<style>
#divProfile{
	background:#ffffff;
}
</style>

<script>
function edit_bay_profile_<?=$tab_id?>(id_bay){
	var url="<?=controller_?>vessel_profile/editProfile/<?=$vs_code;?>/"+id_bay+"/<?=$tab_id;?>";
	$('#divProfile').load(url).dialog({modal:true, height:400,width:500});
}

$('#contentVesprofile_<?=$tab_id?>').load('<?=controller_?>vessel_profile/vesproContent/<?=$vs_code?>/<?=$tab_id?>');

function reloadaja_<?=$tab_id?>()
{
	$('#contentVesprofile_<?=$tab_id?>').load('<?=controller_?>vessel_profile/vesproContent/<?=$vs_code?>/<?=$tab_id?>');
}

function edit_bay_<?=$tab_id?>(id_bay,no_bay,jmlrow,jmltier_on,jmltier_under,jmlrowexist,jmltier_underexist,jmltier_onexist,abv,blw)
	{
		var win = new Ext.Window({
			layout: 'fit',
			modal: true,
			title: 'Edit Bay '+no_bay,
			closable: false,
			items: Ext.create('Ext.form.Panel', {
				frame: true,
				autoScroll: true,
				bodyPadding: 5,
				url: '<?=controller_?>vessel_profile/generate_rowtier_bay?tab_id=<?=$tab_id?>&vscd=<?=$vs_code?>',
				fieldDefaults: {
					labelAlign: 'left',
					labelWidth: 90,
					anchor: '100%'
				},
				items: [{
					xtype: 'hiddenfield',
					name: 'ID_BAY',
					value: id_bay
				},{
					xtype: 'numberfield',
					name: 'JMLROW',
					fieldLabel: 'Row Bay',
					value: jmlrowexist,
					minValue: 1,
					maxValue: jmlrow
				},{
					xtype: 'numberfield',
					name: 'JMLTIERD',
					fieldLabel: 'Tier Deck',
					value: jmltier_onexist,
					minValue: 1,
					maxValue: jmltier_on
				},{
					xtype: 'numberfield',
					name: 'JMLTIERH',
					fieldLabel: 'Tier Hatch',
					value: jmltier_underexist,
					minValue: 1,
					maxValue: jmltier_under
				},{
					xtype:'combo',
					name: 'ABV_STAT',
					displayField: 'STATUS',
					valueField: 'ID_STATUS',
					value:abv,
					fieldLabel: 'Deck Status',
					allowBlank: false,
					anchor:'95%',
					emptyText: '- Choose -',
					queryMode: 'local',
					store: Ext.create('Ext.data.Store', {
						fields:['ID_STATUS', 'STATUS'],
						data : [
							 {ID_STATUS: 'AKTIF', STATUS: 'ACTIVE'},
							 {ID_STATUS: 'NONE', STATUS: 'NOT ACTIVE'}
						 ]
					})
				},{
					xtype:'combo',
					name: 'BLW_STAT',
					displayField: 'STATUS',
					valueField: 'ID_STATUS',
					value:blw,
					fieldLabel: 'Hatch Status',
					allowBlank: false,
					anchor:'95%',
					emptyText: '- Choose -',
					queryMode: 'local',
					store: Ext.create('Ext.data.Store', {
						fields:['ID_STATUS', 'STATUS'],
						data : [
							 {ID_STATUS: 'AKTIF', STATUS: 'ACTIVE'},
							 {ID_STATUS: 'NONE', STATUS: 'NOT ACTIVE'}
						 ]
					})
				}],
				buttons: [{
					text: 'Save',
					formBind: true,
					handler: function() {
						var form = this.up('form').getForm();
						if (form.isValid()){
							loadmask.show();
							form.submit({
								success: function(form, action) {
									loadmask.hide();
									Ext.Msg.alert('Success', 'generate success');
									// console.log(Ext.getCmp('<?=$tab_id?>'));
									win.close();
									reloadaja_<?=$tab_id?>();
								},
								failure: function(form, action) {
									loadmask.hide();
									Ext.Msg.alert('Failed', action.result.errors);
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
			})
		});
		win.show();
	}

function edit_occupy_<?=$tab_id?>(id_bay,no_bay)
	{
		var win = new Ext.Window({
			layout: 'fit',
			modal: true,
			title: 'Occupy Bay '+no_bay,
			closable: false,
			items: Ext.create('Ext.form.Panel', {
				frame: true,
				autoScroll: true,
				bodyPadding: 5,
				url: '<?=controller_?>vessel_profile/set_occupy?tab_id=<?=$tab_id?>&vescd=<?=$vs_code;?>',
				fieldDefaults: {
					labelAlign: 'left',
					labelWidth: 90,
					anchor: '100%'
				},
				items: [{
					xtype: 'hiddenfield',
					name: 'ID_BAY',
					value: id_bay
				},{
					xtype:'combo',
					name: 'STAT',
					displayField: 'STATUS',
					valueField: 'ID_STATUS',
					fieldLabel: 'Status',
					allowBlank: false,
					anchor:'95%',
					emptyText: '- Choose -',
					queryMode: 'local',
					store: Ext.create('Ext.data.Store', {
						fields:['ID_STATUS', 'STATUS'],
						data : [
							 {ID_STATUS: 'Y', STATUS: 'YES'},
							 {ID_STATUS: 'N', STATUS: 'NO'}
						 ]
					})
				}],
				buttons: [{
					text: 'Save',
					formBind: true,
					handler: function() {
						var form = this.up('form').getForm();
						if (form.isValid()){
							loadmask.show();
							form.submit({
								success: function(form, action) {
									loadmask.hide();
									Ext.Msg.alert('Success', 'save success');
									// console.log(Ext.getCmp('<?=$tab_id?>'));
									win.close();
									reloadaja_<?=$tab_id?>();
								},
								failure: function(form, action) {
									loadmask.hide();
									Ext.Msg.alert('Failed', action.result.errors);
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
			})
		});
		win.show();
	}

function assign_hatch_<?=$tab_id?>(id_bay,no_bay)
	{
		var htc_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_HATCH', 'HATCH_NUMBER'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>vessel_profile/data_hatch_list/<?=$vs_code;?>',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});

		var win = new Ext.Window({
			layout: 'fit',
			modal: true,
			title: 'Hatch Assign - Bay '+no_bay,
			closable: false,
			items: Ext.create('Ext.form.Panel', {
				frame: true,
				autoScroll: true,
				bodyPadding: 5,
				url: '<?=controller_?>vessel_profile/set_hatch?tab_id=<?=$tab_id?>&vescd=<?=$vs_code;?>',
				fieldDefaults: {
					labelAlign: 'left',
					labelWidth: 90,
					anchor: '100%'
				},
				items: [{
					xtype: 'hiddenfield',
					name: 'ID_BAY',
					value: id_bay
				},{
					xtype:'combo',
					name: 'HATCH_ID',
					displayField: 'HATCH_NUMBER',
					valueField: 'ID_HATCH',
					fieldLabel: 'Hatch Numb',
					allowBlank: false,
					anchor:'95%',
					emptyText: '- Choose -',
					queryMode: 'local',
					store: htc_list_store
				}],
				buttons: [{
					text: 'Save',
					formBind: true,
					handler: function() {
						var form = this.up('form').getForm();
						if (form.isValid()){
							loadmask.show();
							form.submit({
								success: function(form, action) {
									loadmask.hide();
									Ext.Msg.alert('Success', 'save success');
									// console.log(Ext.getCmp('<?=$tab_id?>'));
									win.close();
									reloadaja_<?=$tab_id?>();
								},
								failure: function(form, action) {
									loadmask.hide();
									Ext.Msg.alert('Failed', action.result.errors);
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
			})
		});
		win.show();
	}

</script>

<div id="contentVesprofile_<?=$tab_id?>"></div>
