<?php
defined("BASEPATH") or exit("No direct script access allowed");

date_default_timezone_set('America/Sao_Paulo');

class Relatorios extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        
        $this->load->model("empresa_model", "empresamodel");
        $this->load->model("documentos_model", "docmodel");
        $this->load->model('etapas_model', 'etapasmodel');
        $this->load->model('erros_model', 'errosmodel');
        $this->load->model('usuario_model', 'usermodel');
        $this->load->model('competencia_model', 'compmodel');
        $this->load->model('timer_model', 'timermodel');
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
        $dados["submenu"] = "com_erro";

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

    public function documentos_pendentes(){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $dados["pagina"]    = "Documentos Pendentes";
        $dados["pg"]        = "documentos";
        $dados["submenu"]   = "pendente";

        $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["doc_pendentes"] = $this->docmodel->listar_documentos_pendente($_SESSION["idempresa"]);

        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('documentos/documentos_pendentes');
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

        $recebido = $this->etapasmodel->listar_etapa_ordem($idprotocolo);

        if($this->docmodel->editar_documentos_log($idprotocolo)){
            
            $documento = $this->docmodel->documento_id($idprotocolo);

            //$usuario = $this->compmodel->competencia_user($documento, $recebido);

            $verificarDataAusencia = date("Y-m-d");

            $verificadosUsuariosAptos = $this->compmodel->verifica_usuario_apto($documento, $recebido);

            if($verificadosUsuariosAptos == 0){

                $status = "Pendente";
                
                $retornar1 = array(
                    'descricao'     => "RETORNO SUSPENSÃO", 
                    'data_hora'     => date("Y-m-d H:i:s"),
                    'ultima_etapa'  => "false",
                    'usuario'       => 0,
                    'etapa'         => $recebido,
                    'documento'     => $idprotocolo
                );

                $this->docmodel->cadastrar_log_documento($retornar1);
                
                $dados = array(
                    'fk_iddoccad'   => $idprotocolo,
                    'fk_idetapa'    => $recebido,
                    'action'        => "pause",
                    'timestamp'     => time(),
                    'observacao'    => "SUSPENSO"
                );
        
                $this->timermodel->cadastrar_tempo($dados);

                $pendente = array(
                    'documento'     => $idprotocolo, 
                    'etapa'         => $recebido,
                    'usuario'       => 0,
                    'descricao'     => 'PENDENTE',
                    'data_hora'     => date("Y-m-d H:i:s"),
                    'ultima_etapa'  => 'true'
                );
                

                //echo "documento pendente";
                //print_r($pendente);
                //echo "<br/>";
                $documento_log2 = $this->docmodel->cadastrar_log_documento($pendente);


                $dados = array(
                    'fk_iddoccad'   => $idprotocolo,
                    'fk_idetapa'    => $recebido,
                    'action'        => "start",
                    'timestamp'     => time(),
                    'observacao'    => "PENDENTE"
                );
            
                $this->timermodel->cadastrar_tempo($dados);

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

                    //echo "<br/> contaUsuariosAptos: $contaUsuariosAptos <br/>";
                    
                    if ($contaUsuariosAptos > 1) {

                        $usuarios_documentos = $this->docmodel->documento_por_usuario($usuariosAptosImplode);

                        if($usuarios_documentos > 0){
                            
                            $idEscolhido = $usuarios_documentos;

                        } else {
                            $numeroRandomico = rand(0, $contaUsuariosAptos);

                            $idEscolhido = $usuarios_aptos[$numeroRandomico];
                        }

                    } else {

                        $idEscolhido = $usuarios_aptos[0];

                    }

                    $status = "retorno";

                    $transfereProximaEtapa = array(
                        'descricao'     => "RETORNO SUSPENSÃO", 
                        'data_hora'     => date("Y-m-d H:i:s"),
                        'ultima_etapa'  => "true",
                        'usuario'       => $idEscolhido,
                        'etapa'         => $recebido,
                        'documento'     => $idprotocolo
                    );

                    $mensagem = "retornado";
                
                    $dados = array(
                        'fk_iddoccad'   => $idprotocolo,
                        'fk_idetapa'    => $recebido,
                        'action'        => "pause",
                        'timestamp'     => time(),
                        'observacao'    => "SUSPENSO"
                    );
            
                    $this->timermodel->cadastrar_tempo($dados);

                    //echo "documento recebido 1";
                    //print_r($transfereProximaEtapa);
                    //echo "<br/>";
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

                    $status = "retorno";

                    $transfereProximaEtapa = array(
                        'descricao'     => "RETORNO SUSPENSÃO", 
                        'data_hora'     => date("Y-m-d H:i:s"),
                        'ultima_etapa'  => "true",
                        'usuario'       => $idEscolhidoPrimeiraEtapa,
                        'etapa'         => $recebido,
                        'documento'     => $idprotocolo
                    );

                    $mensagem = "retornado";
                
                    $dados = array(
                        'fk_iddoccad'   => $idprotocolo,
                        'fk_idetapa'    => $recebido,
                        'action'        => "pause",
                        'timestamp'     => time(),
                        'observacao'    => "SUSPENSO"
                    );
            
                    $this->timermodel->cadastrar_tempo($dados);

                    //echo "documento recebido 2";
                    //print_r($transfereProximaEtapa);
                    //echo "<br/>";
                    $documento_log2 = $this->docmodel->cadastrar_log_documento($transfereProximaEtapa);

                    $idMostraDirecionamento = $idEscolhidoPrimeiraEtapa;

                }

            }
            
            if ($documento_log2) {

                /**
                * Envio de email
                */
               $this->load->model('email_model', 'emailmodel');

               $dados = $this->docmodel->dados_documento_cad($idprotocolo);
               $usuario = $this->docmodel->retorna_email_usuario($idprotocolo);
           
               if ($usuario) {
                   
                   foreach ($dados as $doc) {
                   
                       $enviar = array(
                           'tipo'      => 'retorno_suspensao',
                           'protocolo' => $doc->protocolo,
                           'documento' => $doc->documento_nome,
                           'email'     => $usuario->email_usuario,
                           'usuario'   => $usuario->usuario_nome,
                           'status'    => $status
                       );
                       
                    }

                } else {

                    $responsavel = $this->docmodel->retorna_email_responsavel($idprotocolo);

                    foreach ($dados as $doc) {
                    
                        $enviar = array(
                            'tipo'      => 'retorno_suspensao',
                            'protocolo' => $doc->protocolo,
                            'documento' => $doc->documento_nome,
                            'email'     => $responsavel->email_usuario,
                            'usuario'   => $responsavel->usuario_nome,
                            'status'    => $status
                        );
                        
                    }

                }

                $this->emailmodel->enviar_email($enviar);

                /**
                    * Fim do envio de email
                    */

                $this->session->set_flashdata('success', 'Documento com exigência concluída!');
                redirect("suspenso");

            } else {

                $this->session->set_flashdata('error', 'Ocorreu um problema ao transferir o documento! Favor entre em contato com o suporte e tente novamente mais tarde.');
                redirect("suspenso");
                
            }
        
        } else {

            $this->session->set_flashdata('error', 'Ocorreu um problema ao transferir o documento! Favor entre em contato com o suporte e tente novamente mais tarde.');
            redirect("suspenso");

        }


    }

    public function transfere_para(){
        
        if((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)){
            redirect("/");
        }

        $data = new stdClass();

        $idprotocolo = $this->input->post("idprotocolo");
        $usuario = $this->input->post('usuario');

        $etapa = $this->docmodel->etapa_documento($idprotocolo);

        $documento = array(
            'descricao'     => "TRANSFERIDO MANUALMENTE",
            'data_hora'     => date('Y-m-d H:i:s'),
            'ultima_etapa'  => "true",
            'usuario'       => $usuario,
            'etapa'         => $etapa,
            'documento'     => $idprotocolo
        );

        $this->docmodel->editar_documentos_log($idprotocolo);

        if($this->docmodel->cadastrar_log_documento($documento)){

            $this->load->model('timer_model', 'timermodel');

            $dados = array(
                'fk_iddoccad'   => $idprotocolo,
                'fk_idetapa'    => $etapa,
                'action'        => "pause",
                'timestamp'     => time(),
                'observacao'    => "PENDENTE"
            );
    
            $this->timermodel->cadastrar_tempo($dados);

            /**
             * Envio de email
             */
            $this->load->model('email_model', 'emailmodel');

            $dados = $this->docmodel->dados_documento_cad($idprotocolo);
            $usuario = $this->docmodel->retorna_email_usuario($idprotocolo);


            foreach ($dados as $doc) {
                
                $enviar = array(
                    'tipo'      => 'novo',
                    'protocolo' => $doc->protocolo,
                    'documento' => $doc->documento_nome,
                    'email'     => $usuario->email_usuario,
                    'usuario'   => $usuario->usuario_nome
                );
                
            }
            $this->emailmodel->enviar_email($enviar);

            /**
             * Fim do envio de email
             */

            $data->success = "Documento transferido com sucesso";

            $dados["pagina"]    = "Documentos Pendentes";
            $dados["pg"]        = "documentos";
            $dados["submenu"]   = "pendente";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["doc_pendentes"] = $this->docmodel->listar_documentos_pendente($_SESSION["idempresa"]);

            $this->load->view("template/html_header", $dados);
            $this->load->view("template/header");
            $this->load->view("template/menu", $data);
            $this->load->view("documentos/documentos_pendentes");
            $this->load->view("template/footer");
            $this->load->view("template/html_footer");

        } else {

            $data->error = "Ocorreu um problema ao transferir o documento. Favor entre em contato com o suporte e tente novamente mais tarde";

            $dados["pagina"]  = "Documentos Pendentes";
            $dados["pg"]      = "documentos";
            $dados["submenu"] = "pendente";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["doc_pendentes"] = $this->docmodel->listar_documentos_pendente($_SESSION["idempresa"]);

            $this->load->view("template/html_header", $dados);
            $this->load->view("template/header");
            $this->load->view("template/menu", $data);
            $this->load->view("documentos/documentos_pendentes");
            $this->load->view("template/footer");
            $this->load->view("template/html_footer");

        }

    }

    public function tranferencia_documento(){
    
        echo $this->usermodel->usuarios_disponiveis($_SESSION["idempresa"]);

    }

}
