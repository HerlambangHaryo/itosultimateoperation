<script type="text/javascript">
	Ext.onReady(function() {
		var vsparticular_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['ID_VESSEL', 'VESSEL_NAME', 'CALL_SIGN', 'LENGTH'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>vessel_particular/data_vesparticular',
				reader: {
					type: 'json'
				}
			}
		});
		
		var particular_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'particular_grid_<?=$tab_id?>',
			store: vsparticular_store_<?=$tab_id?>,
			width: 650,
			columns: [
				{ text: 'Vessel Code', dataIndex: 'ID_VESSEL', width: 100},
				{ text: 'Vessel Name', dataIndex: 'VESSEL_NAME' , width: 150},
				{ text: 'Call Sign', dataIndex: 'CALL_SIGN', width: 80},
				{ text: 'LOA', dataIndex: 'LENGTH', width: 80},
				{ 
					text: 'Actions',
					width: 90,
					align: 'center',
					xtype: 'actioncolumn',
					items: [{
						icon: "<?=IMG_?>icons/edit.png",
						tooltip: 'Edit',
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.getStore().getAt(rowIndex);
							addTab('center_panel', 'vessel_particular/detail_panel', rec.get('ID_VESSEL'), 'Vessel Particular Detail');
						}
					},'-',{
						icon: "<?=IMG_?>icons/delete.png",
						tooltip: 'Delete',
						handler: function(grid, rowIndex, colIndex) {
							Ext.MessageBox.confirm('Confirm', 'Are you sure you want to do that?', function(confirmation){
								if (confirmation=='yes'){
									var rec = grid.getStore().getAt(rowIndex);
									loadmask.show();
									var url = "<?=controller_?>vessel_particular/delete_vesparticular";
									$.post( url, { id_vessel: rec.get('ID_VESSEL')}, function(data) {
										loadmask.hide();
										Ext.Msg.alert('Success', 'Vessel Particular Deleted');
										grid.getStore().reload();
									});
								}
							});
						}
					}]
				}
			],
			tbar: [
					{ xtype: 'button', 
					   text: 'Add Particular',
					   handler: function (){
					   		addTab('center_panel', 'vessel_particular/form_addParticular', '', 'Add Particular');
					   } 
					},
					{
						id: 'vessname_<?=$tab_id?>',
						// fieldLabel: 'Nick Name',
						// afterLabelTextTpl: required,
						xtype: 'textfield',
						allowBlank: false,
						name: 'vessname'
					},
					{
					xtype: 'button',
					cls: 'btn-search',
						handler: function (){
							vsparticular_store_<?=$tab_id?>.getProxy().extraParams = {
								ves_name: Ext.getCmp('vessname_<?=$tab_id?>').getValue()
							};
							Ext.getCmp('particular_grid_<?=$tab_id?>').getStore().reload();
						}
					}
			],
			bbar: [{
				xtype: 'button',
				text: 'Refresh Data',
				handler: function (){
					Ext.getCmp('particular_grid_<?=$tab_id?>').getStore().reload();
				}
			}],
		});
		
		Ext.getCmp('west_panel').expand();
		particular_grid_<?=$tab_id?>.render('particular_grid_<?=$tab_id?>');
	});
</script>
<div id="particular_grid_<?=$tab_id?>"></div>