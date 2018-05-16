<?php
class Timer_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * Médoto responsável por retornar os dados de determinado documento
     * Utilizado no controller documento/Documento.php
     *
     * @param int $protocolo
     * @param int $etapa
     * @return object retorna o objeto com dados
     */
    public function get_timer($protocolo, $etapa){
        $this->db->select('action, timestamp');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad = ', $protocolo);
        $this->db->where('fk_idetapa =', $etapa);
        $this->db->order_by('id');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar o total de linhas referentes ao objeto determinado
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $protocolo
     * @param int $etapa
     * @return int
     */
    public function contador($protocolo, $etapa){
        $this->db->select('count(*) as conta');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad = ', $protocolo);
        $this->db->where('fk_idetapa = ', $etapa);
        $this->db->order_by('id');
        return $this->db->get()->row('conta');
    }

    /**
     * Método responsável por retornar o total de linhas referentes ao documento determinado
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $protocolo
     * @param int $etapa
     * @return string
     */
    public function get_action($protocolo, $etapa){
        $this->db->select('action');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad = ', $protocolo);
        $this->db->where('fk_idetapa =', $etapa);
        $this->db->order_by('id desc');
        return $this->db->get()->row('action');
    }

    /**
     * Método responsável por retornar o total de linhas referentes ao documento determinado
     * Utilizado no controller documentos/Documento.php
     *
     * @param array $dados
     * @return int retorna o numero de linhas afetadas
     */
    public function cadastrar_tempo($dados){
        return $this->db->insert('tbtimer', $dados);
    }
}
