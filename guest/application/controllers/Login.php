<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

  function __construct(){
    parent::__construct();

    $this->load->model('usuario_model', 'user_model');
    $this->load->model('Empresa_model', 'empresamodel');
  }

  public function index(){
    
  }

  public function acesso($empresa, $usuario){

    if ((!isset($_SESSION['logado'])) && ($_SESSION['logado'] != true)) {
      redirect('/');
    } 

    // cria um objeto data
    $data = new stdClass();

    $user           = $this->user_model->get_user($usuario);
    $coordenador    = $this->user_model->is_coordenador($usuario);

    //print_r($user);

    // set session user datas
    $_SESSION['idusuario']          = (int)$usuario;
    $_SESSION['usuario']            = (string)$user->usuario;
    $_SESSION['nome_user']          = (string)$user->nome;
    $_SESSION['guest_empresa']      = (int)$empresa;
    $_SESSION['logado']             = (bool)true;
    $_SESSION['is_confirmed']       = (bool)$user->ativo;
    $_SESSION['is_admin']           = (bool)true;
    $_SESSION['is_coordenador']     = (bool)$coordenador;

    /*if ($cliente_code == "sgtgestaoetecnologia") {
      $_SESSION['sgt_admin']          = (bool)true;
    } else {
      $_SESSION['sgt_admin']          = (bool)false;
    }*/

    // se o login for ok
    redirect("home");
 
  }

}
