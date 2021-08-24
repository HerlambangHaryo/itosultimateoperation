<script>
	$(function() {
		$("#list_yard_<?=$tab_id?>").change(function() {
			if ($(this).val()!='-'){
				loadmask.show();
				Ext.getCmp("<?=$tab_id?>").getLoader().load({
					url: '<?=controller_?>yard_planning?tab_id=<?=$tab_id?>&id_yard='+$(this).val(),
					scripts: true,
					contentType: 'html',
					autoLoad: true,
					success: function(){
						loadmask.hide();
					}
				});
			}
		});

		$("#refresh_data_<?=$tab_id?>").click(function() {
			var yard = $('#list_yard_<?=$tab_id?>').val();
			loadmask.show();
			Ext.getCmp("<?=$tab_id?>").getLoader().load({
				url: '<?=controller_?>yard_planning?tab_id=<?=$tab_id?>&id_yard='+yard,
				scripts: true,
				contentType: 'html',
				autoLoad: true,
				success: function(){
					loadmask.hide();
				}
			});
		});
	});
</script>
<div id="yard_viewer_header_<?=$tab_id?>" class="sendiriStyle1">
	<table>
		<tr>
			<td>
				&nbsp;Choose Yard:
			</td>
			<td>
				<select id="list_yard_<?=$tab_id?>" name="list_yard" <?php if($act == 'edit') echo 'disabled'; ?>>
					<option value="-">--Pilih--</option>
					<?php
					foreach ($yard_list as $option){
					?>
						<option value="<?=$option['ID_YARD']?>" <?php if ($id_yard==$option['ID_YARD']) {?> selected <?php }?> ><?=$option['NAME']?></option>
					<?php
					}
					?>
				</select>
			</td>
			<td>
				Plan:
			</td>
			<td>
				<input type="text" id="selected_plan_<?=$tab_id?>" size="30" readonly />
			</td>
			<td>
				Capacity (20' Container):
			</td>
			<td>
				<input type="text" id="selected_capacity_<?=$tab_id?>" size="5" readonly />
			</td>
			<td>
				<button type="button" id="refresh_data_<?=$tab_id?>" action="refresh_data">Refresh Data</button>
			</td>
		</tr>
	</table>
</div>
