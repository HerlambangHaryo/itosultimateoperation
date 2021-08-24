<script>

	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Configure Vessel',
		closable: false,
		items: Ext.create('Ext.form.Panel', {
			frame: true,
			autoScroll: true,
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 90,
				anchor: '100%'
			},
			items: [{
				xtype: 'numberfield',
				id: 'jmlbay',
				fieldLabel: 'Bay Count',
				value: 5,
				minValue: 1,
				maxValue: 100
			}, {
				xtype: 'numberfield',
				id: 'jmlrow',
				fieldLabel: 'Row',
				value: 5,
				minValue: 1,
				maxValue: 100
			}, {
				xtype: 'numberfield',
				id: 'jmltier_on',
				fieldLabel: 'Tier Above',
				value: 5,
				minValue: 1,
				maxValue: 10
			}, {
				xtype: 'numberfield',
				id: 'jmltier_un',
				fieldLabel: 'Tier Below',
				value: 5,
				minValue: 1,
				maxValue: 10
			}, {
				xtype: 'numberfield',
				id: 'jmlht',
				fieldLabel: 'Hatch Count',
				value: 5,
				minValue: 1,
				maxValue: 100
			}],
			buttons: [{
				text: 'Create',
				formBind: true,
				handler: function() {
					if (this.up('form').getForm().isValid()){
						loadmask.show();
						Ext.getCmp('<?=$tab_id?>').getLoader().load({
							url: '<?=controller_?>vessel_profile/create_profile?tab_id=<?=$tab_id?>&vscode=<?=$vs_code?>',
							params: {jmlbay: Ext.getCmp('jmlbay').getRawValue(), 
								     jmlrow: Ext.getCmp('jmlrow').getRawValue(), 
								     jmltier_on: Ext.getCmp('jmltier_on').getRawValue(), 
								     jmltier_un: Ext.getCmp('jmltier_un').getRawValue(),
								 	 jmlht: Ext.getCmp('jmlht').getRawValue()},
							scripts: true,
							contentType: 'html',
							autoLoad: true,
							success: function(){
								loadmask.hide();
							}
						});
						win.close();
					}
				}
			},{
				text: 'Cancel',
				handler: function() {
					win.close();
					Ext.getCmp('<?=$tab_id?>').close();
				}
			}]
		})
	});

	win.show();

</script>