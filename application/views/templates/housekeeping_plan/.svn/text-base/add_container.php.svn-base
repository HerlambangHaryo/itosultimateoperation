<script type="text/javascript">	
function hk_search_cont(a,b)
{
	Ext.Ajax.request({
		url: '<?=controller_?>housekeeping_plan/srch_cont_hk/'+a+'/'+b,
		scope: this,
		success: function(response, request){
			//$("#popup_add_conthk_<?=$tab_id?>").html(response.responseText);
			console.log(response);
		}
	});
}

$(function() {
	var ei_hk = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        data: [{
            "id": "E",
            "name": "E"
        }, {
            "id": "I",
            "name": "I"
        }]
    });
	
	var ct_store3 = Ext.create('Ext.data.Store', {
		fields:['HKP_ID', 'NO_CONTAINER', 'ID_ISO_CODE', 'ID_VES_VOYAGE', 'HKP_STATUS_CONT', 'ACTION'],
		autoLoad: true,
		remoteSort: true,
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>housekeeping_plan/load_hkplan_gridcont/<?=$hkp_id?>',
			reader: {
				type: 'json',
				root: 'data',
				totalProperty: 'total'
			}
		},
		pageSize: 100,
		sorters: [{
			property: 'HKP_ID',
			direction: 'ASC'
		}]
	});
	
	
	var win9add = new Ext.Window({
		layout: 'fit',
		title: 'Add Container',
		//closable: true,
		modal: true,
		width:660,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [
			{
				layout: 'hbox',
				xtype: 'fieldset',
				title: 'Container Info',
				items: [{
						id: "no_container_hk<?=$tab_id?>",
						xtype: 'textfield',
						labelWidth: 250,
						name: "no_container_hk<?=$tab_id?>",
						fieldLabel: 'Search CONTAINER NUMBER',
						allowBlank: false
						},
						{
						id: "ei_hk<?=$tab_id?>",
						xtype: 'combo',
						labelWidth: 50,
						width:50,
						store:ei_hk,
						queryMode: 'local',
						valueField: 'id',
						displayField: 'name',
						name: "ei_hk<?=$tab_id?>",
						allowBlank: false
						},
						{
							id: "button_hk<?=$tab_id?>",
							xtype: 'button',
							name: "button_hk<?=$tab_id?>",
							allowBlank: false,
							text:'Search',
							handler:
								function (){
									Ext.Ajax.request({
										url: '<?=controller_?>housekeeping_plan/srch_cont_hk/',
										params: {
											no_container: Ext.getCmp("no_container_hk<?=$tab_id?>").getValue(),
											ei: Ext.getCmp("ei_hk<?=$tab_id?>").getValue()
											
										},
										success: function(response, request){
											var data=JSON.parse(response.responseText);
											console.log(response);
											
											Ext.getCmp("no_container<?=$tab_id?>").setValue(data.NO_CONTAINER);
											Ext.getCmp("iso_code<?=$tab_id?>").setValue(data.ID_ISO_CODE);
											Ext.getCmp("point<?=$tab_id?>").setValue(data.POINT);
											Ext.getCmp("id_vvd<?=$tab_id?>").setValue(data.ID_VES_VOYAGE);
										} 
									});
								}
							
						}]
			},
			{
				id: "no_container<?=$tab_id?>",
				xtype: 'textfield',
				name: "no_container<?=$tab_id?>",
				fieldLabel: 'NO CONTAINER',
				allowBlank: false
			},
			{
				id: "iso_code<?=$tab_id?>",
				xtype: 'textfield',
				name: "iso_code<?=$tab_id?>",
				fieldLabel: 'ISO CODE',
				allowBlank: false
			},
			{
				id: "point<?=$tab_id?>",
				xtype: 'textfield',
				name: "point<?=$tab_id?>",
				fieldLabel: 'POINT',
				allowBlank: false
			},
			{
				id: "id_vvd<?=$tab_id?>",
				xtype: 'textfield',
				name: "id_vvd<?=$tab_id?>",
				fieldLabel: 'VVD',
				allowBlank: false
			}
			],
			
			buttons: [{
				text: 'Add',
				formBind: true,
				handler: function() {
					if (this.up('form').getForm().isValid()){
						var no_container_hk = this.up('form').getForm().findField("no_container_hk<?=$tab_id?>").getValue();
						var point_hk = this.up('form').getForm().findField("point<?=$tab_id?>").getValue();
						//loadmask.show();
						Ext.Ajax.request({
							url: '<?=controller_?>housekeeping_plan/insert_container_hk/',
							params: {no_container: no_container_hk, point: point_hk,hkp_id:'<?=$hkp_id?>'},
							success: function(response){
								var text = response.responseText;
								if (text!='0'){
									//loadmask.hide();
									Ext.MessageBox.show({
										title: 'Success',
										msg: 'Container Success Added',
										buttons: Ext.MessageBox.OK,
										fn:function(btn) {
											Ext.getStore('cont_hk_grid<?=$tab_id?>').reload();
										}
									});
								}else{
									//loadmask.hide();
									Ext.MessageBox.show({
										title: 'Error',
										msg: 'Failed to add Container',
										buttons: Ext.MessageBox.OK
									});
								}
							}
						});
						win9add.close();
					}
				}
			},{
				text: 'Cancel',
				handler: function() {
					win9add.close();
				}
			}]
		})]
	});
	win9add.show();	
});
</script>