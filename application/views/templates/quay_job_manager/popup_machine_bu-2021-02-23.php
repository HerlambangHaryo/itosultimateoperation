<script type="text/javascript">
$(function() {
	var quay_machine_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_MACHINE', 'MCH_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>quay_job_manager/data_quay_machine?id_ves_voyage=<?=$id_ves_voyage?>',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var qc_operator_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_USER', 'FULL_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>quay_job_manager/data_qc_operator/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var itv_machine_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_MACHINE', 'MCH_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>quay_job_manager/data_itv_machine/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Choose Machine',
		closable: false,
		items: [Ext.create('Ext.form.Panel', {
			id: 'form_confirm_<?=$tab_id?>',
			frame: true,
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [
			{
				id: "machine_<?=$tab_id?>",
				xtype: 'combo',
				name: "machine_<?=$tab_id?>",
				fieldLabel: 'Crane Name',
				displayField: 'MCH_NAME',
				valueField: 'ID_MACHINE',
				queryMode: 'local',
				editable: false,
				store: quay_machine_list_store,
				allowBlank: false
			},
			
			{
				id: "driver_<?=$tab_id?>",
				xtype: 'combo',
				name: "driver_<?=$tab_id?>",
				fieldLabel: 'QC Operator',
				displayField: 'FULL_NAME',
				valueField: 'ID_USER',
				queryMode: 'local',
				editable: false,
				store: qc_operator_list_store,
				allowBlank: false
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
				store: itv_machine_list_store,
				allowBlank: false
			}
			,{
				id: "stowage_<?=$tab_id?>",
				xtype: 'textfield',
				name: "stowage_<?=$tab_id?>",
				fieldLabel: 'Stowage',
				minLength: 6,
				maxLength: 7,
				maskRe: /[\d]/,
				regex: /^([\d]{6,7})$/,
				allowBlank: false
			}
			],
			buttons: [{
				text: 'Save',
				formBind: true,
				handler: function() {
					if (this.up('form').getForm().isValid()){
						var id_machine = this.up('form').getForm().findField("machine_<?=$tab_id?>").getValue();
						var driver_id = this.up('form').getForm().findField("driver_<?=$tab_id?>").getValue();
						var itv = this.up('form').getForm().findField("itv_<?=$tab_id?>").getValue();
						var stowage = this.up('form').getForm().findField("stowage_<?=$tab_id?>").getValue();
						Ext.Ajax.request({
							url: '<?=controller_?>quay_job_manager/tally_confirm',
							params: {
								no_container: '<?=$no_container?>',
								point: '<?=$point?>',
								id_class_code: '<?=$id_class_code?>',
								id_ves_voyage: '<?=$id_ves_voyage?>',
								stowage: stowage,
								id_machine: id_machine,
								driver_id: driver_id,
								itv: itv
							},
							callback: function(opt,success,response){
								var retval = eval(response.responseText)
								if (retval[0]=='S'){
									Ext.Msg.alert('Success', 'Job complete success');
									win.close();
									Ext.getStore('quay_job_list_<?=$tab_id?>').reload();
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
	Ext.getCmp('form_confirm_<?=$tab_id?>').getForm().findField("stowage_<?=$tab_id?>").setValue('<?=$stowage?>');
});
</script>