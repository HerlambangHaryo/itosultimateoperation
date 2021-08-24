<script>
	var yardlist_store = Ext.create('Ext.data.Store', {
		fields:['NAME', 'NUM_BLOCK', 'ID_YARD'],
		autoLoad: true,
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_configuration/data_yard_list/',
			reader: {
				type: 'json'
			}
		}
	});

	var yardlist_grid = Ext.create('Ext.grid.Panel', {
		store: yardlist_store,
		id: 'yard_list_grid',
		columns: [
			{ text: 'Name', dataIndex: 'NAME' , width: 180},
			{ text: '# of Block', dataIndex: 'NUM_BLOCK' },
			{ text: 'ID Yard', dataIndex: 'ID_YARD', hidden: true, hideable: false },
			{
				text: 'Edit',
				xtype: 'actioncolumn',
				width: 50,
				items: [{
					icon: "<?=IMG_?>icons/edit.png",
					tooltip: 'Edit',
					handler: function(grid, rowIndex, colIndex) {
						var rec = grid.getStore().getAt(rowIndex);
						loadmask.show();
						Ext.getCmp('<?=$tab_id?>').setTitle('Yard Configuration-' + rec.get('NAME'));
						Ext.getCmp('<?=$tab_id?>').getLoader().load({
							url: '<?=controller_?>yard_configuration/editor_panel?tab_id=<?=$tab_id?>&id_yard='+rec.get('ID_YARD'),
							scripts: true,
							contentType: 'html',
							autoLoad: true,
							success: function(){
								loadmask.hide();
							}
						});
						win.close();
					}
				}]
			}
		],
		emptyText: 'No Data Found'
	});

	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Choose Yard',
		height: 500,
		width: 350,
		closable: false,
		autoScroll: true,
		items: yardlist_grid,
		buttons: [{
			text: 'Cancel',
			handler: function() {
				win.close();
				Ext.getCmp('<?=$tab_id?>').close();
			}
		}]
	});
	win.show();
</script>
