<script type="text/javascript">
	Ext.create('Ext.form.Panel', {
		id: "actual_berthing_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 150
		},
		url: '<?=controller_?>actual_berthing_time/save_actual_berthing_time',
		tbar: ['->',
		{
				xtype: 'button',
				text: 'Departure Vessel Voyage',
				handler: function(){
					var form = this.up('form').getForm();
					var atbd = form.findField("ATB_DATE").getValue();
					var atbh = form.findField("ATB_HOUR").getValue();
					var atbm = form.findField("ATB_MIN").getValue();
					var atdd = form.findField("ATD_DATE").getValue();
					var atdh = form.findField("ATD_HOUR").getValue();
					var atdm = form.findField("ATD_MIN").getValue();
					// Ext.MessageBox.show({
					// 	title: 'Success',
					// 	msg: atdd + ', ' + atdh + ':' + atdm,
					// 	buttons: Ext.MessageBox.OK
					// });
					if (atbd != null && atbh != '' && atbm != '' && atdd != null && atdh != '' && atdm != ''){
					Ext.MessageBox.confirm('Confirm', 'Are you sure you want to do that?', function(confirmation){
						if (confirmation=='yes'){
							loadmask.show();
							Ext.Ajax.request({
								url: '<?=controller_?>actual_berthing_time/departure_vessel_voyage/' + '<?=$id_ves_voyage?>',
								success: function(response){
									var text = response.responseText;
									if (text=='1'){
										Ext.MessageBox.show({
											title: 'Success',
											msg: 'Vessel voyage departed.',
											buttons: Ext.MessageBox.OK
										});
										Ext.getCmp('<?=$tab_id?>').close();
										vessel_schedule_store.reload();
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
						}
					});
				}else{
				    Ext.MessageBox.show({
					    title: 'Error',
					    msg: 'ATB and ATD must be filled.',
					    buttons: Ext.MessageBox.OK
				    });
				}
			}
		}/*,
		{
			xtype: 'button',
			text: 'Clear Date',
			handler: function(){
				Ext.getCmp('ata_date_<?=$tab_id?>').setValue('');
				Ext.getCmp('ata_hour_<?=$tab_id?>').setValue('');
				Ext.getCmp('ata_min_<?=$tab_id?>').setValue('');

				Ext.getCmp('atb_date_<?=$tab_id?>').setValue('');
				Ext.getCmp('atb_hour_<?=$tab_id?>').setValue('');
				Ext.getCmp('atb_min_<?=$tab_id?>').setValue('');
			}
		}*/
		],
		items: [{
			xtype: 'hiddenfield',
			name: 'ID_VES_VOYAGE'
		},{
			xtype: 'fieldset',
			title: 'Vessel Details',
			items: [{
				id: "vessel_<?=$tab_id?>",
				xtype: 'displayfield',
				name: "VESSEL",
				fieldLabel: 'Vessel'
			},{
				id: "voy_in_<?=$tab_id?>",
				xtype: 'displayfield',
				name: "VOY_IN",
				fieldLabel: 'Voyage In'
			},{
				id: "voy_out_<?=$tab_id?>",
				xtype: 'displayfield',
				name: "VOY_OUT",
				fieldLabel: 'Voyage Out'
			}]
		},{
			xtype: 'fieldset',
			title: 'Actual Berthing Time',
			//ATA
			items: [{
				xtype: 'fieldcontainer',
				fieldLabel: 'Arrival (ATA)',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [{
					id: "ata_date_<?=$tab_id?>",
					xtype: 'datefield',
					name: "ATA_DATE",
					fieldLabel: 'Arrival Date',
					emptyText: 'Pick Date',
					format: 'd-m-Y',
					width: 120,
					editable: false,
					allowBlank: false
				},{
					id: "ata_hour_<?=$tab_id?>",
					xtype: 'textfield',
					name: "ATA_HOUR",
					fieldLabel: 'Arrival Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23',
					allowBlank: false
				},{
					id: "ata_min_<?=$tab_id?>",
					xtype: 'textfield',
					name: "ATA_MIN",
					fieldLabel: 'Arrival Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59',
					allowBlank: false
				},
				{
					xtype: 'button',
					text: 'Clear',
					handler: function(){
						Ext.getCmp('ata_date_<?=$tab_id?>').setValue('');
						Ext.getCmp('ata_hour_<?=$tab_id?>').setValue('');
						Ext.getCmp('ata_min_<?=$tab_id?>').setValue('');
					}
				}]
			},{ //ATB
				xtype: 'fieldcontainer',
				fieldLabel: 'Berth (ATB)',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [{
					id: "atb_date_<?=$tab_id?>",
					xtype: 'datefield',
					name: "ATB_DATE",
					fieldLabel: 'Berth Date',
					emptyText: 'Pick Date',
					format: 'd-m-Y',
					width: 120,
					editable: false,
//					allowBlank: false
				},{
					id: "atb_hour_<?=$tab_id?>",
					xtype: 'textfield',
					name: "ATB_HOUR",
					fieldLabel: 'Berth Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23',
//					allowBlank: false
				},{
					id: "atb_min_<?=$tab_id?>",
					xtype: 'textfield',
					name: "ATB_MIN",
					fieldLabel: 'Berth Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59',
//					allowBlank: false
				},
				{
					xtype: 'button',
					text: 'Clear',
					handler: function(){
						Ext.getCmp('atb_date_<?=$tab_id?>').setValue('');
						Ext.getCmp('atb_hour_<?=$tab_id?>').setValue('');
						Ext.getCmp('atb_min_<?=$tab_id?>').setValue('');
					}
				}]
			},{ //ATD
				xtype: 'fieldcontainer',
				fieldLabel: 'Departure (ATD)',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [{
					id: "atd_date_<?=$tab_id?>",
					xtype: 'datefield',
					name: "ATD_DATE",
					fieldLabel: 'Departure Date',
					emptyText: 'Pick Date',
					format: 'd-m-Y',
					width: 120,
					editable: false,
//					allowBlank: false
				},{
					id: "atd_hour_<?=$tab_id?>",
					xtype: 'textfield',
					name: "ATD_HOUR",
					fieldLabel: 'Departure Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23',
//					allowBlank: false
				},{
					id: "atd_min_<?=$tab_id?>",
					xtype: 'textfield',
					name: "ATD_MIN",
					fieldLabel: 'Departure Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59',
//					allowBlank: false
				},
				{
					xtype: 'button',
					text: 'Clear',
					handler: function(){
						Ext.getCmp('atd_date_<?=$tab_id?>').setValue('');
						Ext.getCmp('atd_hour_<?=$tab_id?>').setValue('');
						Ext.getCmp('atd_min_<?=$tab_id?>').setValue('');
					}
				}]
			}]
		},{
			xtype: 'fieldset',
			title: 'Working List',

			items:[{ //Loading Commence
				xtype: 'fieldcontainer',
				fieldLabel: 'Loading Commence',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [{
					id: "lcommence_date_<?=$tab_id?>",
					xtype: 'datefield',
					name: "lcommence_DATE",
					fieldLabel: 'Loading Commence Date',
					emptyText: 'Pick Date',
					format: 'd-m-Y',
					width: 120,
					editable: false,
					
				},{
					id: "lcommence_hour_<?=$tab_id?>",
					xtype: 'textfield',
					name: "lcommence_HOUR",
					fieldLabel: 'lcommence Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23',
					
				},{
					id: "lcommence_min_<?=$tab_id?>",
					xtype: 'textfield',
					name: "lcommence_MIN",
					fieldLabel: 'lcommence Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59',
					
				}]
			},{ //Loading Complete
				xtype: 'fieldcontainer',
				fieldLabel: 'Loading Complete',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [{
					id: "lcomplete_date_<?=$tab_id?>",
					xtype: 'datefield',
					name: "lcomplete_DATE",
					fieldLabel: 'Loading Complete Date',
					emptyText: 'Pick Date',
					format: 'd-m-Y',
					width: 120,
					editable: false
					
				},{
					id: "lcomplete_hour_<?=$tab_id?>",
					xtype: 'textfield',
					name: "lcomplete_HOUR",
					fieldLabel: 'Loading Complete Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23'
					
				},{
					id: "lcomplete_min_<?=$tab_id?>",
					xtype: 'textfield',
					name: "lcomplete_MIN",
					fieldLabel: 'Loading Complete Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59'
					
				}]
			},{ //Discharge Commence
				xtype: 'fieldcontainer',
				fieldLabel: 'Discharge Commence',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [{
					id: "dcommence_date_<?=$tab_id?>",
					xtype: 'datefield',
					name: "dcommence_DATE",
					fieldLabel: 'Discharge Commence Date',
					emptyText: 'Pick Date',
					format: 'd-m-Y',
					width: 120,
					editable: false
				},{
					id: "dcommence_hour_<?=$tab_id?>",
					xtype: 'textfield',
					name: "dcommence_HOUR",
					fieldLabel: 'Discharge Commence Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23'
				},{
					id: "dcommence_min_<?=$tab_id?>",
					xtype: 'textfield',
					name: "dcommence_MIN",
					fieldLabel: 'Discharge Commence Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59'
				}]
			},{ //Discharge Complete
				xtype: 'fieldcontainer',
				fieldLabel: 'Discharge Complete',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [{
					id: "dcomplete_date_<?=$tab_id?>",
					xtype: 'datefield',
					name: "dcomplete_DATE",
					fieldLabel: 'Discharge Complete Date',
					emptyText: 'Pick Date',
					format: 'd-m-Y',
					width: 120,
					editable: false
				},{
					id: "dcomplete_hour_<?=$tab_id?>",
					xtype: 'textfield',
					name: "dcomplete_HOUR",
					fieldLabel: 'Discharge Complete Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23'
				},{
					id: "dcomplete_min_<?=$tab_id?>",
					xtype: 'textfield',
					name: "dcomplete_MIN",
					fieldLabel: 'Discharge Complete Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59'
				}]
			}]
		}],
		buttons: [{
			text: 'Save',
			formBind: true,
			handler: function() {
				var form = this.up('form').getForm();
				if (form.isValid()){
					loadmask.show();
					form.submit({
						success: function(form, action) {
							loadmask.hide();
							Ext.Msg.alert('Success', 'Update success');
						},
						failure: function(form, action) {
							loadmask.hide();
							Ext.Msg.alert('Failed', action.result.errors);
						}
					});
				}
			}
		}]
	}).render('actual_berthing_<?=$tab_id?>');
	
	$(document).ready(function(){
		var id_ves_voyage = '<?=$id_ves_voyage?>';
		if (id_ves_voyage!=''){
			Ext.getCmp('actual_berthing_form_<?=$tab_id?>').getForm().setValues(JSON.parse('<?=$ves_voyage?>'));
		}
	});
</script>
<div id="actual_berthing_<?=$tab_id?>"></div>