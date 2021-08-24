<?php

class Master extends CI_Model {

    public function __construct() {
	$this->load->database();
    }

    //Master PORT
    /*
      public function get_port(){
      $query 		= "SELECT * FROM M_PORT WHERE PORT_COUNTRY = 'ID'";
      $rs 		= $this->db->query($query);
      $data 		= $rs->result_array();

      return $data;
      } */

    public function get_port($paging = false, $sort = false, $filters = false) {
	$qPaging = '';
	if ($paging != false) {
	    $start = $paging['start'] + 1;
	    $end = $paging['page'] * $paging['limit'];
	    $qPaging = "WHERE REC_NUM >= $start AND REC_NUM <= $end";
	}
	$qSort = '';
	if ($sort != false) {
	    $sortProperty = $sort[0]->property;
	    $sortDirection = $sort[0]->direction;
	    if ($sortProperty == 'PORT_CODE') {
		$sortProperty = 'PORT_CODE';
	    }
	    if ($sortProperty == 'PORT_NAME') {
		$sortProperty = 'PORT_NAME';
	    }
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
		    case'PORT_CODE' : $field = "$field";
			break;
		    case'PORT_NAME' : $field = "$field";
			break;
		}

		switch ($filterType) {
		    case 'string' : $qs .= " AND " . $field . " LIKE '%" . strtoupper($value) . "%'";
			Break;
		    case 'list' :
			if (strstr($value, ',')) {
			    $fi = explode(',', $value);
			    for ($q = 0; $q < count($fi); $q++) {
				$fi[$q] = "'" . $fi[$q] . "'";
			    }
			    $value = implode(',', $fi);
			    $qs .= " AND " . $field . " IN (" . strtoupper($value) . ")";
			} else {
			    $qs .= " AND " . $field . " = '" . strtoupper($value) . "'";
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
	$query = "SELECT * FROM (SELECT PORT_CODE, PORT_COUNTRY, PORT_NAME, PORT_PURECODE, IS_ACTIVE, FOREGROUND_COLOR, BACKGROUND_COLOR, ROWNUM REC_NUM FROM M_PORT $qWhere AND PORT_COUNTRY = 'ID' $qSort) $qPaging";

	$rs = $this->db->query($query);
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

	$query_count = "SELECT COUNT(*) AS TOTAL FROM M_PORT $qWhere AND PORT_COUNTRY = 'ID'";
	$rs = $this->db->query($query_count);
	$row = $rs->row_array();
	$total = $row['TOTAL'];

	$data = array(
	    'total' => $total,
	    'data' => $operator_list
	);
	return $data;
    }

    public function get_port_by_code($port_code) {
	$this->db->from('M_PORT');
	$this->db->where('PORT_CODE', strtoupper($port_code));
	return $this->db->get()->row();
    }

    public function edit_port($data) {
		$port_code = $data['port_code'];
		$port_name = $data['port_name'];
		$foreground = $data['foreground'];
		$background = $data['background'];
		$is_active = $data['is_active'];

		if (!isset($port_code) || $port_code == '') {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Port code harus diisi.'
		    );
		}
		if (!isset($port_name) || $port_name == '') {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Port name harus diisi.'
		    );
		}
		//$port_code = "ID".$port_code;
		/* if(strlen($port_code) != 5){
		  return array(
		  'IsSuccess'=>false,
		  'Message'=>'Port code harus 3 karakter.'
		  );
		  } */
		if (strlen($port_name) > 49) {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Port name maksimum harus 50 karakter.'
		    );
		}
	$this->db->trans_start();
		$query = $this->db->query("UPDATE ITOS_OP.M_PORT SET 
										PORT_NAME = '$port_name',
										FOREGROUND_COLOR	= '$foreground',
										BACKGROUND_COLOR	= '$background',
										is_active 	= '$is_active'
									WHERE PORT_CODE = '$port_code'");

		if ($this->db->trans_complete()) {
		    return array(
			'IsSuccess' => true,
			'Message' => $port_name . ' berhasil diupdate'
		    );
		} else {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Update gagal.'
		    );
		}
    }

    public function save_port($data) {

	$id_user = $data['id_user'];
	$port_code = $data['port_code'];
	$port_name = $data['port_name'];
	$foreground = $data['foreground'];
	$background = $data['background'];
	$is_active = $data['is_active'];

	if (!isset($port_code) || $port_code == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Port code harus diisi.'
	    );
	}
	if (!isset($port_name) || $port_name == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Port name harus diisi.'
	    );
	}
	$port_code = "ID" . $port_code;
	if (strlen($port_code) != 5) {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Port code harus 5 karakter.'
	    );
	}
	if (strlen($port_name) > 49) {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Port name maksimum harus 50 karakter.'
	    );
	}

	$query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_PORT WHERE PORT_CODE = '$port_code'";
	$result = $this->db->query($query);
	$row = $result->row_array();
	$count_port_code = $row['COUNT'];

	if ($count_port_code != '0') {
	    return array(
		'IsSuccess' => false,
		'Message' => $port_code . ' sudah ada.'
	    );
	}

	$port_name = $port_name . ', INDONESIA';
	$port_country = substr($port_code, 0, 2);
	$port_purecode = substr($port_code, 2, 3);
	$this->db->trans_start();
	$query = "INSERT INTO ITOS_OP.M_PORT (PORT_CODE, PORT_NAME, PORT_COUNTRY, PORT_PURECODE, FOREGROUND_COLOR, BACKGROUND_COLOR , IS_ACTIVE)
				  VALUES ('$port_code',
				  '$port_name',
				  '$port_country',
				  '$port_purecode',
				  '$foreground',
				  '$background',
				  '$is_active')";
	$this->db->query($query);

	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $port_name . ' berhasil disimpan'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Save gagal.'
	    );
	}
    }

    public function enabled_or_disabled_port($port_code, $isActive = 'Y') {
	$query = "UPDATE M_PORT SET IS_ACTIVE = '$isActive' WHERE PORT_CODE = '$port_code'";
	$rs = $this->db->query($query);
	/* $data 		= $rs->result_array(); */

	if ($this->db->affected_rows() > 0) {
	    return 1;
	} else {
	    return 0;
	}
    }

    //Master OPERATOR
    public function get_operator($paging = false, $sort = false, $filters = false) {
	$qPaging = '';
	if ($paging != false) {
	    $start = $paging['start'] + 1;
	    $end = $paging['page'] * $paging['limit'];
	    $qPaging = "WHERE REC_NUM >= $start AND REC_NUM <= $end";
	}
	$qSort = " ORDER BY ID_OPERATOR ASC ";
	if ($sort != false) {
	    $sortProperty = $sort[0]->property;
	    $sortDirection = $sort[0]->direction;
	    if ($sortProperty == 'ID_OPERATOR') {
		$sortProperty = 'ID_OPERATOR';
	    }
	    if ($sortProperty == 'OPERATOR_NAME') {
		$sortProperty = 'OPERATOR_NAME';
	    }
	    $qSort .= ", " . $sortProperty . " " . $sortDirection;
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
		    case'ID_OPERATOR' : $field = "$field";
			break;
		    case'OPERATOR_NAME' : $field = "$field";
			break;
		}

		switch ($filterType) {
		    case 'string' : $qs .= " AND LOWER(" . $field . ") LIKE LOWER('%" . strtolower($value) . "%')";
			Break;
		    case 'list' :
			if (strstr($value, ',')) {
			    $fi = explode(',', $value);
			    for ($q = 0; $q < count($fi); $q++) {
				$fi[$q] = "'" . $fi[$q] . "'";
			    }
			    $value = implode(',', $fi);
			    $qs .= " AND " . $field . " IN (" . strtoupper($value) . ")";
			} else {
			    $qs .= " AND " . $field . " = '" . strtoupper($value) . "'";
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
	$query = "SELECT * FROM (SELECT ID_OPERATOR, OPERATOR_NAME, IS_ACTIVE, row_number() over ($qSort) REC_NUM FROM M_OPERATOR $qWhere $qSort) $qPaging";
//		echo '<pre>'.$query.'</pre>';exit;
	$rs = $this->db->query($query);
	$operator_list = $rs->result_array();

	$query_count = "SELECT COUNT(*) TOTAL FROM M_OPERATOR $qWhere";
	$rs = $this->db->query($query_count);
	$row = $rs->row_array();
	$total = $row['TOTAL'];

	$data = array(
	    'total' => $total,
	    'data' => $operator_list
	);

	return $data;
    }

    public function save_operator($id_operator, $operator_name, $id_user) {

	if (!isset($id_operator) || $id_operator == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Id operator harus diisi.'
	    );
	}
	if (!isset($operator_name) || $operator_name == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Operator name harus diisi.'
	    );
	}
	if (strlen($id_operator) > 10) {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Id operator maximal 10 karakter.'
	    );
	}
	if (strlen($operator_name) > 49) {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Operator name maksimum harus 50 karakter.'
	    );
	}

	$query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_OPERATOR WHERE ID_OPERATOR = '$id_operator'";
	$result = $this->db->query($query);
	$row = $result->row_array();
	$count_id_operator = $row['COUNT'];

	if ($count_id_operator != '0') {
	    return array(
		'IsSuccess' => false,
		'Message' => $id_operator . ' sudah ada.'
	    );
	}

	$this->db->trans_start();
	$query = "INSERT INTO ITOS_OP.M_OPERATOR (ID_OPERATOR, OPERATOR_NAME, ID_USER) VALUES ('$id_operator', '$operator_name', '$id_user')";
	$this->db->query($query);

	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $operator_name . ' berhasil disimpan'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Save gagal.'
	    );
	}
    }

    public function enabled_or_disabled_operator($id_operator, $isActive = 'Y') {
	$query = "UPDATE M_OPERATOR SET IS_ACTIVE = '$isActive' WHERE ID_OPERATOR = '$id_operator'";
	$rs = $this->db->query($query);
	$data = $rs->result_array();

	return $data;
    }

    public function getDataMvOrder() {
	$query = "SELECT HKP_ACTIVITY,HKP_ACTIVITY_DESC FROM M_HKP_ACTIVITY";
	$rs = $this->db->query($query);
	$data = $rs->result_array();

	return $data;
    }

    public function getDataMvCrane() {
		$query = "SELECT ID_MACHINE, MCH_NAME FROM M_MACHINE WHERE MCH_SUB_TYPE='VC' AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query);
		$data = $rs->result_array();

		return $data;
    }

    //=====================================
    //======================================
    //==Edit by mustadio_gun
    //==06-06-2017

    public function getMachineList() {

		// $query = "SELECT MCH_NAME, MCH_SUB_TYPE
		// FROM M_MACHINE
		// WHERE ORDER BY MCH_TYPE='ITV' TO_NUMBER(ID_MACHINE) ASC";
		// $rs 		= $this->db->query($query);
		// $data 		= $rs->result_array();
		// return $data;
		// if($id=='null'){
		// $comment='';
		// }
		// else
		// {
		// $comment="where VESSEL_SERVICE_NAME like '%$id%'";
		// }

		$query = "SELECT A.MCH_NAME, A.MCH_TYPE, C.FULL_NAME, B.IS_ACTIVE, B.START_ACTIVE, B.END_ACTIVE
					FROM M_MACHINE A
					INNER JOIN JOB_MACHINE_OPERATOR B ON A.ID_MACHINE = B.ID_MACHINE
					INNER JOIN M_USERS C ON B.ID_USER = C.ID_USER
	                WHERE A.MCH_TYPE='ITV' AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND B.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_TERMINAL='".$this->gtools->terminal()."'
					AND B.START_ACTIVE = (SELECT MAX(D.START_ACTIVE) FROM JOB_MACHINE_OPERATOR D WHERE D.ID_MACHINE=B.ID_MACHINE AND D.ID_TERMINAL='".$this->gtools->terminal()."')
					ORDER BY A.MCH_NAME ASC";

	// $query="SELECT A.MCH_NAME, A.MCH_TYPE,  B.IS_ACTIVE, B.START_ACTIVE, B.END_ACTIVE
	// FROM M_MACHINE A
	// INNER JOIN JOB_MACHINE_OPERATOR B ON A.ID_MACHINE = B.ID_MACHINE
		// WHERE A.MCH_TYPE='ITV'
	// AND B.START_ACTIVE = (SELECT MAX(D.START_ACTIVE) FROM JOB_MACHINE_OPERATOR D WHERE D.ID_MACHINE=B.ID_MACHINE)
	// ORDER BY A.MCH_NAME ASC  ";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		return $data;
    }

    public function getOperatorList() {

	// $query = "SELECT MCH_NAME, MCH_SUB_TYPE
	// FROM M_MACHINE
	// WHERE ORDER BY MCH_TYPE='ITV' TO_NUMBER(ID_MACHINE) ASC";
	// $rs 		= $this->db->query($query);
	// $data 		= $rs->result_array();
	// return $data;
	// if($id=='null'){
	// $comment='';
	// }
	// else
	// {
	// $comment="where VESSEL_SERVICE_NAME like '%$id%'";
	// }

		$query = "SELECT ID_USER, FULL_NAME FROM M_USERS WHERE ROLE_ITV='Y' AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		return $data;
    }

    //==End of edit by mustadio_gun
    //======================================
    //======================================

    public function get_terminal($id = '') {
	if ($id == '') {
	    return $this->db->get('M_TERMINAL')->result_array();
	} else {
	    return $this->db->get_where('M_TERMINAL', array('ID_TERMINAL' => $id))->result_array();
	}
    }

    //reporting
    public function get_data_stacking($paging = false, $sort = false, $filters = false, $dw_from, $dw_to, $sl, $io, $lc) {
		$qPaging = '';
		if ($paging != false) {
		    $start = $paging['start'] + 1;
		    $end = $paging['page'] * $paging['limit'];
		    $qPaging = "WHERE REC_NUM >= $start AND REC_NUM <= $end";
		}
		$qSort = '';
		if ($sort != false) {
		    $sortProperty = $sort[0]->property;
		    $sortDirection = $sort[0]->direction;
		    /* if ($sortProperty=='NO_CONTAINER'){
		      $sortProperty = 'NO_CONTAINER';
		      }
		      if ($sortProperty=='ID_CLASS_CODE'){
		      $sortProperty = 'ID_CLASS_CODE';
		      } */
		    $qSort .= " ORDER BY " . $sortProperty . " " . $sortDirection;
		}
		$qWhere = "WHERE 1=1";
		//$qWhere = "";
		$qs = '';

		$where = '';
		if($dw_from != NULL || $dw_to != NULL){
			$where .= " AND (TO_CHAR(PLACEMENT_DATE, 'dd-mm-yyyy HH24:MI') BETWEEN '".$dw_from."' AND '".$dw_to."')";
		}

		if($io == 'Inbound'){
			$io = 'I';
		}else if($io == 'Outbound'){
			$io = 'E';
		}else{
			$io = NULL;
		}
		
		$sl == NULL ? $where .= '' : $where .= " AND B.OPERATOR_NAME = '".$sl."' ";
		$io == NULL ? $where .= '' : $where .= " AND A.ID_CLASS_CODE = '".$io."' ";
		$lc == NULL ? $where .= '' : $where .= " AND A.YD_BLOCK_NAME = '".$lc."' ";

		//echo $io;die;
		//debux($where);die;
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
			    case'NO_CONTAINER' : $field = "$field";
				break;
			    case'ID_CLASS_CODE' : $field = "$field";
				break;
			    case'PLACEMENT_DATE' : $field = "$field";
				break;
			    case'OPERATOR_NAME' : $field = "B.$field";
				break;
			    case'GT_JS_BLOCK_NAME' : $field = "$field";
				break;
			    case'VESSEL_NAME' : $field = "$field";
				break;
			    case'VOY_IN' : $field = "$field";
				break;
			    case'VOY_OUT' : $field = "$field";
				break;
			}

			if ($field == 'DWELLING_TIME') {
			    if ($filterType == 'numeric') {
				if ($compare == 'lt') {
				    $qs .= " AND 
					    CASE
					    WHEN A.ID_CLASS_CODE = 'I' THEN
						    TRUNC(SYSDATE-A.CONFIRM_DATE)
					    ELSE
						    TRUNC(SYSDATE-A.PLACEMENT_DATE)
				    END < " . $value;
				}

				if ($compare == 'gt') {
				    $qs .= " AND 
					    CASE
					    WHEN A.ID_CLASS_CODE = 'I' THEN
						    TRUNC(SYSDATE-A.CONFIRM_DATE)
					    ELSE
						    TRUNC(SYSDATE-A.PLACEMENT_DATE)
				    END > " . $value;
				}

				if ($compare == 'eq') {
				    $qs .= " AND 
					    CASE
					    WHEN A.ID_CLASS_CODE = 'I' THEN
						    TRUNC(SYSDATE-A.CONFIRM_DATE)
					    ELSE
						    TRUNC(SYSDATE-A.PLACEMENT_DATE)
					    END = " . $value;
				}
			    }
			} else {
			    switch ($filterType) {
				case 'string' : $qs .= " AND " . $field . " LIKE '%" . strtoupper($value) . "%'";
				    Break;
				case 'list' :
				    if (strstr($value, ',')) {
					$fi = explode(',', $value);
					for ($q = 0; $q < count($fi); $q++) {
					    $fi[$q] = "'" . $fi[$q] . "'";
					}
					$value = implode(',', $fi);
					$qs .= " AND " . $field . " IN (" . strtoupper($value) . ")";
				    } else {
					$qs .= " AND " . $field . " = '" . strtoupper($value) . "'";
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
		    }
		    $qWhere .= $qs;
		}

		$query = "SELECT * FROM (SELECT  A.NO_CONTAINER, A.ID_CLASS_CODE, A.PLACEMENT_DATE, TO_CHAR(A.PLACEMENT_DATE, 'HH24:MI') PLACEMENT_TIME, A.YC_REAL, B.OPERATOR_NAME, A.YD_BLOCK_NAME, C.VESSEL_NAME, C.VOY_IN, C.VOY_OUT,
	                        CASE WHEN A.CONT_STATUS = 'MTY' THEN 'EMPTY' ELSE 'FULL' END CONT_STATUS,
	                         CASE WHEN A.ID_CLASS_CODE = 'I' THEN GET_DURATION(A.CONFIRM_DATE, SYSDATE)
	                         ELSE GET_DURATION(A.PLACEMENT_DATE, SYSDATE)
	                         END  DWELLING_TIME , A.YD_SLOT, A.YD_ROW , A.YD_TIER, A.CONT_SIZE, A.ID_ISO_CODE,
							 CASE WHEN A.ID_CLASS_CODE = 'E' THEN
							 (SELECT PORT_NAME FROM M_PORT D WHERE D.PORT_CODE = A.ID_POD)
							 ELSE (SELECT PORT_NAME FROM M_PORT D WHERE D.PORT_CODE = A.ID_POL) END POD,
	                         ROWNUM REC_NUM
	                                    FROM CON_LISTCONT A
	                                    LEFT JOIN M_OPERATOR B ON B.ID_OPERATOR = A.ID_OPERATOR
	                                    LEFT JOIN VES_VOYAGE C ON C.ID_VES_VOYAGE = A.ID_VES_VOYAGE
	                                    $qWhere $where AND A.OP_STATUS_DESC = 'Stacking' AND A.ACTIVE = 'Y' AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_TERMINAL='".$this->gtools->terminal()."' $qSort) $qPaging";

	    //debux($query);die;
		$rs = $this->db->query($query);
		$list = $rs->result_array();

		$query_count = "SELECT COUNT(*) TOTAL FROM CON_LISTCONT A
										LEFT JOIN M_OPERATOR B ON B.ID_OPERATOR = A.ID_OPERATOR
										LEFT JOIN VES_VOYAGE C ON C.ID_VES_VOYAGE = A.ID_VES_VOYAGE
										$qWhere $where AND A.OP_STATUS_DESC = 'Stacking' AND A.ACTIVE = 'Y'AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];

		$data = array(
		    'total' => $total,
		    'data' => $list
		);

		return $data;
    }

	public function get_data_yard(){
		//debux($this->input->get());die;
		$sl = $_GET['sl'];
		$ios = $_GET['io'];
		if($io == 'Inbound'){
			$io = 'I';
		}else if($io == 'Outbound'){
			$io = 'E';
		}else{
			$io = NULL;
		}
		$lc = $_GET['lc'];
		$dfd = $_GET['dfd'];
		$dfh = $_GET['dfh'];
		$dfm = $_GET['dfm'];
		$dtd = $_GET['dtd'];
		$dth = $_GET['dth'];
		$dtm = $_GET['dtm'];
		
		$qs = '';
		$qy = '';

		$sl == NULL ? $qs .= '' : $qs .= " AND B.OPERATOR_NAME = '".$sl."' ";
		$io == NULL ? $qs .= '' : $qs .= " AND A.ID_CLASS_CODE = '".$io."' ";
		$lc == NULL ? $qs .= '' : $qs .= " AND A.YD_BLOCK_NAME = '".$lc."' ";

		// $qy .=  $dtH != NULL && $dtJ != NULL ? " WHERE DWELLING_TIME >= '".$dtH." hari ".$dtJ." jam'" : '' ;
		// $qy .= $dtH != NULL && $dtJ != NULL && $dtHt != NULL && $dtJt != NULL ? " AND DWELLING_TIME <= '".$dtHt." hari ".$dtJt." jam'" : '';

		if($dfd != NULL || $dtd != NULL){
			$dw_from = $dfd." ".$dfh.":".$dfm;
			$dw_to = $dtd." ".$dth.":".$dtm;
			$qy .= "WHERE TO_CHAR(PLACEMENT_DATE, 'dd-mm-yyyy HH24:MI') BETWEEN '".$dw_from."' AND '".$dw_to."'";
		}

		$query 		= "SELECT * FROM(
		SELECT  A.NO_CONTAINER, A.ID_CLASS_CODE, A.PLACEMENT_DATE,TO_CHAR(A.PLACEMENT_DATE, 'HH24:MI') PLACEMENT_TIME, A.YC_REAL, B.OPERATOR_NAME, A.YD_BLOCK_NAME, C.VESSEL_NAME, C.VOY_IN, C.VOY_OUT,
                        CASE WHEN A.CONT_STATUS = 'MTY' THEN 'EMPTY' ELSE 'FULL' END CONT_STATUS,
						CASE WHEN A.ID_CLASS_CODE = 'I' THEN GET_DURATION(A.CONFIRM_DATE, SYSDATE)
                         ELSE GET_DURATION(A.PLACEMENT_DATE, SYSDATE)
                         END  DWELLING_TIME, A.YD_SLOT, A.YD_ROW, A.YD_TIER,
						 A.CONT_SIZE,
						 A.ID_ISO_CODE,
						 CASE WHEN A.ID_CLASS_CODE = 'E' THEN
						 (SELECT PORT_NAME FROM M_PORT D WHERE D.PORT_CODE = A.ID_POD)
						 ELSE (SELECT PORT_NAME FROM M_PORT D WHERE D.PORT_CODE = A.ID_POL) END POD
						FROM CON_LISTCONT A
						LEFT JOIN M_OPERATOR B ON B.ID_OPERATOR = A.ID_OPERATOR
						LEFT JOIN VES_VOYAGE C ON C.ID_VES_VOYAGE = A.ID_VES_VOYAGE
						WHERE A.OP_STATUS_DESC = 'Stacking' AND A.ID_TERMINAL = '".$this->gtools->terminal()."' AND A.ACTIVE = 'Y' ".$qs." ORDER BY PLACEMENT_DATE ASC ) ".$qy."";
					// echo '</pre>';echo $query;die();
		//debux($query);die;
		$rs = $this->db->query($query);
		$data = $rs->result_array();

		return $data;
    }

    public function get_data_progress_loading_monitoring($paging, $sort, $filters) {
		$query_count = "SELECT COUNT(*) TOTAL
							FROM VES_VOYAGE
							WHERE ID_TERMINAL='".$this->gtools->terminal()."' AND ATD IS NULL AND ACTIVE = 'Y'";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];

		$qPaging = '';
		if ($paging != false) {
		    $start = $paging['start'] + 1;
		    $end = $paging['page'] * $paging['limit'];
		    $qPaging = "WHERE REC_NUM >= $start AND REC_NUM <= $end";
		}

		$qSort = '';
		if ($sort != false) {
		    $sortProperty = $sort[0]->property;
		    $sortDirection = $sort[0]->direction;
		    if ($sortProperty == 'ETB') {
			$sortProperty = 'TGL_ETB';
		    }
		    if ($sortProperty == 'ETD') {
			$sortProperty = 'TGL_ETD';
		    }
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
				    case 'VESSEL_NAME' : $field = "VESSEL_NAME";
					break;
				}

				switch ($filterType) {
				    case 'string' : $qs .= " AND " . $field . " LIKE '%" . strtoupper($value) . "%'";
					Break;
				    case 'list' :
					if (strstr($value, ',')) {
					    $fi = explode(',', $value);
					    for ($q = 0; $q < count($fi); $q++) {
						$fi[$q] = "'" . $fi[$q] . "'";
					    }
					    $value = implode(',', $fi);
					    $qs .= " AND " . $field . " IN (" . strtoupper($value) . ")";
					} else {
					    $qs .= " AND " . $field . " = '" . strtoupper($value) . "'";
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
				    case 'mainfilterdate' :
					switch ($field) {
					    case 'FROM_DATE' : $qs .= " AND ETB >= TO_DATE('" . date('Y-m-d', strtotime($value)) . "','yyyy-mm-dd')";
						Break;
					    case 'TO_DATE' :
						if ($value == NULL) {
						    $qs .= " AND ETB <= TO_DATE('" . date('Y-m-d') . "','yyyy-mm-dd')";
						} else {
						    $qs .= " AND ETB <= TO_DATE('" . date('Y-m-d', strtotime($value)) . "','yyyy-mm-dd')";
						} Break;
					}
					break;
				}
		    }
		    $qWhere .= $qs;
		}

		/*
		$query = "SELECT * FROM (SELECT Z.*,  CASE WHEN BOOKING_TEUS = 0 THEN 0 ELSE ((READINESS_TEUS+BOOKED_TEUS)/BOOKING_TEUS)*100 END PERCENTAGE FROM (
						SELECT VESSEL_NAME, (VOY_IN||' / '||VOY_OUT) VOY, (TO_CHAR(ETB, 'dd-mm-yyyy hh24:mi:ss')||' WIB') ETB, (TO_CHAR(ETD, 'dd-mm-yyyy hh24:mi:ss')||' WIB') ETD,
						NVL(BOOKING_STACK, 0)  BOOKING_TEUS,
						(SELECT COUNT(*) FROM CON_LISTCONT C WHERE C.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_OP_STATUS <> 'DIS' AND C.ID_CLASS_CODE IN ('E','TE') AND C.ID_VES_VOYAGE = A.ID_VES_VOYAGE AND C.OP_STATUS_DESC IN ('Gate In to Yard','Stacking') AND CONT_SIZE = '20') +
						(SELECT COUNT(*)*2 FROM CON_LISTCONT C WHERE C.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_OP_STATUS <> 'DIS' AND C.ID_CLASS_CODE IN ('E','TE') AND C.ID_VES_VOYAGE = A.ID_VES_VOYAGE AND C.OP_STATUS_DESC IN ('Gate In to Yard','Stacking') AND CONT_SIZE <> '20') READINESS_TEUS,
						(SELECT COUNT(*) FROM CON_LISTCONT C WHERE C.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_OP_STATUS <> 'DIS' AND C.ID_CLASS_CODE IN ('E','TE') AND C.ID_VES_VOYAGE = A.ID_VES_VOYAGE AND C.OP_STATUS_DESC NOT IN ('Gate In to Yard','Stacking') AND CONT_SIZE = '20') +
						(SELECT COUNT(*)*2 FROM CON_LISTCONT C WHERE C.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_OP_STATUS <> 'DIS' AND C.ID_CLASS_CODE IN ('E','TE') AND C.ID_VES_VOYAGE = A.ID_VES_VOYAGE AND C.OP_STATUS_DESC NOT IN ('Gate In to Yard','Stacking') AND CONT_SIZE <> '20') BOOKED_TEUS, ROWNUM REC_NUM, ETB AS TGL_ETB, ETD AS TGL_ETD
						FROM VES_VOYAGE A
						$qWhere AND A.ID_TERMINAL='".$this->gtools->terminal()."' AND A.ATA IS NULL
						$qSort) Z) $qPaging";
		*/

		$query = "SELECT * FROM
					( SELECT Z.*,
							CASE
								WHEN BOOKING_TEUS = 0 THEN 0
								ELSE (READINESS_TEUS/BOOKING_TEUS)* 100
							END PERCENTAGE,
							(OUTBOUND_LIST-READINESS_TEUS) AS BOOKED_TEUS
						FROM
							( SELECT
								VESSEL_NAME,
								(VOY_IN || ' / ' || VOY_OUT) VOY,
								(TO_CHAR(ETB, 'dd-mm-yyyy hh24:mi:ss')|| ' WIB') ETB,
								(TO_CHAR(ETD, 'dd-mm-yyyy hh24:mi:ss')|| ' WIB') ETD,
								NVL(BOOKING_STACK, 0) BOOKING_TEUS,
								NVL(APP_BOOKING_STACK, 0) APPROVED_TEUS, 
								( (SELECT COUNT(*)
									FROM CON_LISTCONT C
									LEFT JOIN ITOS_BILLING.REQ_RECEIVING_D D ON D.NO_CONTAINER = C.NO_CONTAINER
									LEFT JOIN ITOS_BILLING.REQ_RECEIVING_H E ON E.ID_REQ = D.NO_REQ_ANNE
									WHERE C.ID_VES_VOYAGE=A.ID_VES_VOYAGE
										AND C.CONT_SIZE IN ('20','21')
										AND TRIM(C.ID_OP_STATUS) <> 'DIS' 
										AND C.ID_CLASS_CODE IN ('E', 'TE') 
										AND E.STATUS IN ('P','T')
										AND C.TL_FLAG != 'Y'
										AND C.ID_TERMINAL='".$this->gtools->terminal()."'
										AND C.YD_BLOCK_NAME IS NOT NULL) + ((SELECT COUNT(*)
									FROM CON_LISTCONT C
									LEFT JOIN ITOS_BILLING.REQ_RECEIVING_D D ON D.NO_CONTAINER = C.NO_CONTAINER
									LEFT JOIN ITOS_BILLING.REQ_RECEIVING_H E ON E.ID_REQ = D.NO_REQ_ANNE
									WHERE C.ID_VES_VOYAGE=A.ID_VES_VOYAGE
										AND C.CONT_SIZE IN ('40','45')
										AND TRIM(C.ID_OP_STATUS) <> 'DIS' 
										AND C.ID_CLASS_CODE IN ('E', 'TE') 
										AND E.STATUS IN ('P','T')
										AND C.TL_FLAG != 'Y'
										AND C.ID_TERMINAL='".$this->gtools->terminal()."'
										AND C.YD_BLOCK_NAME IS NOT NULL)*2)
								) READINESS_TEUS,
								((SELECT COUNT(*)
										FROM CON_LISTCONT C
										JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER AND C.POINT = E.POINT	
										WHERE C.ID_VES_VOYAGE= A.ID_VES_VOYAGE
										AND C.CONT_SIZE IN ('20','21')
										AND TRIM(C.ID_OP_STATUS) <> 'DIS'
										AND C.OP_STATUS_DESC NOT IN ('Booking Inbound') 
										AND C.ID_CLASS_CODE = 'E' 
										AND NVL(E.BILLING_PAID,'0') = (CASE WHEN C.ID_CLASS_CODE = 'E' THEN E.BILLING_PAID ELSE '0' END)
										AND C.ID_TERMINAL='".$this->gtools->terminal()."') + ((SELECT COUNT(*)
										FROM CON_LISTCONT C
										JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER AND C.POINT = E.POINT	
										WHERE C.ID_VES_VOYAGE= A.ID_VES_VOYAGE
										AND C.CONT_SIZE IN ('40','45')
										AND TRIM(C.ID_OP_STATUS) <> 'DIS'
										AND C.OP_STATUS_DESC NOT IN ('Booking Inbound') 
										AND C.ID_CLASS_CODE = 'E' 
										AND NVL(E.BILLING_PAID,'0') = (CASE WHEN C.ID_CLASS_CODE = 'E' THEN E.BILLING_PAID ELSE '0' END)
										AND C.ID_TERMINAL='".$this->gtools->terminal()."')*2)
								) OUTBOUND_LIST,
								ROWNUM REC_NUM,
								ETB AS TGL_ETB,
								ETD AS TGL_ETD
							FROM
								VES_VOYAGE A
							$qWhere
								AND A.ID_TERMINAL = '".$this->gtools->terminal()."'
								AND A.ATD IS NULL
								AND A.ACTIVE = 'Y'
							$qsort) Z
					) $qPaging";

	//		echo '<pre>';print_r($query);echo '</pre>';exit;
		//debux($query);die;
		$rs = $this->db->query($query);
		$list = $rs->result_array();

		$data = array(
		    'total' => $total,
		    'data' => $list
		);

		return $data;
    }

    public function get_data_ete_progress_loading_monitoring() {
		/*
		$query = "SELECT Z.*,  CASE WHEN BOOKING_TEUS = 0 THEN 0 ELSE ((READINESS_TEUS+BOOKED_TEUS)/BOOKING_TEUS)*100 END PERCENTAGE FROM (
						SELECT VESSEL_NAME, (VOY_IN||' / '||VOY_OUT) VOY, (TO_CHAR(ETB, 'dd-mm-yyyy hh24:mi:ss')||' WIB') ETB, (TO_CHAR(ETD, 'dd-mm-yyyy hh24:mi:ss')||' WIB') ETD,
						NVL(BOOKING_STACK, 0)  BOOKING_TEUS,
						(SELECT COUNT(*) FROM CON_LISTCONT C WHERE C.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_OP_STATUS <> 'DIS' AND C.ID_CLASS_CODE IN ('E','TE') AND C.ID_VES_VOYAGE = A.ID_VES_VOYAGE AND C.OP_STATUS_DESC IN ('Gate In to Yard','Stacking') AND CONT_SIZE = '20') +
						(SELECT COUNT(*)*2 FROM CON_LISTCONT C WHERE C.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_OP_STATUS <> 'DIS' AND C.ID_CLASS_CODE IN ('E','TE') AND C.ID_VES_VOYAGE = A.ID_VES_VOYAGE AND C.OP_STATUS_DESC IN ('Gate In to Yard','Stacking') AND CONT_SIZE <> '20') READINESS_TEUS,
						(SELECT COUNT(*) FROM CON_LISTCONT C WHERE C.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_OP_STATUS <> 'DIS' AND C.ID_CLASS_CODE IN ('E','TE') AND C.ID_VES_VOYAGE = A.ID_VES_VOYAGE AND C.OP_STATUS_DESC NOT IN ('Gate In to Yard','Stacking') AND CONT_SIZE = '20') +
						(SELECT COUNT(*)*2 FROM CON_LISTCONT C WHERE C.ID_TERMINAL='".$this->gtools->terminal()."' AND C.ID_OP_STATUS <> 'DIS' AND C.ID_CLASS_CODE IN ('E','TE') AND C.ID_VES_VOYAGE = A.ID_VES_VOYAGE AND C.OP_STATUS_DESC NOT IN ('Gate In to Yard','Stacking') AND CONT_SIZE <> '20') BOOKED_TEUS
						FROM VES_VOYAGE A
						WHERE A.ATA IS NULL AND A.ID_TERMINAL='".$this->gtools->terminal()."'
						ORDER BY A.ETB ASC) Z";
		*/

		$query = "SELECT Z.*,
							CASE
								WHEN BOOKING_TEUS = 0 THEN 0
								ELSE (READINESS_TEUS/BOOKING_TEUS)* 100
							END PERCENTAGE,
							(OUTBOUND_LIST-READINESS_TEUS) AS BOOKED_TEUS
						FROM
							( SELECT
								VESSEL_NAME,
								(VOY_IN || ' / ' || VOY_OUT) VOY,
								(TO_CHAR(ETB, 'dd-mm-yyyy hh24:mi:ss')|| ' WIB') ETB,
								(TO_CHAR(ETD, 'dd-mm-yyyy hh24:mi:ss')|| ' WIB') ETD,
								NVL(BOOKING_STACK, 0) BOOKING_TEUS,
								NVL(APP_BOOKING_STACK, 0) APPROVED_TEUS, 
								( (SELECT COUNT(*)
									FROM CON_LISTCONT C
									LEFT JOIN ITOS_BILLING.REQ_RECEIVING_D D ON D.NO_CONTAINER = C.NO_CONTAINER
									LEFT JOIN ITOS_BILLING.REQ_RECEIVING_H E ON E.ID_REQ = D.NO_REQ_ANNE
									WHERE C.ID_VES_VOYAGE=A.ID_VES_VOYAGE
										AND C.CONT_SIZE IN ('20','21')
										AND TRIM(C.ID_OP_STATUS) <> 'DIS' 
										AND C.ID_CLASS_CODE IN ('E', 'TE') 
										AND E.STATUS IN ('P','T')
										AND C.TL_FLAG != 'Y'
										AND C.ID_TERMINAL='103'
										AND C.YD_BLOCK_NAME IS NOT NULL) + ((SELECT COUNT(*)
									FROM CON_LISTCONT C
									LEFT JOIN ITOS_BILLING.REQ_RECEIVING_D D ON D.NO_CONTAINER = C.NO_CONTAINER
									LEFT JOIN ITOS_BILLING.REQ_RECEIVING_H E ON E.ID_REQ = D.NO_REQ_ANNE
									WHERE C.ID_VES_VOYAGE=A.ID_VES_VOYAGE
										AND C.CONT_SIZE IN ('40','45')
										AND TRIM(C.ID_OP_STATUS) <> 'DIS' 
										AND C.ID_CLASS_CODE IN ('E', 'TE') 
										AND E.STATUS IN ('P','T')
										AND C.TL_FLAG != 'Y'
										AND C.ID_TERMINAL='103'
										AND C.YD_BLOCK_NAME IS NOT NULL)*2)
								) READINESS_TEUS,
								((SELECT COUNT(*)
										FROM CON_LISTCONT C
										JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER AND C.POINT = E.POINT	
										WHERE C.ID_VES_VOYAGE= A.ID_VES_VOYAGE
										AND C.CONT_SIZE IN ('20','21')
                                        AND C.OP_STATUS_DESC NOT IN ('Booking Inbound')
										AND TRIM(C.ID_OP_STATUS) <> 'DIS' 
										AND C.ID_CLASS_CODE = 'E'
										AND NVL(E.BILLING_PAID,'0') = (CASE WHEN C.ID_CLASS_CODE = 'E' THEN E.BILLING_PAID ELSE '0' END)
										AND C.ID_TERMINAL='103') + ((SELECT COUNT(*)
										FROM CON_LISTCONT C
										JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER AND C.POINT = E.POINT	
										WHERE C.ID_VES_VOYAGE= A.ID_VES_VOYAGE
										AND C.CONT_SIZE IN ('40','45')
										AND TRIM(C.ID_OP_STATUS) <> 'DIS'
                                        AND C.OP_STATUS_DESC NOT IN ('Booking Inbound')
										AND C.ID_CLASS_CODE = 'E'
										AND NVL(E.BILLING_PAID,'0') = (CASE WHEN C.ID_CLASS_CODE = 'E' THEN E.BILLING_PAID ELSE '0' END)
										AND C.ID_TERMINAL='103')*2)
								) OUTBOUND_LIST,
								ROWNUM REC_NUM,
								ETB AS TGL_ETB,
								ETD AS TGL_ETD
							FROM
								VES_VOYAGE A
							WHERE 1=1
								AND A.ID_TERMINAL = '103'
								AND A.ATD IS NULL
								AND A.ACTIVE = 'Y'
							) Z";
		$rs = $this->db->query($query);
		$data = $rs->result_array();

		return $data;
    }

    public function get_truck($paging = false, $sort = false, $filters = false) {
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
	$query = "SELECT ID_TRUCK, TID, NO_POL, STATUS_GATE,ROWNUM REC_NUM FROM (
				    SELECT ID_TRUCK, TID, NO_POL, STATUS_GATE FROM M_TRUCK
				    $qWhere
				    $qSort
			    )
			    GROUP BY ID_TRUCK, TID, NO_POL, STATUS_GATE,ROWNUM
			    $qPaging
			    ORDER BY ROWNUM";
	$rs = $this->db->query($query);
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

	$query_count = "SELECT COUNT(*) AS TOTAL FROM M_TRUCK $qWhere";
	$rs = $this->db->query($query_count);
	$row = $rs->row_array();
	$total = $row['TOTAL'];

	$data = array(
	    'total' => $total,
	    'data' => $operator_list
	);
	return $data;
    }

    public function check_tid($tid, $no_pol) {
	$ret = 1;
	$paramTID = array($tid);
	$queryTID = "SELECT COUNT(*) AS TOTAL
				    FROM M_TRUCK
				    WHERE TID=? ";
	$rsTID = $this->db->query($queryTID, $paramTID);
	$rowTID = $rsTID->row_array();
	if ($rowTID['TOTAL'] > 0) {
	    $ret = 1;
	    return $ret;
	} else {
	    $ret = 0;
	}
	$param = array($no_pol);
	$query = "SELECT COUNT(*) AS TOTAL
				    FROM M_TRUCK
				    WHERE NO_POL=? ";
	$rs = $this->db->query($query, $param);
	$row = $rs->row_array();
	if ($row['TOTAL'] > 0) {
	    $ret = 2;
	} else {
	    $ret = 0;
	}

	return $ret;
    }

    public function save_truck($data) {

	$id_user = $data['id_user'];
	$CREATE_DATE = date('d-M-y h:i:s A');
	$tid = $data['TID'];
	$no_pol = $data['NO_POL'];

	if (!isset($tid) || $tid == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'TID harus diisi.'
	    );
	}
	if (!isset($no_pol) || $no_pol == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'No Pol harus diisi.'
	    );
	}

	if (strlen($tid) != 5) {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Port code harus 5 karakter.'
	    );
	}

	$query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_TRUCK WHERE TID = '$tid'";
	$result = $this->db->query($query);
	$row = $result->row_array();
	$count_tid = $row['COUNT'];

	if ($count_tid != '0') {
	    return array(
		'IsSuccess' => false,
		'Message' => $tid . ' sudah terdaftar.'
	    );
	}
	$query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_TRUCK WHERE NO_POL = '$no_pol'";
	$result = $this->db->query($query);
	$row = $result->row_array();
	$count_nopol = $row['COUNT'];

	if ($count_nopol != '0') {
	    return array(
		'IsSuccess' => false,
		'Message' => $no_pol . ' sudah terdaftar.'
	    );
	}

	$this->db->trans_start();
	$query = "INSERT INTO ITOS_OP.M_TRUCK (ID_TRUCK,TID,NO_POL,CREATE_USER,CREATE_DATE)
				  VALUES (m_truck_seq.nextval,'$tid','$no_pol','$id_user','$CREATE_DATE')";
	$this->db->query($query);

	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $tid . ' berhasil disimpan'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Save gagal.'
	    );
	}
    }

    public function get_truck_by_id($id_truck, $tid) {
	$qry = "SELECT * FROM M_TRUCK WHERE ID_TRUCK = " . $id_truck;
//	    echo $qry;
	$res = $this->db->query($qry)->row_array();

	return $res;
    }

    public function edit_truck($data) {
		$id_user = $data['id_user'];
		$id_truck = $data['ID_TRUCK'];
		$tid = $data['TID'];
		$no_pol = $data['NO_POL'];
		$MODIFY_DATE = date('d-M-y h:i:s A');

		if (!isset($tid) || $tid == '') {
		    return array(
			'IsSuccess' => false,
			'Message' => 'TID harus diisi.'
		    );
		}

		if (!isset($no_pol) || $no_pol == '') {
		    return array(
			'IsSuccess' => false,
			'Message' => 'No Pol harus diisi.'
		    );
		}

		if (strlen($tid) != 5) {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Port code harus 5 karakter.'
		    );
		}

		$query = "SELECT * FROM ITOS_OP.M_TRUCK WHERE ID_TRUCK = '$id_truck'";
		$result = $this->db->query($query);
		$row = $result->row_array();
		$tid_ori = $row['TID'];
		$no_pol_ori = $row['NO_POL'];
	//	    echo 'query : '.$query;
	//	    echo $no_pol.' : '.$no_pol_ori;exit;
		if ($tid != $tid_ori) {
		    $query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_TRUCK WHERE TID = '$tid'";
		    $result = $this->db->query($query);
		    $row = $result->row_array();
		    $count_tid = $row['COUNT'];

		    if ($count_tid != '0') {
			return array(
			    'IsSuccess' => false,
			    'Message' => $tid . ' sudah terdaftar.'
			);
		    }
		}
		if ($no_pol != $no_pol_ori) {
		    $query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_TRUCK WHERE NO_POL = '$no_pol'";
		    $result = $this->db->query($query);
		    $row = $result->row_array();
		    $count_nopol = $row['COUNT'];

		    if ($count_nopol != '0') {
			return array(
			    'IsSuccess' => false,
			    'Message' => $no_pol . ' sudah terdaftar.'
			);
		    }
		}

		$this->db->trans_start();
		$query = "UPDATE ITOS_OP.M_TRUCK SET TID='$tid',NO_POL='$no_pol',MODIFY_USER='$id_user',MODIFY_DATE='$MODIFY_DATE'
				WHERE ID_TRUCK='$id_truck'";
		$this->db->query($query);

		if ($this->db->trans_complete()) {
		    return array(
			'IsSuccess' => true,
			'Message' => $tid . ' berhasil diubah'
		    );
		} else {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Update gagal.'
		    );
		}
    }

    public function delete_truck($id_truck, $tid) {
		$this->db->trans_start();
		$query = "DELETE FROM M_TRUCK WHERE ID_TRUCK=$id_truck";
		$this->db->query($query);

		if ($this->db->trans_complete()) {
		    return array(
			'IsSuccess' => true,
			'Message' => $tid . ' berhasil di hapus'
		    );
		} else {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Hapus gagal.'
		    );
		}
    }

    public function get_pool($paging = false, $sort = false, $filters = false) {
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
		$query = "SELECT 
					ID_POOL, 
					POOL_NAME, 
					POOL_DESCRIPTION, 
					POOL_TYPE,
					CTRUCK,
					ROWNUM REC_NUM 
					FROM (
					    SELECT 
					    	mp.ID_POOL, 
					    	mp.POOL_NAME, 
					    	mp.POOL_DESCRIPTION, 
         					count(*) as CTRUCK,
					    	DECODE(mp.POOL_TYPE, 'Y', 'Yard','Vessel') AS POOL_TYPE 
					    FROM 
					    	M_POOL_H mp
					    INNER JOIN
					        M_MACHINE mm
					    ON  
					       mp.ID_POOL = mm.ID_POOL
					    $qWhere 
					    AND
					        mm.mch_type = 'ITV'
					    AND 
					    	mp.ID_TERMINAL='".$this->gtools->terminal()."'
					    GROUP BY
					        mp.ID_POOL,
					  		mp.POOL_NAME,
					   		mp.POOL_DESCRIPTION,
					   		mp.POOL_TYPE
					    $qSort
				    )
				    GROUP BY 
				    	ID_POOL, 
				    	POOL_NAME, 
				    	POOL_DESCRIPTION, 
				    	POOL_TYPE,
   						CTRUCK,
				    	ROWNUM
				    $qPaging
				    ORDER BY 
				    	ROWNUM";
		$rs = $this->db->query($query);
		$result_list = $rs->result_array();
		
		$query_count = "SELECT COUNT(*) AS TOTAL FROM M_POOL_H $qWhere AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query_count);
		$row = $rs->row_array();
		$total = $row['TOTAL'];

		$data = array(
		    'total' => $total,
		    'data' => $result_list
		);
		return $data;
    }

    public function check_pool($pool) {
	$ret = 1;
	$param = array($pool);
	$query = "SELECT COUNT(*) AS TOTAL
				    FROM M_POOL_H
				    WHERE POOL_NAME=? ";
	$rs = $this->db->query($query, $param);
	$row = $rs->row_array();
	if ($row['TOTAL'] > 0) {
	    $ret = 1;
	} else {
	    $ret = 0;
	}

	return $ret;
    }

    public function save_pool($data) {

	$id_user = $data['id_user'];
	$pool_name = $data['POOL_NAME'];
	$pool_desc = $data['POOL_DESCRIPTION'];
	$pool_type = $data['POOL_TYPE'];
	$date = date('d-M-y h:i:s A');

	if (!isset($pool_name) || $pool_name == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Pool Name harus diisi.'
	    );
	}
	if (!isset($pool_type) || $pool_type == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Pool Type harus diisi.'
	    );
	}

	$query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_POOL_H WHERE POOL_NAME = '$pool_name' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
	$result = $this->db->query($query);
	$row = $result->row_array();
	$count = $row['COUNT'];

	if ($count != '0') {
	    return array(
		'IsSuccess' => false,
		'Message' => $pool_name . ' sudah terdaftar.'
	    );
	}

	$this->db->trans_start();
	$query = "INSERT INTO ITOS_OP.M_POOL_H (ID_POOL,POOL_NAME,POOL_DESCRIPTION,POOL_TYPE,CREATE_USER,CREATE_DATE, ID_TERMINAL)
				  VALUES (m_pool_seq.nextval,'$pool_name','$pool_desc','$pool_type','$id_user','$date',".$this->gtools->terminal().")";
	$this->db->query($query);

	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $pool_name . ' berhasil disimpan'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Save gagal.'
	    );
	}
    }

    public function get_pool_by_id($id_pool) {
		$qry = "SELECT * FROM M_POOL_H WHERE ID_POOL = '".$id_pool."' AND ID_TERMINAL='".$this->gtools->terminal()."'";
	//	    echo $qry;
		$res = $this->db->query($qry)->row_array();

		return $res;
    }

    public function edit_pool($data) {
		$id_user = $data['id_user'];
		$id_pool = $data['ID_POOL'];
		$pool_name = $data['POOL_NAME'];
		$pool_desc = $data['POOL_DESCRIPTION'];
		$pool_type = $data['POOL_TYPE'];
		$date = date('d-M-y h:i:s A');

		if (!isset($pool_name) || $pool_name == '') {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Pool Name harus diisi.'
		    );
		}

		if (!isset($pool_type) || $pool_type == '') {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Pool Type name harus diisi.'
		    );
		}

		$query = "SELECT * FROM ITOS_OP.M_POOL_H WHERE ID_POOL = '$id_pool' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$result = $this->db->query($query);
		$row = $result->row_array();
		$pool_name_old = $row['POOL_NAME'];
	//	    echo 'query : '.$query;
	//	    echo $no_pol.' : '.$no_pol_ori;exit;
		if ($pool_name != $pool_name_old) {
		    $query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_POOL_H WHERE POOL_NAME = '$pool_name' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		    $result = $this->db->query($query);
		    $row = $result->row_array();
		    $count = $row['COUNT'];

		    if ($count != '0') {
			return array(
			    'IsSuccess' => false,
			    'Message' => $pool_name . ' sudah terdaftar.'
			);
		    }
		}

		$this->db->trans_start();
		$query = "UPDATE ITOS_OP.M_POOL_H SET POOL_NAME='$pool_name',POOL_DESCRIPTION='$pool_desc',POOL_TYPE='$pool_type',MODIFY_USER='$id_user',MODIFY_DATE='$date'
				WHERE ID_POOL=$id_pool AND ID_TERMINAL='".$this->gtools->terminal()."'";
	//	    echo $query;exit;
		$this->db->query($query);

		if ($this->db->trans_complete()) {
		    return array(
			'IsSuccess' => true,
			'Message' => $pool_name . ' berhasil diubah'
		    );
		} else {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Update gagal.'
		    );
		}
    }

    public function delete_pool($id_pool, $pool_name) {
	$this->db->trans_start();
	$query = "DELETE FROM M_POOL_H WHERE ID_POOL=$id_pool AND ID_TERMINAL = '".$this->gtools->terminal()."'";
	$this->db->query($query);

	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $pool_name . ' berhasil di hapus'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Hapus gagal.'
	    );
	}
    }

    public function get_pool_itv($id_pool) {
		$sql = "SELECT * FROM (
			SELECT ID_MACHINE,MCH_NAME,A.ID_POOL,B.POOL_NAME,1 AS CHECKLIST FROM M_MACHINE A 
			JOIN M_POOL_H B ON A.ID_POOL = B.ID_POOL
			WHERE A.MCH_TYPE = 'ITV' AND A.ID_POOL = $id_pool AND A.ID_TERMINAL = '".$this->gtools->terminal()."' AND B.ID_TERMINAL = '".$this->gtools->terminal()."'
			/*
			UNION
			SELECT ID_MACHINE,MCH_NAME,A.ID_POOL,B.POOL_NAME,0 AS CHECKLIST FROM M_MACHINE A 
			LEFT JOIN M_POOL_H B ON A.ID_POOL = B.ID_POOL AND B.ID_TERMINAL = '".$this->gtools->terminal()."'
			WHERE A.MCH_TYPE = 'ITV' AND A.ID_POOL != $id_pool AND A.ID_TERMINAL = '".$this->gtools->terminal()."'
			hanya pool yg berkaitan */
			UNION 
			SELECT ID_MACHINE,MCH_NAME,A.ID_POOL,B.POOL_NAME,0 AS CHECKLIST 
			FROM M_MACHINE A 
			LEFT JOIN M_POOL_H B ON A.ID_POOL = B.ID_POOL AND B.ID_TERMINAL = '".$this->gtools->terminal()."'
			WHERE A.MCH_TYPE = 'ITV' AND A.ID_POOL IS NULL AND A.ID_TERMINAL = '".$this->gtools->terminal()."' 
			) A ORDER BY CHECKLIST DESC";

//		 echo $sql;
		$qry = $this->db->query($sql)->result_array();

		return $qry;
    }

    public function save_pool_assigment($id_pool, $arr_machine) {
		$this->db->trans_start();
		$qry = "UPDATE M_MACHINE SET ID_POOL = NULL WHERE ID_POOL = $id_pool AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$this->db->query($qry);
		foreach ($arr_machine as $mch) {
		    $qry = "UPDATE M_MACHINE SET ID_POOL = $id_pool WHERE ID_MACHINE = $mch AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		    $this->db->query($qry);
		}

		if ($this->db->trans_complete()) {
		    return array(
			'IsSuccess' => true,
			'Message' => 'Set Assigment Success.'
		    );
		} else {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Set Assigment Failed.'
		    );
		}
    }

    public function get_shippingline(){
		$query ="SELECT DISTINCT (B.OPERATOR_NAME), A.ID_OPERATOR FROM CON_LISTCONT A
					LEFT JOIN M_OPERATOR B ON B.ID_OPERATOR = A .ID_OPERATOR
						WHERE
					1 = 1
					AND A.OP_STATUS_DESC = 'Stacking'
					AND A.ACTIVE = 'Y'
					AND A.ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		return $data;
	}

	public function get_location(){	
		$query = "SELECT DISTINCT (A.YD_BLOCK_NAME) from CON_LISTCONT A
					WHERE
						1 = 1
					AND A.OP_STATUS_DESC = 'Stacking'
					AND A.ACTIVE = 'Y'
					AND A.ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		return $data;
	}
   
    public function get_all_terminal(){
	$result = $this->db->get('M_TERMINAL')->result_array();
	return $result;
    }

    public function get_all_group() {
	$this->db->order_by('GROUP_NAME ASC');
	$result = $this->db->get('M_USER_GROUP')->result_array();
	return $result;
    }

    public function get_stoppages($paging, $sort, $filters) {
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

		switch ($filterType) {
		    case 'string' : $qs .= " AND LOWER(" . $field . ") LIKE '%" . strtolower($value) . "%'";
			Break;
		    case 'list' :
			if (is_array($value)) {
			    $fval = "";
			    foreach ($value as $val) {
				if ($fval != '')
				    $fval .= ',';
				$fval .= "'" . $val . "'";
			    }
			    $qs .= " AND " . $field . " IN (" . $fval . ")";
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
	$query = "SELECT ROWNUM,ID_SUSPEND,ACTIVITY,EQ_TYPE,GROUP_APP,CATEGORY FROM (
				    SELECT ID_SUSPEND,ACTIVITY,EQ_TYPE,GROUP_APP,CATEGORY FROM M_SUSPEND
				    $qWhere
				    $qSort
			    )
			    GROUP BY ID_SUSPEND,ACTIVITY,EQ_TYPE,GROUP_APP,ROWNUM,CATEGORY
			    $qPaging
			    ORDER BY ROWNUM";
	$rs = $this->db->query($query);
	$result_list = $rs->result_array();
	
	$query_count = "SELECT COUNT(*) AS TOTAL FROM M_SUSPEND $qWhere";
	$rs = $this->db->query($query_count);
	$row = $rs->row_array();
	$total = $row['TOTAL'];

	$data = array(
	    'total' => $total,
	    'data' => $result_list
	);
	return $data;
    }
    
    public function check_suspend_activity($activity) {
	$ret = 1;
	$param = array($activity);
	$query = "SELECT COUNT(*) AS TOTAL
				    FROM M_SUSPEND
				    WHERE ACTIVITY=? ";
	$rs = $this->db->query($query, $param);
	$row = $rs->row_array();
	if ($row['TOTAL'] > 0) {
	    $ret = 1;
	} else {
	    $ret = 0;
	}

	return $ret;
    }
    
    public function save_stoppage($data) {

	$CREATE_USER = $data['CREATE_USER'];
	$ACTIVITY = $data['ACTIVITY'];
	$EQ_TYPE = $data['EQ_TYPE'];
	$C_TYPE = $data['C_TYPE'];
	$CREATE_DATE = date('d-M-y h:i:s A');

	if (!isset($ACTIVITY) || $ACTIVITY == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Activity harus diisi.'
	    );
	}
	if (!isset($EQ_TYPE) || $EQ_TYPE == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'EQ Type harus diisi.'
	    );
	}
	if (!isset($C_TYPE) || $C_TYPE == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Category harus diisi.'
	    );
	}

	$query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_SUSPEND WHERE ACTIVITY = '$ACTIVITY'";
	$result = $this->db->query($query);
	$row = $result->row_array();
	$count = $row['COUNT'];

	if ($count != '0') {
	    return array(
		'IsSuccess' => false,
		'Message' => $ACTIVITY . ' sudah terdaftar.'
	    );
	}

	$this->db->trans_start();
	$query = "INSERT INTO ITOS_OP.M_SUSPEND (ID_SUSPEND,ACTIVITY,EQ_TYPE,CATEGORY,CREATE_USER,CREATE_DATE)
				  VALUES (m_suspend_seq.nextval,'$ACTIVITY','$EQ_TYPE','$C_TYPE','$CREATE_USER','$CREATE_DATE')";
	$this->db->query($query);

	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $ACTIVITY . ' berhasil disimpan'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Save gagal.'
	    );
	}
    }
    
    public function get_stoppage_by_id($id_suspend) {
	$qry = "SELECT * FROM M_SUSPEND WHERE ID_SUSPEND = " . $id_suspend;
//	    echo $qry;
	$res = $this->db->query($qry)->row_array();

	return $res;
    }
    
    public function edit_stoppage($data) {
		$MODIFY_USER = $data['MODIFY_USER'];
		$ID_SUSPEND = $data['ID_SUSPEND'];
		$ACTIVITY = $data['ACTIVITY'];
		$EQ_TYPE = $data['EQ_TYPE'];
		$C_TYPE = $data['C_TYPE'];
		$MODIFY_DATE = date('d-M-y h:i:s A');

		if (!isset($ACTIVITY) || $ACTIVITY == '') {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Activity Name harus diisi.'
		    );
		}

		if (!isset($EQ_TYPE) || $EQ_TYPE == '') {
		    return array(
			'IsSuccess' => false,
			'Message' => 'EQ Type harus diisi.'
		    );
		}

		$query = "SELECT * FROM ITOS_OP.M_SUSPEND WHERE ID_SUSPEND = '$ID_SUSPEND'";
		$result = $this->db->query($query);
		$row = $result->row_array();
		$activity_old = $row['ACTIVITY'];
	//	    echo 'query : '.$query;
	//	    echo $no_pol.' : '.$no_pol_ori;exit;
		if ($ACTIVITY != $activity_old) {
		    $query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_SUSPEND WHERE ACTIVITY = '$ACTIVITY'";
		    $result = $this->db->query($query);
		    $row = $result->row_array();
		    $count = $row['COUNT'];

		    if ($count != '0') {
			return array(
			    'IsSuccess' => false,
			    'Message' => $ACTIVITY . ' sudah terdaftar.'
			);
		    }
		}

		$this->db->trans_start();
		$query = "UPDATE ITOS_OP.M_SUSPEND SET ACTIVITY='$ACTIVITY',EQ_TYPE='$EQ_TYPE',CATEGORY='$C_TYPE',MODIFY_USER='$MODIFY_USER',MODIFY_DATE='$MODIFY_DATE'
				WHERE ID_SUSPEND=$ID_SUSPEND";
	//	    echo $query;exit;
		$this->db->query($query);

		if ($this->db->trans_complete()) {
		    return array(
			'IsSuccess' => true,
			'Message' => $ACTIVITY . ' berhasil diubah'
		    );
		} else {
		    return array(
			'IsSuccess' => false,
			'Message' => 'Update gagal.'
		    );
		}
    }
    
    public function delete_stoppage($id_suspend,$activity) {
	$this->db->trans_start();
	$query = "DELETE FROM M_SUSPEND WHERE ID_SUSPEND=$id_suspend";
	$this->db->query($query);

	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $activity . ' berhasil di hapus'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Hapus gagal.'
	    );
	}
    }
}

?>
