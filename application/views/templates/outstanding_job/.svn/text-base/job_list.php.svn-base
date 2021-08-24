<script type="text/javascript">
	Ext.onReady(function(){
		var ct_store = Ext.create('Ext.data.Store', {
			fields:['VESSEL', 'VOY_IN', 'VOY_OUT', 'IN_QUAY_JOB', 'IN_YARD_PLACEMENT', 'IN_YARD_ONCHASIS', 'IN_GT_TRUCKORDER', 'IN_GT_TRUCKIN', 'IN_GT_INS_DEL', 'IN_GT_TRUCKOUT', 'OUT_QUAY_JOB', 'OUT_YARD_PLACEMENT', 'OUT_YARD_ONCHASIS', 'OUT_GT_INS_REC', 'OUT_GT_TRUCKIN', 'OUT_GT_TRUCKOUT'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>outstanding_job/data_job_list',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 100
		});
		
		var ct_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var ct_grid = Ext.create('Ext.grid.Panel', {
			store: ct_store,
			loadMask: true,
			width: 1900,
			height: 440,
			columns: [
				{ text: 'Vessel', dataIndex: 'VESSEL', width: 180, filter: {type: 'string'}},
				{ text: 'Voy IN', dataIndex: 'VOY_IN', width: 80},
				{ text: 'Voy OUT', dataIndex: 'VOY_OUT', width: 80},
				{ text: 'IN Quay Job', dataIndex: 'IN_QUAY_JOB' , width: 120},
				{ text: 'IN Placement', dataIndex: 'IN_YARD_PLACEMENT' , width: 120},
				{ text: 'IN Truck Order', dataIndex: 'IN_GT_TRUCKORDER', width: 120},
				{ text: 'IN Truck In', dataIndex: 'IN_GT_TRUCKIN' , width: 120},
				{ text: 'IN OnChasis', dataIndex: 'IN_YARD_ONCHASIS' , width: 120},					
				{ text: 'IN Inspection', dataIndex: 'IN_GT_INS_DEL' , width: 120},
				{ text: 'IN Truck Out', dataIndex: 'IN_GT_TRUCKOUT' , width: 120},
				{ text: 'OUT Inspection', dataIndex: 'OUT_GT_INS_REC' , width: 120},
				{ text: 'OUT Truck In', dataIndex: 'OUT_GT_TRUCKIN', width: 120 },
				{ text: 'OUT Placement', dataIndex: 'OUT_YARD_PLACEMENT' , width: 120},
				{ text: 'OUT Truck Out', dataIndex: 'OUT_GT_TRUCKOUT' , width: 120},
				{ text: 'OUT OnChasis', dataIndex: 'OUT_YARD_ONCHASIS', width: 120},
				{ text: 'OUT Quay Job', dataIndex: 'OUT_QUAY_JOB' , width: 120}						
			],
			viewConfig : {
				enableTextSelection: true
			},
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: ct_store,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							ct_grid.filters.clearFilters();
						}
					}
				]
			})],
			features: [ct_filters],
			emptyText: 'No Data Found'
		});
		
		ct_grid.render('job_list_<?=$tab_id?>');
	});
</script>
<div id="job_list_<?=$tab_id?>"></div>