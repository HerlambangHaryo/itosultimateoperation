<script type="text/javascript">
Ext.onReady(function(){
	Ext.create('Ext.form.Panel', {
		id: "heapzone_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 200
		},
		items: [{
			xtype:'textfield',
			id: "heapzone_name_<?=$tab_id?>",
			fieldLabel: 'Heap Zone Name',
			name: 'HEAPZONE_NAME',
			allowBlank: false
		},{
			xtype:'textfield',
			id: "owner_<?=$tab_id?>",
			fieldLabel: 'Owner',
			name: 'OWNER',
			allowBlank: false
		},{
			xtype:'numberfield',
			id: "capacity_<?=$tab_id?>",
			fieldLabel: 'Capacity',
			name: 'CAPACITY',
			allowDecimals: false,
			allowBlank: false
		},{
			xtype:'numberfield',
			id: "position_x_<?=$tab_id?>",
			fieldLabel: 'Position X',
			name: 'POSITION_X',
			allowDecimals: false,
			allowBlank: false
		},{
			xtype:'numberfield',
			id: "position_y_<?=$tab_id?>",
			fieldLabel: 'Position Y',
			name: 'POSITION_Y',
			allowDecimals: false,
			allowBlank: false
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
							Ext.Msg.alert('Success', 'New Heap Zone Added');
							Ext.getCmp('<?=$tab_id?>').close();
						},
						failure: function(form, action) {
							loadmask.hide();
							Ext.Msg.alert('Failed', action.result.errors);
							Ext.getCmp('<?=$tab_id?>').close();
						}
					});
				}
			}
		}]
	}).render('heapzone_detail_<?=$tab_id?>');
});
</script>
<div id="heapzone_detail_<?=$tab_id?>"></div>