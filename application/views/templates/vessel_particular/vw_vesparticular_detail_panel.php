<!-- // Kapal <?=$ID_VESSEL?> -->

<script type="text/javascript">
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
		id: "vessel_particular_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		items: [{
			xtype: 'container',
			anchor: '100%',
			layout: 'hbox',
			items:[{
				xtype: 'container',
				flex: 1,
				layout: 'anchor',
				items: [{
					xtype:'displayfield',
					fieldLabel: 'Vessel Code',
					name: 'ID_VESSEL',
					anchor:'95%'
				}, {
					xtype:'textfield',
					id: "callsign_<?=$tab_id?>",
					fieldLabel: 'Call Sign',
					name: 'CALL_SIGN',
					afterLabelTextTpl: required,
					allowBlank: false,
					anchor:'95%'
				}, {
					xtype:'combo',
					id: "opr_<?=$tab_id?>",
					name: 'OPERATOR',
					displayField: 'ID_OPERATOR',
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
					fieldLabel: 'Vessel Name',
					name: 'VESSEL_NAME',
					afterLabelTextTpl: required,
					allowBlank: false,
					anchor:'95%'
				},{
					xtype: 'combo',
					id: "countrycode_<?=$tab_id?>",
					displayField: 'PORT_COUNTRY',
					valueField: 'PORT_COUNTRY',
					fieldLabel: 'Country Code',
					name: 'COUNTRY_CODE',
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
				name: 'GROSS_TONAGE',
				id: 'gross_<?=$tab_id?>'
			}, {
				fieldLabel: 'Net Tonnage',
				name: 'NET_TONAGE',
				id: 'net_<?=$tab_id?>'
			}, {
				fieldLabel: 'Hatch Cover',
				name: 'HATCH_COVER',
				id: 'ht_<?=$tab_id?>'
			}, {
				fieldLabel: 'Length (m)',
				name: 'LENGTH',
				id: 'length_<?=$tab_id?>'
			}, {
				fieldLabel: 'Depth (m)',
				name: 'DEPTH',
				id: 'depth_<?=$tab_id?>'
			}, {
				fieldLabel: 'Draft (m)',
				name: 'MAX_DRAFT',
				id: 'draft_<?=$tab_id?>'
			}]
        }],
		buttons: [{
			text: 'Update',
			formBind: true,
			handler: function() {
				if (this.up('form').getForm().isValid()){
					Ext.getCmp('<?=$tab_id?>').getLoader().load({
						url: '<?=controller_?>vessel_particular/update_vesparticular',
						params: {vescode: '<?=$ID_VESSEL?>', 
								 callsg: Ext.getCmp('callsign_<?=$tab_id?>').getRawValue(), 
								 opr: Ext.getCmp('opr_<?=$tab_id?>').getRawValue(), 
								 vesnm: Ext.getCmp('vesselnm_<?=$tab_id?>').getRawValue(),
								 countrycd: Ext.getCmp('countrycode_<?=$tab_id?>').getRawValue(),
								 grt: Ext.getCmp('gross_<?=$tab_id?>').getRawValue(),
								 net: Ext.getCmp('net_<?=$tab_id?>').getRawValue(),
								 ht: Ext.getCmp('ht_<?=$tab_id?>').getRawValue(),
								 lng: Ext.getCmp('length_<?=$tab_id?>').getRawValue(),
								 dpt: Ext.getCmp('depth_<?=$tab_id?>').getRawValue(),
								 dft: Ext.getCmp('draft_<?=$tab_id?>').getRawValue()/*,

								 tk: Ext.getCmp('TKTrue<?=$tab_id?>').getRawValue()*/
								},
						scripts: true,
						contentType: 'html',
						autoLoad: true
					});
					Ext.getCmp('<?=$tab_id?>').close();
				}
			}
		}]
	}).render('vessel_particular_<?=$tab_id?>');

	// console.log(JSON.parse('<?=$vessel_detail?>'));
	Ext.getCmp('vessel_particular_form_<?=$tab_id?>').getForm().setValues(JSON.parse('<?=$vessel_detail?>'));
});

</script>
<div id="vessel_particular_<?=$tab_id?>"></div>