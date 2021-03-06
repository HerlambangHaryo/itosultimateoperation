<script type="text/javascript">
	Ext.onReady(function(){
		Ext.create('Ext.form.Panel', {
			id: "upload_baplie_form_<?=$tab_id?>",
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			width: 500,
			url: '<?=controller_?>inbound_list/upload_baplie_import',
			items: [{
				xtype: 'hiddenfield',
				name: 'ID_VES_VOYAGE',
				value: '<?=$id_ves_voyage?>'
			},{
				xtype: 'fieldset',
				title: 'Upload Baplie Inbound',
				defaults: {
					anchor: '100%'
				},
				items: [{
					id: "file_<?=$tab_id?>",
					xtype: 'filefield',
					name: "file",
					fieldLabel: 'File',
					emptyText: 'Select file',
					allowBlank: false
				},{
					id: "type_<?=$tab_id?>",
					xtype: 'combo',
					name: 'typefile',
					fieldLabel: 'Type',
					displayField: 'NAME',
					valueField: 'ID',
					queryMode: 'local',
					editable: false,
					anchor: '50%',
					store: Ext.create('Ext.data.Store', {
						fields:['ID', 'NAME'],
						data : [
							 {ID: 'csv', NAME: 'CSV'},
							 {ID: 'edifact', NAME: 'EDIFACT'}
						 ]
					}),
					value: 'csv'
				},{
					id: "method_<?=$tab_id?>",
					xtype: 'combo',
					name: 'method',
					fieldLabel: 'Method',
					displayField: 'NAME',
					valueField: 'ID',
					queryMode: 'local',
					editable: false,
					anchor: '50%',
					store: Ext.create('Ext.data.Store', {
						fields:['ID', 'NAME'],
						data : [
							 {ID: 'overwrite', NAME: 'Overwrite'},
							 {ID: 'append', NAME: 'Append'}
						 ]
					}),
					value: 'overwrite'
				}]
			}],
			buttons: [{
				text: 'Upload',
				formBind: true,
				handler: function() {
					var form = this.up('form').getForm();
					if (form.isValid()){
						//alert('test');
						loadmask.show();
						form.submit({
							success: function(form, action) {
                                                            	loadmask.hide();
                                                                var jsonResp = JSON.parse(action.response.responseText);
                                                                var errorMsg = '';
                                                                if(jsonResp.errors != ''){
                                                                    errorMsg = 'with errors :<br>' + jsonResp.errors;
                                                                }
								Ext.Msg.alert('Success', 'upload success ' + errorMsg);
								Ext.getCmp('inbound_list_grid_<?=$tab_id?>').getStore().reload();
							},
							failure: function(form, action) {
								loadmask.hide();
								
//								 var jsonResp = Ext.util.JSON.decode(action.response.responseText);
//								 Ext.Msg.alert('Failed',jsonResp);
								
								Ext.Msg.alert('Failed', action.result.errors);
								
							}
						});
					}
				}
			}]
		}).render('upload_baplie_<?=$tab_id?>');
	});
</script>
<div id="upload_baplie_<?=$tab_id?>"></div>