<script type="text/javascript">
	Ext.onReady(function(){
		var yard_lini2<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['ID_YARD', 'YARD_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>extended_storage_yard/data_lini2/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		/*
		var isViaYard = Ext.create('Ext.data.Store', {
			fields: ['ID', 'NAME'],
			data: [{
				"ID": "N",
				"NAME": "Truck Loosing"
			}, {
				"ID": "Y",
				"NAME": "Container Yard"
			}]
		});*/
	
		Ext.create('Ext.form.Panel', {
			id: "container_search_form_<?=$tab_id?>",
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: 'container_number_<?=$tab_id?>',
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
								var container_number = Ext.getCmp("container_number_<?=$tab_id?>").getValue();
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
									id_ves_voyage: '<?=$id_ves_voyage?>',
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
				url: '<?=controller_?>extended_storage_yard/data_extended_storage_yard',
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
			id: 'container_list_grid_<?=$tab_id?>',
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
		
		ct_grid.render('container_list_<?=$tab_id?>');
		
		Ext.create('Ext.form.Panel', {
			id: "detail_form_<?=$tab_id?>",
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 150
			},
			url: '<?=controller_?>extended_storage_yard/save_extended_storage_yard',
			items: [{
				xtype: 'fieldset',
				title: 'ESY Detail',
				items: [{
					xtype: 'hiddenfield',
					name: 'ID_VES_VOYAGE',
					value: '<?=$id_ves_voyage?>'
				},{
					id: 'yard<?=$tab_id?>',
					xtype: 'combo',
					name: "ID_YARD",
					fieldLabel: 'Yard',
					width: 400,
					store: yard_lini2<?=$tab_id?>,
					queryMode: 'local',
					valueField: 'ID_YARD',
					allowBlank: false,
					displayField: 'YARD_NAME'
				}/*,{
					id: 'via_yard<?=$tab_id?>',
					xtype: 'combo',
					name: "IS_VIA_YARD",
					fieldLabel: 'Via Yard',
					width: 400,
					store: isViaYard,
					queryMode: 'local',
					valueField: 'ID',
					allowBlank: false,
					displayField: 'NAME'
				}*/]
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
		}).render('multiple_detail_<?=$tab_id?>');
	});
</script>
<div id="container_search_<?=$tab_id?>"></div>
<div id="container_list_<?=$tab_id?>"></div>
<div id="multiple_detail_<?=$tab_id?>"></div>