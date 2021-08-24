<script type="text/javascript">
	var vessel_list_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_VES_VOYAGE', 'VESSEL'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>report_bch/data_vesselvoyage_list/',
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
		//url: '<?=controller_?>report_bch/save_vessel_voyage',
		items: [
		// FIELD SELECT VESSEL
		{
			xtype: 'fieldset',
			title: 'Choose Vessel',
			items: [
			{
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
				fieldLabel: 'Vessel Voyage',
				//allowBlank: false
			}
			]
		},

		],
		buttons: [
			{
			text: 'Show',
			//formBind: true,
			handler: function() {
				var form = this.up('form').getForm();
				var vessel_voyage_id = form.findField("ID_VES_VOYAGE").getValue();
				
				if (form.isValid()){
					loadmask.show();
					Ext.Ajax.request({
						url: '<?=controller_?>report_bch/check_vessel_voyage',
						params: {
							id_ves_voyage: vessel_voyage_id
						},
						success: function(response){
							var text = response.responseText;
							if (text=='1' || (text=='0' && (vessel_voyage_id == '' || vessel_voyage_id == null)) ){ 
								Ext.Ajax.request({
									url: '<?=controller_?>report_bch/get_data_bch_show',
								params: {
									id_ves_voyage: vessel_voyage_id,
									tab_id: '<?=$tab_id?>'
								},
								success: function(response){
									loadmask.hide();
									if(response.status=='200'){
										$('#reportvessel_voyage_<?=$tab_id?>').html(response.responseText);
									}else{
										Ext.Msg.alert('Failed', 'Vessel Voyage Tidak Valid');
									}
								}
								});
							} else{
								Ext.Msg.alert('Failed', 'Vessel Voyage Tidak Valid');
							}
						}
					});
				} else {
					Ext.Msg.alert('Failed', 'Form Tidak Valid');
				}
			}
			}
			,{
			text: 'Export to PDF',
			//formBind: true,
			handler: function() {
				var form = this.up('form').getForm();
				var vessel_voyage_id = form.findField("ID_VES_VOYAGE").getValue();
				
				if (form.isValid()){
					loadmask.show();
					Ext.Ajax.request({
						url: '<?=controller_?>report_bch/check_vessel_voyage',
						params: {
							id_ves_voyage: vessel_voyage_id,
						},
						success: function(response){
							loadmask.hide();
							var text = response.responseText;
							if (text=='1' || (text=='0' && (vessel_voyage_id == '' || vessel_voyage_id == null)) ){ 
								var url = '<?=controller_?>report_bch/get_data_bch_pdf?id_ves_voyage='+vessel_voyage_id;
								window.open(url,'_blank');
							} else{
								Ext.Msg.alert('Failed', 'Vessel Voyage Tidak Valid');
							}
						}
					});
				} else {
					Ext.Msg.alert('Failed', 'Form Tidak Valid');
				}
			}
			}
			// ,{
			// text: 'Export to Excel',
			// formBind: true,
			// handler: function() {
				// var form = this.up('form').getForm();
				// var vessel_voyage_id = form.findField("ID_VES_VOYAGE").getValue();
				
				// if (form.isValid()){
					// loadmask.show();
					// Ext.Ajax.request({
						// url: '<?=controller_?>report_bch/check_vessel_voyage',
						// params: {
							// id_ves_voyage: vessel_voyage_id,
						// },
						// success: function(response){
							// var text = response.responseText;
							// loadmask.hide();
							// if (text=='1' || (text=='0' && (vessel_voyage_id == '' || vessel_voyage_id == null)) ){ 
							// report per vessel voyage or all vessel
								// var url = '<?=controller_?>report_bch/get_data_bch?id_ves_voyage='+vessel_voyage_id;
								// window.open(url,'_blank');
							// } else{
								// Ext.Msg.alert('Failed', 'Vessel Voyage Tidak Valid');
							// }
						// }
					// });
				// } else {
					// Ext.Msg.alert('Failed', 'Form Tidak Valid');
				// }
			// }
			// }
		]
	}).render('vessel_voyage_<?=$tab_id?>');
	
	$(document).ready(function (){
		
	});
</script>
<div id="vessel_voyage_<?=$tab_id?>"></div>
<br/>
<br/>
<br/>
<div id="reportvessel_voyage_<?=$tab_id?>"></div>