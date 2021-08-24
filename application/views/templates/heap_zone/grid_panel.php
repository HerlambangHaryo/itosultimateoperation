<script type="text/javascript">
	Ext.onReady(function() {
		var heapzone_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['ID_HEAPZONE', 'HEAPZONE_NAME', 'OWNER', 'CAPACITY'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>heap_zone/data_heapzone',
				reader: {
					type: 'json'
				}
			}
		});
		
		var heapzone_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'heapzone_grid_<?=$tab_id?>',
			store: heapzone_store_<?=$tab_id?>,
			width: 420,
			columns: [
				{ dataIndex: 'ID_HEAPZONE', hidden: true, hideable: false},
				{ text: 'Heap Zone Name', dataIndex: 'HEAPZONE_NAME', width: 150},
				{ text: 'Owner', dataIndex: 'OWNER' , width: 100},
				{ text: 'Capacity', dataIndex: 'CAPACITY', width: 80},
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
							addTab('center_panel', 'heap_zone/detail_panel', rec.get('ID_HEAPZONE'), 'Heap Zone Detail');
						}
					},'-',{
						icon: "<?=IMG_?>icons/delete.png",
						tooltip: 'Delete',
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.getStore().getAt(rowIndex);
							loadmask.show();
							var url = "<?=controller_?>heap_zone/delete_heapzone";
							$.post( url, { ID_HEAPZONE: rec.get('ID_HEAPZONE')}, function(data) {
								loadmask.hide();
								if(data=="1"){
									Ext.Msg.alert('Success', 'Heap Zone Deleted');
									grid.getStore().reload();
								}else{
									Ext.Msg.alert('Failed', 'Failed to Delete Heap Zone');
								}
							});
						}
					}]
				}
			],
			tbar: [
					{ xtype: 'button', 
						text: 'Add Heap Zone',
						handler: function (){
							addTab('center_panel', 'heap_zone/add_panel', '', 'Add Heap Zone');
						}
					}
			],
			bbar: [{
				xtype: 'button',
				text: 'Refresh Data',
				handler: function (){
					Ext.getCmp('heapzone_grid_<?=$tab_id?>').getStore().reload();
				}
			}],
		});
		
		Ext.getCmp('west_panel').expand();
		heapzone_grid_<?=$tab_id?>.render('heapzone_grid_<?=$tab_id?>');
	});
</script>
<div id="heapzone_grid_<?=$tab_id?>"></div>