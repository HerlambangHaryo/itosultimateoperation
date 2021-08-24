<script type="text/javascript">
	$(function(){
		var tpl = $.pivotUtilities.aggregatorTemplates;
		$.getJSON("<?=controller_?>outbound_yard_summary/data_outbound_yard_summary/<?=$id_ves_voyage?>", function(mps) {
			$("#outbound_yard_summary_<?=$tab_id?>").pivotUI(mps, {
				aggregators: {
					"Count" : function() { return tpl.count()() }
				},
				rows: ["ID_POD", "ID_YARD", "BLOCK_"],
				cols: ["CONT_SIZE", "CONT_STATUS", "IS_PLAN"]
			});
		});
	 });
</script>
<div id="outbound_yard_summary_<?=$tab_id?>"></div>