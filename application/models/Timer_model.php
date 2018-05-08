<?php
class Timer_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * Médoto responsável por retornar os dados de determinado documento
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
        $this->db->order_by('id');
        return $this->db->get()->row('action');
    }
}
