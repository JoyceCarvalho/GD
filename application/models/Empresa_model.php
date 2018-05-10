<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe para consultas referentes a empresa no geral (CRUD)
 */
class Empresa_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    /**
     * Método responsável por trazer os dados do banco
     * Utilizado no controller admin/Controle.php
     *
     * @param int $id
     * @return object retorna um objeto de dados da tabela tbempresa
     */
    public function dados_empresa($id){

        $this->db->from('tbempresa');
        $this->db->where('id = '.$id);
        return $this->db->get('')->result();

    }

    /**
     * Médoto responsável por trazer os dados referentes ao coordenador na tabela tbcargos
     * Utilizado no controller admin/Controle.php
     *
     * @param int $id
     * @return object retorna o objeto de dados da tabela tbcargos
     */
    public function coordenador($id){

        $this->db->select("u.id as id, u.nome as nome, u.email as email, u.usuario as usuario");
        $this->db->from('tbusuario as u');
        $this->db->join('tbcargos as c', 'c.id = u.fk_idcargos');
        $this->db->where("c.titulo = 'Coordenador'");
        $this->db->where('c.fk_idempresa = '.$id);
        $this->db->order_by('u.id');
        return $this->db->get('')->result();
    }

    /**
     * Método para retornar o nome da empresa do usuário logado
     * Utilizado em todos os controller
     *
     * @param int $id
     * @return string
     */
    public function nome_empresa($id){
        $this->db->select('nome');
        $this->db->from('tbempresa');
        $this->db->where('id ='.$id);
        return $this->db->get('')->result();
    }

    /**
     * Método de listagem de empresas
     * Utilizado no controller admin/Controle.php
     *
     * @return object retorna todas as empresas cadastradas na tabela tbempresa
     */
    public function listar_empresas(){
        $this->db->where('ativo = 1');
        return $this->db->get('tbempresa')->result();
    }

    /**
     * Médoto para excluir uma determinada empresa do banco de dados
     * Utilizado no controller admin/Controle.php
     *
     * @param int $id
     * @return int retorna o numero de linhas afetadas
     */
    public function excluir_empresa($id){
        $dados = array('ativo' => 0);
        $this->db->where('id',$id);
        return $this->db->update('tbempresa', $dados);
       
    }

    /**
     * Método para cadastro das empresas
     * Utilizado no controller admin/Controle.php
     *
     * @param object $dados
     * @return int retorna o numero de linhas afetadas
     */
    public function cadastrar_empresa($dados){
        $this->db->insert('tbempresa', $dados);
        return $this->db->insert_id();
    }

    /**
     * Método para cadastro de coordenador (primeiro usuário cadastrado)
     * Utilizado no controller admin/Controle.php
     *
     * @param object $dados
     * @return int retorna o numero de linhas afetadas
     */
    public function cadastrar_coordenador($dados){
        return $this->db->insert('tbusuario', $dados);
    }

    /**
     * Método para edição de dados da empresa(feita somente pelo coordenador)
     * Utilizado no controller admin/Controle.php
     *
     * @param array $dados
     * @param int $id
     * @return int retorna o numero de linhas afetadas
     */
    public function editar_empresa($dados, $id){

        $this->db->where('id',$id);
        return $this->db->update('tbempresa', $dados);

    }

    /**
     * Método para edição de coordenador da empresa (cadastrado somente pelo SGT Master)
     *
     * @param array $dados
     * @param int $id
     * @return int retorna o número de linhas afetadas
     */
    public function editar_coordenador($dados, $id){

        $this->db->where('id',$id);
        return $this->db->update('tbusuario', $dados);

    }

}