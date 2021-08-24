<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=yard_reports.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	date_default_timezone_set("Asia/Jakarta");
	$datetime = date("d-m-Y H:i:s"); 
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
<title>Untitled Document</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>
<body>
<table>
</table>
<table>
	<tr><td colspan='18' style='text-align: center; vertical-align:middle'><?php echo $datetime . ' WIB';?></td></tr>
	<tr><td colspan='18' style='text-align: center; vertical-align:middle'>REPORT</td></tr>
	<tr><td colspan='18' style='text-align: center; vertical-align:middle'>CONTAINER STACKING</td></tr>
</table>
<table>
</table>
<table border="1" cellspacing="0" cellpadding="0">
	<tr bgcolor="#CCCCCC">
		<td style='text-align: center; vertical-align:middle'>
			NO
		</td>
		<td style='text-align: center; vertical-align:middle'>
			NO CONTAINER
		</td>
		<td style='text-align: center; vertical-align:middle'>
			SIZE
		</td>
		<td style='text-align: center; vertical-align:middle'>
			ISO CODE
		</td>
		<td style='text-align: center; vertical-align:middle'>
			POD(E)/POL(I)
		</td>
		<td style='text-align: center; vertical-align:middle'>
			CLASS
		</td>
		<td style='text-align: center; vertical-align:middle'>
			STATUS
		</td>
		<td style='text-align: center; vertical-align:middle'>
			PLACEMENT DATE
		</td>
		<td style='text-align: center; vertical-align:middle'>
			PLACEMENT TIME
		</td>
		<td style='text-align: center; vertical-align:middle'>
			EQUIPMENT
		</td>
		<td style='text-align: center; vertical-align:middle'>
			SHIPPING LINE
		</td>
		<td style='text-align: center; vertical-align:middle'>
			LOCATION
		</td>
		<td style='text-align: center; vertical-align:middle'>
			SLOT
		</td>
		<td style='text-align: center; vertical-align:middle'>
			ROW
		</td>
		<td style='text-align: center; vertical-align:middle'>
			TIER
		</td>
		<td style='text-align: center; vertical-align:middle'>
			VESSEL
		</td>
		<td style='text-align: center; vertical-align:middle'>
			VOY IN
		</td>
		<td style='text-align: center; vertical-align:middle'>
			VOY OUT
		</td>
		<td style='text-align: center; vertical-align:middle'>
			DWELLING TIME
		</td>
	</tr>
	<? $no=1;
	foreach ($data_detail as $row) {
		if($row["CONT_SIZE"]>25){
			$slot = $row["YD_SLOT"] + 1;
		}else{
			$slot = $row["YD_SLOT"];
		}
		echo "<tr style='text-align: center; vertical-align:middle;mso-number-format:\"\@\"'>";
		echo "<td style='mso-number-format:\"\@\"'>" . $no . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["NO_CONTAINER"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["CONT_SIZE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["ID_ISO_CODE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["POD"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["ID_CLASS_CODE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["CONT_STATUS"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["PLACEMENT_DATE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["PLACEMENT_TIME"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["YC_REAL"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["OPERATOR_NAME"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["YD_BLOCK_NAME"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $slot . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["YD_ROW"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["YD_TIER"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["VESSEL_NAME"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["VOY_IN"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["VOY_OUT"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["DWELLING_TIME"] . "</td>" . "</tr>";
	$no++; }
	?>
</table>
</body>
