<form class="form" method="post" action="<?php echo base_url('coba/simpan'); ?>" >
<input type="hidden" name="id" value="<?php echo $data['id']; ?>">
<input type="hidden" name="mode" value="<?php echo $data['mode']; ?>">
	<div class="form-group">
		<label>No</label>
		<input type="number" name="no" class="form-control" required value="<?php echo $data['no']; ?>"></div>
	<div class="form-group"><button type="submit" class="btn btn-success">Simpan</button> <a href="<?php echo base_url('coba'); ?>" class="btn btn-info">Kembali</a></div>
</form>