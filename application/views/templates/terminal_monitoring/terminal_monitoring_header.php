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

.filtered { background: #03C03C!important; }

.unfiltered { background: #B2BEB5; }
</style>

<script>

/** 
 * Registered properties
 */
var filterType = ['pod', 'ves', 'opr', 'class_code'];
var listClass = "filtered unfiltered tier1 tier2 tier3 tier4 tier5 tier6";

/** 
 * Filter event binding
 */
$(".filter_<?=$tab_id?>").change(function(){
	var filterVal = $(".filter_<?=$tab_id?>>option:selected").map(function() { return $(this).val(); });
	filterContent_<?=$tab_id?>(filterVal);
});

/** 
 * Manipulate Yard content
 */
function changeLegend_<?=$tab_id?>(){
	$(".exist_<?=$tab_id?>").removeClass(listClass);
	
	$(".exist_<?=$tab_id?>[data-placement=1]").addClass('tier1');
	$(".exist_<?=$tab_id?>[data-placement=2]").addClass('tier2');
	$(".exist_<?=$tab_id?>[data-placement=3]").addClass('tier3');
	$(".exist_<?=$tab_id?>[data-placement=4]").addClass('tier4');
	$(".exist_<?=$tab_id?>[data-placement=5]").addClass('tier5');
	$(".exist_<?=$tab_id?>[data-placement=6]").addClass('tier6');
}

function filterContent_<?=$tab_id?>(filterVal){
	var filterQuery = ''; var noFilterQuery = '';
	
	for(var i=0; i<filterType.length; i++){
		if (filterVal[i] != '-'){
			filterQuery += "[data-" +filterType[i]+ "='" +filterVal[i]+ "']";
			if (noFilterQuery!='') { noFilterQuery += ","; }
			noFilterQuery += ".exist_<?=$tab_id?>[data-" +filterType[i]+ "!='" +filterVal[i]+ "']";
		}
	}
	
	console.log("FILTER: .exist_<?=$tab_id?>" + filterQuery);
	console.log("NO FILTER: " + noFilterQuery);
	
	if (filterQuery != ''){
		$(".exist_<?=$tab_id?>").removeClass(listClass);
		$(".exist_<?=$tab_id?>" + noFilterQuery).addClass('unfiltered')
		$(".exist_<?=$tab_id?>" + filterQuery).addClass('filtered');
	} else {
		changeLegend_<?=$tab_id?>(legendMode);
	}
}

/** 
 * Refresh Yard content
 */
 
function refreshMonitoring_<?=$tab_id?>(){
	$(".filter_<?=$tab_id?>").val('-');
	loadmask.show();
	Ext.get("mainmon_<?=$tab_id?>").load({
		url: '<?=controller_?>terminal_monitoring/load_data?tab_id=<?=$tab_id?>'
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
//	    Ext.Msg.alert('Success', $(this).val());
		if ($(this).val()!='-'){
			loadmask.show();
			Ext.getCmp("<?=$tab_id?>").getLoader().load({
				url: '<?=controller_?>terminal_monitoring?tab_id=<?=$tab_id?>&id_yard='+$(this).val(),
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
refreshMonitoring_<?=$tab_id?>();

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
			
			<?php if ($id_yard != null){ ?>
			<td width="250px">
				<select id="filter_pod_<?=$tab_id?>" name="filter_pod" class="filter_<?=$tab_id?>">
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
				<select id="filter_vessel_<?=$tab_id?>" name="filter_vessel" class="filter_<?=$tab_id?>">
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
				<select id="filter_carrier_<?=$tab_id?>" name="filter_carrier" class="filter_<?=$tab_id?>">
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
				<select id="filter_ei_<?=$tab_id?>" name="filter_ei" class="filter_<?=$tab_id?>">
					<option value="-">--Inbound/Outbound--</option>
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
					<a class="button-common" onclick="refreshMonitoring_<?=$tab_id?>(); return false;">Refresh</a>
				</span>
			</td>
			<?php } ?>
		</tr>
	</table>
</div>

<div id='mainmon_<?=$tab_id?>' class='mainmon'>
</div>

</div>