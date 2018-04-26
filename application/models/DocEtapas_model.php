<?php
defined('BASEPATH') or exit('No direct script access allowed!');

class DocEtapas_model extends CI_Model{

    public function __construct(){
        parent::__construct();
    }

    /**
     * Função para cadastro de etapas de documentos tbdocumentoetapa
     * Utilizado no controller conf/Documento.php
     *
     * @param array $dados
     * @return int
     */
    public function cadastrar_documento_etapa($dados){
        return $this->db->insert('tbdocumentoetapa', $dados);
    }

    /**
     * Função para listar as etapas de documentos com titulo da etapa
     * Utilizado no controller conf/Documento.php, conf/Competencia.php, e na view config/documento.php
     *
     * @param int $iddocumento
     * @return void
     */
    public function listar_documento_etapa($iddocumento){
        $this->db->select('e.titulo as etapa, de.iddocumento as iddocumento, de.idetapa as idetapa');
        $this->db->from('tbdocumentoetapa as de');
        $this->db->join('tbetapa as e', 'e.id = de.idetapa');
        $this->db->where('de.iddocumento = ', $iddocumento);
        $this->db->order_by('de.ordem asc');
        return $this->db->get('')->result();
    }

    /**
     * Função para listar etapas de documento pela ordem de etapas
     * Utilizado no controller conf/Documento.php 
     *
     * @param int $iddocumento
     * @return int
     */
    public function listar_docetapa($iddocumento){
        $this->db->where('iddocumento =', $iddocumento);
        $this->db->from('tbdocumentoetapa');
        $this->db->order_by('ordem asc');
        return $this->db->get('')->result();
    }

    /**
     * Função para editar etapas de documentos (Geralmente a ordem em que ela será realizada)
     *
     * @param int $iddocumento
     * @param int $idetapa
     * @param array $dados
     * @return int retorna o numero de linhas afetadas
     */
    public function editar_docetapa($iddocumento, $idetapa,$dados){
        $this->db->where('iddocumento = ', $iddocumento);
        $this->db->where('idetapa = ', $idetapa);
        return $this->db->update('tbdocumentoetapa', $dados);
    }

    /**
     * Função para verificar etapas do documentos
     *
     * @param int $iddocumento
     * @param int $idetapa
     * @return object
     */
    public function verifica_docetapa($iddocumento, $idetapa){
        $this->db->where('iddocumento = ', $iddocumento);
        $this->db->where('idetapa = ', $idetapa);
        $this->db->from('tbdocumentoetapa');
        return $this->db->get()->result();
    }

}