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
			fields:['NO_CONTAINER', 'POINT', 'ID_VES_VOYAGE', 'ID_ISO_CODE', 'ID_CLASS_CODE', 'ID_OPERATOR', 'CONT_STATUS','HOLD_CONTAINER', 'TL_FLAG', 'EDIT_VESSEL', 'EDIT_TL',
					'OVER_HEIGHT','OVER_HEIGHT','OVER_RIGHT','OVER_LEFT','OVER_FRONT','OVER_REAR','WEIGHT','SEAL_NUMB'],
			autoLoad: false,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>hold_container/data_container',
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
			width: 1390,
			height: 250,
			id: 'container_list_grid_<?=$tab_id?>',
			columns: [
				{ dataIndex: 'EDIT_VESSEL', hidden: true, hideable: false},
				{ dataIndex: 'EDIT_TL', hidden: true, hideable: false},
				{ dataIndex: 'POINT', hidden: true, hideable: false},
				{ text: 'No Container', dataIndex: 'NO_CONTAINER', width: 140, filter: {type: 'string'}},
				{ text: 'Vessel', dataIndex: 'ID_VES_VOYAGE', width: 150},
				{ text: 'ISO', dataIndex: 'ID_ISO_CODE' , width: 80},
				{ text: 'Class', dataIndex: 'ID_CLASS_CODE' , width: 80},
				{ text: 'OPR', dataIndex: 'ID_OPERATOR' , width: 80},
				{ text: 'HOLD', dataIndex: 'HOLD_CONTAINER', width: 80},
				{ text: 'F/M', dataIndex: 'CONT_STATUS', width: 80},
				{ text: 'Weight', dataIndex: 'WEIGHT', width: 80},
				{ text: 'Seal Numb', dataIndex: 'SEAL_NUMB', width: 80},
				{ text: 'TL', dataIndex: 'TL_FLAG', width: 80},
				{ text: 'Over Height', dataIndex: 'OVER_HEIGHT', width: 80},
				{ text: 'Over Right', dataIndex: 'OVER_RIGHT', width: 80},
				{ text: 'Over Left', dataIndex: 'OVER_LEFT', width: 80},
				{ text: 'Over Front', dataIndex: 'OVER_FRONT', width: 80},
				{ text: 'Over Rear', dataIndex: 'OVER_REAR', width: 80}
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
			url: '<?=controller_?>hold_container/save_hold_container',
			buttons: [{
		        text: 'Hold',
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
									temp.HOLD_CONTAINER = container_data[i].data.HOLD_CONTAINER;

									// console.log(temp);s
									container_list.push(temp);
								}
								// console.log(container_list);

								if (container_list.length>0){
									loadmask.show();
									form.submit({
										params : {container_data : JSON.stringify(container_list)},
										success: function(form, action) {
											loadmask.hide();
											Ext.Msg.alert('Success', action.result.errors);
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
		    }, {
		        itemId: 'btnDownload',
		        text: 'Unhold',
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
									temp.HOLD_CONTAINER = container_data[i].data.HOLD_CONTAINER;
									// console.log(temp);s
									container_list.push(temp);
								}
								// console.log(container_list);

								if (container_list.length>0){
									loadmask.show();
									form.submit({
										params : {container_data : JSON.stringify(container_list)},
										success: function(form, action) {
											loadmask.hide();
											Ext.Msg.alert('Success', action.result.errors);
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
			/*buttons: [{
				
			}]*/
		}).render('hold_container_detail_<?=$tab_id?>');
	});
</script>
<div id="container_search_<?=$tab_id?>"></div>
<div id="container_list_<?=$tab_id?>"></div>
<div id="hold_container_detail_<?=$tab_id?>"></div>