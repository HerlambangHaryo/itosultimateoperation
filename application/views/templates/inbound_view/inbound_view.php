<?php
	$size = 35; //20
?>

<style>
.selectable_<?=$tab_id?> .ui-selecting { background: #FECA40; }
.selectable_<?=$tab_id?> .ui-selected { background: #F39814 !important; color: white; }
.selectable_<?=$tab_id?> { list-style-type: none; margin: 0; padding: 0; }
.selectable_<?=$tab_id?> li {float: left; width: <?php echo $size."px"?>; height: <?php echo $size."px"?>; text-align: center; line-height:<?=$size?>px;}
div.grid_<?=$tab_id?> {
	position: absolute;
}

.grid_<?=$tab_id?> .palka {
    background:#3a5795;
    height: 5px;
    float: left;
}

.corner-left-label{
    left: 0;
    top: -1px;
    font-size: <?=floor($konstantabox / 4)?>px;
    position: absolute;
}

.display-none  {
	display: none;
}

.boxTable{
	background: white url(./excite-bike/images/MUTIH.png)  50% 50% repeat;
	color: #3b3b3b;
	font-size:10px;
	text-align: center;
	width: 35px;
}

.simbil{
	font-size: xx-small;
}
.spacer{
	padding-bottom: 5px;
}

.div-job-seq{
    float: left;
    height: 18px;
    margin-top: -8px;
    width: 10px;
}

.div-tl-simbol{
    float: right;
    height: 18px;
    margin-top: -8px;
    width: 10px;
}
</style>

<script>
$(function() {
	var sequence = 1;
	$( ".selectable_<?=$tab_id?>" ).selectable({
		filter: ".ui-stacking-default, .ui-plan-default",
		start: function( event, ui ) {
			$( "#bay_id_<?=$tab_id?>" ).empty();
			$( "#deck_hatch_<?=$tab_id?>" ).empty();
		},
		selecting: function( event, ui ) {
			var id_bay_cur = $(ui.selecting).attr('id_bay');
			var deck_hatch_cur = $(ui.selecting).attr('deck_hatch');
			
			var id_bay_before = $( "#bay_id_before_<?=$tab_id?>" ).html();
			var deck_hatch_before = $( "#deck_hatch_before_<?=$tab_id?>" ).html();
			
			// console.log(id_bay_cur+'='+id_bay_before+' - '+deck_hatch_cur+'='+deck_hatch_before);
			if (id_bay_cur!=id_bay_before || deck_hatch_cur!=deck_hatch_before){
				$( ".selectable_<?=$tab_id?> .ui-selected" ).removeAttr('sequence');
				$( ".selectable_<?=$tab_id?> .ui-selected" ).removeClass('ui-selected');
				sequence = 1;
			}
			$(ui.selecting).attr('sequence',sequence);
			sequence = sequence + 1;
			$( "#bay_id_before_<?=$tab_id?>" ).html($(ui.selecting).attr('id_bay'));
			$( "#deck_hatch_before_<?=$tab_id?>" ).html($(ui.selecting).attr('deck_hatch'));
		},
		unselecting: function( event, ui ) {
			$(ui.selecting).removeAttr('sequence');
			sequence = sequence - 1;
		},
		selected: function(event, ui) {
			if ($( "#bay_id_<?=$tab_id?>" ).html()==""){
				$( "#bay_id_<?=$tab_id?>" ).append(
					$(ui.selected).attr('id_bay')
				);
			}
			if ($( "#deck_hatch_<?=$tab_id?>" ).html()==""){
				$( "#deck_hatch_<?=$tab_id?>" ).append(
					$(ui.selected).attr('deck_hatch')
				);
			}
		},
		stop: function(event, ui) {
			sequence = 1;
			$( "#select-result_<?=$tab_id?>" ).empty();
			$( "#result_<?=$tab_id?>" ).empty();
			var selected_el = $(".ui-stacking-default.ui-selected, .ui-plan-default.ui-selected");
			for (var index = 0; index < selected_el.length; ++index) {
				if ($( "#select-result_<?=$tab_id?>" ).html()!=""){
					$( "#select-result_<?=$tab_id?>" ).append(",");
				}
				if ($( "#result_<?=$tab_id?>" ).html()!=""){
					$( "#result_<?=$tab_id?>" ).append(",");
				}
				
				$( "#select-result_<?=$tab_id?>" ).append(
					$(selected_el[index]).attr('no_container')+"-"+$(selected_el[index]).attr('point')+"-"+$(selected_el[index]).attr('bay')+"-"+$(selected_el[index]).attr('row')+"-"+$(selected_el[index]).attr('tier')+"-"+$(selected_el[index]).attr('id_cell')+"-"+$(selected_el[index]).attr('sequence')
				);
				$( "#result_<?=$tab_id?>" ).append(
					$(selected_el[index]).attr('id_cell')
				);
				sequence += 1;
			}
			
			// console.log($( "#select-result_<?=$tab_id?>" ).html());
		}
	});
	
	$.contextMenu({
		selector: ".selectable_<?=$tab_id?> .ui-stacking-default.ui-selected",
		items: {
			"set": {
				name: "Set Sequence", 
				icon: "edit", 
				callback: function(key, options) {
					setSequence_<?=$tab_id?>($("#id_ves_voyage_<?=$tab_id?>").val(), $("#bay_id_<?=$tab_id?>").html(), $("#deck_hatch_<?=$tab_id?>").html());
				}
			},
			"sep1": "---------",
			"quit": {
				name: "Quit",
				icon: "quit",
				callback: function(key, options) {
					$(this).contextMenu("hide");
				}
			},
			"sep2": "---------",
			"inquiry": {
				name: "Container Inquiry",
				icon: "search",
				callback: function(key, options) {
					contInquiry_<?=$tab_id?>(this);
				}
			}
		}
	});
	
	$.contextMenu({
		selector: ".selectable_<?=$tab_id?> .ui-plan-default.ui-selected",
		items: {
			"unset": {
				name: "Unset Sequence",
				icon: "delete",
				callback: function(key, options) {
					unsetSequence_<?=$tab_id?>($("#id_ves_voyage_<?=$tab_id?>").val(), $("#bay_id_<?=$tab_id?>").html(), $("#deck_hatch_<?=$tab_id?>").html());
				}
			},
			"sep1": "---------",
			"quit": {
				name: "Quit",
				icon: "quit",
				callback: function(key, options) {
					$(this).contextMenu("hide");
				}
			},
			"sep2": "---------",
			"inquiry": {
				name: "Container Inquiry",
				icon: "search",
				callback: function(key, options) {
					contInquiry_<?=$tab_id?>(this);
				}
			}
		}
	});
	
	$.contextMenu({
		selector: ".selectable_<?=$tab_id?> .ui-box-container",
		items: {
			"inquiry": {
				name: "Container Inquiry",
				icon: "search",
				callback: function(key, options) {
					contInquiry_<?=$tab_id?>(this);
				}
			}
		}
	});
	
	$.contextMenu({
		selector: ".selectable_<?=$tab_id?> .ui-placement-default",
		items: {
			"inquiry": {
				name: "Container Inquiry",
				icon: "search",
				callback: function(key, options) {
					contInquiry_<?=$tab_id?>(this);
				}
			}
		}
	});
	
});
</script>

<span id="select-result_<?=$tab_id?>" style="display: none;"></span>
<span id="result_<?=$tab_id?>" style="display: none;"></span>
<span id="bay_id_<?=$tab_id?>" style="display: none;"></span>
<span id="deck_hatch_<?=$tab_id?>" style="display: none;"></span>
<input id="id_ves_voyage_<?=$tab_id?>" type="hidden" value="<?=$id_ves_voyage?>"></input>

<span id="bay_id_before_<?=$tab_id?>" style="display: none;"></span>
<span id="deck_hatch_before_<?=$tab_id?>" style="display: none;"></span>

<center>
	<div class="grid_<?=$tab_id?>">
	<table border="0" width="100%">
		<tr align="center">
	<?php
		$count_bay = 0;
		foreach($bay_area as $bay){
			$n = -2;
			$status_row_hidden = array();
			$colspan_row = 1;
			$tier = array();
			$status_tier_hid = false;
	?>
	<?php
			if ($bay['BAY']%2!=0){
	?>
		<td align="center" id="bay_view_<?=$tab_id?>_<?=$bay['BAY']?>">
			<table style="width: <?=($bay["JML_ROW"]+1)*$size+30?>px;" frame="box"> <!-- +20 -->
				<tr>
					<?php
						if ($count_bay==0 || $bay_area[$count_bay-1]["BAY"]%2!=0){
					?>
					<td colspan="<?=$bay["JML_ROW"]+1?>" align="center">
					    <div style="float: left; width: 15px;">
						<?php 
						if($vessel['ALONG_SIDE'] == 'P'){ 
						    echo 'L';
						}else{
						    echo 'W';
						}
						?>
					    </div>
					    <div style="float: left; width:calc(100% - 32px);">
						<div style="text-align: center; width: 40px;">
						    <h1 style="background-color: #3a5795; color: #FFFFFF; margin-top:0px;"><?=$bay["BAY"]?></h1>
						</div>
					    </div>
					    <div style="float: right; width: 15px;">
						<?php 
						if($vessel['ALONG_SIDE'] == 'P'){ 
						    echo 'W';
						}else{
						    echo 'L';
						}
						?>
					    </div>
					</td>
					<?php
						}else{
							if (($bay["JML_ROW"]+1)%2==0){
								$colspan_left = ($bay["JML_ROW"]+1)/2;
								$colspan_right = ($bay["JML_ROW"]+1)/2;
							}else{
								$colspan_left = ($bay["JML_ROW"])/2;
								$colspan_right = ($bay["JML_ROW"]/2)+1;
							}
					?>
					<td colspan="<?=$colspan_left?>" align="right">
					    <div style="float: left; width: 15px;">
						<?php 
						if($vessel['ALONG_SIDE'] == 'P'){ 
						    echo 'L';
						}else{
						    echo 'W';
						}
						?>
					    </div>
					    <div style="float: left; width: calc(100% - 17px)">
						<div style="width:40px;" align="center"><h1 style="background-color: #ffffff; color: #3a5795; margin-top:0px; cursor: pointer;" onclick="switchBayView_<?=$tab_id?>('<?=$bay_area[$count_bay-1]["BAY"]?>');"><?=$bay_area[$count_bay-1]["BAY"]?></h1></div>
					    </div>  
					</td>
					<td colspan="<?=$colspan_right?>" align="left">
					    <div style="float: left; width: calc(100% - 17px)">
						<div style="width:40px;" align="center"><h1 style="background-color: #3a5795; color: #FFFFFF; margin-top:0px;"><?=$bay["BAY"]?></h1></div>
					    </div>
					    <div style="float: right; width: 15px;">
						<?php 
						if($vessel['ALONG_SIDE'] == 'P'){ 
						    echo 'W';
						}else{
						    echo 'L';
						}
						?>
					    </div>
					</td>
					<?php
						}
					?>
				</tr>
				<tr style="border-style: solid;">
				<?php
					$odd = false;
					if( ($bay["JML_ROW"] % 2) == 0){
						$start = $bay["JML_ROW"];
					}else{
						$odd = true;
						$start = $bay["JML_ROW"] - 1;
					}
					$status_row_hid = false;
					for($j = 1; $j <= $bay["JML_ROW"]; $j++){
						$row = str_pad($start,2,'0',STR_PAD_LEFT);
					 	$count_row = $this->vessel->get_count_row($ID_VESSEL, $bay['ID_BAY'],$row);
						 if($count_row->JML < 1){
							 $status_row_hid = true;
						 } else {
							 $colspan_row++;
							 $status_row_hid = false;
						 }
						array_push($status_row_hidden, $status_row_hid);

				?>
					<td style="padding: 0px; width:25px" class="boxTable <?=($count_row->JML < 1) ? 'display-none' : '' ?>">
						<?=$row?>
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
					<td width="<?=$size;?>">
					</td>
				</tr>
				<tr>
					<td colspan='<?=$colspan_row?>' check="AA">
				<?php
				if ($bay['ABOVE']=='AKTIF'){
				?>
						<ol class="selectable_<?=$tab_id?>">
				<?php
				$index = 0;
				$bay_cell = $this->vessel->get_vessel_profile_cellInfo($id_ves_voyage, $class_code, $ID_VESSEL, $bay['ID_BAY'], $bay['BAY'], 'ABOVE');
				//debux($bay_cell);
				
				for($j = 1; $j <= $bay["JML_TIER_ON"]; $j++){
						for($s = 1; $s <= $bay["JML_ROW"]; $s++){
							$cell = $bay_cell[$index];
							 
							if(!in_array($cell['TIER_'], $tier)){
								$count_tier =  $this->vessel->get_count_tier($ID_VESSEL, $bay['ID_BAY'],$cell['TIER_']);
								$status_tier_hid = ($count_tier->JML < 1) ? true : false;
								array_push($tier, $cell['TIER_']);
							}

							if ($cell['CONT_40_LOCATION']==''){ 
							    if($cell['ID_CLASS_CODE']=='TC'){  
								$cls="ui-placement-disabled"; 
							    }else{
								if ($cell['SEQUENCE']!=''){ 
								    if ($cell['STATUS']=='P'){  
									   $cls="ui-plan-default";
								    }else{ 
									       $cls="ui-placement-default"; 
								    } 
								}else{ 
									       $cls="ui-stacking-default"; 
								} 
							    }
							}else{ 
							       //echo "tes";
								       $cls='uiUnAvb';

							}   
					?>

						<li 

						    <?php if ($cell['NO_CONTAINER']!=''){ ?> 
							no_container="<?=$cell['NO_CONTAINER']?>" 
							point="<?=$cell['POINT']?>" 
							cont_size="<?=$cell['CONT_SIZE']?>" 
							title="<?=$cell['NO_CONTAINER']?><?php echo ($cell['TL_FLAG']=='Y') ? ' - TL' : ''; ?>" 
								class="ui-box-container <?=$cls?>"
							<?php }else if($cell['STATUS_STACK']!='A'){ ?> class="uiMutih <?=($status_row_hidden[$s-1] || $status_tier_hid) ? 'display-none' : '' ?>" <?php }
							else{ ?> class="ui-state-default" <?php }?> id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="D" 
							<?php if($cell['STATUS_STACK']!='X'){ ?>style="box-shadow:0 1px 2px #616161,inset 0 -1px 1px rgba(0,0,0,0.1),inset 0 1px 1px rgba(255,255,255,0.8); 
							<?php if($cell['CONT_40_LOCATION']==''){
								if($cell['BACKGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
								    background: #<?=$cell['BACKGROUND_COLOR']?>;
								<?php }
								if($cell['FOREGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
								    color: #<?=$cell['FOREGROUND_COLOR']?>;
								<?php }
							    }?>
							"<? } ?> >
							<div class="simbil">
								<?php
									if($cell['CONT_TYPE']=='HQ'){
									?>
								<div class="div-tl-simbol">
									&#9701;
								</div>	
							<?php
								} 
									if($cell['TL_FLAG']=='Y'){ ?>
									<div class="div-tl-simbol">
										&#9660;
									</div>	
								<?php }else if($cell['HAZARD']=='Y'){ ?>
									<div class="corner-left-label">&#9674;</div>
								<?php } ?>

							    <?php if ($cell['CONT_40_LOCATION']==''){ ?> 
							    	<?php if ($cell['SEQUENCE']!=''){ ?>
										<div class="div-job-seq">
											<?php if ($cell['STATUS']=='P') {
											    echo $cell['SEQUENCE'];
										    } else {
											    echo 'C ';
										    } ?>
									    </div>
									<?php } ?>
								<?php if($cell['HAZARD'] == 'Y'){
									echo 'H';
								}else{
									if($filter == 'SIZE'){
										echo $cell['CONT_SIZE'];
									}else if($filter == 'WEIGHT'){
										echo $cell['WEIGHT'];
									}else if($filter == 'OPERATOR'){ 
										echo $cell['ID_OPERATOR'];
									}else{
										echo $cell['ID_COMMODITY'];
									} 
								}

								} ?>
							</div>
					</li>
					<?php
							$index++;
							//($cell['STATUS']=='P')
						}
					?>
						<li style="font-size:10px;" class="<?=($status_tier_hid) ? 'display-none' : ''?>"><?=$cell["TIER_"]?></li>
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
				    <td title="cover" colspan="<?=$bay["JML_ROW"]?>" style="height: 2px;" >
					<?php if($bay["HATCH_NUMBER"]>=1){
						for($tt=1;$tt<=$bay["HATCH_NUMBER"];$tt++){
						    if($tt > 1){
					?>
					<div style="height: 5px; width: 3px; float: left">&nbsp;</div>
					<?php
						    }
						
					?>
							<!--<td title="cover" colspan="<?=$bay["JML_ROW"]/$bay["HATCH_NUMBER"]?>" style="background:#3a5795;padding-left:30px;" height="5px" > </td>-->
					<div class="palka" style="width: calc(<?=100/$bay["HATCH_NUMBER"]?>% - 2px)">&nbsp;</div>
					<?php
						}
					    }
					?>
				     </td>
				    <td width="<?=$size?>px"></td>
				</tr>
				
				<tr>
					<td colspan='<?=$colspan_row?>' check="BB">
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
							 
							if(!in_array($cell['TIER_'], $tier)){
								$count_tier =  $this->vessel->get_count_tier($ID_VESSEL, $bay['ID_BAY'],$cell['TIER_']);
								$status_tier_hid = ($count_tier->JML < 1) ? true : false;
								array_push($tier, $cell['TIER_']);
							}
				?>
						<li <?php if ($cell['NO_CONTAINER']!=''){ ?> 
						    no_container="<?=$cell['NO_CONTAINER']?>" 
						    point="<?=$cell['POINT']?>" 
						    cont_size="<?=$cell['CONT_SIZE']?>" 
						    title="<?=$cell['NO_CONTAINER']?><?php echo ($cell['TL_FLAG']=='Y') ? ' - TL' : ''; ?>" 
						    class="ui-box-container
							<?php  
							    if ($cell['CONT_40_LOCATION']==''){
								if($cell['ID_CLASS_CODE']=='TC'){
								    echo ' ui-placement-disabled ';
								}else{
								    if ($cell['SEQUENCE']!=''){ 
									if($cell['STATUS']=='P'){ ?> 
									    ui-plan-default 
								<?php }else{ ?> 
									ui-placement-default 
								<?php } ?> 
							<?php	    }else{ ?> 
								    ui-stacking-default 
							<?php	    }
								}
							    }else{ ?> 
								    uiUnAvb 
							<?php } ?>"
							<?php
							}else if($cell['STATUS_STACK']!='A'){ ?> 
								    class="uiMutih <?=($status_row_hidden[$s-1] || $status_tier_hid) ? 'display-none' : '' ?>" 
							<?php }else{ ?> 
								class="ui-state-default" <?php } ?> id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="H" 
							<?php if($cell['STATUS_STACK']!='X'){ ?>
									style="box-shadow:0 1px 2px #616161,inset 0 -1px 1px rgba(0,0,0,0.1),inset 0 1px 1px rgba(255,255,255,0.8);
							    <?php if($cell['BACKGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
								    background: #<?=$cell['BACKGROUND_COLOR']?>;
							    <?php } ?>
							    <?php if($cell['FOREGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
								    color: #<?=$cell['FOREGROUND_COLOR']?>;
							    <?php } ?>"
							<?php } ?> >
							<?php //debux($cell); ?>
							<div class="simbil">
								<?php
								if($cell['CONT_TYPE']=='HQ'){
										?>
									<div class="div-tl-simbol">
										&#9701;
									</div>	
										<?php
								}
								if($cell['TL_FLAG']=='Y'){
										?>
								    <div class="div-tl-simbol">
										&#9660;
								    </div>	
									    <?php
								}else if($cell['HAZARD']=='Y'){ ?>
									<div class="corner-left-label">&#9674;</div>
								<?php } ?>
								
							    <?php if ($cell['CONT_40_LOCATION']==''){ ?> 
							    	<?php if ($cell['SEQUENCE']!=''){?>
										<div class="div-job-seq">
											<?php if ($cell['STATUS']=='P') {
										    	echo $cell['SEQUENCE'];
									    	}else {
										    	echo 'C ';
									    	} ?>
									    </div>
									<?php } ?>
								<?php 
									if($filter == 'SIZE'){
											echo $cell['CONT_SIZE'];
										}else if($filter == 'WEIGHT'){
											echo $cell['WEIGHT'];
										}else if($filter == 'OPERATOR'){ 
											echo $cell['ID_OPERATOR'];
										}else{
											echo $cell['ID_COMMODITY'];
										} 
									}?>
							</div>
						</li>
					<?php
							$index++;
						
						}
					?>
						<li style="font-size:10px;" class="<?=($status_tier_hid) ? 'display-none' : ''?>"><?=str_pad($cell["TIER_"],2,'0',STR_PAD_LEFT)?></li>
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
				<?php
					$odd = false;
					if( ($bay["JML_ROW"] % 2) == 0){
						$start = $bay["JML_ROW"];
					}else{
						$odd = true;
						$start = $bay["JML_ROW"] - 1;
					}
					$n = -2;
					
					for($j = 1; $j <= $bay["JML_ROW"]; $j++){
						$row = str_pad($start,2,'0',STR_PAD_LEFT);
					 	$count_row = $this->vessel->get_count_row($ID_VESSEL, $bay['ID_BAY'],$row);
						if($count_row->JML < 1){
							$status_row_hid = true;
						} else {
							$colspan_row++;
							$status_row_hid = false;
						}
						
						array_push($status_row_hidden, $status_row_hid);
				?>
					<td style="padding: 0px; width:25px" class="boxTable <?=($count_row->JML < 1) ? 'display-none' : '' ?>">
						<?=$row?>
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
				</tr>
				<tr></tr>
			</table>
		</td>
	<?php
			}else if ($bay['BAY']%2==0){
	?>
		<td align="center" id="bay_view_<?=$tab_id?>_<?=$bay['BAY']?>" style="display:none;">
			<table style="width: <?=($bay["JML_ROW"]+1)*$size+20?>px;" frame="box">
				<tr>
					<?php
						if ($bay_area[$count_bay+1]["BAY"]%2==0){
					?>
					<td colspan="<?=$bay["JML_ROW"]+1?>" align="center">
					    <div style="float: left; width: 15px;">
						<?php 
						if($vessel['ALONG_SIDE'] == 'P'){ 
						    echo 'L';
						}else{
						    echo 'W';
						}
						?>
					    </div>
					    <div style="float: left; width:calc(100% - 32px);">
						<div style="width:40px;"><h1 style="background-color: #3a5795; color: #FFFFFF; margin-top:0px;"><?=$bay["BAY"]?></h1></div>
					    </div>
					    <div style="float: right; width: 15px;">
						<?php 
						if($vessel['ALONG_SIDE'] == 'P'){ 
						    echo 'W';
						}else{
						    echo 'L';
						}
						?>
					    </div>
					</td>
					<?php
						}else{
							if (($bay["JML_ROW"]+1)%2==0){
								$colspan_left = ($bay["JML_ROW"]+1)/2;
								$colspan_right = ($bay["JML_ROW"]+1)/2;
							}else{
								$colspan_left = ($bay["JML_ROW"])/2;
								$colspan_right = ($bay["JML_ROW"]/2)+1;
							}
					?>
					<td colspan="<?=$colspan_left?>" align="right">
					    <div style="float: left; width: 15px;">
						<?php 
						if($vessel['ALONG_SIDE'] == 'P'){ 
						    echo 'L';
						}else{
						    echo 'W';
						}
						?>
					    </div>
					    <div style="float: left; width: calc(100% - 17px)">
						<div style="width:40px;" align="center"><h1 style="background-color: #3a5795; color: #FFFFFF; margin-top:0px;"><?=$bay["BAY"]?></h1></div>
					    </div>
					</td>
					<td colspan="<?=$colspan_right?>" align="left">
					    <div style="float: left; width: calc(100% - 17px);">
						<div style="width:40px;" align="center"><h1 style="background-color: #ffffff; color: #3a5795; margin-top:0px; cursor: pointer;" onclick="switchBayView_<?=$tab_id?>('<?=$bay_area[$count_bay+1]["BAY"]?>');"><?=$bay_area[$count_bay+1]["BAY"]?></h1></div>
					    </div>
					    <div style="float: right; width: 15px;">
						<?php 
						if($vessel['ALONG_SIDE'] == 'P'){ 
						    echo 'W';
						}else{
						    echo 'L';
						}
						?>
					    </div>
					</td>
					<?php
						}
					?>
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
						$row = str_pad($start,2,'0',STR_PAD_LEFT);
					 	$count_row = $this->vessel->get_count_row($ID_VESSEL, $bay['ID_BAY'],$row);
						 if($count_row->JML < 1){
							 $status_row_hid = true;
						 } else {
							 $colspan_row++;
							 $status_row_hid = false;
						 }
						 
						array_push($status_row_hidden, $status_row_hid);
				?>
					<td style="padding: 0px;" class="boxTable <?=($count_row->JML < 1) ? 'display-none' : '' ?>">
						<?=$row?>
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
					<td colspan='<?=$colspan_row?>' check="CC">
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
							 
							if(!in_array($cell['TIER_'], $tier)){
								$count_tier =  $this->vessel->get_count_tier($ID_VESSEL, $bay['ID_BAY'],$cell['TIER_']);
								$status_tier_hid = ($count_tier->JML < 1) ? true : false;
								array_push($tier, $cell['TIER_']);
							}
				?>
						<li 
						    <?php if ($cell['NO_CONTAINER']!=''){ ?> 
							no_container="<?=$cell['NO_CONTAINER']?>" 
							point="<?=$cell['POINT']?>" 
							cont_size="<?=$cell['CONT_SIZE']?>" 
							title="<?=$cell['NO_CONTAINER']?><?php echo ($cell['TL_FLAG']=='Y') ? ' - TL' : ''; ?>" 
							    	<?php if ($cell['CONT_40_LOCATION']==''){
									if($cell['ID_CLASS_CODE']=='TC'){
								?> 
									class="ui-placement-disabled"
									<?php }else{ 
									    if ($cell['SEQUENCE']!=''){ ?> 
									<?php if($cell['STATUS']=='P'){ ?> 
							    class="ui-plan-default" 
								<?php }else{ ?> 
							    class="ui-placement-default" <?php } ?> 
								<?php }else{ ?> 
							    class="ui-stacking-default" 
								<?php } 
									}
								?> 
								    <?php }else{ ?> 
							    class="ui-placement-default" <?php } ?> 
							    <?php }else if($cell['STATUS_STACK']!='A'){ ?> class="uiMutih <?=($status_row_hidden[$s-1] || $status_tier_hid ) ? 'display-none' : '' ?>" <?php }else{ ?> class="ui-state-default" <?php }?> id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="D" 
						<?php if($cell['STATUS_STACK']!='X'){ ?>
							    style="box-shadow:0 1px 2px #616161,inset 0 -1px 1px rgba(0,0,0,0.1),inset 0 1px 1px rgba(255,255,255,0.8);
							    <?php if($cell['BACKGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
								background: #<?=$cell['BACKGROUND_COLOR']?>;
							    <?php } ?>
							    <?php if($cell['FOREGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
								color: #<?=$cell['FOREGROUND_COLOR']?>;
							    <?php } ?>"
						<? } ?> >
						    
						<div class="simbil">
							<?php 
								if($cell['CONT_TYPE']=='HQ'){
									?>
								<div class="div-tl-simbol">
									&#9701;
								</div>	
							<?php
								}
							
								if($cell['TL_FLAG']=='Y'){ ?>
								<div class="div-tl-simbol">
										&#9660;

								    </div>	
								<!-- <div class="corner-left-label">&#10041;</div> -->
							<?php }else if($cell['HAZARD']=='Y'){ ?>
									<div class="corner-left-label">&#9674;</div>
								<?php } ?>
						    <?php if ($cell['CONT_40_LOCATION']==''){ ?>
						    	<?php if ($cell['SEQUENCE']!=''){ ?>
						    		<div class="div-job-seq">
							    		<?php if ($cell['STATUS']=='P') {
							    			echo $cell['SEQUENCE'];
							    		}else { echo "C"; } ?>
							    	</div>
						    	<?php } ?>
						    	<?php 
						    		if($filter == 'SIZE'){
										echo $cell['CONT_SIZE'];
									}else if($filter == 'WEIGHT'){
										echo $cell['WEIGHT'];
									}else if($filter == 'OPERATOR'){ 
										echo $cell['ID_OPERATOR'];
									}else{
										echo $cell['ID_COMMODITY'];
									} 
						    	?>
						    <?php }else{ ?> 40 <?php }?>
						</div>
		  
		  </li>
					<?php
							$index++;
						}
					?>
						<li style="font-size:10px;" class="<?=($status_tier_hid) ? 'display-none' : ''?>"><?=$cell["TIER_"]?></li>
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
					<td colspan='<?=$colspan_row?>' check="DD">
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
							 
							if(!in_array($cell['TIER_'], $tier)){
								$count_tier =  $this->vessel->get_count_tier($ID_VESSEL, $bay['ID_BAY'],$cell['TIER_']);
								$status_tier_hid = ($count_tier->JML < 1) ? true : false;
								array_push($tier, $cell['TIER_']);
							}
				?>
						<li <?php if ($cell['NO_CONTAINER']!=''){ ?> no_container="<?=$cell['NO_CONTAINER']?>" point="<?=$cell['POINT']?>" cont_size="<?=$cell['CONT_SIZE']?>" title="<?=$cell['NO_CONTAINER']?><?php echo ($cell['TL_FLAG']=='Y') ? ' - TL' : ''; ?>" 
						    	    <?php if ($cell['CONT_40_LOCATION']==''){ 
								    if($cell['ID_CLASS_CODE']=='TC'){
							    ?>
								    class="ui-placement-disabled"
								<?php
								    }else{
?> 
								<?php if ($cell['SEQUENCE']!=''){ ?> 
								    <?php if($cell['STATUS']=='P'){ ?> 
								    class="ui-plan-default" 
								<?php }else{ ?> 
								    class="ui-placement-default" 
								<?php } ?> 
							<?php }else{ ?> 
								    class="ui-stacking-default" 
							<?php } 
								    }
							?> 
						<?php }else{ ?> 
								    class="ui-placement-default" 
						<?php } ?> 
						    <?php  ?> 
						    <?php }else if($cell['STATUS_STACK']!='A'){ ?> class="uiMutih <?=($status_row_hidden[$s-1] || $status_tier_hid) ? 'display-none' : '' ?>" <?php }else{ ?> class="ui-state-default" <?php } ?> id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="H" 
						<?php if($cell['STATUS_STACK']!='X'){ ?>style="box-shadow:0 1px 2px #616161,inset 0 -1px 1px rgba(0,0,0,0.1),inset 0 1px 1px rgba(255,255,255,0.8); 
							<?php if($cell['BACKGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
								background: #<?=$cell['BACKGROUND_COLOR']?>;
							<?php } ?>
							<?php if($cell['FOREGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
								color: #<?=$cell['FOREGROUND_COLOR']?>;
							<?php } ?>
						"<? } ?> 
						>
						
						<div class="simbil">
						<?php
							if($cell['CONT_TYPE_NAME']=='HQ'){
								?>
							<div class="div-tl-simbol">
								&#9701;
							</div>	
						<?php
							} 
							if($cell['TL_FLAG']=='Y'){
								?>
							<div class="div-tl-simbol">
										&#9660;
										
								    </div>	
							    <?php
								}else if($cell['HAZARD']=='Y'){ ?>
									<div class="corner-left-label">&#9674;</div>
								<?php } ?>
						    <?php if ($cell['CONT_40_LOCATION']==''){ ?> 
						    	<?php if ($cell['SEQUENCE']!=''){ ?> 
						    		<div class="div-job-seq">
							    		<?php if ($cell['STATUS']=='P') {
							    			echo $cell['SEQUENCE'];
							    		} else { echo "C"; }  ?>
							    	</div>
						    	<?php } ?> 
						    	<?php
						    		if($filter == 'SIZE'){
										echo $cell['CONT_SIZE'];
									}else if($filter == 'WEIGHT'){
										echo $cell['WEIGHT'];
									}else if($filter == 'OPERATOR'){ 
										echo $cell['ID_OPERATOR'];
									}else{
										echo $cell['ID_COMMODITY'];
									} 
						    	?>
						    <?php }else{ ?> 40 <?php } ?></li>
						    </div>
					</li>
							<?php
							$index++;
						}
					?>
						<li style="font-size:10px;" class="<?=($status_tier_hid) ? 'display-none' : ''?>"><?=str_pad($cell["TIER_"],2,'0',STR_PAD_LEFT)?></li>
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
				<?php
					$odd = false;
					if( ($bay["JML_ROW"] % 2) == 0){
						$start = $bay["JML_ROW"];
					}else{
						$odd = true;
						$start = $bay["JML_ROW"] - 1;
					}
					$n = -2;
					
					for($j = 1; $j <= $bay["JML_ROW"]; $j++){
						$row = str_pad($start,2,'0',STR_PAD_LEFT);
					 	$count_row = $this->vessel->get_count_row($ID_VESSEL, $bay['ID_BAY'],$row);
						if($count_row->JML < 1){
							$status_row_hid = true;
						} else {
							$colspan_row++;
							$status_row_hid = false;
						}
						
						array_push($status_row_hidden, $status_row_hid);
				?>
					<td style="padding: 0px;" class="boxTable <?=($count_row->JML < 1) ? 'display-none' : '' ?>">
						<?=$row?>
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
				</tr>
				<tr></tr>
			</table>
		</td>
	<?php
			}
	?>
	<?php
			$count_bay++;
		}
	?>
		</tr>
	</table>
	<div class="spacer"></div>
		<table width="130" style="float: left !important;">
			<tr>
				<td>&#9701;</td><td>:</td><td>High Qube</td>
			</tr>
			<tr>
				<td>&#9660;</td><td>:</td><td>Container TL</td>
			</tr>
			<tr>
				<td>C</td><td>:</td><td>Complete</td>
			</tr>
			<tr>
				<td>R</td><td>:</td><td>Reefer</td>
			</tr>
			<tr>
				<td>H</td><td>:</td><td>Hazard</td>
			</tr>
			<tr>
				<td>G</td><td>:</td><td>General</td>
			</tr>
			<tr>
				<td>M</td><td>:</td><td>Empty</td>
			</tr>
		</table>

	</div>
</center>



<script>
	function setSequence_<?=$tab_id?>(id_ves_voyage, id_bay, deck_hatch){
		var data_str = "";
		data_str += "<sequence>"+$("#select-result_<?=$tab_id?>").html()+"</sequence>";
		
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><plan><data>"+data_str+"</data></plan>";
		
		var url = "<?=controller_?>inbound_view/set_sequence";
		
		loadmask.show();
		Ext.Ajax.request({
			url: url,
			params: {
				id_ves_voyage: id_ves_voyage,
				id_bay: id_bay,
				deck_hatch: deck_hatch,
				xml_: xml_str
			},
			success: function(response){
				loadmask.hide();
				if (response.responseText=='1'){
					Ext.MessageBox.show({
						title: 'Success',
						msg: 'Sequence Inserted!',
						buttons: Ext.MessageBox.OK,
						fn: function (){
							Ext.getCmp('<?=$tab_id?>').close();
							addTab('center_panel', 'Inbound View', id_ves_voyage);
						}
					});
				}else{
					Ext.Msg.alert('Failed', 'Sequence Error!');
				}
			}
		});
		
		return true;
	}
	
	function unsetSequence_<?=$tab_id?>(id_ves_voyage, id_bay, deck_hatch){
		var data_str = "";
		data_str += "<id_cell>"+$("#result_<?=$tab_id?>").html()+"</id_cell>";
		
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><plan><data>"+data_str+"</data></plan>";
		
		var url = "<?=controller_?>inbound_view/unset_sequence";
		
		loadmask.show();
		Ext.Ajax.request({
			url: url,
			params: {
				id_ves_voyage: id_ves_voyage,
				id_bay: id_bay,
				deck_hatch: deck_hatch,
				xml_: xml_str
			},
			success: function(response){
				loadmask.hide();
				if (response.responseText=='1'){
					Ext.MessageBox.show({
						title: 'Success',
						msg: 'Sequence Deleted!',
						buttons: Ext.MessageBox.OK,
						fn: function (){
							Ext.getCmp('<?=$tab_id?>').close();
							addTab('center_panel', 'Inbound View', id_ves_voyage);
						}
					});
				}else{
					Ext.Msg.alert('Failed', 'Error!');
				}
			}
		});
		
		return true;
	}
	
	function contInquiry_<?=$tab_id?>(e){
//	    console.log('con inquiry');
//	    console.log(e);
//	    console.log($(e).attr('no_container') + ' : ' + $(e).attr('point'));
	    Ext.getCmp('east_panel').expand();
	    Ext.getCmp('west_panel').collapse();
	    addTab('east_panel', 'container_inquiry', 'no_container:' + $(e).attr('no_container'), 'Container Inquiry');
	}
	function switchBayView_<?=$tab_id?>(bay_number){
		$("#bay_view_<?=$tab_id?>_"+bay_number).show();
		if (bay_number%2==0){
			$("#bay_view_<?=$tab_id?>_"+(parseInt(bay_number)+1)).hide();
		}else{
			$("#bay_view_<?=$tab_id?>_"+(parseInt(bay_number-1))).hide();
		}
	}
</script>