<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Transferencia extends CI_Controller {
    public function __construct(){
        
        parent::__construct();

        $this->load->model('documentos_model', 'docmodel');
        $this->load->model('horario_model', 'horasmodel');
        $this->load->model('DocEtapas_model', 'docetapamodel');
        $this->load->model('competencia_model', 'compmodel');
        $this->load->model('usuario_model', 'usermodel');
        $this->load->model('empresa_model', 'empresamodel');
        $this->load->model('etapas_model', 'etapasmodel');

    }

    public function transfere_etapa($identificador){

        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] == true )) {
            redirect("/");
        }

        //transforma o hash com o id (string) em um array
        $id = str_split($identificador);

        //pega o tamanho total do array (quantidade de caracteres)
        $tamanho = count($id);

        //Inicia a variavel (sem inicializar a variavel aparece um erro no CI)
        $protocolo = "";

        //percorre o array para retirar o id correto do restante da hash
        for ($i=32; $i < $tamanho; $i++) { 
            $protocolo .= $id[$i];
        }

        //transforma a string em inteiro
        $idprotocolo = (int)$protocolo;

        //instância a classe stdClass para a variavel dados poder receber os dados de um objeto
        $dados = new stdClass;

        //recebe os objetos retornados pela função documento_transferencia(int);
        $dados = $this->docmodel->documento_tranferencia($idprotocolo);

        //armazena o atributo data_hora do objeto dados;
        $dataInicio = $dados->data_hora;
        //armazena o atributo etapa do objeto dados;
        $etapa = $dados->etapa;

        
        $horario = $this->horasmodel->verifica_horario($_SESSION["idempresa"]);

        $primeiro_turno_inicio  = $horario->manha_entrada;
        $primeiro_turno_fim     = $horario->manha_saida;
        $segundo_turno_inicio   = $horario->tarde_entrada;
        $segundo_turno_fim      = $horario->tarde_saida;

        $primeiro_turno_inicio_ex = explode(":", $primeiro_turno_inicio);
        $primeiro_turno_inicio_min = ($primeiro_turno_inicio_ex[0] * 60) + $primeiro_turno_inicio_ex[1];

        $primeiro_turno_fim_ex = explode(":", $primeiro_turno_fim);
        $primeiro_turno_fim_min = ($primeiro_turno_fim_ex[0] * 60) + $primeiro_turno_fim_ex[1];

        $segundo_turno_inicio_ex = explode(":", $segundo_turno_inicio);
        $segundo_turno_inicio_min = ($segundo_turno_inicio_ex[0] * 60) + $segundo_turno_inicio_ex[1];

        $segundo_turno_fim_ex = explode(":", $segundo_turno_fim);
        $segundo_turno_fim_min = ($segundo_turno_fim_ex[0] * 60) + $segundo_turno_fim_ex[1];

        $minutos_turno_primeiro = ($primeiro_turno_fim_min - $primeiro_turno_inicio_min);
        $minutos_turno_segundo = ($segundo_turno_fim_min - $segundo_turno_inicio_min);
        $minutos_turno_total = $minutos_turno_primeiro + $minutos_turno_segundo;

        $dataFim = date("Y-m-d H:i:s");

        $dataInicioExplode = explode(" ", $dataInicio);
        $dataInicioQuebrada = $dataInicioExplode[0];
        $HoraInicioQuebrada = $dataInicioExplode[1];
        $HoraInicioQuebrada = explode(":", $HoraInicioQuebrada);
        $HoraInicioQuebrada = $HoraInicioQuebrada[0].":".$HoraInicioQuebrada[1];


        $dataFimExplode = explode(" ", $dataFim);
        $dataFimQuebrada = $dataFimExplode[0];
        $HoraFimQuebrada = $dataFimExplode[1];
        $HoraFimQuebrada = explode(":", $HoraFimQuebrada);
        $HoraFimQuebrada = $HoraFimQuebrada[0].":".$HoraFimQuebrada[1];

        $dataInicioVerifica = $dataInicioQuebrada;

        
        if ($dataInicioQuebrada == $dataFimQuebrada) {
            
            $somatorioTotalMinutos = 0;

            if ((($HoraInicioQuebrada >= $primeiro_turno_inicio) and ($HoraInicioQuebrada <= $primeiro_turno_fim)) and (($HoraFimQuebrada >= $segundo_turno_inicio) and ($HoraFimQuebrada <= $segundo_turno_fim))) {
                
                $HoraInicioQuebradaExplode = explode(":", $HoraInicioQuebrada);
                $HoraInicioQuebradaMinutos = ($HoraInicioQuebradaExplode[0] * 60) + $HoraInicioQuebradaExplode[1];

                $minutos_a = $primeiro_turno_fim_min - $HoraInicioQuebradaMinutos;

                $HoraFimQuebradaExplode = explode(":", $HoraFimQuebrada);
                $HoraFimQuebradaMinutos = ($HoraFimQuebradaExplode[0] * 60) + $HoraFimQuebradaExplode[1];

                $minutos_b = $segundo_turno_fim_min - $HoraFimQuebradaMinutos;

                $minutos = $minutos_a + $minutos_b;

                $somatorioTotalMinutos = $minutos;

                //echo "<br/> Somatório de minutos ". $somatorioTotalMinutos;

            }elseif((($HoraInicioQuebrada >= $primeiro_turno_inicio) and ($HoraInicioQuebrada <= $primeiro_turno_fim)) and (($HoraInicioQuebrada >= $primeiro_turno_inicio) and ($HoraInicioQuebrada <= $primeiro_turno_fim))) {
                
                $HoraInicioQuebradaExplode = explode(":", $HoraInicioQuebrada);
                $HoraInicioQuebradaMinutos = ($HoraInicioQuebradaExplode[0] * 60) + $HoraInicioQuebradaExplode[1];
        
                $HoraFimQuebradaExplode = explode(":", $HoraFimQuebrada);
                $HoraFimQuebradaMinutos = ($HoraFimQuebradaExplode[0] * 60) + $HoraFimQuebradaExplode[1];
        
                $minutos = ($HoraFimQuebradaMinutos - $HoraInicioQuebradaMinutos);
        
                $somatorioTotalMinutos = $minutos;

                //echo "<br/> Somatório de minutos ". $somatorioTotalMinutos;


            }elseif( (($HoraInicioQuebrada >= $segundo_turno_inicio) and ($HoraInicioQuebrada <= $segundo_turno_fim)) and (($HoraInicioQuebrada >= $segundo_turno_inicio) and ($HoraInicioQuebrada <= $segundo_turno_fim))) {
                
                $HoraInicioQuebradaExplode = explode(":", $HoraInicioQuebrada);
                $HoraInicioQuebradaMinutos = ($HoraInicioQuebradaExplode[0] * 60) + $HoraInicioQuebradaExplode[1];
        
                $HoraFimQuebradaExplode = explode(":", $HoraFimQuebrada);
                $HoraFimQuebradaMinutos = ($HoraFimQuebradaExplode[0] * 60) + $HoraFimQuebradaExplode[1];
        
                $minutos = ($HoraFimQuebradaMinutos - $HoraInicioQuebradaMinutos);
        
                $somatorioTotalMinutos = $minutos;

                //echo "<br/> Somatório de minutos ". $somatorioTotalMinutos;

            }else{
                
                $HoraInicioQuebradaExplode = explode(":", $HoraInicioQuebrada);
                $HoraInicioQuebradaMinutos = ($HoraInicioQuebradaExplode[0] * 60) + $HoraInicioQuebradaExplode[1];
        
                $HoraFimQuebradaExplode = explode(":", $HoraFimQuebrada);
                $HoraFimQuebradaMinutos = ($HoraFimQuebradaExplode[0] * 60) + $HoraFimQuebradaExplode[1];
        
                $minutos = $HoraFimQuebradaMinutos - $HoraInicioQuebradaMinutos;
        
                $somatorioTotalMinutos = $minutos;

                //echo "<br/> Somatório de minutos ". $somatorioTotalMinutos;
        
            }

        } else {

            $i = 0;
            $contadorDias = 0;
            while($i != 1){
                $dataVerifica = strtotime ("+1 day", strtotime($dataInicioVerifica));
                $dataInicioVerifica = date ("Y-m-d", $dataVerifica);
                
                $contadorDias++;
                if($dataFimQuebrada == $dataInicioVerifica){
                    $i = 1;
                    $contadorDias--;
                }
            }

            $somatorioTotalMinutos = $contadorDias * $minutos_turno_total;
            $debugSomatorioTotalMinutos = $somatorioTotalMinutos;


            if($HoraInicioQuebrada <= $primeiro_turno_fim){
                
                $HoraInicioQuebradaExplode = explode(":", $HoraInicioQuebrada);
                $HoraInicioQuebradaMinutos = ($HoraInicioQuebradaExplode[0] * 60) + $HoraInicioQuebradaExplode[1];

                $minutos_inicio = ($primeiro_turno_fim_min - $HoraInicioQuebradaMinutos) + $minutos_turno_segundo;

            }else{
                
                $HoraInicioQuebradaExplode = explode(":", $HoraInicioQuebrada);
                $HoraInicioQuebradaMinutos = ($HoraInicioQuebradaExplode[0] * 60) + $HoraInicioQuebradaExplode[1];

                $minutos_inicio = ($segundo_turno_fim_min - $HoraInicioQuebradaMinutos);

            }

            $somatorioTotalMinutos = $somatorioTotalMinutos + $minutos_inicio;

            if($HoraFimQuebrada <= $primeiro_turno_fim){
                
                $HoraFimQuebradaExplode = explode(":", $HoraFimQuebrada);
                $HoraFimQuebradaMinutos = ($HoraFimQuebradaExplode[0] * 60) + $HoraFimQuebradaExplode[1];
                
                $minutos_fim = ($primeiro_turno_fim_min - $HoraFimQuebradaExplode);

            }else{
                
                $HoraFimQuebradaExplode = explode(":", $HoraFimQuebrada);
                $HoraFimQuebradaMinutos = ($HoraFimQuebradaExplode[0] * 60) + $HoraFimQuebradaExplode[1];

                $minutos_fim = ($HoraFimQuebradaMinutos - $segundo_turno_inicio_min) + $minutos_turno_primeiro;

            }

            $somatorioTotalMinutos = $somatorioTotalMinutos + $minutos_fim;

            //echo "<br/> Somatório de minutos ". $somatorioTotalMinutos;
        }

        $log_tempo = array(
            'id_protocolo'  => $idprotocolo, 
            'id_etapa'      => $etapa, 
            'data_inicio'   => $dataInicio, 
            'data_fim'      => $dataFim,  
            'total_minutos' => $somatorioTotalMinutos
        );

        if($this->docmodel->cadastrar_documento_tempo($log_tempo)){

            if($this->docmodel->editar_documentos_log($idprotocolo)){

                $id_documento = $this->docmodel->documento_id($idprotocolo);

                $ordem_etapa_atual = $this->docetapamodel->etapa_atual($id_documento, $etapa);

                $proxima_etapa_documento = $ordem_etapa_atual + 1;

                $proxima_etapa = $this->docetapamodel->proxima_etapa($id_documento, $proxima_etapa_documento);

                $verificarDataAusencia = date("Y-m-d");

                $verificadosUsuariosAptos = $this->compmodel->verifica_usuario_apto($id_documento, $proxima_etapa);

                if($verificadosUsuariosAptos == 0){

                    $pendente = array(
                        'documento'     => $idprotocolo, 
                        'etapa'         => $proxima_etapa,
                        'usuario'       => null,
                        'descricao'     => 'PENDENTE',
                        'data_hora'     => date("Y-m-d H:i:s"),
                        'ultima_etapa'  => 'true'
                    );

                   $this->docmodel->cadastrar_log_documento($pendente);

                } else {

                    //echo "Id documento: ". $id_documento. "<br/>";
                    //echo "proxima etapa: ". $proxima_etapa. "<br/>";
                    //echo "data ausencia: ". $verificarDataAusencia. "<br/>";

                    $usuariosAptos = $this->compmodel->usuario_apto($id_documento, $proxima_etapa, $verificarDataAusencia);
                    //echo "usuariosAptos: ";
                    //print_r($usuariosAptos);

                    foreach ($usuariosAptos as $usuarios ) {
                        
                        if ($usuarios->tipo == "funcionario") {
                            
                            $usuarios_aptos[] = $usuarios->fk_idusuario;
                            $usuariosAptosQuantidade[$usuarios->fk_idusuario] = 0;

                        } else {
                            
                            $usuariosAptosCargo = $this->usermodel->usuario_por_cargo($usuarios->fk_idcargo);

                            //echo "usuariosAptosCargo";
                            //print_r($usuariosAptosCargo);

                            foreach ($usuariosAptosCargo as $user ) {
                                
                                $usuarios_aptos[] = $user->id;
                                $usuariosAptosQuantidade[$user->id] = 0;
                                
                            }

                        }
                        

                    }     
                    
                    //echo "<br/><br/>".$usuarios_aptos;

                    $usuariosAptosImplode = implode(",", $usuarios_aptos);

                    $contaUsuariosAptos = count($usuarios_aptos);

                    $verificaNumeroDocumentos = $this->docmodel->numero_documentos($usuariosAptosImplode);

                    if ($verificaNumeroDocumentos == 0) {
                        
                        $numeroRandomico = rand(0, $contaUsuariosAptos);

                        $idEscolhido = $usuarios_aptos[$numeroRandomico];

                        $transfereProximaEtapa = array(
                            'descricao' => 'TRANSFERIDO',
                            'data_hora' => date("Y-m-D"),
                            'ultima_etapa' => 'true',
                            'usuario' => $idEscolhido,
                            'etapa' => $proxima_etapa,
                            'documento' => $idprotocolo
                        );

                        $this->docmodel->cadastrar_log_documento($transfereProximaEtapa);

                        $idMostraDirecionamento = $idEscolhido;
                    } else {

                        $usuarios_quantidada_documento = $this->docmodel->quantidade_documentos_usuario($usuariosAptosImplode);

                        foreach ($usuarios_quantidada_documento as $usuariosDocumento ) {
                            
                            $usuariosAptosQuantidade[$usuariosDocumento->usuario] = $usuariosDocumento->quantidade_documento;

                        }

                        asort($usuariosAptosQuantidade);

                        $controlaMenor = 1;
                        foreach ($usuariosAptosQuantidade as $key => $quantidade) {
                            if ($controlaMenor == 1) {
                                
                                $quantidadeVerificar = $quantidade;
                                $usuariosAptosPrimeiraEtapa[] = $key;

                            } else {

                                if ($quantidadeVerificar = $quantidade) {
                                    $usuariosAptosPrimeiraEtapa[] = $key;
                                }

                            }

                            $controlaMenor ++;

                        }

                        $contaUsuarioAptosPrimeiraEtapa = count($usuariosAptosPrimeiraEtapa);
                        
                        $numeroRandomicoPrimeiraEtapa = rand(0,$contaUsuarioAptosPrimeiraEtapa - 1);
                        
                        $idEscolhidoPrimeiraEtapa = $usuariosAptosPrimeiraEtapa[$numeroRandomicoPrimeiraEtapa];

                        $transfereProximaEtapa = array(
                            'descricao' => 'TRANSFERIDO', 
                            'data_hora' => date("Y-m-d H:i:s"),
                            'ultima_etapa' => 'true',
                            'usuario' => $idEscolhidoPrimeiraEtapa,
                            'etapa' => $proxima_etapa,
                            'documento' => $idprotocolo
                        );

                        $this->docmodel->cadastrar_log_documento($transfereProximaEtapa);

                        $idMostraDirecionamento = $idEscolhidoPrimeiraEtapa;

                    }

                }

            }
            
        }

        if(!empty($idMostraDirecionamento)){

            $mensagem = new stdClass();
            /*$dados    = new stdClass();*/

            $mensagem->success = "Documento transferido com sucesso!";
            
            redirect("meusdocumentos", $mensagem);

            /*$dados->pagina    = "Meus Documentos";
            $dados->pg        = "documentos";
            $dados->submenu   = "meusdocs";

            $dados->nome_empresa       = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados->documentos_cargo   = $this->docmodel->listar_meus_documentos_cargos($_SESSION["idusuario"]);
            $dados->documentos_usuario = $this->docmodel->listar_meus_documentos_funcionario($_SESSION["idusuario"]);
            

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $mensagem);
            $this->load->view('documentos/meus_documentos');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');*/

        } else {

            $mensagem->error = "Ocorreu um problema ao transferir o documento. Favor entre em contato com o suporte e tente novamente mais tarde.";

            redirect("meusdocumentos", $mensagem);

            /*$dados->pagina    = "Meus Documentos";
            $dados->pg        = "documentos";
            $dados->submenu   = "meusdocs";

            $dados->nome_empresa       = $this->empresamodel->nome_empresa($_SESSION["idempresa"]);
            $dados->documentos_cargo   = $this->docmodel->listar_meus_documentos_cargos($_SESSION["idusuario"]);
            $dados->documentos_usuario = $this->docmodel->listar_meus_documentos_funcionario($_SESSION["idusuario"]);
            

            $this->load->view('template/html_header', $dados);
            $this->load->view('template/header');
            $this->load->view('template/menu', $mensagem);
            $this->load->view('documentos/meus_documentos');
            $this->load->view('template/footer');
            $this->load->view('template/html_footer');*/

        }

    }

}
