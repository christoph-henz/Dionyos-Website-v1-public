<?php
include_once 'Page.php';
class AGB extends Page
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
        $this->generatePageHeader("AGB");
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

        /*(!$this->cookieHandler->isAllowOrder()){
            header("HTTP/1.1 301 See Other");
            header("Location: /");
        }*/

    }


    protected function printDatenschutz():void{
        echo <<< EOT
            <div id="order-infobox">
            <h2><span class="highlight">A</span>llgemeine Geschäftsbedingungen (AGB)</h2>
            <div class="impressum-div">
            <div class="section">
                <h2>1. Geltungsbereich</h2>
                <p>Diese Allgemeinen Geschäftsbedingungen (AGB) gelten für alle Bestellungen, Reservierungen und sonstigen Dienstleistungen, die über unsere Website erfolgen. Mit der Nutzung unserer Website erklären Sie sich mit diesen AGB einverstanden.</p>
            </div>
            <hr>
        
            <div class="section">
                <h2>2. Vertragspartner</h2>
                <p>Der Vertrag kommt zustande zwischen:</p>
                <ul>
                    <li>Restaurant Dionysos</li>
                    <li>Floßhafen 27</li>
                    <li>63739 Aschaffenburg</li>
                    <li>06021 25779</li>
                    <li>info@dionysos-aburg.de</li>
                </ul>
                <p>(im Folgenden "Restaurant" genannt) und dem Kunden, der über die Website eine Bestellung oder Reservierung aufgibt.</p>
            </div>
            
            <hr>
        
            <div class="section">
                <h2>3. Bestellungen</h2>
                <h3>3.1 Online-Bestellung</h3>
                <ul>
                    <li>Der Kunde kann über die Website Speisen und Getränke zur Lieferung oder Abholung bestellen.</li>
                    <li>Die im Online-Shop angegebenen Preise enthalten die gesetzliche Mehrwertsteuer.</li>
                    <li>Bestellungen gelten als verbindlich, sobald sie vom Restaurant bestätigt wurden.</li>
                    <li>Das Restaurant behält sich das Recht vor, Bestellungen in Ausnahmefällen abzulehnen (z.B. bei Lieferengpässen oder ungewöhnlich hohen Bestellmengen).</li>
                </ul>
        
                <h3>3.2 Lieferung</h3>
                <ul>
                    <li>Die Lieferung erfolgt an die vom Kunden angegebene Adresse.</li>
                    <li>Lieferkosten werden dem Kunden vor Abschluss der Bestellung angezeigt.</li>
                    <li>Das Restaurant übernimmt keine Haftung für verspätete Lieferungen aufgrund höherer Gewalt, Verkehrslage oder technischer Probleme.</li>
                </ul>
        
                <h3>3.3 Abholung</h3>
                <ul>
                    <li>Bei der Bestellung zur Abholung kann der Kunde einen Abholzeitpunkt wählen.</li>
                    <li>Der Kunde ist verpflichtet, die bestellten Speisen innerhalb des angegebenen Zeitraums abzuholen.</li>
                </ul>
            </div>
            
            <hr>
            
            <div class="section">
                <h2>4. Reservierungen</h2>
                <h3>4.1 Reservierungsanfrage</h3>
                <ul>
                    <li>Reservierungen können über das auf der Website bereitgestellte Reservierungssystem vorgenommen werden.</li>
                    <li>Reservierungen für den Folgetag können nur bis 22:00 Uhr abgeschlossen werden</li>
                    <li>Der Kunde erhält eine Bestätigung der Reservierung per E-Mail.</li>
                    <li>Das Restaurant behält sich das Recht vor, Reservierungen in Ausnahmefällen abzulehnen.</li>
                </ul>
        
                <h3>4.2 Stornierung von Reservierungen</h3>
                <ul> 
                    <li>Stornierungen sind bis 48 Stunden vor dem Reservierungszeitpunkt kostenfrei möglich.</li>
                    <li>Für Reservierungen von Gruppen ab 10 Personen wird im Voraus eine Kaution in Höhe von 10 € pro Person erhoben.</li> 
                    <li>Sollte die Reservierung nicht mindestens 24 Stunden vor dem Reservierungszeitpunkt abgesagt werden oder die Gruppe ohne Absage nicht erscheinen, wird die geleistete Kaution vollständig einbehalten.</li>
                    <li>Das Restaurant behält sich außerdem das Recht vor, den Betrag von 10 € pro Person für die Anzahl an Personen einzubehalten, die weniger erscheinen als ursprünglich reserviert wurden.</li>
                </ul>
        
                <h3>4.3 No-Show-Regel</h3>
                <ul>
                    <li>Sollte der Kunde ohne rechtzeitige Stornierung nicht zur Reservierung erscheinen, behält sich das Restaurant vor, zukünftige Reservierungen abzulehnen.</li>
                </ul>
            </div>
            
            <hr>
        
            <div class="section">
                <h2>5. Widerrufsrecht</h2>
                <p>Gemäß § 312g Abs. 2 Nr. 9 BGB besteht kein Widerrufsrecht für die Lieferung von Speisen und Getränken, die schnell verderben oder deren Verfallsdatum überschritten würde.</p>
            </div>
            
            <hr>
        
            <div class="section">
                <h2>6. Zahlung</h2>
                <h3>6.1 Zahlungsmethoden</h3>
                <ul>
                    <li>Zahlungen können bei Abholung per Bankkarte sowie mit Bargeld und bei Lieferung in bar erfolgen.</li>
                    <li>Das Restaurant behält sich das Recht vor, bestimmte Zahlungsmethoden auszuschließen.</li>
                </ul>
        
                <h3>6.2 Fälligkeit</h3>
                <ul>
                    <li>Der Kaufpreis ist mit Vertragsschluss sofort fällig.</li>
                    <li>Bei Zahlungsverzug behält sich das Restaurant das Recht vor, rechtliche Schritte einzuleiten.</li>
                </ul>
            </div>
            
            <hr>
        
            <div class="section">
                <h2>7. Haftung</h2>
                <p>Das Restaurant haftet nur für Vorsatz und grobe Fahrlässigkeit. Für leichte Fahrlässigkeit haftet das Restaurant nur bei der Verletzung wesentlicher Vertragspflichten. Das Restaurant haftet nicht für technische Probleme, die zur Nichterreichbarkeit der Website oder Verzögerungen bei der Bearbeitung von Bestellungen oder Reservierungen führen.</p>
            </div>
            
            <hr>
            
            <div class="section">
                <h2>8. Datenschutz</h2>
                <p>Informationen zur Erhebung, Verarbeitung und Nutzung personenbezogener Daten finden Sie in unserer <a style="color: coral" href="Datenschutz.php">Datenschutzerklärung</a>.</p>
            </div>
            
            <hr>
            
            <div class="section">
                <h2>9. Google Maps</h2>
                <p>Diese Website verwendet Google Maps, um den Standort des Restaurants anzuzeigen und eine Routenplanung zu ermöglichen. Durch die Nutzung von Google Maps werden Informationen über die Nutzung der Kartenfunktion durch den Kunden an Google übermittelt. Weitere Informationen dazu finden Sie in der Datenschutzerklärung.</p>
            </div>
            
            <hr>
            
            <div class="section">
                <h2>10. Gerichtsstand</h2>
                <p>Für alle Streitigkeiten aus Verträgen, die unter Einbeziehung dieser AGB geschlossen wurden, gilt der Sitz des Restaurants als Gerichtsstand, sofern der Kunde Kaufmann, eine juristische Person des öffentlichen Rechts oder ein öffentlich-rechtliches Sondervermögen ist.</p>
            </div>
            
            <hr>
            
            <div class="section">
                <h2>11. Salvatorische Klausel</h2>
                <p>Sollten einzelne Bestimmungen dieser AGB unwirksam sein oder werden, so bleibt die Wirksamkeit der übrigen Bestimmungen davon unberührt. Anstelle der unwirksamen Bestimmung treten die gesetzlichen Vorschriften.</p>
            </div>
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
            $page = new AGB();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }

}
AGB::main();