<style type="text/css">
	tr.x-grid-row.small-vessel.small-vessel.x-grid-data-row{
		color: blue !important;
	}
</style>
<script type="text/javascript">
	var vessel_schedule_store;
	Ext.onReady(function(){
		vessel_schedule_store = Ext.create('Ext.data.Store', {
			fields:['VESSEL_DETAIL', 'ARRIVAL', 'BERTH', 'DEPARTURE', 'VESSEL_NAME', 'ID_VES_VOYAGE', 'FL_TONGKANG'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>main/data_vessel_schedule',
				reader: {
					type: 'json',
					root: 'data',
					idProperty: 'ID_VES_VOYAGE',
					totalProperty: 'total'
				}
			},
			pageSize: 20,
			sorters: [{
				property: 'ARRIVAL',
				direction: 'ASC'
			}]
		});
		
		var vs_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var vs_grid = Ext.create('Ext.grid.Panel', {
			viewConfig: { 
		        stripeRows: false, 
		        getRowClass: function(record) { 
		            return record.get('FL_TONGKANG') == 'Y' ? 'small-vessel' : 'big-vessel'; 
		        }
		    },
			store: vessel_schedule_store,
			loadMask: true,
			width: 950,
			columns: [
				{ text: 'Vessel/Voyage', dataIndex: 'VESSEL_DETAIL', width: 150, filter: {type: 'string'}},
				{ text: 'Arrival', dataIndex: 'ARRIVAL' , width: 150},
				{ text: 'Berth', dataIndex: 'BERTH' , width: 150},
				{ text: 'Departure', dataIndex: 'DEPARTURE' , width: 150},
				{ text: 'Vessel Name', dataIndex: 'VESSEL_NAME' , width: 200},
				{ text: 'Vessel Voyage', dataIndex: 'ID_VES_VOYAGE', width: 120}
			],
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: vessel_schedule_store,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items : [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							vs_grid.filters.clearFilters();
						}
					},'-',{
						text: 'Deselect Data',
						handler: function () {
							vs_grid.getSelectionModel().deselectAll();
							id_ves_voyage = '';
						}
					}
				]
			})],
			features: [vs_filters],
			emptyText: 'No Data Found'
		});
		
		vs_grid.getSelectionModel().on('selectionchange', function(sm, selectedRecord) {
			if (selectedRecord.length) {
				id_ves_voyage = selectedRecord[0].data.ID_VES_VOYAGE;
			}
		});
		
		vs_grid.render('vs_grid');
	});
</script>
<div id="vs_grid"></div>