<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=vessel_volume_reports.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<style>
.Middle{
}
.CenterAndMiddle{
	text-align: center;
	vertical-align:middle;
}

/*table styling*/
table, td, th {  
  border: 1px solid #ddd;
  text-align: left;
}

table {
  border-collapse: collapse;
  /*width: 100%;*/
}

th, td {
  padding: 7px;
}

.spacer{
	padding-bottom: 30px;
}

.abu-abu
{
	background-color: #303030 !important;
	color: #ffffff !important;
}

.abu-terang
{
	background-color: #aaaaaa !important;
	font-weight: bold !important;
}

</style>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>REPORT VESSEL PRODUCTION VOLUME AND PRODUCTIVITY REPORT DATABASE</title>
</head>
<body>

<table cellpadding="2" cellspacing="2" style="border:0px !important">
    <tr><td colspan='10' bgcolor="#CCCCCC" style='text-align: center; vertical-align:middle; border:0px !important;'>VESSEL PRODUCTION VOLUME AND PRODUCTIVITY REPORT DATABASE</td></tr>
    <tr><td colspan='10' bgcolor="#CCCCCC" style='text-align: left; vertical-align:middle; border:0px !important;'>TP2 DOMESTIK</td></tr>
    <tr><td colspan='10' bgcolor="#CCCCCC" style='text-align: left; vertical-align:middle; border:0px !important;'><?=$year?></td></tr>
</table>

<div class="spacer">
<?php for ($i=0; $i <=1 ; $i++) { ?>
    <table cellpadding="2" cellspacing="2" style="border:0px !important;">
        <tr>
            <td style="border:0px !important;" >&nbsp;</td>
        </tr>
    </table>
<?php } ?>  
</div>

<?php 
//die('test');
?>

<table cellpadding="2" cellspacing="2" border="1" style="width:100%">
    <tr style='vertical-align:middle;' bgcolor="#FFFF00">
        <th rowspan="2">No</th>
        <th rowspan="2">Year</th>
        <th rowspan="2">Vessel Name</th>
        <th rowspan="2">Berth</th>
        <th rowspan="2">Voyage</th>
        <th rowspan="2">Voy In</th>
        <th rowspan="2">Voy Out</th>
        <th rowspan="2">OPR</th>
        <th rowspan="2">V.Service</th>
        <th rowspan="2">Month</th>
        <!-- <th>aMonth (1-25)</th> -->
        <th rowspan="2">Week (ATD)</th>
        <th rowspan="2">ATB</th>
        <th rowspan="2">Commence</th>
        <th rowspan="2">Complete</th>
        <th rowspan="2">ATD</th>
        <th rowspan="2">Closing Time</th>
        <th rowspan="2">Berthside</th>
        <th rowspan="2">Start Pos</th>
        <th rowspan="2">Brdg Pos</th>
        <th rowspan="2">Shipping Line</th>

        <?php 
            $detail_mch = $this->vessel->get_all_machines();
            foreach($detail_mch as $keyx => $valuex){ ?>
                <th colspan='8' style="text-align: center !important"><?=$valuex->MCH_NAME?></th>
        <?php } ?>
           
        <th colspan="24" style="text-align: center !important">SUMMARY</th>
        <th colspan="11" style="text-align: center !important">MOVEMENT</th>
        <th colspan="16" style="text-align: center !important">PERFORMANCE</th>
        <th colspan="7" style="text-align: center !important">NOT</th>
        <th colspan="24" style="text-align: center !important">IDLE TIME</th>

            <!-- TOTAL BOX PER MESIN -->
             <?php 
                $detail_mch = $this->vessel->get_all_machines();
                foreach($detail_mch as $keyx => $valuex){ ?>
                    <th rowspan="2"><?php echo $valuex->MCH_NAME.' BOX'; ?></th>
            <?php } ?>
            <!-- END TOTAL BOX PER MESIN -->

            <!-- TOTAL TEUS PER MESIN -->
             <?php 
                //$detail_mch = $this->vessel->get_all_machines();
                foreach($detail_mch as $keyx => $valuex){ ?>
                    <th rowspan="2"><?php echo $valuex->MCH_NAME.' TEUS'; ?></th>
            <?php } ?>
            <!-- END TOTAL TEUS PER MESIN -->
        
        <tr style='vertical-align:middle;' bgcolor="#FA8585">
            <?php 
                $detail_mch = $this->vessel->get_all_machines();
                foreach($detail_mch as $keyx => $valuex){ ?>
                    <th><?php echo $valuex->MCH_NAME.' 20  Full Disch'; ?></th>
                    <th><?php echo $valuex->MCH_NAME.' 20  MT Disch'; ?></th>
                    <th><?php echo $valuex->MCH_NAME.' 40  Full Disch'; ?></th>
                    <th><?php echo $valuex->MCH_NAME.' 40  MT Disch'; ?></th>
                    <th><?php echo $valuex->MCH_NAME.' 20  Full Load'; ?></th>
                    <th><?php echo $valuex->MCH_NAME.' 20  MT Load'; ?></th>
                    <th><?php echo $valuex->MCH_NAME.' 40  Full Load'; ?></th>
                    <th><?php echo $valuex->MCH_NAME.' 40  MT Load'; ?></th>
            <?php } ?>

            <th>20 Full Disch</th>
            <th>20 MT Disch</th>
            <th>40 Full Disch</th>
            <th>40 MT Disch</th>
            <th>20 Full Load</th>
            <th>20 MT Load</th>
            <th>40 Full Load</th>
            <th>40 MT Load</th>
            <th>20 Full DL</th>
            <th>20 MT DL</th>
            <th>40 Full DL</th>
            <th>40 MT DL</th>

            <!-- Cont RF,OOG,dan Shifting -->
            <th>20 RF Load</th>
            <th>40 RF Load</th>
            <th>20 OOG Load</th>
            <th>40 OOG Load</th>
            <th>20 Full Shifting</th>
            <th>20 MT Shifting</th>
            <th>40 Full Shifting</th>
            <th>40 MT Shifting</th>
            <th>20 RF Shifting</th>
            <th>40 RF Shifting</th>
            <th>20 OOG Shifting</th>
            <th>40 OOG Shifting</th>
            <!-- End  Cont RF,OOG,dan Shifting -->


            <!-- Start Movement -->
            <th>Hatch Cover </th>
            <th>Disch (Box) </th>
            <th>Disch (Teu's) </th>
            <th>Load (Box) </th>
            <th>Load (Teu's) </th>
            <th>Shifting (Box) </th>
            <th>Total Box </th>
            <th>Total Teus</th>
            <th>Total Box w/o Shifting</th>
            <th>Total Teus w/o Shifting</th>
            <th>Movement</th>
            <!-- End Movement -->

            <th>Berth Time</th>  
            <th>Vessel Working Time</th>

            <!--  -->
            <th>Gross Crane Time</th>
            <th>Crane Ratio</th>
            <!-- <th>Crane Idle</th>
            <th>Net Crane Time (ET)</th>
            <th>BPR (mph) </th> -->
            <th>BCH</th>
            <th>BSH (BWT) (bph)</th>
            <th>BSH (BT) (bph)</th>
            <th>BSH (ET) (bph)</th>
            <th>GCR (mph)</th>
            <th>VOR (mph)</th>
            <th>NOT (preparation  + sailing)</th>
            <th>NOT</th>
            <th>IT</th>
            <th>ET</th>
            <th>ET/BT(%)</th>
            <th>BWT/BT (%)</th>

            <!--START SUSPEND TIME NOT-->
            <?php 
                $detail_suspend = $this->vessel->get_all_suspend();
                foreach($detail_suspend as $keyx => $valuex){ ?>
                    <?php if($valuex->CATEGORY == 'NOT'){ ?>
                        <th><?php echo $valuex->ACTIVITY; ?></th>
                    <?php } ?>
            <?php } ?>
            <!-- END SUSPEND TIME NOT-->

            <!--START SUSPEND TIME IDLE-->
            <?php 
                $detail_suspend = $this->vessel->get_all_suspend();
                foreach($detail_suspend as $keyx => $valuex){ ?>
                    <?php if($valuex->CATEGORY != 'NOT'){ ?>
                        <th><?php echo $valuex->ACTIVITY; ?></th>
                    <?php } ?>
            <?php } ?>
            <!-- END SUSPEND TIME IDLE-->
        </tr>

        

        
</th>

    </tr>
    <?php
    //debux($vessel1);
    $no=1;
    $id_ves_voyage = array();
    foreach ($vessel1 as $key => $value) {
        $id_ves_voyage[] = $value->ID_VES_VOYAGE;
        //echo"test ".$value->ID_VES_VOYAGE;
    ?>
        <tr>
            <td><?=$no?></td>
            <td><?=$value->YEAR?></td>
            <td><?=$value->VESSEL?></td>
            <td><?=$value->BERTH?></td>
            <td><?=$value->VOYAGE?></td>
            <td><?=$value->VOYAGE_IN?></td>
            <td><?=$value->VOYAGE_OUT?></td>
            <td><?=$value->OPERATOR_ID?></td>
            <td><?=$value->IN_SERVICE?></td>
            <!-- <td><?=$value->MONTH?></td> -->
            <td><?=$value->MONTH?></td>
            <td><?=$value->WEEK_ATD?></td>
            <td><?=$value->ATB?></td>

            <?php if($value->DISC_COMMENCE == NULL){
                $commence = $value->LOAD_COMMENCE; 
            }else if($value->LOAD_COMMENCE == NULL){
                $commence = $value->DISC_COMMENCE;
            }else{
                if(strtotime($value->DISC_COMMENCE) < strtotime($value->LOAD_COMMENCE)){
                    $commence = $value->DISC_COMMENCE;
                }else{
                    $commence = $value->LOAD_COMMENCE;
                }
            }

            if($value->DISC_COMPLETE == NULL){
                $complete = $value->LOAD_COMPLETE; 
            }else if($value->LOAD_COMPLETE == NULL){
                $complete = $value->DISC_COMPLETE;
            }else{
                if(strtotime($value->DISC_COMPLETE) > strtotime($value->LOAD_COMPLETE)){
                    $complete = $value->DISC_COMPLETE;
                }else{
                    $complete = $value->LOAD_COMPLETE;
                }
            }

             ?>

            <td><?=$commence?></td>
            <td><?=$complete?></td>
            <td><?=$value->ATD?></td>
            <td><?=$value->CLOSING_TIME?></td>
            <td><?=$value->ALONG_SIDE?></td>
            <td><?=$value->START_POS?></td>
            <td><?=$value->BRDG_POS?></td>
            <td><?=$value->SHIPPING_LINE?></td>

            <!-- start machine  -->
        <?php 
            $detail_mch = $this->vessel->get_all_machines();
            $_20_i_fcl = 0;
            $_20_i_mty = 0;
            $_40_i_fcl = 0;
            $_40_i_mty = 0;
            $_20_e_fcl = 0;
            $_20_e_mty = 0;
            $_40_e_fcl = 0;
            $_40_e_mty = 0;
            $_20_fcl   = 0;
            $_20_mty   = 0;
            $_40_fcl   = 0;
            $_40_mty   = 0;
            $_20_e_rfr = 0;
            $_40_e_rfr = 0;

            $_20_e_oog = 0;
            $_40_e_oog = 0;

            $_20_sf_fcl = 0;
            $_20_sf_mty = 0;
            $_40_sf_fcl = 0;
            $_40_sf_mty = 0;

            $_20_rf_sf  = 0;
            $_40_rf_sf  = 0;

            $_20_oog_sf  = 0;
            $_40_oog_sf  = 0;

            $_hatch_ves = 0;

            $fcl_disc = 0;
            $fcl_load = 0;
            $ttl_box  = 0;

            $id0 = 0;
            $id1 = 0;
            $idttl = 0;

            $box_per_mch        = array();
            $box_per_mch_teu    = array();
            $data_average       = array();
            
        foreach($detail_mch as $keyx => $valuex){ 
            $i_fcl20 = get_dtl_mch($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'20','I','FCL');
            $i_mty20 = get_dtl_mch($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'20','I','MTY');
            $i_fcl40 = get_dtl_mch($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'40','I','FCL');
            $i_mty40 = get_dtl_mch($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'40','I','MTY');
            $e_fcl20 = get_dtl_mch($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'20','E','FCL');
            $e_mty20 = get_dtl_mch($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'20','E','MTY');
            $e_fcl40 = get_dtl_mch($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'40','E','FCL');
            $e_mty40 = get_dtl_mch($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'40','E','MTY');
            
            $_20_i_fcl  += $i_fcl20;
            $_20_i_mty  += $i_mty20;
            $_40_i_fcl  += $i_fcl40;
            $_40_i_mty  += $i_mty40;
            $_20_e_fcl  += $e_fcl20;
            $_20_e_mty  += $e_mty20;
            $_40_e_fcl  += $e_fcl40;
            $_40_e_mty  += $e_mty40;
            $_20_e_rfr  += get_dtl_mch_sum($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'20','E','RFR');
            $_40_e_rfr  += get_dtl_mch_sum($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'40','E','RFR');
            $_20_e_oog  += get_dtl_mch_sum($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'20','E','OOG');
            $_40_e_oog  += get_dtl_mch_sum($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'20','E','OOG');
            $_20_sf_fcl += get_dtl_mch_sum_shift($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'20','FCL');
            $_20_sf_mty += get_dtl_mch_sum_shift($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'20','MTY');
            $_40_sf_fcl += get_dtl_mch_sum_shift($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'40','FCL');
            $_40_sf_mty += get_dtl_mch_sum_shift($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'40','MTY');
            $_20_rf_sf  += get_dtl_mch_sum_shift($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'20','RFR');
            $_40_rf_sf  += get_dtl_mch_sum_shift($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'40','RFR');
            $_20_oog_sf += get_dtl_mch_sum_shift($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'20','OOG');
            $_40_oog_sf += get_dtl_mch_sum_shift($value->ID_VES_VOYAGE,$valuex->ID_MACHINE,'40','OOG');
            $_hatch_ves = get_hatch_ves($value->ID_VES_VOYAGE);

            $arr[$value->ID_VES_VOYAGE]['i20fcl'][] = $i_fcl20;
            $arr[$value->ID_VES_VOYAGE]['i20mty'][] = $i_mty20;
            $arr[$value->ID_VES_VOYAGE]['i40fcl'][] = $i_fcl40;
            $arr[$value->ID_VES_VOYAGE]['i40mtyl'][] = $i_mty40;
            $arr[$value->ID_VES_VOYAGE]['e20fcl'][] = $e_fcl20;
            $arr[$value->ID_VES_VOYAGE]['e20mty'][] = $e_mty20;
            $arr[$value->ID_VES_VOYAGE]['e40fcl'][] = $e_fcl40;
            $arr[$value->ID_VES_VOYAGE]['e40mty'][] = $e_mty40;
           
        ?>
            <td><?php echo $i_fcl20; ?></td>
            <td><?php echo $i_mty20; ?></td>
            <td><?php echo $i_fcl40; ?></td>
            <td><?php echo $i_mty40; ?></td>
            <td><?php echo $e_fcl20; ?></td>
            <td><?php echo $e_mty20; ?></td>
            <td><?php echo $e_fcl40; ?></td>
            <td><?php echo $e_mty40; ?></td>

        <?php 

            $box_per_mch[$valuex->ID_MACHINE] = $i_fcl20 + $i_mty20 + $i_fcl40 + $i_mty40 +  $e_fcl20 + $e_mty20 + $e_fcl40 + $e_mty40;

            $box_per_mch_teu[$valuex->ID_MACHINE] = $i_fcl20 + $i_mty20 + $i_fcl40 + $i_mty40 +  $e_fcl20 + $e_mty20 + $e_fcl40 + $e_mty40 + $_40_i_fcl + $_40_i_mty + $_40_e_fcl + $_40_e_mty;
        }

        foreach($box_per_mch as $key => $valueq){
            $s_box_per_mch[$key] += $valueq;  
        }

        foreach($box_per_mch_teu as $key => $valuew){
            $s_box_per_mch_teu[$key] += $valuew;
        }

        $_20_fcl   = $_20_i_fcl + $_20_e_fcl;
        $_20_mty   = $_20_i_mty + $_20_e_mty;
        $_40_fcl   = $_40_i_fcl + $_40_e_fcl;
        $_40_mty   = $_40_i_mty + $_40_e_mty;

        $fcl_disc = $_20_i_fcl + $_40_i_fcl;
        $fcl_load = $_20_e_fcl + $_40_e_fcl;

        /*CT    = SUMMARY 40  Full Disch
        CU8 = SUMMARY 40  MT Disch*/

        $disch_teu = $fcl_disc + $_40_i_mty + $_40_i_fcl;
        $load_teu = $fcl_load  + $_40_e_mty  + $_40_e_fcl;

        /*DL = 20  Full Shifting
          DS = 40  OOG Shifting*/

        $ttl_sf   = $_20_sf_fcl + $_40_oog_sf;
        $ttl_box  = $fcl_disc + $fcl_load;

        $ttl_box_wo_sf = $ttl_box - $ttl_sf;

        #movement
        $t_movement =  $_hatch_ves + $ttl_box;

        #berth time
        //$berth_time = ($value->ATD - $value->ATB) * 24;
        if($value->BERTH_TIME){
            $berth_time   = $value->BERTH_TIME;
        }else{
            $berth_time = 0;
        }

        if($value->DIFF_LOADING){
             $working_time = $value->DIFF_LOADING;
        }else{
            $working_time = 0;
        }
        

        #working time
        //$working_time = ($value->COMPLETE - $value->COMMENCE) * 24;
       
    
        ?>

            <!-- end machine  -->

            <?php
                /*data sum*/
                $sum_20_i_fcl += $_20_i_fcl;
                $sum_20_i_mty += $_20_i_mty;
                $sum_40_i_fcl += $_40_i_fcl;
                $sum_40_i_mty += $_40_i_mty;
                $sum_20_e_fcl += $_20_e_fcl;
                $sum_20_e_mty += $_20_e_mty;
                $sum_40_e_fcl += $_40_e_fcl;
                $sum_40_e_mty += $_40_e_mty;
                $sum_20_fcl += $_20_fcl;
                $sum_20_mty += $_20_mty;
                $sum_40_fcl += $_40_fcl;
                $sum_40_mty += $_40_mty;
                $sum_20_e_rfr += $_20_e_rfr;
                $sum_40_e_rfr += $_40_e_rfr;
                $sum_20_e_oog += $_20_e_oog;
                $sum_40_e_oog += $_40_e_oog;
                $sum_20_sf_fcl += $_20_sf_fcl;
                $sum_20_sf_mty += $_20_sf_mty;
                $sum_40_sf_fcl += $_40_sf_mty;
                $sum_40_sf_mty += $_40_sf_mty;
                $sum_20_rf_sf += $_20_rf_sf;
                $sum_40_rf_sf += $_40_rf_sf;
                $sum_20_oog_sf += $_20_oog_sf;
                $sum_40_oog_sf += $_40_oog_sf;
                $sum_hatch_ves += $_hatch_ves;

                $sum_fcl_disc += $fcl_disc;
                $sum_disch_teu += $disch_teu;
                $sum_fcl_load += $fcl_load;
                $sum_load_teu += $load_teu;
                $sum_ttl_sf += $ttl_sf;
                $sum_ttl_box += $ttl_box;
                $sum_ttl_box_wo_sf += $ttl_box_wo_sf;
                $sum_t_movement += $t_movement;
                $sum_berth_time += $berth_time;
                $sum_working_time += $working_time;
            ?>

            <!-- summary machine -->
            <td><?=$_20_i_fcl?></td>
            <td><?=$_20_i_mty?></td>
            <td><?=$_40_i_fcl?></td>
            <td><?=$_40_i_mty?></td>
            <td><?=$_20_e_fcl?></td>
            <td><?=$_20_e_mty?></td>
            <td><?=$_40_e_fcl?></td>
            <td><?=$_40_e_mty?></td>
            <td><?=$_20_fcl?></td>
            <td><?=$_20_mty?></td>
            <td><?=$_40_fcl?></td>
            <td><?=$_40_mty?></td>
            <td><?=$_20_e_rfr?></td>
            <td><?=$_40_e_rfr?></td>
            <td><?=$_20_e_oog?></td>
            <td><?=$_40_e_oog?></td>
            <td><?=$_20_sf_fcl?></td>
            <td><?=$_20_sf_mty?></td>
            <td><?=$_40_sf_fcl?></td>
            <td><?=$_40_sf_mty?></td>
            <td><?=$_20_rf_sf?></td>
            <td><?=$_40_rf_sf?></td>
            <td><?=$_20_oog_sf?></td>
            <td><?=$_40_oog_sf?></td>
            <td><?=$_hatch_ves?></td>
            <!-- end summary machine -->

            <td><?=$fcl_disc?></td>
            <td><?=$disch_teu?></td>

            <td><?=$fcl_load?></td>
            <td><?=$load_teu?></td>

            <td><?=$ttl_sf?></td>
            <td><?=$ttl_box?></td>

            <!-- total teus -->
            <td><?=$ttl_box?></td>
            <!-- end total teus -->

            <td><?=$ttl_box_wo_sf?></td>

            <!-- total teus wo sf -->
            <td><?=$ttl_box_wo_sf?></td>
            <!-- end total teus wo sf -->

            <td><?=$t_movement?></td>
            <td><?=$berth_time?></td>
            <td><?=$working_time?></td>


            <!-- start config suspend  -->
            <?php 
            //echo"test ".$value->ID_VES_VOYAGE;
            foreach($detail_suspend as $keyx => $valuex){
                    if($valuex->ID_SUSPEND == '0'){
                        $id0  = get_suspend_mch($value->ID_VES_VOYAGE,$valuex->ID_SUSPEND);
                        $ids0 = $id0;      
                    }elseif ($valuex->ID_SUSPEND == '4') {
                        $id1 = get_suspend_mch($value->ID_VES_VOYAGE,$valuex->ID_SUSPEND);
                        $ids1 = $id1;
                    }elseif ($valuex->ID_SUSPEND == '1') {
                        $ids2 = get_suspend_mch($value->ID_VES_VOYAGE,$valuex->ID_SUSPEND);
                    }
                ?>
            <?php } 
                $idttl  = $value->DIFF_DISCHARGE - ($id0 + $id1); 
                $idttls = ($ids0 + $ids1 + $ids2);
                if($value->DIFF_DISCHARGE){
                    $grossct = $value->DIFF_DISCHARGE;
                }else{
                    $grossct = 0;
                }
                
                $crane_t = round($grossct/$working_time,1); 
                $bch =  round($ttl_box/$crane_t,1);
                $bsh_bwt = round($ttl_box/$working_time,1);
                $bsh_bt = round($ttl_box/$berth_time,1);

                $sum_grossct += $grossct;
                $sum_crane_t += $crane_t;
                $sum_bch += $bch;
                $sum_bsh_bwt += $bsh_bwt;
                $sum_bsh_bt += $bsh_bt;

            ?>
            <!-- end config suspend -->

            <td><?=$grossct?></td>            <!-- Gross Crane Time -->
            <td><?=$crane_t?></td>            <!-- Crane Ratio -->

            <!-- <td>Crane Idle</td>
            <td>Net Crane Time (ET)</td>
            <td>BPR (mph) </td> 
            Total Box / ET
            -->

            <td><?=$bch?></td>                                  <!-- BCH -->
            <td><?=$bsh_bwt?></td>                              <!-- BSH (BWT) (bph) -->
            <td><?=$bsh_bt?></td>                               <!-- BSH (BT) (bph) -->
            
            <?php 
                $bph = round(($ttl_box / $berth_time-(get_suspend_mch_idle($value->ID_VES_VOYAGE) + (($berth_time - $working_time) + $idttls))),1);

                // echo "<pre>";
                // echo "ttl_box : ".$ttl_box;
                // echo "get_suspend_mch_idle($value->ID_VES_VOYAGE : ".get_suspend_mch_idle($value->ID_VES_VOYAGE);
                // echo "berth_time : ".$berth_time;
                // echo "working_time : ".$working_time;
                // echo "idttls : ".$idttls;
                // echo "</pre>";

                // debux($bph);

                $vor =  round($t_movement/$working_time,1);
                $notps = $berth_time - $working_time;
                $not = $notps + $idttls;
                $it = get_suspend_mch_idle($value->ID_VES_VOYAGE);
                $et = $berth_time-($it + $not);
                $et_bt = round(($set / $berth_time) * 100,1);
                $bw_bt = round(($working_time/$berth_time) * 100,1);

                $sum_bph += $bph;
                $sum_idttl += $idttl;
                $sum_vor += $vor;
                $sum_notps += $notps;
                $sum_not += $not;
                $sum_it += $it;
                $sum_et += $et;
                $sum_et_bt += $et_bt;
                $sum_bw_bt += $bw_bt;
            ?>

            <td><?=$bph?></td>                                  <!-- BSH (ET) (bph)  pending--> 
            <td><?=$idttl?></td>                                <!-- GCR (mph) -->
            <td><?=$vor?></td>                                  <!-- VOR (mph) -->
            <td><?=$notps?></td>                                <!-- NOT (preparation  + sailing) -->
            <td><?=$not?></td>                                  <!-- NOT -->
            <td><?=$it?></td>                                   <!-- IT -->
            <td><?=$et?></td>                                   <!-- ET -->
            <td><?=$et_bt?></td>                                <!-- ET/BT(%) -->
            <td><?=$bw_bt?></td>                                <!-- BWT/BT (%) -->

            <!--START SUSPEND TIME -->
            <?php 
            //debux($detail_suspend);
            foreach($detail_suspend as $keyx => $valuex){
                if($valuex->ID_SUSPEND == '0'){
                    $id0 = get_suspend_mch($value->ID_VES_VOYAGE,$valuex->ID_SUSPEND);      
                }elseif ($valuex->ID_SUSPEND == '4') {
                    $id1 = get_suspend_mch($value->ID_VES_VOYAGE,$valuex->ID_SUSPEND);
                } 
                //debux($value->ID_VES_VOYAGE);die;
                $suspend = get_suspend_mch($value->ID_VES_VOYAGE,$valuex->ID_SUSPEND);
                //debux($suspend);
            ?>
                <td><?=$suspend?></td>
            <?php } 
                //debux($valueID_VES_VOYAGE);
                $idttl =  $value->DIFF_DISCHARGE - ($id0 + $id1); 
            ?>
            <input type="hidden" id="idttl" value="<?=$idttl?>">

            <!-- END SUSPEND TIME -->
            
            <!-- TOTAL BOX PER MESIN -->

            <?php foreach($box_per_mch as $valuex){  ?>
                <td><?php echo $valuex; ?></td>
            <?php } ?>


            <!-- END TOTAL BOX PER MESIN -->

            <!-- TOTAL TEUS PER MESIN -->
            <?php foreach($box_per_mch_teu as $valuey){ ?>
                <td><?php echo $valuey; ?></td>
            <?php } ?>
            <!-- END TOTAL TEUS PER MESIN -->
        </tr>
    <?php $no+=1; } 
    //debux($s_box_per_mch);
    //debux($arr);

    // $hasil = array();
    // foreach ($arr as $key => $value) {
        // foreach ($value as $keyx => $valuex) {
            
        //debux($value);

            /*foreach ($valuex as $keyy => $valuey) {
                # code...
                //echo $valuey."<br />";
                //$hasil[$keyy] += $valuey;
                debux($valuex);
            }*/
        // }
        //debux($valuex);
    //}
    $jml = count($id_ves_voyage);
    //echo $jml;
    $temp = add_single_quotes($id_ves_voyage);
    $id = implode(',',$temp);
    //debux($ex);

    //$detail_mch = $this->vessel->get_all_machines();
    $temp_sum = array();
    foreach($detail_mch as $key => $value2){
        $temp_sum[$value2->ID_MACHINE] = get_dtl_mch_sum_all($id,$value2->ID_MACHINE);
    }

    //$detail_suspend = $this->vessel->get_all_suspend();
    $temp2_sum = array();
    foreach($detail_suspend as $key => $value3){
        //echo $value->ID_SUSPEND."<br />";
        $temp2_sum[$value3->ID_SUSPEND] = get_sum_suspend_mch($id,$value3->ID_SUSPEND);
    }

    //debux($temp2_sum);

    ?>
    <tr bgcolor="#CCCCCC">
        <td colspan="20" class="abu-terang">SUMMARY</td>
        <?php foreach ($temp_sum as $val) { ?>
           <td class="abu-terang"><?php echo $val['I_20FCL']; ?></td>
           <td class="abu-terang"><?php echo $val['I_20MTY']; ?></td>
           <td class="abu-terang"><?php echo $val['I_40FCL']; ?></td>
           <td class="abu-terang"><?php echo $val['I_40MTY']; ?></td>
           <td class="abu-terang"><?php echo $val['E_20FCL']; ?></td>
           <td class="abu-terang"><?php echo $val['E_20MTY']; ?></td>
           <td class="abu-terang"><?php echo $val['E_40FCL']; ?></td>
           <td class="abu-terang"><?php echo $val['E_40MTY']; ?></td>
        <?php } ?> 
        <td class="abu-terang"><?=$sum_20_i_fcl?></td>
        <td class="abu-terang"><?=$sum_20_i_mty?></td>
        <td class="abu-terang"><?=$sum_40_i_fcl?></td>
        <td class="abu-terang"><?=$sum_40_i_mty?></td>
        <td class="abu-terang"><?=$sum_20_e_fcl?></td>
        <td class="abu-terang"><?=$sum_20_e_mty?></td>
        <td class="abu-terang"><?=$sum_40_e_fcl?></td>
        <td class="abu-terang"><?=$sum_40_e_mty?></td>
        <td class="abu-terang"><?=$sum_20_fcl?></td>
        <td class="abu-terang"><?=$sum_20_mty?></td>
        <td class="abu-terang"><?=$sum_40_fcl?></td>
        <td class="abu-terang"><?=$sum_40_mty?></td>
        <td class="abu-terang"><?=$sum_20_e_rfr?></td>
        <td class="abu-terang"><?=$sum_40_e_rfr?></td>
        <td class="abu-terang"><?=$sum_20_e_oog?></td>
        <td class="abu-terang"><?=$sum_40_e_oog?></td>
        <td class="abu-terang"><?=$sum_20_sf_fcl?></td>
        <td class="abu-terang"><?=$sum_20_sf_mty?></td>
        <td class="abu-terang"><?=$sum_40_sf_fcl?></td>
        <td class="abu-terang"><?=$sum_40_sf_mty?></td>
        <td class="abu-terang"><?=$sum_20_rf_sf?></td>
        <td class="abu-terang"><?=$sum_40_rf_sf?></td>
        <td class="abu-terang"><?=$sum_20_oog_sf?></td>
        <td class="abu-terang"><?=$sum_40_oog_sf?></td>
        <td class="abu-terang"><?=$sum_hatch_ves?></td>
        <td class="abu-terang"><?=$sum_fcl_disc?></td>
        <td class="abu-terang"><?=$sum_disch_teu?></td>
        <td class="abu-terang"><?=$sum_fcl_load?></td>
        <td class="abu-terang"><?=$sum_load_teu?></td>
        <td class="abu-terang"><?=$sum_ttl_sf?></td>
        <td class="abu-terang"><?=$sum_ttl_box?></td>
        <td class="abu-terang"><?=$sum_ttl_box?></td>
        <td class="abu-terang"><?=$sum_ttl_box_wo_sf?></td>
        <td class="abu-terang"><?=$sum_ttl_box_wo_sf?></td>
        <td class="abu-terang"><?=$sum_t_movement?></td>
        <td class="abu-terang"><?=$sum_berth_time?></td>
        <td class="abu-terang"><?=$sum_working_time?></td>
        <td class="abu-terang"><?=$sum_grossct?></td>
        <td class="abu-terang"><?=$sum_crane_t?></td>
        <td class="abu-terang"><?=$sum_bch?></td>
        <td class="abu-terang"><?=$sum_bsh_bwt?></td>
        <td class="abu-terang"><?=$sum_bsh_bt?></td>
        <td class="abu-terang"><?=$sum_bph?></td>
        <td class="abu-terang"><?=$sum_idttl?></td>
        <td class="abu-terang"><?=$sum_vor?></td>
        <td class="abu-terang"><?=$sum_notps?></td>
        <td class="abu-terang"><?=$sum_not?></td>
        <td class="abu-terang"><?=$sum_it?></td>
        <td class="abu-terang"><?=$sum_et?></td>
        <td class="abu-terang"><?=$sum_et_bt?></td>
        <td class="abu-terang"><?=$sum_bw_bt?></td>
        <?php foreach($temp2_sum as $key){ ?>
            <td class="abu-terang"><? echo $key; ?></td>
        <?php } ?>
        <?php foreach($s_box_per_mch as $val){  ?>
            <td class="abu-terang"><?php echo $val; ?></td>
        <?php } ?>
        <?php foreach($s_box_per_mch_teu as $val2){  ?>
            <td class="abu-terang"><?php echo $val2; ?></td>
        <?php } ?>
    </tr>
    <tr bgcolor="#CCCCCC">
        <td colspan="20" class="abu-terang">AVERAGE</td>
        <?php foreach ($temp_sum as $val) { ?>
           <td class="abu-terang"><?php echo ROUND(($val['I_20FCL']/$jml),1); ?></td>
           <td class="abu-terang"><?php echo ROUND(($val['I_20MTY']/$jml),1); ?></td>
           <td class="abu-terang"><?php echo ROUND(($val['I_40FCL']/$jml),1); ?></td>
           <td class="abu-terang"><?php echo ROUND(($val['I_40MTY']/$jml),1); ?></td>
           <td class="abu-terang"><?php echo ROUND(($val['E_20FCL']/$jml),1); ?></td>
           <td class="abu-terang"><?php echo ROUND(($val['E_20MTY']/$jml),1); ?></td>
           <td class="abu-terang"><?php echo ROUND(($val['E_40FCL']/$jml),1); ?></td>
           <td class="abu-terang"><?php echo ROUND(($val['E_40MTY']/$jml),1); ?></td>
        <?php } ?> 
        <td class="abu-terang"><?=ROUND(($sum_20_i_fcl/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_20_i_mty/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_40_i_fcl/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_40_i_mty/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_20_e_fcl/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_20_e_mty/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_40_e_fcl/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_40_e_mty/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_20_fcl/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_20_mty/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_40_fcl/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_40_mty/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_20_e_rfr/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_40_e_rfr/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_20_e_oog/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_40_e_oog/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_20_sf_fcl/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_20_sf_mty/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_40_sf_fcl/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_40_sf_mty/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_20_rf_sf/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_40_rf_sf/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_20_oog_sf/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_40_oog_sf/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_hatch_ves/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_fcl_disc/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_disch_teu/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_fcl_load/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_load_teu/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_ttl_sf/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_ttl_box/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_ttl_box/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_ttl_box_wo_sf/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_ttl_box_wo_sf/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_t_movement/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_berth_time/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_working_time/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_grossct/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_crane_t/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_bch/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_bsh_bwt/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_bsh_bt/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_bph/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_idttl/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_vor/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_notps/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_not/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_it/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_et/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_et_bt/$jml),1)?></td>
        <td class="abu-terang"><?=ROUND(($sum_bw_bt/$jml),1)?></td>
        <?php foreach($temp2_sum as $key){ ?>
            <td class="abu-terang"><? echo ROUND(($key/$jml),1); ?></td>
        <?php } ?>
        <?php foreach($s_box_per_mch as $val){  ?>
            <td class="abu-terang"><?php echo ROUND(($val/$jml),1); ?></td>
        <?php } ?>
        <?php foreach($s_box_per_mch_teu as $val2){  ?>
            <td class="abu-terang"><?php echo ROUND(($val2/$jml),1); ?></td>
        <?php } ?>
    </tr>
</table>

<div class="spacer">
<?php for ($i=0; $i <=1 ; $i++) { ?>
    <table cellpadding="2" cellspacing="2" style="border:0px !important;">
        <tr>
            <td style="border:0px !important;" >&nbsp;</td>
        </tr>
    </table>
<?php } ?>  
</div>


</body>
</html>
<script type="text/javascript">
$( document ).ready(function() {
    $('.spacer').html('<table cellpadding="2" cellspacing="2" style="border:0px !important;">'+
    				'<tr>'+
    					'<td style="border:0px !important;" >&nbsp;</td>'+
    				'</tr>'+
    				'</table>');

});

</script>