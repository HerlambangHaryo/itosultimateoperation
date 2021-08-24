<?php
	require_once('tcpdf/config/lang/eng.php');
	require_once('tcpdf/tcpdf.php');

	// create new PDF document
	$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('IPC');
	$pdf->SetTitle('Loading List');
	$pdf->SetSubject('Loading List');
	$pdf->SetKeywords('Loading List, IPC, !TOS');

	// set default header data
	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);

	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	// set header and footer fonts
	//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

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

	$pdf->SetFont('courier', '', 8);
	$pdf->setPageOrientation('l');
	
	$html='<style>
			.basic  {
				font-size: small;
			}
			</style>
			
			<table border="0">
				<tr><td colspan="2">'.$corporate_name.'</td><td></td><td>Form No.: FM.02/03/01/24</td></tr>
				<tr><td></td><td></td><td></td><td>Revision : 01</td></tr>
				<tr><td></td><td></td><td></td><td>Date : '.$date.'</td></tr>
				<tr><td>Printed by : '.$username.'</td><td></td><td></td><td>Page : 1/1</td></tr>
				<tr><td colspan="4"></td></tr>
				<tr><td colspan="4" align="center"><font size="12pt"><b><i>LOADING LIST '.$vsname.'</i></b></font></td></tr>
				<tr><td colspan="4"><hr></td></tr>
				<tr>
					<td colspan="4">
						<table border="0">
							<tr>
								<td width="80">VESSEL ID</td>
								<td width="10">:</td>
								<td colspan="10">'.$ves_id.'</td>
							</tr>
							<tr>
								<td>VOYAGE</td>
								<td width="10">:</td>
								<td colspan="10">'.$voyg.'</td>
							</tr>
							<tr>
								<td width="80">ARRIVAL</td>
								<td width="10">:</td>
								<td width="100">'.$rta.'</td>
								<td width="30"></td>
								<td width="80">BERTHING</td>
								<td width="10">:</td>
								<td width="100">'.$berth.'</td>
								<td width="30"></td>
								<td width="80">DEPARTURE</td>
								<td width="10">:</td>
								<td width="100">'.$rtd.'</td>
							</tr>
							<tr>
								<td width="80">START WORK</td>
								<td width="10">:</td>
								<td width="100">'.$str.'</td>
								<td width="30"></td>
								<td width="80">END WORK</td>
								<td width="10">:</td>
								<td width="100">'.$end.'</td>
								<td width="30"></td> 
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<TR>
					<td colspan="4">
					<table border="1">
						<tr align="center" class="basic">
							<td width="70" >No. Container</td>
							<td width="40">Stowage Plan</td>
							<td width="40">Stowage Real</td>							
							<td width="30">ISO</td>
							<td width="30">Class</td>
							<td width="40">OPR</td>
							<td width="30">F/M</td>
							<td width="40">POL</td>
							<td width="40">POD</td>
							<td width="40">POR</td>
							<td width="60">Yard</td>
							<td width="40">WGT(Ton)</td>
							<td width="40">Temp.(C)</td>
							<td width="30">UNNO</td>
							<td width="30">IMDG</td>
							<td width="40">Comm.</td>
							<td width="30">Size</td>
							<td width="20">Type</td>
							<td width="34">Height</td>
							<td width="20">TL</td>
							<td width="40">QC Plan</td>
							<td width="40">QC Real</td>
							<td width="40">YC PLan</td>
							<td width="40">YC Real</td>
							<td width="20">OH</td>
							<td width="20">OW-R</td>
							<td width="20">OW-L</td>
							<td width="20">OL-F</td>
							<td width="20">OL-B</td>
							<td width="20">OW</td>
							<td width="40">Handling</td>
						</tr>';
		foreach ($datadetail['data'] as $rowd){
			$html.='
						<TR align="center">
							<td class="basic">'.$rowd['NO_CONTAINER'].'</td>
							<td>'.$rowd['STOWAGE_PLAN'].'</td>
							<td>'.$rowd['STOWAGE'].'</td>
							<td>'.$rowd['ID_ISO_CODE'].'</td>
							<td>'.$rowd['ID_CLASS_CODE'].'</td>
							<td>'.$rowd['ID_OPERATOR'].'</td>
							<td>'.$rowd['CONT_STATUS'].'</td>
							<td>'.$rowd['ID_POL'].'</td>
							<td>'.$rowd['ID_POD'].'</td>
							<td>'.$rowd['ID_POR'].'</td>
							<td>'.$rowd['YARD_POS'].'</td>
							<td>'.$rowd['WEIGHT'].'</td>
							<td>'.$rowd['TEMP'].'</td>
							<td>'.$rowd['UNNO'].'</td>
							<td>'.$rowd['IMDG'].'</td>
							<td>'.$rowd['ID_COMMODITY'].'</td>
							<td>'.$rowd['CONT_SIZE'].'</td>
							<td>'.$rowd['CONT_TYPE'].'</td>
							<td>'.$rowd['CONT_HEIGHT'].'</td>
							<td>'.$rowd['TL_FLAG'].'</td>
							<td>'.$rowd['QC_PLAN'].'</td>
							<td>'.$rowd['QC_REAL'].'</td>
							<td>'.$rowd['YC_PLAN'].'</td>
							<td>'.$rowd['YC_REAL'].'</td>
							<td>'.$rowd['OVER_HEIGHT'].'</td>
							<td>'.$rowd['OVER_RIGHT'].'</td>
							<td>'.$rowd['OVER_LEFT'].'</td>
							<td>'.$rowd['OVER_FRONT'].'</td>
							<td>'.$rowd['OVER_REAR'].'</td>
							<td>'.$rowd['OVER_WIDTH'].'</td>
							<td>'.$rowd['ID_SPEC_HAND'].'</td>
						</TR>
					';
		}
	$html.='
						<TR>
						</TR>
					</table>
					</TD>
				</TR>
			</table>';
	// Print text using writeHTMLCell()
	$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='10', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

	// ---------------------------------------------------------

	//Close and output PDF document
	$pdf->Output('LoadingList_'.$id_ves_voyage.'.pdf', 'I');

	//============================================================+
	// END OF FILE												
	//============================================================+
?>