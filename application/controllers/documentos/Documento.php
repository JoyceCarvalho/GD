<?php
defined("BASEPATH") OR exit('No direct script access allowed');

date_default_timezone_set('America/Sao_Paulo'); 

class Documento extends CI_Controller {
    
    public function __construct(){
        parent::__construct();

        $this->load->model('empresa_model', 'empresamodel');
        $this->load->model('grupo_model', 'grupomodel');
        $this->load->model('etapas_model', 'etapasmodel');
        $this->load->model('documentos_model', 'docmodel');
    }

    public function index(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
            
            $dados["pagina"] = "Férias Funcionário";
            $dados["pg"] = "documentos";
            $dados["submenu"] = "novodoc";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["grupo_dados"] = $this->grupomodel->listar_grupos($_SESSION["idempresa"]);

            $this->load->view("template/html_header", $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('documentos/novo_documento');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            redirect('/');
        }

    }

    public function cadastrar_novo_documento(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {

            $data = new stdClass();
            
            $dados = array(
                'protocolo'      => $this->input->post('protocolo'),
                'atos'           => $this->input->post('atos'),
                'fk_iddocumento' => $this->input->post('documento'),
                'prazo'          => $this->input->post('prazo_final'),
                'status'         => 'Criado'
            );

            $iddocumento = $this->docmodel->cadastrar_novo_documento($dados);

            $steps_number = $this->input->post('prazo_etapa');

            if(isset($steps_number)){

                for ($i=1; $i <= $steps_number; ++$i) { 
                    //echo "<br/>".$i."<br/>";
                    $etapas = array(
                        'prazo'           => $this->input->post("prazo[$i]"),
                        'fk_idetapas'     => $this->input->post("etapas[$i]"),
                        'fk_iddocumento'  => $iddocumento
                    );

                    $prazos = $this->etapasmodel->cadastrar_etapas_prazos($etapas);

                }

            }

            $log = array(
                'descricao'     => "CRIADO",
                'data_hora'     => date('Y-m-d H:i:s'),
                'ultima_etapa'  => 'false',
                'usuario'       => $_SESSION["idusuario"],
                'etapa'         => 0,
                'documento'     => $iddocumento
            );

            $documento_log = $this->docmodel->cadastrar_log_documento($log);

            if($documento_log){

                $data->success = "Documento de protocolo ".$this->input->post('protocolo')." cadastrado com sucesso!";

                $dados["pagina"]    = "Novo Documento";
                $dados["pg"]        = "documentos";
                $dados["submenu"]   = "novodoc";

                $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["grupo_dados"] = $this->grupomodel->listar_grupos($_SESSION["idempresa"]);

                $this->load->view("template/html_header", $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('documentos/novo_documento');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
            } else {

                $data->error = "Ocorreu um problema ao cadastra os dados! Favor entre em contato com o suporte e tente novamente mais tarde.";

                $dados["pagina"]    = "Novo Documento";
                $dados["pg"]        = "documentos";
                $dados["submenu"]   = "novodoc";

                $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["grupo_dados"] = $this->grupomodel->listar_grupos($_SESSION["idempresa"]);

                $this->load->view("template/html_header", $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('documentos/novo_documento');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
            }

        } else {
            redirect("/");
        }

    }

    public function meus_documentos(){
        
        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $dados["pagina"]    = "Meus Documentos";
            $dados["pg"]        = "documentos";
            $dados["submenu"]   = "meusdocs";

            $dados["nome_empresa"]       = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["documentos_cargo"]   = $this->docmodel->listar_meus_documentos_cargos($_SESSION["idusuario"]);
            $dados["documentos_usuario"] = $this->docmodel->listar_meus_documentos_funcionario($_SESSION["idusuario"]);
            

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('documentos/meus_documentos');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            redirect("/");
        }

    }

    public function busca_documentos($value){

        echo $this->docmodel->listar_documentos_json($value);

    }

    public function busca_etapas($value){

        echo $this->etapasmodel->listar_etapas_json($value);

    }
}
