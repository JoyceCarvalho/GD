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
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $usuario
     * @return object retorna um objeto de dados
     */
    public function listar_meus_documentos_cargos($usuario){
        $this->db->select('d.id as iddocumento, e.id as idetapa, dc.protocolo AS protocolo, d.titulo AS documento, g.titulo AS grupo, dc.prazo AS prazo, 
        e.titulo AS etapa, ldA.data_hora AS data_criacao, u.nome AS nome_usuario, de.ordem as ordem, dc.id as idprotocolo');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbcompetencias as c', 'c.fk_iddocumento = d.id and c.tipo = "cargo"');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.ultima_etapa = "true"');
        $this->db->join('tbusuario as u', 'u.fk_idcargos = c.fk_idcargo', 'left');
        $this->db->join('tbetapa as e', 'e.id = ldB.etapa', 'left');
        $this->db->join('tbdocumentoetapa as de', 'de.iddocumento = d.id and de.idetapa = ldB.etapa');
        $this->db->where("ldB.usuario = 0");
        $this->db->where("u.id = $usuario");
        $this->db->order_by('de.ordem');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar dados de meus documentos
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $usuario
     * @return object retorna um objeto de dados
     */
    public function listar_meus_documentos_funcionario($usuario){
        $this->db->select('d.id as iddocumento, e.id as idetapa, dc.protocolo AS protocolo, d.titulo AS documento, g.titulo AS grupo, dc.prazo AS prazo, 
        e.titulo AS etapa, ldA.data_hora AS data_criacao, u.nome AS nome_usuario, de.ordem as ordem, dc.id as idprotocolo');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.ultima_etapa = "true"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->join('tbetapa as e', 'e.id = ldB.etapa', 'left');
        $this->db->join('tbdocumentoetapa as de', 'de.iddocumento = d.id and de.idetapa = ldB.etapa');
        $this->db->where("ldB.usuario = $usuario");
        $this->db->order_by('de.ordem');
        return $this->db->get()->result();
    }

    /**
     * Método para pegar o id do documento
     * Utilizado no controller documento/Documento.php
     *
     * @param int $caddoc
     * @return int retorna o id do documento
     */
    public function documento_id($caddoc){
        $this->db->select('d.id as id');
        $this->db->from('tbdocumento as d');
        $this->db->join('tbdocumentos_cad as dc', "d.id = dc.fk_iddocumento");
        $this->db->where('dc.id = ', $caddoc);
        return $this->db->get()->row('id');
    }

    /**
     * Método responsável por retornar a etapa atual do documento cadastrado
     * Utilizado no controller Documento/documentos.php
     *
     * @param int $documento
     * @return int retorna o id da etapa
     */
    public function etapa_documento($documento){
        $this->db->select('etapa');
        $this->db->from('tblog_documentos');
        $this->db->where('documento = ', $documento);
        $this->db->where('ultima_etapa = ', 'true');
        $this->db->limit(1);
        return $this->db->get()->row('etapa');
    }

    /**
     * Método responsável por retornar o usuario responsável pelo documento cadastrado
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $documento
     * @return int retorna o id do usuario
     */
    public function usuario_documento($documento){
        $this->db->select('usuario');
        $this->db->from('tblog_documentos');
        $this->db->where('documento = ', $documento);
        $this->db->where('ultima_etapa = ', 'true');
        $this->db->limit(1);
        return $this->db->get()->row('usuario');
    }

    /**
     * Método responsável por retornar os dados do documento em execução
     * Utilizado no controller documentos/Transferencia.php
     *
     * @param int $idprotocolo
     * @return object retorna um objeto de dados
     */
    public function documento_tranferencia($idprotocolo){
        $this->db->from('tblog_documentos');
        $this->db->where('documento =', $idprotocolo);
        $this->db->where('ultima_etapa =', 'true');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

}
