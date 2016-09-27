<?php

session_start();
include "function.php";
$link = connectDB();
$_GET = filterParameters($_GET);
$_POST = filterParameters($_POST);

if(isset($_GET['func'])) {
	if($_GET['func'] == 'showErgebnisse')			{ showErgebnisse(); }
	if($_GET['func'] == 'showErgebnisseM')			{ showErgebnisseM(); }
}

//phpinfo(32);


$link->close();
exit;

?>