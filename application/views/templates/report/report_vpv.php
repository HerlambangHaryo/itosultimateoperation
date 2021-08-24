<script type="text/javascript">
	var vessel_list_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['TAHUN'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>vessel_production_volume/data_vessel_year/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
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
			title: 'Choose Year',
			items: [
			{
				id: "vessel_<?=$tab_id?>",
				xtype: 'combo',
				width: 500,
				displayField: 'TAHUN',
				valueField: 'TAHUN',
				store: vessel_list_store_<?=$tab_id?>,
				queryMode: 'remote',
				forceSelection: true,
				hideTrigger: true,
				triggerAction: 'query',
				emptyText: 'Autocomplete',
				typeAhead: true,
				minChars: 3,
				name: "TAHUN",
				fieldLabel: 'Year',
				//allowBlank: false
			}
			]
		},

		],
		buttons: [{
			text: 'Show',
			//formBind: true,
			handler: function() {
				var form = this.up('form').getForm();
				var year = form.findField("TAHUN").getValue();
				
				if (form.isValid()){
					loadmask.show();
					Ext.Ajax.request({
						url: '<?=controller_?>vessel_production_volume/get_data_vpv_show',
					params: {
						year: year
					},
					success: function(response){
						loadmask.hide();
						if(response.status=='200'){
							$('#reportvpv_<?=$tab_id?>').html(response.responseText);
						}else{
							Ext.Msg.alert('Failed');
						}
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
				var year = form.findField("TAHUN").getValue();
				
				if (form.isValid()){
					//loadmask.show();
					var url = '<?=controller_?>vessel_production_volume/get_data_vpv?year='+year;
					window.open(url,'_blank');
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
<div id="reportvpv_<?=$tab_id?>"></div>

<style>
div#reportvpv_<?=$tab_id?> {
    padding: 10px;
}

div#reportvpv_<?=$tab_id?> table {
    border-collapse: collapse;
}

div#reportvpv_<?=$tab_id?> .CenterAndMiddle{
	text-align: center;
	vertical-align:middle;
}

div#reportvpv_<?=$tab_id?> table, div#reportvpv_<?=$tab_id?> td, div#reportvpv_<?=$tab_id?> th {  
  border: 1px solid #ddd;
  text-align: left;
}

div#reportvpv_<?=$tab_id?> th, div#reportvpv_<?=$tab_id?> td {
  padding: 7px;
    white-space: nowrap;
}

div#reportvpv_<?=$tab_id?> .spacer{
	padding-bottom: 30px;
}

div#reportvpv_<?=$tab_id?> .abu-abu
{
	background-color: #303030 !important;
	color: #ffffff !important;
}

div#reportvpv_<?=$tab_id?> .abu-terang
{
	background-color: #aaaaaa !important;
	font-weight: bold !important;
}
</style>