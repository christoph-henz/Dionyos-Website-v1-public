<?php declare(strict_types=1);
include_once 'Mailer.php';
class TelegramBot
{

    private string $deliveryID = "6267302899";
    //private string $deliveryID = "-680585011";
    private string $pickupID = "6267302899"; //6267302899   -1002432834012
    //private string $pickupID = "-654631454";
    private string $reservationID = "6267302899";
    //private string $token = "AAHY1ES3JY6jOMbjcUcXSIcxRbvgek153Bo";
    private string $token = "AAHalCGuXvrYuq-oglaGKQByFPVABZ2xfSw";
    private string $uri = "https://api.telegram.org/bot8184358825:";
    //private string $uri = "https://api.telegram.org/bot2122131811:";

    public function __construct() {
        // Überprüfen, ob die Seite über localhost läuft
        if ($_SERVER['HTTP_HOST'] === 'localhost') {
            // Wenn localhost, setze die ID auf 6267302899
            $this->deliveryID = "6267302899";
            $this->pickupID = "6267302899";
            $this->reservationID = "6267302899";
        } else {
            // Wenn nicht localhost (z.B. Produktionsdomain), setze die ID auf -1002432834012
            $this->deliveryID = "-1002432834012";
            $this->pickupID = "-1002432834012";
            $this->reservationID = "-1002432834012";
        }
    }
    function returnResult($result): void{
        echo <<< EOT
            <nav style="height: auto">
                <h2>Menu</h2>
                    <ul>
                        <li><span class="nav-item"><?php $result?></span></li>
                    </ul>
            </nav>
            
        EOT;
    }

    function sendMessage($url, $str, $markup) {
        // POST-Daten erstellen
        $postData = [
            'chat_id' => $this->reservationID,  // Chat ID
            'text' => $str,                     // Nachrichtentext
            'reply_markup' => $markup           // Inline-Keyboard
        ];

        // cURL Session initialisieren
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // POST Methode verwenden
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // POST-Daten setzen

        // Anfrage ausführen und Ergebnis abholen
        $result = curl_exec($ch);

        // cURL Session schließen
        curl_close($ch);

        return $result;
    }


    public function sendReservationMessage(string $name, string $email, string $phone, string $date, string $time, string $guests, string $message): void
    {
        // URL für die Telegram API
        $url = $this->uri . $this->token . "/sendMessage";

        // Nachricht zusammenbauen
        $str = $this->buildResMessage($name, $email, $phone, $date, $time, $guests, $message);

        // Inline-Keyboard erstellen
        $inlineKeyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '✔️ Reservierung bestätigen', 'callback_data' => 'confirm_reservation_' . $email],
                    ['text' => '❌ Reservierung ablehnen', 'callback_data' => 'decline_reservation_' . $email]
                ]
            ]
        ];

        // Inline-Keyboard als JSON encoden
        $markup = json_encode($inlineKeyboard);

        // Nachricht und Inline-Keyboard senden
        $this->sendMessage($url, $str, $markup);
    }

    private function processReservation(array $reservationData, string $status)
    {
        // Reservierungsdetails extrahieren
        $email = $reservationData['email'];
        $name = $reservationData['name'];
        $phone = $reservationData['phone'];
        $date = $reservationData['date'];
        $time = $reservationData['time'];
        $guests = $reservationData['guests'];
        if ($status === 'confirmed') {
            $subject = "Ihre Reservierung wurde bestätigt";
            $message = "Lieber Gast,\n\n";
            $message .="Ihre Reservierung für den $date um $time für $guests Gäste wurde auf den Namen $name verbucht.\n";
            $message .="Wir freuen uns auf Ihren Besuch.\n\n";
            $message .="Mit freundlichen Grüßen\n";
            $message .="Ihr Team Dionysos";
            Mailer::sendMail($email, $subject, $message);

        } elseif ($status === 'declined') {
            $subject = "Reservierung abgelehnt";
            $message = "Lieber Gast,\n\n";
            $message .= "Vielen Dank für Ihre Reservierungsanfrage. Leider können wir Ihre Reservierung am gewünschten Datum und zur gewünschten Uhrzeit nicht bestätigen.\n";
            $message .= "Wir bitten um Ihr Verständnis und würden uns freuen, wenn Sie einen anderen Termin in Betracht ziehen.\n";
            $message .= "Für Rückfragen stehen wir Ihnen gerne unter der Telefonnummer 06021/25779 zur Verfügung.\n\n";
            $message .= "Mit freundlichen Grüßen\n";
            $message .= "Ihr Team Dionysos";

            // Mailer-Funktion zum Versenden der E-Mail
            Mailer::isendMail($email, $subject, $message);
        }
    }

    private function sendTelegramCallbackAnswer($callbackQueryId, $text) {
        $url = $this->uri . $this->token . "/answerCallbackQuery";

        // Daten für die Antwort auf die Callback Query
        $postData = [
            'callback_query_id' => $callbackQueryId,
            'text' => $text,
            'show_alert' => true // Optional, zeigt eine Alert-Box an
        ];

        // cURL Anfrage zum Senden der Antwort
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData)); // URL-Daten als Query-String
        curl_exec($ch);
        curl_close($ch);
    }

    public function sendDeliveryMessage(DBOrder $order) : void{
        $url = $this->uri . $this->token . "/sendMessage?chat_id=" . $this->deliveryID;
        $str = $this->buildDelMessage($order);

        $str .= "\n\nAdresse:\n";
        $str .= $order->getStreet(). "\n";
        $str .= $order->getPLZ(). "\n";
        $str .= $order->getCity(). "\n";

        $inlineKeyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '✔️ Reservierung bestätigen', 'callback_data' => 'confirm_reservation_' .$order->getEmail()],
                    ['text' => '❌ Reservierung ablehnen', 'callback_data' => 'decline_reservation_' . $order->getEmail()]
                ]
            ]
        ];

        // Inline-Keyboard als JSON encoden
        $markup = json_encode($inlineKeyboard);

        // Nachricht und Inline-Keyboard senden
        $this->sendMessage($url, $str, $markup);
        $this->sendSubmissionMail($order);
    }
    public function sendPickupMessage(DBOrder $order) : void{
        $url = $this->uri . $this->token . "/sendMessage?chat_id=" . $this->pickupID;
        $str = $this->buildDelMessage($order);

        $inlineKeyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '✔️ Reservierung bestätigen', 'callback_data' => 'confirm_reservation_' .$order->getEmail()],
                    ['text' => '❌ Reservierung ablehnen', 'callback_data' => 'decline_reservation_' . $order->getEmail()]
                ]
            ]
        ];

        // Inline-Keyboard als JSON encoden
        $markup = json_encode($inlineKeyboard);

        // Nachricht und Inline-Keyboard senden
        $this->sendMessage($url, $str, $markup);

    }

    /**
     * @param DBOrder $order
     * @return string
     */
    public function buildDelMessage(DBOrder $order): string
    {
        $str = "NEUE ABHOLUNG:\n";
        $str .= "-----------------------------------------------------------------------------\n";
        $str .= "Name: " . $order->getName(). "\n";
        $str .= "Telefon: " . $order->getPhoneNumber() . "\n";
        $str .= "E-Mail: " . $order->getEmail() . "\n";

        $str .= "   Anzahl   |  Nummer   |     Name\n";
        $str .= "-----------------------------------------------------------------------------\n";

        foreach ($order->getArticles() as $a) {
            $str .= "       ". $a->getQuantity() . "x                  " . $a->getArticleID() ."                 ". $a->getArticleName() . "\n";
        }
        if(!empty($order->getNote())) {
            $str .= "\n-----------------------------------------------------------------------------\n";
            $str .= "Zusätzliche Anmerkungen:\n";
            $str .= $order->getNote() . "\n";
        }

        return $str;
    }

    public function buildDelMail(DBOrder $order): string
    {
        $str = "Lieber Kunde,\n\n";
        $str .= "Wir haben Ihre Bestellung erhalten.";
        $str .= "Ihre Bestellung:\n";

        foreach ($order->getArticles() as $a) {
            $str .= "       ". $a->getQuantity() . "x                  " . $a->getArticleID() ."                 ". $a->getArticleName() . "\n";
        }
        if(!empty($order->getNote())) {
            $str .= "\n-----------------------------------------------------------------------------\n";
            $str .= "Zusätzliche Anmerkungen:\n";
            $str .= $order->getNote() . "\n";
        }
        $str .= "\n\n Kommen Sie zur Abholung auf einen Ouzo vorbei.\n";
        $str .= "Mit freundlichen Grüßen\n";
        $str .= "Ihr Team Dionysos";

        return $str;
    }

    public function buildResMessage(string $name,string $email,string $phone,string $date,string $time,string $guests,string $message): string
    {
        $str = "NEUE RESERVIERUNG:\n";
        $str .= "-----------------------------------------------------------------------------\n";
        $str .= "Name: " . $name. "\n";
        $str .= "Telefon: " . $phone . "\n";
        $str .= "E-Mail: " . $email . "\n";

        $str .= "          Datum          |         Uhrzeit         |          Anzahl         \n";
        $str .= "-----------------------------------------------------------------------------\n";

        $str .= "       " . date('d.m.Y', strtotime($date)) . "                " . $time . "                                 " . $guests . "\n";
        if(!empty($message)){
            $str .="\n-----------------------------------------------------------------------------\n";
            $str .="Zusätzliche Anmerkungen:\n";
            $str .= $message . "\n";

        }

        
        return $str;
    }

    private function sendSubmissionMail(DBOrder $order) :void{
        $message = $this->buildDelMail($order);
        Mailer::isendMail($order->getEmail(),"Ihre Bestellung",$message);
    }

}