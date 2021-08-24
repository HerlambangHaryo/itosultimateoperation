<script type="text/javascript">
	Ext.onReady(function() {
		var id_vesvoy = "<?=$id_ves_voyage?>";
		var cranelist_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['ID_MCH_WORKING_PLAN', 'MCH_NAME', 'BCH', 'START_WORK', 'END_WORK'],
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>qc_assignment_plan/get_active_mch/'+id_vesvoy,
				reader: {
					type: 'json'
				}
			}
		});
		
		var crane_list_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'crane_list_<?=$tab_id?>',
			title: 'Crane List',
			width: 850,
			store: cranelist_store_<?=$tab_id?>,
			columns: [
				{ dataIndex: 'ID_MCH_WORKING_PLAN', hidden: true, hideable: false},
				{ text: 'Machine', dataIndex: 'MCH_NAME', align: 'center', width: 260},
				{ text: 'BCH', dataIndex: 'BCH', align: 'center', width: 130},
				{ text: 'Start', dataIndex: 'START_WORK', align: 'center', width: 230},
				{ text: 'End', dataIndex: 'END_WORK', align: 'center', width: 230}
			],
			listeners: {
				'selectionchange': function(view, records) {
					Ext.getCmp('crane_list_<?=$tab_id?>').down('#remove_qc_<?=$tab_id?>').setDisabled(!records.length);
				}
			},
			tbar: [
					 { xtype: 'button', 
					   text: 'Add Machine',
					   handler: function (){
							var mch_master_store = Ext.create('Ext.data.Store', {
										fields:['ID_MACHINE', 'MCH_NAME'],
										proxy: {
											type: 'ajax',
											url: '<?=controller_?>qc_assignment_plan/get_machine_mst/'+id_vesvoy,
											reader: {
												type: 'json'
											}
										},
										autoLoad: true
									});

						   var win = new Ext.Window({
								layout: 'fit',
								modal: true,
								title: 'Machine for Vessel Working',
								closable: false,
								items: Ext.create('Ext.form.Panel', {
									frame: true,
									autoScroll: true,
									bodyPadding: 5,
									fieldDefaults: {
										labelAlign: 'left',
										labelWidth: 90,
										anchor: '100%'
									},
									url: '<?=controller_?>qc_assignment_plan/save_machine_vesvoy/'+id_vesvoy,
									items: [{
										xtype:'combo',
										id: "mch_<?=$tab_id?>",
										name: "MACHINE_NAME",
										displayField: 'MCH_NAME',
										valueField: 'ID_MACHINE',
										fieldLabel: 'Machine',
										allowBlank: false,
										anchor:'95%',
										forceSelection: true,
										emptyText: '- Choose -',
										queryMode: 'remote',
										store: mch_master_store
									},
									{
										id: "bch_<?=$tab_id?>",
										xtype: 'numberfield',
										name: "BCH",
										fieldLabel: 'BCH',
										allowDecimals: false,
										allowBlank: false
									},
									{
										xtype: 'fieldcontainer',
										fieldLabel: 'Start Work',
										layout: 'hbox',
										combineErrors: true,
										defaultType: 'textfield',
										defaults: {
											hideLabel: 'true'
										},
										items: [{
											id: "start_date_<?=$tab_id?>",
											xtype: 'datefield',
											name: "START_DATE",
											fieldLabel: 'Start Date',
											emptyText: 'Pick Date',
											format: 'd-m-Y',
											width: 120,
											editable: false,
											allowBlank: false
										},{
											id: "start_hour_<?=$tab_id?>",
											xtype: 'textfield',
											name: "START_HOUR",
											fieldLabel: 'Start Hour',
											minLength: 1,
											maxLength: 2,
											enforceMaxLength: true,
											width: 50,
											maskRe: /[\d]/,
											regex: /^([0,1]?\d|2[0-3])$/,
											regexText: 'Value of this field must between 0-23',
											allowBlank: false
										},{
											id: "start_min_<?=$tab_id?>",
											xtype: 'textfield',
											name: "START_MIN",
											fieldLabel: 'Start Minute',
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
										fieldLabel: 'End Work',
										layout: 'hbox',
										combineErrors: true,
										defaultType: 'textfield',
										defaults: {
											hideLabel: 'true'
										},
										items: [{
											id: "end_date_<?=$tab_id?>",
											xtype: 'datefield',
											name: "END_DATE",
											fieldLabel: 'End Date',
											emptyText: 'Pick Date',
											format: 'd-m-Y',
											width: 120,
											editable: false,
											allowBlank: false
										},{
											id: "end_hour_<?=$tab_id?>",
											xtype: 'textfield',
											name: "END_HOUR",
											fieldLabel: 'End Hour',
											minLength: 1,
											maxLength: 2,
											enforceMaxLength: true,
											width: 50,
											maskRe: /[\d]/,
											regex: /^([0,1]?\d|2[0-3])$/,
											regexText: 'Value of this field must between 0-23',
											allowBlank: false
										},{
											id: "end_min_<?=$tab_id?>",
											xtype: 'textfield',
											name: "END_MIN",
											fieldLabel: 'End Minute',
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
										text: 'Assign',
										formBind: true,
										handler: function() {
											var form = this.up('form').getForm();
											if (form.isValid()){
												loadmask.show();
												form.submit({
													success: function(form, action) {
														loadmask.hide();
														Ext.Msg.alert('Success', 'update success');
														Ext.getCmp('crane_list_<?=$tab_id?>').getStore().reload();
														win.close();
													},
													failure: function(form, action) {
														loadmask.hide();
														Ext.Msg.alert('Failed', action.result.errors);
													}
												});	
											}
										}
									},{
										text: 'Cancel',
										handler: function() {
											win.close();
										}
									}]
								})
							});

							win.show();
					   } },
					   {
							xtype: 'button',
							text: 'Refresh Data',
							handler: function (){
								Ext.getCmp('crane_list_<?=$tab_id?>').getStore().reload();
							}
						},
						{
							itemId: 'remove_qc_<?=$tab_id?>',
							xtype: 'button',
							text: 'Delete Machine',
							disabled: true,
							handler: function (){
								var sm = Ext.getCmp('crane_list_<?=$tab_id?>').getSelectionModel();
								var selected = sm.getSelection();
								loadmask.show();
								Ext.Ajax.request({
									url: '<?=controller_?>qc_assignment_plan/delete_qc_assignment/',
									params: {
										ID_MCH_WORKING_PLAN: selected[0].data.ID_MCH_WORKING_PLAN
									},
									success: function(response){
										var text = response.responseText;
										if (text=='1'){
											Ext.MessageBox.show({
												title: 'Success',
												msg: 'Changes saved successfully.',
												buttons: Ext.MessageBox.OK
											});
											cranelist_store_<?=$tab_id?>.remove(selected);
											if (cranelist_store_<?=$tab_id?>.getCount() > 0) {
												sm.select(0);
											}
										}else{
											Ext.MessageBox.show({
												title: 'Error',
												msg: 'Failed to save changes.',
												buttons: Ext.MessageBox.OK
											});
										}
										loadmask.hide();
									}
								});
							}
						}
				  ]
		});
		
		crane_list_grid_<?=$tab_id?>.render('crane_list_grid_<?=$tab_id?>');
		
	});
</script>
<div id="crane_list_grid_<?=$tab_id?>"></div>