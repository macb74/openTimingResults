<?php

function auswertung() {
	showRaceList();	
}

function getSeconds($s) {
	date_default_timezone_set("UTC");
	$sec = strtotime($s);
	date_default_timezone_set("Europe/Berlin");
	return $sec;
}
	
function getRealTime($startZeit, $zeit) {
	$zielSec = getSeconds($zeit);
	$startSec = getSeconds($startZeit);
	$zeit = $zielSec - $startSec;
	
	$zeit = sec2Time($zeit);
	return $zeit;
}

function sec2Time($sec){
  if(is_numeric($sec)){
    if($sec >= 3600){
      	$value["hours"] = floor($sec/3600);
      	if($value["hours"] < 10) { $value["hours"] = "0".$value["hours"]; }
      	$sec = ($sec%3600);
    } else {
    	$value["hours"] = "00";
    }
    if($sec >= 60){
      	$value["minutes"] = floor($sec/60);
      	if($value["minutes"] < 10) { $value["minutes"] = "0".$value["minutes"]; }
      	$sec = ($sec%60);
    } else {
		$value["minutes"] = "00";
    }
    $value["seconds"] = floor($sec);
    if($value["seconds"] < 10) { $value["seconds"] = "0".$value["seconds"]; }
    
    $time = $value["hours"].":".$value["minutes"].":".$value["seconds"];
    return $time;
  }
}


function showRaceList() {
	
?>

	<script>

		$(document).ready(function(){
	
			$('[data-toggle="tooltip"]').tooltip({container: "body"});

			$('.last-race-update').mouseenter(function(data){
				var target = this;
				var rid = $( this ).attr('rid');
				var jqxhr = $.get( "ajaxRequest.php?func=getLastRaceUpdate&id=" + rid);
			
				jqxhr.done(function( data ) {
					$( target ).tooltip( {container: 'body' } )
					.attr('data-original-title', data)
					.tooltip('fixTitle')
					.tooltip('show');
				});
			});


			$(".btn").mouseup(function(){
			    $(this).blur();
			})

			
			<?php 

					if($_SESSION['rID'] != 0) {
						echo "showContent( '".$_SESSION['contentFunc']."', ".$_SESSION['rID']." )";
					}

			?>
			
		});
	
	</script>

	<h3>Auswertung</h3>
	
	<div class="table-responsive">
		<table class="table table-striped table-vcenter">
			<thead>
				<tr>
					<th>ID</th>
					<th>Titel</th>
					<th>Start</th>
					<th>Ergebnisse</th>
				</tr>
			</thead>
		<tbody>
	
<?php	
	
	$veranstaltung = $_SESSION['vID'];
	$sql = "select * from lauf where vID = $veranstaltung order by start asc, titel;";
	$result = dbRequest($sql, 'SELECT');

	if($result[1] > 0) {
		foreach ($result[0] as $row) {

			$count = getCountRunner($row['ID']);
			
?>

				<tr>
					<td><?php echo $row['ID']; ?></td>
					<td><?php echo $row['titel']; ?> <small><?php echo $row['untertitel']; ?> (<?php echo $count[0]; ?> / <span id="finisher-<?php echo $row['ID']; ?>"><?php echo $count[1] ?></span>)</small></td>
					<td><?php echo substr($row['start'], 10); ?></td>
					<td>
							<a class="btn btn-default btn-small-border" data-toggle="tooltip" title="Bildschirmliste" onclick="javascript:showContent('showErgebnisse', <?php echo $row['ID']; ?>)">
								Ergebnisliste Gesamt / Urkunden
							</a>

							<a class="btn btn-default btn-small-border" data-toggle="tooltip" title="PDF Gesammt" href="exportPDF.php?action=ergebnisGesamt&id=<?php echo $row['ID']; ?>" target="_new">
								<i class="fa fa-file-pdf-o"></i> Ergebnisliste Gesamt
							</a>

							<a class="btn btn-default btn-small-border" data-toggle="tooltip" title="PDF nach Klassen" href="exportPDF.php?action=ergebnisKlasse&id=<?php echo $row['ID']; ?>" target="_new">
								<i class="fa fa-file-pdf-o"></i> Ergebnisliste nach Klassen
							</a>

							<a class="btn btn-default btn-small-border" data-toggle="tooltip" title="PDF Ergebnisse Mannschaft" href="exportPDF.php?action=ergebninsMannschaft&id=<?php echo $row['ID']; ?>" target="_new">
								<i class="fa fa-file-pdf-o"></i> Ergebnisliste Mannschaft
							</a>
						<?php if($row['rundenrennen'] == 1) {?>
							<a class="btn btn-default btn-small-border" data-toggle="tooltip" title="PDF Rundenzeiten" href="exportRundenzeiten.php?&id=<?php echo $row['ID']; ?>" target="_new">
								<i class="fa fa-file-pdf-o"></i> Rundenzeiten
							</a>
						<?php } ?>
					</td>
				</tr>
	
<?php
	
		}
	}
	
?>
			</tbody>
		</table>
	</div>
	
<?php 

}


function getCountRunner($race) {

	$sql = "select id from teilnehmer where lid = $race and disq = 0 and del = 0;";
	$res = dbRequest($sql, 'SELECT');
	$count[0] = $res[1];
	
	$sql = "select id from teilnehmer where lid = $race and disq = 0 and del = 0 and zeit <> '00:00:00';";
	$res = dbRequest($sql, 'SELECT');
	$count[1] = $res[1];
	
	return $count;
}