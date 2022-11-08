<?php
include_once __DIR__.'/config.php';         
include_once __DIR__.'/tools.php';        
include_once __DIR__.'/functions.php';

?>
<div style="width:180px;"><span style="font-weight: bold;font-size:14px;">SVXLink Info</span></div>
<fieldset style="width:175px;background-color:#e8e8e8e8;margin-top:6px;;margin-bottom:0px;margin-left:0px;margin-right:3px;font-size:12px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<?php

if (isProcessRunning('svxlink')) {

echo "<table style=\"margin-top:4px;margin-bottom:13px;\">\n";
echo "<tr><th><span style=\"font-size:12px;\">Logics</span></th></tr>\n";

$svxConfigFile = SVXCONFPATH."/".SVXCONFIG;
    if (fopen($svxConfigFile,'r')) {$svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW); }
$logics = explode(",",$svxconfig['GLOBAL']['LOGICS']);
foreach ($logics as $key) {
  echo "<tr><td style=\"background:#ffffed;\"><span style=\"color:#b5651d;font-weight: bold;\">".$key."</span></td></tr>";
  if ($key == "SimplexLogic") $isSimplex = true;
  if ($key == "TetraLogic") $isTetra = true; 
}
echo "</table>\n";
echo "<table style=\"margin-top:2px;margin-bottom:13px;\">\n";
if ($isSimplex) $modules = explode(",",str_replace('Module','',$svxconfig['SimplexLogic']['MODULES']));
if ($isTetra) $modules = explode(",",str_replace('Module','',$svxconfig['TetraLogic']['MODULES']));

$modecho = "False";
if ($modules!="") {
define("SVXMODULES",$modules);
$admodules = getActiveModules();
 echo "<tr><th><span style=\"font-size:12px;\">Modules Loaded</span></th></tr>\n";
 foreach ($modules as $key) {
     if ($admodules[$key]=="On"){
    $activemod="<td style=\"background:MediumSeaGreen;color:#464646;font-weight: bold;\">";} else {
    $activemod="<td style=\"background:#ffffed;;color:#b5651d;font-weight: bold;\">";}

   echo "<tr>".$activemod."".$key."</td></tr>";

   if ($key=="EchoLink") {$modecho ="True";}}

} else {
  echo "<tr><td style=\"background: #ffffed;\" ><span style=\"color:#b0b0b0;\"><b>No Modules</span></td></tr>";
}
echo "</table>\n";

$tgtmp = trim(getSVXTGTMP());
echo "<table colspan=2 style=\"margin-top:4px;margin-bottom:13px;\">\n";
$tgdefault = $svxconfig['ReflectorLogic']['DEFAULT_TG'];
$tgmon = explode(",",$svxconfig['ReflectorLogic']['MONITOR_TGS']);
echo "<tr><th width=50%>TG Default</th><td style=\"background:#ffffed;color:green;font-weight: bold;\">".$tgdefault."</td></tr>\n";
echo "<tr><th width=50%>TG Monitor</th><td style=\"background:#ffffed;color:#b44010;font-weight: bold;\">";
echo "<div style=\"white-space:normal;\">";
 foreach ($tgmon as $key) {
   echo $key." ";
   }
echo "<span style=\"background: #ffffed;color:#0065ff;font-weight: bold;\">".$tgtmp."</span>";
echo "</div></td></tr>\n";

$tgselect = trim(getSVXTGSelect());
if ( $tgselect=="0"){$tgselect="";}
echo "<tr><th width=50%>TG Active</th><td style=\"background: #ffffed;color:#0065ff;font-weight: bold;\">".$tgselect."</td></tr>\n";
echo "</table>";

if ($svxconfig["Rx1"]["PEAK_METER"] =="1") 
$ispeak = true ;

echo "<table  style=\"margin-bottom:13px;\"><tr><th>Radio Status</th></tr><tr>";
echo getTXInfo();
if ($ispeak==true) echo getRXPeak();
echo "</table>\n";

echo "<table  style=\"margin-bottom:13px;\"><tr><th>".FMNETWORK."</th></tr><tr>";
  $svxrstatus = getSVXRstatus();
  echo "<tr>";
  if ($svxrstatus=="Connected") {
   echo "<td style=\"background:#c3e5cc;\"><div style=\"margin-top:2px;margin-bottom:2px;white-space:normal;color:#b44010;font-weight:bold;\">";
   echo $svxrstatus."</div>";}
  if ($svxrstatus=="Not connected") {
   echo "<td style=\"background:#ff9;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#454545;font-weight:bold;\">";
   echo $svxrstatus."</div>";}
  if ($svxrstatus=="No status") {
   echo "<td style=\"background:#ffffed;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#b0b0b0;font-weight:bold;\">"; 
   echo $svxrstatus."</div>";}
   echo "</td></tr>";
echo "</table>\n";

if ($modecho=="True") {
  $echolog = getEchoLog();
  $echotxing = getEchoLinkTX();
  echo "<table style=\"margin-top:4px;margin-bottom:13px;\"><tr><th colspan=2 >EchoLink Users</th></tr><tr>";
  echo "<tr>";
  $users = getConnectedEcholink($echolog);
  if (count($users)!=0){
  echo "<td colspan=2 style=\"background:#f6f6bd;\"><div style=\"margin-top:4px;margin-bottom:4px;white-space:normal;color:#0065ff;font-weight: bold;\">";
  foreach ($users as $user) {
    echo "<a href=\"http://www.qrz.com/db/".$user."\" target=\"_blank\"><b>".str_replace("0","&Oslash;",$user)."</b></a> ";
     }
   } else { echo "<td colspan=2 style=\"background:#ffffed;\"><div style=\"margin-top:4px;margin-bottom:4px;color:#b0b0b0;font-weight: bold;\">Not connected";}
  echo "</div></td></tr>";
  echo "<tr><th width=50%>TXing</th><td style=\"background:#ffffed;color:red;font-weight: bold;\">".$echotxing."</td></tr>";
  echo "</table>\n";
  $svxEchoConfigFile = "/etc/svxlink/svxlink.d/ModuleEchoLink.conf";
    if (fopen($svxEchoConfigFile,'r')) { 
       $svxeconfig = parse_ini_file($svxEchoConfigFile,true,INI_SCANNER_RAW);
       $eproxyd= $svxeconfig['ModuleEchoLink']['PROXY_SERVER']; 
       } else {
       $eproxyd= ""; 
      }
  $eproxy = getEchoLinkProxy();
  if ($eproxy!="" and $eproxyd!="") {
   echo "<table style=\"margin-top:4px;margin-bottom:4px;\"><tr><th>EchoLink Proxy</th></tr><tr>"; 
   echo "<tr><td style=\"background:#ffffed;\">";
   echo "<div style=\"margin-top:2px;margin-bottom:2px;white-space:normal;color:black;font-weight:500;\">";
   if ($eproxy!="Access denied to proxy") {
   echo $eproxy;
   } else { echo "<div style=\"margin-top:2px;margin-bottom:2px;color:red;font-weight: bold;\">".$eproxy; }
   echo "</div></td></tr>";
   echo "</table>\n";
  }
}

} else {

echo "<span style=\"color:red;font-size:13.5px;font-weight: bold;\">SvxLink is not <br>running</span>";
}
?>
</fieldset>
