<script>
	var url

	$(function() {
		$("#list_yard_<?=$tab_id?>").change(function() {
			$('#list_block_<?=$tab_id?>').find('option').remove();
			$('#list_block_<?=$tab_id?>').append('<option value="-">--Pilih--</option>');
			if ($(this).val()!='-'){
				loadmask.show();
				$.post("<?=controller_?>single_stack_view/data_block_list/",{id_yard:$("#list_yard_<?=$tab_id?>").val()}, function(data){
					var options = eval(data);
					for(var i=0;i<options.length;i++){
						$('#list_block_<?=$tab_id?>').append('<option value="'+options[i].ID_BLOCK+'">'+options[i].BLOCK_NAME+'</option>');
					}
					loadmask.hide();
				});
			}else{
				$('.refreshsinglestackview').hide();
			}
			$('#list_block_<?=$tab_id?>').change();
		});
		
		$("#list_block_<?=$tab_id?>").change(function() {
			$('#list_slot_<?=$tab_id?>').find('option').remove();
			$('#list_slot_<?=$tab_id?>').append('<option value="-">--Pilih--</option>');
			if ($(this).val()!='-'){
				loadmask.show();
				$.post("<?=controller_?>single_stack_view/data_slot_list/",{id_yard:$("#list_yard_<?=$tab_id?>").val(), id_block:$("#list_block_<?=$tab_id?>").val()}, function(data){
					var options = eval(data);
					for(var i=0;i<options.length;i++){
						$('#list_slot_<?=$tab_id?>').append('<option value="'+options[i].slot+'">'+options[i].slot+'</option>');
					}
					loadmask.hide();
				});
			}else{
				$('.refreshsinglestackview').hide();
			}
		});
		
		$("#list_slot_<?=$tab_id?>").change(function() {
			if ($(this).val()!='-'){
				loadSingleStackView();
			}else{
				$('.refreshsinglestackview').hide();
			}
		});	
		
		if ($('.x-tab-inner.x-tab-inner-center:contains("-<?=$id_ves_voyage?>-<?=$slot?>-")').length > 0) {
			$('.x-tab-inner.x-tab-inner-center:contains("-<?=$id_ves_voyage?>-<?=$slot?>-")').text(function () {
				$(this).text("Single Stack View - <?=$id_ves_voyage?>"); 
			});
		}
		var nilaislot = $("#list_slot_<?=$tab_id?>").val();
		if(nilaislot > 0 && nilaislot!='-'){
			$('.refreshsinglestackview').click(function(){
				if(nilaislot!='-'){
					loadmask.show();
					var isRelocOn = $('#OnOffRelocation_<?=$tab_id?>').is(':checked') ? 1 : 0;
					Ext.getCmp("<?=$tab_id?>").getLoader().load({
						url: '<?=controller_?>single_stack_view/index/'+$("#list_yard_<?=$tab_id?>").val()+'/'+$("#list_block_<?=$tab_id?>").val()+'/'+$("#list_slot_<?=$tab_id?>").val()+'?tab_id=<?=$tab_id?>',
						scripts: true,
						contentType: 'html',
						autoLoad: true,
						success: function(){
							loadmask.hide();
						}
					});
				}
			});
		}else{
			$('.refreshsinglestackview').hide();
		}
		console.log('ini slot',$("#list_slot_<?=$tab_id?>").val());
	});

	function loadSingleStackView(){
	    loadmask.show();
	    var isRelocOn = $('#OnOffRelocation_<?=$tab_id?>').is(':checked') ? 1 : 0;
	    Ext.getCmp("<?=$tab_id?>").getLoader().load({
		    url: '<?=controller_?>single_stack_view/index/'+$("#list_yard_<?=$tab_id?>").val()+'/'+$("#list_block_<?=$tab_id?>").val()+'/'+$("#list_slot_<?=$tab_id?>").val()+'?tab_id=<?=$tab_id?>&isRelocOn='+isRelocOn,
		    scripts: true,
		    contentType: 'html',
		    autoLoad: true,
		    success: function(){
			    loadmask.hide();
		    }
	    });
	}
			
	function nextPrev_onClick_<?=$tab_id?>(act){
	    var slot = $("#list_slot_<?=$tab_id?>").val();
	    var max_slot = $("#list_slot_<?=$tab_id?> option").length - 1;
	    
	    console.log('slot : ' + slot);
	    console.log('max_slot : ' + max_slot);
	    
	    if(act == 'p'){
		if(slot != '-' && slot > 1){
		    slot--;
		}
	    }else{
		if(slot == '-' && max_slot > 0){
		    slot = 1;
		}else if(slot < max_slot){
		    slot++;
		}
	    }
	    $("#list_slot_<?=$tab_id?>").val(slot);
	    loadSingleStackView();
	    
	}

	function save_no_work_area(row_from,row_to,tier_from,tier_to,remarks){
	    
	    var url = "<?=controller_?>single_stack_view/save_void";
	    $.ajax({
		type: "POST",
		url: url,
		data: {
		    'row_from' : row_from,
		    'row_to' : row_to,
		    'tier_from' : tier_from,
		    'tier_to' : tier_to,	    
		    'remarks' : remarks,	    
		    'yard' : '<?=$id_yard?>',	    
		    'block' : '<?=$id_block?>',	    
		    'slot' : '<?=$slot?>'	    
		},
		success: function (result) {
		    if(result == 1){
			loadmask.show();
			Ext.getCmp("<?=$tab_id?>").getLoader().load({
				url: '<?=controller_?>single_stack_view/index/'+$("#list_yard_<?=$tab_id?>").val()+'/'+$("#list_block_<?=$tab_id?>").val()+'/'+$("#list_slot_<?=$tab_id?>").val()+'?tab_id=<?=$tab_id?>',
				scripts: true,
				contentType: 'html',
				autoLoad: true,
				success: function(){
					loadmask.hide();
				}
			});
		    }else{
			alert('Set No Work Area Failed');
		    }
		    
		}
	    });
	}
</script>

<div id="stack_viewer_header_<?=$tab_id?>" class="sendiriStyle4">
	<table>
		<tr>
			<?php
			if($idpod==''){
			?>
			<td>
				Choose Yard:
			</td>
			<td>
				<select id="list_yard_<?=$tab_id?>" name="list_yard">
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
			<?php
			}else{
				echo"
				<td>
					Yard:
				</td>
				<td>";
				foreach ($yard_list as $option){
					if ($id_yard==$option['ID_YARD']) {
						echo"$option[NAME]";
					}
				}
				echo"
				</td>";
			}
			?>
		</tr>
		<tr>
			<td>
				Block:
			</td>
			<td>
				<?php
				if($idpod==''){
				?>
				<select id="list_block_<?=$tab_id?>" name="list_block">
					<option value="-">--Pilih--</option>
					<?php
					foreach ($block_list as $option){
					?>
						<option value="<?=$option['ID_BLOCK']?>" <?php if ($id_block==$option['ID_BLOCK']) {?> selected <?php }?> ><?=$option['BLOCK_NAME']?></option>
					<?php
					}
					?>
				</select>
				<?php
				}else{
					foreach ($block_list as $option){
						if ($id_block==$option['ID_BLOCK']) {
							echo"$option[BLOCK_NAME]";
						}
					}
				}
				?>
			</td>
		</tr>
		<tr>
			<td>
				Slot:
			</td>
			<td>
				<select id="list_slot_<?=$tab_id?>" name="list_slot">
					<option value="-">--Pilih--</option>
					<?php
					if($idpod==''){
						foreach ($slot_list as $option){
						?>
							<option value="<?=$option['slot']?>" <?php if ($slot==$option['slot']) {?> selected <?php }?> ><?=$option['slot']?></option>
						<?php
						}
					}else{
						foreach ($YD_SLOT as $option){
						?>
							<option value="<?=$option['YD_SLOT']?>" <?php if ($slot==$option['YD_SLOT']) {?> selected <?php }?> ><?=$option['YD_SLOT']?></option>
						<?php
						}
					}
					?>
				</select>

					<?php
					if($idpod==''){
						?>
			    <a href="javascript:;" onclick="nextPrev_onClick_<?=$tab_id?>('p')" title="Previous">
					<img src="<?=IMG_?>icons/prev.png" width="20px"/>
			    </a>
			    
			    <a href="javascript:;" onclick="nextPrev_onClick_<?=$tab_id?>('n')" title="Next">
					<img src="<?=IMG_?>icons/next.png" width="20px"/>
			    </a>
					<?php
					}
					?>
				<a class='refreshsinglestackview x-btn-default-small'>
					Refresh
				</a>		
			</td>			
		</tr>
	</table>
</div>
<style>
a.refreshsinglestackview {
    color: white;
    cursor: pointer;
}
</style>