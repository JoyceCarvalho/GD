<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Imprimir extends CI_Controller {
    
    function __construct(){
        parent::__construct();

        $this->load->model("documentos_model", "docmodel");
        $this->load->model("etapas_model", "etapasmodel");
        $this->load->model("DocEtapas_model", "docetapa");
        $this->load->model("empresa_model", "empresamodel");
        $this->load->model("timer_model", "timermodel");
        $this->load->model('usuario_model', 'usermodel');
        $this->load->model("cargos_model", "cargosmodel");
        $this->load->model('filtros_model', 'filtromodel');
    }

    public function imprimir_finalizados($id){
        
        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $informacoes_documento = json_decode($this->docmodel->historico_documento($id));
        
        foreach ($informacoes_documento as $doc ) {

            if ($doc->idempresa == $_SESSION["idempresa"]) {
                
                $dados["informacoes_documento"] = $informacoes_documento;
        
                $dados["etapas_documento"]      = json_decode($this->docmodel->historico_documentos_dados($id));
                $dados["id_documento"]          = $this->docmodel->documento_id($id);
                $dados["erros_documento"]       = $this->docmodel->erros_do_documento($id);
                $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        
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
            
            if ($doc->idempresa == $_SESSION["idempresa"]) {

                $validar = $this->timermodel->verifica_reinicio($id);

                if($validar){
                    $tempomedio = $this->timermodel->listar_timer_suspenso($id);
                } else {
                    $tempomedio = $this->timermodel->listar_timer($id);
                }
                
                $dados["informacoes_documento"] = $informacoes_documento;
                $dados["tempo_medio"]           = $tempomedio;
                $dados["qnt_etapas_documento"]  = $this->docetapa->qnt_etapas_por_documento($id);
                $dados["tempo_por_etapa"]       = $this->timermodel->timer_etapa($id);
                $dados["tempo_por_responsavel"] = $this->timermodel->timer_responsavel($id);
                $dados["tempo_em_suspensao"]    = $this->timermodel->tempo_em_suspensao($id);
                $dados["tempo_pendente"]        = $this->timermodel->tempo_pendente($id);
                $dados["tempo_total_documento"] = $this->timermodel->listar_timer($id);
                $dados["data_finalizacao"]      = $this->docmodel->finalizacao_data_documento($id);
                $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                
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

        if ($empresa == $_SESSION["idempresa"]) {

            $dados["mes_ano"]       = $date;
            $dados["dados_mensais"] = $this->docmodel->documento_do_mes($date, $_SESSION["idempresa"]);
            $dados["tempo_medio"]   = $this->timermodel->tempo_documento_mensal($date, $_SESSION["idempresa"]);
            $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

            $this->load->view('relatorios/imprimir/relatorios_tempo_mensal', $dados);
            
        } else {

            $this->load->view('errors/acesso_restrito');

        }

    }

    public function filtro_tempo_grupo($grupo){

        if((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)){
            redirect("/");
        }

        $this->load->model('Grupo_model', 'grupomodel');

        $dados_grupo = $this->grupomodel->dados_grupo($grupo);

        foreach ($dados_grupo as $grupo) {
            
            if ($grupo->fk_idempresa == $_SESSION["idempresa"]) {
                
                $dados["titulo_grupo"]     = $grupo->titulo;
                $dados["documentos_grupo"] = $this->filtromodel->resultado_filtro_grupo($_SESSION["idempresa"], $grupo->id);
                $dados["nome_empresa"]     = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

                $this->load->view('relatorios/imprimir/tempo_filtro_grupo', $dados);

            } else {

                $this->load->view('errors/acesso_restrito');

            }
        }

    }

    public function filtro_tempo_documentos($documento){

        if((!isset($_SESSION["logado"])) && ($_SESSION["logado"])){
            redirect('/');
        }

        $dados_documento = $this->docmodel->dados_documentos($documento);

        foreach ($dados_documento as $doc) {
            
            if($doc->fk_idempresa == $_SESSION["idempresa"]){

                $dados["titulo_documento"]     = $doc->titulo;
                $dados["protocolos_documento"] = $this->filtromodel->resultados_filtro_documentos($_SESSION["idempresa"], $documento);
                $dados["nome_empresa"]         = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

                $this->load->view('relatorios/imprimir/tempo_filtro_documento', $dados);

            } else {

                $this->load->view('errors/acesso_restrito');

            }
        }

    }

    public function imprimir_tempo_medio_cargo($cargo){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $dados_cargo = $this->cargosmodel->dados_cargo($cargo);

        foreach ($dados_cargo as $cargos) {
            
            if ($cargos->fk_idempresa == $_SESSION["idempresa"]) {

                $tempomedio = 0;
                $verifica_pause = $this->timermodel->verifica_pause_cargo($cargo);
                if(!empty($verifica_pause)){
                    if($verifica_pause->action == "start"){
                        $tempomedio = $this->timermodel->tempo_documento_cargo_without($cargo, $verifica_pause->id);
                    } else {
                        $tempomedio = $this->timermodel->tempo_documento_cargo($cargo);
                    }
                }
                
                $dados["dados_cargo"]           = $dados_cargo;
                $dados["documento_trabalhados"] = $this->docmodel->documento_por_cargo($cargo);
                $dados["tempo_medio"]           = $tempomedio;

                $this->load->view("relatorios/imprimir/relatorios_tempo_cargo", $dados);

            } else {

                $this->load->view("errors/acesso_restrito");

            }
        }
    }

    public function produtividade_relatorio($idusuario){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $usuario = $this->usermodel->get_user($idusuario);

        if ($_SESSION["idempresa"] == $usuario->fk_idempresa) {

            $tempomedio = 0;
            $verifica_pause = $this->timermodel->verifica_pause($idusuario);
            if(!empty($verifica_pause)){
                if($verifica_pause->action == "start"){
                    $tempomedio = $this->timermodel->tempo_documento_usuario($idusuario, $verifica_pause->id);
                } else {
                    $tempomedio = $this->timermodel->tempo_documento_usuario_rel($idusuario);
                }
            }

            $dados["usuario"]               = $usuario;
            $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["documentos_fnalizados"] = $this->docmodel->quantidade_documentos_finalizados_usuario($idusuario);
            $dados["documentos_andamento"]  = $this->docmodel->numero_documentos($idusuario);
            $dados["tempomedio"]            = $tempomedio;
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
            
            if ($doc->idempresa == $_SESSION["idempresa"]) {

                $dados["informacoes_documento"] = $informacoes_documento;
                $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
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
            
            if ($doc->idempresa == $_SESSION["idempresa"]) {
                
                $dados["informacoes_documento"] = $informacoes_documento;
                $dados["historico_documentos"]  = json_decode($this->docmodel->historico_documentos_dados($idprotocolo));
                $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

                $this->load->view('relatorios/imprimir/imprimir_historico', $dados);

            } else {

                $this->load->view('errors/acesso_restrito');

            }
        }

    }

    public function filtro_por_grupo($grupo){

        if((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)){
            redirect("/");
        }

        $this->load->model('Grupo_model', 'grupomodel');

        $dados_grupo = $this->grupomodel->dados_grupo($grupo);

        foreach ($dados_grupo as $grupo) {
            
            if ($grupo->fk_idempresa == $_SESSION["idempresa"]) {
                
                $dados["titulo_grupo"]     = $grupo->titulo;
                $dados["documentos_grupo"] = $this->filtromodel->resultado_filtro_grupo($_SESSION["idempresa"], $grupo->id);
                $dados["nome_empresa"]     = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

                $this->load->view('relatorios/imprimir/imprimir_filtro_grupo', $dados);

            } else {

                $this->load->view('errors/acesso_restrito');

            }
        }

    }

    public function filtro_por_documento($documento){

        if((!isset($_SESSION["logado"])) && ($_SESSION["logado"])){
            redirect('/');
        }

        $dados_documento = $this->docmodel->dados_documentos($documento);

        foreach ($dados_documento as $doc) {
            
            if($doc->fk_idempresa == $_SESSION["idempresa"]){

                $dados["titulo_documento"]     = $doc->titulo;
                $dados["protocolos_documento"] = $this->filtromodel->resultados_filtro_documentos($_SESSION["idempresa"], $documento);
                $dados["nome_empresa"]         = $this->empresamodel->nome_empresa($_SESSION['idempresa']);

                $this->load->view('relatorios/imprimir/imprimir_filtro_documento', $dados);

            } else {

                $this->load->view('errors/acesso_restrito');

            }
        }
    }

}
