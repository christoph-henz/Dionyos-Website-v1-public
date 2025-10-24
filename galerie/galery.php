<?php

class Gallery{
	
	private $path;
	
	function __construct($path){
		$this->path = $path;
	}
	
	public function display(){
		?>
			<div class="row">
				
				<?php
				if(is_dir($this->path)){
					if($folder = opendir($this->path)){
						echo "<div class='column'>";
						$c = 1;
						while(($file = readdir($folder)) !== false){
							if(is_file($this->path."/".$file)){
								echo "<img src=\"".$this->path."/".$file."\">";
							if($c%6==0){
								echo "</div>";
								echo "<div class='column'>";
							}
							$c++;
							}
							
						}
						echo "</div>";
					}else{
						echo "fehler";
					}
					
					closedir($this->path);
				}
		
		
					
				
				?>
			
			</div>
		<?php
	}
	
}

?>