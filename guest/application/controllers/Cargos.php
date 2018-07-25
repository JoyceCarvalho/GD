<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cargos extends CI_Controller {

	function __construct(){
		parent::__construct();

        $this->load->model('cargos_model', 'cargosmodel');
        $this->load->model('empresa_model', 'empresamodel');
        $this->load->model('LogsSistema_model', 'logsistema');
	}
	
    public function cadastrar_cargo(){

        $data = new stdClass();

        $dados = array('titulo' => $this->input->post("titulo"), 'fk_idempresa' => $_SESSION["guest_empresa"]);

        if ($this->cargosmodel->cadastrar_cargos($dados)) {
            
            $data->success = "Cargo ". $this->input->post('titulo') ." cadastrado com sucesso!";

            $dados['pagina']    = "Cargos";
            $dados['pg']        = "empresa";
            $dados["submenu"]   = "cargos";

            //dados do banco (nome da empresa) menu
            $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $data);
            $this->load->view('cargo_cadastro');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

            //log do sistema
            $mensagem = "Cadastrou o cargo ".$this->input->post('titulo');
            $log = array(
                'usuario' => $_SESSION["idusuario"], 
                'mensagem' => $mensagem,
                'data_hora' => date("Y-m-d H:i:s")
            );
            $this->logsistema->cadastrar_log_sistema($log);
            //fim log sistema

        } else {

            $data->error = "Ocorreu um problema ao cadastrar o cargo! Favor entre em contato com o suporte e tente novamente mais tarde!";

            $dados['pagina']    = "Cargos";
            $dados['pg']        = "empresa";
            $dados["submenu"]   = "cargos";

            //dados do banco (nome da empresa) menu
            $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $data);
            $this->load->view('cargo_cadastro');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        }
    }

    public function editar_cargo_pagina(){

        $id = $this->input->post('idcargo');

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)) {
            
            $dados["pagina"]        = "Cargos";
            $dados["pg"]            = "empresa";
            $dados["submenu"]       = "cargos";

            $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
            $dados["dados_cargo"]   = $this->cargosmodel->dados_cargo($id);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('cargo_editar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');
        } else {
            redirect('/');
        }
    }

    public function editar_cargo(){

        $data = new stdClass();

        if((isset($_SESSION['logado'])) && ($_SESSION["logado"] == true)){

            $idcargo = $this->input->post('idcargo');
            
            $dados   = array(
                'titulo' => $this->input->post('titulo')
            );

            if($this->cargosmodel->editar_cargo($dados, $idcargo)){

                $data->success = "Cargo ".$this->input->post('titulo'). " atualizado com sucesso";

                $dados["pagina"]        = "Cargos";
                $dados["pg"]            = "empresa";
                $dados["submenu"]       = "cargos";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
                $dados["dados_cargo"]   = $this->cargosmodel->dados_cargo($idcargo);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('cargo_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                $mensagem = "Edição do cargo " . $this->input->post('titulo');
                $log = array(
                    'usuario'   => $_SESSION["idusuario"], 
                    'mensagem'  => $mensagem, 
                    'data_hora' => date("Y-m-d H:i:s")
                );
                $this->logsistema->cadastrar_log_sistema($log);

            } else {

                $data->error = "Ocorreu um erro ao atualizar o cargo! Favor entre em contato com o suporte e tente mais tarde novamente!";

                $dados["pagina"]        = "Cargos";
                $dados["pg"]            = "empresa";
                $dados["submenu"]       = "cargos";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
                $dados["dados_cargo"]   = $this->cargosmodel->dados_cargo($idcargo);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('cargo_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            }

        }
    }

    public function excluir_cargo(){

        $id = $this->input->post('idcargo');

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"] == true)){

            $data = new stdClass();

            $nome = $this->logsistema->seleciona_por_titulo('tbcargos', $id);

            if($this->cargosmodel->excluir_cargo($id)){

                $data->success = "Cargo excluido com sucesso!";

                $dados["pagina"]        = "Cargos";
                $dados["pg"]            = "empresa";
                $dados["submenu"]       = "cargos";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
                $dados['listagem_cargos'] = $this->cargosmodel->listar_cargos($_SESSION["guest_empresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('cargos');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

                //log do sistema
                $mensagem = "Excluiu o cargo $nome";
                $log = array(
                    'usuario' => $_SESSION["idusuario"],
                    'mensagem' => $mensagem,
                    'data_hora' => date("Y-m-d H:i:s")
                );
                $this->logsistema->cadastrar_log_sistema($log);
                //fim log da sistema

            } else {

                $data->error = "Ocorreu um problema ao excluir o cargo! Favor entre em contato com o supote e tente mais tarde novamente!";

                $dados["pagina"]        = "Cargos";
                $dados["pg"]            = "empresa";
                $dados["submenu"]       = "cargos";

                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["guest_empresa"]);
                $dados['listagem_cargos']   = $this->cargosmodel->listar_cargos($_SESSION["guest_empresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('cargos');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
            }
        } else {
            redirect('/');
        }
    }

}
