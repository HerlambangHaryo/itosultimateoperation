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
	
	var axleCont<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['WEIGHT_ASSUMPTION', 'AXLE_SIZE'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>gate_operation/data_axle/',
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
				fieldLabel: 'Truck Loosing',
				name: 'TL_FLAG',
				readOnly: true
			},{
				xtype: 'field',
				fieldLabel: 'ESY',
				name: 'ITT_FLAG',
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
				readOnly: true,
				allowBlank: false
			},{
				xtype: 'field',
				fieldLabel: 'Seal Number',
				name: 'SEAL_ID',
				fieldStyle: 'background-color: #ffffcc; background-image: none;',
				readOnly: true
			},{
				xtype: 'hiddenfield',
				name: 'ID_AXLE',
				readOnly: true
			},{
				id: 'axleCont<?=$tab_id?>',
				xtype: 'combo',
				name: "AXLE_SIZE",
				fieldLabel: 'Axle Truck',
				width: 220,
				store: axleCont<?=$tab_id?>,
				queryMode: 'local',
				readOnly: true,
				valueField: 'WEIGHT_ASSUMPTION',
				displayField: 'AXLE_SIZE',
				fieldStyle: 'background-color: #ffffcc; background-image: none;',
				listeners : {
					            select: function(combo, selection) {
					                if (combo.getValue()) {
					        			//alert(combo.getValue());
					        			Ext.getCmp('axle<?=$tab_id?>').setRawValue(combo.getValue());
					                }
					            }
					        }
			  },{
				xtype: 'fieldcontainer',
				fieldLabel: 'Combo',
				defaultType: 'checkboxfield',
				items: [
					{
						id        : 'checkboxcomboweight<?=$tab_id?>'
					}
				]
			  },{
				xtype: 'fieldcontainer',

				fieldLabel: 'Weight',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				items: [{
							id: 'bruto<?=$tab_id?>',
							xtype: 'field',
							name: "bruto",
							width: 80,
                                                        minChars: 4,
							margin: '0 10 0 0' ,
							fieldStyle: 'background-color: #ffffcc; background-image: none;'
						},{

			                xtype: 'label',
			                text: '-',
			                name: '-',
			                margins: '0 10 0 0'

			            },{
							id: 'axle<?=$tab_id?>',
							xtype: 'field',
							name: "axle",
							width: 80,
							margin: '0 10 0 0',
							readOnly: true,
							fieldStyle: 'background-color: #ffffcc; background-image: none;'
						},{

			                xtype: 'label',
			                text: '=',
			                name: '=',
			                margins: '0 10 0 0'

			            },{
					    id: 'netto<?=$tab_id?>',
					    xtype: 'field',
					    name: "NETTO",
					    width: 80,
					    margin: '0 10 0 0',
					    readOnly: true,
					    fieldStyle: 'background-color: #ffffcc; background-image: none;'
				    },{
					    id: 'buttonAxle<?=$tab_id?>',
					    xtype: 'button',
					    text: "Calculate",
					    margin: '0 10 0 0',
					    handler: function () {
											    //alert('test');
							    var wgtBruto = Ext.getCmp('bruto<?=$tab_id?>').getRawValue();
							    var wgtAxle = Ext.getCmp('axle<?=$tab_id?>').getRawValue();
								var cc = Ext.getCmp('checkboxcomboweight<?=$tab_id?>').getRawValue();
							    var wgtNetto = wgtBruto-wgtAxle;
								if(cc==true){
									wgtNetto=wgtNetto/2;
								}
//                                                            alert('length : ' +wgtBruto.length);
                                                            if(wgtNetto < 0){
                                                                alert('Weight netto cannot less than 0. Please check again.');
                                                            }
                                                            Ext.getCmp('netto<?=$tab_id?>').setRawValue(wgtNetto);
                                            }
				    }]
			  },{
				xtype: 'hiddenfield',
				name: 'ID_DAMAGE'
			},{
				id: 'damageCont<?=$tab_id?>',
//				xtype: 'combo',
				xtype: 'textfield',
				name: "DAMAGE",
				fieldLabel: 'Damage',
				width: 400,
//				store: damageCont<?=$tab_id?>,
//				queryMode: 'local',
//				valueField: 'ID_DAMAGE',
//				displayField: 'DAMAGE',
//				readOnly: true,
				fieldStyle: 'background-color: #ffffcc; background-image: none;'
			  },{
				xtype: 'hiddenfield',
				name: 'ID_DAMAGE_LOCATION'
			}
			  ,{
				id: 'damageContLoc<?=$tab_id?>',
//				xtype: 'combo',
				xtype: 'textfield',
				name: "DAMAGE_LOCATION",
				fieldLabel: 'Damage Location',
				width: 400,
//				store: damageContLocation<?=$tab_id?>,
//				queryMode: 'local',
//				valueField: 'ID_DAMAGE_LOCATION',
//				displayField: 'DAMAGE_LOCATION',
//				readOnly: true,
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
                                                var wgtNetto = form.findField("netto<?=$tab_id?>").getValue();
						if (form.isValid()){
                                                    if(wgtNetto < 0){
                                                        alert('Weight netto cannot less than 0. Please check again.');
                                                    }else{
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
									else if(trjobct=='TRUCK OUT ESY')
									{
										remarkscard='EIR - ESY';
										converttrjob='TOE';
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

//									console.log(action.result.errors);
									
									var data = JSON.parse(action.result.data);
									var form2=Ext.getCmp('container_search_form_<?=$tab_id?>').getForm();
									var recdel=form2.findField("typeRecDel").getValue();//typeRecDel
									var tpinOutGate=form2.findField("typeInOut").getValue();//typeRecDelconsole.log('data');
//									console.log(data);
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
									Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().setValues(data);
									Ext.getCmp('axle<?=$tab_id?>').setRawValue(data.AXLE);
									if(tpinOutGate == 'IN' && recdel == 'REC'){
									    Ext.getCmp('bruto<?=$tab_id?>').setReadOnly(false);
//									    Ext.getCmp('bruto<?=$tab_id?>').getEl().dom.removeAttribute('readOnly');
									    Ext.getCmp('bruto<?=$tab_id?>').focus(true);
									    Ext.getCmp('buttonAxle<?=$tab_id?>').setDisabled(false);
									}else{
									    Ext.getCmp('bruto<?=$tab_id?>').setReadOnly(true);
									    Ext.getCmp('buttonAxle<?=$tab_id?>').setDisabled(true);
									    console.log('masuk else');
//									    Ext.getCmp('bruto<?=$tab_id?>').getEl().dom.setAttribute('readOnly', true);
									}
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