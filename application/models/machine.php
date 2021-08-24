<?php
class Machine extends CI_Model {
	public function __construct(){
		$this->load->database();
		$this->load->model('gtools');
	}
	
	public function get_all_machines(){
	    return $this->db->get('M_MACHINE')->result_array();
	}
	
	public function get_data_machine($mch_type){
		$param 		= array($mch_type,$this->gtools->terminal());
		$query 		= "SELECT * FROM M_MACHINE WHERE MCH_TYPE=? AND ID_TERMINAL=? AND ID_MACHINE > 0 ORDER BY ID_MACHINE";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_data_machine_quay($mch_type,$id_class_code){
		$param 		= array($mch_type,$this->gtools->terminal());
		if($id_class_code == 'I')
		{
			$qwhere="
					AND A.ID_MACHINE NOT IN (
					SELECT
						ID_MACHINE_ITV AS ID_MACHINE 
					FROM
						(
						SELECT
							SIZE_CHASSIS - SUM( CONT_SIZE ) AS S,
							ID_MACHINE_ITV 
						FROM
							(
							SELECT
								A.NO_CONTAINER,
								C.ID_CLASS_CODE,
								C.CONT_SIZE,
								M.SIZE_CHASSIS,
								A.ID_MACHINE_ITV 
							FROM
								JOB_YARD_MANAGER A
								INNER JOIN JOB_QUAY_MANAGER B ON A.NO_CONTAINER = B.NO_CONTAINER
								INNER JOIN CON_LISTCONT C ON A.NO_CONTAINER = C.NO_CONTAINER AND A.POINT = C.POINT
								LEFT JOIN M_MACHINE M ON A.ID_MACHINE_ITV = M.ID_MACHINE 
							WHERE
								A.STATUS_FLAG = 'P' 
								AND A.ID_MACHINE_ITV IS NOT NULL 
							GROUP BY
								A.NO_CONTAINER,
								C.ID_CLASS_CODE,
								C.CONT_SIZE,
								M.SIZE_CHASSIS,
								A.ID_MACHINE_ITV 
							) 
						GROUP BY
							SIZE_CHASSIS,
							ID_MACHINE_ITV 
						) 
					WHERE
						S <= 5
					) ";
		}else{
			$qwhere="
					AND A.ID_MACHINE NOT IN (
					SELECT
						ID_MACHINE_ITV AS ID_MACHINE 
					FROM
						(
						SELECT
							SIZE_CHASSIS - SUM( CONT_SIZE ) AS S,
							ID_MACHINE_ITV 
						FROM
							(
							SELECT
								A.NO_CONTAINER,
								B.ID_CLASS_CODE,
								B.CONT_SIZE,
								M.SIZE_CHASSIS,
								A.ID_MACHINE_ITV 
							FROM
								JOB_QUAY_MANAGER A
								LEFT JOIN CON_LISTCONT B ON  A.NO_CONTAINER = B.NO_CONTAINER AND A.POINT = B.POINT
								LEFT JOIN M_MACHINE M ON A.ID_MACHINE_ITV = M.ID_MACHINE 
							WHERE
								A.STATUS_FLAG = 'P' 
								AND A.ID_MACHINE_ITV IS NOT NULL 
							GROUP BY
								A.NO_CONTAINER,
								B.ID_CLASS_CODE,
								B.CONT_SIZE,
								M.SIZE_CHASSIS,
								A.ID_MACHINE_ITV 
							) 
						GROUP BY
							SIZE_CHASSIS,
							ID_MACHINE_ITV
						) 
					WHERE
						S <= 5
					) ";
		}
		$query 		= "SELECT
							A.ID_MACHINE,
							A.MCH_NAME 
						FROM
							M_MACHINE A
						WHERE A.MCH_TYPE='$mch_type' AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND A.ID_MACHINE > 0
						$qwhere
					GROUP BY
						A.ID_MACHINE,
						A.MCH_NAME
					ORDER BY
						A.MCH_NAME";
		// debux($query);
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_data_machine_itv_pool_quay($mch_type,$id_pool,$id_class_code){
		$param 		= array($mch_type,$this->gtools->terminal(),$id_pool);
		if($id_class_code == 'I')
		{
			$qwhere="
					AND A.ID_MACHINE NOT IN (
					SELECT
						ID_MACHINE_ITV AS ID_MACHINE 
					FROM
						(
						SELECT
							SIZE_CHASSIS - SUM( CONT_SIZE ) AS S,
							ID_MACHINE_ITV 
						FROM
							(
							SELECT
								A.NO_CONTAINER,
								C.ID_CLASS_CODE,
								C.CONT_SIZE,
								M.SIZE_CHASSIS,
								A.ID_MACHINE_ITV 
							FROM
								JOB_YARD_MANAGER A
								INNER JOIN JOB_QUAY_MANAGER B ON A.NO_CONTAINER = B.NO_CONTAINER
								INNER JOIN CON_LISTCONT C ON A.NO_CONTAINER = C.NO_CONTAINER AND A.POINT = C.POINT
								LEFT JOIN M_MACHINE M ON A.ID_MACHINE_ITV = M.ID_MACHINE 
							WHERE
								A.STATUS_FLAG = 'P' 
								AND A.ID_MACHINE_ITV IS NOT NULL 
							GROUP BY
								A.NO_CONTAINER,
								C.ID_CLASS_CODE,
								C.CONT_SIZE,
								M.SIZE_CHASSIS,
								A.ID_MACHINE_ITV 
							) 
						GROUP BY
							SIZE_CHASSIS,
							ID_MACHINE_ITV 
						) 
					WHERE
						S <= 5 
					) ";
		}else{
			$qwhere="
					AND A.ID_MACHINE NOT IN (
					SELECT
						ID_MACHINE_ITV AS ID_MACHINE 
					FROM
						(
						SELECT
							SIZE_CHASSIS - SUM( CONT_SIZE ) AS S,
							ID_MACHINE_ITV 
						FROM
							(
							SELECT
								A.NO_CONTAINER,
								B.ID_CLASS_CODE,
								B.CONT_SIZE,
								M.SIZE_CHASSIS,
								A.ID_MACHINE_ITV 
							FROM
								JOB_QUAY_MANAGER A
								LEFT JOIN CON_LISTCONT B ON A.NO_CONTAINER = B.NO_CONTAINER AND A.POINT = B.POINT
								LEFT JOIN M_MACHINE M ON A.ID_MACHINE_ITV = M.ID_MACHINE 
							WHERE
								A.STATUS_FLAG = 'P' 
								AND A.ID_MACHINE_ITV IS NOT NULL 
							GROUP BY
								A.NO_CONTAINER,
								B.ID_CLASS_CODE,
								B.CONT_SIZE,
								M.SIZE_CHASSIS,
								A.ID_MACHINE_ITV 
							) 
						GROUP BY
							SIZE_CHASSIS,
							ID_MACHINE_ITV
						) 
					WHERE
						S <= 5
					) ";
		}
		$query 		= "SELECT
							A.ID_MACHINE,
							A.MCH_NAME 
						FROM
							M_MACHINE A
						WHERE A.MCH_TYPE='$mch_type' AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND A.ID_POOL='$id_pool' AND A.ID_MACHINE > 0
						$qwhere
					GROUP BY
						A.ID_MACHINE,
						A.MCH_NAME
					ORDER BY
						A.MCH_NAME";
		// debux($query);
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_data_machine_from_voyage($id_ves_voyage){
		$query = "SELECT DISTINCT c.ID_MACHINE, c.MCH_NAME
				    FROM mch_working_plan a
					INNER JOIN mch_working_sequence b ON a.id_mch_working_plan = b.id_mch_working_plan
					INNER JOIN m_machine c ON a.id_machine = c.id_machine
			    	WHERE b.ACTIVE = 'Y' AND a.ID_VES_VOYAGE='$id_ves_voyage' AND c.ID_TERMINAL='".$this->gtools->terminal()."' AND a.id_machine <> -1 
					ORDER BY MCH_NAME";

		$rs			= $this->db->query($query);
		$data 		= $rs->result_array();
		return $data;
	}

	public function validation_data_itv($id_class_code,$itv)
	{
		if($id_class_code == 'I')
		{
			$query = "SELECT
						A.*,
						C.ID_CLASS_CODE
					FROM
						JOB_YARD_MANAGER A
					INNER JOIN 
						JOB_QUAY_MANAGER B 
					ON 
						A.NO_CONTAINER = B.NO_CONTAINER
					INNER JOIN 
						CON_LISTCONT C 
					ON 
						C.NO_CONTAINER = A.NO_CONTAINER
					WHERE
						A.ID_MACHINE_ITV = '$itv'
					AND 
						A.STATUS_FLAG = 'P'";	
		}
		else
		{
			$query = "SELECT
					A.*,
					B.ID_CLASS_CODE
				FROM
					JOB_QUAY_MANAGER A
				LEFT JOIN 
					CON_LISTCONT B 
				ON 
					A.NO_CONTAINER = B.NO_CONTAINER
				WHERE
					ID_MACHINE_ITV = '$itv'
				AND 
					STATUS_FLAG = 'P'
				ORDER BY
					SEQUENCE DESC";
		}

		$response = $this->db->query($query)->num_rows();

		//debux($query);

		if($response>0)
		{
			#ada isi
			return 'full';
		}
		else
		{
			#kosong
			return 'empty';
		}
	}

	public function validation_data_itv_class($id_class_code,$itv,$cont)
	{
		if($id_class_code == 'I')
		{
			$query = "SELECT
						( SIZE_CHASSIS - SUM( CONT_SIZE ) ) - NEWSIZE AS S,
						ID_MACHINE_ITV 
					FROM
						(
						SELECT
							A.NO_CONTAINER,
							C.ID_CLASS_CODE,
							C.CONT_SIZE,
							CASE
								WHEN D.CONT_SIZE IS NULL THEN
								'0' ELSE D.CONT_SIZE 
							END AS NEWSIZE,
							M.SIZE_CHASSIS,
							A.ID_MACHINE_ITV 
						FROM
							JOB_YARD_MANAGER A
							INNER JOIN JOB_QUAY_MANAGER B ON A.NO_CONTAINER = B.NO_CONTAINER
							INNER JOIN CON_LISTCONT C ON A.NO_CONTAINER = C.NO_CONTAINER AND A.POINT = C.POINT
							LEFT JOIN CON_LISTCONT D ON D.NO_CONTAINER = '$cont'
							AND A.NO_CONTAINER != D.NO_CONTAINER 
							AND A.POINT != D.POINT
							LEFT JOIN M_MACHINE M ON A.ID_MACHINE_ITV = M.ID_MACHINE 
						WHERE
							A.STATUS_FLAG = 'P' 
							AND A.ID_MACHINE_ITV = '$itv'
						GROUP BY
							A.NO_CONTAINER,
							C.ID_CLASS_CODE,
							C.CONT_SIZE,
							D.CONT_SIZE,
							M.SIZE_CHASSIS,
							A.ID_MACHINE_ITV 
						) 
					GROUP BY
						SIZE_CHASSIS,
						NEWSIZE,
						ID_MACHINE_ITV 
						";	
		}
		else
		{
			$query = "SELECT
						( SIZE_CHASSIS - SUM( CONT_SIZE ) ) - NEWSIZE AS S,
						ID_MACHINE_ITV 
					FROM
						(
						SELECT
							A.NO_CONTAINER,
							B.ID_CLASS_CODE,
							B.CONT_SIZE,
							CASE
								WHEN C.CONT_SIZE IS NULL THEN
								'0' ELSE C.CONT_SIZE 
							END AS NEWSIZE,
							M.SIZE_CHASSIS,
							A.ID_MACHINE_ITV 
						FROM
							JOB_QUAY_MANAGER A
							LEFT JOIN CON_LISTCONT B ON A.NO_CONTAINER = B.NO_CONTAINER AND B.POINT = A.POINT
							LEFT JOIN CON_LISTCONT C ON C.NO_CONTAINER = '$cont' AND C.POINT != B.POINT
							LEFT JOIN M_MACHINE M ON A.ID_MACHINE_ITV = M.ID_MACHINE 
						WHERE
							A.STATUS_FLAG = 'P' 
							AND A.ID_MACHINE_ITV = '$itv'
						GROUP BY
							A.NO_CONTAINER,
							B.ID_CLASS_CODE,
							B.CONT_SIZE,
							C.CONT_SIZE,
							M.SIZE_CHASSIS,
							A.ID_MACHINE_ITV 
						) 
					GROUP BY
						SIZE_CHASSIS,
						NEWSIZE,
						ID_MACHINE_ITV
						";
		}

		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row_array();
		$num_rows 		= $rs->num_rows();

		// debux($query);die();

		if($num_rows>0 and strpos($data['S'], '-') !== false)
		{
			#ada isi
			return 'full';
		}
		else
		{
			#kosong
			return 'empty';
		}
	}
	
	public function get_data_machine_yard_quay()
	{
		$query 		= "SELECT 
						ID_MACHINE, MCH_NAME, MCH_TYPE 
					FROM 
						M_MACHINE 
					WHERE 
						MCH_TYPE IN ('QUAY', 'YARD','ITV') 
					AND 
						ID_MACHINE > 0 
					AND 
						ID_TERMINAL = '".$this->gtools->terminal()."' 
					ORDER BY 
						ID_MACHINE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_data_operator(){
		$query 		= "SELECT mu.ID_USER,mu.FULL_NAME FROM m_users mu
					LEFT JOIN M_USER_TERMINAL MUT ON MU.ID_USER = MUT.ID_USER
					WHERE mut.ID_TERMINAL='".$this->gtools->terminal()."' ORDER BY mu.FULL_NAME";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_etd_vvd($idvvd)
	{
		$param 		= array($mch_type,$this->gtools->terminal);
		$query 		= "SELECT to_char(ETD,'DD-MM-YYYY') DETD, 
						to_char(ETD,'HH24') HETD,
						to_char(ETD,'Mi') MINETD
						FROM ves_voyage WHERE id_ves_voyage=? AND ID_TERMINAL=?";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row_array();
		
		return $data;
	}

	public function save_machine_vesvoy($data,$id_ves_voyage,$id_user){
		$str_param = $id_ves_voyage."^".$data['start_work']."^".$data['end_work']."^".$data['mch_id']."^".$id_user;
		$param = array(
			array('name'=>':v_parameter', 'value'=>$str_param, 'length'=>200),
			array('name'=>':v_msg', 'value'=>&$msg, 'length'=>100)
		);
		
		$sql = "BEGIN ITOS_OP.proc_add_machine_vesvoy(:v_parameter,:v_msg); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		
		return $msg;
	}
	
	public function update_machine_vesvoy($data,$id_ves_voyage,$id_user){
		$str_param = $id_ves_voyage."^".$data['start_work']."^".$data['end_work']."^".$data['mch_id']."^".$id_user."^U";
		$param = array(
			array('name'=>':v_parameter', 'value'=>$str_param, 'length'=>200),
			array('name'=>':v_msg', 'value'=>&$mch_vv, 'length'=>100)
		);
		#print_r($param);die;
		
		$sql = "BEGIN ITOS_OP.proc_update_machine_vesvoy(:v_parameter, :v_msg); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		
		return "OK";
	}

	public function save_machine_and_seq_vesvoy($data,$id_ves_voyage,$id_user){
		$str_param = $id_ves_voyage."^".$data['start_work']."^".$data['end_work']."^".$data['mch_id']."^".$id_user."^".$data['bch'];
		$param = array(
			array('name'=>':v_parameter', 'value'=>$str_param, 'length'=>200),
			array('name'=>':v_msg', 'value'=>&$msg, 'length'=>100)
		);
		
		$sql = "BEGIN ITOS_OP.PROC_ADD_MACHINE_AND_SEQ(:v_parameter,:v_msg); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);		
		
		return $msg;
	}
	
	public function deleteSequenceCwp($v_idmchwkplan,$v_seq,$v_bay,$v_act,$v_deck,$id_vsbvoy)
	{

		$v_act = ($v_act=='D') ? 'I' : 'E';
//		$ws_deck = ($v_deck == 'DE' || $v_deck == 'D') ? 'D' : 'H';
		
//		$this->db->query("DELETE mch_working_sequence 
//						 where id_mch_working_plan='$v_idmchwkplan' 
//						 and sequence='$v_seq' 
//						 and bay='$v_seq' 
//						 and deck_hatch='$ws_deck' 
//						 and activity='$v_act'");
//		echo '<pre>'. $this->db->last_query().'</pre>';
		//$this->db->affected_rows()	
		$param = array(
			array('name'=>':v_idmchwkplan', 'value'=>$v_idmchwkplan, 'length'=>22),
			array('name'=>':v_seq', 'value'=>$v_seq, 'length'=>10),
			array('name'=>':v_bay', 'value'=>$v_bay, 'length'=>10),
			array('name'=>':v_act', 'value'=>$v_act, 'length'=>2),
			array('name'=>':v_deck', 'value'=>$v_deck, 'length'=>2),
			array('name'=>':id_vsbvoy', 'value'=>$id_vsbvoy, 'length'=>15),
			array('name' => ':out_message', 'value' => &$msg, 'length' => 100)
		);
//		 print_r($param);die;
		$sql = "BEGIN ITOS_OP.proc_del_cwp_assign(:v_idmchwkplan,:v_seq,:v_bay,:v_act,:v_deck,:id_vsbvoy,:out_message); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		
		return $msg;
	}
	
	public function insert_plan_yard_equipment($id_yard, $xml_str){
		$xml = simplexml_load_string($xml_str);
		
		$block = $xml->block;
		$block_id = $block->block_id;
		$id_machine = $xml->id_machine;
		$index = $block->index;
		$index_arr = explode(",",$index);
		
		$query = "SELECT MAX(ID_MCH_PLAN) AS MAX_ID FROM MCH_PLAN_GROUP";
		$rs 		= $this->db->query($query);
		$row 		= $rs->row_array();
		$id = 1;
		if ($row['MAX_ID']){
			$id = $row['MAX_ID']+1;
		}
		
		$max_slot=0;
		$min_slot=0;
		$max_row=0;
		$min_row=0;
		
		foreach($index_arr as $cell){
			$query = "SELECT SLOT_,ROW_ FROM M_YARDBLOCK_CELL
						WHERE ID_YARD='$id_yard' AND ID_BLOCK='$block_id' AND INDEX_CELL='$cell'
						GROUP BY SLOT_,ROW_";
			$rs = $this->db->query($query);
			$data_slot_row = $rs->result_array();
			
			if ($min_slot==0){
				$min_slot = $data_slot_row[0]['SLOT_'];
				$max_slot = $data_slot_row[0]['SLOT_'];
				$min_row = $data_slot_row[0]['ROW_'];
				$max_row = $data_slot_row[0]['ROW_'];
			}else{
				if ($data_slot_row[0]['SLOT_']>$max_slot){
					$max_slot = $data_slot_row[0]['SLOT_'];
				}else if ($data_slot_row[0]['SLOT_']<$min_slot){
					$min_slot = $data_slot_row[0]['SLOT_'];
				}
				if ($data_slot_row[0]['ROW_']>$max_row){
					$max_row = $data_slot_row[0]['ROW_'];
				}else if ($data_slot_row[0]['ROW_']<$min_row){
					$min_row = $data_slot_row[0]['ROW_'];
				}
			}
			
			$query_plan_cell = "INSERT INTO MCH_PLAN(ID_YARD, ID_BLOCK, INDEX_CELL, SLOT_, ROW_, ID_MACHINE, ID_MCH_PLAN)
			VALUES('$id_yard', '$block_id', '$cell', '".$data_slot_row[0]['SLOT_']."', '".$data_slot_row[0]['ROW_']."', $id_machine, '$id')";
			$this->db->query($query_plan_cell);
		}
		
		$query 	= "INSERT INTO MCH_PLAN_GROUP
					(ID_MCH_PLAN, ID_YARD, ID_BLOCK, START_SLOT, END_SLOT, START_ROW, END_ROW, ID_MACHINE) VALUES('$id', '$id_yard', '$block_id', '$min_slot', '$max_slot', '$min_row', '$max_row', '$id_machine')";
		$rs 	= $this->db->query($query);
		
		return 1;
	}
	
	public function get_yard_equipment_group($filters){
		$qWhere = '';
		if ($filters != false){
			$qs = '';
			$encoded = true;
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
				if($field != '' && $value != ''){
				    if($field == 'ID_MACHINE'){
					$field = 'A.'.$field;
				    }
				    if($qs != '' ) $qs .= " AND ";
				    switch($filterType){
					    case 'string' : $qs .= $field." LIKE '%".strtoupper($value)."%'"; Break;
					    case 'list' :
						    if (strstr($value,',')){
							    $fi = explode(',',$value);
							    for ($q=0;$q<count($fi);$q++){
								    $fi[$q] = "'".$fi[$q]."'";
							    }
							    $value = implode(',',$fi);
							    $qs .= $field." IN (".strtoupper($value).")";
						    }else{
							    $qs .= $field." = '".strtoupper($value)."'";
						    }
					    Break;
					    case 'boolean' : $qs .= $field." = ".($value); Break;
					    case 'numeric' :
						    switch ($compare) {
							    case 'eq' : $qs .= $field." = ".$value; Break;
							    case 'lt' : $qs .= $field." < ".$value; Break;
							    case 'gt' : $qs .= $field." > ".$value; Break;
						    }
					    Break;
					    case 'date' :
						    switch ($compare) {
							    case 'eq' : $qs .= $field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							    case 'lt' : $qs .= $field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							    case 'gt' : $qs .= $field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						    }
					    Break;
				    }
				}
			}
			if($qs != '') $qWhere = 'WHERE '.$qs;
//			echo '<pre>';print_r($qWhere);echo '</pre>';exit;
		}
//		echo '<pre>session : ';print_r($this->session->userdata('terminal'));echo '</pre>';
		$query = "SELECT ID_MCH_PLAN, YARD_NAME, BLOCK_NAME, START_SLOT||'-'||END_SLOT AS SLOT_RANGE, START_ROW||'-'||END_ROW AS ROW_RANGE, MCH_NAME
			FROM MCH_PLAN_GROUP A
			INNER JOIN M_YARD B ON A.ID_YARD=B.ID_YARD 
			INNER JOIN M_YARDBLOCK C ON A.ID_BLOCK=C.ID_BLOCK
			INNER JOIN M_MACHINE D ON A.ID_MACHINE=D.ID_MACHINE 
			".$qWhere."
			ORDER BY ID_MCH_PLAN";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
//		echo '<pre>session : ';print_r($this->session->userdata('terminal'));echo '</pre>';
//		echo '<pre>';print_r($query);echo '</pre>';exit;
		return $data;
	}
	
	public function delete_yard_equipment_group($id_mch_plan){
		$query = "DELETE FROM MCH_PLAN WHERE ID_MCH_PLAN='$id_mch_plan'";
		$this->db->query($query);
		$query = "DELETE FROM MCH_PLAN_GROUP WHERE ID_MCH_PLAN='$id_mch_plan'";
		$this->db->query($query);
	}

	public function get_machine_cwp($filter,$id_vesvoy){
		$query 		= "SELECT ID_MACHINE, MCH_NAME FROM MCH_WORKING_PLAN
						WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy')
							AND LOWER(MCH_NAME) LIKE '%".strtolower($filter)."%'
						ORDER BY ID_MACHINE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_machine_dsc($id_vesvoy, $id_machine=NULL)
	{
		$where_m="";
		if(!empty($id_machine)){
			$where_m = " AND ID_MACHINE='$id_machine'";
		}
		$query 	= "SELECT (SELECT COUNT(*) 
		                       FROM CON_INBOUND_SEQUENCE A
						       INNER JOIN CON_LISTCONT B
						       	ON A.NO_CONTAINER = B.NO_CONTAINER AND A.POINT = B.POINT AND A.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND A.ID_TERMINAL = B.ID_TERMINAL
		                        WHERE TRIM(A.ID_VES_VOYAGE) = TRIM('$id_vesvoy') AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND ID_CLASS_CODE IN ('I','TI','S1','S2') AND B.ID_OP_STATUS <> 'DIS'
	                        	AND A.STATUS = 'C') SUM_COMPLETED,
		             (SELECT COUNT(*) 
					       FROM CON_INBOUND_SEQUENCE A
					       INNER JOIN CON_LISTCONT B
					       	ON A.NO_CONTAINER = B.NO_CONTAINER AND A.POINT = B.POINT AND A.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND A.ID_TERMINAL = B.ID_TERMINAL
					        WHERE TRIM(A.ID_VES_VOYAGE) = TRIM('$id_vesvoy') AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND ID_CLASS_CODE IN ('I','TI','S1','S2') AND B.ID_OP_STATUS <> 'DIS') SUM_PLANNED,
		             (SELECT COUNT (*)
		                       FROM CON_LISTCONT C
		                        WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy') AND ID_TERMINAL='".$this->gtools->terminal()."' AND ID_OP_STATUS <> 'DIS'
		                        AND ID_CLASS_CODE IN ('I','TI','S1','S2') AND VS_BAY IS NOT NULL
					AND CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
						THEN C.POINT ELSE 1 END = CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
					THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) ELSE 1 END) - (SELECT COUNT(*) 
		                       FROM CON_INBOUND_SEQUENCE A
						       INNER JOIN CON_LISTCONT B
						       	ON A.NO_CONTAINER = B.NO_CONTAINER AND A.POINT = B.POINT AND A.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND A.ID_TERMINAL = B.ID_TERMINAL
		                        WHERE TRIM(A.ID_VES_VOYAGE) = TRIM('$id_vesvoy') AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND ID_CLASS_CODE IN ('I','TI','S1','S2') AND B.ID_OP_STATUS <> 'DIS'
	                        	AND A.STATUS = 'C') SUM_REMAINED,
		              (SELECT COUNT(*) FROM (
					SELECT  fc_getcontentbay('$id_vesvoy', 'ABOVE', BAY,'I') AS TOTAL_PER_BAY
					FROM VES_VOYAGE_CWP
					WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy') AND ID_TERMINAL = '".$this->gtools->terminal()."'
					UNION ALL
					SELECT  fc_getcontentbay('$id_vesvoy', 'BELOW', BAY,'I') AS TOTAL_PER_BAY
					FROM VES_VOYAGE_CWP
					WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy') AND ID_TERMINAL = '".$this->gtools->terminal()."'
				) A WHERE TOTAL_PER_BAY > 0)-(SELECT COUNT(*) FROM (SELECT A.ID_MCH_WORKING_PLAN 
		                FROM MCH_WORKING_PLAN A, MCH_WORKING_SEQUENCE B 
		                WHERE TRIM(A.ID_VES_VOYAGE) = TRIM('$id_vesvoy')
		                AND B.ACTIVITY = 'I'
						AND B.BAY IS NOT NULL
		                AND A.ID_MCH_WORKING_PLAN = B.ID_MCH_WORKING_PLAN
		                $where_m)) QC_UNASSIGNED,
		              (SELECT COUNT (*)
		                       FROM CON_LISTCONT C
		                        WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy') AND ID_TERMINAL='".$this->gtools->terminal()."' AND ID_OP_STATUS <> 'DIS'
		                        AND ID_CLASS_CODE IN ('I','TI','S1','S2') AND VS_BAY IS NOT NULL
					AND CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
						THEN C.POINT ELSE 1 END = CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
					THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) ELSE 1 END) TOTAL                   
					FROM DUAL";
		//echo $query;
    //   debux($query);die;
		$rs 	= $this->db->query($query);
		$data 	= $rs->result_array();
		
		return $data;
	}

	public function get_machine_load($id_vesvoy, $id_machine=NULL)
	{
		$where_m="";
		if(!empty($id_machine)){
			$where_m = " AND ID_MACHINE='$id_machine'";
		}
		$query 	= "SELECT (SELECT COUNT(*) 
		                       FROM CON_OUTBOUND_SEQUENCE
		                        WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy')
		                        AND STATUS = 'C' AND ID_TERMINAL='".$this->gtools->terminal()."') SUM_COMPLETED,
		             (SELECT COUNT(*) 
		                       FROM CON_OUTBOUND_SEQUENCE
		                        WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy') AND ID_TERMINAL='".$this->gtools->terminal()."') SUM_PLANNED,
		             (SELECT COUNT(*) 
		                       FROM CON_OUTBOUND_SEQUENCE
		                        WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy') AND ID_TERMINAL='".$this->gtools->terminal()."')-(SELECT COUNT(*) 
		                       FROM CON_OUTBOUND_SEQUENCE
		                        WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy')
		                        AND STATUS = 'C' AND ID_TERMINAL='".$this->gtools->terminal()."') SUM_REMAINED,
		              (SELECT COUNT(*) FROM (
					SELECT  fc_getcontentbay('$id_vesvoy', 'ABOVE', BAY,'E') + fc_getcontentbay('$id_vesvoy', 'BELOW', BAY,'E') AS TOTAL_PER_BAY
					FROM VES_VOYAGE_CWP
					WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy') AND ID_TERMINAL = '".$this->gtools->terminal()."'
				) A WHERE TOTAL_PER_BAY > 0)-(SELECT COUNT(*) FROM (SELECT DISTINCT b.BAY 
		                FROM MCH_WORKING_PLAN A, MCH_WORKING_SEQUENCE B 
		                WHERE TRIM(A.ID_VES_VOYAGE) = TRIM('$id_vesvoy')
		                AND B.ACTIVITY = 'E'
						AND B.BAY IS NOT NULL
		                AND A.ID_MCH_WORKING_PLAN = B.ID_MCH_WORKING_PLAN)) QC_UNASSIGNED,
		              (SELECT COUNT(*)
                       	FROM CON_LISTCONT C
                       	JOIN VES_VOYAGE VV ON C.ID_VES_VOYAGE = VV.ID_VES_VOYAGE
                       	LEFT JOIN ITOS_BILLING.REQ_RECEIVING_D D ON D.NO_CONTAINER = C.NO_CONTAINER AND VV.VESSEL_NAME = D.VESSEL AND VV.VOY_IN = D.VOYAGE_IN AND VV.VOY_OUT = D.VOYAGE_OUT
						LEFT JOIN ITOS_BILLING.REQ_RECEIVING_H E ON E.ID_REQ = D.NO_REQ_ANNE
						JOIN CON_OUTBOUND_SEQUENCE F ON C.NO_CONTAINER = F.NO_CONTAINER AND C.ID_VES_VOYAGE = F.ID_VES_VOYAGE
                        WHERE TRIM(C.ID_VES_VOYAGE) = TRIM('$id_vesvoy') 
                        AND C.ID_OP_STATUS <> 'DIS'
                        AND C.ID_CLASS_CODE IN ('E','TE','S1','S2')
                       	AND (E.STATUS IN ('P','T') OR (CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
							    THEN C.POINT ELSE 1 END != CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
						    THEN (SELECT MAX(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) ELSE 0 END))
                       	AND C.ID_TERMINAL='".$this->gtools->terminal()."'
                       	AND F.ID_TERMINAL='".$this->gtools->terminal()."' $where_m) TOTAL                   
					FROM DUAL";
		//debux($query);die;
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}	

	public function data_machine_mst($filter,$id_vesvoy){
		$query 		= "SELECT ID_MACHINE, MCH_NAME FROM M_MACHINE 
						WHERE TRIM(MCH_TYPE) = 'QUAY' AND ID_TERMINAL='".$this->gtools->terminal()."' AND ID_MACHINE NOT IN (SELECT B.ID_MACHINE FROM MCH_WORKING_PLAN B
						WHERE TRIM(B.ID_VES_VOYAGE) = TRIM('$id_vesvoy'))
							AND ID_MACHINE <> -1
							AND LOWER(MCH_NAME) LIKE '%".strtolower($filter)."%' ORDER BY ID_MACHINE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function create_cwp_assign($vs_code,$id_vesvoy,$mch_nm,$id_bay,$pss_bay,$class,$id_user)
	{		
		$ps_bay = substr($pss_bay,0,1);
		
		if($class=='EXPORT'){
			$sql = "SELECT NO_CONTAINER,ID_VES_VOYAGE 
									FROM CON_OUTBOUND_SEQUENCE 
									WHERE ID_BAY = '$id_bay'
									AND ID_VES_VOYAGE = '$id_vesvoy' 
									AND ID_TERMINAL='".$this->gtools->terminal()."'
									AND DECK_HATCH = '$ps_bay'";
			$query   = $this->db->query($sql)->result();	
		}else{
			$sql = "SELECT NO_CONTAINER,ID_VES_VOYAGE 
									FROM CON_INBOUND_SEQUENCE 
									WHERE ID_BAY = '$id_bay'
									AND ID_VES_VOYAGE = '$id_vesvoy'  
									AND ID_TERMINAL='".$this->gtools->terminal()."'
									AND DECK_HATCH = '$ps_bay'";
			$query   = $this->db->query($sql)->result();	
		}

		foreach ($query as $key => $value) {
			
			$no_cont = $value->NO_CONTAINER;
			$id_ves  = $value->ID_VES_VOYAGE;

			/*validate qc plan is empty*/
			$sql_qc = "SELECT QC_PLAN 
						FROM CON_LISTCONT 
						WHERE NO_CONTAINER = '$no_cont'
						AND ID_VES_VOYAGE = '$id_ves' 
						AND ID_TERMINAL='".$this->gtools->terminal()."'";
			$row_qc = $this->db->query($sql_qc)->row();

			if(empty($row_qc->QC_PLAN)){
				$sql_update = "UPDATE CON_LISTCONT 
						SET QC_PLAN = '$mch_nm' 
						WHERE NO_CONTAINER = '$no_cont'
						AND ID_VES_VOYAGE = '$id_ves' 
						AND ID_TERMINAL='".$this->gtools->terminal()."'";
				$this->db->query($sql_update);
			}
		}


		$params_cwp = $vs_code."^".$id_vesvoy."^".$mch_nm."^".$id_bay."^".$pss_bay."^".$class."^".$id_user;
		$param = array(
			array('name'=>':v_param', 'value'=>$params_cwp, 'length'=>500),
			array('name'=>':v_msg', 'value'=>&$msg, 'length'=>50)
		);
		
		$sql = "BEGIN ITOS_OP.proc_create_cwp_assign(:v_param,:v_msg); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);

		return $msg;
	}

	public function data_active_mch_old($id_vesvoy){
		$query 		= "SELECT I_MCH_WORKING_PLAN,
							  MCH_NAME, 
							  BCH, 
							  TO_CHAR(START_WORK,'DD-MM-RRRR HH24:MI') START_WORK,
							  TO_CHAR(END_WORK,'DD-MM-RRRR HH24:MI') END_WORK,
							  ID_MACHINE
						FROM MCH_WORKING_PLAN 
						WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy')
							AND ID_MACHINE <> -1";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
        
	public function data_active_mch($id_vesvoy){
		// $query = "Select mwp.id_machine, mwp.mch_name, TO_CHAR(mwp.start_work,'DD-MM-RRRR HH24:MI') START_WORK, TO_CHAR(mwp.end_work,'DD-MM-RRRR HH24:MI') END_WORK, t_totals.total_moves from MCH_WORKING_PLAN MWP, (
  //                   select id_machine, mch_name, sum(total_inbound) total_moves from (
  //                       select 
  //                           p.id_machine, p.mch_name, count(1) total_inbound
  //                       from CON_INBOUND_SEQUENCE r
  //                       inner join JOB_YARD_MANAGER k on k.no_container = r.no_container
  //                       inner join MCH_WORKING_PLAN p on p.id_mch_working_plan = k.id_mch_working_plan
  //                       WHERE TRIM(r.ID_VES_VOYAGE) = TRIM('$id_vesvoy') and r.status='C' AND p.ID_MACHINE <> -1
  //                       group by p.id_machine, p.mch_name
  //                       union all 
  //                       select 
  //                           p.id_machine, p.mch_name, count(1) total_inbound
  //                       from CON_OUTBOUND_SEQUENCE r
  //                       inner join JOB_YARD_MANAGER k on k.no_container = r.no_container
  //                       inner join MCH_WORKING_PLAN p on p.id_mch_working_plan = k.id_mch_working_plan
  //                       WHERE TRIM(r.ID_VES_VOYAGE) = TRIM('$id_vesvoy') and r.status='C' AND p.ID_MACHINE <> -1
  //                       group by p.id_machine, p.mch_name
  //                   ) group by id_machine, mch_name
  //               ) t_totals
  //               where MWP.id_machine=t_totals.id_machine and TRIM(MWP.ID_VES_VOYAGE) = TRIM('$id_vesvoy')
  //               ";

		// $query 		= "SELECT ID_MCH_WORKING_PLAN,
		// 					  MCH_NAME, 
		// 					  BCH, 
		// 					  TO_CHAR(START_WORK,'DD-MM-RRRR HH24:MI') START_WORK,
		// 					  TO_CHAR(END_WORK,'DD-MM-RRRR HH24:MI') END_WORK,
		// 					  ID_MACHINE
		// 				FROM MCH_WORKING_PLAN 
		// 				WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy')
		// 					AND ID_MACHINE <> -1";   


		$query 		= "SELECT ID_MCH_WORKING_PLAN,
								MWP.MCH_NAME, 
								MCH.STANDARD_BCH AS BCH, 
								MCH.BG_COLOR, 
								TO_CHAR(START_WORK,'DD-MM-RRRR HH24:MI') START_WORK,
								TO_CHAR(END_WORK,'DD-MM-RRRR HH24:MI') END_WORK,
								MWP.ID_MACHINE,
								(SELECT COUNT(*) 
                       FROM CON_INBOUND_SEQUENCE C
				       INNER JOIN CON_LISTCONT B
				       	ON C.NO_CONTAINER = B.NO_CONTAINER AND C.POINT = B.POINT AND C.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND C.ID_TERMINAL = B.ID_TERMINAL
                        WHERE TRIM(C.ID_VES_VOYAGE) = TRIM(B.ID_VES_VOYAGE) AND C.ID_TERMINAL='".$this->gtools->terminal()."' AND ID_CLASS_CODE IN ('I','TI','S1','S2') AND B.ID_OP_STATUS <> 'DIS'
                    	AND C.STATUS = 'C' AND B.QC_REAL = MWP.MCH_NAME) AS IT,
						NVL((SELECT COMPLETED FROM (SELECT 
						 F.ID_MACHINE,
						 F.MCH_NAME MACHINE,
						 SUM(G.COMPLETED) AS COMPLETED
						FROM    (  SELECT BAY_ AS BAY,
									DECK_HATCH AS LOCATION,
									SUM (COMPLETED) COMPLETED,
									ID_VES_VOYAGE
								 FROM (SELECT A.NO_CONTAINER,
											A.ID_VES_VOYAGE,
											DECK_HATCH,
											A.BAY_,
											A.ID_BAY,
											DECODE (STATUS, 'C', 1, 0) COMPLETED
										 FROM     CON_OUTBOUND_SEQUENCE  A
											INNER JOIN
												 CON_LISTCONT B
											ON (A.NO_CONTAINER = B.NO_CONTAINER
												AND A.POINT = B.POINT))
								WHERE ID_VES_VOYAGE = '$id_vesvoy'
							 GROUP BY BAY_, DECK_HATCH, ID_VES_VOYAGE
							 ORDER BY BAY_, DECK_HATCH, ID_VES_VOYAGE) G
						 LEFT JOIN
							(SELECT C.SEQUENCE,
									C.BAY,
									C.DECK_HATCH,
									C.ACTIVE,
									D.ID_VES_VOYAGE,
									D.ID_MACHINE,
									E.MCH_NAME,
									TO_CHAR (C.ESTIMATE_TIME, 'DD-MM-YY HH24:MI') ESTIMATE_TIME
							 FROM MCH_WORKING_SEQUENCE C
									INNER JOIN MCH_WORKING_PLAN D
									 ON (C.ID_MCH_WORKING_PLAN = D.ID_MCH_WORKING_PLAN)
									INNER JOIN M_MACHINE E
									 ON (D.ID_MACHINE = E.ID_MACHINE)
							WHERE C.ACTIVITY = 'E') F
						 ON (    F.ID_VES_VOYAGE = G.ID_VES_VOYAGE
							 AND F.BAY = G.BAY
							 AND F.DECK_HATCH = G.LOCATION)
					GROUP BY F.ID_MACHINE,F.MCH_NAME) CO WHERE CO.ID_MACHINE = MWP.ID_MACHINE),0) AS OT,
					((SELECT COUNT(*) 
                       FROM CON_INBOUND_SEQUENCE C
				       INNER JOIN CON_LISTCONT B
				       	ON C.NO_CONTAINER = B.NO_CONTAINER AND C.POINT = B.POINT AND C.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND C.ID_TERMINAL = B.ID_TERMINAL
                        WHERE TRIM(C.ID_VES_VOYAGE) = TRIM(MWP.ID_VES_VOYAGE) AND C.ID_TERMINAL='".$this->gtools->terminal()."' AND ID_CLASS_CODE IN ('I','TI','S1','S2') AND B.ID_OP_STATUS <> 'DIS'
                    	AND C.STATUS = 'C' AND B.QC_REAL = MWP.MCH_NAME) + (SELECT COUNT(*) 
                       FROM CON_OUTBOUND_SEQUENCE C
				       INNER JOIN CON_LISTCONT B
				       	ON C.NO_CONTAINER = B.NO_CONTAINER AND C.POINT = B.POINT AND C.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND C.ID_TERMINAL = B.ID_TERMINAL
                        WHERE TRIM(C.ID_VES_VOYAGE) = TRIM(MWP.ID_VES_VOYAGE) AND C.ID_TERMINAL='".$this->gtools->terminal()."' AND ID_CLASS_CODE IN ('E','TE','S1','S2') AND B.ID_OP_STATUS <> 'DIS'
                    	AND C.STATUS = 'C' AND B.QC_REAL = MWP.MCH_NAME)) AS COMPLETED
						FROM MCH_WORKING_PLAN MWP
						LEFT JOIN M_MACHINE MCH ON MWP.ID_MACHINE = MCH.ID_MACHINE
						WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy')
							AND MWP.ID_MACHINE <> -1"; 
//		debux($query);
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
                
		return $data;
	}

	public function get_cwp_vesvoy($id_ves_voyage){
		$query 		= "SELECT HD.ID_MACHINE, 
				              HD.MCH_NAME, 
				              SQ.SEQUENCE,
				              SQ.BAY,
				              CASE WHEN SQ.DECK_HATCH = 'D' THEN 'DECK'
				              	   ELSE 'HATCH' END AS DECK_HATCH,
				              SQ.ACTIVITY,
				              TO_CHAR(SQ.START_SEQUENCE,'DD') DAY_START,
				              TO_CHAR(SQ.END_SEQUENCE,'DD') DAY_END,
				              TO_CHAR(SQ.START_SEQUENCE,'MM') MON_START,
				              TO_CHAR(SQ.END_SEQUENCE,'MM') MON_END,
				              TO_CHAR(SQ.START_SEQUENCE,'YYYY') YEAR_START,
				              TO_CHAR(SQ.END_SEQUENCE,'YYYY') YEAR_END,
				              TO_CHAR(SQ.START_SEQUENCE,'HH24') HOUR_START,
				              TO_CHAR(SQ.END_SEQUENCE,'HH24') HOUR_END,
				              TO_CHAR(SQ.START_SEQUENCE,'MI') MNT_START,
				              TO_CHAR(SQ.END_SEQUENCE,'MI') MNT_END,
				              TRIM(MST.BG_COLOR) COLOR
				FROM MCH_WORKING_PLAN HD, MCH_WORKING_SEQUENCE SQ, M_MACHINE MST
				WHERE HD.ID_MCH_WORKING_PLAN = SQ.ID_MCH_WORKING_PLAN
				    AND TRIM(HD.ID_VES_VOYAGE) = TRIM('$id_ves_voyage')
				    AND MST.ID_MACHINE = HD.ID_MACHINE
				    AND TRIM(SQ.ACTIVITY) = 'I'
				    ORDER BY BAY ASC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_cwp_vesvoydeck($id_ves_voyage){
		$query 		= "SELECT hd.ID_MCH_WORKING_PLAN,HD.ID_MACHINE, 
				              HD.MCH_NAME, 
				              SQ.SEQUENCE,
				              SQ.BAY,
				              CASE WHEN SQ.DECK_HATCH = 'D' THEN 'DECK'
				                   ELSE 'HATCH' END AS DECK_HATCH,
				              SQ.ACTIVITY,
				              TO_CHAR(SQ.START_SEQUENCE,'DD') DAY_START,
				              TO_CHAR(SQ.END_SEQUENCE,'DD') DAY_END,
				              TO_CHAR(SQ.START_SEQUENCE,'MM') MON_START,
				              TO_CHAR(SQ.END_SEQUENCE,'MM') MON_END,
				              TO_CHAR(SQ.START_SEQUENCE,'YYYY') YEAR_START,
				              TO_CHAR(SQ.END_SEQUENCE,'YYYY') YEAR_END,
				              TO_CHAR(SQ.START_SEQUENCE,'HH24') HOUR_START,
				              TO_CHAR(SQ.END_SEQUENCE,'HH24') HOUR_END,
				              TO_CHAR(SQ.START_SEQUENCE,'MI') MNT_START,
				              TO_CHAR(SQ.END_SEQUENCE,'MI') MNT_END,
				              TRIM(MST.BG_COLOR) COLOR,
							  case when ((SQ.end_SEQUENCE-SQ.START_SEQUENCE)*24)<=0 then 1
                                when nvl(((SQ.end_SEQUENCE-SQ.START_SEQUENCE)*24),1)=1 then 1 
                                else ceil((SQ.end_SEQUENCE-SQ.START_SEQUENCE)*24) 
                              end  SELISIHC
				FROM MCH_WORKING_PLAN HD, MCH_WORKING_SEQUENCE SQ, M_MACHINE MST
				WHERE HD.ID_MCH_WORKING_PLAN = SQ.ID_MCH_WORKING_PLAN
				    AND TRIM(HD.ID_VES_VOYAGE) = TRIM('$id_ves_voyage')
				    AND MST.ID_MACHINE = HD.ID_MACHINE
					and SQ.DECK_HATCH='D'
				    ORDER BY BAY ASC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_cwp_vesvoyhatch($id_ves_voyage){
		$query 		= "SELECT hd.ID_MCH_WORKING_PLAN,HD.ID_MACHINE, 
				              HD.MCH_NAME, 
				              SQ.SEQUENCE,
				              SQ.BAY,
				              CASE WHEN SQ.DECK_HATCH = 'D' THEN 'DECK'
				              	   ELSE 'HATCH' END AS DECK_HATCH,
				              SQ.ACTIVITY,
				              TO_CHAR(SQ.START_SEQUENCE,'DD') DAY_START,
				              TO_CHAR(SQ.END_SEQUENCE,'DD') DAY_END,
				              TO_CHAR(SQ.START_SEQUENCE,'MM') MON_START,
				              TO_CHAR(SQ.END_SEQUENCE,'MM') MON_END,
				              TO_CHAR(SQ.START_SEQUENCE,'YYYY') YEAR_START,
				              TO_CHAR(SQ.END_SEQUENCE,'YYYY') YEAR_END,
				              TO_CHAR(SQ.START_SEQUENCE,'HH24') HOUR_START,
				              TO_CHAR(SQ.END_SEQUENCE,'HH24') HOUR_END,
				              TO_CHAR(SQ.START_SEQUENCE,'MI') MNT_START,
				              TO_CHAR(SQ.END_SEQUENCE,'MI') MNT_END,
				              TRIM(MST.BG_COLOR) COLOR,
							   case when ((SQ.end_SEQUENCE-SQ.START_SEQUENCE)*24)<=0 then 1 else ceil((SQ.end_SEQUENCE-SQ.START_SEQUENCE)*24) end  SELISIHC
				FROM MCH_WORKING_PLAN HD, MCH_WORKING_SEQUENCE SQ, M_MACHINE MST
				WHERE HD.ID_MCH_WORKING_PLAN = SQ.ID_MCH_WORKING_PLAN
				    AND TRIM(HD.ID_VES_VOYAGE) = TRIM('$id_ves_voyage')
				    AND MST.ID_MACHINE = HD.ID_MACHINE
					and SQ.DECK_HATCH='H'
				    ORDER BY BAY ASC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_start_cwp($id_ves_voyage)
	{
		$query 		= "SELECT TO_CHAR(MIN(START_WORK),'DD') DAY_MULAI FROM MCH_WORKING_PLAN WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_ves_voyage')";
		$day_start	= $this->db->query($query);
		$data 		= $day_start->result_array();
		
		return $data;
	}
	
	public function getParameterJam($id_ves_voyage)
	{
		$query 		= "SELECT WP.ID_VES_VOYAGE,TO_CHAR(MIN(WS.START_SEQUENCE),'DD MONTH YYYY HH24:MI') AS STARTX, TO_CHAR(MAX(WS.END_SEQUENCE),'DD MM YYYY HH24:MI') AS ENDX, CEIL((MAX(WS.END_SEQUENCE) - MIN(WS.START_SEQUENCE))*24/6) AS SELISIHX, CEIL((MAX(WS.END_SEQUENCE) - MIN(WS.START_SEQUENCE))*24) AS SELISIHY  
				    FROM MCH_WORKING_PLAN WP
				    LEFT JOIN MCH_WORKING_SEQUENCE WS
					    ON WP.ID_MCH_WORKING_PLAN = WS.ID_MCH_WORKING_PLAN
				    LEFT JOIN (
					    SELECT * FROM VES_VOYAGE_CWP WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND ID_TERMINAL = ".$this->gtools->terminal()."
				    ) VVC ON WP.ID_VES_VOYAGE = VVC.ID_VES_VOYAGE AND WS.BAY = VVC.BAY
				    WHERE TRIM(WP.ID_VES_VOYAGE) = TRIM('$id_ves_voyage') AND (VVC.CWP_D <> 0 OR VVC.CWP_DE <> 0 OR VVC.CWP_H <> 0 OR VVC.CWP_HE <> 0)
					AND WS.START_SEQUENCE < WS.END_SEQUENCE
				    GROUP BY WP.ID_VES_VOYAGE";
		$day_end	= $this->db->query($query);
		$data 		= $day_end->result_array();
		
		return $data;
	}
	
	public function getSequenceCWP($id_ves_voyage)
	{
		$query 		= "SELECT WP.ID_MCH_WORKING_PLAN, WP.ID_VES_VOYAGE, WS.\"SEQUENCE\",WS.BAY
				    ,LOWER(WS.DECK_HATCH) AS DECK_HATCH
				    ,DECODE(WS.ACTIVITY,'I','D','L') AS ACTIVITY
				    ,TO_CHAR(WS.START_SEQUENCE,'DD MONTH YYYY HH24:MI') AS START_SEQUENCE
				    ,STARTX
				    ,((WS.START_SEQUENCE - STARTX) * 24 * 60) / 10 AS START_SEQUENCE_
				    ,TO_CHAR(WS.END_SEQUENCE,'DD MONTH YYYY HH24:MI') AS END_SEQUENCE
				    ,(((WS.END_SEQUENCE - STARTX) * 24 * 60) / 10) - (((WS.START_SEQUENCE - STARTX) * 24 * 60) / 10) AS LENGTH_SEQUENCE_
				    ,MST.BG_COLOR
				    ,CASE WHEN WS.ACTIVITY = 'I' THEN VVC.CWP_D ELSE 0 END AS CWP_D
				    ,CASE WHEN WS.ACTIVITY = 'I' THEN VVC.CWP_H ELSE 0 END AS CWP_H
				    ,CASE WHEN WS.ACTIVITY = 'E' THEN VVC.CWP_DE ELSE 0 END AS CWP_DE
				    ,CASE WHEN WS.ACTIVITY = 'E' THEN VVC.CWP_HE ELSE 0 END AS CWP_HE
				    FROM MCH_WORKING_PLAN WP
				    LEFT JOIN MCH_WORKING_SEQUENCE WS
					    ON WP.ID_MCH_WORKING_PLAN = WS.ID_MCH_WORKING_PLAN
				    LEFT JOIN (
					    SELECT WP.ID_VES_VOYAGE,MIN(WS.START_SEQUENCE) AS STARTX, MAX(WS.END_SEQUENCE) AS ENDX, CEIL((MAX(WS.END_SEQUENCE) - MIN(WS.START_SEQUENCE))*24/6) AS SELISIHX, CEIL((MAX(WS.END_SEQUENCE) - MIN(WS.START_SEQUENCE))*24) AS SELISIHY  
					    FROM MCH_WORKING_PLAN WP
					    LEFT JOIN MCH_WORKING_SEQUENCE WS
						    ON WP.ID_MCH_WORKING_PLAN = WS.ID_MCH_WORKING_PLAN
					    LEFT JOIN (
						    SELECT * FROM VES_VOYAGE_CWP WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND ID_TERMINAL = ".$this->gtools->terminal()."
					    ) VVC ON WP.ID_VES_VOYAGE = VVC.ID_VES_VOYAGE AND WS.BAY = VVC.BAY
					    WHERE TRIM(WP.ID_VES_VOYAGE) = TRIM('$id_ves_voyage') AND (VVC.CWP_D <> 0 OR VVC.CWP_DE <> 0 OR VVC.CWP_H <> 0 OR VVC.CWP_HE <> 0)
					    AND WS.START_SEQUENCE < WS.END_SEQUENCE
					    GROUP BY WP.ID_VES_VOYAGE
				    ) A ON WP.ID_VES_VOYAGE = A.ID_VES_VOYAGE
				    LEFT JOIN M_MACHINE MST ON WP.ID_MACHINE = MST.ID_MACHINE
				    LEFT JOIN (
					    SELECT * FROM VES_VOYAGE_CWP WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND ID_TERMINAL = ".$this->gtools->terminal()."
				    ) VVC ON WP.ID_VES_VOYAGE = VVC.ID_VES_VOYAGE AND WS.BAY = VVC.BAY
				    WHERE TRIM(WP.ID_VES_VOYAGE) = TRIM('$id_ves_voyage') AND (VVC.CWP_D <> 0 OR VVC.CWP_DE <> 0 OR VVC.CWP_H <> 0 OR VVC.CWP_HE <> 0)
					AND WS.START_SEQUENCE < WS.END_SEQUENCE
				    ORDER BY WS.START_SEQUENCE";
//		echo '<pre>'.$query.'</pre>';exit;
		$day_end	= $this->db->query($query);
		$data 		= $day_end->result_array();
		
		return $data;
	}
	
	public function get_end_cwp($id_ves_voyage)
	{
		$query 		= "SELECT TO_CHAR(MAX(START_WORK),'DD') DAY_AKHIR FROM MCH_WORKING_PLAN WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_ves_voyage')";
		$day_end	= $this->db->query($query);
		$data 		= $day_end->result_array();
		
		return $data;
	}
	
	public function get_qc_working_list($id_ves_voyage='',$type='I'){
		$param = array($id_ves_voyage, $type);
		$table = '';
		if ($type=='I'){
			$table = ' CON_INBOUND_SEQUENCE ';
		} else if ($type=='E') {
			$table = ' CON_OUTBOUND_SEQUENCE ';
		}
		
		$query = "SELECT 
					   G.*,
					   F.MCH_NAME MACHINE,
					   CASE WHEN REMAIN = 0 THEN 'FINISH' ELSE F.ESTIMATE_TIME END AS ESTIMATE_TIME,
					   F.ACTIVE,
					   TOTAL - REMAIN AS SUMDISCHLOAD,
					   F.SEQUENCE
				  FROM    (  SELECT BAY_ AS BAY,
									DECK_HATCH AS LOCATION,
									COUNT (TWENTY) TWENTY,
									COUNT (FOURTY) FOURTY,
									COUNT (TWENTY) + COUNT (FOURTY) TOTAL,
									SUM (REMAIN) REMAIN,
									ID_VES_VOYAGE
							   FROM (SELECT A.NO_CONTAINER,
											A.ID_VES_VOYAGE,
											'1' AS TWENTY,
											'' AS FOURTY,
											DECK_HATCH,
											A.BAY_,
											A.ID_BAY,
											DECODE (STATUS, 'P', 1, 0) REMAIN
									   FROM    ".$table." A
											INNER JOIN
											   CON_LISTCONT B
											ON (A.NO_CONTAINER = B.NO_CONTAINER
												AND A.POINT = B.POINT)
									  WHERE B.CONT_SIZE IN ('20','21')
									 UNION
									 SELECT A.NO_CONTAINER,
											A.ID_VES_VOYAGE,
											'' AS TWENTY,
											'1' AS FOURTY,
											DECK_HATCH,
											A.BAY_ BAY_,
											A.ID_BAY,
											DECODE (STATUS, 'P', 1, 0) REMAIN
									   FROM    ".$table." A
											INNER JOIN
											   CON_LISTCONT B
											ON (A.NO_CONTAINER = B.NO_CONTAINER
												AND A.POINT = B.POINT)
									  WHERE B.CONT_SIZE IN ('40','45'))
							  WHERE ID_VES_VOYAGE = ?
						   GROUP BY BAY_, DECK_HATCH, ID_VES_VOYAGE
						   ORDER BY BAY_, DECK_HATCH, ID_VES_VOYAGE) G
					   LEFT JOIN
						  (SELECT C.SEQUENCE,
								  C.BAY,
								  C.DECK_HATCH,
								  C.ACTIVE,
								  D.ID_VES_VOYAGE,
								  E.MCH_NAME,
								  TO_CHAR (C.ESTIMATE_TIME, 'DD-MM-YY HH24:MI') ESTIMATE_TIME
							 FROM MCH_WORKING_SEQUENCE C
								  INNER JOIN MCH_WORKING_PLAN D
									 ON (C.ID_MCH_WORKING_PLAN = D.ID_MCH_WORKING_PLAN)
								  INNER JOIN M_MACHINE E
									 ON (D.ID_MACHINE = E.ID_MACHINE)
							WHERE C.ACTIVITY = ?) F
					   ON (    F.ID_VES_VOYAGE = G.ID_VES_VOYAGE
						   AND F.BAY = G.BAY
						   AND F.DECK_HATCH = G.LOCATION)
				ORDER BY G.BAY, G.LOCATION";
		$rs = $this->db->query($query, $param);
		// echo '<pre>'. $this->db->last_query().'</pre>';exit;
		$data = $rs->result_array();
		return $data;
	}
	
	public function get_total_qc_working_list($id_ves_voyage,$id_machine){
		$query = "SELECT
				sum(subs.COMPLETE) TOTAL
			FROM
				(
		SELECT
			sub.ID_MACHINE,
			sub.MACHINE,
			sum( sub.REMAIN ) COMPLETE
		FROM
			(SELECT 
					   G.*,
						F.ID_MACHINE,
					   F.MCH_NAME MACHINE,
					   CASE WHEN REMAIN = 0 THEN 'FINISH' ELSE F.ESTIMATE_TIME END AS ESTIMATE_TIME,
					   F.ACTIVE,
					   TOTAL - REMAIN AS SUMDISCHLOAD,
					   F.SEQUENCE
				  FROM    (  SELECT BAY_ AS BAY,
									DECK_HATCH AS LOCATION,
									COUNT (TWENTY) TWENTY,
									COUNT (FOURTY) FOURTY,
									COUNT (TWENTY) + COUNT (FOURTY) TOTAL,
									SUM (REMAIN) REMAIN,
									ID_VES_VOYAGE
							   FROM (SELECT A.NO_CONTAINER,
											A.ID_VES_VOYAGE,
											'1' AS TWENTY,
											'' AS FOURTY,
											DECK_HATCH,
											A.BAY_,
											A.ID_BAY,
											DECODE (STATUS, 'P', 1, 0) REMAIN
									   FROM    CON_INBOUND_SEQUENCE A
											INNER JOIN
											   CON_LISTCONT B
											ON (A.NO_CONTAINER = B.NO_CONTAINER
												AND A.POINT = B.POINT)
									  WHERE B.CONT_SIZE IN ('20','21')
									 UNION
									 SELECT A.NO_CONTAINER,
											A.ID_VES_VOYAGE,
											'' AS TWENTY,
											'1' AS FOURTY,
											DECK_HATCH,
											A.BAY_ BAY_,
											A.ID_BAY,
											DECODE (STATUS, 'P', 1, 0) REMAIN
									   FROM    CON_INBOUND_SEQUENCE A
											INNER JOIN
											   CON_LISTCONT B
											ON (A.NO_CONTAINER = B.NO_CONTAINER
												AND A.POINT = B.POINT)
									  WHERE B.CONT_SIZE IN ('40','45'))
							  WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						   GROUP BY BAY_, DECK_HATCH, ID_VES_VOYAGE
						   ORDER BY BAY_, DECK_HATCH, ID_VES_VOYAGE) G
					   LEFT JOIN
						  (SELECT C.SEQUENCE,
								  C.BAY,
								  C.DECK_HATCH,
								  C.ACTIVE,
								  D.ID_VES_VOYAGE,
									E.ID_MACHINE,
								  E.MCH_NAME,
								  TO_CHAR (C.ESTIMATE_TIME, 'DD-MM-YY HH24:MI') ESTIMATE_TIME
							 FROM MCH_WORKING_SEQUENCE C
								  INNER JOIN MCH_WORKING_PLAN D
									 ON (C.ID_MCH_WORKING_PLAN = D.ID_MCH_WORKING_PLAN)
								  INNER JOIN M_MACHINE E
									 ON (D.ID_MACHINE = E.ID_MACHINE)
							WHERE C.ACTIVITY = 'I') F
					   ON (    F.ID_VES_VOYAGE = G.ID_VES_VOYAGE
						   AND F.BAY = G.BAY
						   AND F.DECK_HATCH = G.LOCATION)
				UNION ALL
				SELECT 
					   G.*,
						F.ID_MACHINE,
					   F.MCH_NAME MACHINE,
					   CASE WHEN REMAIN = 0 THEN 'FINISH' ELSE F.ESTIMATE_TIME END AS ESTIMATE_TIME,
					   F.ACTIVE,
					   TOTAL - REMAIN AS SUMDISCHLOAD,
					   F.SEQUENCE
				  FROM    (  SELECT BAY_ AS BAY,
									DECK_HATCH AS LOCATION,
									COUNT (TWENTY) TWENTY,
									COUNT (FOURTY) FOURTY,
									COUNT (TWENTY) + COUNT (FOURTY) TOTAL,
									SUM (REMAIN) REMAIN,
									ID_VES_VOYAGE
							   FROM (SELECT A.NO_CONTAINER,
											A.ID_VES_VOYAGE,
											'1' AS TWENTY,
											'' AS FOURTY,
											DECK_HATCH,
											A.BAY_,
											A.ID_BAY,
											DECODE (STATUS, 'P', 1, 0) REMAIN
									   FROM    CON_OUTBOUND_SEQUENCE A
											INNER JOIN
											   CON_LISTCONT B
											ON (A.NO_CONTAINER = B.NO_CONTAINER
												AND A.POINT = B.POINT)
									  WHERE B.CONT_SIZE IN ('20','21')
									 UNION
									 SELECT A.NO_CONTAINER,
											A.ID_VES_VOYAGE,
											'' AS TWENTY,
											'1' AS FOURTY,
											DECK_HATCH,
											A.BAY_ BAY_,
											A.ID_BAY,
											DECODE (STATUS, 'P', 1, 0) REMAIN
									   FROM    CON_OUTBOUND_SEQUENCE A
											INNER JOIN
											   CON_LISTCONT B
											ON (A.NO_CONTAINER = B.NO_CONTAINER
												AND A.POINT = B.POINT)
									  WHERE B.CONT_SIZE IN ('40','45'))
							  WHERE ID_VES_VOYAGE = '$id_ves_voyage'
						   GROUP BY BAY_, DECK_HATCH, ID_VES_VOYAGE
						   ORDER BY BAY_, DECK_HATCH, ID_VES_VOYAGE) G
					   LEFT JOIN
						  (SELECT C.SEQUENCE,
								  C.BAY,
								  C.DECK_HATCH,
								  C.ACTIVE,
								  D.ID_VES_VOYAGE,
									E.ID_MACHINE,
								  E.MCH_NAME,
								  TO_CHAR (C.ESTIMATE_TIME, 'DD-MM-YY HH24:MI') ESTIMATE_TIME
							 FROM MCH_WORKING_SEQUENCE C
								  INNER JOIN MCH_WORKING_PLAN D
									 ON (C.ID_MCH_WORKING_PLAN = D.ID_MCH_WORKING_PLAN)
								  INNER JOIN M_MACHINE E
									 ON (D.ID_MACHINE = E.ID_MACHINE)
							WHERE C.ACTIVITY = 'E') F
					   ON (    F.ID_VES_VOYAGE = G.ID_VES_VOYAGE
						   AND F.BAY = G.BAY
						   AND F.DECK_HATCH = G.LOCATION)
			) sub 
		GROUP BY
			sub.ID_MACHINE,
			sub.MACHINE 
			
			UNION ALL
			
			SELECT 
								MWP.ID_MACHINE,
								MWP.MCH_NAME MACHINE,
					((SELECT COUNT(*) 
                       FROM CON_INBOUND_SEQUENCE C
				       INNER JOIN CON_LISTCONT B
				       	ON C.NO_CONTAINER = B.NO_CONTAINER AND C.POINT = B.POINT AND C.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND C.ID_TERMINAL = B.ID_TERMINAL
                        WHERE TRIM(C.ID_VES_VOYAGE) = TRIM(MWP.ID_VES_VOYAGE) AND C.ID_TERMINAL='".$this->gtools->terminal()."' AND ID_CLASS_CODE IN ('I','TI','S1','S2') AND B.ID_OP_STATUS <> 'DIS'
                    	AND C.STATUS = 'C' AND B.QC_REAL = MWP.MCH_NAME) + (SELECT COUNT(*) 
                       FROM CON_OUTBOUND_SEQUENCE C
				       INNER JOIN CON_LISTCONT B
				       	ON C.NO_CONTAINER = B.NO_CONTAINER AND C.POINT = B.POINT AND C.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND C.ID_TERMINAL = B.ID_TERMINAL
                        WHERE TRIM(C.ID_VES_VOYAGE) = TRIM(MWP.ID_VES_VOYAGE) AND C.ID_TERMINAL='".$this->gtools->terminal()."' AND ID_CLASS_CODE IN ('E','TE','S1','S2') AND B.ID_OP_STATUS <> 'DIS'
                    	AND C.STATUS = 'C' AND B.QC_REAL = MWP.MCH_NAME)) AS COMPLETE
						
						FROM MCH_WORKING_PLAN MWP
						LEFT JOIN M_MACHINE MCH ON MWP.ID_MACHINE = MCH.ID_MACHINE
						WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_ves_voyage')
							AND MWP.ID_MACHINE <> -1
					) subs
					WHERE subs.ID_MACHINE = '$id_machine'
						GROUP BY
							subs.ID_MACHINE,
							subs.MACHINE 
		 ";
		$rs = $this->db->query($query);
		// echo '<pre>'. $this->db->last_query().'</pre>';exit;
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		return $total;
	}
	
	public function activate_stevedoring_job($id_ves_voyage, $activity){
		$param = array(
			array('name'=>':id_ves_voyage', 'value'=>$id_ves_voyage, 'length'=>15),
			array('name'=>':activity', 'value'=>$activity, 'length'=>1)
		);
		
		$this->db->trans_start();
		$sql = "BEGIN ITOS_OP.PROC_ACTIVATE_STEVEDORING_JOB(:id_ves_voyage, :activity); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		// print $sql;
		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function deactivate_stevedoring_job($id_ves_voyage, $activity){
		$param = array(
			array('name'=>':id_ves_voyage', 'value'=>$id_ves_voyage, 'length'=>15),
			array('name'=>':activity', 'value'=>$activity, 'length'=>1)
		);
		
		$this->db->trans_start();
		$sql = "BEGIN ITOS_OP.PROC_DEACTIVATE_STEVE_JOB(:id_ves_voyage, :activity); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		// print $sql;
		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function get_data_external_truck_list($paging=false, $sort=false, $filters=false){
		$query_count = "SELECT COUNT(B.NO_POL) TOTAL
						FROM
						JOB_GATE_MANAGER A
						INNER JOIN 
						M_TRUCK B
						ON A.ID_TRUCK=B.ID_TRUCK
						WHERE A.STATUS_FLAG<>'C' AND ID_TERMINAL='".$this->gtools->terminal()."' AND A.GTIN_DATE IS NOT NULL";
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
			$qSort .= " , ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "WHERE 1=1 AND A.STATUS_FLAG<>'C' AND A.GTIN_DATE IS NOT NULL";
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
				
				if ($field=='NO_POL'){
					$field = "A.".$field;
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
								  FROM (  SELECT B.NO_POL, TO_CHAR(A.GTIN_DATE, 'DD-MM-YYYY HH24:MI:SS') GTIN_DATE, ((SYSDATE-A.GTIN_DATE)*24*60) IN_TERMINAL_DURATION
											FROM
											JOB_GATE_MANAGER A
											INNER JOIN 
											M_TRUCK B
											ON A.ID_TRUCK=B.ID_TRUCK
										$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."'
										ORDER BY A.GTIN_DATE ASC $qSort) V
							) B
						$qPaging";
		// print $query;
		$rs = $this->db->query($query);
		$truck_list = $rs->result_array();
		for ($i=0; $i<sizeof($truck_list); $i++){
			$hour = floor($truck_list[$i]['IN_TERMINAL_DURATION']/60);
			$minute = ceil((($truck_list[$i]['IN_TERMINAL_DURATION']/60)-$hour)*60);
			$truck_list[$i]['IN_TERMINAL_DURATION'] = $hour."HR ".$minute."MIN";
		}
		$data = array (
			'total'=>$total,
			'data'=>$truck_list
		);
		
		return $data;
	}
	
	public function delete_qc_assignment($id_mch_working_plan){
		
		
		
		
		//=============
		
		
		$this->db->trans_start();
		
		
		$param = array(
			array('name'=>':v_param', 'value'=>$id_mch_working_plan, 'length'=>500),
			array('name'=>':v_msg', 'value'=>&$msg, 'length'=>50)
		);
		
		
		$sql = "BEGIN ITOS_OP.PROC_DELETE_QC_ASSIGN(:v_param,:v_msg); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		
		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
		
		//=============
		
		// $param = array($id_mch_working_plan);
		// $this->db->trans_start();
		// $query 		= "DELETE FROM MCH_WORKING_PLAN
						// WHERE ID_MCH_WORKING_PLAN = ?";
		// $this->db->query($query, $param);
		// $query_2 		= "DELETE FROM MCH_WORKING_SEQUENCE
						// WHERE ID_MCH_WORKING_PLAN = ?";
		// $this->db->query($query_2, $param);
		// if ($this->db->trans_complete()){
			// return 1;
		// }else{
			// return 0;
		// }
	}
	
	public function get_data_job_control($paging, $sort, $filters){
	    	$qPaging = '';
		if ($paging != false){
		    $start = $paging['start']+1;
		    $end = $paging['page']*$paging['limit'];
//			$qPaging = "WHERE B.REC_NUM >= $start AND B.REC_NUM <= $end";
		    $qPaging = "HAVING ROWNUM >= $start AND ROWNUM <= $end";
		}
		$qSort = '';
		if ($sort != false){
			$sortProperty = $sort[0]->property; 
			$sortDirection = $sort[0]->direction;
			$qSort .= "ORDER BY ".$sortProperty." ".$sortDirection;
		}
		$qWhere = "";
		$qs = '';
		$encoded = true;
//		echo '<pre>filter : ';print_r($filters);echo '</pre>';
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
				
				if ($field=='MCH_NAME'){
					$field = "A.".$field;
				}else if ($field=='POOL_NAME'){
				    $field = "B.".$field;
				}else if($field=='ACTIVE'){
				    $field = "NVL(ACTIVE,'STOP')";
				}
//				echo '<pre>filtertype : '.$filterType.'</pre>';
//				echo '<pre>value : ';print_r($value);echo '</pre>';
				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".strtoupper($value)."%'"; Break;
					case 'list' :
						if(is_array($value)){
						    $fval = "";
						    foreach ($value as $val){
							if($fval != '') $fval .= ',';
							$fval .= "'".$val."'";
						    }
						    $qs .= " AND ".$field." IN (".$fval.")";
						}elseif (strstr($value,',')){
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
		
		$qry = "SELECT ROWNUM AS NO_URUT,ID_POOL,POOL_NAME,ID_MACHINE,MCH_NAME,ACTIVE FROM (
			    SELECT A.ID_POOL,B.POOL_NAME,A.ID_MACHINE,A.MCH_NAME,NVL(D.ACTIVE,'STOP') AS ACTIVE
			    FROM M_MACHINE A
			    LEFT JOIN M_POOL_H B ON A.ID_POOL = B.ID_POOL 
			    LEFT JOIN (
				    SELECT MAX(ID_MCH_WORKING_PLAN) AS ID_MCH_WORKING_PLAN, ID_MACHINE,MCH_NAME 
				    FROM MCH_WORKING_PLAN
				    GROUP BY ID_MACHINE,MCH_NAME
			    ) C ON A.ID_MACHINE = C.ID_MACHINE
			    LEFT JOIN (
				    SELECT ID_MCH_WORKING_PLAN, DECODE(MAX(ACTIVE),'Y','START','STOP') AS ACTIVE FROM MCH_WORKING_SEQUENCE 
				    GROUP BY ID_MCH_WORKING_PLAN
				    ORDER BY ID_MCH_WORKING_PLAN DESC
			    ) D ON C.ID_MCH_WORKING_PLAN = D.ID_MCH_WORKING_PLAN
			    WHERE MCH_TYPE IN ('YARD','QUAY') AND A.ID_TERMINAL='".$this->gtools->terminal()."' $qWhere
			    $qSort
			)A GROUP BY ROWNUM,ID_POOL,POOL_NAME,ID_MACHINE,MCH_NAME,ACTIVE
			    $qPaging 
			ORDER BY ROWNUM";
		
//		echo '<pre>';print_r($qry);echo '</pre>';exit;
		$result = $this->db->query($qry)->result_array();
		
		$query_count = "SELECT COUNT(*) AS TOTAL FROM M_MACHINE A
			LEFT JOIN M_POOL_H B ON A.ID_POOL = B.ID_POOL 
			LEFT JOIN (
				SELECT MAX(ID_MCH_WORKING_PLAN) AS ID_MCH_WORKING_PLAN, ID_MACHINE,MCH_NAME 
				FROM MCH_WORKING_PLAN
				GROUP BY ID_MACHINE,MCH_NAME
			) C ON A.ID_MACHINE = C.ID_MACHINE
			LEFT JOIN (
				SELECT ID_MCH_WORKING_PLAN, DECODE(MAX(ACTIVE),'Y','START','STOP') AS ACTIVE FROM MCH_WORKING_SEQUENCE 
				GROUP BY ID_MCH_WORKING_PLAN
				ORDER BY ID_MCH_WORKING_PLAN DESC
			) D ON C.ID_MCH_WORKING_PLAN = D.ID_MCH_WORKING_PLAN
			WHERE MCH_TYPE IN ('YARD','QUAY') AND A.ID_TERMINAL='".$this->gtools->terminal()."'$qWhere";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];
		
		$data = array (
			'total'=>$total,
			'data'=>$result
		);
		
		return $data;
	}
	
	public function assign_pool_mch($data){
	    $mch = $data['mch'];
	    $mch_name = $data['mch_name'];
	    $pool = $data['pool'];
	    $msg = 'di Assign';
	    
	    $this->db->trans_start();
	    $qry_check = "SELECT * FROM M_MACHINE WHERE ID_MACHINE = $mch AND ID_TERMINAL='".$this->gtools->terminal()."'";
	    $data_check = $this->db->query($qry_check)->result_array();
	    if($data_check[0]['ID_POOL'] == $pool){
		$pool = 'NULL';
		$msg = 'di Unassign';
	    }
	    $qry = "UPDATE M_MACHINE SET ID_POOL = $pool WHERE ID_MACHINE = $mch AND ID_TERMINAL='".$this->gtools->terminal()."'";
	    $this->db->query($qry);

	    if($this->db->trans_complete()){
		    return array(
			    'IsSuccess'=>true,
			    'Message'=>$mch_name.' berhasil '.$msg
		    );
	    }else{
		    return array(
			     'IsSuccess'=>false,
			     'Message'=>$mch_name.' gagal '.$msg
		     );
	    }
	}
	
	public function get_machine_specification($paging = false, $sort = false, $filters = false) {
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
		$qWhere = "WHERE 1=1";
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
				    case'TID' : $field = "$field";
					break;
				    case'NO_POL' : $field = "$field";
					break;
				}

				switch ($filterType) {
				    case 'string' : $qs .= " AND LOWER(" . $field . ") LIKE '%" . strtolower($value) . "%'";
					Break;
				    case 'list' :
					if(is_array($value)){
					    $fval = "";
					    foreach ($value as $val){
						if($fval != '') $fval .= ',';
						$fval .= "'".$val."'";
					    }
					    $qs .= " AND ".$field." IN (".$fval.")";
					}elseif (strstr($value, ',')) {
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

		$query = "SELECT ROWNUM,ID_MACHINE,MCH_NAME,MCH_TYPE,MCH_SUB_TYPE,SIZE_CHASSIS,STANDARD_BCH,ID_POOL,POOL_NAME,BG_COLOR FROM (
				SELECT ID_MACHINE,MCH_NAME,MCH_TYPE,MCH_SUB_TYPE,SIZE_CHASSIS,STANDARD_BCH,A.ID_POOL,B.POOL_NAME,BG_COLOR 
				FROM M_MACHINE A
				LEFT JOIN M_POOL_H B ON A.ID_POOL = B.ID_POOL
				$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND ID_MACHINE > 0
				$qSort
			)
			GROUP BY ROWNUM,ID_MACHINE,MCH_NAME,MCH_TYPE,MCH_SUB_TYPE,SIZE_CHASSIS,STANDARD_BCH,ID_POOL,POOL_NAME,BG_COLOR
			$qPaging
			ORDER BY ROWNUM";
		$rs = $this->db->query($query);
//		echo '<pre>'.$this->db->last_query().'</pre>';exit;
		$result_list = $rs->result_array();
		
		$query_count = "SELECT COUNT(*) AS TOTAL FROM M_MACHINE A
				LEFT JOIN M_POOL_H B ON A.ID_POOL = B.ID_POOL
				$qWhere";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];

		$data = array(
		    'total' => $total,
		    'data' => $result_list
		);
		return $data;
    }
    
    public function save_mch($data) {

		$CREATE_USER	= $data['CREATE_USER'];
		$MCH_NAME	= $data['MCH_NAME'];
		$MCH_TYPE	= $data['MCH_TYPE'];
		$MCH_SUB_TYPE	= $data['MCH_SUB_TYPE'];
		$SIZE_CHASSIS	= $data['SIZE_CHASSIS'];
		$STANDARD_BCH	= $data['STANDARD_BCH'];
		$BG_COLOR	= $data['BG_COLOR'];
		$CREATE_DATE = date('d-M-y h:i:s A');

		if (!isset($MCH_NAME) || $MCH_NAME == '') {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Code harus diisi.'
		    );
		}
		if (!isset($MCH_TYPE) || $MCH_TYPE == '') {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Type harus diisi.'
		    );
		}
		if ($MCH_TYPE != 'ITV' && !isset($MCH_SUB_TYPE) || $MCH_TYPE != 'ITV' && isset($MCH_SUB_TYPE) && $MCH_SUB_TYPE == '') {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Sub Type harus diisi.'
		    );
		}

		$query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_MACHINE WHERE MCH_NAME = '$MCH_NAME' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$result = $this->db->query($query);
		$row = $result->row_array();
		$count = $row['COUNT'];

		if ($count != '0') {
		    return array(
			'IsSuccess' => false,
			'Message' => $MCH_NAME . ' sudah terdaftar.'
		    );
		}

		$this->db->trans_start();
		$query = "INSERT INTO ITOS_OP.M_MACHINE (ID_MACHINE,MCH_NAME,MCH_TYPE,MCH_SUB_TYPE,SIZE_CHASSIS,STANDARD_BCH,BG_COLOR,CREATE_USER,CREATE_DATE,ID_TERMINAL)
					  VALUES (m_machine_seq.nextval,'$MCH_NAME','$MCH_TYPE','$MCH_SUB_TYPE','$SIZE_CHASSIS','$STANDARD_BCH','$BG_COLOR','$CREATE_USER','$CREATE_DATE',".$this->gtools->terminal().")";
		$this->db->query($query);

		
		$query_1 = "INSERT INTO ITOS_BILLING.bil_stv_jenisalat (ALAT,TY_CC)
					  		  VALUES ('$MCH_NAME','$MCH_SUB_TYPE')";
		$this->db->query($query_1);



		if ($this->db->trans_complete()) {
		    return array(
			'IsSuccess' => true,
			'Message' => $MCH_NAME . ' berhasil disimpan'
		    );
		} else {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Save gagal.'
		    );
		}
    }
    
    public function check_mch($mch_name){
		$ret = 1;
		$param = array($mch_name,$this->gtools->terminal());
		$query = "SELECT COUNT(*) AS TOTAL
					    FROM M_MACHINE
					    WHERE MCH_NAME=? AND ID_TERMINAL=?";
		$rs = $this->db->query($query, $param);
		$row = $rs->row_array();
		if ($row['TOTAL'] > 0) {
		    $ret = 1;
		} else {
		    $ret = 0;
		}

		return $ret;
	    }
	    
	    public function get_mch_spec_by_id($id_machine){
			$query = "SELECT * FROM M_MACHINE WHERE ID_MACHINE=$id_machine AND ID_TERMINAL = '".$this->gtools->terminal()."'";
			return $this->db->query($query)->row_array();
	    }
    
    public function edit_mch($data){
		$MODIFY_USER	= $data['MODIFY_USER'];
		$ID_MACHINE	= $data['ID_MACHINE'];
		$MCH_NAME	= $data['MCH_NAME'];
//		$MCH_TYPE	= $data['MCH_TYPE'];
//		$MCH_SUB_TYPE	= $data['MCH_SUB_TYPE'];
		$SIZE_CHASSIS	= $data['SIZE_CHASSIS'];
		$STANDARD_BCH	= $data['STANDARD_BCH'];
		$BG_COLOR	= $data['BG_COLOR'];
		$MODIFY_DATE = date('d-M-y h:i:s A');

		if (!isset($MCH_NAME) || $MCH_NAME == '') {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Code harus diisi.'
		    );
		}
		

		$query = "SELECT * FROM ITOS_OP.M_MACHINE WHERE ID_MACHINE = '$ID_MACHINE' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$result = $this->db->query($query);
		$row = $result->row_array();
		$mch_name_old = $row['MCH_NAME'];
	//	    echo 'query : '.$query;
	//	    echo $no_pol.' : '.$no_pol_ori;exit;
		if ($MCH_NAME != $mch_name_old) {
		    $query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_MACHINE WHERE MCH_NAME = '$MCH_NAME' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		    $result = $this->db->query($query);
		    $row = $result->row_array();
		    $count = $row['COUNT'];

		    if ($count != '0') {
				return array(
				    'IsSuccess' => false,
				    'Message' => $MCH_NAME . ' sudah terdaftar.'
				);
			}
		}
	
		$this->db->trans_start();
		$query = "UPDATE ITOS_OP.M_MACHINE SET MCH_NAME='$MCH_NAME',SIZE_CHASSIS='$SIZE_CHASSIS',STANDARD_BCH='$STANDARD_BCH',BG_COLOR='$BG_COLOR',MODIFY_USER='$MODIFY_USER',MODIFY_DATE='$MODIFY_DATE' 
			WHERE ID_MACHINE=$ID_MACHINE AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$this->db->query($query);

		if ($this->db->trans_complete()) {
		    return array(
			'IsSuccess' => true,
			'Message' => $MCH_NAME . ' berhasil diubah'
		    );
		} else {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Edit gagal.'
		    );
		}
    }
    
	public function delete_machine_qc_working_plan($ID_MCH_WORKING_PLAN,$MCH_NAME){
		$query = "SELECT DISTINCT c.ID_MACHINE, c.MCH_NAME
				    FROM mch_working_plan a
					INNER JOIN mch_working_sequence b ON a.id_mch_working_plan = b.id_mch_working_plan
					INNER JOIN m_machine c ON a.id_machine = c.id_machine
			    	WHERE b.ACTIVE = 'Y' AND a.ID_MCH_WORKING_PLAN='$ID_MCH_WORKING_PLAN' AND c.ID_TERMINAL='".$this->gtools->terminal()."' AND a.id_machine <> -1 
					ORDER BY MCH_NAME";


		$response = $this->db->query($query)->num_rows();

		if($response>0)
		{
			return array(
				'IsSuccess' => false,
				'Message' => "$MCH_NAME sudah di plan!"
			);
		}
		else
		{
			$this->db->trans_start();
			$query 		= "DELETE FROM MCH_WORKING_PLAN
							WHERE ID_MCH_WORKING_PLAN = '$ID_MCH_WORKING_PLAN'";
			$this->db->query($query);
			$query_2 		= "DELETE FROM MCH_WORKING_SEQUENCE
							WHERE ID_MCH_WORKING_PLAN = '$ID_MCH_WORKING_PLAN'";
			$this->db->query($query_2);
			// debux($query.$query_2 );die();
			if ($this->db->trans_complete()){
				return array(
					'IsSuccess' => true,
					'Message' => "$MCH_NAME berhasil di hapus"
				);
			}else{
				return array(
					'IsSuccess' => true,
					'Message' => "$MCH_NAME gagal di hapus"
				);
			}
		}
	}
	
    public function delete_mch($id_machine,$mch_name){
        //cek jika mesin sudah di plan
        $qryCekMesin = "SELECT COUNT(*) TOTAL FROM MCH_PLAN WHERE ID_MACHINE=$id_machine";
        $resCekMesin = $this->db->query($qryCekMesin)->row_array();
        $qryCekMesinCWP = "SELECT COUNT(*) TOTAL FROM MCH_WORKING_PLAN WHERE ID_MACHINE=$id_machine";
        $resCekMesinCWP = $this->db->query($qryCekMesinCWP)->row_array();
        if($resCekMesin['TOTAL'] < 1 && $resCekMesinCWP['TOTAL'] < 1){
            $this->db->trans_start();
            $query = "DELETE FROM M_MACHINE WHERE ID_MACHINE=$id_machine AND  ID_TERMINAL = '".$this->gtools->terminal()."'";
            $this->db->query($query);

            if ($this->db->trans_complete()) {
                return array(
                    'IsSuccess' => true,
                    'Message' => $mch_name . ' berhasil di hapus'
                );
            } else {
                return array(
                    'IsSuccess' => false,
                    'Message' => 'Hapus gagal.'
                );
            }
        }else{
            if($resCekMesin['TOTAL'] > 0){
                return array(
                    'IsSuccess' => false,
                    'Message' => $mch_name . ' sudah di plan di Yard Equipment Plan!.'
                );
            }
            if($resCekMesinCWP['TOTAL'] > 0){
                return array(
                    'IsSuccess' => false,
                    'Message' => $mch_name . ' sudah di plan di QC Working Plan!.'
                );
            }
        }
    }
    
    public function get_equipment_deployment($id_ves_voyage){
	$qry = "SELECT A.MCH_SUB_TYPE, COUNT(C.MCH_NAME) AS TOTAL 
		FROM M_MACHINE_TYPE A
		LEFT JOIN (
			SELECT DISTINCT B.MCH_SUB_TYPE,B.MCH_NAME
			FROM JOB_QUAY_MANAGER A
			LEFT JOIN M_MACHINE B ON A.ID_MACHINE = B.ID_MACHINE
			WHERE STATUS_FLAG = 'C' AND A.ID_VES_VOYAGE = '$id_ves_voyage'
		) C ON A.MCH_SUB_TYPE = C.MCH_SUB_TYPE
		WHERE A.MCH_TYPE = 'QUAY'
		GROUP BY A.MCH_SUB_TYPE";
//	echo '<pre>'.$qry.'</pre>';exit;
	return $this->db->query($qry)->result_array();
    }
    
    public function get_detail_equipment_activity($id_ves_voyage){
		$qry = "SELECT A.MCH_NAME,SUM(CASE WHEN B.STATUS_FLAG = 'C' THEN 1 ELSE 0 END) MOVES,
					TO_CHAR (MIN(B.COMPLETE_DATE), 'YYYY/MM/DD HH24:MI') COMMENCE_WORK,
					CASE WHEN MIN(B.COMPLETE_DATE) IS NOT NULL THEN
						CASE WHEN SUM(CASE WHEN B.STATUS_FLAG = 'P' THEN 1 ELSE 0 END) > 0 
							THEN TO_CHAR (SYSDATE, 'YYYY/MM/DD HH24:MI') 
							ELSE TO_CHAR (MAX(B.COMPLETE_DATE), 'YYYY/MM/DD HH24:MI') END
					ELSE ''
					END AS CURRENT_WORK
					,SUM(CASE WHEN B.STATUS_FLAG = 'P' THEN 1 ELSE 0 END) REMAIN
					,CASE WHEN (((CASE WHEN SUM(CASE WHEN B.STATUS_FLAG = 'P' THEN 1 ELSE 0 END) > 0 THEN MAX(SYSDATE) ELSE MAX(B.COMPLETE_DATE) END)-MIN(B.COMPLETE_DATE))) > 0 
						THEN CEIL(SUM(CASE WHEN B.STATUS_FLAG = 'C' THEN 1 ELSE 0 END) / (((CASE WHEN SUM(CASE WHEN B.STATUS_FLAG = 'P' THEN 1 ELSE 0 END) > 0 THEN MAX(SYSDATE) ELSE MAX(B.COMPLETE_DATE) END)-MIN(B.COMPLETE_DATE)) * 24)) 
						ELSE 0 END AS BCH
			FROM M_MACHINE A
			INNER JOIN JOB_QUAY_MANAGER B
				ON A.ID_MACHINE = B.ID_MACHINE
			LEFT JOIN JOB_SUSPEND C 
				ON A.ID_MACHINE = C.ID_MACHINE  AND B.ID_VES_VOYAGE =C.ID_VES_VOYAGE 
			LEFT JOIN M_SUSPEND D 
				ON C.ID_SUSPEND = D.ID_SUSPEND
			WHERE A.MCH_TYPE = 'QUAY' AND B.ID_VES_VOYAGE = '$id_ves_voyage'
				AND (D.CATEGORY <> 'NOT' OR D.CATEGORY IS NULL)
			GROUP BY A.MCH_NAME";
	//	echo '<pre>'.$qry.'</pre>';exit;
		return $this->db->query($qry)->result_array();
    }
    
    public function bch_summary($id_ves_voyage){
	$qry = "SELECT SUM(CASE WHEN B.STATUS_FLAG = 'C' THEN 1 ELSE 0 END) MOVES,
		TO_CHAR (MIN(B.COMPLETE_DATE), 'YYYY/MM/DD HH24:MI') COMMENCE_WORK,
		CASE WHEN SUM(CASE WHEN B.STATUS_FLAG = 'P' THEN 1 ELSE 0 END) > 0 
			THEN TO_CHAR (SYSDATE, 'YYYY/MM/DD HH24:MI') 
			ELSE TO_CHAR (MAX(B.COMPLETE_DATE), 'YYYY/MM/DD HH24:MI') 
		END AS CURRENT_WORK
		,ROUND(((CASE WHEN SUM(CASE WHEN B.STATUS_FLAG = 'P' THEN 1 ELSE 0 END) > 0 THEN MAX(SYSDATE) ELSE MAX(B.COMPLETE_DATE) END)-MIN(B.COMPLETE_DATE)) * 24) AS WORKING_TIME
		,SUM(CASE WHEN (C.ID_CLASS_CODE = 'E' OR C.ID_CLASS_CODE = 'TE') AND B.STATUS_FLAG = 'C' THEN 1 ELSE 0 END) AS LOAD
		,SUM(CASE WHEN (C.ID_CLASS_CODE = 'I' OR C.ID_CLASS_CODE = 'TI') AND B.STATUS_FLAG = 'C' THEN 1 ELSE 0 END) AS DISCH
		,SUM(CASE WHEN B.STATUS_FLAG = 'C' THEN 1 ELSE 0 END) AS TOTAL
		,CASE WHEN ((MAX(B.COMPLETE_DATE)-MIN(B.COMPLETE_DATE)) * 24) > 0 
			THEN CEIL(COUNT(*) / (((CASE WHEN SUM(CASE WHEN B.STATUS_FLAG = 'P' THEN 1 ELSE 0 END) > 0 THEN MAX(SYSDATE) ELSE MAX(B.COMPLETE_DATE) END)-MIN(B.COMPLETE_DATE)) * 24)) 
			ELSE 0 END AS BCH
		FROM M_MACHINE A
		INNER JOIN JOB_QUAY_MANAGER B
			ON A.ID_MACHINE = B.ID_MACHINE
		LEFT JOIN CON_LISTCONT C
			ON B.NO_CONTAINER = C.NO_CONTAINER AND B.POINT = C.POINT
		WHERE A.MCH_TYPE = 'QUAY' AND B.ID_VES_VOYAGE = '$id_ves_voyage'";
	$result = $this->db->query($qry)->result_array();
	return $result[0];
    }
    
    public function data_mch_by_id($id_vesvoy, $type, $id_machine){

		$param = array($id_vesvoy, $type);
		$table = '';
		if ($type=='I'){
			$table = ' CON_INBOUND_SEQUENCE ';
		} else if ($type=='E') {
			$table = ' CON_OUTBOUND_SEQUENCE ';
		}
		
		$query = "SELECT 
					   G.*,
					   F.MCH_NAME MACHINE, F.ID_MACHINE,
					   CASE WHEN REMAIN = 0 THEN 'FINISH' ELSE F.ESTIMATE_TIME END AS ESTIMATE_TIME,
					   F.ACTIVE,
					   TOTAL - REMAIN AS SUMDISCHLOAD,
					   F.SEQUENCE
				  FROM    (  SELECT BAY_ AS BAY,
									DECK_HATCH AS LOCATION,
									COUNT (TWENTY) TWENTY,
									COUNT (FOURTY) FOURTY,
									COUNT (TWENTY) + COUNT (FOURTY) TOTAL,
									SUM (REMAIN) REMAIN,
									ID_VES_VOYAGE
							   FROM (SELECT A.NO_CONTAINER,
											A.ID_VES_VOYAGE,
											'1' AS TWENTY,
											'' AS FOURTY,
											DECK_HATCH,
											A.BAY_,
											A.ID_BAY,
											DECODE (STATUS, 'P', 1, 0) REMAIN
									   FROM    ".$table." A
											INNER JOIN
											   CON_LISTCONT B
											ON (A.NO_CONTAINER = B.NO_CONTAINER
												AND A.POINT = B.POINT)
									  WHERE B.CONT_SIZE IN ('20','21')
									 UNION
									 SELECT A.NO_CONTAINER,
											A.ID_VES_VOYAGE,
											'' AS TWENTY,
											'1' AS FOURTY,
											DECK_HATCH,
											A.BAY_ BAY_,
											A.ID_BAY,
											DECODE (STATUS, 'P', 1, 0) REMAIN
									   FROM    ".$table." A
											INNER JOIN
											   CON_LISTCONT B
											ON (A.NO_CONTAINER = B.NO_CONTAINER
												AND A.POINT = B.POINT)
									  WHERE B.CONT_SIZE IN ('40','45'))
							  WHERE ID_VES_VOYAGE = '$id_vesvoy'
						   GROUP BY BAY_, DECK_HATCH, ID_VES_VOYAGE
						   ORDER BY BAY_, DECK_HATCH, ID_VES_VOYAGE) G
					   LEFT JOIN
						  (SELECT C.SEQUENCE,
								  C.BAY,
								  C.DECK_HATCH,
								  C.ACTIVE,
								  D.ID_VES_VOYAGE,
								  E.MCH_NAME,
								  E.ID_MACHINE,
								  TO_CHAR (C.ESTIMATE_TIME, 'DD-MM-YY HH24:MI') ESTIMATE_TIME
							 FROM MCH_WORKING_SEQUENCE C
								  INNER JOIN MCH_WORKING_PLAN D
									 ON (C.ID_MCH_WORKING_PLAN = D.ID_MCH_WORKING_PLAN)
								  INNER JOIN M_MACHINE E
									 ON (D.ID_MACHINE = E.ID_MACHINE)
							WHERE C.ACTIVITY = '$type') F
					   ON (    F.ID_VES_VOYAGE = G.ID_VES_VOYAGE
						   AND F.BAY = G.BAY
						   AND F.DECK_HATCH = G.LOCATION)
				WHERE F.ID_MACHINE='$id_machine'
				ORDER BY G.BAY, G.LOCATION";

		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
                
		return $data;
	}

	public function getDataCrane($id_ves_voyage){
		/*
		$query = "SELECT
					A.ID_VES_VOYAGE,
					A.ID_MACHINE,
					A.MCH_NAME,
					TO_CHAR(START_WORK,'DD-MM-RRRR HH24:MI') START_WORK,
					TO_CHAR(END_WORK,'DD-MM-RRRR HH24:MI') END_WORK,
					ROUND((END_WORK - START_WORK) * 24,1) AS GROSS_H,
					(SELECT COUNT (*)
					   FROM CON_LISTCONT
					    WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND ID_TERMINAL = '".$this->gtools->terminal()."' AND ID_OP_STATUS <> 'DIS'
					AND ID_CLASS_CODE IN ('E','TE')
					AND QC_PLAN = A.MCH_NAME) AS TOTAL_LOAD,
					(SELECT COUNT (*)
				   FROM CON_LISTCONT
				    WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND ID_TERMINAL = '".$this->gtools->terminal()."' AND ID_OP_STATUS <> 'DIS'
					AND ID_CLASS_CODE IN ('I','TI','TC')
					AND QC_PLAN = A.MCH_NAME) AS TOTAL_DISC,
					(SELECT COUNT (*)
						FROM CON_LISTCONT C
						INNER JOIN JOB_QUAY_MANAGER B ON C.NO_CONTAINER = B.NO_CONTAINER AND C.ID_VES_VOYAGE = B.ID_VES_VOYAGE
						WHERE TRIM(C.ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND C.ID_TERMINAL='".$this->gtools->terminal()."' AND C.QC_PLAN = A.MCH_NAME AND B.STATUS_FLAG = 'C' AND C.ID_CLASS_CODE IN ('I','TI','TC')) AS COMPLETE_DISC,
					(SELECT COUNT (*)
						FROM CON_LISTCONT C
						INNER JOIN JOB_QUAY_MANAGER B ON C.NO_CONTAINER = B.NO_CONTAINER AND C.ID_VES_VOYAGE = B.ID_VES_VOYAGE
						WHERE TRIM(C.ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND C.ID_TERMINAL='".$this->gtools->terminal()."' AND C.QC_PLAN = A.MCH_NAME AND B.STATUS_FLAG = 'C' AND C.ID_CLASS_CODE IN ('E','TE')) AS COMPLETE_LOAD,
					(SELECT SUM(A.JUMLAH)
						FROM ITOS_REPO.M_HATCH_MOVE A
						INNER JOIN ITOS_OP.VES_VOYAGE B ON A.VESSEL=B.VESSEL_NAME AND A.VOYAGE_IN=B.VOY_IN AND A.VOYAGE_OUT=B.VOY_OUT
						WHERE B.ID_TERMINAL = '".$this->gtools->terminal()."' AND TRIM(B.ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND A.ALAT = A.MCH_NAME) AS TOTAL_HATCH
				FROM
					MCH_WORKING_PLAN A
				WHERE
					ID_VES_VOYAGE = '".$id_ves_voyage."'";
		*/
//		$query = "SELECT
//					A.ID_VES_VOYAGE,
//					A.ID_MACHINE,
//					A.MCH_NAME,
//					(SELECT TO_CHAR(MIN(CONFIRM_DATE),'DD-MM-YYYY HH24:MI') FROM CON_LISTCONT WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND QC_REAL = A.MCH_NAME AND ID_TERMINAL = '".$this->gtools->terminal()."') START_WORK,
//					(SELECT TO_CHAR(MAX(CONFIRM_DATE),'DD-MM-YYYY HH24:MI') FROM CON_LISTCONT WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND QC_REAL = A.MCH_NAME AND ID_TERMINAL = '".$this->gtools->terminal()."') END_WORK,
//					(SELECT TO_CHAR(MIN(CONFIRM_DATE),'DD-MM-YYYY HH24:MI') FROM CON_LISTCONT WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND QC_REAL = A.MCH_NAME AND ID_CLASS_CODE IN ('I','TI') AND ID_TERMINAL = '".$this->gtools->terminal()."') START_WORK_DISC,
//					(SELECT TO_CHAR(MAX(CONFIRM_DATE),'DD-MM-YYYY HH24:MI') FROM CON_LISTCONT WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND QC_REAL = A.MCH_NAME AND ID_CLASS_CODE IN ('I','TI') AND ID_TERMINAL = '".$this->gtools->terminal()."') END_WORK_DISC,
//					(SELECT TO_CHAR(MIN(CONFIRM_DATE),'DD-MM-YYYY HH24:MI') FROM CON_LISTCONT WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND QC_REAL = A.MCH_NAME AND ID_CLASS_CODE IN ('E','TE') AND ID_TERMINAL = '".$this->gtools->terminal()."') START_WORK_LOAD,
//					(SELECT TO_CHAR(MAX(CONFIRM_DATE),'DD-MM-YYYY HH24:MI') FROM CON_LISTCONT WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND QC_REAL = A.MCH_NAME AND ID_CLASS_CODE IN ('E','TE') AND ID_TERMINAL = '".$this->gtools->terminal()."') END_WORK_LOAD,
//					(SELECT COUNT(*)
//                       	FROM CON_LISTCONT C
//                       	LEFT JOIN ITOS_BILLING.REQ_RECEIVING_D D ON D.NO_CONTAINER = C.NO_CONTAINER
//						LEFT JOIN ITOS_BILLING.REQ_RECEIVING_H E ON E.ID_REQ = D.NO_REQ_ANNE
//						JOIN CON_OUTBOUND_SEQUENCE F ON C.NO_CONTAINER = F.NO_CONTAINER
//                        WHERE TRIM(C.ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) 
//                        AND C.ID_OP_STATUS <> 'DIS'
//                        AND C.ID_CLASS_CODE IN ('E','TE','S1','S2')
//                       	AND (E.STATUS IN ('P','T') OR (CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
//							    THEN C.POINT ELSE 1 END != CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
//						    THEN (SELECT POINT FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) ELSE 0 END))
//                       	AND C.ID_TERMINAL='".$this->gtools->terminal()."'
//                       	AND F.ID_TERMINAL='".$this->gtools->terminal()."') AS TOTAL_LOAD,
//					(SELECT COUNT (*)
//		                  FROM CON_LISTCONT C
//		                  WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND ID_TERMINAL='103' AND ID_OP_STATUS <> 'DIS'
//		                  AND ID_CLASS_CODE IN ('I','TI','S1','S2')
//						  AND CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
//						  THEN C.POINT ELSE 1 END = CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
//					      THEN (SELECT POINT FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) ELSE 1 END) AS TOTAL_DISC,
//					(SELECT COUNT(*) 
//		                       FROM CON_INBOUND_SEQUENCE CO
//						       INNER JOIN CON_LISTCONT B
//						       	ON CO.NO_CONTAINER = B.NO_CONTAINER AND CO.POINT = B.POINT AND CO.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND CO.ID_TERMINAL = B.ID_TERMINAL
//		                        WHERE TRIM(CO.ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND CO.ID_TERMINAL='".$this->gtools->terminal()."' AND B.ID_CLASS_CODE IN ('I','TI','S1','S2') AND B.ID_OP_STATUS <> 'DIS'
//	                        	AND CO.STATUS = 'C') AS COMPLETE_DISC,
//					(SELECT COUNT(*) 
//                       FROM CON_OUTBOUND_SEQUENCE
//                        WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE)
//                        AND STATUS = 'C' AND ID_TERMINAL='".$this->gtools->terminal()."') AS COMPLETE_LOAD,
//					(SELECT NVL(SUM(NUM_HATCH),0) FROM JOB_HATCH WHERE ID_VES_VOYAGE = A.ID_VES_VOYAGE AND ID_MACHINE = A.ID_MACHINE ) AS TOTAL_HATCH
//					FROM
//						MCH_WORKING_PLAN A
//					WHERE
//						A.ID_VES_VOYAGE = '$id_ves_voyage'";
		
		// UPDATE BY YAZIR
		$query = "SELECT MWP.ID_VES_VOYAGE,MWP.ID_MACHINE,MWP.MCH_NAME
					,TO_CHAR(MIN(DL.COMPLETE_DATE),'DD-MM-YYYY HH24:MI') AS COMMENCE_OPERATION
					,TO_CHAR(MAX(DL.COMPLETE_DATE),'DD-MM-YYYY HH24:MI') AS COMPLETE_OPERATION
					,TO_CHAR(MIN(CASE WHEN DL.ACTIVITY = 'D' THEN DL.COMPLETE_DATE ELSE NULL END),'DD-MM-YYYY HH24:MI') AS START_WORK_DISC
					,TO_CHAR(MAX(CASE WHEN DL.ACTIVITY = 'D' THEN DL.COMPLETE_DATE ELSE NULL END),'DD-MM-YYYY HH24:MI') AS END_WORK_DISC
					,SUM(CASE WHEN DL.ACTIVITY = 'D' THEN 1 ELSE 0 END) AS PLANNED_DISC
					,SUM(CASE WHEN DL.ACTIVITY = 'D' AND DL.STATUS_FLAG = 'C' THEN 1 ELSE 0 END) AS COMPLETE_DISC
					,TO_CHAR(MIN(CASE WHEN DL.ACTIVITY = 'L' THEN DL.COMPLETE_DATE ELSE NULL END),'DD-MM-YYYY HH24:MI') AS START_WORK_LOAD
					,TO_CHAR(MAX(CASE WHEN DL.ACTIVITY = 'L' THEN DL.COMPLETE_DATE ELSE NULL END),'DD-MM-YYYY HH24:MI') AS END_WORK_LOAD
					,SUM(CASE WHEN DL.ACTIVITY = 'L' THEN 1 ELSE 0 END) AS PLANNED_LOAD
					,SUM(CASE WHEN DL.ACTIVITY = 'L'  AND DL.STATUS_FLAG = 'C' THEN 1 ELSE 0 END) AS COMPLETE_LOAD
					,NVL((SELECT SUM(JUMLAH) FROM ITOS_REPO.M_HATCH_MOVE MHM JOIN ITOS_REPO.M_VSB_VOYAGE MVV ON MHM.VESSEL = MVV.VESSEL AND MHM.VOYAGE_IN = MVV.VOYAGE_IN WHERE MVV.UKKS = MWP.ID_VES_VOYAGE AND MHM.ALAT = MWP.MCH_NAME),0) AS TOTAL_HATCH
			FROM MCH_WORKING_PLAN MWP
			LEFT JOIN (SELECT CI.ID_VES_VOYAGE,JQI.ID_MACHINE,JQI.COMPLETE_DATE,JQI.STATUS_FLAG,CI.STATUS,'D' AS ACTIVITY FROM CON_INBOUND_SEQUENCE CI
						INNER JOIN CON_LISTCONT C ON CI.NO_CONTAINER = C.NO_CONTAINER AND CI.POINT = C.POINT AND CI.ID_TERMINAL = C.ID_TERMINAL
						LEFT JOIN JOB_QUAY_MANAGER JQI ON JQI.NO_CONTAINER = CI.NO_CONTAINER AND JQI.POINT = CI.POINT AND CI.ID_TERMINAL = JQI.ID_TERMINAL
						WHERE CI.ID_VES_VOYAGE = '$id_ves_voyage' AND C.ID_OP_STATUS <> 'DIS' AND CI.ID_TERMINAL = '".$this->gtools->terminal()."'
						UNION ALL
						SELECT CO.ID_VES_VOYAGE,JQO.ID_MACHINE,JQO.COMPLETE_DATE,JQO.STATUS_FLAG,CO.STATUS,'L' AS ACTIVITY FROM CON_OUTBOUND_SEQUENCE CO
						INNER JOIN CON_LISTCONT C ON CO.NO_CONTAINER = C.NO_CONTAINER AND CO.POINT = C.POINT AND CO.ID_TERMINAL = C.ID_TERMINAL
						LEFT JOIN JOB_QUAY_MANAGER JQO ON JQO.NO_CONTAINER = CO.NO_CONTAINER AND JQO.POINT = CO.POINT AND CO.ID_TERMINAL = JQO.ID_TERMINAL
						WHERE CO.ID_VES_VOYAGE = '$id_ves_voyage' AND C.ID_OP_STATUS <> 'DIS' AND CO.ID_TERMINAL = '".$this->gtools->terminal()."') DL
				ON MWP.ID_VES_VOYAGE = DL.ID_VES_VOYAGE AND MWP.ID_MACHINE = DL.ID_MACHINE
			WHERE MWP.ID_VES_VOYAGE = '$id_ves_voyage'
			GROUP BY MWP.ID_VES_VOYAGE,MWP.ID_MACHINE,MWP.MCH_NAME
			ORDER BY ID_VES_VOYAGE,MCH_NAME";
//		 debux($query);die;
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
                
		return $data;
	}

	public function getSuspendDetail($id_ves_voyage){
		$query = "SELECT
						C.MCH_NAME,
						A.ID_SUSPEND,
						B.ACTIVITY,
						TO_CHAR(START_SUSPEND,'DD-MM-RRRR') START_DATE,
						TO_CHAR(START_SUSPEND,'HH24:MI') START_TIME,
						TO_CHAR(END_SUSPEND,'DD-MM-RRRR') END_DATE,
						TO_CHAR(END_SUSPEND,'HH24:MI') END_TIME,
						ROUND((A.END_SUSPEND - A.START_SUSPEND) * 24 * 60,0) AS OUTAGE
					FROM JOB_SUSPEND A
					INNER JOIN M_SUSPEND B ON B.ID_SUSPEND=A.ID_SUSPEND
					INNER JOIN M_MACHINE C ON C.ID_MACHINE=A.ID_MACHINE
					WHERE A.ID_VES_VOYAGE = '$id_ves_voyage'
					ORDER BY A.START_SUSPEND";

		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
                
		return $data;
	}

	public function getSuspendSummary($id_ves_voyage,$mch){
		$query = "SELECT
					C.MCH_NAME,
					A.ID_SUSPEND,
					B.ACTIVITY,
				    SUM(ROUND((A.END_SUSPEND - A.START_SUSPEND) * 24 * 60,0)) AS DIFF_MINUTES
				FROM JOB_SUSPEND A
				INNER JOIN M_SUSPEND B ON B.ID_SUSPEND=A.ID_SUSPEND
				INNER JOIN M_MACHINE C ON C.ID_MACHINE=A.ID_MACHINE
				WHERE A.ID_VES_VOYAGE = '$id_ves_voyage' AND C.MCH_NAME = '$mch'
				GROUP BY C.MCH_NAME,
					A.ID_SUSPEND,
					B.ACTIVITY
				ORDER BY C.MCH_NAME";

		$rs = $this->db->query($query);
		$data = $rs->result_array();
        
		return $data;
	}

	public function getTotalOutage($id_ves_voyage,$mch){
		$query = "SELECT
						C.MCH_NAME,
					    SUM(ROUND((A.END_SUSPEND - A.START_SUSPEND) * 24 * 60,0)) AS TOTAL_OUTAGE
					FROM JOB_SUSPEND A
					INNER JOIN M_SUSPEND B ON B.ID_SUSPEND=A.ID_SUSPEND
					INNER JOIN M_MACHINE C ON C.ID_MACHINE=A.ID_MACHINE
					WHERE A.ID_VES_VOYAGE = '$id_ves_voyage' AND C.MCH_NAME = '$mch'
					GROUP BY C.MCH_NAME";

		$rs = $this->db->query($query);
		$data = $rs->row();
        
		return $data;
	}

	public function getDataCraneByVesvoy($id_ves_voyage){
		$query = "SELECT
						B.MCH_NAME
					FROM JOB_SUSPEND A
					INNER JOIN M_MACHINE B ON B.ID_MACHINE=A.ID_MACHINE
					WHERE A.ID_VES_VOYAGE = '$id_ves_voyage'
					GROUP BY B.MCH_NAME
					ORDER BY B.MCH_NAME";

		$rs = $this->db->query($query);
		$data = $rs->result_array();
        
		return $data;
	}

	public function getCraneByVesvoy($id_ves_voyage){
		$query = "SELECT MCH_NAME
   					 FROM MCH_WORKING_PLAN 
    				WHERE ID_MACHINE<>-1 AND TRIM(ID_VES_VOYAGE) = TRIM('$id_ves_voyage')";

		$rs = $this->db->query($query);
		$data = $rs->result_array();
        
		return $data;
	}

	public function getSummaryCrane($id_ves_voyage,$mch){
		/*$query = "SELECT
					A.ID_VES_VOYAGE,
					A.ID_MACHINE,
					A.MCH_NAME,
					TO_CHAR(START_WORK,'DD-MM-RRRR HH24:MI') START_WORK,
					TO_CHAR(END_WORK,'DD-MM-RRRR HH24:MI') END_WORK,
					ROUND((END_WORK - START_WORK) * 24,1) AS GROSS_H,
					(SELECT COUNT(*)
                       	FROM CON_LISTCONT C
                       	LEFT JOIN ITOS_BILLING.REQ_RECEIVING_D D ON D.NO_CONTAINER = C.NO_CONTAINER
						LEFT JOIN ITOS_BILLING.REQ_RECEIVING_H E ON E.ID_REQ = D.NO_REQ_ANNE
						JOIN CON_OUTBOUND_SEQUENCE F ON C.NO_CONTAINER = F.NO_CONTAINER
                        WHERE TRIM(C.ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) 
                        AND C.ID_OP_STATUS <> 'DIS'
                        AND C.ID_CLASS_CODE IN ('E','TE','S1','S2')
                       	AND (E.STATUS IN ('P','T') OR (CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
							    THEN C.POINT ELSE 1 END != CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
						    THEN (SELECT POINT FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) ELSE 0 END))
                       	AND C.ID_TERMINAL='".$this->gtools->terminal()."'
                       	AND F.ID_TERMINAL='".$this->gtools->terminal()."') AS TOTAL_LOAD,
					(SELECT COUNT (*)
		                  FROM CON_LISTCONT C
		                  WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND ID_TERMINAL='".$this->gtools->terminal()."' AND ID_OP_STATUS <> 'DIS'
		                  AND ID_CLASS_CODE IN ('I','TI','S1','S2')
						  AND CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
						  THEN C.POINT ELSE 1 END = CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
					      THEN (SELECT POINT FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) ELSE 1 END) AS TOTAL_DISC,
					(SELECT COUNT(*) 
		                       FROM CON_INBOUND_SEQUENCE CO
						       INNER JOIN CON_LISTCONT B
						       	ON CO.NO_CONTAINER = B.NO_CONTAINER AND CO.POINT = B.POINT AND CO.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND CO.ID_TERMINAL = B.ID_TERMINAL
		                        WHERE TRIM(CO.ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND CO.ID_TERMINAL='".$this->gtools->terminal()."' AND B.ID_CLASS_CODE IN ('I','TI','S1','S2') AND B.ID_OP_STATUS <> 'DIS'
	                        	AND CO.STATUS = 'C') AS COMPLETE_DISC,
	                (SELECT COUNT(*) 
                       FROM CON_OUTBOUND_SEQUENCE
                        WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE)
                        AND STATUS = 'C' AND ID_TERMINAL='".$this->gtools->terminal()."') AS COMPLETE_LOAD
					FROM
						MCH_WORKING_PLAN A
					WHERE
						A.ID_VES_VOYAGE = '$id_ves_voyage' AND A.MCH_NAME = '$mch'";
*/
			$query = "SELECT
						A.ID_VES_VOYAGE,
						A.ID_MACHINE,
						A.MCH_NAME,
						TO_CHAR(START_WORK,'DD-MM-RRRR HH24:MI') START_WORK,
						TO_CHAR(END_WORK,'DD-MM-RRRR HH24:MI') END_WORK,
						(SELECT COUNT(*)
	                       	FROM CON_LISTCONT C
	                       	LEFT JOIN ITOS_BILLING.REQ_RECEIVING_D D ON D.NO_CONTAINER = C.NO_CONTAINER
							LEFT JOIN ITOS_BILLING.REQ_RECEIVING_H E ON E.ID_REQ = D.NO_REQ_ANNE
							JOIN CON_OUTBOUND_SEQUENCE F ON C.NO_CONTAINER = F.NO_CONTAINER
	                        WHERE TRIM(C.ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE)
	                        AND C.QC_REAL = A.MCH_NAME
	                        AND C.ID_OP_STATUS <> 'DIS'
	                        AND C.ID_CLASS_CODE IN ('E','TE','S1','S2')
	                       	AND (E.STATUS IN ('P','T') OR (CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
								    THEN C.POINT ELSE 1 END != CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
							    THEN (SELECT POINT FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) ELSE 0 END))
	                       	AND C.ID_TERMINAL='".$this->gtools->terminal()."'
	                       	AND F.ID_TERMINAL='".$this->gtools->terminal()."') AS TOTAL_LOAD,
						(SELECT COUNT (*)
			       				FROM CON_LISTCONT C
			                    WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND C.QC_REAL = A.MCH_NAME AND ID_TERMINAL='".$this->gtools->terminal()."' AND ID_OP_STATUS <> 'DIS'
			                        AND ID_CLASS_CODE IN ('I','TI','S1','S2') AND VS_BAY IS NOT NULL
									AND CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2')
									AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
							THEN C.POINT ELSE 1 END = CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
						THEN (SELECT POINT FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) ELSE 1 END) AS TOTAL_DISC,
						(SELECT COUNT(*) 
			                       FROM CON_INBOUND_SEQUENCE CO
							       INNER JOIN CON_LISTCONT B
							       	ON CO.NO_CONTAINER = B.NO_CONTAINER AND CO.POINT = B.POINT AND CO.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND CO.ID_TERMINAL = B.ID_TERMINAL
			                        WHERE TRIM(CO.ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND B.QC_REAL = A.MCH_NAME AND CO.ID_TERMINAL='".$this->gtools->terminal()."' AND B.ID_CLASS_CODE IN ('I','TI','S1','S2') AND B.ID_OP_STATUS <> 'DIS'
		                        	AND CO.STATUS = 'C') AS COMPLETE_DISC,
		                (SELECT COUNT(*) 
	                       FROM CON_OUTBOUND_SEQUENCE CO
	                       INNER JOIN CON_LISTCONT B ON CO.NO_CONTAINER = B.NO_CONTAINER AND CO.POINT = B.POINT AND CO.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND CO.ID_TERMINAL = B.ID_TERMINAL
	                        WHERE TRIM(CO.ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE)
	                        AND B.QC_REAL = A.MCH_NAME AND CO.ID_TERMINAL='".$this->gtools->terminal()."' AND B.ID_CLASS_CODE IN ('E','TE','S1','S2') AND B.ID_OP_STATUS <> 'DIS'
		                        	AND CO.STATUS = 'C') AS COMPLETE_LOAD
						FROM
							MCH_WORKING_PLAN A
						WHERE
							A.ID_VES_VOYAGE = '$id_ves_voyage' 
							AND A.MCH_NAME = '$mch'
					";

		//debux($query);die;
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
                
		return $data;
	}
	
}
?>