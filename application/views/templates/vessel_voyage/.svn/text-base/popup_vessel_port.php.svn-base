<script type="text/javascript">
$(function() {
	var vessel_port_store = Ext.create('Ext.data.Store', {
		fields:['ID_PORT', 'PORT_NAME'],
		autoLoad: true,
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>vessel_voyage/vessel_port_list',
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
		title: 'Port of Discharge',
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
				store: vessel_port_store,
				columns: [
					{ text: 'ID Port', dataIndex: 'ID_PORT', width: 100 },
					{ text: 'Port Name', dataIndex: 'PORT_NAME', width: 300 }
				]
			}]
		})]
	});
	win.show();
});
</script>