<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Imprimir extends CI_Controller {
    
    public function __construct(){
        parent::__construct();

        $this->load->model("documentos_model", "docmodel");
        $this->load->model("etapas_model", "etapasmodel");
        $this->load->model("DocEtapas_model", "docetapamodel");
        $this->load->model("empresa_model", "empresamodel");
        $this->load->model("timer_model", "timermodel");
        $this->load->model('usuario_model', 'usermodel');
        $this->load->model("cargos_model", "cargosmodel");
    }

    public function imprimir_finalizados($id){
        
        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $informacoes_documento = json_decode($this->docmodel->historico_documento($id));
        
        foreach ($informacoes_documento as $doc ) {

            if ($doc->idempresa == $_SESSION["guest_empresa"]) {
                
                $dados["informacoes_documento"] = $informacoes_documento;
        
                $dados["etapas_documento"]      = json_decode($this->docmodel->historico_documentos_dados($id));
                $dados["id_documento"]          = $this->docmodel->documento_id($id);
                $dados["erros_documento"]       = $this->docmodel->erros_do_documento($id);
                $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
        
                $this->load->view('relatorios/imprimir/relatorios_finalizados', $dados);

            } else {

                $this->load->view('errors/acesso_restrito');

            }
        }

    }

    public function imprimir_tempo_medio($id){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $informacoes_documento = json_decode($this->docmodel->historico_documento($id));

        foreach ($informacoes_documento as $doc) {
            
            if ($doc->idempresa == $_SESSION["guest_empresa"]) {
                
                $dados["informacoes_documento"] = $informacoes_documento;
                $dados["tempo_medio"]           = $this->timermodel->listar_timer($id);
                $dados["tempo_por_etapa"]       = $this->timermodel->timer_etapa($id);
                $dados["tempo_por_responsavel"] = $this->timermodel->timer_responsavel($id);
                $dados["data_finalizacao"]      = $this->docmodel->finalizacao_data_documento($id);
                $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
                
                $this->load->view('relatorios/imprimir/relatorios_tempo', $dados);

            } else {

                $this->load->view('errors/acesso_restrito');

            }

        }
    }

    public function imprimir_tempo_medio_mensal($empresa, $date){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        if ($empresa == $_SESSION["guest_empresa"]) {

            $dados["mes_ano"]       = $date;
            $dados["dados_mensais"] = $this->docmodel->documento_do_mes($date);
            $dados["tempo_medio"]   = $this->timermodel->tempo_documento_mensal($date);
            $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);

            $this->load->view('relatorios/imprimir/relatorios_tempo_mensal', $dados);
            
        } else {

            $this->load->view('errors/acesso_restrito');

        }

    }

    public function produtividade_relatorio($idusuario){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $usuario = $this->usermodel->get_user($idusuario);

        if ($_SESSION["guest_empresa"] == $usuario->fk_idempresa) {

            $dados["usuario"]               = $usuario;
            $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
            $dados["documentos_fnalizados"] = $this->docmodel->quantidade_documentos_finalizados_usuario($idusuario);
            $dados["documentos_andamento"]  = $this->docmodel->numero_documentos($idusuario);
            $dados["tempomedio"]            = $this->timermodel->tempo_documento_usuario($idusuario);
            $dados["erros_user"]            = $this->docmodel->erros_usuario_documento($idusuario);
            
            $this->load->view('relatorios/imprimir/relatorio_produtividade',$dados);

        } else {

            $this->load->view('errors/acesso_restrito');

        }

    }

    public function imprimir_fora_prazo($idprotocolo){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect('/');
        }


        $informacoes_documento = json_decode($this->docmodel->historico_documento($idprotocolo));

        foreach ($informacoes_documento as $doc) {
            
            if ($doc->idempresa == $_SESSION["guest_empresa"]) {

                $dados["informacoes_documento"] = $informacoes_documento;
                $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
                $dados["documentos_prazo"]      = $this->docmodel->documento_em_atraso($idprotocolo);

                $this->load->view('relatorios/imprimir/relatorio_prazo', $dados);

            } else {

                $this->load->view('errors/acesso_restrito');

            }

        }

    }

    public function imprimir_historico($idprotocolo){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $informacoes_documento = json_decode($this->docmodel->historico_documento($idprotocolo));

        foreach ($informacoes_documento as $doc) {
            
            if ($doc->idempresa == $_SESSION["guest_empresa"]) {
                
                $dados["informacoes_documento"] = $informacoes_documento;
                $dados["historico_documentos"]  = json_decode($this->docmodel->historico_documentos_dados($idprotocolo));
                $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION['guest_empresa']);

                $this->load->view('relatorios/imprimir/imprimir_historico', $dados);

            } else {

                $this->load->view('errors/acesso_restrito');

            }
        }

    }
}
