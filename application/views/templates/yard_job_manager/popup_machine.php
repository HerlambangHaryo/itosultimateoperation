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
	
	var itv_machine_list_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_MACHINE', 'MCH_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_job_manager/data_itv_machine?id_pool=<?=$id_pool?>&id_class_code=<?=$id_class_code?>',
			reader: {
				type: 'json'
			}
		},
		listeners: {
			load: function(store) {
				if("<?=$iditv?>"!=''){
					itv_machine_list_store_<?=$tab_id?>.insert(0, [{'ID_MACHINE': '<?=$iditv?>',
						'MCH_NAME': '<?=$itv?>'}]);
				}
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
			id: 'form_confirm_<?=$tab_id?>', // HERE tambahan
			frame: true,
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [
			{
				// id: "machine_<?=$tab_id?>",
				// xtype: 'combo',
				// name: "machine_<?=$tab_id?>",
				// fieldLabel: 'Machine Name',
				// displayField: 'MCH_NAME',
				// valueField: 'ID_MACHINE',
				// queryMode: 'local',
				// editable: false,
				// store: yard_machine_list_store,
				// allowBlank: false,
				id: "machine_<?=$tab_id?>",
				xtype: 'textfield',
				name: "machine_",
				fieldLabel: 'Machine Name',
				allowBlank: false,
    			readOnly: true
			},
			{
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
			},
			
			{
				id: "itv_<?=$tab_id?>",
				xtype: 'combo',
				name: "itv_<?=$tab_id?>",
				fieldLabel: 'ITV Name',
				displayField: 'MCH_NAME',
				valueField: 'ID_MACHINE',
				queryMode: 'local',
				editable: false,
				store: itv_machine_list_store_<?=$tab_id?>,
				readOnly: true,
				allowBlank: false
			}],
			buttons: [{
				text: 'Save',
				formBind: true,
				handler: function() {
					if (this.up('form').getForm().isValid()){
						var id_machine = this.up('form').getForm().findField("machine_<?=$tab_id?>").getValue();
						var driver_id = this.up('form').getForm().findField("driver_<?=$tab_id?>").getValue();
						var itv_id = this.up('form').getForm().findField("itv_<?=$tab_id?>").getValue();
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
								id_machine: '<?=$id_machine?>',
								id_class_code: '<?=$id_class_code?>',
								iditv: itv_id,
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
	Ext.getCmp('form_confirm_<?=$tab_id?>').getForm().findField("machine_<?=$tab_id?>").setValue('<?=$machine?>'); // EDIT
	if("<?=$job?>"=='LD' && "<?=$iditv?>"==''){
		Ext.getCmp('form_confirm_<?=$tab_id?>').getForm().findField("itv_<?=$tab_id?>").setReadOnly (false);
	}
	if("<?=$iditv?>"!=''){
		Ext.getCmp('form_confirm_<?=$tab_id?>').getForm().findField("itv_<?=$tab_id?>").setValue('<?=$iditv?>').setReadOnly (true);
	}
	if("<?=$job?>"!='LD' && "<?=$iditv?>"==''){
		Ext.getCmp('form_confirm_<?=$tab_id?>').getForm().findField("itv_<?=$tab_id?>").allowBlank = true;
	}
});
</script>