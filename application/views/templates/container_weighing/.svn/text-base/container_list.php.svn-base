<script type="text/javascript">
	Ext.onReady(function(){

		var ct_store = Ext.create('Ext.data.Store', {
			fields:[
				'NO_CONTAINER', 
				'ID_VES_VOYAGE', 
				'STOWAGE', 
				'ID_ISO_CODE', 
				'ID_CLASS_CODE', 
				'ID_OPERATOR', 
				'UNNO', 
				'IMDG', 
				'CONT_SIZE', 
				'CONT_TYPE', 
				'CONT_STATUS', 
				'CONT_HEIGHT', 
				'ID_POL', 
				'ID_POD', 
				'ID_POR', 
				'WEIGHT', 
				'TEMP', 
				'ID_COMMODITY', 
				'YARD_POS', 
				'TL_FLAG', 
				'NO_REQUEST'
			],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>container_weighing/data_countainer_weighing_list',
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
			width: 1600,
			height: 440,
			id: 'container_weighing_list_grid_<?=$tab_id?>',
			columns: [
				{ dataIndex: 'ID_VES_VOYAGE', hidden: true, hideable: false},
				{ text: 'No Container', dataIndex: 'NO_CONTAINER', width: 140, filter: {type: 'string'} },
				{ text: 'Stowage', dataIndex: 'STOWAGE' , width: 80 },
				{ text: 'ISO', dataIndex: 'ID_ISO_CODE' , width: 80 },
				{ text: 'Class', dataIndex: 'ID_CLASS_CODE' , width: 80 },
				{ text: 'OPR', dataIndex: 'ID_OPERATOR' , width: 80 },
				{ text: 'F/M', dataIndex: 'CONT_STATUS', width: 80 },
				{ text: 'POL', dataIndex: 'ID_POL', width: 80 },
				{ text: 'POD', dataIndex: 'ID_POD', width: 80 },
				{ text: 'POR', dataIndex: 'ID_POR', width: 80 },
				{ text: 'Yard', dataIndex: 'YARD_POS', width: 100 },
				{ text: 'WGT(Ton)', dataIndex: 'WEIGHT', width: 80 },
				{ text: 'Temp.(C)', dataIndex: 'TEMP', width: 80 },
				{ text: 'UNNO', dataIndex: 'UNNO', width: 80 },
				{ text: 'IMDG', dataIndex: 'IMDG', width: 80 },
				{ text: 'Comm.', dataIndex: 'ID_COMMODITY', width: 80 },
				{ text: 'Size', dataIndex: 'CONT_SIZE', width: 80 },
				{ text: 'Type', dataIndex: 'CONT_TYPE', width: 80 },
				{ text: 'Height', dataIndex: 'CONT_HEIGHT', width: 80 },
				{ text: 'TL', dataIndex: 'TL_FLAG', width: 80 }
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
			emptyText: 'No Data Found',
		});
		
		ct_grid.render('container_weighing_list_<?=$tab_id?>');
	});
</script>
<div id="container_weighing_list_<?=$tab_id?>"></div>