<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=yor_reports.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<!DOCTYPE html PUBLIC "-//W3C//Dth XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/Dth/xhtml1-transitional.dth">

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
</style>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Report YOR</title>
</head>
<body>

<table cellpadding="2" cellspacing="2" style="border:0px !important">
    <tr><td colspan='10' bgcolor="#CCCCCC" style='text-align: center; vertical-align:middle; border:0px !important;'>YARD OCCUPANCY RATIO REPORT</td></tr>
</table>

<br />
<br />

<table cellpadding="2" cellspacing="2" border="1">
	<tr bgcolor="#FFFF00">
		<th style='text-align: center; vertical-align:middle' rowspan = '2'>YARD</th>		
		<th style='text-align: center; vertical-align:middle' rowspan = '2'>BLOCK</th>		
		<th style='text-align: center; vertical-align:middle' rowspan = '2'>CAPACITY</th>		
		<th style='text-align: center; vertical-align:middle' colspan = '4'>BOX</th>		
		<th style='text-align: center; vertical-align:middle' rowspan = '2'>TEUS</th>
		<th style='text-align: center; vertical-align:middle' rowspan = '2'>YOR</th>	
	</tr>	
	<tr bgcolor="#FFFF00">
		<th style='text-align: center; vertical-align:middle'>20'FULL</th>	
		<th style='text-align: center; vertical-align:middle'>20'MTY</th>	
		<th style='text-align: center; vertical-align:middle'>40'FULL</th>				
		<th style='text-align: center; vertical-align:middle'>40'MTY</th>
	</tr>	
	<?php 
		foreach ($data_detail as $row) {
			$sum_c = 0;
			$sum_f2 = 0;
			$sum_m2 = 0;
			$sum_f4 = 0;
			$sum_m4 = 0;
			$sum_teus = 0;
			$sum_yor = 0; 

			$rows = count($row['detail']);
			$counter = 1;
			if($row['detail']){
				foreach($row['detail'] as $value){
				$fcl20 = 0;
				$mty20 = 0;
				$fcl40 = 0;
				$mty40 = 0;
				$teus = 0;
				$yor = 0;
				$teus = 0;
				
				if($value['CONT_20FCL'] != NULL){ $fcl20 = $value['CONT_20FCL']; }
				if($value['CONT_20MTY'] != NULL){ $mty20 = $value['CONT_20MTY']; }
				if($value['CONT_40FCL'] != NULL){ $fcl40 = $value['CONT_40FCL']; }
				if($value['CONT_40MTY'] != NULL){ $mty40 = $value['CONT_40MTY']; }
				if($value['TEUS'] != NULL){ $teus = $value['TEUS']; }
				
				$yor = round(($teus/$value['CAPACITY'])*100,2);

				$sum_c += $value['CAPACITY'];
				$sum_f2 += $fcl20;
				$sum_m2 += $mty20;
				$sum_f4 += $fcl40;
				$sum_m4 += $mty40;
				$sum_teus += $teus;
				$sum_yor += $yor;
			?>
				<tr>
					<?php if($counter == 1){ ?>
					 <th rowspan="<?=$rows?>"><?php echo $value['YARD_NAME']; ?></th>
					<?php } ?>

					<td><?php echo $value['BLOCK_NAME']; ?></td>
					<td><?php echo $value['CAPACITY']; ?></td>
					<td><?php echo $fcl20; ?></td>
					<td><?php echo $mty20; ?></td>
					<td><?php echo $fcl40; ?></td>
					<td><?php echo $mty40; ?></td>
					<td><?php echo $teus; ?></td>
					<td style='mso-number-format:Percent'><?php echo $yor."%"; ?></td>
				</tr>
				
			<?php $counter+=1; } ?>
					<th bgcolor="#CCCCCC" colspan="2">SUB TOTAL</th>
					<td bgcolor="#CCCCCC"><?php echo $sum_c; ?></td>
					<td bgcolor="#CCCCCC"><?php echo $sum_f2; ?></td>
					<td bgcolor="#CCCCCC"><?php echo $sum_m2; ?></td>
					<td bgcolor="#CCCCCC"><?php echo $sum_f4; ?></td>
					<td bgcolor="#CCCCCC"><?php echo $sum_m4; ?></td>
					<td bgcolor="#CCCCCC"><?php echo $sum_teus; ?></td>
					<td bgcolor="#CCCCCC"><?php echo $sum_yor."%"; ?></td>
				</tr>
			<?php } ?>
			
	<?php } ?>
</table>
</body>