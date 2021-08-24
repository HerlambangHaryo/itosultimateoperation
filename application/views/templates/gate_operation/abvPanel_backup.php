<script type="text/javascript">

	var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
	
	var container_list_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['NO_CONTAINERX', 'CONT_INFO'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>gate_operation/data_containernya/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var container_inquiry_point_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['POINT'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>container_inquiry/list_of_point/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var damageCont<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_DAMAGE', 'DAMAGE'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>gate_operation/data_dmg/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var damageContLocation<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_DAMAGE_LOCATION', 'DAMAGE_LOCATION'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>gate_operation/data_dmgLoc/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var inOutGate = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        data: [{
            "id": "IN",
            "name": "IN"
        }, {
            "id": "OUT",
            "name": "OUT"
        }]
    });
	
	var recDelGate = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        data: [{
            "id": "REC",
            "name": "Receiving"
        }, {
            "id": "DEL",
            "name": "Delivery"
        }]
    });
	
	Ext.create('Ext.form.Panel', {
		id: "container_data_form_<?=$tab_id?>",
		url: '<?=controller_?>gate_operation/saveGate',
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		items: [{
			xtype: 'fieldset',
			title: 'Container Spec',
			items: [{
				xtype: 'field',
				fieldLabel: 'No Container',
				name: 'NO_CONTAINER',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'Point',
				name: 'POINT',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'ISO',
				name: 'ID_ISO_CODE',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'ID VES VOYAGE',
				name: 'ID_VES_VOYAGE',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'Vessel (Voyage)',
				name: 'VESSEL_VOYAGE',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'Class Code',
				name: 'EI',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'Truck In date',
				name: 'TRINDATE',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'Truck Out date',
				name: 'TROTDATE',
				readOnly: true
			},{
				xtype: 'textfield',
				fieldLabel: 'Job Truck',
				name: 'TR_JOB',
				readOnly: true,
				allowBlank: false
			}]
		},{
			xtype: 'fieldset',
			title: 'Gate Content',
			items: [{
				id: 'truck_number_<?=$tab_id?>',
				xtype: 'textfield',
				fieldLabel: 'Truck Number',
				name: 'TRUCK_NUMBER',
				fieldStyle: 'background-color: #ffffcc; background-image: none;',
				allowBlank: false
			},{
				xtype: 'field',
				fieldLabel: 'Seal Number',
				name: 'SEAL_ID',
				fieldStyle: 'background-color: #ffffcc; background-image: none;'
			},{
				xtype: 'field',
				fieldLabel: 'Weight',
				name: 'WEIGHT',
				fieldStyle: 'background-color: #ffffcc; background-image: none;'
			},{
				id: 'damageCont<?=$tab_id?>',
				xtype: 'combo',
				name: "damageCont",
				fieldLabel: 'Damage',
				width: 400,
				store: damageCont<?=$tab_id?>,
				queryMode: 'local',
				valueField: 'ID_DAMAGE',
				displayField: 'DAMAGE',
				fieldStyle: 'background-color: #ffffcc; background-image: none;'
			  }
			  ,{
				id: 'damageContLoc<?=$tab_id?>',
				xtype: 'combo',
				name: "damageContLoc",
				fieldLabel: 'Damage Location',
				width: 400,
				store: damageContLocation<?=$tab_id?>,
				queryMode: 'local',
				valueField: 'ID_DAMAGE_LOCATION',
				displayField: 'DAMAGE_LOCATION',
				fieldStyle: 'background-color: #ffffcc; background-image: none;'
			  }
			]	
		}],buttons: [{
			text: 'Save',
			id: 'saveGateButton<?=$tab_id?>',
			formBind: true,
			listeners: {
				click: {
					fn: function () {
						var form = this.up('form').getForm();
						var ct=form.findField("NO_CONTAINER").getValue();
						var pointct=form.findField("POINT").getValue();
						var trjobct=form.findField("TR_JOB").getValue();
						var form2=Ext.getCmp('container_search_form_<?=$tab_id?>').getForm();
						var recdel=form2.findField("typeRecDel").getValue();//typeRecDel
						var tpinOutGate=form2.findField("typeInOut").getValue();//typeRecDel
						var remarkscard='';
						var converttrjob='';
						if (form.isValid()){
							loadmask.show();
							form.submit({
								success: function(form, action) {
									loadmask.hide();
									console.log(action.result.errors);
									
									Ext.Msg.alert('Success', 'Container berhasil di Gate '+tpinOutGate, function(){
										Ext.getCmp('contInquiryGate<?=$tab_id?>').focus(true);
									});
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
									if(trjobct=='TRUCK IN RECEIVING'){
										remarkscard='CMS';
										converttrjob='TIR';
									}
									else if(trjobct=='TRUCK OUT RECEIVING')
									{
										remarkscard='EIR';
										converttrjob='TOR';
									}
									else if(trjobct=='TRUCK IN DELIVERY')
									{
										remarkscard='CMS';
										converttrjob='TID';
									}
									else if(trjobct=='TRUCK OUT DELIVERY')
									{
										remarkscard='EIR';
										converttrjob='TOD';
									}
									else if(trjobct=='CONTAINER WEIGHING')
									{
										remarkscard='CONTAINER WEIGHING';
										converttrjob='CW';
									}
									
									window.open('<?=controller_?>gate_operation/printCoba/'+remarkscard+'/'+recdel+'/'+ct+'/'+pointct+'/'+converttrjob,'_blank');
								},
								failure: function(form, action) {
									loadmask.hide();	
									console.log(action.result.errors);
									Ext.Msg.alert('Failed', action.result.errors);
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
									
								}
							});
						}
					}
				}
			}
		}]
	}).render('container_data_<?=$tab_id?>');
	
	/*panel diatas*/
	Ext.create('Ext.form.Panel', {
		id: "container_search_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		
		url: '<?=controller_?>gate_operation/data_container_inquiry',
		items: [{
			id: 'typeOfGate<?=$tab_id?>',
			xtype: 'combo',
			name: "typeInOut",
			fieldLabel: 'Gate',
			width: 200,
			store: inOutGate,
			queryMode: 'local',
			valueField: 'id',
			displayField: 'name',
			fieldStyle: 'background-color: #ffffcc; background-image: none;'
		},{
			id: 'recDelOfGate<?=$tab_id?>',
			xtype: 'combo',
			fieldLabel: 'Rec / Del',
			name: "typeRecDel",
			store: recDelGate,
            listeners: {
                change:
                    function(el, newValue, oldValue, eOpts){ 
                        var output = '';
                        if (newValue == 'REC'){
                            output = 'E';
                        }
                        else if (newValue == 'DEL') {
                            output = 'I';
                        }
                        container_list_store_<?=$tab_id?>.getProxy().extraParams = {ei: output};
                        Ext.getCmp('contInquiryGate<?=$tab_id?>').clearValue();
                        container_list_store_<?=$tab_id?>.reload();
                    }
            },
			queryMode: 'local',
			valueField: 'id',
			displayField: 'name',
			fieldStyle: 'background-color: #ffffcc; background-image: none;'
		},{
			xtype: 'combo',
			id: "contInquiryGate<?=$tab_id?>",
			name: "cont_inquiry",
			displayField: 'NO_CONTAINERX',
			valueField: 'CONT_INFO',
			fieldLabel: 'No. Container',
			afterLabelTextTpl: required,
			allowBlank: false,
			anchor:'40%',
			emptyText: 'Autocomplete',
			queryMode: 'remote',
			typeAhead: true,
			minChars: 4,
			triggerAction: 'query',
			store: container_list_store_<?=$tab_id?>,
			fieldStyle: 'background-color: #ffffcc; background-image: none;'
		}],
		buttons: [{
			text: 'Search',
			id: 'inquiry_button_<?=$tab_id?>',
			formBind: true,
			listeners: {
				click: {
					fn: function () {
						var form = this.up('form').getForm();
						if (form.isValid()){
							// loadmask.show();
							form.submit({
								success: function(form, action) {
									loadmask.hide();
									//Ext.Msg.alert('Found', action.result.errors);

									console.log(action.result.errors);
									
									var data = JSON.parse(action.result.data);
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().setValues(data);
									Ext.getCmp('truck_number_<?=$tab_id?>').focus(true);
								},
								failure: function(form, action) {
									loadmask.hide();
									
									console.log(action.result.errors);
									
									Ext.Msg.alert('Failed', action.result.errors);
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
									
								}
							});
						}
					}
				}
			}
		}]
	}).render('container_search_<?=$tab_id?>');
</script>
<div id="container_search_<?=$tab_id?>"></div>
<div id="container_data_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>