<script type="text/javascript">
	
$(function() {
    var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
	var role_store = Ext.create('Ext.data.Store', {
	    fields: ['val', 'name'],
	    data: [
		{"val": "Y","name": "Yes"},
		{"val": "N","name": "No"}
	    ]
	});
	
	var terminal_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_TERMINAL','TERMINAL_CODE','TERMINAL_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>main/terminal_list/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var role_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_GROUP','GROUP_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>main/group_list/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
		
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
//		height: 400,
		title: 'Add User',
		closable: false,
		items: [Ext.create('Ext.form.Panel', {
//			frame: true,
			
//			height: 400,
			layout: 'column',
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
			    bodyPadding: 5,
//			    columnWidth: '.5',
			    width:300,
			    items: [ 
				{
					id: 'FULL_NAME_<?=$tab_id?>',
					fieldLabel: 'Full Name',
					afterLabelTextTpl: required,
					xtype: 'textfield',
					allowBlank: false,
					name: 'FULL_NAME'
				},{
					id: 'NICK_NAME_<?=$tab_id?>',
					fieldLabel: 'Nick Name',
					afterLabelTextTpl: required,
					xtype: 'textfield',
					allowBlank: false,
					name: 'NICK_NAME'
				},{
					id: 'USERNAME_<?=$tab_id?>',
					fieldLabel: 'Username',
					afterLabelTextTpl: required,
					xtype: 'textfield',
					allowBlank: false,
					name: 'USERNAME'
				},{
					id: 'PASSWORD_<?=$tab_id?>',
					fieldLabel: 'Password',
					afterLabelTextTpl: required,
					xtype: 'textfield',
					allowBlank: false,
					name: 'PASSWORD'
				},{
					xtype:'combo',
					id: "group_<?=$tab_id?>",
					name: "GROUP",
					displayField: 'GROUP_NAME',
					valueField: 'ID_GROUP',
					fieldLabel: 'Group Role',
//					afterLabelTextTpl: required,
//					allowBlank: false,
					anchor:'95%',
					emptyText: 'Autocomplete',
					queryMode: 'remote',
					typeAhead: true,
					minChars: 0,
					triggerAction: 'query',
					store: role_list_store
				}
			    ],
			    layout: 'form', // <- ADDED
			    //xtype: 'fieldset',// <- not necesarry, but I would set it explicit
			    border: false // <- ADDED
			}
			,{ 
			    bodyPadding: 5,
			    columnWidth: '.5', 
			    width:200,
			    items: [ {
					id: 'ROLE_VMT_<?=$tab_id?>',
					fieldLabel: 'Role VMT',
					afterLabelTextTpl: required,
					xtype: 'combo',
					renderTo: Ext.getCmp(),
					store: role_store,
					displayField: 'name',
					valueField: 'val',
					allowBlank: false,
					name: 'ROLE_VMT'
				},{
					id: 'ROLE_PAGER_<?=$tab_id?>',
					fieldLabel: 'Role Pager',
					afterLabelTextTpl: required,
					xtype: 'combo',
					renderTo: Ext.getCmp(),
					store: role_store,
					displayField: 'name',
					valueField: 'val',
					allowBlank: false,
					name: 'ROLE_PAGER'
				}
				,{
					id: 'ROLE_PDA_<?=$tab_id?>',
					fieldLabel: 'Role PDA',
					afterLabelTextTpl: required,
					xtype: 'combo',
					renderTo: Ext.getCmp(),
					store: role_store,
					displayField: 'name',
					valueField: 'val',
					allowBlank: false,
					name: 'ROLE_PDA'
				}
//				,{
//					id: 'ROLE_GATE_<?=$tab_id?>',
//					fieldLabel: 'Role Gate',
//					afterLabelTextTpl: required,
//					xtype: 'combo',
//					renderTo: Ext.getCmp(),
//					store: role_store,
//					displayField: 'name',
//					valueField: 'val',
//					allowBlank: false,
//					name: 'ROLE_GATE'
//				},{
//					id: 'ROLE_TALLY_<?=$tab_id?>',
//					fieldLabel: 'Role Tally',
//					afterLabelTextTpl: required,
//					xtype: 'combo',
//					renderTo: Ext.getCmp(),
//					store: role_store,
//					displayField: 'name',
//					valueField: 'val',
//					allowBlank: false,
//					name: 'ROLE_TALLY'
//				}
//				,{
//					id: 'ROLE_YARD_<?=$tab_id?>',
//					fieldLabel: 'Role Yard',
//					afterLabelTextTpl: required,
//					xtype: 'combo',
//					renderTo: Ext.getCmp(),
//					store: role_store,
//					displayField: 'name',
//					valueField: 'val',
//					allowBlank: false,
//					name: 'ROLE_YARD'
//				}
//				,{
//					id: 'ROLE_REEFER_<?=$tab_id?>',
//					fieldLabel: 'Role Reefer',
//					afterLabelTextTpl: required,
//					xtype: 'combo',
//					renderTo: Ext.getCmp(),
//					store: role_store,
//					displayField: 'name',
//					valueField: 'val',
//					allowBlank: false,
//					name: 'ROLE_REEFER'
//				}
				,{
					id: 'ROLE_QC_<?=$tab_id?>',
					fieldLabel: 'Role QC',
					afterLabelTextTpl: required,
					xtype: 'combo',
					renderTo: Ext.getCmp(),
					store: role_store,
					displayField: 'name',
					valueField: 'val',
					allowBlank: false,
					name: 'ROLE_QC'
				}
//				,{
//					id: 'ROLE_ITV_<?=$tab_id?>',
//					fieldLabel: 'Role ITV',
//					afterLabelTextTpl: required,
//					xtype: 'combo',
//					renderTo: Ext.getCmp(),
//					store: role_store,
//					displayField: 'name',
//					valueField: 'val',
//					allowBlank: false,
//					name: 'ROLE_ITV'
//				}
			    ],
			    layout: 'form', // <- ADDED
			    //xtype: 'fieldset',// <- not necesarry, but I would set it explicit
			    border: false // <- ADDED 
			}
			,{ 
			    bodyPadding: 5,
			    columnWidth: '.5', 
			    items: [ 
				{
				xtype: 'fieldcontainer',
				fieldLabel: 'Terminal',
				defaultType: 'checkboxfield',
				items: [
<?php
foreach($terminal_list as $list){
?>
				    {
					    boxLabel  : '<?=$list['TERMINAL_CODE'].'-'.$list['TERMINAL_NAME']?>',
					    name      : 'terminal[]',
					    inputValue: '<?=$list['ID_TERMINAL']?>',
					    id        : '<?=$list['ID_TERMINAL']?>'
				    },
<?php
}
?>
				    ]
				}
			    ],
			    layout: 'form', // <- ADDED
			    //xtype: 'fieldset',// <- not necesarry, but I would set it explicit
			    border: false // <- ADDED 
			}],
			defaults: { width: 400 },
			buttons: [{
				text: 'Save',
				formBind: true,
				handler: function() {
					var form = this.up('form').getForm();
					if (form.isValid()){
//						loadmask.show();
						Ext.Ajax.request({
							url: '<?=controller_?>users/check_username',
							params: {
								username: form.findField("USERNAME").getValue()
							},
							success: function(response){
								var text = response.responseText;
								console.log('text : ' + text);
								if (text=='0'){
									Ext.Ajax.request({
									    url: '<?=controller_?>users/save_user',
									    method: 'POST',
									    params: form.getValues(),
									    scope: this,
									    success: function(result, response) {
//										loadmask.hide();
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
								}else{
//									loadmask.hide();
									Ext.MessageBox.show({
										title: 'Error',
										msg: 'Username ' + form.findField("USERNAME").getValue() + ' Already Exist.',
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
});
</script>