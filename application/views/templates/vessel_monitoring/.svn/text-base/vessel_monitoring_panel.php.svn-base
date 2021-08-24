<style type="text/css">
	#vessel {
	}

	.vessel_selector{
	    display: inline-block;
	    padding: 5px;
	    cursor: pointer;
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

	.vessel_mon { 
	}

	.vessel_mon span { 
	   position: absolute;
	   left: 10px;
	}

	.vessel_mon table { 
	   position: absolute;
	   border: solid black 1px;
	   left: 50px;
	   top: 10px;
	}

	.dl {
		font-weight: bold;
	}


</style>

<script type="text/javascript">
	Ext.create('Ext.form.Panel', {
		id: "container_search_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		items: [],
		buttons: [{
			text: 'Search',
			id: 'inquiry_button',
			formBind: true,
			listeners: {
				click: {
					fn: function () {
						alert("hulahula");
					}
				}
			}
		}]
	}).render('yard_monitoring_<?=$tab_id?>');
</script>

<div id="vessel">

	<?php foreach ($vessel as $key => $value) {
		echo "<figure class='vessel_selector'>
		        <img src='" .IMG_ . "assets/vessel-icon.jpg'width='140px' height='42px' />
		        <figcaption>"
		        . $value['VESSEL_NAME'] . "<br />"
		        . $value['ID_VES_VOYAGE'] .
		        "</figcaption>
		    </figure>";
	} ?>
</div>

<!-- div id="left_container">
	<div class="vessel_mon">
		<img src="<?=IMG_?>assets/kapal.png" width="400px" height="110px" />
		<span id="disch" class="dl">DISCH</span>

		<table>
			<tr>
				<td>a</td>
				<td>b</td>
				<td>c</td>
			</tr>
			<tr>
				<td>a</td>
				<td>b</td>
				<td>c</td>
			</tr>
			<tr>
				<td>a</td>
				<td>b</td>
				<td>c</td>
			</tr>
		</table>
	</div>

	<div class="vessel_mon">
		<img src="<?=IMG_?>assets/kapal.png" width="400px" height="110px" />
		<span id="load" class="dl">LOAD</span>
	</div>
</div -->

<div id="right_container">
	
</div>

<div id="bottom_container">
	
</div>

<div id="yard_monitoring_<?=$tab_id?>"></div>