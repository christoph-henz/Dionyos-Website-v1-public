<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€
include_once 'CookieHandler.php';
//include_once 'SessionManager.php';

abstract class Page
{
    // --- ATTRIBUTES ---


    protected mysqli $_database;

    protected CookieHandler $cookieHandler;


    // --- OPERATIONS ---

    /**
     * Connects to DB and stores
     * the connection in member $_database.
     * Needs name of DB, user, password.
     */
    protected function __construct()
    {
        error_reporting(E_ALL);

        $this->cookieHandler = CookieHandler::getInstance();

        // MariaDB-Verbindungsdaten
        $dbHost = "db****************hosting.zone";
        $dbUser = "db****************";
        $dbPassword = "*************************";
        $dbName = "db****************";

        // Verbindung zur MariaDB herstellen
        $this->_database = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

        // Überprüfen, ob die Verbindung erfolgreich war
        if ($this->_database->connect_error) {
            die("Datenbankverbindung fehlgeschlagen: " . $this->_database->connect_error);
        }
        // Setze den Zeichensatz auf UTF-8
        if (!$this->_database->set_charset("utf8")) {
            die("Fehler beim Setzen des Zeichensatzes: " . $this->_database->error);
        }
    }

    /**
     * Closes the DB connection and cleans up
     */
    public function __destruct()
    {
        //$this->_database->close();
        // to do: close database
    }

    /**
     * Generates the header section of the page.
     * i.e. starting from the content type up to the body-tag.
     * Takes care that all strings passed from outside
     * are converted to safe HTML by htmlspecialchars.
     *
     * @param string $title $title is the text to be used as title of the page
     * @param string $jsFile path to a java script file to be included, default is "" i.e. no java script file
     * @param bool $autoreload  true: auto reload the page every 5 s, false: not auto reload
     * @return void
     */
    protected function generatePageHeader(string $title = "", string $jsFile = "", bool $autoreload = false):void
    {
        $title = htmlspecialchars($title);
        header("Content-type: text/html; charset=UTF-8");

        // to do: handle all parameters
        // to do: output common beginning of HTML code

        echo <<< HERE
            <!doctype html>
            <html lang="de">
                <head>
                <title>$title</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <meta name="Description" content="Genießen Sie in einem stilvollen Ambiente köstliche südländische Spezialitäten."/>
                    <link rel="icon" href="img/header/logo3.png" type="image/icon type">
                    <link rel="stylesheet" href="style/frame.css?3" type="text/css">
                    <link rel="stylesheet" href="style/footer.css?3" type="text/css">
                    <script src="js/cookie_banner.js"></script>
                    <script src="js/parallax.js"></script>
                    <script src="js/visuals.js"></script>
                    
        HERE;
                    $this->additionalMetaData();

        $this->generateBanner("Am 06.01.2025 hat unsere Gaststätte ab 17.30 Uhr für Sie geöffnet");
        echo <<< HERE
                </head>
        HERE;
        if(!$this->cookieHandler->hasAskedBefore()){
            echo "<body onload='showCookieBanner(1,1)'>";
        }else echo "<body>";
        echo <<< HERE
                <main class="parallax">
                    <header>
                      <img id="mainlogo" src="img/header/logo3.png"/>
                    </header>                
        HERE;

    }

    protected function generateBanner(string $text)
    {
        echo <<<EOT
    <style>
        #Timebanner {
            position: fixed;
            top: 0;
            width: 100%;
            height: 10%;
            background-color: #111111;
            opacity: 0.8;
            color: gold;
            text-align: center;
            font-size: 40px;
            padding: 30px;
            z-index: 1;
            transform: translateX(100%);
            transition: transform 0.5s ease-in-out;
        }

        #closeButton {
            background: none;
            border: none;
            color: goldenrod;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            margin-left: 10px;
        }

        #closeButton:hover {
            color: red;
        }
    </style>
    <div id="Timebanner">
        <p>{$text}<button id="closeButton" onclick="closeBanner()">X</button></p>
    </div>
    <script>
        // Banner anzeigen
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('Timebanner').style.transform = 'translateX(0)';
            }, 100); // Verzögerung, um sicherzustellen, dass die Animation funktioniert
        });

        // Banner schließen
        function closeBanner() {
            document.getElementById('Timebanner').style.transform = 'translateX(100%)';
        }
    </script>
    EOT;
    }

    /**
     * Outputs the end of the HTML-file i.e. </body> etc.
     * @return void
     */
    protected function generatePageFooter():void
    {
        echo <<< HERE
                <footer>
                    <div class="footer-container">
                        <ul class="footer-links">
                            <li style="align-content: center"><a class="reg-a" href="Impressum.php">Impressum</a></li>
                            <li style="align-content: center"style="align-content: center"><a class="reg-a" href="Datenschutz.php">Datenschutz</a></li>
                            <li style="align-content: center"><a class="reg-a" href="AGB.php">AGB</a></li>

        HERE;
                        $this->cookieHandler->isAllowGoogle() ? $go = 1 : $go = 0;
                        $this->cookieHandler->isAllowOrder() ? $or = 1 : $or = 0;
        echo <<< HERE
                            <li style="align-content: center"><a class="reg-a" href="javascript:void(0)" onclick="showCookieBanner(<?= $go ?>, <?= $or ?>)">Cookie Einstellungen bearbeiten</a></li>
                            <li><a href="https://www.instagram.com/dionysos_aburg/?hl=de"><img src="img/header/logo3.png" style="height: 70px" alt="Instagram"></a></li>
                        </ul>
                    </div>
                </footer>
             </main>
            </body>
        HERE;



    }

    /**
     * Processes the data that comes in via GET or POST.
     * If every derived page is supposed to do something common
     * with submitted data do it here.
     * E.g. checking the settings of PHP that
     * influence passing the parameters (e.g. magic_quotes).
     * @return void
     */
    protected function processReceivedData():void
    {
        if(isset($_POST[CookieHandler::ALLOW_ORDER_KEY]) && isset($_POST[CookieHandler::ALLOW_GOOGLE_KEY])){
            $this->cookieHandler->setAskedBefore(true);
            $this->cookieHandler->setAllowGoogle((bool)$_POST[CookieHandler::ALLOW_GOOGLE_KEY]);
            $this->cookieHandler->setAllowOrder((bool)$_POST[CookieHandler::ALLOW_ORDER_KEY]);
            $uri = $_SERVER['REQUEST_URI'];
            header("HTTP/1.1 301 See Other");
            header("Location: $uri");
        }
    }

    protected abstract function additionalMetaData();

    protected abstract function footerScripts();
}
