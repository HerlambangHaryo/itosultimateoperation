<style type="text/css">
	.x-mask {
		background: transparent !important;
	}
</style>
<script type="text/javascript">
	Ext.onReady(function() {
		var id_vesvoy = "<?=$id_ves_voyage?>";
		var cranelist_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
			fields:['MCH_NAME', 'BCH', 'START_WORK', 'END_WORK', 'COMPLETED', 'TOTALDATA', 'REMAIN', 'ACTION', 'BG_COLOR'], //tambahan
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>qc_working_plan/get_active_mch/'+id_vesvoy,
				reader: {
					type: 'json'
				}
			}
		});
		
		var crane_list_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
			id: 'crane_list_<?=$tab_id?>',
			title: 'Crane List',
			width: 800, 
			store: cranelist_store_<?=$tab_id?>,
			columns: [
				{ text: 'Machine', dataIndex: 'MCH_NAME', align: 'center', width: 80,
					renderer:function(value,metaData, record, rowIndex){
						var BG_COLOR = record.get('BG_COLOR');
						metaData.style="background-color:"+BG_COLOR+";color:white;";
						return value;
					},
				},
				// { text: 'BCH', dataIndex: 'BCH', align: 'center', width: 60},
				{ text: 'Start', dataIndex: 'START_WORK', align: 'center', width: 120},
				{ text: 'End', dataIndex: 'END_WORK', align: 'center', width: 120},
				{ text: 'Total', dataIndex: 'TOTALDATA', align: 'center', width: 80},
				{ text: 'Completed', dataIndex: 'COMPLETED', align: 'center', width: 100}, //tambahan
				{ text: 'Remain', dataIndex: 'REMAIN', align: 'center', width: 80}, //tambahan
				{ text: 'Action', dataIndex: 'ACTION', align: 'center', width: 120}
			],
			tbar: [
					 { xtype: 'button', 
					   text: 'Add Machine',
					   handler: function ()
					   {
							var mch_master_store = Ext.create('Ext.data.Store', {
										fields:['ID_MACHINE', 'MCH_NAME'],
										proxy: {
											type: 'ajax',
											url: '<?=controller_?>qc_working_plan/get_machine_mst/'+id_vesvoy,
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
									url: '<?=controller_?>qc_working_plan/save_machine_vesvoy/'+id_vesvoy,
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
//									{
//										id: "bch_<?=$tab_id?>",
//										xtype: 'numberfield',
//										name: "BCH",
//										fieldLabel: 'BCH',
//										allowDecimals: false,
//										allowBlank: false
//									},
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
											value:'<?=$detd?>',
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
											value:'<?=$hetd?>',
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
											value:'<?=$minetd?>',
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
//								Ext.getCmp('crane_list_<?=$tab_id?>').getStore().reload();
								loadmask.show();
								Ext.get("<?=$tab_id?>-innerCt").load({
//								    url: '<?=controller_?>qc_working_plan/refresh_qc_summary?tab_id=<?=$tab_id?>&id_ves_voyage=<?=$id_ves_voyage?>',
								    url: '<?=controller_?>qc_working_plan/qc_summary?tab_id=<?=$tab_id?>',
								    scripts: true,
								    contentType: 'html',
								    autoLoad: true,
								    params: {
									    data_id: '<?=$id_ves_voyage?>'
								    },
								    success: function(){
									loadmask.hide();
								    }
								});
							}
						}
				  ]
		});
		
		crane_list_grid_<?=$tab_id?>.render('crane_list_grid_<?=$tab_id?>');
		
		var qc_disch_grid_<?=$tab_id?> = Ext.create('Ext.grid.PropertyGrid', {
						id: 'qc_disch_grid_<?=$tab_id?>',
						title: 'Discharge',
						sortableColumns: false,
						disabled: true,
						source: {
							"Total":'<?=$dsc_total?> box',
							"Planned":'<?=$dsc_planned?> box',
							"QC unassigned": '<?=$dsc_qc?>',
							"Completed": '<?=$dsc_completed?> box',
							"Remained": '<?=$dsc_remained?> box'
						}
					});
		
		qc_disch_grid_<?=$tab_id?>.render('qc_disch_grid_<?=$tab_id?>');

		var qc_load_grid_<?=$tab_id?> = Ext.create('Ext.grid.PropertyGrid', {
						id: 'qc_load_grid_<?=$tab_id?>',
						title: 'Load',
						sortableColumns: false,
						disabled: true,
						source: {
							"Total":'<?=$load_total?> box',
							"Planned":'<?=$load_planned?> box',
							"QC unassigned": '<?=$load_qc?>',
							"Completed": '<?=$load_completed?> box',
							"Remained": '<?=$load_remained?> box'
						}
					});
		
		qc_load_grid_<?=$tab_id?>.render('qc_load_grid_<?=$tab_id?>');
	});
	
	
function deletemchstt(idmwp,mchname){
	Ext.MessageBox.confirm('Confirm', 'Apakah anda yakin akan menghapus '+mchname+' ?', showResult);
	function showResult(btn)
	{
		if(btn=='yes')
		{		
			loadmask.show();
			var url = "<?=controller_?>qc_working_plan/delete_mch";
			Ext.Ajax.request({
				url: url,
				method: 'POST',
				params: {
					ID_MCH_WORKING_PLAN: idmwp,
					MCH_NAME: mchname
				},
				scope: this,
				success: function(result, response) {
				loadmask.hide();
				var res = JSON.parse(result.responseText);
				var status = res.IsSuccess ? 'Success' : 'Failed';

				Ext.Msg.alert(status, res.Message);
				if(res.IsSuccess){
					Ext.getCmp('crane_list_<?=$tab_id?>').getStore().reload();
				}
				},
				failure:function(form, response) {
				Ext.Msg.alert('Failed: ', response.errorMessage);
				}
			});
		}
		else
		{
			Ext.MessageBox.alert('Status', 'Cancel.');
		}
	}
}
	
function updatemchstt(idvvd,mchid,mchname,bch,stwk,enwk){
	var mch_master_store1 = Ext.create('Ext.data.Store', {
        fields:['ID_MACHINE', 'MCH_NAME'],
        data : [
            {"ID_MACHINE":mchid, "MCH_NAME":mchname}
        ]
    });
	var stwkdt=stwk.split(" ");
	var stwkday=stwkdt[0];
	var stwkdays=stwkdt[1].split(":");
	var stwkhour=stwkdays[0];
	var stwkmin=stwkdays[1];

	var enwkdt=enwk.split(" ");
	var enwkday=enwkdt[0];
	var enwkdays=enwkdt[1].split(":");
	var enwkhour=enwkdays[0];
	var enwkmin=enwkdays[1];
	
	var win2 = new Ext.Window(
	{
		layout: 'fit',
		modal: true,
		title: 'Update Machine for Vessel Working',
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
			url: '<?=controller_?>qc_working_plan/update_machine_vesvoy/'+idvvd,
			items: [{
				xtype:'combo',
				id: "mch_<?=$tab_id?>",
				name: "MACHINE_NAME",
				displayField: 'MCH_NAME',
				valueField: 'ID_MACHINE',
				value:mchid,
				fieldLabel: 'Machine',
				allowBlank: false,
				anchor:'95%',
				forceSelection: true,
				//emptyText: mchname,
				queryMode: 'local',
				store: mch_master_store1
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
					value:stwkday,
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
					allowBlank: false,
					value:stwkhour
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
					allowBlank: false,
					value:stwkmin
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
					value:enwkday,
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
					value:enwkhour,
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
					value:enwkmin,
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
							    //<div id="contentCwp<?=$tab_id?>" class="contentCWP_<?=$id_ves_voyage?>" tab-id="<?=$tab_id?>"></div>
							    $('.contentCWP_<?=$id_ves_voyage?>').each(function(){
								$(this).load('<?=controller_?>qc_working_plan/cwpContent/<?=$id_ves_voyage?>/' + $(this).attr('tab-id'));
							    });
								loadmask.hide();
								Ext.Msg.alert('Success', 'update success');
								Ext.getCmp('crane_list_<?=$tab_id?>').getStore().reload();
								win2.close();
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
					win2.close();
				}
			}]
		})
	});
	
	win2.show();
	
}

</script>
<div id="crane_list_grid_<?=$tab_id?>"></div>
<div id="qc_disch_grid_<?=$tab_id?>"></div>
<div id="qc_load_grid_<?=$tab_id?>"></div>