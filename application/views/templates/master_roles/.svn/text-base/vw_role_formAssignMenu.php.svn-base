<script type="text/javascript">
$(function() {
	var panel1 = {
	    html : $('#div_menu_list_<?=$tab_id?>').html()
	};
	var win = new Ext.Window({
		layout: 'fit',
		modal: true,
		title: 'Assign Menu - <?=$group_name?>',
		closable: true,
		items: [Ext.create('Ext.form.Panel', {
			frame: true,
			bodyPadding: 5,
			height: 500,
			width: 500,
			autoScroll: true,
			fieldDefaults: {
				labelAlign: 'left',
				labelWidth: 100
			},
			items: [panel1],
			buttons: [{
				text: 'Save',
				formBind: true,
				handler: function() {
//				    loadmask.show();
				    var val = '';
				    $('.menu').each(function(x,y){
					if(val != '') val += '|';
					val += $(y).val() + ':';
					val += y.checked ? 1 : 0;
				    });
//				    console.log('val = ' + val);
				    Ext.Ajax.request({
					url: '<?=controller_?>roles/save_assign_menu',
					method: 'POST',
					params: {
					    id_group: '<?=$id_group?>',
					    group_name: '<?=$group_name?>',
					    val: val
					},
					scope: this,
					success: function(result, response) {
					    loadmask.hide();
					    var res = JSON.parse(result.responseText);
					    var status = res.IsSuccess ? 'Success' : 'Failed';

					    Ext.Msg.alert(status, res.Message);
					    if(res.IsSuccess){
						win.close();
						role_store.reload();
					    }
					},
					failure:function(form, response) {
					    Ext.Msg.alert('Failed: ', response.errorMessage);
					    loadmask.hide();
					}
				    });
				}
			},{
				text: 'Cancel',
				handler: function() {
					win.close();
				}
			}]
		})]
	});
	win.show();
	$('#div_menu_list_<?=$tab_id?>').remove();
});

function menu_choose(elm){
    var id = $(elm).attr("id");
    $('.' + id).prop('checked', elm.checked);
}
</script>
<div id="div_menu_list_<?=$tab_id?>">
    <table>
	<thead>
	    <tr>
		<th>Menu Name</th>
	    </tr>
	</thead>
	<tbody>
<?php
foreach ($menu as $lvl1){
    if($lvl1['PARENT_ID'] == -1){
?>
	    <tr>
		<td><input id="menu-<?=$lvl1['ID_MENU']?>" class="menu" type="checkbox" onclick="menu_choose(this)" value="<?=$lvl1['ID_MENU']?>" <?php if(strpos($lvl1['ID_USER_GROUP'],','.$id_group.',') > -1){ ?> checked <?php } ?>/> <?=$lvl1['MENU_NAME']?></td>
	    </tr>
<?php
	foreach ($menu as $lvl2){
	    if($lvl1['ID_MENU'] == $lvl2['PARENT_ID'] && trim($lvl2['MENU_NAME']) != '-'){
?>
	    <tr>
		<td style="padding-left: 10px;"><input id="menu-<?=$lvl2['ID_MENU']?>" class="menu menu-<?=$lvl1['ID_MENU']?>" type="checkbox" onclick="menu_choose(this)" value="<?=$lvl2['ID_MENU']?>" <?php if($lvl2['ID_USER_GROUP'] != '' && strpos($lvl2['ID_USER_GROUP'],','.$id_group.',') > -1){ ?> checked <?php } ?>/> <?=$lvl2['MENU_NAME']?></td>
	    </tr>
<?php
		foreach ($menu as $lvl3){
		    if($lvl2['ID_MENU'] == $lvl3['PARENT_ID'] && trim($lvl3['MENU_NAME']) != '-'){
?>
	    <tr>
		<td style="padding-left: 20px;"><input id="menu-<?=$lvl3['ID_MENU']?>" class="menu menu-<?=$lvl1['ID_MENU']?> menu-<?=$lvl2['ID_MENU']?>" type="checkbox" onclick="menu_choose(this)" value="<?=$lvl3['ID_MENU']?>" <?php if($lvl3['ID_USER_GROUP'] != '' && strpos($lvl3['ID_USER_GROUP'],','.$id_group.',') > -1){ ?> checked <?php } ?>/> <?=$lvl3['MENU_NAME']?></td>
	    </tr>
<?php
		    }
		    if($lvl2['ID_MENU'] == $lvl3['PARENT_ID'] && trim($lvl3['MENU_NAME']) == '-'){
?>
	    <input type="checkbox" class="menu menu-<?=$lvl2['ID_MENU']?>" style="display: none" value="<?=$lvl3['ID_MENU']?>"/>
<?php
		    }
		}
	    }
	    if($lvl1['ID_MENU'] == $lvl2['PARENT_ID'] && trim($lvl2['MENU_NAME']) == '-'){
?>
	    <input type="checkbox" class="menu menu-<?=$lvl1['ID_MENU']?>" style="display: none;" value="<?=$lvl2['ID_MENU']?>"/>
<?php
	    }
	}
    }
}
?>
	</tbody>
    </table>
</div>