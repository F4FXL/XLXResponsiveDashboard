
<?php
function isJson($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function readJson($sock)
{
    $s = "";
    while(true)
    {
        $c = socket_recvfrom($sock, $buf, 128 * 1024, 0, $from, $port);
        $s .= $buf;
        if(isJson($s) && $s != "")
            break;
    }

    return $s;
}

function startSession()
{
    $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    $msg = "hello";
    $len = strlen($msg);
    socket_sendto($sock, $msg, $len, 0, '213.186.34.50', 10001);
    return $sock;
}

function closeSession($sock)
{
    $msg = "bye";
    $len = strlen($msg);
    socket_sendto($sock, $msg, $len, 0, '213.186.34.50', 10001);
    socket_close($sock);
}

$sock = startSession();

$json = "{}";
$modules  = readJson($sock);
$nodes    = readJson($sock);
$stations = readJson($sock);

switch($_GET["action"])
{
    case "modules" :
        $json = $modules;
        break;
    case "nodes" :
        $json = $nodes;
        break;
    case "stations" :
        $json = $stations;
        break;
    default :
        $json = $modules;
        break;
}

closeSession($sock);

//header('Content-Type: application/json');
echo $json;
?>
