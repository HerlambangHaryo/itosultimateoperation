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
								ct_store_<?=$tab_id?>.getProxy().extraParams = {
									id_ves_voyage: '<?=$id_ves_voyage?>',
									container_list: container_param
								};
								ct_store_<?=$tab_id?>.load();
							}
						}
					}
				}
			}]
		}).render('container_search_<?=$tab_id?>');
		
		var ct_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['ID_TRANSHIPMENT','NO_CONTAINER','POINT','LOAD_POINT','ID_ISO_CODE','CONT_STATUS','CONT_HEIGHT','OLD_ID_VES_VOYAGE','ID_VES_VOYAGE','ID_OP_STATUS','OP_STATUS_DESC','OP_STATUS','ID_CLASS_CODE', 'ID_OPERATOR'],
			autoLoad: false,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>cancel_transhipment_container/data_transhipment_container',
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
		
		var ct_filters_<?=$tab_id?> = {
			ftype: 'filters',
			encode: false,
			local: true
		};
		
		var ct_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			store: ct_store_<?=$tab_id?>,
			loadMask: true,
			width: 800,
			height: 250,
			id: 'container_list_grid_<?=$tab_id?>',
			columns: [
				{ xtype: 'rownumberer'},
				{ dataIndex: 'ID_TRANSHIPMENT', hidden: true, hideable: false},
				{ text: 'No Container', dataIndex: 'NO_CONTAINER', width: 110},
				{ text: 'Vessel From', dataIndex: 'OLD_ID_VES_VOYAGE', width: 110},
				{ text: 'Vessel To', dataIndex: 'ID_VES_VOYAGE', width: 110},
				{ dataIndex: 'POINT', hidden: true, hideable: false},
				{ dataIndex: 'LOAD_POINT', hidden: true, hideable: false},
				{ text: 'ISO', dataIndex: 'ID_ISO_CODE' , width: 60},
				{ text: 'Class', dataIndex: 'ID_CLASS_CODE' , width: 60},
				{ text: 'OPR', dataIndex: 'ID_OPERATOR' , width: 60},
				{ text: 'F/M', dataIndex: 'CONT_STATUS', width: 60},
				{ text: 'STATUS', dataIndex: 'OP_STATUS', width: 110},
				{ text: 'Height', dataIndex: 'CONT_HEIGHT', width: 80},
				{ dataIndex: 'ID_OP_STATUS', hidden: true, hideable: false},
				{ dataIndex: 'OP_STATUS_DESC', hidden: true, hideable: false}
			],
			viewConfig : {
				enableTextSelection: true
			},
			features: [ct_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
		});
		
		ct_grid_<?=$tab_id?>.render('container_list_<?=$tab_id?>');
		
		Ext.create('Ext.form.Panel', {
			id: "detail_form_<?=$tab_id?>",
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 150
			},
			url: '<?=controller_?>cancel_transhipment_container/save_cancel_transhipment_container',
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
								var container_data = ct_store_<?=$tab_id?>.data.items;
								var container_list = [];
								for (i=0;i<container_data.length;i++){
									var temp = {};
									temp.ID_TRANSHIPMENT = container_data[i].data.ID_TRANSHIPMENT;
									temp.NO_CONTAINER = container_data[i].data.NO_CONTAINER;
									temp.POINT = container_data[i].data.POINT;
									temp.LOAD_POINT = container_data[i].data.LOAD_POINT;
									temp.ID_CLASS_CODE = container_data[i].data.ID_CLASS_CODE;
									temp.OLD_ID_VES_VOYAGE = container_data[i].data.OLD_ID_VES_VOYAGE;
									temp.ID_VES_VOYAGE = container_data[i].data.ID_VES_VOYAGE;
									temp.ID_OP_STATUS = container_data[i].data.ID_OP_STATUS;
									// console.log(temp);
									container_list.push(temp);
									
								}
								
								if (container_list.length>0){
									Ext.MessageBox.confirm('Delete', 'Apakah anda yakin ingin cancel transhipment ?', function(btn){
									    if(btn === 'yes'){
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
									    }
									    else{
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