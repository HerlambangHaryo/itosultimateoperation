<?php
class Vessel extends CI_Model {
	public function __construct(){
	    $this->load->model('gtools');
	    $this->load->database();
	}

	public function check_voyage_number($id_vessel, $voy_in, $voy_out, $id_ves_voyage){
		$param = array($id_vessel, $voy_in, $voy_out, $this->gtools->terminal());
		$query = "SELECT ID_VES_VOYAGE
					FROM VES_VOYAGE
					WHERE ID_VESSEL=? AND UPPER(VOY_IN)=UPPER(?) AND UPPER(VOY_OUT)=UPPER(?) AND ID_TERMINAL=?";
		$rs = $this->db->query($query, $param);
		$row = $rs->row_array();
		if ($row['ID_VES_VOYAGE']!=''){
			if ($row['ID_VES_VOYAGE']==$id_ves_voyage){
				return 1;
			}else{
				return 0;
			}
		}else {
			return 1;
		}
	}

	public function get_vvd($vessel_name){
		$sql = "SELECT LENGTH FROM ITOS_OP.M_VESSEL WHERE VESSEL_NAME LIKE '%$vessel_name%'";
		$row = $this->db->query($sql)->row();
		return $row;

	}

	public function data_vessel_year(){
		$sql = "SELECT DISTINCT(YEAR) AS TAHUN FROM ITOS_OP.VES_VOYAGE";
		$row = $this->db->query($sql)->row();
		return $row;
	}

	public function data_vessel_by_year($year){
		$sql = "SELECT
				B.ID_VES_VOYAGE,
				B.YEAR,
				A.VESSEL,
				A.BERTH,
				A.VOYAGE,
				A.VOYAGE_IN,
				A.VOYAGE_OUT,
				A.OPERATOR_ID,
				(SELECT VESSEL_SERVICE_NAME FROM M_VESSEL_SERVICE WHERE ID_VESSEL_SERVICE = B.IN_SERVICE) AS IN_SERVICE,
				TO_CHAR ((B.ATD), 'MON') AS MONTH,
				TO_CHAR ((B.ATB), 'WW') AS WEEK_ATD,
				B.ATB,
				(SELECT TO_CHAR (MIN(COMPLETE_DATE), 'DD-MM-YYYY HH24:MI') AS DISCHARGE_COMMENCE
					FROM JOB_QUAY_MANAGER A 
					INNER JOIN CON_INBOUND_SEQUENCE CO ON A.NO_CONTAINER = CO.NO_CONTAINER AND A.POINT = CO.POINT AND A.ID_VES_VOYAGE = CO.ID_VES_VOYAGE
					WHERE A.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND A.STATUS_FLAG = 'C'
					GROUP BY A.ID_VES_VOYAGE) AS DISC_COMMENCE,
				(SELECT TO_CHAR (MIN(COMPLETE_DATE), 'DD-MM-YYYY HH24:MI') AS LOAD_COMMENCE
					FROM JOB_QUAY_MANAGER A 
					INNER JOIN CON_OUTBOUND_SEQUENCE CO ON A.NO_CONTAINER = CO.NO_CONTAINER AND A.POINT = CO.POINT AND A.ID_VES_VOYAGE = CO.ID_VES_VOYAGE
					WHERE A.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND A.STATUS_FLAG = 'C'
					GROUP BY A.ID_VES_VOYAGE) AS LOAD_COMMENCE,
				(SELECT TO_CHAR (MAX(COMPLETE_DATE), 'DD-MM-YYYY HH24:MI') AS DISCHARGE_COMPLETE
					FROM JOB_QUAY_MANAGER A 
					INNER JOIN CON_INBOUND_SEQUENCE CO ON A.NO_CONTAINER = CO.NO_CONTAINER AND A.POINT = CO.POINT AND A.ID_VES_VOYAGE = CO.ID_VES_VOYAGE
					WHERE A.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND A.STATUS_FLAG = 'C'
					GROUP BY A.ID_VES_VOYAGE) AS DISC_COMPLETE,
				(SELECT TO_CHAR (MAX(COMPLETE_DATE), 'DD-MM-YYYY HH24:MI') AS LOAD_COMPLETE
					FROM JOB_QUAY_MANAGER A 
					INNER JOIN CON_OUTBOUND_SEQUENCE CO ON A.NO_CONTAINER = CO.NO_CONTAINER AND A.POINT = CO.POINT AND A.ID_VES_VOYAGE = CO.ID_VES_VOYAGE
					WHERE A.ID_VES_VOYAGE = B.ID_VES_VOYAGE AND A.STATUS_FLAG = 'C'
					GROUP BY A.ID_VES_VOYAGE) AS LOAD_COMPLETE,
				B.ATD,
				B.CUTOFF_DATE AS CLOSING_TIME,
				(CASE WHEN B.ALONG_SIDE = 'P' THEN 'Portside'
				ELSE
				'Starboard'
				END) AS ALONG_SIDE,
				B.START_METER AS START_POS,
				B.END_METER AS BRDG_POS,
				A.OPERATOR_NAME AS SHIPPING_LINE,
				ROUND((B.DISCHARGE_COMPLETE - B.DISCHARGE_COMMENCE) * 24 * 60,0) AS DIFF_DISCHARGE,
				ROUND((B.LOAD_COMPLETE - B.LOAD_COMMENCE) * 24 * 60,0) AS DIFF_LOADING,
				ROUND((B.ATD - B.ATB) * 24 * 60,0) AS BERTH_TIME
			FROM
				ITOS_REPO.M_VSB_VOYAGE A
			JOIN ITOS_OP.VES_VOYAGE B ON A.UKKS = B.ID_VES_VOYAGE
			AND B.YEAR = '$year'
			--AND ROWNUM <= 2
			";
		//debux($sql);die;
		$row = $this->db->query($sql)->result();
		return $row;	
	}

	public function get_all_machines(){
		$sql = "SELECT * FROM M_MACHINE WHERE MCH_TYPE IN ('QUAY') 
		--AND MCH_NAME IN ('QC01','SC01','HMC01')
		";
		$row = $this->db->query($sql)->result();
		return $row;
	}

	public function get_all_suspend(){
		$sql = "SELECT * FROM ITOS_OP.M_SUSPEND WHERE EQ_TYPE = 'QUAY' ORDER BY ID_SUSPEND ASC";
		$row = $this->db->query($sql)->result();
		return $row;
	}

	public function get_detail_mch($id_ves_voyage){
		$sql = "SELECT
					A.ID_VES_VOYAGE,
					A.ID_MACHINE,
					A.MCH_NAME,
					(SELECT TO_CHAR (MIN(COMPLETE_DATE), 'DD-MM-YYYY HH24:MI') COMMENCE_WORK
						FROM JOB_QUAY_MANAGER
						WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND  ID_MACHINE=A.ID_MACHINE) START_WORK,
					(SELECT CASE WHEN MIN(COMPLETE_DATE) IS NOT NULL THEN
								CASE WHEN SUM(CASE WHEN STATUS_FLAG = 'P' THEN 1 ELSE 0 END) > 0 
									THEN TO_CHAR (SYSDATE, 'DD-MM-Y	YYY HH24:MI') 
									ELSE TO_CHAR (MAX(COMPLETE_DATE), 'DD-MM-YYYY HH24:MI') END
							ELSE ''
							END AS CURRENT_WORK
							FROM JOB_QUAY_MANAGER
							WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND  ID_MACHINE=A.ID_MACHINE) END_WORK,
					(SELECT COUNT(*)
					   	FROM CON_LISTCONT C
					   	LEFT JOIN ITOS_BILLING.REQ_RECEIVING_D D ON D.NO_CONTAINER = C.NO_CONTAINER
						LEFT JOIN ITOS_BILLING.REQ_RECEIVING_H E ON E.ID_REQ = D.NO_REQ_ANNE
						JOIN CON_OUTBOUND_SEQUENCE F ON C.NO_CONTAINER = F.NO_CONTAINER
						WHERE TRIM(C.ID_VES_VOYAGE) = A.ID_VES_VOYAGE 
							AND C.QC_PLAN = A.MCH_NAME
							AND C.ID_OP_STATUS <> 'DIS'
							AND C.ID_CLASS_CODE = 'E'
							AND E.STATUS IN ('P','T')
							AND C.ID_TERMINAL = '103') AS TOTAL_LOAD,
					(SELECT COUNT (*)
					   FROM CON_LISTCONT
					    WHERE TRIM(ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND ID_TERMINAL = '103' AND ID_OP_STATUS <> 'DIS'
						AND ID_CLASS_CODE IN ('I','TI')
						AND QC_PLAN = A.MCH_NAME) AS TOTAL_DISC,
					(SELECT COUNT (*)
						FROM CON_LISTCONT C
						INNER JOIN JOB_QUAY_MANAGER B ON C.NO_CONTAINER = B.NO_CONTAINER AND C.ID_VES_VOYAGE = B.ID_VES_VOYAGE
						WHERE TRIM(C.ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND C.ID_TERMINAL='103' AND C.QC_PLAN = A.MCH_NAME
						AND B.STATUS_FLAG = 'C' 
						AND C.ID_CLASS_CODE IN ('I','TI','TC')) AS COMPLETE_DISC,
					(SELECT COUNT (*)
						FROM CON_LISTCONT C
						INNER JOIN JOB_QUAY_MANAGER B ON C.NO_CONTAINER = B.NO_CONTAINER AND C.ID_VES_VOYAGE = B.ID_VES_VOYAGE
						WHERE TRIM(C.ID_VES_VOYAGE) = TRIM(A.ID_VES_VOYAGE) AND C.ID_TERMINAL='103' AND C.QC_PLAN = A.MCH_NAME AND B.STATUS_FLAG = 'C' AND C.ID_CLASS_CODE IN ('E','TE')) AS COMPLETE_LOAD
					FROM
						MCH_WORKING_PLAN A
					WHERE
						A.ID_VES_VOYAGE = '$id_ves_voyage'";
		$row = $this->db->query($sql)->result();
		return $row;

	}


	public function check_vessel_voyage($id_ves_voyage){
		$param = array($id_ves_voyage,$this->gtools->terminal());
		$query 		= "SELECT VESSEL_NAME||' '||VOY_IN||'-'||VOY_OUT AS VESVOY FROM VES_VOYAGE WHERE UPPER(TRIM(ID_VES_VOYAGE)) = UPPER(TRIM(?)) AND ID_TERMINAL=?";
		$rs 		= $this->db->query($query, $param);
//		echo '<pre>'. $this->db->last_query().'</pre>';exit;
		$data 		= $rs->row_array();

		if ($data['VESVOY']!=''){
			return 1;
		} else {
			return 0;
		}
	}

	public function getFlTongkang($id_ves_voyage){
		$query 		= "SELECT FL_TONGKANG FROM VES_VOYAGE WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs 		= $this->db->query($query);
		$data 		= $rs->row_array();

		return $data['FL_TONGKANG'];
	}

	public function get_berth_meter(){
		$query = "SELECT mk.id_kade, mk.kade_name, mk.start_meter, mk.end_meter
			from M_KADE mk where mk.terminal = '".$this->config->item('SITE_PORT_CODE')."'";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		$total_length = 0;
		$kade_list = array();
		foreach ($data as $kade){
			$kade_length = $kade['END_METER'] - $kade['START_METER'];
			$total_length += $kade_length;
			$kade['length'] = $kade_length;
			$kade_list[] = $kade;
		}

		return array('total_length'=>$total_length,'kade_list'=>$kade_list);
	}

	public function get_vessel_berthing(){
		$query = "SELECT vv.*,
			mv.OPERATOR, mv.CALL_SIGN, mv.COUNTRY_CODE
		  FROM VES_VOYAGE vv
		  left join M_VESSEL mv on (vv.id_vessel = mv.id_vessel)
		 	WHERE	 vv.ata IS NOT NULL
			   AND vv.ata <= SYSDATE
			   AND (vv.atd IS NULL OR vv.atd >= SYSDATE)
			   AND vv.ID_TERMINAL = '".$this->gtools->terminal()."'";
		$rs = $this->db->query($query);
		return $rs->result_array();
	}

	public function get_vessel_berthing_monitoring(){
		$query = "SELECT vv.ACTIVE, vv.ALONG_SIDE, vv.ATA,
				vv.ATB, vv.ATD, vv.BOOKING_STACK,
				vv.CUTOFF_DATE, vv.CUTOFF_DOC_DATE,
				vv.END_METER, vv.END_WORK, vv.ETA,
				vv.ETB, vv.ETD, vv.ID_KADE,
				vv.ID_VES_VOYAGE, vv.ID_VESSEL, vv.IN_SERVICE,
				vv.OPEN_STACK_DATE, vv.OPERATOR_NAME,
				vv.OUT_SERVICE, vv.POINT, vv.START_METER,
				vv.START_WORK, vv.VESSEL_NAME, vv.VOY_IN,
				vv.VOY_OUT, vv.YEAR,
				mv.OPERATOR, mv.CALL_SIGN, mv.COUNTRY_CODE
			  FROM VES_VOYAGE vv left join
					M_VESSEL mv on (vv.id_vessel = mv.id_vessel)
			 WHERE     vv.ata IS NOT NULL
				   AND vv.ata <= SYSDATE
				   AND (vv.atd IS NULL OR vv.atd >= SYSDATE)
				   AND vv.ID_TERMINAL = '".$this->gtools->terminal()."'
				   --AND VV.ACTIVE != 'N'
			ORDER BY ATA DESC";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		// var_dump($data); die;
		for($i=0;$i<sizeof($data);$i++){

		}
		$list_kade_m = array(); $data_ret = array();
		for($i=0;$i<sizeof($data);$i++){
			// echo $data[$i]['ID_KADE'] . ',' . $data[$i]['START_METER'] . ',' . $data[$i]['END_METER'] . '<br />';
			if ($i==0 || !$this->is_contained($list_kade_m, $data[$i]['ID_KADE'], $data[$i]['START_METER'], $data[$i]['END_METER'])){
				array_push($list_kade_m, array ($data[$i]['ID_KADE'], $data[$i]['START_METER'], $data[$i]['END_METER']));
				array_push($data_ret, $data[$i]);
				// echo "masuk";
			} else {
				// echo "tidakmasuk";
			}
		}
		// die;
		return $data_ret;
	}

	public function is_contained($list_kade, $id_kade, $sm, $em){
		$found = false; $idx = 0;
		// echo 'Data array masukan: ' . json_encode($list_kade) . "\n";
		// echo 'Data yang dicompare: ' . $id_kade . ',' . $sm . ',' . $em . "\n";
		while (!$found && $idx < sizeof($list_kade)){
			// echo " iterasi ke-" . $idx . "\n";
			if ($list_kade[$idx][0] == $id_kade &&
				((intval($sm) >= intval($list_kade[$idx][1]) /* start meter */ && intval($sm) <= intval($list_kade[$idx][2]) /* end meter */ )
				|| (intval($em) >= intval($list_kade[$idx][1]) /* start meter */ && intval($em) <= intval($list_kade[$idx][2]) /* end meter */ )
				|| (intval($em) >= intval($list_kade[$idx][2]) && intval($sm) <= intval($list_kade[$idx][2]))
				)
			){
				$found = true;
			}
			$idx++;
		}
		return $found;
	}

	public function get_sum_container($class_code, $vess_voyage){
		$query = "SELECT SUM (1) TOTAL_CONTAINER
		  	FROM con_listcont
		 	WHERE id_ves_voyage = '$vess_voyage' AND id_class_code = '$class_code' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$rs = $this->db->query($query);

		if (isset($rs->row()->TOTAL_CONTAINER)){
			return $rs->row()->TOTAL_CONTAINER;
		} else {
			return 0;
		}
	}

	public function get_sum_container_confirm($class_code, $vess_voyage){
		$query = "SELECT case SUM(1) when NULL then 0 else SUM(1) end TOTAL_CONTAINER
		  FROM	con_listcont c
			   INNER JOIN
				  job_confirm jc
			   ON (c.no_container = jc.no_container)
		 WHERE c.id_ves_voyage = '$vess_voyage' AND c.id_class_code = '$class_code' AND c.ID_TERMINAL = '".$this->gtools->terminal()."' AND jc.ID_TERMINAL = '".$this->gtools->terminal()."'";
		$rs = $this->db->query($query);

		if (isset($rs->row()->TOTAL_CONTAINER)){
			return $rs->row()->TOTAL_CONTAINER;
		} else {
			return 0;
		}
	}

	public function get_vessel_schedule($id_ves_voyage='', $paging=false, $sort=false, $filters=false){
		if ($id_ves_voyage){
			$query = "SELECT V.ID_VES_VOYAGE,
				   V.ID_VESSEL,
				   V.VOY_IN,
				   V.VOY_OUT,
				   V.VESSEL_NAME,
				   V.POINT,
				   V.YEAR,
				   V.ID_KADE,
				   V.ALONG_SIDE,
				   V.START_METER,
				   V.END_METER,
				   V.CREATE_DATE,
				   V.CREATE_USER,
			 	   V.CREATE_IP,
				   V.MODIFY_DATE,
				   V.MODIFY_USER,
				   V.MODIFY_IP,
				   V.STV_COMPANY,
				   V.APP_BOOKING_STACK,
				   V.BOOKING_STACK,
				   V.TL_RECEIVING,
				   V.VESSEL_NAME || ' - ' || V.ID_VES_VOYAGE VESSEL,
				   TO_CHAR (V.ETA, 'DD-MM-YYYY HH24 MI') ETA,
				   TO_CHAR (V.ETB, 'DD-MM-YYYY HH24 MI') ETB,
				   TO_CHAR (V.ETD, 'DD-MM-YYYY HH24 MI') ETD,
				   TO_CHAR (V.CUTOFF_DATE, 'DD-MM-YYYY HH24 MI') CUTOFF_DATE,
				   TO_CHAR (V.OPEN_STACK_DATE, 'DD-MM-YYYY HH24 MI') OPEN_STACK_DATE,
				   TO_CHAR (V.EARLY_STACK_DATE, 'DD-MM-YYYY HH24 MI') EARLY_STACK_DATE,
				   V.IN_SERVICE,
				   V.OUT_SERVICE,
				   TO_CHAR (V.ATA, 'DD-MM-YYYY HH24 MI') ATA,
				   TO_CHAR (V.ATB, 'DD-MM-YYYY HH24 MI') ATB,
				   TO_CHAR (V.ATD, 'DD-MM-YYYY HH24 MI') ATD,
				   TO_CHAR (V.CUTOFF_DOC_DATE, 'DD-MM-YYYY HH24 MI') CUTOFF_DOC_DATE,
				   V.FL_TONGKANG,
				   TO_CHAR (B.LOAD_COMMENCE, 'DD-MM-YYYY HH24 MI') LOAD_COMMENCE,
				   TO_CHAR (B.LOAD_COMPLETE, 'DD-MM-YYYY HH24 MI') LOAD_COMPLETE,
				   TO_CHAR (A.DISCHARGE_COMMENCE, 'DD-MM-YYYY HH24 MI') DISCHARGE_COMMENCE,
				   TO_CHAR (A.DISCHARGE_COMPLETE, 'DD-MM-YYYY HH24 MI') DISCHARGE_COMPLETE,
				   Z.LENGTH
			  	FROM VES_VOYAGE V
			  	LEFT JOIN M_VESSEL Z ON Z.VESSEL_NAME = V.VESSEL_NAME
				LEFT JOIN (
			  		SELECT A.ID_VES_VOYAGE,MIN(COMPLETE_DATE) AS DISCHARGE_COMMENCE,MAX(COMPLETE_DATE) AS DISCHARGE_COMPLETE
					FROM JOB_QUAY_MANAGER A 
					INNER JOIN CON_INBOUND_SEQUENCE B ON A.NO_CONTAINER = B.NO_CONTAINER AND A.POINT = B.POINT AND A.ID_VES_VOYAGE = B.ID_VES_VOYAGE
					WHERE A.ID_VES_VOYAGE = '$id_ves_voyage' AND A.STATUS_FLAG = 'C'
					GROUP BY A.ID_VES_VOYAGE
			  	) A ON V.ID_VES_VOYAGE = A.ID_VES_VOYAGE
			  	LEFT JOIN (
			  		SELECT A.ID_VES_VOYAGE,MIN(COMPLETE_DATE) AS LOAD_COMMENCE,MAX(COMPLETE_DATE) AS LOAD_COMPLETE
					FROM JOB_QUAY_MANAGER A 
					INNER JOIN CON_OUTBOUND_SEQUENCE B ON A.NO_CONTAINER = B.NO_CONTAINER AND A.POINT = B.POINT AND A.ID_VES_VOYAGE = B.ID_VES_VOYAGE
					WHERE A.ID_VES_VOYAGE = '$id_ves_voyage' AND A.STATUS_FLAG = 'C'
					GROUP BY A.ID_VES_VOYAGE
			  	) B ON V.ID_VES_VOYAGE = B.ID_VES_VOYAGE
			 	WHERE V.ID_VES_VOYAGE = '$id_ves_voyage' AND V.ID_TERMINAL='".$this->gtools->terminal()."'";
			 
			$rs = $this->db->query($query);
//			debux($this->db->last_query());die();
			$data = $rs->row_array();
			$eta = explode(' ', $data['ETA']);
			$data['ETA_DATE'] = $eta[0];
			$data['ETA_HOUR'] = $eta[1];
			$data['ETA_MIN'] = $eta[2];
			$etb = explode(' ', $data['ETB']);
			$data['ETB_DATE'] = $etb[0];
			$data['ETB_HOUR'] = $etb[1];
			$data['ETB_MIN'] = $etb[2];
			$etd = explode(' ', $data['ETD']);
			$data['ETD_DATE'] = $etd[0];
			$data['ETD_HOUR'] = $etd[1];
			$data['ETD_MIN'] = $etd[2];
			$cutoffdoc = explode(' ', $data['CUTOFF_DOC_DATE']);
			$data['CUTOFF_DATE_DOC'] = $cutoffdoc[0];
			$data['CUTOFF_HOUR_DOC'] = $cutoffdoc[1];
			$data['CUTOFF_MIN_DOC'] = $cutoffdoc[2];
			$cutoff = explode(' ', $data['CUTOFF_DATE']);
			$data['CUTOFF_DATE'] = $cutoff[0];
			$data['CUTOFF_HOUR'] = $cutoff[1];
			$data['CUTOFF_MIN'] = $cutoff[2];
			
			$openstack = explode(' ', $data['OPEN_STACK_DATE']);
			$data['OPEN_STACK_DATE'] = $openstack[0];
			$data['OPEN_STACK_HOUR'] = $openstack[1];
			$data['OPEN_STACK_MIN'] = $openstack[2];
			
			#early stack
			$earlystack = explode(' ', $data['EARLY_STACK_DATE']);
			$data['EARLY_STACK_DATE'] = $earlystack[0];
			$data['EARLY_STACK_HOUR'] = $earlystack[1];
			$data['EARLY_STACK_MIN'] = $earlystack[2];

			#stevedoring company
			$data['ID_COMPANY'] = $data['STV_COMPANY'];

			$ata = explode(' ', $data['ATA']);
			$data['ATA_DATE'] = $ata[0];
			$data['ATA_HOUR'] = $ata[1];
			$data['ATA_MIN'] = $ata[2];
			$atb = explode(' ', $data['ATB']);
			$data['ATB_DATE'] = $atb[0];
			$data['ATB_HOUR'] = $atb[1];
			$data['ATB_MIN'] = $atb[2];
			$atd = explode(' ', $data['ATD']);
			$data['ATD_DATE'] = $atd[0];
			$data['ATD_HOUR'] = $atd[1];
			$data['ATD_MIN'] = $atd[2];
			$lcommence = explode(' ', $data['LOAD_COMMENCE']);
			$data['lcommence_DATE'] = $lcommence[0];
			$data['lcommence_HOUR'] = $lcommence[1];
			$data['lcommence_MIN'] = $lcommence[2];
			$lcomplete = explode(' ', $data['LOAD_COMPLETE']);
			$data['lcomplete_DATE'] = $lcomplete[0];
			$data['lcomplete_HOUR'] = $lcomplete[1];
			$data['lcomplete_MIN'] = $lcomplete[2];
			$dcommence = explode(' ', $data['DISCHARGE_COMMENCE']);
			$data['dcommence_DATE'] = $dcommence[0];
			$data['dcommence_HOUR'] = $dcommence[1];
			$data['dcommence_MIN'] = $dcommence[2];
			$dcomplete = explode(' ', $data['DISCHARGE_COMPLETE']);
			$data['dcomplete_DATE'] = $dcomplete[0];
			$data['dcomplete_HOUR'] = $dcomplete[1];
			$data['dcomplete_MIN'] = $dcomplete[2];
			if ($data['FL_TONGKANG'] == null) {
				$data['FL_TONGKANG'] = 'N';
			}

		}else{
			$query_count = "SELECT COUNT(ID_VES_VOYAGE) TOTAL
							FROM VES_VOYAGE WHERE ACTIVE='Y' AND ID_TERMINAL='".$this->gtools->terminal()."'";
			if($this->gtools->terminal() != ''){
//			    $query_count .= " AND ";
			}
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
				if ($sortProperty=='ARRIVAL'){
					$sortProperty = 'ETA';
				}
				if ($sortProperty=='BERTH'){
					$sortProperty = 'ETB';
				}
				if ($sortProperty=='DEPARTURE'){
					$sortProperty = 'ETD';
				}
				if ($sortProperty=='VESSEL_DETAIL'){
					$sortProperty = 'ID_VESSEL';
				}
				$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
			}
			$qWhere = "WHERE V.ACTIVE='Y'";
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
						case 'VESSEL_DETAIL' : $field = "V.VESSEL_NAME"; break;
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
						  FROM (SELECT A.*, ROWNUM REC_NUM
								  FROM (  SELECT V.*, TO_CHAR(NVL(ATA,ETA),'DD-MM-YYYY HH24:MI') ARRIVAL, TO_CHAR(NVL(ATB,ETB),'DD-MM-YYYY HH24:MI') BERTH, TO_CHAR(NVL(ATD,ETD),'DD-MM-YYYY HH24:MI') DEPARTURE
											FROM VES_VOYAGE V
											$qWhere AND V.ID_TERMINAL='".$this->gtools->terminal()."'
										$qSort) A
									) B
						$qPaging";
			// print $query;
			$rs = $this->db->query($query);
			$vessel_voyage = $rs->result_array();

			for($i=0;$i<sizeof($vessel_voyage);$i++){
				$vessel_voyage[$i]['VESSEL_DETAIL'] = $vessel_voyage[$i]['ID_VESSEL'].' '.$vessel_voyage[$i]['VOY_IN'].'/'.$vessel_voyage[$i]['VOY_OUT'];
			}
			$data = array (
				'total'=>$total,
				'data'=>$vessel_voyage
			);
		}

		return $data;
	}

	public function get_vessel_particular(){
		$query 		= "SELECT * FROM M_VESSEL";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_vessel_particular_filtered($vessel_name){
		$query 		= "SELECT * FROM M_VESSEL WHERE VESSEL_NAME LIKE '%$vessel_name%'";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;	
	}

	public function get_vessel_code($filter=''){
		$param = array();
		$qWhere = '';

		if ($filter!=''){
			$param = array('%'.strtolower($filter).'%', '%'.strtolower($filter).'%');
			$qWhere = " WHERE LOWER(ID_VESSEL) LIKE ? OR LOWER(VESSEL_NAME) LIKE ?";
		}
		$query 		= "SELECT ID_VESSEL, VESSEL_NAME
						FROM M_VESSEL $qWhere
						ORDER BY VESSEL_NAME";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_vesselposisi_toprint($id_vessel){
		$query 		= "SELECT ID_BAY, BAY FROM M_VESSEL_PROFILE_BAY
					   WHERE TRIM(ID_VESSEL) = TRIM('$id_vessel') AND OCCUPY = 'Y'
					   ORDER BY BAY";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_vessel_schedule_list($filter){
		$query 		= "SELECT ID_VES_VOYAGE, VESSEL_NAME||' - '||VOY_IN||'/'||VOY_OUT VESSEL FROM VES_VOYAGE
					WHERE ACTIVE='Y' AND ID_TERMINAL = '".$this->gtools->terminal()."' AND 
					(LOWER(VESSEL_NAME) LIKE '%".strtolower($filter)."%'
						OR LOWER(VOY_IN) LIKE '%".strtolower($filter)."%'
						OR LOWER(VOY_OUT) LIKE '%".strtolower($filter)."%'
						OR LOWER(ID_VES_VOYAGE) LIKE '%".strtolower($filter)."%')
					ORDER BY VESSEL_NAME";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_vessel_schedule_report($filter=''){
		$param = array();
		$qWhere = '';

		if ($filter!=''){
			$param = array('%'.strtolower($filter).'%', '%'.strtolower($filter).'%', '%'.strtolower($filter).'%');
			$qWhere = " WHERE LOWER(VESSEL_NAME) LIKE ? OR LOWER(VOY_IN) LIKE ? OR LOWER(VOY_OUT) LIKE ?";
		}
		$query 		= "SELECT ID_VES_VOYAGE, VESSEL_NAME||' - '||VOY_IN||'/'||VOY_OUT as VESSEL
						FROM VES_VOYAGE $qWhere AND ID_TERMINAL = '".$this->gtools->terminal()."'
						ORDER BY VESSEL_NAME";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();

		return $data;
	}


	public function get_data_vessel(){
		$query 		= "SELECT ID_VESSEL, VESSEL_NAME FROM M_VESSEL ORDER BY ID_VESSEL";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}



	public function get_gross($E_I, $SIZE_CONT, $id_ves_voyage){
		$param = array($E_I, $SIZE_CONT, $id_ves_voyage, $this->gtools->terminal());
		$qWhere = '';
		$query ="SELECT NVL(SUM(A.GROSS),0) BERAT
		FROM ITOS_REPO.M_STEVEDORING A JOIN ITOS_REPO.M_CYC_CONTAINER B ON B.NO_CONTAINER=A.NO_CONTAINER
        JOIN VES_VOYAGE C ON C.VESSEL_NAME=A.VESSEL
        WHERE A.E_I=? AND B.SIZE_CONT=? AND C.ID_VES_VOYAGE=? AND C.ID_TERMINAL=? ";

		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();

		return $data;
	}
	public function get_total_gross($E_I, $id_ves_voyage){
		$param = array($E_I, $id_ves_voyage, $this->gtools->terminal());
		$qWhere = '';
		$query ="SELECT NVL(SUM(A.GROSS),0) BERAT
		FROM ITOS_REPO.M_STEVEDORING A JOIN ITOS_REPO.M_CYC_CONTAINER B ON B.NO_CONTAINER=A.NO_CONTAINER
        JOIN VES_VOYAGE C ON C.VESSEL_NAME=A.VESSEL
        WHERE A.E_I=? AND C.ID_VES_VOYAGE=? AND C.ID_TERMINAL=?";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_summary($E_I, $id_ves_voyage){
		// $param = array($E_I, $id_ves_voyage, $this->gtools->terminal(), $this->gtools->terminal(), $this->gtools->terminal());
		$param = array($E_I, $id_ves_voyage, $this->gtools->terminal());
		$qWhere = '';
		$query ="SELECT B.TYPE_CONT, A.STATUS_CONT, sum(case when B.size_CONT < '40' then 1 else 0 end ) BOX_20,
        sum(case when B.size_CONT >= '40' then 1 else 0 end ) BOX_40,
        sum(case when B.size_CONT < '40' then 1 else 0 end ) TEUS_20,
        sum(case when B.size_CONT >= '40' then 1*2 else 0 end ) TEUS_40,
		sum(case when B.size_CONT < '40' then 1 else 0 end ) + sum(case when B.size_CONT >= '40' then 1 else 0 end ) TOTAL_BOX,
        sum(case when B.size_CONT >= '40' then 1*2 else 0 end ) + sum(case when B.size_CONT < '40' then 1 else 0 end ) TOTAL_TEUS
        FROM ITOS_REPO.M_STEVEDORING A JOIN ITOS_REPO.M_CYC_CONTAINER B ON B.NO_CONTAINER=A.NO_CONTAINER
        JOIN VES_VOYAGE C ON C.VESSEL_NAME=A.VESSEL
        WHERE A.E_I=? AND C.ID_VES_VOYAGE=? 
        AND C.ID_TERMINAL=? 
        GROUP BY B.TYPE_CONT, A.STATUS_CONT";

		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();

		return $data;
	}
	public function get_data_dl_productivity($E_I, $id_ves_voyage){
		// $param = array($E_I, $id_ves_voyage, $this->gtools->terminal(), $this->gtools->terminal(), $this->gtools->terminal());
		$param = array($E_I, $id_ves_voyage, $this->gtools->terminal());
		$qWhere = '';
		$query 		= "SELECT
        A.NO_CONTAINER, C.VOY_IN, C.VOY_OUT, C.VESSEL_NAME, B.SIZE_CONT, B.TYPE_CONT, B.STATUS,
        A.GROSS, A.E_I, B.HZ, A.ALAT, B.FPOD, B.STW_POSITION, B.CRANE_OPR, TO_CHAR(TO_DATE(B.VESSEL_CONFIRM, 'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI:SS') VESSEL_CONFIRM
        FROM ITOS_REPO.M_STEVEDORING A JOIN ITOS_REPO.M_CYC_CONTAINER B ON B.NO_CONTAINER=A.NO_CONTAINER
        JOIN VES_VOYAGE C ON C.VESSEL_NAME=A.VESSEL
        WHERE A.E_I=? AND C.ID_VES_VOYAGE=? 
        -- AND A.ID_TERMINAL=? AND B.ID_TERMINAL=? AND C.ID_TERMINAL=?
		";
		//debux($param);die;
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_data_bch_head($id_ves_voyage)
	{
		$query = "SELECT
					V.VESSEL_NAME||' Voy.'||VOY_IN||'/'||VOY_OUT AS VESSEL_NAME,
					TO_CHAR (V.ATB, 'YYYY/MM/DD') AS BERTHING_DATE,
					TO_CHAR (V.ATB, 'HH24:MI') AS BERTHING_TIME,
					TO_CHAR (A.DISCHARGE_COMMENCE, 'YYYY/MM/DD') AS COMMENCE_DISCHARGE_DATE,
					TO_CHAR (A.DISCHARGE_COMMENCE, 'HH24:MI') AS COMMENCE_DISCHARGE_TIME,
					--TO_CHAR (A.DISCHARGE_COMPLETE, 'DD/MM/YYYY') AS COMPLETE_DISCHARGE_DATE,
					TO_CHAR (A.DISCHARGE_COMPLETE, 'YYYY/MM/DD') AS COMPLETE_DISCHARGE_DATE,
					TO_CHAR (A.DISCHARGE_COMPLETE, 'HH24:MI') AS COMPLETE_DISCHARGE_TIME,
					TO_CHAR (B.LOAD_COMMENCE, 'YYYY/MM/DD') AS COMMENCE_LOAD_DATE,
					TO_CHAR (B.LOAD_COMMENCE, 'HH24:MI') AS COMMENCE_LOAD_TIME,
					TO_CHAR (B.LOAD_COMPLETE, 'YYYY/MM/DD') AS COMPLETE_LOAD_DATE,
					TO_CHAR (B.LOAD_COMPLETE, 'HH24:MI') AS COMPLETE_LOAD_TIME,
					TO_CHAR (V.ATD, 'YYYY/MM/DD') AS ATD_DATE,
					TO_CHAR (V.ATD, 'HH24:MI') AS ATD_TIME,
					K.KADE_NAME
				FROM
					VES_VOYAGE V
				LEFT JOIN M_KADE K ON K.ID_KADE = V.ID_KADE
				LEFT JOIN (
			  		SELECT A.ID_VES_VOYAGE,MIN(COMPLETE_DATE) AS DISCHARGE_COMMENCE,MAX(COMPLETE_DATE) AS DISCHARGE_COMPLETE
					FROM JOB_QUAY_MANAGER A 
					INNER JOIN CON_INBOUND_SEQUENCE B ON A.NO_CONTAINER = B.NO_CONTAINER AND A.POINT = B.POINT AND A.ID_VES_VOYAGE = B.ID_VES_VOYAGE
					WHERE A.ID_VES_VOYAGE = '$id_ves_voyage' AND A.STATUS_FLAG = 'C'
					GROUP BY A.ID_VES_VOYAGE
			  	) A ON V.ID_VES_VOYAGE = A.ID_VES_VOYAGE
			  	LEFT JOIN (
			  		SELECT A.ID_VES_VOYAGE,MIN(COMPLETE_DATE) AS LOAD_COMMENCE,MAX(COMPLETE_DATE) AS LOAD_COMPLETE
					FROM JOB_QUAY_MANAGER A 
					INNER JOIN CON_OUTBOUND_SEQUENCE B ON A.NO_CONTAINER = B.NO_CONTAINER AND A.POINT = B.POINT AND A.ID_VES_VOYAGE = B.ID_VES_VOYAGE
					WHERE A.ID_VES_VOYAGE = '$id_ves_voyage' AND A.STATUS_FLAG = 'C'
					GROUP BY A.ID_VES_VOYAGE
			  	) B ON V.ID_VES_VOYAGE = B.ID_VES_VOYAGE
				WHERE
					V.ID_VES_VOYAGE = '".$id_ves_voyage."' AND V.ID_TERMINAL = '".$this->gtools->terminal()."'";
//				echo '<pre>'.$query.'</pre>';exit;
		return $this->db->query($query)->row();
	}

	public function get_data_bch($id_ves_voyage){
		$param = array();
		$qWhere = '';

		if ($id_ves_voyage !='' and $id_ves_voyage != "null"){
			$param = array('%'.strtolower($id_ves_voyage).'%');
			$qWhere = " and LOWER(mwp.id_ves_voyage) LIKE ? ";
		}

		$query 		= "select mwp.id_ves_voyage, vv.VESSEL_NAME, vv.VOY_IN, vv.VOY_OUT, mk.kade_name, vv.start_meter, vv.end_meter, mwp.id_machine, mwp.mch_name, TO_CHAR(mwp.start_work, 'MM-DD-YYYY HH24:MI:SS') as START_WORK, TO_CHAR(mwp.end_work, 'MM-DD-YYYY HH24:MI:SS') as END_WORK,
		(mwp.end_work - mwp.start_work) as WORK_TIME, a.IDLE, ((mwp.end_work - mwp.start_work) - (case when a.IDLE is null then 0 else a.IDLE end)) EFFECTIVE_TIME,
		b.LOAD_20_FULL, b.LOAD_20_EMPTY, b.LOAD_40_FULL, b.LOAD_40_EMPTY, b.LOAD_45_FULL, b.LOAD_45_EMPTY,
		b.DISCH_20_FULL, b.DISCH_20_EMPTY, b.DISCH_40_FULL, b.DISCH_40_EMPTY, b.DISCH_45_FULL, b.DISCH_45_EMPTY, b.DISCH_TOTAL, b.LOAD_TOTAL
		from mch_working_plan mwp
		left join ves_voyage vv on mwp.id_ves_voyage = vv.id_ves_voyage
		left join m_kade mk on vv.id_kade = mk.id_kade
		left join (
			select id_ves_voyage, id_machine, SUM((END_SUSPEND  - START_SUSPEND)) as IDLE from job_suspend
			where end_suspend is not null and ID_TERMINAL = '".$this->gtools->terminal()."'
			group by id_ves_voyage, id_machine
		) a on MWP.ID_VES_VOYAGE = a.id_ves_voyage and mwp.id_machine = a.id_machine
		left join (
			select jc.id_ves_voyage, jc.id_machine,
			sum(case when jc.activity = 'E' and clc.cont_size = '20' and clc.cont_status = 'FCL' then 1 else 0 end) as LOAD_20_FULL,
			sum(case when jc.activity = 'E' and clc.cont_size = '20' and clc.cont_status = 'MTY' then 1 else 0 end) as LOAD_20_EMPTY,
			sum(case when jc.activity = 'E' and clc.cont_size = '40' and clc.cont_status = 'FCL' then 1 else 0 end) as LOAD_40_FULL,
			sum(case when jc.activity = 'E' and clc.cont_size = '40' and clc.cont_status = 'MTY' then 1 else 0 end) as LOAD_40_EMPTY,
			sum(case when jc.activity = 'E' and clc.cont_size = '45' and clc.cont_status = 'FCL' then 1 else 0 end) as LOAD_45_FULL,
			sum(case when jc.activity = 'E' and clc.cont_size = '45' and clc.cont_status = 'MTY' then 1 else 0 end) as LOAD_45_EMPTY,
			sum(case when jc.activity = 'I' and clc.cont_size = '20' and clc.cont_status = 'FCL' then 1 else 0 end) as DISCH_20_FULL,
			sum(case when jc.activity = 'I' and clc.cont_size = '20' and clc.cont_status = 'MTY' then 1 else 0 end) as DISCH_20_EMPTY,
			sum(case when jc.activity = 'I' and clc.cont_size = '40' and clc.cont_status = 'FCL' then 1 else 0 end) as DISCH_40_FULL,
			sum(case when jc.activity = 'I' and clc.cont_size = '40' and clc.cont_status = 'MTY' then 1 else 0 end) as DISCH_40_EMPTY,
			sum(case when jc.activity = 'I' and clc.cont_size = '45' and clc.cont_status = 'FCL' then 1 else 0 end) as DISCH_45_FULL,
			sum(case when jc.activity = 'I' and clc.cont_size = '45' and clc.cont_status = 'MTY' then 1 else 0 end) as DISCH_45_EMPTY,
			sum(case when jc.activity = 'I' then 1 else 0 end) as DISCH_TOTAL, sum(case when jc.activity = 'E' then 1 else 0 end) as LOAD_TOTAL
			from job_confirm jc
			left join con_listcont clc on jc.no_container = clc.no_container and jc.point = clc.point and jc.id_ves_voyage = clc.id_ves_voyage
			where clc.ID_TERMINAL = '".$this->gtools->terminal()."' AND jc.ID_TERMINAL = '".$this->gtools->terminal()."'
			group by jc.id_ves_voyage, jc.id_machine
		) b on MWP.ID_VES_VOYAGE = b.id_ves_voyage and mwp.id_machine = b.id_machine
		where mwp.id_machine > 0 AND vv.ID_TERMINAL = '".$this->gtools->terminal()."' $qWhere
		order by mwp.id_ves_voyage, mwp.id_machine
		";

		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();

		#debux($this->db->last_query());die();

		return $data;
	}

	public function get_data_bor($start_period, $end_period){
		$query 		= "select ID_VES_VOYAGE, VESSEL_NAME, VOY_IN, VOY_OUT, TO_CHAR(ATB, 'DD-MM-YYYY HH24:MI:SS') as ATB, TO_CHAR(ATD, 'DD-MM-YYYY HH24:MI:SS') as ATD, LENGTH, ((LENGTH+RATIO)*(MIN_ATD - MAX_ATB)*24) as BERTHING from (
						select vv.ID_VES_VOYAGE, vv.VESSEL_NAME, vv.VOY_IN, vv.VOY_OUT, mv.LENGTH, mv.length*0.1 as RATIO, vv.ATB, vv.ATD,
						case when to_date('$end_period 23:59:59', 'DD-MM-YYYY HH24:MI:SS') >= vv.ATD then vv.ATD else  to_date('$end_period 23:59:59', 'DD-MM-YYYY HH24:MI:SS') end as MIN_ATD,
						case when to_date('$start_period 00:00:00', 'DD-MM-YYYY HH24:MI:SS') <= vv.ATB then vv.ATB else  to_date('$start_period 00:00:00', 'DD-MM-YYYY HH24:MI:SS') end as MAX_ATB
						from ves_voyage vv
						left join m_vessel mv on vv.id_vessel = mv.id_vessel
						where ((ATB between to_date('$start_period 00:00:00', 'DD-MM-YYYY HH24:MI:SS') and to_date('$end_period 23:59:59', 'DD-MM-YYYY HH24:MI:SS'))
						or (ATD between to_date('$start_period 00:00:00', 'DD-MM-YYYY HH24:MI:SS') and to_date('$end_period 23:59:59', 'DD-MM-YYYY HH24:MI:SS'))) and vv.ID_TERMINAL = '".$this->gtools->terminal()."'
						) a
						order by ATB";

		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_data_kade_periode($start_period, $end_period){
		$query 		= "select sum(LENGTH_PER_KADE) as NETBERTH_LENGTH, (to_date('$end_period', 'DD-MM-YYYY') - to_date('$start_period', 'DD-MM-YYYY'))*24 as period
						FROM (
						    select end_meter-start_meter as LENGTH_PER_KADE from m_kade where TERMINAL = 'IDDJB'
						) a";

		$rs 		= $this->db->query($query);
		$data 		= $rs->row_array();

		return $data;
	}

	public function get_flag_vespro($vscode){
		$query 		= "SELECT PROFILE FROM M_VESSEL WHERE TRIM(ID_VESSEL) = TRIM('$vscode')";
		$fl_vs 		= $this->db->query($query);
		$data 		= $fl_vs->result_array();

		return $data;
	}

	public function get_info_vessel($vescode){
		$query 		= "SELECT ID_VESSEL, VESSEL_NAME, OPERATOR, CALL_SIGN, VESSEL_NAME, COUNTRY_CODE, FL_SMALL_VESSEL, LENGTH, GROSS_TONAGE, NET_TONAGE, HATCH_COVER, DEPTH, MAX_DRAFT
					   FROM M_VESSEL
					   WHERE TRIM(ID_VESSEL) = TRIM('$vescode')";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_vessel_operator($idvesvoy){
		$query 		= "SELECT distinct vso.id_operator, o.operator_name
		  FROM	m_vessel_service vs
			   LEFT JOIN
				  m_vessel_service_operator vso
			   ON vs.id_vessel_service = vso.id_vessel_service
			   LEFT JOIN
				  m_operator o
			   ON vso.id_operator = o.id_operator
		 WHERE vs.id_vessel_service IN (SELECT V.IN_SERVICE service
											FROM VES_VOYAGE V
										   WHERE ID_VES_VOYAGE = '$idvesvoy' AND ID_TERMINAL='".$this->gtools->terminal()."'
										  UNION
										  SELECT V.OUT_SERVICE service
											FROM VES_VOYAGE V
										   WHERE ID_VES_VOYAGE = '$idvesvoy' AND ID_TERMINAL='".$this->gtools->terminal()."')
			  AND vso.id_operator IS NOT NULL
		 ORDER BY vso.id_operator, o.operator_name";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_vessel_port($idvesvoy){
		$query 		= "SELECT DISTINCT vsp.id_port, p.port_name
			FROM m_vessel_service vs
				 LEFT JOIN m_vessel_service_port vsp
					ON vs.id_vessel_service = vsp.id_vessel_service
				 LEFT JOIN m_port p
					ON vsp.id_port = p.port_code
		   WHERE vs.id_vessel_service IN (SELECT V.IN_SERVICE service
											FROM VES_VOYAGE V
										   WHERE ID_VES_VOYAGE = '$idvesvoy' AND ID_TERMINAL='".$this->gtools->terminal()."'
										  UNION
										  SELECT V.OUT_SERVICE service
											FROM VES_VOYAGE V
										   WHERE ID_VES_VOYAGE = '$idvesvoy' AND ID_TERMINAL='".$this->gtools->terminal()."')
				 AND vsp.id_port IS NOT NULL
		ORDER BY vsp.id_port, p.port_name";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_stevedoring_companies(){
		$query 		= "SELECT
			ID_COMPANY, ACCOUNT_NUMBER, COMPANY_NAME
			FROM ITOS_OP.M_STEVEDORING_COMPANIES
			ORDER BY COMPANY_NAME";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_ves_voyage($data){
		$query 		= "SELECT ID_VES_VOYAGE FROM VES_VOYAGE WHERE VESSEL_NAME = '".$data['VESSEL_NAME']."'
					  	AND VOY_IN 		  = '".$data['VOY_IN']."'
					  	AND VOY_OUT 	  = '".$data['VOY_OUT']."'
					  	";
		$rs 		= $this->db->query($query);
		$data 		= $rs->row();
		return $data->ID_VES_VOYAGE;
	}

	public function create_vespro($vescode,$jmlbay,$jmlrow,$jmltier_on,$jmltier_un,$jmlht,$iduser){
		$str_param = $vescode."^".$jmlbay."^".$jmlrow."^".$jmltier_on."^".$jmltier_un."^".$jmlht;
		$param = array(
			array('name'=>':v_parameter', 'value'=>$str_param, 'length'=>200),
			array('name'=>':v_msg', 'value'=>&$msg, 'length'=>50)
		);

		$sql = "BEGIN ITOS_OP.proc_create_vespro(:v_parameter,:v_msg); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);

		$queryhst 	= "INSERT INTO M_VESSEL_PROFILE_HIST
					   (ID,ID_VESSEL,STATUS,CREATED_DATE,CREATED_BY)
					   VALUES
					   (M_VESSEL_PROFILE_HIST_S.nextval,'$vescode','CREATE PROFILE',SYSDATE,'$iduser')";
		$this->db->query($queryhst);

		$queryflg 	= "UPDATE M_VESSEL SET PROFILE = 'Y' WHERE TRIM(ID_VESSEL) = TRIM('$vescode')";
		$this->db->query($queryflg);

		return $msg;
	}

	public function get_vessel_above($along_side, $id_ves_voyage){
		if ($along_side=='P'){
			$qSort = " ASC ";
		}else if ($along_side=='S'){
			$qSort = " DESC ";
		}

		$query 		= "SELECT A.ID_BAY,
							  A.BAY,
							  A.CWP_D,
							  A.CWP_DE,
							  B.ABOVE
							  , fc_getcontentbay('$id_ves_voyage', 'ABOVE', A.BAY,'I') as CONT_IMP
                              , fc_getcontentbay('$id_ves_voyage', 'ABOVE', A.BAY,'E') as CONT_EXP
					   FROM VES_VOYAGE_CWP A
						INNER JOIN M_VESSEL_PROFILE_BAY B ON A.ID_BAY=B.ID_BAY
					   WHERE TRIM(A.ID_VES_VOYAGE) = TRIM('$id_ves_voyage') AND A.ID_TERMINAL = '".$this->gtools->terminal()."' AND B.OCCUPY = 'Y'
					   ORDER BY A.ID_BAY $qSort";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_vessel_above_profile($vescode){
		$query 		= "SELECT A.ID_BAY,
							  A.BAY,
							  A.ABOVE,
							  A.JML_TIER_ON
					   FROM M_VESSEL_PROFILE_BAY A
					   WHERE TRIM(A.ID_VESSEL) = TRIM('$vescode')
					   ORDER BY A.ID_BAY ASC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_vessel_below($along_side, $id_ves_voyage){
		if ($along_side=='P'){
			$qSort = " ASC ";
		}else if ($along_side=='S'){
			$qSort = " DESC ";
		}

		$query 		= "SELECT A.ID_BAY,
							  A.BAY,
							  A.CWP_H,
							  A.CWP_HE,
							  B.BELOW, fc_getcontentbay('$id_ves_voyage', 'BELOW', A.BAY,'I') as CONT_IMP
                              , fc_getcontentbay('$id_ves_voyage', 'BELOW', A.BAY,'E') as CONT_EXP
					   FROM VES_VOYAGE_CWP A
						INNER JOIN M_VESSEL_PROFILE_BAY B
						ON A.ID_BAY=B.ID_BAY
					   WHERE TRIM(A.ID_VES_VOYAGE) = TRIM('$id_ves_voyage') AND A.ID_TERMINAL = '".$this->gtools->terminal()."' AND B.OCCUPY = 'Y'
					   ORDER BY A.ID_BAY $qSort";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_vessel_below_profile($vescode){
		$query 		= "SELECT A.ID_BAY,
							  A.BAY,
							  A.BELOW,
							  A.JML_TIER_UNDER
					   FROM M_VESSEL_PROFILE_BAY A
					   WHERE TRIM(A.ID_VESSEL) = TRIM('$vescode')
					   ORDER BY A.ID_BAY ASC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_bay_numb($along_side, $id_ves_voyage){
		if ($along_side=='P'){
			$qSort = " ASC ";
		}else if ($along_side=='S'){
			$qSort = " DESC ";
		}

		$query 		= "SELECT A.ID_BAY,
							  A.BAY
					   FROM VES_VOYAGE_CWP A
					   INNER JOIN M_VESSEL_PROFILE_BAY C ON A.ID_BAY = C.ID_BAY
					   WHERE TRIM(A.ID_VES_VOYAGE) = TRIM('$id_ves_voyage') AND A.ID_TERMINAL = '".$this->gtools->terminal()."' AND C.OCCUPY = 'Y'
					   ORDER BY A.ID_BAY $qSort";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
//		echo '<pre>'.$query.'</pre>';exit;
		return $data;
	}

	public function get_bay_numb_profile($vescode){
		$query 		= "SELECT A.ID_BAY,
							  A.BAY
					   FROM M_VESSEL_PROFILE_BAY A
					   WHERE TRIM(A.ID_VESSEL) = TRIM('$vescode')
					   ORDER BY A.ID_BAY ASC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_along_side_orientation($id_ves_voyage){
		$query = "SELECT ALONG_SIDE FROM VES_VOYAGE WHERE ID_VES_VOYAGE=? AND ID_TERMINAL=?";
		$rs = $this->db->query($query, array($id_ves_voyage,$this->gtools->terminal()));
		$data = $rs->row_array();

		return $data['ALONG_SIDE'];
	}

	public function get_bay_info($vescode){
		$query 		= "SELECT A.*, B.MAX_ABOVE_ROWS, B.MAX_ABOVE_TIERS, B.MAX_BELOW_TIERS 
						FROM M_VESSEL_PROFILE_BAY A JOIN M_VESSEL_PROFILE B ON A.ID_VESSEL=B.ID_VESSEL
					   WHERE TRIM(A.ID_VESSEL) = TRIM('$vescode') ORDER BY ID_BAY ASC";
		//debux($query);
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_max_row($vescode){
		$query 		= "SELECT MAX(JML_ROW) JML FROM M_VESSEL_PROFILE_BAY WHERE TRIM(ID_VESSEL) = TRIM('$vescode')";
		$mxrow 		= $this->db->query($query);
		$data 		= $mxrow->result_array();

		return $data;
	}

	public function get_max_tier_on($vescode){
		$query 		= "SELECT MAX(JML_TIER_ON) JML FROM M_VESSEL_PROFILE_BAY WHERE TRIM(ID_VESSEL) = TRIM('$vescode')";
		$mx_tier_on	= $this->db->query($query);
		$data 		= $mx_tier_on->result_array();

		return $data;
	}

	public function get_max_tier_under($vescode){
		$query 		= "SELECT MAX(JML_TIER_UNDER) JML FROM M_VESSEL_PROFILE_BAY WHERE TRIM(ID_VESSEL) = TRIM('$vescode')";
		$mx_tier_under	= $this->db->query($query);
		$data 		= $mx_tier_under->result_array();

		return $data;
	}

	public function get_country_list($filter){
		$query 		= "SELECT PORT_COUNTRY
						FROM M_PORT
						WHERE
							LOWER(PORT_COUNTRY) LIKE '%".strtolower($filter)."%' OR
							LOWER(PORT_NAME) LIKE '%".strtolower($filter)."%'
						GROUP BY PORT_COUNTRY";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_operator_list($filter){
		$query 		= "SELECT ID_OPERATOR,
							  ID_OPERATOR||' - '||OPERATOR_NAME OPERATOR_NAME
					   FROM M_OPERATOR
					   WHERE LOWER(OPERATOR_NAME) LIKE '%".strtolower($filter)."%'
					   OR LOWER(ID_OPERATOR) LIKE '%".strtolower($filter)."%'
					   ORDER BY OPERATOR_NAME";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_operator_name($idopr){
		$query 		= "SELECT OPERATOR_NAME
						FROM M_OPERATOR
						WHERE TRIM(ID_OPERATOR) = TRIM('$idopr')";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function insert_particular($vescode,$csg,$opr,$vesselnm,$cncode,$gross,$net_ton,$ht_ton,$lng_ton,$depth,$draft,$opr_nm){

		/*,$v_fl_small_vessel*/ // INCLUDE THIS AFTER $draft in function insert_particular( $this )

		$sql_cek = "SELECT COUNT(*) AS TOTAL
					 FROM M_VESSEL 
					 WHERE TRIM(UPPER(VESSEL_NAME)) = TRIM(UPPER('".$vesselnm."'))
					    OR TRIM(UPPER(CALL_SIGN)) = TRIM(UPPER('".$csg."'))
					    OR TRIM(UPPER(ID_VESSEL)) = TRIM(UPPER('".$vescode."'))";
		
		$row = $this->db->query($sql_cek)->row();

		if($row->TOTAL==0):
		
		$sql = "INSERT INTO M_VESSEL(
						   ID_VESSEL,
                           VESSEL_NAME,
                           OPERATOR,
                           CALL_SIGN,
                           COUNTRY_CODE,
                           GROSS_TONAGE,
                           NET_TONAGE,
                           LENGTH,
                           DEPTH,
                           MAX_DRAFT,
                           HATCH_COVER,
                           ACTIVE,
                           OPERATOR_NAME,
                           FL_SMALL_VESSEL) 
                       VALUES 
                          ('".$vescode."',
                           '".$vesselnm."',
                           '".$opr."',
                           '".$csg."',
                           '".$cncode."',
                           '".$gross."',
                           '".$net_ton."',
                           '".$lng_ton."',
                           '".$depth."',
                           '".$draft."',
                           '".$ht_ton."',
                           'Y',
                           '".$opr_nm."',
                           '')";
 		$this->db->trans_start();

        $this->db->query($sql);

        if ($this->db->trans_complete()){
			return 'Suksess';
		}else{
			return 'Gagal';
		}

		else:
			return 'Vessel Particural Exsitst';
		endif;


        //($this->db->affected_rows()==0) ? return 'Gagal' : return 'Berhasil';


		// $param = array(
		// 	array('name'=>':v_vescode', 'value'=>$vescode, 'length'=>20),
		// 	array('name'=>':v_vesselnm', 'value'=>$vesselnm, 'length'=>200),
		// 	array('name'=>':v_opr', 'value'=>$opr, 'length'=>200),
		// 	array('name'=>':v_csg', 'value'=>$csg, 'length'=>10),
		// 	array('name'=>':v_cncode', 'value'=>$cncode, 'length'=>10),
		// 	array('name'=>':v_gross', 'value'=>$gross, 'length'=>20),
		// 	array('name'=>':v_net_ton', 'value'=>$net_ton, 'length'=>20),
		// 	array('name'=>':v_lng_ton', 'value'=>$lng_ton, 'length'=>20),
		// 	array('name'=>':v_depth', 'value'=>$depth, 'length'=>20),
		// 	array('name'=>':v_draft', 'value'=>$draft, 'length'=>20),
		// 	// array('name'=>':v_fl_small_vessel', 'value'=>$v_fl_small_vessel, 'length'=>20),
		// 	array('name'=>':v_fl_small_vessel', 'value'=>'', 'length'=>20), // FL SMALL VESSEL NULL
		// 	array('name'=>':v_ht_ton', 'value'=>$ht_ton, 'length'=>20),
		// 	array('name'=>':v_opr_nm', 'value'=>$opr_nm, 'length'=>20),
		// 	array('name'=>':v_msg', 'value'=>&$MSG, 'length'=>50)
		// );
		// $this->db->trans_start();

		//debux($param);die;

		// with fl small vessel
		// $sql = "BEGIN PROC_INSERT_PARTICULAR(:v_vescode, :v_vesselnm, :v_opr, :v_csg, :v_cncode, :v_gross, :v_net_ton, :v_lng_ton, :v_depth, :v_draft, :v_ht_ton, :v_opr_nm, :v_msg, :v_fl_small_vessel); END;";

		// $sql = "BEGIN PROC_INSERT_PARTICULAR(:v_vescode, :v_vesselnm, :v_opr, :v_csg, :v_cncode, :v_gross, :v_net_ton, :v_lng_ton, :v_depth, :v_draft, :v_ht_ton, :v_opr_nm, :v_fl_small_vessel, :v_msg); END;";
		// $this->db->exec_bind_stored_procedure($sql, $param);

		// $this->db->trans_complete();

		// if($this->db->affected_rows()==0){
			//return 'SUKSESS';
		// }else{
			//return $this->db->affected_rows();
		// }

	}

	public function update_particular($vescode,$csg,$opr,$vesselnm,$cncode,$gross,$net_ton,$ht_ton,$lng_ton,$depth,$draft/*,$v_fl_small_vessel*/){
		$this->db->trans_start();

		$query 	= "UPDATE M_VESSEL
					SET
					   	VESSEL_NAME='$vesselnm',
					   	OPERATOR='$opr',
					   	CALL_SIGN='$csg',
					   	COUNTRY_CODE='$cncode',
					   	GROSS_TONAGE='$gross',
					   	NET_TONAGE='$net_ton',
					   	LENGTH='$lng_ton',
					   	DEPTH='$depth',
					   	-- FL_SMALL_VESSEL='$v_fl_small_vessel',
					   	MAX_DRAFT='$draft',
					   	HATCH_COVER='$ht_ton'
					WHERE ID_VESSEL='$vescode'";
		$this->db->query($query);

		if ($this->db->trans_complete()){
			return true;
		}else{
			return false;
		}
	}

	public function update_actual_berthing_time($data){
		$this->db->trans_start();
		$param = array(
			$data['ATA'],
			$data['ATB'],
			$data['ATD'],
			$data['LOAD_COMMENCE'],
			$data['LOAD_COMPLETE'],
			$data['DISCHARGE_COMMENCE'],
			$data['DISCHARGE_COMPLETE'],
			$data['ATB'],
			$data['ATD'],
			$data['ID_VES_VOYAGE'],
			$this->gtools->terminal()
		);
		$query 	= "UPDATE VES_VOYAGE
					SET
						ATA=TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						ATB=TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						ATD=TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						LOAD_COMMENCE=TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						LOAD_COMPLETE=TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						DISCHARGE_COMMENCE=TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						DISCHARGE_COMPLETE=TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						START_WORK=TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						END_WORK=TO_DATE(?,'DD-MM-YYYY HH24:MI')
					WHERE ID_VES_VOYAGE=? AND ID_TERMINAL=?";
		$this->db->query($query, $param);
		if ($this->db->trans_complete()){
			return true;
		}else{
			return false;
		}
	}

	public function departure_vessel_voyage($id_ves_voyage){
		$this->db->trans_start();
		$param = array($id_ves_voyage, $this->gtools->terminal());
		$param2 = array($id_ves_voyage);
		$query = "UPDATE VES_VOYAGE
					SET ACTIVE='N'
					WHERE ID_VES_VOYAGE=? AND ID_TERMINAL=?";
		$this->db->query($query, $param);

		$query = "DELETE FROM JOB_QUAY_MANAGER
					WHERE ID_VES_VOYAGE=? AND STATUS_FLAG='P' AND ID_TERMINAL=?";
		$this->db->query($query, $param);

		$query = "SELECT ID_MCH_WORKING_PLAN
					FROM MCH_WORKING_PLAN
					WHERE ID_VES_VOYAGE=?";
		$rs = $this->db->query($query, $param2);
		$data2 = $rs->result_array();
		$qwhere = '';
		foreach($data2 as $row){
			if ($qwhere!=''){
				$qwhere .= ",";
			}
			$qwhere .= $row['ID_MCH_WORKING_PLAN'];
		}

		if ($qwhere!=''){
			$query = "UPDATE MCH_WORKING_SEQUENCE
						SET ACTIVE='N'
						WHERE ID_MCH_WORKING_PLAN IN ($qwhere)";
			$this->db->query($query);
		}
		if ($this->db->trans_complete()){
			return 1;
		}else{
			return 0;
		}
	}

	public function get_kade_list($filter=''){
		$param = array();
		$qWhere = '';

		if ($filter!=''){
			$param = array('%'.strtolower($filter).'%');
			$qWhere = " WHERE LOWER(KADE_NAME) LIKE ?";
		}
		$query 		= "SELECT ID_KADE, KADE_NAME FROM M_KADE $qWhere";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_vessel_service($filter=''){
		$param = array();
		$qWhere = '';

		if ($filter!=''){
			$param = array('%'.strtolower($filter).'%');
			$qWhere = " WHERE LOWER(VESSEL_SERVICE_NAME) LIKE ?";
		}
		$query 		= "SELECT ID_VESSEL_SERVICE, VESSEL_SERVICE_NAME FROM M_VESSEL_SERVICE $qWhere
			ORDER BY VESSEL_SERVICE_NAME";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();

		return $data;
	}

	public function insert_vessel_voyage($data){
		$param = array(
			array('name'=>':id_vessel', 'value'=>$data['ID_VESSEL'], 'length'=>4),
			array('name'=>':v_terminal', 'value'=> $this->gtools->terminal(), 'length'=>4),
			array('name'=>':id_ves_voyage', 'value'=>&$ID_VES_VOYAGE, 'length'=>15),
			array('name'=>':point', 'value'=>&$POINT, 'length'=>3),
			array('name'=>':year', 'value'=>&$YEAR, 'length'=>4)
		);

		$flag = 0;
		$this->db->trans_start();

		$sql = "BEGIN PROC_GENERATE_ID_VES_VOYAGE(:id_vessel, :v_terminal, :id_ves_voyage, :point, :year); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		// print_r($param);

		$param = array(
			$ID_VES_VOYAGE,
			$data['ID_VESSEL'],
			$data['VOY_IN'],
			$data['VOY_OUT'],
			$data['VESSEL_NAME'],
			$POINT,
			$YEAR,
			$data['ID_KADE'],
			$data['ALONG_SIDE'],
			$data['START_METER'],
			$data['END_METER'],
			$data['ETA'],
			$data['ETB'],
			$data['ETD'],

			$data['CUTOFF_DATE'],
			$data['OPEN_STACK_DATE'],
			$data['IN_SERVICE'],
			$data['OUT_SERVICE'],

			$data['CUTOFF_DATE_DOC'],
			$data['FL_TONGKANG'],


			#new field
			$data['EARLY_STACK_DATE'],
			$data['BOOKING_STACK'],
			$data['APP_BOOKING_STACK'],
			$data['STV_COMPANY'],

			#update_log
			$data['CREATE_DATE'] = date('d-m-Y H:i'),
			$data['CREATE_USER'] = $this->session->userdata('id_user'),
			$data['CREATE_IP']	 = $this->input->ip_address(),
			$data['TL_RECEIVING'],
			$this->gtools->terminal(),

			#new
			$data['ATB']		 = date('d-m-Y H:i'),
			$data['START_WORK']	 = date('d-m-Y H:i'),
			$data['END_WORK']	 = date('d-m-Y H:i'),
			$data['ATA']	 	 = date('d-m-Y H:i'),
			$data['ATD']	 	 = date('d-m-Y H:i')
		);
		if ($data['EARLY_STACK_DATE'] ==''){
			unset ($param[20]);
		}
		 // echo "<pre>";print_r($param);echo "</pre>";
		$query 	= "INSERT INTO VES_VOYAGE (
					   ID_VES_VOYAGE, --1
					   ID_VESSEL, --2
					   VOY_IN, --3
					   VOY_OUT, --4
					   VESSEL_NAME, --5
					   POINT, --6
					   YEAR, --7
					   ID_KADE, --8
					   ALONG_SIDE, --9
					   START_METER, --10 
					   END_METER,  --11
					   ETA, --12
					   ETB,  --13
					   ETD, --14
					   CUTOFF_DATE, --15
					   OPEN_STACK_DATE, --16
					   IN_SERVICE, --17 
					   OUT_SERVICE, -- 18 
					   CUTOFF_DOC_DATE, --19 
					   FL_TONGKANG,";

		if ($data['EARLY_STACK_DATE'] !=''){
			$query.="EARLY_STACK_DATE,";
		}
					   
					   
					  $query.= " BOOKING_STACK,
					   APP_BOOKING_STACK, --23
					   STV_COMPANY, --24
					   CREATE_DATE, --25
					   CREATE_USER, --26
					   CREATE_IP, --, --27,
					   TL_RECEIVING,
					   ID_TERMINAL
					   --ATB, --28,
					   --START_WORK, --29,
					   --END_WORK, --30,
					   --ATA, --31
					   --ATD --31
					)
					VALUES ( 
					 ?/* 1 ID_VES_VOYAGE */,
					 ?/*2 ID_VESSEL */,
					 ?/*3 VOY_IN */,
					 ?/*4 VOY_OUT */,
					 ?/*5 VESSEL_NAME */,
					 ?/*6 POINT */,
					 ?/*7 YEAR */,
					 ?/*8 ID_KADE */,
					 ?/*9 ALONG_SIDE */,
					 ?/*10 START_METER */,
					 ?/*11 END_METER */,
					 TO_DATE(?,'DD-MM-YYYY HH24:MI')/* 12 ETA */,
					 TO_DATE(?,'DD-MM-YYYY HH24:MI')/* 13 ETB */,
					 TO_DATE(?,'DD-MM-YYYY HH24:MI')/* 14 ETD */,
					 TO_DATE(?,'DD-MM-YYYY HH24:MI')/* 15 CUTOFF_DATE */,
					 TO_DATE(?,'DD-MM-YYYY HH24:MI')/* 16 OPEN_STACK_DATE */,
					 ?/* 17 IN_SERVICE */,
					 ?/* 18 OUT_SERVICE */,

					
					 TO_DATE(?,'DD-MM-YYYY HH24:MI')/* 19 CUTOFF_DOC_DATE */,
					 ?/*20 FL_TONGKANG */,";

					 if ($data['EARLY_STACK_DATE'] !=''){
					 $query .= "TO_DATE(?,'DD-MM-YYYY HH24:MI'),";
					 }

					 $query.="?/* 22 BOOKING_STACK */,
					 ?/* 23 APP_BOOKING_STACK */,
					 ?/* 24 STV_COMPANY */,

					 TO_DATE(?,'DD-MM-YYYY HH24:MI')/* 25 CREATE_DATE */,
					 ?/* 26 CREATE_USER */,
					 ?/* 27 CREATE_IP, */,
					 ?/* TL RECIEVING */,
					 ?/* ID TERMINAL */
					 
					 /* TO_DATE(?,'DD-MM-YYYY HH24:MI') 28 ATB ,*/
					 /* TO_DATE(?,'DD-MM-YYYY HH24:MI') 29 START_WORK ,*/
					 /* TO_DATE(?,'DD-MM-YYYY HH24:MI') 30 END_WORK ,*/
					 /* TO_DATE(?,'DD-MM-YYYY HH24:MI') 31 ATA ,*/
					 /* TO_DATE(?,'DD-MM-YYYY HH24:MI') 32 ATD */
					)";
		// echo "<pre>".$query."</pre>"; exit;
		$flag = $this->db->query($query, $param);

		/*insert log ves voyage*/

		if ($flag){
			// procedure ves voyage
			$param = array(
				array('name'=>':id_vessel', 'value'=>$data['ID_VESSEL'], 'length'=>4)
			);
			$sql = "BEGIN PROC_UPCOUNT_ID_VES_VOYAGE(:id_vessel); END;";
			$this->db->exec_bind_stored_procedure($sql, $param);

			$param = array($data['ID_VESSEL']);
			$query 	= "SELECT ID_BAY, BAY
						FROM M_VESSEL_PROFILE_BAY
						WHERE ID_VESSEL=?
						ORDER BY ID_BAY";
			$rs = $this->db->query($query, $param);
			$data = $rs->result_array();
			foreach ($data as $row){
				$param = array($ID_VES_VOYAGE, $row['ID_BAY'], $row['BAY'],$this->gtools->terminal());
				$query 	= "INSERT INTO
							VES_VOYAGE_CWP
							(ID_VES_VOYAGE,ID_BAY,BAY,CWP_D,CWP_H,CWP_DE,CWP_HE,ID_TERMINAL)
							VALUES(
								?,?,?,0,0,0,0,?
							)";
				
				$this->db->query($query, $param);
//				echo '<pre>'.$this->db->last_query().'</pre>';
			}
		}	

		$this->db->trans_complete();
		return $flag;
	}

	public function update_vessel_voyage($data){

		$param = array(
			$data['VOY_IN'],
			$data['VOY_OUT'],
			$data['ID_KADE'],
			$data['ALONG_SIDE'],
			$data['START_METER'],
			$data['END_METER'],

			$data['ETA'],
			$data['ETB'],
			$data['ETD'],
			$data['CUTOFF_DATE'],
			$data['OPEN_STACK_DATE'],
			$data['IN_SERVICE'],
			$data['OUT_SERVICE'],

			$data['CUTOFF_DATE_DOC'],
			$data['FL_TONGKANG'],
			
			#new field
			$data['EARLY_STACK_DATE'],
			$data['BOOKING_STACK'],
			$data['APP_BOOKING_STACK'],
			$data['STV_COMPANY'],

			#update_log
			$data['MODIFY_DATE'] = date('d-m-Y H:i'),
			$data['MODIFY_USER'] = $this->session->userdata('id_user'),
			$data['MODIFY_IP']	 = $this->input->ip_address(),

			$data['TL_RECEIVING'],

			#id_ves_voyage
			$data['ID_VES_VOYAGE'],
			$this->gtools->terminal()

		);

		if ($data['EARLY_STACK_DATE'] ==''){
			unset ($param[15]);
		}
		#debux($param,true);
		$query 	= "UPDATE VES_VOYAGE
					SET	   VOY_IN		  	 	= ?,
						   VOY_OUT		 		= ?,
						   ID_KADE		 		= ?,
						   ALONG_SIDE	  		= ?,
						   START_METER	 		= ?,
						   END_METER	   		= ?,
						   ETA			 		= TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						   ETB			 		= TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						   ETD			 		= TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						   CUTOFF_DATE	 		= TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						   OPEN_STACK_DATE 		= TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						   IN_SERVICE	  		= ?,
						   OUT_SERVICE	 		= ?,
						   
						   CUTOFF_DOC_DATE		= TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						   FL_TONGKANG 			= ?,";


		if ($data['EARLY_STACK_DATE'] !=''){
			
						   $query.="EARLY_STACK_DATE 	= TO_DATE(?,'DD-MM-YYYY HH24:MI'),";
		}
						   
						   
						   $query.="BOOKING_STACK 		= ?,
						   APP_BOOKING_STACK 	= ?,
						   STV_COMPANY 			= ?,

						   MODIFY_DATE			= TO_DATE(?,'DD-MM-YYYY HH24:MI'),
						   MODIFY_USER			= ?,
						   MODIFY_IP			= ?,

						   TL_RECEIVING			= ?

					WHERE  ID_VES_VOYAGE   		= ? AND ID_TERMINAL = ?";
		$this->db->query($query, $param);
		#debux($this->db->last_query(),true);

		/*$id_vsb_voyage =  $this->db->query("SELECT ID_VSB_VOYAGE from ITOS_REPO.M_VSB_VOYAGE WHERE UKKS = '".$data['ID_VES_VOYAGE']."'")->row();
		$this->db->query("UPDATE ITOS_BILLING.req_receiving_h 
						  SET CLOSSING_TIME = TO_DATE('".$data['CUTOFF_DATE_DOC']."','DD-MM-YYYY HH24:MI')
						  WHERE  no_ukk='".$id_vsb_voyage->ID_VSB_VOYAGE."'");*/
		
		// insert stevedoring company
		$param_proc_stv = array(
			array('name'=>':v_id_ves_voy', 'value'=>$data['ID_VES_VOYAGE'], 'length'=>15),
			array('name'=>':v_stv_comp_dl', 'value'=>$data['STV_COMPANY_DISCHLOAD'], 'length'=>20),
			array('name'=>':v_stv_comp_lolo', 'value'=>$data['STV_COMPANY_LOLO'], 'length'=>20),
			array('name'=>':v_out', 'value'=>$v_out_proc_stv_comp, 'length'=>1000)
		);
		$sql_proc_stv = "BEGIN ITOS_OP.proc_save_stv_company(
				:v_id_ves_voy,
				:v_stv_comp_dl,
				:v_stv_comp_lolo,
				:v_out
			); END;";
		$this->db->exec_bind_stored_procedure($sql_proc_stv, $param_proc_stv);
		return true;
	}

	public function get_vessel_profile_info($id_ves_voyage){
		$param = array($id_ves_voyage,$this->gtools->terminal());
		$query	= "SELECT ID_VES_VOYAGE, ID_VESSEL, VESSEL_NAME, VOY_IN, VOY_OUT, VESSEL_NAME||' '||VOY_IN||'-'||VOY_OUT VSVY,ALONG_SIDE
					FROM VES_VOYAGE WHERE ID_VES_VOYAGE=? AND ID_TERMINAL=?";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row_array();

		return $data;
	}

	public function getMaxRowProfile($idx)
	{
		$query="select maX(JML_ROW) MAXROW , max(JML_TIER_UNDER+JML_TIER_ON)+2 AS MAXTIER from M_VESSEL_PROFILE_BAY WHERE ID_VESSEL='$idx'";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row_array();

		return $data;
	}


	public function get_vessel_profile_cellInfo($id_ves_voyage, $class_code, $id_vessel, $id_bay, $bay, $pos){
		$class_code_str = '';
		$qWhere = '';
		if($pos == 'ABOVE'){
		    $addWhere = " AND A.ABOVE = 'AKTIF'";
		}else{
		    $addWhere = " AND A.BELOW = 'AKTIF'";
		}
		if ($class_code=='I'){
			if ($bay % 2 != 0){
				$qWhere = ' OR (C.VS_BAY-1) = A.BAY OR (C.VS_BAY+1) = A.BAY ';
			}
			$param = array($id_ves_voyage, $id_vessel, $id_bay, $pos);

			//debux($param);die;
			$query = "SELECT B.*, A.BAY, A.JML_ROW, C.NO_CONTAINER, C.POINT, C.ID_CLASS_CODE, C.CONT_SIZE, C.ID_COMMODITY, D.SEQUENCE, D.STATUS, DECODE(A.BAY,C.VS_BAY-1,'FORE',C.VS_BAY+1,'AFTER','') CONT_40_LOCATION,C.TL_FLAG,E.FOREGROUND_COLOR,E.BACKGROUND_COLOR,C.ID_COMMODITY,C.HAZARD, ROUND( C.WEIGHT / 1000 ) WEIGHT, C.ID_OPERATOR,C.ID_POD 
						FROM
							M_VESSEL_PROFILE_CELL B
							LEFT JOIN M_VESSEL_PROFILE_BAY A
							ON A.ID_BAY = B.ID_BAY
							LEFT JOIN (SELECT * FROM CON_LISTCONT C 
										WHERE C.ID_VES_VOYAGE = ? AND C.ID_CLASS_CODE IN ('I', 'TI', 'TC','S1','S2') AND C.ID_OP_STATUS <> 'DIS' 
										";
			if($this->gtools->terminal() != ''){
			    $query .= "AND C.ID_TERMINAL = ".$this->gtools->terminal();
			}				
			$query .= "		 AND CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
									
			 	 THEN C.POINT ELSE 1 END = CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
			 			THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) ELSE 1 END) C
							ON (C.VS_BAY = A.BAY
							$qWhere
							) AND C.VS_ROW = B.ROW_ AND C.VS_TIER = B.TIER_ ";
			$query .= "			LEFT JOIN CON_INBOUND_SEQUENCE D
							ON D.NO_CONTAINER = C.NO_CONTAINER AND D.POINT = C.POINT
							LEFT JOIN M_PORT E
							ON C.ID_POD = E.PORT_CODE
						WHERE B.STATUS_STACK <> 'N' AND A.ID_VESSEL = ? AND A.ID_BAY = ? AND B.POSISI_STACK = ? $addWhere
					ORDER BY B.ID_CELL ASC";
		}else if ($class_code=='E'){
			if ($bay % 2 != 0){
				$qWhere = ' OR (C.P_BAY-1) = A.BAY OR (C.P_BAY+1) = A.BAY ';
			}
			$param = array($id_ves_voyage, $id_vessel, $id_bay, $pos);
			// var_dump($param);
			$query = "SELECT B.*, A.BAY, A.JML_ROW, C.NO_CONTAINER, C.POINT, C.ID_CLASS_CODE,C.ID_ISO_CODE, C.CONT_SIZE, C.CONT_TYPE, C.CONT_HEIGHT, C.SEQUENCE, C.HAS_JOB_SHIFTING,
				C.STATUS, DECODE(A.BAY,C.P_BAY-1,'FORE',C.P_BAY+1,'AFTER','') CONT_40_LOCATION, C.ID_COMMODITY, C.TL_FLAG,C.ITT_FLAG, C.HAZARD,C.ID_POL,C.CONT_STATUS,
				C.ID_POD, C.POD_COLOR, C.FOREGROUND_COLOR,C.BACKGROUND_COLOR, C.ID_OPERATOR, C.OPR_COLOR, ROUND( C.WEIGHT / 1000 ) WEIGHT,C.YD_LOCATION
					FROM
						M_VESSEL_PROFILE_CELL B
						LEFT JOIN M_VESSEL_PROFILE_BAY A
						ON A.ID_BAY = B.ID_BAY
						LEFT JOIN (
							SELECT CT.NO_CONTAINER,
							   CT.POINT,
							   CT.ID_CLASS_CODE,
							   CT.ID_ISO_CODE,
							   CT.CONT_TYPE,
							   CT.CONT_SIZE,
							   CT.CONT_HEIGHT,
							   CT.CONT_STATUS,
							   CT.HAZARD,
							   CT.ID_COMMODITY,
							   CT.TL_FLAG,
							   CT.ITT_FLAG,
							   CASE WHEN (CT.ID_CLASS_CODE = 'S1' OR CT.ID_CLASS_CODE = 'S2') THEN DECODE(CS.BAY_,'',CT.VS_BAY_TO,CS.BAY_) ELSE DECODE(CT.VS_BAY,'',CS.BAY_,CT.VS_BAY) END AS P_BAY,
							   CASE WHEN (CT.ID_CLASS_CODE = 'S1' OR CT.ID_CLASS_CODE = 'S2') THEN DECODE(CS.ROW_,'',CT.VS_ROW_TO,CS.ROW_) ELSE DECODE(CT.VS_ROW,'',CS.ROW_,CT.VS_ROW) END AS P_ROW,
							   CASE WHEN (CT.ID_CLASS_CODE = 'S1' OR CT.ID_CLASS_CODE = 'S2') THEN DECODE(CS.TIER_,'',CT.VS_TIER_TO,CS.TIER_) ELSE DECODE(CT.VS_TIER,'',CS.TIER_,CT.VS_TIER) END AS P_TIER,
							   (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = CT.ID_VES_VOYAGE AND NO_CONTAINER = CT.NO_CONTAINER) AS HAS_JOB_SHIFTING,
							   CS.SEQUENCE,
							   CS.STATUS,
							   CT.ID_POL,
							   CT.ID_POD,
							   E.FOREGROUND_COLOR,
							   E.BACKGROUND_COLOR,
							   (select fc_col_vssvc_port(CT.ID_VES_VOYAGE, CT.ID_POD) from dual) POD_COLOR,
							   CT.ID_OPERATOR,
							   (select fc_col_vssvc_operator(CT.ID_VES_VOYAGE, CT.ID_OPERATOR) from dual) OPR_COLOR,
							   CT.WEIGHT,
							   CT.YD_BLOCK_NAME||' '||CT.YD_SLOT||'-'||CT.YD_ROW||'-'||CT.YD_TIER YD_LOCATION
							FROM CON_LISTCONT CT 
							LEFT JOIN CON_OUTBOUND_SEQUENCE CS ON CT.NO_CONTAINER = CS.NO_CONTAINER AND CT.POINT = CS.POINT
							LEFT JOIN M_PORT E ON E.PORT_CODE = CT.ID_POD
							WHERE CT.ID_VES_VOYAGE = ? AND ID_CLASS_CODE IN ('E', 'TE', 'TC','S1','S2') AND CT.ID_OP_STATUS <> 'DIS'";
					    if($this->gtools->terminal() != ''){
						$query .= "AND CT.ID_TERMINAL = ".$this->gtools->terminal();
					    }
					    $query .= "	AND CASE WHEN (CT.ID_CLASS_CODE = 'S1' OR CT.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = CT.ID_VES_VOYAGE AND NO_CONTAINER = CT.NO_CONTAINER) > 0 
							THEN CT.POINT ELSE 1 END <> CASE WHEN (CT.ID_CLASS_CODE = 'S1' OR CT.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = CT.ID_VES_VOYAGE AND NO_CONTAINER = CT.NO_CONTAINER) > 0 
								       THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = CT.ID_VES_VOYAGE AND NO_CONTAINER = CT.NO_CONTAINER) ELSE 0 END
							       ) C
						ON (C.P_BAY = A.BAY
							$qWhere
						) AND C.P_ROW = B.ROW_ AND C.P_TIER = B.TIER_
					WHERE B.STATUS_STACK <> 'N' AND A.ID_VESSEL = ? AND A.ID_BAY = ? AND B.POSISI_STACK = ? $addWhere
				ORDER BY B.ID_CELL ASC";
				// echo $query;
		}
		//debux($param);die;
		//debux($query);die;
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		//debux($data);die;
//		if((($bay == 8 || $bay == 9) && $pos == 'BELOW')){
//		    echo '<pre>'.$this->db->last_query(),'</pre>';die;
//		}
		return $data;
	}

	public function get_vessel_profile_cellInfo1($id_ves_voyage, $class_code, $id_vessel, $id_bay, $bay, $pos){
		$class_code_str = '';
		$qWhere = '';
		if($pos == 'ABOVE'){
		    $addWhere = " AND A.ABOVE = 'AKTIF'";
		}else{
		    $addWhere = " AND A.BELOW = 'AKTIF'";
		}
		if ($class_code=='I'){
			if ($bay % 2 != 0){
				$qWhere = ' OR (C.VS_BAY-1) = A.BAY OR (C.VS_BAY+1) = A.BAY ';
			}
			$param = array($id_ves_voyage, $id_vessel, $id_bay, $pos);

			//debux($param);die;
			$query = "SELECT CONT_TYPE, B.*, A.BAY, A.JML_ROW, C.NO_CONTAINER, C.POINT, C.ID_CLASS_CODE, C.CONT_SIZE, C.ID_COMMODITY, D.SEQUENCE, D.STATUS, DECODE(A.BAY,C.VS_BAY-1,'FORE',C.VS_BAY+1,'AFTER','') CONT_40_LOCATION,C.TL_FLAG,E.FOREGROUND_COLOR,E.BACKGROUND_COLOR,C.ID_COMMODITY,C.HAZARD, ROUND( C.WEIGHT / 1000 ) WEIGHT, C.ID_OPERATOR,C.ID_POD 
						FROM
							M_VESSEL_PROFILE_CELL B
							LEFT JOIN M_VESSEL_PROFILE_BAY A
							ON A.ID_BAY = B.ID_BAY
							LEFT JOIN (SELECT * FROM CON_LISTCONT C 
										WHERE C.ID_VES_VOYAGE = ? AND C.ID_CLASS_CODE IN ('I', 'TI', 'TC','S1','S2') AND C.ID_OP_STATUS <> 'DIS' 
										";
			if($this->gtools->terminal() != ''){
			    $query .= "AND C.ID_TERMINAL = ".$this->gtools->terminal();
			}				
			$query .= "		 AND CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
									
			 	 THEN C.POINT ELSE 1 END = CASE WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 
			 			THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) ELSE 1 END) C
							ON (C.VS_BAY = A.BAY
							$qWhere
							) AND C.VS_ROW = B.ROW_ AND C.VS_TIER = B.TIER_ ";
			$query .= "			LEFT JOIN CON_INBOUND_SEQUENCE D
							ON D.NO_CONTAINER = C.NO_CONTAINER AND D.POINT = C.POINT
							LEFT JOIN M_PORT E
							ON C.ID_POD = E.PORT_CODE
						WHERE B.STATUS_STACK NOT IN ('N', 'X') AND A.ID_VESSEL = ? AND A.ID_BAY = ? AND B.POSISI_STACK = ? $addWhere
					ORDER BY B.ID_CELL ASC";
		}else if ($class_code=='E'){
			if ($bay % 2 != 0){
				$qWhere = ' OR (C.P_BAY-1) = A.BAY OR (C.P_BAY+1) = A.BAY ';
			}
			$param = array($id_ves_voyage, $id_vessel, $id_bay, $pos);
			// var_dump($param);
			$query = "SELECT B.*, A.BAY, A.JML_ROW, C.NO_CONTAINER, C.POINT, C.ID_CLASS_CODE,C.ID_ISO_CODE, C.CONT_SIZE, C.CONT_TYPE, C.CONT_HEIGHT, C.SEQUENCE, C.HAS_JOB_SHIFTING,
				C.STATUS, DECODE(A.BAY,C.P_BAY-1,'FORE',C.P_BAY+1,'AFTER','') CONT_40_LOCATION, C.ID_COMMODITY, C.TL_FLAG,C.ITT_FLAG, C.HAZARD,C.ID_POL,C.CONT_STATUS,
				C.ID_POD, C.POD_COLOR, C.FOREGROUND_COLOR,C.BACKGROUND_COLOR, C.ID_OPERATOR, C.OPR_COLOR, ROUND( C.WEIGHT / 1000 ) WEIGHT,C.YD_LOCATION
					FROM
						M_VESSEL_PROFILE_CELL B
						LEFT JOIN M_VESSEL_PROFILE_BAY A
						ON A.ID_BAY = B.ID_BAY
						LEFT JOIN (
							SELECT CT.NO_CONTAINER,
							   CT.POINT,
							   CT.ID_CLASS_CODE,
							   CT.ID_ISO_CODE,
							   CT.CONT_TYPE,
							   CT.CONT_SIZE,
							   CT.CONT_HEIGHT,
							   CT.CONT_STATUS,
							   CT.HAZARD,
							   CT.ID_COMMODITY,
							   CT.TL_FLAG,
							   CT.ITT_FLAG,
							   CASE WHEN (CT.ID_CLASS_CODE = 'S1' OR CT.ID_CLASS_CODE = 'S2') THEN DECODE(CS.BAY_,'',CT.VS_BAY_TO,CS.BAY_) ELSE DECODE(CT.VS_BAY,'',CS.BAY_,CT.VS_BAY) END AS P_BAY,
							   CASE WHEN (CT.ID_CLASS_CODE = 'S1' OR CT.ID_CLASS_CODE = 'S2') THEN DECODE(CS.ROW_,'',CT.VS_ROW_TO,CS.ROW_) ELSE DECODE(CT.VS_ROW,'',CS.ROW_,CT.VS_ROW) END AS P_ROW,
							   CASE WHEN (CT.ID_CLASS_CODE = 'S1' OR CT.ID_CLASS_CODE = 'S2') THEN DECODE(CS.TIER_,'',CT.VS_TIER_TO,CS.TIER_) ELSE DECODE(CT.VS_TIER,'',CS.TIER_,CT.VS_TIER) END AS P_TIER,
							   (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = CT.ID_VES_VOYAGE AND NO_CONTAINER = CT.NO_CONTAINER) AS HAS_JOB_SHIFTING,
							   CS.SEQUENCE,
							   CS.STATUS,
							   CT.ID_POL,
							   CT.ID_POD,
							   E.FOREGROUND_COLOR,
							   E.BACKGROUND_COLOR,
							   (select fc_col_vssvc_port(CT.ID_VES_VOYAGE, CT.ID_POD) from dual) POD_COLOR,
							   CT.ID_OPERATOR,
							   (select fc_col_vssvc_operator(CT.ID_VES_VOYAGE, CT.ID_OPERATOR) from dual) OPR_COLOR,
							   CT.WEIGHT,
							   CT.YD_BLOCK_NAME||' '||CT.YD_SLOT||'-'||CT.YD_ROW||'-'||CT.YD_TIER YD_LOCATION
							FROM CON_LISTCONT CT 
							LEFT JOIN CON_OUTBOUND_SEQUENCE CS ON CT.NO_CONTAINER = CS.NO_CONTAINER AND CT.POINT = CS.POINT
							LEFT JOIN M_PORT E ON E.PORT_CODE = CT.ID_POD
							WHERE CT.ID_VES_VOYAGE = ? AND ID_CLASS_CODE IN ('E', 'TE', 'TC','S1','S2') AND CT.ID_OP_STATUS <> 'DIS'";
					    if($this->gtools->terminal() != ''){
						$query .= "AND CT.ID_TERMINAL = ".$this->gtools->terminal();
					    }
					    $query .= "	AND CASE WHEN (CT.ID_CLASS_CODE = 'S1' OR CT.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = CT.ID_VES_VOYAGE AND NO_CONTAINER = CT.NO_CONTAINER) > 0 
							THEN CT.POINT ELSE 1 END <> CASE WHEN (CT.ID_CLASS_CODE = 'S1' OR CT.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = CT.ID_VES_VOYAGE AND NO_CONTAINER = CT.NO_CONTAINER) > 0 
								       THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = CT.ID_VES_VOYAGE AND NO_CONTAINER = CT.NO_CONTAINER) ELSE 0 END
							       ) C
						ON (C.P_BAY = A.BAY
							$qWhere
						) AND C.P_ROW = B.ROW_ AND C.P_TIER = B.TIER_
					WHERE B.STATUS_STACK NOT IN ('N', 'X') AND A.ID_VESSEL = ? AND A.ID_BAY = ? AND B.POSISI_STACK = ? $addWhere
				ORDER BY B.ID_CELL ASC";
				// echo $query;
		}
		//debux($param);die;
		//debux($query);die;
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		//		if((($bay == 8 || $bay == 9) && $pos == 'BELOW')){
		//		    echo '<pre>'.$this->db->last_query(),'</pre>';die;
		//		}
		return $data;
	}

	public function get_count_row_and_tier($id_vessel, $id_bay,$pos,$row){
		$param = array($id_vessel, $id_bay, $pos, $row);
		if($pos == 'ABOVE'){
		    $addWhere = " AND A.ABOVE = 'AKTIF'";
		}else{
		    $addWhere = " AND A.BELOW = 'AKTIF'";
		}

		$query = "SELECT count(*) AS JML_ROW, JML_TIER_UNDER FROM
		M_VESSEL_PROFILE_CELL B LEFT JOIN M_VESSEL_PROFILE_BAY A 
		ON A.ID_BAY = B.ID_BAY 
		WHERE B.STATUS_STACK <> 'N' 
		AND A.ID_VESSEL = ? 
		AND A.ID_BAY = ? 
		AND B.POSISI_STACK = ? 
		AND ROW_ = ? 
		AND STATUS_STACK <> 'A' 
		$addWhere
		GROUP BY JML_TIER_UNDER";

		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row();
		return $data;
	}


	public function get_count_row($id_vessel, $id_bay,$pos){
		$param = array($id_vessel, $id_bay, $pos);
		if($pos == 'ABOVE'){
		    $addWhere = " AND A.ABOVE = 'AKTIF'";
		}else{
		    $addWhere = " AND A.BELOW = 'AKTIF'";
		}

		$query = "SELECT count(DISTINCT ROW_) AS JML_ROW, JML_TIER_UNDER FROM
		M_VESSEL_PROFILE_CELL B LEFT JOIN M_VESSEL_PROFILE_BAY A 
		ON A.ID_BAY = B.ID_BAY 
		WHERE B.STATUS_STACK <> 'N' 
		AND A.ID_VESSEL = ? 
		AND A.ID_BAY = ? 
		AND B.POSISI_STACK = ? 
		AND STATUS_STACK = 'A' 
		$addWhere
		GROUP BY JML_TIER_UNDER";

		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row();
		return $data;
	}

	public function get_count_row1($id_vessel, $id_bay,$pos){
		$param = array($id_vessel, $id_bay, $pos);
		if($pos == 'ABOVE'){
		    $addWhere = " AND A.ABOVE = 'AKTIF'";
		}else{
		    $addWhere = " AND A.BELOW = 'AKTIF'";
		}

		$query = "SELECT count(DISTINCT ROW_) AS JML_ROW, JML_TIER_UNDER FROM
		M_VESSEL_PROFILE_CELL B LEFT JOIN M_VESSEL_PROFILE_BAY A 
		ON A.ID_BAY = B.ID_BAY 
		WHERE B.STATUS_STACK <> 'N' 
		AND A.ID_VESSEL = ? 
		AND A.ID_BAY = ? 
		AND B.POSISI_STACK = ? 
		AND STATUS_STACK = 'A' 
		$addWhere
		GROUP BY JML_TIER_UNDER";

		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row();
		return $data;
	}

	public function get_vessel_profile_bayArea($id_vessel, $id_bay=false){
		$param = array($id_vessel);
		if ($id_bay){
			$qWhere = " AND ID_BAY=? ";
			array_push($param, $id_bay);
		}
		$query	= " SELECT * FROM
					M_VESSEL_PROFILE_BAY
					WHERE ID_VESSEL=? AND OCCUPY='Y' $qWhere
					ORDER BY BAY";

		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_vessel_profile_bayAreaNew($id_vessel){

		$query	= " select A.BAY,A.HATCH_NUMBER,c.BAY AS BAYGENAP, CASE WHEN A.JML_ROW>NVL(C.JML_ROW,0) THEN A.JML_ROW ELSE C.JML_ROW END AS MAX_ROW,
					CASE WHEN A.JML_TIER_UNDER>NVL(C.JML_TIER_UNDER,0) THEN A.JML_TIER_UNDER ELSE C.JML_TIER_UNDER END AS MAX_TIERUNDER,
					CASE WHEN A.JML_TIER_ON>NVL(C.JML_TIER_ON,0) THEN A.JML_TIER_ON ELSE C.JML_TIER_ON END AS MAX_TIERON,
					a.ID_BAY, c.ID_BAY AS ID_BAY_GENAP
					FROM M_VESSEL_PROFILE_BAY A
					left join
					(select B.ID_BAY,b.BAY, b.JML_ROW, b.JML_TIER_UNDER, b.JML_TIER_ON, b.OCCUPY FROM M_VESSEL_PROFILE_BAY b
					WHERE b.ID_VESSEL='$id_vessel' AND b.OCCUPY='Y' AND MOD(b.BAY,2)=0) c on C.BAY+1=A.BAY
					WHERE A.ID_VESSEL='$id_vessel' AND A.OCCUPY='Y' AND MOD(A.BAY,2)!=0 order by A.BAY";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_vesselBaySum($idvesvoy,$bay,$abvBlw,$ei,$sz){
		if($sz == '20'){
			$size = "('20','21')";
		}else{
			$size = "('40','45')";
		}

		if($abvBlw=='ABOVE')
		{
			$statementwhere=" and to_number(vs_tier)>=80";
		}
		else
		{
			$statementwhere=" and to_number(vs_tier)<80";
		}

		if(($bay%2)==0)
		{
			$bay2=$bay+1;
			$statementbay="('$bay','$bay2')";
		}
		else{
			$statementbay="('$bay')";
		}



		if($ei=='I')
		{
			/*$smp="'I','TI','TC','S1','S2'";
			$dmp="";*/

			$query = "SELECT
						COUNT(*) AS JML
					FROM
						M_VESSEL_PROFILE_CELL a
					JOIN m_vessel_profile_bay e ON A.ID_VESSEL = E.ID_VESSEL AND A.ID_BAY = E.ID_BAY
					LEFT JOIN (
						SELECT
							B.no_container,
							b.ID_POD,
							b.ID_COMMODITY,
							d.id_vessel,
							b.CONT_SIZE,
							CASE
								WHEN MOD(NVL(B.VS_BAY, E.BAY_),
								2) = 0 THEN NVL(B.VS_BAY, E.BAY_)+ 1
								ELSE NVL(B.VS_BAY, E.BAY_)
							END VSB_BAY,
							NVL(B.VS_ROW, E.ROW_) VS_ROW,
							NVL(B.VS_TIER, E.TIER_) VS_TIER,
							B.ID_CLASS_CODE,
							b.CONT_STATUS,
							b.CONT_TYPE,
							b.HAZARD,
							b.IMDG,
							b.TL_FLAG,
							ROUND((b.WEIGHT/1000),1) as WEIGHT,
							M.FOREGROUND_COLOR,
							b.ID_OPERATOR
						FROM con_listcont b
						JOIN ves_voyage d ON B.ID_VES_VOYAGE = D.ID_VES_VOYAGE
						LEFT JOIN con_inbound_sequence E ON B.NO_CONTAINER = E.NO_CONTAINER AND B.POINT = E.POINT
						LEFT JOIN M_PORT M ON M.PORT_CODE = B.ID_POD
						WHERE
							B.id_ves_voyage = '$idvesvoy'
							AND b.ID_TERMINAL = ".$this->gtools->terminal()."
							AND b.id_class_code IN ('I','TI','TC','S1','S2')
							AND B.ID_OP_STATUS <> 'DIS'
							AND
							CASE
								WHEN (B.ID_CLASS_CODE = 'S1' OR B.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = B.ID_VES_VOYAGE AND NO_CONTAINER = B.NO_CONTAINER) > 0 
								THEN B.POINT
								ELSE 1
							END =
							CASE
								WHEN (B.ID_CLASS_CODE = 'S1' OR B.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = B.ID_VES_VOYAGE AND NO_CONTAINER = B.NO_CONTAINER) > 0
								THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = B.ID_VES_VOYAGE AND NO_CONTAINER = B.NO_CONTAINER)
								ELSE 1
							END ) c ON
						a.ID_VESSEL = c.ID_VESSEL
						AND E.BAY = c.vsb_bay
						AND A.ROW_ = c.vs_row
						AND A.TIER_ = c.vs_tier
					WHERE
						a.ID_VESSEL = c.ID_VESSEL
						AND a.POSISI_STACK = '$abvBlw'
						AND a.STATUS_STACK != 'N'
						AND e.BAY in $statementbay
						AND E.OCCUPY = 'Y'
						AND C.CONT_SIZE IN $size
						AND a.tier_ IN ( SELECT z.tier_
									FROM M_VESSEL_PROFILE_CELL z
									WHERE 1=1  
									--AND z.status_stack NOT IN ('X','N') 
									AND z.ID_VESSEL = a.id_vessel AND z.id_bay = a.id_bay
									GROUP BY z.tier_)
						ORDER BY a.cell_number";
		}
		else
		{
			/*$smp="'E','TE','TC','S1','S2'";
			$dmp=" and ID_OP_STATUS IN ('SLY','SLG')";*/

			$query = "SELECT
							COUNT(1) AS JML
						FROM
							M_VESSEL_PROFILE_CELL a
						JOIN m_vessel_profile_bay e ON A.ID_VESSEL = E.ID_VESSEL AND A.ID_BAY = E.ID_BAY
						LEFT JOIN (
							SELECT
								B.no_container,
								b.ID_POD,
								b.ID_COMMODITY,
								d.id_vessel,
								b.CONT_SIZE,
								CASE
									WHEN MOD(NVL(B.VS_BAY, E.BAY_),2) = 0 THEN NVL(B.VS_BAY, E.BAY_)+ 1
									ELSE NVL(B.VS_BAY, E.BAY_)
								END VSB_BAY,
								NVL(B.VS_ROW, E.ROW_) VS_ROW,
								NVL(B.VS_TIER, E.TIER_) VS_TIER,
								B.ID_CLASS_CODE,
								b.CONT_STATUS,
								b.CONT_TYPE,
								b.HAZARD,
								b.IMDG,
								b.TL_FLAG,
								ROUND((b.WEIGHT/1000),1) as WEIGHT,
								M.FOREGROUND_COLOR,
								b.ID_OPERATOR
							FROM
								con_listcont b
							JOIN ves_voyage d ON B.ID_VES_VOYAGE = D.ID_VES_VOYAGE
							LEFT JOIN con_outbound_sequence E ON B.NO_CONTAINER = E.NO_CONTAINER AND B.POINT = E.POINT
							LEFT JOIN M_PORT M ON M.PORT_CODE = B.ID_POD
							WHERE
								B.id_ves_voyage = '$idvesvoy'
								AND b.ID_TERMINAL = ".$this->gtools->terminal()."
								AND b.id_class_code IN ('E','TE','TC','S1','S2')
								AND B.ID_OP_STATUS <> 'DIS'
								AND
									CASE
										WHEN (B.ID_CLASS_CODE = 'S1' OR B.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = B.ID_VES_VOYAGE AND NO_CONTAINER = B.NO_CONTAINER) > 0 THEN B.POINT
										ELSE 1
									END <>
									CASE
										WHEN (B.ID_CLASS_CODE = 'S1' OR B.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = B.ID_VES_VOYAGE AND NO_CONTAINER = B.NO_CONTAINER) > 0
										THEN (SELECT MAX(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = B.ID_VES_VOYAGE AND NO_CONTAINER = B.NO_CONTAINER)
										ELSE 0
									END
						) c ON a.ID_VESSEL = c.ID_VESSEL AND E.BAY = c.vsb_bay AND A.ROW_ = c.vs_row AND A.TIER_ = c.vs_tier
						WHERE
							a.ID_VESSEL = c.ID_VESSEL
							AND a.POSISI_STACK = '$abvBlw'
							AND a.STATUS_STACK != 'N'
							AND e.BAY in $statementbay
							AND E.OCCUPY = 'Y'
							AND C.CONT_SIZE IN $size
							AND a.tier_ IN ( SELECT z.tier_
										FROM M_VESSEL_PROFILE_CELL z
										WHERE 1=1  
										AND z.ID_VESSEL = a.id_vessel AND z.id_bay = a.id_bay
										GROUP BY z.tier_)
							ORDER BY a.cell_number";
		}

		
		//$query	= "SELECT count(1) AS JML from con_listcont where id_ves_voyage='$idvesvoy' and vs_bay in ($statementbay) and cont_size='$sz' and ID_TERMINAL='".$this->gtools->terminal()."' and id_class_code IN ($smp) $dmp $statementwhere";


		//debux($query);die;
		$rs 		= $this->db->query($query);
		$data 		= $rs->row_array();

		return $data;
	}

	public function get_cellPerBayVesselAbv($id_vessel,$idvesvoy,$bay,$abvBlw,$ei){
		//echo $bay;

		$q_add = ($abvBlw == 'ABOVE') ? "AND E.ABOVE = 'AKTIF'" : "AND E.BELOW = 'AKTIF'";


		if($ei=='I')
		{
			// $smp="'I','TI','TC','S1','S2'";
			// $dmp="";
			// $xmp="con_inbound_sequence";
			/*
			$query	= "SELECT
							B.*, A.BAY, A.JML_ROW, c.NO_CONTAINER,
							SUBSTR(c.NO_CONTAINER, 0, 4) no_container_h,
							SUBSTR(c.NO_CONTAINER, 5, 7) no_container_num,
							c.ID_POD,
							(SELECT f.CONT_SIZE
								FROM con_listcont f
								WHERE f.vs_bay =(TO_NUMBER(a.BAY)+ 1) AND f.vs_row = b.ROW_ AND f.vs_tier = b.TIER_
								AND f.id_class_code IN ('I','TI','TC','S1','S2')
								AND f.id_ves_voyage = '$id_vessel'
								AND rownum = 1) AS FUTURE40,
							C.ID_CLASS_CODE,
							c.CONT_SIZE,
							c.ID_COMMODITY,
							C.CONT_STATUS,
							C.CONT_TYPE,
							c.HAZARD,
							c.IMDG,
							C.TL_FLAG,
							C.WEIGHT,
							E.FOREGROUND_COLOR
						FROM
							M_VESSEL_PROFILE_CELL B
						JOIN M_VESSEL_PROFILE_BAY A ON A.ID_VESSEL = B.ID_VESSEL AND A.ID_BAY = B.ID_BAY
						LEFT JOIN ( SELECT * FROM CON_LISTCONT C
							WHERE
								C.ID_VES_VOYAGE = '$idvesvoy'
								AND C.ID_CLASS_CODE IN ('I','TI','TC','S1','S2')
								AND C.ID_OP_STATUS <> 'DIS'
								AND C.ID_TERMINAL = ".$this->gtools->terminal()."
								AND CASE
										WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
										THEN C.POINT
										ELSE 1
									END =
									CASE
										WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
										THEN (SELECT POINT FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER)
										ELSE 1
									END 
						) C ON (C.VS_BAY = A.BAY OR (C.VS_BAY-1) = A.BAY OR (C.VS_BAY + 1) = A.BAY ) AND C.VS_ROW = B.ROW_ AND C.VS_TIER = B.TIER_
						LEFT JOIN CON_INBOUND_SEQUENCE D ON D.NO_CONTAINER = C.NO_CONTAINER AND D.POINT = C.POINT
						LEFT JOIN M_PORT E ON C.ID_POD = E.PORT_CODE
						WHERE
							B.STATUS_STACK <> 'N'
							AND A.ID_VESSEL = '$id_vessel'
							AND A.BAY = '$bay'
							AND B.POSISI_STACK = '$abvBlw'
						ORDER BY
							B.ID_CELL";
			*/
			$query = "SELECT
						a.*, E.BAY, c.CONT_SIZE, c.VSB_BAY, c.VS_ROW,
						c.VS_TIER, c.NO_CONTAINER,
						SUBSTR(c.NO_CONTAINER, 0, 4) no_container_h,
						SUBSTR(c.NO_CONTAINER, 5, 7) no_container_num,
						c.ID_POD,
						(SELECT f.CONT_SIZE
						FROM con_listcont f
						WHERE
							f.vs_bay =(TO_NUMBER(e.BAY)+ 1)
							AND f.vs_row = a.ROW_
							AND f.vs_tier = a.TIER_
							AND f.id_class_code IN ('I','TI','TC','S1','S2')
							AND f.id_ves_voyage = '$idvesvoy'
							AND rownum = 1) AS FUTURE40,
						C.ID_CLASS_CODE,
						C.CONT_STATUS ,
						C.CONT_TYPE,
						c.HAZARD,
						c.IMDG,
						C.TL_FLAG,
						c.ID_COMMODITY,
						C.WEIGHT,
						C.FOREGROUND_COLOR,
						C.BACKGROUND_COLOR,
						C.CONT_SIZE,
						C.ID_OPERATOR
					FROM
						M_VESSEL_PROFILE_CELL a
					JOIN m_vessel_profile_bay e ON A.ID_VESSEL = E.ID_VESSEL AND A.ID_BAY = E.ID_BAY
					LEFT JOIN (
						SELECT
							B.no_container,
							b.ID_POD,
							b.ID_COMMODITY,
							d.id_vessel,
							b.CONT_SIZE,
							CASE
								WHEN MOD(NVL(B.VS_BAY, E.BAY_),
								2) = 0 THEN NVL(B.VS_BAY, E.BAY_)+ 1
								ELSE NVL(B.VS_BAY, E.BAY_)
							END VSB_BAY,
							NVL(B.VS_ROW, E.ROW_) VS_ROW,
							NVL(B.VS_TIER, E.TIER_) VS_TIER,
							B.ID_CLASS_CODE,
							b.CONT_STATUS,
							b.CONT_TYPE,
							b.HAZARD,
							b.IMDG,
							b.TL_FLAG,
							ROUND((b.WEIGHT/1000),1) as WEIGHT,
							M.FOREGROUND_COLOR,
							M.BACKGROUND_COLOR,
							b.ID_OPERATOR
						FROM con_listcont b
						JOIN ves_voyage d ON B.ID_VES_VOYAGE = D.ID_VES_VOYAGE
						LEFT JOIN con_inbound_sequence E ON B.NO_CONTAINER = E.NO_CONTAINER AND B.POINT = E.POINT
						LEFT JOIN M_PORT M ON M.PORT_CODE = B.ID_POD
						WHERE
							B.id_ves_voyage = '$idvesvoy'
							AND b.ID_TERMINAL = ".$this->gtools->terminal()."
							AND b.id_class_code IN ('I','TI','TC','S1','S2')
							AND B.ID_OP_STATUS <> 'DIS'
							AND
							CASE
								WHEN (B.ID_CLASS_CODE = 'S1' OR B.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = B.ID_VES_VOYAGE AND NO_CONTAINER = B.NO_CONTAINER) > 0 
								THEN B.POINT
								ELSE 1
							END =
							CASE
								WHEN (B.ID_CLASS_CODE = 'S1' OR B.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = B.ID_VES_VOYAGE AND NO_CONTAINER = B.NO_CONTAINER) > 0
								THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = B.ID_VES_VOYAGE AND NO_CONTAINER = B.NO_CONTAINER)
								ELSE 1
							END ) c ON
						a.ID_VESSEL = c.ID_VESSEL
						AND E.BAY = c.vsb_bay
						AND A.ROW_ = c.vs_row
						AND A.TIER_ = c.vs_tier
					WHERE
						a.ID_VESSEL = '$id_vessel'
						AND a.POSISI_STACK = '$abvBlw'
						AND a.STATUS_STACK != 'N'
						AND e.BAY = '$bay'
						AND E.OCCUPY = 'Y'
						$q_add
						AND a.tier_ IN ( SELECT z.tier_
									FROM M_VESSEL_PROFILE_CELL z
									WHERE 1=1  
									--AND z.status_stack NOT IN ('X','N') 
									AND z.ID_VESSEL = a.id_vessel AND z.id_bay = a.id_bay
									GROUP BY z.tier_)
						ORDER BY a.cell_number";
		}
		else
		{
			// $smp="'E','TE','TC','S1','S2'";
			// $dmp="";
			// $xmp="con_outbound_sequence";

			$query	= "SELECT
							a.*,
							E.BAY, c.CONT_SIZE, c.VSB_BAY, c.VS_ROW, c.VS_TIER, c.NO_CONTAINER,
							SUBSTR(c.NO_CONTAINER, 0, 4) no_container_h,
							SUBSTR(c.NO_CONTAINER, 5, 7) no_container_num,
							c.ID_POD,
							(SELECT f.CONT_SIZE
								FROM con_listcont f
								INNER JOIN CON_OUTBOUND_SEQUENCE co ON co.ID_VES_VOYAGE=f.ID_VES_VOYAGE
								WHERE
									(TO_NUMBER(e.BAY)+ 1) = CASE WHEN f.ID_CLASS_CODE = 'TC' THEN f.vs_bay ELSE co.bay_ END
									AND  a.ROW_ = CASE WHEN f.ID_CLASS_CODE = 'TC' THEN f.VS_ROW ELSE co.ROW_ END
									AND a.TIER_ = CASE WHEN f.ID_CLASS_CODE = 'TC' THEN f.VS_TIER ELSE co.TIER_ END
									AND f.id_class_code IN ('E','TE','TC','S1','S2')
									AND f.id_ves_voyage = '$idvesvoy'
									AND rownum = 1) AS FUTURE40,
							C.ID_CLASS_CODE,
							C.CONT_STATUS ,
							C.CONT_TYPE,
							c.HAZARD,
							c.IMDG,
							C.TL_FLAG,
							c.ID_COMMODITY,
							C.WEIGHT,
							C.FOREGROUND_COLOR,
							C.BACKGROUND_COLOR,
							C.CONT_SIZE,
							C.ID_OPERATOR
						FROM
							M_VESSEL_PROFILE_CELL a
						JOIN m_vessel_profile_bay e ON A.ID_VESSEL = E.ID_VESSEL AND A.ID_BAY = E.ID_BAY
						LEFT JOIN (
							SELECT
								B.no_container,
								b.ID_POD,
								b.ID_COMMODITY,
								d.id_vessel,
								b.CONT_SIZE,
								CASE
									WHEN MOD(NVL(B.VS_BAY, E.BAY_),2) = 0 THEN NVL(B.VS_BAY, E.BAY_)+ 1
									ELSE NVL(B.VS_BAY, E.BAY_)
								END VSB_BAY,
								NVL(B.VS_ROW, E.ROW_) VS_ROW,
								NVL(B.VS_TIER, E.TIER_) VS_TIER,
								B.ID_CLASS_CODE,
								b.CONT_STATUS,
								b.CONT_TYPE,
								b.HAZARD,
								b.IMDG,
								b.TL_FLAG,
								ROUND((b.WEIGHT/1000),1) as WEIGHT,
								M.FOREGROUND_COLOR,
								M.BACKGROUND_COLOR,
								b.ID_OPERATOR
							FROM
								con_listcont b
							JOIN ves_voyage d ON B.ID_VES_VOYAGE = D.ID_VES_VOYAGE
							LEFT JOIN con_outbound_sequence E ON B.NO_CONTAINER = E.NO_CONTAINER AND B.POINT = E.POINT
							LEFT JOIN M_PORT M ON M.PORT_CODE = B.ID_POD
							WHERE
								B.id_ves_voyage = '$idvesvoy'
								AND b.ID_TERMINAL = ".$this->gtools->terminal()."
								AND b.id_class_code IN ('E','TE','TC','S1','S2')
								AND B.ID_OP_STATUS <> 'DIS'
								AND
									CASE
										WHEN (B.ID_CLASS_CODE = 'S1' OR B.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = B.ID_VES_VOYAGE AND NO_CONTAINER = B.NO_CONTAINER) > 0 THEN B.POINT
										ELSE 1
									END <>
									CASE
										WHEN (B.ID_CLASS_CODE = 'S1' OR B.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = B.ID_VES_VOYAGE AND NO_CONTAINER = B.NO_CONTAINER) > 0
										THEN (SELECT MAX(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = B.ID_VES_VOYAGE AND NO_CONTAINER = B.NO_CONTAINER)
										ELSE 0
									END
						) c ON a.ID_VESSEL = c.ID_VESSEL AND E.BAY = c.vsb_bay AND A.ROW_ = c.vs_row AND A.TIER_ = c.vs_tier
						WHERE
							a.ID_VESSEL = '$id_vessel'
							AND a.POSISI_STACK = '$abvBlw'
							AND a.STATUS_STACK != 'N'
							AND e.BAY = '$bay'
							AND E.OCCUPY = 'Y'
							$q_add
							AND a.tier_ IN ( SELECT z.tier_
										FROM M_VESSEL_PROFILE_CELL z
										WHERE 1=1  
										--AND z.status_stack NOT IN ('X','N') 
										AND z.ID_VESSEL = a.id_vessel AND z.id_bay = a.id_bay
										GROUP BY z.tier_)
							ORDER BY a.cell_number";
			//echo $query;die;
		}
		
		/*
		$query	= "select a.*,E.BAY,c.CONT_SIZE,c.VSB_BAY,c.VS_ROW,c.VS_TIER,c.NO_CONTAINER,substr(c.NO_CONTAINER, 0, 4) no_container_h,substr(c.NO_CONTAINER, 5, 7) no_container_num,c.ID_POD,(select f.CONT_SIZE from con_listcont f where
					f.vs_bay=(TO_NUMBER(e.BAY)+1) and f.vs_row=a.ROW_ and f.vs_tier=a.TIER_ and  f.id_class_code in ($smp) and f.id_ves_voyage='$idvesvoy' and rownum = 1) as FUTURE40,C.ID_CLASS_CODE,C.CONT_STATUS , C.CONT_TYPE, c.HAZARD, c.IMDG,C.TL_FLAG, c.ID_COMMODITY, C.WEIGHT
					FROM M_VESSEL_PROFILE_CELL a join m_vessel_profile_bay e on
					A.ID_VESSEL=E.ID_VESSEL and A.ID_BAY=E.ID_BAY
					left join
					(select B.no_container,b.ID_POD,b.ID_COMMODITY,d.id_vessel,b.CONT_SIZE,case when MOD(NVL(B.VS_BAY,E.BAY_),2)=0 then NVL(B.VS_BAY,E.BAY_)+1 else NVL(B.VS_BAY,E.BAY_) end VSB_BAY,NVL(B.VS_ROW,E.ROW_) VS_ROW,
					NVL(B.VS_TIER,E.TIER_) VS_TIER,B.ID_CLASS_CODE,b.CONT_STATUS, b.CONT_TYPE, b.HAZARD, b.IMDG,b.TL_FLAG, b.WEIGHT
					from con_listcont b
						join ves_voyage d on B.ID_VES_VOYAGE=D.ID_VES_VOYAGE
						join $xmp E on B.NO_CONTAINER=E.NO_CONTAINER and E.ID_VES_VOYAGE=B.ID_VES_VOYAGE
						where d.id_ves_voyage='$idvesvoy' ";
		if($this->gtools->terminal() != ''){
		$query .= "			and b.ID_TERMINAL = ".$this->gtools->terminal()." ";
		}
		
		$query .= "			and b.id_class_code in ($smp) $dmp ) c
						on a.ID_VESSEL=c.ID_VESSEL AND E.BAY=c.vsb_bay and A.ROW_=c.vs_row and A.TIER_=c.vs_tier
					WHERE a.ID_VESSEL='$id_vessel' and a.POSISI_STACK='$abvBlw' AND a.STATUS_STACK!='N' AND e.BAY='$bay'
					    and a.tier_ in
					(select z.tier_ from M_VESSEL_PROFILE_CELL z where z.status_stack NOT IN ('X','N') and z.ID_VESSEL=a.id_vessel and z.id_bay=a.id_bay group by z.tier_)
					order by a.cell_number";
		*/
		//debux($query);die;
		$rs 		= $this->db->query($query);

		$data 		= $rs->result_array();
		
		//debux($data);die;
		return $data;
	}

	public function cek_RowStowageStatus($id_vessel,$idbay,$rowbay)
	{
		$query="select count(1) RES  from M_VESSEL_PROFILE_CELL WHERE ID_VESSEL='$id_vessel' AND ID_BAY='$idbay' and ROW_='$rowbay' and status_stack NOT in ('X','N') ";
		$rs 		= $this->db->query($query);

		$data 		= $rs->row_array();

		return $data;
	}

	public function get_cellPerBayPreLoad($id_vessel,$idvesvoy,$bay,$abvBlw,$ei){
		$smp="'E','TE','TC'";

		$query	= "select a.*,E.BAY,c.CONT_SIZE,c.VSB_BAY,c.VS_ROW,c.VS_TIER,c.NO_CONTAINER,(select f.CONT_SIZE from con_listcont f
	join CON_OUTBOUND_SEQUENCE h on h.ID_VES_VOYAGE=f.ID_VES_VOYAGE and h.NO_CONTAINER=f.NO_CONTAINER and h.POINT=f.POINT
	where h.bay_=(TO_NUMBER(e.BAY)+1) and h.row_=a.ROW_ and h.tier_=a.TIER_ and h.id_ves_voyage='$idvesvoy' ) as FUTURE40,C.ID_CLASS_CODE,C.CONT_STATUS
					FROM M_VESSEL_PROFILE_CELL a join m_vessel_profile_bay e on
					A.ID_VESSEL=E.ID_VESSEL and A.ID_BAY=E.ID_BAY
					left join
					 (select B.no_container,d.id_vessel,b.CONT_SIZE,case when MOD(g.BAY_,2)=0 then g.BAY_+1 else G.BAY_ end VSB_BAY,g.ROW_ as VS_ROW, g.TIER_ VS_TIER,B.ID_CLASS_CODE,b.CONT_STATUS
						from con_listcont b join ves_voyage d on B.ID_VES_VOYAGE=D.ID_VES_VOYAGE
						join CON_OUTBOUND_SEQUENCE g on G.ID_VES_VOYAGE=B.ID_VES_VOYAGE and G.NO_CONTAINER=B.NO_CONTAINER and G.POINT=B.POINT
						where d.id_ves_voyage='$idvesvoy' and b.id_class_code in ($smp) ) c
						on a.ID_VESSEL=c.ID_VESSEL AND E.BAY=c.vsb_bay and A.ROW_=c.vs_row and A.TIER_=c.vs_tier
					WHERE a.ID_VESSEL='$id_vessel' and a.POSISI_STACK='$abvBlw' AND a.STATUS_STACK!='N' AND e.BAY='$bay'
					order by a.cell_number";
		//echo $query;die;
		$rs 		= $this->db->query($query);

		$data 		= $rs->result_array();

		return $data;
	}

	public function get_info_vesvoy($id_vesvoy){
		$query 		= "SELECT VESSEL_NAME||' '||VOY_IN||'-'||VOY_OUT AS VESVOY FROM VES_VOYAGE WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_vesvoy') AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$fl_vs 		= $this->db->query($query);
		$data 		= $fl_vs->result_array();

		return $data;
	}

	public function delete_vessel_particular($id_vessel){
		$query = "DELETE FROM M_VESSEL WHERE ID_VESSEL='$id_vessel'";
		$this->db->query($query);
		$query = "DELETE FROM M_VESSEL_PROFILE WHERE ID_VESSEL='$id_vessel'";
		$this->db->query($query);
		$query = "DELETE FROM M_VESSEL_PROFILE_BAY WHERE ID_VESSEL='$id_vessel'";
		$this->db->query($query);
		$query = "DELETE FROM M_VESSEL_PROFILE_CELL WHERE ID_VESSEL='$id_vessel'";
		$this->db->query($query);
		$query = "DELETE FROM M_VESSEL_HATCH WHERE ID_VESSEL='$id_vessel'";
		$this->db->query($query);
	}

	public function stowage_header_print($vescode,$id_ves_voyage){
		$query 		= "SELECT VESSEL_NAME,
							  VOY_IN||'-'||VOY_OUT AS VOYAGE
					   FROM VES_VOYAGE
					   WHERE TRIM(ID_VES_VOYAGE) = TRIM('$id_ves_voyage') AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function stowage_print_vesinfo($vescode,$id_ves_voyage,$id_bay){
		$query 		= "SELECT JML_ROW,
							  JML_TIER_UNDER,
							  JML_TIER_ON,
							  OCCUPY
						FROM M_VESSEL_PROFILE_BAY
						WHERE TRIM(ID_VESSEL) = ('$vescode')
						AND ID_BAY = '$id_bay'";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function stowage_print_vesinfo_allbay($vescode){
		$query 		= "SELECT DISTINCT JML_ROW,
									   JML_TIER_UNDER,
									   JML_TIER_ON,
									   (JML_ROW+1) WIDTH
			  		   FROM M_VESSEL_PROFILE_BAY
			  		   WHERE TRIM(ID_VESSEL) = TRIM('$vescode')
			  		   AND BAY > 0
			  		   AND JML_TIER_UNDER > 0";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function stowage_print_vescell($id_bay,$cell_number,$pss_bay){
		if ($pss_bay=='D')
		{
			$posisi_bay = 'ABOVE';
		}
		else
		{
			$posisi_bay = 'BELOW';
		}

		$query 		= "SELECT ID_CELL ID,
							  ROW_,
							  TIER_,
							  STATUS_STACK,
							  POSISI_STACK
					   FROM M_VESSEL_PROFILE_CELL
					   WHERE ID_BAY = '$id_bay'
					   AND CELL_NUMBER = '$cell_number'
					   AND POSISI_STACK IN ('$posisi_bay','HATCH')";
		//print_r($query);
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function stowage_print_vescont_imp($id_vs,$id_cellx){
		$param = array(
			array('name'=>':v_cell', 'value'=>$id_cellx, 'length'=>50),
			array('name'=>':v_act', 'value'=>'I', 'length'=>10),
			array('name'=>':v_idvesvoyage', 'value'=>$id_vs, 'length'=>50),
			//array('name'=>':v_act_in', 'value'=>'("I","TI")', 'length'=>50),
			array('name'=>':v_msg', 'value'=>&$msg, 'length'=>500)
		);
		 //print_r($param);die;

		$sql = "BEGIN ITOS_OP.proc_stowage_print_cont(:v_cell,:v_act,:v_idvesvoyage,:v_msg); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		// print_r($msg);die;

		return $msg;
	}

	public function get_pod_color($pod){
		return $this->db->get_where('M_PORT', array('PORT_CODE' => $pod))->row();
	}

	public function stowage_print_vescont_exp($id_vs,$id_cellx){
		$param = array(
			array('name'=>':v_cell', 'value'=>$id_cellx, 'length'=>50),
			array('name'=>':v_act', 'value'=>'E', 'length'=>10),
			array('name'=>':v_idvesvoyage', 'value'=>$id_vs, 'length'=>50),
			array('name'=>':v_msg', 'value'=>&$msg, 'length'=>500)
		);
		// print_r($param);die;

		$sql = "BEGIN ITOS_OP.proc_stowage_print_cont(:v_cell,:v_act,:v_idvesvoyage,:v_msg); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		// print_r($msg);die;

		return $msg;
	}

	public function stowage_print_vesbay_list($vescode,$id_ves_voyage){
		$query 		= "SELECT ID_BAY ID,
							  BAY,
							  NVL(OCCUPY,'T') OCCUPY
					   FROM M_VESSEL_PROFILE_BAY
					   WHERE ID_VESSEL = '$vescode'
					   AND BAY > 0
					   ORDER BY ID_BAY DESC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function stowage_print_allbay_blok2($id_area){
		$query 		= "SELECT ID_BAY ID,
							  CELL_NUMBER,
							  ROW_,
							  TIER_,
							  STATUS_STACK,
							  PLUGGING
						FROM M_VESSEL_PROFILE_CELL
						WHERE ID_BAY = '$id_area'
						ORDER BY CELL_NUMBER ASC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_vessel_profile_cellInfo_editprofile($id_vessel, $id_bay, $pos){
		$param = array($id_vessel, $id_bay, $pos);
		$query = "SELECT B.*, A.BAY, A.JML_ROW
						FROM
							M_VESSEL_PROFILE_CELL B
							LEFT JOIN M_VESSEL_PROFILE_BAY A
							ON A.ID_BAY = B.ID_BAY
						WHERE B.STATUS_STACK <> 'N' AND A.ID_VESSEL = ? AND A.ID_BAY = ? AND B.POSISI_STACK = ?
					ORDER BY B.ID_CELL ASC";

		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		return $data;
	}

	public function edit_vessel_profile_set_broken_space($id_vessel, $id_bay, $xml_str){
		$xml = simplexml_load_string($xml_str);

		$data = $xml->data;
		$id_cell = $data->id_cell;
		$id_cell_arr = explode(",",$id_cell);

		$flag = 1;
		$this->db->trans_start();

		foreach ($id_cell_arr as $id_cell){
			$param = array(
				$id_vessel,
				$id_bay,
				$id_cell
			);
			$query_plan = "UPDATE M_VESSEL_PROFILE_CELL
							SET
								STATUS_STACK='X'
							WHERE ID_VESSEL=? AND ID_BAY=? AND ID_CELL=?";
			$flag = ($flag && $this->db->query($query_plan, $param));
		}

		$this->db->trans_complete();
		return $flag;
	}

	public function edit_vessel_profile_unset_broken_space($id_vessel, $id_bay, $xml_str){
		$xml = simplexml_load_string($xml_str);

		$data = $xml->data;
		$id_cell = $data->id_cell;
		$id_cell_arr = explode(",",$id_cell);

		$flag = 1;
		$this->db->trans_start();

		foreach ($id_cell_arr as $id_cell){
			$param = array(
				$id_vessel,
				$id_bay,
				$id_cell
			);
			$query_plan = "UPDATE M_VESSEL_PROFILE_CELL
							SET
								STATUS_STACK='A'
							WHERE ID_VESSEL=? AND ID_BAY=? AND ID_CELL=?";
			$flag = ($flag && $this->db->query($query_plan, $param));
		}

		$this->db->trans_complete();
		return $flag;
	}

	public function edit_vessel_profile_set_reefer_racking($id_vessel, $id_bay, $xml_str){
		$xml = simplexml_load_string($xml_str);

		$data = $xml->data;
		$id_cell = $data->id_cell;
		$id_cell_arr = explode(",",$id_cell);

		$flag = 1;
		$this->db->trans_start();

		foreach ($id_cell_arr as $id_cell){
			$param = array(
				$id_vessel,
				$id_bay,
				$id_cell
			);
			$query_plan = "UPDATE M_VESSEL_PROFILE_CELL
							SET
								STATUS_REEFER_RACKING='X'
							WHERE ID_VESSEL=? AND ID_BAY=? AND ID_CELL=?";
			$flag = ($flag && $this->db->query($query_plan, $param));
		}

		$this->db->trans_complete();
		return $flag;
	}

	public function edit_vessel_profile_unset_reefer_racking($id_vessel, $id_bay, $xml_str){
		$xml = simplexml_load_string($xml_str);

		$data = $xml->data;
		$id_cell = $data->id_cell;
		$id_cell_arr = explode(",",$id_cell);

		$flag = 1;
		$this->db->trans_start();

		foreach ($id_cell_arr as $id_cell){
			$param = array(
				$id_vessel,
				$id_bay,
				$id_cell
			);
			$query_plan = "UPDATE M_VESSEL_PROFILE_CELL
							SET
								STATUS_REEFER_RACKING='A'
							WHERE ID_VESSEL=? AND ID_BAY=? AND ID_CELL=?";
			$flag = ($flag && $this->db->query($query_plan, $param));
		}

		$this->db->trans_complete();
		return $flag;
	}

	public function get_vesselprofile_info($id_vessel){
		$param = array($id_vessel);
		$query = "SELECT *
						FROM
							M_VESSEL_PROFILE
						WHERE ID_VESSEL = ?";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row_array();
		return $data;
	}

	public function getVesServc($id, $field){
		$where = " where 1=1";
		if($id != 'null' || $id != ''){
			if($field == 'name') {
				$where .= " and VESSEL_SERVICE_NAME like '%$id%'";
			}

			if($field == 'operator') {
				$where .= " and ID_VESSEL_SERVICE IN (
						SELECT ID_VESSEL_SERVICE FROM M_VESSEL_SERVICE_OPERATOR WHERE ID_OPERATOR LIKE '%$id%'
					)";
			}
		}

		$query = "SELECT ID_VESSEL_SERVICE, VESSEL_SERVICE_NAME
						FROM
							M_VESSEL_SERVICE $where ORDER BY TO_NUMBER(ID_VESSEL_SERVICE) ASC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		return $data;
	}

	public function getServOp($id){
		$comment='where A.ID_VESSEL_SERVICE='.$id;
		$query = "SELECT A.ID_OPERATOR,B.OPERATOR_NAME
						FROM
							M_VESSEL_SERVICE_OPERATOR A JOIN M_OPERATOR B ON A.ID_OPERATOR=B.ID_OPERATOR $comment ORDER BY ID_VESSEL_SERVICE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		return $data;
	}

	public function getServPr($id){
		$comment='where A.ID_VESSEL_SERVICE='.$id;
		$query = "SELECT A.ID_PORT,B.PORT_NAME, A.COLOR
						FROM
							M_VESSEL_SERVICE_PORT A JOIN M_PORT B ON A.ID_PORT=B.PORT_CODE $comment ORDER BY SEQUENCE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		return $data;
	}

	public function getContOp(){
		$query = "SELECT B.ID_OPERATOR,B.OPERATOR_NAME
						FROM
							M_OPERATOR B ORDER BY B.ID_OPERATOR";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		return $data;
	}

	public function getContPrt(){
		$query = "SELECT B.PORT_CODE,B.PORT_NAME
						FROM
							M_PORT B ORDER BY B.PORT_CODE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		return $data;
	}

	public function getServPrt($id){
		$comment='where ID_VESSEL_SERVICE='.$id;
		$query = "SELECT ID_PORT
						FROM
							M_VESSEL_SERVICE_PORT $comment ORDER BY ID_VESSEL_SERVICE";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		return $data;
	}

	public function saveOprSrv($idserv,$idopr){
		$query = "begin prc_add_oprsrvc('$idserv','$idopr'); end;";
		$rs 		= $this->db->query($query);
		return 'success';
	}

	public function savePrtSrv($idserv,$idopr,$colorport){
		$param = array(
				array('name'=>':v_idsrv', 'value'=>$idserv, 'length'=>30),
				array('name'=>':v_prt', 'value'=>$idopr, 'length'=>50),
				array('name'=>':v_color', 'value'=>$colorport, 'length'=>50)
			);
		$this->db->trans_start();
		$query = "begin prc_add_prtsrvc(:v_idsrv, :v_prt ,:v_color ); end;";
		//echo $query;die;
		$this->db->exec_bind_stored_procedure($query, $param);

		if ($this->db->trans_complete()){
			return 'success';
		}else{
			return 'no';
		}
	}

	public function saveSrvLane($servname){
		$param = array(

				array('name'=>':v_servname', 'value'=>$servname, 'length'=>50)
			);
		$this->db->trans_start();
		$query = "begin prc_add_srvclane(:v_servname ); end;";
		//echo $query;die;
		$this->db->exec_bind_stored_procedure($query, $param);

		if ($this->db->trans_complete()){
			return 'success';
		}else{
			return 'no';
		}
	}

	public function getInfoStowageprint($id_vesvoyage,$ei,$id_vessel){
		$param = array(

				array('name'=>':vesvoyage_id', 'value'=>$id_vesvoyage, 'length'=>50),
				array('name'=>':ei', 'value'=>$ei, 'length'=>50),
				array('name'=>':vessel_id', 'value'=>$id_vessel, 'length'=>50)
			);
		$this->db->trans_start();
		$query = "begin prc_docstowageprint(:vesvoyage_id,:ei,:vessel_id); end;";
		//echo $query;die;
		$this->db->exec_bind_stored_procedure($query, $param);

		if ($this->db->trans_complete()){
			$query="select ITEMS, Q20, Q40, Q40HC, Q45, QTOTAL from DOC_STOWAGEPRINT where ID_VES_VOYAGE='$id_vesvoyage' AND EI='$ei' AND ID_VESSEL = '$id_vessel' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
			$datanya    =$this->db->query($query);
			$data 		= $datanya->result_array();
			return $data;
		}else{
			return 'database error';
		}
	}

	public function saveSrvLaneRename($id,$servname){

		$this->db->trans_start();
		$query = "update m_vessel_service set vessel_service_name='$servname' where id_vessel_service=$id";
		//echo $query;die;
		$this->db->query($query);

		if ($this->db->trans_complete()){
			return 'success';
		}else{
			return 'no';
		}
	}

	public function delOprSrv($idserv,$idopr){
		$query = "begin prc_del_oprsrvc('$idserv','$idopr'); end;";
		$rs  = $this->db->query($query);
		return 'success';
	}

	public function delOprPrt($idserv,$idopr){
		$query = "begin prc_del_prtsrvc('$idserv','$idopr'); end;";
		$rs  = $this->db->query($query);
		return 'success';
	}

	public function get_vessel_departure_list($paging=false, $sort=false, $filters=false){
		$qWhere = "WHERE ACTIVE='N'";

		if($filters) {
			foreach($filters as $obj) {
				if($obj->type == 'string') {
					if($obj->field == 'VESSEL_NAME') {
						$qWhere .= " AND VESSEL_NAME LIKE '%$obj->value%'";
					}

				}

				if($obj->type == 'date') {
					if($obj->field == 'ARRIVAL') {
						if($obj->comparison == 'lt') $comparison = '<=';
						if($obj->comparison == 'gt') $comparison = '>=';
						$qWhere .= " AND ATA $comparison TO_DATE('$obj->value', 'MM/DD/YYYY')";
					}
				}
			}
		}

		$query_count = "SELECT COUNT(ID_VES_VOYAGE) TOTAL
						FROM VES_VOYAGE $qWhere AND ID_TERMINAL = '".$this->gtools->terminal()."'";

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
			if ($sortProperty=='ARRIVAL'){
				$sortProperty = 'ATA';
			}
			if ($sortProperty=='BERTH'){
				$sortProperty = 'ATB';
			}
			if ($sortProperty=='DEPARTURE'){
				$sortProperty = 'ATD';
			}
			$qSort .= " ORDER BY ".$sortProperty." ".$sortDirection;
		}


		$query = "SELECT B.*
					  FROM (SELECT A.*, ROWNUM REC_NUM
							  FROM (  SELECT
										ID_VES_VOYAGE, VESSEL_NAME, VOY_IN, VOY_OUT,
										TO_CHAR(ATA,'DD-MM-YYYY HH24:MI') ARRIVAL,
										TO_CHAR(ATB,'DD-MM-YYYY HH24:MI') BERTH,
										TO_CHAR(ATD,'DD-MM-YYYY HH24:MI') DEPARTURE
									FROM
										VES_VOYAGE
										$qWhere AND ID_TERMINAL = '".$this->gtools->terminal()."'
									$qSort) A
								) B
					$qPaging";
	 	// echo $query;die;
		$rs = $this->db->query($query);
		$vessel_voyage = $rs->result_array();
		$data = array (
			'total'=>$total,
			'data'=>$vessel_voyage
		);
		return $data;
	}

	public function set_undeparture_vessel_voyage($id_ves_voyage){
		$query = "UPDATE VES_VOYAGE
					SET ACTIVE='Y', ATD=NULL, END_WORK=NULL
					WHERE ID_VES_VOYAGE=? AND ID_TERMINAL=? AND ACTIVE='N'";
		$flag = $this->db->query($query, array($id_ves_voyage,$this->gtools->terminal()));
		if ($flag){
			return 1;
		}else{
			return 0;
		}
	}

	public function set_vesbay_occupy($vs_code,$idbay,$status,$id_user){
		$params = $vs_code."^".$idbay."^".$status."^".$id_user;
		$param = array(
			array('name'=>':v_param', 'value'=>$params, 'length'=>500),
			array('name'=>':v_msg', 'value'=>&$msg, 'length'=>50)
		);
		// print_r($param);die;

		$sql = "BEGIN ITOS_OP.proc_vesbay_occupy(:v_param,:v_msg); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		// print_r($msg);die;

		return $msg;
	}

	public function reset_vespro($vs_code,$id_user){
		$params = $vs_code."^".$id_user;
		$param = array(
			array('name'=>':v_param', 'value'=>$params, 'length'=>500),
			array('name'=>':v_msg', 'value'=>&$msg, 'length'=>50)
		);
		// print_r($param);die;

		$sql = "BEGIN ITOS_OP.proc_del_vespro(:v_param,:v_msg); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		// print_r($msg);die;

		return $msg;
	}

	public function generateBay_rowTier($vs_code,$idbay,$jmlrow,$jmltier_abv,$jmltier_blw,$abv_stat,$blw_stat,$id_user){
		$params = $vs_code."^".$idbay."^".$jmlrow."^".$jmltier_abv."^".$jmltier_blw."^".$abv_stat."^".$blw_stat."^".$id_user;
		$param = array(
			array('name'=>':v_param', 'value'=>$params, 'length'=>500),
			array('name'=>':v_msg', 'value'=>&$msg, 'length'=>50)
		);
		// print_r($param);die;

		$sql = "BEGIN ITOS_OP.PROC_GENERATE_ROWTIER_D_H(:v_param,:v_msg); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		// print_r($msg);die;

		return $msg;
	}

	public function set_vesbay_hatch($vs_code,$idbay,$idht,$id_user){
		$params = $vs_code."^".$idbay."^".$idht."^".$id_user;
		// print_r($params);die;
		$param = array(
			array('name'=>':v_param', 'value'=>$params, 'length'=>500),
			array('name'=>':v_msg', 'value'=>&$msg, 'length'=>50)
		);
		// print_r($param);die;
		$sql = "BEGIN ITOS_OP.proc_vesbay_hatch(:v_param,:v_msg); END;";
		$this->db->exec_bind_stored_procedure($sql, $param);
		// print_r($msg);die;

		return $msg;
	}

	public function get_hatch_list($filter=false,$vscode){
		$query 		= "SELECT ID_HATCH, HATCH_NUMBER FROM M_VESSEL_HATCH
						WHERE ID_VESSEL = '$vscode'
						ORDER BY ID_HATCH ASC";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function get_vesvoy($idvesvoy){
		$query 		= "SELECT VESSEL_NAME||' '||VOY_IN||'-'||VOY_OUT AS VESVOY FROM VES_VOYAGE
						WHERE ID_VES_VOYAGE = '$idvesvoy' AND ID_TERMINAL = '".$this->gtools->terminal()."'";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}

	public function validate_stowage_position($id_ves_voyage, $vs_bay, $vs_row, $vs_tier){
		$vs_bay = (int) $vs_bay;
		$vs_row = (int) $vs_row;
		$vs_tier = (int) $vs_tier;
		$param = array($id_ves_voyage,$this->gtools->terminal());
		$query = "SELECT ID_VESSEL
					FROM VES_VOYAGE
					WHERE ID_VES_VOYAGE=? AND ID_TERMINAL=?";
		$rs = $this->db->query($query, $param);
		$data = $rs->row_array();
		$id_vessel = $data['ID_VESSEL'];
		$param = array($id_vessel, $vs_bay);
		$query = "SELECT ID_BAY
					FROM M_VESSEL_PROFILE_BAY
					WHERE ID_VESSEL=? AND BAY=?";
		$rs = $this->db->query($query, $param);
		$data = $rs->row_array();
		$id_bay = $data['ID_BAY'];
		$param = array($id_vessel, $id_bay, $vs_row, $vs_tier);
		$query = "SELECT COUNT(ID_CELL) JUMLAH
					FROM M_VESSEL_PROFILE_CELL
					WHERE ID_VESSEL=? AND ID_BAY=? AND ROW_=? AND TIER_=? AND STATUS_STACK='A'";
		$rs = $this->db->query($query, $param);
		$data = $rs->row_array();
		if ($data['JUMLAH']==0){
			return 2;
		}

		$param = array($id_ves_voyage, $vs_bay, $vs_bay+1, $vs_bay-1, $vs_row, $vs_tier,$this->gtools->terminal());
		$query = "SELECT COUNT(NO_CONTAINER) JUMLAH
					FROM CON_LISTCONT
					WHERE ID_VES_VOYAGE=? AND (VS_BAY=? OR VS_BAY=? OR VS_BAY=?) AND VS_ROW=? AND VS_TIER=? AND ID_TERMINAL=? AND ID_CLASS_CODE IN ('E', 'TE')";
		$rs = $this->db->query($query, $param);
		$data = $rs->row_array();
		if ($data['JUMLAH']>0){
			return 3;
		}
		return 1;
	}

	public function get_edi_service_config(){
		$query = "SELECT VES_OPERATOR, EMAIL_ADDRESS
					FROM EDI_SERVICE_CONFIG
					WHERE ACTIVE=1";
		$rs = $this->db->query($query);
		$data = $rs->result_array();

		return $data;
	}

	public function get_vessel_stevedoring_status($id_ves_voyage){
		$retval = array();
		$param = array($id_ves_voyage,$this->gtools->terminal());

		$query 		= "SELECT VESSEL_NAME, VOY_IN, VOY_OUT
						FROM VES_VOYAGE
						WHERE ID_VES_VOYAGE=? AND ID_TERMINAL=?";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->row_array();
		$retval['vessel_info'] = $data;

		$query 		= "SELECT DECODE(ID_CLASS_CODE,'E','LOADING','DISCHARGE') ACTIVITY, COUNT(NO_CONTAINER) JUMLAH
						FROM CON_LISTCONT
						WHERE ID_VES_VOYAGE=? AND ID_TERMINAL=? AND ID_OP_STATUS <> 'DIS'
						GROUP BY ID_CLASS_CODE";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		$retval['plan']['DISCHARGE'] = 0;
		$retval['plan']['LOADING'] = 0;
		foreach ($data as $key => $value){
			$retval['plan'][$value['ACTIVITY']] = $value['JUMLAH'];
		}

		$query 		= "SELECT DECODE(ACTIVITY,'E','LOADING','DISCHARGE') ACTIVITY, COUNT(NO_CONTAINER) JUMLAH
						FROM JOB_CONFIRM
						WHERE ID_VES_VOYAGE=? AND ID_TERMINAL=?
						GROUP BY ACTIVITY";
		$rs 		= $this->db->query($query, $param);
		$data 		= $rs->result_array();
		$retval['real']['DISCHARGE'] = 0;
		$retval['real']['LOADING'] = 0;
		foreach ($data as $key => $value){
			$retval['real'][$value['ACTIVITY']] = $value['JUMLAH'];
		}

		return $retval;
	}


	//===Add by mustadio_gun
	//===08-05-2017

	public function insert_category($category_name, $category_detail, $ves_voy)
	{

		// echo '<pre>';
		// print_r($ves_voy);
		// echo '</pre>'; die;





		$this->db->trans_start();

		// $param = array($id, $category_name);
		// $query 	= "INSERT INTO M_VESSEL_SI_CTGR
					// (ID_CATEGORY, CATEGORY_NAME) VALUES(?, ?)";
		// $rs 	= $this->db->query($query, $param);

		// foreach($detail as $key=>$value){
				// if ($q_fields!=''){
					// $q_fields .= ",";
				// }
				// $q_fields .= $key;
				// if ($q_values!=''){
					// $q_values .= ",";
				// }
				// if ($key=='ID_SI_CTGR'){
					// $q_values .= "'".$id."'";
				// }else{
					// $q_values .= "'".$value."'";
				// }
			// }


		$query_vess 	= "SELECT DISTINCT(ID_VESSEL) ID_VESSEL
						FROM VES_VOYAGE
						WHERE ID_VES_VOYAGE='$ves_voy' AND ID_TERMINAL='".$this->gtools->terminal()."'";
		$rs 		= $this->db->query($query_vess);
		$data 		= $rs->row_array();
		// $retval['plan']['DISCHARGE'] = 0;
		$id_vessel = $data['ID_VESSEL'];

		// echo '<pre>';
		// print_r($id_vessel);
		// echo '</pre>'; die;


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
				if ($key=='CATEGORY_NAME'){
					$q_values .= "'".$category_name."'";
				}else if ($key=='ID_VES_VOYAGE')
				{
					$q_values .="'".$ves_voy."'";
				}
				else if ($key=='ID_VESSEL')
				{
					$q_values .="'".$id_vessel."'";
				}
				else{
					$q_values .= "'".$value."'";
				}
			}

			// echo '<pre>';
			// print_r ($q_fields);
			// echo '<br/>';
			// print_r ($q_values);
			// echo '</pre>'; die;



			$query 	= "INSERT INTO M_VESSEL_SI_CTGR ($q_fields) VALUES($q_values)";
			$rs 	= $this->db->query($query);
		}

		if ($this->db->trans_complete()){
			return $id;
		}else{
			return 0;
		}
	}

	public function get_si_category_group(){
		$query = "SELECT A.ID_YARD_PLAN, B.YARD_NAME, C.BLOCK_NAME, A.START_SLOT||'-'||A.END_SLOT AS SLOT_RANGE, A.START_ROW||'-'||A.END_ROW AS ROW_RANGE, A.CAPACITY, D.CATEGORY_NAME, D.ID_CATEGORY
			FROM YARD_PLAN_GROUP A
			INNER JOIN M_YARD B ON A.ID_YARD=B.ID_YARD
			INNER JOIN M_YARDBLOCK C ON A.ID_BLOCK=C.ID_BLOCK
			INNER JOIN M_PLAN_CATEGORY_H D ON A.ID_CATEGORY=D.ID_CATEGORY
			$qid_ves_voyage
			ORDER BY A.ID_YARD_PLAN";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();

		return $data;
	}


	//===End of add by mustadio_gun

	public function get_list_machine($id_ves_voyage, $activity){
		$param = array($id_ves_voyage, $activity);
		$query = "select
			distinct w.id_machine, w.mch_name
		from mch_working_sequence s
		left join mch_working_plan w
			on (s.id_mch_working_plan = w.id_mch_working_plan)
		left join m_machine m
			on (w.id_machine = m.id_machine)
		where w.id_ves_voyage = ? and w.id_machine <> -1 and s.activity = ?";
		$rs = $this->db->query($query, $param);
		$data = $rs->result_array();

		return $data;
	}

	public function get_machine_all_plan($id_ves_voyage, $activity){
		$param = array($id_ves_voyage, $activity);
		$query = "select
			w.id_ves_voyage, w.id_machine, w.mch_name, s.sequence, s.bay, s.deck_hatch,
			s.activity, s.active, s.estimate_time, s.start_sequence, s.end_sequence
		from mch_working_sequence s
		left join mch_working_plan w
			on (s.id_mch_working_plan = w.id_mch_working_plan)
		left join m_machine m
			on (w.id_machine = m.id_machine)
		where w.id_ves_voyage = ? and w.id_machine <> -1 and s.activity = ?";
		$rs = $this->db->query($query, $param);
		$data = $rs->result_array();

		return $data;
	}

	public function get_machine_plan($id_ves, $id_ves_voyage, $activity, $id_bay){
		$param = array($id_ves_voyage, $activity, $id_ves, $id_bay);
		$query = "select
				w.id_ves_voyage, w.id_machine, w.mch_name, s.sequence, s.bay, s.deck_hatch,
				s.activity, s.active, s.estimate_time, s.start_sequence, s.end_sequence
			from mch_working_sequence s
			left join mch_working_plan w
				on (s.id_mch_working_plan = w.id_mch_working_plan)
			left join m_machine m
				on (w.id_machine = m.id_machine)
			where w.id_ves_voyage = ? and w.id_machine <> -1 and s.activity = ? and s.bay =
				(select bay from m_vessel_profile_bay where id_vessel = ? and id_bay = ?)";
		$rs = $this->db->query($query, $param);
		$data = $rs->result_array();

		return $data;
	}

	public function get_machine_plan_dh($id_ves, $id_ves_voyage, $activity, $id_bay, $deck_hatch){
		$param = array($id_ves_voyage, $activity, $deck_hatch, $id_ves, $id_bay);
		$query = "select
				w.id_ves_voyage, w.id_machine, w.mch_name, s.sequence, s.bay, s.deck_hatch,
				s.activity, s.active, s.estimate_time, s.start_sequence, s.end_sequence
			from mch_working_sequence s
			left join mch_working_plan w
				on (s.id_mch_working_plan = w.id_mch_working_plan)
			left join m_machine m
				on (w.id_machine = m.id_machine";
		if($this->gtools->terminal() != ''){
		    $query .= " AND m.ID_TERMINAL = ".$this->gtools->terminal();
		}
		$query .= ")
			where w.id_ves_voyage = ? and w.id_machine <> -1 and s.activity = ? and s.deck_hatch = ? and s.bay =
				(select bay from m_vessel_profile_bay where id_vessel = ? and id_bay = ?)";
		$rs = $this->db->query($query, $param);
		$data = $rs->result_array();

		return $data;
	}

	public function get_vessel_port_list($filter){
		$query 		= "SELECT PORT_CODE, PORT_NAME FROM M_PORT WHERE (PORT_CODE LIKE '%".strtoupper($filter)."%' OR PORT_NAME LIKE '%".strtoupper($filter)."%') AND PORT_COUNTRY = 'ID'";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function get_active_vessel()
	{
		$query 		= "SELECT 
						DISTINCT
						 	A.ID_VES_VOYAGE,
						 	A.VESSEL_NAME||' - '||A.VOY_IN||'/'||A.VOY_OUT VESSEL
						FROM ves_voyage A 
						--INNER JOIN mch_working_plan C ON A.id_ves_voyage = C.id_ves_voyage
						--INNER JOIN mch_working_sequence B ON C.id_mch_working_plan = B.id_mch_working_plan
						WHERE A.ACTIVE IN ('Y') AND A.ID_TERMINAL = '".$this->gtools->terminal()."'
						ORDER BY
						VESSEL";
		$rs 		= $this->db->query($query);
		$data 		= $rs->result_array();
		
		return $data;
	}

	public function getDetailVesVoy($id){
		$query 		= "SELECT 
							C.VOY_IN||' | '||C.VOY_OUT AS VOYAGE,
					    	C.ID_VESSEL,
					    	A.VESSEL,
					    	A.VESSEL_CODE,
					    	B.LENGTH,
					    	TO_CHAR (C.ATD, 'DD-MM-YYYY HH24:MI') ATD,
					    	TO_CHAR (C.ATA, 'DD-MM-YYYY HH24:MI') ATA,
					    	TO_CHAR (C.ATB, 'DD-MM-YYYY HH24:MI') ATB
					   	FROM ITOS_REPO.M_VSB_VOYAGE A
					   	LEFT JOIN ITOS_OP.M_VESSEL B ON B.ID_VESSEL=A.VESSEL_CODE
					   	LEFT JOIN ITOS_OP.VES_VOYAGE C ON C.ID_VES_VOYAGE=A.UKKS
					   	WHERE A.UKKS = '".$id."' AND C.ID_TERMINAL = '".$this->gtools->terminal()."'";

		$rs 		= $this->db->query($query);
		$data 		= $rs->row();
		
		return $data;
	}

	public function getVesvoy($id){
		$query = "SELECT VESSEL_NAME || ' ' || VOY_IN || '/' || VOY_OUT AS VESSEL FROM VES_VOYAGE WHERE ID_VES_VOYAGE = '$id' AND ID_TERMINAL = '".$this->gtools->terminal()."'";

		$rs 		= $this->db->query($query);
		$data 		= $rs->row();
		
		return $data;
	}

	public function getSummaryContByVesselAndCommodity($id,$commodity){
		if($commodity == 'R'){
			$qwhere = "AND C.ID_COMMODITY IN ('R','RH')";
		}else{
			$qwhere = "AND C.ID_COMMODITY = '$commodity'";
		}

		//debux($qwhere);
		$query = "SELECT
					(SELECT COUNT(*)
					FROM CON_LISTCONT C
					JOIN VES_VOYAGE VV ON VV.ID_VES_VOYAGE = C.ID_VES_VOYAGE
					LEFT JOIN CON_INBOUND_SEQUENCE E ON C.NO_CONTAINER = E.NO_CONTAINER AND C.POINT = E.POINT
					WHERE
						C.ID_VES_VOYAGE = '$id'
						AND C.ID_TERMINAL = '".$this->gtools->terminal()."'
						AND C.ID_CLASS_CODE IN ('I','TI','TC','S1','S2')
						AND C.ID_OP_STATUS <> 'DIS'
						AND C.CONT_SIZE IN ('20','21')
						$qwhere
						AND
						CASE
							WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
							THEN C.POINT
							ELSE 1
						END =
						CASE
							WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
							THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER)
							ELSE 1
						END
					) DUAPULUH,
					(SELECT COUNT(*)
					FROM CON_LISTCONT C
					JOIN VES_VOYAGE VV ON VV.ID_VES_VOYAGE = C.ID_VES_VOYAGE
					LEFT JOIN CON_INBOUND_SEQUENCE E ON C.NO_CONTAINER = E.NO_CONTAINER AND C.POINT = E.POINT
					WHERE
						C.ID_VES_VOYAGE = '$id'
						AND C.ID_TERMINAL = '".$this->gtools->terminal()."'
						AND C.ID_CLASS_CODE IN ('I','TI','TC','S1','S2')
						AND C.ID_OP_STATUS <> 'DIS'
						AND C.CONT_SIZE = '40'
						$qwhere
						AND
						CASE
							WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
							THEN C.POINT
							ELSE 1
						END =
						CASE
							WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
							THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER)
							ELSE 1
						END
					) EMPATPULUH,
					(SELECT COUNT(*)
					FROM CON_LISTCONT C
					JOIN VES_VOYAGE VV ON VV.ID_VES_VOYAGE = C.ID_VES_VOYAGE
					LEFT JOIN CON_INBOUND_SEQUENCE E ON C.NO_CONTAINER = E.NO_CONTAINER AND C.POINT = E.POINT
					WHERE
						C.ID_VES_VOYAGE = '$id'
						AND C.ID_TERMINAL = '".$this->gtools->terminal()."'
						AND C.ID_CLASS_CODE IN ('I','TI','TC','S1','S2')
						AND C.ID_OP_STATUS <> 'DIS'
						AND C.CONT_SIZE = '40'
						$qwhere
						AND C.CONT_TYPE = 'HQ'
						AND
						CASE
							WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
							THEN C.POINT
							ELSE 1
						END =
						CASE
							WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
							THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER)
							ELSE 1
						END
					) EMPATHC,
					(SELECT COUNT(*)
					FROM CON_LISTCONT C
					JOIN VES_VOYAGE VV ON VV.ID_VES_VOYAGE = C.ID_VES_VOYAGE
					LEFT JOIN CON_INBOUND_SEQUENCE E ON C.NO_CONTAINER = E.NO_CONTAINER AND C.POINT = E.POINT
					WHERE
						C.ID_VES_VOYAGE = '$id'
						AND C.ID_TERMINAL = '".$this->gtools->terminal()."'
						AND C.ID_CLASS_CODE IN ('I','TI','TC','S1','S2')
						AND C.ID_OP_STATUS <> 'DIS'
						AND C.CONT_SIZE = '45'
						$qwhere
						AND
						CASE
							WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
							THEN C.POINT
							ELSE 1
						END =
						CASE
							WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
							THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER)
							ELSE 1
							
						END
					) EMPATLIMA,
					(SELECT COUNT(*)
					FROM CON_LISTCONT C
					JOIN VES_VOYAGE VV ON VV.ID_VES_VOYAGE = C.ID_VES_VOYAGE
					LEFT JOIN CON_INBOUND_SEQUENCE E ON C.NO_CONTAINER = E.NO_CONTAINER AND C.POINT = E.POINT
					WHERE
						C.ID_VES_VOYAGE = '$id'
						AND C.ID_TERMINAL = '".$this->gtools->terminal()."'
						AND C.ID_CLASS_CODE IN ('I','TI','TC','S1','S2')
						AND C.ID_OP_STATUS <> 'DIS'
						$qwhere
						AND
						CASE
							WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
							THEN C.POINT
							ELSE 1
						END =
						CASE
							WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
							THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER)
							ELSE 1
						END
					) TOTAL
				FROM DUAL";

			
			$rs 		= $this->db->query($query);
			$data 		= $rs->row();
			
			//debux($data);

			return $data;
		}

	public function recalculate_working_sequence($id_ves_voyage){
		$msg = '';
		$param = array(

				array('name'=>':v_idvesvoy', 'value'=>$id_ves_voyage, 'length'=>50),
				array('name'=>':v_msg', 'value'=>&$msg, 'length'=>50)
			);
		$query = "begin RECALCULATE_MCH_WORKING_SEQ(:v_idvesvoy,:v_msg); end;";
		//echo $query;die;
		$this->db->exec_bind_stored_procedure($query, $param);

		return $msg;
	}

	public function summary_weight($id_vesvoy,$bay,$row,$pss_bay){
		if($pss_bay=='D'){
			$ps = 'ABOVE';
		}else{
			$ps = 'BELOW';
		}

		$query = "SELECT SUM(Z.WEIGHT) AS TOTAL 
					FROM (SELECT C.NO_CONTAINER, C.ID_VES_VOYAGE, C.WEIGHT FROM CON_LISTCONT C
					INNER JOIN M_VESSEL_PROFILE_CELL MV ON MV.ROW_=C.VS_ROW AND MV.TIER_=C.VS_TIER
					INNER JOIN CON_INBOUND_SEQUENCE CO ON CO.NO_CONTAINER=C.NO_CONTAINER AND CO.ID_VES_VOYAGE=C.ID_VES_VOYAGE
					WHERE C.ID_VES_VOYAGE = '$id_vesvoy'
						AND trim(C.id_class_code) IN ('I','TI','S1','S2')
						AND
						CASE
							WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
							THEN C.POINT
							ELSE 1
						END =
						CASE
							WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
							THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER)
							ELSE 1
						END
						AND C.VS_BAY = '$bay'
						AND C.VS_ROW = '$row'
						AND CO.DECK_HATCH = '$pss_bay'
						AND MV.POSISI_STACK = '$ps'
					GROUP BY C.NO_CONTAINER, C.ID_VES_VOYAGE, C.WEIGHT) Z";

		$rs 		= $this->db->query($query);
		$data 		= $rs->row();
		
		return $data;
	}
	
	public function summary_weight_out($id_vesvoy,$bay,$row,$pss_bay){
		if($pss_bay=='D'){
			$ps = 'ABOVE';
		}else{
			$ps = 'BELOW';
		}

		$query = "SELECT SUM(Z.WEIGHT) AS TOTAL 
					FROM (SELECT C.NO_CONTAINER, C.ID_VES_VOYAGE, C.WEIGHT FROM CON_LISTCONT C
					INNER JOIN CON_OUTBOUND_SEQUENCE CO ON CO.NO_CONTAINER=C.NO_CONTAINER AND CO.ID_VES_VOYAGE=C.ID_VES_VOYAGE
					INNER JOIN M_VESSEL_PROFILE_CELL MV ON MV.ROW_=CO.ROW_ AND MV.TIER_=CO.TIER_
					WHERE C.ID_VES_VOYAGE = '$id_vesvoy'
						AND trim(C.id_class_code) IN ('E','TE','S1','S2')
						AND
							CASE
								WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0 THEN C.POINT
								ELSE 1
							END <>
							CASE
								WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
								THEN (SELECT MAX(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER)
								ELSE 0
							END
						AND CO.BAY_ = '$bay'
						AND CO.ROW_ = '$row'
						AND CO.DECK_HATCH = '$pss_bay'
						AND MV.POSISI_STACK = '$ps'
					GROUP BY C.NO_CONTAINER, C.ID_VES_VOYAGE, C.WEIGHT) Z";

		$rs 		= $this->db->query($query);
		$data 		= $rs->row();
		
		return $data;
	}
	
	public function get_summary_vessel_header($id_ves_voyage,$ei){
	    $query = "SELECT DECODE(CONT_SIZE,21,20,CONT_SIZE) CONT_SIZE,DECODE(CONT_TYPE,'HQ','HC','N') CONT_TYPE
			,CASE WHEN CONT_TYPE = 'OVD' THEN 'OOG' ELSE DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) END AS GROUP_
			,'TOTAL_'|| DECODE(CONT_SIZE,21,20,CONT_SIZE) || CASE WHEN CONT_TYPE = 'OVD' THEN 'OOG' ELSE DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) END || DECODE(CONT_TYPE,'HQ','_HQ','') AS COL
		    FROM CON_LISTCONT C
		    LEFT JOIN 
		    ";
		    if($ei == 'I'){
			$query .= " CON_INBOUND_SEQUENCE " ;
			$class_code = "('I','TI','TC','S1','S2')";
		    }else{
			$query .= " CON_OUTBOUND_SEQUENCE " ;
			$class_code = "('E','TE','TC','S1','S2')";
		    }
		    $query .= "CO
			    ON C.NO_CONTAINER = CO.NO_CONTAINER AND C.POINT = CO.POINT
		    WHERE C.ID_VES_VOYAGE = '$id_ves_voyage' 
		    AND C.ID_CLASS_CODE IN $class_code
		    AND
			    CASE
				    WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
				    THEN C.POINT
				    ELSE 1
			    END <>
			    CASE
				    WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
				    THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER)
				    ELSE 0
			    END
		    AND C.ID_OP_STATUS <> 'DIS'
		    AND CASE WHEN C.ID_CLASS_CODE IN ('TC','S1','S2') THEN 1 WHEN CO.BAY_ IS NOT NULL THEN 1 ELSE 0 END = 1
		    GROUP BY DECODE (CONT_SIZE,21,20,CONT_SIZE)
		    ,DECODE(CONT_TYPE,'HQ','HC','N')
		    ,CASE WHEN CONT_TYPE = 'OVD' THEN 'OOG' ELSE DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) END 
		    ,DECODE(CONT_TYPE,'HQ','_HQ','N'),'TOTAL_'|| DECODE(CONT_SIZE,21,20,CONT_SIZE) || CASE WHEN CONT_TYPE = 'OVD' THEN 'OOG' ELSE DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) END || DECODE(CONT_TYPE,'HQ','_HQ','') 
		    ORDER BY CONT_SIZE,DECODE(CONT_TYPE,'HQ','_HQ','N') DESC";
		    
	    $res = $this->db->query($query)->result_array();
	    
	    return $res;    
	}
	public function get_summary_vessel_body($id_ves_voyage,$ei){
	    $query = "SELECT ID_POD
			    ,MAX(TOTAL_20H) TOTAL_20H
			    ,MAX(TOTAL_20OOG) TOTAL_20OOG
			    ,MAX(TOTAL_20R) TOTAL_20R
			    ,MAX(TOTAL_20RH) TOTAL_20RH
			    ,MAX(TOTAL_20F) TOTAL_20F
			    ,MAX(TOTAL_20M) TOTAL_20M
			    ,MAX(TOTAL_20H_HQ) TOTAL_20H_HQ
			    ,MAX(TOTAL_20R_HQ) TOTAL_20R_HQ
			    ,MAX(TOTAL_20RH_HQ) TOTAL_20RH_HQ
			    ,MAX(TOTAL_20F_HQ) TOTAL_20F_HQ
			    ,MAX(TOTAL_20M_HQ) TOTAL_20M_HQ
			    ,MAX(TOTAL_40H) TOTAL_40H 
			    ,MAX(TOTAL_40OOG) TOTAL_40OOG 
			    ,MAX(TOTAL_40R) TOTAL_40R 
			    ,MAX(TOTAL_40RH) TOTAL_40RH 
			    ,MAX(TOTAL_40F) TOTAL_40F 
			    ,MAX(TOTAL_40M) TOTAL_40M 
			    ,MAX(TOTAL_40H_HQ) TOTAL_40H_HQ 
			    ,MAX(TOTAL_40R_HQ) TOTAL_40R_HQ 
			    ,MAX(TOTAL_40RH_HQ) TOTAL_40RH_HQ 
			    ,MAX(TOTAL_40F_HQ) TOTAL_40F_HQ 
			    ,MAX(TOTAL_40M_HQ) TOTAL_40M_HQ 
			    ,MAX(TOTAL_45H) TOTAL_45H 
			    ,MAX(TOTAL_45OOG) TOTAL_45OOG 
			    ,MAX(TOTAL_45R) TOTAL_45R 
			    ,MAX(TOTAL_45RH) TOTAL_45RH 
			    ,MAX(TOTAL_45F) TOTAL_45F 
			    ,MAX(TOTAL_45M) TOTAL_45M 
			    ,MAX(TOTAL_45H_HQ) TOTAL_45H_HQ 
			    ,MAX(TOTAL_45R_HQ) TOTAL_45R_HQ 
			    ,MAX(TOTAL_45RH_HQ) TOTAL_45RH_HQ 
			    ,MAX(TOTAL_45F_HQ) TOTAL_45F_HQ 
			    ,MAX(TOTAL_45M_HQ) TOTAL_45M_HQ
		    FROM (
			    SELECT ID_POD,DECODE(CONT_SIZE,21,20,CONT_SIZE) CONT_SIZE,DECODE(CONT_TYPE,'HQ',CONT_TYPE,'N') CONT_TYPE
				,CASE WHEN CONT_TYPE = 'OVD' THEN 'OOG' ELSE DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) END AS GROUP_
				,'TOTAL_'|| DECODE(CONT_SIZE,21,20,CONT_SIZE) || CASE WHEN CONT_TYPE = 'OVD' THEN 'OOG' ELSE DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) END AS COL
				,COUNT(*) AS TOTAL
				,SUM(CASE WHEN CONT_SIZE IN (20,21) AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'H' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_20H 
				,SUM(CASE WHEN CONT_SIZE IN (20,21) AND CONT_TYPE = 'OVD' THEN 1 ELSE 0 END) AS TOTAL_20OOG 
				,SUM(CASE WHEN CONT_SIZE IN (20,21) AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'R' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_20R 
				,SUM(CASE WHEN CONT_SIZE IN (20,21) AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'RH' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_20RH 
				,SUM(CASE WHEN CONT_SIZE IN (20,21) AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'F' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_20F 
				,SUM(CASE WHEN CONT_SIZE IN (20,21) AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'M' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_20M 
				,SUM(CASE WHEN CONT_SIZE IN (20,21) AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'H' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_20H_HQ 
				,SUM(CASE WHEN CONT_SIZE IN (20,21) AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'R' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_20R_HQ 
				,SUM(CASE WHEN CONT_SIZE IN (20,21) AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'RH' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_20RH_HQ 
				,SUM(CASE WHEN CONT_SIZE IN (20,21) AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'F' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_20F_HQ 
				,SUM(CASE WHEN CONT_SIZE IN (20,21) AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'M' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_20M_HQ 
				,SUM(CASE WHEN CONT_SIZE = 40 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'H' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_40H 
				,SUM(CASE WHEN CONT_SIZE = 40 AND CONT_TYPE = 'OVD' THEN 1 ELSE 0 END) AS TOTAL_40OOG 
				,SUM(CASE WHEN CONT_SIZE = 40 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'R' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_40R 
				,SUM(CASE WHEN CONT_SIZE = 40 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'RH' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_40RH 
				,SUM(CASE WHEN CONT_SIZE = 40 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'F' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_40F 
				,SUM(CASE WHEN CONT_SIZE = 40 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'M' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_40M 
				,SUM(CASE WHEN CONT_SIZE = 40 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'H' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_40H_HQ 
				,SUM(CASE WHEN CONT_SIZE = 40 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'R' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_40R_HQ 
				,SUM(CASE WHEN CONT_SIZE = 40 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'RH' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_40RH_HQ 
				,SUM(CASE WHEN CONT_SIZE = 40 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'F' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_40F_HQ 
				,SUM(CASE WHEN CONT_SIZE = 40 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'M' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_40M_HQ 
				,SUM(CASE WHEN CONT_SIZE = 45 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'H' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_45H 
				,SUM(CASE WHEN CONT_SIZE = 45 AND CONT_TYPE = 'OVD' THEN 1 ELSE 0 END) AS TOTAL_45OOG 
				,SUM(CASE WHEN CONT_SIZE = 45 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'R' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_45R 
				,SUM(CASE WHEN CONT_SIZE = 45 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'RH' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_45RH 
				,SUM(CASE WHEN CONT_SIZE = 45 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'F' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_45F 
				,SUM(CASE WHEN CONT_SIZE = 45 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'M' AND CONT_TYPE NOT IN ('HQ','OVD') THEN 1 ELSE 0 END) AS TOTAL_45M 
				,SUM(CASE WHEN CONT_SIZE = 45 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'H' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_45H_HQ 
				,SUM(CASE WHEN CONT_SIZE = 45 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'R' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_45R_HQ 
				,SUM(CASE WHEN CONT_SIZE = 45 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'RH' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_45RH_HQ 
				,SUM(CASE WHEN CONT_SIZE = 45 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'F' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_45F_HQ 
				,SUM(CASE WHEN CONT_SIZE = 45 AND DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) = 'M' AND CONT_TYPE = 'HQ' THEN 1 ELSE 0 END) AS TOTAL_45M_HQ 
			    FROM CON_LISTCONT C
			    LEFT JOIN 
		    ";
		    if($ei == 'I'){
			$query .= " CON_INBOUND_SEQUENCE " ;
			$class_code = "('I','TI','TC','S1','S2')";
		    }else{
			$query .= " CON_OUTBOUND_SEQUENCE " ;
			$class_code = "('E','TE','TC','S1','S2')";
		    }
		    $query .= "CO
			    ON C.NO_CONTAINER = CO.NO_CONTAINER AND C.POINT = CO.POINT
		    WHERE C.ID_VES_VOYAGE = '$id_ves_voyage' 
		    AND C.ID_CLASS_CODE IN $class_code
		    AND
			    CASE
				    WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
				    THEN C.POINT
				    ELSE 1
			    END <>
			    CASE
				    WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
				    THEN (SELECT MIN(POINT) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER)
				    ELSE 0
			    END
		    AND C.ID_OP_STATUS <> 'DIS'
		    AND CASE WHEN C.ID_CLASS_CODE IN ('TC','S1','S2') THEN 1 WHEN CO.BAY_ IS NOT NULL THEN 1 ELSE 0 END = 1
		    GROUP BY ID_POD,DECODE (CONT_SIZE,21,20,CONT_SIZE),DECODE(CONT_TYPE,'HQ',CONT_TYPE,'N'),CASE WHEN CONT_TYPE = 'OVD' THEN 'OOG' ELSE DECODE(CONT_STATUS,'MTY','M',DECODE(ID_COMMODITY,'G','F',ID_COMMODITY)) END
	    ) A GROUP BY A.ID_POD";
//		    debux($query);exit;
	    $res = $this->db->query($query)->result_array();
	    
	    return $res;
	}
}
?>
