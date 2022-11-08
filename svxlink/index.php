<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Audio Peak Meter</title>
    <meta name="Author" content="Waldek SP2ONG" />
    <meta name="Description" content="Audio Test Peak Meter for SVXLink by SP2ONG 2022" />
    <meta name="KeyWords" content="SVXLink, SVXRelector,SP2ONG" />
    <link href="/css/css.php" type="text/css" rel="stylesheet" />
<style type="text/css">
body {
  background-color: #eee;
  font-size: 18px;
  font-family: Arial;
  font-weight: 300;
  margin: 2em auto;
  max-width: 40em;
  line-height: 1.5;
  color: #444;
  padding: 0 0.5em;
}
h1, h2, h3 {
  line-height: 1.2;
}
a {
  color: #607d8b;
}
.highlighter-rouge {
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: .2em;
  font-size: .8em;
  overflow-x: auto;
  padding: .2em .4em;
}
pre {
  margin: 0;
  padding: .6em;
  overflow-x: auto;
}

#player {
    position:relative;
    width:205px;
    overflow: hidden;
    direction: ltl;
}

textarea {
    background-color: #111;
    border: 1px solid #000;
    color: #ffffff;
    padding: 1px;
    font-family: courier new;
    font-size:10px;
}




</style>
</head>
<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<center>
<fieldset style="border:#3083b8 2px groove;box-shadow:0 0 10px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<center>
<h1 id="web-audio-peak-meters" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">SVXLink Configurator</h1>


<?php 
//sp0dz based on:
//https://programmierfrage.com/items/convert-array-to-an-ini-file
function build_ini_string(array $a) {
    $out = '';
    $sectionless = '';
    foreach($a as $rootkey => $rootvalue){
        if(is_array($rootvalue)){
            // find out if the root-level item is an indexed or associative array
            $indexed_root = array_keys($rootvalue) == range(0, count($rootvalue) - 1);
            // associative arrays at the root level have a section heading
            if(!$indexed_root) $out .= PHP_EOL."[$rootkey]".PHP_EOL;
            // loop through items under a section heading
            foreach($rootvalue as $key => $value){
                if(is_array($value)){
                    // indexed arrays under a section heading will have their key omitted
                    $indexed_item = array_keys($value) == range(0, count($value) - 1);
                    foreach($value as $subkey=>$subvalue){
                        // omit subkey for indexed arrays
                        if($indexed_item) $subkey = "";
                        // add this line under the section heading
                        $out .= "{$key}[$subkey] = $subvalue" . PHP_EOL;
                    }
                }else{
                    if($indexed_root){
                        // root level indexed array becomes sectionless
                        $sectionless .= "{$rootkey}[] = $value" . PHP_EOL;
                    }else{
                        // plain values within root level sections
                        $out .= "$key = $value" . PHP_EOL;
                    }
                }
            }

        }else{
            // root level sectionless values
            $sectionless .= "$rootkey = $rootvalue" . PHP_EOL;
        }
    }
    return $sectionless.$out;
}


$svxConfigFile = '/etc/svxlink/svxlink.conf';
$init_pei_tail = ";AT+CTSP=1,3,131;AT+CTSP=1,3,130;AT+CTSP=1,3,138;AT+CTSP=1,2,20;AT+CTSP=2,0,0;AT+CTSP=1,3,24;AT+CTSP=1,3,25;AT+CTSP=1,3,3;AT+CTSP=1,3,10;AT+CTSP=1,1,11;AT+CTSDC=0,0,0,1,1,0,1,1,0,0";
if (fopen($svxConfigFile,'r'))
      {

        $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
        //$svxconfig['TetraLogic']['INIT_PEI'] = $svxconfig['TetraLogic']['INIT_PEI'] . $init_pei_tail;    
};


$logics = explode(",",$svxconfig['GLOBAL']['LOGICS']);
foreach ($logics as $key) {
 // echo "<tr><td style=\"background:#ffffed;\"><span style=\"color:#b5651d;font-weight: bold;\">".$key."</span></td></tr>";
  if ($key == "SimplexLogic") $isSimplex = true;
  if ($key == "TetraLogic") $isTetra = true; 
}





if (isset($_POST['btnSave']))
    {
        $retval = null;
        $screen = null;

        // tail is hardcoded - if need to be changed should be consider to build tetra-device config json file 
        // $init_pei_tail = ";AT+CTSP=1,3,131;AT+CTSP=1,3,130;AT+CTSP=1,3,138;AT+CTSP=1,2,20;AT+CTSP=2,0,0;AT+CTSP=1,3,24;AT+CTSP=1,3,25;AT+CTSP=1,3,3;AT+CTSP=1,3,10;AT+CTSP=1,1,11;AT+CTSDC=0,0,0,1,1,0,1,1,0,0";
        //$ini = build_ini_string($svxconfig);
        //fopen($svxConfigFile,w);
        
	$svxconfig['GLOBAL']['DEFAULT_LANG'] = $_POST['inGlobalDefaultLang'];
	$svxconfig['GLOBAL']['LOGICS'] = $_POST['inGlobalLogics'];
        $svxconfig['GLOBAL']['RF_MODULE'] = $_POST['inGlobalRf'];

	$svxconfig['ReflectorLogic']['DEFAULT_LANG'] = $_POST['inReflectorDefaultLang'];
	$svxconfig['ReflectorLogic']['PORT'] = $_POST['inReflectorPort'];
	$svxconfig['ReflectorLogic']['API'] = $_POST['inReflectorApi'];
        $svxconfig['ReflectorLogic']['HOST'] = $_POST['inReflectorServer'];
        $svxconfig['ReflectorLogic']['DEFAULT_TG'] = $_POST['inDefaultTg'];
        $svxconfig['ReflectorLogic']['MONITOR_TGS'] = $_POST['inMonitorTgs'];
        $svxconfig['ReflectorLogic']['AUTH_KEY'] = $_POST['inPassword'];
        $svxconfig['ReflectorLogic']['FMNET'] = $_POST['inFmNetwork'];
        $svxconfig['ReflectorLogic']['CALLSIGN'] = $_POST['inCallsign'];
	$svxconfig['ReflectorLogic']['TG_URI'] = $_POST['inReflectorTgUri'];

        if ($isSimplex){
	$svxconfig['SimplexLogic']['DEFAULT_LANG'] = $_POST['inSimplexDefaultLang'];
        $svxconfig['SimplexLogic']['CALLSIGN'] = $_POST['inSimplexCallsign'];
        $svxconfig['SimplexLogic']['MODULES'] = $_POST['inSimplexModules'];
        };
        if ($isTetra){
	$svxconfig['TetraLogic']['DEFAULT_LANG'] = $_POST['inTetraDefaultLang'];
        $svxconfig['TetraLogic']['CALLSIGN'] = $_POST['inTetraCallsign'];
        $svxconfig['TetraLogic']['MODULES'] = $_POST['inTetraModules'];
        $svxconfig['TetraLogic']['BAUD'] = $_POST['inTetraBaud'];
        $svxconfig['TetraLogic']['PORT'] = $_POST['inTetraPort'];
        $svxconfig['TetraLogic']['ISSI'] = $_POST['inTetraIssi'];
        $svxconfig['TetraLogic']['GSSI'] = $_POST['inTetraGssi'];
        $svxconfig['TetraLogic']['MNC'] = $_POST['inTetraMnc'];
        $svxconfig['TetraLogic']['MCC'] = $_POST['inTetraMcc'];           //addin the tail 
        $svxconfig['TetraLogic']['INIT_PEI'] = $_POST['inTetraInitPei'] . $init_pei_tail;      
        $svxconfig['TetraLogic']['APRSPATH'] = $_POST['inTetraAprspath']; 
        $svxconfig['TetraLogic']['TETRA_MODE'] = $_POST['inTetraMode']; 
        };

	$svxconfig['Macros']['0'] = $_POST['inMD0'];
        $svxconfig['Macros']['1'] = $_POST['inMD1'];
	$svxconfig['Macros']['2'] = $_POST['inMD2'];
	$svxconfig['Macros']['3'] = $_POST['inMD3'];
	$svxconfig['Macros']['4'] = $_POST['inMD4'];
	$svxconfig['Macros']['5'] = $_POST['inMD5'];
	$svxconfig['Macros']['6'] = $_POST['inMD6'];
	$svxconfig['Macros']['7'] = $_POST['inMD7'];
	$svxconfig['Macros']['8'] = $_POST['inMD8'];
	$svxconfig['Macros']['9'] = $_POST['inMD9'];
	
	$svxconfig['Rx1']['PEAK_METER'] = $_POST['inRx1PeakMeter'];
        //$svxconfig['ReflectorLogic']['PORT'] = $_POST['inReflectorPort'];
        //$svxconfig['ReflectorLogic']['PORT'] = $_POST['inReflectorPort'];
        //$svxconfig['ReflectorLogic']['PORT'] = $_POST['inReflectorPort'];
        $ini = build_ini_string($svxconfig);

        //file_put_contents("/var/www/html/test.ini",$ini,FILE_USE_INCLUDE_PAT);
        file_put_contents("/var/www/html/svxlink/svxlink.conf", $ini ,FILE_USE_INCLUDE_PATH);

	///file manipulation section

	$retval = null;
        $screen = null;
	//archive the current config
	exec('sudo cp /etc/svxlink/svxlink.conf /etc/svxlink/svxlink.conf.' .date("YmdThis") ,$screen,$retval);
	//move generated file to current config
	exec('sudo mv /var/www/html/svxlink/svxlink.conf /etc/svxlink/svxlink.conf', $screen, $retval);
	exec('sudo cp /etc/svxlink/svxlink.conf /etc/svxlink/svxlink.d/TetraLogic.conf', $screen, $retval);
        //Service SVXlink restart
        exec('sudo service svxlink restart 2>&1',$screen,$retval);



// debug
//      echo '<pre>';
//      print_r($ini);

//end of debug

}


//if (fopen($svxConfigFile,'r'))
//      {

//        $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
//};

//$svxConfigFile = '/etc/svxlink/svxlink.conf';
//$svxConfigFile = '/var/www/html/svxlink.conf';    






//if (fopen($svxConfigFile,'r'))
  //    { 

//	$svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
        
	$inGlobalDefaultLang = $svxconfig['GLOBAL']['DEFAULT_LANG'];
        $inGlobalLogics = $svxconfig['GLOBAL']['LOGICS'];
        $inGlobalRf = $svxconfig['GLOBAL']['RF_MODULE'];
	
	$inReflectorDefaultLang = $svxconfig['ReflectorLogic']['DEFAULT_LANG'];
	$inCallsign = $svxconfig['ReflectorLogic']['CALLSIGN'];
	$inReflectorServer =$svxconfig['ReflectorLogic']['HOST'];
	$inReflectorApi =$svxconfig['ReflectorLogic']['API'];
	$inReflectorPort =$svxconfig['ReflectorLogic']['PORT'];
	$inDefaultTg =$svxconfig['ReflectorLogic']['DEFAULT_TG'];
	$inMonitorTgs =$svxconfig['ReflectorLogic']['MONITOR_TGS'];
	$inPassword =$svxconfig['ReflectorLogic']['AUTH_KEY'];
	$inFmNetwork =$svxconfig['ReflectorLogic']['FMNET'];
	$inReflectorTgUri = $svxconfig['ReflectorLogic']['TG_URI'];

        if ($isSimplex){ 
	$inSimplexCallsign = $svxconfig['SimplexLogic']['CALLSIGN'];
	$inSimplexDefaultLang = $svxconfig['SimplexLogic']['DEFAULT_LANG'];
        $inSimplexModules = $svxconfig['SimplexLogic']['MODULES'];
        };

        if ($isTetra){
        $inTetraCallsign = $svxconfig['TetraLogic']['CALLSIGN'];
	$inTetraDefaultLang = $svxconfig['TetraLogic']['DEFAULT_LANG'];
        $inTetraModules = $svxconfig['TetraLogic']['MODULES'];
        $inTetraBaud = $svxconfig['TetraLogic']['BAUD'];
        $inTetraPort = $svxconfig['TetraLogic']['PORT'];
        $inTetraIssi = $svxconfig['TetraLogic']['ISSI'];
        $inTetraGssi = $svxconfig['TetraLogic']['GSSI'];
        $inTetraMnc = $svxconfig['TetraLogic']['MNC'];
        $inTetraMcc = $svxconfig['TetraLogic']['MCC'];          // fix for non standard ADI's use of ini - uncoment if needed.
        $inTetraInitPei = $svxconfig['TetraLogic']['INIT_PEI']; //.";AT+CTSP=1,3,131;AT+CTSP=1,3,130;AT+CTSP=1,3,138;AT+CTSP=1,2,20;AT+CTSP=2,0,0;AT+CTSP=1,3,24;AT+CTSP=1,3,25;AT+CTSP=1,3,3;AT+CTSP=1,3,10;AT+CTSP=1,1,11;AT+CTSDC=0,0,0,1,1,0,1,1,0,0";      
        $inTetraAprspath = $svxconfig['TetraLogic']['APRSPATH'];
        $inTetraMode = $svxconfig['TetraLogic']['TETRA_MODE'];
        };

	$inMD0 =$svxconfig['Macros']['0'];
	$inMD1 =$svxconfig['Macros']['1'];
	$inMD2 =$svxconfig['Macros']['2'];
	$inMD3 =$svxconfig['Macros']['3'];
	$inMD4 =$svxconfig['Macros']['4'];
	$inMD5 =$svxconfig['Macros']['5'];
	$inMD6 =$svxconfig['Macros']['6'];
	$inMD7 =$svxconfig['Macros']['7'];
	$inMD8 =$svxconfig['Macros']['8'];
	$inMD9 =$svxconfig['Macros']['9'];

	$inRx1PeakMeter = $svxconfig['Rx1']['PEAK_METER'];

//}
//    else { $callsign="N0CALL";}



//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//  if (empty($_POST["ssid"])) {
//     echo "Name is required";
//  } else {
//    $ssid = $_POST["ssid"]);
//  }
//}}


// load the connlist
$retval = null;
$conns = null;
// find the gateway
//tbc - load the data from ini RF.

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">


<table>
        <tr>
        <th width = "380px">Global Input</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<TD>
        <Table style="border-collapse: collapse; border: none;">
        <tr style="border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Default Language</td>
        <td style="border: none;"><input type="text" name="inGlobalDefaultLang" style="width:98%" value="<?php echo $inGlobalDefaultLang;?>"></td>
        </tr>
        <tr style="border: none;">
        <td style="border: none;">Logics</td>
        <td style="border: none;"> <input type="text" name="inGlobalLogics" style="width:98%" value="<?php echo $inGlobalLogics;?>"></td>
        </tr>
        </tr>
        <tr style="border: none;">
        <td style="border: none;">RF Module</td>
        <td style="border: none;"> <input type="text" name="inGlobalRf" style="width:98%" value="<?php echo $inGlobalRf;?>"></td>
        </tr>
        </table>

</TD>
<td>
        <button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">Save <BR><Br> & <BR><BR> ReLoad</button>
</td>

</tr>
</table>

<table>
        <tr>
        <th width = "380px">Reflector Input</th>
	<th width = "100px">Action</th>
        </tr>
<tr>
<TD>

<table style="border-collapse: collapse; border: none;">
        <tr style="border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Default Language</td>
        <td style="border: none;">
        <input type="text" name="inReflectorDefaultLang" style="width:98%" value="<?php echo $inReflectorDefaultLang;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">FM Network</td>
        <td style="border: none;"><input type="text" name="inFmNetwork" style="width:98%" value="<?php echo $inFmNetwork;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Callsign</td>
        <td style="border: none;"><input type="text" name="inCallsign" style="width:98%" value="<?php echo $inCallsign;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Password</td>
        <td style="border: none;"><input type="password" name="inPassword" style="width:98%" value="<?php echo $inPassword;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Default TG</td>
        <td style="border: none;"><input type="text" name="inDefaultTg" style="width:98%" value="<?php echo $inDefaultTg;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Monitor TGs</td>
        <td style="border: none;"><input type="text" name="inMonitorTgs" style="width:98%" value="<?php echo $inMonitorTgs;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Reflector Server</td>
        <td style="border: none;"><input type="text" name="inReflectorServer" style="width:98%" value="<?php echo $inReflectorServer;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Reflector Port</td>
        <td style="border: none;"><input type="text" name="inReflectorPort" style="width:98%" value="<?php echo $inReflectorPort;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Reflector Api</td>
        <td style="border: none;"><input type="text" name="inReflectorApi" style="width:98%" value="<?php echo $inReflectorApi;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Reflector TgUri</td>
        <td style="border: none;"><input type="text" name="inReflectorTgUri" style="width:98%" value="<?php echo $inReflectorTgUri;?>">
        </td></tr>
</table>

</td>
<td> 
	<button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">Save <br> 
        <Br> & <BR><BR> ReLoad</button>
</td>
</tr>
</table>


<?php 
if ($isTetra){ include "tetra.php" ;};
if ($isSimplex){ include "simplex.php" ;};

//include "simplex.php";
//include "tetra.php";

?>

<table>
        <tr>
        <th width = "380px">Macros Input</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<TD>
        
<table style="border-collapse: collapse; border: none;">
        <tr style="border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D1</td>
        <td style="border: none;"><input type="text" name="inMD1" style="width:98%" value="<?php echo $inMD1;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D2</td>
        <td style="border: none;"><input type="text" name="inMD2" style="width:98%" value="<?php echo $inMD2;?>">
        <tr style="border: none;"> 
        <td style="border: none;">Macro D3</td>
        <td style="border: none;"><input type="text" name="inMD3" style="width:98%" value="<?php echo $inMD3;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D4</td>
        <td style="border: none;"><input type="text" name="inMD4" style="width:98%" value="<?php echo $inMD4;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D5</td>
        <td style="border: none;"><input type="text" name="inMD5" style="width:98%" value="<?php echo $inMD5;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D6</td>
        <td style="border: none;"><input type="text" name="inMD6" style="width:98%" value="<?php echo $inMD6;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D7</td>
        <td style="border: none;"><input type="text" name="inMD7" style="width:98%" value="<?php echo $inMD7;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D8</td>
        <td style="border: none;"><input type="text" name="inMD8" style="width:98%" value="<?php echo $inMD8;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D9</td>
        <td style="border: none;"><input type="text" name="inMD9" style="width:98%" value="<?php echo $inMD9;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D0</td>
        <td style="border: none;"><input type="text" name="inMD0" style="width:98%" value="<?php echo $inMD0;?>">
        </td></tr>
</table>
</td>
<td>
        <button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">Save <BR><Br> & <BR><BR> ReLoad</button>
</td>
</tr>
</table>



<table>
        <tr>
        <th width = "380px">Rx1 Input</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<TD>
        
<table style="border-collapse: collapse; border: none;">
        <tr style="border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Peak Meter</td>
        <td style="border: none;"><input type="text" name="inRx1PeakMeter" style="width:98%" value="<?php echo $inRx1PeakMeter;?>">
        </td></tr>


</table>
</td>
<td>
        <button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">Save <BR><Br> & <BR><BR> ReLoad</button>
</td>
</tr>
</table>




</form>

<p style="margin: 0 auto;"></p>
<p style="margin-bottom:-2px;"></p>

</body>
</html>
