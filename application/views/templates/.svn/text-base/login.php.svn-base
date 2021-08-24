<script src="<?=JS_?>md5.js"></script>
<script>
	Ext.require(['*']);
	
	Ext.onReady(function() {
		loadmask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading..."});
		var get_terminal = Ext.create('Ext.data.Store', {
			fields:['ID_TERMINAL', 'TERMINAL_NAME'],
			proxy: {
				type: 'ajax',
				url: '<?=controller_?>main/get_terminal/',
				reader: {
					type: 'json'
				}
			},
			autoLoad: true
		});
		win = new Ext.Window({
			layout: 'fit',
			modal: true,
			title: 'MyTOS Terminal',
			closable: false,
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
				url: "<?=controller_?>main/login",
				items: [{
					xtype: 'textfield',
					id: 'username',
					name: 'username',
					fieldLabel: 'username',
					allowBlank: false,
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								var login_button = Ext.getCmp('login_button');
								login_button.fireEvent('click', login_button);
							}
						}
					}
				}, {
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
				}
				, {
					xtype: 'combo',
					displayField: 'TERMINAL_NAME',
					valueField: 'ID_TERMINAL',
					queryMode: 'local',
//					editable: false,
					store: get_terminal,
					allowBlank: false,
					fieldLabel: 'terminal',
					id: 'terminal',
					name: 'terminal'
				}
				],
				buttons: [{
					text: 'Reset',
					handler: function() {
						this.up('form').getForm().reset();
					}
				},{
					text: 'Login',
					id: 'login_button',
					formBind: true,
					listeners: {
						click: {
							fn: function () {
								var form = this.up('form').getForm();
								if (form.isValid()){
									loadmask.show();
									// form.submit({
										// success: function(form, action) {
											// location.reload();
										// },
										// failure: function(form, action) {
											// loadmask.hide();
											// Ext.Msg.alert('Failed', action.result.errors);
										// }
									// });
									var url = "<?=controller_?>main/login";
									$.post( url, {username: Ext.getCmp('username').getRawValue(), 
										      password: CryptoJS.MD5(Ext.getCmp('password').getRawValue()).toString()
										      ,
										      terminal: Ext.getCmp('terminal').getValue(),
										      terminal_name: Ext.getCmp('terminal').getRawValue()
										      }, function(data) {
										data = JSON.parse(data);
										if (data.success){
											location.reload();
										}else{
											loadmask.hide();
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
		win.show();
	});
	
	var win;
	var loadmask;
</script>