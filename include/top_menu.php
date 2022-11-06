<p style="padding-right: 5px; text-align: right; color: #000000;"> 
	<a href="/" style="color: #0000ff;">Dashboard</a> | 
	<a href="/node.php" style="color: #0000ff;">Nodes</a> | 
	<a href="/tg.php" style="color: #0000ff;">Talk Groups</a> | 
	<a href="/dtmf.php" style="color: #0000ff;">Dtmf</a> | 
	<a href="/audio.php" style="color: #0000ff;">Audio</a> | 
	<a href="/wifi.php" style="color: #0000ff;">Wifi</a> | 
	<a href="/network.php" style="color: #0000ff;">Network</a> |
	<a href="/svxlink.php" style="color: #0000ff;">SVXLink</a> |
	<a href="/nodeInfo.php" style="color: #0000ff;">Node Info</a> |

<?php 
if (fopen($svxConfigFile,'r'))
{

  $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
  //$svxconfig['TetraLogic']['INIT_PEI'] = $svxconfig['TetraLogic']['INIT_PEI'] . $init_pei_tail;    
  $logics = explode(",",$svxconfig['GLOBAL']['LOGICS']);
  foreach ($logics as $key) {
	if ($key == "SimplexLogic") $isSimplex = true;
	if ($key == "TetraLogic") $isTetra = true; 
  };
  $logics = explode(",",$svxconfig['GLOBAL']['LOGICS']);
  if ($isSimplex) $modules = explode(",",str_replace('Module','',$svxconfig['SimplexLogic']['MODULES']));
  if ($isTetra) $modules = explode(",",str_replace('Module','',$svxconfig['TetraLogic']['MODULES']));
  foreach ($modules as $key){
	if ($key == "EchoLink") $isEchoLink = true;
 }
 if ($isEchoLink==true) {echo ' <a href="/echolink.php" style="color: #0000ff;">EchoLink</a> |';};
$globalRf = $svxconfig['GLOBAL']['RF_MODULE'];

if ($globalRf <> "No")
{
	echo'	<a href="/rf.php" style="color: #0000ff;"> Rf</a> |';
}
};
?>
	<a href="/log.php" style="color: #0000ff;">Log</a> |
	<a href="/update.php" style="color: #0000ff;">Update</a> |
	<a href="/power.php" style="color: #0000ff;">Power</a>
</p>
