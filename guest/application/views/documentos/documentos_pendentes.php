<section class="tables">   
    <div class="container-fluid">
        <?php if (validation_errors()) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= validation_errors() ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata("error")) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata("error"); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata("success")) : ?>
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata("success"); ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">

            <div class="col-lg-12">
    
                <div class="card">
        
                    <div class="card-header d-flex align-items-center">
                        <h3 class="h4">Documentos Pendentes</h3>
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
                                        <th>Data Pendente</th>
                                        <th>Etapa</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if($doc_pendentes){
                                        foreach ($doc_pendentes as $documentos) {
                                            ?>
                                            <tr>
                                                <td><?=$documentos->protocolo;?></td>
                                                <td>
                                                    <?=$documentos->documento;?><br/>
                                                    <strong><?=$documentos->grupo;?></strong>
                                                </td>
                                                <td>
                                                    <?php
                                                    if(!empty($documentos->prazo)){
                                                        echo "".converte_data($documentos->prazo) . "<br/>";
                                                    } else {
                                                        echo "<strong>Documento sem prazo!</strong>";
                                                    }
                                                    ?>
                                                </td>
                                                <td><?=$documentos->data_criacao;?></td>
                                                <td><?=$documentos->data_pendente;?></td>
                                                <td><?=$documentos->etapa_nome;?></td>
                                                <td style="text-align: center;">
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="javascript:historico(<?=$documentos->idprotocolo;?>)">Ver Histórico Documento</a><br/>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="transfere(<?=$documentos->idprotocolo;?>)">Transferir Documento</a><br/>
                                                    <?php 
                                                    $this->load->model('erros_model', 'errosmodel');
                                                    
                                                    $contador = $this->errosmodel->conta_erros($documentos->idprotocolo);

                                                    if ($contador > 0) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="vizualizar_erro(<?=$documentos->idprotocolo;?>)" style="color:red;">Ver Erros</a><br/>
                                                        <?php
                                                    }

                                                    if($documentos->idresponsavel == $_SESSION["idusuario"]){
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="apontarObservacao(<?=$documentos->idprotocolo;?>)"> Apontar Observação</a><br/>
                                                        <?php
                                                    }
                                                    $this->load->model('documentos_model', 'docmodel');

                                                    if ($this->docmodel->verifica_observacoes($documentos->idprotocolo) > 0) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="ver_obs(<?=$documentos->idprotocolo;?>)" style="color:green"> Ver Observações</a><br/>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="timer_<?=$documentos->idprotocolo;?>">0 segundos</div>
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
                foreach ($doc_pendentes as $documentos) {
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

            //console.log(id_pro);
            $.post('get_time_pendente', { pro: id_pro }, function(resp){
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

</script>