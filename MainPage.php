<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€
/**
 * Class PageTemplate for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 *
 * PHP Version 7.4
 *
 * @file     PageTemplate.php
 * @package  Page Template
 * @version  3.1
 */

// to do: change name 'PageTemplate' throughout this file
require_once './Page.php';
require_once './menu/menu.php';

/**
 * This is a template for top level classes, which represent
 * a complete web page and which are called directly by the user.
 * Usually there will only be a single instance of such a class.
 * The name of the template is supposed
 * to be replaced by the name of the specific HTML page e.g. baker.
 * The order of methods might correspond to the order of thinking
 * during implementation.
 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 */
class MainPage extends Page
{
    // to do: declare reference variables for members
    // representing substructures/blocks
    private Menu $menu;
    /**
     * Instantiates members (to be defined above).
     * Calls the constructor of the parent i.e. page class.
     * So, the database connection is established.
     * @throws Exception
     */
    protected function __construct()
    {
        parent::__construct();
        $this->menu = new Menu("./menu/",$this->_database);
    }

    /**
     * Cleans up whatever is needed.
     * Calls the destructor of the parent i.e. page class.
     * So, the database connection is closed.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is returned in an array e.g. as associative array.
     * @return array An array containing the requested data.
     * This may be a normal array, an empty array or an associative array.
     */
    protected function getViewData():array
    {
        // to do: fetch data for this view from the database
        // to do: return array containing data
        return array();
    }

    /**
     * First the required data is fetched and then the HTML is
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if available- the content of
     * all views contained is generated.
     * Finally, the footer is added.
     * @return void
     */
    protected function generateView():void
    {
        $data = $this->getViewData(); //NOSONAR ignore unused $data
        $this->generatePageHeader('Dionysos'); //to do: set optional parameters

        echo <<< EOT
            <div id="main-wrapper">
                <div id="nav-wrapper">
        EOT;
                $this->generateNav();
        echo <<< EOT
                </div>
                <div id="content-wrapper">
        EOT;
                $this->generateMainBody();
        echo <<< EOT
                </div>
            </div>
            <script src="js/fadein.js"></script>
        EOT;


        $this->generatePageFooter();
    }

    /**
     * Processes the data that comes via GET or POST.
     * If this page is supposed to do something with submitted
     * data do it here.
     * @return void
     */
    protected function processReceivedData():void
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members


    }

    /**
     * This main-function has the only purpose to create an instance
     * of the class and to get all the things going.
     * I.e. the operations of the class are called to produce
     * the output of the HTML-file.
     * The name "main" is no keyword for php. It is just used to
     * indicate that function as the central starting point.
     * To make it simpler this is a static function. That is you can simply
     * call it without first creating an instance of the class.
     * @return void
     */
    public static function main():void
    {
        // Generiere ein zufälliges Token und speichere es in der Sitzung
        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }
        try {
            $page = new MainPage();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }

    protected function additionalMetaData()
    {
        echo <<< EOT
            <link rel="stylesheet" type="text/css" href="style/reservation.css"/>
            <link rel="stylesheet" type="text/css" href="style/contentbox.css"/>
            <link rel="stylesheet" type="text/css" href="style/galerie.css"/>
            <link rel="stylesheet" type="text/css" href="style/navigation.css"/>
            <link rel="stylesheet" href="style/menu_style.css" type="text/css">
            <script src="js/cbox.js"></script>
            <script src="js/btn.js"></script>
        EOT;
        if($this->cookieHandler->isAllowGoogle()){
            echo <<< EOT
            <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cinzel&family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet"/>
            EOT;
        }
    }

    private function generateGallery(){
        echo <<< EOT
        <div class="content-container" id="igalery">
            <div class="headline-sec">
                <h1><span class="highlight">G</span>alerie</h1>
            </div>
            <div class="foldable">
            <div class="see-more">
                <a href="javascript:javascript:void(0)" onclick="toggleFoldSection(this)">aufklappen</a>
            </div>
            <div class="content-sec">
                <div class="row">
                                <div class="column">
                                    <img src="galerie/img/2.jpg" style="width: 100%;" />
                                    <img src="galerie/img/1.jpg" style="width: 100%;" />
                                    <img src="galerie/img/8.jpg" style="width: 100%;" />

                                    <img src="galerie/img/neu_4.JPEG" style="width: 100%;" />
                                    <img src="galerie/img/011.JPG" style="width: 100%;" />
                                </div>
                                <div class="column">
                                    <img src="galerie/img/9.JPG" style="width: 100%;" />
                                    <img src="galerie/img/5.JPG" style="width: 100%;" />

                                    <img src="galerie/img/neu18.png" style="width: 100%;" />
                                    <img src="galerie/img/neu_7.jpg" style="width: 100%;" />

                                    <img src="galerie/img/neu16.jpg" style="width: 100%;" />
                                </div>
                                <div class="column">
                                    <img src="galerie/img/15.jpg" style="width: 100%;" />
                                    <img src="galerie/img/DSC_0124.png" style="width: 100%;" />
                                    <img src="galerie/img/20190405_220837.jpg" style="width: 100%;" />
                                    <img src="galerie/img/neu17.jpg" style="width: 100%;" />
                                </div>
                            </div>
            </div>
            </div>
        </div>
        EOT;
    }

    private function generateReservation(){
        echo <<< EOT
        <div class="content-container" id="ireservation">
            <h2><span class="highlight">R</span>eservieren Sie einen Tisch</h2>
                 <form id="reservation-form" action="ReservationSubmit.php" method="POST">
                    <div class="form-top">
                        <!-- Linke Spalte -->
                        <div class="form-column">
                            <div class="form-group">
                                <label for="name">Ihr Name:</label>
                                <input type="text" id="name" name="name">
                            </div>

                            <div class="form-group">
                                <label for="email">Ihre E-Mail-Adresse:</label>
                                <input id="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Bitte geben Sie eine gültige E-Mail-Adresse ein.">
                            </div>

                            <div class="form-group">
                                <label for="phone">Ihre Telefonnummer:</label>
                                <input type="tel" id="phone" name="phone" >
                            </div>
                        </div>

                        <!-- Rechte Spalte -->
                        <div class="form-column">
                            <div class="form-group">
                                <label for="date">Reservierungsdatum:</label>
                                <input type="date" id="date" name="date" >
                            </div>

                            <div class="form-group">
                                <label for="time">Uhrzeit:</label>
                                <input type="time" id="time" name="time">
                            </div>

                            <div class="form-group">
                                <label for="guests">Anzahl der Gäste:</label>
                                <input id="guests" name="guests" >
                            </div>
                        </div>
                    </div>

                    <!-- Anmerkungen Abschnitt -->
                    <div class="form-comments">
                        <div class="form-group">
                            <label for="message">Besondere Wünsche oder Anmerkungen:</label>
                            <textarea id="message" name="message" rows="4"></textarea>
                        </div>
                    </div>

                    <button type="button" id="reservation-submit-btn">Reservierung abschicken</button>
            </form>
            <!-- Modal zur Bestätigung -->
            <div id="reservation-confirm-modal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); padding: 20px; background-color:rgb(36,36,36,0.9); border: 2px solid #ccc; z-index:1000;">
                <h2>Hinweis</h2>
                <p>Reservierungen gelten nur für unseren Innenbereich.</p>
                <div style="align-self: center">
                <button id="confirm-btn" class="reservation-btn" type="button" style="width: 100%;padding: 10px;border: 1px solid #ccc;background:#89656a;cursor: pointer;border-radius: 5px;">Bestätigen</button>
                <button id="cancel-btn" class="reservation-btn" type="button" style="width: 100%;padding: 10px;border: 1px solid #ccc;background: #89656a;cursor: pointer;border-radius: 5px;">Abbrechen</button>
                </div>
            </div>
            <div id="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;"></div>
        </div>
        <script>
            document.getElementById('reservation-submit-btn').addEventListener('click', function(){
                // Zeige das Bestätigungs-Modal an
                document.getElementById('reservation-confirm-modal').style.display = 'block';
                document.getElementById('modal-overlay').style.display = 'block';
            });
            document.getElementById('confirm-btn').addEventListener('click', function(){
                // Submite das Formular
                document.getElementById('reservation-form').submit();
            });
            document.getElementById('cancel-btn').addEventListener('click', function(){
                // Schließe das Modal ohne das Formular zu submitten
                document.getElementById('reservation-confirm-modal').style.display = 'none';
                document.getElementById('modal-overlay').style.display = 'none';
            });
        </script>
        EOT;
    }

    private function generateApproach(){
        echo <<< EOT
        <div class="content-container" id="iarival">
            <div class="headline-sec">
                <h1><span class="highlight">A</span>nfahrt</h1>
            </div>
            <div class="content-sec">
             <div id="anfahrt-wrapper">
                <div id="address">
                    <p>Restaurant Dionysos <br />
                       Am Floßhafen 27<br />
                       63739 Aschaffenburg</p>
                </div>
                <div id="maps_container">
                    <a href="https://maps.app.goo.gl/6AtoJMS2zFAnmLeq9?g_st=ic"><img src="img/misc/g_maps.png"/></a>
                    <a href="https://maps.apple.com/?address=Am%20Flo%C3%9Fhafen%2027,%2063739%20Aschaffenburg,%20Deutschland&ll=49.967978,9.140846&q=Am%20Flo%C3%9Fhafen%2027&t=h"><img src="img/misc/a_maps.png"/></a>
                </div>
        EOT;
            if($this->cookieHandler->isAllowGoogle()){
        echo <<< EOT
                <div id="map" style="width: 100%; overflow: hidden; height: 550px;">
                    <iframe src="https://www.google.com/maps/d/u/0/embed?mid=1PUapZmiIxbQRXtQ-yye2FHxICSXaDdr6&z=15" width="100%" height="600px" style="border: 0; margin-top: -55px;">></iframe>
                </div>
        EOT;
            }
        echo <<< EOT
        
             </div>
            </div>
        </div>
        EOT;
    }

    private function generateIntroDisplay(){
        echo <<< EOT
        <div class="content-container" id="iinfo">
            <div class="headline-sec">
                  <h2>Willkommen im</h2>
                  <h1><span class="highlight">D</span>ionysos</h1>
            </div>
            <div class="content-sec">
                <div class="content-nav">
                        <a class="content-item item-active" onClick="onClickMen(0)">Das Dionysos</a>
                        <a class="content-item" onClick="onClickMen(1)">Öffnungszeiten - Kontakt</a>
                        
                </div>
                <div class="foldable">
                <div class="see-more">
                    <a href="javascript:javascript:void(0)" onclick="toggleFoldSection(this)">aufklappen</a>
                </div>
                <div class="content-box fold">
                     <div class="content-display">
                        <div class="content-display-container content-active">
                           <div id="dionysos-info">
                              <div class="info-grid-container">
                                    <div class="flex-row">
                                        <div class="info-text1 flex-column">
                                        <h3>Herzlich Willkommen</h3>
                                        <p class="content-display-font" style="align-content: center">
                                       Wir freuen uns sehr, Sie bei uns begrüßen und verwöhnen zu dürfen. <br><br>
                                       Genießen Sie in einem stilvollen Ambiente köstliche südländische Spezialitäten.<br> 
                                       Wir bieten Ihnen sowohl original griechische Spezialitäten vom Grill, aus dem Ofen, der Pfanne, 
                                       als auch eine breite Auswahl an Meze (Tapas), die Sie sich nach Belieben zusammenstellen können.<br>
                                       Erlesene griechische Weine und eine Digestif-Auswahl runden Ihren Besuch ab.
                                        </p>
                                        </div>
                                        <div class="info-img1 flex-column">
                                            <img src="./galerie/img/neu_3.JPEG">
                                        </div>

                                    </div>
                                    <div class="flex-row">
                                        <div class="info-img2 flex-column">
                                            <img src="./galerie/img/9.jpg">
                                        </div>
                                        <div class="info-text2 flex-column">
                                            <h3>Ihr Erlebnis ist uns wichtig</h3>
                                            <p class="content-display-font">
                                                Es liegt uns am Herzen Ihnen neben Speisen und Getränken, die zweifelsohne ein wichtiger Bestandteil unseres Angebots sind, noch weitere Annehmlichkeiten zu bieten.<br><br>
                                                Ein Besuch im Dionysos verspricht Ihnen ein angenehmes Wohlfühlerlebnis im griechischen Stil, bei dem Sie sich in einer ungezwungenen Atmosphäre wie zu Hause fühlen können.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex-row">
                                        <div class="info-text1 flex-column">
                                            <h3>Unser Dienst an Sie</h3>
                                            <p class="content-display-font">
                                            Neben den herkömmlichen Dienstleistungen eines Restaurants bieten wir zusätzlich Dienstleistungen wie:
                                            </p>
                                            <ul>
                                                <li>Organisation von Veranstaltungen</li>
                                                <li>Live Musik</li>
                                                <!--<li>Bestellung zur Lieferung/Abholung</li>-->
                                            </ul>
                                        </div>
                                        <div class="info-img1 flex-column">
                                            <img src="./galerie/img/neu8.jpg">
                                        </div>
                                    </div>
                                    
                                   <div class="flex-row">
                                        <div class="info-img1 flex-column">
                                            <img src="./galerie/img/011.jpg"/>
                                        </div>
                                        <div class="info-text2 flex-column">
                                            <h3>Unsere Geschichte</h3>
                                            <p class="content-display-font">
                                               Das Dionysos existiert seit 1970 als Familienbetrieb. Der Gründervater ist
                                               Großvater Panagiotis, welcher das Dionysos als eines der ersten griechischen Restaurants im gesamten Rhein-Main Gebiet eröffnete. Aller Anfang war schwer und der heimische Gaumen musste sich erst langsam an die fremdwürzigen kulinarischen Speisen der eher untypisch südlichen Küche gewöhnen.
                                               So kochte er anfangs die gewohnten gut bürgerlichen Gerichte der Deutschen und servierte seinen Gästen mal hier ein Suwlaki-Spieß zur Kohlroulade, Suzukaki statt Frikadelle wie auch Gyros, getarnt als Geschnetzeltes.<br>
                                               Die Leute mochten diesen fremdartigen Geschmack und so gewöhnte er Sie Stück für Stück an unsere Küche, das erste griechische Restaurant der Gegend fing an aufzublühen und bewährte sich bis zu seiner Rente. 
                                               <br><br>
                                               Gegen Anfang der 90er Jahre trat sein Sohn Naki in die Fußstapfen seines Vaters und führte mit seiner Frau, die Gastronomie weiter fort, bis sie 2018 beschlossen sich zur Ruhe zu setzen. 
                                               <br><br>
                                               Im Jahre 2018 trat wiederum sein Sohn Dionys in die Fußstapfen der Gastronomie hinein wie die Generationen vor ihm auch. Gemeinsam mit seiner Frau Nancy führten Sie den
                                               Geist dieser Tradition bis 2024 weiter fort.
                                            </p>
                                        </div>
                                   </div>
                              </div>
                              
                           </div>
                        </div>
                        <div class="content-display-container content-inactive">
                           <div id="opening">
                              <div class="opening-grid-left">
                                 <h3 >Öffnungszeiten Restaurant</h3>
                                 <p class="content-display-font">
                                    <b>Montag</b> Ruhetag <br>
                                    <b>Dienstag - Samstag</b> 17.30 Uhr - 23.00 Uhr <br>
                                    <b>Sonn- und Feiertage</b> 11.30 Uhr - 22.00 Uhr
                                 </p>
                              </div>
                              <div class="opening-grid-right">
                                 <h3 >Öffnungszeiten Küche</h3>
                                 <p class="content-display-font">
                                    <b>Montag</b> Ruhetag <br>
                                    <b>Dienstag - Samstag</b> 17.30 Uhr - 22.00 Uhr <br>
                                    <b>Sonn- und Feiertage</b> 11.30 Uhr - 21.30 Uhr
                                 </p>
                              </div>
                           </div>
                           <div id="contact">
                              <h3>Kontakt</h3>
                              <div>
                                 <p class="content-display-font">
                                    Telefonnummer: <a class="reg-a" href="tel:+49602125779">06021 - 25779</a><br><br>
                                    Restaurant Dionysos<br>
                                    Am Floßhafen 27<br>
                                    63739 Aschaffenburg<br>
                                 </p>
                              </div>
                           </div>
                        </div>
                        
                     </div>
                  </div>
                </div>
            </div>
            </div>
        
        EOT;
    }

    private function generateMenu(){
        echo <<< EOT
        <div class="content-container" id="imenu">
            <div class="headline-sec">
                <h1><span class="highlight">S</span>peisen</h1>
            </div>
            <div class="content-sec">
            <div class="menu">
            <div class="menu-class">
        EOT;
            $this->menu->displayHTML();
         echo <<< EOT
            </div>
            </div>
            <div style="justify-content: center">
            <p style="text-align: center">Sie können selbstverständlich auch alle Gerichte zur Abholung oder Lieferung bestellen. Nutzen Sie bitte dazu unsere integrierte Bestellfunktion.<br></p>
            <h2><a class="reg-a" style="align-self: " href="Order.php">jetzt Bestellen</a></h2>
            <!--button class="reg-a" onclick="window.location.href='Order.php';">jetzt Bestellen</button-->
            </div>
            </div>
        </div>
        EOT;
    }

    private function generateNav(){
        echo <<< EOT
            <nav>
                <h2>Menu</h2>
                    <ul>
                        <li><span class="nav-item"><a href="#iinfo" onclick="onClickMen(0)">Das Dionysos</a></span></li>
                        <li><span class="nav-item"><a href="#igalery">Galerie</a></span></li>
                        <li><span class="nav-item"><a href="#imenu">Speisen</a></span></li>
                        <!--li><span class="nav-item"><a href="#ireservation">Reservierung</a></span></li-->
                        <li><span class="nav-item"><a href="#iarival">Anfahrt</a></span></li>
                    </ul>
            </nav>
            
        EOT;
    }

    private function generateMainBody(){
        $this->generateIntroDisplay();
        $this->generateGallery();
        $this->generateMenu();
        $this->generateReservation();
        $this->generateApproach();

        echo <<< EOT
            <script>setMenuHeight()</script>
            <script>initTouchEvents()</script>
        EOT;
    }

    protected function footerScripts()
    {
       echo <<< EOT
            
       EOT;

    }
}

MainPage::main();

