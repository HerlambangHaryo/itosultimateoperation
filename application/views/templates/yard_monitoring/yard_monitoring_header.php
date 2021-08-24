<!-- Maximum of Tier: 4 -->

<style>
.container_monitoring {
    position: relative;
}

.filter_component {
    width: 100%;
}

.filtermon {
    background-color: #ADD2ED;
    padding: 10px;
    width: <?php if ($id_yard != null){ echo "100"; } else { echo "40"; } ?>%;
    border-radius: 0px 0px 20px 0px;
}

.alnright {
    float: right;
    display: table-cell;
    text-align: right;
}

.button-common {
  background: #3498db;
  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
  background-image: -o-linear-gradient(top, #3498db, #2980b9);
  background-image: linear-gradient(to bottom, #3498db, #2980b9);
  -webkit-border-radius: 20;
  -moz-border-radius: 20;
  border-radius: 20px;
  font-family: Arial;
  color: #ffffff;
  font-size: 10px;
  padding: 7px 15px 7px 15px;
  text-decoration: none;
  cursor: pointer;
}

.button-common:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
}

.filtered { background: #03C03C; }

.unfiltered { background: #B2BEB5; }
</style>

<script>

/** 
 * Registered properties
 */
var legendModeType = {
    TIER: 1
};
var filterType = ['pod', 'ves', 'opr', 'class_code'];
var listClass = "filtered unfiltered tier1 tier2 tier3 tier4";
var legendMode = legendModeType.TIER;

/** 
 * Filter event binding
 */
$(".filter").change(function(){
    var filterVal = $(".filter>option:selected").map(function() { return $(this).val(); });
    filterContent(filterVal);
});

/** 
 * Manipulate Yard content
 */
function changeLegend(mode){
    $(".exist").removeClass(listClass);
    
    if (mode == legendModeType.TIER){
        $(".exist[data-placement=1]").addClass('tier1');
        $(".exist[data-placement=2]").addClass('tier2');
        $(".exist[data-placement=3]").addClass('tier3');
        $(".exist[data-placement=4]").addClass('tier4');
    }
}

function filterContent(filterVal){
    var filterQuery = ''; var noFilterQuery = '';
    
    for(var i=0; i<filterType.length; i++){
        if (filterVal[i] != '-'){
            filterQuery += "[data-" +filterType[i]+ "='" +filterVal[i]+ "']";
            if (noFilterQuery!='') { noFilterQuery += ","; }
            noFilterQuery += ".exist[data-" +filterType[i]+ "!='" +filterVal[i]+ "']";
        }
    }
    
    console.log("FILTER: .exist" + filterQuery);
    console.log("NO FILTER: " + noFilterQuery);
    
    if (filterQuery != ''){
        $(".exist").removeClass(listClass);
        $(".exist" + noFilterQuery).addClass('unfiltered')
        $(".exist" + filterQuery).addClass('filtered');
    } else {
        changeLegend(legendMode);
    }
}

/** 
 * Refresh Yard content
 */
function refreshMonitoring(){
    $(".filter").val('-');
    loadmask.show();
    Ext.get("mainmon_<?=$tab_id?>").load({
        url: '<?=controller_?>yard_monitoring/load_data?tab_id=<?=$tab_id?>'
            + '&id_yard='+$("#list_yard_<?=$tab_id?>").val()
            + '&pod='+$("#filter_pod_<?=$tab_id?>").val()
            + '&ves='+$("#filter_vessel_<?=$tab_id?>").val()
            + '&carr='+$("#filter_carrier_<?=$tab_id?>").val()
            + '&ei='+$("#filter_ei_<?=$tab_id?>").val(),
        scripts: true,
        contentType: 'html',
        autoLoad: true,
        success: function(){
            loadmask.hide();
        }
    });
}

$(function() {
    //Ext.getCmp('west_panel').collapse();
    $("#list_yard_<?=$tab_id?>").change(function() {
        if ($(this).val()!='-'){
            loadmask.show();
            Ext.getCmp("<?=$tab_id?>").getLoader().load({
                url: '<?=controller_?>yard_monitoring?tab_id=<?=$tab_id?>&id_yard='+$(this).val(),
                scripts: true,
                contentType: 'html',
                autoLoad: true,
                success: function(){
                    loadmask.hide();
                }
            });
        }
    });
});

<?php if ($id_yard != null){ ?>

Ext.getCmp('west_panel').collapse();
refreshMonitoring();

<?php } ?>

</script>

<div class='container_monitoring'>
<div id="yard_viewer_header_<?=$tab_id?>" class="filtermon">
	<table style="width:100%">
		<tr>
			<td width="100px">
				Yard:
			</td>
			<td width="150px">
				<select id="list_yard_<?=$tab_id?>" name="list_yard">
					<option value="-">--Select--</option>
					<?php
					foreach ($yard_list as $option){
					?>
						<option value="<?=$option['ID_YARD']?>" <?php if ($id_yard==$option['ID_YARD']) {?> selected <?php }?> ><?=$option['NAME']?></option>
					<?php
					}
					?>
				</select>
			</td>
            
            
            <!--@change 15/JUN/2015 -->
            <?php if ($id_yard != null){ ?>
            <!--td>
                <div class='filter_component'>
                    <span>Filter : </span>
                    <span><input type="checkbox" id="pod" value="POD" />POD </span>
                    <span><input type="checkbox" id="ves" value="VES" />Vessel </span>
                    <span><input type="checkbox" id="carr" value="CARR" />Carrier </span>
                    <span><input type="checkbox" id="ei" value="EI" />Export/Import </span>
                    <span style="margin-left:50px"><input type="checkbox" name="vehicle" value="PA" />Show PA </span>
                    <span class='alnright'>
                        <a class="button-common" onclick="refreshMonitoring(); return false;">Refresh</a>
                    </span>
                </div>
			</td -->
            <td width="250px">
				<select id="filter_pod_<?=$tab_id?>" name="filter_pod" class="filter">
					<option value="-">--POD--</option>
					<?php
                    
					foreach ($filter_data['pod'] as $option){
					?>
						<option value="<?=$option['POD']?>" <?php if ('a'==$option['POD']) {?> selected <?php }?> ><?=$option['PORT_NAME']?></option>
					<?php
					}
					?>
				</select>
			</td>
            <td width="350px">
				<select id="filter_vessel_<?=$tab_id?>" name="filter_vessel" class="filter">
					<option value="-">--Vessel--</option>
					<?php
					foreach ($filter_data['vessel'] as $option){
					?>
						<option value="<?=$option['ID_VES_VOYAGE']?>" <?php if ('a'==$option['ID_VES_VOYAGE']) {?> selected <?php }?> ><?=$option['VESSEL_DETAIL']?></option>
					<?php
					}
					?>
				</select>
			</td>
            <td width="100px">
				<select id="filter_carrier_<?=$tab_id?>" name="filter_carrier" class="filter">
					<option value="-">--Carrier--</option>
					<?php
					foreach ($filter_data['carrier'] as $option){
					?>
						<option value="<?=$option['ID_OPERATOR']?>" <?php if ('a'==$option['ID_OPERATOR']) {?> selected <?php }?> ><?=$option['OPERATOR_NAME']?></option>
					<?php
					}
					?>
				</select>
			</td>
            <td width="100px">
				<select id="filter_ei_<?=$tab_id?>" name="filter_ei" class="filter">
					<option value="-">--Outbound/Inbound--</option>
					<?php
					foreach ($filter_data['ei'] as $key => $value){
					?>
						<option value="<?=$key?>" <?php if ('a'==$key) {?> selected <?php }?> ><?=$value?></option>
					<?php
					}
					?>
				</select>
			</td>
            <td>
                <span class='alnright'>
                    <a class="button-common" onclick="refreshMonitoring(); return false;">Refresh</a>
                </span>
			</td>
            <?php } ?>
		</tr>
	</table>
</div>

<!-- Delegate container -->
<div id='mainmon_<?=$tab_id?>' class='mainmon'></div>
<div id='container_in_yard_view<?=$tab_id?>'></div>

</div>