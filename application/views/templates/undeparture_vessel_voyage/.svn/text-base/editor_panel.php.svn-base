<script type="text/javascript">
	Ext.onReady(function(){
		var vessel_departure_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_VES_VOYAGE', 'VESSEL_NAME', 'VOY_IN', 'VOY_OUT', 'ARRIVAL', 'BERTH', 'DEPARTURE'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>undeparture_vessel_voyage/data_vessel_departure/',
				reader: {
					type: 'json',
					root: 'data',
					idProperty: 'ID_VES_VOYAGE',
					totalProperty: 'total'
				}
			},
			pageSize: 20,
			sorters: [{
				property: 'DEPARTURE',
				direction: 'DESC'
			}]
		});

		var ct_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};

		var grid = Ext.create('Ext.grid.Panel', {
			id: 'vessel_list_grid_<?=$tab_id?>',
			store: vessel_departure_list_store,
			loadMask: true,
			width: 800,
			columns: [
				{ xtype: 'rownumberer'},
				{ dataIndex: 'ID_VES_VOYAGE', hidden: true, hideable: false},
				{ text: 'Vessel Name', dataIndex: 'VESSEL_NAME', width: 200, filter: {type: 'string'}},
				{ text: 'Voyage In', dataIndex: 'VOY_IN', width: 100},
				{ text: 'Voyage Out', dataIndex: 'VOY_OUT', width: 100},
				{ text: 'ATA', dataIndex: 'ARRIVAL', width: 120, filter: {type: 'date'}},
				{ text: 'ATB', dataIndex: 'BERTH', width: 120},
				{ text: 'ATD', dataIndex: 'DEPARTURE', width: 120}
			],
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: vessel_departure_list_store,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}'
			})],
			tbar: [{
				itemId: 'undeparture_vessel_<?=$tab_id?>',
				text: 'Undeparture',
				handler: function() {
					Ext.MessageBox.confirm('Confirm', 'Are you sure you want to do that?', function(confirmation){
						if (confirmation=='yes'){
							var sm = grid.getSelectionModel();
							var selected = sm.getSelection();
							loadmask.show();
							Ext.Ajax.request({
								url: '<?=controller_?>undeparture_vessel_voyage/set_undeparture_vessel_voyage/' + selected[0].data.ID_VES_VOYAGE,
								success: function(response){
									var text = response.responseText;
									if (text=='1'){
										Ext.MessageBox.show({
											title: 'Success',
											msg: 'Changes saved successfully.',
											buttons: Ext.MessageBox.OK
										});
										vessel_departure_list_store.remove(selected);
										if (vessel_departure_list_store.getCount() > 0) {
											sm.select(0);
										}
										vessel_departure_list_store.reload();
										vessel_schedule_store.reload();
									}else{
										Ext.MessageBox.show({
											title: 'Error',
											msg: 'Failed to save changes.',
											buttons: Ext.MessageBox.OK
										});
									}
									loadmask.hide();
								}
							});
						}
					});
				},
				disabled: true
			}],
			listeners: {
				'selectionchange': function(view, records) {
					grid.down('#undeparture_vessel_<?=$tab_id?>').setDisabled(!records.length);
				}
			},
			features: [ct_filters],
			emptyText: 'No Data Found'
		});

		grid.render('vessel_list_<?=$tab_id?>');
	});
</script>

<div id="vessel_list_<?=$tab_id?>"></div>
