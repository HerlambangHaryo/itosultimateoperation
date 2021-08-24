<?php

class User extends CI_Model {

    public function __construct() {
	$this->load->database();
    }

    function login($username, $password) {
	$param = array($username, $password);
	$query = "SELECT * FROM M_USERS WHERE USERNAME=? AND PASSWORD=?";
	$rs = $this->db->query($query, $param);
	$data = $rs->result_array();
	if (sizeof($data) == 1) {
	    return $data[0];
	}
	return 0;
    }

    function menu_toolbar($username) {
	$param = array($username);
	$query = "SELECT ID_USER_GROUP FROM M_USERS WHERE USERNAME=?";
	$rs = $this->db->query($query, $param);
	$data = $rs->row_array();
	$id_user_group = $data['ID_USER_GROUP'] != '' ? $data['ID_USER_GROUP'] : '*';

	$param = array("%," . $id_user_group . ",%");

	$query = "SELECT *
						FROM
							M_USER_MENU
						WHERE ID_USER_GROUP LIKE ?
						ORDER BY PARENT_ID, MENU_ORDER, ID_MENU";
	$rs = $this->db->query($query, $param);
	$data = $rs->result_array();

	$main_menu = array();
	$child_menu = array();
	foreach ($data as $row) {
	    if ($row['PARENT_ID'] == "-1") {
		array_push($main_menu, $row);
	    } else {
		$child_menu[$row['PARENT_ID']][] = $row;
	    }
	}

	return array('main_menu' => $main_menu, 'child_menu' => $child_menu);
    }

    function get_user_detail($id_user) {
	$param = array($id_user);
	$query = "SELECT * FROM M_USERS WHERE ID_USER=?";
	$rs = $this->db->query($query, $param);
	$data = $rs->row_array();
	return $data;
    }

    public function update_password($id_user, $oldpwd, $newpwd, $cnewpwd) {
	$oldpassword = md5($oldpwd);
	$newpassword = md5($newpwd);
	$cnewpassword = md5($cnewpwd);
	$params = $id_user . "^" . $oldpassword . "^" . $newpassword . "^" . $cnewpassword;
	$param = array(
	    array('name' => ':v_param', 'value' => $params, 'length' => 500),
	    array('name' => ':v_msg', 'value' => &$msg, 'length' => 50)
	);
	// print_r($param);die;

	$sql = "BEGIN ITOS_OP.proc_update_passwd(:v_param,:v_msg); END;";
	$this->db->exec_bind_stored_procedure($sql, $param);
	// print_r($msg);die;

	return $msg;
    }

    function get_data_operator($role) 
    {
		$query 	= "SELECT 
					ID_USER, FULL_NAME
				FROM 
					M_USERS
				WHERE 
					$role = 'Y'";

		$rs 	= $this->db->query($query);
		$data 	= $rs->result_array();
		return $data;
    }

    public function get_users($paging = false, $sort = false, $filters = false) {
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
				if ($field == 'ROLE_GATE' || $field == 'ROLE_TALLY' || $field == 'ROLE_VMT' || $field == 'ROLE_PAGER' || $field == 'ROLE_YARD' || $field == 'ROLE_REEFER' || $field == 'ROLE_QC' || $field == 'ROLE_ITV') {
//							    $value = str_replace('NO','N',str_replace('YES', 'Y', $value));
				    $val = $val == 'YES' ? 'Y' : 'N';
				}
				if ($field == 'TERMINAL') {
				    $arrVal = explode(' - ', $val);
				    $field = 'B.TERMINAL_CODE';
				    $val = $arrVal[0];
				}
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
	$query = "SELECT ROWNUM,ID_USER,FULL_NAME,NICK_NAME,USERNAME,ROLE_GATE,ROLE_TALLY,ROLE_VMT,ROLE_PAGER,ROLE_YARD,ROLE_REEFER,ROLE_QC,ROLE_ITV,ROLE_PDA,TERMINAL,GROUP_NAME FROM (		   
				SELECT A.ID_USER,FULL_NAME,NICK_NAME,USERNAME,
						DECODE(ROLE_GATE,'Y','YES','NO') AS ROLE_GATE,
						DECODE(ROLE_TALLY,'Y','YES','NO') AS ROLE_TALLY,
						DECODE(ROLE_VMT,'Y','YES','NO') AS ROLE_VMT,
						DECODE(ROLE_PAGER,'Y','YES','NO') AS ROLE_PAGER,
						DECODE(ROLE_YARD,'Y','YES','NO') AS ROLE_YARD,
						DECODE(ROLE_REEFER,'Y','YES','NO') AS ROLE_REEFER,
						DECODE(ROLE_QC,'Y','YES','NO') AS ROLE_QC,
						DECODE(ROLE_ITV,'Y','YES','NO') AS ROLE_ITV,
						DECODE(ROLE_PDA,'Y','YES','NO') AS ROLE_PDA,
						A.ID_TERMINAL,
						TERMINAL,
						A.ID_USER_GROUP,
						C.GROUP_NAME
				FROM M_USERS A
				LEFT JOIN (
					SELECT A.ID_USER,LISTAGG(TERMINAL, ', ') WITHIN GROUP (ORDER BY A.ID_USER) AS TERMINAL 
					FROM (
						SELECT A.ID_USER,A.ID_TERMINAL, B.TERMINAL_CODE || '-' || B.TERMINAL_NAME AS TERMINAL
						FROM M_USER_TERMINAL A
						INNER JOIN M_TERMINAL B
						  ON A.ID_TERMINAL = B.ID_TERMINAL AND A.ASSIGN = 1 
					) A
					GROUP BY A.ID_USER
				) D
					ON A.ID_USER = D.ID_USER
				LEFT JOIN M_USER_GROUP C
					ON A.ID_USER_GROUP = C.ID_GROUP
				$qWhere
				$qSort
			) A 
			GROUP BY ROWNUM,ID_USER,FULL_NAME,NICK_NAME,USERNAME,ROLE_GATE,ROLE_TALLY,ROLE_VMT,ROLE_PAGER,ROLE_YARD,ROLE_REEFER,ROLE_QC,ROLE_ITV,ROLE_PDA,TERMINAL,GROUP_NAME
			$qPaging
			ORDER BY ROWNUM";
	$rs = $this->db->query($query);
	$result_list = $rs->result_array();

	$query_count = "SELECT COUNT(*) AS TOTAL FROM M_USERS A
				LEFT JOIN M_TERMINAL B
					ON A.ID_TERMINAL = B.ID_TERMINAL
				LEFT JOIN M_USER_GROUP C
					ON A.ID_USER_GROUP = C.ID_GROUP 
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

    public function check_username($username) {
	$ret = 1;
	$param = array($username);
	$query = "SELECT COUNT(*) AS TOTAL
				    FROM M_USERS
				    WHERE USERNAME=? ";
	$rs = $this->db->query($query, $param);
	$row = $rs->row_array();
	if ($row['TOTAL'] > 0) {
	    $ret = 1;
	} else {
	    $ret = 0;
	}
	return $ret;
    }

    public function save_user($data) {
	$CREATE_USER = $data['CREATE_USER'];
	$FULL_NAME = $data['FULL_NAME'];
	$NICK_NAME = $data['NICK_NAME'];
	$USERNAME = $data['USERNAME'];
	$PASSWORD = md5($data['PASSWORD']);
//	$ROLE_GATE = $data['ROLE_GATE'];
//	$ROLE_TALLY = $data['ROLE_TALLY'];
	$ROLE_PDA = $data['ROLE_PDA'];
	$ROLE_VMT = $data['ROLE_VMT'];
	$ROLE_PAGER = $data['ROLE_PAGER'];
//	$ROLE_YARD = $data['ROLE_YARD'];
//	$ROLE_REEFER = $data['ROLE_REEFER'];
	$ROLE_QC = $data['ROLE_QC'];
//	$ROLE_ITV = $data['ROLE_ITV'];
	$ID_USER_GROUP = $data['GROUP'];
	$terminals = $data['terminal'];
	$CREATE_DATE = date('d-M-y h:i:s A');
	$ID_USER = '';
	if (!isset($FULL_NAME) || $FULL_NAME == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Full Name harus diisi.'
	    );
	}
	if (!isset($USERNAME) || $USERNAME == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Username harus diisi.'
	    );
	}
	if (!isset($PASSWORD) || $PASSWORD == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Password harus diisi.'
	    );
	}

	$query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_USERS WHERE USERNAME = '$USERNAME'";
	$result = $this->db->query($query);
	$row = $result->row_array();
	$count_username = $row['COUNT'];

	if ($count_username != '0') {
	    return array(
		'IsSuccess' => false,
		'Message' => $USERNAME . ' sudah terdaftar.'
	    );
	}


	$this->db->trans_start();
	$getID = $this->db->query('select M_USERS_SEQ.nextval AS ID from dual')->result_array();
	$ID_USER = $getID[0]['ID'];
//	$query = "INSERT INTO ITOS_OP.M_USERS (ID_USER,FULL_NAME,NICK_NAME,USERNAME,PASSWORD,ROLE_GATE,ROLE_TALLY,ROLE_VMT,ROLE_PAGER,ROLE_YARD,ROLE_REEFER,ROLE_QC,ROLE_ITV,ID_USER_GROUP,CREATE_USER,CREATE_DATE)
//			      VALUES ('$ID_USER', '$FULL_NAME','$NICK_NAME','$USERNAME','$PASSWORD','$ROLE_GATE','$ROLE_TALLY','$ROLE_VMT','$ROLE_PAGER','$ROLE_YARD','$ROLE_REEFER','$ROLE_QC','$ROLE_ITV','$ID_USER_GROUP','$CREATE_USER','$CREATE_DATE')";
	$query = "INSERT INTO ITOS_OP.M_USERS (ID_USER,FULL_NAME,NICK_NAME,USERNAME,PASSWORD,ROLE_VMT,ROLE_PDA,ROLE_PAGER,ROLE_QC,ID_USER_GROUP,CREATE_USER,CREATE_DATE)
			      VALUES ('$ID_USER', '$FULL_NAME','$NICK_NAME','$USERNAME','$PASSWORD','$ROLE_VMT','$ROLE_PDA','$ROLE_PAGER','$ROLE_QC','$ID_USER_GROUP','$CREATE_USER','$CREATE_DATE')";
	$this->db->query($query);
	
	//set terminal assign
	$terminal_list = $this->master->get_all_terminal();
	
	foreach ($terminal_list as $list){
	    $assign = 0;
	    if(in_array($list['ID_TERMINAL'], $terminals)){
		$assign = 1;
	    }
	    $cekUserTerminal = $this->db->query("SELECT * FROM ITOS_OP.M_USER_TERMINAL WHERE ID_USER = $ID_USER AND ID_TERMINAL = ".$list['ID_TERMINAL'])->result_array();
	    if(count($cekUserTerminal) < 1){
		$query = "INSERT INTO ITOS_OP.M_USER_TERMINAL (ID_USER,ID_TERMINAL,ACTIVE,ASSIGN)
				  VALUES ('$ID_USER','".$list['ID_TERMINAL']."',0,$assign)";
		$this->db->query($query);
	    }else{
		$query = "UPDATE ITOS_OP.M_USER_TERMINAL SET ASSIGN = $assign WHERE ID_USER = $ID_USER AND ID_TERMINAL = ".$list['ID_TERMINAL'];
		$this->db->query($query);
	    }
	}
	
	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $FULL_NAME . ' berhasil disimpan'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Save gagal.'
	    );
	}
    }

    public function edit_user($data) {
	$MODIFY_USER = $data['MODIFY_USER'];
	$ID_USER = $data['ID_USER'];
	$FULL_NAME = $data['FULL_NAME'];
	$NICK_NAME = $data['NICK_NAME'];
	$USERNAME = $data['USERNAME'];
	$ROLE_PDA = $data['ROLE_PDA'];
//	$ROLE_GATE = $data['ROLE_GATE'];
//	$ROLE_TALLY = $data['ROLE_TALLY'];
	$ROLE_VMT = $data['ROLE_VMT'];
	$ROLE_PAGER = $data['ROLE_PAGER'];
//	$ROLE_YARD = $data['ROLE_YARD'];
//	$ROLE_REEFER = $data['ROLE_REEFER'];
	$ROLE_QC = $data['ROLE_QC'];
//	$ROLE_ITV = $data['ROLE_ITV'];
	$terminals = $data['terminal'];
	$ID_USER_GROUP = $data['GROUP'];
	$MODIFY_DATE = date('d-M-y h:i:s A');

	if (!isset($FULL_NAME) || $FULL_NAME == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Full Name harus diisi.'
	    );
	}
	if (!isset($USERNAME) || $USERNAME == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Username harus diisi.'
	    );
	}

	$query = "SELECT * FROM ITOS_OP.M_USERS WHERE ID_USER = '$ID_USER'";
	$result = $this->db->query($query);
	$row = $result->row_array();
	$USERNAME_old = $row['USERNAME'];
	if ($USERNAME != $USERNAME_old) {
	    $query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_USERS WHERE USERNAME = '$USERNAME'";
	    $result = $this->db->query($query);
	    $row = $result->row_array();
	    $count_username = $row['COUNT'];

	    if ($count_username != '0') {
		return array(
		    'IsSuccess' => false,
		    'Message' => $USERNAME . ' sudah terdaftar.'
		);
	    }
	}
	$this->db->trans_start();
//	$query = "UPDATE ITOS_OP.M_USERS SET 
//			FULL_NAME='$FULL_NAME',
//			NICK_NAME='$NICK_NAME',
//			USERNAME='$USERNAME',
//			ROLE_GATE='$ROLE_GATE',
//			ROLE_TALLY='$ROLE_TALLY',
//			ROLE_VMT='$ROLE_VMT',
//			ROLE_PAGER='$ROLE_PAGER',
//			ROLE_YARD='$ROLE_YARD',
//			ROLE_REEFER='$ROLE_REEFER',
//			ROLE_QC='$ROLE_QC',
//			ROLE_ITV='$ROLE_ITV',
//			ID_USER_GROUP='$ID_USER_GROUP',
//			MODIFY_USER='$MODIFY_USER',
//			MODIFY_DATE='$MODIFY_DATE'
//			WHERE ID_USER=$ID_USER";
	$query = "UPDATE ITOS_OP.M_USERS SET 
			FULL_NAME='$FULL_NAME',
			NICK_NAME='$NICK_NAME',
			USERNAME='$USERNAME',
			ROLE_PDA='$ROLE_PDA',
			ROLE_VMT='$ROLE_VMT',
			ROLE_PAGER='$ROLE_PAGER',
			ROLE_QC='$ROLE_QC',
			ID_USER_GROUP='$ID_USER_GROUP',
			MODIFY_USER='$MODIFY_USER',
			MODIFY_DATE='$MODIFY_DATE'
			WHERE ID_USER=$ID_USER";
//	    echo $query;exit;
	$this->db->query($query);

	$terminal_list = $this->master->get_all_terminal();
	
	foreach ($terminal_list as $list){
	    $assign = 0;
	    if(in_array($list['ID_TERMINAL'], $terminals)){
		$assign = 1;
	    }
	    $cekUserTerminal = $this->db->query("SELECT * FROM ITOS_OP.M_USER_TERMINAL WHERE ID_USER = $ID_USER AND ID_TERMINAL = ".$list['ID_TERMINAL'])->result_array();
	    if(count($cekUserTerminal) < 1){
		$query = "INSERT INTO ITOS_OP.M_USER_TERMINAL (ID_USER,ID_TERMINAL,ACTIVE,ASSIGN)
				  VALUES ('$ID_USER','".$list['ID_TERMINAL']."',0,$assign)";
		$this->db->query($query);
	    }else{
		$query = "UPDATE ITOS_OP.M_USER_TERMINAL SET ASSIGN = $assign WHERE ID_USER = $ID_USER AND ID_TERMINAL = ".$list['ID_TERMINAL'];
		$this->db->query($query);
	    }
	}
	
	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $FULL_NAME . ' berhasil diubah'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Update gagal.'
	    );
	}
    }

    public function delete_user($id_user, $full_name) {
	$this->db->trans_start();
	$query = "DELETE FROM M_USERS WHERE ID_USER=$id_user";
	$this->db->query($query);

	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $full_name . ' berhasil di hapus'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Hapus gagal.'
	    );
	}
    }
    
    public function save_reset_password($data){
	$MODIFY_USER = $data['MODIFY_USER'];
	$ID_USER = $data['ID_USER'];
	$PASSWORD = md5($data['PASSWORD']);
	$MODIFY_DATE = date('d-M-y h:i:s A');
	$this->db->trans_start();
	$query = "UPDATE ITOS_OP.M_USERS SET 
			PASSWORD='$PASSWORD',
			MODIFY_USER='$MODIFY_USER',
			MODIFY_DATE='$MODIFY_DATE'
			WHERE ID_USER=$ID_USER";
//	    echo $query;exit;
	$this->db->query($query);

	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $FULL_NAME . ' berhasil diubah'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Update gagal.'
	    );
	}
	
    }
    
    public function get_roles($paging = false, $sort = false, $filters = false) {
	$qPaging = '';
	if ($paging != false) {
	    $start = $paging['start'] + 1;
	    $end = $paging['page'] * $paging['limit'];
	    $qPaging = "HAVING ROWNUM >= $start AND ROWNUM <= $end";
	}
	$qSort = ' ORDER BY GROUP_NAME ASC';
	if ($sort != false) {
	    $sortProperty = $sort[0]->property;
	    $sortDirection = $sort[0]->direction;
	    $qSort .= ",".$sortProperty . " " . $sortDirection;
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
				if ($field == 'ROLE_GATE' || $field == 'ROLE_TALLY' || $field == 'ROLE_VMT' || $field == 'ROLE_PAGER' || $field == 'ROLE_YARD' || $field == 'ROLE_REEFER' || $field == 'ROLE_QC' || $field == 'ROLE_ITV') {
//							    $value = str_replace('NO','N',str_replace('YES', 'Y', $value));
				    $val = $val == 'YES' ? 'Y' : 'N';
				}
				if ($field == 'TERMINAL') {
				    $arrVal = explode(' - ', $val);
				    $field = 'B.TERMINAL_CODE';
				    $val = $arrVal[0];
				}
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
	
	$query = "SELECT ROWNUM,ID_GROUP,GROUP_NAME FROM (
			SELECT ID_GROUP,GROUP_NAME FROM M_USER_GROUP
			$qWhere
			$qSort
		) A 
		GROUP BY ROWNUM,ID_GROUP,GROUP_NAME
		$qPaging
		ORDER BY ROWNUM";
//	echo '<pre>'.$query.'</pre>';exit;
	$rs = $this->db->query($query);
	$result_list = $rs->result_array();

	$query_count = "SELECT COUNT(*) AS TOTAL FROM M_USER_GROUP $qWhere";
	$rs = $this->db->query($query_count);
	$row = $rs->row_array();
	$total = $row['TOTAL'];

	$data = array(
	    'total' => $total,
	    'data' => $result_list
	);
	return $data;
    }

    function get_group_by_id($id_group) {
	$param = array($id_group);
	$query = "SELECT * FROM M_USER_GROUP WHERE ID_GROUP=?";
	$rs = $this->db->query($query, $param);
	$data = $rs->row_array();
	return $data;
    }
    
    function check_group($group_name) {
	$ret = 1;
	$param = array($group_name);
	$query = "SELECT COUNT(*) AS TOTAL
				    FROM M_USER_GROUP
				    WHERE GROUP_NAME=? ";
	$rs = $this->db->query($query, $param);
	$row = $rs->row_array();
	if ($row['TOTAL'] > 0) {
	    $ret = 1;
	} else {
	    $ret = 0;
	}
	return $ret;
    }

    public function save_group($data) {
	$CREATE_USER = $data['CREATE_USER'];
	$GROUP_NAME = $data['GROUP_NAME'];
	$CREATE_DATE = date('d-M-y h:i:s A');

	if (!isset($GROUP_NAME) || $GROUP_NAME == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Role harus diisi.'
	    );
	}

	$query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_USER_GROUP WHERE GROUP_NAME = '$GROUP_NAME'";
	$result = $this->db->query($query);
	$row = $result->row_array();
	$count_group = $row['COUNT'];

	if ($count_group != '0') {
	    return array(
		'IsSuccess' => false,
		'Message' => $GROUP_NAME . ' sudah terdaftar.'
	    );
	}


	$this->db->trans_start();
	$query = "INSERT INTO ITOS_OP.M_USER_GROUP (ID_GROUP,GROUP_NAME,CREATE_USER,CREATE_DATE)
			      VALUES (M_USER_GROUP_SEQ.nextval,'$GROUP_NAME','$CREATE_USER','$CREATE_DATE')";
	$this->db->query($query);

	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $GROUP_NAME . ' berhasil disimpan'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Save gagal.'
	    );
	}
    }

    public function edit_group($data) {
	$MODIFY_USER = $data['MODIFY_USER'];
	$ID_GROUP = $data['ID_GROUP'];
	$GROUP_NAME = $data['GROUP_NAME'];
	$MODIFY_DATE = date('d-M-y h:i:s A');

	if (!isset($GROUP_NAME) || $GROUP_NAME == '') {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Full Name harus diisi.'
	    );
	}

	$query = "SELECT * FROM ITOS_OP.M_USER_GROUP WHERE ID_GROUP = '$ID_GROUP'";
	$result = $this->db->query($query);
	$row = $result->row_array();
	$GROUP_NAME_old = $row['GROUP_NAME'];
	if ($GROUP_NAME != $GROUP_NAME_old) {
	    $query = "SELECT COUNT(*) COUNT FROM ITOS_OP.M_USER_GROUP WHERE GROUP_NAME = '$GROUP_NAME'";
	    $result = $this->db->query($query);
	    $row = $result->row_array();
	    $count_username = $row['COUNT'];

	    if ($count_username != '0') {
		return array(
		    'IsSuccess' => false,
		    'Message' => $GROUP_NAME . ' sudah terdaftar.'
		);
	    }
	}
	$this->db->trans_start();
	$query = "UPDATE ITOS_OP.M_USER_GROUP SET 
			GROUP_NAME='$GROUP_NAME',
			MODIFY_USER='$MODIFY_USER',
			MODIFY_DATE='$MODIFY_DATE'
			WHERE ID_GROUP=$ID_GROUP";
//	    echo $query;exit;
	$this->db->query($query);

	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $GROUP_NAME . ' berhasil diubah'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Update gagal.'
	    );
	}
    }

    public function delete_group($id_group,$group_name) {
	$this->db->trans_start();
	$query = "DELETE FROM M_USER_GROUP WHERE ID_GROUP=$id_group";
	$this->db->query($query);
	
	$query_upd = "UPDATE M_USERS SET ID_USER_GROUP=NULL WHERE ID_USER_GROUP=$id_group";
	$this->db->query($query_upd);
	
	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => $group_name . ' berhasil di hapus'
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Hapus gagal.'
	    );
	}
    }
    
    public function get_menu_list() {
	$this->db->order_by('PARENT_ID, MENU_ORDER');
	return $this->db->get('M_USER_MENU')->result_array();
	
    }
    
    public function save_assign_menu($data){
	$id_group = $data['id_group'];
	$group_name = $data['group_name'];
	$array = explode("|", $data['val']);
	$countUpdate = 0;
	$this->db->trans_start();
	foreach($array as $arr){
	    $isUpdate = FALSE;
	    $tempArr = explode(':', $arr);
	    $id_menu = $tempArr[0];
	    $bool = $tempArr[1];
	    $new_id_group = "";
	    
	    $qrychk = $this->db->get_where("M_USER_MENU", array("ID_MENU" => $id_menu))->result_array();
	    $id_user_group = $qrychk[0]['ID_USER_GROUP'];
	    if($bool && strpos($id_user_group, ",".$id_group.",") == ''){
		$new_id_group = $id_user_group.','.$id_group.',';
		$isUpdate = TRUE;
	    }elseif(!$bool && strpos($id_user_group, ",".$id_group.",") > -1){
		$isUpdate = TRUE;
		$new_id_group = str_replace(",".$id_group.",", '', $id_user_group);
	    }
	    if($isUpdate){
		$qryUpdate = "UPDATE M_USER_MENU SET ID_USER_GROUP = '$new_id_group' WHERE ID_MENU = $id_menu";
		$this->db->query($qryUpdate);
		$countUpdate++;
	    }
	}
	if($countUpdate > 0){
	    if ($this->db->trans_complete()) {
		return array(
		    'IsSuccess' => true,
		    'Message' => 'Assign menu to '.$group_name . ' success'
		);
	    } else {
		return array(
		    'IsSuccess' => false,
		    'Message' => 'Assign menu to '.$group_name . ' failed'
		);
	    }
	}else{
	    return array(
		    'IsSuccess' => false,
		    'Message' => 'There is no update to '.$group_name
		);
	}
    }
    
    public function user_terminal($id_user){
	$qry = "SELECT * FROM M_USER_TERMINAL WHERE ID_USER = $id_user AND ASSIGN = 1";
	$arr = $this->db->query($qry)->result_array();
	$result = array();
	foreach ($arr as $a){
	    array_push($result, $a['ID_TERMINAL']);
	}
	return $result;
    }
    
    public function activate_user_terminal($id_user,$terminal){
	$this->db->trans_start();
	
	$qry1 = "UPDATE M_USER_TERMINAL SET ACTIVE = 0 WHERE ID_USER = $id_user";
	$this->db->query($qry1);
	
	$qry = "UPDATE M_USER_TERMINAL SET ACTIVE = 1 WHERE ID_USER = $id_user AND ID_TERMINAL = $terminal";
	$this->db->query($qry);
	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => ''
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Activated terminal failed'
	    );
	}
	
    }
    
    public function deactivate_user_terminal($id_user){
	$this->db->trans_start();
	
	$qry1 = "UPDATE M_USER_TERMINAL SET ACTIVE = 0 WHERE ID_USER = $id_user";
	$this->db->query($qry1);
	
	if ($this->db->trans_complete()) {
	    return array(
		'IsSuccess' => true,
		'Message' => ''
	    );
	} else {
	    return array(
		'IsSuccess' => false,
		'Message' => 'Deactivated terminal failed'
	    );
	}
    }
}

?>