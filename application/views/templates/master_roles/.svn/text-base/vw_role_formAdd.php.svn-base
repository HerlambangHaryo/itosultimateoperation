<script type="text/javascript">
	
$(function() {
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Add Role',
		closable: false,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			height: 100,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: 'role_<?=$tab_id?>',
				fieldLabel: 'Role',
				xtype: 'textfield',
				allowBlank: false,
				titleError:'5 Characters',
				name: 'GROUP_NAME'
			}],
			buttons: [{
				text: 'Save',
				formBind: true,
				handler: function() {
					var form = this.up('form').getForm();
					if (form.isValid()){
						loadmask.show();
						Ext.Ajax.request({
							url: '<?=controller_?>roles/check_group',
							params: {
								group_name: form.findField("GROUP_NAME").getValue()
							},
							success: function(response){
								var text = response.responseText;
								console.log('text : ' + text);
								if (text=='0'){
									Ext.Ajax.request({
									    url: '<?=controller_?>roles/save_group',
									    method: 'POST',
									    params: form.getValues(),
									    scope: this,
									    success: function(result, response) {
										loadmask.hide();
										var res = JSON.parse(result.responseText);
										var status = res.IsSuccess ? 'Success' : 'Failed';

										Ext.Msg.alert(status, res.Message);
										if(res.IsSuccess){
										    win.close();
										    role_store.reload();
										}
									    },
									    failure:function(form, response) {
										Ext.Msg.alert('Failed: ', response.errorMessage);
									    }
									})
								}else{
									loadmask.hide();
									Ext.MessageBox.show({
										title: 'Error',
										msg: 'Role ' + form.findField("GROUP_NAME").getValue() + ' Already Exist.',
										buttons: Ext.MessageBox.OK
									});
								}
							}
						});
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
	
	var backupKeyRoleName = '';
	var backupChangeKeyRoleName = '';
	$('#role_<?=$tab_id?>-inputEl').bind('keyup', function(e){
		if ((e.which >= 65 && e.which <= 90) || e.which == 8 || e.which == 32 || e.which == 44 || e.which == 57 || e.which == 48|| e.which == 190) {
			$(this).val($(this).val().toUpperCase());
			backupKeyRoleName = $(this).val();
		}else{
			$(this).val(backupKeyRoleName);
		}
		if ($(this).val().length > 50){
			$(this).val($(this).val().substr(0,50));
		}
	});	
	$('#role_<?=$tab_id?>-inputEl').bind('change', function(e){
		$(this).val($.trim($(this).val()));
		if($(this).val().match(/^[a-zA-Z/ /(/)/.]*$/)){
			backupChangeKeyRoleName = $(this).val();
		}else{
			Ext.MessageBox.alert('Error', 'have a illegal character.');
			$(this).val(backupChangeKeyRoleName);
		}
		if ($(this).val().length > 50){
			$(this).val($(this).val().substr(0,50));
		}
		backupKeyRoleName = $(this).val();
	});
});
</script>