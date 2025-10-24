<?php

include_once("article.php");
include_once("imenu.php");

class MenuSite implements MenuInterface{

	private $cat;

	private $loa;

	private $size;
	
	private $capacity;

	function __construct($cat, $capacity){
		$loa = array();
		$this->cat = $cat;
		$this->capacity = $capacity;
		$size = 0;

	}

    public function loadFromDatabase($category, $dbConnection)
    {
        $this->cat = $category;
        $this->loa = [];
        $this->size = 0;

        // SQL-Statement vorbereiten
        $stmt = $dbConnection->prepare("SELECT Plu, Name, Description, Price FROM Menu WHERE Category = ? ORDER BY Plu ASC");
        if (!$stmt) {
            throw new Exception("Datenbankfehler: " . $dbConnection->error);
        }

        $stmt->bind_param('s', $category);
        $stmt->execute();
        $result = $stmt->get_result();

        // Artikel laden
        while ($row = $result->fetch_assoc()) {
            $row['Description'] = $row['Description'] ?? ''; // Setze leeren String, falls Description NULL ist

            // Erstelle Artikel nur, wenn Name vorhanden ist
            if (!empty($row['Name'])) {
                $article = Article::fromDatabaseRow($row);
                $this->addArticle($article);
            }
        }

        $stmt->close();
    }




    public function addArticle($art){

		if($this->size < $this->capacity){
			$this->loa[] = $art;
			$this->size++;
		}else{
			throw new Exception("capacity_reached", 1);
			
		}
	
	}
	
	public function getArticle($number){
		for($i = 0; $i < $this->size; $i++){
			if($this->loa[$i]->getNumber() == $number){
				return $this->loa[$i];
			}
		}
		return null;
	}

	public function displayHTML(){
		?>
			<div class="menu-site menu-site-inactive">
				<?php
					echo "<div class=\"menu-site-header\">";
					echo "<h3>".$this->cat."</h3>";
					echo "</div>";
					for($i = 0; $i < $this->size; $i++){
							
							$this->loa[$i]->displayHTML();
						
						
					}
					
				?>
			
			</div>
		

		<?php
	}
	
	public function displayAsInteractiveHTML(){
		?>
			<div class="menu-site menu-site-inactive">
				<?php
					echo "<div class=\"menu-site-header\">";
					echo "<h3>".$this->cat."</h3>";
					echo "</div>";
					for($i = 0; $i < $this->size; $i++){
						
						
							$this->loa[$i]->displayAsInteractiveHTML();
						
						
					}
					
				?>
			
			</div>
		

		<?php
	}

    /**
     * @return mixed
     */
    public function getLoa()
    {
        return $this->loa;
    }



}
	

?>