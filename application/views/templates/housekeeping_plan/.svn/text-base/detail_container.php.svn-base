<script type="text/javascript">	
function delete_cont_hkp(a,b,c)
{
	$.post('<?=controller_?>housekeeping_plan/del_cont_hk/',{NO_CONTAINER:b, POINT:c, HKP_ID:a},function(data){
			alert(data);
			Ext.getStore('cont_hk_grid<?=$tab_id?>').reload();
		});
}

function add_hk_detail<?=$tab_id?>(a)
{
	//alert(a);
	
	Ext.Ajax.request({
		url: '<?=controller_?>housekeeping_plan/add_cont_hk/<?=$tab_id?>/'+a,
		scope: this,
		success: function(response, request){
			$("#popup_add_conthk_<?=$tab_id?>").html(response.responseText);
		}
		
	});
}

$(function() {
	var ct_store2 = Ext.create('Ext.data.Store', {
		fields:['NO_CONTAINER', 'POINT','ID_ISO_CODE', 'ID_VES_VOYAGE', 'HKP_STATUS_CONT', 'LOC_CON_REAL', 'LOC_CON_PLAN', 'ACTION'],
		autoLoad: true,
		remoteSort: true,
		storeId: 'cont_hk_grid<?=$tab_id?>',
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>housekeeping_plan/load_hkplan_gridcont/<?=$hkp_id?>',
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
	
	var ct_grid2 = Ext.create('Ext.grid.Panel', {
		store: ct_store2,
		loadMask: true,
		width: '300%',
		height: 200,
		columns: [
			{ text: 'No. Container', dataIndex: 'NO_CONTAINER' , width: 140, filter: {type: 'string'}, align:'center'},
			{ text: 'Point', dataIndex: 'POINT' , width: 80, filter: {type: 'string'}, align:'center'},
			{ text: 'ISO Code', dataIndex: 'ID_ISO_CODE' , width: 80, filter: {type: 'string'}, align:'center'},
			{ text: 'VVD', dataIndex: 'ID_VES_VOYAGE' , width: 120, filter: {type: 'string'}, align:'center'},
			{ text: 'Status', dataIndex: 'HKP_STATUS_CONT' , width: 80, filter: {type: 'string'}, align:'center'},
			{ text: 'From', dataIndex: 'LOC_CON_REAL' , width: 140, filter: {type: 'string'}, align:'center'},
			{ text: 'To', dataIndex: 'LOC_CON_PLAN' , width: 140, filter: {type: 'string'}, align:'center'},
			{ text: 'Action', dataIndex: 'ACTION' , width: 100, align:'center'}
		],
		viewConfig : {
			enableTextSelection: true
		},
		listeners : {
			itemdblclick: function(dv, record, item, index, e) {
				//alert(record.get('NO_CONTAINER'));
				set_container_hk(record.get('NO_CONTAINER'),record.get('POINT'),'<?=$hkp_id?>','<?=$tab_id?>');
			}
		},
		dockedItems: [Ext.create('Ext.toolbar.Paging', {
			dock: 'bottom',
			//store: ct_store,
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
		//features: [ct_filters],
		emptyText: 'No Data Found'
	});
	
	var win = new Ext.Window({
		layout: 'fit',
		title: 'Housekeeping Container',
		closable: true,
		width:900,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: "hkp_name<?=$tab_id?>",
				xtype: 'textfield',
				name: "hkp_name<?=$tab_id?>",
				fieldLabel: 'Housekeeping',
				maskRe: /[\w\s]/,
				regex: /[\w\s]/,
				allowBlank: false,
				readOnly:true,
				value:'<?=$hkp_name?>'
			},
			{
				id: "hkp_id<?=$tab_id?>",
				xtype: 'hidden',
				name: "hkp_id<?=$tab_id?>",
				fieldLabel: 'Housekeeping ID',
				maskRe: /[\w\s]/,
				regex: /[\w\s]/,
				allowBlank: false,
				value:'<?=$hkp_id?>'
			},
			{
				id: "but_<?=$tab_id?>",
				xtype: 'button',
				text: 'Add Container',
				name: "but_<?=$tab_id?>",
				allowBlank: false,
				handler: function(){ 
					add_hk_detail<?=$tab_id?>('<?=$hkp_id?>');
				}
			},
			ct_grid2
			]
		})]
	});
	win.show();	
});
</script>
<div id="popup_add_conthk_<?=$tab_id?>"></div>