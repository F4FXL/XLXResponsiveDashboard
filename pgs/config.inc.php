<?php<?php
/*
Possible values for IPModus

HideIP
ShowFullIP
ShowLast1ByteOfIP
ShowLast2ByteOfIP
ShowLast3ByteOfIP

*/

$Service     = array();
$CallingHome = array();
$PageOptions = array();
$VNStat      = array();

$PageOptions['ContactEmail']                         = ''; 	        // Support E-Mail address

$PageOptions['DashboardVersion']                     = '2.4.0';		// Dashboard Version

$PageOptions['PageRefreshActive']                    = false;		// Activate automatic refresh
$PageOptions['PageRefreshDelay']                     = '10000';		// Page refresh time in miliseconds

$PageOptions['RepeatersPage'] = array();
$PageOptions['RepeatersPage']['LimitTo']             = 99;		// Number of Repeaters to show
$PageOptions['RepeatersPage']['IPModus']             = 'ShowLast3ByteOfIP';	// See possible options above
$PageOptions['RepeatersPage']['MasqueradeCharacter'] = '*';		// Character used for  masquerade

$PageOptions['PeerPage'] = array();
$PageOptions['PeerPage']['LimitTo']                  = 99;		// Number of peers to show
$PageOptions['PeerPage']['IPModus']                  = 'ShowLast3ByteOfIP';//'HideIP';	// See possible options above
$PageOptions['PeerPage']['MasqueradeCharacter']      = '*';		// Character used for  masquerade

$PageOptions['LastHeardPage']['LimitTo']             = 39;		// Number of stations to show

$PageOptions['ModuleNames'] = array();                                			// Module nomination
$PageOptions['ModuleNames']['A']                     = 'Interlink XLX';
$PageOptions['ModuleNames']['B']                     = 'France';
$PageOptions['ModuleNames']['C']                     = 'Dstar/TG20867/YSF';
$PageOptions['ModuleNames']['D']                     = 'MMDVM TG6 DMR';
$PageOptions['ModuleNames']['E']                     = 'Auvergne-Rhone-Alpes';
$PageOptions['ModuleNames']['F']                     = 'Bourgogne-Franche-Comté';
$PageOptions['ModuleNames']['G']                     = 'Bretagne';
$PageOptions['ModuleNames']['H']                     = 'Centre-Val de Loire';
$PageOptions['ModuleNames']['I']                     = 'Corse';
$PageOptions['ModuleNames']['J']                     = 'Grand Est';
$PageOptions['ModuleNames']['K']                     = 'Hauts de France';
$PageOptions['ModuleNames']['L']                     = 'Ile de France';
$PageOptions['ModuleNames']['M']                     = 'Normandie';
$PageOptions['ModuleNames']['N']                     = 'New Aquitaine';
$PageOptions['ModuleNames']['O']                     = 'Occitanie';
$PageOptions['ModuleNames']['P']                     = 'Pays de la Loire';
$PageOptions['ModuleNames']['Q']                     = 'PACA';
$PageOptions['ModuleNames']['R']                     = 'DOM-ToM';
$PageOptions['ModuleNames']['S']                     = 'UNARAF';
$PageOptions['ModuleNames']['T']                     = 'Test';
$PageOptions['ModuleNames']['W']                     = 'World Wide';
$PageOptions['ModuleNames']['X']                     = 'Sysop';


$PageOptions['MetaDescription']                      = 'XLX is a D-Star Reflector System for Ham Radio Operators.';  // Meta Tag Values, usefull for Search Engine
$PageOptions['MetaKeywords']                         = 'Ham Radio, D-Star, XReflector, XLX, XRF, DCS, REF, ';        // Meta Tag Values, usefull forSearch Engine
$PageOptions['MetaAuthor']                           = 'LX1IQ';                                                      // Meta Tag Values, usefull for Search Engine
$PageOptions['MetaRevisit']                          = 'After 30 Days';                                              // Meta Tag Values, usefull for Search Engine
$PageOptions['MetaRobots']                           = 'index,follow';                                               // Meta Tag Values, usefull for Search Engine

$PageOptions['UserPage']['ShowFilter']               = true;								// Show Filter on Users page
$PageOptions['Traffic']['Show']                      = false;								// Enable vnstat traffic statistics

$PageOptions['CustomTXT']                            = 'Sysop F5KAV R.A.C.C.W';					// custom text in your header   
$PageOptions['CustomTXTLink']                        = 'https://www.f5kav.fr';

$Service['PIDFile']                                  = '/var/log/xlxd.pid';
$Service['XMLFile']                                  = '/var/log/xlxd.xml';

$CallingHome['Active']                               = true;					               // xlx phone home, true or false
$CallingHome['MyDashBoardURL']                       = 'http://xlx208.f5kav.fr';			       // dashboard url
$CallingHome['ServerURL']                            = 'http://xlxapi.rlx.lu/api.php';         // database server, do not change !!!!
$CallingHome['PushDelay']                            = 600;  	                               // push delay in seconds
$CallingHome['Country']                              = "France";                         // Country
$CallingHome['Comment']                              = "French D-Star XLX Multiprotocol Reflector, brought to you by F5KAV";            // Comment. Max 100 character
$CallingHome['HashFile']                             = "/callhome/callinghome.php";                 // Make sure the apache user has read and write permissions in this folder.
$CallingHome['LastCallHomefile']                     = "/tmp/lastcallhome.php";			// lastcallhome.php can remain in the tmp folder 
$CallingHome['OverrideIPAddress']                    = "";                                     // Insert your IP address here. Leave blank for autodetection. No need to enter a fake address.
$CallingHome['InterlinkFile']                        = "/xlxd/xlxd.interlink";                 // Path to interlink file

$VNStat['Interfaces']                                = array();
$VNStat['Interfaces'][0]['Name']                     = 'venet0';
$VNStat['Interfaces'][0]['Address']                  = 'venet0';
$VNStat['Binary']                                    = '/usr/bin/vnstat';
/*
  include an extra config file for people who dont like to mess with shipped config.ing.php
  this makes updating dashboard from git a little bit easier
*/

if (file_exists("../config.inc.php")) {$PageOptions['ModuleNames'] = array(); 
  include ("../config.inc.php");
}

?>
