<script>
	$(function() {
		$("#list_vessel_<?=$tab_id?>").change(function() {
			loadmask.show();
			Ext.getCmp("<?=$tab_id?>").getLoader().load({
				url: '<?=controller_?>vessel_profile?tab_id=<?=$tab_id?>&ves_code='+$(this).val(),
				scripts: true,
				contentType: 'html',
				autoLoad: true,
				success: function(){
					loadmask.hide();
				}
			});
		});
	});

	$('#mb1_<?=$tab_id?>').click(function(e){
	    Ext.MessageBox.confirm('Confirm', 'Are you sure you want to do that?', showResult);
	});
	
	function showResult(btn)
	{
	    if(btn=='yes')
	    {			
		    var url = "<?=controller_?>vessel_profile/reset_vessel_profile";

			$.post(url,{VSCD:$('#vescode_<?=$tab_id?>').val(),
						IDUSER:$('#iduser_<?=$tab_id?>').val()},	
		    function(data){	
		    // alert(data);
			var v_msg = data;
			if(v_msg!='OK')
			{
				Ext.MessageBox.alert('Status', 'Failed.');
				return false;
			}
			else
			{
				Ext.MessageBox.alert('Status', 'Changes saved successfully.');
				Ext.getCmp('<?=$tab_id?>').close();
			}
			});	  
	    }
	    else
	    {
	    	Ext.MessageBox.alert('Status', 'Cancel.');
	    }
	}
//}
</script>

<div id="yard_viewer_header_<?=$tab_id?>">
	<table>
		<tr>
			<td>
				Vessel Code :
			</td>
			<td>
				<select id="list_vessel_<?=$tab_id?>" name="list_vessel">
					<option value="">--Choose--</option>
					<?php
					foreach ($vessel_code as $option){
					?>
						<option value="<?=$option['ID_VESSEL']?>" <?php if ($vs_code==$option['ID_VESSEL']) {?> selected <?php }?> ><?=$option['ID_VESSEL']?> - <?=$option['VESSEL_NAME']; ?></option>
					<?php
					}
					?>
				</select>
			</td>
			<? if ($flag_profile=='Y') { ?>
			<td>
				<input id='vescode_<?=$tab_id?>' name='vescode_<?=$tab_id?>' value='<?=$vs_code?>' type='text' hidden='hidden'/>
				<input id='iduser_<?=$tab_id?>' name='iduser_<?=$tab_id?>' value='<?=$id_user?>' type='text' hidden='hidden'/>
				
				&nbsp;&nbsp;&nbsp;<button type="button" id="mb1_<?=$tab_id?>">Reset</button>
			</td>
			<? } ?>
		</tr>
	</table>
</div>
<hr/>
<div id="yard_viewer_content_<?=$tab_id?>">
</div>