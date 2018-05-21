<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Classe responsével pelo CRUD da tabela tbcompetência
 */
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

    /**
     * Método responsavel por retornar o usuario especifico da etapa do documento
     *
     * @param int $documento
     * @param int $etapa
     * @return int retorna o id do usuario
     */
    public function competencia_user($documento,$etapa){
        $this->db->select('fk_idusuario');
        $this->db->from('tbcompetencias');
        $this->db->where('fk_iddocumento =', $documento);
        $this->db->where('fk_idetapa = ', $etapa);
        return $this->db->get()->row('fk_idusuario');
    }

    /**
     * Método responsável por verificar os usuarios aptos para direcionar documentos
     * Utilizado no controller documentos/Transferencia.php
     *
     * @param int $documento
     * @param int $etapa
     * @return int retorna a quantidade de linhas retornadas
     */
    public function verifica_usuario_apto($documento, $etapa){
        //Subquery
        $this->db->select('fk_idusuario');
        $this->db->from('tbferias_func');
        $subquery = $this->db->get_compiled_select();

        //query
        $this->db->select('count(*) as total');
        $this->db->from('tbcompetencias');
        $this->db->where('fk_iddocumento', $documento);
        $this->db->where('fk_idetapa', $etapa);
        $this->db->where("fk_idusuario not in($subquery)", NULL, FALSE);
        
        return $this->db->get('')->row('total');
    }

}
