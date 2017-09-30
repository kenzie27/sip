<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ibu_hamil extends CI_Controller {
	public $idIbu;
	public $namaBumil;			public $namaSuami;
	public $alamatBumil;		public $umur;
	public $usiaKandungan;		public $kandunganKe;
	public $tempatLahir;		public $tanggalLahir;
	public $hpht;				public $perkiraanLahir;
	public $tinggiIbu;			public $beratAwal;
	public function __construct() {
        parent::__construct();
        $this->load->model('ModelPosyandu');
    }
    public function hitungBBIdeal($id){
		$data = $this->ModelPosyandu->getData('ibuhamil','where idIbu = '.$id);
		$tinggiIbu = $data[0]['tinggiIbu'];
		$usiaKandungan = $data[0]['usiaKandungan'];
		$beratUpdate = $data[0]['beratUpdate'];
		if($tinggiIbu >= 160){
			$ntb = "110";
		}
		elseif($tinggiIbu < 160 && $tinggiIbu >= 150){
			$ntb = "105";
		}
		else{
			$ntb = "100";
		}
		$bbi = $tinggiIbu - $ntb;
		$beratIdeal = $bbi + ($usiaKandungan*(7/20));
		if($beratUpdate == $beratIdeal){
			$status = '<div class="alert alert-success">Ideal</div>';
		}elseif ($beratUpdate < $beratIdeal) {
			$status = '<div class="alert alert-warning">Terlalu Kurus</div>';
		}else{
			$status = '<div class="alert alert-danger">Terlalu Gemuk</div>';
		}
		return $status;
	}
	public function index(){
		$bumil = $this->ModelPosyandu->getData('ibuhamil');
		$this->load->view('header');	
		$this->load->view('sidebar');
		$this->load->view('tabel_ibuhamil',array('bumil'=>$bumil));
	}
	public function add(){
		$this->load->view('header');	
		$this->load->view('sidebar');
		$this->load->view('form_ibuhamil');
	}
	public function naegele(){

	}
	public function processAdd(){
		$idIbu = $_POST['idIbu'];
		$namaBumil = $_POST['namaBumil'];	$namaSuami = $_POST['namaSuami'];
		$tempatLahir = $_POST['tempatLahir'];	$tanggalLahir = $_POST['tanggalLahir'];
		$kandunganKe = $_POST['kandunganKe'];	$usiaKandungan = $_POST['usiaKandungan'];
		$alamatBumil = $_POST['alamatBumil'];
		$hpht = $_POST['hpht'];
		$tinggiIbu = $_POST['tinggiIbu'];	$beratAwal = $_POST['beratAwal'];
		$now =  date("Y-m-d");
		$datetime1 = new DateTime($tanggalLahir);
  		$datetime2 = new DateTime($now);
  		$difference = $datetime1->diff($datetime2)->days;
  		$umur = ceil($difference/365);


  		$bulan =  date('Y-m-d', strtotime('-3 month', strtotime($hpht)));
		$tanggal = date('Y-m-d', strtotime('+7 days', strtotime($bulan)));
		$perkiraanLahir = date('Y-m-d', strtotime('+1 year', strtotime($tanggal)));
		$tambah_data = array(
			'idIbu'=>$idIbu, 'namaBumil'=>$namaBumil, 'namaSuami'=>$namaSuami,
			'tempatLahir'=>$tempatLahir, 'tanggalLahir'=>$tanggalLahir,
			'kandunganKe'=>$kandunganKe, 'usiaKandungan'=>$usiaKandungan,
			'hpht'=>$hpht, 'umur'=>$umur, 'perkiraanLahir'=>$perkiraanLahir,
			'alamatBumil'=>$alamatBumil, 'beratUpdate'=>$beratAwal,
			'tinggiIbu'=>$tinggiIbu, 'beratAwal'=>$beratAwal
		);
		$res = $this->ModelPosyandu->addData('ibuhamil',$tambah_data);
		if($res >=1){
			echo "berhasil";
		}else{
			echo "<h2>Galat</h2>";
		}
	}
	public function doUpdate(){
		$idIbu = $_POST['idIbu'];
		$usiaKandungan = $_POST['usiaKandungan'];
		$tinggiIbu = $_POST['tinggiIbu']; 	$beratUpdate = $_POST['beratUpdate'];
		$update_data = array(
			'usiaKandungan' => $usiaKandungan,
			'tinggiIbu' =>  $tinggiIbu,
			'beratUpdate' => $beratUpdate
		);
		$where = array('idIbu' => $idIbu );
		$res = $this->ModelPosyandu->UpdateData('ibuhamil',$update_data,$where);
		if($res >=1){
			/*
			$this->session->set_flashdata('msg', 
            	'<div class="alert alert-info alert-dismissible" role="alert">
            		<i class="fa fa-info-circle"></i>
            			Data '.$idIbu.' telah berhasil diupdate 
                </div>');
            */ 
			redirect('Ibu_hamil/index');
		}else{
			/*
			$this->session->set_flashdata('msg', 
                '<div class="alert alert-danger alert-dismissible" role="alert">
                	<i class="fa fa-times-circle"></i>
	                    Data '.$idIbu.' gagal diupdate
                </div>');
            */ 
			redirect('Ibu_hamil/edit/'.$idIbu);
		}
	}
	public function detail($id){
		$data = $this->ModelPosyandu->getData('ibuhamil','where idIbu = '.$id);
		$status = $this->hitungBBIdeal($id);
		$dataIbu = array('idIbu' => $data[0]['idIbu'],
			'namaBumil'=>$data[0]['namaBumil'], 'namaSuami'=>$data[0]['namaSuami'],
			'alamatBumil'=>$data[0]['alamatBumil'],'umur'=>$data[0]['umur'],
			'usiaKandungan'=>$data[0]['usiaKandungan'],'kandunganKe'=>$data[0]['kandunganKe'],
			'tempatLahir'=>$data[0]['tempatLahir'], 'tanggalLahir'=>$data[0]['tanggalLahir'],
			'beratUpdate'=>$data[0]['beratUpdate'], 'beratAwal'=>$data[0]['beratAwal'],
			'tinggiIbu'=>$data[0]['tinggiIbu'],
			'hpht'=>$data[0]['hpht'], 'perkiraanLahir'=>$data[0]['perkiraanLahir'],
			'status'=>$status
		);
		$this->load->view('header');	
		$this->load->view('sidebar');
		$this->load->view('detail_ibuhamil',$dataIbu);
	}

	public function edit($id){
		$data = $this->ModelPosyandu->getData('ibuhamil','where idIbu = '.$id);
		$dataIbu = array('idIbu' => $data[0]['idIbu']);
		$this->load->view('header');	
		$this->load->view('sidebar');
		$this->load->view('edit_ibuhamil',$dataIbu);
	}

	public function delete($id){
		$where = array('idIbu' => $id );
		$res = $this->ModelPosyandu->HapusData('ibuhamil',$where);
		if($res >=1){
			redirect('Ibu_hamil');
		}else{
			echo "<h2>Galat</h2>";
		}
	}
}