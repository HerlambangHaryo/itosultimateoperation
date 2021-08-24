<script>
$(document).ready(function(){
	$("#btnClosec<?=$tab_id?>").click(function(){
		$('#edit_reqbhc<?=$tab_id?>').dialog('destroy').remove();
		$('#mainform<?=$tab_id?>').append('<div id="edit_reqbhc<?=$tab_id?>"></div>');
	});
	$('#ctOpCTc<?=$tab_id?>').load("<?=controller_?>vessel_service/masterPortCt/"+<?=$id_ves_svc?>+"/<?=$tab_id?>");
});

function addPrtSv<?=$tab_id?>(a){
	var idops=$('#selOpsc<?=$tab_id?>').val();
	var color=$('#colorport<?=$tab_id?>').val();
	var url="<?=controller_?>vessel_service/addOperatorPrt";
	$.post(url,{ID_SERVICE:a,ID_PORT:idops,COLOR:color}, function(data){ 
		alert(data);
		$('#ctOpCTc<?=$tab_id?>').load("<?=controller_?>vessel_service/masterPortCt/"+<?=$id_ves_svc?>+"/<?=$tab_id?>");
	});
}
function delPrtSv<?=$tab_id?>(a,b){
	
	var url="<?=controller_?>vessel_service/delOperatorPrt";
	$.post(url,{ID_SERVICE:a,ID_PORT:b}, function(data){ 
		alert(data);
		$('#ctOpCTc<?=$tab_id?>').load("<?=controller_?>vessel_service/masterPortCt/"+<?=$id_ves_svc?>+"/<?=$tab_id?>");
	});
}
</script>
<div id="ctOpCTc<?=$tab_id?>"></div>
<br>
<hr width="100%" color="#c0c0c0"/>
<input type="button" id="btnClosec<?=$tab_id?>" value="&nbsp;Close&nbsp;"/>