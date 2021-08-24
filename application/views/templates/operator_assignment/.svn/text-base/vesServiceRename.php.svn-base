<script>
function cbstrn<?=$tab_id?>()
{
	var laneName=$('#servNameLaneNew<?=$tab_id?>').val();
	var laneNameRetype=$('#servNameLaneRetype<?=$tab_id?>').val();
	var id=$('#servNameIde<?=$tab_id?>').val();
	
	if (laneName!=laneNameRetype)
	{
		alert("Lane name doesn't match");
	}
	else
	{
		var url="<?=controller_?>vessel_service/renameServiceLaneSave";
		$.post(url,{SERVNAME:laneName,ID:id}, function(data){ 
			alert(data);
			$('#vesRnmSvc<?=$tab_id?>').dialog('destroy').remove();
			$('#mainform<?=$tab_id?>').append('<div id="vesRnmSvc<?=$tab_id?>"></div>');
			var vesIq="null";
			$('#loadContentVesServ<?=$tab_id?>').load('<?=controller_?>vessel_service/loadContents/<?=$tab_id?>',{name:vesIq});
		});
	}
}

function cbstCl<?=$tab_id?>()
{
	$('#vesRnmSvc<?=$tab_id?>').dialog('destroy').remove();
	$('#mainform<?=$tab_id?>').append('<div id="vesRnmSvc<?=$tab_id?>"></div>');
	var vesIq="null";
	$('#loadContentVesServ<?=$tab_id?>').load('<?=controller_?>vessel_service/loadContents/<?=$tab_id?>',{name:vesIq});
}
</script>
<table>
<tr>
	<td>ID Service Lane</td>
	<td><input type="readonly" id="servNameIde<?=$tab_id?>" name="servNameIde<?=$tab_id?>" size="20" value="<?=$id_service;?>"/></td>
</tr>
<tr>
	<td>Service Lane (Name) - Old</td>
	<td><input type="readonly" id="servNameLaneOld<?=$tab_id?>" name="servNameLaneOld<?=$tab_id?>" size="20" value="<?=$service_name;?>"/></td>
</tr>
<tr>
	<td>New Service Lane (Name)</td>
	<td><input type="text" id="servNameLaneNew<?=$tab_id?>" name="servNameLaneNew<?=$tab_id?>" size="20" value=""/></td>
</tr>
<tr>
	<td>Retype New Service Lane (Name)</td>
	<td><input type="password" id="servNameLaneRetype<?=$tab_id?>" name="servNameLaneRetype<?=$tab_id?>" size="20" value=""/></td>
</tr>
</table>
<input type="button" id="btnSaveSvRn<?=$tab_id?>" ONCLICK="cbstrn<?=$tab_id?>()" value="&nbsp;Save&nbsp;"/>
<input type="button" id="btnCloseSvRn<?=$tab_id?>" ONCLICK="cbstCl<?=$tab_id?>()" value="&nbsp;Close&nbsp;"/>