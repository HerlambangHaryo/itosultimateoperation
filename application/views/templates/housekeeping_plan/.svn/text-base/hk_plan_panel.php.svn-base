<script type="text/javascript">
$( document ).ready(function() {
    $('#hk_plan_grid<?=$tab_id?>').load('<?=controller_?>housekeeping_plan/load_hkplan/<?=$tab_id?>');
});
/*panel diatas*/
	var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
	var mv_order_hk<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['HKP_ACTIVITY', 'HKP_ACTIVITY_DESC'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>housekeeping_plan/data_mv_order/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	var itv_used = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        data: [{
            "id": "Y",
            "name": "Yes"
        }, {
            "id": "N",
            "name": "No"
        }]
    });
	
	var mv_virt_crane<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_MACHINE', 'MCH_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>housekeeping_plan/data_virt_crane/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	Ext.create('Ext.form.Panel', {
		id: "hk_plan_above<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		//type:"GET",
		url: '<?=controller_?>housekeeping_plan/create_hkplan',
		items: [
		{
			xtype: 'fieldset',
			title: 'Housekeeping Data',
			items: [
			{
				id: 'mv_Order<?=$tab_id?>',
				xtype: 'combo',
				name: "mv_Order",
				fieldLabel: 'Movement Order',
				width: 300,
				store: mv_order_hk<?=$tab_id?>,
				queryMode: 'local',
				valueField: 'HKP_ACTIVITY',
				displayField: 'HKP_ACTIVITY_DESC',
				fieldStyle: 'background-color: #ffffcc; background-image: none;'
			},
			{
				id: 'virtual_crane<?=$tab_id?>',
				xtype: 'combo',
				fieldLabel: 'Virtual Crane',
				name: "virtual_crane",
				store: mv_virt_crane<?=$tab_id?>,
				queryMode: 'local',
				valueField: 'ID_MACHINE',
				displayField: 'MCH_NAME',
				fieldStyle: 'background-color: #ffffcc; background-image: none;'
			},
			{
				xtype: 'field',
				fieldLabel: 'Movement Description',
				name: 'mv_Desc',
				fieldStyle: 'background-color: #ffffcc; background-image: none;'
			},
			{
				id: 'itv_use<?=$tab_id?>',
				xtype: 'combo',
				name: "itv_use",
				fieldLabel: 'ITV Use',
				width: 200,
				store: itv_used,
				queryMode: 'local',
				valueField: 'id',
				displayField: 'name',
				fieldStyle: 'background-color: #ffffcc; background-image: none;'
			}
		]
		}],
		buttons: [{
			text: 'Save',
			id: 'Save_hk<?=$tab_id?>',
			formBind: true,
			listeners: {
				click: {
					fn: function () {
						var form = this.up('form').getForm();
						if (form.isValid()){
							loadmask.show();
							form.submit({
								success: function(form, action) {
									loadmask.hide();
									$('#hk_plan_grid<?=$tab_id?>').load('<?=controller_?>housekeeping_plan/load_hkplan/<?=$tab_id?>');
								},
								failure: function(form, action) {
									loadmask.hide();
									$('#hk_plan_grid<?=$tab_id?>').load('<?=controller_?>housekeeping_plan/load_hkplan/<?=$tab_id?>');
								}
							});
						}
					}
				}
			}
		}]
	}).render('hk_plan_above<?=$tab_id?>');
	
	
</script>
<div id="hk_plan_above<?=$tab_id?>"> </div>
<div id="hk_plan_grid<?=$tab_id?>"></div>