<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Awal extends CI_Controller {

	public function index() {
		$fm = "./application/modules/";

		$html = 'Modul telah dibuat : <ul>';

		$dir = new DirectoryIterator($fm);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->isDir() && !$fileinfo->isDot()) {
				$html .= '<li style="margin-left: -20px"><a target="_blank" href="'.base_url($fileinfo->getFilename()).'">'.$fileinfo->getFilename().'</a></li>';
			}
		}

		$html .= '</ul>';
		

		$html .= '<p style="margin-top: 30px"><h3>Buat Modul</h3>
				<label>Jumlah Field</label>
				<input type="number" class="form-control" id="jml" style="width: 200px" value="2">
				<button class="btn btn-success" type="button" style="margin-top: 10px" id="tbl_buat">Buat</button>
				</p>';


		$d['p'] = 'list';		
		$d['html'] = $html;
		$this->load->view('template', $d);
	}

	public function buat_modul($jml_field=2) {
		$d['p_tipe'] = array("int"=>"integer","decimal"=>"decimal","text"=>"text","select"=>"select","file"=>"file","date"=>"date","textarea"=>"textarea");
		$d['p'] = 'form';

		$html = '<form action="'.base_url('awal/buat_modul_simpan').'" method="post">
			<table class="table table-bordered">';

		$html .= '<thead><tr><td colspan="6"><label>Nama Modul</label>'.form_input('nama_modul','','class="form-control" style="width: 300px" required').'<br><label><input type="checkbox" value="Y" checked name="buat_id"> Buat field ID (AUTO_INCREMENT, PRIMARY KEY) </label></td></tr></thead>';

		$html .= '<thead>
				<tr>
					<th width="10%">Tipe</th>
					<th width="20%">Nama Field</th>
					<th width="20%">Nama Label</th>
					<th width="30%">Keterangan Tambahan</th>
					<th width="20%">Required</th>
				</tr>
			</thead><tbody>';


		for ($i = 0; $i < $jml_field; $i++) {
			$html .= '<tr><td>'.form_dropdown('tipe[]',$d['p_tipe'],'','class="form-control" onchange="return de_pilselek('.$i.');" id="tipe_'.$i.'" required').'</td>
						<td>'.form_input('nama_f[]','','class="form-control" required id="nama_f_'.$i.'" onchange="return ke_label('.$i.');"').'</td>
						<td>'.form_input('nama_l[]','','class="form-control" required id="nama_l_'.$i.'"').'</td>
						<td>'.form_input('pil_selek[]','','class="form-control" readonly id="pil_selek_'.$i.'"').'<span id="petunjuk" style="color: red; font-weight: bold; font-size: 10px"></span></td>
						<td>'.form_checkbox('is_required[]',$i,'class="form-control"').'</td>
					  </tr>';
		}

		$html .= '<tr><td colspan="6">
					<button type="submit" class="btn btn-success">Simpan</button>
					<a href="'.base_url('awal').'" class="btn btn-info">Kembali</a>
				  </td></tr>';
		$html .= '</tbody></table>';

		$d['html'] = $html;
		$this->load->view('template', $d);
	}


	public function buat_modul_simpan() {
		$p = $this->input->post();
		
		$file_template = "./application/controllers/";

		$fm = "./application/modules/";
		$nm = $p['nama_modul'];
		$gb = $fm.$nm;

		//cek adakah folder di folder module, jika ada, hapus
		$folder_eksis = file_exists($fm.$p['nama_modul']);

		if ($folder_eksis) {
			echo "Folder sudah ada. Perintah gagal. Hapus/rename folder yg sudah ada..!";
		} else {

			//tampung variabel
			$nm_file = ucfirst($nm); //nama file
			$jml_colspan = sizeof($p['nama_f']); //jml colspan nanti di tabel view
			$jml_colspan2 = $jml_colspan+1; //jml colspan nanti di tabel view
			$kolom_header = array(); //tampung dulu untuk kolom header
			$data = array(); //sama, untuk datanya
			$data_value_form = array();
			$data_post = array();
			$data_create_form = array();
			$data_value_form_is_upload = "";

			$enctype = in_array("file", $p['tipe']) == TRUE ? 'enctype="multipart/form-data"' : '';

			//create tabel
			$sql_create_tabel = "CREATE TABLE IF NOT EXISTS `".$nm."` (";

			$sql_create_tabel .= (isset($p['buat_id']) && $p['buat_id'] == "Y") ? "\n"."`id` INT(8) NOT NULL AUTO_INCREMENT,"."\n" : "\n";

			//create form
			$create_form = '<form class="form" method="post" action="<?php echo base_url(\''.$nm.'/simpan\'); ?>" '.$enctype.'>'."\n".'<input type="hidden" name="id" value="<?php echo $data[\'id\']; ?>">'."\n".'<input type="hidden" name="mode" value="<?php echo $data[\'mode\']; ?>">'."\n";

			$sql_create_tabel_s = array();
			
			for ($x = 0; $x < $jml_colspan; $x++) {
				$kolom_header[] = '<th>'.$p['nama_l'][$x].'</th>'; //generate kolom header 
				$data[] = '<td>\'.$d[\''.$p['nama_f'][$x].'\'].\'</td>'; //generate kolom data
				$data_value_form[] = '$data[\''.$p['nama_f'][$x].'\'] = "";'."\n\t\t\t";
				
				$tipe_field = $p['tipe'][$x];

				$_x = strval($x);
				$is_required = in_array($_x, $p['is_required']) ? "required" : "";

				if ($tipe_field == "text") {
					$sql_create_tabel_s[] = "`".$p['nama_f'][$x]."` VARCHAR(200) NOT NULL";
					$data_create_form[] = "\t".'<div class="form-group">'."\n\t\t".'<label>'.$p['nama_l'][$x].'</label>'."\n\t\t".'<input type="text" name="'.$p['nama_f'][$x].'" class="form-control" '.$is_required.' value="<?php echo $data[\''.$p['nama_f'][$x].'\']; ?>">'."\n\t".'</div>'."\n";
					$data_post[] = '\''.$p['nama_f'][$x].'\'=>$p[\''.$p['nama_f'][$x].'\'],';

				} else if ($tipe_field == "file") {
					$sql_create_tabel_s[] = "`".$p['nama_f'][$x]."` VARCHAR(200) NOT NULL";

					$data_create_form[] = "\t".'<div class="form-group">'."\n\t\t".'<label>'.$p['nama_l'][$x].'</label>'."\n\t\t".'<input type="file" name="'.$p['nama_f'][$x].'" class="form-control" '.$is_required.'>'."\n\t".'</div>'."\n";

					if (!file_exists('./upload')) {
						mkdir('./upload/');
						mkdir('./upload/'.$nm);
					} else {
						mkdir('./upload/'.$nm);	
					}

					$pil_selek = $p['pil_selek'][$x];
					$pc1_pil_selek = explode(",",$pil_selek);
					
					$data_value_form_is_upload .= "\n\t\t".'$config[\'upload_path\']'."\t\t".'= \'./upload/'.$nm.'\';'."\n\t\t".'$config[\'allowed_types\']'."\t".'= \''.$pc1_pil_selek[0].'\';'."\n\t\t".'$config[\'max_size\']'."\t\t\t".'= \''.$pc1_pil_selek[1].'\';'."\n\t\t".'$this->load->library(\'upload\', $config);'."\n";

					$data_value_form_is_upload .= "\n\t\t".'if ($this->upload->do_upload(\''.$p['nama_f'][$x].'\')) {'."\n\t\t\t".'$up_data = $this->upload->data();'."\n\t\t\t".'$p_data[\''.$p['nama_f'][$x].'\'] = $up_data[\'file_name\'];'."\n\t\t".'}'."\n";

				} else if ($tipe_field == "select") {
					//$pil_selek = !empty($p['pil_selek'][$x]) ? $p['pil_selek'][$x] : ',';

					$pc1_pil_selek = explode(",",$p['pil_selek'][$x]);

					//echo $cek = empty($p['pil_selek'][$x]) ? "$x tidak ada" : "ada";

					$arr_pilihan = array();
					$arr_pilihan_label = array();

					$data_create_form[] = "\t".'<div class="form-group">'."\n\t\t".'<label>'.$p['nama_l'][$x].'</label>'."\n\t\t".'<select name="'.$p['nama_f'][$x].'" class="form-control" '.$is_required.'>';

					foreach($pc1_pil_selek as $r) {
						$pc2_pil_selek = explode("=",$r);
						$arr_pilihan[] = "'".$pc2_pil_selek[0]."'";
						
						$arr_pilihan_label[] = "'".$pc2_pil_selek[0]."'=>'".$pc2_pil_selek[1]."'";
					}

					$data_post[] = '\''.$p['nama_f'][$x].'\'=>$p[\''.$p['nama_f'][$x].'\'],';


					$data_create_form[] .= "\t\t".'<?php'."\n\t\t\t".'$p_'.$p['nama_f'][$x].' = array('.implode(",",$arr_pilihan_label).');'."\n\t\t\t".'foreach ($p_'.$p['nama_f'][$x].' as $x => $y ) {'."\n\t\t\t\t".'$selected = $data[\''.$p['nama_f'][$x].'\'] == $x ? "selected" : ""; '."\n\t\t\t\t".'echo \'<option value="\'.$x.\'" \'.$selected.\'>\'.$y.\'</option>\';'."\n\t\t\t".'}'."\n\t\t".'?>'."\n\t\t".'</select>'."\n\t".'</div>'."\n";

					$sql_create_tabel_s[] = "`".$p['nama_f'][$x]."` ENUM(".implode(",", $arr_pilihan).") NOT NULL";
				} else if ($tipe_field == "date") {	
					$sql_create_tabel_s[] = "`".$p['nama_f'][$x]."` DATE NOT NULL";

					$data_create_form[] .= "\t".'<div class="form-group">'."\n\t\t".'<label>'.$p['nama_l'][$x].'</label>'."\n\t\t".'<input type="date" name="'.$p['nama_f'][$x].'" class="form-control" '.$is_required.' value="<?php echo $data[\''.$p['nama_f'][$x].'\']; ?>"></div>';

					$data_post[] = '\''.$p['nama_f'][$x].'\'=>$p[\''.$p['nama_f'][$x].'\'],';

				} else if ($tipe_field == "textarea") {					
					$sql_create_tabel_s[] = "`".$p['nama_f'][$x]."` LONGTEXT NOT NULL";

					$data_create_form[] .= "\t".'<div class="form-group">'."\n\t\t".'<label>'.$p['nama_l'][$x].'</label>'."\n\t\t".'<textarea name="'.$p['nama_f'][$x].'" class="form-control" '.$is_required.'><?php echo $data[\''.$p['nama_f'][$x].'\']; ?></textarea></div>';

					$data_post[] = '\''.$p['nama_f'][$x].'\'=>$p[\''.$p['nama_f'][$x].'\'],';

				} else if ($tipe_field == "int") {	
					$sql_create_tabel_s[] = "`".$p['nama_f'][$x]."` INT(10) NOT NULL";

					$data_create_form[] .= "\t".'<div class="form-group">'."\n\t\t".'<label>'.$p['nama_l'][$x].'</label>'."\n\t\t".'<input type="number" name="'.$p['nama_f'][$x].'" class="form-control" '.$is_required.' value="<?php echo $data[\''.$p['nama_f'][$x].'\']; ?>"></div>';

					$data_post[] = '\''.$p['nama_f'][$x].'\'=>$p[\''.$p['nama_f'][$x].'\'],';
				} else if ($tipe_field == "decimal") {	
					$sql_create_tabel_s[] = "`".$p['nama_f'][$x]."` DECIMAL(10,2) NOT NULL";

					$data_create_form[] .= "\t".'<div class="form-group">'."\n\t\t".'<label>'.$p['nama_l'][$x].'</label>'."\n\t\t".'<input type="number" name="'.$p['nama_f'][$x].'" class="form-control" '.$is_required.' value="<?php echo $data[\''.$p['nama_f'][$x].'\']; ?>"></div>';

					$data_post[] = '\''.$p['nama_f'][$x].'\'=>$p[\''.$p['nama_f'][$x].'\'],';
				} 

			}

			$create_form .= implode("\n",$data_create_form)."\n\t".'<div class="form-group"><button type="submit" class="btn btn-success">Simpan</button> <a href="<?php echo base_url(\''.$nm.'\'); ?>" class="btn btn-info">Kembali</a></div>'."\n".'</form>';


			$kolom_header[] = '<th>Aksi</th>'; //tambahi kolom aksi
			$data[] = '<td>
						<a href="\'.base_url(\''.$nm.'/edit/\'.$d[\'id\']).\'">Edit</a> 
						<a href="\'.base_url(\''.$nm.'/hapus/\'.$d[\'id\']).\'" onclick="return confirm(\\\'Anda yakin..?\\\');">Hapus</a>
					   </td>';  //tambahi kolom klik aksi
			$data_value_form[] = '$data[\'mode\'] = "add";'."\n\t\t\t".'$data[\'id\'] = 0;'."\n\t\t\t";
			
			$sql_create_tabel .= implode(","."\n", $sql_create_tabel_s);
			$sql_create_tabel .= (isset($p['buat_id']) && $p['buat_id'] == "Y") ? ", PRIMARY KEY (`id`)"."\n".") ENGINE = InnoDB;" : ");";

			mkdir($gb); //buat folder module
			mkdir($gb."/controllers"); //buat folder controller
			mkdir($gb."/views"); //buat folder views
			mkdir($gb."/models"); //buat folder models


			$_kolom_header = implode("", $kolom_header); //gabung
			$_data = implode("", $data); //gabung
			$_data_value_form = implode("", $data_value_form); //gabung
			$_data_post = implode("", $data_post); //gabung

			$buka_file_template_controller = read_file($file_template."/template_controller.txt"); //buka file template
			$buka_file_template_v_view = read_file($file_template."/template_view.txt"); //buka file template
			
			$new_content_controllers = str_replace(array("<<nama_modul>>","<<nama_tabel>>","<<kolom_header>>","<<data>>","<<jml_colspan>>","<<data_value_form>>","<<data_post>>","<<data_value_form_is_upload>>"), array($nm_file,$nm,$_kolom_header,$_data,$jml_colspan,$_data_value_form,$_data_post, $data_value_form_is_upload), $buka_file_template_controller);
			//replace template


			//BUAT TABEL
			$this->db->query($sql_create_tabel);

			write_file($gb."/controllers/".$nm_file.".php", $new_content_controllers);
			//buat controllers
			write_file($gb."/views/list.php", $buka_file_template_v_view);
			//buat view list
			write_file($gb."/views/form.php", $create_form);
			//buat view form

			redirect('awal');
		}

	}
	
}