<script type="text/javascript">
    var equipment_stoppage_store;
	Ext.onReady(function() {
		equipment_stoppage_store = Ext.create('Ext.data.Store', {
			fields:['ID_SUSPEND','ACTIVITY', 'EQ_TYPE', 'GROUP_APP', 'CATEGORY'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>equipment_stoppage/data_stoppages',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 20
		});
		
		var stoppage_filters_<?=$tab_id?> = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var stoppage_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'stoppage_grid_<?=$tab_id?>',
			store: equipment_stoppage_store,
			width: 700,
			columns: [
				{ text: 'ID SUSPEND', dataIndex: 'ID_SUSPEND', hidden: true},
				{ text: 'ACTIVITY', dataIndex: 'ACTIVITY', width: 400, filter: {type: 'string'}},
				{ text: 'EQ Type', dataIndex: 'EQ_TYPE' , width: 100, filter: {type: 'list'}},
				{ text: 'Category', dataIndex: 'CATEGORY' , width: 100, filter: {type: 'list'}},
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
						var id_suspend = rec.get('ID_SUSPEND');
						Ext.Ajax.request({
						    url: '<?=controller_?>equipment_stoppage/form_editStoppage?tab_id=<?=$tab_id?>',
						    params: {
							id_suspend: id_suspend
						    },
						    callback: function(opt,success,response){
							$("#popup_script_<?=$tab_id?>").html(response.responseText);
						    }
						});
						return false;

						}
					},{},{
						icon: "<?=IMG_?>icons/delete.png",
						tooltip: 'Delete',
						handler: function(grid, rowIndex, colIndex) {

						    var rec = grid.getStore().getAt(rowIndex);
						    var id_suspend = rec.get('ID_SUSPEND');
						    var activity = rec.get('ACTIVITY');

						    Ext.MessageBox.confirm('Confirm', 'Apakah anda yakin akan menghapus '+activity+'?', showResult);
							function showResult(btn)
							{
								if(btn=='yes')
								{		
									var url = "<?=controller_?>equipment_stoppage/delete_stoppage";
									Ext.Ajax.request({
									    url: url,
									    method: 'POST',
									    params: {
										    ID_SUSPEND: id_suspend,
										    ACTIVITY: activity
									    },
									    scope: this,
									    success: function(result, response) {
										loadmask.hide();
										var res = JSON.parse(result.responseText);
										var status = res.IsSuccess ? 'Success' : 'Failed';

										Ext.Msg.alert(status, res.Message);
										if(res.IsSuccess){
										    equipment_stoppage_store.reload();
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
					   text: 'Add Equipment Stoppage',
					   handler: function (){
//					   		addTab('center_panel', 'truck/form_addTruck', '', 'Add Truck');
							Ext.Ajax.request({
							    url: '<?=controller_?>equipment_stoppage/form_addStoppage?tab_id=<?=$tab_id?>',
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
				store: equipment_stoppage_store,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							stoppage_grid_<?=$tab_id?>.filters.clearFilters();
						}
					}
				]
			})],
			features: [stoppage_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
		});
		
		Ext.getCmp('west_panel').expand();
		stoppage_grid_<?=$tab_id?>.render('stoppage_grid_<?=$tab_id?>');
		
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
<div id="stoppage_grid_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>