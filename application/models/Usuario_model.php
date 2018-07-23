<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    /**
     * Método responsável por cadastrar os usuários
     * Utilizado no controller Usuario.php
     *
     * @param array $dados
     * @return int retorna o numero de linhas afetadas
     */
    public function cadastrar_usuario($dados){
        
        return $this->db->insert('tbusuario', $dados);
    }

    /**
     * Método responsável por retornar os dados de um determinado usuário
     * Utilizado no controller Usuário.php
     *
     * @param int $id
     * @return object retorna um objeto de dados
     */
    public function dados_usuario($id){
        $this->db->from('tbusuario');
        $this->db->where('id =', $id);

        return $this->db->get('')->result();
    }

    /**
     * Método responsável por editar os dados do usuário
     * Utilizado no controller Usuario.php
     *
     * @param array $dados
     * @param int $id
     * @return int retorna o numero de linhas afetadas
     */
    public function editar_usuario($dados, $id){

        $this->db->where('id = ', $id);
        return $this->db->update('tbusuario', $dados);
    }

    /**
     * Método responsável por "excluir" o usuario (apenas troca o ativo por 0 para que os dados referentes a esse usuario não sejam apagados também)
     * Utilizado no controller Usuario.php
     *
     * @param int $id
     * @return int retorna o numero de linhas afetadas
     */
    public function excluir_usuario($id){

        $this->db->set('ativo', 0, false);
        $this->db->where('id =', $id);
        return $this->db->update('tbusuario');
    }

    /**
     * Método responsável por verificar se os dados fornecidos para login são validos
     * Utilizado no controller Login.php
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
        $this->db->where('u.ativo = 1');

        return $this->db->get('')->result();

    }

    /**
     * Método responsável por verificar se o usuário já esta cadastrado no banco de dados
     * Utilizado no controller Usuario.php
     *
     * @param string $usuario
     * @param int $empresa
     * @return object
     */
    public function verifica_usuario($usuario, $empresa){

        $this->db->from('tbusuario');
        $this->db->where('fk_idempresa', $empresa);
        $this->db->where('usuario', $usuario);
        $this->db->where('ativo = 1');

        return $this->db->get('')->result();
    }
    
    /**
     * Método responsável por retornar o id do usuario logado
     * Utilizado no controller Login.php
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
     * Utilizado no controller Login.php e relatorios/Imprimir.php
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
     * Utilizado no controller Login.php
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
     * Utilizado no controller Login.php
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
     * Método responsável por listar os usuários da empresa
     * Utilizado no controller Usuario.php
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
     * Utilizado no controller conf/Competencia.php
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

    /**
     * Método responsável por alterar a senha do usuário
     * Utilizado no controller Usuario.php
     *
     * @param int $id
     * @param string $senha
     * @return int retorna o numero de linhas afetadas
     */
    public function alterar_senha($id, $senha){

        $this->db->where('id', $id);
        return $this->db->update('tbusuario', $senha);

    }

    /**
     * Método responsável por verificar os usuarios aptos por cargo
     * Utilizado no controller documentos/Transferencia.php
     *
     * @param int $cargo
     * @return object retorna um objeto de dados
     */
    public function usuario_por_cargo($cargo, $dataAtual){
        //subquery1
        $this->db->select('fk_idusuario');
        $this->db->from('tbausencia');
        $this->db->where('dia_inicio >', $dataAtual);
        $this->db->where('dia_fim < ', $dataAtual);
        $subquery1 = $this->db->get_compiled_select();

        //subquery2
        $this->db->select('fk_idusuario');
        $this->db->from('tbferias_func');
        $this->db->where('dia_inicio >', $dataAtual);
        $this->db->where('dia_fim <', $dataAtual);
        $subquery2 = $this->db->get_compiled_select(); 

        //query
        $this->db->from('tbusuario');
        $this->db->where('fk_idcargos', $cargo);
        $this->db->where('ativo = 1');
        $this->db->where("id not in ($subquery1)");
        $this->db->where("id not in ($subquery2)");
        
        return $this->db->get('')->result();

    }

    /**
     * Método responsável por listar os usuários disponíveis para receber o documento
     * Utilizado no controller documentos/Relatorios.php 
     *
     * @param int $empresa
     * @return json
     */
    public function usuarios_disponiveis($empresa){
        //Subquery1
        $this->db->select('fk_idusuario');
        $this->db->from('tbferias_func');
        $this->db->where('NOW() < dia_inicio');
        $this->db->where('NOW() > dia_fim');
        $subquery = $this->db->get_compiled_select();

        //Subquery2
        $this->db->select('fk_idusuario');
        $this->db->from('tbausencia');
        $this->db->where('NOW() >= dia_inicio');
        $this->db->where('NOW() <= dia_fim');
        $subquery2 = $this->db->get_compiled_select();

        //query main
        $this->db->from("tbusuario");
        $this->db->where("id not in($subquery)");
        $this->db->where("id not in($subquery2)");
        $this->db->where("fk_idempresa", $empresa);
        $this->db->where('ativo = 1');
        return json_encode($this->db->get()->result());
    }

}