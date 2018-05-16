<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Erros_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    public function cadastrar_erros($dados){
        return $this->db->insert('tberros', $dados);
    }
    
    public function listar_erros($empresa){
        $this->db->from('tberros');
        $this->db->where('fk_idempresa = ', $empresa);
        return $this->db->get()->result();
    }

    public function editar_erro($id, $dados){
        $this->db->where("id =", $id);
        return $this->db->update("tberros", $dados);
    }

    public function deleta_erro($id){
        $this->db->where('id = ', $id);
        return $this->db->delete("tberros");
    }

    public function dados_erro($id){
        $this->db->from('tberros');
        $this->db->where('id = ', $id);
        return $this->db->get()->result();
    }
    
}
