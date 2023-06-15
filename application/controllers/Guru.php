<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Guru extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		} else if (!$this->ion_auth->is_admin()) {
			show_error('Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href="' . base_url('dashboard') . '">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
		}
		$this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->form_validation->set_error_delimiters('', '');
	}

	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	public function index()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Guru',
			'subjudul' => 'Data Guru'
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/guru/data');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function data()
	{
		$this->output_json($this->master->getDataGuru(), false);
	}

	public function add()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Tambah Guru',
			'subjudul' => 'Tambah Data Guru',
			'mapel'	=> $this->master->getAllMapel()
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/guru/add');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function edit($id)
	{
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Edit Guru',
			'subjudul'	=> 'Edit Data Guru',
			'mapel'		=> $this->master->getAllMapel(),
			'data' 		=> $this->master->getGuruById($id)
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/guru/edit');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function save()
	{
		$method 	= $this->input->post('method', true);
		$id_guru 	= $this->input->post('id_guru', true);
		$nip 		= $this->input->post('nip', true);
		$nama_guru = $this->input->post('nama_guru', true);
		$email 		= $this->input->post('email', true);
		$mapel 	= $this->input->post('mapel', true);
		if ($method == 'add') {
			$u_nip = '|is_unique[guru.nip]';
			$u_email = '|is_unique[guru.email]';
		} else {
			$dbdata 	= $this->master->getGuruById($id_guru);
			$u_nip		= $dbdata->nip === $nip ? "" : "|is_unique[guru.nip]";
			$u_email	= $dbdata->email === $email ? "" : "|is_unique[guru.email]";
		}
		$this->form_validation->set_rules('nip', 'NIP', 'required|numeric|trim|min_length[8]|max_length[12]' . $u_nip);
		$this->form_validation->set_rules('nama_guru', 'Nama Guru', 'required|trim|min_length[3]|max_length[50]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email' . $u_email);
		$this->form_validation->set_rules('mapel', 'Mata Pelajaran', 'required');

		if ($this->form_validation->run() == FALSE) {
			$data = [
				'status'	=> false,
				'errors'	=> [
					'nip' => form_error('nip'),
					'nama_guru' => form_error('nama_guru'),
					'email' => form_error('email'),
					'mapel' => form_error('mapel'),
				]
			];
			$this->output_json($data);
		} else {
			$input = [
				'nip'			=> $nip,
				'nama_guru' 	=> $nama_guru,
				'email' 		=> $email,
				'mapel_id' 	=> $mapel
			];
			if ($method === 'add') {
				$action = $this->master->create('guru', $input);
			} else if ($method === 'edit') {
				$action = $this->master->update('guru', $input, 'id_guru', $id_guru);
			}

			if ($action) {
				$this->output_json(['status' => true]);
			} else {
				$this->output_json(['status' => false]);
			}
		}
	}

	public function delete()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->master->delete('guru', $chk, 'id_guru')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}

	public function create_user()
	{
		$id = $this->input->get('id', true);
		$data = $this->master->getGuruById($id);
		$nama = explode(' ', $data->nama_guru);
		$first_name = $nama[0];
		$last_name = end($nama);

		$username = $data->nip;
		$password = $data->nip;
		$email = $data->email;
		$additional_data = [
			'image'  => 'default.png',
			'first_name'	=> $first_name,
			'last_name'		=> $last_name
		];
		$group = array('2'); // Sets user to guru.

		if ($this->ion_auth->username_check($username)) {
			$data = [
				'status' => false,
				'msg'	 => 'Username tidak tersedia (sudah digunakan).'
			];
		} else if ($this->ion_auth->email_check($email)) {
			$data = [
				'status' => false,
				'msg'	 => 'Email tidak tersedia (sudah digunakan).'
			];
		} else {
			$this->ion_auth->register($username, $password, $email, $additional_data, $group);
			$data = [
				'status'	=> true,
				'msg'	 => 'User berhasil dibuat. NIP digunakan sebagai default username dan password pada saat login.'
			];
		}
		$this->output_json($data);
	}

	public function import($import_data = null, $errFile = false)
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Guru',
			'subjudul' => 'Import Data Guru',
			'mapel' => $this->master->getAllMapel(),
			'errFile' => $errFile,
			'import' => $import_data
		];

		$this->load->view('_templates/dashboard/_header', $data);
		$this->load->view('master/guru/import');
		$this->load->view('_templates/dashboard/_footer');
	}
	public function preview()
	{
		$config['upload_path']		= './uploads/import/';
		$config['allowed_types']	= 'xls|xlsx|csv';
		$config['max_size']			= 2048;
		$config['encrypt_name']		= true;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('upload_file')) {
			$this->import(null, "500");
		} else {
			$file = $this->upload->data('full_path');
			$ext = $this->upload->data('file_ext');

			switch ($ext) {
				case '.xlsx':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
					break;
				case '.xls':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
					break;
				case '.csv':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
					break;
				default:
					$this->import(null, "500");
			}

			$spreadsheet = $reader->load($file);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			$data = [];
			for ($i = 1; $i < count($sheetData); $i++) {
				$data[] = [
					'nip' => $sheetData[$i][0],
					'nama_guru' => $sheetData[$i][1],
					'email' => $sheetData[$i][2],
					'mapel_id' => $sheetData[$i][3]
				];
			}

			unlink($file);

			$this->import($data, "200");
			return;
		}
	}

	public function do_import()
	{
		$input = json_decode($this->input->post('data', true));
		$data = [];
		foreach ($input as $d) {
			$data[] = [
				'nip' => $d->nip,
				'nama_guru' => $d->nama_guru,
				'email' => $d->email,
				'mapel_id' => $d->mapel_id
			];
		}

		$this->master->create('guru', $data, true);
		echo 200;
		return;
	}
}
