<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function cadastrar_usuario($dados){
        
        return $this->db->insert('tbusuario', $dados);
    }

    public function dados_usuario($id){
        $this->db->from('tbusuario');
        $this->db->where('id =', $id);

        return $this->db->get('')->result();
    }

    public function editar_usuario($dados, $id){

        $this->db->where('id = ', $id);
        return $this->db->update('tbusuario', $dados);
    }

    public function excluir_usuario($id){

        $this->db->set('ativo', 0, false);
        $this->db->where('id =', $id);
        return $this->db->update('tbusuario');
    }

    /**
     * Método responsável por verificar se os dados fornecidos para login são validos
     *
     * @param string $usuario
     * @param string $senha
     * @param string $cliente_code
     * @return void
     */
    public function verificar_dados($usuario, $senha, $cliente_code){

        $this->db->select("u.usuario as usuario, u.senha as senha, e.id as idempresa, e.cliente_code as cliente_code");
        $this->db->from('tbusuario as u');
        $this->db->join('tbempresa as e', 'u.fk_idempresa = e.id and e.ativo = 1');
        $this->db->where("e.cliente_code = '{$cliente_code}'");
        $this->db->where("u.usuario = '{$usuario}'");
        $this->db->where("u.senha = '{$senha}'");

        return $this->db->get('')->result();

    }
    
    /**
     * Método responsável por retornar o id do usuario logado
     *
     * @param int $usuario
     * @param string $cliente_code
     * @return idusuario retorna o id do usuario
     */
    public function get_id_por_usuario($usuario, $cliente_code){
        
        $this->db->select('tbusuario.id as id');
        $this->db->from('tbusuario');
        $this->db->join('tbempresa', 'tbusuario.fk_idempresa = tbempresa.id');
        $this->db->where("tbusuario.usuario = '{$usuario}'");
        $this->db->where("tbempresa.cliente_code = '{$cliente_code}'");

		return $this->db->get()->row('id');
    }

    /**
     * Método responsável por retornar os dados do usuário
     *
     * @param int $user_id
     * @return object retorna o objeto de dados
     */
    public function get_user($user_id){
        
        $this->db->from('tbusuario');
		$this->db->where('id', $user_id);
        return $this->db->get()->row();
        
    }

    /**
     * Método responsável por verificar se o usuário logado é administrador
     *
     * @param int $id_user
     * @return object retorna um objeto de dados
     */
    public function is_admin($id_user){

        $this->db->select('tbusuario.fk_idcargos as cargo');
        $this->db->from('tbusuario');
        $this->db->join('tbcargos', 'tbusuario.fk_idcargos = tbcargos.id');
        $this->db->where('tbusuario.id', $id_user);
        $this->db->where("tbcargos.titulo = 'Administrador Master'");

        return $this->db->get('')->result();
    }

    /**
     * Método responsável posr verificar se é coordenador
     *
     * @param int $id_user
     * @return object retorna um objeto de dados
     */
    public function is_coordenador($id_user){

        $this->db->select('tbusuario.fk_idcargos as cargo');
        $this->db->from('tbusuario');
        $this->db->join('tbcargos', 'tbusuario.fk_idcargos = tbcargos.id');
        $this->db->where('tbusuario.id', $id_user);
        $this->db->where("tbcargos.titulo = 'Coordenador'");

        return $this->db->get('')->result();
    }

    /**
     * Método responsável por identificar se é o sgt master
     *
     * @return object retorna um objeto de dados
     */
    public function sgt_admin(){
        $this->db->select("cliente_code");
        $this->db->from('tbempresa');
        $this->db->where("cliente_code = 'sgtgestaoetecnologia'");

        return $this->db->get()->result();
    }

    /**
     * Método responsável por listar os usuários da empresa
     *
     * @param int $empresa
     * @return object retorna um objeto de dados
     */
    public function listar_usuarios($empresa){

        $this->db->select('u.id as id, u.nome as nome, u.usuario, c.titulo as cargo');
        $this->db->from('tbusuario as u');
        $this->db->join('tbcargos as c', 'u.fk_idcargos = c.id');
        $this->db->where('u.ativo = 1');
        $this->db->where('u.fk_idempresa =', $empresa);
        $this->db->order_by('u.nome');

        return $this->db->get('')->result();
    }

    /**
     * Método responsável por listar os usuários da empresa em formato json
     * Utilizado no controller Competencia.php
     *
     * @param int $empresa
     * @return json
     */
    public function listar_usuarios_json($empresa){
        $this->db->select('u.id as id, u.nome as nome, u.usuario, c.titulo as cargo');
        $this->db->from('tbusuario as u');
        $this->db->join('tbcargos as c', 'u.fk_idcargos = c.id');
        $this->db->where('u.ativo = 1');
        $this->db->where('u.fk_idempresa =', $empresa);
        $this->db->order_by('u.nome');

        return json_encode($this->db->get('')->result());
    }

    public function alterar_senha($id, $senha){

        $this->db->where('id', $id);
        return $this->db->update('tbusuario', $senha);

    }

}