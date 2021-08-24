<script type="text/javascript">
	Ext.onReady(function() {
		var yard_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['VESSEL_NAME', 'VOY', 'BOOKING_TEUS', 'APPROVED_TEUS','READINESS_TEUS','BOOKED_TEUS', 'PERCENTAGE', 'ETB','ETD'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>pre_berthing/data_pre_berthing',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 20,
			sorters: [{
				property: 'TGL_ETB',
				direction: 'ASC'
			}]
		});
		
		var yard_filters_<?=$tab_id?> = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var yard_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'yard_grid_<?=$tab_id?>',
			store: yard_store_<?=$tab_id?>,
			width: 1460,
			columns: [
				{ text: 'NAMA KAPAL', dataIndex: 'VESSEL_NAME', width: 200, filter: {type: 'string'}},
				{ text: 'VOY', dataIndex: 'VOY', width: 150},
				{ text: 'BOOKING (TEUS)', dataIndex: 'BOOKING_TEUS', width: 140},
				{ text: 'APPROVED (TEUS', dataIndex: 'APPROVED_TEUS', width: 160},
				{ text: 'READINESS (TEUS)', dataIndex: 'READINESS_TEUS' , width: 160},
				{ text: 'BOOKED (TEUS)', dataIndex: 'BOOKED_TEUS' , width: 160},
				{ text: '%', dataIndex: 'PERCENTAGE' , width: 50},				
				{ text: 'ETB', dataIndex: 'ETB', width: 200},
				{ text: 'ETD', dataIndex: 'ETD', width: 200}
			],
			tbar: [
				{
				    xtype: 'fieldcontainer',
				    fieldLabel: 'From',
				    layout: 'hbox',
				    combineErrors: true,
				    defaultType: 'textfield',
				    defaults: {
					    hideLabel: 'true'
				    },
				    items: [{
					    id: 'from_date_<?=$tab_id?>',
					    xtype: 'datefield',
					    name: 'fromdate',
					    fieldLabel: 'From Date',
					    emptyText: 'Pick Date',
					    format: 'd-m-Y',
					    width: 120,
					    editable: true,
					    allowBlank: true
				    }],
				    width: 250
				},{
				    xtype: 'fieldcontainer',
				    fieldLabel: 'To',
				    layout: 'hbox',
				    combineErrors: true,
				    defaultType: 'textfield',
				    defaults: {
					    hideLabel: 'true'
				    },
				    items: [{
					    id: 'to_date_<?=$tab_id?>',
					    xtype: 'datefield',
					    name: 'todate',
					    fieldLabel: 'To Date',
					    emptyText: 'Pick Date',
					    format: 'd-m-Y',
					    width: 120,
					    editable: true,
					    allowBlank: true
				    }],
				    width: 250
				},
				{ 
				    xtype: 'button', 
				    text: 'Filter',
				    handler: function (){
					yard_grid_<?=$tab_id?>.filters.clearFilters();
					var vFromDate = Ext.getCmp('from_date_<?=$tab_id?>').getValue();
					var vToDate = Ext.getCmp('to_date_<?=$tab_id?>').getValue();
					

					if(vFromDate != ''){
					    var fromDate = [{type: "mainfilterdate",value: vFromDate,field: "FROM_DATE"}];
					}
					if(vToDate != ''){
					    var toDate = [{type: "mainfilterdate",value: vToDate,field: "TO_DATE"}];
					}
					
					yard_store_<?=$tab_id?>.getProxy().extraParams = {
					    fromdate: JSON.stringify(fromDate),
					    todate: JSON.stringify(toDate)					    
					};
					yard_store_<?=$tab_id?>.load();
				    } 
				},
				{
				    xtype: 'button', 
				    text: 'Clear Filter',
				    handler: function (){
				    	
				    	var vFromDate = Ext.getCmp('from_date_<?=$tab_id?>').getValue();
						var vToDate = Ext.getCmp('to_date_<?=$tab_id?>').getValue();
						
						if(vFromDate != ''){
					    	var fromDate = [{type: "mainmain",value: vFromDate,field: "FROM_DATE"}];
						}
						if(vToDate != ''){
					    	var toDate = [{type: "mainmain",value: vToDate,field: "TO_DATE"}];
						}
						yard_store_<?=$tab_id?>.getProxy().extraParams = {
					    	fromdate: JSON.stringify(fromDate),
					    	todate: JSON.stringify(toDate)					    
						};
						yard_store_<?=$tab_id?>.load();
				    }
				},
				{
				    xtype: 'button', 
				    text: 'EXPORT TO EXCEL',
				    handler: function (){
					window.open('<?=controller_?>pre_berthing/export_to_excel/','_blank');
				    }
				}
			],			
			viewConfig : {
				enableTextSelection: true
			},
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: yard_store_<?=$tab_id?>,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							yard_grid_<?=$tab_id?>.filters.clearFilters();
						}
					}
				]
			})],
			features: [yard_filters_<?=$tab_id?>],
			emptyText: 'No Data Found'
		});
		
		Ext.getCmp('west_panel').expand();
		yard_grid_<?=$tab_id?>.render('yard_grid_<?=$tab_id?>');
	});
	
</script>
<div id="yard_grid_<?=$tab_id?>"></div>