<?php declare(strict_types=1);
class TelegramBot
{

    private string $deliveryID = "***********";
    private string $pickupID = "**********";
    private string $reservationID = "************";
    private string $token = "***************";
    private string $uri = "https://api.telegram.org/bot***************:";

    public function __construct() {
        // Überprüfen, ob die Seite über localhost läuft
        if ($_SERVER['HTTP_HOST'] === 'localhost') {
            // Wenn localhost, setze die ID auf **********
            $this->deliveryID = "**********";
            $this->pickupID = "**********";
            $this->reservationID = "**********";
        } else {
            // Wenn nicht localhost (z.B. Produktionsdomain), setze die ID auf -**************
            $this->deliveryID = "-**************";
            $this->pickupID = "-**************";
            $this->reservationID = "-**************";
        }
    }

    function sendMessage($url, $str/*, $markup*/) : string{
        // POST-Daten erstellen
        $postData = [
            'chat_id' => $this->reservationID,  // Chat ID
            'text' => $str//,                     // Nachrichtentext
            //'reply_markup' => $markup           // Inline-Keyboard
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

        // Nachricht und Inline-Keyboard senden
        $this->sendMessage($url, $str/*, $markup*/);
    }

    public function sendDeliverMessage(DBOrder $order) : void{
        $url = $this->uri . $this->token . "/sendMessage?chat_id=" . $this->deliveryID;
        $str = $this->buildDeliverMessage($order);

        // Nachricht und Inline-Keyboard senden
        $this->sendMessage($url, $str/*, $markup*/);
    }
    public function sendPickupMessage(DBOrder $order) : void{
        $url = $this->uri . $this->token . "/sendMessage?chat_id=" . $this->pickupID;
        $str = $this->buildPickupMessage($order);

        // Nachricht und Inline-Keyboard senden
        $this->sendMessage($url, $str/*, $markup*/);
    }

    public function buildDeliverMessage(DBOrder $order): string
    {
        $str = "NEUE LIEFERUNG:\n";
        $str .= "-----------------------------------------------------------------------------\n";
        $str .= "Name: " . $order->getName(). "\n";
        $str .= "Telefon: " . $order->getPhoneNumber() . "\n";
        $str .= "E-Mail: " . $order->getEmail() . "\n";
        $str .= "-----------------------------------------------------------------------------\n";
        $str .= "Adresse: " . $order->getStreet() . " " . $order->getPLZ() . " " . $order->getCity() . "\n";
        $str .= "-----------------------------------------------------------------------------\n";

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

    /**
     * @param DBOrder $order
     * @return string
     */
    public function buildPickupMessage(DBOrder $order): string
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

    public function buildPickupMail(DBOrder $order): string
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
}