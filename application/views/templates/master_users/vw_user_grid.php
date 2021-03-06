<script type="text/javascript">
    var user_store_<?=$tab_id?>;
	Ext.onReady(function() {
		user_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['ID_USER','FULL_NAME', 'NICK_NAME', 'USERNAME','ROLE_PDA','ROLE_GATE','ROLE_TALLY', 'ROLE_VMT', 'ROLE_PAGER','ROLE_YARD','ROLE_REEFER', 'ROLE_QC', 'ROLE_ITV','TERMINAL','GROUP_NAME'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>users/data_user',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 20
		});
		
		var users_filters_<?=$tab_id?> = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var user_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'user_grid_<?=$tab_id?>',
			store: user_store_<?=$tab_id?>,
			width: 1770,
			columns: [
				{ text: 'ID USER', dataIndex: 'ID_USER', hidden: true},
				{ text: 'Full Name', dataIndex: 'FULL_NAME', width: 200, filter: {type: 'string'}},
				{ text: 'Nick Name', dataIndex: 'NICK_NAME' , width: 100, filter: {type: 'string'}},
				{ text: 'Username', dataIndex: 'USERNAME', width: 100, filter: {type: 'string'}},
				{ text: 'Role PDA', dataIndex: 'ROLE_PDA', width: 100,filter: {type: 'list'}},
//				{ text: 'Role Tally', dataIndex: 'ROLE_TALLY', width: 100, filter: {type: 'list'}},
				{ text: 'Role VMT', dataIndex: 'ROLE_VMT' , width: 100, filter: {type: 'list'}},
				{ text: 'Role Pager', dataIndex: 'ROLE_PAGER', width: 100,filter: {type: 'list'}},
//				{ text: 'Role Yard', dataIndex: 'ROLE_YARD', width: 100,filter: {type: 'list'}},
//				{ text: 'Role Reefer', dataIndex: 'ROLE_REEFER', width: 100,filter: {type: 'list'}},
				{ text: 'Role QC', dataIndex: 'ROLE_QC', width: 100, filter: {type: 'list'}},
//				{ text: 'Role ITV', dataIndex: 'ROLE_ITV' , width: 100, filter: {type: 'list'}},
				{ text: 'Terminal', dataIndex: 'TERMINAL', width: 400, filter: {type: 'list'}},				
				{ text: 'Group', dataIndex: 'GROUP_NAME', width: 200, filter: {type: 'string'}},				
				/*edit.png*/
				{ 
					text: 'Actions',
					width: 160,
					align: 'center',
					xtype: 'actioncolumn',
					items: [{
						icon: "<?=IMG_?>icons/edit.png",
						tooltip: 'Edit',
						handler: function(grid, rowIndex, colIndex) {

						    var rec = grid.getStore().getAt(rowIndex);
						    var id_user = rec.get('ID_USER');
						    console.log("id_user : " + id_user);
    //						addTab('center_panel', 'truck/form_editTruck/'+id_truck, '', 'Edit Truck: ' + tid);
						    Ext.Ajax.request({
							url: '<?=controller_?>users/form_editUser?tab_id=<?=$tab_id?>',
							params: {
							    id_user: id_user
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
						    var id_user = rec.get('ID_USER');
						    var full_name = rec.get('FULL_NAME');

						    Ext.MessageBox.confirm('Confirm', 'Apakah anda yakin akan menghapus '+full_name+'?', showResult);
							function showResult(btn)
							{
								if(btn=='yes')
								{		
									var url = "<?=controller_?>users/delete_user";
									Ext.Ajax.request({
									    url: url,
									    method: 'POST',
									    params: {
										    id_user: id_user,
										    full_name: full_name
									    },
									    scope: this,
									    success: function(result, response) {
										loadmask.hide();
										var res = JSON.parse(result.responseText);
										var status = res.IsSuccess ? 'Success' : 'Failed';

										Ext.Msg.alert(status, res.Message);
										if(res.IsSuccess){
										    user_store_<?=$tab_id?>.reload();
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
						icon: "<?=IMG_?>icons/reset_password.png",
						tooltip: 'Reset Password',
						handler: function(grid, rowIndex, colIndex) {

						    var rec = grid.getStore().getAt(rowIndex);
						    var id_user = rec.get('ID_USER');
						    var full_name = rec.get('FULL_NAME');
						    var username = rec.get('USERNAME');
	
						    var url = "<?=controller_?>users/reset_password";
						    Ext.Ajax.request({
							url: url,
							method: 'POST',
							params: {
								id_user: id_user,
								full_name: full_name,
								username: username
							},
							callback: function(opt,success,response){
							    $("#popup_script_<?=$tab_id?>").html(response.responseText);
							}
						    });
						}
					}]
				}				
			],
			tbar: [
					 { xtype: 'button', 
					   text: 'Add User',
					   handler: function (){
//					   		addTab('center_panel', 'truck/form_addTruck', '', 'Add Truck');
							Ext.Ajax.request({
							    url: '<?=controller_?>users/form_addUser?tab_id=<?=$tab_id?>',
							    callback: function(opt,success,response){
								$("#popup_script_<?=$tab_id?>").html(response.responseText);
							    }
							});
					   } 
					}
			],			
			viewConfig : {
				enableTextSelection: true
			},
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: user_store_<?=$tab_id?>,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							user_grid_<?=$tab_id?>.filters.clearFilters();
						}
					}
				]
			})],
			features: [users_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
		});
		
		Ext.getCmp('west_panel').expand();
		user_grid_<?=$tab_id?>.render('user_grid_<?=$tab_id?>');
		
		add_title();	
	});

function add_title(){
	setTimeout(
	function() 
	{
		$('.x-action-col-0 ').attr('title','Edit');
		$('.x-action-col-2 ').attr('title','Delete');
		$('.x-action-col-4 ').attr('title','Reset Password');
	}, 1000);	
}


</script>
<div id="user_grid_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>