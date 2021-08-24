<?php
//echo 'Ini menu rename container';
?>
<style>
.tabMainPanelVesServ1{

	background: linear-gradient(to top, #fcfbfb , #f7f6f6);
	box-shadow:
		  0 1px 2px #fff, /*bottom external highlight*/
		  0 -1px 1px #9c9da2, /*top external shadow*/
		  inset 0 -1px 1px rgba(0,0,0,0.1), /*bottom internal shadow*/
		  inset 0 1px 1px rgba(255,255,255,0.8); /*top internal highlight*/
}

</style>


<script>
var vesIq='';

$(document).ready(function(){
		var params = {
			name: $('#vesServ<?=$tab_id?>').val(),
			field: $('#field<?=$tab_id?>').val()
		} ;
		$('#loadContentVesServ<?=$tab_id?>').load('<?=controller_?>vessel_service/loadContents/<?=$tab_id?>', params);
	});
function vesInquiry<?=$tab_id?>(){
	var params = {
		name: $('#vesServ<?=$tab_id?>').val(),
		field: $('#field<?=$tab_id?>').val()
	};
	$('#loadContentVesServ<?=$tab_id?>').load('<?=controller_?>vessel_service/loadContents/<?=$tab_id?>', params);
}

function vesSvcAdd<?=$tab_id?>(){
	$('#vesAddSvc<?=$tab_id?>').load("<?=controller_?>vessel_service/AddServiceLane/<?=$tab_id?>").dialog({closeOnEscape: false, modal:true, height:150,width:300, title : "Master Service Lane", open: function(event,ui){$(".ui-dialog-titlebar-close",ui.dialog).hide();}});
}
</script>
<div class="tabMainPanelVesServ1">
    <table>
	    <tr>
		    <td>
			    <select name="field" id="field<?=$tab_id?>">
				    <option value="name">Vessel Service (Name)</option>
				    <option value="operator">Operator</option>
			    </select>
		    </td>
		    <td>:</td>
		    <td><input type="text" id="vesServ<?=$tab_id?>" name="vesServ<?=$tab_id?>"/></td>
		    <td><div onclick="vesInquiry<?=$tab_id?>()" class="inquirySenter"><img src="images/flashlight.png" title="inquiry" width="18"/></div></td>
		    <td><div onclick="vesSvcAdd<?=$tab_id?>()" class="inquirySenter"><img src="images/plus.png" title="inquiry" width="18"/></div></td>
	    </tr>
    </table>
</div>
<div id="loadContentVesServ<?=$tab_id?>" width="100%"></div>
<form id="mainformsVc<?=$tab_id?>">
<div id="vesAddSvc<?=$tab_id?>"></div>
</form>
