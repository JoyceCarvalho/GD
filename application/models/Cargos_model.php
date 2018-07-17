<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cargos_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    /**
     * Médoto para cadastro de cargos
     *
     * @param object $dados
     * @return int retorna o numero de linhas afetadas
     */
     public function cadastrar_cargos($dados){
        $this->db->insert('tbcargos', $dados);
        return $this->db->insert_id();
    }

    /**
     * Função para editar dados dos cargos
     * utilizado somente no controller Cargos.php
     *
     * @param array $dados
     * @param int $id
     * @return int retorna o numero de linhas afetadas (ou não sei lá eu acho kkkk)
     */
    public function editar_cargo($dados, $id){
        
        $this->db->where('id =', $id);
        return $this->db->update('tbcargos', $dados);

    }

    /**
     * Função para listagem de cargos
     * Utilizado no controller Cargos.php, Usuario.php
     *
     * @param int $empresa
     * @return object 
     */
    public function listar_cargos($empresa){

        $this->db->from('tbcargos');
        $this->db->where('fk_idempresa =', $empresa);
        $this->db->order_by('titulo');

        return $this->db->get('')->result();
    }

    /**
     * Função para excluir cargos
     * Utilizado somente no controller Cargos.php
     *
     * @param int $id
     * @return int
     */
    public function excluir_cargo($id){

        $this->db->where("id =", $id);
        return $this->db->delete('tbcargos');

    }

    /**
     * Função para listar dados de determinada linha da tabela
     * Utilizado no controller Cargos.php
     *
     * @param int $id
     * @return object
     */
    public function dados_cargo($id){

        $this->db->from('tbcargos');
        $this->db->where('id =', $id);

        return $this->db->get('')->result();
    }

    /**
     * Função para listar os cargos em formato json
     * Utilizado no controller Competencia.php
     *
     * @param int $empresa
     * @return json
     */
    public function listar_cargos_json($empresa){
        $this->db->select('id as id, titulo as nome');
        $this->db->from('tbcargos');
        $this->db->where('fk_idempresa =', $empresa);
        $this->db->order_by('titulo');

        return json_encode($this->db->get('')->result());
    }

    /**
     * Método para retornar o nome de determinado cargo
     * Utilizado no controller relatorios/Relatorios.php
     *
     * @param int $id
     * @return string
     */
    public function listar_nome_cargo($id){
        
        $this->db->select("titulo as nome");
        $this->db->from('tbcargos');
        $this->db->where('id', $id);
        
        return $this->db->get()->row('nome');
    }

    /**
     * Método responsável por retornar os cargos dos documentos trabalhado para saber o tempo médio de cada um
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $empresa
     * @return object
     */
    public function listar_cargos_tempo($empresa){
        
        $this->db->select("c.id as idcargo, c.titulo as cargo");
        $this->db->from("tbcargos as c");
        $this->db->join("tbcompetencias as comp", "c.id = comp.fk_idcargo");
        $this->db->join("tbdocumentos_cad as dc", "comp.fk_iddocumento = dc.fk_iddocumento");
        $this->db->join('tblog_documentos as ldA', 'dc.id = ldA.documento and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'dc.id = ldB.documento and ldB.descricao = "FINALIZADO"');
        $this->db->where("c.fk_idempresa", $empresa);
        $this->db->group_by("c.id");
        $this->db->order_by("c.titulo asc");
        
        return $this->db->get()->result();
    }


}