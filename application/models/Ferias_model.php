<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ferias_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * Método para cadastro de férias de funcionários
     * Utilizado no controller conf/Ferias.php
     *
     * @param array $dados
     * @return int retorna o numero de linhas afetadas
     */
    public function cadastrar_ferias($dados){
        return $this->db->insert('tbferias_func', $dados);
    }

    /**
     * Método para listar férias de funcionários
     * Utilizado no controller conf/Ferias.php
     *
     * @param int $empresa
     * @return object
     */
    public function listar_ferias($empresa){
        $this->db->from('tbferias_func');
        $this->db->where('fk_idempresa = ', $empresa);
        return $this->db->get('')->result();
    }

    /**
     * Método para editar férias de funcionários
     * Utilizado no controller conf/Ferias.php
     *
     * @param array $dados
     * @param int $id
     * @return int
     */
    public function editar_ferias($dados, $id){
        $this->db->where('id = ', $id);
        return $this->db->update('tbferias_func', $dados);
    }

    /**
     * Método para excluir férias de funcionários
     * Utilizado no controller conf/Ferias.php
     *
     * @param [type] $id
     * @return void
     */
    public function excluir_ferias($id){
        $this->db->where('id =', $id);
        return $this->db->delete('tbferias_func');
    }

    /**
     * Método para listar dados de férias de funcionário
     * Utlizado no controller conf/Ferias.php
     *
     * @param int $id
     * @return object
     */
    public function dados_ferias($id){
        $this->db->from('tbferias_func');
        $this->db->where('id =', $id);
        return $this->db->get()->result();
    }

}
