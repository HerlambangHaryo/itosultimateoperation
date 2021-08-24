<?php
// echo "<pre>";
// var_dump($data_detail['data']);die();
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Job_list_".date('d-m-Y').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	//$db = getDB('billing');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<style>
.Middle{
}
.CenterAndMiddle{
	text-align: center;
	vertical-align:middle;
}
</style>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- TemplateBeginEditable name="doctitle" -->
<title>Job List - Gate Job Manager</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>
<body>
<table>
</table>
<table>
	<tr><td colspan='17' style='text-align: center; vertical-align:middle'>REPORT</td></tr>
	<tr><td colspan='17' style='text-align: center; vertical-align:middle'>YARD JOB MANAGER - JOB LIST</td></tr>
	<tr><td colspan='17' style='text-align: center; vertical-align:middle'><?php echo date("Y-m-d H:i:s");?></td></tr>
</table>
<table>
</table>
<table border="1" cellspacing="0" cellpadding="0">
	<tr bgcolor="#CCCCCC">
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			No Container
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			ISO
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Size
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Job
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			PA
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Yard
		</td>		
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			YC
		</td>		
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			QC
		</td>			
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Vessel
		</td>		
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			POD
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Waiting Time
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Class
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			ESY
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Seq No
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Queue
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			ITV
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			OPR
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Comm.
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Type
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			WGT (Ton)
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Stowage
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Handling
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Status
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Complete Date
		</td>
	</tr>
	<tr style='text-align: center; vertical-align:middle;mso-number-format:\"\@\"'></tr>
	<?
	foreach ($data_detail['data'] as $row=>$key) {

		echo "<tr style='text-align: center; vertical-align:middle;mso-number-format:\"\@\"'>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["NO_CONTAINER"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ID_ISO_CODE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["CONT_SIZE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["JOB"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["PA_POS"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["YARD_POS"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["EQ"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["QCPLAN"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ID_VES_VOYAGE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ID_POD"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["WAITING_TIME"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ID_CLASS_CODE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ITT_FLAG"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["SEQ_NO"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["QUEUE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ITV"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ID_OPERATOR"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ID_COMMODITY"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["CONT_TYPE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . number_format($key["WEIGHT"],1) . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["STOWAGE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ID_SPEC_HAND"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["STATUS_FLAG"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["MIN"] . "</td>" . "</tr>";
	}

	?> 
</table>
</body>