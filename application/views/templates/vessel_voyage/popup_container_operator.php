<script type="text/javascript">
$(function() {
	var container_operator_store = Ext.create('Ext.data.Store', {
		fields:['ID_OPERATOR', 'OPERATOR_NAME'],
		autoLoad: true,
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>vessel_voyage/vessel_container_operator',
			extraParams: {
				id_ves_voyage: "<?=$id_ves_voyage?>"
			},
			reader: {
				type: 'json'
			}
		}
	});
	
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Container Operator',
		width: 500,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				xtype: 'grid',
				width: 400,
				minHeight: 150,
				autoScroll: true,
				store: container_operator_store,
				columns: [
					{ text: 'ID Operator', dataIndex: 'ID_OPERATOR', width: 100 },
					{ text: 'Operator Name', dataIndex: 'OPERATOR_NAME', width: 300 }
				]
			}]
		})]
	});
	win.show();
});
</script>