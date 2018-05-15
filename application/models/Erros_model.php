<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Erros_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    public function listar_erros($empresa){
        $this->db->from('tberros');
        $this->db->where('fk_idempresa = ', $empresa);
        return $this->db->get()->result();
    }

    public function cadastrar_erros($dados){
        return $this->db->insert('tberros', $dados);
    }
}
