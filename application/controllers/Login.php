<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

  function __construct(){
    parent::__construct();

    $this->load->model('usuario_model', 'user_model');
    $this->load->model('Empresa_model', 'empresamodel');
  }

  public function index(){

    if ((isset($_SESSION['logado'])) && ($_SESSION['logado'] == true)) {

      redirect('home');

    } else {

      $this->load->view('template/html_header');
      $this->load->view('auth/login');
      $this->load->view('template/html_footer');

    }

  }


  public function login(){

    // cria um objeto data
    $data = new stdClass();

    // carrega helper form e validation library
    $this->load->library('form_validation');

    // set validation rules
    $this->form_validation->set_rules('cliente_code', 'Código da Empresa', 'trim|required');
    $this->form_validation->set_rules('usuario', 'Usuário', 'trim|required');
    $this->form_validation->set_rules('senha', 'Senha', 'trim|required');

    if ($this->form_validation->run() == false) {

      // Se a validação não estiver ok, envia a validação com erros para a view
      $data->error = "Dados inválidos!";

      $this->load->view('template/html_header');
      $this->load->view('auth/login');
      $this->load->view('template/html_footer');

    } else {

      // pega as variaveis do formulario
      $cliente_code = $this->input->post('cliente_code');
      $usuario = $this->input->post('usuario');
      $senha = sha1($this->input->post('senha'));

      if ($this->user_model->verificar_dados($usuario, $senha, $cliente_code)) {

        $user_id        = $this->user_model->get_id_por_usuario($usuario, $cliente_code);
        $user           = $this->user_model->get_user($user_id);
        $admin          = $this->user_model->is_admin($user_id);
        $coordenador    = $this->user_model->is_coordenador($user_id);

        // set session user datas
        $_SESSION['idusuario']          = (int)$user->id;
        $_SESSION['usuario']            = (string)$user->usuario;
        $_SESSION['nome_user']          = (string)$user->nome;
        $_SESSION['idempresa']          = (int)$user->fk_idempresa;
        $_SESSION['logado']             = (bool)true;
        $_SESSION['is_confirmed']       = (bool)$user->ativo;
        $_SESSION['is_admin']           = (bool)$admin;
        $_SESSION['is_coordenador']     = (bool)$coordenador;

        if ($cliente_code == "sgtgestaoetecnologia") {
          $_SESSION['sgt_admin']          = (bool)true;
        } else {
          $_SESSION['sgt_admin']          = (bool)false;
        }

        // se o login for ok
        redirect("home");

      } else {

        // login com falha
        $data->error = 'Dados incorretos';

        // envia erro para a view
        $this->load->view('template/html_header', $data);
        $this->load->view('auth/login');
        $this->load->view('template/html_footer');

      }

    }

  }


  public function logout() {

    // cria o objeto data
    $data = new stdClass();

    if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {

      // remove session datas
      foreach ($_SESSION as $key) {
        unset($_SESSION[$key]);
      }

      // logout com sucesso
      $this->load->view('template/html_header');
      $this->load->view('auth/login');
      $this->load->view('template/html_footer');

    } else {

      // aqi o usuario não esta logado nos não podemos deslogalo,
      // redirecionamos ele para a raiz
      redirect('/');

    }

  }


}
