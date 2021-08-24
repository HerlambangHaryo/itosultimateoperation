<script type="text/javascript">
$(function() {
	var yard_machine_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_MACHINE', 'MCH_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_job_manager/data_yard_machine/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var yc_operator_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_USER', 'FULL_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_job_manager/data_yc_operator/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Choose Yard Machine',
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
			},{
				id: "driver_<?=$tab_id?>",
				xtype: 'combo',
				name: "driver_<?=$tab_id?>",
				fieldLabel: 'YC Operator',
				displayField: 'FULL_NAME',
				valueField: 'ID_USER',
				queryMode: 'local',
				editable: false,
				store: yc_operator_list_store,
				allowBlank: false,
			}],
			buttons: [{
				text: 'Save',
				formBind: true,
				handler: function() {
					if (this.up('form').getForm().isValid()){
						var id_machine = this.up('form').getForm().findField("machine_<?=$tab_id?>").getValue();
						var driver_id = this.up('form').getForm().findField("driver_<?=$tab_id?>").getValue();
						Ext.Ajax.request({
							url: '<?=controller_?>yard_job_manager/yard_placement_submit',
							params: {
								no_container: '<?=$no_container?>',
								point: '<?=$point?>',
								id_op_status: '<?=$id_op_status?>',
								event: '<?=$event?>',
								block_name: '<?=$block_name?>',
								id_block: '<?=$id_block?>',
								slot: '<?=$slot?>',
								row: '<?=$row?>',
								tier: '<?=$tier?>',
								yard_placement: '<?=$yard_placement?>',
								id_machine: id_machine,
								driver_id: driver_id
							},
							callback: function(opt,success,response){
								var retval = eval(response.responseText)
								if (retval[0]=='S'){
									Ext.Msg.alert('Success', 'Job complete success');
									win.close();
									Ext.getStore('yard_job_list_<?=$tab_id?>').reload();
								}else{
									Ext.Msg.alert('Failed', retval[1]);
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