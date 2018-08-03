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
                        <h3 class="h4">Documentos Finalizados</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?=base_url('finalizados');?>" method="post">
                            <div class="row col-sm-12">
                                <div class="form-group col-sm-3">
                                    <label>Grupo</label>
                                    <select class="form-control" name="filtrar_grupo" id="filtro_grupo">
                                        <option value="nda"> -- Todos -- </option>
                                        <?php 
                                        foreach($grupo_documentos as $doc){
                                            if($grupo_filtrado == $doc->idgrupo){
                                                $sel = "selected=\"selected\"";
                                            } else {
                                                $sel = "";
                                            }
                                            ?>
                                            <option <?=$sel;?> value="<?=$doc->idgrupo;?>"><?=$doc->titulo_grupo;?></option>
                                            <?php
                                        } 
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label>Documento</label>
                                    <select class="form-control" name="filtrar_documento" id="filtro_documento">
                                        <option value="nda"> -- Todos -- </option>
                                        <?php 
                                        foreach($documentos_finalizados as $doc){
                                            if($documento_filtrado == $doc->iddocumento){
                                                $sel = "selected=\"selected\"";
                                            } else {
                                                $sel = "";
                                            }
                                            ?>
                                            <option <?=$sel;?> value="<?=$doc->iddocumento;?>"><?=$doc->titulo_documento;?></option>
                                            <?php
                                        } 
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label>&nbsp;</label><br/>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-filter"></i> Filtrar</button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">         
                            <div class="row col-sm-12">
                                <div class="col-sm-3">
                                    <?php 
                                    if (!empty($grupo_filtrado)) {
                                        ?>
                                        <a target="_blank" href="<?=base_url('imprimir_por_grupo/'.$grupo_filtrado)?>" class="btn btn-sm btn-info"><i class="fa fa-print"></i>Imprimir por grupo de documentos</a>
                                        <br><br>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php
                                    if(!empty($documento_filtrado)){
                                        ?>
                                        <a target="_blank" href="<?=base_url('imprimir_por_documento/'.$documento_filtrado);?>" class="btn btn-sm btn-success"><i class="fa fa-print"></i>Imprimir protocolos por documento</a>
                                        <br><br>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <table class="table table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th width="10%">Protocolo</th>
                                        <th width="25%">Documento<br/>/Grupo</th>
                                        <th width="5%">Data de Criação</th>
                                        <th width="5%">Prazo Finalização</th>
                                        <th width="5%">Finalização</th>
                                        <th width="15%">Responsável</th>
                                        <th width="30%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($doc_finalizados as $documentos) {
                                        ?>
                                        <tr>
                                            <td><?=$documentos->protocolo;?></td>
                                            <td>
                                                <?=$documentos->documento;?><br/>
                                                <strong><?=$documentos->grupo;?></strong>
                                            </td>
                                            <td><?=$documentos->data_criacao;?></td>
                                            <td>
                                                <?php
                                                if(!empty($documentos->prazo_documento)){
                                                    echo $documentos->prazo_documento;
                                                } else {
                                                    echo "<strong>Documento sem prazos!</strong>";
                                                }
                                                ?>
                                            </td>
                                            <td><?=$documentos->data_finalizacao;?></td>
                                            <td><?=$documentos->nome_usuario;?></td>
                                            <td style="text-align: center;">
                                                <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" id="historico_<?=$documentos->idprotocolo;?>">Ver Histórico Documento</a><br/>
                                                <?php 
                                                    $this->load->model('erros_model', 'errosmodel');
                                                    
                                                    $contador = $this->errosmodel->conta_erros($documentos->idprotocolo);

                                                    if ($contador > 0) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" id="vizualizar_erro_<?=$documentos->idprotocolo;?>" style="color:red;">Ver Erros</a><br/>
                                                        <?php
                                                    }
                                                ?>
                                                <a class="btn btn-sm btn-warning external" style="color: white;" href="<?=base_url('imprimir_finalizados/'.$documentos->idprotocolo);?>"><i class="fa fa-print"></i> Imprimir</a>
                                                <div class="line"></div>
                                                <input class="id_protocolo" name="id_protocolo" id="id_protocolo" type="hidden" value="<?=$documentos->idprotocolo;?>">
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
                        <p> Não há informações disponíveis no momento. Caso o problema persista entre em contato com o suporte. </p>
                    </div>
                </div>

                <div class="modal-body" id="historico_documento">
                    
                </div>


                <div id="erro">
                    
                    <div class="modal-body" id="doc_conteudo">                                                
                        
                    </div>

                    <div class="modal-body" id="etapa">
                    
                    </div>

                    <div class="modal-body" id="erro_form">
                        
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
	
	$(function(){
			
		$.each($('input[id=id_protocolo]'),function (){

			var id_pro = $(this).val();

            $('#historico_'+id_pro).click(function(e){

                //var iddocumento = $('#id_protocolo').val();
                console.log(id_pro);

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
                    $('#exampleModalLabel').html(titulo).show();
                    $('#conteudo').html(body).show();
                    $("#erro").hide();
                    $("#doc_conteudo").hide();
                    $('#etapa').hide();
                    $('#erro_form').hide();
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
                            
                            if(obj.nome == ''){
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
                    $("#exampleModalLabel").html(titulo).show();
                    $("#doc_conteudo").html(body).show();
                    $('#historico_documento').hide();
                    $('#conteudo').hide();

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

		});
		
	});
	
});
</script>