<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Soal extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth');
        } else if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('guru')) {
            show_error('Hanya Administrator dan guru yang diberi hak untuk mengakses halaman ini, <a href="' . base_url('dashboard') . '">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
        }
        $this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
        $this->load->helper('my'); // Load Library Ignited-Datatables
        $this->load->model('Master_model', 'master');
        $this->load->model('Soal_model', 'soal');
        // $this->load->model('Ujian_model', 'ujian');
        $this->form_validation->set_error_delimiters('', '');
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }

    public function index()
    {
        $user = $this->ion_auth->user()->row();
        $data = [
            'user' => $user,
            'judul'    => 'Soal',
            'subjudul' => 'Bank Soal',
            'guru' => $this->soal->getMapelGuru($user->username)
        ];

        if ($this->ion_auth->is_admin()) {
            //Jika admin maka tampilkan semua mapel
            $data['mapel'] = $this->master->getAllMapel();
        } else {
            //Jika bukan maka mapel dipilih otomatis sesuai mapel guru
            $data['mapel'] = $this->soal->getMapelGuru($user->username);
        }

        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('soal/data');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function detail($id)
    {
        $user = $this->ion_auth->user()->row();
        $data = [
            'user'      => $user,
            'judul'     => 'Soal',
            'subjudul'  => 'Detail Soal',
            'soal'      => $this->soal->getSoalById($id),
            'guru' 		=> $this->master->getGuruById($id),
        ];

        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('soal/detail');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function add()
    {
        $user = $this->ion_auth->user()->row();
        $data = [
            'user'      => $user,
            'judul'        => 'Soal',
            'subjudul'  => 'Buat Soal'
        ];

        if ($this->ion_auth->is_admin()) {
            //Jika admin maka tampilkan semua mapel
            $data['guru'] = $this->soal->getAllGuru();
        } else {
            //Jika bukan maka mapel dipilih otomatis sesuai mapel guru
            $data['guru'] = $this->soal->getMapelGuru($user->username);
        }

        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('soal/add');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function edit($id = false)
    {
        $user = $this->ion_auth->user()->row();
        $data = [
            'user'      => $user,
            'judul'        => 'Soal',
            'subjudul'  => 'Edit Soal',
            'soal'      => $this->soal->getSoalById($id),
        ];

        if ($this->ion_auth->is_admin()) {
            //Jika admin maka tampilkan semua mapel
            $data['guru'] = $this->soal->getAllGuru();
        } else {
            //Jika bukan maka mapel dipilih otomatis sesuai mapel guru
            $data['guru'] = $this->soal->getMapelGuru($user->username);
        }

        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('soal/edit');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function data($id = null, $guru = null)
    {
        $this->output_json($this->soal->getDataSoal($id, $guru), false);
    }

    public function validasi()
    {
        if ($this->ion_auth->is_admin()) {
            $this->form_validation->set_rules('guru_id', 'Guru', 'required');
        }
        // $this->form_validation->set_rules('soal', 'Soal', 'required');
        // $this->form_validation->set_rules('jawaban_a', 'Jawaban A', 'required');
        // $this->form_validation->set_rules('jawaban_b', 'Jawaban B', 'required');
        // $this->form_validation->set_rules('jawaban_c', 'Jawaban C', 'required');
        // $this->form_validation->set_rules('jawaban_d', 'Jawaban D', 'required');
        // $this->form_validation->set_rules('jawaban_e', 'Jawaban E', 'required');
        $this->form_validation->set_rules('jawaban', 'Kunci Jawaban', 'required');
        $this->form_validation->set_rules('bobot', 'Bobot Soal', 'required|max_length[2]');
    }

    public function file_config()
    {
        $allowed_type     = [
            "image/jpeg", "image/jpg", "image/png", "image/gif",
            "audio/mpeg", "audio/mpg", "audio/mpeg3", "audio/mp3", "audio/x-wav", "audio/wave", "audio/wav",
            "video/mp4", "application/octet-stream"
        ];
        $config['upload_path']      = FCPATH . 'uploads/bank_soal/';
        $config['allowed_types']    = 'jpeg|jpg|png|gif|mpeg|mpg|mpeg3|mp3|wav|wave|mp4';
        $config['encrypt_name']     = TRUE;

        return $this->load->library('upload', $config);
    }

    public function save()
    {
        $method = $this->input->post('method', true);
        $this->validasi();
        $this->file_config();


        if ($this->form_validation->run() === FALSE) {
            $method === 'add' ? $this->add() : $this->edit();
        } else {
            $data = [
                'soal'      => $this->input->post('soal', true),
                'jawaban'   => $this->input->post('jawaban', true),
                'bobot'     => $this->input->post('bobot', true),
            ];

            $abjad = ['a', 'b', 'c', 'd', 'e'];

            // Inputan Opsi
            foreach ($abjad as $abj) {
                $data['opsi_' . $abj]    = $this->input->post('jawaban_' . $abj, true);
            }

            $i = 0;
            foreach ($_FILES as $key => $val) {
                $img_src = FCPATH . 'uploads/bank_soal/';
                $getsoal = $this->soal->getSoalById($this->input->post('id_soal', true));

                $error = '';
                if ($key === 'file_soal') {
                    if (!empty($_FILES['file_soal']['name'])) {
                        if (!$this->upload->do_upload('file_soal')) {
                            $error = $this->upload->display_errors();
                            show_error($error, 500, 'File Soal Error');
                            exit();
                        } else {
                            if ($method === 'edit') {
                                if (!unlink($img_src . $getsoal->file)) {
                                    show_error('Error saat delete gambar <br/>' . var_dump($getsoal), 500, 'Error Edit Gambar');
                                    exit();
                                }
                            }
                            $data['file'] = $this->upload->data('file_name');
                            $data['tipe_file'] = $this->upload->data('file_type');
                        }
                    }
                } else {
                    $file_abj = 'file_' . $abjad[$i];
                    if (!empty($_FILES[$file_abj]['name'])) {
                        if (!$this->upload->do_upload($key)) {
                            $error = $this->upload->display_errors();
                            show_error($error, 500, 'File Opsi ' . strtoupper($abjad[$i]) . ' Error');
                            exit();
                        } else {
                            if ($method === 'edit') {
                                if (!unlink($img_src . $getsoal->$file_abj)) {
                                    show_error('Error saat delete gambar', 500, 'Error Edit Gambar');
                                    exit();
                                }
                            }
                            $data[$file_abj] = $this->upload->data('file_name');
                        }
                    }
                    $i++;
                }
            }

            if ($this->ion_auth->is_admin()) {
                $pecah = $this->input->post('guru_id', true);
                $pecah = explode(':', $pecah);
                $data['guru_id'] = $pecah[0];
                $data['mapel_id'] = end($pecah);
            } else {
                $data['guru_id'] = $this->input->post('guru_id', true);
                $data['mapel_id'] = $this->input->post('mapel_id', true);
            }

            if ($method === 'add') {
                //push array
                $data['created_on'] = time();
                $data['updated_on'] = time();
                //insert data
                $this->master->create('tb_soal', $data);
            } else if ($method === 'edit') {
                //push array
                $data['updated_on'] = time();
                //update data
                $id_soal = $this->input->post('id_soal', true);
                $this->master->update('tb_soal', $data, 'id_soal', $id_soal);
            } else {
                show_error('Method tidak diketahui', 404);
            }
            redirect('soal');
        }
    }

    public function delete()
    {
        $chk = $this->input->post('checked', true);

        // Delete File
        foreach ($chk as $id) {
            $abjad = ['a', 'b', 'c', 'd', 'e'];
            $path = FCPATH . 'uploads/bank_soal/';
            $soal = $this->soal->getSoalById($id);
            // Hapus File Soal
            if (!empty($soal->file)) {
                if (file_exists($path . $soal->file)) {
                    unlink($path . $soal->file);
                }
            }
            //Hapus File Opsi
            $i = 0; //index
            foreach ($abjad as $abj) {
                $file_opsi = 'file_' . $abj;
                if (!empty($soal->$file_opsi)) {
                    if (file_exists($path . $soal->$file_opsi)) {
                        unlink($path . $soal->$file_opsi);
                    }
                }
            }
        }

        if (!$chk) {
            $this->output_json(['status' => false]);
        } else {
            if ($this->master->delete('tb_soal', $chk, 'id_soal')) {
                $this->output_json(['status' => true, 'total' => count($chk)]);
            }
        }
    }

    public function csv($error = false)
    {
        $user = $this->ion_auth->user()->row();
        $data = [
            'user'      => $user,
            'judul'     => 'Upload Soal',
            'subjudul'  => 'Upload Soal File CSV',
            'error'     => $error,
        ];

        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('soal/uploadCSV');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function uploadCSV()
    {
        $user = $this->ion_auth->user()->row();
        $guruMapel = $this->soal->getMapelGuru($user->username);
        $file = $_FILES['soal-csv']['tmp_name'];
        $ekstensi  = explode('.', $_FILES['soal-csv']['name']);

        if (empty($file)) {
            return $this->csv('* File tidak boleh kosong!');
        } else {
            if (strtolower(end($ekstensi)) === 'csv' && $_FILES["soal-csv"]["size"] > 0) {
                $data = [];
                $i = 0;
                $handle = fopen($file, "r");

                while (($row = fgetcsv($handle, 2048))) {
                    $i++;

                    if ($i === 1) {
                        if (
                            $row[1] !== "bobot" || $row[2] !== "soal" || $row[3] !== "jawaban-a" || $row[4] !== "jawaban-b" ||
                            $row[5] !== "jawaban-c" || $row[6] !== "jawaban-d" || $row[7] !== "jawaban-e" || $row[8] !== "jawaban-benar"
                        ) {
                            return $this->csv('* Format tidak sesuai!');
                        } else {
                            continue;
                        }
                    }

                    $data[] = [
                        'guru_id'      => $guruMapel->id_guru,
                        'mapel_id'     => $guruMapel->mapel_id,
                        'bobot'         => $row[1],
                        'soal'          => $row[2],
                        'opsi_a'        => $row[3],
                        'opsi_b'        => $row[4],
                        'opsi_c'        => $row[5],
                        'opsi_d'        => $row[6],
                        'opsi_e'        => $row[7],
                        'jawaban'       => $row[8],
                        'created_on'    => time(),
                        'updated_on'    => time(),
                    ];
                }

                $this->master->create('tb_soal', $data, true);

                fclose($handle);
                redirect('soal');
            } else {
                return $this->csv('* Hanya untuk file CSV!');
            }
        }
    }
    public function import($import_data = null, $errFile = false)
    {
        $data = [
            'user' => $this->ion_auth->user()->row(),
            'judul'    => 'Soal',
            'subjudul' => 'Import Soal',
            'errFile' => $errFile,
            'import' => $import_data
        ];

        $this->load->view('_templates/dashboard/_header', $data);
        $this->load->view('soal/import');
        $this->load->view('_templates/dashboard/_footer');
    }
    public function preview()
    {
        $config['upload_path']        = './uploads/import/';
        $config['allowed_types']    = 'xls|xlsx|csv';
        $config['max_size']            = 2048;
        $config['encrypt_name']        = true;

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
                    'bobot' => $sheetData[$i][1],
                    'soal' => $sheetData[$i][2],
                    'jawaban_a' => $sheetData[$i][3],
                    'jawaban_b' => $sheetData[$i][4],
                    'jawaban_c' => $sheetData[$i][5],
                    'jawaban_d' => $sheetData[$i][6],
                    'jawaban_e' => $sheetData[$i][7],
                    'jawaban_benar' => $sheetData[$i][8],
                ];
            }

            unlink($file);

            $this->import($data, "200");
            return;
        }
    }

    public function do_import()
    {
        $user = $this->ion_auth->user()->row();
        $guruMapel = $this->soal->getMapelGuru($user->username);
        $input = json_decode($this->input->post('data', true));
        $data = [];
        foreach ($input as $d) {
            $data[] = [
                'guru_id'      => $guruMapel->id_guru,
                'mapel_id'     => $guruMapel->mapel_id,
                'bobot'         => $d->bobot,
                'soal'          => $d->soal,
                'opsi_a'        => $d->jawaban_a,
                'opsi_b'        => $d->jawaban_b,
                'opsi_c'        => $d->jawaban_c,
                'opsi_d'        => $d->jawaban_d,
                'opsi_e'        => $d->jawaban_e,
                'jawaban'       => $d->jawaban_benar,
                'created_on'    => time(),
                'updated_on'    => time()
            ];
        }

        $this->master->create('tb_soal', $data, true);
        echo 200;
        return;
    }
}
