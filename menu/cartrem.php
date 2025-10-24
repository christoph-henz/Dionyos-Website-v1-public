<?php
session_start();
include_once("../orders/chart.php");
use Cart;
if(isset($_GET['art'])){
	$c = unserialize($_SESSION['cart']);
	$a = $_GET['art'];
	
	$c->removeFromCart($a);
	$_SESSION['cart'] = serialize($c);
	$c->printCart();
}else{
	echo "<h1>unexpected Failure</h1>";
}

?>