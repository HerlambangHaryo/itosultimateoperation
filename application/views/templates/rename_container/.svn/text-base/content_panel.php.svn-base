<?php
?>
<style>
.fieldNya{
	border:1px solid #abadb3;
}
.colorNya{
	background-color:#f7f6f6;
}
</style>
<script>
	function funSaveRename<?=$tab_id?>()
	{
		var v_oldcont=$('#oldCont<?=$tab_id?>').val();
		var v_newcont=$('#newCont<?=$tab_id?>').val();
		var v_classPoint=$('#classPoint<?=$tab_id?>').val();
		var url='<?=controller_?>rename_container/saveRename';
		$.post(url,{OLDCONT:v_oldcont, NEWCONT: v_newcont, CLASSPOINT:v_classPoint}, function(data){
			if(data=='1')
			{
				alert("success");
				resetRename();
			}
			else if (data=='0')
			{
				alert("failed");
			}else {
				alert("failed, "+data);
			}
		});
	}
</script>

<div>

<table>
<tr><td valign="top"><fieldset class="fieldNya"><legend>Common</legend>
	<table>
		<tr><td>Old Container Number</td>
			<td><input type="text" id="oldCont<?=$tab_id?>" name="oldCont<?=$tab_id?>" value="<?=$containerSrc;?>" readonly /></td>
		</tr>
		<tr>
			<td>Point</td>
			<td><input type="text" id="classPoint<?=$tab_id?>" name="classPoint<?=$tab_id?>" value="<?=$rowsDetail[0]['POINT'];?>" readonly /></td>
		</tr>
		<tr>
			<td>Class</td>
			<td><input type="text" id="classCont<?=$tab_id?>" name="classCont<?=$tab_id?>" value="<?=$rowsDetail[0]['ID_CLASS_CODE'];?>" readonly /></td>
		</tr>
		<tr>
			<td>Full/Empty</td>
			<td><input type="text" id="fullEmpty<?=$tab_id?>" name="fullEmpty<?=$tab_id?>" value="<?=$rowsDetail[0]['CONT_STATUS'];?>" readonly /></td>
		</tr>
		<tr>
			<td>ISO/Type/Size/Height</td>
			<td><input type="text" id="iso<?=$tab_id?>" name="iso<?=$tab_id?>" size="5" value="<?=$rowsDetail[0]['ID_ISO_CODE'];?>" readonly /> <input type="text" id="type<?=$tab_id?>" name="type<?=$tab_id?>" size="5" value="<?=$rowsDetail[0]['CONT_TYPE'];?>" readonly /> <input type="text" id="size<?=$tab_id?>" name="size<?=$tab_id?>" size="2" value="<?=$rowsDetail[0]['CONT_SIZE'];?>" readonly /> <input type="text" id="height<?=$tab_id?>" name="height<?=$tab_id?>" size="2" value="<?=$rowsDetail[0]['CONT_HEIGHT'];?>" readonly /></td>
		</tr>
		<tr>
			<td>Operator</td>
			<td><input type="text" id="operator<?=$tab_id?>" name="operator<?=$tab_id?>" value="<?=$rowsDetail[0]['ID_OPERATOR'];?>" readonly /></td>
		</tr>
		<tr>
			<td>Weight</td>
			<td><input type="text" id="weightCont<?=$tab_id?>" name="weightCont<?=$tab_id?>" value="<?=$rowsDetail[0]['WEIGHT'];?>" readonly /></td>
		</tr>
	</table>
	</fieldset>
	</td>
	<td valign="top"><fieldset class="fieldNya">
	<legend>Vessel Voyage</legend>
	<table>
		<tr>
			<td>Id Ves Voyage</td>
			<td><input type="text" id="ukks<?=$tab_id?>" name="ukks<?=$tab_id?>" value="<?=$rowsDetail[0]['ID_VES_VOYAGE'];?>" readonly  /></td>
		</tr>
		<tr>
			<td>Vessel</td>
			<td><input type="text" id="vesCont<?=$tab_id?>" name="vesCont<?=$tab_id?>" value="<?=$rowsDetail[0]['VESSEL_NAME'];?>" readonly  /></td>
		</tr>
		<tr>
			<td>Voyage</td>
			<td><input type="text" id="voyIn<?=$tab_id?>" name="voyIn<?=$tab_id?>" size="10" value="<?=$rowsDetail[0]['VOY_IN'];?>" readonly /> <input type="text" id="voyOut<?=$tab_id?>" name="voyOut<?=$tab_id?>" size="10" value="<?=$rowsDetail[0]['VOY_OUT'];?>" readonly /></td>
		</tr>
		<tr>
			<td>ATB</td>
			<td><input type="text" id="atb<?=$tab_id?>" name="atb<?=$tab_id?>" value="<?=$rowsDetail[0]['ATB'];?>" readonly /> </td>
		</tr>
		<tr>
			<td>ATD</td>
			<td><input type="text" id="atd<?=$tab_id?>" name="atd<?=$tab_id?>" value="<?=$rowsDetail[0]['ATD'];?>" readonly /></td>
		</tr>
	</table>
	</fieldset>
		<table>
			<tr>
			<td>New Container Number</td>
		<td><input type="text" id="newCont<?=$tab_id?>" name="newCont<?=$tab_id?>" /></td>
		<td><button onclick="funSaveRename<?=$tab_id?>()">Save</button></td>
		</tr>
		</table>
	</td>
	<td></td>
</tr>
</table>

</div>