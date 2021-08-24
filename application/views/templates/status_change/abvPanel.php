<script type="text/javascript">
	var outboundInbound = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        data: [{
            "id": "E",
            "name": "Outbound"
        }
		, {
            "id": "I",
            "name": "Inbound"
        }
		, {
            "id": "T",
            "name": "Transhipment"
        }
		]
    });
	var dataGlobal;
	var history_status_store=Ext.create('Ext.data.Store', {
		fields:['ID_OP_STATUS', 'OP_STATUS_DESC', 'DATE_HISTORY_CHAR'],
		autoLoad: true,
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>container_inquiry/history_status_detail',
			extraParams: {
				no_container: "",
				point: ""
			},
			reader: {
				type: 'json'
			}
		}
	});
	
	var contList<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_CONTCONV', 'NO_CONTAINER_EXP'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>status_change/data_container_inquiry/',
			extraParams: {
				cont_inquiry: "",
				inbOutb: ""
			},
			reader: {
				type: 'json'
			}
		}
		//,autoLoad: true
		});
	
	function refreshContList(a,b)
	{
		contList<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_CONTCONV', 'NO_CONTAINER_EXP'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>status_change/data_container_inquiry/',
			extraParams: {
				cont_inquiry: a,
				inbOutb: b
			},
			reader: {
				type: 'json'
			}
		}
		});
	}
	
	function refreshGrid(a)
	{
		var myarr = a.split("^");
		
		var nocont=myarr[0];
		var point=myarr[1];
		history_status_store = Ext.create('Ext.data.Store', 
		{
			fields:['ID_OP_STATUS', 'OP_STATUS_DESC', 'DATE_HISTORY_CHAR'],
			autoLoad: true,
			proxy: 
			{
				type: 'ajax',
				url: '<?=controller_?>status_change/history_status_detail',
				extraParams: {
					no_container:nocont,
					point: point
				},
				reader: {
					type: 'json'
				}
			}
		});
	}
	
	Ext.create('Ext.form.Panel', {
		id: "containerDetail_form<?=$tab_id?>",
		url: '<?=controller_?>status_change/saveChange',
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'right',
			labelWidth: 150
		},
		items: 
		[
			{
				items: [{
					xtype: 'fieldset',
					title: 'Situation Change',
					layout: 'hbox',
					items: 
					[
						{
							items:
							[
								{
									xtype: 'field',
									fieldLabel: 'Last Location',
									name: 'LOCATION_CHG',
									readOnly: true,
									width:400
								}
							]
						},
						{
							items:
							[
								{
									xtype: 'button',
									text : 'Cancel Situation',
									listeners: 
									{
										click: {
											fn: function () 
											{
												var form = this.up('form').getForm();
												
												loadmask.show();
												form.submit({
													success: function(form, action) {
														loadmask.hide();
														Ext.Msg.alert('Success', 'saved successfully');
														//Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
														Ext.getCmp("<?=$tab_id?>").close();
													},
													failure: function(form, action) {
														loadmask.hide();
														//var data2 = JSON.parse(action.result.errors);
														Ext.Msg.alert('Failed', action.result.errors);
														//Ext.getCmp('container_data_form_<?=$tab_id?>').getForm().reset();
														
													}
												});
												
											}
										}
									}
								}
							]
						}
					]
				}]
			},
			{
				items: [{
					xtype: 'fieldset',
					title: 'Container Information',
					layout: 'hbox',
					items: 
					[
						{
							items:
							[
								{
									xtype: 'field',
									fieldLabel: 'Container No.',
									name: 'NO_CONTAINER',
									readOnly: true
								}
								,{
									xtype: 'field',
									fieldLabel: 'Class Code',
									name: 'EI',
									readOnly: true
								},
								{
									xtype: 'field',
									fieldLabel: 'ISO',
									name: 'ID_ISO_CODE',
									readOnly: true
								},
								{
									xtype: 'field',
									fieldLabel: 'Full/Empty',
									name: 'FE',
									readOnly: true
								},
								{
									xtype: 'field',
									fieldLabel: 'Operator',
									name: 'OPERATOR',
									readOnly: true
								}
							]
						},
						{
							items:
							[
								{
									xtype: 'field',
									fieldLabel: 'POINT',
									name: 'POINTS',
									readOnly: true
								},{
									xtype: 'field',
									fieldLabel: 'ID VES VOYAGE',
									name: 'ID_VES_VOYAGE',
									readOnly: true
								},
								{
									xtype: 'field',
									fieldLabel: 'VESSEL',
									name: 'VESSEL_NAME',
									readOnly: true
								},
								{
									xtype: 'field',
									fieldLabel: 'VOYAGE',
									name: 'VOYAGE',
									readOnly: true
								},
								{
									xtype: 'field',
									fieldLabel: 'POD',
									name: 'POD',
									readOnly: true
								}
							]
						}
					],
					
				}]
			}
		]
	}).render('containerDetail_<?=$tab_id?>');
	
	
		/*panel diatas*/
	Ext.create('Ext.form.Panel', {
		id: "container_searchSC_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		items: [{
			id: 'contInquiry_SC<?=$tab_id?>',
			xtype: 'field',
			fieldLabel: 'Container No.',
			name: "cont_inquiry"
		}, 
		{
			id: 'EI_<?=$tab_id?>',
			xtype: 'combo',
			fieldLabel: 'Outbound / Inbound',
			name: "inbOutb",
			store: outboundInbound,
			queryMode: 'local',
			valueField: 'id',
			displayField: 'name'
		}
		],
		buttons: [{
			text: 'Search',
			id: 'inquiry_button_SC<?=$tab_id?>',
			formBind: true,
			listeners: {
				click: {
					fn: function () {
						var form = this.up('form').getForm();
						var form2=Ext.getCmp('container_list_form_<?=$tab_id?>').getForm();
						var ct=form.findField("contInquiry_SC<?=$tab_id?>").getValue();
						var ei=form.findField("EI_<?=$tab_id?>").getValue();
						refreshContList(ct,ei);
						contList<?=$tab_id?>.reload();
						form2.findField('contListX<?=$tab_id?>').bindStore(contList<?=$tab_id?>);
						
					}
				}
			}
		}]
	}).render('container_searchSC_<?=$tab_id?>');
	
	Ext.create('Ext.form.Panel', {
	id: "container_list_form_<?=$tab_id?>",
	bodyPadding: 5,
		fieldDefaults: 
		{
			labelAlign: 'left',
			labelWidth: 100
		},
		
		items: [{
			id: 'contListX<?=$tab_id?>',
			xtype: 'combo',
			name: "contListX",
			fieldLabel: 'Container List',
			width: 400,
			store: contList<?=$tab_id?>,
			queryMode: 'local',
			valueField: 'ID_CONTCONV',
			displayField: 'NO_CONTAINER_EXP',
			listeners: 
			{
				select: function(combo, records, eOpts) {
					console.log(records[0].get('NO_CONTAINER_EXP'));
					console.log(records[0].get('ID_CONTCONV'));
					var a3=records[0].get('ID_CONTCONV');
					postDataCombo(a3);	
					refreshGrid(a3);	
					//console.log(history_status_store);
					history_status_store.reload();
					Ext.getCmp('history_status_detail_<?=$tab_id?>').bindStore(history_status_store);
				}
			}
		  }
		]
	}).render('container_list_<?=$tab_id?>');
	
	Ext.create('Ext.form.Panel', {
			id: "containerGrid_form_<?=$tab_id?>",
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: 'history_status_detail_<?=$tab_id?>',
				xtype: 'grid',
				width: 600,
				minHeight: 150,
				autoScroll: true,
				store: history_status_store,
				columns: [
					{ text: 'Status', dataIndex: 'ID_OP_STATUS', width: 100 },
					{ text: 'Operation', dataIndex: 'OP_STATUS_DESC', width: 300 },
					{ text: 'Date / Time', dataIndex: 'DATE_HISTORY_CHAR', width: 200 }
				]
			}]
		}).render('containerGrid_<?=$tab_id?>');
	
	function postDataCombo(a)
	{
		var data2;
		var url='<?=controller_?>status_change/postDataCombo';
		$.post(url,{PARAMPOST:a}, function(data){
			dataGlobal = eval("["+data+"]");
			console.log(dataGlobal);
			console.log('log2: '+dataGlobal[0].success);
			
			if (dataGlobal[0].success==1)
			{
				Ext.Msg.alert('Success', 'Container Found');
				var dg=eval(dataGlobal[0].data)[0];
				console.log('log8: '+dg);
				Ext.getCmp('containerDetail_form<?=$tab_id?>').getForm().setValues(dg);
			}
			else
			{
				Ext.Msg.alert('Failed', 'Container Not Found');
			}
		});
		
	}
	

</script>
<div id="container_searchSC_<?=$tab_id?>"></div>
<div id="container_list_<?=$tab_id?>"></div>
<div id="containerDetail_<?=$tab_id?>"></div>
<div id="containerGrid_<?=$tab_id?>"></div>