<?php

/*
 *  This dashboard is being developed by the DVBrazil Team as a courtesy to
 *  the XLX Multiprotocol Gateway Reflector Server project.
 *  The dashboard is based of the Bootstrap dashboard template. 
*/

if (file_exists("./pgs/functions.php")) {
    require_once("./pgs/functions.php");
} else {
    die("functions.php does not exist. I");
}
if (file_exists("./pgs/config.inc.php")) {
    require_once("./pgs/config.inc.php");
} else {
    die("config.inc.php does not exist.");
}

if (!class_exists('ParseXML')) require_once("./pgs/class.parsexml.php");
if (!class_exists('Node')) require_once("./pgs/class.node.php");
if (!class_exists('xReflector')) require_once("./pgs/class.reflector.php");
if (!class_exists('Station')) require_once("./pgs/class.station.php");
if (!class_exists('Peer')) require_once("./pgs/class.peer.php");
if (!class_exists('Interlink')) require_once("./pgs/class.interlink.php");

$Reflector = new xReflector();
$Reflector->SetFlagFile("./pgs/country.csv");
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
        if ($LastSync < (time() - $CallingHome['PushDelay'])) { $Ressource=@fopen($CallingHome['HashFile'], "w" );
            if($Ressource)
            {
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

function getContentHandler()
{
    switch ($_GET["show"]) {
        case 'users'      :
            return "pgs/users.php?module=" . $_GET["module"];
        case 'repeaters'  :
            return "pgs/repeaters.php?module=" . $_GET["module"];
        case 'liveircddb' :
            return "pgs/liveircddb.php?module=" . $_GET["module"];
        case 'peers'      :
            return "pgs/peers.php?module=" . $_GET["module"];
        case 'reflectors' :
            return "pgs/reflectors.php?module=" . $_GET["module"];
        case 'moduleslist' :
            return "pgs/moduleslist.php?module=" . $_GET["module"];
        case 'sysinfo' :
            return "pgs/sysinfo.php?module=" . $_GET["module"];
        case 'sgs' :
            return "pgs/sgs.php?module=" . $_GET["module"];
        default           :
            return "pgs/users.php?module=" . $_GET["module"];
    }
}

function getRefreshTimeout()
{
    global $PageOptions;
    switch ($_GET["show"]) {
        case 'users'      :
            return $PageOptions['PageRefreshDelay'];
        case 'repeaters'  :
            return $PageOptions['PageRefreshDelay'];
        case 'liveircddb' :
            return $PageOptions['PageRefreshDelay'];
        case 'peers'      :
            return $PageOptions['PageRefreshDelay'];
        case 'reflectors' :
            return null;
        case 'moduleslist' :
            return null;
        case 'sysinfo' :
            return $PageOptions['PageRefreshDelay'];
        case 'sgs' :
            return $PageOptions['PageRefreshAlt'];
        default           :
            return $PageOptions['PageRefreshDelay'];;
    }
}
?>
<!DOCTYPE html>
<html lang=" en">
    <head>
        <base href="/" /> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="<?php echo $PageOptions['MetaDescription']; ?>" />
        <meta name="keywords" content="<?php echo $PageOptions['MetaKeywords']; ?>" />
        <meta name="author" content="<?php echo $PageOptions['MetaAuthor']; ?>" />
        <meta name="revisit" content="<?php echo $PageOptions['MetaRevisit']; ?>" />
        <meta name="robots" content="<?php echo $PageOptions['MetaAuthor']; ?>" />

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo $Reflector->GetReflectorName(); ?> Reflector Dashboard</title>
        <link rel="icon" href="./favicon.ico" type="image/vnd.microsoft.icon">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
            integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <!-- own css -->
        <link rel="stylesheet" href="/css/style.css">

        <style type="text/css">
        /* Make sure pills strech to whole screen on lg break point */
        @media (max-width: 992px) {
            .nav-fill .nav-item {
                width: 100% !important;
                flex-basis: unset !important;
            }
        }
        </style>
        <script language="JavaScript">
            function reloadDashboard()
            {
                var xhr=null;
    
                if (window.XMLHttpRequest) {
                    xhr = new XMLHttpRequest();
                }
                else if (window.ActiveXObject)
                {
                    xhr = new ActiveXObject("Microsoft.XMLHTTP");
                }
                    
                xhr.open("GET", "<?php echo getContentHandler(); ?>&nocache=" + Date.now(), true);
                    
                xhr.onreadystatechange = function() {
                    if(xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById('dashboard-content').innerHTML = xhr.responseText;
                    }
                }
                xhr.send(null);
                <?php
                $timeout = getRefreshTimeout();
                if(isset($timeout))
                    echo "PageRefresh = setTimeout(reloadDashboard, " . $timeout . ");\n";
                ?>
            }
        </script>
    </head>

    <body onload="reloadDashboard()">
        <?php if (file_exists("./tracking.php")) {
    include_once("tracking.php");
} ?>

        <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
            <div class="media border-right mr-3 pr-0">
                <img class="m-0 p-0" style="height:75px;" src="<?php echo $PageOptions['LogoFile']; ?>">
                <div class="media-body text-center">
                    <p class="navbar-brand h1 mt-0 mb-0 ml-3 mr-3"><?php echo $Reflector->GetReflectorName(); ?></p>
                    <p class="border-top pt-1 mt-0 mb-0 ml-3 mr-3"><small><a target="_blank"
                                href="<?php echo $PageOptions['CustomTXTLink']; ?>"><?php echo $PageOptions['CustomTXT'];?></a></small>
                    </p>
                </div>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="nav collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="nav nav-pills nav-fill">
                    <a class="nav-link nav-item<?php echo (($_GET['show'] == "users" || $_GET['show'] == "") && $_GET['module'] == "") ? ' active' : ''; ?>"
                        href="./index.php?show=users">Utilisateurs (<?php echo $Reflector->StationCount(); ?>) / Modules
                        (<?php echo count($Reflector->GetModules()); ?>)</a>
                    <a class="nav-link nav-item<?php echo ($_GET['show'] == "repeaters") ? ' active' : ''; ?>"
                        href="./index.php?show=repeaters">Relais / Nodes (<?php echo $Reflector->NodeCount(); ?>)</a>
                    <a class="nav-link nav-item<?php echo ($_GET['show'] == "moduleslist") ? ' active' : ''; ?>"
                        href="./index.php?show=moduleslist">Liste des Modules
                        (<?php echo count($PageOptions['ModuleNames']); ?>)</a>
                    <a class="nav-link nav-item<?php echo ($_GET['show'] == "peers") ? ' active' : ''; ?>"
                        href="./index.php?show=peers">Interlink (<?php echo $Reflector->PeerCount(); ?>)</a>
                    <a class="nav-link nav-item<?php echo ($_GET['show'] == "sgs") ? ' active' : ''; ?>"
                        href="./index.php?show=sgs">Smart Group Server</a>
                    <a class="nav-link nav-item<?php echo ($_GET['show'] == "reflectors") ? ' active' : ''; ?>"
                        href="./index.php?show=reflectors">Liste des réflecteurs XLX</a>
                    <a class="nav-link nav-item<?php echo ($_GET['show'] == "sysinfo") ? ' active' : ''; ?>"
                        href="./index.php?show=sysinfo">Infos. Système</a>
                    <!-- <a class="nav-link nav-item<?php echo ($_GET['show'] == "liveircddb") ? ' active' : ''; ?>" href="./index.php?show=liveircddb">D-Star live</a> -->
                </div>
            </div>
        </nav>
        <?php
            if ($CallingHome['Active']) {
                if (!is_readable($CallingHome['HashFile']) && (!is_writeable($CallingHome['HashFile']))) {
                    echo '
                    <div class="error">
                        your private hash in ' . $CallingHome['HashFile'] . ' could not be created, please check your config file and the permissions for the defined folder.
                    </div>';
                }
            }
            ?>
        <div class="container-fluid" id="dashboard-content">
            <div class="row justify-content-md-left">
                <h5>Loading ...</h5>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p><a
                        href="mailto:<?php echo $PageOptions['ContactEmail']; ?>"><?php echo $PageOptions['ContactEmail']; ?></a>
                </p>
            </div>
        </footer>

        <!-- Bootstrap core JavaScript
    ================================================== -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
        </script>
    </body>

    </html>