<style>
	.home_container {
		margin-top: -10px ;
		margin-left: 10px ;
		margin-right: 80px ;
		border: 1px solid #D0D0D0;
		padding: 10px 20px 10px 0px;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
		width: auto;
	}
	.title_container {
		position:fixed;
	}
</style>

<script type="text/javascript">
	$(function() {
		$.contextMenu({
			selector: '.boxh', 
			
			items: {
				"delete": {
					name: "Delete Sequence", 
					icon: "delete", 
					callback: function(key, options) {
						deleteSeq_<?=$tab_id?>();
					}
				},
				"sep1": "---------",
				"quit": {
					name: "Quit",
					icon: "quit",
					callback: function(key, options) {
						$(this).contextMenu("hide");
					}
				}
			}
		});
	});

	$('#contentCwp<?=$tab_id?>').load('<?=controller_?>qc_working_plan/cwpContent/<?=$id_ves_voyage?>/<?=$tab_id?>');
	var idbefore_<?=$tab_id?>;
	var colorbefore_<?=$tab_id?>;
	
	function reload_cwp_content_<?=$tab_id?>()
	{
		$('#contentCwp<?=$tab_id?>').load('<?=controller_?>qc_working_plan/cwpContent/<?=$id_ves_voyage?>/<?=$tab_id?>');
	}
	
	function deleteSeq_<?=$tab_id?>()
	{
		var v_idmchwkplan= $('#selected-v_idmchwkplan_'+'<?=$tab_id?>').val();
		
		//console.log($('#selected-v_seq_'+'<?=$tab_id?>').val());
		var v_seq= $('#selected-v_seq_'+'<?=$tab_id?>').val();
		var v_bay= $('#selected-v_bay_'+'<?=$tab_id?>').val();
		var v_act= $('#selected-v_act_'+'<?=$tab_id?>').val();
		var v_deck= $('#selected-v_deck_'+'<?=$tab_id?>').val();
		var url='<?=controller_?>qc_working_plan/deleteSequenceCwp';
                if(v_idmchwkplan == '' || v_seq == '' || v_bay == '' || v_act == '' || v_deck == ''){
                    alert('Please select sequence to be delete!');
                }else{
                    $.post(url,{IDMACHINE:v_idmchwkplan,SEQUENCE:v_seq,BAY:v_bay,ACTIVITY:v_act,DECK:v_deck, ID_VSBVOY:'<?=$id_ves_voyage?>'}, function(data){
                            alert(data);
    //			console.log(data);
                            if(data.trim() == 'OK')
    //			    alert('reload');
                                reload_cwp_content_<?=$tab_id?>();
                    });
                }
	}
	
	function onClickSeq_<?=$tab_id?>(a,b)
	{
	    var bay = $(a).attr('data-bay');
		// console.log('a: '+a+',B: '+b);
		if(idbefore_<?=$tab_id?>!='')
		{
			$(idbefore_<?=$tab_id?>).css('background-color', colorbefore_<?=$tab_id?>);
			$(idbefore_<?=$tab_id?>).css('color', '#000000');
		}
		
		idbefore_<?=$tab_id?>= '#' + $(a).attr('id');
		colorbefore_<?=$tab_id?>=$(a).css('background-color');
		$(a).css('background-color', '#403f3f');
		$(a).css('color', '#ffffff');
		
		$('#selected-v_idmchwkplan_<?=$tab_id?>').val($(a).attr('data-id-mch-working-plan'));
		$('#selected-v_seq_<?=$tab_id?>').val($(a).attr('data-sequence'));
		$('#selected-v_bay_<?=$tab_id?>').val($(a).attr('data-bay'));
		$('#selected-v_act_<?=$tab_id?>').val($(a).attr('data-activity'));
		$('#selected-v_deck_<?=$tab_id?>').val(b);
	}
	
	addTab('east_panel', 'qc_working_plan/qc_summary', '<?=$id_ves_voyage?>', 'QC-Summary');
	Ext.getCmp('east_panel').expand();
	Ext.getCmp('west_panel').collapse();
	var id_vesvoy = "<?=$id_ves_voyage?>";
	
	var mch_list_store = Ext.create('Ext.data.Store', {
		fields:['ID_MACHINE', 'MCH_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>qc_working_plan/data_machine_cwp/'+id_vesvoy,
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var classcode_store = Ext.create('Ext.data.Store', {
		fields:['ID_CLASS_CODE', 'CODE_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>qc_working_plan/get_classcode/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	function assign_cwp_<?=$tab_id?>(no_bay,id_bay,display_bay,position,id_classcode)
	{
		var win = new Ext.Window({
			layout: 'fit',
			modal: true,
			title: 'CWP Bay '+display_bay+' '+position,
			closable: false,
			items: Ext.create('Ext.form.Panel', {
				frame: true,
				autoScroll: true,
				bodyPadding: 5,
				url: '<?=controller_?>qc_working_plan/assign_mch_cwp?tab_id=<?=$tab_id?>&vsvoy_id=<?=$id_ves_voyage?>',
				fieldDefaults: {
					labelAlign: 'left',
					labelWidth: 90,
					anchor: '100%'
				},
				items: [{
					xtype: 'hiddenfield',
					name: 'ID_BAY',
					value: id_bay
				},{
					xtype: 'hiddenfield',
					name: 'BAY_POSITION',
					value: position
				},{
					xtype:'combo',
					id: "mch_<?=$tab_id?>",
					name: "MACHINE_NAME",
					displayField: 'MCH_NAME',
					valueField: 'MCH_NAME',
					fieldLabel: 'Machine',
					allowBlank: false,
					anchor:'95%',
					emptyText: '- Choose -',
					queryMode: 'remote',
					store: mch_list_store
				},{
					xtype:'combo',
					id: "classcode_<?=$tab_id?>",
					name: 'CLASSCODE',
					displayField: 'CODE_NAME',
					valueField: 'ID_CLASS_CODE',
					value:id_classcode,
					fieldLabel: 'Class',
					allowBlank: false,
					anchor:'95%',
					emptyText: '- Choose -',
					queryMode: 'local',
					store: Ext.create('Ext.data.Store', {
						fields:['ID_CLASS_CODE', 'CODE_NAME'],
						data : [
							 {ID_CLASS_CODE: 'IMPORT', CODE_NAME: 'IMPORT'},
							 {ID_CLASS_CODE: 'EXPORT', CODE_NAME: 'EXPORT'}
						 ]
					})
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
									Ext.Msg.alert('Success', 'assign success');
									// console.log(Ext.getCmp('<?=$tab_id?>'));
									win.close();
									reloadaja_<?=$tab_id?>();
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
	}
</script>
<div id="contentCwp<?=$tab_id?>" class="contentCWP_<?=$id_ves_voyage?>" tab-id="<?=$tab_id?>" style="margin-top: 95px;"></div>