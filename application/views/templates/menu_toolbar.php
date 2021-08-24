
<script type="text/javascript">
	Ext.require(['*']);
	
	Ext.define('MyApp.menu.Menu', {
		override: 'Ext.menu.Menu',
		onMouseLeave: function(ev) {
			var activeItem = this.activeItem,
			menu = activeItem && activeItem.menu,
			menuEl = menu && menu.getEl();
			if (Ext.isChrome && menuEl && menuEl.contains(ev.getRelatedTarget())) {
			    return;
			}
			this.callParent([ev]);
		}
	});
	
	Ext.onReady(function() {
		var tb = Ext.create('Ext.toolbar.Toolbar', {
			style : {
				background : 'none'
			}
		});
		
		tb.add(
		<?php
			foreach($main_menu as $main){
				echo "{";
				echo "text: '".$main['MENU_NAME']."',";
				echo "icon: '".IMG_.$main['ICON']."',";
				echo "menu: ";
		?>
		Ext.create('Ext.menu.Menu', {
			items: [
			<?php
			//debux($child_menu);
				foreach($child_menu[$main['ID_MENU']] as $child){

					#hide child yard monitoring
					/*if($child['MENU_NAME']=='Yard Monitoring'){
						continue;
					}*/

					if ($child['MENU_NAME']=="-"){
					echo "'-'";
					}else{
					echo "{";
					echo "text: '".$child['MENU_NAME']."',";

					if (sizeof($child_menu[$child['ID_MENU']])>0){
						echo "menu:{";
						echo "items: [";
						foreach($child_menu[$child['ID_MENU']] as $child2){
							echo "{";
							echo "text: '".$child2['MENU_NAME']."',";echo "handler: function (){";
			?>
							addTab('<?=$child2['TABPANEL']?>', <?=$child2['CONTROLLER']?> <?php if($child2['DATA_ID']!='') echo ', '.$child2['DATA_ID']; ?> <?php if($child2['TITLE']!='') echo ', '.$child2['TITLE']; ?>);
			<?php
							echo "}";
							echo "},";
						}
			?>
			<?php
						echo "]";
						echo "}";
					}else{
						echo "handler: function (){";
			?>
							addTab('<?=$child['TABPANEL']?>', <?=$child['CONTROLLER']?> <?php if($child['DATA_ID']!='') echo ', '.$child['DATA_ID']; ?> <?php if($child['TITLE']!='') echo ', '.$child['TITLE']; ?>);
			<?php
						echo "}";
					}
					echo "}";
					}
					echo ",";
				}
			?>
			]
		})
		<?php
				echo "},";
				echo "'-',";
			}
		?>
		{
			text:'Logout - <?=$full_name?>',
			icon: '<?=IMG_?>icons/logout.png',
			handler: function (){
				loadmask.show();
				var url = "<?=controller_?>main/logout";
				$.post( url, function(data) {
					location.reload();
				});
			}
		});
		
		tb.render('toolbar');
	});
</script>

<style>
.sendiriStyle1{
	height:45px;
	background-color: #f7f6f6;
	box-shadow: 
			0 1px 2px #fff, /*bottom external highlight*/
			0 -1px 1px #9c9da2, /*top external shadow*/ 
			inset 0 -1px 1px rgba(0,0,0,0.1), /*bottom internal shadow*/ 
			inset 0 1px 1px rgba(255,255,255,0.8); /*top internal highlight*/
}
.sendiriStyle2{
	height:40px;
	background-color: #f0eeee ;
	box-shadow: 
		  0 1px 2px #fff, /*bottom external highlight*/
		  0 -1px 1px #9c9da2, /*top external shadow*/ 
		  inset 0 -1px 1px rgba(0,0,0,0.1), /*bottom internal shadow*/ 
		  inset 0 1px 1px rgba(255,255,255,0.8); /*top internal highlight*/
}
.sendiriStyle4{
	height:80px;
	background-color: #f7f6f6;
	box-shadow: 
		  0 1px 2px #fff, /*bottom external highlight*/
		  0 -1px 1px #9c9da2, /*top external shadow*/ 
		  inset 0 -1px 1px rgba(0,0,0,0.1), /*bottom internal shadow*/ 
		  inset 0 1px 1px rgba(255,255,255,0.8); /*top internal highlight*/
}
.inquirySenter{
	width:26px;
	padding:2px;
	background-color:#f7f6f6;
	display:block;
	text-decoration:none;
	margin:0 auto;
	text-align:center;
	border:solid 1px #f7f6f6;
	
	-webkit-transition: all 1s;
	-moz-transition: all 1s;
	transition: all 1s;
}
.inquirySenter:active{
	border:solid 1px #abadb3;
	border-radius:2px;
   background:linear-gradient(to top, #fcfbfb , #e3e3e3);
}
.inquirySenter:hover{
	border:solid 1px #abadb3;
	background-color:#e3e3e3;
}

.btn-add{
    background-image: url('<?=IMG_?>icons/add.png') !important;
}

.btn-edit{
    background-image: url('<?=IMG_?>icons/edit.png') !important;
}

.btn-refresh{
    background-image: url('<?=IMG_?>icons/refresh.png') !important;
}

.btn-delete{
    background-image: url('<?=IMG_?>icons/delete.png') !important;
}

.btn-select-all{
    background-image: url('<?=IMG_?>icons/select_all.png') !important;
}

.btn-search{
    background-image: url('<?=IMG_?>icons/search.png') !important;
}

.btn-add,.btn-edit,.btn-refresh,.btn-delete,.btn-select-all,.btn-search{
    background-size: 15px;
    background-repeat: no-repeat;
    background-position: center;
    padding: 3px 5px;
}
</style>
<div id="toolbar" class="sendiriStyle1">
	<div style="position:absolute;right:0px;" >
	    <!--<label><?php echo $this->gtools->terminal_name()?></label>-->
	    &nbsp;<img src="<?=IMG_?>icons/itos-logo.png" width="120px"/>
	</div>
</div>