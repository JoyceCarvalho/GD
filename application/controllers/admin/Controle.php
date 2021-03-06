<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controle extends CI_Controller {

  function __construct(){

    parent::__construct();

    $this->load->model('Empresa_model', 'empresamodel');
    $this->load->model('Horario_model', 'horasmodel');
    $this->load->model('Cargos_model', 'cargosmodel');
    $this->load->model('Erros_model', 'errosmodel');
    $this->load->model('Usuario_model', 'usermodel');
    $this->load->model('Email_model', 'emailmodel');
  }

  /**
  * Direciona para a pagina de listagem de empresas se estiver logado, se não redireciona para a pagina de login
  */
  public function index(){

    if ((isset($_SESSION['logado'])) && ($_SESSION['logado'] == true)) {

      //Dados voláteis do menu da pagina
      $dados['pagina'] = "Listagem de Empresas";
      $dados['pg'] = "controle";
      $dados['submenu'] = "empresalist";

      //dados do banco (nome Empresa, nome usuário) utilizados no menu
      $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

      //todas as empresas cadastradas;
      $dados['list_empresa'] = $this->empresamodel->listar_empresas();

      $this->load->view('template/html_header', $dados);
      $this->load->view('template/header');
      $this->load->view('template/menu');
      $this->load->view('admin/listar_empresas');
      $this->load->view('template/footer');
      $this->load->view('template/html_footer');
    } else {
      redirect("/");
    }

  }

  /**
  * Redireciona para a pagina de cadastro de empresas se estiver logado, se não direciona para a pagina de login
  */
  public function pagina_cadastro(){

    if ((isset($_SESSION['logado'])) && ($_SESSION['logado'] == true)) {

      //Dados voláteis do menu da pagina
      $dados['pagina'] = "Listagem de Empresas";
      $dados['pg'] = "controle";
      $dados['submenu'] = "empresacad";

      //dados do banco (nome Empresa, nome usuário) utilizados no menu
      $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

      $this->load->view('template/html_header', $dados);
      $this->load->view('template/header');
      $this->load->view('template/menu');
      $this->load->view('admin/cadastrar_empresa');
      $this->load->view('template/footer');
      $this->load->view('template/html_footer');
    } else {
      redirect('/');
    }

  }

  /**
  * redireciona para a pagina de edição da empresa
  */
  public function editar_empresa(){

    if(!(isset($_SESSION["logado"])) and ($_SESSION["logado"] == false)){
      redirect("/");
    }

    $id = $this->input->post("idempresa");

    //Dados voláteis do menu da pagina
    $dados['pagina']        = "Listagem de Empresas";
    $dados['pg']            = "controle";
    $dados['submenu']       = "empresalist";

    //dados do banco (nome empresa logada) utilizados no menu
    $dados['nome_empresa']          = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

    //dados da empresa cadastrada;
    $dados['dados_empresa']         = $this->empresamodel->dados_empresa($id);


    $this->load->view('template/html_header', $dados);
    $this->load->view('template/header');
    $this->load->view('template/menu');
    $this->load->view("admin/editar_empresa");
    $this->load->view('template/footer');
    $this->load->view('template/html_footer');

  }

  public function empresa_editar(){

    if (!(isset($_SESSION["logado"])) and ($_SESSION["logado"] == false)) {
      redirect("/");
    }

    // cria um objeto data
    $data = new stdClass();

    $clientecode  = $this->input->post("cliente_code");

    $config['upload_path']          = './assets/img/logo_empresas/';
    $config['allowed_types']        = 'jpg';
    $config['file_name']						= $clientecode . '.jpg';

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('logo_cliente')){

      $data->warning = "Ops! Pode ter ocorrido um problema ao cadastrar a logo do cliente.";
      $logo_code = null;

    } else {

     $logo_code = $config["file_name"];
      
    }

    $idempresa = $this->input->post('id_empresa');
    
    if(!empty($clientecode)){
      
      $empresa = array(
        'nome' => $this->input->post("empresa"),
        'cliente_code' => $clientecode,
        'logo_code'    => $logo_code
      );

    } else {

      $empresa = array(
        'nome' => $this->input->post("empresa"),
        'cliente_code' => $clientecode
      );

    }

    if($this->empresamodel->editar_empresa($empresa, $idempresa)){

      $idcoordenador = $this->input->post('id_coordenador');

      $coordenador = array(
        'nome' => $this->input->post('nome'),
        'email' => $this->input->post('email'),
        'usuario' => $this->input->post('usuario')
      );

      if($this->empresamodel->editar_coordenador($coordenador, $idcoordenador)){

        $data->success = "Alteração realizada com sucesso!";

        //Dados voláteis do menu da pagina
        $dados['pagina']        = "Listagem de Empresas";
        $dados['pg']            = "controle";
        $dados['submenu']       = "empresalist";

        //dados do banco (nome empresa logada) utilizados no menu
        $dados['nome_empresa']          = $this->empresamodel->nome_empresa($_SESSION['logado']);

        //dados da empresa cadastrada;
        $dados['dados_empresa']         = $this->empresamodel->dados_empresa($idempresa);
        $dados['dados_coordenador']     = $this->empresamodel->coordenador($idempresa);

        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu', $data);
        $this->load->view("admin/editar_empresa");
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

      } else {

        $data->error = "Ocorreu um erro ao editar os dados do coordenador!";

        //Dados voláteis do menu da pagina
        $dados['pagina']        = "Listagem de Empresas";
        $dados['pg']            = "controle";
        $dados['submenu']       = "empresalist";

        //dados do banco (nome empresa logada) utilizados no menu
        $dados['nome_empresa']          = $this->empresamodel->nome_empresa($_SESSION['logado']);

        //dados da empresa cadastrada;
        $dados['dados_empresa']         = $this->empresamodel->dados_empresa($idempresa);
        $dados['dados_coordenador']     = $this->empresamodel->coordenador($idempresa);

        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu', $data);
        $this->load->view("admin/editar_empresa");
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');
      }

    } else {

      $data->error = "Ocorreu um erro ao realizar a alteração";

      //Dados voláteis do menu da pagina
      $dados['pagina']        = "Listagem de Empresas";
      $dados['pg']            = "controle";
      $dados['submenu']       = "empresalist";

      //dados do banco (nome empresa logada) utilizados no menu
      $dados['nome_empresa']          = $this->empresamodel->nome_empresa($_SESSION['logado']);

      //dados da empresa cadastrada;
      $dados['dados_empresa']         = $this->empresamodel->dados_empresa($idempresa);
      $dados['dados_coordenador']     = $this->empresamodel->coordenador($idempresa);

      $this->load->view('template/html_header', $dados);
      $this->load->view('template/header');
      $this->load->view('template/menu', $data);
      $this->load->view("admin/editar_empresa");
      $this->load->view('template/footer');
      $this->load->view('template/html_footer');
    }
  }

  public function excluir_empresa(){

    if (!(isset($_SESSION["logado"])) && ($_SESSION["logado"] == false)) {
      redirect("/");
    }

    $id = $this->input->post("idempresa");

    // cria um objeto data
    $data = new stdClass();

    if($this->empresamodel->excluir_empresa($id)){

      //$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Empresa deletada com sucesso!</div>');

      $data->success = "Empresa deletada com sucesso!";

      $dados['pagina'] = "Listagem de Empresas";
      $dados['pg'] = "controle";
      $dados['submenu'] = "empresalist";

      //dados do banco (nome Empresa, nome usuário) utilizados no menu
      $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

      //todas as empresas cadastradas;
      $dados['list_empresa'] = $this->empresamodel->listar_empresas();

      $this->load->view('template/html_header', $dados);
      $this->load->view('template/header');
      $this->load->view('template/menu', $data);
      $this->load->view('admin/listar_empresas');
      $this->load->view('template/footer');
      $this->load->view('template/html_footer');

    } else {

      //$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Ocorreu um erro ao excluir os dados! Tente novamente mais tarde!</div>');
      $data->error = "Ocorreu um erro ao excluir os dados! Tente novamente mais tarde!";

      $dados['pagina'] = "Listagem de Empresas";
      $dados['pg'] = "controle";
      $dados['submenu'] = "empresalist";

      //dados do banco (nome Empresa, nome usuário) utilizados no menu
      $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

      //todas as empresas cadastradas;
      $dados['list_empresa'] = $this->empresamodel->listar_empresas();

      $this->load->view('template/html_header', $dados);
      $this->load->view('template/header');
      $this->load->view('template/menu', $data);
      $this->load->view('admin/listar_empresas');
      $this->load->view('template/footer');
      $this->load->view('template/html_footer');

    }
  }

  public function cadastrar_empresa(){

    if ((!isset($_SESSION["logado"])) and ($_SESSION["logado"] == false)) {
      redirect("/");
    }
    // cria um objeto data
    $data = new stdClass();

    $clientecode  = $this->input->post("cliente_code");

    $config['upload_path']          = './assets/img/logo_empresas/';
    $config['allowed_types']        = 'jpg';
    $config['file_name']						= $clientecode . '.jpg';

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('logo_cliente')){

      $data->warning = "Ops! Pode ter ocorrido um problema ao cadastrar a logo do cliente.";
      $logo_code = null;

    } else {

     $logo_code = $config["file_name"];
      
    }

    $empresa = array(
      'nome' => $this->input->post("empresa"),
      'cliente_code' => $this->input->post("cliente_code"),
      'logo_code' => $logo_code
    );

    $idempresa = $this->empresamodel->cadastrar_empresa($empresa);

    for ($i=0; $i < 3; $i++) { 
      if ($i==0) {
        $tipo_erro["titulo"] = "Leve";
      } elseif($i==1) {
        $tipo_erro["titulo"] = "Intermediário";
      } else{
        $tipo_erro["titulo"] = "Grave";
      }

      $tipo_erro["fk_idempresa"] = $idempresa;

      $this->errosmodel->cadastrar_tipo_erro($tipo_erro);

    }

    $cargos = array(
      'titulo' => 'Coordenador',
      'fk_idempresa' => $idempresa
    );

    $idcargo = $this->cargosmodel->cadastrar_cargos($cargos);

    $horario = array(
      'titulo' => "Regular",
      'manha_entrada' => "07:00:00",
      'manha_saida' => "12:00:00",
      'tarde_entrada' => "13:00:00",
      'tarde_saida' => "18:00:00",
      'fk_idempresa' => $idempresa
    );

    $horario_trab = $this->horasmodel->cadastrar_horario($horario);

    $coordenador = array(
      'nome' => $this->input->post('nome'),
      'email' => $this->input->post('email'),
      'usuario' => $this->input->post('usuario'),
      'senha' => $this->usermodel->hash_password($this->input->post('senha')),
      'fk_idempresa' => $idempresa,
      'fk_idcargos' => $idcargo,
      'fk_idhorariotrab' => $horario_trab
    );


    if ($this->empresamodel->cadastrar_coordenador($coordenador)) {

      $enviar = array(
        'tipo'         => 'novo_usuario',
        'pass' 		   => $this->input->post('senha'),
        'cliente_code' => $this->input->post("cliente_code"),
        'email'    	   => $this->input->post('email'),
        'nome'         => $this->input->post('nome'),
        'usuario'	   => $this->input->post('usuario')
      );
     
      $this->emailmodel->enviar_email($enviar);

      $data->success = "Empresa cadastrada com sucesso!";

      $dados['pagina'] = "Listagem de Empresas";
      $dados['pg'] = "controle";
      $dados['submenu'] = "empresacad";

      //dados do banco (nome Empresa, nome usuário) utilizados no menu
      $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

      $this->load->view('template/html_header', $dados);
      $this->load->view('template/header', $data);
      $this->load->view('template/menu');
      $this->load->view('admin/cadastrar_empresa');
      $this->load->view('template/footer');
      $this->load->view('template/html_footer');

    } else {

      $data->error = "Ocorreu um erro ao cadastrar! Entre em contato com administrador e tente novamente mais tarde!";

      $dados['pagina'] = "Listagem de Empresas";
      $dados['pg'] = "controle";
      $dados['submenu'] = "cadempresa";

      //dados do banco (nome Empresa, nome usuário) utilizados no menu
      $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

      $this->load->view('template/html_header', $dados);
      $this->load->view('template/header', $data);
      $this->load->view('template/menu');
      $this->load->view('admin/cadastrar_empresa');
      $this->load->view('template/footer');
      $this->load->view('template/html_footer');
    }
  }

  public function altera_dadosempresa(){

    if ((!isset($_SESSION["logado"])) and ($_SESSION["logado"] == false)) {
      redirect("/");
    }
    // cria um objeto data
    $data = new stdClass();

    $clientecode = $this->empresamodel->cliente_code($_SESSION["idempresa"]);

    $config['upload_path']          = './assets/img/logo_empresas/';
    $config['allowed_types']        = 'jpg';
    $config['file_name']						= $clientecode . '.jpg';
    $config['overwrite']            = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('logo_cliente')){

      $data->warning = "Ops! Pode ter havido um problema ao cadastrar a logo do cliente.";

    } 

    $empresa = array(
      'nome'      => $this->input->post("empresa"),
      'missao'    => $this->input->post('missao'),
      'visao'     => $this->input->post('visao'),
      'logo_code' => $config['file_name'],
      'valores'   => $this->input->post('valores')
    );

    if($this->empresamodel->editar_empresa($empresa, $_SESSION['idempresa'])){

      $data->success = "Dados alterados com sucesso!!";

      $dados['pagina'] = "Dados da empresa";
      $dados['pg'] = "empresa";
      $dados['submenu'] = "dados";

      //dados do banco(nome da empresa, nome usuario);
      $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

      $dados['empresa'] = $this->empresamodel->dados_empresa($_SESSION['idempresa']);

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
      $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

      $dados['empresa'] = $this->empresamodel->dados_empresa($_SESSION["idempresa"]);

      $this->load->view('template/html_header', $dados);
      $this->load->view('template/header');
      $this->load->view('template/menu');
      $this->load->view('empresa', $data);
      $this->load->view('template/footer');
      $this->load->view('template/html_footer');
    }
  }

  public function verifica_empresa(){

    $cliente_code = $_POST['cliente_code'];

    $empresa = count($this->empresamodel->verifica_existencia($cliente_code));

    if($empresa > 0){
      
      echo json_encode(array('mensagem' => 'Este cliente code já está sendo utilizado! Favor tentar outro.', 'valido' => 'not'));

    } else {

      echo json_encode(array('mensagem' => 'Cliente code válido!', 'valido' => 'is'));

    }
  }
}
