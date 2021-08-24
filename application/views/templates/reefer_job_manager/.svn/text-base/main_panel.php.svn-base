<?php
//echo 'Ini menu rename container';
?>
<style>
.tabMainPanel3{

	background: linear-gradient(to top, #fcfbfb , #f7f6f6);
	box-shadow: 
		  0 1px 2px #fff, /*bottom external highlight*/
		  0 -1px 1px #9c9da2, /*top external shadow*/ 
		  inset 0 -1px 1px rgba(0,0,0,0.1), /*bottom internal shadow*/ 
		  inset 0 1px 1px rgba(255,255,255,0.8); /*top internal highlight*/
}
.fieldNya{
	border:1px solid #abadb3;
}
.colorNya{
	background-color:#f7f6f6;
}
</style>
<script>
var contIq="null";

$(document).ready(function(){
		$('#loadContentRfMon<?=$tab_id?>').load('<?=controller_?>reefer_job_manager/loadContents/'+contIq+'/<?=$tab_id?>');
	});

</script>
<div class="tabMainPanel3">
<table>
	<tr >
		<td valign="top">
			<fieldset class="fieldNya"><legend>Job Type</legend>
			<table>
				<tr><td><input type="radio" name="jobtype" value="monitoring">Monitoring<br>
						<input type="radio" name="jobtype" value="plout">After Plug<br>
						<input type="radio" name="jobtype" value="plinout">Unplug
					</td>
				</tr>
			</table>
			</fieldset>
		</td>
		<td valign="top"><fieldset class="fieldNya"><legend>Vessel Info</legend>
			<table>
				<tr><td>Vessel</td>
					<td><select id="optVesl" name="optVesl">
							<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						</select>
					</td>
					<td>Block</td>
					<td><select id="optBlock" name="optBlock">
							<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						</select>
					</td>
				</tr>
				<tr><td>&nbsp;</td>
					<td>&nbsp;
					</td>
					<td>Class</td>
					<td><select id="optClass" name="optClass">
							<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						</select>
					</td>
				</tr>
			</table>
			</fieldset></td>
		<td valign="top"><div onclick="refMonInquiry()" class="inquirySenter"><img src="images/flashlight.png" title="inquiry" width="18"/></div></td>
	</tr>
</table>
</div>
<div id="loadContentRfMon<?=$tab_id?>" width="100%"></div>