<?php
/**
 * Sanitizing Output
 */
function sanitize_output($buffer) {

    $search = array(
        '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        '/(\s)+/s'       // shorten multiple whitespace sequences
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


/*$s = 76;
$s_h = 28;*/

$s_h_white = 7;
$grid_width = ($s*$width)+8; // 8 fix value
$grid_height = ($s_h*$height)+8;

$border_color = '#8C92AC';
?>

<style type="text/css">

#center_content_<?=$tab_id?> {
    margin-left: 0px;
    padding-top: 20px;
    position: relative;
}

/* Vessel berthing module */
.vessel_selector{
    position: absolute;
    padding: 5px;
    cursor: pointer;
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    border-radius: 20px;
    border:1px solid #EFE4DE;
    background-color:#FFFFFF;
    -webkit-box-shadow: #b3b3b3 8px 8px 8px;
    -moz-box-shadow: #b3b3b3 8px 8px 8px; 
    box-shadow: #b3b3b3 8px 8px 8px;
    padding: 10px;

    left: 600px;
    top: 250px;
}

.vessel_selector figcaption {
    margin: 10px 0 0 0;
    font-variant: small-caps;
    font-family: Arial;
    font-weight: bold;
}

.vessel_selector img:hover {
    transform: scale(1.1);
    -ms-transform: scale(1.1);
    -webkit-transform: scale(1.1);
    -moz-transform: scale(1.1);
    -o-transform: scale(1.1);
}

.vessel_selector img {
    transition: transform 0.2s;
    -webkit-transition: -webkit-transform 0.2s;
    -moz-transition: -moz-transform 0.2s;
    -o-transition: -o-transform 0.2s;
}

#left_container {
	position: relative;
	width: 60%;
	float: left;
	height: 300px;
	padding: 10px;
}

#right_container {
	position: relative;
	width: 40%;
	float: left;
	height: 300px;
	padding: 10px;
}

#bottom_container {
	clear: both;
	position: relative;
	width: 100%;
	padding: 10px;
}

.dl {
	font-weight: bold;
}

/* Yard module */
#selectable_<?=$tab_id?> .ui-selecting { background: #FECA40; }
#selectable_<?=$tab_id?> .ui-selected { background: #F39814; color: white; }
#selectable_<?=$tab_id?> { list-style-type: none; margin: 0; padding: 0; }
#selectable_<?=$tab_id?> li {
	float: left; 
	width: <?php echo $s."px"?>; 
	height: <?php echo $s."px"?>; 
	font-size: 2em; 
	text-align: center; 
}
div.grid_<?=$tab_id?> {
	width:  <?php echo $grid_width."px"?>;
	height: <?php echo $grid_height."px"?>;
	font-size: 5px;
	position: absolute;
}

.div_cell_block{
	float: left;
	width: <?php echo $s."px"?>;
	height: <?php echo $s_h."px"?>;
	border-top: 1px solid <?=$border_color?>;
	border-left: 1px solid <?=$border_color?>;
	/*border: 1px solid #e8edff;*/
    font-size: 2em; 
    text-align: center;
}

.div_cell_block_40{
	float: left;
	width: <?php echo ($s*2)."px"?>;
	height: <?php echo $s_h."px"?>;
	border-top: 1px solid <?=$border_color?>;
	border-left: 1px solid <?=$border_color?>;
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

.div_cell_block_zero{
	float: left;
	width: 0px;
	height: <?php echo $s_h."px"?>;
    font-size: 2em; 
    text-align: center;
}

.exist:hover{
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
#equipment-group {
	position: absolute;
	top:0px;
	left:3px;
}

.equip-rtg {
	position:absolute;
    width: 200px;
    height: 400px;
}

.equip-rtg img {
	opacity:0.8;
}

.equip-rs {
	position:absolute;
}

.equip-rs img {
	opacity:0.8;
}
	
.equip-name {
	font-size: 6pt;
    position: absolute;
    width: 150px;
    color: #fff;
}

.equip-rtg-name {
    top: -11px;
    left: 0;
}

.equip-rs-name {
	top: 61px;
	left: 0;
}

.equip-block-name {
	float: left;
    padding: 0 2px;
}

.equip-block-act {
	float: left;
	margin-left: 2px;
    padding: 0 2px;
}

/** legend **/
.my-legend-group {

}
.my-legend {
	background: none repeat scroll 0 0 #eeeeee;
    border: 1px solid #cdcdcd;
    border-radius: 5px;
    margin-left: 5px;
    margin-top: 20px;
    padding: 10px 15px;
    width: 200px;
    float: left;
}
.my-legend .legend-title {
    text-align: left;
    margin-bottom: 5px;
    font-weight: bold;
    font-size: 90%;
}
.my-legend .legend-scale ul {
    margin: 0;
    margin-bottom: 5px;
    padding: 0;
    float: left;
    list-style: none;
}
.my-legend .legend-scale ul li {
    font-size: 80%;
    list-style: none;
    margin-left: 0;
    line-height: 18px;
    margin-bottom: 2px;
}
.my-legend ul.legend-labels li span {
    display: block;
    float: left;
    height: 16px;
    width: 30px;
    margin-right: 5px;
    margin-left: 0;
    border: 1px solid #999;
}
.my-legend .legend-source {
    font-size: 70%;
    color: #999;
    clear: both;
}
.my-legend a {
    color: #777;
}

/******* berth ******/

.berth {
	display: inline-block;
	background-image: url('<?= IMG_ ?>assets/background-shading-2.png');
    /*float: left;*/
    margin: 10px;
    text-align: center;
    padding: 3px;
    border: 1px solid;
    border-radius: 5px;
}

/******* coloring filter ******/
/*.tier1{ background: #A1CAF1; }
.tier2{ background: #6DA9E3; }
.tier3{ background: #297CCC; }
.tier4{ background: #014585; }
.tier5{ background: #014585; }
.tier6{ background: #014585; }*/

.tier1{ background: #adcf60; }
.tier2{ background: #8db438; }
.tier3{ background: #fde74c; }
.tier4{ background: #fa7921; }
.tier5{ background: #e46e1e; }
.tier6{ background: #e55934; }

/******* Container in Yard View ******/
.ciy_tab_switch {
    height: 40px;
    margin-top: 20px;
    text-align: center;
    width: 495px; /*@todo*/
}
.ciy_tab_switch span {
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
.ciy_tab_switch .passive {
    background-color: #007FFF;
    cursor: pointer;
}
.ciy_header td {
    border: none;
    min-width: 80px;
    padding: 5px;
    text-align: center;
    font-family: helvetica, arial, verdana, sans-serif;
    font-size: 9px;
}
.ciy_table table, .ciy_table th, .ciy_table td {
   border: 1px solid black;
}
.ciy_table {
    font-family: helvetica, arial, verdana, sans-serif;
    font-size: 9px;
}
.ciy_table table {
    width:80px;
    padding:.5em;
}
.ciy_data tr {}
.ciy_data .data {
    min-width: 80px;
    height: 80px;
    padding: 3px;
    background: #e8edff;
}
.ciy_data .stacking {
    background: #A1CAF1;
}
.ciy_data .right_header {
    border: none;
    min-width: 10px;
    background: #FFFFFF;
    padding-left: 10px;
}
.hide {display: none;}

/**** Filter Container in Yard ****/
@media screen and (-webkit-min-device-pixel-ratio:0) {  /*safari and chrome*/
    .filter_ciy {
        height:32px;
        line-height:32px;
        background:#f4f4f4;
    } 
}
.filter_ciy::-moz-focus-inner { /*Remove button padding in FF*/ 
    border: 0;
    padding: 0;
}
@-moz-document url-prefix() { /* targets Firefox only */
    .filter_ciy {
        padding: 16px 0!important;
    }
}        
@media screen\0 { /* IE Hacks: targets IE 8, 9 and 10 */        
    .filter_ciy {
        height:32px;
        line-height:30px;
    }     
}
</style>

<!-- div id="vessel">
<?php foreach ($vessel as $key => $value) {
	echo "<figure class='vessel_selector'>
	        <img src='" .IMG_ . "assets/vessel-icon.jpg'width='140px' height='42px' />
	        <figcaption>"
	        . $value['VESSEL_NAME'] . "<br />"
	        . $value['ID_VES_VOYAGE'] .
	        "</figcaption>
	    </figure>";
}?>
</div -->

<!--div id="berth_meter">
<?php foreach ($berth as $key => $value) {
	$width_start_end = intval($value['END_METER']) - intval($value['START_METER']);
	//echo "<div id='berth_1' class='berth' style='width:".$width_start_end."px;height:40px;'>".$value['KADE_NAME']."</div>";
} ?>
	<table id="berth_table">
		<tr>
			<th>
				<div id='berth_1' class='berth' style='width:200px;height:40px;'>
					KADE 1
				</div>
			</th>
			<th>
				<div id='berth_2' class='berth' style='width:200px;height:40px;'>
					KADE 2
				</div>
			</th>
		</tr>
		<tr>
			<td>0</td>
			<td>200</td>
		</tr>
	</table> 
</div -->

<!-- Equipment -->
<!-- div class='my-legend'>
<div class='legend-title'>Equipment</div>
<div class='legend-scale'>
  <ul class='legend-labels'>
	<?php
	foreach ($equipment_legend as $key => $value) {
		echo "<li><span style='background:" .$value['BG_COLOR']. ";'></span>" .$value['MCH_NAME']. "</li>";
	}
	?>
  </ul>
</div>
<div class='legend-source'></div>
</div -->
<img src="<?=IMG_?>icons/compass.png" width="80px" style="margin: 20px 0px 20px 50px"/>
<center id="center_content_<?=$tab_id?>" class='mainmon'>
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
                                        echo "div_cell_block_zero ";
										$flag_40f = true;
									} else if ($flag_40f) {
										$flagging_border_right = true;
                                        echo "div_cell_block_40 ";
										$flag_40f = false;
									} else {
                                        $flagging_border_right = true;
										echo "div_cell_block ";
									}
									if($placement_total>0){
										echo "exist tier" .$placement_total. " ";
									} else {
                                        echo "notexist ";
                                    }
                                    
                                    // case border bottom
                                    if (
                                        ($row_[$p] == $max_row[$p] && substr( $orientation[$p], 0, 1) == 'T') 
                                        || 
                                        ($row_[$p] == '1' && substr( $orientation[$p], 0, 1) == 'B')
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
                                    data-slot="<?=$slot_[$p]?>" 
									data-row="<?=$row_[$p]?>" 
                                    data-max_row="<?=$max_row[$p]?>"
                                    data-orientation="<?=$orientation[$p]?>"
                                    data-pod="<?=$filter_pod?>" 
                                    data-ves="<?=$filter_ves?>" 
                                    data-opr="<?=$filter_carr?>" 
                                    data-class_code="<?=$filter_ei?>"
                                    data-placement="<?=$placement_total?>"

									<?php 
									if (($i%$m) == 0){ 
										$j++;
										echo "style=\"clear: both;\"";
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
	<div id="equipment-group">
		<?php  $equipment_data_json = json_encode($equipment); ?>
	</div>
</div>
</center>

<!-- Legend -->
<!-- div class='my-legend'>
<div class='legend-title'>Plan Category</div>
<div class='legend-scale'>
  <ul class='legend-labels'>
    <?php
	foreach ($category_legend as $key => $value) {
		echo "<li><span style='background:" .$value['HEX_COLOR']. ";'></span>" .$value['CATEGORY_NAME']. "</li>";
	}
	?>
  </ul>
</div>
<div class='legend-source'></div>
</div -->

<div style="clear:both"></div>

<!-- div class="btn-group" role="group" aria-label="...">
  <button type="button" class="btn btn-success active">By Category</button>
  <button type="button" class="btn btn-default disabled">Vessels</button>
  <button type="button" class="btn btn-default disabled">Port of Discharge</button>
  <button type="button" class="btn btn-default disabled">Container Size</button>
  <button type="button" class="btn btn-default disabled">Container Type</button>
  <button type="button" class="btn btn-default disabled">Operator</button>
</div -->

<?php
ob_end_flush();
?>

<script type="text/javascript">
/**
 * Global Variables
 */
var ux_singlestackview;
var ux_tabpanelstackview;
var counter_id_ssv = 1;

// store equipment data json
var equipmentDataJson = <?php echo $equipment_data_json ?>; console.log(equipmentDataJson);

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
	renderEquipment<?=$tab_id?>();
});

/** 
 * Change Block event binding
 */
$('#container_in_yard_view<?=$tab_id?>').on('change', '.filter_block', function(event){
    var maxSlot = $(this).find('option:selected').data('slot'); 
    console.log('max slot: ' + maxSlot);
        
    // update data filter
    var filterSlot = $(this).parent().children('.filter_slot');
    filterSlot.empty();
    for(var i=1;i<=maxSlot;i++){
        var option = $('<option></option>').attr("value", i).text(i);
        filterSlot.append(option);
    }
    
    var selectedIdWindow = $(this).parent().data('idwin');
    var selectedBlock = $(this).find('option:selected').text();
    var selectedSlot = $(this).parent().children('.filter_slot').val();
    console.log('select: ' + selectedBlock + ',' + selectedSlot + ',' + selectedIdWindow);
    
    changeSingleStackViewContent(selectedBlock, selectedSlot, selectedIdWindow);
});

/** 
 * Change Slot event binding
 */
$('#container_in_yard_view<?=$tab_id?>').on('change', '.filter_slot', function(event){
    var selectedIdWindow = $(this).parent().data('idwin');
    var selectedBlock = $(this).siblings().find('option:selected').text();
    var selectedSlot = $(this).parent().children('.filter_slot').val();
    console.log('select: ' + selectedBlock + ',' + selectedSlot);
    
    changeSingleStackViewContent(selectedBlock, selectedSlot, selectedIdWindow);
});

/** 
 * Change Single Stack View Content
 */
function changeSingleStackViewContent(selectedBlock, selectedSlot, selectedIdWindow){
    
    var currentLoadmask = new Ext.LoadMask(Ext.getCmp('win_ciy_view<?=$tab_id?>'), {msg:"Loading..."});
    
    currentLoadmask.show();
    Ext.Ajax.request({
        url: '<?=controller_?>terminal_monitoring/load_yard_stacking_data/' 
            +$('#list_yard_<?=$tab_id?>').val()+'/'+selectedBlock+'/'+selectedSlot+'/20',
        method: 'POST',
        scope:this,
        success: function(response, request){
            currentLoadmask.hide();
            var resData = Ext.decode(response.responseText);
            //console.log(resData);
            
            var switchContent = generateSwitchContent(resData, selectedBlock, selectedSlot);
            var bodyContent = generateBodyContent(resData, selectedBlock, selectedSlot);
            
            $("#ciy_container_"+selectedIdWindow).find('.ciy_button_switch').html(switchContent);
            $("#ciy_container_"+selectedIdWindow).find('.ciy_header').remove();
            $("#ciy_container_"+selectedIdWindow).find('.ciy_table').remove();
            $("#ciy_container_"+selectedIdWindow).append(bodyContent);
            
            // insert container content
            fillBodyContent(resData)
        },                                    
        failure: function(response, request){
            Ext.MessageBox.alert('Error', 'Please try again. ' + response.status);
        }
    });
}

/** 
 * Switch Container in Yard event binding
 */
$('#container_in_yard_view<?=$tab_id?>').on('click', '.ciy_tab_switch span', function(){
    if ($(this).hasClass('passive')){
        // switch button
        $(this).toggleClass('passive');
        $(this).siblings().toggleClass('passive');
        
        // switch data
        $('.ciy_table[data-slot='+ $(this).siblings().data('refer') +']').addClass('hide');
        $('.ciy_table[data-slot='+ $(this).data('refer') +']').removeClass('hide');
    }
});
    
/** 
 * Filter event binding
 */
$(".exist").on('click', function(event){
    
    var indexClick = $(this).data('index');
    var yardClick = $("#list_yard_<?=$tab_id?>").val()
    var blockClick = $(this).attr('title');
    var slotClick = $(this).data('slot');
    var sizeClick = $(this).hasClass('div_cell_block_40') ? '40' : '20';
    
    loadmask.show();
    Ext.Ajax.request({
        url: '<?=controller_?>terminal_monitoring/load_yard_stacking_data/' 
            +yardClick+'/'+blockClick+'/'+slotClick+'/'+sizeClick,
        method: 'POST',
        scope:this,
        success: function(response, request){
            console.log('index: ' + indexClick + ' yard: ' + yardClick + ' block: ' + blockClick + ' slot: ' + slotClick + ' size: ' + sizeClick );
            loadmask.hide();
            
            var resData = Ext.decode(response.responseText);
            
            var filterContent = "Block: <select class='filter_ciy filter_ciy_<?=$tab_id?> filter_block'>";
            for(var keyBlock in resData.filter_block){
                var case_selected = ''; if (keyBlock == blockClick) { case_selected = 'selected'; }
                filterContent += "<option value='"+resData.filter_block[keyBlock].ID_BLOCK+"'"+case_selected
                    + " data-slot=" + resData.filter_block[keyBlock].SLOT
                    + " >"+keyBlock+"</option> ";
            }
            filterContent += "</select>";
            
            filterContent += " Slot: <select class='filter_ciy filter_ciy_<?=$tab_id?> filter_slot'>";
            console.log(Number(resData.filter_block[blockClick].SLOT));
            for(var idx=1;idx<=Number(resData.filter_block[blockClick].SLOT);idx++){
                var case_selected = ''; if (idx == slotClick) { case_selected = 'selected'; }
                filterContent += "<option value='"+idx+"'"+case_selected+">"+idx+"</option> ";
            }
            filterContent += "</select>";
            
            var switchContent = generateSwitchContent(resData, blockClick, slotClick);
            var bodyContent = generateBodyContent(resData, blockClick, slotClick);

            var htmlContent = "<div id='ciy_container_"+counter_id_ssv+"' class='ciy_container'><div class='ciy_tab_switch'>"
                    + "<div style='float:left; margin-left:2px;' data-idwin="+counter_id_ssv+">" + filterContent + "</div>"
                    + "<div style='float:right' class='ciy_button_switch'>" + switchContent + "</div>"
                + "</div>"
                + bodyContent
            + "</div>";
            
            var tabComponent = {
                itemId: 'tabpanel-' + counter_id_ssv,
                title: 'Single Stack View '.concat(generateTitlePostfix(counter_id_ssv)),
                html: htmlContent,
                closable: true
            };
            
            if (!ux_singlestackview){
                ux_tabpanelstackview = Ext.create('Ext.tab.Panel', {
                    id: 'tabpanel_singlestack',
                    tabPosition: 'top',
                    items: [tabComponent]
                });
                ux_singlestackview = Ext.create('Ext.Window', {
                    id: 'win_ciy_view<?=$tab_id?>',
                    title: 'Container in Yard View',
                    width: resData.configs.MAX_ROW * 82.5 + 40, // konstanta lebar
                    height: resData.configs.MAX_TIER * 82.5 + 180, // konstanta tinggi
                    x: 300,
                    y: 0,
                    plain: true,
                    headerPosition: 'top',
                    //layout: 'fit',
                    //closeAction: 'hide',
                    renderTo: 'container_in_yard_view<?=$tab_id?>',
                    listeners:{
                        close:function(){
                            ux_singlestackview = null; 
                            counter_id_ssv = 1;
                                // @todo Not found another way. 
                                // Weakness: "zombie" Window component may be exist
                        },
                        scope:this
                    },
                    items: [ux_tabpanelstackview]
                }).show();
                
                // var myMask = new Ext.LoadMask(ux_tabpanelstackview, {msg:"Please wait..."});
                // myMask.show();
                counter_id_ssv++;
            } else {
                ux_tabpanelstackview.add(tabComponent);
                ux_tabpanelstackview.setActiveTab('tabpanel-' + counter_id_ssv).show();
                counter_id_ssv++;
            }
            
            // insert container content
            fillBodyContent(resData)
        },                                    
        failure: function(response, request){
            Ext.MessageBox.alert('Error', 'Please try again. ' + response.status);
        }
    });
});

/********* HTML Generate Manipulation ********/
function generateTitlePostfix(counter_id_ssv){
    if (counter_id_ssv != 1){ return "("+counter_id_ssv+")"; } else { return ""; }
}

function generateSwitchContent(resData, blockClick, slotClick){
    var switchContent = ""; var i=0;
    for(var y =0;y<resData.data_idx.length;y++){
        if (i==0){
            switchContent += "<span data-refer='" +resData.data_idx[y]+ "'>" +resData.data_idx[y]+ "</span>";
            //switchContent += "<span data-refer='" +resData.data_idx[y]+ "' class='passive'>" +resData.data_idx[y]+ "</span>";
        } else {
            switchContent += "<span data-refer='" +resData.data_idx[y]+ "' class='passive'>" +resData.data_idx[y]+ "</span>";
        }
        i++;
    }
    return switchContent
}

function generateBodyContent(resData, blockClick, slotClick){
    var headerContent = "<table class='ciy_header'><tr>";
    for(var i=1;i<=Number(resData.configs.MAX_ROW);i++){
        headerContent += "<td>" +i+ "</td>";
    }
    headerContent += "<td></td></tr></table>";
    
    var bodyContent = ""; var z=0;
    for(var y =0;y<resData.data_idx.length;y++){
        if (z>0){ var class_css = 'ciy_table hide';} else { class_css = 'ciy_table';}
        bodyContent += "<table class='"+class_css+"' data-slot='"+resData.data_idx[y]+"'><tr class='ciy_data'>";
        for(var j=Number(resData.configs.MAX_TIER);j>=1;j--){
            bodyContent += "<tr class='ciy_data'>";
            for(var k=1;k<=Number(resData.configs.MAX_ROW);k++){
                bodyContent += "<td data-row='" +k+ "' data-tier='" +j+ "' class='data'></td>";
            }
            bodyContent += "<td class='right_header'>" +j+ "</td></tr>";
        }
        bodyContent += "</table>";
        z++;
    }
    
    return (headerContent + bodyContent);
}

function fillBodyContent(resData){
    for(var key in resData.data){
        for(var i=0;i<resData.data[key].length;i++){
            $(".ciy_table[data-slot="+key+"] td[data-row="+resData.data[key][i].YD_ROW+"][data-tier="+resData.data[key][i].YD_TIER+"]").html(
                generateDataContainer(resData.data[key][i])
            ).addClass('stacking');
        }
    }
}

function generateDataContainer(dataContainer){
    return dataContainer.NO_CONTAINER + '<br />' +
        dataContainer.ID_ISO_CODE + ' ' + dataContainer.ID_COMMODITY + ' ' + dataContainer.ID_CLASS_CODE + '<br />' +
        dataContainer.ID_OPERATOR + ' ' + dataContainer.WEIGHT + 'T<br />' +
        dataContainer.ID_VESSEL + 
        '<span style=\'float:right;border:1px solid black;padding:2px\'>' + dataContainer.ID_POD + '</span><br />';
}

/**
 * Render Equipment
 */
function renderEquipment<?=$tab_id?>(){
    // render equipment 
    for (var i = 0; i < equipmentDataJson.length; i++) {

        var css_class = "equip-" + equipmentDataJson[i].MCH_SUB_TYPE.toLowerCase();
        var image_name = "assets/small/" + equipmentDataJson[i].MCH_SUB_TYPE.toLowerCase() + ".png";

        if (equipmentDataJson[i].LAST_JOB != null){
            var last_activity = "Last Activity: " + equipmentDataJson[i].LAST_JOB;	
        } else {
            last_activity = "No Activity";
        }
        
        if (equipmentDataJson[i].SLOT_ != null){
            var block_selector = 
                ".div_cell_block[title='"+equipmentDataJson[i].BLOCKNAME+"']"
                + "[data-slot="+equipmentDataJson[i].SLOT_+"]"
                + "[data-row=1]";
            var css_pos = $(block_selector).position();
            console.log('Equipment selector : ' + block_selector);
            
            if (css_pos != undefined){
                if (equipmentDataJson[i].MCH_SUB_TYPE == "RTG"){
                    var image_width = 8;
                    var image_height = 60;

                    css_pos.top = css_pos.top-8; // depends on RTG image
                    css_pos.left = css_pos.left+3;

                } else if (equipmentDataJson[i].MCH_SUB_TYPE == "RS"){
                    image_width = 8;
                    image_height = 60;

                    css_pos.top = css_pos.top+28//-8;//+77; // depends on RS image
                    css_pos.left = css_pos.left+71//+3;
                } else {
                    image_width = 0;
                    image_height = 0;
                }

                var string_content = "<div class=\"" + css_class +"\" "
                        + "style=\"top:" + css_pos.top + "px;left:" + css_pos.left + "px;\" "
                        + "title=\"" + last_activity + "\">"
                    + "<img src=\"<?=IMG_?>" + image_name + "\"  width=\"" +image_width+ "px\" height=\"" +image_height+ "px\" />"
                    + "<span class=\"equip-name " + css_class + "-name\" >"
                        + "<span class=\"equip-block-name\" style=\"background:" +equipmentDataJson[i].BG_COLOR+ "\">"
                            + equipmentDataJson[i].MCH_NAME
                        + "</span>"
                        /*+ "<span class=\"equip-block-act\" style=\"background:#FF1DCE\">"
                            + equipmentDataJson[i].LAST_JOB_MINI
                        + "</span>"*/
                    + "</span>"
                + "</div>";
                $("#equipment-group").append(string_content);
            }
        }
    };
}

</script>