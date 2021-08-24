<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=dl_productivity_reports.xls");
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
</style>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Report DL Productivity</title>
</head>
<body>
<table cellpadding="2" cellspacing="2" style="border:0px !important">
    <tr><td colspan='5' bgcolor="#CCCCCC" style='text-align: center; vertical-align:middle; border:0px !important;'>DL PRODUCTIVITY REPORT</td></tr>
</table>
<br />

<p>QUAY CRANE PRODUCTIFITY</p>
<br />

<table cellpadding="2" cellspacing="2" border="1">
    <tr>
        <td style='vertical-align:middle;'>Vessel</td>
        <td style='vertical-align:middle;'><b><?=$vesvoy->ID_VESSEL?> : <?=$vesvoy->VESSEL?></b></td>
        <td style='vertical-align:middle;'><b><?=date('Y')?></b></td>
    </tr>
    <tr>
        <td style='vertical-align:middle;'>Voyage</td>
        <td style='vertical-align:middle;'><b><?=$vesvoy->VOYAGE?></b></td>
    </tr>
</table>

<br />
<br />

<table cellpadding="2" cellspacing="2" border="1">
	<tr>
		<th style="text-align: center; vertical-align:middle;" bgcolor="#CCCCCC" colspan="<?php echo count($crane)?>" align="center">Discharge</th>
		<th style="text-align: center; vertical-align:middle;" bgcolor="#CCCCCC" colspan="<?php echo count($crane)?>" align="center">Loading</th>
	</tr>
	<tr>
		<?php foreach ($crane as $row) { ?>
		<td valign="top">
			<table border=none;>
				<tr>
					<th style='text-align: center; vertical-align:middle;' colspan="3"><?php echo $row['MCH_NAME']; ?></th>
				</tr>
				<tr>
					<th>Commence Operation</th>
					<th>Complete Operation</th>
					<th>Complete</th>
				</tr>
				<tr>
					<td><?php echo $row['START_WORK_DISC']; ?></td>
					<td><?php echo $row['END_WORK_DISC']; ?></td>
					<td><?php echo $row['COMPLETE_DISC']; ?></td> 
				</tr>
			</table>
		</td>
		<?php }?>
		<?php foreach ($crane as $row) { ?>
		<td valign="top">
			<table border=none;>
				<tr>
					<th style='text-align: center; vertical-align:middle;' colspan="3"><?php echo $row['MCH_NAME']; ?></th>
				</tr>
				<tr>
					<th>Commence Operation</th>
					<th>Complete Operation</th>
					<th>Complete</th>
				</tr>
				<tr>
					<td><?php echo $row['START_WORK_LOAD']; ?></td>
					<td><?php echo $row['END_WORK_LOAD']; ?></td>
					<td><?php echo $row['COMPLETE_LOAD']; ?></td> 
				</tr>
			</table>
		</td>
		<?php }?>
	</tr>
</table>

<br />
<br />
<p>QUAY CRANE SUMMARY</p>
<br />

<table cellpadding="2" cellspacing="2" border="1">
	<tr style='text-align: center; vertical-align:middle;'>
	    <th bgcolor="#CCCCCC" colspan="2" rowspan="2">QUAY</th>
	    <th bgcolor="#CCCCCC" colspan="3">DISCHARGE</th>
	    <th bgcolor="#CCCCCC" colspan="3">LOADING</th>
	    <th bgcolor="#CCCCCC" colspan="3">SUB TOTAL</th>
	</tr>
	<tr style='text-align: center; vertical-align:middle;'>
	    <th bgcolor="#CCCCCC">Total</th>
	    <th bgcolor="#CCCCCC">Complete</th>
	    <th bgcolor="#CCCCCC">Remain</th>
	    <th bgcolor="#CCCCCC">Total</th>
	    <th bgcolor="#CCCCCC">Complete</th>
	    <th bgcolor="#CCCCCC">Remain</th>
	    <th bgcolor="#CCCCCC">Total</th>
	    <th bgcolor="#CCCCCC">Complete</th>
	    <th bgcolor="#CCCCCC">Remain</th>
	</tr>
<?php
    $baris = 20;
    foreach ($crane as $row) {
	$baris++;
?>
	<tr style='text-align: center; vertical-align:middle;'>
	    <th bgcolor="#CCCCCC" colspan="2"><?=$row['MCH_NAME']?></th>
	    <td><?=$row['PLANNED_DISC']?></td>
	    <td><?=$row['COMPLETE_DISC']?></td>
	    <td><?=$row['PLANNED_DISC'] - $row['COMPLETE_DISC']?></td>
	    <td><?=$row['PLANNED_LOAD']?></td>
	    <td><?=$row['COMPLETE_LOAD']?></td>
	    <td><?=$row['PLANNED_LOAD'] - $row['COMPLETE_LOAD']?></td>
	    <td>=SUM(C<?=$baris?>+F<?=$baris?>)</td>
	    <td>=SUM(D<?=$baris?>+G<?=$baris?>)</td>
	    <td>=SUM(E<?=$baris?>+H<?=$baris?>)</td>
	</tr>
<?php
    }
?>
	<tr style='text-align: center; vertical-align:middle;'>
	    <th bgcolor="#CCCCCC" colspan="2">TOTAL</th>
	    <td>=SUM(C20:C<?=$baris?>)</td>
	    <td>=SUM(D20:D<?=$baris?>)</td>
	    <td>=SUM(E20:E<?=$baris?>)</td>
	    <td>=SUM(F20:F<?=$baris?>)</td>
	    <td>=SUM(G20:G<?=$baris?>)</td>
	    <td>=SUM(H20:H<?=$baris?>)</td>
	    <td>=SUM(I20:I<?=$baris?>)</td>
	    <td>=SUM(J20:J<?=$baris?>)</td>
	    <td>=SUM(K20:K<?=$baris?>)</td>
	</tr>
</table>

</body>
</html>