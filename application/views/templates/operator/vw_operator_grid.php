<script type="text/javascript">
	Ext.onReady(function() {
		var operator_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['ID_OPERATOR', 'OPERATOR_NAME', 'IS_ACTIVE'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>operator/data_operator',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 20
			/*sorters: [{
				property: 'ID_OPERATOR',
				direction: 'DESC'
			}]*/
		});
		
		var operator_filters_<?=$tab_id?> = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var operator_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'operator_grid_<?=$tab_id?>',
			store: operator_store_<?=$tab_id?>,
			width: 500,
			columns: [
				{ text: 'Id Operator', dataIndex: 'ID_OPERATOR', width: 100, filter: {type: 'string'}},
				{ text: 'Operator Name', dataIndex: 'OPERATOR_NAME' , width: 250, filter: {type: 'string'}},
				{ text: 'Active', dataIndex: 'IS_ACTIVE' , width: 50},				
				{ 
					text: 'Actions',
					width: 100,
					align: 'center',
					xtype: 'actioncolumn',
					items: [{
						icon: "<?=IMG_?>icons/start.png",
						tooltip: 'Enabled',
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.getStore().getAt(rowIndex);
							if($.trim(rec.get('IS_ACTIVE')) == 'N')
							{
								Ext.MessageBox.confirm('Confirm', 'Are you sure to enable '+rec.get('ID_OPERATOR')+'?', showResult);
							}	
							
							function showResult(btn)
							{
								if(btn=='yes')
								{		
									var url = "<?=controller_?>operator/enabled_or_disabled_operator";
									$.post( url, { ID_OPERATOR: rec.get('ID_OPERATOR'), IS_ACTIVE : 'Y'}, function(data) {
										loadmask.hide();
										Ext.Msg.alert('Success',  '' + rec.get('ID_OPERATOR') + ' has disabled');
										operator_store_<?=$tab_id?>.reload();
										add_title();
									});
								}
								else
								{
									Ext.MessageBox.alert('Status', 'Cancel.');
								}
							}							
						}
					},'-',{
						icon: "<?=IMG_?>icons/stop.png",
						tooltip: 'Disabled',
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.getStore().getAt(rowIndex);							
							if(rec.get('IS_ACTIVE') == 'Y')
							{
								Ext.MessageBox.confirm('Confirm', 'Are you sure to disable '+rec.get('ID_OPERATOR')+'?', showResult);								
							}
							
							function showResult(btn)
							{
								if(btn=='yes')
								{		
									var url = "<?=controller_?>operator/enabled_or_disabled_operator";
									$.post( url, { ID_OPERATOR: rec.get('ID_OPERATOR'), IS_ACTIVE : 'N'}, function(data) {
										loadmask.hide();
										Ext.Msg.alert('Success',  '' + rec.get('ID_OPERATOR') + ' has disabled');
										operator_store_<?=$tab_id?>.reload();
										add_title();
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
					   text: 'Add Operator',
					   handler: function (){
					   		addTab('center_panel', 'operator/form_addoperator', '', 'Add Operator');
					   } }
			],			
			viewConfig : {
				enableTextSelection: true
			},
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: operator_store_<?=$tab_id?>,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							operator_grid_<?=$tab_id?>.filters.clearFilters();
						}
					}
				]
			})],
			features: [operator_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
		});
		
		Ext.getCmp('west_panel').expand();
		operator_grid_<?=$tab_id?>.render('operator_grid_<?=$tab_id?>');
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
<div id="operator_grid_<?=$tab_id?>"></div>