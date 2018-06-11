<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documentos_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * Função responsável por cadastrar os dados dos documentos da tabela tbdocumento
     * Utilizado no controller conf/Documento.php
     *
     * @param array $dados
     * @return int retorna o ultimo id inserido
     */
    public function cadastrar_documentos($dados){

        $this->db->insert('tbdocumento', $dados);
        return $this->db->insert_id();

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
     * Método responsável por cadastrar os logs de documentos
     * Utilizados pelo controller documentos/Documento.php e documentos/Transferencia.php e documentos/Finalizar.php
     *
     * @param array $dados
     * @return int retorna o numero de linhas afetadas
     */
    public function cadastrar_log_documento($dados){
        return $this->db->insert('tblog_documentos', $dados);
    }
    
    /**
     * Método responsável por cadastrar os dados da tabela tblog_documentos_tempo
     * Utilizado no controller documentos/Transferencia.php 
     *
     * @param array $dados
     * @return int
     */     
    public function cadastrar_documento_tempo($dados){
        return $this->db->insert('tblog_documentos_tempo', $dados);
    }

    /**
     * Método para cadastro do cancelamento do documento
     * Utilizado no controller documentos/Documento.php
     *
     * @param array $dados
     * @return int
     */
    public function cadastrar_cancelamento($dados){
        return $this->db->insert('tbcancelamento', $dados);
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
     * Método para retornar os dados do documento a ser editado
     *
     * @param int $id
     * @return object
     */
    public function dados_documento_cad($id){
        $this->db->select("dc.protocolo as protocolo, dc.id as id, d.fk_idgrupo as grupo, d.titulo as documento_nome, d.id as iddocumento");
        $this->db->from('tbdocumentos_cad as dc');
        $this->db->join('tbdocumento as d', "d.id = dc.fk_iddocumento");
        $this->db->where('dc.id', $id);
        return $this->db->get()->result();
    }

    public function dados_documento_finalizado($idprotocolo){
        $this->db->select('dc.id as idprotocolo, dc.protocolo as protocolo, d.id as iddocumento, d.titulo as titulo_documento, g.titulo as titulo_grupo, 
        DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") as data_criacao, DATE_FORMAT(dc.prazo, "%d/%m/%Y") as prazo_documento, DATE_FORMAT(ldB.data_hora, "%d/%m/%Y") as data_finalizacao
        u.nome as nome_usuario');
        
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
     * Método responsável por retornar a quantidade de documentos por usuários
     * Utilizado no controller documentos/Documentos.php e documentos/Transferencia.php
     *
     * @param array $usuarios
     * @return int
     */
    public function documento_por_usuario($usuarios){
        $this->db->select("usuario, count(*) as 'quantidade_documentos'");
        $this->db->from('tblog_documentos');
        $this->db->where("usuario in ($usuarios)");
        $this->db->where('ultima_etapa', "true");
        $this->db->group_by('usuario');
        $this->db->order_by("'quantidade_documentos' asc");
        $this->db->limit(1);

        return $this->db->get('')->row('usuario');
    }

    /**
     * Método responsável por retornar os dados do documento em execução
     * Utilizado no controller documentos/Transferencia.php
     *
     * @param int $idprotocolo
     * @return object retorna um objeto de dados
     */
    public function documento_transferencia($idprotocolo){
        $this->db->from('tblog_documentos');
        $this->db->where('documento =', $idprotocolo);
        $this->db->where('ultima_etapa =', 'true');
        $this->db->limit(1);
        return $this->db->get()->row();
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
     * Método para editar os dados de ultima etapa do documento cadastrado
     * Utilizado no controller documento/Transferencia.php 
     *
     * @param int $id_protocolo
     * @return int
     */
    public function editar_documentos_log($id_protocolo){
        $dados = array('ultima_etapa' => "false");
        $this->db->where('documento', $id_protocolo);
        $this->db->where('ultima_etapa =', 'true');
        $this->db->limit(1);
        return $this->db->update('tblog_documentos', $dados);
    }

    /**
     * Método para editar um novo documento
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $id
     * @param array $dados
     * @return int
     */
    public function editar_novo_documento($id, $dados){
        $this->db->where('id', $id);
        return $this->db->update("tbdocumentos_cad", $dados);
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
     * Método responsável por retornar a etapa e o usuario da etapa anterior
     * Utilizado no controller documentos/Transferencia.php
     *
     * @param int $documento
     * @param int $etapa
     * @return object
     */
    public function etapa_anterior($documento, $etapa){
        $this->db->select("etapa, usuario");
        $this->db->from('tblog_documentos');
        $this->db->where('documento = ', $documento);
        $this->db->where('ultima_etapa =', "false");
        $this->db->where('etapa != ', $etapa);
        $this->db->order_by('id desc');
        $this->db->limit(1);
        return $this->db->get('')->row();
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
     * Método responsável por gerar o histórico do documento
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $idprotocolo
     * @return json
     */
    public function historico_documento($idprotocolo){
        $this->db->select("dc.protocolo as protocolo, d.titulo as nome_documento, g.titulo as nome_grupo");
        $this->db->from("tblog_documentos as ld");
        $this->db->join("tbdocumentos_cad as dc", "ld.documento = dc.id");
        $this->db->join("tbdocumento as d", "dc.fk_iddocumento = d.id");
        $this->db->join('tbgrupo as g', 'g.id = d.fk_idgrupo');
        $this->db->where('dc.id =', $idprotocolo);
        $this->db->group_by('dc.id');
        $query = $this->db->get();

        return json_encode($query->result());
    }

    /**
     * Método responsável por listar o histórico do documento
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $idprotocolo
     * @return json
     */
    public function historico_documentos_dados($idprotocolo){
        $this->db->select("ld.descricao as descricao, u.nome as nome, DATE_FORMAT(ld.data_hora, '%d/%m/%Y') as data, DATE_FORMAT(ld.data_hora,'%H:%i') as hora, e.titulo as etapa");
        $this->db->from("tblog_documentos as ld");
        $this->db->join("tbusuario as u", "u.id = ld.usuario", "left");
        $this->db->join("tbetapa as e", "e.id = ld.etapa", "left");
        $this->db->where("ld.documento", $idprotocolo);
        $this->db->order_by("ld.id asc");

        return json_encode($this->db->get()->result());
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
        $this->db->join('tbcompetencias as c', 'c.fk_iddocumento = d.id and c.tipo="cargo"');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.ultima_etapa = "true"');
        $this->db->join('tbusuario as u', 'u.fk_idcargos = c.fk_idcargo', 'left');
        $this->db->join('tbetapa as e', 'e.id = ldB.etapa', 'left');
        $this->db->join('tbdocumentoetapa as de', 'de.iddocumento = d.id and de.idetapa = ldB.etapa');
        $this->db->where("ldB.usuario = 0");
        $this->db->where("u.id = $usuario");
        $this->db->order_by('de.ordem asc, ldA.data_hora asc');
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
        $this->db->order_by('de.ordem asc, ldA.data_hora asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar os documentos em andamento 
     * Utilizado no controller documentos/Relatorios.php
     *
     * @param int $empresa
     * @return object
     */
    public function listar_documentos_em_andamento($empresa){
        $this->db->select('d.id as iddocumento, e.id as idetapa, dc.protocolo AS protocolo, d.titulo AS documento, g.titulo AS grupo, dc.prazo AS prazo, 
        e.titulo AS etapa, DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.nome AS nome_usuario, de.ordem as ordem, dc.id as idprotocolo');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.ultima_etapa = "true"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->join('tbetapa as e', 'e.id = ldB.etapa', 'left');
        $this->db->join('tbdocumentoetapa as de', 'de.iddocumento = d.id and de.idetapa = ldB.etapa');
        $this->db->where("d.fk_idempresa = $empresa");
        $this->db->where('ldB.descricao != "FINALIZADO"');
        $this->db->order_by('de.ordem asc, ldA.data_hora asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar os documentos com erro
     * Utilizado no controller documentos/Relatorios.php
     *
     * @param int $empresa
     * @return object
     */
    public function listar_documentos_com_erros($empresa){
        $this->db->select('d.id as iddocumento, e.id as idetapa, dc.protocolo AS protocolo, d.titulo AS documento, g.titulo AS grupo, dc.prazo AS prazo,
        e.titulo AS etapa, ldA.data_hora AS data_criacao, u.nome AS nome_usuario, de.ordem as ordem, dc.id as idprotocolo, er.titulo as titulo_erro, 
        ed.descricao as descricao_erro, DATE_FORMAT(ed.data_hora, "%d/%m/%Y") as data_erro');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tberros_documentos as ed', 'ed.fk_iddocumentos = dc.id');
        $this->db->join('tberros as er', 'er.id = ed.fk_iderros');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.ultima_etapa = "true"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->join('tbetapa as e', 'e.id = ldB.etapa', 'left');
        $this->db->join('tbdocumentoetapa as de', 'de.iddocumento = d.id and de.idetapa = ldB.etapa');
        $this->db->where("d.fk_idempresa = $empresa");
        $this->db->order_by('de.ordem asc, ldA.data_hora asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar os documentos cancelados
     * Utilizado no controller documentos/Relatorios.php
     *
     * @param int $empresa
     * @return object
     */
    public function listar_documentos_cancelados($empresa){
        $this->db->select('dc.id as idprotocolo, dc.protocolo as protocolo, d.titulo as documento, g.titulo as grupo, DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") as data_criacao, 
        DATE_FORMAT(c.data_hora, "%d/%m/%Y") as data_cancelamento, ldB.usuario as idresponsavel, u.nome as nome_usuario,ldB.etapa AS idetapa, dc.prazo AS prazo');
        $this->db->from('tbdocumentos_cad as dc');
        $this->db->join('tbdocumento as d', 'dc.fk_iddocumento = d.id');
        $this->db->join('tbgrupo as g', 'g.id = d.fk_idgrupo');
        $this->db->join('tbcancelamento as c', 'c.fk_iddocumento = dc.id');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "CANCELADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where("d.fk_idempresa = $empresa");
        $this->db->order_by('dc.id asc');
        return $this->db->get()->result();
    }

    
    /**
     * Método responsável por listar os documentos finalizados
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $empresa
     * @return object
     */
    public function listar_documentos_finalizados($empresa){
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
        $this->db->order_by("ldA.data_hora asc");
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar os documentos suspensos
     * Utilizado no controller documentos/Relatorio.php
     *
     * @param int $empresa
     * @return object
     */
    public function listar_documentos_suspensos($empresa){
        $this->db->select('dc.id as idprotocolo, dc.protocolo as protocolo, d.titulo as documento, g.titulo as grupo, DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") as data_criacao, 
        DATE_FORMAT(ldB.data_hora, "%d/%m/%Y") as data_suspensao, ldB.usuario as idresponsavel, u.nome as nome_usuario,ldB.etapa AS idetapa, dc.prazo AS prazo');
        $this->db->from('tbdocumentos_cad as dc');
        $this->db->join('tbdocumento as d', 'dc.fk_iddocumento = d.id');
        $this->db->join('tbgrupo as g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "SUSPENSO" and ldB.ultima_etapa = "true"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where("d.fk_idempresa = $empresa");
        $this->db->order_by('dc.id asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar o total de registro de documento cadastrado
     * Utilizado no controller documentos/Transferencia.php e documentos/Documento.php
     *
     * @param string $usuariosAptos
     * @return int
     */
    public function numero_documentos($usuariosAptos){
        $this->db->select('count(*) as total');
        $this->db->from('tblog_documentos');
        $this->db->where("usuario in ($usuariosAptos)");
        $this->db->where("ultima_etapa = 'true'");

        return $this->db->get()->row('total');
    }

    /**
     * Método responsável por retornar a quantidade de documentos por usuario
     * Utilizado no controller documentos/Transferencia.php e documentos/Documento.php
     *
     * @param int $usuarios
     * @return object
     */
    public function quantidade_documentos_usuario($usuarios){

        $this->db->select('usuario, count(*) quantidade_documento');
        $this->db->from('tblog_documentos');
        $this->db->where("usuario in ($usuarios)");
        $this->db->where("ultima_etapa", "true");
        $this->db->group_by('usuario');
        
        return $this->db->get()->result();

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

}
