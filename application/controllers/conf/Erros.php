<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Erros extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        
        $this->load->model('empresa_model', 'empresamodel');
        $this->load->model('erros_model', 'errosmodel');
        $this->load->model('documentos_model', 'docmodel');
        $this->load->model('LogsSistema_model', 'logsistema');
    }

    public function index(){

        if (!(isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }
        
        $dados["pagina"]    = "Erros";
        $dados["pg"]        = "configuracao";
        $dados["submenu"]   = "erro";

        $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["listagem_erros"] = $this->errosmodel->listar_erros($_SESSION["idempresa"]);

        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('config/erros');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }

    public function pagina_cadastro(){

        if (!(isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $dados["pagina"]    = "Erros";
        $dados["pg"]        = "configuracao";
        $dados["submenu"]   = "erro";

        $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["listagem_erros"] = $this->errosmodel->listar_erros($_SESSION["idempresa"]);

        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('config/erros_cadastrar');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }

    public function cadastrar(){

        if(!(isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)){
            redirect("/");
        }

        $data = new stdClass();

        $dados = array(
            "titulo"        => $this->input->post('titulo'),
            "tipo"          => $this->input->post("tipo_erro"),
            "fk_idempresa"  => $_SESSION["idempresa"]
        );

        if ($this->errosmodel->cadastrar_erros($dados)) {
            
            $data->success = "Erro ".$this->input->post('titulo')." cadastrado com sucesso!";

            $dados["pagina"]    = "Erros";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "erro";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["listagem_erros"] = $this->errosmodel->listar_erros($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $data);
            $this->load->view('config/erros_cadastrar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

            //Log do sistema
            $mensagem = "Cadastrou o erro ".$this->input->post('titulo');
            $log = array(
                "usuario"   => $_SESSION['idusuario'],
                "mensagem"  => $mensagem,
                "data_hora" => date("Y-m-d H:i:s")
            );
            $this->logsistema->cadastrar_log_sistema($log);
            //fim log sistema

        } else {
            
            $data->error = "Ocorreu um problema ao cadastrar o erro. Favor entre em contato com o suporte e tente novamente mais tarde!";

            $dados["pagina"]    = "Erros";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "erro";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["listagem_erros"] = $this->errosmodel->listar_erros($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $data);
            $this->load->view('config/erros_cadastrar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        }
        
    }

    public function editar_erro_pagina(){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $id = $this->input->post("iderro");

        $dados["pagina"]    = "Erros";
        $dados["pg"]        = "configuracao";
        $dados["submenu"]   = "erro";

        $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["dados_erro"]    = $this->errosmodel->dados_erro($id);
        
        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('config/erros_editar');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }

    public function editar_erro(){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
            redirect("/");
        }

        $data = new stdClass();

        $id = $this->input->post("iderro");
        
        $dados = array(
            'titulo' => $this->input->post('titulo'), 
            'tipo' => $this->input->post('tipo_erro')
        );

        if($this->errosmodel->editar_erro($id, $dados)){

            $data->success = "Erro ". $this->input->post('titulo')." alterado com sucesso!";

            $dados["pagina"]    = "Erros";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "erro";


            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["dados_erro"]   = $this->errosmodel->dados_erro($id);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $data);
            $this->load->view('config/erros_editar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            $data->error = "Ocorreu um problema ao alterar o erro ".$this->input->post('titulo'). ". Favor entre em contato com o suporte e tente novamente mais tarde.";

            $dados["pagina"]    = "Erros";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "erro";

            $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["dados_erro"]    = $this->errosmodel->dados_erro($id);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $data);
            $this->load->view('config/erros_editar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');
        }

    }

    public function excluir_erro(){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true )) {
            redirect("/");
        }

        $data = new stdClass;

        $id = $this->input->post('iderro');

        if ($this->errosmodel->deleta_erro($id)) {

            $data = "Erro excluido com sucesso!";
            
            $dados["pagina"]    = "Erros";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "erro";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["listagem_erros"] = $this->errosmodel->listar_erros($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $data);
            $this->load->view('config/erros');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            
            $data->error = "Ocorreu um problema ao excluir o erro. Favor entre em contato com o suporte e tente novamente mais tarde";


            $dados["pagina"]    = "Erros";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "erro";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["listagem_erros"] = $this->errosmodel->listar_erros($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $data);
            $this->load->view('config/erros');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        }
        

    }

    public function erro_documento_cad(){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $idprotocolo = $this->input->post("idprotocolo");

        $dados = array(
            'fk_iderros' => $this->input->post("erro"), 
            'fk_iddocumentos' => $this->input->post("idprotocolo"),
            'descricao'       => $this->input->post("descricao"),
            'data_hora'       => date("Y-m-d H:i:s"),
            'fk_idusuario'    => $_SESSION["idusuario"],
            'fk_idetapa'      => $this->input->post("etapa_erro"),
        );

        $this->errosmodel->cadastrar_erros_documento($dados);

        if($this->docmodel->editar_documentos_log($idprotocolo)){

            $anterior = $this->docmodel->retorna_etapa($this->input->post('etapa_erro'), $idprotocolo);

            $usuario_anterior = $anterior->usuario;
            $etapa_anterior = $anterior->etapa;

            $retornar = array(
                'descricao'     => "RETORNO COM ERRO", 
                'data_hora'     => date("Y-m-d H:i:s"),
                'ultima_etapa'  => "true",
                'usuario'       => $usuario_anterior,
                'etapa'         => $etapa_anterior,
                'documento'     => $idprotocolo
            );

            if($this->docmodel->cadastrar_log_documento($retornar)){

                redirect("meus_documentos/erro");
     
            } else {
    
                redirect("meus_documentos/error");
    
            }

        }


    }

    public function visualizar_erros_documento($idprotocolo){

        echo $this->errosmodel->listar_erros_documentos($idprotocolo);

    }

    public function erro_documento(){

        echo $this->errosmodel->listar_erros_json($_SESSION["idempresa"]);

    }

}
