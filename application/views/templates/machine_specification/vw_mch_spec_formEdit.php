<!-- // Kapal <?=$PORT_CODE?> -->


<script type="text/javascript">
//function update(jscolor) {
    // 'jscolor' instance can be used as a string
    //document.getElementById('background_<?=$tab_id?>').style.backgroundColor = '#' + jscolor
//}

Ext.onReady(function(){
	Ext.create('Ext.form.Panel', {
		id: "mch_spec_formadd_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		url: '<?=controller_?>machine_specification/edit_mch?tab_id=<?=$tab_id?>',
		items: [],
		buttons: [{
			text: 'Edit',
			formBind: true,

			handler: function() {
			    Ext.MessageBox.confirm('Confirm', 'Are you sure you want to do that?', showResult);
			}
		}]
	}).render('mchEdit_<?=$tab_id?>');
	
	function showResult(btn)
	{
	    if(btn=='yes')
	    {		
		var URL = "<?=controller_?>machine_specification/edit_mch";
		if($('#mch_type_<?=$tab_id?>').val() == 'QUAY' && $('#standard_bch_<?=$tab_id?>').val() == ''){
		    alert('Choose Standard BCH if type is QUAY');
		}else if($('#mch_type_<?=$tab_id?>').val() == 'QUAY' && $('#sub_type_<?=$tab_id?>').val() == ''){
		    alert("Sub Type must be fill for QUAY");
		}else if($('#mch_type_<?=$tab_id?>').val() == 'YARD' && $('#sub_type_<?=$tab_id?>').val() == ''){
		    alert("Sub Type must be fill for YARD");
		}else{
		    $.ajax({
			      type: "POST",
			      url: URL,
			      data: {'ID_MACHINE'     : $('#id_mch_<?=$tab_id?>').val(),
				    'MCH_NAME'     : $('#mch_code_<?=$tab_id?>').val(),
//				    'MCH_TYPE'     : $('#mch_type_<?=$tab_id?>').val(),
//				    'MCH_SUB_TYPE' : $('#sub_type_<?=$tab_id?>').val(),
				    'SIZE_CHASSIS' : $('#size_chassis_<?=$tab_id?>').val(),
				    'STANDARD_BCH' 	: $('#standard_bch_<?=$tab_id?>').val(),
				    'BG_COLOR' 	: $('#bg_<?=$tab_id?>').val()
					     },
			      success: function (result) {
				    if(result.IsSuccess){
					Ext.MessageBox.alert('Sukses', result.Message);
					mch_spec_store.reload();
					Ext.getCmp('<?=$tab_id?>').close();
					    //window.location.reload();
				    }else{
					Ext.MessageBox.alert('Error', result.Message);
				    }
			      }
		    });
		}
	    }
	    else
	    {
	    	Ext.MessageBox.alert('Status', 'Cancel.');
	    }
	}
	
	var backupKeyPortCode = '';
	var backupChangeKeyPortCode = '';
	$('#mch_code_<?=$tab_id?>').bind('keyup', function(e){
//		alert(e.which);
		if ((e.which >= 65 && e.which <= 90) || e.which == 8 || e.which >= 48 && e.which <= 57) {
			$(this).val($(this).val().toUpperCase());
			backupKeyPortCode = $(this).val();
		}else{
			$(this).val(backupKeyPortCode);
		}
	});
	
	
	
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
    <input id="id_mch_<?=$tab_id?>" type="hidden" value="<?=$machine['ID_MACHINE']?>" required>
    <table cellpadding="2" cellspacing="2">
	<tr>
		<td>Code</td>
		<td> : </td>
		<td><input id="mch_code_<?=$tab_id?>" type="text" class="x-form-text text_id" value="<?=$machine['MCH_NAME']?>" readonly required>
		<span style="color:red;font-weight:bold" data-qtip="Required">*</span> </td>
	</tr>
	<tr>
		<td>Type</td>
		<td> : </td>
		<td>
		    <select id="mch_type_<?=$tab_id?>" disabled >
			<option value="ITV" <?php if($machine['MCH_TYPE'] == 'ITV'){ ?> selected <?php } ?>>ITV</option>
			<option value="QUAY" <?php if($machine['MCH_TYPE'] == 'QUAY'){ ?> selected <?php } ?>>QUAY</option>
			<option value="YARD" <?php if($machine['MCH_TYPE'] == 'YARD'){ ?> selected <?php } ?>>YARD</option>
		    </select>
		    <span style="color:red;font-weight:bold" data-qtip="Required">*</span> 
		</td>
	</tr>
	<tr>
		<td>Sub Type</td>
		<td> : </td>
		<td>
		    <select id="sub_type_<?=$tab_id?>" disabled >
			<option value="" <?php if($machine['MCH_SUB_TYPE'] == ''){ ?> selected <?php } ?>></option>
			<option value="RTG" <?php if($machine['MCH_SUB_TYPE'] == 'RTG'){ ?> selected <?php } ?>>RTG</option>
			<option value="QC" <?php if($machine['MCH_SUB_TYPE'] == 'QC'){ ?> selected <?php } ?>>QC</option>
			<option value="RS" <?php if($machine['MCH_SUB_TYPE'] == 'RS'){ ?> selected <?php } ?>>RS</option>
		    </select>
		</td>
	</tr>
	<tr>
		<td>Size Chassis</td>
		<td> : </td>
		<td>
		    <select id="size_chassis_<?=$tab_id?>">
			<option value="" <?php if($machine['SIZE_CHASSIS'] == ''){ ?> selected <?php } ?>></option>
			<option value="20" <?php if($machine['SIZE_CHASSIS'] == '20'){ ?> selected <?php } ?>>20</option>
			<option value="40" <?php if($machine['SIZE_CHASSIS'] == '40'){ ?> selected <?php } ?>>40</option>
			<option value="45" <?php if($machine['SIZE_CHASSIS'] == '45'){ ?> selected <?php } ?>>45</option>
		    </select>
		</td>
	</tr>
	<tr>
		<td>Standard BCH</td>
		<td> : </td>
		<td>
		    <input id="standard_bch_<?=$tab_id?>" type="number" class="x-form-text text_id" value="<?=$machine['STANDARD_BCH']?>" required>
		</td>
	</tr>
	<tr>
		<td>Background Color</td>
		<td> : </td>
		<td>
		    <input type="color" id="bg_<?=$tab_id?>" class="x-form-text" value="<?=$machine['BG_COLOR']?>">
		    <span style="color:red;font-weight:bold" data-qtip="Required">*</span> 
		</td>
	</tr>
    </table>
</div>
<div id="mchEdit_<?=$tab_id?>"></div>