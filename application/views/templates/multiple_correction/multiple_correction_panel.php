<script type="text/javascript">
	Ext.onReady(function(){
		var vessel_schedule_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_VES_VOYAGE', 'VESSEL'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>multiple_correction/data_vessel_schedule_autocomplete/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		var truck_loosing_store = Ext.create('Ext.data.Store', {
			fields:['ID', 'TL_FLAG'],
			data : [
				 {ID: 'Y', TL_FLAG: 'Yes'},
				 {ID: 'N', TL_FLAG: 'No'}
			 ]
		});

		var pod_list_store = Ext.create('Ext.data.Store',{
			fields:['ID_POD','PORT_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>multiple_correction/data_port/',
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
				url: '<?=controller_?>multiple_correction/data_port/ID_POL',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
	
		var por_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_POR', 'PORT_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>multiple_correction/data_port/ID_POR',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});

		var iso_code_store = Ext.create('Ext.data.Store', {
			fields:['ID_ISO_CODE', 'ID_ISO_CODE'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>single_correction/data_cont_iso_code/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});

		var operator_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_OPERATOR', 'OPERATOR_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>single_correction/data_operator/',
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
				url: '<?=controller_?>single_correction/data_cont_status/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		Ext.create('Ext.form.Panel', {
			id: "container_search_form_<?=$tab_id?>",
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: 'multiple_correction_number_<?=$tab_id?>',
				xtype: 'textareafield',
				name: "container_list",
				fieldLabel: 'No Container',
				allowBlank: false
			}],
			buttons: [{
				text: 'Search',
				formBind: true,
				listeners: {
					click: {
						fn: function () {
							var form = this.up('form').getForm();
							if (form.isValid()){
								var container_number = Ext.getCmp("multiple_correction_number_<?=$tab_id?>").getValue();
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
									container_list: container_param
								};
								ct_store.load();
							}
						}
					}
				}
			}]
		}).render('container_search_<?=$tab_id?>');
		
		var ct_store = Ext.create('Ext.data.Store', {
			fields:['NO_CONTAINER', 'POINT', 'ID_VES_VOYAGE', 'ID_POD', 'ID_POL', 'ID_POR', 'ID_ISO_CODE', 'ID_CLASS_CODE', 'ID_OPERATOR', 'CONT_STATUS', 'TL_FLAG', 'EDIT_VESSEL', 'EDIT_TL',
					'OVER_HEIGHT','OVER_HEIGHT','OVER_RIGHT','OVER_LEFT','OVER_FRONT','OVER_REAR','WEIGHT','SEAL_NUMB'],
			autoLoad: false,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>multiple_correction/data_multiple_correction',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			listeners: {
				datachanged: function (){
					vessel_schedule_list_store.load();
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
		
		var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			pluginId: 'rowEditingInboundList',
			clicksToMoveEditor: 1,
			autoCancel: false,
			listeners: {
				edit: function(editor, e, opt){
					var record = e.record.getChanges();
					var array = $.map(record, function(value, index) {
						return [value];
					});
					if (array.length>0){
						loadmask.show();
						Ext.Ajax.request({
							url: '<?=controller_?>multiple_correction/update_list_detail/' + e.record.data.NO_CONTAINER + '/' + e.record.data.POINT,
							params: e.record.getChanges(),
							success: function(response){
								var text = eval(response.responseText);

								if (text[0]=='S'){
									Ext.MessageBox.show({
										title: 'Success',
										msg: text[1],
										buttons: Ext.MessageBox.OK
									});
									ct_store.reload();
								}else{
									Ext.MessageBox.show({
										title: 'Error',
										msg: text[1],
										buttons: Ext.MessageBox.OK
									});
									editor.startEdit(e.rowIdx, 0);
									loadmask.hide();
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
			width: 1300,
			height: 450,
			id: 'multiple_correction_list_grid_<?=$tab_id?>',
			columns: [
				{ dataIndex: 'EDIT_VESSEL', hidden: true, hideable: false},
				{ dataIndex: 'EDIT_TL', hidden: true, hideable: false},
				{ dataIndex: 'POINT', hidden: true, hideable: false},
				{ text: 'No Container', dataIndex: 'NO_CONTAINER', width: 140, filter: {type: 'string'}},
				{ text: 'Vessel', dataIndex: 'ID_VES_VOYAGE', width: 150,
					editor: {
						xtype: 'combo',
						displayField: 'VESSEL',
						valueField: 'ID_VES_VOYAGE',
						store: vessel_schedule_list_store,
						queryMode: 'remote',
						forceSelection: true,
						hideTrigger: true,
						triggerAction: 'query',
						emptyText: 'Autocomplete',
						typeAhead: true,
						minChars: 3,
						editable: false,
						allowBlank: false
					}
				},
				{ text: 'POD', dataIndex: 'ID_POD', width: 80,
					editor: {
						xtype:'combo',
						displayField: 'ID_POD',
						valueField: 'ID_POD',
						store: pod_list_store,
						queryMode: 'remote',
						forceSelection : true,
						hideTrigger: true,
						triggerAction: 'query',
						emptyText: 'Autocomplete',
						typeAhead: true,
						minChars: 3,
						editable: true,
						allowBlank: true
					}
				},				
				// { text: 'POL', dataIndex: 'ID_POL', width: 80,
					// editor: {
						// xtype:'combo',
						// displayField: 'ID_POL',
						// valueField: 'ID_POL',
						// store: pol_list_store,
						// queryMode: 'remote',
						// forceSelection : true,
						// hideTrigger: true,
						// triggerAction: 'query',
						// emptyText: 'Autocomplete',
						// typeAhead: true,
						// minChars: 3,
						// editable: true,
						// allowBlank: true
					// }
				// },						
				{ text: 'FPOD', dataIndex: 'ID_POR', width: 80,
					editor: {
						xtype:'combo',
						displayField: 'ID_POR',
						valueField: 'ID_POR',
						store: por_list_store,
						queryMode: 'remote',
						forceSelection : true,
						hideTrigger: true,
						triggerAction: 'query',
						emptyText: 'Autocomplete',
						typeAhead: true,
						minChars: 3,
						editable: true,
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
						minChars: 3,
						editable: true,
						allowBlank: false
					}
				},
				{ text: 'Class', dataIndex: 'ID_CLASS_CODE' , width: 80},
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
						editable: true,
						allowBlank: false
						//fieldLabel: 'Operator',
						//name: 'ID_OPERATOR'
					}
				},
				{ text: 'F/M', dataIndex: 'CONT_STATUS', width: 80,
				editor : {
					xtype: 'combo',
					displayField: 'NAME',
					valueField: 'CONT_STATUS',
					queryMode: 'local',
					editable: true,
					store: cont_status_list_store,
					allowBlank: false
					//fieldLabel: 'Full/Empty',
					//name: 'CONT_STATUS'
					}
				},

				{ text: 'Weight', dataIndex: 'WEIGHT', width: 80,
					editor : {
						xtype: 'numberfield',
						valueField: 'WEIGHT',
						editable: true,
						allowBlank: true
					}
				},

				{ text: 'Seal Numb', dataIndex: 'SEAL_NUMB', width: 80,
					editor : {
						xtype: 'textfield',
						valueField: 'SEAL_NUMB',
						editable: true,
						allowBlank: true
					}
				},

				{ text: 'TL', dataIndex: 'TL_FLAG', width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'TL_FLAG',
						valueField: 'ID',
						queryMode: 'local',
						editable: false,
						store: truck_loosing_store,
						allowBlank: false,
						readOnly: true
					}
				},
				{ text: 'Over Height', dataIndex: 'OVER_HEIGHT', width: 80,
					editor: {
						xtype: 'numberfield',
						valueField: 'OVER_HEIGHT',
						editable: true,
						allowBlank: true
					}
				},
				{ text: 'Over Right', dataIndex: 'OVER_RIGHT', width: 80,
					editor: {
						xtype: 'numberfield',
						valueField: 'OVER_RIGHT',
						editable: true,
						allowBlank: true
					}
				},
				{ text: 'Over Left', dataIndex: 'OVER_LEFT', width: 80,
					editor: {
						xtype: 'numberfield',
						valueField: 'OVER_LEFT',
						editable: true,
						allowBlank: true
					}
				},
				{ text: 'Over Front', dataIndex: 'OVER_FRONT', width: 80,
					editor: {
						xtype: 'numberfield',
						valueField: 'OVER_FRONT',
						editable: true,
						allowBlank: true
					}
				},
				{ text: 'Over Rear', dataIndex: 'OVER_REAR', width: 80,
					editor: {
						xtype: 'numberfield',
						valueField: 'OVER_REAR',
						editable: true,
						allowBlank: true
					}
				}
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
			plugins: [rowEditing]
		});
		
		ct_grid.on('beforeedit', function(editor, e) {
			var fields = ct_grid.getPlugin('rowEditingInboundList').editor.form.getFields();
			console.log("status beforeedit : "+e.record.get('EDIT_VESSEL'));
			if (e.record.get('EDIT_VESSEL') == 0){
				fields.items[1].disable();
			}else{
				fields.items[1].enable();
			}
			fields.items[1].disable();
			if (e.record.get('EDIT_TL') == 0){
				fields.items[8].disable();
			}else{
				fields.items[8].enable();
			}
		});
		
		ct_grid.render('multiple_correction_list_<?=$tab_id?>');
	});
</script>
<div id="container_search_<?=$tab_id?>"></div>
<div id="multiple_correction_list_<?=$tab_id?>"></div>