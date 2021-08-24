<script type="text/javascript">
	Ext.onReady(function(){
		var ct_store = Ext.create('Ext.data.Store', {
			fields:['REC_NUM','NO_CONTAINER', 'CONT_SIZE','ID_ISO_CODE',  'CONT_STATUS', 'ID_CLASS_CODE', 'WEIGHT', 'SEAL_NUMB', 'GT_DATE', 'STATUS', 'LOCATION', 'IN_VESSEL', 'IN_VOYAGE', 'OUT_VESSEL', 'OUT_VOYAGE', 'ID_OPERATOR', 'ID_POD', 'TRANSHIPMENT', 'HOLD_CONTAINER', 'ID_COMMODITY', 'TEMP', 'OOG', 'IMDG', 'UNNO'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>outbound_stacking_list/data_outbound_stacking_list',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				},
				extraParams: {
					id_ves_voyage: '<?=$id_ves_voyage?>'
				}
			},
			pageSize: 100,
			sorters: [{
				property: 'NO_CONTAINER',
				direction: 'ASC'
			}]
		});
		
		var ct_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var ct_grid = Ext.create('Ext.grid.Panel', {
			store: ct_store,
			loadMask: true,
			width: 900,
			height: 440,
			columns: [
				{ text: 'No', dataIndex: 'REC_NUM', width: 50,},
				{ text: 'No Container', dataIndex: 'NO_CONTAINER', width: 140, filter: {type: 'string'}},
				{ dataIndex: 'POINT', hidden: true, hideable: false},
				{ text: 'Size', dataIndex: 'CONT_SIZE' , width: 80, filter: {type: 'string'}},
				{ text: 'ISO', dataIndex: 'ID_ISO_CODE' , width: 80, filter: {type: 'string'}},
				{ text: 'F/M', dataIndex: 'CONT_STATUS' , width: 80, filter: {type: 'string'}},
				{ text: 'Class', dataIndex: 'ID_CLASS_CODE' , width: 100, filter: {type: 'string'}},
				{ text: 'WGT(Ton)', dataIndex: 'WEIGHT' , width: 80, filter: {type: 'string'}},
				{ text: 'Seal No', dataIndex: 'SEAL_NUMB', width: 80, filter: {type: 'string'}},
				{ text: 'Gate In', dataIndex: 'GT_DATE', width: 100, filter: {type: 'string'}},
				{ text: 'Status', dataIndex: 'STATUS', width: 80, filter: {type: 'string'}},
				{ text: 'Location', dataIndex: 'LOCATION', width: 140, filter: {type: 'string'}},
				{ text: 'In Vessel', dataIndex: 'IN_VESSEL' , width: 140, filter: {type: 'string'}},
				{ text: 'In/Out Voyage', dataIndex: 'IN_VOYAGE' , width: 140, filter: {type: 'string'}},
				{ text: 'Out Vessel', dataIndex: 'OUT_VESSEL' , width: 140, filter: {type: 'string'}},
				{ text: 'In/Out Voyage', dataIndex: 'OUT_VOYAGE' , width: 140, filter: {type: 'string'}},
				{ text: 'Operator', dataIndex: 'ID_OPERATOR' , width: 80, filter: {type: 'string'}},
				{ text: 'POD', dataIndex: 'ID_POD' , width: 80, filter: {type: 'string'}},
				{ text: 'Transhipment', dataIndex: 'TRANSHIPMENT', width: 80, filter: {type: 'string'}},
				{ text: 'Hold', dataIndex: 'HOLD_CONTAINER', width: 80, filter: {type: 'string'}},
				{ text: 'Commodity', dataIndex: 'ID_COMMODITY', width: 80, filter: {type: 'string'}},
				{ text: 'Temperature', dataIndex: 'TEMP', width: 100, filter: {type: 'string'}},
				{ text: 'OOG', dataIndex: 'OOG', width: 80, filter: {type: 'string'}},
				{ text: 'IMDG', dataIndex: 'IMDG', width: 80, filter: {type: 'string'}},
				{ text: 'UNNO', dataIndex: 'UNNO', width: 100, filter: {type: 'string'}}
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
			tbar: [{
				itemId: 'export_to_excel_<?=$tab_id?>',
				text: 'Export to Excel',
				handler: function() {
					window.open('<?=controller_?>outbound_stacking_list/export_to_excel/<?=$id_ves_voyage?>/','_blank');
				}
			}],
			features: [ct_filters],
			emptyText: 'No Data Found'
		});
		
		ct_grid.render('job_list_<?=$tab_id?>');
	});
</script>
<div id="job_list_<?=$tab_id?>"></div>