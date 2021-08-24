<script type="text/javascript"> 
    var pool_store;
	Ext.onReady(function() {
		pool_store = Ext.create('Ext.data.Store', {
			fields:['ID_POOL','POOL_NAME', 'POOL_DESCRIPTION', 'CTRUCK', 'POOL_TYPE'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>pool/data_pool',
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
		
		var pool_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'pool_grid_<?=$tab_id?>',
			store: pool_store,
			width: 750,
			columns: [
				{ text: 'ID_POOL', dataIndex: 'ID_POOL', hidden: true},
				{ text: 'Pool', dataIndex: 'POOL_NAME', width: 100, filter: {type: 'string'}},
				{ text: 'Pool Description', dataIndex: 'POOL_DESCRIPTION' , width: 200, filter: {type: 'string'}},
				{ text: 'Pool Type', dataIndex: 'POOL_TYPE', width: 100},		
				{ text: 'Jumlah Truck', dataIndex: 'CTRUCK', width: 100, filter: {type: 'number'}},
				/*edit.png*/
				{ 
					text: 'Actions',
					width: 130,
					align: 'center',
					xtype: 'actioncolumn',
					items: [{
						icon: "<?=IMG_?>icons/edit.png",
						tooltip: 'Edit',
						title: 'Edit',
						handler: function(grid, rowIndex, colIndex) {

						    var rec = grid.getStore().getAt(rowIndex);
						    var id_pool = rec.get('ID_POOL');
						    var pool_name = rec.get('POOL_NAME');
//						    addTab('center_panel', 'pool/form_editPool/'+id_pool, '', 'Edit Pool: ' + pool_name);
						    Ext.Ajax.request({
							url: '<?=controller_?>pool/form_editPool?tab_id=<?=$tab_id?>',
							params: {
							    id_pool: id_pool
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
						title: 'Delete',
						handler: function(grid, rowIndex, colIndex) {

						    var rec = grid.getStore().getAt(rowIndex);
						    var id_pool = rec.get('ID_POOL');
						    var pool_name = rec.get('POOL_NAME');

						    var rec = grid.getStore().getAt(rowIndex);
						    Ext.MessageBox.confirm('Confirm', 'Apakah anda yakin akan menghapus '+pool_name+'?', showResult);
							function showResult(btn)
							{
								if(btn=='yes')
								{		
								    var url = "<?=controller_?>pool/delete_pool";
								    Ext.Ajax.request({
									url: url,
									method: 'POST',
									params: {
										ID_POOL: id_pool,
										POOL_NAME: pool_name
									},
									scope: this,
									success: function(result, response) {
									    loadmask.hide();
									    var res = JSON.parse(result.responseText);
									    var status = res.IsSuccess ? 'Success' : 'Failed';

									    Ext.Msg.alert(status, res.Message);
									    if(res.IsSuccess){
										pool_store.reload();
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
					},{},{
						icon: "<?=IMG_?>icons/assign_truck.png",
						tooltip: 'Assign ITV',
						title: 'Assign ITV',
						handler: function(grid, rowIndex, colIndex) {
						    var rec = grid.getStore().getAt(rowIndex);
						    var id_pool = rec.get('ID_POOL');
						    var pool_name = rec.get('POOL_NAME');
						    var pool_description = rec.get('POOL_DESCRIPTION');
						    var pool_type = rec.get('POOL_TYPE');
//						    addTab('center_panel', 'pool/form_assignPool/'+id_pool+'/'+pool_name+'/'+pool_description+'/'+pool_type, '', 'Assign Pool: ' + pool_name);
						    Ext.Ajax.request({
							url: '<?=controller_?>pool/form_assignPool?tab_id=<?=$tab_id?>',
							params: {
							    id_pool: id_pool,
							    pool_name: pool_name,
							    pool_description: pool_description,
							    pool_type: pool_type
							},
							callback: function(opt,success,response){
							    $("#popup_script_<?=$tab_id?>").html(response.responseText);
							}
						    });
						    return false;
						    
						}
					}]
				}				
			],
			tbar: [
					 { xtype: 'button', 
					   text: 'Add Pool',
					   handler: function (){
//					   		addTab('center_panel', 'pool/form_addPool', '', 'Add Pool');
						Ext.Ajax.request({
						    url: '<?=controller_?>pool/form_addPool?tab_id=<?=$tab_id?>',
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
				store: pool_store,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							pool_grid_<?=$tab_id?>.filters.clearFilters();
						}
					}
				]
			})],
			features: [port_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
		});
		
		Ext.getCmp('west_panel').expand();
		pool_grid_<?=$tab_id?>.render('pool_grid_<?=$tab_id?>');
		
		add_title();	
	});

function add_title(){
	setTimeout(
	function() 
	{
		$('.x-action-col-0 ').attr('title','Edit');
		$('.x-action-col-2 ').attr('title','Delete');
		$('.x-action-col-4 ').attr('title','Assign Pool');
	}, 1000);	
}


</script>
<div id="pool_grid_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>