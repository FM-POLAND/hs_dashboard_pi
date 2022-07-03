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
//$svxConfigFile = '/var/www/html/svxlink.conf';    


if (fopen($svxConfigFile,'r'))
      {

        $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
};



if (isset($_POST['btnSave']))
    {
        $retval = null;
        $screen = null;
        //$ini = build_ini_string($svxconfig);
        //fopen($svxConfigFile,w);
        $svxconfig['ReflectorLogic']['PORT'] = $_POST['inReflectorPort'];
        $svxconfig['ReflectorLogic']['HOST'] = $_POST['inReflectorServer'];
        $svxconfig['ReflectorLogic']['DEFAULT_TG'] = $_POST['inDefaultTg'];
        $svxconfig['ReflectorLogic']['MONITOR_TGS'] = $_POST['inMonitorTgs'];
        $svxconfig['ReflectorLogic']['AUTH_KEY'] = $_POST['inPassword'];
        $svxconfig['ReflectorLogic']['FMNET'] = $_POST['inFmNetwork'];
        $svxconfig['ReflectorLogic']['CALLSIGN'] = $_POST['inCallsign'];

        $svxconfig['SimplexLogic']['CALLSIGN'] = $_POST['inCallsignSimplex'];
	
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
	
        //Service SVXlink restart
        exec('sudo service svxlink restart 2>&1',$screen,$retval);



// debug
//      echo '<pre>';
//      print_r($ini);

//end of debug

}

//$svxConfigFile = '/etc/svxlink/svxlink.conf';



//if (fopen($svxConfigFile,'r'))
  //    { 

//	$svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
        $inCallsign = $svxconfig['ReflectorLogic']['CALLSIGN'];
	$inReflectorServer =$svxconfig['ReflectorLogic']['HOST'];
	$inReflectorPort =$svxconfig['ReflectorLogic']['PORT'];
	$inDefaultTg =$svxconfig['ReflectorLogic']['DEFAULT_TG'];
	$inMonitorTgs =$svxconfig['ReflectorLogic']['MONITOR_TGS'];
	$inPassword =$svxconfig['ReflectorLogic']['AUTH_KEY'];
	$inFmNetwork =$svxconfig['ReflectorLogic']['FMNET'];

	$inCallsignSimplex = $svxconfig['SimplexLogic']['CALLSIGN'];

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
        <th width = "380px">Reflector Input</th>
	<th width = "100px">Action</th>
        </tr>
<tr>
<TD>
	FM Network: <input type="text" name="inFmNetwork" style="width: 150px;" value="<?php echo $inFmNetwork;?>">
<BR>
        Callsign: <input type="text" name="inCallsign" style="width: 150px;" value="<?php echo $inCallsign;?>">
<BR> 
       	Password: <input type="password" name="inPassword" style="width: 150px;" value="<?php echo $inPassword;?>">
<BR>
	Default TG: <input type="text" name="inDefaultTg" style="width: 150px;" value="<?php echo $inDefaultTg;?>">
<BR>
	Monitor TGs: <input type="text" name="inMonitorTgs" style="width: 150px;" value="<?php echo $inMonitorTgs;?>">
<BR>
	Reflector Server: <input type="text" name="inReflectorServer" style="width: 150px;" value="<?php echo $inReflectorServer;?>">
<BR>
	Reflector Port: <input type="text" name="inReflectorPort" style="width: 150px;" value="<?php echo $inReflectorPort;?>">

</td>
<td> 
	<button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">Save <BR><Br> & <BR><BR> ReLoad</button>
</td>
</tr>
</table>





<table>
        <tr>
        <th width = "380px">Simplex Input</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<TD>
        Callsign: <input type="text" name="inCallsignSimplex" style="width: 150px;" value="<?php echo $inCallsignSimplex;?>">
</td>
<td>
        <button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">Save <BR><Br> & <BR><BR> ReLoad</button>
</td>
</tr>
</table>


<table>
        <tr>
        <th width = "380px">Macros Input</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<TD>
        Macro D1: <input type="text" name="inMD1" style="width: 150px;" value="<?php echo $inMD1;?>">
<BR>
        Macro D2: <input type="text" name="inMD2" style="width: 150px;" value="<?php echo $inMD2;?>">
<BR>
        Macro D3: <input type="text" name="inMD3" style="width: 150px;" value="<?php echo $inMD3;?>">
<BR>
        Macro D4: <input type="text" name="inMD4" style="width: 150px;" value="<?php echo $inMD4;?>">
<BR>
        Macro D5: <input type="text" name="inMD5" style="width: 150px;" value="<?php echo $inMD5;?>">
<BR>
        Macro D5: <input type="text" name="inMD6" style="width: 150px;" value="<?php echo $inMD6;?>">
<BR>
        Macro D7: <input type="text" name="inMD7" style="width: 150px;" value="<?php echo $inMD7;?>">
<BR>
        Macro D8: <input type="text" name="inMD8" style="width: 150px;" value="<?php echo $inMD8;?>">
<BR>
        Macro D9: <input type="text" name="inMD9" style="width: 150px;" value="<?php echo $inMD9;?>">
<BR>
        Macro D0: <input type="text" name="inMD0" style="width: 150px;" value="<?php echo $inMD0;?>">
<BR>

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
