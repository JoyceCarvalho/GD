<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ausencia extends CI_Controller{
    
    function __construct(){
        parent::__construct();

        $this->load->model('empresa_model', 'empresamodel');
        $this->load->model('ausencia_model', 'ausenciamodel');
        $this->load->model('usuario_model', 'usermodel');
        $this->load->model('LogsSistema_model', 'logsistema');
    }

    public function index(){

        if ((isset($_SESSION['logado'])) && ($_SESSION["logado"] == true)) {
            
            $dados["pagina"]    = "Ausência Funcionário";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "ausencia";
            $dados["sub"]       = "ausencialist";

            $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["listagem_ausencia"] = $this->ausenciamodel->listar_ausencia($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/ausencia');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            redirect('/');

        }

    }

    public function cadastro(){

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $dados["pagina"]    = "Ausência Funcionário";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "ausencia";
            $dados["sub"]       = "ausenciacad";

            $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["funcionario_full"]  = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);
            
            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/ausencia_cadastrar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            redirect('/');

        }

    }

    public function cadastrar_ausencia(){
        
        if(isset($_SESSION["logado"]) and ($_SESSION["logado"] == true)){

            $data = new stdClass();

            $dados = array(
                'dia_inicio'    => $this->input->post('dia_inicio'),
                'dia_fim'       => $this->input->post('dia_fim'),
                'motivo'        => $this->input->post('motivo'),
                'fk_idusuario'  => $this->input->post('funcionario'),
                'fk_idempresa'  => $_SESSION["idempresa"]
            );

            $nome = $this->logsistema->retorna_usuario($this->input->post('funcionario'));

            if($this->ausenciamodel->cadastrar_ausencia($dados)){

                $data->success = "Ausência do dia ".$this->input->post('dia_inicio')." cadastrada com sucesso!";

                $dados["pagina"]    = "Ausência Funcionário";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "ausencia";
                $dados["sub"]       = "ausenciacad";

                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["funcionario_full"]  = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/ausencia_cadastrar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //Log do sistema
                $mensagem = "Cadastrou a ausência do funcionario ".$nome;
                $log = array(
                    'usuario' => $_SESSION["idusuario"],
                    'mensagem' => $mensagem,
                    'data_hora' => date("Y-m-d H:i:s")
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log sistema

            } else{

                $data->error = "Ocorreu um problema ao cadastrar ausência de funcionário! Favor entre em contato com o suporte e tente novamente mais tarde!";

                $dados["pagina"]    = "Ausência Funcionário";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "ausencia";
                $dados["sub"]       = "ausenciacad";

                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["funcionario_full"]  = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/ausencia_cadastrar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
            }

        } else {

            redirect('/');

        }

    }

    public function editar_ausencia_pagina(){

        if((isset($_SESSION["logado"])) and ($_SESSION["logado"] == true)){

            $idausencia = $this->input->post('idausencia');

            $dados["pagina"]    = "Ausência Funcionário";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "ausencia";

            $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["dados_ausencia"]    = $this->ausenciamodel->dados_ausencia($idausencia);
            $dados["funcionario_full"]  = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/ausencia_editar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            redirect("/");

        }
    }

    public function editar_ausencia(){

        if((isset($_SESSION["logado"])) and ($_SESSION["logado"] == true)){

            $data = new stdClass();

            $idausencia = $this->input->post('idausencia');

            $dados = array(
                'dia_inicio'    => $this->input->post('dia_inicio'),
                'dia_fim'       => $this->input->post('dia_fim'),
                'motivo'       => $this->input->post('motivo'),
                'fk_idusuario'  => $this->input->post('funcionario')
            );

            $nome = $this->logsistema->retorna_usuario($this->input->post('funcionario'));

            if ($this->ausenciamodel->editar_ausencia($dados, $idausencia)) {
                
                $data->success = "Ausência do funcionário alterado com sucesso!";

                $dados["pagina"]    = "Ausência Funcionário";
                $dados["pg"]        = "configuracoes";
                $dados["submenu"]   = "ausencia";

                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["dados_ausencia"]    = $this->ausenciamodel->dados_ausencia($idausencia);
                $dados["funcionario_full"]  = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/ausencia_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //Log do sistema
                $mensagem = "Edição da ausência do funcionario ".$nome;
                $log = array(
                    'usuario' => $_SESSION["idusuario"],
                    'mensagem' => $mensagem,
                    'data_hora' => date("Y-m-d H:i:s")
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log sistema

            } else {
                
                $data->error = "Ocorreu um problema ao alterar os dados! Favor entre em contato com o suporte e tente novamente mais tarde.";

                $dados["pagina"]    = "Ausência Funcionário";
                $dados["pg"]        = "configuracoes";
                $dados["submenu"]   = "ausencia";

                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["dados_ausencia"]    = $this->ausenciamodel->dados_ausencia($idausencia);
                $dados["funcionario_full"]  = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/ausencia_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
            }

        }

    }

    public function excluir_ausencia(){

        if ((isset($_SESSION["logado"])) and ($_SESSION["logado"] == true)) {
            
            $data = new stdClass();

            $idausencia = $this->input->post('idausencia');

            $nome = $this->logsistema->nome_usuario('tbausencia', $idausencia);

            if ($this->ausenciamodel->excluir_ausencia($idausencia)) {
                
                $data->success = "Dados excluidos com sucesso!";

                $dados["pagina"]    = "Ausência Funcionário";
                $dados["pg"]        = "configuracoes";
                $dados["submenu"]   = "ausencia";
                $dados["sub"]       = "ausencialist";

                $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["listagem_ausencia"] = $this->ausenciamodel->listar_ausencia($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/ausencia');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //Log do sistema
                $mensagem = "Excluiu ausência do funcionário ".$nome;
                $log = array(
                    'usuario' => $_SESSION["idusuario"],
                    'mensagem' => $mensagem,
                    'data_hora' => date("Y-m-d H:i:s")
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log sistema

            } else {
                
                $data->error = "Ocorreu um problema ao excluir os dados! Favor entre em contato com o suporte e tente novamente mais tarde.";

                $dados["pagina"]    = "Ausência Funcionário";
                $dados["pg"]        = "configuracoes";
                $dados["submenu"]   = "ausencia";
                $dados["sub"]       = "ausencialist";

                $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["listagem_ausencia"] = $this->ausenciamodel->listar_ausencia($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/ausencia');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
            }
            
        }
    }

}
