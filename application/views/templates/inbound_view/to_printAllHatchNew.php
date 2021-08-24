
<?PHP 
	$MXWDTHA4=2268+374;
	$MXHGHTA4=1566+40;
	
	$jumbay=count($bay_area);
	$top1=20;
	$konstantabox=44;
	$max_fontbox=20;
	
	$paramHeight1=$MXHGHTA4-100;
	
	$masingbay=($maxRow['MAXROW']+4)*($konstantabox);
	$pembagi=floor($MXWDTHA4/$masingbay);
	
	if($jumbay<$pembagi){
		$konstanta=73;
	}
	else
	{
		$konstanta=33;
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
	ELSE if($ei=='E')
	{
		$eiInfo='LOAD PLAN';
	}
	$sisi_miring = sqrt(2 * ($konstantabox * $konstantabox));
	?>
<style>
.corner-left-label{
    right: 5.2px;
    top: -1px;
    font-size: <?= floor($konstantabox / 4)?>px;
    position: absolute;
}

.corner-hq-left-label{
    right: 0.4px;
    top: -1px;
    font-size: <?= floor($konstantabox / 4)?>px;
    position: absolute;
}

.div-job-seq{
	left: 1px;
    top: -1px;
	font-weight: bold;
    font-size: <?= floor($konstantabox / 4)?>px;
    position: absolute;
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
	height:<?=$MXHGHTA4;?>px;
	border:1px solid gray;
	position:absolute;
	font-size:<?=($max_fontbox);?>pt;font-family: calibri, serif;
	text-align:center;
}

.display-none {
	display: none;
}

.text_nocontainer
{
	margin-top: 1px;
	font-size: 6pt;
	font-family: 'Courier New', Courier, 'Lucida Sans Typewriter', 'Lucida Typewriter', monospace;
	font-weight: bold;
}
.text_nocontainer_small
{
	font-size: 5pt;
	font-family: 'Courier New', Courier, 'Lucida Sans Typewriter', 'Lucida Typewriter', monospace;
}
.text_pod
{
	margin-top: 3px;
	font-size: 6pt;
	font-family: 'Courier New', Courier, 'Lucida Sans Typewriter', 'Lucida Typewriter', monospace;
	color: gray;
}
.text_weight
{
	font-size: 5pt;
	font-family: 'Courier New', Courier, 'Lucida Sans Typewriter', 'Lucida Typewriter', monospace;
	color: red;
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
		//echo '&& ' . $hatchpx;
		$maxheight=($bay['MAX_TIERUNDER']+$bay['MAX_TIERON']+5)*$konstantabox+8;
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
				<div style='font-size:<?=($konstantabox/2)+4;?>pt;font-family: calibri, serif;'><?=$bayname;?></div>
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
				$status_tier_hid=false;
				$lastTopclAbvss = $topclAbv;
				foreach($resCellAbv as $key => $rcAbv)
				{
					/*remarks row atas*/
					if($key == 0){
						$count_tier =  $this->vessel->get_count_tier($ID_VESSEL, $bay['ID_BAY'],$rcAbv['TIER_']);
						$status_tier_hid = ($count_tier->JML < 1) ? true : false;
						if($status_tier_hid){
							$tier_putih++;
						}
					}

					if($rowB==1)
					{
						$firsttier=$rcAbv['TIER_'];
					}
					
					if($firsttier==$rcAbv['TIER_']) 
					{
						$topDr=$topclAbv-$konstantabox;
					?>
						<style>
						.boxConte<?=$paramclass;?>b<?=$bay['BAY'];?>rD<?=$rowB;?>
						{
							font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
							width:<?=$konstantabox;?>px;
							height:<?=$konstantabox;?>px;
							border:1px solid white;
							position:absolute;
							left:<?=$left_row;?>px;
							top:<?=$topDr;?>px;
							
						}
						</style>
						
					<?php
						
					}
					/*remarks row atas*/
					
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
					?>
					<style>
					.boxCont<?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>
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
					.boxContX<?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>
					{
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border:1px solid <?=$color;?>;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						background-color: <?=$colorbull?>;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					
					.boxContXX<?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>
					{
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border:1px solid <?=$color;?>;
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
							rgba(0,0,0,0) 100%);
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					.boxContRbs<?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>
					{
						display: table-cell;
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border:1px solid <?=$color;?>;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						/*background:
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
							rgba(0,0,0,0) 100%);*/
						text-align: center;
						vertical-align: middle;
						/*line-height:<?=$konstantabox;?>px;*/
						font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;
					}
					.boxContHq<?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>
					{
						display: table-cell;
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border: 1px solid gray;
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
						/*line-height:<?=$konstantabox;?>px;*/
						font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;
					}
					.triaCont<?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>
					{ 
						width: <?=$konstantabox;?>px; 
						height: <?=$konstantabox;?>px; 
						border-top:1px solid grey;
						border-left:1px solid grey;
						background-image: linear-gradient(135deg, white 50%, <?=$colorbull?> 51%);  
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					</style>
					<?php 
						// remove size container
						// if ($rcAbv['CONT_SIZE']=='40')
						// {
							// $classy='boxContX';
						// }
						// else if($rcAbv['CONT_SIZE']=='20'){
							// $classy='triaCont';
						// }
						// else if($rcAbv['FUTURE40']=='40'){
							// $classy='boxContXX';
						// }
						// else
						// {
							// $classy='boxCont';
						// }
						$classy='boxCont';
						
					?>
					<!--div 
					class="<?=$classy;?><?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>"
					><b><?php if(($rcAbv['CONT_TYPE']=='RFR') and ($rcAbv['CONT_STATUS']=='FCL')) { echo 'R';} else if (($rcAbv['CONT_TYPE']=='TNK') and ($rcAbv['CONT_STATUS']=='FCL')){ echo 'T';}else if ($rcAbv['CONT_STATUS']=='MTY'){ echo 'e';}else if ($rcAbv['PLUGGING']=='Y'){ echo '.';};?></b></div>
					<?
					if($rcAbv['HAZARD']=='Y'){
							$classy='boxContRbs';
						
					?>
					<div 
					class="<?=$classy;?><?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>"
					><b><?php if ($rcAbv['HAZARD']=='Y'){ echo $rcAbv['IMDG']; }?></b></div>
					<?
					}
					else if($rcAbv['CONT_TYPE']=='HQ'){
							$classy='boxContHq';
					?>
					<div 
					class="<?=$classy;?><?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>"
					></div-->
					
					<?php
					} ?>
					
					<!-- show container number and POD -->
					<div 
					class="<?=$classy;?><?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>">
						<?php
						if($rcAbv['CONT_TYPE']=='HQ'){
							?>
								<div class="corner-hq-left-label">&#9701;</div>
						<?php
						}
						if($rcAbv['TL_FLAG']=='Y'){
								?>
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
						<div class="text_nocontainer"><?= substr($rcAbv['NO_CONTAINER'],0,4)?></div>
						<div class="text_nocontainer_small"><?=substr($rcAbv['NO_CONTAINER'],4)?></div>
						<div class="text_pod"><?=$rcAbv['ID_POD']?></div>
						<div class="text_weight"><?=$rcAbv['WEIGHT']?></div>
					</div>
					
					<?php
					$kst=1;
					if($rowB==$bay['MAX_ROW'])
					{
						
						$leftDr=$leftclAbv+$konstantabox;
					?>
						<style>
						.boxContR<?=$paramclass;?><?=$bay['BAY'];?><?=$rowB;?>DT<?=$kst;?><?=$rcAbv['TIER_'];?>
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
						<div class="boxContR<?=$paramclass;?><?=$bay['BAY'];?><?=$rowB;?>DT<?=$kst;?><?=$rcAbv['TIER_'];?>"><?=$rcAbv['TIER_'];?></div>
					<?php	$kst=$kst+1;
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
				
				$sz=20;
				$f20sumA=$this->vessel->get_vesselBaySum($id_ves_voyage,$bayD,'ABOVE',$class_code,$sz);
				$f20sumB=$this->vessel->get_vesselBaySum($id_ves_voyage,$bayD,'BELOW',$class_code,$sz);
				$sz=40;
				$f40sumA=$this->vessel->get_vesselBaySum($id_ves_voyage,$bayD,'ABOVE',$class_code,$sz);
				$f40sumB=$this->vessel->get_vesselBaySum($id_ves_voyage,$bayD,'BELOW',$class_code,$sz);
				?>
				
				<!-- Alokasi dan sequence alat On Deck -->
				<?php 
					$data_mchplan = $this->vessel->get_machine_plan_dh($ID_VESSEL, $id_ves_voyage, $class_code, $rcAbv['ID_BAY'], 'D');
				?>
				<div style="left:<?=$stHleft+($konstantabox*1.5)-10;?>;position:absolute;text-align: left;">
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
				
				<div style="left:<?=$stHleft+($konstantabox*1.5)-10;?>;top:<?=$lastTopclAbvss+($konstantabox/2);?>;position:absolute;">
					<?=$f20sumA['JML'];?> + <?=$f40sumA['JML'];?><br><hr><?=$f20sumB['JML'];?> + <?=$f40sumB['JML'];?>
				</div>
				
				<!-- Alokasi dan sequence alat Under Deck -->
				<?php 
					$data_mchplan = $this->vessel->get_machine_plan_dh($ID_VESSEL, $id_ves_voyage, $class_code, $rcAbv['ID_BAY'], 'H');
				?>
				<div style="left:<?=$stHleft+($konstantabox*1.5)-10;?>;top:<?=$lastTopclAbvss+($konstantabox/2)+30;?>;position:absolute;text-align: left;">
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
//				$resCellAbv=$this->vessel->get_cellPerBayVesselAbv($ID_VESSEL,$id_ves_voyage,$bay['BAY'],'BELOW',$class_code);
				$resCellAbv = $this->vessel->get_vessel_profile_cellInfo($id_ves_voyage, $ei, $ID_VESSEL, $bay['ID_BAY'], $bay['BAY'], 'BELOW');
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
				$status_tier_hid=false;
				$lastTopclAbvss2 = $topclAbv;
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
						background-color: <?=$colorbull?>;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					.boxContHq<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{

						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border: 1px solid gray;
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
						/*line-height:<?=$konstantabox;?>px;*/
						font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;
					}
					.boxContRbs<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{
						display: table-cell;
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border:1px solid <?=$color;?>;
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						/*background:
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
							rgba(0,0,0,0) 100%);*/
						text-align: center;
						vertical-align: middle;
						/*line-height:<?=$konstantabox;?>px;*/
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
						background-image: linear-gradient(135deg, white 50%, <?=$colorbull?> 51%);  
						position:absolute;
						left:<?=$left_row;?>px;
						top:<?=$topclAbvss;?>px;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					
					</style>
					<?php 
						// remove size container
						// if ($rcAbv['CONT_SIZE']=='40')
						// {
							// $classy='boxContX';
						// }
						// else if($rcAbv['CONT_SIZE']=='20'){
							// $classy='triaCont';
						// }
						// else if($rcAbv['FUTURE40']=='40'){
							// $classy='boxContXX';
						// }
						
						// else
						// {
							// $classy='boxCont';
						// }
						
						$classy='boxCont';
						

					?>
					<!--div 
					class="<?=$classy;?><?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>"
					><b><?php if(($rcAbv['CONT_TYPE']=='RFR') and ($rcAbv['CONT_STATUS']=='FCL')) { echo 'R';} else if (($rcAbv['CONT_TYPE']=='TNK') and ($rcAbv['CONT_STATUS']=='FCL')){ echo 'T';}else if ($rcAbv['CONT_STATUS']=='MTY'){ echo 'e';}else if ($rcAbv['PLUGGING']=='Y'){ echo '.';};?></b></div>
					<?
					if($rcAbv['HAZARD']=='Y'){
							$classy='boxContRbs';
						
					?>
					<div 
					class="<?=$classy;?><?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>"
					><b><?php if ($rcAbv['HAZARD']=='Y'){ echo $rcAbv['IMDG']; }?></b></div>
					<?
					}
					else if($rcAbv['CONT_TYPE']=='HQ'){
							$classy='boxContHq';
					?>
					<div 
					class="<?=$classy;?><?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>"
					></div-->
					<?php
					}
					?>
					
					<!-- show container number and POD -->
					<div 
					class="<?=$classy;?><?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>" style="<?php if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>">
						<?php
						if($rcAbv['CONT_TYPE']=='HQ'){
							?>
								<div class="corner-hq-left-label">&#9701;</div>
						<?php
						}
						if($rcAbv['TL_FLAG']=='Y'){ ?>
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
							<div class="text_nocontainer"><?= substr($rcAbv['NO_CONTAINER'],0,4)?></div>
						<div class="text_nocontainer_small"><?=substr($rcAbv['NO_CONTAINER'],4)?></div>
						<div class="text_pod"><?=$rcAbv['ID_POD']?></div>
						<div class="text_weight"><?=$rcAbv['WEIGHT']?></div>
					</div>
					
					<?php
					$kst=1;
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
							left:<?=$leftDr;?>px;
							display: <?=($status_tier_hid) ? 'none' : 'block'?>;
							top:<?=$topclAbvss;?>px;
							
						}
						</style>
						<div class="boxContR<?=$paramclass;?><?=$bay['BAY'];?><?=$rowB;?><?=$kst;?><?=$rcAbv['TIER_'];?>"><?=$rcAbv['TIER_'];?></div>
					<?	$kst++;
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
				<div class="row-number" style="left:<?=$arr_left_row[$key];?>px;;top:<?=$lastTopclAbvss2+46?>"><?=$row?></div>
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

<style>
	.remarksVes
	{
		left:20px;
		top:<?=($maxdivheight+$konstantabox+$top1+($maxdivheight/2))+20;?>px;
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
		left:110px;
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
		left:260px;
		top:<?=$maxdivheight+($konstantabox*3)+$top1-215;?>px;
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
		font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;
	}
</style>
<!-- <div class="boxContXSample"></div> <div class="remarksBiasa" style="left:50px;top:<?=$maxdivheight+($konstantabox)+$top1;?>;font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;"><i> : 40' (rear)</i></div>
<div class="boxContXXSample"></div> <div class="remarksBiasa" style="left:200px;top:<?=$maxdivheight+$konstantabox+$top1;?>;font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;"><i> : 40' (front)</i></div>
<div class="triaContSample"></div> <div class="remarksBiasa" style="left:350px;top:<?=$maxdivheight+$konstantabox+$top1;?>;font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;"><i> : 20'</i></div>


<div class="legendeq1"></div> <div class="remarksBiasa" style="left:50px;top:<?=$maxdivheight+($konstantabox*3)+$top1;?>;font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;"><i> : <?=isset($assigned_mch_name[0]) ? $assigned_mch_name[0] : '{reserved}' ?></i></div>
<div class="legendeq2"></div> <div class="remarksBiasa" style="left:200px;top:<?=$maxdivheight+($konstantabox*3)+$top1;?>;font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;"><i> : <?=isset($assigned_mch_name[1]) ? $assigned_mch_name[1] : '{reserved}' ?></i></div>
<div class="legendeq3"></div> <div class="remarksBiasa" style="left:350px;top:<?=$maxdivheight+($konstantabox*3)+$top1;?>;font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;"><i> : <?=isset($assigned_mch_name[2]) ? $assigned_mch_name[2] : '{reserved}' ?></i></div>
<div class="legendeq4"></div> <div class="remarksBiasa" style="left:500px;top:<?=$maxdivheight+($konstantabox*3)+$top1;?>;font-size:<?=($konstantabox/3);?>pt;font-family: calibri, serif;"><i> : <?=isset($assigned_mch_name[3]) ? $assigned_mch_name[3] : '{reserved}' ?></i></div>

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
		<?
		}
	?>
	</table>
</div> -->

</div>

	<?php if($ei == 'I'){ 
		//echo $id_ves_voyage;
		$g = $this->vessel->getSummaryContByVesselAndCommodity($id_ves_voyage,'G');
		$h = $this->vessel->getSummaryContByVesselAndCommodity($id_ves_voyage,'H');
		$m = $this->vessel->getSummaryContByVesselAndCommodity($id_ves_voyage,'M');
		$r = $this->vessel->getSummaryContByVesselAndCommodity($id_ves_voyage,'R');

		//debux($r->DUAPULUH);

		$t20 = $g->DUAPULUH + $h->DUAPULUH + $m->DUAPULUH + $r->DUAPULUH;
		$t40 = $g->EMPATPULUH + $h->EMPATPULUH + $m->EMPATPULUH + $r->EMPATPULUH;
		$t40hc = $g->EMPATHC + $h->EMPATHC + $m->EMPATHC + $r->EMPATHC;
		$t45 = $g->EMPATLIMA + $h->EMPATLIMA + $m->EMPATLIMA + $r->EMPATLIMA;
		$t = $g->TOTAL + $h->TOTAL + $m->TOTAL + $r->TOTAL;

	?>	
	<div class="remarksVes">

		<table border="1">
			<tr style="background-color: #CCCCCC;">
				<th></th>
				<th>20'</th>
				<th>40'</th>
				<th>40'HC</th>
				<th>45'</th>
				<th>Total</th>
			</tr>
			<tr>
				<th style="background-color: #CCCCCC;">G</th>
				<th><?php echo $g->DUAPULUH; ?></th>
				<th><?php echo $g->EMPATPULUH; ?></th>
				<th><?php echo $g->EMPATHC; ?></th>
				<th><?php echo $g->EMPATLIMA; ?></th>
				<th><?php echo $g->TOTAL; ?></th>
			</tr>
			<tr>
				<th style="background-color: #CCCCCC;">H</th>
				<th><?php echo $h->DUAPULUH; ?></th>
				<th><?php echo $h->EMPATPULUH; ?></th>
				<th><?php echo $h->EMPATHC; ?></th>
				<th><?php echo $h->EMPATLIMA; ?></th>
				<th><?php echo $h->TOTAL; ?></th>
			</tr>
			<tr>
				<th style="background-color: #CCCCCC;">M</th>
				<th><?php echo $m->DUAPULUH; ?></th>
				<th><?php echo $m->EMPATPULUH; ?></th>
				<th><?php echo $m->EMPATHC; ?></th>
				<th><?php echo $m->EMPATLIMA; ?></th>
				<th><?php echo $m->TOTAL; ?></th>
			</tr>
			<tr>
				<th style="background-color: #CCCCCC;">R</th>
				<th><?php echo $r->DUAPULUH; ?></th>
				<th><?php echo $r->EMPATPULUH; ?></th>
				<th><?php echo $r->EMPATHC; ?></th>
				<th><?php echo $r->EMPATLIMA; ?></th>
				<th><?php echo $r->TOTAL; ?></th>
			</tr>
			<tr>
				<th style="background-color: #CCCCCC;">Total</th>
				<th><?php echo $t20; ?></th>
				<th><?php echo $t40; ?></th>
				<th><?php echo $t40hc; ?></th>
				<th><?php echo $t45; ?></th>
				<th><?php echo $t; ?></th>
			</tr>
		</table>
	</div>
	<div class="remarksVes" style="padding-left: 250px">
		<table border="1">
			<tr style="background-color: #CCCCCC;">
				<th></th>
				<th>20'</th>
				<th>40'</th>
				<th>40'HC</th>
				<th>45'</th>
				<th>Total</th>
			</tr>
			<tr>
				<th>II</th>
				<th><?php echo $t20; ?></th>
				<th><?php echo $t40; ?></th>
				<th><?php echo $t40hc; ?></th>
				<th><?php echo $t45; ?></th>
				<th><?php echo $t; ?></th>
			</tr>
			<tr>
				<th>Total</th>
				<th><?php echo $t20; ?></th>
				<th><?php echo $t40; ?></th>
				<th><?php echo $t40hc; ?></th>
				<th><?php echo $t45; ?></th>
				<th><?php echo $t; ?></th>
			</tr>
		</table>
	</div>
	<?php }else{ ?>
		<div class="remarksVes">
			<table border="1">
				<tr style="background-color: #CCCCCC;">
					<th></th>
					<th colspan="5">20'</th>
					<th colspan="5">40'</th>
					<th colspan="5">40'HC</th>
					<th colspan="5">45'</th>
					<th colspan="5">Total</th>
				</tr>
				<tr style="background-color: #CCCCCC;">
					<th></th>
					<th>H</th>
					<th>OOG</th>
					<th>R</th>
					<th>F</th>
					<th>M</th>
					<th>H</th>
					<th>OOG</th>
					<th>R</th>
					<th>F</th>
					<th>M</th>
					<th>H</th>
					<th>OOG</th>
					<th>R</th>
					<th>F</th>
					<th>M</th>
					<th>H</th>
					<th>OOG</th>
					<th>R</th>
					<th>F</th>
					<th>M</th>
					<th>20'</th>
					<th>40'</th>
					<th>40'HC</th>
					<th>45'</th>
					<th>Total</th>
				</tr>
				<?php $pod = getPodOutbound($id_ves_voyage);
				foreach($pod as $vpod){
					$_20 = getDataOutboundBySize2021($id_ves_voyage,$vpod['ID_POD']);
					$_40 = getDataOutboundBySize($id_ves_voyage,40,$vpod['ID_POD']);
					$_45 = getDataOutboundBySize($id_ves_voyage,45,$vpod['ID_POD']);
					$_40hc = getDataOutboundHC($id_ves_voyage,$vpod['ID_POD']);

					//debux($_40);

					$r20 = 0;
					$h20 = 0;
					$oog20 = 0;
					$m20 = 0;
					$f20 = 0;
					foreach($_20 as $v20){
						if($v20['VSB_BAY'] != NULL){
							if($v20['CONT_HEIGHT'] == 'OOG'){
								$oog20 += 1;
							}else{
								if($v20['CONT_STATUS']=='MTY'){
									$m20 += 1;
								}else{
									if($v20['ID_COMMODITY']=='R'){
										$r20 += 1;
									}else if($v20['ID_COMMODITY']=='RH'){
										$r20 += 1;
									}else if($v20['ID_COMMODITY']=='H'){
										$h20 += 1;
									}else if($v20['ID_COMMODITY']=='MH'){
										$m20 += 1;
									}else if($v20['ID_COMMODITY']=='M'){
										$m20 += 1;
									}else{
										$f20 +=1;
									}
								}
							}
						}
					}

					$r40 = 0;
					$h40 = 0;
					$oog40 = 0;
					$m40 = 0;
					$f40 = 0;
					foreach($_40 as $v40){
						if($v40['VSB_BAY'] != NULL){
							if($v40['CONT_HEIGHT'] == 'OOG'){
								$oog40 += 1;
							}else{
								if($v40['CONT_STATUS']=='MTY'){
									$m40 += 1;
								}else{
									if($v40['ID_COMMODITY']=='R'){
										$r40 += 1;
									}else if($v40['ID_COMMODITY']=='RH'){
										$r40 += 1;
									}else if($v40['ID_COMMODITY']=='H'){
										$h40 += 1;
									}else if($v40['ID_COMMODITY']=='MH'){
										$m40 += 1;
									}else if($v40['ID_COMMODITY']=='M'){
										$m40 += 1;
									}else{
										$f40 +=1;
									}
								}
							}
						}
					}

					//debux($_40);

					$r40hc = 0;
					$h40hc = 0;
					$oog40hc = 0;
					$m40hc = 0;
					$f40hc = 0;
					foreach($_40hc as $v40hc){
						if($v20['VSB_BAY'] != NULL){
							if($v40hc['CONT_HEIGHT'] == 'OOG'){
								$oog40hc += 1;
							}else{
								if($v40hc['CONT_STATUS']=='MTY'){
									$m40hc += 1;
								}else{
									if($v40hc['ID_COMMODITY']=='R'){
										$r40hc += 1;
									}else if($v40hc['ID_COMMODITY']=='RH'){
										$r40 += 1;
									}else if($v40hc['ID_COMMODITY']=='H'){
										$h40hc += 1;
									}else if($v40hc['ID_COMMODITY']=='MH'){
										$m40hc += 1;
									}else if($v40hc['ID_COMMODITY']=='M'){
										$m40hc += 1;
									}else{
										$f40hc +=1;
									}
								}
							}
						}
					}

					$r45 = 0;
					$oog45 = 0;
					$h45 = 0;
					$m45 = 0;
					$f45 = 0;
					foreach($_45 as $v45){
						if($v20['VSB_BAY'] != NULL){
							if($v45['CONT_HEIGHT'] == 'OOG'){
								$oog45 += 1;
							}else{
								if($v45['CONT_STATUS']=='MTY'){
									$m45 += 1;
								}else{
									if($v45['ID_COMMODITY']=='R'){
										$r45 += 1;
									}else if($v45['ID_COMMODITY']=='RH'){
										$r45 += 1;
									}else if($v45['ID_COMMODITY']=='H'){
										$h45 += 1;
									}else if($v45['ID_COMMODITY']=='MH'){
										$m45 += 1;
									}else if($v45['ID_COMMODITY']=='M'){
										$m45 += 1;
									}else{
										$f45 +=1;
									}
								}
							}
						}
					}

					?>
					<tr>
						<th><?php echo $vpod['ID_POD']; ?></th>
						<td><?php echo $h20; ?></td>
						<td><?php echo $oog20; ?></td>
						<td><?php echo $r20; ?></td>
						<td><?php echo $f20; ?></td>
						<td><?php echo $m20; ?></td>
						<td><?php echo $h40; ?></td>
						<td><?php echo $oog40; ?></td>
						<td><?php echo $r40; ?></td>
						<td><?php echo $f40; ?></td>
						<td><?php echo $m40; ?></td>
						<td><?php echo $h40hc; ?></td>
						<td><?php echo $oog40hc; ?></td>
						<td><?php echo $r40hc; ?></td>
						<td><?php echo $f40hc; ?></td>
						<td><?php echo $m40hc; ?></td>
						<td><?php echo $h45; ?></td>
						<td><?php echo $oog45; ?></td>
						<td><?php echo $r45; ?></td>
						<td><?php echo $f45; ?></td>
						<td><?php echo $m45; ?></td>
						<?php
							$t20 = $h20+$oog20+$r20+$f20+$m20;
							$t40 = $h40+$oog40+$r40+$f40+$m40;
							$t40hc = $h40hc+$oog40hc+$r40hc+$f40hc+$m40hc;
							$t45 = $h45+$oog45+$r45+$f45+$m45;
							$t = $t20+$t40+$t40hc+$t45;
						?>
						<td><?php echo $t20; ?></td>
						<td><?php echo $t40; ?></td>
						<td><?php echo $t40hc; ?></td>
						<td><?php echo $t45; ?></td>
						<td><?php echo $t; ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>
	<?php } ?>
</div>