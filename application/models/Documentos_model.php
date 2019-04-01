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
     * Utilizados pelo controller documentos/Documento.php e documentos/Transferencia.php e documentos/Finalizar.php e Usuario.php
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
     * Método para cadastro de observação de usuarios
     * Utilizado no controller documentos/Documento.php 
     *
     * @param array $dados
     * @return int
     */
    public function cadastrar_observacao($dados){
        return $this->db->insert('tbobservacoes', $dados);
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
     * Utilizado em todos os controllers da pasta documentos
     *
     * @param int $id
     * @return object
     */
    public function dados_documento_cad($id){
        $this->db->select("dc.protocolo as protocolo, dc.id as id, d.fk_idgrupo as grupo, d.titulo as documento_nome, d.id as iddocumento, dc.prazo as prazo");
        $this->db->from('tbdocumentos_cad as dc');
        $this->db->join('tbdocumento as d', "d.id = dc.fk_iddocumento");
        $this->db->where('dc.id', $id);
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar os dados dos documentos referentes a determinado mês
     * Utilizado no controller relatorios/Imprimir.php 
     *
     * @param date $mes
     * @return object
     */
    public function documento_do_mes($mes, $empresa){
        $this->db->select('DATE_FORMAT(ldB.data_hora, "%M/%Y") as mes_ano, dc.id as idprotocolo, dc.protocolo AS protocolo, d.id as iddocumento, 
        d.titulo AS documento, g.titulo AS grupo, DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.nome AS nome_usuario, 
        DATE_FORMAT(ldB.data_hora, "%d/%m/%Y") as data_finalizacao, DATE_FORMAT(dc.prazo, "%d/%m/%Y") as prazo_documento');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where("ldB.data_hora like '$mes%'");
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->order_by('ldA.data_hora asc');
        return $this->db->get()->result();
    }

    /**
     * Método para retornar os dados dos documentos em atraso
     * Utilizado em relatorios/Imprimir.php 
     *
     * @param int $idprotocolo
     * @return object
     */
    public function documento_em_atraso($idprotocolo){
        $this->db->select('dc.id as idprotocolo, dc.protocolo as protocolo, d.id as iddocumento, d.titulo as titulo_documento, e.titulo as etapa_atual, 
        g.titulo as titulo_grupo, u.nome as nome_usuario, DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") as data_criacao, DATE_FORMAT(NOW(), "%d/%m/%Y") as data_atual, 
        DATE_FORMAT(pe.prazo, "%d/%m/%Y") as prazo, DATEDIFF(CURDATE(), pe.prazo) as dias_atraso, DATE_FORMAT(ldB.data_hora, "%d/%m/%Y") as data_recebimento');
        $this->db->from('tbdocumentos_cad as dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo as g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.ultima_etapa = "true"');
        $this->db->join('tbprazoetapa as pe', 'pe.fk_iddocumento = ldA.documento and pe.fk_idetapas = ldB.etapa');
        $this->db->join('tbetapa as e', 'e.id = ldB.etapa');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario');
        $this->db->where('pe.prazo < NOW()');
        $this->db->where('ldA.documento', $idprotocolo);

        return $this->db->get()->result();
    }

    /**
     * Método para listagem de documento por cargo
     * Utilizado no controller relatorios/Imprimir.php 
     *
     * @param int $cargo
     * @return object
     */
    public function documento_por_cargo($cargo){
        $this->db->select("d.id as iddocumento, dc.protocolo AS protocolo, d.titulo AS documento, g.titulo AS grupo, 
        DATE_FORMAT(dc.prazo, '%d/%m/%Y') as prazo_documento, DATE_FORMAT(ldB.data_hora, '%d/%m/%Y') as data_finalizacao, DATE_FORMAT(ldA.data_hora, '%d/%m/%Y') AS data_criacao, u.nome AS nome_usuario, dc.id as idprotocolo");
        $this->db->from("tbdocumentos_cad AS dc");
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->join('tbcompetencias as c', 'c.fk_iddocumento = d.id and c.tipo="cargo"');
        $this->db->join("tbgrupo AS g", "g.id = d.fk_idgrupo");
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join("tbusuario as u", "u.fk_idcargos = c.fk_idcargo", 'left');
        $this->db->where("c.fk_idcargo", $cargo);
        $this->db->group_by("dc.id");
        $this->db->order_by("ldA.data_hora asc");

        return $this->db->get()->result();
    }

    /**
     * Método para listagem de documento por cargo por filtro de dias
     * Utilizado no controller relatorios/Imprimir.php 
     *
     * @param int $cargo
     * @return object
     */
    public function documento_por_cargo_date($cargo, $dataDe, $dataAte){
        $this->db->select("d.id as iddocumento, dc.protocolo AS protocolo, d.titulo AS documento, g.titulo AS grupo, 
        DATE_FORMAT(dc.prazo, '%d/%m/%Y') as prazo_documento, DATE_FORMAT(ldB.data_hora, '%d/%m/%Y') as data_finalizacao, DATE_FORMAT(ldA.data_hora, '%d/%m/%Y') AS data_criacao, u.nome AS nome_usuario, dc.id as idprotocolo");
        $this->db->from("tbdocumentos_cad AS dc");
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->join('tbcompetencias as c', 'c.fk_iddocumento = d.id and c.tipo="cargo"');
        $this->db->join("tbgrupo AS g", "g.id = d.fk_idgrupo");
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join("tbusuario as u", "u.fk_idcargos = c.fk_idcargo", 'left');
        $this->db->where("c.fk_idcargo", $cargo);
        $this->db->where("DATE_FORMAT(ldA.data_hora, '%Y-%m') >= '$dataDe'");
        $this->db->where("DATE_FORMAT(ldA.data_hora, '%Y-%m') <= '$dataAte'");
        $this->db->group_by("dc.id");
        $this->db->order_by("ldA.data_hora asc");

        return $this->db->get()->result();
    }

    /**
     * Método para listagem de documento por cargo por filtro de dias por protocolo
     * Utilizado no controller relatorios/Imprimir.php 
     *
     * @param int $cargo
     * @param int $dataDe
     * @param int $dataAte
     * @param int $iddocumento
     * @return object
     */
    public function documento_por_cargo_dateDoc($cargo, $dataDe, $dataAte, $iddocumento){
        $this->db->select("d.id as iddocumento, dc.protocolo AS protocolo, d.titulo AS documento, g.titulo AS grupo, 
        DATE_FORMAT(dc.prazo, '%d/%m/%Y') as prazo_documento, DATE_FORMAT(ldB.data_hora, '%d/%m/%Y') as data_finalizacao, DATE_FORMAT(ldA.data_hora, '%d/%m/%Y') AS data_criacao, u.nome AS nome_usuario, dc.id as idprotocolo");
        $this->db->from("tbdocumentos_cad AS dc");
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->join('tbcompetencias as c', 'c.fk_iddocumento = d.id and c.tipo="cargo"');
        $this->db->join("tbgrupo AS g", "g.id = d.fk_idgrupo");
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join("tbusuario as u", "u.fk_idcargos = c.fk_idcargo", 'left');
        $this->db->where("c.fk_idcargo", $cargo);
        $this->db->where("DATE_FORMAT(ldA.data_hora, '%Y-%m') >= '$dataDe'");
        $this->db->where("DATE_FORMAT(ldA.data_hora, '%Y-%m') <= '$dataAte'");
        $this->db->where('d.id', $iddocumento);
        $this->db->group_by("dc.id");
        $this->db->order_by("ldA.data_hora asc");

        return $this->db->get()->result();
    }

    /**
     * Método para listagem de documento por cargo por filtro de documentos
     *
     * @param int $cargo
     * @param int $iddocumento
     * @return object
     */
    public function documento_cargo_filtro($cargo, $iddocumento){
        $this->db->select("d.id as iddocumento, dc.protocolo AS protocolo, d.titulo AS documento, g.titulo AS grupo, 
        DATE_FORMAT(dc.prazo, '%d/%m/%Y') as prazo_documento, DATE_FORMAT(ldB.data_hora, '%d/%m/%Y') as data_finalizacao, DATE_FORMAT(ldA.data_hora, '%d/%m/%Y') AS data_criacao, u.nome AS nome_usuario, dc.id as idprotocolo");
        $this->db->from("tbdocumentos_cad AS dc");
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->join('tbcompetencias as c', 'c.fk_iddocumento = d.id and c.tipo="cargo"');
        $this->db->join("tbgrupo AS g", "g.id = d.fk_idgrupo");
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join("tbusuario as u", "u.fk_idcargos = c.fk_idcargo", 'left');
        $this->db->where("c.fk_idcargo", $cargo);
        $this->db->where('d.id', $iddocumento);
        $this->db->group_by("dc.id");
        $this->db->order_by("ldA.data_hora asc");

        return $this->db->get()->result();
    }

    /**
     * Método responsável por mostrar os documentos que foram trabalhados por determinado cargo
     * Utilizado no controller relatorios/Imprimir.php 
     *
     * @param int $cargo
     * @return object
     */
    public function filtro_documento_cargo($cargo){
        $this->db->select("d.*");
        $this->db->from("tbdocumentos_cad AS dc"); 
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->join('tbcompetencias as c', 'c.fk_iddocumento = d.id and c.tipo="cargo"');
        $this->db->join("tbgrupo AS g", "g.id = d.fk_idgrupo");
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.fk_idcargos = c.fk_idcargo', 'left');
        $this->db->where("c.fk_idcargo = $cargo");
        $this->db->group_by('d.id');
        $this->db->order_by("ldA.data_hora asc");

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
     * Utilizado no controller documento/Transferencia.php, Usuario.php
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
     * Método responsável por retornar os erros encontrados no documento
     * Utilizado no controller relatorios/Imprimir.php 
     *
     * @param int $idprotocolo
     * @return object
     */
    public function erros_do_documento($idprotocolo){
        $this->db->select('dc.id as idprotocolo, er.titulo as titulo, ed.descricao as descricao, et.titulo as tipo, e.titulo as titulo_etapa, 
        DATE_FORMAT(ed.data_hora, "%d/%m/%Y") as quando, u.nome as relator, ld.usuario as responsavel');
        $this->db->from('tbdocumentos_cad as dc');
        $this->db->join('tblog_documentos as ld', "ld.documento = dc.id and ld.descricao = 'RETORNO COM ERRO'");
        $this->db->join('tberros_documentos as ed', 'ed.fk_iddocumentos = dc.id');
        $this->db->join('tberros as er', 'er.id = ed.fk_iderros');
        $this->db->join('tberros_tipo as et', 'et.id = er.fk_idtipo');
        $this->db->join('tbetapa as e', 'e.id = ed.fk_idetapa');
        $this->db->join('tbusuario as u', 'u.id = ed.fk_idusuario');
        $this->db->where('dc.id', $idprotocolo);
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar quantos erros por documento determinado usuário tem
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $usuario
     * @return int
     */
    public function erros_usuario_documento($usuario){
        
        $this->db->select('count(*) as total_doc');
        $this->db->from('tblog_documentos');
        $this->db->where('usuario =', $usuario);
        $this->db->where('descricao = ', "RETORNO COM ERRO");
        
        return $this->db->get()->row('total_doc');
    }

    /**
     * Método responsável por retornar quantos erros por documento determinado usuário tem
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $usuario
     * @return int
     */
    public function erros_usuario_documento_date($usuario, $dataDe, $dataAte){
        
        $this->db->select('count(*) as total_doc');
        $this->db->from('tblog_documentos');
        $this->db->where('usuario =', $usuario);
        $this->db->where('descricao = ', "RETORNO COM ERRO");
        $this->db->where("DATE_FORMAT(data_hora, '%Y-%m') >= '$dataDe'");
        $this->db->where("DATE_FORMAT(data_hora, '%Y-%m') <= '$dataAte'");
        
        return $this->db->get()->row('total_doc');
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
     * Método responsável por retornar a data de finalização do documento
     * Utilizado no controller relatorios/Imprimir.php 
     *
     * @param int $idprotocolo
     * @return object
     */
    public function finalizacao_data_documento($idprotocolo){
        $this->db->select("DATE_FORMAT(ld.data_hora, '%d/%m/%Y - %H:%i') as data_finalizacao");
        $this->db->from('tblog_documentos as ld');
        $this->db->where("ld.descricao = 'FINALIZADO'");
        $this->db->where('ld.documento', $idprotocolo);
        return $this->db->get()->row('data_finalizacao');
    }

    /**
     * Método responsável por gerar o histórico do documento
     * Utilizado no controller documentos/Documento.php e relatorios/Imprimir.php
     *
     * @param int $idprotocolo
     * @return json
     */
    public function historico_documento($idprotocolo){
        $this->db->select("dc.id as idprotocolo, dc.protocolo as protocolo, d.titulo as nome_documento, g.titulo as nome_grupo, 
        DATE_FORMAT(ld.data_hora, '%d/%m/%Y - %H:%i') as data_criacao, u.nome as usuario_nome, DATE_FORMAT(dc.prazo, '%d/%m/%Y') as prazo, 
        d.fk_idempresa as idempresa");
        $this->db->from("tblog_documentos as ld");
        $this->db->join("tbdocumentos_cad as dc", "ld.documento = dc.id");
        $this->db->join("tbdocumento as d", "dc.fk_iddocumento = d.id");
        $this->db->join('tbgrupo as g', 'g.id = d.fk_idgrupo');
        $this->db->join('tbusuario as u', 'u.id = ld.usuario');
        $this->db->where('dc.id =', $idprotocolo);
        $this->db->where('ld.descricao = "CRIADO"');
        $this->db->group_by('dc.id');
        $query = $this->db->get();

        return json_encode($query->result());
    }

    /**
     * Método responsável por listar o histórico do documento
     * Utilizado no controller documentos/Documento.php e relatorios/Imprimir.php
     *
     * @param int $idprotocolo
     * @return json
     */
    public function historico_documentos_dados($idprotocolo){
        $this->db->select("ld.descricao as descricao, u.nome as nome, DATE_FORMAT(ld.data_hora, '%d/%m/%Y') as data, DATE_FORMAT(ld.data_hora,'%H:%i') as hora, 
        e.titulo as etapa, ld.documento as idprotocolo, e.id as idetapa, c.motivo as motivo, et.titulo as natureza_erro, er.titulo as tipo_erro, 
        ed.descricao as erro, o.descricao as observacao");
        $this->db->from("tblog_documentos as ld");
        $this->db->join("tbusuario as u", "u.id = ld.usuario", "left");
        $this->db->join("tbetapa as e", "e.id = ld.etapa", "left");
        $this->db->join('tbcancelamento as c', 'c.fk_iddocumento = ld.documento', 'left');
        $this->db->join("tberros_documentos as ed", "ed.fk_iddocumentos = ld.documento and ed.fk_idetapa = e.id and (ld.descricao = 'RETORNO COM ERRO')", "left");
        $this->db->join('tberros as er', 'er.id = ed.fk_iderros', 'left');
        $this->db->join('tberros_tipo as et', 'et.id = er.fk_idtipo', 'left');
        $this->db->join('tbobservacoes as o', 'o.fk_idprotocolo = ld.documento and o.fk_idetapa = e.id', 'left');
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
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->join('tbetapa as e', 'e.id = ldB.etapa', 'left');
        $this->db->join('tbdocumentoetapa as de', 'de.iddocumento = d.id and de.idetapa = ldB.etapa');
        $this->db->where("ldB.usuario = $usuario");
        $this->db->group_by('dc.id');
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
        //$this->db->join('tbcompetencias as c', 'c.fk_iddocumento = d.id and c.tipo="funcionario"');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.ultima_etapa = "true"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->join('tbetapa as e', 'e.id = ldB.etapa', 'left');
        $this->db->join('tbdocumentoetapa as de', 'de.iddocumento = d.id and de.idetapa = ldB.etapa');
        $this->db->where("ldB.usuario = $usuario");
        $this->db->group_by('dc.id');
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
        e.titulo AS etapa, DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.id AS idresponsavel, u.nome AS nome_usuario, de.ordem as ordem, 
        dc.id as idprotocolo, ldB.descricao as descricao');
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
     * Método responsável por retornar os documentos que não foram iniciados ainda
     * Utilizado no controller documentos/Relatório.php
     *
     * @param int $empresa
     * @return object
     */
    public function listar_documentos_nao_iniciados($empresa){
        // subquery
        $this->db->select("fk_idusuario");
        $this->db->from("tbtimer");
        $this->db->where("dc.id = tbtimer.fk_iddoccad and ldB.etapa = tbtimer.fk_idetapa");
        $subquery = $this->db->get_compiled_select();

        $this->db->select('d.id as iddocumento, e.id as idetapa, dc.protocolo AS protocolo, d.titulo AS documento, g.titulo AS grupo, dc.prazo AS prazo, 
        e.titulo AS etapa, DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.id AS idresponsavel, u.nome AS nome_usuario, de.ordem as ordem, 
        dc.id as idprotocolo, ldB.descricao as descricao');
        $this->db->from("tbdocumentos_cad AS dc");
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->join("tbgrupo AS g", "g.id = d.fk_idgrupo");
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.ultima_etapa = "true"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->join('tbetapa as e', 'e.id = ldB.etapa', 'left');
        $this->db->join('tbdocumentoetapa as de', 'de.iddocumento = d.id and de.idetapa = ldB.etapa');
        $this->db->where("d.fk_idempresa = $empresa");
        $this->db->where("ldB.usuario not in($subquery)");
        $this->db->where('ldB.descricao != "FINALIZADO"');
        $this->db->order_by("de.ordem asc, ldA.data_hora asc");
        
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar os documentos em andamento por cargo
     * Utilizado no controller documentos/Relatorios.php
     *
     * @param int $idusuario
     * @return object
     */
    public function listar_documentos_andamento_cargos($idusuario){
        $this->db->select('d.id as iddocumento, e.id as idetapa, dc.protocolo AS protocolo, d.titulo AS documento, g.titulo AS grupo, dc.prazo AS prazo, 
        e.titulo AS etapa, DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.id AS idresponsavel, u.nome AS nome_usuario, de.ordem as ordem, dc.id as idprotocolo');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbcompetencias as c', 'c.fk_iddocumento = d.id and c.tipo="cargo"');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.ultima_etapa = "true"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->join('tbetapa as e', 'e.id = ldB.etapa', 'left');
        $this->db->join('tbdocumentoetapa as de', 'de.iddocumento = d.id and de.idetapa = ldB.etapa');
        $this->db->where("u.id = $idusuario");
        $this->db->where('ldB.descricao != "FINALIZADO"');
        $this->db->group_by('d.id');
        $this->db->order_by('de.ordem asc, ldA.data_hora asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar os documentos em andamento 
     * Utilizado no controller documentos/Relatorios.php
     *
     * @param int $idusuario
     * @return object
     */
    public function listar_documentos_andamento_funcionarios($idusuario){
        $this->db->select('d.id as iddocumento, e.id as idetapa, dc.protocolo AS protocolo, d.titulo AS documento, g.titulo AS grupo, dc.prazo AS prazo, 
        e.titulo AS etapa, DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") AS data_criacao, u.id AS idresponsavel, u.nome AS nome_usuario, de.ordem as ordem, dc.id as idprotocolo');
        $this->db->from('tbdocumentos_cad AS dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbcompetencias as c', 'c.fk_iddocumento = d.id and c.tipo="funcionario"');
        $this->db->join('tbgrupo AS g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.ultima_etapa = "true"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->join('tbetapa as e', 'e.id = ldB.etapa', 'left');
        $this->db->join('tbdocumentoetapa as de', 'de.iddocumento = d.id and de.idetapa = ldB.etapa');
        $this->db->where('c.fk_idusuario', $idusuario);
        $this->db->where('ldB.descricao != "FINALIZADO"');
        $this->db->group_by('d.id');
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
        ed.descricao as descricao_erro, DATE_FORMAT(ed.data_hora, "%d/%m/%Y") as data_erro, u.id as idresponsavel');
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
     * Utilizado no controller relatorios/Relatorios.php e relatorios/Imprimir.php
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
     * Método responsável por listar os dados para o filtro de mes e ano
     * Utilizado no controller relatorios/Relatorios.php
     *
     * @param int $empresa
     * @return object
     */
    public function listar_documentos_finalizados_filtro($empresa){
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
        $this->db->group_by('DATE_FORMAT(ldB.data_hora, "%m/%Y")');
        $this->db->order_by("ldA.data_hora asc");
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar os dados depois de filtrados
     *
     * @param int $empresa
     * @param date $mesano
     * @return object
     */
    public function filtro_documentos_finalizados($empresa, $mesano){
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
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->where("ldB.data_hora like $data");
        $this->db->order_by("ldA.data_hora asc");
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar os documetos finalizado por mes
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int  $empresa
     * @return object
     */
    public function listar_documentos_finalizados_mes($empresa){
        $this->db->select('DATE_FORMAT(ldB.data_hora, "%m/%Y") as mes_ano');
        $this->db->from("tbdocumentos_cad AS dc");
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->join("tbgrupo AS g", "g.id = d.fk_idgrupo");
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->group_by('DATE_FORMAT(ldB.data_hora, "%m/%Y")');
        $this->db->order_by('ldA.data_hora asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar o ano dos documentos finalizados
     * Utilizado no controller relatorios/Relatorios.php
     *
     * @param int $empresa
     * @return object
     */
    public function listar_documentos_finalizados_ano($empresa){
        $this->db->select('YEAR(ldB.data_hora) as ano');
        $this->db->from("tbdocumentos_cad AS dc");
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->join("tbgrupo AS g", "g.id = d.fk_idgrupo");
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->group_by('YEAR(ldB.data_hora)');
        $this->db->order_by('ldA.data_hora asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar os documentos finalizados no mes e ano filtrado pelo usuario
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $empresa
     * @param int $mes
     * @param int $ano
     * @return object
     */
    public function listar_documentos_finalizados_filtro_mesano($empresa, $mes, $ano){
        $mesano_filtro = '"'.$ano.'-'.$mes.'-%"';
        $this->db->select('DATE_FORMAT(ldB.data_hora, "%m/%Y") as mes_ano');
        $this->db->from("tbdocumentos_cad AS dc");
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->join("tbgrupo AS g", "g.id = d.fk_idgrupo");
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->where("ldB.data_hora like $mesano_filtro");
        $this->db->group_by('DATE_FORMAT(ldB.data_hora, "%m/%Y")');
        $this->db->order_by('ldA.data_hora asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar os documentos de acordo com o mes escolhido
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int  $empresa
     * @param int $mes
     * @return object
     */
    public function filtro_documentos_por_mes($empresa, $mes){
        $mes_filtro = '"%-'.$mes.'-%"';
        $this->db->select('DATE_FORMAT(ldB.data_hora, "%m/%Y") as mes_ano');
        $this->db->from("tbdocumentos_cad AS dc");
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->join("tbgrupo AS g", "g.id = d.fk_idgrupo");
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->where("ldB.data_hora like $mes_filtro");
        $this->db->group_by('DATE_FORMAT(ldB.data_hora, "%m/%Y")');
        $this->db->order_by('ldA.data_hora asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar os documentos de acordo com o ano escolhido
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int  $empresa
     * @param int $ano
     * @return object
     */
    public function filtro_documentos_por_ano($empresa, $ano){
        $mes_filtro = '"%-'.$mes.'-%"';
        $this->db->select('DATE_FORMAT(ldB.data_hora, "%m/%Y") as mes_ano');
        $this->db->from("tbdocumentos_cad AS dc");
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->join("tbgrupo AS g", "g.id = d.fk_idgrupo");
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "FINALIZADO"');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->where("ldB.data_hora like $mes_filtro");
        $this->db->group_by('DATE_FORMAT(ldB.data_hora, "%m/%Y")');
        $this->db->order_by('ldA.data_hora asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar os documentos pendentes
     * Utilizado no controller documentos/Relatorios.php
     *
     * @param int $empresa
     * @return object
     */
    public function listar_documentos_pendente($empresa){
        $this->db->select('dc.id as idprotocolo, dc.protocolo as protocolo, d.titulo as documento, g.titulo as grupo, DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") as data_criacao, 
        DATE_FORMAT(ldB.data_hora, "%d/%m/%Y") as data_pendente, ldB.usuario as idresponsavel, u.nome as nome_usuario,ldB.etapa AS idetapa, dc.prazo AS prazo, e.titulo as etapa_nome');
        $this->db->from('tbdocumentos_cad as dc');
        $this->db->join('tbdocumento as d', 'dc.fk_iddocumento = d.id');
        $this->db->join('tbgrupo as g','g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.descricao = "PENDENTE"');
        $this->db->join('tbetapa as e', 'e.id = ldB.etapa');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario', 'left');
        $this->db->where('ldB.ultima_etapa = "true"');
        $this->db->where('d.fk_idempresa', $empresa);
        $this->db->order_by('dc.id asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar os documentos fora do prazo
     * Utilizado no controller relatorios/Relatorios.php
     *
     * @param int $empresa
     * @return object
     */
    public function listar_documentos_fora_prazo($empresa){
        $this->db->select('dc.id as idprotocolo, dc.protocolo as protocolo, d.id as iddocumento, d.titulo as titulo_documento, e.titulo as etapa_atual, g.titulo as titulo_grupo,
        u.nome as nome_usuario, DATE_FORMAT(ldA.data_hora, "%d/%m/%Y") as data_criacao, DATE_FORMAT(NOW(), "%d/%m/%Y") as data_atual, 
        DATE_FORMAT(pe.prazo, "%d/%m/%Y") as prazo');
        $this->db->from('tbdocumentos_cad as dc');
        $this->db->join('tbdocumento as d', 'd.id = dc.fk_iddocumento');
        $this->db->join('tbgrupo as g', 'g.id = d.fk_idgrupo');
        $this->db->join('tblog_documentos as ldA', 'ldA.documento = dc.id and ldA.descricao = "CRIADO"');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = dc.id and ldB.ultima_etapa = "true"');
        $this->db->join('tbprazoetapa as pe', 'pe.fk_iddocumento = ldA.documento and pe.fk_idetapas = ldB.etapa');
        $this->db->join('tbetapa as e', 'e.id = ldB.etapa');
        $this->db->join('tbusuario as u', 'u.id = ldB.usuario');
        $this->db->where('pe.prazo < NOW()');
        $this->db->where('d.fk_idempresa', $empresa);
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
     * Método responsável por listar os dados da observações
     * Utilizado no controller documentos/Documento.php 
     *
     * @param int $idprotocolo
     * @return object
     */
    public function listar_observacoes_json($idprotocolo){
        $this->db->select('o.descricao as observacao, e.titulo as etapa, u.nome as nome_usuario');
        $this->db->from("tbobservacoes as o");
        $this->db->join("tbetapa as e", 'e.id = o.fk_idetapa', 'left');
        $this->db->join('tbusuario as u', 'u.id = o.fk_idusuario');
        $this->db->where("o.fk_idprotocolo = ", $idprotocolo);
        return json_encode($this->db->get()->result());
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
     * Método responsável por retornar o total de registro de documento cadastrado
     * Utilizado no controller documentos/Transferencia.php e documentos/Documento.php
     *
     * @param string $usuariosAptos
     * @return int
     */
    public function numero_documentos_data($usuariosAptos, $dataDe, $dataAte){
        $this->db->select('count(*) as total');
        $this->db->from('tblog_documentos');
        $this->db->where("usuario in ($usuariosAptos)");
        $this->db->where("ultima_etapa = 'true'");
        $this->db->where("DATE_FORMAT(data_hora, '%Y-%m') >= '$dataDe'");
        $this->db->where("DATE_FORMAT(data_hora, '%Y-%m') <= '$dataAte'");

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
     * Método responsável por retornar a quantidade de documentos finalizado por determinado funcionario
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $usuario
     * @return int
     */
    public function quantidade_documentos_finalizados_usuario($usuario){
        $this->db->select('count(*) as total');
        $this->db->from('tblog_documentos as ldA');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = ldA.documento and ldB.descricao = "FINALIZADO"');
        $this->db->where("ldA.usuario", $usuario);
        
        return $this->db->get()->row('total');
    }

    /**
     * Método responsável por retornar a quantidade de documentos finalizado por determinado funcionario
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $usuario
     * @return int
     */
    public function quantidade_documentos_finalizados_usuario_date($usuario, $dataDe, $dataAte){
        $this->db->select('count(*) as total');
        $this->db->from('tblog_documentos as ldA');
        $this->db->join('tblog_documentos as ldB', 'ldB.documento = ldA.documento and ldB.descricao = "FINALIZADO"');
        $this->db->where("ldA.usuario", $usuario);
        $this->db->where("DATE_FORMAT(ldA.data_hora, '%Y-%m') >= '$dataDe'");
        $this->db->where("DATE_FORMAT(ldA.data_hora, '%Y-%m') <= '$dataAte'");
        
        return $this->db->get()->row('total');
    }

    /**
     * Método responsável por retornar o usuario da etapa do documento
     * Utilizado no controller conf/Erros.php
     *
     * @param int $etapa
     * @param int $protocolo
     * @return object
     */
    public function retorna_etapa($etapa, $protocolo){

        $this->db->select("etapa, usuario");
        $this->db->from('tblog_documentos');
        $this->db->where('documento = ', $protocolo);
        $this->db->where('ultima_etapa =', "false");
        $this->db->where('etapa = ', $etapa);
        $this->db->order_by('id desc');
        $this->db->limit(1);
        return $this->db->get('')->row();

    }

    /**
     * Método responsável por retornar email e usuario que recebeu o documento
     * Utilizado em todos os controller da pasta documentos
     *
     * @param int $protocolo
     * @return object
     */
    public function retorna_email_usuario($protocolo){
        $this->db->select("u.nome as usuario_nome, u.email as email_usuario");
        $this->db->from("tblog_documentos as ld");
        $this->db->join("tbusuario as u", "u.id = ld.usuario");
        $this->db->where("ld.ultima_etapa = 'true'");
        $this->db->where("ld.documento", $protocolo);
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    /**
     * Método responsável por retornar o email e nome do usuario que criou determinado documento
     * Utilizado no controller documentos/Relatorios.php
     *
     * @param int $protocolo
     * @return object
     */
    public function retorna_email_responsavel($protocolo){
        $this->db->select("u.nome as usuario_nome, u.email as email_usuario");
        $this->db->from('tblog_documentos as ld');
        $this->db->join('tbusuario as u', 'u.id = ld.usuario');
        $this->db->where('ld.descricao = "CRIADO"');
        $this->db->where('ld.documento', $protocolo);
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    /**
     * Método responsável por retornar o prazo e o titulo da etapa do documento
     * Utilizado no controllers documentos/Transferencia.php e documentos/Documento.php 
     *
     * @param int $protocolo
     * @return object
     */
    public function retorna_etapa_prazo($protocolo){
        $this->db->select('e.titulo as etapa, DATE_FORMAT(pe.prazo, "%d/%m/%Y") as prazo_etapa');
        $this->db->from('tblog_documentos as ld');
        $this->db->join('tbetapa as e', 'e.id = ld.etapa');
        $this->db->join('tbprazoetapa as pe', 'pe.fk_iddocumento = ld.documento and pe.fk_idetapas = ld.etapa', 'left');
        $this->db->where('ld.documento', $protocolo);
        $this->db->where('ld.ultima_etapa = "true"');
        $this->db->limit(1);
        return $this->db->get()->row();
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
     * Método responsável por retornar as obervações de determinado documento (se houver)
     * Utilizado no controller documentos/Documento.php 
     *
     * @param int $idprotocolo
     * @return int
     */
    public function verifica_observacoes($idprotocolo){
        $this->db->from('tbobservacoes');
        $this->db->where('fk_idprotocolo', $idprotocolo);
        return $this->db->count_all_results();
    }

    /**
     * Método responsável por verificar e retornar os documentos que estão sendo trabalhado por determinado usuario
     * Utilizado no controller Usuario.php
     *
     * @param int $usuario
     * @return object
     */
    public function verifica_documento_execucao($usuario){

        $this->db->from('tblog_documentos');
        $this->db->where('usuario', $usuario);
        $this->db->where('ultima_etapa = "true"');
        return $this->db->get()->result();

    }

}
