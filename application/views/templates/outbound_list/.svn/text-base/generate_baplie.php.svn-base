<script type="text/javascript">
	Ext.onReady(function(){
		Ext.create('Ext.form.Panel', {
			id: "generate_baplie_form_<?=$tab_id?>",
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			width: 500,
			url: '<?=controller_?>outbound_list/generate_baplie_export',
			items: [{
				xtype: 'hiddenfield',
				name: 'ID_VES_VOYAGE',
				value: '<?=$id_ves_voyage?>'
			},{
				xtype: 'fieldset',
				title: 'Generate Baplie Outbound',
				defaults: {
					anchor: '100%'
				},
				items: [{
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
							 {ID: 'xls', NAME: 'XLS'},
							 {ID: 'pdf', NAME: 'PDF'},
							 {ID: 'edifact', NAME: 'EDIFACT'}
						 ]
					}),
					value: 'xls'
				}]
			}],
			buttons: [{
				text: 'Generate',
				formBind: true,
				handler: function() {
					var form = this.up('form').getForm();
					var typeFilenya=this.up('form').getForm().findField("type_<?=$tab_id?>").getValue();
					if (form.isValid()){
						loadmask.show();
						form.submit({
							success: function(form, action) {
								loadmask.hide();
								if (typeFilenya=='edifact'){
									Ext.Msg.alert('Success', 'success Generate');
									addTab('center_panel', 'outbound_list/save_baplie_page', action.result.errors, 'Save Baplie Outbound');
								}else{
									window.open('<?=controller_?>outbound_list/print_baplie/<?=$id_ves_voyage?>/'+typeFilenya,'_blank');
								}
							},
							failure: function(form, action) {
								loadmask.hide();
								Ext.Msg.alert('Failed', action.result.errors);
							}
						});
					}
				}
			}]
		}).render('generate_baplie_<?=$tab_id?>');
	});
</script>
<div id="generate_baplie_<?=$tab_id?>"></div>