<?php
/*
* If you do not want to mess with the distribution file
* you can overwrite all of those values in the config.inc.user.php
* file in the root folder
*/


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

$PageOptions['DashboardVersion']                     = '1.0';		// Dashboard Version

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
$PageOptions['ModuleNames']['B']                     = 'National';
$PageOptions['ModuleNames']['C']                     = 'Another Module';


$PageOptions['MetaDescription']                      = 'XLX is a D-Star Reflector System for Ham Radio Operators.';  // Meta Tag Values, usefull for Search Engine
$PageOptions['MetaKeywords']                         = 'Ham Radio, D-Star, XReflector, XLX, XRF, DCS, REF, ';        // Meta Tag Values, usefull forSearch Engine
$PageOptions['MetaAuthor']                           = 'LX1IQ';                                                      // Meta Tag Values, usefull for Search Engine
$PageOptions['MetaRevisit']                          = 'After 30 Days';                                              // Meta Tag Values, usefull for Search Engine
$PageOptions['MetaRobots']                           = 'index,follow';                                               // Meta Tag Values, usefull for Search Engine

$PageOptions['UserPage']['ShowFilter']               = true;								// Show Filter on Users page
$PageOptions['Traffic']['Show']                      = false;								// Enable vnstat traffic statistics

$PageOptions['CustomTXT']                            = 'Sysop N0CALL';					// custom text in your header   
$PageOptions['CustomTXTLink']                        = 'https://html5zombo.com/';
$PageOptions['LogoFile']                             = './img/International_amateur_radio_symbol.png';

$Service['PIDFile']                                  = '/var/log/xlxd.pid';
$Service['XMLFile']                                  = '/var/log/xlxd.xml';
$Service['AmbedStatusCommand']                       = 'pgrep ambed';
$Service['xlxdStatusCommand']                        = 'pgrep xlxd';

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

if (file_exists(realpath(dirname(__FILE__)."/../config.inc.user.php")))
{
  $PageOptions['ModuleNames'] = array(); 
  include (realpath(dirname(__FILE__)."/../config.inc.user.php"));
}

?>
