<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=inbound_report_$id_ves_voyage.xls");
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
	<tr><td colspan='18' style='text-align: center; vertical-align:middle'>REPORT</td></tr>
	<tr><td colspan='18' style='text-align: center; vertical-align:middle'><?=$vesvoy->VESSEL?></td></tr>
	<tr><td colspan='18' style='text-align: center; vertical-align:middle'>INBOUND LIST</td></tr>
	<tr><td colspan='18' style='text-align: center; vertical-align:middle'><?php echo date("Y-m-d H:i:s");?></td></tr>
</table>
<table>
</table>
<table border="1" cellspacing="0" cellpadding="0">
	<tr bgcolor="#CCCCCC">
		<td style='text-align: center; vertical-align:middle'>No.</td>		
		<td style='text-align: center; vertical-align:middle'>No Container</td>		
		<td style='text-align: center; vertical-align:middle'>Stowage</td>
		<td style='text-align: center; vertical-align:middle'>Stowage To</td>
		<td style='text-align: center; vertical-align:middle'>ISO</td>		
		<td style='text-align: center; vertical-align:middle'>Class</td>		
		<td style='text-align: center; vertical-align:middle'>Operator</td>
		<td style='text-align: center; vertical-align:middle'>F/M</td>		
		<td style='text-align: center; vertical-align:middle'>POL</td>	
		<td style='text-align: center; vertical-align:middle'>POD</td>	
		<td style='text-align: center; vertical-align:middle'>POR</td>	
		<td style='text-align: center; vertical-align:middle'>Yard</td>
		<td style='text-align: center; vertical-align:middle'>Complete Discharge</td>
		<td style='text-align: center; vertical-align:middle'>Weight(Ton)</td>	
		<td style='text-align: center; vertical-align:middle'>Temp.(C)</td>	
		<td style='text-align: center; vertical-align:middle'>UNNO</td>		
		<td style='text-align: center; vertical-align:middle'>IMDG</td>	
		<td style='text-align: center; vertical-align:middle'>Comm.</td>	
		<td style='text-align: center; vertical-align:middle'>Size</td>	
		<td style='text-align: center; vertical-align:middle'>Type</td>	
		<td style='text-align: center; vertical-align:middle'>Height</td>
		<td style='text-align: center; vertical-align:middle'>ESY</td>
		<td style='text-align: center; vertical-align:middle'>TL</td>
		<td style='text-align: center; vertical-align:middle'>Placement</td>
		<td style='text-align: center; vertical-align:middle'>OH</td>
		<td style='text-align: center; vertical-align:middle'>OR</td>
		<td style='text-align: center; vertical-align:middle'>OL</td>
		<td style='text-align: center; vertical-align:middle'>OF</td>
		<td style='text-align: center; vertical-align:middle'>OLB</td>
		<td style='text-align: center; vertical-align:middle'>QC Plan</td>
		<td style='text-align: center; vertical-align:middle'>QC Real</td>
		<td style='text-align: center; vertical-align:middle'>YC Plan</td>
		<td style='text-align: center; vertical-align:middle'>YC Real</td>	
	</tr>	
	<?
	$no =1;
	foreach ($result as $row):
		echo "<tr style='text-align: center; vertical-align:middle;mso-number-format:\"\@\"'>";
		echo "<td style='mso-number-format:\"\@\"'>" . $no++ . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["NO_CONTAINER"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["STOWAGE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["STOWAGE_TO"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["ID_ISO_CODE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["ID_CLASS_CODE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["ID_OPERATOR"] . "</td>"; 
		echo "<td style='mso-number-format:\"\@\"'>" . $row["CONT_STATUS"] . "</td>";//
		echo "<td style='mso-number-format:\"\@\"'>" . $row["ID_POL"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["ID_POD"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["ID_POR"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["YARD_POS"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["CONFIRM_DATE_"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["WEIGHT"]. "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["TEMP"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["UNNO"] . "</td>";		
		echo "<td style='mso-number-format:\"\@\"'>" . $row["IMDG"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["ID_COMMODITY"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["CONT_SIZE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["CONT_TYPE"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["CONT_HEIGHT"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["ITT_FLAG"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["TL_FLAG"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["STATUS_PLACEMENT"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["OVER_HEIGHT"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["OVER_RIGHT"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["OVER_LEFT"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["OVER_FRONT"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["OVER_REAR"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["QC_PLAN"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["QC_REAL"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["YC_PLAN"] . "</td>";
		echo "<td style='mso-number-format:\"\@\"'>" . $row["YC_REAL"] . "</td>" . "</tr>";
	endforeach;
	?>
</table>
</body>