<style>
.triangle {
  font-size: 8px; 
  width: 0; 
  height: 0; 
  border-left: 10px solid transparent;
  border-right: 10px solid transparent;
  border-bottom: 14px solid #fb9889;
  float:right;
  
}
.texts{
  color:black;
  text-align:center;
  position:relative;
  margin-left:-6px;
  margin-top:2px;
}
.x-tab-default-active {
	background-color: #4b9cd7 !important;
}
.x-tab-bar-default {
	background-color: #3892d3 !important;
}
.x-tab-default-top {
	background-color: transparent !important; 
}
.x-tab-inner {
	color: white !important;
}
</style>

<?php
/**
 * Sanitizing Output
 */
function sanitize_output($buffer) {
	$search = array(
		'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
		'/[^\S ]+\</s',  // strip whitespaces before tags, except space
		'/(\s)+/s'  // shorten multiple whitespace sequences
	);

	$replace = array(
		'>',
		'<',
		'\\1'
	);

	$buffer = preg_replace($search, $replace, $buffer);

	return $buffer;
}


ob_start("sanitize_output");

/**
 * Layout mini version
 */
$L	= $width * $height;
$s = 19;
$s_h = 7;
$s_h_white = 7;
$grid_width = ($s*$width)+8; // 8 fix value
$grid_height = ($s_h*$height)+8;

$border_color = '#8C92AC';
$border_color_ship = '#ffac00';

$grid_berth_width = (($berth['total_length']/6)*$s)+8;
$grid_berth_height = (24*$s_h)+8;

$equipment_data_json = json_encode($equipment);
?>

<style type="text/css">
#center_content_<?=$tab_id?> {
	margin-left: 0px;
	position: relative;
}

.vessel_selector_<?=$tab_id?>{
    position: relative;
    float: left;
    display: inline-block;
    margin: 0 0 0 10px;
}

div#box_vessel_berth_<?=$tab_id?>{
    display: inline-block;
    position: absolute;
    top: 0;
    width: max-content;
}

#selectable_<?=$tab_id?> {
	list-style-type: none; margin: 0; padding: 0;
}

div.grid_berth_<?=$tab_id?> {
	width:  <?php echo $grid_berth_width."px"?>;
	font-size: 5px;
	position: relative;
}

div.grid_<?=$tab_id?> {
	width:  <?php echo $grid_width."px"?>;
	height: <?php echo $grid_height."px"?>;
	font-size: 5px;
	position: relative;
}

.div_cell_block_<?=$tab_id?>{
	float: left;
	border-top: 1px solid <?=$border_color?>;
	border-left: 1px solid <?=$border_color?>;
	/*border: 1px solid #e8edff;*/
	font-size: 2em; 
	text-align: center;
	width: <?php echo $s."px"?>;
	height: <?php echo $s_h."px"?>;
}

/*
.div_cell_block_<?=$tab_id?>[data-position=H]{
	width: <?php echo $s."px"?>;
	height: <?php echo $s_h."px"?>;
}

.div_cell_block_<?=$tab_id?>[data-position=V]{
	width: <?php echo $s_h."px"?>;
	height: <?php echo $s."px"?>;
}
*/

.div_cell_block_40_<?=$tab_id?>{
	float: left;
	border-top: 1px solid <?=$border_color?>;
	border-left: 1px solid <?=$border_color?>;
	/*border: 1px solid #e8edff;*/
	font-size: 2em; 
	text-align: center;
	width: <?php echo ($s*2)."px"?>;
	height: <?php echo $s_h."px"?>;
}

/*
.div_cell_block_40_<?=$tab_id?>[data-position=H]{
	width: <?php echo ($s*2)."px"?>;
	height: <?php echo $s_h."px"?>;
}

.div_cell_block_40_<?=$tab_id?>[data-position=V]{
	width: <?php echo $s_h."px"?>;
	height: <?php echo ($s*2)."px"?>;
}
*/

.div_cell_block_ship_<?=$tab_id?>{
	float: left;
	width: <?php echo $s."px"?>;
	height: <?php echo $s_h."px"?>;
	border-top: 1px solid <?=$border_color_ship?>;
	border-left: 1px solid <?=$border_color_ship?>;
	/*border: 1px solid #e8edff;*/
	font-size: 2em; 
	text-align: center;
}

.notexist { background: #e8edff; }

.cell_block_right{
	border-left: 1px solid <?=$border_color?>;
}

.cell_block_bottom{
	border-bottom: 1px solid <?=$border_color?>;
}

.cell_block_left{
	border-right: 1px solid <?=$border_color?>;
}

.cell_block_bottom_ship{
	border-bottom: 1px solid <?=$border_color_ship?>;
}

.cell_block_left_ship{
	border-right: 1px solid <?=$border_color_ship?>;
}

.div_cell_block_zero{
	float: left;
	width: 0px;
	height: <?php echo $s_h."px"?>;
	font-size: 2em; 
	text-align: center;
}

.exist_<?=$tab_id?>:hover{
	background: #FFBF00;/*#d0dafd;*/
	cursor: pointer;
}

.ship_exist_<?=$tab_id?>:hover{
	background: #FFBF00;/*#d0dafd;*/
	cursor: pointer;
}

.div_cell_block_whitespace{
	float: left;
	width: <?php echo $s."px"?>;
	height: <?php echo $s_h_white."px"?>;
	font-size: 1.2em; 
	text-align: center;
}

/* equipment group */
#equipment-group_<?=$tab_id?> {
	position: absolute;
	top:0px;
	left:3px;
}

/* equipment group */
#equipment_berth-group_<?=$tab_id?> {
	position: absolute;
	top:0px;
	left:3px;
}

.equip-rtg {
	position:absolute;
}

.equip-rtg img {
	opacity:0.8;
}

.equip-qc {
	position:absolute;
}

.equip-qc img {
	opacity:0.8;
}

.equip-sc {
	position:absolute;
}

.equip-sc img {
	opacity:0.8;
}

.equip-rs {
	position:absolute;
}

.equip-rs img {
	opacity:0.8;
}
	
.equip-name_<?=$tab_id?> {
	font-size: 6pt;
	position: absolute;
	width: 50px;
	color: #fff;
    left: 0;
    transform: rotate(270deg);
}
	
.equip-name_qc_<?=$tab_id?> {
	font-size: 6pt;
    position: absolute;
    width: 50px;
    white-space: nowrap;
    color: #fff;
    left: 0;
    bottom: 100px;
    transform: rotate(270deg);
}

/******* berth ******/
.berth {
	display: inline-block;
	background-image: url('<?= IMG_ ?>assets/background-shading-2.png');
	/*float: left;*/
	text-align: center;
	padding: 3px;
	border: 1px solid;
	border-radius: 5px;
}

/******* coloring filter ******/
.tier1{ background: #A1CAF1; }
.tier2{ background: #6DA9E3; }
.tier3{ background: #297CCC; }
.tier4{ background: #014585; }
.tier5{ background: #014585; }
.tier6{ background: #014585; }

.stack_ship{ background: #f9e559; }

/******* Container in Yard View ******/
.ciy_tab_switch_<?=$tab_id?>, .cis_tab_switch_<?=$tab_id?>{
	height: 40px;
	margin-top: 20px;
	text-align: center;
	width: 495px; /*@todo*/
}

.ciy_tab_switch_<?=$tab_id?> span {
	display: inline-block;
	width: 50px;
	height: 35px;
	margin-left: 5px;
	margin-right: 5px;
	padding: 10px;
	border-radius: 5px;
	color: #214FC6;
	text-align: center;
	background-color: #A1CAF1;
	font-weight: bold;
}

.ciy_tab_switch_<?=$tab_id?> .passive_<?=$tab_id?> {
	background-color: #007FFF;
	cursor: pointer;
}

.ciy_header_<?=$tab_id?> td {
	border: none;
	min-width: 80px;
	padding: 5px;
	text-align: center;
	font-family: helvetica, arial, verdana, sans-serif;
	font-size: 9px;
}

.cis_header_<?=$tab_id?> td {
	border: none;
	min-width: 60px;
	padding: 5px;
	text-align: center;
	font-family: helvetica, arial, verdana, sans-serif;
	font-size: 9px;
}

.ciy_table_<?=$tab_id?> table, .ciy_table_<?=$tab_id?> th, .ciy_table_<?=$tab_id?> td {
	border: 1px solid black;
}

.ciy_table_<?=$tab_id?> {
	font-family: helvetica, arial, verdana, sans-serif;
	font-size: 9px;
}

.ciy_table_<?=$tab_id?> table {
	width:80px;
	padding:.5em;
}

.cis_table_<?=$tab_id?> table, .cis_table_<?=$tab_id?> th, .cis_table_<?=$tab_id?> td {
	border: 1px solid black;
}

.cis_table_<?=$tab_id?> {
	font-family: helvetica, arial, verdana, sans-serif;
	font-size: 9px;
}

.cis_table_<?=$tab_id?> table {
	width:60px;
	padding:.5em;
}

.ciy_data_<?=$tab_id?> .data_<?=$tab_id?> {
	min-width: 80px;
	height: 80px;
	padding: 3px;
	background: #e8edff;
}

.ciy_data_<?=$tab_id?> .stacking_<?=$tab_id?> {
	background: #FF8040;
}

.ciy_data_<?=$tab_id?> .right_header_<?=$tab_id?> {
	border: none;
	min-width: 10px;
	background: #FFFFFF;
	padding-left: 10px;
}

.cis_data_<?=$tab_id?> .data_<?=$tab_id?> {
	min-width: 60px;
	height: 60px;
	padding: 3px;
	background: #e8edff;
}

.cis_data_<?=$tab_id?> .stacking_<?=$tab_id?> {
	background: #FF8040;
}

.cis_data_<?=$tab_id?> .right_header_<?=$tab_id?> {
	border: none;
	min-width: 10px;
	background: #FFFFFF;
	padding-left: 10px;
}

.ciy_footer_<?=$tab_id?> td {
	border: none;
	min-width: 80px;
	/*padding: 5px;*/
	text-align: center;
	font-family: helvetica, arial, verdana, sans-serif;
	font-size: 9px;
}

.ciy_footer_<?=$tab_id?> td a {
    color: #000000;
    text-decoration: none;
}

.hide {display: none;}

/**** Filter Container in Yard ****/
@media screen and (-webkit-min-device-pixel-ratio:0) {  /*safari and chrome*/
	.filter_ciy_<?=$tab_id?> {
		height:32px;
		line-height:32px;
		background:#f4f4f4;
	} 
}

.filter_ciy_<?=$tab_id?>::-moz-focus-inner { /*Remove button padding in FF*/ 
	border: 0;
	padding: 0;
}

@-moz-document url-prefix() { /* targets Firefox only */
	.filter_ciy_<?=$tab_id?> {
		padding: 16px 0!important;
	}
}

@media screen\0 { /* IE Hacks: targets IE 8, 9 and 10 */
	.filter_ciy_<?=$tab_id?> {
		height:32px;
		line-height:30px;
	}
}

.ssv_cat_group{
    background: #FFFFFF;
    border: 1px solid #000;
    font-size: 15px;
    font-weight: bold;
    text-align: left;
    padding-left: 5px;
}

.equip-qc:hover > .equip-qc-name, 
.equip-sc:hover > .equip-sc-name, 
.equip-rs:hover > .equip-rs-name {
    font-size: 15px;
    z-index:2;
    transform: rotate( 0deg );
    width: max-content;
    color: white;
}

.equip-qc:hover > .equip-qc-name span.equip-block-name, 
.equip-sc:hover > .equip-sc-name span.equip-block-name, 
.equip-rs:hover > .equip-rs-name span.equip-block-name {
    background: black!important;
    padding: 5px;
}

span.equip-name_terminal_monitoring_2.equip-rtg-name {
    transform: rotate(0deg);
}

</style>

<script>
$(document).ready(function() {
	$('.tooltip_info_<?=$tab_id?>').tooltipster({
		theme: 'tooltipster-punk',
		contentAsHTML: true,
		interactive: true,
		trigger: 'custom',
		triggerOpen: {
			click: true
		},
		triggerClose: {
			click: true,
			scroll: true,
			mouseleave: true
		},
		content: 'Loading...',
		functionBefore: function(instance, helper) {
			var $origin = $(helper.origin);
			console.log($origin);
			var id_ves_voyage = $origin.attr("id_ves_voyage");
			if ($origin.data('loaded') !== true) {
				$.post('<?=controller_?>terminal_monitoring/load_vessel_berth_stevedoring_info',{ id_ves_voyage: id_ves_voyage}, function(data) {
					var html_text = "";
					html_text += "<h4>Vessel Info: " + data.vessel_info.VESSEL_NAME + " " + data.vessel_info.VOY_IN + "/" + data.vessel_info.VOY_OUT + "</h4>";
					html_text += "<h4><a style='color: white;' href='javascript:show_container_list_<?=$tab_id?>(\"I\", \""+id_ves_voyage+"\")'>Inbound</a>" + "<br/>Plan = " + data.plan.DISCHARGE + "<br/>Real = " + data.real.DISCHARGE + "</h4>";
					html_text += "<h4><a style='color: white;' href='javascript:show_container_list_<?=$tab_id?>(\"E\", \""+id_ves_voyage+"\")'>Outbound</a>" + "<br/>Plan = " + data.plan.LOADING + "<br/>Real = " + data.real.LOADING + "</h4>";
					instance.content(html_text);
					$origin.data('loaded', true);
				}, 'json');
			}
		},
		functionAfter: function(instance, helper) {
			var $origin = $(helper.origin);
			$origin.data('loaded', false);
		}
	});
});
</script>

<div id='container_in_yard_view<?=$tab_id?>'></div>
<div id='container_in_ship_view_<?=$tab_id?>'></div>
<div id='container_list_<?=$tab_id?>'></div>

<center id="center_content_<?=$tab_id?>" class='mainmon'>
<div class="grid_berth_<?=$tab_id?>">
	<table border="0" width="100%" style="margin-bottom:30px;">
		<tr align="center" valign="bottom" style="background-color: #63c5d2;height: <?php echo $grid_berth_height."px"?>">
<?php
foreach ($berth['kade_list'] as $key => $value) {
?>
			<td colspan=2 id='berth_<?=$tab_id?>_<?=$value['ID_KADE']?>' class='berth' style='margin: <?php echo $grid_berth_height."px"?> 0px 0px 0px;width: <?=($value['length']/$berth['total_length'])*100?>%;height: 10px;' title='KADE: <?=$value['KADE_NAME']?>' kade_start='<?=$value['START_METER']?>' kade_length='<?=$value['length']?>'>
			</td>
<?php
}
?>
		</tr>
		<tr>
<?php
foreach ($berth['kade_list'] as $key => $value) {
?>
			<td colspan=1 style='display: inline-block;margin: 0px 0px;font-size: 8px;width: <?=($value['length']/$berth['total_length'])*50?>%;'>
			<?=$value['START_METER']?>
			</td>
			<td colspan=1 style='display: inline-block;margin: 0px 0px;font-size: 10px;width: <?=($value['length']/$berth['total_length'])*50?>%;'>
			<b>KADE: <?=$value['KADE_NAME']?></b>
			</td>
<?php
}
?>
		</tr>
	</table>
	
<div id="box_vessel_berth_<?=$tab_id?>">
<?php
//print_r($vessel);
foreach ($vessel as $key => $value) {
	$max_row_draw = 15;
	$along_side=$value['ALONG_SIDE'];
	$dShip=$value['vessel_profile']['BAY_COUNT'];
	$nRows = $value['vessel_profile']['MAX_ABOVE_ROWS'];
	$dbay=$dShip*$s;
	$hShip=$max_row_draw*$s_h;
	$lfBay=0;
	$offsetstern = 90;
	$offsetbow = 100;
	$offsetleft = 0;
	$offsettop = ((($max_row_draw-$nRows)/2)*$s_h)+3;
	if ($along_side=='S'){
		$rgBay=$offsetstern+$dbay;
		$offsetleft = $offsetstern;
	}else if ($along_side=='P'){
		$rgBay=$offsetbow+$dbay;
		$offsetleft = $offsetbow;
	}
	
	$total_viewport_width = $offsetbow+$offsetstern+$dbay;
	$total_viewport_height = $hShip;
?>
	<div id='vessel_berth_<?=$tab_id?>_<?=$value['ID_VES_VOYAGE']?>' class='vessel_selector_<?=$tab_id?> tooltip_info_<?=$tab_id?>' id_ves_voyage='<?=$value['ID_VES_VOYAGE']?>' style="width: <?=$total_viewport_width?>px;height: <?=$total_viewport_height?>px;top:30px;">
			<div style="font-size: 12px"><?=$value['VESSEL_NAME']?></div> <!-- here -->
			<?php
				if ($along_side=='S'){
			?>
			<div id="vesselleft" style="position: absolute;left:<?=$lfBay;?>px; transform: rotateY(180deg);background-image:url('images/ves_3.png');background-position: center bottom;background-repeat:repeat-x;height:<?=$hShip?>px;width:<?=$offsetstern?>px;"></div>
			<div id="vesselright" style="position: absolute;left:<?=$rgBay?>px; transform: rotateY(180deg); background-image:url('images/ves_1.png');background-position: center bottom;background-repeat:repeat-x;height:<?=$hShip?>px;width:<?=$offsetbow?>px;"></div>
			<?php
				}else if ($along_side=='P'){
			?>
			<div id="vesselleft" style="position: absolute;left:<?=$lfBay?>px;background-image:url('images/ves_1.png');background-position: center bottom;background-repeat:repeat-x;height:<?=$hShip?>px;width:<?=$offsetbow?>px;"></div>
			<div id="vesselright" style="position: absolute;left:<?=$rgBay;?>px;background-image:url('images/ves_3.png');background-position: center bottom;background-repeat:repeat-x;height:<?=$hShip?>px;width:<?=$offsetstern?>px;"></div>
			<?php
				}
			?>
			<div id="vesselcenter" style="position: absolute;left:<?=$lfBay+$offsetleft;?>px;background-image:url('images/ves_2.png');background-position: center bottom;background-repeat:repeat-x;height:<?=$hShip?>px;width:<?=$dbay?>px;">
				<div style="margin-top: <?=$offsettop?>px">
			<?php
				for ($i=1; $i<=$nRows; $i++){
					if ($along_side=='P'){
						$baynum = 1;
					}else if ($along_side=='S'){
						$baynum = ($dShip*2)-1;
					}
					$class = "";
					if ($i==$nRows){
						$class = "cell_block_bottom_ship";
					}
					for ($j=1; $j<=$dShip; $j++){
						if ($j==$dShip){
							$class .= " cell_block_left_ship";
						}
			?>
					<div class="div_cell_block_ship_<?=$tab_id?> ship_exist_<?=$tab_id?> <?=$class?> stack_ship" title="Bay: <?=$baynum?>" data-idvesvoyage="<?=$value['ID_VES_VOYAGE']?>" data-bay="<?=$baynum?>" data-row="<?=$i?>"></div>
			<?php
						if ($along_side=='P'){
							$baynum += 2;
						}else if ($along_side=='S'){
							$baynum -= 2;
						}
					}
				}
			?>
				</div>
			</div>
	</div>
	
	<script type="javascript">
	console.log('along side: <?=$along_side?>, bay count: <?=$dShip?>, row count: <?=$nRows?>');
	if($("#berth_<?=$tab_id?>_<?=$value['ID_KADE']?>").length > 0){
	    var offset_pos_<?=$value['ID_KADE']?> = $("#berth_<?=$tab_id?>_<?=$value['ID_KADE']?>").offset();
	    var length_<?=$value['ID_KADE']?> = $("#berth_<?=$tab_id?>_<?=$value['ID_KADE']?>").attr('kade_length');
	    var start_<?=$value['ID_KADE']?> = $("#berth_<?=$tab_id?>_<?=$value['ID_KADE']?>").attr('kade_start');
	    var width_<?=$value['ID_KADE']?> = $("#berth_<?=$tab_id?>_<?=$value['ID_KADE']?>").width();
	    console.log("info kade:");
	    console.log($("#berth_<?=$tab_id?>_<?=$value['ID_KADE']?>").position());
	    console.log(offset_pos_<?=$value['ID_KADE']?>);
	    console.log(length_<?=$value['ID_KADE']?>);
	    console.log(start_<?=$value['ID_KADE']?>);
	    console.log(width_<?=$value['ID_KADE']?>);
	    var x_pos_<?=$value['ID_VES_VOYAGE']?> = offset_pos_<?=$value['ID_KADE']?>.left+(width_<?=$value['ID_KADE']?>*(<?=$value['START_METER']?>-start_<?=$value['ID_KADE']?>)/length_<?=$value['ID_KADE']?>);
	    var y_pos_<?=$value['ID_VES_VOYAGE']?> = offset_pos_<?=$value['ID_KADE']?>.top-$("#vessel_berth_<?=$tab_id?>_<?=$value['ID_VES_VOYAGE']?>").height();
	    console.log(x_pos_<?=$value['ID_VES_VOYAGE']?>);
	    console.log(y_pos_<?=$value['ID_VES_VOYAGE']?>);
	    console.log('TEST: ' + y_pos_<?=$value['ID_VES_VOYAGE']?>);
	    $("#vessel_berth_<?=$tab_id?>_<?=$value['ID_VES_VOYAGE']?>").offset({left: x_pos_<?=$value['ID_VES_VOYAGE']?>/*, top: y_pos_<?=$value['ID_VES_VOYAGE']?>*/});
	}
	</script>
<?php
}
?>
</div>
	<div id="equipment_berth-group_<?=$tab_id?>">
	</div>
</div>
<!-- end berth -->
<!-- yard -->
<div class="grid_<?=$tab_id?>">
	<table border="0" width="100%">
		<tr align="center" valign="top">
			<td align="center" valign="middle" style="padding-left: 2px; padding-right: 2px;">
				<div id="selectable_<?=$tab_id?>">
					<!-- HATI HATI menghapus setiap karakter di sini -->
					<?php
						$count_block = 0;
						$j = 1; $p = 0; $l = 0;
						// $p is INDEX in data Monitoring

						$block_array = array();
						$flag_40f = false;
						$flagging_border_right = false;

						for($i = 1; $i <= $L; $i++){
							
							$block_flag = 0;
							$m = ($width*$j) + 1;
						
							if($i - 1 == $index[$p]) {
								if (!in_array($block_name[$p],$block_array)) {
									$block_array[] = $block_name[$p];
									$count_block += 1;
									$block_flag = 1;
								}
								$arr_placement = explode('|', $placement[$p]);
								$placement_total = $arr_placement[0];
								$placement_size = $arr_placement[1];
								// 2 is for color
								
								// Filter
								$filter_pod = $arr_placement[3];
								$filter_ves = $arr_placement[4];
								$filter_carr = $arr_placement[5];
								$filter_ei = $arr_placement[6];
								
								// set color
								if ($arr_placement[2] != null){
									$placement_color = $arr_placement[2];
								} else {
									$placement_color = '#69c';
								}
					?>
								<div class="<?php
									if ($placement_size == '40' && !$flag_40f){
										$flagging_border_right = true;
										if (substr( $orientation[$p], 1, 1) == 'L') {
											echo "div_cell_block_zero ";
										}else{
											echo "div_cell_block_40_".$tab_id." ";
										}
										$flag_40f = true;
									} else if ($flag_40f) {
										$flagging_border_right = true;
										if (substr( $orientation[$p], 1, 1) == 'L') {
											echo "div_cell_block_40_".$tab_id." ";
										}else{
											echo "div_cell_block_zero ";
										}
										$flag_40f = false;
									} else {
										$flagging_border_right = true;
										echo "div_cell_block_".$tab_id." ";
									}
									if($placement_total>0){
										echo "exist_".$tab_id." tier" .$placement_total. " ";
									} else {
										echo "notexist ";
									}
									
									// case border bottom
									if (
										($position[$p] == 'H' && (
										($row_[$p] == $max_row[$p] && substr( $orientation[$p], 0, 1) == 'T') 
										|| 
										($row_[$p] == '1' && substr( $orientation[$p], 0, 1) == 'B')
										))
										||
										($position[$p] == 'V' && (
										($slot_[$p] == $max_slot[$p] && substr( $orientation[$p], 0, 1) == 'T') 
										|| 
										($slot_[$p] == '1' && substr( $orientation[$p], 0, 1) == 'B')
										))
									){
										echo "cell_block_bottom ";
									}
									?>" 

									title="<?php echo $block_name[$p] ?>"

									<?php 
									if ($block_flag==1) 
										echo "id='block_name_".$tab_id."_".$count_block."'";
									?> 

									class="ui-stacking-default" 
									data-index="<?=$i-1?>" 
									data-block="<?=$block[$p]?>" 
									data-slot="<?=$slot_[$p]?>" 
									data-row="<?=$row_[$p]?>" 
									data-max_row="<?=$max_row[$p]?>"
									data-orientation="<?=$orientation[$p]?>"
									data-pod="<?=$filter_pod?>" 
									data-ves="<?=$filter_ves?>" 
									data-opr="<?=$filter_carr?>" 
									data-class_code="<?=$filter_ei?>"
									data-placement="<?=$placement_total?>"
									data-position="<?=$position[$p]?>"

									<?php 
									$colorpodbg="";
									if(!empty($filter_pod) and trim($filter_pod)!=''){
										$podcpolor   = get_pod_color($filter_pod,'BACKGROUND_COLOR');
										$colorpodbg="background:#$podcpolor;";
									}
									if (($i%$m) == 0){ 
										$j++;
										echo "style=\"clear: both;$colorpodbg\"";
									}else{
										echo "style=\"$colorpodbg\"";
									}
									?>
								>
								</div>
						<?php 
								$p++;
							}
							else
							{
						?>
							<div class="div_cell_block_whitespace 
								<?php if($flagging_border_right){
									echo "cell_block_right";
									$flagging_border_right = false;
								}?>" 
								data-index="<?=$i-1?>"
								<?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>>
								<? if (($i-1)==$label[$l]){
									echo $label_text[$l];
									$l++; 
								} ?>
							</div>
					<?php 
							}
						}
					?>
					<!-- End HATI HATI -->
				</div>
			</td>
		</tr>
	</table>
	<div id="equipment-group_<?=$tab_id?>"></div>
</div>
</center>

<div style="clear:both"></div>

<?php
ob_end_flush();
?>

<script type="text/javascript">
/**
 * Global Variables
 */
var ux_singlestackview_<?=$tab_id?>;
var ux_tabpanelstackview_<?=$tab_id?>;
var counter_id_ssv_<?=$tab_id?> = 0;

var ux_shipview_<?=$tab_id?>;
var ux_tabpanelshipview_<?=$tab_id?>;
var counter_id_sv_<?=$tab_id?> = 0;

var ux_container_list_<?=$tab_id?>;
var ux_tabpanel_container_list_<?=$tab_id?>;
var counter_id_cl_<?=$tab_id?> = 0;

// store equipment data json
var equipmentDataJson_<?=$tab_id?> = <?php echo $equipment_data_json ?>;// console.log(equipmentDataJson_<?=$tab_id?>);

/**
 * Starting point applications
 */
$(document).ready(function(){
	var count_block_<?=$tab_id?> = <?=$count_block?>;
	$("#<?=$tab_id?>-body").scrollTop(0);
	
	// render block name
	for (var i=0;i<count_block_<?=$tab_id?>;i++){
		var xoffset = $("#block_name_<?=$tab_id?>_"+(i+1)).offset().left;
		var yoffset = $("#block_name_<?=$tab_id?>_"+(i+1)).offset().top;
		$("#center_content_<?=$tab_id?>").append('<div id="block_name_container_<?=$tab_id?>_'+i+'" style="position:absolute;font-size:1em;"><b>'+$("#block_name_<?=$tab_id?>_"+(i+1)).attr('title')+'</b></div>');
		$("#block_name_container_<?=$tab_id?>_"+i).offset({ top: yoffset-30, left: xoffset});
	}

	// render equipment
	$(document).ready(function() {
		renderEquipment<?=$tab_id?>();
	});
});

$(".ship_exist_<?=$tab_id?>").on('click', function(event){
	event.stopPropagation();
	
	counter_id_sv_<?=$tab_id?>++;
	var idvesvoyageClick = $(this).data('idvesvoyage');
	var bayClick = $(this).data('bay');
	
	loadmask.show();
	Ext.Ajax.request({
		url: '<?=controller_?>terminal_monitoring/load_ship_confirm_data/',
		method: 'POST',
		scope:this,
		params: {
			id_ves_voyage: idvesvoyageClick,
			bay: bayClick
		},
		success: function(response, request){
			loadmask.hide();
			
			var resData = Ext.decode(response.responseText);
			
			var filterContent = "Bay: <select class='filter_ciy_<?=$tab_id?> filter_bay_<?=$tab_id?>' data-idtabpanel='"+counter_id_sv_<?=$tab_id?>+"'>";
			for(var keyBay in resData.filter_bay){
				var case_selected = '';
				if (keyBay == bayClick) { case_selected = 'selected'; }
				filterContent += "<option value='"+resData.filter_bay[keyBay].BAY+"' "+case_selected+ ">"+keyBay+"</option> ";
			}
			filterContent += "</select>";
			
			var bodyContent = createShipBayViewFrame_<?=$tab_id?>(resData, bayClick, counter_id_sv_<?=$tab_id?>);

			var htmlContent = "<div id='cis_container_<?=$tab_id?>_"+counter_id_sv_<?=$tab_id?>+"'><div class='cis_tab_switch_<?=$tab_id?>'>"
					+ "<div style='float:left; margin-left:2px;' data-idwin="+counter_id_sv_<?=$tab_id?>+">" + filterContent + "</div>"
				+ "</div>"
				+ bodyContent
			+ "</div>";
			
			var tabComponent = {
				itemId: 'tabpanel_shipview_<?=$tab_id?>-' + counter_id_sv_<?=$tab_id?>,
				title: 'Bay View '+ idvesvoyageClick + " " + generateTitlePostfix_<?=$tab_id?>(counter_id_sv_<?=$tab_id?>),
				html: htmlContent,
				closable: true
			};
			
			if (!ux_shipview_<?=$tab_id?>){
				ux_tabpanelshipview_<?=$tab_id?> = Ext.create('Ext.tab.Panel', {
					tabPosition: 'top',
					items: [tabComponent]
				});
				ux_shipview_<?=$tab_id?> = Ext.create('Ext.Window', {
					id: 'win_cis_view<?=$tab_id?>',
					title: 'Container in Ship View',
					width: resData.configs.MAX_ROW * 62.5 + 40, // konstanta lebar
					height: resData.configs.MAX_TIER * 62.5 + 180, // konstanta tinggi
					plain: true,
					headerPosition: 'top',
					constrain: true,
					renderTo: 'container_in_ship_view_<?=$tab_id?>',
					listeners:{
						close:function(){
							ux_shipview_<?=$tab_id?> = null; 
							counter_id_sv_<?=$tab_id?> = 0;
						},
						scope:this
					},
					items: [ux_tabpanelshipview_<?=$tab_id?>]
				}).show();
			} else {
				ux_tabpanelshipview_<?=$tab_id?>.add(tabComponent);
				ux_tabpanelshipview_<?=$tab_id?>.setActiveTab('tabpanel_shipview_<?=$tab_id?>-' + counter_id_sv_<?=$tab_id?>).show();
			}
			
			// insert container content
			// fillBodyContent_<?=$tab_id?>(resData, counter_id_sv_<?=$tab_id?>)
		},
		failure: function(response, request){
			Ext.MessageBox.alert('Error', 'Please try again. ' + response.status);
		}
	});
});

/** 
 * Change Block event binding
 */
$('#container_in_yard_view<?=$tab_id?>').on('change', '.filter_block_<?=$tab_id?>', function(event){
	var maxSlot = $(this).find('option:selected').data('slot'); 
	// console.log('max slot: ' + maxSlot);
		
	// update data filter
	var filterSlot = $(this).parent().children('.filter_slot_<?=$tab_id?>');
	filterSlot.empty();
	for(var i=1;i<=maxSlot;i++){
		var option = $('<option></option>').attr("value", i).text(i);
		filterSlot.append(option);
	}
	
	var selectedIdTabPanel = filterSlot.data('idtabpanel');
	var selectedIdWindow = $(this).parent().data('idwin');
	var selectedIdBlock = $(this).find('option:selected').val();
	var selectedBlock = $(this).find('option:selected').text();
	var selectedSlot = $(this).parent().children('.filter_slot_<?=$tab_id?>').val();
	// console.log('select: ' + selectedBlock + ',' + selectedSlot + ',' + selectedIdWindow);
	
	changeSingleStackViewContent_<?=$tab_id?>(selectedIdBlock,selectedBlock, selectedSlot, selectedIdWindow, selectedIdTabPanel);
});

/** 
 * Change Slot event binding
 */
$('#container_in_yard_view<?=$tab_id?>').on('change', '.filter_slot_<?=$tab_id?>', function(event){
	var filterSlot = $(this).parent().children('.filter_slot_<?=$tab_id?>');
	var selectedIdTabPanel = filterSlot.data('idtabpanel');
	var selectedIdWindow = $(this).parent().data('idwin');
	var selectedIdBlock = $(this).siblings().find('option:selected').val();
	var selectedBlock = $(this).siblings().find('option:selected').text();
	var selectedSlot = $(this).parent().children('.filter_slot_<?=$tab_id?>').val();
	console.log('select: ' + selectedBlock + ',' + selectedSlot);

	//console.log('ganti');
	
	changeSingleStackViewContent_<?=$tab_id?>(selectedIdBlock,selectedBlock, selectedSlot, selectedIdWindow, selectedIdTabPanel);
});

/** 
 * Change Single Stack View Content
 */
function changeSingleStackViewContent_<?=$tab_id?>(selectedIdBlock,selectedBlock, selectedSlot, selectedIdWindow, selectedIdTabPanel){
	var currentLoadmask = new Ext.LoadMask(Ext.getCmp('win_ciy_view<?=$tab_id?>'), {msg:"Loading..."});

	currentLoadmask.show();

	var yardClick 		= $('#list_yard_<?=$tab_id?>').val();
	var idBlockClick	= selectedIdBlock;
	var blockClick		= selectedBlock;
	var slotClick		= selectedSlot;
	var sizeClick 		= '20';


	Ext.Ajax.request({
		url: '<?=controller_?>terminal_monitoring/load_yard_stacking_data/' 
			+$('#list_yard_<?=$tab_id?>').val()+'/'+selectedIdBlock+'/'+selectedBlock+'/'+selectedSlot+'/20',
		method: 'POST',
		scope:this,
		success: function(response, request){
			currentLoadmask.hide();
			var resData = Ext.decode(response.responseText);
			 console.log('changeSingleStackViewContent_');

			loadmask.hide();
			
			var resData = Ext.decode(response.responseText);
			
			var filterContent = "Block: <select class='filter_ciy_<?=$tab_id?> filter_block_<?=$tab_id?>' data-idtabpanel='"+selectedIdTabPanel+"'>";
			for(var keyBlock in resData.filter_block){
				var case_selected = ''; if (keyBlock == blockClick) { case_selected = 'selected'; }
				filterContent += "<option value='"+resData.filter_block[keyBlock].ID_BLOCK+"'"+case_selected
					+ " data-slot=" + resData.filter_block[keyBlock].SLOT
					+ " >"+keyBlock+"</option> ";
			}
			filterContent += "</select>";
			
			filterContent += " Slot: <select class='filter_ciy_<?=$tab_id?> filter_slot_<?=$tab_id?>' id='list_slot_<?=$tab_id?>'data-idtabpanel='"+selectedIdTabPanel+"' >";
			// console.log(Number(resData.filter_block[blockClick].SLOT));
			for(var idx=1;idx<=Number(resData.filter_block[blockClick].SLOT);idx++){
				var case_selected = ''; 
				if (idx == slotClick) { case_selected = 'selected'; }
				filterContent += "<option value='"+idx+"'"+case_selected+">"+idx+"</option> ";
			}
			filterContent += "</select>";

			var tab_id_p = '<?=$tab_id?>("p")';
			var tab_id_n = '<?=$tab_id?>("n")';

			filterContent += "&nbsp;<a href='#' onclick='nextPrev_onClick_"+tab_id_p+"' title='Previous'><img src='<?=IMG_?>icons/prev.png' width='20px'/><a/>";
			filterContent += "&nbsp;<a href='#' onclick='nextPrev_onClick_"+tab_id_n+"' title='Next'><img src='<?=IMG_?>icons/next.png' width='20px'/><a/>";
			
			console.log('tab_id_p'+tab_id_p);
			console.log('tab_id_n'+tab_id_n);
			// var switchContent = generateSwitchContent_<?=$tab_id?>(resData, blockClick, slotClick);
			var bodyContent = generateBodyContent_<?=$tab_id?>(resData, blockClick, slotClick, selectedIdTabPanel);

			var htmlContent = "<div id='ciy_container_<?=$tab_id?>_"+selectedIdTabPanel+"' class='ciy_container'><div class='ciy_tab_switch_<?=$tab_id?>'>"
					+ "<div style='float:left; margin-left:2px;' data-idwin="+selectedIdTabPanel+">" + filterContent + "</div>"
					/*+ "<div style='float:right' class='ciy_button_switch_<?=$tab_id?>_"+selectedIdTabPanel+"'>" + switchContent + "</div>"*/
				+ "</div>"
				+ bodyContent
			+ "</div>";

			//console.log(htmlContent);

			console.log('tabpanel_singlestack_<?=$tab_id?>-' + selectedIdTabPanel);
			
			var tabComponent = {
				itemId: 'tabpanel_singlestack_<?=$tab_id?>-' + selectedIdTabPanel,
				// title: 'Single Stack Views '.concat(generateTitlePostfix_<?=$tab_id?>(selectedIdTabPanel)),
				html: htmlContent,
				closable: false
			};
			
			if (!ux_singlestackview_<?=$tab_id?>){
				ux_tabpanelstackview_<?=$tab_id?> = Ext.create('Ext.tab.Panel', {
					// tabPosition: 'top',
					items: [tabComponent]
				});
				ux_singlestackview_<?=$tab_id?> = Ext.create('Ext.Window', {
					id: 'win_ciy_view<?=$tab_id?>',
					title: 'Container in Yard View',
					width: resData.configs.MAX_ROW * 82.5 + 40, // konstanta lebar
					height: resData.configs.MAX_TIER * 82.5 + 200 + (20 * resData.category_list.length), // konstanta tinggi
					plain: true,
					headerPosition: 'top',
					constrain: true,
					renderTo: 'container_in_yard_view<?=$tab_id?>',
					listeners:{
						close:function(){
							ux_singlestackview_<?=$tab_id?> = null; 
							selectedIdTabPanel = 0;
						},
						scope:this
					},
					items: [ux_tabpanelstackview_<?=$tab_id?>]
				}).show();
			} else {
				ux_tabpanelstackview_<?=$tab_id?>.add(tabComponent);
				ux_tabpanelstackview_<?=$tab_id?>.setActiveTab('tabpanel_singlestack_<?=$tab_id?>-' + selectedIdTabPanel).show();
			}


			//$("#ciy_container_<?=$tab_id?>_"+selectedIdWindow).find('.ciy_button_switch_<?=$tab_id?>_'+selectedIdTabPanel).html(switchContent);
			$("#ciy_container_<?=$tab_id?>_"+selectedIdWindow).find('#ciy_header_<?=$tab_id?>_'+selectedIdTabPanel).remove();
			$("#ciy_container_<?=$tab_id?>_"+selectedIdWindow).find('#ciy_table_<?=$tab_id?>_'+selectedIdTabPanel).remove();

			
			// insert container content
			console.log('selectedIdTabPanel : '+selectedIdTabPanel);
			fillBodyContent_<?=$tab_id?>(resData, selectedIdTabPanel);
			 /*select new end*/
			
			/*var switchContent = generateSwitchContent_<?=$tab_id?>(resData, selectedBlock, selectedSlot);
			var bodyContent = generateBodyContent_<?=$tab_id?>(resData, selectedBlock, selectedSlot, selectedIdTabPanel);
			console.log(bodyContent);
			*/

			/*$("#ciy_container_<?=$tab_id?>_"+selectedIdWindow).find('.ciy_button_switch_<?=$tab_id?>_'+selectedIdTabPanel).html(switchContent);
			//$("#ciy_container_<?=$tab_id?>_"+selectedIdWindow).find('#ciy_header_<?=$tab_id?>_'+selectedIdTabPanel).remove();
			//$("#ciy_container_<?=$tab_id?>_"+selectedIdWindow).find('#ciy_table_<?=$tab_id?>_'+selectedIdTabPanel).remove();
			$("#ciy_container_<?=$tab_id?>_"+selectedIdWindow).html(bodyContent);
			
			// insert container content
			fillBodyContent_<?=$tab_id?>(resData, selectedIdTabPanel);*/
		},
		failure: function(response, request){
			Ext.MessageBox.alert('Error', 'Please try again. ' + response.status);
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

/** 
 * Switch Container in Yard event binding
 */
$('#container_in_yard_view<?=$tab_id?>').on('click', '.ciy_tab_switch_<?=$tab_id?> span', function(){
	if ($(this).hasClass('passive_<?=$tab_id?>')){
		// switch button
		$(this).toggleClass('passive_<?=$tab_id?>');
		$(this).siblings().toggleClass('passive_<?=$tab_id?>');
		
		var filterSlot = $(this).parent().children('.filter_slot_<?=$tab_id?>');
		var selectedIdTabPanel = filterSlot.data('idtabpanel');
		
		// switch data
		$('#ciy_table_<?=$tab_id?>_'+selectedIdTabPanel+'[data-slot='+ $(this).siblings().data('refer') +'].ciy_table_<?=$tab_id?>').addClass('hide');
		$('#ciy_table_<?=$tab_id?>_'+selectedIdTabPanel+'[data-slot='+ $(this).siblings().data('refer') +'].ciy_table_<?=$tab_id?>').removeClass('hide');
	}
});

/** 
 * Filter event binding
 */
$(".exist_<?=$tab_id?>").on('click', function(event){
	counter_id_ssv_<?=$tab_id?>++;
	var indexClick = $(this).data('index');
	var yardClick = $("#list_yard_<?=$tab_id?>").val();
	var blockClick = $(this).attr('title');
	var idBlockClick = $(this).data('block');
	var slotClick = $(this).data('slot');
	var sizeClick = $(this).hasClass('div_cell_block_40_<?=$tab_id?>') ? '40' : '20';

	url = '<?=controller_?>terminal_monitoring/load_yard_stacking_data/' 
			+yardClick+'/'+idBlockClick+'/'+blockClick+'/'+slotClick+'/'+sizeClick;
	console.log('url : '+url);

	
	loadmask.show();
	Ext.Ajax.request({
		url: '<?=controller_?>terminal_monitoring/load_yard_stacking_data/' 
			+yardClick+'/'+idBlockClick+'/'+blockClick+'/'+slotClick+'/'+sizeClick,
		method: 'POST',
		scope:this,
		success: function(response, request){
			console.log('index: ' + indexClick + ' yard: ' + yardClick + ' block: ' + blockClick + ' slot: ' + slotClick + ' size: ' + sizeClick );
			loadmask.hide();
			
			var resData = Ext.decode(response.responseText);
			
			var filterContent = "Block: <select class='filter_ciy_<?=$tab_id?> filter_block_<?=$tab_id?>' data-idtabpanel='"+counter_id_ssv_<?=$tab_id?>+"'>";
			for(var keyBlock in resData.filter_block){
				var case_selected = ''; if (keyBlock == blockClick) { case_selected = 'selected'; }
				filterContent += "<option value='"+resData.filter_block[keyBlock].ID_BLOCK+"'"+case_selected
					+ " data-slot=" + resData.filter_block[keyBlock].SLOT
					+ " >"+keyBlock+"</option> ";
			}
			filterContent += "</select>";
			
			filterContent += " Slot: <select class='filter_ciy_<?=$tab_id?> filter_slot_<?=$tab_id?>' id='list_slot_<?=$tab_id?>'data-idtabpanel='"+counter_id_ssv_<?=$tab_id?>+"' >";
			// console.log(Number(resData.filter_block[blockClick].SLOT));
			for(var idx=1;idx<=Number(resData.filter_block[blockClick].SLOT);idx++){
				var case_selected = ''; 
				if (idx == slotClick) { case_selected = 'selected'; }
				filterContent += "<option value='"+idx+"'"+case_selected+">"+idx+"</option> ";
			}
			filterContent += "</select>";

			var tab_id_p = '<?=$tab_id?>("p")';
			var tab_id_n = '<?=$tab_id?>("n")';

			filterContent += "&nbsp;<a href='#' onclick='nextPrev_onClick_"+tab_id_p+"' title='Previous'><img src='<?=IMG_?>icons/prev.png' width='20px'/><a/>";
			filterContent += "&nbsp;<a href='#' onclick='nextPrev_onClick_"+tab_id_n+"' title='Next'><img src='<?=IMG_?>icons/next.png' width='20px'/><a/>";
			
			console.log('tab_id_p'+tab_id_p);
			console.log('tab_id_n'+tab_id_n);
			// var switchContent = generateSwitchContent_<?=$tab_id?>(resData, blockClick, slotClick);
			var bodyContent = generateBodyContent_<?=$tab_id?>(resData, blockClick, slotClick, counter_id_ssv_<?=$tab_id?>);

			var htmlContent = "<div id='ciy_container_<?=$tab_id?>_"+counter_id_ssv_<?=$tab_id?>+"' class='ciy_container'><div class='ciy_tab_switch_<?=$tab_id?>'>"
					+ "<div style='float:left; margin-left:2px;' data-idwin="+counter_id_ssv_<?=$tab_id?>+">" + filterContent + "</div>"
					/*+ "<div style='float:right' class='ciy_button_switch_<?=$tab_id?>_"+counter_id_ssv_<?=$tab_id?>+"'>" + switchContent + "</div>"*/
				+ "</div>"
				+ bodyContent
			+ "</div>";
			
			var tabComponent = {
				itemId: 'tabpanel_singlestack_<?=$tab_id?>-' + counter_id_ssv_<?=$tab_id?>,
				title: 'Single Stack View '.concat(generateTitlePostfix_<?=$tab_id?>(counter_id_ssv_<?=$tab_id?>)),
				html: htmlContent,
				closable: false
			};
			
			if (!ux_singlestackview_<?=$tab_id?>){
				ux_tabpanelstackview_<?=$tab_id?> = Ext.create('Ext.tab.Panel', {
					tabPosition: 'top',
					items: [tabComponent]
				});
				ux_singlestackview_<?=$tab_id?> = Ext.create('Ext.Window', {
					id: 'win_ciy_view<?=$tab_id?>',
					title: 'Container in Yard View',
					width: resData.configs.MAX_ROW * 82.5 + 40, // konstanta lebar
					height: resData.configs.MAX_TIER * 82.5 + 200 + (20 * resData.category_list.length), // konstanta tinggi
					plain: true,
					headerPosition: 'top',
					constrain: true,
					renderTo: 'container_in_yard_view<?=$tab_id?>',
					listeners:{
						close:function(){
							ux_singlestackview_<?=$tab_id?> = null; 
							counter_id_ssv_<?=$tab_id?> = 0;
						},
						scope:this
					},
					items: [ux_tabpanelstackview_<?=$tab_id?>]
				}).show();
			} else {
				ux_tabpanelstackview_<?=$tab_id?>.add(tabComponent);
				ux_tabpanelstackview_<?=$tab_id?>.setActiveTab('tabpanel_singlestack_<?=$tab_id?>-' + counter_id_ssv_<?=$tab_id?>).show();
			}
			
			// insert container content
			fillBodyContent_<?=$tab_id?>(resData, counter_id_ssv_<?=$tab_id?>)
		},
		failure: function(response, request){
			Ext.MessageBox.alert('Error', 'Please try again. ' + response.status);
		}
	});
});

function loadSingleStackView(){
    loadmask.show();

    var list_yard 		= $("#list_yard_<?=$tab_id?>").val();
    var list_block 		= $(".filter_block_<?=$tab_id?>").val();
    var list_block_text	= $(".filter_ciy_<?=$tab_id?>").text();
    var list_slot 		= $("#list_slot_<?=$tab_id?>").val();

    $.ajax({
        url : "<?=controller_?>terminal_monitoring/get_block_text",
        type: "POST",
        data: {"id_block":list_block,"id_yard":list_yard},
        dataType:"json",
        success: function(data)
        {

        	console.log(data);
        	list_block_text = data
          
           	var ct 					= $('.filter_block_<?=$tab_id?>').parent();
		    var filterSlot 			= $('.filter_slot_<?=$tab_id?>').parent().children();
			var selectedIdTabPanel 	= filterSlot.data('idtabpanel');
			var selectedIdWindow 	= ct.data('idwin');
			var selectedIdBlock 	= list_block;
			var selectedBlock 		= list_block_text;
			var selectedSlot 		= list_slot;

			changeSingleStackViewContent_<?=$tab_id?>(selectedIdBlock,selectedBlock, selectedSlot, selectedIdWindow, selectedIdTabPanel);

			loadmask.hide();
         },
         error: function (jqXHR, textStatus, errorThrown)
         {
          //alert('Error adding / update data');
         }
      });  

}

/********* HTML Generate Manipulation ********/
function generateTitlePostfix_<?=$tab_id?>(counter){
	if (counter != 1){ return "("+counter+")"; } else { return ""; }
}

function generateSwitchContent_<?=$tab_id?>(resData, blockClick, slotClick){
	var switchContent = ""; var i=0;
	for(var y =0;y<resData.data_idx.length;y++){
		if (i==0){
			switchContent += "<span data-refer='" +resData.data_idx[y]+ "'>" +resData.data_idx[y]+ "</span>";
		} else {
			switchContent += "<span data-refer='" +resData.data_idx[y]+ "' class='passive_<?=$tab_id?>'>" +resData.data_idx[y]+ "</span>";
		}
		i++;
	}
	return switchContent
}

function generateBodyContent_<?=$tab_id?>(resData, blockClick, slotClick, selectedIdTabPanel){
	var headerContent = "<table id='ciy_header_<?=$tab_id?>_"+selectedIdTabPanel+"'"+" class='ciy_header_<?=$tab_id?>'><tr>";
	for(var i=1;i<=Number(resData.configs.MAX_ROW);i++){
		headerContent += "<td>" +i+ "</td>";
	}
	headerContent += "<td></td></tr></table>";
	var bodyContent = ""; var z=0;
	for(var y =0;y<resData.data_idx.length;y++){
		if (z>0){ var class_css = 'ciy_table_<?=$tab_id?> hide';} else { class_css = 'ciy_table_<?=$tab_id?>';}
		bodyContent += "<table id='ciy_table_<?=$tab_id?>_" +selectedIdTabPanel+"'" + " class='"+class_css+"' data-slot='"+resData.data_idx[y]+"'><tr class='ciy_data_<?=$tab_id?>'>";
		for(var j=Number(resData.configs.MAX_TIER);j>=1;j--){
			bodyContent += "<tr class='ciy_data_<?=$tab_id?>'>";
			for(var k=1;k<=Number(resData.configs.MAX_ROW);k++){
				bodyContent += "<td data-row='" +k+ "' data-tier='" +j+ "' class='data_<?=$tab_id?>'></td>";
			}
			bodyContent += "<td class='right_header_<?=$tab_id?>'>" +j+ "</td></tr>";
		}
		bodyContent += "</table>";
		z++;
	}
	bodyContent += "<table id='ciy_footer_<?=$tab_id?>_"+selectedIdTabPanel+"'"+" class='ciy_footer_<?=$tab_id?>'>";
	bodyContent += "<tr>";
	for(var i=1;i<=Number(resData.configs.MAX_ROW);i++){
		bodyContent += "<td>&nbsp;</td>";
	}
	bodyContent += "<td>&nbsp;</td>";
	bodyContent += "</tr>";
	bodyContent += "<tr>";
	bodyContent += '<td colspan="'+resData.configs.MAX_ROW+'">';
	$.each(resData.category_list, function(i,j){
	    var bg_style = '';
	    var left_cat = (j.START_ROW  - 1) * 80;
	    var width_cat = ((j.END_ROW - j.START_ROW + 1) * 80) + (2 * (resData.configs.MAX_ROW - 1));
	    if(j.BACKGROUND_COLOR != ''){
		bg_style = 'background: #' + j.BACKGROUND_COLOR;
	    }
	    bodyContent += '<a  href="javascript:;" onclick="edit_category_<?=$tab_id?>(\'' + j.ID_CATEGORY + '\')">';
	    bodyContent += '<div class="ssv_cat_group" style="margin-left: ' + left_cat + 'px;width: ' + width_cat + 'px;' + bg_style + '">';
	    bodyContent += j.CATEGORY_NAME;
	    bodyContent += '</div>';
	    bodyContent += "</a>";
	});
	bodyContent += "</td>";
	bodyContent += "<td>&nbsp;</td>";
	bodyContent += "</tr>";
	bodyContent += "</table>";
	return (headerContent + bodyContent);
}

function createShipBayViewFrame_<?=$tab_id?>(resData, bay, selectedIdTabPanel){
	var headerContent = "<table id='cis_header_<?=$tab_id?>_"+selectedIdTabPanel+"'"+" class='cis_header_<?=$tab_id?>'><tr>";
	for(var i=1;i<=Number(resData.configs.MAX_ROW);i++){
		headerContent += "<td>" +i+ "</td>";
	}
	headerContent += "<td></td></tr></table>";
	
	var bodyContent = "<table id='cis_table_<?=$tab_id?>_" +selectedIdTabPanel+"'" + " class='cis_table_<?=$tab_id?>' data-bay='"+bay+"'>";
	for(var j=Number(resData.configs.MAX_TIER);j>=1;j--){
		bodyContent += "<tr class='cis_data_<?=$tab_id?>'>";
		for(var k=1;k<=Number(resData.configs.MAX_ROW);k++){
			bodyContent += "<td data-row='" +k+ "' data-tier='" +j+ "' class='data_<?=$tab_id?>'></td>";
		}
		bodyContent += "<td class='right_header_<?=$tab_id?>'>" +j+ "</td></tr>";
	}
	bodyContent += "</table>";
	
	return (headerContent + bodyContent);
}


function fillBodyContent_<?=$tab_id?>(resData, selectedIdTabPanel){
	for(var key in resData.data){
		for(var i=0;i<resData.data[key].length;i++){
			$("#ciy_table_<?=$tab_id?>_"+selectedIdTabPanel+"[data-slot="+key+"].ciy_table_<?=$tab_id?> td[data-row="+resData.data[key][i].YD_ROW+"][data-tier="+resData.data[key][i].YD_TIER+"]").html(
				generateDataContainer_<?=$tab_id?>(resData.data[key][i])
			).addClass('stacking_<?=$tab_id?>').css("background", "#"+resData.data[key][i].BACKGROUND_COLOR).css("color", "#"+resData.data[key][i].FOREGROUND_COLOR);
		}
	}
}
/*ganda remarks*/
function generateDataContainer_<?=$tab_id?>(dataContainer){
	var d='';
	if((dataContainer.IMDG!=='0') && dataContainer.IMDG!= null)
	{
		d='<span class="triangle"><span class="texts">'+dataContainer.IMDG+'</span></span>';
	}
	if (dataContainer.IMDG=='0')
	{
		d='';
	}
	var e='';
	if(dataContainer.ID_SPEC_HAND!=null)
	{
		e=dataContainer.ID_SPEC_HAND;
	}
	return dataContainer.NO_CONTAINER + '<br />' +
		dataContainer.ID_ISO_CODE + ' ' + dataContainer.ID_COMMODITY + ' ' + dataContainer.ID_CLASS_CODE + '<br />' +
		dataContainer.ID_OPERATOR + ' ' + dataContainer.WEIGHT + 'T '+e+'<br />' +
		dataContainer.ID_VESSEL +' '+d+ 
		'<span style=\'float:right;border:1px solid black;padding:2px\'>' + dataContainer.ID_POD + '</span><br />';
}

/**
 * Render Equipment
 */
function renderEquipment<?=$tab_id?>(){
	// render equipment 
	for (var i = 0; i < equipmentDataJson_<?=$tab_id?>.length; i++) {
		console.log(equipmentDataJson_<?=$tab_id?>[i].MCH_NAME);
		var css_class = "equip-" + equipmentDataJson_<?=$tab_id?>[i].MCH_SUB_TYPE.toLowerCase();
		var image_name = "assets/small/" + equipmentDataJson_<?=$tab_id?>[i].MCH_SUB_TYPE.toLowerCase() + ".png";

		var last_activity = '';
		if (equipmentDataJson_<?=$tab_id?>[i].LAST_JOB != null){
			last_activity = "Last Activity: " + equipmentDataJson_<?=$tab_id?>[i].LAST_JOB  ;	
		} else {
			last_activity = "No Activity";
		}
		
		var block_selector = '';
		var css_pos = null;
		var css_off = null;
		var offsettop_ship = 0;
		
		if (equipmentDataJson_<?=$tab_id?>[i].SLOT_ != null){
			block_selector = 
				".div_cell_block_<?=$tab_id?>[title='"+equipmentDataJson_<?=$tab_id?>[i].BLOCKNAME+"']"
				+ "[data-slot='"+equipmentDataJson_<?=$tab_id?>[i].SLOT_+"']"
				+ "[data-row='1']";
			block_selector += 
				",.div_cell_block_40_<?=$tab_id?>[title='"+equipmentDataJson_<?=$tab_id?>[i].BLOCKNAME+"']"
				+ "[data-slot='"+equipmentDataJson_<?=$tab_id?>[i].SLOT_+"']"
				+ "[data-row='1']";
		}else if (equipmentDataJson_<?=$tab_id?>[i].VS_BAY != null){
			var vs_bay = equipmentDataJson_<?=$tab_id?>[i].VS_BAY
			if (vs_bay%2==0){
				vs_bay -= 1;
			}
			block_selector = 
				".div_cell_block_ship_<?=$tab_id?>"
				+"[data-idvesvoyage='"+equipmentDataJson_<?=$tab_id?>[i].ID_VES_VOYAGE+"']"
				+"[data-bay='"+vs_bay+"']"
				+"[data-row='1']";
				offsettop_ship = $("#vessel_berth_<?=$tab_id?>_"+equipmentDataJson_<?=$tab_id?>[i].ID_VES_VOYAGE).height();
		}
		
		css_pos = $(block_selector).position();
		css_off = $(block_selector).offset();
		if (block_selector!=''){
			console.log('--Equipment selector : ');
			console.log(block_selector);
			console.log('CSS selector : ');
			console.log(css_pos);
			console.log(css_off);
		}else{
			console.log('--No Selector : ');
		}
		
		var mch_type = '';
		var csstop = '';
		var cssleft = '';
		var nameqc="";
		var classnameqc="";
		if (css_pos != undefined && css_off != undefined){
			if (equipmentDataJson_<?=$tab_id?>[i].MCH_SUB_TYPE == "RTG"){
				mch_type = 'YARD';
				var image_width = 8;
				var image_height = 60;

				csstop = css_pos.top-8; // depends on RTG image
				cssleft = css_pos.left+3;

			} else if (equipmentDataJson_<?=$tab_id?>[i].MCH_SUB_TYPE == "RS"){
				mch_type = 'YARD';
				image_width = 8;
				image_height = 60;

				csstop = css_pos.top-20; // depends on RS image
				cssleft = css_pos.left+3;
			} else if (equipmentDataJson_<?=$tab_id?>[i].MCH_SUB_TYPE == "QC"){
				mch_type = 'QUAY';
				image_width = 30;
				image_height = 160;

				csstop = css_off.top-offsettop_ship-40; // depends on QC image
				cssleft = css_off.left-10;
				
				nameqc=  " - "+equipmentDataJson_<?=$tab_id?>[i].VESSEL_NAME + "  - BAY: "+equipmentDataJson_<?=$tab_id?>[i].VS_BAY;
				var classnameqc="_qc";
			} else if (equipmentDataJson_<?=$tab_id?>[i].MCH_SUB_TYPE == "SC"){
				mch_type = 'QUAY';
				image_width = 8;
				image_height = 60;

				csstop = css_off.top-offsettop_ship; // depends on SC image
				cssleft = css_off.left;
				
				nameqc=  " - "+equipmentDataJson_<?=$tab_id?>[i].VESSEL_NAME + "  - BAY: "+equipmentDataJson_<?=$tab_id?>[i].VS_BAY;
				var classnameqc="_qc";
			}else {
				image_width = 0;
				image_height = 0;
			}
			
			var string_content = "<div id='equip_<?=$tab_id?>_"+equipmentDataJson_<?=$tab_id?>[i].ID_MACHINE+"' class=\"" + css_class +"\" "
					+ "style=\"top:" + csstop + "px;left:" + cssleft + "px;\" "
					+ "title=\"" + last_activity + "\">"
				+ "<img src=\"<?=IMG_?>" + image_name + "\"  width=\"" +image_width+ "px\" height=\"" +image_height+ "px\" />"
				+ "<span class=\"equip-name"+classnameqc+"_<?=$tab_id?> " + css_class + "-name\" >"
					+ "<span class=\"equip-block-name\" style=\"background:" +equipmentDataJson_<?=$tab_id?>[i].BG_COLOR+ "\">"
						+ equipmentDataJson_<?=$tab_id?>[i].MCH_NAME + nameqc
					+ "</span>"
				+ "</span>"
			+ "</div>";
			
			if (mch_type == 'YARD'){
				$("#equipment-group_<?=$tab_id?>").append(string_content);
			} else if (mch_type == 'QUAY'){
				$("#equipment_berth-group_<?=$tab_id?>").append(string_content);
			}
		}
	};
}

function show_container_list_<?=$tab_id?>(id_class_code, id_ves_voyage){
	counter_id_cl_<?=$tab_id?>++;
	
	loadmask.show();
	Ext.Ajax.request({
		url: '<?=controller_?>terminal_monitoring/load_ship_container_list/',
		method: 'POST',
		params: {
			id_ves_voyage: id_ves_voyage,
			id_class_code: id_class_code
		},
		success: function(response, request){
			loadmask.hide();
			
			var resData = Ext.decode(response.responseText);
			
			var title = "";
			if (id_class_code == 'E'){
				title = "Outbound List of "+id_ves_voyage;
			}else if (id_class_code == 'I'){
				title = "Inbound List of "+id_ves_voyage;
			}
			
			Ext.create('Ext.data.Store', {
				storeId: 'container_list_<?=$tab_id?>',
				fields: ['NO_CONTAINER', 'ID_ISO_CODE', 'STOWAGE', 'YARD_POS', 'OP_STATUS_DESC'],
				data: resData,
				proxy: {
					type: 'memory',
					enablePaging: true,
					reader: {
						type: 'json',
						root: 'data'
					}
				},
				pageSize: 15,
				sorters: [{
					property: 'NO_CONTAINER',
					direction: 'ASC'
				}]
			});
			
			var tabComponent = {
				itemId: 'tabpanel_container_list_<?=$tab_id?>-' + counter_id_cl_<?=$tab_id?>,
				title: title,
				items: [
					Ext.create('Ext.grid.Panel', {
						store: Ext.data.StoreManager.lookup('container_list_<?=$tab_id?>'),
						width: 490, // konstanta lebar
						height: 470, // konstanta tinggi
						columns: [
							{ text: 'No Container',  dataIndex: 'NO_CONTAINER', width: 120, filter: {type: 'string'}},
							{ text: 'ISO Code', dataIndex: 'ID_ISO_CODE', width: 80, filter: {type: 'string'}},
							{ text: 'Stowage', dataIndex: 'STOWAGE' , width: 75},
							{ text: 'Yard', dataIndex: 'YARD_POS' , width: 80},
							{ text: 'Status', dataIndex: 'OP_STATUS_DESC' , width: 130}
						],
						dockedItems: [Ext.create('Ext.toolbar.Paging', {
							dock: 'bottom',
							store: Ext.data.StoreManager.lookup('container_list_<?=$tab_id?>'),
							displayInfo: true,
							displayMsg: 'Displaying {0} - {1} of {2}'
						})],
						features: [{
							ftype: 'filters',
							local: true
						}],
						emptyText: 'No Data Found',
					})
				],
				closable: true
			};
			
			if (!ux_container_list_<?=$tab_id?>){
				ux_tabpanel_container_list_<?=$tab_id?> = Ext.create('Ext.tab.Panel', {
					tabPosition: 'top',
					items: [tabComponent]
				});
				ux_container_list_<?=$tab_id?> = Ext.create('Ext.Window', {
					id: 'win_ship_view<?=$tab_id?>',
					title: 'Container List',
					width: 500, // konstanta lebar
					height: 550, // konstanta tinggi
					plain: true,
					headerPosition: 'top',
					constrain: true,
					renderTo: 'container_list_<?=$tab_id?>',
					listeners:{
						close:function(){
							ux_container_list_<?=$tab_id?> = null; 
							counter_id_cl_<?=$tab_id?> = 0;
						},
						scope:this
					},
					items: [ux_tabpanel_container_list_<?=$tab_id?>]
				}).show();
			} else {
				ux_tabpanel_container_list_<?=$tab_id?>.add(tabComponent);
				ux_tabpanel_container_list_<?=$tab_id?>.setActiveTab('tabpanel_container_list_<?=$tab_id?>-' + counter_id_cl_<?=$tab_id?>).show();
			}
		},
		failure: function(response, request){
			Ext.MessageBox.alert('Error', 'Please try again. ' + response.status);
		}
	});
}
</script>