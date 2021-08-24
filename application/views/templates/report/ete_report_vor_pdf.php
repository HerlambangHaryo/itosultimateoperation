
<?php require_once('tcpdf/config/lang/eng.php');
    	ob_start();
        $pdf = new TCPDF("P", "mm","A4", true, "UTF-8", false);
        $filename = "$vesvoy->VESSEL $vesvoy->VOYAGE.pdf";
        $pdf->SetAuthor('ILCS');
		$pdf->SetTitle($filename);
		$pdf->SetSubject('Stowage');
		$pdf->SetKeywords('Stowage, IPC, !TOS');
        $pdf->AddPage('L', 'A4');
        $PDF_MARGIN_BOTTOM = 10;
        $pdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM);
        $pdf->SetFont('times','B',12);
        $pdf->SetFillColor(204, 204, 204);
        $pdf->Cell($pdf->GetPageWidth()-20, 10, 'VESSEL OPERATION REPORT', 0, 0, 'C', true);
        $pdf->Ln();
        $pdf->Ln();
        
        $year = date('Y');
        $pdf->SetFont('times','',10);
        $html = <<<EOD
        <table style="border:1px solid #ddd;"  cellpadding="2" >
            <tr nobr="true">
                <td width="80" style="border:1px solid #ddd;"> VESSEL</td>
                <td style="border:1px solid #ddd;"><b>  $vesvoy->ID_VESSEL : $vesvoy->VESSEL $year</b></td>
            </tr>
            <tr nobr="true" >
                <td width="80" style="border:1px solid #ddd;"> Voyage</td>
                <td style="border:1px solid #ddd;">  $vesvoy->VOYAGE</td>
            </tr>
        </table>
        <br/>
        <table style="border:1px solid #ddd;" cellpadding="2" >
            <tr nobr="true">
                <td width="80" style="border:1px solid #ddd;"> Vessel Name</td>
                <td style="border:1px solid #ddd;"><b> $vesvoy->VESSEL</b></td>
                <td style="border:1px solid #ddd;">Arrival Date/Time</td>
                <td style="border:1px solid #ddd;"><b> $vesvoy->ATA</b></td>
            </tr>
            <tr nobr="true">
                <td width="80" style="border:1px solid #ddd;"> Voyage Code</td>
                <td style="border:1px solid #ddd;"><b> $vesvoy->VESSEL_CODE</b></td>
                <td style="border:1px solid #ddd;">Berth Date/Time</td>
                <td style="border:1px solid #ddd;"><b> $vesvoy->ATB</b></td>
            </tr>
            <tr nobr="true">
                <td width="80" style="border:1px solid #ddd;"> Voyage LOA</td>
                <td style="border:1px solid #ddd;"><b> $vesvoy->LENGTH M</b></td>
                <td style="border:1px solid #ddd;">Departure Date/Time</td>
                <td style="border:1px solid #ddd;"><b> $vesvoy->ATD</b></td>
            </tr>
        </table>
EOD;

        $pdf->Ln();
        $html .= <<<EOD
        <br/>
        <table cellpadding="2" style="border:1px solid #ddd;">
            <tr nobr="true" style="vertical-align:middle;" bgcolor="#FFFF00">
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;" rowspan="2">Crane No</th>
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;" rowspan="2">Commence Operation</th>
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;" rowspan="2">Complete Operation</th>
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;" colspan="2">Crane Work Time(Hour)</th>
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;" colspan="4" style="text-align:center;vertical-align:middle;">Total (Box)</th>
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;" colspan="2">Crane Rate (Box)</th>
            </tr>
            <tr nobr="true" style="vertical-align:middle;" bgcolor="#FFFF00">
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Gross</th>
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Net</th>
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Discharge</th>
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Load</th>
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Move</th>
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Hatch Covers</th>
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Gross</th>
                <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Net</th>
            </tr>
EOD;
    
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
            $html .= <<<EOD
            <tr nobr="true">
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$key[MCH_NAME]</td>
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$key[COMMENCE_OPERATION]</td> 
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$key[COMPLETE_OPERATION]</td> 
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$gross_h</td> 
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$net</td> 
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$key[COMPLETE_DISC]</td> 
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$key[COMPLETE_LOAD]</td> 
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$move</td> 
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$key[TOTAL_HATCH]</td> 
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$gross_rate</td> 
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$net_rate</td> 
            </tr>
EOD;
            $i++; 
        }
        $total_gross = round($total_gross,1);
        $total_net = round($total_net,1);
        $total_gross_rate = round($total_gross_rate,1);
        $total_net_rate = round($total_net_rate,1);
        
        $html .= <<<EOD
            <tr nobr="true" bgcolor="#CCCCCC">
                <td style="border:1px solid #aaaaaa;text-align:center;vertical-align:middle;" colspan="3" class="abu-terang">Total Work</td>
                <td style="border:1px solid #aaaaaa;text-align:center;vertical-align:middle;" class="abu-terang">$total_gross</td>
                <td style="border:1px solid #aaaaaa;text-align:center;vertical-align:middle;" class="abu-terang">$total_net</td>
                <td style="border:1px solid #aaaaaa;text-align:center;vertical-align:middle;" class="abu-terang">$total_discharge</td>
                <td style="border:1px solid #aaaaaa;text-align:center;vertical-align:middle;" class="abu-terang">$total_loading</td>
                <td style="border:1px solid #aaaaaa;text-align:center;vertical-align:middle;" class="abu-terang">$total_move</td>
                <td style="border:1px solid #aaaaaa;text-align:center;vertical-align:middle;" class="abu-terang">$total_hatch</td>
                <td style="border:1px solid #aaaaaa;text-align:center;vertical-align:middle;" class="abu-terang">$total_gross_rate</td>
                <td style="border:1px solid #aaaaaa;text-align:center;vertical-align:middle;" class="abu-terang">$total_net_rate</td>
            </tr>
        </table>
EOD;
$maxArr = max($arr);
$html .= <<<EOD
    <br/>
    <table cellpadding="2" style="border:1px solid #ddd;">
        <tr nobr="true">
            <td width="50" style="border:1px solid #ddd;">Operation</td>
            <td width="80"style="border:1px solid #ddd;">$maxArr H</td>
        </tr>
        <tr nobr="true">
            <td width="50" style="border:1px solid #ddd;">Berthing</td>
            
EOD;
    
    if($vesvoy->ATD == NULL || $vesvoy->ATB == NULL){
        $berthing = 0;
    }else{
        $atd = strtotime($vesvoy->ATD);
        $atb = strtotime($vesvoy->ATB);
        $berthing = round((($atd - $atb)/3600),1);
    }
$html .= <<<EOD
            <td width="80" style="border:1px solid #ddd;">$berthing H</td>
        </tr>
    </table>
EOD;
$html .= <<<EOD
<br/>
<table cellpadding="1.9" style="border:1px solid #ddd;">
    <tr nobr="true"><td colspan="10" bgcolor="#CCCCCC" style="text-align: center; vertical-align:middle; border:1px solid #aaaaaa;">Suspend Detail</td></tr>
    <tr nobr="true" style="vertical-align:middle;" bgcolor="#FFFF00">
       <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">No</th>
       <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Machine</th>
       <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Suspend Code</th>
       <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Suspend Description</th>
       <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Start Date</th>
       <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Start Time</th>
       <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">End Date</th>
       <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">End Time</th>
       <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Outage Min(s)</th>
       <th style="border:1px solid #ddd;text-align:center;vertical-align:middle;">Remark</th>
    </tr>
EOD;
    
    $total_diff=0;
    $no=1;
    foreach($suspend_detail as $key2){ 
        $total_diff = $total_diff + $key2['OUTAGE'];

        $html .= <<<EOD
            <tr nobr="true" style="vertical-align:middle;">
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$no</td>
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$key2[MCH_NAME]</td>
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$key2[ID_SUSPEND]</td>
                <td style="border:1px solid #ddd;">$key2[ACTIVITY]</td>
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$key2[START_DATE]</td>
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$key2[START_TIME]</td>
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$key2[END_DATE]</td>
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$key2[END_TIME]</td>
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;">$key2[OUTAGE]</td>
                <td style="border:1px solid #ddd;text-align:center;vertical-align:middle;"></td>
            </tr>
EOD;
        $no++; 
    }
    if($total_diff != 0){
        
        $html .= <<<EOD
            <tr nobr="true">
                <td style="border:1px solid #aaaaaa;" colspan="8" bgcolor="#CCCCCC">TOTAL</td>
                <td style="border:1px solid #aaaaaa;text-align:center;vertical-align:middle;" bgcolor="#CCCCCC">$total_diff</td>
                <td style="border:1px solid #aaaaaa;text-align:center;vertical-align:middle;" bgcolor="#CCCCCC"></td>
            </tr>
EOD;
    }

$html .= <<<EOD
</table>
EOD;
$html .= <<<EOD
<br/>
<table cellpadding="2" style="border:1px solid #ddd;">
    <tr nobr="true"><td colspan="4" bgcolor="#CCCCCC" style="text-align: center; vertical-align:middle;border:1px solid #aaaaaa;">Suspend Summary</td></tr>
    <tr nobr="true" style="vertical-align:middle;" bgcolor="#FFFF00">
       <th style="border:1px solid #ddd;text-align: center;">Machine</th>
       <th style="border:1px solid #ddd;text-align: center;">Suspend Code</th>
       <th style="border:1px solid #ddd;text-align: center;">Suspend Description</th>
       <th style="border:1px solid #ddd;text-align: center;">Outage Min(s)</th>
    </tr>
EOD;
    
        $total_all=0;
        foreach($suspend_summary as $value){
            $total_diff = 0;
            foreach($value['suspend'] as $val){ 
                $total_diff = $total_diff +  $val['DIFF_MINUTES'];
                $html .= <<<EOD
                    <tr nobr="true">
                        <td style="border:1px solid #ddd;text-align: center;">$val[MCH_NAME]</td>
                        <td style="border:1px solid #ddd;text-align: center;">$val[ID_SUSPEND]</td>
                        <td style="border:1px solid #ddd;">$val[ACTIVITY]</td>
                        <td style="border:1px solid #ddd;text-align: center;">$val[DIFF_MINUTES]</td>
                    </tr>
EOD;
            }
            $html .= <<<EOD
            <tr nobr="true">
                <td colspan="3" style="border:1px solid #aaaaaa;" bgcolor="#CCCCCC">SUB TOTAL</td>
                <td style="border:1px solid #aaaaaa;text-align: center;" bgcolor="#CCCCCC">$total_diff</td>
            </tr>
EOD;
            
            $total_all = $total_all + $total_diff; 
        }
        if($total_all != 0){
        $html .= <<<EOD
        <tr nobr="true">
            <td colspan="3" style="border:1px solid #aaaaaa;" bgcolor="#CCCCCC"> TOTAL</td>
            <td style="border:1px solid #aaaaaa;text-align: center;"bgcolor="#CCCCCC">$total_all</td>
        </tr>
EOD;
    }
$html .= <<<EOD
</table>
EOD;

$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

        
        
        $string = $pdf->Output($filename,'I');
        ob_end_flush(); 
?>