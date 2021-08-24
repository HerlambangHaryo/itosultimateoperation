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
		
		var changeEquipment = Ext.create('Ext.Action', {
			icon   : '<?=IMG_?>icons/config.png',
			text: 'Change Machine',
			handler: function(widget, event) {
				var rec = group_grid.getSelectionModel().getSelection()[0];
				if (rec) {
					Ext.Ajax.request({
						url: '<?=controller_?>yard_equipment_group/popup_change_equipment?tab_id=<?=$tab_id?>',
						params: {
							id_mch_plan: rec.get('ID_MCH_PLAN')
						},
						callback: function(opt,success,response){
							$("#popup_script_<?=$tab_id?>").html(response.responseText);
						} 
					});
				} else {
					Ext.Msg.alert('Warning', 'Please select a job from the grid');
				}
			}
		});
		
		var contextMenu = Ext.create('Ext.menu.Menu', {
			items: [
				changeEquipment
			]
		});
		
		var group_grid = Ext.create('Ext.grid.Panel', {
			id: 'eq_group_<?=$tab_id?>',
			store: group_store,
			loadMask: true,
			width: 600,
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
			tbar: [{
					xtype: 'textfield',
					id: 'src_machine',
					name: 'src_machine',
					fieldLabel: 'machine',
					allowBlank: true,
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								var login_button = Ext.getCmp('filter_yeg_mch_btn');
								login_button.fireEvent('click', login_button);
							}
						}
					}
				},{
				xtype: 'button',
				id: 'filter_yeg_mch_btn',
				name: 'filter_yeg_mch_btn',
				text: 'Filter',
				handler: function (){
					Ext.getCmp('eq_group_<?=$tab_id?>').getStore().reload();
				}
			},{
				xtype: 'button',
				text: 'Refresh Data',
				handler: function (){
					Ext.getCmp('eq_group_<?=$tab_id?>').getStore().reload();
				}
			}],
			features: [{
				ftype: 'groupingsummary',
				groupHeaderTpl: 'Machine: {name}',
				showSummaryRow: false
			}],
			emptyText: 'No Data Found'
		});
		
		group_grid.render('group_grid_<?=$tab_id?>');
	});
</script>

<div id="group_grid_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>