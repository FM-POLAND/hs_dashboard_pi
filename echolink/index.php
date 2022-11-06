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
<h1 id="web-audio-peak-meters" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">EchoLink Configurator</h1>


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


$elConfigFile = '/etc/svxlink/svxlink.d/ModuleEchoLink.conf';
if (fopen($elConfigFile,'r'))
      {

        $elconfig = parse_ini_file($elConfigFile,true,INI_SCANNER_RAW);
        //$elconfig['TetraLogic']['INIT_PEI'] = $elconfig['TetraLogic']['INIT_PEI'] . $init_pei_tail;    
};






if (isset($_POST['btnSave']))
    {
        $retval = null;
        $screen = null;

        // tail is hardcoded - if need to be changed should be consider to build tetra-device config json file 
        // $init_pei_tail = ";AT+CTSP=1,3,131;AT+CTSP=1,3,130;AT+CTSP=1,3,138;AT+CTSP=1,2,20;AT+CTSP=2,0,0;AT+CTSP=1,3,24;AT+CTSP=1,3,25;AT+CTSP=1,3,3;AT+CTSP=1,3,10;AT+CTSP=1,1,11;AT+CTSDC=0,0,0,1,1,0,1,1,0,0";
        //$ini = build_ini_string($elconfig);
        //fopen($svxConfigFile,w);
        
	$elconfig['ModuleEchoLink']['DEFAULT_LANG'] = $_POST['inElDefaultLang'];
        $elconfig['ModuleEchoLink']['CALLSIGN'] = $_POST['inElCallsign'];
        $elconfig['ModuleEchoLink']['PASSWORD'] = $_POST['inElPassword'];
        $elconfig['ModuleEchoLink']['SYSOPNAME'] = $_POST['inElSysOpName'];
        $elconfig['ModuleEchoLink']['LOCATION'] = $_POST['inElLocation'];
        
        $elconfig['ModuleEchoLink']['SERVERS'] = $_POST['inElServers'];
       // $elconfig['ModuleEchoLink']['SERVERS'] = $_POST['inElServers'];
        $elconfig['ModuleEchoLink']['PROXY_SERVER'] = $_POST['inElProxyServer'];
        $elconfig['ModuleEchoLink']['PROXY_PORT'] = $_POST['inElProxyPort'];
        $elconfig['ModuleEchoLink']['PROXY_PASSWORD'] = $_POST['inElProxyPassword'];

        $elconfig['ModuleEchoLink']['DESCRIPTION'] = $_POST['inElDescription'];


        $elconfig['ModuleEchoLink']['MUTE_LOGIC_LINKING'] = $_POST['inElMuteLogicLinking'];

        //$elconfig['ReflectorLogic']['PORT'] = $_POST['inReflectorPort'];
        //$elconfig['ReflectorLogic']['PORT'] = $_POST['inReflectorPort'];
        //$elconfig['ReflectorLogic']['PORT'] = $_POST['inReflectorPort'];
        $ini = build_ini_string($elconfig);

        //file_put_contents("/var/www/html/test.ini",$ini,FILE_USE_INCLUDE_PAT);
        file_put_contents("/var/www/html/echolink/ModuleEchoLink.conf", $ini ,FILE_USE_INCLUDE_PATH);

	///file manipulation section

	$retval = null;
        $screen = null;
	//archive the current config
	exec('sudo cp /etc/svxlink/svxlink.d/ModuleEchoLink.conf /etc/svxlink/svxlink.d/ModuleEchoLink.conf.' .date("YmdThis") ,$screen,$retval);
	//move generated file to current config
	exec('sudo mv /var/www/html/echolink/ModuleEchoLink.conf /etc/svxlink/svxlink.d/ModuleEchoLink.conf', $screen, $retval);

        //Service SVXlink restart
        exec('sudo service svxlink restart 2>&1',$screen,$retval);



// debug
//      echo '<pre>';
//      print_r($ini);

//end of debug

}


//if (fopen($svxConfigFile,'r'))
//      {

//        $elconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
//};

//$svxConfigFile = '/etc/svxlink/svxlink.conf';
//$svxConfigFile = '/var/www/html/svxlink.conf';    






//if (fopen($svxConfigFile,'r'))
  //    { 

//	$elconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
        
	$inElDefaultLang = $elconfig['ModuleEchoLink']['DEFAULT_LANG'];
        $inElCallsign = $elconfig['ModuleEchoLink']['CALLSIGN'];
        $inElPassword = $elconfig['ModuleEchoLink']['PASSWORD'];
        $inElSysOpName = $elconfig['ModuleEchoLink']['SYSOPNAME'];
        $inElLocation = $elconfig['ModuleEchoLink']['LOCATION'];
        $inElDescription = $elconfig['ModuleEchoLink']['DESCRIPTION'];

        $inElServers = $elconfig['ModuleEchoLink']['SERVERS'];
        $inElProxyServer =  $elconfig['ModuleEchoLink']['PROXY_SERVER'];
        $inElProxyPort = $elconfig['ModuleEchoLink']['PROXY_PORT'];
        $inElProxyPassword = $elconfig['ModuleEchoLink']['PROXY_PASSWORD'];

        $inElMuteLogicLinking = $elconfig['ModuleEchoLink']['MUTE_LOGIC_LINKING'];

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
        <th width = "380px">Echolink Input</th>
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
        <td style="border: none;"><input type="text" name="inElDefaultLang" style="width:98%" value="<?php echo $inElDefaultLang;?>"></td>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Callsign</td>
        <td style="border: none;"><input type="text" name="inElCallsign" style="width:98%" value="<?php echo $inElCallsign;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Password</td>
        <td style="border: none;"><input type="password" name="inElPassword" style="width:98%" value="<?php echo $inElPassword;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">SysOp Name</td>
        <td style="border: none;"><input type="text" name="inElSysOpName" style="width:98%" value="<?php echo $inElSysOpName;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Location</td>
        <td style="border: none;"><input type="text" name="inElLocation" style="width:98%" value="<?php echo $inElLocation;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Description</td>
        <td style="border: none;"><input type="text" name="inElDescription" style="width:98%" value="<?php echo $inElDescription;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Servers</td>
        <td style="border: none;"><input type="text" name="inElServers" style="width:98%" value="<?php echo $inElServers;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Proxy Server</td>
        <td style="border: none;"><input type="text" name="inElProxyServer" style="width:98%" value="<?php echo $inElProxyServer;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Proxy Port</td>
        <td style="border: none;"><input type="text" name="inElProxyPort" style="width:98%" value="<?php echo $inElProxyPort;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Proxy Password</td>
        <td style="border: none;"><input type="password" name="inElProxyPassword" style="width:98%" value="<?php echo $inElProxyPassword;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Mute Logic Linking</td>
        <td style="border: none;"><input type="text" name="inElMuteLogicLinking" style="width:98%" value="<?php echo $inElMuteLogicLinking;?>">
        </td></tr>
        </Table>


</TD>
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
