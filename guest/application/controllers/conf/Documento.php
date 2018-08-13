<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documento extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->load->model('empresa_model', 'empresamodel');
        $this->load->model('documentos_model', 'docmodel');
        $this->load->model('grupo_model', 'grupomodel');
        $this->load->model('etapas_model', 'etapasmodel');
        $this->load->model('DocEtapas_model', 'docetapamodel');
        $this->load->model('LogsSistema_model', 'logsistema');
    }

	public function index()	{
        
        if((isset($_SESSION["logado"])) && ($_SESSION["logado"])){

            $dados["pagina"]    = "Documentos";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "documento";
            $dados["sub"]       = "doclist";

            $dados["listagem_documento"]    = $this->docmodel->listar_documentos($_SESSION["guest_empresa"]);
            $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/documento');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            
            redirect('/');

        }
    }
    
    public function cadastro(){

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"])){

            $dados["pagina"]    = "Documentos";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "documento";
            $dados["sub"]       = "doccad";

            $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
            $dados["listar_grupos"] = $this->grupomodel->listar_grupos($_SESSION["guest_empresa"]);
            $dados["listar_etapas"] = $this->etapasmodel->listar_etapas($_SESSION["guest_empresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/documento_cadastrar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            
            redirect('/');

        }
    }

    public function cadastrar_documentos(){

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $data = new stdClass();

            $dados = array(
                'titulo' => $this->input->post('titulo'),
                'fk_idgrupo' => $this->input->post('grupo'),
                'fk_idempresa' => $_SESSION["guest_empresa"]
            );

            $iddocumento = $this->docmodel->cadastrar_documentos($dados);

            $etapa = $this->input->post('total');

            for($i=1; $i<=$etapa; $i++){
                $doc_etapa = array(
                    'iddocumento' => $iddocumento,
                    'idetapa'     => $this->input->post("etapa[$i]"),
                    'ordem'       => $i
                );

                $cadastro = $this->docetapamodel->cadastrar_documento_etapa($doc_etapa);
            }

            if($cadastro){

                $data->success = "Documento ".$this->input->post('titulo')." cadastrado com sucesso!";

                $dados["pagina"]    = "Documentos";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "documento";
                $dados["sub"]       = "doccad";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
                $dados["listar_grupos"] = $this->grupomodel->listar_grupos($_SESSION["guest_empresa"]);
                $dados["listar_etapas"] = $this->etapasmodel->listar_etapas($_SESSION["guest_empresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/documento_cadastrar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //log sistema
                $mensagem = "Cadastrou o documento " . $this->input->post('titulo');
                $log = array(
                    'usuario'   => $_SESSION["idusuario"],
                    'mensagem'  => $mensagem,
                    'data_hora' => date("Y-m-d H:i:s")
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log sistema

            } else {

                $data->error = "Ocorreu um problema ao cadastrar o documento! Favor entre em contato com o suporte e tente novamente mais tarde!";

                $dados["pagina"]    = "Documentos";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "documento";
                $dados["doccad"]    = "doccad";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
                $dados["listar_grupos"] = $this->grupomodel->listar_grupos($_SESSION["guest_empresa"]);
                $dados["listar_etapas"] = $this->etapasmodel->listar_etapas($_SESSION["guest_empresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/documento_cadastrar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
            }
        }

    }

    public function editar_documentos_pagina(){

        $id = $this->input->post('iddocumento');

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
            
            $dados["pagina"]    = "Documentos";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "documento";

            $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
            $dados["dados_documento"]   = $this->docmodel->dados_documentos($id);

            $this->load->model('grupo_model', 'grupomodel');
            $dados["full_grupos"]       = $this->grupomodel->listar_grupos($_SESSION["guest_empresa"]);
            $dados["listar_etapas"]       = $this->etapasmodel->listar_etapas($_SESSION["guest_empresa"]);
            $dados["documento_etapa"]   = $this->docetapamodel->listar_docetapa($id);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/documento_editar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            redirect("/");
        }
        
    }

    public function editar_documentos(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
            
            $data = new stdClass();

            $iddocumento = $this->input->post('iddocumentos');

            $dados = array(
                'titulo'        => $this->input->post('titulo'),
                'fk_idgrupo'    => $this->input->post('grupo')
            );

            $total = $this->input->post('total');

            for($i=1; $i<=$total; $i++){

                $idetapa = $this->input->post("etapa[$i]");
                
                $docetapa = array(
                    'iddocumento' => $iddocumento,
                    'idetapa'     => $idetapa,
                    'ordem'       => $i
                );

                if($this->docetapamodel->verifica_docetapa($iddocumento, $idetapa)){

                    $this->docetapamodel->editar_docetapa($iddocumento, $idetapa, $docetapa);

                } else {

                    $this->docetapamodel->cadastrar_documento_etapa($docetapa);

                }

            }

            if($this->docmodel->editar_documentos($dados, $iddocumento)){

                $data->success = "Documento ".$this->input->post('titulo')." atualizado com sucesso!";

                $dados["pagina"]    = "Documentos";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "documento";

                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
                $dados["dados_documento"]   = $this->docmodel->dados_documentos($iddocumento);

                $this->load->model('grupo_model', 'grupomodel');
                $dados["full_grupos"] = $this->grupomodel->listar_grupos($_SESSION["guest_empresa"]);
                $dados["listar_etapas"]       = $this->etapasmodel->listar_etapas($_SESSION["guest_empresa"]);
                $dados["documento_etapa"]   = $this->docetapamodel->listar_docetapa($iddocumento);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/documento_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //log do sistema
                $mensagem = "Edição do documento " . $this->input->post('titulo');
                $log = array(
                    'usuario' => $_SESSION["idusuario"],
                    'mensagem' => $mensagem,
                    'data_hora' => date("Y-m-d H:i:s")
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log sistema

            } else {

                $data->error = "Ocorreu um problema ao editar o documento! Favor entre em contato com o suporte e tente novamente mais tarde.";

                $dados["pagina"]    = "Documentos";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "documento";

                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
                $dados["dados_documento"]   = $this->docmodel->dados_documentos($iddocumento);

                $this->load->model('grupo_model', 'grupomodel');
                $dados["full_grupos"] = $this->grupomodel->listar_grupos($_SESSION["guest_empresa"]);
                $dados["listar_etapas"]       = $this->etapasmodel->listar_etapas($_SESSION["guest_empresa"]);
                $dados["documento_etapa"]   = $this->docetapamodel->listar_docetapa($iddocumento);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/documento_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
            }
        }
    }

    public function excluir_documento(){

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $data = new stdClass();

            $id = $this->input->post('iddocumento');

            $titulo = $this->logsistema->seleciona_por_titulo('tbdocumento', $id);

            if($this->docmodel->excluir_documentos($id)){

                $data->success = "Documento excluido com sucesso!";

                $dados["pagina"]    = "Documentos";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "documento";
                $dados["sub"]       = "doclist";

                $dados["listagem_documento"]    = $this->docmodel->listar_documentos($_SESSION["guest_empresa"]);
                $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/documento');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //log do sistema
                $mensagem = "Excluiu o documento " . $titulo;
                $log = array(
                    'usuario' => $_SESSION["idusuario"],
                    'mensagem' => $mensagem,
                    'data_hora' => date("Y-m-d H:i:s")
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log sistema

            } else {

                $data->error = "Ocorreu um problema ao excluir os dados! Favor entre em contato com o suporte e tente novamente mais tarde.";

                $dados["pagina"]    = "Documentos";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "documento";
                $dados["sub"]       = "doclist";

                $dados["listagem_documento"]    = $this->docmodel->listar_documentos($_SESSION["guest_empresa"]);
                $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/documento');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
                
            }
        }
    }

}
