<script type="text/javascript">
Ext.onReady(function(){
	var class_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_CLASS_CODE', 'CODE_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>single_correction/data_cont_class_code/',
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
			url: '<?=controller_?>single_correction/data_cont_size/',
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
			url: '<?=controller_?>single_correction/data_cont_type/',
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
	
	var pol_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_POL', 'PORT_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>single_correction/data_port/ID_POL',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var pod_list_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_POD', 'PORT_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>single_correction/data_port/ID_POD',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var por_list_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_POR', 'PORT_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>single_correction/data_port/ID_POR',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var operator_list_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
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
	
	var cont_height_list_store = Ext.create('Ext.data.Store', {
		fields:['CONT_HEIGHT', 'NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>single_correction/data_cont_height/',
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
			url: '<?=controller_?>single_correction/data_cont_commodity/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	

	//console.log('size : ');+$('#CONT_SIZE-inputEl').val()+'/'+$('#CONT_TYPE-inputEl').val()

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

	var over_store = Ext.create('Ext.data.Store', {
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
	
	var unno_list_store = Ext.create('Ext.data.Store', {
		fields:['UNNO', 'UNNO'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>single_correction/data_unno_list/',
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
			url: '<?=controller_?>single_correction/data_imdg_list/',
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
			url: '<?=controller_?>single_correction/data_vessel_schedule_autocomplete/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var single_correction_point_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['POINT'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>single_correction/list_of_point/',
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
	
	Ext.create('Ext.form.Panel', {
		id: "container_data_form_<?=$tab_id?>",
		bodyPadding: 5,
		disabled: true,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		url: '<?=controller_?>single_correction/save_single_correction',
		items: [{
			xtype: 'fieldset',
			title: 'Common',
			items: [{
				xtype: 'displayfield',
				displayField: 'CODE_NAME',
				valueField: 'ID_CLASS_CODE',
				queryMode: 'local',
				editable: false,
				store: class_list_store,
				allowBlank: false,
				fieldLabel: 'Class',
				name: 'CLASS_CODE_NAME'
			},{
				xtype: 'combo',
				displayField: 'NAME',
				valueField: 'CONT_STATUS',
				queryMode: 'local',
				editable: false,
				store: cont_status_list_store,
				allowBlank: false,
				fieldLabel: 'Full/Empty',
				name: 'CONT_STATUS'
			},{
				xtype: 'combo',
				displayField: 'ID_ISO_CODE',
				valueField: 'ID_ISO_CODE',
				store: iso_code_store,
				queryMode: 'remote',
				forceSelection: true,
				hideTrigger: true,
				editable: true,
				triggerAction: 'query',
				emptyText: 'Autocomplete',
				typeAhead: true,
				minChars: 1,
				fieldLabel: 'ISO',
				name: 'ID_ISO_CODE'
			},{
				xtype: 'combo',
				displayField: 'NAME',
				valueField: 'CONT_SIZE',
				queryMode: 'local',
				editable: false,
				store: cont_size_list_store,
				allowBlank: false,
				fieldLabel: 'Size',
				name: 'CONT_SIZE',
				id:'CONT_SIZE'
			},{
				xtype: 'combo',
				displayField: 'NAME',
				valueField: 'CONT_HEIGHT',
				queryMode: 'local',
				editable: false,
				store: cont_height_list_store,
				allowBlank: false,
				fieldLabel: 'Height',
				name: 'CONT_HEIGHT'
			},{
				id: 'operator_list_store_<?=$tab_id?>',
				xtype: 'combo',
				displayField: 'OPERATOR_NAME',
				valueField: 'ID_OPERATOR',
				store: operator_list_store_<?=$tab_id?>,
				queryMode: 'remote',
				forceSelection: true,
				hideTrigger: true,
				triggerAction: 'query',
				emptyText: 'Autocomplete',
				typeAhead: true,
				minChars: 3,
				fieldLabel: 'Operator',
				name: 'ID_OPERATOR'
			},{
				xtype: 'combo',
				displayField: 'COMMODITY_NAME',
				valueField: 'ID_COMMODITY',
				queryMode: 'local',
				editable: false,
				store: commodity_list_store,
				fieldLabel: 'Commodity',
				name: 'ID_COMMODITY'
			},{
				xtype: 'combo',
				displayField: 'NAME',
				valueField: 'CONT_TYPE',
				queryMode: 'local',
				editable: false,
				store: cont_type_list_store,
				allowBlank: false,
				fieldLabel: 'Type',
				name: 'CONT_TYPE',
				id:'CONT_TYPE'
			},{
				xtype: 'numberfield',
				decimalPrecision: 2,
				fieldLabel: 'Weight (Kg)',
				name: 'WEIGHT'
			},{
				xtype: 'numberfield',
				decimalPrecision: 2,
				fieldLabel: 'Temp. (C)',
				name: 'TEMP'
			},{
				xtype: 'combo',
				displayField: 'UNNO',
				valueField: 'UNNO',
				store: unno_list_store,
				queryMode: 'remote',
				forceSelection: true,
				hideTrigger: true,
				editable: true,
				triggerAction: 'query',
				emptyText: 'Autocomplete',
				typeAhead: true,
				minChars: 1,
				fieldLabel: 'UNNO',
				name: 'UNNO'
			},{
				xtype: 'combo',
				displayField: 'IMDG',
				valueField: 'IMDG',
				store: imdg_list_store,
				queryMode: 'remote',
				forceSelection: true,
				hideTrigger: true,
				editable: true,
				triggerAction: 'query',
				emptyText: 'Autocomplete',
				typeAhead: true,
				minChars: 1,
				fieldLabel: 'IMDG',
				name: 'IMDG'
			},{
				xtype: 'textfield',
				fieldLabel: 'Seal Number',
				name: 'SEAL_NUMB'
			},{
				xtype: 'combo',
				displayField: 'TL_FLAG',
				valueField: 'ID',
				queryMode: 'local',
				editable: false,
				store: truck_loosing_store,
				allowBlank: false,
				fieldLabel: 'Truck Loosing',
				name: 'TL_FLAG'
			}]
		},{
			xtype: 'fieldset',
			title: 'Vessel/Voyage',
			items: [{
				id: 'id_ves_voyage_<?=$tab_id?>',
				xtype: 'combo',
				displayField: 'VESSEL',
				valueField: 'ID_VES_VOYAGE',
				store: vessel_schedule_list_store,
				queryMode: 'remote',
				forceSelection: true,
				hideTrigger: true,
				editable: false,
				triggerAction: 'query',
				emptyText: 'Autocomplete',
				typeAhead: true,
				minChars: 3,
				fieldLabel: 'Vessel',
				name: 'ID_VES_VOYAGE'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Visit',
				name: 'VISIT'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Voyage',
				name: 'VOYAGE'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Stowage',
				name: 'STOWAGE'
			}]
		},{
			xtype: 'fieldset',
			title: 'Over Dimension (Unit:cm)',
			items: [{
				xtype: 'numberfield',
				decimalPrecision: 2,
				fieldLabel: 'Height',
				name: 'OVER_HEIGHT'
			},{
				xtype: 'numberfield',
				decimalPrecision: 2,
				fieldLabel: 'Right',
				name: 'OVER_RIGHT'
			},{
				xtype: 'numberfield',
				decimalPrecision: 2,
				fieldLabel: 'Left',
				name: 'OVER_LEFT'
			},{
				xtype: 'numberfield',
				decimalPrecision: 2,
				fieldLabel: 'Front',
				name: 'OVER_FRONT'
			},{
				xtype: 'numberfield',
				decimalPrecision: 2,
				fieldLabel: 'Back',
				name: 'OVER_REAR'
			}]
		},{
			xtype: 'fieldset',
			title: 'Port',
			items: [{
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
				fieldLabel: 'POL',
				name: 'ID_POL'
			},{
				id: 'pod_list_store_<?=$tab_id?>',
				xtype: 'combo',
				displayField: 'PORT_NAME',
				valueField: 'ID_POD',
				store: pod_list_store_<?=$tab_id?>,
				queryMode: 'remote',
				forceSelection: true,
				hideTrigger: true,
				triggerAction: 'query',
				emptyText: 'Autocomplete',
				typeAhead: true,
				minChars: 2,
				fieldLabel: 'POD',
				name: 'ID_POD'
			},{
				id: 'por_list_store_<?=$tab_id?>',
				xtype: 'combo',
				displayField: 'PORT_NAME',
				valueField: 'ID_POR',
				store: por_list_store_<?=$tab_id?>,
				queryMode: 'remote',
				hideTrigger: true,
				triggerAction: 'query',
				emptyText: 'Autocomplete',
				typeAhead: true,
				minChars: 2,
				fieldLabel: 'FPOD',
				name: 'ID_POR'
			}]
		},{
			xtype: 'fieldset',
			title: 'Billing',
			items: [{
				xtype: 'displayfield',
				fieldLabel: 'Payment',
				name: 'PAYMENT'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Paid Thru',
				name: 'PAYTHROUGH_DATE'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Transaction No.',
				name: 'TRX_NUMBER'
			}]
		},{
			xtype: 'fieldset',
			title: 'Status',
			items: [{
				xtype: 'displayfield',
				fieldLabel: 'Status',
				name: 'ID_OP_STATUS'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Status Code',
				name: 'OP_STATUS_DESC'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Location',
				name: 'CONT_LOCATION'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Yard Position',
				name: 'YARD_POS'
			}]
		},{
			id: "no_container_<?=$tab_id?>",
			xtype: "hidden",
			name: "NO_CONTAINER"
		},{
			id: "point_<?=$tab_id?>",
			xtype: "hidden",
			name: "POINT"
		},{
			xtype: "hidden",
			name: "ID_CLASS_CODE"
		},{
			xtype: "hidden",
			name: "ID_POD_VAL"
		},{
			xtype: "hidden",
			name: "ID_POL_VAL"
		},{
			xtype: "hidden",
			name: "ID_FPOD_VAL"
		},{
			xtype: "hidden",
			name: "ID_VES_VOYAGE_VAL"
		}],
		buttons: [{
			text: 'Save',
			formBind: true,
			listeners: {
				click: {
					fn: function () {
						var form = this.up('form').getForm();
						if (form.isValid()){
							loadmask.show();
							form.submit({
								success: function(form, action) {
									loadmask.hide();
									Ext.Msg.alert('Success', 'Changes saved successfully');
									Ext.getCmp("<?=$tab_id?>").close();
								},
								failure: function(form, action) {
									loadmask.hide();
									Ext.Msg.alert('Failed', action.result.errors);
								}
							});
						}
					}
				}
			}
		}]
	}).render('container_data_<?=$tab_id?>');
	
	Ext.create('Ext.form.Panel', {
		id: "container_search_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		layout: 'hbox',
		url: '<?=controller_?>single_correction/data_single_correction',
		items: [{
			id: 'single_correction_number_<?=$tab_id?>',
			xtype: 'textfield',
			name: "no_container",
			fieldLabel: 'No Container',
			maskRe: /[\dA-Z]/,
			regex: /[\dA-Z]{1,}$/,
			minLength: 1,
			maxLength: 11,
			enforceMaxLength: true,
			allowBlank: false,
			listeners: {
				specialkey: function(field, e){
					if (e.getKey() == e.ENTER) {
						var inquiry_button = Ext.getCmp('inquiry_button_<?=$tab_id?>');
						inquiry_button.fireEvent('click', inquiry_button);
					}
				}
			}
		},{
			id: 'single_correction_point_<?=$tab_id?>',
			xtype: 'combo',
			name: "point",
			displayField: 'POINT',
			valueField: 'POINT',
			queryMode: 'local',
			editable: false,
			width: 50,
			store: single_correction_point_<?=$tab_id?>
		}],
		buttons: [{
			text: 'Search',
			id: 'inquiry_button_<?=$tab_id?>',
			formBind: true,
			listeners: {
				click: {
					fn: function () {
						var form = this.up('form').getForm();
						if (form.isValid()){
							loadmask.show();
							Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_VES_VOYAGE').disable();
							Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_POL').disable();
							Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_POD').disable();
							Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_POR').disable();
//							Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('TL_FLAG').enable();
							// Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_ISO_CODE').disable();
							form.submit({
								success: function(form, action) {
									loadmask.hide();
									var data = JSON.parse(action.result.data);
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
									Ext.getCmp('container_data_form_<?=$tab_id?>').setDisabled(false);
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().setValues(data);
									Ext.getCmp("single_correction_point_<?=$tab_id?>").getStore().load({
										params: {
											no_container: Ext.getCmp("single_correction_number_<?=$tab_id?>").getValue()
										}
									});
									Ext.getCmp("single_correction_point_<?=$tab_id?>").setValue(data['POINT']);
									var class_code = Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_CLASS_CODE').getValue();
									var op_status = Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_OP_STATUS').getValue();
									// Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_VES_VOYAGE').disable();
									if (op_status!='BPL'){
										Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('TL_FLAG').disable();
									}
//									if (class_code=='I' || class_code=='TI' || class_code=='TC' || class_code=='TE'){
//										Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_POD').disable();
//										Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_POL').disable();
//										Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_POR').disable();
//									}else 
									if (class_code=='E' && Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_OP_STATUS').getValue()!='SLY'){
										console.log('id_vesvoy : ' + Ext.getCmp("id_ves_voyage_<?=$tab_id?>").getValue());
//									    	Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_POL').disable();
										Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_POD').enable();
										pod_list_store_<?=$tab_id?>.getProxy().extraParams = {
											id_ves_voyage: Ext.getCmp("id_ves_voyage_<?=$tab_id?>").getValue()
										};
										Ext.getCmp("pod_list_store_<?=$tab_id?>").getStore().reload();
										Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_POR').enable();
										por_list_store_<?=$tab_id?>.getProxy().extraParams = {
											id_ves_voyage: Ext.getCmp("id_ves_voyage_<?=$tab_id?>").getValue()
										};
										Ext.getCmp("por_list_store_<?=$tab_id?>").getStore().reload();
										operator_list_store_<?=$tab_id?>.getProxy().extraParams = {
											id_ves_voyage: Ext.getCmp("id_ves_voyage_<?=$tab_id?>").getValue()
										};
										Ext.getCmp('operator_list_store_<?=$tab_id?>').getStore().reload();
										
										// Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('WEIGHT').disable();
										// Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('SEAL_NUMB').disable();
										
											// Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().findField('ID_VES_VOYAGE').enable();
									    
									}
								},
								failure: function(form, action) {
									loadmask.hide();
									Ext.Msg.alert('Failed', action.result.errors);
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
									Ext.getCmp('container_data_form_<?=$tab_id?>').setDisabled(true);
									Ext.getCmp("single_correction_point_<?=$tab_id?>").getStore().load({
										params: {
											no_container: Ext.getCmp("single_correction_number_<?=$tab_id?>").getValue()
										}
									});
									Ext.getCmp("single_correction_point_<?=$tab_id?>").setValue('');
								}
							});
						}
					}
				}
			}
		}]
	}).render('container_search_<?=$tab_id?>');
});
</script>
<div id="container_search_<?=$tab_id?>"></div>
<div id="container_data_<?=$tab_id?>"></div>