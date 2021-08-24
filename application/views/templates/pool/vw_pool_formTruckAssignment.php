<script type="text/javascript">
	
//===================	
$(function() {
	var pool_assingment_store = Ext.create('Ext.data.Store', {
			fields:['ID_MACHINE','MCH_NAME','ID_POOL','POOL_NAME','CHECKLIST'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>pool/get_pool_itv/<?=$id_pool?>',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 20
		});
	
	var pool_assingment_filters = {
			ftype: 'filters',
			encode: true,
			local: true
		};
		
	var grid = Ext.create('Ext.grid.Panel', {
			id: 'pool_assign_grid_<?=$tab_id?>',
			store: pool_assingment_store,
			width: 600,
			mode: 'local',
			columns: [
				{ text: 'ID_POOL', dataIndex: 'ID_POOL', hidden: false,
				    renderer: function(value) {
					return Ext.String.format('<input type="text" name="id_pool" value="{1}">', value, value);
				    }
				},
				{ text: 'ID_MACHINE', dataIndex: 'ID_MACHINE', hidden: false,
				    renderer: function(value) {
					return Ext.String.format('<input type="text" name="id_machine" value="{1}">', value, value);
				    }
				},
				{ text: 'ITV', dataIndex: 'MCH_NAME', width: 100, filter: {type: 'string'}},
				{ text: 'POOL', dataIndex: 'POOL_NAME', width: 100, filter: {type: 'string'}},				
				{ text: 'Assign', dataIndex: 'CHECKLIST', width: 100,
				    renderer: function(value) {
					return Ext.String.format('<input type="checkbox" name="chk-assign" class="checked chk-assign-<?=$tab_id?>" value="{1}">', value, value);
				    }
				}
			],			
			viewConfig : {
				enableTextSelection: true
			},
//			dockedItems: [Ext.create('Ext.toolbar.Paging', {
//				dock: 'bottom',
//				store: pool_assingment_store,
//				displayInfo: false,
//				displayMsg: '',
//				items: [
//					'-',{
//						text: 'Clear Filter Data',
//						handler: function () {
//							pool_assingment_store.store.clearFilters();
//						}
//					}
//				]
//			})],
			features: [pool_assingment_filters],
			emptyText: 'No Data Found'
		});
		
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Pool Assignment',
		closable: true,
		items: [Ext.create('Ext.form.Panel', {
			    id: 'pool_assign_form_<?=$tab_id?>',
			    frame: true,
			    bodyPadding: 5,
			    fieldDefaults: {
				    labelAlign: 'left',
				    labelWidth: 100
			    },
			    items: [Ext.create('Ext.grid.Panel', {
					id: 'pool_assign_grid_<?=$tab_id?>',
					store: pool_assingment_store,
					width: 300,
					height:350,
					mode: 'local',
					columns: [
					    { text: 'ID_POOL', dataIndex: 'ID_POOL', hidden: false, width: 0,
						renderer: function(value) {
						    return Ext.String.format('<input type="hidden" name="id-pool" class="id-pool-<?=$tab_id?>" value="{1}">', value, value);
						}
					    },
					    { text: 'ID_MACHINE', dataIndex: 'ID_MACHINE', hidden: false, width: 0,
						renderer: function(value) {
						    return Ext.String.format('<input type="hidden" name="id-machine" class="id-machine-<?=$tab_id?>" value="{1}">', value, value);
						}
					    },
					    { text: 'ITV', dataIndex: 'MCH_NAME', width: 100, filter: {type: 'string'}},
					    { text: 'POOL', dataIndex: 'POOL_NAME', width: 100, filter: {type: 'string'}},				
					    { text: 'Assign', dataIndex: 'ID_POOL', width: 100,
						renderer: function(value) {
						    console.log(value + ' == ' + <?=$id_pool?>);
						    var checked = value == <?=$id_pool?> ? "checked" : "";
						    return Ext.String.format('<input type="checkbox" name="chk-assign" class="checked chk-assign-<?=$tab_id?>" value="{1}" ' + checked + '>', value, value);
						}
					    }
					],			
					viewConfig : {
						enableTextSelection: true
					},
		//			dockedItems: [Ext.create('Ext.toolbar.Paging', {
		//				dock: 'bottom',
		//				store: pool_assingment_store,
		//				displayInfo: false,
		//				displayMsg: '',
		//				items: [
		//					'-',{
		//						text: 'Clear Filter Data',
		//						handler: function () {
		//							pool_assingment_store.store.clearFilters();
		//						}
		//					}
		//				]
		//			})],
					features: [pool_assingment_filters],
					emptyText: 'No Data Found'
				})],
			    buttons: [{
				    text: 'Save',
				    formBind: true,
				    handler: function() {
					var dataCheck = [];
//					var form = this.up('form').getForm();
//					var mch = Ext.dom.Query.select('.id_machine-<?=$tab_id?>');
					var mch = Ext.dom.Query.select('.id-machine-<?=$tab_id?>');
					var chk = Ext.dom.Query.select('.chk-assign-<?=$tab_id?>');
//					$.each(mch, function(x,y){
////					    console.log(mch[x]);
////					    var temp_mch = mch[x];
//					    console.log(x);
//					    console.log(y);
//					    console.log('value mch : ' + y.checked);
//					    if(y.checked){
//						dataCheck.push(y.value);
//					    }
//					});
					$.each(chk, function(x,y){
					    console.log(x);
					    console.log(y);
					    console.log('value chk : ' + y.checked);
					    console.log('value mch : ' + mch[x].value);
					    if(y.checked){
						dataCheck.push(mch[x].value);
					    }
					});
					console.log(dataCheck);
//					var el = Ext.get(dom[0]);
					
//					form.down('pool_assign_grid_<?=$tab_id?>').store.each(function(r) {
//					    console.log(r.getData());
//					    gridData.push(r.getData());
//					});
					Ext.Ajax.request({
					    url: '<?=controller_?>pool/save_pool_assigment',
					    method: 'POST',
					    params: {
						id_pool: '<?=$id_pool?>',
						dataMachine: JSON.stringify(dataCheck)
					    },
					    scope: this,
					    success: function(result, response) {
						loadmask.hide();
						var res = JSON.parse(result.responseText);
						var status = res.IsSuccess ? 'Success' : 'Failed';

						Ext.Msg.alert(status, res.Message);
						if(res.IsSuccess){
						    pool_store.reload();
						    win.close();
						}
					    },
					    failure:function(form, response) {
						Ext.Msg.alert('Failed: ', response.errorMessage);
						loadmask.hide();
					    }
					});
				    }
			    },{
				    text: 'Cancel',
				    handler: function() {
					    win.close();
				    }
			    }],
			    renderTo: Ext.getBody()
			})
		]
	});
	win.show();
});
</script>
<!--<div id="pool_<?=$tab_id?>"></div>-->