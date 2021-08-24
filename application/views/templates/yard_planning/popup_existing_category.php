<script type="text/javascript">
function PAWeightDialog()
{
	Ext.Ajax.request({
		url: '<?=controller_?>yard_planning/popup_master_paweight?tab_id=<?=$tab_id?>',
		callback: function(opt,success,response){
			console.log(response.responseText);
			$("#popup_script_<?=$tab_id?>").html(response.responseText);
		} 
	});
}

function DelPAWeightDialog()
{
	Ext.Ajax.request({
		url: '<?=controller_?>yard_planning/popup_master_del_paweight?tab_id=<?=$tab_id?>',
		callback: function(opt,success,response){
			$("#popup_script_<?=$tab_id?>").html(response.responseText);
		} 
	});
}

$(function() {
	var category_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_CATEGORY', 'CATEGORY_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_planning/data_category_existing/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
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
		},
		autoLoad: true
	});
	
	var vessel_schedule_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_VES_VOYAGE', 'VESSEL'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_planning/data_vessel_schedule_autocomplete/',
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
			url: '<?=controller_?>yard_planning/data_operator/',
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
		fields:['ID_PAWEIGHT', 'DNAME_PAWEIGHT','TAMPIL'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_planning/get_datapaWeightD/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var hazard_list_store = Ext.create('Ext.data.Store', {
		fields:['ID', 'NAME'],
		data : [
			 {ID: 'Y', NAME: 'Yes'},
			 {ID: 'N', NAME: 'No'}
		 ]
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
	
	var activity_list_store = Ext.create('Ext.data.Store', {
		fields:['ID', 'NAME'],
		data : [
			 {ID: 'E', NAME: 'Outbound'},
			 {ID: 'I', NAME: 'Inbound'},
			 {ID: 'T', NAME: 'Transhipment'}
		 ]
	});
	
	var voyage_type_list_store = Ext.create('Ext.data.Store', {
		fields:['ID', 'NAME'],
		data : [
			 // {ID: 'O', NAME: 'Ocean Going'},
			 {ID: 'I', NAME: 'Intersuler'}
		 ]
	});
	
	var category_detail_store = Ext.create('Ext.data.Store', {
		fields:['ID_CATEGORY', 'ID_DETAIL', 'CONT_SIZE', 'CONT_TYPE', 'CONT_STATUS', 'ID_PORT_DISCHARGE', 'ID_VES_VOYAGE', 'ID_OPERATOR', 'CONT_HEIGHT', 'PAWEIGHT', 'PAWEIGHT_D', 'HAZARD', 'IMDG', 'UNNO', 'E_I', 'O_I']
	});
	
	var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
		pluginId: 'rowEditingExistingCategory',
		clicksToMoveEditor: 1,
		autoCancel: false,
		listeners: {
			edit: function(editor, e, opt){
//			    alert('masuk sini');
				var isValid = true;
				var error_message = '';
				var min_slot = $('#slot_from_category').val();
				var max_slot = $('#slot_to_category').val();
				var record = e.record.getChanges();
				var array = $.map(record, function(value, index) {
				    return [value];
				});
//				console.log('e.record.ID_VES_VOYAGE');
//				console.log(e.originalValues.ID_VES_VOYAGE);
//				console.log(e.newValues.ID_VES_VOYAGE);
				var y = e.newValues;
//				var z = e.originalValues;
				console.log('y.CONT_SIZE');
				console.log(y.CONT_SIZE);
				console.log('min_slot : ' + min_slot);
				console.log('max_slot : ' + max_slot);
				if(y.CONT_SIZE != '20' && y.CONT_SIZE != '21' && min_slot == max_slot){
				    isValid = false;
				    error_message = 'Container Size selain 20 harus menggunakan lebih dari 1 slot';
//				    return false;
				}
				console.log('y.CONT_HEIGHT : ' + y.CONT_HEIGHT);
				console.log('y.CONT_SIZE : ' + y.CONT_SIZE);
				console.log('y.CONT_STATUS : ' + y.CONT_STATUS);
				console.log('y.CONT_TYPE : ' + y.CONT_TYPE);
				console.log('y.E_I : ' + y.E_I);
				console.log('yID_CATEGORYE_I : ' + y.ID_CATEGORY);
				console.log('y.ID_PORT_DISCHARGE : ' + y.ID_PORT_DISCHARGE);
				if(y.CONT_HEIGHT == '' && y.CONT_SIZE == '-' && y.CONT_STATUS == '-' && y.CONT_TYPE == '-' && y.E_I == '-' && y.ID_PORT_DISCHARGE == ''){
				    isValid = false;
				    error_message = "Detail can't be empty";
//				    return false;
				}
				console.log('y.ID_VES_VOYAGE');
				console.log(y.ID_VES_VOYAGE);
				if(y.ID_VES_VOYAGE == ''){
				    isValid = false;
				    error_message = "Vessel Voyage  must be can't be empty";
//				    return false;
				}
				
				if (isValid){
					loadmask.show();
					Ext.Ajax.request({
						url: '<?=controller_?>yard_planning/update_category_detail/' + e.record.data.ID_CATEGORY + '/' + e.record.data.ID_DETAIL,
						params: e.record.getChanges(),
						success: function(response){
							var text = response.responseText;
							if (text=='1'){
								loadmask.hide();
								Ext.MessageBox.show({
									title: 'Success',
									msg: 'Changes saved successfully.',
									buttons: Ext.MessageBox.OK
								});
							}else{
								loadmask.hide();
								Ext.MessageBox.show({
									title: 'Error',
									msg: 'Failed to save changes.',
									buttons: Ext.MessageBox.OK
								});
								e.record.reject();
							}
						}
					});
				}else{
				    alert(error_message);
				}
			}
		}
    });
	
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Plan Category',
		closable: false,
		width: 1350,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				xtype: 'fieldset',
				border: false,
				layout: 'hbox',
				items: [{
					id: "category_<?=$tab_id?>",
					xtype: 'combo',
					name: "category_<?=$tab_id?>",
					fieldLabel: 'Category List',
					displayField: 'CATEGORY_NAME',
					valueField: 'ID_CATEGORY',
					queryMode: 'local',
					store: category_list_store,
					value:'',
					// forceSelection: true,
					typeAhead: true,
					minChars: 1,
					allowBlank: false,
					hideTrigger: true,
					emptyText: 'Search',
					listeners: {
						select: function(field, record){
							category_detail_store.setProxy({
								type: 'ajax',
								url: '<?=controller_?>yard_planning/data_category_detail/'+record[0].data.ID_CATEGORY,
								reader: {
									type: 'json'
								}
							});
							Ext.Ajax.request({
								url: '<?=controller_?>yard_planning/getSlotCategory/',
								params: {
									category: record[0].data.ID_CATEGORY
								},
								success: function(response){
									var arr = JSON.parse(response.responseText);
									$('#slot_from_category').val(arr.START_SLOT);
									$('#slot_to_category').val(arr.END_SLOT);
									loadmask.hide();
								}
							});
							category_detail_store.load();
							Ext.getCmp('category_detail_<?=$tab_id?>').down('#add_detail_<?=$tab_id?>').setDisabled(false);
						},
						change: function() {
							var store = this.store;
							//store.suspendEvents();
							store.clearFilter();
							//store.resumeEvents();
							store.filter({
								property: 'CATEGORY_NAME',
								anyMatch: true,
								value   : this.getValue()
							});
						}
					}
				}
				
				,{
					xtype: 'button',
					margin: '0 0 0 5',
					id: "deletecategory_<?=$tab_id?>",
					text: 'Delete Category',
					handler: function(){
						var category_id = Ext.getCmp('category_<?=$tab_id?>').getValue();
						if (category_id!=null){
							loadmask.show();
							Ext.Ajax.request({
								url: '<?=controller_?>yard_planning/delete_category_plan/',
								params: {
									category_id: Ext.getCmp('category_<?=$tab_id?>').getValue()
								},
								success: function(response){
									var text = response.responseText;
									if (text=='1'){
										Ext.MessageBox.show({
											title: 'Success',
											msg: 'Category deleted.',
											buttons: Ext.MessageBox.OK
										});
										category_list_store.load();
										category_detail_store.removeAll();
										Ext.getCmp('category_<?=$tab_id?>').clearValue();
										Ext.getCmp('category_<?=$tab_id?>').checkChange();
									}else{
										Ext.MessageBox.show({
											title: 'Error',
											msg: 'Failed to delete category.',
											buttons: Ext.MessageBox.OK
										});
									}
									loadmask.hide();
								}
							});
						}else{
							Ext.MessageBox.show({
								title: 'Warning',
								msg: 'Please select a category.',
								buttons: Ext.MessageBox.OK
							});
						}
					}
				}
				
				,{
					xtype: 'button',
					margin: '0 0 0 5',
					id: "renamecategory_<?=$tab_id?>",
					text: 'Rename Category',
					handler: function(){
						var category_id = Ext.getCmp('category_<?=$tab_id?>').getValue();
						if (category_id!=null){
							Ext.Ajax.request({
								url: '<?=controller_?>yard_planning/popup_rename_category?tab_id=<?=$tab_id?>',
								params: {
									category_id: Ext.getCmp('category_<?=$tab_id?>').getValue(),
									category_name: Ext.getCmp('category_<?=$tab_id?>').getRawValue()
								},
								success: function(response){
									$("#popup_script_<?=$tab_id?>").html(response.responseText);
								}
							});
						}else{
							Ext.MessageBox.show({
								title: 'Warning',
								msg: 'Please select a category.',
								buttons: Ext.MessageBox.OK
							});
						}
					}
				}
				
			
				]
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
						var id_category = Ext.getCmp("category_<?=$tab_id?>").getValue();
						var r = {
							ID_CATEGORY: id_category,
							CONT_SIZE: '-',
							CONT_TYPE: '-',
							CONT_STATUS: '-',
							E_I: '-'
						};
						category_detail_store.insert(0, r);
						rowEditing.startEdit(0, 0);
					},
//					handler : function() {
//						rowEditing.cancelEdit();
//						var id_category = Ext.getCmp("category_<?=$tab_id?>").getValue();
//						var r = {
//							ID_CATEGORY: id_category,
//							CONT_SIZE: '-',
//							CONT_TYPE: '-',
//							CONT_STATUS: '-',
//							E_I: '-'
//						};
//						loadmask.show();
//						Ext.Ajax.request({
//							url: '<?=controller_?>yard_planning/insert_category_detail/' + id_category,
//							params: r,
//							success: function(response){
//								var text = response.responseText;
//								if (text!='0'){
//									Ext.MessageBox.show({
//										title: 'Success',
//										msg: 'New detail inserted.',
//										buttons: Ext.MessageBox.OK
//									});
//									r.ID_DETAIL = text;
//									category_detail_store.insert(0, r);
//									rowEditing.startEdit(0, 0);
//								}else{
//									Ext.MessageBox.show({
//										title: 'Error',
//										msg: 'Failed to save changes.',
//										buttons: Ext.MessageBox.OK
//									});
//								}
//								loadmask.hide();
//							}
//						});
//					},
					disabled: true
				}, {
					itemId: 'remove_detail_<?=$tab_id?>',
					text: 'Remove Detail',
					handler: function() {
						var sm = Ext.getCmp('category_detail_<?=$tab_id?>').getSelectionModel();
						rowEditing.cancelEdit();
						var selected = sm.getSelection();
						loadmask.show();
						Ext.Ajax.request({
							url: '<?=controller_?>yard_planning/delete_category_detail/' + selected[0].data.ID_CATEGORY + '/' + selected[0].data.ID_DETAIL,
							success: function(response){
								var text = response.responseText;
								if (text=='1'){
									Ext.MessageBox.show({
										title: 'Success',
										msg: 'Changes saved successfully.',
										buttons: Ext.MessageBox.OK
									});
									category_detail_store.remove(selected);
									if (category_detail_store.getCount() > 0) {
										sm.select(0);
									}
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
					itemId: 'add_paweight_<?=$tab_id?>',
					text: 'Add PA Weight',
					handler : function() {
						PAWeightDialog();
					}
				},{
					itemId: 'del_paweight_<?=$tab_id?>',
					text: 'Delete PA Weight',
					handler : function() {
						DelPAWeightDialog();
					}
				}],
				plugins: [rowEditing],
				listeners: {
					'selectionchange': function(view, records) {
						Ext.getCmp('category_detail_<?=$tab_id?>').down('#remove_detail_<?=$tab_id?>').setDisabled(!records.length);
					}
				},
				columns: [
					{ dataIndex: 'ID_CATEGORY', hidden: true, hideable: false},
					{ dataIndex: 'ID_DETAIL', hidden: true, hideable: false},
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
					},
					{ text: 'Vessel Voyage', dataIndex: 'ID_VES_VOYAGE',
						editor: {
							xtype: 'combo',
							displayField: 'VESSEL',
							valueField: 'ID_VES_VOYAGE',
							store: vessel_schedule_list_store,
							queryMode: 'remote',
							hideTrigger: true,
							triggerAction: 'query',
							emptyText: 'Autocomplete',
							typeAhead: true,
							minChars: 3,
							listConfig: {
							    emptyText: '<span style="padding:5px;text-align:center;font-style: italic;">' +
								'No Data Found' + 
							    '</span>'
							},
							listeners: {
								change: function(field, newValue){
									port_list_store.getProxy().extraParams = {
										id_ves_voyage: newValue
									};
									Ext.getCmp('id_pod_<?=$tab_id?>').getStore().reload();
									field.nextSibling().setValue('');
									operator_list_store.getProxy().extraParams = {
										id_ves_voyage: newValue
									};
									Ext.getCmp('operator_<?=$tab_id?>').getStore().reload();
									Ext.getCmp('operator_<?=$tab_id?>').setValue('');
								}
							}
						}
					}
					
					
					,{ text: 'Operator', dataIndex: 'ID_OPERATOR', width: 150,
						editor: {
							id: 'operator_<?=$tab_id?>',
							xtype: 'combo',
							displayField: 'OPERATOR_NAME',
							valueField: 'ID_OPERATOR',
							store: operator_list_store,
							queryMode: 'remote',
							hideTrigger: true,
							triggerAction: 'query',
							emptyText: 'Autocomplete',
							typeAhead: true,
							minChars: 3,
                            listConfig: {
                                emptyText: '<span style="padding:5px;text-align:center;font-style: italic;">' +
                                    'No Data Found' + 
                                '</span>'
                            }
						}
					}					
					
					,{ text: 'POD', dataIndex: 'ID_PORT_DISCHARGE', width: 180,
						editor: {
							id: 'id_pod_<?=$tab_id?>',
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

					{ text: 'PAWgCat', dataIndex: 'PAWEIGHT', width: 80,
						editor: {
							xtype: 'combo',
							displayField: 'NAME_PAWEIGHT',
							valueField: 'ID_PAWEIGHT',
							queryMode: 'local',
							editable: false,
							store: cont_weightpa_list_store,
							listeners: {
								change: function(field, newValue){
									cont_weightpaD_list_store.setProxy({
										type: 'ajax',
										url: '<?=controller_?>yard_planning/get_datapaWeightD/'+newValue,
										reader: {
											type: 'json'
										}
									});
									cont_weightpaD_list_store.load();
									field.nextSibling().setValue('');
								},
								focus: function(field, opts){
									cont_weightpa_list_store.setProxy({
										type: 'ajax',
										url: '<?=controller_?>yard_planning/data_paweight/',
										reader: {
											type: 'json'
										}
									});
									cont_weightpa_list_store.load();
								}
							}
						}
					},
					{ text: 'PAWgSub', dataIndex: 'PAWEIGHT_D', width: 100,
						editor: {
							xtype: 'combo',
							displayField: 'TAMPIL',
							valueField: 'DNAME_PAWEIGHT',
							queryMode: 'local',
							editable: false,
							store: cont_weightpaD_list_store,
							listeners: {
								focus: function(field, opts){
									var paweight_id = field.previousSibling().getValue();
									if (paweight_id != null){
										cont_weightpaD_list_store.setProxy({
											type: 'ajax',
											url: '<?=controller_?>yard_planning/get_datapaWeightD/'+paweight_id,
											reader: {
												type: 'json'
											}
										});
										cont_weightpaD_list_store.load();
									}
								}
							}
						}
					},
					{ text: 'Hazard', dataIndex: 'HAZARD', width: 80,
						editor: {
							xtype: 'combo',
							displayField: 'NAME',
							valueField: 'ID',
							queryMode: 'local',
							editable: false,
							store: hazard_list_store
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
										url: '<?=controller_?>yard_planning/get_dataUnno/'+newValue,
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
										url: '<?=controller_?>yard_planning/data_imdg_list/',
										reader: {
											type: 'json'
										}
									});
									imdg_list_store.load();
								}
							}
						}
					},

					/*
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
											url: '<?=controller_?>yard_planning/get_dataUnno/'+imdg_id,
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
					*/

					/*{ text: 'UNNO', dataIndex: 'UNNO', width: 80,
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
                            listConfig: {
                                emptyText: '<span style="padding:5px;text-align:center;font-style: italic;">' +
                                    'No Data Found' + 
                                '</span>'
                            }
						}
					},*/

					{ text: 'Height', dataIndex: 'CONT_HEIGHT', width: 80,
						editor: {
							xtype: 'combo',
							displayField: 'NAME',
							valueField: 'CONT_HEIGHT',
							queryMode: 'local',
							editable: false,
							store: cont_height_list_store
						}
					},

					{ text: 'Status', dataIndex: 'CONT_STATUS', width: 80,
						editor: {
							xtype: 'combo',
							displayField: 'NAME',
							valueField: 'CONT_STATUS',
							queryMode: 'local',
							editable: false,
							store: cont_status_list_store,
							allowBlank: false
						}
					}

					
					/*
					,{ text: 'Ocean Going/Intersuler', dataIndex: 'O_I', width: 180, 
						editor: {
							xtype: 'combo',
							displayField: 'NAME',
							valueField: 'ID',
							queryMode: 'local',
							editable: false,
							store: voyage_type_list_store
						}
					}
					*/
				]
			}],
			buttons: [
			<?php if ($edit_mode) { ?>
			<?php
			}else{
			?>
			{
				text: 'Plan',
				formBind: true,
				handler: function() {
					if (this.up('form').getForm().isValid()){
						var category_id = this.up('form').getForm().findField("category_<?=$tab_id?>").getValue();
						PlanYard_<?=$tab_id?>(category_id);
						win.close();
					}
				}
			}
			<?php } ?>,{
				text: 'Cancel',
				handler: function() {
					win.close();
				}
			}]
		})]
	});
	win.show();
	
	
});
<?php
if ($id_category != ''){
?>    
    $(document).ready(function(){
		var combo_<?=$tab_id?> = Ext.getCmp('category_<?=$tab_id?>');
		var toselect = "<?=$id_category?>";
		
//		combo_<?=$tab_id?>.setValue(toselect);
//		Ext.getCmp('category_<?=$tab_id?>').setValue(toselect);
		
		console.log('masuk doc ready');
		console.log(toselect);
		var store = combo_<?=$tab_id?>.store;
		console.log(store);
		var valueField = combo_<?=$tab_id?>.valueField;
		var displayField = combo_<?=$tab_id?>.displayField;
		console.log(valueField);
		combo_<?=$tab_id?>.getStore().on(
		    "load",function() {
			
			/* Set your value to select in combobox */
			/* This will select with id = 2 and label = 'Second Item'. */
			if(toselect == 0) { /* Default selection */
			    var recordSelected = combo_<?=$tab_id?>.getStore().getAt(0);
			    combo_<?=$tab_id?>.setValue(recordSelected.get(displayField));
			} else {
			    combo_<?=$tab_id?>.setValue(toselect);
			}
		    },
		    this,
		    {
			single: true
		    }
		); 
		
		combo_<?=$tab_id?>.fireEvent('select', combo_<?=$tab_id?>, [{data: {ID_CATEGORY:'<?=$id_category?>'}}]);
//		return recordNumber;
    });
<?php		
	}
?>
$(document).ready(function(){
	if('<?=$dtbl?>'=='0'){
		Ext.getCmp('deletecategory_<?=$tab_id?>').hide()
	}
	if('<?=$rtbl?>'=='0'){
		Ext.getCmp('renamecategory_<?=$tab_id?>').hide()
	}
});
</script>
<input type="hidden" id="slot_from_category"/>
<input type="hidden" id="slot_to_category"/>