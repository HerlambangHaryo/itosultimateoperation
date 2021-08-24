<script type="text/javascript">
	var container_inquiry_point_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['POINT'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>disable_container/list_of_point/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	Ext.create('Ext.form.Panel', {
		id: "container_data_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		url: '<?=controller_?>disable_container/save_disable_container',
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
			}]
		},{
			xtype: 'fieldset',
			title: 'Disabled Remarks',
			items: [{
				xtype: 'textareafield',
				grow: true,
				name: 'REMARKS',
				fieldLabel: 'Message',
				allowBlank: false
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
		buttons: [{
			text: 'Disable',
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
		url: '<?=controller_?>disable_container/data_container_inquiry',
		items: [{
			id: 'container_inquiry_number_<?=$tab_id?>',
			xtype: 'textfield',
			name: "no_container",
			fieldLabel: 'No Container',
			maskRe: /[\dA-Z]/,
			regex: /^[\dA-Z]{1,}$/,
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
</script>
<div id="container_search_<?=$tab_id?>"></div>
<div id="container_data_<?=$tab_id?>"></div>