<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Gate_operation extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		//$this->load->library('form_validation');
		$this->load->model(array('container','gtools'));
		$this->load->library('session');
		$this->load->model('gtools');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		$this->load->view('templates/gate_operation/abvPanel', $data);
	}
	
	public function data_dmg()
	{
		$data	= $this->container->getDamageCode();
		echo json_encode($data);
	}
	
	public function data_axle()
	{
		$data	= $this->container->getAxleCont();
		echo json_encode($data);
	}
	
	public function data_dmgLoc()
	{
		$data	= $this->container->getDamageLocation();
		echo json_encode($data);
	}
	
	public function data_containernya(){
		$filter = $_GET['query'];
		$ei = $_GET['ei'];
		$data	= $this->container->getContainerListAvb($filter, $ei);
		echo json_encode($data);
	}
	
	public function call_bat(){
		$portcom=$_POST['portcom'];
		exec('"../timbangan/timbanganbaru.bat" '.$portcom.' 2>&1', $output, $returnCode);
		$output = implode(PHP_EOL, $output);
		echo $output . ' ' . $returnCode;
	}
	
	public function call_faye(){
		$portcom=$_POST['portcom'];
		exec('"../timbangan/runlistenfuye.bat" '.$portcom.' 2>&1', $output, $returnCode);
		$output = implode(PHP_EOL, $output);
		echo $output . ' ' . $returnCode;
	}
	
	public function data_container_inquiry(){
		$data = array(
			'success'=>false,
			'errors'=>'Container not found'
		);

		$typeGate=$_POST['typeInOut'];
		$recDelGate=$_POST['typeRecDel'];
		$cont_info=explode('-',$_POST['cont_inquiry']);

		//debux($cont_info);die;

		$nocontainer = $cont_info[0];
		$point = $cont_info[1];
		$val_bmd = "";
		
		//debux($cont_info);die;
		
		$retval = $this->container->get_data_container_inquiryGate($nocontainer, $point, $typeGate, $recDelGate);
		//debux($this->db->last_query());
		// $data['errors'] = json_encode($retval);
		// $data['errors'] = 'gate : '.$typeGate.' ei : '.$recDelGate.' container : '.$nocontainer.' point : '.$point;
		// echo json_encode($data); die;
		
		if ($retval){
			if (!isset($retval['FL_TONGKANG'])) {
			    if($retval['ITT_FLAG'] != 'Y'){
				if ($retval['STATUS_FLAG']=='P' && $typeGate == 'OUT'){
	                $err='Container not yet Gate In';
					$data['success']=false;
	            // } else if ($retval['STATUS_FLAG']=='G' && $typeGate == 'OUT' && $recDelGate == 'REC'){
            	} /*else if ($typeGate == 'OUT' && $recDelGate == 'REC'){
	                $err='Container not yet Stacking';
					$data['success']=false;
	            }*/ else if (
	                (
	                    $retval['STATUS_FLAG']=='G' || 
	                    $retval['STATUS_FLAG']=='S' ||
	                    $retval['STATUS_FLAG']=='C'
	                ) && $typeGate == 'IN'){
														
						if($retval['TL_FLAG']=='Y'){
							$err='Container found';
							$data['success']=true;
							$data['data']=json_encode($retval);
						}else{
							/*validasi jika batalmuat delivery*/
							$val_bmd = $this->container->valid_bmd($nocontainer,$point,$typeGate,$recDelGate);
							if($val_bmd){
								$err='Container found';
								$data['success']=true;
								$data['data']=json_encode($retval);
							}else{
								$err='Container already Gate In';
								$data['success']=false;	
							}
						}
	            	
	            } else if($retval['STATUS_FLAG']=='C'){
					$data['success']=false;
					$err='container already Gate Out';
				} else if ($retval['PAYMENT_STATUS']!='1'){
	                $err='Container not yet Payment';
					$data['success']=false;
	            } 
				else {
					$err='Container found';
					$data['success']=true;
					$data['data']=json_encode($retval);
				}
				$data['errors']=$err;
			    }else{
				$err='Container found';
				$data['success']=true;
				$data['data']=json_encode($retval);
			    }
			}
			else if (isset($retval['FL_TONGKANG'])) {
				if ($retval['WEIGHT'] == 0) {
					$err='Container found';
					$data['success']=true;
					$data['data']=json_encode($retval);
				}
				else{
					$err='Container found! the container has been weighed';
					$data['success']=false;
					// $data['data']=json_encode($retval);	
				}
			}

		}
		// print_r($retval);
		echo json_encode($data);
	}
	
	public function saveGate(){
		$truckJob=$_POST['TR_JOB'];
		$nocontainer=$_POST['NO_CONTAINER'];
		$pointcontainer=$_POST['POINT'];
		$idvesvoyage=$_POST['ID_VES_VOYAGE'];
		$trucknumber=$_POST['TRUCK_NUMBER'];
		$sealid=$_POST['SEAL_ID'];
		$weight=$_POST['NETTO'];
		$EI=$_POST['EI'];
		$tl=$_POST['TL_FLAG'];
		$esy=$_POST['ITT_FLAG'];
//		$axle=$_POST['ID_AXLE'];
//		$axleSize=$_POST['AXLE_SIZE'];
		$dmg=$_POST['ID_DAMAGE'];
//		$dmgVal=$_POST['damageCont'];
		$dmgLoc=$_POST['ID_DAMAGE_LOCATION'];
//		$dmgLocVal=$_POST['damageContLoc'];
		$userid=$this->session->userdata('id_user');
//		debux($_POST);die;
		//$userid='1';
		$data = $this->container->saveContainerGate($nocontainer, $pointcontainer, $truckJob, $EI, $idvesvoyage,$trucknumber, $sealid, $weight,$userid,$dmg, $dmgLoc,$tl,$esy);


		$q_del = "SELECT GTOUT_DATE FROM ITOS_OP.job_gate_manager 
				  WHERE NO_CONTAINER = '$nocontainer' 
				  AND ID_VES_VOYAGE = '$idvesvoyage' 
				  AND POINT = '$pointcontainer'";
		$rd    = $this->db->query($q_del)->row();
		$gout  = $rd->GTOUT_DATE;

		//debux($EI);die;

		if($EI == 'E'){

			if(empty($gout)){
				$q_del_d = "DELETE FROM ITOS_REPO.LOG_HANDLINGCONLISTCONT 
				WHERE HANDLINGS = 'GATE OUT RECEIVING' 
				AND NO_CONTAINER = '$nocontainer'			
				AND ID_VES_VOYAGE = '$idvesvoyage' 
			  	AND POINT = '$pointcontainer'";
			  	$this->db->query($q_del_d);
			}else{
				$q_del_d = "DELETE FROM ITOS_REPO.LOG_HANDLINGCONLISTCONT 
				WHERE HANDLINGS = 'GATE OUT RECEIVING' 
				AND NO_CONTAINER = '$nocontainer'			
				AND ID_VES_VOYAGE = '$idvesvoyage' 
			  	AND POINT = '$pointcontainer'
			  	AND CALL_SIGN IS NULL";
			  	$this->db->query($q_del_d);
			}

		}else{

			if(empty($gout)){
				$q_del_d = "DELETE FROM ITOS_REPO.LOG_HANDLINGCONLISTCONT 
				WHERE HANDLINGS = 'GATE OUT DELIVERY' 
				AND NO_CONTAINER = '$nocontainer'			
				AND ID_VES_VOYAGE = '$idvesvoyage' 
			  	AND POINT = '$pointcontainer'";
			  	$this->db->query($q_del_d);
			}else{
				$q_del_d = "DELETE FROM ITOS_REPO.LOG_HANDLINGCONLISTCONT 
				WHERE HANDLINGS = 'GATE OUT DELIVERY' 
				AND NO_CONTAINER = '$nocontainer'			
				AND ID_VES_VOYAGE = '$idvesvoyage' 
			  	AND POINT = '$pointcontainer'
			  	AND CALL_SIGN IS NULL";
			  	$this->db->query($q_del_d);
			}
		}


		echo json_encode($data);
		//printCoba();
	}
	
	function printCoba($cmr, $recdel,$cont,$pointcont,$trjob)
	{        
	    $this->load->library('pdf');

	    // set informasi dokumen
	    $this->pdf->SetSubject('TCPDF');
	    //$this->pdf->SetKeywords('CodeIgniter, TCPDF, PDF, example');
	    $this->pdf->SetFont('helvetica', '',8 );
		    $this->pdf->SetMargins(5,5,5);
		    $this->pdf->SetPrintHeader(false);
		    $this->pdf->SetPrintFooter(false);
		    $this->pdf->SetAutoPageBreak(TRUE, 0);
	    // menambahkan halaman (harus digunakan minimal 1 kali)
	    $this->pdf->AddPage('P', 'A7');
	    $dt=date('d M Y H:i:s');
		if($recdel=='REC')
		{
			$remarks='Receiving';
		}
		else if ($recdel=='DEL')
		{
			$remarks='Delivery';
		}
		 ob_start();
		$rowsd = $this->container->getCMSEIRinfo($cont,$pointcont);
		$nocont=$rowsd['NO_CONTAINER'];
		$szty=$rowsd['SIZETYPE'];
		$axle=$rowsd['AXLE_SIZE'];
		$combo=$rowsd['COMBO'];
		$weight=$rowsd['WEIGHT'];
		$ves_name = $rowsd['VESSEL_NAME'];
		$voy = $rowsd['VOYAGE'];
		$pod=$rowsd['PORT_NAME'];
		$id_op=$rowsd['ID_OPERATOR'];
		$imdg=$rowsd['IMDG'];
		$tmp=$rowsd['TEMP'];
		$shipp=$rowsd['CUSTOMER_NAME'];
		$trno= $rowsd['TID'];
		$slnm= $rowsd['SEAL_NUMB'];
		$alok=$rowsd['ALOKASI'];
		$gati=$rowsd['GATEIN'];
		$gato=$rowsd['GATEOUT'];
		$insp=$rowsd['INSPECTIONOPERATOR'];
		$dmg=$rowsd['DAMAGE'];
		$dmgloc=$rowsd['DAMAGE_LOCATION'];
		$tlflag=$rowsd['TL_FLAG'];
		$ittflag=$rowsd['ITT_FLAG'];
		$cmr = urldecode($cmr);
		$lbl_truck_date = 'Truck In';
		$truck_date = $gati;
		if($cmr == 'EIR' || $cmr == 'EIR-ESY'){
		    $lbl_truck_date = 'Truck Out';
		    $truck_date = $gato;
		}
		
		$tbl = <<<EOD
				
                <div align="center" ><b><font size="12px">$cmr</font></b>
				<br><b><font size="12px">iTOS</font></b>
				</div>
				<table width='100%' ALIGN='LEFT' class='coba'>
					<tr>
					<td>Transaction</td>
					<td>: $remarks</td>
					</tr>
					<tr>
					<td>No Container</td>
					<td>: $nocont</td>
					</tr>
					<tr>
					<td>Size / Type</td>
					<td>: $szty</td>
					</tr>
					<tr>
					<tr>
					<td>Axle</td>
					<td>: $axle</td>
					</tr>
					<tr>
					<tr>
					<td>Combo</td>
					<td>: $combo</td>
					</tr>
					<tr>
					<td>Truck Lossing</td>
					<td>: $tlflag</td>
					</tr>
					<tr>
					<td>Weight</td>
					<td>: $weight</td>
					</tr>
					<tr>
					<td>Vessel Name</td>
					<td>: $ves_name</td>
					</tr>
					<tr>
					<td>Voyage</td>
					<td>: $voy</td>
					</tr>
					<tr>
					<td>POD</td>
					<td>: $pod</td>
					</tr>
					<tr>
					<td>Owner</td>
					<td>: $id_op</td>
					</tr>
					<tr>
					<td>DG Label</td>
					<td>: $imdg</td>
					</tr>
					<tr>
					<td>Temperature</td>
					<td>: $tmp</td>
					</tr>
					<tr>
					<td>Customer</td>
					<td>: $shipp</td>
					</tr>
					<tr>
					<td>Truck No</td>
					<td>: $trno</td>
					</tr>
					<tr>
					<td>Seal No</td>
					<td>: $slnm</td>
					</tr>
					<tr>
					<td>Alocation</td>
					<td>: $alok</td>
					</tr>
					<tr>
					<td>$lbl_truck_date</td>
					<td>: $truck_date</td>
					</tr>
					<tr>
					<td>Inspection Op</td>
					<td>: $insp</td>
					</tr>
					<tr>
					<td>Damage</td>
					<td>: $dmg</td>
					</tr>
					<tr>
					<td>Damage Location</td>
					<td>: $dmgloc</td>
					</tr>
				</table>
				<br>
                <div align="center"><font size="6">Copyright IPC Information System Bureau 2015</font></div>
EOD;
                    
        
     
        ob_end_clean();
        

        $this->pdf->writeHTML($tbl, true, false, false, false, '');

        //Menutup dan menampilkan dokumen PDF
        $this->pdf->Output('try.pdf', 'I');
        
    }
	
}