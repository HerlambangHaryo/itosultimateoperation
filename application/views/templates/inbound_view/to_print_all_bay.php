<center>
<h3><? echo $vessel." / ".$voyage; ?></h3>
<br/>
<div align="center">
<table width="100%" cellspacing="3" border="0">
<tbody>
<tr>
<?
			foreach ($blok8 as $row18)
			{
				$id_area = $row18['ID'];
				$bay_name = $row18['BAY'];
				$occ2 = $row18['OCCUPY'];
			
			if((($bay_name+1)%4!=0)&&($bay_name<24))
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ2=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ2=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	   </tr>
	   <tr>   
		 <?php
			$blok2 = $this->vessel->stowage_print_allbay_blok2($id_area);

			$n='';
			$br='';
			$tr='';
			// print_r($blok2);die;
			
			foreach ($blok2 as $row8)
			{				
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				$idx_cell = $row8['ID'];
				
				//echo $index."_".$row8['STATUS_STACK']."<br/>";

				$pol2 = $this->vessel->stowage_print_vescont_imp($id_ves_voyage,$idx_cell);
				$data_cont = explode("^",$pol2);
					$by = $data_cont[0];
					$nocont = trim($data_cont[1]);
					$sz = $data_cont[2];
					$ty = $data_cont[3];
					$pod = $data_cont[4];
					$pol = $data_cont[5];
					$carrier = $data_cont[6];
					$gross = $data_cont[7];
					$ht = $data_cont[8];
					$isocode = $data_cont[9];
					$st = $data_cont[10];

				$pol_bay = $pol;
				$type_cont = $ty;
				$st_cont = $st;
				
				if(($type_cont=='HQ')&&($st_cont=='FCL'))
				{
					$pic = 'HC';
				}
				else if(($type_cont=='HQ')&&($st_cont=='MTY'))
				{
					$pic = 'HCMTY';
				}
				else if(($type_cont=='RFR')&&($st_cont=='FCL'))
				{
					$pic = 'REEFER';
				}
				else if(($type_cont=='RFR')&&($st_cont=='MTY'))
				{
					$pic = 'REEFERMTY';
				}				
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
					// print_r("AY ");
				?>
					<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
				<?
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   		// print_r("index,width ");
			   ?>
					<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'A')
			   {			
					if(($index>=1)&&($index<$width)) {
						// print_r("Awidth1 ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
					else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
						// print_r("Awidth2 ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
					else if($row8['PLUGGING'] == 'N')
					{
						// print_r("AN ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
					<?	
					}	
					else
					   {
					   	// print_r("Aelse ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
						&nbsp;
						</td>
					<? } 
				} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>	
					<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
					</td>
					</tr>
			  <? }
			else if ($index%$width == 0)
			{ ?>			   
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						  else {
						 ?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
							else { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? }
						} ?>
					</tr>				
			<?
				}
			}
			?>
		
</tbody>
</table>
</td>
<? } } ?>
</tr>
<!-- =========================================== LINE =========================================== -->
<tr>
<?
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			$occ3 = $row18['OCCUPY'];
			
			if((($bay_name+1)%4==0)&&($bay_name<24))
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ3=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ3=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	   </tr>
	   <tr>   
		 <?php
			$blok2 = $this->vessel->stowage_print_allbay_blok2($id_area);

			$n='';
			$br='';
			$tr='';
			// debug($blok2);die;

			foreach ($blok2 as $row8){
				//echo $row['INDEX_CELL'];
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				$id_cell = $row8['ID'];
				//echo $tr;
				
				$idx_cell = $row8['ID'];
				
				$pol2 = $this->vessel->stowage_print_vescont_imp($id_ves_voyage,$idx_cell);
				$data_cont = explode("^",$pol2);
					$by = $data_cont[0];
					$nocont = trim($data_cont[1]);
					$sz = $data_cont[2];
					$ty = $data_cont[3];
					$pod = $data_cont[4];
					$pol = $data_cont[5];
					$carrier = $data_cont[6];
					$gross = $data_cont[7];
					$ht = $data_cont[8];
					$isocode = $data_cont[9];
					$st = $data_cont[10];

				$pol_bay = $pol;
				$type_cont = $ty;
				$st_cont = $st;
				
				if(($type_cont=='HQ')&&($st_cont=='FCL'))
				{
					$pic = 'HC';
				}
				else if(($type_cont=='HQ')&&($st_cont=='MTY'))
				{
					$pic = 'HCMTY';
				}
				else if(($type_cont=='RFR')&&($st_cont=='FCL'))
				{
					$pic = 'REEFER';
				}
				else if(($type_cont=='RFR')&&($st_cont=='MTY'))
				{
					$pic = 'REEFERMTY';
				}
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
				?>
					<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
				<?
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'A')
			   {			
					if(($index>=1)&&($index<$width)) {
						// print_r("Awidth1 ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
					else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
						// print_r("Awidth2 ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
					else if($row8['PLUGGING'] == 'N')
					{
						// print_r("AN ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
					<?	
					}	
					else
					   {
					   	// print_r("Aelse ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
						&nbsp;
						</td>
					<? } 
				} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>	
					<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
					</td>
					</tr>
			  <? }
			else if ($index%$width == 0)
			{ ?>				  
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						  else {
						 ?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
							else { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? }
						} ?>
					</tr>				
			<?
				}
			}
			?>
		
</tbody>
</table>
</td>
<? } } ?>
</tr>
<!-- =========================================== LINE =========================================== -->
<tr>
<?
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			$occ2 = $row18['OCCUPY'];
			
			if((($bay_name+1)%4!=0)&&($bay_name>24))
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ2=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ2=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	   </tr>
	   <tr>   
		 <?php
			$blok2 = $this->vessel->stowage_print_allbay_blok2($id_area);

			$n='';
			$br='';
			$tr='';
			//debug($blok2);die;
			
			foreach ($blok2 as $row8){
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				$idx_cell = $row8['ID'];
				
				//echo $index."_".$row8['STATUS_STACK']."<br/>";

				$pol2 = $this->vessel->stowage_print_vescont_imp($id_ves_voyage,$idx_cell);
				$data_cont = explode("^",$pol2);											
					$by = $data_cont[0];
					$nocont = trim($data_cont[1]);
					$sz = $data_cont[2];
					$ty = $data_cont[3];
					$pod = $data_cont[4];
					$pol = $data_cont[5];
					$carrier = $data_cont[6];
					$gross = $data_cont[7];
					$ht = $data_cont[8];
					$isocode = $data_cont[9];
					$st = $data_cont[10];

				$pol_bay = $pol;
				$type_cont = $ty;
				$st_cont = $st;
				
				if(($type_cont=='HQ')&&($st_cont=='FCL'))
				{
					$pic = 'HC';
				}
				else if(($type_cont=='HQ')&&($st_cont=='MTY'))
				{
					$pic = 'HCMTY';
				}
				else if(($type_cont=='RFR')&&($st_cont=='FCL'))
				{
					$pic = 'REEFER';
				}
				else if(($type_cont=='RFR')&&($st_cont=='MTY'))
				{
					$pic = 'REEFERMTY';
				}
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
				?>
					<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
				<?
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'A')
			   {			
					if(($index>=1)&&($index<$width)) {
						// print_r("Awidth1 ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
					else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
						// print_r("Awidth2 ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
					else if($row8['PLUGGING'] == 'N')
					{
						// print_r("AN ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
					<?	
					}	
					else
					   {
					   	// print_r("Aelse ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
						&nbsp;
						</td>
					<? } 
				} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>	
					<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
					</td>
					</tr>
			  <? }
			else if ($index%$width == 0)
			{ ?>				  
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						  else {
						 ?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
							else { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? }
						} ?>
					</tr>
			<?
				}
			}
			?>
		
</tbody>
</table>
</td>
<? } } ?>
</tr>
<!-- =========================================== LINE =========================================== -->
<tr>
<?
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			$occ3 = $row18['OCCUPY'];
			
			if((($bay_name+1)%4==0)&&($bay_name>24))
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ3=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ3=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
		</tr>
		<tr>   
		 <?php
			$blok2 = $this->vessel->stowage_print_allbay_blok2($id_area);

			$n='';
			$br='';
			$tr='';
			// debug($blok2);die;
			foreach ($blok2 as $row8){
				//echo $row['INDEX_CELL'];
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				$id_cell = $row8['ID'];
				//echo $tr;	
			
				$idx_cell = $row8['ID'];

				$pol2 = $this->vessel->stowage_print_vescont_imp($id_ves_voyage,$idx_cell);
				$data_cont = explode("^",$pol2);
					$by = $data_cont[0];
					$nocont = trim($data_cont[1]);
					$sz = $data_cont[2];
					$ty = $data_cont[3];
					$pod = $data_cont[4];
					$pol = $data_cont[5];
					$carrier = $data_cont[6];
					$gross = $data_cont[7];
					$ht = $data_cont[8];
					$isocode = $data_cont[9];
					$st = $data_cont[10];

				$pol_bay = $pol;
				$type_cont = $ty;
				$st_cont = $st;
				
				if(($type_cont=='HQ')&&($st_cont=='FCL'))
				{
					$pic = 'HC';
				}
				else if(($type_cont=='HQ')&&($st_cont=='MTY'))
				{
					$pic = 'HCMTY';
				}
				else if(($type_cont=='RFR')&&($st_cont=='FCL'))
				{
					$pic = 'REEFER';
				}
				else if(($type_cont=='RFR')&&($st_cont=='MTY'))
				{
					$pic = 'REEFERMTY';
				}
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
				?>
					<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
				<?
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'A')
			   {			
					if(($index>=1)&&($index<$width)) {
						// print_r("Awidth1 ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
					else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
						// print_r("Awidth2 ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
					else if($row8['PLUGGING'] == 'N')
					{
						// print_r("AN ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; ">&nbsp;</td>
					<?	
					}	
					else
					   {
					   	// print_r("Aelse ");
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
						&nbsp;
						</td>
					<? } 
				} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>
					<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
					</td>
					</tr>
			  <? }
			else if ($index%$width == 0)
			{ ?>
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						  else {
						 ?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
							else { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? }
						} ?>
					</tr>
			<?
				}
			}
			?>
		
</tbody>
</table>
</td>
<? } } ?>
</tr>
<!-- =========================================== LINE =========================================== -->
</tbody>
</table>
</div>
</center>
<br/>
<br/>
<div align="right"><i>Generated by !TOS</i><br/>&copy; Biro Sistem Informasi 2014</div>