<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Etapas_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * Função para cadastro de etapas na tabela tbetapa
     * Utilizada no controller conf/Etapas.php
     *
     * @param array $dados
     * @return int
     */
    public function cadastrar_etapas($dados){
        return $this->db->insert('tbetapa', $dados);
    }

    /**
     * Função responsável por cadastrar os prazos de um novo documento
     * Utilizado no controller documentos/Documento.php
     *
     * @param array $dados
     * @return result retorna se a consulta foi realizada ou não
     */
    public function cadastrar_etapas_prazos($dados){
        
        return $this->db->insert('tbprazoetapa', $dados);

    }
    
    /**
     * Função para retornar os dados de determinada linha da tabela
     * Utilizado somente no controller conf/Etapas.php
     *
     * @param int $id
     * @return object
     */
    public function dados_etapas($id){

        $this->db->from('tbetapa');
        $this->db->where('id = ', $id);

        return $this->db->get('')->result();
        
    }
    
    /**
     * Função para editar etapas
     * Utilizado no controller conf/Documento.php
     *
     * @param array $dados
     * @param int $id
     * @return int
     */
    public function editar_etapas($dados, $id){

        $this->db->where('id = ', $id);
        return $this->db->update('tbetapa', $dados);

    }

    /**
     * Função para excluir etapas
     * utilizado no controller conf/Etapas.php
     *
     * @param int $id
     * @return int
     */
    public function excluir_etapas($id){

        $ativo = array('ativo' => '0');
        $this->db->where('id = ', $id);
        return $this->db->update('tbetapa', $ativo);
        
    }

    /**
     * Função para listar as etapas cadastradas
     * Utilizado no controller conf/Etapas.php, e na view config/documento.php
     *
     * @param int $empresa
     * @return object
     */
    public function listar_etapas($empresa){

        $this->db->from("tbetapa");
        $this->db->where('ativo = 1');
        $this->db->where('fk_idempresa =', $empresa);
        return $this->db->get('')->result();

    }

    /**
     * Função para listar um json com os dados das etapas de determidado documento
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $documento
     * @return json retorna um json com os objetos de dados
     */
    public function listar_etapas_json($documento){
        
        $this->db->select('e.id as id, e.titulo as titulo, de.iddocumento as iddocumento, de.prazo_def as prazo_def');
        $this->db->from('tbetapa as e');
        $this->db->join('tbdocumentoetapa as de', 'de.idetapa = e.id');
        $this->db->where('iddocumento = ', $documento);
        $this->db->order_by('ordem asc');
        $query = $this->db->get();

        return json_encode($query->result());
    }

    /**
     * Método para listar etapas por ordem ascendente
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $documento
     * @return int
     */
    public function listar_etapa_ordem($documento){
        $this->db->select('e.id as id');
        $this->db->from('tbetapa as e');
        $this->db->join('tbdocumentoetapa as de', 'de.idetapa = e.id');
        $this->db->join('tbdocumentos_cad as dc', 'dc.fk_iddocumento = de.iddocumento');
        $this->db->where('dc.id = ', $documento);
        $this->db->order_by('ordem asc');
        $this->db->limit(1);

        return $this->db->get()->row('id');
    }

    /**
     * Método para mostrar o prazo da etapa do documento
     *
     * @param int $documento
     * @param int $etapa
     * @return date retorna a data em fomato mysql date "Y-m-d"
     */
    public function prazo_etapa($documento, $etapa){
        $this->db->select('pe.prazo as prazo');
        $this->db->from('tbprazoetapa as pe');
        $this->db->where('pe.fk_iddocumento = ', $documento);
        $this->db->where('pe.fk_idetapas = ', $etapa);
        $this->db->limit(1);

        return $this->db->get()->row('prazo');
    }

    /**
     * Método para exclusão de prazos da etapa do documento
     *
     * @param int $id id do documento a que se refere o prazo
     * @return int
     */
    public function exclui_prazoetapa($id){
        $this->db->where('fk_iddocumento', $id);
        return $this->db->delete("tbprazoetapa");
    }

}
