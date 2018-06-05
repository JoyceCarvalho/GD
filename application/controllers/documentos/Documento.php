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
        $this->load->model('usuario_model', 'usermodel');   
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

                //$usuario = $this->compmodel->competencia_user($documento, $recebido);

                $verificarDataAusencia = date("Y-m-d");

                $verificadosUsuariosAptos = $this->compmodel->verifica_usuario_apto($documento, $recebido);

                if($verificadosUsuariosAptos == 0){

                    $pendente = array(
                        'documento'     => $iddocumento, 
                        'etapa'         => $recebido,
                        'usuario'       => null,
                        'descricao'     => 'PENDENTE',
                        'data_hora'     => date("Y-m-d H:i:s"),
                        'ultima_etapa'  => 'true'
                    );

                    $documento_log2 = $this->docmodel->cadastrar_log_documento($pendente);

                } else {

                    $usuariosAptos = $this->compmodel->usuario_apto($documento, $recebido, $verificarDataAusencia);

                    foreach ($usuariosAptos as $usuarios ) {
                        
                        if ($usuarios->tipo == "funcionario") {
                            
                            $usuarios_aptos[] = $usuarios->fk_idusuario;
                            $usuariosAptosQuantidade[$usuarios->fk_idusuario] = 0;

                        } else {
                            
                            $usuariosAptosCargo = $this->usermodel->usuario_por_cargo($usuarios->fk_idcargo, $verificarDataAusencia);

                            foreach ($usuariosAptosCargo as $user ) {
                                
                                $usuarios_aptos[] = $user->id;
                                $usuariosAptosQuantidade[$user->id] = 0;
                                
                            }

                        }
                        

                    }     

                    $usuariosAptosImplode = implode(",", $usuarios_aptos);

                    $contaUsuariosAptos = count($usuarios_aptos);

                    $verificaNumeroDocumentos = $this->docmodel->numero_documentos($usuariosAptosImplode);

                    if ($verificaNumeroDocumentos == 0) {
                        
                        if ($contaUsuariosAptos > 1) {

                            $usuarios_documentos = $this->docmodel->documento_por_usuario($usuariosAptosImplode);
                            $idEscolhido = $usuarios_documentos;                            
                            //$numeroRandomico = rand(0, $contaUsuariosAptos);
                            //$idEscolhido = $usuarios_aptos[$numeroRandomico];

                        } else {

                            $idEscolhido = $usuarios_aptos[0];

                        }

                        $transfereProximaEtapa = array(
                            'descricao' => 'RECEBIDO',
                            'data_hora' => date("Y-m-d H:i:s"),
                            'ultima_etapa' => 'true',
                            'usuario' => $idEscolhido,
                            'etapa' => $recebido,
                            'documento' => $iddocumento
                        );

                        $documento_log2 = $this->docmodel->cadastrar_log_documento($transfereProximaEtapa);

                        $idMostraDirecionamento = $idEscolhido;
                    } else {

                        $usuarios_quantidada_documento = $this->docmodel->quantidade_documentos_usuario($usuariosAptosImplode);

                        foreach ($usuarios_quantidada_documento as $usuariosDocumento ) {
                            
                            $usuariosAptosQuantidade[$usuariosDocumento->usuario] = $usuariosDocumento->quantidade_documento;

                        }

                        asort($usuariosAptosQuantidade);

                        $controlaMenor = 1;
                        foreach ($usuariosAptosQuantidade as $key => $quantidade) {
                            if ($controlaMenor == 1) {
                                
                                $quantidadeVerificar = $quantidade;
                                $usuariosAptosPrimeiraEtapa[] = $key;

                            } else {

                                if ($quantidadeVerificar = $quantidade) {
                                    $usuariosAptosPrimeiraEtapa[] = $key;
                                }

                            }

                            $controlaMenor ++;

                        }

                        $contaUsuarioAptosPrimeiraEtapa = count($usuariosAptosPrimeiraEtapa);
                        
                        $numeroRandomicoPrimeiraEtapa = rand(0,$contaUsuarioAptosPrimeiraEtapa - 1);
                        
                        $idEscolhidoPrimeiraEtapa = $usuariosAptosPrimeiraEtapa[$numeroRandomicoPrimeiraEtapa];

                        $transfereProximaEtapa = array(
                            'descricao' => 'RECEBIDO', 
                            'data_hora' => date("Y-m-d H:i:s"),
                            'ultima_etapa' => 'true',
                            'usuario' => $idEscolhidoPrimeiraEtapa,
                            'etapa' => $recebido,
                            'documento' => $iddocumento
                        );

                        $documento_log2 = $this->docmodel->cadastrar_log_documento($transfereProximaEtapa);

                        $idMostraDirecionamento = $idEscolhidoPrimeiraEtapa;

                    }

                }
                
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

    
    public function editar_documento($identificador){

        if ((!isset($_SESSION["logado"])) and ($_SESSION["logado"] != true)) {
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

        $iddocumento = $this->docmodel->documento_id($idprotocolo);

        $dados["pagina"] = "Novo Documento";
        $dados["pg"] = "documentos";
        $dados["submenu"] = "novodoc";

        $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["grupo_dados"] = $this->grupomodel->listar_grupos($_SESSION["idempresa"]);;
        $dados["dados_documento"] = $this->docmodel->dados_documento_cad($idprotocolo);

        $this->load->view("template/html_header", $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('documentos/editar_documento');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }

    public function editar_novo_documento(){

        if((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)){
            redirect("/");
        }
        
        $data = new stdClass();

        $idprotocolo = $this->input->post("idprotocolo");

        $dados = array(
            'status'         => 'Modificado', 
            'fk_iddocumento' => $this->input->post('documento')
        );

        if ($this->docmodel->editar_novo_documento($idprotocolo, $dados)) {
            
            if($this->etapasmodel->exclui_prazoetapa($idprotocolo)){

                $steps = $this->input->post("prazo_etapa");
    
                if (isset($steps)) {
    
                    for ($i=1; $i <= $steps; ++$i) { 
                        //echo "<br/>".$i."<br/>";
                        $etapas = array(
                            'prazo'           => $this->input->post("prazo[$i]"),
                            'fk_idetapas'     => $this->input->post("etapas[$i]"),
                            'fk_iddocumento'  => $idprotocolo
                        );
    
                        $prazos = $this->etapasmodel->cadastrar_etapas_prazos($etapas);
    
                    }
    
                }

                $data->success = "Documento atualizado com sucesso!";
        
                $dados["pagina"]    = "Novo Documento";
                $dados["pg"]        = "documentos";
                $dados["submenu"]   = "novodoc";

                $dados["nome_empresa"]    = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["grupo_dados"]     = $this->grupomodel->listar_grupos($_SESSION["idempresa"]);;
                $dados["dados_documento"] = $this->docmodel->dados_documento_cad($idprotocolo);

                $this->load->view("template/html_header", $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('documentos/meus_documentos');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
    
            } else {

                $data->success = "Ocorreu um problema ao editar o documento. Favor entre em contato com o suporte e tente novamente mais tarde!";
    
                $dados["pagina"]  = "Novo Documento";
                $dados["pg"]      = "documentos";
                $dados["submenu"] = "novodoc";

                $dados["nome_empresa"]    = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["grupo_dados"]     = $this->grupomodel->listar_grupos($_SESSION["idempresa"]);;
                $dados["dados_documento"] = $this->docmodel->dados_documento_cad($idprotocolo);

                $this->load->view("template/html_header", $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('documentos/meus_documentos');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            }
            
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

    public function meus_documentos_msg($mensagem){
        
        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $data = new stdClass();

            if ($mensagem != "error") {
                $data->success = "Documento ".$mensagem." com sucesso!";
            } else {
                $data->error = "Ocorreu um problema ao transferir o documento. Favor entre em contato com o suporte e tente novamente mais tarde.";
            }

            $dados["pagina"]    = "Meus Documentos";
            $dados["pg"]        = "documentos";
            $dados["submenu"]   = "meusdocs";

            $dados["nome_empresa"]       = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["documentos_cargo"]   = $this->docmodel->listar_meus_documentos_cargos($_SESSION["idusuario"]);
            $dados["documentos_usuario"] = $this->docmodel->listar_meus_documentos_funcionario($_SESSION["idusuario"]);
            

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $data);
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

    public function historico_documento($id){

        echo $this->docmodel->historico_documento($id);

    }

    public function historico($id){

        echo $this->docmodel->historico_documentos_dados($id);

    }
}
