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
	
	Ext.create('Ext.form.Panel', {
		id: "container_data_form_<?=$tab_id?>",
		bodyPadding: 5,
		width: 700,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		items: [{
			xtype: 'fieldset',
			title: 'Common',
			items: [{
				xtype: 'displayfield',
				fieldLabel: 'Class',
				name: 'CLASS_CODE_NAME'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Full/Empty',
				name: 'CONT_STATUS_NAME'
			},{
				xtype: 'displayfield',
				fieldLabel: 'ISO',
				name: 'ID_ISO_CODE'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Size',
				name: 'CONT_SIZE_NAME'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Height',
				name: 'CONT_HEIGHT_NAME'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Operator',
				name: 'CONT_OPERATOR_NAME'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Commodity',
				name: 'COMMODITY_NAME'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Type',
				name: 'CONT_TYPE_NAME'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Weight (Kg)',
				name: 'WEIGHT'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Temp. (C)',
				name: 'TEMP'
			},{
				xtype: 'displayfield',
				fieldLabel: 'UNNO',
				name: 'UNNO'
			},{
				xtype: 'displayfield',
				fieldLabel: 'IMDG',
				name: 'IMDG'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Seal Number',
				name: 'SEAL_NUMB'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Truck Loosing',
				name: 'TL_FLAG'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Damage',
				name: 'DAMAGE'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Damage Location',
				name: 'DAMAGE_LOCATION'
			},{
				xtype: 'displayfield',
				fieldLabel: 'ESY',
				name: 'ITT_FLAG'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Kode Lapangan',
				name: 'KD_LAPANGAN'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Hold Container',
				name: 'HOLD_CONTAINER'
			}]
		},{
			xtype: 'fieldset',
			title: 'Vessel/Voyage',
			items: [{
				xtype: 'displayfield',
				fieldLabel: 'Vessel',
				name: 'VESSEL'
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
				fieldLabel: 'Stowage (Plan)',
				name: 'STOWAGE_PLAN'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Stowage (Real)',
				name: 'STOWAGE'
			}]
		},{
			xtype: 'fieldset',
			title: 'Port',
			items: [{
				xtype: 'displayfield',
				fieldLabel: 'POL',
				name: 'POL_NAME'
			},{
				xtype: 'displayfield',
				fieldLabel: 'POD',
				name: 'POD_NAME'
			},{
				xtype: 'displayfield',
				fieldLabel: 'FPOD',
				name: 'POR_NAME'
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
				fieldLabel: 'Status Code',
				name: 'ID_OP_STATUS'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Status',
				name: 'OP_STATUS_DESC'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Location',
				name: 'CONT_LOCATION'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Yard Position',
				name: 'YARD_POS'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Gate Out',
				name: 'Gate_out_status'
			}]
		},{
			xtype: 'fieldset',
			title: 'OVD',
			items: [{
				xtype: 'displayfield',
				fieldLabel: 'Over Height',
				name: 'OVER_HEIGHT'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Over Right',
				name: 'OVER_RIGHT'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Over Left',
				name: 'OVER_LEFT'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Over Front',
				name: 'OVER_FRONT'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Over Rear',
				name: 'OVER_REAR'
			},{
				xtype: 'displayfield',
				fieldLabel: 'Over Width',
				name: 'OVER_WIDTH'
			}]
		},{
			id: "no_container_<?=$tab_id?>",
			xtype: "hidden",
			name: "NO_CONTAINER"
		},{
			id: "point_<?=$tab_id?>",
			xtype: "hidden",
			name: "POINT"
		}],
		tbar: [{
			xtype: 'button',
			text: 'History Status',
			handler: function (){
				Ext.Ajax.request({
					url: '<?=controller_?>container_inquiry/popup_history_status?tab_id=<?=$tab_id?>',
					params: {
						no_container: Ext.getCmp("no_container_<?=$tab_id?>").getRawValue(),
						point: Ext.getCmp("point_<?=$tab_id?>").getRawValue()
					},
					callback: function(opt,success,response){
						$("#popup_script_<?=$tab_id?>").html(response.responseText);
					} 
				});
			}
		},
		{
			xtype: 'button',
			text: 'History Data Change',
			handler: function (){
				Ext.Ajax.request({
					url: '<?=controller_?>container_inquiry/popup_history_data_change?tab_id=<?=$tab_id?>',
					params: {
						no_container: Ext.getCmp("no_container_<?=$tab_id?>").getRawValue(),
						point: Ext.getCmp("point_<?=$tab_id?>").getRawValue()
					},
					callback: function(opt,success,response){
						$("#popup_script_<?=$tab_id?>").html(response.responseText);
					} 
				});
			}
		}],
	}).render('container_data_<?=$tab_id?>');
	
	Ext.create('Ext.form.Panel', {
		id: "container_search_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		layout: 'hbox',
		url: '<?=controller_?>container_inquiry/data_container_inquiry',
		items: [{
			id: 'container_inquiry_number_<?=$tab_id?>',
			xtype: 'textfield',
			name: "no_container",
			cls: "no_container",
			fieldLabel: 'No Container',
			// maskRe: /[\dA-Z]/,
			// regex: /^[\dA-Z]{1,}$/,
			minLength: 1,
			maxLength: 11,
			enforceMaxLength: true,
			allowBlank: false,
			value: '<?=$no_container?>',
			listeners: {
				specialkey: function(field, e){
					if (e.getKey() == e.ENTER) {
						var inquiry_button = Ext.getCmp('inquiry_button_<?=$tab_id?>');
						inquiry_button.fireEvent('click', inquiry_button);
					}
				}
			}
		},{
			id: 'container_inquiry_point_<?=$tab_id?>',
			xtype: 'combo',
			name: "point",
			displayField: 'POINT',
			valueField: 'POINT',
			queryMode: 'local',
			editable: false,
			width: 50,
			store: container_inquiry_point_<?=$tab_id?>
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
									Ext.getCmp("container_inquiry_point_<?=$tab_id?>").getStore().load({
										params: {
											no_container: Ext.getCmp("container_inquiry_number_<?=$tab_id?>").getValue()
										}
									});
									Ext.getCmp("container_inquiry_point_<?=$tab_id?>").setValue(data['POINT']);
								},
								failure: function(form, action) {
									loadmask.hide();
									Ext.Msg.alert('Failed', action.result.errors);
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
									Ext.getCmp("container_inquiry_point_<?=$tab_id?>").getStore().load({
										params: {
											no_container: Ext.getCmp("container_inquiry_number_<?=$tab_id?>").getValue()
										}
									});
									Ext.getCmp("container_inquiry_point_<?=$tab_id?>").setValue('');
								}
							});
						}
					}
				}
			}
		}]
	}).render('container_search_<?=$tab_id?>');
	Ext.get('inquiry_button_<?=$tab_id?>').dom.click();
</script>
<div id="container_search_<?=$tab_id?>"></div>
<div id="container_data_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>