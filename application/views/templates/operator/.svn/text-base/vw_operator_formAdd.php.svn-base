<script type="text/javascript">
Ext.onReady(function(){
	var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
	
	Ext.create('Ext.form.Panel', {
		id: "operator_formadd_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		url: '<?=controller_?>operator/save_operator?tab_id=<?=$tab_id?>',
		items: [{
			xtype: 'container',
			anchor: '100%',
			layout: 'hbox',
			items:[{
				xtype: 'container',
				flex: 1,
				layout: 'anchor',
				items: [{
					xtype:'textfield',
					id: "idoperator_<?=$tab_id?>",
					name: "ID_OPERATOR",
					fieldLabel: 'Id Operator',
					afterLabelTextTpl: required,
					//minLength: 10,
					//maxLength: 10,
					allowBlank: false,
					anchor:'95%'
				}, {
					xtype:'textfield',
					id: "operatorname_<?=$tab_id?>",
					name: "OPERATOR_NAME",
					fieldLabel: 'Operator Name',
					afterLabelTextTpl: required,
					allowBlank: false,
					anchor:'95%'
				}]
			}]
		}],
		buttons: [{
			text: 'Save',
			formBind: true,

			handler: function() {
					Ext.MessageBox.confirm('Confirm', 'Are you sure you want to do that?', showResult);
			}
		}]
	}).render('operatorAdd_<?=$tab_id?>');
	
	function showResult(btn)
	{
	    if(btn=='yes')
	    {		
			var URL = "<?=controller_?>operator/save_operator";
			
			var idoperator = $('#idoperator_<?=$tab_id?>-inputEl').val();

			if(idoperator.length<11){
				Ext.MessageBox.alert('Error','Id Operator Maksimal 10 Karakter');
			}

			$.ajax({
				  type: "POST",
				  url: URL,
				  data: {'ID_OPERATOR' : $('#idoperator_<?=$tab_id?>-inputEl').val(),
						 'OPERATOR_NAME' : $('#operatorname_<?=$tab_id?>-inputEl').val()
						 },
				  success: function (result) {
						if(result.IsSuccess){
							Ext.MessageBox.alert('Sukses', result.Message);
							Ext.getCmp('<?=$tab_id?>').close();
						}else{
							Ext.MessageBox.alert('Error', result.Message);
						}
				  }
			});
	    }
	    else
	    {
	    	Ext.MessageBox.alert('Status', 'Cancel.');
	    }
	}
	
	var backupKeyIdOperator = '';
	var backupChangeKeyOperatorCode = '';
	$('#idoperator_<?=$tab_id?>-inputEl').bind('keyup', function(e){
		if ((e.which >= 65 && e.which <= 90) || e.which == 8) {
			$(this).val($(this).val().toUpperCase());
			backupKeyIdOperator = $(this).val();
		}else{
			$(this).val(backupKeyIdOperator);
		}
		if ($(this).val().length > 10){
			$(this).val($(this).val().substr(0,10));
		}
	});	
	$('#idoperator_<?=$tab_id?>-inputEl').bind('change', function(e){
		if($(this).val().match(/^[a-zA-Z]*$/)){
			backupChangeKeyOperatorCode = $(this).val();
		}else{
			Ext.MessageBox.alert('Error', 'have a illegal character.');
			$(this).val(backupChangeKeyOperatorCode);
		}
		if ($(this).val().length > 10){
			$(this).val($(this).val().substr(0,10));
		}
		backupKeyIdOperator = $(this).val();
	});

	var backupKeyOperatorName = '';
	var backupChangeKeyOperatorName = '';
	$('#operatorname_<?=$tab_id?>-inputEl').bind('keyup', function(e){
		if ((e.which >= 65 && e.which <= 90) || e.which == 8 || e.which == 32 || e.which == 44 || e.which == 57 || e.which == 48|| e.which == 190) {
			$(this).val($(this).val().toUpperCase());
			backupKeyOperatorName = $(this).val();
		}else{
			$(this).val(backupKeyOperatorName);
		}
		if ($(this).val().length > 50){
			$(this).val($(this).val().substr(0,50));
		}
	});	
	$('#operatorname_<?=$tab_id?>-inputEl').bind('change', function(e){
		$(this).val($.trim($(this).val()));
		if($(this).val().match(/^[a-zA-Z/ /(/)/.]*$/)){
			backupChangeKeyOperatorName = $(this).val();
		}else{
			Ext.MessageBox.alert('Error', 'have a illegal character.');
			$(this).val(backupChangeKeyOperatorName);
		}
		if ($(this).val().length > 50){
			$(this).val($(this).val().substr(0,50));
		}
		backupKeyOperatorName = $(this).val();
	});
});
</script>
<div id="operatorAdd_<?=$tab_id?>"></div>