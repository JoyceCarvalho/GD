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
                        <h3 class="h4">Documentos aguardando exigência</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">                       
                            <table class="table table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Protocolo</th>
                                        <th>Documento<br/>/Grupo</th>
                                        <th>Prazo Documento</th>
                                        <th>Data de Criação</th>
                                        <th>Data exigência</th>
                                        <th>Responsável pela exigência</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($doc_suspensos as $documentos) {
                                        ?>
                                        <tr>
                                            <td><?=$documentos->protocolo;?></td>
                                            <td>
                                                <?=$documentos->documento;?><br/>
                                                <strong><?=$documentos->grupo;?></strong>
                                            </td>
                                            <td>
                                                <?php 
                                                if (!empty($documentos->prazo)) {
                                                    echo "".converte_data($documentos->prazo) . "<br/>";
                                                } else {
                                                    echo "<strong>Documento sem prazo!</strong>";
                                                }
                                                ?>
                                            </td>
                                            <td><?=$documentos->data_criacao;?></td>
                                            <td><?=$documentos->data_suspensao;?></td>
                                            <td><?=$documentos->nome_usuario;?></td>
                                            <td style="text-align: center;">
                                                <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="javascript:historico(<?=$documentos->idprotocolo;?>)">Ver Histórico Documento</a><br/>
                                                <!--<a href="<?//=base_url('reverte_suspensao/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>">Exigência concluída</a><br/>-->
                                                <a href="javascript:void(0)" onclick="javascript:exigencia_ok('<?=md5($documentos->idprotocolo).$documentos->idprotocolo;?>')">Exigência concluída</a><br/>
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="erroDoc(<?=$documentos->idprotocolo;?>)" class="blockF_<?=$documentos->idprotocolo;?>">Apontar Erro</a><br/>
                                                <?php 
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
                                                <!--<input class="id_protocolo" name="id_protocolo" id="id_protocolo" type="hidden" value="<?//=$documentos->idprotocolo;?>">-->
                                                <div class="timer_<?=$documentos->idprotocolo;?>">0 segundos</div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php 
                foreach ($doc_suspensos as $documentos) {
                    echo '<input class="id_protocolo" name="id_protocolo" id="id_protocolo" type="hidden" value="'.$documentos->idprotocolo.'">';
                }
                ?>
            </div>
        </div>    
    </div>
    <!-- Modal exigência -->
    <div class="modal fade" id="modal-concluir_exigencia" tabindex="-1" role="dialog" aria-labelledby="modal-area" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="div_id">
                <div class="modal-header">
                    <h4 id="exampleModalLabel" class="modal-title">Documento com exigência</h4>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>

                <form id="formID" name="exigencia" method="post" action="<?=base_url("reverte_suspensao")?>">
                    <div class="modal-body" id="modal-corpo">
                        <input id="exigencia-concluida" type="hidden" name="id"> 
                        <p>Exigência concluida?</p>
                        <strong>
                            Reiniciar Serviço: irá retornar o serviço para a primeira etapa, reiniciando o trabalho no documento.<br/>
                            Continuar Serviço: irá retornar o serviço para a última etapa executada antes do período em exigência.
                        </strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="send1" name="exigencia_reiniciar" class="btn btn-info">Reiniciar Serviço</button>
                        <button type="submit" id="send2" name="exigencia_continuar" class="btn btn-success">Continuar Serviço</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal exigência -->
</section>
<script src="https://code.jquery.com/jquery.js"></script>
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

            //console.log(id_pro);
            
			$.post('get_time_suspenso', { pro: id_pro }, function(resp){
                //console.log(resp);
				tempo = resp.seconds;
				timer();
				if (resp.running) {
					interval = setInterval(timer, 1000);
				}

			});

		});
		
	});
	
});

function exigencia_ok(id){
    $("#modal-concluir_exigencia").modal("show");
    $("#exigencia-concluida").val(id);
}

var formID = document.getElementById("formID");
var send1 = $("#send1");
var send2 = $("#send2");

$(formID).submit(function(event){
    if (formID.checkValidity()) {
        send1.attr('style', 'visibility: hidden');
        send2.attr('style', 'visibility: hidden');
    }
});
</script>