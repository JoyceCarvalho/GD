<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Relatorios extends CI_Controller {
    
    public function __construct(){
        parent::__construct();

        $this->load->model('documentos_model', 'docmodel');
        $this->load->model('erros_model', 'errosmodel');
        $this->load->model('empresa_model', 'empresamodel');
    }

    public function finalizados(){
        
        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $dados["pagina"]  = "Documentos Finalizados";
        $dados["pg"]      = "relatorio";
        $dados["submenu"] = "finalizado";

        $dados["nome_empresa"]    = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["doc_finalizados"] = $this->docmodel->listar_documentos_finalizados($_SESSION["idempresa"]);

        $this->load->view("template/html_header", $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('relatorios/finalizados');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }

    public function tempo_medio(){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true )) {
            redirect("/");
        }

        $dados["pagina"]  = "Relatório de Tempo Médio";
        $dados["pg"]      = "relatorio";
        $dados["submenu"] = "tempo";

        $dados["nome_empresa"]    = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["doc_finalizados"] = $this->empresamodel->listar_documentos_finalizados($_SESSION["idempresa"]);
        
        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('template/tempo_medio');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }
}
