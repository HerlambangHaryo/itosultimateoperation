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
		title: 'Weight Category',
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
					id: "pa_weightcat<?=$tab_id?>",
					xtype: 'textfield',
					name: "pa_weightcat<?=$tab_id?>",
					fieldLabel: 'Wg Category Name',
					allowBlank: false
				},{
					id: "cb_weightcat<?=$tab_id?>",
					xtype: 'combo',
					name: "cb_weightcat<?=$tab_id?>",
					fieldLabel: 'Category Name (Exist)',
					displayField: 'NAME_PAWEIGHT',
					valueField: 'ID_PAWEIGHT',
					queryMode: 'local',
					editable: false,
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
					xtype: 'fieldcontainer',
					fieldLabel: '20',
					layout: 'hbox',
					combineErrors: true,
					defaultType: 'textfield',
					defaults: {
						hideLabel: 'true'
					},
					items: [{
						id: "20min<?=$tab_id?>",
						xtype: 'textfield',
						name: "20min<?=$tab_id?>",
						fieldLabel: 'min20',
						width: 30
					},{
						xtype: 'label',
						text: '<',
						margins: '0 5 0 5'
					},{
						id: "20med<?=$tab_id?>",
						xtype: 'textfield',
						name: "20med<?=$tab_id?>",
						fieldLabel: 'med20',
						width: 30
					},{
						xtype: 'label',
						text: '<',
						margins: '0 5 0 5'
					},{
						id: "20medhigh<?=$tab_id?>",
						xtype: 'textfield',
						name: "20medhigh<?=$tab_id?>",
						fieldLabel: 'medhigh20',
						width: 30
					},{
						xtype: 'label',
						text: '<',
						margins: '0 5 0 5'
					},{
						id: "20hgh<?=$tab_id?>",
						xtype: 'textfield',
						name: "20hgh<?=$tab_id?>",
						fieldLabel: 'hgh20',
						width: 30
					}]
				},{
					xtype: 'fieldcontainer',
					fieldLabel: '40',
					layout: 'hbox',
					combineErrors: true,
					defaultType: 'textfield',
					defaults: {
						hideLabel: 'true'
					},
					items: [{
						id: "40min<?=$tab_id?>",
						xtype: 'textfield',
						name: "40MIN",
						fieldLabel: '40min<?=$tab_id?>',
						width: 30
					},{
						xtype: 'label',
						text: '<',
						margins: '0 5 0 5'
					},{
						id: "40med<?=$tab_id?>",
						xtype: 'textfield',
						name: "40MED",
						fieldLabel: '40med<?=$tab_id?>',
						width: 30
					},{
						xtype: 'label',
						text: '<',
						margins: '0 5 0 5'
					},{
						id: "40medhigh<?=$tab_id?>",
						xtype: 'textfield',
						name: "40MEDHIGH",
						fieldLabel: '40medhigh<?=$tab_id?>',
						width: 30
					},{
						xtype: 'label',
						text: '<',
						margins: '0 5 0 5'
					},{
						id: "40hgh<?=$tab_id?>",
						xtype: 'textfield',
						name: "40HGH",
						fieldLabel: '40hgh<?=$tab_id?>',
						width: 30
					}]
				},{
					xtype: 'button',
					text: 'Save',
					margin: '0 0 10 280',
					handler : function() {
						if (this.up('form').getForm().isValid()){
							var category_name = this.up('form').getForm().findField("pa_weightcat<?=$tab_id?>").getValue();
							var min20=this.up('form').getForm().findField("20min<?=$tab_id?>").getValue();
							var med20=this.up('form').getForm().findField("20med<?=$tab_id?>").getValue();
							var medhigh20=this.up('form').getForm().findField("20medhigh<?=$tab_id?>").getValue();
							var hgh20=this.up('form').getForm().findField("20hgh<?=$tab_id?>").getValue();
							var min40=this.up('form').getForm().findField("40min<?=$tab_id?>").getValue();
							var med40=this.up('form').getForm().findField("40med<?=$tab_id?>").getValue();
							var medhigh40=this.up('form').getForm().findField("40medhigh<?=$tab_id?>").getValue();
							var hgh40=this.up('form').getForm().findField("40hgh<?=$tab_id?>").getValue();
							var paramny=min20+'^'+med20+'^'+medhigh20+'^'+hgh20+'^'+min40+'^'+med40+'^'+medhigh40+'^'+hgh40;
							
							if (min20 != '' || min40 != ''){
								//loadmask.show();
								 Ext.Ajax.request({
									url: '<?=controller_?>yard_planning/insert_masterweight/',
									params: {name: category_name, param: paramny },
									success: function(response){
										var text = response.responseText;
										if (text='1'){
											Ext.MessageBox.show({
												title: 'Success',
												msg: 'New weight category inserted.',
												buttons: Ext.MessageBox.OK
											});
											store3.reload();
										}else{
											Ext.MessageBox.show({
												title: 'Error',
												msg: 'Failed to save.',
												buttons: Ext.MessageBox.OK
											});
										}
										//loadmask.hide();
									}
								});
							}else{
								Ext.MessageBox.show({
									title: 'Warning',
									msg: 'Weight specification must be inserted.',
									buttons: Ext.MessageBox.OK
								});
							}
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
</script>
