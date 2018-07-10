<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grupos extends CI_Controller {

    
    public function __construct(){
        parent::__construct();

        $this->load->model('empresa_model', 'empresamodel');
        $this->load->model('grupo_model', 'grupomodel');
        $this->load->model('LogsSistema_model', 'logsistema');
    }

    public function index(){
        
        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
            
            $dados["pagina"]    = "Grupos de Documentos";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "grupo";
            $dados["sub"]       = "grupolist";

            $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["listagem_grupos"]   = $this->grupomodel->listar_grupos($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/grupos');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');
        } else {

            redirect('/');
        }
        
    }

    public function cadastro(){

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $dados["pagina"]    = "Grupos de Documentos";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "grupo";
            $dados["sub"]       = "grupocad";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["listagem_grupos"] = $this->grupomodel->listar_grupos($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/grupos_cadastrar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');
        } else {

            redirect('/');
        }
    }

    public function cadastrar_grupo(){

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $data = new stdClass();

            $dados = array(
                'titulo'        => $this->input->post('titulo'),
                'fk_idempresa'  => $_SESSION["idempresa"]
            );

            if ($this->grupomodel->cadastrar_grupo($dados)) {
            
                $data->success = "Grupo ".$this->input->post('titulo'). " cadastrado com sucesso!";

                $dados["pagina"]    = "Grupos de Documentos";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "grupo";
                $dados["sub"]       = "grupocad";

                $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/grupos_cadastrar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //log do sistema
                $mensagem = "Cadastrou o grupo " . $this->input->post('titulo');
                $log = array(
                    'usuario'   => $_SESSION["idusuario"], 
                    'mensagem'  => $mensagem, 
                    'data_hora' => date('Y-m-d H:i:s')
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log do sistema

            } else {

                $data->error = "Ocorreu um problema ao cadastrar as informações! Favor entre em contato com o suporte e tente novamente mais tarde!";

                $dados["pagina"]    = "Grupos de Documentos";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "grupo";
                $dados["sub"]       = "grupocad";

                $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/grupos_cadastrar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            }
        } else {

            redirect('/');
        }
    }

    public function editar_grupo_pagina(){

        $id = $this->input->post('idgrupo');

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
            
            $dados["pagina"]    = "Grupos de Documentos";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "grupo";

            $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["dados_grupo"]   = $this->grupomodel->dados_grupo($id);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/grupos_editar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            redirect('/');
        }

    }

    public function editar_grupo(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {

            $data = new stdClass();
            
            $idgrupo = $this->input->post("idgrupo");

            $dados = array(
                'titulo' => $this->input->post('titulo')
            );

            if($this->grupomodel->editar_grupo($dados, $idgrupo)){

                $data->success = "Grupo ".$this->input->post('titulo')." alterado com sucesso!";
                
                $dados["pagina"]    = "Grupos de Documentos";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "grupo";
    
                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["dados_grupo"]   = $this->grupomodel->dados_grupo($idgrupo);
    
                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/grupos_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //log do sistema
                $mensagem = "Edição do grupo ".$this->input->post("titulo");
                $log = array(
                    'usuario'   => $_SESSION["idusuario"], 
                    'mensagem'  => $mensagem, 
                    'data_hora' => date("Y-m-d H:i:s")
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log do sistema

            } else {

                $data->error = "Ocorreu um problema ao alterar os dados! Favor entre em contato com o suporte e tente novamente mais tarde!";

                $dados["pagina"]    = "Grupos de Documentos";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "grupo";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["dados_grupo"]   = $this->grupomodel->dados_grupo($idgrupo);
                
                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/grupos_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            }

        } else {

            redirect("/");

        }
        
    }

    public function excluir_grupo(){

        $id = $this->input->post('idgrupo');

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"]) == true) {
            
            $data = new stdClass();

            $titulo = $this->logsistema->seleciona_por_titulo('tbgrupo', $id);

            if($this->grupomodel->excluir_grupo($id)){

                $data->success = "Grupo excluido com sucesso!";

                $dados["pagina"]    = "Grupos de Documentos";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "grupo";
                $dados["sub"]       = "grupolist";

                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["listagem_grupos"]   = $this->grupomodel->listar_grupos($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/grupos');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //log do sistema
                $mensagem = "Excluiu o grupo " . $titulo;
                $log = array(
                    'usuario'   => $_SESSION["idusuario"],
                    'mensagem'  => $mensagem,
                    'data_hora' => date("Y-m-d H:i:s")
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log sistema

            } else{

                $data->error = "Ocorreu um problema ao excluir dados! Favor entre em contato com o suporte e tente mais tarde novamente!";

                $dados["pagina"]    = "Grupos de Documentos";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "grupo";
                $dados["sub"]       = "grupolist";

                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["listagem_grupos"]   = $this->grupomodel->listar_grupos($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/grupos');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
            }
        } else {

            redirect('/');

        }
    }


}
