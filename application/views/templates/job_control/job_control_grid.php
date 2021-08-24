<script type="text/javascript">
    var job_control_store;
	Ext.onReady(function() {
		job_control_store = Ext.create('Ext.data.Store', {
			fields:['ID_POOL','POOL_NAME', 'ID_MACHINE', 'MCH_NAME', 'ACTIVE'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>job_control/get_data_job_control',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 20
		});
		
		var job_control_filters_<?=$tab_id?> = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var contextMenu = Ext.create('Ext.menu.Menu', {
			items: [
<?php
			foreach($pool['data'] as $p){
?>
				{
//				    id:'pool_<?=$p['ID_POOL']?>',
				    xtype: 'menucheckitem',
				    text: '<?=$p['POOL_NAME']?>',
				    value: '<?=$p['ID_POOL']?>',
				    group :'itemGroup',
//				    checked:true,
				    handler: function() {
					var rec = job_control_grid_<?=$tab_id?>.getSelectionModel().getSelection()[0]['data'];
//					console.log('rec : ' + rec['ID_MACHINE']);
					Ext.Ajax.request({
					    url: '<?=controller_?>job_control/assign_pool_mch',
					    method: 'POST',
					    params: {
						mch : rec['ID_MACHINE'],
						mch_name : rec['MCH_NAME'],
						pool : '<?=$p['ID_POOL']?>'
					    },
					    scope: this,
					    success: function(result, response) {
						loadmask.hide();
						var res = JSON.parse(result.responseText);
						var status = res.IsSuccess ? 'Success' : 'Failed';

						Ext.Msg.alert(status, res.Message);
						if(res.IsSuccess){
//						    Ext.getCmp('<?=$tab_id?>').close();
						    job_control_store.reload();
						}
					    },
					    failure:function(form, response) {
						Ext.Msg.alert('Failed: ', response.errorMessage);
					    }
					})
//					Ext.Msg.alert("Test", "Handler");
				    }
				},
<?php
			}
?>
			]
		});
		
		var job_control_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'job_control_grid_<?=$tab_id?>',
			store: job_control_store,
			width: 600,
			columns: [
				{ text: 'ID POOL', dataIndex: 'ID_POOL', width: 0},
				{ text: 'POOL NAME', dataIndex: 'POOL_NAME', width: 200, filter: {type: 'string'}},
				{ text: 'ID MACHINE', dataIndex: 'ID_MACHINE', width: 0},
				{ text: 'MCH NAME', dataIndex: 'MCH_NAME' , width: 250, filter: {type: 'string'}},
				{ text: 'ACTIVE', dataIndex: 'ACTIVE', width: 150,filter: {type: 'list'}}				
			],			
			viewConfig : {
				enableTextSelection: true,
				listeners: {
					itemcontextmenu: function(view, rec, node, index, e) {
						var id_pool = rec['data']['ID_POOL'];
						e.stopEvent();
						contextMenu.showAt(e.getXY());
						$.each(contextMenu.items.items, function(x,y){
						    var item=contextMenu.getComponent(x);
						    item.checked = false;
						    Ext.get(item.el.dom).removeCls('x-menu-item-checked');
						    Ext.get(item.el.dom).removeCls('x-menu-item-unchecked');
						    Ext.get(item.el.dom).addCls('x-menu-item-unchecked');
						    if(y.value == id_pool){
							item.checked = true;
							Ext.get(item.el.dom).removeCls('x-menu-item-unchecked');
							Ext.get(item.el.dom).addCls('x-menu-item-checked');
						    }
						});
						return false;
					}
				}
			},
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: job_control_store,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							job_control_grid_<?=$tab_id?>.filters.clearFilters();
						}
					}
				]
			})],
			features: [job_control_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
		});
		
		Ext.getCmp('west_panel').expand();
		job_control_grid_<?=$tab_id?>.render('job_control_grid_<?=$tab_id?>');
		
	});

</script>
<div id="job_control_grid_<?=$tab_id?>"></div>