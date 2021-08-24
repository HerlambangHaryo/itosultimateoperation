<script type="text/javascript">
$(function() {
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Rename Category',
		closable: false,
		width: 350,
		items: Ext.create('Ext.form.Panel', {
			frame: true,
			autoScroll: true,
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100,
				anchor: '100%'
			},
			items: [{
				xtype: 'hidden',
				name: 'category_id',
				value: '<?=$category_id?>'
			},{
				xtype: 'textfield',
				name: 'category_name',
				fieldLabel: 'Category Name',
				value: '<?=$category_name?>',
				maskRe: /[\w\s]/,
				regex: /[\w\s]/,
				allowBlank: false
			}],
			buttons: [{
				text: 'Save Category',
				formBind: true,
				handler: function() {
					if (this.up('form').getForm().isValid()){
						var category_id = this.up('form').getForm().findField("category_id").getValue();
						var category_name = this.up('form').getForm().findField("category_name").getValue();
						loadmask.show();
						Ext.Ajax.request({
							url: '<?=controller_?>yard_planning/rename_category_plan/',
							params: {
								category_id: category_id,
								category_name: category_name
							},
							success: function(response){
								var text = response.responseText;
								if (text=='1'){
									Ext.MessageBox.show({
										title: 'Success',
										msg: 'Category renamed.',
										buttons: Ext.MessageBox.OK
									});
									Ext.getCmp('category_<?=$tab_id?>').getStore().load();
								}else{
									Ext.MessageBox.show({
										title: 'Error',
										msg: 'Failed to rename category.',
										buttons: Ext.MessageBox.OK
									});
								}
								loadmask.hide();
								win.close();
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
});
</script>