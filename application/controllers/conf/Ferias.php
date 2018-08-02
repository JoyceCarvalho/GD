<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ferias extends CI_Controller{
    
    function __construct(){
        parent::__construct();

        $this->load->model('empresa_model', 'empresamodel');
        $this->load->model('ferias_model', 'feriasmodel');
        $this->load->model('usuario_model', 'usermodel');
        $this->load->model('LogsSistema_model', 'logsistema');
    }

    public function index(){

        if ((isset($_SESSION['logado'])) && ($_SESSION["logado"] == true)) {
            
            $dados["pagina"]    = "Férias Funcionário";
            $dados["pg"]        = "empresa";
            $dados["submenu"]   = "ausencia";
            $dados["sub"]       = "ausencialist";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["listagem_ferias"] = $this->feriasmodel->listar_ferias($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/ferias');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            
            redirect("/");

        }
        
    }

    public function cadastro(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
            
            $dados["pagina"]    = "Férias Funcionário";
            $dados["pg"]        = "empresa";
            $dados["submenu"]   = "ausencia";
            $dados["sub"]       = "ausenciacad";

            $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["funcionario_full"]  = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/ferias_cadastrar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            
            redirect("/");

        }
        
    }

    public function cadastrar_ferias(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
            
            $data = new stdClass();

            $dados = array(
                'dia_inicio' => $this->input->post('dia_inicio'),
                'dia_fim' => $this->input->post('dia_fim'),
                'fk_idusuario' => $this->input->post('funcionario'),
                'fk_idempresa' => $_SESSION["idempresa"]
            );

            $nome = $this->logsistema->retorna_usuario($this->input->post('funcionario'));

            if($this->feriasmodel->cadastrar_ferias($dados)){

                $data->success = "Férias do dia ". converte_data($this->input->post('dia_inicio')) ." cadastrada com sucesso!";

                $dados["pagina"]  = "Férias Funcionário";
                $dados["pg"]      = "configuracoes";
                $dados["submenu"] = "ausencia";
                $dados["sub"]     = "ausenciacad";

                $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["funcionario_full"]  = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/ferias_cadastrar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //Log de sistema
                $mensagem = "Cadastrou as férias do funcionário ".$nome;
                $log = array(
                    'usuario' => $_SESSION["idusuario"],
                    'mensagem' => $mensagem,
                    'data_hora' => date('Y-m-d H:i:s')
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log sistema


            } else{

                $data->error = "Ocorreu um problema ao cadastrar os dados! Favor entre em contato com o suporte e tente novamente mais tarde.";

                $dados["pagina"]  = "Férias Funcionário";
                $dados["pg"]      = "configuracoes";
                $dados["submenu"] = "ausencia";
                $dados["sub"]     = "ausenciacad";

                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["funcionario_full"]  = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/ferias_cadastrar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
            }

        } else {
           
            redirect("/");

        }
        
    }

    public function editar_ferias_pagina(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"])) {
            
            $dados["pagina"]    = "Férias Funcionário";
            $dados["pg"]        = "configuracoes";
            $dados["submenu"]   = "ausencia";

            $idferias = $this->input->post('idferias');

            $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["dados_ferias"]      = $this->feriasmodel->dados_ferias($idferias);
            $dados["funcionario_full"]  = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/ferias_editar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            redirect("/");
        }
    
    }

    public function editar_ferias(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
            
            $idferias = $this->input->post('idferias');

            $data = new stdClass();
            
            $dados = array(
                'dia_inicio'    => $this->input->post('dia_inicio'),
                'dia_fim'       => $this->input->post('dia_fim'),
                'fk_idusuario'  => $this->input->post('funcionario')
            );

            $nome = $this->logsistema->retorna_usuario($this->input->post('funcionario'));

            if ($this->feriasmodel->editar_ferias($dados, $idferias)) {
                
                $data->success = "Alteração realizada com sucesso!";

                $dados["pagina"] = "Férias Funcionário!";
                $dados["pg"] = "empresa";
                $dados["submenu"] = "ausencia";

                $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["dados_ferias"] = $this->feriasmodel->dados_ferias($idferias);
                $dados["funcionario_full"] = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/ferias_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //Log do sistema
                $mensagem = "Edição das férias do funcionário ".$nome;
                $log = array(
                    'usuario'   => $_SESSION["idusuario"],
                    'mensagem'  => $mensagem,
                    'data_hora' => date("Y-m-d H:i:s")
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log sistema

            } else {

                $data->error = "Ocorreu um problema ao editar as informações! Favor entre em contato com o suporte e tente novamente mais tarde.";

                $dados["pagina"] = "Férias Funcionário!";
                $dados["pg"] = "empresa";
                $dados["submenu"] = "ausencia";

                $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["dados_ferias"] = $this->feriasmodel->dados_ferias($idferias);
                $dados["funcionario_full"] = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/ferias_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
                
            }

        } else {
            redirect("/");
        }  

    }

    public function excluir_ferias(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {

            $data = new stdClass();
            
            $idferias = $this->input->post('idferias');

            $nome = $this->logsistema->nome_usuario('tbferias_func', $idferias);

            if ($this->feriasmodel->excluir_ferias($idferias)) {
                
                $data->success = "Dados excluidos com sucesso!";

                $dados["pagina"]    = "Férias Funcionário";
                $dados["pg"]        = "configuracoes";
                $dados["submenu"]   = "ausencia";
                $dados["sub"]       = "ausencialist";

                $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["listagem_ferias"] = $this->feriasmodel->listar_ferias($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/ferias');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //Log do sistema
                $mensagem = "Excluiu as férias do funcionário ".$nome;
                $log = array(
                    'usuario' => $_SESSION["idusuario"],
                    'mensagem' => $mensagem,
                    'data_hora' => date('Y-m-d H:i:s')
                );
                $this->logsistema->cadastrar_log_sistema($log);

            } else {

                $data->error = "Ocorreu um erro ao excluir os dados! Favor entre em contato com o suporte e tente mais tarde novamente";

                $dados["pagina"]   = "Férias Funcionário";
                $dados["pg"]       = "configuracoes";
                $dados["submenu"]  = "ausencia";
                $dados["sub"]      = "ausencialist";

                $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["listagem_ferias"] = $this->feriasmodel->listar_ferias($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/ferias');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            }
            
        } else {
            
            redirect("/");

        }
        
    }
}
