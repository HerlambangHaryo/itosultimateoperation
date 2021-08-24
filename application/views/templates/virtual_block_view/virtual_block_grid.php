<style>
    .row-selected td{
	background: orange !important;
    }
</style>
<script type="text/javascript">
    var virtual_block_store;
    Ext.onReady(function() {
	    var virtual_block_seq = 1;
	    virtual_block_store = Ext.create('Ext.data.Store', {
		    fields:['NO_CONTAINER','POINT', 'CONT_SIZE', 'CONT_TYPE','ID_ISO_CODE', 'WEIGHT', 'ID_POD','ID_VES_VOYAGE', 'ID_COMMODITY', 'COMMODITY_NAME', 'ID_OPERATOR','ID_CLASS_CODE', 'ID_SPEC_HAND', 'IMDG', 'ID_OP_STATUS', 'EVENT','YD_SLOT', 'FOREGROUND_COLOR', 'VES_VOYAGE','ID_MACHINE','SEQUENCE'],
		    autoLoad: true,
		    remoteSort: false,
		    proxy: {
			    type: 'ajax',
			    url: '<?=controller_?>virtual_block_view/data_virtual_block',
			    reader: {
				    type: 'json',
				    root: 'data',
				    totalProperty: 'total'
			    },
			    extraParams: {
				    id_ves_voyage: '<?=$id_ves_voyage?>'
			    }
		    }
	    });

	    var virtual_block_filters_<?=$tab_id?> = {
		    ftype: 'filters',
		    encode: true,
		    local: false
	    };

	    var virtual_block_grid_<?=$tab_id?> = Ext.create('Ext.grid.Panel', {
		    id: 'virtual_block_grid_<?=$tab_id?>',
		    store: virtual_block_store,
		    width: 1280,
		    columns: [
			    { text: 'Container', dataIndex: 'NO_CONTAINER', width: 150, sortable: false},
			    { text: 'POD', dataIndex: 'ID_POD', width: 100,sortable: false},
			    { text: 'Point', dataIndex: 'POINT' , width: 70,sortable: false},
			    { text: 'Iso Code', dataIndex: 'ID_ISO_CODE', width: 80,sortable: false},				
			    { text: 'Weight', dataIndex: 'WEIGHT', width: 80,sortable: false},				
			    { text: 'Iso Code', dataIndex: 'ID_ISO_CODE', width: 80,sortable: false},				
			    { text: 'Commodity', dataIndex: 'COMMODITY_NAME', width: 100,sortable: false},				
			    { text: 'Class', dataIndex: 'ID_CLASS_CODE', width: 100,sortable: false},				
			    { text: 'Operator', dataIndex: 'ID_OPERATOR', width: 80,sortable: false},				
			    { text: 'Vessel Voyage', dataIndex: 'VES_VOYAGE', width: 150,sortable: false},				
			    { text: 'Handling', dataIndex: 'ID_SPEC_HAND', width: 100,sortable: false},
			    { text: 'IMDG', dataIndex: 'IMDG', width: 80,sortable: false},
			    { text: 'SEQUENCE', dataIndex: 'SEQUENCE', width: 100,sortable: false}
		    ],			
		    viewConfig : {
			    enableTextSelection: true
		    },
		    tbar: [
			    {
					    text: 'Refresh',
					    handler: function () {
						virtual_block_store.reload();
						    virtual_block_grid_<?=$tab_id?>.getSelectionModel().deselectAll();
//							id_ves_voyage = '';
						    $( "#select-stack_<?=$tab_id?>" ).empty();
						    virtual_block_seq = 1;
						    $('#virtual_block_grid_<?=$tab_id?>').find('tbody').children('tr').removeClass('row-selected');
					    }
				    },{
					    text: 'Deselect Data',
					    handler: function () {
						    virtual_block_grid_<?=$tab_id?>.getSelectionModel().deselectAll();
//							id_ves_voyage = '';
						    $( "#select-stack_<?=$tab_id?>" ).html('');
						    virtual_block_seq = 1;
						    $('#virtual_block_grid_<?=$tab_id?>').find('tbody').children('tr').removeClass('row-selected');
					    }
				    }
		    ],
//		    features: [virtual_block_filters_<?=$tab_id?>],
		    emptyText: 'No Data Found'
	    });
<?php 
if($id_ves_voyage != ''){
?>
	    virtual_block_grid_<?=$tab_id?>.getSelectionModel().on('selectionchange', function(sm, selectedRecord) {
//		    console.log(sm);
//		    console.log(selectedRecord);
//		    console.log(selectedRecord[0].data.SEQUENCE);
		    if (selectedRecord.length > 0 && selectedRecord[0].data.SEQUENCE == null) {
			    var isSelected = $('#virtual_block_grid_<?=$tab_id?>').find('tbody').children('tr').eq(selectedRecord[0].index).hasClass('row-selected');
			    if(!isSelected){
				virtual_block_seq = $('#virtual_block_grid_<?=$tab_id?>').find('tbody').children('.row-selected').length + 1;
    //				id_ves_voyage = selectedRecord[0].data.ID_VES_VOYAGE;
				if ($( "#select-stack_<?=$tab_id?>" ).html()!=""){
					$( "#select-stack_<?=$tab_id?>" ).append(",");
				}
				$( "#select-stack_<?=$tab_id?>" ).append(
					selectedRecord[0].data.NO_CONTAINER+"-"+selectedRecord[0].data.POINT+"-"+virtual_block_seq+"-"+selectedRecord[0].data.CONT_SIZE
				);
    //			    virtual_block_seq++;
				setClass_<?=$tab_id?>(selectedRecord[0].index);
			    }
		    }
	    });
<?php
}
?>

	    Ext.getCmp('west_panel').expand();
	    virtual_block_grid_<?=$tab_id?>.render('virtual_block_grid_<?=$tab_id?>');

    });

function setClass_<?=$tab_id?>(i){
//    console.log('i : ' + i);
    var elem = $('#virtual_block_grid_<?=$tab_id?>').find('tbody').children('tr').eq(i);
//    console.log(elem);
    $(elem).addClass('row-selected');
}
</script>
<span id="select-stack_<?=$tab_id?>" style=""></span>
<div id="virtual_block_grid_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>