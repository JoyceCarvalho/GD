<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('usuario_model', 'usermodel');
		$this->load->model('horario_model', 'horasmodel');
		$this->load->model('empresa_model', 'empresamodel');
		$this->load->model('cargos_model', 'cargosmodel');
		$this->load->model('LogsSistema_model', 'logsistema');
	}


	public function cadastro_usuario(){

		$data = new stdClass();

		$this->load->library('form_validation');

		// set validation rules
		$this->form_validation->set_rules('nome', 'Nome', 'required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('usuario', 'Usuário', 'trim|required|min_length[5]|max_length[12]');
		$this->form_validation->set_rules('senha', 'Senha', 'trim|required|min_length[6]');

		if($this->form_validation->run() == FALSE){
			
			$dados['pagina'] 	= "Usuários";
			$dados['pg'] 		= "empresa";
			$dados['submenu'] 	= "usuario";
			$dados["sub"]		= "usuariocad";

			$dados['listagem_usuarios'] = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);
			$dados['full_cargos']	 	= $this->cargosmodel->listar_cargos($_SESSION["idempresa"]);
			$dados['full_horarios'] 	= $this->horasmodel->listar_horario($_SESSION["idempresa"]);
			//dados do banco (nome da empresa, nome usuário);
			$dados['nome_empresa'] 		= $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

			$this->load->view('template/html_header', $dados);
			$this->load->view('template/header');
			$this->load->view('template/menu', $data);
			$this->load->view('usuario_cadastro');
			$this->load->view('template/footer');
			$this->load->view('template/html_footer');

		} else {

			$dados = array(
				'nome' 				=> $this->input->post('nome'),
				'email' 			=> $this->input->post('email'),
				'usuario' 			=> $this->input->post('usuario'),
				'senha' 			=> sha1($this->input->post('senha')),
				'fk_idcargos' 		=> $this->input->post('cargo'),
				'fk_idhorariotrab' 	=> $this->input->post('horas'),
				'fk_idempresa'		=> $_SESSION['idempresa']
			);
	
			if ($this->usermodel->cadastrar_usuario($dados)) {
	
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
	
				$data->success = "Usuário ".$this->input->post('nome')." cadastrado com sucesso!";
	
				$info['pagina'] 	= "Usuários";
				$info['pg'] 		= "empresa";
				$info['submenu'] 	= "usuario";
				$info["sub"]		= "usuariocad";
	
				$info['listagem_usuarios'] = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);
				$info['full_cargos'] 		= $this->cargosmodel->listar_cargos($_SESSION["idempresa"]);
				$info['full_horarios'] 	= $this->horasmodel->listar_horario($_SESSION["idempresa"]);
				//dados do banco (nome da empresa, nome usuário);
				$info['nome_empresa'] 		= $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
	
				$this->load->view('template/html_header', $info);
				$this->load->view('template/header');
				$this->load->view('template/menu', $data);
				$this->load->view('usuario_cadastro');
				$this->load->view('template/footer');
				$this->load->view('template/html_footer');
	
				//log do sistema
				$mensagem = "Cadastrou novo usuario " . $this->input->post('nome');
				$log = array(
					'usuario' 	=> $_SESSION["idusuario"], 
					'mensagem' 	=> $mensagem,
					'data_hora' => date('Y-m-d H:i:s')
				);
				$this->logsistema->cadastrar_log_sistema($log);
				//fim log do sistema
	
			} else {
	
				$data->error = "Não foi possivel cadastrar o usuário! Favor entre em contato com suporte e tente mais tarde novamente!";
	
				$dados['pagina'] 	= "Usuários";
				$dados['pg'] 		= "empresa";
				$dados['submenu'] 	= "usuario";
				$dados["sub"]		= "usuariocad";
	
				$dados['listagem_usuarios'] = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);
				$dados['full_cargos']	 	= $this->cargosmodel->listar_cargos($_SESSION["idempresa"]);
				$dados['full_horarios'] 	= $this->horasmodel->listar_horario($_SESSION["idempresa"]);
				//dados do banco (nome da empresa, nome usuário);
				$dados['nome_empresa'] 		= $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
	
				$this->load->view('template/html_header', $dados);
				$this->load->view('template/header');
				$this->load->view('template/menu', $data);
				$this->load->view('usuario_cadastro');
				$this->load->view('template/footer');
				$this->load->view('template/html_footer');
			}
		}

	}

	public function editar_usuario(){

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

			$data->success = "Usuário atualizado com sucesso!";

			$dados['pagina'] 	= "Usuários";
			$dados['pg'] 		= "empresa";
			$dados['submenu'] 	= "usuario";

			$dados['full_cargos'] 	= $this->cargosmodel->listar_cargos($_SESSION["idempresa"]);
			$dados['full_horarios'] = $this->horasmodel->listar_horario($_SESSION["idempresa"]);
			$dados['usuario'] 		= $this->usermodel->dados_usuario($idusuario);
			//dados do banco (nome da empresa);
			$dados['nome_empresa'] 	= $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

			$this->load->view('template/html_header', $dados);
			$this->load->view('template/header');
			$this->load->view('template/menu', $data);
			$this->load->view('usuarios_edit');
			$this->load->view('template/footer');
			$this->load->view('template/html_footer');

			//log do sistema
			$mensagem = "Edição de dados do usuário ". $this->input->post('nome');
			$log = array(
				'usuario'  => $_SESSION["idusuario"], 
				'mensagem'  => $mensagem,
				'data_hora' => date("Y-m-d H:i:s")
			);
			$this->logsistema->cadastrar_log_sistema($log);
			//fim log do sistema

		} else {

			$data->error = "Ocorreu um erro ao atualizar dados do usuário! Favor entre em contato com o suporte e tente novamente mais tarde!";

			$dados['pagina'] 	= "Usuários";
			$dados['pg'] 		= "empresa";
			$dados['submenu'] 	= "usuario";

			$dados['full_cargos'] 	= $this->cargosmodel->listar_cargos($_SESSION["idempresa"]);
			$dados['full_horarios'] = $this->horasmodel->listar_horario($_SESSION["idempresa"]);
			$dados['usuario'] 		= $this->usermodel->dados_usuario($idusuario);
			//dados do banco (nome da empresa);
			$dados['nome_empresa'] 	= $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

			$this->load->view('template/html_header', $dados);
			$this->load->view('template/header');
			$this->load->view('template/menu', $data);
			$this->load->view('usuarios_edit');
			$this->load->view('template/footer');
			$this->load->view('template/html_footer');

		}
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

				$data->success = "Usuário excluido com sucesso!";

				$dados['pagina'] 	= "Usuários";
				$dados['pg'] 		= "empresa";
				$dados['submenu'] 	= "usuario";
				$dados['sub']		= "usuariolist";

				$dados['listagem_usuarios'] = $this->usermodel->listar_usuarios($_SESSION["idempresa"]);
				//dados do banco (nome da empresa, nome usuário);
				$dados['nome_empresa'] 		= $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

				$this->load->view('template/html_header', $dados);
				$this->load->view('template/header');
				$this->load->view('template/menu', $data);
				$this->load->view('usuarios');
				$this->load->view('template/footer');
				$this->load->view('template/html_footer');

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

				$data->error = "Ocorreu um problema ao excluir o usuário! Favor entre em contato com o suporte e tente mais tarde novamente!";

				$dados['pagina'] 	= "Usuários";
				$dados['pg'] 		= "empresa";
				$dados['submenu'] 	= "usuario";
				$dados['sub']		= "usuariolist";

				$dados['listagem_usuarios'] = $this->usermodel->listar_usuarios($id);
				//dados do banco (nome da empresa);
				$dados['nome_empresa'] 		= $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

				$this->load->view('template/html_header', $dados);
				$this->load->view('template/header');
				$this->load->view('template/menu', $data);
				$this->load->view('usuarios');
				$this->load->view('template/footer');
				$this->load->view('template/html_footer');

			}

		} else {

			redirect("/");

		}


	}

	public function alterar_senha(){

		$data = new stdClass();

		$usuario = $this->input->post('usuario');

		$altera_senha = array(
			'senha' => sha1($this->input->post('senha'))
		);

		if ($this->usermodel->alterar_senha($usuario,$altera_senha)) {

			$data->success = "Senha alterada com sucesso!";

			$dados['pagina'] 	= "Usuários";
			$dados['pg'] 		= "empresa";
			$dados['submenu'] 	= "usuario";

			$dados['full_cargos'] 	= $this->cargosmodel->listar_cargos($_SESSION["idempresa"]);
			$dados['full_horarios'] = $this->horasmodel->listar_horario($_SESSION["idempresa"]);
			$dados['usuario'] 		= $this->usermodel->dados_usuario($usuario);
			//dados do banco (nome da empresa);
			$dados['nome_empresa'] 	= $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

			$this->load->view('template/html_header', $dados);
			$this->load->view('template/header');
			$this->load->view('template/menu', $data);
			$this->load->view('usuarios_edit');
			$this->load->view('template/footer');
			$this->load->view('template/html_footer');

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

			$data->error = "Ocorreu um problema ao alterar a senha. Favor entre em contato com o suporte e tente novamente mais tarde.";

			$dados['pagina'] 	= "Usuários";
			$dados['pg'] 		= "empresa";
			$dados['submenu'] 	= "usuario";

			$dados['full_cargos'] 	= $this->cargosmodel->listar_cargos($_SESSION["idempresa"]);
			$dados['full_horarios'] = $this->horasmodel->listar_horario($_SESSION["idempresa"]);
			$dados['usuario'] 		= $this->usermodel->dados_usuario($usuario);
			//dados do banco (nome da empresa);
			$dados['nome_empresa'] 	= $this->empresamodel->nome_empresa($_SESSION["idempresa"]);

			$this->load->view('template/html_header', $dados);
			$this->load->view('template/header');
			$this->load->view('template/menu', $data);
			$this->load->view('usuarios_edit');
			$this->load->view('template/footer');
			$this->load->view('template/html_footer');
		}

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

}
