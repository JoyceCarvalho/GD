<?php
class Pop_model extends CI_Model{
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * Método responsável por cadastrar os arquivos ou link do POP
     * Utilizado no controller Usuario.php 
     *
     * @param array $dados
     * @return int retorna o numero de linhas afetadas
     */
    public function cadastrar_pop($dados){
        return $this->db->insert('tbpop', $dados);
    }

    /**
     * Método responsável por retornar os pop cadastrados para determinado colaborador
     * Utilizado no controller Home.php 
     *
     * @param int $id
     * @return object retorna um objeto de dados
     */
    public function listar_pop($idusuario){
        $this->db->from('tbpop');
        $this->db->where('fk_idusuario', $idusuario);
        
        return $this->db->get('')->result();
    }

    /**
     * Método responsável por fazer a exclusão do pop
     * Utilizado no controller Usuario.php
     *
     * @param int $id
     * @return void
     */
    public function excluir_pop($id){
        $this->db->where('id', $id);
        
        return $this->db->delete('tbpop');
    }

    /**
     * Método responsável por retornar os dados existentes de determinado pop
     * Utilizado no controller Usuario.php
     *
     * @param int $idusuario
     * @return object 
     */
    public function pop_exist($id){
        $this->db->from('tbpop');
        $this->db->where('id', $id);

        return $this->db->get()->result();
    }

    /**
     * Método responsável por editar dados de determidado pop
     * Utilizado no controller Usuario.php 
     *
     * @param array $dados
     * @param int $id
     * @return int retorna o numero de linhas afetadas
     */
    public function editar_pop($dados, $id){
        $this->db->where("id", $id);
        return $this->db->update("tbpop", $dados);
    }
}