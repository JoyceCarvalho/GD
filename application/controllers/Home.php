<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct(){

        parent::__construct();

        $this->load->model('Empresa_model', 'empresamodel');
        $this->load->model('usuario_model', 'usermodel');
        $this->load->model('horario_model', 'horasmodel');
        $this->load->model('cargos_model', 'cargosmodel');
        $this->load->model('documentos_model', 'docmodel');
        $this->load->model('pop_model', 'popmodel');

    }

	public function index(){

        if ((isset($_SESSION['logado'])) && ($_SESSION['logado'] == true)) {

            $dados['pagina']    = "Página Inicial";
            $dados['pg']        = "Inicial";
            $dados['submenu']   = "";

            //dados do banco(nome da empresa, nome usuario);
            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION['idempresa']);
            
            //retorna a quantidade de documentos no link meus documentos
            $funcionario = $this->docmodel->listar_meus_documentos_funcionario($_SESSION["idusuario"]);
            //$cargo = $this->docmodel->listar_meus_documentos_cargos($_SESSION["idusuario"]);
            //$total = count($funcionario) + count($cargo);
            $total = count($funcionario);
            $dados["meus_documentos"] = $total;

            //retorna a quantidade de documentos em andamento
            /*if (($_SESSION["is_admin"] == true) || ($_SESSION["is_coordenador"])) {

                $a = $this->docmodel->listar_documentos_em_andamento($_SESSION["idempresa"]);    
                $andamento = count($a);

            } else {

                $a1 = $this->docmodel->listar_documentos_andamento_cargos($_SESSION["idusuario"]);
                $a2 = $this->docmodel->listar_documentos_andamento_funcionarios($_SESSION["idusuario"]);
                $andamento = count($a1) + count($a2);
                
            }*/
            $a = $this->docmodel->listar_documentos_em_andamento($_SESSION["idempresa"]);    
            $andamento = count($a);
            $dados["em_andamento"] = $andamento;

            //retorna a quantidade de documentos com erro
            $erro = $this->docmodel->listar_documentos_com_erros($_SESSION["idempresa"]);
            $dados["com_erro"] = count($erro);

            //retorna a quantidade de documentos cancelados
            $cancelados = $this->docmodel->listar_documentos_cancelados($_SESSION["idempresa"]);
            $dados["documentos_cancelados"] = count($cancelados);

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
        $dados['sub']       = "usuariolist";

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
        $dados['submenu']   = "usuario";
        $dados['sub']       = "usuariocad";

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
            $dados['full_pop']      = $this->popmodel->listar_pop($id);
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

            $dados['pagina']  = "Cargos";
            $dados['pg']      = "empresa";
            $dados['submenu'] = "cargos";
            $dados['sub']     = "cargoslist";

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
            $dados['sub']       = "cargoscad";

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
            $dados["sub"]       = "horalist";

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
            $dados["sub"]       = "horacad";

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
            $dados["sub"]       = "feriadolist";

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
            $dados["sub"]       = "feriadocad";

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
            $dados["pg"]        = "empresa";
            $dados["submenu"]   = "ausencia";
            $dados["sub"]       = "ausencialist";

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
            $dados["pg"]        = "empresa";
            $dados["submenu"]   = "ausencia";
            $dados["sub"]       = "ausenciacad";

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
