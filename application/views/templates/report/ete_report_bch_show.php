<?php

$tbl="
<style>
@media print {
    table#$tab_id * {
        -webkit-print-color-adjust: exact; 
    }
    table#$tab_id {
        width: calc(100% - 20px)!important; 
    }
	@page { size: auto;  margin: 5mm 0mm; }
}
</style>
<table id='$tab_id' cellpadding='2' cellspacing='2' align='center' style='border: 1px solid #349be5!important;
    border-collapse: collapse;
    width: calc(100% - 80px);
    max-width: 800px;'>
    <tr><td style='border: 1px solid #349be5!important;padding: 10px;background:#369de7;color:white;' colspan='3' align='center'>REPORT</td></tr>
	<tr><td style='border: 1px solid #349be5!important;padding: 10px;'colspan='3' align='center' >VESSEL OPERATION SUMMARY REPORT</td></tr>
	<tr><td style='border: 1px solid #349be5!important;padding: 10px;'colspan='3' align='center' >".date('d/m/Y H:i')."</td></tr>
	
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;vertical-align:middle;'class='abu-terang' >Vessel & Voyage</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;vertical-align:middle;'colspan='2' class='abu-terang'><b>".$data_header->VESSEL_NAME."</b></td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;vertical-align:middle;' class='abu-terang'>Current date & Time</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;vertical-align:middle;'colspan='2'  colspan='2'><b>".date('d/m/Y H:i')."</b></td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' style='vertical-align:middle;'>KADE</td>
        <td colspan='2' class='abu-terang' style='border: 1px solid #349be5!important;padding: 10px;vertical-align:middle;' colspan='2'><b>".$data_header->KADE_NAME."</b></td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;' colspan='3'></td>
    </tr>";


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
$tbl.="
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Activity</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Date</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Time</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Berthing (first line)</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>$bflsatu</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>$bfldua</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;' colspan='3'></td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Commenced Discharge</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>$cdsatu</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>$cddua</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Complete Discharge</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>$cdtiga</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>$cdempat</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;' colspan='3'></td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Commenced Loading</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>$clsatu</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>$cldua</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Complete Loading</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>$cltiga</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>$clempat</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;' colspan='3'></td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>ATD</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>$atdsatu</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>$atddua</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;' colspan='3'></td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;' colspan='3'></td>
    </tr>";
$dsc_completedload_completed=$data_detail['dsc_completed']+$data_detail['load_completed'];
$tbl.="
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' bgcolor='#CCCCCC'>Crane total moves:</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>$dsc_completedload_completed</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Box</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' bgcolor='#CCCCCC'>Discharge</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>".$data_detail['dsc_total']."</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Box</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' bgcolor='#CCCCCC'>Discharged</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>".$data_detail['dsc_completed']."</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Box</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' bgcolor='#CCCCCC'>Loading</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>".$data_detail['load_total']."</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Box</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' bgcolor='#CCCCCC'>Loaded</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>".$data_detail['load_completed']."</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Box</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' bgcolor='#CCCCCC'>Remaining Discharge</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>".$data_detail['dsc_remained']."</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Box</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' bgcolor='#CCCCCC'>Remaining Loading</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>".$data_detail['load_remained']."</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Box</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;' colspan='3'></td>
    </tr>
    <tr>
        <td colspan='3' style='border: 1px solid #349be5!important;padding: 10px;background:#369de7;color:white;text-align: center !important;'>Equipment Deployment</td>
    </tr>
	";
    foreach ($equip_deploy as $equip){
		$tbl.="
			<tr>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>".$equip['MCH_SUB_TYPE']."</td>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>".$equip['TOTAL']."</td>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>Unit</td>
			</tr>";
    }
$tbl.="
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;' colspan='3'></td>
    </tr>";

function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}
$jml = 0;
$ave = 0;
foreach ($data_mch as $key => $value){
    $bgColor = random_color();
	 $commence_work = isset($value['COMMENCE_WORK']) ?  date('d/m/Y H:i',strtotime($value['COMMENCE_WORK'])) : '-';
	 $current_work = isset($value['CURRENT_WORK']) ?  date('d/m/Y H:i',strtotime($value['CURRENT_WORK'])) : '-';


	 $a = strtotime($value['COMMENCE_WORK']);
	 $b = strtotime($value['CURRENT_WORK']);
	$tbl.="
	
		<tr>
				<td colspan='3' style='border: 1px solid #349be5!important;padding: 10px;background:#369de7;color:white;text-align: center !important;'>".$value['MCH_NAME']."</td>
		</tr>
		<tr>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>Commence Work</td>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>".$commence_work."</td>
				<td style='border: 1px solid #349be5!important;padding: 10px;'></td>
		</tr>
		<tr>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>Complete / Current Work</td>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>".$current_work."</td>
				<td style='border: 1px solid #349be5!important;padding: 10px;'></td>
		</tr>
		 <tr>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>Moves</td>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>". $value['MOVES'] ."</td>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>Box</td>
		</tr>
		 <tr>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>Remain</td>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>". $value['REMAIN'] ."</td>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>Box</td>
		</tr>
		<tr>";
		
		$bch = ROUND(($value['MOVES']/(($b - $a)/3600)),0);
		$tbl.="<td style='border: 1px solid #349be5!important;padding: 10px;'>".$bch."</td>
				<td style='border: 1px solid #349be5!important;padding: 10px;'>Box/Hour</td>
				<td style='border: 1px solid #349be5!important;padding: 10px;'></td>
		</tr>";
			$jml = $jml + 1;
			$ave = $ave + $bch;
}
$tbl.="
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;' colspan='3'></td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;text-align: center !important;'class='abu-terang' bgcolor='#CCCCCC'>BCH Average</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'colspan='2'>".round($ave/$jml)."</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;' colspan='3'></td>
    </tr>";
	$work_commence = isset($data_summary['COMMENCE_WORK']) ?  date('d/m/Y H:i',strtotime($data_summary['COMMENCE_WORK'])) : '-';
	$work_complete = isset($data_summary['CURRENT_WORK']) ?  date('d/m/Y H:i',strtotime($data_summary['CURRENT_WORK'])) : '-';
	$totaldl=$data_detail['dsc_completed']+$data_detail['load_completed'];
	$bsh=ROUND(($data_detail['dsc_completed']+$data_detail['load_completed'])/$data_summary['WORKING_TIME'],0);
    $tbl.="
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' bgcolor='#CCCCCC' >Work Commence</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>".$work_commence."</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'></td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang'  bgcolor='#CCCCCC'>Work Complete</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>".$work_complete."</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'></td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' bgcolor='#CCCCCC' >Working Time</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>". $data_summary['WORKING_TIME'] ."</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Hour</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' bgcolor='#CCCCCC' >Disch</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>".$data_detail['dsc_completed']."</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Box</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' bgcolor='#CCCCCC' >Load</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>".$data_detail['load_completed']."</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Box</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' bgcolor='#CCCCCC' >Total</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>".$totaldl."</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Box</td>
    </tr>
    <tr>
        <td style='border: 1px solid #349be5!important;padding: 10px;'class='abu-terang' bgcolor='#CCCCCC'>BSH</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>".$bsh."</td>
        <td style='border: 1px solid #349be5!important;padding: 10px;'>Box</td>
    </tr>
</table>
<br/>
<br/>
<br/>";
echo"$tbl";
?>