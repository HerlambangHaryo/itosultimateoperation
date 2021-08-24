<script>
function cbstD<?=$tab_id?>()
{
	var laneName=$('#servNameLane<?=$tab_id?>').val();
		
	var url="<?=controller_?>vessel_service/addServiceLaneSave";
	$.post(url,{SERVNAME:laneName}, function(data){ 
		alert(data);
		$('#vesAddSvc<?=$tab_id?>').dialog('destroy').remove();
		$('#mainformsVc<?=$tab_id?>').append('<div id="vesAddSvc<?=$tab_id?>"></div>');
		var vesIq="null";
		$('#loadContentVesServ<?=$tab_id?>').load('<?=controller_?>vessel_service/loadContents/<?=$tab_id?>',{name:vesIq});
	});
}

function cbstC<?=$tab_id?>()
{
	$('#vesAddSvc<?=$tab_id?>').dialog('destroy').remove();
	$('#mainformsVc<?=$tab_id?>').append('<div id="vesAddSvc<?=$tab_id?>"></div>');
	var vesIq="null";
	$('#loadContentVesServ<?=$tab_id?>').load('<?=controller_?>vessel_service/loadContents/<?=$tab_id?>',{name:vesIq});
}
</script>
<table>
<tr>
	<td>Service Lane (Name)</td>
	<td><input type="text" id="servNameLane<?=$tab_id?>" name="servNameLane<?=$tab_id?>" size="20"/></td>
</tr>
</table>
<input type="button" id="btnCloseMcs<?=$tab_id?>" ONCLICK="cbstD<?=$tab_id?>()" value="&nbsp;Save&nbsp;"/>
<input type="button" id="btnCloseMcsdc<?=$tab_id?>" ONCLICK="cbstC<?=$tab_id?>()" value="&nbsp;Close&nbsp;"/>