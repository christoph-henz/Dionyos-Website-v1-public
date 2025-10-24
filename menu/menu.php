<?php

include_once("menusite.php");

include_once("imenu.php");

class Menu extends Page{


	private $sites;
	private $active_site;
	private $menu_size;
	private $rootPath;


    function __construct($pathToRoot, $databaseConnection)
    {
        $this->_database = $databaseConnection; // Initialisiere die Datenbankverbindung
        $this->sites = array();
        $this->active_site = 0;
        $this->menu_size = 0;
        $this->rootPath = $pathToRoot;
        $this->loadMenu(); // Jetzt sicher aufrufen
    }
	
	
	
	/*private function loadMenu(){
		
		$this->loadFile($this->rootPath . "menufiles/vorspeise.txt");
		$this->loadFile($this->rootPath . "menufiles/beilagensalate.txt");
		$this->loadFile($this->rootPath . "menufiles/salatteller.txt");
		$this->loadFile($this->rootPath . "menufiles/veghaupt.txt");
		$this->loadFile($this->rootPath . "menufiles/grill.txt");
		$this->loadFile($this->rootPath . "menufiles/grillteller.txt");
		$this->loadFile($this->rootPath . "menufiles/fisch.txt");
		$this->loadFile($this->rootPath . "menufiles/pizza.txt");
		$this->loadFile($this->rootPath . "menufiles/kinder.txt");
		$this->loadFile($this->rootPath . "menufiles/beilagen.txt");
        $this->loadFile($this->rootPath . "menufiles/nachspeise.txt");
		$this->loadFile($this->rootPath . "menufiles/wochenkarte.txt");

	}

	private function loadFile($path){
		$file = fopen($path, "r") or die("Could not read file");
		$capacity = fgets($file);
		$cat = fgets($file);

		$site = new MenuSite($cat, $capacity);
		$this->menu_size++;
		while (!feof($file)) {

			$line = fgets($file);
			$arr = explode(":", $line);
			$art = new Article($arr[0], $arr[1], $arr[2],$arr[3]);
			$art->setRootPath($this->rootPath);
			try{
				$site->addArticle($art);
			}catch(Exception $e){
				$this->sites[] = $site;
				$this->menu_size++;
				$site = new MenuSite($cat, $capacity);
				$site->addArticle($art);
			}

		}
		$this->sites[] = $site;
		fclose($file);
	}*/

    private function loadMenu()
    {
        // Lade alle Kategorien
        $stmt = $this->_database->prepare("SELECT DISTINCT category FROM Menu ORDER BY Category ASC");
        if (!$stmt) {
            die("Datenbank-Fehler: " . $this->_database->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $category = $row['category'];
            $menuSite = new MenuSite($category, 80); // Setze Kapazität
            $menuSite->loadFromDatabase($category, $this->_database);
            $this->sites[] = $menuSite;
            $this->menu_size++;
        }

        $stmt->close();
    }


    public function getArticle($number){
		for($i = 0; $i < $this->menu_size; $i++){
			$article = $this->sites[$i]->getArticle($number);
			if($article != null)
				return $article;
		}
		return null;
	}

    public function getAllArticles() : array{
        $arr = array();
        foreach ($this->sites as $site){
            $arr = array_merge($arr, $site->getLoa());
        }
        return $arr;
    }

	
	public function displayHTML(){
		
		?>
			<div class="menu-class">
			<div class="menu-display">
			
			<div class="menu-site menu-site-active">
				<span></span>
				<?php
					echo "<img src='". $this->rootPath ."/res/menu.png'>"
				?>
			</div>
		<?php
		
		for($i = 0; $i < $this->menu_size; $i++){
			$this->sites[$i]->displayHTML();
		}
		
		?>
				<div id="btnl">
				<?php echo "<button class='btn-menu' onClick='goLeft()'><img src=" . $this->rootPath . "'res/arrleft.png'></button>" ?>
				</div>
				<div id="btnr">
				<?php echo "<button class='btn-menu' onClick='goRight()'><img src=" . $this->rootPath . "'res/arrright.png'></button>" ?>
				</div>
			</div>

		<div id="menu-legend">
				<p>
					[1]mit Farbstoff [2]mit Konservierungsstoff  [3]mit Nitritpökelsalz [4]mit Antioxidationsmittel [5]mit Geschmacksverstärker [6]geschwefelt [7]geschwärzt [8]mit Phosphat<br>
					[9]mit Milcheiweiß [10]koffeinhaltig [11]chininhaltig [12]mit Süßungsmittel(n) [13]enthält eine Phenylalaninquelle [14]gewachst [15]mit Taurin<br>[16]enthält Sojaöl; aus genetisch veränderter Soja hergestellt<br>
					Phosphate: Unser Milchkäse ist ein Weichkäse, welcher aus Milch, verschiedenen Milchsäurekulturen und Lab besteht<br><br><b>Alle Preise inklusive 19% Mehrwertsteuer</b>
				</p>
			</div>
		</div>
		<?php
	}
	
	public function displayAsInteractiveHTML(){
		
		?>
			<div class="menu-class">
			<div class="menu-display">
			
			<div class="menu-site menu-site-active">
				<span></span>
				<?php
					echo "<img src='". $this->rootPath ."/res/menu.png'>"
				?>
			</div>
		<?php
		
		for($i = 0; $i < $this->menu_size; $i++){
			$this->sites[$i]->displayAsInteractiveHTML();
		}
		
		?>
				<div id="btnl">
					<?php echo "<button class='btn-menu' onClick='goLeft()'><img src=" . $this->rootPath . "'res/arrleft.png'></button>" ?>
				
				</div>
				<div id="btnr">
				<?php echo "<button class='btn-menu' onClick='goRight()'><img src=" . $this->rootPath . "'res/arrright.png'></button>" ?>
				</div>
				
			</div>
			<div id="menu-legend">
				<p>
					[1]mit Farbstoff [2]mit Konservierungsstoff  [3]mit Nitritpökelsalz [4]mit Antioxidationsmittel [5]mit Geschmacksverstärker [6]geschwefelt [7]geschwärzt [8]mit Phosphat<br>
					[9]mit Milcheiweiß [10]koffeinhaltig [11]chininhaltig [12]mit Süßungsmittel(n) [13]enthält eine Phenylalaninquelle [14]gewachst [15]mit Taurin<br>[16]enthält Sojaöl; aus genetisch veränderter Soja hergestellt<br>
					Phosphate: Unser Milchkäse ist ein Weichkäse, welcher aus Milch, verschiedenen Milchsäurekulturen und Lab besteht<br><br><b>Alle Preise inklusive 19% Mehrwertsteuer</b>
				</p>
			</div>
		
		</div>
		<?php
	}


    protected function additionalMetaData()
    {
        // TODO: Implement additionalMetaData() method.
    }

    protected function footerScripts()
    {
        // TODO: Implement footerScripts() method.
    }
}


?>