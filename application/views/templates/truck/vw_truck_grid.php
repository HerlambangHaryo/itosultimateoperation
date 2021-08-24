<script type="text/javascript">
    var truck_store;
	Ext.onReady(function() {
		truck_store = Ext.create('Ext.data.Store', {
			fields:['ID_TRUCK','TID', 'NO_POL', 'STATUS_GATE'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>truck/data_truck',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 20
		});
		
		var port_filters_<?=$tab_id?> = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var truck_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'truck_grid_<?=$tab_id?>',
			store: truck_store,
			width: 1200,
			columns: [
				{ text: 'ID TRUCK', dataIndex: 'ID_TRUCK', hidden: true},
				{ text: 'TID', dataIndex: 'TID', width: 100, filter: {type: 'string'}},
				{ text: 'No Polisi', dataIndex: 'NO_POL' , width: 100, filter: {type: 'string'}},
				{ text: 'Status Gate', dataIndex: 'STATUS_GATE', width: 100},				
				/*edit.png*/
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
						var id_truck = rec.get('ID_TRUCK');
//						var tid = rec.get('TID');
//						addTab('center_panel', 'truck/form_editTruck/'+id_truck, '', 'Edit Truck: ' + tid);
						Ext.Ajax.request({
						    url: '<?=controller_?>truck/form_editTruck?tab_id=<?=$tab_id?>',
						    params: {
							id_truck: id_truck
						    },
						    callback: function(opt,success,response){
							$("#popup_script_<?=$tab_id?>").html(response.responseText);
						    }
						});
						return false;

						}
					},{
						icon: "<?=IMG_?>icons/delete.png",
						tooltip: 'Delete',
						handler: function(grid, rowIndex, colIndex) {

						    var rec = grid.getStore().getAt(rowIndex);
						    var id_truck = rec.get('ID_TRUCK');
						    var tid = rec.get('TID');

						    Ext.MessageBox.confirm('Confirm', 'Apakah anda yakin akan menghapus '+tid+'?', showResult);
							function showResult(btn)
							{
								if(btn=='yes')
								{		
									var url = "<?=controller_?>truck/delete_truck";
									Ext.Ajax.request({
									    url: url,
									    method: 'POST',
									    params: {
										    ID_TRUCK: id_truck,
										    TID: tid
									    },
									    scope: this,
									    success: function(result, response) {
										loadmask.hide();
										var res = JSON.parse(result.responseText);
										var status = res.IsSuccess ? 'Success' : 'Failed';

										Ext.Msg.alert(status, res.Message);
										if(res.IsSuccess){
										    truck_store.reload();
										}
									    },
									    failure:function(form, response) {
										Ext.Msg.alert('Failed: ', response.errorMessage);
									    }
									});
								}
								else
								{
									Ext.MessageBox.alert('Status', 'Cancel.');
								}
							}
						}
					}]
				}				
			],
			tbar: [
					 { xtype: 'button', 
					   text: 'Add Truck',
					   handler: function (){
//					   		addTab('center_panel', 'truck/form_addTruck', '', 'Add Truck');
							Ext.Ajax.request({
							    url: '<?=controller_?>truck/form_addTruck?tab_id=<?=$tab_id?>',
							    callback: function(opt,success,response){
								$("#popup_script_<?=$tab_id?>").html(response.responseText);
							    }
							});
					   } }
			],			
			viewConfig : {
				enableTextSelection: true
			},
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: truck_store,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							truck_grid_<?=$tab_id?>.filters.clearFilters();
						}
					}
				]
			})],
			features: [port_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
		});
		
		Ext.getCmp('west_panel').expand();
		truck_grid_<?=$tab_id?>.render('truck_grid_<?=$tab_id?>');
		
		add_title();	
	});

function add_title(){
	setTimeout(
	function() 
	{
		$('.x-action-col-0 ').attr('title','Edit');
		$('.x-action-col-2 ').attr('title','Delete');
	}, 1000);	
}


</script>
<div id="truck_grid_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>