<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

	function __construct(){
		parent::__construct();

		$this->load->model('usuario_model', 'usermodel');
		$this->load->model('horario_model', 'horasmodel');
		$this->load->model('empresa_model', 'empresamodel');
		$this->load->model('cargos_model', 'cargosmodel');
		$this->load->model('LogsSistema_model', 'logsistema');
		$this->load->model('pop_model', 'popmodel');
	}


	public function cadastro_usuario(){

		if((!isset($_SESSION["logado"])) and ($_SESSION["logado"] != true)){
			redirect('/');
		}

		$data = new stdClass();

		$this->load->library('form_validation');

		// set validation rules
		$this->form_validation->set_rules('nome', 'Nome', 'required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('usuario', 'Usuário', 'trim|required|min_length[3]|max_length[12]');
		$this->form_validation->set_rules('senha', 'Senha', 'trim|required|min_length[6]');
		
		if($this->form_validation->run() == FALSE){
			
			$this->session->set_flashdata('error', 'Dados inválidos!');        
            redirect('home/usuario_cad');

		} else {

			$dados = array(
				'nome' 			=> $this->input->post('nome'),
				'email' 		=> $this->input->post('email'),
				'usuario' 		=> $this->input->post('usuario'),
				'senha' 		=> $this->usermodel->hash_password($this->input->post('senha')),
				'fk_idcargos' 		=> $this->input->post('cargo'),
				'fk_idhorariotrab' 	=> $this->input->post('horas'),
				'fk_idempresa'		=> $_SESSION['idempresa']
			);

			$usuario = $this->usermodel->cadastrar_usuario($dados);
	
			if ($usuario) {
				
				$tipo_pop = $this->input->post("tipo_pop");
				$qnt_pop = $this->input->post('qnt_pop');

				if($qnt_pop > 0){
					
					for ($i=1; $i <= $qnt_pop; $i++) { 
					
						if($tipo_pop == "texto"){
						
							$pop = $this->input->post("pop_$i");
				
						} elseif($tipo_pop == "arquivo"){
							//echo "entra no laço!";
							$pop_name = $_FILES['pop_'.$i];
							//print_r($pop_name);
							$config['upload_path']          = './pop/';
							$config['allowed_types']        = 'pdf';
							$config['file_name']			= $pop_name["name"];
				
							$this->load->library('upload');
							$this->upload->initialize($config);
							if (!$this->upload->do_upload('pop_'.$i)){
				
								//$warning = "Ops! Pode ter ocorrido um problema ao cadastrar o POP do colaborador.";
								$this->session->set_flashdata("warning", "Ops! Ocorreu um problema ao cadastrar o POP do colaborador.");
								$pop = null;
				
							} else {
								
								$pop = $this->upload->file_name;
							
							}
				
						} 

						$arquivos_pop = array(
							'arquivo' 	   => $pop,
							'fk_idusuario' => $usuario
						);

						if(!$this->popmodel->cadastrar_pop($arquivos_pop)){
							$this->session->set_flashdata('warning', "Ocorreu um problema ao cadastrar o(s) POP(s) do colaborador!");
						}

					}
				}

				/**
				 * Envio de email
				 */
				$this->load->model('email_model', 'emailmodel');
	
				$empresa = $this->empresamodel->dados_empresa($_SESSION["idempresa"]);

				foreach ($empresa as $dados) {
					
					$enviar = array(
						'tipo'         => 'novo_usuario',
						'pass' 		   => $this->input->post('senha'),
						'cliente_code' => $dados->cliente_code,
						'email'    	   => $this->input->post('email'),
						'nome'         => $this->input->post('nome'),
						'usuario'	   => $this->input->post('usuario')
					);
					
				}
				$this->emailmodel->enviar_email($enviar);
	
				/**
				 * Fim do envio de email
				 */

				 //log do sistema
				$mensagem = "Cadastrou novo usuario " . $this->input->post('nome');
				$log = array(
					'usuario' 	=> $_SESSION["idusuario"], 
					'mensagem' 	=> $mensagem,
					'data_hora' => date('Y-m-d H:i:s')
				);
				$this->logsistema->cadastrar_log_sistema($log);
				//fim log do sistema
	
				$success = "Usuário ".$this->input->post('nome')." cadastrado com sucesso!";

				$this->session->set_flashdata('success', $success);
				redirect('home/usuario_cad');
	
				
			} else {
	
				//$data->error = "Não foi possivel cadastrar o usuário! Favor entre em contato com suporte e tente mais tarde novamente!";
				$this->session->set_flashdata("error", "Não foi possível cadastrar o usuário! Favor entre em contato com nosso suporte e tente mais tarde novamente!");
				redirect("home/usuario_cad");
	
				
			}
		}

	}

	public function editar_usuario(){

		if((!isset($_SESSION["logado"])) and ($_SESSION["logado"] != true)){
			redirect('/');
		}

		$data = new stdClass();

		$idusuario = $this->input->post('idusuario');

		$dados = array(
			'nome' 				=> $this->input->post('nome'),
			'email' 			=> $this->input->post('email'),
			'usuario' 			=> $this->input->post('usuario'),
			'fk_idcargos' 		=> $this->input->post('cargo'),
			'fk_idhorariotrab' 	=> $this->input->post('horas')
		);

		if ($this->usermodel->editar_usuario($dados, $idusuario)) {

			$tipo_pop = $this->input->post("tipo_pop");
			$qnt_pop = $this->input->post('qnt_pop');

			if($qnt_pop > 0){
				
				for ($i=1; $i <= $qnt_pop; $i++) { 

					if(empty($this->input->post("id_arquivo_$i"))){

						if($tipo_pop == "texto"){
						
							$pop = $this->input->post("pop_$i");
				
						} elseif($tipo_pop == "arquivo"){
							//echo "entra no laço!";
							$pop_name = $_FILES['pop_'.$i];
							//print_r($pop_name);
							if(!empty($pop_name)){
								$config['upload_path']          = './pop/';
								$config['allowed_types']        = 'pdf';
								$config['file_name']			= $pop_name["name"];
					
								$this->load->library('upload');
								$this->upload->initialize($config);
								if (!$this->upload->do_upload('pop_'.$i)){
					
									//$warning = "Ops! Pode ter ocorrido um problema ao cadastrar o POP do colaborador.";
									$this->session->set_flashdata("warning", "Ops! Ocorreu um problema ao cadastrar o POP do colaborador.");
									$pop = null;
					
								} else {
									
									$pop = $this->upload->file_name;
								
								}
							}
				
						} 
	
						if(!empty($pop)){
							$arquivos_pop = array(
								'arquivo' 	   => $pop,
								'fk_idusuario' => $idusuario
							);
		
							if(!$this->popmodel->cadastrar_pop($arquivos_pop)){
								$this->session->set_flashdata('warning', "Ocorreu um problema ao cadastrar o(s) POP(s) do colaborador!");
							}
						}

					} else {
						
						if(($this->input->post("anexo_".$i) == "false") and ($tipo_pop == "texto")){

							$id_pop = $this->input->post("id_arquivo_$i");
							$dados_pop = array('arquivo' => $this->input->post("pop_$i"));
							
							$this->popmodel->editar_pop($dados_pop, $id_pop);

						}

						
					}

					if($this->input->post("anexo_".$i) == "true"){
						
						$id_pop = $this->input->post('id_arquivo_'.$i);

						$this->popmodel->excluir_pop($id_pop);
						//echo $id_pop;

						if($tipo_pop == "arquivo"){

							$dados_pop = $this->popmodel->pop_exist($id_pop);
														
							foreach ($dados_pop as $pop) {
								
								$dir = './pop/'.$pop->arquivo;
								
								if(!unlink($dir)){
	
									$this->session->set_flashdata('warning', 'Ocorreu um problema ao excluir o arquivo!');
	
								}
							}
						}

					} 

				}

			}

			$this->session->set_flashdata('success', 'Usuário atualizado com sucesso!');

			//log do sistema
			$mensagem = "Edição de dados do usuário ". $this->input->post('nome');
			$log = array(
				'usuario'  => $_SESSION["idusuario"], 
				'mensagem'  => $mensagem,
				'data_hora' => date("Y-m-d H:i:s")
			);
			//$this->logsistema->cadastrar_log_sistema($log);
			//fim log do sistema

		} else {

			$this->session->set_flashdata('error', 'Ocorreu um erro ao atualizar os dados do usuário! Favor entre em contato com o suporte e tente novamente mais tarde!');
			
		}

		redirect("home/usuario/");

	}

	public function excluir_usuario(){

		$id = $this->input->post('idusuario');

		$data = new stdClass();

		if ((isset($_SESSION["logado"])) && $_SESSION['logado'] == true) {

			if($this->usermodel->excluir_usuario($id)){

				$this->load->model('Documentos_model', 'docmodel');

				if($this->docmodel->verifica_documento_execucao($id)){

					$documentos = $this->docmodel->verifica_documento_execucao($id);

					foreach ($documentos as $doc) {

						$this->docmodel->editar_documentos_log($doc->documento);
						
						$pendente = array(
							'documento'     => $doc->documento, 
							'etapa'         => $doc->etapa,
							'usuario'       => 0,
							'descricao'     => 'PENDENTE',
							'data_hora'     => date("Y-m-d H:i:s"),
							'ultima_etapa'  => 'true'
						);
		
						$documento_log2 = $this->docmodel->cadastrar_log_documento($pendente);	
					}
					
				}

				$this->session->set_flashdata('success', 'Usuário excluido com sucesso!');
				
				//log do sistema
				$mensagem = "Excluiu o usuario $id";
				$log = array(
					'usuario' => $_SESSION["idusuario"], 
					'mensagem' => $mensagem,
					'data_hora' => date("Y-m-d H:i:s")
				);
				$this->logsistema->cadastrar_log_sistema($log);
				//fim log do sistema

			} else {

				$this->session->set_flashdata('error', 'Ocorreu um problema ao excluir o usuário! Favor entre em contato com o suporte e tente mais tarde novamente!');

			}

			redirect('home/usuario/');

		} else {

			redirect("/");

		}


	}

	public function alterar_senha(){

		$data = new stdClass();

		$usuario = $this->input->post('usuario');

		$altera_senha = array(
			'senha' => $this->usermodel->hash_password($this->input->post('senha'))
		);

		if ($this->usermodel->alterar_senha($usuario,$altera_senha)) {

			$this->session->set_flashdata('success', 'Senha alterada com sucesso!');
			
			//Log do sistema
			$mensagem = "Alterou a senha do usuario $usuario";
			$log = array(
				'usuario' => $_SESSION["idusuario"], 
				'mensagem' => $mensagem,
				'data_hora' => date('Y-m-d H:i:s')
			);
			$this->logsistema->cadastrar_log_sistema($log);
			//fim log do sistema

		} else {

			$this->session->set_flashdata('error', 'Ocorreu um problema ao alterar a senha! Favor entre em contato com o suporte e tente novamente mais tarde.');
		}

		redirect("editar_usuario");

	}

	public function verifica_user(){

		$usuario = $_POST["usuario"];

		$user = count($this->usermodel->verifica_usuario($usuario, $_SESSION["idempresa"]));

		if($user > 0){
			echo json_encode(array('mensagem' => 'Esse usuário já existe no sistema! Favor tentar outro.', 'valido' => 'not'));
		} else {
			echo json_encode(array('mensagem' => 'Usuário válidado!', 'valido' => 'is'));
		}

	}

	public function download_arquivo($idusuario){
		
		if((!isset($_SESSION["logado"])) && ($_SESSION['logado'] != true)){
			redirect("/");
		}

		$arquivos = $this->popmodel->listar_pop($idusuario);

		if(empty($arquivos)){

			$this->session->set_flashdata('warning', 'Não arquivos não encontrados');
			
		} else {
			
			if(count($arquivos) > 1){
				//print_r($arquivos);
				//exit();
				$this->load->library('zip');

				foreach ($arquivos as $arq) {
					//echo $arq->arquivo;
					//$this->zip->add_data('./pop/'.$arq->arquivo);
					$path = './pop/'.$arq->arquivo;
					//echo $path;exit();./pop/mps.br_tcc.pdf
					$this->zip->add_data($path, file_get_contents($path));

				}
			
				if(!empty($arquivos)){
					$this->zip->archive("/path/pop_$idusuario.zip");
					$this->zip->download("pop_$idusuario.zip");		
				}
			} elseif(count($arquivos) == 1){
				
				$this->load->helper('download');
				
				foreach ($arquivos as $itens){
					$diretorio = file_get_contents('./pop/'.$itens->arquivo);         
					$arquivo = $itens->arquivo;
					force_download($arquivo, $diretorio);      
				}  
			}

		}
	}

	public function download_file($id_file){

		if((!isset($_SESSION["logado"])) && ($_SESSION['logado'] != true)){
			redirect("/");
		}

		$this->load->helper('download');

		$arquivos = $this->popmodel->pop_exist($id_file);
				
		foreach ($arquivos as $itens){
			$diretorio = file_get_contents('./pop/'.$itens->arquivo);         
			$arquivo = $itens->arquivo;
			force_download($arquivo, $diretorio);      
		}  


	}

}
