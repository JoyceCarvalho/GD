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
    }

    public function index(){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true )) {
            redirect("/");
        }

        $dados["pagina"]  = "Documentos em Andamento";
        $dados["pg"]      = "documentos";
        $dados["submenu"] = "andamento";

        $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);

        /*if (($_SESSION["is_admin"] == true) or ($_SESSION["is_coordenador"] == true)) {

            $dados["andamento_doc_c"] = $this->docmodel->listar_documentos_em_andamento($_SESSION["guest_empresa"]);

        } else {

            $dados["andamento_doc_f"] = $this->docmodel->listar_documentos_andamento_cargos($_SESSION["idusuario"]);
            $dados["andamento_doc_c"] = $this->docmodel->listar_documentos_andamento_funcionarios($_SESSION["idusuario"]);
            
        }*/
        $dados["andamento_doc_c"] = $this->docmodel->listar_documentos_em_andamento($_SESSION["idempresa"]);

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

        $dados["nome_empresa"]    = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
        $dados["documentos_erro"] = $this->docmodel->listar_documentos_com_erros($_SESSION["guest_empresa"]);

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

        $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
        $dados["doc_cancelados"] = $this->docmodel->listar_documentos_cancelados($_SESSION["guest_empresa"]);


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

        $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
        $dados["doc_suspensos"] = $this->docmodel->listar_documentos_suspensos($_SESSION["guest_empresa"]);


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

        $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
        $dados["doc_pendentes"] = $this->docmodel->listar_documentos_pendente($_SESSION["guest_empresa"]);

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

        $etapa_atual = $this->docmodel->etapa_documento($idprotocolo);

        $anterior = $this->docmodel->etapa_anterior($idprotocolo, $etapa_atual);

        $usuario_anterior = $anterior->usuario;
        $etapa_anterior = $anterior->etapa;

        if($this->docmodel->editar_documentos_log($idprotocolo)){

            $this->load->model('timer_model', 'timermodel');

            if ($usuario_anterior == 0) {

                $status = "Pendente";
                
                $retornar1 = array(
                    'descricao'     => "RETORNO SUSPENSÃO", 
                    'data_hora'     => date("Y-m-d H:i:s"),
                    'ultima_etapa'  => "false",
                    'usuario'       => 0,
                    'etapa'         => $etapa_anterior,
                    'documento'     => $idprotocolo
                );

                $this->docmodel->cadastrar_log_documento($retornar1);
                
                $dados = array(
                    'fk_iddoccad'   => $idprotocolo,
                    'fk_idetapa'    => $etapa_anterior,
                    'action'        => "pause",
                    'timestamp'     => time(),
                    'observacao'    => "SUSPENSO"
                );
        
                $this->timermodel->cadastrar_tempo($dados);

                $retornar = array(
                    'descricao'     => "PENDENTE", 
                    'data_hora'     => date("Y-m-d H:i:s"),
                    'ultima_etapa'  => "true",
                    'usuario'       => 0,
                    'etapa'         => $etapa_anterior,
                    'documento'     => $idprotocolo
                );
                
                $dados = array(
                    'fk_iddoccad'   => $idprotocolo,
                    'fk_idetapa'    => $etapa_anterior,
                    'action'        => "start",
                    'timestamp'     => time(),
                    'observacao'    => "PENDENTE"
                );
        
                $this->timermodel->cadastrar_tempo($dados);

                $mensagem = "pendente";

            } else {

                $status = "retorno";

                $retornar = array(
                    'descricao'     => "RETORNO SUSPENSÃO", 
                    'data_hora'     => date("Y-m-d H:i:s"),
                    'ultima_etapa'  => "true",
                    'usuario'       => $usuario_anterior,
                    'etapa'         => $etapa_anterior,
                    'documento'     => $idprotocolo
                );

                $mensagem = "retornado";
             
                $dados = array(
                    'fk_iddoccad'   => $idprotocolo,
                    'fk_idetapa'    => $etapa_anterior,
                    'action'        => "pause",
                    'timestamp'     => time(),
                    'observacao'    => "SUSPENSO"
                );
        
                $this->timermodel->cadastrar_tempo($dados);

            }

            if ($this->docmodel->cadastrar_log_documento($retornar)) {

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
                redirect("meusdocumentos");

            } else {

                $this->session->set_flashdata('error', 'Ocorreu um problema ao transferir o documento! Favor entre em contato com o suporte e tente novamente mais tarde.');
                redirect("meusdocumentos");
                
            }

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
                    'etapa'     => $etapa,
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

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
            $dados["doc_pendentes"] = $this->docmodel->listar_documentos_pendente($_SESSION["guest_empresa"]);

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

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
            $dados["doc_pendentes"] = $this->docmodel->listar_documentos_pendente($_SESSION["guest_empresa"]);

            $this->load->view("template/html_header", $dados);
            $this->load->view("template/header");
            $this->load->view("template/menu", $data);
            $this->load->view("documentos/documentos_pendentes");
            $this->load->view("template/footer");
            $this->load->view("template/html_footer");

        }

    }

    public function tranferencia_documento(){
    
        echo $this->usermodel->usuarios_disponiveis($_SESSION["guest_empresa"]);

    }

}
