<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LogsSistema_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * Método de cadastro de log do sistema
     * Utilizado em todos os controllers
     *
     * @param array $dados
     * @return int
     */
    public function cadastrar_log_sistema($dados){
        $this->db->insert('tblog_sistema', $dados);
    }

    /**
     * Método responsável por retornar o titulo de determinada tabela
     * Utilizado em todos os controllers
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

    /**
     * Método responsável por retornar o nome do usuário
     * Utilizado no controller conf/Ausencia.php e conf/Ferias.php
     *
     * @param int $id
     * @return string
     */
    public function retorna_usuario($id){
        $this->db->select('nome');
        $this->db->from('tbusuario');
        $this->db->where('id =', $id);
        return $this->db->get()->row('nome');
    }

    /**
     * Método responsável por retornar o nome do usuario relacionado a determinada tabela
     * Utilizado no controller conf/Ausencia.php e conf/Ferias.php 
     *
     * @param string $tabela
     * @param int $id
     * @return string
     */
    public function nome_usuario($tabela, $id){
        $this->db->select('u.nome as nome');
        $this->db->from($tabela);
        $this->db->join('tbusuario as u', "$tabela.fk_idusuario = u.id");
        $this->db->where("$tabela.id", $id);
        return $this->db->get()->row("nome");
    }

    /**
     * Método responsável por retornar o titulo do documento
     * Utilizado no controller conf/Competencia.php
     *
     * @param int $id
     * @return string
     */
    public function retorna_titulo_documento($id){
        $this->db->select('titulo');
        $this->db->from('tbdocumento');
        $this->db->where('id = ', $id);
        return $this->db->get()->row('titulo');
    }

}
