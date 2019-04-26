        
<!-- por alguma razão q eu desconheço esse script funciona apenas quando inserido aqui se colocar em um arquivo .js ele não funciona, 
mas fica por sua conta e risco '\_(>.<)_/` -->
<script>
    $(document).ready(function(){

        $(window).scroll(function(){
            if ($(this).scrollTop() > 100) {
                $('#scrollToTop').fadeIn();
            } else {
                $('#scrollToTop').fadeOut();
            }
        });

        $('#scrollToTop').click(function(){
            $('html, body').animate({scrollTop : 0},800);
            return false;
        }); 
    });

    function doc_exigencia(id){
        $("#modal-exigencia").modal("show");
        $("#exigencia").val(id);
    }
    
    function historico(id_pro){
        //var iddocumento = $('#id_protocolo').val();
        //console.log(id_pro);
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
            var body = '<div class="form-group">';
                body += '<p> Não há informações disponíveis no momento. Caso o problema persista entre em contato com o suporte. </p>';
            body += '</div>';
        }
        $('#exampleModalLabel').html(titulo).show();
        $("#conteudo").html(body).show();
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
            }             
            $("#conteudo_body").html(data);
        });
    }

    function cancelarDoc(id_pro){
        //var iddocumento = $('#id_protocolo').val();
        //console.log(id_pro);

        $.getJSON('<?=base_url();?>'+'historico_documento/'+id_pro, function (dados){
            if (dados.length>0) {
                var titulo = 'Cancelar documento';
                var body = '<div class="form-group">';
                $.each(dados, function(i, obj){
                    body += '<label><strong>Grupo:</strong> '+obj.nome_grupo+'</label><br/>';
                    body += '<label><strong>Documento:</strong> '+obj.nome_documento+'</label><br/>';
                    body += '<label><strong>Protocolo:</strong> '+obj.protocolo+'</label><br/>';
                });
                body += '</div>';
                body2 = '<hr/>';
                body2 += '<form action="<?=base_url('cancelar_documento');?>" method="post" id="cancelamento">';
                body2 += '<div class="form-group">';
                body2 += '<label>Motivo do cancelamento:</label>';
                body2 += '<textarea class="form-control" rows="6" name="motivo"></textarea>';
                body2 += '<input type="hidden" name="idprotocolo" value="'+id_pro+'">';
                body2 += '</div>';
                body2 += '<div class="form-group">';
                body2 += '<button type="submit" class="btn btn-sm btn-primary">Cadastrar Cancelamento</button>';
                body2 += '</div>';
                body2 += '</form>'
            } else {
            var body = '<div class="form-group">';
                    body += '<p> Não há informações disponíveis no momento. Caso o problema persista entre em contato com o suporte. </p>';
                body += '</div>';
            var body2 = "";
            }
            $('#exampleModalLabel').html(titulo).show();
            $('#conteudo').html(body).show();
            $('#conteudo_body').html(body2).show();
        });
    }

    function erroDoc(id_pro){
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
                var body = '<div class="form-group">';
                    body += '<p> Não há informações disponíveis no momento. Caso o problema persista entre em contato com o suporte. </p>';
                body += '</div>';
            }
            $("#exampleModalLabel").html(titulo).show();
            $("#conteudo").html(body).show();

        });
        $.getJSON('<?=base_url();?>'+'etapa_json/'+id_pro, function(dados){
            var etapa = '<form action="<?=base_url("erro_documento_cad");?>" method="post" id="erro_form">';
            if (dados.length > 0) {
                etapa += '<div class="form-group">';
                etapa += '<label>Etapa</label>';
                etapa += '<select name="etapa_erro" class="form-control">';
                $.each(dados, function(i, obj){
                    etapa += '<option value="'+obj.id+'">'+obj.titulo+'</option>';
                });
                etapa += '</select>';
                etapa += '</div>';
            } 
            $("#conteudo_body").html(etapa).show();
        });
        $.getJSON('<?=base_url();?>'+'erro_documento', function (dados){              
            if (dados.length>0) {                  
                var body2 = '<div class="form-group">';
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
                body2 += '</form>';
            } else {
                body2 = "<br/><strong>Não foram configurados os tipos de erro!<br/>";
                body2 += "Favor vá até o menu configurações cadastre-os e tente novamente!</strong>"
            }
            var main = document.getElementById('erro_form');
            main.insertAdjacentHTML('beforeend', body2);
        });

    }

    function vizualizar_erro(id_pro){

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
                var body = '<div class="form-group">';
                        body += '<p> Não há informações disponíveis no momento. Caso o problema persista entre em contato com o suporte. </p>';
                    body += '</div>';
            }
            $("#exampleModalLabel").html(titulo).show();
            $("#conteudo").html(body).show();

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
                body2 = "";
            }
            $("#conteudo_body").html(body2).show();
        
        });
    }

    function apontarObservacao(id_pro){

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
                var body = '<div class="form-group">';
                        body += '<p> Não há informações disponíveis no momento. Caso o problema persista entre em contato com o suporte. </p>';
                    body += '</div>';
            }
            $("#exampleModalLabel").html(titulo).show();
            $("#conteudo").html(body).show();
        });
        var body2 = '<form action="<?=base_url('observacao_cad');?>" method="post" id="observacao">';
        body2 += '<div class="form-group">';
        body2 += '<label>Observação:</label>';
        body2 += '<textarea class="form-control" rows="6" name="observacao"></textarea>';
        body2 += '<input type="hidden" name="idprotocolo" value="'+id_pro+'">';
        body2 += '</div>';
        body2 += '<div class="form-group">';
        body2 += '<button type="submit" class="btn btn-sm btn-primary">Cadastrar Observação</button>';
        body2 += '</div>';
        body2 += '</form>';

        $("#conteudo_body").html(body2).show();
    };

    function ver_obs(id_pro){

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
                var body = '<div class="form-group">';
                        body += '<p> Não há informações disponíveis no momento. Caso o problema persista entre em contato com o suporte. </p>';
                    body += '</div>';
            }
            $("#exampleModalLabel").html(titulo).show();
            $("#conteudo").html(body).show();
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
                body = "";
            }

            $("#conteudo_body").html(body).show();
        });
    }

    function transfere(id_pro){

        $.getJSON('<?=base_url();?>'+'historico_documento/'+id_pro, function (dados){
            if (dados.length>0) {
                var titulo = 'Transferência Manual';
                var body = '<div class="form-group">';
                $.each(dados, function(i, obj){
                    body += '<label><strong>Grupo:</strong> '+obj.nome_grupo+'</label><br/>';
                    body += '<label><strong>Documento:</strong> '+obj.nome_documento+'</label><br/>';
                    body += '<label><strong>Protocolo:</strong> '+obj.protocolo+'</label><br/>';
                })
                body += '</div>';
                body += '<hr/>';
            } else {
                var body = "";
            }
            $('#exampleModalLabel').html(titulo).show();
            $('#conteudo').html(body).show();
            
        });

        $.getJSON('<?=base_url();?>'+'transferencia', function(dados){
            if (dados.length>0 ) {
                //console.log(dados);
                var body2 = '<form method="post" action="<?=base_url("transfere_para");?>">';
                body2 += '<input name="idprotocolo" value="'+id_pro+'" type="hidden" >'
                body2 += '<div class="form-group">';
                body2 += '<label> Transferir para: </label>';
                body2 += '<select class="form-control" name="usuario">';
                $.each(dados, function(i, obj){
                    body2 += '<option value="'+obj.id+'">'+obj.nome+'</option>';
                });
                body2 += '</select>';
                body2 += '</div>';
                body2 += '<button type="submit" class="btn btn-sm btn-primary"> Transferir documento</button>';
                body2 += '</form>';
            } else {
                var body2 = '<div class="form-group">';
                        body2 += '<p> Não há informações disponíveis no momento. Caso o problema persista entre em contato com o suporte. </p>';
                    body2 += '</div>';
            }
            $("#conteudo_body").html(body2).show();
        });
    }
    </script>

    <!-- Javascript files-->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="<?=base_url('assets/vendor/popper.js/umd/popper.min.js')?>"> </script>
    <script src="<?=base_url('assets/vendor/bootstrap/js/bootstrap.min.js');?>"></script>
    <script src="<?=base_url('assets/vendor/jquery.cookie/jquery.cookie.js');?>"> </script>
    <script src="<?=base_url('assets/vendor/datatables/jquery.dataTables.js');?>"></script>
    <script src="<?=base_url('assets/vendor/datatables/dataTables.bootstrap4.js');?>"></script>
    <script src="<?=base_url('assets/vendor/jquery-validation/jquery.validate.min.js');?>"></script>
    <script src="<?=base_url('assets/js/jquery.mask.min.js');?>"></script>
    <script src="<?=base_url('assets/js/custom.js');?>"></script>
    <!-- Main File-->
    <script src="<?=base_url('assets/js/front.js');?>"></script>

    <!--<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>-->
    <script>
    $(document).ready(function(){
        $('#datatable').DataTable({
            "language": {
            "lengthMenu": "Mostrando _MENU_ registros por página",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum registro disponível",
            "infoFiltered": "(filtrado do total de _MAX_ registros)"
            }
        });
    });
    </script>
  </body>
</html>