<?php
include_once("imenu.php");
class Article implements MenuInterface, JsonSerializable{


	private $number;

	private $name;

	private $description;
	
	private $price;
	
	private $rootPath;
	
	private $json;
	
	function __construct($number, $name, $description, $price){
		$this->number = $number;
		$this->name = $name;
		$this->description = $description;
		$this->price = floatval($price);
		$this->json = json_encode($this,0,512);
		
	}

	public function getNumber(){
		return $this->number;
	}

	public function getName(){
		return $this->name;
	}

	public function getDescription(){
		return $this->description;
	}
	
	public function getPrice() : float{
		return $this->price;
	}

	public function displayHTML(){
		?>
			<div class="menu-item">
				
				<div class="menu-item-number">
					<?php
						echo $this->number;
					?>
				</div>
				<div class="menu-item-name">
					<?php
						echo "<span class=\"name-highlight\">".$this->name."</span> ".$this->description;
					?>
				</div>
				
				<div class="menu-item-price">
					<?php
						if($this->number != "")
						echo number_format($this->price,2,",","") . "€";
					?>
				</div>
				
			</div>
		<?php
	}
	public function displayAsInteractiveHTML(){
		?>
			<div class="menu-item">
				
				<div class="menu-item-number">
					<?php
						echo $this->number;
					?>
				</div>
				<div class="menu-item-name">
					<?php
						echo "<span class=\"name-highlight\">".$this->name."</span> ".$this->description;
					?>
				</div>
				
				<div class="menu-item-price">
					<?php
						echo number_format($this->price,2,",","") . "€";
					?>
				</div>
					<?php
						if($this->number != ""){
					?>
				<div style="display: none" class="num-articles">
					<input type="number" value="1" min="1" max="5" contenteditable="false">
				</div>
				<button class="into-shoppingbag" type="button">
                        <div onclick="void(0)" class="plus"></div>
				</button>
					<?php
						}
					?>
				
			</div>
		<?php
	}

    public static function fromDatabaseRow($row): Article
    {
        if (!isset($row['Plu'], $row['Name'], $row['Description'], $row['Price'])) {
            throw new Exception("Ungültige Datenzeile: " . json_encode($row));
        }

        return new Article(
            $row['Plu'],
            $row['Name'],
            $row['Description'],
            $row['Price']
        );
    }



    public function setRootPath($path){
		$this->rootPath = $path;
	}
	
	 public function jsonSerialize():mixed {
        return [
            'number' => $this->number,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price
        ];
    }

}


?>