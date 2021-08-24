<script type="text/javascript">

	function get_vvd(vessel_name){
	   console.log(vessel_name);
	   // ajax adding data to database
       var url =  '<?=controller_?>vessel_voyage/get_vvd';
       $.ajax({
        url : url,
        type: "POST",
        data:{"vessel_name":vessel_name},
        dataType: "JSON",
        success: function(data)
        {
        	if(data.status!=1){
        		alert('Error');
        	}else{
        		// console.log(data.response);
        		Ext.getCmp('length_<?=$tab_id?>').setValue(data.response);
        	}
         },
         error: function (jqXHR, textStatus, errorThrown)
         {
          alert('Error adding / update data');
         }
      });

	}


	var vessel_list_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_VESSEL', 'VESSEL_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>vessel_voyage/data_vessel_list/',
			reader: {
				type: 'json'
			}
		}
		<?php if($mode=='edit'){?>
		,
		autoLoad: true
		<?php }?>
	});
	
	var kade_list_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_KADE', 'KADE_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>vessel_voyage/data_kade/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});

	/*ID_COMPANY
	COMPANY_NAME*/

	var stv_company_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_COMPANY', 'COMPANY_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>vessel_voyage/data_stevedoring_companies/',
			reader: {
				type: 'json'
			}
		},
		
		autoLoad: true
		
	});
	
	var along_side_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ALONG_SIDE', 'ALONG_SIDE_TEXT'],
		data : [
			 {ALONG_SIDE: 'P', ALONG_SIDE_TEXT: 'P:Port Side'},
			 {ALONG_SIDE: 'S', ALONG_SIDE_TEXT: 'S:Starboard Side'}
		]
	});
	
	var service_list_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['ID_VESSEL_SERVICE', 'VESSEL_SERVICE_NAME'],
		proxy: {
			type: 'ajax',
			url: '<?=controller_?>vessel_voyage/data_vessel_service/',
			reader: {
				type: 'json'
			}
		},
		autoLoad: true
	});

	/*var small_vessel_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['FL_TONGKANG', 'SMALL_VESSEL'],
		data : [
			 {FL_TONGKANG: 'Y', SMALL_VESSEL:'Yes'},
			 {FL_TONGKANG: 'N', SMALL_VESSEL:'No'}
		]
	});*/

	var tl_receiving_store_<?=$tab_id?> = Ext.create('Ext.data.Store', {
		fields:['TL_RECEIVING'],
		data : [
			 {TL_RECEIVING: 'Y'},
			 {TL_RECEIVING: 'N'}
		]
	});
	
	Ext.create('Ext.form.Panel', {
		id: "vessel_voyage_form_<?=$tab_id?>",
		bodyPadding: 5,
		fieldDefaults: {
			labelAlign: 'left',
			labelWidth: 150
		},
		url: '<?=controller_?>vessel_voyage/save_vessel_voyage',
		tbar: ['->',{
			xtype: 'button',
			//text: 'New Vessel Voyage',
			text: 'New Vessel Voyage Details',
			handler: function(){
				addTab('center_panel', 'Vessel Voyage','','Vessel Voyage Details');
			}
		}],
		items: [{
			id: "id_ves_voyage_<?=$tab_id?>",
			xtype: 'hiddenfield',
			name: 'ID_VES_VOYAGE'
		},{
			id: 'vessel_name_<?=$tab_id?>',
			xtype: 'hiddenfield',
			name: 'VESSEL_NAME'
		},{
			id: 'length_<?=$tab_id?>',
			xtype: 'hiddenfield',
			name: 'LENGTH'
		}
		,{
			xtype: 'fieldset',
			title: 'Vessel Details',
			items: [
			<?php if($mode=='edit'){?>
				{
					xtype: 'fieldset',
					border: false,
					items: [{
						xtype: 'button',
						margin: '0 5 0 0',
						text: 'Container Operator',
						handler: function (){
							Ext.Ajax.request({
								url: '<?=controller_?>vessel_voyage/popup_container_operator?tab_id=<?=$tab_id?>',
								params: {
									id_ves_voyage: Ext.getCmp("id_ves_voyage_<?=$tab_id?>").getRawValue()
								},
								callback: function(opt,success,response){
									$("#popup_script_<?=$tab_id?>").html(response.responseText);
								} 
							});
						}
					},{
						xtype: 'button',
						text: 'Port of Discharge',
						handler: function (){
							Ext.Ajax.request({
								url: '<?=controller_?>vessel_voyage/popup_vessel_port?tab_id=<?=$tab_id?>',
								params: {
									id_ves_voyage: Ext.getCmp("id_ves_voyage_<?=$tab_id?>").getRawValue()
								},
								callback: function(opt,success,response){
									$("#popup_script_<?=$tab_id?>").html(response.responseText);
								} 
							});
						}
					}]
				},{
					xtype: 'hiddenfield',
					name: "ID_VESSEL"
				},
			<?php }?>
			{
				id: "vessel_<?=$tab_id?>",
				<?php if($mode=='edit'){?>
				xtype: 'displayfield',
				name: "VESSEL",
				<?php }else{?>
				xtype: 'combo',
				displayField: 'VESSEL_NAME',
				valueField: 'ID_VESSEL',
				store: vessel_list_store_<?=$tab_id?>,
				queryMode: 'remote',
				forceSelection: true,
				hideTrigger: true,
				triggerAction: 'query',
				emptyText: 'Autocomplete',
				typeAhead: true,
				minChars: 3,
				name: "ID_VESSEL",
				listeners: {
					select: function(el, record){
						console.log("value : "+el.getRawValue());

						var value = el.getRawValue();

						get_vvd(value);

						Ext.getCmp('vessel_name_<?=$tab_id?>').setValue(el.getRawValue());
					}
				},
				<?php }?>
				fieldLabel: 'Vessel',
				allowBlank: false
			},{
				id: "voy_in_<?=$tab_id?>",
				xtype: 'textfield',
				name: "VOY_IN",
				fieldLabel: 'Voyage In',
				allowBlank: false
			},{
				id: "voy_out_<?=$tab_id?>",
				xtype: 'textfield',
				name: "VOY_OUT",
				fieldLabel: 'Voyage Out',
				allowBlank: false
			}]
		},{
			xtype: 'fieldset',
			title: 'Berth Information',
			items: [{
				id: "kade_<?=$tab_id?>",
				xtype: 'combo',
				displayField: 'KADE_NAME',
				valueField: 'ID_KADE',
				queryMode: 'local',
				editable: false,
				store: kade_list_store_<?=$tab_id?>,
				name: "ID_KADE",
				fieldLabel: 'Berth',
				allowBlank: false
			},{
				id: "along_side_<?=$tab_id?>",
				xtype: 'combo',
				displayField: 'ALONG_SIDE_TEXT',
				valueField: 'ALONG_SIDE',
				queryMode: 'local',
				editable: false,
				store: along_side_store_<?=$tab_id?>,
				name: "ALONG_SIDE",
				fieldLabel: 'Along Side',
				allowBlank: false
			},{
				id: "start_meter_<?=$tab_id?>",
				xtype: 'numberfield',
				name: "START_METER",
				fieldLabel: 'Start Position (m)',
				allowDecimals: false,
				allowBlank: false,
			 	enableKeyEvents : true,
				listeners: { 
                	blur : function(obj) { 
                 		var start_meter = obj.getValue();
                 		
                 		var end_position = $('#length_<?=$tab_id?>-inputEl').val();
                 		
                 		var total = parseInt(start_meter) + parseInt(end_position);

                 		console.log("start_meter : "+start_meter+" + end_position : "+end_position+" = "+total);

                 		Ext.getCmp('end_meter_<?=$tab_id?>').setValue(total);
                 	} 
               }
			},{
				id: "end_meter_<?=$tab_id?>",
				xtype: 'numberfield',
				name: "END_METER",
				fieldLabel: 'End Position (m)',
				allowDecimals: false,
				allowBlank: false,
				editable:false
			}]
		},{
			xtype: 'fieldset',
			title: 'Estimate Berthing Time',
			items: [{
				xtype: 'fieldcontainer',
				fieldLabel: 'Arrival (ETA)',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [{
					id: "eta_date_<?=$tab_id?>",
					xtype: 'datefield',
					name: "ETA_DATE",
					fieldLabel: 'Arrival Date',
					emptyText: 'Pick Date',
					format: 'd-m-Y',
					width: 120,
					editable: false,
					allowBlank: false
				},{
					id: "eta_hour_<?=$tab_id?>",
					xtype: 'textfield',
					name: "ETA_HOUR",
					fieldLabel: 'Arrival Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23',
					allowBlank: false
				},{
					id: "eta_min_<?=$tab_id?>",
					xtype: 'textfield',
					name: "ETA_MIN",
					fieldLabel: 'Arrival Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59',
					allowBlank: false
				}]
			},{
				xtype: 'fieldcontainer',
				fieldLabel: 'Berth (ETB)',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [{
					id: "etb_date_<?=$tab_id?>",
					xtype: 'datefield',
					name: "ETB_DATE",
					fieldLabel: 'Berth Date',
					emptyText: 'Pick Date',
					format: 'd-m-Y',
					width: 120,
					editable: false,
					allowBlank: false
				},{
					id: "etb_hour_<?=$tab_id?>",
					xtype: 'textfield',
					name: "ETB_HOUR",
					fieldLabel: 'Berth Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23',
					allowBlank: false
				},{
					id: "etb_min_<?=$tab_id?>",
					xtype: 'textfield',
					name: "ETB_MIN",
					fieldLabel: 'Berth Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59',
					allowBlank: false
				}]
			},{
				xtype: 'fieldcontainer',
				fieldLabel: 'Departure (ETD)',
				layout: 'hbox',
				combineErrors: true,
				defaultType: 'textfield',
				defaults: {
					hideLabel: 'true'
				},
				items: [{
					id: "etd_date_<?=$tab_id?>",
					xtype: 'datefield',
					name: "ETD_DATE",
					fieldLabel: 'Departure Date',
					emptyText: 'Pick Date',
					format: 'd-m-Y',
					width: 120,
					editable: false,
					allowBlank: false
				},{
					id: "etd_hour_<?=$tab_id?>",
					xtype: 'textfield',
					name: "ETD_HOUR",
					fieldLabel: 'Departure Hour',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0,1]?\d|2[0-3])$/,
					regexText: 'Value of this field must between 0-23',
					allowBlank: false
				},{
					id: "etd_min_<?=$tab_id?>",
					xtype: 'textfield',
					name: "ETD_MIN",
					fieldLabel: 'Departure Minute',
					minLength: 1,
					maxLength: 2,
					enforceMaxLength: true,
					width: 50,
					maskRe: /[\d]/,
					regex: /^([0-5]?\d)$/,
					regexText: 'Value of this field must between 0-59',
					allowBlank: false
				}]
			}]
		},{
			xtype: 'fieldcontainer',
			fieldLabel: 'Cut-off Time (Document)',
			layout: 'hbox',
			combineErrors: true,
			defaultType: 'textfield',
			defaults: {
				hideLabel: 'true'
			},
			items: [{
				id: "cutoff_date_doc_<?=$tab_id?>",
				xtype: 'datefield',
				name: "CUTOFF_DATE_DOC",
				fieldLabel: 'Cut-off Date Doc',
				emptyText: 'Pick Date',
				format: 'd-m-Y',
				width: 120,
				editable: false,
				allowBlank: false
			},{
				id: "cutoff_hour_doc_<?=$tab_id?>",
				xtype: 'textfield',
				name: "CUTOFF_HOUR_DOC",
				fieldLabel: 'Cut-off Hour Doc',
				minLength: 1,
				maxLength: 2,
				enforceMaxLength: true,
				width: 50,
				maskRe: /[\d]/,
				regex: /^([0,1]?\d|2[0-3])$/,
				regexText: 'Value of this field must between 0-23',
				allowBlank: false
			},{
				id: "cutoff_min_doc_<?=$tab_id?>",
				xtype: 'textfield',
				name: "CUTOFF_MIN_DOC",
				fieldLabel: 'Cut-off Minute Doc',
				minLength: 1,
				maxLength: 2,
				enforceMaxLength: true,
				width: 50,
				maskRe: /[\d]/,
				regex: /^([0-5]?\d)$/,
				regexText: 'Value of this field must between 0-59',
				allowBlank: false
			}]
		},{
			xtype: 'fieldcontainer',
			fieldLabel: 'Cut-off Time (Physic)',
			layout: 'hbox',
			combineErrors: true,
			defaultType: 'textfield',
			defaults: {
				hideLabel: 'true'
			},
			items: [{
				id: "cutoff_date_<?=$tab_id?>",
				xtype: 'datefield',
				name: "CUTOFF_DATE",
				fieldLabel: 'Cut-off Date',
				emptyText: 'Pick Date',
				format: 'd-m-Y',
				width: 120,
				editable: false,
				allowBlank: false
			},{
				id: "cutoff_hour_<?=$tab_id?>",
				xtype: 'textfield',
				name: "CUTOFF_HOUR",
				fieldLabel: 'Cut-off Hour',
				minLength: 1,
				maxLength: 2,
				enforceMaxLength: true,
				width: 50,
				maskRe: /[\d]/,
				regex: /^([0,1]?\d|2[0-3])$/,
				regexText: 'Value of this field must between 0-23',
				allowBlank: false
			},{
				id: "cutoff_min_<?=$tab_id?>",
				xtype: 'textfield',
				name: "CUTOFF_MIN",
				fieldLabel: 'Cut-off Minute',
				minLength: 1,
				maxLength: 2,
				enforceMaxLength: true,
				width: 50,
				maskRe: /[\d]/,
				regex: /^([0-5]?\d)$/,
				regexText: 'Value of this field must between 0-59',
				allowBlank: false
			}]
		},{
			xtype: 'fieldcontainer',
			fieldLabel: 'Open Stack',
			layout: 'hbox',
			combineErrors: true,
			defaultType: 'textfield',
			defaults: {
				hideLabel: 'true'
			},
			items: [{
				id: "open_stack_date_<?=$tab_id?>",
				xtype: 'datefield',
				name: "OPEN_STACK_DATE",
				fieldLabel: 'Open Stack Date',
				emptyText: 'Pick Date',
				format: 'd-m-Y',
				width: 120,
				editable: false,
				allowBlank: false
			},{
				id: "open_stack_hour_<?=$tab_id?>",
				xtype: 'textfield',
				name: "OPEN_STACK_HOUR",
				fieldLabel: 'Open Stack Hour',
				minLength: 1,
				maxLength: 2,
				enforceMaxLength: true,
				width: 50,
				maskRe: /[\d]/,
				regex: /^([0,1]?\d|2[0-3])$/,
				regexText: 'Value of this field must between 0-23',
				allowBlank: false
			},{
				id: "open_stack_min_<?=$tab_id?>",
				xtype: 'textfield',
				name: "OPEN_STACK_MIN",
				fieldLabel: 'Open Stack Minute',
				minLength: 1,
				maxLength: 2,
				enforceMaxLength: true,
				width: 50,
				maskRe: /[\d]/,
				regex: /^([0-5]?\d)$/,
				regexText: 'Value of this field must between 0-59',
				allowBlank: false
			}]
		},

		// booking stack
		{
			xtype: 'fieldcontainer',
			fieldLabel: 'Early Stack',
			layout: 'hbox',
			combineErrors: true,
			defaultType: 'textfield',
			defaults: {
				hideLabel: 'true'
			},
			items: [{
				id: "early_stack_date_<?=$tab_id?>",
				xtype: 'datefield',
				name: "EARLY_STACK_DATE",
				fieldLabel: 'Early Stack Date',
				emptyText: 'Pick Date',
				format: 'd-m-Y',
				width: 120,
				editable: false
				//allowBlank: false
			},{
				id: "early_stack_hour_<?=$tab_id?>",
				xtype: 'textfield',
				name: "EARLY_STACK_HOUR",
				fieldLabel: 'Early Stack Hour',
				minLength: 1,
				maxLength: 2,
				enforceMaxLength: true,
				width: 50,
				maskRe: /[\d]/,
				regex: /^([0,1]?\d|2[0-3])$/,
				regexText: 'Value of this field must between 0-23'
				//allowBlank: false
			},{
				id: "early_stack_min_<?=$tab_id?>",
				xtype: 'textfield',
				name: "EARLY_STACK_MIN",
				fieldLabel: 'Early Stack Minute',
				minLength: 1,
				maxLength: 2,
				enforceMaxLength: true,
				width: 50,
				maskRe: /[\d]/,
				regex: /^([0-5]?\d)$/,
				regexText: 'Value of this field must between 0-59'
				//allowBlank: false
			}]
		},
		// end

		/*{
			id: "booking_stack_<?=$tab_id?>",
			xtype: 'numberfield',
			name: "BOOKING_STACK",
			fieldLabel: 'Booking Stack (TEU)',
			allowDecimals: false
		},*/

		{
			id: "request_booking_stack_<?=$tab_id?>",
			xtype: 'numberfield',
			name: "BOOKING_STACK",
			fieldLabel: 'Request Booking Stack (TEU)',
			allowDecimals: false
		},

		{
			id: "approve_booking_stack_<?=$tab_id?>",
			xtype: 'numberfield',
			name: "APP_BOOKING_STACK",
			fieldLabel: 'Approved Booking Stack (TEU)',
			allowDecimals: false
		},

		{
			id: "stv_company_<?=$tab_id?>",
			xtype: 'combo',
			displayField: 'COMPANY_NAME',
			valueField: 'ID_COMPANY',
			queryMode: 'local',
//			editable: false,
			store: stv_company_store_<?=$tab_id?>,
			name: "ID_COMPANY",
			emptyText: 'Autocomplete',
			fieldLabel: 'Stevedoring Company',
			allowBlank: false,
//			anchor:'40%',
			typeAhead: true,
			triggerAction: 'query'
		},
//			{
//			id: "stv_company_<?=$tab_id?>",
//			xtype: 'combo',
//			displayField: 'NO_CONTAINERX',
//			valueField: 'CONT_INFO',
//			queryMode: 'remote',
//			store: container_list_store_<?=$tab_id?>,
//			name: "ID_COMPANY",
//			emptyText: 'Autocomplete',
//			fieldLabel: 'No. Container',
//			afterLabelTextTpl: required,
//			allowBlank: false,
////			anchor:'40%',
//			typeAhead: true,
//			minChars: 4,
//			triggerAction: 'query',
//			fieldStyle: 'background-color: #ffffcc; background-image: none;'
//		},

		{
			id: "tl_receiving_store_<?=$tab_id?>",
			xtype: 'combo',
			displayField: 'TL_RECEIVING',
			valueField: 'TL_RECEIVING',
			queryMode: 'local',
			editable: false,
			store: tl_receiving_store_<?=$tab_id?>,
			name: "TL_RECEIVING",
			fieldLabel: 'TL RECEIVING',
			allowBlank: false
		},

		{
			xtype: 'fieldset',
			title: 'Service Lane',
			items: [{
				id: "in_service_<?=$tab_id?>",
				xtype: 'combo',
				displayField: 'VESSEL_SERVICE_NAME',
				valueField: 'ID_VESSEL_SERVICE',
				queryMode: 'local',
				editable: false,
				store: service_list_store_<?=$tab_id?>,
				name: "IN_SERVICE",
				fieldLabel: 'In Service',
				allowBlank: false
			},{
				id: "out_service_<?=$tab_id?>",
				xtype: 'combo',
				displayField: 'VESSEL_SERVICE_NAME',
				valueField: 'ID_VESSEL_SERVICE',
				queryMode: 'local',
				editable: false,
				store: service_list_store_<?=$tab_id?>,
				name: "OUT_SERVICE",
				fieldLabel: 'Out Service',
				allowBlank: false
			}]
		}/*,{
			xtype:'fieldcontainer',
			id: "tk_<?=$tab_id?>",
			fieldLabel: 'Small Vessel',
			defaultType: 'radiofield',
			anchor:'95%',
			defaults:{
				flex:1
			},
			layout:'hbox',
			items:[
				{
					name 	  : "FL_SMALL_VESSEL",
					boxLabel  : 'Yes',
                    inputValue: 'Y',
                    id        : 'TKTrue<?=$tab_id?>'
				},
				{
					name 	  : "FL_SMALL_VESSEL",
					boxLabel  : 'No',
                    inputValue: 'N',
                    id        : 'TKFalse<?=$tab_id?>'
				}
			]
		}*/,
		/*{
			id: "small_vessel_<?=$tab_id?>",
			xtype: 'combo',
			displayField: 'SMALL_VESSEL',
			valueField: 'FL_TONGKANG',
			queryMode: 'local',
			editable: false,
			store: small_vessel_store_<?=$tab_id?>,
			name: "FL_TONGKANG",
			fieldLabel: 'Small Vessel',
			style: 'background-color: yellow;',
			allowBlank: false,
			listeners:{
			    scope: this,
			    afterRender: function(me){
			        me.setValue('No');   
			    }
			}
		}*/],
		buttons: [{
			text: 'Save',
			formBind: true,
			handler: function() {
				var form = this.up('form').getForm();
				if (form.isValid()){
					loadmask.show();
					Ext.Ajax.request({
						url: '<?=controller_?>vessel_voyage/check_voyage_number',
						params: {
							id_ves_voyage: form.findField("ID_VES_VOYAGE").getValue(),
							id_vessel: form.findField("ID_VESSEL").getValue(),
							voy_in: form.findField("VOY_IN").getRawValue(),
							voy_out: form.findField("VOY_OUT").getRawValue(),
						},
						success: function(response){
							var text = response.responseText;
							if (text=='1'){
								form.submit({
									success: function(form, action) {
										loadmask.hide();
										Ext.Msg.alert('Success', 'save success');
										console.log(action.result.errors);
										Ext.getCmp('<?=$tab_id?>').close();
										vessel_schedule_store.reload();
									},
									failure: function(form, action) {
										console.log(action.result.errors);
										loadmask.hide();
										Ext.Msg.alert('Failed', action.result.errors);
									}
								});
							}else{
								loadmask.hide();
								Ext.MessageBox.show({
									title: 'Error',
									msg: 'Voyage Number Already Exist.',
									buttons: Ext.MessageBox.OK
								});
							}
						}
					});
				}
			}
		}]
	}).render('vessel_voyage_<?=$tab_id?>');
	
	$(document).ready(function(){
		var id_ves_voyage = '<?=$id_ves_voyage?>';
		if (id_ves_voyage!=''){
			var vesVoyage = JSON.parse('<?=$ves_voyage?>');
			console.log(id_ves_voyage);
			console.log(vesVoyage);
			Ext.getCmp('vessel_voyage_form_<?=$tab_id?>').getForm().setValues(vesVoyage);
		}

		/*start_meter_vessel_voyage_1-inputEl*/
		// var start_position = $('#start_meter_vessel_voyage_<?=$tab_id?>-inputEl');

		/*$("#start_meter_vessel_voyage_<?=$tab_id?>-inputEl").keyup(function() {
		  alert( "Handler for .keyup() called." );
		});*/

		//console.log(start_position);

	});

	Ext.onReady(function(){
        console.log("#start_meter_vessel_voyage_<?=$tab_id?>-inputEl");

        $("#start_meter_vessel_voyage_<?=$tab_id?>-inputEl").keyup(function() {
		  console.log("test keyup");
		});

    });

</script>
<div id="vessel_voyage_<?=$tab_id?>"></div>
<div id="popup_script_<?=$tab_id?>"></div>