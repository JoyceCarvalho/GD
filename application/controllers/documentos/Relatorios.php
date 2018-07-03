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

        if (($_SESSION["is_admin"] == true) or ($_SESSION["is_coordenador"] == true)) {

            $dados["andamento_doc_c"] = $this->docmodel->listar_documentos_em_andamento($_SESSION["idempresa"]);

        } else {

            $dados["andamento_doc_f"] = $this->docmodel->listar_documentos_andamento_cargos($_SESSION["idusuario"]);
            $dados["andamento_doc_c"] = $this->docmodel->listar_documentos_andamento_funcionarios($_SESSION["idusuario"]);
            
        }

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

    public function documentos_cancelados(){
        
        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true )) {
            redirect("/");
        }

        $dados["pagina"]  = "Documentos Cancelados";
        $dados["pg"]      = "documentos";
        $dados["submenu"] = "cancelados";

        $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["doc_cancelados"] = $this->docmodel->listar_documentos_cancelados($_SESSION["idempresa"]);


        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('documentos/documentos_cancelados');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');
    }

    public function documentos_suspensos(){

        if((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)){
            redirect("/");
        }

        $dados["pagina"]  = "Documentos Suspenso";
        $dados["pg"]      = "documentos";
        $dados["submenu"] = "suspensos";

        $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["doc_suspensos"] = $this->docmodel->listar_documentos_suspensos($_SESSION["idempresa"]);


        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('documentos/documentos_suspensos');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');
    }

    public function reverte_suspensao($identificador){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        //transforma o identificador em um array
        $id = str_split($identificador);

        //pega o valor total do array (quantidade de caracteres)
        $tamanho = count($id);

        $protocolo = "";

        for ($i=32; $i < $tamanho ; $i++) { 
            $protocolo .= $id[$i];
        }

        //transforma a string em inteiro
        $idprotocolo = (int)$protocolo;

        $etapa_atual = $this->docmodel->etapa_documento($idprotocolo);

        $anterior = $this->docmodel->etapa_anterior($idprotocolo, $etapa_atual);

        $usuario_anterior = $anterior->usuario;
        $etapa_anterior = $anterior->etapa;

        if($this->docmodel->editar_documentos_log($idprotocolo)){

            $retornar = array(
                'descricao'     => "RETORNO SUSPENSÃO", 
                'data_hora'     => date("Y-m-d H:i:s"),
                'ultima_etapa'  => "true",
                'usuario'       => $usuario_anterior,
                'etapa'         => $etapa_anterior,
                'documento'     => $idprotocolo
            );

            if ($this->docmodel->cadastrar_log_documento($retornar)) {
                
                $mensagem = "retornado";

                redirect("meus_documentos/".$mensagem);

            } else {

                $mensagem = "error";

                redirect("meus_documentos/".$mensagem);
                
            }

        }

    }

}
