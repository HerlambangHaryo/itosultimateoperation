<script type="text/javascript">
	Ext.onReady(function() {
		var qc_working_list_inbound_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['MCH_NAME', 'MCH_TYPE', 'FULL_NAME', 'IS_ACTIVE', 'START_ACTIVE','END_ACTIVE'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>operator_assignment/data_alat',
				reader: {
					type: 'json',
					root: 'data',
					idProperty: 'QC_WORKING_LIST',
					totalProperty: 'total'
				}
			}
		});
		
		var qc_working_list_inbound_filters_<?=$tab_id?> = {
			ftype: 'filters',
			encode: false,
			local: true
		};
		
		var assignOperator = Ext.create('Ext.Action', {
			icon   : '<?=IMG_?>icons/config.png',
			text: 'Assign Operator',
			handler: function(widget, event) {
				var rec = qc_working_list_inbound_grid_<?=$tab_id?>.getSelectionModel().getSelection();
				if (rec.length>0) {
					var list_container = [];
					for (i=0; i<rec.length; i++){
						list_container.push(
							{
								no_container: rec[i].get('NO_CONTAINER'),
								point: rec[i].get('POINT')
							}
						);
					}
					var JSON_list_container = JSON.stringify(list_container);
					Ext.Ajax.request({
						url: '<?=controller_?>operator_assignment/popup_assign_operator?tab_id=<?=$tab_id?>',
						params: {
							list_container : JSON_list_container
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
		
		var finishJob = Ext.create('Ext.Action', {
			text: 'Complete Job',
			handler: function(widget, event) {
				var rec = qc_working_list_inbound_grid_<?=$tab_id?>.getSelectionModel().getSelection()[0];
				if (rec) {
					Ext.Ajax.request({
						url: '<?=controller_?>operator_assignment/popup_finish_job?tab_id=<?=$tab_id?>',
						params: {
							no_container: rec.get('NO_CONTAINER'),
							point: rec.get('POINT'),
							id_op_status: rec.get('ID_OP_STATUS'),
							event: rec.get('EVENT'),
							block_name: rec.get('GT_JS_BLOCK_NAME'),
							id_block: rec.get('GT_JS_BLOCK'),
							slot: rec.get('GT_JS_SLOT'),
							row: rec.get('GT_JS_ROW'),
							tier: rec.get('GT_JS_TIER'),
							yard_placement: rec.get('YARD_PLACEMENT')
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
				assignOperator,
				finishJob
			]
		});
		
		var qc_working_list_inbound_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			store: qc_working_list_inbound_store_<?=$tab_id?>,
			title : 'Daftar Alat',
			loadMask: true,
			width: 900,
			columns: [
				{ text: 'NO', dataIndex: 'NO', width: 40, filter: {type: 'string'}},
				{ text: 'NAMA ALAT', dataIndex: 'MCH_NAME' , width: 120},
				{ text: 'JENIS ALAT', dataIndex: 'MCH_TYPE' , width: 120},
				{ text: 'OPERATOR', dataIndex: 'FULL_NAME' , width: 120},
				{ text: 'ACTIVE', dataIndex: 'IS_ACTIVE', width: 60},
				{ text: 'START ACTIVE', dataIndex: 'START_ACTIVE' , width: 120},
				{ text: 'END ACTIVE', dataIndex: 'END_ACTIVE' , width: 120}
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
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: qc_working_list_inbound_store_<?=$tab_id?>
			})],
			features: [qc_working_list_inbound_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
		});
		
		
		qc_working_list_inbound_grid_<?=$tab_id?>.getSelectionModel().on({
			selectionchange: function(sm, selections) {
				if (selections.length>0) {
					if (selections.length>1){
						changePA.disable();
						jobComplete.disable();
					}else{
						if (selections[0].get('EVENT')=='P'){
							changePA.enable();
						}else{
							changePA.disable();
						}
					}
				} else {
					changePA.disable();
				}
			}
		});
		
		qc_working_list_inbound_grid_<?=$tab_id?>.child('pagingtoolbar').add([
			'-',{
				text: 'Clear Filter Data',
				handler: function () {
					qc_working_list_inbound_grid.filters.clearFilters();
				}
			},'-',{
				text: 'Deselect Data',
				handler: function () {
					qc_working_list_inbound_grid.getSelectionModel().deselectAll();
					id_ves_voyage = '';
				}
			}
		]);
		
		
		
		qc_working_list_inbound_grid_<?=$tab_id?>.render('qc_working_list_inbound_<?=$tab_id?>');
	});

</script>
<div id="qc_working_list_inbound_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>