<?php
defined("BASEPATH") or exit('No direct script access allowed');

class Email extends CI_Controller {
    
    public function __construct(){
        parent::__construct();

        $this->load->library('email');

        $config["charset"]      = 'utf-8';
        $config["wordwrap"]     = TRUE;
        $config["mailtype"]     = 'html';
        $config["protocol"]     = 'smtp';
        $config["smtp_host"]    = 'sgtgestaoetecnologia.com.br';
        $config["smtp_user"]    = 'sgt@sgtgestaoetecnologia.com.br';
        
    }

    public function recebe_documento($idprotocolo){

        

    }
}
 