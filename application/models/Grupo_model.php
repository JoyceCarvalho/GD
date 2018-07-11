<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe responsável pelo CRUD de dados da tabela tbgrupo
 */
class Grupo_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * Método para cadastro de grupos de Documentos
     * Utilizado no controller conf/Grupos.php
     *
     * @param [type] $dados
     * @return void
     */
    public function cadastrar_grupo($dados){
        
        return $this->db->insert('tbgrupo', $dados);

    }

    /**
     * Método para listar grupo de documentos
     * Utilizado no controller conf/Grupos.php e documentos/Documento.php
     *
     * @param int $empresa
     * @return object
     */
    public function listar_grupos($empresa){

        $this->db->from('tbgrupo');
        $this->db->where("fk_idempresa =", $empresa);
        return $this->db->get('')->result();
        
    }

    /**
     * Método para editar grupo de documentos
     * Utilizado somente no controller conf/Grupos.php
     *
     * @param array $dados
     * @param int $id
     * @return int retorna numero de linha afetadas
     */
    public function editar_grupo($dados, $id){

        $this->db->where('id =', $id);
        return $this->db->update('tbgrupo', $dados);

    }

    /**
     * Método para excluir grupo de documentos
     * Utilizado somente no controller conf/Grupos.php
     *
     * @param int $id
     * @return int retorna número de linhas afetadas
     */
    public function excluir_grupo($id){

        $this->db->where('id = ', $id);
        return $this->db->delete('tbgrupo');
    }

    /**
     * Método para mostrar dados de determinado grupo de documentos
     * Utilizado no controller conf/Grupos.php
     *
     * @param int $id
     * @return object
     */
    public function dados_grupo($id){

        $this->db->from("tbgrupo");
        $this->db->where('id = ', $id);

        return $this->db->get('')->result();
    }

    /**
     * Método responsável por retornar os grupos referentes aos documentos finalizados
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $empresa
     * @return object
     */
    public function grupo_por_documento($empresa){
        
        $this->db->select("g.id as idgrupo, d.titulo as titulo_grupo, dc.id as idprotocolo");
        $this->db->from("tbgrupo as g");
        $this->db->join("tbdocumento as d", "d.fk_idgrupo = g.id");
        $this->db->join("tbdocumentos_cad as dc", "dc.fk_iddocumento = d.id");
        $this->db->join("tblog_documentos as ld", "ld.documento = dc.id AND ld.descricao = 'FINALIZADO'");
        $this->db->where("g.fk_idempresa", $empresa);
        $this->db->group_by("g.id");
        $this->db->order_by("g.titulo");

        return $this->db->get()->result();

    }
}
