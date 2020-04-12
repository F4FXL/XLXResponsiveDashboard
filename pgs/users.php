
<div class="row justify-content-md-center">
   <div class="col">
        <table class="table table-hover table-sm table-responsive-md">
            <thead>
                <tr class="table-center">   
                    <th scope="row">#</th>
                    <th scope="row">Flag</th>
                    <th scope="row">Callsign</th>
                    <th scope="row">Suffix</th>
                    <th scope="row">DPRS</th>
                    <th scope="row">Via / Peer</th>
                    <th scope="row">Last heard</th>
                    <th scope="row">Module<!-- <img src="./img/ear.png" alt="Listening on" / --></th>
                </tr>
            </thead>
            <tbody>
<?php

$Reflector->LoadFlags();
for ($i=0;$i<$Reflector->StationCount();$i++) {
   echo '
      <tr>
       <th scope="row">';
        if ($i == 0 && $Reflector->Stations[$i]->GetLastHeardTime() > (time() - 60)) {
            echo '<img src="./img/tx.gif" style="margin-top:3px;" height="20"/>';
        } else {
            echo($i + 1);
        }
        echo '</th>
        <td>';

        list ($Flag, $Name) = $Reflector->GetFlag($Reflector->Stations[$i]->GetCallSign());
        if (file_exists("./img/flags/".$Flag.".png"))
        {
           echo '<a href="#" data-toggle="tooltip" title="'. $Name . '"><img src="./img/flags/'.$Flag.'.png" class="table-flag" alt="'.$Name.'"></a>';
        }
        echo '</td>
   <td><a href="https://www.qrz.com/db/' . $Reflector->Stations[$i]->GetCallsignOnly() . '" class="pl" target="_blank">' . $Reflector->Stations[$i]->GetCallsignOnly() . '</a></td>
   <td>' . $Reflector->Stations[$i]->GetSuffix() . '</td>
   <td><a href="https://www.aprsdirect.com/details/main/name/' . $Reflector->Stations[$i]->GetCallsignOnly() . '" class="pl" target="_blank"><img src="./img/sat.png" alt=""></a></td>
   <td>' . $Reflector->Stations[$i]->GetVia();
        if ($Reflector->Stations[$i]->GetPeer() != $Reflector->GetReflectorName()) {
            echo ' / ' . $Reflector->Stations[$i]->GetPeer();
        }
        echo '</td>
   <td>' . @date("d.m.Y H:i", $Reflector->Stations[$i]->GetLastHeardTime()) . '</td>
   <td>' . $Reflector->Stations[$i]->GetModule() . '</td>
 </tr>';
    if ($i == $PageOptions['LastHeardPage']['LimitTo']) {
        $i = $Reflector->StationCount() + 1;
    }
}

?> 
        </tbody>
      </table>
    </div>
    <div class="col-md-4">
        <table class="table table-hover table-sm table-responsive-md">
            <thead>
         <?php 

$Modules = $Reflector->GetModules();
sort($Modules, SORT_STRING);
echo '<tr>';
for ($i=0;$i<count($Modules);$i++)
{
   if (isset($PageOptions['ModuleNames'][$Modules[$i]]))
   {
      echo '<th scope="row">'.$PageOptions['ModuleNames'][$Modules[$i]];
       if (trim($PageOptions['ModuleNames'][$Modules[$i]]) != "")
       {
           echo ' - ';
       }
      echo $Modules[$i].'</th>';
   }
   else
   {
        echo '<th scope="row">'.$Modules[$i].'</th>';
   }
}

echo '</tr></thead>
    <tbody>';

    $nodesGroupedByModules = array();
    foreach($Modules as $module)
    {
        $nodesInModule = $Reflector->GetNodesInModulesById($module);
        $count = count($nodesInModule);
        if($count > $maxNodes)
            $maxNodes = $count;
        
        $nodesGroupedByModules[$module] = array();
        foreach($nodesInModule as $nodeId)
        {
            $nodesGroupedByModules[$module][] = $Reflector->GetCallsignAndSuffixByID($nodeId); 
        }
        sort($nodesGroupedByModules[$module], SORT_STRING); 
    }

    for ($j=0;$j<$maxNodes;$j++)
    {
        echo '<tr>';
        foreach($Modules as $module)
        {
            $nodesInModule = $nodesGroupedByModules[$module];
            if($j < count($nodesInModule))
            {
                echo '<td>';
                echo '<a href="https://www.aprsdirect.com/details/main/name/' . $nodesInModule[$j] . '" class="pl" target="_blank">'. $nodesInModule[$j] .'</a>';
                echo '</td>';
            }
            else
            {
                echo '<td>&nbsp;</td>';
            }
        }
        echo '</tr>';
    }

?>
        </tbody>
      </table>
   </div>