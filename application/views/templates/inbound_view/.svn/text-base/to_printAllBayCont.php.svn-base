
<?PHP 
	$MXWDTHA4=1551;//1034;
	$MXHGHTA4=10000;
	
	$jumbay=count($bay_area);
	$top1=20;
	$konstantabox=48;
	
	$paramHeight1=$MXHGHTA4-100;
	
	$masingbay=($maxRow['MAXROW']+100)*($konstantabox);
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
	ELSE if($ei=='E')
	{
		$eiInfo='LOAD PLAN';
	}
	?>
<style>
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
		$maxwidth=($bay['MAX_ROW']+4)*$konstantabox;
		$hatch=$bay['HATCH_NUMBER'];
		$hatchpx=floor($bay['MAX_ROW']*$konstantabox/$hatch)-5;
		$maxheight=($bay['MAX_TIERUNDER']+$bay['MAX_TIERON']+5)*$konstantabox;
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
				?>
				<div style='font-size:<?=($konstantabox/2)+4;?>pt;font-family: calibri, serif;'><?=$bayname;?></div>
				<br>
				
				<?php
				
				$rowB=1;
				$startleft=$konstantabox/4;
				$starttop=($konstantabox*2.5)+($konstantabox/2);
				$topclAbv=$starttop;
				$leftclAbv=$startleft;
				$firsttier=0;
				$resCellAbv=$this->vessel->get_cellPerBayVesselAbv($ID_VESSEL,$id_ves_voyage,$bay['BAY'],'ABOVE',$ei);
				foreach($resCellAbv as $rcAbv)
				{
					/*remarks row atas*/
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
							left:<?=$leftclAbv;?>px;
							top:<?=$topDr;?>px;
							
						}
						</style>
						<div class="boxConte<?=$paramclass;?>b<?=$bay['BAY'];?>rD<?=$rowB;?>"><? 
							$resRow=$this->vessel->cek_RowStowageStatus($ID_VESSEL,$rcAbv['ID_BAY'],$rcAbv['ROW_']);
							if($resRow['RES']>0)
							{
								echo $rcAbv['ROW_'];
							}
						?></div>
					<?
						
					}
					/*remarks row atas*/
					
					if($rowB>$bay['MAX_ROW'])
					{
						
						$leftclAbv=$startleft;
						$topclAbv=$topclAbv+$konstantabox;
						$rowB=1;
						
					}
					
					if($rcAbv['STATUS_STACK']=='X')
					{
						$color='white';
					}
					else
						$color='gray';
						
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
						position:absolute;
						left:<?=$leftclAbv;?>px;
						top:<?=$topclAbv;?>px;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					.boxContX<?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>
					{
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border:1px solid <?=$color;?>;
						position:absolute;
						left:<?=$leftclAbv;?>px;
						top:<?=$topclAbv;?>px;
						background-color: <?=$colorbull?>;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					
					.boxContXX<?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>
					{
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border:1px solid <?=$color;?>;
						position:absolute;
						left:<?=$leftclAbv;?>px;
						top:<?=$topclAbv;?>px;
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
						
						position:absolute;
						left:<?=$leftclAbv;?>px;
						top:<?=$topclAbv;?>px;
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
					.boxContHq<?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>
					{
						display: table-cell;
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						
						position:absolute;
						left:<?=$leftclAbv;?>px;
						top:<?=$topclAbv;?>px;
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
					.triaCont<?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>
					{ 
						width: <?=$konstantabox;?>px; 
						height: <?=$konstantabox;?>px; 
						border-top:1px solid grey;
						border-left:1px solid grey;
						background-image: linear-gradient(135deg, white 50%, <?=$colorbull?> 51%);  
						position:absolute;
						left:<?=$leftclAbv;?>px;
						top:<?=$topclAbv;?>px;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					</style>
					<? 
						if ($rcAbv['CONT_SIZE']=='40')
						{
							$classy='boxContX';
						}
						else if($rcAbv['CONT_SIZE']=='20'){
							$classy='triaCont';
						}
						else if($rcAbv['FUTURE40']=='40'){
							$classy='boxContXX';
						}
						else
						{
							$classy='boxCont';
						}
						
						
					?>
					<? $nocontainer = 'O'; ?>
					<div 
					class="<?=$classy;?><?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>" style="<? if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>"
					><b><? echo $nocontainer; if(($rcAbv['CONT_TYPE']=='RFR') and ($rcAbv['CONT_STATUS']=='FCL')) { echo 'R';} else if (($rcAbv['CONT_TYPE']=='TNK') and ($rcAbv['CONT_STATUS']=='FCL')){ echo 'T';}else if ($rcAbv['CONT_STATUS']=='MTY'){ echo 'e';}else if ($rcAbv['PLUGGING']=='Y'){ echo '.';};?></b></div>
					<?
					if($rcAbv['HAZARD']=='Y'){
							$classy='boxContRbs';
						
					?>
					<div 
					class="<?=$classy;?><?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>" style="<? if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>"
					><b><? echo $nocontainer; if ($rcAbv['HAZARD']=='Y'){ echo $rcAbv['IMDG']; }?></b></div>
					<?
					}
					else if($rcAbv['CONT_TYPE']=='HQ'){
							$classy='boxContHq';
					?>
					<div 
					class="<?=$classy;?><?=$paramclass;?>b<?=$bay['BAY'];?>rc<?=$rcAbv['CELL_NUMBER'];?>" style="<? if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>"
					></div>
					<?
					}
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
							left:<?=$leftDr;?>px;
							top:<?=$topclAbv;?>px;
							
						}
						</style>
						<div class="boxContR<?=$paramclass;?><?=$bay['BAY'];?><?=$rowB;?>DT<?=$kst;?><?=$rcAbv['TIER_'];?>"><?=$rcAbv['TIER_'];?></div>
					<?	$kst=$kst+1;
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
					top:<?=$topclAbv+($konstantabox+($konstantabox/2));?>px;
					
				}
				</style>
				<div class="hatchst<?=$paramclass;?><?=$bay['BAY'];?><?=$i;?>"> </div>
				
				<?
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
				<div style="left:<?=$stHleft+($konstantabox*1.5);?>;top:<?=$topclAbv+($konstantabox/2);?>;position:absolute;"> <?=$f20sumA['JML'];?> + <?=$f40sumA['JML'];?><br><hr><?=$f20sumB['JML'];?> + <?=$f40sumB['JML'];?></div>
				<?
					/*cell Below*/
				?>
				
				<br>
				
				<?php
				
				$rowB=1;
				$startleft=($konstantabox/4);
				$starttop=$topclAbv+($konstantabox*2);
				$topclAbv=$starttop;
				$leftclAbv=$startleft;
				$firsttier=0;
				$resCellAbv=$this->vessel->get_cellPerBayVesselAbv($ID_VESSEL,$id_ves_voyage,$bay['BAY'],'BELOW',$ei);
				foreach($resCellAbv as $rcAbv)
				{
					
					
					if($rowB>$bay['MAX_ROW'])
					{
						
						$leftclAbv=$startleft;
						$topclAbv=$topclAbv+$konstantabox;
						$rowB=1;
						
					}
					
					if($rcAbv['STATUS_STACK']=='X')
					{
						$color='white';
					}
					else
						$color='gray';
						
						
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
						position:absolute;
						left:<?=$leftclAbv;?>px;
						top:<?=$topclAbv;?>px;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					.boxContX<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{
						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						border:1px solid <?=$color;?>;
						position:absolute;
						left:<?=$leftclAbv;?>px;
						top:<?=$topclAbv;?>px;
						background-color: <?=$colorbull?>;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					.boxContHq<?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>
					{

						width:<?=$konstantabox;?>px;
						height:<?=$konstantabox;?>px;
						position:absolute;
						left:<?=$leftclAbv;?>px;
						top:<?=$topclAbv;?>px;
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
						left:<?=$leftclAbv;?>px;
						top:<?=$topclAbv;?>px;
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
						left:<?=$leftclAbv;?>px;
						top:<?=$topclAbv;?>px;
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
						left:<?=$leftclAbv;?>px;
						top:<?=$topclAbv;?>px;
						font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
					}
					
					</style>
					<? 
						if ($rcAbv['CONT_SIZE']=='40')
						{
							$classy='boxContX';
						}
						else if($rcAbv['CONT_SIZE']=='20'){
							$classy='triaCont';
						}
						else if($rcAbv['FUTURE40']=='40'){
							$classy='boxContXX';
						}
						
						else
						{
							$classy='boxCont';
						}
						

					?>
					<? $nocontainer = 'X';?>
					<div 
					class="<?=$classy;?><?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>" style="<? if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>"
					><b><? echo $nocontainer; if(($rcAbv['CONT_TYPE']=='RFR') and ($rcAbv['CONT_STATUS']=='FCL')) { echo 'R';} else if (($rcAbv['CONT_TYPE']=='TNK') and ($rcAbv['CONT_STATUS']=='FCL')){ echo 'T';}else if ($rcAbv['CONT_STATUS']=='MTY'){ echo 'e';}else if ($rcAbv['PLUGGING']=='Y'){ echo '.';};?></b></div>
					<?
					if($rcAbv['HAZARD']=='Y'){
							$classy='boxContRbs';
						
					?>
					<div 
					class="<?=$classy;?><?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>" style="<? if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>"
					><b><? echo $nocontainer; if ($rcAbv['HAZARD']=='Y'){ echo $rcAbv['IMDG']; }?></b></div>
					<?
					}
					else if($rcAbv['CONT_TYPE']=='HQ'){
							$classy='boxContHq';
					?>
					<div 
					class="<?=$classy;?><?=$paramclass;?><?=$bay['BAY'];?><?=$rcAbv['CELL_NUMBER'];?>" style="<? if($rcAbv['CONT_TYPE']=='HQ'){?>text-align:left<?}?>"
					></div>
					<?
					}
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
							top:<?=$topclAbv;?>px;
							
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
		top:<?=($maxdivheight+($konstantabox*2)+$top1);?>px;
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

	table,tr,td{
		border-spacing: 0;border-collapse: collapse;border:1px solid grey;
		font-size:<?=($konstantabox/2);?>pt;font-family: calibri, serif;
	}
</style>

<!--@todo dihapus sementara -->
<!--@remove
<div class="boxContXSample"></div> <div class="remarksBiasa" style="left:50px;top:<?=$maxdivheight+($konstantabox)+$top1;?>;"><i> : 40' (rear)</i></div>
<div class="boxContXXSample"></div> <div class="remarksBiasa" style="left:200px;top:<?=$maxdivheight+$konstantabox+$top1;?>;"><i> : 40' (front)</i></div>
<div class="triaContSample"></div> <div class="remarksBiasa" style="left:350px;top:<?=$maxdivheight+$konstantabox+$top1;?>;"><i> : 20'</i></div>
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
	<!--
<?
		//$repInfo=$this->vessel->getInfoStowageprint($id_ves_voyage,$ei);
		//foreach ($repInfo as $rowx)
		//{
		?>
		<tr>
		<td ><span style="display:inline-block;"><?=$rowx['ITEMS'];?><? if ($rowx['ITEMS']=='Full'){ $colorbox='red';}else if($rowx['ITEMS']=='Empty'){ $colorbox='orange';}else if($rowx['ITEMS']=='Transhipment (F)'){ $colorbox='blue';}else if($rowx['ITEMS']=='Transhipment (E)'){ $colorbox='blue';}else {$colorbox='white';}?></span><span style="width:<?=($konstantabox/2)?>px;height:<?=($konstantabox/2)?>px;background-color:<?=$colorbox;?>;display:inline-block;"></span> </td>
		<td align="right"><?=$rowx['Q20'];?></td>
		<td align="right"><?=$rowx['Q40'];?></td>
		<td align="right"><?=$rowx['Q40HC'];?></td>
		<td align="right"><?=$rowx['Q45'];?></td>
		<td align="right"><?=$rowx['QTOTAL'];?></td>
		</tr>
		<?
		//}
	?>-->
	<!--@remove</table>
</div>@remove-->
</div>
<?php ?>