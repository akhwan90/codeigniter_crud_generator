<?php 
echo $html; 
?>


<script type="text/javascript">
	$(document).on("ready", function() {

	});

	function de_pilselek(id) {
		var va = $("#tipe_"+id).val();
		if (va == "select" || va == "file") {
			$("#pil_selek_"+id).attr("readonly", false);
			if (va == "select") {
				$("#petunjuk").html("Isikan pilihan opsi jawaban, dengan format \"value=Label\", dipisahkan dengan koma. Contoh: 01=Januari,02=Februari,03=Maret");
			} else if (va == "file") {
				$("#petunjuk").html("Isikan pilihan opsi file upload, dengan format \"tipe_file1|tipe_file2|tipe_file3|dst...\", dan maksimal ukuran file. Contoh: gif|jpg|png,2000");	
			}
		} else {
			$("#pil_selek_"+id).attr("readonly", true);
			$("#petunjuk").html("");
		}
	}

	function ke_label(id) {
		var va = $("#nama_f_"+id).val();
		
		$("#nama_l_"+id).val(ucfirst(va));
	}

	function ucfirst(string) {
		return string.charAt(0).toUpperCase() + string.slice(1);
	}
</script>	
