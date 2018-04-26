<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feriados extends CI_Controller {

    public function __construct(){

        parent::__construct();

        $this->load->model('Empresa_model', 'empresamodel');
        $this->load->model('usuario_model', 'usermodel');
        $this->load->model('horario_model', 'horasmodel');
        $this->load->model('cargos_model', 'cargosmodel');
        $this->load->model('feriados_model', 'feriadosmodel');
        
    }

    public function cadastrar_feriados(){

        if((isset($_SESSION['logado'])) && ($_SESSION['logado'] == true)){

            $data = new stdClass();

            $dados = array(
                'titulo'        => $this->input->post('titulo'), 
                'dia'           => $this->input->post('dia'),
                'fk_idempresa'  => $_SESSION["idempresa"]
            );

            if($this->feriadosmodel->cadastrar_feriados($dados)){

                $data->success = "Feriado ".$this->input->post('titulo')." cadastrado com sucesso!";

                $dados["pagina"]    = "Feriados";
                $dados["pg"]        = "empresa";
                $dados["submenu"]   = "feriado";

                // dados do banco (nome empresa) menu
                $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('feriados_cadastro');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            } else {

                $data->error = "Ocorreu um problema ao cadastrar o feriado! Favor entre em contato com o suporte e tente novamente mais tarde";

                $dados["pagina"]    = "Feriados";
                $dados["pg"]        = "empresa";
                $dados["submenu"]   = "feriado";

                // dados do banco (nome empresa) menu
                $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('feriados_cadastro');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            }
        } else {

            redirect('/');
        }
    }

    public function editar_feriados_pagina(){

        $id = $this->input->post('idferiado');

        if((isset($_SESSION["logado"])) && ($_SESSION['logado'] == true)){

            $dados["pagina"]    = "Feriados";
            $dados["pg"]        = "empresa";
            $dados["submenu"]   = "feriado";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["dados_feriado"] = $this->feriadosmodel->dados_feriado($id);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('feriados_editar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {

            redirect('/');

        }
    }

    public function editar_feriados(){

        if ((isset($_SESSION['logado'])) && ($_SESSION['logado'] == true)) {
            
            $data = new stdClass();

            $idferiado = $this->input->post('idferiado');

            $dados = array(
                'titulo' => $this->input->post('titulo'),
                'dia'    => $this->input->post('dia') 
            );

            if ($this->feriadosmodel->editar_feriado($dados, $idferiado)) {

                $data->success = "Feriado ". $this->input->post('titulo'). " atualizado com sucesso!";
                
                $dados["pagina"]    = "Feriados";
                $dados["pg"]        = "empresa";
                $dados["submenu"]   = "feriado";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["dados_feriado"] = $this->feriadosmodel->dados_feriado($idferiado);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('feriados_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');   

            } else {

                $data->error = "Ocorreu um erro ao atualizar as informações! Favor entre em contato com o suporte e tente novamente mais tarde!";

                $dados["pagina"]    = "Feriados";
                $dados["pg"]        = "empresa";
                $dados["submenu"]   = "feriado";

                $dados["nome_empresa"]  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["dados_feriado"] = $this->feriadosmodel->dados_feriado($idferiado);

                $this->load->view('template/html_header');
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('feriados_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            }
        } else {

            redirect("/");

        }

    }

    public function excluir_feriados(){

        $id = $this->input->post('idferiado');

        if((isset($_SESSION["logado"])) && ($_SESSION["logado"])){

            $data = new stdClass();

            if ($this->feriadosmodel->excluir_feriado($id)) {
                
                $data->success = "Feriado excluido com sucesso!";

                $dados["pagina"]    = "Feriados";
                $dados["pg"]        = "empresa";
                $dados["submenu"]   = "feriado";

                // dados do banco (nome empresa) menu
                $dados['nome_empresa']      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados['listagem_feriados'] = $this->feriadosmodel->listar_feriados($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('feriados');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            } else {

                $data->error = "Ocorreu um problema ao excluir está informação! Favor entre em contato com o suporte e  tente novamente mais tarde!";

                $dados["pagina"]    = "Feriados";
                $dados["pg"]        = "empresa";
                $dados["submenu"]   = "feriado";

                // dados do banco (nome empresa) menu
                $dados['nome_empresa']      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados['listagem_feriados'] = $this->feriadosmodel->listar_feriados($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('feriados');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            }
        } else {

            redirect('/');
        }
    }


}
