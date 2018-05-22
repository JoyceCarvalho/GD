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
        $this->load->model('competencia_model', 'compmodel');
        $this->load->model('DocEtapas_model', 'docetapamodel');
        $this->load->model('horario_model', 'horasmodel');
    }

    public function index(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
            
            $dados["pagina"] = "Novo Documento";
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
            // Redireciona para o root quando não estiver logado
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

            $recebido = $this->etapasmodel->listar_etapa_ordem($iddocumento);

            $log = array(
                'descricao'     => "CRIADO",
                'data_hora'     => date('Y-m-d H:i:s'),
                'ultima_etapa'  => 'false',
                'usuario'       => $_SESSION["idusuario"],
                'etapa'         => 0,
                'documento'     => $iddocumento
            );

            $documento_log = $this->docmodel->cadastrar_log_documento($log);

            if ($documento_log) {

                $documento = $this->docmodel->documento_id($iddocumento);

                $usuario = $this->compmodel->competencia_user($documento, $recebido);
                
                $log2 = array(
                    'descricao'     => "RECEBIDO",
                    'data_hora'     => date('Y-m-d H:i:s'),
                    'ultima_etapa'  => 'true',
                    'usuario'       => $usuario,
                    'etapa'         => $recebido,
                    'documento'     => $iddocumento
                );
    
                $documento_log2 = $this->docmodel->cadastrar_log_documento($log2);
                
                if($documento_log2){

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
            // Redireciona para o root quando não estiver logado
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
            // Redireciona para o root quando não estiver logado
            redirect("/");
        }

    }

    public function busca_documentos($value){

        echo $this->docmodel->listar_documentos_json($value);

    }

    public function busca_etapas($value){

        echo $this->etapasmodel->listar_etapas_json($value);

    }

    public function get_time(){
        
        $idprotocolo = $this->input->post("pro");

        $etapa_documento = $this->docmodel->etapa_documento($idprotocolo);

        $this->load->model('timer_model', 'timermodel');
        $timer = $this->timermodel->get_timer($idprotocolo, $etapa_documento);

        $seconds = 0;
        $action = 'pause'; // sempre inicia pausado

        foreach ($timer as $t ) {
            
            $action = $t->action;
            switch ($action) {
                case 'start':
                    $seconds -= $t->timestamp;
                    break;
                case 'pause':
                    // para evitar erro se a primeira ação for pause
                    if ($seconds !== 0) {
                        $seconds += $t->timestamp;
                    }
                    break;
            }
        }
        if ($action === 'start') {
            $seconds += time();
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'seconds' => $seconds,
            'running' => $action === 'start',
        ));
    }

    public function grava_acao(){
        
        $idprotocolo = $this->input->post("pro");

        $etapa_documento = $this->docmodel->etapa_documento($idprotocolo);

        $usuario_documento = $this->docmodel->usuario_documento($idprotocolo);

        if ($usuario_documento == 0) {
            $usuario = $_SESSION["idusuario"];
        } else {
            $usuario = $usuario_documento;
        }

        $this->load->model('timer_model', 'timermodel');

        $timer = $this->timermodel->get_timer($idprotocolo, $etapa_documento);
        $contador = $this->timermodel->contador($idprotocolo, $etapa_documento);
        $ac = $this->timermodel->get_action($idprotocolo, $etapa_documento);

        $newAction = 'start';
        if (($contador > 0) && ($ac == 'start')) {
            $newAction = 'pause';
        }

        $dados = array(
            'fk_iddoccad' => $idprotocolo,
            'fk_idetapa'  => $etapa_documento,
            'action'      => $newAction,
            'timestamp'   => time(),
            'fk_idusuario'  => $usuario
        );

        $this->timermodel->cadastrar_tempo($dados);

        header('Content-Type: application/json');
        echo json_encode(array(
        'running' => $newAction === 'start',
        )); 
    }
}
