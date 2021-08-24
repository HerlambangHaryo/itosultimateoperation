<script type="text/javascript">
	Ext.onReady(function(){
		var group_store = Ext.create('Ext.data.Store', {
			fields:['ID_YARD_PLAN', 'YARD_NAME', 'BLOCK_NAME', 'SLOT_RANGE', 'ROW_RANGE', 'CAPACITY', 'CATEGORY_NAME', 'ID_CATEGORY'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>stowage_instruction_summary/data_yard_plan_group',
				reader: {
					type: 'json',
					root: 'data',
					idProperty: 'ID_YARD_PLAN',
					totalProperty: 'total'
				}
			},
			groupField: 'CATEGORY_NAME'
		});
		
		var group_grid = Ext.create('Ext.grid.Panel', {
			id: 'yd_group_<?=$tab_id?>',
			store: group_store,
			loadMask: true,
			width: 700,
			columns: [
				{ dataIndex: 'ID_CATEGORY', hidden: true, hideable: false},
				{ text: 'POD', dataIndex: 'YARD_NAME', width: 150},
				{ text: 'Size', dataIndex: 'BLOCK_NAME' , width: 150},
				{ text: 'Type', dataIndex: 'SLOT_RANGE' , width: 100},
				{ text: 'Commodity', dataIndex: 'ROW_RANGE', width: 100},
				{ text: 'F/M', dataIndex: 'CAPACITY', width: 100},
				{ text: 'ID Yard Plan', dataIndex: 'ID_YARD_PLAN', hidden: true, hideable: false },
				{
					text: 'Delete',
					xtype: 'actioncolumn',
					width: 70,
					items: [{
						icon: "<?=IMG_?>icons/delete.png",
						tooltip: 'Delete',
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.getStore().getAt(rowIndex);
							loadmask.show();
							var url = "<?=controller_?>yard_planning_group/delete_yard_plan_group";
							$.post( url, { id_yard_plan: rec.get('ID_YARD_PLAN')}, function(data) {
								// console.log(data);
								loadmask.hide();
								if (data=='1'){
									Ext.Msg.alert('Success', 'Yard Plan Deleted');
									grid.getStore().reload();
								}else{
									Ext.Msg.alert('Failed', 'Exist Container(s) currently stacking!');
								}
							});
						}
					}]
				}
			],
			
			// tbar: [{
				// xtype: 'button',
				// text: 'Refresh Data',
				// // handler: function (){
					// // Ext.getCmp('yd_group_<?=$tab_id?>').getStore().reload();
				// // }
				// items: [
						// {text:'Toolbar 1',
						 // handler: function (){
						// Ext.getCmp('yd_group_<?=$tab_id?>').getStore().reload();
						// }}
						// ,
						// {text:'Toolbar 2'}
					// ]
			// }],
			
			dockedItems: [
				{
					xtype: 'toolbar',
					dock: 'top',
					items: [
						{text:'Refresh',
							 handler: function (){
							Ext.getCmp('yd_group_<?=$tab_id?>').getStore().reload();
							}
						}
						// ,
						// {text:'Toolbar 2'}
					]
				},
				{
					xtype: 'toolbar',
					dock: 'top',
					items: [
						{text:'Add Data',
							handler: function (id_category){
							
							Ext.Ajax.request({
							url: '<?=controller_?>stowage_instruction_summary/popup_new_category?tab_id=<?=$tab_id?>',
							params: {
								id_category : id_category,
								edit_mode : 1,
								id_ves_voyage: '<?=$id_ves_voyage?>'
							},
							callback: function(opt,success,response){
								$("#popup_script_<?=$tab_id?>").html(response.responseText);
							} 
						});
							
							}
						}
						
					]
				}
			],
			
			features: [{
				ftype: 'groupingsummary',
				groupHeaderTpl: 'Category: <input type="button" value="{name}" onclick="edit_category_<?=$tab_id?>(\'{[values.rows[0].data.ID_CATEGORY]}\')"/>',
				showSummaryRow: false
			}],
			emptyText: 'No Data Found'
		});
		
		group_grid.render('group_grid_<?=$tab_id?>');
	});
	
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
</script>
<div id="group_grid_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>