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
		url: '<?=controller_?>machine_specification/save_mch?tab_id=<?=$tab_id?>',
		items: [],
		buttons: [{
			text: 'Save',
			formBind: true,

			handler: function() {
			    Ext.MessageBox.confirm('Confirm', 'Are you sure you want to do that?', showResult);
			}
		}]
	}).render('mchAdd_<?=$tab_id?>');
	
	function showResult(btn)
	{
	    if(btn=='yes')
	    {		
		var URL = "<?=controller_?>machine_specification/save_mch";
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
			      data: {'MCH_NAME'     : $('#mch_code_<?=$tab_id?>').val(),
				    'MCH_TYPE'     : $('#mch_type_<?=$tab_id?>').val(),
				    'MCH_SUB_TYPE' : $('#sub_type_<?=$tab_id?>').val(),
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
		
	$('#mch_type_<?=$tab_id?>').change(function(){
	    mch_type_onchange($(this).val());
	});
	
	function mch_type_onchange(val){
	    var htmlSubType = '';
	    if(val == 'QUAY'){
		htmlSubType += '<option value="QC">QC</option>';
		htmlSubType += '<option value="SC">SC</option>';
		htmlSubType += '<option value="HMC">HMC</option>';
		$('#sub_type_<?=$tab_id?>').html(htmlSubType);
		$('#sub_type_<?=$tab_id?>').prop('disabled', false);
		$('#standard_bch_<?=$tab_id?>').prop('disabled', false);
	    }else if(val == 'YARD'){
		htmlSubType += '<option value="RTG">RTG</option>';
		htmlSubType += '<option value="RS">RS</option>';
		htmlSubType += '<option value="SL">SL</option>';
		htmlSubType += '<option value="FL">FL</option>';
		$('#sub_type_<?=$tab_id?>').html(htmlSubType);
		$('#sub_type_<?=$tab_id?>').prop('disabled', false);
		$('#standard_bch_<?=$tab_id?>').val('');
		$('#standard_bch_<?=$tab_id?>').prop('disabled', true);
	    }else if(val == 'ITV'){
		htmlSubType += '<option value="ITV">ITV</option>';
		$('#sub_type_<?=$tab_id?>').html(htmlSubType);
		$('#sub_type_<?=$tab_id?>').prop('disabled', false);
		$('#standard_bch_<?=$tab_id?>').prop('disabled', false);
	    }else{
		$('#sub_type_<?=$tab_id?>').val('');
		$('#sub_type_<?=$tab_id?>').prop('disabled', true);
		$('#standard_bch_<?=$tab_id?>').val('');
		$('#standard_bch_<?=$tab_id?>').prop('disabled', true);
	    }
	}
	mch_type_onchange($('#mch_type_<?=$tab_id?>').val());
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
		<td>Code</td>
		<td> : </td>
		<td><input id="mch_code_<?=$tab_id?>" type="text" class="x-form-text text_id" required>
		<span style="color:red;font-weight:bold" data-qtip="Required">*</span> </td>
	</tr>
	<tr>
		<td>Type</td>
		<td> : </td>
		<td>
		    <select id="mch_type_<?=$tab_id?>">
			<option value="ITV">ITV</option>
			<option value="QUAY">QUAY</option>
			<option value="YARD">YARD</option>
		    </select>
		    <span style="color:red;font-weight:bold" data-qtip="Required">*</span> 
		</td>
	</tr>
	<tr>
		<td>Sub Type</td>
		<td> : </td>
		<td>
		    <select id="sub_type_<?=$tab_id?>">
			<option value=""></option>
			<option value="ITV">ITV</option>
			<option value="RTG">RTG</option>
			<option value="QC">QC</option>
			<option value="RS">RS</option>
		    </select>
		</td>
	</tr>
	<tr>
		<td>Size Chassis</td>
		<td> : </td>
		<td>
		    <select id="size_chassis_<?=$tab_id?>">
			<option value=""></option>
			<option value="20">20</option>
			<option value="40">40</option>
			<option value="45">45</option>
		    </select>
		</td>
	</tr>
	<tr>
		<td>Standard BCH</td>
		<td> : </td>
		<td>
		    <input id="standard_bch_<?=$tab_id?>" type="number" class="x-form-text text_id" required>
		</td>
	</tr>
	<tr>
		<td>Background Color</td>
		<td> : </td>
		<td>
		    <input type="color" id="bg_<?=$tab_id?>" class="x-form-text">
		    <span style="color:red;font-weight:bold" data-qtip="Required">*</span> 
		</td>
	</tr>
</table>
</div>
<div id="mchAdd_<?=$tab_id?>"></div>