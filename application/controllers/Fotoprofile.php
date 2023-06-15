<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fotoprofile extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth');
        }
        $this->load->helper('my'); // Load Library Ignited-Datatables
        $this->load->model('Master_model', 'master');
        $this->load->model('Users_model', 'users');
        $this->load->model('Dashboard_model', 'dashboard');
        $this->user = $this->ion_auth->user()->row();
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }
    public function index()
    {
        $user = $this->user;
        $data = [
            'user' => $this->ion_auth->user()->row(),
            'judul'    => 'Edit Profile',
            'subjudul' => 'Foto Profile'
        ];
        if ($this->ion_auth->in_group('guru')) {
            $data['guru'] = $this->dashboard->get_where('guru', 'nip', $user->username)->row();
        } else {
            $data['siswa'] = $this->dashboard->get_where('siswa a', 'nisn', $user->username)->row();
        }
        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('users/edit_profile');
        $this->load->view('_templates/dashboard/_footer.php');
    }
    public function file_config()
    {
        $allowed_type     = [
            "image/jpeg", "image/jpg", "image/png",
        ];
        $config['upload_path']      = FCPATH . 'uploads/profile/';
        $config['allowed_types']    = 'jpeg|jpg|png';
        $config['encrypt_name']     = TRUE;

        return $this->load->library('upload', $config);
    }
    public function save()
    {
        $this->file_config();
        $user = $this->ion_auth->user()->row();
        // $users = $this->users->getDatausers('users_id');
        // $input = json_decode($this->input->post('data', true));
        // $data = [];

        // $img_src = FCPATH . 'uploads/bank_soal/';
        // $getsoal = $this->soal->getSoalById($this->input->post('id_soal', true));
        $config['upload_path']          = '/uploads/profile/';
        $config['allowed_types']        = 'jpeg|jpg|png';
        // $config['max_size']             = 100;
        $config['max_width']            = 2048;
        // $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image')) {
            $error = array('error' => $this->upload->display_errors());

            redirect('fotoprofile', $error);
        } else {
            $data['image'] = $this->upload->data('file_name');

            // $this->load->view('upload_success', $data);
        }
        $id = $user->id;

        $this->master->update('users', $data, 'id', $id);
        redirect('fotoprofile');
    }
    public function remove()
    {
        $user = $this->ion_auth->user()->row();
        // hapus file
        unlink(FCPATH . "/uploads/profile/" . $user->image);

        // set avatar menjadi null
        $new_data = [
            'image' => 'default.png',
        ];
        $id = $user->id;
        $this->master->update('users', $new_data, 'id', $id);
        redirect('fotoprofile');
    }
}
