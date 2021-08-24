<?php
	$L	= $width * $height;
	$s1 = 28;
	$s = 15;
	$grid_width = ($s1*$width)+8;
	$grid_height = ($s*$height)+8;
?>

<style>
#selectable_<?=$tab_id?> .ui-selecting { background: #FECA40!important; }
#selectable_<?=$tab_id?> .ui-selected { background: #F39814!important; color: white; }
#selectable_<?=$tab_id?> { list-style-type: none; margin: 0; padding: 0; }
#selectable_<?=$tab_id?> li {float: left; width: <?php echo $s1."px"?>; height: <?php echo $s."px"?>; font-size: 2em; text-align: center; }
div.grid_<?=$tab_id?> {
	width:  <?php echo $grid_width."px"?>;
	height: <?php echo $grid_height."px"?>;
	font-size: 5px;
}
</style>

<script>
$(function() {
	$( "#selectable_<?=$tab_id?>" ).selectable({
		filter: ".ui-selectable-area",
		start: function( event, ui ) {
			$( "#select-result_<?=$tab_id?>" ).empty();
			$( "#result_<?=$tab_id?>" ).empty();
			$( "#block_id_<?=$tab_id?>" ).empty();
		},
		selected: function(event, ui) {
			//console.log($(ui.selected).attr('index'));
			if ($( "#select-result_<?=$tab_id?>" ).html()!=""){
				$( "#select-result_<?=$tab_id?>" ).append(",");
			}
			if ($( "#result_<?=$tab_id?>" ).html()!=""){
				$( "#result_<?=$tab_id?>" ).append(",");
			}
			$( "#select-result_<?=$tab_id?>" ).append(
				$(ui.selected).attr('title')+"^"+$(ui.selected).attr('slot')+"^"+$(ui.selected).attr('row')+"^"+$(ui.selected).attr('tier')
			);
			$( "#result_<?=$tab_id?>" ).append(
				$(ui.selected).attr('index')
			);
			if ($( "#block_id_<?=$tab_id?>" ).html()==""){
				$( "#block_id_<?=$tab_id?>" ).append(
					$(ui.selected).attr('block_id')
				);
			}
		},
		stop: function( event, ui ) {
			var str = $( "#select-result_<?=$tab_id?>").html();
			var list_plan = str.split(",");
			var block = "";
			var tier = 0;
			var min_slot=0;
			var max_slot=0;
			var min_row=0;
			var max_row=0;
			var flag = 1;
			for(i=0;i<list_plan.length;i++){
				var temp = list_plan[i].split("^");
				// console.log(temp);
				if (i==0){
					if (temp[1] == undefined){
						flag = 0;
						break;
					}
					block = temp[0];
					tier = temp[3];
					min_slot = temp[1];
					max_slot = temp[1];
					min_row = temp[2];
					max_row = temp[2];
				}
				if (parseInt(temp[1])>max_slot){
					max_slot = temp[1];
				}
				if (parseInt(temp[1])<min_slot){
					min_slot = parseInt(temp[1]);
				}
				if (parseInt(temp[2])>max_row){
					max_row = parseInt(temp[2]);
				}
				if (parseInt(temp[2])<min_row){
					min_row = parseInt(temp[2]);
				}
			}
			
			var str_plan = "";
			var capacity = "";
			
			if (flag){
				var str_plan = "Blok: "+block+";";
				str_plan += "Slot: "+min_slot+"-"+max_slot+";";
				str_plan += "Row: "+min_row+"-"+max_row+";";
				
				var capacity = (max_slot-min_slot+1)*(max_row-min_row+1)*tier;
			}
			
			$("#selected_plan_<?=$tab_id?>").val(str_plan);
			$("#selected_capacity_<?=$tab_id?>").val(capacity);
		}
	});
	
	$.contextMenu({
		selector: "#selectable_<?=$tab_id?> .ui-selected",
		items: {
<?php
		    if($act == 'edit'){
?>
			"plan_edit": {
				name: "Set New Plan Area", 
				icon: "edit", 
				callback: function(key, options) {
				    var conf = confirm("Are you sure want to change plan area?");
				    if(conf){
					var block_str = "";
					// console.log($("#block_id_<?=$tab_id?>").html());
					block_str += "<block_id>"+$("#block_id_<?=$tab_id?>").html()+"</block_id>";
					// console.log($("#result_<?=$tab_id?>").html());
					block_str += "<index>"+$("#result_<?=$tab_id?>").html()+"</index>";

					var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><plan><block>"+block_str+"</block><category_id><?=$id_category?></category_id><id_yard_plan><?=$id_yard_plan?></id_yard_plan></plan>";

					var url = "<?=controller_?>yard_planning/plan_yard";

					loadmask.show();
					$.post( url+"?id_yard=<?=$id_yard?>&act=edit", { xml_: xml_str}, function(data) {
						// console.log(data);
						if (data=='1'){
							loadmask.hide();
							Ext.Msg.alert('Success', 'Plan Inserted');
							Ext.getCmp('yd_group_<?=$tab_id_ypg?>').getStore().reload();
							Ext.getCmp("<?=$tab_id?>").close();
						}
					});
					return true;
				    }
				}
			},
<?php
		    }else{
?>
			"plan_new": {
				name: "Plan with New Category", 
				icon: "edit", 
				callback: function(key, options) {
					Ext.Ajax.request({
						url: '<?=controller_?>yard_planning/popup_new_category?tab_id=<?=$tab_id?>',
						callback: function(opt,success,response){
							$("#popup_script_<?=$tab_id?>").html(response.responseText);
						} 
					});
				}
			},
			"plan_existing": {
				name: "Plan with Existing Category",
				icon: "edit",
				callback: function(key, options) {
					Ext.Ajax.request({
						url: '<?=controller_?>yard_planning/popup_existing_category?dtbl=0&rtbl=0&tab_id=<?=$tab_id?>',
						callback: function(opt,success,response){
							$("#popup_script_<?=$tab_id?>").html(response.responseText);
						} 
					});
				}
			},
<?php
		    }
?>
			"sep1": "---------",
			"quit": {
				name: "Cancel",
				icon: "quit",
				callback: function(key, options) {
					$(this).contextMenu("hide");
				}
			}
		}
	});
});
</script>

<span id="select-result_<?=$tab_id?>" style="display: none;"></span>
<span id="result_<?=$tab_id?>" style="display: none;"></span>
<span id="block_id_<?=$tab_id?>" style="display: none;"></span>
<div id="popup_script_<?=$tab_id?>"></div>
<?php 
$rotate = '0';
if($north_orientation == 'R'){
    $rotate = '90';
}else if($north_orientation == 'D'){
    $rotate = '180';
}else if($north_orientation == 'L'){
    $rotate = '270';
}
?>
    <img src="<?=IMG_?>icons/compass.png" width="80px" style="margin: 10px 0px 0px 10px;-webkit-transform: rotate(<?=$rotate?>deg);transform: rotate(<?=$rotate?>deg);"/>
<center>
<div class="grid_<?=$tab_id?>">
	<table border="0" width="100%">
		<tr align="center" valign="top">
			<td align="center" valign="middle" style="padding-left: 2px; padding-right: 2px;">
				<ol id="selectable_<?=$tab_id?>">
					<?php 
						$j = 1;
						$p = 0;
						$l = 0;
						$coen = 0;
						$block_array = array();
						for($i = 1; $i <= $L; $i++){
							$m = ($width*$j) + 1;
							$cell_idx = $i - 1;
							
							if($index[$p] != '' && $cell_idx == $index[$p]){
								if (!in_array($title[$p],$block_array)) {
									$block_array[] = $title[$p];
									$index_block_name = ($i-1)-(1+(2*$width));
								?>
								<script>
									$("#selectable_<?=$tab_id?> li[index='"+<?=$index_block_name?>+"']").css("font-weight", "bold");
									$("#selectable_<?=$tab_id?> li[index='"+<?=$index_block_name?>+"']").css("font-size", "12px");
									$("#selectable_<?=$tab_id?> li[index='"+<?=$index_block_name?>+"']").text('<?=$title[$p]?>');
								</script>
								<?php
								}
								
								    $bgColor = isset($total_stack['BACKGROUND_COLOR'][$block_id[$p]][$slot_[$p]][$row_[$p]]) || $cell_idx == $plan[$coen] ? 'background: #'.$total_stack['BACKGROUND_COLOR'][$block_id[$p]][$slot_[$p]][$row_[$p]] : '';
								    $fColor = $total_stack['FOREGROUND_COLOR'][$block_id[$p]][$slot_[$p]][$row_[$p]];
								    $default_class = $cell_idx == $plan[$coen] ? 'ui-plan-default' : 'ui-stacking-default';
					?>			    
								<li class="<?=$default_class?> ui-selectable-area" index="<?=$i-1?>" slot="<?=$slot_[$p]?>" row="<?=$row_[$p]?>" tier="<?=$tier_[$p]?>" title="<?=$title[$p]?>" block_id="<?=$block_id[$p]?>" orientation="<?=$orientation[$p]?>" position="<?=$position[$p]?>" 
								    cell-idx="<?=$cell_idx?>" plan-coen="<?=$plan[$coen]?>"
								<?php if (($i%$m) == 0){ $j++;?>
								style="clear: both; border-bottom: solid #6dadd6; border-right: solid #6dadd6;border-width:0.15em;color: #<?=$fColor?>;background: <?=$bgColor?>"
								<?php }else{ ?>
								style="border-bottom: solid #6dadd6; border-right: solid #6dadd6;border-width:0.15em;color: #<?=$fColor?>;<?=$bgColor?>"
								<?php } ?>><?=$total_stack['TOTAL'][$block_id[$p]][$slot_[$p]][$row_[$p]] == '' ? 0 : $total_stack['TOTAL'][$block_id[$p]][$slot_[$p]][$row_[$p]]?></li>
					<?php
								if($cell_idx == $plan[$coen]) $coen++;
								
					?>
					<?php 
								$p++;
							}
							else
							{
					?>		
							<li index="<?=$i-1?>"<?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>>
							<?php if (($i-1)==$label[$l]){
								echo $label_text[$l];
								$l++; 
							} ?>
							</li>
					<?php 
							}
						}
					
					?>
				</ol>
			</td>
		</tr>
	</table>
</div>
</center>

<script>
	function PlanYard_<?=$tab_id?>(category_id){
		var block_str = "";
		// console.log($("#block_id_<?=$tab_id?>").html());
		block_str += "<block_id>"+$("#block_id_<?=$tab_id?>").html()+"</block_id>";
		// console.log($("#result_<?=$tab_id?>").html());
		block_str += "<index>"+$("#result_<?=$tab_id?>").html()+"</index>";

		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><plan><block>"+block_str+"</block><category_id>"+category_id+"</category_id><id_yard_plan></id_yard_plan></plan>";
		
		var url = "<?=controller_?>yard_planning/plan_yard";
		
		loadmask.show();
		$.post( url+"?id_yard=<?=$id_yard?>", { xml_: xml_str}, function(data) {
			// console.log(data);
			if (data=='1'){
				loadmask.hide();
				Ext.Msg.alert('Success', 'Plan Inserted');
				$("#list_yard_<?=$tab_id?>").change();
			}
		});
		return true;
	}
</script>