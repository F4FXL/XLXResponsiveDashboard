<?php
/*
* This were you should put all of your customisation, own logo, module names etc ...
* Commented code is provided as an exmaple
*/

$PageOptions['MetaDescription']                      = 'Reflecteur et Serveur Starnet multi protocoles francophone, fourni par F5KAV';  // Meta Tag Values, usefull for Search Engine
$PageOptions['MetaKeywords']                         = 'Ham Radio, D-Star, XReflector, XLX, XRF, DCS, REF, DMR, YSF';        // Meta Tag Values, usefull forSearch Engine
$PageOptions['MetaAuthor']                           = 'F5KAV';                                                      // Meta Tag Values, usefull for Search Engine
$PageOptions['MetaRevisit']                          = 'After 30 Days';                                              // Meta Tag Values, usefull for Search Engine
$PageOptions['MetaRobots']                           = 'index,follow';                                               // Meta Tag Values, usefull for Search Engine

$PageOptions['PageRefreshDelay']                     = '10000';		// Page refresh time in miliseconds
$PageOptions['xlxdVersion']                          = '<a href="https://github.com/F4FXL/xlxd/releases/tag/xlx208%2Fv2.5.3" target="_blank">xlx208/2.5.3</a>';	//xlxd version

$PageOptions['CustomTXT']                            = 'Sysop F5KAV R.A.C.C.W';					// custom text in your header   
$PageOptions['CustomTXTLink']                        = 'https://www.f5kav.fr/nos-installations/reflecteur-xlx208/';
$PageOptions['LogoFile']                             = './img/logo_kav.png';

$PageOptions['ModuleNames']['A']                     = 'Worldwide (Interlink)';
$PageOptions['ModuleNames']['B']                     = 'France';
$PageOptions['ModuleNames']['C']                     = 'DStar/<a href="http://ipsc2fr.dnsalias.net/ipsc/" target=_blank>TG867 IPSC2FR</a>/YSF';
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
$PageOptions['ModuleNames']['V']                     = 'DRats & Fast Data';
$PageOptions['ModuleNames']['W']                     = 'World Wide';
$PageOptions['ModuleNames']['X']                     = 'Sysop';

$PageOptions['PageRefreshDelay']                     = '5000';		// Page refresh time in miliseconds

$PageOptions['SGS']['Show']                          = true;         // Show SGS page
$PageOptions['SGS']['version']                       = "1.0";         // Show SGS page
$PageOptions['SGS']['name']                          = "STN208";

# sgs status page options
$PageOptions['SGSTitle']                             = 'STN208 Smart Groups Status';
$PageOptions['SGSServer']	                         = 'stn208'; //sgsremote <servername> as in sgsremote config
$PageOptions['PageRefreshAlt']                       = '60000';      // Alternate page refresh time in miliseconds
$PageOptions['RepeaterAlias']['F1ZNZ   B']           = 'STN208  B';
$PageOptions['RepeaterAlias']['F1ZNZ   C']           = 'STN208  C';
$PageOptions['RepeaterAlias']['F1ZNZ   D']           = 'STN208  D';
$PageOptions['RepeaterAlias']['IPSC2FR B']           = 'IPSC2 France 2';

$Service['AmbedStatusCommand']                       = "timeout -k 2s 2s socat -u -t1 'TCP4:10.8.0.110:6789' -";

$CallingHome['Active']                               = true;					               // xlx phone home, true or false
$CallingHome['MyDashBoardURL']                       = 'http://xlx208.f5kav.fr';			       // dashboard url
$CallingHome['PushDelay']                            = 600;  	                               // push delay in seconds
$CallingHome['Country']                              = "France";                         // Country
$CallingHome['Comment']                              = "French D-Star XLX Multiprotocol Reflector and Smart Group Server, brought to you by F5KAV";            // Comment. Max 100 character
$CallingHome['OverrideIPAddress']                    = "213.186.34.50"; 
?>
