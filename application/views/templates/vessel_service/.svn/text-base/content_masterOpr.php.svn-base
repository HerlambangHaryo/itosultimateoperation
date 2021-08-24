<STYLE>
.select{
    overflow:scroll;
	width:100px;
}
</STYLE>
<DIV ><table cellpadding="0" cellspacing="0">
<? 
$i=1;
foreach($rowsDetail as $rowSe){ ?>
<tr height="22px" valign="middle" align="center" >
<td class="tdBorderVesServ2" width="100"><?=$rowSe['ID_OPERATOR'];?></td>
<td class="tdBorderVesServ2" width="150"><?=$rowSe['OPERATOR_NAME'];?></td>
<td width="50"><a onclick="delOpr<?=$tab_id;?>('<?=$id_ves_svc;?>','<?=$rowSe['ID_OPERATOR'];?>')"><img src="images/del.png" width="18"/></a></td>
<?$i++;}?>
</tr>
<tr height="22px" valign="middle" align="center" >
<td class="tdBorderVesServ3" width="100">
	<select id="selOps<?=$tab_id?>" class="select">
		<option value="">&nbsp;&nbsp;&nbsp;&nbsp;</option>
		<?php $forSd=$this->vessel->getContOp();
			foreach($forSd as $rowfSd)
			{?>
				<option value="<?=$rowfSd['ID_OPERATOR'];?>"><?=$rowfSd['ID_OPERATOR'];?> - <?=$rowfSd['OPERATOR_NAME'];?></option>
			<?}
		?>
	</select>
</td>
<td class="tdBorderVesServ3" width="150"></td>
<td width="50"><a onclick="addOpr<?=$tab_id;?>(<?=$id_ves_svc;?>)"><img src="images/plus.png" width="18"/></a></td>
</tr>
</table>
</DIV>
