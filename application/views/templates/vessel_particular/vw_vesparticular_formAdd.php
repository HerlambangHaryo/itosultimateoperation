<!-- // Kapal <?=$ID_VESSEL?> -->

<script type="text/javascript">
function cekStr()
{

}

Ext.onReady(function(){
	var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
	
	var country_list_store = Ext.create('Ext.data.Store', {
			fields:['PORT_COUNTRY'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>vessel_particular/data_country/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});

	var operator_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_OPERATOR', 'OPERATOR_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>vessel_particular/data_operator/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
	});
	
	Ext.create('Ext.form.Panel', {
		id: "vessel_particular_formadd_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		url: '<?=controller_?>vessel_particular/add_vesparticular?tab_id=<?=$tab_id?>',
		items: [{
			xtype: 'container',
			anchor: '100%',
			layout: 'hbox',
			items:[{
				xtype: 'container',
				flex: 1,
				layout: 'anchor',
				items: [{
					xtype:'textfield',
					id: "vesselcode_<?=$tab_id?>",
					name: "VESSEL_CODE",
					fieldLabel: 'Vessel Code',
					afterLabelTextTpl: required,
					allowBlank: false,
					anchor:'95%'
				}, {
					xtype:'textfield',
					id: "callsign_<?=$tab_id?>",
					name: "CALL_SIGN",
					fieldLabel: 'Call Sign',
					afterLabelTextTpl: required,
					allowBlank: false,
					anchor:'95%'
				}, {
					xtype:'combo',
					id: "opr_<?=$tab_id?>",
					name: "OPERATOR",
					displayField: 'OPERATOR_NAME',
					valueField: 'ID_OPERATOR',
					fieldLabel: 'Operator',
					afterLabelTextTpl: required,
					allowBlank: false,
					anchor:'95%',
					emptyText: 'Autocomplete',
					queryMode: 'remote',
					typeAhead: true,
					minChars: 3,
					triggerAction: 'query',
					store: operator_list_store
				}/*, {
					xtype:'fieldcontainer',
					id: "tk_<?=$tab_id?>",
					fieldLabel: 'Small Vessel',
					defaultType: 'radiofield',
					anchor:'95%',
					defaults:{
						flex:1
					},
					layout:'hbox',
					items:[
						{
							name 	  : "FL_SMALL_VESSEL",
							boxLabel  : 'Yes',
		                    inputValue: 'Y',
		                    id        : 'TKTrue<?=$tab_id?>'
						},
						{
							name 	  : "FL_SMALL_VESSEL",
							boxLabel  : 'No',
		                    inputValue: 'N',
		                    id        : 'TKFalse<?=$tab_id?>'
						}
					]
				}*/]
			},{
				xtype: 'container',
				flex: 1,
				layout: 'anchor',
				items: [{
					xtype:'textfield',
					id: "vesselnm_<?=$tab_id?>",
					name: "VESSEL_NAME",
					fieldLabel: 'Vessel Name',
					afterLabelTextTpl: required,
					allowBlank: false,
					anchor:'95%'
				},{
					xtype: 'combo',
					id: "countrycode_<?=$tab_id?>",
					name: "COUNTRY_CODE",
					displayField: 'PORT_COUNTRY',
					valueField: 'PORT_COUNTRY',
					fieldLabel: 'Country Code',
					afterLabelTextTpl: required,
					allowBlank: false,
					anchor:'95%',
					emptyText: 'Autocomplete',
					queryMode: 'remote',
					typeAhead: true,
					minChars: 1,
					triggerAction: 'query',
					store: country_list_store
				}]
			}]
		},{
			xtype:'fieldset',
			width: 550,
			title: 'Specification',
			collapsible: true,
			defaultType: 'textfield',
			layout: 'anchor',
			defaults: {
				anchor: '80%'
			},
			items :[{
				fieldLabel: 'Gross Tonnage',
				id: 'gross_<?=$tab_id?>',
//				emptyText: 'Penulisan Desimal Menggunakan (.)',
				name: "GROSS"
			}, {
				fieldLabel: 'Net Tonnage',
//				emptyText: 'Penulisan Desimal Menggunakan (.)',
				id: 'net_<?=$tab_id?>',
				name: "NET"
			}, {
				fieldLabel: 'Hatch Cover',
//				emptyText: 'Penulisan Desimal Menggunakan (.)',
				id: 'ht_<?=$tab_id?>',
				name: "HATCH"
			}, {
				fieldLabel: 'Length (m)',
//				emptyText: 'Penulisan Desimal Menggunakan (.)',
				id: 'length_<?=$tab_id?>',
				name: "LENGTH"
			}, {
				fieldLabel: 'Depth (m)',
//				emptyText: 'Penulisan Desimal Menggunakan (.)',
				id: 'depth_<?=$tab_id?>',
				name: "DEPTH"
			}, {
				fieldLabel: 'Draft (m)',
//				emptyText: 'Penulisan Desimal Menggunakan (.)',
				id: 'draft_<?=$tab_id?>',
				name: "DRAFT"
			}]
        }],
		buttons: [{
			text: 'Save',
			formBind: true,

			handler: function() {
				var form = this.up('form').getForm();
				var vesselNm=this.up('form').getForm().findField("vesselnm_<?=$tab_id?>").getValue();
				if(vesselNm.indexOf("-")>-1)
				{
					Ext.Msg.alert('Failed', 'Vessel Name tidak boleh menggunakan special character (-)');	
				}
				else
				{
					if (form.isValid()){
						loadmask.show();
						form.submit({
						success: function(form, action) {
							loadmask.hide();
							Ext.Msg.alert('Info', action.result.errors);
							console.log(action);
							console.log(action.result.errors);
							Ext.getCmp('<?=$tab_id?>').close();
						},
						failure: function(form, action) {
							loadmask.hide();
							Ext.Msg.alert('Info', action.result.errors);
							console.log(action.result.errors);
							Ext.getCmp('<?=$tab_id?>').close();
						}
						});	
					}
				}
			}
		}]
	}).render('vessel_particularAdd_<?=$tab_id?>');
	
	Ext.DomHelper.insertAfter('draft_<?=$tab_id?>', {tag:'span', html:' NB : Penulisan Desimal Menggunakan (.)'});
});
</script>
<div id="vessel_particularAdd_<?=$tab_id?>"></div>