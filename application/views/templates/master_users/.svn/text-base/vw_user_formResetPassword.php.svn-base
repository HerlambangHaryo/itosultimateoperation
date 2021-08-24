<script type="text/javascript">
	
$(function() {
    var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
    var win = new Ext.Window({
	    layout: 'fit',
	    modal: true,
	    title: 'Reset Password',
	    closable: false,
	    items: [Ext.create('Ext.form.Panel', {
		    frame: true,
		    bodyPadding: 5,
		    height: 160,
		    fieldDefaults: {
			    labelAlign: 'left',
			    labelWidth: 100
		    },
		    items: [{
				id: 'ID_USER_<?=$tab_id?>',
				xtype: 'hiddenfield',
				name: 'ID_USER',
				value: '<?=$id_user?>'
			},{
			    id: 'FULL_NAME_<?=$tab_id?>',
			    fieldLabel: 'Full Name',
			    afterLabelTextTpl: required,
			    xtype: 'textfield',
			    allowBlank: false,
			    name: 'FULL_NAME',
			    readOnly: true,
			    value: '<?=$full_name?>'
		    },{
			    id: 'USERNAME_<?=$tab_id?>',
			    fieldLabel: 'Username',
			    xtype: 'textfield',
			    allowBlank: false,
			    name: 'USERNAME',
			    readOnly: true,
			    value: '<?=$username?>'
		    },{
			    id: 'PASSWORD_<?=$tab_id?>',
			    fieldLabel: 'Password',
			    afterLabelTextTpl: required,
			    xtype: 'textfield',
			    allowBlank: false,
			    name: 'PASSWORD'
		    }],
		    buttons: [{
			    text: 'Save',
			    formBind: true,
			    handler: function() {
				    var form = this.up('form').getForm();
				    if (form.isValid()){
					    loadmask.show();
					    Ext.Ajax.request({
						url: '<?=controller_?>users/save_reset_password',
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
							user_store_<?=$tab_id?>.reload();
						    }
						},
						failure:function(form, response) {
						    Ext.Msg.alert('Failed: ', response.errorMessage);
						}
					    })
							    
						    
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
});
</script>