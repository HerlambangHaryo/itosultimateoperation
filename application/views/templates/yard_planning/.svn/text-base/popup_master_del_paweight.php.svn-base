<script type="text/javascript">
$(function() {
	var store2 = Ext.create('Ext.data.Store', {
		fields:['DNAME_PAWEIGHT', 'SIZE_PAWEIGHT', 'MAX_ESTPAWEIGHT', 'MIN_ESTPAWEIGHT']
	}); 
	
	var store3 = Ext.create('Ext.data.Store', {
		fields:['ID_PAWEIGHT', 'NAME_PAWEIGHT'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_planning/data_paweight/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});

	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Delete Weight Category',
		closable: false,
		width: 400,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 150
			},
			items: [{
					id: "cb_weightcat<?=$tab_id?>",
					xtype: 'combo',
					name: "cb_weightcat<?=$tab_id?>",
					fieldLabel: 'Category Name (Exist)',
					displayField: 'NAME_PAWEIGHT',
					valueField: 'ID_PAWEIGHT',
					queryMode: 'local',
					editable: false,
					allowBlank: false,
					store: store3,
					listeners: {
						change: function(field, newValue){
							store2.setProxy({
								type: 'ajax',
								url: '<?=controller_?>yard_planning/get_datapaWeightD/'+newValue,
								reader: {
									type: 'json'
								}
							});
							store2.load();
						}
					}
				},{
					xtype: 'button',
					text: 'Delete',
					margin: '10 0 10 250',
					handler : function() {
						if (this.up('form').getForm().isValid()){
							var category_name = this.up('form').getForm().findField("cb_weightcat<?=$tab_id?>").getValue();
							
							 Ext.Ajax.request({
								url: '<?=controller_?>yard_planning/delete_masterweight/',
								params: {name: category_name },
								success: function(response){
									var text = response.responseText;
									if (text='1'){
										Ext.MessageBox.show({
											title: 'Success',
											msg: 'Weight category deleted.',
											buttons: Ext.MessageBox.OK
										});
										store3.reload();
									}else{
										Ext.MessageBox.show({
											title: 'Error',
											msg: 'Failed to delete.',
											buttons: Ext.MessageBox.OK
										});
									}
									//loadmask.hide();
								}
							});
						}
					}
				},{
					id: 'sub_catweight<?=$tab_id?>',
					xtype: 'grid',
					width: 360,
					height: 200,
					collapsible: true,
					title:'Sub Category Weight Master',
					store: store2,
					columns: [{
						text: 'Name',
						flex: 35,
						dataIndex: 'DNAME_PAWEIGHT'
					},{
						text: 'Size',
						flex: 35,
						dataIndex: 'SIZE_PAWEIGHT'
					},{
						text: 'Min',
						flex: 35,
						dataIndex: 'MIN_ESTPAWEIGHT'
					},{
						text: 'Max',
						flex: 35,
						dataIndex: 'MAX_ESTPAWEIGHT'
					}]
				}
			],
			buttons: [{
				text: 'Close',
				handler: function() {
					win.close();
				}
			}]
		})]
	});
	win.show();
});
