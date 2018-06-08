<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Relatorios extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        
        $this->load->model("empresa_model", "empresamodel");
        $this->load->model("documentos_model", "docmodel");
        $this->load->model('etapas_model', 'etapasmodel');
        $this->load->model('erros_model', 'errosmodel');
    }

    public function index(){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true )) {
            redirect("/");
        }

        $dados["pagina"]  = "Documentos em Andamento";
        $dados["pg"]      = "documentos";
        $dados["submenu"] = "andamento";

        $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["andamento_doc"] = $this->docmodel->listar_documentos_em_andamento($_SESSION["idempresa"]);

        $this->load->view("template/html_header", $dados);
        $this->load->view("template/header");
        $this->load->view("template/menu");
        $this->load->view("documentos/documentos_andamento");
        $this->load->view("template/footer");
        $this->load->view('template/html_footer');

    }

    public function documentos_com_erro(){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $dados["pagina"]  = "Documentos com Erro";
        $dados["pg"]      = "documentos";
        $dados["submenu"] = "comerro";

        $dados["nome_empresa"]    = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["documentos_erro"] = $this->docmodel->listar_documentos_com_erros($_SESSION["idempresa"]);

        $this->load->view("template/html_header", $dados);
        $this->load->view("template/header");
        $this->load->view("template/menu");
        $this->load->view("documentos/documentos_erro");
        $this->load->view("template/footer");
        $this->load->view("template/html_footer");
    }

}
