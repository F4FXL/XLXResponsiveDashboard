<?php
/*

Original code by Danb Srebnick K2IE https://github.com/K2IE/sgsheard/

*/

if (file_exists("functions.php")) {
    require_once("functions.php");
} else {
    die("functions.php does not exist.");
}
if (file_exists("config.inc.php")) {
    require_once("config.inc.php");
} else {
    die("config.inc.php does not exist.");
}

if (!class_exists('ParseXML')) require_once("class.parsexml.php");
if (!class_exists('Node')) require_once("class.node.php");
if (!class_exists('xReflector')) require_once("class.reflector.php");
if (!class_exists('Station')) require_once("class.station.php");
if (!class_exists('Peer')) require_once("class.peer.php");
if (!class_exists('Interlink')) require_once("class.interlink.php");

$Reflector = new xReflector();
$Reflector->SetFlagFile("country.csv");
$Reflector->SetPIDFile($Service['PIDFile']);
$Reflector->SetXMLFile($Service['XMLFile']);

$Reflector->LoadXML();

if ($CallingHome['Active']) {

    $CallHomeNow = false;
    if (!file_exists($CallingHome['HashFile'])) {
        $Hash = CreateCode(16);
        $LastSync = 0;
        $Ressource = @fopen($CallingHome['HashFile'], "w");
        if ($Ressource) {
            @fwrite($Ressource, "<?php\n");
            @fwrite($Ressource, "\n" . '$LastSync = 0;');
            @fwrite($Ressource, "\n" . '$Hash     = "' . $Hash . '";');
            @fwrite($Ressource, "\n\n" . '?>');
            @fclose($Ressource);
            @exec("chmod 777 " . $CallingHome['HashFile']);
            $CallHomeNow = true;
        }
    } else {
        include($CallingHome['HashFile']);
        if ($LastSync < (time() - $CallingHome['PushDelay'])) {
            $Ressource=@fopen($CallingHome['HashFile'], "w" );
            if ($Ressource) {
                @fwrite($Ressource, "<?php\n");
                @fwrite($Ressource, "\n" . '$LastSync = ' . time() . ';');
                @fwrite($Ressource, "\n" . '$Hash     = "' . $Hash . '";');
                @fwrite($Ressource, "\n\n" . '?>');
                @fclose($Ressource);
            }
            $CallHomeNow = true;
        }
    }

    if ($CallHomeNow || isset($_GET['callhome'])) {
        $Reflector->SetCallingHome($CallingHome, $Hash);
        $Reflector->ReadInterlinkFile();
        $Reflector->PrepareInterlinkXML();
        $Reflector->PrepareReflectorXML();
        $Reflector->CallHome();
    }
} else {
    $Hash = "";
}
?>

<div class="row justify-content-md-center">
    <div class="col">
        <?php

$output = shell_exec("/usr/local/bin/sgsremote " . $PageOptions['SGSServer'] . " list all");
$oparray = preg_split('/\n+/', $output);
unset($oparray[0]);	#get rid of the header
array_pop($oparray);	#get rid of the last element
?>
        <table class="table table-hover table-sm table-responsive-md">
            <thead class="thead-light">
                <tr>
                    <th colspan="6">
                        <h5><strong><?php echo $PageOptions['SGSTitle'] ?></strong></h5>
                    </th>
                </tr>
                <tr>
                    <th scope="col">Logon</th>
                    <th scope="col">Logoff</th>
                    <!-- <th scope="col">Channel</th> -->
                    <th scope="col">Description</th>
                    <th scope="col">Status</th>
                    <th scope="col">Reflector</th>
                    <th scope="col">Timeout</th>
                </tr>
            </thead>
            <tbody>

                <?php

foreach ($oparray as $array) {
	$logon   = substr($array, 0, 8);
	$logoff  = substr($array, 9, 8);
	$channel = substr($array, 18, 8);
	$desc    = substr($array, 27, 20);
	$status  = substr($array, 48, 8);
	$refl    = substr($array, 57, 8);
	if(trim($refl) != "")
		$refl	 = "XLX" . substr($refl,3);
	$timeout = substr($array, 69, 4);

	echo '<tr class="table-center"><td>'.$logon.'</td><td>'.$logoff.'</td><!-- <td>'.$channel.'</td> --><td>'.$desc.'</td><td>'.$status.'</td><td>'.$refl.'</td><td>'.$timeout.'</td></tr>';
	}
?>
            </tbody>
        </table>
    </div>
</div>

<?php
$newrow = 0;
foreach ($oparray as $confs) {
	if($newrow == 0) echo '<div class="row justify-content-md-center">' ;

	echo '<div class="table-responsive col-md-3">';
	$login = substr($confs, 0, 8);
	$desc  = substr($confs, 27, 20);

	$cmd = '/usr/local/bin/sgsremote "'.$PageOptions['SGSServer'].'" list '. str_replace(" ", "_", $login) .' | grep User\ = | cut -d "=" -f2- | cut -d " " -f2-';
	$output = shell_exec($cmd);

	$oparray = preg_split('/\n+/', trim($output));


?>
<table class="table table-hover table-sm table-sm-responsive">
    <thead class="thead-light">
        <tr>
            <th colspan="2" scope="row"><?php echo $login . " - "  . $desc; ?></th>
        </tr>
        <tr>
            <th scope="col">Callsign</th>
            <th scope="col">Last Heard</th>
        </tr>
    </thead>
    <?php
	if ($output) {
		foreach($oparray as $array) {
			$line = preg_split('/,\s+/', $array);
			[$callsign, $timer] = $line;
			preg_match_all('!\d+!', $timer, $min);
			$minutes = $min[0][0];
			$timer = "$minutes minutes ago";

			$basecs = substr($callsign, 0, strpos($callsign, ' '));
			echo '<tr><td><a target ="_blank" href="https://www.qrz.com/db/'.$basecs.'">'.$callsign.'</a></td><td>'.$timer.'</td></tr>';
		}
	}
	else {
		echo '<tr><td colspan="2" scope="row">No users in group</td></tr>';
	}

	
	echo "</table>";
	echo '</div>';
	$newrow =  ($newrow + 1) % 4;
	if($newrow == 0) echo '</div>';
}

/*$now = date('Y-m-d H:i:s');
echo "<BR><BR><I>Last updated: $now</I>\n";*/

?>
    </div>
    </div>