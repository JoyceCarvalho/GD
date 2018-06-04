
<!-- Breadcrumb-->
<div class="breadcrumb-holder container-fluid">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=base_url("home");?>">Página Inicial</a></li>
        <li class="breadcrumb-item active"> Listar Etapas</li>
    </ul>
</div>

<section class="tables">   

    <div class="container-fluid">
        <?php if (validation_errors()) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= validation_errors() ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (isset($error)) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (isset($success)) : ?>
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    <?= $success ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">

            <div class="col-lg-12">
    
                <div class="card">
        
                    <div class="card-header d-flex align-items-center">
                        <h3 class="h4">Etapas de documentos</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">                       
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="10%">Protocolo</th>
                                        <th width="25%">Documento<br/>/Grupo</th>
                                        <th width="10%">Prazos</th>
                                        <th width="10%">Etapas</th>
                                        <th width="15%">Data de Criação</th>
                                        <th width="30%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (!empty($documentos_cargo)) {
                                            foreach ($documentos_cargo as $documentos) {
                                                ?>
                                                <tr>
                                                    <td><?=$documentos->protocolo;?></td>
                                                    <td>
                                                        <?=$documentos->documento;?><br/>
                                                        <strong><?=$documentos->grupo;?></strong>
                                                    </td>
                                                    <td>
                                                        <?="Documento: ".converte_data($documentos->prazo);?><br/>
                                                        <strong>
                                                            <?php
                                                            $this->load->model('etapas_model', 'etapasmodel');
                                                            $prazo = $this->etapasmodel->prazo_etapa($documentos->idprotocolo, $documentos->idetapa);
                                                            echo "Etapa: ".converte_data($prazo);
                                                            ?>
                                                        </strong>
                                                    </td>
                                                    <td><?=$documentos->etapa;?></td>
                                                    <td><?=converte_datetime($documentos->data_criacao);?></td>
                                                    <td style="text-align: center;">
                                                        <?php 
                                                            $this->load->model('DocEtapas_model', 'docetapamodel');
                                                            $last_step = $this->docetapamodel->ultima_etapa($documentos->iddocumento);
                                                            if ($last_step == $documentos->idetapa) {
                                                                ?>
                                                                <a href="<?=base_url('finalizar_documento/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>" class="blockA">Finalizar Documento</a>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <a href="<?=base_url('proxima_etapa/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>" class="blockB">Encaminhar Próxima Etapa</a>
                                                                <?php
                                                            }
                                                            
                                                            if ($documentos->ordem > 1) {
                                                                ?>
                                                                <a href="<?=base_url('etapa_aterior/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>" class="blockC">Retornar Etapa Aterior</a>
                                                                <?php
                                                            }
                                                            if($documentos->ordem == 1){
                                                                ?>
                                                                <a href="<?=base_url('editar_documento/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>">Editar Documento</a>
                                                                <?php
                                                            }
                                                        ?>
                                                        <a href="javascript:void(0)" id="historico">Ver Histórico Documento</a><br/>
                                                        <a href="#" class="blockD">Suspender Documento</a><br/>
                                                        <a href="#">Cancelar Documento</a><br/>
                                                        <a href="#">Apontar Erro</a><br/>
                                                        <div class="line"></div>
                                                        <input class="id_protocolo" name="id_protocolo" id="id_protocolo" type="hidden" value="<?=$documentos->idprotocolo;?>">
                                                        <div class="timer_<?=$documentos->idprotocolo;?>">0 segundos</div>
                                                        <button id="post_<?=$documentos->idprotocolo;?>" class="btn btn-sm btn-info" href="#">Iniciar</button>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } 
                                        
                                        if ($documentos_usuario) {
                                            foreach ($documentos_usuario as $documentos) {
                                                ?>
                                                <tr>
                                                    <td><?=$documentos->protocolo;?></td>
                                                    <td>
                                                        <?=$documentos->documento;?><br/>
                                                        <strong><?=$documentos->grupo;?></strong>
                                                    </td>
                                                    <td>
                                                        <?="Documento: ".converte_data($documentos->prazo);?><br/>
                                                        <strong>
                                                            <?php
                                                            $this->load->model('etapas_model', 'etapasmodel');
                                                            $prazo = $this->etapasmodel->prazo_etapa($documentos->idprotocolo, $documentos->idetapa);
                                                            echo "Etapa: ".converte_data($prazo);
                                                            ?>
                                                        </strong>
                                                    </td>
                                                    <td><?=$documentos->etapa;?></td>
                                                    <td><?=converte_datetime($documentos->data_criacao);?></td>
                                                    <td style="text-align: center;">
                                                        <?php 
                                                            $this->load->model('DocEtapas_model', 'docetapamodel');
                                                            $last_step = $this->docetapamodel->ultima_etapa($documentos->iddocumento);
                                                            if ($last_step == $documentos->idetapa) {
                                                                ?>
                                                                <a href="<?=base_url('finalizar_documento/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>" class="blockA">Finalizar Documento</a>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <a href="<?=base_url('proxima_etapa/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>" class="blockB">Encaminhar Próxima Etapa</a>
                                                                <?php
                                                            }
                                                            
                                                            if ($documentos->ordem > 1) {
                                                                ?>
                                                                <a href="<?=base_url('etapa_aterior/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>" class="blockC">Retornar Etapa Aterior</a>
                                                                <?php
                                                            }
                                                            if($documentos->ordem == 1){
                                                                ?>
                                                                <a href="<?=base_url('editar_documento/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>">Editar Documento</a>
                                                                <?php
                                                            }
                                                        ?>
                                                        <a href="#">Ver Histórico Documento</a><br/>
                                                        <a href="#" class="blockD">Suspender Documento</a><br/>
                                                        <a href="#">Cancelar Documento</a><br/>
                                                        <a href="#">Apontar Erro</a><br/>
                                                        <div class="line"></div>
                                                        <input class="id_protocolo" name="id_protocolo" id="id_protocolo" type="hidden" value="<?=$documentos->idprotocolo;?>">
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
            </div>
        </div>    
    </div>
    <!-- Modal-->
    <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="exampleModalLabel" class="modal-title"></h4>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                    
                <div class="modal-body" id="conteudo">                                                
                    <div class="form-group">
                        <p> Tem certeza que deseja excluir esta informação? </p>
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Fechar</button>
                </div>
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

			//alert(id_pro);

			//$(window).load(function(){ alert("here");

			//var protocol = ($(this).val()); { pro: protocol }
		
			$.post('get_time', { pro: id_pro }, function(resp){
				$('#post_'+id_pro).text(resp.running ? 'Pausar' : 'Iniciar');
				tempo = resp.seconds;
				timer();
				if (resp.running) {
					interval = setInterval(timer, 1000);
				}
				botao = $('#post_'+id_pro).text();
				if(botao === "Pausar"){
					$('.blockA').css("pointer-events", "none");
					$('.blockB').css("pointer-events", "none");
					$('.blockC').css("pointer-events", "none");
					$('.blockD').css("pointer-events", "none");
				}
			});
			
			$('#post_'+id_pro).on('click', function(){ 
				var btn = this;
				btn.disabled = true;
				$.post('grava_acao', { pro: id_pro }, function(resp){
					btn.disabled = false;
					$(btn).text(resp.running ? 'Pausar' : 'Iniciar');
					if (resp.running) {
						timer();
						interval = setInterval(timer, 1000);
					} else {
						clearInterval(interval);
					}
					botao = $('#post_'+id_pro).text();
					if(botao === "Pausar"){
						$('.blockA').css("pointer-events", "none");
						$('.blockB').css("pointer-events", "none");
						$('.blockC').css("pointer-events", "none");
						$('.blockD').css("pointer-events", "none");
					}else{
						$('.blockA').css("pointer-events", "");
						$('.blockB').css("pointer-events", "");
						$('.blockC').css("pointer-events", "");
						$('.blockD').css("pointer-events", "");
					}
				});
			});

            $('#historico').click(function(e){

                var j = 0;

                var iddocumento = $('#id_protocolo').val();

                $.getJSON('<?=base_url();?>'+'historico_documento/'+iddocumento, function (dados){
                    if (dados.length>0) {
                        var option = '<div class="col-sm-12" id="steps">';
                        $.each(dados, function(i, obj){
                            j++;
                            option += '<div class="form-group row">';
                            option += '<input type="hidden" name="etapas['+j+']" value="'+obj.id+'">';
                            option += '<label class="col-sm-3 form-control-label">'+obj.titulo+'</label>';
                            option += '<div class="col-sm-9"> <input type="date" name="prazo['+j+']" class="form-control"></div></div>';
                        })
                        option += '<div class="line"></div>';
                        option += '<hr>';
                        option += '<div class="form-group row">';
                        option += '<label class="form-control-label col-sm-3"> <strong>Prazo Final do documento</strong></label>';
                        option += '<div class="col-sm-9">';
                        option += '<input type="date" name="prazo_final" class="form-control">';
                        option += '</div>';
                        option += '</div>';
                        option += '<input type="hidden" name="prazo_etapa" value="'+dados.length+'">';
                        option += "</div>";
                        $('#mensagem').html('<span class="mensagem">Total de estados encontrados.: '+dados.length+'</span>'); 
                        console.log("Total de etapas "+dados.length+"!");
                    } else {
                        reset();
                        $('#mensagem').html('<span class="mensagem">Não foram encontrados documentos cadastrados neste grupo!</span>');
                    }
                    $('#addprazo').html(option).show();
                })
            });

		});
		
	});
	
});

</script>