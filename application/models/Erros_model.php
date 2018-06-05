<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Erros_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * Método para cadastrar erros
     * Utilizado no controller conf/Erros.php
     *
     * @param array $dados
     * @return int
     */
    public function cadastrar_erros($dados){
        return $this->db->insert('tberros', $dados);
    }
    
    /**
     * Método para listar erros
     * Utilizado no controller conf/Erros.php
     *
     * @param id $empresa
     * @return object retorna um objeto de dados
     */
    public function listar_erros($empresa){
        $this->db->from('tberros');
        $this->db->where('fk_idempresa = ', $empresa);
        return $this->db->get()->result();
    }

    /**
     * Método para alteração de erro
     * Utilizado no controller conf/Erros.php 
     *
     * @param int $id
     * @param array $dados
     * @return int
     */
    public function editar_erro($id, $dados){
        $this->db->where("id =", $id);
        return $this->db->update("tberros", $dados);
    }

    /**
     * Método para exclusão de erro
     * Utilizado no controller conf/Erros.php
     *
     * @param int $id
     * @return int
     */
    public function deleta_erro($id){
        $this->db->where('id = ', $id);
        return $this->db->delete("tberros");
    }

    /**
     * Método para retorno de dados de um determinadoerro
     * Utilizado no controller conf/Erros.php
     *
     * @param int $id
     * @return object
     */
    public function dados_erro($id){
        $this->db->from('tberros');
        $this->db->where('id = ', $id);
        return $this->db->get()->result();
    }

    /**
     * Método para listar erros
     * Utilizado no controller conf/Erros.php
     *
     * @param id $empresa
     * @return json retorna um json de dados
     */
    public function listar_erros_json($empresa){
        $this->db->from('tberros');
        $this->db->where('fk_idempresa = ', $empresa);
        return json_encode($this->db->get()->result());
    }
    
}
