
<table border="1" cellspacing="0" cellpadding="0">
	<tr bgcolor="#CCCCCC">
		<td style='text-align: center; vertical-align:middle'>
			VESSEL NAME
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			VOYAGE IN
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			VOYAGE OUT
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			ATB
		</td>		
		<td style='text-align: center; vertical-align:middle'>
			ATD
		</td>	
		<td style='text-align: center; vertical-align:middle'>
			LENGTH
		</td>
<!-- 		<td style='text-align: center; vertical-align:middle'>
			JAM TERSEDIA
		</td>
		<td style='text-align: center; vertical-align:middle'>
			WAKTU SANDAR
		</td>
		<td style='text-align: center; vertical-align:middle'>
			JAM TERPAKAI
		</td>
		<td style='text-align: center; vertical-align:middle'>
			BOR
		</td>	 -->
	</tr>	
	<?
		$sum = 0;
	
		$t_jm_terpakai = 0;
		$jm_tersedia = $data_kade_period["PERIOD"]*270;

		foreach ($data_detail as $row) {
			$w_sandar = (isset($row["ATD"]) ?  ((strtotime($row["ATD"]) - strtotime($row["ATB"]))/3600) : 0);
			$jm_terpakai = ($row["LENGTH"]+($row["LENGTH"]*0.1))*$w_sandar;

			$sum = $sum + $row["LENGTH"];

			$t_jm_terpakai = $t_jm_terpakai + $jm_terpakai;

			echo "<tr style='text-align: center; vertical-align:middle;mso-number-format:\"\@\"'>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["VESSEL_NAME"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["VOY_IN"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["VOY_OUT"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["ATB"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["ATD"] . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $row["LENGTH"] . "</td>";
			/*echo "<td style='mso-number-format:\"\@\"'>" . $jm_tersedia . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $w_sandar . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $jm_terpakai . "</td>";
			echo "<td style='mso-number-format:\"\@\"'>" . $bor . "</td>" . "</tr>";*/
		}

		$bor = ($t_jm_terpakai/$jm_tersedia)*100;

	?>
	<tr>
		<td style='text-align: left; vertical-align:middle' bgcolor="#CCCCCC">
			Net Berth Length
		</td>
		<td colspan = '5' style='text-align: right; vertical-align:middle'>
			<? echo $sum; ?>
		</td>	
	</tr>	
	<tr>
		<td style='text-align: left; vertical-align:middle' bgcolor="#CCCCCC">
			Periode
		</td>
		<td colspan = '5' style='text-align: right; vertical-align:middle'>
			<? echo $data_kade_period["PERIOD"]; ?>
		</td>	
	</tr>	
	<tr>
		<td style='text-align: left; vertical-align:middle' bgcolor="#CCCCCC">
			BOR
		</td>
		<td colspan = '5' style='text-align:right; vertical-align:middle;'>
			<? echo ROUND($bor,2); ?>
		</td>	
	</tr>	
</table>