<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Global error variable
 */
$error_msg = array();

/**
 * Converts value to nonnegative integer.
 *
 * @param mixed $maybeint Data you wish to have convered to an nonnegative integer
 * @return int An nonnegative integer
 */
if ( !function_exists('absint') )
{
	function absint( $number )
	{
		return abs( intval( $number ) );
	}
}

if ( ! function_exists('debux'))
{
	function debux($array, $is_die = FALSE)
	{
		if(is_array($array))
		{
			echo '<pre>'; print_r($array); echo '</pre>';

			if ($is_die)
			{
				die();
			}
		}
		else
		{
			echo '<pre>'; print_r($array); echo '</pre>';

			if ($is_die)
			{
				die();
			}
		}

		
	}
}



if ( ! function_exists('add_single_quotes'))
{
	function add_single_quotes($param)
	{
		foreach ($param as $value) {
			$val[] = "'".$value."'";
		}

		return $val;
	}
}

if( ! function_exists('convert_days'))
{
	function convert_days($param)
	{
		$secs = strtotime($param);

	    if($secs>=86400){$days=floor($secs/86400);$secs=$secs%86400;$r=$days.' day';if($days<>1){$r.='s';}if($secs>0){$r.=', ';}}  
	    if($secs>=3600){$hours=floor($secs/3600);$secs=$secs%3600;$r.=$hours.' hour';if($hours<>1){$r.='s';}if($secs>0){$r.=', ';}}  
	    if($secs>=60){$minutes=floor($secs/60);$secs=$secs%60;$r.=$minutes.' minute';if($minutes<>1){$r.='s';}if($secs>0){$r.=', ';}}  
	    $r.=$secs.' second';if($secs<>1){$r.='s';}  
	    
	    return $r;  

	}
}

if( !function_exists('get_pod_color')){
	function get_pod_color($pod,$color = 'BACKGROUND_COLOR')
	{	
		$ci    =& get_instance();
		$getcolor = $ci->vessel->get_pod_color($pod);
		if($color == 'BACKGROUND_COLOR') $rs = $getcolor->BACKGROUND_COLOR;
		else $rs = $getcolor->FOREGROUND_COLOR;
		return $rs;
	}
}

if( !function_exists('get_pod_by_com')){
	function get_pod_by_com($commodity,$id_yard,$id_ves_voyage)
	{
		$ci    		   =& get_instance();
		$id_terminal   = $ci->gtools->terminal();
		$id_class_code = "";

		$sql = "SELECT
					DISTINCT(C.ID_POD)
				FROM
					CON_LISTCONT C
				JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER AND C.POINT = E.POINT
				WHERE
				C.ID_COMMODITY = '$commodity'
				AND C.ID_VES_VOYAGE = '$id_ves_voyage'
				AND C.ID_CLASS_CODE IN ('E', 'TE') 
				AND NVL(E.BILLING_PAID,'0') = 
				(CASE WHEN C.ID_CLASS_CODE = 'E' THEN E.BILLING_PAID
				ELSE '0' 
				END)
				--AND C.YD_YARD IN ('$id_yard')
				";
		$response = $ci->db->query($sql)->result();
		//debux($sql);
		return $response;
	}
}

function get_loc($id_comm,$id_pod,$id_yard,$id_ves_voyage){

	$ci    		   =& get_instance();
	$id_terminal   = $ci->gtools->terminal();
	$id_class_code = "";

	$sql = "SELECT
				C.YD_BLOCK,
				C.YD_BLOCK_NAME
			FROM
				CON_LISTCONT C
			JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER AND C.POINT = E.POINT
			WHERE
				C.ID_COMMODITY = '$id_comm'
				AND C.ID_CLASS_CODE IN ('E', 'TE','TC','S1','S2')
				/*AND NVL(E.BILLING_PAID,'0') = 
				(CASE WHEN 
					C.ID_CLASS_CODE = 'E' 
				THEN E.BILLING_PAID
					ELSE '0' 
				END)*/
				AND C.YD_BLOCK IS NOT NULL
				AND C.YD_YARD IN ('$id_yard')
				AND C.ID_POD = '$id_pod' 
				AND C.ID_VES_VOYAGE = '$id_ves_voyage'
				AND C.ID_OP_STATUS NOT IN ('SLY')
			GROUP BY C.YD_BLOCK,
				C.YD_BLOCK_NAME
			UNION 
			SELECT -1 YD_BLOCK,
				   'NY' YD_BLOCK_NAME
				FROM
					DUAL
			UNION
			SELECT -2 YD_BLOCK,
				   'On Chasis' YD_BLOCK_NAME
				FROM
					DUAL
			UNION
			SELECT -3 YD_BLOCK,
				   'Virtual Block View' YD_BLOCK_NAME
				FROM
					DUAL
			ORDER BY YD_BLOCK_NAME ASC";
	// debux($sql);
	$response = $ci->db->query($sql)->result();
	return $response;
}

function get_block_pod_com($id_commodity,$id_pod,$id_block,$status_plan,$cont_status,$cont_size,$cont_type='',$id_yard,$id_ves_voyage)
{
	$ci    		   =& get_instance();
	$id_terminal   = $ci->gtools->terminal();

	if($id_block == '-2' || $id_block == '-1'){
		//$status_plan = 'N';
	}

	if($status_plan == 'N'){
		$yd_yard       = "";
	}else{
		$yd_yard       = "AND C.YD_YARD IN ('$id_yard')";
	}

//	$con_outbound_seq = "";
	$status_seq		  = "";
	$con_outbound_seq = " LEFT JOIN CON_OUTBOUND_SEQUENCE Q ON C.NO_CONTAINER = Q.NO_CONTAINER AND C.ID_VES_VOYAGE = Q.ID_VES_VOYAGE";
	if($status_plan == 'Y'){
		$status_seq       = "AND Q.STATUS = 'P'";
	}else{
		$status_seq 	  = "AND Q.STATUS IS NULL";
	}

	// $status_plan_q   = ($status_plan == 'N')  ? "AND C.STATUS_PLACEMENT IS NULL" : "AND C.STATUS_PLACEMENT = 'Y'";
	$cont_type       = ($cont_type != '') 	  ? "AND C.CONT_TYPE = '$cont_type'" : "AND C.CONT_TYPE NOT IN ('HQ')";

	$status_plan_q   = "";

	
	if($id_block == '-1'){
		/*kondisi container NY*/
		$sql = "SELECT
				DECODE(COUNT(*),0,NULL,COUNT(*)) AS JML
			FROM
				CON_LISTCONT C
				JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER AND C.POINT = E.POINT
			WHERE
				C.ID_COMMODITY 		   = '$id_commodity'
				AND C.ID_POD  		   = '$id_pod'
				AND C.CONT_SIZE 	   = $cont_size
				AND C.ID_CLASS_CODE    IN ('E', 'TE')
				AND C.ID_VES_VOYAGE    = '$id_ves_voyage'
				AND C.CONT_STATUS      = '$cont_status'
				AND C.ID_OP_STATUS     NOT IN ('SLY','SLG')
				AND C.STATUS_PLACEMENT IS NULL
				AND C.TL_FLAG 			= 'N'
				AND C.ID_OP_STATUS <> 'DIS'
				AND NVL(E.BILLING_PAID,'0') = 
				(CASE WHEN C.ID_CLASS_CODE = 'E' THEN E.BILLING_PAID
				ELSE '0' 
				END)
				$cont_type
				$yd_yard";
		// debux($sql);
	}elseif($id_block == '-2'){
		/*kondisi container ON Chasis*/
		$sql = "SELECT
				DECODE(COUNT(*),0,NULL,COUNT(*)) AS JML
			FROM
				CON_LISTCONT C
				JOIN JOB_YARD_MANAGER J ON C.NO_CONTAINER  = J.NO_CONTAINER AND J.ID_VES_VOYAGE = C.ID_VES_VOYAGE
				$con_outbound_seq
			WHERE
					C.ID_COMMODITY 	 = '$id_commodity'
				AND C.ID_POD  		 = '$id_pod'
				AND C.CONT_SIZE 	 = $cont_size
				AND C.ID_VES_VOYAGE  = '$id_ves_voyage'
				AND C.CONT_STATUS    = '$cont_status'
				AND C.ID_CLASS_CODE    IN ('E', 'TE')
				AND C.ID_OP_STATUS     NOT IN ('SLY','SLG')
				AND J.ID_OP_STATUS   = 'OYS'
				AND J.EVENT 		 = 'O'
				AND C.ID_OP_STATUS <> 'DIS'
				AND J.STATUS_FLAG    = 'C'
				$status_seq
				$status_plan_q
				$cont_type
				$yd_yard";
	// debux($sql);
	}elseif($id_block == '-3'){
		/*kondisi container TL*/
		$sql = "SELECT
				DECODE(COUNT(*),0,NULL,COUNT(*)) AS JML
			FROM
				CON_LISTCONT C
				$con_outbound_seq
			WHERE
				C.ID_COMMODITY 		   = '$id_commodity'
				AND C.ID_POD  		   = '$id_pod'
				AND C.CONT_SIZE 	   = $cont_size
				AND C.ID_CLASS_CODE    IN ('E', 'TE','TC')
				AND C.ID_VES_VOYAGE    = '$id_ves_voyage'
				AND C.CONT_STATUS      = '$cont_status'
				AND C.ID_OP_STATUS     NOT IN ('SLY','SLG')
				AND C.ID_OP_STATUS <> 'DIS'
				--AND C.STATUS_PLACEMENT IS NULL
				AND C.TL_FLAG 		   = 'Y'
				$status_seq
				$cont_type
				--$yd_yard
				";
			//debux($sql);
	}else{
		/*kondisi udha stacking*/
		$sql = "SELECT
				DECODE(COUNT(*),0,NULL,COUNT(*)) AS JML
			FROM
				CON_LISTCONT C
				$con_outbound_seq
			WHERE
				C.ID_COMMODITY 		 = '$id_commodity'
				AND C.YD_BLOCK 		 = '$id_block'
				AND C.ID_POD  		 = '$id_pod'
				AND C.CONT_SIZE 		= $cont_size
				AND C.ID_CLASS_CODE    IN ('E', 'TE')
				AND C.ID_VES_VOYAGE    = '$id_ves_voyage'
				AND C.CONT_STATUS      = '$cont_status'
				AND C.ID_OP_STATUS     NOT IN ('SLY','OYS','SLG')
				AND C.ID_OP_STATUS <> 'DIS'
				$status_seq
				$status_plan_q
				$cont_type
				$yd_yard";
		 //debux($sql);
	}
//	if($id_pod == 'IDTNJ' && $id_block == '-3' && $id_commodity == 'RH' && $cont_size == '20'){
//	    debux($sql);
//	}
	$response = $ci->db->query($sql)->row();

	return $response->JML;
}

function get_loc_filtered($id_comm,$id_yard){

	$ci    		   =& get_instance();
	$id_terminal   = $ci->gtools->terminal();
	$id_class_code = "";

	$sql = "SELECT
				DISTINCT(C.YD_BLOCK_NAME)
				FROM
				CON_LISTCONT C
			JOIN ITOS_REPO.M_CYC_CONTAINER E ON E.NO_CONTAINER = C.NO_CONTAINER AND C.POINT = E.POINT
			WHERE
				C.ID_COMMODITY = '$id_comm'
				AND C.ID_CLASS_CODE IN ('E', 'TE','TC','S1','S2')
				AND NVL(E.BILLING_PAID,'0') = 
				(CASE WHEN C.ID_CLASS_CODE = 'E' THEN E.BILLING_PAID
				ELSE '0' 
				END)
				AND C.YD_YARD IS NOT NULL
				AND C.YD_YARD IN ('$id_yard') 
			ORDER BY YD_BLOCK_NAME ASC";
	// debux($sql);
	$response = $ci->db->query($sql)->result();
	return $response;
}

if(!function_exists('get_dtl_mch'))
{
	function get_dtl_mch($id_ves_voyage,$id_machine,$size,$ie,$sts_cont){
		$ci    		   =& get_instance();
		$id_terminal   = $ci->gtools->terminal();
		$id_class_code = "";

		if($ie == 'I'){
			$id_class_code = "('I','TI')";
		}else{
			$id_class_code = "('E','TE')";
		}

		$sql = "SELECT
					COUNT(*) AS JML
				FROM
					CON_LISTCONT
				WHERE
					TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'
					AND ID_TERMINAL 	= '$id_terminal'
					AND ID_CLASS_CODE   IN $id_class_code
					AND CONT_SIZE 		= '$size' 
					AND CONT_STATUS     = '$sts_cont'
					AND QC_PLAN 		= (SELECT MCH_NAME FROM M_MACHINE WHERE ID_MACHINE = '$id_machine')";
		$response = $ci->db->query($sql)->row();
		//debux($sql);
		return $response->JML;
	}
}

if(!function_exists('get_dtl_mch_sum'))
{
	function get_dtl_mch_sum($id_ves_voyage,$id_machine,$size,$ie,$cont_type){
		$ci    		   =& get_instance();
		$id_terminal   = $ci->gtools->terminal();
		$id_class_code = "";

		if($ie == 'I'){
			$id_class_code = "('I','TI')";
		}else{
			$id_class_code = "('E','TE')";
		}

		$q1 = "";
		if($cont_type == 'OOG'){
			$q1 = "AND CONT_HEIGHT = '$cont_type'";
		}else{
			$q1 = "AND CONT_TYPE   = '$cont_type'";
		}


		$sql = "SELECT
					COUNT(*) AS JML
				FROM
					CON_LISTCONT
				WHERE
					TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'
					AND ID_TERMINAL 	= '$id_terminal'
					AND ID_CLASS_CODE   IN $id_class_code
					AND CONT_SIZE 		= '$size' 
					$q1
					AND QC_PLAN 		= (SELECT MCH_NAME FROM M_MACHINE WHERE ID_MACHINE = '$id_machine')";
			
		$response = $ci->db->query($sql)->row();

		return $response->JML;
	}
}

if(!function_exists('get_dtl_mch_sum_shift'))
{
	function get_dtl_mch_sum_shift($id_ves_voyage,$id_machine,$size,$sts_cont){
		$ci    		   =& get_instance();
		$id_terminal   = $ci->gtools->terminal();


		$q1 = "";
		if($sts_cont == 'RFR'){
			$q1 = "AND CONT_TYPE = '$sts_cont'";
		}elseif($sts_cont = 'OOG'){
			$q1 = "AND CONT_HEIGHT = '$sts_cont'";
		}else{
			$q1 = "AND CONT_STATUS = '$sts_cont'";
		}

		$sql = "SELECT
					COUNT(*) AS JML
				FROM
					CON_LISTCONT
				WHERE
					TRIM(ID_VES_VOYAGE) = '$id_ves_voyage'
					AND ID_CLASS_CODE IN ('S1','S2')
					$q1
					AND CONT_SIZE       = '$size'
					AND ID_TERMINAL 	= '$id_terminal'
					";

		$response = $ci->db->query($sql)->row();
		return $response->JML;


	}
}

if(!function_exists('get_hatch_ves'))
{
	function get_hatch_ves($id_ves_voyage){
		$ci    		   =& get_instance();
		$id_terminal   = $ci->gtools->terminal();

		$sql = "SELECT
					A.JUMLAH AS JML
				FROM
					ITOS_REPO.M_HATCH_MOVE A
				LEFT JOIN ITOS_OP.VES_VOYAGE B ON A.VOYAGE_IN = B.VOY_IN AND A.VOYAGE_OUT = B.VOY_OUT AND A.VESSEL = B.VESSEL_NAME
				WHERE B.ID_VES_VOYAGE = '$id_ves_voyage'";
		$response = $ci->db->query($sql)->row();
		return isset($response->JML) ? $response->JML : 0;


	}
}

if(!function_exists('get_suspend_mch'))
{
	function get_suspend_mch($id_ves_voyage,$id_suspend){
		$ci 			=& get_instance();
		$id_terminal 	=  $ci->gtools->terminal();

		$sql  			= "SELECT
			                    C.MCH_NAME,
			                    A.ID_SUSPEND,
			                    B.ACTIVITY,
			                    SUM(ROUND((A.END_SUSPEND - A.START_SUSPEND) * 24 * 60,0)) AS DIFF_MINUTES
			                FROM JOB_SUSPEND A
			                INNER JOIN M_SUSPEND B ON B.ID_SUSPEND=A.ID_SUSPEND
			                INNER JOIN M_MACHINE C ON C.ID_MACHINE=A.ID_MACHINE
			                WHERE A.ID_VES_VOYAGE = '$id_ves_voyage'
			                AND A.ID_SUSPEND = '$id_suspend'
			                GROUP BY C.MCH_NAME,
			                    A.ID_SUSPEND,
			                    B.ACTIVITY
			                ORDER BY C.MCH_NAME";
		$response 		= $ci->db->query($sql)->row();
		// if($id_suspend==0){
		// 	debux($sql);
		// }
		
		return isset($response->DIFF_MINUTES) ? $response->DIFF_MINUTES : 0;
	}
}

if(!function_exists('get_suspend_mch_idle'))
{
	function get_suspend_mch_idle($id_ves_voyage){
		$ci 			=& get_instance();
		$id_terminal    = $ci->gtools->terminal();

		$sql 			= "SELECT
					            C.MCH_NAME,
					            A.ID_SUSPEND,
					            B.ACTIVITY,
					            SUM(ROUND((A.END_SUSPEND - A.START_SUSPEND) * 24 * 60,0)) AS DIFF_MINUTES
					        FROM JOB_SUSPEND A
					        INNER JOIN M_SUSPEND B ON B.ID_SUSPEND=A.ID_SUSPEND
					        INNER JOIN M_MACHINE C ON C.ID_MACHINE=A.ID_MACHINE
					        WHERE A.ID_VES_VOYAGE = '$id_ves_voyage'
					        AND B.EQ_TYPE = 'QUAY' 
					       	AND B.CATEGORY = 'IDLE'
					        GROUP BY C.MCH_NAME,
					            A.ID_SUSPEND,
					            B.ACTIVITY
					        ORDER BY C.MCH_NAME";
		$response       = $ci->db->query($sql)->result();
		$ress 			= $ci->db->query($sql)->num_rows();

		//echo $id_ves_voyage.' - hasil : '.$ress;
		
		$ttl = 0;
		$dif_time = 0;
		if($ress != 0){
			foreach ($response as $key => $value) {
				$dif_time = $value->DIFF_MINUTES;
				$ttl += isset($dif_time) ? $dif_time : 0;
			}
		}

		return $ttl;
	}
}

if(!function_exists('get_dtl_mch_sum_all'))
{
	function get_dtl_mch_sum_all($id_ves_voyage,$id_machine){
		$ci    		   =& get_instance();
		$id_terminal   = $ci->gtools->terminal();
		
		
		$sql = "SELECT
					(SELECT
						COUNT(*) AS JML
					FROM
						CON_LISTCONT
					WHERE
						TRIM(ID_VES_VOYAGE) IN ($id_ves_voyage)
						AND ID_TERMINAL 	= '$id_terminal'
						AND ID_CLASS_CODE   IN ('I','TI')
						AND CONT_SIZE 		= '20' 
						AND CONT_STATUS     = 'FCL'
						AND QC_PLAN 		= (SELECT MCH_NAME FROM M_MACHINE WHERE ID_MACHINE = '$id_machine')) AS i_20fcl,
					(SELECT
						COUNT(*) AS JML
					FROM
						CON_LISTCONT
					WHERE
						TRIM(ID_VES_VOYAGE) IN ($id_ves_voyage)
						AND ID_TERMINAL 	= '$id_terminal'
						AND ID_CLASS_CODE   IN ('I','TI')
						AND CONT_SIZE 		= '20' 
						AND CONT_STATUS     = 'MTY'
						AND QC_PLAN 		= (SELECT MCH_NAME FROM M_MACHINE WHERE ID_MACHINE = '$id_machine')) AS i_20mty,
					(SELECT
						COUNT(*) AS JML
					FROM
						CON_LISTCONT
					WHERE
						TRIM(ID_VES_VOYAGE) IN ($id_ves_voyage)
						AND ID_TERMINAL 	= '$id_terminal'
						AND ID_CLASS_CODE   IN ('I','TI')
						AND CONT_SIZE 		= '40' 
						AND CONT_STATUS     = 'FCL'
						AND QC_PLAN 		= (SELECT MCH_NAME FROM M_MACHINE WHERE ID_MACHINE = '$id_machine')) AS i_40fcl,
					(SELECT
						COUNT(*) AS JML
					FROM
						CON_LISTCONT
					WHERE
						TRIM(ID_VES_VOYAGE) IN ($id_ves_voyage)
						AND ID_TERMINAL 	= '$id_terminal'
						AND ID_CLASS_CODE   IN ('I','TI')
						AND CONT_SIZE 		= '40' 
						AND CONT_STATUS     = 'MTY'
						AND QC_PLAN 		= (SELECT MCH_NAME FROM M_MACHINE WHERE ID_MACHINE = '$id_machine')) AS i_40mty,
					(SELECT
						COUNT(*) AS JML
					FROM
						CON_LISTCONT
					WHERE
						TRIM(ID_VES_VOYAGE) IN ($id_ves_voyage)
						AND ID_TERMINAL 	= '$id_terminal'
						AND ID_CLASS_CODE   IN ('E','TE')
						AND CONT_SIZE 		= '20' 
						AND CONT_STATUS     = 'FCL'
						AND QC_PLAN 		= (SELECT MCH_NAME FROM M_MACHINE WHERE ID_MACHINE = '$id_machine')) AS e_20fcl,
					(SELECT
						COUNT(*) AS JML
					FROM
						CON_LISTCONT
					WHERE
						TRIM(ID_VES_VOYAGE) IN ($id_ves_voyage)
						AND ID_TERMINAL 	= '$id_terminal'
						AND ID_CLASS_CODE   IN ('E','TE')
						AND CONT_SIZE 		= '20' 
						AND CONT_STATUS     = 'MTY'
						AND QC_PLAN 		= (SELECT MCH_NAME FROM M_MACHINE WHERE ID_MACHINE = '$id_machine')) AS e_20mty,
					(SELECT
						COUNT(*) AS JML
					FROM
						CON_LISTCONT
					WHERE
						TRIM(ID_VES_VOYAGE) IN ($id_ves_voyage)
						AND ID_TERMINAL 	= '$id_terminal'
						AND ID_CLASS_CODE   IN ('E','TE')
						AND CONT_SIZE 		= '40' 
						AND CONT_STATUS     = 'FCL'
						AND QC_PLAN 		= (SELECT MCH_NAME FROM M_MACHINE WHERE ID_MACHINE = '$id_machine')) AS e_40fcl,
					(SELECT
						COUNT(*) AS JML
					FROM
						CON_LISTCONT
					WHERE
						TRIM(ID_VES_VOYAGE) IN ($id_ves_voyage)
						AND ID_TERMINAL 	= '$id_terminal'
						AND ID_CLASS_CODE   IN ('E','TE')
						AND CONT_SIZE 		= '40' 
						AND CONT_STATUS     = 'MTY'
						AND QC_PLAN 		= (SELECT MCH_NAME FROM M_MACHINE WHERE ID_MACHINE = '$id_machine')) AS e_40mty
				FROM DUAL";

		$response = $ci->db->query($sql)->row_array();
		//debux($sql);
		return $response;
	}
}

if(!function_exists('get_sum_suspend_mch'))
{
	function get_sum_suspend_mch($id_ves_voyage,$id_suspend){
		$ci 			=& get_instance();
		$id_terminal 	=  $ci->gtools->terminal();

		$sql  			= "SELECT
			                    C.MCH_NAME,
			                    A.ID_SUSPEND,
			                    B.ACTIVITY,
			                    SUM(ROUND((A.END_SUSPEND - A.START_SUSPEND) * 24 * 60,0)) AS DIFF_MINUTES
			                FROM JOB_SUSPEND A
			                INNER JOIN M_SUSPEND B ON B.ID_SUSPEND=A.ID_SUSPEND
			                INNER JOIN M_MACHINE C ON C.ID_MACHINE=A.ID_MACHINE
			                WHERE A.ID_VES_VOYAGE IN ($id_ves_voyage)
			                AND A.ID_SUSPEND = '$id_suspend'
			                GROUP BY C.MCH_NAME,
			                    A.ID_SUSPEND,
			                    B.ACTIVITY
			                ORDER BY C.MCH_NAME";
		$response 		= $ci->db->query($sql)->row();
		return isset($response->DIFF_MINUTES) ? $response->DIFF_MINUTES : 0;
	}
}

if(!function_exists('getPodOutbound'))
{
	function getPodOutbound($id_ves_voyage){
		$ci 			=& get_instance();
		$id_terminal 	=  $ci->gtools->terminal();

		$sql  			= "SELECT ID_POD FROM CON_LISTCONT WHERE ID_VES_VOYAGE = '$id_ves_voyage' AND ID_TERMINAL = $id_terminal
							AND ID_CLASS_CODE IN ('E','TE','TC','S1','S2')
							AND ID_OP_STATUS <> 'DIS'
							--AND YD_YARD IS NOT NULL
							GROUP BY ID_POD";
		$response 		= $ci->db->query($sql)->result_array();
		return $response;
	}
}

if(!function_exists('getDataOutboundBySize'))
{
	function getDataOutboundBySize($id_ves_voyage,$size,$id_pod){
		$ci 			=& get_instance();
		$id_terminal 	=  $ci->gtools->terminal();

		$sql  			= "SELECT
								C.NO_CONTAINER,
								C.CONT_SIZE,
								C.CONT_TYPE,
								C.CONT_STATUS,
								C.HAZARD,
								C.ID_POD,
								C.CONT_HEIGHT,
								C.ID_COMMODITY,
								CASE
									WHEN MOD(NVL(C.VS_BAY, E.BAY_),2) = 0 THEN NVL(C.VS_BAY, E.BAY_)+ 1
									ELSE NVL(C.VS_BAY, E.BAY_)
								END VSB_BAY
							FROM CON_LISTCONT C
							JOIN VES_VOYAGE VV ON VV.ID_VES_VOYAGE = C.ID_VES_VOYAGE
							LEFT JOIN CON_OUTBOUND_SEQUENCE E ON C.NO_CONTAINER = E.NO_CONTAINER AND C.POINT = E.POINT
							WHERE
								C.ID_VES_VOYAGE = '$id_ves_voyage'
								AND C.ID_TERMINAL = '$id_terminal'
								AND C.ID_CLASS_CODE IN ('E','TE','TC','S1','S2')
								AND C.ID_OP_STATUS <> 'DIS'
								AND C.CONT_SIZE = '$size'
								--AND E.BAY_ IS NOT NULL
								AND C.ID_POD = '$id_pod'
								AND
								CASE
									WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
									THEN C.POINT
									ELSE 1
								END <>
								CASE
									WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
									THEN (SELECT POINT FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER)
									ELSE 0
								END";
		//debux($sql);
		$response 		= $ci->db->query($sql)->result_array();
		return $response;
	}
}

if(!function_exists('getDataOutboundBySize2021'))
{
	function getDataOutboundBySize2021($id_ves_voyage,$id_pod){
		$ci 			=& get_instance();
		$id_terminal 	=  $ci->gtools->terminal();

		$sql  			= "SELECT
								C.NO_CONTAINER,
								C.CONT_SIZE,
								C.CONT_TYPE,
								C.CONT_STATUS,
								C.HAZARD,
								C.ID_POD,
								C.CONT_HEIGHT,
								C.ID_COMMODITY,
								CASE
									WHEN MOD(NVL(C.VS_BAY, E.BAY_),2) = 0 THEN NVL(C.VS_BAY, E.BAY_)+ 1
									ELSE NVL(C.VS_BAY, E.BAY_)
								END VSB_BAY
							FROM CON_LISTCONT C
							JOIN VES_VOYAGE VV ON VV.ID_VES_VOYAGE = C.ID_VES_VOYAGE
							LEFT JOIN CON_OUTBOUND_SEQUENCE E ON C.NO_CONTAINER = E.NO_CONTAINER AND C.POINT = E.POINT
							WHERE
								C.ID_VES_VOYAGE = '$id_ves_voyage'
								AND C.ID_TERMINAL = '$id_terminal'
								AND C.ID_CLASS_CODE IN ('E','TE','TC','S1','S2')
								AND C.ID_OP_STATUS <> 'DIS'
								AND C.CONT_SIZE IN ('20','21')
								--AND E.BAY_ IS NOT NULL
								AND C.ID_POD = '$id_pod'
								AND
								CASE
									WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
									THEN C.POINT
									ELSE 1
								END <>
								CASE
									WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
									THEN (SELECT POINT FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER)
									ELSE 0
								END";
		//debux($sql);
		$response 		= $ci->db->query($sql)->result_array();
		return $response;
	}
}


if(!function_exists('getDataOutboundHC'))
{
	function getDataOutboundHC($id_ves_voyage,$id_pod){
		$ci 			=& get_instance();
		$id_terminal 	=  $ci->gtools->terminal();

		$sql  			= "SELECT
								C.NO_CONTAINER,
								C.CONT_SIZE,
								C.CONT_TYPE,
								C.CONT_STATUS,
								C.HAZARD,
								C.ID_POD,
								C.CONT_HEIGHT,
								C.ID_COMMODITY,
								CASE
									WHEN MOD(NVL(C.VS_BAY, E.BAY_),2) = 0 THEN NVL(C.VS_BAY, E.BAY_)+ 1
									ELSE NVL(C.VS_BAY, E.BAY_)
								END VSB_BAY
							FROM CON_LISTCONT C
							JOIN VES_VOYAGE VV ON VV.ID_VES_VOYAGE = C.ID_VES_VOYAGE
							LEFT JOIN CON_OUTBOUND_SEQUENCE E ON C.NO_CONTAINER = E.NO_CONTAINER AND C.POINT = E.POINT
							WHERE
								C.ID_VES_VOYAGE = '$id_ves_voyage'
								AND C.ID_TERMINAL = '$id_terminal'
								AND C.ID_CLASS_CODE IN ('E','TE','TC','S1','S2')
								AND C.ID_OP_STATUS <> 'DIS'
								AND C.CONT_SIZE = '45'
								--AND E.BAY_ IS NOT NULL
								AND C.ID_POD = '$id_pod'
								AND C.CONT_TYPE = 'HQ'
								AND
								CASE
									WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
									THEN C.POINT
									ELSE 1
								END <>
								CASE
									WHEN (C.ID_CLASS_CODE = 'S1' OR C.ID_CLASS_CODE = 'S2') AND (SELECT COUNT(*) FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER) > 0
									THEN (SELECT POINT FROM JOB_SHIFTING WHERE ID_VES_VOYAGE = C.ID_VES_VOYAGE AND NO_CONTAINER = C.NO_CONTAINER)
									ELSE 0
								END";
		$response 		= $ci->db->query($sql)->result_array();
		return $response;
	}
}

