<script type="text/javascript">
	
$(function() {
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Add Truck',
		closable: false,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			height: 130,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: "id_truck_<?=$tab_id?>",
				xtype: 'hiddenfield',
				name: 'ID_TRUCK'
			},{
				id: 'tid_<?=$tab_id?>',
				minLength: 5,
				maxLength: 5,
				fieldLabel: 'TID',
				xtype: 'textfield',
				allowBlank: false,
				titleError:'5 Characters',
				name: 'TID'
			},{
				id: 'no_pol_<?=$tab_id?>',
				fieldLabel: 'No Polisi',
				xtype: 'textfield',
				allowBlank: false,
				name: 'NO_POL'
			}],
			buttons: [{
				text: 'Save',
				formBind: true,
				handler: function() {
					var form = this.up('form').getForm();
					if (form.isValid()){
						loadmask.show();
						Ext.Ajax.request({
							url: '<?=controller_?>truck/check_tid',
							params: {
								tid: form.findField("TID").getValue(),
								no_pol: form.findField("NO_POL").getValue()
							},
							success: function(response){
								var text = response.responseText;
								console.log('text : ' + text);
								if (text=='0'){
									Ext.Ajax.request({
									    url: '<?=controller_?>truck/save_truck',
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
										    truck_store.reload();
										}
									    },
									    failure:function(form, response) {
										Ext.Msg.alert('Failed: ', response.errorMessage);
									    }
									})
								}else if (text=='1'){
									loadmask.hide();
									Ext.MessageBox.show({
										title: 'Error',
										msg: 'TID ' + form.findField("TID").getValue() + ' Already Exist.',
										buttons: Ext.MessageBox.OK
									});
								}else{
									loadmask.hide();
									Ext.MessageBox.show({
										title: 'Error',
										msg: 'No Polisi '+ form.findField("NO_POL").getValue() + ' Already Exist.',
										buttons: Ext.MessageBox.OK
									});
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
});
Ext.DomHelper.insertAfter('tid_<?=$tab_id?>', {tag:'span', html:'<span style="color:red;font-style: italic;font-size:10px;">* Fill this field with 5 Character</span>'});

$("#tid_<?=$tab_id?>").keydown(function (e) {
     if (e.keyCode == 32) { 
//       $(this).val($(this).val() + "-"); // append '-' to input
       return false; // return false to prevent space from being added
     }
});

$("#no_pol_<?=$tab_id?>").keydown(function (e) {
     if (e.keyCode == 32) { 
//       $(this).val($(this).val() + "-"); // append '-' to input
       return false; // return false to prevent space from being added
     }
});
</script>