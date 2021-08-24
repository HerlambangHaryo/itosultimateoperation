<script type="text/javascript">
$(function() {
	var yard_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_YARD', 'NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_job_manager/data_yard_list',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var block_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_BLOCK', 'BLOCK_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_job_manager/data_block_list/<?=$id_yard?>',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var slot_list_store = Ext.create('Ext.data.Store', {
		fields:['slot'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_job_manager/data_slot_list/<?=$id_yard?>',
			reader: {
				type: 'json'
			}
		}
	});
	
	var row_list_store = Ext.create('Ext.data.Store', {
		fields:['row'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_job_manager/data_row_list/<?=$id_yard?>',
			reader: {
				type: 'json'
			}
		}
	});
	
	var tier_list_store = Ext.create('Ext.data.Store', {
		fields:['tier'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_job_manager/data_tier_list/<?=$id_yard?>',
			reader: {
				type: 'json'
			}
		}
	});
	
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Change Container Preferred Area',
		closable: false,
		width: 430,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			padding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelPad: 0,
				labelWidth: 40
			},
			layout: 'hbox',
			items: [
				{
					id: "yard_<?=$tab_id?>", // yard
					xtype: 'combo',
					name: "yard_<?=$tab_id?>",  // yard
					fieldLabel: 'Yard',  // yard
					width: 100,
					queryMode: 'local',
					displayField: 'YARD_NAME',  // yard
					valueField: 'ID_YARD',  // yard
					editable: false,
					store: yard_list_store,  // yard
					listeners: {
						change: {
							fn: function(){
								Ext.getCmp("block_<?=$tab_id?>").reset();
								Ext.getCmp("slot_<?=$tab_id?>").reset();
								Ext.getCmp("row_<?=$tab_id?>").reset();
								Ext.getCmp("tier_<?=$tab_id?>").reset();
							}
						}
					},
					allowBlank: false
				},{
					id: "block_<?=$tab_id?>", // block
					xtype: 'combo',
					name: "block_<?=$tab_id?>", // block
					fieldLabel: 'Block', // block
					width: 100,
					displayField: 'block', // block
					valueField: 'block', // block
					editable: false,
					store: block_list_store, // block NEW
					listeners: {
						beforequery: {
							fn: function(queryPlan){
								var id_yard = Ext.getCmp("yard_<?=$tab_id?>").getValue(); // yard?
								if (id_yard){ // yard?
									queryPlan.query = id_yard; // yard?
								}else{
									return false;
								}
							}
						}
					},
					allowBlank: false
				},{
					id: "slot_<?=$tab_id?>",
					xtype: 'combo',
					name: "slot_<?=$tab_id?>",
					fieldLabel: 'Slot',
					width: 100,
					displayField: 'slot',
					valueField: 'slot',
					editable: false,
					store: slot_list_store,
					listeners: {
						beforequery: {
							fn: function(queryPlan){
								var id_block = Ext.getCmp("block_<?=$tab_id?>").getValue();
								if (id_block){
									queryPlan.query = id_block;
								}else{
									return false;
								}
							}
						}
					},
					allowBlank: false
				},{
					id: "row_<?=$tab_id?>",
					xtype: 'combo',
					name: "row_<?=$tab_id?>",
					fieldLabel: 'Row',
					width: 100,
					displayField: 'row',
					valueField: 'row',
					editable: false,
					store: row_list_store,
					listeners: {
						beforequery: {
							fn: function(queryPlan){
								var id_block = Ext.getCmp("block_<?=$tab_id?>").getValue();
								if (id_block){
									queryPlan.query = id_block;
								}else{
									return false;
								}
							}
						}
					},
					allowBlank: false
				},{
					id: "tier_<?=$tab_id?>",
					xtype: 'combo',
					name: "tier_<?=$tab_id?>",
					fieldLabel: 'Tier',
					width: 100,
					displayField: 'tier',
					valueField: 'tier',
					editable: false,
					store: tier_list_store,
					listeners: {
						beforequery: {
							fn: function(queryPlan){
								var id_block = Ext.getCmp("block_<?=$tab_id?>").getValue();
								if (id_block){
									queryPlan.query = id_block;
								}else{
									return false;
								}
							}
						}
					},
					allowBlank: false
				}
			],
			buttons: [{
				text: 'Save',
				formBind: true,
				handler: function() {
					if (this.up('form').getForm().isValid()){
						var form = this.up('form').getForm();
						var id_block = form.findField("block_<?=$tab_id?>").getValue();
						var block_name = form.findField("block_<?=$tab_id?>").getRawValue();
						var slot = form.findField("slot_<?=$tab_id?>").getValue();
						var row = form.findField("row_<?=$tab_id?>").getValue();
						var tier = form.findField("tier_<?=$tab_id?>").getValue();
						loadmask.show();
						Ext.Ajax.request({
							url: '<?=controller_?>yard_job_manager/save_change_PA/',
							params: {
								id_block: id_block,
								block_name: block_name,
								slot: slot,
								row: row,
								tier: tier,
								no_container: '<?=$no_container?>',
								point: '<?=$point?>'
							},
							success: function(response){
								console.log(response.responseText);
								var res = Ext.JSON.decode(response.responseText);
								if (res[0]=='S'){
									Ext.MessageBox.show({
										title: 'Success',
										msg: 'PA changed.',
										buttons: Ext.MessageBox.OK
									});
									Ext.getCmp("yard_job_grid_"+Ext.getCmp('center_panel').getActiveTab().getId()).getStore().load();
									win.close();
								}else {
									Ext.MessageBox.show({
										title: 'Error',
										msg: res[1],
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
	
	Ext.getCmp("yard_<?=$tab_id?>").setValue('<?=$id_yard?>');
	Ext.getCmp("block_<?=$tab_id?>").setValue('<?=$id_block?>');
	Ext.getCmp("slot_<?=$tab_id?>").setValue('<?=$slot?>');
	Ext.getCmp("row_<?=$tab_id?>").setValue('<?=$row?>');
	Ext.getCmp("tier_<?=$tab_id?>").setValue('<?=$tier?>');
});
</script>