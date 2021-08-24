<script>
	$(document).ready(function()
	{
		// document.getElementById('bay_slct_<?=$tab_id?>').style.display = 'none';
		$.switcher('#OnOffExchange_<?=$tab_id?>');
	});
	
	function setExchange<?=$tab_id?>(){
	    if($('#OnOffExchange_<?=$tab_id?>').is(':checked')){
		$('.selectable_<?=$tab_id?>').selectable( "destroy" );
		$('.selectable_<?=$tab_id?> .ui-stacking-defaults').removeClass('ui-selected');
		$('.selectable_<?=$tab_id?> .ui-plan-defaultz').removeClass('ui-selected');
		$('.selectable_<?=$tab_id?> .ui-stacking-default-s2').removeClass('ui-selected');
		$('.selectable_<?=$tab_id?> .ui-placement-default').removeClass('ui-selected');
		$( "#select-result_<?=$tab_id?>" ).empty();
	    }else{
		$('.selectable_<?=$tab_id?> .ui-plan-defaultz').removeClass('ui-exchange-selected');
		$('.selectable_<?=$tab_id?> .ui-placement-default').removeClass('ui-exchange-selected');
		
		set_stowage_selectable();
	    }
	}
	
	function set_dropdown()
	{
		var print = $("#list_vsbay_<?=$tab_id?>").val();
		
		if(print=='0' || print=='ALL')
		{
			// document.getElementById('bay_slct_<?=$tab_id?>').style.display = 'none';
		}
		else
		{
			// document.getElementById('bay_slct_<?=$tab_id?>').style.display = 'inline';
		}
	}
</script>

<div id="stowage_viewer_header_<?=$tab_id?>">
	<table>
		<tr>
			<td>
				&nbsp;Stowage Position :
			</td>
			<td>
				<select id="list_vsbay_<?=$tab_id?>" name="list_vsbay" onChange="set_dropdown()" >
					<option value="0">-- ALL --</option>
					<option value="D/DECK">DECK</option>
					<option value="H/HATCH">HATCH</option>
				</select>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;<button type="button" onclick="print_stowage_<?=$tab_id?>('<?=$ID_VESSEL?>','<?=$id_ves_voyage?>')">Print</button>
			</td>
			<td>
				&nbsp;Filter by: 
			</td>
			<td>
				<select name="filter" id="filter_<?=$tab_id?>" >
					<?php
						if($filter == 'SIZE'){
							$selsize = ' selected';
						}else{
							$selsize = ' ';
						}
						if($filter == 'WEIGHT'){
							$selweight = ' selected';
						}else{
							$selweight = ' ';
						}
						if($filter == 'OPERATOR'){ 
							$seloperator = ' selected';
						}else{
							$seloperator = ' ';
						}
						if($filter == '-' or $filter == ''){
						echo"<option value='-'>-</option>";
						}
						
						echo"
						<option value='SIZE' $selsize>SIZE</option>
						<option value='WEIGHT' $selweight>WEIGHT</option>
						<option value='OPERATOR' $seloperator>OPERATOR</option>";
					?>
				</select>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;<button type="button" class='printfilterbutton' onclick="print_filter_<?=$tab_id?>('<?=$ID_VESSEL?>','<?=$id_ves_voyage?>','')">Filter</button>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;<button type="button" onclick="refreshOutboundView<?=$tab_id?>('')">Refresh</button>
			</td>
			<td>&nbsp;&nbsp;&nbsp;Exchange</td>
			<td>
			    <div class="form-check form-check-inline">
				<input class="form-check-input" type="checkbox" id="OnOffExchange_<?=$tab_id?>" value="option1" onchange="setExchange<?=$tab_id?>()" <?php if($isExchangeChecked == 1){ ?> checked <?php } ?> data-checked="<?=$isExchangeChecked?>">
			    </div>
			</td>
		</tr>
	</table>
</div>
<div id="stowage_viewer_content_<?=$tab_id?>">
</div>

<script>
	function print_stowage_<?=$tab_id?>(vescode,id_ves_voyage){
		var vss_idbay_nobay = $("#list_vsbay_<?=$tab_id?>").val();
		var explode = vss_idbay_nobay.split('/');
		var vss_idbay = explode[0];
		var vss_nobay = explode[1];

		var fil = $("#filter_<?=$tab_id?>").val();

		var vss_deck_hatch_posisi = $("#posisi_bay_<?=$tab_id?>").val();
		var explode2 = vss_deck_hatch_posisi.split('/');
		var vss_deck_hatch = explode2[0];
		var vss_posisi = explode2[1];

		if(vss_idbay=="0")
		{
			window.open('<?=controller_?>inbound_view/print_all_bayNew/'+vescode+'/'+id_ves_voyage+'/'+fil+'/<?=$tab_id?>/E','_blank');
		}
		else if(vss_idbay=="X")
		{
			window.open('<?=controller_?>outbound_view/print_preLoad/'+vescode+'/'+id_ves_voyage+'/<?=$tab_id?>/E','_blank');
		}
		else if (vss_idbay == "ALL")
		{
			window.open('<?=controller_?>inbound_view/print_allhatch_bayNew/'+vescode+'/'+id_ves_voyage+'/<?=$tab_id?>/E','_blank');
		}
		else
		{
			window.open('<?=controller_?>outbound_view/print_bay/'+vescode+'/'+id_ves_voyage+'/'+vss_idbay+'/'+vss_deck_hatch+'/'+vss_nobay+'/'+vss_posisi,'_blank');
		}
	}

	function print_filter_<?=$tab_id?>(vescode,id_ves_voyage,valfill){
		if(valfill==''){
			var fil = $("#filter_<?=$tab_id?>").val();
		}else{
			var fil = valfill;
		}

		//console.log('fil : '+fil);

		loadmask.show();
	    Ext.get("<?=$tab_id?>-innerCt").load({
	        url: '<?=controller_?>outbound_view/refresh_filter?tab_id=<?=$tab_id?>&id_ves_voyage=<?=$id_ves_voyage?>&filter='+fil,
	        scripts: true,
	        contentType: 'html',
	        autoLoad: true,
	        success: function(){
	            loadmask.hide();
	        }
	    });
	}

	function refreshOutboundView<?=$tab_id?>(valfill){
	    loadmask.show();
	    var isExchangeChecked = $('#OnOffExchange_<?=$tab_id?>').is(':checked') ? 1 : 0;
	    Ext.get("<?=$tab_id?>-innerCt").load({
	        url: '<?=controller_?>outbound_view/refresh_index?tab_id=<?=$tab_id?>&id_ves_voyage=<?=$id_ves_voyage?>&isExchangeChecked='+isExchangeChecked+'&valfill='+valfill,
	        scripts: true,
	        contentType: 'html',
	        autoLoad: true,
	        success: function(){
	            loadmask.hide();
	        }
	    });
	}
</script>