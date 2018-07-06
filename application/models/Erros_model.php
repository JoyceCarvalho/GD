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
     * Método de cadastro de tipos de erro
     * Utilizado no controller conf/Erros.php
     *
     * @param array $dados
     * @return int
     */
    public function cadastrar_tipo_erro($dados){
        $this->db->insert('tberros_tipo', $dados);
        return $this->db->insert_id();
    }
    
    /**
     * Método para listar erros
     * Utilizado no controller conf/Erros.php
     *
     * @param id $empresa
     * @return object retorna um objeto de dados
     */
    public function listar_erros($empresa){
        $this->db->select('e.id as id, e.titulo as titulo, et.titulo as tipo_erro');
        $this->db->from('tberros as e');
        $this->db->join('tberros_tipo as et', 'et.id = e.fk_idtipo');
        $this->db->where('e.fk_idempresa = ', $empresa);
        return $this->db->get()->result();
    }

    /**
     * Método para listar tipos de erros de determinada empresa
     * Utilizado no controller Erros.php 
     *
     * @param int $empresa
     * @return object
     */
    public function listar_tipo_erros($empresa){
        $this->db->from('tberros_tipo');
        $this->db->where('fk_idempresa', $empresa);
        $this->db->order_by('titulo desc');
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
        $this->db->select('e.id as id, e.titulo as titulo, et.titulo as tipo');
        $this->db->from('tberros as e');
        $this->db->join('tberros_tipo as et', 'et.id = e.fk_idtipo');
        $this->db->where('e.fk_idempresa = ', $empresa);
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

    /**
     * Método responsável por retornar os erros de determinado documento (Quando houver)
     * Utilizado em todos os controller da pasta documentos
     *
     * @param int $idprotocolo
     * @return json
     */
    public function listar_erros_documentos($idprotocolo){
        
        $this->db->select('er.titulo as titulo_erro, et.titulo as tipo_erro, e.titulo as titulo_etapa, u.nome as usuario_nome, DATE_FORMAT(ed.data_hora, "%d/%m/%Y - %H:%i") as quando, ed.descricao as descricao');
        $this->db->from('tberros_documentos as ed');
        $this->db->join('tberros as er', 'er.id = ed.fk_iderros');
        $this->db->join('tberros_tipo as et', 'et.id = er.fk_idtipo');
        $this->db->join('tbetapa as e', 'e.id = ed.fk_idetapa');
        $this->db->join('tbusuario as u', 'u.id = ed.fk_idusuario');
        $this->db->where('ed.fk_iddocumentos', $idprotocolo);

        return json_encode($this->db->get()->result());
    }
}
