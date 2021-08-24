<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=vor_reports.xls");
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
<title>Report VOR</title>
</head>
<body>

<table cellpadding="2" cellspacing="2" style="border:0px !important">
    <tr><td colspan='10' bgcolor="#CCCCCC" style='text-align: center; vertical-align:middle; border:0px !important;'>VESSEL OPERATION REPORT</td></tr>
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
        <td style='vertical-align:middle;'>Vessel</td>
        <td style='vertical-align:middle;'><b><?=$vesvoy->ID_VESSEL?> : <?=$vesvoy->VESSEL?></b></td>
        <td style='vertical-align:middle;'><b><?=date('Y')?></b></td>
    </tr>
    <tr>
        <td style='vertical-align:middle;'>Voyage</td>
        <td class="abu-terang" style='vertical-align:middle;'><b><?=$vesvoy->VOYAGE?></b></td>
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
        <td style='vertical-align:middle;'>Vessel Name</td>
        <td style='vertical-align:middle;'><b><?=$vesvoy->VESSEL?></b></td>
        <td style='vertical-align:middle;'></td>
        <td style='vertical-align:middle;'>Arrival Date/Time</td>
        <td style='vertical-align:middle;'><b><?=$vesvoy->ATA?></b></td>
    </tr>
    <tr>
        <td style='vertical-align:middle;'>Voyage Code</td>
        <td style='vertical-align:middle;'><b><?=$vesvoy->VESSEL_CODE?></b></td>
        <td style='vertical-align:middle;'></td>
        <td style='vertical-align:middle;'>Berth Date/Time</td>
        <td style='vertical-align:middle;'><b><?=$vesvoy->ATB?></b></td>
    </tr>
    <tr>
        <td style='vertical-align:middle;'>Voyage LOA</td>
        <td style='vertical-align:middle;'><b><?=$vesvoy->LENGTH?> M</b></td>
        <td style='vertical-align:middle;'></td>
        <td style='vertical-align:middle;'>Departure Date/Time</td>
        <td style='vertical-align:middle;'><b><?=$vesvoy->ATD?></b></td>
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
    <tr style='vertical-align:middle;' bgcolor="#FFFF00">
       <th rowspan="2">Crane No</th>
       <th rowspan="2">Commence Operation</th>
       <th rowspan="2">Complete Operation</th>
       <th colspan="2">Crane Work Time(Hour)</th>
       <th colspan="4">Total (Box)</th>
       <th colspan="2">Crane Rate (Box)</th>
    </tr>
    <tr style='vertical-align:middle;' bgcolor="#FFFF00">
        <th>Gross</th>
        <th>Net</th>
        <th>Discharge</th>
        <th>Load</th>
        <th>Move</th>
        <th>Hatch Covers</th>
        <th>Gross</th>
        <th>Net</th>
    </tr>
    <?php
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
                $gross_h = ROUND(((strtotime($key['COMPLETE_OPERATION'])-strtotime($key['COMMENCE_OPERATION']))/3600),1);
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
            $net = ROUND($gross_h - ($st/60),1);

            $net_rate = ($move + $key['TOTAL_HATCH'])/$net;
            //echo $net." | ".$net_rate."<br />";
            $total_net = $total_net + $net;
            $total_net_rate = $total_net_rate + $net_rate;

            //echo $net_rate." | ".$gross_rate."<br />";
    ?>
        <tr>
            <td><?php echo $key['MCH_NAME']; ?></td>
            <td><?php echo $key['COMMENCE_OPERATION']; ?></td> <!-- A -->
            <td><?php echo $key['COMPLETE_OPERATION']; ?></td> <!-- B -->
            <td><?php echo $gross_h; ?></td> <!-- C -->
            <td><?php echo $net; ?></td> <!-- D -->
            <td><?php echo $key['COMPLETE_DISC']; ?></td> <!-- E -->
            <td><?php echo $key['COMPLETE_LOAD']; ?></td> <!-- F -->
            <td><?php echo $move; ?></td> <!-- G -->
            <td><?php echo $key['TOTAL_HATCH']; ?></td> <!-- H -->
            <td><?php echo round($gross_rate,1); ?></td> <!-- I -->
            <td><?php echo round($net_rate,1); ?></td> <!-- J -->
        </tr>
    <?php $i++; } ?>
    <tr bgcolor="#CCCCCC">
        <td colspan="3" class="abu-terang">Total Work</td>
        <td class="abu-terang"><?php echo round($total_gross,1); ?></td>
        <td class="abu-terang"><?php echo round($total_net,1); ?></td>
        <td class="abu-terang"><?php echo $total_discharge; ?></td>
        <td class="abu-terang"><?php echo $total_loading; ?></td>
        <td class="abu-terang"><?php echo $total_move; ?></td>
        <td class="abu-terang"><?php echo $total_hatch; ?></td>
        <td class="abu-terang"><?php echo round($total_gross_rate,1); ?></td>
        <td class="abu-terang"><?php echo round($total_net_rate,1); ?></td>
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

<table cellpadding="2" cellspacing="2" border="2">
    <tr>
        <td>Operation</td>
        <td><?php echo max($arr)." H"; ?></td>
    </tr>
    <tr>
        <td>Berthing</td>
        <?php 
            if($vesvoy->ATD == NULL || $vesvoy->ATB == NULL){
                $berthing = 0;
            }else{
                $atd = strtotime($vesvoy->ATD);
                $atb = strtotime($vesvoy->ATB);
                $berthing = round((($atd - $atb)/3600),1);
            }
        ?>
        <td><?php echo $berthing." H"; ?></td>
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
    <tr><td colspan="10" bgcolor="#CCCCCC" style='text-align: center; vertical-align:middle; border:0px !important;'>Suspend Detail</td></tr>
    <tr style='vertical-align:middle;' bgcolor="#FFFF00">
       <th>No</th>
       <th>Machine</th>
       <th>Suspend Code</th>
       <th>Suspend Description</th>
       <th>Start Date</th>
       <th>Start Time</th>
       <th>End Date</th>
       <th>End Time</th>
       <th>Outage Min(s)</th>
       <th>Remark</th>
    </tr>
    <?php 
        $total_diff=0;
        $no=1;
        foreach($suspend_detail as $key2){ 
            $total_diff = $total_diff + $key2['OUTAGE'];
    ?>
    <tr>
        <td><?php echo $no; ?></td>
        <td><?php echo $key2['MCH_NAME']; ?></td>
        <td><?php echo $key2['ID_SUSPEND']; ?></td>
        <td><?php echo $key2['ACTIVITY']; ?></td>
        <td><?php echo $key2['START_DATE']; ?></td>
        <td><?php echo $key2['START_TIME']; ?></td>
        <td><?php echo $key2['END_DATE']; ?></td>
        <td><?php echo $key2['END_TIME']; ?></td>
        <td><?php echo $key2['OUTAGE']; ?></td>
        <td></td>
    </tr>
    <?php $no++; } ?>
    <?php if($total_diff != 0){ ?>
    <tr>
        <td colspan="8" bgcolor="#CCCCCC">TOTAL</td>
        <td bgcolor="#CCCCCC"><?php echo $total_diff; ?></td>
        <td></td>
    </tr>
    <?php } ?>
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
    <tr><td colspan="4" bgcolor="#CCCCCC" style='text-align: center; vertical-align:middle; border:0px !important;'>Suspend Summary</td></tr>
    <tr style='vertical-align:middle;' bgcolor="#FFFF00">
       <th>Machine</th>
       <th>Suspend Code</th>
       <th>Suspend Description</th>
       <th>Outage Min(s)</th>
    </tr>
    <?php
        $total_all=0;
        foreach($suspend_summary as $value){
            $total_diff = 0;
            foreach($value['suspend'] as $val){ 
                $total_diff = $total_diff +  $val['DIFF_MINUTES']; ?>
                <tr>
                    <td><?php echo $val['MCH_NAME']; ?></td>
                    <td><?php echo $val['ID_SUSPEND']; ?></td>
                    <td><?php echo $val['ACTIVITY']; ?></td>
                    <td><?php echo $val['DIFF_MINUTES']; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="3" bgcolor="#CCCCCC">SUB TOTAL</td>
                <td bgcolor="#CCCCCC"><?php echo $total_diff; ?></td>
            </tr>
            <?php
                $total_all = $total_all + $total_diff; 
        } ?>
        <?php if($total_all != 0){ ?>
        <tr>
            <td colspan="3" bgcolor="#CCCCCC">TOTAL</td>
            <td bgcolor="#CCCCCC"><?php echo $total_all; ?></td>
        </tr>
    <?php } ?>
</table>

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