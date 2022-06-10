<?php
// Report all errors except E_NOTICE
// error_reporting(E_ALL & ~E_NOTICE);
// disable all 
error_reporting(0);

// Define name of your FM Network
define("FMNETWORK", "FM POLAND");
//
// Select only one URL for SVXReflector API to get connected Nodes
//
// FM Poland API
define("URLSVXRAPI", "http://svxlink.pl:888/api/");
//
// Empty address API do not show connected nodes to svxreflector 
//define("URLSVXRAPI", "");
//
// Put url address to your svxreflector wihc offer information of status
//define("URLSVXRAPI", "http://192.168.1.33:9999/status");
//
// path and file name of configuration
define("SVXCONFPATH", "/etc/svxlink");
define("SVXCONFIG", "svxlink.conf");

// path and file name of log
define("SVXLOGPATH", "/var/log");
define("SVXLOGPREFIX", "svxlink");
//
//
// define where is located menu wit buttons TOP or BOTTOM
define("MENUBUTTON", "TOP");
//
// Button keys define: description button, DTMF command or command, color of button
//
// DTMF keys
// syntax: 'KEY number,'Description','DTMF code','color button' 
//
define("KEY1", array('TG 260','91260#','green'));
define("KEY2", array('TG 2600','912600#','green'));
define("KEY3", array('TG 7783','917783#','green'));
define("KEY4", array('EL-SR4D-R','2# 453582#','purple'));
define("KEY5", array('METAR','5#','blue'));
// additional DTMF keys
define("KEY6", array('','','purple'));
define("KEY7", array('','','blue'));
//
// command "shutdown now" 
define("KEY8", array('POWER OFF','sudo poweroff','red'));
//
// Set SHOWPTT to TRUE if you want use microphone connected
// to sound card and use buttons on dashboard PTT ON & PTT OFF
// Set SHOWPTT to FALSE to disable display PTT buttons
// In most cases you can switch to FALSE
define("SHOWPTT","TRUE");
//
define("KEY9", array('PTT ON','echo "O" >/tmp/SQL','orange'));
define("KEY10", array('PTT OFF','echo "Z" >/tmp/SQL','orange'));
//
?>
