<?php
	$dShip=count($bay_label);
	$wdShip=$dShip*50;
	$dbay=$dShip*28;
	$lfBay=230;
	$lfBay2=$lfBay+$dbay;
//	$max_tier = 0;
?>
<div id="div_vescontent_<?=$tab_id?>" style="width: <?=180 + ($dShip *27) + 210 ?>px; display: flex;">
    <div id="l3" style="transform: rotateY(180deg); width: 180px;float: left;">
	<img src="images/vessel_profile/vesprohaluan_top.png" width="180px" height="115px">
	<img src="images/vessel_profile/vesprohaluan_bottom.png" height="<?=($max_tier_under[0]['JML'] * 10) + 20?>px" width="180px">
    </div>
<div id="l2" style="float: left;  background: linear-gradient(to bottom,  #ffffff 0%,#ffffff 50%,#950000 50%,#950000 100%); border-bottom: 1px #000000 solid;" >

    <table bordercolor="#037ACA" border="0" cellspacing="1" style="margin-top: <?=115 - ($max_tier_under[0]['JML'] * 10) - 15?>px;">
<tbody>
	<tr>
		 <?php foreach ($bay_label as $row)
		 	{  
		 		?>
					<td align="center" style="width:20px;height:10px;font-size:8px; font-family:Tahoma;"><?php echo $row['BAY']; ?></td>
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
			$jmltieron=$row['JML_TIER_ON'];
			if ($row['ABOVE'] == 'AKTIF')
			{
//				$max_tier = $max_tier < $jmltieron ? $jmltieron : $max_tier;
			?>
					<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF;vertical-align: bottom;" >
					    <a href="javascript:edit_bay_profile_<?=$tab_id?>(<?=$row['ID_BAY'];?>)">
		<?php
			for($tier = 1;$tier <= $jmltieron;$tier++){
		?>
						<img src="images/vessel_profile/stack_bay_fit.png" height="10" width="25"/><br>
		<?php
			}
		?>
					    </a>
					</td>
				<?php }
				else if($row['ABOVE'] == 'NONE')
				{
				?>
				<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF;vertical-align: bottom;">
				    <!--<img src="images/vessel_profile/disable_bay_<?=$jmltieron;?>.png" height="50" width="25"/>-->
		<?php
			for($tier = 1;$tier <= $jmltieron;$tier++){
		?>
						<img src="images/vessel_profile/disable_bay_fit.png" height="10" width="25"/><br>
		<?php
			}
		?>		
				</td>
				<?php
				}
				else {
					$idx[$idy]=1;
				?>
					<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF;vertical-align: bottom; "><a href="javascript:info_bay('<?=$id?>','<?echo $row['BAY']?>','<?=$row['ID']?>','<? if ($row['OCCUPY']=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else { ?><? echo $row['BAY'];?><? } ?>','DECK')"><img src="images/vessel_profile/stack_bay_full.png" title="INBOUND OUTBOUND" height="50" width="25" /></a></td>
			  <?php } 
				} ?>
	</tr>
	<!---------------------------- ABOVE -------------------------------->
	<tr>
		 <?php	$jml_bay = count($bay_label); ?>
				<td colspan="<?=$jml_bay;?>" align="center" style="width:20px;height:3px;  background-color:#444343;">
				<div id="l4" style="background-color:#950000;height:3px;width:100%;"></div>
				</td>
	</tr>
	<!---------------------------- BELOW -------------------------------->
	<tr style="background: #950000;">
		 <?php foreach ($profile_blw as $row){
 			$idy++;
			$jmltierunder=$row['JML_TIER_UNDER'];
			if($row['BELOW'] == 'AKTIF')
			{
//				$max_tier = $max_tier < $jmltierunder ? $jmltierunder : $max_tier;
			?>
				<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;vertical-align: bottom;">
				    <a href=javascript:edit_bay_profile_<?=$tab_id?>(<?=$row['ID_BAY'];?>)>
					<!--<img src="images/vessel_profile/stack_bay_<?=$jmltierunder;?>.png" height="50" width="25"/>-->
		<?php
			for($tier = 1;$tier <= $jmltierunder;$tier++){
		?>
						<img src="images/vessel_profile/stack_bay_fit.png" height="10" width="25"/><br>
		<?php
			}
		?>
				    </a>
				</td>
			<?php 
			}
			else if($row['BELOW'] == 'NONE')
			{
			?>
				<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;vertical-align: bottom;">
				    <!--<img src="images/vessel_profile/disable_bay_<?=$jmltierunder?>.png" height="10" width="25"/>-->
		<?php
			for($tier = 1;$tier <= $jmltierunder;$tier++){
		?>
						<img src="images/vessel_profile/disable_bay_fit.png" height="10" width="25"/><br>
		<?php
			}
		?>
				</td>
			<?php
			}
			else { $idx[$idy]=1;?>
				
					<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;"><a href="javascript:info_bay('<?=$id?>','<?echo $row['BAY']?>','<?=$row['ID']?>','<?php if ($row['OCCUPY']=='Y') { ?><?php echo $row['BAY']; ?>(<?php echo $row['BAY']+1;?>)<?php  } else { ?><?php echo $row['BAY'];?><?php } ?>','HATCH')"><img src="images/vessel_profile/stack_bay_full.png" title="INBOUND OUTBOUND" height="50" width="25" /></a></td>
			  <?php } 
				} ?>
	</tr>
	<!---------------------------- BELOW -------------------------------->
</tbody>
</table>

</div>
    <div id="l1" style="float:left; transform: rotateY(180deg);width: 180px;">
	<img src="images/vessel_profile/vespoburitan_top.png" width="180px" height="115px">
	<img src="images/vessel_profile/vespoburitan_bottom.png" height="<?=($max_tier_under[0]['JML'] * 10) + 20?>px" width="180px">
    </div>
</div>
<table>
<tr>
	<td>
	<fieldset style="margin-left:80px; border: 3px solid #cca; -moz-border-radius: 10px; border: 1px solid #cca;">
	<legend>Vessel Information</legend>
		<table style="padding-left:10px">
			<tr>
				<td class="form-field-caption" align="right"><b>Vessel Voyage</b></td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left"><? echo $vessel." ".$voy; ?></td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right"><b>Call Sign</b></td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left"><?=$callsign;?></td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right"><b>Operator</b></td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left"><?=$opr;?></td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right"><b>LOA</b></td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left"><?=$lngth;?></td>
			</tr>
		</table>
	</fieldset>
	</td>
	<td>&nbsp;</td>
	<td>
	<fieldset style="margin-left:90px; border: 3px solid #cca; -moz-border-radius: 10px; border: 1px solid #cca;">
	<legend>Vessel Profile</legend>
		<table style="padding-left:10px">
			<tr>
				<td class="form-field-caption" align="right"><b>Bay Count</b></td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left"><?=$vesselinfo['BAY_COUNT'];?></td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right"><b>Max Row</b></td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left"><?=$vesselinfo['MAX_ABOVE_ROWS'];?></td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right"><b>Max Tier(Deck)</b></td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left"><?=$vesselinfo['MAX_ABOVE_TIERS'];?></td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right"><b>Max Tier(Hatch)</b></td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left"><?=$vesselinfo['MAX_BELOW_TIERS'];?></td>
			</tr>
		</table>
	</fieldset>
	</td>
	<td>&nbsp;</td>
	<td colspan="3" rowspan="2">
		<fieldset style="margin-left:80px; border: 3px solid #cca; -moz-border-radius: 10px; border: 1px solid #cca;">
		<legend>Preview Profile</legend>
				Test Profile
		</fieldset>
	</td>
</tr>
<tr>
	<td colspan="3">
	<fieldset style="margin-left:80px; border: 3px solid #cca; -moz-border-radius: 10px; border: 1px solid #cca;">
	<legend>Stowage Cell Size</legend>
	<form enctype="multipart/form-data" role="form" id="submit_form" action="<?=controller_?>vessel_profile" method="post">
		<table style="padding-left:10px;">
			<tr>
				<td class="form-field-caption" align="center" height="30" width="60"><b>Bay No</b></td>
				<td class="form-field-caption" align="center" height="30" width="70"><b>Cont<br/>Size</b></td>
				<td class="form-field-caption" align="center" height="30" width="80"><b>Deck</b></td>
				<td class="form-field-caption" align="center" height="30" width="80"><b>Hatch</b></td>
				<td class="form-field-caption" align="center" height="30" width="90"><b>Hatch<br/>Number</b></td>
				<td class="form-field-caption" align="center" height="30" width="90"><b>Occupy</b></td>
			</tr>
			<tr>
				<td><hr size="4" width="80%" noshade style="color:#000000" align="center" /></td>
				<td><hr size="4" width="80%" noshade style="color:#000000" align="center" /></td>
				<td><hr size="4" width="80%" noshade style="color:#000000" align="center" /></td>
				<td><hr size="4" width="80%" noshade style="color:#000000" align="center" /></td>
				<td><hr size="4" width="80%" noshade style="color:#000000" align="center" /></td>
				<td><hr size="4" width="80%" noshade style="color:#000000" align="center" /></td>
			</tr>
			<?php 
				$n = 1;
				foreach ($infobay as $rowbay) { 
					$baynumb = $rowbay['BAY'];					
					if ($baynumb % 2 == 0)
					{
						$sizecont="40";
					}
					else
					{
						$sizecont="20";
					}

					if ($rowbay['ABOVE'] == 'AKTIF')
					{
						$statAbv="Active";
					}
					else
					{
						$statAbv="Not Active";
					}

					if ($rowbay['BELOW'] == 'AKTIF')
					{
						$statBlw="Active";
					}
					else
					{
						$statBlw="Not Active";
					}

					if ($rowbay['HATCH_NUMBER'] == NULL)
					{
						$htNumb="-";
					}
					else
					{
						$htNumb=$rowbay['HATCH_NUMBER'];
					}
				?>
			<tr>
				<td class="form-field-caption" align="center"><?=$rowbay['BAY'];?><? if($rowbay['OCCUPY']=='Y'){ ?>&nbsp;<img src="<?=IMG_?>icons/edit.png" onclick="edit_bay_<?=$tab_id?>(<?=$rowbay['ID_BAY'];?>,<?=$rowbay['BAY'];?>,<?=$rowbay['MAX_ABOVE_ROWS'];?>,<?=$rowbay['MAX_ABOVE_TIERS'];?>,<?=$rowbay['MAX_BELOW_TIERS'];?>,<?=$rowbay['JML_ROW'];?>,<?=$rowbay['JML_TIER_UNDER'];?>,<?=$rowbay['JML_TIER_ON'];?>,'<?=$rowbay['ABOVE'];?>','<?=$rowbay['BELOW'];?>')" style="cursor:pointer;"/><? } ?></td>
				<td class="form-field-caption" align="center"><?=$sizecont;?></td>
				<td class="form-field-caption" align="center"><?=$statAbv;?></td>
				<td class="form-field-caption" align="center"><?=$statBlw;?></td>
				<td class="form-field-caption" align="center"><?=$htNumb;?><? if($rowbay['OCCUPY']=='Y'){ ?>&nbsp;<img src="<?=IMG_?>icons/edit.png" onclick="assign_hatch_<?=$tab_id?>(<?=$rowbay['ID_BAY'];?>,<?=$rowbay['BAY'];?>)" style="cursor:pointer;"/><? } ?></td>
				<td class="form-field-caption" align="center"><?=$rowbay['OCCUPY'];?> <img src="<?=IMG_?>icons/edit.png" onclick="edit_occupy_<?=$tab_id?>(<?=$rowbay['ID_BAY'];?>,<?=$rowbay['BAY'];?>)" style="cursor:pointer;"/></td>
			</tr>
			<?php $n++; } ?>
		</table>
		<br/>
		<div align="right">
			<!--<button type="button" onclick="submit_form()">
			Submit Stowage
			</button>-->
		</div>
	</form>
	</fieldset>
	</td>
</tr>
</table>
<div id="divProfile"></div>