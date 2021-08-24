<?php
	$L	= $width * $height;
	$s = 20;
	$grid_width = ($s*$width)+8;
	if($yard->NORTH_ORIENTATION == 'L' || $yard->NORTH_ORIENTATION == 'R' || $yard->SEA_ORIENTATION == 'Left' || $yard->SEA_ORIENTATION == 'Right') $grid_width += 100;
	$grid_height = ($s*$height)+8;
?>

<style>
#feedback { font-size: 1.4em; }
#selectable_<?=$tab_id?> .ui-selecting { background: #FECA40; }
#selectable_<?=$tab_id?> .ui-selected { background: #F39814; color: white; }
#selectable_<?=$tab_id?> { list-style-type: none; margin: 0; padding: 0; }
#selectable_<?=$tab_id?> li {float: left; width: <?php echo $s."px"?>; height: <?php echo $s."px"?>; font-size: 4em; text-align: center; }
div.grid_<?=$tab_id?> {
	width:  <?php echo $grid_width."px"?>;
	height: <?php echo $grid_height."px"?>;
}
</style>

<script>
var cell_<?=$tab_id?>  				= new Array();
var block_act_<?=$tab_id?> 		= new Array();
var block_id_block_<?=$tab_id?> 		= new Array();
var block_name_<?=$tab_id?> 		= new Array();
var block_tier_<?=$tab_id?> 		= new Array();
var block_position_<?=$tab_id?> 	= new Array();
var block_orientation_<?=$tab_id?> 	= new Array();
var block_height_<?=$tab_id?> 		= new Array();
var block_width_<?=$tab_id?> 		= new Array();
var block_color_<?=$tab_id?>		= new Array();

var count_block_<?=$tab_id?> 	= <?php echo $block_sum;?>;
var slot_<?=$tab_id?>  			= <?php echo $width;?>;
var row_<?=$tab_id?>	 		= <?php echo $height;?>;

var total_<?=$tab_id?>			= row_<?=$tab_id?>*slot_<?=$tab_id?>;
</script>

<script>
$(function() {
	for (var i = 0; i < total_<?=$tab_id?>; i++){
		cell_<?=$tab_id?>[i] = new Object();
	}

	$( "#selectable_<?=$tab_id?>" ).selectable({
		start: function( event, ui ) {
			var result = $( "#select-result_<?=$tab_id?>" ).empty();
		},
		selected: function(event, ui) {
			//console.log($(ui.selected).attr('index'));
			$( "#select-result_<?=$tab_id?>" ).append(
				$(ui.selected).attr('index')+","
			);
		},
		stop: function( event, ui ) {
			var str = $( "#select-result_<?=$tab_id?>").html();
			var list_cell = str.split(",");
			var width=0;
			var height=0;
			for(i=0;i<(list_cell.length)-1;i++){
				if (i==0){
					width=1;
					height=1;
				}else{
					if (list_cell[i]-1 == list_cell[i-1]){
						width = width+1;
					}else{
						width = width+1;
						height = height+1;
					}
				}
			}
			width = width/height;
			if (width > slot_<?=$tab_id?>){
				height = width/slot_<?=$tab_id?>;
				width = slot_<?=$tab_id?>;
			}
			$("#selected_width_<?=$tab_id?>").val(width);
			$("#selected_height_<?=$tab_id?>").val(height);
		}
	});

	$.contextMenu({
		selector: "#selectable_<?=$tab_id?> .ui-selected",
		items: {
			"set": {
				name: "Set Block",
				icon: "edit",
				callback: function(key, options) {
					$("#set_block_pop_up_<?=$tab_id?>").click();
				}
			},
			"unset": {
				name: "Unset Block",
				icon: "delete",
				callback: function(key, options) {
					$("#unblock_<?=$tab_id?>").click();
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

	Ext.create('Ext.data.Store', {
		storeId: 'position',
		fields: ['value', 'name'],
		data: [{
			"value": "H",
			"name": "Horizontal"
		}, {
			"value": "V",
			"name": "Vertical"
		}]
	});

	Ext.create('Ext.data.Store', {
		storeId: 'orientation',
		fields: ['value', 'name'],
		data: [{
			"value": "TL",
			"name": "Top-Left"
		}, {
			"value": "TR",
			"name": "Top-Right"
		}, {
			"value": "BL",
			"name": "Bottom-Left"
		}, {
			"value": "BR",
			"name": "Bottom-Right"
		}]
	});
});
</script>

<div>
<center>
<table>
	<tr>
		<input type="button" id="set_block_pop_up_<?=$tab_id?>" class="button_set_block_pop_up" value=" Set Block " name="set_block_pop_up" style="display:none;"/>
		<input type="button" id="unblock_<?=$tab_id?>" value=" UnSet Block " class="unblock" name="unblock" style="display:none;"/>
		<td>
			Selection Width: <input type="text" id="selected_width_<?=$tab_id?>" readonly />
		</td>
		<td>
			Selection Height: <input type="text" id="selected_height_<?=$tab_id?>" readonly />
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div align="center">
				<input type="button" id="update_yard_pop_up_<?=$tab_id?>" class="button_save_yard_pop_up" value=" Save Yard " name="save_yard_pop_up"/>
			</div>
		</td>
	</tr>
</table>
</center>
</div>
<br/>
<br/>

<span id="select-result_<?=$tab_id?>" style="display: none;"></span>
<span id="result_<?=$tab_id?>"></span>

<center>
<?php if($yard->NORTH_ORIENTATION == 'U') { ?>
	<div>N</div>
<?php } ?>
<?php if($yard->SEA_ORIENTATION == 'Up') { ?>
	<div>Sea Side</div>
<?php } ?>
<div class="grid_<?=$tab_id?>">
	<table border="0" width="100%">
		<tr align="center" valign="top">
			<?php if($yard->NORTH_ORIENTATION == 'L' && $yard->SEA_ORIENTATION == 'Left'){ ?>
				<td align="center" valign="middle" style="padding-left: 2px; padding-right: 2px;">N<br/>Sea Side</td>
			<?php }else{ ?>
				<?php if($yard->NORTH_ORIENTATION == 'L') { ?>
					<td align="center" valign="middle" style="padding-left: 2px; padding-right: 2px;">N</td>
				<?php } ?>
				<?php if($yard->SEA_ORIENTATION == 'Left') { ?>
					<td align="center" valign="middle" style="padding-left: 2px; padding-right: 2px;">Sea Side</td>
				<?php } ?>
			<?php } ?>
			<td align="center" valign="middle" style="padding-left: 2px; padding-right: 2px;">
				<ol id="selectable_<?=$tab_id?>">
					<?php
						$j = 1;
						$p = 0;
						for($i = 1; $i <= $L; $i++)
						{
							$m = ($width*$j) + 1;
							$cell_idx = $i - 1;

							if($index[$p] != '' && $cell_idx == $index[$p])
							{
					?>
								<script> cell_<?=$tab_id?>[<?php echo $cell_idx?>].stack = 1;</script>
								<li class="ui-stacking-default" index="<?=$i-1?>" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?> cell-idx="<?=$cell_idx?>" index-p="<?=$index[$p]?>"></li>
					<?php
								$p++;
							}
							else
							{
					?>
							<li class="ui-state-default"  index="<?=$i-1?>"<?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>></li>
					<?php
							}
						}

					?>
				</ol>
			</td>
			<?php if($yard->NORTH_ORIENTATION == 'R' && $yard->SEA_ORIENTATION == 'Right'){ ?>
				<td align="center" valign="middle" style="padding-left: 2px; padding-right: 2px;">N<br />Sea Side</td>
			<?php }else{ ?>
				<?php if($yard->NORTH_ORIENTATION == 'R') { ?>
					<td align="center" valign="middle" style="padding-left: 2px; padding-right: 2px;">N</td>
				<?php } ?>
				<?php if($yard->SEA_ORIENTATION == 'Right') { ?>
					<td align="center" valign="middle" style="padding-left: 2px; padding-right: 2px;">Sea Side</td>
				<?php } ?>
			<?php } ?>
		</tr>
	</table>
</div>
<?php if($yard->NORTH_ORIENTATION == 'D') { ?>
	<div>N</div>
<?php } ?>
<?php if($yard->SEA_ORIENTATION == 'Down') { ?>
	<div>Sea Side</div>
<?php } ?>
</center>

<script>
function RenderBlockEdit_<?=$tab_id?>(index,block_,cell_block){
	block_ = eval(block_);
	cell_block = eval(cell_block);
	block_id_block_<?=$tab_id?>[index] 	= block_.id_block;
	block_name_<?=$tab_id?>[index] 	= block_.name;
	block_tier_<?=$tab_id?>[index]	= block_.tier;
	block_position_<?=$tab_id?>[index]	= block_.position;
	block_orientation_<?=$tab_id?>[index]	= block_.orientation;
	block_height_<?=$tab_id?>[index]	= block_.height;
	block_width_<?=$tab_id?>[index]	= block_.width;
	if (block_.color!=''){
		block_color_<?=$tab_id?>[index]	= block_.color;
	}else{
		block_color_<?=$tab_id?>[index]	= 'BLACK';
	}
	// console.log(index);
	// console.log(block_name[index]);
	// console.log(block_tier[index]);
	// console.log(block_position[index]);
	// console.log(block_color[index]);
	for (var h = 0; h < cell_block.length; h++){
		var style = $("#selectable_<?=$tab_id?> li").eq(parseInt(cell_block[h])).attr( "style");
		style = typeof style !== 'undefined' ? style : "";

//		cell_<?=$tab_id?>[parseInt(cell_block[h])].block = block_name_<?=$tab_id?>[index];
		$("#selectable_<?=$tab_id?> li").eq(parseInt(cell_block[h])).attr( "style", style+"  border: 1px solid "+block_color_<?=$tab_id?>[index]+"; " );
		$("#selectable_<?=$tab_id?> li").eq(parseInt(cell_block[h])).attr( "title", "  Blok "+block_name_<?=$tab_id?>[index] );
		$("#selectable_<?=$tab_id?> li").eq(parseInt(cell_block[h])).attr( "id-block", block_id_block_<?=$tab_id?>[index] );
	}
}
</script>

<script>
var v = 0;
</script>

<?php
	foreach ($block as $block_)
	{
		// print_r($block_);
		$cell_block	= explode(",",$block_->cell)
?>
		<script>
			RenderBlockEdit_<?=$tab_id?>(v,<?php echo json_encode((array) $block_)?>,<?php echo json_encode ($cell_block)?>);
			v++;
		</script>
<?php
	}
?>

<script>
	$("#set_block_pop_up_<?=$tab_id?>").click(function(event){
		var win = new Ext.Window({
			layout: 'fit',
			modal: true,
			title: 'Save Block',
			closable: false,
			items: Ext.create('Ext.form.Panel', {
				frame: true,
				autoScroll: true,
				bodyPadding: 5,
				fieldDefaults: {
					labelAlign: 'left',
					labelWidth: 90,
					anchor: '100%'
				},
				items: [{
					xtype: 'textfield',
					name: 'block_name',
					fieldLabel: 'Block Name',
					maskRe: /[\w\s\-]/,
					regex: /[\w\s\-]/,
					value: '',
					allowBlank: false
				},{
					xtype: 'numberfield',
					name: 'num_tier',
					fieldLabel: 'Number of Tier',
					value: 1,
					minValue: 1,
					allowBlank: false
				},Ext.create('Ext.form.ComboBox', {
					fieldLabel: 'Block Position',
					store: 'position',
					queryMode: 'local',
					displayField: 'name',
					valueField: 'value',
					name: 'block_position',
					allowBlank: false
				}),Ext.create('Ext.form.ComboBox', {
					fieldLabel: 'Slot-Row Orientation',
					store: 'orientation',
					queryMode: 'local',
					displayField: 'name',
					valueField: 'value',
					name: 'orientation',
					allowBlank: false
				}),{
					xtype: 'hidden',
					name: 'block_color',
					fieldLabel: 'Color',
					value: ''
				}],
				buttons: [{
					text: 'Save Block',
					formBind: true,
					handler: function() {
						if (this.up('form').getForm().isValid()){
							if (SetBlock_<?=$tab_id?>(this.up('form').getForm().findField("block_name").getValue(),this.up('form').getForm().findField("num_tier").getValue(),this.up('form').getForm().findField("block_position").getValue(),this.up('form').getForm().findField("orientation").getValue(),this.up('form').getForm().findField("block_color").getValue())){
								win.close();
							}
						}
					}
				},{
					text: 'Cancel',
					handler: function() {
						win.close();
					}
				}]
			})
		});
		win.show();
	});

	$("#update_yard_pop_up_<?=$tab_id?>").click(function(event){
		var str_north_orientation_edit = Ext.create('Ext.data.Store', {
		    fields: ['value', 'name'],
		    data : [
		        {"value":"U", "name":"Up"},
		        {"value":"R", "name":"Right"},
				{"value":"D", "name":"Down"},
		        {"value":"L", "name":"Left"}
		    ]
		});

		var win = new Ext.Window({
			layout: 'fit',
			modal: true,
			title: 'Update Yard',
			closable: false,
			items: Ext.create('Ext.form.Panel', {
				frame: true,
				autoScroll: true,
				bodyPadding: 5,
				fieldDefaults: {
					labelAlign: 'left',
					labelWidth: 90,
					anchor: '100%'
				},
				items: [
					{
						xtype: 'textfield',
						name: 'yard_name',
						fieldLabel: 'Yard Name',
						maskRe: /[\w\s\-]/,
						regex: /[\w\s\-]/,
						value: '<?=$name?>',
						allowBlank: false
					},
					{
						xtype: 'combobox',
						name: 'north_orientation',
						store: str_north_orientation_edit,
						fieldLabel: 'North Orientation',
						queryMode: 'local',
    					displayField: 'name',
    					valueField: 'value',
						allowBlank: false,
						value: '<?=$yard->NORTH_ORIENTATION?>',
					}
				],
				buttons: [{
					text: 'Update Yard',
					formBind: true,
					handler: function() {
						if (this.up('form').getForm().isValid()){
							var yard_name = this.up('form').getForm().findField("yard_name").getValue();
							var north_orientation = this.up('form').getForm().findField("north_orientation").getValue();

							if (UpdateYard_<?=$tab_id?>(yard_name, north_orientation)){
								win.close();
							}
						}
					}
				},{
					text: 'Cancel',
					handler: function() {
						win.close();
					}
				}]
			})
		});
		win.show();
	});

	function SetBlock_<?=$tab_id?>(name, tier, position, orientation, color){
		// event.preventDefault();
		// alert($("#result").html());
		var selected = $("#select-result_<?=$tab_id?>").html();
		// console.log(selected);
		var array_s  = selected.split(",");
		// console.log(array_s);
		// var color 	 = $("#block_color").val();
		// var name 	 = $("#block_name").val();
		// console.log("++"+selected+"++");
		var p = 0;
		var idx = -1;
		var id_block = '';
		for (var i = 0; i < count_block_<?=$tab_id?>; i++){
		    console.log('block_name : ' + block_name_<?=$tab_id?>[i] + ', name : ' + name);
			if(block_name_<?=$tab_id?>[i] == name){
			    console.log('id_block : ' + block_id_block_<?=$tab_id?>[i]);
				p = 1;
				idx = i;
				id_block = block_id_block_<?=$tab_id?>[i];
			}
		}

		if (color==""){
			color = 'BLACK';
		}

		// console.log(cell.length);
		 console.log('array_s.length : ' + array_s.length);
		var height=1;
		var width=1;
		var max_width=1;
		for (var i = 0; i < (array_s.length-1); i++){
			if (i==0){
				height=1;
				width=1;
			}else{
				if ((array_s[i]-1) == array_s[i-1]){
					width += 1;
				}else{
					height += 1;
					max_width = width;
					width=1;
				}
			}
			// console.log(array_s[i]);
			cell_<?=$tab_id?>[array_s[i]].block = name;
			// console.log("--"+cell[array_s[i]].block+"--");
			// console.log("--"+cell[array_s[i]].stack+"--");
			cell_<?=$tab_id?>[array_s[i]].stack = 1;

			var style = $("#selectable_<?=$tab_id?> li").eq(array_s[i]).attr( "style" );
			style = typeof style !== 'undefined' ? style : "";

			$("#selectable_<?=$tab_id?> li").eq(array_s[i]).attr( "class", "ui-stacking-default");
			$("#selectable_<?=$tab_id?> li").eq(array_s[i]).attr( "style", style + "  border: 1px solid "+color+"; " );
			$("#selectable_<?=$tab_id?> li").eq(array_s[i]).attr( "title", "Blok "+name );
		}
		console.log('p : ' + p);
		if (p == 0){
			block_act_<?=$tab_id?>[count_block_<?=$tab_id?>] = 'S';
			block_id_block_<?=$tab_id?>[count_block_<?=$tab_id?>]	 = '';
			block_name_<?=$tab_id?>[count_block_<?=$tab_id?>]	 = name;
			block_tier_<?=$tab_id?>[count_block_<?=$tab_id?>] = tier;
			block_position_<?=$tab_id?>[count_block_<?=$tab_id?>] = position;
			block_orientation_<?=$tab_id?>[count_block_<?=$tab_id?>] = orientation;
			block_color_<?=$tab_id?>[count_block_<?=$tab_id?>] = color;
			block_height_<?=$tab_id?>[count_block_<?=$tab_id?>] = height;
			block_width_<?=$tab_id?>[count_block_<?=$tab_id?>] = max_width;
			count_block_<?=$tab_id?>++;
		}else{
			block_act_<?=$tab_id?>[idx] = 'S';
			block_id_block_<?=$tab_id?>[idx]	 = id_block;
			block_name_<?=$tab_id?>[idx]	 = name;
			block_tier_<?=$tab_id?>[idx] = tier;
			block_position_<?=$tab_id?>[idx] = position;
			block_orientation_<?=$tab_id?>[idx] = orientation;
			block_color_<?=$tab_id?>[idx] = color;
			block_height_<?=$tab_id?>[idx] = height;
			block_width_<?=$tab_id?>[idx] = max_width;
		}

		$("#selected_width_<?=$tab_id?>").val("");
		$("#selected_height_<?=$tab_id?>").val("");
		return 1;
	}

	$("#unblock_<?=$tab_id?>").click(function(name, tier, position, orientation, color) {
		// event.preventDefault();
		//alert($("#result").html());
		var selected = $("#select-result_<?=$tab_id?>").html();
		var array_s  = selected.split(",");
		// var color 	 = $("#block_color").val();
		// var name 	 = $("#block_name").val();
		//console.log("++"+selected+"++");
		var id_block = '';
		var p = 0;
		var idx = -1;
		var height=1;
		var width=1;
		var max_width=1;
		for (var i = 0; i < (array_s.length-1); i++){
			if (i==0){
				height=1;
				width=1;
			}else{
				if ((array_s[i]-1) == array_s[i-1]){
					width += 1;
				}else{
					height += 1;
					max_width = width;
					width=1;
				}
			}
			
			cell_<?=$tab_id?>[array_s[i]].block = "";
			cell_<?=$tab_id?>[array_s[i]].stack = 0;

			var style = $("#selectable_<?=$tab_id?> li").eq(array_s[i]).attr( "style" );
			style = typeof style !== 'undefined' ? style : "";

			$("#selectable_<?=$tab_id?> li").eq(array_s[i]).attr( "class", "ui-state-default");
			$("#selectable_<?=$tab_id?> li").eq(array_s[i]).attr( "style", style + "  border: 1px solid #ffffff; " );
			$("#selectable_<?=$tab_id?> li").eq(array_s[i]).attr( "title", "" );
			if(id_block == ''){
			    id_block = $("#selectable_<?=$tab_id?> li").eq(array_s[i]).attr( "id-block");
			}
		}
		for (var i = 0; i < count_block_<?=$tab_id?>; i++){
		    console.log('block_name : ' + block_name_<?=$tab_id?>[i] + ', name : ' + name);
			if(block_id_block_<?=$tab_id?>[i] == id_block){
			    console.log('id_block : ' + block_id_block_<?=$tab_id?>[i]);
				p = 1;
				idx = i;
				id_block = block_id_block_<?=$tab_id?>[i];
			}
		}
		block_act_<?=$tab_id?>[idx] = 'U';
		block_id_block_<?=$tab_id?>[idx] = id_block;
		block_name_<?=$tab_id?>[idx]	 = '';
		block_tier_<?=$tab_id?>[idx] = '';
		block_position_<?=$tab_id?>[idx] = '';
		block_orientation_<?=$tab_id?>[idx] = '';
		block_color_<?=$tab_id?>[idx] = '';
		block_height_<?=$tab_id?>[idx] = height;
		block_width_<?=$tab_id?>[idx] = max_width;
		
		$("#selected_width_<?=$tab_id?>").val("");
		$("#selected_height_<?=$tab_id?>").val("");
	});

	function UpdateYard_<?=$tab_id?>(yard_name_, north_orientation){
		// event.preventDefault();
		//build width and height
		var width_str 	= "<width>"+slot_<?=$tab_id?>+"</width>";
		var height_str	= "<height>"+row_<?=$tab_id?>+"</height>";

		//build array of stacking area
		var j = 0;
		var index_stack = new Array();
		for (var i = 0; i < total_<?=$tab_id?>; i++){
			if(cell_<?=$tab_id?>[i].stack == 1){
				index_stack[j] = i;
				j++;
			}
		}
		var stack_ 		= index_stack.join(",");
		var stack_str	= "<index>"+stack_+"</index>";
		// console.log("=="+stack_str+"==");

		//build array of blocking area
		var index_block = new Array();
		var p = 0;
		for (var j = 0; j < count_block_<?=$tab_id?>; j++){
			index_block[j] = new Array();
			for (var i = 0; i < total_<?=$tab_id?>; i++){
				if(cell_<?=$tab_id?>[i].block == block_name_<?=$tab_id?>[j]){
					index_block[j][p] = i;
					p++;
				}
			}
			p = 0;
		}

		var block_str = "";
		for (var j = 0; j < count_block_<?=$tab_id?>; j++){
			if (index_block[j].length>0){
				block_str += "<block><act>"+block_act_<?=$tab_id?>[j]+"</act><id_block>"+block_id_block_<?=$tab_id?>[j]+"</id_block><name>"+block_name_<?=$tab_id?>[j]+"</name><color>"+block_color_<?=$tab_id?>[j]+"</color><tier>"+block_tier_<?=$tab_id?>[j]+"</tier><position>"+block_position_<?=$tab_id?>[j]+"</position><orientation>"+block_orientation_<?=$tab_id?>[j]+"</orientation><height>"+block_height_<?=$tab_id?>[j]+"</height><width>"+block_width_<?=$tab_id?>[j]+"</width><cell>"+index_block[j].join(",")+"</cell></block>";
			}
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		// console.log(xml_str);
		var url = "<?=controller_?>yard_editor/modify_yard";
		// var yard_name_ = $("#yard_name").val();

		loadmask.show();
		$.post( url+"?id_yard=<?=$id_yard?>", { xml_: xml_str, yard_name : yard_name_, north_orientation: north_orientation}, function(data) {
			// console.log(data);
			if (data=="0"){
			    loadmask.hide();
			    Ext.Msg.alert('Failed', 'Update yard failed. Please try again or contact your administrator');
			}else{
			    loadmask.hide();
			    Ext.Msg.alert('Success', data);
			    Ext.getCmp('<?=$tab_id?>').close();
			}
		});
		return true;
	}
</script>
