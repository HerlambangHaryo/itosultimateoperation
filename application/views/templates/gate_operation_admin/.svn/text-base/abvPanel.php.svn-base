<script type="text/javascript">
	var container_inquiry_point_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['POINT'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>container_inquiry/list_of_point/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var damageCont<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_DAMAGE', 'DAMAGE'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>gate_operation_admin/data_dmg/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var damageContLocation<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_DAMAGE_LOCATION', 'DAMAGE_LOCATION'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>gate_operation_admin/data_dmgLoc/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var inOutGate = Ext.create('Ext.data.Store', {
		fields: ['id', 'name'],
		data: [{
			"id": "IN",
			"name": "IN"
		}, {
			"id": "OUT",
			"name": "OUT"
		}]
	});
	
	var recDelGate = Ext.create('Ext.data.Store', {
		fields: ['id', 'name'],
		data: [{
			"id": "REC",
			"name": "Receiving"
		}, {
			"id": "DEL",
			"name": "Delivery"
		}]
	});
	
	Ext.create('Ext.form.Panel', {
		id: "container_data_form_<?=$tab_id?>",
		url: '<?=controller_?>gate_operation_admin/saveGate',
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		items: [{
			xtype: 'fieldset',
			title: 'Container Spec',
			items: [{
				xtype: 'field',
				fieldLabel: 'No Container',
				name: 'NO_CONTAINER',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'Point',
				name: 'POINT',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'ISO',
				name: 'ID_ISO_CODE',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'ID VES VOYAGE',
				name: 'ID_VES_VOYAGE',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'Vessel (Voyage)',
				name: 'VESSEL_VOYAGE',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'Class Code',
				name: 'EI',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'Truck In date',
				name: 'TRINDATE',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'Truck Out date',
				name: 'TROTDATE',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'Job Truck',
				name: 'TR_JOB',
				readOnly: true
			}
			]
			},
			{
			xtype: 'fieldset',
			title: 'Gate Content',
			items: [{
				xtype: 'hiddenfield',
				name: "VALID_DATE"
			},{
				xtype: 'fieldcontainer',
				fieldLabel: 'Gate Date',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [{
					xtype: 'datefield',
					name: "DATE_GATE",
					emptyText: 'Pick Date',
					format: 'd-m-Y',
					width: 120,
					editable: false,
					allowBlank: false
				},{
					xtype: 'textfield',
					name: "HOUR_GATE",
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23'
				},{
					xtype: 'textfield',
					name: "MIN_GATE",
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59'
				}]
			},{
				xtype: 'field',
				fieldLabel: 'Truck Number',
				name: 'TRUCK_NUMBER'
			},{
				xtype: 'field',
				fieldLabel: 'Seal Number',
				name: 'SEAL_ID'
			},{
				xtype: 'field',
				fieldLabel: 'Weight',
				name: 'WEIGHT'
			},{
				id: 'damageCont<?=$tab_id?>',
				xtype: 'combo',
				name: "damageCont",
				fieldLabel: 'Damage',
				width: 400,
				store: damageCont<?=$tab_id?>,
				queryMode: 'local',
				valueField: 'ID_DAMAGE',
				displayField: 'DAMAGE'
			  }
			  ,{
				id: 'damageContLoc<?=$tab_id?>',
				xtype: 'combo',
				name: "damageContLoc",
				fieldLabel: 'Damage Location',
				width: 400,
				store: damageContLocation<?=$tab_id?>,
				queryMode: 'local',
				valueField: 'ID_DAMAGE_LOCATION',
				displayField: 'DAMAGE_LOCATION'
			  },{
				xtype: 'textareafield',
				grow: true,
				name: 'REMARKS',
				fieldLabel: 'Remarks',
				allowBlank: false
			}
			]	
		}],buttons: [{
			text: 'Save',
			id: 'saveGateButton<?=$tab_id?>',
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
									Ext.Msg.alert('Success', 'saved successfully');
									Ext.Ajax.request({
										url: '<?=controller_?>gate_operation_admin/send_notification',
										params: {
											module_name: 'GATE_ADMIN',
											no_container: form.findField('NO_CONTAINER').getValue(),
											vessel_voyage: form.findField('VESSEL_VOYAGE').getValue(),
											valid_date: form.findField('VALID_DATE').getValue(),
											date_gate: form.findField('DATE_GATE').getRawValue(),
											remarks: form.findField('REMARKS').getValue()
										},
										callback: function(opt,success,response){
										} 
									});
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
								},
								failure: function(form, action) {
									loadmask.hide();
									//var data2 = JSON.parse(action.result.errors);
									Ext.Msg.alert('Failed', action.result.errors);
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
								}
							});
						}
					}
				}
			}
		}]
	}).render('container_data_<?=$tab_id?>');
	
	/*panel diatas*/
	Ext.create('Ext.form.Panel', {
		id: "container_search_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		
		url: '<?=controller_?>gate_operation_admin/data_container_inquiry',
		items: [{
			id: 'typeOfGate<?=$tab_id?>',
			xtype: 'combo',
			name: "typeInOut",
			fieldLabel: 'Gate',
			width: 200,
			store: inOutGate,
			queryMode: 'local',
			valueField: 'id',
			displayField: 'name'
		},{
			id: 'recDelOfGate<?=$tab_id?>',
			xtype: 'combo',
			fieldLabel: 'Rec / Del',
			name: "typeRecDel",
			store: recDelGate,
			queryMode: 'local',
			valueField: 'id',
			displayField: 'name'
		},
		{
			id: 'contInquiryGate<?=$tab_id?>',
			xtype: 'field',
			fieldLabel: 'No. Container',
			name: "cont_inquiry"
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
							form.submit({
								success: function(form, action) {
									loadmask.hide();
									var data = JSON.parse(action.result.data);
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().setValues(data);
									
								},
								failure: function(form, action) {
									loadmask.hide();
									Ext.Msg.alert('Failed', action.result.errors);
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
									
								}
							});
						}
					}
				}
			}
		}]
	}).render('container_search_<?=$tab_id?>');
</script>
<div id="container_search_<?=$tab_id?>"></div>
<div id="container_data_<?=$tab_id?>"></div>