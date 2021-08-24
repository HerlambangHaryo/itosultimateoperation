
<script type="text/javascript">
	
//===================	
$(function() {
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Edit Pool',
		closable: false,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: "id_pool_<?=$tab_id?>",
				xtype: 'hiddenfield',
				name: 'ID_POOL',
				value: '<?=$pool['ID_POOL']?>'
			},{
				id: 'pool_name_<?=$tab_id?>',
				fieldLabel: 'Pool Name',
				xtype: 'textfield',
				allowBlank: false,
				name: 'POOL_NAME',
//				disabled:true,
				value: '<?=$pool['POOL_NAME']?>'
			},{
				id: 'pool_description_<?=$tab_id?>',
				fieldLabel: 'description',
				xtype: 'textfield',
				allowBlank: false,
				name: 'POOL_DESCRIPTION',
				value: '<?=$pool['POOL_DESCRIPTION']?>'
			},{
				id: 'pool_type_<?=$tab_id?>',
				fieldLabel: 'Type',
				xtype: 'combo',
				renderTo: Ext.getCmp(),
				store: Ext.create('Ext.data.Store', {
				    fields: ['val', 'name'],
				    data: [
					{"val": "V","name": "Vessel"},
					{"val": "Y","name": "Yard"}
				    ]
				}),
				displayField: 'name',
				valueField: 'val',
				allowBlank: false,
				name: 'POOL_TYPE',
				value: '<?=$pool['POOL_TYPE']?>'
			}],
			buttons: [{
				text: 'Edit',
				formBind: true,
				handler: function() {
					var form = this.up('form').getForm();
					if (form.isValid()){
					    loadmask.show();
					    Ext.Ajax.request({
						url: '<?=controller_?>pool/edit_pool',
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
							pool_store.reload();
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
<!--<div id="pool_edit_<?=$tab_id?>"></div>-->