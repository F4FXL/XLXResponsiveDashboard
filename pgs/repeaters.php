<div class="row justify-content-md-center">
   <div class="col">
      <table class="table table-hover table-sm table-responsive-md">
         <thead>
            <tr>
               <th scope="col">#</th>
               <th scope="col">Flag</th>
               <th scope="col">DV Station</th>
               <th scope="col">Band</th>
               <th scope="col">Last Heard</th>
               <th scope="col">Linked for</th>
               <th scope="col">Protocol</th>
               <th scope="col">Module</th>
               <?php
               if ($PageOptions['RepeatersPage']['IPModus'] != 'HideIP') {
                  echo '
                  <th scope="col">IP</th>';
               }?>
            </tr>
         </thead>
      <tbody>  
      <?php
         $Reflector->LoadFlags();

         for ($i=0;$i<$Reflector->NodeCount();$i++)
         {
            if ($i >= $PageOptions['RepeatersPage']['LimitTo'])
               break;
      ?>
            <tr>
               <th scope="row"><?php echo $i+1;?></th>
               <td><?php
                  list ($Flag, $Name) = $Reflector->GetFlag($Reflector->Nodes[$i]->GetCallSign());
                  if (file_exists("./img/flags/".$Flag.".png"))
                  {
                     echo '<a href="#" data-toggle="tooltip" title="'. $Name . '"><img src="./img/flags/'.$Flag.'.png" class="table-flag" alt="'.$Name.'"></a>';
                  }?>
               </td>
               <td>
                  <?php               
                  $suffix = $Reflector->Nodes[$i]->GetSuffix();
                  $callsignWithSuffix = $Reflector->Nodes[$i]->GetCallSign();
                  $callsignWithSuffix = $callsignWithSuffix . ($suffix != ""? "-" . $suffix : "");
                  ?>
                  <a href="https://www.aprsdirect.com/details/main/name/<?php echo $callsignWithSuffix;?>" target="_blank"><?php echo $callsignWithSuffix;?></a>
               </td>
               <?php
                  $band = "";
                  if (($Reflector->Nodes[$i]->GetPrefix() == 'REF') || ($Reflector->Nodes[$i]->GetPrefix() == 'XRF')) {
                     switch ($Reflector->Nodes[$i]->GetPrefix()) {
                     case 'REF'  : $band = 'REF-Link'; break;
                     case 'XRF'  : $band = 'XRF-Link'; break;
                     }
                  }
                  else {
                     switch ($Reflector->Nodes[$i]->GetSuffix()) {
                        case 'A' : $band = '23cm'; break;
                        case 'B' : $band = '70cm'; break;
                        case 'C' : $band = '2m'; break;
                        case 'D' : $band = 'Dongle'; break;
                        case 'G' : $band = 'Internet-Gateway'; break;
                        default  : $band = '';
                     }
                  }
               ?>
               <td>
                  <?php echo $band; ?>
               </td>
               <td>
                  <?php echo  date("d.m.Y H:i", $Reflector->Nodes[$i]->GetLastHeardTime()); ?>
               </td>
               <td>
                  <?php echo FormatSeconds(time()-$Reflector->Nodes[$i]->GetConnectTime()); ?>
               </td>
               <td>
                  <?php echo $Reflector->Nodes[$i]->GetProtocol(); ?>
               </td>
               <td>
                  <?php echo $Reflector->Nodes[$i]->GetLinkedModule(); ?>
               </td>
               <?php
                  $ipstring = "";
                  if ($PageOptions['RepeatersPage']['IPModus'] != 'HideIP')
                  {
                     $Bytes = explode(".", $Reflector->Nodes[$i]->GetIP());
                     if ($Bytes !== false && count($Bytes) == 4)
                     {
                        switch ($PageOptions['RepeatersPage']['IPModus'])
                        {
                           case 'ShowLast1ByteOfIP':
                              $ipstring = $PageOptions['RepeatersPage']['MasqueradeCharacter'].'.'.$PageOptions['RepeatersPage']['MasqueradeCharacter'].'.'.$PageOptions['RepeatersPage']['MasqueradeCharacter'].'.'.$Bytes[3];
                           break;
                           case 'ShowLast2ByteOfIP':
                              $ipstring = $PageOptions['RepeatersPage']['MasqueradeCharacter'].'.'.$PageOptions['RepeatersPage']['MasqueradeCharacter'].'.'.$Bytes[2].'.'.$Bytes[3];
                           break;
                           case 'ShowLast3ByteOfIP':
                              $ipstring = $PageOptions['RepeatersPage']['MasqueradeCharacter'].'.'.$Bytes[1].'.'.$Bytes[2].'.'.$Bytes[3];
                           break;
                           default:
                              $ipstring = $Reflector->Nodes[$i]->GetIP();
                           break;
                        }
                     }
                  }
                  if($ipstring == "")
                     continue;
               ?>
               <td>
                  <?php echo $ipstring;?>
               </td>
            </tr>
         <?php
         }
         ?>
      </tbody>
   </table>
</div>
</div>
