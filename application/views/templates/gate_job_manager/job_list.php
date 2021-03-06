<script type="text/javascript">
	Ext.onReady(function(){
		var ct_store = Ext.create('Ext.data.Store', {
			fields:['NO_CONTAINER', 'POINT', 'IO', 'ID_VES_VOYAGE', 'HAZARD', 'TL_FLAG', 'TID', 'WEIGHT', 'ID_AXLE', 'GTIN_DATE', 'ID_CLASS_CODE', 'CONT_STATUS', 'ID_ISO_CODE', 'ID_POD', 'ID_OPERATOR', 'YARD_POS', 'PAYMENT_STATUS', 'TRX_NUMBER', 'PAYTHROUGH_DATE'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>gate_job_manager/data_job_list',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 100,
			sorters: [{
				property: 'NO_CONTAINER',
				direction: 'ASC'
			}],
			listeners: {
				load: function(store){
					console.log(store);
				},
				filterchange: function(store, filters, opts) {
					console.log(filters);
				}
			}
		});
		
		var ct_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};

		var ct_grid = Ext.create('Ext.grid.Panel', {
			id: 'gp_ct',
			store: ct_store,
			loadMask: true,
			width: 1850,
			height: 440,
			columns: [
				{ text: 'No Container', dataIndex: 'NO_CONTAINER', width: 140, filter: {type: 'string'}},
				{ dataIndex: 'POINT', hidden: true, hideable: false},
				{ text: 'R/D', dataIndex: 'IO', width: 80 },
				// pergantian dari I/O (inbound/outbound) menjadi R/D (receive/deliver)
				{ text: 'Vessel', dataIndex: 'ID_VES_VOYAGE', width: 140, filter: {type: 'string'}},
				{ text: 'Hazard', dataIndex: 'HAZARD' , width: 80, filter: {type: 'string'}},
				{ text: 'TL', dataIndex: 'TL_FLAG' , width: 80, filter: {type: 'string'}},
				{ text: 'Truck', dataIndex: 'TID' , width: 80, filter: {type: 'string'}},
				{ text: 'WGT(Ton)', dataIndex: 'WEIGHT', width: 80, xtype: 'numbercolumn', format:'0.0'},
				{ text: 'Axle', dataIndex: 'ID_AXLE' , width: 80, filter: {type: 'string'}},
				{ text: 'Truck In Date', dataIndex: 'GTIN_DATE' , width: 150, filter: {type: 'date'}},
				{ text: 'Truck Out Date', dataIndex: 'GTOUT_DATE' , width: 150, filter: {type: 'date'}},
				{ text: 'Class', dataIndex: 'ID_CLASS_CODE' , width: 80, filter: {type: 'string'}},
				{ text: 'F/M', dataIndex: 'CONT_STATUS' , width: 80, filter: {type: 'string'}},
				{ text: 'ISO', dataIndex: 'ID_ISO_CODE' , width: 80, filter: {type: 'string'}},
				{ text: 'POD', dataIndex: 'ID_POD', width: 80, filter: {type: 'string'}},
				{ text: 'OPR', dataIndex: 'ID_OPERATOR' , width: 80, filter: {type: 'string'}},
				{ text: 'Yard', dataIndex: 'YARD_POS', width: 100},
				{ text: 'Payment', dataIndex: 'PAYMENT_STATUS' , width: 80, filter: {type: 'string'}},
				{ text: 'TRX Number', dataIndex: 'TRX_NUMBER', width: 140, filter: {type: 'string'}},
				{ text: 'Paid Thru Date', dataIndex: 'PAYTHROUGH_DATE', width: 150, filter: {type: 'date'}}
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
			,
			tbar: [{
				text: 'Export To excel',
				handler: function (){
					window.open('<?=controller_?>gate_job_manager/excel_Gate_job_manager','_blank');
					// console.log(Ext.getCmp('gp_ct').getView().getStore().getFilters());
				} 
			}],
			listeners: {
				filterchange: function() {
					alert('filter');
				}
			}

		});
		
		ct_grid.render('job_list_<?=$tab_id?>');
	});
</script>
<div id="job_list_<?=$tab_id?>"></div>