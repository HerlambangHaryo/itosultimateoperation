<script type="text/javascript">
	Ext.onReady(function(){
		var ct_store = Ext.create('Ext.data.Store', {
			fields:['IS_BYPASS','NO_CONTAINER', 'POINT', 'JOB','CONT_SIZE', 'ID_VES_VOYAGE', 'EQ', 'QCPLAN', 'SEQ_NO', 'QUEUE', 'IDITV', 'ITV', 'ID_POOL', 'ID_CLASS_CODE', 'ID_ISO_CODE', 'ID_POD', 'ID_OPERATOR', 'ID_COMMODITY', 'CONT_TYPE', 'WEIGHT', 'YD_YARD', 'CONT_STATUS', 'PA_POS', 'YARD_POS', 'STOWAGE', 'STATUS_FLAG', 'GT_JS_YARD', 'GT_JS_BLOCK', 'GT_JS_BLOCK_NAME', 'GT_JS_SLOT', 'GT_JS_ROW', 'GT_JS_TIER', 'EVENT', 'ID_OP_STATUS', 'YARD_PLACEMENT', 'YD_BLOCK', 'MIN','ID_SPEC_HAND', 'ITT_FLAG','WAITING_TIME', 'ID_MACHINE'],
			autoLoad: true,
			remoteSort: true,
			storeId: 'yard_job_list_<?=$tab_id?>',
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>yard_job_manager/data_job_list',
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
		var machine_YC_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['ID_MACHINE', 'MCH_NAME'],
			autoLoad: true,
			remoteSort: true,
			listeners: {
				load: function(store) {
					machine_YC_<?=$tab_id?>.insert(0, [{"ID_MACHINE":"-- All --", "MCH_NAME":"-- All --"}]);
				}
			},
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>yard_job_manager/data_all_machine_yc',
				reader: {
					type: 'json'
				}
			},
			sorters: [{
				property: 'MCH_NAME',
				direction: 'ASC'
			}]
		});
		var machine_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['QCPLAN', 'QCPLAN'],
			autoLoad: true,
			remoteSort: true,
			listeners: {
				load: function(store) {
					machine_store_<?=$tab_id?>.insert(0, [{"QCPLAN":"-- All --", "QCPLAN":"-- All --"}]);
				}
			},
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>yard_job_manager/data_all_machine',
				reader: {
					type: 'json'
				}
			},
			sorters: [{
				property: 'QCPLAN',
				direction: 'ASC'
			}]
		});
		
		var ct_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};

		var changePA = Ext.create('Ext.Action', {
			icon   : '<?=IMG_?>icons/config.png',
			text: 'Change PA',
			disabled: true,
			handler: function(widget, event) {
				var rec = ct_grid.getSelectionModel().getSelection()[0];
				if (rec) {
					Ext.Ajax.request({
						url: '<?=controller_?>yard_job_manager/popup_change_PA?tab_id=<?=$tab_id?>',
						params: {
							no_container: rec.get('NO_CONTAINER'),
							point: rec.get('POINT'),
							id_yard: rec.get('GT_JS_YARD'),
							id_block: rec.get('GT_JS_BLOCK'),
							slot: rec.get('GT_JS_SLOT'),
							row: rec.get('GT_JS_ROW'),
							tier: rec.get('GT_JS_TIER')
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
		

		var byPass = Ext.create('Ext.Action', {
			icon   : '<?=IMG_?>icons/config.png',
			text: 'By Pass',
			handler: function(widget, event) {
				var rec = ct_grid.getSelectionModel().getSelection();
				if (rec.length>0) {
					var list_container = [];
					for (i=0; i<rec.length; i++){
						list_container.push({
								NO_CONTAINER: rec[i].get('NO_CONTAINER'),
								POINT: rec[i].get('POINT'),
								IS_BYPASS: rec[i].get('IS_BYPASS') == 'Yes' ? 0 : 1
						});
					}
					var JSON_list_container = JSON.stringify(list_container);
					Ext.Ajax.request({
						url: '<?=controller_?>yard_job_manager/byPass?tab_id=<?=$tab_id?>',
						params: {
							list_container : JSON_list_container
						},
						callback: function(opt,success,response){
							ct_store.load();
							// $("#popup_script_<?=$tab_id?>").html(response.responseText);
						}
					});
				} else {
					Ext.Msg.alert('Warning', 'Please select a job from the grid');
				}
			}
		});
		
		var changeEquipment = Ext.create('Ext.Action', {
			icon   : '<?=IMG_?>icons/config.png',
			text: 'Change Equipment',
			handler: function(widget, event) {
				var rec = ct_grid.getSelectionModel().getSelection();
				if (rec.length>0) {
					var list_container = [];
					for (i=0; i<rec.length; i++){
						list_container.push(
							{
								no_container: rec[i].get('NO_CONTAINER'),
								point: rec[i].get('POINT')
							}
						);
					}
					var JSON_list_container = JSON.stringify(list_container);
					Ext.Ajax.request({
						url: '<?=controller_?>yard_job_manager/popup_change_equipment?tab_id=<?=$tab_id?>',
						params: {
							list_container : JSON_list_container
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
		
		var jobComplete = Ext.create('Ext.Action', {
			text: 'Complete Job',
			icon   : '<?=IMG_?>icons/config.png',
			handler: function(widget, event) {
				var rec = ct_grid.getSelectionModel().getSelection()[0];
				if (rec) {
					Ext.Ajax.request({
						url: '<?=controller_?>yard_job_manager/popup_machine?tab_id=<?=$tab_id?>',
						params: {
							no_container: rec.get('NO_CONTAINER'),
							point: rec.get('POINT'),
							job: rec.get('JOB'),
							id_op_status: rec.get('ID_OP_STATUS'),
							event: rec.get('EVENT'),
							block_name: rec.get('GT_JS_BLOCK_NAME'),
							id_block: rec.get('GT_JS_BLOCK'),
							slot: rec.get('GT_JS_SLOT'),
							row: rec.get('GT_JS_ROW'),
							tier: rec.get('GT_JS_TIER'),
							yard_placement: rec.get('YARD_PLACEMENT'),
							id_pool: rec.get('ID_POOL'),
							id_class_code: rec.get('ID_CLASS_CODE'),
							iditv: rec.get('IDITV'),
							itv: rec.get('ITV'),
							machine: rec.get('EQ'),
							id_machine: rec.get('ID_MACHINE')
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

		var SingleStackView = Ext.create('Ext.Action', {
			text: 'Single Stack View',
			icon   : '<?=IMG_?>icons/config.png',
			handler: function(widget, event) {
				var rec = ct_grid.getSelectionModel().getSelection()[0];
				// var param = rec.get('GT_JS_BLOCK')+'-'+rec.get('GT_JS_SLOT')+'-'+rec.get('GT_JS_ROW')+'-'+rec.get('ID_VES_VOYAGE');
				if(rec.get('CONT_TYPE')!='HQ'){
					var type='0';
				}else{
					var type=rec.get('CONT_TYPE');
				}
				var param = rec.get('GT_JS_BLOCK')+'-0-'+rec.get('ID_VES_VOYAGE')+'-'+rec.get('GT_JS_SLOT')+'-0-'+rec.get('ID_POD')+'-'+rec.get('CONT_SIZE')+'-'+rec.get('YD_YARD')+'-'+type+'-yjp';
				
				if (rec) {
					Ext.Ajax.request({
						url: '<?=controller_?>yard_job_manager/validate_yard_block?tab_id=<?=$tab_id?>',
						params: {
							no_container: rec.get('NO_CONTAINER'),
							point: rec.get('POINT'),
							id_op_status: rec.get('ID_OP_STATUS'),
							event: rec.get('EVENT'),
							block_name: rec.get('GT_JS_BLOCK_NAME'),
							id_block: rec.get('GT_JS_BLOCK'),
							slot: rec.get('GT_JS_SLOT'),
							row: rec.get('GT_JS_ROW'),
							tier: rec.get('GT_JS_TIER'),
							yard_placement: rec.get('YARD_PLACEMENT'),
							id_ves_voyage: rec.get('ID_VES_VOYAGE')
						},
						callback: function(opt,success,response){
							console.log(response.responseText);

							if(response.responseText=='null'){
								addTab('west_panel', 'single_stack_view', param, 'Single Stack View');
							}else{
								Ext.Msg.alert('Warning', 'Yard Not Exsist');
							}
						}
					});
				} else {
					Ext.Msg.alert('Warning', 'Please select a job from the grid');
				}
			}
		});
		
		var contextMenu = Ext.create('Ext.menu.Menu', {
			items: [
				changePA,
				changeEquipment,
				jobComplete,
				SingleStackView,
				byPass
			]
		});

		
		var ct_grid = Ext.create('Ext.grid.Panel', {
			id: 'yard_job_grid_<?=$tab_id?>',
			store: ct_store,
			loadMask: true,
			width: 1750,
			height: 440,
			multiSelect: true,
			columns: [
				{ text: 'No Container', dataIndex: 'NO_CONTAINER', width: 140, filter: {type: 'string'}},
				{ dataIndex: 'POINT', hidden: true, hideable: false},
				{ dataIndex: 'GT_JS_YARD', hidden: true, hideable: false},
				{ dataIndex: 'GT_JS_BLOCK', hidden: true, hideable: false},
				{ dataIndex: 'GT_JS_BLOCK_NAME', hidden: true, hideable: false},
				{ dataIndex: 'GT_JS_SLOT', hidden: true, hideable: false},
				{ dataIndex: 'GT_JS_ROW', hidden: true, hideable: false},
				{ dataIndex: 'GT_JS_TIER', hidden: true, hideable: false},
				{ dataIndex: 'EVENT', hidden: true, hideable: false},
				{ dataIndex: 'ID_OP_STATUS', hidden: true, hideable: false},
				{ dataIndex: 'YARD_PLACEMENT', hidden: true, hideable: false},
				{ dataIndex: 'YD_BLOCK', hidden: true, hideable: false},
				{ text: 'ISO', dataIndex: 'ID_ISO_CODE' , width: 80, filter: {type: 'string'}},
				{ text: 'Size', dataIndex: 'CONT_SIZE' , width: 80, filter: {type: 'string'}},
				{ text: 'Job', dataIndex: 'JOB', width: 80},
				{ text: 'PA', dataIndex: 'PA_POS', width: 100},
				{ text: 'Yard', dataIndex: 'YARD_POS', width: 100},
				{ text: 'By Pass', dataIndex: 'IS_BYPASS', width: 100},
				{ text: 'Stowage', dataIndex: 'STOWAGE' , width: 80},
				{ text: 'Seq No', dataIndex: 'SEQ_NO' , width: 80},
				{ text: 'YC', dataIndex: 'EQ' , width: 80, filter: {type: 'string'}},
				{ text: 'QC', dataIndex: 'QCPLAN'},
				{ text: 'ITV', dataIndex: 'ITV' , width: 80, filter: {type: 'string'}},
				{ text: 'Queue', dataIndex: 'QUEUE' , width: 80},
				{ text: 'Waiting Time', dataIndex: 'WAITING_TIME'},
				{ text: 'Vessel', dataIndex: 'ID_VES_VOYAGE', width: 140, filter: {type: 'string'}},
				{ text: 'POD', dataIndex: 'ID_POD', width: 80, filter: {type: 'string'}},
				{ text: 'ESY', dataIndex: 'ITT_FLAG', width: 80,filter: {type: 'string'}},
				{ text: 'Class', dataIndex: 'ID_CLASS_CODE' , width: 80, filter: {type: 'string'}},
				{ text: 'OPR', dataIndex: 'ID_OPERATOR' , width: 80, filter: {type: 'string'}},
				{ text: 'Comm.', dataIndex: 'ID_COMMODITY', width: 80, filter: {type: 'string'}},
				{ text: 'Type', dataIndex: 'CONT_TYPE', width: 80, filter: {type: 'string'}},
				{ text: 'WGT(Ton)', dataIndex: 'WEIGHT', width: 80, xtype: 'numbercolumn', format:'0.0'},
				{ text: 'Handling', dataIndex: 'ID_SPEC_HAND' , width: 80},
				{ text: 'Status', dataIndex: 'STATUS_FLAG', width: 80},
				{ text: 'Complete Date', dataIndex: 'MIN', width: 140}
			],
			viewConfig : {
				enableTextSelection: true,
				listeners: {
					itemcontextmenu: function(view, rec, node, index, e) {
						if(rec.data.ITT_FLAG != 'Y'){
							if(rec.data.IS_BYPASS == 'No'){
								byPass.setText('By Pass');
							} else {
								byPass.setText('UnBy Pass');
							}
							e.stopEvent();
							contextMenu.showAt(e.getXY());

						}else{
						    alert('No action for Container ESY.');
						}
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
				id: 'idfilteryardbyjob<?=$tab_id?>',
				xtype: 'combo',
				renderTo: Ext.getCmp(),
				store: Ext.create('Ext.data.Store', {
                            fields: ['valx', 'name'],
                                data: [
	                                	{"valx": "","name": "-- All --"},
	                                	{"valx": "DS","name": "Discharge"},
	                                	{"valx": "LD","name": "Loading"},
	                                	{"valx": "GI","name": "Receiving"},
	                                	{"valx": "GO","name": "Delivery"},
	                                	{"valx": "MO","name": "Move Out"},
	                                	{"valx": "MI","name": "Move In"}
                                	]
                       }),
				queryMode: 'local',
                displayField: 'name',
                valueField: 'valx',
                fieldLabel: 'Filter By Job',
                name: 'namefilteryardbyjob',
				emptyText: 'Choose Job',
				width: 250
			},{
				id: 'idfilterbyYC<?=$tab_id?>',
				xtype: 'combo',
				store: machine_YC_<?=$tab_id?>,
				queryMode: 'local',
				valueField: 'MCH_NAME',
				displayField: 'MCH_NAME',
				fieldLabel: 'Filter By YC',
				name: 'namefilteryardbyyc',
				emptyText: 'Choose YC',
				width: 300
			},{
				id: 'idfilterquaybyQC<?=$tab_id?>',
				xtype: 'combo',
				store: machine_store_<?=$tab_id?>,
				queryMode: 'local',
				valueField: 'QCPLAN',
				displayField: 'QCPLAN',
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
				    var vfilteryardbyjob = Ext.getCmp('idfilteryardbyjob<?=$tab_id?>').getValue();
					var vfilterbyYC = Ext.getCmp('idfilterbyYC<?=$tab_id?>').getValue();
					var vfilterbyQC = Ext.getCmp('idfilterquaybyQC<?=$tab_id?>').getValue();
				    var jobcode = [{type: "string",value: vfilteryardbyjob,field: "JOB"}]
//				    ct_store.getProxy().extraParams = {
//
//				    };
					var mincode = "";
					var qccode = "";
					var eqcode = "";
				    var vfilterbymin = Ext.getCmp('idfilterbymin<?=$tab_id?>').getValue();
					
				    if(vfilterbymin != ''){
						var mincode = [{type: "min",value: vfilterbymin,field: "CMPLT_DT"}];
				    }
					if(vfilterbyQC != '-- All --' && vfilterbyQC != null){
						var qccode = [{type: "QCPLAN",value: vfilterbyQC,field: "QC"}];
					}
					if(vfilterbyYC != '-- All --' && vfilterbyYC != null){
						var eqcode = [{type: "string",value: vfilterbyYC,field: "EQ"}];
					}
				    ct_store.getProxy().extraParams = {
					job_filter: JSON.stringify(jobcode),
						filterbyminute: JSON.stringify(mincode),
						filterbyQC: JSON.stringify(qccode),
						filterbyYC: JSON.stringify(eqcode)
				    };
				    ct_store.load();
				} 
			},{
				text: 'Export To excel',
				handler: function (){
				    var vfilteryardbyjob = Ext.getCmp('idfilteryardbyjob<?=$tab_id?>').getValue();
					var vfilterbyYC = Ext.getCmp('idfilterbyYC<?=$tab_id?>').getValue();
					var vfilterbyQC = Ext.getCmp('idfilterquaybyQC<?=$tab_id?>').getValue();
				    var vfilterbymin = Ext.getCmp('idfilterbymin<?=$tab_id?>').getValue();
					var mapForm = document.createElement("form");
					mapForm.target = "_blank";    
					mapForm.method = "POST";
					mapForm.action = "<?=controller_?>yard_job_manager/excel_yard_job_manager";
					
				    if(vfilteryardbyjob != ''){
						var mapInputJ = document.createElement("input");
						mapInputJ.type = "string";
						mapInputJ.name = "JOB";
						mapInputJ.value = vfilteryardbyjob;
						mapForm.appendChild(mapInputJ);
					}
					
				    if(vfilterbymin != ''){
						var mapInputM = document.createElement("input");
						mapInputM.type = "min";
						mapInputM.name = "CMPLT_DT";
						mapInputM.value = vfilterbymin;
						mapForm.appendChild(mapInputM);
					}
					
				    if(vfilterbyQC != ''){
						var mapInputQ = document.createElement("input");
						mapInputQ.type = "QCPLAN";
						mapInputQ.name = "QC";
						mapInputQ.value = vfilterbyQC;
						mapForm.appendChild(mapInputQ);
					}
						
				    if(vfilterbyYC != ''){
						var mapInputY = document.createElement("input");
						mapInputY.type = "string";
						mapInputY.name = "EQ";
						mapInputY.value = vfilterbyYC;
						mapForm.appendChild(mapInputY);
					}
					console.log('mapForm',mapForm);
					document.body.appendChild(mapForm);
					mapForm.submit();
				} 
			}],
		});
		
		
		ct_grid.getSelectionModel().on({
			selectionchange: function(sm, selections) {
				if (selections.length>0) {
					if (selections.length>1){
						changePA.disable();
						jobComplete.disable();
					}else{
						if (selections[0].get('EVENT')=='P'){
							changePA.enable();
						}else{
							changePA.disable();
						}
					}
				} else {
					changePA.disable();
				}
			}
		});
		
		ct_grid.render('job_list_<?=$tab_id?>');
	});
</script>

<div id="job_list_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>