<?php
	$dShip=count($bay_label);
	$wdShip=$dShip*50;
	$dbay=$dShip*28;
	$lfBay=230;
	$lfBay2=$lfBay+$dbay;
?>
<img src="<?=IMG_?>icons/compass.png" width="80px" style="margin: -70px 0px 0px 10px;-moz-transform: rotate(90deg);-webkit-transform: rotate(90deg);-o-transform: rotate(90deg);-ms-transform: rotate(90deg);transform: rotate(90deg);"/>
<div id="l2a" style="position: relative; left:<?=$lfBay;?>px; top: 15px;height:150;z-index:1; margin-bottom:40px;" >

<table bordercolor="#037ACA" border="0" cellspacing="1" >
<tbody>
	<tr>
		 <?php foreach ($bay_label as $row)
		 	{  
		 		?>
					<td align="center" style="width:20px;height:10px;font-size:12px; font-family:Tahoma;"><?php echo $row['BAY']; ?></td>
			  <?php
				} 
			  ?>
	</tr>
	<!---------------------------- ABOVE -------------------------------->
	<tr>
		 <?php 
			$idy=0;
			foreach ($profile_abv as $row){
			$idy++;
			if ($row['ABOVE'] == 'NONE')
			{
			?>
				<td style="width:20px;height:25px;font-size:8px; font-family:Tahoma;"><div style="background:#424242;border-style:solid;border-width: 1px;border-color:#424242;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #ffffff;
						font-size:small;
						text-decoration: none;"></span> </div></td>	
					
				<?php }
				else if($row['CWP_D'] >= '1')
				{
					$idx[$idy]=1;
				?>
						<td align="center" style="width:20px;height:25px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF;" >
						<div style="background:#377ef0;border-style:solid;border-width: 1px;border-color:#377ef0;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #ffffff;
						font-size:small;
						text-decoration: none;"><?=$row['CONT_IMP'];?></span> </div>
						</td>
				<?php
				}
				else if($row['CWP_D'] == '0')
				{
				?>
					<td align="center" style="width:20px;height:25px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF;" ><a href="javascript:assign_cwp_<?=$tab_id?>('<?echo $row['BAY']?>','<?=$row['ID_BAY']?>','<?php if ($row['OCCUPY']=='Y') { ?><?php echo $row['BAY']; ?>(<?php echo $row['BAY']+1;?>)<?php  } else { ?><?php echo $row['BAY'];?><?php } ?>','DECK','IMPORT')"><div style="background:#ffffff;border-style:solid;border-width: 1px;border-color:#377ef0;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #377ef0;
						font-size:small;
						text-decoration: none;"><?=$row['CONT_IMP'];?></span> </div></a></td>
				<?php
				}
				else {
					$idx[$idy]=1;
				?>
					<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF; ">
					<div style="background:#377ef0;border-style:solid;border-width: 1px;border-color:#377ef0;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #ffffff;
						font-size:small;
						text-decoration: none;"><?=$row['CONT_IMP'];?></span> </div>
					</td>
			  <?php } 
				} ?>
	</tr>
	<!---------------------------- ABOVE -------------------------------->
	
	<!--UPDATE GANDA -->
	<tr>
		 <?php	$jml_bay = count($bay_label); ?>
				<td colspan="<?=$jml_bay;?>" align="center" style="width:20px;height:3px;  background-color:#444343;"></td>
	</tr>
	<!---------------------------- ABOVE -------------------------------->
	<tr>
		 <?php foreach ($profile_blw as $row){
 			$idy++;
			if($row['BELOW'] == 'NONE')
			{
			?>
				<td style="width:20px;height:25px;font-size:8px; font-family:Tahoma;"><div style="background:#424242;border-style:solid;border-width: 1px;border-color:#424242;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #ffffff;
						font-size:small;
						text-decoration: none;"></span> </div></td>
				
			<?php 
			}
			else if($row['CWP_H'] >= '1')
			{$idx[$idy]=1;
			?>
				<td align="center" style="width:20px;height:25px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF; "><div style="background:#377ef0;border-style:solid;border-width: 1px;border-color:#377ef0;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #ffffff;
						font-size:small;
						text-decoration: none;"><?=$row['CONT_IMP'];?></span> </div></td>
			<?php
			}
			else if($row['CWP_H'] == '0')
			{
			?>
					<td align="center" style="width:20px;height:25px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF; "><a href="javascript:assign_cwp_<?=$tab_id?>('<?echo $row['BAY']?>','<?=$row['ID_BAY']?>','<?php if ($row['OCCUPY']=='Y') { ?><?php echo $row['BAY']; ?>(<?php echo $row['BAY']+1;?>)<?php  } else { ?><?php echo $row['BAY'];?><?php } ?>','HATCH','IMPORT')"><div style="background:#ffffff;border-style:solid;border-width: 1px;border-color:#377ef0;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #377ef0;
						font-size:small;
						text-decoration: none;"><?=$row['CONT_IMP'];?></span> </div></a></td>
			<?php
			}
			else { $idx[$idy]=1;?>
				
					<td align="center" style="width:20px;height:25px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF; "><div style="background:#377ef0;border-style:solid;border-width: 1px;border-color:#377ef0;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #ffffff;
						font-size:small;
						text-decoration: none;"><?=$row['CONT_IMP'];?></span> </div></td>
			  <?php } 
				} ?>
	</tr>
	
	<!---------------------------- ABOVE -------------------------------->
	
	
</tbody>
</table>

</div>

<?php if ($along_side=='P'){ ?>
	<div id="l1a" style="position: absolute;top:20px;left:<?=$lfBay2;?>px; transform: rotateY(180deg);"><img src="images/vessel_profile/vespoburitan.png" height="219px"></div>
	<div style="position: absolute;top:0px;left:30px;z-index:10;"><b><font size="3"><?php print_r($ves_voyage[0]['VESVOY']); ?></font></b><br/>
	</div>
	<div id="l3a" style="position: absolute;top:20px;left:30px; transform: rotateY(180deg);"><img src="images/vessel_profile/vesprohaluan.png" height="219px"></div>
<?php }else if ($along_side=='S'){ ?>
	<div id="l1a" style="position: absolute;top:0px;left:30px;">
		<b><font size="3"><?php print_r($ves_voyage[0]['VESVOY']); ?></font></b><br/>
		<img src="images/vessel_profile/vespoburitan.png" height="219px">
	</div>
	
	<div id="l3a" style="position: absolute;top:20px;left:<?=$lfBay2;?>px;"><img src="images/vessel_profile/vesprohaluan.png" height="219px"></div>

<?php } ?>

<div id="l4a" style="position: absolute;top:158px;left:<?=$lfBay;?>px;background-color:#950000;height:60px;width:<?=$dbay;?>px;"></div>

<div id="l2b" style="position: relative; left:<?=$lfBay;?>px; top: 100px;height:150;z-index:1; margin-bottom:40px;" >

<table bordercolor="#037ACA" border="0" cellspacing="1" >
<tbody>
	<tr>
		 <?php foreach ($bay_label as $row)
		 	{  
		 		?>
					<td align="center" style="width:20px;height:10px;font-size:12px; font-family:Tahoma;"><?php echo $row['BAY']; ?></td>
			  <?php
				} 
			  ?>
	</tr>
	<!--UPDATE GANDA -->
	
	<!---------------------------- BELOW -------------------------------->
	
	<tr>
		 <?php 
			$idy=0;
			foreach ($profile_abv as $row){
			$idy++;
			if ($row['ABOVE'] == 'NONE')
			{
			?>
					<td style="width:20px;height:25px;font-size:8px; font-family:Tahoma;"><div style="background:#424242;border-style:solid;border-width: 1px;border-color:#424242;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #ffffff;
						font-size:small;
						text-decoration: none;"></span> </div></td>
					
				<?php }
				else if($row['CWP_DE'] >= '1')
				{
					$idx[$idy]=1;
				?>
						<td align="center" style="width:20px;height:25px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF;" >
						<div style="background:#e45641;border-style:solid;border-width: 1px;border-color:#e45641;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #ffffff;
						font-size:small;
						text-decoration: none;"><?=$row['CONT_EXP'];?></span> </div>
						</td>
				<?php
				}
				else if($row['CWP_DE'] == '0')
				{
				?>
					<td align="center" style="width:20px;height:25px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF;" ><a href="javascript:assign_cwp_<?=$tab_id?>('<?echo $row['BAY']?>','<?=$row['ID_BAY']?>','<?php if ($row['OCCUPY']=='Y') { ?><?php echo $row['BAY']; ?>(<?php echo $row['BAY']+1;?>)<?php  } else { ?><?php echo $row['BAY'];?><?php } ?>','DECK','EXPORT')"><div style="background:#ffffff;border-style:solid;border-width: 1px;border-color:#e45641;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #e45641;
						font-size:small;
						text-decoration: none;"><?=$row['CONT_EXP'];?></span> </div></a></td>
				<?php
				}
				else {
					$idx[$idy]=1;
				?>
					<td align="center" style="width:20px;height:25px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF; "><div style="background:#e45641;border-style:solid;border-width: 1px;border-color:#e45641;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #ffffff;
						font-size:small;
						text-decoration: none;"><?=$row['CONT_EXP'];?></span> </div></td>
			  <?php } 
				} ?>
	</tr>
	<tr>
		 <?php	$jml_bay = count($bay_label); ?>
				<td colspan="<?=$jml_bay;?>" align="center" style="width:20px;height:3px;  background-color:#444343;"></td>
	</tr>
	<tr>
		 <?php foreach ($profile_blw as $row){
 			$idy++;
			if($row['BELOW'] == 'NONE')
			{
			?>
				    <td style="width:20px;height:25px;font-size:8px; font-family:Tahoma;"><div style="background:#e45641;border-style:solid;border-width: 1px;border-color:#e45641;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #ffffff;
						font-size:small;
						text-decoration: none;"><?=$row['CONT_EXP'];?></span> </div></td>
				    
			<?php 
			}
			else if($row['CWP_HE'] >= '1')
			{$idx[$idy]=1;
			?>
				<td align="center" style="width:20px;height:25px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF; "><div style="background:#e45641;border-style:solid;border-width: 1px;border-color:#e45641;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #ffffff;
						font-size:small;
						text-decoration: none;"><?=$row['CONT_EXP'];?></span> </div></td>
			<?php
			}
			else if($row['CWP_HE'] == '0')
			{
			?>
					<td align="center" style="width:20px;height:25px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF; "><a href="javascript:assign_cwp_<?=$tab_id?>('<?echo $row['BAY']?>','<?=$row['ID_BAY']?>','<?php if ($row['OCCUPY']=='Y') { ?><?php echo $row['BAY']; ?>(<?php echo $row['BAY']+1;?>)<?php  } else { ?><?php echo $row['BAY'];?><?php } ?>','HATCH','EXPORT')"><div style="background:#ffffff;border-style:solid;border-width: 1px;border-color:#e45641;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #e45641;
						font-size:small;
						text-decoration: none;"><?=$row['CONT_EXP'];?></span> </div></a></td>
			<?php
			}
			else { $idx[$idy]=1;?>
				
					<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF; "><div style="background:#e45641;border-style:solid;border-width: 1px;border-color:#e45641;height:25px;width:25px;" ><span style="width: 25px;
						height: 25px;
						display: table-cell;
						vertical-align:middle;
						text-align:center;
						color: #ffffff;
						font-size:small;
						text-decoration: none;"><?=$row['CONT_EXP'];?></span> </div></td>
			  <?php } 
				} ?>
	</tr>
	<!---------------------------- BELOW -------------------------------->
</tbody>
</table>

</div>

<?php if ($along_side=='P'){ ?>
	<div id="l1b" style="position: absolute;top:220px;left:<?=$lfBay2;?>px; transform: rotateY(180deg);"><img src="images/vessel_profile/vespoburitan.png" height="219px"></div>
	<div style="position: absolute;top:0px;left:30px;z-index:10;"><b><font size="3"></font></b><br/>
	</div>
	<div id="l3b" style="position: absolute;top:220px;left:30px; transform: rotateY(180deg);"><img src="images/vessel_profile/vesprohaluan.png" height="219px"></div>
<?php }else if ($along_side=='S'){ ?>
	<div id="l1b" style="position: absolute;top:200px;left:30px;">
		<b><font size="3"></font></b><br/>
		<img src="images/vessel_profile/vespoburitan.png" height="219px">
	</div>
	
	<div id="l3b" style="position: absolute;top:220px;left:<?=$lfBay2;?>px;"><img src="images/vessel_profile/vesprohaluan.png" height="219px"></div>

<?php } ?>

<div id="l4b" style="position: absolute;top:358px;left:<?=$lfBay;?>px;background-color:#950000;height:60px;width:<?=$dbay;?>px;"></div>

<div style="position: absolute;top:450px;">
	<button onclick="reloadaja_<?=$tab_id?>();">Refresh Sequence</button>
</div>

<input type="hidden" id="selected-v_idmchwkplan_<?=$tab_id?>" />
<input type="hidden" id="selected-v_seq_<?=$tab_id?>" />
<input type="hidden" id="selected-v_bay_<?=$tab_id?>" />
<input type="hidden" id="selected-v_act_<?=$tab_id?>" />
<input type="hidden" id="selected-v_deck_<?=$tab_id?>" />

<div style="position: absolute; top:480px;" >&nbsp;&nbsp;&nbsp;Crane Sequence</div>

<style>
	.boxBay{
	    width:27px;
	    float: left;
	    margin-right: 1px;
	}
	.boxh
	{
		
		/*height:45px;*/
		border:1px solid #FFF;
		font-weight: bold;
		font-size:11px;
	}
	.textJam
	{
		left:5px;
		height: 30px;
		color:#0000ff;
		float:left;
		font-size:x-small;
		font-weight: normal;
		width: 70px;
	}
	.lineJam{
	    float: right;
	    border-top: 1px solid #CCCCCC;
	    height: 5px;
	    width: 9px;
	}
	.boxTimeLine{
	    border-bottom: 1px dashed #cccccc;
	    height: 30px;
	    padding-left: 150px;
	}
</style>

<div id="cwp_seq_content_<?=$tab_id?>" style="position: absolute; top:500px; width: <?=420 + ($jml_bay * 28)?>px;" >
    <div style="float: left; width: 80px; border-right: 1px solid;">
<?php
for($i=0; $i < $selisihy;$i++){
?>
	<div class="textJam" style="<?=$style?>"><?=date('d-m|H:i', strtotime($startx) + (60*60*$i))?></div>
<?php
    for($j=0; $j <= 5; $j++){
?>
	<div class="lineJam">&nbsp;</div>
<?php
    }
}
?>
    </div>
    <div style="float: right; width: calc(100% - 80px); border-right: 2px solid #377ef0;">
<?php
for($i=0; $i < $selisihy;$i++){
    $style = '';
    if($i > 0 && $i % 6 == 0){
	$style = '';
    }
?>
	<div class="boxTimeLine">
<?php
    if($i == 0){
	$idxSeq = 0;
	$from = 0;
	$to = 0;
//	if ($along_side=='P'){
	    $from  = 1;
	    $to = $jml_bay;
//	}else if ($along_side=='S'){
//	    $from  = $jml_bay;
//	    $to = 1;
//	}
	$x = $from;
	for($y = 1; $y <= $jml_bay;$y++){
	    $hasBay = FALSE;
	    $lenBefore = 0;
?>
	    <div class="boxBay" style="height: <?=$selisihy * 30?>px" data-x="<?=$x?>">
<?php
	    foreach ($dataSeq as $dt){
		$style = '';
		if($dt['BAY'] == $bay_label[$x-1]['BAY']){
		    $hasBay = TRUE;
		    $style = 'margin-top: '.(($dt['START_SEQUENCE_'] * 5) - ($lenBefore)).'px;';
		    $style .= 'height: '.($dt['LENGTH_SEQUENCE_'] * 5).'px;';
		    $style .= 'background: '.$dt['BG_COLOR'].';';
		    if($dt['DECK_HATCH'] == 'd' && $dt['CWP_D']<>0){
			$dinf='D';
		    }else if($dt['DECK_HATCH'] == 'd' && $dt['CWP_DE']<>0){
			$dinf='DE';
		    }else if($dt['DECK_HATCH'] == 'h' && $dt['CWP_H']<>0){
			$dinf='H';
		    }else if($dt['DECK_HATCH'] == 'h' && $dt['CWP_HE']<>0){
			$dinf='HE';
		    }
		    
?>
		<div id="bay<?=$idxSeq.'_'.$tab_id?>" class="boxh" style="<?=$style?>" onclick="onClickSeq_<?=$tab_id?>(this,'<?=$dinf?>')" data-id-mch-working-plan="<?=$dt['ID_MCH_WORKING_PLAN']?>" data-sequence="<?=$dt['SEQUENCE']?>" data-bay="<?=$dt['BAY']?>" data-activity="<?=$dt['ACTIVITY']?>">
		    <?php
			if($dt['LENGTH_SEQUENCE_'] > 0)
			echo $dt['BAY'].' '.$dt['ACTIVITY'].$dt['DECK_HATCH'];
		    ?>
		</div>
<?php
		    $lenBefore = ($dt['START_SEQUENCE_'] * 5) + ($dt['LENGTH_SEQUENCE_'] * 5);
		    $idxSeq++;
		}
	    }
	    if(!$hasBay){
?>
		<div class="boxh" style=""></div>
<?php
	    }
?>
	    </div>
<?php
//	    if ($along_side=='P'){
		$x++;
//	    }else if ($along_side=='S'){
//		$x--;
//	    }
	}
    }
?>
	</div>
<?php
}
?>
    </div>
</div>


<script type="text/javascript">
	function reloadaja_<?=$tab_id?>(){
	    loadmask.show();
	    Ext.get("<?=$tab_id?>-innerCt").load({
	        url: '<?=controller_?>qc_working_plan/refresh_index?tab_id=<?=$tab_id?>&id_ves_voyage=<?=$id_ves_voyage?>',
	        scripts: true,
	        contentType: 'html',
	        autoLoad: true,
	        success: function(){
	            loadmask.hide();
	        }
	    });
	}
</script>