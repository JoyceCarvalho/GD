<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Ausencia_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * Função cadastro ausencia de funcionários na tabela tbausencia
     * Utilizado no controller conf/Ausencia.php 
     *
     * @param array $dados
     * @return int
     */
    public function cadastrar_ausencia($dados){
        return $this->db->insert('tbausencia', $dados);
    }

    /**
     * Função para listar a ausencia de funcionarios cadastradas
     * Utilizado no controller conf/Ausencia.php
     *
     * @param int $empresa
     * @return int
     */    
    public function listar_ausencia($empresa){
        $this->db->from('tbausencia');
        $this->db->where('fk_idempresa = ', $empresa);
        return $this->db->get()->result();
    }

    /**
     * Função editar ausência de funcionário
     * Utilizado somente no controller conf/Ausencia.php
     *
     * @param array $dados
     * @param int $id
     * @return int
     */
    public function editar_ausencia($dados, $id){
        $this->db->where('id = ',$id);
        return $this->db->update('tbausencia', $dados);
    }

    /**
     * Função para excluir uma ausência de funcionário (por que vai que neh)
     * Utilizado somente no controller conf/Ausencia.php
     *
     * @param int $id
     * @return int
     */
    public function excluir_ausencia($id){
        $this->db->where('id = ', $id);
        return $this->db->delete('tbausencia');
    }

    /**
     * Função para retornar os dados de uma determinada linha da tabela
     * Utilizado no controller conf/Ausencia.php
     *
     * @param int $id
     * @return int
     */
    public function dados_ausencia($id){
        $this->db->from('tbausencia');
        $this->db->where('id = ', $id);
        return $this->db->get()->result();
    }
}
