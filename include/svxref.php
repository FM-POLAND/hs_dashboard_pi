<?php
include_once __DIR__.'/config.php';          
include_once __DIR__.'/tools.php';       
include_once __DIR__.'/functions.php';    
$url=URLSVXRAPI;
if ($url!="") {
//  Initiate curl
$ch = curl_init();
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);
$nodes = json_decode($result, true);
 } else { $nodes="";}
if ($nodes!="") {
if(array_key_exists('Name', $nodes)) {
    $name=$nodes['Name'];
} else { $name="";}
?>
<br>
<fieldset style="width:620px;border:rgb(255, 156, 42) 2px groove; box-shadow: 5px 5px 20px rgb(255, 236, 214); background-color:#f1f1f1;margin-left:0px;margin-right:0px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style="padding:0px;width:620px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<center>
<p id="rcornerh" style="margin-bottom:35px;"><span style="font-weight: 580;font-size:18px;">Connected Nodes to SVXReflector&nbsp;&nbsp;</span><span style="font-weight: 580;font-size:16px;color:red;"><?php echo FMNETWORK; ?></span><br>
<?php
$count=0;
foreach ($nodes['nodes'] as $key =>$value)
 { $count=$count+1;}
echo "<span style=\"line-height:1.8;font-weight:600;font-size:16px;color:black;\">Number of connected nodes:&nbsp;&nbsp;</span>
      <span style=\"line-height:1.8;font-weight:600;font-size:15px;color:brown;\">";
echo $count;
echo "</span></p>";
?>
</center>
<div style="text-align:left;font:9pt arial,sans-serif;margin-left:25px; margin-right:25px;margin-top:15px;margin-bottom:30px;line-height:1.6;white-space:normal;">
<center>
<?php
foreach ($nodes['nodes'] as $key =>$value)
 { 
   echo "<span class=\"tooltip\" style=\"border-bottom: 1px dotted white;\">";
   echo "<span class=\"node\">".$key."<span class=\"tooltiptext\" style=\"top:100%;left:25%;margin-left:-50%;max-width:200px;width:195px;word-wrap: break-word;white-space: pre-wrap; padding: 3px 0;\">";
   if ($nodes['nodes'][$key]['nodeLocation']!=""){
   echo "&nbsp;&nbsp;Info:<br><span style=\"color:gold;margin-left:10px;margin-right:10px;\"><b>";
   echo $nodes['nodes'][$key]['nodeLocation']."</span><br>";
      }
   echo "&nbsp;&nbsp;Monitored TGs:<br><span style='color:yellow;margin-left:10px;margin-right:10px;'>";
   foreach ($nodes['nodes'][$key]['monitoredTGs'] as $item)
     { echo $item." "; }
   echo "</span></span></span></span>";
 }
?>
</center>
</div><br></div></fieldset>
<?php 
}
?>