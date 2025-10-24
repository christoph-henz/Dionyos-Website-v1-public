<?php
include_once 'Page.php';
include_once 'bot/TelegramBot.php';

// Konfiguration der Restaurant-E-Mail-Adresse
define('RESTAURANT_EMAIL', 'reservierungen@restaurantxyz.de'); // Ersetze dies mit der tatsächlichen E-Mail-Adresse deines Restaurants

// Optional: Konfiguration für Bestätigungs-E-Mail an den Benutzer
define('EMAIL_FROM', 'info@dionysos-aburg.de'); // Ersetze dies mit deiner Absender-E-Mail-Adresse
define('EMAIL_SUBJECT_USER', 'Reservierungsbestätigung - Restaurant XYZ');
define('EMAIL_SUBJECT_RESTAURANT', 'Neue Tischreservierung');
define('EMAIL_REPLY_TO', 'info@dionysos-aburg.de'); // Optional: Antwort-Adresse
class ReservationSubmit extends Page
{
    protected function additionalMetaData()
    {
        echo <<< EOT
            <link rel="stylesheet" type="text/css" href="style/contentbox.css"/>
            <link rel="stylesheet" type="text/css" href="style/galerie.css"/>
            <link rel="stylesheet" type="text/css" href="style/navigation.css"/>
            <link type="text/css" rel="stylesheet" href="style/imenu_style.css">
            <link type="text/css" rel="stylesheet" href="style/form.css">
            <link type="text/css" rel="stylesheet" href="style/cart.css">
            <link type="text/css" rel="stylesheet" href="style/impressum.css">
        EOT;
    }

    protected function generateView():void{
        $this->generatePageHeader("Reservierung");
        $this->printOverview();
        $this->generatePageFooter();

        echo <<< EOT
            <script>setMenuHeight()</script>
            <script>initTouchEvents()</script>
        EOT;
    }

    protected function roundToNearestHalfHour(string $time): string {
        // Erstelle ein DateTime-Objekt aus der übergebenen Zeit
        $datetime = new DateTime($time);

        $minutes = $datetime->format('i');
        $roundedMinutes = ($minutes < 15) ? 0 : (($minutes < 45) ? 30 : 0);

        if ($roundedMinutes === 0 && $minutes >= 45) {
            $datetime->modify('+1 hour');
        }

        // Setze die gerundeten Minuten
        $datetime->setTime($datetime->format('H'), $roundedMinutes, 0);

        // Gebe die gerundete Zeit als String zurück (im Format H:i)
        return $datetime->format('H:i');
    }

    protected function processReceivedData(): void
    {
        parent::processReceivedData();

        /*if(!$this->cookieHandler->isAllowOrder()){
            header("HTTP/1.1 301 See Other");
            header("Location: /");
            $this->generateBanner("Um das Reserviersystem zu nutzen müssen die Cookies zum Bestellsystem akzeptiert werden");
        }*/

    }

    protected function sanitize_output($data): string {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * @throws DateMalformedStringException
     */
    protected function printOverview(): void {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $name = isset($_POST['name']) ? $this->sanitize_output(trim($_POST['name'])) : '';
            $email = isset($_POST['email']) ? $this->sanitize_output(trim($_POST['email'])) : '';
            $phone = isset($_POST['phone']) ? $this->sanitize_output(trim($_POST['phone'])) : '';
            $date = isset($_POST['date']) ? $this->sanitize_output(trim($_POST['date'])) : '';
            $time = isset($_POST['time']) ? $this->sanitize_output(trim($_POST['time'])) : '';
            $guests = isset($_POST['guests']) ? $this->sanitize_output(trim($_POST['guests'])) : '';
            $message = isset($_POST['message']) ? $this->sanitize_output(trim($_POST['message'])) : '';

            // Validierung der Daten
            $errors = [];

            $stmt = $this->_database->prepare("SELECT Reservation FROM Settings");

            if ($stmt === false) {
                die("Fehler beim Vorbereiten der SQL-Abfrage: " . $this->_database->error);
            }

            $reservationOn = false;
            // SQL-Abfrage ausführen
            if ($stmt->execute()) {
                // Ergebnis binden
                $stmt->bind_result($reservation);
                // Ergebnis abrufen
                if ($stmt->fetch()) {
                    $reservationOn = $reservation == 0 ? false : true;
                }
            } else {
                die("Fehler beim Abfragen der Einstellungen: " . $stmt->error);
            }

            // Prepared Statement schließen
            $stmt->close();

            //$settings = json_decode(file_get_contents('settings.json'), true); // Pfad zur settings.json anpassen
            //$reservationOn = $settings['settings']['reservationOn'] ?? false;

            // Überprüfen, ob die benötigten Einstellungen vorhanden sind

            if ($reservationOn === false) {
                $errors[] = "Unser Reserviersystem ist zurzeit deaktiviert. Versuchen Sie es später erneut.";
            }
            if (empty($name)) {
                $errors[] = "Name ist erforderlich.";
            }

            if (empty($email)) {
                $errors[] = "E-Mail-Adresse ist erforderlich.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Ungültige E-Mail-Adresse.";
            }

            if (empty($phone)) {
                $errors[] = "Telefonnummer ist erforderlich.";
            } elseif (!preg_match('/^[0-9]{3,}$/', $phone)) {
                $errors[] = "Ungültige Telefonnummer. Mindestens 3 Ziffern erforderlich.";
            }

            if (empty($date)) {
                $errors[] = "Reservierungsdatum ist erforderlich.";
            } else {
                $today = date('Y-m-d');
                if ($date < $today) {
                    $errors[] = "Das Reservierungsdatum darf nicht in der Vergangenheit liegen.";
                }
            }

            $now = new DateTime();
            $reservationDateTime = new DateTime($date . ' ' . $time);

            if (empty($time)) {
                $errors[] = "Zeitangabe ist erforderlich.";
            }

            // Runden der Reservierungszeit auf halbe Stunden
            $time = $this->roundToNearestHalfHour($time);

            // Überprüfen, ob die Reservierung in der Vergangenheit liegt
            if ($reservationDateTime < $now) {
                $errors[] = "Das Datum und die Uhrzeit dürfen nicht in der Vergangenheit liegen.";
            }
            // Überprüfen, ob die Reservierung für den Folgetag ist
            $tomorrow = new DateTime('tomorrow');
            if ($reservationDateTime->format('Y-m-d') === $tomorrow->format('Y-m-d')) {
                if ($now->format('H') >= 22) {
                    $errors[] = 'Reservierungen für den Folgetag müssen vor 22:00 Uhr erfolgen.';
                }
            }

            // Spezifische Zeitfenster für die Wochentage und Sonntag
            $dayOfWeek = $reservationDateTime->format('N'); // 1 (Montag) bis 7 (Sonntag)
            $reservationHour = (int)$reservationDateTime->format('H');
            $reservationMinute = (int)$reservationDateTime->format('i');

            if ($dayOfWeek == 7) { // Sonntag
                if ($reservationHour < 11 || ($reservationHour == 11 && $reservationMinute < 30) || $reservationHour > 20) {
                    $errors[] = "Am Sonntag sind Reservierungen nur von 11:30 bis 20:00 Uhr möglich.";
                }
            } elseif ($dayOfWeek >= 2 && $dayOfWeek <= 6) { // Dienstag bis Samstag
                if ($reservationHour < 17 || ($reservationHour == 17 && $reservationMinute < 30) || $reservationHour > 20) {
                    $errors[] = "Reservierungen sind von Dienstag bis Samstag nur von 17:30 bis 20:00 Uhr möglich.";
                }
            } else {
                $errors[] = "Montag ist Ruhetag.";
            }

            if (empty($guests)) {
                $errors[] = "Anzahl der Gäste ist erforderlich.";
            } elseif (!is_numeric($guests) || intval($guests) != $guests) {
                $errors[] = "Anzahl der Gäste muss eine gültige Zahl sein.";
            } elseif ($guests < 1 || $guests > 20) {
                $errors[] = "Anzahl der Gäste muss zwischen 1 und 20 liegen.";
            }


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Überprüfen, ob das Token vorhanden ist und übereinstimmt
                if (isset($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
                    // Das Token ist gültig, fahre mit der Reservierung fort
                }
            } else {
                $error[] = "Doppelte Reservierung erkannt oder ungültiges Token.";
            }

            // Wenn keine Fehler vorhanden sind, die Daten anzeigen
            if (empty($errors)) {
                $reservationId = uniqid();

                $restaurant_subject = EMAIL_SUBJECT_RESTAURANT;
                $restaurant_message = "<h2>Neue Tischreservierung</h2>";
                $restaurant_message .= "<p><strong>Name:</strong> $name</p>";
                $restaurant_message .= "<p><strong>E-Mail:</strong> $email</p>";
                $restaurant_message .= "<p><strong>Telefonnummer:</strong> $phone</p>";
                $restaurant_message .= "<p><strong>Datum:</strong> " . date('d.m.Y', strtotime($date)) . "</p>";
                $restaurant_message .= "<p><strong>Uhrzeit:</strong> $time</p>";
                $restaurant_message .= "<p><strong>Anzahl der Gäste:</strong> $guests</p>";

                if (!empty($message)) {
                    $restaurant_message .= "<p><strong>Besondere Wünsche oder Anmerkungen:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>";
                }

                // Bestätigungs-Button zur Reservierungs-E-Mail
                $confirmation_link = "https://www.dionysos-aburg.de/confirm_reservation.php?id=$reservationId&email=" . urlencode($email);
                $confirmation_button = "<a href='$confirmation_link' style='padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Reservierung Bestätigen</a>";

                // Füge den Bestätigungs-Button zur Nachricht hinzu
                $restaurant_message .= "\n\nBitte klicken Sie auf den folgenden Link, um die Reservierung zu bestätigen:\n";
                $restaurant_message .= $confirmation_button;
                $bot = new TelegramBot();
                $bot->sendReservationMessage($name,$email,$phone,$date,$time,$guests,$message);
                // Wenn keine Fehler vorhanden sind, speichere die Reservierung
                if (empty($errors)) {
                    // Reservierungsdaten in die Datenbank speichern
                    $stmt = $this->_database->prepare("INSERT INTO Reservations (name, email, phone, date, time, guests, message, state) 
                                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                    if ($stmt === false) {
                        die("Fehler beim Vorbereiten der SQL-Abfrage: " . $this->_database->error);
                    }
                    $state = 0;
                    // Parameter binden
                    $stmt->bind_param("sssssssi", $name, $email, $phone, $date, $time, $guests, $message, $state);

                    // SQL-Abfrage ausführen
                    if ($stmt->execute()) {
                        // Wenn erfolgreich, ID der neuen Reservierung holen
                        $reservationId = $stmt->insert_id;
                    } else {
                        die("Fehler beim Speichern der Reservierung: " . $stmt->error);
                    }

                    // Prepared Statement schließen
                    $stmt->close();
                }
                unset($_SESSION['token']);
                ?>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f1f1f1;
                        margin: 0;
                    }
                    .container {
                        max-width: 800px;
                        margin: 0 auto;
                        background: rgba(36,36,36,0.9);
                        padding: 20px;
                        border-radius: 10px;
                        color: #fff8ed;
                        box-shadow: 0px 0px 7px 7px rgba(0,0,0,0.8);
                    }
                    h1 {
                        text-align: center;
                        color: #89656a;
                    }
                    p {
                        font-size: 1.2em;
                    }
                    .reservation-details {
                        margin-top: 20px;
                    }
                    .reservation-details dt {
                        font-weight: bold;
                    }
                    .reservation-details dd {
                        margin: 0 0 10px 0;
                    }
                    .back-link {
                        display: block;
                        margin-top: 20px;
                        text-align: center;
                    }
                    .back-link a {
                        color: #f39c12;
                        text-decoration: none;
                        font-weight: bold;
                    }
                    .back-link a:hover {
                        text-decoration: underline;
                    }
                </style>
                <div class="container">
                    <h1>Reservierung Erfolgreich</h1>
                    <p>Falls wir Ihre Reservierung nicht entgegennehmen können, melden wir uns in Kürze per Mail bei Ihnen.</p>
                    <p>Vielen Dank für Ihre Reservierung! Hier sind Ihre Details:</p>
                    <dl class="reservation-details">
                        <dt>Name:</dt>
                        <dd><?php echo $name; ?></dd>

                        <dt>E-Mail-Adresse:</dt>
                        <dd><?php echo $email; ?></dd>

                        <dt>Telefonnummer:</dt>
                        <dd><?php echo $phone; ?></dd>

                        <dt>Reservierungsdatum:</dt>
                        <dd><?php echo date('d.m.Y', strtotime($date)); ?></dd>

                        <dt>Uhrzeit:</dt>
                        <dd><?php echo $time; ?></dd>

                        <dt>Anzahl der Gäste:</dt>
                        <dd><?php echo $guests; ?></dd>

                        <?php if (!empty($message)): ?>
                            <dt>Besondere Wünsche oder Anmerkungen:</dt>
                            <dd><?php echo nl2br($message); ?></dd>
                        <?php endif; ?>
                    </dl>
                    <div class="back-link">
                        <a href="MainPage.php">Zurück zur Hauptseite</a>
                    </div>
                </div>
            <?php
            }
            if (!empty($errors)) {
                // Fehler anzeigen
            ?>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f1f1f1;
                        margin: 0;
                    }
                    .container {
                        max-width: 800px;
                        margin: 0 auto;
                        background: rgba(36,36,36,0.9);
                        padding: 20px;
                        border-radius: 10px;
                        color: #fff8ed;
                        box-shadow: 0px 0px 7px 7px rgba(0,0,0,0.8);
                    }
                    h1 {
                        text-align: center;
                        color: #89656a;
                    }
                    ul {
                        list-style: none;
                        padding: 0;
                    }
                    ul .error {
                        background: #e74c3c;
                        padding: 10px;
                        margin-bottom: 10px;
                        border-radius: 5px;
                    }
                    .back-link {
                        display: block;
                        margin-top: 20px;
                        text-align: center;
                    }
                    .back-link a {
                        color: #f39c12;
                        text-decoration: none;
                        font-weight: bold;
                    }
                    .back-link a:hover {
                        text-decoration: underline;
                    }
                </style>
                <div class="container">
                    <h1>Reservierung Fehler</h1>
                    <p>Bei Ihrer Reservierung sind folgende Fehler aufgetreten:</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li class="error"><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="back-link">
                        <a href="javascript:history.back()">Zurück zum Formular</a>
                    </div>
                </div>
                <?php
            }
        } else {
            // Formular wurde nicht mit POST gesendet
            header("Location: MainPage.php"); // Umleitung zur Hauptseite oder einer Fehlerseite
            exit();
        }
    }

    protected function footerScripts()
    {

    }

    private function generateNav(){
        echo <<< EOT
            <nav style="height: auto">
                <h2>Menu</h2>
                    <ul>
                        <li><span class="nav-item"><a href="MainPage.php">Zur Startseite</a></span></li>
                    </ul>
            </nav>
            
        EOT;
    }

    public static function main():void
    {
        try {
            $page = new ReservationSubmit();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }


}

ReservationSubmit::main();