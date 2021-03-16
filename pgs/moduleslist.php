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
?>

<div class="row justify-content-md-center">
    <div class="col">
        <table class="table table-hover table-sm table-responsive-md">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Module</th>
                    <th scope="col">Nom du Module</th>
                    <th scope="col">Utilisateurs</th>
                    <th scope="col">Relais</th>
                    <!--<th class="col-md-1">Se Connecter</th>-->
                </tr>
            </thead>
            <tbody>
                <?php


foreach($PageOptions['ModuleNames'] as $module => $description)
{
?>
                <tr>
                    <th scope="row"><?php echo $module;?></th>
                    <td><a href="index.php?show=users&module=<?php echo $module;?>"
                            class="pl"><?php echo strip_tags($description);?></a></td>
                    <td><?php echo count($Reflector->GetStationsInModule($module));?></td>
                    <td><?php echo count($Reflector->GetNodesInModulesById($module));?></td>
                </tr>
                <?php
}

?>
            </tbody>
        </table>