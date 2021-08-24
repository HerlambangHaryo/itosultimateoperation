
<?php require_once('tcpdf/config/lang/eng.php');
    	ob_start();
		$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $filename = "report_vos_$data_header->VESSEL_NAME.pdf";
        $pdf->SetAuthor('ILCS');
		$pdf->SetTitle($filename);
        $pdf->AddPage('L', 'A4');
        $pdf->SetFont('times','',12);
        $year = date('Y');
		

$tbl='
<table cellpadding="2" align="center" style="border-collapse: collapse;">
	<tr ><td style="color:#FFFFFF;" border="1" bordercolor="#369de7"  bgcolor="#369de7" colspan="3" align="center">REPORT</td></tr>
	<tr ><td border="1" bordercolor="#369de7" colspan="3" align="center">VESSEL OPERATION SUMMARY REPORT</td></tr>
	<tr ><td border="1" bordercolor="#369de7" colspan="3" align="center" >'.date("d/m/Y H:i").'</td></tr>
    <tr >
        <td border="1" bordercolor="#369de7"  >Vessel & Voyage</td>
        <td border="1" bordercolor="#369de7" colspan="2" ><b>'.$data_header->VESSEL_NAME.'</b></td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" >Current date & Time</td>
        <td border="1" bordercolor="#369de7" colspan="2"  colspan="2"><b>'.date("d/m/Y H:i").'</b></td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" style="">KADE</td>
        <td colspan="2"  border="1" bordercolor="#369de7" colspan="2"><b>'.$data_header->KADE_NAME.'</b></td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" colspan="3"></td>
    </tr>';


$berthing_date = isset($data_header->BERTHING_DATE) ?  date('d/m/Y',strtotime($data_header->BERTHING_DATE)) : '-';
$berthing_time = isset($data_header->BERTHING_TIME) ?  date('H:i', strtotime($data_header->BERTHING_TIME)) : '-';

$commence_ddate = isset($data_header->COMMENCE_DISCHARGE_DATE) ?  date('d/m/Y',strtotime($data_header->COMMENCE_DISCHARGE_DATE)) : '-';
$commence_dtime    = isset($data_header->COMMENCE_DISCHARGE_TIME) ?  date('H:i', strtotime($data_header->COMMENCE_DISCHARGE_TIME)) : '-'  ;

$complete_ddate = isset($data_header->COMPLETE_DISCHARGE_DATE) ?  date('d/m/Y',strtotime($data_header->COMPLETE_DISCHARGE_DATE)) : '-';
$complete_dtime = isset($data_header->COMPLETE_DISCHARGE_TIME) ?  date('H:i', strtotime($data_header->COMPLETE_DISCHARGE_TIME)) : '-';

$commence_ldate = isset($data_header->COMMENCE_LOAD_DATE) ?  date('d/m/Y',strtotime($data_header->COMMENCE_LOAD_DATE)) : '-';
$commence_ltime    = isset($data_header->COMMENCE_LOAD_TIME) ?  date('H:i', strtotime($data_header->COMMENCE_LOAD_TIME)) : '-'  ;

$complete_ldate = isset($data_header->COMPLETE_LOAD_DATE) ?  date('d/m/Y',strtotime($data_header->COMPLETE_LOAD_DATE)) : '-';
$complete_ltime = isset($data_header->COMPLETE_LOAD_TIME) ?  date('H:i', strtotime($data_header->COMPLETE_LOAD_TIME)) : '-';

$atd_date      = isset($data_header->ATD_DATE) ?  date('d/m/Y',strtotime($data_header->ATD_DATE)) : '-';
$atd_time   = isset($data_header->ATD_TIME) ?  date('H:i', strtotime($data_header->ATD_TIME)) : '-';
	
	$bflsatu=(empty($berthing_date)) ? '-' : $berthing_date;
	$bfldua=(empty($berthing_time)) ? '-' : $berthing_time;
	$cdsatu=(empty($commence_ddate)) ? '-' : $commence_ddate;
	$cddua=(empty($commence_dtime)) ? '-' : $commence_dtime;
	$cdtiga=(empty($complete_ddate)) ? '-' : $complete_ddate;
	$cdempat=(empty($complete_dtime)) ? '-' : $complete_dtime;
	$clsatu=(empty($commence_ldate)) ? '-' : $commence_ldate;
	$cldua=(empty($commence_ltime)) ? '-' : $commence_ltime;
	$cltiga=  (empty($complete_ldate)) ? '-' : $complete_ldate;
	$clempat=  (empty($complete_ltime)) ? '-' : $complete_ltime;
	$atdsatu=  (empty($atd_date)) ? '-' : $atd_date;
	$atddua=   (empty($atd_time)) ? '-' : $atd_time;
$tbl.='
    <tr >
        <td border="1" bordercolor="#369de7">Activity</td>
        <td border="1" bordercolor="#369de7">Date</td>
        <td border="1" bordercolor="#369de7">Time</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7">Berthing (first line)</td>
        <td border="1" bordercolor="#369de7">'.$bflsatu.'</td>
        <td border="1" bordercolor="#369de7">'.$bfldua.'</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" colspan="3"></td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7">Commenced Discharge</td>
        <td border="1" bordercolor="#369de7">'.$cdsatu.'</td>
        <td border="1" bordercolor="#369de7">'.$cddua.'</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7">Complete Discharge</td>
        <td border="1" bordercolor="#369de7">'.$cdtiga.'</td>
        <td border="1" bordercolor="#369de7">'.$cdempat.'</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" colspan="3"></td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7">Commenced Loading</td>
        <td border="1" bordercolor="#369de7">'.$clsatu.'</td>
        <td border="1" bordercolor="#369de7">'.$cldua.'</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7">Complete Loading</td>
        <td border="1" bordercolor="#369de7">'.$cltiga.'</td>
        <td border="1" bordercolor="#369de7">'.$clempat.'</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" colspan="3"></td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7">ATD</td>
        <td border="1" bordercolor="#369de7">'.$atdsatu.'</td>
        <td border="1" bordercolor="#369de7">'.$atddua.'</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" colspan="3"></td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" colspan="3"></td>
    </tr>';
$dsc_completedload_completed=$data_detail['dsc_completed']+$data_detail['load_completed'];
$tbl.='
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC">Crane total moves:</td>
        <td border="1" bordercolor="#369de7">'.$dsc_completedload_completed.'</td>
        <td border="1" bordercolor="#369de7">Box</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC">Discharge</td>
        <td border="1" bordercolor="#369de7">'.$data_detail["dsc_total"].'</td>
        <td border="1" bordercolor="#369de7">Box</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC">Discharged</td>
        <td border="1" bordercolor="#369de7">'.$data_detail["dsc_completed"].'</td>
        <td border="1" bordercolor="#369de7">Box</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC">Loading</td>
        <td border="1" bordercolor="#369de7">'.$data_detail["load_total"].'</td>
        <td border="1" bordercolor="#369de7">Box</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC">Loaded</td>
        <td border="1" bordercolor="#369de7">'.$data_detail["load_completed"].'</td>
        <td border="1" bordercolor="#369de7">Box</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC">Remaining Discharge</td>
        <td border="1" bordercolor="#369de7">'.$data_detail["dsc_remained"].'</td>
        <td border="1" bordercolor="#369de7">Box</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC">Remaining Loading</td>
        <td border="1" bordercolor="#369de7">'.$data_detail["load_remained"].'</td>
        <td border="1" bordercolor="#369de7">Box</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" colspan="3"></td>
    </tr>
    <tr >
        <td colspan="3" style="color:#FFFFFF" border="1" bordercolor="#369de7"  bgcolor="#369de7">Equipment Deployment</td>
    </tr>
	';
    foreach ($equip_deploy as $equip){
		$tbl.='
			<tr >
				<td border="1" bordercolor="#369de7">'.$equip["MCH_SUB_TYPE"].'</td>
				<td border="1" bordercolor="#369de7">'.$equip["TOTAL"].'</td>
				<td border="1" bordercolor="#369de7">Unit</td>
			</tr>';
    }
        $pdf->Ln();
$tbl.='
    <tr >
        <td border="1" bordercolor="#369de7" colspan="3"></td>
    </tr>';

function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}
$jml = 0;
$ave = 0;
foreach ($data_mch as $key => $value){
    $bgcolor = random_color();
	 $commence_work = isset($value['COMMENCE_WORK']) ?  date('d/m/Y H:i',strtotime($value['COMMENCE_WORK'])) : '-';
	 $current_work = isset($value['CURRENT_WORK']) ?  date('d/m/Y H:i',strtotime($value['CURRENT_WORK'])) : '-';


	 $a = strtotime($value['COMMENCE_WORK']);
	 $b = strtotime($value['CURRENT_WORK']);
	$tbl.='
	
		<tr >
				<td colspan="3" style="color:#FFFFFF" border="1" bordercolor="#369de7"  bgcolor="#369de7">'.$value["MCH_NAME"].'</td>
		</tr>
		<tr >
				<td border="1" bordercolor="#369de7">Commence Work</td>
				<td border="1" bordercolor="#369de7">'.$commence_work.'</td>
				<td border="1" bordercolor="#369de7"></td>
		</tr>
		<tr >
				<td border="1" bordercolor="#369de7">Complete / Current Work</td>
				<td border="1" bordercolor="#369de7">'.$current_work.'</td>
				<td border="1" bordercolor="#369de7"></td>
		</tr>
		 <tr >
				<td border="1" bordercolor="#369de7">Moves</td>
				<td border="1" bordercolor="#369de7">'. $value["MOVES"] .'</td>
				<td border="1" bordercolor="#369de7">Box</td>
		</tr>
		 <tr >
				<td border="1" bordercolor="#369de7">Remain</td>
				<td border="1" bordercolor="#369de7">'. $value["REMAIN"] .'</td>
				<td border="1" bordercolor="#369de7">Box</td>
		</tr>
		<tr >';
		
		$bch = ROUND(($value['MOVES']/(($b - $a)/3600)),0);
		$tbl.='<td border="1" bordercolor="#369de7">'.$bch.'</td>
				<td border="1" bordercolor="#369de7">Box/Hour</td>
				<td border="1" bordercolor="#369de7"></td>
		</tr>';
			$jml = $jml + 1;
			$ave = $ave + $bch;
}
$tbl.='
    <tr >
        <td border="1" bordercolor="#369de7" colspan="3"></td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC">BCH Average</td>
        <td border="1" bordercolor="#369de7"colspan="2">'.round($ave/$jml).'</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" colspan="3"></td>
    </tr>';
	$work_commence = isset($data_summary['COMMENCE_WORK']) ?  date('d/m/Y H:i',strtotime($data_summary['COMMENCE_WORK'])) : '-';
	$work_complete = isset($data_summary['CURRENT_WORK']) ?  date('d/m/Y H:i',strtotime($data_summary['CURRENT_WORK'])) : '-';
	$totaldl=$data_detail['dsc_completed']+$data_detail['load_completed'];
	$bsh=ROUND(($data_detail['dsc_completed']+$data_detail['load_completed'])/$data_summary['WORKING_TIME'],0);
    $tbl.='
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC" >Work Commence</td>
        <td border="1" bordercolor="#369de7">'.$work_commence.'</td>
        <td border="1" bordercolor="#369de7"></td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7"  bgcolor="#CCCCCC">Work Complete</td>
        <td border="1" bordercolor="#369de7">'.$work_complete.'</td>
        <td border="1" bordercolor="#369de7"></td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC" >Working Time</td>
        <td border="1" bordercolor="#369de7">'. $data_summary["WORKING_TIME"] .'</td>
        <td border="1" bordercolor="#369de7">Hour</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC" >Disch</td>
        <td border="1" bordercolor="#369de7">'.$data_detail["dsc_completed"].'</td>
        <td border="1" bordercolor="#369de7">Box</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC" >Load</td>
        <td border="1" bordercolor="#369de7">'.$data_detail["load_completed"].'</td>
        <td border="1" bordercolor="#369de7">Box</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC" >Total</td>
        <td border="1" bordercolor="#369de7">'.$totaldl.'</td>
        <td border="1" bordercolor="#369de7">Box</td>
    </tr>
    <tr >
        <td border="1" bordercolor="#369de7" bgcolor="#CCCCCC">BSH</td>
        <td border="1" bordercolor="#369de7">'.$bsh.'</td>
        <td border="1" bordercolor="#369de7">Box</td>
    </tr>
</table>';
$html= <<<EOD
$tbl
EOD;

$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

        
        
        $string = $pdf->Output($filename,'I');
        ob_end_flush(); 
?>