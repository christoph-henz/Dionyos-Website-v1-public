<?php
include_once 'Page.php';
class Impressum extends Page
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
        $this->generatePageHeader("Impressum");
        $this->generateNav();
        $this->printImpressum();
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


    protected function printImpressum():void{
        echo <<< EOT
            <div id="order-infobox">
                <h2><span class="highlight">I</span>mpressum</h2>
                <div class="impressum-div">
                    <p>
                        <strong>Restaurant Dionysos</strong><br>
                        Ioannis Gkogkas<br>
                        Floßhafen 27<br>
                        63739 Aschaffenburg<br>
                    </p>
            
                    <!--hr>
            
                    <h3>Vertreten durch:</h3>
                    <p>Kalin Yakimov</p-->
            
                    <hr>
            
                    <h3>Kontakt:</h3>
                    <p>
                        Telefon: +49 06021 25779<br>
                        E-Mail: <a href="mailto:info@dionysos-aburg.de">info@dionysos-aburg.de</a><br>
                        Website: <a href="https://www.dionysos-aburg.de">www.dionysos-aburg.de</a>
                    </p>
            
                    <hr>
            
                    <h3>Umsatzsteuer-ID:</h3>
                    <p>
                        Umsatzsteuer-Identifikationsnummer gemäß §27 a Umsatzsteuergesetz: DE 82 843 719 062
                    </p>
            
                    <hr>
            
                    <h3>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV:</h3>
                    <p>
                        Ioannis Gkogkas<br>
                        Floßhafen 27<br>
                        63739 Aschaffenburg
                    </p>
            
                    <hr>
            
                    <h2>Haftungsausschluss (Disclaimer)</h2>
            
                    <h3>Haftung für Inhalte</h3>
                    <p>
                        Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich.
                        Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen
                        oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen.
                        Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt.
                        Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich.
                        Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.
                    </p>
            
                    <hr>
            
                    <h3>Haftung für Links</h3>
                    <p>
                        Unser Angebot enthält Links zu externen Websites Dritter, auf deren Inhalte wir keinen Einfluss haben.
                        Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen.
                        Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich.
                        Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft.
                        Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar.
                        Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar.
                        Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.
                    </p>
            
                    <hr>
            
                    <h3>Urheberrecht</h3>
                    <p>
                        Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht.
                        Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes
                        bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers.
                        Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet.
                        Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet.
                        Insbesondere werden Inhalte Dritter als solche gekennzeichnet.
                        Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis.
                        Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.
                    </p>
            
                    <hr>
            
                    <h3>Streitschlichtung</h3>
                    <p>
                        Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit:
                        <a href="https://ec.europa.eu/consumers/odr" target="_blank">https://ec.europa.eu/consumers/odr</a>.<br>
                        Unsere E-Mail-Adresse finden Sie oben im Impressum.<br>
                        Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.
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
            $page = new Impressum();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }

}
Impressum::main();