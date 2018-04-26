<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feriados_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    /**
     * Método para cadastro de feriados
     * Utilizado no controller Feriados.php
     *
     * @param object $dados
     * @return int retorna o numero de linha afetadas
     */
    public function cadastrar_feriados($dados){

        return $this->db->insert('tbferiados', $dados);

    }

    /**
     * Método para listagem de feriados cadastrados
     * Utilizado no controller Feriados.php
     *
     * @param int $idempresa
     * @return object
     */
    public function listar_feriados($idempresa){

        $this->db->from('tbferiados');
        $this->db->where('fk_idempresa = ', $idempresa);

        return $this->db->get('')->result();
    }

    /**
     * Método para editar feriados cadastrados
     * Utilizado no controller Feriados.php
     *
     * @param array $dados
     * @param int $id
     * @return int 
     */
    public function editar_feriado($dados, $id){

        $this->db->where('id = ', $id);
        return $this->db->update('tbferiados', $dados);
    }

    /**
     * Método para excluir feriados cadastrado
     * Utilizado no controller Feriados.php
     *
     * @param int $id
     * @return int retorna o numero de linhas afetadas
     */
    public function excluir_feriado($id){

        $this->db->where('id = ', $id);
        return $this->db->delete('tbferiados');
    }

    /**
     * Método para retornar dados de determinado feriado
     * Utilizado no controller Feriados.php
     *
     * @param int $id
     * @return object
     */
    public function dados_feriado($id){

        $this->db->from('tbferiados');
        $this->db->where('id = ', $id);

        return $this->db->get('')->result();
    }
}