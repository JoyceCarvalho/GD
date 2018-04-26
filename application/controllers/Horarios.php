<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Horarios extends CI_Controller {

    public function __construct(){

        parent::__construct();

        $this->load->model('Empresa_model', 'empresamodel');
        $this->load->model('usuario_model', 'usermodel');
        $this->load->model('horario_model', 'horasmodel');
        $this->load->model('cargos_model', 'cargosmodel');
        
    }

    public function cadastrar_horario(){

        if((isset($_SESSION['logado'])) && ($_SESSION['logado'] == true)){

            $data = new stdClass();

            $dados = array(
                'titulo' => $this->input->post('titulo'),
                'manha_entrada' => $this->horasmodel->converte_horas($this->input->post('entrada_manha')),
                'manha_saida'   => $this->horasmodel->converte_horas($this->input->post('saida_manha')),
                'tarde_entrada' => $this->horasmodel->converte_horas($this->input->post('entrada_tarde')),
                'tarde_saida'   => $this->horasmodel->converte_horas($this->input->post('saida_tarde')),
                'fk_idempresa'  => $_SESSION["idempresa"]
            );

            if($this->horasmodel->cadastrar_horario($dados)){

                $data->success = "Horário ".$this->input->post('titulo')." cadastrado com sucesso!";

                $dados["pagina"]    = "Horário de Trabalho";
                $dados["pg"]        = "empresa";
                $dados["submenu"]   = "horarios";

                // dados do banco (nome empresa) menu
                $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('horarios_cadastro');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            } else {

                $data->error = "Ocorreu um erro ao cadastrar horário! Favor entre em contato com o suporte e tente novamente mais tarde";

                $dados["pagina"]    = "Horário de Trabalho";
                $dados["pg"]        = "empresa";
                $dados["submenu"]   = "horarios";

                // dados do banco (nome empresa) menu
                $dados['nome_empresa'] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('horarios_cadastro');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            }

        } else {

            redirect('/');

        }

    }

    public function editar_horario_pagina(){

        $id = $this->input->post('idhorario');

        if((isset($_SESSION['logado'])) && ($_SESSION["logado"] == true)){

            $dados["pagina"]    = "Horário de Trabalho";
            $dados["pg"]        = "empresa";
            $dados["submenu"]   = "horarios";

            // dados do banco (nome empresa) menu
            $dados['nome_empresa']  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados['dados_horario'] = $this->horasmodel->dados_horario($id);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu');
            $this->load->view('horarios_editar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        }

    }

    public function editar_horario(){

        if ((isset($_SESSION["logado"])) && ($_SESSION['logado'])) {

            $data = new stdClass();
            
            $idhorario = $this->input->post('idhorario');

            $dados = array(
                'titulo'        => $this->input->post('titulo'), 
                'manha_entrada' => $this->input->post('entrada_manha'),
                'manha_saida'   => $this->input->post('saida_manha'),
                'tarde_entrada' => $this->input->post('entrada_tarde'),
                'tarde_saida'   => $this->input->post('saida_tarde')
            );

            if ($this->horasmodel->editar_horario($dados, $idhorario)) {
                
                $data->success = "Horário ".$this->input->post('titulo')." alterado com sucesso!";

                $dados["pagina"]    = "Horário de Trabalho";
                $dados["pg"]        = "empresa";
                $dados["submenu"]   = "horarios";

                // dados do banco (nome empresa) menu
                $dados['nome_empresa']  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados['dados_horario'] = $this->horasmodel->dados_horario($idhorario);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('horarios_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            } else {
                
                $data->error = "Ocorreu um problema ao alterar os horários! Favor entre em contato com o suporte e tente novamente mais tarde.";

                $dados["pagina"]    = "Horário de Trabalho";
                $dados["pg"]        = "empresa";
                $dados["submenu"]   = "horarios";

                // dados do banco (nome empresa) menu
                $dados['nome_empresa']  = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados['dados_horario'] = $this->horasmodel->dados_horario($idhorario);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('horarios_editar');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            }
            

        } else {

            redirect('/');

        }
    }

    public function excluir_horario(){

        $id = $this->input->post('idhorario');

        if ((isset($_SESSION["logado"])) && ($_SESSION["logado"])) {
            
            $data = new stdClass();

            if ($this->horasmodel->excluir_horario($id)) {
                
                $data->success = "Horário excluido com sucesso!";

                $dados["pagina"]    = "Horários de Trabalho";
                $dados["pg"]        = "empresa";
                $dados["submenu"]   = "horarios";

                // dados do banco (nome empresa) menu
                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["listagem_horarios"] = $this->horasmodel->listar_horario($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('horarios');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');

            } else {

                $data->error = "Ocorreu um problema ao excluir esta informação! Favor entre em contato com o suporte e tente novamente mais tarde.";

                $dados["pagina"]    = "Horários de Trabalho";
                $dados["pg"]        = "empresa";
                $dados["submenu"]   = "horarios";

                // dados do banco (nome empresa) menu
                $dados["nome_empresa"]      = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
                $dados["listagem_horarios"] = $this->horasmodel->listar_horario($_SESSION["idempresa"]);

                $this->load->view('template/html_header', $dados);
                $this->load->view('template/header');
                $this->load->view('template/menu', $data);
                $this->load->view('horarios');
                $this->load->view('template/footer');
                $this->load->view('template/html_footer');
                
            }
        }
    }

}
