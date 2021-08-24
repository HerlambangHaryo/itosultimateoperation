<script type="text/javascript">
	Ext.onReady(function(){
		var vessel_schedule_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_VES_VOYAGE', 'VESSEL'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>loading_cancel_container/data_vessel_schedule_autocomplete/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		Ext.create('Ext.form.Panel', {
			id: "container_search_form_<?=$tab_id?>",
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: 'loading_cancel_container_number_<?=$tab_id?>',
				xtype: 'textareafield',
				name: "container_list",
				fieldLabel: 'No Container',
				allowBlank: false
			}],
			buttons: [{
				text: 'Search',
				formBind: true,
				listeners: {
					click: {
						fn: function () {
							var form = this.up('form').getForm();
							if (form.isValid()){
								var container_number = Ext.getCmp("loading_cancel_container_number_<?=$tab_id?>").getValue();
								var container_list = container_number.split("\n");
								// console.log(container_list);
								var container_param = '';
								for (i=0;i<container_list.length;i++){
									container_list[i] = $.trim(container_list[i]);
									if (container_list[i]!=''){
										if (container_param!=''){
											container_param+=',';
										}
										container_param+="'"+container_list[i]+"'";
									}
								}
								// console.log(container_param);
								ct_store.getProxy().extraParams = {
									container_list: container_param
								};
								ct_store.load();
							}
						}
					}
				}
			}]
		}).render('container_search_<?=$tab_id?>');
		
		var ct_store = Ext.create('Ext.data.Store', {
			fields:['NO_CONTAINER', 'POINT', 'ID_ISO_CODE', 'ID_CLASS_CODE', 'ID_OPERATOR', 'CONT_STATUS'],
			autoLoad: false,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>loading_cancel_container/data_loading_cancel_container',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			sorters: [{
				property: 'NO_CONTAINER',
				direction: 'ASC'
			}]
		});
		
		var ct_filters = {
			ftype: 'filters',
			encode: false,
			local: true
		};
		
		var ct_grid = Ext.create('Ext.grid.Panel', {
			store: ct_store,
			loadMask: true,
			width: 600,
			height: 250,
			id: 'loading_cancel_container_list_grid_<?=$tab_id?>',
			columns: [
				{ xtype: 'rownumberer'},
				{ dataIndex: 'POINT', hidden: true, hideable: false},
				{ text: 'No Container', dataIndex: 'NO_CONTAINER', width: 140, filter: {type: 'string'}},
				{ text: 'ISO', dataIndex: 'ID_ISO_CODE' , width: 80},
				{ text: 'Class', dataIndex: 'ID_CLASS_CODE' , width: 80},
				{ text: 'OPR', dataIndex: 'ID_OPERATOR' , width: 80},
				{ text: 'F/M', dataIndex: 'CONT_STATUS', width: 80}
			],
			viewConfig : {
				enableTextSelection: true
			},
			features: [ct_filters],
			emptyText: 'No Data Found'
		});
		
		ct_grid.render('loading_cancel_container_list_<?=$tab_id?>');
		
		Ext.create('Ext.form.Panel', {
			id: "loading_cancel_detail_form_<?=$tab_id?>",
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 150
			},
			url: '<?=controller_?>loading_cancel_container/save_loading_cancel_container',
			items: [{
				xtype: 'fieldset',
				title: 'Loading Cancel Detail',
				items: [{
					xtype: 'combo',
					displayField: 'VESSEL',
					valueField: 'ID_VES_VOYAGE',
					store: vessel_schedule_list_store,
					queryMode: 'remote',
					forceSelection: true,
					hideTrigger: true,
					triggerAction: 'query',
					emptyText: 'Autocomplete',
					typeAhead: true,
					minChars: 3,
					allowBlank: false,
					fieldLabel: 'Vessel Destination',
					name: 'ID_VES_VOYAGE'
				},{
					xtype: 'textfield',
					fieldLabel: 'Document Number',
					name: 'DOC_NUMBER'
				}]
			}],
			buttons: [{
				text: 'Save',
				formBind: true,
				listeners: {
					click: {
						fn: function () {
							var form = this.up('form').getForm();
							if (form.isValid()){
								var container_data = ct_store.data.items;
								var container_list = [];
								for (i=0;i<container_data.length;i++){
									var temp = {};
									temp.NO_CONTAINER = container_data[i].data.NO_CONTAINER;
									temp.POINT = container_data[i].data.POINT;
									// console.log(temp);
									container_list.push(temp);
								}
								// console.log(container_list);
								if (container_list.length>0){
									loadmask.show();
									form.submit({
										params : {container_data : JSON.stringify(container_list)},
										success: function(form, action) {
											loadmask.hide();
											Ext.Msg.alert('Success', 'Changes saved successfully');
											Ext.getCmp("<?=$tab_id?>").close();
										},
										failure: function(form, action) {
											loadmask.hide();
											Ext.Msg.alert('Failed', action.result.errors);
										}
									});
								}else{
									Ext.Msg.alert('Warning', 'No Container Selected!');
								}
							}
						}
					}
				}
			}]
		}).render('loading_cancel_detail_<?=$tab_id?>');
	});
</script>
<div id="container_search_<?=$tab_id?>"></div>
<div id="loading_cancel_container_list_<?=$tab_id?>"></div>
<div id="loading_cancel_detail_<?=$tab_id?>"></div>