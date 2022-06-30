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
<script src="web-audio-peak-meter.js"></script>
<center>
<fieldset style="border:#3083b8 2px groove;box-shadow:0 0 10px #999; background-color:#f1f1f1; width:600px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style="padding:0px;width:595px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<center>
<h1 id="web-audio-peak-meters" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">WiFi Configurator</h1>


<?php 



//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//  if (empty($_POST["ssid"])) {
//     echo "Name is required";
//  } else {
//    $ssid = $_POST["ssid"]);
//  }
//}}





if (isset($_POST['btnScan']))
    {
        $retval = null;
	$screen = null;
	exec('nmcli dev wifi rescan');
	exec('nmcli dev wifi list 2>&1',$screen,$retval);
	//$screen[$screen.length]="\n";
	$screen[$screen.length]="Keep in mind the OPIZ don't like 1st and 13th channel unless you use non-standard WIFI antenna.";
}

if (isset($_POST['btnConnList']))
    {
        
	$retval = null;
	$screen = null;
	//exec('nmcli dev wifi rescan');
        exec('nmcli con show --order type 2>&1',$screen,$retval);
}

if (isset($_POST['btnSwitch']))
    {

        $retval = null;
        $screen = null;
        $ssid = $_POST['ssid'];
	//exec('nmcli dev wifi rescan');
        $command = 'nmcli dev wifi connect ' .$ssid. ' 2>&1'; 
	exec($command,$screen,$retval);
}

if (isset($_POST['btnDelete']))
    {

        $retval = null;
        $screen = null;
        $ssid = $_POST['ssid'];
        //exec('nmcli dev wifi rescan');
        $command = 'nmcli con delete ' .$ssid. ' 2>&1';
        exec($command,$screen,$retval);
}

if (isset($_POST['btnAdd']))
    {

        $retval = null;
        $screen = null;
        $ssid = $_POST['ssid'];
        $password = $_POST['password'];
	//exec('nmcli dev wifi rescan');
        $command = "nmcli dev wifi connect " .$ssid. " password  \"" . $password . "\"  2>&1";
        exec($command,$screen,$retval);
}

if (isset($_POST['btnWifiStatus']))
    {

        $retval = null;
        $screen = null;
        //$ssid = $_POST['ssid'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = 'nmcli radio 2>&1';
        exec($command,$screen,$retval);
}


if (isset($_POST['btnWifiOn']))
    {

        $retval = null;
        $screen = null;
        //$ssid = $_POST['ssid'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = 'nmcli radio wifi on 2>&1';
        exec($command,$screen,$retval);
	$command = 'nmcli radio wifi 2>&1';
        exec($command,$screen,$retval);



}


?>
 <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
<DIV style="height:150px">
<table>
	<tr>
	<th>Screen</th> 
	</tr>
<tr>
<Td>
	 
	<textarea name="scan" rows="10" cols="80"><?php 
			echo implode("\n",$screen); ?></textarea>

 </td>
</tr>  
</table> 
</DIV>

<table>
        <tr>
        <th width = "100px">Action</th>
        <th width = "580px">Input</th>
	<th width = "100px">Action</th>
        </tr>
<tr>
<Td>

<p style="margin-bottom:0px;"></p>

        <button name="btnScan" type="submit" class="red" onclick="func()" id="runRec" type="button" value="List" style="height:45px;font-size:12px;">Air Scan</button>
 	<br>
	<button name="btnConnList" type="submit" class="red" onclick="func()" id="runRec" type="button" value="List" style="height:45px;font-size:12px;">Conn List</button>
</tD><TD>

SSID (network name): <input type="text" name="ssid" value="<?php echo $ssid;?>">
<BR>
Password: <input type="password" name="password" value="<?php echo $password;?>">
<BR>
<button name="btnWifiStatus" type="submit" class="red" onclick="func()" id="runRec" type="button" value="List" style="height:45px;font-size:12px;">WiFi Status</button>
<button name="btnAdd" type="submit" class="red" onclick="func()" id="runRec" type="button" value="List" style="height:45px;font-size:12px;">Add Network & Connect</button>
<button name="btnWifiOn" type="submit" class="red" onclick="func()" id="runRec" type="button" value="List" style="height:45px;font-size:12px;">WiFi On</button> 

</td>
<td>
        <button name="btnSwitch" type="submit" class="red" onclick="func()" id="runRec" type="button" value="List" style="height:45px;font-size:12px;">Switch to SSID</button>
        <br>
        <button name="btnDelete" type="submit" class="red" onclick="func()" id="runRec" type="button" value="List" style="height:45px;font-size:12px;">Delete SSID</button>

</td>
</tr>
</table>

</form>

<p style="margin: 0 auto;"></p>
<p style="margin-bottom:-2px;"></p>

</body>
</html>
