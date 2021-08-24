<?php
class Container extends CI_Model {
	public function __construct(){
		$this->load->database();
//		$this->gtools->update_terminal();
	}
	
	public function get_category_list(){
		$query 		= "SELECT A.ID_CATEGORY, A.CATEGORY_NAME FROM M_PLAN_CATEGORY_H A
	WHERE A.STATUS=1 AND A.ID_TERMINAL='".$this->gtools->terminal()."' ORDER BY A.CATEGORY_NAME";
		
//		$query 		= "SELECT
//					D.CATEGORY_NAME,
//					D.ID_CATEGORY
//				FROM
//					YARD_PLAN_GROUP A
//				INNER JOIN M_YARD B ON A.ID_YARD = B.ID_YARD
//				INNER JOIN M_YARDBLOCK C ON A.ID_BLOCK = C.ID_BLOCK
//				INNER JOIN M_PLAN_CATEGORY_H D ON A.ID_CATEGORY = D.ID_CATEGORY
//		WHERE D.STATUS=1 AND D.ID_TERMINAL='".$this->gtools->terminal()."' ORDER BY D.CATEGORY_NAME";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
//		debux($query);exit;
		return $data;
	}
	
	public function get_name_category_existing(){
	    $query = "SELECT
					A.ID_CATEGORY,D.CATEGORY_NAME
				FROM
					YARD_PLAN_GROUP A
					INNER JOIN M_YARD B ON A.ID_YARD = B.ID_YARD
					INNER JOIN M_YARDBLOCK C ON A.ID_BLOCK = C.ID_BLOCK
					INNER JOIN M_PLAN_CATEGORY_H D ON A.ID_CATEGORY = D.ID_CATEGORY AND D.ID_TERMINAL='".$this->gtools->terminal()."'
				GROUP BY
					A.ID_CATEGORY,
					D.CATEGORY_NAME ";
	    
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
//		debux($query);exit;
		return $data;
	}
	
	public function data_paweight(){
		$query 		= "SELECT ID_PAWEIGHT, NAME_PAWEIGHT FROM M_PAWEIGHT_H WHERE ID_TERMINAL = '".$this->gtools->terminal()."' ORDER BY ID_PAWEIGHT";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function getCMSEIRinfo($cont,$point){
		$query="SELECT
					a.ID_VES_VOYAGE,a.CONT_STATUS,
					a.NO_CONTAINER,a.POINT,a.TL_FLAG, 
					a.CONT_SIZE||' / '||a.CONT_TYPE AS SIZETYPE,
					F.AXLE_SIZE, CASE WHEN LOWER(JTI.IN_OUT) = 'in' THEN JGIC.COMBO ELSE JGOC.COMBO END AS COMBO,
					A.WEIGHT || ' kgs' AS WEIGHT,
					vv.VESSEL_NAME,vv.VOY_IN || ' || ' || vv.VOY_OUT VOYAGE,
					G.PORT_NAME, a.ID_OPERATOR,
					a.IMDG, a.TEMP,
					d.CUSTOMER_NAME, 
					CASE WHEN a.ITT_FLAG = 'Y' THEN j.MCH_NAME ELSE  e.TID END AS TID,
					a.SEAL_NUMB,
					a.GT_JS_BLOCK_NAME||' - '||A.GT_JS_SLOT as ALOKASI,
					a.YD_BLOCK_NAME||' - '||a.YD_SLOT||' - '||A.YD_ROW||' - '||A.YD_TIER AS LOKASI,
					TO_CHAR(A.GT_DATE,'DD-MM-YYYY HH24:MI AM') as GATEIN,
					TO_CHAR(a.GT_DATE_OUT,'DD-MM-YYYY HH24:MI AM') AS GATEOUT,
					'OPERATOR' AS INSPECTIONOPERATOR,
					B.DAMAGE,
					C.DAMAGE_LOCATION,
					a.ITT_FLAG
				FROM con_listcont a
				JOIN ITOS_REPO.M_CYC_CONTAINER CYC ON A.NO_CONTAINER = CYC.NO_CONTAINER AND A.POINT = CYC.POINT
				LEFT JOIN job_gate_manager d on a.no_container=d.no_container and a.point=d.point and CYC.BILLING_REQUEST_ID=d.no_request and A.ID_TERMINAL = d.ID_TERMINAL
				LEFT JOIN m_truck e on A.ID_TRUCK=E.ID_TRUCK 
				LEFT JOIN m_damage b on a.id_damage=b.id_damage
				LEFT JOIN m_damage_location c on trim(a.id_damage_location)=c.id_damage_location
				LEFT JOIN VES_VOYAGE vv ON vv.ID_VES_VOYAGE = a.ID_VES_VOYAGE and  A.ID_TERMINAL = vv.ID_TERMINAL
				LEFT JOIN JOB_GATE_INSPECTION JGI ON JGI.NO_CONTAINER = A.NO_CONTAINER AND JGI.POINT = A.POINT
				LEFT JOIN (SELECT ID_TRUCK,EI,GTIN_LANE ,DATE_INSPECTION, CASE WHEN COUNT(*) > 1 THEN 'Y' ELSE 'N' END AS COMBO FROM JOB_GATE_INSPECTION WHERE ID_TERMINAL='".$this->gtools->terminal()."' GROUP BY ID_TRUCK,EI,GTIN_LANE ,DATE_INSPECTION) JGIC 
					ON JGI.ID_TRUCK = JGIC.ID_TRUCK AND JGI.EI = JGIC.EI AND JGI.GTIN_LANE = JGIC.GTIN_LANE AND JGI.DATE_INSPECTION = JGIC.DATE_INSPECTION
				LEFT JOIN (SELECT ID_TRUCK,EI,GTOUT_LANE ,DATE_INSPECTION, CASE WHEN COUNT(*) > 1 THEN 'Y' ELSE 'N' END AS COMBO FROM JOB_GATE_INSPECTION WHERE ID_TERMINAL='".$this->gtools->terminal()."' GROUP BY ID_TRUCK,EI,GTOUT_LANE ,DATE_INSPECTION) JGOC 
					ON JGI.ID_TRUCK = JGOC.ID_TRUCK AND JGI.EI = JGOC.EI AND JGI.GTOUT_LANE = JGOC.GTOUT_LANE AND JGI.DATE_INSPECTION = JGOC.DATE_INSPECTION
				LEFT JOIN M_AXLE_TRUCK F ON JGI.ID_AXLE = F.ID_AXLE
				LEFT JOIN M_PORT G ON A.ID_POD = G.PORT_CODE
				LEFT JOIN JOB_CONFIRM I ON A.NO_CONTAINER = I.NO_CONTAINER AND A.POINT = I.POINT
				LEFT JOIN M_MACHINE J ON I.ID_MACHINE_ITV = J.ID_MACHINE
				LEFT JOIN (SELECT A.ID_TRUCK,A.IN_OUT,A.DATE_TRINOUT 
					FROM JOB_TRUCK_INOUT A
					INNER JOIN (
						SELECT ID_TRUCK,MAX(DATE_TRINOUT) DATE_TRINOUT FROM JOB_TRUCK_INOUT GROUP BY ID_TRUCK
					) B ON A.ID_TRUCK = B.ID_TRUCK AND A.DATE_TRINOUT = B.DATE_TRINOUT
				) JTI ON A.ID_TRUCK = JTI.ID_TRUCK
			   	WHERE a.no_container='$cont' and a.point='$point' and a.ID_TERMINAL='".$this->gtools->terminal()."'";
		// echo '<pre>'.$query.'</pre>';exit;
		$rs 		= $this->db->query($query);
		$row 		= $rs->row_array();
		
		return $row;
	}
	
	public function get_datapaWeightD($id_paweight){
		$param = array($id_paweight);
		$query 		= "SELECT ID_PAWEIGHT, DNAME_PAWEIGHT, SIZE_PAWEIGHT, MAX_ESTPAWEIGHT, MIN_ESTPAWEIGHT, SIZE_PAWEIGHT || ' - ' || DNAME_PAWEIGHT AS TAMPIL FROM M_PAWEIGHT_D WHERE ID_PAWEIGHT=? ORDER BY SIZE_PAWEIGHT, DNAME_PAWEIGHT";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_dataImdg($unno){
		$param = array($unno);
		$query 		= "SELECT UNNO, IMDG, DESCRIPTION FROM M_HAZARDOUS_CODE WHERE UNNO = ? ORDER BY IMDG";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_dataUnno($imdg){
		$param = array($imdg);
		$query 		= "SELECT UNNO, IMDG, DESCRIPTION FROM M_HAZARDOUS_CODE WHERE IMDG = ? ORDER BY UNNO";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function getContainerListAvb($filter, $ei){

		$query 		= "
					SELECT 
						a.NO_CONTAINER as NO_CONTAINERX, 
                        a.ID_VES_VOYAGE, 
                        a.NO_CONTAINER||'-'||a.POINT AS CONT_INFO
                    FROM 
                        JOB_GATE_MANAGER a
                    LEFT JOIN 
                        VES_VOYAGE b ON a.ID_VES_VOYAGE = b.ID_VES_VOYAGE AND a.ID_TERMINAL=b.ID_TERMINAL
                    WHERE
                        a.EI = '$ei'
                        AND a.STATUS_FLAG in ('I','P','G','S')
						AND LOWER(a.NO_CONTAINER) LIKE '%".strtolower($filter)."%'
                        AND (b.FL_TONGKANG <> 'Y' OR b.FL_TONGKANG IS NULL)
					
					";
//		print($query);die;
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function insert_masterweight($a,$b){
		$param = array(
				array('name'=>':cat_name', 'value'=>$a, 'length'=>30),
				array('name'=>':param', 'value'=>$b, 'length'=>100),
				array('name'=>':terminal', 'value'=> $this->gtools->terminal(), 'length'=>100)
			);
		// print_r($param);
		$this->db->trans_start();
		$query = "begin prc_insmasteweight(:cat_name, :param, :terminal); end;";
		$this->db->exec_bind_stored_procedure($query, $param);
		
		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
	}

	public function delete_masterweight($id){
		$query1 = "UPDATE M_PLAN_CATEGORY_D SET PAWEIGHT = NULL, PAWEIGHT_D = NULL WHERE PAWEIGHT = '$id'";
		$update = $this->db->query($query1);

		if($update){
			$query = "DELETE M_PAWEIGHT_D WHERE ID_PAWEIGHT = '$id'";
			$del_sub = $this->db->query($query);

			if($del_sub){
				$query2 = "DELETE M_PAWEIGHT_H WHERE ID_PAWEIGHT = '$id'";

				$rs = $this->db->query($query2);

				if($rs){
					return 1;
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	public function get_category_detail_view($paging=false, $sort=false, $filters=false){
		$qPaging = '';
		if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "";
		
		$qs = '';
		$encoded = true;
                
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case 'filter_nama_vessel' : $field = "ID_VES_VOYAGE"; break;
				}
				
				switch($filterType){
					case 'string'   : $qs .= " WHERE ".$field." LIKE '%".strtoupper($value)."%'"; Break;
				}
			}
			$qWhere .= $qs;
		}
		$query_count = "SELECT
							COUNT (ID_CATEGORY) AS TOTAL
						FROM
							M_PLAN_CATEGORY_D
						$qWhere 
							";
		$rstotal = $this->db->query($query_count);
		$rowtotal = $rstotal->row_array();
		$total = $rowtotal['TOTAL'];
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  
									SELECT
										*
									FROM
										M_PLAN_CATEGORY_D
									$qWhere 
									$qSort
									) V
											) B
										$qPaging";
		$rs = $this->db->query($query);
		$alat_list = $rs->result_array();
		$data = array (
			'total'=>$total,
			'data'=>$alat_list
		);
		
		return $data;
	}
	public function get_category_detail($category_id){
		$query 		= "SELECT * FROM M_PLAN_CATEGORY_D WHERE ID_CATEGORY = '$category_id'";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function headerLoadingList($id_ves_voyage)
	{
		// $query 		= "SELECT A.VESSEL_NAME, VOY_IN||'-'||VOY_OUT AS VOYG, OPERATOR_NAME, A.ID_VESSEL, TO_CHAR(ATA,'DD-MM-YYYY HH24:MI') AS RTA,  TO_CHAR(ATB,'DD-MM-YYYY HH24:MI') AS RTB,  TO_CHAR(ATD,'DD-MM-YYYY HH24:MI') AS RTD,
		     // TO_CHAR(START_WORK,'DD-MM-YYYY HH24:MI') AS STW,  TO_CHAR(END_WORK,'DD-MM-YYYY HH24:MI') AS ENW
		 // FROM VES_VOYAGE A WHERE ID_VES_VOYAGE='$id_ves_voyage'";
		 
		 $query 		= "SELECT A.VESSEL_NAME, VOY_IN||'/'||VOY_OUT AS VOYG, OPERATOR_NAME, 
		A.ID_VESSEL, TO_CHAR(ATA,'DD-MM-YYYY HH24:MI') AS RTA,  
		TO_CHAR(ATB,'DD-MM-YYYY HH24:MI') AS RTB,  
		TO_CHAR(ATD,'DD-MM-YYYY HH24:MI') AS RTD,
		      (select 
		 to_char(min(start_work),'DD-MM-YYYY HH24:MI')
		 from MCH_WORKING_PLAN
		WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_ves_voyage')) AS STW, 
		       (select 
		 to_char(max(end_work),'DD-MM-YYYY HH24:MI')
		 from MCH_WORKING_PLAN
		WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_ves_voyage')) AS ENW
		 FROM VES_VOYAGE A WHERE ID_VES_VOYAGE='$id_ves_voyage' AND ID_TERMINAL ='".$this->gtools->terminal()."'";
 
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function detailLoadingList($id_ves_voyage){
		$query 		= "select NO_CONTAINER, POD, POL, ISO_CODE, CARRIER, IMO, TEMP, HZ, SEAL_ID, WEIGHT,LOKASI_BP, UN_NUMBER, CALL_SIGN, STATUS, CLASS_CODE, HEIGHT, DATE_CONFIRM  from EDI_BAPLIE WHERE ID_VES_VOYAGE='$id_ves_voyage'";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
//		echo '<pre>';print_r($query);echo '<pre>';exit;
		return $data;
	}
	
	public function edi_baplie_outbound($id_ves_voyage,$id_user){
		$terminalcode = $this->config->item('SITE_EDI_TERMINAL_CODE');
		$this->db->trans_start();
		
		//======================== POPULATE DATA ==============================//
		$query_data	= "BEGIN ITOS_OP.proc_populate_data_baplie('$id_ves_voyage','E','$id_user',".$this->gtools->terminal()."); END;";
		$this->db->query($query_data);
		//======================== POPULATE DATA ==============================//

		//======================== create header baplie ==============================//
		$queryhdr 	= "SELECT ID_VESSEL,
							  VOY_IN,
							  VOY_OUT,
							  VESSEL_NAME,
							  TO_CHAR(ETA,'RRRRMMDD') ETA_DATE,
							  TO_CHAR(ETA,'HH24MISS') ETA_HR,
							  TO_CHAR(ETB,'RRRRMMDDHH24MISS') ETB,
							  TO_CHAR(ETD,'RRRRMMDD') ETD_DATE,
							  TO_CHAR(ETD,'HH24MISS') ETD_HR,
							  CALL_SIGN,
							  OPERATOR
					   FROM VES_VOYAGE 
					   WHERE TRIM(ID_VES_VOYAGE) = UPPER('$id_ves_voyage') AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$hdr 		= $this->db->query($queryhdr);
		$row_hdr	= $hdr->result_array();

		$idves = $row_hdr[0]['ID_VESSEL'];
		$voyin = $row_hdr[0]['VOY_IN'];
		$voyout = $row_hdr[0]['VOY_OUT'];
		$vesnm = $row_hdr[0]['VESSEL_NAME'];
		$eta_date = $row_hdr[0]['ETA_DATE'];
		$eta_hr = $row_hdr[0]['ETA_HR'];
		$etb = $row_hdr[0]['ETB'];
		$etd_date = $row_hdr[0]['ETD_DATE'];
		$etd_hr = $row_hdr[0]['ETD_HR'];
		$cs = $row_hdr[0]['CALL_SIGN'];
		$oprves = $row_hdr[0]['OPERATOR'];
		// $oprves = 'MAEU';

		$file_name = "BPO_".strtoupper($id_ves_voyage)."_".date('ymdHis').".edi";

		$fp = fopen('./edifact/'.$file_name, 'w');
			  fwrite($fp, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$eta_date.":".$eta_hr."+0+++++0'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "UNH+MAG13040911183+BAPLIE:1:911:UN:SMDG15'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "BGM++GNCBAPLIE15MAG+9'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "DTM+137:".$eta_date.$eta_hr.":201'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "TDT+20+".$voyout."++".$cs.":103::".$vesnm."++0:172:20'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "LOC+5+IDPJG:139:6'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "LOC+61+TWKHH:139:6'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "DTM+178:".$eta_date."0000:201'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "DTM+136:".$eta_date."0000:201'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "DTM+132:".$eta_date.":101'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "RFF+VON:".$voyout."'");
			  fwrite($fp, PHP_EOL);
		//======================== create header baplie ==============================//

		//======================== create detail baplie ==============================//
		$querydtl 	= "SELECT NO_CONTAINER,
							  ISO_CODE,
							  CASE WHEN TRIM(UPPER(STATUS)) = 'FCL' THEN '5' 
								 ELSE '4' END STATUS,
							  POD,
							  POL,
							  CARRIER,
							  WEIGHT,
							  HZ,
							  SEAL_ID,
							  LOKASI_BP,
							  TEMP,
							  IMO,
							  UN_NUMBER,
							  HANDLING_INST,
							  OVER_FRONT,
							  OVER_TOP,
							  (OVER_LEFT+OVER_RIGHT) OVER_WIDTH
					   FROM EDI_BAPLIE 
					   WHERE CLASS_CODE = 'E' AND ID_TERMINAL = '".$this->gtools->terminal()."'
					   AND TRIM(ID_VES_VOYAGE) = UPPER('$id_ves_voyage')";
		$dtl 		= $this->db->query($querydtl);
		$data_dtl	= $dtl->result_array();

		$n = 0;
		foreach ($data_dtl as $row_dtl)
		{
			$nocont = $row_dtl['NO_CONTAINER'];
			$isocode = $row_dtl['ISO_CODE'];
			$status = $row_dtl['STATUS'];
			$idpod = $row_dtl['POD'];
			$idpol = $row_dtl['POL'];
			$opr = $row_dtl['CARRIER'];
			$wgt = $row_dtl['WEIGHT'];
			$hz = $row_dtl['HZ'];
			$slnumb = $row_dtl['SEAL_ID'];
			$locbp = $row_dtl['LOKASI_BP'];
			$temp = $row_dtl['TEMP'];
			$imo = $row_dtl['IMO'];
			$un = $row_dtl['UN_NUMBER'];
			$hi = $row_dtl['HANDLING_INST'];
			$hgh = $row_dtl['HEIGHT'];
			$ol = $row_dtl['OVER_FRONT'];
			$ow = $row_dtl['OVER_WIDTH'];
			$oh = $row_dtl['OVER_TOP'];

			fwrite($fp, "LOC+147+".$locbp."::5'");
			fwrite($fp, PHP_EOL);
			
			if($hi<>'')
			{
				fwrite($fp, "FTX+HAN+++".$hi."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			fwrite($fp, "MEA+WT++KGM:".$wgt."'");
			fwrite($fp, PHP_EOL);
			
			if($ty_cont=='RFR')
			{
				fwrite($fp, "TMP+2+".$temp."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			if(TRIM($hgh)=='OOG')
			{
				fwrite($fp, "DIM+9+CM:".$ol.":".$ow.":".$oh."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			fwrite($fp, "LOC+6+".$idpol."'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "LOC+12+".$idpod."'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "LOC+83+".$idpod."'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "RFF+BM:1'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "EQD+CN+".$nocont."+".$isocode."+++".$status."'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "NAD+CA+".$opr.":172:ZZZ'");
			fwrite($fp, PHP_EOL);
			
			if($hz=='Y')
			{
				fwrite($fp, "DGS+IMD+".$imo."+".$un."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			$n=$n+8;

		}
		//======================== create detail baplie ==============================//

		$jml_n = $n;
		  
		fwrite($fp, "UNT+".$jml_n."+MAG13040911183'");
		fwrite($fp, PHP_EOL);
		fwrite($fp, "UNZ+1+MAG13040911183'");
		fwrite($fp, PHP_EOL);
		fclose($fp);
		
		if ($this->db->trans_complete()){
			return array('flag'=>1, 'msg'=>$file_name);
		}else{
			return array('flag'=>0, 'msg'=>'error generate baplie');
		}
		
		return $file_name;
	}	

	public function data_class_code($filter){
		$query 		= "SELECT ID_CLASS_CODE, CODE_NAME FROM M_CLASS_CODE
						WHERE LOWER(CODE_NAME) LIKE '%".strtolower($filter)."%'";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function insert_category($category_name, $category_detail){
		$query = "SELECT MAX(ID_CATEGORY) AS MAX_ID FROM M_PLAN_CATEGORY_H WHERE ID_TERMINAL = '".$this->gtools->terminal()."'";
		$rs 		= $this->db->query($query);
		$row 		= $rs->row_array();
		$id = 1;
		if ($row['MAX_ID']){
			$id = $row['MAX_ID']+1;
		}
		
		$this->db->trans_start();
		
		$query 	= "INSERT INTO M_PLAN_CATEGORY_H
					(ID_CATEGORY, CATEGORY_NAME, ID_TERMINAL) VALUES('$id', '$category_name', ".$this->gtools->terminal().")";
		$rs 	= $this->db->query($query);
		
		for($i=0;$i<sizeof($category_detail);$i++){
			$detail = $category_detail[$i];
			$q_fields = "";
			$q_values = "";
			foreach($detail as $key=>$value){
				if ($q_fields!=''){
					$q_fields .= ",";
				}
				$q_fields .= $key;
				if ($q_values!=''){
					$q_values .= ",";
				}
				if ($key=='ID_CATEGORY'){
					$q_values .= "'".$id."'";
				}else if ($key=='ID_DETAIL'){
					$q_values .= "'".($i+1)."'";
				}else{
					$q_values .= "'".$value."'";
				}
			}
			
			$query 	= "INSERT INTO M_PLAN_CATEGORY_D ($q_fields) VALUES($q_values)";
			$rs 	= $this->db->query($query);
		}
		
		if ($this->db->trans_complete()){
			return $id;
		}else{
			return 0;
		}


	// 	$query = "SELECT DECODE(MAX(ID_CATEGORY),998,999,MAX(ID_CATEGORY)) AS MAX_ID FROM M_PLAN_CATEGORY_H";
	// 	$rs 		= $this->db->query($query);
	// 	$row 		= $rs->row_array();
	// 	$id = 1;
	// 	if ($row['MAX_ID']){
	// 		$id = $row['MAX_ID']+1;
	// 	}
		
	// 	$this->db->trans_start();
		
	// 	$param = array($id, $category_name);
	// 	$query 	= "INSERT INTO M_PLAN_CATEGORY_H
	// 				(ID_CATEGORY, CATEGORY_NAME) VALUES(?, ?)";
	// 	$rs 	= $this->db->query($query, $param);
		
		
		
	// 	for($i=0;$i<sizeof($category_detail);$i++){
	// 		$detail = $category_detail[$i];
	// 		$q_fields = "";
	// 		$q_values = "";
			
	// 		//===Edit by mustadio_gun
	// 		//===06/07/2017
	// 		//===purpose : add validation 
			
			
			
	// 		foreach($detail as $key=>$value){
				
	// 			// print_r($value);
	// 			// print_r('<br/>');
				
				
	// 			$array_detail[$key] = $value;
				
				
	// 			// print_r($array_detail['CONT_SIZE']);die;
				
	// 			// if($array_detail['CONT_SIZE'] == '' || 
	// 				// $array_detail['CONT_SIZE'] == '-' ||
	// 				// $array_detail['CONT_TYPE'] == '' ||
	// 				// $array_detail['CONT_TYPE'] == '-' ||
	// 				// $array_detail['CONT_STATUS'] == '' ||
	// 				// $array_detail['CONT_STATUS'] == '-' ||
	// 				// $array_detail['ID_PORT_DISCHARGE'] == '' ||
	// 				// $array_detail['ID_PORT_DISCHARGE'] == '-' ||
	// 				// $array_detail['ID_VES_VOYAGE'] == '' ||
	// 				// $array_detail['ID_VES_VOYAGE'] == '-' ||
	// 				// $array_detail['ID_OPERATOR'] == '' ||
	// 				// $array_detail['ID_OPERATOR'] == '-' ||
	// 				// $array_detail['CONT_HEIGHT'] == '' ||
	// 				// $array_detail['CONT_HEIGHT'] == '-' ||
	// 				// $array_detail['E_I'] == '' ||
	// 				// $array_detail['E_I'] == '-' ||
	// 				// $array_detail['O_I'] == '' ||
	// 				// $array_detail['O_I'] == '-' 
	// 				// )
				
				
	// 			//===End of edit by mustadio_gun
				
	// 			if ($q_fields!=''){
	// 				$q_fields .= ",";
	// 			}
	// 			$q_fields .= $key;
	// 			if ($q_values!=''){
	// 				$q_values .= ",";
	// 			}
	// 			if ($key=='ID_CATEGORY'){
	// 				$q_values .= "'".$id."'";
	// 			}else if ($key=='ID_DETAIL'){
	// 				$q_values .= "'".($i+1)."'";
	// 			}else if ($key=='ID_PORT_DISCHARGE'){
	// 				$q_values .= "'".substr($value,0,5)."'";
	// 			}else{
	// 				$q_values .= "'".$value."'";
	// 			}
	// 		}
	// 		// print_r($array_detail);
	// 		// die;
			
	// 		// if($array_detail['CONT_SIZE'] == '' || 
	// 				// $array_detail['CONT_SIZE'] == '-' || 
	// 				// $array_detail['CONT_SIZE'] == NULL 
	// 				// )
	// 				if($array_detail['CONT_SIZE'] == '' || 
	// 				$array_detail['CONT_SIZE'] == '-' ||
	// 				$array_detail['CONT_TYPE'] == '' ||
	// 				$array_detail['CONT_TYPE'] == '-' ||
	// 				$array_detail['CONT_STATUS'] == '' ||
	// 				$array_detail['CONT_STATUS'] == '-' ||
	// 				$array_detail['ID_PORT_DISCHARGE'] == '' ||
	// 				$array_detail['ID_PORT_DISCHARGE'] == '-' ||
	// 				$array_detail['ID_VES_VOYAGE'] == '' ||
	// 				$array_detail['ID_VES_VOYAGE'] == '-' ||
	// 				$array_detail['ID_OPERATOR'] == '' ||
	// 				$array_detail['ID_OPERATOR'] == '-' ||
	// 				// $array_detail['CONT_HEIGHT'] == '' ||
	// 				// $array_detail['CONT_HEIGHT'] == '-' ||
	// 				$array_detail['E_I'] == '' ||
	// 				$array_detail['E_I'] == '-' ||
	// 				$array_detail['O_I'] == '' ||
	// 				$array_detail['O_I'] == '-' 
	// 				)
	// 			{
	// 				exit("0");
	// 			}
			
	// 		$query 	= "INSERT INTO M_PLAN_CATEGORY_D ($q_fields) VALUES($q_values)";
	// 		$rs 	= $this->db->query($query);
	// 	}
		
	// 	// print_r($array_detail['CONT_SIZE']);
		
	// 	strlen("Hello");
		
	// 	if ($this->db->trans_complete()){
	// 		 return $id;
	// 		// return 1;
			
	// 	}else{
	// 		return 0;
	// 	}
	}
	
	public function insert_category_detail($category_id, $detail){
		$query = "SELECT MAX(ID_DETAIL) AS MAX_ID FROM M_PLAN_CATEGORY_D WHERE ID_CATEGORY='$category_id'";
		$rs 		= $this->db->query($query);
		$row 		= $rs->row_array();
		$id = 1;
		if ($row['MAX_ID']){
			$id = $row['MAX_ID']+1;
		}
		
		$query 	= "INSERT INTO M_PLAN_CATEGORY_D
					(ID_CATEGORY, ID_DETAIL, CONT_SIZE, CONT_TYPE, CONT_STATUS, E_I) VALUES('$category_id', '$id', '".$detail['CONT_SIZE']."', '".$detail['CONT_TYPE']."', '".$detail['CONT_STATUS']."', '".$detail['E_I']."')";
		$rs 	= $this->db->query($query);
		
		if ($rs){
			return $id;
		}else{
			return 0;
		}
	}
	
	public function update_category_detail($category_id, $detail_id, $data){
		$q_set = "";
		if($detail_id != ''){
		    foreach($data as $key=>$value){
			    if ($q_set!=""){
				    $q_set .= ",";
			    }

			    if ($key=='ID_PORT_DISCHARGE'){
				    $value = substr($value,0,5);
			    }
			    $q_set .= $key." = '".$value."'";
		    }
		    $query 	= "UPDATE M_PLAN_CATEGORY_D
					    SET $q_set
					    WHERE ID_CATEGORY = '$category_id' AND ID_DETAIL = '$detail_id'";
		}else{
//		    echo '<pre>';print_r($data);'</pre>';
		    $query 	= "INSERT INTO ITOS_OP.M_PLAN_CATEGORY_D
				    (ID_CATEGORY, ID_DETAIL, CONT_SIZE, CONT_TYPE, CONT_STATUS, ID_PORT_DISCHARGE, ID_VES_VOYAGE, ID_OPERATOR, CONT_HEIGHT, HAZARD, UNNO, E_I, O_I, IMDG, PAWEIGHT, PAWEIGHT_D)
				    VALUES('$category_id', (SELECT MAX(ID_DETAIL) + 1 AS MAX_ID FROM M_PLAN_CATEGORY_D WHERE ID_CATEGORY='$category_id')"
				    . ", '".(isset($data['CONT_SIZE']) && $data['CONT_SIZE'] != '' ? $data['CONT_SIZE']: '-')."'"
				    . ", '".(isset($data['CONT_TYPE']) && $data['CONT_TYPE'] != '' ? $data['CONT_TYPE']: '-')."'"
				    . ", '".(isset($data['CONT_STATUS']) && $data['CONT_STATUS'] != '' ? $data['CONT_STATUS']: '-')."'"
				    . ", '".$data['ID_PORT_DISCHARGE']."'"
				    . ", '".$data['ID_VES_VOYAGE']."'"
				    . ", '".$data['ID_OPERATOR']."'"
				    . ", '".$data['CONT_HEIGHT']."'"
				    . ", '".$data['HAZARD']."'"
				    . ", '".$data['UNNO']."'"
				    . ", '".(isset($data['E_I']) && $data['E_I'] != '' ? $data['E_I']: '-')."'"
				    . ", '".$data['O_I']."'"
				    . ", '".$data['IMDG']."'"
				    . ", '".$data['PAWEIGHT']."'"
				    . ", '".$data['PAWEIGHT_D']."')";
		}
//		echo $query;exit;
		$rs 	= $this->db->query($query);
		
		if ($rs){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function delete_category_detail($category_id, $detail_id){
		$query 	= "DELETE M_PLAN_CATEGORY_D
					WHERE ID_CATEGORY = '$category_id' AND ID_DETAIL = '$detail_id'";
		$rs 	= $this->db->query($query);
		
		if ($rs){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function delete_category_plan($category_id){
		$this->db->trans_start();
		
		$query 	= "UPDATE M_PLAN_CATEGORY_H
					SET STATUS=0
					WHERE ID_CATEGORY = '$category_id' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$rs 	= $this->db->query($query);
		
		$query 	= "DELETE YARD_PLAN
					WHERE ID_CATEGORY = '$category_id'";
		$rs 	= $this->db->query($query);
		
		$query 	= "DELETE YARD_PLAN_GROUP
					WHERE ID_CATEGORY = '$category_id'";
		$rs 	= $this->db->query($query);
		
		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function get_cont_size_list(){
		$query 		= "SELECT CONT_SIZE, NAME FROM M_CONT_SIZE ORDER BY CONT_SIZE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_cont_type_list(){
		$query 		= "SELECT CONT_TYPE, NAME FROM M_CONT_TYPE ORDER BY CONT_TYPE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function data_cont_spec_hand(){
		$query 		= "SELECT ID_SPEC_HAND, DESCRIPTION AS NAME FROM m_spec_handling ORDER BY ID_SPEC_HAND";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_cont_status_list(){
		$query 		= "SELECT CONT_STATUS, NAME FROM M_CONT_STATUS ORDER BY CONT_STATUS";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_port_list($filter, $id_ves_voyage = ''){
		// $query 		= "SELECT PORT_CODE, PORT_CODE||'-'||PORT_NAME PORT_NAME FROM M_PORT
		// WHERE LOWER(PORT_NAME) LIKE '%".strtolower($filter)."%'
		// OR LOWER(PORT_CODE) LIKE '%".strtolower($filter)."%'
		// ORDER BY PORT_CODE";
		
		// Developt by Yazir untuk memanggil data port sesuai dengan kapalnya
		$qWhare = "AND (LOWER(P.PORT_NAME) LIKE '%".strtolower($filter)."%'
			    OR LOWER(P.PORT_CODE) LIKE '%".strtolower($filter)."%')
			";
		$qWhareVesVoy = '1=1';
		if($id_ves_voyage != ''){
		    $qWhareVesVoy = "ID_VES_VOYAGE = '$id_ves_voyage'";
		}
		$query = "SELECT DISTINCT vsp.id_port PORT_CODE, p.port_name
			FROM m_vessel_service vs
				 LEFT JOIN m_vessel_service_port vsp
					ON vs.id_vessel_service = vsp.id_vessel_service
				 LEFT JOIN m_port p
					ON vsp.id_port = p.port_code
		   WHERE vs.id_vessel_service IN (SELECT V.IN_SERVICE service
											FROM VES_VOYAGE V
										   WHERE $qWhareVesVoy AND ID_TERMINAL='".$this->gtools->terminal()."'
										  UNION
										  SELECT V.OUT_SERVICE service
											FROM VES_VOYAGE V
										   WHERE $qWhareVesVoy AND ID_TERMINAL='".$this->gtools->terminal()."')
				 AND vsp.id_port IS NOT NULL
		    $qWhare
		    ORDER BY vsp.id_port";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
//		echo '<pre>'.$query.'</pre>';
		return $data;
	}
	
		public function get_port_list_multiple($filter, $id_ves_voyage = ''){
//		$query 		= "SELECT PORT_CODE, PORT_CODE||'-'||PORT_NAME PORT_NAME FROM M_PORT
//		WHERE LOWER(PORT_NAME) LIKE '%".strtolower($filter)."%'
//			OR LOWER(PORT_CODE) LIKE '%".strtolower($filter)."%'
//		ORDER BY PORT_CODE";
		
		// Developt by Yazir untuk memanggil data port sesuai dengan kapalnya
		$qWhare = "AND (LOWER(P.PORT_NAME) LIKE '%".strtolower($filter)."%'
			    OR LOWER(P.PORT_CODE) LIKE '%".strtolower($filter)."%')
			";
		$qWhareVesVoy = '1=1';
		if($id_ves_voyage != ''){
		    $qWhareVesVoy = "ID_VES_VOYAGE = '$id_ves_voyage'";
		}
		$query = "SELECT DISTINCT vsp.id_port ID_POD, p.port_name
			FROM m_vessel_service vs
				 LEFT JOIN m_vessel_service_port vsp
					ON vs.id_vessel_service = vsp.id_vessel_service
				 LEFT JOIN m_port p
					ON vsp.id_port = p.port_code
		   WHERE vs.id_vessel_service IN (SELECT V.IN_SERVICE service
											FROM VES_VOYAGE V
										   WHERE $qWhareVesVoy AND ID_TERMINAL='".$this->gtools->terminal()."'
										  UNION
										  SELECT V.OUT_SERVICE service
											FROM VES_VOYAGE V
										   WHERE $qWhareVesVoy AND ID_TERMINAL='".$this->gtools->terminal()."')
				 AND vsp.id_port IS NOT NULL
		    $qWhare
		    ORDER BY vsp.id_port";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
//		echo '<pre>'.$query.'</pre>';
		return $data;
	}

	public function get_operator_list($filter, $id_ves_voyage = ''){
//		$query 		= "SELECT ID_OPERATOR, ID_OPERATOR||' - '||OPERATOR_NAME OPERATOR_NAME FROM M_OPERATOR
//		WHERE LOWER(OPERATOR_NAME) LIKE '%".strtolower($filter)."%'
//			OR LOWER(ID_OPERATOR) LIKE '%".strtolower($filter)."%'
//		ORDER BY OPERATOR_NAME";
		
		// Developt by Yazir untuk memanggil data operator sesuai dengan kapalnya
		$qWhare = "AND (LOWER(O.OPERATOR_NAME) LIKE '%".strtolower($filter)."%'
			    OR LOWER(VSO.ID_OPERATOR) LIKE '%".strtolower($filter)."%')
			";
		$qWhareVesVoy = '1=1';
		if($id_ves_voyage != ''){
		    $qWhareVesVoy = "ID_VES_VOYAGE = '$id_ves_voyage'";
		}
		
		$query = "SELECT distinct vso.id_operator, o.operator_name
		  FROM	m_vessel_service vs
			   LEFT JOIN
				  m_vessel_service_operator vso
			   ON vs.id_vessel_service = vso.id_vessel_service
			   LEFT JOIN
				  m_operator o
			   ON vso.id_operator = o.id_operator
		 WHERE vs.id_vessel_service IN (SELECT V.IN_SERVICE service
											FROM VES_VOYAGE V
										   WHERE $qWhareVesVoy AND ID_TERMINAL='".$this->gtools->terminal()."'
										  UNION
										  SELECT V.OUT_SERVICE service
											FROM VES_VOYAGE V
										   WHERE $qWhareVesVoy AND ID_TERMINAL='".$this->gtools->terminal()."')
			  AND vso.id_operator IS NOT NULL
			$qWhare
			ORDER BY O.OPERATOR_NAME";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_cont_height_list(){
		$query 		= "SELECT CONT_HEIGHT, NAME FROM M_CONT_HEIGHT ORDER BY CONT_HEIGHT";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_unno_list($filter){
		$query 		= "SELECT UNNO FROM M_HAZARDOUS_CODE
		WHERE UNNO LIKE '".$filter."%' GROUP BY UNNO
		ORDER BY UNNO";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_imdg_list($filter){
		$query 		= "SELECT DISTINCT IMDG FROM M_HAZARDOUS_CODE
		WHERE IMDG LIKE '".$filter."%'
		ORDER BY IMDG";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_data_by_id_ves_voyage($id_ves_voyage)
	{
		
		$container_list = $this->db->query("SELECT a.*, TO_CHAR(a.CONFIRM_DATE, 'DD-MM-YYYY hh24:mi') AS CONFIRM_DATE_, b.YARD_NAME 
			FROM CON_LISTCONT a 
			LEFT JOIN M_YARD b
			ON a.YD_YARD=b.ID_YARD 
			WHERE a.ID_VES_VOYAGE ='".$id_ves_voyage."'
						AND TRIM (ID_OP_STATUS) <> 'DIS'
						AND ID_CLASS_CODE IN ('I', 'TI','TC','S1','S2') AND a.ID_TERMINAL='".$this->gtools->terminal()."' 
						AND
							CASE
								WHEN (a.ID_CLASS_CODE = 'S1'
								OR a.ID_CLASS_CODE = 'S2')
								AND (
								SELECT
									COUNT(*)
								FROM
									JOB_SHIFTING
								WHERE
									ID_VES_VOYAGE = a.ID_VES_VOYAGE
									AND NO_CONTAINER = a.NO_CONTAINER) > 0 THEN a.POINT
								ELSE 1
							END =
							CASE
								WHEN (a.ID_CLASS_CODE = 'S1'
								OR a.ID_CLASS_CODE = 'S2')
								AND (
								SELECT
									COUNT(*)
								FROM
									JOB_SHIFTING
								WHERE
									ID_VES_VOYAGE = a.ID_VES_VOYAGE
									AND NO_CONTAINER = a.NO_CONTAINER) > 0 THEN (
								SELECT
									POINT
								FROM
									JOB_SHIFTING
								WHERE
									ID_VES_VOYAGE = a.ID_VES_VOYAGE
									AND NO_CONTAINER = a.NO_CONTAINER
									AND POINT = 1)
								ELSE 1
							END
						--AND b.ID_TERMINAL='".$this->gtools->terminal()."'
			ORDER BY A.NO_CONTAINER")->result_array();

		for ($i=0; $i<sizeof($container_list); $i++){
			$container_list[$i]['STOWAGE'] = '';
			$container_list[$i]['YARD_POS'] = '';
			$container_list[$i]['NO_CONTAINER_OLD'] = $container_list[$i]['NO_CONTAINER'];
			$container_list[$i]['NPE'] = $container_list[$i]['NPE'];
			$container_list[$i]['WEIGHT'] = $container_list[$i]['WEIGHT']/1000;
			if ($container_list[$i]['VS_BAY']!=''){
				$container_list[$i]['STOWAGE'] = str_pad($container_list[$i]['VS_BAY'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_ROW'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_TIER'],2,'0',STR_PAD_LEFT);
			}
			if ($container_list[$i]['VS_BAY_TO']!='' && ($container_list[$i]['ID_CLASS_CODE']=="S1" || $container_list[$i]['ID_CLASS_CODE']=="S2")){
				$container_list[$i]['STOWAGE_TO'] = str_pad($container_list[$i]['VS_BAY_TO'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_ROW_TO'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_TIER_TO'],2,'0',STR_PAD_LEFT);
				
			}
			if($container_list[$i]['ITT_FLAG']=='Y'){
				$query3 = "SELECT D.NO_CONTAINER, D.ID_ITT, H.YARD_NAME_LINI2
							FROM CON_ITT_D D
							LEFT JOIN CON_ITT_H H ON H.ID_ITT=D.ID_ITT
							WHERE D.ID_VES_VOYAGE = '".$container_list[$i]['ID_VES_VOYAGE']."' AND D.NO_CONTAINER = '".$container_list[$i]['NO_CONTAINER']."'
							GROUP BY D.NO_CONTAINER, D.ID_ITT, H.YARD_NAME_LINI2";
				$result = $this->db->query($query3);
				$yard = $result->roW();

				$container_list[$i]['YARD_POS'] = $yard->YARD_NAME_LINI2;

			}else{
				if ($container_list[$i]['YD_BLOCK_NAME']!=''){
					$container_list[$i]['YARD_POS'] = $container_list[$i]['YD_BLOCK_NAME'].'-'.($container_list[$i]['CONT_SIZE'] >= 40 ? $container_list[$i]['YD_SLOT'] + 1 : $container_list[$i]['YD_SLOT']).'-'.$container_list[$i]['YD_ROW'].'-'.$container_list[$i]['YD_TIER'];
				}
			}
		}
		//debux($container_list);die;
		return $container_list;
		
	}
	
	public function get_data_inbound_outbound_list($id_ves_voyage='', $class_code, $paging=false, $sort=false, $filters=false, $container_list=false){
		$class_code_str = '';
		$is_paid = '';
		if ($class_code=='I'){
			$class_code_str = "'I', 'TI','TC','S1','S2'";
		}else if ($class_code=='E'){
			$class_code_str = "'E', 'TE','TC','S1','S2'";
			// $is_paid = "AND (E.STATUS IN ('P','T') OR E.STATUS IS NULL)";
			$is_paid = "AND NVL(E.BILLING_PAID,'0') = 
						(CASE WHEN C.ID_CLASS_CODE = 'E' THEN E.BILLING_PAID
						ELSE '0' 
						END)";
		}
		$id_terminal = $this->gtools->terminal();
		
		
		$qPaging = '';
		if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE A.REC_NUM >= $start AND A.REC_NUM <= $end";
		}
		$qSort = '';
		if ($sort != false){
		    $sortDirection = $sort[0]->direction;
		    if($sort[0]->property == 'STOWAGE_PLAN'){
			$sortProperty = "LPAD(CO.BAY_,2,'0') || LPAD(CO.ROW_,2,'0') || LPAD(CO.TIER_,2,'0')"; 
		    }else{
			$sortProperty = 'C.'.$sort[0]->property; 
			if ($sortProperty=='C.STOWAGE'){
				$sortProperty = "CASE WHEN C.ACTIVITY = 'E' AND CO.STATUS = 'C' OR C.ACTIVITY = 'I' THEN LPAD(C.VS_BAY,2,'0') || LPAD(C.VS_ROW,2,'0') || LPAD(C.VS_TIER,2,'0') ELSE '' END";
			}
			if ($sortProperty=='C.YARD_POS'){
				$sortProperty = "CASE WHEN C.ITT_FLAG = 'Y' THEN 
						IH.YARD_NAME_LINI2
					 ELSE
						C.YD_BLOCK_NAME 
					 END $sortDirection,
			    	CASE WHEN C.CONT_SIZE >= 40 THEN C.YD_SLOT + 1 ELSE C.YD_SLOT END $sortDirection,
			    	C.YD_ROW $sortDirection,
			    	C.YD_TIER $sortDirection";
				$sortDirection = '';
			}
			if($sortProperty=='C.CONFIRM_DATE_'){
				$sortProperty = 'CONFIRM_DATE';
				$sortDirection = $sortDirection." NULLS LAST";
			}
		    }
//		    $qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		    $qSort = "ROW_NUMBER() OVER(
					ORDER BY ".$sortProperty." ".$sortDirection."
					    ) REC_NUM,";
		}
		$qWhere = "	WHERE C.ID_VES_VOYAGE= '$id_ves_voyage' 
					AND TRIM(C.ID_OP_STATUS) <> 'DIS' 
					AND C.ID_CLASS_CODE IN ($class_code_str) 
					AND C.ID_TERMINAL='".$this->gtools->terminal()."'
					$is_paid";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];

				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = ($filter->field == 'NO_CONTAINER') ? 'C.NO_CONTAINER' : $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = ($filter['field'] == 'NO_CONTAINER') ? 'C.NO_CONTAINER' : $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}

				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		if ($container_list != false){
			$qWhere .= " AND C.NO_CONTAINER IN ($container_list) ";
		}

		$query = "SELECT 
					A.*
			 FROM 
					(
					SELECT $qSort
					    C.NO_CONTAINER
					    ,C.NO_CONTAINER AS NO_CONTAINER_OLD
					    ,C.POINT
					    ,C.ACTIVITY
					    ,C.ID_VES_VOYAGE
					    ,C.UNNO
					    ,C.IMDG
					    ,C.ID_ISO_CODE
					    ,C.ID_OP_STATUS
					    ,C.OP_STATUS_DESC
					    ,C.ID_CLASS_CODE
					    ,C.CONT_SIZE
					    ,C.CONT_TYPE
					    ,C.CONT_STATUS
					    ,C.CONT_HEIGHT
					    ,C.ID_TRUCK,C.ID_CATEGORY,C.GT_WEIGHT,C.SEAL_NUMB,C.GT_DATE,C.GT_JS_YARD,C.GT_JS_BLOCK,C.GT_JS_SLOT,C.GT_JS_ROW,C.GT_JS_TIER,C.GT_JS_BLOCK_NAME,TO_CHAR(NVL(C.CONFIRM_DATE,JQM.COMPLETE_DATE), 'DD-MM-YYYY hh24:mi') AS CONFIRM_DATE_
					    ,C.PLACEMENT_DATE,C.INSPECTION_DATE,C.ID_USER_BAPLIE,C.NO_REQUEST,C.FLAG_REQUEST,C.GT_DATE_OUT,C.ACTIVE,C.TL_FLAG,C.ID_DAMAGE,C.ID_DAMAGE_LOCATION
					    ,C.USER_CORRECTION,C.NPE,C.TGL_NPE,C.BL_NUMBER,C.ID_SPEC_HAND,C.IS_MIGRATION,C.FL_TONGKANG,C.ITT_FLAG,C.ID_TERMINAL,C.CUSTOMER_NAME,C.QC_PLAN,C.QC_REAL
					    ,C.STATUS_BM,C.COMPLETE_DATE_BM,C.YC_PLAN,C.YC_REAL,C.STATUS_PLACEMENT,C.COMPLETE_DATE_PLACEMENT,C.OVER_HEIGHT,C.OVER_RIGHT,C.OVER_LEFT,C.OVER_FRONT
					    ,C.OVER_REAR,C.OVER_WIDTH,C.HOLD_CONTAINER 
					    ,C.ID_POD,C.ID_POL,C.ID_POR,C.ID_OPERATOR,(C.WEIGHT/1000) WEIGHT,C.TEMP,C.ID_COMMODITY,C.HAZARD
					    ,C.YD_YARD,C.YD_BLOCK
					    ,CASE WHEN C.ITT_FLAG = 'Y' THEN 
						    IH.YARD_NAME_LINI2
					     ELSE
						    CASE WHEN C.YD_BLOCK_NAME IS NOT NULL THEN 
							C.YD_BLOCK_NAME 
							    || '-' || CASE WHEN C.CONT_SIZE >= 40 THEN C.YD_SLOT + 1 ELSE C.YD_SLOT END 
							    || '-' || C.YD_ROW || '-' || C.YD_TIER
						    END
					     END AS YARD_POS
					     ,CASE WHEN C.ACTIVITY = 'E' AND CO.STATUS = 'C' OR C.ACTIVITY = 'I' THEN LPAD(C.VS_BAY,2,'0') || LPAD(C.VS_ROW,2,'0') || LPAD(C.VS_TIER,2,'0') ELSE '' END AS STOWAGE
					     ,LPAD(C.VS_BAY_TO,2,'0') || LPAD(C.VS_ROW_TO,2,'0') || LPAD(C.VS_TIER_TO,2,'0') STOWAGE_TO
					     ,LPAD(CO.BAY_,2,'0') || LPAD(CO.ROW_,2,'0') || LPAD(CO.TIER_,2,'0') STOWAGE_PLAN
					     ,co.STATUS
					 FROM (	
						SELECT  C.*
							,CASE WHEN (C.ID_CLASS_CODE IN ('S1','S2') AND ((JSI.POINT IS NOT NULL AND C.POINT = JSI.POINT) OR (JSI.POINT IS NULL AND JSE.POINT IS NULL))) OR C.ID_CLASS_CODE IN ('TI','I','TC') THEN 'I' 
									  ELSE 'E' END AS ACTIVITY 
						FROM
							CON_LISTCONT C
						LEFT JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER AND C.POINT = E.POINT
						LEFT JOIN (SELECT ID_VES_VOYAGE,NO_CONTAINER,ID_CLASS_CODE,MIN(POINT) POINT FROM CON_LISTCONT WHERE ID_CLASS_CODE IN ('S1','S2') GROUP BY ID_VES_VOYAGE,NO_CONTAINER,ID_CLASS_CODE) JSI ON JSI.NO_CONTAINER = C.NO_CONTAINER AND JSI.ID_VES_VOYAGE = C.ID_VES_VOYAGE AND JSI.ID_CLASS_CODE = C.ID_CLASS_CODE AND JSI.POINT = C.POINT
						LEFT JOIN (SELECT ID_VES_VOYAGE,NO_CONTAINER,ID_CLASS_CODE,MAX(POINT) POINT FROM CON_LISTCONT WHERE ID_CLASS_CODE IN ('S1','S2') GROUP BY ID_VES_VOYAGE,NO_CONTAINER,ID_CLASS_CODE) JSE ON JSE.NO_CONTAINER = C.NO_CONTAINER AND JSE.ID_VES_VOYAGE = C.ID_VES_VOYAGE AND JSE.ID_CLASS_CODE = C.ID_CLASS_CODE AND JSE.POINT = C.POINT
						$qWhere
					) C
					LEFT JOIN CON_OUTBOUND_SEQUENCE CO 
						ON CO.ID_VES_VOYAGE=C.ID_VES_VOYAGE AND C.NO_CONTAINER = CO.NO_CONTAINER 
						AND C.POINT = CO.POINT
					LEFT JOIN JOB_QUAY_MANAGER JQM ON C.NO_CONTAINER = JQM.NO_CONTAINER AND C.POINT = JQM.POINT
					LEFT JOIN CON_ITT_D ID ON C.NO_CONTAINER = ID.NO_CONTAINER AND C.POINT = ID.POINT
					LEFT JOIN CON_ITT_H IH ON ID.ID_ITT = IH.ID_ITT
					WHERE C.ACTIVITY = '$class_code'  ) A
				  $qPaging"; 
//		debux($query);die;
				  /*$query = "
				  	SELECT
							B.*, 0 STATUS_EDIT
						FROM
							(
								SELECT
									A .*, ROWNUM REC_NUM
								FROM
									(
										SELECT
											C.*, ROW_NUMBER () OVER (ORDER BY C.NO_CONTAINER ASC) AS NOMOR, CH.VS_BAY_OLD, CH.VS_ROW_OLD, CH.VS_TIER_OLD
										FROM
											CON_LISTCONT C
										INNER JOIN (SELECT CON_LISTCONT_HIST.NO_CONTAINER,
													CON_LISTCONT_HIST.POINT,
													CON_LISTCONT_HIST.ID_TERMINAL,
													CON_LISTCONT_HIST.VS_BAY VS_BAY_OLD,
													CON_LISTCONT_HIST.VS_ROW VS_ROW_OLD,
													CON_LISTCONT_HIST.VS_TIER VS_TIER_OLD FROM CON_LISTCONT_HIST 
													GROUP BY 
													CON_LISTCONT_HIST.NO_CONTAINER,
													CON_LISTCONT_HIST.POINT,
													CON_LISTCONT_HIST.ID_TERMINAL,
													CON_LISTCONT_HIST.VS_BAY VS_BAY_OLD,
													CON_LISTCONT_HIST.VS_ROW VS_ROW_OLD
													ORDER BY 
													) CH ON CH.NO_CONTAINER = C.NO_CONTAINER AND C. POINT = CH. POINT
												AND C.ID_TERMINAL = CH.ID_TERMINAL
										LEFT JOIN ITOS_BILLING.REQ_RECEIVING_D D ON D .NO_CONTAINER = C.NO_CONTAINER
										LEFT JOIN ITOS_BILLING.REQ_RECEIVING_H E ON E .ID_REQ = D .NO_REQ_ANNE
										
								$qWhere AND C.ID_TERMINAL='".$this->gtools->terminal()."' $qSort) A) B
				  $qPaging
				  ";*/

//		debux($query);die;
		$rs = $this->db->query($query, $param);
		$container_list = $rs->result_array();
//		echo '<pre>';print_r($this->db->last_query());echo '</pre>';exit;
		$query_count = "SELECT COUNT(C.NO_CONTAINER) TOTAL
						FROM (	
							SELECT  C.*
							,CASE WHEN (C.ID_CLASS_CODE IN ('S1','S2') AND ((JSI.POINT IS NOT NULL AND C.POINT = JSI.POINT) OR (JSI.POINT IS NULL AND JSE.POINT IS NULL))) OR C.ID_CLASS_CODE IN ('TI','I','TC') THEN 'I' 
									  ELSE 'E' END AS ACTIVITY 
						FROM
							CON_LISTCONT C
							LEFT JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER AND C.POINT = E.POINT
							LEFT JOIN (SELECT ID_VES_VOYAGE,NO_CONTAINER,ID_CLASS_CODE,MIN(POINT) POINT FROM CON_LISTCONT WHERE ID_CLASS_CODE IN ('S1','S2') GROUP BY ID_VES_VOYAGE,NO_CONTAINER,ID_CLASS_CODE) JSI ON JSI.NO_CONTAINER = C.NO_CONTAINER AND JSI.ID_VES_VOYAGE = C.ID_VES_VOYAGE AND JSI.ID_CLASS_CODE = C.ID_CLASS_CODE AND JSI.POINT = C.POINT
							LEFT JOIN (SELECT ID_VES_VOYAGE,NO_CONTAINER,ID_CLASS_CODE,MAX(POINT) POINT FROM CON_LISTCONT WHERE ID_CLASS_CODE IN ('S1','S2') GROUP BY ID_VES_VOYAGE,NO_CONTAINER,ID_CLASS_CODE) JSE ON JSE.NO_CONTAINER = C.NO_CONTAINER AND JSE.ID_VES_VOYAGE = C.ID_VES_VOYAGE AND JSE.ID_CLASS_CODE = C.ID_CLASS_CODE AND JSE.POINT = C.POINT
							$qWhere
						) C
						WHERE C.ACTIVITY = '$class_code'";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		//print_r($data);
		//die();
		
		return $data;
	}

	public function get_data_weighing_list($id_ves_voyage='', $class_code, $paging=false, $sort=false, $filters=false){
		$torSV = "SELECT FL_TONGKANG FROM VES_VOYAGE WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$rsTOR = $this->db->query($torSV);
		$rowTOR = $rsTOR->row_array();
		if($rowTOR['FL_TONGKANG'] != 'Y'){
			return 'Bukan dari tongkang kecil / small vessel...!';
		}

		$class_code_str = '';

		if ($class_code=='I'){
			$class_code_str = "'I', 'TI'";
		}
		else if ($class_code=='E'){
			$class_code_str = "'E', 'TE'";
		}

		$param = array($id_ves_voyage,$this->gtools->terminal());
		// update WEIGHT = 0 for call just container before weighing
		$query_count = "SELECT COUNT(NO_CONTAINER) TOTAL
						FROM CON_LISTCONT
						WHERE 
							WEIGHT = 0 AND
							ID_VES_VOYAGE=? AND ID_TERMINAL=? AND
							TRIM(ID_OP_STATUS) <> 'DIS' AND 
							ID_CLASS_CODE IN ($class_code_str)";
		$rs = $this->db->query($query_count, $param);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			if ($sortProperty=='STOWAGE'){
				$sortProperty = 'VS_BAY';
			}
			if ($sortProperty=='YARD_POS'){
				$sortProperty = 'YD_BLOCK_NAME';
			}
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		// update WEIGHT = 0 for call just container before weighing
		$qWhere = "WHERE WEIGHT = 0 AND ID_VES_VOYAGE=? AND TRIM(ID_OP_STATUS) <> 'DIS' AND ID_CLASS_CODE IN ($class_code_str) ";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];

				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}

				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT 
						B.*, 
						0 STATUS_EDIT
				  FROM 
						(SELECT 
								A.*, 
								ROWNUM REC_NUM
						 FROM 
								(SELECT * FROM CON_LISTCONT $qWhere AND ID_TERMINAL='".$this->gtools->terminal()."' $qSort) A) B
				  $qPaging";
			
		$rs = $this->db->query($query, $param);
		$container_list = $rs->result_array();
		for ($i=0; $i<sizeof($container_list); $i++){
			$container_list[$i]['STOWAGE'] = '';
			$container_list[$i]['YARD_POS'] = '';
			$container_list[$i]['NO_CONTAINER_OLD'] = $container_list[$i]['NO_CONTAINER'];
			$container_list[$i]['NPE'] = $container_list[$i]['NPE'];
			$container_list[$i]['WEIGHT'] = $container_list[$i]['WEIGHT']/1000;
			if ($container_list[$i]['VS_BAY']!=''){
				$container_list[$i]['STOWAGE'] = str_pad($container_list[$i]['VS_BAY'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_ROW'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_TIER'],2,'0',STR_PAD_LEFT);
			}
			if ($container_list[$i]['YD_BLOCK_NAME']!=''){
				$container_list[$i]['YARD_POS'] = $container_list[$i]['YD_BLOCK_NAME'].'-'.$container_list[$i]['YD_SLOT'].'-'.$container_list[$i]['YD_ROW'].'-'.$container_list[$i]['YD_TIER'];
			}
		}
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function insert_container_working_sequence_inbound($id_ves_voyage, $id_bay, $deck_hatch, $xml_str){
		$xml = simplexml_load_string($xml_str);
		
		$data = $xml->data;
		$sequence = $data->sequence;
		$sequence_arr = explode(",",$sequence);
		
		$flag = 1;
		$this->db->trans_start();
		
		$param = array(
			$id_ves_voyage,
			$id_bay,
			$deck_hatch,
			$this->gtools->terminal()
		);
		
		$query = "SELECT MAX(SEQUENCE) DELTA FROM CON_INBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ? AND
						ID_TERMINAL=?";
		$rs = $this->db->query($query, $param);
		$data = $rs->row_array();
		$delta = 0;
		if ($data['DELTA']!=''){
			$delta = $data['DELTA'];
		}
		
		foreach ($sequence_arr as $row){
			$detail = explode("-",$row);
			$param = array(
				$detail[0],
				$detail[1],
				$id_ves_voyage,
				$detail[2],
				$detail[3],
				$detail[4],
				$id_bay,
				$detail[5],
				$detail[6]+$delta,
				$deck_hatch,
				$this->gtools->terminal()
			);
			$query_plan = "INSERT INTO CON_INBOUND_SEQUENCE (
							   NO_CONTAINER, POINT, ID_VES_VOYAGE, 
							   BAY_, ROW_, TIER_, 
							   ID_BAY, ID_CELL, SEQUENCE, DECK_HATCH,ID_TERMINAL) 
							VALUES ( ?/* NO_CONTAINER */,
							 ?/* POINT */,
							 ?/* ID_VES_VOYAGE */,
							 ?/* BAY_ */,
							 ?/* ROW_ */,
							 ?/* TIER_ */,
							 ?/* ID_BAY */,
							 ?/* ID_CELL */,
							 ?/* SEQUENCE */,
							 ?/* DECK_HATCH */,
							 ?/* ID_TERMINAL */ )";
			$flag = ($flag && $this->db->query($query_plan, $param));
		}
		
		$this->db->trans_complete();
		return $flag;
	}
	
	public function delete_container_working_sequence_inbound($id_ves_voyage, $id_bay, $deck_hatch, $xml_str){
		$xml = simplexml_load_string($xml_str);
		
		$data = $xml->data;
		$id_cell = $data->id_cell;
		$id_cell_arr = explode(",",$id_cell);
		// print_r($id_cell_arr);
		
		$flag = 1;
		$this->db->trans_start();
		
		$param = array(
			$id_ves_voyage,
			$id_bay,
			$deck_hatch,
			'P',
			$this->gtools->terminal()
		);
		
		$query = "SELECT * FROM CON_INBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ? AND
						STATUS = ? AND ID_TERMINAL=?
					ORDER BY SEQUENCE";
		$rs = $this->db->query($query, $param);
		$data = $rs->result_array();
		
		$query = "DELETE FROM CON_INBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ? AND
						STATUS = ? AND ID_TERMINAL=?";
		$this->db->query($query, $param);
		
		$query = "SELECT MAX(SEQUENCE) DELTA FROM CON_INBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ? AND 
						STATUS = ? AND ID_TERMINAL=?";
		$rs = $this->db->query($query, $param);
		$row = $rs->row_array();
		$delta = 0;
		if ($row['DELTA']!=''){
			$delta = $row['DELTA'];
		}
		
		$sequence = 1;
		foreach ($data as $row){
			if (!in_array($row['ID_CELL'], $id_cell_arr)){
				// print $row['ID_CELL'];
				$param = array(
					$row['NO_CONTAINER'],
					$row['POINT'],
					$row['ID_VES_VOYAGE'],
					$row['BAY_'],
					$row['ROW_'],
					$row['TIER_'],
					$row['ID_BAY'],
					$row['ID_CELL'],
					$sequence+$delta,
					$row['DECK_HATCH'],
					$this->gtools->terminal()

				);
				$query_plan = "INSERT INTO CON_INBOUND_SEQUENCE (
								   NO_CONTAINER, POINT, ID_VES_VOYAGE, 
								   BAY_, ROW_, TIER_, 
								   ID_BAY, ID_CELL, SEQUENCE, DECK_HATCH, ID_TERMINAL) 
								VALUES ( ?/* NO_CONTAINER */,
								 ?/* POINT */,
								 ?/* ID_VES_VOYAGE */,
								 ?/* BAY_ */,
								 ?/* ROW_ */,
								 ?/* TIER_ */,
								 ?/* ID_BAY */,
								 ?/* ID_CELL */,
								 ?/* SEQUENCE */,
								 ?/* DECK_HATCH */,
								 ?/* ID_TERMINAL */)";
				$flag = ($flag && $this->db->query($query_plan, $param));
				$sequence += 1;
			}
		}
		
		$this->db->trans_complete();
		return $flag;
	}
	
	public function insert_container_working_sequence_outbound($id_ves_voyage, $id_bay, $deck_hatch, $xml_str){
		$xml = simplexml_load_string($xml_str);
		
		$data = $xml->data;
		$sequence = $data->sequence;
		$sequence_arr = explode(",",$sequence);
		$stack = $data->stack;
		$stack_arr = explode(",",$stack);
		$stack_info_arr = array();
//		echo '<pre>stack : ';print_r($stack);echo '</pre>';
//		echo '<pre>stack_arr : ';print_r($stack_arr);echo '</pre>';
		foreach($stack_arr as $row){
			$detail = explode("-",$row);
			$stack_info_arr[$detail[2]] = array($detail[0],$detail[1],$detail[3]);
		}
//		echo '<pre>stack_info_arr : ';print_r($stack_info_arr);echo '</pre>';
		$flag = 1;
		$msgCont = '';
		$msgContVessel = '';
		$msgContNotMatch = '';
		$msgStowage = '';
		$msg = '';
		$this->db->trans_start();
		
		$param = array(
			$id_ves_voyage,
			$id_bay,
			$deck_hatch,
			$this->gtools->terminal()
		);
		
//		$query = "SELECT MAX(SEQUENCE) DELTA 
//					FROM CON_OUTBOUND_SEQUENCE 
//					WHERE ID_VES_VOYAGE = ? 
//					AND ID_BAY = ? 
//					AND DECK_HATCH = ? 
//					AND ID_TERMINAL=?";
//		$rs = $this->db->query($query, $param);
//		echo '<pre>'. $this->db->last_query().'</pre>';
//		$data = $rs->row_array();
		$delta = 0;
//		if ($data['DELTA']!=''){
//			$delta = $data['DELTA'];
//		}
			
		//debux($sequence_arr);die;

		foreach ($sequence_arr as $row){
//		    echo '<pre>row : ';print_r($row);echo '</pre>';
			$detail = explode("-",$row);
			$stack_info = $stack_info_arr[$detail[4]];
			$no_container = count($detail) > 5 && $detail[5] == 'S' ? $detail[6] : $stack_info[0];
			$point = count($detail) > 5 && $detail[5] == 'S' ? $detail[7] : $stack_info[1];
			$param = array(
				$no_container,
				$point,
				$id_ves_voyage,
				$detail[0],
				$detail[1],
				$detail[2],
				$id_bay,
				$detail[3],
				$detail[4]+$delta,
				$deck_hatch,
				$this->gtools->terminal()
			);
//			echo '<pre>detail :';print_r($detail);echo '</pre>';
//			echo '<pre>stack info :';print_r($stack_info);echo '</pre>';
//			echo '<pre>param : ';print_r($param);echo '</pre>';
//			echo '<pre>size : ';print_r($stack_info[2]);echo '</pre>';
//			echo '<pre>bay : ';print_r($detail[0] % 2);echo '</pre>';
			if(($stack_info[2] == 40 || $stack_info[2] == 45) && $detail[0] % 2 == 0 || ($stack_info[2] == 20 || $stack_info[2] == 21)  && $detail[0] % 2 == 1 || count($detail) > 5 && $detail[5] == 'S'){
			    $queryCekCont = "SELECT COUNT(*) AS TOTAL_CONT FROM CON_OUTBOUND_SEQUENCE WHERE NO_CONTAINER = '$no_container' AND POINT = '$point'";
			    $cekCont = $this->db->query($queryCekCont)->row_array();
			    $queryCekContVessel = "SELECT COUNT(*) AS TOTAL_CONT FROM CON_LISTCONT WHERE NO_CONTAINER = '$no_container' AND POINT = '$point' AND ID_VES_VOYAGE = '$id_ves_voyage'";
			    $cekContVessel = $this->db->query($queryCekContVessel)->row_array();
//                            debux('cekContVessel : '.$cekContVessel['TOTAL_CONT']);
			    $queryCekStowage = "SELECT COUNT(*) AS TOTAL_STOWAGE FROM CON_OUTBOUND_SEQUENCE WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND BAY_ = '$detail[0]' AND ROW_ = '$detail[1]' AND TIER_ = '$detail[2]'";
			    $cekStowage = $this->db->query($queryCekStowage)->row_array();
//			    echo '<pre>TOTAL_CONT : ';print_r($cekCont['TOTAL_CONT']);echo '</pre>';
                            $isValid = true;
			    if($cekCont['TOTAL_CONT'] > 0){
				$msgCont .= $msgCont == '' ? $no_container : ','.$no_container;
                                $isValid = false;
			    }
                            if($cekContVessel['TOTAL_CONT'] == 0){
//                                echo 'masuk sini';exit;
				$msgContVessel .= $msgContVessel == '' ? $no_container : ','.$no_container;
                                $isValid = false;
			    }
                            if($cekStowage['TOTAL_STOWAGE'] > 0){
				$msgStowage .= $msgStowage == '' ? $detail[0].$detail[1].$detail[2] : ','.$detail[0].$detail[1].$detail[2] ;
                                $isValid = false;
			    }
                            
                            if($isValid){

			    
                                $bay = $detail[0];
                                if($bay % 2 == 0){
                                    $bays = "('".($bay-1)."','".$bay."','".($bay+1)."')";
                                    //debux($bays);die;
                                }else{
                                    $bays = "('$bay')";
                                }

                                $queryCekStowage = "SELECT COUNT(*) AS TOTAL_STOWAGE 
                                                                    FROM CON_OUTBOUND_SEQUENCE 
                                                                    WHERE ID_VES_VOYAGE = '$id_ves_voyage' 
                                                                    AND BAY_ IN $bays 
                                                                    AND ROW_ = '$detail[1]' 
                                                                    AND TIER_ = '$detail[2]'";
                                $cekStowage = $this->db->query($queryCekStowage)->row_array();
                                //debux($cekStowage);die;
                                if($cekStowage['TOTAL_STOWAGE'] > 0){
                                    $msgStowage .= $msgStowage == '' ? $detail[0].$detail[1].$detail[2] : ','.$detail[0].$detail[1].$detail[2];
                                }else{
                                    $query_plan = "INSERT INTO CON_OUTBOUND_SEQUENCE (
                                                                       NO_CONTAINER, POINT, ID_VES_VOYAGE, 
                                                                       BAY_, ROW_, TIER_, 
                                                                       ID_BAY, ID_CELL, SEQUENCE, DECK_HATCH, ID_TERMINAL) 
                                                                    VALUES ( ?/* NO_CONTAINER */,
                                                                     ?/* POINT */,
                                                                     ?/* ID_VES_VOYAGE */,
                                                                     ?/* BAY_ */,
                                                                     ?/* ROW_ */,
                                                                     ?/* TIER_ */,
                                                                     ?/* ID_BAY */,
                                                                     ?/* ID_CELL */,
                                                                     ?/* SEQUENCE */,
                                                                     ?/* DECK_HATCH */,
                                                                     ?/* ID_TERMINAL */ )";

									$flag = ($flag && $this->db->query($query_plan, $param));
                                }
				
			    }
                        }else if(($stack_info[2] == 40 || $stack_info[2] == 45) && $detail[0] % 2 == 1 ){
                            $msgContNotMatch .= $msgContNotMatch == '' ? $no_container : ','.$no_container;
                        }else if(($stack_info[2] == 20 || $stack_info[2] == 21) && $detail[0] % 2 == 0 ){
                            $msgContNotMatch .= $msgContNotMatch == '' ? $no_container : ','.$no_container;
                        }
		}
		$this->set_outbound_sequence_per_bay_deck_hatch($id_ves_voyage,$id_bay,$deck_hatch);
		if($msgCont != ''){
		    $msg .= 'Container '.$msgCont.' already set sequence for loading. Please Refresh Single Stack View<br>';
		}
		if($msgContVessel != ''){
		    $msg .= 'Container '.$msgContVessel.' set sequence in wrong vessel. Please check vessel to be set sequence.<br>';
		}
		if($msgStowage != ''){
		    $msg .= 'Stowage '.$msgStowage.' already set for some container. Please Refresh outbound view<br>';
		}
		if($msgContNotMatch != ''){
		    $msg .= 'No Container '.$msgContNotMatch.", size and stowage not match.<br>";
		}
//		exit;
		$this->db->trans_complete();
		return array($flag,$msg);
	}
	
	public function delete_container_working_sequence_outbound($id_ves_voyage, $id_bay, $deck_hatch, $xml_str){
		$xml 			= simplexml_load_string($xml_str);
		
		$data 			= $xml->data;
		$id_cell 		= $data->id_cell;
		$id_cell_arr 	= explode(",",$id_cell);
		// print_r($id_cell_arr);
		
		$flag = 1;
		$this->db->trans_start();
		
		$param = array(
			$id_ves_voyage,
			$id_bay,
			$deck_hatch,
			'P',
			$this->gtools->terminal()
		);
		
		$query = "SELECT * FROM CON_OUTBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ? AND
						STATUS = ? AND ID_TERMINAL=?
					ORDER BY SEQUENCE";
		$rs = $this->db->query($query, $param);
		$data = $rs->result_array();
		
		$qryjq = "SELECT * FROM JOB_QUAY_MANAGER WHERE ID_VES_VOYAGE = '$id_ves_voyage'";
		$rsjq = $this->db->query($qryjq);
		$datajq = $rsjq->result_array();
		
		$query = "DELETE FROM CON_OUTBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ? AND
						STATUS = ? AND ID_TERMINAL=?";
		$this->db->query($query, $param);
		
		$query = "SELECT MAX(SEQUENCE) DELTA FROM CON_OUTBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ? AND
						STATUS = ? AND ID_TERMINAL=?";
		$rs = $this->db->query($query, $param);
		$row = $rs->row_array();
		$delta = 0;
		if ($row['DELTA']!=''){
			$delta = $row['DELTA'];
		}
		
		$sequence = 1;
		$message = '';
		foreach ($data as $row){
		    $isJobExist = false;
		    foreach ($datajq as $jq){
			if($jq['NO_CONTAINER'] == $row['NO_CONTAINER'] && $jq['POINT'] == $row['POINT']){
			    $message .= $message != '' ? ','.$row['NO_CONTAINER'] : $row['NO_CONTAINER'];
			    $isJobExist = true;
			}
		    }
			if (!in_array($row['ID_CELL'], $id_cell_arr) || in_array($row['ID_CELL'], $id_cell_arr) && $isJobExist){
				// print $row['ID_CELL'];
				$param = array(
					$row['NO_CONTAINER'],
					$row['POINT'],
					$row['ID_VES_VOYAGE'],
					$row['BAY_'],
					$row['ROW_'],
					$row['TIER_'],
					$row['ID_BAY'],
					$row['ID_CELL'],
					$sequence+$delta,
					$row['DECK_HATCH'],
					$this->gtools->terminal()
				);
				$query_plan = "INSERT INTO CON_OUTBOUND_SEQUENCE (
								   NO_CONTAINER, POINT, ID_VES_VOYAGE, 
								   BAY_, ROW_, TIER_, 
								   ID_BAY, ID_CELL, SEQUENCE, DECK_HATCH, ID_TERMINAL) 
								VALUES ( ?/* NO_CONTAINER */,
								 ?/* POINT */,
								 ?/* ID_VES_VOYAGE */,
								 ?/* BAY_ */,
								 ?/* ROW_ */,
								 ?/* TIER_ */,
								 ?/* ID_BAY */,
								 ?/* ID_CELL */,
								 ?/* SEQUENCE */,
								 ?/* DECK_HATCH */,
								 ?/* ID_TERMINAL */)";
				$flag = ($flag && $this->db->query($query_plan, $param));
				$sequence += 1;
			}
		}
		$message .= $message != '' ? ' already has job for loading.' : '';
		$this->db->trans_complete();
		return array($flag,$message);
	}
	
	public function get_max_container_point($no_container){
		$param = array($no_container,$this->gtools->terminal());
		$query 	= "SELECT MAX(POINT) POINT
					FROM CON_LISTCONT
					WHERE NO_CONTAINER = ? AND ID_TERMINAL=?";
		$rs = $this->db->query($query, $param);
		$row = $rs->row_array();
		if ($row['POINT']!=''){
			return $row['POINT']+1;
		}else{
			return 1;
		}
	}
	
	public function check_container_number($no_container, $id_ves_voyage){
		$param = array($no_container, $id_ves_voyage);
		$query 	= "SELECT MAX(POINT) POINT
					FROM CON_LISTCONT
					WHERE NO_CONTAINER = ? AND ID_VES_VOYAGE = ?";
		$rs = $this->db->query($query, $param);
		$row = $rs->row_array();
		if ($row['POINT']!=''){
			return false;
		}else{
			return true;
		}
	}
	
	public function check_valid_field($field){
		if ($field && $field!='' && $field!='-'){
			return true;
		}else{
			return false;
		}
	}

	public function getFlTongkang($id_ves_voyage){
		$query 		= "SELECT FL_TONGKANG FROM VES_VOYAGE WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs 		= $this->db->query($query);
		$data 		= $rs->row_array();

		return $data['FL_TONGKANG'];
	}
	
	public function check_valid_container_detail($data, $id_ves_voyage){
		if($this->getFlTongkang($id_ves_voyage)=="Y"){
			if (
				$this->check_valid_field($data['NO_CONTAINER']) && 
				$this->check_valid_field($data['ID_ISO_CODE']) && 
				$this->check_valid_field($data['ID_OPERATOR']) && 
//				$this->check_valid_field($data['CONT_STATUS']) && 
				$this->check_valid_field($data['ID_POL']) && 
				$this->check_valid_field($data['ID_POD']) && 
				$this->check_valid_field($data['CONT_SIZE']) && 
				$this->check_valid_field($data['CONT_TYPE']) && 
				$this->check_valid_field($data['CONT_HEIGHT'])){
				return true;
			}else{
				return false;
			}
		}
		else{
			if (
				$this->check_valid_field($data['NO_CONTAINER']) && 
				$this->check_valid_field($data['ID_ISO_CODE']) && 
				$this->check_valid_field($data['ID_OPERATOR']) && 
//				$this->check_valid_field($data['CONT_STATUS']) && 
				$this->check_valid_field($data['ID_POL']) && 
				$this->check_valid_field($data['ID_POD']) && 
				$this->check_valid_field($data['CONT_SIZE']) && 
				$this->check_valid_field($data['CONT_TYPE']) && 
				$this->check_valid_field($data['CONT_HEIGHT']) && 
				$this->check_valid_field($data['WEIGHT'])){
				return true;
			}else{
				return false;
			}
		}
	}
	
	public function check_valid_container_detail_iso($iso_code, $size, $type){
		$param = array($iso_code);
		$query 	= "SELECT SIZE_, TYPE_
					FROM M_ISO_CODE
					WHERE trim(ISO_CODE) = ?";
		$rs = $this->db->query($query, $param);
		$row = $rs->row_array();
		
		if (trim($row['SIZE_'])== trim($size) && trim($row['TYPE_'])== trim($type)){
			return true;
		}else{
			return false;
		}
	}
	
	public function check_valid_container_detail_change($data){
		$flag = true;
		$fields = array('CONT_STATUS','CONT_SIZE','CONT_TYPE','CONT_HEIGHT','WEIGHT');
		foreach ($data as $key=>$value){
			if (in_array($key, $fields)){
				$flag = $flag && $this->check_valid_field($data[$key]);
				if (!$flag){
					break;
				}
			}
		}
		
		return $flag;
	}
	
	public function insert_listcont_detail($id_ves_voyage, $data, $id_user){
//	    debux($data);exit;
	    $cek_validation = $this->vessel_detail_validation($id_ves_voyage,$data);
	    if ($cek_validation == 1){
		
		$q_field = "";
		$q_value = "";
		foreach($data as $key=>$value){
		    if($key!="REC_NUM" && $key!="STATUS_EDIT" && $key!="NO_CONTAINER_OLD" && $key != 'YARD_POS' 
			    && $key != 'COMPLETE_DATE_BM' && $key != 'STATUS_PLACEMENT' && $key != 'COMPLETE_DATE_PLACEMENT'
			    && $key != 'YC_PLAN' && $key != 'YC_REAL' && $key != 'TL_FLAG' && $key != 'NO_REQUEST'
			    && $key != 'STATUS_BM' && $key != 'QC_PLAN' && $key != 'CONFIRM_DATE_'){
			if ($q_field!=""){
				$q_field .= ",";
			}
			if ($q_value!=""){
				$q_value .= ",";
			}
			if($key=="ID_CLASS_CODE"){
				$kv = $value;
			}
			if ($key=='STOWAGE'){
				$q_field .= "VS_BAY,VS_ROW,VS_TIER";
				$vs_bay = (int) substr($value,0,strlen($value)-4);
				$vs_row = (int) substr($value,-4,2);
				$vs_tier = (int) substr($value,-2,2);
				
				$q_value .= "'" . $vs_bay . "',"; // vs bay
				$q_value .= "'" . $vs_row . "',"; // vs row
				$q_value .= "'" . $vs_tier . "'"; // vs tier
					
			}elseif($key=="STOWAGE_TO"){
				$vs_bay = '';
				$vs_row = '';
				$vs_tier = '';
				if($value != '' && strlen($value) == 6){
				    $vs_bay = (int) substr($value,0,strlen($value)-4);
				    $vs_row = (int) substr($value,-4,2);
				    $vs_tier = (int) substr($value,-2,2);
				}
				
				$q_field .= "VS_BAY_TO,VS_ROW_TO,VS_TIER_TO";
				$q_value .= "'" . $vs_bay . "',"; // vs bay
				$q_value .= "'" . $vs_row . "',"; // vs row
				$q_value .= "'" . $vs_tier . "'"; // vs tier
					
			}else {
				if ($key=='WEIGHT'){
				    $value = $value*1000;
				}
				if ($key=='COMPLETE_DATE_PLACEMENT'){
				    $value = date('Y-m-d',strtotime($value));
				}
				
				$q_field .= $key;
				$q_value .= "'".$value."'";
				
			}
			//print_r($key);
		    }
		}

		if($data['ID_CLASS_CODE']=='E')
		{
			$bp = "REC";
			$bpn = "Booking Outbound";
		}
		else
		{
			$bp = "BPL";
			$bpn = "Booking Inbound";
		}

		$FL_TONGKANG = $this->getFlTongkang($id_ves_voyage);

		$query 	= "INSERT INTO CON_LISTCONT
					($q_field,ID_OP_STATUS,OP_STATUS_DESC,ID_USER_BAPLIE,FL_TONGKANG, ID_TERMINAL)
					VALUES 
					($q_value,'$bp','$bpn','$id_user','$FL_TONGKANG',".$this->gtools->terminal().")";
//		echo $query;
		$rs = $this->db->query($query);
		
		if ($rs){
			//update history
			/*$no_container = $data['NO_CONTAINER'];
			$sql = "update CON_LISTCONT_HIST SET VS_BAY='$vs_bay_to', VS_ROW='$vs_row_to', VS_TIER='$vs_tier_to' WHERE NO_CONTAINER='$no_container' AND POINT='$point' AND ID_VES_VOYAGE='$id_ves_voyage'";
			$this->db->query($sql);*/
			return 1;
		}else{
			return "Save failed.";
		}
	    }else{
		return $cek_validation;
	    }
	}
	
	public function update_listcont_detail($no_container, $point, $data, $id_ves_voyage=''){
		$cek_validation = $this->vessel_detail_validation($id_ves_voyage,$data,'E');
		if ($cek_validation == 1){
		    $query1 = "SELECT ID_OP_STATUS 
				       FROM CON_LISTCONT 
				       WHERE NO_CONTAINER = '".$no_container."'
				       AND POINT = '".$point."' AND ID_TERMINAL='".$this->gtools->terminal()."'";
		    $ress  = $this->db->query($query1)->row();
		    $id_op_status = $ress->ID_OP_STATUS;
		    
		    $q_set = "";
		    foreach($data as $key=>$value){
			if($key!="REC_NUM" && $key!="STATUS_EDIT" && $key!="NO_CONTAINER_OLD" && $key != 'YARD_POS' 
			    && $key != 'COMPLETE_DATE_BM' && $key != 'STATUS_PLACEMENT' && $key != 'COMPLETE_DATE_PLACEMENT'
			    && $key != 'YC_PLAN' && $key != 'YC_REAL' && $key != 'TL_FLAG' && $key != 'NO_REQUEST'
			    && $key != 'STATUS_BM' && $key != 'QC_PLAN' && $key != 'CONFIRM_DATE_'){
			    if ($q_set!=""){
				    $q_set .= ",";
			    }
			    if ($key=='STOWAGE'){
				    $vs_bay = (int) substr($value,0,strlen($value)-4);
				    $vs_row = (int) substr($value,-4,2);
				    $vs_tier = (int) substr($value,-2,2);

				    $q_set .= "VS_BAY = '".$vs_bay."',";
				    $q_set .= "VS_ROW = '".$vs_row."',";
				    $q_set .= "VS_TIER = '".$vs_tier."'";
				    
			    }elseif($key=="STOWAGE_TO"){	
				    $vs_bay = '';
				    $vs_row = '';
				    $vs_tier = '';
				    if($value != '' && strlen($value) == 6){
					$vs_bay = (int) substr($value,0,strlen($value)-4);
					$vs_row = (int) substr($value,-4,2);
					$vs_tier = (int) substr($value,-2,2);
				    }
				    $q_set .= "VS_BAY_TO = '".$vs_bay."',";
				    $q_set .= "VS_ROW_TO = '".$vs_row."',";
				    $q_set .= "VS_TIER_TO = '".$vs_tier."'";
			    }else 
			    {
				    if ($key=='WEIGHT')
				    {
					    $value = $value*1000;
				    }

				    if($key == 'NO_CONTAINER_UPDATE_OUTBOUND_LIST')
				    {
				    	$q_set .= "NO_CONTAINER = '".$value."'";
				    }
				    else{
				    	$q_set .= $key." = '".$value."'";

				    }


				    if($key == 'ID_SPEC_HAND' && $id_op_status !='YGY')
				    {
						return "Handling  belum stackings";
				    }
			    }
			}
		    }
//		echo '<pre>$q_set : '.$q_set.'</pre>';
//		exit;
		
		    if($id_op_status=='YSY'){
			    return 'Container Already Discharge';
		    }

		    $param = array($no_container, $point, $this->gtools->terminal());
		    $query 	= "UPDATE CON_LISTCONT
					    SET $q_set
					    WHERE NO_CONTAINER = ? AND POINT = ? AND ID_TERMINAL = ?";


		    $rs 	= $this->db->query($query, $param);
//		    echo '<pre>query : '.$this->db->last_query().'</pre>';exit;

		    if ($rs){
			    return 1; //sukses
		    }else{
			    return "Update Failed. Please contact your administrator."; //gagal
		    }
		}else{
		    return $cek_validation;
		}
	}
	
	public function vessel_detail_validation($id_ves_voyage, $data, $act = 'A'){
	    if(is_array($data) && count($data) > 0){
		if(isset($id_ves_voyage) && $id_ves_voyage == ''){
		    return "Vessel voyage can't be empty";
		}

		if(isset($data['NO_CONTAINER_UPDATE_OUTBOUND_LIST']))
		{
			$NEW_PARAMETER_NO_CONTAINER = $data['NO_CONTAINER_UPDATE_OUTBOUND_LIST'];
			// lanjut ke line 2157
		}
		else
		{
			$NEW_PARAMETER_NO_CONTAINER = $data['NO_CONTAINER'];
		}

		$qry_old_cont = "SELECT * FROM CON_LISTCONT WHERE NO_CONTAINER = '".$NEW_PARAMETER_NO_CONTAINER."' AND ID_VES_VOYAGE = '$id_ves_voyage'";
		$data_old_cont = $this->db->query($qry_old_cont)->row_array();
		$old_stowage = str_pad($data_old_cont['VS_BAY'],2,"0",STR_PAD_LEFT).str_pad($data_old_cont['VS_ROW'],2,"0",STR_PAD_LEFT).str_pad($data_old_cont['VS_TIER'],2,"0",STR_PAD_LEFT);
		$old_stowage_to = str_pad($data_old_cont['VS_BAY_TO'],2,"0",STR_PAD_LEFT).str_pad($data_old_cont['VS_ROW_TO'],2,"0",STR_PAD_LEFT).str_pad($data_old_cont['VS_TIER_TO'],2,"0",STR_PAD_LEFT);
		if(isset($data['NO_CONTAINER']) && $data['NO_CONTAINER'] == ''){
		    return "No Container can't be empty";
		}
		
		if(isset($data['CONT_SIZE']) && $data['CONT_SIZE'] == ''){
		    return "Container size can't be empty";
		}
		
		if(isset($data['ID_ISO_CODE']) && $data['ID_ISO_CODE'] == ''){
		    return "Iso Code can't be empty";
		}
		
		if(isset($data['ID_OPERATOR']) && $data['ID_OPERATOR'] == ''){
		    return "Operator can't be empty";
		}
		
		if(isset($data['CONT_STATUS']) && $data['CONT_STATUS'] == ''){
		    return "Full Empty can't be empty";
		}
		
		if(isset($data['ID_POL']) && $data['ID_POL'] == ''){
		    return "POL can't be empty";
		}
		
		if(isset($data['ID_POD']) && $data['ID_POD'] == ''){
		    return "POD can't be empty";
		}
		
		if(isset($data['CONT_TYPE']) && $data['CONT_TYPE'] == ''){
		    return "Container Type can't be empty";
		}
		
		if(isset($data['CONT_HEIGHT']) && $data['CONT_HEIGHT'] == ''){
		    return "Container height can't be empty";
		}
		
		if(isset($data['WEIGHT']) && $data['WEIGHT'] == ''){
		    return "Container weight can't be empty";
		}
		
		if(!isset($data['NO_CONTAINER_UPDATE_OUTBOUND_LIST']))
		{
			if (( ($data['NO_CONTAINER_OLD'] != '' && $data['NO_CONTAINER_OLD'] != $data['NO_CONTAINER']) || 
				$data['NO_CONTAINER_OLD'] == '') && 
				!$this->check_container_number($data['NO_CONTAINER'], $id_ves_voyage) )
			{
			    return "Container Number already exist";
			}
		}
		
		if (!($this->check_valid_container_detail_iso($data['ID_ISO_CODE'], $data['CONT_SIZE'], $data['CONT_TYPE']))) {
		    return 'Iso Code not match with Size and Type';
		}
		
		$stowage_to = isset($data['STOWAGE_TO']) && $data['STOWAGE_TO'] != '' ? $data['STOWAGE_TO'] : '';
		
		if(isset($data['STOWAGE']) && $data['STOWAGE'] != '' && $data['STOWAGE'] != $old_stowage){
		    $stowage = $data['STOWAGE'];
		    
		    if($data['ID_CLASS_CODE'] == 'S1' && $stowage_to != '' && $stowage == $stowage_to){
			return "Stowage To can't be set to same location with Stowage";
		    }
		    
		    /* check available stowage location */
		    $vs_bay = (int) substr($stowage,0,strlen($stowage)-4);
		    $vs_row = (int) substr($stowage,-4,2);
		    $vs_tier = (int) substr($stowage,-2,2);

		    if($data['CONT_SIZE'] >= 40 && $vs_bay%2==1){
			return 'Size and Stowage mismatch';
		    }

		    if(($data['CONT_SIZE'] == 20 || $data['CONT_SIZE'] == 21) && $vs_bay%2==0){
			 return 'Size and Stowage mismatch';
		    }

		    if($vs_tier >= 0 && $vs_tier <= 8){
			    $sql_stow = "B.BELOW = 'AKTIF'";
		    }else{
			    $sql_stow = "B.ABOVE = 'AKTIF'";
		    }

		    $query_cekslot = "SELECT COUNT(1) JUMLAH
				    FROM VES_VOYAGE A
				    INNER JOIN M_VESSEL_PROFILE_BAY B 
				    ON A.ID_VESSEL = B.ID_VESSEL
				    INNER JOIN M_VESSEL_PROFILE_CELL C 
				    ON B.ID_BAY = C.ID_BAY
				    WHERE A.ID_VES_VOYAGE ='".$id_ves_voyage."'
				    AND B.BAY='".$vs_bay."'
				    AND C.ROW_='".$vs_row."'
				    AND C.TIER_='".$vs_tier."'
				    AND ($sql_stow
				    AND A.ID_TERMINAL='".$this->gtools->terminal()."')";
//				    echo $query_cekslot;exit;
		    $rs_cekslot = $this->db->query($query_cekslot);
		    $row_cekslot = $rs_cekslot->row_array();
		    // $avail_bay = $row_cekslot['ROW_'];
		    // $avail_row = $row_cekslot['TIER_'];
		    // $avail_tier = $row_cekslot['TIER_'];

		    if(($row_cekslot['JUMLAH']+0)== 0){
			    return "Stowage doesn't exist. ";
		    }

		    $query_cekslot = "SELECT COUNT(1) JUMLAH
				    FROM VES_VOYAGE A
				    INNER JOIN M_VESSEL_PROFILE_BAY B 
				    ON A.ID_VESSEL = B.ID_VESSEL
				    INNER JOIN M_VESSEL_PROFILE_CELL C 
				    ON B.ID_BAY = C.ID_BAY
				    WHERE A.ID_VES_VOYAGE ='".$id_ves_voyage."'
				    AND B.BAY='".$vs_bay."'
				    AND C.ROW_='".$vs_row."'
				    AND C.TIER_='".$vs_tier."'
				    AND $sql_stow
				    AND C.STATUS_STACK = 'A' AND A.ID_TERMINAL='".$this->gtools->terminal()."'
				    ";
//				    echo $query_cekslot;exit;
				    $rs_cekslot = $this->db->query($query_cekslot);
				    $row_cekslot = $rs_cekslot->row_array();

		    if($row_cekslot['JUMLAH']== 0){
			    return "Slot not available.";
		    }

		    //=== 2.Cek slot sudah dipakai belum
		    if(($data['CONT_SIZE']=='40')||($data['CONT_SIZE']=='45'))
		    {
			    $query_slot = "SELECT COUNT(*) JUMLAH_SLOT FROM CON_LISTCONT 
			    WHERE ID_VES_VOYAGE='".$id_ves_voyage."'
			    AND VS_BAY IN ('".($vs_bay-1)."','".$vs_bay."','".($vs_bay+1)."')
			    AND VS_ROW='".$vs_row."'
			    AND VS_TIER='".$vs_tier."'
			    AND ID_TERMINAL='".$this->gtools->terminal()."'
			    AND (ID_CLASS_CODE IN ('I','TC') OR ID_CLASS_CODE IN ('TI','S1','S2') AND ACTIVE = 'Y')
			    AND ID_OP_STATUS <> 'DIS'
			    ";								
		    }
		    else
		    {
			    $query_slot = "SELECT COUNT(*) JUMLAH_SLOT FROM CON_LISTCONT 
			    WHERE ID_VES_VOYAGE='".$id_ves_voyage."'
			    -- AND VS_BAY='".$vs_bay."'
			    AND VS_BAY IN ('".$vs_bay."','".($vs_bay+1)."')
			    AND VS_ROW='".$vs_row."'
			    AND VS_TIER='".$vs_tier."'
			    AND ID_TERMINAL='".$this->gtools->terminal()."'
			    AND (ID_CLASS_CODE IN ('I','TC') OR ID_CLASS_CODE IN ('TI','S1','S2') AND ACTIVE = 'Y')
			    AND ID_OP_STATUS <> 'DIS'
			    ";
		    }
		    //echo '<pre>'.$query_slot.'</pre>';
		    $rs_slot = $this->db->query($query_slot);
		    $row_slot = $rs_slot->row_array();
		    //debux($row_slot);
		    if(($row_slot['JUMLAH_SLOT']+0)>0){
			return 'Stowage already occupied. <br/> ';
		    }

		}else{
		    if($act == 'A')
		    return "Stowage can't be empty";
		}
		
		if(($data['ID_CLASS_CODE'] == 'S1' || $data['ID_CLASS_CODE'] == 'S2')){
		    if($stowage_to != '' && $stowage_to != $old_stowage_to){
			$vs_bay_to = (int) substr($stowage_to,0,strlen($stowage)-4);
			$vs_row_to = (int) substr($stowage_to,-4,2);
			$vs_tier_to = (int) substr($stowage_to,-2,2);
			if($data['CONT_SIZE'] >= 40 && $vs_bay_to%2==1){
			    return 'Size and Stowage mismatch';
			}

			if(($data['CONT_SIZE'] == 20 || $data['CONT_SIZE'] == 21) && $vs_bay_to%2==0){
			     return 'Size and Stowage mismatch';
			}

			if($vs_tier_to >= 0 && $vs_tier_to <= 8){
				$sql_stow = "B.BELOW = 'AKTIF'";
			}else{
				$sql_stow = "B.ABOVE = 'AKTIF'";
			}

			$query_cekslot = "SELECT COUNT(1) JUMLAH
					FROM VES_VOYAGE A
					INNER JOIN M_VESSEL_PROFILE_BAY B 
					ON A.ID_VESSEL = B.ID_VESSEL
					INNER JOIN M_VESSEL_PROFILE_CELL C 
					ON B.ID_BAY = C.ID_BAY
					WHERE A.ID_VES_VOYAGE ='".$id_ves_voyage."'
					AND B.BAY='".$vs_bay_to."'
					AND C.ROW_='".$vs_row_to."'
					AND C.TIER_='".$vs_tier_to."'
					AND ($sql_stow
					AND A.ID_TERMINAL='".$this->gtools->terminal()."')";
    //				    echo $query_cekslot;exit;
			$rs_cekslot = $this->db->query($query_cekslot);
			$row_cekslot = $rs_cekslot->row_array();
			// $avail_bay = $row_cekslot['ROW_'];
			// $avail_row = $row_cekslot['TIER_'];
			// $avail_tier = $row_cekslot['TIER_'];

			if(($row_cekslot['JUMLAH']+0)== 0){
				return "Stowage doesn't exist. ";
			}

			$query_cekslot = "SELECT COUNT(1) JUMLAH
					FROM VES_VOYAGE A
					INNER JOIN M_VESSEL_PROFILE_BAY B 
					ON A.ID_VESSEL = B.ID_VESSEL
					INNER JOIN M_VESSEL_PROFILE_CELL C 
					ON B.ID_BAY = C.ID_BAY
					WHERE A.ID_VES_VOYAGE ='".$id_ves_voyage."'
					AND B.BAY='".$vs_bay_to."'
					AND C.ROW_='".$vs_row_to."'
					AND C.TIER_='".$vs_tier_to."'
					AND $sql_stow
					AND C.STATUS_STACK = 'A' AND A.ID_TERMINAL='".$this->gtools->terminal()."'
					";
    //				    echo $query_cekslot;exit;
					$rs_cekslot = $this->db->query($query_cekslot);
					$row_cekslot = $rs_cekslot->row_array();

			if($row_cekslot['JUMLAH']== 0){
				return "Slot not available.";
			}
			if(($data['CONT_SIZE']=='40')||($data['CONT_SIZE']=='45'))
			{
				$query_slot_to = "SELECT COUNT(*) JUMLAH_SLOT FROM CON_LISTCONT C
				LEFT JOIN CON_OUTBOUND_SEQUENCE CO
				ON C.NO_CONTAINER = CO.NO_CONTAINER AND C.POINT = CO.POINT
				WHERE C.ID_VES_VOYAGE='".$id_ves_voyage."'
				AND (CO.BAY_ IN ('".($vs_bay_to-1)."','".$vs_bay_to."','".($vs_bay_to+1)."') OR C.VS_BAY_TO IN ('".($vs_bay_to-1)."','".$vs_bay_to."','".($vs_bay_to+1)."'))
				AND (CO.ROW_ ='".$vs_row_to."' OR C.VS_ROW_TO ='".$vs_row_to."')
				AND (CO.TIER_ ='".$vs_tier_to."' OR C.VS_TIER_TO ='".$vs_tier_to."')
				AND C.ID_TERMINAL ='".$this->gtools->terminal()."'
				AND C.ID_OP_STATUS <> 'DIS'
				";								
			}
			else
			{
				$query_slot_to = "SELECT COUNT(*) JUMLAH_SLOT FROM CON_LISTCONT C
				LEFT JOIN CON_OUTBOUND_SEQUENCE CO
				ON C.NO_CONTAINER = CO.NO_CONTAINER AND C.POINT = CO.POINT
				WHERE C.ID_VES_VOYAGE='".$id_ves_voyage."'
				AND (CO.BAY_ IN ('".$vs_bay_to."','".($vs_bay_to+1)."') OR C.VS_BAY_TO IN ('".$vs_bay_to."','".($vs_bay_to+1)."'))
				AND (CO.ROW_ ='".$vs_row_to."' OR C.VS_ROW_TO ='".$vs_row_to."')
				AND (CO.TIER_ ='".$vs_tier_to."' OR C.VS_TIER_TO ='".$vs_tier_to."')
				AND C.ID_TERMINAL='".$this->gtools->terminal()."'
				AND C.ID_OP_STATUS <> 'DIS'
				";
			}
//		    echo '<pre>'.$query_slot_to.'</pre>';
			$rs_slot_to = $this->db->query($query_slot_to);
			$row_slot_to = $rs_slot_to->row_array();
		    //debux($row_slot_to);
			if(($row_slot_to['JUMLAH_SLOT']+0)>0){
			    return 'Stowage already occupied or there is container shifting already set to same location. <br/> ';
			}
		    }else if($stowage_to == '' && $old_stowage_to == ''){
			return "Stowage To can't be empty for Shifting";
		    }
		}
		if(isset($data['WEIGHT'])){
		    if(is_numeric($data['WEIGHT'])){
			if ((intval($data['WEIGHT'])) <= 0) {
			    return 'Berat harus lebih besar dari 0. <br/> ';
			}
		    } else {
			return 'Berat Container harus merupakan angka. <br/> ';
		    }
		}

		if(!isset($data['NO_CONTAINER_UPDATE_OUTBOUND_LIST']))
		{
			if(!isset($data['ID_COMMODITY']) || isset($data['ID_COMMODITY']) && $data['ID_COMMODITY'] == '' ){
			    return 'Commodity tidak boleh kosong.';
			}
		}

		return 1;
	    }
	    
	}
	public function delete_listcont_detail($no_container, $point){
		$param = array($no_container, $point, $this->gtools->terminal());
		$query 	= "SELECT ID_OP_STATUS FROM CON_LISTCONT
					WHERE NO_CONTAINER = ? AND POINT = ? AND ID_TERMINAL=?";
		$rs 	= $this->db->query($query, $param);
		$row 	= $rs->row_array();
		
		$flag = false;
		if ($row['ID_OP_STATUS']=='BPL' || $row['ID_OP_STATUS']=='ROB'){
			$query 	= "DELETE FROM CON_LISTCONT
						WHERE NO_CONTAINER = ? AND POINT = ? AND ID_TERMINAL = ?";
			$this->db->query($query, $param);
			
			$flag 	= true;
		}
		
		if ($flag){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function insert_baplie_import($POST, $FILES, $id_user){
		$flag = true;
		$msg = "";
		$uploadtype = $POST['typefile'];   /* File Type Upload */
		if ($FILES[file][size] > 0) {
			$type = substr($FILES[file][name],strrpos($FILES[file][name],'.')+1);
			if (strtolower($type)=='csv')
			{
				$file = $FILES[file][tmp_name];
				$handle = fopen($file,"r");
				$id_ves_voyage = $POST['ID_VES_VOYAGE'];
				$modus = $POST['method'];
				
				$param = array($id_ves_voyage, $this->gtools->terminal());
				if ($modus == 'overwrite') {
					$query = "DELETE FROM CON_LISTCONT
							WHERE ID_VES_VOYAGE = ? AND ID_TERMINAL=? AND ((ID_CLASS_CODE IN ('I', 'TI') AND ID_OP_STATUS='BPL') OR ID_CLASS_CODE = 'TC')
							AND NO_REQUEST IS NULL ";
					$this->db->query($query, $param);
				}
				
				$i = 0;
				$j = 0;
				$jml_error = 0;
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//					echo '<pre>data '.$i.' : ';print_r($data);echo '</pre>';
					try {
						$param = array();
						$tc_nonactive = false;
						
						//print_r($i);die;
						if($i>0) {
							$param['NO_CONTAINER'] = str_replace(' ', '',$data[5]); // no container
							$param['POINT'] = $this->get_max_container_point($param['NO_CONTAINER']); // point
							$param['ID_VES_VOYAGE'] = $id_ves_voyage; // id ves voyage
							$param['UNNO'] = $data[27]; // unno
							$param['IMDG'] = $data[26]; // imdg
							$param['ID_ISO_CODE'] = $data[8]; // iso code							
							// id class code
							if ($data[0]==$this->config->item('SITE_PORT_CODE')){
								// transhipment by request via planner
//								if ($data[0]!=$data[15] && $data[15]!='')
//								{
									// $param[] = 'TI'; 
									$param['ID_CLASS_CODE'] = 'I';
//								}else{
//									$param['ID_CLASS_CODE'] = 'I';
//								}
								$param['ID_OP_STATUS'] = 'BPL'; // id op status
								$param['OP_STATUS_DESC'] = 'Booking Inbound'; // op status desc
							}else{
								$tc_nonactive = true;
								$param['ID_CLASS_CODE'] = 'TC';
								$param['ID_OP_STATUS'] = 'ROB';  // id op status, tanya kenapa harus kosong?
								$param['OP_STATUS_DESC'] = 'Remain On Board'; // op status desc
							}
							
							$param['STOWAGE'] = $data[4]; //stowage ambil dari kolom slot
							$param['CONT_SIZE'] = $data[6]; // cont size
							// cont type
							$query = "SELECT TYPE_,SIZE_ FROM M_ISO_CODE WHERE ISO_CODE='".$data[8]."'";
							$rs = $this->db->query($query);
							$row = $rs->row_array();
							$param['CONT_TYPE'] = $temp_type = $row['TYPE_'];
								
							if ((substr(strtoupper(trim($data[10])),0,1)=='M') || (substr(strtoupper(trim($data[10])),0,1)=='E')){
								$param['CONT_STATUS'] = $temp_status = 'MTY';
							} else if(substr(strtoupper(trim($data[10])),0,1)=='F'){
								$param['CONT_STATUS'] = $temp_status = 'FCL';
							} else {
								$jml_error = $jml_error + 1;
								$error = 'Status Invalid. <br/> ';
								throw new Exception($error);
								
							}
							
							//=======Edit by mustadio_gun
							//=======Date : 05/07/2017
							
							$data4_length = strlen($data[4]);
							
							if($data4_length == 5)
							{
								$vs_bay = substr($data[4],0,1);
								$vs_row = substr($data[4],1,2);
								$vs_tier = substr($data[4],3);
							}
							elseif($data4_length == 6)
							{
								$vs_bay = substr($data[4],0,2);
								$vs_row = substr($data[4],2,2);
								$vs_tier = substr($data[4],4);
							}
							
							if($vs_bay != $data[3]){
							    $jml_error = $jml_error + 1;
							    $error = 'Bay number harus sama antara Bay dan slot. <br/> ';
							    throw new Exception($error);
							}
							$param['CONT_HEIGHT'] = str_replace("'", ".",$data[11]); // cont height
							$param['VS_BAY'] = (int) $data[3]; // vs bay
							$param['VS_ROW'] = (int) substr($data[4],-4,2); // vs row
							$param['VS_TIER'] = (int) substr($data[4],-2,2); // vs tier
							$param['ID_POD'] = $data[0]; // id pod
							$param['ID_POL'] = $data[1]; // id pol
							$param['ID_POR'] = $data[15]; // id por
							$param['ID_OPERATOR'] = $data[12]; // id operator
							$param['WEIGHT'] = $data[7]; // weight
							$param['HAZARD'] = $temp_hz = ($data[25]!='' && ($param['IMDG'] != '' || $param['UNNO'] != '')) ? 'Y' : 'N'; // hazard
							// id commodity
							$temp_commodity = '';
							if ($temp_hz=='Y'){
								if ($temp_status=='MTY'){
									$temp_commodity = 'MH';
								}else if ($temp_type=='RFR'){
									$temp_commodity = 'RH';
								}else{
									$temp_commodity = 'H';
								}
							}else {
								if ($temp_status=='MTY'){
									$temp_commodity = 'M';
								}else if ($temp_type=='RFR'){
									$temp_commodity = 'R';
								}else{
									$temp_commodity = 'G';
								}
							}
							$param['ID_COMMODITY'] = $temp_commodity;
							$param['ID_USER_BAPLIE'] = $id_user;
							$param['TEMP'] = $data[14]; // temp setting
							// print_r($param);

							$FL_TONGKANG = $this->getFlTongkang($id_ves_voyage);

							$param['FL_TONGKANG'] = $FL_TONGKANG;
							$param['OVER_HEIGHT'] = $data[17];
							$param['OVER_RIGHT'] = $data[19];
							$param['OVER_LEFT'] = $data[18];
							$param['OVER_FRONT'] = $data[20];
							$param['ID_TERMINAL'] = $this->gtools->terminal();
//							echo '<pre>param : ';print_r($param);echo '</pre>';
							$cek_validation = $this->vessel_detail_validation($id_ves_voyage,$param);
							if ($cek_validation == 1){
							    
							    $query = "INSERT INTO CON_LISTCONT (
									       NO_CONTAINER, POINT, ID_VES_VOYAGE, 
									       UNNO, IMDG, ID_ISO_CODE, ID_CLASS_CODE, 
									       ID_OP_STATUS, OP_STATUS_DESC,
									       CONT_SIZE, CONT_TYPE, CONT_STATUS, 
									       CONT_HEIGHT, VS_BAY, VS_ROW, 
									       VS_TIER, ID_POD, ID_POL, 
									       ID_POR, ID_OPERATOR, WEIGHT,
									       HAZARD, ID_COMMODITY, ID_USER_BAPLIE, TEMP, FL_TONGKANG,
									       OVER_HEIGHT,OVER_RIGHT,OVER_LEFT,OVER_FRONT, ID_TERMINAL) 
									    VALUES ( 
									     '".$param['NO_CONTAINER']."',
									     '".$param['POINT']."',
									     '".$param['ID_VES_VOYAGE']."',
									     '".$param['UNNO']."',
									     '".$param['IMDG']."',
									     '".$param['ID_ISO_CODE']."',
									     '".$param['ID_CLASS_CODE']."',
									     '".$param['ID_OP_STATUS']."',
									     '".$param['OP_STATUS_DESC']."',
									     '".$param['CONT_SIZE']."',
									     '".$param['CONT_TYPE']."',
									     '".$param['CONT_STATUS']."',
									     '".$param['CONT_HEIGHT']."',
									     '".$param['VS_BAY']."',
									     '".$param['VS_ROW']."',
									     '".$param['VS_TIER']."',
									     '".$param['ID_POD']."',
									     '".$param['ID_POL']."',
									     '".$param['ID_POR']."',
									     '".$param['ID_OPERATOR']."',
									     '".$param['WEIGHT']."',
									     '".$param['HAZARD']."', 
									     '".$param['ID_COMMODITY']."',
									     '".$param['ID_USER_BAPLIE']."',
									     '".$param['TEMP']."',
									     '".$param['FL_TONGKANG']."',
									     '".$param['OVER_HEIGHT']."',
									     '".$param['OVER_RIGHT']."',
									     '".$param['OVER_LEFT']."',
									     '".$param['OVER_FRONT']."',
									     '".$param['ID_TERMINAL']."')";
								    $this->db->query($query);
							    if ($tc_nonactive){
								    $query = "UPDATE CON_LISTCONT
											    SET ACTIVE='N'
										    WHERE NO_CONTAINER=? AND POINT=? AND ID_TERMINAL=?";
								    $this->db->query($query, array($param[0], $param[1], $param[30]));
							    }
							}else{
							    throw new Exception($cek_validation);
							}
							// if ($retval){
							// 	$j++;
							// }else{
							// 	$msg .=$this->db->_error_message().",";
							// }
							//$flag = $flag && $retval;
							//$msg .=$this->db->_error_message()." ,";
							if($jml_error == 0)
							{$flag = TRUE;}
						
						// print_r('$jml_error 3 ='.$jml_error);
						    
						}
					} catch (Exception $e){
						$msg .= "<br/>No Container: ".str_replace(' ', '',$data[5])." Error ".$e->getMessage()."";
						$flag = FALSE;
					}
					$i++;
				}
				fclose($handle);
//				exit;
			}
			elseif ((strtolower($type)=='edi')&&($uploadtype=='edifact')) 
			{
				$flag = true;
				$msg  = '';
				$modus = $POST['method'];
				$id_ves_voyage = $POST['ID_VES_VOYAGE'];
				$file = $FILES[file][tmp_name];
				$dataexp = file_get_contents($file);
				$data2 = str_replace("\r\n","",$dataexp);
				$data2 = str_replace("\n","",$data2);
				$data = split("'", $data2);
				// $arr_edi = explode("'", $data);
				// $jml_arr = count($arr_edi);

				// $this->db->trans_start();
				if ($modus == 'overwrite') {
					$query_method = "DELETE FROM CON_LISTCONT 
									  WHERE TRIM(ID_VES_VOYAGE) = '$id_ves_voyage' 
									  		AND ID_CLASS_CODE IN ('I', 'TI', 'TC')";
					$this->db->query($query_method);

					$query_del2 = "DELETE FROM EDI_PARSE_DATA 
									  WHERE TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'";
					$this->db->query($query_del2);

					$query_del3 = "DELETE FROM EDI_PARSE_RESULT_H 
									  WHERE TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'";
					$this->db->query($query_del3);

					$query_del4 = "DELETE FROM EDI_PARSE_RESULT_D 
									  WHERE TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'";
					$this->db->query($query_del4);
				}

				foreach($data as $row_edi){
					$segmen = $row_edi;
					$query2 = "INSERT INTO EDI_PARSE_DATA (ID, 
														   SEGMEN_DATA, 
														   UPDATED_BY,
														   UPDATED_DATE,
														   ID_VES_VOYAGE,
														   CLASS)
									  VALUES (seq_edi_header.nextval,
									  		  '$segmen',
									  		  'itos',
									  		  sysdate,
									  		  '$id_ves_voyage',
									  		  'I')";
				    $this->db->query($query2);
				}

				/* =============== PARSING RESULT ================ */
				$query4 = "BEGIN ITOS_OP.PROC_EDI_PARSE('$id_ves_voyage','I'); END;";
				$this->db->query($query4);

				/* ==================TRANSFER DATA CONTAINER =================== */
				$querydata = "BEGIN ITOS_OP.PROC_EDI_TRANSFER('$id_ves_voyage','I','$id_user'); END;";
				$this->db->query($querydata);

				// $this->db->trans_complete();
			} 
			else 
			{
				$flag = false;
				$msg = "file not in csv or edi format";
			}
		} 
		else 
		{
			$flag = false;
			$msg = "file not found";
		}
		
		return array('flag'=>$flag, 'msg'=>$msg);
	}
	
	public function get_cont_commodity_list(){
		$query 		= "SELECT ID_COMMODITY, COMMODITY_NAME FROM M_CONT_COMMODITY ORDER BY ID_COMMODITY";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_cont_iso_code_list($filter){

		//debux($filter);

		$query 		= "SELECT ISO_CODE ID_ISO_CODE
						FROM M_ISO_CODE
						WHERE ISO_CODE LIKE '".$filter."%'
						ORDER BY ISO_CODE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_data_quay_job_list_report($sort=false, $filters=false){
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			if ($sortProperty=='STOWAGE'){
				$sortProperty = 'VS_BAY';
			}
			if ($sortProperty=='YARD_POS'){
				$sortProperty = 'YD_BLOCK_NAME';
			}
			if ($sortProperty=='NO_CONTAINER'){
				$sortProperty = 'ID_VES_VOYAGE,QC,SEQ_NO';
			}
			$qSort .= " ORDER BY MINUTES,".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND STATUS_FLAG='P'";
		$qWheremin = '';

		$qs = '';
		$encoded = true;

		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case 'NO_CONTAINER'	: $field = "A.".$field; break;
					case 'ID_VES_VOYAGE'	: $field = "F.VESSEL_NAME"; break;
					case 'SEQ_NO'	: $field = "A.SEQUENCE"; break;
					case 'QC'	: $field = "C.MCH_NAME"; break;
					//case 'QUEUE'	:
					case 'ITV'	: $field = "D.MCH_NAME"; break;
					case 'ID_CLASS_CODE'	:
					case 'ID_ISO_CODE'	:
					case 'ID_POD'	:
					case 'ID_OPERATOR'	:
					case 'ID_COMMODITY'	:
					case 'CONT_TYPE'	:
					case 'WEIGHT'	:
					//case 'YARD_POS'	:
					//case 'STOWAGE'	:
					case 'STATUS_FLAG'	: $field = "E.$field"; break;
					case 'JOB' 			: $field = "B.ACTIVITY"; break;
					case 'CMPLT_DT'		: $field = "MINUTES"; break;
					case 'MINUTES'		: $qWheremin = " WHERE ((SYSDATE - A.COMPLETE_DATE)* 24 * 60) <= $value"; break;
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													DECODE(B.ACTIVITY,'I','D','L') AS JOB,
													A.ID_VES_VOYAGE,
													C.MCH_NAME AS QC,
													A.SEQUENCE SEQ_NO,
													B.BAY||'-'||B.DECK_HATCH AS QUEUE,
													D.MCH_NAME AS ITV,
													E.ID_CLASS_CODE,
													E.ID_ISO_CODE,
													E.ID_POD,
													E.ID_OPERATOR,
													E.ID_COMMODITY,
													E.CONT_TYPE,
													E.WEIGHT,
													E.VS_BAY,
													E.VS_ROW,
													E.VS_TIER,
													CASE WHEN B.ACTIVITY = 'I' THEN LPAD(E.VS_BAY,2,'0') || LPAD(E.VS_ROW,2,'0') || LPAD(E.VS_TIER,2,'0')
														ELSE LPAD(CO.BAY_,2,'0') || LPAD(CO.ROW_,2,'0') || LPAD(CO.TIER_,2,'0') END AS STOWAGE,
													E.YD_BLOCK_NAME,
													CASE WHEN E.CONT_SIZE >=40 THEN E.YD_SLOT + 1
													ELSE E.YD_SLOT END AS YD_SLOT,
													E.YD_ROW,
													E.YD_TIER,
													E.ITT_FLAG,
													A.STATUS_FLAG,
													E.TL_FLAG,
													TO_CHAR(A.COMPLETE_DATE, 'DD-MM-YYYY hh24:mi:ss') as MIN,
													(SYSDATE - A.COMPLETE_DATE)* 24 * 60 as MINUTES
										FROM 
											(
											SELECT NO_CONTAINER, POINT, ID_VES_VOYAGE, SEQUENCE, STATUS_FLAG, ID_MCH_WORKING_PLAN, SEQ_MCH_WORKING_PLAN, ID_MACHINE, ID_MACHINE_ITV, COMPLETE_DATE,ID_TERMINAL FROM JOB_QUAY_MANAGER WHERE ID_TERMINAL='".$this->gtools->terminal()."'
											) A
											INNER JOIN MCH_WORKING_SEQUENCE B ON A.ID_MCH_WORKING_PLAN=B.ID_MCH_WORKING_PLAN AND A.SEQ_MCH_WORKING_PLAN=B.SEQUENCE
											INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
											INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT AND E.ID_TERMINAL = A.ID_TERMINAL
											INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
											LEFT JOIN M_MACHINE D ON A.ID_MACHINE_ITV=D.ID_MACHINE
											LEFT JOIN CON_OUTBOUND_SEQUENCE CO ON A.NO_CONTAINER = CO.NO_CONTAINER AND A.POINT = CO.POINT
										$qWhere ";

		if($qWheremin != ''){
			$qWhereminn = "AND STATUS_FLAG = 'C'";
		    $query .= "								UNION ALL
											SELECT A.NO_CONTAINER,
													A.POINT,
													DECODE(B.ACTIVITY,'I','D','L') AS JOB,
													A.ID_VES_VOYAGE,
													C.MCH_NAME AS QC,
													A.SEQUENCE SEQ_NO,
													B.BAY||'-'||B.DECK_HATCH AS QUEUE,
													D.MCH_NAME AS ITV,
													E.ID_CLASS_CODE,
													E.ID_ISO_CODE,
													E.ID_POD,
													E.ID_OPERATOR,
													E.ID_COMMODITY,
													E.CONT_TYPE,
													E.WEIGHT
													E.VS_BAY,
													E.VS_ROW,
													E.VS_TIER,
													LPAD(E.VS_BAY,2,'0') || LPAD(E.VS_ROW,2,'0') || LPAD(E.VS_TIER,2,'0') AS STOWAGE,
													E.YD_BLOCK_NAME,
													CASE WHEN E.CONT_SIZE >=40 THEN E.YD_SLOT + 1
													ELSE E.YD_SLOT END AS YD_SLOT,
													E.YD_ROW,
													E.YD_TIER,
													E.ITT_FLAG,
													A.STATUS_FLAG,
													E.TL_FLAG,
													TO_CHAR(A.COMPLETE_DATE, 'DD-MM-YYYY hh24:mi:ss') as MIN,
													(SYSDATE - A.COMPLETE_DATE)* 24 * 60 as MINUTES
										FROM 
											(
											SELECT NO_CONTAINER, POINT, ID_VES_VOYAGE, SEQUENCE, STATUS_FLAG, ID_MCH_WORKING_PLAN, SEQ_MCH_WORKING_PLAN, ID_MACHINE, ID_MACHINE_ITV, COMPLETE_DATE,ID_TERMINAL FROM JOB_QUAY_MANAGER WHERE ID_TERMINAL='".$this->gtools->terminal()."'
											) A
											INNER JOIN MCH_WORKING_SEQUENCE B ON A.ID_MCH_WORKING_PLAN=B.ID_MCH_WORKING_PLAN AND A.SEQ_MCH_WORKING_PLAN=B.SEQUENCE
											INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
											INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT AND A.ID_TERMINAL = E.ID_TERMINAL
											AND E.QC_PLAN = C.MCH_NAME
											INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
											LEFT JOIN M_MACHINE D ON A.ID_MACHINE_ITV=D.ID_MACHINE
											$qWheremin";
		}else{ $qWhereminn = '';}								
				$query .= "$qSort) V
							) B
						$qWhereminn";
// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		for ($i=0; $i<sizeof($container_list); $i++){
			$container_list[$i]['STOWAGE'] = '';
			$container_list[$i]['YARD_POS'] = '';
			$container_list[$i]['WEIGHT'] = $container_list[$i]['WEIGHT']/1000;
			if ($container_list[$i]['VS_BAY']!=''){
				$container_list[$i]['STOWAGE'] = str_pad($container_list[$i]['VS_BAY'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_ROW'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_TIER'],2,'0',STR_PAD_LEFT);
			}
			if ($container_list[$i]['YD_BLOCK_NAME']!=''){
				$container_list[$i]['YARD_POS'] = $container_list[$i]['YD_BLOCK_NAME'].'-'.$container_list[$i]['YD_SLOT'].'-'.$container_list[$i]['YD_ROW'].'-'.$container_list[$i]['YD_TIER'];
			}
		}
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}

	public function get_data_quay_job_list($paging=false, $sort=false, $filters=false){
		$query_count = "SELECT COUNT(V.NO_CONTAINER) TOTAL
						FROM (
						SELECT NO_CONTAINER FROM JOB_QUAY_MANAGER
						WHERE STATUS_FLAG='P' AND ID_TERMINAL = '".$this->gtools->terminal()."'
						--UNION
						--SELECT NO_CONTAINER FROM JOB_YARD_MANAGER
						--WHERE STATUS_FLAG='P' AND ID_OP_STATUS='OYS' AND EVENT='O'
						) V";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			if ($sortProperty=='STOWAGE'){
				$sortProperty = 'VS_BAY';
			}
			if ($sortProperty=='YARD_POS'){
				$sortProperty = 'YD_BLOCK_NAME';
			}
			if ($sortProperty=='NO_CONTAINER'){
				$sortProperty = 'MINUTES,ID_VES_VOYAGE,QC,SEQ_NO';
			}
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND STATUS_FLAG='P'";
		$qWheremin = '';
		
		$qs = '';
		$encoded = true;
                
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case 'NO_CONTAINER'	: $field = "A.".$field; break;
					case 'ID_VES_VOYAGE'	: $field = "F.VESSEL_NAME"; break;
					case 'SEQ_NO'	: $field = "A.SEQUENCE"; break;
					case 'QC'	: $field = "C.MCH_NAME"; break;
					//case 'QUEUE'	:
					case 'ITV'	: $field = "D.MCH_NAME"; break;
					case 'ID_CLASS_CODE'	:
					case 'ID_ISO_CODE'	:
					case 'ID_POD'	:
					case 'ID_OPERATOR'	:
					case 'ID_COMMODITY'	:
					case 'CONT_TYPE'	:
					case 'WEIGHT'	:
					//case 'YARD_POS'	:
					//case 'STOWAGE'	:
					case 'STATUS_FLAG'	: $field = "E.$field"; break;
					case 'JOB' 			: $field = "B.ACTIVITY"; break;
					case 'CMPLT_DT'		: $field = "MINUTES"; break;
					case 'MINUTES'		: $qWheremin = " WHERE ((SYSDATE - A.COMPLETE_DATE)* 24 * 60) <= $value"; break;
				}
				
				switch($filterType){
					case 'string' 
                                            : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													DECODE(B.ACTIVITY,'I','D','L') AS JOB,
													A.ID_VES_VOYAGE,
													C.ID_MACHINE AS IDQC,
													C.MCH_NAME AS QC,
													C.ID_POOL,
													A.SEQUENCE SEQ_NO,
													B.BAY||'-'||B.DECK_HATCH AS QUEUE,
													D.ID_MACHINE AS IDITV,
													D.MCH_NAME AS ITV,
													E.ID_CLASS_CODE,
													E.ID_ISO_CODE,
													E.ID_POD,
													E.ID_OPERATOR,
													E.ID_COMMODITY,
													E.CONT_TYPE,
													E.WEIGHT/1000 AS WEIGHT,
													E.VS_BAY,
													E.VS_ROW,
													E.VS_TIER,
													CASE WHEN B.ACTIVITY = 'I' THEN LPAD(E.VS_BAY,2,'0') || LPAD(E.VS_ROW,2,'0') || LPAD(E.VS_TIER,2,'0')
														ELSE LPAD(CO.BAY_,2,'0') || LPAD(CO.ROW_,2,'0') || LPAD(CO.TIER_,2,'0') END AS STOWAGE,
													E.YD_BLOCK_NAME,
													CASE WHEN E.CONT_SIZE >=40 THEN E.YD_SLOT + 1
													ELSE E.YD_SLOT END AS YD_SLOT,
													E.YD_ROW,
													E.YD_TIER,
													E.ITT_FLAG,
													A.STATUS_FLAG,
													E.TL_FLAG,
													TO_CHAR(A.COMPLETE_DATE, 'DD-MM-YYYY hh24:mi:ss') as MIN,
													(SYSDATE - A.COMPLETE_DATE)* 24 * 60 as MINUTES
										FROM 
											(
											SELECT NO_CONTAINER, POINT, ID_VES_VOYAGE, SEQUENCE, STATUS_FLAG, ID_MCH_WORKING_PLAN, SEQ_MCH_WORKING_PLAN, ID_MACHINE, ID_MACHINE_ITV, COMPLETE_DATE,ID_TERMINAL FROM JOB_QUAY_MANAGER WHERE ID_TERMINAL='".$this->gtools->terminal()."'
											) A
											INNER JOIN MCH_WORKING_SEQUENCE B ON A.ID_MCH_WORKING_PLAN=B.ID_MCH_WORKING_PLAN AND A.SEQ_MCH_WORKING_PLAN=B.SEQUENCE
											INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
											INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT AND E.ID_TERMINAL = A.ID_TERMINAL
											INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
											LEFT JOIN M_MACHINE D ON A.ID_MACHINE_ITV=D.ID_MACHINE
											LEFT JOIN CON_OUTBOUND_SEQUENCE CO ON A.NO_CONTAINER = CO.NO_CONTAINER AND A.POINT = CO.POINT
										$qWhere ";
		if($qWheremin != ''){
			$qWhereminn = "AND STATUS_FLAG = 'C'";
		    $query .= "								UNION ALL
											SELECT A.NO_CONTAINER,
													A.POINT,
													DECODE(B.ACTIVITY,'I','D','L') AS JOB,
													A.ID_VES_VOYAGE,
													C.MCH_NAME AS QC,
													A.SEQUENCE SEQ_NO,
													B.BAY||'-'||B.DECK_HATCH AS QUEUE,
													D.MCH_NAME AS ITV,
													E.ID_CLASS_CODE,
													E.ID_ISO_CODE,
													E.ID_POD,
													E.ID_OPERATOR,
													E.ID_COMMODITY,
													E.CONT_TYPE,
													E.WEIGHT/1000 AS WEIGHT,
													E.VS_BAY,
													E.VS_ROW,
													E.VS_TIER,
													LPAD(E.VS_BAY,2,'0') || LPAD(E.VS_ROW,2,'0') || LPAD(E.VS_TIER,2,'0') AS STOWAGE,
													E.YD_BLOCK_NAME,
													CASE WHEN E.CONT_SIZE >=40 THEN E.YD_SLOT + 1
													ELSE E.YD_SLOT END AS YD_SLOT,
													E.YD_ROW,
													E.YD_TIER,
													E.ITT_FLAG,
													A.STATUS_FLAG,
													E.TL_FLAG,
													TO_CHAR(A.COMPLETE_DATE, 'DD-MM-YYYY hh24:mi:ss') as MIN,
													(SYSDATE - A.COMPLETE_DATE)* 24 * 60 as MINUTES
										FROM 
											(
											SELECT NO_CONTAINER, POINT, ID_VES_VOYAGE, SEQUENCE, STATUS_FLAG, ID_MCH_WORKING_PLAN, SEQ_MCH_WORKING_PLAN, ID_MACHINE, ID_MACHINE_ITV, COMPLETE_DATE,ID_TERMINAL FROM JOB_QUAY_MANAGER WHERE ID_TERMINAL='".$this->gtools->terminal()."'
											) A
											INNER JOIN MCH_WORKING_SEQUENCE B ON A.ID_MCH_WORKING_PLAN=B.ID_MCH_WORKING_PLAN AND A.SEQ_MCH_WORKING_PLAN=B.SEQUENCE
											INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
											INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT AND A.ID_TERMINAL = E.ID_TERMINAL
											INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
											LEFT JOIN M_MACHINE D ON A.ID_MACHINE_ITV=D.ID_MACHINE
											$qWheremin
			";
		    
		}else{ $qWhereminn = '';}
								$query .= "$qSort) V
											) B
										$qPaging $qWhereminn";
		 // print $query;
// echo '<pre>';print_r($query);echo '</pre>';exit;
		// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
//		debux($container_list);die;
		for ($i=0; $i<sizeof($container_list); $i++){
//			$container_list[$i]['STOWAGE'] = '';
			$container_list[$i]['YARD_POS'] = '';
			$container_list[$i]['WEIGHT'] = $container_list[$i]['WEIGHT'];
//			if ($container_list[$i]['VS_BAY']!=''){
				$container_list[$i]['STOWAGE'] = $container_list[$i]['STOWAGE'];
//				$container_list[$i]['STOWAGE'] = str_pad($container_list[$i]['VS_BAY'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_ROW'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_TIER'],2,'0',STR_PAD_LEFT);
//			}
			if ($container_list[$i]['YD_BLOCK_NAME']!=''){
				$container_list[$i]['YARD_POS'] = $container_list[$i]['YD_BLOCK_NAME'].'-'.$container_list[$i]['YD_SLOT'].'-'.$container_list[$i]['YD_ROW'].'-'.$container_list[$i]['YD_TIER'];
			}
		}
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}

	public function get_data_quay_machine_list(){
		$query = "SELECT
						A.ID_MACHINE,
						C.MCH_NAME 
					FROM
						JOB_QUAY_MANAGER A
						INNER JOIN MCH_WORKING_SEQUENCE B ON A.ID_MCH_WORKING_PLAN = B.ID_MCH_WORKING_PLAN 
						AND A.SEQ_MCH_WORKING_PLAN = B.SEQUENCE
						INNER JOIN M_MACHINE C ON A.ID_MACHINE = C.ID_MACHINE
						INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER = E.NO_CONTAINER 
						AND A.POINT = E.POINT 
						AND E.ID_TERMINAL = A.ID_TERMINAL 
						AND E.QC_PLAN = C.MCH_NAME
						INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE = F.ID_VES_VOYAGE 
					WHERE
						A.ID_TERMINAL = '".$this->gtools->terminal()."'
					GROUP BY
						A.ID_MACHINE,
						C.MCH_NAME
										 ";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_data_yard_machine_yc_list(){
		$query = "SELECT 
													C.ID_MACHINE,
													C.MCH_NAME
										FROM 
											JOB_YARD_MANAGER A
											INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT
											INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
											INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
											
											LEFT JOIN CON_OUTBOUND_SEQUENCE CO 
												ON CO.ID_VES_VOYAGE=A.ID_VES_VOYAGE AND A.NO_CONTAINER = CO.NO_CONTAINER 
												AND A.POINT = CO.POINT
											LEFT JOIN CON_LISTCONT CQ ON CO.ID_VES_VOYAGE=CQ.ID_VES_VOYAGE AND CO.NO_CONTAINER = CQ.NO_CONTAINER 
												AND CO.POINT = CQ.POINT
											LEFT JOIN (SELECT ID_VES_VOYAGE,NO_CONTAINER,ID_CLASS_CODE,MIN(POINT) POINT FROM CON_LISTCONT WHERE ID_CLASS_CODE IN ('S1','S2') GROUP BY ID_VES_VOYAGE,NO_CONTAINER,ID_CLASS_CODE) JSI ON JSI.NO_CONTAINER = CQ.NO_CONTAINER AND JSI.ID_VES_VOYAGE = CQ.ID_VES_VOYAGE AND JSI.ID_CLASS_CODE = CQ.ID_CLASS_CODE AND JSI.POINT = CQ.POINT
											LEFT JOIN (SELECT ID_VES_VOYAGE,NO_CONTAINER,ID_CLASS_CODE,MAX(POINT) POINT FROM CON_LISTCONT WHERE ID_CLASS_CODE IN ('S1','S2') GROUP BY ID_VES_VOYAGE,NO_CONTAINER,ID_CLASS_CODE) JSE ON JSE.NO_CONTAINER = CQ.NO_CONTAINER AND JSE.ID_VES_VOYAGE = CQ.ID_VES_VOYAGE AND JSE.ID_CLASS_CODE = CQ.ID_CLASS_CODE AND JSE.POINT = CQ.POINT
										WHERE A.STATUS_FLAG='P' AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND E.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_TERMINAL='".$this->gtools->terminal()."' AND F.ID_TERMINAL='".$this->gtools->terminal()."' 
					GROUP BY
						C.ID_MACHINE,
													C.MCH_NAME
										 ";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_data_yard_machine_qc_list(){
		$query = "SELECT
					QCPLAN 
				FROM
					(SELECT 
					     CASE WHEN 
						 (LPAD(CO.BAY_,2,'0') || LPAD(CO.ROW_,2,'0') || LPAD(CO.TIER_,2,'0'))  IS NOT NULL
						 AND (E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O')) THEN CQ.QC_PLAN ELSE '' END AS QCPLAN
										FROM 
											JOB_YARD_MANAGER A
											INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT
											INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
											INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
											
											LEFT JOIN CON_OUTBOUND_SEQUENCE CO 
												ON CO.ID_VES_VOYAGE=A.ID_VES_VOYAGE AND A.NO_CONTAINER = CO.NO_CONTAINER 
												AND A.POINT = CO.POINT
											LEFT JOIN CON_LISTCONT CQ ON CO.ID_VES_VOYAGE=CQ.ID_VES_VOYAGE AND CO.NO_CONTAINER = CQ.NO_CONTAINER 
												AND CO.POINT = CQ.POINT
										WHERE A.STATUS_FLAG='P' AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND E.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_TERMINAL='".$this->gtools->terminal()."' AND F.ID_TERMINAL='".$this->gtools->terminal()."'
							) 
						WHERE
							QCPLAN IS NOT NULL 
					GROUP BY
						QCPLAN
										 ";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_data_alat_vmt_monitoring($paging=false, $sort=false, $filters=false){
		$qPaging = '';
		if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			if ($sortProperty=='NAMA_ALAT'){
				$sortProperty = 'MCH_NAME';
			}
			if ($sortProperty=='ISLOGIN_VMT'){
				$sortProperty = 'ISLOGIN_VMT';
			}
			if ($sortProperty=='FULL_NAME_LOGIN'){
				$sortProperty = 'FULL_NAME_LOGIN';
			}
			if ($sortProperty=='DATE_LOGIN'){
				$sortProperty = 'DATE_LOGIN';
			}
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "";
		$qWheremin = '';
		
		$qs = '';
		$encoded = true;
                
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case 'filter_nama_alat' : $field = "MCH_NAME"; break;
				}
				
				switch($filterType){
					case 'string'   : $qs .= " WHERE ".$field." LIKE '%".strtoupper($value)."%'"; Break;
				}
			}
			$qWhere .= $qs;
		}
		$query_count = "SELECT
							COUNT (ID_MACHINE) AS TOTAL
						FROM
							M_MACHINE
						$qWhere 
							";
		$rstotal = $this->db->query($query_count);
		$rowtotal = $rstotal->row_array();
		$total = $rowtotal['TOTAL'];
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  
									SELECT
										ID_MACHINE,
										MCH_NAME,
										FULL_NAME_LOGIN,
										CASE
									WHEN ISLOGIN_VMT IS NULL THEN
										'Tidak'
									WHEN ISLOGIN_VMT = 'Y' THEN
										'Ya'
									ELSE
										'Tidak'
									END AS ISLOGIN_VMT,
									 TO_CHAR (
										DATE_LOGIN,
										'DD-MM-YYYY HH24:MI'
									) DATE_LOGIN
									FROM
										M_MACHINE
									$qWhere 
									$qSort
									) V
											) B
										$qPaging";
		$rs = $this->db->query($query);
		$alat_list = $rs->result_array();
		$data = array (
			'total'=>$total,
			'data'=>$alat_list
		);
		
		return $data;
	}
	
	public function get_data_yard_job_list_report($sort=false, $filters=false, $url=false){
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			if ($sortProperty=='STOWAGE'){
				$sortProperty = 'VS_BAY';
			}
			if ($sortProperty=='YARD_POS'){
				$sortProperty = 'YD_BLOCK_NAME';
			}
			if ($sortProperty=='PA_POS'){
				$sortProperty = 'GT_JS_BLOCK_NAME';
			}
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		//$qWhere = "WHERE 1=1 AND A.STATUS_FLAG='P' AND A.EVENT='P'";
		$qWhere = "WHERE 1=1 AND A.STATUS_FLAG='P'";
		$qWheremin = "WHERE 1=1 ";
		$qryJobWhere = "";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				$field = $filter['field'];
				$value = $filter['value'];
				$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
				$filterType = $filter['type'];
				// debux($compare);die();
				switch($field){
					case'NO_CONTAINER'	: $field = "A.".$field; break;
					case'ID_VES_VOYAGE'	: $field = "F.ID_VES_VOYAGE"; break;
					case'EQ'			: $field = "C.MCH_NAME"; break;
					case'ITV'			: $field = "D.MCH_NAME"; break;
					case'ITT_FLAG'		: $field = "E.ITT_FLAG"; break;
					case'ID_CLASS_CODE'	: $field = "E.".$field; break;
					case'ID_ISO_CODE'	: $field = "E.".$field; break;
					case'ID_POD'		: $field = "E.".$field; break;
					case'ID_OPERATOR'	: $field = "E.".$field; break;
					case'ID_COMMODITY'	: $field = "E.".$field; break;
					case'CONT_TYPE'		: $field = "E.".$field; break;
					case'CONT_SIZE'		: $field = "E.".$field; break;
					case'WEIGHT'		: $field = "E.".$field; break;
					case'JOB'			: 
					    if(trim($value) == 'DS'){
						$qryJobWhere .= "(E.ID_CLASS_CODE='TI' OR (E.ID_CLASS_CODE='I' AND A.EVENT='P'))";
					    }elseif(trim($value) == 'GO'){
						$qryJobWhere .= "(E.ID_CLASS_CODE='I' AND A.EVENT='O')";
					    }elseif(trim($value) == 'LD'){
						$qryJobWhere .= "(E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O'))";
					    }elseif(trim($value) == 'GI'){
						$qryJobWhere .= "(E.ID_CLASS_CODE='E' AND A.EVENT='P')";
					    }elseif(trim($value) == 'MO'){
						$qryJobWhere .= "(A.ID_OP_STATUS='OYY' AND A.EVENT='O')";
					    }elseif(trim($value) == 'MI'){
						$qryJobWhere .= "(A.ID_OP_STATUS='YYY' AND A.EVENT='P')";
					    }
					    break;
					case'CMPLT_DT'	: $field = "MINUTES"; break;
				}
				
				if($field != 'JOB'){
				    switch($filterType){
					    case 'string' : $qs .= " AND LOWER(".$field.") LIKE '%".strtolower($value)."%'"; Break;
					    case 'list' :
						    if (strstr($value,',')){
							    $fi = explode(',',$value);
							    for ($q=0;$q<count($fi);$q++){
								    $fi[$q] = "'".$fi[$q]."'";
							    }
							    $value = implode(',',$fi);
							    $qs .= " AND ".$field." IN (".strtoupper($value).")";
						    }else{
							    $qs .= " AND ".$field." = '".strtoupper($value)."'";
						    }
					    Break;
					    case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					    case 'numeric' :
						    switch ($compare) {
							    case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							    case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							    case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						    }
					    Break;
					    case 'date' :
						    switch ($compare) {
							    case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							    case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							    case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						    }
					    Break;
					    case 'min' : $qWheremin .= " AND MINUTES <= ".$value; break;
					    case 'QCPLAN' : $qWheremin .= " AND QCPLAN = '".$value."'"; break;
				    }
				}
				
//				if($field == 'CMPLT_DT' && $value != ''){
//				    
//				}
			}
			$qWhere .= $qs;
			$qWhere .= $qryJobWhere != '' ? ' AND '.$qryJobWhere : '';
			
		}
		$query = "SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													CASE
														WHEN (A.ID_OP_STATUS = 'OYY' AND EVENT = 'O' AND STATUS_FLAG = 'P') THEN
															'MO'
														WHEN (A.ID_OP_STATUS = 'YYY' AND EVENT = 'P' AND STATUS_FLAG = 'P') THEN
															'MI'
														WHEN (E.ID_CLASS_CODE='TI' OR (E.ID_CLASS_CODE='I' AND A.EVENT='P')) THEN
															'DS'
														WHEN (E.ID_CLASS_CODE='I' AND A.EVENT='O') THEN
															'DL'
														WHEN (E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O')) THEN
															'LD'
														WHEN (E.ID_CLASS_CODE='E' AND A.EVENT='P') THEN
															'RC'
														ELSE
															''
													END AS JOB,
													CASE
														WHEN (A.IS_BYPASS = '1') THEN
															'Yes'
														ELSE
															'No'
													END AS IS_BYPASS,
													A.ID_VES_VOYAGE,
													C.ID_MACHINE,
													C.MCH_NAME AS EQ,
													A.SEQUENCE SEQ_NO,
													B.BAY||'-'||B.DECK_HATCH AS QUEUE,
													D.MCH_NAME AS ITV,
													E.YD_YARD,
													E.CONT_STATUS,
													E.CONT_SIZE,
													E.ID_CLASS_CODE,
													E.ID_ISO_CODE,
													E.ID_POD,
													E.ID_OPERATOR,
													E.ID_COMMODITY,
													E.CONT_TYPE,
													E.WEIGHT,
													I.BAY_,
													I.ROW_,
													I.TIER_,
													E.VS_BAY,
													E.VS_ROW,
													E.VS_TIER,
													E.GT_JS_YARD,
													E.GT_JS_BLOCK,
													E.GT_JS_BLOCK_NAME,
													/*CASE WHEN E.CONT_SIZE >=40 THEN E.GT_JS_SLOT + 1
													ELSE E.GT_JS_SLOT END AS GT_JS_SLOT,*/
													E.GT_JS_SLOT,
													E.GT_JS_ROW,
													E.GT_JS_TIER,
													E.YD_BLOCK,
													E.YD_BLOCK_NAME,
													CASE WHEN E.CONT_SIZE >=40 THEN E.YD_SLOT + 1
													ELSE E.YD_SLOT END AS YD_SLOT,
													E.YD_ROW,
													E.YD_TIER,
													A.EVENT,
													A.ID_OP_STATUS,
													A.STATUS_FLAG,
													E.ID_SPEC_HAND,
													E.ITT_FLAG,
													CASE 
													    WHEN ((E.ID_CLASS_CODE='I' AND A.EVENT='O') OR (E.ID_CLASS_CODE='E' AND A.EVENT='P')) THEN
															ROUND(24 * (SYSDATE - G.DATE_INSPECTION) * 60)
													    WHEN (E.ID_CLASS_CODE='TI' OR (E.ID_CLASS_CODE='I' AND A.EVENT='P')) THEN
												    		ROUND(24 * (SYSDATE - H.DATE_ENTRY) * 60)
													    WHEN (E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O')) THEN
												    		ROUND(24 * (SYSDATE - I.START_SEQUENCE) * 60)
													ELSE 0 END AS WAITING_TIME,
													TO_CHAR(A.COMPLETE_DATE, 'DD-MM-YYYY hh24:mi:ss') as MIN,
													(SYSDATE - A.COMPLETE_DATE)* 24 * 60 as MINUTES,
					    CASE WHEN 
						 (LPAD(COS.BAY_,2,'0') || LPAD(COS.ROW_,2,'0') || LPAD(COS.TIER_,2,'0'))  IS NOT NULL
						 AND (E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O')) THEN CQ.QC_PLAN ELSE '' END AS QCPLAN
										FROM 
											JOB_YARD_MANAGER A
											INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT
											INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
											INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
											LEFT JOIN M_MACHINE D ON A.ID_MACHINE_ITV=D.ID_MACHINE AND D.ID_TERMINAL='".$this->gtools->terminal()."'
											LEFT JOIN MCH_WORKING_SEQUENCE B ON A.ID_MCH_WORKING_PLAN=B.ID_MCH_WORKING_PLAN AND A.SEQ_MCH_WORKING_PLAN=B.SEQUENCE
											LEFT JOIN JOB_GATE_INSPECTION G ON A.NO_CONTAINER = G.NO_CONTAINER AND A.POINT = G.POINT AND G.EI = E.ID_CLASS_CODE
											LEFT JOIN JOB_CONFIRM H ON A.NO_CONTAINER = H.NO_CONTAINER AND A.POINT = H.POINT AND A.ID_VES_VOYAGE=H.ID_VES_VOYAGE
											LEFT JOIN (
												SELECT MWS.*, CO.BAY_, CO.ROW_, CO.TIER_, CO.POINT,CO.NO_CONTAINER,CO.ID_VES_VOYAGE,MWP.ID_MACHINE
												FROM MCH_WORKING_SEQUENCE MWS
												INNER JOIN CON_OUTBOUND_SEQUENCE CO
												  ON MWS.DECK_HATCH = CO.DECK_HATCH AND MWS.BAY = CO.BAY_ 
												INNER JOIN MCH_WORKING_PLAN MWP
												  ON MWS.ID_MCH_WORKING_PLAN = MWP.ID_MCH_WORKING_PLAN AND MWP.ID_VES_VOYAGE = CO.ID_VES_VOYAGE
												WHERE MWS.ACTIVE = 'Y' AND MWS.ACTIVITY = 'E'
											) I ON E.NO_CONTAINER = I.NO_CONTAINER AND E.POINT = I.POINT AND E.ID_VES_VOYAGE = I.ID_VES_VOYAGE 
											LEFT JOIN CON_OUTBOUND_SEQUENCE COS 
												ON COS.ID_VES_VOYAGE=A.ID_VES_VOYAGE AND A.NO_CONTAINER = COS.NO_CONTAINER 
												AND A.POINT = COS.POINT
											LEFT JOIN CON_LISTCONT CQ ON COS.ID_VES_VOYAGE=CQ.ID_VES_VOYAGE AND COS.NO_CONTAINER = CQ.NO_CONTAINER 
												AND COS.POINT = CQ.POINT
										$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND E.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_TERMINAL='".$this->gtools->terminal()."' AND F.ID_TERMINAL='".$this->gtools->terminal()."' 
										$qSort) V $qWheremin";
		// print $query;die();
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		for ($i=0; $i<sizeof($container_list); $i++){
			$container_list[$i]['STOWAGE'] = '';
			$container_list[$i]['YARD_POS'] = '';
			$container_list[$i]['WEIGHT'] = $container_list[$i]['WEIGHT']/1000;
			if ($container_list[$i]['VS_BAY']!=''){
				$container_list[$i]['STOWAGE'] = str_pad($container_list[$i]['VS_BAY'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_ROW'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_TIER'],2,'0',STR_PAD_LEFT);
			}

			if($container_list[$i]['BAY_']!=''){
				$container_list[$i]['STOWAGE'] = str_pad($container_list[$i]['BAY_'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['ROW_'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['TIER_'],2,'0',STR_PAD_LEFT);
			}

			if ($container_list[$i]['GT_JS_BLOCK_NAME']!=''){
				$container_list[$i]['PA_POS'] = $container_list[$i]['GT_JS_BLOCK_NAME'].'-'.$container_list[$i]['GT_JS_SLOT'].'-'.$container_list[$i]['GT_JS_ROW'].'-'.$container_list[$i]['GT_JS_TIER'];
			}
			if ($container_list[$i]['YD_BLOCK_NAME']!=''){
				$container_list[$i]['YARD_POS'] = $container_list[$i]['YD_BLOCK_NAME'].'-'.$container_list[$i]['YD_SLOT'].'-'.$container_list[$i]['YD_ROW'].'-'.$container_list[$i]['YD_TIER'];
				$container_list[$i]['YARD_PLACEMENT'] = $container_list[$i]['YD_BLOCK_NAME'].'^'.$container_list[$i]['YD_SLOT'].'^'.$container_list[$i]['YD_ROW'].'^'.$container_list[$i]['YD_TIER'];
			}
		}
		$data = array (
			'data'=>$container_list
		);
		
		return $data;
	}

	public function get_data_yard_job_list($paging=false, $sort=false, $filters=false, $url=false){
		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM JOB_YARD_MANAGER A
						INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT
						INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
						INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
						LEFT JOIN M_MACHINE D ON A.ID_MACHINE_ITV=D.ID_MACHINE AND D.ID_TERMINAL='".$this->gtools->terminal()."'
						WHERE STATUS_FLAG='P' AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND E.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_TERMINAL='".$this->gtools->terminal()."' AND F.ID_TERMINAL='".$this->gtools->terminal()."' 
						--AND EVENT='P'
						";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			if ($sortProperty=='STOWAGE'){
				$sortProperty = 'VS_BAY';
			}
			if ($sortProperty=='YARD_POS'){
				$sortProperty = 'YD_BLOCK_NAME';
			}
			if ($sortProperty=='PA_POS'){
				$sortProperty = 'GT_JS_BLOCK_NAME';
			}
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		//$qWhere = "WHERE 1=1 AND A.STATUS_FLAG='P' AND A.EVENT='P'";
		$qWhere = "WHERE 1=1 AND A.STATUS_FLAG='P'";
		$qWheremin = "WHERE 1=1 ";
		$qryJobWhere = "";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case'NO_CONTAINER'	: $field = "A.".$field; break;
					case'ID_VES_VOYAGE'	: $field = "F.ID_VES_VOYAGE"; break;
					case'EQ'			: $field = "C.MCH_NAME"; break;
					case'ITV'			: $field = "D.MCH_NAME"; break;
					case'ITT_FLAG'		: $field = "E.ITT_FLAG"; break;
					case'ID_CLASS_CODE'	: $field = "E.".$field; break;
					case'ID_ISO_CODE'	: $field = "E.".$field; break;
					case'ID_POD'		: $field = "E.".$field; break;
					case'ID_OPERATOR'	: $field = "E.".$field; break;
					case'ID_COMMODITY'	: $field = "E.".$field; break;
					case'CONT_TYPE'		: $field = "E.".$field; break;
					case'CONT_SIZE'		: $field = "E.".$field; break;
					case'WEIGHT'		: $field = "E.".$field; break;
					case'JOB'			: 
					    if(trim($value) == 'DS'){
						$qryJobWhere .= "(E.ID_CLASS_CODE='TI' OR (E.ID_CLASS_CODE='I' AND A.EVENT='P'))";
					    }elseif(trim($value) == 'GO'){
						$qryJobWhere .= "(E.ID_CLASS_CODE='I' AND A.EVENT='O')";
					    }elseif(trim($value) == 'LD'){
						$qryJobWhere .= "(E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O'))";
					    }elseif(trim($value) == 'GI'){
						$qryJobWhere .= "(E.ID_CLASS_CODE='E' AND A.EVENT='P')";
					    }elseif(trim($value) == 'MO'){
						$qryJobWhere .= "(A.ID_OP_STATUS='OYY' AND A.EVENT='O')";
					    }elseif(trim($value) == 'MI'){
						$qryJobWhere .= "(A.ID_OP_STATUS='YYY' AND A.EVENT='P')";
					    }
					    break;
					case'CMPLT_DT'	: $field = "MINUTES"; break;
				}
				
				if($field != 'JOB'){
				    switch($filterType){
					    case 'string' : $qs .= " AND LOWER(".$field.") LIKE '%".strtolower($value)."%'"; Break;
					    case 'list' :
						    if (strstr($value,',')){
							    $fi = explode(',',$value);
							    for ($q=0;$q<count($fi);$q++){
								    $fi[$q] = "'".$fi[$q]."'";
							    }
							    $value = implode(',',$fi);
							    $qs .= " AND ".$field." IN (".strtoupper($value).")";
						    }else{
							    $qs .= " AND ".$field." = '".strtoupper($value)."'";
						    }
					    Break;
					    case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					    case 'numeric' :
						    switch ($compare) {
							    case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							    case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							    case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						    }
					    Break;
					    case 'date' :
						    switch ($compare) {
							    case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							    case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							    case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						    }
					    Break;
					    case 'min' : $qWheremin .= " AND MINUTES <= ".$value; break;
					    case 'QCPLAN' : $qWheremin .= " AND QCPLAN = '".$value."'"; break;
				    }
				}
				
//				if($field == 'CMPLT_DT' && $value != ''){
//				    
//				}
			}
			$qWhere .= $qs;
			$qWhere .= $qryJobWhere != '' ? ' AND '.$qryJobWhere : '';
			
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													CASE
														WHEN (A.ID_OP_STATUS = 'OYY' AND EVENT = 'O' AND STATUS_FLAG = 'P') THEN
															'MO'
														WHEN (A.ID_OP_STATUS = 'YYY' AND EVENT = 'P' AND STATUS_FLAG = 'P') THEN
															'MI'
														WHEN (E.ID_CLASS_CODE='TI' OR (E.ID_CLASS_CODE='I' AND A.EVENT='P')) THEN
															'DS'
														WHEN (E.ID_CLASS_CODE='I' AND A.EVENT='O') THEN
															'DL'
														WHEN (E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O')) THEN
															'LD'
														WHEN (E.ID_CLASS_CODE='E' AND A.EVENT='P') THEN
															'RC'
														ELSE
															''
													END AS JOB,
													CASE
														WHEN (A.IS_BYPASS = '1') THEN
															'Yes'
														ELSE
															'No'
													END AS IS_BYPASS,
													A.ID_VES_VOYAGE,
													C.ID_MACHINE,
													C.MCH_NAME AS EQ,
													C.ID_POOL,
													A.SEQUENCE SEQ_NO,
													B.BAY||'-'||B.DECK_HATCH AS QUEUE,
													D.ID_MACHINE AS IDITV,
													D.MCH_NAME AS ITV,
													E.YD_YARD,
													E.CONT_STATUS,
													E.CONT_SIZE,
													E.ID_CLASS_CODE,
													E.ID_ISO_CODE,
													E.ID_POD,
													E.ID_OPERATOR,
													E.ID_COMMODITY,
													E.CONT_TYPE,
													E.WEIGHT,
													I.BAY_,
													I.ROW_,
													I.TIER_,
													E.VS_BAY,
													E.VS_ROW,
													E.VS_TIER,
													E.GT_JS_YARD,
													E.GT_JS_BLOCK,
													E.GT_JS_BLOCK_NAME,
													/*CASE WHEN E.CONT_SIZE >=40 THEN E.GT_JS_SLOT + 1
													ELSE E.GT_JS_SLOT END AS GT_JS_SLOT,*/
													E.GT_JS_SLOT,
													E.GT_JS_ROW,
													E.GT_JS_TIER,
													E.YD_BLOCK,
													E.YD_BLOCK_NAME,
													CASE WHEN E.CONT_SIZE >=40 THEN E.YD_SLOT + 1
													ELSE E.YD_SLOT END AS YD_SLOT,
													E.YD_ROW,
													E.YD_TIER,
													A.EVENT,
													A.ID_OP_STATUS,
													A.STATUS_FLAG,
													E.ID_SPEC_HAND,
													E.ITT_FLAG,
													CASE 
													    WHEN ((E.ID_CLASS_CODE='I' AND A.EVENT='O') OR (E.ID_CLASS_CODE='E' AND A.EVENT='P')) THEN
															ROUND(24 * (SYSDATE - G.DATE_INSPECTION) * 60)
													    WHEN (E.ID_CLASS_CODE='TI' OR (E.ID_CLASS_CODE='I' AND A.EVENT='P')) THEN
												    		ROUND(24 * (SYSDATE - H.DATE_ENTRY) * 60)
													    WHEN (E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O')) THEN
												    		ROUND(24 * (SYSDATE - I.START_SEQUENCE) * 60)
													ELSE 0 END AS WAITING_TIME,
													TO_CHAR(A.COMPLETE_DATE, 'DD-MM-YYYY hh24:mi:ss') as MIN,
													(SYSDATE - A.COMPLETE_DATE)* 24 * 60 as MINUTES,
					     CASE WHEN 
						 (LPAD(COS.BAY_,2,'0') || LPAD(COS.ROW_,2,'0') || LPAD(COS.TIER_,2,'0'))  IS NOT NULL
						 AND (E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O')) THEN CQ.QC_PLAN ELSE '' END AS QCPLAN
										FROM 
											JOB_YARD_MANAGER A
											INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT
											INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
											INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
											LEFT JOIN M_MACHINE D ON A.ID_MACHINE_ITV=D.ID_MACHINE AND D.ID_TERMINAL='".$this->gtools->terminal()."'
											LEFT JOIN MCH_WORKING_SEQUENCE B ON A.ID_MCH_WORKING_PLAN=B.ID_MCH_WORKING_PLAN AND A.SEQ_MCH_WORKING_PLAN=B.SEQUENCE
											LEFT JOIN JOB_GATE_INSPECTION G ON A.NO_CONTAINER = G.NO_CONTAINER AND A.POINT = G.POINT AND G.EI = E.ID_CLASS_CODE
											LEFT JOIN JOB_CONFIRM H ON A.NO_CONTAINER = H.NO_CONTAINER AND A.POINT = H.POINT AND A.ID_VES_VOYAGE=H.ID_VES_VOYAGE
											LEFT JOIN (
												SELECT MWS.*, CO.BAY_, CO.ROW_, CO.TIER_, CO.POINT,CO.NO_CONTAINER,CO.ID_VES_VOYAGE,MWP.ID_MACHINE
												FROM MCH_WORKING_SEQUENCE MWS
												INNER JOIN CON_OUTBOUND_SEQUENCE CO
												  ON MWS.DECK_HATCH = CO.DECK_HATCH AND MWS.BAY = CO.BAY_ 
												INNER JOIN MCH_WORKING_PLAN MWP
												  ON MWS.ID_MCH_WORKING_PLAN = MWP.ID_MCH_WORKING_PLAN AND MWP.ID_VES_VOYAGE = CO.ID_VES_VOYAGE
												WHERE MWS.ACTIVE = 'Y' AND MWS.ACTIVITY = 'E'
											) I ON E.NO_CONTAINER = I.NO_CONTAINER AND E.POINT = I.POINT AND E.ID_VES_VOYAGE = I.ID_VES_VOYAGE 
											LEFT JOIN CON_OUTBOUND_SEQUENCE COS 
												ON COS.ID_VES_VOYAGE=A.ID_VES_VOYAGE AND A.NO_CONTAINER = COS.NO_CONTAINER 
												AND A.POINT = COS.POINT
											LEFT JOIN CON_LISTCONT CQ ON COS.ID_VES_VOYAGE=CQ.ID_VES_VOYAGE AND COS.NO_CONTAINER = CQ.NO_CONTAINER 
												AND COS.POINT = CQ.POINT
										$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND E.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_TERMINAL='".$this->gtools->terminal()."' AND F.ID_TERMINAL='".$this->gtools->terminal()."' 
										$qSort) V $qWheremin
							) B
						$qPaging";

		//debux($query);die;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		//  echo '<pre>'.$this->db->last_query().'</pre>';exit;


		for ($i=0; $i<sizeof($container_list); $i++){
			$container_list[$i]['STOWAGE'] = '';
			$container_list[$i]['YARD_POS'] = '';
			$container_list[$i]['WEIGHT'] = $container_list[$i]['WEIGHT']/1000;
			if ($container_list[$i]['VS_BAY']!=''){
				$container_list[$i]['STOWAGE'] = str_pad($container_list[$i]['VS_BAY'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_ROW'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_TIER'],2,'0',STR_PAD_LEFT);
			}

			if($container_list[$i]['BAY_']!=''){
				$container_list[$i]['STOWAGE'] = str_pad($container_list[$i]['BAY_'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['ROW_'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['TIER_'],2,'0',STR_PAD_LEFT);
			}

			if ($container_list[$i]['GT_JS_BLOCK_NAME']!=''){
				$container_list[$i]['PA_POS'] = $container_list[$i]['GT_JS_BLOCK_NAME'].'-'.$container_list[$i]['GT_JS_SLOT'].'-'.$container_list[$i]['GT_JS_ROW'].'-'.$container_list[$i]['GT_JS_TIER'];
			}
			if ($container_list[$i]['YD_BLOCK_NAME']!=''){
				$container_list[$i]['YARD_POS'] = $container_list[$i]['YD_BLOCK_NAME'].'-'.$container_list[$i]['YD_SLOT'].'-'.$container_list[$i]['YD_ROW'].'-'.$container_list[$i]['YD_TIER'];
				$container_list[$i]['YARD_PLACEMENT'] = $container_list[$i]['YD_BLOCK_NAME'].'^'.$container_list[$i]['YD_SLOT'].'^'.$container_list[$i]['YD_ROW'].'^'.$container_list[$i]['YD_TIER'];
			}
		}
		$data = array (
			'total'=>$total,
			'url' => $url,
			'data'=>$container_list
		);
		
		return $data; 
// 		select * from (
// 	select COMPLETE_DATE, (SYSDATE - COMPLETE_DATE)* 24 * 60 as minutes   from JOB_YARD_MANAGER
// ) where minutes < 15
	}
	
	public function get_data_gate_job_list_report($sort=false, $filters=false){
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			if ($sortProperty=='STOWAGE'){
				$sortProperty = 'VS_BAY';
			}
			if ($sortProperty=='YARD_POS'){
				$sortProperty = 'YD_BLOCK_NAME';
			}
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND A.STATUS_FLAG NOT IN ('I','P','C') ";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case'NO_CONTAINER'		: $field = "A.".$field; break;
					//case'IO'	: 
					case'ID_VES_VOYAGE'		: $field = "E.ID_VES_VOYAGE"; break;
					case'TRX_NUMBER'		: 
					case'GTIN_DATE'			: 
					case'PAYMENT_STATUS'	: 
					case'PAYTHROUGH_DATE'	: $field = "A.$field"; break;
					case'HAZARD'			: 
					case'TL_FLAG'			: 
					case'ID_CLASS_CODE'		:
					case'ID_ISO_CODE'		: 
					case'ID_POD'			: 
					case'ID_OPERATOR'		: 
					case'CONT_STATUS'		: $field = "B.$field"; break;
					case'WEIGHT'			: $field = "B.GT_WEIGHT"; break;
					case'NO_POL'			: $field = "C.$field"; break;
					case'ID_AXLE'			: $field = "D.$field"; break;
					//case'YARD_POS'	: 
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													DECODE(A.EI,'I','D','R') AS IO,
													-- decode I menjadi D(deliver) dan selain I menjadi R(receive)
													A.ID_VES_VOYAGE,
													B.HAZARD,
													B.TL_FLAG,
													C.TID,
													B.GT_WEIGHT WEIGHT,
													D.ID_AXLE,
													TO_CHAR(A.GTIN_DATE, 'DD-MM-YYYY HH24:MI:SS') GTIN_DATE,
													TO_CHAR(A.GTOUT_DATE, 'DD-MM-YYYY HH24:MI:SS') GTOUT_DATE,
													B.ID_CLASS_CODE,
													B.CONT_STATUS,
													B.ID_ISO_CODE,
													B.ID_POD,
													B.ID_OPERATOR,
													B.YD_BLOCK_NAME,
													B.YD_SLOT,
													B.YD_ROW,
													B.YD_TIER,
													DECODE(A.PAYMENT_STATUS,1,'Y','N') PAYMENT_STATUS,
													A.TRX_NUMBER,
													DECODE(A.PAYTHROUGH_DATE,NULL,'',(TO_CHAR(A.PAYTHROUGH_DATE, 'DD-MM-YYYY')||' 23:59:59')) PAYTHROUGH_DATE
										FROM 
											JOB_GATE_MANAGER A
											INNER JOIN CON_LISTCONT B ON A.NO_CONTAINER=B.NO_CONTAINER AND A.POINT=B.POINT
											INNER JOIN VES_VOYAGE E ON A.ID_VES_VOYAGE=E.ID_VES_VOYAGE
											LEFT JOIN M_TRUCK C ON A.ID_TRUCK=C.ID_TRUCK
											LEFT JOIN JOB_GATE_INSPECTION D ON A.NO_CONTAINER=D.NO_CONTAINER AND A.POINT=D.POINT
										$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."'
										$qSort) V
							) B";
		//print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();

		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM JOB_GATE_MANAGER A
						INNER JOIN CON_LISTCONT B ON A.NO_CONTAINER=B.NO_CONTAINER AND A.POINT=B.POINT
						INNER JOIN VES_VOYAGE E ON A.ID_VES_VOYAGE=E.ID_VES_VOYAGE
						LEFT JOIN M_TRUCK C ON A.ID_TRUCK=C.ID_TRUCK
						LEFT JOIN JOB_GATE_INSPECTION D ON A.NO_CONTAINER=D.NO_CONTAINER AND A.POINT=D.POINT
						$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];

		for ($i=0; $i<sizeof($container_list); $i++){
			$container_list[$i]['STOWAGE'] = '';
			$container_list[$i]['YARD_POS'] = '';
			$container_list[$i]['WEIGHT'] = ($container_list[$i]['WEIGHT']!='') ? $container_list[$i]['WEIGHT']/1000 : $container_list[$i]['WEIGHT'];
			if ($container_list[$i]['VS_BAY']!=''){
				$container_list[$i]['STOWAGE'] = str_pad($container_list[$i]['VS_BAY'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_ROW'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_TIER'],2,'0',STR_PAD_LEFT);
			}
			if ($container_list[$i]['YD_BLOCK_NAME']!=''){
				$container_list[$i]['YARD_POS'] = $container_list[$i]['YD_BLOCK_NAME'].'-'.$container_list[$i]['YD_SLOT'].'-'.$container_list[$i]['YD_ROW'].'-'.$container_list[$i]['YD_TIER'];
			}
		}
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}

	public function get_data_gate_job_list($paging=false, $sort=false, $filters=false, $url=false){
		$qPaging = '';
		if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			if ($sortProperty=='STOWAGE'){
				$sortProperty = 'VS_BAY';
			}
			if ($sortProperty=='YARD_POS'){
				$sortProperty = 'YD_BLOCK_NAME';
			}
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND A.STATUS_FLAG NOT IN ('I','P','C') ";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case'NO_CONTAINER'	: $field = "A.".$field; break;
					//case'IO'	: 
					case'ID_VES_VOYAGE'	: $field = "E.ID_VES_VOYAGE"; break;
					case'TRX_NUMBER'	: 
					case'GTIN_DATE'		: 
					case'PAYMENT_STATUS'	: 
					case'PAYTHROUGH_DATE'	: $field = "A.$field"; break;
					case'HAZARD'	: 
					case'TL_FLAG'	: 
					case'ID_CLASS_CODE'	:
					case'ID_ISO_CODE'	: 
					case'ID_POD'	: 
					case'ID_OPERATOR'	: 
					case'CONT_STATUS'	: $field = "B.$field"; break;
					case'WEIGHT'	: $field = "B.GT_WEIGHT"; break;
					case'NO_POL'	: $field = "C.$field"; break;
					case'ID_AXLE'	: $field = "D.$field"; break;
					//case'YARD_POS'	: 
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													DECODE(A.EI,'I','D','R') AS IO,
													-- decode I menjadi D(deliver) dan selain I menjadi R(receive)
													A.ID_VES_VOYAGE,
													B.HAZARD,
													B.TL_FLAG,
													C.TID,
													B.GT_WEIGHT WEIGHT,
													D.ID_AXLE,
													TO_CHAR(A.GTIN_DATE, 'DD-MM-YYYY HH24:MI:SS') GTIN_DATE,
													TO_CHAR(A.GTOUT_DATE, 'DD-MM-YYYY HH24:MI:SS') GTOUT_DATE,
													B.ID_CLASS_CODE,
													B.CONT_STATUS,
													B.ID_ISO_CODE,
													B.ID_POD,
													B.ID_OPERATOR,
													B.YD_BLOCK_NAME,
													B.YD_SLOT,
													B.YD_ROW,
													B.YD_TIER,
													DECODE(A.PAYMENT_STATUS,1,'Y','N') PAYMENT_STATUS,
													A.TRX_NUMBER,
													DECODE(A.PAYTHROUGH_DATE,NULL,'',(TO_CHAR(A.PAYTHROUGH_DATE, 'DD-MM-YYYY')||' 23:59:59')) PAYTHROUGH_DATE
										FROM 
											JOB_GATE_MANAGER A
											INNER JOIN CON_LISTCONT B ON A.NO_CONTAINER=B.NO_CONTAINER AND A.POINT=B.POINT
											INNER JOIN VES_VOYAGE E ON A.ID_VES_VOYAGE=E.ID_VES_VOYAGE
											LEFT JOIN M_TRUCK C ON A.ID_TRUCK=C.ID_TRUCK
											LEFT JOIN JOB_GATE_INSPECTION D ON A.NO_CONTAINER=D.NO_CONTAINER AND A.POINT=D.POINT
										$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."' 
										/*AND B.ID_TERMINAL='".$this->gtools->terminal()."' 
										AND E.ID_TERMINAL='".$this->gtools->terminal()."' 
										AND D.ID_TERMINAL='".$this->gtools->terminal()."'*/
										$qSort) V
							) B
						$qPaging";
//		 print $query;
//		echo '<pre>'.$query.'</pre>';exit;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();

		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM JOB_GATE_MANAGER A
						INNER JOIN CON_LISTCONT B ON A.NO_CONTAINER=B.NO_CONTAINER AND A.POINT=B.POINT
						INNER JOIN VES_VOYAGE E ON A.ID_VES_VOYAGE=E.ID_VES_VOYAGE
						LEFT JOIN M_TRUCK C ON A.ID_TRUCK=C.ID_TRUCK
						LEFT JOIN JOB_GATE_INSPECTION D ON A.NO_CONTAINER=D.NO_CONTAINER AND A.POINT=D.POINT
						$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."'";
		//debux($query_count);die;
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];

		for ($i=0; $i<sizeof($container_list); $i++){
			$container_list[$i]['STOWAGE'] = '';
			$container_list[$i]['YARD_POS'] = '';
			$container_list[$i]['WEIGHT'] = ($container_list[$i]['WEIGHT']!='') ? $container_list[$i]['WEIGHT']/1000 : $container_list[$i]['WEIGHT'];
			if ($container_list[$i]['VS_BAY']!=''){
				$container_list[$i]['STOWAGE'] = str_pad($container_list[$i]['VS_BAY'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_ROW'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_TIER'],2,'0',STR_PAD_LEFT);
			}
			if ($container_list[$i]['YD_BLOCK_NAME']!=''){
				$container_list[$i]['YARD_POS'] = $container_list[$i]['YD_BLOCK_NAME'].'-'.$container_list[$i]['YD_SLOT'].'-'.$container_list[$i]['YD_ROW'].'-'.$container_list[$i]['YD_TIER'];
			}
		}
		$data = array (
			'total'=>$total,
			'url' => $url,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function get_data_outstanding_job($paging=false, $sort=false, $filters=false){
		$query_job_summary = "BEGIN ITOS_OP.PROC_JOB_SUMMARY; END;";
		$this->db->query($query_job_summary);

		$query_count = "SELECT COUNT(ID_VES_VOYAGE) TOTAL
						FROM JOB_MANAGER_SUMMARY WHERE ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			if ($sortProperty=='VOY_IN'){
				$sortProperty = 'VOY_IN';
			}
			if ($sortProperty=='VOY_OUT'){
				$sortProperty = 'VOY_OUT';
			}
			$qSort .= " , ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				if ($field=='VESSEL'){
					$field = $field;
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT ID_VES_VOYAGE,
												 VESSEL,
												 VOY_IN,
												 VOY_OUT,
												 IN_QUAY_JOB,
												 IN_YARD_PLACEMENT,
												 IN_YARD_ONCHASIS,
												 IN_GT_TRUCKORDER,
												 IN_GT_TRUCKIN,
												 IN_GT_INS_DEL,
												 IN_GT_TRUCKOUT,
												 OUT_QUAY_JOB,
												 OUT_YARD_PLACEMENT,
												 OUT_YARD_ONCHASIS,
												 OUT_GT_INS_REC,
												 OUT_GT_TRUCKIN,
												 OUT_GT_TRUCKOUT
										FROM JOB_MANAGER_SUMMARY
										$qWhere AND ID_TERMINAL='".$this->gtools->terminal()."' $qSort) V
							) B
						$qPaging";
		// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}

	public function get_data_gateTrx($paging=false, $sort=false, $filters=false){
		$query_job_summary = "BEGIN ITOS_OP.PROC_JOB_SUMMARY; END;";
		$this->db->query($query_job_summary);

		$query_count = "SELECT COUNT(ID_VES_VOYAGE) TOTAL
						FROM JOB_MANAGER_SUMMARY";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			if ($sortProperty=='VOY_IN'){
				$sortProperty = 'VOY_IN';
			}
			if ($sortProperty=='VOY_OUT'){
				$sortProperty = 'VOY_OUT';
			}
			$qSort .= " , ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				if ($field=='VESSEL'){
					$field = $field;
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT ID_VES_VOYAGE,
												 VESSEL,
												 VOY_IN,
												 VOY_OUT,
												 IN_QUAY_JOB,
												 IN_YARD_PLACEMENT,
												 IN_YARD_ONCHASIS,
												 IN_GT_TRUCKORDER,
												 IN_GT_TRUCKIN,
												 IN_GT_INS_DEL,
												 IN_GT_TRUCKOUT,
												 OUT_QUAY_JOB,
												 OUT_YARD_PLACEMENT,
												 OUT_YARD_ONCHASIS,
												 OUT_GT_INS_REC,
												 OUT_GT_TRUCKIN,
												 OUT_GT_TRUCKOUT
										FROM JOB_MANAGER_SUMMARY
										$qWhere $qSort) V
							) B
						$qPaging";
		// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}

	public function get_data_container_inquiry($no_container, $point=false){
		//$param = array($no_container,$this->gtools->terminal());
		//$param2 = array($no_container,$this->gtools->terminal(),$this->gtools->terminal(),$this->gtools->terminal());
		$query = "SELECT NO_CONTAINER, MAX(POINT) POINT
					FROM CON_LISTCONT
					WHERE NO_CONTAINER = '$no_container' AND ID_TERMINAL='".$this->gtools->terminal()."'
					GROUP BY NO_CONTAINER";
		$rs 		= $this->db->query($query);
		$data 		= $rs->row_array();
		$max_point = $data['POINT'];
		
		// return $data['POINT'];
		
		$qWhere = '';
		if ($max_point && $point){
			if ($point>$max_point){
				$point = $max_point;
			}
			//array_push($param, $point);
			$qWhere = "AND c.POINT='$point'";
		}
		// return $qWhere.' - MAX: '.$max_point.'- POINT:'.$point;
		$query = "
		SELECT mcc.code_name class_code_name,
				 mcs.name cont_status_name,
				 c.id_iso_code,
				 mcsi.name cont_size_name,
				 mch.name cont_height_name,
				 c.id_operator || '-' || mo.operator_name cont_operator_name,
				 mccom.commodity_name,
				 mct.name cont_type_name,
				 c.weight,
				 c.vs_bay,
				 c.vs_row,
				 c.vs_tier,
				 vv.id_vessel || '-' || vv.vessel_name vessel,
				 vv.point || '/' || vv.year visit,
				 vv.voy_in || '/' || vv.voy_out voyage,
				 (SELECT port_code || '-' || port_name
					FROM M_PORT
				   WHERE port_code = c.id_pol)
					pol_name,
				 (SELECT port_code || '-' || port_name
					FROM M_PORT
				   WHERE port_code = c.id_pod)
					pod_name,
				 (SELECT port_code || '-' || port_name
					FROM M_PORT
				   WHERE port_code = c.id_por)
					por_name,
				 CASE WHEN c.ITT_FLAG = 'Y' THEN 
				 	(SELECT H.YARD_NAME_LINI2 
					FROM CON_ITT_D D 
					LEFT JOIN CON_ITT_H H ON D.ID_ITT = H.ID_ITT
					WHERE NO_CONTAINER = c.no_container AND H.ID_VES_VOYAGE = C.ID_VES_VOYAGE)
				 ELSE (SELECT YARD_NAME
					FROM M_YARD
				   WHERE ID_YARD = c.YD_YARD) END  
					KD_lapangan,
				 DECODE (jgm.payment_status, 1, 'Y', 'N') payment,
				 TO_CHAR(jgm.paythrough_date, 'DD-MM-YYYY HH24:MI:SS') paythrough_date,
				 jgm.trx_number,
				 mos.id_op_status,
				 mos.op_status_desc,
				 mos.op_status_group cont_location,
				 c.no_container,
				 c.point,
				 c.yd_block_name,
				 CASE WHEN c.CONT_SIZE > 25 THEN c.yd_slot + 1 ELSE c.yd_slot END AS yd_slot,
				 c.yd_row,
				 c.yd_tier,
				 c.temp,
				 c.unno,
				 c.imdg,
				 c.seal_numb,				 
	                c.OVER_HEIGHT,
	                c.OVER_RIGHT, 
	                c.OVER_LEFT,
	                c.OVER_FRONT,
	                c.OVER_REAR,           
	                c.OVER_WIDTH,
				 DECODE(c.itt_flag,'N','No','Yes') ITT_FLAG,
				 DECODE(c.tl_flag,'N','No','Yes') TL_FLAG,
				 DECODE(c.HOLD_CONTAINER,'N','No','Yes') HOLD_CONTAINER,
				 (SELECT d.BAY_ FROM CON_OUTBOUND_SEQUENCE d 
					WHERE d.NO_CONTAINER = c.no_container
						AND d.POINT = c.point AND rownum = 1 AND d.ID_TERMINAL='".$this->gtools->terminal()."') vsp_bay,
				 (SELECT d.ROW_ FROM CON_OUTBOUND_SEQUENCE d 
					WHERE d.NO_CONTAINER = c.no_container
						AND d.POINT = c.point AND rownum = 1 AND d.ID_TERMINAL='".$this->gtools->terminal()."') vsp_row,
				 (SELECT d.TIER_ FROM CON_OUTBOUND_SEQUENCE d 
					WHERE d.NO_CONTAINER = c.no_container
						AND d.POINT = c.point AND rownum = 1 AND d.ID_TERMINAL='".$this->gtools->terminal()."') vsp_tier						
			FROM CON_LISTCONT c,
				ITOS_REPO.M_CYC_CONTAINER cyc,
				 M_CLASS_CODE mcc,
				 M_CONT_STATUS mcs,
				 M_CONT_SIZE mcsi,
				 M_CONT_HEIGHT mch,
				 M_OPERATOR mo,
				 M_CONT_COMMODITY mccom,
				 M_CONT_TYPE mct,
				 VES_VOYAGE vv,
				 JOB_GATE_MANAGER jgm,
				 M_OP_STATUS mos
		   WHERE     c.NO_CONTAINER = '".$no_container."' 
		   	     AND c.ID_TERMINAL='".$this->gtools->terminal()."' 
		   		 AND vv.ID_TERMINAL ='".$this->gtools->terminal()."' 
		   		 $qWhere
				 AND c.NO_CONTAINER = cyc.NO_CONTAINER(+)
				 AND c.POINT = cyc.POINT(+)
				 AND c.id_class_code = mcc.id_class_code(+)
				 AND c.cont_status = mcs.cont_status(+)
				 AND c.cont_size = mcsi.cont_size(+)
				 AND c.cont_height = mch.cont_height(+)
				 AND c.id_operator = mo.id_operator(+)
				 AND c.id_commodity = mccom.id_commodity(+)
				 AND c.cont_type = mct.cont_type(+)
				 AND c.id_ves_voyage = vv.id_ves_voyage(+)
				 AND c.no_container = jgm.no_container(+)
				 AND CASE WHEN cyc.BILLING_FLAG = 'CALDL' THEN cyc.BILLING_REQUEST_ID ELSE 'Y' END = CASE WHEN cyc.BILLING_FLAG = 'CALDL' THEN jgm.NO_REQUEST ELSE 'Y' END
				 AND c.point = jgm.point(+)
				 AND c.id_op_status = mos.id_op_status(+)
		ORDER BY c.point DESC
		";
		//debux($query);die;
		// return $query;
		$rs 		= $this->db->query($query);
		$data 		= $rs->row_array();
		// echo '<pre>'.$this->db->last_query().'</pre>';exit;
		if ($data){
			$data['STOWAGE'] = '';
			$data['STOWAGE_PLAN'] = '';
			if ($data['VS_BAY']!=''){
				$data['STOWAGE'] = str_pad($data['VS_BAY'],2,'0',STR_PAD_LEFT).str_pad($data['VS_ROW'],2,'0',STR_PAD_LEFT).str_pad($data['VS_TIER'],2,'0',STR_PAD_LEFT);
			}
			if ($data['VSP_BAY']!=''){
				$data['STOWAGE_PLAN'] = str_pad($data['VSP_BAY'],2,'0',STR_PAD_LEFT).str_pad($data['VSP_ROW'],2,'0',STR_PAD_LEFT).str_pad($data['VSP_TIER'],2,'0',STR_PAD_LEFT);
			}
			if ($data['YD_BLOCK_NAME']!=''){
				$data['YARD_POS'] = $data['YD_BLOCK_NAME'].'-'.$data['YD_SLOT'].'-'.$data['YD_ROW'].'-'.$data['YD_TIER'];
			}
		}
		
		return $data;
	}

	public function valid_bmd($no_container, $point, $typeGate, $recDelGate){
		$query = "SELECT
					A.ID_BATALMUAT,
					A.VESSEL,
					A.VOYAGE,
					A.VOYAGE_OUT
				FROM
					ITOS_BILLING.TB_BATALMUAT_D B
				JOIN ITOS_BILLING.TB_BATALMUAT_H A ON A.ID_BATALMUAT = B.ID_BATALMUAT
				WHERE
					NO_CONTAINER = '$no_container'";
		$row   = $this->db->query($query);
		$row_1 = $row->row_array();
		if($row->num_rows()>0){
			$query_ves = "SELECT * FROM 
						  ITOS_OP.VES_VOYAGE 
						  WHERE VESSEL_NAME = '".$row_1['VESSEL']."' 
						  AND VOY_IN 		= '".$row_1['VOYAGE']."'
						  AND VOY_OUT 		= '".$row_1['VOYAGE_OUT']."'";
			$row_ves = $this->db->query($query_ves)->row_array();
			/*update job_gate_manager*/
			$query_up_jg = "UPDATE JOB_GATE_MANAGER 
							SET STATUS_FLAG     = 'C' 
							WHERE ID_VES_VOYAGE = '".$row_ves['ID_VES_VOYAGE']."' 
							AND NO_CONTAINER    = '".$no_container."'";
			$this->db->query($query_up_jg);
			return true;
		}
			return false;

	}
	
	public function get_data_container_inquiryGate($no_container, $point, $typeGate, $recDelGate){
		//debux($point);die;
		if($recDelGate=='REC')
		{
			$ei='E';
		}
		else
		{
			$ei='I';
		}
		// small vessel
		$query_cekTongkang = "
			SELECT
				FL_TONGKANG
			FROM
				CON_LISTCONT
			WHERE
				NO_CONTAINER = '".$no_container."' and POINT  = '".$point." and ID_TERMINAL = '".$this->gtools->terminal()."' 
		";

		$rs_cekTongkang = $this->db->query($query_cekTongkang);
		$row_cekTongkang = $rs_cekTongkang->row_array();
		$FL_TONGKANG = $row_cekTongkang['FL_TONGKANG'];

		if ($FL_TONGKANG == null or $FL_TONGKANG == 'N') {
	        $query = "
	        	SELECT 
					A.NO_CONTAINER,
					A.POINT,
					A.ID_ISO_CODE,
					A.EI,
					B.VESSEL_NAME || ' (' || B.VOY_IN || ' ' || B.VOY_OUT || ') ' AS VESSEL_VOYAGE,
					A.ID_VES_VOYAGE,
					A.GTIN_DATE TRINDATE,
					A.GTOUT_DATE TROTDATE,
					CASE
						WHEN 
							('$ei' = 'E') AND 
							(A.GTIN_DATE IS NULL) AND 
							('$typeGate' = 'IN')
						THEN
							'TRUCK IN RECEIVING'
						WHEN 
							('$ei' = 'E') AND 
							(A.GTIN_DATE IS NOT NULL) AND 
							(A.GTOUT_DATE IS NULL) AND 
							('$typeGate' = 'OUT')
						THEN
							'TRUCK OUT RECEIVING'
						WHEN     
							('$ei' = 'I') AND 
							(A.GTIN_DATE IS NULL) AND 
							(A.GTOUT_DATE IS NULL) AND 
							('$typeGate' = 'IN')
						THEN
							'TRUCK IN DELIVERY'
						WHEN     
							('$ei' = 'I') AND 
							(A.GTIN_DATE IS NOT NULL) AND 
							(A.GTOUT_DATE IS NULL) AND 
							('$typeGate' = 'OUT')
						THEN
							'TRUCK OUT DELIVERY'
						WHEN
							('I' = 'I') AND 
							(D.ITT_FLAG = 'Y') AND 
							(A.GTOUT_DATE IS NULL) AND 
							('OUT' = 'OUT')
						THEN
							'TRUCK OUT ESY'
					END
					TR_JOB,
					TO_CHAR (A.PAYTHROUGH_DATE, 'DD-MM-YYYY') VALID_DATE,
					NVL(C.TID, J.MCH_NAME) TRUCK_NUMBER,
					D.SEAL_NUMB AS SEAL_ID,
					H.ID_AXLE,
					G.AXLE_SIZE,
					G.WEIGHT_ASSUMPTION AS axle,
					D.ID_DAMAGE,
					E.DAMAGE AS damageCont,
					E.DAMAGE AS DAMAGE,
					D.ID_DAMAGE_LOCATION,
					F.DAMAGE_LOCATION AS DAMAGE_LOCATION,
					NVL (D.WEIGHT, 0) WEIGHT,
					NVL (D.WEIGHT, 0) NETTO,
					A.STATUS_FLAG,
					A.PAYMENT_STATUS,
					D.TL_FLAG,
					D.ITT_FLAG
				FROM 
	              	job_gate_manager A
				JOIN ves_voyage b ON A.ID_VES_VOYAGE = B.ID_VES_VOYAGE
				JOIN con_listcont D
					ON
						A.NO_CONTAINER = D.NO_CONTAINER AND 
                                                A.POINT = D.POINT AND
						A.ID_VES_VOYAGE = D.ID_VES_VOYAGE AND 
						A.EI = D.ID_CLASS_CODE
				LEFT JOIN m_damage E ON D.ID_DAMAGE = E.ID_DAMAGE
				LEFT JOIN M_DAMAGE_LOCATION F ON TRIM(D.ID_DAMAGE_LOCATION) = F.ID_DAMAGE_LOCATION
				LEFT JOIN m_truck C ON A.ID_TRUCK = C.ID_TRUCK
				LEFT JOIN JOB_GATE_INSPECTION H ON A.NO_CONTAINER = H.NO_CONTAINER AND A.POINT = H.POINT
				LEFT JOIN M_AXLE_TRUCK G ON H.ID_AXLE = G.ID_AXLE
				LEFT JOIN JOB_CONFIRM I ON A.NO_CONTAINER = I.NO_CONTAINER AND A.POINT = I.POINT
				LEFT JOIN M_MACHINE J ON I.ID_MACHINE_ITV = J.ID_MACHINE
				WHERE     
					A.NO_CONTAINER = '$no_container' AND
					A.POINT='$point' AND 
					A.EI = '$ei'
					AND D.ID_OP_STATUS <> 'DIS'
					AND D.ID_TERMINAL='".$this->gtools->terminal()."'";
              
//			 		debux($query);die;
        }
        else if ($FL_TONGKANG == 'Y') {
			$ei='I';
        	$query = "SELECT A.NO_CONTAINER, A.POINT, A.ID_ISO_CODE, A.EI, B.VESSEL_NAME || ' (' || B.VOY_IN || ' ' || B.VOY_OUT || ') ' AS VESSEL_VOYAGE, A.ID_VES_VOYAGE, A.GTIN_DATE TRINDATE, A.GTOUT_DATE TROTDATE, 'CONTAINER WEIGHING' TR_JOB, TO_CHAR (A.PAYTHROUGH_DATE, 'DD-MM-YYYY') VALID_DATE, C.NO_POL TRUCK_NUMBER, D.SEAL_NUMB AS SEAL_ID, E.DAMAGE AS damageCont, F.DAMAGE_LOCATION damageContLoc, NVL (D.WEIGHT, 0) WEIGHT, A.STATUS_FLAG, A.PAYMENT_STATUS, D.FL_TONGKANG 
        	FROM  job_gate_manager A 
        	JOIN ves_voyage b ON A.ID_VES_VOYAGE = B.ID_VES_VOYAGE 
        	JOIN con_listcont D ON A.NO_CONTAINER = D.NO_CONTAINER AND A.ID_VES_VOYAGE = D.ID_VES_VOYAGE 
        	LEFT JOIN m_damage E ON D.ID_DAMAGE = E.ID_DAMAGE 
        	LEFT JOIN M_DAMAGE_LOCATION F ON D.ID_DAMAGE_LOCATION = F.ID_DAMAGE_LOCATION 
        	LEFT JOIN m_truck C ON A.ID_TRUCK = C.ID_TRUCK 
        	WHERE A.NO_CONTAINER = '$no_container' 
        		AND A.POINT='$point' AND A.EI = '$ei'"
        		."AND A.ID_TERMINAL='".$this->gtools->terminal()."'";
        }
//	    echo '<pre>'.$query.'</pre>';exit;
//         return $query;

		$rs 		= $this->db->query($query);
		$data 		= $rs->row_array();
		
		return $data;
	}
	
	public function get_data_container_inquiryGateAuto($no_container, $typeGate, $recDelGate){
		if($recDelGate=='REC')
		{
			$ei='E';
		}else
		{
			$ei='I';
		}

		$query = " SELECT A.NO_CONTAINER, A.POINT, A.ID_ISO_CODE,A.EI,B.VESSEL_NAME||' ('||B.VOY_IN||' '|| B.VOY_OUT||') ' AS VESSEL_VOYAGE,
                    A.ID_VES_VOYAGE, A.GTIN_DATE TRINDATE, A.GTOUT_DATE TROTDATE, 
					CASE 
						WHEN ('$ei'='E') and (A.GTIN_DATE IS NULL) AND ('$typeGate'='IN') THEN 'TRUCK IN RECEIVING'
						WHEN ('$ei'='E') and (A.GTIN_DATE IS NOT NULL) and (A.GTOUT_DATE IS NULL) AND ('$typeGate'='OUT') THEN 'TRUCK OUT RECEIVING'
						WHEN ('$ei'='I') and (A.GTIN_DATE IS NULL) and (A.GTOUT_DATE IS NULL) AND ('$typeGate'='IN') THEN 'TRUCK IN OUT DELIVERY'
						WHEN ('$ei'='I') and (A.GTIN_DATE IS NOT NULL) and (A.GTOUT_DATE IS NULL) AND ('$typeGate'='OUT') THEN 'TRUCK OUT DELIVERY'
					END TR_JOB
                    from job_gate_manager A JOIN ves_voyage b on A.ID_VES_VOYAGE=B.ID_VES_VOYAGE  
					where A.status_flAg!='C' AND A.NO_CONTAINER='$no_container' AND A.EI='$ei' and A.PAYMENT_DATE IS NOT NULL AND A.PAYMENT_STATUS=1 and TL_FLAG = 'Y' and A.ID_TERMINAL = '".$this->gtools->terminal."' and B.ID_TERMINAL = '".$this->gtools->terminal."'";
//		echo $query;die;
		$rs 		= $this->db->query($query);
		$data 		= $rs->row_array();
		
		return $data;
	}
	
	/*CONTAINER STATUS CHANGE*/
	public function getDataContainerStatusChange($no_container,$inbOutb){
		$query = "select NO_CONTAINER||'^'||POINT||'^'||ID_VES_VOYAGE||'^'||ID_CLASS_CODE ID_CONTCONV , NO_CONTAINER||' Point: '||POINT||', '||ID_VES_VOYAGE||', '||ID_CLASS_CODE NO_CONTAINER_EXP from con_listcont 
		where no_container='$no_container' 
		--and id_class_code='$inbOutb' 
		and ROWNUM = 1 ORDER BY POINT DESC";
		//echo $query;die;
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function getDataDetailStatusChange($parampost){
	
		$pieces = explode("^", $parampost);
		$noContainer=$pieces[0]; 
		$point=$pieces[1]; 
		$ukk=$pieces[2]; 
		$ei=$pieces[3]; 
	
		$query = "select A.NO_CONTAINER, A.ID_CLASS_CODE EI, A.CONT_STATUS FE, A.ID_OPERATOR OPERATOR,A.ID_ISO_CODE, A.ID_VES_VOYAGE, B.VESSEL_NAME, B.VOY_IN||' - '||B.VOY_OUT AS VOYAGE, A.ID_POD AS POD, A.POINT AS POINTS, A.ID_OP_STATUS AS LOCATION_CHG  
		from con_listcont A 
		JOIN VES_VOYAGE B ON A.ID_VES_VOYAGE=B.ID_VES_VOYAGE 
		where A.no_container='$noContainer' and A.id_class_code='$ei' AND A.POINT='$point' AND A.ID_VES_VOYAGE='$ukk' AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND B.ID_TERMINAL='".$this->gtools->terminal()."'";
		//echo $query;die;
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_container_history_statusChange($no_container, $point){
		
		$query 		= "SELECT
						ID_OP_STATUS,
						TO_CHAR(DATE_HISTORY, 'DD-MM-YYYY HH24:MI:SS') DATE_HISTORY_CHAR,
						CASE 
							WHEN ID_OP_STATUS = 'YYY' THEN
								OP_STATUS_DESC||' ( '||YD_BLOCK_NAME||'-'||YD_SLOT||'-'||YD_ROW||'-'||YD_TIER||' )'
							ELSE
								OP_STATUS_DESC
						END AS OP_STATUS_DESC
					FROM CON_LISTCONT_HIST
					WHERE NO_CONTAINER='$no_container' AND ID_TERMINAL='".$this->gtools->terminal()."' AND POINT='$point' 
					--AND ID_OP_STATUS IN ('YYY') 
					ORDER BY DATE_HISTORY";
		//echo $query ;die;
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function saveChange($nocontainer, $pointcontainer, $EI, $idvesvoyage,$lastStatus, $userid){
		$param = array(
				array('name'=>':v_nocontainer', 'value'=>$nocontainer, 'length'=>30),
				array('name'=>':v_pointcontainer', 'value'=>$pointcontainer, 'length'=>10),
				array('name'=>':v_ei', 'value'=>$EI, 'length'=>50),
				array('name'=>':v_idvsbvoyage', 'value'=>$idvesvoyage, 'length'=>50),
				array('name'=>':v_laststat', 'value'=>$lastStatus, 'length'=>50),
				array('name'=>':v_userid', 'value'=>$userid, 'length'=>50),
				array('name'=>':v_terminal', 'value'=>$this->gtools->terminal(), 'length'=>50),
				array('name'=>':v_out', 'value'=>&$msg_out, 'length'=>100)
			);
//		echo '<pre>';print_r($param);echo '</pre>';exit;
		$this->db->trans_start();
		$query = "begin PRC_SAVECHANGE(:v_nocontainer, :v_pointcontainer,:v_ei,:v_idvsbvoyage,:v_laststat,:v_userid,:v_terminal,:v_out); end;";

		$this->db->exec_bind_stored_procedure($query, $param);
		
	
		if ($this->db->trans_complete())
		 { 
		if($msg_out!='OK')
		{
			 return array(
							 'success'=>false,
							 'errors'=>$msg_out
						 );
		}
		else
		{
			 return array(
							 'success'=>true,
							 'errors'=>$msg_out
						 );
		}
		}
		else
		{
			 return array(
							 'success'=>false,
							 'errors'=>$msg_out
						 );
		}
		 
	}
	/*CONTAINER STATUS CHANGE*/
	
	public function get_container_history_status($no_container, $point){
		$param = array($no_container, $point, $this->gtools->terminal());
		$query 		= "SELECT NO_CONTAINER,POINT,ID_TERMINAL,ID_OP_STATUS,OP_STATUS_DESC,TO_CHAR(DATE_HISTORY, 'DD-MM-YYYY HH24:MI') AS DATE_HISTORY_CHAR 
				    FROM (
					SELECT NO_CONTAINER,POINT,ID_TERMINAL,
						ID_OP_STATUS,
						OP_STATUS_DESC,
						MAX(DATE_HISTORY) DATE_HISTORY
					FROM CON_LISTCONT_HIST
					WHERE NO_CONTAINER=? AND POINT=? AND ID_TERMINAL=?
					GROUP BY NO_CONTAINER,POINT,ID_TERMINAL,ID_OP_STATUS,OP_STATUS_DESC
					) A
					ORDER BY DATE_HISTORY";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_container_list_of_point($no_container){
		$param = array($no_container,$this->gtools->terminal());
		$query 		= "SELECT
						POINT
					FROM CON_LISTCONT
					WHERE NO_CONTAINER=? AND ID_TERMINAL=?
					ORDER BY POINT DESC";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function getDamageCode(){
		
		$query 		= "SELECT ID_DAMAGE, DAMAGE FROM M_DAMAGE ORDER BY ID_DAMAGE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function getAxleCont(){
		
		$query 		= "SELECT WEIGHT_ASSUMPTION, AXLE_SIZE FROM M_AXLE_TRUCK ORDER BY ID_AXLE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}	
	
	public function getDamageLocation(){
		
		$query 		= "SELECT ID_DAMAGE_LOCATION, DAMAGE_LOCATION FROM M_DAMAGE_LOCATION ORDER BY ID_DAMAGE_LOCATION";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function getContainerDetail($no_container){
		if($no_container!='null'){
			$param = array($no_container);
			$query 		= "SELECT
						A.POINT, A.ID_CLASS_CODE, A.CONT_STATUS, A.ID_ISO_CODE, A.CONT_TYPE, A.CONT_SIZE,A.ID_OPERATOR,A.WEIGHT, A.ID_VES_VOYAGE,B.VESSEL_NAME, B.VOY_IN, B.VOY_OUT, to_char(B.ATB,'dd-mm-yyyy hh24:mi') ATB, to_char(B.ATD,'dd-mm-yyyy hh24:mi') ATD, A.CONT_HEIGHT
					FROM CON_LISTCONT A JOIN VES_VOYAGE B ON A.ID_VES_VOYAGE=B.ID_VES_VOYAGE
					WHERE A.NO_CONTAINER=? AND A.ACTIVE='Y' AND A.ID_TERMINAL='".$this->gtools->terminal()."'
					ORDER BY A.POINT DESC";
		}
		else
		{
			$query 		= "SELECT
						'' POINT, '' ID_CLASS_CODE, '' CONT_STATUS, '' ID_ISO_CODE, '' CONT_TYPE, '' CONT_SIZE,'' ID_OPERATOR,'' WEIGHT, '' ID_VES_VOYAGE,'' VESSEL_NAME, '' VOY_IN, '' VOY_OUT, '' ATB, '' ATD, '' CONT_HEIGHT
					FROM dual";
		}
		
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function saveContainerGate($nocontainer, $pointcontainer, $truckJob, $ei, $idvesvoyage, $trucknumber, $sealid, $weight, $userid, $dmg, $dmgLoc,$tl,$esy){
		// small vessel
		
//		$query_cekTongkang = "
//			SELECT
//				FL_TONGKANG
//			FROM
//				CON_LISTCONT
//			WHERE
//				NO_CONTAINER = '".$nocontainer."' AND POINT = '".$pointcontainer."' AND ID_TERMINAL = '".$this->gtools->terminal()."'
//		";
//		echo '<pre>'.$query_cekTongkang.'</pre>';
//		$rs_cekTongkang = $this->db->query($query_cekTongkang);
//		$row_cekTongkang = $rs_cekTongkang->row_array();
//		$FL_TONGKANG = $row_cekTongkang['FL_TONGKANG'];
//		echo '<pre>FL_TONGKANG : '.$FL_TONGKANG.'</pre>';
//		exit;
//		if ($FL_TONGKANG == 'Y') {
//			// $ei = 'I';
//			$param = array(
//				array('name'=>':v_nocontainer', 'value'=>$nocontainer, 'length'=>30),
//				array('name'=>':v_pointcontainer', 'value'=>$pointcontainer, 'length'=>10),
//				array('name'=>':v_TID', 'value'=>$trucknumber, 'length'=>50),
//				array('name'=>':v_sealnumber', 'value'=>$sealid, 'length'=>50),
//				array('name'=>':v_ei', 'value'=>$ei, 'length'=>50),
//				array('name'=>':v_trjob', 'value'=>$truckJob, 'length'=>50),
//				array('name'=>':v_idvsbvoyage', 'value'=>$idvesvoyage, 'length'=>50),
//				array('name'=>':v_weight', 'value'=>$weight, 'length'=>50),
//				array('name'=>':v_userid', 'value'=>$userid, 'length'=>50),
//				array('name'=>':v_dmg', 'value'=>$dmg, 'length'=>50),
//				array('name'=>':v_dmgLoc', 'value'=>$dmgLoc, 'length'=>50),
//				array('name'=>':v_terminal', 'value'=>$this->gtools->terminal(), 'length'=>50),
//				array('name'=>':v_out', 'value'=>&$msg_out, 'length'=>1000)
//			);
//
//			if ($weight <= 0) {
//				return array(
//					'success'=>false,
//					'errors'=>'WEIGHT CANT BE NULL!'
//				);
//			}
//			else{
//				$this->db->trans_start();
//				$query = "begin prc_GO_ContainerWeighing2(
//					:v_nocontainer,
//					:v_pointcontainer,
//					:v_trucknumber,
//					:v_sealnumber,
//					:v_ei,
//					:v_trjob,
//					:v_idvsbvoyage,
//					:v_weight,
//					:v_userid,
//					:v_dmg,
//					:v_dmgLoc,
//					:v_terminal,
//					:v_out
//				); end;";
//
////		        var_dump($param); die();
//				$this->db->exec_bind_stored_procedure($query, $param);
//				if ($this->db->trans_complete())
//				 {
//					if($msg_out!='OK')
//					{
//						 return array(
//										 'success'=>false,
//										 'errors'=>$msg_out
//									 );
//					}
//					else
//					{
//						 return array(
//										 'success'=>true,
//										 'errors'=>$msg_out
//									 );
//					}
//				 }
//				 else
//				 {
//					return array(
//								 'success'=>false,
//								 'errors'=>$msg_out
//							 );
//				 }
//			}
//		}
//		else{
		if($weight == '' || $weight == 0){
		    return array(
			'success'=>false,
			'errors'=>'Weight must be fill!'
		    );
		}
		$terminalid = $this->gtools->terminal();
		$get_job_slip 	= 'OK';
		if($esy == 'Y' && $truckJob == 'TRUCK OUT ESY'){
                    $query = "SELECT COUNT(*) TOTAL
        		FROM JOB_GATE_INSPECTION WHERE NO_CONTAINER = '$nocontainer' AND POINT = $pointcontainer AND GTOUT_LANE IS NOT NULL AND ID_TERMINAL  = ".$this->gtools->terminal();
                    $row = $this->db->query($query)->row();
                    if($row->TOTAL == 0){
                        $get_job_slip = 'NO';
                        return array(
                                'success'=>false,
                                'errors'=>'Container not yet inspection'
                        );
                    } else {
                        $this->load->library('nusoap_lib');
                        $client = new nusoap_client($this->config->item('link_nusoap_client'));

                        $error = $client->getError();
                        if ($error) {
                           $get_job_slip = 'NO';
                        }

                        $param = array("id_ves_voyage"  => 	"$idvesvoyage", 
                                        "no_container" => "$nocontainer",
                                        "point" => "$pointcontainer",
                                        "weight" => "$weight",
                                        "remark" => "",
                                        "seal_numb" => "$sealid");

                        $result = $client->call("getJobYard_Lini2", $param);

                        if ($client->fault) {
                            $get_job_slip = 'NO';
                            return array(
                                    'success'=>false,
                                    'errors'=>$client->fault
                            );
                        }
                        else {
                           $error = $client->getError();
                           if ($error) {
                                   $get_job_slip = 'NO';
                                   return array(
                                            'success'=>false,
                                            'errors'=>$client->fault
                                    );
                           }
                           else {
                                   $get_job_slip = $result;
                           }		 
                        }
                        if($get_job_slip != 'NO')
                        {
                                $get_job_slip_array = explode("-",$get_job_slip);
                                $get_job_slip = $get_job_slip_array[0];
                                //$err."-".$idbl."-".$nmbl."-".$slotbl."-".$rowbl."-".$tierbl."-".$sz
                            //	0        1         2          3          4           5
                                if($get_job_slip != 'OK'){
                                        return array(
                                                'success'=>false,
                                                'errors'=>$get_job_slip_array[0].'-'.$get_job_slip_array[1]
                                        );
                                }
                        }
                    }
		}
		if($get_job_slip == 'OK'){
			$param = array(
				array('name'=>':v_nocontainer', 'value'=>$nocontainer, 'length'=>30),
				array('name'=>':v_pointcontainer', 'value'=>$pointcontainer, 'length'=>10),
				array('name'=>':v_trucknumber', 'value'=>$trucknumber, 'length'=>50),
				array('name'=>':v_sealnumber', 'value'=>$sealid, 'length'=>50),
				array('name'=>':v_ei', 'value'=>$ei, 'length'=>50),
				array('name'=>':v_trjob', 'value'=>$truckJob, 'length'=>50),
				array('name'=>':v_idvsbvoyage', 'value'=>$idvesvoyage, 'length'=>50),
				array('name'=>':v_weight', 'value'=>$weight, 'length'=>50),
				array('name'=>':v_userid', 'value'=>$userid, 'length'=>50),
				array('name'=>':v_dmg', 'value'=>$dmg, 'length'=>50),
				array('name'=>':v_dmgLoc', 'value'=>$dmgLoc, 'length'=>50),
				array('name'=>':v_terminal', 'value'=>$terminalid, 'length'=>50),
				array('name'=>':v_out', 'value'=>&$msg_out, 'length'=>1000)
			);
//			echo '<pre>------------------</pre>';
//	        debux($param); die();

			$this->db->trans_start();
			$query = "begin prc_gateOperation(
				:v_nocontainer,
				:v_pointcontainer,
				:v_trucknumber,
				:v_sealnumber,
				:v_ei,
				:v_trjob,
				:v_idvsbvoyage,
				:v_weight,
				:v_userid,
				:v_dmg,
				:v_dmgLoc,
				:v_terminal,
				:v_out
			); end;";

			$this->db->exec_bind_stored_procedure($query, $param);

				$query = "SELECT
							C.MCH_NAME
						FROM
							JOB_YARD_MANAGER A
							LEFT JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
						WHERE
							A.NO_CONTAINER LIKE '".$nocontainer."'
					    AND A.ID_VES_VOYAGE = '".$idvesvoyage."' AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_TERMINAL='".$this->gtools->terminal()."'";
				$row = $this->db->query($query)->row();
				 //$err."-".$idbl."-".$nmbl."-".$slotbl."-".$rowbl."-".$tierbl."-".$sz
				//  0        1         2          3          4           5
				if($esy == 'Y'){
				    $esy_slot = explode('/', $get_job_slip_array[3]);
				    $query_up = "UPDATE CON_LISTCONT
							     SET YC_PLAN = '".$row->MCH_NAME."'
								 ,GT_JS_BLOCK = '".$get_job_slip_array[1]."'
								 ,GT_JS_BLOCK_NAME = '".$get_job_slip_array[2]."'
								 ,GT_JS_SLOT = '".$esy_slot[0]."'
								 ,GT_JS_ROW = '".$get_job_slip_array[4]."'
								 ,GT_JS_TIER = '".$get_job_slip_array[5]."'
							     WHERE NO_CONTAINER = '".$nocontainer."'
							     AND ID_VES_VOYAGE = '".$idvesvoyage."' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
				}else{
				    $query_up = "UPDATE CON_LISTCONT
							     SET YC_PLAN = '".$row->MCH_NAME."'
							     WHERE NO_CONTAINER = '".$nocontainer."'
							     AND ID_VES_VOYAGE = '".$idvesvoyage."' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
				}
			$this->db->query($query_up);
		
			if ($this->db->trans_complete())
			{
				if($msg_out!='OK')
				{
					return array(
									 'success'=>false,
									 'errors'=>$msg_out
								 );
				}
				else
				{
					 return array(
									 'success'=>true,
									 'errors'=>$msg_out
								 );
				}
			}
			else
			{
			return array(
						 'success'=>false,
						 'errors'=>$msg_out
					 );
			} 
		} 
			
//		}
	}
	
	public function saveContainerGateAdmin($nocontainer, $pointcontainer, $date_gate, $truckJob, $ei, $idvesvoyage, $trucknumber, $sealid, $weight, $userid, $dmg, $dmgLoc, $remarks){
		$param = array(
				array('name'=>':v_nocontainer', 'value'=>$nocontainer, 'length'=>30),
				array('name'=>':v_pointcontainer', 'value'=>$pointcontainer, 'length'=>10),
				array('name'=>':v_date_gate', 'value'=>$date_gate, 'length'=>20),
				array('name'=>':v_trucknumber', 'value'=>$trucknumber, 'length'=>50),
				array('name'=>':v_sealnumber', 'value'=>$sealid, 'length'=>50),
				array('name'=>':v_ei', 'value'=>$ei, 'length'=>50),
				array('name'=>':v_trjob', 'value'=>$truckJob, 'length'=>50),
				array('name'=>':v_idvsbvoyage', 'value'=>$idvesvoyage, 'length'=>50),
				array('name'=>':v_weight', 'value'=>$weight, 'length'=>50),
				array('name'=>':v_userid', 'value'=>$userid, 'length'=>50),
				array('name'=>':v_dmg', 'value'=>$dmg, 'length'=>50),
				array('name'=>':v_dmgLoc', 'value'=>$dmgLoc, 'length'=>50),
				array('name'=>':v_remarks', 'value'=>$remarks, 'length'=>500),
				array('name'=>':v_terminal', 'value'=>$this->gtools->terminal(), 'length'=>500),
				array('name'=>':v_out', 'value'=>&$msg_out, 'length'=>100)
			);
		$this->db->trans_start();
		// $msg_out = 'OK';
		$query = "begin prc_gateOperationAdmin(:v_nocontainer, :v_pointcontainer,:v_date_gate,:v_trucknumber,:v_sealnumber,:v_ei,:v_trjob,:v_idvsbvoyage,:v_weight,:v_userid,:v_dmg ,:v_dmgLoc,:v_remarks,:v_terminal,:v_out); end;";
		//var_dump($param); die();
		$this->db->exec_bind_stored_procedure($query, $param);
		if ($this->db->trans_complete()){
			if($msg_out!='OK')
			{
				 return array(
								 'success'=>false,
								 'errors'=>$msg_out
							 );
			}
			else
			{
				 return array(
								 'success'=>true,
								 'errors'=>$msg_out
							 );
			}
		}else{
			return array(
						 'success'=>false,
						 'errors'=>$msg_out
					 );
		}
	}
	
	public function saveContainerAutoGate($nocontainer, $pointcontainer, $truckJob, $ei, $idvesvoyage,$trucknumber, $sealid, $weight,$userid, $dmg, $dmgLoc){

		$param = array(
				array('name'=>':v_nocontainer', 'value'=>$nocontainer, 'length'=>30),
				array('name'=>':v_pointcontainer', 'value'=>$pointcontainer, 'length'=>10),
				array('name'=>':v_trucknumber', 'value'=>$trucknumber, 'length'=>50),
				array('name'=>':v_sealnumber', 'value'=>$sealid, 'length'=>50),
				array('name'=>':v_ei', 'value'=>$ei, 'length'=>50),
				array('name'=>':v_trjob', 'value'=>$truckJob, 'length'=>50),
				array('name'=>':v_idvsbvoyage', 'value'=>$idvesvoyage, 'length'=>50),
				array('name'=>':v_weight', 'value'=>$weight, 'length'=>50),
				array('name'=>':v_userid', 'value'=>$userid, 'length'=>50),
				array('name'=>':v_dmg', 'value'=>$dmg, 'length'=>50),
				array('name'=>':v_dmgLoc', 'value'=>$dmgLoc, 'length'=>50),
				array('name'=>':v_terminal', 'value'=>$this->gtools->terminal(), 'length'=>50),
				array('name'=>':v_out', 'value'=>&$msg_out, 'length'=>200)
			);
//		var_dump($param); die;
		$this->db->trans_start();
		$query = "begin prc_gateOperationAuto(:v_nocontainer, :v_pointcontainer,:v_trucknumber,:v_sealnumber,:v_ei,:v_trjob,:v_idvsbvoyage,:v_weight,:v_userid,:v_dmg ,:v_dmgLoc,:v_terminal,:v_out); end;";
		$this->db->exec_bind_stored_procedure($query, $param);
		
		 
			if($msg_out<>'OK')
			{
				if($msg_out==null)
				{ 
					$msg_out=$genError;
				}
				 return array(
								 'success'=>false,
								 'errors'=>$msg_out
							 );
			}
			else
			{
				 return array(
								 'success'=>true,
								 'errors'=>$msg_out
							 );
			}
		
	}
	
	public function saveRenameContainer($a,$b,$c,$id_user){
		//(v_oldnocont in varchar2, v_oldpoint in varchar2,v_newnocont in varchar2, v_userid in varchar2,v_msg out varchar2)
		$param = array($c);
		$query = "SELECT COUNT(*) JUMLAH FROM CON_LISTCONT WHERE NO_CONTAINER=? AND ACTIVE='Y' AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query, $param);
		$data = $rs->row_array();
		$count_active = $data['JUMLAH'];
		//echo $query;die;
		
		if ($count_active==0){
			$param = array(
					array('name'=>':v_oldnocont', 'value'=>$a, 'length'=>30),
					array('name'=>':v_oldpoint', 'value'=>$b, 'length'=>50),
					array('name'=>':v_newnocont', 'value'=>$c, 'length'=>50),
					array('name'=>':v_userid', 'value'=>$id_user, 'length'=>50),
					array('name'=>':v_terminal', 'value'=>$this->gtools->terminal(), 'length'=>50)
				);
			$this->db->trans_start();
			$query = "begin PRC_INS_CONTRENAME(:v_oldnocont, :v_oldpoint,:v_newnocont, :v_userid, :v_terminal ); end;";
			//echo $query;die;
			$this->db->exec_bind_stored_procedure($query, $param);
			
			if ($this->db->trans_complete()){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 'New Container Number currently in active cycle!';
		}
	}
	
	public function get_data_single_correction($no_container, $point=false){
		$param = array($no_container,$this->gtools->terminal());
		$query = "SELECT NO_CONTAINER, MAX(POINT) AS POINT
					FROM CON_LISTCONT
					WHERE NO_CONTAINER = ? AND ID_TERMINAL=?
					GROUP BY NO_CONTAINER";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row_array();
		//debux($data);die;
		$max_point = $data['POINT'];
		
		$qWhere = '';
		if ($max_point && $point){
			if ($point>$max_point){
				$point = $max_point;
			}
			array_push($param, $point);
			//debux($data);die;
			$qWhere = ' AND C.POINT=? ';
		}
		$query = "
			SELECT mcc.code_name class_code_name,
				 mcs.name cont_status_name,
				 c.id_iso_code,
				 mcsi.name cont_size_name,
				 mch.name cont_height_name,
				 c.id_operator || '-' || mo.operator_name cont_operator_name,
				 mccom.commodity_name,
				 mct.name cont_type_name,
				 c.weight,
				 c.vs_bay,
				 c.vs_row,
				 c.vs_tier,
				 vv.id_vessel || '-' || vv.vessel_name vessel,
				 vv.point || '/' || vv.year visit,
				 vv.voy_in || '/' || vv.voy_out voyage,
				 (SELECT port_code || '-' || port_name
					FROM M_PORT
				   WHERE port_code = c.id_pol)
					pol_name,
				 (SELECT port_code || '-' || port_name
					FROM M_PORT
				   WHERE port_code = c.id_pod)
					pod_name,
				 (SELECT port_code || '-' || port_name
					FROM M_PORT
				   WHERE port_code = c.id_por)
					por_name,
				 DECODE (jgm.payment_status, 1, 'Y', 'N') payment,
				 TO_CHAR(jgm.paythrough_date, 'DD-MM-YYYY HH24:MI:SS') paythrough_date,
				 jgm.trx_number,
				 mos.id_op_status,
				 mos.op_status_desc,
				 mos.op_status_group cont_location,
				 c.no_container,
				 c.point,
				 c.cont_height,
				 c.cont_size,
				 c.cont_type,
				 c.cont_status,
				 c.id_class_code,
				 c.id_operator,
				 c.id_commodity,
				 c.id_pol,
				 c.id_pod,
				 c.id_por,
				 vv.id_ves_voyage,
				 c.yd_block_name,
				 c.yd_slot,
				 c.yd_row,
				 c.yd_tier,
				 c.temp,
				 c.unno,
				 c.imdg,
				 c.seal_numb,
				 c.tl_flag,
				 c.OVER_HEIGHT,
				 c.OVER_RIGHT,
				 c.OVER_LEFT,
				 c.OVER_FRONT,
				 c.OVER_REAR,
				 C.ID_POD AS ID_POD_VAL,
				 C.ID_POL AS ID_POL_VAL,
				 C.ID_POR AS ID_FPOD_VAL,
				 vv.id_ves_voyage AS ID_VES_VOYAGE_VAL
			FROM CON_LISTCONT c,
				 M_CLASS_CODE mcc,
				 M_CONT_STATUS mcs,
				 M_CONT_SIZE mcsi,
				 M_CONT_HEIGHT mch,
				 M_OPERATOR mo,
				 M_CONT_COMMODITY mccom,
				 M_CONT_TYPE mct,
				 VES_VOYAGE vv,
				 JOB_GATE_MANAGER jgm,
				 M_OP_STATUS mos
		   WHERE     c.NO_CONTAINER = ? AND C.ACTIVE='Y' AND c.ID_TERMINAL=? $qWhere
				 AND c.id_class_code = mcc.id_class_code(+)
				 AND c.cont_status = mcs.cont_status(+)
				 AND c.cont_size = mcsi.cont_size(+)
				 AND c.cont_height = mch.cont_height(+)
				 AND c.id_operator = mo.id_operator(+)
				 AND c.id_commodity = mccom.id_commodity(+)
				 AND c.cont_type = mct.cont_type(+)
				 AND c.id_ves_voyage = vv.id_ves_voyage(+)
				 AND c.no_container = jgm.no_container(+)
				 AND c.point = jgm.point(+)
				 AND c.id_op_status = mos.id_op_status(+)
		ORDER BY c.point DESC
		";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row_array();
		
		if ($data){
			$data['STOWAGE'] = '';
			if ($data['VS_BAY']!=''){
				$data['STOWAGE'] = str_pad($data['VS_BAY'],2,'0',STR_PAD_LEFT).str_pad($data['VS_ROW'],2,'0',STR_PAD_LEFT).str_pad($data['VS_TIER'],2,'0',STR_PAD_LEFT);
			}
			if ($data['YD_BLOCK_NAME']!=''){
				$data['YARD_POS'] = $data['YD_BLOCK_NAME'].'-'.$data['YD_SLOT'].'-'.$data['YD_ROW'].'-'.$data['YD_TIER'];
			}
		}
		
		return $data;
	}
	
	public function save_single_correction($data,$id_user){
		$msg_out = '';

		$pod = isset($data['ID_POD']) && $data['ID_POD'] != '' ? $data['ID_POD'] : $data['ID_POD_VAL'];
		$fpod = isset($data['ID_POR']) && $data['ID_POR'] != '' ? $data['ID_POR'] : $data['ID_FPOD_VAL'];

		if ($data['ID_POR']=='Autocomplete'){
			$data['ID_POR'] = "";
		}

		//echo $id_user;die;

		$query = "SELECT 
				  IN_SERVICE 
				  FROM VES_VOYAGE 
				  WHERE ID_VES_VOYAGE = '".$data['ID_VES_VOYAGE_VAL']."' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$ress  = $this->db->query($query)->row();

		$query1="SELECT 
				COUNT(ID_PORT) AS POD 
				FROM M_VESSEL_SERVICE_PORT 
				WHERE ID_VESSEL_SERVICE = '".$ress->IN_SERVICE."'
				AND ID_PORT = '".$pod."'";
		$ress1 = $this->db->query($query1)->row();

		$query2="SELECT 
				COUNT(ID_PORT) AS POR 
				FROM M_VESSEL_SERVICE_PORT
				WHERE ID_VESSEL_SERVICE = '".$ress->IN_SERVICE."'
				AND ID_PORT = '".$fpod."'";
		$ress2 = $this->db->query($query2)->row();

		// echo $query2;exit;

		$query3="SELECT 
				COUNT(ID_OPERATOR) AS OPERATOR 
				FROM M_VESSEL_SERVICE_OPERATOR 
				WHERE ID_VESSEL_SERVICE = '".$ress->IN_SERVICE."'
				AND ID_OPERATOR = '".$data['ID_OPERATOR']."'";
		$ress3 = $this->db->query($query3)->row();

		if($ress1->POD == 0){
			$msg_out = 'POD not match in vessel service';
		}

		if($ress2->POR == 0){
			$msg_out = 'FPOD not match in vessel service';
		}

		if($ress3->OPERATOR == 0){
			//$msg_out = 'OPERATOR not match in vessel service';
		}

		//echo $ress1->POD." | ".$ress2->POR." | ".$ress3->OPERATOR;die;

		//debux($data);die;
		if($data['CONT_TYPE'] == 'Tank'){
			$cont_type = 'TNK';
		}else{
			$cont_type = $data['CONT_TYPE'];
		}

		/*validasi iso code*/
		$data[CONT_HEIGHT] = str_replace(',', '.', $data[CONT_HEIGHT]);

		$query_iso = "SELECT * FROM M_ISO_CODE 
					  WHERE SIZE_ = '$data[CONT_SIZE]' 
					  AND TYPE_ = '$cont_type' 
					  AND H_ISO = '$data[CONT_HEIGHT]'";
		$ress_iso = $this->db->query($query_iso)->result();

		$new_iso_code = $data['ID_ISO_CODE'];
		$is_found 	  = 0;
		foreach ($ress_iso as $key => $value) {
			if($value->ISO_CODE  == $new_iso_code){
				$is_found = 1;
				break;
			}
		}

		if($is_found == 0){
			$msg_out = 'ISO CODE NOT VALID';
		}

		if(!empty($msg_out) || $msg_out != ''){
			return array(
					'success'=>false,
					'errors'=>$msg_out
				);
		}

		if($data['UNNO'] == "Autocomplete"){
			$data['UNNO'] = "";
		}

		if($data['IMDG'] == "Autocomplete"){
			$data['IMDG'] = "";
		}

		$param = array(
				array('name'=>':v_no_container', 'value'=>$data['NO_CONTAINER'], 'length'=>15),
				array('name'=>':v_point', 'value'=>$data['POINT'], 'length'=>10),
				array('name'=>':v_class', 'value'=>$data['ID_CLASS_CODE'], 'length'=>5),
				array('name'=>':v_cont_iso', 'value'=>$data['ID_ISO_CODE'], 'length'=>4),
				array('name'=>':v_cont_size', 'value'=>$data['CONT_SIZE'], 'length'=>5),
				array('name'=>':v_cont_type', 'value'=>$cont_type, 'length'=>10),
				array('name'=>':v_cont_height', 'value'=>$data['CONT_HEIGHT'], 'length'=>10),
				array('name'=>':v_id_operator', 'value'=>$data['ID_OPERATOR'], 'length'=>10),
				array('name'=>':v_id_commodity', 'value'=>$data['ID_COMMODITY'], 'length'=>10),
				array('name'=>':v_weight', 'value'=>$data['WEIGHT'], 'length'=>10),
				array('name'=>':v_temp', 'value'=>$data['TEMP'], 'length'=>10),
				array('name'=>':v_unno', 'value'=>$data['UNNO'], 'length'=>20),
				array('name'=>':v_imdg', 'value'=>$data['IMDG'], 'length'=>20),
				array('name'=>':v_seal_numb', 'value'=>$data['SEAL_NUMB'], 'length'=>10),
				array('name'=>':v_id_ves_voyage', 'value'=>$data['ID_VES_VOYAGE'], 'length'=>20),
				array('name'=>':v_id_pol', 'value'=>$data['ID_POL_VAL'], 'length'=>10),
				array('name'=>':v_id_pod', 'value'=>$data['ID_POD'], 'length'=>10),
				array('name'=>':v_id_por', 'value'=>$data['ID_POR'], 'length'=>10),
				array('name'=>':v_status_cont', 'value'=>$data['CONT_STATUS'], 'length'=>10),
				array('name'=>':v_tlflag', 'value'=>$data['TL_FLAG'], 'length'=>10),
				array('name'=>':v_oh', 'value'=>$data['OVER_HEIGHT'], 'length'=>10),
				array('name'=>':v_ow_l', 'value'=>$data['OVER_LEFT'], 'length'=>10),
				array('name'=>':v_ow_r', 'value'=>$data['OVER_RIGHT'], 'length'=>10),
				array('name'=>':v_ol_f', 'value'=>$data['OVER_FRONT'], 'length'=>10),
				array('name'=>':v_ol_b', 'value'=>$data['OVER_REAR'], 'length'=>10),
				array('name'=>':v_userid', 'value'=>$id_user, 'length'=>50),
				array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>500)
			);

		// debux($param);die;
		 
		$query = "begin PROC_SINGLE_CORRECTION(:v_no_container, :v_point, :v_class, :v_cont_iso, :v_cont_size, :v_cont_type, :v_cont_height, :v_id_operator, :v_id_commodity, :v_weight, :v_temp, :v_unno, :v_imdg, :v_seal_numb, :v_id_ves_voyage, :v_id_pol, :v_id_pod, :v_id_por, :v_status_cont, :v_tlflag, :v_oh, :v_ow_l, :v_ow_r, :v_ol_f, :v_ol_b, :v_userid, :v_msg_out); end;";
		
		$this->db->exec_bind_stored_procedure($query, $param);

		$sql_repo = $this->db->query("SELECT COUNT(*) AS JML_ FROM ITOS_REPO.M_STEVEDORING 
									  WHERE NO_CONTAINER = '$data[NO_CONTAINER]' 
									  AND POINT = '$data[POINT]'")->row();

		if($sql_repo->JML_ > 0){
			
			//$sts_cont = $data['CONT_STATUS'];
			if($data['CONT_STATUS'] == 'MTY'){
				$sts_cont = 'Empty';
			}elseif ($data['CONT_STATUS'] == 'FCL') {
				$sts_cont = 'Full';
			}else{
				$sts_cont = $data['CONT_STATUS'];
			}

			$sql_cek_iso_new = $this->db->query("SELECT ID_ISO_CODE FROM ITOS_OP.CON_LISTCONT WHERE NO_CONTAINER = '$data[NO_CONTAINER]'")->row();

			$sql_up_repo = "UPDATE ITOS_REPO.M_STEVEDORING 
							SET STATUS_CONT = '$sts_cont',
							ISO_CODE 		= '".$sql_cek_iso_new->ID_ISO_CODE."',
							GROSS 			= '$data[WEIGHT]',
							HEIGHT_CONT 	= '$data[CONT_HEIGHT]',
							WEIGHT 			= '$data[WEIGHT]'
							WHERE 
							NO_CONTAINER 	= '$data[NO_CONTAINER]' 
							AND POINT 		= '$data[POINT]'";
			//debux($sql_up_repo);
			$this->db->query($sql_up_repo);
		}

		if ($msg_out==''){
			return array(
						'success'=>true,
						'errors'=>$msg_out
					);
		}else{
			return array(
						'success'=>false,
						'errors'=>$msg_out
					);
		}
	}
	
	public function yard_placement_submit($no_container, $point, $id_op_status, $event, $user_id, $yard_position, $id_machine, $driver_id, $iditv=false){
		// penambahan untuk small vessel
//		$tofSV = "SELECT FL_TONGKANG, WEIGHT FROM CON_LISTCONT WHERE NO_CONTAINER = '".$no_container."' AND POINT =  '".$point."' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
//		$rsTOF = $this->db->query($tofSV);
//		$rowTOF = $rsTOF->row_array();
//		if($rowTOF['FL_TONGKANG'] == 'Y' and $rowTOF['WEIGHT'] == 0){
//			return array('F', 'Container dari small vessel dan belum ditimbang... harap timbang terlebih dahulu...');
//		}
		// penambahan untuk small vessel

		//return $id_machine;
		$status_flag = 'F';
		$message = '';
		$isProcess = TRUE;
		
		if ($iditv != false){
			$v_yt=$iditv;
		}else{
			$v_yt='';
		}
		
		if($event == 'O'){
		    $qry = "SELECT A.NO_CONTAINER,C.ID_CLASS_CODE,B.EVENT,
					    CASE WHEN (C.ID_CLASS_CODE = 'E' OR C.ID_CLASS_CODE = 'TE') AND B.EVENT = 'O' THEN C.YD_BLOCK_NAME || ' - ' || D.BAY_ || ' ' || D.DECK_HATCH || ' ' || ' LD'
					    WHEN (C.ID_CLASS_CODE = 'I' OR C.ID_CLASS_CODE = 'TI') AND B.EVENT = 'P' THEN C.GT_JS_BLOCK_NAME || ' - ' || E.BAY_ || ' ' || E.DECK_HATCH || ' ' || ' DS'
					    ELSE '' END AS LOCATION
			    FROM JOB_QUAY_MANAGER A
			    INNER JOIN JOB_YARD_MANAGER B
				    ON A.NO_CONTAINER = B.NO_CONTAINER AND a.STATUS_FLAG != B.STATUS_FLAG
				    AND CASE WHEN B.EVENT = 'O' THEN 'P' ELSE 'C' END = A.STATUS_FLAG 
				    AND CASE WHEN B.EVENT = 'O' THEN 'C' ELSE 'P' END = B.STATUS_FLAG
			    LEFT JOIN CON_LISTCONT C
				    ON A.NO_CONTAINER = C.NO_CONTAINER AND A.POINT = C.POINT
			    LEFT JOIN CON_OUTBOUND_SEQUENCE D
				    ON A.NO_CONTAINER = D.NO_CONTAINER AND A.POINT = D.POINT
			    LEFT JOIN CON_INBOUND_SEQUENCE E
				    ON A.NO_CONTAINER = E.NO_CONTAINER AND A.POINT = E.POINT
			    WHERE A.ID_MACHINE_ITV = '".$v_yt."'";
//			    echo '<pre>'.$qry.'</pre>';
		    $check_job_itv = $this->db->query($qry)->result_array();
//			    echo '<pre>'.$check_job_itv[0]['EVENT'].'</pre>';

		    if(count($check_job_itv) > 1){
			$isProcess = FALSE;
			$status_flag = 'F';
			$message = 'Container full';
		    }else if(count($check_job_itv) == 1){
			if($check_job_itv[0]['EVENT'] == 'P'){
			    $isProcess = FALSE;
			    $status_flag = 'F';
			    $message = 'Container has job for placement';
			}
		    }
		}
//			exit;
		if($isProcess){
			if ($iditv != false){
				$param = array(
				array('name'=>':no_container', 'value'=>$no_container, 'length'=>15),
				array('name'=>':point', 'value'=>$point, 'length'=>10),
				array('name'=>':id_op_status', 'value'=>$id_op_status, 'length'=>3),
				array('name'=>':event', 'value'=>$event, 'length'=>1),
				array('name'=>':user_id', 'value'=>$user_id, 'length'=>10),
				array('name'=>':driver_id', 'value'=>$driver_id, 'length'=>10),
				array('name'=>':id_block', 'value'=>$yard_position['BLOCK'], 'length'=>10),
				array('name'=>':block_', 'value'=>$yard_position['BLOCK_NAME'], 'length'=>10),
				array('name'=>':slot_', 'value'=>$yard_position['SLOT'], 'length'=>10),
				array('name'=>':row_', 'value'=>$yard_position['ROW'], 'length'=>10),
				array('name'=>':tier_', 'value'=>$yard_position['TIER'], 'length'=>10),
				array('name'=>':id_machine', 'value'=>$id_machine, 'length'=>10),
				array('name'=>':v_terminal', 'value'=> $this->gtools->terminal(), 'length'=>10),
				array('name'=>':v_yt', 'value'=> $v_yt, 'length'=>10),
				array('name'=>':status_flag', 'value'=>&$status_flag, 'length'=>1),
				array('name'=>':message', 'value'=>&$message, 'length'=>1000)
				);
			 // echo '<pre>';print_r($param);echo '</pre>';
			 // exit;
		
				$sql = "BEGIN PROC_JOB_YARD_COMPLETE_ITV(:no_container, :point, :id_op_status, :event, :user_id, :driver_id, :id_block, :block_, :slot_, :row_, :tier_, :id_machine, :v_terminal,  :v_yt, :status_flag, :message); END;";
			}else{
				$param = array(
				array('name'=>':no_container', 'value'=>$no_container, 'length'=>15),
				array('name'=>':point', 'value'=>$point, 'length'=>10),
				array('name'=>':id_op_status', 'value'=>$id_op_status, 'length'=>3),
				array('name'=>':event', 'value'=>$event, 'length'=>1),
				array('name'=>':user_id', 'value'=>$user_id, 'length'=>10),
				array('name'=>':driver_id', 'value'=>$driver_id, 'length'=>10),
				array('name'=>':id_block', 'value'=>$yard_position['BLOCK'], 'length'=>10),
				array('name'=>':block_', 'value'=>$yard_position['BLOCK_NAME'], 'length'=>10),
				array('name'=>':slot_', 'value'=>$yard_position['SLOT'], 'length'=>10),
				array('name'=>':row_', 'value'=>$yard_position['ROW'], 'length'=>10),
				array('name'=>':tier_', 'value'=>$yard_position['TIER'], 'length'=>10),
				array('name'=>':id_machine', 'value'=>$id_machine, 'length'=>10),
				array('name'=>':v_terminal', 'value'=> $this->gtools->terminal(), 'length'=>10),
				array('name'=>':status_flag', 'value'=>&$status_flag, 'length'=>1),
				array('name'=>':message', 'value'=>&$message, 'length'=>1000)
				);
	//		 echo '<pre>';print_r($param);echo '</pre>';
	//		 exit;
				$sql = "BEGIN PROC_JOB_YARD_COMPLETE(:no_container, :point, :id_op_status, :event, :user_id, :driver_id, :id_block, :block_, :slot_, :row_, :tier_, :id_machine, :v_terminal, :status_flag, :message); END;";
			}
				$this->db->exec_bind_stored_procedure($sql, $param);
		}
		    return array($status_flag, $message);
	}
	
	public function tally_confirm_submit($no_container, $point, $class, $location, $id_user, $driver_id, $id_machine, $id_machine_quay, $dmgpart, $dmg, $seal_num,$id_ves_voyage)
	{
		$v_out 			= 'NOT OK';
		$v_out_msg 		= '';

		// array('name'=>':location', 'value'=>$location[0].'-'.$location[1].'-'.$location[2], 'length'=>10),
		$param = array(
			array('name'=>':no_container', 'value'=>$no_container, 'length'=>15),
			array('name'=>':point', 'value'=>$point, 'length'=>10),
			array('name'=>':class', 'value'=>$class, 'length'=>3),
			array('name'=>':location', 'value'=>$location[0].'-'.$location[1].'-'.$location[2], 'length'=>10),
			array('name'=>':id_user', 'value'=>$id_user, 'length'=>10),
			array('name'=>':driver_id', 'value'=>$driver_id, 'length'=>10),
			array('name'=>':id_machine', 'value'=>$id_machine, 'length'=>5),
			array('name'=>':id_machine_quay', 'value'=>$id_machine_quay, 'length'=>5),
			array('name'=>':id_dmgpart', 'value'=>$dmgpart, 'length'=>5),
			array('name'=>':id_dmg', 'value'=>$dmg, 'length'=>5),
			array('name'=>':terminal', 'value'=>$this->gtools->terminal(), 'length'=>5),
			// array('name'=>':seal_num', 'value'=>$seal_num, 'length'=>50),
			array('name'=>':v_out', 'value'=>&$v_out, 'length'=>8),
			array('name'=>':v_out_msg', 'value'=>&$v_out_msg, 'length'=>500),
		);
		
		// print_r($param);
		// echo '<pre>param : ';print_r($param);echo '</pre>';
		// exit;
		// echo '<pre>';print_r($this->db->last_query());echo '</pre>';
		
		$sql 				= "BEGIN PROC_JOB_QUAY_COMPLETE(:no_container,:point,:class,:location,:id_user,:driver_id,:id_machine,:id_machine_quay,:id_dmgpart,:id_dmg,:terminal,:v_out,:v_out_msg); END;";

		//debux($param);die;
		$this->db->exec_bind_stored_procedure($sql, $param);


		/*update atb*/
		$id_class_code 	  	= $class; 
		$query_atb   	  	= $this->db->query("SELECT * FROM JOB_CONFIRM
											  WHERE ID_VES_VOYAGE = '".$id_ves_voyage."'
											  AND ACTIVITY = '".$id_class_code."'");
		$row_atb   	 	  	= $query_atb->row();
		$query_disch_load 	= "";

		if($id_class_code == 'I')
		{
			#inbound
			if($query_atb->num_rows()==1)
			{
				#update discharge commence
				$query_disch_load = "UPDATE VES_VOYAGE SET DISCHARGE_COMMENCE = SYSDATE WHERE ID_VES_VOYAGE = '$id_ves_voyage'";
			}
			else
			{
				#update discharge complete
				$query_disch_load = "UPDATE VES_VOYAGE SET DISCHARGE_COMPLETE = SYSDATE WHERE ID_VES_VOYAGE = '$id_ves_voyage'";
			}

		}else{
			#outbound
			if($query_atb->num_rows()==1){
				#update discharge commence
				$query_disch_load = "UPDATE VES_VOYAGE SET LOAD_COMMENCE = SYSDATE WHERE ID_VES_VOYAGE = '$id_ves_voyage'";
			}else{
				#update discharge complete
				$query_disch_load = "UPDATE VES_VOYAGE SET LOAD_COMPLETE = SYSDATE WHERE ID_VES_VOYAGE = '$id_ves_voyage'";
			}

		}
		$this->db->query($query_disch_load);
		/*end update atb*/

		/*start update qc_real and yc_real*/
		$query_mch  = $this->db->query("SELECT MCH_NAME 
									  FROM M_MACHINE 
									  WHERE ID_MACHINE = '$id_machine_quay'")->row();
		$qc_mch	   	= $query_mch->MCH_NAME;

		$query 	   	= "SELECT ID_VES_VOYAGE,WEIGHT,SEAL_NUMB,ITT_FLAG,ID_CLASS_CODE 
						FROM ITOS_OP.CON_LISTCONT 
						WHERE NO_CONTAINER = '$no_container' 
						AND POINT = '$point'";
		$result		= $this->db->query($query);
		$row		= $result->row_array();

		$id_ves_voyage 	= $row['ID_VES_VOYAGE'];
		$weight 		= $row['WEIGHT'];
		$seal_numb 		= $row['SEAL_NUMB'];	
		$itt_flag 		= $row['ITT_FLAG'];
		$id_class_code  = $row['ID_CLASS_CODE'];

		$query_mch = $this->db->query("SELECT 
										A.ID_MACHINE_ITV,
										B.MCH_NAME
										FROM JOB_YARD_MANAGER A
										LEFT JOIN M_MACHINE B ON A.ID_MACHINE = B.ID_MACHINE
										WHERE NO_CONTAINER = '$no_container'
										AND ID_VES_VOYAGE = '$id_ves_voyage'")->row();

		$query_up  = $this->db->query("UPDATE ITOS_OP.CON_LISTCONT 
								   SET QC_REAL = '".$qc_mch."',
								       YC_PLAN = '".$query_mch->MCH_NAME."' 
								   WHERE NO_CONTAINER = '".$no_container."' 
								   AND ID_VES_VOYAGE = '".$id_ves_voyage."'");
		/*end update qc_real and yc_real*/

		/*add kondisi untuk container transipmen*/
			if($v_out == 'OK' && ($class=='TI' || $class=='TE')){
				$query_ves 		= $this->db->query("SELECT VESSEL, VOYAGE_IN, VOYAGE_OUT, CALL_SIGN,OPERATOR_ID,OPERATOR_NAME 
													FROM ITOS_REPO.M_VSB_VOYAGE 
													WHERE UKKS = '$id_ves_voyage'")->row_array();
				$vessel_name 	= $query_ves['VESSEL'];
				$voy_in 		= $query_ves['VOYAGE_IN'];
				$voy_out 		= $query_ves['VOYAGE_OUT'];
				$call_sign 		= $query_ves['CALL_SIGN'];
				$operator 		= $query_ves['OPERATOR_ID'];
				$op_name 		= $query_ves['OPERATOR_NAME'];

				$query_cont     = $this->db->query("SELECT POINT,NO_CONTAINER,CONT_STATUS,ID_ISO_CODE,
													ID_CLASS_CODE,HAZARD,QC_REAL,
													CONT_HEIGHT,WEIGHT,TL_FLAG,ID_TERMINAL
													FROM ITOS_OP.CON_LISTCONT
													WHERE ID_VES_VOYAGE = '$id_ves_voyage'
													AND NO_CONTAINER = '$no_container'
													AND POINT = '$point'")->row_array();
				$point 			= $query_cont['POINT'];
				$no_container 	= $query_cont['NO_CONTAINER'];
				$cont_status 	= $query_cont['CONT_STATUS'];
				$id_iso_code 	= $query_cont['ID_ISO_CODE'];
				$id_class_code 	= $query_cont['ID_CLASS_CODE'];
				$hazard 		= $query_cont['HAZARD'];
				$date_send      = date('YmdHis');
				$qc_real 		= $query_cont['QC_REAL'];
				$cont_height 	= $query_cont['CONT_HEIGHT'];
				$weight 	   	= $query_cont['WEIGHT'];
				$tl_flag 	   	= $query_cont['TL_FLAG'];
				$id_terminal 	= $query_cont['ID_TERMINAL'];


				$query_delete 	= "DELETE FROM ITOS_REPO.M_STEVEDORING 
								   WHERE NO_CONTAINER = '".$no_container."' 
								   AND VESSEL 		  = '".$vessel_name."'
								   AND VOYAGE_IN 	  = '".$voy_in."'
								   AND VOYAGE_OUT 	  = '".$voy_out."'
								   AND E_I IN ('I','E')";
				$this->db->query($query_delete);

				$sql_trans_repo = "INSERT INTO ITOS_REPO.M_STEVEDORING(
										VESSEL,
										VOYAGE_IN,
										VOYAGE_OUT,
										POINT,
										CALL_SIGN,
										OPERATOR_ID,
										OPERATOR_NAME,
										NO_CONTAINER,
										STATUS_CONT,
										ISO_CODE,
										E_I,
										HZ,
										ALAT,
										DATE_SEND,
										FLAG_SEND,
										HEIGHT_CONT,
										WEIGHT,
										TL_FLAG,
										ID_TERMINAL
										)
								VALUES (
										'$vessel_name', --VESSEL
										'$voy_in', -- VOYAGE_IN
										'$voy_out', -- VOYAGE_OUT
										'$point', -- POINT
										'$call_sign', -- CALL_SIGN
										'$operator', -- OPERATOR_ID
										'$op_name', -- OPERATOR_NAME
										'$no_container', -- NO_CONTAINER
										'$cont_status', -- STATUS_CONT
										'$id_iso_code', -- ISO_CODE
										'$id_class_code', -- E_I
										'$hazard', -- HZ
										'$qc_real', --ALAT
										'$date_send', --DATE_SEND
										'1', --FLAG_SEND
										'$cont_height', --HEIGHT_CONT
										'$weight', --WEIGHT
										'$tl_flag', --TL_FLAG
										'$id_terminal' --ID_TERMINAL
								)";
					$sql_trans_op = "INSERT INTO ITOS_OP.M_STEVEDORING(
										VESSEL,
										VOYAGE_IN,
										VOYAGE_OUT,
										POINT,
										CALL_SIGN,
										OPERATOR_ID,
										OPERATOR_NAME,
										NO_CONTAINER,
										STATUS_CONT,
										ISO_CODE,
										E_I,
										HZ,
										ALAT,
										DATE_SEND,
										FLAG_SEND,
										HEIGHT_CONT,
										WEIGHT,
										--TL_FLAG,
										ID_TERMINAL
										)
								VALUES (
										'$vessel_name', --VESSEL
										'$voy_in', -- VOYAGE_IN
										'$voy_out', -- VOYAGE_OUT
										'$point', -- POINT
										'$call_sign', -- CALL_SIGN
										'$operator', -- OPERATOR_ID
										'$op_name', -- OPERATOR_NAME
										'$no_container', -- NO_CONTAINER
										'$cont_status', -- STATUS_CONT
										'$id_iso_code', -- ISO_CODE
										'$id_class_code', -- E_I
										'$hazard', -- HZ
										'$qc_real', --ALAT
										'$date_send', --DATE_SEND
										'1', --FLAG_SEND
										'$cont_height', --HEIGHT_CONT
										'$weight', --WEIGHT
										--'$tl_flag', --TL_FLAG
										'$id_terminal' --ID_TERMINAL
								)";
				$response_trans 	= $this->db->query($sql_trans_repo);
				$response_trans_1	= $this->db->query($sql_trans_op);
			}
			/*end kondisi transipment*/

			$query = " SELECT COUNT(*) TOTAL FROM CON_WRONG_TYPE_INBOUND_STG WHERE NO_CONTAINER = '$no_container' AND POINT = '$point'";
			$rs			= $this->db->query($query);
			$data 		= $rs->row_array();
		
			if($data[TOTAL] < 1){
				$query = "INSERT INTO CON_WRONG_TYPE_INBOUND_STG(NO_CONTAINER,POINT,STATUS) VALUES
				('$no_container','$point','$wrong_type')";
				$this->db->query($query);
			} else {
				$query = "UPDATE CON_WRONG_TYPE_INBOUND_STG SET STATUS = '$wrong_type'
				WHERE NO_CONTAINER = '$no_container' AND POINT = '$point'";
				$this->db->query($query);
			}

		
		if ($v_out == 'OK'){
			$v_out = 'S';
		}
		
		return array($v_out, $v_out_msg);
	}
	
	public function save_disable_container($data,$id_user){
		$param = array(
				array('name'=>':v_no_container', 'value'=>$data['NO_CONTAINER'], 'length'=>15),
				array('name'=>':v_point', 'value'=>$data['POINT'], 'length'=>10),
				array('name'=>':v_remarks', 'value'=>$data['REMARKS'], 'length'=>500),
				array('name'=>':v_userid', 'value'=>$id_user, 'length'=>50),
				array('name'=>':v_terminal', 'value'=> $this->gtools->terminal(), 'length'=>50),
				array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>500)
			);
		// print_r($param);
		$query = "BEGIN PROC_DISABLE_CONTAINER(:v_no_container, :v_point, :v_remarks, :v_userid,:v_terminal, :v_msg_out); end;";
		// echo $query;die;
		$this->db->exec_bind_stored_procedure($query, $param);
		if ($msg_out==''){
			return array(
						'success'=>true,
						'errors'=>$msg_out
					);
		}else{
			return array(
						'success'=>false,
						'errors'=>$msg_out
					);
		}
	}
	
	public function get_data_multiple_correction_list($container_list=false, $paging=false, $sort=false, $filters=false){
		$q_in_con = '';
		if ($container_list){
			$q_in_con = " AND A.NO_CONTAINER IN (".$container_list.") ";
		}
		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM CON_LISTCONT A
						WHERE A.ACTIVE='Y' AND ID_TERMINAL='".$this->gtools->terminal()."' $q_in_con";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND A.ACTIVE='Y' $q_in_con";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case'NO_CONTAINER'	: $field = "A.".$field; break;
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													A.ID_VES_VOYAGE,
													A.ID_POD,
													A.ID_POL,
													A.ID_POR,
													A.ID_ISO_CODE,
													A.ID_CLASS_CODE,
													A.ID_OPERATOR,
													A.CONT_STATUS,
													A.TL_FLAG,
													A.ID_OP_STATUS,
													A.OVER_HEIGHT, 
													A.OVER_RIGHT, 
													A.OVER_LEFT, 
													A.OVER_FRONT, 
													A.OVER_REAR,
													A.WEIGHT,
													A.SEAL_NUMB
										FROM 
											CON_LISTCONT A
										$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."'
										$qSort) V
							) B
						$qPaging";
		#print $query;die;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		for ($i=0; $i<sizeof($container_list); $i++){
			if ($container_list[$i]['ID_CLASS_CODE']=='E'){
				$container_list[$i]['EDIT_VESSEL'] = 1;
				$container_list[$i]['EDIT_TL'] = 0;
			}else if ($container_list[$i]['ID_CLASS_CODE']=='I' && $container_list[$i]['ID_OP_STATUS']=='BPL'){
				$container_list[$i]['EDIT_VESSEL'] = 0;
				$container_list[$i]['EDIT_TL'] = 1;
			}else{
				$container_list[$i]['EDIT_VESSEL'] = 0;
				$container_list[$i]['EDIT_TL'] = 0;
			}
		}
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function save_multiple_correction($no_container, $point, $id_user, $data){

		$msg_out = '';

		$query  	  = "SELECT ID_CLASS_CODE,ID_OP_STATUS,ID_VES_VOYAGE FROM CON_LISTCONT WHERE NO_CONTAINER = '".$no_container."' AND POINT = '".$point."' AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$row 		  = $this->db->query($query)->row();
		$id_op_status = $row->ID_OP_STATUS;

		$text_message = "";
		if($id_op_status=='SLY'){
			$text_message = "Container Already Loaded";
		}elseif($id_op_status=='OYS'){
			$text_message = "Container Onchasis";
		}elseif($id_op_status=='YSY'){
			$text_message = "Container Stacking";
		}else{
			$text_message = "Error";
		}

		if($row->ID_CLASS_CODE=='I'){
			#container inbound
			$validasi_req = $this->container->validasi_req_sp2($no_container, $point);
			if($validasi_req>0){
				$text_message = 'Container Already request';
				return array('F',$text_message);
			}

			if($id_op_status=='YSY'){ #SDY
				return array('F',$text_message);
			}

		}else{
			#container outbound
			if($id_op_status=='SLY' || $id_op_status=='OYS'){
				return array('F',$text_message);
			}
		}


		foreach ($data as $key => $value) {
			if($key=='OVER_HEIGHT' || $key=='OVER_RIGHT' || $key=='OVER_LEFT' || $key=='OVER_FRONT' || $key=='OVER_REAR'){
				$field[$key] = (int)$value;
			}else{
				$field[$key] = $value;
			}
		}

		$this->db->where('NO_CONTAINER', $no_container);
		$this->db->update('CON_LISTCONT', $field);

		$param = array(
				array('name'=>':v_no_container', 	'value'=>$no_container, 		'length'=>15),
				array('name'=>':v_point', 			'value'=>$point, 				'length'=>10),
				array('name'=>':v_id_ves_voyage', 	'value'=>$data['ID_VES_VOYAGE'],'length'=>20),
				array('name'=>':v_over_height', 	'value'=>$data['OVER_HEIGHT'], 	'length'=>10),
				array('name'=>':v_over_right', 		'value'=>$data['OVER_RIGHT'], 	'length'=>10),
				array('name'=>':v_over_left', 		'value'=>$data['OVER_LEFT'], 	'length'=>10),
				array('name'=>':v_over_front', 		'value'=>$data['OVER_FRONT'], 	'length'=>10),
				array('name'=>':v_over_rear', 		'value'=>$data['OVER_REAR'], 	'length'=>10),
				array('name'=>':v_tlflag', 		'value'=>$data['TL_FLAG'], 		'length'=>10),
				array('name'=>':v_userid', 		'value'=>$id_user, 				'length'=>50),
				array('name'=>':v_terminal', 		'value'=>$this->gtools->terminal(), 				'length'=>50),
				array('name'=>':v_msg_out', 		'value'=>&$msg_out, 			'length'=>500)
			);
	 	#print_r($param);
		$query = "begin PROC_MULTIPLE_CORRECTION(:v_no_container, :v_point, :v_id_ves_voyage,:v_over_height,:v_over_right,:v_over_left,:v_over_front,:v_over_rear,:v_tlflag, :v_userid, :v_terminal, :v_msg_out); end;";
		//echo $query;die;
		$this->db->exec_bind_stored_procedure($query, $param);
		if ($msg_out==''){
			return array('S',$msg_out);
		}else{
			return array('F',$text_message);
		}
	}
	
	public function get_data_multiple_correction_tl($id_ves_voyage, $container_list=false, $paging=false, $sort=false, $filters=false){
		$q_in_con = '';
		if ($container_list){
			$q_in_con = " AND A.NO_CONTAINER IN (".$container_list.") ";
		}
		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM CON_LISTCONT A
						WHERE A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_OP_STATUS='BPL' AND A.ID_CLASS_CODE='I' AND A.TL_FLAG='N' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') AND (ITT_FLAG = 'N' OR ITT_FLAG IS NULL) AND ID_TERMINAL='".$this->gtools->terminal()."'
						$q_in_con";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		/*if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}*/
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_OP_STATUS='BPL' AND A.ID_CLASS_CODE='I' AND A.TL_FLAG='N' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') AND (ITT_FLAG = 'N' OR ITT_FLAG IS NULL)
						$q_in_con";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case'NO_CONTAINER'	: $field = "A.".$field; break;
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													A.ID_ISO_CODE,
													A.ID_CLASS_CODE,
													A.ID_OPERATOR,
													A.CONT_STATUS
										FROM 
											CON_LISTCONT A
										$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."'
										$qSort) V
							) B
						$qPaging";
//		echo '<pre>'; print $query; echo '</pre>';exit;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function save_multiple_correction_tl($data,$id_user){
		$msg_complete = '';
		$cont_list = '';
		$id_ves_voyage = $_POST['ID_VES_VOYAGE'];
		$container_data = json_decode($data['container_data']);
	
		if ($msg_out==''){
			for ($i=0;$i<sizeof($container_data);$i++){
				$no_container = $container_data[$i]->NO_CONTAINER;
				$point = $container_data[$i]->POINT;
				// print_r($param);
				$query = "UPDATE CON_LISTCONT SET TL_FLAG='Y' WHERE NO_CONTAINER = '$no_container' AND POINT = '$point' AND ID_OP_STATUS='BPL' AND ID_CLASS_CODE='I' AND TL_FLAG='N' AND (NO_REQUEST IS NULL OR NO_REQUEST='') AND (ITT_FLAG = 'N' OR ITT_FLAG IS NULL) AND ID_TERMINAL = '".$this->gtools->terminal()."'";
				//echo $query;die;
				$this->db->query($query);
				$query = "SELECT * FROM CON_INBOUND_SEQUENCE WHERE NO_CONTAINER='$no_container' AND ID_VES_VOYAGE='$id_ves_voyage' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
				$cek = $this->db->query($query)->result_array();
				if (count($cek)>0){
					$cont_list .= $cont_list != ''? ',' .$no_container : $no_container;
				}
			}
		}

		if($cont_list != ''){
			$msg_complete = 'Container : '.$cont_list.' has been set sequence'; 
		}
		if($this->db->trans_complete()){
			return array(
						'success'=>true,
						'errors'=> 'Save success. '.$msg_complete
					);
		}else{
			return array(
						'success'=>false,
						'errors'=> 'Save failed'
					);
		}
	}
	
	public function get_data_transhipment_container_list($id_ves_voyage, $container_list=false, $paging=false, $sort=false, $filters=false){
		$q_in_con = '';
		if ($container_list){
			$q_in_con = " AND A.NO_CONTAINER IN (".$container_list.") ";
		}
		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM CON_LISTCONT A
						WHERE A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_OP_STATUS='BPL' AND A.ID_CLASS_CODE IN ('I','TI') AND A.TL_FLAG='N' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') AND ID_TERMINAL='".$this->gtools->terminal()."' $q_in_con";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		/*if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}*/
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_OP_STATUS IN ('BPL','SDG','YSY','YYY','TRS') AND A.ID_CLASS_CODE IN ('I','TI') AND A.ITT_FLAG = 'N' AND A.TL_FLAG='N' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') $q_in_con";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case'NO_CONTAINER'	: $field = "A.".$field; break;
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													A.ID_ISO_CODE,
													A.ID_CLASS_CODE,
													A.ID_OPERATOR,
													A.CONT_STATUS
										FROM 
											CON_LISTCONT A
						LEFT JOIN (
							SELECT H.OLD_ID_VES_VOYAGE,H.ID_VES_VOYAGE, CO.NO_CONTAINER,D.POINT,D.LOAD_POINT FROM CON_OUTBOUND_SEQUENCE CO 
							JOIN CON_TRANSHIPMENT_H H ON CO.ID_VES_VOYAGE = H.ID_VES_VOYAGE
							JOIN CON_TRANSHIPMENT_D D ON H.ID_TRANSHIPMENT = D.ID_TRANSHIPMENT AND CO.NO_CONTAINER = D.NO_CONTAINER AND CO.POINT = D.LOAD_POINT 
							JOIN CON_LISTCONT C ON D.NO_CONTAINER = C.NO_CONTAINER AND D.LOAD_POINT = C.POINT
							WHERE ID_CLASS_CODE = 'TE'
						) B ON A.NO_CONTAINER = B.NO_CONTAINER AND A.POINT = B.POINT AND A.ID_VES_VOYAGE = B.OLD_ID_VES_VOYAGE
										$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND B.NO_CONTAINER IS NULL
										$qSort) V
							) B
						$qPaging";
//		 print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function save_transhipment_container($data,$id_user){
		$msg_complete 		= '';
		$old_id_ves_voyage 	= $_POST['OLD_ID_VES_VOYAGE'];
		$id_ves_voyage 		= $_POST['ID_VES_VOYAGE'];
		$via_gate 			= $_POST['VIA_GATE'];
		$doc_number 		= $_POST['DOC_NUMBER'];
		$id_pod 			= $_POST['ID_POD'];
		$id_fpod 			= $_POST['ID_FPOD'];
		$container_data 	= json_decode($data['container_data']);
		$total_size 		= 0;
		$size 				= 0;

		for ($i=0;$i<sizeof($container_data);$i++){
			$size = $container_data[$i]->CONT_SIZE;		
			if($size==20 || $size==21){
				$total_size += 1;
			}else{
				$total_size += 2;					
			}
		}

		$query_count = "SELECT A.BOOKING_STACK AS REQUEST_BOOKING_STACK,
		(SELECT COUNT(*) FROM CON_LISTCONT C WHERE C.ID_OP_STATUS <> 'DIS' AND C.ID_CLASS_CODE IN ('E','TE') AND C.ID_VES_VOYAGE = A.ID_VES_VOYAGE AND CONT_SIZE < 40 AND C.ID_TERMINAL = A.ID_TERMINAL) +
		(SELECT COUNT(*)*2 FROM CON_LISTCONT C WHERE C.ID_OP_STATUS <> 'DIS' AND C.ID_CLASS_CODE IN ('E','TE') AND C.ID_VES_VOYAGE = A.ID_VES_VOYAGE AND CONT_SIZE >= 40 AND ID_TERMINAL = A.ID_TERMINAL) + 
		(SELECT COUNT(*) FROM (
			SELECT NO_CONTAINER,MAX(POINT) POINT,ID_VES_VOYAGE,CONT_SIZE,ID_TERMINAL FROM CON_LISTCONT WHERE ID_CLASS_CODE IN ('S1','S2') AND ID_OP_STATUS <> 'DIS' AND CONT_SIZE < 40 
			GROUP BY NO_CONTAINER,ID_VES_VOYAGE,CONT_SIZE,ID_TERMINAL
		) C WHERE ID_VES_VOYAGE = A.ID_VES_VOYAGE AND ID_TERMINAL = A.ID_TERMINAL) + 
		(SELECT COUNT(*) FROM (
			SELECT NO_CONTAINER,MAX(POINT) POINT,ID_VES_VOYAGE,CONT_SIZE,ID_TERMINAL FROM CON_LISTCONT WHERE ID_CLASS_CODE IN ('S1','S2') AND ID_OP_STATUS <> 'DIS' AND CONT_SIZE >= 40 
			GROUP BY NO_CONTAINER,ID_VES_VOYAGE,CONT_SIZE,ID_TERMINAL
		) C WHERE ID_VES_VOYAGE = A.ID_VES_VOYAGE AND ID_TERMINAL = A.ID_TERMINAL) AS BOOKING_COUNTER
		FROM VES_VOYAGE A 
		WHERE A.ID_VES_VOYAGE = '$id_ves_voyage' AND ID_TERMINAL = '".$this->gtools->terminal()."'";

		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total_book = $row['REQUEST_BOOKING_STACK'];
		$total_counter = $row['BOOKING_COUNTER'];
//		echo '<pre>total_book : '.$total_book.'</pre>';
//		echo '<pre>total_counter : '.($total_counter+$total_size).'</pre>';
//		exit;
		if($total_book<($total_counter+$total_size)){
			return array(
						'success'=>false,
						'errors'=>"Sudah mencapai booking limit, booking=$total_book,counter=$total_counter,transhipment=$total_size"
					);
		}
		
		$param = array(
			array('name'=>':v_old_id_ves_voyage', 'value'=>$old_id_ves_voyage, 'length'=>15),
			array('name'=>':v_id_ves_voyage', 'value'=>$id_ves_voyage, 'length'=>15),
			array('name'=>':v_via_gate', 'value'=>$via_gate, 'length'=>1),
			array('name'=>':v_doc_number', 'value'=>$doc_number, 'length'=>100),
			array('name'=>':v_userid', 'value'=>$id_user, 'length'=>50),
			array('name'=>':v_id_transhipment', 'value'=>&$id_transhipment, 'length'=>50),
			array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>500)
		);
		// print_r($param);
		$query = "BEGIN PROC_TRANSHIPMENT_CONT_HEADER(:v_old_id_ves_voyage, :v_id_ves_voyage, :v_via_gate, :v_doc_number, :v_userid, :v_id_transhipment, :v_msg_out); end;";
		// echo $query;die;
		$this->db->exec_bind_stored_procedure($query, $param);
		$msg_complete = $msg_out;
		if ($msg_out==''){
			for ($i=0;$i<sizeof($container_data);$i++){
				$no_container = $container_data[$i]->NO_CONTAINER;
				$point = $container_data[$i]->POINT;
				
				//cek if container already set to transhipment
				
				$query = "SELECT A.*,H.OLD_ID_VES_VOYAGE,B.ID_OP_STATUS,H.ID_VES_VOYAGE 
					    FROM CON_TRANSHIPMENT_D A
					    LEFT JOIN CON_TRANSHIPMENT_H H ON A.ID_TRANSHIPMENT = H.ID_TRANSHIPMENT
					    JOIN CON_LISTCONT B ON A.NO_CONTAINER = B.NO_CONTAINER AND A.LOAD_POINT = B.POINT
					    WHERE B.ID_OP_STATUS NOT IN ('DIS','SLY','OYS') AND H.OLD_ID_VES_VOYAGE = '$old_id_ves_voyage' AND A.NO_CONTAINER = '$no_container' AND A.POINT='$point'";
//				echo '<pre>'.$query.'</pre>';
				$rs = $this->db->query($query)->result_array();
//				    if(count($rs) > 1){
				$qry_del1 = "DELETE FROM CON_TRANSHIPMENT_D WHERE ID_TRANSHIPMENT='".$rs[0]['ID_TRANSHIPMENT']."' AND NO_CONTAINER='$no_container' AND POINT='$point'";
//				echo '<pre>'.$qry_del1.'</pre>';
				$this->db->query($qry_del1);
				$qry_del2 = "DELETE FROM CON_LISTCONT WHERE NO_CONTAINER='$no_container' AND POINT='".$rs[0]['LOAD_POINT']."' AND ID_VES_VOYAGE='".$rs[0]['ID_VES_VOYAGE']."'";
//				echo '<pre>'.$qry_del2.'</pre>';
				$this->db->query($qry_del2);
				$qry_del3 = "DELETE FROM ITOS_REPO.M_CYC_CONTAINER WHERE NO_CONTAINER='$no_container' AND POINT='".$rs[0]['LOAD_POINT']."'";
//				echo '<pre>'.$qry_del3.'</pre>';
				$this->db->query($qry_del3);
					
//				    }
				
				$param = array(
					array('name'=>':v_no_container', 'value'=>$no_container, 'length'=>15),
					array('name'=>':v_point', 'value'=>$point, 'length'=>10),
					array('name'=>':v_id_transhipment', 'value'=>$id_transhipment, 'length'=>50),
					array('name'=>':v_terminal', 'value'=>$this->gtools->terminal(), 'length'=>50),
					array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>500)
				);
				// print_r($param);
				$query = "BEGIN PROC_TRANSHIPMENT_CONT_DETAIL(:v_no_container, :v_point, :v_id_transhipment, :v_terminal,:v_msg_out); end;";
				// echo $query;die;
				$this->db->exec_bind_stored_procedure($query, $param);
				
				$query_max = "SELECT MAX (POINT) MAX_TOTAL FROM CON_LISTCONT WHERE NO_CONTAINER = '$no_container' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
				$rs_max = $this->db->query($query_max);
				$row_max = $rs_max->row_array();
				$total_max = $row_max['MAX_TOTAL'];
				
				$query 	= "UPDATE CON_LISTCONT
							SET ID_POD = '$id_pod',
							ID_POR = '$id_fpod',
							ID_POL = 'IDJKT'
							WHERE NO_CONTAINER = '$no_container' AND POINT = '$total_max' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
				$rs 	= $this->db->query($query);

				

				/*$query_ves 		= $this->db->query("SELECT VESSEL, VOYAGE_IN, VOYAGE_OUT, CALL_SIGN,OPERATOR_ID,OPERATOR_NAME 
													FROM ITOS_REPO.M_VSB_VOYAGE 
													WHERE UKKS = '$id_ves_voyage'")->row_array();
				$vessel_name 	= $query_ves['VESSEL'];
				$voy_in 		= $query_ves['VOYAGE_IN'];
				$voy_out 		= $query_ves['VOYAGE_OUT'];
				$call_sign 		= $query_ves['CALL_SIGN'];
				$operator 		= $query_ves['OPERATOR_ID'];
				$op_name 		= $query_ves['OPERATOR_NAME'];

				$query_cont     = $this->db->query("SELECT POINT,NO_CONTAINER,CONT_STATUS,ID_ISO_CODE,ID_CLASS_CODE,HAZARD,QC_REAL,CONT_HEIGHT,WEIGHT,TL_FLAG,ID_TERMINAL
													FROM ITOS_OP.CON_LISTCONT
													WHERE ID_VES_VOYAGE = '$id_ves_voyage'
													AND NO_CONTAINER = '$no_container'")->row_array();
				$point 			= $query_cont['POINT'];
				$no_container 	= $query_cont['NO_CONTAINER'];
				$cont_status 	= $query_cont['CONT_STATUS'];
				$id_iso_code 	= $query_cont['ID_ISO_CODE'];
				$id_class_code 	= $query_cont['ID_CLASS_CODE'];
				$hazard 		= $query_cont['HAZARD'];
				$date_send      = date('YmdHis');
				$qc_real 		= $query_cont['QC_REAL'];
				$cont_height 	= $query_cont['CONT_HEIGHT'];
				$weight 	   	= $query_cont['WEIGHT'];
				$tl_flag 	   	= $query_cont['TL_FLAG'];
				$id_terminal 	= $query_cont['ID_TERMINAL'];

				$sql_trans      = "INSERT INTO ITOS_REPO.M_STEVEDORING(
										VESSEL,
										VOYAGE_IN,
										VOYAGE_OUT,
										POINT,
										CALL_SIGN,
										OPERATOR_ID,
										OPERATOR_NAME,
										NO_CONTAINER,
										STATUS_CONT,
										ISO_CODE,
										E_I,
										HZ,
										ALAT,
										DATE_SEND,
										FLAG_SEND,
										HEIGHT_CONT,
										WEIGHT,
										TL_FLAG,
										ID_TERMINAL
										)
								VALUES (
									'$vessel_name', --VESSEL
									'$voy_in', -- VOYAGE_IN
									'$voy_out', -- VOYAGE_OUT
									'$point', -- POINT
									'$call_sign', -- CALL_SIGN
									'$operator', -- OPERATOR_ID
									'$op_name', -- OPERATOR_NAME
									'$no_container', -- NO_CONTAINER
									'$cont_status', -- STATUS_CONT
									'$id_iso_code', -- ISO_CODE
									'T', -- E_I
									'$hazard', -- HZ
									'$qc_real', --ALAT
									'$date_send', --DATE_SEND
									'1', --FLAG_SEND
									'$cont_height', --HEIGHT_CONT
									'$weight', --WEIGHT
									'$tl_flag', --TL_FLAG
									'$id_terminal' --ID_TERMINAL
								)";
				$response_trans = $this->db->query($sql_trans);*/

				
				if ($msg_out!=''){
					$msg_complete .= $no_container." FAILED ".$msg_out."<br/>";
				}
			}
		}
		if ($msg_complete==''){
			return array(
						'success'=>true,
						'errors'=>$msg_complete
					);
		}else{
			return array(
						'success'=>false,
						'errors'=>$msg_complete
					);
		}
	}
	
	public function get_data_itt_container_list($id_ves_voyage, $container_list=false, $paging=false, $sort=false, $filters=false){
		$q_in_con = '';
		if ($container_list){
			$q_in_con = " AND A.NO_CONTAINER IN (".$container_list.") ";
		}
		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM CON_LISTCONT A
						WHERE A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_CLASS_CODE='I' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') AND A.ITT_FLAG='N' AND A.ID_TERMINAL='".$this->gtools->terminal()."' $q_in_con";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		/*if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}*/
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_CLASS_CODE='I' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') AND A.ITT_FLAG='N' $q_in_con";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case'NO_CONTAINER'	: $field = "A.".$field; break;
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													A.ID_ISO_CODE,
													A.ID_CLASS_CODE,
													A.ID_OPERATOR,
													A.CONT_STATUS
										FROM 
											CON_LISTCONT A
										$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."'
										$qSort) V
							) B
						$qPaging";
		// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function save_itt_container($data,$id_user){
		$msg_complete = '';
		$id_ves_voyage = $_POST['ID_VES_VOYAGE'];
		$id_yard_lini2 = $_POST['ID_YARD_LINI2'];
		$via_yard = $_POST['VIA_YARD'];
		$container_data = json_decode($data['container_data']);
		// print $id_ves_voyage.'<br/>';
		// print_r($container_data);
		$param = array(
			array('name'=>':v_id_ves_voyage', 'value'=>$id_ves_voyage, 'length'=>15),
			array('name'=>':v_id_yard_lini2', 'value'=>$id_yard_lini2, 'length'=>4),
			array('name'=>':v_via_yard', 'value'=>$via_yard, 'length'=>1),
			array('name'=>':v_userid', 'value'=>$id_user, 'length'=>50),
			array('name'=>':v_id_itt', 'value'=>&$id_itt, 'length'=>50),
			array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>500)
		);
		// print_r($param);
		$query = "BEGIN PROC_ITT_CONT_HEADER(:v_id_ves_voyage, :v_id_yard_lini2, :v_via_yard, :v_userid, :v_id_itt, :v_msg_out); end;";
		// echo $query;die;
		$this->db->exec_bind_stored_procedure($query, $param);
		$msg_complete = $msg_out;
		if ($msg_out==''){
			for ($i=0;$i<sizeof($container_data);$i++){
				$no_container = $container_data[$i]->NO_CONTAINER;
				$point = $container_data[$i]->POINT;
				$param = array(
					array('name'=>':v_no_container', 'value'=>$no_container, 'length'=>15),
					array('name'=>':v_point', 'value'=>$point, 'length'=>10),
					array('name'=>':v_userid', 'value'=>$id_user, 'length'=>50),
					array('name'=>':v_id_itt', 'value'=>$id_itt, 'length'=>50),
					array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>500)
				);
				// print_r($param);
				$query = "BEGIN PROC_ITT_CONT_DETAIL(:v_no_container, :v_point, :v_userid, :v_id_itt, :v_msg_out); end;";
				// echo $query;die;
				$this->db->exec_bind_stored_procedure($query, $param);
				if ($msg_out!=''){
					$msg_complete .= $no_container." FAILED ".$msg_out."<br/>";
				}
			}
		}
		if ($msg_complete==''){
			return array(
						'success'=>true,
						'errors'=>$msg_complete
					);
		}else{
			return array(
						'success'=>false,
						'errors'=>$msg_complete
					);
		}
	}
	
	public function rename_category_plan($category_id, $category_name){
		$param = array($category_name, $category_id, $this->gtools->terminal());
		
		$this->db->trans_start();
		
		$query 	= "UPDATE M_PLAN_CATEGORY_H
					SET CATEGORY_NAME = ?
					WHERE ID_CATEGORY = ? AND ID_TERMINAL = ?";
		$rs 	= $this->db->query($query, $param);
		
		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function get_data_cancel_itt_container_list($id_ves_voyage, $container_list=false, $paging=false, $sort=false, $filters=false){
		$q_in_con = '';
		if ($container_list){
			$q_in_con = " AND A.NO_CONTAINER IN (".$container_list.") ";
		}
		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM CON_LISTCONT A
						INNER JOIN JOB_GATE_MANAGER C
						ON A.NO_CONTAINER=C.NO_CONTAINER AND A.POINT=C.POINT
						WHERE A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ITT_FLAG='Y' AND (GT_DATE_OUT IS NULL OR GT_DATE_OUT='') AND C.STATUS_FLAG='G' AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_TERMINAL='".$this->gtools->terminal()."' $q_in_con";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		/*if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}*/
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ITT_FLAG='Y' AND (GT_DATE_OUT IS NULL OR GT_DATE_OUT='') AND C.STATUS_FLAG='G' $q_in_con";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case'NO_CONTAINER'	: $field = "A.".$field; break;
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													A.ID_ISO_CODE,
													A.ID_CLASS_CODE,
													A.ID_OPERATOR,
													A.CONT_STATUS
										FROM 
											CON_LISTCONT A
											INNER JOIN JOB_GATE_MANAGER C
											ON A.NO_CONTAINER=C.NO_CONTAINER AND A.POINT=C.POINT
										$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_TERMINAL='".$this->gtools->terminal()."'
										$qSort) V
							) B
						$qPaging";
		// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function cancel_itt_container($no_container,$point){
		$param = array(
			array('name'=>':v_no_container', 'value'=>$no_container, 'length'=>15),
			array('name'=>':v_point', 'value'=>$point, 'length'=>10),
			array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>500)
		);
		// print_r($param);
		$query = "BEGIN PROC_CANCEL_ITT_CONT(:v_no_container, :v_point, :v_msg_out); end;";
		// echo $query;die;
		$this->db->exec_bind_stored_procedure($query, $param);
		$flag_status = '';
		if ($msg_out==''){
			$flag_status = 'S';
		}else{
			$flag_status = 'F';
		}
		
		return array($flag_status, $msg_out);
	}
	
	public function get_data_loading_cancel_container_list($container_list=false, $paging=false, $sort=false, $filters=false){
		$q_in_con = '';
		if ($container_list){
			$q_in_con = " AND A.NO_CONTAINER IN (".$container_list.") ";
		}
		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM JOB_PLACEMENT A
						WHERE A.ID_CLASS_CODE='E' AND ID_TERMINAL='".$this->gtools->terminal()."' $q_in_con";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		/*if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}*/
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND A.ID_CLASS_CODE='E' $q_in_con";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case'NO_CONTAINER'	: $field = "A.".$field; break;
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													C.ID_ISO_CODE,
													A.ID_CLASS_CODE,
													C.ID_OPERATOR,
													C.CONT_STATUS
										FROM 
											JOB_PLACEMENT A
											INNER JOIN CON_LISTCONT C
											ON A.NO_CONTAINER=C.NO_CONTAINER AND A.POINT=C.POINT
										$qWhere AND C.ID_TERMINAL='".$this->gtools->terminal()."' AND A.ID_TERMINAL='".$this->gtools->terminal()."'
										$qSort) V
							) B
						$qPaging";
		// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function save_loading_cancel_container($data,$id_user){
		$msg_complete = '';
		$id_ves_voyage = $_POST['ID_VES_VOYAGE'];
		$doc_number = $_POST['DOC_NUMBER'];
		$container_data = json_decode($data['container_data']);
		$param = array(
			array('name'=>':v_id_ves_voyage', 'value'=>$id_ves_voyage, 'length'=>15),
			array('name'=>':v_doc_number', 'value'=>$doc_number, 'length'=>100),
			array('name'=>':v_userid', 'value'=>$id_user, 'length'=>50),
			array('name'=>':v_id_loading_cancel', 'value'=>&$id_loading_cancel, 'length'=>50),
			array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>500)
		);
		// print_r($param);
		$query = "BEGIN PROC_LOAD_CANCEL_CONT_HEADER(:v_id_ves_voyage, :v_doc_number, :v_userid, :v_id_loading_cancel, :v_msg_out); end;";
		// echo $query;die;
		$this->db->exec_bind_stored_procedure($query, $param);
		$msg_complete = $msg_out;
		if ($msg_out==''){
			for ($i=0;$i<sizeof($container_data);$i++){
				$no_container = $container_data[$i]->NO_CONTAINER;
				$point = $container_data[$i]->POINT;
				$param = array(
					array('name'=>':v_no_container', 'value'=>$no_container, 'length'=>15),
					array('name'=>':v_point', 'value'=>$point, 'length'=>10),
					array('name'=>':v_id_loading_cancel', 'value'=>$id_loading_cancel, 'length'=>50),
					array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>500)
				);
				// print_r($param);
				$query = "BEGIN PROC_LOAD_CANCEL_CONT_DETAIL(:v_no_container, :v_point, :v_id_loading_cancel, :v_msg_out); end;";
				// echo $query;die;
				$this->db->exec_bind_stored_procedure($query, $param);
				if ($msg_out!=''){
					$msg_complete .= $no_container." FAILED ".$msg_out."<br/>";
				}
			}
		}
		if ($msg_complete==''){
			return array(
						'success'=>true,
						'errors'=>$msg_complete
					);
		}else{
			return array(
						'success'=>false,
						'errors'=>$msg_complete
					);
		}
	}

	public function get_data_coarri_list($id_ves_voyage){
		$query 		= "SELECT A.ID_VES_VOYAGE, 
							  A.EDI_TYPE,
							  A.FILE_NAME,
							  A.E_I,
							  A.STATUS,
							  TO_CHAR(A.CREATED_DATE,'DD/MM/YYYY HH24:MI:SS') CREATED_DATE,
							  B.FULL_NAME
					   FROM EDI_GENERATE_LOGFILE A, M_USERS B
					   WHERE TRIM(A.ID_VES_VOYAGE) = '$id_ves_voyage'
					   	AND A.CREATED_BY = B.ID_USER
					   	AND A.EDI_TYPE = 'COARRI'
					   	AND A.ID_TERMINAL='".$this->gtools->terminal()."'
					   	AND B.ID_TERMINAL='".$this->gtools->terminal()."'
					   ORDER BY A.ID_EDI DESC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function edi_coarri($id_ves_voyage,$id_user){
		$file_generated = array();
		$terminalcode = $this->config->item('SITE_EDI_TERMINAL_CODE');
		$this->db->trans_start();

		//======================== create header coarri ==============================//
		$queryhdr 	= "SELECT ID_VESSEL,
							  VOY_IN,
							  VOY_OUT,
							  VESSEL_NAME,
							  OPERATOR,
							  CALL_SIGN
					   FROM VES_VOYAGE 
					   WHERE TRIM(ID_VES_VOYAGE) = UPPER('$id_ves_voyage') AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$hdr 		= $this->db->query($queryhdr);
		$row_hdr	= $hdr->result_array();

		$idves = $row_hdr[0]['ID_VESSEL'];
		$voyin = $row_hdr[0]['VOY_IN'];
		$voyout = $row_hdr[0]['VOY_OUT'];
		$vesnm = $row_hdr[0]['VESSEL_NAME'];
		$cs = $row_hdr[0]['CALL_SIGN'];
		$oprves = $row_hdr[0]['OPERATOR'];
		// $oprves = 'MAEU';

		$dt_tgl = date('Ymd');
		$dt_tm = date('Hi');
		$dt2 = date('ymd');
		$dt_tgl2 = date('ymdHi');

		$jml_dsf 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'I'
						  AND TRIM(UPPER(STATUS)) = 'FULL'
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$dsf 		= $this->db->query($jml_dsf);
		$row_dsf	= $dsf->result_array();
		$jmldtl_dsf = $row_dsf[0]['JML'];

		if($jmldtl_dsf>0) //========== COARRI DISCHARGE FULL ==========//
		{
			$file_name_dsf = "DSF_".strtoupper($id_ves_voyage)."_".date('ymdHis').".edi";

			$fp_dsf = fopen('./edifact/coarri/'.$file_name_dsf, 'w');
					  fwrite($fp_dsf, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_dsf, PHP_EOL);
			//======================== create header coarri ==============================//

			//======================== create detail coarri full ==============================//
			$querydtl_dsf = "SELECT NO_CONTAINER,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									'' AS NO_TRUCK,
									'' AS ALAT,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '46' 
									  ELSE '44' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									  ELSE '3' END EQD_STAT,
									SUBSTR(DISCHARGE_CONFIRM, 1, 12) AS DATE_CONFIRM,
									BP_LOCATION
							FROM EDI_COARRI 
							   WHERE TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'
								AND E_I = 'I'
								AND TRIM(UPPER(STATUS)) = 'FULL'
								AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_dsf	= $this->db->query($querydtl_dsf);
			$data_dtl_dsf	= $dtl_dsf->result_array();

			$n = 1;
			foreach ($data_dtl_dsf as $row_dtl_dsf)
			{
				$nocont = $row_dtl_dsf['NO_CONTAINER'];
				$isocode = $row_dtl_dsf['ISO_CODE'];
				$st = $row_dtl_dsf['INDIKATOR'];
				$pol = $row_dtl_dsf['POL'];
				$pod = $row_dtl_dsf['POD'];
				$grt = $row_dtl_dsf['WEIGHT'];
				$seal = $row_dtl_dsf['SEAL_ID'];
				$confirm_tgl = $row_dtl_dsf['DATE_CONFIRM'];
				$crr = $row_dtl_dsf['CARRIER'];
				$bgm = $row_dtl_dsf['BGM_STAT'];
				$eqd = $row_dtl_dsf['EQD_STAT'];
				$bsl = $row_dtl_dsf['BOOKING_SL'];
				$trck = $row_dtl_dsf['NO_TRUCK'];
				$alat = $row_dtl_dsf['ALAT'];
				$ei = $row_dtl_dsf['E_I'];
				$bp_loc = $row_dtl_dsf['BP_LOCATION'];

				if($ei=='E')
				{
					$voy = $voyin;
					$prt = $pol;
				}
				else
				{
					$voy = $voyin;
					$prt = $pod;
				}

				fwrite($fp_dsf, "UNH+".$n."+COARRI:D:95B:UN:ITG12'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "TDT+20+".$voy."+1++".$crr.":172:166+++".$cs.":103::".$vesnm."'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "RFF+BN:".$bsl."'");      //booking number
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "DTM+203:".$confirm_tgl.":203'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "LOC+9+".$prt.":139:6'");   //port of loading
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "LOC+11+".$pod.":139:6'");  //port of discharge
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "LOC+147+".$bp_loc."::5'");  //vessel slot number
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "CNT+16:1'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "UNT+9+".$n."'");
				fwrite($fp_dsf, PHP_EOL);
				
				$n++;

			}
			//======================== create detail coarri full ==============================//

			$jmln_dsf = $n-1;
			  
			fwrite($fp_dsf, "UNZ+".$jmln_dsf."+".$dt_tgl2."1014'");
			fclose($fp_dsf);

			$queryInsertDsf = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$id_ves_voyage',
										'COARRI',
										'$file_name_dsf',
										'I',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
									    ".$this->gtools->terminal().")";
			$this->db->query($queryInsertDsf);
			
			array_push($file_generated, $file_name_dsf);
		}

		$jml_dse 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'I'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$dse 		= $this->db->query($jml_dse);
		$row_dse	= $dse->result_array();
		$jmldtl_dse = $row_dse[0]['JML'];

		if($jmldtl_dse>0) //========== COARRI DISCHARGE EMPTY ==========//
		{
			$file_name_dse = "DSE_".strtoupper($id_ves_voyage)."_".date('ymdHis').".edi";
			
			// print_r($file_name_dse);die;

			$fp_dse = fopen('./edifact/coarri/'.$file_name_dse, 'w');
					  fwrite($fp_dse, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_dse, PHP_EOL);
			//======================== create header coarri ==============================//

			//======================== create detail coarri empty ==============================//
			$querydtl_dse = "SELECT NO_CONTAINER,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									'' AS NO_TRUCK,
									'' AS ALAT,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '46' 
									  ELSE '44' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									  ELSE '3' END EQD_STAT,
									SUBSTR(DISCHARGE_CONFIRM, 1, 12) AS DATE_CONFIRM,
									BP_LOCATION
							FROM EDI_COARRI 
							   WHERE TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'
								AND E_I = 'I'
								AND TRIM(UPPER(STATUS)) = 'EMPTY'
								AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_dse	= $this->db->query($querydtl_dse);
			$data_dtl_dse	= $dtl_dse->result_array();

			$o = 1;
			foreach ($data_dtl_dse as $row_dtl_dse)
			{
				$nocont = $row_dtl_dse['NO_CONTAINER'];
				$isocode = $row_dtl_dse['ISO_CODE'];
				$st = $row_dtl_dse['INDIKATOR'];
				$pol = $row_dtl_dse['POL'];
				$pod = $row_dtl_dse['POD'];
				$grt = $row_dtl_dse['WEIGHT'];
				$seal = $row_dtl_dse['SEAL_ID'];
				$confirm_tgl = $row_dtl_dse['DATE_CONFIRM'];
				$crr = $row_dtl_dse['CARRIER'];
				$bgm = $row_dtl_dse['BGM_STAT'];
				$eqd = $row_dtl_dse['EQD_STAT'];
				$bsl = $row_dtl_dse['BOOKING_SL'];
				$trck = $row_dtl_dse['NO_TRUCK'];
				$alat = $row_dtl_dse['ALAT'];
				$ei = $row_dtl_dse['E_I'];
				$bp_loc = $row_dtl_dse['BP_LOCATION'];

				if($ei=='E')
				{
					$voy = $voyin;
					$prt = $pol;
				}
				else
				{
					$voy = $voyin;
					$prt = $pod;
				}

				fwrite($fp_dse, "UNH+".$o."+COARRI:D:95B:UN:ITG12'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "TDT+20+".$voy."+1++".$crr.":172:166+++".$cs.":103::".$vesnm."'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "DTM+203:".$confirm_tgl.":203'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "LOC+9+".$prt.":139:6'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "LOC+11+".$pod.":139:6'");  //port of discharge
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "LOC+147+".$bp_loc."::5'");  //vessel slot number
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "CNT+16:1'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "UNT+9+".$o."'");
				fwrite($fp_dse, PHP_EOL);
				
				$o++;

			}
			//======================== create detail coarri empty ==============================//

			$jmlo_dse = $o-1;
			  
			fwrite($fp_dse, "UNZ+".$jmlo_dse."+".$dt_tgl2."1014'");
			fclose($fp_dse);

			$queryInsertDse = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$id_ves_voyage',
										'COARRI',
										'$file_name_dse',
										'I',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
									    ".$this->gtools->terminal().")";
			$this->db->query($queryInsertDse);
			
			array_push($file_generated, $file_name_dse);
		}
		
		$jml_lof 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'FULL'
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$lof 		= $this->db->query($jml_lof);
		$row_lof	= $lof->result_array();
		$jmldtl_lof = $row_lof[0]['JML'];

		if($jmldtl_lof>0) //========== COARRI LOADING FULL ==========//
		{
			$file_name_lof = "LOF_".strtoupper($id_ves_voyage)."_".date('ymdHis').".edi";

			$fp_lof = fopen('./edifact/coarri/'.$file_name_lof, 'w');
					  fwrite($fp_lof, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_lof, PHP_EOL);
			//======================== create header coarri ==============================//

			//======================== create detail coarri full ==============================//
			$querydtl_lof = "SELECT NO_CONTAINER,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									'' AS NO_TRUCK,
									'' AS ALAT,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '46' 
									  ELSE '44' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									  ELSE '3' END EQD_STAT,
									SUBSTR(LOADING_CONFIRM, 1, 12) AS DATE_CONFIRM,
									BP_LOCATION
							FROM EDI_COARRI 
							   WHERE TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'
								AND E_I = 'E'
								AND TRIM(UPPER(STATUS)) = 'FULL'
								AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_lof	= $this->db->query($querydtl_lof);
			$data_dtl_lof	= $dtl_lof->result_array();

			$l = 1;
			foreach ($data_dtl_lof as $row_dtl_lof)
			{
				$nocont = $row_dtl_lof['NO_CONTAINER'];
				$isocode = $row_dtl_lof['ISO_CODE'];
				$st = $row_dtl_lof['INDIKATOR'];
				$pol = $row_dtl_lof['POL'];
				$pod = $row_dtl_lof['POD'];
				$grt = $row_dtl_lof['WEIGHT'];
				$seal = $row_dtl_lof['SEAL_ID'];
				$confirm_tgl = $row_dtl_lof['DATE_CONFIRM'];
				$crr = $row_dtl_lof['CARRIER'];
				$bgm = $row_dtl_lof['BGM_STAT'];
				$eqd = $row_dtl_lof['EQD_STAT'];
				$bsl = $row_dtl_lof['BOOKING_SL'];
				$trck = $row_dtl_lof['NO_TRUCK'];
				$alat = $row_dtl_lof['ALAT'];
				$ei = $row_dtl_lof['E_I'];
				$bp_loc = $row_dtl_lof['BP_LOCATION'];

				if($ei=='E')
				{
					$voy = $voyin;
					$prt = $pol;
				}
				else
				{
					$voy = $voyin;
					$prt = $pod;
				}

				fwrite($fp_lof, "UNH+".$l."+COARRI:D:95B:UN:ITG12'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "TDT+20+".$voy."+1++".$crr.":172:166+++".$cs.":103::".$vesnm."'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "RFF+BN:".$bsl."'");      //booking number
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "DTM+203:".$confirm_tgl.":203'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "LOC+9+".$prt.":139:6'");   //port of loading
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "LOC+11+".$pod.":139:6'");  //port of discharge
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "LOC+147+".$bp_loc."::5'");  //vessel slot number
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "SEL+".$seal."+CA'");      //seal number
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "CNT+16:1'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "UNT+9+".$l."'");
				fwrite($fp_lof, PHP_EOL);
				
				$l++;

			}
			//======================== create detail coarri full ==============================//

			$jmll_lof = $l-1;
			  
			fwrite($fp_lof, "UNZ+".$jmll_lof."+".$dt_tgl2."1014'");
			fclose($fp_lof);

			$queryInsertLof = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$id_ves_voyage',
										'COARRI',
										'$file_name_lof',
										'E',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
										".$this->gtools->terminal().")";
			$this->db->query($queryInsertLof);
			
			array_push($file_generated, $file_name_lof);
		}
		
		$jml_loe 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$loe 		= $this->db->query($jml_loe);
		$row_loe	= $loe->result_array();
		$jmldtl_loe = $row_loe[0]['JML'];
		
		if($jmldtl_loe>0) //========== COARRI LOADING EMPTY ==========//
		{
			$file_name_loe = "LOE_".strtoupper($id_ves_voyage)."_".date('ymdHis').".edi";

			$fp_loe = fopen('./edifact/coarri/'.$file_name_loe, 'w');
					  fwrite($fp_loe, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_loe, PHP_EOL);
			//======================== create header coarri ==============================//

			//======================== create detail coarri empty ==============================//
			$querydtl_loe = "SELECT NO_CONTAINER,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									'' AS NO_TRUCK,
									'' AS ALAT,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '46' 
									  ELSE '44' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									  ELSE '3' END EQD_STAT,
									SUBSTR(LOADING_CONFIRM, 1, 12) AS DATE_CONFIRM,
									BP_LOCATION
							FROM EDI_COARRI 
							   WHERE TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'
								AND E_I = 'E'
								AND TRIM(UPPER(STATUS)) = 'EMPTY'
								AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_loe	= $this->db->query($querydtl_loe);
			$data_dtl_loe	= $dtl_loe->result_array();

			$p = 1;
			foreach ($data_dtl_loe as $row_dtl_loe)
			{
				$nocont = $row_dtl_loe['NO_CONTAINER'];
				$isocode = $row_dtl_loe['ISO_CODE'];
				$st = $row_dtl_loe['INDIKATOR'];
				$pol = $row_dtl_loe['POL'];
				$pod = $row_dtl_loe['POD'];
				$grt = $row_dtl_loe['WEIGHT'];
				$seal = $row_dtl_loe['SEAL_ID'];
				$confirm_tgl = $row_dtl_loe['DATE_CONFIRM'];
				$crr = $row_dtl_loe['CARRIER'];
				$bgm = $row_dtl_loe['BGM_STAT'];
				$eqd = $row_dtl_loe['EQD_STAT'];
				$bsl = $row_dtl_loe['BOOKING_SL'];
				$trck = $row_dtl_loe['NO_TRUCK'];
				$alat = $row_dtl_loe['ALAT'];
				$ei = $row_dtl_loe['E_I'];
				$bp_loc = $row_dtl_loe['BP_LOCATION'];

				if($ei=='E')
				{
					$voy = $voyin;
					$prt = $pol;
				}
				else
				{
					$voy = $voyin;
					$prt = $pod;
				}

				fwrite($fp_loe, "UNH+".$p."+COARRI:D:95B:UN:ITG12'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "TDT+20+".$voy."+1++".$crr.":172:166+++".$cs.":103::".$vesnm."'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "DTM+203:".$confirm_tgl.":203'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "LOC+9+".$prt.":139:6'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "LOC+11+".$pod.":139:6'");  //port of discharge
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "LOC+147+".$bp_loc."::5'");  //vessel slot number
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "CNT+16:1'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "UNT+9+".$p."'");
				fwrite($fp_loe, PHP_EOL);
				
				$p++;

			}
			//======================== create detail coarri empty ==============================//

			$jmlp_loe = $p-1;
			  
			fwrite($fp_loe, "UNZ+".$jmlp_loe."+".$dt_tgl2."1014'");
			fclose($fp_loe);

			$queryInsertLoe = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$id_ves_voyage',
										'COARRI',
										'$file_name_loe',
										'E',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
										".$this->gtools->terminal().")";
			$this->db->query($queryInsertLoe);
			
			array_push($file_generated, $file_name_loe);
		}
		
		if ($this->db->trans_complete()){
			return array('flag'=>1, 'msg'=>'OK', 'files'=>$file_generated);
		}else{
			return array('flag'=>0, 'msg'=>'error generate baplie');
		}
		
		return 'OK';
	}
	
	public function edi_coarri_service($id_user,$oprves){
		$file_generated = array();
		$terminalcode = $this->config->item('SITE_EDI_TERMINAL_CODE');
		$this->db->trans_start();

		$dt_tgl = date('Ymd');
		$dt_tm = date('Hi');
		$dt2 = date('ymd');
		$dt_tgl2 = date('ymdHi');

		$jml_dsf 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'I'
						  AND TRIM(UPPER(STATUS)) = 'FULL'
						  AND SEND_STATUS<>2 
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$dsf 		= $this->db->query($jml_dsf);
		$row_dsf	= $dsf->result_array();
		$jmldtl_dsf = $row_dsf[0]['JML'];

		if($jmldtl_dsf>0) //========== COARRI DISCHARGE FULL ==========//
		{
			$file_name_dsf = "DSF_".strtoupper($oprves)."_".date('ymdHis').".edi";

			//======================== create header coarri ==============================//
			$fp_dsf = fopen('./edifact/coarri/'.$file_name_dsf, 'w');
					  fwrite($fp_dsf, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_dsf, PHP_EOL);

			//======================== create detail coarri full ==============================//
			$querydtl_dsf = "SELECT NO_CONTAINER,
									POINT,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									'' AS NO_TRUCK,
									'' AS ALAT,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '46' 
									  ELSE '44' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									  ELSE '3' END EQD_STAT,
									SUBSTR(DISCHARGE_CONFIRM, 1, 12) AS DATE_CONFIRM,
									BP_LOCATION,
									VESSEL,
									CALL_SIGN,
									VOYAGE_IN
							FROM EDI_COARRI 
							   WHERE OPR_ID = '$oprves'
									  AND E_I = 'I'
									  AND TRIM(UPPER(STATUS)) = 'FULL'
									  AND SEND_STATUS<>2
									  AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_dsf	= $this->db->query($querydtl_dsf);
			$data_dtl_dsf	= $dtl_dsf->result_array();

			$n = 1;
			foreach ($data_dtl_dsf as $row_dtl_dsf)
			{
				$nocont = $row_dtl_dsf['NO_CONTAINER'];
				$point = $row_dtl_dsf['POINT'];
				$isocode = $row_dtl_dsf['ISO_CODE'];
				$st = $row_dtl_dsf['INDIKATOR'];
				$pol = $row_dtl_dsf['POL'];
				$pod = $row_dtl_dsf['POD'];
				$grt = $row_dtl_dsf['WEIGHT'];
				$seal = $row_dtl_dsf['SEAL_ID'];
				$confirm_tgl = $row_dtl_dsf['DATE_CONFIRM'];
				$crr = $row_dtl_dsf['CARRIER'];
				$bgm = $row_dtl_dsf['BGM_STAT'];
				$eqd = $row_dtl_dsf['EQD_STAT'];
				$bsl = $row_dtl_dsf['BOOKING_SL'];
				$trck = $row_dtl_dsf['NO_TRUCK'];
				$alat = $row_dtl_dsf['ALAT'];
				$ei = $row_dtl_dsf['E_I'];
				$bp_loc = $row_dtl_dsf['BP_LOCATION'];
				$vesnm = $row_dtl_dsf['VESSEL'];
				$cs = $row_dtl_dsf['CALL_SIGN'];
				$voyin = $row_dtl_dsf['VOYAGE_IN'];
				
				$query_flag_send = "UPDATE EDI_COARRI SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point' AND ID_TERMINAL='".$this->gtools->terminal()."'";
				$this->db->query($query_flag_send);
				
				if($ei=='E')
				{
					$voy = $voyin;
					$prt = $pol;
				}
				else
				{
					$voy = $voyin;
					$prt = $pod;
				}

				fwrite($fp_dsf, "UNH+".$n."+COARRI:D:95B:UN:ITG12'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "TDT+20+".$voy."+1++".$crr.":172:166+++".$cs.":103::".$vesnm."'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "RFF+BN:".$bsl."'");      //booking number
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "DTM+203:".$confirm_tgl.":203'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "LOC+9+".$prt.":139:6'");   //port of loading
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "LOC+11+".$pod.":139:6'");  //port of discharge
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "LOC+147+".$bp_loc."::5'");  //vessel slot number
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "CNT+16:1'");
				fwrite($fp_dsf, PHP_EOL);

				fwrite($fp_dsf, "UNT+9+".$n."'");
				fwrite($fp_dsf, PHP_EOL);
				
				$n++;

			}
			//======================== create detail coarri full ==============================//

			$jmln_dsf = $n-1;
			  
			fwrite($fp_dsf, "UNZ+".$jmln_dsf."+".$dt_tgl2."1014'");
			fclose($fp_dsf);

			$queryInsertDsf = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$oprves',
										'COARRI',
										'$file_name_dsf',
										'I',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
										".$this->gtools->terminal().")";
			$this->db->query($queryInsertDsf);
			
			array_push($file_generated, $file_name_dsf);
		}

		$jml_dse 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'I'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND SEND_STATUS<>2
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$dse 		= $this->db->query($jml_dse);
		$row_dse	= $dse->result_array();
		$jmldtl_dse = $row_dse[0]['JML'];

		if($jmldtl_dse>0) //========== COARRI DISCHARGE EMPTY ==========//
		{
			$file_name_dse = "DSE_".strtoupper($oprves)."_".date('ymdHis').".edi";

			//======================== create header coarri ==============================//
			$fp_dse = fopen('./edifact/coarri/'.$file_name_dse, 'w');
					  fwrite($fp_dse, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_dse, PHP_EOL);

			//======================== create detail coarri empty ==============================//
			$querydtl_dse = "SELECT NO_CONTAINER,
									POINT,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									'' AS NO_TRUCK,
									'' AS ALAT,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '46' 
									  ELSE '44' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									  ELSE '3' END EQD_STAT,
									SUBSTR(DISCHARGE_CONFIRM, 1, 12) AS DATE_CONFIRM,
									BP_LOCATION,
									VESSEL,
									CALL_SIGN,
									VOYAGE_IN
							FROM EDI_COARRI 
							   WHERE OPR_ID = '$oprves'
									  AND E_I = 'I'
									  AND TRIM(UPPER(STATUS)) = 'EMPTY'
									  AND SEND_STATUS<>2
									  AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_dse	= $this->db->query($querydtl_dse);
			$data_dtl_dse	= $dtl_dse->result_array();

			$o = 1;
			foreach ($data_dtl_dse as $row_dtl_dse)
			{
				$nocont = $row_dtl_dse['NO_CONTAINER'];
				$point = $row_dtl_dse['POINT'];
				$isocode = $row_dtl_dse['ISO_CODE'];
				$st = $row_dtl_dse['INDIKATOR'];
				$pol = $row_dtl_dse['POL'];
				$pod = $row_dtl_dse['POD'];
				$grt = $row_dtl_dse['WEIGHT'];
				$seal = $row_dtl_dse['SEAL_ID'];
				$confirm_tgl = $row_dtl_dse['DATE_CONFIRM'];
				$crr = $row_dtl_dse['CARRIER'];
				$bgm = $row_dtl_dse['BGM_STAT'];
				$eqd = $row_dtl_dse['EQD_STAT'];
				$bsl = $row_dtl_dse['BOOKING_SL'];
				$trck = $row_dtl_dse['NO_TRUCK'];
				$alat = $row_dtl_dse['ALAT'];
				$ei = $row_dtl_dse['E_I'];
				$bp_loc = $row_dtl_dse['BP_LOCATION'];
				$vesnm = $row_dtl_dse['VESSEL'];
				$cs = $row_dtl_dse['CALL_SIGN'];
				$voyin = $row_dtl_dse['VOYAGE_IN'];
				
				$query_flag_send = "UPDATE EDI_COARRI SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point' AND ID_TERMINAL='".$this->gtools->terminal()."'";
				$this->db->query($query_flag_send);
				
				if($ei=='E')
				{
					$voy = $voyin;
					$prt = $pol;
				}
				else
				{
					$voy = $voyin;
					$prt = $pod;
				}

				fwrite($fp_dse, "UNH+".$o."+COARRI:D:95B:UN:ITG12'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "TDT+20+".$voy."+1++".$crr.":172:166+++".$cs.":103::".$vesnm."'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "DTM+203:".$confirm_tgl.":203'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "LOC+9+".$prt.":139:6'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "LOC+11+".$pod.":139:6'");  //port of discharge
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "LOC+147+".$bp_loc."::5'");  //vessel slot number
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "CNT+16:1'");
				fwrite($fp_dse, PHP_EOL);

				fwrite($fp_dse, "UNT+9+".$o."'");
				fwrite($fp_dse, PHP_EOL);
				
				$o++;

			}
			//======================== create detail coarri empty ==============================//

			$jmlo_dse = $o-1;
			  
			fwrite($fp_dse, "UNZ+".$jmlo_dse."+".$dt_tgl2."1014'");
			fclose($fp_dse);

			$queryInsertDse = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$oprves',
										'COARRI',
										'$file_name_dse',
										'I',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
										".$this->gtools->terminal().")";
			$this->db->query($queryInsertDse);
			
			array_push($file_generated, $file_name_dse);
		}
		
		$jml_lof 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'FULL'
						  AND SEND_STATUS<>2
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$lof 		= $this->db->query($jml_lof);
		$row_lof	= $lof->result_array();
		$jmldtl_lof = $row_lof[0]['JML'];

		if($jmldtl_lof>0) //========== COARRI LOADING FULL ==========//
		{
			$file_name_lof = "LOF_".strtoupper($oprves)."_".date('ymdHis').".edi";

			//======================== create header coarri ==============================//
			$fp_lof = fopen('./edifact/coarri/'.$file_name_lof, 'w');
					  fwrite($fp_lof, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_lof, PHP_EOL);

			//======================== create detail coarri full ==============================//
			$querydtl_lof = "SELECT NO_CONTAINER,
									POINT,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									'' AS NO_TRUCK,
									'' AS ALAT,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '46' 
									  ELSE '44' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									  ELSE '3' END EQD_STAT,
									SUBSTR(LOADING_CONFIRM, 1, 12) AS DATE_CONFIRM,
									BP_LOCATION,
									VESSEL,
									CALL_SIGN,
									VOYAGE_IN
							FROM EDI_COARRI 
							   WHERE OPR_ID = '$oprves'
									  AND E_I = 'E'
									  AND TRIM(UPPER(STATUS)) = 'FULL'
									  AND SEND_STATUS<>2
									  AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_lof	= $this->db->query($querydtl_lof);
			$data_dtl_lof	= $dtl_lof->result_array();

			$l = 1;
			foreach ($data_dtl_lof as $row_dtl_lof)
			{
				$nocont = $row_dtl_lof['NO_CONTAINER'];
				$point = $row_dtl_lof['POINT'];
				$isocode = $row_dtl_lof['ISO_CODE'];
				$st = $row_dtl_lof['INDIKATOR'];
				$pol = $row_dtl_lof['POL'];
				$pod = $row_dtl_lof['POD'];
				$grt = $row_dtl_lof['WEIGHT'];
				$seal = $row_dtl_lof['SEAL_ID'];
				$confirm_tgl = $row_dtl_lof['DATE_CONFIRM'];
				$crr = $row_dtl_lof['CARRIER'];
				$bgm = $row_dtl_lof['BGM_STAT'];
				$eqd = $row_dtl_lof['EQD_STAT'];
				$bsl = $row_dtl_lof['BOOKING_SL'];
				$trck = $row_dtl_lof['NO_TRUCK'];
				$alat = $row_dtl_lof['ALAT'];
				$ei = $row_dtl_lof['E_I'];
				$bp_loc = $row_dtl_lof['BP_LOCATION'];
				$vesnm = $row_dtl_lof['VESSEL'];
				$cs = $row_dtl_lof['CALL_SIGN'];
				$voyin = $row_dtl_lof['VOYAGE_IN'];
				
				$query_flag_send = "UPDATE EDI_COARRI SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point' AND ID_TERMINAL='".$this->gtools->terminal()."'";
				$this->db->query($query_flag_send);
				
				if($ei=='E')
				{
					$voy = $voyin;
					$prt = $pol;
				}
				else
				{
					$voy = $voyin;
					$prt = $pod;
				}

				fwrite($fp_lof, "UNH+".$l."+COARRI:D:95B:UN:ITG12'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "TDT+20+".$voy."+1++".$crr.":172:166+++".$cs.":103::".$vesnm."'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "RFF+BN:".$bsl."'");      //booking number
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "DTM+203:".$confirm_tgl.":203'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "LOC+9+".$prt.":139:6'");   //port of loading
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "LOC+11+".$pod.":139:6'");  //port of discharge
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "LOC+147+".$bp_loc."::5'");  //vessel slot number
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "SEL+".$seal."+CA'");      //seal number
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "CNT+16:1'");
				fwrite($fp_lof, PHP_EOL);

				fwrite($fp_lof, "UNT+9+".$l."'");
				fwrite($fp_lof, PHP_EOL);
				
				$l++;

			}
			//======================== create detail coarri full ==============================//

			$jmll_lof = $l-1;
			  
			fwrite($fp_lof, "UNZ+".$jmll_lof."+".$dt_tgl2."1014'");
			fclose($fp_lof);

			$queryInsertLof = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$oprves',
										'COARRI',
										'$file_name_lof',
										'E',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
									   	".$this->gtools->terminal().")";
			$this->db->query($queryInsertLof);
			
			array_push($file_generated, $file_name_lof);
		}
		
		$jml_loe 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND SEND_STATUS<>2
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$loe 		= $this->db->query($jml_loe);
		$row_loe	= $loe->result_array();
		$jmldtl_loe = $row_loe[0]['JML'];
		
		if($jmldtl_loe>0) //========== COARRI LOADING EMPTY ==========//
		{
			$file_name_loe = "LOE_".strtoupper($oprves)."_".date('ymdHis').".edi";

			//======================== create header coarri ==============================//
			$fp_loe = fopen('./edifact/coarri/'.$file_name_loe, 'w');
					  fwrite($fp_loe, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_loe, PHP_EOL);

			//======================== create detail coarri empty ==============================//
			$querydtl_loe = "SELECT NO_CONTAINER,
									POINT,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									'' AS NO_TRUCK,
									'' AS ALAT,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '46' 
									  ELSE '44' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									  ELSE '3' END EQD_STAT,
									SUBSTR(LOADING_CONFIRM, 1, 12) AS DATE_CONFIRM,
									BP_LOCATION,
									VESSEL,
									CALL_SIGN,
									VOYAGE_IN
							FROM EDI_COARRI 
							   WHERE OPR_ID = '$oprves'
									  AND E_I = 'E'
									  AND TRIM(UPPER(STATUS)) = 'EMPTY'
									  AND SEND_STATUS<>2
									  AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_loe	= $this->db->query($querydtl_loe);
			$data_dtl_loe	= $dtl_loe->result_array();

			$p = 1;
			foreach ($data_dtl_loe as $row_dtl_loe)
			{
				$nocont = $row_dtl_loe['NO_CONTAINER'];
				$point = $row_dtl_loe['POINT'];
				$isocode = $row_dtl_loe['ISO_CODE'];
				$st = $row_dtl_loe['INDIKATOR'];
				$pol = $row_dtl_loe['POL'];
				$pod = $row_dtl_loe['POD'];
				$grt = $row_dtl_loe['WEIGHT'];
				$seal = $row_dtl_loe['SEAL_ID'];
				$confirm_tgl = $row_dtl_loe['DATE_CONFIRM'];
				$crr = $row_dtl_loe['CARRIER'];
				$bgm = $row_dtl_loe['BGM_STAT'];
				$eqd = $row_dtl_loe['EQD_STAT'];
				$bsl = $row_dtl_loe['BOOKING_SL'];
				$trck = $row_dtl_loe['NO_TRUCK'];
				$alat = $row_dtl_loe['ALAT'];
				$ei = $row_dtl_loe['E_I'];
				$bp_loc = $row_dtl_loe['BP_LOCATION'];
				$vesnm = $row_dtl_loe['VESSEL'];
				$cs = $row_dtl_loe['CALL_SIGN'];
				$voyin = $row_dtl_loe['VOYAGE_IN'];
				 
				$query_flag_send = "UPDATE EDI_COARRI SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point' AND ID_TERMINAL='".$this->gtools->terminal()."'";
				$this->db->query($query_flag_send);
				
				if($ei=='E')
				{
					$voy = $voyin;
					$prt = $pol;
				}
				else
				{
					$voy = $voyin;
					$prt = $pod;
				}

				fwrite($fp_loe, "UNH+".$p."+COARRI:D:95B:UN:ITG12'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "TDT+20+".$voy."+1++".$crr.":172:166+++".$cs.":103::".$vesnm."'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "DTM+203:".$confirm_tgl.":203'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "LOC+9+".$prt.":139:6'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "LOC+11+".$pod.":139:6'");  //port of discharge
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "LOC+147+".$bp_loc."::5'");  //vessel slot number
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "CNT+16:1'");
				fwrite($fp_loe, PHP_EOL);

				fwrite($fp_loe, "UNT+9+".$p."'");
				fwrite($fp_loe, PHP_EOL);
				
				$p++;

			}
			//======================== create detail coarri empty ==============================//

			$jmlp_loe = $p-1;
			  
			fwrite($fp_loe, "UNZ+".$jmlp_loe."+".$dt_tgl2."1014'");
			fclose($fp_loe);

			$queryInsertLoe = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$oprves',
										'COARRI',
										'$file_name_loe',
										'E',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
										".$this->gtools->terminal().")";
			$this->db->query($queryInsertLoe);
			
			array_push($file_generated, $file_name_loe);
		}
		
		if ($this->db->trans_complete()){
			return array('flag'=>1, 'msg'=>'OK', 'files'=>$file_generated);
		}else{
			return array('flag'=>0, 'msg'=>'error generate baplie');
		}
		
		return 'OK';
	}
	
	public function get_data_codeco_list($id_ves_voyage){
		$query 		= "SELECT A.ID_VES_VOYAGE, 
							  A.EDI_TYPE,
							  A.FILE_NAME,
							  A.E_I,
							  A.STATUS,
							  TO_CHAR(A.CREATED_DATE,'DD/MM/YYYY HH24:MI:SS') CREATED_DATE,
							  B.FULL_NAME
					   FROM EDI_GENERATE_LOGFILE A, M_USERS B
					   WHERE TRIM(A.ID_VES_VOYAGE) = '$id_ves_voyage'
					   	AND A.CREATED_BY = B.ID_USER
					   	AND A.EDI_TYPE = 'CODECO'
					   	AND A.ID_TERMINAL='".$this->gtools->terminal()."'
					   	AND B.ID_TERMINAL='".$this->gtools->terminal()."'
					   ORDER BY A.ID_EDI DESC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function edi_codeco($id_ves_voyage,$id_user){
		$file_generated = array();
		$terminalcode = $this->config->item('SITE_EDI_TERMINAL_CODE');
		$this->db->trans_start();

		//======================== create header codeco ==============================//
		$queryhdr 	= "SELECT ID_VESSEL,
							  VOY_IN,
							  VOY_OUT,
							  VESSEL_NAME,
							  OPERATOR
					   FROM VES_VOYAGE 
					   WHERE TRIM(ID_VES_VOYAGE) = UPPER('$id_ves_voyage') AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$hdr 		= $this->db->query($queryhdr);
		$row_hdr	= $hdr->result_array();

		$idves = $row_hdr[0]['ID_VESSEL'];
		$voyin = $row_hdr[0]['VOY_IN'];
		$voyout = $row_hdr[0]['VOY_OUT'];
		$vesnm = $row_hdr[0]['VESSEL_NAME'];
		$oprves = $row_hdr[0]['OPERATOR'];
		// $oprves = 'MAEU';

		$dt_tgl = date('Ymd');
		$dt_tm = date('Hi');
		$dt2 = date('ymd');
		$dt_tgl2 = date('ymdHi');

		$jml_gof	= "SELECT COUNT(*) AS JML FROM EDI_CODECO
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'I'
						  AND TRIM(UPPER(STATUS)) = 'FULL'
						  AND TRUCK_OUT_DATE IS NOT NULL
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$gof 		= $this->db->query($jml_gof);
		$row_gof	= $gof->result_array();
		$jmldtl_gof = $row_gof[0]['JML'];

		if($jmldtl_gof>0) //========== CODECO GATE DELIVERY FULL ==========//
		{
			$file_name_gof = "GOF_".strtoupper($id_ves_voyage)."_".date('ymdHis').".edi";

			$fp_gof = fopen('./edifact/codeco/'.$file_name_gof, 'w');
					  fwrite($fp_gof, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_gof, PHP_EOL);
			//======================== create header codeco ==============================//

			//======================== create detail codeco full ==============================//
			$querydtl_gof = "SELECT NO_CONTAINER,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '34' 
									 ELSE '36' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									 ELSE '3' END EQD_STAT,
									CASE WHEN TRIM(E_I)='E' THEN SUBSTR(TRUCK_IN_DATE, 1, 12) 
									 ELSE SUBSTR(TRUCK_OUT_DATE, 1, 12) END TGL_GATE
							 FROM EDI_CODECO 
							 WHERE TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'
							   AND E_I = 'I'
							   AND TRIM(UPPER(STATUS)) = 'FULL'
							   AND TRUCK_OUT_DATE IS NOT NULL
							   AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_gof	= $this->db->query($querydtl_gof);
			$data_dtl_gof	= $dtl_gof->result_array();

			$n = 1;
			foreach ($data_dtl_gof as $row_dtl_gof)
			{
				$nocont = $row_dtl_gof['NO_CONTAINER'];
				$isocode = $row_dtl_gof['ISO_CODE'];
				$st = $row_dtl_gof['INDIKATOR'];
				$pol = $row_dtl_gof['POL'];
				$pod = $row_dtl_gof['POD'];
				$grt = $row_dtl_gof['WEIGHT'];
				$seal = $row_dtl_gof['SEAL_ID'];
				$gate_tgl = $row_dtl_gof['TGL_GATE'];
				$crr = $row_dtl_gof['CARRIER'];
				$bgm = $row_dtl_gof['BGM_STAT'];
				$eqd = $row_dtl_gof['EQD_STAT'];
				$bsl = $row_dtl_gof['BOOKING_SL'];
				$trck = $row_dtl_gof['NO_TRUCK'];
				$ei = $row_dtl_gof['E_I'];

				if($ei=='E')
				{
					$voy = $voyin;
					$prt = $pol;
				}
				else
				{
					$voy = $voyin;
					$prt = $pod;
				}

				fwrite($fp_gof, "UNH+".$n."+CODECO:D:95B:UN:ITG12'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "RFF+BN:".$bsl."'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "DTM+7:".$gate_tgl.":203'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "LOC+165+IDPAGTM:139:6'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "HAN+6'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "TDT+1++3++PAG888:172:87'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "CNT+16:1'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "UNT+10+".$n."'");
				fwrite($fp_gof, PHP_EOL);
				
				$n++;

			}
			//======================== create detail codeco full ==============================//

			$jmln_gof = $n-1;
			  
			fwrite($fp_gof, "UNZ+".$jmln_gof."+".$dt_tgl2."1014'");
			fclose($fp_gof);

			$queryInsertGof = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)

								VALUES ('$id_ves_voyage',
										'CODECO',
										'$file_name_gof',
										'I',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
										".$this->gtools->terminal().")";
			$this->db->query($queryInsertGof);
			
			array_push($file_generated, $file_name_gof);
		}
		
		$jml_goe 	= "SELECT COUNT(*) AS JML FROM EDI_CODECO 
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'I'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND TRUCK_OUT_DATE IS NOT NULL
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$goe 		= $this->db->query($jml_goe);
		$row_goe	= $goe->result_array();
		$jmldtl_goe = $row_goe[0]['JML'];
		
		if($jmldtl_goe>0) //========== CODECO GATE DELIVERY EMPTY ==========//
		{
			$file_name_goe = "GOP_".strtoupper($id_ves_voyage)."_".date('ymdHis').".edi";

			$fp_goe = fopen('./edifact/codeco/'.$file_name_goe, 'w');
					  fwrite($fp_goe, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_goe, PHP_EOL);
			//======================== create header codeco ==============================//

			//======================== create detail codeco empty ==============================//
			$querydtl_goe = "SELECT NO_CONTAINER,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '34' 
									 ELSE '36' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									 ELSE '3' END EQD_STAT,
									CASE WHEN TRIM(E_I)='E' THEN SUBSTR(TRUCK_IN_DATE, 1, 12) 
									 ELSE SUBSTR(TRUCK_OUT_DATE, 1, 12) END TGL_GATE
							 FROM EDI_CODECO 
							   WHERE TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'
								AND E_I = 'I'
								AND TRIM(UPPER(STATUS)) = 'EMPTY'
								AND TRUCK_OUT_DATE IS NOT NULL
								AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_goe	= $this->db->query($querydtl_goe);
			$data_dtl_goe	= $dtl_goe->result_array();

			$o = 1;
			foreach ($data_dtl_goe as $row_dtl_goe)
			{
				$nocont = $row_dtl_goe['NO_CONTAINER'];
				$isocode = $row_dtl_goe['ISO_CODE'];
				$st = $row_dtl_goe['INDIKATOR'];
				$pol = $row_dtl_goe['POL'];
				$pod = $row_dtl_goe['POD'];
				$grt = $row_dtl_goe['WEIGHT'];
				$seal = $row_dtl_goe['SEAL_ID'];
				$gate_tgl = $row_dtl_goe['TGL_GATE'];
				$crr = $row_dtl_goe['CARRIER'];
				$bgm = $row_dtl_goe['BGM_STAT'];
				$eqd = $row_dtl_goe['EQD_STAT'];
				$bsl = $row_dtl_goe['BOOKING_SL'];
				$trck = $row_dtl_goe['NO_TRUCK'];
				$ei = $row_dtl_goe['E_I'];

				if($ei=='E')
				{
					$voy = $voyin;
					$prt = $pol;
				}
				else
				{
					$voy = $voyin;
					$prt = $pod;
				}

				fwrite($fp_goe, "UNH+".$o."+CODECO:D:95B:UN:ITG12'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "DTM+7:".$gate_tgl.":203'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "LOC+165+IDPAGTM:139:6'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "HAN+6'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "TDT+1++3++PAG888:172:87'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "CNT+16:1'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "UNT+10+".$o."'");
				fwrite($fp_goe, PHP_EOL);
				
				$o++;

			}
			//======================== create detail codeco empty ==============================//

			$jmlo_goe = $o-1;
			  
			fwrite($fp_goe, "UNZ+".$jmlo_goe."+".$dt_tgl2."1014'");
			fclose($fp_goe);

			$queryInsertGoe = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$id_ves_voyage',
										'CODECO',
										'$file_name_goe',
										'I',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
										".$this->gtools->terminal().")";
			$this->db->query($queryInsertGoe);
			
			array_push($file_generated, $file_name_goe);
		}
		
		$jml_gif 	= "SELECT COUNT(*) AS JML FROM EDI_CODECO 
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'FULL'
						  AND TRUCK_IN_DATE IS NOT NULL
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$gif 		= $this->db->query($jml_gif);
		$row_gif	= $gif->result_array();
		$jmldtl_gif = $row_gif[0]['JML'];
		
		if($jmldtl_gif>0) //========== CODECO GATE RECEIVING FULL ==========//
		{
			$file_name_gif = "GIF_".strtoupper($id_ves_voyage)."_".date('ymdHis').".edi";

			$fp_gif = fopen('./edifact/codeco/'.$file_name_gif, 'w');
					  fwrite($fp_gif, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_gif, PHP_EOL);
			//======================== create header codeco ==============================//

			//======================== create detail codeco full ==============================//
			$querydtl_gif = "SELECT NO_CONTAINER,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '34' 
									 ELSE '36' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									 ELSE '3' END EQD_STAT,
									CASE WHEN TRIM(E_I)='E' THEN SUBSTR(TRUCK_IN_DATE, 1, 12) 
									 ELSE SUBSTR(TRUCK_OUT_DATE, 1, 12) END TGL_GATE
							 FROM EDI_CODECO 
							   WHERE TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'
								AND E_I = 'E'
								AND TRIM(UPPER(STATUS)) = 'FULL'
								AND TRUCK_IN_DATE IS NOT NULL
								AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_gif	= $this->db->query($querydtl_gif);
			$data_dtl_gif	= $dtl_gif->result_array();

			$l = 1;
			foreach ($data_dtl_gif as $row_dtl_gif)
			{
				$nocont = $row_dtl_gif['NO_CONTAINER'];
				$isocode = $row_dtl_gif['ISO_CODE'];
				$st = $row_dtl_gif['INDIKATOR'];
				$pol = $row_dtl_gif['POL'];
				$pod = $row_dtl_gif['POD'];
				$grt = $row_dtl_gif['WEIGHT'];
				$seal = $row_dtl_gif['SEAL_ID'];
				$gate_tgl = $row_dtl_gif['TGL_GATE'];
				$crr = $row_dtl_gif['CARRIER'];
				$bgm = $row_dtl_gif['BGM_STAT'];
				$eqd = $row_dtl_gif['EQD_STAT'];
				$bsl = $row_dtl_gif['BOOKING_SL'];
				$trck = $row_dtl_gif['NO_TRUCK'];
				$ei = $row_dtl_gif['E_I'];

				if($ei=='E')
				{
					$voy = $voyin;
					$prt = $pol;
				}
				else
				{
					$voy = $voyin;
					$prt = $pod;
				}

				fwrite($fp_gif, "UNH+".$l."+CODECO:D:95B:UN:ITG12'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "RFF+BN:".$bsl."'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "DTM+7:".$gate_tgl.":203'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "LOC+165+IDPAGTM:139:6'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "SEL+".$seal."+CA'");      //seal number
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "HAN+6'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "TDT+1++3++PAG888:172:87'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "CNT+16:1'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "UNT+10+".$l."'");
				fwrite($fp_gif, PHP_EOL);
				
				$l++;

			}
			//======================== create detail coarri full ==============================//

			$jmll_gif = $l-1;
			  
			fwrite($fp_gif, "UNZ+".$jmll_gif."+".$dt_tgl2."1014'");
			fclose($fp_gif);

			$queryInsertGif = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$id_ves_voyage',
										'CODECO',
										'$file_name_gif',
										'E',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
										".$this->gtools->terminal().")";
			$this->db->query($queryInsertGif);
			
			array_push($file_generated, $file_name_gif);
		}
		
		$jml_gie 	= "SELECT COUNT(*) AS JML FROM EDI_CODECO 
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND TRUCK_IN_DATE IS NOT NULL
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$gie 		= $this->db->query($jml_gie);
		$row_gie	= $gie->result_array();
		$jmldtl_gie = $row_gie[0]['JML'];

		if($jmldtl_gie>0) //========== CODECO GATE RECEIVING EMPTY ==========//
		{
			$file_name_gie = "GIE_".strtoupper($id_ves_voyage)."_".date('ymdHis').".edi";

			$fp_gie = fopen('./edifact/codeco/'.$file_name_gie, 'w');
					  fwrite($fp_gie, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_gie, PHP_EOL);
			//======================== create header codeco ==============================//

			//======================== create detail codeco empty ==============================//
			$querydtl_gie = "SELECT NO_CONTAINER,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '34' 
									 ELSE '36' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									 ELSE '3' END EQD_STAT,
									CASE WHEN TRIM(E_I)='E' THEN SUBSTR(TRUCK_IN_DATE, 1, 12) 
									 ELSE SUBSTR(TRUCK_OUT_DATE, 1, 12) END TGL_GATE
							 FROM EDI_CODECO 
							   WHERE TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'
								AND E_I = 'E'
								AND TRIM(UPPER(STATUS)) = 'EMPTY'
								AND TRUCK_IN_DATE IS NOT NULL
								AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_gie	= $this->db->query($querydtl_gie);
			$data_dtl_gie	= $dtl_gie->result_array();

			$p = 1;
			foreach ($data_dtl_gie as $row_dtl_gie)
			{
				$nocont = $row_dtl_gie['NO_CONTAINER'];
				$isocode = $row_dtl_gie['ISO_CODE'];
				$st = $row_dtl_gie['INDIKATOR'];
				$pol = $row_dtl_gie['POL'];
				$pod = $row_dtl_gie['POD'];
				$grt = $row_dtl_gie['WEIGHT'];
				$seal = $row_dtl_gie['SEAL_ID'];
				$gate_tgl = $row_dtl_gie['TGL_GATE'];
				$crr = $row_dtl_gie['CARRIER'];
				$bgm = $row_dtl_gie['BGM_STAT'];
				$eqd = $row_dtl_gie['EQD_STAT'];
				$bsl = $row_dtl_gie['BOOKING_SL'];
				$trck = $row_dtl_gie['NO_TRUCK'];
				$ei = $row_dtl_gie['E_I'];

				if($ei=='E')
				{
					$voy = $voyin;
					$prt = $pol;
				}
				else
				{
					$voy = $voyin;
					$prt = $pod;
				}

				fwrite($fp_gie, "UNH+".$p."+CODECO:D:95B:UN:ITG12'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "DTM+7:".$gate_tgl.":203'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "LOC+165+IDPAGTM:139:6'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "HAN+6'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "TDT+1++3++PAG888:172:87'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "CNT+16:1'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "UNT+10+".$p."'");
				fwrite($fp_gie, PHP_EOL);
				
				$p++;

			}
			//======================== create detail codeco empty ==============================//

			$jmlp_gie = $p-1;
			  
			fwrite($fp_gie, "UNZ+".$jmlp_gie."+".$dt_tgl2."1014'");
			fclose($fp_gie);

			$queryInsertGie = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$id_ves_voyage',
										'CODECO',
										'$file_name_gie',
										'E',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
										".$this->gtools->terminal().")";
			$this->db->query($queryInsertGie);
			
			array_push($file_generated, $file_name_gie);
		}
		
		if ($this->db->trans_complete()){
			return array('flag'=>1, 'msg'=>'OK', 'files'=>$file_generated);
		}else{
			return array('flag'=>0, 'msg'=>'error generate baplie');
		}
		
		return 'OK';
	}
	
	public function edi_codeco_service($id_user,$oprves){
		$file_generated = array();
		$terminalcode = $this->config->item('SITE_EDI_TERMINAL_CODE');
		$this->db->trans_start();
		
		$dt_tgl = date('Ymd');
		$dt_tm = date('Hi');
		$dt2 = date('ymd');
		$dt_tgl2 = date('ymdHi');

		$jml_gof	= "SELECT COUNT(*) AS JML FROM EDI_CODECO
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'I'
						  AND TRIM(UPPER(STATUS)) = 'FULL'
						  AND TRUCK_OUT_DATE IS NOT NULL
						  AND SEND_STATUS<>2
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$gof 		= $this->db->query($jml_gof);
		$row_gof	= $gof->result_array();
		$jmldtl_gof = $row_gof[0]['JML'];

		if($jmldtl_gof>0) //========== CODECO GATE DELIVERY FULL ==========//
		{
			$file_name_gof = "GOF_".strtoupper($oprves)."_".date('ymdHis').".edi";

			//======================== create header codeco ==============================//
			$fp_gof = fopen('./edifact/codeco/'.$file_name_gof, 'w');
					  fwrite($fp_gof, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_gof, PHP_EOL);

			//======================== create detail codeco full ==============================//
			$querydtl_gof = "SELECT NO_CONTAINER,
									POINT,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '34' 
									 ELSE '36' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									 ELSE '3' END EQD_STAT,
									CASE WHEN TRIM(E_I)='E' THEN SUBSTR(TRUCK_IN_DATE, 1, 12) 
									 ELSE SUBSTR(TRUCK_OUT_DATE, 1, 12) END TGL_GATE
							 FROM EDI_CODECO 
							 WHERE OPR_ID = '$oprves'
									  AND E_I = 'I'
									  AND TRIM(UPPER(STATUS)) = 'FULL'
									  AND TRUCK_OUT_DATE IS NOT NULL
									  AND SEND_STATUS<>2
									  AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_gof	= $this->db->query($querydtl_gof);
			$data_dtl_gof	= $dtl_gof->result_array();

			$n = 1;
			foreach ($data_dtl_gof as $row_dtl_gof)
			{
				$nocont = $row_dtl_gof['NO_CONTAINER'];
				$point = $row_dtl_gof['POINT'];
				$isocode = $row_dtl_gof['ISO_CODE'];
				$st = $row_dtl_gof['INDIKATOR'];
				$pol = $row_dtl_gof['POL'];
				$pod = $row_dtl_gof['POD'];
				$grt = $row_dtl_gof['WEIGHT'];
				$seal = $row_dtl_gof['SEAL_ID'];
				$gate_tgl = $row_dtl_gof['TGL_GATE'];
				$crr = $row_dtl_gof['CARRIER'];
				$bgm = $row_dtl_gof['BGM_STAT'];
				$eqd = $row_dtl_gof['EQD_STAT'];
				$bsl = $row_dtl_gof['BOOKING_SL'];
				
				$query_flag_send = "UPDATE EDI_CODECO SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point' AND ID_TERMINAL='".$this->gtools->terminal()."'";
				$this->db->query($query_flag_send);
				
				fwrite($fp_gof, "UNH+".$n."+CODECO:D:95B:UN:ITG12'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "RFF+BN:".$bsl."'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "DTM+7:".$gate_tgl.":203'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "LOC+165+IDPAGTM:139:6'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "HAN+6'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "TDT+1++3++PAG888:172:87'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "CNT+16:1'");
				fwrite($fp_gof, PHP_EOL);

				fwrite($fp_gof, "UNT+10+".$n."'");
				fwrite($fp_gof, PHP_EOL);
				
				$n++;

			}
			//======================== create detail codeco full ==============================//

			$jmln_gof = $n-1;
			  
			fwrite($fp_gof, "UNZ+".$jmln_gof."+".$dt_tgl2."1014'");
			fclose($fp_gof);

			$queryInsertGof = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$oprves',
										'CODECO',
										'$file_name_gof',
										'I',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
										".$this->gtools->terminal().")";
			$this->db->query($queryInsertGof);
			
			array_push($file_generated, $file_name_gof);
		}
		
		$jml_goe 	= "SELECT COUNT(*) AS JML FROM EDI_CODECO 
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'I'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND TRUCK_OUT_DATE IS NOT NULL
						  AND SEND_STATUS<>2
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$goe 		= $this->db->query($jml_goe);
		$row_goe	= $goe->result_array();
		$jmldtl_goe = $row_goe[0]['JML'];
		
		if($jmldtl_goe>0) //========== CODECO GATE DELIVERY EMPTY ==========//
		{
			$file_name_goe = "GOP_".strtoupper($oprves)."_".date('ymdHis').".edi";

			//======================== create header codeco ==============================//
			$fp_goe = fopen('./edifact/codeco/'.$file_name_goe, 'w');
					  fwrite($fp_goe, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_goe, PHP_EOL);

			//======================== create detail codeco empty ==============================//
			$querydtl_goe = "SELECT NO_CONTAINER,
									POINT,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '34' 
									 ELSE '36' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									 ELSE '3' END EQD_STAT,
									CASE WHEN TRIM(E_I)='E' THEN SUBSTR(TRUCK_IN_DATE, 1, 12) 
									 ELSE SUBSTR(TRUCK_OUT_DATE, 1, 12) END TGL_GATE
							 FROM EDI_CODECO 
							   WHERE OPR_ID = '$oprves'
									  AND E_I = 'I'
									  AND TRIM(UPPER(STATUS)) = 'EMPTY'
									  AND TRUCK_OUT_DATE IS NOT NULL
									  AND SEND_STATUS<>2
									  AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_goe	= $this->db->query($querydtl_goe);
			$data_dtl_goe	= $dtl_goe->result_array();

			$o = 1;
			foreach ($data_dtl_goe as $row_dtl_goe)
			{
				$nocont = $row_dtl_goe['NO_CONTAINER'];
				$point = $row_dtl_goe['POINT'];
				$isocode = $row_dtl_goe['ISO_CODE'];
				$st = $row_dtl_goe['INDIKATOR'];
				$pol = $row_dtl_goe['POL'];
				$pod = $row_dtl_goe['POD'];
				$grt = $row_dtl_goe['WEIGHT'];
				$seal = $row_dtl_goe['SEAL_ID'];
				$gate_tgl = $row_dtl_goe['TGL_GATE'];
				$crr = $row_dtl_goe['CARRIER'];
				$bgm = $row_dtl_goe['BGM_STAT'];
				$eqd = $row_dtl_goe['EQD_STAT'];
				$bsl = $row_dtl_goe['BOOKING_SL'];
				
				$query_flag_send = "UPDATE EDI_CODECO SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point' AND ID_TERMINAL='".$this->gtools->terminal()."'";
				$this->db->query($query_flag_send);

				fwrite($fp_goe, "UNH+".$o."+CODECO:D:95B:UN:ITG12'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "DTM+7:".$gate_tgl.":203'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "LOC+165+IDPAGTM:139:6'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "HAN+6'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "TDT+1++3++PAG888:172:87'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "CNT+16:1'");
				fwrite($fp_goe, PHP_EOL);

				fwrite($fp_goe, "UNT+10+".$o."'");
				fwrite($fp_goe, PHP_EOL);
				
				$o++;

			}
			//======================== create detail codeco empty ==============================//

			$jmlo_goe = $o-1;
			  
			fwrite($fp_goe, "UNZ+".$jmlo_goe."+".$dt_tgl2."1014'");
			fclose($fp_goe);

			$queryInsertGoe = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$oprves',
										'CODECO',
										'$file_name_goe',
										'I',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
										".$this->gtools->terminal().")";
			$this->db->query($queryInsertGoe);
			
			array_push($file_generated, $file_name_goe);
		}
		
		$jml_gif 	= "SELECT COUNT(*) AS JML FROM EDI_CODECO 
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'FULL'
						  AND TRUCK_IN_DATE IS NOT NULL
						  AND SEND_STATUS<>2
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$gif 		= $this->db->query($jml_gif);
		$row_gif	= $gif->result_array();
		$jmldtl_gif = $row_gif[0]['JML'];
		
		if($jmldtl_gif>0) //========== CODECO GATE RECEIVING FULL ==========//
		{
			$file_name_gif = "GIF_".strtoupper($oprves)."_".date('ymdHis').".edi";

			//======================== create header codeco ==============================//
			$fp_gif = fopen('./edifact/codeco/'.$file_name_gif, 'w');
					  fwrite($fp_gif, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_gif, PHP_EOL);

			//======================== create detail codeco full ==============================//
			$querydtl_gif = "SELECT NO_CONTAINER,
									POINT,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '34' 
									 ELSE '36' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									 ELSE '3' END EQD_STAT,
									CASE WHEN TRIM(E_I)='E' THEN SUBSTR(TRUCK_IN_DATE, 1, 12) 
									 ELSE SUBSTR(TRUCK_OUT_DATE, 1, 12) END TGL_GATE
							 FROM EDI_CODECO 
							   WHERE OPR_ID = '$oprves'
									  AND E_I = 'E'
									  AND TRIM(UPPER(STATUS)) = 'FULL'
									  AND TRUCK_IN_DATE IS NOT NULL
									  AND SEND_STATUS<>2
									  AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_gif	= $this->db->query($querydtl_gif);
			$data_dtl_gif	= $dtl_gif->result_array();

			$l = 1;
			foreach ($data_dtl_gif as $row_dtl_gif)
			{
				$nocont = $row_dtl_gif['NO_CONTAINER'];
				$point = $row_dtl_gif['POINT'];
				$isocode = $row_dtl_gif['ISO_CODE'];
				$st = $row_dtl_gif['INDIKATOR'];
				$pol = $row_dtl_gif['POL'];
				$pod = $row_dtl_gif['POD'];
				$grt = $row_dtl_gif['WEIGHT'];
				$seal = $row_dtl_gif['SEAL_ID'];
				$gate_tgl = $row_dtl_gif['TGL_GATE'];
				$crr = $row_dtl_gif['CARRIER'];
				$bgm = $row_dtl_gif['BGM_STAT'];
				$eqd = $row_dtl_gif['EQD_STAT'];
				$bsl = $row_dtl_gif['BOOKING_SL'];
				
				$query_flag_send = "UPDATE EDI_CODECO SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point' AND ID_TERMINAL='".$this->gtools->terminal()."'";
				$this->db->query($query_flag_send);

				fwrite($fp_gif, "UNH+".$l."+CODECO:D:95B:UN:ITG12'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "RFF+BN:".$bsl."'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "DTM+7:".$gate_tgl.":203'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "LOC+165+IDPAGTM:139:6'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "SEL+".$seal."+CA'");      //seal number
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "HAN+6'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "TDT+1++3++PAG888:172:87'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "CNT+16:1'");
				fwrite($fp_gif, PHP_EOL);

				fwrite($fp_gif, "UNT+10+".$l."'");
				fwrite($fp_gif, PHP_EOL);
				
				$l++;

			}
			//======================== create detail coarri full ==============================//

			$jmll_gif = $l-1;
			  
			fwrite($fp_gif, "UNZ+".$jmll_gif."+".$dt_tgl2."1014'");
			fclose($fp_gif);

			$queryInsertGif = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$oprves',
										'CODECO',
										'$file_name_gif',
										'E',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
										".$this->gtools->terminal().")";
			$this->db->query($queryInsertGif);
			
			array_push($file_generated, $file_name_gif);
		}
		
		$jml_gie 	= "SELECT COUNT(*) AS JML FROM EDI_CODECO 
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND TRUCK_IN_DATE IS NOT NULL
						  AND SEND_STATUS<>2
						  AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$gie 		= $this->db->query($jml_gie);
		$row_gie	= $gie->result_array();
		$jmldtl_gie = $row_gie[0]['JML'];

		if($jmldtl_gie>0) //========== CODECO GATE RECEIVING EMPTY ==========//
		{
			$file_name_gie = "GIE_".strtoupper($oprves)."_".date('ymdHis').".edi";

			//======================== create header codeco ==============================//
			$fp_gie = fopen('./edifact/codeco/'.$file_name_gie, 'w');
					  fwrite($fp_gie, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
					  fwrite($fp_gie, PHP_EOL);

			//======================== create detail codeco empty ==============================//
			$querydtl_gie = "SELECT NO_CONTAINER,
									POINT,
									ISO_CODE,
									STATUS,
									CASE WHEN TRIM(UPPER(STATUS)) = 'FULL' THEN '5' 
									 ELSE '4' END INDIKATOR,
									E_I,
									NVL(CARRIER,0) AS CARRIER,
									BL_NUMBER AS BOOKING_SL,
									NVL(SEAL_ID,0) AS SEAL_ID,
									TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
									CASE WHEN TRIM(POD) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POD) END POD,
									CASE WHEN TRIM(POL) = 'IDPJG' THEN 'IDPAG'
										ELSE TRIM(POL) END POL,
									WEIGHT,
									CASE WHEN TRIM(E_I)='E' THEN '34' 
									 ELSE '36' END BGM_STAT,
									CASE WHEN TRIM(E_I)='E' THEN '2' 
									 ELSE '3' END EQD_STAT,
									CASE WHEN TRIM(E_I)='E' THEN SUBSTR(TRUCK_IN_DATE, 1, 12) 
									 ELSE SUBSTR(TRUCK_OUT_DATE, 1, 12) END TGL_GATE
							 FROM EDI_CODECO 
							   WHERE OPR_ID = '$oprves'
								  AND E_I = 'E'
								  AND TRIM(UPPER(STATUS)) = 'EMPTY'
								  AND TRUCK_IN_DATE IS NOT NULL
								  AND SEND_STATUS<>2
								  AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$dtl_gie	= $this->db->query($querydtl_gie);
			$data_dtl_gie	= $dtl_gie->result_array();

			$p = 1;
			foreach ($data_dtl_gie as $row_dtl_gie)
			{
				$nocont = $row_dtl_gie['NO_CONTAINER'];
				$point = $row_dtl_gie['POINT'];
				$isocode = $row_dtl_gie['ISO_CODE'];
				$st = $row_dtl_gie['INDIKATOR'];
				$pol = $row_dtl_gie['POL'];
				$pod = $row_dtl_gie['POD'];
				$grt = $row_dtl_gie['WEIGHT'];
				$seal = $row_dtl_gie['SEAL_ID'];
				$gate_tgl = $row_dtl_gie['TGL_GATE'];
				$crr = $row_dtl_gie['CARRIER'];
				$bgm = $row_dtl_gie['BGM_STAT'];
				$eqd = $row_dtl_gie['EQD_STAT'];
				$bsl = $row_dtl_gie['BOOKING_SL'];
				
				$query_flag_send = "UPDATE EDI_CODECO SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point' AND ID_TERMINAL='".$this->gtools->terminal()."'";
				$this->db->query($query_flag_send);

				fwrite($fp_gie, "UNH+".$p."+CODECO:D:95B:UN:ITG12'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "BGM+".$bgm."+0000000000+9'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "NAD+CF+".$crr.":160:166'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "DTM+7:".$gate_tgl.":203'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "LOC+165+IDPAGTM:139:6'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "MEA+AAE+G+KGM:".$grt."'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "HAN+6'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "TDT+1++3++PAG888:172:87'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "CNT+16:1'");
				fwrite($fp_gie, PHP_EOL);

				fwrite($fp_gie, "UNT+10+".$p."'");
				fwrite($fp_gie, PHP_EOL);
				
				$p++;

			}
			//======================== create detail codeco empty ==============================//

			$jmlp_gie = $p-1;
			  
			fwrite($fp_gie, "UNZ+".$jmlp_gie."+".$dt_tgl2."1014'");
			fclose($fp_gie);

			$queryInsertGie = "INSERT INTO EDI_GENERATE_LOGFILE 
								(ID_VES_VOYAGE,
								 EDI_TYPE,
								 FILE_NAME,
								 E_I,
								 STATUS,
								 CREATED_BY,
								 CREATED_DATE,
								 ID_EDI,
								 ID_TERMINAL)
								VALUES ('$oprves',
										'CODECO',
										'$file_name_gie',
										'E',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval,
										".$this->gtools->terminal().")";
			$this->db->query($queryInsertGie);
			
			array_push($file_generated, $file_name_gie);
		}
		
		if ($this->db->trans_complete()){
			return array('flag'=>1, 'msg'=>'OK', 'files'=>$file_generated);
		}else{
			return array('flag'=>0, 'msg'=>'error generate baplie');
		}
		
		return 'OK';
	}
	
	public function get_data_bpe_list($id_ves_voyage){
		$query 		= "SELECT A.ID_VES_VOYAGE, 
							  A.EDI_TYPE,
							  A.FILE_NAME,
							  A.E_I,
							  A.STATUS,
							  TO_CHAR(A.CREATED_DATE,'DD/MM/YYYY HH24:MI:SS') CREATED_DATE,
							  B.FULL_NAME
					   FROM EDI_GENERATE_LOGFILE A, M_USERS B
					   WHERE TRIM(A.ID_VES_VOYAGE) = '$id_ves_voyage'
					   	AND A.CREATED_BY = B.ID_USER
					   	AND A.EDI_TYPE = 'BAPLIE'
					   	AND A.ID_TERMINAL = '".$this->gtools->terminal()."'
					   	AND B.ID_TERMINAL = '".$this->gtools->terminal()."'
					   ORDER BY A.ID_EDI DESC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	//get_data_container_hk
	public function get_data_container_hk(){
		$a=$_POST['no_container'];
		$b=$_POST['ei'];
		$query 		= "SELECT NO_CONTAINER, POINT, ID_VES_VOYAGE, ID_ISO_CODE
					   FROM CON_LISTCONT
					   WHERE TRIM(NO_CONTAINER) = '$a'
					   	AND ID_CLASS_CODE='$b' and ID_TERMINAL='".$this->gtools->terminal()."' and ID_OP_STATUS IN ('YGY','YYY','YIF','YSY')";
		$rs 		= $this->db->query($query);
		$data 		= $rs->row_array();
		
		return $data;
	}
	
	public function insert_container_hk(){
		$a=$_POST['no_container'];
		$b=$_POST['hkp_id'];
		$c=$_POST['point'];
		$out = '';
		$out_message = '';
		
		$param = array(
				array('name'=>':param', 'value'=>$a.'^'.$b.'^'.$c, 'length'=>100),
				array('name'=>':v_terminal', 'value'=>$this->gtools->terminal(), 'length'=>100),
				array('name'=>':v_out', 'value'=>&$out, 'length'=>100),
				array('name'=>':v_out_message', 'value'=>&$out_message, 'length'=>100)
			);
		// print_r($param);
		$this->db->trans_start();
		$query = "begin prc_add_container_hk(:param,:v_terminal,:v_out,:v_out_message); end;";
		$this->db->exec_bind_stored_procedure($query, $param);
		
		if ($this->db->trans_complete()){
			if($out == 'F'){
			    return 0;
			}else{
			    return 1;
			}
		}else{
			return 0;
		}
		
		return $data;
	}
	
	public function del_container_hk(){
		$a=$_POST['NO_CONTAINER'];
		$b=$_POST['HKP_ID'];
		$c=$_POST['POINT'];
		
		
		$this->db->trans_start();
		$query = "delete from con_hkp_plan_d where no_container='$a' and point='$c' and hkp_id='$b'";
		
		$this->db->query($query);
		
		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
		
		return $data;
	}
	
	public function edi_bpe($id_ves_voyage,$id_user){
		$terminalcode = $this->config->item('SITE_EDI_TERMINAL_CODE');
		$this->db->trans_start();
		
		//======================== POPULATE DATA ==============================//
		$query_data	= "BEGIN ITOS_OP.proc_populate_data_baplie('$id_ves_voyage','E','$id_user'); END;";
		$this->db->query($query_data);
		//======================== POPULATE DATA ==============================//

		//======================== create header baplie ==============================//
		$queryhdr 	= "SELECT ID_VESSEL,
							  VOY_IN,
							  VOY_OUT,
							  VESSEL_NAME,
							  TO_CHAR(ETA,'RRRRMMDD') ETA_DATE,
							  TO_CHAR(ETA,'HH24MISS') ETA_HR,
							  TO_CHAR(ETB,'RRRRMMDDHH24MISS') ETB,
							  TO_CHAR(ETD,'RRRRMMDD') ETD_DATE,
							  TO_CHAR(ETD,'HH24MISS') ETD_HR,
							  OPERATOR,
							  CALL_SIGN
					   FROM VES_VOYAGE 
					   WHERE TRIM(ID_VES_VOYAGE) = UPPER('$id_ves_voyage') AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$hdr 		= $this->db->query($queryhdr);
		$row_hdr	= $hdr->result_array();

		$idves = $row_hdr[0]['ID_VESSEL'];
		$voyin = $row_hdr[0]['VOY_IN'];
		$voyout = $row_hdr[0]['VOY_OUT'];
		$vesnm = $row_hdr[0]['VESSEL_NAME'];
		$eta_date = $row_hdr[0]['ETA_DATE'];
		$eta_hr = $row_hdr[0]['ETA_HR'];
		$etb = $row_hdr[0]['ETB'];
		$etd_date = $row_hdr[0]['ETD_DATE'];
		$etd_hr = $row_hdr[0]['ETD_HR'];
		$cs = $row_hdr[0]['CALL_SIGN'];
		$oprves = $row_hdr[0]['OPERATOR'];
		// $oprves = 'MAEU';

		$file_name = "BPE_".strtoupper($id_ves_voyage)."_".date('ymdHis').".edi";

		$fp = fopen('./edifact/baplie/'.$file_name, 'w');
			  fwrite($fp, "UNB+UNOA:1+".$terminalcode."+".$oprves."+".$eta_date.":".$eta_hr."+0+++++0'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "UNH+1+BAPLIE:1:911:UN:SMDG15'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "BGM++0+9'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "DTM+137:".$eta_date.$eta_hr.":201'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "TDT+20+".$voyout."++".$cs.":103::".$vesnm."++0:172:20'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "LOC+5+IDPAG:139:6'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "LOC+61+TWKHH:139:6'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "DTM+178:".$eta_date."0000:201'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "DTM+136:".$eta_date."0000:201'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "DTM+132:".$eta_date.":101'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "RFF+VON:".$voyout."'");
			  fwrite($fp, PHP_EOL);
		//======================== create header baplie ==============================//

		//======================== create detail baplie ==============================//
		$querydtl 	= "SELECT NO_CONTAINER,
							  ISO_CODE,
							  CASE WHEN TRIM(UPPER(STATUS)) = 'FCL' THEN '5' 
								 ELSE '4' END STATUS,
							  POD,
							  POL,
							  CARRIER,
							  WEIGHT,
							  HZ,
							  SEAL_ID,
							  LOKASI_BP,
							  TEMP,
							  IMO,
							  UN_NUMBER,
							  HANDLING_INST,
							  OVER_FRONT,
							  OVER_TOP,
							  (OVER_LEFT+OVER_RIGHT) OVER_WIDTH
					   FROM EDI_BAPLIE 
					   WHERE CLASS_CODE = 'E'
					   AND TRIM(ID_VES_VOYAGE) = UPPER('$id_ves_voyage') AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$dtl 		= $this->db->query($querydtl);
		$data_dtl	= $dtl->result_array();

		$n = 0;
		foreach ($data_dtl as $row_dtl)
		{
			$nocont = $row_dtl['NO_CONTAINER'];
			$isocode = $row_dtl['ISO_CODE'];
			$status = $row_dtl['STATUS'];
			$idpod = $row_dtl['POD'];
			$idpol = $row_dtl['POL'];
			$opr = $row_dtl['CARRIER'];
			$wgt = $row_dtl['WEIGHT'];
			$hz = $row_dtl['HZ'];
			$slnumb = $row_dtl['SEAL_ID'];
			$locbp = $row_dtl['LOKASI_BP'];
			$temp = $row_dtl['TEMP'];
			$imo = $row_dtl['IMO'];
			$un = $row_dtl['UN_NUMBER'];
			$hi = $row_dtl['HANDLING_INST'];
			$hgh = $row_dtl['HEIGHT'];
			$ol = $row_dtl['OVER_FRONT'];
			$ow = $row_dtl['OVER_WIDTH'];
			$oh = $row_dtl['OVER_TOP'];

			fwrite($fp, "LOC+147+".$locbp."::5'");
			fwrite($fp, PHP_EOL);
			
			if($hi<>'')
			{
				fwrite($fp, "FTX+HAN+++".$hi."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			fwrite($fp, "MEA+WT++KGM:".$wgt."'");
			fwrite($fp, PHP_EOL);
			
			if($ty_cont=='RFR')
			{
				fwrite($fp, "TMP+2+".$temp."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			if(TRIM($hgh)=='OOG')
			{
				fwrite($fp, "DIM+9+CM:".$ol.":".$ow.":".$oh."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			fwrite($fp, "LOC+6+".$idpol."'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "LOC+12+".$idpod."'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "LOC+83+".$idpod."'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "RFF+BM:1'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "EQD+CN+".$nocont."+".$isocode."+++".$status."'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "NAD+CA+".$opr.":172:ZZZ'");
			fwrite($fp, PHP_EOL);
			
			if($hz=='Y')
			{
				fwrite($fp, "DGS+IMD+".$imo."+".$un."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			$n=$n+8;

		}
		//======================== create detail baplie ==============================//

		$jml_n = $n;
		  
		fwrite($fp, "UNT+".$jml_n."+MAG13040911183'");
		fwrite($fp, PHP_EOL);
		fwrite($fp, "UNZ+1+MAG13040911183'");
		fwrite($fp, PHP_EOL);
		fclose($fp);

		$queryInsertBpe = "INSERT INTO EDI_GENERATE_LOGFILE 
							(ID_VES_VOYAGE,
							 EDI_TYPE,
							 FILE_NAME,
							 E_I,
							 STATUS,
							 CREATED_BY,
							 CREATED_DATE,
							 ID_EDI,
							 ID_TERMINAL)
							VALUES ('$id_ves_voyage',
									'BAPLIE',
									'$file_name',
									'E',
									'ALL',
									'$id_user',
									SYSDATE,
									seq_edi_generate.nextval,
									".$this->gtools->terminal().")";
		$this->db->query($queryInsertBpe);
		
		if ($this->db->trans_complete()){
			return array('flag'=>1, 'msg'=>'OK');
		}else{
			return array('flag'=>0, 'msg'=>'error generate baplie');
		}
		
		return 'OK';
	}		
	
	public function get_container_outbound_stacking_list($id_ves_voyage, $paging=false, $sort=false, $filters=false){
		
		$qPaging = '';
		if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}
		
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			if ($sortProperty=='YARD_POS'){
				$sortProperty = 'YD_BLOCK_NAME';
			}
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		
		$qWhere = "WHERE 1=1 AND B.ID_VES_VOYAGE='$id_ves_voyage' AND B.ID_CLASS_CODE IN ('E','TE')";
		
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case 'NO_CONTAINER'	: $field = "B.".$field; break;
					case 'ID_ISO_CODE'	: $field = "B.".$field; break;
					case 'CONT_SIZE'	: $field = "B.".$field; break;
					case 'CONT_TYPE'	: $field = "B.".$field; break;
					case 'CONT_STATUS'	: $field = "B.".$field; break;
					case 'HAZARD'	: $field = "B.".$field; break;
					case 'WEIGHT'	: $field = "B.".$field; break;
					case 'ID_POD'	: $field = "B.".$field; break;
					case 'YD_BLOCK_NAME'	: $field = "B.".$field; break;
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}

		/*$query = "SELECT B.*
				  	FROM (SELECT V.*, ROWNUM REC_NUM
							FROM ( SELECT
								A.NO_CONTAINER,
								B.CONT_SIZE,
								B.ID_ISO_CODE,
								B.CONT_STATUS,
								A.ID_CLASS_CODE,
								B.WEIGHT/1000 as WEIGHT,
								B.SEAL_NUMB,
								B.GT_DATE,
								CASE
									WHEN B.OP_STATUS_DESC IN ('Loaded','Loaded from Shifting') THEN 'Loaded'
									WHEN B.OP_STATUS_DESC = 'Gate Out' THEN 'Gate Out'
									WHEN B.OP_STATUS_DESC IN ('Gate In to Ship','Gate In to Yard') THEN 'Gate In'
									WHEN B.OP_STATUS_DESC = 'Stacking' THEN 'Yard'
									WHEN B.OP_STATUS_DESC = 'Transhipment' THEN 'Transhipment'
									WHEN B.OP_STATUS_DESC IN ('On Chassised for Gate Out','On Chassised for Loading','On Chassised for Move In Yard', '') THEN 'On Chassis'
									ELSE '-'
								END AS STATUS,
								A.BLOCK_ || '-' || A.SLOT_ || '-' || A.ROW_ || '-' || A.TIER AS LOCATION,
								CASE
									WHEN A.ID_CLASS_CODE = 'TE' 
										THEN (SELECT VESSEL_NAME FROM VES_VOYAGE WHERE ID_VES_VOYAGE = (SELECT ID_VES_VOYAGE FROM CON_LISTCONT WHERE ID_CLASS_CODE = 'TI' AND NO_CONTAINER = A.NO_CONTAINER))
									ELSE '-'
								END AS IN_VESSEL,
								C.VESSEL_NAME AS OUT_VESSEL,
								C.VOY_OUT AS OUT_VOYAGE,
								B.ID_OPERATOR,
								B.ID_POD,
								'-' AS CANCEL,
								B.HOLD_CONTAINER,
								B.ID_COMMODITY,
								B.TEMP,
								CASE 
									WHEN B.CONT_HEIGHT = 'OOG' THEN 'Y'
									ELSE 'N'
								END AS OOG,
								B.IMDG,
								B.UNNO
							FROM
								JOB_PLACEMENT A
							INNER JOIN CON_LISTCONT B ON A.NO_CONTAINER = B.NO_CONTAINER AND A.POINT = B.POINT
							INNER JOIN VES_VOYAGE C ON C.ID_VES_VOYAGE=B.ID_VES_VOYAGE
							$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."'
						$qSort) V
				) B
					$qPaging";*/

		$query = "SELECT B.*
				  	FROM (SELECT V.*, ROWNUM REC_NUM
							FROM ( SELECT
								B.NO_CONTAINER,
								B.CONT_SIZE,
								B.ID_ISO_CODE,
								B.CONT_STATUS,
								CASE
									WHEN B.ID_CLASS_CODE = 'TE' 
										THEN 'Transhipment'
									ELSE 'E'
								END AS ID_CLASS_CODE,
								B.WEIGHT,
								B.SEAL_NUMB,
								TO_CHAR(B.GT_DATE,'DD-MM-YYYY HH24:MI AM') as GT_DATE,
								B.ID_OP_STATUS,
								CASE
									WHEN B.OP_STATUS_DESC = 'Loaded' THEN 'Loaded'
									WHEN B.OP_STATUS_DESC = 'Stacking' THEN 'Yard'
									WHEN B.OP_STATUS_DESC IN ('On Chassised for Gate Out','On Chassised for Loading','On Chassised for Move In Yard') THEN 'On Chassis'
									ELSE '-'
								END AS STATUS,
								B.YD_BLOCK_NAME || '-' || B.YD_SLOT || '-' || B.YD_ROW || '-' || B.YD_TIER AS LOCATION,
								CASE
									WHEN B.ID_CLASS_CODE = 'TE' 
										THEN (SELECT VV.VESSEL_NAME FROM CON_LISTCONT CC INNER JOIN VES_VOYAGE VV ON CC.ID_VES_VOYAGE=VV.ID_VES_VOYAGE WHERE CC.ID_CLASS_CODE = 'TI' AND CC.NO_CONTAINER = B.NO_CONTAINER)
									ELSE C.VESSEL_NAME
								END AS IN_VESSEL,
								CASE
									WHEN B.ID_CLASS_CODE = 'TE' 
										THEN (SELECT VV.VOY_IN || '/' || VV.VOY_OUT AS IN_VOY FROM CON_LISTCONT CC INNER JOIN VES_VOYAGE VV ON CC.ID_VES_VOYAGE=VV.ID_VES_VOYAGE WHERE CC.ID_CLASS_CODE = 'TI' AND CC.NO_CONTAINER = B.NO_CONTAINER)
									ELSE C.VOY_IN || '/' || C.VOY_OUT
								END AS IN_VOYAGE,
								C.VESSEL_NAME AS OUT_VESSEL,
								C.VOY_IN || '/' || C.VOY_OUT AS OUT_VOYAGE,
								B.ID_OPERATOR,
								B.ID_POD,
								CASE
									WHEN B.ID_CLASS_CODE = 'TE' 
										THEN 'TE'
									ELSE ''
								END AS TRANSHIPMENT,
								B.HOLD_CONTAINER,
								B.ID_COMMODITY,
								B.TEMP,
								CASE 
									WHEN B.CONT_HEIGHT = 'OOG' THEN 'Y'
									ELSE 'N'
								END AS OOG,
								B.IMDG,
								B.UNNO
							FROM CON_LISTCONT B
							INNER JOIN VES_VOYAGE C ON C.ID_VES_VOYAGE=B.ID_VES_VOYAGE
							$qWhere AND B.ID_TERMINAL = '".$this->gtools->terminal()."'
								AND B.ID_OP_STATUS <> 'DIS'
							$qSort) V
				) B
					$qPaging";
		//debux($query);die;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();

		$query_count = "SELECT COUNT(NO_CONTAINER) TOTAL
						FROM CON_LISTCONT
						WHERE ID_VES_VOYAGE='$id_ves_voyage' AND ID_CLASS_CODE IN ('E','TE') AND ID_OP_STATUS <> 'DIS' AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];

		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function getAll_container_outbound_stacking_list($id_ves_voyage){
		$query = "SELECT
					B.NO_CONTAINER,
					B.CONT_SIZE,
					B.ID_ISO_CODE,
					B.CONT_STATUS,
					CASE
						WHEN B.ID_CLASS_CODE = 'TE' 
							THEN 'Transhipment'
						ELSE 'E'
					END AS ID_CLASS_CODE,
					B.WEIGHT,
					B.SEAL_NUMB,
					B.GT_DATE,
					B.ID_OP_STATUS,
					CASE
						WHEN B.OP_STATUS_DESC = 'Loaded' THEN 'Loaded'
						WHEN B.OP_STATUS_DESC = 'Stacking' THEN 'Yard'
						WHEN B.OP_STATUS_DESC IN ('On Chassised for Gate Out','On Chassised for Loading','On Chassised for Move In Yard') THEN 'On Chassis'
						ELSE '-'
					END AS STATUS,
					B.YD_BLOCK_NAME || '-' || B.YD_SLOT || '-' || B.YD_ROW || '-' || B.YD_TIER AS LOCATION,
					CASE
						WHEN B.ID_CLASS_CODE = 'TE' 
							THEN (SELECT VV.VESSEL_NAME FROM CON_LISTCONT CC INNER JOIN VES_VOYAGE VV ON CC.ID_VES_VOYAGE=VV.ID_VES_VOYAGE WHERE CC.ID_CLASS_CODE = 'TI' AND CC.NO_CONTAINER = B.NO_CONTAINER)
						ELSE C.VESSEL_NAME
					END AS IN_VESSEL,
					CASE
						WHEN B.ID_CLASS_CODE = 'TE' 
							THEN (SELECT VV.VOY_IN || '/' || VV.VOY_OUT AS IN_VOY FROM CON_LISTCONT CC INNER JOIN VES_VOYAGE VV ON CC.ID_VES_VOYAGE=VV.ID_VES_VOYAGE WHERE CC.ID_CLASS_CODE = 'TI' AND CC.NO_CONTAINER = B.NO_CONTAINER)
						ELSE C.VOY_IN || '/' || C.VOY_OUT
					END AS IN_VOYAGE,
					C.VESSEL_NAME AS OUT_VESSEL,
					C.VOY_IN || '/' || C.VOY_OUT AS OUT_VOYAGE,
					B.ID_OPERATOR,
					B.ID_POD,
					CASE
						WHEN B.ID_CLASS_CODE = 'TE' 
							THEN 'TE'
						ELSE ''
					END AS TRANSHIPMENT,
					B.HOLD_CONTAINER,
					B.ID_COMMODITY,
					B.TEMP,
					CASE 
						WHEN B.CONT_HEIGHT = 'OOG' THEN 'Y'
						ELSE 'N'
					END AS OOG,
					B.IMDG,
					B.UNNO
				FROM CON_LISTCONT B
				INNER JOIN VES_VOYAGE C ON C.ID_VES_VOYAGE=B.ID_VES_VOYAGE
				WHERE
					B.ID_VES_VOYAGE='$id_ves_voyage' AND B.ID_CLASS_CODE IN ('E','TE')
					AND B.ID_TERMINAL = '".$this->gtools->terminal()."'
					AND B.ID_OP_STATUS <> 'DIS'
				ORDER BY
					NO_CONTAINER ASC";
		// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();

		//debux($container_list);die;
		return $container_list;
	}
	
	public function plan_container_hkp(){
		
		$params=$_POST['NO_CONTAINER'].'^'.$_POST['HKP_ID'].'^'.$_POST['POINT'].'^'.$_POST['BLOCK_ID'].'^'.$_POST['SLOT'].'^'.$_POST['ROW'].'^'.$_POST['TIER'];
		$param = array(
				array('name'=>':param', 'value'=>$params, 'length'=>100),
				array('name'=>':v_out', 'value'=>&$msg_out, 'length'=>100)
			);
		// print_r($param);
		$this->db->trans_start();
		$query = "begin prc_plan_hkp_d(:param, :v_out); end;";
		$this->db->exec_bind_stored_procedure($query, $param);
		
		/*if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}*/
		if ($this->db->trans_complete()) { 
			if($msg_out!='Ok') {
				 return $msg_out;
			} else {
				 return "Ok";
			}
		} else {
			 return $msg_out;
		}
		 
	}
	
	public function activate_hkp(){
		
		$params=$_POST['HKP_ID'];
		$param = array(
				array('name'=>':param', 'value'=>$params, 'length'=>100),
				array('name'=>':v_terminal', 'value'=>$this->gtools->terminal(), 'length'=>100)
			);
//		 print_r($param);exit;
		$this->db->trans_start();
		$query = "begin prc_activate_hkp(:param,:v_terminal); end;";
		$this->db->exec_bind_stored_procedure($query, $param);
		
		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function deactivate_hkp(){
		
		$params=$_POST['HKP_ID'];
		$param = array(
				array('name'=>':param', 'value'=>$params, 'length'=>100)
			);
//		 print_r($param);exit;
		$this->db->trans_start();
		$query = "begin prc_deactivate_hkp(:param); end;";
		$this->db->exec_bind_stored_procedure($query, $param);
		
		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function edi_service_flag_send($flag){
		$query = "UPDATE EDI_COARRI SET SEND_STATUS='$flag' WHERE SEND_STATUS=1 AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$this->db->query($query);

		$query = "UPDATE EDI_CODECO SET SEND_STATUS='$flag' WHERE SEND_STATUS=1 AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$this->db->query($query);
	}
	
	public function create_hk_plan(){
		$hkp_mvdesc=$_POST['mv_Desc'];
		$itv_use=$_POST['itv_use'];
		$mv_order=$_POST['mv_Order'];
		$virt_crane=$_POST['virtual_crane'];
		$iduser=$this->session->userdata('id_user');
		
		$query = "INSERT INTO CON_HKP_PLAN_H (HKP_MV_DESC, ITV_USE, ID_MACHINE, CREATED_ID_USER, HKP_STATUS, HKP_ACTIVITY, ID_TERMINAL)
					values
					('$hkp_mvdesc','$itv_use','$virt_crane','$iduser','N','$mv_order', ".$this->gtools->terminal().")";
		$this->db->query($query);
	}
	
	public function content_hk_grid(){
		$query_count = "SELECT COUNT(1) TOTAL
						FROM con_hkp_plan_h
						WHERE HKP_STATUS!='C' AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		
		$query = "SELECT A.HKP_ID, A.HKP_MV_DESC, A.ITV_USE, B.MCH_NAME, A.HKP_STATUS, C.HKP_ACTIVITY_DESC
                          FROM CON_HKP_PLAN_H A 
			  LEFT JOIN M_MACHINE B ON A.ID_MACHINE=B.ID_MACHINE
						  JOIN M_HKP_ACTIVITY C ON A.HKP_ACTIVITY=C.HKP_ACTIVITY
                          WHERE A.HKP_STATUS!='C' AND A.ID_TERMINAL='".$this->gtools->terminal()."'";
//		 print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function content_hk_gridcont($hkp_id){
		$query_count = "SELECT COUNT(1) TOTAL
						FROM con_hkp_plan_d
						WHERE HKP_ID='$hkp_id' AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		
		$query = "SELECT A.NO_CONTAINER, A.POINT, B.ID_ISO_CODE, A.ID_VES_VOYAGE, A.HKP_STATUS_CONT, fc_get_hkpstatus_name(A.HKP_STATUS_CONT) as STATUS_NAME,
		(SELECT C.YD_BLOCK_NAME||' '||C.YD_SLOT||'-'||C.YD_ROW||'-'||C.YD_TIER FROM CON_LISTCONT C WHERE C.NO_CONTAINER = A.NO_CONTAINER AND C.POINT = A.POINT AND ID_TERMINAL='".$this->gtools->terminal()."') as LOC_CON_REAL,
		A.GT_JS_BLOCK_NAME||' '||A.GT_JS_SLOT||'-'||A.GT_JS_ROW||'-'||A.GT_JS_TIER as LOC_CON_PLAN
                          FROM CON_HKP_PLAN_D A JOIN CON_LISTCONT B ON A.NO_CONTAINER=B.NO_CONTAINER AND A.POINT=B.POINT
                          WHERE A.HKP_ID=$hkp_id AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND B.ID_TERMINAL='".$this->gtools->terminal()."'";
		//debux($query);die;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function get_data_container_hkp($no_container, $point=false){
		$param = array($no_container,$this->gtools->terminal());
		$param2 = array($no_container,$this->gtools->terminal(),$this->gtools->terminal(),$this->gtools->terminal());
		$query = "SELECT NO_CONTAINER, MAX(POINT) POINT
					FROM CON_LISTCONT
					WHERE NO_CONTAINER = ? AND ID_TERMINAL=?
					GROUP BY NO_CONTAINER";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row_array();
		$max_point = $data['POINT'];
		
		$qWhere = '';
		if ($max_point && $point){
			if ($point>$max_point){
				$point = $max_point;
			}
			array_push($param, $point);
			$qWhere = ' AND C.POINT=? ';
		}
		$query = "
			SELECT mcc.code_name class_code_name,
				 mcs.name cont_status_name,
				 c.id_iso_code,
				 mcsi.name cont_size_name,
				 mch.name cont_height_name,
				 c.id_operator || '-' || mo.operator_name cont_operator_name,
				 mccom.commodity_name,
				 mct.name cont_type_name,
				 c.weight,
				 c.vs_bay,
				 c.vs_row,
				 c.vs_tier,
				 vv.id_vessel || '-' || vv.vessel_name vessel,
				 vv.point || '/' || vv.year visit,
				 vv.voy_in || '/' || vv.voy_out voyage,
				 (SELECT port_code || '-' || port_name
					FROM M_PORT
				   WHERE port_code = c.id_pol)
					pol_name,
				 (SELECT port_code || '-' || port_name
					FROM M_PORT
				   WHERE port_code = c.id_pod)
					pod_name,
				 (SELECT port_code || '-' || port_name
					FROM M_PORT
				   WHERE port_code = c.id_por)
					por_name,
				 DECODE (jgm.payment_status, 1, 'Y', 'N') payment,
				 TO_CHAR(jgm.paythrough_date, 'DD-MM-YYYY HH24:MI:SS') paythrough_date,
				 jgm.trx_number,
				 mos.id_op_status,
				 mos.op_status_desc,
				 mos.op_status_group cont_location,
				 c.no_container,
				 c.point,
				 c.cont_height,
				 c.cont_size,
				 c.cont_type,
				 c.cont_status,
				 c.id_class_code,
				 c.id_operator,
				 c.id_commodity,
				 c.id_pol,
				 c.id_pod,
				 c.id_por,
				 vv.id_ves_voyage,
				 c.yd_block_name,
				 c.yd_slot,
				 c.yd_row,
				 c.yd_tier,
				 c.temp,
				 c.unno,
				 c.imdg,
				 c.seal_numb,
				 c.tl_flag
			FROM CON_LISTCONT c,
				 M_CLASS_CODE mcc,
				 M_CONT_STATUS mcs,
				 M_CONT_SIZE mcsi,
				 M_CONT_HEIGHT mch,
				 M_OPERATOR mo,
				 M_CONT_COMMODITY mccom,
				 M_CONT_TYPE mct,
				 VES_VOYAGE vv,
				 JOB_GATE_MANAGER jgm,
				 M_OP_STATUS mos
		   WHERE     c.NO_CONTAINER = ? AND c.ID_TERMINAL=? AND vv.ID_TERMINAL=? AND jgm.ID_TERMINAL=? AND C.ACTIVE='Y' $qWhere
				 AND c.id_class_code = mcc.id_class_code(+)
				 AND c.cont_status = mcs.cont_status(+)
				 AND c.cont_size = mcsi.cont_size(+)
				 AND c.cont_height = mch.cont_height(+)
				 AND c.id_operator = mo.id_operator(+)
				 AND c.id_commodity = mccom.id_commodity(+)
				 AND c.cont_type = mct.cont_type(+)
				 AND c.id_ves_voyage = vv.id_ves_voyage(+)
				 AND c.no_container = jgm.no_container(+)
				 AND c.point = jgm.point(+)
				 AND c.id_op_status = mos.id_op_status(+)
		ORDER BY c.point DESC
		";
		$rs 		= $this->db->query($query, $param2);
		$data 		= $rs->row_array();
		
		if ($data){
			$data['STOWAGE'] = '';
			if ($data['VS_BAY']!=''){
				$data['STOWAGE'] = str_pad($data['VS_BAY'],2,'0',STR_PAD_LEFT).str_pad($data['VS_ROW'],2,'0',STR_PAD_LEFT).str_pad($data['VS_TIER'],2,'0',STR_PAD_LEFT);
			}
			if ($data['YD_BLOCK_NAME']!=''){
				$data['YARD_POS'] = $data['YD_BLOCK_NAME'].'-'.$data['YD_SLOT'].'-'.$data['YD_ROW'].'-'.$data['YD_TIER'];
			}
		}
		
		return $data;
	}
	
	public function upload_npe($POST, $FILES, $id_user){
		$flag = true;
		$msg = "";
		/*
		print_r($FILES[file][name]." - ");
		print_r($POST['ID_VES_VOYAGE']." - "); 
		print_r($FILES[file][size]);
		die;
		*/
		if ($FILES[file][size] > 0) {
			$type = substr($FILES[file][name],strrpos($FILES[file][name],'.')+1);
			if (strtolower($type)=='csv')
			{
				$file = $FILES[file][tmp_name];
				$handle = fopen($file,"r");
				$id_ves_voyage = $POST['ID_VES_VOYAGE'];
				$modus = $POST['method'];
				
				$param = array($id_ves_voyage);				
				$i = 0;
				$j = 0;
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					try {
						$param = array();
						$tc_nonactive = false;
						
						//print_r($i);die;
						if($i>0) {							
							// print_r($data);
							$param[] = $data[1]; // npe number
							$param[] = $data[2]; // npe date DDMMYYYY
							$param[] = str_replace(' ', '',$data[0]); // no container
							$param[] = $id_ves_voyage; // id ves voyage
							$param[] = $this->gtools->terminal(); //id terminal						
							//print_r($param);
							
							$query = "UPDATE CON_LISTCONT
											SET NPE=?, TGL_NPE = TO_DATE(?,'DDMMYYYY')
										WHERE NO_CONTAINER=? AND ID_VES_VOYAGE=? AND ID_TERMINAL=?";
							$this->db->query($query, $param);
							//print_r($query);
							// if ($retval){
							// 	$j++;
							// }else{
							// 	$msg .=$this->db->_error_message().",";
							// }
							//$flag = $flag && $retval;
							//$msg .=$this->db->_error_message()." ,";
						    $flag = 1;
						}
					} catch (Exception $e){
						$msg .= "<br/>No Container: ".str_replace(' ', '',$data[5])." Error ".$e->getMessage().", ";
						$flag = 0;
					}
					$i++;
				}
				fclose($handle);
			} 
			else 
			{
				$flag = 0;
				$msg = "file not in csv format";
			}
		} 
		else 
		{
			$flag = 0;
			$msg = "file not found";
		}
		
		return array('flag'=>$flag, 'msg'=>$msg);
	}	
	
	public function save_cancel_tl2($data,$id_user){
		$msg_complete = '';
		$container_data = json_decode($data['container_data']);
		$remarks = 'test';
		$msg_out = '';
		for ($i=0;$i<sizeof($container_data);$i++){
			$no_container = $container_data[$i]->NO_CONTAINER;
			$point = $container_data[$i]->POINT;
			$desc = $container_data[$i]->ID_OP_STATUS;
			$id_ves_voyage = $container_data[$i]->ID_VES_VOYAGE;
			
			$param = array(
				array('name'=>':v_no_container', 'value'=>$no_container, 'length'=>15),
				array('name'=>':v_point', 'value'=>$point, 'length'=>10),
				array('name'=>':v_remarks', 'value'=>$point, 'length'=>10),
				array('name'=>':v_id_user', 'value'=>$id_user, 'length'=>10),
				array('name'=>':v_terminal', 'value'=>$this->gtools->terminal(), 'length'=>10),
				array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>500)
			);
			$query = "BEGIN PROC_CANCEL_TL(:v_no_container, :v_point, :v_remarks, :v_id_user, :v_terminal, :v_msg_out); end;";
			$this->db->exec_bind_stored_procedure($query, $param);
			// echo $query;die;
			if ($msg_out!=''){
				$msg_complete .= $no_container." FAILED ".$msg_out."<br/>";
			}
//			echo '<pre>';print_r($param);echo '</pre>';
//			echo '<pre>'.$query.'</pre>';
		}
//		exit;
		if ($msg_complete==''){
			return array(
						'success'=>true,
						'errors'=> 'Save success'
					);
		}else{
			return array(
						'success'=>false,
						'errors'=> $msg_out
					);
		}
	}
	
	public function get_data_cancel_tl_list($container_list=false, $paging=false, $sort=false, $filters=false){
		$q_in_con = '';
		if ($container_list){
			$q_in_con = " AND A.NO_CONTAINER IN (".$container_list.") ";
		}
		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM CON_LISTCONT A
						WHERE A.active='Y' and A.tl_flag='Y' and A.id_class_code='I' and (A.ID_OP_STATUS ='SDG' or A.ID_OP_STATUS='BPL') $q_in_con AND A.ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
	$qWhere = "WHERE 1=1 AND A.active='Y' and A.tl_flag='Y' and A.id_class_code='I' and (A.ID_OP_STATUS ='SDG' or A.ID_OP_STATUS='BPL') $q_in_con";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case'NO_CONTAINER'	: $field = "A.".$field; break;
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													A.ID_VES_VOYAGE,
													A.ID_ISO_CODE,
													A.ID_CLASS_CODE,
													A.ID_OPERATOR,
													A.CONT_STATUS,
													A.TL_FLAG,
													A.ID_OP_STATUS,
													A.OP_STATUS_DESC
										FROM 
											CON_LISTCONT A
										$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."'
										$qSort) V
							) B
						$qPaging";
		// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		for ($i=0; $i<sizeof($container_list); $i++){
			$container_list[$i]['EDIT_VESSEL'] = 0;
			$container_list[$i]['EDIT_TL'] = 0;
		}
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}

	public function validasi_req_sp2($no_container)
	{
		$this->db->trans_start();
		$sql = "SELECT COUNT(*) AS JML FROM ITOS_BILLING.REQ_DELIVERY_D WHERE NO_CONTAINER LIKE '%$no_container%'";
		$row = $this->db->query($sql)->row();
		return $row->JML;

	}
	
	public function get_container_virtual_block($id_ves_voyage = '',$paging=false, $sort=false, $filters=false){
	    $qPaging = '';
	    if ($paging != false) {
		$start = $paging['start'] + 1;
		$end = $paging['page'] * $paging['limit'];
		$qPaging = "HAVING ROWNUM >= $start AND ROWNUM <= $end";
	    }
	    $qSort = '';
	    if ($sort != false) {
		$sortProperty = $sort[0]->property;
		$sortDirection = $sort[0]->direction;
		$qSort .= " ORDER BY " . $sortProperty . " " . $sortDirection;
	    }
	    $qWhere = "WHERE B.YD_BLOCK = 0 AND B.ITT_FLAG = 'N' AND B.ID_OP_STATUS <> 'SLG'";
	    if($id_ves_voyage != ''){
		$qWhere  .= " AND B.ID_VES_VOYAGE='$id_ves_voyage'";
	    }
	    $qs = '';
	    $encoded = true;
	    if ($filters != false) {
		for ($i = 0; $i < count($filters); $i++) {
		    $filter = $filters[$i];

		    // assign filter data (location depends if encoded or not)
		    if ($encoded) {
			$field = $filter->field;
			$value = $filter->value;
			$compare = isset($filter->comparison) ? $filter->comparison : null;
			$filterType = $filter->type;
		    } else {
			$field = $filter['field'];
			$value = $filter['data']['value'];
			$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
			$filterType = $filter['data']['type'];
		    }

		    switch ($field) {
			case'NO_CONTAINER' : $field = "D.$field";
			    break;
			case'NO_POL' : $field = "$field";
			    break;
		    }

		    switch ($filterType) {
			case 'string' : $qs .= " AND LOWER(" . $field . ") LIKE '%" . strtolower($value) . "%'";
			    Break;
			case 'list' :
			    if (strstr($value, ',')) {
				$fi = explode(',', $value);
				for ($q = 0; $q < count($fi); $q++) {
				    $fi[$q] = "'" . $fi[$q] . "'";
				}
				$value = implode(',', $fi);
				$qs .= " AND LOWER(" . $field . ") IN (" . strtolower($value) . ")";
			    } else {
				$qs .= " AND LOWER(" . $field . ") = '" . strtolower($value) . "'";
			    }
			    Break;
			case 'boolean' : $qs .= " AND " . $field . " = " . ($value);
			    Break;
			case 'numeric' :
			    switch ($compare) {
				case 'eq' : $qs .= " AND " . $field . " = " . $value;
				    Break;
				case 'lt' : $qs .= " AND " . $field . " < " . $value;
				    Break;
				case 'gt' : $qs .= " AND " . $field . " > " . $value;
				    Break;
			    }
			    Break;
			case 'date' :
			    switch ($compare) {
				case 'eq' : $qs .= " AND " . $field . " = '" . date('Y-m-d', strtotime($value)) . "'";
				    Break;
				case 'lt' : $qs .= " AND " . $field . " < '" . date('Y-m-d', strtotime($value)) . "'";
				    Break;
				case 'gt' : $qs .= " AND " . $field . " > '" . date('Y-m-d', strtotime($value)) . "'";
				    Break;
			    }
			    Break;
		    }
		}
		$qWhere .= $qs;
	    }
	    $query = "SELECT D.NO_CONTAINER, D.POINT, B.CONT_SIZE, B.CONT_TYPE, B.ID_ISO_CODE, ROUND((B.WEIGHT/1000),1) AS WEIGHT, B.ID_POD, B.ID_VES_VOYAGE, B.ID_COMMODITY, COM.COMMODITY_NAME, B.ID_OPERATOR, B.ID_CLASS_CODE, B.ID_SPEC_HAND, B.IMDG, B.ID_OP_STATUS,JYM.EVENT,B.YD_SLOT,P.FOREGROUND_COLOR
				,VV.ID_VESSEL || ' ' || VV.VOY_IN || '/' || VV.VOY_OUT AS VES_VOYAGE,JYM.ID_MACHINE,COS.\"SEQUENCE\"
				FROM
				JOB_PLACEMENT D
				INNER JOIN CON_LISTCONT B
				ON B.NO_CONTAINER=D.NO_CONTAINER AND B.POINT=D.POINT
				LEFT JOIN JOB_YARD_MANAGER JYM 
				ON D.NO_CONTAINER = JYM.NO_CONTAINER AND D.ID_VES_VOYAGE = JYM.ID_VES_VOYAGE AND D.POINT = JYM.POINT
				LEFT JOIN M_PORT P
				ON B.ID_POD = P.PORT_CODE
				LEFT JOIN VES_VOYAGE VV
				ON B.ID_VES_VOYAGE = VV.ID_VES_VOYAGE
				LEFT JOIN M_CONT_COMMODITY COM
				ON B.ID_COMMODITY = COM.ID_COMMODITY
				LEFT JOIN CON_OUTBOUND_SEQUENCE COS
				ON B.NO_CONTAINER=COS.NO_CONTAINER AND B.POINT=COS.POINT
				$qWhere
				ORDER BY B.ID_VES_VOYAGE,B.ID_POD ";
	    $rs = $this->db->query($query);
//	    echo '<pre>'.$this->db->last_query().'</pre>';exit;
	    $operator_list = $rs->result_array();
	    /* for ($i=0; $i<sizeof($container_list); $i++){
	      $container_list[$i]['STOWAGE'] = '';
	      $container_list[$i]['YARD_POS'] = '';
	      $container_list[$i]['WEIGHT'] = ($container_list[$i]['WEIGHT']!='') ? $container_list[$i]['WEIGHT']/1000 : $container_list[$i]['WEIGHT'];
	      if ($container_list[$i]['VS_BAY']!=''){
	      $container_list[$i]['STOWAGE'] = str_pad($container_list[$i]['VS_BAY'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_ROW'],2,'0',STR_PAD_LEFT).str_pad($container_list[$i]['VS_TIER'],2,'0',STR_PAD_LEFT);
	      }
	      if ($container_list[$i]['YD_BLOCK_NAME']!=''){
	      $container_list[$i]['YARD_POS'] = $container_list[$i]['YD_BLOCK_NAME'].'-'.$container_list[$i]['YD_SLOT'].'-'.$container_list[$i]['YD_ROW'].'-'.$container_list[$i]['YD_TIER'];
	      }
	      } */

	    $query_count = "SELECT COUNT(*) AS TOTAL FROM JOB_PLACEMENT D
				INNER JOIN CON_LISTCONT B
				ON B.NO_CONTAINER=D.NO_CONTAINER AND B.POINT=D.POINT
				LEFT JOIN JOB_YARD_MANAGER JYM 
				ON D.NO_CONTAINER = JYM.NO_CONTAINER AND D.ID_VES_VOYAGE = JYM.ID_VES_VOYAGE AND D.POINT = JYM.POINT
				LEFT JOIN M_PORT P
				ON B.ID_POD = P.PORT_CODE
				LEFT JOIN VES_VOYAGE VV
				ON B.ID_VES_VOYAGE = VV.ID_VES_VOYAGE
				LEFT JOIN M_CONT_COMMODITY COM
				ON B.ID_COMMODITY = COM.ID_COMMODITY 
				LEFT JOIN CON_OUTBOUND_SEQUENCE COS
				ON B.NO_CONTAINER=COS.NO_CONTAINER AND B.POINT=COS.POINT
				$qWhere";
	    $rs = $this->db->query($query_count);
	    $row = $rs->row_array();
	    $total = $row['TOTAL'];

	    $data = array(
		'total' => $total,
		'data' => $operator_list
	    );
	    return $data;
	    
	}
	
	public function save_hold_container($data,$id_user){
	    $msg_complete = '';
	    $cont_list = '';
	    $id_ves_voyage = $_POST['ID_VES_VOYAGE'];
	    $container_data = json_decode($data['container_data']);

	    if ($msg_out==''){
		    for ($i=0;$i<sizeof($container_data);$i++){
			    $no_container = $container_data[$i]->NO_CONTAINER;
			    $point = $container_data[$i]->POINT;
			    $status_hold = $container_data[$i]->HOLD_CONTAINER;
			    // print_r($param);
			    if($status_hold=="Y"){
			    	$s = "N";
			    }else{
			    	$s = "Y";
			    }
			    $query = "UPDATE CON_LISTCONT SET HOLD_CONTAINER='$s' WHERE NO_CONTAINER = '$no_container' AND POINT = '$point' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
			    //echo $query;die;
			    $this->db->query($query);
			    $query = "SELECT * FROM CON_INBOUND_SEQUENCE WHERE NO_CONTAINER='$no_container' AND ID_VES_VOYAGE='$id_ves_voyage' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
			    $cek = $this->db->query($query)->result_array();
			    if (count($cek)>0){
				    $cont_list .= $cont_list != ''? ',' .$no_container : $no_container;
			    }
		    }
	    }

	    if($cont_list != ''){
		    $msg_complete = 'Container : '.$cont_list.' has been set sequence'; 
	    }
	    if($this->db->trans_complete()){
		    return array(
			    'success'=>true,
			    'errors'=> 'Save success. '.$msg_complete
		    );
	    }else{
		    return array(
			    'success'=>false,
			    'errors'=> 'Save failed'
		    );
	    }
	}
	
	public function get_data_hold_container_list($container_list=false, $paging=false, $sort=false, $filters=false){
		$q_in_con = '';
		if ($container_list){
			$q_in_con = " AND A.NO_CONTAINER IN (".$container_list.") ";
		}
		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM CON_LISTCONT A
						WHERE A.ACTIVE='Y' AND ID_TERMINAL='".$this->gtools->terminal()."' $q_in_con";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		if ($paging != false){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		}
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		//$qWhere = "WHERE 1=1 AND A.HOLD_CONTAINER='N' AND A.ACTIVE='Y' $q_in_con";
		$qWhere = "WHERE 1=1 AND A.ACTIVE='Y' $q_in_con";
		$qs = '';
		$encoded = true;
		if ($filters != false){
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];
				// assign filter data (location depends if encoded or not)
				if ($encoded) {
					$field = $filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}
				
				switch($field){
					case'NO_CONTAINER'	: $field = "A.".$field; break;
				}
				
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " AND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " AND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													A.ID_VES_VOYAGE,
													A.ID_ISO_CODE,
													A.ID_CLASS_CODE,
													A.ID_OPERATOR,
													A.CONT_STATUS,
													A.HOLD_CONTAINER,
													A.TL_FLAG,
													A.ID_OP_STATUS,
													A.OVER_HEIGHT, 
													A.OVER_RIGHT, 
													A.OVER_LEFT, 
													A.OVER_FRONT, 
													A.OVER_REAR,
													A.WEIGHT,
													A.SEAL_NUMB
										FROM 
											CON_LISTCONT A
										$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."'
										$qSort) V
							) B
						$qPaging";
		#print $query;die;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		for ($i=0; $i<sizeof($container_list); $i++){
			if ($container_list[$i]['ID_CLASS_CODE']=='E'){
				$container_list[$i]['EDIT_VESSEL'] = 1;
				$container_list[$i]['EDIT_TL'] = 0;
			}else if ($container_list[$i]['ID_CLASS_CODE']=='I' && $container_list[$i]['ID_OP_STATUS']=='BPL'){
				$container_list[$i]['EDIT_VESSEL'] = 0;
				$container_list[$i]['EDIT_TL'] = 1;
			}else{
				$container_list[$i]['EDIT_VESSEL'] = 0;
				$container_list[$i]['EDIT_TL'] = 0;
			}
		}
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function get_slot_category($category){
	    $qry = "SELECT D.*
			,Y.START_SLOT,Y.END_SLOT
		    FROM M_PLAN_CATEGORY_D D
		    LEFT JOIN YARD_PLAN_GROUP Y
		      ON D.ID_CATEGORY = Y.ID_CATEGORY
		    WHERE D.ID_CATEGORY = '$category'";
	    
	    return $this->db->query($qry)->row_array();
	}

	public function get_outbound_sequence_exchange($id_ves_voyage, $bay,$row,$tier){
		$query = "SELECT * FROM CON_OUTBOUND_SEQUENCE WHERE ID_VES_VOYAGE='$id_ves_voyage' AND BAY_ IN ($bay-1, $bay+1) AND ROW_ = '$row' and TIER_ ='$tier' ";
		return $this->db->query($query)->result_array();
	}
	
	public function exchange_stowage($id_ves_voyage,$from,$to){
	    $arrFrom = explode('-', $from);
	    $arrTo = explode('-', $to);
	    
	    //check if already start cwl for planning container
	    // if($arrFrom[9] == 'P'){
		// $qryCheckCWLfrom = "SELECT B.ACTIVE FROM MCH_WORKING_PLAN A
				// LEFT JOIN MCH_WORKING_SEQUENCE B ON A.ID_MCH_WORKING_PLAN = B.ID_MCH_WORKING_PLAN
				// WHERE A.ID_VES_VOYAGE = '$id_ves_voyage' AND B.ACTIVITY = 'E' AND B.BAY = ".$arrFrom[1]." AND B.DECK_HATCH = '".$arrFrom[4]."'";
		// $res1 = $this->db->query($qryCheckCWLfrom)->row_array();

		// if($res1['ACTIVE'] == 'Y'){
		    // return array('F','Machine at bay '.$arrFrom[1].' '.$arrFrom[4].' already active.');
		// }
	    // }
	    // if($arrTo[6] != 'undefined' && $arrTo[9] == 'P' || $arrTo[6] == 'undefined'){
		// $qryCheckCWLto = "SELECT B.ACTIVE FROM MCH_WORKING_PLAN A
				// LEFT JOIN MCH_WORKING_SEQUENCE B ON A.ID_MCH_WORKING_PLAN = B.ID_MCH_WORKING_PLAN
				// WHERE A.ID_VES_VOYAGE = '$id_ves_voyage' AND B.ACTIVITY = 'E' AND B.BAY = ".$arrTo[1]." AND B.DECK_HATCH = '".$arrTo[4]."'";
		// $res2 = $this->db->query($qryCheckCWLto)->row_array();

		// if($res2['ACTIVE'] == 'Y'){
		    // return array('F','Machine at bay '.$arrTo[1].' '.$arrTo[4].' already active');
		// }
	    // }
	    
	    $this->db->trans_start();
	    
	    $qryUpdate1 = "UPDATE CON_OUTBOUND_SEQUENCE SET ID_BAY = ".$arrTo[0].",BAY_ = ".$arrTo[1].",ROW_ = ".$arrTo[2].",TIER_ = ".$arrTo[3].",ID_CELL = ".$arrTo[5].",DECK_HATCH = '".$arrTo[4]."'
				WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND NO_CONTAINER = '".$arrFrom[6]."' AND POINT = ".$arrFrom[7]." AND ID_TERMINAL = '".$this->gtools->terminal()."'";
	    $this->db->query($qryUpdate1);
	    
	    if($arrTo[6] != 'undefined' && $arrTo[7] != 'undefined'){
		$qryUpdate2 = "UPDATE CON_OUTBOUND_SEQUENCE SET ID_BAY = ".$arrFrom[0].",BAY_ = ".$arrFrom[1].",ROW_ = ".$arrFrom[2].",TIER_ = ".$arrFrom[3].",ID_CELL = ".$arrFrom[5].",DECK_HATCH = '".$arrFrom[4]."'
				WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND NO_CONTAINER = '".$arrTo[6]."' AND POINT = ".$arrTo[7]." AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$this->db->query($qryUpdate2);
	    }
	    
	    if($arrFrom[9] == 'C'){
		$qryUpdate3 = "UPDATE CON_LISTCONT SET VS_BAY = ".$arrTo[1].",VS_ROW = ".$arrTo[2].",VS_TIER = ".$arrTo[3]."
				WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND NO_CONTAINER = '".$arrFrom[6]."' AND POINT = ".$arrFrom[7]." AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$this->db->query($qryUpdate3);
	    }
	    
	    if($arrTo[9] == 'C'){
		$qryUpdate4 = "UPDATE CON_LISTCONT SET VS_BAY = ".$arrFrom[1].",VS_ROW = ".$arrFrom[2].",VS_TIER = ".$arrFrom[3]."
				WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND NO_CONTAINER = '".$arrTo[6]."' AND POINT = ".$arrTo[7]." AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$this->db->query($qryUpdate4);
	    }
	    if ($this->db->trans_complete()){
		    if($this->set_outbound_sequence_per_bay_deck_hatch($id_ves_voyage,$arrTo[0],$arrTo[4]) && $this->set_outbound_sequence_per_bay_deck_hatch($id_ves_voyage,$arrFrom[0],$arrFrom[4])){
			return array('S','Save Success');
		    }else{
			return array('F','Save Failed. Please contact your EOS team.');
		    }
	    }else{
		    return array('F','Save Failed. Please contact your EOS team.');
	    }
//	    $(selected_el[index]).attr('no_container')+"-"+$(selected_el[index]).attr('point')+"-"+$(selected_el[index]).attr('sequence')+"-"+$(selected_el[index]).attr('cont_size')
	    
//	    $(selected_el[index]).attr('bay')+"-"+$(selected_el[index]).attr('row')+"-"+$(selected_el[index]).attr('tier')+"-"+$(selected_el[index]).attr('id_cell')+"-"+$(selected_el[index]).attr('sequence')
	}
	
	public function set_outbound_sequence_per_bay_deck_hatch($id_ves_voyage,$id_bay,$deck_hatch){
	    //get data from con_outbound_seq
	    $qry = "SELECT * FROM CON_OUTBOUND_SEQUENCE WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND ID_BAY = $id_bay AND DECK_HATCH = '$deck_hatch' AND ID_TERMINAL='".$this->gtools->terminal()."'
		    ORDER BY TIER_, ROW_";
	    $data = $this->db->query($qry)->result_array();
	    $seq = 1;
	    $this->db->trans_start();
	    foreach($data as $row){
		$qryUpdate = "UPDATE CON_OUTBOUND_SEQUENCE SET SEQUENCE = $seq WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND ID_BAY = $id_bay AND DECK_HATCH = '$deck_hatch' AND ID_CELL = ".$row['ID_CELL']." AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$this->db->query($qryUpdate);
		$seq++;
	    }
	    
	    if ($this->db->trans_complete()){
		    return true;
	    }else{
		    return false;
	    }
	}

	public function byPass($listContainer){
		foreach($listContainer as $row){
			$query = "UPDATE JOB_YARD_MANAGER SET IS_BYPASS='$row->IS_BYPASS' WHERE NO_CONTAINER='$row->NO_CONTAINER' AND POINT='$row->POINT'";
			$check = $this->db->query($query);
		}
		return $check;
	}
}
?>