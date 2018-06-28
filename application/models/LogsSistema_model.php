<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LogsSistema_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    public function cadastrar_log_sistema($dados){
        $this->db->insert('tblog_sistema', $dados);
    }

    /**
     * Método responsável por retornar o titulo de determinada tabela
     * Utilizado nos controller 
     *
     * @param string $tabela
     * @param int $id
     * @return string
     */
    public function seleciona_por_titulo($tabela, $id){
        $this->db->select('titulo');
        $this->db->from($tabela);
        $this->db->where('id = ', $id);
        return $this->db->get()->row('titulo');
    }

}
