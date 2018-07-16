<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Competencia extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->model('empresa_model', 'empresamodel');
        $this->load->model('documentos_model', 'docmodel');
        $this->load->model('DocEtapas_model', 'docetapamodel');
        $this->load->model('competencia_model', 'compmodel');
        $this->load->model('usuario_model', 'usermodel');
        $this->load->model('cargos_model', 'cargomodel');
        $this->load->model('LogsSistema_model', 'logsistema');
    }

	public function index()	{

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
            
            $dados["pagina"] = "Competência Funcionários/Cargos";
            $dados["pg"]     = "configuracao";
            $dados["submenu"] = "comp";

            $dados["listagem_documento"]    = $this->docmodel->listar_documentos($_SESSION["guest_empresa"]);
            $dados["nome_empresa"]          = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/competencia');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            redirect('/');
        }
        
    }

    public function cadastro(){

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $id = $this->input->post('iddocumento');

            $dados["pagina"] = "Competências Funcionários/Cargo";
            $dados["pg"] = "configuracao";
            $dados["submenu"] = "competencia";

            $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
            $dados["dados_etapa"]   = $this->docetapamodel->listar_documento_etapa($id);
            $dados["competencia_d"] = $this->compmodel->listar_competencias($id);
            $dados["dados_usuario"] = $this->usermodel->listar_usuarios($_SESSION["guest_empresa"]);
            $dados["dados_cargo"]   = $this->cargomodel->listar_cargos($_SESSION["guest_empresa"]);
            $dados["documento"]     = $this->compmodel->nome_documento($id);
            $dados["iddocumento"]   = $id;

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('config/competencia_cadastrar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            redirect('/');
        }
    }

    public function cadastrar(){

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
    
            $data = new stdClass();

            $iddocumento = $this->input->post('iddocumento');
            $total_etapa = $this->input->post('quant_etapas');
            $tipo        = $this->input->post('tipo');

            $titulo = $this->logsistema->retorna_titulo_documento($iddocumento);
            $aux_edicao = false;

            for ($i=1; $i <= $total_etapa; $i++) { 

                if ($tipo == "funcionario") {
                    
                    $dados = array(
                        'tipo' => $tipo,
                        'fk_iddocumento' => $iddocumento,
                        'fk_idetapa'     => $this->input->post("etapa_$i"),
                        'fk_idusuario'   => $this->input->post("idtipo_$i"),
                        'fk_idcargo'     => 0,
                        'fk_idempresa'   => $_SESSION["guest_empresa"]
                    );

                } else {
                    
                    $dados = array(
                        'tipo' => $tipo,
                        'fk_iddocumento' => $iddocumento,
                        'fk_idetapa'     => $this->input->post("etapa_$i"),
                        'fk_idusuario'   => 0,
                        'fk_idcargo'     => $this->input->post("idtipo_$i"),
                        'fk_idempresa'   => $_SESSION["guest_empresa"]
                    );

                }

                if (($this->compmodel->retorna_cadastrados($iddocumento) > 0) and ($i == 1)) {
                    
                    //echo "aqui exclui";
                    $this->compmodel->excluir_compentecias($iddocumento);

                    $aux_edicao = true;
                    //log do sistema
                    $mensagem = "Edição da competencia do documento " . $titulo;
                    $log = array(
                        'usuario' => $_SESSION["idusuario"],
                        'mensagem' => $mensagem,
                        'data_hora' => date('Y-m-d H:i:s')
                    );
                    $this->logsistema->cadastrar_log_sistema($log);
                    //fim log sistema

                }
                //echo "aqui cadastra";
                $cadastrar = $this->compmodel->cadastar_competencias($dados);
                
            }

            if ($cadastrar) {
                
                $data->success = "Competência cadastrada com sucesso!";

                $dados["pagina"]    = "Competências Funcionários/Cargo";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "competencia";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
                $dados["dados_etapa"]   = $this->docetapamodel->listar_documento_etapa($iddocumento);
                $dados["competencia_d"] = $this->compmodel->listar_competencias($iddocumento);
                $dados["dados_usuario"] = $this->usermodel->listar_usuarios($_SESSION["guest_empresa"]);
                $dados["dados_cargo"]   = $this->cargomodel->listar_cargos($_SESSION["guest_empresa"]);
                $dados["documento"]     = $this->compmodel->nome_documento($iddocumento);
                $dados["iddocumento"]   = $iddocumento;

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/competencia_cadastrar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                if ($aux_edicao == false) {
                    //log do sistema
                    $mensagem = "Cadastrou a competência do documento ".$titulo;
                    $log = array(
                        'usuario'   => $_SESSION["idusuario"],
                        'mensagem'  => $mensagem,
                        'data_hora' => date("Y-m-d H:i:s")
                    );
                    $this->logsistema->cadastrar_log_sistema($log);
                    //fim log sistema
                }

            } else {
                
                $data->error = "Ocorreu um problema ao cadastrar a competência! Favor entre em contato com o suporte e tente novamente mais tarde";

                $dados["pagina"]    = "Competências Funcionários/Cargo";
                $dados["pg"]        = "configuracao";
                $dados["submenu"]   = "competencia";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
                $dados["dados_etapa"]   = $this->docetapamodel->listar_documento_etapa($iddocumento);
                $dados["competencia_d"] = $this->compmodel->listar_competencias($iddocumento);
                $dados["dados_usuario"] = $this->usermodel->listar_usuarios($_SESSION["guest_empresa"]);
                $dados["dados_cargo"]   = $this->cargomodel->listar_cargos($_SESSION["guest_empresa"]);
                $dados["documento"]     = $this->compmodel->nome_documento($iddocumento);
                $dados["iddocumento"]   = $iddocumento;

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('config/competencia_cadastrar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
            }
            
        } else {

            redirect('/');

        }

    }

    public function tipo_competencia($tipo){

        if($tipo == "funcionario"){

            echo $this->usermodel->listar_usuarios_json($_SESSION["guest_empresa"]);

        } elseif($tipo == "cargo"){
            
            echo $this->cargomodel->listar_cargos_json($_SESSION["guest_empresa"]);

        }

    }

}
