<STYLE>
.select{
    overflow:scroll;
	width:100px;
}
</STYLE>
<link rel="stylesheet" media="screen" type="text/css" href="<?=JS_?>colorpicker/css/colorpicker.css" />
<script type="text/javascript" src="<?=JS_?>colorpicker/js/colorpicker.js"></script>
<style>
	.colorpicker, .colorpicker * {
    z-index: 9999;
}
</style>
<script>
 $(document).ready(function(){
  $('#colorport<?=$tab_id;?>').ColorPicker({
	onSubmit: function(hsb, hex, rgb, el) {
		$(el).val(hex);
		$(el).ColorPickerHide();
	},
	onBeforeShow: function () {
		$(this).ColorPickerSetColor(this.value);
	}
	})
	.bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});
});
</script>
<DIV ><table cellpadding="0" cellspacing="0">
<? 
$i=1;
foreach($rowsDetail as $rowSe){ ?>
<tr height="22px" valign="middle" align="center" >
<td class="tdBorderVesServ2" width="100"><?=$rowSe['ID_PORT'];?></td>
<td class="tdBorderVesServ2" width="150"><?=$rowSe['PORT_NAME'];?></td>
<td width="150"><div style="height:15px;width:30px;background:<?=$rowSe['COLOR'];?>;border-style:solid;border-width:thin;"></div></td>
<td width="50"><a onclick="delPrtSv<?=$tab_id;?>('<?=$id_ves_svc;?>','<?=$rowSe['ID_PORT'];?>')"><img src="images/del.png" width="18"/></a></td>
<?$i++;}?>
</tr>
<tr height="22px" valign="middle" align="center" >
<td class="tdBorderVesServ3" width="100">
	<select id="selOpsc<?=$tab_id?>" class="select">
		<option value="">&nbsp;&nbsp;&nbsp;&nbsp;</option>
		<?php $forSdc=$this->vessel->getContPrt();
			foreach($forSdc as $rowfSdc)
			{?>
				<option value="<?=$rowfSdc['PORT_CODE'];?>"><?=$rowfSdc['PORT_CODE'];?> - <?=$rowfSdc['PORT_NAME'];?></option>
			<?}
		?>
	</select>
</td>
<td class="tdBorderVesServ3" width="150"><input class="jscolor" id="colorport<?=$tab_id;?>"></td>
<td width="50"><a onclick="addPrtSv<?=$tab_id;?>(<?=$id_ves_svc;?>)"><img src="images/plus.png" width="18"/></a></td>
</tr>
</table>
</DIV>
