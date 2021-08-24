<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Housekeeping_plan extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('container');
		$this->load->model('master');
		$this->load->model('gtools');
		$this->load->library('session');
	}
	
	public function index(){
		$data['tab_id'] = $_GET['tab_id'];
		
		if ($this->session->userdata('id_user')!=''){
			$this->load->view('templates/housekeeping_plan/hk_plan_panel', $data);
		}
	}
	
	public function data_mv_order()
	{
		$data	= $this->master->getDataMvOrder();
		echo json_encode($data);
	}
	
	public function data_virt_crane()
	{
		$data	= $this->master->getDataMvCrane();
		echo json_encode($data);
	}
	
	public function create_hkplan()
	{
		$data	= $this->container->create_hk_plan();
		//echo json_encode($data); typeerror a is null=response not available
		echo 'ok';
	}
	
	public function load_hkplan($q)
	{
		$data['tab_id'] = $q;

		if ($this->session->userdata('id_user')!=''){
			$this->load->view('templates/housekeeping_plan/grid_panel', $data);
		}
	}
	
	public function load_detail_container_hk($a,$b,$c)
	{
		$data['tab_id'] = $a;
		$data['hkp_id'] = $b;
		$data['hkp_name'] = urldecode($c);
		
		if ($this->session->userdata('id_user')!=''){
			$this->load->view('templates/housekeeping_plan/detail_container', $data);
		}
		else
		{
			echo 'session expired';
		}
		
	}
	
	public function load_hkplan_grid()
	{
		$data	= $this->container->content_hk_grid();
		
		echo json_encode($data);
	}
	
	public function srch_cont_hk()
	{
		$data	= $this->container->get_data_container_hk();
		
		echo json_encode($data);
	}
	
	public function insert_container_hk()
	{
		$data	= $this->container->insert_container_hk();
		
		echo json_encode($data);
	}
	
	public function load_hkplan_gridcont($hkp_id)
	{
		$data	= $this->container->content_hk_gridcont($hkp_id);
		//PRINT_R($data);die;
		//PRINT_R(count($data['data']));die;
		for($i=0;$i<count($data['data']);$i++)
		{

			$comment='Delete';
			$no_container=$data['data'][$i]['NO_CONTAINER'];
			$point=$data['data'][$i]['POINT'];
			$id=$data['data'][$i]['HKP_ID'];
			if($data['data'][$i]['HKP_STATUS_CONT']!='N')
			{
				$act="<i>".$data['data'][$i]['STATUS_NAME']."</i>";
			}
			else
			{
				$act="<button onclick='delete_cont_hkp(\"$hkp_id\",\"$no_container\",\"$point\")'>$comment</button>";
			}
			$data['data'][$i]['ACTION']=$act;
			
		}
		
		echo json_encode($data);
	}
	
	public function add_cont_hk($a,$b)
	{
		$data['tab_id'] = $a;
		$data['hkp_id'] = $b;
		
		if ($this->session->userdata('id_user')!=''){
			$this->load->view('templates/housekeeping_plan/add_container', $data);
		}
		else
		{
			echo 'session expired';
		}
		
	}
	
	public function del_cont_hk()
	{
		$data	= $this->container->del_container_hk();
		
		echo json_encode($data);
	}
	
	public function data_container_hk(){
		$point = false;
		if (isset($_POST['point'])) {
			$point = $_POST['point'];
		}
		$retval = $this->container->get_data_container_hkp($_POST['no_container'], $point);
		
		$data = array(
			'success'=>false,
			'errors'=>'container not found error'
		);
		
		if ($retval){
			$data['success']=true;
			$data['errors']='';
			$data['data']=json_encode($retval);
		}
		
		echo json_encode($data);
	}
        
	
	public function save_plan_housekeeping(){
		
		$data = $this->container->plan_container_hkp();
		
		echo json_encode($data);
	}
	
	public function activate_hkp(){
		
		$data = $this->container->activate_hkp();
		
		echo json_encode($data);
	}
	public function deactivate_hkp(){
		
		$data = $this->container->deactivate_hkp();
		
		echo json_encode($data);
	}
}