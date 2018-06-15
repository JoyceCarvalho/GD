<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Imprimir extends CI_Controller {
    
    public function __construct(){
        parent::__construct();

        $this->load->model("documentos_model", "docmodel");
        $this->load->model("etapas_model", "etapasmodel");
        $this->load->model("DocEtapas_model", "docetapamodel");
        $this->load->model("empresa_model", "empresamodel");
    }

    public function imprimir_finalizados($id){
        
        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $informacoes_documento = json_decode($this->docmodel->historico_documento($id));
        
        foreach ($informacoes_documento as $doc ) {

            if ($doc->idempresa == $_SESSION["idempresa"]) {
                
                $dados["informacoes_documento"] = $informacoes_documento;
        
                $dados["etapas_documento"]      = json_decode($this->docmodel->historico_documentos_dados($id));
                $dados["id_documento"]          = $this->docmodel->documento_id($id);
                $dados["erros_documento"]       = $this->docmodel->erros_do_documento($id);
                $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        
                $this->load->view('relatorios/imprimir/relatorios_finalizados', $dados);

            } else {

                $this->load->view('errors/acesso_restrito');

            }
        }

    }

    public function imprimir_tempo_medio($id){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $informacoes_documento = json_decode($this->docmodel->historico_documento($id));

        foreach ($informacoes_documento as $doc) {
            
            if ($doc->idempresa == $_SESSION["idempresa"]) {
                
                $dados["informacoes_documento"] = $informacoes_documento;

            }

        }
    }
}
