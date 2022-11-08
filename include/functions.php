<?php


function getSVXLog() {
	// Open Logfile and copy loglines into LogLines-Array()
	$logLines = array();
	$logLines1 = array();
	$logLines2 = array();
//	if (file_exists(LOGPATH."/".SVXLOGPREFIX."-".gmdate("Y-m-d").".log")) {
	if (file_exists(SVXLOGPATH."/".SVXLOGPREFIX)) {
		$logPath = SVXLOGPATH."/".SVXLOGPREFIX;
		$logLines1 = explode("\n", `tail -10000 $logPath | egrep -a -h "Talker start on|Talker stop on" `);
	}
	$logLines1 = array_slice($logLines1, -250);
	if (sizeof($logLines1) < 250) {
		if (file_exists(SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1")) {
			$logPath = SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1";
			$logLines2 = explode("\n", `tail -10000 $logPath | egrep -a -h "Talker start on|Talker stop on" `);
		}
	}
	$logLines2 = array_slice($logLines2, -250);
//	$logLines = $logLines1 + $logLines2;
	$logLines = array_merge($logLines1,$logLines2);
	$logLines = array_slice($logLines, -500);
	return $logLines;
}

function getSVXStatusLog() {
	// Open Logfile and copy loglines into LogLines-Array()
	$logLines = array();
	$logLines1 = array();
	$logLines2 = array();
	if (file_exists(SVXLOGPATH."/".SVXLOGPREFIX)) {
		$logPath = SVXLOGPATH."/".SVXLOGPREFIX;
		$logLines1 = explode("\n", `tail -10000 $logPath | egrep -a -h "EchoLink QSO|ransmitter|Selecting" `);
	}
	$logLines1 = array_slice($logLines1, -250);
	if (sizeof($logLines1) < 250) {
		if (file_exists(SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1")) {
			$logPath = SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1";
			$logLines2 = explode("\n", `tail -10000 $logPath |egrep -a -h "Talker start on|Talker stop on" `);
		}
	}
	$logLines2 = array_slice($logLines2, -250);
//	$logLines = $logLines1 + $logLines2;
	$logLines = array_merge($logLines1,$logLines2);
	$logLines = array_slice($logLines, -250);
	return $logLines;
}

// SVXReflector connections
//2021-07-22 18:57:03: RefLogic: Heartbeat timeout
//2021-07-22 18:57:03: RefLogic: Disconnected from 127.0.0.1:5300: Locally ordered disconnect
//2021-07-22 18:59:18: RefLogic: Disconnected from 127.0.0.1:5300: Connection timed out
//2021-07-25 16:30:35: RefLogic: Disconnected from 127.0.0.1:5300: Connection refused
//2021-07-25 16:31:46: RefLogic: Disconnected from 127.0.0.1:5300: No route to host
//2021-07-22 19:07:03: RefLogic: Connection established to 127.0.0.1:5300
//2021-07-22 19:07:03: RefLogic: Authentication OK

function getSVXRstatus() {
	if (file_exists(SVXLOGPATH."/".SVXLOGPREFIX)) {
           $slogPath = SVXLOGPATH."/".SVXLOGPREFIX; 
           $svxrstat = `tail -10000 $slogPath | egrep -a -h "Authentication|Connection established|Heartbeat timeout|No route to host|Connection refused|Connection timed out|Locally ordered disconnect|Deactivating link|Activating link" | tail -1`;}
	if ($svxrstat=="" &&  file_exists(SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1")) {
           $slogPath = SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1"; 
           $svxrstat = `tail -10000 $slogPath | egrep -a -h "Authentication|Connection established|Heartbeat timeout|No route to host|Connection refused|Connection timed out|Locally ordered disconnect|Deactivating link|Activating link" | tail -1`;}
           if(strpos($svxrstat,"Authentication OK") || strpos($svxrstat,"Connection established") || strpos($svxrstat,"Activating link")){
              $svxrstatus="Connected";
            }
           elseif (strpos($svxrstat,"Heartbeat timeout") || strpos($svxrstat,"No route to host") || strpos($svxrstat,"Connection refused") || strpos($svxrstat,"Connection timed out") || strpos($svxrstat,"Locally ordered disconnect") || strpos($svxrstat,"Deactivating link")) { $svxrstatus="Not connected";}
           else { $svxrstatus="No status";}
      return $svxrstatus;
}

// SVXLink proxy public log lines
//2021-06-19 20:45:16: Connected to EchoLink proxy 51.83.134.252:8100
//2021-06-19 20:45:16: *** ERROR: Access denied to EchoLink proxy
//2021-06-19 20:45:16: Disconnected from EchoLink proxy 51.83.134.252:8100
//2021-06-19 20:53:19: Connected to EchoLink proxy 44.137.75.82:8100

function getEchoLinkProxy() {
	if (file_exists(SVXLOGPATH."/".SVXLOGPREFIX)) {
           $elogPath = SVXLOGPATH."/".SVXLOGPREFIX; 
           $echoproxy = `tail -10000 $elogPath | grep -a -h "EchoLink proxy" | tail -1`;}
	if ($echoproxy=="" && file_exists(SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1")) {
           $elogPath = SVXLOGPATH.".hdd/".SVXLOGPREFIX.".1"; 
           $echoproxy = `tail -10000 $elogPath | grep -a -h "EchoLink proxy" | tail -1`;}
           if(strpos($echoproxy,"Connected to EchoLink proxy")){
              $proxy=substr($echoproxy,strpos($echoproxy,"Connected to EchoLink proxy")+27);
              $eproxy="Connected to proxy<br><span style=\"color:brown;font-weight:bold;\">".$proxy."</span>";
            }
           elseif(strpos($echoproxy,"Disconnected from EchoLink proxy")){
              $proxy=substr($echoproxy,strpos($echoproxy,"Disconnected from EchoLink proxy")+32);
              $eproxy="<span style=\"color:red;font-weight:bold;\">Disconnected proxy</span><br><span style=\"color:brown;font-weight:bold;\">".$proxy."</span>";
            }
           elseif(strpos($echoproxy,"Access denied to EchoLink proxy")){
              $eproxy="Access denied to proxy";
            }
           else { $eproxy="";}

      return $eproxy;
}


function getEchoLog() {
	if (file_exists(SVXLOGPATH."/".SVXLOGPREFIX)) {
           $elogPath = SVXLOGPATH."/".SVXLOGPREFIX; 
           $echolog = explode("\n",`tail -10000 $elogPath | grep -a -h "EchoLink QSO" `);}
           $echolog = array_slice($echolog, -250);
      return $echolog;
}

function getConnectedEcholink($echolog) {
        $users = Array();
        foreach ($echolog as $ElogLine) {
                //if(strpos($ElogLine,"EchoLink QSO")){
                        //$users = Array();
                //}
                if(strpos($ElogLine,"state changed to CONNECTED")) {
                        $lineParts = explode(" ", $ElogLine);
              if (!in_array(substr($lineParts[2],0,-1), $users)) {
                                array_push($users,trim(substr($lineParts[2],0,-1)));
                        }
                }
                if(strpos($ElogLine,"state changed to DISCONNECTED")) {
                    $lineParts = explode(" ", $ElogLine);
    		    $call=substr($lineParts[2],0,-1);
        	    $pos = array_search($call, $users);
                    array_splice($users, $pos, 1);
                }
        }
        return $users;
}

// check callsign EchoLink talker TXing form log line
// ### EchoLink talker stop SP2ABC
// ### EchoLink talker start SP2ABC


function getEchoLinkTX() {
        $logPath = SVXLOGPATH."/".SVXLOGPREFIX;
        $echotxing="";
        $logLine = `tail -10000 $logPath | egrep -a -h "### EchoLink" | tail -1`;
        if (strpos($logLine,"### EchoLink talker start")) {
          $echotxing=substr($logLine,strpos($logLine,"start")+6,12);
         }
        return $echotxing;
}

function getSVXTGSelect() {
        $logPath = SVXLOGPATH."/".SVXLOGPREFIX;
        $tgselect="0";
        $logLine = `tail -10000 $logPath | egrep -a -h "Selecting" | tail -1`;
        if (strpos($logLine,"TG #")) {
          $tgselect=substr($logLine,strpos($logLine,"#")+1,12);
         }
        return $tgselect;
}

function getSVXTGTMP() {
        $logPath = SVXLOGPATH."/".SVXLOGPREFIX;
        $tgselect="0";
        $logLine = `tail -10000 $logPath | egrep -a -h "emporary monitor" | tail -1`;
        if (strpos($logLine,"Add")) {
          $tgselect=substr($logLine,strpos($logLine,"#")+1,12);
         }
         else {$tgselect=""; }
        return $tgselect;
}

function initModuleArray() {
    $modules = Array();
    foreach (SVXMODULES as $enabled) {
                $modules[$enabled] = 'Off';
        }
    return $modules;
}

function getActiveModules() {
    $logLines = array();
    $logPath = SVXLOGPATH."/".SVXLOGPREFIX;
    $logLines = explode("\n",`tail -10000 $logPath | egrep -a -h "Activating module|Deactivating module" `);
    $logLines = array_slice($logLines, -250);
    $modules = initModuleArray();
        foreach ($logLines as $logLine) {
                if(strpos($logLine,"Activating module")) {
                        $lineParts = explode(" ", $logLine);
	    $modul = substr($lineParts[5],0,-3);
                        if (!array_search($modul, $modules)) {
                                $modules[$modul] = 'On';
                        }
	    if (array_search($modul, $modules)) {
		$modules[$modul] = 'On';
	    }
                }
                if(strpos($logLine,"Deactivating module")) {
                        $lineParts = explode(" ", $logLine);
	    $modul = substr($lineParts[5],0,-3);
	    $modules[$modul] = 'Off';
                }

        }
        return $modules;
}



//SVXLink log line
//14.06.2021 16:00:00: Tx1: Turning the transmitter ON
//14.06.2021 16:00:44: Tx1: Turning the transmitter OFF
//14.06.2021 16:57:27: RefLogic: Talker start on TG #7: DMR-Bridge
//14.06.2021 16:57:27: RefLogic: Selecting TG #7
//14.06.2021 16:57:27: Transmission starts (TG# 0)
//14.06.2021 16:57:28: Tx1: Turning the transmitter ON
//14.06.2021 16:57:33: Transmission stops (TG# 0)
//14.06.2021 16:57:33: RefLogic: Talker stop on TG #7: DMR-Bridge
//14.06.2021 16:57:33: Tx1: Turning the transmitter OFF

function getHeardList($logLines) {
	//array_multisort($logLines,SORT_DESC);
	$heardList = array();
        //print_r($logLines);
	foreach ($logLines as $logLine) {
	     if(strpos($logLine,"Tx1") || strpos($logLine,"Rx1") || strpos($logLine, ": Talker start on") || strpos($logLine, ": Talker stop on")) {
		if (strpos($logLine,": Talker stop on")) {
                $calltemp = substr($logLine,strpos($logLine,"TG")+4,27);
		$callsign = substr($calltemp,strpos($calltemp,":")+1,27);
		$callsign = trim($callsign);
                $target = "TG ".trim(get_string_between($logLine, "#", ":"));
		$source = "SVXRef";
		$timestamp = substr($logLine, 0, 19);
                $tx="OFF";
               } 
		if (strpos($logLine,": Talker start on")) {
                 $calltemp = substr($logLine,strpos($logLine,"TG")+4,27);
		 $callsign = substr($calltemp,strpos($calltemp,":")+1,27);
		 $callsign = trim($callsign);
                 $target = "TG ".trim(get_string_between($logLine, "#", ":"));
		 $source = "SVXRef";
		 $timestamp = substr($logLine, 0, 19);
                 $tmss=strtotime($timestamp);
                 $tmst=strtotime('now');
		 $diff=$tmst-$tmss;
                 if ($diff>300) {
                	$tx="OFF"; 
		    } else { $tx="ON";}
                } 
		// Callsign should be less than 16 chars long, otherwise it could be errorneous
		if ( strlen($callsign) < 16 ) {
			array_push($heardList, array($timestamp, $callsign, $target, $tx, $source));
		}
	}
}
	return $heardList;
}

function getLastHeard($logLines) {
	$lastHeard = array();
	$heardCalls = array();
	$heardList = getHeardList($logLines);
	$counter = 0;
	foreach ($heardList as $listElem) {
		if ( $listElem[4] == "SVXRef" ) {
			$callUuid = $listElem[1]."#".$listElem[2];
			if(!(array_search($callUuid, $heardCalls) > -1)) {
				array_push($heardCalls, $callUuid);
				array_push($lastHeard, $listElem);
				$counter++;
			}
		}
	}
	return $lastHeard;
}

//14.06.2021 16:57:33: Rx1: The squelch is OPEN (2.07523)
//14.06.2021 16:57:33: Rx1: The squelch is CLOSED (4.43843)
//14.06.2021 16:57:33: Tx1: Turning the transmitter ON
//14.06.2021 16:57:33: Tx1: Turning the transmitter OFF

function getTXInfo() {
	$logPath = SVXLOGPATH."/".SVXLOGPREFIX;
	if (file_exists(SVXLOGPATH."/".SVXLOGPREFIX)) { 
                $txstat =`tail -10000 $logPath | egrep -a -h "Turning the transmitter|squelch is|squelch for" | tail -1`; 
	        //print($txstat);
                
                if (strpos($txstat, "ON")) { 
	   	// $timestamp = substr($txstat, 0, 19);
        //      //date_default_timezone_set('Europe/Warsaw');
                // $tmss=strtotime($timestamp);
                // $tmst=strtotime('now');
          	// $diff=$tmst-$tmss;
                // if ($diff>250) {
            	//       $txs="<td style=\"background:#c3e5cc;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#464646;font-weight:bold;\">Listening</div></td></tr>\n"; 
        	//	    } else { $txs="<tr><td style=\"background:#ff6600;color:white;\"><div style=\"margin-top:2px;margin-bottom:2px;font-weight:bold;\">TX</div></td></tr>\n";
                //            }    
                return "<tr><td style=\"background:#ff6600;color:white;\"><div style=\"margin-top:2px;margin-bottom:2px;font-weight:bold;\">TX</div></td></tr>\n";       
                //return $txs;
        }
        //if (strpos($txstat, "OFF")) { 
        //  
        //                return "<tr><td style=\"background:#ff6600;color:white;\"><div style=\"margin-top:2px;margin-bottom:2px;font-weight:bold;\">TXdone</div></td></tr>\n";
        //        }


           //     $txs =  "<td style=\"background:#c3e5cc;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#464646;font-weight:bold;\">Listening</div></td></tr>\n";
        
	//};
	        if (strpos($txstat, "OPEN")) { 		
               
                 return "<tr><td style=\"background:#4aa361;color:black;\"><div style=\"margin-top:2px;margin-bottom:2px;font-weight:bold;\">RX</div></td></tr>\n";
        //;;
                } ;
                return  "<td style=\"background:#c3e5cc;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#464646;font-weight:bold;\">Listening</div></td></tr>\n"; 

        }
}

//}

//2022-11-08 00:36:01: Rx1: Distortion detected! Please lower the input volume!

function getRXPeak() {
        $logPath = SVXLOGPATH."/".SVXLOGPREFIX;
        if (file_exists(SVXLOGPATH."/".SVXLOGPREFIX)) { 
                $txstat =`tail -100 $logPath | egrep -a -h "Distortion detected!" | tail -1`; 
                //print($txstat);
		$timestamp = substr($txstat, 0, 19);
        //      //date_default_timezone_set('Europe/Warsaw');
                $tmss=strtotime($timestamp);
                $tmst=strtotime('now');
                $diff=$tmst-$tmss;
                // if ($diff>250)



                if (strpos($txstat, "Distortion") && ($diff < 1) ) { 
                return "<tr><td style=\"background:#ff6600;color:white;\"><div style=\"margin-top:2px;margin-bottom:2px;font-weight:bold;\">DISTORTION</div></td></tr>\n";
                //return $txs;
        }
                return  "<td style=\"background:#c3e5cc;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#464646;font-weight:bold;\">Peak OK</div></td></tr>\n";
        }
}

//}








function getConfigItem($section, $key, $configs) {
	// retrieves the corresponding config-entry within a [section]
	$sectionpos = array_search("[" . $section . "]", $configs) + 1;
	$len = count($configs);
	while(startsWith($configs[$sectionpos],$key."=") === false && $sectionpos <= ($len) ) {
		if (startsWith($configs[$sectionpos],"[")) {
			return null;
		}
		$sectionpos++;
	}

	return substr($configs[$sectionpos], strlen($key) + 1);
}

function get_string_between($string, $start, $end) {
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) {
	return "";
    }
    $ini += strlen($start);   
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

$logLinesSVX = getSVXLog();
$reverseLogLinesSVX = $logLinesSVX;
array_multisort($reverseLogLinesSVX,SORT_DESC);
$lastHeard = getLastHeard($reverseLogLinesSVX);


?>
