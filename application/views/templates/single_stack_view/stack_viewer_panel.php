<?php
	$size = 80;
?>

<style>
#selectable_<?=$tab_id?> .ui-selecting { background: #FECA40; }
#selectable_<?=$tab_id?> .ui-selected { background: #F39814 !important; color: white; }
#selectable_<?=$tab_id?> { list-style-type: none; margin: 0; padding: 0; }
#selectable_<?=$tab_id?> li {float: left; width: <?php echo $size."px"?>; height: <?php echo $size."px"?>;}
.triangle {
  font-size: 8px; 
  width: 0; 
  height: 0; 
  border-left: 10px solid transparent;
  border-right: 10px solid transparent;
  border-bottom: 14px solid #fb9889;
  float:right;
  
}
.texts{
  color:black;
  text-align:center;
  position:relative;
  margin-left:-6px;
  margin-top:2px;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 80px;
    background-color: #088da5;
    color: #fff;
    text-align: center;
    border-radius: 2px;
	margin-top:18px;
	margin-left:0px;
    padding: 5px 0;
	font-size:6pt;
    /* Position the tooltip */
    position: absolute;
    z-index: 99;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
}

.full-width{
    width: 100%;
}

.ssv_cat_group{
    background: #FFFFFF;
    border: 1px solid #000;
    font-size: 15px;
    font-weight: bold;
    text-align: left;
    padding-left: 5px;
}

.grid_<?=$tab_id?> a{
    color: #000000;
    text-decoration: none;
}
.tonase-ssv{
    padding: 0 5px;
    text-align: right;
}
.bold{
	font-weight: bold;
}
.roundsequence {
    display: inline-block;
    border-radius: 5px;
    border: 1px solid black;
    padding: 0px 5px;
    margin: 0 0 0 5px;
}
.margintopseq {
    margin-top: 5px;
}
.marginbottom {
    margin-bottom: 5px;
}
.float-right{
	float: right;
    text-align: right;
    padding-right: 5px;
}
</style>

<script type="text/javascript">
var no_container_hk = "";
var point_no_containerhk = "";
var hkp_id = "";
var tab_id ="";
var selected_el3 = '';
var selected_el4 = '';



function set_container_hk(a,b,c,d)
{
//    var selected_el = $("#selectable_<?=$tab_id?> .ui-placement-default.ui-selected");
    if(selected_el3 != ''){
	selected_el3 = '';     
	$("#selectable_<?=$tab_id?> .ui-placement-default").removeClass('ui-selected');
    }
    if(selected_el3 != ''){
	selected_el3 = '';     
	$("#selectable_<?=$tab_id?> .ui-placement-default").removeClass('ui-selected');
    }
	alert('you set container '+a+' to plan');
	no_container_hk=a;
	point_no_containerhk=b;
	hkp_id=c;
	tab_id=d;
}

function plan_container_hk(blok,slot,row,tier)
{
	if(no_container_hk!='')
	{
		alert(blok+" "+slot+"-"+row+"-"+tier+" "+no_container_hk);
		var url="<?=controller_?>housekeeping_plan/save_plan_housekeeping";
		$.post(url,{NO_CONTAINER:no_container_hk, POINT:point_no_containerhk, HKP_ID:hkp_id, BLOCK_ID:blok, SLOT:slot, ROW:row, TIER:tier},function(data){
			alert(data);
			loadmask.show();
			Ext.getCmp("<?=$tab_id?>").getLoader().load({
				url: '<?=controller_?>single_stack_view/index/'+$("#list_yard_<?=$tab_id?>").val()+'/'+blok+'/'+slot+'?tab_id=<?=$tab_id?>',
				scripts: true,
				contentType: 'html',
				autoLoad: true,
				success: function(){
					loadmask.hide();
				}
			});
			Ext.getStore('cont_hk_grid'+tab_id).reload();
		});
	}
}

function placement_cont_ssv(elem_from,elem_to, act){
    console.log('elem');
    console.log(elem_from);
    console.log($(elem_from).attr('no_container'));
    if($('#OnOffRelocation_<?=$tab_id?>').is(':checked')){
	var no_container = $(elem_from).attr('no_container');
	var point = $(elem_from).attr('point');
	var op_status = $(elem_from).attr('op_status');
	var data_event = $(elem_from).attr('event');
	var id_machine = act == 'R' ? '' : $(elem_from).attr('id_machine');
	var block_name = $(elem_from).attr('block_name');
	var id_block = $(elem_from).attr('id_block');
	var slot = $(elem_from).attr('slot');
	var row = $(elem_to).attr('row');
	var tier = $(elem_to).attr('tier');
    //    var result = false;
	if(no_container != '' && point != '' && id_block != '' && slot != '' && row != '' && tier != ''){
	    $.ajax({
		url:'<?= controller_?>single_stack_view/yard_placement_submit',
		method: "POST",
		dataType : 'json',
		async: false,
		data:{
		    act: act,
		    no_container: no_container,
		    point: point,
		    id_op_status: op_status,
		    event: data_event,
		    block_name: block_name,
		    id_block: id_block,
		    slot: slot,
		    row: row,
		    tier: tier,
		    id_machine: id_machine
	//	    driver_id: $('#driver-<?=$tab_id?>').val()
		},
		success: function(data){
		    if(data[0] == 'F'){
			alert(data[1]);
	//		alert('Update Job Yard Failed. Please try again or contact your administrator.');
	//		result = false;
		    }else{
			loadSingleStackView();
		    }

		},
		error: function (){
		    alert('Get Job Yard Failed. Check your connection and try again or contact your administrator.');
	//	    result = false;
		}
	    });
	}
    }
}

$(function() {	
	var sequence = 1;
	
	$( "#selectable_<?=$tab_id?>" ).selectable({
		filter: ".ui-stacking-default,.ui-state-default,.ui-placement-default",
		selecting: function( event, ui ) {
			$(ui.selecting).attr('sequence',sequence);
			sequence = sequence + 1;
			
		},
		unselecting: function( event, ui ) {
			$(ui.selecting).removeAttr('sequence');
			sequence = sequence - 1;
		},
		stop: function(event, ui) {
			$( "#select-stack_<?=$tab_id?>" ).empty();
			var selected_el = $("#selectable_<?=$tab_id?> .ui-stacking-default.ui-selected");
			for (var index = 0; index < selected_el.length; ++index) {
				if ($( "#select-stack_<?=$tab_id?>" ).html()!=""){
					$( "#select-stack_<?=$tab_id?>" ).append(",");
				}
				$( "#select-stack_<?=$tab_id?>" ).append(
					$(selected_el[index]).attr('no_container')+"-"+$(selected_el[index]).attr('point')+"-"+$(selected_el[index]).attr('sequence')+"-"+$(selected_el[index]).attr('cont_size')
				);
			}
			
			var selected_el2 = $("#selectable_<?=$tab_id?> .ui-state-default.ui-selected");
			if(selected_el2.length>1){
				alert('cannot plan more than 1 container');
				$("#selectable_<?=$tab_id?> .ui-state-default").removeClass('ui-selected');
			}
			else if(selected_el2.length == 1)
			{
			    console.log('selected_el3');
			    console.log(selected_el3);
			    console.log('selected_el4');
			    console.log(selected_el4);
			    if(no_container_hk!='')
			    {
				for (var index = 0; index < selected_el2.length; ++index) {
					plan_container_hk($(selected_el2[index]).attr('blok_idhk'),$(selected_el2[index]).attr('slothk'),$(selected_el2[index]).attr('rowhk'),$(selected_el2[index]).attr('tierhk'));
				}
			    }else if(selected_el3 != ''){
				console.log('selected_el3');
				placement_cont_ssv(selected_el3,selected_el2, 'R');
			    }else if(selected_el4 != ''){
				placement_cont_ssv(selected_el4,selected_el2, 'R');
			    }
			}
			
			selected_el3 = $("#selectable_<?=$tab_id?> .ui-placement-default.ui-selected").length > 0 ? $("#selectable_<?=$tab_id?> .ui-placement-default.ui-selected") : '';
			selected_el4 = $("#selectable_<?=$tab_id?> .ui-stacking-default.ui-selected").length > 0 ? $("#selectable_<?=$tab_id?> .ui-stacking-default.ui-selected") : '';
			if($('#OnOffRelocation_<?=$tab_id?>').is(':checked') && (selected_el3 != '' && selected_el4 != '' && (selected_el3.length + selected_el4.length )>1
			    || selected_el3 != '' && selected_el4 == '' && selected_el3.length > 1
			    || selected_el3 == '' && selected_el4 != '' && selected_el4.length > 1)){
			    alert('cannot select more than 1 container');
			    $("#selectable_<?=$tab_id?> .ui-placement-default").removeClass('ui-selected');
			    $("#selectable_<?=$tab_id?> .ui-stacking-default").removeClass('ui-selected');
			}else if(selected_el3.length == 1 || selected_el4.length == 1){
			    if(no_container_hk!='')
			    {
				var class_selected = selected_el3.length == 1 ? 'ui-placement-default' : 'ui-stacking-default';
				var no_cont = $("#selectable_<?=$tab_id?> ." + class_selected + ".ui-selected").attr('no_container');
				alert('Unset container '+no_container_hk+' for plan and set container ' + no_cont + ' for placement');
				no_container_hk = '';
				
			    }
			}
		}
	});
});

function save_from_form_<?=$tab_id?>(){
    var row_from = $('#row-from-<?=$tab_id?>').val();
    var row_to = $('#row-to-<?=$tab_id?>').val();
    var tier_from = $('#tier-from-<?=$tab_id?>').val();
    var tier_to = $('#tier-to-<?=$tab_id?>').val();
    var remarks = $('#remarks-<?=$tab_id?>').val();
    
    if(row_from == ''){
	alert("Row(F) harus di isi.");
    }else if(row_to == ''){
	alert("Row(T) harus di isi.");
    }else if(tier_from == ''){
	alert("Tier(F) harus di isi.");
    }else if(tier_from == ''){
	alert("Tier(F) harus di isi.");
    }else if(row_from > row_to){
	alert('"Row From" tidak boleh lebih besar dari "Row To"');
    }else if(tier_from > tier_to ){
	alert('"Tier From" tidak boleh lebih besar dari "Tier To"');
    }else{
	save_no_work_area(row_from,row_to,tier_from,tier_to,remarks);
    }
}



function edit_category_<?=$tab_id?>(id_category){
	Ext.Ajax.request({
		url: '<?=controller_?>yard_planning/popup_existing_category?tab_id=<?=$tab_id?>',
		params: {
			id_category : id_category,
			edit_mode : 1
		},
		callback: function(opt,success,response){
			$("#popup_script_<?=$tab_id?>").html(response.responseText);
		} 
	});
}

$(document).ready(function(){
//   (function(){
	$.switcher('#OnOffRelocation_<?=$tab_id?>');
//     }); 
    
    $('.oog').each(function(){
	var blok = '';
	var slot = '';
	var row = '';
	var tier = '';
	var html = '';
//	console.log('oog:');
	blok = $(this).attr('id_block');
	slot = $(this).attr('slot');
	if($(this).attr('oh') != '' && $(this).attr('oh') != '0'){
	    html = '<div style="text-align: center;width: 100%;bottom:0px;height:100%;margin-top: 25px;"><img src="<?=IMG_?>icons/oog_oh.png" style="height: 50px; width: 50px;" /></div>';
	    row = $(this).attr('row');
	    tier = parseInt($(this).attr('tier')) + parseInt(1);
	    console.log('#<?=$id_yard?>-' + blok + '-' + slot + '-' + row + '-' + tier+'-<?=$tab_id?> : ' + $('#<?=$id_yard?>-' + blok + '-' + slot + '-' + row + '-' + tier+'-<?=$tab_id?>').length);
	    $('#<?=$id_yard?>-' + blok + '-' + slot + '-' + row + '-' + tier + '-<?=$tab_id?>').html(html);
	}
	if($(this).attr('owl') != '' && $(this).attr('owl') != '0'){
	    html = '<div style="text-align: center;width: 100%;height:100%;margin-top: 12px;"><img src="<?=IMG_?>icons/oog_owl.png" style="height: 50px; width: 50px; float: right;" /></div>';
	    row = parseInt($(this).attr('row')) - parseInt(1);
	    tier = parseInt($(this).attr('tier'));
	    console.log('#<?=$id_yard?>-' + blok + '-' + slot + '-' + row + '-' + tier+'-<?=$tab_id?> : ' + $('#<?=$id_yard?>-' + blok + '-' + slot + '-' + row + '-' + tier+'-<?=$tab_id?>').length);
	    $('#<?=$id_yard?>-' + blok + '-' + slot + '-' + row + '-' + tier + '-<?=$tab_id?>').html(html);
	    
	
	}
	if($(this).attr('owr') != '' && $(this).attr('owr') != '0'){
	    html = '<div style="text-align: center;width: 100%;margin-top: 12px;height:100%; "><img src="<?=IMG_?>icons/oog_owr.png" style="height: 50px; width: 50px; float:left;" /></div>';
	    row = parseInt($(this).attr('row')) + parseInt(1);
	    tier = $(this).attr('tier');
	    console.log('#<?=$id_yard?>-' + blok + '-' + slot + '-' + row + '-' + tier+'-<?=$tab_id?> : ' + $('#<?=$id_yard?>-' + blok + '-' + slot + '-' + row + '-' + tier+'-<?=$tab_id?>').length);
	    $('#<?=$id_yard?>-' + blok + '-' + slot + '-' + row + '-' + tier + '-<?=$tab_id?>').html(html);
	
	}
	
    });
});

$.contextMenu({
	selector: "#selectable_<?=$tab_id?> .ui-placement-default",
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
	selector: "#selectable_<?=$tab_id?> .ui-placement-disabled",
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
	selector: "#selectable_<?=$tab_id?> .ui-plan-default",
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

function contInquiry_<?=$tab_id?>(e){
//	    console.log('con inquiry');
//	    console.log(e);
//	    console.log($(e).attr('no_container') + ' : ' + $(e).attr('point'));
//    Ext.getCmp('east_panel').collapse();
//    Ext.getCmp('west_panel').collapse();
    addTab('center_panel', 'container_inquiry', 'no_container:' + $(e).attr('no_container'), 'Container Inquiry');
}

function selectVoid(elm){
    if($(elm).hasClass('void-selected')){
	$(elm).removeClass('void-selected');
	$(elm).children('img').attr('src','<?=IMG_?>icons/no_work_area.png');
    }else{
	$(elm).addClass('void-selected');
	$(elm).children('img').attr('src','<?=IMG_?>icons/no_work_area_selected.png');
    }
}

function delete_void_<?=$tab_id?>(){
    var del = '';
    $('.void.void-selected').each(function(){
	if(del != '') del += ',';
	del += $(this).attr('id');
    });
    
    $.ajax({
	url:'<?= controller_?>single_stack_view/delete_void',
	method: "POST",
	dataType : 'json',
	async: false,
	data:{
	    del : del
	},
	success: function(data){
	    if(data[0] == 'F'){
		alert(data[1]);
	    }else{
		alert(data[1]);
		loadSingleStackView();
	    }

	},
	error: function (){
	    alert('Get Job Yard Failed. Check your connection and try again or contact your administrator.');
//	    result = false;
	}
    });
}
</script>

<span id="select-stack_<?=$tab_id?>" style="display: none;"></span>
<input id="id_yard_<?=$tab_id?>" type="hidden" value="<?=$id_yard?>"></input>
<input id="id_block_<?=$tab_id?>" type="hidden" value="<?=$id_block?>"></input>
<input id="slot_<?=$tab_id?>" type="hidden" value="<?=$slot?>"></input>
<input id="id_ves_voyage_<?=$tab_id?>" type="hidden" value="<?=$id_ves_voyage?>"></input>

<center>
	<div class="grid_<?=$tab_id?>">
	<table border="0" width="100%">
		<tr>
		<td>
			<table style="width: <?=($stack_profile["count_row"]+2)*$size+(0.1*$size)?>px;" frame="box">
				<tr>
					<td colspan="<?=$stack_profile["count_row"]+2?>" align="center">
					  <div><h1 style="background-color: #3a5795; color: #FFFFFF; margin-top:0px;"><?=($stack_profile["YARD_NAME"].'-'.$stack_profile["BLOCK_NAME"].'-'.$slot)?></h1></div>
					</td>
				</tr>
				<tr>
				<?php
					$width = $size;
					for($j = $stack_profile["START_ROW_"]; $j <= $stack_profile["ROW_"]; $j++){
				?>
					<td width="<?=$width?>px">
						<center><?=$j?></center>
					</td>
				<?php
						$width = $width-1;
						if ($width<=(0.9*$size)) $width = $size;
					}
				?>
					<td>
					</td>
				</tr>
				<tr>
					
					<td colspan='<?=$stack_profile["count_row"]+2?>'>
<?php
$max_row = $row_list[count($row_list) - 1]['row'];
$max_tier = $tier_list[count($tier_list) - 1]['tier'];
//print_r($tier_list);
//
//echo 'tier : '.$tier_list[count($tier_list) - 1]['tier'].'<br>';
//echo 'max_tier : '.$max_tier.'<br>';
$zindex = 100;
foreach ($void_list as $void){
    $height = ($void['TO_TIER'] - $void['FROM_TIER'] + 1) * $size;
    $width = ($void['TO_ROW'] - $void['FROM_ROW'] + 1) * $size;
    $top = (($max_tier - $void['TO_TIER']) * $size) + 160;
    $left = (($void['FROM_ROW'] - 1) * $size) + 5;
?>
<a href="javascript:;" onclick="selectVoid(this)" id="<?=$void['ID_BLOCK_VOID']?>" class="void">
<img src="<?=IMG_?>icons/no_work_area.png" style="height: <?=$height?>px; width: <?=$width?>px; top:<?=$top?>px; left:<?=$left?>px;  position: absolute; z-index: <?=$zindex?>" />
<?php $zindex++;?>
<div style="color: red;position: absolute;z-index: <?=$zindex?>;height: <?=$height?>px; width: <?=$width?>px; top:<?=$top?>px; left:<?=$left?>px; padding: 10px;text-align: center;">
    <label><?=$void['REMARKS']?></label>
</div>
</a>
<?php
    $zindex++;
}
$index = 0;
$indexhk=0;
$slot_cell = $this->yard->get_stack_profile_slotInfo($id_yard, $id_block, $slot, $id_ves_voyage);
$slot_cell_all = $this->yard->get_stack_profile_slotInfo($id_yard, $id_block, $slot, '');
$slot_cell_hk = $this->yard->get_stack_profile_slotInfohk($id_yard, $id_block, $slot);
//echo '<pre>';print_r($slot_cell);echo '</pre>';
?>
						<ol id="selectable_<?=$tab_id?>" >
				<?php
		for($j = $stack_profile["TIER_"]; $j > 0; $j--){
			for($s = $stack_profile['START_ROW_']; $s <= $stack_profile["ROW_"]; $s++){
					$cell_ves = $slot_cell[$index];
					$cell = $slot_cell_all[$index];
					$cellhk = $slot_cell_hk[$indexhk];
				?>
						
				<li blok_idhk="<?=$stack_profile['ID_BLOCK'];?>" slothk="<?=$stack_profile['SLOT_'];?>" rowhk="<?=$s;?>" tierhk="<?=$j;?>" 
					id="<?=$id_yard.'-'.$stack_profile['ID_BLOCK'].'-'.$stack_profile['SLOT_'].'-'.$s.'-'.$j.'-'.$tab_id?>" id_machine="<?=$cell['ID_MACHINE']?>"
		  <?php if ($cell['NO_CONTAINER']!='')
				{ 
					?>
					cell_outbound="<?=$cell_ves['OUTBOUND']?>" 
					no_container="<?=$cell['NO_CONTAINER']?>" 
					point="<?=$cell['POINT']?>" 
					op_status="<?=$cell['ID_OP_STATUS']?>"
					event="<?=$cell['EVENT']?>"
					block_name="<?=$cell['BLOCK_']?>"
					id_block="<?=$cell['ID_BLOCK']?>"
					slot="<?=$cell['SLOT_']?>"
					row="<?=$cell['ROW_']?>"
					tier="<?=$cell['TIER_']?>"
					title="<?=$cell['NO_CONTAINER']?>" 
					oh="<?=$cell['OVER_HEIGHT']?>" 
					owl="<?=$cell['OVER_LEFT']?>" 
					owr="<?=$cell['OVER_RIGHT']?>" 
					cont_size="<?=$cell['CONT_SIZE']?>" <?php 
						if ($cell_ves['OUTBOUND'])
						{
							if($cell_ves['SELECTABLE']){
								?> class="ui-stacking-default
									    <?php if($cell['OVER_HEIGHT'] != '' || $cell['OVER_LEFT'] != '' || $cell['OVER_RIGHT'] != ''){?>
								oog
								<?php 
								
								} 
								?>" <?php 
							}else{ 
							?> class="ui-plan-default 
								<?php if($cell['OVER_HEIGHT'] != '' || $cell['OVER_LEFT'] != '' || $cell['OVER_RIGHT'] != ''){?>
								oog
								<?php 
								
								} 
								?>" <?php 
							} ?> <?php 
						}
						else
						{
						    if($cell_ves['NO_CONTAINER'] != '' && $cell['NO_CONTAINER'] == $cell_ves['NO_CONTAINER']){
							?> class="ui-placement-default <?php if($cell['OVER_HEIGHT'] != '' || $cell['OVER_LEFT'] != '' || $cell['OVER_RIGHT'] != ''){?>oog<?php } ?>" 
							<?php
						    }else{
							?> class="ui-placement-disabled <?php if($cell['OVER_HEIGHT'] != '' || $cell['OVER_LEFT'] != '' || $cell['OVER_RIGHT'] != ''){?>oog<?php } ?>" <?php 
						    }
						}
				 
						?> 
					    <?php if($cell['FOREGROUND_COLOR'] != '' || $cell['BACKGROUND_COLOR'] != ''){?>
					    style="<?php if($cell['BACKGROUND_COLOR'] != ''){?>background: #<?=$cell['BACKGROUND_COLOR']?>;<?php } if($cell['FOREGROUND_COLOR'] != ''){?>color: #<?=$cell['FOREGROUND_COLOR']?>;<?php } ?>" 
						    
					    <?php }
				}
				else
				{ 
					?> 
					row="<?=$s?>"
					tier="<?=$j?>"
					class="ui-state-default" 
					<?php 
				} ?>
					id_cell="<?=$cell['INDEX_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>"  style="box-shadow: 
		  0 1px 2px #616161, /*bottom external highlight*/
		  inset 0 -1px 1px rgba(0,0,0,0.1), /*bottom internal shadow*/ 
		  inset 0 1px 1px rgba(255,255,255,0.8); /*top internal highlight*/ background:<?=$cell['COLORS']?>;">
		  
		<?php if(($cell['ID_BLOCK'] != '' && $cellhk['ID_BLOCK'] != '' && $cell['ID_BLOCK']==$cellhk['ID_BLOCK']) AND ($cell['SLOT_'] != '' && $cellhk['SLOT_'] != '' && $cell['SLOT_']==$cellhk['SLOT_']) 
					AND ($cell['ROW_']==$cellhk['ROW_']) AND ($cell['TIER_']==$cellhk['TIER_']))
				{ 
					?><div class="tooltip" style="background-image:url('<?=IMG_?>icons/inspect4.png'); position:left top;background-size:contain;padding-top:10px; background-repeat: no-repeat;color:#191919;margin: 5px 0px 0px 5px;">&nbsp;<span class="tooltiptext"><?=$cellhk['NO_CONTAINER']?></span></div>
					idblok:<?=$cellhk['ID_BLOCK']?>
			<?php $indexhk++; 
				}
				else if ($cell['NO_CONTAINER']!='')
				{
				    if ($cell['SLOT_EXT']=='1'){
				?>
					<img src="<?=IMG_?>icons/no_work_area.png" style="height: <?=$size?>px; width: <?=$size?>px;" />
				<?php
				    }else{
					?>
                        <?php
                            if($cell['ID_CLASS_CODE'] == 'I')
                            {
	                            if(!empty($cell['GT_DATE']))
	                            {
	                                echo '<div class="tonase-ssv bold">&uarr;</div>';
	                            }
	                        }
                        ?>
                        

					<?php
						echo '<div class="marginbottom">';
							echo '<div class="bold  float-right">';
								echo $cell['ID_SPEC_HAND'];
							echo '</div>';
							echo '<div>';
								echo $cell['ID_ISO_CODE'];
								echo ' ';
								echo $cell['ID_COMMODITY'];
								echo ' ';
								echo $cell['ID_CLASS_CODE'];
							echo '</div>';
						echo '</div>';
					?>
					
					<?=$cell['ID_OPERATOR']?>
					<br/>
					<?=$cell['VESSEL_VOYAGE']?>



					<?php if ($cell['IMDG']!=''){?>
						<div class="triangle">
							<div class="texts"><?=$cell['IMDG'];?></div></div><?php }?>
					
					<?=$cell['NO_CONTAINER']?>
					<br/>
					<?=$cell['ID_POD']?><?
					if(!empty($cell['SEQUENCE'])){
						echo"<div class='roundsequence'>$cell[SEQUENCE]</div>";
					}
					?></div>
					<br/>
					<?php
						if(!empty($cell['OUTB_SEQ_BAY'])){
							echo "<div class='margintopseq'>".str_pad($cell['OUTB_SEQ_BAY'],2,'0',STR_PAD_LEFT).str_pad($cell['OUTB_SEQ_ROW'],2,'0',STR_PAD_LEFT).str_pad($cell['OUTB_SEQ_TIER'],2,'0',STR_PAD_LEFT)."</div>";
						}
					?>

                        <div class="tonase-ssv"><?=$cell['WEIGHT'].'T'?></div>
					<?php 
				    }
				}
				else if ($cell['PLAN_AREA']==1)
				{
				?>
					<span style="font-weight: bold; color: #000;font-size: 10px;"><?=$cell['PLAN_NO_CONTAINER']?></span><br>
					<div style="text-align: center;width: 100%;margin-top: 10px; ">
					    <img src="<?=IMG_?>icons/plan_placement.png" style="height: 25px; width: 20px;" />
					</div>
				<?php
				}
				?></li>
			<?php
					$index++;
			}
				
			?>
				<li style='text-align:center;line-height:<?=$size?>px;' ><?=$j?></li>
		<?php
		}
				?>
						</ol>
					</td>
					
				</tr>
				<tr>
				    <td colspan="<?=$stack_profile["count_row"]+2?>"></td>
				</tr>
				<tr>
				<td colspan="<?=$stack_profile["count_row"]+2?>">
<?php
foreach($category_list as $cat){
    $left_cat = (($cat['START_ROW'] - 1) * 80);
    $width_cat = ($cat['END_ROW'] - $cat['START_ROW'] + 1) * 80;
?>
				    <a href="javascript:;" onclick="edit_category_<?=$tab_id?>(<?=$cat['ID_CATEGORY']?>)">
					<div class="ssv_cat_group" style="margin-left: <?=$left_cat?>px;width: <?=$width_cat?>px;
					<?php if($cat['BACKGROUND_COLOR'] != ''){ ?> background: #<?=$cat['BACKGROUND_COLOR']?> <?php } ?>">
					    <?=$cat['CATEGORY_NAME']?>
					</div>
				    </a>
<?php
}
?>
				</td>
				</tr>
			</table>
		</td>
		<td valign="top">
		    <div>
			<b>No Work Area</b>
		    </div> 
		    <table width="200">
			<tr>
			    <td colspan="3"></td>
			</tr>
			<tr>
			    <td style="width: 49%">
				<select id="row-from-<?=$tab_id?>" class="full-width">
				    <option value="">Row(F)</option>
<?php
				foreach($row_list as $row){
?>
				    <option value="<?=$row['row']?>"><?=$row['row']?></option>
<?php
				}
?>
				</select>
			    </td>
			    <td>~</td>
			    <td style="width: 49%">
				<select id="row-to-<?=$tab_id?>" class="full-width">
				    <option value="">Row(T)</option>
<?php
				foreach($row_list as $row){
?>
				    <option value="<?=$row['row']?>"><?=$row['row']?></option>
<?php
				}
?>
				</select>
			    </td>
			</tr>
			<tr>
			    <td>
				<select id="tier-from-<?=$tab_id?>" class="full-width">
				    <option value="">Tier(F)</option>
<?php
				foreach($tier_list as $tier){
?>
				    <option value="<?=$tier['tier']?>"><?=$tier['tier']?></option>
<?php
				}
?>
				</select>
			    </td>
			    <td>~</td>
			    <td>
				<select id="tier-to-<?=$tab_id?>" class="full-width">
				    <option value="">Tier(T)</option>
<?php
				foreach($tier_list as $tier){
?>
				    <option value="<?=$tier['tier']?>"><?=$tier['tier']?></option>
<?php
				}
?>
				</select>
			    </td>
			</tr>
			<tr>
			    <td>Remark</td>
			    <td colspan="2">
				<textarea id="remarks-<?=$tab_id?>" class="full-width"></textarea>
			    </td>
			</tr>
			<tr>
			    <td colspan="3">
				<a href="javascript:;" title="Save" onclick="save_from_form_<?=$tab_id?>()">
				    <img src="<?=IMG_?>icons/save.png" width="20px"/>
				</a>
				<a href="javascript:;" title="Delete" onclick="delete_void_<?=$tab_id?>()">
				    <img src="<?=IMG_?>icons/delete.png" width="20px"/>
				</a>
			   </td>
			</tr>
		    </table>
		    <div style="border-top: 2px inset #616161; margin-top: 30px; padding-top: 10px;">
			&nbsp;
		    </div>
		    <table style="width: 100%">
			<tr>
			    <td>Relocation</td>
			    <td style="text-align: right;">
				<div class="form-check form-check-inline">
				    <input class="form-check-input" type="checkbox" id="OnOffRelocation_<?=$tab_id?>" value="option1" <?php if($isRelocOn == 1){ ?> checked <?php } ?>>
				</div>
			    </td>
			</tr>
		    </table>
		</td>
		</tr>
	</table>
	</div>
</center>
<div id="popup_script_<?=$tab_id?>"></div>