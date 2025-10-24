<?php
include_once 'Page.php';
class Datenschutz extends Page
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
        $this->generatePageHeader("Datenschutz");
        $this->generateNav();
        $this->printDatenschutz();
        $this->generatePageFooter();

        echo <<< EOT
            <script>setMenuHeight()</script>
            <script>initTouchEvents()</script>
        EOT;

    }

    protected function processReceivedData(): void
    {
        parent::processReceivedData();

        /*if(!$this->cookieHandler->isAllowOrder()){
            header("HTTP/1.1 301 See Other");
            header("Location: /");
        }*/

    }


    protected function printDatenschutz():void{
        echo <<< EOT
            <div id="order-infobox">
            <h2><span class="highlight">D</span>atenschutzerklärung</h2>
            <div class="impressum-div">
            <h2>1. Allgemeine Hinweise</h2>
            <p>
                Der Schutz Ihrer persönlichen Daten ist uns ein wichtiges Anliegen. In dieser Datenschutzerklärung informieren wir Sie darüber, welche Daten wir erheben, wie wir diese nutzen und welche Rechte Ihnen in Bezug auf Ihre Daten zustehen.
            </p>
            <hr>
            <h2>2. Verantwortliche Stelle</h2>
            <p>
                Verantwortlich für die Datenverarbeitung auf dieser Website ist:<br>
                <strong>Restaurant Dionysos</strong><br>
                Ioannis Gkogkas<br>
                Floßhafen 27<br>
                63739 Aschaffenburg<br>
                E-Mail: <a href="mailto:info@dionysos-aburg.de">info@dionysos-aburg.de</a>
            </p>
            <hr>
            <h2>3. Datenerfassung auf unserer Website</h2>
            <h3>3.1 Bestell- und Liefersystem</h3>
            <p>
                Im Rahmen unseres Online-Bestell- und Liefersystems erheben wir folgende personenbezogene Daten:
            </p>
            <ul>
                <li>Name</li>
                <li>Adresse (für die Lieferung)</li>
                <li>E-Mail-Adresse (für Bestellbestätigungen und Rückfragen)</li>
                <li>Telefonnummer (für Rückfragen zur Bestellung)</li>
                <li>Bestellinformationen (Details der Bestellung)</li>
            </ul>
            <p>
                Die Datenverarbeitung erfolgt, um Ihre Bestellung zu bearbeiten und auszuliefern. Rechtsgrundlage ist Art. 6 Abs. 1 lit. b DSGVO (Verarbeitung zur Erfüllung eines Vertrags).
            </p>
            <h3>3.2 Reservierungssystem</h3>
            <p>
                Wenn Sie über unser Reservierungssystem einen Tisch reservieren, verarbeiten wir folgende personenbezogene Daten:
            </p>
            <ul>
                <li>Name</li>
                <li>E-Mail-Adresse</li>
                <li>Telefonnummer</li>
                <li>Reservierungsdetails (Datum, Uhrzeit, Anzahl der Gäste)</li>
            </ul>
            <p>
                Diese Daten werden ausschließlich zur Bearbeitung Ihrer Reservierung genutzt. Die Verarbeitung erfolgt auf Grundlage von Art. 6 Abs. 1 lit. b DSGVO.
            </p>
            <hr>
            <h2>4. Nutzung von Google Maps</h2>
            <p>
                Diese Website nutzt den Kartendienst Google Maps. Anbieter ist die Google Ireland Limited („Google“), Gordon House, Barrow Street, Dublin 4, Irland.
            </p>
            <p>
                Zur Nutzung der Funktionen von Google Maps ist es notwendig, Ihre IP-Adresse zu speichern. Diese Informationen werden in der Regel an einen Server von Google in den USA übertragen und dort gespeichert. Wir haben keinen Einfluss auf diese Datenübertragung.
            </p>
            <p>
                Die Nutzung von Google Maps erfolgt im Interesse einer ansprechenden Darstellung unseres Online-Angebots und einer leichten Auffindbarkeit der von uns auf der Website angegebenen Orte. Dies stellt ein berechtigtes Interesse im Sinne von Art. 6 Abs. 1 lit. f DSGVO dar.
            </p>
            <p>
                Weitere Informationen zum Umgang mit Nutzerdaten finden Sie in der Datenschutzerklärung von Google: <a href="https://policies.google.com/privacy" target="_blank">https://policies.google.com/privacy</a>.
            </p>
            <hr>
            <h2>5. Speicherdauer der Daten</h2>
            <p>
                Wir speichern personenbezogene Daten nur so lange, wie dies für die Bearbeitung Ihrer Anfrage, Bestellung oder Reservierung erforderlich ist. Sofern keine gesetzlichen Aufbewahrungsfristen bestehen, werden die Daten nach Erledigung der Anfrage oder vollständiger Vertragserfüllung gelöscht.
            </p>
            <hr>
            <h2>6. Weitergabe von Daten</h2>
            <p>
                Eine Weitergabe Ihrer persönlichen Daten an Dritte erfolgt nur, wenn dies zur Vertragsabwicklung notwendig ist (z. B. an Lieferdienste), Sie ausdrücklich eingewilligt haben oder eine gesetzliche Verpflichtung besteht.
            </p>
            <hr>
            <h2>7. Ihre Rechte</h2>
            <p>Sie haben jederzeit das Recht auf:</p>
            <ul>
                <li>Auskunft über Ihre gespeicherten personenbezogenen Daten</li>
                <li>Berichtigung unrichtiger Daten</li>
                <li>Löschung Ihrer Daten</li>
                <li>Einschränkung der Verarbeitung</li>
                <li>Datenübertragbarkeit</li>
                <li>Widerspruch gegen die Verarbeitung</li>
            </ul>
            <p>
                Um eines dieser Rechte geltend zu machen, wenden Sie sich bitte an uns unter: <a href="mailto:info@dionysos-aburg.de">info@dionysos-aburg.de</a>.
            </p>
            <hr>
            <h2>8. Externes Hosting</h2>
            <p>
                Unsere Website wird bei einem externen Dienstleister (Hoster) gehostet. Die personenbezogenen Daten, die auf dieser Website erfasst werden, werden auf den Servern des Hosters gespeichert. Rechtsgrundlage für die Datenverarbeitung ist Art. 6 Abs. 1 lit. f DSGVO (berechtigtes Interesse).
            </p>
            <hr>
            <h2>9. Änderungen dieser Datenschutzerklärung</h2>
            <p>
                Wir behalten uns das Recht vor, diese Datenschutzerklärung jederzeit zu ändern, um sie an aktuelle rechtliche Anforderungen oder Änderungen unserer Dienstleistungen anzupassen. Die neue Datenschutzerklärung gilt dann bei Ihrem nächsten Besuch.
            </p>
            </div>
        </div>
        EOT;
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
            $page = new Datenschutz();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }

}
Datenschutz::main();