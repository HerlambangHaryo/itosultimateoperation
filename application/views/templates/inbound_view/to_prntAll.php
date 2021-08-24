<?php
	$size = 20;
?>

<style type="text/css">
.selectable_<?=$tab_id?> .ui-selecting { background: #FECA40; }
.selectable_<?=$tab_id?> .ui-selected { background: #F39814; color: white; }
.selectable_<?=$tab_id?> { list-style-type: none; margin: 0; padding: 0; }
.selectable_<?=$tab_id?> li {float: left;width: <?php echo $size."px"?>; height: <?php echo $size."px"?>; text-align: center; line-height:<?=$size?>px;}
div.grid_<?=$tab_id?> {
	position: absolute;
}
li {
	border:solid 1px #ffffff; 
}
.ui-state-default{
	all:none;
}
.ui-state-default2{
	background-color:#ffffff !important;
	border:solid 1px #000000; 
	border-spacing: 0;
	padding: 0px;
	box-shadow:0 1px 2px #616161,inset 0 -1px 1px rgba(0,0,0,0.1),inset 0 1px 1px rgba(255,255,255,0.8);
	-webkit-print-color-adjust: exact; 
}

.uiMutih{
	border:solid 1px #ffffff; 	
}

.ui-stacking-default{
		background-color:#f6e23c !important;
		border:solid 1px #000000; 
		border-spacing: 0;
		padding: 0px;
		box-shadow:0 1px 2px #616161,inset 0 -1px 1px rgba(0,0,0,0.1),inset 0 1px 1px rgba(255,255,255,0.8);
		-webkit-print-color-adjust: exact; 
	}

.tulisanBay{
	font-size:10pt;font-family: calibri, serif;
	padding:5px;
	background-color: #3a5795 !important; 
	color: #FFFFFF !important; 
	margin-top:0px;
}
</style>

<span id="select-result_<?=$tab_id?>" style="display: none;"></span>
<span id="result_<?=$tab_id?>" style="display: none;"></span>
<span id="bay_id_<?=$tab_id?>" style="display: none;"></span>
<span id="deck_hatch_<?=$tab_id?>" style="display: none;"></span>
<input id="id_ves_voyage_<?=$tab_id?>" type="hidden" value="<?=$id_ves_voyage?>"></input>

<span id="bay_id_before_<?=$tab_id?>" style="display: none;"></span>
<span id="deck_hatch_before_<?=$tab_id?>" style="display: none;"></span>
<font face="calibri"><b>Vessel : </b><?=$vesselLD['VSVY'];?></font>
<hr/>
<center>
	<div class="grid_<?=$tab_id?>">
	<table border="0" width="100%">
		<tr align="center">
	<?php
		foreach($bay_area as $bay){
			$n = -2;
	?>
	<?php
			if ($bay['BAY']%2!=0){
	?>
		<td align="center">
			<table style="width: <?=($bay["JML_ROW"]+1)*$size+30?>px;" >
				<tr>
					<td colspan="<?=$bay["JML_ROW"]+1?>" align="left">
					  <div style="font-size:10pt;font-family: calibri, serif;padding:5px;background-color: #3a5795 !important; color: #FFFFFF; margin-top:0px;">Bay <?=$bay["BAY"]?></div>
					</td>
				</tr>
				<tr>
				<?php
					$odd = false;
					if( ($bay["JML_ROW"] % 2) == 0){
						$start = $bay["JML_ROW"];
					}else{
						$odd = true;
						$start = $bay["JML_ROW"] - 1;
					}
					
					for($j = 1; $j <= $bay["JML_ROW"]; $j++){
				?>
					<td width="<?=$size;?>" class="uiMutih">
						<center style="font-size:10px;"><?=str_pad($start,2,'0',STR_PAD_LEFT)?></center>
					</td>
				<?php
						if (($start + $n) == 0){
							if ($odd){
								$start = $start + $n;
							}else{
								$n = $n * -1;
								$start = 1;
							}
						}else if (($start + $n) < 0){
							$n = $n * -1;
							$start = 1;
						}else{
							$start = $start + $n;
						}
					}
				?>
					<td>
					</td>
				</tr>
				<tr>
					<td colspan='<?=$bay["JML_ROW"]+1?>'>
				<?php
				if ($bay['ABOVE']=='AKTIF'){
				?>
						<ol class="selectable_<?=$tab_id?>">
				<?php
				$index = 0;
				$bay_cell = $this->vessel->get_vessel_profile_cellInfo($id_ves_voyage, $class_code, $ID_VESSEL, $bay['ID_BAY'], $bay['BAY'], 'ABOVE');
				for($j = 1; $j <= $bay["JML_TIER_ON"]; $j++){
						for($s = 1; $s <= $bay["JML_ROW"]; $s++){
							$cell = $bay_cell[$index];
				?>
						<li <?php if ($cell['NO_CONTAINER']!=''){ ?> no_container="<?=$cell['NO_CONTAINER']?>" point="<?=$cell['POINT']?>" cont_size="<?=$cell['CONT_SIZE']?>" title="<?=$cell['NO_CONTAINER']?>" <?php if($cell['ID_CLASS_CODE']=='TC'){ ?> class="ui-placement-default" <?php }else{ ?> <?php if ($cell['CONT_40_LOCATION']==''){ ?> <?php if ($cell['SEQUENCE']!=''){ ?> <?php if($cell['STATUS']=='P'){ ?> class="ui-plan-default" <?php }else{ ?> class="ui-placement-default" <?php } ?> <?php }else{ ?> class="ui-stacking-default" <?php } ?> <?php }else{ ?> class="uiUnAvb" <?php } ?> <?php } ?>  <?php }else if($cell['STATUS_STACK']!='A'){ ?> class="uiMutih" <?php }else{ ?> class="ui-state-default2" <?php }?> id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="D" 
						<?php if($cell['STATUS_STACK']!='X'){ ?><? } ?> ><?php if ($cell['CONT_40_LOCATION']==''){ ?> <?php if ($cell['SEQUENCE']!=''){ if ($cell['STATUS']=='P') {echo $cell['SEQUENCE'];} else {echo "C";} } ?> <?php }else{ ?>  <?php }?>
						</li>
					<?php
							$index++;
							//($cell['STATUS']=='P')
						}
					?>
						<li style="font-size:10px;"><?=$cell["TIER_"]?></li>
				<?php
				}
				?>
						</ol>
				<?php
				}
				?>
					</td>
				</tr>
				<tr>
					<td title="cover" colspan="<?=$bay["JML_ROW"]?>" style="background:#3a5795" height="5px"> </td>
					<td width="<?=$size?>px"></td>
				</tr>
				
				<tr>
					<td colspan='<?=$bay["JML_ROW"]+1?>'>
				<?php
				if ($bay['BELOW']=='AKTIF'){
				?>
						<ol class="selectable_<?=$tab_id?>">
				<?php
				$index = 0;
				$bay_cell = $this->vessel->get_vessel_profile_cellInfo($id_ves_voyage, $class_code, $ID_VESSEL, $bay['ID_BAY'], $bay['BAY'], 'BELOW');
				for($j = 1; $j <= $bay["JML_TIER_UNDER"]; $j++){
						for($s = 1; $s <= $bay["JML_ROW"]; $s++){
							$cell = $bay_cell[$index];
				?>
						<li <?php if ($cell['NO_CONTAINER']!=''){ ?> no_container="<?=$cell['NO_CONTAINER']?>" point="<?=$cell['POINT']?>" cont_size="<?=$cell['CONT_SIZE']?>" title="<?=$cell['NO_CONTAINER']?>" <?php if($cell['ID_CLASS_CODE']=='TC'){ ?> class="ui-placement-default" <?php }else{ ?> <?php if ($cell['CONT_40_LOCATION']==''){ ?> <?php if ($cell['SEQUENCE']!=''){ ?> <?php if($cell['STATUS']=='P'){ ?> class="ui-plan-default" <?php }else{ ?> class="ui-placement-default" <?php } ?> <?php }else{ ?> class="ui-stacking-default" <?php } ?> <?php }else{ ?> class="uiUnAvb" <?php } ?> <?php } ?> <?php }else if($cell['STATUS_STACK']!='A'){ ?> class="uiMutih" <?php }else{ ?> class="ui-state-default2" <?php } ?> id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="H" 
						<?php if($cell['STATUS_STACK']!='X'){ ?><? } ?> ><?php if ($cell['CONT_40_LOCATION']==''){ ?> <?php if ($cell['SEQUENCE']!=''){ if ($cell['STATUS']=='P') {echo $cell['SEQUENCE'];} else {echo "C";} } ?> <?php }else{ ?>  <?php } ?></li>
					<?php
							$index++;
						
						}
					?>
						<li style="font-size:10px;"><?=str_pad($cell["TIER_"],2,'0',STR_PAD_LEFT)?></li>
				<?php
				}
				?>
						</ol>
				<?php
				}
				?>
					</td>
				</tr>
			</table>
		</td>
	<?php
			}
	?>
	<?php
		}
	?>
		</tr>
	</table>
	
	<table border="0">
		<tr align="center">
	<?php
		foreach($bay_area as $bay){
			$n = -2;
	?>
	<?php
			if ($bay['BAY']%2==0){
	?>
		<td align="center">
			<table style="width: <?=($bay["JML_ROW"]+1)*$size+30?>px;" >
				<tr>
					<td colspan="<?=$bay["JML_ROW"]+1?>" align="left">
					  <div class="tulisanBay">Bay <?=$bay["BAY"]?></div>
					</td>
				</tr>
				<tr>
				<?php
					$odd = false;
					if( ($bay["JML_ROW"] % 2) == 0){
						$start = $bay["JML_ROW"];
					}else{
						$odd = true;
						$start = $bay["JML_ROW"] - 1;
					}
					
					for($j = 1; $j <= $bay["JML_ROW"]; $j++){
				?>
					<td>
						<center style="font-size:10px;"><?=str_pad($start,2,'0',STR_PAD_LEFT)?></center>
					</td>
				<?php
						if (($start + $n) == 0){
							if ($odd){
								$start = $start + $n;
							}else{
								$n = $n * -1;
								$start = 1;
							}
						}else if (($start + $n) < 0){
							$n = $n * -1;
							$start = 1;
						}else{
							$start = $start + $n;
						}
					}
				?>
					<td>
					</td>
				</tr>
				<tr>
					<td colspan='<?=$bay["JML_ROW"]+1?>'>
				<?php
				if ($bay['ABOVE']=='AKTIF'){
				?>
						<ol class="selectable_<?=$tab_id?>">
				<?php
				$index = 0;
				$bay_cell = $this->vessel->get_vessel_profile_cellInfo($id_ves_voyage, $class_code, $ID_VESSEL, $bay['ID_BAY'], $bay['BAY'], 'ABOVE');
				for($j = 1; $j <= $bay["JML_TIER_ON"]; $j++){
						for($s = 1; $s <= $bay["JML_ROW"]; $s++){
							$cell = $bay_cell[$index];
				?>
						<li <?php if ($cell['NO_CONTAINER']!=''){ ?> no_container="<?=$cell['NO_CONTAINER']?>" point="<?=$cell['POINT']?>" cont_size="<?=$cell['CONT_SIZE']?>" title="<?=$cell['NO_CONTAINER']?>" <?php if($cell['ID_CLASS_CODE']=='TC'){ ?> class="ui-placement-default" <?php }else{ ?> <?php if ($cell['CONT_40_LOCATION']==''){ ?> <?php if ($cell['SEQUENCE']!=''){ ?> <?php if($cell['STATUS']=='P'){ ?> class="ui-plan-default" <?php }else{ ?> class="ui-placement-default" <?php } ?> <?php }else{ ?> class="ui-stacking-default" <?php } ?> <?php }else{ ?> class="ui-placement-default" <?php } ?> <?php } ?>  <?php }else if($cell['STATUS_STACK']!='A'){ ?> class="uiMutih" <?php }else{ ?> class="ui-state-default2" <?php }?> id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="D" 
						<?php if($cell['STATUS_STACK']!='X'){ ?>style="box-shadow:0 1px 2px #616161,inset 0 -1px 1px rgba(0,0,0,0.1),inset 0 1px 1px rgba(255,255,255,0.8);"<? } ?> ><?php if ($cell['CONT_40_LOCATION']==''){ ?> <?php if ($cell['SEQUENCE']!=''){ if ($cell['STATUS']=='P') {echo $cell['SEQUENCE'];} else {echo "C";} } ?> <?php }else{ ?> 40 <?php }?>
		  
		  </li>
					<?php
							$index++;
						}
					?>
						<li style="font-size:10px;"><?=$cell["TIER_"]?></li>
				<?php
				}
				?>
						</ol>
				<?php
				}
				?>
					</td>
				</tr>
				<tr>
					<td title="cover" colspan="<?=$bay["JML_ROW"]?>" style="background:#3a5795" height="5px"> </td>
					<td width="<?=$size?>px"></td>
				</tr>
				
				<tr>
					<td colspan='<?=$bay["JML_ROW"]+1?>'>
				<?php
				if ($bay['BELOW']=='AKTIF'){
				?>
						<ol class="selectable_<?=$tab_id?>">
				<?php
				$index = 0;
				$bay_cell = $this->vessel->get_vessel_profile_cellInfo($id_ves_voyage, $class_code, $ID_VESSEL, $bay['ID_BAY'], $bay['BAY'], 'BELOW');
				for($j = 1; $j <= $bay["JML_TIER_UNDER"]; $j++){
						for($s = 1; $s <= $bay["JML_ROW"]; $s++){
							$cell = $bay_cell[$index];
				?>
						<li <?php if ($cell['NO_CONTAINER']!=''){ ?> no_container="<?=$cell['NO_CONTAINER']?>" point="<?=$cell['POINT']?>" cont_size="<?=$cell['CONT_SIZE']?>" title="<?=$cell['NO_CONTAINER']?>" <?php if($cell['ID_CLASS_CODE']=='TC'){ ?> class="ui-placement-default" <?php }else{ ?> <?php if ($cell['CONT_40_LOCATION']==''){ ?> <?php if ($cell['SEQUENCE']!=''){ ?> <?php if($cell['STATUS']=='P'){ ?> class="ui-plan-default" <?php }else{ ?> class="ui-placement-default" <?php } ?> <?php }else{ ?> class="ui-stacking-default" <?php } ?> <?php }else{ ?> class="ui-placement-default" <?php } ?> <?php } ?> <?php }else if($cell['STATUS_STACK']!='A'){ ?> class="uiMutih" <?php }else{ ?> class="ui-state-default2" <?php } ?> id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="H" 
						<?php if($cell['STATUS_STACK']!='X'){ ?><? } ?> ><?php if ($cell['CONT_40_LOCATION']==''){ ?> <?php if ($cell['SEQUENCE']!=''){ if ($cell['STATUS']=='P') {echo $cell['SEQUENCE'];} else {echo "C";} } ?> <?php }else{ ?> 40 <?php } ?></li>
					<?php
							$index++;
						}
					?>
						<li style="font-size:10px;"><?=str_pad($cell["TIER_"],2,'0',STR_PAD_LEFT)?></li>
				<?php
				}
				?>
						</ol>
				<?php
				}
				?>
					</td>
				</tr>
			</table>
		</td>
	<?php
			}
	?>
	<?php
		}
	?>
		</tr>
	</table>
	</div>
</center>

