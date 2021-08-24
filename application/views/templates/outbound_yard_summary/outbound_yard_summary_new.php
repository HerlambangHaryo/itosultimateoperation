<?php if($excel == 'excel'): 
      /*header("Content-type: application/octet-stream");
      header("Content-Disposition: attachment; filename=outbound_yard_summary.xls");
      header("Pragma: no-cache");
      header("Expires: 0");*/
?>
<script type="text/javascript">
window.print();
</script>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Outbound Yard Summary</title>
</head>

<body>
    <?php endif; ?>

    <style>
    .Middle {}

    .CenterAndMiddle {
        text-align: center;
        vertical-align: middle;
    }

    /*table styling*/
    .table,
    .tds,
    .ths {
        border: 1px solid #ddd;
        text-align: left;
        padding: 7px;
    }

    .<?=$tab_id?> .tables {
        border-collapse: collapse;
		white-space: nowrap;
    }

    .spacer {
        padding-bottom: 30px;
    }

    .abu-abu {
        background-color: #303030 !important;
        color: #ffffff !important;
    }

    .abu-terang {
        background-color: #e0e0e0 !important;
        font-weight: bold !important;
    }

    .tabMainPanelVesServ1 {

        background: linear-gradient(to top, #fcfbfb, #f7f6f6);
        box-shadow:
            0 1px 2px #fff,
            /*bottom external highlight*/
            0 -1px 1px #9c9da2,
            /*top external shadow*/
            inset 0 -1px 1px rgba(0, 0, 0, 0.1),
            /*bottom internal shadow*/
            inset 0 1px 1px rgba(255, 255, 255, 0.8);
        /*top internal highlight*/
        margin: 8px;
    }
    </style>

    <br />
    <div class="tabMainPanelVesServ1 <?=$tab_id?>">
        <table cellpadding="3" cellspacing="3" border="1" class="tables">
            <tr style='vertical-align:middle;'>
                <th class="abu-terang" rowspan="3" style="text-align: center;">Comm</th>
                <th class="abu-terang" rowspan="3" style="text-align: center;">POD</th>
                <th class="abu-terang" rowspan="3" style="text-align: center;">Location</th>

                <th class="abu-terang topn" colspan="16" colasli="16" colbaru="16" style="text-align: center;">N</th>
                <th class="abu-terang topy" colspan="16" colasli="16" colbaru="16" style="text-align: center;">Y</th>
                <th class="abu-terang" rowspan="3" style="text-align: center;">GRAND TOTAL</th>
            </tr>

            <tr>
                <td colspan="8" colasli="8" colbaru="8" class="abu-terang topfn" style="text-align: center;">F</td>
                <td colspan="8" colasli="8" colbaru="8" class="abu-terang topmn" style="text-align: center;">M</td>
                <td colspan="8" colasli="8" colbaru="8" class="abu-terang topfy" style="text-align: center;">F</td>
                <td colspan="8" colasli="8" colbaru="8" class="abu-terang topmy" style="text-align: center;">M</td>
            </tr>

            <tr class='tdcenter'>
                <td class="abu-terang sizefn" data-size='20' >20</td>
                <td class="abu-terang sizefn" data-size='21' >21</td>
                <td class="abu-terang sizefn" data-size='40' >40</td>
                <td class="abu-terang sizefn" data-size='45' >45</td>
                <td class="abu-terang sizefn" data-size='20H' >20H</td>
                <td class="abu-terang sizefn" data-size='21H' >21H</td>
                <td class="abu-terang sizefn" data-size='40H' >40H</td>
                <td class="abu-terang sizefn" data-size='45H' >45H</td>

                <td class="abu-terang sizemn" data-size='20' >20</td>
                <td class="abu-terang sizemn" data-size='21' >21</td>
                <td class="abu-terang sizemn" data-size='40' >40</td>
                <td class="abu-terang sizemn" data-size='45' >45</td>
                <td class="abu-terang sizemn" data-size='20H' >20H</td>
                <td class="abu-terang sizemn" data-size='21H' >21H</td>
                <td class="abu-terang sizemn" data-size='40H' >40H</td>
                <td class="abu-terang sizemn" data-size='45H' >45H</td>

                <td class="abu-terang sizefy" data-size='20' >20</td>
                <td class="abu-terang sizefy" data-size='21' >21</td>
                <td class="abu-terang sizefy" data-size='40' >40</td>
                <td class="abu-terang sizefy" data-size='45' >45</td>
                <td class="abu-terang sizefy" data-size='20H' >20H</td>
                <td class="abu-terang sizefy" data-size='21H' >21H</td>
                <td class="abu-terang sizefy" data-size='40H' >40H</td>
                <td class="abu-terang sizefy" data-size='45H' >45H</td>

                <td class="abu-terang sizemy" data-size='20' >20</td>
                <td class="abu-terang sizemy" data-size='21' >21</td>
                <td class="abu-terang sizemy" data-size='40' >40</td>
                <td class="abu-terang sizemy" data-size='45' >45</td>
                <td class="abu-terang sizemy" data-size='20H' >20H</td>
                <td class="abu-terang sizemy" data-size='21H' >21H</td>
                <td class="abu-terang sizemy" data-size='40H' >40H</td>
                <td class="abu-terang sizemy" data-size='45H' >45H</td>

            </tr>

            <?php
                $header = 1;

                foreach($list_commodity as $val => $value){
                    $id_comm            = $value->ID_COMMODITY;

                    $pod                = get_pod_by_com($id_comm,$id_yard,$id_ves_voyage);
                    $pod_head           = 0;

                    foreach ($pod as $keyx){ 
                        $block_pod      = get_loc($id_comm,$keyx->ID_POD,$id_yard,$id_ves_voyage);

                        foreach($block_pod as $valx) {
                            $pod_head ++;
                        }
                    }

                    $head_comm = count(get_pod_by_com($id_comm,$id_yard,$id_ves_voyage)) + $pod_head + $header;

                    if(empty($pod)){
                        $head_comm = 2;
                    }

            ?>
                <tr class='tdcom'>
                    <td data-cn="<?=$value->COMMODITY_NAME?>" class="abu-terang" rowspan="<?=$head_comm?>" rowasli="<?=$head_comm?>" rowbaru="<?=$head_comm?>"><?=$value->COMMODITY_NAME?></td>

                    <?php 
                        if(!empty($pod)){ 
                            foreach ($pod as $keyy){ 
                                $block_pod   = get_loc($id_comm,$keyy->ID_POD,$id_yard,$id_ves_voyage);
                                $podcpolor   = get_pod_color($keyy->ID_POD,'BACKGROUND_COLOR');
                                //debux($this->db->last_query());
                                $rowspan_pod = count($block_pod) + $header;
                    ?>

                <tr class='tdpod'>
                    <td data-cn="<?=$value->COMMODITY_NAME?>" rowspan="<?=$rowspan_pod?>" rowasli="<?=$rowspan_pod?>" rowbaru="<?=$rowspan_pod?>" style="color:black;background:#<?=($podcpolor)?>"><?=($keyy->ID_POD)?></td>

                    <?php 
                        // debux($block_pod);
                        foreach($block_pod as $valx) {
                            /*<!--NF -->*/
                                $n_20_block_pod_h_fcl    = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','FCL','20','',$id_yard,$id_ves_voyage);
                                $n_21_block_pod_h_fcl    = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','FCL','21','',$id_yard,$id_ves_voyage);
                                $n_40_block_pod_h_fcl    = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','FCL','40','',$id_yard,$id_ves_voyage);
                                $n_45_block_pod_h_fcl    = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','FCL','45','',$id_yard,$id_ves_voyage);
                                $n_20hq_block_pod_h_fcl  = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','FCL','20','HQ',$id_yard,$id_ves_voyage);
                                $n_21hq_block_pod_h_fcl  = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','FCL','21','HQ',$id_yard,$id_ves_voyage);
                                $n_40hq_block_pod_h_fcl  = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','FCL','40','HQ',$id_yard,$id_ves_voyage);
                                $n_45hq_block_pod_h_fcl  = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','FCL','45','HQ',$id_yard,$id_ves_voyage);
                                $n_20_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-20-$id_yard-0-".$valx->YD_BLOCK;
                                $n_21_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-21-$id_yard-0-".$valx->YD_BLOCK;
                                $n_40_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-40-$id_yard-0-".$valx->YD_BLOCK;
                                $n_45_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-45-$id_yard-0-".$valx->YD_BLOCK;
                                $n_20h_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-20-$id_yard-HQ-".$valx->YD_BLOCK;
                                $n_21h_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-21-$id_yard-HQ-".$valx->YD_BLOCK;
                                $n_40h_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-40-$id_yard-HQ-".$valx->YD_BLOCK;
                                $n_45h_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-45-$id_yard-HQ-".$valx->YD_BLOCK;

                            /*<!--NM -->*/
                                $n_20_block_pod_h_mty    = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','MTY','20','',$id_yard,$id_ves_voyage);
                                $n_21_block_pod_h_mty    = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','MTY','21','',$id_yard,$id_ves_voyage);
                                $n_40_block_pod_h_mty    = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','MTY','40','',$id_yard,$id_ves_voyage);
                                $n_45_block_pod_h_mty    = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','MTY','45','',$id_yard,$id_ves_voyage);
                                $n_20hq_block_pod_h_mty  = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','MTY','20','HQ',$id_yard,$id_ves_voyage);
                                $n_21hq_block_pod_h_mty  = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','MTY','21','HQ',$id_yard,$id_ves_voyage);
                                $n_40hq_block_pod_h_mty  = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','MTY','40','HQ',$id_yard,$id_ves_voyage);
                                $n_45hq_block_pod_h_mty  = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'N','MTY','45','HQ',$id_yard,$id_ves_voyage);
                                $n_20_block_slot_m    = "$id_ves_voyage-".$keyy->ID_POD."-20-$id_yard-0-".$valx->YD_BLOCK;
                                $n_21_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-21-$id_yard-0-".$valx->YD_BLOCK;
                                $n_40_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-40-$id_yard-0-".$valx->YD_BLOCK;
                                $n_45_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-45-$id_yard-0-".$valx->YD_BLOCK;
                                $n_20h_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-20-$id_yard-HQ-".$valx->YD_BLOCK;
                                $n_21h_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-21-$id_yard-HQ-".$valx->YD_BLOCK;
                                $n_40h_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-40-$id_yard-HQ-".$valx->YD_BLOCK;
                                $n_45h_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-45-$id_yard-HQ-".$valx->YD_BLOCK;

                            /*<!--YF -->*/
                                $y_20_block_pod_h_fcl    = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','FCL','20','',$id_yard,$id_ves_voyage);
                                $y_21_block_pod_h_fcl    = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','FCL','21','',$id_yard,$id_ves_voyage);
                                $y_40_block_pod_h_fcl    = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','FCL','40','',$id_yard,$id_ves_voyage);
                                $y_45_block_pod_h_fcl    = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','FCL','45','',$id_yard,$id_ves_voyage);
                                $y_20hq_block_pod_h_fcl  = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','FCL','20','HQ',$id_yard,$id_ves_voyage);
                                $y_21hq_block_pod_h_fcl  = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','FCL','21','HQ',$id_yard,$id_ves_voyage);
                                $y_40hq_block_pod_h_fcl  = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','FCL','40','HQ',$id_yard,$id_ves_voyage);
                                $y_45hq_block_pod_h_fcl  = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','FCL','45','HQ',$id_yard,$id_ves_voyage);
                                $y_20_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-20-$id_yard-0-".$valx->YD_BLOCK;
                                $y_21_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-21-$id_yard-0-".$valx->YD_BLOCK;
                                $y_40_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-40-$id_yard-0-".$valx->YD_BLOCK;
                                $y_45_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-45-$id_yard-0-".$valx->YD_BLOCK;
                                $y_20h_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-20-$id_yard-HQ-".$valx->YD_BLOCK;
                                $y_21h_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-21-$id_yard-HQ-".$valx->YD_BLOCK;
                                $y_40h_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-40-$id_yard-HQ-".$valx->YD_BLOCK;
                                $y_45h_block_slot    = "$id_ves_voyage-".$keyy->ID_POD."-45-$id_yard-HQ-".$valx->YD_BLOCK;

                            /*<!--YM -->*/
                                $y_20_block_pod_h_mty   = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','MTY','20','',$id_yard,$id_ves_voyage);
                                $y_21_block_pod_h_mty   = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','MTY','21','',$id_yard,$id_ves_voyage);
                                $y_40_block_pod_h_mty   = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','MTY','40','',$id_yard,$id_ves_voyage);
                                $y_45_block_pod_h_mty   = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','MTY','45','',$id_yard,$id_ves_voyage);
                                $y_20hq_block_pod_h_mty = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','MTY','20','HQ',$id_yard,$id_ves_voyage);
                                $y_21hq_block_pod_h_mty = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','MTY','21','HQ',$id_yard,$id_ves_voyage);
                                $y_40hq_block_pod_h_mty = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','MTY','40','HQ',$id_yard,$id_ves_voyage);
                                $y_45hq_block_pod_h_mty = get_block_pod_com($id_comm,$keyy->ID_POD,$valx->YD_BLOCK,'Y','MTY','45','HQ',$id_yard,$id_ves_voyage);
                                $y_20_block_slot_m    = "$id_ves_voyage-".$keyy->ID_POD."-20-$id_yard-0-".$valx->YD_BLOCK;
                                $y_21_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-21-$id_yard-0-".$valx->YD_BLOCK;
                                $y_40_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-40-$id_yard-0-".$valx->YD_BLOCK;
                                $y_45_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-45-$id_yard-0-".$valx->YD_BLOCK;
                                $y_20h_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-20-$id_yard-HQ-".$valx->YD_BLOCK;
                                $y_21h_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-21-$id_yard-HQ-".$valx->YD_BLOCK;
                                $y_40h_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-40-$id_yard-HQ-".$valx->YD_BLOCK;
                                $y_45h_block_slot_m     = "$id_ves_voyage-".$keyy->ID_POD."-45-$id_yard-HQ-".$valx->YD_BLOCK;

                            /*grand total 1*/
                                $grand_total  = $n_20_block_pod_h_fcl + $n_21_block_pod_h_fcl + $n_40_block_pod_h_fcl + $n_45_block_pod_h_fcl + $n_20hq_block_pod_h_fcl + $n_21hq_block_pod_h_fcl +$n_40hq_block_pod_h_fcl + $n_45hq_block_pod_h_fcl + $n_20_block_pod_h_mty + $n_21_block_pod_h_mty + $n_40_block_pod_h_mty +  $n_45_block_pod_h_mty + $n_20hq_block_pod_h_mty + $n_21hq_block_pod_h_mty + $n_40hq_block_pod_h_mty + $n_45hq_block_pod_h_mty +$y_20_block_pod_h_fcl + $y_21_block_pod_h_fcl + $y_40_block_pod_h_fcl + $y_45_block_pod_h_fcl + $y_20hq_block_pod_h_fcl + $y_21hq_block_pod_h_fcl + $y_40hq_block_pod_h_fcl + $y_45hq_block_pod_h_fcl + $y_20_block_pod_h_mty + $y_21_block_pod_h_mty + $y_40_block_pod_h_mty + $y_45_block_pod_h_mty + $y_20hq_block_pod_h_mty + $y_21hq_block_pod_h_mty + $y_40hq_block_pod_h_mty + $y_45hq_block_pod_h_mty;

                            /*grand total 2*/
                                $kolom1[] = $n_20_block_pod_h_fcl;
                                $kolom2[] = $n_21_block_pod_h_fcl;
                                $kolom3[] = $n_40_block_pod_h_fcl;
                                $kolom4[] = $n_45_block_pod_h_fcl;
                                $kolom5[] = $n_20hq_block_pod_h_fcl;
                                $kolom6[] = $n_21hq_block_pod_h_fcl;
                                $kolom7[] = $n_40hq_block_pod_h_fcl;
                                $kolom8[] = $n_45hq_block_pod_h_fcl;

                                $kolom9[]  = $n_20_block_pod_h_mty;
                                $kolom10[] = $n_21_block_pod_h_mty;
                                $kolom11[] = $n_40_block_pod_h_mty;
                                $kolom12[] = $n_45_block_pod_h_mty;
                                $kolom13[] = $n_20hq_block_pod_h_mty;
                                $kolom14[] = $n_21hq_block_pod_h_mty;
                                $kolom15[] = $n_40hq_block_pod_h_mty;
                                $kolom16[] = $n_45hq_block_pod_h_mty;

                                $kolom17[] = $y_20_block_pod_h_fcl;
                                $kolom18[] = $y_21_block_pod_h_fcl;
                                $kolom19[] = $y_40_block_pod_h_fcl;
                                $kolom20[] = $y_45_block_pod_h_fcl;
                                $kolom21[] = $y_20hq_block_pod_h_fcl;
                                $kolom22[] = $y_21hq_block_pod_h_fcl;
                                $kolom23[] = $y_40hq_block_pod_h_fcl;
                                $kolom24[] = $y_45hq_block_pod_h_fcl;

                                $kolom25[] = $y_20_block_pod_h_mty;
                                $kolom26[] = $y_21_block_pod_h_mty;
                                $kolom27[] = $y_40_block_pod_h_mty;
                                $kolom28[] = $y_45_block_pod_h_mty;
                                $kolom29[] = $y_20hq_block_pod_h_mty;
                                $kolom30[] = $y_21hq_block_pod_h_mty;
                                $kolom31[] = $y_40hq_block_pod_h_mty;
                                $kolom32[] = $y_45hq_block_pod_h_mty;
								
					if (strpos($valx->YD_BLOCK, '-') !== false) {
						$IDBLOCK="";
					}else{
						$IDBLOCK=$valx->YD_BLOCK;
					}
                    ?>

                <tr data-cn="<?=$value->COMMODITY_NAME?>" data-idpod="<?=($keyy->ID_POD)?>" class='tdloc'>
                    <td><?=$valx->YD_BLOCK_NAME?></td>
					
					<td class='loadstack nilaitd tdcenter sizefn' data-size='20' data-stack="<?=$IDBLOCK."-".$n_20_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$n_20_block_slot?>"><?=$n_20_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefn' data-size='21' data-stack="<?=$IDBLOCK."-".$n_21_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$n_21_block_slot?>"><?=$n_21_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefn' data-size='40' data-stack="<?=$IDBLOCK."-".$n_40_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$n_40_block_slot?>"><?=$n_40_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefn' data-size='45' data-stack="<?=$IDBLOCK."-".$n_45_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$n_45_block_slot?>"><?=$n_45_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefn' data-size='20H' data-stack="<?=$IDBLOCK."-".$n_20hq_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$n_20h_block_slot?>"><?=$n_20hq_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefn' data-size='21H' data-stack="<?=$IDBLOCK."-".$n_21hq_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$n_21h_block_slot?>"><?=$n_21hq_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefn' data-size='40H' data-stack="<?=$IDBLOCK."-".$n_40hq_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$n_40h_block_slot?>"><?=$n_40hq_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefn' data-size='45H' data-stack="<?=$IDBLOCK."-".$n_45hq_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$n_45h_block_slot?>"><?=$n_45hq_block_pod_h_fcl?></td>

					<td class='loadstack nilaitd tdcenter sizemn' data-size='20' data-stack="<?=$IDBLOCK."-".$n_20_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$n_20_block_slot_m?>"><?=$n_20_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemn' data-size='21' data-stack="<?=$IDBLOCK."-".$n_21_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$n_21_block_slot_m?>"><?=$n_21_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemn' data-size='40' data-stack="<?=$IDBLOCK."-".$n_40_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$n_40_block_slot_m?>"><?=$n_40_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemn' data-size='45' data-stack="<?=$IDBLOCK."-".$n_45_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$n_45_block_slot_m?>"><?=$n_45_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemn' data-size='20H' data-stack="<?=$IDBLOCK."-".$n_20hq_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$n_20h_block_slot_m?>"><?=$n_20hq_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemn' data-size='21H' data-stack="<?=$IDBLOCK."-".$n_21hq_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$n_21h_block_slot_m?>"><?=$n_21hq_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemn' data-size='40H' data-stack="<?=$IDBLOCK."-".$n_40hq_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$n_40h_block_slot_m?>"><?=$n_40hq_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemn' data-size='45H' data-stack="<?=$IDBLOCK."-".$n_45hq_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$n_45h_block_slot_m?>"><?=$n_45hq_block_pod_h_mty?></td>

					<td class='loadstack nilaitd tdcenter sizefy' data-size='20' data-stack="<?=$IDBLOCK."-".$y_20_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$y_20_block_slot?>"><?=$y_20_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefy' data-size='21' data-stack="<?=$IDBLOCK."-".$y_21_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$y_21_block_slot?>"><?=$y_21_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefy' data-size='40' data-stack="<?=$IDBLOCK."-".$y_40_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$y_40_block_slot?>"><?=$y_40_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefy' data-size='45' data-stack="<?=$IDBLOCK."-".$y_45_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$y_45_block_slot?>"><?=$y_45_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefy' data-size='20H' data-stack="<?=$IDBLOCK."-".$y_20hq_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$y_20h_block_slot?>"><?=$y_20hq_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefy' data-size='21H' data-stack="<?=$IDBLOCK."-".$y_21hq_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$y_21h_block_slot?>"><?=$y_21hq_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefy' data-size='40H' data-stack="<?=$IDBLOCK."-".$y_40hq_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$y_40h_block_slot?>"><?=$y_40hq_block_pod_h_fcl?></td>
					<td class='loadstack nilaitd tdcenter sizefy' data-size='45H' data-stack="<?=$IDBLOCK."-".$y_45hq_block_pod_h_fcl."-".$id_ves_voyage?>" data-slot="<?=$y_45h_block_slot?>"><?=$y_45hq_block_pod_h_fcl?></td>

					<td class='loadstack nilaitd tdcenter sizemy' data-size='20' data-stack="<?=$IDBLOCK."-".$y_20_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$y_20_block_slot_m?>"><?=$y_20_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemy' data-size='21' data-stack="<?=$IDBLOCK."-".$y_21_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$y_21_block_slot_m?>"><?=$y_21_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemy' data-size='40' data-stack="<?=$IDBLOCK."-".$y_40_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$y_40_block_slot_m?>"><?=$y_40_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemy' data-size='45' data-stack="<?=$IDBLOCK."-".$y_45_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$y_45_block_slot_m?>"><?=$y_45_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemy' data-size='20H' data-stack="<?=$IDBLOCK."-".$y_20hq_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$y_20h_block_slot_m?>"><?=$y_20hq_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemy' data-size='21H' data-stack="<?=$IDBLOCK."-".$y_21hq_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$y_21h_block_slot_m?>"><?=$y_21hq_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemy' data-size='40H' data-stack="<?=$IDBLOCK."-".$y_40hq_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$y_40h_block_slot_m?>"><?=$y_40hq_block_pod_h_mty?></td>
					<td class='loadstack nilaitd tdcenter sizemy' data-size='45H' data-stack="<?=$IDBLOCK."-".$y_45hq_block_pod_h_mty."-".$id_ves_voyage?>" data-slot="<?=$y_45h_block_slot_m?>"><?=$y_45hq_block_pod_h_mty?></td>

                    <td class="abu-terang grandtotalright" style="text-align: center;"><?=$grand_total?></td>

                </tr>
                    <?php } ?>
            </tr>

        <?php 
        } 
        //endforeach 

        }else{ ?>
            <tr data-cn="<?=$value->COMMODITY_NAME?>">
                <td> &nbsp; </td>

                <td> &nbsp; </td>

                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>

                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>

                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>

                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>

                <td class="abu-terang grandtotalright" style="text-align: center;"> - </td>

            </tr>
            <?php }
        ?>
            </tr>

            <?php 
        } ?>

            <?php
            $grand_total_2 = array_sum($kolom1) + array_sum($kolom2) + array_sum($kolom3) + array_sum($kolom4) + array_sum($kolom5) + array_sum($kolom6) + array_sum($kolom7) + array_sum($kolom8) + array_sum($kolom9) + array_sum($kolom10) + array_sum($kolom11) + array_sum($kolom12) + array_sum($kolom13) + array_sum($kolom14) + array_sum($kolom15) + array_sum($kolom16) + array_sum($kolom17) + array_sum($kolom18) + array_sum($kolom19) + array_sum($kolom20) + array_sum($kolom21) + array_sum($kolom22) + array_sum($kolom23) + array_sum($kolom24) + array_sum($kolom25) + array_sum($kolom26) + array_sum($kolom27) + array_sum($kolom28) + array_sum($kolom29) + array_sum($kolom30) + array_sum($kolom31) + array_sum($kolom32); 
     ?>


            <tr class='tdcenter'>
                <td class="abu-terang" colspan="3" style="text-align: center;">Grand Total</td>
                <!--NF -->
                <td class="abu-terang nilaigt sizefn" data-size='20' ><?=array_sum($kolom1); ?></td>
                <td class="abu-terang nilaigt sizefn" data-size='21' ><?=array_sum($kolom2); ?></td>
                <td class="abu-terang nilaigt sizefn" data-size='40' ><?=array_sum($kolom3); ?></td>
                <td class="abu-terang nilaigt sizefn" data-size='45' ><?=array_sum($kolom4); ?></td>
                <td class="abu-terang nilaigt sizefn" data-size='20H' ><?=array_sum($kolom5); ?></td>
                <td class="abu-terang nilaigt sizefn" data-size='21H' ><?=array_sum($kolom6); ?></td>
                <td class="abu-terang nilaigt sizefn" data-size='40H' ><?=array_sum($kolom7); ?></td>
                <td class="abu-terang nilaigt sizefn" data-size='45H' ><?=array_sum($kolom8); ?></td>
                <!--NM -->
                <td class="abu-terang nilaigt sizemn" data-size='20' ><?=array_sum($kolom9); ?></td>
                <td class="abu-terang nilaigt sizemn" data-size='21' ><?=array_sum($kolom10); ?></td>
                <td class="abu-terang nilaigt sizemn" data-size='40' ><?=array_sum($kolom11); ?></td>
                <td class="abu-terang nilaigt sizemn" data-size='45' ><?=array_sum($kolom12); ?></td>
                <td class="abu-terang nilaigt sizemn" data-size='20H' ><?=array_sum($kolom13); ?></td>
                <td class="abu-terang nilaigt sizemn" data-size='21H' ><?=array_sum($kolom14); ?></td>
                <td class="abu-terang nilaigt sizemn" data-size='40H' ><?=array_sum($kolom15); ?></td>
                <td class="abu-terang nilaigt sizemn" data-size='45H' ><?=array_sum($kolom16); ?></td>

                <!--YF -->
                <td class="abu-terang nilaigt sizefy" data-size='20' ><?=array_sum($kolom17); ?></td>
                <td class="abu-terang nilaigt sizefy" data-size='21' ><?=array_sum($kolom18); ?></td>
                <td class="abu-terang nilaigt sizefy" data-size='40' ><?=array_sum($kolom19); ?></td>
                <td class="abu-terang nilaigt sizefy" data-size='45' ><?=array_sum($kolom20); ?></td>
                <td class="abu-terang nilaigt sizefy" data-size='20H' ><?=array_sum($kolom21); ?></td>
                <td class="abu-terang nilaigt sizefy" data-size='21H' ><?=array_sum($kolom22); ?></td>
                <td class="abu-terang nilaigt sizefy" data-size='40H' ><?=array_sum($kolom23); ?></td>
                <td class="abu-terang nilaigt sizefy" data-size='45H' ><?=array_sum($kolom24); ?></td>

                <!--YM -->
                <td class="abu-terang nilaigt sizemy" data-size='20' ><?=array_sum($kolom25); ?></td>
                <td class="abu-terang nilaigt sizemy" data-size='21' ><?=array_sum($kolom26); ?></td>
                <td class="abu-terang nilaigt sizemy" data-size='40' ><?=array_sum($kolom27); ?></td>
                <td class="abu-terang nilaigt sizemy" data-size='45' ><?=array_sum($kolom28); ?></td>
                <td class="abu-terang nilaigt sizemy" data-size='20H' ><?=array_sum($kolom29); ?></td>
                <td class="abu-terang nilaigt sizemy" data-size='21H' ><?=array_sum($kolom30); ?></td>
                <td class="abu-terang nilaigt sizemy" data-size='40H' ><?=array_sum($kolom31); ?></td>
                <td class="abu-terang nilaigt sizemy" data-size='45H' ><?=array_sum($kolom32); ?></td>

                <!-- GRAND TOTAL -->
                <td class="abu-terang grandtotal" style="text-align: center;"><?=$grand_total_2?></td>
            </tr>
        </table>
    </div>

    <?php if($excel == 'excel'): ?>
</body>

</html>
<?php if($excel == 'excel'){ ?>
<style>
.<?=$tab_id?> .cnkosonghide {
    display: none;
}
.<?=$tab_id?> .tdhide {
    display: none;
}
.<?=$tab_id?> .cnkosonghide.cnshow {
    display: table-row;
}
.<?=$tab_id?> .tdhide.tdshow {
    display: table-cell;
}
.<?=$tab_id?> .tdcenter {
    text-align: center;
}
.<?=$tab_id?> th,.<?=$tab_id?> td {
    padding: 5px 10px;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$( document ).ready(function() {
	$('.<?=$tab_id?> td.grandtotalright').each(function(){
		if($(this).text().indexOf('-') != -1){

			var cnkosong = $(this).parent('tr').data('cn');

			$(this).parent('tr').addClass('cnkosong').addClass('cnkosonghide');
			$('.<?=$tab_id?> tr.tdcom').each(function(){
				if($(this).children('td').data('cn')==cnkosong){
					$(this).addClass('cnkosong').addClass('cnkosonghide');
				}
			});
		}
		if($(this).text()=='0'){
			var cnnol = $(this).parent('tr').data('cn');
			$(this).parent('tr').addClass('cnnol').addClass('cnkosonghide');
			 $('.<?=$tab_id?> td[data-cn="'+cnnol+'"]').addClass('tdubah');
		}else if($(this).text()!='0' && $(this).text().indexOf('-') == -1){
			$(this).parent('tr').addClass('cnada');
		}
	});
	
	$('.<?=$tab_id?> td.tdubah').each(function(){
		var cn = $(this).data('cn');
		var nilai=1;
		$('.<?=$tab_id?> tr.tdpod').each(function(){
			var idpod = $(this).children('td').text();
			var cnp = $(this).children('td').data('cn');
			if(cnp==cn){
				nilai+= 1;
			}
		});
		var idpod = $(this).text();
		if($('.<?=$tab_id?> tr.tdloc.cnada[data-cn="'+cn+'"]').length>0){
			var cnadalength  = $('.<?=$tab_id?> tr.tdloc.cnada[data-cn="'+cn+'"][data-idpod="'+idpod+'"]').length+1;
			var cnadacomlength  = $('.<?=$tab_id?> tr.tdloc.cnada[data-cn="'+cn+'"]').length+nilai;
			if($(this).parent('tr').attr('class')=='tdcom'){
				$(this).attr('rowspan',cnadacomlength);
				$(this).attr('rowbaru',cnadacomlength);
			}else if($(this).parent('tr').attr('class')=='tdpod'){
				$(this).attr('rowspan',cnadalength);
				$(this).attr('rowbaru',cnadalength);
			}
		}else{
			$(this).parent('tr').addClass('cnnol').addClass('cnkosonghide');
		}
	});
	$('.<?=$tab_id?> td.nilaigt.sizefn').each(function(){
		var gtnol = $(this).data('size');
		if(parseFloat($(this).text())<1){
			$('.<?=$tab_id?> td.sizefn[data-size="'+gtnol+'"]').addClass('tdhide');
		}else if(parseFloat($(this).text())>0){
			 $('.<?=$tab_id?> td.sizefn[data-size="'+gtnol+'"]').addClass('tdnilaiada');
		}
	});
	if($('.<?=$tab_id?> td.nilaigt.sizefn.tdnilaiada').length>0){
		$('.<?=$tab_id?> td.topfn').attr('colspan',$('.<?=$tab_id?> td.nilaigt.sizefn.tdnilaiada').length);
		$('.<?=$tab_id?> td.topfn').attr('colbaru',$('.<?=$tab_id?> td.nilaigt.sizefn.tdnilaiada').length);
	}else{
		 $('.<?=$tab_id?> td.topfn').addClass('tdhide');
	}
	$('.<?=$tab_id?> td.nilaigt.sizemn').each(function(){
		var gtnol = $(this).data('size');
		if(parseFloat($(this).text())<1){
			$('.<?=$tab_id?> td.sizemn[data-size="'+gtnol+'"]').addClass('tdhide');
		}else if(parseFloat($(this).text())>0){
			 $('.<?=$tab_id?> td.sizemn[data-size="'+gtnol+'"]').addClass('tdnilaiada');
		}
	});
	if($('.<?=$tab_id?> td.nilaigt.sizemn.tdnilaiada').length>0){
		$('.<?=$tab_id?> td.topmn').attr('colspan',$('.<?=$tab_id?> td.nilaigt.sizemn.tdnilaiada').length);
		$('.<?=$tab_id?> td.topmn').attr('colbaru',$('.<?=$tab_id?> td.nilaigt.sizemn.tdnilaiada').length);
	}else{
		 $('.<?=$tab_id?> td.topmn').addClass('tdhide');
	}
	var nilaitopn = $('.<?=$tab_id?> td.nilaigt.sizefn.tdnilaiada').length + $('.<?=$tab_id?> td.nilaigt.sizemn.tdnilaiada').length;
	if(nilaitopn > 0){
		$('.<?=$tab_id?> th.topn').attr('colspan',nilaitopn);
		$('.<?=$tab_id?> th.topn').attr('colbaru',nilaitopn);
	}else{
		$('.<?=$tab_id?> th.topn').addClass('tdhide');
	}
	
	$('.<?=$tab_id?> td.nilaigt.sizefy').each(function(){
		var gtnol = $(this).data('size');
		if(parseFloat($(this).text())<1){
			$('.<?=$tab_id?> td.sizefy[data-size="'+gtnol+'"]').addClass('tdhide');
		}else if(parseFloat($(this).text())>0){
			 $('.<?=$tab_id?> td.sizefy[data-size="'+gtnol+'"]').addClass('tdnilaiada');
		}
	});
	if($('.<?=$tab_id?> td.nilaigt.sizefy.tdnilaiada').length>0){
		$('.<?=$tab_id?> td.topfy').attr('colspan',$('.<?=$tab_id?> td.nilaigt.sizefy.tdnilaiada').length);
		$('.<?=$tab_id?> td.topfy').attr('colbaru',$('.<?=$tab_id?> td.nilaigt.sizefy.tdnilaiada').length);
	}else{
		 $('.<?=$tab_id?> td.topfy').addClass('tdhide');
	}
	$('.<?=$tab_id?> td.nilaigt.sizemy').each(function(){
		var gtnol = $(this).data('size');
		if(parseFloat($(this).text())<1){
			$('.<?=$tab_id?> td.sizemy[data-size="'+gtnol+'"]').addClass('tdhide');
		}else if(parseFloat($(this).text())>0){
			 $('.<?=$tab_id?> td.sizemy[data-size="'+gtnol+'"]').addClass('tdnilaiada');
		}
	});
	if($('.<?=$tab_id?> td.nilaigt.sizemy.tdnilaiada').length>0){
		$('.<?=$tab_id?> td.topmy').attr('colspan',$('.<?=$tab_id?> td.nilaigt.sizemy.tdnilaiada').length);
		$('.<?=$tab_id?> td.topmy').attr('colbaru',$('.<?=$tab_id?> td.nilaigt.sizemy.tdnilaiada').length);
	}else{
		 $('.<?=$tab_id?> td.topmy').addClass('tdhide');
	}
	var nilaitopy = $('.<?=$tab_id?> td.nilaigt.sizefy.tdnilaiada').length + $('.<?=$tab_id?> td.nilaigt.sizemy.tdnilaiada').length;
	if(nilaitopy > 0){
		$('.<?=$tab_id?> th.topy').attr('colspan',nilaitopy);
		$('.<?=$tab_id?> th.topy').attr('colbaru',nilaitopy);
	}else{
		$('.<?=$tab_id?> th.topy').addClass('tdhide');
	}
});
</script>
<?php } ?>
<?php endif; ?>