<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Andamento extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        
        $this->load->model("empresa_model", "empresamodel");
    }

    public function index(){

        $dados["pagina"]  = "Documentos em Andamento";
        $dados["pg"]      = "documentos";
        $dados["submenu"] = "andamento";

        $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

        $this->load->view("template/html_header", $dados);
        $this->load->view("template/header");
        $this->load->view("template/menu");
        $this->load->view("documentos/documentos_andamento");
        $this->load->view("template/footer");
        $this->load->view('template/html_footer');

    }

}
