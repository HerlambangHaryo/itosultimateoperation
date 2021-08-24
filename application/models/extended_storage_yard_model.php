<?php
class Extended_Storage_Yard_Model extends CI_Model {
	public function __construct(){
		$this->load->database();
	}
	
	public function get_data_lini2(){		
		$query 		= "SELECT ID_YARD||'|'||YARD_NAME ID_YARD, YARD_NAME FROM M_YARD_LINI2 ORDER BY YARD_NAME ASC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_data_extended_storage_yard($id_ves_voyage, $container_list=false, $paging=false, $sort=false, $filters=false){
		$q_in_con = '';
		if ($container_list){
			$q_in_con = " AND A.NO_CONTAINER IN (".$container_list.") ";
		}
		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM CON_LISTCONT A
						WHERE A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_OP_STATUS='BPL' AND A.ID_CLASS_CODE='I' AND A.TL_FLAG='N' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') AND ITT_FLAG = 'N'
						$q_in_con";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_OP_STATUS='BPL' AND A.ID_CLASS_CODE='I' AND A.TL_FLAG='N' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') AND ITT_FLAG = 'N'
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
		// print $query;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function save_extended_storage_yard($data,$id_user,$id_ves_voyage,$id_and_name_yard,$viayard){
		$splitYard = explode("|",$id_and_name_yard);
		$out = '';
		$msg_out = '';
		$msg_complete = '';
		$container_data = json_decode($data['container_data']);
	
		if ($msg_out==''){
			$query = "SELECT TO_CHAR(START_WORK, 'YYYYMMDDHH24MISS') START_WORK FROM VES_VOYAGE WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
			$rs 		= $this->db->query($query);
			$row 		= $rs->row_array();
			$start_work = $row['START_WORK'];			
			
			$query 	= "INSERT INTO CON_ITT_H (ID_VES_VOYAGE,ID_YARD_LINI2,YARD_NAME_LINI2, VIA_YARD, INS_DATE, INS_USER, START_WORK)
						VALUES
						('$id_ves_voyage',".$splitYard[0].", '".$splitYard[1]."', '$viayard', SYSDATE, $id_user, TO_DATE('$start_work', 'YYYYMMDDHH24MISS'))";
			$rs 	= $this->db->query($query);
			
			$query = "SELECT ID_ITT FROM ( SELECT ID_ITT FROM CON_ITT_H ORDER BY INS_DATE DESC) A WHERE ROWNUM = 1";
			$rs 		= $this->db->query($query);
			$row 		= $rs->row_array();
			$id_itt = $row['ID_ITT'];
			  
			
			for ($i=0;$i<sizeof($container_data);$i++){
				$no_container = $container_data[$i]->NO_CONTAINER;
				$point = $container_data[$i]->POINT;
				
				$param = array(
					array('name'=>':v_iditt', 'value'=>$id_itt, 'length'=>30),
					array('name'=>':v_nocont', 'value'=>$no_container, 'length'=>30),
					array('name'=>':v_point', 'value'=>$point, 'length'=>10),
					array('name'=>':v_idvesvoyage', 'value'=>$id_ves_voyage, 'length'=>50),
					array('name'=>':v_idlaplini2', 'value'=>$splitYard[0], 'length'=>50),
					array('name'=>':v_yardname', 'value'=>$splitYard[1], 'length'=>50),
					array('name'=>':v_viayard', 'value'=>$viayard, 'length'=>50),
					array('name'=>':v_user', 'value'=>$id_user, 'length'=>50),
					array('name'=>':v_out', 'value'=>&$out, 'length'=>100),
					array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>100)
				);
				$this->db->trans_start();
				$query = "begin PROC_SAVEREQUESTITT(:v_iditt,:v_nocont,:v_point,:v_idvesvoyage,:v_idlaplini2,:v_yardname,:v_viayard,:v_user,:v_out,:v_msg_out); end;";
				
		       	#print_r($param); die();
				$this->db->exec_bind_stored_procedure($query, $param);
				if ($this->db->trans_complete())
				{
					if($out!='OK')
					{
						$msg_complete .= $msg_out;
						return array(
							'success'=>false,
							'errors'=> $msg_complete
						);
					}
				}
			}
		}
		if($msg_complete == ''){
			return array(
						'success'=>true,
						'errors'=> 'Save success'
					);
		}else{
			return array(
						'success'=>false,
						'errors'=> $msg_complete
					);
		}
	}		
	
	public function get_data_cancel_extended_storage_yard($id_ves_voyage, $container_list=false, $paging=false, $sort=false, $filters=false){
		$q_in_con = '';
		if ($container_list){
			$q_in_con = " AND A.NO_CONTAINER IN (".$container_list.") ";
		}
		$query_count = "SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM CON_LISTCONT A
						WHERE A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_OP_STATUS IN ('BPL','SDY','YSY') AND A.ID_CLASS_CODE='I' AND A.TL_FLAG='N' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') AND ITT_FLAG = 'Y' AND A.ID_TERMINAL = '".$this->gtools->terminal()."'
						$q_in_con
						UNION
						SELECT COUNT(A.NO_CONTAINER) TOTAL
						FROM CON_LISTCONT A
						WHERE A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_OP_STATUS IN ('REC') AND A.ID_CLASS_CODE='E' AND A.TL_FLAG='N' AND ITT_FLAG = 'Y' AND A.ID_TERMINAL = '".$this->gtools->terminal()."'
						$q_in_con
						";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		$qPaging = '';
		
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_OP_STATUS IN ('BPL','SDG','YSY')  AND A.ID_CLASS_CODE='I' AND A.TL_FLAG='N' AND (A.NO_REQUEST IS NULL OR A.NO_REQUEST='') AND ITT_FLAG = 'Y'
						$q_in_con";
		$qWhereI = "WHERE 1=1 AND A.ID_VES_VOYAGE='".$id_ves_voyage."' AND A.ID_OP_STATUS IN ('REC')  AND A.ID_CLASS_CODE='E' AND A.TL_FLAG='N' AND ITT_FLAG = 'Y'
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
													A.CONT_STATUS,
													A.OP_STATUS_DESC
										FROM 
											CON_LISTCONT A
										$qWhere AND  A.ID_TERMINAL = '".$this->gtools->terminal()."'
										UNION
										SELECT A.NO_CONTAINER,
													A.POINT,
													A.ID_ISO_CODE,
													A.ID_CLASS_CODE,
													A.ID_OPERATOR,
													A.CONT_STATUS,
													A.OP_STATUS_DESC
										FROM 
											CON_LISTCONT A
										$qWhereI AND  A.ID_TERMINAL = '".$this->gtools->terminal()."'
										) V
							) B
						$qPaging";
		// print $query;
//		echo '<pre>'.$query.'</pre>';exit;
		$rs = $this->db->query($query);
		$container_list = $rs->result_array();
		$data = array (
			'total'=>$total,
			'data'=>$container_list
		);
		
		return $data;
	}
	
	public function save_cancel_extended_storage_yard($data,$id_user,$id_ves_voyage){
		$out = '';
		$msg_out = '';
		$msg_complete = '';
		$container_data = json_decode($data['container_data']);
		
		if ($msg_out==''){
			for ($i=0;$i<sizeof($container_data);$i++){
				$no_container = $container_data[$i]->NO_CONTAINER;
				$point = $container_data[$i]->POINT;
				$id_class_code = $container_data[$i]->ID_CLASS_CODE;
									
				if($id_class_code == 'I'){
					$param = array(
						array('name'=>':v_nocont', 'value'=>$no_container, 'length'=>30),
						array('name'=>':v_point', 'value'=>$point, 'length'=>10),
						array('name'=>':v_idvesvoyage', 'value'=>$id_ves_voyage, 'length'=>50),
						array('name'=>':v_user', 'value'=>$id_user, 'length'=>50),
						array('name'=>':v_terminal', 'value'=>$this->gtools->terminal(), 'length'=>50),
						array('name'=>':v_out', 'value'=>&$out, 'length'=>100),
						array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>100)
					);
					$this->db->trans_start();
					$query = "begin PROC_SAVECANCELITT(:v_nocont,:v_point,:v_idvesvoyage,:v_user,:v_terminal,:v_out,:v_msg_out); end;";

//			        debux($param); die();
					$this->db->exec_bind_stored_procedure($query, $param);
					if ($this->db->trans_complete())
					{
						if($out!='OK')
						{
							$msg_complete .= $msg_out.', ';
						}else{						
							$this->load->library('nusoap_lib');
							$client = new nusoap_client($this->config->item('link_nusoap_client'));
							
							$error = $client->getError();
							if ($error) {
							   $updateCancelITT = 'NO';
							}
							$result = $client->call("updateStatusCancelITT", array("id_ves_voyage" => "$id_ves_voyage", 
																				"no_container" => "$no_container",
																				"point" => "$point"));
							if ($client->fault) {
							   $updateCancelITT = 'NO';
							}
							else {
							   $error = $client->getError();
							   if ($error) {
								   $updateCancelITT = 'NO';
							   }
							   else {
								   $updateCancelITT = $result;
							   }		 
							}
							IF($updateCancelITT != 'NO')
							{
								$get_job_slip_array = explode("-",$updateCancelITT);
								$updateCancelITT = $get_job_slip_array[0];
								IF($updateCancelITT != 'OK'){
									$v_out = 'NOT OK';
									$msg_complete .= $get_job_slip_array[1].', ';				
								}
							}
						}
					}
				}else{
					$param = array(
						array('name'=>':v_nocont', 'value'=>$no_container, 'length'=>30),
						array('name'=>':v_point', 'value'=>$point, 'length'=>10),
						array('name'=>':v_idvesvoyage', 'value'=>$id_ves_voyage, 'length'=>50),
						array('name'=>':v_user', 'value'=>$id_user, 'length'=>50),
						array('name'=>':v_out', 'value'=>&$out, 'length'=>100),
						array('name'=>':v_msg_out', 'value'=>&$msg_out, 'length'=>100)
					);
					$this->db->trans_start();
					$query = "begin Proc_SaveCancelESY(:v_nocont,:v_point,:v_idvesvoyage,:v_user,:v_out,:v_msg_out); end;";

			//        var_dump($param); die();
					$this->db->exec_bind_stored_procedure($query, $param);
					if ($this->db->trans_complete())
					{
						if($out!='OK')
						{
							$msg_complete .= $msg_out.', ';
						}else{						
							$this->load->library('nusoap_lib');
							$client = new nusoap_client("http://intranet.indonesiaport.co.id/esy_muat/gate_out_lini2.php");
						
							$error = $client->getError();
							if ($error) {
							   $updateCancelITT = 'NO';
							}
							$result = $client->call("CancelESY", array("id_ves_voyage" => "$id_ves_voyage", 
																				"no_container" => "$no_container",
																				"point" => "$point"));
							if ($client->fault) {
							   $updateCancelITT = 'NO';
							}
							else {
							   $error = $client->getError();
							   if ($error) {
								   $updateCancelITT = 'NO';
							   }
							   else {
								   $updateCancelITT = $result;
							   }		 
							}
							IF($updateCancelITT != 'NO')
							{
								$get_job_slip_array = explode("-",$updateCancelITT);
								$updateCancelITT = $get_job_slip_array[0];
								IF($updateCancelITT != 'OK'){
									$v_out = 'NOT OK';
									$msg_complete .= $get_job_slip_array[1].', ';				
								}
							}
						}
					}
					if($msg_complete != ''){
						$query 	= "UPDATE CON_LISTCONT SET ITT_FLAG = 'Y', TL_FLAG = 'N' WHERE NO_CONTAINER = '$no_container' AND POINT = '$point' AND ID_VES_VOYAGE = '$id_ves_voyage' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
						$this->db->query($query);
						$query 	= "UPDATE CON_ESY_D SET CANCEL_ESY = 'N', CANCEL_USR = NULL WHERE NO_CONTAINER = '$no_container' AND POINT = '$point' AND ID_VES_VOYAGE = '$id_ves_voyage'";
						$this->db->query($query);
					}					
				}
			}
		}
		if($msg_complete == ''){
			return array(
						'success'=>true,
						'errors'=> 'Save success'
					);
		}else{
			return array(
						'success'=>false,
						'errors'=> $msg_complete
					);
		}
	}		

	public function get_data_cancel_transhipment_container($id_ves_voyage, $container_list=false, $paging=false, $sort=false, $filters=false){
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}
		
		$query = "SELECT H.ID_TRANSHIPMENT,D.NO_CONTAINER,D.POINT,D.LOAD_POINT,D.ID_ISO_CODE,D.CONT_STATUS,D.CONT_HEIGHT,H.OLD_ID_VES_VOYAGE,H.ID_VES_VOYAGE
			    ,CASE WHEN C.ACTIVE = 'Y' THEN C.ID_OP_STATUS ELSE CL.ID_OP_STATUS END ID_OP_STATUS
			    ,CASE WHEN C.ACTIVE = 'Y' THEN C.OP_STATUS_DESC ELSE CL.OP_STATUS_DESC END  OP_STATUS_DESC
			    ,CASE WHEN C.ACTIVE = 'Y' THEN C.ID_OP_STATUS || '-' || C.OP_STATUS_DESC ELSE CL.ID_OP_STATUS || '-' || CL.OP_STATUS_DESC END OP_STATUS,C.ID_CLASS_CODE,C.ID_OPERATOR 
			    FROM CON_TRANSHIPMENT_D D
			    LEFT JOIN CON_TRANSHIPMENT_H H
			      ON D.ID_TRANSHIPMENT = H.ID_TRANSHIPMENT AND D.ID_TERMINAL = H.ID_TERMINAL
			    LEFT JOIN CON_LISTCONT C
			      ON D.NO_CONTAINER = C.NO_CONTAINER AND D.POINT = C.POINT AND D.ID_TERMINAL = C.ID_TERMINAL --AND C.ACTIVE = 'N'
			    LEFT JOIN CON_LISTCONT CL
			      ON D.NO_CONTAINER = CL.NO_CONTAINER AND D.LOAD_POINT = CL.POINT AND D.ID_TERMINAL = CL.ID_TERMINAL AND CL.ACTIVE = 'Y'
		          WHERE H.OLD_ID_VES_VOYAGE = '$id_ves_voyage' AND D.NO_CONTAINER IN ($container_list) AND CL.ID_OP_STATUS NOT IN ('OYS','SLY','DIS')
				      $qSort";
		 //print $query;
		$rs = $this->db->query($query);
		$data = array (
			'total'=>0,
			'data'=>$rs->result_array()
		);
		
		return $data;
	}

	public function save_cancel_transhipment_container($data,$id_user,$id_ves_voyage){
		$container_data = json_decode($data['container_data']);
//		echo '<pre>';print_r($container_data);echo '</pre>';
		$this->db->trans_start();
		for ($i=0;$i<sizeof($container_data);$i++){
			$id_transhipment = $container_data[$i]->ID_TRANSHIPMENT;
			$no_container = $container_data[$i]->NO_CONTAINER;
			$point = $container_data[$i]->POINT;
			$load_point = $container_data[$i]->LOAD_POINT;
			$id_class_code = $container_data[$i]->ID_CLASS_CODE;
			$old_id_vesvoyage = $container_data[$i]->OLD_ID_VES_VOYAGE;
			$id_vesvoyage = $container_data[$i]->ID_VES_VOYAGE;
			$id_op_status = $container_data[$i]->ID_OP_STATUS;
			
			// check second point active status
			$qrycek = "SELECT ACTIVE,ID_OP_STATUS,YD_YARD,YD_BLOCK,YD_BLOCK_NAME,YD_SLOT,YD_ROW,YD_TIER FROM CON_LISTCONT WHERE NO_CONTAINER = '$no_container' AND POINT = '$load_point' AND ID_VES_VOYAGE = '$id_vesvoyage' AND ID_TERMINAL = ".$this->gtools->terminal();
			$rs_cek = $this->db->query($qrycek)->row_array();
			
			if($rs_cek['ACTIVE'] == 'Y' && ($rs_cek['ID_OP_STATUS'] != 'DIS' && $rs_cek['ID_OP_STATUS'] != 'OYS')){
			    $ins_log_cancel = "INSERT INTO ITOS_OP.LOG_CANCEL_TRANSHIPMENT (ID_TRANSHIPMENT, OLD_ID_VES_VOYAGE, ID_VES_VOYAGE, VIA_GATE, DOC_NUMBER, USER_ENTRY, DATE_ENTRY, ID_TERMINAL, NO_CONTAINER, POINT, LOAD_POINT, DISCHARGE_DATE, PLACEMENT_DATE, LOAD_DATE, ID_ISO_CODE, CONT_STATUS, CONT_HEIGHT, USER_CANCEL, DATE_CANCEL)
						SELECT H.ID_TRANSHIPMENT,H.OLD_ID_VES_VOYAGE,H.ID_VES_VOYAGE,H.VIA_GATE,H.DOC_NUMBER,H.USER_ENTRY,H.DATE_ENTRY,H.ID_TERMINAL,D.NO_CONTAINER,D.POINT,D.LOAD_POINT,D.DISCHARGE_DATE,D.PLACEMENT_DATE,D.LOAD_DATE,D.ID_ISO_CODE,D.CONT_STATUS,D.CONT_HEIGHT,'".$id_user."',SYSDATE
						FROM CON_TRANSHIPMENT_D D
						LEFT JOIN CON_TRANSHIPMENT_H H
						  ON D.ID_TRANSHIPMENT = H.ID_TRANSHIPMENT AND D.ID_TERMINAL = H.ID_TERMINAL
						WHERE D.ID_TRANSHIPMENT = $id_transhipment AND D.NO_CONTAINER = '$no_container' AND H.OLD_ID_VES_VOYAGE = '$old_id_vesvoyage'";
			    $this->db->query($ins_log_cancel);
			    
			    $qry_cek_status = "SELECT * FROM CON_LISTCONT WHERE NO_CONTAINER = '$no_container' AND POINT = '$point' AND ID_VES_VOYAGE = '$old_id_vesvoyage' AND ID_TERMINAL = ".$this->gtools->terminal();
			    $rs_cek_status = $this->db->query($qry_cek_status)->row_array();
			    $op_status = $rs_cek_status['ID_OP_STATUS'] == 'TRS' ? $rs_cek['ID_OP_STATUS'] : $rs_cek_status['ID_OP_STATUS'];
			    $op_status_desc = $rs_cek_status['ID_OP_STATUS'] == 'TRS' ? $rs_cek['OP_STATUS_DESC'] : $rs_cek_status['OP_STATUS_DESC'];
			    $qry_update1 = "UPDATE CON_LISTCONT SET ACTIVE = 'Y',ID_CLASS_CODE = 'I',ID_OP_STATUS = '$op_status',OP_STATUS_DESC = '$op_status_desc',YD_YARD = '".$rs_cek['YD_YARD']."',YD_BLOCK = '".$rs_cek['YD_BLOCK']."',YD_BLOCK_NAME = '".$rs_cek['YD_BLOCK_NAME']."',YD_SLOT = '".$rs_cek['YD_SLOT']."',YD_ROW = '".$rs_cek['YD_ROW']."',YD_TIER = '".$rs_cek['YD_TIER']."' WHERE NO_CONTAINER = '$no_container' AND POINT = '$point' AND ID_VES_VOYAGE = '$old_id_vesvoyage' AND ID_TERMINAL = ".$this->gtools->terminal();
			    $this->db->query($qry_update1);
			    
			    $qry_update_repo = "UPDATE ITOS_REPO.M_CYC_CONTAINER SET CONT_LOCATION='Yard', YD_BLOCK = '".$rs_cek['YD_BLOCK']."',YD_SLOT = '".$rs_cek['YD_SLOT']."',YD_ROW = '".$rs_cek['YD_ROW']."',YD_TIER = '".$rs_cek['YD_TIER']."',E_I='I' WHERE NO_CONTAINER = '$no_container' AND POINT = '$point'";
			    $this->db->query($qry_update_repo);
			}
			
			$delnewpoint = "DELETE FROM CON_LISTCONT WHERE NO_CONTAINER = '$no_container' AND POINT = '$load_point' AND ID_VES_VOYAGE = '$id_vesvoyage' AND ID_TERMINAL = ".$this->gtools->terminal();
			$this->db->query($delnewpoint);
			
			$delnewpoint_repo = "DELETE FROM ITOS_REPO.M_CYC_CONTAINER WHERE NO_CONTAINER = '$no_container' AND POINT = '$load_point'";
			$this->db->query($delnewpoint_repo);
			
			$deldetail_trans = "DELETE FROM CON_TRANSHIPMENT_D WHERE ID_TRANSHIPMENT = '$id_transhipment' AND NO_CONTAINER = '$no_container' AND POINT = '$point' AND ID_TERMINAL = ".$this->gtools->terminal();
			$this->db->query($deldetail_trans);
			
			$update_jobplacement = "UPDATE JOB_PLACEMENT SET POINT = '$point', ID_VES_VOYAGE = '$old_id_vesvoyage' WHERE NO_CONTAINER = '$no_container' AND POINT = '$load_point' AND ID_TERMINAL = ".$this->gtools->terminal();
			$this->db->query($update_jobplacement);
		}
		
//		exit;
		if ($this->db->trans_complete())
		{
			return array(
				'success'=>true,
				'errors'=> 'Save success'
			);
		}else{
			return array(
						'success'=>false,
						'errors'=> $msg_complete
					);
		}
	}	
}
?>