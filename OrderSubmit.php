<?php
include_once 'Page.php';
include_once 'DBOrder.php';
include_once 'bot/TelegramBot.php';
class OrderSubmit extends Page
{
    const MIN_ORDER_VAL = 40;
    private $articleIDs;
    private $submitted;
    private $order;
    private $bot;
    private $isClosed;
    public function __construct()
    {
        parent::__construct();
        $this->order = new DBOrder();
        $this->bot = new TelegramBot();
    }

    protected function processReceivedData(): void
    {
        if (isset($_POST['articles'])) {
            $this->handleArticleSubmission();
        } else {
            $this->handleOrderSubmission();
            //echo <<< EOT
            //<nav style="height: auto">
            //    <h2 style="color: #e74c3c">Hinweis</h2>
            //        <p style="color: #e74c3c">Unerwarteter Fehler aufgetreten</p>
            //</nav>
            //EOT;
        }
    }

    private function getSubmissionString(): string
    {
        return "Ihre Bestellung wurde erfolgreich entgegengenommen.";
    }

    /**
     * Verarbeitet die Bestellung (Abholung oder Lieferung)
     * @return void
     */
    public function handleOrderSubmission(): void
    {

        if (isset($_SESSION['order'])) {
            $this->order = $_SESSION['order'];
            unset($_SESSION['order']);
            if (isset($_POST["ordertype"])) {
                $this->submitted = true;
                $type = $_POST["ordertype"];

                if ($type === "del") {
                    // Lieferung: Name, Tel, Straße, PLZ, Ort sind erforderlich
                    if (isset($_POST["name"], $_POST["tel"], $_POST["street"], $_POST["plz"], $_POST["city"])) {
                        // Bestellungsdetails setzen
                        $this->order->setName(htmlspecialchars($_POST["name"], ENT_QUOTES, 'UTF-8'));
                        $this->order->setStreet(isset($_POST["street"]) ? htmlspecialchars($_POST["street"], ENT_QUOTES, 'UTF-8') : '');
                        $this->order->setPLZ(isset($_POST["plz"]) ? htmlspecialchars($_POST["plz"], ENT_QUOTES, 'UTF-8') : '');
                        $this->order->setCity(isset($_POST["city"]) ? htmlspecialchars($_POST["city"], ENT_QUOTES, 'UTF-8') : '');
                        //$this->order->setAddress(htmlspecialchars("{$_POST["street"]}, {$_POST["plz"]}, {$_POST["city"]}", ENT_QUOTES, 'UTF-8'));
                        $this->order->setPhoneNumber(htmlspecialchars($_POST["tel"], ENT_QUOTES, 'UTF-8'));
                        $this->order->setEmail(isset($_POST["mail"]) ? htmlspecialchars($_POST["mail"], ENT_QUOTES, 'UTF-8') : '');
                        $this->order->setNote(isset($_POST["note"]) ? htmlspecialchars($_POST["note"], ENT_QUOTES, 'UTF-8') : '');

                        // Bestellung in die Datenbank speichern
                        $orderId = $this->saveOrderToDatabase($this->order, true);

                        // Telegram Nachricht senden
                        $this->bot->sendDeliverMessage($this->order);
                        //$this->generateOrderView();
                        // E-Mail senden, falls vorhanden

                    } else {
                        // Fehlende Post-Daten
                        header("HTTP/1.1 301 See Other");
                        header("Location: Order.php");
                        exit();
                    }

                } elseif ($type === "pick") {
                    // Abholung: Name, Tel sind erforderlich
                    if (isset($_POST["name"], $_POST["tel"])) {
                        // Bestellungsdetails setzen
                        $this->order->setName(htmlspecialchars($_POST["name"], ENT_QUOTES, 'UTF-8'));
                        $this->order->setPhoneNumber(htmlspecialchars($_POST["tel"], ENT_QUOTES, 'UTF-8'));
                        $this->order->setEmail(isset($_POST["mail"]) ? htmlspecialchars($_POST["mail"], ENT_QUOTES, 'UTF-8') : '');
                        $this->order->setNote(isset($_POST["note"]) ? htmlspecialchars($_POST["note"], ENT_QUOTES, 'UTF-8') : '');

                        // Bestellung in die Datenbank speichern
                        $orderId = $this->saveOrderToDatabase($this->order, false);

                        // Telegram Nachricht senden
                        $this->bot->sendPickupMessage($this->order);
                        //$this->generateOrderView();
                        // E-Mail senden, falls vorhanden

                    } else {
                        // Fehlende Post-Daten
                        header("HTTP/1.1 301 See Other");
                        header("Location: Order.php");
                        exit();
                    }

                } else {
                    // Manipulationsversuch oder falscher Bestelltyp
                    header("HTTP/1.1 301 See Other");
                    header("Location: Order.php");
                    exit();
                }
            }
        } else {
            header("HTTP/1.1 301 See Other");
            header("Location: Order.php");
            exit();
        }

    }

    private function saveOrderToDatabase(DBOrder $order, bool $isDelivery): int
    {
        // Bestellung einfügen
        if($isDelivery) {
            $stmtOrder = $this->_database->prepare("
    INSERT INTO `Order` 
    (`Name`, `Email`, `Phone`, `Street`, `PostalCode`, `City`, `Message`, `IsDelivery`, `State`) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");
            if (!$stmtOrder) {
                throw new Exception("Fehler beim Vorbereiten der Bestellung: " . $this->_database->error);
            }

            $name = $order->getName();
            $email = $order->getEmail();
            $phoneNumber = $order->getPhoneNumber();
            $street = empty($order->getStreet()) ? null : $order->getStreet();
            $postalCode = empty($order->getPLZ()) ? null : $order->getPLZ(); // oder NULL, falls nicht angegeben
            $city = empty($order->getCity()) ? null : $order->getCity(); // oder NULL
            $note = $order->getNote(); // oder NULL
            $state = 0; // Standardstatus
            //$deliveredOn = (new DateTime())->modify($isDelivery ? '+50 minutes' : '+30 minutes')->format('Y-m-d H:i:s');

            $stmtOrder->bind_param(
                "sssissssi", // 10 Variablen
                $name,
                $email,
                $phoneNumber,
                $street,
                $postalCode,
                $city,
                $note,
                $isDelivery,
                $state/*,
                $deliveredOn*/
            );



        } else {
            $stmtOrder = $this->_database->prepare("
    INSERT INTO `Order` 
    (`Name`, `Email`, `Phone`, `Message`, `IsDelivery`, `State`) 
    VALUES (?, ?, ?, ?, ?, ?)
");
            if (!$stmtOrder) {
                throw new Exception("Fehler beim Vorbereiten der Bestellung: " . $this->_database->error);
            }

            $name = $order->getName();
            $email = $order->getEmail();
            $phoneNumber = $order->getPhoneNumber();
            $note = $order->getNote(); // oder NULL
            $state = 0; // Standardstatus
            //$deliveredOn = (new DateTime())->modify($isDelivery ? '+50 minutes' : '+30 minutes')->format('Y-m-d H:i:s');

            $stmtOrder->bind_param(
                "sssssi", // 7 Variablen
                $name,
                $email,
                $phoneNumber,
                $note,
                $isDelivery,
                $state/*,
                $deliveredOn*/
            );

        }

        if (!$stmtOrder->execute()) {
            throw new Exception("Fehler beim Einfügen der Bestellung: " . $stmtOrder->error);
        }

        // Letzte eingefügte ID (Bestellungs-ID)
        $orderId = $stmtOrder->insert_id;

        // Artikel einfügen
        $stmtOrderMenu = $this->_database->prepare("
        INSERT INTO `OrderMenu` (`OrderId`, `MenuPLU`, `Amount`) VALUES (?, ?, ?)
    ");
        if (!$stmtOrderMenu) {
            throw new Exception("Fehler beim Vorbereiten der Artikel: " . $this->_database->error);
        }

        foreach ($order->getArticles() as $article) {
            $articleId = $article->getArticleId();
            $quantity = $article->getQuantity();
            $stmtOrderMenu->bind_param(
                "isi",
                $orderId,
                $articleId,
                $quantity
            );

            if (!$stmtOrderMenu->execute()) {
                throw new Exception("Fehler beim Einfügen des Artikels: " . $stmtOrderMenu->error);
            }
        }

        // Ressourcen schließen
        //$stmtOrder->close();
        //$stmtOrderMenu->close();

        // Rückgabe der Bestellungs-ID
        return $orderId;
    }

    public function handleArticleSubmission(): void
    {
        // Überprüfen, ob Artikel-Daten vorhanden und ein Array sind
        if (!isset($_POST['articles']) || !is_array($_POST['articles'])) {
            error_log("Ungültige Artikeldaten erhalten.");
            header("HTTP/1.1 301 See Other");
            header("Location: Order.php");
            exit();
        }

        // Speichern der übergebenen Artikel-IDs
        $this->articleIDs = $_POST['articles'];

        // Verfügbare Artikel aus der Datenbank abrufen
        $availableArticles = $this->getAvailableArticlesFromDB();

        foreach ($this->articleIDs as $id) {
            $articleData = null;

            // Suche den Artikel anhand der ID
            foreach ($availableArticles as $article) {
                if ($article['id'] === $id) {
                    $articleData = $article;
                    break;
                }
            }

            if ($articleData) {
                // Artikelinformationen abrufen
                $article = new DBArticle(
                    htmlspecialchars($articleData['id'], ENT_QUOTES, 'UTF-8'), // Artikel-ID
                    htmlspecialchars($articleData['name'], ENT_QUOTES, 'UTF-8'), // Artikelname
                    (float)$articleData['price'], // Artikelpreis
                    1 // Standardmenge
                );

                // Artikel zur Bestellung hinzufügen
                $this->order->addArticle($article);
            } else {
                // Loggen, wenn eine ungültige Artikel-ID übergeben wurde
                error_log("Undefinierte Artikel-ID: {$id}");
                // Optional: Weitere Maßnahmen, z.B. Fehlernachricht an Benutzer
            }
        }

        // Bestellung in der Session speichern
        $_SESSION['order'] = $this->order;
        $this->submitted = false;
    }

    private function getAvailableArticlesFromDB(): array
    {
        $articles = [];

        // SQL-Abfrage für die Artikel
        $query = "SELECT Plu AS id, Name AS name, Price AS price FROM Menu";
        $result = $this->_database->query($query);

        if (!$result) {
            throw new Exception("Datenbankfehler: " . $this->_database->error);
        }

        // Verarbeite die Ergebnisse
        while ($row = $result->fetch_assoc()) {
            $articles[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => (float)$row['price']
            ];
        }

        //$this->_database->close();
        return $articles;
    }

    private function generateOrderView() : void {
        $this->generatePageHeader("Bestellung");
        echo <<<EOT
            <div id="main-wrapper">
                <div id="content-wrapper-alt">
        EOT;
        if ($this->submitted) {
            $this->printCompletion();
        } else {
            $this->printOverview();
        }
        echo <<<EOT
                </div>
            </div>
        EOT;
        $this->generatePageFooter();
    }

    protected function printOverview(): void
    {
        echo <<<EOT
            <div class="">
                <div class="headline-sec">
                    <h1><span class="highlight">B</span>estelldetails</h1>
                </div>
                <div class="content-sec">
        EOT;
        $this->generateOverview();
        $this->generateForm();
        echo <<<EOT
                </div>
            </div>
        EOT;
    }
    protected function printCompletion(): void
    {
        echo <<<EOT
            <div class="">
                <div class="headline-sec">
                    <h1><span class="highlight">B</span>estellung abgeschlossen</h1>
                </div>
                <div class="content-sec">
                    <div id="success">                
        EOT;
        $this->generateOverview();
        $this->generateDetails();
        echo <<<EOT

                </div>
            </div>
        EOT;
    }
    protected function generateOverview(): void
    {
        echo <<<EOT
            <div id="overview">
                <h2>Gerichte</h2>
                <ul>
        EOT;
        // Artikel anzeigen
        $articles = $this->order->getArticles();

        foreach ($articles as $a) {
            echo "<li>{$a->getQuantity()}x {$a->getArticleName()} - {$a->sumUp()} €</li>";
        }

        echo <<<EOT
                </ul>
                <p>Summe: {$this->order->getSum()} €</p>
            </div>
        EOT;
    }
    protected function generateDetails(): void
    {
        echo <<<EOT
            <div id="user-details">
                <div class="detail-container" id="detail-name">
                    Name: {$this->order->getName()}
                </div>
                <div class="detail-container" id="detail-number">
                    Tel: {$this->order->getPhoneNumber()}
                </div>
            EOT;
        if(!empty($this->order->getStreet())){
                echo<<< EOT
                <div class="detail-container" id="detail-address">
                    Adresse: {$this->order->getStreet()}, {$this->order->getPLZ()} {$this->order->getCity()}
                </div>
                EOT;
        }
        echo <<<EOT
                <div class="detail-container" id="detail-mail">
                    Email: {$this->order->getEmail()}
                </div>
            EOT;
        if(!empty($this->order->getNote())){
            echo<<< EOT
                <div class="detail-container" id="detail-note">
                    Anmerkungen: {$this->order->getNote()}
                </div>
                EOT;
        }
        echo <<<EOT
                <div class="detail-container" id="detail-text">
                    <p>Wenn Sie eine Email-Adresse eingetragen haben bekommen Sie eine Zusammenfassung per Mail (bald verfügbar)</p>           
        EOT;
        if(!empty($this->order->getStreet()))
        echo <<<EOT
                    <p>Unsere aktuelle Lieferzeit beträgt 50 Minuten</p>
                </div>          
        EOT;
        else{
            echo <<<EOT
                    <p>Sie können ihre Bestellung in 20-30 Minuten abholen</p
                </div>
                </div>
        EOT;
        }
    }
    protected function generateForm(): void
    {
        $settings = json_decode(file_get_contents('settings.json'), true); // Pfad zur settings.json anpassen
        $stmt = $this->_database->prepare("SELECT ToGo,Delivery,MinOrder FROM Settings");

        if ($stmt === false) {
            die("Fehler beim Vorbereiten der SQL-Abfrage: " . $this->_database->error);
        }

        $isOpen = false;        // Standardwert für $isOpen
        $deliverOn = false;     // Standardwert für $deliverOn
        $deliverLimit = 0;      // Standardwert für $deliverLimit

        // SQL-Abfrage ausführen
        if ($stmt->execute()) {
            // Ergebnis binden
            $stmt->bind_result($togo, $delivery, $minOrder);

            // Ergebnis abrufen
            if ($stmt->fetch()) {
                // Boolean-Werte interpretieren
                $deliverOn = $togo == 1;         // true, wenn ToGo = 1, sonst false
                $isOpen = $delivery == 1; // true, wenn Delivery = 1, sonst false
                $deliverLimit = (int) $minOrder; // MinOrder wird als Ganzzahl interpretiert
            } else {
                echo "Keine Daten gefunden.";
            }
        } else {
            die("Fehler beim Abfragen der Einstellungen: " . $stmt->error);
        }
        $currentDay = date('N'); // 1 = Montag, 7 = Sonntag
        $currentHour = date('G'); // Aktuelle Stunde im 24-Stunden-Format (0 bis 23)

        // Wenn es Montag ist, ist das Restaurant immer geschlossen
        if ($currentDay == 1) {
            $this->isClosed = true;
        }
        // Wenn es Sonntag ist und die Uhrzeit zwischen 12 und 21 liegt
        elseif ($currentDay == 7 && $currentHour < 11 && $currentHour > 21) {
            $this->isClosed = true;
        }
        // Für alle anderen Tage außer Montag: Wenn die Uhrzeit zwischen 18 und 21 liegt
        elseif ($currentDay >= 2 && $currentDay <= 6 && $currentHour < 17 && $currentHour > 21 ) {
            $this->isClosed = true;
        }
        //$this->isClosed = false;
        $formHidden = !$isOpen||$this->isClosed ? 'hidden="hidden"' : '';
        echo <<<EOT
            <style>
                form{
                     background: #212121;
                }
                .form-control {
                    width: 90%;
                    height: auto;
                    padding: 12px 20px;
                    margin: 8px 10px 8px 10px;
                    display: block;
                    border-radius: 4px;
                    box-sizing: border-box;
                    font-size: 16px;
                    transition: border-color 0.3s, box-shadow 0.3s;
                }
                
                /* Fokus-Stile */
                .form-control:focus {
                    border-color: #66afe9;
                    box-shadow: 0 0 8px rgba(102, 175, 233, 0.6);
                    outline: none;
                }
                
                /* Hover-Effekt */
                .form-control:hover {
                    border-color: #404040;
                }
                
                /* Disabled Zustand */
                .form-control:disabled, 
                .form-control[readonly] {
                    background-color: #414141;
                    cursor: not-allowed;
                    opacity: 1;
                }
                
                /* Stile für Fehlermeldungen */
                .form-control.error {
                    border-color: #e74c3c;
                    box-shadow: 0 0 8px rgba(231, 76, 60, 0.6);
                }
                
                .form-control.error:focus {
                    border-color: #c0392b;
                    box-shadow: 0 0 8px rgba(192, 57, 43, 0.6);
                }
                
                /* Labels */
                form label {
                    font-weight: 600;
                    color: #333333;
                    display: block;
                    margin-bottom: 5px;
                }
                
                /* Stile für select Elemente */
                .form-control.select-control {
                    appearance: none; /* Entfernt das Standard-Dropdown-Pfeilsymbol */
                    background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="5"><path fill="%23333" d="M0 0l5 5 5-5z"/></svg>');*/
                    background-repeat: no-repeat;
                    background-position: right 10px center;
                    background-size: 10px 5px;
                    cursor: pointer;
                }
                
                #pcpbtn {
                    width: 90%;
                    height: auto;
                    padding: 12px 20px;
                    margin: 8px 10px 8px 10px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    appearance: none; /* Entfernt das Standard-Dropdown-Pfeilsymbol */
                    cursor: pointer;
                }
                
                #delbtn {
                    width: 90%;
                    height: auto;
                    padding: 12px 20px;
                    margin: 8px 10px 8px 10px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    appearance: none; /* Entfernt das Standard-Dropdown-Pfeilsymbol */
                    cursor: pointer;
                }
                
                #pcpbtn:hover {
                    background: #856404;
                }
                
                /* Responsive Anpassungen */
                @media (max-width: 600px) {
                    .form-control {
                        font-size: 14px;
                        padding: 10px 15px;
                    }
                }
            </style>
             <div class="order">
                <div class="form-wrapper">
                    <form action="" method="post" <?php echo $formHidden; ?>
                        <div class="form-group order-selection">
                            <label>Bestellart</label>
                            <select onChange="toggleDeliveryType()" class="form-control order-type" name="ordertype">
                                <option value="pick">
                                    Abholung
                                </option>
        EOT;
        if($this->order->getSum() >= $deliverLimit && $deliverOn && $isOpen) {
            echo <<< EOT
                                <option value="del">
                                    Lieferung
                                </option>
        EOT;
        }
        elseif(!$deliverOn && $isOpen) {
            echo <<< EOT
                                <option value="del" disabled>
                                    Lieferung (derzeit nicht verfügbar)
                                </option>
        EOT;
        }

        echo <<<EOT
                            </select>
                        </div>

                        <div class="form-inner-sec">
                            <div class="pickup-sec">
                                <div class="form-group"><label>Vollständiger Name *</label> <input type="text" class="form-control" name="name" placeholder="Name" required /></div>

                                <div class="form-group"><label>Telefonnummer *</label> <input type="text" class="form-control" name="tel" placeholder="Telefonnummer" required /></div>

                                <div class="form-group"><label>E-Mail *</label> <input type="email" class="form-control" name="mail" placeholder="E-Mail" required/></div>

                                <div class="form-group">
                                    <label>Anmerkung zur Bestellung</label>
                                    <textarea class="form-control" name="note"></textarea>
                                </div>
                            </div>
                        EOT;
        if ($this->order->getSum() >= self::MIN_ORDER_VAL){
            echo <<<EOT

                            <div class="del-sec delivery-elem">
                                <div class="form-group"><label>Straße *</label> <input type="text" class="form-control" name="street" placeholder="Straße/Nr" required="required" /></div>

                                <!--div class="form-group"><label>Postleitzahl *</label> <input type="text" class="form-control" name="plz" placeholder="PLZ" required="required" /></div-->
                                <div class="form-group"><label>Postleitzahl</label><select class="form-control" name="plz" required="required" >
                                <option value="63739">
                                    63739
                                </option>
                                <option value="63741">
                                    63741
                                </option><option value="63743">
                                    63743
                                </option>
                                </select>
                                </div>
                                <div class="form-group"><label>Ort *</label> <input type="text" class="form-control" name="city" placeholder="Ort" required="required" /></div>
                            </div>
                        
                        EOT;

        }
        echo <<<EOT
                        </div>
                        <div class="law">
                            <div class="form-group">
                                <label>Rechtliches</label>

                                <div class="form-check"><input class="form-check-input" type="checkbox" required name="dsgvo" value="1" /> <label class="form-check-label">Ich habe die Datenschutzverordnung gelesen und stimme dieser zu *</label></div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" required name="agb" value="1" /> <label class="form-check-label">Ich habe die allgemeinen Geschäftsbedingung (AGB) gelesen und stimme diesen zu *</label>
                                </div>
                            </div>
                            <small>Felder markiert mit * sind Pflichtfelder.</small>
                        </div>

                        <input id="pcpbtn" type="submit" value="Abholung" onclick="processPickupOrder(this.form, this.form.name, this.form.tel, this.form.mail, this.form.note, this.form.dsgvo, this.form.agb)"" />

                        <input
                            class="delivery-elem"
                            type="submit"
                            id="delbtn"
                            value="Lieferung"
                            onclick="processDeliveryOrder(this.form, this.form.name, this.form.tel, this.form.mail, this.form.note, this.form.dsgvo, this.form.agb, this.form.street, this.form.plz, this.form.city)"
                        />
                    </form>
                    
                </div>
            </div>

        EOT;
        if (!$isOpen){
            echo <<< EOT
                <nav style="height: auto">
                <h2 style="color: #e74c3c">Hinweis</h2>
                    <p style="color: #e74c3c">Unsere Bestellfunktion ist zurzeit deaktiviert</p>
                    <p><a href="MainPage.php">Zurück zur Hauptseite</a></p>
                </nav>
        EOT;
        }elseif ($this->isClosed){
            echo <<< EOT
                <nav style="height: auto">
                <h2 style="color: #e74c3c">Hinweis</h2>
                    <p style="color: #e74c3c">Unsere Küche ist zurzeit geschlossen</p>
                    <p><a href="MainPage.php">Zurück zur Hauptseite</a></p>
                </nav>
                EOT;
        }

    }

    public static function main(): void
    {
        try {
            $page = new OrderSubmit();
            $page->processReceivedData();
            $page->generateOrderView();
        } catch (Exception $e) {
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
    protected function additionalMetaData():void
    {
        echo <<< EOT
            <link rel="stylesheet" type="text/css" href="style/contentbox.css"/>
            <link rel="stylesheet" type="text/css" href="style/galerie.css"/>
            <link rel="stylesheet" type="text/css" href="style/navigation.css"/>
            <link type="text/css" rel="stylesheet" href="style/imenu_style.css">
            <link type="text/css" rel="stylesheet" href="style/form.css">
            <link type="text/css" rel="stylesheet" href="style/cart.css">

            <script src="menu/js/btn.js"></script>
            <script src="js/ordercheck.js"></script>
            <script src="js/fadein.js"></script>
            
        EOT;
        if ($this->cookieHandler->isAllowGoogle()) {
            echo <<<EOT
            <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cinzel&family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet"/>
        EOT;
        }
    }

    protected function footerScripts():void
    {
        echo <<<EOT
            <script src="js/fadein.js"></script>
        EOT;
    }
}

OrderSubmit::main();