<?php
class Container extends CI_Model {
	public function __construct(){
		$this->load->database();
	}
	
	public function get_category_list(){
		$query 		= "SELECT A.ID_CATEGORY, A.CATEGORY_NAME FROM M_PLAN_CATEGORY_H A
		WHERE A.STATUS=1 ORDER BY A.CATEGORY_NAME";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function data_paweight(){
		$query 		= "SELECT ID_PAWEIGHT, NAME_PAWEIGHT FROM M_PAWEIGHT_H ORDER BY ID_PAWEIGHT";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function getCMSEIRinfo($cont,$point){
		$query="select a.CONT_SIZE||' / '||a.CONT_TYPE AS SIZETYPE,
					 a.ID_POL, a.ID_OPERATOR,
					 a.IMDG, a.TEMP,
					 d.CUSTOMER_NAME, 
					 e.NO_POL,
					 a.SEAL_NUMB,
					 a.GT_JS_BLOCK_NAME||' - '||A.GT_JS_SLOT as ALOKASI,
					 a.YD_BLOCK_NAME||' - '||a.YD_SLOT||' - '||A.YD_ROW||' - '||A.YD_TIER AS LOKASI,
					 TO_CHAR(A.GT_DATE,'DD-MM-YYYY HH24:MI AM') as GATEIN,
					 TO_CHAR(a.GT_DATE_OUT,'DD-MM-YYYY HH24:MI AM') AS GATEOUT,
					 'OPERATOR' AS INSPECTIONOPERATOR,
					 B.DAMAGE,
					 C.DAMAGE_LOCATION
					 
				 from con_listcont a
				left join job_gate_manager d on a.no_container=d.no_container and a.point=d.point and a.no_request=d.no_request
				left join m_truck e on A.ID_TRUCK=E.ID_TRUCK 
				left join m_damage b on a.id_damage=b.id_damage
				left join m_damage_location c on a.id_damage_location=c.id_damage_location
				where a.no_container='$cont' and a.point='$point'";
		$rs 		= $this->db->query($query);
		$row 		= $rs->row_array();
		
		return $row;
	}
	
	public function get_datapaWeightD($id_paweight){
		$param = array($id_paweight);
		$query 		= "SELECT ID_PAWEIGHT, DNAME_PAWEIGHT, SIZE_PAWEIGHT, MAX_ESTPAWEIGHT, MIN_ESTPAWEIGHT, SIZE_PAWEIGHT||'-'||DNAME_PAWEIGHT CATEGNAME_PAWEIGHT FROM M_PAWEIGHT_D WHERE ID_PAWEIGHT=? ORDER BY SIZE_PAWEIGHT, DNAME_PAWEIGHT";
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
                        VES_VOYAGE b ON a.ID_VES_VOYAGE = b.ID_VES_VOYAGE 
                    WHERE
                        a.EI = '$ei'
                        AND a.STATUS_FLAG != 'C'
						AND LOWER(a.NO_CONTAINER) LIKE '%".strtolower($filter)."%'
                        AND (b.FL_TONGKANG <> 'Y' OR b.FL_TONGKANG IS NULL)
					UNION ALL
					SELECT
						a.NO_CONTAINER as NO_CONTAINERX, 
						a.ID_VES_VOYAGE, 
						a.NO_CONTAINER||'-'||a.POINT AS CONT_INFO
					FROM
						JOB_GATE_MANAGER a
					LEFT JOIN
						VES_VOYAGE b ON a.ID_VES_VOYAGE = b.ID_VES_VOYAGE
					WHERE
						'$ei' = 'E'
						--AND a.STATUS_FLAG != 'C'
						AND LOWER(a.NO_CONTAINER) LIKE '%".strtolower($filter)."%'
						AND b.FL_TONGKANG = 'Y'
					";
					
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function insert_masterweight($a,$b){
		$param = array(
				array('name'=>':cat_name', 'value'=>$a, 'length'=>30),
				array('name'=>':param', 'value'=>$b, 'length'=>100)
			);
		// print_r($param);
		$this->db->trans_start();
		$query = "begin prc_insmasteweight(:cat_name, :param); end;";
		$this->db->exec_bind_stored_procedure($query, $param);
		
		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
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
		 
		 $query 		= "SELECT A.VESSEL_NAME, VOY_IN||'-'||VOY_OUT AS VOYG, OPERATOR_NAME, 
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
		 FROM VES_VOYAGE A WHERE ID_VES_VOYAGE='$id_ves_voyage'";
 
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
							  CALL_SIGN,
							  OPERATOR
					   FROM VES_VOYAGE 
					   WHERE TRIM(ID_VES_VOYAGE) = UPPER('$id_ves_voyage')";
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
					   WHERE CLASS_CODE = 'E'
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
		$query = "SELECT DECODE(MAX(ID_CATEGORY),998,999,MAX(ID_CATEGORY)) AS MAX_ID FROM M_PLAN_CATEGORY_H";
		$rs 		= $this->db->query($query);
		$row 		= $rs->row_array();
		$id = 1;
		if ($row['MAX_ID']){
			$id = $row['MAX_ID']+1;
		}
		
		$this->db->trans_start();
		
		$param = array($id, $category_name);
		$query 	= "INSERT INTO M_PLAN_CATEGORY_H
					(ID_CATEGORY, CATEGORY_NAME) VALUES(?, ?)";
		$rs 	= $this->db->query($query, $param);
		
		
		
		for($i=0;$i<sizeof($category_detail);$i++){
			$detail = $category_detail[$i];
			$q_fields = "";
			$q_values = "";
			
			//===Edit by mustadio_gun
			//===06/07/2017
			//===purpose : add validation 
			
			
			
			foreach($detail as $key=>$value){
				
				// print_r($value);
				// print_r('<br/>');
				
				
				$array_detail[$key] = $value;
				
				
				// print_r($array_detail['CONT_SIZE']);die;
				
				// if($array_detail['CONT_SIZE'] == '' || 
					// $array_detail['CONT_SIZE'] == '-' ||
					// $array_detail['CONT_TYPE'] == '' ||
					// $array_detail['CONT_TYPE'] == '-' ||
					// $array_detail['CONT_STATUS'] == '' ||
					// $array_detail['CONT_STATUS'] == '-' ||
					// $array_detail['ID_PORT_DISCHARGE'] == '' ||
					// $array_detail['ID_PORT_DISCHARGE'] == '-' ||
					// $array_detail['ID_VES_VOYAGE'] == '' ||
					// $array_detail['ID_VES_VOYAGE'] == '-' ||
					// $array_detail['ID_OPERATOR'] == '' ||
					// $array_detail['ID_OPERATOR'] == '-' ||
					// $array_detail['CONT_HEIGHT'] == '' ||
					// $array_detail['CONT_HEIGHT'] == '-' ||
					// $array_detail['E_I'] == '' ||
					// $array_detail['E_I'] == '-' ||
					// $array_detail['O_I'] == '' ||
					// $array_detail['O_I'] == '-' 
					// )
				
				
				//===End of edit by mustadio_gun
				
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
				}else if ($key=='ID_PORT_DISCHARGE'){
					$q_values .= "'".substr($value,0,5)."'";
				}else{
					$q_values .= "'".$value."'";
				}
			}
			// print_r($array_detail);
			// die;
			
			// if($array_detail['CONT_SIZE'] == '' || 
					// $array_detail['CONT_SIZE'] == '-' || 
					// $array_detail['CONT_SIZE'] == NULL 
					// )
					if($array_detail['CONT_SIZE'] == '' || 
					$array_detail['CONT_SIZE'] == '-' ||
					$array_detail['CONT_TYPE'] == '' ||
					$array_detail['CONT_TYPE'] == '-' ||
					$array_detail['CONT_STATUS'] == '' ||
					$array_detail['CONT_STATUS'] == '-' ||
					$array_detail['ID_PORT_DISCHARGE'] == '' ||
					$array_detail['ID_PORT_DISCHARGE'] == '-' ||
					$array_detail['ID_VES_VOYAGE'] == '' ||
					$array_detail['ID_VES_VOYAGE'] == '-' ||
					$array_detail['ID_OPERATOR'] == '' ||
					$array_detail['ID_OPERATOR'] == '-' ||
					// $array_detail['CONT_HEIGHT'] == '' ||
					// $array_detail['CONT_HEIGHT'] == '-' ||
					$array_detail['E_I'] == '' ||
					$array_detail['E_I'] == '-' ||
					$array_detail['O_I'] == '' ||
					$array_detail['O_I'] == '-' 
					)
				{
					exit("0");
				}
			
			$query 	= "INSERT INTO M_PLAN_CATEGORY_D ($q_fields) VALUES($q_values)";
			$rs 	= $this->db->query($query);
		}
		
		// print_r($array_detail['CONT_SIZE']);
		
		strlen("Hello");
		
		if ($this->db->trans_complete()){
			 return $id;
			// return 1;
			
		}else{
			return 0;
		}
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
					WHERE ID_CATEGORY = '$category_id'";
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
	
	public function get_port_list($filter){
		$query 		= "SELECT PORT_CODE, PORT_CODE||'-'||PORT_NAME PORT_NAME FROM M_PORT
		WHERE LOWER(PORT_NAME) LIKE '%".strtolower($filter)."%'
			OR LOWER(PORT_CODE) LIKE '%".strtolower($filter)."%'
		ORDER BY PORT_CODE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_operator_list($filter){
		$query 		= "SELECT ID_OPERATOR, ID_OPERATOR||' - '||OPERATOR_NAME OPERATOR_NAME FROM M_OPERATOR
		WHERE LOWER(OPERATOR_NAME) LIKE '%".strtolower($filter)."%'
			OR LOWER(ID_OPERATOR) LIKE '%".strtolower($filter)."%'
		ORDER BY OPERATOR_NAME";
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
		WHERE UNNO LIKE '".$filter."%'
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
		
		$container_list = $this->db->query("SELECT * FROM CON_LISTCONT
						WHERE ID_VES_VOYAGE ='".$id_ves_voyage."'
						AND TRIM (ID_OP_STATUS) <> 'DIS'
						AND ID_CLASS_CODE IN ('I', 'TI')")->result_array();

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

		return $container_list;
		
	}
	
	public function get_data_inbound_outbound_list($id_ves_voyage='', $class_code, $paging=false, $sort=false, $filters=false){
		$class_code_str = '';
		if ($class_code=='I'){
			$class_code_str = "'I', 'TI','TC'";
		}else if ($class_code=='E'){
			$class_code_str = "'E', 'TE'";
		}
		$param = array($id_ves_voyage);
		$query_count = "SELECT COUNT(NO_CONTAINER) TOTAL
						FROM CON_LISTCONT
						WHERE ID_VES_VOYAGE=? AND TRIM(ID_OP_STATUS) <> 'DIS' AND ID_CLASS_CODE IN ($class_code_str)";
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
		$qWhere = "WHERE ID_VES_VOYAGE=? AND TRIM(ID_OP_STATUS) <> 'DIS' AND ID_CLASS_CODE IN ($class_code_str) ";
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
								(SELECT * FROM CON_LISTCONT $qWhere $qSort) A) B
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
		
		//print_r($data);
		//die();
		
		return $data;
	}

	public function get_data_weighing_list($id_ves_voyage='', $class_code, $paging=false, $sort=false, $filters=false){
		$torSV = "SELECT FL_TONGKANG FROM VES_VOYAGE WHERE ID_VES_VOYAGE = '$id_ves_voyage'";
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

		$param = array($id_ves_voyage);
		// update WEIGHT = 0 for call just container before weighing
		$query_count = "SELECT COUNT(NO_CONTAINER) TOTAL
						FROM CON_LISTCONT
						WHERE 
							WEIGHT = 0 AND
							ID_VES_VOYAGE=? AND 
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
								(SELECT * FROM CON_LISTCONT $qWhere $qSort) A) B
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
			$deck_hatch
		);
		
		$query = "SELECT MAX(SEQUENCE) DELTA FROM CON_INBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ?";
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
				$deck_hatch
			);
			$query_plan = "INSERT INTO CON_INBOUND_SEQUENCE (
							   NO_CONTAINER, POINT, ID_VES_VOYAGE, 
							   BAY_, ROW_, TIER_, 
							   ID_BAY, ID_CELL, SEQUENCE, DECK_HATCH) 
							VALUES ( ?/* NO_CONTAINER */,
							 ?/* POINT */,
							 ?/* ID_VES_VOYAGE */,
							 ?/* BAY_ */,
							 ?/* ROW_ */,
							 ?/* TIER_ */,
							 ?/* ID_BAY */,
							 ?/* ID_CELL */,
							 ?/* SEQUENCE */,
							 ?/* DECK_HATCH */ )";
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
			'P'
		);
		
		$query = "SELECT * FROM CON_INBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ? AND
						STATUS = ?
					ORDER BY SEQUENCE";
		$rs = $this->db->query($query, $param);
		$data = $rs->result_array();
		
		$query = "DELETE FROM CON_INBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ? AND
						STATUS = ?";
		$this->db->query($query, $param);
		
		$query = "SELECT MAX(SEQUENCE) DELTA FROM CON_INBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ?";
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
					$row['DECK_HATCH']
				);
				$query_plan = "INSERT INTO CON_INBOUND_SEQUENCE (
								   NO_CONTAINER, POINT, ID_VES_VOYAGE, 
								   BAY_, ROW_, TIER_, 
								   ID_BAY, ID_CELL, SEQUENCE, DECK_HATCH) 
								VALUES ( ?/* NO_CONTAINER */,
								 ?/* POINT */,
								 ?/* ID_VES_VOYAGE */,
								 ?/* BAY_ */,
								 ?/* ROW_ */,
								 ?/* TIER_ */,
								 ?/* ID_BAY */,
								 ?/* ID_CELL */,
								 ?/* SEQUENCE */,
								 ?/* DECK_HATCH */ )";
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
		
		foreach($stack_arr as $row){
			$detail = explode("-",$row);
			$stack_info_arr[$detail[2]] = array($detail[0],$detail[1],$detail[3]);
		}
		
		$flag = 1;
		$this->db->trans_start();
		
		$param = array(
			$id_ves_voyage,
			$id_bay,
			$deck_hatch
		);
		
		$query = "SELECT MAX(SEQUENCE) DELTA FROM CON_OUTBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ?";
		$rs = $this->db->query($query, $param);
		$data = $rs->row_array();
		$delta = 0;
		if ($data['DELTA']!=''){
			$delta = $data['DELTA'];
		}
		
		foreach ($sequence_arr as $row){
			$detail = explode("-",$row);
			$stack_info = $stack_info_arr[$detail[4]];
			$param = array(
				$stack_info[0],
				$stack_info[1],
				$id_ves_voyage,
				$detail[0],
				$detail[1],
				$detail[2],
				$id_bay,
				$detail[3],
				$detail[4]+$delta,
				$deck_hatch
			);
			$query_plan = "INSERT INTO CON_OUTBOUND_SEQUENCE (
							   NO_CONTAINER, POINT, ID_VES_VOYAGE, 
							   BAY_, ROW_, TIER_, 
							   ID_BAY, ID_CELL, SEQUENCE, DECK_HATCH) 
							VALUES ( ?/* NO_CONTAINER */,
							 ?/* POINT */,
							 ?/* ID_VES_VOYAGE */,
							 ?/* BAY_ */,
							 ?/* ROW_ */,
							 ?/* TIER_ */,
							 ?/* ID_BAY */,
							 ?/* ID_CELL */,
							 ?/* SEQUENCE */,
							 ?/* DECK_HATCH */ )";
			$flag = ($flag && $this->db->query($query_plan, $param));
		}
		
		$this->db->trans_complete();
		return $flag;
	}
	
	public function delete_container_working_sequence_outbound($id_ves_voyage, $id_bay, $deck_hatch, $xml_str){
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
			'P'
		);
		
		$query = "SELECT * FROM CON_OUTBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ? AND
						STATUS = ?
					ORDER BY SEQUENCE";
		$rs = $this->db->query($query, $param);
		$data = $rs->result_array();
		
		$query = "DELETE FROM CON_OUTBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ? AND
						STATUS = ?";
		$this->db->query($query, $param);
		
		$query = "SELECT MAX(SEQUENCE) DELTA FROM CON_OUTBOUND_SEQUENCE 
					WHERE 
						ID_VES_VOYAGE = ? AND
						ID_BAY = ? AND
						DECK_HATCH = ?";
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
					$row['DECK_HATCH']
				);
				$query_plan = "INSERT INTO CON_OUTBOUND_SEQUENCE (
								   NO_CONTAINER, POINT, ID_VES_VOYAGE, 
								   BAY_, ROW_, TIER_, 
								   ID_BAY, ID_CELL, SEQUENCE, DECK_HATCH) 
								VALUES ( ?/* NO_CONTAINER */,
								 ?/* POINT */,
								 ?/* ID_VES_VOYAGE */,
								 ?/* BAY_ */,
								 ?/* ROW_ */,
								 ?/* TIER_ */,
								 ?/* ID_BAY */,
								 ?/* ID_CELL */,
								 ?/* SEQUENCE */,
								 ?/* DECK_HATCH */ )";
				$flag = ($flag && $this->db->query($query_plan, $param));
				$sequence += 1;
			}
		}
		
		$this->db->trans_complete();
		return $flag;
	}
	
	public function get_max_container_point($no_container){
		$param = array($no_container);
		$query 	= "SELECT MAX(POINT) POINT
					FROM CON_LISTCONT
					WHERE NO_CONTAINER = ?";
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
		$query 		= "SELECT FL_TONGKANG FROM VES_VOYAGE WHERE ID_VES_VOYAGE = '$id_ves_voyage'";
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
				$this->check_valid_field($data['CONT_STATUS']) && 
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
				$this->check_valid_field($data['CONT_STATUS']) && 
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
		if (!$this->check_container_number($data['NO_CONTAINER'], $id_ves_voyage)){
			return 0;
		}
		if (!$this->check_valid_container_detail($data, $id_ves_voyage)){
			return 0;
		}
		if (!($this->check_valid_container_detail_iso($data['ID_ISO_CODE'], $data['CONT_SIZE'], $data['CONT_TYPE']))) {
			return 0;
		}
		
		$q_field = "";
		$q_value = "";
		if ($data['UNNO']!='' || $data['IMDG']!=''){
			$data['HAZARD'] = 'Y';
		}else if ($data['UNNO']=='' || $data['IMDG']==''){
			$data['HAZARD'] = 'N';
		}
		$data['POINT'] = $this->get_max_container_point($data['NO_CONTAINER']);
		$data['ID_VES_VOYAGE'] = $id_ves_voyage;
		foreach($data as $key=>$value){
			if ($q_field!=""){
				$q_field .= ",";
			}
			if ($q_value!=""){
				$q_value .= ",";
			}
			
			if ($key=='STOWAGE'){
				$q_field .= "VS_BAY,VS_ROW,VS_TIER";
				if ($value!=''){
					$q_value .= "'" . (int) substr($value,0,strlen($value)-4) . "',"; // vs bay
					$q_value .= "'" . (int) substr($value,-4,2) . "',"; // vs row
					$q_value .= "'" . (int) substr($value,-2,2) . "'"; // vs tier
					
					/* check available stowage location */
					$vs_bay = (int) substr($value,0,strlen($value)-4);
					$vs_row = (int) substr($value,-4,2);
					$vs_tier = (int) substr($value,-2,2);
					
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
							AND C.STATUS_STACK = 'A'
							";
							
							$rs_cekslot = $this->db->query($query_cekslot);
							$row_cekslot = $rs_cekslot->row_array();
					
					if($row_cekslot['JUMLAH']== 0){
								return 2;
							}
					/* check available stowage location */
				}else{
					$q_value .= "'','',''";
				}
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
					($q_field,ID_OP_STATUS,OP_STATUS_DESC,ID_USER_BAPLIE,FL_TONGKANG)
					VALUES 
					($q_value,'$bp','$bpn','$id_user','$FL_TONGKANG')";
		$rs 	= $this->db->query($query);
		
		if ($rs){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function update_listcont_detail($no_container, $point, $data, $id_ves_voyage=''){
		if ($id_ves_voyage!=''){
			if (!$this->check_container_number($data['NO_CONTAINER'], $id_ves_voyage)){
				return 0;
			}
		}
		if (!$this->check_valid_container_detail_change($data)){
			return 0;
		}
		
		if ($data['NO_CONTAINER']!=''){
			$data['POINT'] = $this->get_max_container_point($data['NO_CONTAINER']);
		}
		if ($data['UNNO']!='' || $data['IMDG']!=''){
			$data['HAZARD'] = 'Y';
		}else if ($data['UNNO']=='' || $data['IMDG']==''){
			$data['HAZARD'] = 'N';
		}
		
		$q_set = "";
		foreach($data as $key=>$value){
			if ($q_set!=""){
				$q_set .= ",";
			}
			
			if ($key=='STOWAGE'){
				if ($value!='' && $value!='000000'){
					$q_set .= "VS_BAY = '".(int) substr($value,0,strlen($value)-4)."',";
					$q_set .= "VS_ROW = '".(int) substr($value,-4,2)."',";
					$q_set .= "VS_TIER = '".(int) substr($value,-2,2)."'";
				}else{
					$q_set .= "VS_BAY='',VS_ROW='',VS_TIER=''";
				}
			}else {
				if ($key=='WEIGHT'){
					$value = $value*1000;
				}
				$q_set .= $key." = '".$value."'";
			}
		}
		
		$param = array($no_container, $point);
		$query 	= "UPDATE CON_LISTCONT
					SET $q_set
					WHERE NO_CONTAINER = ? AND POINT = ?";
		$rs 	= $this->db->query($query, $param);
		
		if ($rs){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function delete_listcont_detail($no_container, $point){
		$param = array($no_container, $point);
		$query 	= "SELECT ID_OP_STATUS FROM CON_LISTCONT
					WHERE NO_CONTAINER = ? AND POINT = ?";
		$rs 	= $this->db->query($query, $param);
		$row 	= $rs->row_array();
		
		$flag = false;
		if ($row['ID_OP_STATUS']=='BPL'){
			$query 	= "DELETE FROM CON_LISTCONT
						WHERE NO_CONTAINER = ? AND POINT = ?";
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
				
				$param = array($id_ves_voyage);
				if ($modus == 'overwrite') {
					$query = "DELETE FROM CON_LISTCONT
							WHERE ID_VES_VOYAGE = ? AND ((ID_CLASS_CODE IN ('I', 'TI') AND ID_OP_STATUS='BPL') OR ID_CLASS_CODE = 'TC')";
					$this->db->query($query, $param);
				}
				
				$i = 0;
				$j = 0;
				$jml_error = 0;
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					try {
						$param = array();
						$tc_nonactive = false;
						
						//print_r($i);die;
						if($i>0) {
							$query = "SELECT COUNT(*) JUMLAH FROM CON_LISTCONT WHERE NO_CONTAINER='".str_replace(' ', '',$data[5])."' AND ACTIVE='Y'";
							// print_r($query);
							$rs = $this->db->query($query);
							$row = $rs->row_array();
							if(($row['JUMLAH']+0)>0){
								$jml_error = $jml_error + 1;
								$error = 'There is container active state. <br/> ';
								throw new Exception($error);
								
							}
							
							// print_r('$jml_error 1='.$jml_error);
							
							// print_r($data);
							$param[] = str_replace(' ', '',$data[5]); // no container
							$param[] = $this->get_max_container_point($param[0]); // point
							$param[] = $id_ves_voyage; // id ves voyage
							$param[] = $data[27]; // unno
							$param[] = $data[13]; // imdg
							$param[] = $data[8]; // iso code							
							// id class code
							if ($data[0]==$this->config->item('SITE_PORT_CODE')){
								if ($data[0]!=$data[15] && $data[15]!='')
								{
									// $param[] = 'TI'; 
									// transhipment by request via planner
									$param[] = 'I';
								}else{
									$param[] = 'I';
								}
								$param[] = 'BPL'; // id op status
								$param[] = 'Booking Inbound'; // op status desc
							}else{
								$tc_nonactive = true;
								$param[] = 'TC';
								$param[] = '';  // id op status, tanya kenapa harus kosong?
								$param[] = ''; // op status desc
							}
							$param[] = $data[6]; // cont size
							// cont type
							$query = "SELECT TYPE_,SIZE_ FROM M_ISO_CODE WHERE ISO_CODE='".$data[8]."'";
							$rs = $this->db->query($query);
							$row = $rs->row_array();
							$param[] = $row['TYPE_'];
							$sizecon = $row['SIZE_'];
								$temp_type = $row['TYPE_'];
							if ((substr(strtoupper(trim($data[10])),0,1)=='M') || (substr(strtoupper(trim($data[10])),0,1)=='E')){
								$param[] = 'MTY';
									$temp_status = 'MTY';
							} else if(substr(strtoupper(trim($data[10])),0,1)=='F'){
								$param[] = 'FCL';
									$temp_status = 'FCL';
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
							
							//=== 1.Cek slot ada atau tidak
							
							$vs_bay_cek = $vs_bay;
							$vs_row_cek = $vs_row;
							$vs_tier_cek = $vs_tier;
							
							if($vs_bay[0] == 0 || $vs_bay[0] == '0')
							{
								$vs_bay_cek = substr($vs_bay,1);
							}
							
							if($vs_row[0] == 0 || $vs_row[0] == '0')
							{
								$vs_row_cek = substr($vs_row,1);
							}
							
							if($vs_tier[0] == 0 || $vs_tier[0] == '0')
							{
								$vs_tier_cek = substr($vs_tier,1);
							}
							
							$query_cekslot = "SELECT COUNT(1) JUMLAH
							FROM VES_VOYAGE A
							INNER JOIN M_VESSEL_PROFILE_BAY B 
							ON A.ID_VESSEL = B.ID_VESSEL
							INNER JOIN M_VESSEL_PROFILE_CELL C 
							ON B.ID_BAY = C.ID_BAY
							WHERE A.ID_VES_VOYAGE ='".$id_ves_voyage."'
							AND B.BAY='".$vs_bay_cek."'
							AND C.ROW_='".$vs_row_cek."'
							AND C.TIER_='".$vs_tier_cek."'
							AND C.STATUS_STACK = 'A'
							";
							
							$rs_cekslot = $this->db->query($query_cekslot);
							$row_cekslot = $rs_cekslot->row_array();
							// $avail_bay = $row_cekslot['ROW_'];
							// $avail_row = $row_cekslot['TIER_'];
							// $avail_tier = $row_cekslot['TIER_'];
							
							if(($row_cekslot['JUMLAH']+0)== 0){
								$jml_error = $jml_error + 1;
								$error = 'Slot not available. <br/> ';
								throw new Exception($error);
							}
							
							if(is_numeric($data[7])){
								if ((intval($data[7])) <= 0) {
									$jml_error = $jml_error + 1;
									$error = 'Berat harus lebih besar dari 0. <br/> ';
									throw new Exception($error);
								}
							} else {
								$jml_error = $jml_error + 1;
								$error = 'Berat Container harus merupakan angka. <br/> ';
								throw new Exception($error);
							}
							
							//=== 2.Cek slot sudah dipakai belum
							if(($sizecon=='40')||($sizecon=='45'))
							{
								$query_slot = "SELECT COUNT(*) JUMLAH_SLOT FROM CON_LISTCONT 
								WHERE ID_VES_VOYAGE='".$id_ves_voyage."'
								AND VS_BAY IN ('".($vs_bay-1)."','".($vs_bay+1)."')
								AND VS_ROW='".$vs_row."'
								AND VS_TIER='".$vs_tier."'
								";								
							}
							else
							{
								$query_slot = "SELECT COUNT(*) JUMLAH_SLOT FROM CON_LISTCONT 
								WHERE ID_VES_VOYAGE='".$id_ves_voyage."'
								AND VS_BAY='".$vs_bay."'
								AND VS_ROW='".$vs_row."'
								AND VS_TIER='".$vs_tier."'
								";
							}
							
							$rs_slot = $this->db->query($query_slot);
							$row_slot = $rs_slot->row_array();
							if(($row_slot['JUMLAH_SLOT']+0)>0){
								$jml_error = $jml_error + 1;
								$error = 'Slot already taken. <br/> ';
								throw new Exception($error);
								
							}
							
							if (!($this->check_valid_container_detail_iso($data[8], $data[6], $temp_type))) {// isocode, cont_size, cont_type
								$jml_error = $jml_error + 1;
								$error = 'Iso Code Tidak Sesuai dengan Ukuran. <br/> ';
								throw new Exception($error);
							}
							
							//End of edit by mustadio_gun
							
							// print_r('$jml_error 2='.$jml_error);
							
							$param[] = str_replace("'", ".",$data[11]); // cont height
							$param[] = (int) $data[3]; // vs bay
							$param[] = (int) substr($data[4],-4,2); // vs row
							$param[] = (int) substr($data[4],-2,2); // vs tier
							$param[] = $data[0]; // id pod
							$param[] = $data[1]; // id pol
							$param[] = $data[15]; // id por
							$param[] = $data[12]; // id operator
							$param[] = $data[7]; // weight
							$param[] = ($data[25]!='') ? 'Y' : 'N'; // hazard
								$temp_hz = ($data[25]!='') ? 'Y' : 'N';
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
							$param[] = $temp_commodity;
							$param[] = $id_user;
							$param[] = $data[14]; // temp setting
							// print_r($param);

							$FL_TONGKANG = $this->getFlTongkang($id_ves_voyage);

							$param[] = $FL_TONGKANG;
							
							$query = "INSERT INTO CON_LISTCONT (
									   NO_CONTAINER, POINT, ID_VES_VOYAGE, 
									   UNNO, IMDG, ID_ISO_CODE, ID_CLASS_CODE, 
									   ID_OP_STATUS, OP_STATUS_DESC,
									   CONT_SIZE, CONT_TYPE, CONT_STATUS, 
									   CONT_HEIGHT, VS_BAY, VS_ROW, 
									   VS_TIER, ID_POD, ID_POL, 
									   ID_POR, ID_OPERATOR, WEIGHT,
									   HAZARD, ID_COMMODITY, ID_USER_BAPLIE, TEMP, FL_TONGKANG) 
									VALUES ( ?/* NO_CONTAINER */,
									 ?/* POINT */,
									 ?/* ID_VES_VOYAGE */,
									 ?/* UNNO */,
									 ?/* IMDG */,
									 ?/* ID_ISO_CODE */,
									 ?/* ID_CLASS_CODE */,
									 ?/* ID_OP_STATUS */,
									 ?/* OP_STATUS_DESC */,
									 ?/* CONT_SIZE */,
									 ?/* CONT_TYPE */,
									 ?/* CONT_STATUS */,
									 ?/* CONT_HEIGHT */,
									 ?/* VS_BAY */,
									 ?/* VS_ROW */,
									 ?/* VS_TIER */,
									 ?/* ID_POD */,
									 ?/* ID_POL */,
									 ?/* ID_POR */,
									 ?/* ID_OPERATOR */,
									 ?/* WEIGHT */,
									 ?/* HAZARD */, 
									 ?/* ID_COMMODITY */,
									 ?/* ID_USER_BAPLIE */,
									 ?/* TEMP */,
									 ?/* FL TONGKANG */)";
								$this->db->query($query, $param);
							if ($tc_nonactive){
								$query = "UPDATE CON_LISTCONT
											SET ACTIVE='N'
										WHERE NO_CONTAINER=? AND POINT=?";
								$this->db->query($query, array($param[0], $param[1]));
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
						$msg .= "<br/>No Container: ".str_replace(' ', '',$data[5])." Error ".$e->getMessage().", ";
						$flag = FALSE;
					}
					$i++;
				}
				fclose($handle);
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
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
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
													E.YD_BLOCK_NAME,
													E.YD_SLOT,
													E.YD_ROW,
													E.YD_TIER,
													A.STATUS_FLAG
										FROM 
											(
											SELECT NO_CONTAINER, POINT, ID_VES_VOYAGE, SEQUENCE, STATUS_FLAG, ID_MCH_WORKING_PLAN, SEQ_MCH_WORKING_PLAN, ID_MACHINE, ID_MACHINE_ITV FROM JOB_QUAY_MANAGER
											WHERE STATUS_FLAG='P'
											--UNION
											--SELECT NO_CONTAINER, POINT, ID_VES_VOYAGE, SEQUENCE, STATUS_FLAG, --ID_MCH_WORKING_PLAN, SEQ_MCH_WORKING_PLAN, ID_MACHINE, --ID_MACHINE_ITV FROM JOB_YARD_MANAGER
											--WHERE STATUS_FLAG='P' AND ID_OP_STATUS='OYS' AND EVENT='O'
											) A
											INNER JOIN MCH_WORKING_SEQUENCE B ON A.ID_MCH_WORKING_PLAN=B.ID_MCH_WORKING_PLAN AND A.SEQ_MCH_WORKING_PLAN=B.SEQUENCE
											INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
											INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT
											INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
											LEFT JOIN M_MACHINE D ON A.ID_MACHINE_ITV=D.ID_MACHINE
										$qWhere
										$qSort) V
							) B
						$qPaging";
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
						WHERE STATUS_FLAG='P'
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
				$sortProperty = 'ID_VES_VOYAGE,QC,SEQ_NO';
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
					 case 'min' : $qWheremin .= "WHERE MINUTES <= ".$value; $qWhere = "WHERE 1=1 ";break;
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
													E.YD_BLOCK_NAME,
													E.YD_SLOT,
													E.YD_ROW,
													E.YD_TIER,
													A.STATUS_FLAG,
													TO_CHAR(A.COMPLETE_DATE, 'DD-MM-YYYY hh24:mi:ss') as MIN,
													(SYSDATE - A.COMPLETE_DATE)* 24 * 60 as MINUTES
										FROM 
											(
											SELECT NO_CONTAINER, POINT, ID_VES_VOYAGE, SEQUENCE, STATUS_FLAG, ID_MCH_WORKING_PLAN, SEQ_MCH_WORKING_PLAN, ID_MACHINE, ID_MACHINE_ITV, COMPLETE_DATE FROM JOB_QUAY_MANAGER
											-- WHERE STATUS_FLAG='P'
											-- UNION
											-- SELECT NO_CONTAINER, POINT, ID_VES_VOYAGE, SEQUENCE, STATUS_FLAG, --ID_MCH_WORKING_PLAN, SEQ_MCH_WORKING_PLAN, ID_MACHINE, --ID_MACHINE_ITV FROM JOB_YARD_MANAGER
											-- WHERE STATUS_FLAG='P' AND ID_OP_STATUS='OYS' AND EVENT='O'
											) A
											INNER JOIN MCH_WORKING_SEQUENCE B ON A.ID_MCH_WORKING_PLAN=B.ID_MCH_WORKING_PLAN AND A.SEQ_MCH_WORKING_PLAN=B.SEQUENCE
											INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
											INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT
											INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
											LEFT JOIN M_MACHINE D ON A.ID_MACHINE_ITV=D.ID_MACHINE
										$qWhere
										$qSort) V $qWheremin
							) B
						$qPaging";
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
					case'ID_VES_VOYAGE'	: $field = "F.VESSEL_NAME"; break;
					case'EQ'	: $field = "C.MCH_NAME"; break;
					case'ITV'	: $field = "D.MCH_NAME"; break;
					case'ID_CLASS_CODE'	: $field = "E.".$field; break;
					case'ID_ISO_CODE'	: $field = "E.".$field; break;
					case'ID_POD'	: $field = "E.".$field; break;
					case'ID_OPERATOR'	: $field = "E.".$field; break;
					case'ID_COMMODITY'	: $field = "E.".$field; break;
					case'CONT_TYPE'	: $field = "E.".$field; break;
					case'WEIGHT'	: $field = "E.".$field; break;
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
													CASE
														WHEN (E.ID_CLASS_CODE='TI' OR (E.ID_CLASS_CODE='I' AND A.EVENT='P')) THEN
															'DS'
														WHEN (E.ID_CLASS_CODE='I' AND A.EVENT='O') THEN
															'GO'
														WHEN (E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O')) THEN
															'LD'
														WHEN (E.ID_CLASS_CODE='E' AND A.EVENT='P') THEN
															'GI'
														ELSE
															''
													END AS JOB,
													A.ID_VES_VOYAGE,
													C.MCH_NAME AS EQ,
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
													E.GT_JS_YARD,
													E.GT_JS_BLOCK,
													E.GT_JS_BLOCK_NAME,
													E.GT_JS_SLOT,
													E.GT_JS_ROW,
													E.GT_JS_TIER,
													E.YD_BLOCK,
													E.YD_BLOCK_NAME,
													E.YD_SLOT,
													E.YD_ROW,
													E.YD_TIER,
													A.EVENT,
													A.ID_OP_STATUS,
													A.STATUS_FLAG
										FROM 
											JOB_YARD_MANAGER A
											INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT
											INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
											INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
											LEFT JOIN M_MACHINE D ON A.ID_MACHINE_ITV=D.ID_MACHINE
											LEFT JOIN MCH_WORKING_SEQUENCE B ON A.ID_MCH_WORKING_PLAN=B.ID_MCH_WORKING_PLAN AND A.SEQ_MCH_WORKING_PLAN=B.SEQUENCE
										$qWhere
										$qSort) V
							) B
						$qPaging";
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
	}

	public function get_data_yard_job_list($paging=false, $sort=false, $filters=false, $url=false){
		$query_count = "SELECT COUNT(NO_CONTAINER) TOTAL
						FROM JOB_YARD_MANAGER
						WHERE STATUS_FLAG='P' 
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
		$qWheremin = '';
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
					case'ID_VES_VOYAGE'	: $field = "F.VESSEL_NAME"; break;
					case'EQ'			: $field = "C.MCH_NAME"; break;
					case'ITV'			: $field = "D.MCH_NAME"; break;
					case'ID_CLASS_CODE'	: $field = "E.".$field; break;
					case'ID_ISO_CODE'	: $field = "E.".$field; break;
					case'ID_POD'		: $field = "E.".$field; break;
					case'ID_OPERATOR'	: $field = "E.".$field; break;
					case'ID_COMMODITY'	: $field = "E.".$field; break;
					case'CONT_TYPE'		: $field = "E.".$field; break;
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
					    }
					    break;
					case'CMPLT_DT'	: $field = "MINUTES"; break;
				}
				
				if($field != 'JOB'){
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
					    case 'min' : $qWheremin .= "WHERE MINUTES <= ".$value; $qWhere = "WHERE 1=1"; break;
				    }
				}
			}
			$qWhere .= $qs;
			$qWhere .= $qryJobWhere != '' ? ' AND '.$qryJobWhere : '';
			
		}
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT A.NO_CONTAINER,
													A.POINT,
													CASE
														WHEN (E.ID_CLASS_CODE='TI' OR (E.ID_CLASS_CODE='I' AND A.EVENT='P')) THEN
															'DS'
														WHEN (E.ID_CLASS_CODE='I' AND A.EVENT='O') THEN
															'GO'
														WHEN (E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O')) THEN
															'LD'
														WHEN (E.ID_CLASS_CODE='E' AND A.EVENT='P') THEN
															'GI'
														ELSE
															''
													END AS JOB,
													A.ID_VES_VOYAGE,
													C.MCH_NAME AS EQ,
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
													E.GT_JS_YARD,
													E.GT_JS_BLOCK,
													E.GT_JS_BLOCK_NAME,
													E.GT_JS_SLOT,
													E.GT_JS_ROW,
													E.GT_JS_TIER,
													E.YD_BLOCK,
													E.YD_BLOCK_NAME,
													E.YD_SLOT,
													E.YD_ROW,
													E.YD_TIER,
													A.EVENT,
													A.ID_OP_STATUS,
													A.STATUS_FLAG, 
													TO_CHAR(A.COMPLETE_DATE, 'DD-MM-YYYY hh24:mi:ss') as MIN,
													(SYSDATE - A.COMPLETE_DATE)* 24 * 60 as MINUTES
										FROM 
											JOB_YARD_MANAGER A
											INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT
											INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE
											INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
											LEFT JOIN M_MACHINE D ON A.ID_MACHINE_ITV=D.ID_MACHINE
											LEFT JOIN MCH_WORKING_SEQUENCE B ON A.ID_MCH_WORKING_PLAN=B.ID_MCH_WORKING_PLAN AND A.SEQ_MCH_WORKING_PLAN=B.SEQUENCE
										$qWhere
										$qSort) V $qWheremin
							) B
						$qPaging";
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
		$query_count = "SELECT COUNT(NO_CONTAINER) TOTAL
						FROM JOB_GATE_MANAGER WHERE STATUS_FLAG!='C'";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
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
		$qWhere = "WHERE 1=1 AND A.STATUS_FLAG!='C'";
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
					case'ID_VES_VOYAGE'	: $field = "E.VESSEL_NAME"; break;
					case'TRX_NUMBER'	: 
					case'GTIN_DATE'	: 
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
													C.NO_POL,
													B.GT_WEIGHT WEIGHT,
													D.ID_AXLE,
													TO_CHAR(A.GTIN_DATE, 'DD-MM-YYYY HH24:MI:SS') GTIN_DATE,
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
										$qWhere
										$qSort) V
							) B";
		// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
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
		$query_count = "SELECT COUNT(NO_CONTAINER) TOTAL
						FROM JOB_GATE_MANAGER WHERE STATUS_FLAG!='C'";
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
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND A.STATUS_FLAG!='C'";
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
					case'ID_VES_VOYAGE'	: $field = "E.VESSEL_NAME"; break;
					case'TRX_NUMBER'	: 
					case'GTIN_DATE'	: 
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
													C.NO_POL,
													B.GT_WEIGHT WEIGHT,
													D.ID_AXLE,
													TO_CHAR(A.GTIN_DATE, 'DD-MM-YYYY HH24:MI:SS') GTIN_DATE,
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
										$qWhere
										$qSort) V
							) B
						$qPaging";
		// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
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
		$param = array($no_container);
		$query = "SELECT NO_CONTAINER, MAX(POINT) POINT
					FROM CON_LISTCONT
					WHERE NO_CONTAINER = ?
					GROUP BY NO_CONTAINER";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row_array();
		$max_point = $data['POINT'];
		
		// return $data['POINT'];
		
		$qWhere = '';
		if ($max_point && $point){
			if ($point>$max_point){
				$point = $max_point;
			}
			array_push($param, $point);
			$qWhere = ' AND c.POINT=? ';
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
				 DECODE (jgm.payment_status, 1, 'Y', 'N') payment,
				 TO_CHAR(jgm.paythrough_date, 'DD-MM-YYYY HH24:MI:SS') paythrough_date,
				 jgm.trx_number,
				 mos.id_op_status,
				 mos.op_status_desc,
				 mos.op_status_group cont_location,
				 c.no_container,
				 c.point,
				 c.yd_block_name,
				 c.yd_slot,
				 c.yd_row,
				 c.yd_tier,
				 c.temp,
				 c.unno,
				 c.imdg,
				 c.seal_numb,
				 DECODE(c.itt_flag,'N','No','Yes') ITT_FLAG,
				 DECODE(c.tl_flag,'N','No','Yes') TL_FLAG,
				 (SELECT d.BAY_ FROM CON_OUTBOUND_SEQUENCE d 
					WHERE d.NO_CONTAINER = c.no_container
						AND d.POINT = c.point AND rownum = 1) vsp_bay,
				 (SELECT d.ROW_ FROM CON_OUTBOUND_SEQUENCE d 
					WHERE d.NO_CONTAINER = c.no_container
						AND d.POINT = c.point AND rownum = 1) vsp_row,
				 (SELECT d.TIER_ FROM CON_OUTBOUND_SEQUENCE d 
					WHERE d.NO_CONTAINER = c.no_container
						AND d.POINT = c.point AND rownum = 1) vsp_tier						
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
		   WHERE     c.NO_CONTAINER = ? $qWhere
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
		// return $query;
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row_array();
		
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
	
	public function get_data_container_inquiryGate($no_container, $point, $typeGate, $recDelGate){
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
				NO_CONTAINER = '".$no_container."' and POINT  = '".$point."' 
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
					END
					TR_JOB,
					TO_CHAR (A.PAYTHROUGH_DATE, 'DD-MM-YYYY') VALID_DATE,
					C.NO_POL TRUCK_NUMBER,
					D.SEAL_NUMB AS SEAL_ID,
					E.DAMAGE AS damageCont,
					F.DAMAGE_LOCATION damageContLoc,
					NVL (D.WEIGHT, 0) WEIGHT,
					A.STATUS_FLAG,
					A.PAYMENT_STATUS
				FROM 
	              	job_gate_manager A
				JOIN 
					ves_voyage b ON A.ID_VES_VOYAGE = B.ID_VES_VOYAGE
				JOIN 
					con_listcont D
						ON
							A.NO_CONTAINER = D.NO_CONTAINER AND 
							A.ID_VES_VOYAGE = D.ID_VES_VOYAGE AND 
							A.EI = D.ID_CLASS_CODE
				LEFT JOIN 
					m_damage E ON D.ID_DAMAGE = E.ID_DAMAGE
				LEFT JOIN 
					M_DAMAGE_LOCATION F ON D.ID_DAMAGE_LOCATION = F.ID_DAMAGE_LOCATION
				LEFT JOIN 
					m_truck C ON A.ID_TRUCK = C.ID_TRUCK
				WHERE     
					A.NO_CONTAINER = '$no_container' AND 
					A.POINT='$point' AND 
					A.EI = '$ei'
	                   --AND A.PAYMENT_DATE IS NOT NULL
	                   --AND A.PAYMENT_STATUS = 1";
			// echo $query;
			// die;
        }
        else if ($FL_TONGKANG == 'Y') {
			$ei='I';
        	$query = "SELECT A.NO_CONTAINER, A.POINT, A.ID_ISO_CODE, A.EI, B.VESSEL_NAME || ' (' || B.VOY_IN || ' ' || B.VOY_OUT || ') ' AS VESSEL_VOYAGE, A.ID_VES_VOYAGE, A.GTIN_DATE TRINDATE, A.GTOUT_DATE TROTDATE, 'CONTAINER WEIGHING' TR_JOB, TO_CHAR (A.PAYTHROUGH_DATE, 'DD-MM-YYYY') VALID_DATE, C.NO_POL TRUCK_NUMBER, D.SEAL_NUMB AS SEAL_ID, E.DAMAGE AS damageCont, F.DAMAGE_LOCATION damageContLoc, NVL (D.WEIGHT, 0) WEIGHT, A.STATUS_FLAG, A.PAYMENT_STATUS, D.FL_TONGKANG FROM  job_gate_manager A JOIN ves_voyage b ON A.ID_VES_VOYAGE = B.ID_VES_VOYAGE JOIN con_listcont D ON A.NO_CONTAINER = D.NO_CONTAINER AND A.ID_VES_VOYAGE = D.ID_VES_VOYAGE LEFT JOIN m_damage E ON D.ID_DAMAGE = E.ID_DAMAGE LEFT JOIN M_DAMAGE_LOCATION F ON D.ID_DAMAGE_LOCATION = F.ID_DAMAGE_LOCATION LEFT JOIN m_truck C ON A.ID_TRUCK = C.ID_TRUCK WHERE A.NO_CONTAINER = '$no_container' AND A.POINT='$point' AND A.EI = '$ei'";
        }

        // return $query;

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
					where A.status_flAg!='C' AND A.NO_CONTAINER='$no_container' AND A.EI='$ei' and A.PAYMENT_DATE IS NOT NULL AND A.PAYMENT_STATUS=1 and TL_FLAG = 'Y'";
//		echo $query;die;
		$rs 		= $this->db->query($query);
		$data 		= $rs->row_array();
		
		return $data;
	}
	
	/*CONTAINER STATUS CHANGE*/
	public function getDataContainerStatusChange($no_container,$inbOutb){
		$query = "select NO_CONTAINER||'^'||POINT||'^'||ID_VES_VOYAGE||'^'||ID_CLASS_CODE ID_CONTCONV , NO_CONTAINER||' Point: '||POINT||', '||ID_VES_VOYAGE||', '||ID_CLASS_CODE NO_CONTAINER_EXP from con_listcont where no_container='$no_container' and id_class_code='$inbOutb'";
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
	
		$query = "select A.NO_CONTAINER, A.ID_CLASS_CODE EI, A.CONT_STATUS FE, A.ID_OPERATOR OPERATOR,A.ID_ISO_CODE, A.ID_VES_VOYAGE, B.VESSEL_NAME, B.VOY_IN||' - '||B.VOY_OUT AS VOYAGE, A.ID_POD AS POD, A.POINT AS POINTS, A.ID_OP_STATUS AS LOCATION_CHG  from con_listcont A JOIN VES_VOYAGE B ON A.ID_VES_VOYAGE=B.ID_VES_VOYAGE where A.no_container='$noContainer' and A.id_class_code='$ei' AND A.POINT='$point' AND A.ID_VES_VOYAGE='$ukk'";
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
					WHERE NO_CONTAINER='$no_container' AND POINT='$point' AND ID_OP_STATUS IN ('YYY')
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
				array('name'=>':v_out', 'value'=>&$msg_out, 'length'=>100)
			);
		$this->db->trans_start();
		$query = "begin PRC_SAVECHANGE(:v_nocontainer, :v_pointcontainer,:v_ei,:v_idvsbvoyage,:v_laststat,:v_userid,:v_out); end;";

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
		$param = array($no_container, $point);
		$query 		= "SELECT
						ID_OP_STATUS,
						OP_STATUS_DESC,
						TO_CHAR(DATE_HISTORY, 'DD-MM-YYYY HH24:MI:SS') DATE_HISTORY_CHAR
					FROM CON_LISTCONT_HIST
					WHERE NO_CONTAINER=? AND POINT=?
					ORDER BY DATE_HISTORY";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_container_list_of_point($no_container){
		$param = array($no_container);
		$query 		= "SELECT
						POINT
					FROM CON_LISTCONT
					WHERE NO_CONTAINER=?
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
					WHERE A.NO_CONTAINER=? AND A.ACTIVE='Y'
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
	
	public function saveContainerGate($nocontainer, $pointcontainer, $truckJob, $ei, $idvesvoyage, $trucknumber, $sealid, $weight, $userid, $dmg, $dmgLoc){
		// small vessel
		$query_cekTongkang = "
			SELECT
				FL_TONGKANG
			FROM
				CON_LISTCONT
			WHERE
				NO_CONTAINER = '".$nocontainer."' AND POINT = '".$pointcontainer."'
		";
//		echo '<pre>'.$query_cekTongkang.'</pre>';
		$rs_cekTongkang = $this->db->query($query_cekTongkang);
		$row_cekTongkang = $rs_cekTongkang->row_array();
		$FL_TONGKANG = $row_cekTongkang['FL_TONGKANG'];
//		echo '<pre>FL_TONGKANG : '.$FL_TONGKANG.'</pre>';
//		exit;
		if ($FL_TONGKANG == 'Y') {
			// $ei = 'I';
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
				array('name'=>':v_out', 'value'=>&$msg_out, 'length'=>1000)
			);

			if ($weight <= 0) {
				return array(
					'success'=>false,
					'errors'=>'WEIGHT CANT BE NULL!'
				);
			}
			else{
				$this->db->trans_start();
				$query = "begin prc_GO_ContainerWeighing2(
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
					:v_out
				); end;";

		       // var_dump($param); die();
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
		}
		else{
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
				array('name'=>':v_out', 'value'=>&$msg_out, 'length'=>1000)
			);

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
				:v_out
			); end;";

	       // var_dump($param); die();
//			echo '<pre>';print_r($param);echo '</pre>';
			$this->db->exec_bind_stored_procedure($query, $param);
		
//			echo '<pre>';print_r($msg_out);echo '</pre>';
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
				array('name'=>':v_out', 'value'=>&$msg_out, 'length'=>100)
			);
		$this->db->trans_start();
		// $msg_out = 'OK';
		$query = "begin prc_gateOperationAdmin(:v_nocontainer, :v_pointcontainer,:v_date_gate,:v_trucknumber,:v_sealnumber,:v_ei,:v_trjob,:v_idvsbvoyage,:v_weight,:v_userid,:v_dmg ,:v_dmgLoc,:v_remarks,:v_out); end;";
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
				array('name'=>':v_out', 'value'=>&$msg_out, 'length'=>200)
			);
		var_dump($param); die;
		$this->db->trans_start();
		$query = "begin prc_gateOperationAuto(:v_nocontainer, :v_pointcontainer,:v_trucknumber,:v_sealnumber,:v_ei,:v_trjob,:v_idvsbvoyage,:v_weight,:v_userid,:v_dmg ,:v_dmgLoc,:v_out); end;";
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
		$query = "SELECT COUNT(*) JUMLAH FROM CON_LISTCONT WHERE NO_CONTAINER=? AND ACTIVE='Y'";
		$rs = $this->db->query($query, $param);
		$data = $rs->row_array();
		$count_active = $data['JUMLAH'];
		//echo $query;die;
		
		if ($count_active==0){
			$param = array(
					array('name'=>':v_oldnocont', 'value'=>$a, 'length'=>30),
					array('name'=>':v_oldpoint', 'value'=>$b, 'length'=>50),
					array('name'=>':v_newnocont', 'value'=>$c, 'length'=>50),
					array('name'=>':v_userid', 'value'=>$id_user, 'length'=>50)
				);
			$this->db->trans_start();
			$query = "begin PRC_INS_CONTRENAME(:v_oldnocont, :v_oldpoint,:v_newnocont, :v_userid ); end;";
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
		$param = array($no_container);
		$query = "SELECT NO_CONTAINER, MAX(POINT) POINT
					FROM CON_LISTCONT
					WHERE NO_CONTAINER = ?
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
				 c.tl_flag,
				 c.OVER_HEIGHT,
				 c.OVER_RIGHT,
				 c.OVER_LEFT,
				 c.OVER_FRONT,
				 c.OVER_REAR
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
		   WHERE     c.NO_CONTAINER = ? AND C.ACTIVE='Y' $qWhere
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
		if ($data['ID_POR']=='Autocomplete'){
			$data['ID_POR'] = "";
		}
		
		$param = array(
				array('name'=>':v_no_container', 'value'=>$data['NO_CONTAINER'], 'length'=>15),
				array('name'=>':v_point', 'value'=>$data['POINT'], 'length'=>10),
				array('name'=>':v_class', 'value'=>$data['ID_CLASS_CODE'], 'length'=>5),
				array('name'=>':v_cont_iso', 'value'=>$data['ID_ISO_CODE'], 'length'=>4),
				array('name'=>':v_cont_size', 'value'=>$data['CONT_SIZE'], 'length'=>5),
				array('name'=>':v_cont_type', 'value'=>$data['CONT_TYPE'], 'length'=>10),
				array('name'=>':v_cont_height', 'value'=>$data['CONT_HEIGHT'], 'length'=>10),
				array('name'=>':v_id_operator', 'value'=>$data['ID_OPERATOR'], 'length'=>10),
				array('name'=>':v_id_commodity', 'value'=>$data['ID_COMMODITY'], 'length'=>10),
				array('name'=>':v_weight', 'value'=>$data['WEIGHT'], 'length'=>10),
				array('name'=>':v_temp', 'value'=>$data['TEMP'], 'length'=>10),
				array('name'=>':v_seal_numb', 'value'=>$data['SEAL_NUMB'], 'length'=>10),
				array('name'=>':v_id_ves_voyage', 'value'=>$data['ID_VES_VOYAGE'], 'length'=>20),
				array('name'=>':v_id_pol', 'value'=>$data['ID_POL'], 'length'=>10),
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
		 
		 $query = "begin PROC_SINGLE_CORRECTION(:v_no_container, :v_point, :v_class, :v_cont_iso, :v_cont_size, :v_cont_type, :v_cont_height, :v_id_operator, :v_id_commodity, :v_weight, :v_temp, :v_seal_numb, :v_id_ves_voyage, :v_id_pol, :v_id_pod, :v_id_por, :v_status_cont, :v_tlflag, :v_oh, :v_ow_l, :v_ow_r, :v_ol_f, :v_ol_b, :v_userid, :v_msg_out); end;";
		
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
	
	public function yard_placement_submit($no_container, $point, $id_op_status, $event, $user_id, $yard_position, $id_machine, $driver_id){
		// penambahan untuk small vessel
		$tofSV = "SELECT FL_TONGKANG, WEIGHT FROM CON_LISTCONT WHERE NO_CONTAINER = '".$no_container."' AND POINT =  '".$point."'";
		$rsTOF = $this->db->query($tofSV);
		$rowTOF = $rsTOF->row_array();
		if($rowTOF['FL_TONGKANG'] == 'Y' and $rowTOF['WEIGHT'] == 0){
			return array('F', 'Container dari small vessel dan belum ditimbang... harap timbang terlebih dahulu...');
		}
		// penambahan untuk small vessel
		$status_flag = 'F';
		$message = '';
		
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
			array('name'=>':status_flag', 'value'=>&$status_flag, 'length'=>1),
			array('name'=>':message', 'value'=>&$message, 'length'=>1000)
		);
//		 echo '<pre>';print_r($param);echo '</pre>';
//		 exit;
		
		$sql = "BEGIN PROC_JOB_YARD_COMPLETE(:no_container, :point, :id_op_status, :event, :user_id, :driver_id, :id_block, :block_, :slot_, :row_, :tier_, :id_machine, :status_flag, :message); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);

		return array($status_flag, $message);
	}
	
	public function tally_confirm_submit($no_container, $point, $class, $location, $id_user, $driver_id, $id_machine, $id_machine_quay, $dmgpart, $dmg, $seal_num){
		$v_out = 'NOT OK';
		$v_out_msg = 'TIDAK OK';

		
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
			array('name'=>':seal_num', 'value'=>$seal_num, 'length'=>50),
			array('name'=>':v_out', 'value'=>&$v_out, 'length'=>8),
			array('name'=>':v_out_msg', 'value'=>&$v_out_msg, 'length'=>500),
		);
		
		// print_r($param);
		
		$sql = "BEGIN PROC_JOB_QUAY_COMPLETE(:no_container,:point,:class,:location,:id_user,:driver_id,:id_machine,:id_machine_quay,:id_dmgpart,:id_dmg,:seal_num,:v_out,:v_out_msg); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		
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
				array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>500)
			);
		// print_r($param);
		$query = "BEGIN PROC_DISABLE_CONTAINER(:v_no_container, :v_point, :v_remarks, :v_userid, :v_msg_out); end;";
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
						WHERE A.ACTIVE='Y' $q_in_con";
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
													A.OVER_REAR
										FROM 
											CON_LISTCONT A
										$qWhere
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
		$param = array(
				array('name'=>':v_no_container', 	'value'=>$no_container, 		'length'=>15),
				array('name'=>':v_point', 			'value'=>$point, 				'length'=>10),
				array('name'=>':v_id_ves_voyage', 	'value'=>$data['ID_VES_VOYAGE'],'length'=>20),
				array('name'=>':v_over_height', 	'value'=>$data['OVER_HEIGHT'], 	'length'=>10),
				array('name'=>':v_over_right', 		'value'=>$data['OVER_RIGHT'], 	'length'=>10),
				array('name'=>':v_over_left', 		'value'=>$data['OVER_LEFT'], 	'length'=>10),
				array('name'=>':v_over_front', 		'value'=>$data['OVER_FRONT'], 	'length'=>10),
				array('name'=>':v_over_rear', 		'value'=>$data['OVER_REAR'], 	'length'=>10),
				array('name'=>':v_tlflag', 			'value'=>$data['TL_FLAG'], 		'length'=>10),
				array('name'=>':v_userid', 			'value'=>$id_user, 				'length'=>50),
				array('name'=>':v_msg_out', 		'value'=>&$msg_out, 			'length'=>500)
			);
		 #print_r($param);
		$query = "begin PROC_MULTIPLE_CORRECTION(:v_no_container, :v_point, :v_id_ves_voyage,:v_over_height,:v_over_right,:v_over_left,:v_over_front,:v_over_rear,:v_tlflag, :v_userid, :v_msg_out); end;";
		//echo $query;die;
		$this->db->exec_bind_stored_procedure($query, $param);
		if ($msg_out==''){
			return array('S',$msg_out);
		}else{
			return array('F',$msg_out);
		}
	}
	
	public function get_data_multiple_correction_tl($id_ves_voyage, $container_list=false, $paging=false, $sort=false, $filters=false){
		$q_in_con = '';
		if ($container_list){
			$q_in_con = " AND A.NO_CONTAINER IN (".$container_list.") ";
		}
		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM CON_LISTCONT A
						WHERE A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_OP_STATUS='BPL' AND A.ID_CLASS_CODE='I' AND A.TL_FLAG='N' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') AND (ITT_FLAG = 'N' OR ITT_FLAG IS NULL)
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
										$qWhere
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
		$id_ves_voyage = $_POST['ID_VES_VOYAGE'];
		$container_data = json_decode($data['container_data']);
	
		if ($msg_out==''){
			for ($i=0;$i<sizeof($container_data);$i++){
				$no_container = $container_data[$i]->NO_CONTAINER;
				$point = $container_data[$i]->POINT;
				// print_r($param);
				$query = "UPDATE CON_LISTCONT SET TL_FLAG='Y' WHERE NO_CONTAINER = '$no_container' AND POINT = '$point' AND ID_OP_STATUS='BPL' AND ID_CLASS_CODE='I' AND TL_FLAG='N' AND (NO_REQUEST IS NULL OR NO_REQUEST='') AND (ITT_FLAG = 'N' OR ITT_FLAG IS NULL)";
				// echo $query;die;
				$this->db->query($query);
			}
		}
		if($this->db->trans_complete()){
			return array(
						'success'=>true,
						'errors'=> 'Save success'
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
						WHERE A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_OP_STATUS='BPL' AND A.ID_CLASS_CODE='I' AND A.TL_FLAG='N' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') $q_in_con";
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
		$qWhere = "WHERE 1=1 AND A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_OP_STATUS='BPL' AND A.ID_CLASS_CODE='I' AND A.TL_FLAG='N' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') $q_in_con";
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
										$qWhere
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
	
	public function save_transhipment_container($data,$id_user){
		$msg_complete = '';
		$old_id_ves_voyage = $_POST['OLD_ID_VES_VOYAGE'];
		$id_ves_voyage = $_POST['ID_VES_VOYAGE'];
		$via_gate = $_POST['VIA_GATE'];
		$doc_number = $_POST['DOC_NUMBER'];
		$container_data = json_decode($data['container_data']);
		// print $id_ves_voyage.'<br/>';
		// print $via_gate.'<br/>';
		// print $doc_number.'<br/>';
		// print_r($container_data);
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
				$param = array(
					array('name'=>':v_no_container', 'value'=>$no_container, 'length'=>15),
					array('name'=>':v_point', 'value'=>$point, 'length'=>10),
					array('name'=>':v_id_transhipment', 'value'=>$id_transhipment, 'length'=>50),
					array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>500)
				);
				// print_r($param);
				$query = "BEGIN PROC_TRANSHIPMENT_CONT_DETAIL(:v_no_container, :v_point, :v_id_transhipment, :v_msg_out); end;";
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
	
	public function get_data_itt_container_list($id_ves_voyage, $container_list=false, $paging=false, $sort=false, $filters=false){
		$q_in_con = '';
		if ($container_list){
			$q_in_con = " AND A.NO_CONTAINER IN (".$container_list.") ";
		}
		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM CON_LISTCONT A
						WHERE A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_CLASS_CODE='I' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') AND A.ITT_FLAG='N' $q_in_con";
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
										$qWhere
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
		$param = array($category_name, $category_id);
		
		$this->db->trans_start();
		
		$query 	= "UPDATE M_PLAN_CATEGORY_H
					SET CATEGORY_NAME = ?
					WHERE ID_CATEGORY = ?";
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
						WHERE A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ITT_FLAG='Y' AND (GT_DATE_OUT IS NULL OR GT_DATE_OUT='') AND C.STATUS_FLAG='G' $q_in_con";
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
										$qWhere
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
						WHERE A.ID_CLASS_CODE='E' $q_in_con";
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
										$qWhere
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
					   WHERE TRIM(ID_VES_VOYAGE) = UPPER('$id_ves_voyage')";
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
						  AND TRIM(UPPER(STATUS)) = 'FULL'";
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
								AND TRIM(UPPER(STATUS)) = 'FULL'";
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
								 ID_EDI)
								VALUES ('$id_ves_voyage',
										'COARRI',
										'$file_name_dsf',
										'I',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
			$this->db->query($queryInsertDsf);
			
			array_push($file_generated, $file_name_dsf);
		}

		$jml_dse 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'I'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'";
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
								AND TRIM(UPPER(STATUS)) = 'EMPTY'";
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
								 ID_EDI)
								VALUES ('$id_ves_voyage',
										'COARRI',
										'$file_name_dse',
										'I',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
			$this->db->query($queryInsertDse);
			
			array_push($file_generated, $file_name_dse);
		}
		
		$jml_lof 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'FULL'";
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
								AND TRIM(UPPER(STATUS)) = 'FULL'";
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
								 ID_EDI)
								VALUES ('$id_ves_voyage',
										'COARRI',
										'$file_name_lof',
										'E',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
			$this->db->query($queryInsertLof);
			
			array_push($file_generated, $file_name_lof);
		}
		
		$jml_loe 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'";
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
								AND TRIM(UPPER(STATUS)) = 'EMPTY'";
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
								 ID_EDI)
								VALUES ('$id_ves_voyage',
										'COARRI',
										'$file_name_loe',
										'E',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
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
						  AND SEND_STATUS<>2 ";
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
									  AND SEND_STATUS<>2";
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
				
				$query_flag_send = "UPDATE EDI_COARRI SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point'";
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
								 ID_EDI)
								VALUES ('$oprves',
										'COARRI',
										'$file_name_dsf',
										'I',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
			$this->db->query($queryInsertDsf);
			
			array_push($file_generated, $file_name_dsf);
		}

		$jml_dse 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'I'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND SEND_STATUS<>2";
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
									  AND SEND_STATUS<>2";
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
				
				$query_flag_send = "UPDATE EDI_COARRI SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point'";
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
								 ID_EDI)
								VALUES ('$oprves',
										'COARRI',
										'$file_name_dse',
										'I',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
			$this->db->query($queryInsertDse);
			
			array_push($file_generated, $file_name_dse);
		}
		
		$jml_lof 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'FULL'
						  AND SEND_STATUS<>2";
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
									  AND SEND_STATUS<>2";
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
				
				$query_flag_send = "UPDATE EDI_COARRI SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point'";
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
								 ID_EDI)
								VALUES ('$oprves',
										'COARRI',
										'$file_name_lof',
										'E',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
			$this->db->query($queryInsertLof);
			
			array_push($file_generated, $file_name_lof);
		}
		
		$jml_loe 	= "SELECT COUNT(*) AS JML FROM EDI_COARRI 
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND SEND_STATUS<>2";
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
									  AND SEND_STATUS<>2";
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
				
				$query_flag_send = "UPDATE EDI_COARRI SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point'";
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
								 ID_EDI)
								VALUES ('$oprves',
										'COARRI',
										'$file_name_loe',
										'E',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
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
					   WHERE TRIM(ID_VES_VOYAGE) = UPPER('$id_ves_voyage')";
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
						  AND TRUCK_OUT_DATE IS NOT NULL";
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
							   AND TRUCK_OUT_DATE IS NOT NULL";
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
								 ID_EDI)
								VALUES ('$id_ves_voyage',
										'CODECO',
										'$file_name_gof',
										'I',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
			$this->db->query($queryInsertGof);
			
			array_push($file_generated, $file_name_gof);
		}
		
		$jml_goe 	= "SELECT COUNT(*) AS JML FROM EDI_CODECO 
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'I'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND TRUCK_OUT_DATE IS NOT NULL";
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
								AND TRUCK_OUT_DATE IS NOT NULL";
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
								 ID_EDI)
								VALUES ('$id_ves_voyage',
										'CODECO',
										'$file_name_goe',
										'I',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
			$this->db->query($queryInsertGoe);
			
			array_push($file_generated, $file_name_goe);
		}
		
		$jml_gif 	= "SELECT COUNT(*) AS JML FROM EDI_CODECO 
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'FULL'
						  AND TRUCK_IN_DATE IS NOT NULL";
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
								AND TRUCK_IN_DATE IS NOT NULL";
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
								 ID_EDI)
								VALUES ('$id_ves_voyage',
										'CODECO',
										'$file_name_gif',
										'E',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
			$this->db->query($queryInsertGif);
			
			array_push($file_generated, $file_name_gif);
		}
		
		$jml_gie 	= "SELECT COUNT(*) AS JML FROM EDI_CODECO 
		                WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND TRUCK_IN_DATE IS NOT NULL";
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
								AND TRUCK_IN_DATE IS NOT NULL";
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
								 ID_EDI)
								VALUES ('$id_ves_voyage',
										'CODECO',
										'$file_name_gie',
										'E',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
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
						  AND SEND_STATUS<>2";
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
									  AND SEND_STATUS<>2";
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
				
				$query_flag_send = "UPDATE EDI_CODECO SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point'";
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
								 ID_EDI)
								VALUES ('$oprves',
										'CODECO',
										'$file_name_gof',
										'I',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
			$this->db->query($queryInsertGof);
			
			array_push($file_generated, $file_name_gof);
		}
		
		$jml_goe 	= "SELECT COUNT(*) AS JML FROM EDI_CODECO 
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'I'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND TRUCK_OUT_DATE IS NOT NULL
						  AND SEND_STATUS<>2";
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
									  AND SEND_STATUS<>2";
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
				
				$query_flag_send = "UPDATE EDI_CODECO SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point'";
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
								 ID_EDI)
								VALUES ('$oprves',
										'CODECO',
										'$file_name_goe',
										'I',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
			$this->db->query($queryInsertGoe);
			
			array_push($file_generated, $file_name_goe);
		}
		
		$jml_gif 	= "SELECT COUNT(*) AS JML FROM EDI_CODECO 
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'FULL'
						  AND TRUCK_IN_DATE IS NOT NULL
						  AND SEND_STATUS<>2";
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
									  AND SEND_STATUS<>2";
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
				
				$query_flag_send = "UPDATE EDI_CODECO SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point'";
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
								 ID_EDI)
								VALUES ('$oprves',
										'CODECO',
										'$file_name_gif',
										'E',
										'FULL',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
			$this->db->query($queryInsertGif);
			
			array_push($file_generated, $file_name_gif);
		}
		
		$jml_gie 	= "SELECT COUNT(*) AS JML FROM EDI_CODECO 
		                WHERE OPR_ID = '$oprves'
						  AND E_I = 'E'
						  AND TRIM(UPPER(STATUS)) = 'EMPTY'
						  AND TRUCK_IN_DATE IS NOT NULL
						  AND SEND_STATUS<>2";
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
								  AND SEND_STATUS<>2";
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
				
				$query_flag_send = "UPDATE EDI_CODECO SET SEND_STATUS=1 WHERE NO_CONTAINER='$nocont' AND POINT='$point'";
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
								 ID_EDI)
								VALUES ('$oprves',
										'CODECO',
										'$file_name_gie',
										'E',
										'EMPTY',
										'$id_user',
										SYSDATE,
										seq_edi_generate.nextval)";
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
					   	AND ID_CLASS_CODE='$b' and ID_OP_STATUS IN ('YGY','YYY','YIF','YSY')";
		$rs 		= $this->db->query($query);
		$data 		= $rs->row_array();
		
		return $data;
	}
	
	public function insert_container_hk(){
		$a=$_POST['no_container'];
		$b=$_POST['hkp_id'];
		$c=$_POST['point'];
		
		$param = array(
				array('name'=>':param', 'value'=>$a.'^'.$b.'^'.$c, 'length'=>100)
			);
		// print_r($param);
		$this->db->trans_start();
		$query = "begin prc_add_container_hk(:param); end;";
		$this->db->exec_bind_stored_procedure($query, $param);
		
		if ($this->db->trans_complete()){
			return 1;
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
					   WHERE TRIM(ID_VES_VOYAGE) = UPPER('$id_ves_voyage')";
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

		$queryInsertBpe = "INSERT INTO EDI_GENERATE_LOGFILE 
							(ID_VES_VOYAGE,
							 EDI_TYPE,
							 FILE_NAME,
							 E_I,
							 STATUS,
							 CREATED_BY,
							 CREATED_DATE,
							 ID_EDI)
							VALUES ('$id_ves_voyage',
									'BAPLIE',
									'$file_name',
									'E',
									'ALL',
									'$id_user',
									SYSDATE,
									seq_edi_generate.nextval)";
		$this->db->query($queryInsertBpe);
		
		if ($this->db->trans_complete()){
			return array('flag'=>1, 'msg'=>'OK');
		}else{
			return array('flag'=>0, 'msg'=>'error generate baplie');
		}
		
		return 'OK';
	}		
	
	public function get_container_outbound_stacking_list($id_ves_voyage, $paging=false, $sort=false, $filters=false){
		$query_count = "SELECT COUNT(NO_CONTAINER) TOTAL
						FROM JOB_PLACEMENT
						WHERE ID_VES_VOYAGE='$id_ves_voyage' AND ID_CLASS_CODE='E'";
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
			if ($sortProperty=='YARD_POS'){
				$sortProperty = 'YD_BLOCK_NAME';
			}
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		
		$qWhere = "WHERE 1=1 AND A.ID_VES_VOYAGE='$id_ves_voyage' AND A.ID_CLASS_CODE='E'";
		
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
		
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (  SELECT
											A.NO_CONTAINER,
											A.POINT,
											B.ID_ISO_CODE,
											B.CONT_SIZE,
											B.CONT_TYPE,
											B.CONT_STATUS,
											B.HAZARD,
											B.WEIGHT,
											B.ID_POD,
											B.YD_BLOCK_NAME,
											B.YD_SLOT,
											B.YD_ROW,
											B.YD_TIER
										FROM JOB_PLACEMENT A INNER JOIN
										CON_LISTCONT B ON A.NO_CONTAINER=B.NO_CONTAINER AND A.POINT=B.POINT
										$qWhere
										$qSort) V
							) B
						$qPaging";
		// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		for ($i=0; $i<sizeof($container_list); $i++){
			$container_list[$i]['YARD_POS'] = '';
			$container_list[$i]['WEIGHT'] = $container_list[$i]['WEIGHT']/1000;
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
	
	public function getAll_container_outbound_stacking_list($id_ves_voyage){
		$param = array($id_ves_voyage);
		$query = "SELECT
						A.NO_CONTAINER,
						A.POINT,
						B.ID_ISO_CODE,
						B.CONT_SIZE,
						B.CONT_TYPE,
						B.CONT_STATUS,
						B.HAZARD,
						B.WEIGHT,
						B.ID_POD,
						B.YD_BLOCK_NAME,
						B.YD_SLOT,
						B.YD_ROW,
						B.YD_TIER,
						B.NPE
					FROM JOB_PLACEMENT A INNER JOIN
					CON_LISTCONT B ON A.NO_CONTAINER=B.NO_CONTAINER AND A.POINT=B.POINT
					WHERE A.ID_VES_VOYAGE=? AND A.ID_CLASS_CODE='E'";
		// print $query;
		$rs = $this->db->query($query, $param);
		$container_list = $rs->result_array();
		for ($i=0; $i<sizeof($container_list); $i++){
			$container_list[$i]['YARD_POS'] = '';
			if ($container_list[$i]['YD_BLOCK_NAME']!=''){
				$container_list[$i]['YARD_POS'] = $container_list[$i]['YD_BLOCK_NAME'].'-'.$container_list[$i]['YD_SLOT'].'-'.$container_list[$i]['YD_ROW'].'-'.$container_list[$i]['YD_TIER'];
			}
		}
		
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
				array('name'=>':param', 'value'=>$params, 'length'=>100)
			);
		// print_r($param);
		$this->db->trans_start();
		$query = "begin prc_activate_hkp(:param); end;";
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
		// print_r($param);
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
		$query = "UPDATE EDI_COARRI SET SEND_STATUS='$flag' WHERE SEND_STATUS=1";
		$this->db->query($query, $param);
		$query = "UPDATE EDI_CODECO SET SEND_STATUS='$flag' WHERE SEND_STATUS=1";
		$this->db->query($query, $param);
	}
	
	public function create_hk_plan(){
		$hkp_mvdesc=$_POST['mv_Desc'];
		$itv_use=$_POST['itv_use'];
		$mv_order=$_POST['mv_Order'];
		$virt_crane=$_POST['virtual_crane'];
		$iduser=$this->session->userdata('id_user');
		
		$query = "INSERT INTO CON_HKP_PLAN (HKP_MV_DESC, ITV_USE, ID_MACHINE, CREATED_ID_USER, HKP_STATUS, HKP_ACTIVITY)
					values
					('$hkp_mvdesc','$itv_use','$virt_crane','$iduser','N','$mv_order')";
		$this->db->query($query);
	}
	
	public function content_hk_grid(){
		$query_count = "SELECT COUNT(1) TOTAL
						FROM con_hkp_plan
						WHERE HKP_STATUS!='C'";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		
		$query = "SELECT A.HKP_ID, A.HKP_MV_DESC, A.ITV_USE, B.MCH_NAME, A.HKP_STATUS, C.HKP_ACTIVITY_DESC
                          FROM CON_HKP_PLAN A JOIN M_MACHINE B ON A.ID_MACHINE=B.ID_MACHINE
						  JOIN M_HKP_ACTIVITY C ON A.HKP_ACTIVITY=C.HKP_ACTIVITY
                          WHERE A.HKP_STATUS!='C'";
		// print $query;
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
						WHERE HKP_ID='$hkp_id'";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		
		$query = "SELECT A.NO_CONTAINER, A.POINT, B.ID_ISO_CODE, A.ID_VES_VOYAGE, A.HKP_STATUS_CONT, fc_get_hkpstatus_name(A.HKP_STATUS_CONT) as STATUS_NAME,
		(SELECT C.YD_BLOCK_NAME||' '||C.YD_SLOT||'-'||C.YD_ROW||'-'||C.YD_TIER FROM CON_LISTCONT C WHERE C.NO_CONTAINER = A.NO_CONTAINER AND C.POINT = A.POINT) as LOC_CON_REAL,
		A.GT_JS_BLOCK_NAME||' '||A.GT_JS_SLOT||'-'||A.GT_JS_ROW||'-'||A.GT_JS_TIER as LOC_CON_PLAN
                          FROM CON_HKP_PLAN_D A JOIN CON_LISTCONT B ON A.NO_CONTAINER=B.NO_CONTAINER AND A.POINT=B.POINT
                          WHERE A.HKP_ID=$hkp_id";
		// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function get_data_container_hkp($no_container, $point=false){
		$param = array($no_container);
		$query = "SELECT NO_CONTAINER, MAX(POINT) POINT
					FROM CON_LISTCONT
					WHERE NO_CONTAINER = ?
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
		   WHERE     c.NO_CONTAINER = ? AND C.ACTIVE='Y' $qWhere
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
							//print_r($param);
							
							$query = "UPDATE CON_LISTCONT
											SET NPE=?, TGL_NPE = TO_DATE(?,'DDMMYYYY')
										WHERE NO_CONTAINER=? AND ID_VES_VOYAGE=?";
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
				array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>500)
			);
			$query = "BEGIN PROC_CANCEL_TL(:v_no_container, :v_point, :v_remarks, :v_id_user, :v_msg_out); end;";
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
						WHERE A.active='Y' and A.tl_flag='Y' and A.id_class_code='I' and (A.ID_OP_STATUS ='SDG' or A.ID_OP_STATUS='BPL') $q_in_con";
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
										$qWhere
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
	
}
?>