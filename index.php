<?php
/*
 * Created on 20.11.2015
 *
 */

if (stristr($_SERVER["REQUEST_URI"], '/index.php') === false) {
	header('Location: '.$_SERVER["SCRIPT_NAME"].'?func=auswertung');
}

$showContent = false;
session_start();
include "function.php";

$link = connectDB();
$allowedFunctions = array('auswertung');

$_GET = filterParameters($_GET);
$_POST = filterParameters($_POST);
$html = "";

# Wenn keine Funktion übergeben wurde, dann wird die Veranstaltungsauswahl angezeigt
if (!isset($_GET['func'])) {
	$func[0] = 'auswertung';
} else {
	$func[0] = "";
	$func[1] = "";
	$func = explode(".", $_GET['func']);
}	

if(isset($_GET['id'])) {
	$_SESSION['vID'] = $_GET['id'];
	selectVeranstaltung( $_SESSION['vID'] );
}

#Prüfung ob eine erlaubte function übergeben wird.
if(array_search($func[0], $allowedFunctions) !== false) {
	if (isset($_SESSION['vID']) || $func[0] == 'auswertung') {
		$showContent = true;
	}
}



if (!isset($_SESSION['vTitel']))      { $_SESSION['vTitel'] = ''; }
if (!isset($_SESSION['vUntertitel'])) { $_SESSION['vUntertitel'] = ''; }
if (!isset($_SESSION['rID']))         { $_SESSION['rID'] = 0; }

$testDiv = false;
if((stristr($_SERVER["SCRIPT_NAME"], 'test') !== FALSE) || (stristr($config['dbname'], 'test'))){
	$testDiv = true;
}
?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="cache-control" content="no-cache; no-store; max-age=0" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="description" content="openTiming SportsTiming" />
	<meta http-equiv="Content-Language" content="de" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<meta name="decorator" content="main" />
	
	<title>openTiming</title>
	
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/opentiming.css" rel="stylesheet">
	<link href="css/font-awesome.css" rel="stylesheet">
	
	<script src="js/jquery-2.1.4.js"></script>
	
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/opentiming.js"></script>
    <script src="js/base64.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>

</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#"><span class="navbar-brand-orange">open</span>Timing</a>
			</div>
		</div>
	</nav>
	
	<div class="main">
	<h2 id="page-header" class="page-header text-center"><?php echo $_SESSION['vTitel']; ?></h2>
		<div class="race-table">

<?php
if ($showContent == true) { 
	$func[0]();
} else {
	echo "Use of disallowed function"; die;
}
?>
		
		</div>

		<div class="content-table"></div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal" aria-labelledby="gridSystemModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
      			<div class="modal-header">
      				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      				<h4 class="modal-title" id="gridSystemModalLabel">&nbsp;</h4>
      			</div>
      			<div class="modal-body" id="modal-body">
      				<span class="text-muted">loading...</span>
      			</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" onclick="clearModal(); return false;" data-dismiss="modal">Close</button>
				</div>
    		</div>
 		</div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal2" aria-labelledby="gridSystemModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
      			<div class="modal-header">
      				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      				<h4 class="modal-title" id="gridSystemModalLabel">&nbsp;</h4>
      			</div>
      			<div class="modal-body" id="modal2-body">
      				<span class="text-muted">loading...</span>
      			</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" onclick="clearModal(); return false;" data-dismiss="modal">Close</button>
				</div>
    		</div>
 		</div>
	</div>

			
</body>


</html>

<?php
$link->close();
#phpinfo(32);
#print_r($_SESSION);
?>