<?php
require_once("prepare.php");

//https://wp-mix.com/php-get-server-information/
function shapeSpace_server_memory_usage()
{
 
	$free = shell_exec('free');
	$free = (string)trim($free);
	$free_arr = explode("\n", $free);
	$mem = explode(" ", $free_arr[1]);
	$mem = array_filter($mem);
	$mem = array_merge($mem);
	$memory_usage = round($mem[2] / 1024) . " / " . round($mem[1] / 1024) .  " MB";
 
	return $memory_usage;	
}

function shapeSpace_server_uptime()
{
	$uptime = floor((int) preg_replace ('/\.[0-9]+/', '', file_get_contents('/proc/uptime')));
	return (int)$uptime;	
}

function get_process_system_usage($processname)
{
    $pid = exec('pgrep ' . $processname);
    $pscmd = "ps -p " . $pid . " -o %cpu,%mem | sed -n 2p";
    $ps = (string)trim(exec($pscmd));
    $usage = array_merge(array_filter(explode(" ", $ps)));
    return $usage;
}

function checkProcessStatus($command)
{
    $out = exec($command);
    return $out != "";
}

function getGitVersion()
{
    $gitver = exec('git rev-parse HEAD');
    return $gitver;
}

function getProcessUptime($processName)
{
    $uptime = exec('ps -p `pidof ' . $processName .  '` -o etimes | tail -n 1');
    return $uptime;
}

$xlxdStatus = checkProcessStatus($Service['xlxdStatusCommand']);
$sgsStatus  = checkProcessStatus($Service['sgsStatusCommand']);
?>
<div class="row justify-content-md-center">
    <div class="col">
        <table class="table table-hover table-sm table-responsive-md">
            <thead class="thead-light">
                <tr>
                    <th scope="col" colspan="2">
                        <h5><strong>System Info</strong></h5>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Local time</th>
                    <td><?php echo date("Y-m-d H:i:s"); ?></td>
                </tr>
                <tr>
                    <th scope="row">System up time</th>
                    <td><?php echo FormatSeconds(shapeSpace_server_uptime()); ?></td>
                </tr>
                <tr>
                    <th scope="row">System memory usage</th>
                    <td><?php echo shapeSpace_server_memory_usage(); ?></td>
                </tr>
                <tr>
                    <th scope="row">CPU Load (avg. last minute)</th>
                    <td><?php echo round(sys_getloadavg()[0] * 100.0, 1) . "%"; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col">
        <table class="table table-hover table-sm table-responsive-md">
            <thead class="thead-light">
                <tr>
                    <th scope="col" colspan="2">
                        <h5><strong>XLX Software Runtime Info</strong></h5>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Software Version</th>
                    <td><?php echo $PageOptions['xlxdVersion'] ?></td>
                </tr>
                <tr>
                    <th scope="row">Process status</th>
                    <td><img src="<?php echo ($xlxdStatus ? "img/up.png" : "img/down.png" )?>"></td>
                </tr>
                <tr>
                    <th scope="row">Process up time</th>
                    <td><?php echo ($xlxdStatus ? FormatSeconds($Reflector->GetServiceUptime()) : "-"); ?></td>
                </tr>
                <tr>
                    <th scope="row">CPU usage</th>
                    <td><?php echo ($xlxdStatus ? get_process_system_usage("xlxd")[0] . "%" : "-"); ?></td>
                </tr>
                <tr>
                    <th scope="row">Memory usage</th>
                    <td><?php echo ($xlxdStatus ? get_process_system_usage("xlxd")[1] . "%" : "-"); ?></td>
                </tr>
                <tr>
                    <th scope="row">Transcoding status</th>
                    <td><img
                            src="<?php echo checkProcessStatus($Service['AmbedStatusCommand']) ? "img/up.png" : "img/down.png" ?>">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php if($PageOptions['SGS']['Show']) { ?>
    <div class="col">
        <table class="table table-hover table-sm table-responsive-md">
            <thead class="thead-light">
                <tr>
                    <th scope="col" colspan="2">
                        <h5><strong>Smart Group Software Runtime Info</strong></h5>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Software Version</th>
                    <td><?php echo $PageOptions['SGS']['version'] ?></td>
                </tr>
                <tr>
                    <th scope="row">Process status</th>
                    <td><img src="<?php echo ($sgsStatus ? "img/up.png" : "img/down.png" )?>"></td>
                </tr>
                <tr>
                    <th scope="row">Process up time</th>
                    <td><?php echo ($sgsStatus ? FormatSeconds(getProcessUptime("sgs-xl")) : "-"); ?></td>
                </tr>
                <tr>
                    <th scope="row">CPU usage</th>
                    <td><?php echo ($sgsStatus ? get_process_system_usage("sgs-xl")[0] . "%" : "-"); ?></td>
                </tr>
                <tr>
                    <th scope="row">Memory usage</th>
                    <td><?php echo ($sgsStatus ? get_process_system_usage("sgs-xl")[1] . "%" : "-"); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <div class="col">
        <table class="table table-hover table-sm table-responsive-md">
            <thead class="thead-light">
                <tr>
                    <th scope="row" colspan="2">
                        <h5><strong>Dashboard Info</strong></h5>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Git Version</th>
                    <td><?php echo getGitVersion(); ?></td>
                </tr>
                <tr>
                    <th scope="row" rowspan="4">Credits</th>
                </tr>
                <tr>
                    <td>Developed by F4FXL</td>
                </tr>
                <tr>
                    <td>Based on <a target="_blank" href="https://github.com/LX3JL/xlxd/tree/master/dashboard">XLX Dashboard</a> by LX1IQ</td>
                </tr>
                <tr>
                    <td>Based on <a target="_blank" href="https://github.com/K2IE/sgsheard/">SGS Heard</a> by K2IE</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>