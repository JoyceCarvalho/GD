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
    
    /**
     * Método responsável por cadastrar os erros dos documentos
     * Utilizado no controller conf/Erros.php
     *
     * @param array $dados
     * @return int
     */
    public function cadastrar_erros_documento($dados){
        return $this->db->insert('tberros_documentos', $dados);
    }

    /**
     * Método responsável por contar a quantidade de erros de determinado documento
     * Utilizado na view documentos/meus_documentos.php 
     *
     * @param int $idprotocolo
     * @return object
     */
    public function conta_erros($idprotocolo){
        $this->db->select("count(*) as contador");
        $this->db->from('tberros_documentos');
        $this->db->where('fk_iddocumentos', $idprotocolo);
        
        return $this->db->get()->row("contador");
    }

    public function listar_erros_documentos($idprotocolo){
        $this->db->select('er.titulo as titulo_erro, er.tipo as tipo_erro, e.titulo as titulo_etapa, u.nome as usuario_nome, DATE_FORMAT(ed.data_hora, "%d/%m/%Y - %H:%i") as quando');
        $this->db->from('tberros_documentos as ed');
        $this->db->join('tberros as er', 'er.id = ed.fk_iderros');
        $this->db->join('tbetapa as e', 'e.id = ed.fk_idetapa');
        $this->db->join('tbusuario as u', 'u.id = ed.fk_idusuario');
        $this->db->where('ed.fk_iddocumentos', $idprotocolo);
    }
}
