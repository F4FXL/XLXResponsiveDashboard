<?php

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
         <thead>
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
 <tr class="table-center">
   <th scope="row">'.($i+1).'</th>
   <td><a href="'.$DASHBOARDURL.'" target="_blank" class="listinglink" title="Visit the Dashboard of&nbsp;'.$NAME.'">'.$NAME.'</a></td>
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
   
   
