<script>
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Activate Stevedoring Job',
		closable: false,
		items: Ext.create('Ext.form.Panel', {
			bodyPadding: 5,
			layout: 'hbox',
			items: [{
				xtype: 'fieldset',
				title: 'Discharge Job',
				padding: 5,
				margin: 10,
				width: 220,
				items: [{
					xtype: 'button',
					text: 'Activate',
					width: 100,
					margin: '0 5 0 0',
					listeners: {
						click: function(el, e, eOpts){
							loadmask.show();
							Ext.Ajax.request({
								url: '<?=controller_?>activate_stevedoring_job/activate_job/' + 'I',
								params: {
									id_ves_voyage: '<?=$id_ves_voyage?>'
								},
								success: function(response){
									var text = response.responseText;
									if (text=='1'){
										Ext.MessageBox.show({
											title: 'Success',
											msg: 'Job Activated',
											buttons: Ext.MessageBox.OK
										});
									}else{
										Ext.MessageBox.show({
											title: 'Error',
											msg: 'Failed to Activate Job',
											buttons: Ext.MessageBox.OK
										});
									}
									loadmask.hide();
								}
							});
						}
					}
				},{
					xtype: 'button',
					text: 'Deactivate',
					width: 100,
					listeners: {
						click: function(el, e, eOpts){
							loadmask.show();
							Ext.Ajax.request({
								url: '<?=controller_?>activate_stevedoring_job/deactivate_job/' + 'I',
								params: {
									id_ves_voyage: '<?=$id_ves_voyage?>'
								},
								success: function(response){
									var text = response.responseText;
									if (text=='1'){
										Ext.MessageBox.show({
											title: 'Success',
											msg: 'Job Deactivated',
											buttons: Ext.MessageBox.OK
										});
									}else{
										Ext.MessageBox.show({
											title: 'Error',
											msg: 'Failed to Deactivate Job',
											buttons: Ext.MessageBox.OK
										});
									}
									loadmask.hide();
								}
							});
						}
					}
				}]
			},{
				xtype: 'fieldset',
				title: 'Loading Job',
				padding: 5,
				margin: 10,
				width: 220,
				items: [{
					xtype: 'button',
					text: 'Activate',
					width: 100,
					margin: '0 5 0 0',
					listeners: {
						click: function(el, e, eOpts){
							loadmask.show();
							Ext.Ajax.request({
								url: '<?=controller_?>activate_stevedoring_job/activate_job/' + 'E',
								params: {
									id_ves_voyage: '<?=$id_ves_voyage?>'
								},
								success: function(response){
									var text = response.responseText;
									if (text=='1'){
										Ext.MessageBox.show({
											title: 'Success',
											msg: 'Job Activated',
											buttons: Ext.MessageBox.OK
										});
									}else{
										Ext.MessageBox.show({
											title: 'Error',
											msg: 'Failed to Activate Job',
											buttons: Ext.MessageBox.OK
										});
									}
									loadmask.hide();
								}
							});
						}
					}
				},{
					xtype: 'button',
					text: 'Deactivate',
					width: 100,
					listeners: {
						click: function(el, e, eOpts){
							loadmask.show();
							Ext.Ajax.request({
								url: '<?=controller_?>activate_stevedoring_job/deactivate_job/' + 'E',
								params: {
									id_ves_voyage: '<?=$id_ves_voyage?>'
								},
								success: function(response){
									var text = response.responseText;
									if (text=='1'){
										Ext.MessageBox.show({
											title: 'Success',
											msg: 'Job Deactivated',
											buttons: Ext.MessageBox.OK
										});
									}else{
										Ext.MessageBox.show({
											title: 'Error',
											msg: 'Failed to Deactivate Job',
											buttons: Ext.MessageBox.OK
										});
									}
									loadmask.hide();
								}
							});
						}
					}
				}]
			}],
			buttons: [{
				text: 'Cancel',
				handler: function() {
					win.close();
					Ext.getCmp('<?=$tab_id?>').close();
				}
			}]
		})
	});
	win.show();
</script>