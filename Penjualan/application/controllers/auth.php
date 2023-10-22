<?php
defined('BASEPATH') or exit('No direct script access allowed');

class auth extends CI_Controller
{
    public $form_validation;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function Index()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', [
            'required' => 'Tolong masukkan Email Anda'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|trim', [
            'required' => 'Tolong isi Password anda'
        ]);
        if ($this->form_validation->run() == false) {

            $data['title'] = 'RUMAH SAKIT CINTA BANGSA';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array(); //Select * from user here email = $email
        // Mengecek apakah user ada
        if ($user) { {
                if ($user['status'] == 'verified') {

                    if (password_verify($password, $user['password'])) {

                        $_SESSION['email'] = $email;
                        redirect(base_url('user'));
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Password anda salah </div>');
                        redirect('auth');
                    }
                } else {
                    $info = "It's look like you haven't still verify your email - $email";
                    $_SESSION['email'] = $email;
                    $_SESSION['info'] = $info;
                    redirect(base_url('auth/otp'));
                }
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Email tidak terdaftar</div>');
            redirect('auth');
        }
    }

    public function registrasi()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim', [
            'required' => 'Tolong isi Nama anda'
        ]);
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', [
            'required' => 'Tolong isi Email anda'
        ]);

        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'required' => 'Tolong isi Password anda',
            'matches' => 'Password tidak sama',
            'min_length' => 'Password terlalu pendek'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');


        if ($this->form_validation->run() == false) {
            $data['title'] = 'Registrasi Akun';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registrasi');
            $this->load->view('templates/auth_footer');
        } else {
            $nama = htmlspecialchars($this->input->post('nama', true));
            $email = htmlspecialchars($this->input->post('email', true));
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            $code = rand(999999, 111111);

            $data = [
                'nama' => $nama,
                'email' => $email,
                'image' => 'default.jpg',
                'password' => $password,
                'user_role' => '2',
                'status' => 'not_verified',
                'data_created' => time(),
                'code' => $code

            ];

            $config = [
                'protocol' => 'smtp',
                'smtp_host' => 'ssl://smtp.googlemail,com',
                'smtp_user' => 'rizkycoba04@gmail.com',
                'smtp_pass' => 'snznmpzcnlvqfcul',
                'smtp_port' => 465,
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'newline' => "\r\n"
            ];

            $this->db->insert('user', $data);

            $this->load->library('email', $config);

            $this->email->from('rizkycoba04@gmail.com', 'Rumah sakit Cinta bangsa');
            $this->email->to($this->input->post('email'));
            $this->email->subject('CODE OTP RUMAH SAKIT CINTA BANGSA');
            $this->email->message('Kode OTP Anda adalah ' . $code);

            if ($this->email->send()) {
                $info = "We've sent a password reset otp to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                redirect(base_url('auth/otp'));
            } else {
                echo $this->email->print_debugger();
                die;
            }
        }
    }


    public function otp()
    {
        $this->form_validation->set_rules('otp', 'Otp', 'required|trim', [
            'required' => 'Tolong masukkan kode OTP anda'
        ]);
        if ($this->form_validation->run() == false) {
            $data['title'] = 'KODE VERIFIKASI';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/otp');
            $this->load->view('templates/auth_footer');
        } else {
            if (isset($_POST['check'])) {
                $otp_code = $_POST['otp'];
                $check_code = $this->db->query("SELECT * FROM user WHERE code = $otp_code");

                if ($check_code->num_rows() > 0) {
                    $fetch_data = $check_code->row_array();
                    $fetch_code = $fetch_data['code'];
                    $email = $fetch_data['email'];
                    $code = 0;
                    $status = 'verified';
                    $update_otp = $this->db->query("UPDATE user SET code = $code, status = '$status' WHERE code = $fetch_code");
                    if ($update_otp) {
                        $this->session->set_flashdata('success', 'Akun anda berhasil didaftarkan <br> Terimakasih ');
                        redirect('auth/otp');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Gagal dalam mengambil data </div>');
                        redirect('auth/otp');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> kode OTP anda salah </div>');
                    redirect('auth/otp');
                }
            }
        }
    }

    public function forgot()
    {
        $this->form_validation->set_rules('check-email', 'Check-Email', 'required|trim|valid_email', [
            'required' => 'Tolong masukkan email anda'
        ]);

        if ($this->form_validation->run() == false) {
            $data['title'] = 'FORGOT PASSWORD';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/forgot');
            $this->load->view('templates/auth_footer');
        } else {
            if (isset($_POST['check'])) {
                $email = htmlspecialchars($this->input->post('check-email', true));
                $user = $this->db->get_where('user', ['email' => $email])->row_array(); //select user where email = $email 

                if ($user) {
                    if ($user['status'] == 'verified') {
                        $code = rand(999999, 111111);
                        $insert_code = $this->db->query("update user set code = $code where email = '$email'");
                        if ($insert_code) {
                            $config = [
                                'protocol' => 'smtp',
                                'smtp_host' => 'ssl://smtp.googlemail,com',
                                'smtp_user' => 'rizkycoba04@gmail.com',
                                'smtp_pass' => 'snznmpzcnlvqfcul',
                                'smtp_port' => 465,
                                'mailtype' => 'html',
                                'charset' => 'utf-8',
                                'newline' => "\r\n"
                            ];
                            $this->load->library('email', $config);

                            $this->email->from('rizkycoba04@gmail.com', 'Rumah sakit Cinta bangsa');
                            $this->email->to($this->input->post('check-email'));
                            $this->email->subject('CODE OTP RUMAH SAKIT CINTA BANGSA');
                            $this->email->message('Kode OTP Anda adalah ' . $code);
                            if ($this->email->send()) {
                                $info = "We've sent a password reset otp to your email - $email";
                                $_SESSION['info'] = $info;
                                $_SESSION['email'] = $email;
                                redirect('auth/reset_code');
                            } else {
                                echo $this->email->print_debugger();
                                die;
                            }
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Password anda salah </div>');
                            redirect('auth');
                        }
                    } else {
                        $info = "It's look like you haven't still verify your email - $email";
                        $_SESSION['check-email'] = $email;
                        $_SESSION['info'] = $info;
                        redirect(base_url('auth/otp'));
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Email tidak terdaftar </div>');
                    redirect('auth/forgot');
                }
            }
        }
    }

    public function reset_code()
    {
        $this->form_validation->set_rules('reset-otp', 'Reset-otp   ', 'required|trim', [
            'required' => 'Tolong masukkan kode OTP anda'
        ]);

        if ($this->form_validation->run() == false) {
            $data['title'] = 'RESET CODE';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/reset_code');
            $this->load->view('templates/auth_footer');
        } else {

            if (isset($_POST['check-reset'])) {
                $otp_code = htmlspecialchars($this->input->post('reset-otp'));
                $check_code = $this->db->query("SELECT * FROM user WHERE code = $otp_code");

                if ($check_code->num_rows() > 0) {
                    $fetch_data = $check_code->row_array();
                    $email = $fetch_data['email'];
                    $_SESSION['email'] = $email;
                    $info = "Masukkan password baru anda";
                    $_SESSION['info'] = $info;
                    redirect('auth/reset_password');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> kode OTP anda salah </div>');
                    redirect('auth/otp');
                }
            }
        }
    }

    public function reset_password()
    {

        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'required' => 'Tolong isi Password anda',
            'matches' => 'Password tidak sama',
            'min_length' => 'Password terlalu pendek'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password]');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Reset Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/reset_password');
            $this->load->view('templates/auth_footer');
        } else {
            if (isset($_POST['reset_pass'])) {
                $email = $_SESSION['email'];
                $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                $code = 0;
                $update_pass = $this->db->query("update user set password = '$password', code = $code where email = '$email'");
                if ($update_pass) {

                    $this->session->unset_userdata('email');
                    $this->session->unset_userdata('user_role');
                    $this->session->unset_userdata('info');
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert"> anda berhasil mengubah password anda </div>');
                    redirect('auth');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Gagal mengganti password </div>');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Gagal dalam menginput data (Database Error) </div>');
            }
        }
    }
    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('user_role');
        $this->session->unset_userdata('info');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert"> Anda anda sudah logout </div>');
        redirect('auth');
    }
}
