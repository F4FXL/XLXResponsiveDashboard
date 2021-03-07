<?php
/*
* This were you should put all of your customisation, own logo, module names etc ...
* Commented code is provided as an exmaple
*/

$PageOptions['xlxdVersion']                          = '<a href="https://github.com/F4FXL/xlxd/releases/tag/xlx208%2Fv2.4.1" target="_blank">xlx208/2.4.1</a>';	//xlxd version

$PageOptions['CustomTXT']                            = 'Sysop F5KAV R.A.C.C.W';					// custom text in your header   
$PageOptions['CustomTXTLink']                        = 'https://www.f5kav.fr/nos-installations/reflecteur-xlx208/';
$PageOptions['LogoFile']                             = './img/logo_kav.png';

$PageOptions['ModuleNames']['A']                     = 'Worldwide (Interlink)';
$PageOptions['ModuleNames']['B']                     = 'France';
$PageOptions['ModuleNames']['C']                     = 'DStar / TG20867 FreeDMR / <a href="http://ipsc2fr.dnsalias.net/ipsc/" target=_blank>TG867 IPSC2FR</a> / YSF';
$PageOptions['ModuleNames']['D']                     = 'Francophone';
$PageOptions['ModuleNames']['E']                     = 'Auvergne-Rhone-Alpes';
$PageOptions['ModuleNames']['F']                     = 'Bourgogne-Franche-Comté';
$PageOptions['ModuleNames']['G']                     = 'Bretagne';
$PageOptions['ModuleNames']['H']                     = 'Centre-Val de Loire';
$PageOptions['ModuleNames']['I']                     = 'Corse';
$PageOptions['ModuleNames']['J']                     = 'Grand Est';
$PageOptions['ModuleNames']['K']                     = 'Hauts de France';
$PageOptions['ModuleNames']['L']                     = 'Ile de France';
$PageOptions['ModuleNames']['M']                     = 'Normandie';
$PageOptions['ModuleNames']['N']                     = 'Nouvelle Aquitaine';
$PageOptions['ModuleNames']['O']                     = 'Occitanie';
$PageOptions['ModuleNames']['P']                     = 'Pays de la Loire';
$PageOptions['ModuleNames']['Q']                     = 'PACA';
$PageOptions['ModuleNames']['R']                     = 'DOM-TOM';
$PageOptions['ModuleNames']['S']                     = 'UNARAF';
$PageOptions['ModuleNames']['T']                     = 'Test';
$PageOptions['ModuleNames']['U']                     = 'Urgence';
$PageOptions['ModuleNames']['W']                     = 'World Wide';
$PageOptions['ModuleNames']['X']                     = 'Sysop';

$PageOptions['SGS']['Show']                          = true;         // Show SGS page

# sgs status page options
$PageOptions['SGSTitle']                             = 'STN208 Smart Groups Status';
$PageOptions['SGSServer']	                         = 'stn208'; //sgsremote <servername> as in sgsremote config
$PageOptions['PageRefreshAlt']                       = '60000';      // Alternate page refresh time in miliseconds
$PageOptions['SGSRepeaterReplace']['F1ZNZ   A']        = 'STN208  B';
$PageOptions['SGSRepeaterReplace']['F1ZNZ   C']        = 'STN208  C';
$PageOptions['SGSRepeaterReplace']['F1ZNZ   D']        = 'STN208  D';


$Service['AmbedStatusCommand']                       = "timeout -k 2s 2s socat -u -t1 'TCP4:10.9.0.100:6789' -";

$CallingHome['Active']                               = true;					               // xlx phone home, true or false
$CallingHome['MyDashBoardURL']                       = 'http://xlx208.f5kav.fr';			       // dashboard url
$CallingHome['PushDelay']                            = 600;  	                               // push delay in seconds
$CallingHome['Country']                              = "France";                         // Country
$CallingHome['Comment']                              = "French D-Star XLX Multiprotocol Reflector, brought to you by F5KAV";            // Comment. Max 100 character

?>
