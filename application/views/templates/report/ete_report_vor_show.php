<?php 
    	ob_start();
		$ID_VESSEL= $vesvoy->ID_VESSEL;
		$VESSEL= $vesvoy->VESSEL;
		$VOYAGE= $vesvoy->VOYAGE;
		$ATA= $vesvoy->ATA;
		$VESSEL_CODE= $vesvoy->VESSEL_CODE;
		$ATB= $vesvoy->ATB;
		$LENGTH= $vesvoy->LENGTH;
		$ATD= $vesvoy->ATD;
        $html = "<table style='border:1px solid #ddd;'  cellpadding='2' >
            <tr nobr='true'>
                <td class='title' style='border:1px solid #ddd;' colspan='5'> VESSEL OPERATION REPORT</td>
            </tr>
            <tr nobr='true'>
                <td colspan='5'><br/><br/></td>
            </tr>
			<tr nobr='true'>
                <td class='coltitle' style='border:1px solid #ddd;' colspan='2'> VESSEL</td>
                <td class='coltitle2' style='border:1px solid #ddd;' colspan='2'><b>$ID_VESSEL:$VESSEL $year</b></td>
            </tr>
            <tr nobr='true' >
                <td class='coltitle' style='border:1px solid #ddd;' colspan='2'> Voyage</td>
                <td class='coltitle2' style='border:1px solid #ddd;' colspan='2'>$VOYAGE</td>
            </tr>
            <tr nobr='true'>
                <td colspan='5'><br/></td>
            </tr>
            <tr nobr='true'>
                <td style='border:1px solid #ddd;'> Vessel Name</td>
                <td style='border:1px solid #ddd;'><b>$VESSEL</b></td>
                <td style='border:1px solid #ddd;'>Arrival Date/Time</td>
                <td style='border:1px solid #ddd;'><b>$ATA</b></td>
            </tr>
            <tr nobr='true'>
                <td style='border:1px solid #ddd;'> Voyage Code</td>
                <td style='border:1px solid #ddd;'><b>$VESSEL_CODE</b></td>
                <td style='border:1px solid #ddd;'>Berth Date/Time</td>
                <td style='border:1px solid #ddd;'><b>$ATB</b></td>
            </tr>
            <tr nobr='true'>
                <td style='border:1px solid #ddd;'> Voyage LOA</td>
                <td style='border:1px solid #ddd;'><b>$LENGTH M</b></td>
                <td style='border:1px solid #ddd;'>Departure Date/Time</td>
                <td style='border:1px solid #ddd;'><b>$ATD</b></td>
            </tr>
        </table>
		
        <br/>
        <table cellpadding='2' style='border:1px solid #ddd;'>
            <tr nobr='true' style='vertical-align:middle;' bgcolor='#FFFF00'>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;' rowspan='2'>Crane No</th>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;' rowspan='2'>Commence Operation</th>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;' rowspan='2'>Complete Operation</th>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;' colspan='2'>Crane Work Time(Hour)</th>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;' colspan='4' style='text-align:center;vertical-align:middle;'>Total (Box)</th>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;' colspan='2'>Crane Rate (Box)</th>
            </tr>
            <tr nobr='true' style='vertical-align:middle;' bgcolor='#FFFF00'>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Gross</th>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Net</th>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Discharge</th>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Load</th>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Move</th>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Hatch Covers</th>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Gross</th>
                <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Net</th>
            </tr>";
    
        $total_discharge=0;
        $total_loading=0;
        $total_move=0;
        $total_gross=0;
        $total_net=0;
        $total_hatch=0;
        $total_gross_rate=0;
        $total_net_rate=0;
        $arr = array();
        $i=0;
        // debux($crane);
        foreach ($crane as $key) {
            if($key['COMPLETE_OPERATION'] != ''){
                $gross_h = round(((strtotime($key['COMPLETE_OPERATION'])-strtotime($key['COMMENCE_OPERATION']))/3600),1);
            }else{
                $gross_h = 0;
            }
            
            //ROUND(($key['END_WORK']-$key['START_WORK'])*24,1);
            //echo $gross_h."<br />";
            $move = $key['COMPLETE_DISC']+$key['COMPLETE_LOAD'];
            
            $total_discharge = $total_discharge + $key['COMPLETE_DISC']; 
            $total_loading = $total_loading + $key['COMPLETE_LOAD'];
            $total_move = $total_move + $move;
            $total_gross = $total_gross + $gross_h;
            $total_hatch = $total_hatch + $key['TOTAL_HATCH'];

            $gross_rate = ($move + $key['TOTAL_HATCH'])/$gross_h;
            $total_gross_rate = $total_gross_rate + $gross_rate;

            $arr[$i] = $gross_h;

            $st = $this->machine->getTotalOutage($id_ves_voyage,$key['MCH_NAME'])->TOTAL_OUTAGE;
            $net = round($gross_h - ($st/60),1);

            $net_rate = ($move + $key['TOTAL_HATCH'])/$net;
            //echo $net." | ".$net_rate."<br />";
            $total_net = $total_net + $net;
            $total_net_rate = $total_net_rate + $net_rate;

            //echo $net_rate." | ".$gross_rate."<br />";
            $gross_rate = round($gross_rate,1);
            $net_rate = round($net_rate,1);
            $html.= "
            <tr nobr='true'>
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$key[MCH_NAME]</td>
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$key[COMMENCE_OPERATION]</td> 
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$key[COMPLETE_OPERATION]</td> 
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$gross_h</td> 
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$net</td> 
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$key[COMPLETE_DISC]</td> 
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$key[COMPLETE_LOAD]</td> 
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$move</td> 
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$key[TOTAL_HATCH]</td> 
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$gross_rate</td> 
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$net_rate</td> 
            </tr>";
            $i++; 
        }
        $total_gross = round($total_gross,1);
        $total_net = round($total_net,1);
        $total_gross_rate = round($total_gross_rate,1);
        $total_net_rate = round($total_net_rate,1);
        
        $html.= "
            <tr nobr='true' bgcolor='#CCCCCC'>
                <td style='border:1px solid #aaaaaa;text-align:center;vertical-align:middle;' colspan='3' class='abu-terang'>Total Work</td>
                <td style='border:1px solid #aaaaaa;text-align:center;vertical-align:middle;' class='abu-terang'>$total_gross</td>
                <td style='border:1px solid #aaaaaa;text-align:center;vertical-align:middle;' class='abu-terang'>$total_net</td>
                <td style='border:1px solid #aaaaaa;text-align:center;vertical-align:middle;' class='abu-terang'>$total_discharge</td>
                <td style='border:1px solid #aaaaaa;text-align:center;vertical-align:middle;' class='abu-terang'>$total_loading</td>
                <td style='border:1px solid #aaaaaa;text-align:center;vertical-align:middle;' class='abu-terang'>$total_move</td>
                <td style='border:1px solid #aaaaaa;text-align:center;vertical-align:middle;' class='abu-terang'>$total_hatch</td>
                <td style='border:1px solid #aaaaaa;text-align:center;vertical-align:middle;' class='abu-terang'>$total_gross_rate</td>
                <td style='border:1px solid #aaaaaa;text-align:center;vertical-align:middle;' class='abu-terang'>$total_net_rate</td>
            </tr>
        </table>";
$maxArr = max($arr);
$html.= "
    <br/>
    <table class='widthtengah' cellpadding='2' style='border:1px solid #ddd;'>
        <tr nobr='true'>
            <td width='50' style='border:1px solid #ddd;'>Operation</td>
            <td width='80'style='border:1px solid #ddd;'>$maxArr H</td>
        </tr>
        <tr nobr='true'>
            <td width='50' style='border:1px solid #ddd;'>Berthing</td>";
    
    if($vesvoy->ATD == NULL || $vesvoy->ATB == NULL){
        $berthing = 0;
    }else{
        $atd = strtotime($vesvoy->ATD);
        $atb = strtotime($vesvoy->ATB);
        $berthing = round((($atd - $atb)/3600),1);
    }
$html.= "
            <td width='80' style='border:1px solid #ddd;'>$berthing H</td>
        </tr>
    </table>
<br/>
<table cellpadding='1.9' style='border:1px solid #ddd;'>
    <tr nobr='true'><td colspan='10' bgcolor='#CCCCCC' style='text-align: center; vertical-align:middle; border:1px solid #aaaaaa;'>Suspend Detail</td></tr>
    <tr nobr='true' style='vertical-align:middle;' bgcolor='#FFFF00'>
       <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>No</th>
       <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Machine</th>
       <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Suspend Code</th>
       <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Suspend Description</th>
       <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Start Date</th>
       <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Start Time</th>
       <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>End Date</th>
       <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>End Time</th>
       <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Outage Min(s)</th>
       <th style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>Remark</th>
    </tr>";
    
    $total_diff=0;
    $no=1;
    foreach($suspend_detail as $key2){ 
        $total_diff = $total_diff + $key2['OUTAGE'];

        $html.= "
            <tr nobr='true' style='vertical-align:middle;'>
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$no</td>
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$key2[MCH_NAME]</td>
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$key2[ID_SUSPEND]</td>
                <td style='border:1px solid #ddd;'>$key2[ACTIVITY]</td>
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$key2[START_DATE]</td>
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$key2[START_TIME]</td>
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$key2[END_DATE]</td>
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$key2[END_TIME]</td>
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'>$key2[OUTAGE]</td>
                <td style='border:1px solid #ddd;text-align:center;vertical-align:middle;'></td>
            </tr>";
        $no++; 
    }
    if($total_diff != 0){
        
        $html.= "
            <tr nobr='true'>
                <td style='border:1px solid #aaaaaa;' colspan='8' bgcolor='#CCCCCC'>TOTAL</td>
                <td style='border:1px solid #aaaaaa;text-align:center;vertical-align:middle;' bgcolor='#CCCCCC'>$total_diff</td>
                <td style='border:1px solid #aaaaaa;text-align:center;vertical-align:middle;' bgcolor='#CCCCCC'></td>
            </tr>";
    }

$html.= "
</table>
<br/>
<table cellpadding='2' style='border:1px solid #ddd;'>
    <tr nobr='true'><td colspan='4' bgcolor='#CCCCCC' style='text-align: center; vertical-align:middle;border:1px solid #aaaaaa;'>Suspend Summary</td></tr>
    <tr nobr='true' style='vertical-align:middle;' bgcolor='#FFFF00'>
       <th style='border:1px solid #ddd;text-align: center;'>Machine</th>
       <th style='border:1px solid #ddd;text-align: center;'>Suspend Code</th>
       <th style='border:1px solid #ddd;text-align: center;'>Suspend Description</th>
       <th style='border:1px solid #ddd;text-align: center;'>Outage Min(s)</th>
    </tr>";
    
        $total_all=0;
        foreach($suspend_summary as $value){
            $total_diff = 0;
            foreach($value['suspend'] as $val){ 
                $total_diff = $total_diff +  $val['DIFF_MINUTES'];
                $html.= "
                    <tr nobr='true'>
                        <td style='border:1px solid #ddd;text-align: center;'>$val[MCH_NAME]</td>
                        <td style='border:1px solid #ddd;text-align: center;'>$val[ID_SUSPEND]</td>
                        <td style='border:1px solid #ddd;'>$val[ACTIVITY]</td>
                        <td style='border:1px solid #ddd;text-align: center;'>$val[DIFF_MINUTES]</td>
                    </tr>";
            }
            $html.= "
            <tr nobr='true'>
                <td colspan='3' style='border:1px solid #aaaaaa;' bgcolor='#CCCCCC'>SUB TOTAL</td>
                <td style='border:1px solid #aaaaaa;text-align: center;' bgcolor='#CCCCCC'>$total_diff</td>
            </tr>";
            
            $total_all = $total_all + $total_diff; 
        }
        if($total_all != 0){
        $html.= "
        <tr nobr='true'>
            <td colspan='3' style='border:1px solid #aaaaaa;' bgcolor='#CCCCCC'> TOTAL</td>
            <td style='border:1px solid #aaaaaa;text-align: center;'bgcolor='#CCCCCC'>$total_all</td>
        </tr>";
    }
$html.= "
</table>";
echo"$html";
        ob_end_flush(); 
?>