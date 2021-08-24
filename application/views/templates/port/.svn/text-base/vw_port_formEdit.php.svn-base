<!-- // Kapal <?=$PORT_CODE?> -->
<!-- <script type="text/javascript" src="<?=JS_?>jscolor.js"></script> -->

<script type="text/javascript">
Ext.onReady(function(){
	var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';

	
	Ext.create('Ext.form.Panel', {
		id: "port_formedit_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		url: '<?=controller_?>port/edit_port?tab_id=<?=$tab_id?>',
		items: [],
		buttons: [{
			text: 'Save',
			formBind: true,

			handler: function() {
					Ext.MessageBox.confirm('Confirm', 'Are you sure you want to do that?', showResult);
			}
		}]
	}).render('portEdit_<?=$tab_id?>');
	
	function showResult(btn)
	{
	    if(btn=='yes')
	    {		
			var URL = "<?=controller_?>port/edit_port";

			var background = $('#background_<?=$tab_id?>').val();
			var foreground = $('#foreground_<?=$tab_id?>').val();

			var res_background = background.replace("#", "");
			var res_foreground = foreground.replace("#", "");

			/*console.log('BACKGROUND_COLOR'+res_background);
			console.log('FOREGROUND_COLOR'+res_foreground);
			return false;*/
			
			$.ajax({
				  type: "POST",
				  url: URL,
				  data: {'PORT_CODE' 		: $('#portcode_<?=$tab_id?>').val(),
						 'PORT_NAME' 		: $('#portname_<?=$tab_id?>').val(),
						 'BACKGROUND_COLOR' : $('#background_<?=$tab_id?>').val(),
						 'FOREGROUND_COLOR' : $('#foreground_<?=$tab_id?>').val(),
						 'IS_ACTIVE' 		: $('#is_active_<?=$tab_id?>').val()
						 },
				  success: function (result) {
						if(result.IsSuccess){
							Ext.MessageBox.alert('Sukses', result.Message);
							Ext.getCmp('<?=$tab_id?>').close();
							//window.location.reload();
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
	
	var backupKeyPortCode = '';
	var backupChangeKeyPortCode = '';
	$('#portcode_<?=$tab_id?>').bind('keyup', function(e){
		if ((e.which >= 65 && e.which <= 90) || e.which == 8) {
			$(this).val($(this).val().toUpperCase());
			backupKeyPortCode = $(this).val();
		}else{
			$(this).val(backupKeyPortCode);
		}
		if ($(this).val().length > 6){
			$(this).val($(this).val().substr(0,6));
		}
	});	
	$('#portcode_<?=$tab_id?>').bind('change', function(e){
		if($(this).val().match(/^[a-zA-Z]*$/)){
			backupChangeKeyPortCode = $(this).val();
		}else{
			Ext.MessageBox.alert('Error', 'have a illegal character.');
			$(this).val(backupChangeKeyPortCode);
		}
		if ($(this).val().length > 6){
			$(this).val($(this).val().substr(0,6));
		}
		backupKeyPortCode = $(this).val();
	});

	/*var backupKeyPortName = '';
	var backupChangeKeyPortName = '';
	$('#portname_<?=$tab_id?>').bind('keyup', function(e){
		if ((e.which >= 65 && e.which <= 90) || e.which == 8 || e.which == 32 || e.which == 44) {
			$(this).val($(this).val().toUpperCase());
			backupKeyPortName = $(this).val();
		}else{
			$(this).val(backupKeyPortName);
		}
		if ($(this).val().length > 50){
			$(this).val($(this).val().substr(0,50));
		}
	});	
	$('#portname_<?=$tab_id?>').bind('change', function(e){
		$(this).val($.trim($(this).val()));
		if($(this).val().match(/^[a-zA-Z/ ]*$/)){
			backupChangeKeyPortName = $(this).val();
		}else{
			Ext.MessageBox.alert('Error', 'have a illegal character.');
			$(this).val(backupChangeKeyPortName);
		}
		if ($(this).val().length > 50){
			$(this).val($(this).val().substr(0,50));
		}
		backupKeyPortName = $(this).val();
	});
	*/
	$('#portcode_<?=$tab_id?>').before("ID ");
	$('#portcode_<?=$tab_id?>').attr("style", "width: 80%");
});
</script>
<style type="text/css">
	.x-form-text{
		width: 250px !important;
	}
	.text_id{
		width: 234px !important;
	}
</style>
<div class="row">
<table cellpadding="2" cellspacing="2">
	<tr>
		<td>Port Code</td>
		<td> : </td>
		<td><input id="portcode_<?=$tab_id?>" type="text" class="x-form-text text_id" required value="<?=$port->PORT_CODE?>">
		<span style="color:red;font-weight:bold" data-qtip="Required">*</span> </td>
	</tr>
	<tr>
		<td>Port Name</td>
		<td> : </td>
		<td><input type="text" id="portname_<?=$tab_id?>" class="x-form-text" required value="<?=$port->PORT_NAME?>">
		<span style="color:red;font-weight:bold" data-qtip="Required">*</span> </td>
	</tr>
	<tr>
		<td>Foreground Color</td>
		<td> : </td>
		<td>
			<!-- <input type="text" id="background_<?=$tab_id?>" class="jscolor x-form-text" required value="<?=$port->FOREGROUND_COLOR?>"> -->
			<input type="color" id="foreground_<?=$tab_id?>" class="x-form-text" value="#<?=$port->FOREGROUND_COLOR?>">
		<span style="color:red;font-weight:bold" data-qtip="Required">*</span> </td>
	</tr>
	<tr>
		<td>Background Color</td>
		<td> : </td>
		<td>
			<!-- <input type="text" id="foreground_<?=$tab_id?>" class="jscolor x-form-text" required value="<?=$port->FOREGROUND_COLOR?>"> -->
			<input type="color" id="background_<?=$tab_id?>" class="x-form-text" value="#<?=$port->BACKGROUND_COLOR?>">
		<span style="color:red;font-weight:bold" data-qtip="Required">*</span> </td>
	</tr>
	<tr>
		<td>Is Active</td>
		<td> : </td>
		<td>
			<select id="is_active_<?=$tab_id?>" class="x-form-text">
				<option value="Y" <?=($port->IS_ACTIVE=='Y') ? 'selected' : '';?> >Y</option>
				<option value="N" <?=($port->IS_ACTIVE=='N') ? 'selected' : '';?>>N</option>
			</select>
		</td>
	</tr>
</table>
</div>
<div id="portEdit_<?=$tab_id?>"></div>