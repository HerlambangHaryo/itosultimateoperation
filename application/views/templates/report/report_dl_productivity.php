<script type="text/javascript">
	var vessel_list_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_VES_VOYAGE', 'VESSEL'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>report_dl_productivity/data_vesselvoyage_list/',
			reader: {
				type: 'json'
			}
		}
		<?php if($mode=='edit'){?>
		,
		autoLoad: true
		<?php }?>
	});

	Ext.create('Ext.form.Panel', {
		id: "vessel_voyage_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 150
		},
		//url: '<?=controller_?>report_dl_productivity/save_vessel_voyage',
		items: [{
			xtype: 'fieldset',
			title: 'Choose Vessel',
			items: [{
				id: "vessel_<?=$tab_id?>",
				xtype: 'combo',
				width: 500,
				displayField: 'VESSEL',
				valueField: 'ID_VES_VOYAGE',
				store: vessel_list_store_<?=$tab_id?>,
				queryMode: 'remote',
				forceSelection: true,
				hideTrigger: true,
				triggerAction: 'query',
				emptyText: 'Autocomplete',
				typeAhead: true,
				minChars: 3,
				name: "ID_VES_VOYAGE",
				listeners: {
					select: function(el, record){
						Ext.getCmp('vesvoy_name_<?=$tab_id?>').setValue(el.getRawValue());
					}
				},
				fieldLabel: 'Vessel Voyage',
				//allowBlank: false
			}]
		}],
		buttons: [{
			text: 'Show',
			//formBind: true,
			handler: function() {
				var form = this.up('form').getForm();
				var vessel_voyage_id = form.findField("ID_VES_VOYAGE").getValue();
				
				if (form.isValid()){
					loadmask.show();
					Ext.Ajax.request({
						url: '<?=controller_?>report_dl_productivity/get_data_dl_productivity',
						params: {
							id_ves_voyage: vessel_voyage_id,
						},
						success: function(response){
							var text = response.responseText;
							loadmask.hide();
							Ext.Ajax.request({
								url: '<?=controller_?>report_dl_productivity/get_data_dl_productivity_show',
							params: {
								vessel_voyage_id: vessel_voyage_id
							},
							success: function(response){
								loadmask.hide();
								if(response.status=='200'){
									$('#reportdl_<?=$tab_id?>').html(response.responseText);
								}else{
									Ext.Msg.alert('Failed');
								}
							}
							});
							
						}
					});
				} else {
					Ext.Msg.alert('Failed', 'Form Tidak Valid');
				}
			}
		},{
			text: 'Export to Excel',
			//formBind: true,
			handler: function() {
				var form = this.up('form').getForm();
				var vessel_voyage_id = form.findField("ID_VES_VOYAGE").getValue();
				
				if (form.isValid()){
					loadmask.show();
					Ext.Ajax.request({
						url: '<?=controller_?>report_dl_productivity/get_data_dl_productivity',
						params: {
							id_ves_voyage: vessel_voyage_id,
						},
						success: function(response){
							var text = response.responseText;
							loadmask.hide();
							//alert (vessel_voyage_id);
							var url = '<?=controller_?>report_dl_productivity/get_data_dl_productivity?vessel_voyage_id='+vessel_voyage_id;
								window.open(url,'_blank');
							
						}
					});
				} else {
					Ext.Msg.alert('Failed', 'Form Tidak Valid');
				}
			}
		}]
	}).render('vessel_voyage_<?=$tab_id?>');
	
	$(document).ready(function (){
		
	});
</script>
<div id="vessel_voyage_<?=$tab_id?>"></div>
<div id="reportdl_<?=$tab_id?>"></div>

<style>
div#reportdl_<?=$tab_id?> {
    padding: 10px;
}

div#reportdl_<?=$tab_id?> table {
    border-collapse: collapse;
    white-space: nowrap;
}

div#reportdl_<?=$tab_id?> table td.tchild {
    padding: 0px;
    border-spacing: 0px;
}

div#reportdl_<?=$tab_id?> table td.tchild table {
    border: none;
}

div#reportdl_<?=$tab_id?> table td.tchild table tr.trchild th{
    border-top: none;
}

div#reportdl_<?=$tab_id?> table td.tchild table th{
    border-top: 1px solid;
}

div#reportdl_<?=$tab_id?> table td.tchild table td {
    border-top: 1px solid;
}

div#reportdl_<?=$tab_id?> table td.tchild table th:nth-child(2),
div#reportdl_<?=$tab_id?> table td.tchild table td:nth-child(2) {
    border-right: 1px solid;
    border-left: 1px solid;
}

div#reportdl_<?=$tab_id?> th,div#reportdl_<?=$tab_id?> td {
    padding: 10px;
}
</style>