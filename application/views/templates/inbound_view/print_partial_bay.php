<?php
		require_once('tcpdf/config/lang/eng.php');
		require_once('tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('IPC');
		$pdf->SetTitle('Stowage');
		$pdf->SetSubject('Stowage');
		$pdf->SetKeywords('Stowage, IPC, !TOS');


		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins($left = 4,
						 $top,
						 $right = 4,
						 $keepmargins = false );
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);

		// ---------------------------------------------------------

		// set font
		//$pdf->SetFont('courier', 'B', 20);

		// add a page
		$pdf->AddPage();

		//$pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);

		$pdf->SetFont('helvetica', '', 8);
		$pdf->setPageOrientation('p');

		$id_vs = $id_ves_voyage;
		$id_bay = $idbay;
		$no_bay = $nobay;
		$pss_bay = trim($deck_hatch);
		$id_user = $this->session->userdata('id_user');	

		//======================= Header ===========================//

		$header = $this->vessel->stowage_header_print($vescode,$id_ves_voyage);
		$plan_mch = $this->vessel->get_machine_plan($vescode, $id_ves_voyage, 'I', $idbay);
		$vesselLD = $this->vessel->get_vessel_profile_info($id_ves_voyage);
		// var_dump($plan_mch); die;
		foreach ($header as $row_header)
		{
			$vessel = $row_header['VESSEL_NAME'];
			$voyage = $row_header['VOYAGE'];
		}
		$putih=0;
		$palka=0;

		$sideInfoL = '';
		$sideInfoR = '';
		if($vesselLD['ALONG_SIDE'] == 'P'){ 
		    $sideInfoL = '<td style="width: 15px;">L</td>';
		    $sideInfoR = '<td style="width: 15px;">W</td>';
		}else{
		    $sideInfoL = '<td style="width: 15px;">W</td>';
		    $sideInfoR = '<td style="width: 15px;">L</td>';
		}
		$html = '<b>BAYPLAN INBOUND</b> ['.date('Y-m-d H:i:s').']
				<hr/>
				<br/>
				<div align="right">'.$vessel.' ['.$voyage.']</div>
				<br/>
				<div align="center" style="width:100%">
				<table align="center" >
				<tr>
				'.$sideInfoL.'
				<td style="width:680px;">
				<b>BAY '.$no_bay.' '.$posisibay.'</b>
				</td>
				'.$sideInfoR.'
				</tr>
				</table>
				</div>';
				$vesinfo = $this->vessel->stowage_print_vesinfo($vescode,$id_ves_voyage,$id_bay);
				foreach ($vesinfo as $row_vesinfo)
				{
					$jumlah_row = $row_vesinfo['JML_ROW'];
					$jml_tier_under = $row_vesinfo['JML_TIER_UNDER'];
					$jml_tier_on = $row_vesinfo['JML_TIER_ON'];
					$bay_occ = $row_vesinfo['OCCUPY'];
					$width = $jumlah_row+1;
				}
					
					if($pss_bay=='D')
					{
						$height = $jml_tier_on+1;
					}
					else
					{
						$height = $jml_tier_under+1;
					}					

		$szbox=54;
		$ftsz=5.5;
		$html .= '<br/>
				  <center>
				  <table align="center">
				  <tbody>
					<tr align="center">';
					
					$n='';
					$br='';
					$tr='';
										
					$html .= '<td align="center">
							  <table align="center">
								<tbody>
								<tr>
									<td colspan="'.($jumlah_row+1).'" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								</tr>
								<tr>';
							for($r=1;$r<=($jumlah_row+1);$r++){
								if($pss_bay=='D'){
									$index_cell = (0*($jumlah_row+1))+$r;
								}
								else{
									$index_cell = (0*($jumlah_row+1))+$r+($jml_tier_on*$width);
								}

								$cell_number = $index_cell-1;

								$vesbay_cell = $this->vessel->stowage_print_vescell($id_bay,$cell_number,$pss_bay);
								foreach ($vesbay_cell as $row_vescell)
								{
									$id_cellx = $row_vescell['ID'];
									$br = $n;
									$tr = $row_vescell['TIER_'];
									$n = $tr;
									$pss_stack = $row_vescell['POSISI_STACK'];
									$stat_stack = $row_vescell['STATUS_STACK'];
									$row_bay = $row_vescell['ROW_'];
								}

								if($cell_number<($jumlah_row+1))
								{
									if($cell_number==$jumlah_row)
									{
										$html .= '<td class="label"></td>';
									}
									else
									{	
										$summary_weight = $this->vessel->summary_weight($id_ves_voyage,$no_bay,$row_bay,$pss_bay)->TOTAL;
										$row = str_pad($row_bay,2,'0',STR_PAD_LEFT);
										$count_row = $this->vessel->get_count_row($vescode, $id_bay,$row,$posisibay);
										if($count_row->JML < 1){
											$status_row_hid = 'display-none';
										} else {
											$status_row_hid = '';
											$palka++;
										}
										$html .= '<td class="label '.$status_row_hid.'">'.$row.'<br />'.$summary_weight.'</td>';
									}
								}
							}	
					$html .= ' </tr>';
					
							for($t=1;$t<=$height;$t++)
							{
								$html .= '<tr>';
								for($r=1;$r<=($jumlah_row+1);$r++)
								{
									if($pss_bay=='D')
									{
										$index_cell = (($t-1)*($jumlah_row+1))+$r;
									}
									else
									{
										$index_cell = (($t-1)*($jumlah_row+1))+$r+($jml_tier_on*$width);
									}
									$cell_number = $index_cell-1;

									$vesbay_cell = $this->vessel->stowage_print_vescell($id_bay,$cell_number,$pss_bay);
									foreach ($vesbay_cell as $row_vescell)
									{
										$id_cellx = $row_vescell['ID'];
										$br = $n;
										$tr = $row_vescell['TIER_'];
										$n = $tr;
										$pss_stack = $row_vescell['POSISI_STACK'];
										$stat_stack = $row_vescell['STATUS_STACK'];
										$row_bay = $row_vescell['ROW_'];
									}

									if($pss_stack=='HATCH')
									{
										if($index_cell%($jumlah_row+1)==0)
										{
											$html .= '<td class="label"> '.$pss_stack.'</td>';
										}
										else
										{
											$status_palka_hid = ($palka<$r) ? 'display-none' : '';
											$html .= '<td class="palka '.$status_palka_hid.'"></td>';
										}
									}
									else if($cell_number>(($jumlah_row+1)*($jml_tier_on+$jml_tier_under+2)))
									{
										if($cell_number==($jml_tier_on+$jml_tier_under+3))
										{
											$html .= '<td class="label"></td>';
										}
										else
										{
											$html .= '<td class="label"> '.$row_bay.'</td>';
										}
									}
									else if($index_cell%($jumlah_row+1)==0)
									{
										$status_tier_hid = ($putih==$jumlah_row) ? 'display-none' : '';
										$html .= '<td class="label '.$status_tier_hid.'">  &nbsp;<br/><br/>'.str_pad($br,2,'0',STR_PAD_LEFT).'</td>';
										$putih=0;
									}
									else
									{
										if(($stat_stack=='A')||($stat_stack=='P')||($stat_stack=='R'))
										{
											$vescont = $this->vessel->stowage_print_vescont_imp($id_vs,$id_cellx);
											$data_cont = explode("^",$vescont);
											$by = $data_cont[0];
											$nocont = $data_cont[1];
											$sz = $data_cont[2];
											$ty = $data_cont[3];
											$pod = $data_cont[4];
											$pol = $data_cont[5];
											$carrier = $data_cont[6];
											$gross = $data_cont[7];
											$ht = $data_cont[8];
											$isocode = $data_cont[9];
											$st = $data_cont[10];
											$vsbay = $data_cont[11];
											$ydloc = $data_cont[12];
											$vstatusload = $data_cont[13];
											$hq_flag = ($ty=='HQ') ? 'HQ' : '';
											$tl_flag = ($data_cont[14]=='Y') ? 'TL' : '';
											$commodity = $data_cont[15];
											$id_class_code = $data_cont[16];
											$esy = ($data_cont[17] == 'Y') ? 'ESY' : '';
											$pod_color = $id_class_code == 'TC' ? 'CCCCCC' : get_pod_color($pod);
											$pod_fontcolor = $id_class_code == 'TC' ? 'CCCCCC' : get_pod_color($pod,'FOREGROUND_COLOR');
											$ydloc = ($data_cont[17] == 'Y') ? '--' : $ydloc;
											$sequence = $data_cont[19]=='P' ?  $data_cont[18]: 'C';

											//debux($data_cont);
											
											if(($sz=='40')&&($bay_occ=='T'))
											{
												$html .= '<td class="allocation">&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>+++</td>';
											}
											else
											{
												if (($sz=='40')&&($no_bay<>$vsbay))
												{
													$classor='allocationX';
													$isi='<img src="images/cross.jpg" />';
												}
												else if(($nocont<>'')&&($no_bay==$vsbay)){

													$line1 = ($id_class_code=='I') ? '' : $id_class_code;
													//if($tl_flag == 'Y'){
														$line1 .= '<span style="font-weight:bold">'.$sequence.'</span> ';
														$line1 .= $hq_flag.' '.$tl_flag.''.$esy;
													//}

													
													if($vstatusload=='C')
													{
														$classor='allocation2';
														$isi='<br/>'.$line1.'<br/>'.$pol.'&nbsp;&nbsp;&nbsp;'.$pod.'<br/><b>'.$nocont.'</b><br/>'.$carrier.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.ROUND(($gross/1000),2).'<br/>'.$sz.$ty.'&nbsp;&nbsp;'.$commodity.'&nbsp;&nbsp;&nbsp;'.$ht.
														'<br/>'.$ydloc;	
													}
													else
													{
														$classor='allocationplan';
														$isi='<br/>'.$line1.'<br/>'.$pol.'&nbsp;&nbsp;&nbsp;'.$pod.'<br/><b>'.$nocont.'</b><br/>'.$carrier.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.ROUND(($gross/1000),2).'<br/>'.$sz.$ty.'&nbsp;&nbsp;'.$commodity.'&nbsp;&nbsp;&nbsp;'.$ht.
														'<br/>'.$ydloc;
													}
													
														
												}
												else
												{
													$classor='allocation';
													$isi='';//$id_cellx.' '.$id_vs;
												}

												if(empty($pod_color)){
													$html .= '<td class="'.$classor.'" valign="center">'.$isi.' </td>';
												}else{
													$html .= '<td bgcolor="#'.$pod_color.'" class="'.$classor.'" valign="center" style="color:#'.$pod_fontcolor.';">'.$isi.' </td>';
												}
												
											}
										}
										else
										{
											$html .= '<td class="general">&nbsp;  </td>';
											$putih++;
										}
									}
								}
								$html .= '</tr>';
								
							}

								$html .= '<tr>';

							for($r=1;$r<=($jumlah_row+1);$r++){
								if($pss_bay=='D'){
									$index_cell = $r;
								}
								else{
									$index_cell = $r+($jml_tier_on*$width)+$jumlah_row;
								}

								$cell_number = $index_cell;

								$vesbay_cell = $this->vessel->stowage_print_vescell($id_bay,$cell_number,$pss_bay);
								//debux($vesbay_cell);die;
								foreach ($vesbay_cell as $row_vescell)
								{
									$id_cellx = $row_vescell['ID'];
									$br = $n;
									$tr = $row_vescell['TIER_'];
									$n = $tr;
									$pss_stack = $row_vescell['POSISI_STACK'];
									$stat_stack = $row_vescell['STATUS_STACK'];
									$row_bay = $row_vescell['ROW_'];
								} 

								if($cell_number>($jumlah_row+1))
								{	

									if($r==($jumlah_row+1))
									{
										$html .= '<td class="label"></td>';
									}
									else
									{	
										$summary_weight = $this->vessel->summary_weight($id_ves_voyage,$no_bay,$row_bay,$pss_bay)->TOTAL;
										$row = str_pad($row_bay,2,'0',STR_PAD_LEFT);
										$count_row = $this->vessel->get_count_row($vescode, $id_bay,$row,$posisibay);
										if($count_row->JML < 1){
											$status_row_hid = 'display-none';
										} else {
											$status_row_hid = '';
											$palka++;
										}
										$html .= '<td class="label '.$status_row_hid.'"> '.$row.'<br />'.$summary_weight.'</td>';
									}
								}
							}

							$html .= '</tr></tbody>
									  </table>
									  </td>';
						   
						    // alokasi alat
							$data_html = '';
							foreach($plan_mch as $data_row){
								$data_html = $data_html . '<tr><td class="alat" valign="center" align="center"> '
									. $data_row['MCH_NAME'] .'<br /><span class="big_font">abc '. $data_row['SEQUENCE'] . '</span> </td></tr>';
							}
							$html .= '<td align="center">
								<table align="center">
									<tbody>
									</tbody>
								</table>	
							</td></tr>';
							
				 $html .= '
						  </tbody>
						  </table>
						  </center>
						  <style>
						.display-none{
							display:none;
						}
						.allocation  {
							width : '.$szbox.'px;
							height : '.$szbox.'px;
							font-size : '.$ftsz.'pt; 
							font-family : verdana; 
							border : 1px solid #FFFFFF;
							background-color:#D6D6C2;
						}
						.allocation2  {
							width : '.$szbox.'px;
							height : '.$szbox.'px;
							font-size : '.$ftsz.'pt; 
							font-family : verdana; 
							border : 1px solid #FFFFFF;
							background-color:#f0d048;
						}
						.allocationplan  {
							width : '.$szbox.'px;
							height : '.$szbox.'px;
							font-size : '.$ftsz.'pt; 
							font-family : verdana; 
							border : 1px solid #FFFFFF;
							background-color:#819ede;
						}
						.alat  {
							padding: 15px;
							width : '.$szbox.'px;
							height : '.$szbox.'px;
							font-size : 8pt; 
							font-family : verdana; 
							border : 1px solid #FFFFFF;
							background-color:#4679BD;
						}
						.big_font {
							font-size: 12pt;
						}
						.allocationX{
							width : '.$szbox.'px;
							height : '.$szbox.'px;
							font-size : '.$ftsz.'pt; 
							border : 1px solid #000000;
							background-color:#f0d048;
						}
						.label  {
							width : '.$szbox.'px;
							height : '.($szbox/2).'px;
							font-size : 8pt; 
							font-family : verdana;
						}
						.general  {
							display:none;
							width : '.$szbox.'px;
							height : '.$szbox.'px;
							font-size : '.$ftsz.'pt; 
							font-family : verdana; 
							border : 1px solid #FFFFFF;
							background-color:#FFFFFF;
						}
						.palka  {
							width : '.$szbox.'px;
							height :'.($szbox/2).'px;
							font-size : '.$ftsz.'pt; 
							font-family : verdana; 
							border : 1px solid #0079a3;
							background-color:#0B3861;
						}
				</style>';
				
		// echo $html; die;
		
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
//		echo $html;
		// ---------------------------------------------------------

		//Close and output PDF document
		$pdf->Output('print_bayplan.pdf', 'I');

		//============================================================+
		// END OF FILE												
		//============================================================+
