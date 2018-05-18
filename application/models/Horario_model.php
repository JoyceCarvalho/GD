<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Horario_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    
    /**
     * Médoto para cadastro de Horário de trabalho
     * Utilizado somente no controller Horarios.php
     *
     * @param object $dados
     * @return int retorna o numero de linhas afetadas
     */
    public function cadastrar_horario($dados){
        $this->db->insert('tbhorario_trab', $dados);
        return $this->db->insert_id();
    }

    /**
     * Médoto para edição de Horários de trabalho
     * Utilizado somente no controller Horarios.php
     *
     * @param object $dados
     * @param int $id
     * @return int retorna o numero de linhas afetadas
     */
    public function editar_horario($dados, $id){

        $this->db->where("id = ", $id);
        return $this->db->update("tbhorario_trab", $dados);
    }
    
    /**
     * Método para listar todos os horarios referentes a empresa logada
     * Utilizado somente no controller Horários.php
     *
     * @param int $idempresa
     * @return object retorna um objeto de dados
     */
    public function listar_horario($empresa){

        $this->db->from('tbhorario_trab');
        $this->db->where('fk_idempresa =', $empresa);
        $this->db->where('ativo = 1');
        $this->db->order_by('titulo');

        return $this->db->get('')->result();
    }

    /**
     * Método para exclusão de determinado Horário
     * Utilizado somente no controller Horário.php
     *
     * @param int $id
     * @return int retorna o numero de linhas afetadas
     */
    public function excluir_horario($id){

        $this->db->where('id =', $id);
        $dados = array("ativo" => "0");
        return $this->db->update('tbhorario_trab', $dados);

    }

    /**
     * Método de retorno de dados de determinado horário
     *
     * @param int $id
     * @return object retorna um objeto de dados
     */
    public function dados_horario($id){
        
        $this->db->from('tbhorario_trab');
        $this->db->where('id =', $id);
        
        return $this->db->get('')->result();
        
    }

    /**
     * Médoto de conversão de datas para formato TIME do MySQL
     *
     * @param time formato html5
     * @return TIME retorna o formato TIME do MySQL
     */
    public function converte_horas($hora){

        return $hora.":00";

    }

    /**
     * Método para retornar o primeiro grupo de horário cadastrado
     * Utilizado no controller documentos/Transferencia.php
     *
     * @param int $empresa
     * @return object retorna um objeto de dados
     */
    public function verifica_horario($empresa){
        $this->db->from('tbhorario_trab');
        $this->db->where('fk_idempresa =', $empresa);
        $this->db->order_by('id asc');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

}