<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Imprimir extends CI_Controller {
    
    public function __construct(){
        parent::__construct();

        $this->load->model("documentos_model", "docmodel");
        $this->load->model("etapas_model", "etapasmodel");
        $this->load->model("DocEtapas_model", "docetapamodel");
    }

    public function imprimir_finalizados($id){
        
        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $dados_documento = $this->docmodel->historico_documento($id);

        $dados["informacoes_documento"] = json_decode($dados_documento);
        $dados["etapas_documento"]      = json_decode($this->docmodel->historico_documentos_dados($id));
        $dados["id_documento"]          = $this->docmodel->documento_id($id);

        $this->load->view('relatorios/imprimir/relatorios_finalizados', $dados);

    }
}
