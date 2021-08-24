<script type="text/javascript">
    var role_store;
	Ext.onReady(function() {
		role_store = Ext.create('Ext.data.Store', {
			fields:['ID_GROUP','GROUP_NAME'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>roles/data_roles',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 20
		});
		
		var role_filters_<?=$tab_id?> = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var role_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'role_grid_<?=$tab_id?>',
			store: role_store,
			width: 730,
			columns: [
				{ text: 'ID GROUP', dataIndex: 'ID_GROUP', hidden: true},
				{ text: 'ROLE', dataIndex: 'GROUP_NAME', width: 600, filter: {type: 'string'}},
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
						    var id_group = rec.get('ID_GROUP');
    //						var tid = rec.get('TID');
    //						addTab('center_panel', 'truck/form_editTruck/'+id_truck, '', 'Edit Truck: ' + tid);
						    Ext.Ajax.request({
							url: '<?=controller_?>roles/form_editRole?tab_id=<?=$tab_id?>',
							params: {
							    id_group: id_group
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
						    var id_group = rec.get('ID_GROUP');
						    var group_name = rec.get('GROUP_NAME');

						    Ext.MessageBox.confirm('Confirm', 'Apakah anda yakin akan menghapus '+group_name+'?', showResult);
							function showResult(btn)
							{
								if(btn=='yes')
								{		
									var url = "<?=controller_?>roles/delete_group";
									Ext.Ajax.request({
									    url: url,
									    method: 'POST',
									    params: {
										    ID_GROUP: id_group,
										    GROUP_NAME: group_name
									    },
									    scope: this,
									    success: function(result, response) {
										loadmask.hide();
										var res = JSON.parse(result.responseText);
										var status = res.IsSuccess ? 'Success' : 'Failed';

										Ext.Msg.alert(status, res.Message);
										if(res.IsSuccess){
										    role_store.reload();
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
						icon: "<?=IMG_?>icons/menu.png",
						tooltip: 'Assign Menu',
						handler: function(grid, rowIndex, colIndex) {
						    loadmask.show();
						    var rec = grid.getStore().getAt(rowIndex);
						    var id_group = rec.get('ID_GROUP');
						    var group_name = rec.get('GROUP_NAME');
						    Ext.Ajax.request({
							url: '<?=controller_?>roles/assign_menu_form?tab_id=<?=$tab_id?>',
							params: {
							    ID_GROUP: id_group,
							    GROUP_NAME: group_name
							},
							callback: function(opt,success,response){
							    loadmask.hide();
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
					   text: 'Add Role',
					   handler: function (){
//					   		addTab('center_panel', 'truck/form_addTruck', '', 'Add Truck');
							Ext.Ajax.request({
							    url: '<?=controller_?>roles/form_addRole?tab_id=<?=$tab_id?>',
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
				store: role_store,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							role_grid_<?=$tab_id?>.filters.clearFilters();
						}
					}
				]
			})],
			features: [role_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
		});
		
		Ext.getCmp('west_panel').expand();
		role_grid_<?=$tab_id?>.render('role_grid_<?=$tab_id?>');
		
		add_title();	
	});

function add_title(){
	setTimeout(
	function() 
	{
		$('.x-action-col-0 ').attr('title','Edit');
		$('.x-action-col-2 ').attr('title','Delete');
		$('.x-action-col-4 ').attr('title','Menu Access');
	}, 1000);	
}


</script>
<div id="role_grid_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>