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
                        <h3 class="h4">Documentos em Andamento</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">                       
                            <table class="table table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th width="10%">Protocolo</th>
                                        <th width="25%">Documento<br/>/Grupo</th>
                                        <th width="10%">Prazos</th>
                                        <th width="10%">Etapas</th>
                                        <th width="15%">Responsável</th>
                                        <th width="30%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if($andamento_doc_c){
                                        foreach ($andamento_doc_c as $documentos) {
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
                                                        echo "Documento: ".converte_data($documentos->prazo);
                                                        ?>
                                                        <br/>
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
                                                <td>
                                                    <?php
                                                    if(!empty($documentos->nome_usuario)){
                                                        echo $documentos->nome_usuario;
                                                    } else {
                                                        if($documentos->descricao == "PENDENTE"){
                                                            echo "<strong>Documento Pendente</strong> - Sem responsável";
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" id="historico_<?=$documentos->idprotocolo;?>">Ver Histórico Documento</a><br/>
                                                    <a href="<?=base_url('suspender/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>">Documento com exigência</a><br/>
                                                    <?php 
                                                    if ($documentos->idresponsavel == $_SESSION["idusuario"]) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" id="erro_<?=$documentos->idprotocolo;?>">Apontar Erro</a><br/>
                                                        <?php
                                                    }
                                                    $this->load->model('erros_model', 'errosmodel');
                                                    
                                                    $contador = $this->errosmodel->conta_erros($documentos->idprotocolo);

                                                    if ($contador > 0) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" id="vizualizar_erro_<?=$documentos->idprotocolo;?>" style="color:red;">Ver Erros</a><br/>
                                                        <?php
                                                    }
                                                    
                                                    $this->load->model('documentos_model', 'docmodel');
                                                    if ($documentos->idresponsavel == $_SESSION["idusuario"]) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" id="observacao_<?=$documentos->idprotocolo;?>"> Apontar Observação</a><br/>
                                                        <?php
                                                    }

                                                    if ($this->docmodel->verifica_observacoes($documentos->idprotocolo)) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" id="ver_obs_<?=$documentos->idprotocolo;?>" style="color:green"> Ver Observações</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    <input class="id_protocolo" name="id_protocolo" id="id_protocolo" type="hidden" value="<?=$documentos->idprotocolo;?>">
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }

                                    /*if(isset($andamento_doc_f)){
                                        foreach ($andamento_doc_f as $documentos) {
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
                                                        echo "Documento: ".converte_data($documentos->prazo);
                                                        ?>
                                                        <br/>
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
                                                <td>
                                                    <?php
                                                    if(!empty($documentos->nome_usuario)){
                                                        echo $documentos->nome_usuario;
                                                    } else {
                                                        if($documentos->descricao == "PENDENTE"){
                                                            echo "<strong>Documento Pendente</strong> - Sem responsável";
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td style="text-align: center;">
                                                    <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" id="historico_<?=$documentos->idprotocolo;?>">Ver Histórico Documento</a><br/>
                                                    <a href="<?=base_url('suspender/'.md5($documentos->idprotocolo).$documentos->idprotocolo);?>">Documento com exigência</a><br/>
                                                    <?php 
                                                    if ($documentos->idresponsavel == $_SESSION["idusuario"]) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" id="erro_<?=$documentos->idprotocolo;?>">Apontar Erro</a><br/>
                                                        <?php
                                                    }
                                                    $this->load->model('erros_model', 'errosmodel');
                                                    
                                                    $contador = $this->errosmodel->conta_erros($documentos->idprotocolo);

                                                    if ($contador > 0) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" id="vizualizar_erro_<?=$documentos->idprotocolo;?>" style="color:red;">Ver Erros</a><br/>
                                                        <?php
                                                    }
                                                    
                                                    $this->load->model('documentos_model', 'docmodel');
                                                    if ($documentos->idresponsavel == $_SESSION["idusuario"]) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" id="observacao_<?=$documentos->idprotocolo;?>"> Apontar Observação</a><br/>
                                                        <?php
                                                    }

                                                    if ($this->docmodel->verifica_observacoes($documentos->idprotocolo)) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" id="ver_obs_<?=$documentos->idprotocolo;?>" style="color:green"> Ver Observações</a>
                                                        <?php
                                                    }
                                                    ?>
                                                    <input class="id_protocolo" name="id_protocolo" id="id_protocolo" type="hidden" value="<?=$documentos->idprotocolo;?>">
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }*/
                                    ?>
                                </tbody>
                            </table>
                            <?php
                            if($andamento_doc_c){
                                foreach ($andamento_doc_c as $documentos) {
                                    ?>
                                    <input class="id_protocolo" name="id_protocolo" id="id_protocolo" type="hidden" value="<?=$documentos->idprotocolo;?>">
                                    <?
                                }
                            }
                            ?>
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
                
                    
                <div class="modal-body" id="his_conteudo"></div>

                <div class="modal-body" id="historico_documento"></div>
                
                <form action="<?=base_url('cancelar_documento');?>" method="post" id="cancelamento">
                    
                    <div class="modal-body" id="conteudo">                                                
                        <div class="form-group">
                            <p> Não há informações disponíveis no momento. Caso o problema persista entre em contato com o suporte. </p>
                        </div>
                    </div>

                </form>

                <form action="<?=base_url('observacao_cad');?>" method="post" id="observacao">
                    <div class="modal-body" id="obs"></div>
                </form>


                <form action="<?=base_url("erro_documento_cad");?>" method="post" id="erro">
                    
                    <div class="modal-body" id="doc_conteudo"></div>

                    <div class="modal-body" id="etapa"></div>

                    <div class="modal-body" id="erro_form"></div>
                </form>
            
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

	$(function(){
			
		$.each($('input[id=id_protocolo]'),function (){

			var id_pro = $(this).val();

            //console.log(id_pro);

            $('#historico_'+id_pro).click(function(e){

                var iddocumento = parseInt($('#id_protocolo').val());
                //console.log(id_pro);
                //console.log('valor_documento '+iddocumento);

                $.getJSON('<?=base_url();?>'+'historico_documento/'+id_pro, function (dados){
                    if (dados.length>0) {
                        var titulo = 'Histórico do Documento';
                        var body = '<div class="form-group">';
                        $.each(dados, function(i, obj){
                            body += '<label><strong>Grupo:</strong> '+obj.nome_grupo+'</label><br/>';
                            body += '<label><strong>Documento:</strong> '+obj.nome_documento+'</label><br/>';
                            body += '<label><strong>Protocolo:</strong> '+obj.protocolo+'</label><br/>';
                        })
                        body += '</div>';
                    } else {
                        reset();
                    }
                    $('#conteudo').hide();
                    $("#erro").hide();
                    $("#doc_conteudo").hide();
                    $('#etapa').hide();
                    $('#erro_form').hide();
                    $('#observacao').hide();

                    $('#exampleModalLabel').html(titulo).show();
                    $('#his_conteudo').html(body).show();
                });

                $.getJSON('<?=base_url();?>'+'historico/'+id_pro, function (dados){
                    if (dados.length>0) {
                        var data = '';
                        $.each(dados, function(i,obj){
                            data += '<div class="form-group">';
                            data += '<label>'+obj.descricao+'</label>';
                            if (obj.etapa != null) {
                                data += ' - <strong>'+obj.etapa+'</strong><br/>';    
                            }
                            
                            if(obj.nome == null){
                                nome = "Documento Pendente - Sem Responsável";
                            } else {
                                nome = obj.nome;
                            }
                            data += '<p>'+nome+'</p>';
                            data += '<p>'+obj.data+' - '+obj.hora+'</p>';
                            if (obj.descricao == 'CANCELADO') {
                                data += '<br/></br/> <label>Motivo Cancelamento</label><br/>';
                                data += '<p>'+obj.motivo+'</p>';
                            }
                            if(obj.erro != null){
                                data += "<label><strong>Erro:</strong></label><br/>";
                                data += '<label>'+obj.tipo_erro+'</label>';
                                data += ' - <strong>'+obj.natureza_erro+'</strong><br/>';
                                data += '<p>'+obj.erro+'</p>';
                            }
                            if(obj.observacao != null){
                                data += '<label><strong>Observação:</strong></label>';
                                data += '<p>'+obj.observacao+'</p>';
                            }
                            data += '</div>';
                            data += '<hr/>'
                        });
                        data += '<a href="<?=base_url();?>imprimir_historico/'+id_pro+'" target="_blank" class="btn btn-sm btn-warning" style="color: white"><i class="fa fa-print"></i> Imprimir</a>';
                    } else {
                        reset();
                    }
                    $("#historico_documento").html(data).show();
                })
            });

            $("#erro_"+id_pro).click(function(e){

                $.getJSON('<?=base_url();?>'+'historico_documento/'+id_pro, function (dados){
                    if (dados.length>0) {
                        var titulo = 'Apontar erros documento';
                        var body = '<div class="form-group">';
                        $.each(dados, function(i, obj){
                            body += '<label><strong>Grupo:</strong> '+obj.nome_grupo+'</label><br/>';
                            body += '<label><strong>Documento:</strong> '+obj.nome_documento+'</label><br/>';
                            body += '<label><strong>Protocolo:</strong> '+obj.protocolo+'</label><br/>';
                        })
                        body += '</div>';
                        body += '<hr/>';
                    } else {
                        reset();
                    }
                    $('#historico_documento').hide();
                    $('#cancelamento').hide();
                    $("#observacao").hide();

                    $("#exampleModalLabel").html(titulo).show();
                    $("#his_conteudo").html(body).show();

                });
                $.getJSON('<?=base_url();?>'+'etapa_json/'+id_pro, function(dados){
                    if (dados.length > 0) {
                        var etapa = '<div class="form-group">';
                        etapa += '<label>Etapa</label>';
                        etapa += '<select name="etapa_erro" class="form-control">';
                        $.each(dados, function(i, obj){
                            etapa += '<option value="'+obj.id+'">'+obj.titulo+'</option>';
                        });
                        etapa += '</select>';
                        etapa += '</div>';
                    } else {
                        reset();
                    }
                    $("#etapa").html(etapa).show();
                });
                $.getJSON('<?=base_url();?>'+'erro_documento', function (dados){
                    
                    if (dados.length>0) {
                        
                        var body2 = '<div class="form-group">';
                        body2 += '<label>Erro:</label>';
                        body2 += '<select name="erro" class="form-control">';
                        $.each(dados, function(i, obj){
                            body2 += '<option value="'+obj.id+'">'+obj.titulo+'</option>';
                        });
                        body2 += '</select>'
                        body2 += '</div>';
                        body2 += '<hr/>';
                        body2 += '<div class="form-group">';
                        body2 += '<label>Descrição do erro:</label>';
                        body2 += '<textarea class="form-control" name="descricao"></textarea>';
                        body2 += '<input type="hidden" name="idprotocolo" value="'+id_pro+'">';
                        body2 += '</div>';
                        body2 += '<div class="form-group">';
                        body2 += '<button type="submit" class="btn btn-sm btn-primary">Apontar erro</button>';
                        body2 += '</div>';

                    } else {
                        reset();
                    }

                    $('#erro_form').html(body2).show();
                });

            });

            $("#vizualizar_erro_"+id_pro).click(function(e){

                $.getJSON('<?=base_url();?>'+'historico_documento/'+id_pro, function (dados){
                    if (dados.length>0) {
                        var titulo = 'Erros do documento';
                        var body = '<div class="form-group">';
                        $.each(dados, function(i, obj){
                            body += '<label><strong>Grupo:</strong> '+obj.nome_grupo+'</label><br/>';
                            body += '<label><strong>Documento:</strong> '+obj.nome_documento+'</label><br/>';
                            body += '<label><strong>Protocolo:</strong> '+obj.protocolo+'</label><br/>';
                        })
                        body += '</div>';
                        body += '<hr/>';
                    } else {
                        reset();
                    }
                    $('#historico_documento').hide();
                    $('#cancelamento').hide();
                    $('#observacao').hide();
                    $('#etapa').hide();

                    $("#exampleModalLabel").html(titulo).show();
                    $("#his_conteudo").html(body).show();

                });
                $.getJSON('<?=base_url();?>'+'vizualizar_erros/'+id_pro, function (dados){
                    if(dados.length > 0){
                        var body2 = '';
                        $.each(dados, function(i, obj){
                            body2 += '<div class="form-group">';
                            body2 += '<p><strong>Etapa: </strong>'+obj.titulo_etapa+'</p>';
                            body2 += '<p><strong>Quem: </strong>'+obj.usuario_nome+'</p>';
                            body2 += '<p><strong>Quando: </strong>'+obj.quando+'</p>';
                            body2 += '<p><strong>Erro: </strong>'+obj.titulo_erro+'</p>';
                            body2 += '<p><strong>Tipo de erro: </strong>'+obj.tipo_erro+'</p>';
                            body2 += '<p><strong>Descrição: </strong>'+obj.descricao+'</p>';
                            body2 += '</div>';
                            body2 += '<hr/>';
                        });
                    } else {
                        reset();
                    }
                    $("#erro_form").html(body2).show();
                    
                })
            });

            $("#observacao_"+id_pro).click(function(e){

                $.getJSON('<?=base_url();?>'+'historico_documento/'+id_pro, function(dados){
                    if(dados.length > 0){
                        var titulo = 'Observações do documento';
                        var body = '<div class="form-group">';
                        $.each(dados, function(i, obj){
                            body += '<label><strong>Grupo:</strong> '+obj.nome_grupo+'</label><br/>';
                            body += '<label><strong>Documento:</strong> '+obj.nome_documento+'</label><br/>';
                            body += '<label><strong>Protocolo:</strong> '+obj.protocolo+'</label><br/>';
                        })
                        body += '</div>';
                        body += '<hr/>';
                    } else {
                        reset();
                    }

                    $('#historico_documento').hide();
                    $('#erro').hide();
                    $('#cancelamento').hide();
                    $("#doc_conteudo").hide();
                    $('#etapa').hide();
                    $('#erro_form').hide();

                    $("#exampleModalLabel").html(titulo).show();
                    $("#his_conteudo").html(body).show();
                });

                body2 = '<div class="form-group">';
                body2 += '<label>Observação:</label>';
                body2 += '<textarea class="form-control" rows="6" name="observacao"></textarea>';
                body2 += '<input type="hidden" name="idprotocolo" value="'+id_pro+'">';
                body2 += '</div>';
                body2 += '<div class="form-group">';
                body2 += '<button type="submit" class="btn btn-sm btn-primary">Cadastrar Observação</button>';
                body2 += '</div>';
                
                $("#observacao").show();
                $("#obs").html(body2).show();
            });

            $("#ver_obs_"+id_pro).click(function(e){

                $.getJSON('<?=base_url();?>'+'historico_documento/'+id_pro, function(dados){
                    if (dados.length>0) {
                        var titulo = 'Observações documento';
                        var body = '<div class="form-group">';
                        $.each(dados, function(i, obj){
                            body += '<label><strong>Grupo:</strong> '+obj.nome_grupo+'</label><br/>';
                            body += '<label><strong>Documento:</strong> '+obj.nome_documento+'</label><br/>';
                            body += '<label><strong>Protocolo:</strong> '+obj.protocolo+'</label><br/>';
                        });
                        body += '</div>';
                        body += '<hr/>';
                    } else {
                        reset();
                    }

                    $('#historico_documento').hide();
                    $('#erro').hide();
                    $('#cancelamento').hide();
                    $("#doc_conteudo").hide();
                    $('#etapa').hide();
                    $('#erro_form').hide();

                    $("#exampleModalLabel").html(titulo).show();
                    $("#his_conteudo").html(body).show();
                });

                $.getJSON('<?=base_url();?>'+'ver_observacao/'+id_pro, function (dados){
                    //console.log(id_pro);
                    if (dados.length>0) {
                        //console.log(dados);
                        var body = '<div class="form-group">';
                        $.each(dados, function(i, obj){
                            body += '<label>'+obj.etapa+' - <strong>'+obj.nome_usuario+'</strong></label><br/>';
                            body += '<label><b>Observação:</b></label>';
                            body += '<p>'+obj.observacao+'</p>'
                            body += '<hr/>';
                        })
                    } else {
                        reset();
                    }

                    $("#observacao").show();
                    $("#obs").html(body).show();
                });

            });

		});
		
	});
	
});
</script>
