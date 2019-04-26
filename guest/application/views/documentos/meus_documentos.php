<section class="tables">   
    <div class="container-fluid">
        <?php if (validation_errors()) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= validation_errors() ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error') == TRUE) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('success') == TRUE) : ?>
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('warning') == TRUE): ?>
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert">
                    <?=$this->session->flashdata('warning');?>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">

            <div class="col-lg-12">
    
                <div class="card">
        
                    <div class="card-header d-flex align-items-center">
                        <h3 class="h4">Meus documentos</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">                       
                            <table class="table table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">Protocolo</th>
                                        <th width="25%">Documento<br/>/Grupo</th>
                                        <th width="10%">Prazos</th>
                                        <th width="10%">Etapas</th>
                                        <th width="10%">Data de Criação</th>
                                        <th width="30%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php                                        
                                    if ($documentos_usuario) {
                                        $i = 1;
                                        foreach ($documentos_usuario as $documentos) {
                                            ?>
                                            <tr>
                                                <td scope="row"><strong><?=$i++;?></strong></td>
                                                <td><?=$documentos->protocolo;?></td>
                                                <td>
                                                    <?=$documentos->documento;?><br/>
                                                    <strong><?=$documentos->grupo;?></strong>
                                                </td>
                                                <td>
                                                    <?php
                                                    if(!empty($documentos->prazo)){
                                                        ?>
                                                        Documento: <?=converte_data($documentos->prazo);?> <br/>
                                                        <strong>
                                                            <?php
                                                            $this->load->model('etapas_model', 'etapasmodel');
                                                            $prazo = $this->etapasmodel->prazo_etapa($documentos->idprotocolo, $documentos->idetapa);
                                                            echo "Etapa: ".converte_data($prazo);
                                                            ?>
                                                        </strong>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <strong>Documento sem prazo!</strong>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td><?=$documentos->etapa;?></td>
                                                <td><?=converte_datetime($documentos->data_criacao);?></td>
                                                <td style="text-align: center;">
                                                    <?php 
                                                        $this->load->model('DocEtapas_model', 'docetapamodel');
                                                        $last_step = $this->docetapamodel->ultima_etapa($documentos->iddocumento);
                                                        if ($last_step == $documentos->idetapa) {
                                                            ?>
                                                            <a href="<?=base_url('finalizar_documento/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>" class="blockA_<?=$documentos->idprotocolo?>">Finalizar Documento</a><br/>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <a href="<?=base_url('proxima_etapa/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>" class="blockB_<?=$documentos->idprotocolo;?>">Encaminhar Próxima Etapa</a><br/>
                                                            <?php
                                                        }
                                                        
                                                        if ($documentos->ordem > 1) {
                                                            ?>
                                                            <a href="<?=base_url('etapa_aterior/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>" class="blockC_<?=$documentos->idprotocolo;?>">Retornar Etapa Aterior</a><br/>
                                                            <?php
                                                        }
                                                        if($documentos->ordem == 1){
                                                            ?>
                                                            <a href="<?=base_url('editar_documento/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>">Editar Documento</a><br/>
                                                            <?php
                                                        }
                                                    ?>
                                                    <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="javascript:historico(<?=$documentos->idprotocolo;?>)">Ver Histórico Documento</a><br/>
                                                    <!--<a href="<?//=base_url('suspender/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>" class="blockD_<?//=$documentos->idprotocolo;?>">Documento com exigência</a><br/>-->
                                                    <a href="javascript:void(0)" onclick="javascript:doc_exigencia('<?=md5($documentos->idprotocolo).$documentos->idprotocolo;?>')" class="blockD_<?=$documentos->idprotocolo;?>">Documento com exigência</a><br/>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" class="blockE_<?=$documentos->idprotocolo;?>" onclick="cancelarDoc(<?=$documentos->idprotocolo;?>)">Cancelar Documento</a><br/>
                                                    <?php 

                                                    if ($documentos->ordem > 1) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="erroDoc(<?=$documentos->idprotocolo;?>)" class="blockF_<?=$documentos->idprotocolo;?>">Apontar Erro</a><br/>
                                                        <?php
                                                    }
                                                    $this->load->model('erros_model', 'errosmodel');
                                                    
                                                    $contador = $this->errosmodel->conta_erros($documentos->idprotocolo);

                                                    if ($contador > 0) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="vizualizar_erro(<?=$documentos->idprotocolo;?>)" style="color:red;">Ver Erros</a><br/>
                                                        <?php
                                                    }
                                                    ?>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="apontarObservacao(<?=$documentos->idprotocolo;?>)"> Apontar Observação</a><br/>
                                                    <?php
                                                    $this->load->model('documentos_model', 'docmodel');

                                                    if ($this->docmodel->verifica_observacoes($documentos->idprotocolo) > 0) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="ver_obs(<?=$documentos->idprotocolo;?>)" style="color:green"> Ver Observações</a><br/>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="line"></div>
                                                    <!--<input class="id_protocolo" name="id_protocolo" id="id_protocolo" type="hidden" value="<?//=$documentos->idprotocolo;?>">-->
                                                    <div class="timer_<?=$documentos->idprotocolo;?>">0 segundos</div>
                                                    <button id="post_<?=$documentos->idprotocolo;?>" class="btn btn-sm btn-info" href="#">Iniciar</button>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php 
                foreach ($documentos_usuario as $documentos) {
                    echo '<input class="id_protocolo" name="id_protocolo" id="id_protocolo" type="hidden" value="'.$documentos->idprotocolo.'">';
                }
                ?>
            </div>
        </div>    
    </div>
</section>
<script src="http://code.jquery.com/jquery.js"></script>
<script>
window.addEventListener("DOMContentLoaded", function() {
	
	var format = function(seconds) {
		var tempos = {
			segundos: 60
		,   minutos: 60
		,   horas: 24
		,   dias: ''
		};
		var parts = [], string = '', resto, dias;
		for (var unid in tempos) {
			if (typeof tempos[unid] === 'number') {
				resto = seconds % tempos[unid];
				seconds = (seconds - resto) / tempos[unid];
			} else {
				resto = seconds;
			}
			parts.unshift(resto);
		}
		dias = parts.shift();
		if (dias) {
			string = dias + (dias > 1 ? ' dias ' : ' dia ');
		}
		for (var i = 0; i < 3; i++) {
			parts[i] = ('0' + parts[i]).substr(-2);
		}
		string += parts.join(':');
		return string;
	};
	

	$(function(){
			
		$.each($('input[id=id_protocolo]'),function (){

			var id_pro = $(this).val();
	
			var tempo = 0;
			var interval = 0;
			var timer = function(){ 
				$('.timer_'+id_pro).html(format(++tempo));
			};
            console.log(id_pro);
            console.log(format(++tempo));
		
			$.post('get_time', { pro: id_pro }, function(resp){
                //console.log(resp);
				$('#post_'+id_pro).text(resp.running ? 'Pausar' : 'Iniciar');
                if(!resp.running){
                    $('.blockA_'+id_pro).css("pointer-events", "none");
					$('.blockB_'+id_pro).css("pointer-events", "none");
                }
				tempo = resp.seconds;
				timer();
                //console.log('Retorna o tipo ' + resp.running);
				if (resp.running) {
					interval = setInterval(timer, 1000);
				}
				botao = $('#post_'+id_pro).text();
				if(botao === "Pausar"){
					$('.blockA_'+id_pro).css("pointer-events", "none");
					$('.blockB_'+id_pro).css("pointer-events", "none");
					$('.blockC_'+id_pro).css("pointer-events", "none");
					$('.blockD_'+id_pro).css("pointer-events", "none");
                    $('.blockE_'+id_pro).css("pointer-events", "none");
                    $('.blockF_'+id_pro).css("pointer-events", "none");
				}
			});
			
			$('#post_'+id_pro).on('click', function(){ 
				var btn = this;
				btn.disabled = true;
				$.post('grava_acao', { pro: id_pro }, function(resp){
                    //console.log(id_pro);
					btn.disabled = false;
					$(btn).text(resp.running ? 'Pausar' : 'Iniciar');
					if (resp.running) {
						timer();
						interval = setInterval(timer, 1000);
					} else {
						clearInterval(interval);
					}
                    //console.log(resp.running);
					botao = $('#post_'+id_pro).text();
					if(botao === "Pausar"){
						$('.blockA_'+id_pro).css("pointer-events", "none");
						$('.blockB_'+id_pro).css("pointer-events", "none");
						$('.blockC_'+id_pro).css("pointer-events", "none");
						$('.blockD_'+id_pro).css("pointer-events", "none");
                        $('.blockE_'+id_pro).css("pointer-events", "none");
                        $('.blockF_'+id_pro).css("pointer-events", "none");
					}else{
						$('.blockA_'+id_pro).css("pointer-events", "");
						$('.blockB_'+id_pro).css("pointer-events", "");
						$('.blockC_'+id_pro).css("pointer-events", "");
						$('.blockD_'+id_pro).css("pointer-events", "");
                        $('.blockE_'+id_pro).css("pointer-events", "");
                        $('.blockF_'+id_pro).css("pointer-events", "");
					}
				});
			});


		});
		
	});
	
});

</script>