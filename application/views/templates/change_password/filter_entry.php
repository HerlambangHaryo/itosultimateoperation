<script type="text/javascript">
Ext.onReady(function(){
	
	Ext.create('Ext.form.Panel', {
		id: "change_password_form_<?=$tab_id?>",
		url: '<?=controller_?>change_password/update_password?tab_id=<?=$tab_id?>',
		bodyPadding: 20,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 200,
			anchor: '70%'
		},
		items: [{
					xtype: 'textfield',
					inputType: 'password',
					id: 'oldpassword_<?=$tab_id?>',
					name: 'OLD_PASSWORD',
					fieldLabel: 'Old Password',
					allowBlank: false
				}, {
					xtype: 'textfield',
					inputType: 'password',
					id: 'newpassword_<?=$tab_id?>',
					name: 'NEW_PASSWORD',
					fieldLabel: 'New Password',
					allowBlank: false
				}, {
					xtype: 'textfield',
					inputType: 'password',
					id: 'cnewpassword_<?=$tab_id?>',
					name: 'CNEW_PASSWORD',
					fieldLabel: 'New Password (Confirm)',
					allowBlank: false
				}],
		buttons: [{
			text: 'Update',
			formBind: true,
			handler: function() {
						var form = this.up('form').getForm();
						if (form.isValid()){
							loadmask.show();
							form.submit({
								success: function(form, action) {
									loadmask.hide();
									Ext.Msg.alert('Success', 'update success');
									Ext.getCmp('<?=$tab_id?>').close();
									// console.log(Ext.getCmp('<?=$tab_id?>'));
								},
								failure: function(form, action) {
									loadmask.hide();
									Ext.Msg.alert('Failed', action.result.errors);
								}
							});
						}
					}
		},{
				text: 'Cancel',
				handler: function() {
					Ext.MessageBox.alert('Status', 'Cancel.');
					Ext.getCmp('<?=$tab_id?>').close();
				}
		  }]
	}).render('change_password_<?=$tab_id?>');
	
	// console.log(JSON.parse('<?=$vessel_detail?>'));
	//Ext.getCmp('change_password_form_<?=$tab_id?>').getForm().setValues(JSON.parse('<?=$vessel_detail?>'));
});

</script>
<div id="change_password_<?=$tab_id?>"></div>