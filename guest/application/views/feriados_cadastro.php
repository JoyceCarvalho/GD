<section class="forms"> 
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
            <!-- Form Elements -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="h4">Feriados</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?=base_url('cad_feriado');?>" class="form-horizontal">
                            
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Titulo</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="titulo">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Tipo Feriado</label>
                                <div class="col-sm-9">
                                    <select name="feriado_tipo" id="feriado_tipo" class="form-control">
                                        <option value="nda"> -- Selecione -- </option>
                                        <option value="1">Feriados Fixos</option>
                                        <option value="2">Feriados Móveis</option>
                                    </select>
                                </div>
                            </div>

                            <div id="tipo"></div>

                            <!--<div class="form-group row">
                                <label class="col-sm-3 form-control-label">Dia</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" name="dia">
                                </div>
                            </div>-->
                        
                            <div class="line"></div>
                            <div class="form-group row">
                                <div class="col-sm-6 offset-sm-3">
                                    <a href="<?=base_url('home/feriado');?>" class="btn btn-sm btn-secondary"><i class="fa fa-backward"></i> Voltar</a>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Salvar Informações</button>
                                </div>
                            </div>
                                    
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function(){

        $("#feriado_tipo").change(function(){
            var feriadoTipo = parseInt($("#feriado_tipo").val());

            if (feriadoTipo == 1) {
                
                var input;
                input = "<div id='id_dia' class='form-group row'>";
                    input += "<label class='col-sm-3 form-control-label'>Dia</label>";
                    input += "<div class='col-sm-9'>";
                        input += "<input id='dia_mes' name='dia' type='text' class='form-control' placeholder='dd/mm'>";
                        input += "<div id='mensagem'></div>";
                    input += "</div>";
                input += "</div>";

                $("#tipo").html(input).show();

                $('#dia_mes').mask('00/00');

                $("#dia_mes").blur(function(){
                    
                    var dia = $("#dia_mes").val();

                    var expReg = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/(19|20)?\d{2}$/;
                    var aRet = true;
                    if ((dia) && (dia != '')) {
                        var dia_d = dia.substring(0,2);
                        var mes = dia.substring(3,5);
                        //console.log(dia_d)
                        //console.log(mes);
                        if (mes == 4 || mes == 6 || mes == 9 || mes == 11 && dia_d > 30) 
                        aRet = false;
                        else 
                        if (mes == 2 && dia_d > 28) 
                            aRet = false;
                        else
                            if (mes == 2 && dia_d > 29)
                            aRet = false;
                        else
                            if (mes > 12)
                            aRet = false;
                    }  else {
                        aRet = false;  
                    }

                    //console.log(aRet);
                    if(aRet == false){
                        var input = '<div style="color:#dc3545;">Data Inválida!</div>';
                        $("#mensagem").html(input);
                        $("#dia_mes").css('border-color', '#dc3545');
                    } else {
                        var input = '<div style="color:#28a745;">Data Válida!</div>';
                        $("#mensagem").html(input);
                        $("#dia_mes").css('border-color', '#28a745');
                    }
                    
                });

            } else if(feriadoTipo == 2) {

                var input;

                input = "<div id='id_dia' class='form-group row'>";
                    input += "<label class='col-sm-3 form-control-label'>Dia</label>";
                    input += "<div class='col-sm-9'>";
                        input += "<input name='dia' type='date' class='form-control'>";
                        input += "<div id='mensagem'></div>";
                    input += "</div>";
                input += "</div>";

                $("#tipo").html(input).show();

                $("#id_dia").blur(function(){

                    var data = $('#id_dia').val();

                    var expReg = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/(19|20)?\d{2}$/;
                    var aRet = true;
                    if ((pObj) && (pObj.value.match(expReg)) && (pObj.value != '')) {
                        var dia = pObj.value.substring(0,2);
                        var mes = pObj.value.substring(3,5);
                        var ano = pObj.value.substring(6,10);
                        if (mes == 4 || mes == 6 || mes == 9 || mes == 11 && dia > 30) 
                        aRet = false;
                        else 
                        if ((ano % 4) != 0 && mes == 2 && dia > 28) 
                            aRet = false;
                        else
                            if ((ano%4) == 0 && mes == 2 && dia > 29)
                            aRet = false;
                    }  else {
                        aRet = false;  
                        return aRet;
                    }

                    //console.log(aRet);
                    if(aRet == false){
                        var input = '<div style="color:#dc3545;">Data Inválida!</div>';
                        $("#mensagem").html(input);
                        $("#dia_mes").css('border-color', '#dc3545');
                    } else {
                        var input = '<div style="color:#28a745;">Data Válida!</div>';
                        $("#mensagem").html(input);
                        $("#dia_mes").css('border-color', '#28a745');
                    }
                
                });

            } else {

                var input = document.getElementById('id_dia');

                var main = document.getElementById('tipo');

                main.removeChild(input);

            }
        });

    });

</script>