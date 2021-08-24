<script>
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Configure Yard Size',
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
				id: 'width',
				fieldLabel: 'Width',
				value: 5,
				minValue: 1,
				maxValue: 500
			}, {
				xtype: 'numberfield',
				id: 'height',
				fieldLabel: 'Height',
				value: 5,
				minValue: 1,
				maxValue: 500
			}],
			buttons: [{
				text: 'Build',
				formBind: true,
				handler: function() {
					var form = this.up('form').getForm();
					if (form.isValid()){
						loadmask.show();
						Ext.getCmp('<?=$tab_id?>').getLoader().load({
							url: '<?=controller_?>yard_builder/builder_panel?tab_id=<?=$tab_id?>',
							params: {width: Ext.getCmp('width').getRawValue(), height: Ext.getCmp('height').getRawValue() },
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