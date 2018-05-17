<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct(){

        parent::__construct();

        $this->load->model('Empresa_model', 'empresamodel');
        $this->load->model('usuario_model', 'usermodel');
        $this->load->model('horario_model', 'horasmodel');
        $this->load->model('cargos_model', 'cargosmodel');
        $this->load->model('documentos_model', 'docmodel');

    }

	public function index(){

        if ((isset($_SESSION['logado'])) && ($_SESSION['logado'] == true)) {

            $dados['pagina']    = "Página Inicial";
            $dados['pg']        = "Inicial";
            $dados['submenu']   = "";

            //dados do banco(nome da empresa, nome usuario);
            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION['idempresa']);
            //retorna a quantidade de documentos no link meus documentos
            $dados["meus_documentos"] = $this->docmodel->listar_meus_documentos_funcionario($_SESSION["idusuario"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('home');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            redirect('/');

        }

    }

    public function empresa(){

        if ((isset($_SESSION['logado'])) && ($_SESSION['logado'] == true)) {

            $dados['pagina']    = "Dados da empresa";
            $dados['pg']        = "empresa";
            $dados['submenu']   = "dados";

            //dados do banco(nome da empresa, nome usuario);
            $dados['nome_empresa']  = $this->empresamodel->nome_empresa($_SESSION['idempresa']);
            $dados['empresa']       = $this->empresamodel->dados_empresa($_SESSION['idempresa']);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('empresa');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            redirect('/');
        }

    }

    public function usuario(){

        $dados['pagina']    = "Usuários";
        $dados['pg']        = "empresa";
        $dados['submenu']   = "usuario";

        $dados['listagem_usuarios'] = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);

        //dados do banco (nome da empresa, nome usuário);
        $dados['nome_empresa']      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('usuarios');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');
    }

    public function usuario_cad(){

        $dados['pagina']    = "Usuários";
        $dados['pg']        = "empresa";
        $dados['submenu']   = "usuariocad";

        $dados['full_cargos']   = $this->cargosmodel->listar_cargos($_SESSION["idempresa"]);
        $dados['full_horarios'] = $this->horasmodel->listar_horario($_SESSION["idempresa"]);
        //dados do banco (nome da empresa, nome usuário);
        $dados['nome_empresa']  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('usuario_cadastro');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');
    }

    public function editar_usuario(){

        $id = $this->input->post("idusuario");

        if ((isset($_SESSION['logado'])) && ($_SESSION['logado'] == true)) {

            $dados['pagina']    = "Usuários";
            $dados['pg']        = "empresa";
            $dados['submenu']   = "usuario";

            $dados['full_cargos']   = $this->cargosmodel->listar_cargos($_SESSION["idempresa"]);
            $dados['full_horarios'] = $this->horasmodel->listar_horario($_SESSION["idempresa"]);
            $dados['usuario']       = $this->usermodel->dados_usuario($id);
            //dados do banco (nome da empresa);
            $dados['nome_empresa']  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('usuarios_edit');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');
        }

    }

    public function cargos(){

        if ((isset($_SESSION['idempresa'])) && ($_SESSION["idempresa"] == true)) {

            $dados['pagina'] = "Cargos";
            $dados['pg'] = "empresa";
            $dados['submenu'] = "cargos";

            $dados['listagem_cargos'] = $this->cargosmodel->listar_cargos($_SESSION["idempresa"]);

            //dados do banco (nome da empresa);
            $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('cargos');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            redirect('/');
        }

    }

    public function cargos_cad(){

        if((isset($_SESSION['idempresa'])) && ($_SESSION["idempresa"] == true)){

            $dados['pagina']    = "Cargos";
            $dados['pg']        = "empresa";
            $dados["submenu"]   = "cargos";

            //dados do banco (nome da empresa) menu
            $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('cargo_cadastro');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else{

            redirect('/');

        }
    }

    public function horarios(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"])) {

            $dados["pagina"]    = "Horários de Trabalho";
            $dados["pg"]        = "empresa";
            $dados["submenu"]   = "horarios";

            // dados do banco (nome empresa) menu
            $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["listagem_horarios"] = $this->horasmodel->listar_horario($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('horarios');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            redirect('/');

        }
    }

    public function horarios_cad(){

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"])){

            $dados["pagina"]    = "Horário de Trabalho";
            $dados["pg"]        = "empresa";
            $dados["submenu"]   = "horarios";

            // dados do banco (nome empresa) menu
            $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('horarios_cadastro');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            redirect('/');

        }

    }

    public function feriado(){

        if((isset($_SESSION['logado'])) && $_SESSION["logado"]){

            $dados["pagina"]    = "Feriados";
            $dados["pg"]        = "empresa";
            $dados["submenu"]   = "feriado";

            $this->load->model('feriados_model', 'feriadosmodel');

            // dados do banco (nome empresa) menu
            $dados['nome_empresa']      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados['listagem_feriados'] = $this->feriadosmodel->listar_feriados($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('feriados');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            redirect('/');

        }
    }

    public function feriado_cad(){

        if((isset($_SESSION['logado'])) && ($_SESSION["logado"] == true)){

            $dados["pagina"]    = "Feriados";
            $dados["pg"]        = "empresa";
            $dados["submenu"]   = "feriado";

            $this->load->model('feriados_model', 'feriadosmodel');

            // dados do banco (nome empresa) menu
            $dados['nome_empresa']      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('feriados_cadastro');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            redirect('/');

        }
    }

    public function ausencia_ferias(){

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $dados["pagina"]    = "Ausência / Férias";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "ausencia";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('ausencia_ferias');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            redirect("/");

        }
    }

    public function ausencia_ferias_cadastro(){

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $dados["pagina"]    = "Ausência / Férias";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "ausencia";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('ausencia_ferias_cad');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            redirect("/");

        }
    }

}
