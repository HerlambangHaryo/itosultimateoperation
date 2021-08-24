<script type="text/javascript">
	var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
Ext.apply(Ext.form.VTypes, {
	daterange : function(val, field) {
		var date = field.parseDate(val);

		if(!date){
			return;
		}
		if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
			var start = Ext.getCmp(field.startDateField);
			start.setMaxValue(date);
			start.validate();
			this.dateRangeMax = date;
		} 
		else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
			var end = Ext.getCmp(field.endDateField);
			end.setMinValue(date);
			end.validate();
			this.dateRangeMin = date;
		}
		/*
		 * Always return true since we're only using this vtype to set the
		 * min/max allowed values (these are tested for after the vtype test)
		 */
		return true;
	}
});
// Add the additional 'advanced' VTypes -- [End]

dateRangeFunc();
function dateRangeFunc()
	{
		// Date picker				
		var fromdate = new Ext.form.DateField({
			format: 'd-m-Y', //YYYY-MMM-DD
			fieldLabel: '',
			id: 'start_period_<?=$tab_id?>',
			name: 'start_period_<?=$tab_id?>',
			emptyText: 'Date',
			width:140,
			allowBlank:false,
			vtype: 'daterange',
			afterLabelTextTpl: required,
            endDateField: 'end_period_<?=$tab_id?>'// id of the 'To' date field
		});
		
		var todate = new Ext.form.DateField({
			format: 'd-m-Y', //YYYY-MMM-DD
			fieldLabel: '',
			id: 'end_period_<?=$tab_id?>',
			name: 'end_period_<?=$tab_id?>',
			emptyText: 'Date',
			width:140,
			allowBlank:false,
			vtype: 'daterange',
			afterLabelTextTpl: required,
            startDateField: 'start_period_<?=$tab_id?>'// id of the 'From' date field
		});
		
		fromdate.render('fromdate<?=$tab_id?>');
		todate.render('todate<?=$tab_id?>');
} //dateRangeFunc() close

Ext.create('Ext.form.Panel', {
		id: "bor_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 100
		},
		url: '<?=controller_?>report_bor/get_data_bor?tab_id=<?=$tab_id?>',
		buttons: [{
			text: 'Show',
			handler: function() {
				var start_period = $('#start_period_<?=$tab_id?>-inputEl').val();
				var end_period = $('#end_period_<?=$tab_id?>-inputEl').val();

				if (start_period == "" || end_period == "") {
					alert ("Periode Awal atau Akhir tidak boleh kosong");
				} else {
					if (start_period == end_period) {
						var temp = new Date( end_period.replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3") );
						temp.setDate(temp.getDate() + 1);
						var mm = temp.getMonth() + 1; // getMonth() is zero-based
						var dd = temp.getDate();
						end_period = [(dd>9 ? '' : '0') + dd, (mm>9 ? '' : '0') + mm, temp.getFullYear()].join('-');
						console.log(end_period);
					}
					loadmask.show();
					Ext.Ajax.request({
						url: '<?=controller_?>report_bor/get_data_bor_show',
					params: {
						START_PERIOD: start_period,
						END_PERIOD: end_period
					},
					success: function(response){
						loadmask.hide();
						if(response.status=='200'){
							$('#reportbor_<?=$tab_id?>').html(response.responseText);
						}else{
							Ext.Msg.alert('Failed');
						}
					}
					});
				}
			}
		},{
			text: 'Export to Excel',
			handler: function() {
				var start_period = $('#start_period_<?=$tab_id?>-inputEl').val();
				var end_period = $('#end_period_<?=$tab_id?>-inputEl').val();

				if (start_period == "" || end_period == "") {
					alert ("Periode Awal atau Akhir tidak boleh kosong");
				} else {
					if (start_period == end_period) {
						var temp = new Date( end_period.replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3") );
						temp.setDate(temp.getDate() + 1);
						var mm = temp.getMonth() + 1; // getMonth() is zero-based
						var dd = temp.getDate();
						end_period = [(dd>9 ? '' : '0') + dd, (mm>9 ? '' : '0') + mm, temp.getFullYear()].join('-');
						console.log(end_period);
					}
					var url = '<?=controller_?>report_bor/get_data_bor?START_PERIOD='+start_period+'&END_PERIOD='+end_period;
					window.open(url,'_blank');
				}
			}
		}]
	}).render('bor_<?=$tab_id?>');
</script>
<div id="bor_<?=$tab_id?>">
	<div>
		</br>
		<div style="float:left; padding-left:20px; "><strong>Periode Awal : </strong>
			<div id="fromdate<?=$tab_id?>"></div>
		</div>
		<div style="float:left; padding-left:20px;"><strong>Periode Akhir: </strong>
			<div id="todate<?=$tab_id?>"></div>
		</div>
		<div style="clear:both"></div>
	</div>
	<br clear="all" />
</div>
<div id="reportbor_<?=$tab_id?>"></div>
<style>
#reportbor_<?=$tab_id?>  {
    margin: 10px;
}

#reportbor_<?=$tab_id?> table {
    width: calc(100% - 80px);
    border-collapse: collapse;
}

#reportbor_<?=$tab_id?> table td.title {
    font-size: 20px;
    font-weight: bold;
    text-align: center;
    background: #d2d2d2;
    padding: 10px;
}

#reportbor_<?=$tab_id?> table td {
    padding: 5px;
}

#reportbor_<?=$tab_id?> table.widthtengah {
    width: auto;
    text-align: left;
}

</style>
