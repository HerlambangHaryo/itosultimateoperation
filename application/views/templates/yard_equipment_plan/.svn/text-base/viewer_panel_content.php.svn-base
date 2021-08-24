<?php
	$L	= $width * $height;
	$s1 = 28;
	$s = 15;
	$grid_width = ($s1*$width)+8;
	$grid_height = ($s*$height)+8;
?>

<style>
#selectable_<?=$tab_id?> .ui-selecting { background: #FECA40; }
#selectable_<?=$tab_id?> .ui-selected { background: #F39814; color: white; }
#selectable_<?=$tab_id?> { list-style-type: none; margin: 0; padding: 0; }
#selectable_<?=$tab_id?> li {float: left; width: <?php echo $s1."px"?>; height: <?php echo $s."px"?>; font-size: 2em; text-align: center; }
div.grid_<?=$tab_id?> {
	width:  <?php echo $grid_width."px"?>;
	height: <?php echo $grid_height."px"?>;
	font-size: 5px;
	position: absolute;
}
</style>

<script>
$(function() {
	$( "#selectable_<?=$tab_id?>" ).selectable({
		filter: ".ui-stacking-default",
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
			"plan_new": {
				name: "Plan Machine", 
				icon: "edit", 
				callback: function(key, options) {
					Ext.Ajax.request({
						url: '<?=controller_?>yard_equipment_plan/popup_machine?tab_id=<?=$tab_id?>',
						callback: function(opt,success,response){
							$("#popup_script_<?=$tab_id?>").html(response.responseText);
						} 
					});
				}
			},
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

<center id="center_content_<?=$tab_id?>">
<div class="grid_<?=$tab_id?>">
	<table border="0" width="100%">
		<tr align="center" valign="top">
			<td align="center" valign="middle" style="padding-left: 2px; padding-right: 2px;">
				<ol id="selectable_<?=$tab_id?>">
					<?php 
						$group_name = '';
						$count_group = 0;
						$j = 1;
						$p = 0;
						$l = 0;
						$coen = 0;
						$raster = 0;
						$block_array = array();
						$id_mch_plan_rastered = array();
						for($i = 1; $i <= $L; $i++){
							$m = ($width*$j) + 1;
							$cell_idx = $i - 1;
							
							if($cell_idx == $index[$p]){
								if (!in_array($title[$p],$block_array)) {
									$block_array[] = $title[$p];
									$index_block_name = ($i-1)-(1+(2*$width));
								?>
								<script>
									$("li[index='"+<?=$index_block_name?>+"']").css("font-weight", "bold");
									$("li[index='"+<?=$index_block_name?>+"']").css("font-size", "12px");
									$("li[index='"+<?=$index_block_name?>+"']").text('<?=$title[$p]?>');
								</script>
								<?php
								}
								if ($cell_idx == $plan[$coen]){
									
									if (!in_array($id_machine[$coen],$id_mch_plan_rastered)){
										$id_mch_plan_rastered[] = $id_machine[$coen];
										$raster = 2;
										$count_group += 1;
									}else{
										$raster = 3;
									}
									$group_name = "group_eq_".$tab_id."_".$mch_name[$coen];
									$group_idx = "group_eq_".$tab_id."_".($i-1);
					?>			
								<li <?php if ($raster==2) echo "id='group_eq_".$tab_id."_".$count_group."'";?> class="ui-plan-default <?= $group_name?> <?= $group_idx?>" index="<?=$i-1?>" mch_name="<?=$mch_name[$coen]?>" slot="<?=$slot_[$p]?>" row="<?=$row_[$p]?>" tier="<?=$tier_[$p]?>" title="<?=$title[$p]?>" block_id="<?=$block_id[$p]?>" orientation="<?=$orientation[$p]?>" position="<?=$position[$p]?>" 
								<?php if (($i%$m) == 0){ $j++;	?>
								style="clear: both; <?php echo " background: ".$mch_color[$coen]."; border: solid ".$mch_color[$coen]."; border-width:0.15em;";?>"
								<?php }else{ ?>
								style="<?php echo " background: ".$mch_color[$coen]."; border: solid ".$mch_color[$coen]."; border-width:0.15em;";?>"
								<?php } ?>></li>
					<?php
									$coen++;
								}else{
									$raster = 1;
					?>
								<li class="ui-stacking-default" index="<?=$i-1?>" slot="<?=$slot_[$p]?>" row="<?=$row_[$p]?>" tier="<?=$tier_[$p]?>" title="<?=$title[$p]?>" block_id="<?=$block_id[$p]?>" orientation="<?=$orientation[$p]?>" position="<?=$position[$p]?>" 
								<?php if (($i%$m) == 0){ $j++;	?>
								style="clear: both; border-bottom: solid #6dadd6; border-right: solid #6dadd6;border-width:0.15em;"
								<?php }else{ ?>
								style="border-bottom: solid #6dadd6; border-right: solid #6dadd6;border-width:0.15em;"
								<?php } ?>></li>
					<?php
								}
					?>
					<?php 
								$p++;
							}
							else
							{
								$raster = 0;
					?>		
							<li index="<?=$i-1?>"<?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>>
							<? if (($i-1)==$label[$l]){
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
	function PlanEquipmentYard_<?=$tab_id?>(id_machine){
		var block_str = "";
		// console.log($("#block_id_<?=$tab_id?>").html());
		block_str += "<block_id>"+$("#block_id_<?=$tab_id?>").html()+"</block_id>";
		// console.log($("#result_<?=$tab_id?>").html());
		block_str += "<index>"+$("#result_<?=$tab_id?>").html()+"</index>";

		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><plan><block>"+block_str+"</block><id_machine>"+id_machine+"</id_machine></plan>";
		
		var url = "<?=controller_?>yard_equipment_plan/plan_yard_equipment";
		
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

<script>
$(document).ready(function(){
	setNameEquipment_<?=$tab_id?>();
});

function setNameEquipment_<?=$tab_id?>(){
    var count_group_<?=$tab_id?> = <?=$count_group?>;
	$("#<?=$tab_id?>-body").scrollTop(0);
	
	for (var i=0;i<count_group_<?=$tab_id?>;i++){
	    var printMchName = false;
	    var xoffset = $("#group_eq_<?=$tab_id?>_"+(i+1)).offset().left;
	    var yoffset = $("#group_eq_<?=$tab_id?>_"+(i+1)).offset().top;
	    var mch_name = $("#group_eq_<?=$tab_id?>_"+(i+1)).attr('mch_name');
	    var idx = 0;
//		console.log("#group_eq_<?=$tab_id?>_"+(i+1));
//		console.log($("#group_eq_<?=$tab_id?>_"+(i+1)));
//		console.log('block: '+xoffset+','+yoffset);
	    var listItem = document.getElementById( "group_eq_<?=$tab_id?>_"+(i+1) );
//		console.log( "Index: " + $( ".group_eq_<?=$tab_id?>_"+mch_name ).index(listItem) );
//		console.log( $( ".group_eq_<?=$tab_id?>_" + mch_name ).index(listItem) );
//		console.log('masuk looping');
//		console.log('idx li : ' + $("#group_eq_<?=$tab_id?>_"+(i+1)).index());
//		$(".group_eq_<?=$tab_id?>_"+mch_name).each(function(){
//		    idx = $(this).attr('index');
//		    idxli = $(this).index(); ($i-1)-(1+(2*$width))
		var idxli = $("#group_eq_<?=$tab_id?>_"+(i+1)).index();
		var idxup = (idxli)-((<?=$width?>));
//		    console.log($(this));
//		    console.log('idx : ' + idx);
//		    console.log('idxup : ' + idxup);
//		    console.log("has plan default : " + $("li:eq(" + (idxli - 1) + ")").hasClass("ui-plan-default"));
//		    console.log("li:eq(" + (idxli - 1) + ")");
//		    console.log($("li:eq(" + (idxli - 1) + ")"));
//		    console.log($("li:eq(" + (idxli - 1) + ")").hasClass("group_eq_<?=$tab_id?>_"+mch_name));
//		    console.log("up li:eq(" + (idxup) + ")");
//		    console.log($("li:eq(" + (idxup) + ")"));
//		    console.log($("li:eq(" + (idxup) + ")").hasClass("group_eq_<?=$tab_id?>_"+mch_name));
		if(!$("#selectable_<?=$tab_id?> li:eq(" + (idxli - 1) + ")").hasClass("ui-plan-default") && !$("#selectable_<?=$tab_id?> li:eq(" + (idxup) + ")").hasClass("ui-plan-default") || 
			$("#selectable_<?=$tab_id?> li:eq(" + (idxli - 1) + ")").hasClass("ui-plan-default")
			&& !$("#selectable_<?=$tab_id?> li:eq(" + (idxli - 1) + ")").hasClass("group_eq_<?=$tab_id?>_"+mch_name)
			&& $("#selectable_<?=$tab_id?> li:eq(" + (idxup) + ")").hasClass("ui-plan-default")
			&& !$("#selectable_<?=$tab_id?> li:eq(" + (idxup) + ")").hasClass("group_eq_<?=$tab_id?>_"+mch_name) ||
			!$("#selectable_<?=$tab_id?> li:eq(" + (idxli - 1) + ")").hasClass("ui-plan-default")
			&& $("#selectable_<?=$tab_id?> li:eq(" + (idxup) + ")").hasClass("ui-plan-default")
			&& !$("#selectable_<?=$tab_id?> li:eq(" + (idxup) + ")").hasClass("group_eq_<?=$tab_id?>_"+mch_name) ||
			$("#selectable_<?=$tab_id?> li:eq(" + (idxli - 1) + ")").hasClass("ui-plan-default")
			&& !$("#selectable_<?=$tab_id?> li:eq(" + (idxli - 1) + ")").hasClass("group_eq_<?=$tab_id?>_"+mch_name)
			&& !$("#selectable_<?=$tab_id?> li:eq(" + (idxup) + ")").hasClass("ui-plan-default")
		    ){
//			console.log('print true');
		    printMchName = true;
//			return false;
		}
//		    console.log('=======================');
//		});
	    if(printMchName){
		$("#center_content_<?=$tab_id?>").append('<div id="equipment_plan_container_<?=$tab_id?>_'+i+'" style="position:absolute;color:#FFFFFF;"><b>'+$("#group_eq_<?=$tab_id?>_"+(i+1)).attr('mch_name')+'</b></div>');
		$("#equipment_plan_container_<?=$tab_id?>_"+i).offset({ top: yoffset, left: xoffset});
	    }
	}
}
</script>