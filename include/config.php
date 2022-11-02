<?php
// Report all errors except E_NOTICE
// error_reporting(E_ALL & ~E_NOTICE);
// disable all 

$svxConfigFile = '/etc/svxlink/svxlink.conf';
    if (fopen($svxConfigFile,'r'))
       { $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
         $refApi = $svxconfig['ReflectorLogic']['API'];
         $fmnetwork =$svxconfig['ReflectorLogic']['FMNET'];   }
else { $callsign="N0CALL";
       $fmnetwork="no registered";
        }



error_reporting(0);

// Define name of your FM Network
define("FMNETWORK", $fmnetwork);
//
// Select only one URL for SVXReflector API to get connected Nodes
//
// FM Poland API
define("URLSVXRAPI", $refApi);
//
// Empty address API do not show connected nodes to svxreflector 
//define("URLSVXRAPI", "");
//
// Put url address to your svxreflector wihc offer information of status
//define("URLSVXRAPI", "http://192.168.1.33:9999/status");
//
//
// Orange Pi Zero LTS version requires CPU_TEMP_OFFSET value 30 
// to display CPU TEMPERATURE correctly
define("CPU_TEMP_OFFSET","0");
//
// Path and file name of confguration
define("SVXCONFPATH", "/etc/svxlink");
define("SVXCONFIG", "svxlink.conf");
//
// Path and file name of log
define("SVXLOGPATH", "/var/log");
define("SVXLOGPREFIX", "svxlink");
//
//
// Define where is located menu wit buttons TOP or BOTTOM
define("MENUBUTTON", "BOTTOM");
//
// Button keys define: description button, DTMF command or command, color of button
//
// DTMF keys
// syntax: 'KEY number,'Description','DTMF code','color button' 
//
define("KEY1", array( ' D1 ','D1#','green'));
define("KEY2", array(' D2 ','D2#','green'));
define("KEY3", array(' D3 ','D3#','orange'));
define("KEY4", array(' D4 ','D4#','orange'));
define("KEY5", array(' D5 ','D5#','red'));
// additional DTMF keys
define("KEY6", array(' D6 ','D6#','red'));
define("KEY7", array(' D7 ','D7#','purple'));
//
// command "shutdown now" 
define("KEY8", array(' D8 ','D8#','purple'));
//
// Set SHOWPTT to TRUE if you want use microphone connected
// to sound card and use buttons on dashboard PTT ON & PTT OFF
// Set SHOWPTT to FALSE to disable display PTT buttons
// In most cases you can switch to FALSE
//define("SHOWPTT","TRUE");
//
define("KEY9", array(' D9 ','D9#','blue'));
define("KEY10", array(" D0 ","D0#","blue"));
//
?>
