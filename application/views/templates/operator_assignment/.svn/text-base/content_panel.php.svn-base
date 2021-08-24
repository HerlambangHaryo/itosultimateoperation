<?php
?>
<style>
.fieldNya{
	border:1px solid #abadb3;
}
.colorNya{
	background-color:#f7f6f6;
}
.tabMainTabelVesServ1{

	background: linear-gradient(to top, #fcfbfb , #f7f6f6);
	
	box-shadow: 
		  0 1px 2px #fff, /*bottom external highlight*/
		  0 -1px 1px #9c9da2, /*top external shadow*/ 
		  inset 0 -1px 1px rgba(0,0,0,0.1), /*bottom internal shadow*/ 
		  inset 0 1px 1px rgba(255,255,255,0.8); /*top internal highlight*/
	color:#999999;
	font-weight: bold;
}
.tdBorderVesServ{
	border-bottom:1px solid #c0c0c0;
	border-right:1px solid #c0c0c0;
}
.tdBorderVesServ2{
	background-color:#43a9f2;
	font-family: 'Open Sans', sans-serif;
	font-size:10px;
	box-shadow: 
		  0 1px 2px #fff, /*bottom external highlight*/
		  0 -1px 1px #9c9da2, /*top external shadow*/ 
		  inset 0 -1px 1px rgba(0,0,0,0.1), /*bottom internal shadow*/ 
		  inset 0 1px 1px rgba(255,255,255,0.8); /*top internal highlight*/
	color:#f8f8f8;
	border-bottom:1px solid #f8f8f8;
	border-right:1px solid #f8f8f8;
	
}

.tdBorderVesServ3{
	background: linear-gradient(to top, #fcfbfb , #f7f6f6);
	font-family: 'Open Sans', sans-serif;
	font-size:10px;
	box-shadow: 
		  0 1px 2px #fff, /*bottom external highlight*/
		  0 -1px 1px #9c9da2, /*top external shadow*/ 
		  inset 0 -1px 1px rgba(0,0,0,0.1), /*bottom internal shadow*/ 
		  inset 0 1px 1px rgba(255,255,255,0.8); /*top internal highlight*/
	color:#f8f8f8;
	border-bottom:1px solid #f8f8f8;
	border-right:1px solid #f8f8f8;
	
}
.oddrowcolor{
	background-color:#ffffff;
	border-bottom:1px solid #dbdbdb;
	
}
.evenrowcolor{
	background-color:#ededed;
	border-bottom:1px solid #dbdbdb;
	
}
.a_demo_one {
	background-color:#3bb3e0;
	padding:5px;
	position:relative;
	font-family: 'Open Sans', sans-serif;
	font-size:8px;
	text-decoration:none;
	color:#fff;
	border: solid 1px #186f8f;
	background-image: linear-gradient(bottom, rgb(44,160,202) 0%, rgb(62,184,229) 100%);
	background-image: -o-linear-gradient(bottom, rgb(44,160,202) 0%, rgb(62,184,229) 100%);
	background-image: -moz-linear-gradient(bottom, rgb(44,160,202) 0%, rgb(62,184,229) 100%);
	background-image: -webkit-linear-gradient(bottom, rgb(44,160,202) 0%, rgb(62,184,229) 100%);
	background-image: -ms-linear-gradient(bottom, rgb(44,160,202) 0%, rgb(62,184,229) 100%);
	background-image: -webkit-gradient(
	linear,
	left bottom,
	left top,
	color-stop(0, rgb(44,160,202)),
	color-stop(1, rgb(62,184,229))
	);
	-webkit-box-shadow: inset 0px 1px 0px #7fd2f1, 0px 1px 0px #fff;
	-moz-box-shadow: inset 0px 1px 0px #7fd2f1, 0px 1px 0px #fff;
	box-shadow: inset 0px 1px 0px #7fd2f1, 0px 1px 0px #fff;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	-o-border-radius: 5px;
	border-radius: 5px;
}

.a_demo_one::before {
	background-color:#ccd0d5;
	content:"";
	display:block;
	position:absolute;
	width:100%;
	height:100%;
	padding:2px;
	left:-8px;
	top:-8px;
	z-index:-1;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	-o-border-radius: 5px;
	border-radius: 5px;
	-webkit-box-shadow: inset 0px 1px 1px #909193, 0px 1px 0px #fff;
	-moz-box-shadow: inset 0px 1px 1px #909193, 0px 1px 0px #fff;
	-o-box-shadow: inset 0px 1px 1px #909193, 0px 1px 0px #fff;
	box-shadow: inset 0px 1px 1px #909193, 0px 1px 0px #fff;
}

.a_demo_one:active {
	
	top:1px;
	background-image: linear-gradient(bottom, rgb(62,184,229) 0%, rgb(44,160,202) 100%);
	background-image: -o-linear-gradient(bottom, rgb(62,184,229) 0%, rgb(44,160,202) 100%);
	background-image: -moz-linear-gradient(bottom, rgb(62,184,229) 0%, rgb(44,160,202) 100%);
	background-image: -webkit-linear-gradient(bottom, rgb(62,184,229) 0%, rgb(44,160,202) 100%);
	background-image: -ms-linear-gradient(bottom, rgb(62,184,229) 0%, rgb(44,160,202) 100%);
	background-image: -webkit-gradient(
	linear,
	left bottom,
	left top,
	color-stop(0, rgb(62,184,229)),
	color-stop(1, rgb(44,160,202))
	);
}


</style>
<script>
	function addOps<?=$tab_id?>(a)
	{
		$('#edit_reqbh<?=$tab_id?>').load("<?=controller_?>vessel_service/masterOperator/"+a+"/<?=$tab_id?>").dialog({closeOnEscape: false, modal:true, height:300,width:300, title : "Master Operator", open: function(event,ui){$(".ui-dialog-titlebar-close",ui.dialog).hide();}});
	}
	
	function addPrt<?=$tab_id?>(a)
	{
		$('#edit_reqbhc<?=$tab_id?>').load("<?=controller_?>vessel_service/masterPort/"+a+"/<?=$tab_id?>").dialog({closeOnEscape: false, modal:true, height:300,width:300, title : "Master Port", open: function(event,ui){$(".ui-dialog-titlebar-close",ui.dialog).hide();}});
	}
	
	function renameSrvLane<?=$tab_id?>(a,b)
	{
		alert('id: '+a+' '+b);
		$('#vesRnmSvc<?=$tab_id?>').load("<?=controller_?>vessel_service/RenameServiceLane/<?=$tab_id?>",{id:a,name:b}).dialog({closeOnEscape: true, modal:true, height:220,width:400, title : "Rename Service Lane", open: function(event,ui){$(".ui-dialog-titlebar-close",ui.dialog).hide();}});
	}
</script>

<div>

<table >
<tr height="30px" class="tabMainTabelVesServ1" align="center"><td valign="middle"  width="20" class="tdBorderVesServ">No.</TD>
	<td valign="middle" width="200" class="tdBorderVesServ" colspan="2">Nama Alat</TD>
	<td valign="middle" width="250" class="tdBorderVesServ" >Type Alat</TD>
	<td valign="middle" width="250" class="tdBorderVesServ">Operator</TD>
	<td valign="middle" width="150" class="tdBorderVesServ">Master</TD>
</tr>
<? 
$i=1;
foreach($rowsDetail as $rowSe){ ?>
<tr height="32px" valign="middle" align="center" >
<td class="<?if(($i%2)!=0){ echo 'oddrowcolor';}else{ echo 'evenrowcolor'; }?>"><? echo $i;?></td>

<td class="<?if(($i%2)!=0){ echo 'oddrowcolor';}else{ echo 'evenrowcolor'; }?>"><?=$rowSe['MCH_NAME'];?></td>

<td class="<?if(($i%2)!=0){ echo 'oddrowcolor';}else{ echo 'evenrowcolor'; }?>"><div onclick="renameSrvLane<?=$tab_id?>(<?=$rowSe['ID_VESSEL_SERVICE'];?>,'<?=$rowSe['VESSEL_SERVICE_NAME'];?>')" ><img src="images/edits.png" title="rename" ></div></td>

<td class="<?if(($i%2)!=0){ echo 'oddrowcolor';}else{ echo 'evenrowcolor'; }?>"><?=$rowSe['MCH_TYPE'];?> </td>

<td class="<?if(($i%2)!=0){ echo 'oddrowcolor';}else{ echo 'evenrowcolor'; }?>">
<br>
</td>

<td valign="middle" class="<?if(($i%2)!=0){ echo 'oddrowcolor';}else{ echo 'evenrowcolor'; }?>"><a onclick="addOps<?=$tab_id?>(<?=$rowSe['ID_VESSEL_SERVICE'];?>)"  class="a_demo_one">Master Operator</a> <a onclick="addPrt<?=$tab_id?>(<?=$rowSe['ID_VESSEL_SERVICE'];?>)"  class="a_demo_one">Master Port</a></td>

</tr>
<? $i++;}?>
</table>
</div>
<form id="mainform<?=$tab_id?>">
<div id="edit_reqbh<?=$tab_id?>"></div>
<div id="edit_reqbhc<?=$tab_id?>"></div>
<div id="vesRnmSvc<?=$tab_id?>"></div>
</form>