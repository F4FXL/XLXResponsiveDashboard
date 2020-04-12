<div class="row justify-content-md-center">
   <div class="col">
    <table class="table table-hover table-sm table-responsive-md">
            <thead>
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
    <td><?php echo $description;?></td>
    <td><?php echo count($Reflector->GetStationsInModule($module));?></td>
    <td><?php echo count($Reflector->GetNodesInModulesById($module));?></td>
</tr>
<?php
}

?>
</tbody>
</table>