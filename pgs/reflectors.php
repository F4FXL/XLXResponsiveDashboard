<?php

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


$Result = @fopen($CallingHome['ServerURL']."?do=GetReflectorList", "r");

if (!$Result) die("HEUTE GIBTS KEIN BROT");

$INPUT = "";
while (!feof ($Result)) {
    $INPUT .= fgets ($Result, 1024);
}
fclose($Result);

$XML = new ParseXML();
$Reflectorlist = $XML->GetElement($INPUT, "reflectorlist");
$Reflectors    = $XML->GetAllElements($Reflectorlist, "reflector");

?>

<div class="row justify-content-md-center">
    <div class="col">
        <table class="table table-hover table-sm table-responsive-md">
            <thead class="thead-light">
                <tr class="table-center">
                    <th scope="row">#</th>
                    <th scope="row">Réflecteur</th>
                    <th scope="row">Pays</th>
                    <th scope="row">Etat</th>
                    <th scope="row">Infos</th>
                </tr>
            </thead>
            <tbody>
                <?php

for ($i=0;$i<count($Reflectors);$i++) {
   
   $NAME          = $XML->GetElement($Reflectors[$i], "name");
   $COUNTRY       = $XML->GetElement($Reflectors[$i], "country");
   $LASTCONTACT   = $XML->GetElement($Reflectors[$i], "lastcontact");
   $COMMENT       = $XML->GetElement($Reflectors[$i], "comment");
   $DASHBOARDURL  = $XML->GetElement($Reflectors[$i], "dashboardurl");
   
   echo '
 <tr>
   <th scope="row">'.($i+1).'</th>
   <td><a href="'.$DASHBOARDURL.'" target="_blank" class="pl" title="Visit the Dashboard of&nbsp;'.$NAME.'">'.$NAME.'</a></td>
   <td>'.$COUNTRY.'</td>
   <td><img src="./img/'; if ($LASTCONTACT<(time()-600)) { echo 'down'; } ELSE { echo 'up'; } echo '.png" class="table-status" alt=""></td>
   <td>'.$COMMENT.'</td>
 </tr>';
}
?>
            </tbody>
        </table>
    </div>
</div>