<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Relatorios extends CI_Controller {
    
    function __construct(){
        parent::__construct();

        $this->load->model('documentos_model', 'docmodel');
        $this->load->model('erros_model', 'errosmodel');
        $this->load->model('empresa_model', 'empresamodel');
        $this->load->model('timer_model', 'timermodel');
        $this->load->model('usuario_model', 'usermodel');
        $this->load->model('cargos_model', 'cargosmodel');
        $this->load->model('grupo_model', 'grupomodel');
    }

    public function finalizados(){
        
        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $dados["pagina"]  = "Documentos Finalizados";
        $dados["pg"]      = "relatorio";
        $dados["submenu"] = "finalizado";

        $dados["nome_empresa"]    = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["doc_finalizados"] = $this->docmodel->listar_documentos_finalizados($_SESSION["idempresa"]);

        $this->load->view("template/html_header", $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('relatorios/finalizados');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }

    public function tempo_medio(){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true )) {
            redirect("/");
        }

        if(isset($_POST["filtrar_mesano"])){

            $mesano = $this->input->post('filtrar_mesano');

            if((!empty($mesano)) && ($mesano != "nda")){

                $dados["doc_finalizados"] = $this->docmodel->filtro_documentos_finalizados($_SESSION["idempresa"], $mesano);
                $dados["mesano_filtrado"]    = $mesano;                

            } else {

                $dados["doc_finalizados"] = $this->docmodel->listar_documentos_finalizados($_SESSION["idempresa"]);
                $dados["mesano_filtrado"]    = "";

            }

        } else {
            $dados["doc_finalizados"] = $this->docmodel->listar_documentos_finalizados($_SESSION["idempresa"]);
            $dados["mesano_filtrado"]    = "";
        }

        $dados["pagina"]  = "Relatório de Tempo Médio";
        $dados["pg"]      = "relatorio";
        $dados["submenu"] = "tempo";
        $dados["sub"]     = "tempgeral";

        $dados["nome_empresa"]    = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["finalizados"]      = $this->docmodel->listar_documentos_finalizados_filtro($_SESSION["idempresa"]);
        
        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('relatorios/tempo_medio');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }

    public function tempo_mensal(){
        
        if((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)){
            redirect("/");
        }

        if((isset($_POST["filtrar_mes"])) or  (isset($_POST["filtrar_ano"]))){
            
            $mes = $this->input->post('filtrar_mes');
            $ano = $this->input->post('filtrar_ano');

            if(((!empty($mes)) && ($mes != "nda")) or ((!empty($ano)) && ($ano != "nda"))){

                $mes_true = false;
                $ano_true = false;

                if((!empty($mes)) && ($mes != "nda")){
                    $mes_true  = true;
                }

                if((!empty($ano)) && ($ano != "nda")){
                    $ano_true = true;
                }

                if(($mes_true == true) and ($ano_true == true)){

                    $dados["doc_finalizados"] = $this->docmodel->listar_documentos_finalizados_filtro_mesano($_SESSION["idempresa"], $mes, $ano);
                    $dados["mes_filtrado"]    = $mes;
                    $dados["ano_filtrado"]    = $ano;

                } elseif(($mes_true == true) and ($ano_true == false)){

                    $dados["doc_finalizados"] = $this->docmodel->filtro_documentos_por_mes($_SESSION["idempresa"], $mes);
                    $dados["mes_filtrado"]    = $mes;
                    $dados["ano_filtrado"]    = "";

                } elseif(($mes_true == false) and ($ano_true == true)){

                    $dados["doc_finalizados"] = $this->docmodel->filtro_documentos_por_ano($_SESSION["idempresa"], $ano);
                    $dados["mes_filtrado"]    = $mes;
                    $dados["ano_filtrado"]    = "";
                    
                } else {
                    $dados["doc_finalizados"] = "";
                    $dados["mes_filtrado"]    = "";
                    $dados["ano_filtrado"]    = "";
                }

            } else {

                $dados["doc_finalizados"] = $this->docmodel->listar_documentos_finalizados_mes($_SESSION["idempresa"]);
                $dados["mes_filtrado"]    = "";
                $dados["ano_filtrado"]    = "";

            }

        } else {

            $dados["doc_finalizados"] = $this->docmodel->listar_documentos_finalizados_mes($_SESSION["idempresa"]);
            $dados["mes_filtrado"]    = "";
            $dados["ano_filtrado"]    = "";

        }

        $dados["pagina"]   = "Relatório de tempo médio mensal";
        $dados["pg"]       = "relatorio";
        $dados["submenu"]  = "tempo";
        $dados["sub"]      = "tempmensal";

        $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["finalizados"]       = $this->docmodel->listar_documentos_finalizados_mes($_SESSION["idempresa"]);
        $dados["finalizados_ano"]   = $this->docmodel->listar_documentos_finalizados_ano($_SESSION["idempresa"]);

        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('relatorios/tempo_mensal');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }

    public function tempo_cargo(){

        if((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)){
            redirect("/");
        }

        $dados["pagina"]    = "Relatório de tempo médio por cargo";
        $dados["pg"]        = "relatorio";
        $dados["submenu"]   = "tempo";
        $dados["sub"]       = "tempocargo";

        $dados["nome_empresa"]    = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["doc_finalizados"] = $this->cargosmodel->listar_cargos_tempo($_SESSION["idempresa"]);

        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('relatorios/tempo_cargo');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');


    }

    public function produtividade_individual(){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $dados["pagina"]  = "Produtividade Individual";
        $dados["pg"]      = "relatorio";
        $dados["submenu"] = "produtividade";
        $dados["sub"]     = "individual";

        $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados['usuario']               = $this->usermodel->dados_usuario($_SESSION["idusuario"]);
        $dados["documentos_fnalizados"] = $this->docmodel->quantidade_documentos_finalizados_usuario($_SESSION["idusuario"]);
        $dados["documentos_andamento"]  = $this->docmodel->numero_documentos($_SESSION["idusuario"]);
        $dados["tempomedio"]            = $this->timermodel->tempo_documento_usuario($_SESSION["idusuario"]);
        $dados["erros_user"]            = $this->docmodel->erros_usuario_documento($_SESSION["idusuario"]);
        
        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('relatorios/produtividade_user');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }

    public function produtividade_grupo(){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $dados["pagina"]  = "Produtividade Grupo";
        $dados["pg"]      = "relatorio";
        $dados["submenu"] = "produtividade";
        $dados["sub"]     = "prod_grupo";

        $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["usuario"]       = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);

        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('relatorios/produtividade');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }

    public function listar_prazos(){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect('/');
        }

        $dados["pagina"]  = "Documentos fora do prazo";
        $dados["pg"]      = "relatorio";
        $dados["submenu"] = "prazos";

        $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["doc_prazo"]    = $this->docmodel->listar_documentos_fora_prazo($_SESSION["idempresa"]);

        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('relatorios/fora_prazo');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }

}
