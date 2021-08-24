<script type="text/javascript">
	Ext.onReady(function() {
		var yard_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['NO_CONTAINER', 'ID_CLASS_CODE', 'ID_ISO_CODE','POD', 'PLACEMENT_DATE','PLACEMENT_TIME','YC_REAL', 'OPERATOR_NAME','YD_BLOCK_NAME', 'YD_SLOT', 'YD_ROW', 'YD_TIER', 'VESSEL_NAME','VOY_IN','VOY_OUT', 'CONT_STATUS', 'DWELLING_TIME', 'CONT_SIZE'],
			autoLoad: true,
			remoteSort: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>yard_reports/data_stacking',
				reader: {
					type: 'json',
					root: 'data',
					totalProperty: 'total'
				}
			},
			pageSize: 20,
			sorters: [{
				property: 'PLACEMENT_DATE',
				direction: 'ASC'
			}]
		});

		var yard_filters_<?=$tab_id?> = {
			ftype: 'filters',
			encode: true,
			local: false
		};

		var io_<?=$tab_id?> = Ext.create('Ext.data.Store', {
            fields: ['valx', 'name'],
            data: [
	                {"valx": "","name": "-- All --"},
	                {"valx": "E","name": "Outbound"},
	                {"valx": "I","name": "Inbound"}
            ],
		});

		var shippingline_<?=$tab_id?> = Ext.create('Ext.data.Store',{
			fields: ['ID_OPERATOR','OPERATOR_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>yard_reports/get_data_shippingline/',
				reader: {
					type: 'json'
				}
			},
		});

		var location_<?=$tab_id?> = Ext.create('Ext.data.Store', {
	            fields: ['YD_BLOCK_NAME'],
	            proxy: {
				type: 'ajax',
				url: '<?=controller_?>yard_reports/get_data_location/',
				reader: {
					type: 'json'
				}
			},
		});

		var yard_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'yard_grid_<?=$tab_id?>',
			store: yard_store_<?=$tab_id?>,
			width: 2300,
			columns: [
				{ xtype: 'rownumberer', text: 'NO', width: 35 },
				{ text: 'NO CONTAINER', dataIndex: 'NO_CONTAINER', width: 120, filter: {type: 'string'}},
				{ text: 'SIZE', dataIndex: 'CONT_SIZE', width: 60, filter: {type: 'string'}},
				{ text: 'ISO CODE', dataIndex: 'ID_ISO_CODE', width: 100},
				{ text: 'POD(E)/POL(I)', dataIndex: 'POD', width: 200},
				{ text: 'CLASS', dataIndex: 'ID_CLASS_CODE' , width: 100, filter: {type: 'string'}},
				{ text: 'STATUS', dataIndex: 'CONT_STATUS' , width: 100, filter: {type: 'string'}},
				{ text: 'PLACEMENT DATE', dataIndex: 'PLACEMENT_DATE' , width: 180, filter: {type: 'string'}},
				{ text: 'PLACEMENT TIME', dataIndex: 'PLACEMENT_TIME' , width: 150, filter: {type: 'string'}},
				{ text: 'EQUIPMENT', dataIndex: 'YC_REAL' , width: 100, filter: {type: 'string'}},
				{ text: 'SHIPPING LINE', dataIndex: 'OPERATOR_NAME' , width: 180, filter: {type: 'string'}},
				{ text: 'LOCATION', dataIndex: 'YD_BLOCK_NAME' , width: 100, filter: {type: 'string'}},
				{ text: 'SLOT', dataIndex: 'YD_SLOT' , width: 100, filter: {type: 'string'}},
				{ text: 'ROW', dataIndex: 'YD_ROW' , width: 100, filter: {type: 'string'}},
				{ text: 'TIER', dataIndex: 'YD_TIER' , width: 100, filter: {type: 'string'}},
				{ text: 'VESSEL', dataIndex: 'VESSEL_NAME' , width: 100, filter: {type: 'string'}},
				{ text: 'VOY IN', dataIndex: 'VOY_IN' , width: 100, filter: {type: 'string'}},
				{ text: 'VOY OUT', dataIndex: 'VOY_OUT' , width: 100, filter: {type: 'string'}},
				{ text: 'DWELLING TIME', dataIndex: 'DWELLING_TIME' , 
							  renderer: function(value, metaData, record, row, col, store, gridView){
								  $dt=value.split(' hari');
								return $dt[0] + ' hari';
							  } , width: 200, filter: {type: 'numeric'}}
			],
			// tbar: [
			// 		{ xtype: 'button',
			// 		   text: 'EXPORT TO EXCEL',
			// 		   handler: function (){
			// 				window.open('<?=controller_?>yard_reports/get_data_yard/','_blank');
			// 		   } 
			// 		}
			// ],
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
		}).render('yard_grid_<?=$tab_id?>');
		Ext.getCmp('west_panel').expand();

	Ext.create('Ext.form.Panel', {
			id: "yard_grid_form_<?=$tab_id?>",
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
		items: [
		// FIELD SELECT VESSEL
		{
			xtype: 'fieldset',
			title: 'FILTER',
			items: [
			// shippingline_
			{
				id: "shippingline_<?=$tab_id?>",
				store: shippingline_<?=$tab_id?>,
				xtype: 'combo',
				valueField: 'ID_OPERATOR',
				displayField: 'OPERATOR_NAME',
				emptyText: 'filter by Shipping Line',
				editable: true,
				name: "FILTER_shippingline",
				fieldLabel: 'By Shipping Line',
				width: 500
			},
			// io
			{
				id: "io_<?=$tab_id?>",
				store: io_<?=$tab_id?>,
				xtype: 'combo',
				valueField: 'valx',
				displayField: 'name',
				emptyText: 'filter by I/O',
				editable: true,
				name: "FILTER_io",
				fieldLabel: 'By I/O',
				width: 500
			},
			// Location
			{
				id: "location_<?=$tab_id?>",
				store: location_<?=$tab_id?>,
				xtype: 'combo',
				valueField: 'YD_BLOCK_NAME',
				displayField: 'YD_BLOCK_NAME',
				emptyText: 'filter by Location',
				editable: true,
				name: "FILTER_location",
				fieldLabel: 'By Location',
				width: 500
			},{
				xtype: 'fieldcontainer',
				fieldLabel: 'Dweling Time From',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [{
					id: "dw_date_<?=$tab_id?>",
					xtype: 'datefield',
					name: "DW_DATE",
					fieldLabel: 'Dweling Date',
					emptyText: 'Pick Date',
					format: 'd-m-Y',
					width: 120,
					editable: false,
					allowBlank: false
				},{
					id: "dw_hour_<?=$tab_id?>",
					xtype: 'textfield',
					name: "DW_HOUR",
					fieldLabel: 'Dweling Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23',
					allowBlank: false
				},{
					id: "dw_min_<?=$tab_id?>",
					xtype: 'textfield',
					name: "DW_MIN",
					fieldLabel: 'Dweling Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59',
					allowBlank: false
				},
				{
					xtype: 'button',
					text: 'Clear',
					handler: function(){
						Ext.getCmp('dw_date_<?=$tab_id?>').setValue('');
						Ext.getCmp('dw_hour_<?=$tab_id?>').setValue('');
						Ext.getCmp('dw_min_<?=$tab_id?>').setValue('');
					}
				}]
			},{
				xtype: 'fieldcontainer',
				fieldLabel: 'Dweling Time To',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [{
					id: "dw_date_t<?=$tab_id?>",
					xtype: 'datefield',
					name: "DW_DATE_T",
					fieldLabel: 'Dweling Date',
					emptyText: 'Pick Date',
					format: 'd-m-Y',
					width: 120,
					editable: false,
//					allowBlank: false
				},{
					id: "dw_hour_t<?=$tab_id?>",
					xtype: 'textfield',
					name: "DW_HOUR_T",
					fieldLabel: 'Dweling Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23',
//					allowBlank: false
				},{
					id: "dw_min_t<?=$tab_id?>",
					xtype: 'textfield',
					name: "DW_MIN_T",
					fieldLabel: 'Dweling Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59',
//					allowBlank: false
				},
				{
					xtype: 'button',
					text: 'Clear',
					handler: function(){
						Ext.getCmp('dw_date_t<?=$tab_id?>').setValue('');
						Ext.getCmp('dw_hour_t<?=$tab_id?>').setValue('');
						Ext.getCmp('dw_min_t<?=$tab_id?>').setValue('');
					}
				}]

			}]
			},],

			// DT
			/*{
				xtype: 'fieldcontainer',
				fieldLabel: 'By Dwelling Time From',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [
				{
					id: "DtHari_<?=$tab_id?>",
					xtype: 'textfield',
					name: "DT_HARI",
					emptyText: 'day',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 75,
					// maskRe: /[\d]/,
					// regex: /^([0,1]?\d|5[0-5])$/,
					// regexText: 'Value of this field must between 0-50',
					allowBlank: false
				},{
					id: "DtJam_<?=$tab_id?>",
					xtype: 'textfield',
					name: "DT_JAM",
					emptyText: 'hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 75,
					// maskRe: /[\d]/,
					// regex: /^([0,1]?\d|2[0-4])$/,
					// regexText: 'Value of this field must between 0-24',
					allowBlank: false
				}]
			},
			{
				xtype: 'fieldcontainer',
				fieldLabel: 'By Dwelling Time To',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [
				{
					id: "DtHarit_<?=$tab_id?>",
					xtype: 'textfield',
					name: "DT_HARIt",
					emptyText: 'day',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 75,
					// maskRe: /[\d]/,
					// regex: /^([0,1]?\d|5[0-0])$/,
					// regexText: 'Value of this field must between 0-50',
					allowBlank: false
				},{
					id: "DtJamt_<?=$tab_id?>",
					xtype: 'textfield',
					name: "DT_JAMt",
					emptyText: 'hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 75,
					// maskRe: /[\d]/,
					// regex: /^([0,1]?\d|2[0-4])$/,
					// regexText: 'Value of this field must between 0-24',
					allowBlank: false
				}]
			}
			]
		},*/
		buttons: [{
			text: 'Show Data',
			formBind: true,
			handler: function() {
				var filter_sl = $('#shippingline_<?=$tab_id?>-inputEl').val();
				var filter_io = $('#io_<?=$tab_id?>-inputEl').val();
				var filter_location = $('#location_<?=$tab_id?>-inputEl').val();
				var filter_dfd = $('#dw_date_<?=$tab_id?>-inputEl').val();
				var filter_dfh = $('#dw_hour_<?=$tab_id?>-inputEl').val();
				var filter_dfm = $('#dw_min_<?=$tab_id?>-inputEl').val();
				var filter_dtd = $('#dw_date_t<?=$tab_id?>-inputEl').val();
				var filter_dth = $('#dw_hour_t<?=$tab_id?>-inputEl').val();
				var filter_dtm = $('#dw_min_t<?=$tab_id?>-inputEl').val();
				
				var show_data = [{sl: filter_sl, io: filter_io, lc: filter_location, dfd: filter_dfd, dfh: filter_dfh, dfm: filter_dfm, dtd: filter_dtd, dth: filter_dth, dtm: filter_dtm}]
				yard_store_<?=$tab_id?>.getProxy().extraParams = {
							show_data: JSON.stringify(show_data)
						};
				yard_store_<?=$tab_id?>.load();
			}
		},{
			text: 'EXPORT TO EXCEL',
			handler: function (){
				var filter_sl = $('#shippingline_<?=$tab_id?>-inputEl').val();
				var filter_io = $('#io_<?=$tab_id?>-inputEl').val();
				var filter_location = $('#location_<?=$tab_id?>-inputEl').val();
				var filter_dfd = $('#dw_date_<?=$tab_id?>-inputEl').val();
				var filter_dfh = $('#dw_hour_<?=$tab_id?>-inputEl').val();
				var filter_dfm = $('#dw_min_<?=$tab_id?>-inputEl').val();
				var filter_dtd = $('#dw_date_t<?=$tab_id?>-inputEl').val();
				var filter_dth = $('#dw_hour_t<?=$tab_id?>-inputEl').val();
				var filter_dtm = $('#dw_min_t<?=$tab_id?>-inputEl').val();
				var url = '<?=controller_?>yard_reports/get_data_yard?sl='+filter_sl+'&io='+filter_io+'&lc='+filter_location+'&dfd='+filter_dfd+'&dfh='+filter_dfh+'&dfm='+filter_dfm+'&dtd='+filter_dtd+'&dth='+filter_dth+'&dtm='+filter_dtm;
				window.open(url,'_blank');
			} 
		}]
	}).render('yard_grid_form_<?=$tab_id?>');
		// yard_grid_<?=$tab_id?>.render('yard_grid_<?=$tab_id?>');
	});

</script>
<div id="yard_grid_form_<?=$tab_id?>"></div>
<div id="yard_grid_<?=$tab_id?>"></div>