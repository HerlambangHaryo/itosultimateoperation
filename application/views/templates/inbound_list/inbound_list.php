<script type="text/javascript">
	Ext.onReady(function(){
		var cont_size_list_store = Ext.create('Ext.data.Store', {
			fields:['CONT_SIZE', 'NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>inbound_list/data_cont_size/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		var cont_type_list_store = Ext.create('Ext.data.Store', {
			fields:['CONT_TYPE', 'NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>inbound_list/data_cont_type/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		var cont_status_list_store = Ext.create('Ext.data.Store', {
			fields:['CONT_STATUS', 'NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>inbound_list/data_cont_status/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		var pol_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_POL', 'PORT_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>inbound_list/data_port/ID_POL',
				reader: {
					type: 'json'
				},
				extraParams: {
					id_ves_voyage: '<?=$id_ves_voyage?>'
				}
			},
			autoLoad: true
		});
		
		var pod_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_POD', 'PORT_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>inbound_list/data_port/ID_POD',
				reader: {
					type: 'json'
				},
				extraParams: {
					id_ves_voyage: '<?=$id_ves_voyage?>'
				}
			},
			autoLoad: true
		});
		
		var por_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_POR', 'PORT_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>inbound_list/data_port/ID_POR',
				reader: {
					type: 'json'
				},
				extraParams: {
					id_ves_voyage: '<?=$id_ves_voyage?>'
				}
			},
			autoLoad: true
		});
		
		var operator_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_OPERATOR', 'OPERATOR_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>inbound_list/data_operator/',
				reader: {
					type: 'json'
				},
				extraParams: {
					id_ves_voyage: '<?=$id_ves_voyage?>'
				}
			},
			autoLoad: true
		});
		
		var cont_height_list_store = Ext.create('Ext.data.Store', {
			fields:['CONT_HEIGHT', 'NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>inbound_list/data_cont_height/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		var commodity_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_COMMODITY', 'COMMODITY_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>inbound_list/data_cont_commodity/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		var class_list_store = Ext.create('Ext.data.Store', {
			fields:['ID', 'NAME'],
			data : [
				 {ID: 'TI', NAME: 'Transhipment Import'},
				 {ID: 'I',  NAME: 'Import'},
				 {ID: 'TC', NAME: 'Through Cargo'},
				 {ID: 'S1', NAME: 'Unlanded'},
				 {ID: 'S2', NAME: 'Landed'}
			]
		});
		
		var iso_code_store = Ext.create('Ext.data.Store', {
			fields:['ID_ISO_CODE', 'ID_ISO_CODE'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>inbound_list/data_cont_iso_code/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		

		var imdg_list_store = Ext.create('Ext.data.Store', {
			fields:['IMDG', 'IMDG'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>inbound_list/data_imdg_list/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});

		var unno_list_store = Ext.create('Ext.data.Store', {
			fields:['UNNO', 'IMDG', 'DESCRIPTION']
		});
		
		var ct_store = Ext.create('Ext.data.Store', {
			fields:['REC_NUM','NO_CONTAINER_OLD', 'NO_CONTAINER', 'POINT', 'ID_VES_VOYAGE', 'STOWAGE','STOWAGE_TO', 'ID_ISO_CODE', 'ID_CLASS_CODE', 'ID_OPERATOR', 'IMDG', 'UNNO', 'CONT_SIZE', 'CONT_TYPE', 'CONT_STATUS', 'CONT_HEIGHT', 'ID_POL', 'ID_POD', 'ID_POR', 'WEIGHT', 'TEMP', 'ID_COMMODITY', 'YARD_POS', 'TL_FLAG', 'STATUS_EDIT', 'NO_REQUEST','QC_REAL','STATUS_BM','COMPLETE_DATE_BM','YC_PLAN','YC_REAL','STATUS_PLACEMENT','COMPLETE_DATE_PLACEMENT','OVER_HEIGHT','OVER_RIGHT','OVER_LEFT','OVER_FRONT','OVER_REAR','ITT_FLAG','QC_PLAN','OVER_WIDTH','CONFIRM_DATE_'
				],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>inbound_list/data_inbound_list',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				},
				extraParams: {
					id_ves_voyage: '<?=$id_ves_voyage?>'
				}
			},
			listeners: {
				datachanged: function (){
					pol_list_store.load();
					pod_list_store.load();
					por_list_store.load();
					operator_list_store.load();
				}
			},
			pageSize: 100,
			sorters: [{
				property: 'NO_CONTAINER',
				direction: 'ASC'
			}]
		});
		
		Ext.create('Ext.form.Panel', {
			id: "search_multiple_container_<?=$tab_id?>",
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			width: 250,
			items: [{
				id: 'inbound_list_number<?=$tab_id?>',
				xtype: 'textareafield',
				name: "container_list",
				fieldLabel: 'No Container',
				allowBlank: true
			}],
			buttons: [{
				text: 'Search',
				formBind: true,
				listeners: {
					click: {
						fn: function () {
							var form = this.up('form').getForm();
							if (form.isValid()){
								var container_number = Ext.getCmp("inbound_list_number<?=$tab_id?>").getValue();
									var container_list = container_number.split("\n");
									// console.log(container_list);
									var container_param = '';
									for (i=0;i<container_list.length;i++){
										container_list[i] = $.trim(container_list[i]);
										if (container_list[i]!=''){
											if (container_param!=''){
												container_param+=',';
											}
											container_param+="'"+container_list[i]+"'";
										}
									}
									// console.log(container_param);
									ct_store.getProxy().extraParams = {
										container_list: container_param,
										id_ves_voyage: '<?=$id_ves_voyage?>'
									};
									ct_store.load();
							}
						}
					}
				}
			}]
		}).render('search_multiple_container_<?=$tab_id?>');
		
		var ct_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			pluginId: 'rowEditingInboundList',
			clicksToMoveEditor: 1,
			autoCancel: false,
			errorSummary: false,
			listeners: {
				edit: function(editor, e, opt){
					var record = e.record.getChanges();
					var array = $.map(record, function(value, index) {
						return [value];
					});

					if (array.length>0 && e.record.data.STOWAGE != '' && e.record.data.STOWAGE != '000000'){
						loadmask.show();
						Ext.MessageBox.show({
										title: 'test',
										msg: 'STOWAGE : ' + e.record.data.STOWAGE,
										buttons: Ext.MessageBox.OK
									});
						if(e.record.data.ID_CLASS_CODE=="S1" || e.record.data.ID_CLASS_CODE=="S1"){
							if(e.record.data.STOWAGE_TO==""){
								alert("Stowage To tidak boleh kosong");
								loadmask.hide();
								return false;
							}
						}
						Ext.Ajax.request({
							url: '<?=controller_?>inbound_list/update_list_detail/' + e.record.data.NO_CONTAINER + '/' + e.record.data.ID_VES_VOYAGE + '/' + e.record.data.NO_CONTAINER_OLD + '/' + e.record.data.POINT,
//							params: e.record.getChanges(),
							params: e.record.data,
							success: function(response){
								var text = response.responseText;
								if (text=='1'){
									Ext.MessageBox.show({
										title: 'Success',
										msg: 'Changes saved successfully.',
										buttons: Ext.MessageBox.OK
									});
									ct_store.reload();
								}else if (text =='2'){
									Ext.MessageBox.show({
										title: 'Error',
										msg: 'Slot not available.',
										buttons: Ext.MessageBox.OK
									});
									editor.startEdit(e.rowIdx, 0);
								}else{
									Ext.MessageBox.show({
										title: 'Error',
										// msg: 'Failed to save changes.',
										msg : text,
										buttons: Ext.MessageBox.OK
									});
									editor.startEdit(e.rowIdx, 0);
									// console.log(response);
									// e.record.reject();
								}
								loadmask.hide();
							}
						});
					}
				}
			}
		});
		
		var ct_grid = Ext.create('Ext.grid.Panel', {
			store: ct_store,
			loadMask: true,
			width: 2900,
			height: 440,
			id: 'inbound_list_grid_<?=$tab_id?>',
			columns: [
				{ dataIndex: 'STATUS_EDIT', hidden: true, hideable: false},
				{ dataIndex: 'NO_REQUEST', hidden: true, hideable: false},
				{ dataIndex: 'ID_VES_VOYAGE', hidden: true, hideable: false},
				{ dataIndex: 'NO_CONTAINER_OLD', hidden: true, hideable: false},
				{ dataIndex: 'ITT_FLAG', hidden: true, hideable: false},
				{ dataIndex: 'POINT', hidden: true, hideable: false},
				{ text: 'No', dataIndex: 'REC_NUM', width: 50,},
				{ text: 'No Container', dataIndex: 'NO_CONTAINER', width: 140, filter: {type: 'string'},
					editor: {
						xtype: 'textfield',
						maskRe: /[\dA-Z]/,
						regex: /^[A-Z]{4}[\d]{7}$/,
						minLength: 11,
						maxLength: 11,
						enforceMaxLength: true,
						allowBlank: false
					}
				},
				{ text: 'Stowage', dataIndex: 'STOWAGE' , width: 80,
					editor: {
						xtype: 'textfield',
						maskRe: /[\d]/,
						regex: /^0?[\d]{6}$/,
						minLength: 6,
						maxLength: 6,
						enforceMaxLength: true,
						allowBlank: false
					}
				},
				{ text: 'Stowage To', dataIndex: 'STOWAGE_TO' , width: 120,
					editor: {
						xtype: 'textfield',
						maskRe: /[\d]/,
						regex: /^0?[\d]{6}$/,
						minLength: 6,
						maxLength: 6,
						enforceMaxLength: true,
						allowBlank: true
					}
				},
				{ text: 'ISO', dataIndex: 'ID_ISO_CODE' , width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'ID_ISO_CODE',
						valueField: 'ID_ISO_CODE',
						store: iso_code_store,
						queryMode: 'remote',
						forceSelection: true,
						hideTrigger: true,
						triggerAction: 'query',
						emptyText: 'Autocomplete',
						typeAhead: true,
						minChars: 1,
						// allowBlank: false
					}
				},
				{ text: 'Class', dataIndex: 'ID_CLASS_CODE' , width: 80,  filter: {type: 'string'},
					editor: {
						xtype: 'combo',
						displayField: 'NAME',
						valueField: 'ID',
						queryMode: 'local',
						editable: false,
						store: class_list_store,
						allowBlank: false
					}
				},
				{ text: 'OPR', dataIndex: 'ID_OPERATOR' , width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'OPERATOR_NAME',
						valueField: 'ID_OPERATOR',
						store: operator_list_store,
						queryMode: 'remote',
						forceSelection: true,
						hideTrigger: true,
						triggerAction: 'query',
						emptyText: 'Autocomplete',
						typeAhead: true,
						minChars: 3,
						// allowBlank: false
					}
				},
				{ text: 'F/M', dataIndex: 'CONT_STATUS', width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'NAME',
						valueField: 'CONT_STATUS',
						queryMode: 'local',
						editable: false,
						store: cont_status_list_store,
						allowBlank: false
					}
				},
				{ text: 'POL', dataIndex: 'ID_POL', width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'PORT_NAME',
						valueField: 'ID_POL',
						store: pol_list_store,
						queryMode: 'remote',
						forceSelection: true,
						hideTrigger: true,
						triggerAction: 'query',
						emptyText: 'Autocomplete',
						typeAhead: true,
						minChars: 2,
						// allowBlank: false
					}
				},
				{ text: 'POD', dataIndex: 'ID_POD', width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'PORT_NAME',
						valueField: 'ID_POD',
						store: pod_list_store,
						queryMode: 'remote',
						forceSelection: true,
						hideTrigger: true,
						triggerAction: 'query',
						emptyText: 'Autocomplete',
						typeAhead: true,
						minChars: 2,
						// allowBlank: false
					}
				},
				{ text: 'POR', dataIndex: 'ID_POR', width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'PORT_NAME',
						valueField: 'ID_POR',
						store: por_list_store,
						queryMode: 'remote',
						hideTrigger: true,
						triggerAction: 'query',
						emptyText: 'Autocomplete',
						typeAhead: true,
						minChars: 2
					}
				},
				{ text: 'Yard', dataIndex: 'YARD_POS', width: 100 },
				{ text: 'Complete Discharge', dataIndex: 'CONFIRM_DATE_', width: 150},
				{ text: 'WGT(Ton)', dataIndex: 'WEIGHT', width: 80,
					editor: {
						xtype: 'numberfield',
						decimalPrecision: 2,
						allowBlank: false
					}
				},
				{ text: 'Temp.(C)', dataIndex: 'TEMP', width: 80,
					editor: {
						xtype: 'numberfield',
						decimalPrecision: 2
					}
				},
				
				{ 	text: 'IMDG', dataIndex: 'IMDG', width: 100,
						editor: {
						xtype: 'combo',
						displayField: 'IMDG',
						valueField: 'IMDG',
						queryMode: 'local',
						editable: false,
						store: imdg_list_store,
						listeners: {
							change: function(field, newValue){
								unno_list_store.setProxy({
									type: 'ajax',
									url: '<?=controller_?>inbound_list/get_dataUnno/'+newValue,
									reader: {
										type: 'json'
									}
								});
								unno_list_store.load();
								field.nextSibling().setValue('');
							},
							focus: function(field, opts){
								imdg_list_store.setProxy({
									type: 'ajax',
									url: '<?=controller_?>inbound_list/data_imdg_list/',
									reader: {
										type: 'json'
									}
								});
								imdg_list_store.load();
							}
						}
					}
				},

				{ text: 'UNNO', dataIndex: 'UNNO', width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'UNNO',
						valueField: 'UNNO',
						queryMode: 'local',
						editable: false,
						store: unno_list_store,
						listeners: {
							focus: function(field, opts){
								var imdg_id = field.previousSibling().getValue();
								console.log(imdg_id);
								if (imdg_id != null){
									unno_list_store.setProxy({
										type: 'ajax',
										url: '<?=controller_?>inbound_list/get_dataUnno/'+imdg_id,
										reader: {
											type: 'json'
										}
									});
									unno_list_store.load();
								}
							}
						}
					}
				},
				
				{ text: 'Comm.', dataIndex: 'ID_COMMODITY', width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'COMMODITY_NAME',
						valueField: 'ID_COMMODITY',
						queryMode: 'local',
						editable: false,
						store: commodity_list_store
					}
				},
				{ text: 'Size', dataIndex: 'CONT_SIZE', width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'NAME',
						valueField: 'CONT_SIZE',
						queryMode: 'local',
						editable: false,
						store: cont_size_list_store,
						allowBlank: false
					}
				},
				{ text: 'Type', dataIndex: 'CONT_TYPE', width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'NAME',
						valueField: 'CONT_TYPE',
						queryMode: 'local',
						editable: false,
						store: cont_type_list_store,
						allowBlank: false
					}
				},
				{ text: 'Height', dataIndex: 'CONT_HEIGHT', width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'NAME',
						valueField: 'CONT_HEIGHT',
						queryMode: 'local',
						editable: false,
						store: cont_height_list_store,
						allowBlank: false
					}
				},
				{ text: 'TL', dataIndex: 'TL_FLAG', width: 80 },
				//NEW
				{ text: 'ESY', dataIndex: 'ITT_FLAG', width: 80 },
				{ text: 'Placement', dataIndex: 'STATUS_PLACEMENT', width: 80},
				{ text: 'OH', dataIndex: 'OVER_HEIGHT', width: 80,
					editor: {
						xtype: 'numberfield',
						decimalPrecision: 2,
						allowBlank: true
					}
				},
				{ text: 'OR', dataIndex: 'OVER_RIGHT', width: 80,
					editor: {
						xtype: 'numberfield',
						decimalPrecision: 2,
						allowBlank: true
					}
				},
				{ text: 'OL', dataIndex: 'OVER_LEFT', width: 80,
					editor: {
						xtype: 'numberfield',
						decimalPrecision: 2,
						allowBlank: true
					}
				},
				{ text: 'OF', dataIndex: 'OVER_FRONT', width: 80,
					editor: {
						xtype: 'numberfield',
						decimalPrecision: 2,
						allowBlank: true
					}
				},
				{ text: 'OLB', dataIndex: 'OVER_REAR', width: 80,
					editor: {
						xtype: 'numberfield',
						decimalPrecision: 2,
						allowBlank: true
					}
				},
//				{ text: 'OW', dataIndex: 'OVER_WIDTH', width: 80,
//					editor: {
//						xtype: 'numberfield',
//						decimalPrecision: 2,
//						allowBlank: true
//					}
//				},
				{ text: 'QC Plan', dataIndex: 'QC_PLAN', width: 80},
				{ text: 'QC Real', dataIndex: 'QC_REAL', width: 80},
				// { text: 'Status Bongkar / Muat', dataIndex: 'STATUS_BM', width: 80, },
				// { text: 'Complete Date B/M ', dataIndex: 'COMPLETE_DATE_BM', width: 80, },
				{ text: 'YC PLan', dataIndex: 'YC_PLAN', width: 80},
				{ text: 'YC Real', dataIndex: 'YC_REAL', width: 80}
				// END
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
			tbar: [{
				itemId: 'add_detail_<?=$tab_id?>',
				text: 'Add Detail',
				handler : function() {
					rowEditing.cancelEdit();
					var r = {
						STATUS_EDIT: 1,
						ID_VES_VOYAGE: '<?=$id_ves_voyage?>',
						NO_CONTAINER_OLD: '-',
						POINT: '-',
						NO_CONTAINER: 'TEST0000000',
						STOWAGE: '000000',
						ID_CLASS_CODE: 'I',
						CONT_STATUS: '-',
						WEIGHT: 0,
						CONT_SIZE: '-',
						CONT_TYPE: '-',
						CONT_HEIGHT: '-',
						TL_FLAG: 'N',
						OVER_HEIGHT: 0,
						OVER_RIGHT:0,
						OVER_LEFT:0,
						OVER_FRONT:0,
						OVER_REAR:0,
						ITT_FLAG:'N',
						STATUS_PLACEMENT:'N'
					};
					ct_store.insert(0, r);
					rowEditing.startEdit(0, 0);
				}
			}, {
				itemId: 'remove_detail_<?=$tab_id?>',
				text: 'Remove Detail',
				handler: function() {
					var sm = ct_grid.getSelectionModel();
					rowEditing.cancelEdit();
					var selected = sm.getSelection();
					loadmask.show();
					Ext.Ajax.request({
						url: '<?=controller_?>inbound_list/delete_list_detail/' + selected[0].data.NO_CONTAINER_OLD + '/' + selected[0].data.POINT,
						success: function(response){
							var text = response.responseText;
							if (text=='1'){
								Ext.MessageBox.show({
									title: 'Success',
									msg: 'Changes saved successfully.',
									buttons: Ext.MessageBox.OK
								});
								ct_store.remove(selected);
								if (ct_store.getCount() > 0) {
									sm.select(0);
								}
								ct_store.reload();
							}else{
								Ext.MessageBox.show({
									title: 'Error',
									msg: 'Failed to save changes.',
									buttons: Ext.MessageBox.OK
								});
							}
							loadmask.hide();
						}
					});
				},
				disabled: true
			},{
				itemId: 'excel_<?=$tab_id?>',
				text: 'Export Excel',
				handler: function() {
					
					rowEditing.cancelEdit();
					window.open('<?=controller_?>inbound_list/export_excel/<?=$id_ves_voyage?>','_blank');

					/*Ext.Ajax.request({
						url: '<?=controller_?>inbound_list/export_excel/' + id_ves_voyage,
						success: function(response){
							var text = response.responseText;
							if (text=='1'){
								Ext.MessageBox.show({
									title: 'Success',
									msg: 'Changes saved successfully.',
									buttons: Ext.MessageBox.OK
								});
								ct_store.remove(selected);
								if (ct_store.getCount() > 0) {
									sm.select(0);
								}
								ct_store.reload();
							}else{
								Ext.MessageBox.show({
									title: 'Error',
									msg: 'Failed to save changes.',
									buttons: Ext.MessageBox.OK
								});
							}
							loadmask.hide();
						}
					});*/
				},
				//disabled: true
			}],
			plugins: [rowEditing],
			listeners: {
				'selectionchange': function(view, records) {
					if (records.length>0){
						if (records[0].data.NO_REQUEST=='' || records[0].data.NO_REQUEST==null){
							ct_grid.down('#remove_detail_<?=$tab_id?>').setDisabled(false);
						}else{
							ct_grid.down('#remove_detail_<?=$tab_id?>').setDisabled(true);
						}
					}
				}
			}
		});
		
		ct_grid.on('beforeedit', function(editor, e) {
			var fields = ct_grid.getPlugin('rowEditingInboundList').editor.form.getFields();
			if (e.record.get('STATUS_EDIT') == 0){
				for(i=0;i<fields.items.length;i++){
					fields.items[i].enable();
					// fields.items[i].disable();
					//console.log(fields.items[i]);

				}
			}else{
				for(i=0;i<fields.items.length;i++){
					fields.items[i].enable();
				}
			}
		});
		
		ct_grid.render('inbound_list_<?=$tab_id?>');
	});
</script>
<div id="search_multiple_container_<?=$tab_id?>"></div>
<div id="inbound_list_<?=$tab_id?>"></div>

<style>
#search_multiple_container_<?=$tab_id?> {
	display: inline-block;
}

#upload_baplie_<?=$tab_id?> {
	display: inline-block;
}
</style>