<?php
defined("BASEPATH") or exit("No direct access script allowed");

class Email_model extends CI_Model {
    
    public function __construct(){
        
        parent::__construct();

        $this->load->library('email');

    }

    public function verifica_coordenador(){
        
        $empresa = $_SESSION["idempresa"];

        $this->db->select('u.email as email');
        $this->db->from('tbusuario as u');
        $this->db->join('tbcargos as c', 'c.id = u.fk_idcargos');
        $this->db->where('u.fk_idempresa', $empresa);
        $this->db->where('c.titulo = "Coordenador"');

        return $this->db->get()->row('email');
    }

    public function enviar_email($dados){
        
        $config['protocol'] = 'smtp';
        $config["charset"]  = 'utf-8';
		$config['wordwrap'] = TRUE;
		$config['validate'] = TRUE;

		$config["smtp_crypto"] = "tsl";
		$config["smtp_host"]   = 'bz16.hostgator.com.br';
		$config["smtp_user"]   = "contato@sgtgestaoetecnologia.com.br";
        $config["smtp_pass"]   = "{y22oG#A8ODc";
        $config["smtp_port"]   = "587";

        $config['mailtype'] = 'html';

        $this->email->initialize($config);

        $this->email->from('contato@sgtgestaoetecnologia.com.br', 'SGT - Gestão e tecnologia');
        $this->email->to($dados["email"], $dados["usuario"]);
        $this->email->cc($this->verifica_coordenador());

        $this->email->subject('SGT - Gestão de Documentos');

        if ($dados["tipo"] == "novo") {

            $this->email->message($this->load->view('email/novo_documento', $dados, TRUE));

        } elseif($dados["tipo"] == "pendente"){

            $this->email->message($this->load->view('email/documento_pendente',$dados, TRUE));

        } elseif($dados["tipo"] == "suspenso"){

            $this->email->message($this->load->view('email/documento_suspenso', $dados, TRUE));

        } elseif($dados["tipo"] == "cancelado"){

            $this->email->message($this->load->view('email/documento_cancelado', $dados, TRUE));

        } elseif($dados["tipo"] == "erro"){

            $this->email->message($this->load->view('email/documento_erro', $dados, TRUE));

        } elseif($dados["tipo"] == "retorno"){

            $this->email->message($this->load->view('email/documento_retornado', $dados, TRUE));

        } elseif($dados["tipo"] == "retorno_suspensao"){

            $this->email->message($this->load->view('email/documento_resuspensao', $dados, TRUE));

        } elseif($dados["tipo"] == "novo_usuario"){

            $this->email->message($this->load->view('email/novo_usuario', $dados, TRUE));
            
        }

        $this->email->send();
    }
}
