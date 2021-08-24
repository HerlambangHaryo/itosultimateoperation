<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=gate_reports.xls");
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
<title>Report Gate</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>
<body>
<table>
</table>
<table>
	<tr><td colspan='23' style='text-align: center; vertical-align:middle'>REPORT</td></tr>
	<tr><td colspan='23' style='text-align: center; vertical-align:middle'>GATE</td></tr>
	<tr><td colspan='23' style='text-align: center; vertical-align:middle'><?php echo date("Y-m-d H:i:s");?></td></tr>
	<?php
	if($COMPANY_NAME!='ALL PBM'){
		echo"<tr><td colspan='23' style='text-align: center; vertical-align:middle'>".$COMPANY_NAME."</td></tr>";
	}else{
		echo"<tr><td colspan='23' style='text-align: center; vertical-align:middle'>ALL PBM</td></tr>";
	}
	?>
</table>
<table>
</table>
<table border="1" cellspacing="0" cellpadding="0">
	<tr bgcolor="#CCCCCC">
		<td style='text-align: center; vertical-align:middle'>
			NO
		</td>
		<td style='text-align: center; vertical-align:middle'>
			COMPANY
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			NO CONTAINER
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			SIZE
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			TYPE
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			STATUS
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			IMDG
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			WEIGHT
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			CLASS
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
			ISO CODE
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			POD(E)/POL(I)
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			TID
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			NO TRUCK
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			GATE IN
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			USER GATE IN
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			GATE OUT
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			USER GATE OUT
		</td>
		<td style='text-align: center; vertical-align:middle'>
			TERMINAL
		</td>
		<td style='text-align: center; vertical-align:middle'>
			SHIPPING
		</td>
		<td style='text-align: center; vertical-align:middle'>
			ESY
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			TRT (Menit)
		</td>	
	</tr>	
	<?php
	$i=1;
	$jml=0;
	$ave=0;
	foreach ($data_detail as $row) {
		echo "<tr style='text-align: center; vertical-align:middle;mso-number-format:\"\@\"'>";
			echo "<td style='mso-number-format:\"\@\"'>" . $i++ . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["COMPANY_NAME"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["NO_CONTAINER"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["CONT_SIZE"]. "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["CONT_TYPE"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["CONT_STATUS"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["IMDG"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["WEIGHT"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["ID_CLASS_CODE"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["VESSEL_NAME"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["VOY_IN"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["VOY_OUT"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["ID_ISO_CODE"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["POD"] . "</td>";
	        echo "<td style='mso-number-format:\"\@\"'>" . $row["TID"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["NO_TRUCK"] . "</td>";		
			echo "<td style='mso-number-format:\"\@\"'>" . $row["GATE_IN"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["USER_GATE_IN"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["GATE_OUT"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["USER_GATE_OUT"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["TERMINAL_NAME"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["SHIPPING"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["ESY"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["TRT"] . " </td>";
			if($row["TRT"] == NULL){
				$s = 0;
			}else{
				$s = $row["TRT"];
			}

			$jml = $jml + 1;
			$ave = $ave + $s;
		echo "</tr>";
	}

	$average = $ave/$jml;
	?>
	<tr>
		<td colspan='22' style='text-align: right; vertical-align:middle'>
			RATA-RATA WAKTU
		</td>
		<td style='text-align: center; vertical-align:middle'>
			<b><?php echo ROUND($average,1); ?></b>
		</td>	
	</tr>	
</table>
</body>