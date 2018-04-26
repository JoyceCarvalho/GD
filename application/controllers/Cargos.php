<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cargos extends CI_Controller {

	public function __construct(){
		parent::__construct();

        $this->load->model('cargos_model', 'cargosmodel');
        $this->load->model('empresa_model', 'empresamodel');
	}
	
    public function cadastrar_cargo(){

        $data = new stdClass();

        $dados = array('titulo' => $this->input->post("titulo"), 'fk_idempresa' => $_SESSION["idempresa"]);

        if ($this->cargosmodel->cadastrar_cargos($dados)) {
            
            $data->success = "Cargo ". $this->input->post('titulo') ." casdastrado com sucesso!";

            $dados['pagina']    = "Cargos";
            $dados['pg']        = "empresa";
            $dados["submenu"]   = "cargos";

            //dados do banco (nome da empresa) menu
            $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $data);
            $this->load->view('cargo_cadastro');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            $data->error = "Ocorreu um problema ao cadastrar o cargo! Favor entre em contato com o suporte e tente novamente mais tarde!";

            $dados['pagina']    = "Cargos";
            $dados['pg']        = "empresa";
            $dados["submenu"]   = "cargos";

            //dados do banco (nome da empresa) menu
            $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

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

            $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
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

                $data->success = "Cargo ".$this->input->post('titulo'). "atualizado com sucesso";

                $dados["pagina"]        = "Cargos";
                $dados["pg"]            = "empresa";
                $dados["submenu"]       = "cargos";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["dados_cargo"]   = $this->cargosmodel->dados_cargo($idcargo);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('cargo_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            } else {

                $data->error = "Ocorreu um erro ao atualizar o cargo! Favor entre em contato com o suporte e tente mais tarde novamente!";

                $dados["pagina"]        = "Cargos";
                $dados["pg"]            = "empresa";
                $dados["submenu"]       = "cargos";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
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

            if($this->cargosmodel->excluir_cargo($id)){

                $data->success = "Cargo excluido com sucesso!";

                $dados["pagina"]        = "Cargos";
                $dados["pg"]            = "empresa";
                $dados["submenu"]       = "cargos";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados['listagem_cargos'] = $this->cargosmodel->listar_cargos($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('cargos');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            } else {

                $data->error = "Ocorreu um problema ao excluir o cargo! Favor entre em contato com o supote e tente mais tarde novamente!";

                $dados["pagina"]        = "Cargos";
                $dados["pg"]            = "empresa";
                $dados["submenu"]       = "cargos";

                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados['listagem_cargos']   = $this->cargosmodel->listar_cargos($_SESSION["idempresa"]);

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
