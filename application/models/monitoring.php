<?php
class Monitoring extends CI_Model {
	public function __construct(){
		$this->load->database();
	}
	
	public function get_slot_configuration($id_yard, $block){
		$query = "select row_ max_row, tier_ max_tier 
			from m_yardblock 
			where id_yard = $id_yard and block_name = '$block'";
		$rs 		= $this->db->query($query);
		$data 		= $rs->row();
		return $data;
	}
	
	 public function get_bay_configuration($id_vessel, $bay){
		$param = array($id_vessel, $bay);
		$query = "select jml_row max_row, jml_tier_under, jml_tier_on, (jml_tier_under+jml_tier_on+1) max_tier
			from m_vessel_profile_bay
			where id_vessel = ? and bay = ?";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row();
		return $data;
	}
	
	/**
	 * Get Single Stack View
	 */
	public function get_container_in_yard_data($id_yard, $block, $id_slot, $size){
		$query = "SELECT yb.block_name,
			   c.yd_slot,
			   c.yd_row,
			   c.yd_tier,
			   c.no_container,
			   c.point,
			   case c.id_class_code
				   when 'E' then 'EXPORT'
				   when 'I' then 'IMPORT'
			   end id_class_code,
			   c.id_ves_voyage,
			   vv.id_vessel,
			   c.id_iso_code,
			   c.cont_size,
			   c.cont_type,
			   c.cont_status,
			   c.cont_height,
			   c.id_pod,
			   c.id_operator,
			   (c.weight / 1000) weight,
			   c.id_commodity,
			   c.hazard,
			   c.id_spec_hand,
			   case when c.imdg is null then '0'
			   else c.imdg end IMDG
		  FROM con_listcont c
			LEFT JOIN m_yardblock yb
			  ON (c.yd_block = yb.id_block)
			LEFT JOIN ves_voyage vv
			  ON (c.id_ves_voyage = vv.id_ves_voyage)
		  WHERE c.yd_yard = $id_yard
			and yb.block_name = '$block' 
			and c.ID_TERMINAL='".$this->gtools->terminal()."'
			and vv.ID_TERMINAL='".$this->gtools->terminal()."'
			and (
				(c.yd_slot = $id_slot and '$size' = '20') or
				(c.yd_slot in ($id_slot-1, $id_slot) and '$size' = '40')
			) 
			and c.id_op_status in ('YYY', 'YGY', 'YSY')
		  ORDER BY c.yd_slot, c.yd_row, c.yd_tier";
		  // echo $query;
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	/**
	 * Fetch Container Stacking
	 */
	public function extract_yard_monitoring($id_yard){
		$xml_str = "";
		$query 		= "SELECT * FROM M_YARD WHERE ID_YARD='$id_yard' AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs 		= $this->db->query($query);
		$row 		= $rs->row_array();
		
		$width_str = "<width>".$row['WIDTH']."</width>";
		$height_str = "<height>".$row['HEIGHT']."</height>";
		
		$query = "SELECT V_PLACE.*,
					 V_PLACE.JML_TAKEN TOTAL_20_PLACEMENT,
					 SUM (DECODE (E.FLAG_STATUS, 2, 1, 0)) JML_PLACEMENT,
					 E.ID_CATEGORY,
					 C.HEX_COLOR
				  FROM (SELECT V_CELL.*, 
							YB.SLOT_ MAX_SLOT, YB.ROW_ MAX_ROW,
							SUM (DECODE (D.NO_CONTAINER, '', 0, 1)) JML_TAKEN,
							MAX(E.ID_POD) ID_POD,
							MAX(D.ID_VES_VOYAGE) ID_VES_VOYAGE,
							MAX(E.ID_OPERATOR) ID_OPERATOR,
							MAX(E.ID_CLASS_CODE) ID_CLASS_CODE
						FROM( SELECT *
							  FROM M_YARD_VIEW
							  WHERE ID_YARD = '$id_yard'
						      ORDER BY INDEX_CELL) V_CELL
						LEFT JOIN M_YARDBLOCK YB ON YB.ID_BLOCK = V_CELL.ID_BLOCK
						LEFT JOIN JOB_PLACEMENT D ON V_CELL.ID_BLOCK = D.ID_BLOCK AND V_CELL.SLOT_ = D.SLOT_ AND V_CELL.ROW_ = D.ROW_
						LEFT JOIN CON_LISTCONT E ON	 D.NO_CONTAINER = E.NO_CONTAINER AND D.POINT = E.POINT AND E.ID_TERMINAL = D.ID_TERMINAL
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
								   V_CELL.BLOCK_LABEL,
								   YB.SLOT_, 
								   YB.ROW_
						  ORDER BY V_CELL.INDEX_CELL) V_PLACE
				 --LEFT JOIN (SELECT * FROM YARD_PLAN WHERE ID_CATEGORY <> 999) E
				 LEFT JOIN YARD_PLAN E
					ON	 V_PLACE.ID_BLOCK = E.ID_BLOCK
					   AND V_PLACE.INDEX_CELL = E.INDEX_CELL
					   AND V_PLACE.SLOT_ = E.SLOT_
					   AND V_PLACE.ROW_ = E.ROW_
				 LEFT JOIN M_PLAN_CATEGORY_H PC
					ON E.ID_CATEGORY = PC.ID_CATEGORY AND PC.ID_TERMINAL = '".$this->gtools->terminal()."'
				 LEFT JOIN M_COLOR C
					ON PC.ID_COLOR = C.ID_COLOR
				WHERE E.ID_CATEGORY IS NULL OR E.ID_CATEGORY <> 999
				GROUP BY V_PLACE.ID_YARD,
						 V_PLACE.INDEX_CELL,
						 V_PLACE.STATUS_STACK,
						 V_PLACE.ID_BLOCK,
						 V_PLACE.BLOCK_NAME,
						 V_PLACE.COLOR,
						 V_PLACE.POSISI,
						 V_PLACE.ORIENTATION,
						 V_PLACE.TIER_,
						 V_PLACE.SLOT_,
						 V_PLACE.ROW_,
						 V_PLACE.BLOCK_LABEL,
						 V_PLACE.MAX_SLOT,
						 V_PLACE.MAX_ROW,
						 V_PLACE.JML_TAKEN,
						 V_PLACE.ID_POD,
						 V_PLACE.ID_VES_VOYAGE,
						 V_PLACE.ID_OPERATOR,
						 V_PLACE.ID_CLASS_CODE,
						 E.ID_CATEGORY,
						 C.HEX_COLOR
				ORDER BY V_PLACE.INDEX_CELL";

		//print_r($query);die;
		
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		// var_dump($data);
		
		$index_stack = array();
		$index_placement = array();
		$index_slot = array();
		$index_row = array();
		$max_slot = array();
		$max_row = array();
		$position = array();
		$orientation = array();
		$block = array();
		$block_array = array();
		$index_label = array();
		$index_label_text = array();
		
		foreach($data as $row){
			if ($row['STATUS_STACK']==1){
				$index_stack[] = $row['INDEX_CELL'];
				$placement_temp = ($row['JML_PLACEMENT']>0) ? $row['JML_PLACEMENT'] : $row['JML_TAKEN'];
				
				//@todo Check 40 container in ITOS Panjang
				if ($row['JML_PLACEMENT'] > 0 && ($row['TOTAL_20_PLACEMENT']<$row['JML_PLACEMENT'])){
					if ($row['ORIENTATION']=='TL' || $row['ORIENTATION']=='BL'){
						$temp_placement = $placement_temp .'|40|'. $row['HEX_COLOR'];
					
					}else if ($row['ORIENTATION']=='TR' || $row['ORIENTATION']=='BR'){
						$cur_size = count($index_placement);
						
						$index_placement[$cur_size-1] = str_replace('20','40',$index_placement[$cur_size-1]); // change before 40
						
						$temp_placement = $placement_temp .'|20|'. $row['HEX_COLOR'];
					}
				
				} else {
					$temp_placement = $placement_temp .'|20|'. $row['HEX_COLOR'];
				}
				$temp_placement = $temp_placement . '|' .$row['ID_POD']. '|' .$row['ID_VES_VOYAGE']. '|' 
					.$row['ID_OPERATOR']. '|' .$row['ID_CLASS_CODE'];
				$index_placement[] = $temp_placement;
				$index_slot[] = $row['SLOT_'];
				$index_row[] = $row['ROW_'];
				$max_slot[] = $row['MAX_SLOT'];
				$max_row[] = $row['MAX_ROW'];
				$position[] = $row['POSISI'];
				$orientation[] = $row['ORIENTATION'];
				$block[] = $row['ID_BLOCK'];
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
		$max_slot_ = implode(",",$max_slot);
		$max_row_ = implode(",",$max_row);
		$position_ = implode(",",$position);
		$orientation_ = implode(",",$orientation);
		$block_ = implode(",",$block);
		$block_array_ = implode(",",$block_array);
		$label_ = implode(",",$index_label);
		$label_text_ = implode(",",$index_label_text);
		
		$stack_str = "<index>".$stack_."</index>";
		$placement_str = "<placement>".$placement_."</placement>";
		$slot_str = "<slot>".$slot_."</slot>";
		$row_str = "<row>".$row_."</row>";
		$max_slot_str = "<max_slot>".$max_slot_."</max_slot>";
		$max_row_str = "<max_row>".$max_row_."</max_row>";
		$position_str = "<position>".$position_."</position>";
		$orientation_str = "<orientation>".$orientation_."</orientation>";
		$block_str = "<block>".$block_."</block>";
		$block_array_str = "<block_name>".$block_array_."</block_name>";
		$label_str = "<label>".$label_."</label>";
		$label_text_str = "<label_text>".$label_text_."</label_text>";
		
		$xml_str = "<yard>"
			.$width_str
			.$height_str
			.$stack_str
			.$placement_str
			.$slot_str
			.$row_str
			.$max_slot_str
			.$max_row_str
			.$position_str
			.$orientation_str
			.$block_str
			.$block_array_str
			.$label_str
			.$label_text_str
		."</yard>";
		
		return $xml_str;
	}

/**
 * Get Equipment Monitoring
 */
	public function get_equipment_monitoring($id_yard){
		// YARD EQUIPMENT
		$query 		= "SELECT m.id_machine,
			   m.mch_name,
			   m.mch_type,
			   m.mch_sub_type,
			   m.bg_color,
			   v.*,
			   NVL (TO_CHAR (v.last_placement, 'DD-MON'), 'NO-ACT') last_job
			FROM m_machine m
				LEFT JOIN (SELECT j.id_yard,
					   j.id_block,
					   j.block_ blockname,
					   j.slot_,
					   j.row_,
					   j.tier,
					   j.id_user,
					   lp.id_machine,
					   lp.last_placement
				  FROM	job_placement j
					   INNER JOIN
						  (  SELECT id_yard, id_machine, MAX (placement_date) last_placement
							   FROM job_placement
							   WHERE ID_TERMINAL='".$this->gtools->terminal()."'
						   GROUP BY id_yard, id_machine) lp
					   ON (	j.id_yard = lp.id_yard
						   AND j.id_machine = lp.id_machine
						   AND j.placement_date = lp.last_placement)
				  WHERE j.id_yard = $id_yard AND j.ID_TERMINAL='".$this->gtools->terminal()."') v
				ON (m.id_machine = v.id_machine)
			WHERE m.mch_type = 'YARD' and m.id_machine > 0 AND m.ID_TERMINAL='".$this->gtools->terminal()."'
			ORDER BY m.mch_name";
		$rs 		= $this->db->query($query);
		$yard 		= $rs->result_array();
		
		// QUAY EQUIPMENT
		$query 		= "SELECT m.id_machine,
			   m.mch_name,
			   m.mch_type,
			   m.mch_sub_type,
			   m.bg_color,
			   v.*,
			   NVL(TO_CHAR(v.date_entry, 'DD-MON'), 'NO-ACT') last_job
			FROM m_machine m
			LEFT JOIN (SELECT j.*, C.VS_BAY
				  FROM	job_confirm j
				  INNER JOIN
					(SELECT id_machine, MAX (date_entry) last_job
					 FROM job_confirm
					 WHERE ID_TERMINAL='".$this->gtools->terminal()."'
					 GROUP BY id_machine) lj
				  ON (j.id_machine = lj.id_machine AND j.date_entry = lj.last_job)
				  INNER JOIN con_listcont c ON (J.NO_CONTAINER = C.NO_CONTAINER AND J.POINT=C.POINT)
				  WHERE j.ID_TERMINAL='".$this->gtools->terminal()."') v
			ON (m.id_machine = v.id_machine)
			WHERE m.mch_type = 'QUAY' and m.id_machine > 0 and m.ID_TERMINAL='".$this->gtools->terminal()."'
			ORDER BY m.mch_name";
		$rs 		= $this->db->query($query);
		$quay 		= $rs->result_array();
		
		$data = array_merge($yard, $quay);
		
		return $data;
	}
	
	/**
	 * Get Filter list
	 *	Data source: Container stacking
	 */
	public function get_constacking_pod($id_yard){
		$query 		= "SELECT DISTINCT c.id_pod POD, c.id_pod || ' - ' || p.port_name port_name
		  FROM con_listcont c LEFT JOIN M_PORT p ON (c.id_pod = p.port_code)
		 WHERE c.id_op_status IN ('YSY', 'YYY', 'YGY') and c.yd_yard = $id_yard";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_constacking_vessel($id_yard){
		$query 		= "SELECT DISTINCT c.id_ves_voyage, 
				v.vessel_name || ' (' || v.voy_in || '/' || v.voy_out || ')' vessel_detail
			  FROM con_listcont c LEFT JOIN VES_VOYAGE v ON (c.id_ves_voyage = v.id_ves_voyage)
			 WHERE c.ID_TERMINAL='".$this->gtools->terminal()."' AND v.ID_TERMINAL='".$this->gtools->terminal()."' AND c.id_op_status IN ('YSY', 'YYY', 'YGY') and c.yd_yard = $id_yard AND v.ACTIVE = 'Y' AND (v.ATD IS NULL OR v.ATD >= SYSDATE)
			ORDER BY c.id_ves_voyage ASC";
//		echo '<pre>'.$query.'</pre>';exit;
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_constacking_carrier($id_yard){
		$query 		= "SELECT DISTINCT c.id_operator, c.id_operator || ' - ' || o.operator_name operator_name
			  FROM con_listcont c left join M_OPERATOR o on (c.id_operator = o.id_operator)
			 WHERE c.ID_TERMINAL='".$this->gtools->terminal()."' AND c.id_op_status IN ('YSY', 'YYY', 'YGY') and c.yd_yard = $id_yard
			ORDER BY c.id_operator";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	/**
	 * Get Filter list
	 *	Data source: Yard Plan
	 */
	public function get_yard_plan_pod($id_yard){
		$query 		= "SELECT DISTINCT substr(c.id_port_discharge, 1, 5) POD, p.port_name
			FROM M_PLAN_CATEGORY_D c LEFT JOIN M_PORT p ON (substr(c.id_port_discharge, 1, 5) = p.port_code)
		  WHERE id_category IN (SELECT DISTINCT id_category
								   FROM YARD_PLAN y
								  WHERE y.id_yard = $id_yard)
		  ORDER BY substr(c.id_port_discharge, 1, 5)";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_yard_plan_vessel($id_yard){
		$query 		= "select distinct c.id_ves_voyage, v.vessel_name || ' (' || v.voy_in || '/' || v.voy_out || ')' vessel_detail
			from M_PLAN_CATEGORY_D c left join VES_VOYAGE v ON (c.id_ves_voyage = v.id_ves_voyage)
			 WHERE c.id_category IN (SELECT DISTINCT y.id_category
									   FROM YARD_PLAN y
									  WHERE y.id_yard = $id_yard)
			order by id_ves_voyage";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_yard_plan_carrier($id_yard){
		$query 		= "select distinct c.id_operator, o.operator_name
			 from M_PLAN_CATEGORY_D c left join M_OPERATOR o on (c.id_operator = o.id_operator)
			 WHERE c.id_category IN (SELECT DISTINCT y.id_category
									   FROM YARD_PLAN y
									  WHERE y.id_yard = $id_yard)
			order by id_operator";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	/**
	 * Get 
	 */
	public function get_list_block_slot($id_yard){
		$query 		= "SELECT id_block, block_name, slot_ slot
				FROM m_yardblock
			   WHERE id_yard = $id_yard
			ORDER BY block_name";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
	
	public function get_list_vessel_bay($id_vessel){
		$param = array($id_vessel);
		$query 		= "SELECT id_bay, bay
				FROM m_vessel_profile_bay
			   WHERE id_vessel = ? AND occupy = 'Y'
			ORDER BY bay";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_block_name($id_block,$id_yard){
		$query = "SELECT BLOCK_NAME FROM ITOS_OP.M_YARDBLOCK WHERE ID_YARD = '".$id_yard."' AND ID_BLOCK = '".$id_block."'";
		$row   = $this->db->query($query)->row();
		return $row->BLOCK_NAME;
	}
	
	public function get_data_report_gate($gateIn, $gateOut, $kegiatan, $esy, $gate, $vesvoy='', $pbm, $paging=''){
		//debux($esy);
		$qwhere = '';

		if($esy == 'N'){
			$qwhere .= "AND Z.ITT_FLAG = 'N'";
		}else if($esy == 'Y'){
			$qwhere .= "AND Z.ITT_FLAG = 'Y'";
		}

		$qwhere3 ='';
		if($gate == 'Gate In'){
			$qwhere3 .= "AND A.IN_OUT = 'In'";
		}else if($gate == 'Gate Out'){
			$qwhere3 .= "AND A.IN_OUT = 'Out'";
		}

		$qwherepbm ='';
		if($pbm != '' and $pbm!='ALL PBM'){
			$qwherepbm .= "AND pbm.COMPANY_NAME = '$pbm'";
		}
		
		$qPaging = '';
		if ($paging != ''){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE PG.REC_NUM >= $start AND PG.REC_NUM <= $end";
		}

		//debux($gate);die;
		
		$qwhere4 = '';
		if($kegiatan == 'Receiving'){
			$qwhere4 .= "WHERE C.ID_CLASS_CODE = 'I'";
		}else if($kegiatan == 'Delivery'){
			$qwhere4 .= "WHERE C.ID_CLASS_CODE = 'E'";
		}else{
			$qwhere4 .= "WHERE C.ID_CLASS_CODE IN ('E','I')";
		}

		if($vesvoy == 'null'){		    
		    $qwhere_vesvoy = "--AND A.ID_VES_VOYAGE = '$vesvoy'";
		} else if ($vesvoy != ''){
			$qwhere_vesvoy = "AND A.ID_VES_VOYAGE = '$vesvoy'";
		}
		
		$mainquery = "SELECT
						COMPANY_NAME,
						NO_CONTAINER,
						CONT_SIZE, CONT_TYPE, CONT_STATUS, IMDG, WEIGHT, ID_CLASS_CODE, VESSEL_NAME, VOY_IN, VOY_OUT, ID_ISO_CODE, PORT_NAME AS POD,
						TID,
						NO_POL AS NO_TRUCK,
						--IN_OUT, 
						TERMINAL_NAME, SHIPPING,
						ITT_FLAG AS ESY,
						USER_GATE_IN,
						USER_GATE_OUT,
						TO_CHAR (Z.GATE_IN, 'DD-MM-YYYY HH24.MI') GATE_IN,
                        TO_CHAR (Z.GATE_OUT,'DD-MM-YYYY HH24.MI') GATE_OUT,
						CEIL((Z.GATE_OUT - Z.GATE_IN)* 24 * 60) TRT
					FROM (SELECT 
								pbm.COMPANY_NAME,
								A.NO_CONTAINER,
								C.CONT_SIZE,
								C.CONT_TYPE,
								C.CONT_STATUS,
								C.IMDG,
								C.WEIGHT,
								C.ID_CLASS_CODE,
								V.VESSEL_NAME,
								V.VOY_IN,
								V.VOY_OUT,
								C.ID_ISO_CODE,
								P.PORT_NAME,
								A.ID_TRUCK,
								T.TID,
								T.NO_POL,
								--A.IN_OUT, 
								(SELECT DATE_TRINOUT FROM JOB_TRUCK_INOUT WHERE ID_VES_VOYAGE = A.ID_VES_VOYAGE AND NO_CONTAINER = A.NO_CONTAINER AND IN_OUT = 'In' AND ROWNUM <= 1) AS GATE_IN,
								(SELECT U.FULL_NAME FROM JOB_TRUCK_INOUT DT JOIN M_USERS U ON U.ID_USER=DT.ID_USER WHERE DT.ID_VES_VOYAGE = A.ID_VES_VOYAGE AND DT.NO_CONTAINER = A.NO_CONTAINER AND DT.IN_OUT = 'In' AND ROWNUM <= 1) AS USER_GATE_IN,
								(SELECT DATE_TRINOUT FROM JOB_TRUCK_INOUT WHERE ID_VES_VOYAGE = A.ID_VES_VOYAGE AND NO_CONTAINER = A.NO_CONTAINER AND IN_OUT = 'Out' AND ROWNUM <= 1) AS GATE_OUT,
								(SELECT U.FULL_NAME FROM JOB_TRUCK_INOUT DT JOIN M_USERS U ON U.ID_USER=DT.ID_USER WHERE DT.ID_VES_VOYAGE = A.ID_VES_VOYAGE AND DT.NO_CONTAINER = A.NO_CONTAINER AND DT.IN_OUT = 'Out' AND ROWNUM <= 1) AS USER_GATE_OUT,
								M.TERMINAL_NAME,
								'I' AS SHIPPING,
								C.ITT_FLAG
							FROM JOB_TRUCK_INOUT A
							JOIN M_TRUCK T ON T.ID_TRUCK=A.ID_TRUCK
							JOIN CON_LISTCONT C ON C.NO_CONTAINER=A.NO_CONTAINER AND C.POINT=A.POINT
							JOIN VES_VOYAGE V ON V.ID_VES_VOYAGE=A.ID_VES_VOYAGE
							JOIN M_PORT P ON P.PORT_CODE=C.ID_POD
							JOIN M_TERMINAL M ON A.ID_TERMINAL=M.ID_TERMINAL
							JOIN M_STEVEDORING_COMPANIES pbm ON V.STV_COMPANY=pbm.ID_COMPANY
							$qwhere4
								AND A.ID_TERMINAL = '".$this->gtools->terminal()."'
								$qwhere_vesvoy
								$qwhere3
								$qwherepbm
							GROUP BY
								pbm.COMPANY_NAME,
								A.NO_CONTAINER,
								C.CONT_SIZE,
								C.CONT_TYPE,
								C.CONT_STATUS,
								C.IMDG,
								C.WEIGHT,
								C.ID_CLASS_CODE,
								V.VESSEL_NAME,
								V.VOY_IN,
								V.VOY_OUT,
								C.ID_ISO_CODE,
								P.PORT_NAME,
								A.ID_TRUCK,
								T.TID,
								T.NO_POL,
								M.TERMINAL_NAME,
								C.ITT_FLAG,
								A.ID_VES_VOYAGE
							ORDER BY GATE_IN DESC) Z
					WHERE 1=1 $qwhere AND (GATE_IN > TO_DATE ('$gateIn','DD-MM-YYYY HH24.MI'))";
					if($gate == 'Gate Out'){
				            $mainquery .= " AND (GATE_OUT < TO_DATE ('$gateOut','DD-MM-YYYY HH24.MI'))";
				     }
					

		$query_count = "SELECT COUNT(R.COMPANY_NAME) TOTAL
					  FROM (  
					  $mainquery
					  ) R
					  ";

		$rsqc = $this->db->query($query_count);
		$rowrsqc = $rsqc->row_array();
		$total = $rowrsqc['TOTAL'];
/*		$query = "SELECT
                        NO_CONTAINER,
                        ID_CLASS_CODE,
                        NO_TRUCK,
                        ID_TRUCK TID,
                        VESSEL,
                        VOY_IN,
                        VOY_OUT,
                        TO_CHAR (
                                GATE_IN,
                                'DD-MM-YYYY HH24.MI'
                        ) GATE_IN,
                        USER_GATE_IN,
                        TO_CHAR (
                                GATE_OUT,
                                'DD-MM-YYYY HH24.MI'
                        ) GATE_OUT,
                        USER_GATE_OUT,
                        CEIL((GATE_OUT - GATE_IN)* 24 * 60) TRT,
                        -- GET_DURATION (GATE_IN, GATE_OUT) TRT,
                        TRUNC (((GATE_OUT - GATE_IN) * 1440)) TRTMIN,
                        ID_ISO_CODE,
                        POD,
                        CONT_SIZE,
                        CONT_TYPE,
                        CONT_STATUS,
                        IMDG,
                        WEIGHT,
                        O_I AS SHIPPING,
                        ITT_FLAG AS ESY,
                        OP_STATUS_DESC,
                        TERMINAL_NAME,
                        ID_TERMINAL,
                        ID_OP_STATUS
                    FROM
                    (
                            SELECT
                                    A .NO_CONTAINER,
                                    A .ID_CLASS_CODE,
                                    AB .ID_TRUCK, 
                                    (
                                            SELECT
                                                    D .NO_POL
                                            FROM
                                                    JOB_TRUCK_INOUT B
                                            LEFT JOIN M_TRUCK D ON D .ID_TRUCK = B.ID_TRUCK
                                            WHERE
                                                    B.NO_CONTAINER = A .NO_CONTAINER 
                                            AND B. POINT = A . POINT
                                            AND B.EI = A .ID_CLASS_CODE
                                            AND B.IN_OUT = 'In'
                                            AND ROWNUM < 2
                                            AND B.ID_TERMINAL='".$this->gtools->terminal()."'
                                    ) NO_TRUCK,
                                    (
                                            SELECT
                                                    E .VESSEL_NAME
                                            FROM
                                                    VES_VOYAGE E
                                            WHERE
                                                    E .ID_VES_VOYAGE = A .ID_VES_VOYAGE
                                                    AND E.ID_TERMINAL='".$this->gtools->terminal()."'
                                    ) VESSEL,
                                    (
                                            SELECT
                                                    E .VOY_IN
                                            FROM
                                                    VES_VOYAGE E
                                            WHERE
                                                    E .ID_VES_VOYAGE = A .ID_VES_VOYAGE
                                                    AND E.ID_TERMINAL='".$this->gtools->terminal()."'
                                    ) VOY_IN,
                                    (
                                            SELECT
                                                    E .VOY_OUT
                                            FROM
                                                    VES_VOYAGE E
                                            WHERE
                                                    E .ID_VES_VOYAGE = A .ID_VES_VOYAGE
                                                    AND E.ID_TERMINAL='".$this->gtools->terminal()."'
                                    ) VOY_OUT,
                                    (
                                            SELECT
                                                    B .DATE_TRINOUT
                                            FROM
                                                    JOB_TRUCK_INOUT B
                                            WHERE
                                                    B .NO_CONTAINER = A .NO_CONTAINER
                                            AND B .POINT = A .POINT
                                            AND B .EI = A .ID_CLASS_CODE
                                            AND B .IN_OUT = 'In'
                                            AND ROWNUM < 2
                                            AND B.ID_TERMINAL='".$this->gtools->terminal()."'
                                    ) GATE_IN,
                                    (
                                            SELECT
                                                    C .FULL_NAME
                                            FROM
                                                    JOB_TRUCK_INOUT B
                                            LEFT JOIN M_USERS C ON C .ID_USER = B .ID_USER
                                            WHERE
                                                    B .NO_CONTAINER = A .NO_CONTAINER
                                            AND B . POINT = A . POINT
                                            AND B .EI = A .ID_CLASS_CODE
                                            AND B .IN_OUT = 'In'
                                            AND ROWNUM < 2
                                            AND B.ID_TERMINAL='".$this->gtools->terminal()."'
                                            AND C.ID_TERMINAL='".$this->gtools->terminal()."'
                                    ) USER_GATE_IN,
                                    (
                                            SELECT
                                                    B .DATE_TRINOUT
                                            FROM
                                                    JOB_TRUCK_INOUT B
                                            WHERE
                                                    B .NO_CONTAINER = A .NO_CONTAINER
                                            AND B . POINT = A . POINT
                                            AND B .EI = A .ID_CLASS_CODE
                                            AND B .IN_OUT = 'Out'
                                            AND ROWNUM < 2
                                            AND B.ID_TERMINAL='".$this->gtools->terminal()."'
                                    ) GATE_OUT,
                                    (
                                            SELECT
                                                    C .FULL_NAME
                                            FROM
                                                    JOB_TRUCK_INOUT B
                                            LEFT JOIN M_USERS C ON C .ID_USER = B .ID_USER
                                            WHERE
                                                    B .NO_CONTAINER = A .NO_CONTAINER
                                            AND B . POINT = A . POINT
                                            AND B .EI = A .ID_CLASS_CODE
                                            AND B .IN_OUT = 'Out'
                                            AND ROWNUM < 2
                                            AND B.ID_TERMINAL='".$this->gtools->terminal()."'
                                            AND C.ID_TERMINAL='".$this->gtools->terminal()."'
                                    ) USER_GATE_OUT,
                                    A .ID_ISO_CODE,
                                    CASE
                            WHEN A .ID_CLASS_CODE = 'E' THEN
                                    (
                                            SELECT
                                                    PORT_NAME
                                            FROM
                                                    M_PORT B
                                            WHERE
                                                    B .PORT_CODE = A .ID_POD
                                    )
                            ELSE
                                    (
                                            SELECT
                                                    PORT_NAME
                                            FROM
                                                    M_PORT B
                                            WHERE
                                                    B .PORT_CODE = A .ID_POL
                                    )
                            END POD,
                            A .CONT_SIZE,
                            A .CONT_TYPE,
                            A .CONT_STATUS,
                            A .IMDG,
                            A .WEIGHT,
                            ZZ .O_I,
                            A .ITT_FLAG,
                            A .OP_STATUS_DESC,
                            XX. TERMINAL_NAME,
                            A. ID_TERMINAL,
                            A. ID_OP_STATUS
                    FROM CON_LISTCONT A
                    LEFT JOIN JOB_TRUCK_INOUT AB ON AB.NO_CONTAINER = A.NO_CONTAINER AND AB.POINT = A.POINT
                    LEFT JOIN M_PLAN_CATEGORY_D ZZ ON A .ID_VES_VOYAGE = ZZ .ID_VES_VOYAGE
                    LEFT JOIN M_TERMINAL XX ON A .ID_TERMINAL = XX .ID_TERMINAL
                    WHERE AB .DATE_TRINOUT > TO_DATE (
                                    '$gateIn',
                                    'DD-MM-YYYY HH24.MI'
                            )
                    	AND AB .DATE_TRINOUT < TO_DATE (
                            '$gateOut',
                            'DD-MM-YYYY HH24.MI'
                    	)
                    	AND A.ID_TERMINAL='".$this->gtools->terminal()."'
                    	AND AB.ID_TERMINAL='".$this->gtools->terminal()."'
                    	AND XX.ID_TERMINAL='".$this->gtools->terminal()."'
                    GROUP BY
                            A .NO_CONTAINER,
                            A . POINT,
                            A .ID_CLASS_CODE,
                            A .ID_VES_VOYAGE,
                            A .ID_ISO_CODE,
                            A .ID_POD,
                            A .ID_POL,
                            AB .ID_TRUCK, 
                            A .CONT_SIZE,
                            A .CONT_TYPE,
                            A .CONT_STATUS,
                            A .IMDG,
                            A .WEIGHT,
                            ZZ .O_I,
                            A .ITT_FLAG,
                            A .OP_STATUS_DESC,
                            XX. TERMINAL_NAME,
                            A. ID_TERMINAL,
                            A. ID_OP_STATUS
                    ORDER BY
                            A .NO_CONTAINER ASC
                    )
            	WHERE
                    GATE_IN > TO_DATE (
                            '$gateIn',
                            'DD-MM-YYYY HH24.MI'
                    )";
			if($gate == 'Gate Out'){
		            $query .= "    AND GATE_OUT < TO_DATE (
		                        '$gateOut',
		                        'DD-MM-YYYY HH24.MI'
		                )";
			}
			$query .= "
		                AND ID_CLASS_CODE IN (".implode(',',$kegiatan).")
		                AND O_I LIKE UPPER ('%$shipping_line%')
		                AND ITT_FLAG LIKE UPPER ('%$esy%')
		                AND ID_OP_STATUS LIKE '%$gate%'";
			if($vessel != ''){
			    $arrVessel = explode('-', $vessel);
			    $arrVoy = explode('/', $arrVessel[1]);
			    $query .= "
				    AND VESSEL = '$arrVessel[0]'";
			    $query .= "
				    AND VOY_IN = '$arrVoy[0]'";
			    $query .= "
				    AND VOY_OUT = '$arrVoy[1]'";
			}*/
				$query = "SELECT PG.*
					  FROM (SELECT R.*, ROWNUM REC_NUM
							  FROM (  
							  $mainquery
							  ) R
								) PG
					$qPaging";
				$rs 		= $this->db->query($query);
				$dataresult = $rs->result_array();
				$data = array (
					'total'=>$total,
					'data'=>$dataresult
				);
				//echo '<pre>'.$query.'</pre>';die();

				//debux($query);die;
				
				return $data;
	}
	
	public function get_data_report_kinerja_alat($startPeriod, $endPeriod, $alat, $id_user_operator, $action){
		$qWhere = "";
		if ($alat != "" and $alat != "null") {
			$qWhere.= " AND mm.mch_name = '$alat'";
		}
		
		if ($id_user_operator != "" and $id_user_operator != "null") {
			$qWhere.= " AND mu.id_user = '$id_user_operator'";
		}
		
		if ($action != "" and $action != "null") {
			if ($action == "Discharged") {
				$qaction=" AND A.ID_OP_STATUS IN ('SDG','SDY')";
			}
			if ($action == "Delivery") {
				$qaction=" AND A.ID_OP_STATUS IN ('OYG')";
			}
			if ($action == "Receiving") {
				$qaction=" AND A.ID_OP_STATUS IN ('YGY')";
			}
			if ($action == "Loading") {
				$qaction=" AND A.ID_OP_STATUS IN ('SLY','SLG')";
			}
		}else{
			$qaction="AND A.ID_OP_STATUS IN ('OYG','YGY','SLY','SLG','SDY','SDG') ";
		}
		
		$query1	= "select a.no_container, a.id_ves_voyage, vv.vessel_name, vv.voy_in, vv.voy_out, a.activity, a.date_entry, a.id_machine, mm.mch_name, mm.mch_type, mm.mch_sub_type, a.id_user, mu.full_name, a.id_op_status, DECODE (a.id_op_status, 'SDY', 'Discharge', 'SLY', 'Loaded', 'SDG', 'Deliver',null,'-', 'Request') AS ACT, MOS.OP_STATUS_DESC
from (

    select no_container, point, activity, id_ves_voyage, TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry, id_machine, user_entry as id_user, id_op_status
    from job_confirm 
    where ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry between TO_DATE('$startPeriod 00.00.00', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod 23.59.59', 'DD-MM-YYYY HH24.MI.SS'))
    union all 
    select no_container, point, id_class_code as activity, id_ves_voyage, TO_CHAR(placement_date, 'DD-MM-YYYY HH24.MI.SS') as date_entry, id_machine, id_user, id_op_status
    from job_placement_history
    where ID_TERMINAL = '".$this->gtools->terminal()."' AND (placement_date between TO_DATE('$startPeriod 00.00.00', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod 23.59.59', 'DD-MM-YYYY HH24.MI.SS'))
    union all 
    select no_container, point, activity, id_ves_voyage, TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry, id_machine, user_entry as id_user, id_op_status
    from job_pickup
    where ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry between TO_DATE('$startPeriod 00.00.00', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod 23.59.59', 'DD-MM-YYYY HH24.MI.SS'))
    union all 
    select no_container, point, id_class_code as activity, id_ves_voyage, TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry, id_machine, user_entry as id_user, '' as id_op_status
    from job_shifting
    where ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry between TO_DATE('$startPeriod 00.00.00', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod 23.59.59', 'DD-MM-YYYY HH24.MI.SS'))
) a 
left join ves_voyage vv on a.id_ves_voyage = vv.id_ves_voyage
left join m_machine mm on a.id_machine = mm.id_machine
left join m_users mu on a.id_user = mu.id_user
left join m_op_status mos on a.id_op_status = MOS.ID_OP_STATUS
WHERE 1=1
$qWhere AND vv.ID_TERMINAL = '".$this->gtools->terminal()."' AND mm.ID_TERMINAL = '".$this->gtools->terminal()."' AND mu.ID_TERMINAL = '".$this->gtools->terminal()."'
$qaction
order by a.date_entry";

		$query = "SELECT
			A .no_container,
			A .id_ves_voyage,
			vv.vessel_name || ' ' ||vv.voy_in ||'/'||vv.voy_out as vessel_name,
			vv.voy_in,
			vv.voy_out,
			A .activity,
			A .date_entry,
			A .id_machine,
			mm.mch_name,
			mm.mch_type,
			mm.mch_sub_type,
			A .id_user,
			mu.full_name,
			A .id_op_status,
			CN.CONT_TYPE,
			CN.CONT_SIZE,
			CN.CONT_STATUS,
			A .id_machine_itv,
			CASE 
				WHEN a.id_op_status = 'SDY' THEN 'Discharged'
				WHEN a.id_op_status = 'SDG' THEN 'Discharged'
				WHEN a.id_op_status = 'YGY' THEN 'Receiving'
				WHEN a.id_op_status = 'OYG' THEN 'Delivery'
				WHEN a.id_op_status = 'SLY' THEN 'Loading'
				WHEN a.id_op_status = 'SLG' THEN 'Loading'
			END AS ACT,
			MOS.OP_STATUS_DESC
		FROM
			(
				SELECT
					no_container,
					POINT,
					activity,
					id_ves_voyage,
					TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
					id_machine,
					user_entry AS id_user,
					--driver_id AS id_user,
					id_op_status,
					id_machine_itv
				FROM
					job_confirm
				WHERE
					ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
				UNION ALL
					SELECT
						no_container,
						POINT,
						id_class_code AS activity,
						id_ves_voyage,
						TO_CHAR(placement_date, 'DD-MM-YYYY HH24.MI.SS') AS date_entry,
						id_machine,
						id_user,
						id_op_status,
						id_machine_itv
					FROM
						job_placement_history
					WHERE
						ID_TERMINAL = '".$this->gtools->terminal()."' AND (placement_date BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
					UNION ALL
						SELECT
							no_container,
							POINT,
							activity,
							id_ves_voyage,
							TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
							id_machine,
							user_entry AS id_user,
							id_op_status,
							id_machine_itv
						FROM
							job_pickup
						WHERE
							ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
						UNION ALL
							SELECT
								no_container,
								POINT,
								id_class_code AS activity,
								id_ves_voyage,
								TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
								id_machine,
								user_entry AS id_user,
								'' AS id_op_status,
								1 AS id_machine_itv
							FROM
								job_shifting
							WHERE
								ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
			) A
		LEFT JOIN ves_voyage vv ON A .id_ves_voyage = vv.id_ves_voyage
		LEFT JOIN m_machine mm ON (A .id_machine = mm.id_machine OR A.id_machine_itv=mm.id_machine)
		LEFT JOIN m_users mu ON A .id_user = mu.id_user
		LEFT JOIN CON_LISTCONT CN ON A.NO_CONTAINER = CN.NO_CONTAINER AND A.id_ves_voyage=CN.ID_VES_VOYAGE
		LEFT JOIN m_op_status mos ON A .id_op_status = MOS.ID_OP_STATUS 
		WHERE 1=1
		$qWhere AND 
		vv.ID_TERMINAL = '".$this->gtools->terminal()."' 
		$qaction
		AND mm.MCH_TYPE IN ('QUAY','YARD','ITV')
		ORDER BY
			A .date_entry";

		//debux(query);die;
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_data_report_kinerja_alat_tampil($paging, $startPeriod, $endPeriod, $alat, $filters=false, $id_user_operator, $action){
		$qWhere = "";
		if ($alat != "" and $alat != "null") {
			$qWhere.= " AND mm.mch_name = '$alat'";
		}
		
		if ($id_user_operator != "" and $id_user_operator != "null") {
			$qWhere.= " AND mu.id_user = '$id_user_operator'";
		}
		
		if ($action != "" and $action != "null") {
			if ($action == "Discharged") {
				$qaction="AND A.ID_OP_STATUS IN ('SDG','SDY')";
			}
			if ($action == "Delivery") {
				$qaction="AND A.ID_OP_STATUS IN ('OYG')";
			}
			if ($action == "Receiving") {
				$qaction="AND A.ID_OP_STATUS IN ('YGY')";
			}
			if ($action == "Loading") {
				$qaction="AND A.ID_OP_STATUS IN ('SLY','SLG')";
			}
		}else{
			$qaction="AND A.ID_OP_STATUS IN ('OYG','YGY','SLY','SLG','SDY','SDG') ";
		}
		
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
					case'VESSEL_NAME' : $field = "vv.".$field; break;
					case'FULL_NAME'	: $field = "mu.".$field; break;
				}

				//debux($field);
				switch($filterType){
					case 'string' : $qs .= " AND UPPER(".$field.") LIKE '%".strtoupper($value)."%'"; Break;
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
		
		$qPaging = '';
		if ($paging != ''){
			$start = $paging['start']+1;
			$end = $paging['page']*$paging['limit'];
			$qPaging = "WHERE PG.REC_NUM >= $start AND PG.REC_NUM <= $end";
		}
			$mainquery = "SELECT
				A .no_container,
				A .id_ves_voyage,
				vv.vessel_name || ' ' ||vv.voy_in ||'/'||vv.voy_out as vessel_name,
				vv.voy_in,
				vv.voy_out,
				A .activity,
				A .date_entry,
				A .id_machine,
				mm.mch_name,
				mm.mch_type,
				mm.mch_sub_type,
				A .id_user,
				mu.full_name,
				A .id_op_status,
				CN.CONT_TYPE,
				CN.CONT_SIZE,
				CN.CONT_STATUS,
				A .id_machine_itv,
                    CASE 
                        WHEN a.id_op_status = 'SDY' THEN 'Discharged'
                        WHEN a.id_op_status = 'SDG' THEN 'Discharged'
                        WHEN a.id_op_status = 'YGY' THEN 'Receiving'
                        WHEN a.id_op_status = 'OYG' THEN 'Delivery'
                        WHEN a.id_op_status = 'SLY' THEN 'Loading'
                        WHEN a.id_op_status = 'SLG' THEN 'Loading'
                    END AS act,
				MOS.OP_STATUS_DESC
			FROM
				(
					SELECT
						no_container,
						POINT,
						activity,
						id_ves_voyage,
						TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
						id_machine,
						user_entry AS id_user,
						id_op_status,
						id_machine_itv
					FROM
						job_confirm
					WHERE
						ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
					UNION ALL
						SELECT
							no_container,
							POINT,
							id_class_code AS activity,
							id_ves_voyage,
							TO_CHAR(placement_date, 'DD-MM-YYYY HH24.MI.SS') AS date_entry,
							id_machine,
							id_user,
							id_op_status,
							id_machine_itv
						FROM
							job_placement_history
						WHERE
							ID_TERMINAL = '".$this->gtools->terminal()."' AND (placement_date BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
						UNION ALL
							SELECT
								no_container,
								POINT,
								activity,
								id_ves_voyage,
								TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
								id_machine,
								user_entry AS id_user,
								id_op_status,
								id_machine_itv
							FROM
								job_pickup
							WHERE
								ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
							UNION ALL
								SELECT
									no_container,
									POINT,
									id_class_code AS activity,
									id_ves_voyage,
									TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
									id_machine,
									user_entry AS id_user,
									'' AS id_op_status,
									1 AS id_machine_itv
								FROM
									job_shifting
								WHERE
									ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
				) A
			LEFT JOIN ves_voyage vv ON A .id_ves_voyage = vv.id_ves_voyage
			LEFT JOIN m_machine mm ON (A .id_machine = mm.id_machine OR A.id_machine_itv=mm.id_machine)
			LEFT JOIN m_users mu ON A .id_user = mu.id_user
			LEFT JOIN CON_LISTCONT CN ON A.NO_CONTAINER = CN.NO_CONTAINER AND A.id_ves_voyage=CN.ID_VES_VOYAGE
			LEFT JOIN m_op_status mos ON A .id_op_status = MOS.ID_OP_STATUS 
			WHERE 1=1
			$qWhere 
			AND vv.ID_TERMINAL = '".$this->gtools->terminal()."' 
			/*AND mm.ID_TERMINAL = '".$this->gtools->terminal()."' 
			AND mu.ID_TERMINAL = '".$this->gtools->terminal()."' 
			AND CN.ID_TERMINAL = '".$this->gtools->terminal()."'*/ 
			-- AND A.ID_OP_STATUS IN ('OYG','OYS','YGY','YSY','SLY','SLG','SDY','SDG') AND mm.MCH_TYPE IN ('QUAY','YARD','ITV')
			$qaction
			 AND mm.MCH_TYPE IN ('QUAY','YARD','ITV')
			ORDER BY
				A .date_entry";


			$query_count = "SELECT COUNT(R.id_ves_voyage) TOTAL
						  FROM (  
						  $mainquery
						  ) R
						  ";

			$rsqc = $this->db->query($query_count);
			$rowrsqc = $rsqc->row_array();
			$total = $rowrsqc['TOTAL'];
			
			$query = "SELECT PG.*
				  FROM (SELECT R.*, ROWNUM REC_NUM
						  FROM (  
						  $mainquery
						  ) R
							) PG
				$qPaging";
			$rs 		= $this->db->query($query);
			$dataresult = $rs->result_array();
			$data = array (
				'total'=>$total,
				'data'=>$dataresult
			);
			
			return $data;
	}

	public function getSummaryKinerjaAlat($startPeriod, $endPeriod, $alat, $user, $size, $status){
		$query = "SELECT
					(SELECT
						COUNT(A.no_container) AS JUMLAH
					FROM
						(
							SELECT
								no_container,
								POINT,
								activity,
								id_ves_voyage,
								TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
								id_machine,
								user_entry AS id_user,
								id_op_status,
								id_machine_itv
							FROM
								job_confirm
							WHERE
								ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
							UNION ALL
								SELECT
									no_container,
									POINT,
									id_class_code AS activity,
									id_ves_voyage,
									TO_CHAR(placement_date, 'DD-MM-YYYY HH24.MI.SS') AS date_entry,
									id_machine,
									id_user,
									id_op_status,
									id_machine_itv
								FROM
									job_placement_history
								WHERE
									ID_TERMINAL = '".$this->gtools->terminal()."' AND (placement_date BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
								UNION ALL
									SELECT
										no_container,
										POINT,
										activity,
										id_ves_voyage,
										TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
										id_machine,
										user_entry AS id_user,
										id_op_status,
										id_machine_itv
									FROM
										job_pickup
									WHERE
										ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
									UNION ALL
										SELECT
											no_container,
											POINT,
											id_class_code AS activity,
											id_ves_voyage,
											TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
											id_machine,
											user_entry AS id_user,
											'' AS id_op_status,
											1 AS id_machine_itv
										FROM
											job_shifting
										WHERE
											ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
						) A
					LEFT JOIN ves_voyage vv ON A .id_ves_voyage = vv.id_ves_voyage
					LEFT JOIN m_machine mm ON (A .id_machine = mm.id_machine OR A.id_machine_itv=mm.id_machine)
					LEFT JOIN m_users mu ON A .id_user = mu.id_user
					LEFT JOIN CON_LISTCONT CN ON A.NO_CONTAINER = CN.NO_CONTAINER 
					LEFT JOIN m_op_status mos ON A .id_op_status = MOS.ID_OP_STATUS
					WHERE 1=1
					 AND vv.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND mm.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND mu.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND CN.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND mm.MCH_NAME = '$alat'
					 AND A.ID_OP_STATUS IN ('SDY','SDG')
					 AND mu.FULL_NAME = '$user'
					 AND CN.CONT_SIZE = '$size'
					 AND CN.CONT_STATUS = '$status') AS JUMLAH_DISC,
					 (SELECT
						COUNT(A.no_container) AS JUMLAH
					FROM
						(
							SELECT
								no_container,
								POINT,
								activity,
								id_ves_voyage,
								TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
								id_machine,
								user_entry AS id_user,
								id_op_status,
								id_machine_itv
							FROM
								job_confirm
							WHERE
								ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
							UNION ALL
								SELECT
									no_container,
									POINT,
									id_class_code AS activity,
									id_ves_voyage,
									TO_CHAR(placement_date, 'DD-MM-YYYY HH24.MI.SS') AS date_entry,
									id_machine,
									id_user,
									id_op_status,
									id_machine_itv
								FROM
									job_placement_history
								WHERE
									ID_TERMINAL = '".$this->gtools->terminal()."' AND (placement_date BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
								UNION ALL
									SELECT
										no_container,
										POINT,
										activity,
										id_ves_voyage,
										TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
										id_machine,
										user_entry AS id_user,
										id_op_status,
										id_machine_itv
									FROM
										job_pickup
									WHERE
										ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
									UNION ALL
										SELECT
											no_container,
											POINT,
											id_class_code AS activity,
											id_ves_voyage,
											TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
											id_machine,
											user_entry AS id_user,
											'' AS id_op_status,
											1 AS id_machine_itv
										FROM
											job_shifting
										WHERE
											ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
						) A
					LEFT JOIN ves_voyage vv ON A .id_ves_voyage = vv.id_ves_voyage
					LEFT JOIN m_machine mm ON (A .id_machine = mm.id_machine OR A.id_machine_itv=mm.id_machine)
					LEFT JOIN m_users mu ON A .id_user = mu.id_user
					LEFT JOIN CON_LISTCONT CN ON A.NO_CONTAINER = CN.NO_CONTAINER 
					LEFT JOIN m_op_status mos ON A .id_op_status = MOS.ID_OP_STATUS
					WHERE 1=1
					 AND vv.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND mm.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND mu.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND CN.ID_TERMINAL = '".$this->gtools->terminal()."'
					  AND mm.MCH_NAME = '$alat'
					 AND A.ID_OP_STATUS IN ('SLY','SLG')
					 AND mu.FULL_NAME = '$user'
					 AND CN.CONT_SIZE = '$size'
					 AND CN.CONT_STATUS = '$status') AS JUMLAH_LOAD,
					(SELECT
						COUNT(A.no_container) AS JUMLAH
					FROM
						(
							SELECT
								no_container,
								POINT,
								activity,
								id_ves_voyage,
								TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
								id_machine,
								user_entry AS id_user,
								id_op_status,
								id_machine_itv
							FROM
								job_confirm
							WHERE
								ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
							UNION ALL
								SELECT
									no_container,
									POINT,
									id_class_code AS activity,
									id_ves_voyage,
									TO_CHAR(placement_date, 'DD-MM-YYYY HH24.MI.SS') AS date_entry,
									id_machine,
									id_user,
									id_op_status,
									id_machine_itv
								FROM
									job_placement_history
								WHERE
									ID_TERMINAL = '".$this->gtools->terminal()."' AND (placement_date BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
								UNION ALL
									SELECT
										no_container,
										POINT,
										activity,
										id_ves_voyage,
										TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
										id_machine,
										user_entry AS id_user,
										id_op_status,
										id_machine_itv
									FROM
										job_pickup
									WHERE
										ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
									UNION ALL
										SELECT
											no_container,
											POINT,
											id_class_code AS activity,
											id_ves_voyage,
											TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
											id_machine,
											user_entry AS id_user,
											'' AS id_op_status,
											1 AS id_machine_itv
										FROM
											job_shifting
										WHERE
											ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
						) A
					LEFT JOIN ves_voyage vv ON A .id_ves_voyage = vv.id_ves_voyage
					LEFT JOIN m_machine mm ON (A .id_machine = mm.id_machine OR A.id_machine_itv=mm.id_machine)
					LEFT JOIN m_users mu ON A .id_user = mu.id_user
					LEFT JOIN CON_LISTCONT CN ON A.NO_CONTAINER = CN.NO_CONTAINER 
					LEFT JOIN m_op_status mos ON A .id_op_status = MOS.ID_OP_STATUS
					WHERE 1=1
					 AND vv.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND mm.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND mu.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND CN.ID_TERMINAL = '".$this->gtools->terminal()."'
					  AND mm.MCH_NAME = '$alat'
					 AND A.ID_OP_STATUS IN ('YSY')
					 AND mu.FULL_NAME = '$user'
					 AND CN.CONT_SIZE = '$size'
					 AND CN.CONT_STATUS = '$status') AS JUMLAH_STACKING_INBOUND,
				(SELECT
						COUNT(A.no_container) AS JUMLAH
					FROM
						(
							SELECT
								no_container,
								POINT,
								activity,
								id_ves_voyage,
								TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
								id_machine,
								user_entry AS id_user,
								id_op_status,
								id_machine_itv
							FROM
								job_confirm
							WHERE
								ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('19-08-2019 11.30.59', 'DD-MM-YYYY HH24.MI.SS'))
							UNION ALL
								SELECT
									no_container,
									POINT,
									id_class_code AS activity,
									id_ves_voyage,
									TO_CHAR(placement_date, 'DD-MM-YYYY HH24.MI.SS') AS date_entry,
									id_machine,
									id_user,
									id_op_status,
									id_machine_itv
								FROM
									job_placement_history
								WHERE
									ID_TERMINAL = '".$this->gtools->terminal()."' AND (placement_date BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
								UNION ALL
									SELECT
										no_container,
										POINT,
										activity,
										id_ves_voyage,
										TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
										id_machine,
										user_entry AS id_user,
										id_op_status,
										id_machine_itv
									FROM
										job_pickup
									WHERE
										ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
									UNION ALL
										SELECT
											no_container,
											POINT,
											id_class_code AS activity,
											id_ves_voyage,
											TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
											id_machine,
											user_entry AS id_user,
											'' AS id_op_status,
											1 AS id_machine_itv
										FROM
											job_shifting
										WHERE
											ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
						) A
					LEFT JOIN ves_voyage vv ON A .id_ves_voyage = vv.id_ves_voyage
					LEFT JOIN m_machine mm ON (A .id_machine = mm.id_machine OR A.id_machine_itv=mm.id_machine)
					LEFT JOIN m_users mu ON A .id_user = mu.id_user
					LEFT JOIN CON_LISTCONT CN ON A.NO_CONTAINER = CN.NO_CONTAINER 
					LEFT JOIN m_op_status mos ON A .id_op_status = MOS.ID_OP_STATUS
					WHERE 1=1
					 AND vv.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND mm.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND mu.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND CN.ID_TERMINAL = '".$this->gtools->terminal()."'
					  AND mm.MCH_NAME = '$alat'
					 AND A.ID_OP_STATUS IN ('YGY')
					 AND mu.FULL_NAME = '$user'
					 AND CN.CONT_SIZE = '$size'
					 AND CN.CONT_STATUS = '$status') AS JUMLAH_STACKING_OUTBOUND,
				(SELECT
						COUNT(A.no_container) AS JUMLAH
					FROM
						(
							SELECT
								no_container,
								POINT,
								activity,
								id_ves_voyage,
								TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
								id_machine,
								user_entry AS id_user,
								id_op_status,
								id_machine_itv
							FROM
								job_confirm
							WHERE
								ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
							UNION ALL
								SELECT
									no_container,
									POINT,
									id_class_code AS activity,
									id_ves_voyage,
									TO_CHAR(placement_date, 'DD-MM-YYYY HH24.MI.SS') AS date_entry,
									id_machine,
									id_user,
									id_op_status,
									id_machine_itv
								FROM
									job_placement_history
								WHERE
									ID_TERMINAL = '".$this->gtools->terminal()."' AND (placement_date BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
								UNION ALL
									SELECT
										no_container,
										POINT,
										activity,
										id_ves_voyage,
										TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
										id_machine,
										user_entry AS id_user,
										id_op_status,
										id_machine_itv
									FROM
										job_pickup
									WHERE
										ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
									UNION ALL
										SELECT
											no_container,
											POINT,
											id_class_code AS activity,
											id_ves_voyage,
											TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
											id_machine,
											user_entry AS id_user,
											'' AS id_op_status,
											1 AS id_machine_itv
										FROM
											job_shifting
										WHERE
											ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
						) A
					LEFT JOIN ves_voyage vv ON A .id_ves_voyage = vv.id_ves_voyage
					LEFT JOIN m_machine mm ON (A .id_machine = mm.id_machine OR A.id_machine_itv=mm.id_machine)
					LEFT JOIN m_users mu ON A .id_user = mu.id_user
					LEFT JOIN CON_LISTCONT CN ON A.NO_CONTAINER = CN.NO_CONTAINER 
					LEFT JOIN m_op_status mos ON A .id_op_status = MOS.ID_OP_STATUS
					WHERE 1=1
					 AND vv.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND mm.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND mu.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND CN.ID_TERMINAL = '".$this->gtools->terminal()."'
					  AND mm.MCH_NAME = '$alat'
					 AND A.ID_OP_STATUS IN ('OYG')
					 AND mu.FULL_NAME = '$user'
					 AND CN.CONT_SIZE = '$size'
					 AND CN.CONT_STATUS = '$status') AS JUMLAH_CHASSIS_INBOUND,
				(SELECT
						COUNT(A.no_container) AS JUMLAH
					FROM
						(
							SELECT
								no_container,
								POINT,
								activity,
								id_ves_voyage,
								TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
								id_machine,
								user_entry AS id_user,
								id_op_status,
								id_machine_itv
							FROM
								job_confirm
							WHERE
								ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
							UNION ALL
								SELECT
									no_container,
									POINT,
									id_class_code AS activity,
									id_ves_voyage,
									TO_CHAR(placement_date, 'DD-MM-YYYY HH24.MI.SS') AS date_entry,
									id_machine,
									id_user,
									id_op_status,
									id_machine_itv
								FROM
									job_placement_history
								WHERE
									ID_TERMINAL = '".$this->gtools->terminal()."' AND (placement_date BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
								UNION ALL
									SELECT
										no_container,
										POINT,
										activity,
										id_ves_voyage,
										TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
										id_machine,
										user_entry AS id_user,
										id_op_status,
										id_machine_itv
									FROM
										job_pickup
									WHERE
										ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
									UNION ALL
										SELECT
											no_container,
											POINT,
											id_class_code AS activity,
											id_ves_voyage,
											TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
											id_machine,
											user_entry AS id_user,
											'' AS id_op_status,
											1 AS id_machine_itv
										FROM
											job_shifting
										WHERE
											ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
						) A
					LEFT JOIN ves_voyage vv ON A .id_ves_voyage = vv.id_ves_voyage
					LEFT JOIN m_machine mm ON (A .id_machine = mm.id_machine OR A.id_machine_itv=mm.id_machine)
					LEFT JOIN m_users mu ON A .id_user = mu.id_user
					LEFT JOIN CON_LISTCONT CN ON A.NO_CONTAINER = CN.NO_CONTAINER 
					LEFT JOIN m_op_status mos ON A .id_op_status = MOS.ID_OP_STATUS
					WHERE 1=1
					 AND vv.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND mm.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND mu.ID_TERMINAL = '".$this->gtools->terminal()."'
					 AND CN.ID_TERMINAL = '".$this->gtools->terminal()."'
					  AND mm.MCH_NAME = '$alat'
					 AND A.ID_OP_STATUS IN ('OYS')
					 AND mu.FULL_NAME = '$user'
					 AND CN.CONT_SIZE = '$size'
					 AND CN.CONT_STATUS = '$status') AS JUMLAH_CHASSIS_OUTBOUND
			FROM DUAL";

			$rs 		= $this->db->query($query);
			$data 		= $rs->row();
		
			return $data;
	}

	public function getMachineKinerjaAlat($startPeriod, $endPeriod, $alat, $id_user_operator, $action){
		$qWhere = "";
		if ($alat != "" and $alat != "null") {
			$qWhere.= " AND mm.mch_name = '$alat'";
		}
		
		if ($id_user_operator != "" and $id_user_operator != "null") {
			$qWhere.= " AND mu.id_user = '$id_user_operator'";
		}
		
		if ($action != "" and $action != "null") {
			if ($action == "Discharged") {
				$qaction=" AND A.ID_OP_STATUS IN ('SDG','SDY')";
			}
			if ($action == "Delivery") {
				$qaction=" AND A.ID_OP_STATUS IN ('OYG')";
			}
			if ($action == "Receiving") {
				$qaction=" AND A.ID_OP_STATUS IN ('YGY')";
			}
			if ($action == "Loading") {
				$qaction=" AND A.ID_OP_STATUS IN ('SLY','SLG')";
			}
		}else{
			$qaction="AND A.ID_OP_STATUS IN ('OYG','YGY','SLY','SLG','SDY','SDG') ";
		}
		$query = "SELECT
						mm.mch_name,
						mu.full_name
					FROM
						(
							SELECT
								no_container,
								POINT,
								activity,
								id_ves_voyage,
								TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
								id_machine,
								user_entry AS id_user,
								id_op_status,
								id_machine_itv
							FROM
								job_confirm
							WHERE
								ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
							UNION ALL
								SELECT
									no_container,
									POINT,
									id_class_code AS activity,
									id_ves_voyage,
									TO_CHAR(placement_date, 'DD-MM-YYYY HH24.MI.SS') AS date_entry,
									id_machine,
									id_user,
									id_op_status,
									id_machine_itv
								FROM
									job_placement_history
								WHERE
									ID_TERMINAL = '".$this->gtools->terminal()."' AND (placement_date BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
								UNION ALL
									SELECT
										no_container,
										POINT,
										activity,
										id_ves_voyage,
										TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
										id_machine,
										user_entry AS id_user,
										id_op_status,
										id_machine_itv
									FROM
										job_pickup
									WHERE
										ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
									UNION ALL
										SELECT
											no_container,
											POINT,
											id_class_code AS activity,
											id_ves_voyage,
											TO_CHAR(date_entry, 'DD-MM-YYYY HH24.MI.SS') date_entry,
											id_machine,
											user_entry AS id_user,
											'' AS id_op_status,
											1 AS id_machine_itv
										FROM
											job_shifting
										WHERE
											ID_TERMINAL = '".$this->gtools->terminal()."' AND (date_entry BETWEEN TO_DATE('$startPeriod', 'DD-MM-YYYY HH24.MI.SS') and TO_DATE('$endPeriod', 'DD-MM-YYYY HH24.MI.SS'))
						) A
					LEFT JOIN ves_voyage vv ON A.id_ves_voyage = vv.id_ves_voyage
					LEFT JOIN m_machine mm ON (A .id_machine = mm.id_machine OR A.id_machine_itv=mm.id_machine) AND vv.ID_TERMINAL = mm.ID_TERMINAL
					LEFT JOIN m_users mu ON A.id_user = mu.id_user
					LEFT JOIN M_USER_TERMINAL MUT ON MU.ID_USER = MUT.ID_USER AND vv.ID_TERMINAL = mut.ID_TERMINAL
					LEFT JOIN CON_LISTCONT CN ON A.NO_CONTAINER = CN.NO_CONTAINER AND vv.ID_TERMINAL = cn.ID_TERMINAL
					LEFT JOIN m_op_status mos ON A .id_op_status = MOS.ID_OP_STATUS 
					WHERE 1=1
					$qWhere AND vv.ID_TERMINAL = '".$this->gtools->terminal()."' 
					$qaction
					GROUP BY mm.MCH_NAME, mu.FULL_NAME
					ORDER BY mm.MCH_NAME, mu.FULL_NAME";

		//debux($query);
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}
}
?>