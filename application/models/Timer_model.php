<?php
class Timer_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    public function get_timer($protocolo, $etapa){
        $this->db->select('action, timestamp');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad = ', $protocolo);
        $this->db->where('fk_idetapa =', $etapa);
        return $this->db->get()->result();
    }
}
