<?php
class Yard extends CI_Model {
	public function __construct(){
		$this->load->database();
	}

	public function get_yard_by_id($id) {
		$sql  = "select * from m_yard where ID_TERMINAL = '".$this->gtools->terminal()."' AND id_yard = '".$id."'";
		$query = $this->db->query($sql);
		$result = $query->row();

		return $result;
	}

	public function get_yard_by_block($id_block){
		$sql 	= "SELECT ID_YARD FROM M_YARDBLOCK WHERE ID_BLOCK = '".$id_block."'";
		$query 	= $this->db->query($sql)->row();
		return $query->ID_YARD;
	}

	public function get_slot_single_stok_view($id_ves_voyage,$idyard,$size,$idpod,$type='0',$idblock){
		$cont_type       = ($type != '0') 	  ? "AND C.CONT_TYPE = '$type'" : "AND C.CONT_TYPE NOT IN ('HQ')";
		$mainquery 	= "
						SELECT
							C.YD_SLOT 
						FROM
							CON_LISTCONT C
							JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER 
							AND C.POINT = E.POINT 
						WHERE
							C.ID_COMMODITY = 'G' 
							AND C.ID_CLASS_CODE IN ( 'E', 'TE', 'TC', 'S1', 'S2' ) 
							AND C.YD_BLOCK IS NOT NULL 
							AND C.YD_YARD IN ( '$idyard' ) 
							AND C.ID_POD = '$idpod' 
							AND C.ID_VES_VOYAGE = '$id_ves_voyage' 
							AND C.ID_OP_STATUS NOT IN ( 'SLY' ) 
							AND C.CONT_SIZE = '$size' 
							AND C.YD_BLOCK = '$idblock' 
							$cont_type 
						GROUP BY
							C.YD_SLOT 
						ORDER BY
							C.YD_SLOT
							";
						// debux($mainquery );die();
		
		$query_slot = "SELECT sub.YD_SLOT
								  FROM (
										$mainquery
									) sub
						where ROWNUM <= 1";
		$rs 		= $this->db->query($query_slot);
		$datalist 		= $rs->row_array();
		
		$query_count = "SELECT COUNT( sub.YD_SLOT ) AS total 
								  FROM (
										$mainquery
									) sub";
		$rstotal = $this->db->query($query_count);
		$rowtotal = $rstotal->row_array();
		$total = $rowtotal['TOTAL'];
		
		$data = array (
			'total'=>$total,
			'slot'=>$datalist['YD_SLOT']
		);
		return $data;
	}

	public function get_list_slot_single_stok_view($id_ves_voyage,$idyard,$size,$idpod,$type='0',$idblock){
		$cont_type       = ($type != '0') 	  ? "AND C.CONT_TYPE = '$type'" : "AND C.CONT_TYPE NOT IN ('HQ')";
		$mainquery 	= "
						SELECT
							C.YD_SLOT 
						FROM
							CON_LISTCONT C
							JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER 
							AND C.POINT = E.POINT 
						WHERE
							C.ID_COMMODITY = 'G' 
							AND C.ID_CLASS_CODE IN ( 'E', 'TE', 'TC', 'S1', 'S2' ) 
							AND C.YD_BLOCK IS NOT NULL 
							AND C.YD_YARD IN ( '$idyard' ) 
							AND C.ID_POD = '$idpod' 
							AND C.ID_VES_VOYAGE = '$id_ves_voyage' 
							AND C.ID_OP_STATUS NOT IN ( 'SLY' ) 
							AND C.CONT_SIZE = '$size' 
							AND C.YD_BLOCK = '$idblock' 
							$cont_type 
						GROUP BY
							C.YD_SLOT 
						ORDER BY
							C.YD_SLOT
							";
						// debux($mainquery );die();
		
		$rslot 		= $this->db->query($mainquery);
		$dataslot 		= $rslot->result_array();
		
		return $dataslot;
	}

	public function get_yard_list(){
		$query = "SELECT A.ID_YARD ID_YARD, A.YARD_NAME NAME, COUNT(B.ID_BLOCK) NUM_BLOCK
		FROM M_YARD A
		LEFT JOIN M_YARDBLOCK B
			ON A.ID_YARD=B.ID_YARD
		WHERE (A.ID_YARD <> 0) AND A.ID_TERMINAL='".$this->gtools->terminal()."'
		GROUP BY A.ID_YARD, A.YARD_NAME
		ORDER BY A.ID_YARD ";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_data_yor($filter_block,$id_yard){
		if($filter_block != NULL){
			$where = "AND myb.BLOCK_NAME = '$filter_block'";
		}else{
			$where = " ";
		}

		$query = "SELECT
					my.id_yard,
					my.yard_name,
					myb.id_block,
					myb.block_name,
					myb.capacity,
					CONT_20fcl,
					CONT_20mty,
					CONT_40fcl,
					CONT_40mty,
					(CONT_20fcl + CONT_20mty + (CONT_40fcl * 2) + (CONT_40mty * 2)) AS TEUS
			FROM
				m_yard my
			LEFT JOIN m_yardblock myb ON my.id_yard = myb.id_yard
			LEFT JOIN (
				SELECT
					jp.id_yard,
					jp.id_block,
					SUM (
						CASE
						WHEN clc.cont_size = '20' AND clc.CONT_STATUS  = 'FCL' THEN
							1
						ELSE
							0
						END
					) AS CONT_20fcl,
					SUM (
						CASE
						WHEN clc.cont_size = '20' AND clc.CONT_STATUS  = 'MTY' THEN
							1
						ELSE
							0
						END
					) AS CONT_20mty,
					SUM (
						CASE
						WHEN clc.cont_size = '40' AND clc.CONT_STATUS  = 'FCL' THEN
							1
						ELSE
							0
						END
					) AS CONT_40fcl,
					SUM (
						CASE
						WHEN clc.cont_size = '40' AND clc.CONT_STATUS  = 'MTY' THEN
							1
						ELSE
							0
						END
					) AS CONT_40mty
				FROM
					job_placement jp
				LEFT JOIN con_listcont clc ON jp.no_container = clc.no_container
				AND jp. POINT = clc. POINT
				AND jp.id_ves_voyage = clc.id_ves_voyage
				WHERE jp.ID_TERMINAL = '".$this->gtools->terminal()."' AND clc.ID_TERMINAL='".$this->gtools->terminal()."'
				GROUP BY
					jp.id_yard,
					jp.id_block
			) x ON myb.id_yard = x.id_yard
			AND myb.id_block = x.id_block
			WHERE my.ID_YARD = '$id_yard' AND my.ID_TERMINAL = '".$this->gtools->terminal()."' $where
			ORDER BY
				my.id_yard,
				myb.id_block";

		//debux($query);die;
		// var_dump($query);die();
		$rs 		= $this->db->query($query);
		// echo "<pre>";var_dump($rs); die();
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_blockname(){
		$query ="SELECT ID_BLOCK, BLOCK_NAME
					FROM M_YARDBLOCK
					WHERE ID_BLOCK IN (
						SELECT MAX(ID_BLOCK)
						FROM M_YARDBLOCK
						GROUP BY BLOCK_NAME
					)";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		return $data;
	}

	public function get_vessel(){
		$query ="SELECT ID_VES_VOYAGE, ID_VESSEL ||' '|| VOY_IN ||'/'|| VOY_OUT AS ".'NAME '."FROM ves_voyage WHERE active = 'Y' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		return $data;
	}

	public function get_block_list($id_yard){
		$param = array($id_yard);
		$query 		= "SELECT ID_BLOCK, BLOCK_NAME, CAPACITY, SLOT_, ROW_, TIER_, ORIENTATION FROM M_YARDBLOCK WHERE ID_YARD=? ORDER BY BLOCK_NAME";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_slot_list($id_yard, $id_block){
		$param = array($id_yard, $id_block);
		$query 		= "SELECT SLOT_ FROM M_YARDBLOCK WHERE ID_YARD=? AND ID_BLOCK=?";
		$rs 		= $this->db->query($query, $param);
		$row 		= $rs->row_array();

		$data = array();
		for($i=1;$i<=$row['SLOT_'];$i++){
			$data[] = array('slot'=>$i);
		}

		return $data;
	}

	public function get_slot_list_by_block($id_block){
		$param = array($id_block);
		$query 		= "SELECT SLOT_ FROM M_YARDBLOCK WHERE ID_BLOCK=?";
		$rs 		= $this->db->query($query, $param);
		$row 		= $rs->row_array();

		$data = array();
		for($i=1;$i<=$row['SLOT_'];$i++){
			$data[] = array('slot'=>$i);
		}

		return $data;
	}

	public function get_row_list($id_yard, $id_block){
		$param = array($id_yard, $id_block);
		$query 		= "SELECT ROW_ FROM M_YARDBLOCK WHERE ID_YARD=? AND ID_BLOCK=?";
		$rs 		= $this->db->query($query, $param);
		$row 		= $rs->row_array();

		$data = array();
		for($i=1;$i<=$row['ROW_'];$i++){
			$data[] = array('row'=>$i);
		}

		return $data;
	}

	public function get_row_list_by_block($id_block){
		$param = array($id_block);
		$query 		= "SELECT ROW_ FROM M_YARDBLOCK WHERE ID_BLOCK=?";
		$rs 		= $this->db->query($query, $param);
		$row 		= $rs->row_array();

		$data = array();
		for($i=1;$i<=$row['ROW_'];$i++){
			$data[] = array('row'=>$i);
		}

		return $data;
	}

	public function get_tier_list($id_yard, $id_block){
		$param = array($id_yard, $id_block);
		$query 		= "SELECT TIER_ FROM M_YARDBLOCK WHERE ID_YARD=? AND ID_BLOCK=?";
		$rs 		= $this->db->query($query, $param);
		$row 		= $rs->row_array();

		$data = array();
		for($i=1;$i<=$row['TIER_'];$i++){
			$data[] = array('tier'=>$i);
		}

		return $data;
	}

	public function get_tier_list_by_block($id_block){
		$param = array($id_block);
		$query 		= "SELECT TIER_ FROM M_YARDBLOCK WHERE ID_BLOCK=?";
		$rs 		= $this->db->query($query, $param);
		$row 		= $rs->row_array();

		$data = array();
		for($i=1;$i<=$row['TIER_'];$i++){
			$data[] = array('tier'=>$i);
		}

		return $data;
	}

	public function get_stack_profile_blockInfo($id_yard, $id_block, $slot){
		$param = array($id_yard, $id_block, $slot);
		$query 		= "SELECT A.ID_YARD, A.ID_BLOCK, A.SLOT_, B.YARD_NAME, C.BLOCK_NAME, MIN(A.ROW_) START_ROW_, MAX(A.ROW_) ROW_, MAX(A.TIER_) TIER_
					FROM M_YARDBLOCK_CELL A
					INNER JOIN M_YARDBLOCK C ON A.ID_YARD=C.ID_YARD AND A.ID_BLOCK=C.ID_BLOCK
					INNER JOIN M_YARD B ON A.ID_YARD=B.ID_YARD
					WHERE A.ID_YARD=? AND A.ID_BLOCK=? AND A.SLOT_=?
					GROUP BY A.ID_YARD, A.ID_BLOCK, A.SLOT_, B.YARD_NAME, C.BLOCK_NAME";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row_array();

		return $data;
	}

	public function get_stack_profile_slotInfo($id_yard, $id_block, $slot, $id_ves_voyage=''){
		$param = array($id_yard, $id_block, $slot);
		$query_even1 = '';
		$query_even2 = '';
//		if($slot%2==1){
			$query_even1 = " OR (D.SLOT_+1=$slot AND B.CONT_SIZE>25)";
			$query_even2 = " OR A.SLOT_-1=STACK.SLOT_";
			$query_even3 = " OR (A.GT_JS_SLOT+1=$slot AND A.CONT_SIZE>25)";
			$query_even4 = " OR A.SLOT_-1=PLAN_STACK.GT_JS_SLOT";
//		}
		$q_ves_voyage = ($id_ves_voyage=='') ? '' : " AND STACK.ID_VES_VOYAGE='$id_ves_voyage' ";
		$a_ves_voyage = ($id_ves_voyage=='') ? '' : " AND a.ID_VES_VOYAGE='$id_ves_voyage' ";
		$b_ves_voyage = ($id_ves_voyage=='') ? '' : " AND b.ID_VES_VOYAGE='$id_ves_voyage' ";
		$f_outbound = ($id_ves_voyage=='') ? ' , 0 AS OUTBOUND ' : " , DECODE(STACK.ID_CLASS_CODE,'E',1,'TE',1,0) AS OUTBOUND ";
		$f_selectable = ($id_ves_voyage=='') ? ' , 0 AS SELECTABLE ' : " , DECODE(C.BAY_,'',1,0) AS SELECTABLE ";

		$query = "SELECT
			C.BAY_ OUTB_SEQ_BAY, C.ROW_ OUTB_SEQ_ROW, C.TIER_ OUTB_SEQ_TIER,
			STACK.GT_DATE,
			A.ID_BLOCK,STACK.BLOCK_,A.INDEX_CELL, A.ROW_,A.SLOT_, A.TIER_, STACK.NO_CONTAINER, STACK.POINT, STACK.CONT_SIZE, STACK.CONT_TYPE, STACK.CONT_STATUS, STACK.CONT_HEIGHT, STACK.ID_ISO_CODE, ROUND(STACK.WEIGHT,1) WEIGHT, STACK.ID_POD, STACK.ID_VES_VOYAGE, STACK.ID_COMMODITY, STACK.ID_OPERATOR,STACK.ID_SPEC_HAND,STACK.IMDG,STACK.HAZARD,
			fc_col_vssvc_port(STACK.ID_VES_VOYAGE, STACK.ID_POD) as COLORS,
			STACK.ID_CLASS_CODE $f_outbound $f_selectable,
			DECODE(PLAN_STACK.GT_JS_YARD,'',0,1) PLAN_AREA,
			PLAN_STACK.NO_CONTAINER PLAN_NO_CONTAINER,
			STACK.ID_OP_STATUS ,STACK.EVENT,STACK.FOREGROUND_COLOR,STACK.BACKGROUND_COLOR,STACK.ID_VESSEL,
			/*CASE WHEN STACK.CONT_SIZE > 25 AND A.SLOT_ < STACK.YD_SLOT AND STACK.YD_SLOT = $slot THEN 1
				 WHEN STACK.CONT_SIZE > 25 AND A.SLOT_ > STACK.YD_SLOT AND A.SLOT_ = $slot THEN 1
			 	 WHEN STACK.CONT_SIZE > 25 AND A.SLOT_ = STACK.YD_SLOT AND A.SLOT_ = $slot THEN 1
				 ELSE 0 END AS SLOT_EXT,*/
			CASE WHEN STACK.SLOT_ + 1 = $slot THEN 1 ELSE 0 END AS SLOT_EXT,
			CASE WHEN STACK.ID_VESSEL IS NOT NULL THEN STACK.ID_VESSEL || ' ' || STACK.VOY_IN || '/' || STACK.VOY_OUT ELSE '' END AS VESSEL_VOYAGE,
			NVL(STACK.ID_MACHINE, D.ID_MACHINE) ID_MACHINE,C.\"SEQUENCE\",STACK.OVER_HEIGHT,STACK.OVER_LEFT,STACK.OVER_RIGHT
			FROM M_YARDBLOCK_CELL A
			LEFT JOIN
			(SELECT D.ID_YARD, D.ID_BLOCK, D.BLOCK_, D.SLOT_, D.ROW_, D.TIER, D.NO_CONTAINER, D.POINT, B.CONT_SIZE, B.CONT_TYPE, B.CONT_STATUS, B.CONT_HEIGHT, B.ID_ISO_CODE, (B.WEIGHT/1000) WEIGHT
				, B.ID_POD, B.ID_VES_VOYAGE, B.ID_COMMODITY, B.ID_OPERATOR, B.ID_CLASS_CODE, B.ID_SPEC_HAND, B.IMDG, B.ID_OP_STATUS,JYM.EVENT,B.YD_SLOT,P.FOREGROUND_COLOR,P.BACKGROUND_COLOR,B.HAZARD
				,VV.ID_VESSEL,VV.VOY_IN,VV.VOY_OUT,JYM.ID_MACHINE,B.OVER_HEIGHT,B.OVER_LEFT,B.OVER_RIGHT,
				B.GT_DATE
				FROM
				JOB_PLACEMENT D
				INNER JOIN CON_LISTCONT B
				ON B.NO_CONTAINER=D.NO_CONTAINER AND B.POINT=D.POINT AND D.ID_TERMINAL = B.ID_TERMINAL
				LEFT JOIN JOB_YARD_MANAGER JYM 
				ON D.NO_CONTAINER = JYM.NO_CONTAINER AND D.ID_VES_VOYAGE = JYM.ID_VES_VOYAGE AND D.POINT = JYM.POINT AND B.ID_TERMINAL = JYM.ID_TERMINAL AND JYM.STATUS_FLAG = 'P' AND JYM.EVENT = 'P'
				LEFT JOIN M_PORT P
				ON B.ID_POD = P.PORT_CODE
				LEFT JOIN VES_VOYAGE VV
				ON B.ID_VES_VOYAGE = VV.ID_VES_VOYAGE AND B.ID_TERMINAL = JYM.ID_TERMINAL
				WHERE (D.SLOT_=$slot $query_even1) $b_ves_voyage AND B.ID_TERMINAL = '".$this->gtools->terminal()."') STACK
			ON A.ID_YARD=STACK.ID_YARD AND A.ID_BLOCK=STACK.ID_BLOCK AND (A.SLOT_=STACK.SLOT_ 
			$query_even2) AND A.ROW_=STACK.ROW_ AND A.TIER_=STACK.TIER
			LEFT JOIN CON_OUTBOUND_SEQUENCE C
				ON STACK.NO_CONTAINER=C.NO_CONTAINER AND STACK.POINT=C.POINT
			LEFT JOIN (
				SELECT A.NO_CONTAINER,A.POINT,A.GT_JS_YARD,A.GT_JS_BLOCK,GT_JS_ROW,GT_JS_SLOT,GT_JS_TIER,A.ID_VES_VOYAGE
				FROM CON_LISTCONT A
				INNER JOIN JOB_YARD_MANAGER B
					ON A.NO_CONTAINER = B.NO_CONTAINER AND A.POINT = B.POINT
				WHERE (A.GT_JS_SLOT=$slot $query_even3) AND B.STATUS_FLAG = 'P' AND B.EVENT = 'P' AND A.GT_JS_BLOCK IS NOT NULL AND A.GT_JS_YARD IS NOT NULL $a_ves_voyage
			) PLAN_STACK
			ON A.ID_YARD = PLAN_STACK.GT_JS_YARD AND A.ID_BLOCK=PLAN_STACK.GT_JS_BLOCK AND (A.SLOT_=PLAN_STACK.GT_JS_SLOT 
			 $query_even4) AND A.ROW_=PLAN_STACK.GT_JS_ROW AND A.TIER_=PLAN_STACK.GT_JS_TIER
			LEFT JOIN MCH_PLAN D
		 	ON A.ID_YARD = D.ID_YARD AND A.ID_BLOCK = D.ID_BLOCK AND A.SLOT_ = D.SLOT_ AND A.ROW_ = D.ROW_
			WHERE A.ID_YARD=? AND A.ID_BLOCK=? AND A.SLOT_=?
			ORDER BY A.TIER_ DESC, A.ROW_ ASC";
//		echo '<pre>'.$query.'</pre>';exit;
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
//		echo '<pre>'.$this->db->last_query().'</pre>';

		return $data;
	}

	public function get_stack_profile_slotInfohk($id_yard, $id_block, $slot){
		if($slot%2==1){
			$query_even1 = " OR (D.GT_JS_SLOT-1=$slot AND B.CONT_SIZE!='20')";
			$query_even2 = " OR A.SLOT_+1=STACK.SLOT_";
		}

		$query = "SELECT
			A.INDEX_CELL, A.ROW_, A.TIER_, STACK.NO_CONTAINER , A.ID_BLOCK, A.SLOT_
			FROM M_YARDBLOCK_CELL A
			JOIN
			(SELECT D.GT_JS_YARD ID_YARD, D.GT_JS_BLOCK ID_BLOCK, D.GT_JS_SLOT SLOT_, D.GT_JS_ROW ROW_, D.GT_JS_TIER TIER,
			D.NO_CONTAINER, D.POINT, B.CONT_SIZE, B.ID_ISO_CODE, (B.WEIGHT/1000) WEIGHT, B.ID_POD, B.ID_VES_VOYAGE, B.ID_COMMODITY, B.ID_OPERATOR, B.ID_CLASS_CODE, B.ID_SPEC_HAND, B.IMDG
				FROM
				CON_HKP_PLAN_D D
				JOIN CON_LISTCONT B
				ON B.NO_CONTAINER=D.NO_CONTAINER AND B.POINT=D.POINT
				LEFT JOIN YARD_PLAN YDP
				ON D.GT_JS_YARD=YDP.ID_YARD AND D.GT_JS_BLOCK=YDP.ID_BLOCK AND D.GT_JS_SLOT=YDP.SLOT_ AND D.GT_JS_ROW=YDP.ROW_ AND D.GT_JS_TIER=YDP.TIER_
				WHERE (D.GT_JS_SLOT=$slot $query_even1) AND D.ID_TERMINAL = '".$this->gtools->terminal()."' AND B.ID_TERMINAL = '".$this->gtools->terminal()."' 
				and D.HKP_STATUS_CONT!='C'
				AND YDP.FLAG_STATUS=0
				) STACK
			ON A.ID_YARD=STACK.ID_YARD AND A.ID_BLOCK=STACK.ID_BLOCK AND (A.SLOT_=STACK.SLOT_ $query_even2) AND A.ROW_=STACK.ROW_ AND A.TIER_=STACK.TIER
			WHERE A.ID_YARD=$id_yard AND A.ID_BLOCK=$id_block AND A.SLOT_=$slot
			ORDER BY A.TIER_ DESC, A.ROW_ ASC";
		// print_r $query;
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function insert_yard($xml_str, $yard_name, $north_orientation, $sea_position){
		$xml = simplexml_load_string($xml_str);

		$width  	= $xml->width;
		$height 	= $xml->height;

		$this->db->trans_start();

		$query = "SELECT MAX(ID_YARD) AS MAX_ID FROM M_YARD";
		$rs = $this->db->query($query);
		$row = $rs->row_array();
		$id = 1;
		if ($row['MAX_ID']){
			$id = $row['MAX_ID']+1;
		}

		$yard_id = $id;

		$total = $width * $height;

		for($i = 0; $i < $total; $i++){
			$query_yard_cell = "INSERT INTO M_YARD_CELL(ID_YARD, INDEX_CELL) VALUES('$yard_id', $i)";

			$this->db->query($query_yard_cell);
		}

		$stack_cell = $xml->index;
		$index 		= explode(",", $stack_cell);

		$index_sum	 	= count($index);

		foreach ($index as $index_){
			$query_update_stack = "UPDATE M_YARD_CELL SET STATUS_STACK = 1 WHERE INDEX_CELL = '$index_' AND ID_YARD = '$yard_id'";
			$this->db->query($query_update_stack);
		}

		$block 			= $xml->block;
		$block_sum	 	= count($block);

		foreach ($block as $block_){
			$block_name  = $block_->name;
			$block_tier = $block_->tier;
			$block_position = $block_->position;
			$block_orientation = $block_->orientation;
			$block_width = $block_->width;
			$block_height = $block_->height;
			$block_color = $block_->color;
			$block_capacity = $block_tier*$block_width*$block_height;

			if ($block_color==""){
				$block_color = 'BLACK';
			}

			if($block_position=="H"){
				$block_slot = $block_width;
				$block_row = $block_height;
			}else if($block_position=="V"){
				$block_slot = $block_height;
				$block_row = $block_width;
			}

			$query = "SELECT MAX(ID_BLOCK) AS MAX_ID FROM M_YARDBLOCK";
			$rs 		= $this->db->query($query);
			$row 		= $rs->row_array();
			$id = 1;
			if ($row['MAX_ID']){
				$id = $row['MAX_ID']+1;
			}
			$query_blocking_area = "INSERT INTO M_YARDBLOCK(ID_BLOCK, ID_YARD, BLOCK_NAME, COLOR, POSISI, TIER_, ORIENTATION, CAPACITY, SLOT_, ROW_) VALUES('$id', '$yard_id', '$block_name', '$block_color', '$block_position', '$block_tier', '$block_orientation', '$block_capacity', '$block_slot', '$block_row')";
			$this->db->query($query_blocking_area);

			$block_id  		= $id;

			$cell	= explode(",",$block_->cell);
			$cell_sum	= count($cell);

			for ($j = 0; $j < $cell_sum; $j++){
				//set row and slot
				if($j == 0){
					if ($block_position=="H"){
						if ($block_orientation=="TL"){
							$slot = 1;
							$row_ = 1;
						}else if ($block_orientation=="TR"){
							$slot = $block_slot;
							$row_ = 1;
						}else if ($block_orientation=="BL"){
							$slot = 1;
							$row_ = $block_row;
						}else if ($block_orientation=="BR"){
							$slot = $block_slot;
							$row_ = $block_row;
						}
					}else if ($block_position=="V"){
						if ($block_orientation=="TL"){
							$row_ = 1;
							$slot = 1;
						}else if ($block_orientation=="TR"){
							$row_ = $block_row;
							$slot = 1;
						}else if ($block_orientation=="BL"){
							$row_ = 1;
							$slot = $block_slot;
						}else if ($block_orientation=="BR"){
							$row_ = $block_row;
							$slot = $block_slot;
						}
					}
				}else{
					if ($block_position=="H"){
						if ($block_orientation=="TL"){
							if($cell[$j-1] == ($cell[$j]-1)){
								$slot++;
							}else{
								$row_++;
								$slot = 1;
							}
						}else if ($block_orientation=="TR"){
							if($cell[$j-1] == ($cell[$j]-1)){
								$slot -= 1;
							}else{
								$row_++;
								$slot = $block_slot;
							}
						}else if ($block_orientation=="BL"){
							if($cell[$j-1] == ($cell[$j]-1)){
								$slot++;
							}else{
								$row_ -= 1;
								$slot = 1;
							}
						}else if ($block_orientation=="BR"){
							if($cell[$j-1] == ($cell[$j]-1)){
								$slot -= 1;
							}else{
								$row_ -= 1;
								$slot = $block_slot;
							}
						}
					}else if ($block_position=="V"){
						if ($block_orientation=="TL"){
							if($cell[$j-1] == ($cell[$j]-1)){
								$row_++;
							}else{
								$slot++;
								$row_ = 1;
							}
						}else if ($block_orientation=="TR"){
							if($cell[$j-1] == ($cell[$j]-1)){
								$row_ -= 1;
							}else{
								$slot++;
								$row_ = $block_row;
							}
						}else if ($block_orientation=="BL"){
							if($cell[$j-1] == ($cell[$j]-1)){
								$row_++;
							}else{
								$slot -= 1;
								$row_ = 1;
							}
						}else if ($block_orientation=="BR"){
							if($cell[$j-1] == ($cell[$j]-1)){
								$row_ -= 1;
							}else{
								$slot -= 1;
								$row_ = $block_row;
							}
						}
					}
				}

				// loop tier
				for ($k = 1; $k <= $block_tier; $k++){
					$query_block_cell = "INSERT INTO M_YARDBLOCK_CELL(ID_YARD, INDEX_CELL, ID_BLOCK, ROW_, SLOT_, TIER_) VALUES('$yard_id', $cell[$j], $block_id, $row_, $slot, $k)";
					$this->db->query($query_block_cell);
				}
			}
		}

		$query_yard_area = "INSERT INTO M_YARD(ID_YARD, YARD_NAME, WIDTH, HEIGHT, NORTH_ORIENTATION, ID_TERMINAL, SEA_ORIENTATION) VALUES('$yard_id', '$yard_name', '$width', '$height', '$north_orientation', '".$this->gtools->terminal()."', '$sea_position')";
		// echo $query_yard_area;
		$this->db->query($query_yard_area);

		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
	}

	public function insert_plan_yard($id_yard, $xml_str, $act){
		$xml = simplexml_load_string($xml_str);

		$block = $xml->block;
		$block_id = $block->block_id;
		$category_id = $xml->category_id;
		$index = $block->index;
		$index_arr = explode(",",$index);

		if($act == 'edit'){
			$id = $xml->id_yard_plan;
			$qryDel = "DELETE FROM YARD_PLAN WHERE ID_YARD = $id_yard AND ID_YARD_PLAN = $id";
			$this->db->query($qryDel);
			$qryDelGroup = "DELETE FROM YARD_PLAN_GROUP WHERE ID_YARD = $id_yard AND ID_YARD_PLAN = $id";
			$this->db->query($qryDelGroup);
		}else{
			$query = "SELECT MAX(ID_YARD_PLAN) AS MAX_ID FROM YARD_PLAN_GROUP";
			$rs 		= $this->db->query($query);
			$row 		= $rs->row_array();
			$id = 1;
			if ($row['MAX_ID']){
				$id = $row['MAX_ID']+1;
			}
		}
		$max_slot=0;
		$min_slot=0;
		$max_row=0;
		$min_row=0;

		$query = "SELECT TIER_ FROM M_YARDBLOCK
					WHERE ID_YARD='$id_yard' AND ID_BLOCK='$block_id'";
		$rs = $this->db->query($query);
		$data_tier = $rs->row_array();
		$tier = $data_tier['TIER_'];
			
		foreach($index_arr as $cell){
			$query = "SELECT SLOT_,ROW_,TIER_ FROM M_YARDBLOCK_CELL
						WHERE ID_YARD='$id_yard' AND ID_BLOCK='$block_id' AND INDEX_CELL='$cell'
						ORDER BY SLOT_,ROW_,TIER_";
			$rs = $this->db->query($query);
			$data_slot_row_tier = $rs->result_array();

			if ($min_slot==0){
				$min_slot = $data_slot_row_tier[0]['SLOT_'];
				$max_slot = $data_slot_row_tier[0]['SLOT_'];
				$min_row = $data_slot_row_tier[0]['ROW_'];
				$max_row = $data_slot_row_tier[0]['ROW_'];
			}else{
				if ($data_slot_row_tier[0]['SLOT_']>$max_slot){
					$max_slot = $data_slot_row_tier[0]['SLOT_'];
				}else if ($data_slot_row_tier[0]['SLOT_']<$min_slot){
					$min_slot = $data_slot_row_tier[0]['SLOT_'];
				}
				if ($data_slot_row_tier[0]['ROW_']>$max_row){
					$max_row = $data_slot_row_tier[0]['ROW_'];
				}else if ($data_slot_row_tier[0]['ROW_']<$min_row){
					$min_row = $data_slot_row_tier[0]['ROW_'];
				}
			}

			foreach ($data_slot_row_tier as $slot_row_tier){
				$query = "SELECT COUNT(A.NO_CONTAINER) JUMLAH
						FROM JOB_PLACEMENT A
						INNER JOIN CON_LISTCONT B
						ON A.NO_CONTAINER=B.NO_CONTAINER AND A.POINT=B.POINT AND A.ID_TERMINAL = B.ID_TERMINAL
						WHERE A.ID_YARD='$id_yard' AND A.ID_BLOCK='$block_id' AND (A.SLOT_='".$slot_row_tier['SLOT_']."' OR (A.SLOT_='".($slot_row_tier['SLOT_']+1)."' AND B.CONT_SIZE >= 40)) AND A.ROW_='".$slot_row_tier['ROW_']."' AND A.TIER='".$slot_row_tier['TIER_']."' AND A.ID_TERMINAL = '".$this->gtools->terminal()."'";
				$rs = $this->db->query($query);
				$check_placement = $rs->row_array();
				
				$query_cek_plan = "SELECT COUNT(*) JUMLAH FROM JOB_YARD_MANAGER A 
					    INNER JOIN CON_LISTCONT B ON A.NO_CONTAINER = B.NO_CONTAINER AND A.POINT = B.POINT AND A.ID_TERMINAL = B.ID_TERMINAL
					    WHERE A.EVENT = 'P' AND A.STATUS_FLAG = 'P' AND B.GT_JS_YARD='$id_yard' AND B.GT_JS_BLOCK='$block_id' AND (B.GT_JS_SLOT='".$slot_row_tier['SLOT_']."' OR (B.GT_JS_SLOT='".($slot_row_tier['SLOT_']+1)."' AND B.CONT_SIZE >= 40)) AND B.GT_JS_ROW='".$slot_row_tier['ROW_']."' AND B.GT_JS_TIER='".$slot_row_tier['TIER_']."' AND A.ID_TERMINAL = '".$this->gtools->terminal()."'";
				$rs_cek_plan = $this->db->query($query_cek_plan);
				$check_plan = $rs_cek_plan->row_array();
				$flag_status = '0';
				if ($check_plan['JUMLAH']>0){
					$flag_status = '1';
				}
				if ($check_placement['JUMLAH']>0){
					$flag_status = '2';
				}
				$query_plan_cell = "INSERT INTO YARD_PLAN(ID_YARD, ID_BLOCK, INDEX_CELL, SLOT_, ROW_, TIER_, ID_CATEGORY, ID_YARD_PLAN, FLAG_STATUS)
				VALUES('$id_yard', '$block_id', '$cell', '".$slot_row_tier['SLOT_']."', '".$slot_row_tier['ROW_']."', '".$slot_row_tier['TIER_']."', $category_id, '$id', '$flag_status')";
				$this->db->query($query_plan_cell);
			}
		}

		$capacity = ($max_slot-$min_slot+1)*($max_row-$min_row+1)*$tier;

		$query 	= "INSERT INTO YARD_PLAN_GROUP
					(ID_YARD_PLAN, ID_YARD, ID_BLOCK, START_SLOT, END_SLOT, START_ROW, END_ROW, ID_CATEGORY, CAPACITY) VALUES('$id', '$id_yard', '$block_id', '$min_slot', '$max_slot', '$min_row', '$max_row', '$category_id', '$capacity')";
		$rs 	= $this->db->query($query);

		return 1;
	}

	public function extract_yard($id_yard){
		$xml_str = "";
		$query 		= "SELECT * FROM M_YARD WHERE ID_YARD='$id_yard' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$rs 		= $this->db->query($query);
		$row 		= $rs->row_array();

		$width_str = "<width>".$row['WIDTH']."</width>";
		$height_str = "<height>".$row['HEIGHT']."</height>";
		$yard_name = "<name>".$row['YARD_NAME']."</name>";

		$query 		= "SELECT * FROM M_YARD_VIEW WHERE ID_YARD='$id_yard' ORDER BY INDEX_CELL";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		$index_stack = array();
		$index_slot = array();
		$index_row = array();
		foreach($data as $row){
			if ($row['STATUS_STACK']==1){
				$index_stack[] = $row['INDEX_CELL'];
				$index_slot[] = $row['SLOT_'];
				$index_row[] = $row['ROW_'];
			}
		}
		$stack_ = implode(",",$index_stack);
		$slot_ = implode(",",$index_slot);
		$row_ = implode(",",$index_row);
		$stack_str = "<index>".$stack_."</index>";
		$slot_str = "<slot>".$slot_."</slot>";
		$row_str = "<row>".$row_."</row>";

		$query 		= "SELECT * FROM M_YARDBLOCK WHERE ID_YARD='$id_yard'";
		$rs 		= $this->db->query($query);
		$data_block = $rs->result_array();
		$index_block = array();
		for ($i=0;$i<sizeof($data_block);$i++){
			$index_block[] = array();
			foreach($data as $row){
				if ($row['BLOCK_NAME']==$data_block[$i]['BLOCK_NAME']){
					$index_block[$i][] = $row['INDEX_CELL'];
				}
			}
			if ($data_block[$i]['POSISI']=="H"){
				$data_block[$i]['WIDTH'] = $data_block[$i]['SLOT_'];
				$data_block[$i]['HEIGHT'] = $data_block[$i]['ROW_'];
			}else if ($data_block[$i]['POSISI']=="V"){
				$data_block[$i]['WIDTH'] = $data_block[$i]['ROW_'];
				$data_block[$i]['HEIGHT'] = $data_block[$i]['SLOT_'];
			}
			if (!$data_block[$i]['COLOR']){
				$data_block[$i]['COLOR']="BLACK";
			}
		}

		$block_str = "";
		for ($i=0;$i<sizeof($data_block);$i++){
			$block_str .= "<block><id_block>".$data_block[$i]['ID_BLOCK']."</id_block><name>".$data_block[$i]['BLOCK_NAME']."</name><color>".$data_block[$i]['COLOR']."</color><tier>".$data_block[$i]['TIER_']."</tier><position>".$data_block[$i]['POSISI']."</position><orientation>".$data_block[$i]['ORIENTATION']."</orientation><height>".$data_block[$i]['HEIGHT']."</height><width>".$data_block[$i]['WIDTH']."</width><cell>".join(",",$index_block[$i])."</cell></block>";
		}

		$xml_str = "<yard>".$width_str.$height_str.$yard_name.$stack_str.$slot_str.$row_str.$block_str."</yard>";

		return $xml_str;
	}

	public function extract_yard_plan($id_yard){
		$xml_str = "";
		$query 		= "SELECT * FROM M_YARD WHERE ID_YARD='$id_yard' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$rs 		= $this->db->query($query);
		$row 		= $rs->row_array();

		$width_str = "<width>".$row['WIDTH']."</width>";
		$height_str = "<height>".$row['HEIGHT']."</height>";
		$north_or = "<north_orientation>".$row['NORTH_ORIENTATION']."</north_orientation>";

//		$query = "SELECT V_PLAN.*,0 PLACEMENT,0 JML_PLACEMENT
//				FROM  (SELECT ID_YARD,INDEX_CELL,STATUS_STACK,ID_BLOCK,BLOCK_NAME,COLOR,POSISI,ORIENTATION,TIER_,SLOT_,ROW_,BLOCK_LABEL,MAX(FLAG_STATUS) FLAG_STATUS,SUM(DECODE(NVL(FLAG_STATUS,0),0,0,1)) JML_TAKEN	
//						FROM (
//							SELECT DISTINCT V_CELL.*,D.TIER_ AS YARD_PLAN_TIER,D.FLAG_STATUS
//							FROM (SELECT * FROM M_YARD_VIEW WHERE ID_YARD='$id_yard' ORDER BY INDEX_CELL) V_CELL
//								 LEFT JOIN YARD_PLAN D ON V_CELL.ID_BLOCK=D.ID_BLOCK AND V_CELL.INDEX_CELL=D.INDEX_CELL AND V_CELL.SLOT_=D.SLOT_ AND V_CELL.ROW_=D.ROW_
//						) A 
//						GROUP BY ID_YARD,INDEX_CELL,STATUS_STACK,ID_BLOCK,BLOCK_NAME,COLOR,POSISI,ORIENTATION,TIER_,SLOT_,ROW_,BLOCK_LABEL
//						ORDER BY INDEX_CELL) V_PLAN
//				--LEFT JOIN YD_PLACEMENT_YARD E ON V_PLAN.ID_BLOCKING_AREA=E.ID_BLOCKING_AREA AND V_PLAN.INDEX_CELL=E.ID_CELL AND V_PLAN.SLOT_=E.SLOT_YARD AND V_PLAN.ROW_=E.ROW_YARD
//				GROUP BY V_PLAN.ID_YARD,V_PLAN.INDEX_CELL,V_PLAN.STATUS_STACK,V_PLAN.ID_BLOCK,V_PLAN.BLOCK_NAME,V_PLAN.COLOR,V_PLAN.POSISI,V_PLAN.ORIENTATION,V_PLAN.TIER_,V_PLAN.SLOT_,V_PLAN.ROW_, V_PLAN.BLOCK_LABEL,V_PLAN.FLAG_STATUS,V_PLAN.JML_TAKEN
//				ORDER BY V_PLAN.INDEX_CELL";
		$query = "SELECT V_PLAN.*,0 PLACEMENT,0 JML_PLACEMENT, V_CON.ID_POD, V_CON.FOREGROUND_COLOR
				FROM  (SELECT ID_YARD,INDEX_CELL,STATUS_STACK,ID_BLOCK,BLOCK_NAME,COLOR,POSISI,ORIENTATION,TIER_,SLOT_,ROW_,BLOCK_LABEL,MAX(FLAG_STATUS) FLAG_STATUS,SUM(DECODE(NVL(FLAG_STATUS,0),0,0,1)) JML_TAKEN	
					FROM (
						SELECT DISTINCT V_CELL.*,D.TIER_ AS YARD_PLAN_TIER,D.FLAG_STATUS
						FROM (SELECT * FROM M_YARD_VIEW WHERE ID_YARD='$id_yard' ORDER BY INDEX_CELL) V_CELL
						LEFT JOIN YARD_PLAN D ON V_CELL.ID_BLOCK=D.ID_BLOCK AND V_CELL.INDEX_CELL=D.INDEX_CELL AND V_CELL.SLOT_=D.SLOT_ AND V_CELL.ROW_=D.ROW_
					) A 
					GROUP BY ID_YARD,INDEX_CELL,STATUS_STACK,ID_BLOCK,BLOCK_NAME,COLOR,POSISI,ORIENTATION,TIER_,SLOT_,ROW_,BLOCK_LABEL
					ORDER BY INDEX_CELL) V_PLAN
				LEFT JOIN (
				SELECT B.ID_VES_VOYAGE,B.NO_CONTAINER,B.ID_YARD,B.ID_BLOCK,B.SLOT_,B.ROW_,B.TIER,C.ID_POD,D.FOREGROUND_COLOR FROM (
					SELECT ID_YARD,ID_BLOCK,SLOT_,ROW_,MAX(TIER) TIER
					FROM JOB_PLACEMENT 
					WHERE ID_YARD = '$id_yard' AND ID_TERMINAL = '".$this->gtools->terminal()."'
					GROUP BY ID_YARD,ID_BLOCK,SLOT_,ROW_
					ORDER BY ID_YARD,ID_BLOCK,SLOT_,ROW_
				) A
				INNER JOIN JOB_PLACEMENT B ON A.ID_YARD = B.ID_YARD AND A.ID_BLOCK = B.ID_BLOCK AND A.SLOT_ = B.SLOT_ AND A.ROW_ = B.ROW_ AND A.TIER = B.TIER
				INNER JOIN CON_LISTCONT C ON B.NO_CONTAINER = C.NO_CONTAINER AND B.ID_VES_VOYAGE = C.ID_VES_VOYAGE AND B.POINT = C.POINT
				LEFT JOIN M_PORT D ON C.ID_POD = D.PORT_CODE
				WHERE B.ID_TERMINAL = '".$this->gtools->terminal()."' AND C.ID_TERMINAL = '".$this->gtools->terminal()."'
				ORDER BY A.ID_BLOCK,A.SLOT_,A.ROW_,A.TIER
				) V_CON ON V_PLAN.ID_BLOCK = V_CON.ID_BLOCK AND V_PLAN.SLOT_ = V_CON.SLOT_ AND V_PLAN.ROW_= V_CON.ROW_
				ORDER BY V_PLAN.INDEX_CELL";

		//debux($query);die;

		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
//		echo '<pre>'. $this->db->last_query().'</pre>';exit;
		$index_stack = array();
		$index_plan = array();
		$index_taken = array();
		$index_placement = array();
		$index_slot = array();
		$index_row = array();
		$index_tier = array();
		$index_title = array();
		$index_block_id = array();
		$index_orientation = array();
		$index_position = array();
		$index_label = array();
		$index_label_text = array();
		$index_bgcolor = array();

		foreach($data as $row){
			if ($row['STATUS_STACK']==1){
				$index_stack[] = $row['INDEX_CELL'];
				$index_slot[] = $row['SLOT_'];
				$index_row[] = $row['ROW_'];
				$index_tier[] = $row['TIER_'];
				$index_title[] = $row['BLOCK_NAME'];
				$index_block_id[] = $row['ID_BLOCK'];
				$index_orientation[] = $row['ORIENTATION'];
				$index_position[] = $row['POSISI'];
				$index_placement[] = $row['JML_PLACEMENT'];
				$delta = $row['JML_TAKEN']-$row['JML_PLACEMENT'];
//				 if ($delta>2){
//					 $delta = 2;
//				 }
				$index_taken[] = $delta;
				$index_bgcolor[] = $row['FOREGROUND_COLOR'];
			}
			if ($row['FLAG_STATUS']!=""){
				$index_plan[] = $row['INDEX_CELL'];
			}
			if ($row['BLOCK_LABEL']!=""){
				$index_label[] = $row['INDEX_CELL'];
				$index_label_text[] = $row['BLOCK_LABEL'];
			}
		}

		$stack_ = implode(",",$index_stack);
		$plan_ = implode(",",$index_plan);
		$taken_ = implode(",",$index_taken);
		$placement_ = implode(",",$index_placement);
		$slot_ = implode(",",$index_slot);
		$row_ = implode(",",$index_row);
		$tier_ = implode(",",$index_tier);
		$title = implode(",",$index_title);
		$block_id = implode(",",$index_block_id);
		$orientation = implode(",",$index_orientation);
		$position = implode(",",$index_position);
		$label_ = implode(",",$index_label);
		$label_text_ = implode(",",$index_label_text);
		$bgcolor = implode(",",$index_bgcolor);

		$stack_str = "<index>".$stack_."</index>";
		$plan_str = "<plan>".$plan_."</plan>";
		$taken_str = "<taken>".$taken_."</taken>";
		$placement_str = "<placement>".$placement_."</placement>";
		$slot_str = "<slot>".$slot_."</slot>";
		$row_str = "<row>".$row_."</row>";
		$tier_str = "<tier>".$tier_."</tier>";
		$title_str = "<title>".$title."</title>";
		$block_id_str = "<block_id>".$block_id."</block_id>";
		$orientation_str = "<orientation>".$orientation."</orientation>";
		$position_str = "<position>".$position."</position>";
		$label_str = "<label>".$label_."</label>";
		$label_text_str = "<label_text>".$label_text_."</label_text>";
		$bgcolor_str = "<bgcolor>".$bgcolor."</bgcolor>";

		$xml_str = "<yard>".$width_str.$height_str.$north_or.$stack_str.$plan_str.$taken_str.$placement_str.$slot_str.$row_str.$tier_str.$title_str.$block_id_str.$orientation_str.$position_str.$label_str.$label_text_str.$bgcolor_str."</yard>";

		//debux (json_encode($xml_str));die;
		
		return $xml_str;

	}
	public function yard_stacking_info($id_yard){
	    $qry = "SELECT Y.ID_YARD,Y.ID_BLOCK,Y.SLOT_,Y.ROW_--,NVL(A.TOTAL,0),NVL(B.TOTAL,0),NVL(C.TOTAL,0)
				    ,CON.TIER_ AS GREATEST_TIER
				    ,CON.ID_POD ID_POD,P.FOREGROUND_COLOR FOREGROUND_COLOR
				    ,P.BACKGROUND_COLOR  BACKGROUND_COLOR
				    , (NVL(A.TOTAL,0) + NVL(B.TOTAL,0) + NVL(C.TOTAL,0) + NVL(D.TOTAL,0)) AS TOTAL
		    FROM M_YARDBLOCK_CELL Y
		    LEFT JOIN (
			    SELECT YD_YARD,YD_BLOCK,YD_SLOT,YD_ROW,MAX(YD_TIER) AS TIER_,COUNT(*) AS TOTAL
			    FROM CON_LISTCONT
			    WHERE YD_YARD = $id_yard AND ID_OP_STATUS IN ('YYY','YGY','YSY')
			    GROUP BY YD_YARD,YD_BLOCK,YD_SLOT,YD_ROW
		    ) A ON Y.ID_YARD = A.YD_YARD AND Y.ID_BLOCK = A.YD_BLOCK AND Y.SLOT_ = A.YD_SLOT AND Y.ROW_ = A.YD_ROW
		    LEFT JOIN (
			    SELECT GT_JS_YARD,GT_JS_BLOCK,GT_JS_SLOT,GT_JS_ROW,MAX(GT_JS_TIER) AS TIER_,COUNT(*) AS TOTAL
			    FROM CON_LISTCONT
			    WHERE GT_JS_YARD = $id_yard AND ID_OP_STATUS = CASE WHEN ID_CLASS_CODE IN ('I','TI') THEN 'SDY' ELSE 'GIY' END 
			    GROUP BY GT_JS_YARD,GT_JS_BLOCK,GT_JS_SLOT,GT_JS_ROW
		    ) B ON Y.ID_YARD = B.GT_JS_YARD AND Y.ID_BLOCK = B.GT_JS_BLOCK AND Y.SLOT_ = B.GT_JS_SLOT AND Y.ROW_ = B.GT_JS_ROW
		    LEFT JOIN (
			    SELECT YD_YARD,YD_BLOCK,(YD_SLOT + 1) YD_SLOT,YD_ROW,MAX(YD_TIER) AS TIER_,COUNT(*) AS TOTAL
			    FROM CON_LISTCONT
			    WHERE YD_YARD = $id_yard AND ID_OP_STATUS IN ('YYY','YGY','YSY')
				    AND CONT_SIZE >= 40
			    GROUP BY YD_YARD,YD_BLOCK,YD_SLOT,YD_ROW
		    ) C ON Y.ID_YARD = C.YD_YARD AND Y.ID_BLOCK = C.YD_BLOCK AND Y.SLOT_ = C.YD_SLOT AND Y.ROW_ = C.YD_ROW
		    LEFT JOIN (
			    SELECT GT_JS_YARD,GT_JS_BLOCK,(GT_JS_SLOT + 1) AS GT_JS_SLOT,GT_JS_ROW,MAX(GT_JS_TIER) AS TIER_,COUNT(*) AS TOTAL
			    FROM CON_LISTCONT
			    WHERE GT_JS_YARD = $id_yard AND ID_OP_STATUS = CASE WHEN ID_CLASS_CODE IN ('I','TI') THEN 'SDY' ELSE 'GIY' END 
				    AND CONT_SIZE >= 40
			    GROUP BY GT_JS_YARD,GT_JS_BLOCK,GT_JS_SLOT,GT_JS_ROW
		    ) D ON Y.ID_YARD = D.GT_JS_YARD AND Y.ID_BLOCK = D.GT_JS_BLOCK AND Y.SLOT_ = D.GT_JS_SLOT AND Y.ROW_ = D.GT_JS_ROW
		    JOIN (
                        SELECT TEMP.ID_YARD,TEMP.ID_BLOCK,TEMP.SLOT_,TEMP.ROW_,TEMP.TIER_,ID_POD 
                        FROM CON_LISTCONT CON
                        JOIN (
                                SELECT NVL(YD_YARD,GT_JS_YARD) ID_YARD
                                                ,NVL(YD_BLOCK,GT_JS_BLOCK) ID_BLOCK
                                                ,NVL(YD_SLOT ,GT_JS_SLOT) SLOT_
                                                ,NVL(YD_ROW,GT_JS_ROW) ROW_
                                                ,MAX(NVL(YD_TIER,GT_JS_TIER)) TIER_
                                FROM CON_LISTCONT
                                WHERE (GT_JS_YARD = $id_yard OR YD_YARD = $id_yard ) AND ID_OP_STATUS IN ('SDY','GIY','YYY','YGY','YSY')
                                GROUP BY NVL(YD_YARD,GT_JS_YARD),NVL(YD_BLOCK,GT_JS_BLOCK),NVL(YD_SLOT ,GT_JS_SLOT),NVL(YD_ROW,GT_JS_ROW)
                                UNION 	    	
                                SELECT NVL(YD_YARD,GT_JS_YARD) ID_YARD
                                                ,NVL(YD_BLOCK,GT_JS_BLOCK) ID_BLOCK
                                                ,NVL(CASE WHEN CONT_SIZE >= 40 THEN YD_SLOT + 1 ELSE YD_SLOT END ,CASE WHEN CONT_SIZE >= 40 THEN GT_JS_SLOT + 1 ELSE GT_JS_SLOT END) SLOT_
                                                ,NVL(YD_ROW,GT_JS_ROW) ROW_
                                                ,MAX(NVL(YD_TIER,GT_JS_TIER)) TIER_
                                FROM CON_LISTCONT
                                WHERE (GT_JS_YARD = $id_yard OR YD_YARD = $id_yard ) AND ID_OP_STATUS IN ('SDY','GIY','YYY','YGY','YSY')
                                GROUP BY NVL(YD_YARD,GT_JS_YARD),NVL(YD_BLOCK,GT_JS_BLOCK),NVL(CASE WHEN CONT_SIZE >= 40 THEN YD_SLOT + 1 ELSE YD_SLOT END ,CASE WHEN CONT_SIZE >= 40 THEN GT_JS_SLOT + 1 ELSE GT_JS_SLOT END),NVL(YD_ROW,GT_JS_ROW)
                            ) TEMP ON NVL(CON.YD_YARD ,CON.GT_JS_YARD ) = TEMP.ID_YARD AND NVL(CON.YD_BLOCK ,CON.GT_JS_BLOCK ) = TEMP.ID_BLOCK
                                        AND (NVL(CON.YD_SLOT ,CON.GT_JS_SLOT ) = TEMP.SLOT_ OR CASE WHEN CONT_SIZE >= 40 THEN NVL(CON.YD_SLOT ,CON.GT_JS_SLOT ) + 1 ELSE NVL(CON.YD_SLOT ,CON.GT_JS_SLOT ) END = TEMP.SLOT_)
                                        AND NVL(CON.YD_ROW ,CON.GT_JS_ROW ) = TEMP.ROW_
                                        AND NVL(CON.YD_TIER ,CON.GT_JS_TIER ) = TEMP.TIER_
                        WHERE CON.ID_OP_STATUS IN ('SDY','GIY','YYY','YGY','YSY')
                    ) CON ON Y.ID_YARD = CON.ID_YARD AND Y.ID_BLOCK = CON.ID_BLOCK AND Y.SLOT_ = CON.SLOT_ AND Y.ROW_ = CON.ROW_ AND Y.TIER_ = CON.TIER_
		    LEFT JOIN M_PORT P ON CON.ID_POD = P.PORT_CODE
		    WHERE Y.ID_YARD = $id_yard
		    GROUP BY Y.ID_YARD,Y.ID_BLOCK,Y.SLOT_,Y.ROW_,A.TOTAL,B.TOTAL,C.TOTAL,D.TOTAL
				    ,CON.TIER_
				    ,CON.ID_POD,P.FOREGROUND_COLOR,P.BACKGROUND_COLOR 
		    ORDER BY Y.ID_BLOCK,Y.SLOT_,Y.ROW_";
//	    debux($qry);exit;
	    $result = $this->db->query($qry)->result_array();
	    foreach ($result as $rs){
		$data['TOTAL'][$rs['ID_BLOCK']][$rs['SLOT_']][$rs['ROW_']] = $rs['TOTAL'];
		$data['POD'][$rs['ID_BLOCK']][$rs['SLOT_']][$rs['ROW_']] = $rs['POD'];
		$data['FOREGROUND_COLOR'][$rs['ID_BLOCK']][$rs['SLOT_']][$rs['ROW_']] = $rs['FOREGROUND_COLOR'];
		$data['BACKGROUND_COLOR'][$rs['ID_BLOCK']][$rs['SLOT_']][$rs['ROW_']] = $rs['BACKGROUND_COLOR'];
	    }
	    
	    return $data;
	}
	
	public function extract_yard_equipment_plan($id_yard){
		$xml_str = "";
		$query 		= "SELECT * FROM M_YARD WHERE ID_YARD='$id_yard' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$rs 		= $this->db->query($query);
		$row 		= $rs->row_array();

		$width_str = "<width>".$row['WIDTH']."</width>";
		$height_str = "<height>".$row['HEIGHT']."</height>";

		$query 		= "SELECT V_CELL.*,D.ID_MCH_PLAN,E.MCH_NAME,E.BG_COLOR
						FROM (SELECT * FROM M_YARD_VIEW WHERE ID_YARD='$id_yard' ORDER BY INDEX_CELL) V_CELL
						 LEFT JOIN MCH_PLAN D ON V_CELL.ID_BLOCK=D.ID_BLOCK AND V_CELL.INDEX_CELL=D.INDEX_CELL AND V_CELL.SLOT_=D.SLOT_ AND V_CELL.ROW_=D.ROW_
						 LEFT JOIN M_MACHINE E ON D.ID_MACHINE=E.ID_MACHINE
						 --WHERE E.ID_TERMINAL = '".$this->gtools->terminal()."'
						 GROUP BY V_CELL.ID_YARD,V_CELL.INDEX_CELL,V_CELL.STATUS_STACK,V_CELL.ID_BLOCK,V_CELL.BLOCK_NAME,V_CELL.COLOR,V_CELL.POSISI,V_CELL.ORIENTATION,V_CELL.TIER_,V_CELL.SLOT_,V_CELL.ROW_,V_CELL.BLOCK_LABEL,D.ID_MCH_PLAN,E.MCH_NAME,E.BG_COLOR
						 ORDER BY V_CELL.INDEX_CELL";
		//debux($query);die;
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		$index_stack = array();
		$index_plan = array();
		$index_id_mch = array();
		$index_mch_name = array();
		$index_mch_color = array();
		$index_slot = array();
		$index_row = array();
		$index_tier = array();
		$index_title = array();
		$index_block_id = array();
		$index_orientation = array();
		$index_position = array();
		$index_label = array();
		$index_label_text = array();

		foreach($data as $row){
			if ($row['STATUS_STACK']==1){
				$index_stack[] = $row['INDEX_CELL'];
				$index_slot[] = $row['SLOT_'];
				$index_row[] = $row['ROW_'];
				$index_tier[] = $row['TIER_'];
				$index_title[] = $row['BLOCK_NAME'];
				$index_block_id[] = $row['ID_BLOCK'];
				$index_orientation[] = $row['ORIENTATION'];
				$index_position[] = $row['POSISI'];
				if ($row['ID_MCH_PLAN']!=""){
					$index_plan[] = $row['INDEX_CELL'];
					$index_id_mch[] = $row['ID_MCH_PLAN'];
					$index_mch_name[] = $row['MCH_NAME'];
					$index_mch_color[] = $row['BG_COLOR'];
				}
			}
			if ($row['BLOCK_LABEL']!=""){
				$index_label[] = $row['INDEX_CELL'];
				$index_label_text[] = $row['BLOCK_LABEL'];
			}
		}

		$stack_ = implode(",",$index_stack);
		$plan_ = implode(",",$index_plan);
		$id_mch_ = implode(",",$index_id_mch);
		$mch_name_ = implode(",",$index_mch_name);
		$mch_color_ = implode(",",$index_mch_color);
		$slot_ = implode(",",$index_slot);
		$row_ = implode(",",$index_row);
		$tier_ = implode(",",$index_tier);
		$title = implode(",",$index_title);
		$block_id = implode(",",$index_block_id);
		$orientation = implode(",",$index_orientation);
		$position = implode(",",$index_position);
		$label_ = implode(",",$index_label);
		$label_text_ = implode(",",$index_label_text);

		$stack_str = "<index>".$stack_."</index>";
		$plan_str = "<plan>".$plan_."</plan>";
		$id_mch_str = "<id_machine>".$id_mch_."</id_machine>";
		$mch_name_str = "<mch_name>".$mch_name_."</mch_name>";
		$mch_color_str = "<mch_color>".$mch_color_."</mch_color>";
		$slot_str = "<slot>".$slot_."</slot>";
		$row_str = "<row>".$row_."</row>";
		$tier_str = "<tier>".$tier_."</tier>";
		$title_str = "<title>".$title."</title>";
		$block_id_str = "<block_id>".$block_id."</block_id>";
		$orientation_str = "<orientation>".$orientation."</orientation>";
		$position_str = "<position>".$position."</position>";
		$label_str = "<label>".$label_."</label>";
		$label_text_str = "<label_text>".$label_text_."</label_text>";

		$xml_str = "<yard>".$width_str.$height_str.$stack_str.$plan_str.$id_mch_str.$mch_name_str.$mch_color_str.$slot_str.$row_str.$block_str.$tier_str.$title_str.$block_id_str.$orientation_str.$position_str.$label_str.$label_text_str."</yard>";

		return $xml_str;
	}

	public function update_yard($id_yard, $xml_str, $yard_name, $north_orientation){
		$xml = simplexml_load_string($xml_str);

		$this->db->trans_start();

		$query_yard_cell = "UPDATE M_YARD_CELL SET STATUS_STACK=0 WHERE ID_YARD='$id_yard'";

		$this->db->query($query_yard_cell);

		$stack_cell = $xml->index;
		$index 		= explode(",", $stack_cell);

		$index_sum	 	= count($index);

		foreach ($index as $index_){
			$query_update_stack = "UPDATE M_YARD_CELL SET STATUS_STACK = 1 WHERE INDEX_CELL = '$index_' AND ID_YARD = '$id_yard'";
			$this->db->query($query_update_stack);
		}

		$block 			= $xml->block;
		$block_sum	 	= count($block);

//		$query_blocking_area = "SELECT ID_BLOCK FROM M_YARDBLOCK WHERE ID_YARD='$id_yard'";
//		$rs = $this->db->query($query_blocking_area);
//		$data 		= $rs->result_array();
//		$deleted_id = "";
//		foreach ($data as $row){
//			if ($deleted_id!=""){
//				$deleted_id .= ",";
//			}
//			$deleted_id .= "'".$row['ID_BLOCK']."'";
//		}
//
//		$deleted = "";
//		if($deleted_id != "") {
//			$deleted = " AND ID_BLOCK IN ($deleted_id)";
//		}

//		$query_block_cell = "DELETE FROM M_YARDBLOCK_CELL WHERE ID_YARD='$id_yard' $deleted";
//		$this->db->query($query_block_cell);

//		$query_block_cell = "DELETE FROM M_YARDBLOCK_CELL WHERE ID_YARD='$id_yard' $deleted";
//		$this->db->query($query_block_cell);

		/*
		$query_blocking_area = "DELETE FROM M_YARDBLOCK WHERE ID_YARD='$id_yard'";
		$this->db->query($query_blocking_area);
		*/
//		echo '<pre>';print_r($block);echo '</pre>';
		$msg = '';
		$msg_filter_yard_plan = '';
		$msg_filter_mch_plan = '';
		$msg_filter_container = '';
		foreach ($block as $block_){
			$act  = $block_->act;
			$id_block  = $block_->id_block;
			$block_name  = $block_->name;
			$block_tier = $block_->tier;
			$block_position = $block_->position;
			$block_orientation = $block_->orientation;
			$block_width = $block_->width;
			$block_height = $block_->height;
			$block_color = $block_->color;
			$cell = $block_->cell;
			$block_capacity = $block_tier*$block_width*$block_height;

			if ($block_color==""){
				$block_color = 'BLACK';
			}

			if($block_position=="H"){
				$block_slot = $block_width;
				$block_row = $block_height;
			}else if($block_position=="V"){
				$block_slot = $block_height;
				$block_row = $block_width;
			}

//			if ($row['ID_BLOCK']!=''){
//				$block_id  		= $row['ID_BLOCK'];
//			}else{
//				$query = "SELECT MAX(ID_BLOCK) AS MAX_ID FROM M_YARDBLOCK";
//				$rs 		= $this->db->query($query);
//				$row 		= $rs->row_array();
//				$id = 1;
//				if ($row['MAX_ID']){
//					$id = $row['MAX_ID']+1;
//				}
//				$query_blocking_area = "INSERT INTO M_YARDBLOCK(ID_BLOCK, ID_YARD, BLOCK_NAME, COLOR, POSISI, TIER_, ORIENTATION, CAPACITY, SLOT_, ROW_) VALUES('$id', '$id_yard', '$block_name', '$block_color', '$block_position', '$block_tier', '$block_orientation', '$block_capacity', '$block_slot', '$block_row')";
//				$this->db->query($query_blocking_area);
//
//				$block_id  		= $id;
//			}
			// perubahan Yazir Ciptagiara ILCS
//			echo '<pre>id_yard : '.$id_yard.'</pre>';
//			echo '<pre>id_block : '.$id_block.'</pre>';
//			echo '<pre>$block_name : '.$block_name.'</pre>';
//			echo '<pre>act : '.$act.'</pre>';
			$isValid = TRUE;
			if($id_block != ''){
			    $qry_cek_plan = "SELECT COUNT(*) AS TOTAL FROM YARD_PLAN WHERE ID_YARD = $id_yard AND ID_BLOCK = $id_block";
			    $rs_cek_plan = $this->db->query($qry_cek_plan)->row_array();
			    $qry_cek_cont = "SELECT COUNT(*) AS TOTAL FROM YARD_PLAN WHERE ID_YARD = $id_yard AND ID_BLOCK = $id_block AND FLAG_STATUS > 0";
			    $rs_cek_cont = $this->db->query($qry_cek_cont)->row_array();
			    $qry_cek_mch_plan = "SELECT COUNT(*) AS TOTAL FROM YARD_PLAN WHERE ID_YARD = $id_yard AND ID_BLOCK = $id_block";
			    $rs_cek_mch_plan = $this->db->query($qry_cek_mch_plan)->row_array();
			    if($block_name == ''){
				$qry_bl_nm = "SELECT BLOCK_NAME FROM M_YARDBLOCK WHERE ID_YARD = 38 AND ID_BLOCK = $id_block";
				$rs_bl_nm = $this->db->query($qry_bl_nm)->row_array();
				$block_name = $rs_bl_nm['BLOCK_NAME'];
//				echo '<pre>$block_name2 : '.$block_name.'</pre>';
			    }
			    if($rs_cek_plan['TOTAL'] > 0){
				$msg_filter_yard_plan .= $msg_filter_yard_plan != '' ? ','.$block_name : $block_name; 
				$isValid = FALSE;
			    }
			    
			    if($rs_cek_cont['TOTAL'] > 0){
				$msg_filter_container .= $msg_filter_container != '' ? ','.$block_name : $block_name;
				$isValid = FALSE;
			    }
			    
			    if($rs_cek_mch_plan['TOTAL'] > 0){
				$msg_filter_mch_plan .= $msg_filter_mch_plan != '' ? ','.$block_name : $block_name;
				$isValid = FALSE;
			    }
			}
//			echo '<pre>$msg_filter_yard_plan : '.$msg_filter_yard_plan.'</pre>';
//			echo '<pre>$msg_filter_container : '.$msg_filter_container.'</pre>';
//			echo '<pre>$msg_filter_mch_plan : '.$msg_filter_mch_plan.'</pre>';
//			echo '<pre>valid : '.$isValid.'</pre>';
			if($isValid){
			    if($act == 'S'){
				if($id_block == ''){
				    $query = "SELECT MAX(ID_BLOCK) AS MAX_ID FROM M_YARDBLOCK";
				    $rs 		= $this->db->query($query);
				    $row 		= $rs->row_array();


				    $id_block = $row['MAX_ID'] ? $row['MAX_ID']+1 : 1;
				    $query_blocking_area = "INSERT INTO M_YARDBLOCK(ID_BLOCK, ID_YARD, BLOCK_NAME, COLOR, POSISI, TIER_, ORIENTATION, CAPACITY, SLOT_, ROW_) "
							 . "VALUES('$id_block', '$id_yard', '$block_name', '$block_color', '$block_position', '$block_tier', '$block_orientation', '$block_capacity', '$block_slot', '$block_row')";
				    $this->db->query($query_blocking_area);
    //				echo '<pre>query_blocking_area insert : '.$query_blocking_area.'</pre>';
				    $cell	= explode(",",$block_->cell);
				    $cell_sum	= count($cell);

				}else{
				    $query_blocking_area = "UPDATE M_YARDBLOCK SET COLOR = '$block_color', POSISI = '$block_position', TIER_ = '$block_tier', ORIENTATION='$block_orientation', CAPACITY='$block_capacity', SLOT_='$block_slot', ROW_='$block_row'
							    WHERE ID_BLOCK=$id_block AND ID_YARD=$id_yard";

				    $this->db->query($query_blocking_area);
    //				echo '<pre>query_blocking_area update : '.$query_blocking_area.'</pre>';
				    $qry_get_cell = "SELECT DISTINCT INDEX_CELL FROM M_YARDBLOCK_CELL WHERE ID_YARD='$id_yard' AND ID_BLOCK=$id_block ";
				    $rs_get_cell = $this->db->query($qry_get_cell)->result_array();
				    $cell_prev = '';
				    foreach ($rs_get_cell as $v){
					$cell_prev .= $cell_prev == '' ? $v['INDEX_CELL'] : ','.$v['INDEX_CELL'];
				    }
				    $query_block_cell = "DELETE FROM M_YARDBLOCK_CELL WHERE ID_YARD='$id_yard' AND ID_BLOCK=$id_block ";
				    $this->db->query($query_block_cell);
    //				echo '<pre>query_block_cell : '.$query_block_cell.'</pre>';
				    $qry_del2 = "UPDATE M_YARD_CELL SET STATUS_STACK = 0 WHERE ID_YARD = $id_yard AND INDEX_CELL IN ($cell_prev)";
				    $this->db->query($qry_del2);
				    $qry_del3 = "UPDATE M_YARD_VIEW SET STATUS_STACK = 0 WHERE ID_YARD = $id_yard AND INDEX_CELL IN ($cell_prev)";
				    $this->db->query($qry_del3);
				    $cell	= explode(",",$block_->cell);
				    $cell_sum	= count($cell);
				}
				for ($j = 0; $j < $cell_sum; $j++){
				    //set row and slot
				    if($j == 0){
					    if ($block_position=="H"){
						    if ($block_orientation=="TL"){
							    $slot = 1;
							    $row_ = 1;
						    }else if ($block_orientation=="TR"){
							    $slot = $block_slot;
							    $row_ = 1;
						    }else if ($block_orientation=="BL"){
							    $slot = 1;
							    $row_ = $block_row;
						    }else if ($block_orientation=="BR"){
							    $slot = $block_slot;
							    $row_ = $block_row;
						    }
					    }else if ($block_position=="V"){
						    if ($block_orientation=="TL"){
							    $row_ = 1;
							    $slot = 1;
						    }else if ($block_orientation=="TR"){
							    $row_ = $block_row;
							    $slot = 1;
						    }else if ($block_orientation=="BL"){
							    $row_ = 1;
							    $slot = $block_slot;
						    }else if ($block_orientation=="BR"){
							    $row_ = $block_row;
							    $slot = $block_slot;
						    }
					    }
				    }else{
					    if ($block_position=="H"){
						    if ($block_orientation=="TL"){
							    if($cell[$j-1] == ($cell[$j]-1)){
								    $slot++;
							    }else{
								    $row_++;
								    $slot = 1;
							    }
						    }else if ($block_orientation=="TR"){
							    if($cell[$j-1] == ($cell[$j]-1)){
								    $slot -= 1;
							    }else{
								    $row_++;
								    $slot = $block_slot;
							    }
						    }else if ($block_orientation=="BL"){
							    if($cell[$j-1] == ($cell[$j]-1)){
								    $slot++;
							    }else{
								    $row_ -= 1;
								    $slot = 1;
							    }
						    }else if ($block_orientation=="BR"){
							    if($cell[$j-1] == ($cell[$j]-1)){
								    $slot -= 1;
							    }else{
								    $row_ -= 1;
								    $slot = $block_slot;
							    }
						    }
					    }else if ($block_position=="V"){
						    if ($block_orientation=="TL"){
							    if($cell[$j-1] == ($cell[$j]-1)){
								    $row_++;
							    }else{
								    $slot++;
								    $row_ = 1;
							    }
						    }else if ($block_orientation=="TR"){
							    if($cell[$j-1] == ($cell[$j]-1)){
								    $row_ -= 1;
							    }else{
								    $slot++;
								    $row_ = $block_row;
							    }
						    }else if ($block_orientation=="BL"){
							    if($cell[$j-1] == ($cell[$j]-1)){
								    $row_++;
							    }else{
								    $slot -= 1;
								    $row_ = 1;
							    }
						    }else if ($block_orientation=="BR"){
							    if($cell[$j-1] == ($cell[$j]-1)){
								    $row_ -= 1;
							    }else{
								    $slot -= 1;
								    $row_ = $block_row;
							    }
						    }
					    }
				    }

				    // loop tier
				    for ($k = 1; $k <= $block_tier; $k++){
					    $query_block_cell = "INSERT INTO M_YARDBLOCK_CELL(ID_YARD, INDEX_CELL, ID_BLOCK, ROW_, SLOT_, TIER_) VALUES('$id_yard', $cell[$j], $id_block, $row_, $slot, $k)";
					    $this->db->query($query_block_cell);
    //					echo '<pre>query_block_cell : '.$query_block_cell.'</pre>';
				    }
				}

			    }else{
				// ACT = U
				$qry_count = "SELECT COUNT(*) AS TOTAL FROM M_YARDBLOCK_CELL WHERE ID_YARD = $id_yard AND ID_BLOCK = $id_block AND INDEX_CELL NOT IN ($cell)";
				$total = $this->db->query($qry_count)->row_array();
				$qry_dt_block_prev = "SELECT * FROM M_YARDBLOCK WHERE ID_BLOCK=$id_block AND ID_YARD=$id_yard";
				$block_prev = $this->db->query($qry_dt_block_prev)->row_array();
    //			    echo '<pre>';print_r($block_prev);echo '</pre>';
				if($total['TOTAL'] == 0){
				    $query_blocking_area = "DELETE M_YARDBLOCK WHERE ID_BLOCK=$id_block AND ID_YARD=$id_yard";
				    $this->db->query($query_blocking_area);
				    $query_blocking_area = "DELETE M_YARDBLOCK_CELL WHERE ID_BLOCK=$id_block AND ID_YARD=$id_yard";
				    $this->db->query($query_blocking_area);
				}else{
				    $qry_del1 = "DELETE FROM M_YARDBLOCK_CELL WHERE ID_YARD = $id_yard AND ID_BLOCK = $id_block AND INDEX_CELL IN ($cell)";
				    $this->db->query($qry_del1);
				    if($block_slot == $block_prev['SLOT_'] && $block_row != $block_prev['ROW_']){
					$block_row = $block_prev['ROW_'] - $block_row;
					$block_slot = $block_prev['SLOT_'];
				    }else if($block_slot != $block_prev['SLOT_'] && $block_row == $block_prev['ROW_']){
					$block_slot = $block_prev['SLOT_'] - $block_slot;
					$block_row = $block_prev['ROW_'];
				    }else{
					$block_row = $block_prev['ROW_'];
					$block_slot = $block_prev['SLOT_'];
				    }
    //				echo '<pre>d sini</pre>';
				    $query_blocking_area = "UPDATE M_YARDBLOCK SET SLOT_='$block_slot', ROW_='$block_row'
								WHERE ID_BLOCK=$id_block AND ID_YARD=$id_yard";
				    $this->db->query($query_blocking_area);
				}
				$qry_del2 = "UPDATE M_YARD_CELL SET STATUS_STACK = 0 WHERE ID_YARD = $id_yard AND INDEX_CELL IN ($cell)";
				$this->db->query($qry_del2);
				$qry_del3 = "UPDATE M_YARD_VIEW SET STATUS_STACK = 0 WHERE ID_YARD = $id_yard AND INDEX_CELL IN ($cell)";
				$this->db->query($qry_del3);

			    }
			}else{
//			    echo '<pre>valid false : '.$isValid.'</pre>';
			    if($act == 'S'){
//				echo '<pre>valid false act : '.$act.'</pre>';
				$qry_del2 = "UPDATE M_YARD_CELL SET STATUS_STACK = 0 WHERE ID_YARD = $id_yard AND INDEX_CELL IN ($cell)";
				$this->db->query($qry_del2);
			    }else{
				$qry_rollback = "UPDATE M_YARD_CELL SET STATUS_STACK = 1 WHERE ID_YARD = $id_yard AND INDEX_CELL IN ($cell)";
				$this->db->query($qry_rollback);
			    }
			}
		}
//exit;
		$query_yard_area = "UPDATE M_YARD SET YARD_NAME='$yard_name', NORTH_ORIENTATION = '$north_orientation' WHERE ID_YARD=$id_yard AND ID_TERMINAL=".$this->gtools->terminal();
		$this->db->query($query_yard_area);
		
		if($msg_filter_yard_plan != ''){
		    $msg .= 'Yard plan has been set to block '.$msg_filter_yard_plan.'.<br>'; 
		}

		if($msg_filter_container != ''){
		    $msg .= 'Some container has been plan/placement to block '.$msg_filter_container.'.<br>'; 
		}

		if($msg_filter_mch_plan != ''){
		    $msg .= 'Equipment plan has been set to block '.$msg_filter_mch_plan.'.'; 
		}
		
		if ($this->db->trans_complete()){
			return 'Update yard Success. '.$msg;
		}else{
			return 0;
		}
	}

	public function delete_yard($id_yard){
		$query = "DELETE FROM YARD_PLAN WHERE ID_YARD='$id_yard'";
		$this->db->query($query);
		$query = "DELETE FROM MCH_PLAN WHERE ID_YARD = '$id_yard'";
		$this->db->query($query);
		$query = "DELETE FROM YARD_PLAN_GROUP WHERE ID_YARD='$id_yard'";
		$this->db->query($query);
		$query = "DELETE FROM M_YARDBLOCK_CELL WHERE ID_YARD='$id_yard'";
		$this->db->query($query);
		$query = "DELETE FROM M_YARDBLOCK WHERE ID_YARD='$id_yard'";
		$this->db->query($query);
		$query = "DELETE FROM M_YARD_VIEW WHERE ID_YARD='$id_yard'";
		$this->db->query($query);
		$query = "DELETE FROM M_YARD_CELL WHERE ID_YARD='$id_yard'";
		$this->db->query($query);
		$query = "DELETE FROM M_YARDBLOCK_CELL_VOID_D WHERE ID_BLOCK_VOID IN (SELECT ID_BLOCK_VOID FROM M_YARDBLOCK_CELL_VOID_H WHERE ID_YARD = $id_yard)";
		$this->db->query($query);
		$query = "DELETE FROM M_YARDBLOCK_CELL_VOID_H WHERE ID_YARD='$id_yard'";
		$this->db->query($query);
		$query = "DELETE FROM M_YARD WHERE ID_YARD='$id_yard' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$this->db->query($query);
	}

	public function get_yard_plan_group($id_ves_voyage,$id_category){
		$where = '';
		if($id_ves_voyage != '' || $id_category != ''){
			$where = 'WHERE ';
			if($id_ves_voyage != ''){
			$where .= "A.ID_CATEGORY IN (SELECT ID_CATEGORY FROM M_PLAN_CATEGORY_D WHERE ID_VES_VOYAGE = '$id_ves_voyage')";
			}
			if($id_ves_voyage != '' && $id_category != ''){
			$where .= " AND ";
			}
			if($id_category != ''){
			$where .= " A.ID_CATEGORY = $id_category ";
			}
		}
		$query = "SELECT
					A.ID_YARD,
					A.ID_YARD_PLAN,
					B.YARD_NAME,
					C.BLOCK_NAME,
					A.START_SLOT || '-' || A.END_SLOT AS SLOT_RANGE,
					A.START_ROW || '-' || A.END_ROW AS ROW_RANGE,
					A.CAPACITY,
					D.CATEGORY_NAME,
					D.ID_CATEGORY
				FROM
					YARD_PLAN_GROUP A
				INNER JOIN M_YARD B ON A.ID_YARD = B.ID_YARD
				INNER JOIN M_YARDBLOCK C ON A.ID_BLOCK = C.ID_BLOCK
				INNER JOIN M_PLAN_CATEGORY_H D ON A.ID_CATEGORY = D.ID_CATEGORY
				--INNER JOIN M_PLAN_CATEGORY_D DD ON D.ID_CATEGORY = DD.ID_CATEGORY 
					$where
				ORDER BY
					A.ID_YARD_PLAN";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		//print_r($query);die;

		return $data;
	}

	public function delete_yard_plan_group($id_yard_plan){
		$param = array($id_yard_plan);

		$query = "SELECT ID_CATEGORY
					FROM YARD_PLAN_GROUP
					WHERE ID_YARD_PLAN=?";
		$rs = $this->db->query($query, $param);
		$data = $rs->row_array();
		$id_category = $data['ID_CATEGORY'];

		$query = "DELETE FROM YARD_PLAN WHERE ID_YARD_PLAN=?";
		$this->db->query($query, $param);

		$query = "DELETE FROM YARD_PLAN_GROUP WHERE ID_YARD_PLAN=?";
		$this->db->query($query, $param);

		$param2 = array($id_category);
		$query = "SELECT COUNT(ID_YARD_PLAN) JUMLAH
					FROM YARD_PLAN_GROUP
					WHERE ID_CATEGORY=?";
		$rs = $this->db->query($query, $param2);
		$data = $rs->row_array();
		$count_plan = $data['JUMLAH'];
		if ($count_plan==0) {
			$query = "UPDATE M_PLAN_CATEGORY_H SET STATUS='0'
						WHERE
						ID_CATEGORY=?";
			$this->db->query($query, $param2);
		}

		return 1;
	}

	public function delete_yard_plan_group_mutiple($data){
		$no=1;
		foreach ($data as $value) {
			#get id_category
			$category = $this->db->query("SELECT ID_CATEGORY
						   FROM YARD_PLAN_GROUP
						   WHERE ID_YARD_PLAN = '".$value."'")->row_array();
			$id_category[] =  $category['ID_CATEGORY'];

			#delete yard plan
			$this->db->query("DELETE FROM YARD_PLAN WHERE ID_YARD_PLAN='".$value."'");

			#delete yard plan group
			$this->db->query("DELETE FROM YARD_PLAN_GROUP WHERE ID_YARD_PLAN='".$value."'");

		$no+=1;
		}


		foreach ($category as $valuex) {
			#get jumlah id_yard_plan
			$plan = $this->db->query("SELECT COUNT(ID_YARD_PLAN) JUMLAH
					FROM YARD_PLAN_GROUP
					WHERE ID_CATEGORY='".$valuex."'")->row_array();
			$count_plan = $plan['JUMLAH'];

			if ($count_plan==0) {
				$this->db->query("UPDATE 
				M_PLAN_CATEGORY_H SET STATUS='0'
				WHERE
				ID_CATEGORY='".$valuex."'");
			}

		}

		return 1;

	}

	public function get_data_yard()
	{
		$sql = $this->db->query("SELECT ID_YARD,YARD_NAME FROM M_YARD ORDER BY ID_YARD")->result_array();
		return $sql;
	}

	public function list_pod($id_yard)
	{
		$sql = $this->db->query("SELECT
									DISTINCT(SELECT COMMODITY_NAME FROM M_CONT_COMMODITY WHERE ID_COMMODITY = C.ID_COMMODITY) AS COMMODITY_NAME,
									C.ID_COMMODITY,
									COUNT(C.ID_COMMODITY) AS JML_COM
								FROM
									CON_LISTCONT C
								JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER AND C.POINT = E.POINT
								WHERE
								TRIM(C.ID_OP_STATUS) <> 'DIS'
								AND C.ID_COMMODITY NOT IN ('G') 
								AND C.ID_CLASS_CODE IN ('E', 'TE','TC','S1','S2') 
								AND NVL(E.BILLING_PAID,'0') = 
								(CASE WHEN C.ID_CLASS_CODE = 'E' THEN E.BILLING_PAID
								ELSE '0' 
								END) GROUP BY C.ID_COMMODITY")->result_array();
		//debux($query);
		return $sql;
	}

	public function list_commodity()
	{
		$query = "SELECT * FROM M_CONT_COMMODITY ORDER BY ID_COMMODITY ASC";
		return $this->db->query($query)->result();
	}

	public function list_refer($id_yard,$id_comm,$id_ves_voyage)
	{
		$query = "SELECT
						A.COMMODITY_NAME,
						C.ID_POD,
						COUNT(A.COMMODITY_NAME)
					FROM
						CON_LISTCONT C
					JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER AND C.POINT = E.POINT
					JOIN ITOS_OP.M_CONT_COMMODITY A ON A.ID_COMMODITY = C.ID_COMMODITY
					WHERE
					TRIM(C.ID_OP_STATUS) <> 'DIS'
					AND C.ID_VES_VOYAGE  = '$id_ves_voyage'
					AND C.ID_COMMODITY  = ('$id_comm') 
					AND C.ID_CLASS_CODE IN ('E', 'TE','TC','S1','S2') 
					/*AND NVL(E.BILLING_PAID,'0') = 
					(CASE WHEN C.ID_CLASS_CODE = 'E' THEN E.BILLING_PAID
					ELSE '0' 
					END)*/
					--AND C.YD_YARD IN ($id_yard) 
					GROUP BY C.ID_POD,A.COMMODITY_NAME";
		// debux($query);
		$sql = $this->db->query($query)->result_array();
		return $sql;
	}

	public function get_data1($id_yard)
	{
		$sql = "SELECT
					C.ID_COMMODITY,C.ID_POD,C.YD_BLOCK_NAME,
					TO_CHAR(C.CONFIRM_DATE, 'DD-MM-YYYY hh24:mi') AS CONFIRM_DATE_,
					ROW_NUMBER() OVER(ORDER BY C.NO_CONTAINER ASC) AS NOMOR
				FROM
					CON_LISTCONT C
				JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER AND C.POINT = E.POINT
				WHERE
				TRIM(C.ID_OP_STATUS) <> 'DIS' 
				AND C.ID_COMMODITY NOT IN ('G')
				AND C.ID_CLASS_CODE IN ('E', 'TE','TC','S1','S2') 
				AND NVL(E.BILLING_PAID,'0') = 
				(CASE WHEN C.ID_CLASS_CODE = 'E' THEN E.BILLING_PAID
				ELSE '0' 
				END)  ORDER BY C.NO_CONTAINER ASC";
		return $this->db->query($sql)->result();
	}

	public function get_data_outbound_yard_summary($id_ves_voyage){

		$id_terminal = $this->gtools->terminal();

		$query = "SELECT
					A.NO_CONTAINER, A.POINT, A.ID_YARD, A.ID_BLOCK, A.BLOCK_,
					B.ID_ISO_CODE, B.CONT_SIZE, B.CONT_TYPE, B.CONT_STATUS, B.CONT_HEIGHT, B.ID_POD, DECODE(BAY_,'','N','Y') IS_PLAN
					FROM
						JOB_PLACEMENT A
						INNER JOIN CON_LISTCONT B
						ON A.NO_CONTAINER=B.NO_CONTAINER AND A.POINT=B.POINT
						LEFT JOIN CON_OUTBOUND_SEQUENCE C
						ON A.NO_CONTAINER = C.NO_CONTAINER AND A.POINT = C.POINT
					WHERE
						A.ID_VES_VOYAGE  = '$id_ves_voyage'
						AND B.ID_TERMINAL= '$id_terminal' 
						AND B.ID_CLASS_CODE IN ('E', 'TE')";
		// debux($query);die;
		$rs = $this->db->query($query);
		$data = $rs->result_array();

		return $data;
	}

	public function get_list_plan_category(){
		$query = "SELECT pc.*, c.hex_color
		  FROM M_PLAN_CATEGORY_H pc
		  LEFT JOIN M_COLOR c
				  ON (pc.id_color = c.id_color) 
		  WHERE pc.ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		return $data;
	}

	public function get_list_equipment(){
		$query = "SELECT m.id_machine,
			   m.mch_name,
			   m.mch_type,
			   m.mch_sub_type,
			   m.bg_color
		  FROM m_machine m
		  WHERE m.mch_type = 'YARD' AND m.ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		return $data;
	}

	//@deprecated
	public function extract_yard_monitoring($id_yard){
		$xml_str = "";
		$query 		= "SELECT * FROM M_YARD WHERE ID_YARD='$id_yard' AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs 		= $this->db->query($query);
		$row 		= $rs->row_array();

		$width_str = "<width>".$row['WIDTH']."</width>";
		$height_str = "<height>".$row['HEIGHT']."</height>";

		$query = "SELECT V_CELL.*,
			SUM (DECODE (D.NO_CONTAINER, '', 0, 1)) JML_TAKEN,
			MAX(E.ID_POD) ID_POD,
			MAX(D.ID_VES_VOYAGE) ID_VES_VOYAGE,
			MAX(E.ID_OPERATOR) ID_OPERATOR,
			MAX(E.ID_CLASS_CODE) ID_CLASS_CODE--,
			--LISTAGG(E.ID_CLASS_CODE,',') within group( order by E.ID_CLASS_CODE ) ID_CLASS_CODE_COLL
					  FROM	(  SELECT *
								   FROM M_YARD_VIEW
								  WHERE ID_YARD = '$id_yard'-- AND ID_BLOCK = 12
							   ORDER BY INDEX_CELL) V_CELL
						   LEFT JOIN
							  JOB_PLACEMENT D
						   ON	 V_CELL.ID_BLOCK = D.ID_BLOCK
							  AND V_CELL.SLOT_ = D.SLOT_
							  AND V_CELL.ROW_ = D.ROW_
						   LEFT JOIN
							  CON_LISTCONT E
						   ON	 D.NO_CONTAINER = E.NO_CONTAINER
							  AND D.POINT = E.POINT
				  WHERE D.ID_TERMINAL='".$this->gtools->terminal()."' AND E.ID_TERMINAL='".$this->gtools->terminal()."'
				  GROUP BY V_CELL.ID_YARD,
						   V_CELL.INDEX_CELL,
						   V_CELL.STATUS_STACK,
						   V_CELL.ID_BLOCK,
						   V_CELL.BLOCK_NAME,
						   V_CELL.COLOR,
						   V_CELL.POSISI,
						   V_CELL.ORIENTATION,
						   V_CELL.TIER_,
						   V_CELL.SLOT_,
						   V_CELL.ROW_,
						   V_CELL.BLOCK_LABEL
				  ORDER BY V_CELL.INDEX_CELL";

		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		// var_dump($data);

		$index_stack = array();
		$index_placement = array();
		$index_slot = array();
		$index_row = array();
		$block_array = array();
		$index_label = array();
		$index_label_text = array();

		foreach($data as $row){
			if ($row['STATUS_STACK']==1){
				$index_stack[] = $row['INDEX_CELL'];
				$placement_temp = ($row['JML_PLACEMENT']>0) ? $row['JML_PLACEMENT'] : $row['JML_TAKEN'];
				if ($row['JML_PLACEMENT'] > 0 && ($row['TOTAL_20_PLACEMENT']<$row['JML_PLACEMENT'])){
					if ($row['ORIENTATION']=='TL' || $row['ORIENTATION']=='BL'){
						$index_placement[] = $placement_temp .'|40|'. $row['HEX_COLOR'];
					}else if ($row['ORIENTATION']=='TR' || $row['ORIENTATION']=='BR'){
						$cur_size = sizeof($index_placement);
						$index_placement[$cur_size-1] = str_replace('20','40',$index_placement[$cur_size-1]);
						$index_placement[] = $placement_temp .'|20|'. $row['HEX_COLOR'];
					}
				} else {
					$index_placement[] = $placement_temp .'|20|'. $row['HEX_COLOR'];
				}
				$index_slot[] = $row['SLOT_'];
				$index_row[] = $row['ROW_'];
				$block_array[] = $row['BLOCK_NAME'];
			}
			if ($row['BLOCK_LABEL']!=""){
				$index_label[] = $row['INDEX_CELL'];
				$index_label_text[] = $row['BLOCK_LABEL'];
			}
		}
		$stack_ = implode(",",$index_stack);
		$placement_ = implode(",",$index_placement);
		$slot_ = implode(",",$index_slot);
		$row_ = implode(",",$index_row);
		$block_array_ = implode(",",$block_array);
		$label_ = implode(",",$index_label);
		$label_text_ = implode(",",$index_label_text);

		$stack_str = "<index>".$stack_."</index>";
		$placement_str = "<placement>".$placement_."</placement>";
		$slot_str = "<slot>".$slot_."</slot>";
		$row_str = "<row>".$row_."</row>";
		$block_array_str = "<block_name>".$block_array_."</block_name>";
		$label_str = "<label>".$label_."</label>";
		$label_text_str = "<label_text>".$label_text_."</label_text>";

		$xml_str = "<yard>".$width_str.$height_str.$stack_str.$placement_str.$slot_str.$row_str.$block_array_str.$label_str.$label_text_str."</yard>";

		return $xml_str;
	}

	//@deprecated
	public function get_equipment_position($id_yard){
		$query = "SELECT C.*,
	   D.mch_name,
	   D.mch_sub_type,
	   D.bg_color,
	   CASE
		  WHEN C.last_job IS NULL
		  THEN
			 (SELECT MIN (INDEX_CELL)
				FROM m_yardblock_cell
			   WHERE	 id_yard = c.id_yard
					 AND id_block = c.id_block
					 AND slot_ = c.start_slot)
		  ELSE
			 (SELECT MIN (INDEX_CELL)
				FROM m_yardblock_cell
			   WHERE	 id_yard = c.last_yard
					 AND id_block = c.last_id_block
					 AND slot_ = c.last_slot)
	   END
		  INDEX_CELL
  FROM	(  SELECT A.id_mch_plan,
					A.id_machine,
					A.id_yard,
					A.id_block,
					A.start_slot,
					A.end_slot,
					A.start_row,
					A.end_row,
					B.id_yard last_yard,
					B.id_block last_id_block,
					B.block_ last_block,
					MIN (B.slot_) last_slot,
					MAX (B.last_job) LAST_JOB_DATE,
					TO_CHAR (MAX (B.last_job), 'DD-MON-YYYY HH24:MI') LAST_JOB,
					NVL (TO_CHAR (MAX (B.last_job), 'DD-MON'), 'NO-ACT')
					   LAST_JOB_MINI
			   FROM	MCH_PLAN_GROUP A
					LEFT JOIN
					   (  SELECT jp.id_machine,
								 jp.id_yard,
								 jp.id_block,
								 jp.block_,
								 jp.slot_,
								 MAX (jp.placement_date) last_job
							FROM job_placement jp
						   WHERE jp.ID_YARD = '$id_yard'
						GROUP BY jp.id_machine,
								 jp.id_yard,
								 jp.id_block,
								 jp.block_,
								 jp.slot_) B
					ON (	A.id_machine = B.id_machine
						AND A.id_block = B.id_block
						AND B.slot_ BETWEEN A.start_slot AND A.end_slot)
		   GROUP BY A.id_mch_plan,
					A.id_machine,
					A.id_yard,
					A.id_block,
					A.start_slot,
					A.end_slot,
					A.start_row,
					A.end_row,
					B.id_yard,
					B.id_block,
					B.block_) C
	   INNER JOIN
		  M_MACHINE D
	   ON C.ID_MACHINE = D.ID_MACHINE";
		$rs = $this->db->query($query);
		return $rs->result_array();
	}

	public function save_change_PA($data){
		$status_flag = '0';
		$message = '';
		$param = array(
			array('name'=>':no_container', 'value'=>$data['no_container'], 'length'=>15),
			array('name'=>':point', 'value'=>$data['point'], 'length'=>10),
			array('name'=>':id_block', 'value'=>$data['id_block'], 'length'=>10),
			array('name'=>':block_', 'value'=>$data['block_name'], 'length'=>10),
			array('name'=>':slot_', 'value'=>$data['slot'], 'length'=>10),
			array('name'=>':row_', 'value'=>$data['row'], 'length'=>10),
			array('name'=>':tier_', 'value'=>$data['tier'], 'length'=>10),
			array('name'=>':status_flag', 'value'=>&$status_flag, 'length'=>1),
			array('name' => ':message', 'value' => &$message, 'length' => 1000)
		);
		// print_r($param);

		//debux($param);die;
		$sql = "BEGIN PROC_SAVE_CHANGE_PA(:no_container, :point, :id_block, :block_, :slot_, :row_, :tier_, :status_flag, :message); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		return array($status_flag, $message);
	}

	public function save_change_equipment($data){
		$status_flag = '0';

		$containers = json_decode($data['list_container']);

		foreach ($containers as $container){
			$param = array(
				$data['id_mch'], $container->no_container, $container->point, $this->gtools->terminal()
			);
			// print_r($param);

			$query = "UPDATE JOB_YARD_MANAGER SET ID_MACHINE=? WHERE NO_CONTAINER=? AND POINT=? AND ID_TERMINAL=?";
			if($this->db->query($query,$param)){
				$status_flag = '1';
			}else{
				$status_flag = '0';
			}
		}
		return $status_flag;
	}

	public function get_heapzone_list(){
		$query 		= "SELECT ID_HEAPZONE, HEAPZONE_NAME, OWNER, CAPACITY
						FROM M_HEAPZONE
						WHERE ID_TERMINAL='".$this->gtools->terminal()."'
						ORDER BY ID_HEAPZONE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_heapzone_detail($id_heapzone){
		$param = array($id_heapzone,$this->gtools->terminal());
		$query 		= "SELECT *
						FROM M_HEAPZONE
						WHERE ID_HEAPZONE=? AND ID_TERMINAL=?";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row_array();

		return $data;
	}

	public function delete_heapzone($id_heapzone){
		$param = array($id_heapzone, $this->gtools->terminal());
		$query = "DELETE FROM M_HEAPZONE
					WHERE ID_HEAPZONE=? AND ID_TERMINAL=?";
		$retval = $this->db->query($query, $param);

		return $retval;
	}

	public function change_block_tier_count($id_yard, $id_block, $tier){
		$this->db->trans_start();

		$param = array($id_yard, $id_block);
		$sql = "SELECT * FROM M_YARDBLOCK WHERE ID_YARD=? AND ID_BLOCK=?";
		$rs = $this->db->query($sql, $param);
		$data = $rs->row_array();
		$tier_old = $data['TIER_'];
		$slot = $data['SLOT_'];
		$row = $data['ROW_'];
		$capacity = $tier * $slot * $row;
		$flag = '';
		if ($tier_old<$tier){
			$flag = 'upsize';
		}else{
			$flag = 'downsize';
		}
		$delta = abs($tier_old-$tier);

		$param = array($id_yard, $id_block);
		$sql = "SELECT DISTINCT INDEX_CELL, SLOT_, ROW_ FROM M_YARDBLOCK_CELL
		WHERE ID_YARD=? AND ID_BLOCK=? ORDER BY INDEX_CELL";
		$rs = $this->db->query($sql, $param);
		$data = $rs->result_array();

		foreach ($data as $row){
			for ($i=0;$i<$delta;$i++){
				if ($flag=='upsize'){
					$param = array($id_yard, $id_block, $row['INDEX_CELL'], $row['SLOT_'], $row['ROW_'], ($tier-$i));
					$sql = "INSERT INTO M_YARDBLOCK_CELL
							VALUES(?,?,?,?,?,?) ";
					$this->db->query($sql, $param);
				}else{
					$param = array($id_yard, $id_block, $row['INDEX_CELL'], $row['SLOT_'], $row['ROW_'], ($tier_old-$i));
					$sql = "DELETE FROM M_YARDBLOCK_CELL
							WHERE ID_YARD=?
							AND ID_BLOCK=?
							AND INDEX_CELL=?
							AND SLOT_=?
							AND ROW_=?
							AND TIER_=?";
					$this->db->query($sql, $param);
				}
			}
		}

		$param = array($id_yard, $id_block);
		$sql = "SELECT DISTINCT INDEX_CELL, ID_CATEGORY, SLOT_, ROW_, ID_YARD_PLAN FROM YARD_PLAN
		WHERE ID_YARD=? AND ID_BLOCK=? ORDER BY INDEX_CELL";
		$rs = $this->db->query($sql, $param);
		$data = $rs->result_array();

		foreach ($data as $row){
			for ($i=0;$i<$delta;$i++){
				if ($flag=='upsize'){
					$param = array($id_yard, $id_block, $row['INDEX_CELL'], $row['ID_CATEGORY'], $row['SLOT_'], $row['ROW_'], ($tier-$i), $row['ID_YARD_PLAN']);
					$sql = "INSERT INTO YARD_PLAN
							VALUES(?,?,?,?,?,?,?,0,?) ";
					$this->db->query($sql, $param);
				}else{
					$param = array($id_yard, $id_block, $row['INDEX_CELL'], $row['SLOT_'], $row['ROW_'], ($tier_old-$i));
					$sql = "DELETE FROM YARD_PLAN
							WHERE ID_YARD=?
							AND ID_BLOCK=?
							AND INDEX_CELL=?
							AND SLOT_=?
							AND ROW_=?
							AND TIER_=?";
					$this->db->query($sql, $param);
				}
			}
		}

		$param = array($capacity, $tier, $id_yard, $id_block);
		$sql = "UPDATE M_YARDBLOCK SET CAPACITY = ?, TIER_=? WHERE ID_YARD=? AND ID_BLOCK=?";
		$this->db->query($sql, $param);

		$param = array($id_yard,$this->gtools->terminal());
		$sql = "UPDATE M_YARD SET STATUS=1 WHERE ID_YARD=? AND ID_TERMINAL=?";
		$this->db->query($sql, $param);

		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
	}

	public function change_block_slot_row_orientation($id_yard, $id_block, $block_orientation){
		$check = 0;

		$param = array($id_yard, $id_block);
		$sql = "SELECT COUNT(ID_YARD_PLAN) JUMLAH FROM YARD_PLAN WHERE ID_YARD=? AND ID_BLOCK=?";
		$rs = $this->db->query($sql, $param);
		$data = $rs->row_array();
		$check = $data['JUMLAH'];
		if ($check>0){
			return 2;
		}

		$param = array($id_yard, $id_block);
		$sql = "SELECT COUNT(ID_MCH_PLAN) JUMLAH FROM MCH_PLAN WHERE ID_YARD=? AND ID_BLOCK=?";
		$rs = $this->db->query($sql, $param);
		$data = $rs->row_array();
		$check = $data['JUMLAH'];
		if ($check>0){
			return 2;
		}

		$this->db->trans_start();

		$param = array($id_yard, $id_block);
		$sql = "SELECT POSISI, SLOT_, ROW_, TIER_ FROM M_YARDBLOCK WHERE ID_YARD=? AND ID_BLOCK=?";
		$rs = $this->db->query($sql, $param);
		$data = $rs->row_array();
		$block_position = $data['POSISI'];
		$block_slot = $data['SLOT_'];
		$block_row = $data['ROW_'];
		$block_tier = $data['TIER_'];

		$param = array($id_yard, $id_block);
		$sql = "SELECT DISTINCT INDEX_CELL FROM M_YARDBLOCK_CELL WHERE ID_YARD=? AND ID_BLOCK=? ORDER BY INDEX_CELL";
		$rs = $this->db->query($sql, $param);
		$data = $rs->result_array();
		$cell = array();
		foreach ($data as $row_cell){
			array_push($cell, $row_cell['INDEX_CELL']);
		}
		$cell_sum	= count($cell);

		$param = array($id_yard, $id_block);
		$sql = "DELETE FROM M_YARDBLOCK_CELL WHERE ID_YARD=? AND ID_BLOCK=?";
		$this->db->query($sql, $param);

		for ($j = 0; $j < $cell_sum; $j++){
			//set row and slot
			if($j == 0){
				if ($block_position=="H"){
					if ($block_orientation=="TL"){
						$slot = 1;
						$row_ = 1;
					}else if ($block_orientation=="TR"){
						$slot = $block_slot;
						$row_ = 1;
					}else if ($block_orientation=="BL"){
						$slot = 1;
						$row_ = $block_row;
					}else if ($block_orientation=="BR"){
						$slot = $block_slot;
						$row_ = $block_row;
					}
				}else if ($block_position=="V"){
					if ($block_orientation=="TL"){
						$row_ = 1;
						$slot = 1;
					}else if ($block_orientation=="TR"){
						$row_ = $block_row;
						$slot = 1;
					}else if ($block_orientation=="BL"){
						$row_ = 1;
						$slot = $block_slot;
					}else if ($block_orientation=="BR"){
						$row_ = $block_row;
						$slot = $block_slot;
					}
				}
			}else{
				if ($block_position=="H"){
					if ($block_orientation=="TL"){
						if($cell[$j-1] == ($cell[$j]-1)){
							$slot++;
						}else{
							$row_++;
							$slot = 1;
						}
					}else if ($block_orientation=="TR"){
						if($cell[$j-1] == ($cell[$j]-1)){
							$slot -= 1;
						}else{
							$row_++;
							$slot = $block_slot;
						}
					}else if ($block_orientation=="BL"){
						if($cell[$j-1] == ($cell[$j]-1)){
							$slot++;
						}else{
							$row_ -= 1;
							$slot = 1;
						}
					}else if ($block_orientation=="BR"){
						if($cell[$j-1] == ($cell[$j]-1)){
							$slot -= 1;
						}else{
							$row_ -= 1;
							$slot = $block_slot;
						}
					}
				}else if ($block_position=="V"){
					if ($block_orientation=="TL"){
						if($cell[$j-1] == ($cell[$j]-1)){
							$row_++;
						}else{
							$slot++;
							$row_ = 1;
						}
					}else if ($block_orientation=="TR"){
						if($cell[$j-1] == ($cell[$j]-1)){
							$row_ -= 1;
						}else{
							$slot++;
							$row_ = $block_row;
						}
					}else if ($block_orientation=="BL"){
						if($cell[$j-1] == ($cell[$j]-1)){
							$row_++;
						}else{
							$slot -= 1;
							$row_ = 1;
						}
					}else if ($block_orientation=="BR"){
						if($cell[$j-1] == ($cell[$j]-1)){
							$row_ -= 1;
						}else{
							$slot -= 1;
							$row_ = $block_row;
						}
					}
				}
			}

			// loop tier
			for ($k = 1; $k <= $block_tier; $k++){
				$query_block_cell = "INSERT INTO M_YARDBLOCK_CELL(ID_YARD, INDEX_CELL, ID_BLOCK, ROW_, SLOT_, TIER_) VALUES('$id_yard', $cell[$j], $id_block, $row_, $slot, $k)";
				$this->db->query($query_block_cell);
			}
		}

		$param = array($block_orientation, $id_yard, $id_block);
		$sql = "UPDATE M_YARDBLOCK SET ORIENTATION=? WHERE ID_YARD=? AND ID_BLOCK=?";
		$this->db->query($sql, $param);

		$param = array($id_yard,$this->gtools->terminal());
		$sql = "UPDATE M_YARD SET STATUS=1 WHERE ID_YARD=? AND ID_TERMINAL=?";
		$this->db->query($sql, $param);

		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
	}

	public function get_yard_lini2_list($filter){
		$query 		= "SELECT ID_YARD, YARD_NAME
						FROM M_YARD_LINI2
						WHERE ACTIVE='Y' AND (
						UPPER(ID_YARD) LIKE '%".strtoupper(trim($filter))."%' OR
						UPPER(YARD_NAME) LIKE '%".strtoupper(trim($filter))."%'
						)
						ORDER BY ID_YARD ";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function save_change_equipment_plan($data){
		$new_id_machine = $data['id_machine'];
		$id_mch_plan = $data['id_mch_plan'];

		$param = array($id_mch_plan);
		$query = "SELECT ID_BLOCK, START_SLOT, END_SLOT, START_ROW, END_ROW, ID_MACHINE
					FROM MCH_PLAN_GROUP WHERE ID_MCH_PLAN=?";
		$rs = $this->db->query($query, $param);
		$data = $rs->row_array();
		// print_r($data);

		$id_block = $data['ID_BLOCK'];
		$start_slot = $data['START_SLOT'];
		$end_slot = $data['END_SLOT'];
		$start_row = $data['START_ROW'];
		$end_row = $data['END_ROW'];
		$old_id_machine = $data['ID_MACHINE'];

		$param = array($old_id_machine,$this->gtools->terminal());
		$query = "SELECT NO_CONTAINER, POINT, EVENT
					FROM JOB_YARD_MANAGER WHERE ID_MACHINE=? AND ID_TERMINAL=? AND STATUS_FLAG='P' ";
		$rs = $this->db->query($query, $param);
		$data = $rs->result_array();
		// echo sizeof($data)."<br/>";

		$this->db->trans_start();

		foreach ($data as $job){
			$param = array($job['NO_CONTAINER'], $job['POINT'], $this->gtools->terminal());
			if ($job['EVENT']=='P'){
				$query = "SELECT GT_JS_BLOCK BLOCK_, GT_JS_SLOT SLOT_, GT_JS_ROW ROW_
						FROM CON_LISTCONT
						WHERE NO_CONTAINER=? AND POINT=? AND ID_TERMINAL=?";
			}else{
				$query = "SELECT YD_BLOCK BLOCK_, YD_SLOT SLOT_, YD_ROW ROW_
						FROM CON_LISTCONT
						WHERE NO_CONTAINER=? AND POINT=? AND ID_TERMINAL=?";
			}
			$rs = $this->db->query($query, $param);
			$row_data = $rs->row_array();
			$cont_block = $row_data['BLOCK_'];
			$cont_slot = $row_data['SLOT_'];
			$cont_row = $row_data['ROW_'];
			if ($cont_block==$id_block && ($cont_slot>=$start_slot && $cont_slot<=$end_slot) && ($cont_row>=$start_row && $cont_row<=$end_row)){
				// print_r($job);
				// print_r($row_data);
				$param = array($new_id_machine, $job['NO_CONTAINER'], $job['POINT'], $old_id_machine, $this->gtools->terminal());
				$query = "UPDATE JOB_YARD_MANAGER SET ID_MACHINE=?
							WHERE NO_CONTAINER=? AND POINT=? AND ID_MACHINE=? AND ID_TERMINAL=? AND STATUS_FLAG='P'";
				$this->db->query($query, $param);
			}
		}

		$param = array($new_id_machine, $id_mch_plan);

		$query = "UPDATE MCH_PLAN SET ID_MACHINE=?
					WHERE ID_MCH_PLAN=?";
		$this->db->query($query, $param);

		$query = "UPDATE MCH_PLAN_GROUP SET ID_MACHINE=?
					WHERE ID_MCH_PLAN=?";
		$this->db->query($query, $param);

		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function save_void($data) {
		$row_from = $data['row_from'];
		$row_to = $data['row_to'];
		$tier_from = $data['tier_from'];
		$tier_to = $data['tier_to'];
		$remarks = $data['remarks'];
		$id_yard = $data['yard'];
		$id_block = $data['block'];
		$slot = $data['slot'];
//		echo '<pre>';print_r($data);echo '</pre>';exit;
		//get sequence of id_block_void
		$CREATE_USER = $this->session->userdata('id_user');
		$CREATE_DATE = date('d-M-y h:i:s A');
		
		$this->db->trans_start();
		
		$get_id_block_void = $this->db->query("SELECT M_YARDBLOCK_CELL_VOID_S.nextval AS ID FROM DUAL")->result_array();
		$id = $get_id_block_void[0]['ID'];
		
		//insert header void
		$qry_ins = "INSERT INTO M_YARDBLOCK_CELL_VOID_H(ID_BLOCK_VOID,ID_YARD,ID_BLOCK,SLOT_,FROM_ROW,TO_ROW,FROM_TIER,TO_TIER,REMARKS,CREATED_USER,CREATED_DATE)
			VALUES(".$id.",'".$id_yard."','".$id_block."','".$slot."','".$row_from."','".$row_to."','".$tier_from."','".$tier_to."','".$remarks."','".$CREATE_USER."','".$CREATE_DATE."')";
		$this->db->query($qry_ins);
		
		for($r = $row_from; $r <= $row_to; $r++){
		for($t = $tier_from; $t <= $tier_to; $t++){
			$qry_upd = "INSERT INTO M_YARDBLOCK_CELL_VOID_D(ID,ID_BLOCK_VOID,ROW_,TIER_)
				VALUES (M_YARDBLOCK_CELL_VOID_D_S.nextval,$id,$r,$t)";
			$this->db->query($qry_upd);
		}
		}
		
		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function delete_void($ids){
	    $arr_id = explode(',', $ids);
	    
	    $this->db->trans_start();
	    foreach ($arr_id as $id){
		$this->db->query("DELETE FROM M_YARDBLOCK_CELL_VOID_H WHERE ID_BLOCK_VOID = $id");
		$this->db->query("DELETE FROM M_YARDBLOCK_CELL_VOID_D WHERE ID_BLOCK_VOID = $id");
	    }
	    
	    if ($this->db->trans_complete()){
		    return array(1, "Delete Void Success");
	    }else{
		    return array(0, "Delete Void Failed");
	    }
	}
	
	public function get_void_list($id_yard, $id_block,$slot){
		$qry = "SELECT * FROM M_YARDBLOCK_CELL_VOID_H 
			WHERE ID_YARD = '$id_yard' AND ID_BLOCK ='$id_block' AND SLOT_ = '$slot'";
		return $this->db->query($qry)->result_array();
		
	}
	
	public function get_all_detail_void_list($id_yard, $id_block,$slot){
		$qry = "SELECT * FROM M_YARDBLOCK_CELL_VOID_H 
			WHERE ID_YARD = '$id_yard' AND ID_BLOCK ='$id_block' AND SLOT_ = '$slot'";
		return $this->db->query($qry)->result_array();
		
	}
	
	public function get_yard_plan_category_per_slot($id_yard,$id_block,$slot){
		$qry = "SELECT DISTINCT A.ID_CATEGORY,B.CATEGORY_NAME,A.ID_YARD,A.ID_BLOCK,A.START_SLOT,A.END_SLOT,A.START_ROW,A.END_ROW,D.BACKGROUND_COLOR
			FROM YARD_PLAN_GROUP A
			LEFT JOIN M_PLAN_CATEGORY_H B
			  ON A.ID_CATEGORY = B.ID_CATEGORY
			LEFT JOIN (
				SELECT A.ID_CATEGORY,A.ID_DETAIL,ID_PORT_DISCHARGE
					FROM M_PLAN_CATEGORY_D A
					INNER JOIN (
						SELECT ID_CATEGORY,MAX(ID_DETAIL) ID_DETAIL
						FROM M_PLAN_CATEGORY_D 
						GROUP BY ID_CATEGORY
					) B ON A.ID_CATEGORY = B.ID_CATEGORY AND A.ID_DETAIL = B.ID_DETAIL
			) C ON B.ID_CATEGORY = C.ID_CATEGORY
			LEFT JOIN M_PORT D
			ON C.ID_PORT_DISCHARGE = D.PORT_CODE
			WHERE A.ID_YARD = '$id_yard' AND A.ID_BLOCK = '$id_block' AND A.START_SLOT <= $slot AND A.END_SLOT >= $slot
			ORDER BY A.START_ROW";
//		echo $qry;exit;
		$res = $this->db->query($qry)->result_array();
		return $res;
	}
	
	public function yard_placement_submit($no_container, $point, $id_op_status, $event, $user_id, $yard_position, $id_machine, $driver_id) {
		$status_flag = 'F';
		$message = '';

		$param = array(
		array('name' => ':no_container', 'value' => $no_container, 'length' => 15),
		array('name' => ':point', 'value' => $point, 'length' => 10),
		array('name' => ':id_op_status', 'value' => $id_op_status, 'length' => 3),
		array('name' => ':event', 'value' => $event, 'length' => 1),
		array('name' => ':user_id', 'value' => $user_id, 'length' => 10),
		array('name' => ':driver_id', 'value' => $driver_id, 'length' => 10),
		array('name' => ':id_block', 'value' => $yard_position['BLOCK'], 'length' => 10),
		array('name' => ':block_', 'value' => $yard_position['BLOCK_NAME'], 'length' => 15),
		array('name' => ':slot_', 'value' => $yard_position['SLOT'], 'length' => 10),
		array('name' => ':row_', 'value' => $yard_position['ROW'], 'length' => 10),
		array('name' => ':tier_', 'value' => $yard_position['TIER'], 'length' => 10),
		array('name' => ':id_machine', 'value' => $id_machine, 'length' => 10),
		array('name' => ':v_terminal', 'value' => $this->gtools->terminal(), 'length' => 10),
		array('name' => ':status_flag', 'value' => &$status_flag, 'length' => 1),
		array('name' => ':message', 'value' => &$message, 'length' => 1000)
		);
	//			 echo '<pre>';print_r($param);echo '</pre>';exit;

		$sql = "BEGIN PROC_JOB_YARD_COMPLETE(:no_container, :point, :id_op_status, :event, :user_id, :driver_id, :id_block, :block_, :slot_, :row_, :tier_, :id_machine, :v_terminal, :status_flag, :message); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
	
		return array($status_flag, $message);
	}

	public function yard_relocation_submit($no_container, $point, $user_id, $yard_position, $machine){
		$status_flag = 'F';
		$message = '';

		$param = array(
			array('name'=>':no_container', 'value'=>$no_container, 'length'=>15),
			array('name'=>':point', 'value'=>$point, 'length'=>10),
			array('name'=>':user_id', 'value'=>$user_id, 'length'=>10),
			array('name'=>':id_block', 'value'=>$yard_position['BLOCK'], 'length'=>10),
			array('name'=>':block_', 'value'=>$yard_position['BLOCK_NAME'], 'length'=>10),
			array('name'=>':slot_', 'value'=>$yard_position['SLOT'], 'length'=>10),
			array('name'=>':row_', 'value'=>$yard_position['ROW'], 'length'=>10),
			array('name'=>':tier_', 'value'=>$yard_position['TIER'], 'length'=>10),
			array('name'=>':status_flag', 'value'=>&$status_flag, 'length'=>1),
			array('name'=>':message', 'value'=>&$message, 'length'=>1000),
			array('name'=>':machine', 'value'=>$machine, 'length'=>10),
			array('name'=>':v_terminal', 'value'=>$this->gtools->terminal(), 'length'=>10)
		);
//		print_r($param);die;

		$sql = "BEGIN PROC_RELOCATION_COMPLETE(:no_container, :point, :user_id, :id_block, :block_, :slot_, :row_, :tier_, :status_flag, :message, :machine, :v_terminal); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		return array($status_flag, $message);
	}
	
	public function get_yard_plan_group_by_id($id_yard_plan){
		$query = "SELECT ID_YARD_PLAN,START_SLOT,END_SLOT,START_ROW,END_ROW
			FROM YARD_PLAN_GROUP
			WHERE ID_YARD_PLAN = $id_yard_plan";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data[0];
	}
	
	public function edit_yard_plan_group($data){
		$id_user = $data['id_user'];
		$id_yard_plan = $data['ID_YARD_PLAN'];
		$start_slot = $data['START_SLOT'];
		$end_slot = $data['END_SLOT'];
		$start_row = $data['START_ROW'];
		$end_row = $data['END_ROW'];
		$MODIFY_DATE = date('d-M-y h:i:s A');

		if (!isset($id_yard_plan) || $id_yard_plan == '') {
		return array(
			'IsSuccess' => false,
			'Message' => 'ID Yard Plan tidak boleh kosong.'
		);
		}

		if (!isset($start_slot) || $start_slot == '' || $start_slot < 1 ||
		!isset($end_slot) || $end_slot == '' || $end_slot < 1 ||	
		!isset($start_row) || $start_row == '' || $start_row < 1 ||
		!isset($end_row) || $end_row == '' || $end_row < 1) {
		return array(
			'IsSuccess' => false,
			'Message' => 'Pengisian data tidak melalui form.'
		);
		}

		$query = "SELECT * FROM ITOS_OP.YARD_PLAN_GROUP WHERE ID_YARD_PLAN = '$id_yard_plan'";
		$result = $this->db->query($query);
		$row = $result->row_array();
		$tid_ori = $row['TID'];
		$no_pol_ori = $row['NO_POL'];
	//		echo 'query : '.$query;
	//		echo $no_pol.' : '.$no_pol_ori;exit;
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
				WHERE ID_TRUCK=$id_truck";
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

	public function getAllYard(){
		$query = "SELECT ID_YARD, YARD_NAME FROM M_YARD";

		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_plan_category_by_id($id){
	    $qry = "SELECT * FROM M_PLAN_CATEGORY_H WHERE ID_CATEGORY = $id";
	    return $this->db->query($qry)->row_array();
	}
	
	public function get_yard_plan_group_category_list($id_ves_voyage){
		
		$query = "SELECT H.ID_CATEGORY,H.CATEGORY_NAME 
			FROM M_PLAN_CATEGORY_H H
			LEFT JOIN M_PLAN_CATEGORY_D D
				ON H.ID_CATEGORY = D.ID_CATEGORY
			WHERE D.ID_VES_VOYAGE = '$id_ves_voyage'";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

//		print_r($query);die;

		return $data;
	}
	
	public function stowage_summary_group($paging=false, $sort=false, $filters=false, $id_ves_voyage){
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
			$qSort .= " ORDER BY S.".$sortProperty." ".$sortDirection;
		}
		$qWhere = "";
		
		$qs = '';
		$qAND = '';
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
					case 'filter_pod' : $field = "ID_POD"; break;
				}
				$field = 'S.'. $field;
				switch($filterType){
					case 'string'   : 
						if($i>0){
							$qAND = "AND ";
						}
						$qs .= " $qAND ".$field." LIKE '%".strtoupper($value)."%'"; 
					Break;
					case 'list' :
						if($i>0){
							$qAND = "AND ";
						}
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							$qs .= " $qAND ".$field." IN (".strtoupper($value).")";
						}else{
							$qs .= " $qAND ".$field." = '".strtoupper($value)."'";
						}
					Break;
					case 'boolean' : 
						if($i>0){
							$qAND = "AND ";
						}
						$qs .= " $qAND ".$field." = ".($value); 
						Break;
					case 'numeric' :
						if($i>0){
							$qAND = "AND ";
						}
						switch ($compare) {
							case 'eq' : $qs .= " $qAND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " $qAND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " $qAND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						if($i>0){
							$qAND = "AND ";
						}
						switch ($compare) {
							case 'eq' : $qs .= " $qAND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " $qAND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " $qAND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$qWhere .= $qs;
		}
		if(!empty($qWhere)){
			$qWhere = "WHERE $qWhere ";
		}
		$mainquery = "
				SELECT
					*
				FROM
					(
						SELECT
							COUNT (SUB.ID_POD) TOTAL,
							SUB.*
						FROM
							(
								SELECT
									D .COMMODITY_NAME,
									B.ID_POD,
									B.CONT_SIZE,
									B.CONT_TYPE,
									B.ID_COMMODITY,
									B.CONT_STATUS,
									CASE
								WHEN B.OVER_FRONT IS NULL THEN
									0
								ELSE
									B.OVER_FRONT
								END AS FRONT,
									CASE
								WHEN B.OVER_HEIGHT IS NULL THEN
									0
								ELSE
									B.OVER_HEIGHT
								END AS HEIGHT,
									CASE
								WHEN B.OVER_LEFT IS NULL THEN
									0
								ELSE
									B.OVER_LEFT
								END AS LEFT,
									CASE
								WHEN B.OVER_REAR IS NULL THEN
									0
								ELSE
									B.OVER_REAR
								END AS REAR,
									CASE
								WHEN B.OVER_RIGHT IS NULL THEN
									0
								ELSE
									B.OVER_RIGHT
								END AS RIGHT,
								 CASE
								WHEN B.OVER_WIDTH IS NULL THEN
									0
								ELSE
									B.OVER_WIDTH
								END AS WIDTH,
								 CASE
								WHEN B.CONT_STATUS = 'FCL' THEN
									'F'
								WHEN B.CONT_STATUS = 'MTY' THEN
									'M'
								END AS FM,
								 CASE
								WHEN B.ID_CLASS_CODE = 'TE' THEN
									'Transhipment'
								ELSE
									'E'
								END AS ID_CLASS_CODE,
								 CASE
								WHEN B.CONT_HEIGHT = 'OOG' THEN
									'Y'
								ELSE
									'N'
								END AS OOG,
								 B.IMDG
								FROM
									CON_LISTCONT B
								INNER JOIN VES_VOYAGE C ON C.ID_VES_VOYAGE = B.ID_VES_VOYAGE
								LEFT OUTER JOIN M_CONT_COMMODITY D ON B.ID_COMMODITY = D .ID_COMMODITY
								WHERE
									B.ID_VES_VOYAGE = '$id_ves_voyage'
								AND B.ID_CLASS_CODE IN ('E', 'TE')
								AND B.ID_TERMINAL = '".$this->gtools->terminal()."'
								AND B.ID_OP_STATUS <> 'DIS'
							) SUB
					GROUP BY
						SUB.COMMODITY_NAME,
						SUB.ID_POD,
						SUB.CONT_SIZE,
						SUB.CONT_STATUS,
						SUB.FRONT,
						SUB.HEIGHT,
						SUB. LEFT,
						SUB.REAR,
						SUB. RIGHT,
						SUB.WIDTH,
						SUB.CONT_TYPE,
						SUB.FM,
						SUB.ID_CLASS_CODE,
						SUB.ID_COMMODITY,
						SUB.IMDG,
						SUB.OOG
					) S
					$qWhere 
					$qSort
				";
		$query = "SELECT B.*
						  FROM (SELECT V.*, ROWNUM REC_NUM
								  FROM (
										$mainquery
									) V
											) B
										$qPaging";
		$rs = $this->db->query($query);
		$alat_list = $rs->result_array();
		$query_count = "SELECT COUNT (V.ID_POD) AS TOTAL
								  FROM (
										$mainquery
									) V";
		$rstotal = $this->db->query($query_count);
		$rowtotal = $rstotal->row_array();
		$total = $rowtotal['TOTAL'];
		$data = array (
			'total'=>$total,
			'data'=>$alat_list
		);
		return $data;
	}
}
?>