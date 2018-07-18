<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controle extends CI_Controller {

  public function __construct(){

    parent::__construct();

    $this->load->model('Empresa_model', 'empresamodel');
    $this->load->model('Horario_model', 'horasmodel');
    $this->load->model('Cargos_model', 'cargosmodel');
    $this->load->model('Erros_model', 'errosmodel');
  }

   public function altera_dadosempresa(){

    if ((!isset($_SESSION["logado"])) and ($_SESSION["logado"] == false)) {
      redirect("/");
    }
    // cria um objeto data
    $data = new stdClass();

    $clientecode = $this->empresamodel->cliente_code($_SESSION["guest_empresa"]);

    $config['upload_path']          = './assets/img/logo_empresas/';
    $config['allowed_types']        = 'jpg';
    $config['file_name']						= $clientecode . '.jpg';
    $config['overwrite']            = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('logo_cliente')){

      $data->warning = "Ops! Pode ter ocorrido um problema ao cadastrar a logo do cliente.";


    } 

    $empresa = array(
      'nome'      => $this->input->post("empresa"),
      'missao'    => $this->input->post('missao'),
      'visao'     => $this->input->post('visao'),
      'logo_code' => $config['file_name'],
      'valores'   => $this->input->post('valores')
    );

    if($this->empresamodel->editar_empresa($empresa, $_SESSION['guest_empresa'])){

      $data->success = "Dados alterados com sucesso!!";

      $dados['pagina'] = "Dados da empresa";
      $dados['pg'] = "empresa";
      $dados['submenu'] = "dados";

      //dados do banco(nome da empresa, nome usuario);
      $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION['guest_empresa']);

      $dados['empresa'] = $this->empresamodel->dados_empresa($_SESSION['guest_empresa']);

      $this->load->view('template/html_header', $dados);
      $this->load->view('template/header');
      $this->load->view('template/menu');
      $this->load->view('empresa', $data);
      $this->load->view('template/footer');
      $this->load->view('template/html_footer');

    } else {

      $data->error = "Ocorreu um erro ao realizar a alteração";

      $dados['pagina'] = "Dados da empresa";
      $dados['pg'] = "empresa";
      $dados['submenu'] = "dados";

      //dados do banco(nome da empresa, nome usuario);
      $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION['guest_empresa']);

      $dados['empresa'] = $this->empresamodel->dados_empresa($_SESSION["quest_empresa"]);

      $this->load->view('template/html_header', $dados);
      $this->load->view('template/header');
      $this->load->view('template/menu');
      $this->load->view('empresa', $data);
      $this->load->view('template/footer');
      $this->load->view('template/html_footer');
    }
  }
}
