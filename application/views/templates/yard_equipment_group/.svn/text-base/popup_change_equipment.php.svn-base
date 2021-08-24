<script type="text/javascript">
$(function() {
	var machine_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_MACHINE', 'MCH_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_equipment_group/data_yard_machine/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Change Equipment',
		closable: false,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			padding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelPad: 0
			},
			layout: 'hbox',
			items: [{
				id: "machine_<?=$tab_id?>",
				xtype: 'combo',
				name: "machine_<?=$tab_id?>",
				fieldLabel: 'Machine Name',
				queryMode: 'local',
				displayField: 'MCH_NAME',
				valueField: 'ID_MACHINE',
				editable: false,
				store: machine_list_store,
				allowBlank: false
			}],
			buttons: [{
				text: 'Save',
				formBind: true,
				handler: function() {
					if (this.up('form').getForm().isValid()){
						var form = this.up('form').getForm();
						var id_machine = form.findField("machine_<?=$tab_id?>").getValue();
						loadmask.show();
						Ext.Ajax.request({
							url: '<?=controller_?>yard_equipment_group/save_change_equipment_plan/',
							params: {
								id_machine: id_machine,
								id_mch_plan: '<?=$id_mch_plan?>'
							},
							success: function(response){
								var text = response.responseText;
								if (text=='1'){
									Ext.MessageBox.show({
										title: 'Success',
										msg: 'Machine changed.',
										buttons: Ext.MessageBox.OK
									});
									Ext.getCmp("eq_group_"+Ext.getCmp('west_panel').getActiveTab().getId()).getStore().load();
									win.close();
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