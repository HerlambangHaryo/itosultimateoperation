<script type="text/javascript">
	Ext.onReady(function() {
		var port_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['PORT_CODE', 'PORT_NAME', 'PORT_COUNTRY', 'PORT_PURECODE','FOREGROUND_COLOR','BACKGROUND_COLOR','IS_ACTIVE'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>port/data_port',
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
		
		var port_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'port_grid_<?=$tab_id?>',
			store: port_store_<?=$tab_id?>,
			width: 1200,
			columns: [
				{ text: 'Port Code', dataIndex: 'PORT_CODE', width: 100, filter: {type: 'string'}},
				{ text: 'Port Name', dataIndex: 'PORT_NAME' , width: 200, filter: {type: 'string'}},
				{ text: 'Port Country', dataIndex: 'PORT_COUNTRY', width: 130},
				{ text: 'Port Purecode', dataIndex: 'PORT_PURECODE', width: 130},
				{ text: 'Foregound Color', dataIndex: 'FOREGROUND_COLOR', width: 130,
				renderer: function(value) {
						if (value==null) {
							return Ext.String.format('<span style="background-color:#515151;">__________</span>', value, value);
						}else{
				            return Ext.String.format('<span style="background-color:#{1};">__________</span>', value, value);
						}
			    	}
				},
				{ text: 'Backgorund Color', dataIndex: 'BACKGROUND_COLOR', width: 130, 
					renderer: function(value) {
						if (value==null) {
							return Ext.String.format('<span style="background-color:#515151;">__________</span>', value, value);
						}else{
				            return Ext.String.format('<span style="background-color:#{1};">__________</span>', value, value);
						}
			    	}
				},
				{ text: 'Active', dataIndex: 'IS_ACTIVE', width: 100},				
				/*edit.png*/
				{ 
					text: 'Actions',
					width: 130,
					align: 'center',
					xtype: 'actioncolumn',
					items: [{
						icon: "<?=IMG_?>icons/start.png",
						tooltip: 'Enabled',
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.getStore().getAt(rowIndex);	
							if($.trim(rec.get('IS_ACTIVE')) == 'N')
							{
								Ext.MessageBox.confirm('Confirm', 'Are you sure to enable '+rec.get('PORT_CODE')+'?', showResult);
							}
							
							function showResult(btn)
							{
								if(btn=='yes')
								{		
									var url = "<?=controller_?>port/enabled_or_disabled_port";
									$.post( url, { PORT_CODE : rec.get('PORT_CODE'), IS_ACTIVE : 'Y'}, function(data) {
										loadmask.hide();
										Ext.Msg.alert('Success', '' + rec.get('PORT_NAME') + ' has enabled');
										grid.getStore().reload();
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
								Ext.MessageBox.confirm('Confirm', 'Are you sure to disable '+rec.get('PORT_CODE')+'?', showResult);
							}
							
							function showResult(btn)
							{
								if(btn=='yes')
								{		
									var url = "<?=controller_?>port/enabled_or_disabled_port";
									$.post( url, { PORT_CODE : rec.get('PORT_CODE'), IS_ACTIVE : 'N'}, function(data) {
										loadmask.hide();
										Ext.Msg.alert('Success', '' + rec.get('PORT_NAME') + ' has disabled');
										grid.getStore().reload();
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
						icon: "<?=IMG_?>icons/edit.png",
						tooltip: 'Edit',
						handler: function(grid, rowIndex, colIndex) {

						var rec = grid.getStore().getAt(rowIndex);
						var port_code = rec.get('PORT_CODE');
						addTab('center_panel', 'port/form_editport/'+port_code+'/<?=$tab_id?>', '', 'Edit Port');
						return false;

						var rec = grid.getStore().getAt(rowIndex);
						Ext.MessageBox.confirm('Confirm', 'Are you sure to edit '+rec.get('PORT_CODE')+'?', showResult);
							function showResult(btn)
							{
								if(btn=='yes')
								{		
									var url = "<?=controller_?>port/form_editport";
									$.post( url, { PORT_CODE : rec.get('PORT_CODE'), TAB_ID : '<?=$tab_id?>'}, 
										function(data) {
											loadmask.hide();
											//Ext.Msg.alert('Success', '' + rec.get('PORT_NAME') + ' has disabled');
											grid.getStore().reload();
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
					   text: 'Add Port',
					   handler: function (){
					   		addTab('center_panel', 'port/form_addPort', '', 'Add Port');
					   } }
			],			
			viewConfig : {
				enableTextSelection: true
			},
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: port_store_<?=$tab_id?>,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							port_grid_<?=$tab_id?>.filters.clearFilters();
						}
					}
				]
			})],
			features: [port_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
		});
		
		Ext.getCmp('west_panel').expand();
		port_grid_<?=$tab_id?>.render('port_grid_<?=$tab_id?>');
		
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
<div id="port_grid_<?=$tab_id?>"></div>