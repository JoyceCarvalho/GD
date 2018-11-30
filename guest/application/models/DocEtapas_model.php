<?php
defined('BASEPATH') or exit('No direct script access allowed!');

class DocEtapas_model extends CI_Model{

    public function __construct(){
        parent::__construct();
    }

    /**
     * Método para cadastro de etapas de documentos tbdocumentoetapa
     * Utilizado no controller conf/Documento.php
     *
     * @param array $dados
     * @return int
     */
    public function cadastrar_documento_etapa($dados){
        return $this->db->insert('tbdocumentoetapa', $dados);
    }

    /**
     * Método para listar as etapas de documentos com titulo da etapa
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
     * Método para excluir etapas não utilizadas mais no documento
     * Utilizada no controller conf/Documentos.php
     *
     * @return void
     */
    public function excluir_documentos($iddocumento){
        $this->db->where('iddocumento = ', $iddocumento);
        return $this->db->delete('tbdocumentoetapa');
    }

    /**
     * Método para listar etapas de documento pela ordem de etapas
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
     * Método para editar etapas de documentos (Geralmente a ordem em que ela será realizada)
     * Utilizado no controller conf/Documento.php
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
     * Método para verificar etapas do documentos
     * Utilizado no controller conf/Documento.php
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

    /**
     * Método para retornar a ultima etapa do documento
     * Utilizado na view documentos/meus_documento.php
     *
     * @param int $documento
     * @return int retorna o id da etapa
     */
    public function ultima_etapa($documento){
        $this->db->select('idetapa');
        $this->db->from('tbdocumentoetapa');
        $this->db->where('iddocumento = ', $documento);
        $this->db->order_by('ordem desc');
        $this->db->limit(1);
        return $this->db->get('')->row('idetapa');
    }

    /**
     * Método responsável por retornar qual ordem se encontra o documento
     * Utilizado no controller documentos/Transferencia.php
     *
     * @param int $documento
     * @param int $etapa
     * @return int retorna qual ordem se encontra o documento
     */
    public function etapa_atual($documento, $etapa){
        $this->db->select('ordem');
        $this->db->from('tbdocumentoetapa');
        $this->db->where('iddocumento', $documento);
        $this->db->where('idetapa', $etapa);
        $this->db->limit(1);
        return $this->db->get()->row('ordem');
    }

    /**
     * Método responsável por retornar o proxima etapa do documento
     * Utilizado no controller documentos/Transferencia.php
     *
     * @param int $documento
     * @param int $ordem
     * @return int retorna o id da proxima etapa
     */
    public function proxima_etapa($documento, $ordem){
        $this->db->select('idetapa');
        $this->db->from('tbdocumentoetapa');
        $this->db->where('iddocumento', $documento);
        $this->db->where('ordem', $ordem);
        $this->db->limit(1);
        return $this->db->get()->row('idetapa');
    }

    /**
     * Método responsável por retornar o titulo das etapas do documento
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $idprotocolo
     * @return json retorna um json com os dados
     */
    public function listar_etapa_documento_json($idprotocolo){
        $this->db->select("e.id as id, e.titulo as titulo");
        $this->db->from("tblog_documentos as ld");
        $this->db->join("tbetapa as e", "e.id = ld.etapa");
        $this->db->join("tbdocumentoetapa as de", "e.id = de.idetapa");
        $this->db->where('ld.documento', $idprotocolo);
        $this->db->group_by("e.id");
        $this->db->order_by("de.ordem asc");

        return json_encode($this->db->get()->result());
    }

     /**
     * Método responsável por retornar o numero de etapas por documento
     * Utilizado no controller relatorios/Imprimir.php
     * e nas views relatorios/imprimir/relatorios_tempo_mensa.php, relatorios/imprimir/relatorios_tempo.php, relatorios/imprimir/tempo_filtro_documento.php 
     * relatorios/imprimir/tempo_filtro_grupo.php e relatorios/tempo_medio.php
     *
     * @param int $documento
     * @return int
     */
    public function qnt_etapas_por_documento($documento){
        $this->db->select('count(de.idetapa) qnt_etapas');
        $this->db->from('tbdocumentoetapa as de');
        $this->db->join('tbdocumentos_cad as dc', 'de.iddocumento = dc.fk_iddocumento');
        $this->db->where('dc.id', $documento);
        
        return $this->db->get()->row('qnt_etapas');
    }

}