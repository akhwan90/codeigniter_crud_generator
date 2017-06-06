<?php echo $html; ?>

<script type="text/javascript">
	$("#tbl_buat").on("click", function() {
		var jml = $("#jml").val();
		window.open("<?php echo base_url('awal/buat_modul/'); ?>"+jml, '_self');
	});
</script>