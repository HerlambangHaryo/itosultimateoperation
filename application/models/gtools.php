<?php

class Gtools extends CI_Model {

    public function __construct() {
	$this->load->database();
	$this->load->library('session');
    }

    public function terminal() {
	   return $this->session->userdata('terminal');
    }
    
    public function terminal_name() {
	   return $this->session->userdata('terminal_name');
    }
    
    public function update_terminal(){
	$id_terminal = ID_TERMINAL;
	
	$qry = "UPDATE CON_CHANGESTATUS_STG SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_CORRECTION_HIST SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_CORRECTION_STG SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_DATACHANGE SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_DISABLE_STG SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_HKP_PLAN_D SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_HKP_PLAN_H SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_INBOUND_SEQUENCE SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_LISTCONT SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_LISTCONT_HIST SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_LOADING_CANCEL_D SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_LOADING_CANCEL_H SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_NPE SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_OUTBOUND_SEQUENCE SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_RENAME_STG SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_TRANSHIPMENT_D SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE CON_TRANSHIPMENT_H SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE DOC_STOWAGEPRINT SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE DSH_EDI_COARRI SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE DSH_EDI_CODECO SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE EDI_BAPLIE SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE EDI_COARRI SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE EDI_CODECO SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE EDI_GENERATE_LOGFILE SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_BEHANDLE SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_CONFIRM SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_GATE_INSPECTION SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_GATE_MANAGER SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_HATCH SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_MACHINE_OPERATOR SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_MANAGER_SUMMARY SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_PICKUP SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_PLACEMENT SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_PLACEMENT_HISTORY SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_QUAY_MANAGER SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_REEFER_MANAGER SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_REEFER_PLUG SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_SHIFTING SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_SUSPEND SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_TRUCK_INOUT SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE JOB_YARD_MANAGER SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE LOG_CON_LISTCONT SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE M_COUNTER_ID_VES_VOYAGE SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE M_GATE_LANE SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE M_HATCH_MOVE SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE M_HEAPZONE SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE M_KADE_TB SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE M_MACHINE SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE M_PAWEIGHT_H SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE M_PLAN_CATEGORY_H SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE M_POOL_D SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE M_POOL_H SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE M_SHIFTING SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE M_STEVEDORING SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE M_YARD SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE VES_VOYAGE_CWP SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
	$qry = "UPDATE VES_VOYAGE_MON SET ID_TERMINAL = $id_terminal WHERE ID_TERMINAL IS NULL OR ID_TERMINAL = 0"; $this->db->query($qry);
    }
}
