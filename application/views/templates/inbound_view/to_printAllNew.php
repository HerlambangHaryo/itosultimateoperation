
<?php 
	$MXWDTHA4=1034;
	$MXHGHTA4=733;
	
	$jumbay=count($bay_area);
	$top1=20;
	$konstantabox=12; //12
	
	$paramHeight1=$MXHGHTA4-100;
	
	$masingbay=($maxRow['MAXROW']+4)*($konstantabox);
	$pembagi=floor($MXWDTHA4/$masingbay);
	
	if($jumbay<$pembagi){
		$konstanta=50;
	}
	else
	{
		$konstanta=10;
	}
	$pengali=ceil($jumbay/$pembagi);
	$maxdivheight= (($maxRow['MAXTIER']+1+$pengali)*$konstantabox*$pengali)+$konstanta;
	//echo $maxdivheight.'<br>';
	while($maxdivheight>$paramHeight1)
	{
		$konstantabox=$konstantabox-2;
		$masingbay=($maxRow['MAXROW']+4)*($konstantabox);
		$pembagi=floor($MXWDTHA4/$masingbay);
		
		if($jumbay<$pembagi){
			$konstanta=50;
		}
		else
		{
			$konstanta=10;
		}
		$pengali=ceil($jumbay/$pembagi);
		$maxdivheight= (($maxRow['MAXTIER']+1+$pengali)*$konstantabox*$pengali)+$konstanta;
	}
	//echo $maxdivheight.'<br>';die;
	$maxdivwidth= $MXWDTHA4;
	
	
	
	
	$ei=$class_code;
	//echo $pengali;
	if($ei=='I')
	{
		$eiInfo='DISCHARGE PLAN';
	}
	else if($ei=='E')
	{
		$eiInfo='LOAD PLAN';
	}
	?>

<style>
.corner-left-label{
    /*right: 0.5px;
    top: 2px;
    font-size: <?=floor($konstantabox / 3)?>px;
    position: absolute;*/
    left: 2px;
    top: 1px;
    font-size: <?= floor($konstantabox / 4)?>px;
    position: absolute;
}

.display-none {
	display: none;
}

.corner-hq-left-label{
    /*right: 0.5px;
    top: 2px;
    font-size: <?=floor($konstantabox / 3)?>px;
    position: absolute;*/
    left: 0.4px;
    top: 1px;
    font-size: <?= floor($konstantabox / 4)?>px;
    position: absolute;
}

.div-job-seq{
	right: 1px;
    top: 1px;
	font-weight: bold;
    font-size: <?= floor($konstantabox / 4)?>px;
    position: absolute;
}

.ui-plan-default {
	background: rgba(0, 0, 0, 0) linear-gradient(to right bottom, rgb(57,159,233), rgb(57,159,233)) repeat scroll 0 0; 
	/*rgb(58,87,149), rgb(95,167,219)*/
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

.boxed1
{
	width:<?=$maxdivwidth;?>px;
	height:<?=$maxdivheight;?>px;

	position:absolute;
	font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
	top:<?=$top1;?>px;
}
.boxedPd
{
	width:<?=$MXWDTHA4;?>px;
	height:<?=($MXHGHTA4+20);?>px;
	border:1px solid gray;
	position:absolute;
	font-size:<?=($konstantabox+2);?>pt;font-family: calibri, serif;
	text-align:center;
}

</style>

<div class="boxedPd"><B><?=$eiInfo;?> <?=$vesselLD['VSVY'];?></B>
<div class="boxed1" align="right"><i>printed: <?=date('d-M-Y H:i');?>&nbsp;</i> 
	<?php
	$paramcell=1;
	$firstleft=10;
	$firsttop=20;
	$left=$firstleft;
	$top=$firsttop;
	$paramclass=1;
	foreach($bay_area as $bay){
		$maxwidth=($bay['MAX_ROW']+3.5)*$konstantabox;
		$hatch=$bay['HATCH_NUMBER'];
		if ($hatch > 0){
			$hatchpx=floor($bay['MAX_ROW']*$konstantabox/$hatch)-5;
		} else {
			$hatchpx=floor($bay['MAX_ROW']*$konstantabox)-5;
		}
		$maxheight=($bay['MAX_TIERUNDER']+$bay['MAX_TIERON']+5)*$konstantabox+3;
		$pembanding=($left+($maxwidth*2)+$konstantabox);
		$pembanding1=($left+$maxwidth)+$konstantabox;
		if((($pembanding>$maxdivwidth) && ($bay['BAYGENAP']=='')) or ($pembanding1>=$maxdivwidth))
		{
			$top=$top+$maxheight;
			$left=$firstleft;
		}
	?>
	<style>
	.boxedBig<?=$paramclass;?>
	{
		width:<?=$maxwidth;?>px;
		height:<?=$maxheight;?>px;
		border:1px solid gray;
		position:absolute;
		left:<?=$left;?>px;
		top:<?=$top;?>px;
	}
	</style>

	
	<div class="boxedBig<?=$paramclass;?>">
		<div id="NameBay<?=$paramclass;?>" style="border-spacing: 0;border-collapse: collapse;" align="center">
				
			<?php 
				if($bay['BAYGENAP']<>'')
				{
					$bayname=$bay['BAY'].'('.$bay['BAYGENAP'].')';
				}
				else
					$bayname=$bay['BAY'];
					
				/*cell Above*/
				$startleft=$konstantabox/4;
				$starttop=($konstantabox*2);
				$topclAbv=$starttop;
				$leftclAbv=$startleft;
				?>
				<div style="float: left; width: 15px;">
				    <?php 
				    if($vesselLD['ALONG_SIDE'] == 'P'){ 
					echo 'L';
				    }else{
					echo 'W';
				    }
				    ?>
				</div>
				<div style='font-size:<?=($konstantabox/2)+4;?>pt;font-family: calibri, serif;float: left; width:calc(100% - 32px);'><?=$bayname;?></div>
				<div style="float: right; width: 15px;">
				    <?php 
				    if($vesselLD['ALONG_SIDE'] == 'P'){ 
					echo 'W';
				    }else{
					echo 'L';
				    }
				    ?>
				</div>
				<br>
				<style>
				    .row-number{
					width:<?=$konstantabox;?>px;
					height:<?=$konstantabox;?>px;
					position:absolute;
					top:<?=$topclAbv;?>px;
					font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
				    }
				</style>
				<?php
				$odd = false;
				$n = -2;
				if( ($bay["MAX_ROW"] % 2) == 0){
					$start = $bay["MAX_ROW"];
				}else{
					$odd = true;
					$start = $bay["MAX_ROW"] - 1;
				}
//				echo 'jml row : '.$bay["MAX_ROW"];
				$left_row = 0 ;
				$temp_row = 0;
				$check_row = false;
				$isDisplayNone = false;
				$selisih = 0;
				$temp_row_1 = 0 ;
				$arr_total_row = array();
				$arr_left_row = array();
				for($j = 1; $j <= $bay["MAX_ROW"]; $j++){
					$row = str_pad($start,2,'0',STR_PAD_LEFT);
					$count_row = $this->vessel->get_count_row($ID_VESSEL, $bay['ID_BAY'],$row);
					if($j == 1){
						$temp_row_1 = $leftclAbv;
					} else if ($j == 2) {
						$selisih = $leftclAbv - $temp_row_1 ;
					}
					$left_row = $leftclAbv;
					if($count_row->JML < 1){
						$status_row_hid = 'display-none';
						$temp_row = $leftclAbv;
						$check_row=true;
						$isDisplayNone = true;
					} else {
						$status_row_hid = '';
						if($check_row){
							$left_row = $temp_row;
						} else if($isDisplayNone) {
							$left_row -= $selisih;
						}
						array_push($arr_total_row, $row);
						array_push($arr_left_row, $left_row);
						$check_row = false;
					}
				?>
				<div class="row-number <?=$status_row_hid?>" style="left:<?=$left_row;?>px;"><?=$row?></div>
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
				    $leftclAbv=$leftclAbv+$konstantabox;
				}
				?>
				    
				    
				<?php
				
				$rowB=1;
				$startleft=$konstantabox/4;
				$starttop=($konstantabox*2.5)+($konstantabox/2);
				$topclAbv=$starttop;
				$leftclAbv=$startleft;
				$firsttier=0;
//				$resCellAbv=$this->vessel->get_cellPerBayVesselAbv($ID_VESSEL,$id_ves_voyage,$bay['BAY'],'ABOVE',$ei);
				$resCellAbv = $this->vessel->get_vessel_profile_cellInfo($id_ves_voyage, $ei, $ID_VESSEL, $bay['ID_BAY'], $bay['BAY'], 'ABOVE');
				//debux($resCellAbv);die;

				$left_row = 0 ;
				$isDisplayNone = false;
				$putih =0;
				$arr_row = array();
				$i = 0;
				$i_putih =1;
				$tier_putih = 0;
				$top_tier = array($topclAbv);
				$tier = array();
				$topclAbvss = $topclAbv;
				$lastTopclAbvss = $topclAbv;
				$status_tier_hid=false;
				foreach($resCellAbv as $key => $rcAbv)
				{
					if($key == 0){
						$count_tier =  $this->vessel->get_count_tier($ID_VESSEL, $bay['ID_BAY'],$rcAbv['TIER_']);
						$status_tier_hid = ($count_tier->JML < 1) ? true : false;
						if($status_tier_hid){
							$tier_putih++;
						}
					}
					
					
					if($rowB>$bay['MAX_ROW'])
					{
						
						$leftclAbv=$startleft;
						$count_tier =  $this->vessel->get_count_tier($ID_VESSEL, $bay['ID_BAY'],$rcAbv['TIER_']);
						$topclAbv=$topclAbv+$konstantabox;
						
						array_push($top_tier, $topclAbv);
						if($count_tier->JML < 1){
							$topclAbvss = 0;
							$status_tier_hid = true;
							$tier_putih++;
						} else {
							$status_tier_hid = false;
							if(!in_array($rcAbv['TIER_'], $tier)){
								$topclAbvss = $top_tier[$i_putih-$tier_putih];
								$lastTopclAbvss = $topclAbvss;
							}
						}

						$rowB=1;
						$arr_row = array();
						$isDisplayNone = false;
						$putih=0;
						$i=0;
						$i_putih++;
						
					}

					array_push($tier, $rcAbv['TIER_']);
					array_push($arr_row, $leftclAbv);
					$left_row = $leftclAbv;
					if($rcAbv['STATUS_STACK']=='X')
					{
						$color='white';
						$left_row = $leftclAbv;
						$isDisplayNone = true;
						if(($arr_total_row[$i-$putih] != $rcAbv['ROW_'])){
							$putih++;
						} else {
							$left_row = $arr_row[$i-$putih];
						}
					}
					else{
						$color='gray';
						if($isDisplayNone){
							$left_row = $arr_row[$i-$putih];
						}
					}
					$i++;
					/*	
					if($rcAbv['CONT_STATUS']=='FCL')
					{
						$colorbull='red';
					}
					else if($rcAbv['CONT_STATUS']=='MTY')
						$colorbull='orange';
					
					if(($rcAbv['ID_CLASS_CODE']=='TI') OR ($rcAbv['ID_CLASS_CODE']=='TE')){
							$colorbull='blue';
						}
					if(($rcAbv['TL_FLAG']=='Y')){
							$colorbull='skyblue';
						}
					*/

					$colorbull = $rcAbv['ID_CLASS_CODE'] == 'TC' ? '#CCCCCC' : "#".$rcAbv['BACKGROUND_COLOR'];
					$colorfont = $rcAbv['ID_CLASS_CODE'] == 'TC' ? '#222222' : "#".$rcAbv['FOREGROUND_COLOR'];
					?>
					<style>
					.boxCont<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border:1px solid <?=$color;?>;
						display: <?=($color == 'white') ? 'none' : 'block'?>;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					.boxContX<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border:1px solid <?=$color;?>;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						color:<?=$colorfont?>
						background-color: <?=$colorbull?>;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					.boxContHq<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{

						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						background:
							
							linear-gradient(to bottom right,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(20% - 0.8px),
							rgba(0,0,0,1) 20%,
							rgba(0,0,0,0) calc(20% + 0.8px),
							rgba(0,0,0,0) 100%);
						text-align: center;
						vertical-align: middle;
						line-height:<?=$konstantabox;?>px;
						font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;
					}
					.boxContRbs<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{
						display: table-cell;
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						background:
							linear-gradient(to top right,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(25% - 0.8px),
							rgba(0,0,0,1) 25%,
							rgba(0,0,0,0) calc(25% + 0.8px),
							rgba(0,0,0,0) 100%),
							linear-gradient(to top left,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(25% - 0.8px),
							rgba(0,0,0,1) 25%,
							rgba(0,0,0,0) calc(25% + 0.8px),
							rgba(0,0,0,0) 100%),
							linear-gradient(to bottom left,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(25% - 0.8px),
							rgba(0,0,0,1) 25%,
							rgba(0,0,0,0) calc(25% + 0.8px),
							rgba(0,0,0,0) 100%),
							linear-gradient(to bottom right,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(25% - 0.8px),
							rgba(0,0,0,1) 25%,
							rgba(0,0,0,0) calc(25% + 0.8px),
							rgba(0,0,0,0) 100%);
						text-align: center;
						vertical-align: middle;
						line-height:<?=$konstantabox;?>px;
						font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;
					}
					.boxContXX<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border:1px solid <?=$color;?> !important;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						background:
							linear-gradient(to top left,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(50% - 0.8px),
							rgba(0,0,0,1) 50%,
							rgba(0,0,0,0) calc(50% + 0.8px),
							rgba(0,0,0,0) 100%),
							linear-gradient(to top right,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(50% - 0.8px),
							rgba(0,0,0,1) 50%,
							rgba(0,0,0,0) calc(50% + 0.8px),
							rgba(0,0,0,0) 100%) !important;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					.triaCont<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{ 
						width: <?=$konstantabox;?>px; 
						height: <?=$konstantabox;?>px; 
						border-top:1px solid grey;
						border-left:1px solid grey;
						color: <?=$colorfont?>;  
						background-image: linear-gradient(-45deg, white 50%, <?=$colorbull?> 51%);  
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						font-size:<?=($konstantabox/2)-2;?>pt;font-family: calibri, serif;
					}
					.fCont<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{ 
						width: <?=$konstantabox;?>px; 
						height: <?=$konstantabox;?>px; 
						border-top:1px solid grey;
						border-left:1px solid grey;
						/*background-image: linear-gradient(-45deg, white 50%, <?=$colorbull?> 51%);  */
						color: <?=$colorfont?>;
						background-color: <?=$colorbull?>;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						font-size:<?=($konstantabox/2)-2;?>pt;font-family: calibri, serif;
					}
					.uiUnAvb<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?> {
						border: 1px solid #ffffff;
						background: #ececec url(../../../../../../../config/CSS/excite-bike/images/40.png)  50% 50% repeat !important;
						width: <?=$konstantabox;?>px; 
						height: <?=$konstantabox;?>px; 
						border-top:1px solid grey;
						border-left:1px solid grey;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;

					}
					
					</style>
					<?php 
					    if ($rcAbv['CONT_40_LOCATION']=='FORE'){
						$classy='uiUnAvb';
					    }else{
						if ($rcAbv['CONT_SIZE']=='40')
						{
							$classy='fCont';
						}
						else if($rcAbv['CONT_SIZE']=='20'  || $rcAbv['CONT_SIZE']=='21'){
							$classy='triaCont';
						}
						else if($rcAbv['FUTURE40']=='40' || $rcAbv['FUTURE40']=='20'){
							$classy='boxContXX';
						}
						
						else
						{
							$classy='boxCont';
						}
					    }
					?> 
					<div 
					class="<?=$classy;?><?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:center<?}?>"
					>
						<?php 
							if ($rcAbv['CONT_40_LOCATION']!='FORE'){?>
							<?php 
							if($rcAbv['CONT_TYPE']=='HQ'){
								?>
									<div class="corner-hq-left-label">&#9701;</div>
							<?php
							}
							if($rcAbv['TL_FLAG']=='Y'){ ?>
									<!-- <div class="corner-left-label">&#10041;</div> -->
									<div class="corner-left-label">&#9660;</div>
							<?php }else if($rcAbv['HAZARD']=='Y'){ ?>
								<div class="corner-left-label">&#9674;</div>
							<?php } ?>
							<?php if ($rcAbv['SEQUENCE']!=''){ ?> 
						    		<div class="div-job-seq">
							    		<?php if ($rcAbv['STATUS']=='P') {
							    			echo $rcAbv['SEQUENCE'];
							    		} else { echo "C"; }  ?>
							    	</div>
							<?php } ?> 
							 <b><?php 
							 	if($filter == 'SIZE'){
										echo $rcAbv['CONT_SIZE'];
									}else if($filter == 'WEIGHT'){
										echo $rcAbv['WEIGHT'];
									}else if($filter == 'OPERATOR'){ 
										echo $rcAbv['ID_OPERATOR'];
									}else{
										echo $rcAbv['ID_COMMODITY'];
									} 
							 ?></b>
					
						<!--
					    <?php if($rcAbv['TL_FLAG']=='Y'){ ?>
							 <div class="corner-left-label">&#10041;</div> 
							<div class="div-tl-simbol">&#9660;</div>
						<?php } ?>
					     <b><?php if(($rcAbv['CONT_TYPE']=='RFR') and ($rcAbv['CONT_STATUS']=='FCL')) { echo 'R';} else if (($rcAbv['CONT_TYPE']=='TNK') and ($rcAbv['CONT_STATUS']=='FCL')){ echo 'T';}else if ($rcAbv['CONT_STATUS']=='MTY'){ echo 'M';}else if ($rcAbv['PLUGGING']=='Y'){ echo '.';};?></b> 
					    <b><?php echo $rcAbv['ID_COMMODITY']; ?></b> -->
					    <?php }?>
					</div>
					<!-- <?php
					if($rcAbv['HAZARD']=='Y'){
							$classy='boxContRbs';
						
					?>
					<div 
					class="<?=$classy;?><?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:center<?}?>"
					><b><?php if ($rcAbv['HAZARD']=='Y'){ echo $rcAbv['IMDG']; }?></b></div>
					<?php
					}
					else if($rcAbv['CONT_TYPE']=='HQ'){
							$classy='boxContHq';
					?>
					<div 
					class="<?=$classy;?><?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:center<?}?>"
					></div>
					<?php
					} ?> -->
					<?php $kst=1;
					if($rowB==$bay['MAX_ROW'])
					{
						$leftDr=$leftclAbv+$konstantabox;
					?>
						<style>
						.boxContR<?=$paramclass;?><?=$bay['BAY'];?><?=$rowB;?><?=$kst;?><?=$rcAbv['TIER_'];?>
						{
							font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
							width:<?=$konstantabox;?>px;
							height:<?=$konstantabox;?>px;
							border:1px solid white;
							position:absolute;
							display: <?=($status_tier_hid) ? 'none' : 'block'?>;
							left:<?=$leftDr;?>px;
							top:<?=$topclAbvss;?>px;
							
						}
						</style>
						<div class="boxContR<?=$paramclass;?><?=$bay['BAY'];?><?=$rowB;?><?=$kst;?><?=$rcAbv['TIER_'];?>"><?=$rcAbv['TIER_'];?></div>
					<?php	$kst++;
					}
					$leftclAbv=$leftclAbv+$konstantabox;
					$rowB++;
				}
				/*cell Above*/
			?>
				<br>
				<?PHP
				/*Hatch Cover*/
				$stHleft=$konstantabox-($konstantabox/2);
				if ($hatch > 0){
					for($i=1;$i<=$hatch;$i++){
					
					?>
					<style>
					.hatchst<?=$paramclass;?><?=$bay['BAY'];?><?=$i;?>
					{
						width:<?=$hatchpx;?>px;
						height:3px;
						background-color:black;
						position:absolute;
						left:<?=$stHleft;?>px;
						top:<?=$lastTopclAbvss+($konstantabox+($konstantabox/2));?>px;
						
					}
					.text-triangle-up{
						position:absolute;
						font-size: 6pt;
						color: white;
						margin-left: -5pt;
						margin-top: 2pt;
					}
					.text-triangle-down{
						position:absolute;
						font-size: 6pt;
						color: white;
						margin-left: -5pt;
						margin-top: -10pt;
					}
					.text-rectangle {
					   font-size: 6pt;
					   text-align: center;
					}
					.text-circle {
					   font-size: 6pt;
					   text-align: center;
					}
					/*Up pointing*/
					.triangle-up {
						margin-top: 2px;
						width: 10%;
						height: 0;    
						padding-left:10%;
						padding-bottom: 10%;
						overflow: hidden;
					}
					.triangle-up:after {
						content: "";
						display: block;
						width: 0;
						height: 0;
						margin-left:-125px;
						border-left: 120px solid transparent;
						border-right: 120px solid transparent;
						border-bottom: 240px solid black;
					}
					/*Down pointing*/
					.triangle-down {
						margin-top: 2px;
						width: 10%;
						height: 0;
						padding-left:10%;
						padding-top: 10%;
						overflow: hidden;
					}
					.triangle-down:after {
						content: "";
						display: block;
						width: 0;
						height: 0;
						margin-left:-65px;
						margin-top:-100px;
						border-left: 60px solid transparent;
						border-right: 60px solid transparent;
						border-top: 100px solid black;
					}
					.rectangle {
						margin-top: 2px;
						width:10px;
						height:10px; /* #4679BD*/
						border: 1px solid gray;
					}
					.circle {
						margin-top: 2px;
						width: 10px;
						height: 10px;
						border-radius: 50%;
						border: 1px solid gray;
					}
					</style>
					<div class="hatchst<?=$paramclass;?><?=$bay['BAY'];?><?=$i;?>"> </div>
					
					<?php
						$stHleft=$hatchpx+$stHleft+($konstantabox/4);
					}
				} else {
					?> 
					<style>
					.hatchstEmpty<?=$paramclass;?><?=$bay['BAY'];?>
					{
						width:<?=$hatchpx;?>px;
						height:3px;
						background-color:white;
						position:absolute;
						left:<?=$stHleft;?>px;
						top:<?=$lastTopclAbvss+($konstantabox+($konstantabox/2));?>px;
					}
					</style>
					<div class="hatchstEmpty<?=$paramclass;?><?=$bay['BAY'];?>"> </div>
					<?php
					$stHleft=$hatchpx+$stHleft+($konstantabox/4);
				}

				if($bay['BAYGENAP']<>'')
				{
					$bayD=$bay['BAYGENAP'];
				}
				else
					$bayD=$bay['BAY'];
				
					$sz2=20;
					$f20sumA=$this->vessel->get_vesselBaySum($id_ves_voyage,$bayD,'ABOVE',$class_code,$sz2);
					$f20sumB=$this->vessel->get_vesselBaySum($id_ves_voyage,$bayD,'BELOW',$class_code,$sz2);
					$sz4=40;
					$f40sumA=$this->vessel->get_vesselBaySum($id_ves_voyage,$bayD,'ABOVE',$class_code,$sz4);
					$f40sumB=$this->vessel->get_vesselBaySum($id_ves_voyage,$bayD,'BELOW',$class_code,$sz4);
				?>
				
				<!-- Alokasi dan sequence alat On Deck -->
				<?php 
					$data_mchplan = $this->vessel->get_machine_plan_dh($ID_VESSEL, $id_ves_voyage, $class_code, $rcAbv['ID_BAY'], 'D');
				?>
				<div style="left:<?=$stHleft+($konstantabox*1.5);?>;position:absolute;text-align: left;">
					<?php
					foreach($data_mchplan as $data_mchdet){
						echo "<div class=\"".$assigned_shape[$data_mchdet['ID_MACHINE']]."\">
							<div class=\"text-".$assigned_shape[$data_mchdet['ID_MACHINE']]."\">
							".$data_mchdet['SEQUENCE']."
							</div>
						</div>";
					}
					?>
				</div>
				
				<div style="left:<?=$stHleft+($konstantabox*1.5);?>;top:<?=$lastTopclAbvss+($konstantabox/2);?>;position:absolute;">
					<?=$f20sumA['JML'];?> + <?=$f40sumA['JML'];?><br><hr><?=$f20sumB['JML'];?> + <?=$f40sumB['JML'];?>
				</div>
				
				<!-- Alokasi dan sequence alat Under Deck -->
				<?php 
					$data_mchplan = $this->vessel->get_machine_plan_dh($ID_VESSEL, $id_ves_voyage, $class_code, $rcAbv['ID_BAY'], 'H');
				?>
				<div style="left:<?=$stHleft+($konstantabox*1.5);?>;top:<?=$lastTopclAbvss+($konstantabox/2)+30;?>;position:absolute;text-align: left;">
					<?php
					foreach($data_mchplan as $data_mchdet){
						echo "<div class=\"".$assigned_shape[$data_mchdet['ID_MACHINE']]."\">
							<div class=\"text-".$assigned_shape[$data_mchdet['ID_MACHINE']]."\">
							".$data_mchdet['SEQUENCE']."
							</div>
						</div>";
					}
					?>
				</div>
				
				<?php
					/*cell Below*/
				?>
				
				<br>
				
				<?php
				
				$rowB=1;
				$startleft=($konstantabox/4);
				$starttop=$lastTopclAbvss+($konstantabox*2);
				$topclAbv=$starttop;
				$leftclAbv=$startleft;
				$firsttier=0;
				
				$left_row = 0 ;
				$isDisplayNone = false;
				$putih =0;
				$arr_row = array();
				$i = 0;
				$i_putih =1;
				$tier_putih = 0;
				$top_tier = array($topclAbv);
				$tier = array();
//				$resCellAbv=$this->vessel->get_cellPerBayVesselAbv($ID_VESSEL,$id_ves_voyage,$bay['BAY'],'BELOW',$ei);
				$resCellAbv = $this->vessel->get_vessel_profile_cellInfo($id_ves_voyage, $ei, $ID_VESSEL, $bay['ID_BAY'], $bay['BAY'], 'BELOW');
				
				$topclAbvss = $topclAbv;
				$lastTopclAbvss2 = $topclAbv;
				$status_tier_hid = false;
				foreach($resCellAbv as $key => $rcAbv)
				{
					if($key == 0){
						$count_tier =  $this->vessel->get_count_tier($ID_VESSEL, $bay['ID_BAY'],$rcAbv['TIER_']);
						$status_tier_hid = ($count_tier->JML < 1) ? true : false;
						if($status_tier_hid){
							$tier_putih++;
						}
					}
					
					if($rowB>$bay['MAX_ROW'])
					{
						
						$leftclAbv=$startleft;
						$count_tier =  $this->vessel->get_count_tier($ID_VESSEL, $bay['ID_BAY'],$rcAbv['TIER_']);
						$topclAbv=$topclAbv+$konstantabox;
						
						array_push($top_tier, $topclAbv);
						if($count_tier->JML < 1){
							$topclAbvss = 0;
							$tier_putih++;
							$status_tier_hid = true;
						} else {
							$status_tier_hid = false;
							if(!in_array($rcAbv['TIER_'], $tier)){
								$topclAbvss = $top_tier[$i_putih-$tier_putih];
								$lastTopclAbvss2 = $topclAbvss;
							}
							
						}
						array_push($tier, $rcAbv['TIER_']);

						$rowB=1;
						$arr_row = array();
						$isDisplayNone = false;
						$putih=0;
						$i=0;
						$i_putih++;
						
					}

					array_push($arr_row, $leftclAbv);
					$left_row = $leftclAbv;
					if($rcAbv['STATUS_STACK']=='X')
					{
						$color='white';
						$left_row = $leftclAbv;
						$isDisplayNone = true;
						if(($arr_total_row[$i-$putih] != $rcAbv['ROW_'])){
							$putih++;
						} else {
							$left_row = $arr_row[$i-$putih];
						}
					}
					else{
						$color='gray';
						if($isDisplayNone){
							$left_row = $arr_row[$i-$putih];
						}
					}
					$i++;
						
					/*	
					if($rcAbv['CONT_STATUS']=='FCL')
					{
						$colorbull='red';
					}
					else if($rcAbv['CONT_STATUS']=='MTY')
						$colorbull='orange';
					
					if(($rcAbv['ID_CLASS_CODE']=='TI') OR ($rcAbv['ID_CLASS_CODE']=='TE')){
							$colorbull='blue';
						}
					if(($rcAbv['TL_FLAG']=='Y')){
							$colorbull='skyblue';
						}
					*/

					// $colorbull = "#".$rcAbv['BACKGROUND_COLOR'];
					$colorbull = $rcAbv['ID_CLASS_CODE'] == 'TC' ? '#CCCCCC' : "#".$rcAbv['BACKGROUND_COLOR'];
					$colorfont = $rcAbv['ID_CLASS_CODE'] == 'TC' ? '#222222' : "#".$rcAbv['FOREGROUND_COLOR'];
					?>
					<style>
					.boxCont<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						display: <?=($color == 'white') ? 'none' : 'block'?>;
						border:1px solid <?=$color;?>;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					.boxContX<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border:1px solid <?=$color;?>;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						color: <?=$colorfont?>;
						background-color: <?=$colorbull?>;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					.boxContHq<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{

						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						background:
							
							linear-gradient(to bottom right,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(20% - 0.8px),
							rgba(0,0,0,1) 20%,
							rgba(0,0,0,0) calc(20% + 0.8px),
							rgba(0,0,0,0) 100%);
						text-align: center;
						vertical-align: middle;
						line-height:<?=$konstantabox;?>px;
						font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;
					}
					.boxContRbs<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{
						display: table-cell;
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						background:
							linear-gradient(to top right,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(25% - 0.8px),
							rgba(0,0,0,1) 25%,
							rgba(0,0,0,0) calc(25% + 0.8px),
							rgba(0,0,0,0) 100%),
							linear-gradient(to top left,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(25% - 0.8px),
							rgba(0,0,0,1) 25%,
							rgba(0,0,0,0) calc(25% + 0.8px),
							rgba(0,0,0,0) 100%),
							linear-gradient(to bottom left,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(25% - 0.8px),
							rgba(0,0,0,1) 25%,
							rgba(0,0,0,0) calc(25% + 0.8px),
							rgba(0,0,0,0) 100%),
							linear-gradient(to bottom right,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(25% - 0.8px),
							rgba(0,0,0,1) 25%,
							rgba(0,0,0,0) calc(25% + 0.8px),
							rgba(0,0,0,0) 100%);
						text-align: center;
						vertical-align: middle;
						line-height:<?=$konstantabox;?>px;
						font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;
					}
					.boxContXX<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border:1px solid <?=$color;?> !important;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						background:
							linear-gradient(to top left,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(50% - 0.8px),
							rgba(0,0,0,1) 50%,
							rgba(0,0,0,0) calc(50% + 0.8px),
							rgba(0,0,0,0) 100%),
							linear-gradient(to top right,
							rgba(0,0,0,0) 0%,
							rgba(0,0,0,0) calc(50% - 0.8px),
							rgba(0,0,0,1) 50%,
							rgba(0,0,0,0) calc(50% + 0.8px),
							rgba(0,0,0,0) 100%) !important;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					.triaCont<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{ 
						width: <?=$konstantabox;?>px; 
						height: <?=$konstantabox;?>px; 
						border-top:1px solid grey;
						border-left:1px solid grey;
						color: <?=$colorfont?>;  
						background-image: linear-gradient(-45deg, white 50%, <?=$colorbull?> 51%);  
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						font-size:<?=($konstantabox/2)-2;?>pt;font-family: calibri, serif;
					}
					.fCont<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{ 
						width: <?=$konstantabox;?>px; 
						height: <?=$konstantabox;?>px; 
						border-top:1px solid grey;
						border-left:1px solid grey;
						/*background-image: linear-gradient(-45deg, white 50%, <?=$colorbull?> 51%);  */
						color: <?=$colorfont?>;
						background-color: <?=$colorbull?>;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						font-size:<?=($konstantabox/2)-2;?>pt;font-family: calibri, serif;
					}
					.uiUnAvb<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?> {
						border: 1px solid #ffffff;
						background: #ececec url(../../../../../../../config/CSS/excite-bike/images/40.png)  50% 50% repeat !important;
						width: <?=$konstantabox;?>px; 
						height: <?=$konstantabox;?>px; 
						border-top:1px solid grey;
						border-left:1px solid grey;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;

					}
					</style>
					<?php 
					
					    if ($rcAbv['CONT_40_LOCATION']=='FORE'){
						$classy='uiUnAvb';
					    }else{
						if ($rcAbv['CONT_SIZE']=='40')
						{
							$classy='fCont';
						}
						else if($rcAbv['CONT_SIZE']=='20'  || $rcAbv['CONT_SIZE']=='21'){
							$classy='triaCont';
						}
						else if($rcAbv['FUTURE40']=='40' || $rcAbv['FUTURE40']=='20'){
							$classy='boxContXX';
						}
						
						else
						{
							$classy='boxCont';
						}
					    }
					?> 
					<div data-40-loc="<?=$rcAbv['CONT_40_LOCATION']?>" 
					class="<?=$classy;?><?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:center<?}?>"
					>
						<?php if ($rcAbv['CONT_40_LOCATION']!='FORE'){?>
							<?php 
							if($rcAbv['CONT_TYPE']=='HQ'){
								?>
									<div class="corner-hq-left-label">&#9701;</div>
							<?php
							}	
							if($rcAbv['TL_FLAG']=='Y'){ ?>
									<!-- <div class="corner-left-label">&#10041;</div> -->
									<div class="corner-left-label">&#9660;</div>
							<?php }else if($rcAbv['HAZARD']=='Y'){ ?>
								<div class="corner-left-label">&#9674;</div>
							<?php } ?>
							<?php if ($rcAbv['SEQUENCE']!=''){ ?> 
						    		<div class="div-job-seq">
							    		<?php if ($rcAbv['STATUS']=='P') {
							    			echo $rcAbv['SEQUENCE'];
							    		} else { echo "C"; }  ?>
							    	</div>
							<?php } ?> 
							 <b><?php 
							 	if($filter == 'SIZE'){
									echo $rcAbv['CONT_SIZE'];
								}else if($filter == 'WEIGHT'){
									echo $rcAbv['WEIGHT'];
								}else if($filter == 'OPERATOR'){ 
									echo $rcAbv['ID_OPERATOR'];
								}else{
									echo $rcAbv['ID_COMMODITY'];
								} 
							 ?></b>
					
						<!--
					    <?php if($rcAbv['TL_FLAG']=='Y'){ ?>
							 <div class="corner-left-label">&#10041;</div> 
							<div class="div-tl-simbol">&#9660;</div>
						<?php } ?>
					     <b><?php if(($rcAbv['CONT_TYPE']=='RFR') and ($rcAbv['CONT_STATUS']=='FCL')) { echo 'R';} else if (($rcAbv['CONT_TYPE']=='TNK') and ($rcAbv['CONT_STATUS']=='FCL')){ echo 'T';}else if ($rcAbv['CONT_STATUS']=='MTY'){ echo 'M';}else if ($rcAbv['PLUGGING']=='Y'){ echo '.';};?></b> 
					    <b><?php echo $rcAbv['ID_COMMODITY']; ?></b> -->
						<?php }?>
					</div>
					<!-- <?php
					if($rcAbv['HAZARD']=='Y'){
							$classy='boxContRbs';
						
					?>
					<div 
					class="<?=$classy;?><?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:center<?}?>"
					><b><?php if ($rcAbv['HAZARD']=='Y'){ echo $rcAbv['IMDG']; }?></b></div>
					<?php
					}
					else if($rcAbv['CONT_TYPE']=='HQ'){
							$classy='boxContHq';
					?>
					<div 
					class="<?=$classy;?><?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:center<?}?>"
					></div>
					<?php
					} ?> -->
					<?php $kst=1;
					if($rowB==$bay['MAX_ROW'])
					{
						$leftDr=$leftclAbv+$konstantabox;
					?>
						<style>
						.boxContR<?=$paramclass;?><?=$bay['BAY'];?><?=$rowB;?><?=$kst;?><?=$rcAbv['TIER_'];?>
						{
							font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
							width:<?=$konstantabox;?>px;
							height:<?=$konstantabox;?>px;
							border:1px solid white;
							position:absolute;
							display: <?=($status_tier_hid) ? 'none' : 'block'?>;
							left:<?=$leftDr;?>px;
							top:<?=$topclAbvss;?>px;
							
						}
						</style>
						<div class="boxContR<?=$paramclass;?><?=$bay['BAY'];?><?=$rowB;?><?=$kst;?><?=$rcAbv['TIER_'];?>"><?=$rcAbv['TIER_'];?></div>
					<?php	$kst++;
					}
					$leftclAbv=$leftclAbv+$konstantabox;
					$rowB++;
				}
				/*cell Below*/
				?>
	<?php
		$startleft=$konstantabox/4;
		$leftclAbv=$startleft;
		$odd = false;
		$n = -2;
		if( ($bay["MAX_ROW"] % 2) == 0){
			$start = $bay["MAX_ROW"];
		}else{
			$odd = true;
			$start = $bay["MAX_ROW"] - 1;
		}
//				echo 'jml row : '.$bay["MAX_ROW"];
		foreach($arr_total_row as $key=> $row){
		?>
		<div class="row-number" style="left:<?=$arr_left_row[$key];?>px;top:<?=$lastTopclAbvss2+11?>"><?=$row?></div>
		<?php 
		}
	?>
	
		</div>
	</div>
	<?php
		$left=$left+$maxwidth;
		$paramclass=$paramclass+1;
	}
	?>
	
</div>
scr
<style>
	.remarksVes
	{
		left:20px;
		top:<?=($maxdivheight+($konstantabox*2)+$top1)+3;?>px;
		position:absolute;
		font-size:<?=($konstantabox-4);?>pt;font-family: calibri, serif;
	}
	.remarksBiasa
	{
		position:absolute;
		font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
	}
	.boxContXSample
	{
		width:<?=($konstantabox/2);?>px;
		height:<?=($konstantabox/2);?>px;
		border:1px solid grey;
		position:absolute;
		left:20px;
		top:<?=($maxdivheight+$konstantabox+$top1);?>px;
		background-color: black;
		font-size:<?=($konstantabox-4);?>pt;font-family: calibri, serif;
	}
	.boxContXXSample
	{
		width:<?=($konstantabox/2);?>px;
		height:<?=($konstantabox/2);?>px;
		border:1px solid grey !important;
		position:absolute;
		left:170px;
		top:<?=($maxdivheight+$konstantabox+$top1);?>px;
		background:
			linear-gradient(to top left,
			rgba(0,0,0,0) 0%,
			rgba(0,0,0,0) calc(50% - 0.8px),
			rgba(0,0,0,1) 50%,
			rgba(0,0,0,0) calc(50% + 0.8px),
			rgba(0,0,0,0) 100%),
			linear-gradient(to top right,
			rgba(0,0,0,0) 0%,
			rgba(0,0,0,0) calc(50% - 0.8px),
			rgba(0,0,0,1) 50%,
			rgba(0,0,0,0) calc(50% + 0.8px),
			rgba(0,0,0,0) 100%) !important;
		font-size:<?=($konstantabox-4);?>pt;font-family: calibri, serif;
	}
	.triaContSample
	{ 
		width: <?=($konstantabox/2);?>px; 
		height: <?=($konstantabox/2);?>px; 
		border-top:1px solid grey;
		border-left:1px solid grey;
		background-image: linear-gradient(135deg, white 50%, black 51%);  
		position:absolute;
		left:320px;
		top:<?=$maxdivheight+$konstantabox+$top1;?>px;
		font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
	}
	.triaContSample1
	{ 
		/*width: <?=($konstantabox/2);?>px; 
		height: <?=($konstantabox/2);?>px;*/
		width: 9px;
    	height: 9px; 
		border-top:1px solid grey;
		border-left:1px solid grey;
		/*background-image: linear-gradient(135deg, white 50%, black 51%);  */
		background-image: linear-gradient(135deg, white 50%, skyblue 51%);
		font-size:<?=($konstantabox-4);?>pt;font-family: calibri, serif;
		position:absolute;
		left: 468px;
		top:<?=$maxdivheight+$konstantabox+$top1;?>px;
		font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
	}
	
	/* alat */
	.legendeq1
	{
		width:<?=($konstantabox/2);?>px;
		height:<?=($konstantabox/2);?>px;
		position:absolute;
		left:20px;
		top:<?=($maxdivheight+($konstantabox*3)+$top1);?>px;
		font-size:<?=($konstantabox-4);?>pt;font-family: calibri, serif;
		width:10px;
		height:10px;
		border: 1px solid gray;
	}
	.legendeq2
	{
		width:<?=($konstantabox/2);?>px;
		height:<?=($konstantabox/2);?>px;
		position:absolute;
		left:170px;
		top:<?=($maxdivheight+($konstantabox*3)+$top1);?>px;
		font-size:<?=($konstantabox-4);?>pt;font-family: calibri, serif;
		width: 10px;
		height: 10px;
		border-radius: 50%;
		border: 1px solid gray;
	}
	.legendeq3
	{ 
		width: <?=($konstantabox/2);?>px; 
		height: <?=($konstantabox/2);?>px;  
		position:absolute;
		left:220px;
		top:<?=$maxdivheight+($konstantabox*3)+$top1;?>px;
		font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
		width: 10%;
		height: 0;    
		padding-left:10%;
		padding-bottom: 10%;
		overflow: hidden;
	}
	.legendeq3:after
	{
		content: "";
		display: block;
		width: 0;
		height: 0;
		margin-left:-6px;
		border-left: 6px solid transparent;
		border-right: 6px solid transparent;
		border-bottom: 10px solid black;
		
	}
	.legendeq4
	{ 
		width: <?=($konstantabox/2);?>px; 
		height: <?=($konstantabox/2);?>px;  
		position:absolute;
		left:370px;
		top:<?=$maxdivheight+($konstantabox*3)+$top1-93;?>px;
		font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
		width: 10%;
		height: 0;
		padding-left:10%;
		padding-top: 10%;
		overflow: hidden;
	}
	.legendeq4:after 
	{
		content: "";
		display: block;
		width: 0;
		height: 0;
		margin-left:-6px;
		margin-top:-10px;
		border-left: 6px solid transparent;
		border-right: 6px solid transparent;
		border-top: 10px solid black;
	}

	table,tr,td{
		border-spacing: 0;border-collapse: collapse;border:1px solid grey;
		font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
	}
</style>
<!-- 
<div class="boxContXSample"></div> <div class="remarksBiasa" style="left:50px;top:<?=$maxdivheight+($konstantabox)+$top1;?>;"><i> : 40' (rear)</i></div>
<div class="boxContXXSample"></div> <div class="remarksBiasa" style="left:200px;top:<?=$maxdivheight+$konstantabox+$top1;?>;"><i> : 40' (front)</i></div>
<div class="triaContSample"></div> <div class="remarksBiasa" style="left:350px;top:<?=$maxdivheight+$konstantabox+$top1;?>;"><i> : 20'</i></div>

<div class="triaContSample1">
	
<div class="corner-left-label">&#10041;</div>

</div> <div class="remarksBiasa" style="left:500px;top:<?=$maxdivheight+$konstantabox+$top1;?>;"><i> : Container TL</i></div>
 legend alat
<div class="legendeq1"></div> <div class="remarksBiasa" style="left:50px;top:<?=$maxdivheight+($konstantabox*3)+$top1;?>;"><i> : <?=isset($assigned_mch_name[0]) ? $assigned_mch_name[0] : '{reserved symbols}' ?></i></div>
<div class="legendeq2"></div> <div class="remarksBiasa" style="left:200px;top:<?=$maxdivheight+($konstantabox*3)+$top1;?>;"><i> : <?=isset($assigned_mch_name[1]) ? $assigned_mch_name[1] : '{reserved symbols}' ?></i></div>
<div class="legendeq3"></div> <div class="remarksBiasa" style="left:350px;top:<?=$maxdivheight+($konstantabox*3)+$top1;?>;"><i> : <?=isset($assigned_mch_name[2]) ? $assigned_mch_name[2] : '{reserved symbols}' ?></i></div>
<div class="legendeq4"></div> <div class="remarksBiasa" style="left:500px;top:<?=$maxdivheight+($konstantabox*3)+$top1;?>;"><i> : <?=isset($assigned_mch_name[3]) ? $assigned_mch_name[3] : '{reserved symbols}' ?></i></div>
<div class="legendeq4"></div> <div class="remarksBiasa" style="left:500px;top:<?=$maxdivheight+($konstantabox*3)+$top1;?>;"><i> : <?=isset($assigned_mch_name[3]) ? $assigned_mch_name[3] : '{reserved symbols}' ?></i></div> -->

<!--
<div class="remarksVes" align="left">
	<br>
	<table >
	<tr align="center">
		<td width="<?=($konstantabox*6);?>"></td>
		<td width="<?=($konstantabox*2);?>">20'</td>
		<td width="<?=($konstantabox*2);?>">40'</td>
		<td width="<?=($konstantabox*2);?>">40' HC</td>
		<td width="<?=($konstantabox*2);?>">45'</td>
		<td width="<?=($konstantabox*2);?>">Total</td>
	</tr>
	
<?php
		$repInfo=$this->vessel->getInfoStowageprint($id_ves_voyage,$ei,$ID_VESSEL);

		//debux($repInfo);

		foreach ($repInfo as $rowx)
		{
		?>
		<tr>
		<td ><span style="display:inline-block;"><?=$rowx['ITEMS'];?><?php if ($rowx['ITEMS']=='Full'){ $colorbox='red';}else if($rowx['ITEMS']=='Empty'){ $colorbox='orange';}else if($rowx['ITEMS']=='Transhipment (F)'){ $colorbox='blue';}else if($rowx['ITEMS']=='Transhipment (E)'){ $colorbox='blue';}else {$colorbox='white';}?></span><span style="width:<?=($konstantabox/2)?>px;height:<?=($konstantabox/2)?>px;background-color:<?=$colorbox;?>;display:inline-block;"></span> </td>
		<td align="right"><?=$rowx['Q20'];?></td>
		<td align="right"><?=$rowx['Q40'];?></td>
		<td align="right"><?=$rowx['Q40HC'];?></td>
		<td align="right"><?=$rowx['Q45'];?></td>
		<td align="right"><?=$rowx['QTOTAL'];?></td>
		</tr>
		<?php
		}
	?>
	</table>
</div>
-->
<div class="remarksVes">
	<table width="130" style="float: left !important;">
		<tr>
			<td>&#9701;</td><td>:</td><td>High Qube</td>
		</tr>
		<tr>
			<td>&#9660;</td><td>:</td><td>Container TL</td>
		</tr>
		<tr>
			<td>&#9674;</td><td>:</td><td>Container Hazard</td>
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
</div>

<div class="remarksVes" style="padding-left: 150px">
    <table border="1">
	<tr style="background-color: #CCCCCC;">
	    <th rowspan="2">POD</th>
<?php
	$span = 0;
	$prevSize = '';
	$prevType = '';
	foreach ($summary_head as $i => $h){
	    $lspan = 0;
//	    echo '<pre>'.$prevSize.' == '.$h['CONT_SIZE'].' && '.$prevType.' == '.$h['CONT_TYPE'].'</pre>';
	    if($prevSize == $h['CONT_SIZE'] && $prevType == $h['CONT_TYPE']){
//		echo debux('same');
		$span++;
	    }else{
//		echo debux('diff');
		if($span > 0){
		    echo "<th colspan='$span'>$prevSize'".($prevType == 'HC' ? $prevType : '')."</th>";
		}
		$span = 1;
		$prevSize = $h['CONT_SIZE'];
		$prevType = $h['CONT_TYPE'];
	    }
	    if($i == (count($summary_head) - 1)){
		echo "<th colspan='$span'>$prevSize'".($prevType == 'HC' ? $prevType : '')."</th>";
	    }
	}
?>
	    
	</tr>
	<tr style="background-color: #CCCCCC;">
<?php
	foreach ($summary_head as $h){
?>
	    <th><?=$h['GROUP_']?></th>   
<?php
	}
?>
	</tr>
<?php
	foreach ($summary_body as $b){
?>
	<tr>
	    <th><?=$b['ID_POD']?></th>  
<?php
	foreach ($summary_head as $h){
?>
	    <th><?=$b[$h['COL']]?></th>   
<?php
	}
?>
	</tr>  
<?php
	}
?>
	<tr>
	    
	</tr>
    </table>
</div>
</div>

