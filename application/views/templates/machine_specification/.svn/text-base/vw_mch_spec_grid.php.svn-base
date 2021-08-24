<script type="text/javascript">
    var mch_spec_store;
	Ext.onReady(function() {
		mch_spec_store = Ext.create('Ext.data.Store', {
			fields:['ID_MACHINE', 'MCH_NAME', 'MCH_TYPE', 'MCH_SUB_TYPE','SIZE_CHASSIS','STANDARD_BCH','POOL_NAME','BG_COLOR'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>machine_specification/data_mch_spec',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 20
		});
		
		var mch_spec_filters_<?=$tab_id?> = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var mch_spec_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'mch_spec_grid_<?=$tab_id?>',
			store: mch_spec_store,
			width: 900,
			columns: [
				{ text: 'ID MACHINE', dataIndex: 'ID_MACHINE', hidden: true},
				{ text: 'Code', dataIndex: 'MCH_NAME' , width: 150, filter: {type: 'string'}},
				{ text: 'Type', dataIndex: 'MCH_TYPE', width: 100, filter: {type: 'list'}},
				{ text: 'Sub Type', dataIndex: 'MCH_SUB_TYPE', width: 100, filter: {type: 'list'}},
				{ text: 'BCH', dataIndex: 'STANDARD_BCH', width: 70, filter: {type: 'string'}},
				{ text: 'Size Chasis', dataIndex: 'SIZE_CHASSIS', width: 100, filter: {type: 'string'}},
				{ text: 'Pool Name', dataIndex: 'POOL_NAME', width: 100, filter: {type: 'string'}},
				{ text: 'Background Color', dataIndex: 'BG_COLOR', width: 150,
				    renderer: function(value) {
					return Ext.String.format('<span style="background-color:{1};">__________</span>', value, value);
				    }
				},				
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
						var id_machine = rec.get('ID_MACHINE');
						addTab('center_panel', 'machine_specification/form_editMch/'+id_machine, '', 'Edit Machine');
//						Ext.Ajax.request({
//						    url: '<?=controller_?>machine_specification/form_editMch?tab_id=<?=$tab_id?>',
//						    params: {
//							id_machine: id_machine
//						    },
//						    callback: function(opt,success,response){
//							$("#popup_script_<?=$tab_id?>").html(response.responseText);
//						    }
//						});

						}
					},{},{
						icon: "<?=IMG_?>icons/delete.png",
						tooltip: 'Delete',
						handler: function(grid, rowIndex, colIndex) {

						    var rec = grid.getStore().getAt(rowIndex);
						    var id_machine = rec.get('ID_MACHINE');
						    var mch_name = rec.get('MCH_NAME');

						    Ext.MessageBox.confirm('Confirm', 'Apakah anda yakin akan menghapus '+mch_name+'?', showResult);
							function showResult(btn)
							{
								if(btn=='yes')
								{		
									var url = "<?=controller_?>machine_specification/delete_mch";
									Ext.Ajax.request({
									    url: url,
									    method: 'POST',
									    params: {
										    ID_MACHINE: id_machine,
										    MCH_NAME: mch_name
									    },
									    scope: this,
									    success: function(result, response) {
										loadmask.hide();
										var res = JSON.parse(result.responseText);
										var status = res.IsSuccess ? 'Success' : 'Failed';

										Ext.Msg.alert(status, res.Message);
										if(res.IsSuccess){
										    mch_spec_store.reload();
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
					   text: 'Add Machine',
					   handler: function (){
					   		addTab('center_panel', 'machine_specification/form_addMch', '', 'Add Machine');
					   } }
			],			
			viewConfig : {
				enableTextSelection: true
			},
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: mch_spec_store,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							mch_spec_grid_<?=$tab_id?>.filters.clearFilters();
						}
					}
				]
			})],
			features: [mch_spec_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
		});
		
		Ext.getCmp('west_panel').expand();
		mch_spec_grid_<?=$tab_id?>.render('mch_spec_grid_<?=$tab_id?>');
		
		add_title();	
	});

function add_title(){
	setTimeout(
	function() 
	{
		$('.x-action-col-0 ').attr('title','enabled');
		$('.x-action-col-2 ').attr('title','disabled');
	}, 1000);	
}


</script>
<div id="mch_spec_grid_<?=$tab_id?>"></div>