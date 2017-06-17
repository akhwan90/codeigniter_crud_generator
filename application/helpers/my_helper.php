<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function j($data) {
	header('Content-Type: application/json');
	echo json_encode($data);
}

function tjs ($tgl, $tipe) {
	$pc_satu	= explode(" ", $tgl);
	if (count($pc_satu) < 2) {	
		$tgl1		= $pc_satu[0];
		$jam1		= "";
	} else {
		$jam1		= $pc_satu[1];
		$tgl1		= $pc_satu[0];
	}
	
	$pc_dua		= explode("-", $tgl1);
	$tgl		= $pc_dua[2];
	$bln		= $pc_dua[1];
	$thn		= $pc_dua[0];
	
	$bln_pendek		= array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des");
	$bln_panjang	= array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	
	$bln_angka		= intval($bln) - 1;
	
	if ($tipe == "l") {
		$bln_txt = $bln_panjang[$bln_angka];
	} else if ($tipe == "s") {
		$bln_txt = $bln_pendek[$bln_angka];
	}
	
	return $tgl." ".$bln_txt." ".$thn."  ".$jam1;
}

function _page($total_row, $per_page, $uri_segment, $url) {
	$CI 	=& get_instance();
	$CI->load->library('pagination');
	$config['base_url'] 	= $url;
	$config['total_rows'] 	= $total_row;
	$config['uri_segment'] 	= $uri_segment;
	$config['per_page'] 	= $per_page; 
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close']= '</li>';
	$config['prev_link'] 	= '&lt;';
	$config['prev_tag_open']='<li>';
	$config['prev_tag_close']='</li>';
	$config['next_link'] 	= '&gt;';
	$config['next_tag_open']='<li>';
	$config['next_tag_close']='</li>';
	$config['cur_tag_open']='<li class="active disabled"><a href="#"  style="background: #e3e3e3">';
	$config['cur_tag_close']='</a></li>';
	$config['first_tag_open']='<li>';
	$config['first_tag_close']='</li>';
	$config['last_tag_open']='<li>';
	$config['last_tag_close']='</li>';
	
	$CI->pagination->initialize($config); 
	return $CI->pagination->create_links();
}
