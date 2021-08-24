<script type="text/javascript">
$(function() {
	var history_data_change_store = Ext.create('Ext.data.Store', {
		fields:['ID_OP_STATUS', 'OP_STATUS_DESC', 'DATE_HISTORY_CHAR'],
		autoLoad: true,
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>container_inquiry/history_data_change_detail',
			extraParams: {
				no_container: "<?=$no_container?>",
				point: "<?=$point?>"
			},
			reader: {
				type: 'json'
			}
		}
	});
	
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'History Data Change',
		width: 500,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [{
				id: 'history_data_change_detail<?=$tab_id?>',
				xtype: 'grid',
				width: 500,
				minHeight: 150,
				autoScroll: true,
				store: history_data_change_store,
				columns: [
					{ text: 'Status', dataIndex: 'ID_OP_STATUS', width: 100 },
					{ text: 'Operation', dataIndex: 'OP_STATUS_DESC', width: 200 },
					{ text: 'Date / Time', dataIndex: 'DATE_HISTORY_CHAR', width: 200 }
				]
			}]
		})]
	});
	win.show();
});
</script>