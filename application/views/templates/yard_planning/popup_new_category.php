<script type="text/javascript">
function PAWeightDialog() {
    Ext.Ajax.request({
        url: '<?=controller_?>yard_planning/popup_master_paweight?tab_id=<?=$tab_id?>',
        callback: function(opt, success, response) {
            $("#popup_script_<?=$tab_id?>").html(response.responseText);
        }
    });
}

function DelPAWeightDialog() {
    Ext.Ajax.request({
        url: '<?=controller_?>yard_planning/popup_master_del_paweight?tab_id=<?=$tab_id?>',
        callback: function(opt, success, response) {
            $("#popup_script_<?=$tab_id?>").html(response.responseText);
        }
    });
}

$(function() {
    var cont_size_list_store = Ext.create('Ext.data.Store', {
        fields: ['CONT_SIZE', 'NAME'],
        proxy: {
            type: 'ajax',
            url: '<?=controller_?>yard_planning/data_cont_size/',
            reader: {
                type: 'json'
            }
        },
        autoLoad: true
    });

    var cont_type_list_store = Ext.create('Ext.data.Store', {
        fields: ['CONT_TYPE', 'NAME'],
        proxy: {
            type: 'ajax',
            url: '<?=controller_?>yard_planning/data_cont_type/',
            reader: {
                type: 'json'
            }
        },
        autoLoad: true
    });

    var cont_status_list_store = Ext.create('Ext.data.Store', {
        fields: ['CONT_STATUS', 'NAME'],
        proxy: {
            type: 'ajax',
            url: '<?=controller_?>yard_planning/data_cont_status/',
            reader: {
                type: 'json'
            }
        },
        autoLoad: true
    });

    var port_list_store = Ext.create('Ext.data.Store', {
        fields: ['PORT_CODE', 'PORT_NAME'],
        proxy: {
            type: 'ajax',
            url: '<?=controller_?>yard_planning/data_port/',
            reader: {
                type: 'json'
            }
        }
    });

    /*var vessel_schedule_list_store = Ext.create('Ext.data.Store', {
    	fields:['ID_VES_VOYAGE', 'VESSEL'],
    	proxy: {
    		type: 'ajax',
    		url: '<?=controller_?>yard_planning/data_vessel_schedule_autocomplete/',
    		reader: {
    			type: 'json'
    		}
    	}
    });*/

    var vessel_schedule_list_store = Ext.create('Ext.data.Store', {
        fields: ['ID_VES_VOYAGE', 'VESSEL'],
        proxy: {
            type: 'ajax',
            url: '<?=controller_?>yard_planning/data_active_vessel/',
            reader: {
                type: 'json'
            }
        },
        autoLoad: true
    });


    var operator_list_store = Ext.create('Ext.data.Store', {
        fields: ['ID_OPERATOR', 'OPERATOR_NAME'],
        proxy: {
            type: 'ajax',
            url: '<?=controller_?>yard_planning/data_operator/',
            reader: {
                type: 'json'
            }
        }
    });

    var cont_height_list_store = Ext.create('Ext.data.Store', {
        fields: ['CONT_HEIGHT', 'NAME'],
        proxy: {
            type: 'ajax',
            url: '<?=controller_?>yard_planning/data_cont_height/',
            reader: {
                type: 'json'
            }
        },
        autoLoad: true
    });

    var cont_weightpa_list_store = Ext.create('Ext.data.Store', {
        fields: ['ID_PAWEIGHT', 'NAME_PAWEIGHT'],
        proxy: {
            type: 'ajax',
            url: '<?=controller_?>yard_planning/data_paweight/',
            reader: {
                type: 'json'
            }
        },
        autoLoad: true
    });

    var cont_weightpaD_list_store = Ext.create('Ext.data.Store', {
        fields: ['ID_PAWEIGHT', 'DNAME_PAWEIGHT', 'TAMPIL']
    });

    var hazard_list_store = Ext.create('Ext.data.Store', {
        fields: ['ID', 'NAME'],
        data: [{
                ID: 'Y',
                NAME: 'Yes'
            },
            {
                ID: 'N',
                NAME: 'No'
            }
        ]
    });

    var imdg_list_store = Ext.create('Ext.data.Store', {
        fields: ['IMDG', 'IMDG'],
        proxy: {
            type: 'ajax',
            url: '<?=controller_?>yard_planning/data_imdg_list/',
            reader: {
                type: 'json'
            }
        },
        autoLoad: true
    });

    var unno_list_store = Ext.create('Ext.data.Store', {
        fields: ['UNNO', 'IMDG', 'DESCRIPTION']
    });

    /*var imdg_list_store = Ext.create('Ext.data.Store', {
    	fields:['IMDG', 'IMDG'],
    	proxy: {
    		type: 'ajax',
    		url: '<?=controller_?>yard_planning/data_imdg_list/',
    		reader: {
    			type: 'json'
    		}
    	},
    	autoLoad: true
    });*/

    var activity_list_store = Ext.create('Ext.data.Store', {
        fields: ['ID', 'NAME'],
        data: [{
                ID: 'E',
                NAME: 'Outbound'
            },
            {
                ID: 'I',
                NAME: 'Inbound'
            },
            {
                ID: 'T',
                NAME: 'Transhipment'
            }
        ]
    });

    var voyage_type_list_store = Ext.create('Ext.data.Store', {
        fields: ['ID', 'NAME'],
        data: [
            // {ID: 'O', NAME: 'Ocean Going'},
            {
                ID: 'I',
                NAME: 'Intersuler'
            }
        ]
    });

    var category_detail_store = Ext.create('Ext.data.Store', {
        fields: ['ID_CATEGORY', 'ID_DETAIL', 'E_I', 'ID_VES_VOYAGE', 'ID_PORT_DISCHARGE', 'CONT_SIZE',
            'CONT_TYPE', 'PAWEIGHT', 'PAWEIGHT_D', 'HAZARD', 'IMDG', 'UNNO', 'CONT_STATUS',
            'ID_OPERATOR', 'CONT_HEIGHT', 'O_I'
        ]
    });

    var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
        clicksToMoveEditor: 1,
        autoCancel: false,
        listeners: {
            edit: function(editor, e, opt) {
                var record = e.record.getChanges();
                var array = $.map(record, function(value, index) {
                    return [value];
                });
            }
        }
    });

    var win = new Ext.Window({
        layout: 'fit',
        modal: true,
        title: 'Plan Category',
        closable: false,
        width: 1350,
        items: [Ext.create('Ext.form.Panel', {
            frame: true,
            bodyPadding: 5,
            loadmask: true,
            fieldDefaults: {
                labelAlign: 'left',
                labelWidth: 100
            },
            items: [{
                id: "category_name_<?=$tab_id?>",
                xtype: 'textfield',
                name: "category_name_<?=$tab_id?>",
                fieldLabel: 'Category Name',
                maskRe: /[\w\s]/,
                regex: /[\w\s]/,
                allowBlank: false
            }, {
                id: 'category_detail_<?=$tab_id?>',
                xtype: 'grid',
                width: 1325,
                minHeight: 150,
                autoScroll: true,
                store: category_detail_store,
                tbar: [{
                    itemId: 'add_detail_<?=$tab_id?>',
                    text: 'Add Detail',
                    handler: function() {
                        rowEditing.cancelEdit();
                        var r = {
                            CONT_SIZE: '-',
                            CONT_TYPE: '-',
                            CONT_STATUS: '-',
                            E_I: '-'
                        };
                        category_detail_store.insert(0, r);
                        rowEditing.startEdit(0, 0);
                    }
                }, {
                    itemId: 'remove_detail_<?=$tab_id?>',
                    text: 'Remove Detail',
                    handler: function() {
                        var sm = Ext.getCmp(
                                'category_detail_<?=$tab_id?>')
                            .getSelectionModel();
                        rowEditing.cancelEdit();
                        var selected = sm.getSelection();
                        category_detail_store.remove(selected);
                        if (category_detail_store.getCount() > 0) {
                            sm.select(0);
                        }
                    },
                    disabled: true
                }, {
                    itemId: 'add_paweight_<?=$tab_id?>',
                    text: 'Add PA Weight',
                    handler: function() {
                        PAWeightDialog();
                    }
                }, {
                    itemId: 'del_paweight_<?=$tab_id?>',
                    text: 'Delete PA Weight',
                    handler: function() {
                        DelPAWeightDialog();
                    }
                }],
                plugins: [rowEditing],
                listeners: {
                    'selectionchange': function(view, records) {
                        Ext.getCmp('category_detail_<?=$tab_id?>').down(
                            '#remove_detail_<?=$tab_id?>').setDisabled(!
                            records.length);
                    }
                },
                columns: [{
                        dataIndex: 'ID_CATEGORY',
                        hidden: true,
                        hideable: false
                    },
                    {
                        dataIndex: 'ID_DETAIL',
                        hidden: true,
                        hideable: false
                    },
                    {
                        text: 'Outbound/Inbound',
                        dataIndex: 'E_I',
                        editor: {
                            allowBlank: false,
                            xtype: 'combo',
                            displayField: 'NAME',
                            valueField: 'ID',
                            queryMode: 'local',
                            editable: false,
                            store: activity_list_store
                        }
                    },
                    {
                        text: 'Vessel Voyage',
                        dataIndex: 'ID_VES_VOYAGE',
                        width: 180,
                        editor: {
                            xtype: 'combo',
                            displayField: 'VESSEL',
                            valueField: 'ID_VES_VOYAGE',
                            queryMode: 'local',
                            editable: false,
                            store: vessel_schedule_list_store,
                            allowBlank: true,
                            listeners: {
                                change: function(field, newValue) {
                                    port_list_store.getProxy()
                                    .extraParams = {
                                        id_ves_voyage: newValue
                                    };
                                    Ext.getCmp('id_pod_<?=$tab_id?>')
                                        .getStore().reload();
                                    field.nextSibling().setValue('');
                                    operator_list_store.getProxy()
                                        .extraParams = {
                                            id_ves_voyage: newValue
                                        };
                                    Ext.getCmp('operator_<?=$tab_id?>')
                                        .getStore().reload();
                                    Ext.getCmp('operator_<?=$tab_id?>')
                                        .setValue('');
                                }
                            }
                        }
                    },

                    {
                        text: 'Operator',
                        dataIndex: 'ID_OPERATOR',
                        width: 150,
                        editor: {
                            id: 'operator_<?=$tab_id?>',
                            xtype: 'combo',
                            displayField: 'OPERATOR_NAME',
                            valueField: 'ID_OPERATOR',
                            store: operator_list_store,
                            queryMode: 'remote',
                            hideTrigger: true,
                            triggerAction: 'query',
                            emptyText: 'Autocomplete',
                            typeAhead: true,
                            minChars: 3,
                            listConfig: {
                                emptyText: '<span style="padding:5px;text-align:center;font-style: italic;">' +
                                    'No Data Found' +
                                    '</span>'
                            }
                        }
                    },

                    {
                        text: 'POD',
                        dataIndex: 'ID_PORT_DISCHARGE',
                        width: 180,
                        editor: {
                            id: 'id_pod_<?=$tab_id?>',
                            xtype: 'combo',
                            displayField: 'PORT_NAME',
                            valueField: 'PORT_CODE',
                            store: port_list_store,
                            queryMode: 'remote',
                            hideTrigger: true,
                            triggerAction: 'query',
                            emptyText: 'Autocomplete',
                            typeAhead: true,
                            minChars: 2,
                            listConfig: {
                                emptyText: '<span style="padding:5px;text-align:center;font-style: italic;">' +
                                    'No Data Found' +
                                    '</span>'
                            }
                        }
                    },

                    {
                        text: 'Size',
                        dataIndex: 'CONT_SIZE',
                        width: 70,
                        editor: {
                            xtype: 'combo',
                            displayField: 'NAME',
                            valueField: 'CONT_SIZE',
                            queryMode: 'local',
                            editable: false,
                            store: cont_size_list_store,
                            allowBlank: false
                        }
                    },
                    {
                        text: 'Type',
                        dataIndex: 'CONT_TYPE',
                        editor: {
                            xtype: 'combo',
                            displayField: 'NAME',
                            valueField: 'CONT_TYPE',
                            queryMode: 'local',
                            editable: false,
                            store: cont_type_list_store,
                            allowBlank: false
                        }
                    },

                    {
                        text: 'PAWgCat',
                        dataIndex: 'PAWEIGHT',
                        width: 100,
                        editor: {
                            xtype: 'combo',
                            displayField: 'NAME_PAWEIGHT',
                            valueField: 'ID_PAWEIGHT',
                            queryMode: 'local',
                            editable: false,
                            store: cont_weightpa_list_store,
                            listeners: {
                                change: function(field, newValue) {
                                    cont_weightpaD_list_store.setProxy({
                                        type: 'ajax',
                                        url: '<?=controller_?>yard_planning/get_datapaWeightD/' +
                                            newValue,
                                        reader: {
                                            type: 'json'
                                        }
                                    });
                                    cont_weightpaD_list_store.load();
                                    field.nextSibling().setValue('');
                                },
                                focus: function(field, opts) {
                                    cont_weightpa_list_store.setProxy({
                                        type: 'ajax',
                                        url: '<?=controller_?>yard_planning/data_paweight/',
                                        reader: {
                                            type: 'json'
                                        }
                                    });
                                    cont_weightpa_list_store.load();
                                }
                            }
                        }
                    },

                    {
                        text: 'PAWgSub',
                        dataIndex: 'PAWEIGHT_D',
                        width: 80,
                        editor: {
                            xtype: 'combo',
                            displayField: 'TAMPIL',
                            valueField: 'DNAME_PAWEIGHT',
                            queryMode: 'local',
                            editable: false,
                            store: cont_weightpaD_list_store,
                            listeners: {
                                focus: function(field, opts) {
                                    var paweight_id = field
                                    .previousSibling().getValue();
                                    if (paweight_id != null) {
                                        cont_weightpaD_list_store.setProxy({
                                            type: 'ajax',
                                            url: '<?=controller_?>yard_planning/get_datapaWeightD/' +
                                                paweight_id,
                                            reader: {
                                                type: 'json'
                                            }
                                        });
                                        cont_weightpaD_list_store.load();
                                    }
                                }
                            }
                        }
                    },

                    {
                        text: 'Hazard',
                        dataIndex: 'HAZARD',
                        width: 80,
                        editor: {
                            xtype: 'combo',
                            displayField: 'NAME',
                            valueField: 'ID',
                            queryMode: 'local',
                            editable: false,
                            store: hazard_list_store
                        }
                    },


                    {
                        text: 'IMDG',
                        dataIndex: 'IMDG',
                        width: 100,
                        editor: {
                            xtype: 'combo',
                            displayField: 'IMDG',
                            valueField: 'IMDG',
                            queryMode: 'local',
                            editable: false,
                            store: imdg_list_store,
                            listeners: {
                                change: function(field, newValue) {
                                    unno_list_store.setProxy({
                                        type: 'ajax',
                                        url: '<?=controller_?>yard_planning/get_dataUnno/' +
                                            newValue,
                                        reader: {
                                            type: 'json'
                                        }
                                    });
                                    unno_list_store.load();
                                    field.nextSibling().setValue('');
                                },
                                focus: function(field, opts) {
                                    imdg_list_store.setProxy({
                                        type: 'ajax',
                                        url: '<?=controller_?>yard_planning/data_imdg_list/',
                                        reader: {
                                            type: 'json'
                                        }
                                    });
                                    imdg_list_store.load();
                                }
                            }
                        }
                    },

                    // {
                    //     text: 'UNNO',
                    //     dataIndex: 'UNNO',
                    //     width: 80,
                    //     editor: {
                    //         xtype: 'combo',
                    //         displayField: 'UNNO',
                    //         valueField: 'UNNO',
                    //         queryMode: 'local',
                    //         editable: false,
                    //         store: unno_list_store,
                    //         listeners: {
                    //             focus: function(field, opts) {
                    //                 var imdg_id = field.previousSibling()
                    //                     .getValue();
                    //                 console.log(imdg_id);
                    //                 if (imdg_id != null) {
                    //                     unno_list_store.setProxy({
                    //                         type: 'ajax',
                    //                         url: '<?=controller_?>yard_planning/get_dataUnno/' +
                    //                             imdg_id,
                    //                         reader: {
                    //                             type: 'json'
                    //                         }
                    //                     });
                    //                     unno_list_store.load();
                    //                 }
                    //             }
                    //         }
                    //     }
                    // },

                    {
                        text: 'Height',
                        dataIndex: 'CONT_HEIGHT',
                        width: 80,
                        editor: {
                            xtype: 'combo',
                            displayField: 'NAME',
                            valueField: 'CONT_HEIGHT',
                            queryMode: 'local',
                            editable: false,
                            store: cont_height_list_store
                        }
                    },

                    {
                        text: 'Status',
                        dataIndex: 'CONT_STATUS',
                        width: 80,
                        editor: {
                            xtype: 'combo',
                            displayField: 'NAME',
                            valueField: 'CONT_STATUS',
                            queryMode: 'local',
                            editable: false,
                            store: cont_status_list_store,
                            allowBlank: false
                        }
                    }

                    // ,{
                    //     text: 'Ocean Going/Intersuler',
                    //     dataIndex: 'O_I',
                    //     width: 180,
                    //     editor: {
                    //         xtype: 'combo',
                    //         displayField: 'NAME',
                    //         valueField: 'ID',
                    //         queryMode: 'local',
                    //         editable: false,
                    //         store: voyage_type_list_store
                    //     }
                    // }
                ]
            }],
            buttons: [{
                text: 'Plan',
                formBind: true,
                handler: function() {
                    if (this.up('form').getForm().isValid()) {
                        var category_name = this.up('form').getForm().findField(
                            "category_name_<?=$tab_id?>").getValue();
                        var category_detail = Ext.encode(Ext.pluck(
                            category_detail_store.data.items, 'data'));
                        var min_slot = 0;
                        var max_slot = 0;
                        var str = $("#select-result_<?=$tab_id?>").html();
                        var list_plan = str.split(',');
                        $.each(list_plan, function(i, j) {
                            var temp = j.split('^');
                            if (min_slot == 0) {
                                min_slot = temp[1];
                            }
                            max_slot = temp[1];
                        });

                        var isValid = true;
                        var error_message = '';
                        $.each(Ext.pluck(category_detail_store.data.items,
                            'data'), function(x, y) {
                            console.log('------------');
                            console.log(y);
                            if (y.CONT_SIZE != '20' && y.CONT_SIZE !=
                                '21' && min_slot == max_slot) {
                                isValid = false;
                                error_message =
                                    'Container Size selain 20 dan 21 harus menggunakan lebih dari 1 slot';
                                return false;
                            }

                            if (y.CONT_HEIGHT == '' && y.CONT_SIZE ==
                                '-' && y.CONT_STATUS == '-' && y
                                .CONT_TYPE == '-' && y.E_I == '-' && y
                                .ID_CATEGORY == '' && y
                                .ID_PORT_DISCHARGE == '') {
                                isValid = false;
                                error_message = "Detail can't be empty";
                                return false;
                            }

                            if (y.ID_VES_VOYAGE == '') {
                                isValid = false;
                                error_message =
                                    "Vessel Voyage  must be can't be empty";
                                return false;
                            }
                        });
                        if (isValid) {
                            loadmask.show();
                            Ext.Ajax.request({
                                url: '<?=controller_?>yard_planning/insert_category/',
                                params: {
                                    name: category_name,
                                    detail: category_detail
                                },
                                success: function(response) {
                                    var text = response
                                    .responseText;
                                    // if (text!='0'){
                                    loadmask.hide();
                                    Ext.MessageBox.show({
                                        title: 'Success',
                                        msg: 'New category inserted.',
                                        buttons: Ext
                                            .MessageBox.OK,
                                        fn: function(btn) {
                                            PlanYard_<?=$tab_id?>
                                                (text);
                                        }
                                    });
                                    // }else{
                                    // 	loadmask.hide();
                                    // 	Ext.MessageBox.show({
                                    // 		title: 'Error',
                                    // 		msg: 'Failed to save changes. Message : Size, Type, Status, POD, VESVOY, OPE E_I and O_I harus diisi' + text,
                                    // 		buttons: Ext.MessageBox.OK
                                    // 	});
                                    // }
                                }
                            });
                            win.close();
                        } else {
                            alert(error_message);
                        }
                    }
                }
            }, {
                text: 'Cancel',
                handler: function() {
                    win.close();
                }
            }]
        })]
    });
    win.show();
});
</script>