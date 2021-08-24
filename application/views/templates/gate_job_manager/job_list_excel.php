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
	<tr><td colspan='17' style='text-align: center; vertical-align:middle'>GATE JOB MANAGER - JOB LIST</td></tr>
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
			R/D
		</td>		
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Vessel
		</td>		
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Hazard
		</td>		
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			TL
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Truck
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			WGT(Ton)
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Axle
		</td>	
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Truck In Date
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Truck Out Date
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Class
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			F/M
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			ISO
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			POD
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			OPR
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			YARD
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Payment
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			TRX Number
		</td>
		<td style='text-align: center; vertical-align:middle' rowspan = '2'>
			Paid Thru Date
		</td>
	</tr>
	<tr style='text-align: center; vertical-align:middle;mso-number-format:\"\@\"'></tr>
	<?
	foreach ($data_detail['data'] as $row=>$key) {

		echo "<tr style='text-align: center; vertical-align:middle;mso-number-format:\"\@\"'>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["NO_CONTAINER"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["IO"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ID_VES_VOYAGE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["HAZARD"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["TL_FLAG"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["TID"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . number_format($key["WEIGHT"],1) . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ID_AXEL"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["GTIN_DATE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["GTOUT_DATE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ID_CLASS_CODE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["CONT_STATUS"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ID_ISO_CODE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ID_POD"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["ID_OPERATOR"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["YD_BLOCK_NAME"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["PAYMENT_STATUS"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["TRX_NUMBER"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $key["PAYTHROUGH_DATE"] . "</td>" . "</tr>";
	}

	?>
</table>
</body>