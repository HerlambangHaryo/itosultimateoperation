<script type="text/javascript">
	var id_ves_voyage = '<?=$id_ves_voyage?>';
	Ext.onReady(function(){
		var ct_store = Ext.create('Ext.data.Store', {
			fields:['TOTAL', 'COMMODITY_NAME', 'ID_POD', 'CONT_TYPE', 'CONT_SIZE', 'ID_COMMODITY', 'CONT_STATUS', 'FM', 'ID_CLASS_CODE', 'OOG', 'IMDG', 'FRONT', 'REAR', 'LEFT', 'RIGHT'],
			autoLoad: true,
			remoteSort: true,
			storeId: 'summary_grid<?=$tab_id?>',
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>stowage_instruction_summary/get_data_stowage_summary/'+id_ves_voyage,
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 100,
			sorters: [{
				property: 'ID_POD',
				direction: 'ASC'
			}]
		});
		
		var ct_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var contextMenu = Ext.create('Ext.menu.Menu', {
			items: [
				
			]
		});
		
		var ct_grid = Ext.create('Ext.grid.Panel', {
                    store: ct_store,
                    loadMask: true,
                    width: 1000,
                    height: 440,
                    columns: [
                            { text: 'TOTAL', dataIndex: 'TOTAL', width: 80, filter: {type: 'numeric'}, align:'center'},
                            { text: 'POD', dataIndex: 'ID_POD', width: 80, filter: {type: 'string'}, align:'center'},
                            { text: 'Size', dataIndex: 'CONT_SIZE', width: 50, filter: {type: 'numeric'}, align:'center'},
                            { text: 'Type', dataIndex: 'CONT_TYPE', width: 80, filter: {type: 'string'}, align:'center'},
                            { text: 'Commodity', dataIndex: 'ID_COMMODITY', width: 100, filter: {type: 'string'}, align:'center'},
                            { text: 'F/M', dataIndex: 'FM', width: 80, filter: {type: 'string'}, align:'center'},
                            { text: 'Class', dataIndex: 'ID_CLASS_CODE', width: 100, filter: {type: 'string'}, align:'center'},
                            { text: 'OGG (Dimension)',
								columns:[
									{ text: 'FRONT', sortable: true, dataIndex: 'FRONT', width: 80, filter: {type: 'numeric'}, align:'center'},
									{ text: 'BACK', sortable: true, dataIndex: 'REAR', width: 80, filter: {type: 'numeric'}, align:'center'},
									{ text: 'LEFT', sortable: true, dataIndex: 'LEFT', width: 80, filter: {type: 'numeric'}, align:'center'},
									{ text: 'RIGHT', sortable: true, dataIndex: 'RIGHT', width: 80, filter: {type: 'numeric'}, align:'center'}
								]
							},
                            { text: 'IMDG', dataIndex: 'IMDG', width: 100, filter: {type: 'string'}, align:'center'}
                    ],
                    viewConfig : {
                        enableTextSelection: true,
                        listeners: {
                            itemcontextmenu: function(view, rec, node, index, e) {
                                e.stopEvent();
                                contextMenu.showAt(e.getXY());
                                return false;
                            }
                        }
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
                    // tbar: [{
                        // xtype: 'field',
                        // id: 'idfilteralat<?=$tab_id?>',
                        // fieldLabel: 'Alat',
                        // name: 'filter_nama_alat',
                        // readOnly: false,
                        // width: 250
                    // },{
                        // text: 'Filter',
                        // handler: function (){
                            // ct_grid.filters.clearFilters();
                            // var vfilterbyalat = Ext.getCmp('idfilteralat<?=$tab_id?>').getValue();
                            // if(vfilterbyalat != ''){
                                // var filter_nama_alat = [{type: "string",value: vfilterbyalat,field: "MCH_NAME"}];
                            // }
                            // ct_store.getProxy().extraParams = {
                                // filter_nama_alat: JSON.stringify(filter_nama_alat)
                            // };
                            // ct_store.load();
                        // } 
                    // }],
		});
		ct_grid.render('summary_grid<?=$tab_id?>');
	});
</script>
<div id="summary_grid<?=$tab_id?>"></div>