<script type="text/javascript">
	var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';

	var kegiatan_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['KEGIATAN', 'KEGIATAN_TEXT'],
		data : [
			 {KEGIATAN: 'I', KEGIATAN_TEXT: 'Receiving'},
			 {KEGIATAN: 'O', KEGIATAN_TEXT: 'Delivery'}
		]
	});
        
        var vessel_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_VES_VOYAGE', 'VESSEL_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>report_gate/data_vessel/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
        
        var pbm_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_COMPANY', 'COMPANY_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>report_gate/data_pbm/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true,
		listeners: {
			load: function() {
				pbm_<?=$tab_id?>.insert(0, {
					ID_COMPANY: 'ALLPBM',
					COMPANY_NAME: 'ALL PBM'
				})
			}
		}
	});
        
	var shipping_line_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['LINE', 'LINE_TEXT'],
		data : [
			 // {LINE: 'O', LINE_TEXT: 'Ocean Going'},
			 {LINE: 'I', LINE_TEXT: 'Intersuler'}
		]
	});

	var esy_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ESY', 'ESY_TEXT'],
		data : [
			 {ESY: 'N', ESY_TEXT: 'N'},
			 {ESY: 'Y', ESY_TEXT: 'Y'}
		]
	});

	var gate_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['GATE', 'GATE_TEXT'],
		data : [
			 {GATE: '', GATE_TEXT: 'All'},
			 {GATE: 'Gate In', GATE_TEXT: 'Gate In'},
			 {GATE: 'Gate Out', GATE_TEXT: 'Gate Out'}
		]
	});
	
	var tampil_store = Ext.create('Ext.data.Store', {
			fields:[
				'COMPANY_NAME',
				'NO_CONTAINER',
				'CONT_SIZE',
				'CONT_TYPE',
				'CONT_STATUS',
				'IMDG',
				'WEIGHT',
				'ID_CLASS_CODE',
				'VESSEL_NAME',
				'VOY_IN',
				'VOY_OUT',
				'ID_ISO_CODE',
				'POD',
				'TID',
				'NO_TRUCK',		
				'GATE_IN',
				'USER_GATE_IN',
				'GATE_OUT',
				'USER_GATE_OUT',
				'TERMINAL_NAME',
				'SHIPPING',
				'ESY',
			'TRT'
			],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>report_gate/get_data_gate_show/',
				reader: {
					type: 'json',
					root: 'data',
					idProperty: 'ID_VES_VOYAGE',
					totalProperty: 'total'
				}
			},
			pageSize: 50,
			autoLoad: true
		});

	Ext.create('Ext.form.Panel', {
		id: "gate_in_out_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		url: '<?=controller_?>report_gate/get_data_gate?tab_id=<?=$tab_id?>',
		items: [{
			xtype: 'fieldcontainer',
			fieldLabel: 'From',
			layout: 'hbox',
			combineErrors: true,
			defaultType: 'textfield',
			defaults: {
				hideLabel:'true'
			},
			items: [{
					id: "date_in_<?=$tab_id?>",
					xtype: 'datefield',
					name: "DATE_IN",
					fieldLabel: 'Date',
					emptyText: 'Date',
					format: 'd-m-Y',
					width: 120,
					editable: false,
					afterLabelTextTpl: required,
					allowBlank: false
				},{
					id: "hour_in_<?=$tab_id?>",
					xtype: 'textfield',
					name: "HOUR_IN",
					fieldLabel: 'Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23',
					afterLabelTextTpl: required,
					allowBlank: false
				},{
					id: "min_in_<?=$tab_id?>",
					xtype: 'textfield',
					name: "MIN_IN",
					fieldLabel: 'Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59',
					afterLabelTextTpl: required,
					allowBlank: false
				}]
		},{
			xtype: 'fieldcontainer',
			fieldLabel: 'Until',
			layout: 'hbox',
			combineErrors: true,
			defaultType: 'textfield',
			defaults: {
				hideLabel: 'true'
			},
			items: [{
				id: "date_out_<?=$tab_id?>",
				xtype: 'datefield',
				name: "DATE_OUT",
				fieldLabel: 'Date',
				emptyText: 'Date',
				format: 'd-m-Y',
				width: 120,
				editable: false,
				afterLabelTextTpl: required,
				allowBlank: false
			},{
				id: "hour_out_<?=$tab_id?>",
				xtype: 'textfield',
				name: "HOUR_OUT",
				fieldLabel: 'Hour',
				minLength: 1,
				maxLength: 2,
				enforceMaxLength: true,
				width: 50,
				maskRe: /[\d]/,
				regex: /^([0,1]?\d|2[0-3])$/,
				regexText: 'Value of this field must between 0-23',
				afterLabelTextTpl: required,
				allowBlank: false
			},{
				id: "min_out_<?=$tab_id?>",
				xtype: 'textfield',
				name: "MIN_OUT",
				fieldLabel: 'Minute',
				minLength: 1,
				maxLength: 2,
				enforceMaxLength: true,
				width: 50,
				maskRe: /[\d]/,
				regex: /^([0-5]?\d)$/,
				regexText: 'Value of this field must between 0-59',
				afterLabelTextTpl: required,
				allowBlank: false
			}]
		},
        {
			id: "vessel_<?=$tab_id?>",
			xtype: 'combo',
			displayField: 'VESSEL_NAME',
			valueField: 'ID_VES_VOYAGE',
			emptyText: 'VESSEL',
			editable: false,
			store: vessel_<?=$tab_id?>,
			name: "VESSEL",
			fieldLabel: 'Vessel',
			allowBlank: true,
			width: 325,
		},
        {
			id: "pbm_<?=$tab_id?>",
			xtype: 'combo',
			displayField: 'COMPANY_NAME',
			valueField: 'ID_COMPANY',
			emptyText: 'PBM',
			editable: false,
			store: pbm_<?=$tab_id?>,
			name: "PBM",
			fieldLabel: 'PBM',
			listConfig: {
				cls: 'thisComboMakesLastItemDifferent'        
			},
			allowBlank: true,
			width: 325,
		},
		{
			id: "kegiatan_<?=$tab_id?>",
			xtype: 'combo',
			displayField: 'KEGIATAN_TEXT',
			valueField: 'KEGIATAN',
			queryMode: 'local',
			emptyText: 'Jenis Kegiatan',
			editable: false,
			store: kegiatan_<?=$tab_id?>,
			name: "JENIS_KEGIATAN",
			fieldLabel: 'Jenis Kegiatan',
			allowBlank: true,
			width: 325,
		},
		/*{
			id: "shipping_line_<?=$tab_id?>",
			xtype: 'combo',
			displayField: 'LINE_TEXT',
			valueField: 'LINE',
			queryMode: 'local',
			emptyText: 'Shipping Line',
			editable: false,
			store: shipping_line_<?=$tab_id?>,
			name: "SHIPPING_LINE",
			fieldLabel: 'Shipping',
			allowBlank: true,
			width: 325,
		},*/
		{
			id: "esy_<?=$tab_id?>",
			xtype: 'combo',
			displayField: 'ESY_TEXT',
			valueField: 'ESY',
			queryMode: 'local',
			emptyText: 'ESY',
			editable: false,
			store: esy_<?=$tab_id?>,
			name: "ESY",
			fieldLabel: 'ESY',
			allowBlank: true,
			width: 325,
		},
		{
			id: "gate_<?=$tab_id?>",
			xtype: 'combo',
			displayField: 'GATE_TEXT',
			valueField: 'GATE',
			queryMode: 'local',
			emptyText: 'Gate In / Out',
			editable: false,
			store: gate_<?=$tab_id?>,
			name: "GATE",
			fieldLabel: 'Gate In / Out',
			allowBlank: true,
			width: 325,
		}

		],
		buttons: [
		{
			text: 'Show Data',
			formBind: true,
			handler: function() {
				var date_in = $('#date_in_<?=$tab_id?>-inputEl').val() + ' ' + $('#hour_in_<?=$tab_id?>-inputEl').val() +'.' + $('#min_in_<?=$tab_id?>-inputEl').val();
				var date_out = $('#date_out_<?=$tab_id?>-inputEl').val() + ' ' + $('#hour_out_<?=$tab_id?>-inputEl').val() +'.' + $('#min_out_<?=$tab_id?>-inputEl').val();
				var kegiatan = $('#kegiatan_<?=$tab_id?>-inputEl').val();
				// var shipping_line = $('#shipping_line_<?=$tab_id?>-inputEl').val();
				var esy = $('#esy_<?=$tab_id?>-inputEl').val();
				var gate = $('#gate_<?=$tab_id?>-inputEl').val();
				var vessel = $('#vessel_<?=$tab_id?>-inputEl').val();
				var pbm = $('#pbm_<?=$tab_id?>-inputEl').val();

				var URL = "<?=controller_?>report_gate/get_ves_voyage";
				var id_ves_voyage = "";
				$.ajax({
					type: "POST",
					url : URL,
					data: {'VESSEL' : vessel},
					success: function (result) {
						var id_ves_voyage = Ext.JSON.decode(result);
						var show_data = [{
							DATE_GATE_IN:date_in,
							DATE_GATE_OUT:date_out,
							KEGIATAN:kegiatan,
							ESY:esy,
							GATE:gate,
							VESSEL:id_ves_voyage,
							pbm:pbm
						}]
						tampil_store.getProxy().extraParams = {
							show_data: JSON.stringify(show_data)
						};
						tampil_store.load();
					}
				});
			}
		},
		{
			text: 'Export to Excel',
			formBind: true,
			handler: function() {
				var date_in = $('#date_in_<?=$tab_id?>-inputEl').val() + ' ' + $('#hour_in_<?=$tab_id?>-inputEl').val() +'.' + $('#min_in_<?=$tab_id?>-inputEl').val();
                            var date_out = $('#date_out_<?=$tab_id?>-inputEl').val() + ' ' + $('#hour_out_<?=$tab_id?>-inputEl').val() +'.' + $('#min_out_<?=$tab_id?>-inputEl').val();
                            var kegiatan = $('#kegiatan_<?=$tab_id?>-inputEl').val();
                            // var shipping_line = $('#shipping_line_<?=$tab_id?>-inputEl').val();
                            var esy = $('#esy_<?=$tab_id?>-inputEl').val();
                            var gate = $('#gate_<?=$tab_id?>-inputEl').val();
                            var vessel = $('#vessel_<?=$tab_id?>-inputEl').val();
                            var pbm = $('#pbm_<?=$tab_id?>-inputEl').val();

                            var URL = "<?=controller_?>report_gate/get_ves_voyage";
                            var id_ves_voyage = "";
							$.ajax({
									  type: "POST",
									  url : URL,
									  data: {'VESSEL' : vessel},
									  success: function (result) {
									  	var id_ves_voyage = Ext.JSON.decode(result);
								  	 	var url = '<?=controller_?>report_gate/get_data_gate?DATE_GATE_IN='+date_in+'&DATE_GATE_OUT='+date_out+
			                            '&KEGIATAN='+kegiatan+'&ESY='+esy+'&GATE='+gate+'&VESSEL='+id_ves_voyage+'&pbm='+pbm;

			                            window.open(url,'_blank');

									  }
								});

                            
                           
			}
		}
		]
	}).render('gate_in_out_<?=$tab_id?>');
	
	var contextMenu = Ext.create('Ext.menu.Menu', {
		items: [
			
		]
	});
	
	var ct_filters = {
		ftype: 'filters',
		encode: true,
		local: false
	};

	var ct_grid = Ext.create('Ext.grid.Panel', {
			id: 'data_report_gate_show<?=$tab_id?>',
			store: tampil_store,
			loadMask: true,
			width: 1440,
			height: 440,
			multiSelect: true,
			columns: [
				//{ text: 'NO', dataIndex: 'MCH_NAME', width: 90},
				{ text: 'COMPANY', dataIndex: 'COMPANY_NAME', width: 200},		
				{ text: 'NO CONTAINER', dataIndex: 'NO_CONTAINER', width: 200},		
				{ text: 'SIZE', dataIndex: 'CONT_SIZE', width: 90},		
				{ text: 'TYPE', dataIndex: 'CONT_TYPE', width: 90},		
				{ text: 'STATUS', dataIndex: 'CONT_STATUS', width: 90},		
				{ text: 'IMDG', dataIndex: 'IMDG', width: 90},	
				{ text: 'WEIGHT', dataIndex: 'WEIGHT', width: 90},		
				{ text: 'CLASS', dataIndex: 'ID_CLASS_CODE', width: 90},	
				{ text: 'VESSEL', dataIndex: 'VESSEL_NAME', width: 200},	
				{ text: 'VOY IN', dataIndex: 'VOY_IN', width: 90},	
				{ text: 'VOY OUT', dataIndex: 'VOY_OUT', width: 90},	
				{ text: 'ISO CODE', dataIndex: 'ID_ISO_CODE', width: 90},	
				{ text: 'POD(E)/POL(I)', dataIndex: 'POD', width: 90},	
				{ text: 'TID', dataIndex: 'TID', width: 90},		
				{ text: 'NO TRUCK', dataIndex: 'NO_TRUCK', width: 90},		
				{ text: 'GATE IN', dataIndex: 'GATE_IN', width: 90},	
				{ text: 'USER GATE IN', dataIndex: 'USER_GATE_IN', width: 90},	
				{ text: 'GATE OUT', dataIndex: 'GATE_OUT', width: 90},	
				{ text: 'USER GATE OUT', dataIndex: 'USER_GATE_OUT', width: 90},
				{ text: 'TERMINAL', dataIndex: 'TERMINAL_NAME', width: 200},
				{ text: 'SHIPPING', dataIndex: 'SHIPPING', width: 200},
				{ text: 'ESY', dataIndex: 'ESY', width: 90},	
				{ text: 'TRT (Menit)', dataIndex: 'TRT', width: 90}
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
		}).render('data_report_gate<?=$tab_id?>');
	$(document).ready(function (){
		
	});
</script>
<div id="gate_in_out_<?=$tab_id?>"></div>
<div id="data_report_gate<?=$tab_id?>"></div>