<?php
class Timer_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * Veridica se o documentos retornor da suspensão
     * Utlizado no controller documentos/Documentos.php
     *
     * @param int $documento
     * @return object
     */
    public function verifica_reinicio($documento){
        $this->db->select('count(*) as registros');
        $this->db->from('tblog_documentos');
        $this->db->where('documento', $documento);
        $this->db->where('descricao = "RETORNO SUSPENSÃO"');
        return $this->db->get()->row('registros');
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
        $this->db->where('fk_idusuario is not null');
        $this->db->order_by('id');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar o tempo do documento após retorno de suspensão
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $protocolo
     * @param int $etapa
     * @return object retorna um objeto de dados
     */
    public function get_time_reinicio($protocolo, $etapa){
        $this->db->select("action, timestamp");
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad', $protocolo);
        $this->db->where('fk_idetapa', $etapa);
        $this->db->where('observacao = "REINICIO"');
        $this->db->order_by('id');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar o tempo do documento aguardando exigência
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $protocolo
     * @return object
     */
    public function get_time_suspenso($protocolo){
        
        $this->db->select('action, timestamp');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad', $protocolo);
        $this->db->where('observacao = "SUSPENSO"');
        $this->db->order_by('id');
        
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar o tempo do documento aguardando exigência
     * Utilizado no controller documentos/Documento.php
     *
     * @param int $protocolo
     * @return object
     */
    public function get_time_pendente($protocolo){
        
        $this->db->select('action, timestamp');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad', $protocolo);
        $this->db->where('observacao = "PENDENTE"');
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
        $this->db->where('(observacao != "SUSPENSO" or observacao is null)');
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
     * Método responsável por verificar se o temporizador parou de contar ou não
     * Utilizado no controller documentos/Transferencia.php 
     *
     * @param int $id_protocolo
     * @return string retorna o valor da coluna action - start/pause
     */
    public function verifica_timer($id_protocolo){
        $this->db->select('action');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad', $id_protocolo);
        $this->db->order_by('id desc');
        return $this->db->get()->row('action');
    }

    /**
     * Método responsável por pausar o temporizador que está em funcionamento
     *
     * @param [type] $id_protocolo
     * @return void
     */
    public function troca_acao($id_protocolo){
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad', $id_protocolo);
        $this->db->order_by('id asc');
        $data = $this->db->get()->row();

        $dados = array(
            'fk_iddoccad' => $id_protocolo,
            'fk_idetapa'  => $data->fk_idetapa,
            'action'      => "pause",
            'timestamp'   => $data->timestamp,
            'fk_idusuario' => $data->fk_idusuario,
            'observacao'  => $data->observacao
        );

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
        $this->db->select('id, action, timestamp');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad = ', $protocolo);
        $this->db->order_by('id asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por retornar o tempo desenvolvido no documento que voltou da suspensão(exigencia)
     * Utilizado na view relatorios/tempo_medio.php 
     *
     * @param int $protocolo
     * @return object
     */
    public function listar_timer_suspenso($protocolo){
        $this->db->select('id, action, timestamp');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad = ', $protocolo);
        $this->db->where('observacao = "REINICIO"');
        $this->db->order_by('id asc');
        return $this->db->get()->result();
    }

    /**
     * Método para retorno de tempo de documentos em determinado grupo de documentos
     * Utilizado no controller relatorios/Imprimir.php
     *
     * @param int $grupo
     * @return object
     */
    public function listar_timer_grupo($grupo){
        $this->db->select("action, timestamp");
        $this->db->from("tbtimer as t");
        $this->db->join('tblog_documentos as ld','ld.documento = t.fk_iddoccad and ld.descricao = "FINALIZADO"');
        $this->db->join("tbdocumentos_cad as dc", "dc.id = t.fk_iddoccad");
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->where("d.fk_idgrupo", $grupo);
        $this->db->order_by("t.id asc");
        return $this->db->get()->result();
    }


    /**
     * Método para retorno de tempo de documentos em determinado documentos
     * Utilizado no controller relatorios/Imprimir.php
     *
     * @param int $documento
     * @return object
     */
    public function listar_timer_documento($documento){
        $this->db->select("action, timestamp");
        $this->db->from("tbtimer as t");
        $this->db->join('tblog_documentos as ld','ld.documento = t.fk_iddoccad and ld.descricao = "FINALIZADO"');
        $this->db->join("tbdocumentos_cad as dc", "dc.id = t.fk_iddoccad");
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->where("d.fk_idgrupo", $documento);
        $this->db->order_by("t.id asc");
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
        $this->db->join('tbdocumentos_cad as dc', 'dc.id = t.fk_iddoccad');
        $this->db->join('tbdocumentoetapa as de', 'de.iddocumento = dc.fk_iddocumento and de.idetapa = e.id');
        $this->db->join('tbusuario as u', 'u.id = t.fk_idusuario');
        $this->db->where('t.fk_iddoccad', $protocolo);
        $this->db->group_by('t.fk_idetapa');
        $this->db->order_by('de.ordem asc');
        
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
        $this->db->where('observacao is null');
        $this->db->order_by('id asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsavel por listar o tempo do documento em determinada etapa depois de retornar de exigência
     * Utilizado na view relatorios/imprimir/relatorios_tempo.php 
     *
     * @param int $protocolo
     * @param int $etapa
     * @return object
     */
    public function tempo_por_etapa_suspenso($protocolo, $etapa){
        $this->db->select('action, timestamp');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad', $protocolo);
        $this->db->where('fk_idetapa', $etapa);
        $this->db->where('observacao = "REINICIO"');

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
        $this->db->select('action, timestamp, observacao');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad', $protocolo);
        $this->db->where('fk_idusuario', $responsavel);
        $this->db->order_by('id asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar o tempo de desenvolvimento do documento depois de entrar em exigência
     * Utilizado na view relatorios/imprimir/relatorios_tempo.php 
     *
     * @param int $protocolo
     * @param int $responsavel
     * @return object
     */
    public function tempo_por_responsavel_sus($protocolo, $responsavel){
        $this->db->select('action, timestamp');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad', $protocolo);
        $this->db->where('fk_idusuario', $responsavel);
        $this->db->where('observacao = "REINICIO"');
        $this->db->order_by('id asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar o tempo médio do usuário
     * Utilizado nos controller relatorios/Imprimir.php e relatorios/Relatorios.php
     *
     * @param int $responsavel
     * @return object
     */
    public function tempo_documento_usuario($responsavel, $idtimer){

        $this->db->select('action, timestamp, fk_iddoccad as idprotocolo');
        $this->db->from('tbtimer');
        $this->db->where('fk_idusuario', $responsavel);
        $this->db->where("id != $idtimer");
        $this->db->order_by('id asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar o tempo médio do usuário
     * Utilizado nos controller relatorios/Imprimir.php e relatorios/Relatorios.php
     *
     * @param int $responsavel
     * @return object
     */
    public function tempo_documento_usuario_date($responsavel, $idtimer, $dataDe, $dataAte){

        $this->db->select('t.action as action, t.timestamp as timestamp, t.fk_iddoccad as idprotocolo');
        $this->db->from('tbtimer as t');
        $this->db->join('tblog_documentos as ld','ld.documento = t.fk_iddoccad');
        $this->db->where('t.fk_idusuario', $responsavel);
        $this->db->where("t.id != $idtimer");
        $this->db->where("DATE_FORMAT(ld.data_hora, '%Y-%m') >= '$dataDe'"); 
        $this->db->where("DATE_FORMAT(ld.data_hora, '%Y-%m') <= '$dataAte'");
        $this->db->order_by('t.fk_iddoccad asc, ld.id asc');
        
        return $this->db->get()->result();

    }

    /**
     * Método responsável por listar o tempo médio do usuário nos documentos trabalhados (casos especificos)
     * Utilizado nos controller relatorios/Imprimir.php e relatorios/Relatorios.php
     *
     * @param int $responsavel
     * @return object
     */
    public function tempo_documento_usuario_rel($responsavel){
        $this->db->select('action, timestamp, fk_iddoccad as idprotocolo');
        $this->db->from('tbtimer');
        $this->db->where('fk_idusuario', $responsavel);
        $this->db->order_by('id asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar o tempo médio do usuário nos documentos trabalhados (casos especificos)
     * Utilizado nos controller relatorios/Imprimir.php e relatorios/Relatorios.php
     *
     * @param int $responsavel
     * @return object
     */
    public function tempo_documento_usuario_rel_date($responsavel, $dataDe, $dataAte){
        $this->db->select('t.action as action, t.timestamp as timestamp, t.fk_iddoccad as idprotocolo');
        $this->db->from('tbtimer as t');
        $this->db->join('tblog_documentos as ld','ld.documento = t.fk_iddoccad');
        $this->db->where('fk_idusuario', $responsavel);
        $this->db->where("DATE_FORMAT(ld.data_hora, '%Y-%m') >= '$dataDe'"); 
        $this->db->where("DATE_FORMAT(ld.data_hora, '%Y-%m') <= '$dataAte'");
        $this->db->order_by('t.fk_iddoccad asc, ld.id asc');
        return $this->db->get()->result();
    }

    /**
     * Método responsável por verificar se o ultimo timer cadastrado possui action "pause"
     * Utilizado nos controller relatorios/Imprimir.php e relatorios/Relatorios.php
     *
     * @param int $responsavel
     * @return object
     */
    public function verifica_pause($responsavel){

        $this->db->select('action, id');
        $this->db->from('tbtimer');
        $this->db->where('fk_idusuario', $responsavel);
        $this->db->order_by("id desc");
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    /**
     * Método responsável por listar o tempo médio mensal
     * Utilizado no controller relatorios/Relatorios.php 
     *
     * @param int $grupo
     * @return object
     */
    public function tempo_documento_mensal($dia_mes, $empresa){
        
        $this->db->select("action, timestamp, fk_iddoccad as idprotocolo");
        $this->db->from('tbtimer as t');
        $this->db->join("tblog_documentos as ld", "ld.documento = t.fk_iddoccad and ld.descricao = 'FINALIZADO'");
        $this->db->join("tbdocumentos_cad as dc", "dc.id = ld.documento");
        $this->db->join("tbdocumento as d", "d.id = dc.fk_iddocumento");
        $this->db->where("ld.data_hora like '$dia_mes%'");
        $this->db->where("d.fk_idempresa", $empresa);
        $this->db->where("(t.observacao is null or t.observacao != 'SUSPENSO')");
        $this->db->group_by('t.id');
        $this->db->order_by('t.id asc');

        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar o tempo médio por cargos
     * Utilizado na view relatorios/tempo_cargo.php
     *
     * @param int $idcargo
     * @return object
     */
    public function tempo_documento_cargo($idcargo){
        
        $this->db->select("action, timestamp, fk_iddoccad as idprotocolo");
        $this->db->from("tbtimer as t");
        $this->db->join("tblog_documentos as ld", "ld.documento = t.fk_iddoccad and ld.descricao = 'FINALIZADO'");
        $this->db->join("tbusuario as u","u.id = t.fk_idusuario");
        $this->db->join("tbcargos as c", "c.id = u.fk_idcargos");
        $this->db->where("c.id", $idcargo);
        $this->db->group_by("t.id");
        $this->db->order_by("t.id");
        
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar o tempo médio por cargos
     * Utilizado na view relatorios/tempo_cargo.php
     *
     * @param int $idcargo
     * @return object
     */
    public function tempo_documento_cargo_date($idcargo, $dataDe, $dataAte){
        
        $this->db->select("action, timestamp, fk_iddoccad as idprotocolo");
        $this->db->from("tbtimer as t");
        $this->db->join("tblog_documentos as ld", "ld.documento = t.fk_iddoccad and ld.descricao = 'FINALIZADO'");
        $this->db->join("tbusuario as u","u.id = t.fk_idusuario");
        $this->db->join("tbcargos as c", "c.id = u.fk_idcargos");
        $this->db->where("c.id", $idcargo);
        $this->db->where("DATE_FORMAT(ld.data_hora, '%Y-%m') >= '$dataDe'");
        $this->db->where("DATE_FORMAT(ld.data_hora, '%Y-%m') <= '$dataAte'");
        $this->db->group_by("t.id");
        $this->db->order_by("t.id");
        
        return $this->db->get()->result();
    }

    /**
     * Lista documentos por cargo sem contar o ultimo registro
     *
     * @param int $idcargo
     * @param int $idtimer
     * @return int retorna numero de resultados
     */
    public function tempo_documento_cargo_without($idcargo, $idtimer){
        
        $this->db->select("action, timestamp, fk_iddoccad as idprotocolo");
        $this->db->from("tbtimer as t");
        $this->db->join("tblog_documentos as ld", "ld.documento = t.fk_iddoccad and ld.descricao = 'FINALIZADO'");
        $this->db->join("tbusuario as u","u.id = t.fk_idusuario");
        $this->db->join("tbcargos as c", "c.id = u.fk_idcargos");
        $this->db->where("c.id", $idcargo);
        $this->db->where('t.id != '.$idtimer);
        $this->db->group_by("t.id");
        $this->db->order_by("t.id");
        
        return $this->db->get()->result();
    }

    /**
     * Lista documentos por cargo sem contar o ultimo registro
     *
     * @param int $idcargo
     * @param int $idtimer
     * @return int retorna numero de resultados
     */
    public function tempo_documento_cargo_without_date($idcargo, $idtimer, $dataDe, $dataAte){
        
        $this->db->select("action, timestamp, fk_iddoccad as idprotocolo");
        $this->db->from("tbtimer as t");
        $this->db->join("tblog_documentos as ld", "ld.documento = t.fk_iddoccad and ld.descricao = 'FINALIZADO'");
        $this->db->join("tbusuario as u","u.id = t.fk_idusuario");
        $this->db->join("tbcargos as c", "c.id = u.fk_idcargos");
        $this->db->where("c.id", $idcargo);
        $this->db->where('t.id != '.$idtimer);
        $this->db->where("DATE_FORMAT(ld.data_hora, '%Y-%m') >= '$dataDe'");
        $this->db->where("DATE_FORMAT(ld.data_hora, '%Y-%m') <= '$dataAte'");
        $this->db->group_by("t.id");
        $this->db->order_by("t.id");
        
        return $this->db->get()->result();
    }

    /**
     * Verifica ultimo registro de ação relativo o cargo
     * Utilizado no controller Imprimir.php
     *
     * @param int $cargo
     * @return void
     */
    public function verifica_pause_cargo($cargo){

        $this->db->select('t.action as action, t.id as id');
        $this->db->from('tbtimer as t');
        $this->db->join('tbusuario as u', 'u.id = t.fk_idusuario');
        $this->db->join('tbcargos as c', 'c.id = u.fk_idcargos');
        $this->db->where('c.id', $cargo);
        $this->db->order_by("t.id desc");
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    /**
     * Método responsável por listar o tempo médio do documento em exigencia(suspensão)
     * Utilizado no controller relatorios/Imprimir.php
     *
     * @param int $idprotocolo
     * @return object
     */
    public function tempo_em_suspensao($idprotocolo){

        $this->db->select('action, timestamp, fk_iddoccad as idprotocolo');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad', $idprotocolo);
        $this->db->where('observacao = "SUSPENSO"');
        $this->db->order_by('id asc');
        
        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar o tempo médio do documento pendente
     * Utilizado no controller relatorios/Imprimir.php
     *
     * @param int $idprotocolo
     * @return object
     */
    public function tempo_pendente($idprotocolo){

        $this->db->select('action, timestamp, fk_iddoccad as idprotocolo');
        $this->db->from('tbtimer');
        $this->db->where('fk_iddoccad', $idprotocolo);
        $this->db->where('observacao = "PENDENTE"');
        $this->db->order_by('id asc');
        
        return $this->db->get()->result();

    }

    public function andamento_produtividade(){

    }

}