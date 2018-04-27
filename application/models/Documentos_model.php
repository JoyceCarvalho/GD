<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documentos_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * Função responsável por cadastrar os dados dos documentos da tabela tbdocumento
     * Utilizado no controller conf Documento.php
     *
     * @param array $dados
     * @return int retorna o ultimo id inserido
     */
    public function cadastrar_documentos($dados){

        $this->db->insert('tbdocumento', $dados);
        return $this->db->insert_id();

    }

    /**
     * Função responsável por listar os documentos da tabela tbdocumento
     * Utilizada no controller conf/Documento.php
     *
     * @param int $empresa id da empresa
     * @return object retorna os objetos da tabela
     */
    public function listar_documentos($empresa){
        $this->db->select('d.id as id, d.titulo as titulo, g.titulo as grupo, ');
        $this->db->from('tbdocumento as d');
        $this->db->join('tbgrupo as g', "d.fk_idgrupo = g.id");
        $this->db->where('d.fk_idempresa =', $empresa);
        $this->db->order_by('fk_idgrupo asc');
        return $this->db->get('')->result();
    }

    /**
     * Função para editar os dados da tabela tbdocumento
     * Utilizada no controller conf/Documento.php
     *
     * @param array $dados dados para edição
     * @param int $id identificador da linha alterada
     * @return int
     */
    public function editar_documentos($dados, $id){
        $this->db->where("id = ", $id);
        return $this->db->update('tbdocumento', $dados);
    }

    /**
     * Função para excluir os dados da tabela tbdocumento
     * Utilizada no controller conf/Documento.php
     *
     * @param int $id
     * @return int
     */
    public function excluir_documentos($id){
        $this->db->where('id = ', $id);
        return $this->db->delete('tbdocumento');
    }

    /**
     * Função para listar os dados referentes a determinada linha da tabela tbdocumento
     * Utilizada no controller conf/Documento.php
     *
     * @param int $id
     * @return object
     */
    public function dados_documentos($id){
        $this->db->from('tbdocumento');
        $this->db->where('id=', $id);

        return $this->db->get('')->result();
    }

    /**
     * Função para listar os dados da tabela tbdocumentos em formato json
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $grupo
     * @return json retorna um json com os dados
     */
    public function listar_documentos_json($grupo){
        
        $this->db->select('d.id as id, d.titulo as titulo, g.titulo as grupo, ');
        $this->db->from('tbdocumento as d');
        $this->db->join('tbgrupo as g', "d.fk_idgrupo = g.id");
        $this->db->where('d.fk_idgrupo =', $grupo);
        $query = $this->db->get();

        return json_encode($query->result());
    }

    /**
     * Função para cadastrar um novo documento para trabalho na tabela tbdocumentos_cad
     * Utilizado no controller documentos/Documento.php
     *
     * @param array $dados
     * @return int retorna o ultimo id inserido
     */
    public function cadastrar_novo_documento($dados){
        
        $this->db->insert('tbdocumentos_cad', $dados);
        return $this->db->insert_id();

    }

    /**
     * Função responsável por cadastrar os logs de documentos
     * Utilizado pelo controller documentos/Documento.php
     *
     * @param array $dados
     * @return int retorna o numero de linhas afetadas
     */
    public function cadastrar_log_documento($dados){
        return $this->db->insert('tblog_documentos', $dados);
    }
 
    /**
     * Fução reponsável por listar dados de meus documentos
     *
     * @param int $usuario
     * @return object retorna um objeto de dados
     */
    public function listar_meus_documentos_cargos($usuario){
        $this->db->select('dc.protocolo AS protocolo, d.titulo AS documento, g.titulo AS grupo, dc.prazo AS prazo, e.titulo AS etapa, 
        ld.data_hora AS data_criação, u.nome AS nome_usuario');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento AS d', 'dc.fk_iddocumento = d.id');
        $this->db->join('tbgrupo AS g', 'd.fk_idgrupo = g.id');
        $this->db->join('tbdocumentoetapa AS de', 'd.id = de.iddocumento');
        $this->db->join('tbetapa AS e', 'e.id = de.idetapa');
        $this->db->join('tblog_documentos AS ld', 'ld.documento = d.id');
        $this->db->join('tbcompetencias AS comp', 'comp.fk_iddocumento = d.id');
        $this->db->join('tbusuario AS u', 'u.fk_idcargos = comp.fk_idcargo');
        $this->db->where('ld.ultima_etapa = ', '"false"');
        $this->db->where('comp.tipo = ', '"cargo"');
        $this->db->order_by('u.id = ', $usuario);
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar dados de meus documentos
     *
     * @param int $usuario
     * @return object retorna um objeto de dados
     */
    public function listar_meus_documentos_funcionario($usuario){
        $this->db->select('dc.protocolo AS protocolo, d.titulo AS documento, g.titulo AS grupo, dc.prazo AS prazo, e.titulo AS etapa, 
        ld.data_hora AS data_criacao, u.nome AS nome_usuario');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento AS d', 'dc.fk_iddocumento = d.id');
        $this->db->join('tbgrupo AS g', 'd.fk_idgrupo = g.id');
        $this->db->join('tbdocumentoetapa AS de', 'd.id = de.iddocumento');
        $this->db->join('tbetapa AS e', 'e.id = de.idetapa');
        $this->db->join('tblog_documentos AS ld', 'ld.documento = dc.id');
        $this->db->join('tbcompetencias AS c', 'c.fk_iddocumento = d.id');
        $this->db->join('tbusuario AS u', 'u.id = c.fk_idusuario');
        $this->db->where('c.tipo = ', '"funcionario"');
        $this->db->where('ld.ultima_etapa = ', '"false"');
        $this->db->where('u.id = ', $usuario);
        $this->db->order_by('de.ordem');
        return $this->db->get()->result();
    }

}
