<script src="<?=JS_?>md5.js"></script>
<script>
	var yardlist_store = Ext.create('Ext.data.Store', {
		fields:['NAME', 'NUM_BLOCK', 'ID_YARD'],
		autoLoad: true,
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>yard_editor/data_yard_list/',
			reader: {
				type: 'json'
			}
		}
	});
	
	var yardlist_grid = Ext.create('Ext.grid.Panel', {
		store: yardlist_store,
		id: 'yard_list_grid',
		height:500,
		columns: [
			{ text: 'Name', dataIndex: 'NAME' },
			{ text: '# of Block', dataIndex: 'NUM_BLOCK' },
			{ text: 'ID Yard', dataIndex: 'ID_YARD', hidden: true, hideable: false },
			{ 
				text: 'Edit',
				xtype: 'actioncolumn',
				width: 70,
				items: [{
					icon: "<?=IMG_?>icons/edit.png",
					tooltip: 'Edit',
					handler: function(grid, rowIndex, colIndex) {
						var rec = grid.getStore().getAt(rowIndex);
						loadmask.show();
						Ext.getCmp('<?=$tab_id?>').setTitle('Yard Editor - '+rec.get('NAME'));
						Ext.getCmp('<?=$tab_id?>').getLoader().load({
							url: '<?=controller_?>yard_editor/editor_panel?tab_id=<?=$tab_id?>&id_yard='+rec.get('ID_YARD'),
							scripts: true,
							contentType: 'html',
							autoLoad: true,
							success: function(){
								loadmask.hide();
							}
						});
						win.close();
					}
				}]
			},
			{
				text: 'Delete',
				xtype: 'actioncolumn',
				width: 70,
				items: [{
					icon: "<?=IMG_?>icons/delete.png",
					tooltip: 'Delete',
					handler: function(grid, rowIndex, colIndex) {
						var pouppassword = new Ext.Window({
							width: 350,
							floating: true,
							id: 'pouppassword',
							closable : true,
							layout: 'fit',
							modal: true,
							title: 'Check Password Login',
							icon: '<?=IMG_?>icons/itos-icon.png',
							items: Ext.create('Ext.form.Panel', {
								frame: true,
								autoScroll: true,
								bodyPadding: 5,
								fieldDefaults: {
									labelAlign: 'left',
									labelWidth: 90,
									anchor: '100%'
								},
								items: [{
									xtype: 'textfield',
									inputType: 'password',
									id: 'password',
									name: 'password',
									fieldLabel: 'password',
									allowBlank: false,
									listeners: {
										specialkey: function(field, e){
											if (e.getKey() == e.ENTER) {
												var login_button = Ext.getCmp('login_button');
												login_button.fireEvent('click', login_button);
											}
										}
									}
								}],
								buttons: [{
									text: 'OK',
									id: 'login_button',
									formBind: true,
									listeners: {
										click: {
											fn: function () {
												var form = this.up('form').getForm();
												if (form.isValid()){
													loadmask.show();
													
													var url = "<?=controller_?>yard_editor/login_unset";
													$.post( url, {
															  password: CryptoJS.MD5(Ext.getCmp('password').getRawValue()).toString()
															  }, function(data) {
															loadmask.hide();
														data = JSON.parse(data);
														if (data.success){
															pouppassword.close();
															Ext.MessageBox.confirm('Confirm', 'Are you sure you want to do that?', function(confirmation){
																if (confirmation=='yes'){
																	var rec = grid.getStore().getAt(rowIndex);
																	loadmask.show();
																	var url = "<?=controller_?>yard_editor/delete_yard";
																	$.post( url, { id_yard: rec.get('ID_YARD')}, function(data) {
																		console.log(data);
																		loadmask.hide();
																		Ext.Msg.alert('Success', 'Yard Deleted');
																		grid.getStore().reload();
																		// win.close();
																		// Ext.getCmp('<?=$tab_id?>').close();
																	});
																}
															});
														}else{
															Ext.Msg.alert('Failed', data.errors);
														}
													});
												}
											}
										}
									}
								}]
							})
						});
						pouppassword.show();
					}
				}]
			}
		],
		emptyText: 'No Data Found'
	});
	
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Choose Yard',
		width: 350,
		closable: false,
		items: yardlist_grid,
		buttons: [{
			text: 'Cancel',
			handler: function() {
				win.close();
				Ext.getCmp('<?=$tab_id?>').close();
			}
		}]
	});
	win.show();
</script>
