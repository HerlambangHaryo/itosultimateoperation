<script type="text/javascript">
	Ext.onReady(function() {
		var qc_working_list_inbound_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			//fields:['BAY', 'LOCATION', 'TWENTY', 'FOURTY', 'TOTAL','SUMDISCHLOAD', 'REMAIN', 'MACHINE', 'ESTIMATE_TIME','ACTIVE', 'SEQUENCE'],
			fields:['SEQUENCE','BAY', 'LOCATION', 'TWENTY', 'FOURTY', 'TOTAL','SUMDISCHLOAD', 'REMAIN', 'MACHINE', 'ESTIMATE_TIME','ACTIVE'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>qc_working_list/data_qc_working_list?id_ves_voyage=<?=$id_ves_voyage?>&type=I',
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
		
		var qc_working_list_inbound_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			store: qc_working_list_inbound_store_<?=$tab_id?>,
			title : 'Inbound',
			loadMask: true,
			width: 900,
			columns: [
				{ text: 'SEQUENCE', dataIndex: 'SEQUENCE', width: 90},
				{ text: 'BAY', dataIndex: 'BAY', width: 80, filter: {type: 'string'}},
				{ text: 'LOCATION', dataIndex: 'LOCATION' , width: 80},
				{ text: '20\'', dataIndex: 'TWENTY' , width: 40},
				{ text: '40\'', dataIndex: 'FOURTY' , width: 40},
				{ text: 'TOTAL', dataIndex: 'TOTAL', width: 60},
				{ text: 'COMPLETED', dataIndex: 'SUMDISCHLOAD', width: 60},
				{ text: 'REMAIN', dataIndex: 'REMAIN' , width: 80},
				{ text: 'MACHINE', dataIndex: 'MACHINE', width: 100},
				{ text: 'ESTIMATE TIME', dataIndex: 'ESTIMATE_TIME', width: 120},
				{ text: 'ACTIVE', dataIndex: 'ACTIVE', width: 60},
				//{ text: 'SEQUENCE', dataIndex: 'SEQUENCE', width: 90},
				{ 
					text: 'ACTION',
					menuDisabled: true,
					sortable: false,
					xtype: 'actioncolumn',
					width: 80,
					items: [{
						//var rec = grid.getStore().getAt(rowIndex);
						icon : '<?=IMG_?>icons/stop.png',
						//iconCls: 'sell-col',
						tooltip: 'Stop',
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.getStore().getAt(rowIndex);
							if(!rec.get('MACHINE')){
								Ext.Msg.alert('Error', 'Machine hast not been set');
							} else {
								loadmask.show();
								Ext.Ajax.request({
									url: '<?=controller_?>qc_working_list/deactivate?id_ves_voyage=<?=$id_ves_voyage?>&bay='+rec.get('BAY')+'&location='+rec.get('LOCATION')+'&activity=I',
									success: function(response){
										var text = response.responseText;
										if (text=='1'){
											Ext.MessageBox.show({
												title: 'Success',
												msg: 'Changes saved successfully.',
												buttons: Ext.MessageBox.OK
											});
											qc_working_list_inbound_store_<?=$tab_id?>.reload();
										}else{
											Ext.MessageBox.show({
												title: 'Error',
												msg: 'Failed : ' + text ,
												buttons: Ext.MessageBox.OK
											});
										}
										loadmask.hide();
									}
								});
							}
						}
					},
					'-',
					{
						//var rec = grid.getStore().getAt(rowIndex);
						icon : '<?=IMG_?>icons/start.png',
						//iconCls: 'sell-col',
						tooltip: 'Start',
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.getStore().getAt(rowIndex);
							if(!rec.get('MACHINE')){
								Ext.Msg.alert('Error', 'Machine hast not been set');
							} else {	
								loadmask.show();
								Ext.Ajax.request({
									url: '<?=controller_?>qc_working_list/activate?id_ves_voyage=<?=$id_ves_voyage?>&bay='+rec.get('BAY')+'&location='+rec.get('LOCATION')+'&activity=I',
									success: function(response){
										var text = response.responseText;
										if (text=='1'){
											Ext.MessageBox.show({
												title: 'Success',
												msg: 'Changes saved successfully.',
												buttons: Ext.MessageBox.OK
											});
											qc_working_list_inbound_store_<?=$tab_id?>.reload();
										}else{
											Ext.MessageBox.show({
												title: 'Error',
												msg: 'Failed : ' + text ,
												buttons: Ext.MessageBox.OK
											});
										}
										loadmask.hide();
									}
								});
							}
						}
					}
					]
				}
			],
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: qc_working_list_inbound_store_<?=$tab_id?>
			})],
			features: [qc_working_list_inbound_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
		});
		
		var qc_working_list_outbound_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			//fields:['BAY', 'LOCATION', 'TWENTY', 'FOURTY', 'TOTAL','SUMDISCHLOAD', 'REMAIN', 'MACHINE', 'ESTIMATE_TIME','ACTIVE','SEQUENCE'],
			fields:['SEQUENCE','BAY', 'LOCATION', 'TWENTY', 'FOURTY', 'TOTAL','SUMDISCHLOAD', 'REMAIN', 'MACHINE', 'ESTIMATE_TIME','ACTIVE'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>qc_working_list/data_qc_working_list?id_ves_voyage=<?=$id_ves_voyage?>&type=E',
				reader: {
					type: 'json',
					root: 'data',
					idProperty: 'QC_WORKING_LIST',
					totalProperty: 'total'
				}
			}
		});
		
		var qc_working_list_outbound_filters_<?=$tab_id?> = {
			ftype: 'filters',
			encode: false,
			local: true
		};
		
		var qc_working_list_outbound_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			store: qc_working_list_outbound_store_<?=$tab_id?>,
			title : 'Outbound',
			loadMask: true,
			width: 900,
			columns: [
				{ text: 'SEQUENCE', dataIndex: 'SEQUENCE', width: 90},
				{ text: 'BAY', dataIndex: 'BAY', width: 80, filter: {type: 'string'}},
				{ text: 'LOCATION', dataIndex: 'LOCATION' , width: 80},
				{ text: '20\'', dataIndex: 'TWENTY' , width: 40},
				{ text: '40\'', dataIndex: 'FOURTY' , width: 40},
				{ text: 'TOTAL', dataIndex: 'TOTAL', width: 60},
				{ text: 'COMPLETED', dataIndex: 'SUMDISCHLOAD', width: 60},
				{ text: 'REMAIN', dataIndex: 'REMAIN' , width: 80},
				{ text: 'MACHINE', dataIndex: 'MACHINE', width: 100},
				{ text: 'ESTIMATE TIME', dataIndex: 'ESTIMATE_TIME', width: 120},
				{ text: 'ACTIVE', dataIndex: 'ACTIVE', width: 60},
				//{ text: 'SEQUENCE', dataIndex: 'SEQUENCE', width: 90},
				{ 
					text: 'ACTION',
					menuDisabled: true,
					sortable: false,
					xtype: 'actioncolumn',
					width: 80,
					items: [{
						//var rec = grid.getStore().getAt(rowIndex);
						icon : '<?=IMG_?>icons/stop.png',
						//iconCls: 'sell-col',
						tooltip: 'Stop',
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.getStore().getAt(rowIndex);
							if(!rec.get('MACHINE')){
								Ext.Msg.alert('Error', 'Machine hast not been set');
							} else {	
								loadmask.show();
								Ext.Ajax.request({
									url: '<?=controller_?>qc_working_list/deactivate?id_ves_voyage=<?=$id_ves_voyage?>&bay='+rec.get('BAY')+'&location='+rec.get('LOCATION')+'&activity=E',
									success: function(response){
										var text = response.responseText;
										console.log(text);
										if (text=='1'){
											Ext.MessageBox.show({
												title: 'Success',
												msg: 'Changes saved successfully.',
												buttons: Ext.MessageBox.OK
											});
											qc_working_list_outbound_store_<?=$tab_id?>.reload();
										}else{
											Ext.MessageBox.show({
												title: 'Error',
												msg: 'Failed to save changes. ' + text,
												buttons: Ext.MessageBox.OK
											});
										}
										loadmask.hide();
									}
								});
							}
						}
					},
					'-',
					{
						//var rec = grid.getStore().getAt(rowIndex);
						icon : '<?=IMG_?>icons/start.png',
						//iconCls: 'sell-col',
						tooltip: 'Start',
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.getStore().getAt(rowIndex);
							if(!rec.get('MACHINE')){
								Ext.Msg.alert('Error', 'Machine hast not been set');
							} else {	
								loadmask.show();
								Ext.Ajax.request({
									url: '<?=controller_?>qc_working_list/activate?id_ves_voyage=<?=$id_ves_voyage?>&bay='+rec.get('BAY')+'&location='+rec.get('LOCATION')+'&activity=E',
									success: function(response){
										var text = response.responseText;
										console.log('text : ' + text);
										if (text=='1'){
											Ext.MessageBox.show({
												title: 'Success',
												msg: 'Changes saved successfully.',
												buttons: Ext.MessageBox.OK
											});
											qc_working_list_outbound_store_<?=$tab_id?>.reload();
										}else{
											Ext.MessageBox.show({
												title: 'Error',
												msg: 'Failed: '+text,
												buttons: Ext.MessageBox.OK
											});
										}
										loadmask.hide();
									}
								});
							}
						}
					}
					]
				}
			],
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: qc_working_list_outbound_store_<?=$tab_id?>
			})],
			features: [qc_working_list_outbound_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
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
		
		qc_working_list_outbound_grid_<?=$tab_id?>.child('pagingtoolbar').add([
			'-',{
				text: 'Clear Filter Data',
				handler: function () {
					qc_working_list_outbound_grid.filters.clearFilters();
				}
			},'-',{
				text: 'Deselect Data',
				handler: function () {
					qc_working_list_outbound_grid.getSelectionModel().deselectAll();
					id_ves_voyage = '';
				}
			}
		]);
		
		qc_working_list_inbound_grid_<?=$tab_id?>.render('qc_working_list_inbound_<?=$tab_id?>');
		qc_working_list_outbound_grid_<?=$tab_id?>.render('qc_working_list_outbound_<?=$tab_id?>');
	});

</script>
<div id="qc_working_list_inbound_<?=$tab_id?>"></div>
<div id="qc_working_list_outbound_<?=$tab_id?>"></div>