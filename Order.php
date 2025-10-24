<?php
include_once 'Page.php';
include_once './menu/menu.php';
class Order extends Page
{
    private Menu $menu;


    public function __construct()
    {
        parent::__construct();
        $this->menu = new Menu("./menu/", $this->_database);
    }


    protected function additionalMetaData()
    {
        echo <<< EOT
            <link rel="stylesheet" type="text/css" href="style/contentbox.css"/>
            <link rel="stylesheet" type="text/css" href="style/galerie.css"/>
            <link rel="stylesheet" type="text/css" href="style/navigation.css"/>
            <link type="text/css" rel="stylesheet" href="style/imenu_style.css">
            <link type="text/css" rel="stylesheet" href="style/form.css">
            <link type="text/css" rel="stylesheet" href="style/cart.css">
           
        
            <script src="menu/js/btn.js"></script>
            
            
        EOT;
        if($this->cookieHandler->isAllowGoogle()){
            echo <<< EOT
            <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cinzel&family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet"/>
            EOT;
        }
    }

    protected function footerScripts()
    {
    }

    protected function generateView():void{

        $this->generatePageHeader("Bestellung");
        $this->printCart();
        $this->printMenu();

        $this->generatePageFooter();

        echo <<< EOT
            <script>setMenuHeight()</script>
            <script src="js/cart.js"></script>
            <script>initTouchEvents()</script>
        EOT;

    }

    protected function printMenu(){
        $this->menu->displayAsInteractiveHTML();
    }

    protected function printCart(){
        $stmt = $this->_database->prepare("SELECT MinOrder FROM Settings");

        if ($stmt === false) {
            die("Fehler beim Vorbereiten der SQL-Abfrage: " . $this->_database->error);
        }

        $reservationOn = false;
        // SQL-Abfrage ausführen
        if ($stmt->execute()) {
            // Ergebnis binden
            $stmt->bind_result($minOrder);
            // Ergebnis abrufen
            if ($stmt->fetch()) {
                $deliverLimit = $minOrder;
            }
        } else {
            die("Fehler beim Abfragen der Einstellungen: " . $stmt->error);
        }

        // Prepared Statement schließen
        $stmt->close();

        //$settings = json_decode(file_get_contents('settings.json'), true);
        //$deliverLimit = $settings['settings']['deliverLimit'] ?? 0;
        echo <<< EOT
            <div id="order-infobox">
                <p style="margin: 0">Lieferungen erfordern einen Mindestbestellwert von <b> $deliverLimit,00€</b></p>
            </div>
            <div id="cart">
                <h2 onclick="toggleCart()" style="cursor: grab;">Warenkorb</h2>
                <div id="cart-items"></div>
                <div id="open-cart" style="cursor: grab;" onclick="toggleCart()"><p><i class="arrow down"></i></p></div>
                <div class="flex-vert">
                    <div id="cart-sum"  style="cursor: grab;" onclick="toggleCart()">Summe: 0,00€</div>
                    <form id="order-submission" method="POST" action="OrderSubmit.php" onsubmit="submitOrder()">
                    <!--form-->
                        <select name="articles[]" id="virt-cart" multiple style="display: none">
                        </select>
                        <button id="order-submit" type="button" onclick="submitOrder()">Weiter</button>
                    </form>
                </div>
                
            </div>
            
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


    public static function main():void
    {
        try {
            $page = new Order();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }

}
Order::main();