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
     * Exclui as competencias cadastradas para cadastrar novas
     * Utilizado no controller conf/Competencia.php 
     *
     * @param int $iddocumento
     * @return int
     */
    public function excluir_compentecias($iddocumento){
        $this->db->where('fk_iddocumento', $iddocumento);
        return $this->db->delete('tbcompetencias');
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
        $this->db->join('tbdocumentoetapa as de', 'de.iddocumento = c.fk_iddocumento and de.idetapa = c.fk_idetapa');
        $this->db->where('fk_iddocumento = ', $documento);
        $this->db->order_by('de.ordem asc');
        
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
     * Utilizado no controller documentos/Transferencia.php, documentos/Documento.php
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
     * Método responsável por verificar quantos são os usuarios aptos para direcionar documentos
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
        $this->db->where('NOW() > dia_inicio');
        $this->db->where('NOW() < dia_fim');
        $subquery = $this->db->get_compiled_select();

        //Subquery2
        $this->db->select('fk_idusuario');
        $this->db->from('tbausencia');
        $this->db->where('NOW() >= dia_inicio');
        $this->db->where('NOW() <= dia_fim');
        $subquery2 = $this->db->get_compiled_select();

        //query
        $this->db->select('count(*) as total');
        $this->db->from('tbcompetencias');
        $this->db->where('fk_iddocumento', $documento);
        $this->db->where('fk_idetapa', $etapa);
        $this->db->where("fk_idusuario not in($subquery)");
        $this->db->where("fk_idusuario not in($subquery2)");
        
        return $this->db->get('')->row('total');
    }

    /**
     * Método para retornar todos os usuários aptos a receber o documento
     * Utilizado no controller documentos/Transferencia.php 
     *
     * @param int $documento
     * @param int $etapa
     * @param date $dataAtual
     * @return object retorna um objeto de dados
     */
    public function usuario_apto($documento, $etapa, $dataAtual){
        //subquery1
        $this->db->select('fk_idusuario');
        $this->db->from('tbausencia');
        $this->db->where('dia_inicio <= NOW()');
        $this->db->where('dia_fim >= NOW()');
        $subquery1 = $this->db->get_compiled_select();

        //subquery2
        $this->db->select('fk_idusuario');
        $this->db->from('tbferias_func');
        $this->db->where('dia_inicio <', $dataAtual);
        $this->db->where('dia_fim >', $dataAtual);
        $subquery2 = $this->db->get_compiled_select();

        //query main
        $this->db->from('tbcompetencias');
        $this->db->where('fk_iddocumento', $documento);
        $this->db->where('fk_idetapa', $etapa);
        $this->db->where("fk_idusuario not in ($subquery1)");
        $this->db->where("fk_idusuario not in ($subquery2)");
        
        return $this->db->get('')->result();

    }

    /**
     * Método para retornar a quantidade de registros cadastrados
     * Utilizado no controller conf/Competencias.php 
     *
     * @param int $documento
     * @return int
     */
    public function retorna_cadastrados($documento){
        $this->db->from('tbcompetencias');
        $this->db->where('fk_iddocumento', $documento);
        return $this->db->count_all_results();
    }

}
