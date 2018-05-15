<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Erros extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        
        $this->load->model('empresa_model', 'empresamodel');
        $this->load->model('erros_model', 'errosmodel');
    }

    public function index(){

        if (!(isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }
        
        $dados["pagina"]    = "Erros";
        $dados["pg"]        = "configuracao";
        $dados["submenu"]   = "erro";

        $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["listagem_erros"] = $this->errosmodel->listar_erros($_SESSION["idempresa"]);

        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('config/erros');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }

    public function pagina_cadastro(){

        if (!(isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            redirect("/");
        }

        $dados["pagina"]    = "Erros";
        $dados["pg"]        = "configuracao";
        $dados["submenu"]   = "erro";

        $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
        $dados["listagem_erros"] = $this->errosmodel->listar_erros($_SESSION["idempresa"]);

        $this->load->view('template/html_header', $dados);
        $this->load->view('template/header');
        $this->load->view('template/menu');
        $this->load->view('config/erros_cadastrar');
        $this->load->view('template/footer');
        $this->load->view('template/html_footer');

    }

    public function cadastrar(){

        if(!(isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)){
            redirect("/");
        }

        $data = new stdClass();

        $dados = array(
            "titulo"        => $this->input->post('titulo'),
            "tipo"          => $this->input->post("tipo_erro"),
            "fk_idempresa"  => $_SESSION["idempresa"]
        );

        if ($this->errosmodel->cadastrar_erros($dados)) {
            
            $data->success = "Erro ".$this->input->post('titulo')." cadastrado com sucesso!";

            $dados["pagina"]    = "Erros";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "erro";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["listagem_erros"] = $this->errosmodel->listar_erros($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $data);
            $this->load->view('config/erros_cadastrar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        } else {
            
            $data->error = "Ocorreu um problema ao cadastrar o erro. Favor entre em contato com o suporte e tente novamente mais tarde!";

            $dados["pagina"]    = "Erros";
            $dados["pg"]        = "configuracao";
            $dados["submenu"]   = "erro";

            $dados["nome_empresa"] = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados["listagem_erros"] = $this->errosmodel->listar_erros($_SESSION["idempresa"]);

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $data);
            $this->load->view('config/erros_cadastrar');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');

        }
        

    }
}
