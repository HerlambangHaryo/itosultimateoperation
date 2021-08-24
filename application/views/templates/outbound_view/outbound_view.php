<?php
	//lama
		$size = 35;
?>

<style>
	.selectable_<?=$tab_id?> .ui-selecting { background: #FECA40; }
	.selectable_<?=$tab_id?> .ui-selected,.selectable_<?=$tab_id?> .ui-exchange-selected { background: #F39814 !important; color: white; }
	.selectable_<?=$tab_id?> { list-style-type: none; margin: 0; padding: 0; }
	.selectable_<?=$tab_id?> li {float: left; width: <?php echo $size."px"?>; height: <?php echo $size."px"?>; text-align: center; line-height:<?=$size?>px;}
	div.grid_<?=$tab_id?> {
		position: absolute;
	}
	.grid_<?=$tab_id?> .palka {
		background:#3a5795;
		height: 5px;
		float: left;
	}
	.text_ {
		font-size: 8px;
		margin-left: 0;
		margin-top: 3px;
		position: absolute;
	}


	.simbil{
		font-size: 8px;
	}			
	
	.div-tl-simbol{
		float: right;
		height: 15px;
		margin-top: -12px;
		width: 10px;
	}

	.div-job-seq{
		float: left;
		height: 18px;
		margin-top: -8px;
		width: 10px;
	}	

	.ui-plan-defaultz {
		/*background: rgba(0, 0, 0, 0) linear-gradient(to right bottom, rgb(57,159,233), rgb(57,159,233)) repeat scroll 0 0; */
		/*rgb(58,87,149), rgb(95,167,219)*/
		/*background: transparent !important;*/
	}

	.ui-plan-20-cell {
		background-image: linear-gradient(-45deg, white 50%, rgb(58,87,149), rgb(95,167,219) 51%) !important; 
		/* rgb(58,87,149), #bafc6f, #ffd86c */
		background-color: rgb(58,87,149) !important;
	}

	.ui-placement-20-cell {
		background-image: linear-gradient(-45deg, white 50%, rgb(58,87,149), rgb(95,167,219) 51%) !important; 
		/* rgb(58,87,149), #bafc6f, #ffd86c */
		background-color: rgb(58,87,149) !important;
	}

	.ui-plan-20-cell .ui-selected {
		background-image: none !important; /* #ffd86c */
		background-color: none !important;
	}

	.ui-placement-default {
		color: #3b3b3b;
	}

	.ui-stacking-defaults{
		/*border-top: 1px solid #000000;*/
		/*border-left: 1px solid #000000;*/
		/*background: #1484e6 url(./excite-bike/images/gantiPng.png)  50% 50% repeat;*/
		background: #1484e6 url(./config/CSS/excite-bike/images/ui-bg_diagonals-small_25_c5ddfc_40x40.png)  50% 50% repeat !important;
		font-size:10px; 
		color: #3b3b3b; 
	}
	
	.tooltip{
		display: inline;
		position: relative;
	}
	
	.tooltip:hover:after{
		background: rgb(255 255 255);
		-webkit-border-bottom-right-radius: 5px;
		-webkit-border-bottom-left-radius: 5px;
		-moz-border-radius-bottomright: 5px;
		-moz-border-radius-bottomleft: 5px;
		border-bottom-right-radius: 5px;
		border-bottom-left-radius: 5px;
		bottom: -75px;
		color: #000;
		border-bottom: 1px solid black;
		border-left: 1px solid black;
		border-right: 1px solid black;
		content: attr(tooltip);
		left: 20%;
		padding: 0px 5px 5px 5px;
		margin-top: 5px;
		position: absolute;
		z-index: 98;
		font-size: 12px;
		white-space: nowrap;
		display: block;
		min-width: 90px;
	}
	
	.tooltip:hover:before{
		background: rgb(255 255 255);
		-webkit-border-top-left-radius: 5px;
		-webkit-border-top-right-radius: 5px;
		-moz-border-radius-topleft: 5px;
		-moz-border-radius-topright: 5px;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
		bottom: -40px;
		color: #000;
		border-top: 1px solid black;
		border-left: 1px solid black;
		border-right: 1px solid black;
		content: attr(infotitle);
		left: 20%;
		padding: 5px 5px 0px 5px;
		margin-bottom: 5px;
		align-content: normal;
		position: absolute;
		z-index: 99;
		white-space: nowrap;
		font-size: 12px;
		min-width: 90px;
	}
	
	.tooltip.hidetl:hover:after {
		display: none;
	}

	.tooltip.hidetl:hover:before {
		border-bottom: 1px solid black;
		border-radius: 5px;
	}
</style>

<script>
	(function($) {    
		if ($.fn.style) {
			return;
		}

	  // Escape regex chars with \
	  var escape = function(text) {
	  	return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
	  };

	  // For those who need them (< IE 9), add support for CSS functions
	  var isStyleFuncSupported = !!CSSStyleDeclaration.prototype.getPropertyValue;
	  if (!isStyleFuncSupported) {
	  	CSSStyleDeclaration.prototype.getPropertyValue = function(a) {
	  		return this.getAttribute(a);
	  	};
	  	CSSStyleDeclaration.prototype.setProperty = function(styleName, value, priority) {
	  		this.setAttribute(styleName, value);
	  		var priority = typeof priority != 'undefined' ? priority : '';
	  		if (priority != '') {
	        // Add priority manually
	        var rule = new RegExp(escape(styleName) + '\\s*:\\s*' + escape(value) +
	        	'(\\s*;)?', 'gmi');
	        this.cssText =
	        this.cssText.replace(rule, styleName + ': ' + value + ' !' + priority + ';');
	    }
	};

	CSSStyleDeclaration.prototype.removeProperty = function(a) {
		return this.removeAttribute(a);
	};

	CSSStyleDeclaration.prototype.getPropertyPriority = function(styleName) {
		var rule = new RegExp(escape(styleName) + '\\s*:\\s*[^\\s]*\\s*!important(\\s*;)?',
			'gmi');
		return rule.test(this.cssText) ? 'important' : '';
	}
}

	  // The style function
	  $.fn.style = function(styleName, value, priority) {
	    // DOM node
	    var node = this.get(0);
	    // Ensure we have a DOM node
	    if (typeof node == 'undefined') {
	    	return this;
	    }
	    // CSSStyleDeclaration
	    var style = this.get(0).style;
	    // Getter/Setter
	    if (typeof styleName != 'undefined') {
	    	if (typeof value != 'undefined') {
	        // Set style property
	        priority = typeof priority != 'undefined' ? priority : '';
	        style.setProperty(styleName, value, priority);
	        return this;
	    } else {
	        // Get style property
	        return style.getPropertyValue(styleName);
	    }
	} else {
	      // Get CSSStyleDeclaration
	      return style;
	 }
	};
})(jQuery);

$(function() {
	var default_mode 	= "-";
	var list_mode 		= [
		"SIZE", "WEIGHT", "OPERATOR"
	];

	$("select[name=filter_outbound_view]").on('change', function(){
		console.log($(this).val());
		if ($(this).val() == list_mode[0]){
			$(".ui-plan-defaultz").each(function(){
				$(this).style('background', 'rgba(0, 0, 0, 0) linear-gradient(to right bottom, rgb(57, 159, 233), rgb(57, 159, 233)) repeat scroll 0 0', '');
				$(this).find('.text_').html($(this).attr('data-seq'));
			});
		} else if ($(this).val() == list_mode[1]){
			$(".ui-plan-defaultz").each(function(){
				console.log($(this).attr('no_container'));
				$(this).style('background', 'rgba(0, 0, 0, 0) linear-gradient(to right bottom, rgb(57, 159, 233), rgb(57, 159, 233)) repeat scroll 0 0', '');
				$(this).find('.text_').html($(this).attr('data-weight'));
			});
		} else if ($(this).val() == list_mode[2]){
			$(".ui-plan-defaultz").each(function(){
					// $(this).css('background-color', $(this).attr('data-opr-color'));
					$(this).style('background', 'rgba(0, 0, 0, 0) linear-gradient(to right bottom, '+$(this).attr('data-opr-color')+', '+$(this).attr('data-opr-color')+') repeat scroll 0 0', 'important');
					$(this).find('.text_').html($(this).attr('data-seq'));
				});
		}
	});

	

	$.contextMenu({
		selector: ".selectable_<?=$tab_id?> .ui-stacking-defaults.ui-selected, .selectable_<?=$tab_id?> .ui-stacking-default-s2.ui-selected",
		items: {
			"set": {
				name: "Set Sequence", 
				icon: "edit", 
				callback: function(key, options) {
					setSequence_<?=$tab_id?>($("#id_ves_voyage_<?=$tab_id?>").val(), $("#bay_id_<?=$tab_id?>").html(), $("#deck_hatch_<?=$tab_id?>").html());
				}
			},
			"sep1": "---------",
			"quit": {
				name: "Quit",
				icon: "quit",
				callback: function(key, options) {
					$(this).contextMenu("hide");
				}
			}
		}
	});

	$.contextMenu({
		selector: ".selectable_<?=$tab_id?> .ui-plan-defaultz.ui-selected",
		items: {
			"unset": {
				name: "Unset Sequence",
				icon: "delete",
				callback: function(key, options) {
					unsetSequence_<?=$tab_id?>($("#id_ves_voyage_<?=$tab_id?>").val(), $("#bay_id_<?=$tab_id?>").html(), $("#deck_hatch_<?=$tab_id?>").html());
				}
			},
			"sep1": "---------",
			"quit": {
				name: "Quit",
				icon: "quit",
				callback: function(key, options) {
					$(this).contextMenu("hide");
				}
			},
			"sep2": "---------",
			"inquiry": {
				name: "Container Inquiry",
				icon: "search",
				callback: function(key, options) {
					contInquiry_<?=$tab_id?>(this);
				}
			}
		}
	});

	$.contextMenu({
		selector: ".selectable_<?=$tab_id?> .ui-box-container,.selectable_<?=$tab_id?> .ui-placement-default,.selectable_<?=$tab_id?> .ui-plan-defaultz",
		items: {
			"inquiry": {
				name: "Container Inquiry",
				icon: "search",
				callback: function(key, options) {
					contInquiry_<?=$tab_id?>(this);
				}
			}
		}
	});
});

$(document).ready(function()
{
	$( ".selectable_<?=$tab_id?>" ).selectable();
	setExchange<?=$tab_id?>();
});

function set_stowage_selectable(){
    var sequence = 1;
    $( ".selectable_<?=$tab_id?>" ).selectable({
	    filter: ".ui-stacking-defaults, .ui-plan-defaultz, .ui-stacking-default-s2",
	    start: function( event, ui ) {
		    $( "#bay_id_<?=$tab_id?>" ).empty();
		    $( "#deck_hatch_<?=$tab_id?>" ).empty();
	    },
	    selecting: function( event, ui ) {
		    var id_bay_cur = $(ui.selecting).attr('id_bay');
		    var deck_hatch_cur = $(ui.selecting).attr('deck_hatch');

		    var id_bay_before = $( "#bay_id_before_<?=$tab_id?>" ).html();
		    var deck_hatch_before = $( "#deck_hatch_before_<?=$tab_id?>" ).html();

			    // console.log(id_bay_cur+'='+id_bay_before+' - '+deck_hatch_cur+'='+deck_hatch_before);
			    if (id_bay_cur!=id_bay_before || deck_hatch_cur!=deck_hatch_before){
				    $( ".selectable_<?=$tab_id?> .ui-selected" ).removeAttr('sequence');
				    $( ".selectable_<?=$tab_id?> .ui-selected" ).removeClass('ui-selected');
				    sequence = 1;
			    }
			    $(ui.selecting).attr('sequence',sequence);
			    sequence = sequence + 1;
			    $( "#bay_id_before_<?=$tab_id?>" ).html($(ui.selecting).attr('id_bay'));
			    $( "#deck_hatch_before_<?=$tab_id?>" ).html($(ui.selecting).attr('deck_hatch'));
		    },
		    unselecting: function( event, ui ) {
			    $(ui.selecting).removeAttr('sequence');
			    sequence = sequence - 1;
		    },
		    selected: function(event, ui) {
			    if ($( "#bay_id_<?=$tab_id?>" ).html()==""){
				    $( "#bay_id_<?=$tab_id?>" ).append(
					    $(ui.selected).attr('id_bay')
					    );
			    }
			    if ($( "#deck_hatch_<?=$tab_id?>" ).html()==""){
				    $( "#deck_hatch_<?=$tab_id?>" ).append(
					    $(ui.selected).attr('deck_hatch')
					    );
			    }
		    },
		    stop: function(event, ui) {
			    sequence = 1;
			    $( "#select-result_<?=$tab_id?>" ).empty();
			    $( "#result_<?=$tab_id?>" ).empty();
			    var selected_el = $(".selectable_<?=$tab_id?> .ui-stacking-defaults.ui-selected, .selectable_<?=$tab_id?> .ui-plan-defaultz.ui-selected, .selectable_<?=$tab_id?> .ui-stacking-default-s2.ui-selected");
			    for (var index = 0; index < selected_el.length; ++index) {
				    var attr = $(selected_el[index]).attr('no_container');
				    if(typeof attr === typeof undefined){
					    if ($( "#select-result_<?=$tab_id?>" ).html()!=""){
						    $( "#select-result_<?=$tab_id?>" ).append(",");
					    }
					    if ($( "#result_<?=$tab_id?>" ).html()!=""){
						    $( "#result_<?=$tab_id?>" ).append(",");
					    }

					    $( "#select-result_<?=$tab_id?>" ).append(
						    $(selected_el[index]).attr('bay')+"-"+$(selected_el[index]).attr('row')+"-"+$(selected_el[index]).attr('tier')+"-"+$(selected_el[index]).attr('id_cell')+"-"+$(selected_el[index]).attr('sequence')
						);
					    $( "#result_<?=$tab_id?>" ).append(
						    $(selected_el[index]).attr('id_cell')
						    );
					    sequence += 1;
				    }
			    }
			    for (var index = 0; index < selected_el.length; ++index) {
				    var no_container = $(selected_el[index]).attr('no_container');
				    var point = $(selected_el[index]).attr('point');
				    if(typeof no_container !== typeof undefined && no_container !== false){
					    if ($( "#select-result_<?=$tab_id?>" ).html()!=""){
						    $( "#select-result_<?=$tab_id?>" ).append(",");
					    }
					    if ($( "#result_<?=$tab_id?>" ).html()!=""){
						    $( "#result_<?=$tab_id?>" ).append(",");
					    }

					    $( "#select-result_<?=$tab_id?>" ).append(
						    $(selected_el[index]).attr('bay')+"-"+$(selected_el[index]).attr('row')+"-"+$(selected_el[index]).attr('tier')+"-"+$(selected_el[index]).attr('id_cell')+"-"+$(selected_el[index]).attr('sequence')+"-S-" + no_container + "-" + point
						    );
					    $( "#result_<?=$tab_id?>" ).append(
						    $(selected_el[index]).attr('id_cell')
						    );
					    $(selected_el[index]).attr('id',$(selected_el[index]).attr('bay')+"-"+$(selected_el[index]).attr('row')+"-"+$(selected_el[index]).attr('tier')+"-"+$(selected_el[index]).attr('id_cell')+"-"+$(selected_el[index]).attr('sequence')+"-S-" + no_container + "-" + point);
					    sequence += 1;
				    }
			    }

			    // console.log($( "#select-result_<?=$tab_id?>" ).html());
		    }
	    });
}

$( ".selectable_<?=$tab_id?> .ui-placement-default,.selectable_<?=$tab_id?> .ui-plan-defaultz,.selectable_<?=$tab_id?> .ui-stacking-defaults" ).on('click', function(){
    var from = '';
    var to = '';
    var id_ves_voyage = $(this).attr('id_ves_voyage');
    var fromSelected = $( "#select-result_<?=$tab_id?>" ).html();

    if(fromSelected == ''){
	if(!$(this).hasClass('ui-stacking-defaults')){
	    from = $(this).attr('id_bay') + '-' + $(this).attr('bay') + '-' + $(this).attr('row') + '-' + $(this).attr('tier');
	    from += '-' + $(this).attr('deck_hatch') + '-' + $(this).attr('id_cell');
	    from += '-' + $(this).attr('no_container') + '-' + $(this).attr('point') + '-' + $(this).attr('cont_size');
	    from += '-' + $(this).attr('data-status');
	    $( "#select-result_<?=$tab_id?>" ).html(from);
	    $(this).addClass('ui-exchange-selected');
	}
    }else{
	var arrFrom = fromSelected.split('-');
	var bayTo = $(this).attr('bay');
	var contSizeTo = $(this).attr('cont_size');
	to = $(this).attr('id_bay') + '-' + $(this).attr('bay') + '-' + $(this).attr('row') + '-' + $(this).attr('tier');
	to += '-' + $(this).attr('deck_hatch') + '-' + $(this).attr('id_cell');
	to += '-' + $(this).attr('no_container') + '-' + $(this).attr('point') + '-' + $(this).attr('cont_size');
	to += '-' + $(this).attr('data-status');
	if(fromSelected == to){ //unselect apabila pilih data yg sama
	    $( "#select-result_<?=$tab_id?>" ).html('');
	    $(this).removeClass('ui-exchange-selected');
	}else if(arrFrom[8] >= 40 && (bayTo % 2) == 1 || arrFrom[8] < 40 && (bayTo % 2) == 0 
		|| contSizeTo >= 40 && (arrFrom[1] % 2) == 1 || contSizeTo < 40 && (arrFrom[1] % 2) == 0){ 
	    // jika tidak sesuai antara size dan bay
	    Ext.Msg.alert('Failed', "Container size did not match with bay");
	}else{
	    var url = "<?=controller_?>outbound_view/exchange_stowage";
	    loadmask.show();
	    Ext.Ajax.request({
		    url: url,
		    params: {
			    id_ves_voyage: id_ves_voyage,
			    from: fromSelected,
			    to: to
		    },
		    success: function(response){
			    loadmask.hide();
				var valuescroll = $("div#<?=$tab_id?>-body").scrollLeft();
				localStorage['vsc_<?=$tab_id?>'] = valuescroll;	  
			    var res = JSON.parse(response.responseText);
			    if (res[0]=='S'){
				var fil = $("#filter_<?=$tab_id?>").val();
				var valfill="";
				if(fil!='-'){
					var valfill=fil;
				}
				refreshOutboundView<?=$tab_id?>(valfill);
			    }else{
				Ext.Msg.alert('Failed', res[1]);
			    }
		    }
	    });
	}
    }
});
</script>

<span id="select-result_<?=$tab_id?>" style="display: none;"></span>
<span id="result_<?=$tab_id?>" style="display: none;"></span>
<span id="bay_id_<?=$tab_id?>" style="display: none;"></span>
<span id="deck_hatch_<?=$tab_id?>" style="display: none;"></span>
<input id="id_ves_voyage_<?=$tab_id?>" type="hidden" value="<?=$id_ves_voyage?>"></input>

<span id="bay_id_before_<?=$tab_id?>" style="display: none;"></span>
<span id="deck_hatch_before_<?=$tab_id?>" style="display: none;"></span>

<center>
	<div class="grid_<?=$tab_id?>">
		<table border="0" width="100%">
			<tr align="center">
				<?php
				foreach($bay_area as $bay){
					$n = -2;
					?>
					<?php
					if ($bay['BAY']%2!=0){
						?>
						<td align="center" id="bay_view_<?=$tab_id?>_<?=$bay['BAY']?>">
							<table style="width: <?=($bay["JML_ROW"]+1)*$size+20?>px;" frame="box">
								<tr>
									<?php
									if ($count_bay==0 || $bay_area[$count_bay-1]["BAY"]%2!=0){
										?>
										<td colspan="<?=$bay["JML_ROW"]+1?>" align="center">
											<div style="float: left; width: 15px;">
												<?php 
												if($vessel['ALONG_SIDE'] == 'P'){ 
													echo 'L';
												}else{
													echo 'W';
												}
												?>
											</div>
											<div style="float: left; width:calc(100% - 32px);">
												<div style="text-align: center; width: 40px;">
													<h1 style="background-color: #3a5795; color: #FFFFFF; margin-top:0px;"><?=$bay["BAY"]?></h1>
												</div>
											</div>
											<div style="float: right; width: 15px;">
												<?php 
												if($vessel['ALONG_SIDE'] == 'P'){ 
													echo 'W';
												}else{
													echo 'L';
												}
												?>
											</div>
										</td>
										<?php
									}else{
										if (($bay["JML_ROW"]+1)%2==0){
											$colspan_left = ($bay["JML_ROW"]+1)/2;
											$colspan_right = ($bay["JML_ROW"]+1)/2;
										}else{
											$colspan_left = ($bay["JML_ROW"])/2;
											$colspan_right = ($bay["JML_ROW"]/2)+1;
										}
										?>
										<td colspan="<?=$colspan_left?>" align="right">
											<div style="float: left; width: 15px;">
												<?php 
												if($vessel['ALONG_SIDE'] == 'P'){ 
													echo 'L';
												}else{
													echo 'W';
												}
												?>
											</div>
											<div style="float: left; width: calc(100% - 17px)">
												<div style="width:40px;" align="center"><h1 class='sbv_<?=$tab_id.$bay_area[$count_bay-1]["BAY"]?>' style="background-color: #ffffff; color: #3a5795; margin-top:0px; cursor: pointer;" onclick="switchBayView_<?=$tab_id?>('<?=$bay_area[$count_bay-1]["BAY"]?>','1');"><?=$bay_area[$count_bay-1]["BAY"]?></h1></div>
											</div>  
										</td>
										<td colspan="<?=$colspan_right?>" align="left">
											<div style="float: left; width: calc(100% - 17px)">
												<div style="width:40px;" align="center"><h1 style="background-color: #3a5795; color: #FFFFFF; margin-top:0px;"><?=$bay["BAY"]?></h1></div>
											</div>
											<div style="float: right; width: 15px;">
												<?php 
												if($vessel['ALONG_SIDE'] == 'P'){ 
													echo 'W';
												}else{
													echo 'L';
												}
												?>
											</div>
										</td>
										<?php
									}
									?>
								</tr>
								<tr>
									<?php
									$odd = false;
									if( ($bay["JML_ROW"] % 2) == 0){
										$start = $bay["JML_ROW"];
									}else{
										$odd = true;
										$start = $bay["JML_ROW"] - 1;
									}

									for($j = 1; $j <= $bay["JML_ROW"]; $j++){
										?>
										<td style="padding: 0px; width: <?=$size-$bay["JML_ROW"]?>px;">
											<center class="ui-small-font"><?=str_pad($start,2,'0',STR_PAD_LEFT)?></center>
										</td>
										<?php
										if (($start + $n) == 0){
											if ($odd){
												$start = $start + $n;
											}else{
												$n = $n * -1;
												$start = 1;
											}
										}else if (($start + $n) < 0){
											$n = $n * -1;
											$start = 1;
										}else{
											$start = $start + $n;
										}
									}
									?>
									<td>
									</td>
								</tr>
								<tr>
									<td colspan='<?=$bay["JML_ROW"]+1?>'>
										<?php
										if ($bay['ABOVE']=='AKTIF'){
											?>
											<ol class="selectable_<?=$tab_id?>">
												<?php
												$index = 0;
												$bay_cell = $this->vessel->get_vessel_profile_cellInfo($id_ves_voyage, $class_code, $ID_VESSEL, $bay['ID_BAY'], $bay['BAY'], 'ABOVE');
												for($j = 1; $j <= $bay["JML_TIER_ON"]; $j++){
													for($s = 1; $s <= $bay["JML_ROW"]; $s++){
														$cell = $bay_cell[$index];
														?>
														<?php 
						// var_dump($cell['NO_CONTAINER']);
						// var_dump($cell['STATUS_STACK']);
						// var_dump($bay_cell[$index-$bay["JML_ROW"]]['NO_CONTAINER']);

						//------------ code pembentukan class ------------//
														if ($cell['TIER_'] == "80" || $cell['TIER_'] == "00") { $class_add_ = "ui-stacking-bottom";} else { $class_add_ = ""; } 

														if ($cell['NO_CONTAINER'] == '' && $cell['STATUS_STACK'] != 'A' && $bay_cell[$index-$bay["JML_ROW"]]['NO_CONTAINER']!='') { $class_add_2 = "ui-stacking-top";} else { $class_add_2 = ""; } 

														if ($cell['STATUS_STACK'] == 'X' && $bay_cell[$index-1]['STATUS_STACK']=='A') { $class_add_ .= "ui-stacking-left";} else { $class_add_ .= ""; } 

														if ($cell['STATUS_STACK'] == 'X' && $bay_cell[$index-1]['STATUS_STACK']=='A' && $cell['TIER_'] == $bay_cell[$index-1]['TIER_']) { $class_add_2 .= " ui-stacking-left";} else { $class_add_2 .= " "; } 
														if(!empty($cell['NO_CONTAINER'])){
															$titleli="$cell[YD_LOCATION]";
															$class_add_ .= " tooltip ";
														}
														if ($cell['TL_FLAG']=='Y') {
															$class_add_ .= " hidetl ";
														}
														?>
														<li tooltip="<?=$titleli?>" id_ves_voyage="<?=$id_ves_voyage?>" class_code="<?=$class_code?>" id_vessel="<?=$ID_VESSEL?>" id_bay="<?=$bay['ID_BAY']?>" <?php if ($cell['NO_CONTAINER']!=''){ ?> 
														    no_container="<?=$cell['NO_CONTAINER']?>" 
														    point="<?=$cell['POINT']?>" 
														    infotitle="<?=$cell['NO_CONTAINER']?><?php echo ($cell['TL_FLAG']=='Y') ? ' - TL' : ''; ?>" 
														    cont_size="<?=$cell['CONT_SIZE']?>" 
														    data-pod="<?=$cell['ID_POD']?>" 
														    data-pod-color="<?=$cell['POD_COLOR']?>" 
														    data-opr="<?=$cell['ID_OPERATOR']?>" 
														    data-opr-color="<?=$cell['OPR_COLOR']?>" 
														    data-weight="<?=$cell['WEIGHT']?>" 
														    data-seq="<?=$cell['SEQUENCE']?>" 
														    data-status="<?=$cell['STATUS']?>"
														    <?php if ($cell['CONT_40_LOCATION']==''){ ?>
															<?php if($cell['ID_CLASS_CODE']=='TC'){ ?> 
																class="ui-placement-disabled <?=$class_add_?>" 
															<?php } else if(($cell['ID_CLASS_CODE']=='S1' || $cell['ID_CLASS_CODE']=='S2') && $cell['HAS_JOB_SHIFTING'] > 0){ ?> 
																<?php if($cell['STATUS']=='P'){ ?> 
																class="ui-stacking-default-s2 <?=$class_add_?>" 
																<?php }else{ ?> 
																	class="ui-placement-default <?=$class_add_?>" 
																<?php } ?> 
															<?php }else{ ?> 
																<?php if ($cell['SEQUENCE']!=''){ ?> 
																	<?php if($cell['STATUS']=='P'){ ?> 
																	    class="ui-plan-defaultz <?=$class_add_?>" 
																	<?php }else{ ?> 
																		class="ui-placement-default <?=$class_add_?>" 
																	<?php } ?> 
																<?php }else if($cell['ID_CLASS_CODE']!='S1' && $cell['ID_CLASS_CODE']!='S2'){ ?> 
																	    class="ui-stacking-defaults <?=$class_add_?>" 
																<?php } 
																}
																?> 
															    <?php }else{ ?> 
																	    class="uiUnAvb" 
															    <?php } ?> 
															<?php }else if($cell['STATUS_STACK']!='A'){ ?> class="uiMutih <?=$class_add_2?>" <?php }else{ ?> class="ui-stacking-defaults <?=$class_add_?>" <?php } ?> id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="D" 
															<?php if($cell['STATUS_STACK']!='X'){ ?>style="box-shadow:0 1px 2px #616161,inset 0 -1px 1px rgba(0,0,0,0.1),inset 0 1px 1px rgba(255,255,255,0.8); 
															<?php if($cell['CONT_40_LOCATION']==''){
																if($cell['BACKGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
																    background-image: linear-gradient(-45deg, white 40%, #<?=$cell['BACKGROUND_COLOR']?>, #<?=$cell['BACKGROUND_COLOR']?> 51%); background-color: #<?=$cell['BACKGROUND_COLOR']?>;
																<?php }
																if($cell['FOREGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
																    color: #<?=$cell['FOREGROUND_COLOR']?>;
																<?php }
															    }?>
														"<? } ?> >
														<div class="simbil">
															<?php
																echo $cell['ID_SPEC_HAND'];

															if($cell['CONT_TYPE']=='HQ'){
																	?>
																<div class="div-tl-simbol">
																	&#9701;
																</div>	
															<?php
															}
															if($cell['TL_FLAG']=='Y'){
																?>
																<div class="div-tl-simbol">
																	&#9660;
																</div>	
																<?php
															}/*else{ echo "C"; }*/ ?>
															<?php if ($cell['CONT_40_LOCATION']==''){ ?> 
																<?php if ($cell['SEQUENCE']!=''){ ?>
																	<!--div style="width: 26px; border-bottom: 1px solid rgb(58,87,149); -webkit-transform: translateY(9px) translateX(-3px) rotate(-45deg);"></div-->
																	<!-- <span class="text_"> -->
																		<div class="div-job-seq">
																			<?php if ($cell['STATUS']=='P') {
																				echo $cell['SEQUENCE'];
																			} else {
																				echo "C";
																			} ?>
																		</div>
																		<!-- </span> -->
																	<?php } ?> 
																	<?php 
																	if($cell['HAZARD'] == 'Y'){
																		echo 'H';
																	}else{
																		if($filter == 'SIZE'){
																			echo $cell['CONT_SIZE'];
																		}else if($filter == 'WEIGHT'){
																			echo $cell['WEIGHT'];
																		}else if($filter == 'OPERATOR'){ 
																			echo $cell['ID_OPERATOR'];
																		}else{
																			echo $cell['ID_COMMODITY'];
																		} 
																	}
																} ?>
															</div>
						<!--div style="width: 26px; border-bottom: 1px solid black; -webkit-transform: translateY(9px) translateX(-3px) rotate(-45deg);"></div>
							<span class="text_">1</span-->
							</li>
							<?php
							if ($cell['STATUS_STACK'] == 'A'){
								$next_class_tier_ = "ui-stacking-left ";
							} else {
								$next_class_tier_ = "";
							}
							$index++;
						}
						?>
						<li class="<?=$next_class_tier_?> ui-small-font"><?=$cell["TIER_"]?></li>
						<?php
					}
					?>
				</ol>
				<?php
			}
			?>
		</td>
	</tr>
	<tr>

		<!-- <td title="cover" colspan="<?=$bay["JML_ROW"]?>" style="background:#3a5795" height="5px"> </td> -->


		<td title="cover" colspan="<?=$bay["JML_ROW"]?>" style="height: 2px;" >
			<?php if($bay["HATCH_NUMBER"]>=1){
				for($tt=1;$tt<=$bay["HATCH_NUMBER"];$tt++){
					if($tt > 1){
						?>
						<div style="height: 5px; width: 3px; float: left">&nbsp;</div>
						<?php
					}

					?>
					<!--<td title="cover" colspan="<?=$bay["JML_ROW"]/$bay["HATCH_NUMBER"]?>" style="background:#3a5795;padding-left:30px;" height="5px" > </td>-->
					<div class="palka" style="width: calc(<?=100/$bay["HATCH_NUMBER"]?>% - 2px)">&nbsp;</div>
					<?php
				}
			}
			?>
		</td>

		<td width="<?=$size?>px"></td>
	</tr>

	<tr>
		<td colspan='<?=$bay["JML_ROW"]+1?>'>
			<?php
			if ($bay['BELOW']=='AKTIF'){
				?>
				<ol class="selectable_<?=$tab_id?>">
					<?php
					$index = 0;
					$bay_cell = $this->vessel->get_vessel_profile_cellInfo($id_ves_voyage, $class_code, $ID_VESSEL, $bay['ID_BAY'], $bay['BAY'], 'BELOW');
					for($j = 1; $j <= $bay["JML_TIER_UNDER"]; $j++){
						for($s = 1; $s <= $bay["JML_ROW"]; $s++){
							$cell = $bay_cell[$index];
							?>
							<?php 
						// var_dump($cell['STATUS_STACK']);
						// var_dump($bay_cell[$index-1]['STATUS_STACK']);
						// var_dump($bay_cell[$index+1]['STATUS_STACK']);
						// var_dump($bay_cell[$index-$bay["JML_ROW"]]['NO_CONTAINER']);

							if ($cell['TIER_'] == "80" || $cell['TIER_'] == "00") { $class_add_ = "ui-stacking-bottom";} else { $class_add_ = ""; } 

							if ($cell['STATUS_STACK'] == 'X' && $bay_cell[$index-$bay["JML_ROW"]]['STATUS_STACK']=='A') { $class_add_2 = " ui-stacking-top";} else { $class_add_2 = " "; } 

							if ($cell['STATUS_STACK'] == 'X' && $bay_cell[$index-1]['STATUS_STACK']=='A' && $cell['TIER_'] == $bay_cell[$index-1]['TIER_']) { $class_add_2 .= " ui-stacking-left";} else { $class_add_2 .= " "; } 

							if ($cell['STATUS_STACK'] == 'X' && $bay_cell[$index-1]['STATUS_STACK']=='A' && $cell['TIER_'] == $bay_cell[$index-1]['TIER_']) { $class_add_2 .= " ui-stacking-left";} else { $class_add_2 .= " "; } 

							if(!empty($cell['NO_CONTAINER'])){
								$titleli="$cell[YD_LOCATION]";
								$class_add_ .= " tooltip ";
							}
							if ($cell['TL_FLAG']=='Y') {
								$class_add_ .= " hidetl ";
							}
							?>
							<li tooltip="<?=$titleli?>" id_ves_voyage="<?=$id_ves_voyage?>" class_code="<?=$class_code?>" id_vessel="<?=$ID_VESSEL?>" id_bay="<?=$bay['ID_BAY']?>" <?php if ($cell['NO_CONTAINER']!=''){ ?> 
							    no_container="<?=$cell['NO_CONTAINER']?>" 
							    point="<?=$cell['POINT']?>" 
							    infotitle="<?=$cell['NO_CONTAINER']?><?php echo ($cell['TL_FLAG']=='Y') ? ' - TL' : ''; ?>" 
							    cont_size="<?=$cell['CONT_SIZE']?>" 
							    data-pod="<?=$cell['ID_POD']?>" 
							    data-pod-color="<?=$cell['POD_COLOR']?>" 
							    data-opr="<?=$cell['ID_OPERATOR']?>" 
							    data-opr-color="<?=$cell['OPR_COLOR']?>" 
							    data-weight="<?=$cell['WEIGHT']?>" 
							    data-seq="<?=$cell['SEQUENCE']?>" 
							    data-status="<?=$cell['STATUS']?>"
								<?php if ($cell['CONT_40_LOCATION']==''){ ?> 
								    <?php if($cell['ID_CLASS_CODE']=='TC'){ ?> 
									    class="ui-placement-disabled <?=$class_add_?>" 
								    <?php } else if(($cell['ID_CLASS_CODE']=='S1' || $cell['ID_CLASS_CODE']=='S2') && $cell['HAS_JOB_SHIFTING']>0){ ?> 
									    <?php if($cell['STATUS']=='P'){ ?> 
										class="ui-stacking-default-s2 <?=$class_add_?>" 
									    <?php }else{ ?> 
										class="ui-placement-default <?=$class_add_?>" 
									    <?php } ?>
								    <?php } else{ ?> 
									<?php if ($cell['SEQUENCE']!=''){ ?> 
									    <?php if($cell['STATUS']=='P'){ ?> 
										class="ui-plan-defaultz <?=$class_add_?>" 
									    <?php }else{ ?> 
										class="ui-placement-default <?=$class_add_?>" 
									    <?php } ?> 
									<?php }else if($cell['ID_CLASS_CODE']!='S1' && $cell['ID_CLASS_CODE']!='S2'){ ?> 
										class="ui-stacking-defaults <?=$class_add_?>" 
									<?php } ?> 
								    <?php } ?>  
								<?php }else{ ?> class="uiUnAvb" 
								    <?php } ?> 
								<?php }else if($cell['STATUS_STACK']!='A'){ ?> class="uiMutih <?=$class_add_2?>" <?php }else{ ?> class="ui-stacking-defaults <?=$class_add_?>" <?php } ?> id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="H" 
								<?php if($cell['STATUS_STACK']!='X'){ ?>style="box-shadow:0 1px 2px #616161,inset 0 -1px 1px rgba(0,0,0,0.1),inset 0 1px 1px rgba(255,255,255,0.8); 
								<?php if($cell['CONT_40_LOCATION']==''){
									if($cell['BACKGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
										background-image: linear-gradient(-45deg, white 40%, #<?=$cell['BACKGROUND_COLOR']?>, #<?=$cell['BACKGROUND_COLOR']?> 51%); background-color: #<?=$cell['BACKGROUND_COLOR']?>;

									<?php }
									if($cell['FOREGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
										color: #<?=$cell['FOREGROUND_COLOR']?>;
									<?php }
								    }?>
							"<?php } ?> >
							<div class="simbil">
								<?php 
									echo $cell['ID_SPEC_HAND'];

								if($cell['CONT_TYPE']=='HQ'){
									?>
								<div class="div-tl-simbol">
									&#9701;
								</div>	
								<?php
								}
								if($cell['TL_FLAG']=='Y'){ ?>
									<div class="div-tl-simbol">
										&#9660;
									</div>	
								<?php }/*else{ echo "C"; }*/ ?>
								<?php if ($cell['CONT_40_LOCATION']==''){ ?> 
									<?php if ($cell['SEQUENCE']!=''){ ?>
										<div class="div-job-seq">
											<?php if ($cell['STATUS']=='P') {
												echo $cell['SEQUENCE'];
											} else { echo "C";} ?> 
										</div> 
									<?php } ?>
									<?php
									if($filter == 'SIZE'){
										echo $cell['CONT_SIZE'];
									}else if($filter == 'WEIGHT'){
										echo $cell['WEIGHT'];
									}else if($filter == 'OPERATOR'){
										echo $cell['ID_OPERATOR'];
									}else{
										echo $cell['ID_COMMODITY'];
									}  } ?>
								</div>
							<!--div style="width: 26px; border-bottom: 1px solid black; -webkit-transform: translateY(9px) translateX(-3px) rotate(-45deg);"></div>
								<div style="width: 26px; border-bottom: 1px solid black; -webkit-transform: translateY(8px) translateX(-4px) rotate(45deg);"></div-->
								</li>
								<?php
								if ($cell['STATUS_STACK'] == 'A'){
									$next_class_tier_ = "ui-stacking-left ";
								} else {
									$next_class_tier_ = "";
								}
								$index++;
							}
							?>
							<li class="<?=$next_class_tier_?> ui-small-font"><?=str_pad($cell["TIER_"],2,'0',STR_PAD_LEFT)?></li>
							<?php
						}
						?>
					</ol>
					<?php
				}
				?>
			</td>
		</tr>
		<tr>
			<?php
			$odd = false;
			if( ($bay["JML_ROW"] % 2) == 0){
				$start = $bay["JML_ROW"];
			}else{
				$odd = true;
				$start = $bay["JML_ROW"] - 1;
			}

			$n = -2;
			for($j = 1; $j <= $bay["JML_ROW"]; $j++){
				?>
				<td style="padding: 0px; width: <?=$size-$bay["JML_ROW"]?>px;">
					<center class="ui-small-font"><?=str_pad($start,2,'0',STR_PAD_LEFT)?></center>
				</td>
				<?php
				if (($start + $n) == 0){
					if ($odd){
						$start = $start + $n;
					}else{
						$n = $n * -1;
						$start = 1;
					}
				}else if (($start + $n) < 0){
					$n = $n * -1;
					$start = 1;
				}else{
					$start = $start + $n;
				}
			}
			?>
			<td>
			</td>
		</tr>
	</table>
</td>
<?php
}else if ($bay['BAY']%2==0){
	?>
	<td align="center" id="bay_view_<?=$tab_id?>_<?=$bay['BAY']?>" style="display:none;">
		<table style="width: <?=($bay["JML_ROW"]+1)*$size+20?>px;" frame="box">
			<tr>
				<?php
				if ($bay_area[$count_bay+1]["BAY"]%2==0){
					?>
					<td colspan="<?=$bay["JML_ROW"]+1?>" align="center">
						<div style="width:40px;"><h1 style="background-color: #3a5795; color: #FFFFFF; margin-top:0px;"><?=$bay["BAY"]?></h1></div>
					</td>
					<?php
				}else{
					if (($bay["JML_ROW"]+1)%2==0){
						$colspan_left = ($bay["JML_ROW"]+1)/2;
						$colspan_right = ($bay["JML_ROW"]+1)/2;
					}else{
						$colspan_left = ($bay["JML_ROW"])/2;
						$colspan_right = ($bay["JML_ROW"]/2)+1;
					}
					?>
					<td colspan="<?=$colspan_left?>" align="right">
						<div style="float: left; width: 15px;">
							<?php 
							if($vessel['ALONG_SIDE'] == 'P'){ 
								echo 'L';
							}else{
								echo 'W';
							}
							?>
						</div>
						<div style="float: left; width: calc(100% - 17px)">
							<div style="width:40px;" align="center"><h1 style="background-color: #3a5795; color: #FFFFFF; margin-top:0px;"><?=$bay["BAY"]?></h1></div>
						</div>
					</td>
					<td colspan="<?=$colspan_right?>" align="left">
						<div style="float: left; width: calc(100% - 17px);">
							<div style="width:40px;" align="center"><h1 class='sbv_<?=$tab_id.$bay_area[$count_bay+1]["BAY"]?>' style="background-color: #ffffff; color: #3a5795; margin-top:0px; cursor: pointer;" onclick="switchBayView_<?=$tab_id?>('<?=$bay_area[$count_bay+1]["BAY"]?>','1');"><?=$bay_area[$count_bay+1]["BAY"]?></h1></div>
						</div>
						<div style="float: right; width: 15px;">
							<?php 
							if($vessel['ALONG_SIDE'] == 'P'){ 
								echo 'W';
							}else{
								echo 'L';
							}
							?>
						</div>
					</td>
					<?php
				}
				?>
			</tr>
			<tr>
				<?php
				$odd = false;
				if( ($bay["JML_ROW"] % 2) == 0){
					$start = $bay["JML_ROW"];
				}else{
					$odd = true;
					$start = $bay["JML_ROW"] - 1;
				}

				for($j = 1; $j <= $bay["JML_ROW"]; $j++){
					?>
					<td style="padding: 0px; width: <?=$size-$bay["JML_ROW"]?>px;">
						<center class="ui-small-font"><?=str_pad($start,2,'0',STR_PAD_LEFT)?></center>
					</td>
					<?php
					if (($start + $n) == 0){
						if ($odd){
							$start = $start + $n;
						}else{
							$n = $n * -1;
							$start = 1;
						}
					}else if (($start + $n) < 0){
						$n = $n * -1;
						$start = 1;
					}else{
						$start = $start + $n;
					}
				}
				?>
				<td>
				</td>
			</tr>
			<tr>
				<td colspan='<?=$bay["JML_ROW"]+1?>'>
					<?php
					if ($bay['ABOVE']=='AKTIF'){
						?>
						<ol class="selectable_<?=$tab_id?>">
							<?php
							$index = 0;
							$bay_cell = $this->vessel->get_vessel_profile_cellInfo($id_ves_voyage, $class_code, $ID_VESSEL, $bay['ID_BAY'], $bay['BAY'], 'ABOVE');
							for($j = 1; $j <= $bay["JML_TIER_ON"]; $j++){
								for($s = 1; $s <= $bay["JML_ROW"]; $s++){
									$cell = $bay_cell[$index];
									?>
									<?php 
									if ($cell['TIER_'] == "80" || $cell['TIER_'] == "00") { $class_add_ = "ui-stacking-bottom";} else { $class_add_ = ""; } 

									if ($cell['STATUS_STACK'] == 'X' && $bay_cell[$index-$bay["JML_ROW"]]['STATUS_STACK']=='A') { $class_add_2 = "ui-stacking-top";} else { $class_add_2 = ""; } 

									if ($cell['STATUS_STACK'] == 'X' && $bay_cell[$index-1]['STATUS_STACK']=='A') { $class_add_ .= "ui-stacking-left";} else { $class_add_ .= ""; } 

									if ($cell['STATUS_STACK'] == 'X' && $bay_cell[$index-1]['STATUS_STACK']=='A' && $cell['TIER_'] == $bay_cell[$index-1]['TIER_']) { $class_add_2 .= " ui-stacking-left";} else { $class_add_2 .= " "; } 

									if(!empty($cell['NO_CONTAINER'])){
										$titleli="$cell[YD_LOCATION]";
										$class_add_ .= " tooltip ";
									}
									if ($cell['TL_FLAG']=='Y') {
										$class_add_ .= " hidetl ";
									}
									?>
									<li tooltip="<?=$titleli?>" id_ves_voyage="<?=$id_ves_voyage?>" class_code="<?=$class_code?>" id_vessel="<?=$ID_VESSEL?>" id_bay="<?=$bay['ID_BAY']?>" <?php if ($cell['NO_CONTAINER']!=''){ ?> 
									    no_container="<?=$cell['NO_CONTAINER']?>" 
									    point="<?=$cell['POINT']?>" 
									    infotitle="<?=$cell['NO_CONTAINER'].', '.$cell['ID_POD'].', '.$cell['ID_OPERATOR']?>" 
									    cont_size="<?=$cell['CONT_SIZE']?>" 
									    data-pod="<?=$cell['ID_POD']?>" 
									    data-pod-color="<?=$cell['POD_COLOR']?>" 
									    data-opr="<?=$cell['ID_OPERATOR']?>" 
									    data-opr-color="<?=$cell['OPR_COLOR']?>" 
									    data-weight="<?=$cell['WEIGHT']?>" 
									    data-seq="<?=$cell['SEQUENCE']?>" 
									    data-status="<?=$cell['STATUS']?>"
										    <?php if ($cell['CONT_40_LOCATION']==''){ ?> 
											<?php if($cell['ID_CLASS_CODE']=='TC'){ ?> 
												    class="ui-placement-disabled <?=$class_add_?>" 
											<?php }else{ ?> 
											    <?php if ($cell['SEQUENCE']!=''){ ?> 
												<?php if($cell['STATUS']=='P'){ ?> 
												    class="ui-plan-defaultz  <?=$class_add_?>" 
												<?php }else{ ?> 
												    class="ui-placement-default <?=$class_add_?>" 
												<?php } ?> 
											    <?php }else{ ?> 
												    class="ui-stacking-defaults <?=$class_add_?>" 
											    <?php } ?> 
											<?php } ?>  
										    <?php }else{ ?> 
												class="uiUnAvb <?=$class_add_?>" 
										    <?php } ?> 
										<?php }else if($cell['STATUS_STACK']!='A'){ ?> 
												class="uiMutih <?=$class_add_2?>" 
										<?php }else{ ?> 
												class="ui-stacking-defaults <?=$class_add_?>" 
										<?php } ?> id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="D" 
										<?php if($cell['STATUS_STACK']!='X'){ ?>style="box-shadow:0 1px 2px #616161,inset 0 -1px 1px rgba(0,0,0,0.1),inset 0 1px 1px rgba(255,255,255,0.8); 
										<?php if($cell['CONT_40_LOCATION']==''){
											if($cell['BACKGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
												background: #<?=$cell['BACKGROUND_COLOR']?> ;
											<?php }
											if($cell['FOREGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
												color: #<?=$cell['FOREGROUND_COLOR']?> ;
											<?php }
										}?>
										"<? } ?> >
										<div class="simbil">
											<?php 
												echo $cell['ID_SPEC_HAND'];

											if($cell['CONT_TYPE']=='HQ'){
												?>
											<div class="div-tl-simbol">
												&#9701;
											</div>	
											<?php
											}
											if($cell['TL_FLAG']=='Y'){ ?>
												<div class="div-tl-simbol">
													&#9660;
												</div>	
												<!-- <div class="corner-left-label">&#10041;</div> -->
											<?php } ?>
											<?php if ($cell['CONT_40_LOCATION']==''){ ?> 
												<?php if ($cell['SEQUENCE']!=''){ ?> 
													<div class="div-job-seq"> 
														<?php if ($cell['STATUS']=='P') { 
															echo $cell['SEQUENCE'];
														} else {
															echo "C";
														} ?> 
													</div>
												<?php } ?>
												<?php 
												if($filter == 'SIZE'){
													echo $cell['CONT_SIZE'];
												}else if($filter == 'WEIGHT'){
													echo $cell['WEIGHT'];
												}else if($filter == 'OPERATOR'){
													echo $cell['ID_OPERATOR'];
												}else{
													echo $cell['ID_COMMODITY'];
												}  ?>
											<?php }else{ ?> 40 <?php } ?>
										</div>
									</li>
									<?php
									if ($cell['STATUS_STACK'] == 'A'){
										$next_class_tier_ = "ui-stacking-left ";
									} else {
										$next_class_tier_ = "";
									}
									$index++;
								}
								?>
								<li class="<?=$next_class_tier_?> ui-small-font"><?=$cell["TIER_"]?></li>
								<?php
							}
							?>
						</ol>
						<?php
					}
					?>
				</td>
			</tr>
			<tr>
				<td title="cover" colspan="<?=$bay["JML_ROW"]?>" style="background:#3a5795" height="5px"> </td>
				<td width="<?=$size?>px"></td>
			</tr>

			<tr>
				<td colspan='<?=$bay["JML_ROW"]+1?>'>
					<?php
					if ($bay['BELOW']=='AKTIF'){
						?>
						<ol class="selectable_<?=$tab_id?>">
							<?php
							$index = 0;
							$bay_cell = $this->vessel->get_vessel_profile_cellInfo($id_ves_voyage, $class_code, $ID_VESSEL, $bay['ID_BAY'], $bay['BAY'], 'BELOW');
							for($j = 1; $j <= $bay["JML_TIER_UNDER"]; $j++){
								for($s = 1; $s <= $bay["JML_ROW"]; $s++){
									$cell = $bay_cell[$index];
									?>
									<?php 
									if ($cell['TIER_'] == "80" || $cell['TIER_'] == "00") { $class_add_ = "ui-stacking-bottom";} else { $class_add_ = ""; } 

									if ($cell['STATUS_STACK'] == 'X' && $bay_cell[$index-$bay["JML_ROW"]]['STATUS_STACK']=='A') { $class_add_2 = "ui-stacking-top";} else { $class_add_2 = ""; } 

									if ($cell['STATUS_STACK'] == 'X' && $bay_cell[$index-1]['STATUS_STACK']=='A' && $cell['TIER_'] == $bay_cell[$index-1]['TIER_']) { $class_add_2 .= " ui-stacking-left";} else { $class_add_2 .= " "; } 

									if(!empty($cell['NO_CONTAINER'])){
										$titleli="$cell[YD_LOCATION]";
										$class_add_ .= " tooltip ";
									}
									if ($cell['TL_FLAG']=='Y') {
										$class_add_ .= " hidetl ";
									}
									?>
									<li tooltip="<?=$titleli?>" id_ves_voyage="<?=$id_ves_voyage?>" class_code="<?=$class_code?>" id_vessel="<?=$ID_VESSEL?>" id_bay="<?=$bay['ID_BAY']?>" 
										<?php if ($cell['NO_CONTAINER']!=''){ ?> 
											no_container="<?=$cell['NO_CONTAINER']?>"
											point="<?=$cell['POINT']?>"
											infotitle="<?=$cell['NO_CONTAINER'].', '.$cell['ID_POD'].', '.$cell['ID_OPERATOR']?>"
											cont_size="<?=$cell['CONT_SIZE']?>"
											data-pod="<?=$cell['ID_POD']?>" data-pod-color="<?=$cell['POD_COLOR']?>" data-opr="<?=$cell['ID_OPERATOR']?>" data-opr-color="<?=$cell['OPR_COLOR']?>" data-weight="<?=$cell['WEIGHT']?>" data-seq="<?=$cell['SEQUENCE']?>"
											data-status="<?=$cell['STATUS']?>"
											<?php if ($cell['CONT_40_LOCATION']==''){ ?>
											    <?php if($cell['ID_CLASS_CODE']=='TC'){ ?> 
												    class="ui-placement-disabled <?=$class_add_?>"
											    <?php }else{ ?> 
												<?php if ($cell['SEQUENCE']!=''){ ?> 
													<?php if($cell['STATUS']=='P'){ ?> 
														class="ui-plan-defaultz <?=$class_add_?>"
														<?php 
													}else{ ?> 
														class="ui-placement-default <?=$class_add_?>"
													<?php } ?>
												<?php }else{ ?> 
													class="ui-stacking-defaults <?=$class_add_?>" 
												<?php } ?>
											    <?php } ?> 
											<?php }else{ ?> 
												class="uiUnAvb <?=$class_add_?>" 
											<?php } ?>
										<?php }else if($cell['STATUS_STACK']!='A'){ ?>
											class="uiMutih <?=$class_add_2?>"
										<?php }else{ ?>
											class="ui-stacking-defaults <?=$class_add_?>" 
										<?php } ?> 
										id_bay="<?=$cell['ID_BAY']?>" id_cell="<?=$cell['ID_CELL']?>" row="<?=$cell['ROW_']?>" tier="<?=$cell['TIER_']?>" bay="<?=$cell['BAY']?>" deck_hatch="H" 
										<?php if($cell['STATUS_STACK']!='X'){ ?>style="box-shadow:0 1px 2px #616161,inset 0 -1px 1px rgba(0,0,0,0.1),inset 0 1px 1px rgba(255,255,255,0.8); 
										<?php if($cell['CONT_40_LOCATION']==''){
											if($cell['BACKGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
												background: #<?=$cell['BACKGROUND_COLOR']?>;

											<?php }
											if($cell['FOREGROUND_COLOR'] != '' && $cell['ID_CLASS_CODE']!='TC'){ ?>
												color: #<?=$cell['FOREGROUND_COLOR']?>;

											<?php }
										    }?>
										"<? } ?> >
										<div class="simbil">
											<?php 
												echo $cell['ID_SPEC_HAND'];

											if($cell['CONT_TYPE']=='HQ'){
												?>
											<div class="div-tl-simbol">
												&#9701;
											</div>	
											<?php
											}
											if($cell['TL_FLAG']=='Y'){ ?>
												<div class="div-tl-simbol">
													&#9660;			
												</div>	
											<?php } ?>
											<?php if ($cell['CONT_40_LOCATION']==''){ ?> 
												<?php if ($cell['SEQUENCE']!=''){ ?> 
													<div class="div-job-seq">
														<?php 
															if ($cell['STATUS']=='P') 
															{
																echo $cell['SEQUENCE'];
															} 
															else 
															{ 
																echo "C"; 
															} 

														?> 
													</div>
												<?php } ?> 
												<?php
												if($filter == 'SIZE'){
													echo $cell['CONT_SIZE'];
												}else if($filter == 'WEIGHT'){
													echo $cell['WEIGHT'];
												}else if($filter == 'OPERATOR'){
													echo $cell['ID_OPERATOR'];
												}else{
													echo $cell['ID_COMMODITY'];
												}  ?>
											<?php }else{ ?> 40 <?php } ?>
										</li>
										<?php
										if ($cell['STATUS_STACK'] == 'A'){
											$next_class_tier_ = "ui-stacking-left ";
										} else {
											$next_class_tier_ = "";
										}
										$index++;
									}
									?>
									<li class="<?=$next_class_tier_?> ui-small-font"><?=str_pad($cell["TIER_"],2,'0',STR_PAD_LEFT)?></li>
									<?php
								}
								?>
							</ol>
							<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<?php
					$odd = false;
					if( ($bay["JML_ROW"] % 2) == 0){
						$start = $bay["JML_ROW"];
					}else{
						$odd = true;
						$start = $bay["JML_ROW"] - 1;
					}

					$n = -2;
					for($j = 1; $j <= $bay["JML_ROW"]; $j++){
						?>
						<td style="padding: 0px; width: <?=$size-$bay["JML_ROW"]?>px;">
							<center class="ui-small-font"><?=str_pad($start,2,'0',STR_PAD_LEFT)?></center>
						</td>
						<?php
						if (($start + $n) == 0){
							if ($odd){
								$start = $start + $n;
							}else{
								$n = $n * -1;
								$start = 1;
							}
						}else if (($start + $n) < 0){
							$n = $n * -1;
							$start = 1;
						}else{
							$start = $start + $n;
						}
					}
					?>
					<td>
					</td>
				</tr>
			</table>
		</td>
		<?php
	}
	?>
	<?php
	$count_bay++;
}
?>
</tr>

<div class="spacer"></div>
<table width="130" style="float: left !important;">
	<tr>
		<td>&#9701;</td><td>:</td><td>High Qube</td>
	</tr>
	<tr>
		<td>&#9660;</td><td>:</td><td>Container TL</td>
	</tr>
	<tr>
		<td>C</td><td>:</td><td>Complete</td>
	</tr>
	<tr>
		<td>R</td><td>:</td><td>Reefer</td>
	</tr>
	<tr>
		<td>H</td><td>:</td><td>Hazard</td>
	</tr>
	<tr>
		<td>G</td><td>:</td><td>General</td>
	</tr>
	<tr>
		<td>M</td><td>:</td><td>Empty</td>
	</tr>
</table>

<table width="230" style="float: left !important;">	
	<?php
		foreach ($special_handling as $sh) 
		{
			echo '
			<tr>
				<td>
				'.$sh['ID_SPEC_HAND'].'
				</td>
				<td>:</td><td>'.$sh['NAME'].'</td>
			</tr>
			';
		}
	?>
</table>

</table>
</div>
</center>

<script>
	function setSequence_<?=$tab_id?>(id_ves_voyage, id_bay, deck_hatch){
		var data_str = $("#select-result_<?=$tab_id?>").html();
		var data_len = 0;
		var yard_str = "";
		var yard_len = 0;
//		console.log('data_str : ' + data_str);
if ($('#select-stack_'+Ext.getCmp('west_panel').getActiveTab().getId()).length==1){
	yard_str = $('#select-stack_'+Ext.getCmp('west_panel').getActiveTab().getId()).html();
}


var arr_data = data_str.split(',');
var arr_yard = yard_str.split(',');
//		console.log(arr_data);				
for(var i = 1; i <= arr_data.length; i++){
//		    console.log(arr_data[i-1]);
if($('#' + arr_data[i-1]).length < 1){
	data_len++;
}
}
if(yard_str != ''){
	yard_len = arr_yard.length;
}

if (data_len > 0 && yard_str==''){
	Ext.Msg.alert('Warning', 'Plan container from yard first!');
	return;
}
//		console.log('data_len : ' + data_len);
console.log(arr_yard.length);

if (data_len != yard_len){
	Ext.Msg.alert('Warning', 'Plan count not match');
	return;
}


data_str = "<sequence>"+data_str+"</sequence>";
yard_str = "<stack>"+yard_str+"</stack>";

var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><plan><data>"+data_str+yard_str+"</data></plan>";

var url = "<?=controller_?>outbound_view/set_sequence";

loadmask.show();
var valuescroll = $("div#<?=$tab_id?>-body").scrollLeft();
localStorage['vsc_<?=$tab_id?>'] = valuescroll;	  
Ext.Ajax.request({
	url: url,
	params: {
		id_ves_voyage: id_ves_voyage,
		id_bay: id_bay,
		deck_hatch: deck_hatch,
		xml_: xml_str
	},
	success: function(response){
		loadmask.hide();
		var res = JSON.parse(response.responseText);
		if (res[0]=='1'){
			var msg = res[1] !== undefined && res[1] != '' ? 'with ' + res[1] : '';
			Ext.MessageBox.show({
				title: 'Success',
				msg: 'Sequence Inserted! ' + msg,
				buttons: Ext.MessageBox.OK,
				fn: function (){
					var fvt = $("#filter_<?=$tab_id?>").val();
					if(fvt!='-'){
						vfvt=fvt;
					}else{
						vfvt=0;
					}
					Ext.getCmp('<?=$tab_id?>').close();
					var bay_number = localStorage['bay_number_<?=$tab_id?>'] || '';
					addTab('center_panel', 'Outbound View', id_ves_voyage+'-fvt-'+vfvt);
					$('#list_slot_'+Ext.getCmp('west_panel').getActiveTab().getId()).change();
					localStorage['vsc_'+Ext.getCmp('center_panel').getActiveTab().getId()] = valuescroll;
					if(bay_number!='undefined' && bay_number!=''){
						localStorage['bay_number_'+Ext.getCmp('center_panel').getActiveTab().getId()] = bay_number;
					}
					virtual_block_store.reload();
					if($( "#select-stack_"+Ext.getCmp('west_panel').getActiveTab().getId()).length > 0){
						$( "#select-stack_"+Ext.getCmp('west_panel').getActiveTab().getId()).html('');
					}
				}
			});
		}else{
			Ext.Msg.alert('Failed', 'Sequence Error!');
		}
	}
});

return true;
}

function unsetSequence_<?=$tab_id?>(id_ves_voyage, id_bay, deck_hatch){
	var data_str = "";
	data_str += "<id_cell>"+$("#result_<?=$tab_id?>").html()+"</id_cell>";

	var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><plan><data>"+data_str+"</data></plan>";

	var url = "<?=controller_?>outbound_view/unset_sequence";

	loadmask.show();
var valuescroll = $("div#<?=$tab_id?>-body").scrollLeft();
localStorage['vsc_<?=$tab_id?>'] = valuescroll;  
	Ext.Ajax.request({
		url: url,
		params: {
			id_ves_voyage: id_ves_voyage,
			id_bay: id_bay,
			deck_hatch: deck_hatch,
			xml_: xml_str
		},
		success: function(response){
			loadmask.hide();
			var res = JSON.parse(response.responseText);
//				console.log(res);
if (res[0]=='1'){
	var msg = res[1] !== undefined ? res[1] : '';
	Ext.MessageBox.show({
		title: 'Success',
		msg: 'Sequence Deleted!. ' + msg,
		buttons: Ext.MessageBox.OK,
		fn: function (){
			var fvt = $("#filter_<?=$tab_id?>").val();
			if(fvt!='-'){
				vfvt=fvt;
			}else{
				vfvt=0;
			}
			Ext.getCmp('<?=$tab_id?>').close();
			var bay_number = localStorage['bay_number_<?=$tab_id?>'] || '';
			addTab('center_panel', 'Outbound View', id_ves_voyage+'-fvt-'+vfvt);
			$('#list_slot_'+Ext.getCmp('west_panel').getActiveTab().getId()).change();
			localStorage['vsc_'+Ext.getCmp('center_panel').getActiveTab().getId()] = valuescroll;
			if(bay_number!='undefined' && bay_number!=''){
				localStorage['bay_number_'+Ext.getCmp('center_panel').getActiveTab().getId()] = bay_number;
			}
			virtual_block_store.reload();
		}
	});
}else{
	Ext.Msg.alert('Failed', 'Error!');
}
}
});

	return true;
}

function contInquiry_<?=$tab_id?>(e){
//	    console.log('con inquiry');
//	    console.log(e);
//	    console.log($(e).attr('no_container') + ' : ' + $(e).attr('point'));
Ext.getCmp('east_panel').expand();
Ext.getCmp('west_panel').collapse();
addTab('east_panel', 'container_inquiry', 'no_container:' + $(e).attr('no_container'), 'Container Inquiry');
}
function switchBayView_<?=$tab_id?>(bay_number,sbv){
	if(sbv=='1'){
		localStorage['bay_number_<?=$tab_id?>'] = bay_number;
	}else{
		localStorage['bay_number_<?=$tab_id?>'] = '';
	}
	$("#bay_view_<?=$tab_id?>_"+bay_number).show();
	if (bay_number%2==0){
		$("#bay_view_<?=$tab_id?>_"+(parseInt(bay_number)+1)).hide();
	}else{
		$("#bay_view_<?=$tab_id?>_"+(parseInt(bay_number-1))).hide();
	}
}
</script>

<script type="text/javascript">
	// give class for pod
	$(function(){
		var allPOD = [];
		var allCOL = [];
		$("#<?=$tab_id?>-innerCt .grid_<?=$tab_id?> li.ui-plan-defaultz").each(function(){
			var pod = $(this).data('pod');
			if (pod != '') {
				pod += '-IS-POD';
				$(this).addClass(pod);
				if (jQuery.inArray(pod, allPOD)) {
					allPOD.push(pod);
					allCOL.push($(this).data('pod-color'));
				}
			}
		});
		var style = '<style>';
		var count = 0;
		$.each(allPOD, function(id,vl){
			var rgb = allCOL[count];
			count += 1;
			style += "li."+vl+"{ background-color : "+rgb+" !important; }"
			style += "li.ui-plan-20-cell."+vl+"{background-image: linear-gradient(-45deg, white 50%, rgb(58,87,149), "+rgb+" 51%) !important;}"
		});
		style += '</style>';
		$('#for-pod-color-style').html(style);
		console.log(style);
	});
	// give class for pod
	$( document ).ready(function() {
		<?php if($valfill != '' and $filter == ''){ ?> 
			print_filter_<?=$tab_id?>('<?=$ID_VESSEL?>','<?=$id_ves_voyage?>','<?=$valfill?>');
		<?php } ?> 
	});
	$( document ).ready(function() {
		var valuescroll = localStorage['vsc_<?=$tab_id?>'] || '';
		if(valuescroll!='undefined' && valuescroll!=''){
			$("div#<?=$tab_id?>-body").scrollLeft(valuescroll);
		}
		var bay_number = localStorage['bay_number_<?=$tab_id?>'] || '';
		if(bay_number!='undefined' && bay_number!=''){
			$('.sbv_<?=$tab_id?>'+bay_number).click();
		}
		
		if ($('.x-tab-inner.x-tab-inner-center:contains("<?=$id_ves_voyage?>-fvt-")').length > 0) {
			$('.x-tab-inner.x-tab-inner-center:contains("<?=$id_ves_voyage?>-fvt-")').text(function () {
				$(this).text("Outbound View - <?=$id_ves_voyage?>"); 
			});
		}
	});	 
</script>

<div id="for-pod-color-style"></div>