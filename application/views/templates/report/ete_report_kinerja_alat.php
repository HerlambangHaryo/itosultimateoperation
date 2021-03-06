<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=kinerja_alat_reports.xls");
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
<title>Report Kinerja Alat</title>
</head>
<body>
<table>
</table>
<table>
	<tr><td colspan='9' style='text-align: center; vertical-align:middle'>REPORT KINERJA ALAT</td></tr>
	<tr><td colspan='9' style='text-align: center; vertical-align:middle'>Periode Awal : <?php echo $start_period;?></td></tr>
	<tr><td colspan='9' style='text-align: center; vertical-align:middle'>Periode Akhir : <?php echo $end_period;?></td></tr>
	<?php
		if ($alat != "" and $alat != "null") {
			echo"<tr><td colspan='9' style='text-align: center; vertical-align:middle'>Nama Alat : $alat</td></tr>";
		}
		
		if ($id_user_operator != "" and $id_user_operator != "null") {
			echo"<tr><td colspan='9' style='text-align: center; vertical-align:middle'>Nama Operator : $operator_name</td></tr>";
		}
		
		if ($action != "" and $action != "null") {
			echo"<tr><td colspan='9' style='text-align: center; vertical-align:middle'>Action : $action</td></tr>";
		}
	
	?>
</table>
<br />
<br />

<?php
$xx = $data_detail[0]['MCH_TYPE'];
//print_r($data_detail)
?>
<table cellpadding="2" cellspacing="2" border="1">
	<tr bgcolor="#CCCCCC">		
		<td style='text-align: center; vertical-align:middle'>NO</td>
		<td style='text-align: center; vertical-align:middle'>NAMA ALAT</td>	
		<td style='text-align: center; vertical-align:middle'>NAMA OPERATOR</td>
		<td style='text-align: center; vertical-align:middle'>DATE ENTRY</td>
		<td style='text-align: center; vertical-align:middle'>Vessel Voyage In/Out</td>	
		<td style='text-align: center; vertical-align:middle'>CONTAINER</td>
		<td style='text-align: center; vertical-align:middle'>TYPE</td>
		<td style='text-align: center; vertical-align:middle'>SIZE</td>
		<td style='text-align: center; vertical-align:middle'>STATUS</td>
		<?php if ($xx == "YARD") { ?>
		<td style='text-align: center; vertical-align:middle'>ACTIVITY</td>
		<?php } elseif ($xx == "QUAY") { ?>
		<td style='text-align: center; vertical-align:middle'>ACTION</td>
		<?php } ?>
	</tr>	
		
	<?php $no=1; foreach ($data_detail as $row) {
		if($row['ACT'] != NULL){ ?>
			<tr>
				<td><? echo $no; ?></td>
				<td><? echo $row['MCH_NAME']; ?></td>
				<td><? echo $row['FULL_NAME']; ?></td>
				<td><? echo $row['DATE_ENTRY']; ?></td>
				<td><? echo $row['VESSEL_NAME']; ?></td>
				<td><? echo $row['NO_CONTAINER']; ?></td>
				<td><? echo $row['CONT_TYPE']; ?></td>
				<td><? echo $row['CONT_SIZE']; ?></td>
				<td><? echo $row['CONT_STATUS']; ?></td>
				<td><? echo $row['ACT']; ?></td>
			</tr>	
		<?php $no++; }
	} ?>

</table>

<br />
<br />

<table cellpadding="2" cellspacing="2" border="1">
	<tr bgcolor="#CCCCCC">		
		<td rowspan="2" style='text-align: center; vertical-align:middle'>NO</td>
		<td rowspan="2" style='text-align: center; vertical-align:middle'>NAMA ALAT</td>
		<td rowspan="2" style='text-align: center; vertical-align:middle'>NAMA OPERATOR</td>
		<td rowspan="2" style='text-align: center; vertical-align:middle'>SIZE</td>
		<td rowspan="2" style='text-align: center; vertical-align:middle'>STATUS</td>	
		<td colspan="3" style='text-align: center; vertical-align:middle'>OUTBOUND</td>
		<td colspan="3" style='text-align: center; vertical-align:middle'>INBOUND</td>
		<td rowspan="2" style='text-align: center; vertical-align:middle'>JUMLAH</td>
	</tr>
	<tr bgcolor="#CCCCCC">
		<td style='text-align: center; vertical-align:middle'>STACKING</td>
		<td style='text-align: center; vertical-align:middle'>ON CHASSISED</td>
		<td style='text-align: center; vertical-align:middle'>LOADED</td>
		<td style='text-align: center; vertical-align:middle'>DISCHARGED</td>
		<td style='text-align: center; vertical-align:middle'>STACKING</td>
		<td style='text-align: center; vertical-align:middle'>ON CHASSISED</td>
	</tr>
	<?php
		$total = 0;
		$no=1;
		foreach($summary as $key){
	?>
		<tr>
			<td rowspan="8"><?php echo $no; ?></td>
			<td rowspan="8"><?php echo $key['MCH_NAME']; ?></td>
			<td rowspan="8"><?php echo $key['FULL_NAME']; ?></td>
			<td rowspan="2">20</td>
			<td>FCL</td>
			<td><?php echo $jso_f20 = $key['fcl20']->JUMLAH_STACKING_OUTBOUND; ?></td>
			<td><?php echo $jco_f20 = $key['fcl20']->JUMLAH_CHASSIS_OUTBOUND; ?></td>
			<td><?php echo $jl_f20 = $key['fcl20']->JUMLAH_LOAD; ?></td>
			<td><?php echo $jd_f20 = $key['fcl20']->JUMLAH_DISC; ?></td>
			<td><?php echo $jsi_f20 = $key['fcl20']->JUMLAH_STACKING_INBOUND; ?></td>
			<td><?php echo $jci_f20 = $key['fcl20']->JUMLAH_CHASSIS_INBOUND; ?></td>
			<td><?php echo $total_f20 = $jso_f20+$jco_f20+$jl_f20+$jd_f20+$jsi_f20+$jci_f20; ?></td>
		</tr>
		<tr>
			<td>MTY</td>
			<td><?php echo $jso_m20 = $key['mty20']->JUMLAH_STACKING_OUTBOUND; ?></td>
			<td><?php echo $jco_m20 = $key['mty20']->JUMLAH_CHASSIS_OUTBOUND; ?></td>
			<td><?php echo $jl_m20 = $key['mty20']->JUMLAH_LOAD; ?></td>
			<td><?php echo $jd_m20 = $key['mty20']->JUMLAH_DISC; ?></td>
			<td><?php echo $jsi_m20 = $key['mty20']->JUMLAH_STACKING_INBOUND; ?></td>
			<td><?php echo $jci_m20 = $key['mty20']->JUMLAH_CHASSIS_INBOUND; ?></td>
			<td><?php echo $total_m20 = $jso_m20+$jco_m20+$jl_m20+$jd_m20+$jsi_m20+$jci_m20; ?></td>
		</tr>
		<tr>
			<td rowspan="2">21</td>
			<td>FCL</td>
			<td><?php echo $jso_f21 = $key['fcl21']->JUMLAH_STACKING_OUTBOUND; ?></td>
			<td><?php echo $jco_f21 = $key['fcl21']->JUMLAH_CHASSIS_OUTBOUND; ?></td>
			<td><?php echo $jl_f21 = $key['fcl21']->JUMLAH_LOAD; ?></td>
			<td><?php echo $jd_f21 = $key['fcl21']->JUMLAH_DISC; ?></td>
			<td><?php echo $jsi_f21 = $key['fcl21']->JUMLAH_STACKING_INBOUND; ?></td>
			<td><?php echo $jci_f21 = $key['fcl21']->JUMLAH_CHASSIS_INBOUND; ?></td>
			<td><?php echo $total_f21 = $jso_f21+$jco_f21+$jl_f21+$jd_f21+$jsi_f21+$jci_f21; ?></td>
		</tr>
		<tr>
			<td>MTY</td>
			<td><?php echo $jso_m21 = $key['mty21']->JUMLAH_STACKING_OUTBOUND; ?></td>
			<td><?php echo $jco_m21 = $key['mty21']->JUMLAH_CHASSIS_OUTBOUND; ?></td>
			<td><?php echo $jl_m21 = $key['mty21']->JUMLAH_LOAD; ?></td>
			<td><?php echo $jd_m21 = $key['mty21']->JUMLAH_DISC; ?></td>
			<td><?php echo $jsi_m21 = $key['mty21']->JUMLAH_STACKING_INBOUND; ?></td>
			<td><?php echo $jci_m21 = $key['mty21']->JUMLAH_CHASSIS_INBOUND; ?></td>
			<td><?php echo $total_m21 = $jso_m21+$jco_m21+$jl_m21+$jd_m21+$jsi_m21+$jci_m21; ?></td>
		</tr>
		<tr>
			<td rowspan="2">40</td>
			<td>FCL</td>
			<td><?php echo $jso_f40 = $key['fcl40']->JUMLAH_STACKING_OUTBOUND; ?></td>
			<td><?php echo $jco_f40 = $key['fcl40']->JUMLAH_CHASSIS_OUTBOUND; ?></td>
			<td><?php echo $jl_f40 = $key['fcl40']->JUMLAH_LOAD; ?></td>
			<td><?php echo $jd_f40 = $key['fcl40']->JUMLAH_DISC; ?></td>
			<td><?php echo $jsi_f40 = $key['fcl40']->JUMLAH_STACKING_INBOUND; ?></td>
			<td><?php echo $jci_f40 = $key['fcl40']->JUMLAH_CHASSIS_INBOUND; ?></td>
			<td><?php echo $total_f40 = $jso_f40+$jco_f40+$jl_f40+$jd_f40+$jsi_f40+$jci_f40; ?></td>
		</tr>
		<tr>
			<td>MTY</td>
			<td><?php echo $jso_m40 = $key['mty40']->JUMLAH_STACKING_OUTBOUND; ?></td>
			<td><?php echo $jco_m40 = $key['mty40']->JUMLAH_CHASSIS_OUTBOUND; ?></td>
			<td><?php echo $jl_m40 = $key['mty40']->JUMLAH_LOAD; ?></td>
			<td><?php echo $jd_m40 = $key['mty40']->JUMLAH_DISC; ?></td>
			<td><?php echo $jsi_m40 = $key['mty40']->JUMLAH_STACKING_INBOUND; ?></td>
			<td><?php echo $jci_m40 = $key['mty40']->JUMLAH_CHASSIS_INBOUND; ?></td>
			<td><?php echo $total_m40 = $jso_m40+$jco_m40+$jl_m40+$jd_m40+$jsi_m40+$jci_m40; ?></td>
		</tr>
		<tr>
			<td rowspan="2">45</td>
			<td>FCL</td>
			<td><?php echo $jso_f45 = $key['fcl45']->JUMLAH_STACKING_OUTBOUND; ?></td>
			<td><?php echo $jco_f45 = $key['fcl45']->JUMLAH_CHASSIS_OUTBOUND; ?></td>
			<td><?php echo $jl_f45 = $key['fcl45']->JUMLAH_LOAD; ?></td>
			<td><?php echo $jd_f45 = $key['fcl45']->JUMLAH_DISC; ?></td>
			<td><?php echo $jsi_f45 = $key['fcl45']->JUMLAH_STACKING_INBOUND; ?></td>
			<td><?php echo $jci_f45 = $key['fcl45']->JUMLAH_CHASSIS_INBOUND; ?></td>
			<td><?php echo $total_f45 = $jso_f45+$jco_f45+$jl_f45+$jd_f45+$jsi_f45+$jci_f45; ?></td>
		</tr>
		<tr>
			<td>MTY</td>
			<td><?php echo $jso_m45 = $key['mty45']->JUMLAH_STACKING_OUTBOUND; ?></td>
			<td><?php echo $jco_m45 = $key['mty45']->JUMLAH_CHASSIS_OUTBOUND; ?></td>
			<td><?php echo $jl_m45 = $key['mty45']->JUMLAH_LOAD; ?></td>
			<td><?php echo $jd_m45 = $key['mty45']->JUMLAH_DISC; ?></td>
			<td><?php echo $jsi_m45 = $key['mty45']->JUMLAH_STACKING_INBOUND; ?></td>
			<td><?php echo $jci_m45 = $key['mty45']->JUMLAH_CHASSIS_INBOUND; ?></td>
			<td><?php echo $total_m45 = $jso_m45+$jco_m45+$jl_m45+$jd_m45+$jsi_m45+$jci_m45; ?></td>
		</tr>

	<?php
		$total += $total_f20 + $total_m20 + $total_f21 + $total_m21 + $total_f40 + $total_m40 + $total_f45 + $total_m45;
		$no++;
	} ?>
	<tr>
		<td colspan="11" bgcolor="#CCCCCC">TOTAL</td>
		<td bgcolor="#CCCCCC" style='text-align: center; vertical-align:middle'><?php echo $total; ?></td>
	</tr>
</table>

</body>