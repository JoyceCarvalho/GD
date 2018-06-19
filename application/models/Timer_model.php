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

    /**
     * Método responsável por retornar os timers correspondentes a esse protocolo
     * Utilizado no controller relatorios/Relatorios.php e relatorios/Imprimir.php
     *
     * @param int $protocolo
     * @return object
     */
    public function listar_timer($protocolo){
        $this->db->select('action, timestamp');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad = ', $protocolo);
        $this->db->order_by('id asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar o tempo desenvolvido por cada etapa
     * Utilizado no controller relatorios/Imprimir.php 
     *
     * @param int $protocolo
     * @return object
     */
    public function timer_etapa($protocolo){
        
        $this->db->select('t.fk_idetapa as idetapa, e.titulo as etapa_titulo, u.nome as nome_usuario');
        $this->db->from('tbtimer as t');
        $this->db->join('tbetapa as e', 'e.id = t.fk_idetapa');
        $this->db->join('tbusuario as u', 'u.id = t.fk_idusuario');
        $this->db->where('t.fk_iddoccad', $protocolo);
        $this->db->group_by('t.fk_idetapa');
        
        return $this->db->get()->result();

    }

    /**
     * Método responsável por listar o tempo desenvolvido por usuario nas etapas
     * Utilizado no controller relatorios/Imprimir.php 
     *
     * @param int $protocolo
     * @param int $etapa
     * @return object
     */
    public function timer_responsavel($protocolo){
        
        $this->db->select('t.fk_idetapa as idetapa, t.fk_idusuario as idusuario, u.nome as nome_usuario');
        $this->db->from('tbtimer as t');
        $this->db->join('tbusuario as u', 'u.id = t.fk_idusuario');
        $this->db->where('t.fk_iddoccad', $protocolo);
        $this->db->group_by('t.fk_idusuario');
        $this->db->order_by('u.nome');

        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar o tempo do documento em determinada etapa
     * Utilizado no controller relatorios/Imprimir.php 
     *
     * @param int $protocolo
     * @param int $etapa
     * @return object
     */
    public function tempo_por_etapa($protocolo, $etapa){
        $this->db->select('action, timestamp');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad = ', $protocolo);
        $this->db->where('fk_idetapa', $etapa);
        $this->db->order_by('id asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar o tempo do documento com determinado usuário
     * Utilizado no controller relatorios/Imprimir.php
     *
     * @param int $protocolo
     * @param int $responsavel
     * @return object
     */
    public function tempo_por_responsavel($protocolo, $responsavel){
        $this->db->select('action, timestamp');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad', $protocolo);
        $this->db->where('fk_idusuario', $responsavel);
        $this->db->order_by('id asc');
        return $this->db->get()->result();
    }
}
