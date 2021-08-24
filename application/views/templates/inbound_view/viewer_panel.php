<script>
	$(document).ready(function()
	{
		document.getElementById('bay_slct_<?=$tab_id?>').style.display = 'none';
	});
	
	function set_dropdown()
	{
		var print = $("#list_vsbay_<?=$tab_id?>").val();
		
		if(print=='0')
		{
			document.getElementById('bay_slct_<?=$tab_id?>').style.display = 'none';
		}
		else if (print == 'ALL')
		{
			document.getElementById('bay_slct_<?=$tab_id?>').style.display = 'none';
		}
		else
		{
			document.getElementById('bay_slct_<?=$tab_id?>').style.display = 'inline';
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
					<option value="ALL">Hatch Print All</option>
					
					<?php
					foreach ($vessel_posisi as $option){
					?>
						<option value="<?=$option['ID_BAY'];?>/<?=$option['BAY'];?>" >Bay <?=$option['BAY']; ?></option>
					<?php
					}
					?>
				</select>
				&nbsp;
				<span id="bay_slct_<?=$tab_id?>">
					<select name="posisi_bay" id="posisi_bay_<?=$tab_id?>" >
						<option value="D/DECK">DECK</option>
						<option value="H/HATCH">HATCH</option>
					</select>
				</span>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;<button type="button" onclick="print_stowage_<?=$tab_id?>('<?=$ID_VESSEL?>','<?=$id_ves_voyage?>')">Print</button>
			</td>
			<td>
				&nbsp;Filter by: 
			</td>
			<td>
				<select name="filter" id="filter_<?=$tab_id?>" >
					<option value="-">-</option>
					<option value="SIZE">SIZE</option>
					<option value="WEIGHT">WEIGHT</option>
					<option value="OPERATOR">OPERATOR</option>
				</select>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;<button type="button" onclick="print_filter_<?=$tab_id?>('<?=$ID_VESSEL?>','<?=$id_ves_voyage?>')">Filter</button>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;<button type="button" onclick="refreshInboundView<?=$tab_id?>()">Refresh</button>
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
		var fil = $("#filter_<?=$tab_id?>").val();
		var vss_idbay = explode[0];
		var vss_nobay = explode[1];

		var vss_deck_hatch_posisi = $("#posisi_bay_<?=$tab_id?>").val();
		var explode2 = vss_deck_hatch_posisi.split('/');
		var vss_deck_hatch = explode2[0];
		var vss_posisi = explode2[1];

		if(vss_idbay=="0")
		{
			window.open('<?=controller_?>inbound_view/print_all_bayNew/'+vescode+'/'+id_ves_voyage+'/'+fil+'/<?=$tab_id?>/I','_blank');
		}
		else if (vss_idbay == "ALL")
		{
			window.open('<?=controller_?>inbound_view/print_allhatch_bayNew/'+vescode+'/'+id_ves_voyage+'/<?=$tab_id?>/I','_blank');
		}
		else
		{
			window.open('<?=controller_?>inbound_view/print_bay/'+vescode+'/'+id_ves_voyage+'/'+vss_idbay+'/'+vss_deck_hatch+'/'+vss_nobay+'/'+vss_posisi,'_blank');
		}
	}


	function print_filter_<?=$tab_id?>(vescode,id_ves_voyage){
		var fil = $("#filter_<?=$tab_id?>").val();

		//console.log('fil : '+fil);

		loadmask.show();
	    Ext.get("<?=$tab_id?>-innerCt").load({
	        url: '<?=controller_?>inbound_view/refresh_filter?tab_id=<?=$tab_id?>&id_ves_voyage=<?=$id_ves_voyage?>&filter='+fil,
	        scripts: true,
	        contentType: 'html',
	        autoLoad: true,
	        success: function(){
	            loadmask.hide();
	        }
	    });
	}

	function refreshInboundView<?=$tab_id?>(){
	    loadmask.show();
	    Ext.get("<?=$tab_id?>-innerCt").load({
	        url: '<?=controller_?>inbound_view/refresh_index?tab_id=<?=$tab_id?>&id_ves_voyage=<?=$id_ves_voyage?>',
	        scripts: true,
	        contentType: 'html',
	        autoLoad: true,
	        success: function(){
	            loadmask.hide();
	        }
	    });
	}
</script>
