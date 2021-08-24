<script type="text/javascript">
	var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
	var data_alat_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_MACHINE', 'MCH_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>report_kinerja_alat/data_alat/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var data_operator_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_USER', 'FULL_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>report_kinerja_alat/data_operator/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var data_action_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_ACTION', 'NAMA_ACTION'],
	    data: [
		{"ID_ACTION": "Receiving","NAMA_ACTION": "Receiving"},
		{"ID_ACTION": "Delivery","NAMA_ACTION": "Delivery"},
		{"ID_ACTION": "Loading","NAMA_ACTION": "Loading"},
		{"ID_ACTION": "Discharged","NAMA_ACTION": "Discharged"}
	    ]
	});

	var tampil_store = Ext.create('Ext.data.Store', {
			fields:['MCH_NAME','FULL_NAME','DATE_ENTRY','VESSEL_NAME','VOY_IN','VOY_OUT','NO_CONTAINER','CONT_TYPE','CONT_SIZE', 'CONT_STATUS', 'ACT'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>report_kinerja_alat/get_data_kinerja_alat_tampil/',
				reader: {
					type: 'json',
					root: 'data',
					idProperty: 'id_ves_voyage',
					totalProperty: 'total'
				}
			},
			pageSize: 50,
			autoLoad: false
		});
	
	Ext.create('Ext.form.Panel', {
		id: "kinerja_alat_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		url: '<?=controller_?>report_kinerja_alat/get_data_kinerja_alat?tab_id=<?=$tab_id?>',
		items: [{
			xtype: 'fieldcontainer',
			fieldLabel: 'Alat',
			layout: 'hbox',
			combineErrors: true,
			defaultType: 'textfield',
			defaults: {
				hideLabel:'false'
			},
			items: [{
					id: "alat_<?=$tab_id?>",
					xtype: 'combo',
					fieldLabel: 'Alat',
					name: "alat",
					store: data_alat_<?=$tab_id?>,
					queryMode: 'local',
					valueField: 'ID_MACHINE',
					displayField: 'MCH_NAME',
					width:220
				}]
		},{
			xtype: 'fieldcontainer',
			fieldLabel: 'Operator',
			layout: 'hbox',
			combineErrors: true,
			defaultType: 'textfield',
			defaults: {
				hideLabel:'false'
			},
			items: [{
					id: "data_operator_<?=$tab_id?>",
					xtype: 'combo',
					fieldLabel: 'Operator',
					name: "id_user_operator",
					store: data_operator_<?=$tab_id?>,
					queryMode: 'local',
					valueField: 'ID_USER',
					displayField: 'FULL_NAME',
					width:220
				}]
		},{
			xtype: 'fieldcontainer',
			fieldLabel: 'Filter Action',
			layout: 'hbox',
			combineErrors: true,
			defaultType: 'textfield',
			defaults: {
				hideLabel:'false'
			},
			items: [{
					id: "action_<?=$tab_id?>",
					xtype: 'combo',
					fieldLabel: 'Action',
					name: "action",
					store: data_action_<?=$tab_id?>,
					queryMode: 'local',
					valueField: 'ID_ACTION',
					displayField: 'NAMA_ACTION',
					width:220
				}]
		},{
			xtype: 'fieldcontainer',
			fieldLabel: 'Periode Awal',
			layout: 'hbox',
			combineErrors: true,
			defaultType: 'textfield',
			defaults: {
				hideLabel:'true'
			},
			items: [{
					id: "start_period_<?=$tab_id?>",
					xtype: 'datefield',
					name: "START_PERIOD",
					fieldLabel: 'Date',
					emptyText: 'Date',
					format: 'd-m-Y',
					width: 120,
					editable: false,
					afterLabelTextTpl: required,
					allowBlank: false
				},{
					id: "start_period_hour_<?=$tab_id?>",
					xtype: 'textfield',
					name: "START_PERIOD_HOUR",
					fieldLabel: 'Start Periode Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23',
					allowBlank: false
				},{
					id: "start_period_min_<?=$tab_id?>",
					xtype: 'textfield',
					name: "START_PERIOD_MIN",
					fieldLabel: 'Start Periode Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59',
					allowBlank: false
				}]
		},{
			xtype: 'fieldcontainer',
			fieldLabel: 'Periode Akhir',
			layout: 'hbox',
			combineErrors: true,
			defaultType: 'textfield',
			defaults: {
				hideLabel: 'true'
			},
			items: [{
				id: "end_period_<?=$tab_id?>",
				xtype: 'datefield',
				name: "END_PERIOD",
				fieldLabel: 'Date',
				emptyText: 'Date',
				format: 'd-m-Y',
				width: 120,
				editable: false,
				afterLabelTextTpl: required,
				allowBlank: false
			},{
				id: "end_period_hour_<?=$tab_id?>",
				xtype: 'textfield',
				name: "END_PERIOD_HOUR",
				fieldLabel: 'End Periode Hour',
				minLength: 1,
				maxLength: 2,
				enforceMaxLength: true,
				width: 50,
				maskRe: /[\d]/,
				regex: /^([0,1]?\d|2[0-3])$/,
				regexText: 'Value of this field must between 0-23',
				allowBlank: false
			},{
				id: "end_period_min_<?=$tab_id?>",
				xtype: 'textfield',
				name: "END_PERIOD_MIN",
				fieldLabel: 'End Periode Minute',
				minLength: 1,
				maxLength: 2,
				enforceMaxLength: true,
				width: 50,
				maskRe: /[\d]/,
				regex: /^([0-5]?\d)$/,
				regexText: 'Value of this field must between 0-59',
				allowBlank: false
			}]
		}],
		buttons: [{
			text: 'Show Data',
			formBind: true,
			handler: function() {
				var form = this.up('form').getForm(),
				values = form.getFieldValues();
				var start_period = $('#start_period_<?=$tab_id?>-inputEl').val();
				var st_hour = $('#start_period_hour_<?=$tab_id?>-inputEl').val();
				var st_min = $('#start_period_min_<?=$tab_id?>-inputEl').val();
				var end_period = $('#end_period_<?=$tab_id?>-inputEl').val();
				var et_hour = $('#end_period_hour_<?=$tab_id?>-inputEl').val();
				var et_min = $('#end_period_min_<?=$tab_id?>-inputEl').val();
				var alat = $('#alat_<?=$tab_id?>-inputEl').val();
				var action_ = $('#action_<?=$tab_id?>-inputEl').val();
				var id_user = values.id_user_operator;
				var sp = start_period+' '+st_hour+'.'+st_min+'.00';
				var ep = end_period+' '+et_hour+'.'+et_min+'.59';
				
				var show_data = [{start_period: sp, end_period: ep, alat: alat, id_user_operator: id_user, action: action_}]
				tampil_store.getProxy().extraParams = {
							show_data: JSON.stringify(show_data)
						};
				tampil_store.load();
			}
		},{
			text: 'Export to Excel',
			formBind: true,
			handler: function() {
				var form = this.up('form').getForm(),
				values = form.getFieldValues();
				var start_period = $('#start_period_<?=$tab_id?>-inputEl').val();
				var st_hour = $('#start_period_hour_<?=$tab_id?>-inputEl').val();
				var st_min = $('#start_period_min_<?=$tab_id?>-inputEl').val();
				var end_period = $('#end_period_<?=$tab_id?>-inputEl').val();
				var et_hour = $('#end_period_hour_<?=$tab_id?>-inputEl').val();
				var et_min = $('#end_period_min_<?=$tab_id?>-inputEl').val();
				var alat = $('#alat_<?=$tab_id?>-inputEl').val();
				var action_ = $('#action_<?=$tab_id?>-inputEl').val();
				var operator_name = $('#data_operator_<?=$tab_id?>-inputEl').val();
				var id_user = values.id_user_operator;
				var url = '<?=controller_?>report_kinerja_alat/get_data_kinerja_alat?START_PERIOD='+start_period+' '+st_hour+'.'+st_min+'.00&END_PERIOD='+end_period+' '+et_hour+'.'+et_min+'.59&ALAT='+alat+'&id_user_operator='+id_user+'&action='+action_+'&operator_name='+operator_name;
				window.open(url,'_blank');
			}
		}]
	}).render('kinerja_alat_<?=$tab_id?>');
	
	var ct_filters = {
		ftype: 'filters',
		encode: true,
		local: false
	};

	var ct_grid = Ext.create('Ext.grid.Panel', {
			id: 'data_kinerja_alat_<?=$tab_id?>',
			store: tampil_store,
			loadMask: true,
			width: 1440,
			height: 440,
			multiSelect: true,
			columns: [
				{ text: 'Nama Alat', dataIndex: 'MCH_NAME', width: 90},
				{ text: 'Nama Operator', dataIndex: 'FULL_NAME', width: 140, filter: {type: 'string'}},
				{ text: 'Data Entry', dataIndex: 'DATE_ENTRY', width: 150},
				{ text: 'Vessel Voyage In/Out', dataIndex: 'VESSEL_NAME', width: 200, filter: {type: 'string'}},
				{ text: 'Container', dataIndex: 'NO_CONTAINER', width: 120},
				{ text: 'Type', dataIndex: 'CONT_TYPE', width: 60},
				{ text: 'Size', dataIndex: 'CONT_SIZE', width: 60},
				{ text: 'Status', dataIndex: 'CONT_STATUS', width: 90},
				{ text: 'Action', dataIndex: 'ACT', width: 90}
			],
			viewConfig : {
				enableTextSelection: true,
				listeners: {
					itemcontextmenu: function(view, rec, node, index, e) {
						e.stopEvent();
						contextMenu.showAt(e.getXY());
						return false;
					}
				}
			},
			dockedItems: [Ext.create('Ext.toolbar.Paging', {
				dock: 'bottom',
				store: tampil_store,
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
			emptyText: 'No Data Found',
		}).render('data_kinerja_alat_tampil_<?=$tab_id?>');
	$(document).ready(function (){
		
	});
</script>
<div id="kinerja_alat_<?=$tab_id?>"></div>
<div id="data_kinerja_alat_tampil_<?=$tab_id?>"></div>