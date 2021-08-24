<script type="text/javascript">
	Ext.onReady(function(){
		var ct_store = Ext.create('Ext.data.Store', {
			fields:['ID_MACHINE', 'MCH_NAME', 'FULL_NAME_LOGIN', 'ISLOGIN_VMT', 'DATE_LOGIN'],
			autoLoad: true,
			remoteSort: true,
			storeId: 'vmt_monitoring_list_<?=$tab_id?>',
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>vmt_monitoring/data_alat',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 100,
			sorters: [{
				property: 'DATE_LOGIN',
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
                    width: 1850,
                    height: 440,
                    columns: [
                            { text: 'Nama Alat', dataIndex: 'MCH_NAME', width: 140, filter: {type: 'string'}},
                            { text: 'Aktif', dataIndex: 'ISLOGIN_VMT', width: 80},
                            { text: 'User Login', dataIndex: 'FULL_NAME_LOGIN', width: 140, filter: {type: 'string'}},
                            { text: 'Tgl Login', dataIndex: 'DATE_LOGIN', width: 130}
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
                    tbar: [{
                        xtype: 'field',
                        id: 'idfilteralat<?=$tab_id?>',
                        fieldLabel: 'Alat',
                        name: 'filter_nama_alat',
                        readOnly: false,
                        width: 250
                    },{
                        text: 'Filter',
                        handler: function (){
                            ct_grid.filters.clearFilters();
                            var vfilterbyalat = Ext.getCmp('idfilteralat<?=$tab_id?>').getValue();
                            if(vfilterbyalat != ''){
                                var filter_nama_alat = [{type: "string",value: vfilterbyalat,field: "MCH_NAME"}];
                            }
                            ct_store.getProxy().extraParams = {
                                filter_nama_alat: JSON.stringify(filter_nama_alat)
                            };
                            ct_store.load();
                        } 
                    }],
		});
		ct_grid.render('alat_list_<?=$tab_id?>');
	});
</script>
<div id="alat_list_<?=$tab_id?>"></div>