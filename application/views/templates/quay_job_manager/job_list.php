<script type="text/javascript">
	Ext.onReady(function(){
		var ct_store = Ext.create('Ext.data.Store', {
			fields:['NO_CONTAINER', 'POINT', 'JOB', 'ID_VES_VOYAGE', 'IDQC', 'QC', 'SEQ_NO', 'QUEUE', 'IDITV', 'ID_POOL', 'ITV', 'ID_CLASS_CODE', 'ITT_FLAG','ID_ISO_CODE', 'ID_POD', 'ID_OPERATOR', 'ID_COMMODITY', 'CONT_TYPE', 'WEIGHT', 'YARD_POS', 'STOWAGE', 'STATUS_FLAG', 'TL_FLAG' , 'MIN' , 'MINUTES'],
			autoLoad: true,
			remoteSort: true,
			storeId: 'quay_job_list_<?=$tab_id?>',
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>quay_job_manager/data_job_list',
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
			}]
		});
		var machine_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['ID_MACHINE', 'MCH_NAME'],
			autoLoad: true,
			remoteSort: true,
			storeId: 'quay_machine_list_<?=$tab_id?>',
			listeners: {
				load: function(store) {
					machine_store_<?=$tab_id?>.insert(0, [{"ID_MACHINE":"-- All --", "MCH_NAME":"-- All --"}]);
				}
			},
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>quay_job_manager/data_all_machine',
				reader: {
					type: 'json'
				}
			},
			sorters: [{
				property: 'MCH_NAME',
				direction: 'ASC'
			}]
		});
		var vessel_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['ID_VES_VOYAGE', 'VESSEL_NAME'],
			autoLoad: true,
			remoteSort: true,
			storeId: 'quay_vessel_list_<?=$tab_id?>',
			listeners: {
				load: function(store) {
					vessel_store_<?=$tab_id?>.insert(0, [{"ID_VES_VOYAGE":"-- All --", "VESSEL_NAME":"-- All --"}]);
				}
			},
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>quay_job_manager/data_all_vessel',
				reader: {
					type: 'json',
					root: 'data',
					idProperty: 'ID_VES_VOYAGE'
				}
			},
			sorters: [{
				property: 'VESSEL_NAME',
				direction: 'ASC'
			}]
		});
		
		var ct_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var jobComplete = Ext.create('Ext.Action', {
			text: 'Complete Job',
			handler: function(widget, event) {
				var rec = ct_grid.getSelectionModel().getSelection()[0];
				if (rec) {
					Ext.Ajax.request({
						url: '<?=controller_?>quay_job_manager/popup_machine?tab_id=<?=$tab_id?>',
						params: {
							no_container: rec.get('NO_CONTAINER'),
							point: rec.get('POINT'),
							id_class_code: rec.get('ID_CLASS_CODE'),
							id_ves_voyage: rec.get('ID_VES_VOYAGE'),
							stowage: rec.get('STOWAGE'),
							idqc: rec.get('IDQC'),
							qc: rec.get('QC'),
							job: rec.get('JOB'),
							tl_flag: rec.get('TL_FLAG'),
							id_pool: rec.get('ID_POOL'),
							iditv: rec.get('IDITV'),
							itv: rec.get('ITV')
						},
						callback: function(opt,success,response){
							$("#popup_script_<?=$tab_id?>").html(response.responseText);
						} 
					});
				} else {
					Ext.Msg.alert('Warning', 'Please select a job from the grid');
				}
			}
		});
		
		var contextMenu = Ext.create('Ext.menu.Menu', {
			items: [
				jobComplete
			]
		});
		
		var ct_grid = Ext.create('Ext.grid.Panel', {
                    store: ct_store,
                    loadMask: true,
                    width: 1850,
                    height: 440,
                    columns: [
                            { text: 'No Container', dataIndex: 'NO_CONTAINER', width: 140, filter: {type: 'string'}},
                            { dataIndex: 'POINT', hidden: true, hideable: false},
                            { text: 'ISO', dataIndex: 'ID_ISO_CODE' , width: 80, filter: {type: 'string'}},
                            { text: 'Job', dataIndex: 'JOB', width: 80},
                            { text: 'Stowage', dataIndex: 'STOWAGE' , width: 80, filter: {type: 'string'}},
                            { text: 'Seq No', dataIndex: 'SEQ_NO' , width: 80, filter: {type: 'string'}},
                            { text: 'QC', dataIndex: 'QC' , width: 80, filter: {type: 'string'}},
                            { text: 'ITV', dataIndex: 'ITV' , width: 80, filter: {type: 'string'}},
                            { text: 'Queue', dataIndex: 'QUEUE' , width: 80, filter: {type: 'string'}},
                            { text: 'Yard', dataIndex: 'YARD_POS', width: 100 , filter: {type: 'string'}},
                            { text: 'Vessel', dataIndex: 'ID_VES_VOYAGE', width: 140, filter: {type: 'string'}},
                            { text: 'Class', dataIndex: 'ID_CLASS_CODE' , width: 80, filter: {type: 'string'}},
                            { text: 'ESY', dataIndex: 'ITT_FLAG' , width: 80, filter: {type: 'string'}},
                            { text: 'POD', dataIndex: 'ID_POD', width: 80, filter: {type: 'string'}},
                            { text: 'OPR', dataIndex: 'ID_OPERATOR' , width: 80, filter: {type: 'string'}},
                            { text: 'Comm.', dataIndex: 'ID_COMMODITY', width: 80, filter: {type: 'string'}},
                            { text: 'Type', dataIndex: 'CONT_TYPE', width: 80, filter: {type: 'string'}},
                            { text: 'WGT(Ton)', dataIndex: 'WEIGHT', width: 80, xtype: 'numbercolumn', format:'0.0'},
                            { text: 'Status', dataIndex: 'STATUS_FLAG', width: 80, filter: {type: 'string'}},
                            { text: 'TL', dataIndex: 'TL_FLAG', width: 80, filter: {type: 'string'}},
                            { text: 'Complete Date', dataIndex: 'MIN', width: 130},
                            { text: 'Waiting Time', dataIndex: 'MINUTES', width: 130}
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
                        xtype: 'combo',
                        id: 'idfilterbykapal<?=$tab_id?>',
                        fieldLabel: 'Kapal',
                        store: vessel_store_<?=$tab_id?>,
                        queryMode: 'local',
                        valueField: 'VESSEL_NAME',
						displayField: 'ID_VES_VOYAGE',
                        name: 'filterbykapal',
                        readOnly: false,
                        width: 250
                    },{
                        id: 'idfilterquaybyjob<?=$tab_id?>',
                        xtype: 'combo',
                        renderTo: Ext.getCmp(),
                        store: Ext.create('Ext.data.Store', {
                        fields: ['valx', 'name'],
                            data: [
                                {"valx": "","name": "-- All --"},
                                {"valx": "I","name": "Discharge"},
                                {"valx": "E","name": "Loading"}
                            ]
                        }),
                        queryMode: 'local',
                        displayField: 'name',
                        valueField: 'valx',
                        fieldLabel: 'Filter By Job',
                        name: 'namefilteryardbyjob',
                        emptyText: 'Choose Job',
                        width: 300,
                    },{
                        id: 'idfilterquaybyQC<?=$tab_id?>',
                        xtype: 'combo',
                        store: machine_store_<?=$tab_id?>,
                        queryMode: 'local',
                        valueField: 'MCH_NAME',
						displayField: 'MCH_NAME',
                        fieldLabel: 'Filter By QC',
                        name: 'namefilteryardbyqc',
                        emptyText: 'Choose QC',
                        width: 300
                    },{
                        xtype: 'field',
                        id: 'idfilterbymin<?=$tab_id?>',
                        fieldLabel: 'by Minutes',
                        name: 'filterbymin',
                        readOnly: false,
                        width: 150
                    },{
                        text: 'Filter',
                        handler: function (){
                            ct_grid.filters.clearFilters();
                            var vfilterquaybyjob = Ext.getCmp('idfilterquaybyjob<?=$tab_id?>').getValue();
                            var jobcode = [{type: "string",value: vfilterquaybyjob,field: "JOB"}]
                            var vfilterbymin = Ext.getCmp('idfilterbymin<?=$tab_id?>').getValue();
                            var vfilterbykapal = Ext.getCmp('idfilterbykapal<?=$tab_id?>').getValue();
                            var vfilterbyQC = Ext.getCmp('idfilterquaybyQC<?=$tab_id?>').getValue();
                            if(vfilterbymin != 0){
                                var mincode = [{type: "min",value: vfilterbymin,field: "MINUTES"}];
                            }
                            if(vfilterbykapal != ''){
                                var filterbykapal = [{type: "string",value: vfilterbykapal,field: "ID_VES_VOYAGE"}];
                            }
                            if(vfilterbyQC != ''){
                                var filterbyQC = [{type: "string",value: vfilterbyQC,field: "QC"}];
                            }
                            ct_store.getProxy().extraParams = {
                                job_filter: JSON.stringify(jobcode),
                                filterbyminute: JSON.stringify(mincode),
                                filterbykapal: JSON.stringify(filterbykapal),
                                filterbyQC: JSON.stringify(filterbyQC)
                            };
                            ct_store.load();
                        } 
                    },{
                        text: 'Export To excel',
                        handler: function (){
                            window.open('<?=controller_?>quay_job_manager/excel_quay_job_manager','_blank');
                            // console.log(Ext.getCmp('gp_ct').getView().getStore().getFilters());
                        } 
                    }],
		});
		ct_grid.render('job_list_<?=$tab_id?>');
	});
</script>
<div id="job_list_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>