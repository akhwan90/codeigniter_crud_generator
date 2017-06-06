<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coba extends CI_Controller {
	function __construct() {
        parent::__construct();
        $this->d['tabel'] = "coba";
        
    }


	public function index() {
		$data = $this->db->query("SELECT * FROM ".$this->d['tabel']."")->result_array();

		$html = '<a href="'.base_url('coba/edit/0').'" class="btn btn-success">Tambah Data</a><br><br>'.$this->session->flashdata('konfirmasi').'
		<table class="table table-bordered"><thead><tr><th>No</th><th>Aksi</th></tr></thead><tbody>';

		if (!empty($data)) {
			foreach ($data as $d) {
				$html .= '<tr><td>'.$d['no'].'</td><td>
						<a href="'.base_url('coba/edit/'.$d['id']).'">Edit</a> 
						<a href="'.base_url('coba/hapus/'.$d['id']).'" onclick="return confirm(\'Anda yakin..?\');">Hapus</a>
					   </td></tr>';
			}
		} else {
			$html .= '<tr><td colspan="'.(1+1).'">Belum ada data</td></tr>';
		}

		$html .= '</tbody></table>';

		$d['p'] = "list";
		$d['html'] = $html;

		$this->load->view('template', $d);
	}

	public function edit($id) {
		if (empty($id)) {
			$data['no'] = "";
			$data['mode'] = "add";
			$data['id'] = 0;
			
		} else {
			$q_data = $this->db->query("SELECT a.*, 'edit' mode FROM ".$this->d['tabel']." a WHERE a.id = '".$id."'")->row_array();

			$data = $q_data;
		}

		$d['data'] = $data;
		$d['p'] = "form";
		$this->load->view('template', $d);

	}

	public function simpan() {
		$p = $this->input->post();

		$p_data = array('no'=>$p['no'],);

		

		if ($p['mode'] == "edit") {
			$this->db->where("id", $p['id']);
			$this->db->update($this->d['tabel'], $p_data);
		} else if ($p['mode'] == "add") {
			$this->db->insert($this->d['tabel'], $p_data);
		}
		
		redirect(strtolower('Coba'));		
	}


	public function hapus($id) {
		if (!empty($id)) {
			$this->db->query("DELETE FROM ".$this->d['tabel']." WHERE id = $id");
		}
		redirect(strtolower('Coba'));
	}


}
