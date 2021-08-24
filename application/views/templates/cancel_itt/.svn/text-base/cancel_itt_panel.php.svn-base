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
				id: 'itt_container_number_<?=$tab_id?>',
				xtype: 'textareafield',
				name: "container_list",
				fieldLabel: 'No Container'
			}],
			buttons: [{
				text: 'Search',
				formBind: true,
				listeners: {
					click: {
						fn: function () {
							var form = this.up('form').getForm();
							if (form.isValid()){
								var container_number = Ext.getCmp("itt_container_number_<?=$tab_id?>").getValue();
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
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>cancel_itt/data_cancel_itt_container',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				},
				extraParams: {
					id_ves_voyage: '<?=$id_ves_voyage?>'
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
		
		var cancelITT = Ext.create('Ext.Action', {
			text: 'Cancel ITT',
			handler: function(widget, event) {
				var rec = ct_grid.getSelectionModel().getSelection()[0];
				if (rec) {
					Ext.Ajax.request({
						url: '<?=controller_?>cancel_itt/cancel_itt_container',
						params: {
							no_container: rec.get('NO_CONTAINER'),
							point: rec.get('POINT')
						},
						callback: function(opt,success,response){
							var retval = eval(response.responseText)
							if (retval[0]=='S'){
								Ext.Msg.alert('Success', 'Container ITT cancelled');
								ct_store.reload();
							}else{
								Ext.Msg.alert('Failed', retval[1]);
							}
						} 
					});
				} else {
					Ext.Msg.alert('Warning', 'Please select a container from the grid');
				}
			}
		});
		
		var contextMenu = Ext.create('Ext.menu.Menu', {
			items: [
				cancelITT
			]
		});
		
		var ct_grid = Ext.create('Ext.grid.Panel', {
			store: ct_store,
			loadMask: true,
			width: 600,
			height: 250,
			id: 'itt_container_list_grid_<?=$tab_id?>',
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
				enableTextSelection: true,
				listeners: {
					itemcontextmenu: function(view, rec, node, index, e) {
						e.stopEvent();
						contextMenu.showAt(e.getXY());
						return false;
					}
				}
			},
			features: [ct_filters],
			emptyText: 'No Data Found'
		});
		
		ct_grid.render('itt_container_list_<?=$tab_id?>');
	});
</script>
<div id="container_search_<?=$tab_id?>"></div>
<div id="itt_container_list_<?=$tab_id?>"></div>