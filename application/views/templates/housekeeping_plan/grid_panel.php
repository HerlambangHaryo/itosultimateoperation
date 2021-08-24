<script type="text/javascript">
function hk_detail<?=$tab_id?>(a,b)
{
	Ext.Ajax.request({
		url: '<?=controller_?>housekeeping_plan/load_detail_container_hk/<?=$tab_id?>/'+a+'/'+b,
		scope: this,
		success: function(response, request){
			$("#popup_script_<?=$tab_id?>").html(response.responseText);
		}
		
	});
}

Ext.onReady(function(){
	var ct_store = Ext.create('Ext.data.Store', {
		fields:['HKP_ID', 'HKP_MV_DESC', 'ITV_USE', 'MCH_NAME', 'HKP_STATUS', 'HKP_ACTIVITY_DESC'//,'ACTION'
		],
		autoLoad: true,
		remoteSort: true,
		storeId: 'hk_grid<?=$tab_id?>',
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>housekeeping_plan/load_hkplan_grid',
			reader: {
				type: 'json',
				root: 'data',
				totalProperty: 'total'
			}
		},
		pageSize: 100,
		sorters: [{
			property: 'HKP_ID',
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
		width: 900,
		height: 440,
		columns: [
			{ text: 'ID', dataIndex: 'HKP_ID', width: 60, filter: {type: 'string'}, align:'center'},
			{ text: 'Housekeeping', dataIndex: 'HKP_MV_DESC' , width: 140, filter: {type: 'string'}, align:'center'},
			{ text: 'ITV Use', dataIndex: 'ITV_USE' , width: 80, filter: {type: 'string'}, align:'center'},
			{ text: 'Machine', dataIndex: 'MCH_NAME' , width: 120, filter: {type: 'string'}, align:'center'},
			{ text: 'Status', dataIndex: 'HKP_STATUS' , width: 80, filter: {type: 'string'}, align:'center'},
			{ text: 'Activity', dataIndex: 'HKP_ACTIVITY_DESC' , width: 140, filter: {type: 'string'}, align:'center'}
			//,{ text: 'Action', dataIndex: 'ACTION' , width: 140, align:'center'}
		],
		viewConfig : {
			enableTextSelection: true
		},
		listeners : {
			itemdblclick: function(dv, record, item, index, e) {
				//alert(record.get('HKP_ID'));
				hk_detail<?=$tab_id?>(record.get('HKP_ID'),record.get('HKP_MV_DESC'));
			},
			itemcontextmenu: function(tree, record, item, index, e, eOpts ) {
				// Optimize : create menu once
				var menu_grid = new Ext.menu.Menu({ items:
				[
					{ 	text: 'Activate', 
						icon: '<?=IMG_?>icons/actv.png',
						handler: function() {
							$.post('<?=controller_?>housekeeping_plan/activate_hkp',{HKP_ID:record.get('HKP_ID')},function(data){
								var msg = 'Activate Failed';
								if(data == '1'){
								    msg = 'Activate Success';
								}
								alert(msg);
								Ext.getStore('hk_grid<?=$tab_id?>').reload();
							});
						} 
					},
					{ 
						text: 'Deactivate', 
						icon: '<?=IMG_?>icons/deactv.png',
						handler: function() {
							$.post('<?=controller_?>housekeeping_plan/deactivate_hkp',{HKP_ID:record.get('HKP_ID')},function(data){
								var msg = 'Deactivate Failed';
								if(data == '1'){
								    msg = 'Deactivate Success';
								}
								alert(msg);
								Ext.getStore('hk_grid<?=$tab_id?>').reload();
							});
						} 
					}
				]
				});
				// HERE IS THE MAIN CHANGE
				var position = [e.getX()-10, e.getY()-10];
				e.stopEvent();
				menu_grid.showAt(position);
		    }
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
	
	
	ct_grid.render('job_list_<?=$tab_id?>');
	
/* 		$.contextMenu({
		selector: "#selectable_<?=$tab_id?> .ui-selected",
		items: {
			"plan_new": {
				name: "Plan with New Category", 
				icon: "edit", 
				callback: function(key, options) {
					Ext.Ajax.request({
						url: '<?=controller_?>yard_planning/popup_new_category?tab_id=<?=$tab_id?>',
						callback: function(opt,success,response){
							$("#popup_script_<?=$tab_id?>").html(response.responseText);
						} 
					});
				}
			},
			"plan_existing": {
				name: "Plan with Existing Category",
				icon: "edit",
				callback: function(key, options) {
					Ext.Ajax.request({
						url: '<?=controller_?>yard_planning/popup_existing_category?tab_id=<?=$tab_id?>',
						callback: function(opt,success,response){
							$("#popup_script_<?=$tab_id?>").html(response.responseText);
						} 
					});
				}
			},
			"sep1": "---------",
			"quit": {
				name: "Cancel",
				icon: "quit",
				callback: function(key, options) {
					$(this).contextMenu("hide");
				}
			}
		}
	}); */
});
</script>
<div id="job_list_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>