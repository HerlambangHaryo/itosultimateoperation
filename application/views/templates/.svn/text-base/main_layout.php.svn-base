<script type="text/javascript">
	Ext.require([
		'*',
		'Ext.ux.grid.FiltersFeature'
	]);
	
	Ext.onReady(function() {
        Ext.Ajax.timeout = 120000;
    
		Ext.state.Manager.setProvider(Ext.create('Ext.state.CookieProvider'));
		
		loadmask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading..."});
		
		// MAIN LAYOUT
		// north
		var north = Ext.create('Ext.Component', {
			region: 'north',
			height: 45,
			loader: {
				url: "<?=controller_?>main/menu_toolbar",
				contentType: 'html',
				autoLoad: true,
				scripts: true
			}
		});
		
		// west
		var west = Ext.create('Ext.tab.Panel', {
			id: 'west_panel',
			region: 'west',
			split: true,
			width: 500,
			collapsible: true,
			animCollapse: true,
			header: false,
			collapseMode: 'mini',
			activeTab: 0,
			layout:'fit',
			items: [{
				id: 'vessel_schedule',
				closable: false,
				title: 'Vessel Schedule',
				autoScroll: true,
				loader: {
					url: "<?=controller_?>main/vessel_schedule_grid",
					contentType: 'html',
					autoLoad: true,
					scripts: true
				}
			}]
		});
		
		// east
		var east = Ext.create('Ext.tab.Panel', {
			id: 'east_panel',
			region: 'east',
			split: true,
			width: 200,
			autoScroll: true,
			collapsible: true,
			header: false,
			collapseMode: 'mini'
		});
		
		// center
		var center = Ext.create('Ext.tab.Panel', {
			id: 'center_panel',
			region: 'center',
			split: true,
			width: 1000,
			autoScroll: true
		});
		
		// viewport
		var viewport = Ext.create('Ext.Viewport', {
			layout: 'border',
			items:[
				north,
				west,
				east,
				center
			]
		});
		// END MAIN LAYOUT
		
		// HANDLER ADD TAB
		east.on({
			add: addToEast,
			remove: removeFromEast
		});
		
		center.on({
			add: addToCenter,
			remove: removeFromCenter
		});
		
		east.collapse();
	});
	
	var loadmask;
	var id_ves_voyage = '';
	
	/* SECTION TAB UTILITY*/
	var center_tabs = new Ext.menu.Menu();
	var center_index = 0;
	var east_tabs = new Ext.menu.Menu()
	var east_index = 0;
	var west_tabs = new Ext.menu.Menu()
	var west_index = 1;
	
	function addTab(tabpanel, controller, data_id, title) {
		if(typeof(data_id)==='undefined') data_id = '';
		if(typeof(title)==='undefined') title = '';
		
		var index = -1;
		if (tabpanel=='east_panel'){
			east_index++;
			index = east_index;
		}else if (tabpanel=='center_panel'){
			center_index++;
			index = center_index;
		}else if (tabpanel=='west_panel'){
			west_index++;
			index = west_index;
		}
		console.log('controller1 :' + controller);
		var arr_controller = controller.split('?');   
		console.log(arr_controller);
		var controller = arr_controller[0];
		console.log('controller2 :' + controller);
		var ct = Ext.getCmp(tabpanel);
		var class_name = controller.split(' ').join('_').toLowerCase();
		var id = class_name.split('/').join('_').toLowerCase()+"_"+index;
		var judul = ''; 
		if (title!=''){
			judul = title;
		}else{
			judul = controller;
		}
		if (data_id!='') judul += ' - '+data_id;
		
		loadmask.show();
		ct.add({
			id: id,
			closable: true,
			title: judul,
			autoScroll: true,
			loader: {
				url: "<?=controller_?>"+class_name+"?tab_id="+id + (arr_controller.length > 1 ? '&' + arr_controller[1] : ''),
				contentType: 'html',
				autoLoad: true,
				scripts: true,
				params: {
					data_id: data_id
				},
				success: function(el, response, options){
					loadmask.hide();
					if(response.responseText==''){
						el.getTarget().close();
						Ext.Msg.alert('Warning', 'Vessel Schedule must be selected!');
					}
				}
			}
		}).show();
		
		if (Ext.getCmp(tabpanel).getActiveTab()==null){
			Ext.getCmp(tabpanel).setActiveTab(0);
		}
		return id;
	}
	
	function addToCenter(ct, tab) {
		center_tabs.add({
			text: tab.title,
			id: tab.id + '_menu',
			handler: doScroll
		});
	}
	
	function removeFromCenter(ct, tab) {
		var id = tab.id + '_menu';
		center_tabs.remove(id);
	}
	
	function addToEast(ct, tab) {
		east_tabs.add({
			text: tab.title,
			id: tab.id + '_menu',
			handler: doScroll
		});
	}
	
	function removeFromEast(ct, tab) {
		var id = tab.id + '_menu';
		east_tabs.remove(id);
	}
	
	function addToWest(ct, tab) {
		west_tabs.add({
			text: tab.title,
			id: tab.id + '_menu',
			handler: doScroll
		});
	}
	
	function removeFromWest(ct, tab) {
		var id = tab.id + '_menu';
		west_tabs.remove(id);
	}
	
	function doScroll(ct, item) {
		var id = item.id.replace('_menu', '');
		var tab = ct.getComponent(id).tab;
		
		ct.getTabBar().layout.overflowHandler.scrollToItem(tab);
	}
</script>

<body>
</body>