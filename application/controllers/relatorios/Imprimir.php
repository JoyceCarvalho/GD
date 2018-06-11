<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Imprimir extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
    }

    public function imprimir_finalizados($id){

        $dados["imformacoes_documento"] = $this->docmodel->dados_documento_finalizado($id);

        $this->load->view('relatorios/imprimir/relatorios_finalizados');

    }
}
