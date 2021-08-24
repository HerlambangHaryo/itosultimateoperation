<script type="text/javascript">
	Ext.onReady(function(){
		var yard_lini2_list_store = Ext.create('Ext.data.Store', {
			fields:['ID_YARD', 'YARD_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>itt_container/data_yard_lini2_autocomplete/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		
		var via_yard_store = Ext.create('Ext.data.Store', {
			fields:['ID', 'VIA_YARD'],
			data : [
				 {ID: 'Y', VIA_YARD: 'Yes'},
				 {ID: 'N', VIA_YARD: 'No'}
			 ]
		});
		
		var ct_store = Ext.create('Ext.data.Store', {
			fields:['NO_CONTAINER', 'POINT', 'ID_ISO_CODE', 'CONT_STATUS'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>itt_container/data_itt_container',
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
		
		var ct_grid = Ext.create('Ext.grid.Panel', {
			store: ct_store,
			loadMask: true,
			width: 400,
			height: 250,
			multiSelect: true,
			columns: [
				{ xtype: 'rownumberer'},
				{ dataIndex: 'POINT', hidden: true, hideable: false},
				{ text: 'No Container', dataIndex: 'NO_CONTAINER', width: 140, filter: {type: 'string'}},
				{ text: 'ISO', dataIndex: 'ID_ISO_CODE' , width: 80},
				{ text: 'F/M', dataIndex: 'CONT_STATUS', width: 80}
			],
			viewConfig : {
				enableTextSelection: true
			},
			dockedItems: [{
				xtype: 'toolbar',
				dock: 'right',
				items: [{
					xtype: 'tbfill'
				},{
					text: '>',
					handler: function() {
						var sm = ct_grid.getSelectionModel();
						var selected = sm.getSelection();
						for (i=0;i<selected.length;i++){
							ct_store2.add(selected[i].data);
						}
						ct_store.remove(selected);
					}
				},{
					text: '>>',
					handler: function() {
						var container_data = ct_store.data.items;
						for (i=0;i<container_data.length;i++){
							ct_store2.add(container_data[i].data);
						}
						ct_store.remove(container_data);
					}
				},{
					text: '<<',
					handler: function() {
						var container_data = ct_store2.data.items;
						for (i=0;i<container_data.length;i++){
							ct_store.add(container_data[i].data);
						}
						ct_store2.remove(container_data);
					}
				},{
					text: '<',
					handler: function() {
						var sm = ct_grid2.getSelectionModel();
						var selected = sm.getSelection();
						for (i=0;i<selected.length;i++){
							ct_store.add(selected[i].data);
						}
						ct_store2.remove(selected);
					}
				},{
					xtype: 'tbfill' 
				}]
			}],
			features: [ct_filters],
			emptyText: 'No Data Found'
		});
		
		ct_grid.render('itt_container_list_<?=$tab_id?>');
		
		var ct_store2 = Ext.create('Ext.data.Store', {
			fields:['NO_CONTAINER', 'POINT', 'ID_ISO_CODE', 'CONT_STATUS']
		});
		
		var ct_filters2 = {
			ftype: 'filters',
			encode: false,
			local: true
		};
		
		var ct_grid2 = Ext.create('Ext.grid.Panel', {
			store: ct_store2,
			loadMask: true,
			width: 350,
			height: 250,
			multiSelect: true,
			id: 'itt_container_list_grid_<?=$tab_id?>',
			columns: [
				{ xtype: 'rownumberer'},
				{ dataIndex: 'POINT', hidden: true, hideable: false},
				{ text: 'No Container', dataIndex: 'NO_CONTAINER', width: 140, filter: {type: 'string'}},
				{ text: 'ISO', dataIndex: 'ID_ISO_CODE' , width: 80},
				{ text: 'F/M', dataIndex: 'CONT_STATUS', width: 80}
			],
			viewConfig : {
				enableTextSelection: true
			},
			features: [ct_filters2],
			emptyText: 'No Data Found'
		});
		
		ct_grid2.render('itt_container_list_save_<?=$tab_id?>');
		
		Ext.create('Ext.form.Panel', {
			id: "itt_detail_form_<?=$tab_id?>",
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 150
			},
			url: '<?=controller_?>itt_container/save_itt_container',
			items: [{
				xtype: 'hiddenfield',
				name: 'ID_VES_VOYAGE',
				value: '<?=$id_ves_voyage?>'
			},{
				xtype: 'fieldset',
				title: 'ITT Detail',
				items: [{
					xtype: 'combo',
					displayField: 'YARD_NAME',
					valueField: 'ID_YARD',
					store: yard_lini2_list_store,
					queryMode: 'remote',
					forceSelection: true,
					hideTrigger: true,
					triggerAction: 'query',
					emptyText: 'Autocomplete',
					typeAhead: true,
					minChars: 1,
					allowBlank: false,
					fieldLabel: 'Destination Yard',
					name: 'ID_YARD_LINI2'
				},{
					xtype: 'combo',
					displayField: 'VIA_YARD',
					valueField: 'ID',
					queryMode: 'local',
					editable: false,
					store: via_yard_store,
					allowBlank: false,
					fieldLabel: 'Via Yard',
					name: 'VIA_YARD'
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
								var container_data = ct_store2.data.items;
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
		}).render('itt_detail_<?=$tab_id?>');
	});
</script>
<table>
<tr>
	<td>
	<div id="itt_container_list_<?=$tab_id?>"></div>
	</td>
	<td>
	<div id="itt_container_list_save_<?=$tab_id?>"></div>
	</td>
</tr>
</table>
<div id="itt_detail_<?=$tab_id?>"></div>