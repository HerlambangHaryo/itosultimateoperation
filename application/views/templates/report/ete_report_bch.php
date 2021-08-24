<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=vos_reports.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<style>
.Middle{
}
.CenterAndMiddle{
	text-align: center;
	vertical-align:middle;
}

/*table styling*/
table, td, th {  
  border: 1px solid #ddd;
  text-align: left;
}

table {
  border-collapse: collapse;
  /*width: 100%;*/
}

th, td {
  padding: 7px;
}

.spacer{
	padding-bottom: 30px;
}

.abu-abu
{
	background-color: #303030 !important;
	color: #ffffff !important;
}

.abu-terang
{
	background-color: #aaaaaa !important;
	font-weight: bold !important;
}

</style>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- TemplateBeginEditable name="doctitle" -->
<title>Report BCH</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>
<body>



<table style="border:0px !important">
    <tr><td colspan='3' bgcolor="#CCCCCC" style='text-align: center; vertical-align:middle; border:0px !important;'>REPORT</td></tr>
    <tr><td colspan='3' bgcolor="#CCCCCC" style='text-align: center; vertical-align:middle; border:0px !important;'>VESSEL OPERATION SUMMARY REPORT</td></tr>
    <tr><td colspan='3' bgcolor="#CCCCCC" style='text-align: center; vertical-align:middle; border:0px !important;'><?php echo date("d/m/Y-d H:i");?></td></tr>
</table>

<table cellpadding="2" cellspacing="2" border="1">
    <tr>
        <td class="abu-terang" style='vertical-align:middle;'>Vessel & Voyage</td>
        <td class="abu-terang" style='vertical-align:middle;' colspan="2"><b><?=$data_header->VESSEL_NAME?></b></td>
    </tr>
    <tr>
        <td class="abu-terang" style='vertical-align:middle;'>Current date & Time</td>
        <td class="abu-terang" style='vertical-align:middle;'  colspan="2"><b><?=date('d/m/Y H:i')?></b></td>
    </tr>
    <tr>
        <td class="abu-terang" style='vertical-align:middle;'>KADE</td>
        <td class="abu-terang" style='vertical-align:middle;' colspan="2"><b><?=$data_header->KADE_NAME?></b></td>
        <!--<td class="abu-terang" style='vertical-align:middle;' colspan="2"><b><?=""#$data_header->KADE_NAME?></b></td>-->
    </tr>
</table>

<div class="spacer">
<?php for ($i=0; $i <=1 ; $i++) { ?>
    <table cellpadding="2" cellspacing="2" style="border:0px !important;">
        <tr>
            <td style="border:0px !important;" >&nbsp;</td>
        </tr>
    </table>
<?php } ?>	
</div>

<?php

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

?>

<table cellpadding="2" cellspacing="2" border="1">
    <tr>
        <th class="abu-terang" bgcolor="#CCCCCC" style="text-align: center !important;">Activity</th>
        <th class="abu-terang" bgcolor="#CCCCCC" style="text-align: center !important;">Date</th>
        <th class="abu-terang" bgcolor="#CCCCCC" style="text-align: center !important;">Time</th>
    </tr>
    <tr>
        <td>Berthing (first line)</td>
        <td><?=(empty($berthing_date)) ? '-' : $berthing_date?></td>
        <td><?=(empty($berthing_time)) ? '-' : $berthing_time?></td>
    </tr>
    <tr>
       <td colspan="3">&nbsp;</td>
    </tr> 
    <tr>
        <td>Commenced Discharge</td>
        <td><?=(empty($commence_ddate)) ? '-' : $commence_ddate?></td>
        <td><?=(empty($commence_dtime)) ? '-' : $commence_dtime?></td>
    </tr>
    <tr>
        <td>Complete Discharge</td>
        <td><?=(empty($complete_ddate)) ? '-' : $complete_ddate?></td>
        <td><?=(empty($complete_dtime)) ? '-' : $complete_dtime?></td>
    </tr>
     <tr>
        <td colspan="3">&nbsp;</td>
    </tr> 
    <tr>
        <td>Commenced Loading</td>
        <td><?=(empty($commence_ldate)) ? '-' : $commence_ldate?></td>
        <td><?=(empty($commence_ltime)) ? '-' : $commence_ltime?></td>
    </tr>
    <tr>
        <td>Complete Loading</td>
        <td><?=(empty($complete_ldate)) ? '-' : $complete_ldate?></td>
        <td><?=(empty($complete_ltime)) ? '-' : $complete_ltime?></td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr> 
    <tr>
        <td>ATD</td>
        <td><?=(empty($atd_date)) ? '-' : $atd_date?></td>
        <td><?=(empty($atd_time)) ? '-' : $atd_time?></td>
    </tr>
</table>

<div class="spacer">
	<?php for ($i=0; $i <=1 ; $i++) { ?>
		<table cellpadding="2" cellspacing="2" style="border:0px !important;">
		<tr>
                    <td style="border:0px !important;" >&nbsp;</td>
		</tr>
	</table>
	<?php } ?>
</div>

<table cellpadding="2" cellspacing="2" border="1">
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC">Crane total moves:</td>
        <td><?=$data_detail['dsc_completed']+$data_detail['load_completed']?></td>
        <td>Box</td>
    </tr>
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC">Discharge</td>
        <td><?=$data_detail['dsc_total']?></td>
        <td>Box</td>
    </tr>
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC">Discharged</td>
        <td><?=$data_detail['dsc_completed']?></td>
        <td>Box</td>
    </tr>
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC">Loading</td>
        <td><?=$data_detail['load_total']?></td>
        <td>Box</td>
    </tr>
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC">Loaded</td>
        <td><?=$data_detail['load_completed']?></td>
        <td>Box</td>
    </tr>
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC">Remaining Discharge</td>
        <td><?=$data_detail['dsc_remained']?></td>
        <td>Box</td>
    </tr>
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC">Remaining Loading</td>
        <td><?=$data_detail['load_remained']?></td>
        <td>Box</td>
    </tr>
</table>

<div class="spacer">
<?php for ($i=0; $i <=1 ; $i++) { ?>
<table cellpadding="2" cellspacing="2" style="border:0px !important;">
    <tr>
        <td style="border:0px !important;" >&nbsp;</td>
    </tr>
</table>
<?php } ?>	
</div>

<table cellpadding="2" cellspacing="2" border="1">
    <tr>
        <td colspan="3" class="abu-terang" bgcolor="#CCCCCC" style="text-align: center !important;">Equipment Deployment</td>
    </tr>
<?php
    foreach ($equip_deploy as $equip){
?>
    <tr>
        <td><?=$equip['MCH_SUB_TYPE']?></td>
        <td ><?=$equip['TOTAL']?></td>
        <td >Unit</td>
    </tr>
    
<?php
    }
?>
</table>

<div class="spacer">
<?php for ($i=0; $i <=1 ; $i++) { ?>
<table cellpadding="2" cellspacing="2" style="border:0px !important;">
    <tr>
        <td style="border:0px !important;" >&nbsp;</td>
    </tr>
</table>
<?php } ?>	
</div>

<?php // debux($data_mch); ?>

<?php

function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}
$jml = 0;
$ave = 0;
foreach ($data_mch as $key => $value): 
    $bgColor = random_color();
?>
<div class="spacer"></div>
<table cellpadding="2" cellspacing="2" border="1">
	<tr>
            <td colspan="3" bgcolor="<?= $bgColor; ?>" style="text-align: center !important;"><?=$value['MCH_NAME']?></td>
	</tr>
    <?php
         $commence_work = isset($value['COMMENCE_WORK']) ?  date('d/m/Y H:i',strtotime($value['COMMENCE_WORK'])) : '-';
         $current_work = isset($value['CURRENT_WORK']) ?  date('d/m/Y H:i',strtotime($value['CURRENT_WORK'])) : '-';


         $a = strtotime($value['COMMENCE_WORK']);
         $b = strtotime($value['CURRENT_WORK']);
    ?>
	<tr>
            <td>Commence Work</td>
            <td><?=$commence_work?></td>
            <td></td>
	</tr>
	<tr>
            <td>Complete / Current Work</td>
            <td colspan="1" ><?=$current_work?></td>
            <td></td>
	</tr>
	 <tr>
            <td>Moves</td>
            <td><?= $value['MOVES'] ?></td>
            <td>Box</td>
	</tr>
	 <tr>
            <td>Remain</td>
            <td><?= $value['REMAIN'] ?></td>
            <td>Box</td>
	</tr>
	<tr>
           <?php $bch = ROUND(($value['MOVES']/(($b - $a)/3600)),0); ?>
             <!-- <td><?php echo $value['MOVES']/(($value['CURRENT_WORK']-$value['COMMENCE_WORK'])*24); ?></td> -->
            <td><?php echo $bch; ?></td>
            <!-- <td><?php echo $a." ".$b; ?></td> -->
            <td>Box/Hour</td>
	</tr>
    <?php
        $jml = $jml + 1;
        $ave = $ave + $bch;
    ?>
</table>
<!-- <div class="spacer"></div> -->
<?php 
    endforeach; 
?>

<div class="spacer">
<?php for ($i=0; $i <=1 ; $i++) { ?>
<table cellpadding="2" cellspacing="2" style="border:0px !important;">
    <tr>
        <td style="border:0px !important;" >&nbsp;</td>
    </tr>
</table>
<?php } ?>	
</div>

<table cellpadding="2" cellspacing="2" border="1">
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC" style="text-align: center !important;">BCH Average</td>
        <!--<td colspan="2"><?=""#($total_bch/count($data_mch))?></td>-->

        <td colspan="2"><?php echo round($ave/$jml); ?></td>
    </tr>
</table>

<div class="spacer">
<?php for ($i=0; $i <=1 ; $i++) { ?>
<table cellpadding="2" cellspacing="2" style="border:0px !important;">
    <tr>
        <td style="border:0px !important;" >&nbsp;</td>
    </tr>
</table>
<?php } ?>	
</div>

<table cellpadding="2" cellspacing="2" border="1">
    <?php
        $work_commence = isset($data_summary['COMMENCE_WORK']) ?  date('d/m/Y H:i',strtotime($data_summary['COMMENCE_WORK'])) : '-';
        $work_complete = isset($data_summary['CURRENT_WORK']) ?  date('d/m/Y H:i',strtotime($data_summary['CURRENT_WORK'])) : '-';
    ?>
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC" >Work Commence</td>
        <td><?=$work_commence?></td>
        <td></td>
    </tr>
    <tr>
        <td class="abu-terang"  bgcolor="#CCCCCC">Work Complete</td>
        <td ><?=$work_complete?></td>
        <td></td>
    </tr>
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC" >Working Time</td>
        <td><?= $data_summary['WORKING_TIME'] ?></td>
        <td>Hour</td>
    </tr>
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC" >Disch</td>
        <td><?=$data_detail['dsc_completed']?></td>
        <td>Box</td>
    </tr>
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC" >Load</td>
        <td><?=$data_detail['load_completed']?></td>
        <td>Box</td>
    </tr>
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC" >Total</td>
        <td><?=$data_detail['dsc_completed']+$data_detail['load_completed']?></td>
        <td>Box</td>
    </tr>
    <tr>
        <td class="abu-terang" bgcolor="#CCCCCC">BSH</td>
        <td><?=ROUND(($data_detail['dsc_completed']+$data_detail['load_completed'])/$data_summary['WORKING_TIME'],0)?></td>
        <td>Box</td>
    </tr>
</table>

<div class="spacer">
<?php for ($i=0; $i <=1 ; $i++) { ?>
<table cellpadding="2" cellspacing="2" style="border:0px !important;">
    <tr>
        <td style="border:0px !important;" >&nbsp;</td>
    </tr>
</table>
<?php } ?>	
</div>
<!--<table cellpadding="2" cellspacing="2" style="border:0px !important;">
    <tr>
        <td colspan="3" style="border:0px !important;" ><b>NOTES</b></td>
    </tr>
    <tr>
        <td colspan="3" style="border:0px !important;"  ><b>* 23.15 - 23.32 : QC 01 Waiting Container (1 RS Serve 2 QC). </b></td>
    </tr>
    <tr>
        <td colspan="3" style="border:0px !important;"  ><b>* 22.45 - 23.06 : QC 02 Waiting Container (Change Operator At Yard). </b></td>
    </tr>
</table>-->
<div class="spacer">
<?php for ($i=0; $i <=1 ; $i++) { ?>
<table cellpadding="2" cellspacing="2" style="border:0px !important;">
    <tr>
        <td style="border:0px !important;" >&nbsp;</td>
    </tr>
</table>
<?php } ?>	
</div>


</body>
</html>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
    $('.spacer').html('<table cellpadding="2" cellspacing="2" style="border:0px !important;">'+
    				'<tr>'+
    					'<td style="border:0px !important;" >&nbsp;</td>'+
    				'</tr>'+
    				'</table>');
});
</script>