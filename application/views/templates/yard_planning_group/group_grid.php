<script type="text/javascript">
    var yard_plan_group_store
	Ext.onReady(function(){
		yard_plan_group_store = Ext.create('Ext.data.Store', {
			fields:['ID_YARD','ID_YARD_PLAN', 'YARD_NAME', 'BLOCK_NAME', 'SLOT_RANGE', 'ROW_RANGE', 'CAPACITY', 'CATEGORY_NAME', 'ID_CATEGORY','ACTION'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>yard_planning_group/data_yard_plan_group',
				reader: {
					type: 'json',
					root: 'data',
					idProperty: 'ID_YARD_PLAN',
					totalProperty: 'total'
				}
			},
			groupField: 'CATEGORY_NAME'
		});
		
		var vesvoy_data = Ext.create('Ext.data.Store', {
			fields:['ID_VES_VOYAGE', 'VESSEL'],
//			data : [{ID_MACHINE: '', MCH_NAME: '-- All --'}],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>main/get_active_vessel',
				reader: {
					type: 'json'
				}
			},
    
			listeners: {
			    load: function(store){
				var rec = { ID_VES_VOYAGE: '', VESSEL: '-- Vessel --' };
				store.insert(0,rec);    
			    }
			},
			autoLoad: true
		});
		
		var category_data = Ext.create('Ext.data.Store', {
			fields:['ID_CATEGORY', 'CATEGORY_NAME'],
//			data : [{ID_MACHINE: '', MCH_NAME: '-- All --'}],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>yard_planning_group/data_yard_plan_group',
				reader: {
					type: 'json'
				}
			},
    
			listeners: {
			    load: function(store){
				var rec = { ID_CATEGORY: '', CATEGORY_NAME: '-- Category --' };
				store.insert(0,rec);    
			    }
			},
			autoLoad: true
		});
		
		var group_grid = Ext.create('Ext.grid.Panel', {
			id: 'yd_group_<?=$tab_id?>',
			store: yard_plan_group_store,
			loadMask: true,
			width: 830,
			columns: [
				{ dataIndex: 'ID_CATEGORY', hidden: true, hideable: false},
				{ dataIndex: 'CATEGORY_NAME', hidden: true, hideable: false},
				{ text: 'Yard', dataIndex: 'YARD_NAME', width: 150},
				{ text: 'Block', dataIndex: 'BLOCK_NAME' , width: 150},
				{ text: 'Slot', dataIndex: 'SLOT_RANGE' , width: 100},
				{ text: 'Row', dataIndex: 'ROW_RANGE', width: 100},
				{ text: 'Capacity', dataIndex: 'CAPACITY', width: 100},
				{ dataIndex: 'ID_YARD_PLAN', hidden: true, hideable: false },
				{ dataIndex: 'ID_YARD', hidden: true, hideable: false },
				{ text: 'Delete', dataIndex: 'ID_YARD_PLAN', width: 100,
				    renderer: function(value) {
						    return Ext.String.format('<input type="checkbox" class="checked chk-delete-<?=$tab_id?>" value="{1}">-', value, value);
				    }
				},
				{ 
					text: 'Actions',
					width: 130,
					align: 'center',
					xtype: 'actioncolumn',
					items: [{
						icon: "<?=IMG_?>icons/edit.png",
						tooltip: 'Edit',
						handler: function(grid, rowIndex, colIndex) {

						var rec = grid.getStore().getAt(rowIndex);
						var id_yard = rec.get('ID_YARD');
						var id_yard_plan = rec.get('ID_YARD_PLAN');
						var yard_name = rec.get('YARD_NAME');
						var id_category = rec.get('ID_CATEGORY');
						var cat_name = rec.get('CATEGORY_NAME');
//						var tid = rec.get('TID');
//						addTab('center_panel', 'truck/form_editTruck/'+id_truck, '', 'Edit Truck: ' + tid);
//						Ext.Ajax.request({
//						    url: '<?=controller_?>yard_planning_group/form_editYardPlan?tab_id=<?=$tab_id?>',
//						    params: {
//							id_yard_plan: id_yard_plan
//						    },
//						    callback: function(opt,success,response){
//							$("#popup_script_<?=$tab_id?>").html(response.responseText);
//						    }
//						});
						addTab('center_panel', 'yard_planning?id_yard=' + id_yard + '&id_yard_plan=' + id_yard_plan + '&id_category=' + id_category + '&act=edit&tab_id_ypg=<?=$tab_id?>', yard_name + ' : ' + cat_name, 'Edit Yard Planning');
						return false;

						}
					}]
				}
			],
			tbar: [
				{
					id: "src_ypg_vesvoy_<?=$tab_id?>",
					xtype: 'combo',
					displayField: 'VESSEL',
					valueField: 'ID_VES_VOYAGE',
					queryMode: 'local',
					editable: false,
					store: vesvoy_data,
					allowBlank: false,
//					fieldLabel: 'Vessel',
					name: 'VESSEL',
					value: '',
					listeners: {
						change: function(field, newValue){
							category_data.getProxy().extraParams = {
								ves_voyage: newValue
							};
							Ext.getCmp('src_ypg_category_<?=$tab_id?>').getStore().reload();
							field.nextSibling().setValue('');
						}
					}
//						handler: function (){
//						group_store.getProxy().extraParams = {
//							filter: JSON.stringify([{type:'numeric',
//										 value:Ext.getCmp('machine_<?=$tab_id?>').getValue(),
//										 comparison:'eq',
//										 field:'ID_MACHINE'}])
//						};
//						Ext.getCmp('eq_group_<?=$tab_id?>').getStore().reload();
//					}
				},{
					id: "src_ypg_category_<?=$tab_id?>",
					xtype: 'combo',
					displayField: 'CATEGORY_NAME',
					valueField: 'ID_CATEGORY',
					queryMode: 'local',
					editable: false,
					store: category_data,
					allowBlank: false,
//					fieldLabel: 'Vessel',
					name: 'CATEGORY',
					value: ''
				},{
					xtype: 'button',
					cls: 'btn-search',
					handler: function (){
						yard_plan_group_store.getProxy().extraParams = {
							ves_voyage: Ext.getCmp('src_ypg_vesvoy_<?=$tab_id?>').getValue(),
							category: Ext.getCmp('src_ypg_category_<?=$tab_id?>').getValue()
						};
						Ext.getCmp('yd_group_<?=$tab_id?>').getStore().reload();
					}
				},{
					xtype: 'button',
					cls: 'btn-add',
					tooltip: 'Yard Planning',
					tooltipType: 'title',
					handler: function (){
						addTab('center_panel', 'yard_planning', '', 'Yard Planning');
					}
				},
				{
					xtype: 'button',
					cls: 'btn-refresh',
					tooltip: 'Refresh',
					tooltipType: 'title',
					handler: function (){
					    Ext.getCmp('src_ypg_vesvoy_<?=$tab_id?>').setValue('');
					    Ext.getCmp('src_ypg_category_<?=$tab_id?>').setValue('');
					    yard_plan_group_store.getProxy().extraParams = {
						    ves_voyage: Ext.getCmp('src_ypg_vesvoy_<?=$tab_id?>').getValue(),
						    category: Ext.getCmp('src_ypg_category_<?=$tab_id?>').getValue()
					    };
					    Ext.getCmp('yd_group_<?=$tab_id?>').getStore().reload();
					}
				},
				{
					type: 'button',
					cls: 'btn-delete',
					tooltip: 'Delete',
					tooltipType: 'title',
					handler: function() {
						
						var callback = count_checked();
						console.log(callback);
						
						loadmask.show();

						var url = "<?=controller_?>yard_planning_group/delete_yard_plan_group_mutiple";
						$.post( url, 
						{ 
							response: callback
						}, 
						function(data) {
							loadmask.hide();
//							Ext.Msg.alert('Data : ' + data);
							if (data=='1'){
								Ext.Msg.alert('Success', 'Yard Plan Deleted');
								yard_plan_group_store.reload();
//								grid.getStore().reload();
							}else{
								Ext.Msg.alert('Failed', 'Delete Multiple Container Failed');
							}
						});
					}
				},
				{
					type: 'button',
					cls: 'btn-select-all',
					tooltip: 'Select All',
					tooltipType: 'title',
					handler: function() {
//					    Ext.Msg.alert('Test', this.text);
					    if(this.hasCls('check')){
						$('.chk-delete-<?=$tab_id?>').prop("checked", false);
						this.removeCls('check');
//						this.setText('Unselect All');
					    }else{
						$('.chk-delete-<?=$tab_id?>').prop("checked", true);
						this.addCls('check');
//						this.setText('Select All');
					    }
						
					}
				}
			],
			features: [{
				ftype: 'groupingsummary',
				groupHeaderTpl: 'Category: <input type="button" value="{name}" onclick="edit_category_<?=$tab_id?>(\'{[values.rows[0].data.ID_CATEGORY]}\')"/>',
				showSummaryRow: false
			}],
			emptyText: 'No Data Found'
		});
		
		group_grid.render('group_grid_<?=$tab_id?>');
	});
	
	function edit_category_<?=$tab_id?>(id_category){
		Ext.Ajax.request({
			url: '<?=controller_?>yard_planning/popup_existing_category?dtbl=0&rtbl=1&tab_id=<?=$tab_id?>',
			params: {
				id_category : id_category,
				edit_mode : 1
			},
			callback: function(opt,success,response){
				$("#popup_script_<?=$tab_id?>").html(response.responseText);
			} 
		});
	}
</script>
<div id="group_grid_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>


<script type="text/javascript">

	function count_checked()
	{
		var counter = 0;
		var data = [];   
		$('input[type=checkbox]').each(function () {

		    if(this.checked=="1"){
		    	data.push($(this).val());
		    }

		});
			return data;
	}
	
</script>