<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Competencia_model extends CI_Model {    
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * Método para cadastrar as competência
     * Utilizado no controller conf/Competencia.php
     *
     * @param array $dados
     * @return int
     */
    public function cadastar_competencias($dados){
        return $this->db->insert('tbcompetencias', $dados);
    }

    /**
     * Método para listar as competencias
     * Utilizado no controller conf/Competencia.php
     *
     * @param int $empresa
     * @return object
     */
    public function listar_competencias($documento){
        $this->db->select('c.id as id, c.tipo as tipo, c.fk_idetapa as fk_idetapa, c.fk_idusuario as fk_idusuario, c.fk_idcargo as fk_idcargo, e.titulo as etapa');
        $this->db->from('tbcompetencias as c');
        $this->db->join('tbetapa as e', 'e.id = c.fk_idetapa');
        $this->db->where('fk_iddocumento = ', $documento);
        return $this->db->get()->result();
    }

    /**
     * Método para retornar o nome do documento escolhido
     * Utilizado no controller conf/Competencia.php
     *
     * @param int $id
     * @return object
     */
    public function nome_documento($id){
        $this->db->select('titulo');
        $this->db->from('tbdocumento');
        $this->db->where('id = ', $id);
        return $this->db->get()->result();
    }

}
