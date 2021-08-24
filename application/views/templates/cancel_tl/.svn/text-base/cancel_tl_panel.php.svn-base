<script type="text/javascript">
	Ext.onReady(function(){		
		Ext.create('Ext.form.Panel', {
			id: "container_search_form_<?=$tab_id?>",
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: 'cancel_tl_number_<?=$tab_id?>',
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
								var container_number = Ext.getCmp("cancel_tl_number_<?=$tab_id?>").getValue();
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
			fields:['NO_CONTAINER', 'POINT', 'ID_VES_VOYAGE', 'ID_ISO_CODE', 'ID_CLASS_CODE', 'ID_OPERATOR', 'CONT_STATUS', 'TL_FLAG', 'OP_STATUS_DESC','ID_OP_STATUS'],
			autoLoad: false,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>cancel_tl/data_cancel_tl',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 100,
			sorters: [{
				property: 'NO_CONTAINER',
				direction: 'ASC'
			}]
		});
		
		var ct_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var ct_grid = Ext.create('Ext.grid.Panel', {
			store: ct_store,
			loadMask: true,
			width: 870,
			height: 440,
			id: 'cancel_tl_list_grid_<?=$tab_id?>',
			columns: [
				{ dataIndex: 'EDIT_VESSEL', hidden: true, hideable: false},
				{ dataIndex: 'EDIT_TL', hidden: true, hideable: false},
				{ dataIndex: 'POINT', hidden: true, hideable: false},
				{ dataIndex: 'ID_OP_STATUS', hidden: true, hideable: false},
				{ text: 'No Container', dataIndex: 'NO_CONTAINER', width: 140, filter: {type: 'string'}},
				{ text: 'Vessel', dataIndex: 'ID_VES_VOYAGE', width: 150},
				{ text: 'ISO', dataIndex: 'ID_ISO_CODE' , width: 80},
				{ text: 'Class', dataIndex: 'ID_CLASS_CODE' , width: 80},
				{ text: 'OPR', dataIndex: 'ID_OPERATOR' , width: 80},
				{ text: 'F/M', dataIndex: 'CONT_STATUS', width: 80},
				{ text: 'TL', dataIndex: 'TL_FLAG', width: 80},
				{ text: 'STATUS', dataIndex: 'OP_STATUS_DESC', width: 150}
			],
			viewConfig : {
				enableTextSelection: true
			},
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: ct_store,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							ct_grid.filters.clearFilters();
						}
					}
				]
			})],
			features: [ct_filters],
			emptyText: 'No Data Found'
		});
		
		ct_grid.render('cancel_tl_list_<?=$tab_id?>');
		
		Ext.create('Ext.form.Panel', {
			id: "detail_form_<?=$tab_id?>",
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 150
			},
			url: '<?=controller_?>cancel_tl/save_cancel_tl',
			items: [{
				items: [{
					xtype: 'hiddenfield',
					name: 'ID_VES_VOYAGE',
					value: '<?=$id_ves_voyage?>'
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
									temp.ID_OP_STATUS = container_data[i].data.ID_OP_STATUS;
									temp.ID_VES_VOYAGE = container_data[i].data.ID_VES_VOYAGE;
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
		}).render('cancel_tl_list_<?=$tab_id?>');
	});
</script>
<div id="container_search_<?=$tab_id?>"></div>
<div id="cancel_tl_list_<?=$tab_id?>"></div>