<?php
defined('BASEPATH') or exit('No direct script allowed');

class Filtros_model extends CI_Model {
    
    function __construct(){
        parent::__construct();
    }

    /**
     * Método responsável por retornar todos os Grupos dos documentos finalizados
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $empresa
     * @return object
     */
    public function listar_grupo_documentos_final($empresa){
        $this->db->select('g.id as idgrupo, g.titulo as titulo_grupo');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->group_by('g.titulo');
        $this->db->order_by("g.titulo asc");
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar o resultado do filtro do grupo do documento
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $empresa
     * @param int $filtro
     * @return object
     */
    public function resultado_filtro_grupo($empresa, $filtro){
        $this->db->select('dc.id as idprotocolo, dc.protocolo AS protocolo, d.id as iddocumento, d.titulo AS documento, g.titulo AS grupo, 
        DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.nome AS nome_usuario, DATE_FORMAT(ldB.data_hora, "%d/%m/%Y") as data_finalizacao, 
        DATE_FORMAT(dc.prazo, "%d/%m/%Y") as prazo_documento, g.id as id_grupo');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->where('g.id', $filtro);
        $this->db->order_by("ldA.data_hora asc");
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar o resultado do filtro do grupo do documento por determinado mes
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $empresa
     * @param int $filtro
     * @return object
     */
    public function resultado_filtro_grupo_mesano($empresa, $filtro, $mesano){
        $this->db->select('dc.id as idprotocolo, dc.protocolo AS protocolo, d.id as iddocumento, d.titulo AS documento, g.titulo AS grupo, 
        DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.nome AS nome_usuario, DATE_FORMAT(ldB.data_hora, "%d/%m/%Y") as data_finalizacao, 
        DATE_FORMAT(dc.prazo, "%d/%m/%Y") as prazo_documento, g.id as id_grupo');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->where("DATE_FORMAT(ldB.data_hora, '%Y-%m') = '$mesano'");
        $this->db->where('g.id', $filtro);
        $this->db->order_by("ldA.data_hora asc");
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar todos os documentos de protocolos finalizados
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $empresa
     * @return object
     */
    public function listar_documentos_finalizados($empresa){
        $this->db->select('d.id as iddocumento, d.titulo as titulo_documento');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->group_by('d.titulo');
        $this->db->order_by("d.titulo asc");
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar todos os documentos filtrados pelo usuario
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $empresa
     * @param int $filtro
     * @return object
     */
    public function resultados_filtro_documentos($empresa, $filtro){
        $this->db->select('dc.id as idprotocolo, dc.protocolo AS protocolo, d.id as iddocumento, d.titulo AS documento, g.titulo AS grupo, 
        DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.nome AS nome_usuario, DATE_FORMAT(ldB.data_hora, "%d/%m/%Y") as data_finalizacao, 
        DATE_FORMAT(dc.prazo, "%d/%m/%Y") as prazo_documento');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->where('d.id', $filtro);
        $this->db->order_by("ldA.data_hora asc");
        return $this->db->get()->result();
    }

    /**
     * Método de retorno de dados filtrados da listagem
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $empresa
     * @param int $grupo
     * @param int $documento
     * @return object
     */
    public function resultado_filtro_grupo_e_doc($empresa, $documento, $grupo){
        $this->db->select('dc.id as idprotocolo, dc.protocolo AS protocolo, d.id as iddocumento, d.titulo AS documento, g.titulo AS grupo, 
        DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.nome AS nome_usuario, DATE_FORMAT(ldB.data_hora, "%d/%m/%Y") as data_finalizacao, 
        DATE_FORMAT(dc.prazo, "%d/%m/%Y") as prazo_documento');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->where('g.id', $grupo);
        $this->db->where('d.id', $documento);
        $this->db->order_by("ldA.data_hora asc");
        return $this->db->get()->result();
    }

    
    /**
     * Método responsável por retornar os dados referentes aos filtros na listagem de tempo médio
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param date $mesano
     * @param int $grupo
     * @param int $documento
     * @return object
     */
    public function filtro_tempo_mes_grupo_doc($mesano, $grupo, $documento){
        $data = '"'.$mesano.'-%"';
        $this->db->select('dc.id as idprotocolo, dc.protocolo AS protocolo, d.id as iddocumento, d.titulo AS documento, g.titulo AS grupo, 
        DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.nome AS nome_usuario, DATE_FORMAT(ldB.data_hora, "%d/%m/%Y") as data_finalizacao, 
        DATE_FORMAT(dc.prazo, "%d/%m/%Y") as prazo_documento');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where("ldB.data_hora like $data");
        $this->db->where('g.id', $grupo);
        $this->db->where('d.id', $documento);
        $this->db->order_by("ldA.data_hora asc");
        return $this->db->get()->result();
    }

    /**
     * Método responsavel por retornar os dados de acordo com o filtro de grupo de tempo médio
     * Utilizado no controller relatorios/Relatorios.php
     *
     * @param int $grupo
     * @return object
     */
    public function filtro_tempo_grupo($grupo){
        $this->db->select('dc.id as idprotocolo, dc.protocolo AS protocolo, d.id as iddocumento, d.titulo AS documento, g.titulo AS grupo, 
        DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.nome AS nome_usuario, DATE_FORMAT(ldB.data_hora, "%d/%m/%Y") as data_finalizacao, 
        DATE_FORMAT(dc.prazo, "%d/%m/%Y") as prazo_documento');
        $this->db->from('tbdocumentos_cad as dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where('g.id', $grupo);
        $this->db->order_by("ldA.data_hora asc");
        return $this->db->get()->result();
    }

    /**
     * Método de retorno do protocolos pelo filtro mensal e de grupo
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param date $mesano
     * @param int $grupo
     * @return object
     */
    public function filtro_tempo_mensal_grupo($mesano, $grupo){
        $data = '"'.$mesano.'-%"';
        $this->db->select('dc.id as idprotocolo, dc.protocolo AS protocolo, d.id as iddocumento, d.titulo AS documento, g.titulo AS grupo, 
        DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.nome AS nome_usuario, DATE_FORMAT(ldB.data_hora, "%d/%m/%Y") as data_finalizacao, 
        DATE_FORMAT(dc.prazo, "%d/%m/%Y") as prazo_documento');
        $this->db->from('tbdocumentos_cad as dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where('g.id', $grupo);
        $this->db->where("ldB.data_hora like $data");
        $this->db->order_by("ldA.data_hora asc");
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar os dados filtrados mes e documentos
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param date $mesano
     * @param int $documento
     * @return object
     */
    public function filtro_tempo_mensal_documento($mesano, $documento){
        $data = '"'.$mesano.'-%"';
        $this->db->select('dc.id as idprotocolo, dc.protocolo AS protocolo, d.id as iddocumento, d.titulo AS documento, g.titulo AS grupo, 
        DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.nome AS nome_usuario, DATE_FORMAT(ldB.data_hora, "%d/%m/%Y") as data_finalizacao, 
        DATE_FORMAT(dc.prazo, "%d/%m/%Y") as prazo_documento');
        $this->db->from('tbdocumentos_cad as dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where('d.id', $documento);
        $this->db->where("ldB.data_hora like $data");
        $this->db->order_by("ldA.data_hora asc");
        return $this->db->get()->result();
    }


}
