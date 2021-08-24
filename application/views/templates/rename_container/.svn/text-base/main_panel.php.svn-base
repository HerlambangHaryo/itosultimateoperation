<?php
//echo 'Ini menu rename container';
?>
<style>
.tabMainPanel1{

	background: linear-gradient(to top, #fcfbfb , #f7f6f6);
	box-shadow: 
		  0 1px 2px #fff, /*bottom external highlight*/
		  0 -1px 1px #9c9da2, /*top external shadow*/ 
		  inset 0 -1px 1px rgba(0,0,0,0.1), /*bottom internal shadow*/ 
		  inset 0 1px 1px rgba(255,255,255,0.8); /*top internal highlight*/
}

</style>
<script>
var contIq="null";

$(document).ready(function(){
		$('#loadContent<?=$tab_id?>').load('<?=controller_?>rename_container/loadContents/'+contIq+'/<?=$tab_id?>');
	});
function contInquiry(){
	contIq=$('#numbCont<?=$tab_id?>').val();
	if (contIq=='')
	{
		contIq="null";
	}
	$('#loadContent<?=$tab_id?>').load('<?=controller_?>rename_container/loadContents/'+contIq+'/<?=$tab_id?>');
}
function resetRename()
{
	var contIq="null";
	$('#loadContent<?=$tab_id?>').load('<?=controller_?>rename_container/loadContents/'+contIq+'/<?=$tab_id?>');
}
</script>
<div class="tabMainPanel1" id="PanelRename">
<table class="margPanel1">
	<tr >
		<td>Container Number</td>
		<td>:</td>
		<td><input type="text" id="numbCont<?=$tab_id?>" name="numbCont<?=$tab_id?>"/></td>
		<td><div onclick="contInquiry()" class="inquirySenter"><img src="images/flashlight.png" title="inquiry" width="18"/></div></td>
	</tr>
</table>
</div>
<div id="loadContent<?=$tab_id?>" width="100%"></div>