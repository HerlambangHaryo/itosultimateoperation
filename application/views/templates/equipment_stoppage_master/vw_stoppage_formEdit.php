<script type="text/javascript">
$(function() {
	var group_app_store = Ext.create('Ext.data.Store', {
	    fields: ['val', 'name'],
	    data: [
		{"val": "","name": "No Group"},
		{"val": "PDA","name": "PDA"}
	    ]
	});
	var eq_type_store = Ext.create('Ext.data.Store', {
	    fields: ['val', 'name'],
	    data: [
		{"val": "YARD","name": "YARD"},
		{"val": "QUAY","name": "QUAY"}
	    ]
	});
	var category_type_store = Ext.create('Ext.data.Store', {
	    fields: ['val', 'name'],
	    data: [
		{"val": "NOT","name": "NOT"},
		{"val": "IDLE","name": "IDLE"}
	    ]
	});
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Edit Equipment Stoppage',
		closable: false,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			height: 150,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: "id_suspend_<?=$tab_id?>",
				xtype: 'hiddenfield',
				name: 'ID_SUSPEND',
				value: '<?=$suspend['ID_SUSPEND']?>'
			},{
				id: 'activity_<?=$tab_id?>',
				fieldLabel: 'Activity',
				xtype: 'textfield',
				allowBlank: false,
				name: 'ACTIVITY',
				value: '<?=$suspend['ACTIVITY']?>'
			},{
				id: 'eq_type_<?=$tab_id?>',
				fieldLabel: 'EQ Type',
				xtype: 'combo',
				allowBlank: false,
				renderTo: Ext.getCmp(),
				store: eq_type_store,
				displayField: 'name',
				valueField: 'val',
				name: 'EQ_TYPE',
				value: '<?=$suspend['EQ_TYPE']?>'
			},{
				id: 'category_<?=$tab_id?>',
				fieldLabel: 'Category',
				xtype: 'combo',
				allowBlank: false,
				renderTo: Ext.getCmp(),
				store: category_type_store,
				displayField: 'name',
				valueField: 'val',
				name: 'C_TYPE',
				value: '<?=$suspend['CATEGORY']?>'
			}],
			buttons: [{
				text: 'Edit',
				formBind: true,
				handler: function() {
					var form = this.up('form').getForm();
					if (form.isValid()){
						loadmask.show();
						Ext.Ajax.request({
						    url: '<?=controller_?>equipment_stoppage/edit_stoppage',
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
							    equipment_stoppage_store.reload();
							}
						    },
						    failure:function(form, response) {
							Ext.Msg.alert('Failed: ', response.errorMessage);
							loadmask.hide();
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