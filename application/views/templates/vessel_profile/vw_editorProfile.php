<?php
echo '<b>Profile : </b>'.$vessel_code.' - Bay '.$bay['BAY'].'<br>';
echo '<b>Row : </b>'.$bay['JML_ROW'].'<br>';
echo '<b>Tier On Deck: </b>'.$bay['JML_TIER_ON'].'<br>';
echo '<b>Tier Under Deck: </b>'.$bay['JML_TIER_UNDER'].'<br>';
?>

<?php
	$size = 20;
?>

<style>
.selectable_<?=$tab_id?> .ui-selecting { background: #FECA40; }
.selectable_<?=$tab_id?> .ui-selected { background: #F39814; color: white; }
.selectable_<?=$tab_id?> { list-style-type: none; margin: 0; padding: 0; }
.selectable_<?=$tab_id?> li {float: left; width: <?php echo $size."px"?>; height: <?php echo $size."px"?>; text-align: center; line-height:<?=$size?>px;}
.selectable_<?=$tab_id?> .reefer{border-width: 2px;border-color: #3700ff;}
</style>

<script>
$(function() {
	$( ".selectable_<?=$tab_id?>" ).selectable({
		filter: ".ui-stacking-default, .uiMutih",
		start: function( event, ui ) {
			$( "#bay_id_<?=$tab_id?>" ).empty();
		},
		selected: function(event, ui) {
			if ($( "#bay_id_<?=$tab_id?>" ).html()==""){
				$( "#bay_id_<?=$tab_id?>" ).append(
					$(ui.selected).attr('id_bay')
				);
			}
		},
		stop: function(event, ui) {
			$( "#result_<?=$tab_id?>" ).empty();
			var selected_el = $(".ui-stacking-default.ui-selected, .uiMutih.ui-selected");
			for (var index = 0; index < selected_el.length; ++index) {
				if ($( "#result_<?=$tab_id?>" ).html()!=""){
					$( "#result_<?=$tab_id?>" ).append(",");
				}
				$( "#result_<?=$tab_id?>" ).append(
					$(selected_el[index]).attr('id_cell')
				);
			}
		}
	});

	$.contextMenu({
		selector: ".selectable_<?=$tab_id?> .ui-stacking-default.ui-selected",
		items: {
			"set": {
				name: "Set Broken Space",
				icon: "edit",
				callback: function(key, options) {
					setBrokenSpace_<?=$tab_id?>($("#id_vessel_<?=$tab_id?>").val(), $("#bay_id_<?=$tab_id?>").html());
				}
			},
			"set2": {
				name: "Set Reefer Racking",
				icon: "edit",
				callback: function(key, options) {
					setReeferRacking_<?=$tab_id?>($("#id_vessel_<?=$tab_id?>").val(), $("#bay_id_<?=$tab_id?>").html());
				}
			},
			"unset": {
				name: "Unset Broken Space",
				icon: "delete",
				callback: function(key, options) {
					unsetBrokenSpace_<?=$tab_id?>($("#id_vessel_<?=$tab_id?>").val(), $("#bay_id_<?=$tab_id?>").html());
				}
			},
			"unset1": {
				name: "Unset Reefer Racking",
				icon: "delete",
				callback: function(key, options) {
					unsetReeferRacking_<?=$tab_id?>($("#id_vessel_<?=$tab_id?>").val(), $("#bay_id_<?=$tab_id?>").html());
				}
			},
			"sep1": "---------",
			"quit": {
				name: "Quit",
				icon: "quit",
				callback: function(key, options) {
					$(this).contextMenu("hide");
				}
			}
		}
	});


	$.contextMenu({
		selector: ".selectable_<?=$tab_id?> .uiMutih.ui-selected",
		items: {
			"set": {
				name: "Set Broken Space",
				icon: "edit",
				callback: function(key, options) {
					setBrokenSpace_<?=$tab_id?>($("#id_vessel_<?=$tab_id?>").val(), $("#bay_id_<?=$tab_id?>").html());
				}
			},
			"set2": {
				name: "Set Reefer Racking",
				icon: "edit",
				callback: function(key, options) {
					setReeferRacking_<?=$tab_id?>($("#id_vessel_<?=$tab_id?>").val(), $("#bay_id_<?=$tab_id?>").html());
				}
			},
			"unset": {
				name: "Unset Broken Space",
				icon: "delete",
				callback: function(key, options) {
					unsetBrokenSpace_<?=$tab_id?>($("#id_vessel_<?=$tab_id?>").val(), $("#bay_id_<?=$tab_id?>").html());
				}
			},
			"unset1": {
				name: "Unset Reefer Racking",
				icon: "delete",
				callback: function(key, options) {
					unsetReeferRacking_<?=$tab_id?>($("#id_vessel_<?=$tab_id?>").val(), $("#bay_id_<?=$tab_id?>").html());
				}
			},
			"sep1": "---------",
			"quit": {
				name: "Quit",
				icon: "quit",
				callback: function(key, options) {
					$(this).contextMenu("hide");
				}
			}
		}
	});

});
</script>

<span id="result_<?=$tab_id?>" style="display: none;"></span>
<span id="bay_id_<?=$tab_id?>" style="display: none;"></span>
<input id="id_vessel_<?=$tab_id?>" type="hidden" value="<?=$vessel_code?>"></input>

<center>
	<div class="grid_<?=$tab_id?>">
	<?php
		$n = -2;
	?>
		<table style="width: <?=($bay["JML_ROW"]+1)*$size+20?>px;" frame="box">
			<!--<tr>
				<td colspan="<?=$bay["JML_ROW"]+1?>" align="center">
				  <div style="width:40px;"><h1 style="background-color: #3a5795; color: #FFFFFF; margin-top:0px;"><?=$bay["BAY"]?></h1></div>
				</td>
			</tr>-->
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
			$classBroken = 'ui-stacking-default';

			$bay_cell = $this->vessel->get_vessel_profile_cellInfo_editprofile($vessel_code, $bay['ID_BAY'], 'ABOVE');
			for($j = 1; $j <= $bay["JML_TIER_ON"]; $j++){
					for($s = 1; $s <= $bay["JML_ROW"]; $s++){
						$cell = $bay_cell[$index];

						$classBroken = ($cell['STATUS_STACK']=='A') ? 'ui-stacking-default' : 'uiMutih';
			?>
					<li class="<?php  echo $classBroken ?>" id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="D" >
						<?php
							if($cell['STATUS_REEFER_RACKING'] == 'X') {
								echo "R";
							}
						?>
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

			<?php
				if($bay['HATCH_NUMBER'] == '' || $bay['HATCH_NUMBER'] == 0){
					$hatchNo = 1;
					$cover = 100;
				} else {
					$hatchNo = $bay['HATCH_NUMBER'];
					$cover = 100/$bay['HATCH_NUMBER'];
				}
			?>

			<tr>
				<td title="cover" colspan="<?php echo $bay['JML_ROW']; ?>" style="" height="5px">
					<?php
						for($i=0;$i<$hatchNo;$i++) {
					?>
							<div style="height: 5px;float:left;margin-right: 1px;width: <?php echo $cover-1; ?>%;background:#3a5795;">&nbsp;</div>
					<?php
						}
					?>
				</td>
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
			$bay_cell = $this->vessel->get_vessel_profile_cellInfo_editprofile($vessel_code, $bay['ID_BAY'], 'BELOW');
			for($j = 1; $j <= $bay["JML_TIER_UNDER"]; $j++){
					for($s = 1; $s <= $bay["JML_ROW"]; $s++){
						$cell = $bay_cell[$index];
			?>
					<li <?php if ($cell['STATUS_STACK']=='A'){?> class="ui-stacking-default" <?php }else{ ?> class="uiMutih" <?php }?> id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="H" ></li>
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
	</div>
</center>

<script>
	function setBrokenSpace_<?=$tab_id?>(id_vessel, id_bay){
		var data_str = "";
		data_str += "<id_cell>"+$("#result_<?=$tab_id?>").html()+"</id_cell>";

		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><plan><data>"+data_str+"</data></plan>";

		var url = "<?=controller_?>vessel_profile/set_broken_space";

		loadmask.show();
		Ext.Ajax.request({
			url: url,
			params: {
				id_vessel: id_vessel,
				id_bay: id_bay,
				xml_: xml_str
			},
			success: function(response){
				loadmask.hide();
				if (response.responseText=='1'){
					Ext.MessageBox.show({
						title: 'Success',
						msg: 'Broken Space Created!',
						buttons: Ext.MessageBox.OK,
						fn: function (){
							var url="<?=controller_?>vessel_profile/editProfile/"+id_vessel+"/"+id_bay+"/<?=$tab_id;?>";
							$('#divProfile').load(url).dialog({modal:true, height:400,width:500});
						}
					});
				}else{
					Ext.Msg.alert('Failed', 'Error Create Broken Space!');
				}
			}
		});

		return true;
	}

	function setReeferRacking_<?=$tab_id?>(id_vessel, id_bay){
		var data_str = "";
		data_str += "<id_cell>"+$("#result_<?=$tab_id?>").html()+"</id_cell>";

		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><plan><data>"+data_str+"</data></plan>";

		var url = "<?=controller_?>vessel_profile/set_reefer_racking";

		loadmask.show();
		Ext.Ajax.request({
			url: url,
			params: {
				id_vessel: id_vessel,
				id_bay: id_bay,
				xml_: xml_str
			},
			success: function(response){
				loadmask.hide();
				if (response.responseText=='1'){
					Ext.MessageBox.show({
						title: 'Success',
						msg: 'Reefer Racking Created!',
						buttons: Ext.MessageBox.OK,
						fn: function (){
							var url="<?=controller_?>vessel_profile/editProfile/"+id_vessel+"/"+id_bay+"/<?=$tab_id;?>";
							$('#divProfile').load(url).dialog({modal:true, height:400,width:500});
						}
					});
				}else{
					Ext.Msg.alert('Failed', 'Error Create Reefer Racking!');
				}
			}
		});

		return true;
	}

	function unsetBrokenSpace_<?=$tab_id?>(id_vessel, id_bay){
		var data_str = "";
		data_str += "<id_cell>"+$("#result_<?=$tab_id?>").html()+"</id_cell>";

		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><plan><data>"+data_str+"</data></plan>";

		var url = "<?=controller_?>vessel_profile/unset_broken_space";

		loadmask.show();
		Ext.Ajax.request({
			url: url,
			params: {
				id_vessel: id_vessel,
				id_bay: id_bay,
				xml_: xml_str
			},
			success: function(response){
				loadmask.hide();
				if (response.responseText=='1'){
					Ext.MessageBox.show({
						title: 'Success',
						msg: 'Broken Space Cleared!',
						buttons: Ext.MessageBox.OK,
						fn: function (){
							var url="<?=controller_?>vessel_profile/editProfile/"+id_vessel+"/"+id_bay+"/<?=$tab_id;?>";
							$('#divProfile').load(url).dialog({modal:true, height:400,width:500});
						}
					});
				}else{
					Ext.Msg.alert('Failed', 'Error Remove Broken Space!');
				}
			}
		});

		return true;
	}

	function unsetReeferRacking_<?=$tab_id?>(id_vessel, id_bay){
		var data_str = "";
		data_str += "<id_cell>"+$("#result_<?=$tab_id?>").html()+"</id_cell>";

		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><plan><data>"+data_str+"</data></plan>";

		var url = "<?=controller_?>vessel_profile/unset_reefer_racking";

		loadmask.show();
		Ext.Ajax.request({
			url: url,
			params: {
				id_vessel: id_vessel,
				id_bay: id_bay,
				xml_: xml_str
			},
			success: function(response){
				loadmask.hide();
				if (response.responseText=='1'){
					Ext.MessageBox.show({
						title: 'Success',
						msg: 'Reefer Racking Cleared!',
						buttons: Ext.MessageBox.OK,
						fn: function (){
							var url="<?=controller_?>vessel_profile/editProfile/"+id_vessel+"/"+id_bay+"/<?=$tab_id;?>";
							$('#divProfile').load(url).dialog({modal:true, height:400,width:500});
						}
					});
				}else{
					Ext.Msg.alert('Failed', 'Error Remove Reefer Racking!');
				}
			}
		});

		return true;
	}
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('.ui-dialog-titlebar button span').attr('style','margin: -8px !important');
	});
</script>
