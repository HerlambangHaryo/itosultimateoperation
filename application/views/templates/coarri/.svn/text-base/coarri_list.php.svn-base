<script type="text/javascript">
	Ext.onReady(function(){
		var ct_store = Ext.create('Ext.data.Store', {
			fields:['FILE_NAME', 'E_I', 'STATUS', 'FULL_NAME', 'CREATED_DATE'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>coarri/data_coarri_list',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				},				
				extraParams: {
					id_ves_voyage: '<?=$id_ves_voyage?>'
				}
			},
			pageSize: 100,
			sorters: [{
				property: 'FILE_NAME',
				direction: 'ASC'
			}]
		});
		
		var ct_filters = {
			ftype: 'filters',
			encode: true,
			local: false
		};
		
		var ct_grid = Ext.create('Ext.grid.Panel', {
			store: ct_store,
			loadMask: true,
			width: 780,
			height: 500,
			id: 'coarri_list_grid_<?=$tab_id?>',
			columns: [
				{ text: 'File Name', dataIndex: 'FILE_NAME', width: 260, filter: {type: 'string'}},
				{ text: 'EI', dataIndex: 'E_I', width: 40},
				{ text: 'Status', dataIndex: 'STATUS', width: 80, filter: {type: 'string'}},
				{ text: 'Created By', dataIndex: 'FULL_NAME' , width: 120, filter: {type: 'string'}},
				{ text: 'Created Date', dataIndex: 'CREATED_DATE' , width: 160, filter: {type: 'string'}},
				{ 
					text: 'Actions',
					width: 80,
					align: 'center',
					xtype: 'actioncolumn',
					items: [{
						icon: "<?=IMG_?>icons/save_peb.gif",
						tooltip: 'Download',
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.getStore().getAt(rowIndex);
							window.open('./edifact/coarri/' + rec.get('FILE_NAME'),'Coarri Donwload');
							// addTab('center_panel', 'coarri/save_coarri_page/'+ rec.get('FILE_NAME'), '', 'Coarri Donwload');
						}
						   }]
				}
			],
			viewConfig : {
				enableTextSelection: true
			},
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: ct_store,
				displayInfo: true,
				displayMsg: 'Displaying {0} - {1} of {2}',
				items: [
					'-',{
						text: 'Clear Filter Data',
						handler: function () {
							ct_grid.filters.clearFilters();
						}
					}
				]
			})],
			features: [ct_filters],
			emptyText: 'No Data Found'
		});
		
		ct_grid.render('coarri_list_<?=$tab_id?>');
	});
</script>
<br/>
<div id="coarri_list_<?=$tab_id?>"></div>