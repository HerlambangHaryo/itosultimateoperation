<script type="text/javascript">
	Ext.onReady(function(){
		var tr_store = Ext.create('Ext.data.Store', {
			fields:['NO_POL', 'GTIN_DATE', 'IN_TERMINAL_DURATION'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>external_truck_monitoring/data_truck_list',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 100
		});
		
		var tr_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var tr_grid = Ext.create('Ext.grid.Panel', {
			store: tr_store,
			loadMask: true,
			width: 600,
			columns: [
				{ text: 'Truck Number', dataIndex: 'NO_POL', width: 150, filter: {type: 'string'}},
				{ text: 'Gate In Date', dataIndex: 'GTIN_DATE', width: 200},
				{ text: 'Duration in Terminal', dataIndex: 'IN_TERMINAL_DURATION', width: 200}
			],
			viewConfig : {
				enableTextSelection: true
			},
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: tr_store,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							tr_grid.filters.clearFilters();
						}
					}
				]
			})],
			features: [tr_filters],
			emptyText: 'No Data Found'
		});
		
		tr_grid.render('truck_list_<?=$tab_id?>');
	});
</script>
<div id="truck_list_<?=$tab_id?>"></div>