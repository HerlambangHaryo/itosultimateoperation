<script type="text/javascript">
$(function() {
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Edit Truck',
		closable: false,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			height: 180,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: "id_yard_plan_<?=$tab_id?>",
				xtype: 'hiddenfield',
				name: 'ID_YARD_PLAN',
				value: '<?=$group_plan['ID_YARD_PLAN']?>'
			},{
				id: 'start_slot_<?=$tab_id?>',
				fieldLabel: 'START SLOT',
				xtype: 'numberfield',
				allowBlank: false,
				name: 'START_SLOT',
				value: '<?=$group_plan['START_SLOT']?>'
			},{
				id: 'end_slot_<?=$tab_id?>',
				fieldLabel: 'END SLOT',
				xtype: 'numberfield',
				allowBlank: false,
				name: 'END_SLOT',
				value: '<?=$group_plan['END_SLOT']?>'
			},{
				id: 'start_row_<?=$tab_id?>',
				fieldLabel: 'START ROW',
				xtype: 'numberfield',
				allowBlank: false,
				name: 'START_ROW',
				value: '<?=$group_plan['START_ROW']?>'
			},{
				id: 'end_row_<?=$tab_id?>',
				fieldLabel: 'END ROW',
				xtype: 'numberfield',
				allowBlank: false,
				name: 'END_ROW',
				value: '<?=$group_plan['END_ROW']?>'
			}],
			buttons: [{
				text: 'Edit',
				formBind: true,
				handler: function() {
				    var sslot = Ext.getCmp('start_slot_<?=$tab_id?>').getValue();
				    var eslot = Ext.getCmp('end_slot_<?=$tab_id?>').getValue();
				    var srow = Ext.getCmp('start_row_<?=$tab_id?>').getValue();
				    var erow = Ext.getCmp('end_row_<?=$tab_id?>').getValue();
				    
				    if(parseInt(sslot) < 1 || parseInt(eslot) < 1 || parseInt(srow) < 1 || parseInt(erow) < 1){
					alert('Semua field harus diisi lebih dari 0');
				    }else if(parseInt(eslot) < parseInt(sslot)){
					alert('End Slot harus lebih besar dari atau sama dengan Start Slot');
				    }else if(parseInt(erow) < parseInt(srow)){
					alert('End Row harus lebih besar dari atau sama dengan Start Row');
				    }else{
					var form = this.up('form').getForm();
					if (form.isValid()){
						loadmask.show();
						Ext.Ajax.request({
						    url: '<?=controller_?>yard_planning_group/edit_yard_plan',
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
							    yard_plan_group_store.reload();
							}
						    },
						    failure:function(form, response) {
							Ext.Msg.alert('Failed: ', response.errorMessage);
							loadmask.hide();
						    }
						});
					}
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