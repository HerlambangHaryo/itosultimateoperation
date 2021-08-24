<script type="text/javascript">
	var vessel_list_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_YARD','YARD_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>outbound_yard_summary/get_data_yard/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});
	
	function addcn(){
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
	}

	Ext.create('Ext.form.Panel', {
		id: "vessel_voyage_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 150
		},
		//url: '<?=controller_?>report_bch/save_vessel_voyage',
		items: [
		// FIELD SELECT VESSEL
		{
			xtype: 'fieldset',
			title: 'Choose Yard',
			items: [
			{
				id: "vessel_<?=$tab_id?>",
				xtype: 'combo',
				width: 500,
				displayField: 'YARD_NAME',
				valueField: 'ID_YARD',
				store: vessel_list_store_<?=$tab_id?>,
				queryMode: 'local',
				editable: false,
				name: "YARD",
				fieldLabel: 'Yard',
				allowBlank: false
			}
			]
		},

		],
		buttons: [{
			text: 'Show Data',
			//formBind: true,
			handler: function() {
				var form = this.up('form').getForm();
				var yard = form.findField("YARD").getValue();
				var id_ves_voyage = '<?=$id_ves_voyage?>';
				
				if (form.isValid()){
					loadmask.show();
					
					/*show report*/
					$.ajax({
			            url : '<?=controller_?>outbound_yard_summary/get_data_yard_outbound/'+yard+'/<?=$tab_id?>/null/'+id_ves_voyage,
			            type: "POST",
			            data: {"id_yard" : yard,"tab_id" : <?=$tab_id?>}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			            contentType: false, // The content type used when sending data to the server.
			            cache: false,       // To unable request pages to be cached
			            processData:false,
			            //dataType: "JSON",
			          success: function(data)
			          {
			          	$('#outbound_report_<?=$tab_id?>').html(data);
						loadmask.hide();
						$('td.loadstack').click(function(){
							if($(this).text().trim()!=''){
								loadmask.show();
								var datastack = $(this).data('stack');
								var dataslot = $(this).data('slot');
								var fc = datastack.charAt(0);
								if(fc !='-'){
									$.ajax({
										url : '<?=controller_?>outbound_yard_summary/get_slot_yard_outbound/'+dataslot,
										type: "POST",
										data: {"dataslot" : dataslot},
										contentType: false, 
										cache: false, 
										processData:false,
									  success: function(nilaislot)
									  {
										Ext.getCmp('west_panel').expand();
										addTab('west_panel', 'single_stack_view', datastack+'-'+nilaislot, 'Single Stack View');
										// console.log(nilaislot);
									  },
									  error: function (jqXHR, textStatus, errorThrown)
									  {
										loadmask.hide();
									  }
									});
								}else{
									loadmask.hide();
									alert("ID Block Ini Kosong");
								}
							}
						});
						addcn();
						if($('.<?=$tab_id?> .cnkosonghide').length>0){
							// Ext.getCmp('ToggleButton<?=$tab_id?>').show();
						}
			          },
			          error: function (jqXHR, textStatus, errorThrown)
			          {
						loadmask.hide();
			            alert('Error get data from ajax');
			          }
			        });

				} else {
					loadmask.hide();
					Ext.Msg.alert('Failed', 'Choose Yard');
				}
			}
		},
		{
			text: 'Toggle',
			id: 'ToggleButton<?=$tab_id?>',
			hidden: true,
			//formBind: true,
			handler: function() {
				if($('.<?=$tab_id?> .cnshow').length>0){
					$('.<?=$tab_id?> .cnkosonghide').removeClass('cnshow');
					$('.<?=$tab_id?> .tdhide').removeClass('tdshow');
					$('.<?=$tab_id?> td.topfn').attr('colspan',$('.<?=$tab_id?> td.topfn').attr('colbaru'));
					$('.<?=$tab_id?> td.topfy').attr('colspan',$('.<?=$tab_id?> td.topfy').attr('colbaru'));
					$('.<?=$tab_id?> td.topmn').attr('colspan',$('.<?=$tab_id?> td.topmn').attr('colbaru'));
					$('.<?=$tab_id?> td.topmy').attr('colspan',$('.<?=$tab_id?> td.topmy').attr('colbaru'));
					$('.<?=$tab_id?> td.tdubah').each(function(){
						$(this).attr('rowspan',$(this).attr('rowbaru'));
					});
					$('.<?=$tab_id?> th.topn').attr('colspan',$('.<?=$tab_id?> th.topn').attr('colbaru'));
					$('.<?=$tab_id?> th.topy').attr('colspan',$('.<?=$tab_id?> th.topy').attr('colbaru'));
				}else{
					$('.<?=$tab_id?> .cnkosonghide').addClass('cnshow');
					$('.<?=$tab_id?> .tdhide').addClass('tdshow');
					$('.<?=$tab_id?> td.topfn').attr('colspan',$('.<?=$tab_id?> td.topfn').attr('colasli'));
					$('.<?=$tab_id?> td.topfy').attr('colspan',$('.<?=$tab_id?> td.topfy').attr('colasli'));
					$('.<?=$tab_id?> td.topmn').attr('colspan',$('.<?=$tab_id?> td.topmn').attr('colasli'));
					$('.<?=$tab_id?> td.topmy').attr('colspan',$('.<?=$tab_id?> td.topmy').attr('colasli'));
					$('.<?=$tab_id?> td.tdubah').each(function(){
						$(this).attr('rowspan',$(this).attr('rowasli'));
					});
					$('.<?=$tab_id?> th.topn').attr('colspan',$('.<?=$tab_id?> th.topn').attr('colasli'));
					$('.<?=$tab_id?> th.topy').attr('colspan',$('.<?=$tab_id?> th.topy').attr('colasli'));
				}
			}
		},
		{
			text: 'Print',
			//formBind: true,
			handler: function() {
				var form = this.up('form').getForm();
				var yard = form.findField("YARD").getValue();
				var id_ves_voyage = '<?=$id_ves_voyage?>';

				if(yard == 'null'){
					Ext.Msg.alert('Failed', 'Choose Yard');
					return false;
				}
				
				if (form.isValid()){
					window.open('<?=controller_?>outbound_yard_summary/get_data_yard_outbound/'+yard+'/<?=$tab_id?>/excel/'+id_ves_voyage);

				} else {
					Ext.Msg.alert('Failed', 'Choose Yard');
				}
			}
		}]
	}).render('vessel_voyage_<?=$tab_id?>');

</script>
<div id="vessel_voyage_<?=$tab_id?>"></div>
<div id="outbound_report_<?=$tab_id?>"></div>

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