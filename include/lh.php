<?php
include_once __DIR__.'/config.php';         
include_once __DIR__.'/tools.php';        
include_once __DIR__.'/functions.php';    
include_once __DIR__.'/tgdb.php';    
?>
<span style="font-weight: bold;font-size:14px;">SVXReflector Activity</span>
<fieldset style=" width:620px;box-shadow:0 0 10px #999;background-color:#e8e8e8e8;margin-top:10px;margin-left:0px;margin-right:0px;font-size:12px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
  <table style="margin-top:3px;">
    <tr height=25px>
      <th width=150px>Time (<?php echo date('T')?>)</th>
      <th width=160px>Callsign</th>
      <th width=140px>TG #</th>
      <th>TG Name</th>
    </tr>
<?php
$i = 0;
for ($i = 0;  ($i <= 15); $i++) { //Last 15 calls
	if (isset($lastHeard[$i])) {
		$listElem = $lastHeard[$i];
		if ( $listElem[1] ) {
                        $local_time = strftime("%H:%M:%S %d %b",strtotime($listElem[0]));
		echo"<tr height=24px style=\"font-size:12.5px;>\">";
		echo"<td align=\"left\">&nbsp;$local_time</td>";
                if ($listElem[3] == "OFF" ) {$bgcolor=""; $tximg="";}
                if ($listElem[3] == "ON" ) {$bgcolor=""; $tximg="<img src=images/tx.gif height=21 alt='TXing' title='TXing' style=\"vertical-align: middle;\">";}
                $ref = substr($listElem[1],0,3);
                $call=$listElem[1];
                $ssid = strpos($listElem[1],"-");
                if ((!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $listElem[1]) or $ref=="XLX" or $ref=="YSF" or $ref=="M17" or substr($listElem[1],0,3)=="TG" )) {
                  echo "<td $bgcolor align='left' valign='middle' style=\"font-size:12.5px;font-weight:bold;color:#464646;vertical-align:middle;\"><b>&nbsp;&nbsp;&nbsp;$listElem[1]</b>&nbsp;$tximg</td>";
                } else {
                  if ($ssid){
                  $call = substr($listElem[1],0,$ssid);}
                  echo "<td $bgcolor style=\"vertical-align: middle;font-size:12.5px;\" align=\"left\"> &nbsp;&nbsp;&nbsp;<a href=\"http://www.qrz.com/db/".$call."\" target=\"_blank\"><b>$listElem[1]</b></a>&nbsp;$tximg</td>";
               }
		echo "<td align=\"left\">&nbsp;<span style=\"color:#b5651d;font-weight:bold;\">&nbsp;&nbsp;$listElem[2]</span></td>";
               $tgnumber = substr($listElem[2],3);
               $name=$tgdb_array[$tgnumber];
               if ( $name==""){ $name ="------";}
               if ( $tgnumber>=1239900 and $tgnumber<= 1239999){ $name ="AUTO QSY";}
		echo "<td style=\"font-weight:bold;color:#464646;\">&nbsp;<b>".$name."</b></td>";
		echo"</tr>\n";
		}
	}
}

?>
  </table>
</fieldset>
