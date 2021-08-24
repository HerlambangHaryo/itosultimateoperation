<script type="text/javascript">
	Ext.onReady(function(){
		var group_store = Ext.create('Ext.data.Store', {
			fields:['ID_MCH_PLAN', 'YARD_NAME', 'BLOCK_NAME', 'SLOT_RANGE', 'ROW_RANGE', 'MCH_NAME'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>yard_equipment_group/data_yard_equipment_group',
				reader: {
					type: 'json',
					root: 'data',
					idProperty: 'ID_MCH_PLAN',
					totalProperty: 'total'
				}
			},
			groupField: 'MCH_NAME'
		});
		
		var machine_data = Ext.create('Ext.data.Store', {
			fields:['ID_MACHINE', 'MCH_NAME'],
//			data : [{ID_MACHINE: '', MCH_NAME: '-- All --'}],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>yard_equipment_group/get_machine',
				reader: {
					type: 'json'
				}
			},
    
			listeners: {
			    load: function(store){
				var rec = { ID_MACHINE: '', MCH_NAME: '-- All --' };
				store.insert(0,rec);    
			    }
			},
			autoLoad: true
		});
	
		var ct_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var group_grid = Ext.create('Ext.grid.Panel', {
			id: 'eq_group_<?=$tab_id?>',
			store: group_store,
			loadMask: true,
			width: 570,
			columns: [							
				{ text: 'Yard', dataIndex: 'YARD_NAME', width: 150},
				{ text: 'Block', dataIndex: 'BLOCK_NAME' , width: 150},
				{ text: 'Slot', dataIndex: 'SLOT_RANGE' , width: 100},
				{ text: 'Row', dataIndex: 'ROW_RANGE', width: 100},
				{ text: 'ID Mch Plan', dataIndex: 'ID_MCH_PLAN', hidden: true, hideable: false },
				{
					text: 'Delete',
					xtype: 'actioncolumn',
					width: 70,
					items: [{
						icon: "<?=IMG_?>icons/delete.png",
						tooltip: 'Delete',
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.getStore().getAt(rowIndex);
							loadmask.show();
							var url = "<?=controller_?>yard_equipment_group/delete_yard_equipment_group";
							$.post( url, { id_mch_plan: rec.get('ID_MCH_PLAN')}, function(data) {
								// console.log(data);
								loadmask.hide();
								Ext.Msg.alert('Success', 'Yard Equipment Plan Deleted');
								grid.getStore().reload();
							});
						}
					}]
				}
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
			features: [ct_filters,{
				ftype: 'groupingsummary',
				groupHeaderTpl: 'Machine: {name}',
				showSummaryRow: false
			}],
			emptyText: 'No Data Found',
			tbar: [{
				id: "machine_<?=$tab_id?>",
				xtype: 'combo',
				displayField: 'MCH_NAME',
				valueField: 'ID_MACHINE',
				queryMode: 'local',
				editable: false,
				store: machine_data,
				allowBlank: false,
				fieldLabel: 'Machine',
				name: 'MACHINE'
			},{
				xtype: 'button',
				text: 'Filter',
				handler: function (){
					group_store.getProxy().extraParams = {
						filter: JSON.stringify([{type:'numeric',
									 value:Ext.getCmp('machine_<?=$tab_id?>').getValue(),
									 comparison:'eq',
									 field:'ID_MACHINE'}])
					};
					Ext.getCmp('eq_group_<?=$tab_id?>').getStore().reload();
				}
			},{
				xtype: 'button',
				text: 'Refresh Data',
				handler: function (){
					Ext.getCmp('eq_group_<?=$tab_id?>').getStore().reload();
				}
			}]
		});
		
		group_grid.render('group_grid_<?=$tab_id?>');
	});
</script>
<div id="group_grid_<?=$tab_id?>"></div>