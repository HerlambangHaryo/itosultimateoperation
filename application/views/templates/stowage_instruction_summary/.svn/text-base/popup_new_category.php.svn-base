<script type="text/javascript">
// function PAWeightDialog()
// {
	// Ext.Ajax.request({
		// url: '<?=controller_?>yard_planning/popup_master_paweight?tab_id=<?=$tab_id?>',
		// callback: function(opt,success,response){
			// $("#popup_script_<?=$tab_id?>").html(response.responseText);
		// } 
	// });
// }

$(function() {
	var cont_size_list_store = Ext.create('Ext.data.Store', {
		fields:['CONT_SIZE', 'NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_planning/data_cont_size/',
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
			url: '<?=controller_?>yard_planning/data_cont_type/',
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
			url: '<?=controller_?>yard_planning/data_cont_status/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var port_list_store = Ext.create('Ext.data.Store', {
		fields:['PORT_CODE', 'PORT_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_planning/data_port/',
			reader: {
				type: 'json'
			}
		}
	});
	
	var vessel_schedule_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_VES_VOYAGE', 'VESSEL'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_planning/data_vessel_schedule_autocomplete/',
			reader: {
				type: 'json'
			}
		}
	});
	
	var operator_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_OPERATOR', 'OPERATOR_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_planning/data_operator/',
			reader: {
				type: 'json'
			}
		}
	});
	
	var cont_height_list_store = Ext.create('Ext.data.Store', {
		fields:['CONT_HEIGHT', 'NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_planning/data_cont_height/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var cont_weightpa_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_PAWEIGHT', 'NAME_PAWEIGHT'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_planning/data_paweight/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var cont_weightpaD_list_store = Ext.create('Ext.data.Store', {
		fields:['DNAME_PAWEIGHT', 'CATEGNAME_PAWEIGHT']
	});
	
	var hazard_list_store = Ext.create('Ext.data.Store', {
		fields:['ID', 'NAME'],
		data : [
			 {ID: 'Y', NAME: 'Yes'},
			 {ID: 'N', NAME: 'No'}
		 ]
	});
	
	var unno_list_store = Ext.create('Ext.data.Store', {
		fields:['UNNO', 'UNNO'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_planning/data_unno_list/',
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
			url: '<?=controller_?>yard_planning/data_imdg_list/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var activity_list_store = Ext.create('Ext.data.Store', {
		fields:['ID', 'NAME'],
		data : [
			 {ID: 'E', NAME: 'Outbound'},
			 {ID: 'I', NAME: 'Inbound'}
		 ]
	});
	
	var voyage_type_list_store = Ext.create('Ext.data.Store', {
		fields:['ID', 'NAME'],
		data : [
			 {ID: 'O', NAME: 'Ocean Going'},
			 {ID: 'I', NAME: 'Intersuler'}
		 ]
	});
	
	var category_detail_store = Ext.create('Ext.data.Store', {
		fields:[
				// 'ID_SI_CTGR',
				'CATEGORY_NAME', 
				'ID_POD',
				'CONT_SIZE', 'CONT_TYPE', 'CONT_STATUS', 'E_I','ID_VES_VOYAGE','ID_VESSEL']
	});
	
	var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
		clicksToMoveEditor: 1,
		autoCancel: false,
		listeners: {
			edit: function(editor, e, opt){
				var record = e.record.getChanges();
				var array = $.map(record, function(value, index) {
					return [value];
				});
			}
		}
    });
	
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Plan Category',
		closable: false,
		width: 950,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: "category_name_<?=$tab_id?>",
				xtype: 'textfield',
				name: "category_name_<?=$tab_id?>",
				fieldLabel: 'Category Name',
				maskRe: /[\w\s]/,
				regex: /[\w\s]/,
				allowBlank: false
			},{
				id: 'category_detail_<?=$tab_id?>',
				xtype: 'grid',
				width: 1325,
				minHeight: 150,
				autoScroll: true,
				store: category_detail_store,
				tbar: [{
					itemId: 'add_detail_<?=$tab_id?>',
					text: 'Add Detail',
					handler : function() {
						rowEditing.cancelEdit();
						var r = {
							CONT_SIZE: '-',
							CONT_TYPE: '-',
							CONT_STATUS: '-',
							E_I: '-'
						};
						category_detail_store.insert(0, r);
						rowEditing.startEdit(0, 0);
					}
				},{
					itemId: 'remove_detail_<?=$tab_id?>',
					text: 'Remove Detail',
					handler: function() {
						var sm = Ext.getCmp('category_detail_<?=$tab_id?>').getSelectionModel();
						rowEditing.cancelEdit();
						var selected = sm.getSelection();
						category_detail_store.remove(selected);
						if (category_detail_store.getCount() > 0) {
							sm.select(0);
						}
					},
					disabled: true
				}
				// ,{
					// itemId: 'add_paweight_<?=$tab_id?>',
					// text: 'Add PA Weight',
					// handler : function() {
						// PAWeightDialog();
					// }
				// }
				],
				plugins: [rowEditing],
				listeners: {
					'selectionchange': function(view, records) {
						Ext.getCmp('category_detail_<?=$tab_id?>').down('#remove_detail_<?=$tab_id?>').setDisabled(!records.length);
					}
				},
				columns: [
					// { dataIndex: 'ID_SI_CTGR', hidden: true, hideable: false},
					{ dataIndex: 'CATEGORY_NAME', hidden: true, hideable: false},
					// { dataIndex: 'ID_VES_VOYAGE', 
					  // // hidden: true, 
					  // // hideable: false,
					  // store: '<?=$id_ves_voyage?>'},
					
					
					{ text: 'POD', dataIndex: 'ID_POD', width: 180,
						editor: {
							xtype: 'combo',
							displayField: 'PORT_NAME',
							valueField: 'PORT_CODE',
							store: port_list_store,
							queryMode: 'remote',
							hideTrigger: true,
							triggerAction: 'query',
							emptyText: 'Autocomplete',
							typeAhead: true,
							minChars: 2,
                            listConfig: {
                                emptyText: '<span style="padding:5px;text-align:center;font-style: italic;">' +
                                    'No Data Found' + 
                                '</span>'
                            }
						}
					},
					{ text: 'Size', dataIndex: 'CONT_SIZE', width: 70,
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
					{ text: 'Type', dataIndex: 'CONT_TYPE',
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
					
					{ text: 'Commodity', dataIndex: 'CONT_STATUS', width: 100,
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
					
					
					// { text: 'F/M', dataIndex: 'HAZARD', width: 80,
						// editor: {
							// xtype: 'combo',
							// displayField: 'NAME',
							// valueField: 'ID',
							// queryMode: 'local',
							// editable: false,
							// store: hazard_list_store
						// }
					// },
					{ text: 'Outbound/Inbound', dataIndex: 'E_I', 
						editor: {
							allowBlank: false,
							xtype: 'combo',
							displayField: 'NAME',
							valueField: 'ID',
							queryMode: 'local',
							editable: false,
							store: activity_list_store
						}
					}
				]
			}],
			buttons: [{
				text: 'Plan',
				formBind: true,
				handler: function() {
					if (this.up('form').getForm().isValid()){
						var category_name = this.up('form').getForm().findField("category_name_<?=$tab_id?>").getValue();
						var category_detail = Ext.encode(Ext.pluck(category_detail_store.data.items, 'data'));
						var ves_voyage = '<?=$_POST['id_ves_voyage']?>';
						// var ves_voyage = '<?=$id_ves_voyage?>';
						// '<?=$id_ves_voyage?>'
						loadmask.show();
						Ext.Ajax.request({
							url: '<?=controller_?>stowage_instruction_summary/insert_category/',
							params: {name: category_name, detail: category_detail, ves_voy : ves_voyage},
							success: function(response){
								var text = response.responseText;
								if (text!='0'){
									loadmask.hide();
									Ext.MessageBox.show({
										title: 'Success',
										msg: 'New category inserted.',
										buttons: Ext.MessageBox.OK,
										fn:function(btn) {
											PlanYard_<?=$tab_id?>(text);
										}
									});
								}else{
									loadmask.hide();
									Ext.MessageBox.show({
										title: 'Error',
										msg: 'Failed to save changes.',
										buttons: Ext.MessageBox.OK
									});
								}
							}
						});
						win.close();
					}
				}
			},{
				text: 'Cancel',
				handler: function() {
					win.close();
				}
			}]
		})]
	});
	win.show();
});
</script>