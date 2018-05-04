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
                                        <th>Protocolo</th>
                                        <th>Documento<br/>/Grupo</th>
                                        <th>Prazo Documento</th>
                                        <th>Etapas</th>
                                        <th>Data de Criação</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if ($documentos_cargo) {
                                            foreach ($documentos_cargo as $documentos) {
                                                ?>
                                                <tr>
                                                    <td><?=$documentos->protocolo;?></td>
                                                    <td>
                                                        <?=$documentos->documento;?><br/>
                                                        <strong><?=$documentos->grupo;?></strong>
                                                    </td>
                                                    <td><?=converte_data($documentos->prazo);?></td>
                                                    <td><?=$documentos->etapa;?></td>
                                                    <td><?=$documentos->data_criacao;?></td>
                                                    <td></td>
                                                </tr>
                                                <?php
                                            }
                                        } elseif ($documentos_usuario) {
                                            foreach ($documentos_usuario as $documentos) {
                                                ?>
                                                <tr>
                                                    <td><?=$documentos->protocolo;?></td>
                                                    <td>
                                                        <?=$documentos->documento;?><br/>
                                                        <strong><?=$documento->grupo;?></strong>
                                                    </td>
                                                    <td><?=converte_data($documentos->prazo);?></td>
                                                    <td><?=$documentos->etapa;?></td>
                                                    <td><?=$documentos->data_criacao;?></td>
                                                    <td></td>
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
</section>