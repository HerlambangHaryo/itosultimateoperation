<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=pre_berthing.xls");
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
<title>Untitled Document</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>
<body>
<table>
</table>
<table>
	<tr><td colspan='9' style='text-align: center; vertical-align:middle'>REPORT</td></tr>
	<tr><td colspan='9' style='text-align: center; vertical-align:middle'>PRE BERTHING</td></tr>
</table>
<table>
</table>
<table border="1" cellspacing="0" cellpadding="0">
	<tr bgcolor="#CCCCCC">
		<td style='text-align: center; vertical-align:middle'>
			NAMA KAPAL
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			VOY
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			BOOKING (TEUS)
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			APPROVED (TEUS)
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			READINESS (TEUS)
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			BOOKED (TEUS)
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			%
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			ETB
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			ETD
		</td>	
	</tr>	
	<?
	foreach ($data_detail as $row) {
		echo "<tr style='text-align: center; vertical-align:middle;mso-number-format:\"\@\"'>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["VESSEL_NAME"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["VOY"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["BOOKING_TEUS"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["APPROVED_TEUS"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["READINESS_TEUS"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["BOOKED_TEUS"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . round(($row["READINESS_TEUS"]/$row["BOOKING_TEUS"])*100,2) . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["ETB"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["ETD"] . "</td>" . "</tr>";
	}
	?>
</table>
</body>