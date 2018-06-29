<?php
defined('BASEPATH') OR exit('No direct scritp access allowed');

class Etapas extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->model('empresa_model', 'empresamodel');
        $this->load->model('etapas_model', 'etapasmodel');
        $this->load->model('LogsSistema_model', 'logsistema');
    }

    public function index(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"]) == true) {
            
            $dados["pagina"]    = "Etapas";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "etapa";

            $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["listagem_etapa"]    = $this->etapasmodel->listar_etapas($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/etapas');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');
        }

    }

    public function cadastro(){

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $dados["pagina"] = "Etapas";
            $dados["pg"] = "configuracao";
            $dados["submenu"] = "etapa";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/etapas_cadastrar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');
        } else {

            redirect('/');

        }

    }

    public function cadastrar_etapas(){

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $data = new stdClass();

            $dados = array(
                'titulo'        => $this->input->post('titulo'), 
                'ativo'         => 1,
                'fk_idempresa'  => $_SESSION["idempresa"]
            );

            if($this->etapasmodel->cadastrar_etapas($dados)){

                $data->success = "Etapa ".$this->input->post('titulo')." cadastrada com sucesso!";

                $dados["pagina"]    = "Etapas";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "etapa";

                $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/etapas_cadastrar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //log do sistema
                $mensagem = "Cadastrou a etapa ".$this->input->post('titulo');
                $log = array(
                    'usuario' => $_SESSION["idusuario"],
                    'mensagem' => $mensagem,
                    'data_hora' => date('Y-m-d H:i:s')
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log sistema

            } else {

                $data->error = "Ocorreu um problema ao cadastar a etapa! Favor entre em contato com o suporte e tente novamente mais tarde.";

                $dados["pagina"]    = "Etapas";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "etapa";

                $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/etapas_cadastrar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            }

        } else {

            redirect('/');

        }
    }

    public function editar_etapas_pagina(){

        $id = $this->input->post('idetapa');

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $dados["pagina"]    = "Etapas";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "etapa";

            $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["dados_etapa"]   = $this->etapasmodel->dados_etapas($id);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/etapas_editar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            redirect("/");
        }

    }

    public function editar_etapas(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
            
            $data = new stdClass();

            $idetapa = $this->input->post('idetapa');

            $dados = array(
                'titulo' => $this->input->post('titulo')
            );

            if($this->etapasmodel->editar_etapas($dados, $idetapa)){

                $data->success = "Etapa ".$this->input->post('titulo')." alterado com sucesso";

                $dados["pagina"]    = "Etapas";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "etapa";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["dados_etapa"]   = $this->etapasmodel->dados_etapas($idetapa);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/etapas_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //log sistema
                $mensagem = "Edição da etapa ".$this->input->post('titulo');
                $log = array(
                    'usuario'   => $_SESSION["idusuario"],
                    'mensagem'  => $mensagem,
                    'data_hora' => date('Y-m-d H:i:s')
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log sistema

            } else {

                $data->error = "Ocorreu um problema ao alterar as informações! Favor entre em contato com o suporte e tente novamente mais tarde.";

                $dados["pagina"]    = "Etapas";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "etapa";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["dados_etapa"]   = $this->etapasmodel->dados_etapas($idetapa);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/etapas_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
                
            }
        } else {
            redirect('/');
        }
    }

    public function excluir_etapas(){

        $id = $this->input->post('idetapa');

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $data = new stdClass();

            $titulo = $this->logsistema->seleciona_por_titulo('tbetapa', $id);

            if ($this->etapasmodel->excluir_etapas($id)) {
                
                $data->success = "Etapa excluida com sucesso!";

                $dados["pagina"]    = "Etapas";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "etapa";

                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["listagem_etapa"]    = $this->etapasmodel->listar_etapas($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/etapas');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //Log sistema
                $mensagem = "Excluiu a etapa ".$titulo;
                $log = array(
                    'usuario' => $_SESSION["idusuario"],
                    'mensagem' => $mensagem,
                    'data_hora' => date("Y-m-d H:i:s")
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log sistema

            } else {

                $data->error = "Ocorreu um problema ao excluir os dados! Favor entre em contato com o suporte e tente novamente mais tarde.";

                $dados["pagina"]    = "Etapas";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "etapa";

                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["listagem_etapa"]    = $this->etapasmodel->listar_etapas($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu',$data);
                $this->load->view('config/etapas');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
            }

        } else {

            redirect('/');
            
        }

    }

    public function etapas_documento_json($idprotocolo){
        
        $this->load->model("documentos_model", "docmodel");
        $this->load->model("DocEtapas_model", "docetapamodel");

        $iddocumento = $this->docmodel->documento_id($idprotocolo);

        echo $this->docetapamodel->listar_etapa_documento_json($iddocumento);

    }
    
}
