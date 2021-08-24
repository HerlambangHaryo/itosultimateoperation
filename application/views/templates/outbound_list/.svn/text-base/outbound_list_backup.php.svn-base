<script type="text/javascript">
	Ext.onReady(function(){
		var cont_size_list_store = Ext.create('Ext.data.Store', {
			fields:['CONT_SIZE', 'NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>outbound_list/data_cont_size/',
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
				url: '<?=controller_?>outbound_list/data_cont_type/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		var con_spec_hand = Ext.create('Ext.data.Store', {
			fields:['ID_SPEC_HAND', 'NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>outbound_list/data_cont_spec_hand/',
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
				url: '<?=controller_?>outbound_list/data_cont_status/',
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
				url: '<?=controller_?>outbound_list/data_port/ID_POL',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		var pod_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_POD', 'PORT_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>outbound_list/data_port/ID_POD',
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
				url: '<?=controller_?>outbound_list/data_port/ID_POR',
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
				url: '<?=controller_?>outbound_list/data_operator/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		var cont_height_list_store = Ext.create('Ext.data.Store', {
			fields:['CONT_HEIGHT', 'NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>outbound_list/data_cont_height/',
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
				url: '<?=controller_?>outbound_list/data_cont_commodity/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		var class_list_store = Ext.create('Ext.data.Store', {
			fields:['ID', 'NAME'],
			data : [
				 {ID: 'TE', NAME: 'Transhipment Export'},
				 {ID: 'E', NAME: 'Export'}
			]
		});
		
		var iso_code_store = Ext.create('Ext.data.Store', {
			fields:['ID_ISO_CODE', 'ID_ISO_CODE'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>outbound_list/data_cont_iso_code/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		var unno_list_store = Ext.create('Ext.data.Store', {
			fields:['UNNO', 'UNNO'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>outbound_list/data_unno_list/',
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
				url: '<?=controller_?>outbound_list/data_imdg_list/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		var ct_store = Ext.create('Ext.data.Store', {
			fields:['NO_CONTAINER_OLD', 'NO_CONTAINER', 'POINT', 'ID_VES_VOYAGE', 'STOWAGE', 'ID_ISO_CODE', 'ID_CLASS_CODE', 'ID_OPERATOR', 'UNNO', 'IMDG', 'CONT_SIZE', 'CONT_TYPE', 'CONT_STATUS', 'CONT_HEIGHT', 'ID_POL', 'ID_POD', 'ID_POR', 'WEIGHT', 'TEMP', 'ID_COMMODITY', 'YARD_POS', 'STATUS_EDIT','ID_SPEC_HAND'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>outbound_list/data_outbound_list',
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
		
		var ct_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			pluginId: 'rowEditingOutboundList',
			//clicksToMoveEditor: 1,
			clicksToEdit:1,
			//autoCancel: false,
			errorSummary: false,
			listeners: {
				edit: function(editor, e, opt){
					var record = e.record.getChanges();
					var array = $.map(record, function(value, index) {
						return [value];
					});
					if (array.length>0){
						loadmask.show();
						Ext.Ajax.request({
							url: '<?=controller_?>outbound_list/update_list_detail/' + e.record.data.NO_CONTAINER + '/' + e.record.data.ID_VES_VOYAGE + '/' + e.record.data.NO_CONTAINER_OLD + '/' + e.record.data.POINT,
							params: e.record.getChanges(),
							success: function(response){
								var text = response.responseText;
								if (text=='1'){
									Ext.MessageBox.show({
										title: 'Success',
										msg: 'Changes saved successfully.',
										buttons: Ext.MessageBox.OK
									});
									ct_store.reload();
								}else{
									Ext.MessageBox.show({
										title: 'Error',
										msg: 'Failed to save changes.',
										buttons: Ext.MessageBox.OK
									});
									editor.startEdit(e.rowIdx, 0);
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
			width: 1600,
			height: 440,
			columns: [
				{ dataIndex: 'STATUS_EDIT', hidden: true, hideable: false},
				{ dataIndex: 'ID_VES_VOYAGE', hidden: true, hideable: false},
				{ dataIndex: 'NO_CONTAINER_OLD', hidden: true, hideable: false},
				{ dataIndex: 'POINT', hidden: true, hideable: false},
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
						maxLength: 7,
						enforceMaxLength: true
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
				{ text: 'Class', dataIndex: 'ID_CLASS_CODE' , width: 80,
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
				{ text: 'UNNO', dataIndex: 'UNNO', width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'UNNO',
						valueField: 'UNNO',
						store: unno_list_store,
						queryMode: 'remote',
						// forceSelection: true,
						hideTrigger: true,
						triggerAction: 'query',
						emptyText: 'Autocomplete',
						typeAhead: true,
						minChars: 1,
					}
				},
				{ text: 'IMDG', dataIndex: 'IMDG', width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'IMDG',
						valueField: 'IMDG',
						store: imdg_list_store,
						queryMode: 'remote',
						// forceSelection: true,
						hideTrigger: true,
						triggerAction: 'query',
						emptyText: 'Autocomplete',
						typeAhead: true,
						minChars: 1,
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
				}
				/*tambahan special handling di outbound list*/
				,
				{ text: 'Handling', dataIndex: 'ID_SPEC_HAND', width: 80,
					editor: {
						xtype: 'combo',
						displayField: 'NAME',
						valueField: 'ID_SPEC_HAND',
						queryMode: 'local',
						editable: true,
						store: con_spec_hand,
						//allowBlank: false
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
						ID_CLASS_CODE: 'E',
						CONT_STATUS: '-',
						WEIGHT: 0,
						CONT_SIZE: '-',
						CONT_TYPE: '-',
						CONT_HEIGHT: '-'
					};
					ct_store.insert(0, r);
					rowEditing.startEdit(0, 0);
				}
			},{
				itemId: 'remove_detail_<?=$tab_id?>',
				text: 'Remove Detail',
				handler: function() {
					var sm = ct_grid.getSelectionModel();
					rowEditing.cancelEdit();
					var selected = sm.getSelection();
					loadmask.show();
					Ext.Ajax.request({
						url: '<?=controller_?>outbound_list/delete_list_detail/' + selected[0].data.NO_CONTAINER_OLD + '/' + selected[0].data.POINT,
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
			}]
			,
			plugins: [rowEditing]/*,
			listeners: {
				'selectionchange': function(view, records) {
					ct_grid.down('#remove_detail_<?=$tab_id?>').setDisabled(false);
				}
			}*/
		});

		
		ct_grid.on('beforeedit', function(editor, e) {
			var fields = ct_grid.getPlugin('rowEditingOutboundList').editor.form.getFields();
			
			if (e.record.get('STATUS_EDIT') == 0){
				for(i=0;i<fields.items.length-1;i++){
					fields.items[i].disable();
				}
			}else{
				for(i=0;i<fields.items.length;i++){
					fields.items[i].enable();
				}
			}
		});
		
		
		ct_grid.render('outbound_list_<?=$tab_id?>');
	});
</script>
<div id="outbound_list_<?=$tab_id?>"></div>