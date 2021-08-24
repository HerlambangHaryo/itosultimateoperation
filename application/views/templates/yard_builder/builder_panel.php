<?php
	$L	= $width * $height;
	$s = 20;
	$grid_width = ($s*$width)+8;
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
var block_name_<?=$tab_id?> 		= new Array();
var block_tier_<?=$tab_id?> 		= new Array();
var block_position_<?=$tab_id?> 	= new Array();
var block_orientation_<?=$tab_id?> 	= new Array();
var block_height_<?=$tab_id?> 		= new Array();
var block_width_<?=$tab_id?> 		= new Array();
var block_color_<?=$tab_id?>		= new Array();

var count_block_<?=$tab_id?> = 0;
var slot_<?=$tab_id?> 		 = <?php echo $width;?>;
var row_<?=$tab_id?>		 = <?php echo $height;?>;

var total_<?=$tab_id?> 		 = row_<?=$tab_id?>*slot_<?=$tab_id?>;
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
				<input type="button" id="save_yard_pop_up_<?=$tab_id?>" class="button_save_yard_pop_up" value=" Save Yard " name="save_yard_pop_up"/>
			</div>
		</td>
	</tr>
</table>
</center>
</div>
<br/>
<img src="<?=IMG_?>icons/compass.png" width="80px" style="margin: 20px 0px 20px 60px"/>
<span id="select-result_<?=$tab_id?>" style="display: none;"></span>
<span id="result_<?=$tab_id?>"></span>

<center>
	<div class="grid_<?=$tab_id?>">
		<table border="0" width="100%">
			<tr align="center" valign="top">
				<td align="center" valign="middle" style="padding-left: 2px; padding-right: 2px;">
					<ol id="selectable_<?=$tab_id?>">
						<?php
							$j = 1;
							for($i = 1; $i <= $L; $i++){
								$m = ($width*$j) + 1;
						?>
								<li class="ui-state-default" index="<?=$i-1?>"<?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>></li>
						<?php
							}
						?>
					</ol>
				</td>
			</tr>
		</table>
		
	</div>
</center>

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


	$("#save_yard_pop_up_<?=$tab_id?>").click(function(event){
		var str_north_orientation = Ext.create('Ext.data.Store', {
		    fields: ['value', 'name'],
		    data : [
		        {"value":"U", "name":"Up"},
		        {"value":"R", "name":"Right"},
				{"value":"D", "name":"Down"},
		        {"value":"L", "name":"Left"}
		    ]
		});
		var str_sea_position = Ext.create('Ext.data.Store', {
		    fields: ['value', 'name'],
		    data : [
		        {"value":"Up", "name":"Up"},
		        {"value":"Right", "name":"Right"},
				{"value":"Down", "name":"Down"},
		        {"value":"Left", "name":"Left"}
		    ]
		});

		var win = new Ext.Window({
			layout: 'fit',
			modal: true,
			title: 'Save Yard',
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
						value: '',
						allowBlank: false
					},{
						xtype: 'combobox',
						name: 'north_orientation',
						store: str_north_orientation,
						fieldLabel: 'North Orientation',
						queryMode: 'local',
    					displayField: 'name',
    					valueField: 'value',
						allowBlank: false
					},{
						xtype: 'combobox',
						name: 'sea_position',
						store: str_sea_position,
						fieldLabel: 'Sea Position',
						queryMode: 'local',
    					displayField: 'name',
    					valueField: 'value',
						allowBlank: false
					}
				],
				buttons: [{
					text: 'Save Yard',
					formBind: true,
					handler: function() {
						if (this.up('form').getForm().isValid()){
							var yard_name = this.up('form').getForm().findField("yard_name").getValue();
							var north_orientation = this.up('form').getForm().findField("north_orientation").getValue();
							var sea_position = this.up('form').getForm().findField("sea_position").getValue();

							if (SaveYard_<?=$tab_id?>(yard_name, north_orientation, sea_position)){
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
		for (var i = 0; i < count_block_<?=$tab_id?>; i++){
			if(block_name_<?=$tab_id?>[i] == name){
				p = 1;
				idx = i;
			}
		}

		if (color==""){
			color = 'BLACK';
		}

		// console.log(cell.length);
		// console.log(array_s.length);
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

		if (p == 0){
			block_name_<?=$tab_id?>[count_block_<?=$tab_id?>]	 = name;
			block_tier_<?=$tab_id?>[count_block_<?=$tab_id?>] = tier;
			block_position_<?=$tab_id?>[count_block_<?=$tab_id?>] = position;
			block_orientation_<?=$tab_id?>[count_block_<?=$tab_id?>] = orientation;
			block_color_<?=$tab_id?>[count_block_<?=$tab_id?>] = color;
			block_height_<?=$tab_id?>[count_block_<?=$tab_id?>] = height;
			block_width_<?=$tab_id?>[count_block_<?=$tab_id?>] = max_width;
			count_block_<?=$tab_id?>++;
		}else{
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

	$("#unblock_<?=$tab_id?>").click(function(event) {
		// event.preventDefault();
		//alert($("#result").html());
		var selected = $("#select-result_<?=$tab_id?>").html();
		var array_s  = selected.split(",");
		// var color 	 = $("#block_color").val();
		// var name 	 = $("#block_name").val();
		//console.log("++"+selected+"++");
		for (var i = 0; i < (array_s.length-1); i++){
			cell_<?=$tab_id?>[array_s[i]].block = "";
			cell_<?=$tab_id?>[array_s[i]].stack = 0;

			var style = $("#selectable_<?=$tab_id?> li").eq(array_s[i]).attr( "style" );
			style = typeof style !== 'undefined' ? style : "";

			$("#selectable_<?=$tab_id?> li").eq(array_s[i]).attr( "class", "ui-state-default");
			$("#selectable_<?=$tab_id?> li").eq(array_s[i]).attr( "style", style + "  border: 1px solid #ffffff; " );
			$("#selectable_<?=$tab_id?> li").eq(array_s[i]).attr( "title", "" );
		}

		$("#selected_width_<?=$tab_id?>").val("");
		$("#selected_height_<?=$tab_id?>").val("");
	});

	function SaveYard_<?=$tab_id?>(yard_name_, north_orientation, sea_position){
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
				block_str += "<block><name>"+block_name_<?=$tab_id?>[j]+"</name><color>"+block_color_<?=$tab_id?>[j]+"</color><tier>"+block_tier_<?=$tab_id?>[j]+"</tier><position>"+block_position_<?=$tab_id?>[j]+"</position><orientation>"+block_orientation_<?=$tab_id?>[j]+"</orientation><height>"+block_height_<?=$tab_id?>[j]+"</height><width>"+block_width_<?=$tab_id?>[j]+"</width><cell>"+index_block[j].join(",")+"</cell></block>";
			}
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		// console.log(xml_str);
		var url = "<?=controller_?>yard_builder/create_yard";
		// var yard_name_ = $("#yard_name").val();

		loadmask.show();
		$.post( url, { xml_: xml_str, yard_name : yard_name_, north_orientation: north_orientation, sea_position : sea_position}, function(data) {
			// console.log(data);
			if (data=="1"){
				loadmask.hide();
				Ext.Msg.alert('Success', 'Yard Saved');
				Ext.getCmp('<?=$tab_id?>').close();
			}
		});
		return true;
	}
</script>
