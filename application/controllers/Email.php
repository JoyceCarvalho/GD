<?php
defined("BASEPATH") or exit('No direct script access allowed');

class Email extends CI_Controller {
    
    public function __construct(){
        parent::__construct();        
    }

    public function EnviarEmail($dados){
        
        $this->load->library('email');

        $config['protocol'] = 'smtp';
        $config["charset"]  = 'utf-8';
		$config['wordwrap'] = TRUE;
		$config['validate'] = TRUE;

		$config["smtp_crypto"] = "tsl";
		$config["smtp_host"]   = 'bz16.hostgator.com.br';
		$config["smtp_user"]   = "contato@sgtgestaoetecnologia.com.br";
        $config["smtp_pass"]   = "{y22oG#A8ODc";

        $config['mailtype'] = 'html';

        $this->email->initialize($config);

        $this->email->from('sgt@sgtgestaoetecnologia.com.br', 'SGT - Gestão e Tecnologia');
        $this->email->to($dados["email"], $dados["nome_usuario"]);

        $this->email->subject('Gestão de Documentos');

        if ($dados["tipo"] == "novo") {

            $this->email->message($this->load->view('email/novo_documento', $dados));
            $retorno = "novo_documento";

        } elseif($dados["tipo"] == "pendente"){

            $this->email->message($this->load->view('email/documento_pendente',$dados));
            $retorno = "pendentes";

        } elseif($dados["tipo"] == "suspenso"){

            $this->email->message($this->load->view('email/documento_suspenso', $dados));
            $retorno = "suspenso";

        } elseif($dados["tipo"] == "cancelado"){

            $this->email->message($this->load->view('email/documento_cancelado', $dados));
            $retorno = "cancelados";

        } elseif($dados["tipo"] == "erro"){

            $this->email->message($this->load->view('email/documento_erro', $dados));
            $retorno = "erros";

        }

        $this->email->send();
        /*if ($this->email->send()) {
            redirect($retorno);
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao enviar o email!');
            redirect($retorno);
        }*/

    }

    public function recebe_documento($idprotocolo){

        

    }
}
 