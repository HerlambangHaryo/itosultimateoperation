<script type="text/javascript">
	var block_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_BLOCK', 'BLOCK_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>report_yor/get_data_yor_blockname/',
			reader: {
				type: 'json'
			}
		},
	
	});

	var io_<?=$tab_id?> = Ext.create('Ext.data.Store', {
            fields: ['valx', 'name'],
            data: [
	                {"valx": "","name": "-- All --"},
	                {"valx": "Outbound","name": "Outbound"},
	                {"valx": "Inbound","name": "Inbound"}
            ],
	});

	var vessel_<?=$tab_id?> = Ext.create('Ext.data.Store',{
			fields: ['ID_VES_VOYAGE','NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>report_yor/get_data_vessel/',
				reader: {
					type: 'json'
				}
			},
	});

	var DT_<?=$tab_id?> = Ext.create('Ext.data.Store', {
            fields: ['valx', 'name'],
            data: [
	                {"valx": "","name": "-- All --"},
	                {"valx": "Outbound","name": "Outbound"},
	                {"valx": "Inbound","name": "Inbound"}
            ],
	});

	Ext.create('Ext.form.Panel', {
		id: "yard_grid_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		items: [
		// FIELD SELECT VESSEL
		{
			xtype: 'fieldset',
			title: 'FILTER',
			items: [
			// block
			{
				id: "block_<?=$tab_id?>",
				xtype: 'combo',
				displayField: 'BLOCK_NAME',
				valueField: 'BLOCK_NAME',
				emptyText: 'filter by block',
				editable: true,
				store: block_<?=$tab_id?>,
				name: "FILTER_BLOCK",
				fieldLabel: 'By Block',
				width: 500
			}
			]
		},

		],
		buttons: [
			{
			text: 'Show',
			//formBind: true,
			handler: function() {
				loadmask.show();
				var filter_block = $('#block_<?=$tab_id?>-inputEl').val();
				Ext.Ajax.request({
					url: '<?=controller_?>report_yor/get_data_yor_show',
					params: {
						filter_block: filter_block,
					},
					success: function(response){
						var text = response.responseText;
						loadmask.hide();
						if(response.status=='200'){
							$('#reportyor<?=$tab_id?>').html(response.responseText);
						}else{
							Ext.Msg.alert('Failed', 'Vessel Voyage Tidak Valid');
						}
					}
				});
			}
			}
			,{
			text: 'EXPORT TO EXCEL',
			handler: function (){
				var filter_block = $('#block_<?=$tab_id?>-inputEl').val();
				// console.log(block);
				window.open('<?=controller_?>report_yor/get_data_yor?filter_block='+filter_block,'_blank');
			} 
		}]
	}).render('yard_grid_<?=$tab_id?>');
// ######
	// 	items: [{
	// 		xtype: 'button', 
	// 		text: 'EXPORT TO EXCEL',
	// 		handler: function (){
	// 			window.open('<?=controller_?>report_yor/get_data_yor/','_blank');
	// 		} 
	// 	}]
	// }).render('yard_grid_<?=$tab_id?>');
	
	$(document).ready(function (){
		
	});
</script>
<div id="yard_grid_<?=$tab_id?>"></div>
<div id="reportyor<?=$tab_id?>"></div>
<style>
#reportyor<?=$tab_id?> .Middle{
}
#reportyor<?=$tab_id?> .CenterAndMiddle{
	text-align: center;
	vertical-align:middle;
}

/*table styling*/
#reportyor<?=$tab_id?> table, #reportyor<?=$tab_id?> td, #reportyor<?=$tab_id?> th {  
  border: 1px solid #ddd;
  text-align: left;
}

#reportyor<?=$tab_id?> table {
    border-collapse: collapse;
    width: calc(100% - 80px);
}

#reportyor<?=$tab_id?> th, #reportyor<?=$tab_id?> td {
  padding: 7px;
}
</style>