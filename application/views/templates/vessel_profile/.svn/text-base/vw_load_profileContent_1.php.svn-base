<?
	$dShip=count($bay_label);
	$wdShip=$dShip*50;
	$dbay=$dShip*28;
	$lfBay=230;
	$lfBay2=$lfBay+$dbay;
?>

<div id="l2" style="position: relative; left:<?=$lfBay;?>px; top: 34px;height:150;z-index:1; margin-bottom:80px;" >

<table bordercolor="#037ACA" border="0" cellspacing="1" ">
<tbody>
	<tr>
		 <? foreach ($bay_label as $row)
		 	{  
		 		?>
					<td align="center" style="width:20px;height:10px;font-size:8px; font-family:Tahoma;"><? echo $row['BAY']; ?></td>
			  <?
				} 
			  ?>
	</tr>
	<!---------------------------- ABOVE -------------------------------->
	<tr>
		 <? 
			$idy=0;
			foreach ($profile_abv as $row){
			$idy++;
			if ($row['ABOVE'] == 'AKTIF')
			{
				$jmltieron=$row['JML_TIER_ON'];
			?>
					<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF;" ><a href="javascript:edit_bay_profile_<?=$tab_id?>(<?=$row['ID_BAY'];?>)"><img src="images/vessel_profile/stack_bay_<?=$jmltieron;?>.png" height="50" width="25"/></a></td>
				<? }
				else if($row['ABOVE'] == 'NONE')
				{
				?>
				<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF; "><img src="images/vessel_profile/disable_bay_<?=$jmltieron;?>.png" height="50" width="25"/></td>
				<?
				}
				else {
					$idx[$idy]=1;
				?>
					<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF; "><a href="javascript:info_bay('<?=$id?>','<?echo $row['BAY']?>','<?=$row['ID']?>','<? if ($row['OCCUPY']=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else { ?><? echo $row['BAY'];?><? } ?>','DECK')"><img src="images/vessel_profile/stack_bay_full.png" title="INBOUND OUTBOUND" height="50" width="25" /></a></td>
			  <? } 
				} ?>
	</tr>
	<!---------------------------- ABOVE -------------------------------->
	<tr>
		 <?	$jml_bay = count($bay_label); ?>
				<td colspan="<?=$jml_bay;?>" align="center" style="width:20px;height:3px;  background-color:#444343;"></td>
	</tr>
	<!---------------------------- BELOW -------------------------------->
	<tr>
		 <? foreach ($profile_blw as $row){
 			$idy++;
			if($row['BELOW'] == 'AKTIF')
			{
				$jmltierunder=$row['JML_TIER_UNDER'];
			?>
				<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF; "><a href=javascript:edit_bay_profile_<?=$tab_id?>(<?=$row['ID_BAY'];?>)><img src="images/vessel_profile/stack_bay_<?=$jmltierunder;?>.png" height="50" width="25"/></a></td>
			<? 
			}
			else if($row['BELOW'] == 'NONE')
			{
			?>
				<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF; "><img src="images/vessel_profile/disable_bay_<?=$jmltierunder;?>.png" height="50" width="25"/></td>
			<?
			}
			else { $idx[$idy]=1;?>
				
					<td align="center" style="width:20px;height:50px;font-size:8px; font-family:Tahoma; border:0px solid #000000;background-color:#FFFFFF; "><a href="javascript:info_bay('<?=$id?>','<?echo $row['BAY']?>','<?=$row['ID']?>','<? if ($row['OCCUPY']=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else { ?><? echo $row['BAY'];?><? } ?>','HATCH')"><img src="images/vessel_profile/stack_bay_full.png" title="INBOUND OUTBOUND" height="50" width="25" /></a></td>
			  <? } 
				} ?>
	</tr>
	<!---------------------------- BELOW -------------------------------->
</tbody>
</table>

</div>
<div id="l1" style="position: absolute;top:20px;left:<?=$lfBay2;?>px; transform: rotateY(180deg);"><img src="images/vessel_profile/vespoburitan.png" height="219px"></div>
<div id="l3" style="position: absolute;top:20px;left:60px; transform: rotateY(180deg);"><img src="images/vessel_profile/vesprohaluan.png" height="219px"></div>
<div id="l4" style="position: absolute;top:158px;left:<?=$lfBay;?>px;background-color:#950000;height:60px;width:<?=$dbay;?>px;"></div>
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
			<? 
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
			<? $n++; } ?>
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