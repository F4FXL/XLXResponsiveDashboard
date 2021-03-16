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

$INPUT = "";

if ($Result) {

    while (!feof ($Result)) {
        $INPUT .= fgets ($Result, 1024);
    }

    $XML = new ParseXML();
    $Reflectorlist = $XML->GetElement($INPUT, "reflectorlist");
    $Reflectors    = $XML->GetAllElements($Reflectorlist, "reflector");
}

fclose($Result);
?>

<div class="row justify-content-md-center">
    <div class="col">
        <table class="table table-hover table-sm table-responsive-md">
            <thead class="thead-light">
                <tr>
                    <th scope="row">#</th>
                    <th scope="row">XLX Peer</th>
                    <th scope="row">Last Heard</th>
                    <th scope="row">Linked for</th>
                    <th scope="row">Protocol</th>
                    <th scope="row">Module</th>
                    <?php
                    if ($PageOptions['PeerPage']['IPModus'] != 'HideIP') {
                    echo '
                    <th scope="row">IP</th>';
                    }
                    ?>
                </tr>
            </thead>
            <?php

$Reflector->LoadFlags();

for ($i=0;$i<$Reflector->PeerCount();$i++) {
         
   echo '
  <tr>
   <th scope="row">'.($i+1).'</th>';

   $Name = $Reflector->Peers[$i]->GetCallSign();
   $URL = '';

    for ($j=1;$j<count($Reflectors);$j++) {
        if ($Name === $XML->GetElement($Reflectors[$j], "name")) {
            $URL  = $XML->GetElement($Reflectors[$j], "dashboardurl");
        }
    }
    if ($Result && (trim($URL) != "")) {
        echo '<td><a href="'.$URL.'" target="_blank" class="pl" title="Visit the Dashboard of&nbsp;'.$Name.'">'.$Name.'</a></td>';
    } else {
        echo '<td>'.$Name.'</td>';
    }
    echo '<td>'.date("d.m.Y H:i", $Reflector->Peers[$i]->GetLastHeardTime()).'</td>';
    echo '<td>'.FormatSeconds(time()-$Reflector->Peers[$i]->GetConnectTime()).' s</td>';
    echo '<td>'.$Reflector->Peers[$i]->GetProtocol().'</td>';
    echo '<td><a href="index.php?show=users&module=' . $Reflector->Peers[$i]->GetLinkedModule() . '" class="pl">' . $Reflector->Peers[$i]->GetLinkedModule() . '</a></td>';
    if ($PageOptions['PeerPage']['IPModus'] != 'HideIP') {
        echo '<td>';
        $Bytes = explode(".", $Reflector->Peers[$i]->GetIP());
        if ($Bytes !== false && count($Bytes) == 4) {
            switch ($PageOptions['PeerPage']['IPModus']) {
                case 'ShowLast1ByteOfIP'      : echo $PageOptions['PeerPage']['MasqueradeCharacter'].'.'.$PageOptions['PeerPage']['MasqueradeCharacter'].'.'.$PageOptions['PeerPage']['MasqueradeCharacter'].'.'.$Bytes[3]; break;
                case 'ShowLast2ByteOfIP'      : echo $PageOptions['PeerPage']['MasqueradeCharacter'].'.'.$PageOptions['PeerPage']['MasqueradeCharacter'].'.'.$Bytes[2].'.'.$Bytes[3]; break;
                case 'ShowLast3ByteOfIP'      : echo $PageOptions['PeerPage']['MasqueradeCharacter'].'.'.$Bytes[1].'.'.$Bytes[2].'.'.$Bytes[3]; break;
                default                       : echo '<a href="http://'.$Reflector->Peers[$i]->GetIP().'" target="_blank" style="text-decoration:none;color:#000000;">'.$Reflector->Peers[$i]->GetIP().'</a>';
            }
        }
        echo '</td>';
    }
    echo '</tr>';
    if ($i == $PageOptions['PeerPage']['LimitTo']) {
        $i = $Reflector->PeerCount()+1;
    }
}

?>

        </table>