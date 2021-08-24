<table cellpadding="2" cellspacing="2" border="1">
	<tr>
		<th style="text-align: center; vertical-align:middle;" bgcolor="#CCCCCC" colspan="<?php echo count($crane)?>" align="center">Discharge</th>
		<th style="text-align: center; vertical-align:middle;" bgcolor="#CCCCCC" colspan="<?php echo count($crane)?>" align="center">Loading</th>
	</tr>
	<tr>
		<?php foreach ($crane as $row) { ?>
		<td class="tchild">
			<table border=none;>
				<tr class="trchild">
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
		<td class="tchild">
			<table border=none;>
				<tr class="trchild">
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
	$tpd=0;
	$tcd=0;
	$tpcd=0;
	
	$t1=0;
	$c1=0;
	$r1=0;
	$t2=0;
	$c2=0;
	$r2=0;
	$t3=0;
	$c3=0;
	$r3=0;
    foreach ($crane as $row) {
	$baris++;
	$tpd+=$row['PLANNED_DISC']+$row['PLANNED_LOAD'];
	$tcd+=$row['COMPLETE_DISC']+$row['COMPLETE_LOAD'];
	$tpcd+=(($row['PLANNED_DISC'] - $row['COMPLETE_DISC'])+($row['PLANNED_LOAD'] - $row['COMPLETE_LOAD']));
	$baris++;
	
	
	$t1+=$row['PLANNED_DISC'];
	$c1+=$row['COMPLETE_DISC'];
	$r1+=$row['PLANNED_DISC'] - $row['COMPLETE_DISC'];
	$t2+=$row['PLANNED_LOAD'];
	$c2+=$row['COMPLETE_LOAD'];
	$r2+=$row['PLANNED_LOAD'] - $row['COMPLETE_LOAD'];
	$t3+=$tpd;
	$c3+=$tcd;
	$r3+=$tpcd;
?>
	<tr style='text-align: center; vertical-align:middle;'>
	    <th bgcolor="#CCCCCC" colspan="2"><?=$row['MCH_NAME']?></th>
	    <td><?=$row['PLANNED_DISC']?></td>
	    <td><?=$row['COMPLETE_DISC']?></td>
	    <td><?=$row['PLANNED_DISC'] - $row['COMPLETE_DISC']?></td>
	    <td><?=$row['PLANNED_LOAD']?></td>
	    <td><?=$row['COMPLETE_LOAD']?></td>
	    <td><?=$row['PLANNED_LOAD'] - $row['COMPLETE_LOAD']?></td>
	    <td><?=$tpd?></td>
	    <td><?=$tcd?></td>
	    <td><?=$tpcd?></td>
	</tr>
<?php
    }
?>
	<tr style='text-align: center; vertical-align:middle;'>
	    <th bgcolor="#CCCCCC" colspan="2">TOTAL</th>
		<td><?=$t1?></td>
		<td><?=$c1?></td>
		<td><?=$r1?></td>
		<td><?=$t2?></td>
		<td><?=$c2?></td>
		<td><?=$r2?></td>
		<td><?=$t3?></td>
		<td><?=$c3?></td>
		<td><?=$r3?></td>
	</tr>
</table>