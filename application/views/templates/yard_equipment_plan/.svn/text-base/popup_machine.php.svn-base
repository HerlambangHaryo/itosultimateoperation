<script type="text/javascript">
$(function() {
	var yard_machine_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_MACHINE', 'MCH_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_equipment_plan/data_yard_machine/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Plan Equipment',
		closable: false,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: "machine_<?=$tab_id?>",
				xtype: 'combo',
				name: "machine_<?=$tab_id?>",
				fieldLabel: 'Machine Name',
				displayField: 'MCH_NAME',
				valueField: 'ID_MACHINE',
				queryMode: 'local',
				editable: false,
				store: yard_machine_list_store,
				allowBlank: false,
			}],
			buttons: [{
				text: 'Plan',
				formBind: true,
				handler: function() {
					if (this.up('form').getForm().isValid()){
						var id_machine = this.up('form').getForm().findField("machine_<?=$tab_id?>").getValue();
						PlanEquipmentYard_<?=$tab_id?>(id_machine);
						win.close();
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