<script>
$(document).ready(function(){
	$("#btnClose<?=$tab_id?>").click(function(){
		$('#edit_reqbh<?=$tab_id?>').dialog('destroy').remove();
		$('#mainform<?=$tab_id?>').append('<div id="edit_reqbh<?=$tab_id?>"></div>');
	});
	$('#ctOpCT<?=$tab_id?>').load("<?=controller_?>vessel_service/masterOperatorCt/"+<?=$id_ves_svc?>+"/<?=$tab_id?>");
});
function addOpr<?=$tab_id?>(a){
	var idops=$('#selOps<?=$tab_id?>').val();
	var url="<?=controller_?>vessel_service/addOperatorCt";
	$.post(url,{ID_SERVICE:a,ID_OPERATOR:idops}, function(data){ 
		alert(data);
		$('#ctOpCT<?=$tab_id?>').load("<?=controller_?>vessel_service/masterOperatorCt/"+<?=$id_ves_svc?>+"/<?=$tab_id?>");
	});
}
function delOpr<?=$tab_id?>(a,b){
	
	var url="<?=controller_?>vessel_service/delOperatorCt";
	$.post(url,{ID_SERVICE:a,ID_OPERATOR:b}, function(data){ 
		alert(data);
		$('#ctOpCT<?=$tab_id?>').load("<?=controller_?>vessel_service/masterOperatorCt/"+<?=$id_ves_svc?>+"/<?=$tab_id?>");
	});
}
</script>
<div id="ctOpCT<?=$tab_id?>"></div>
<br>
<hr width="100%" color="#c0c0c0"/>
<input type="button" id="btnClose<?=$tab_id?>" value="&nbsp;Close&nbsp;"/>