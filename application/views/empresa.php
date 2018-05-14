<!-- Breadcrumb-->
<div class="breadcrumb-holder container-fluid">
  <ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?=base_url();?>">Página Inicial</a></li>
    <li class="breadcrumb-item active">Dados da Empresa </li>
  </ul>
</div>
<!-- Forms Section-->
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
            <h3 class="h4">Empresa</h3>
          </div>
          <div class="card-body">
            <!--<form method="post" action="<?//=base_url('alt_empresa');?>" class="form-horizontal">-->
            <?php
            echo form_open_multipart('alt_empresa');
              foreach ($empresa as $dados) {
                ?>
                <div class="form-group row">
                  <label class="col-sm-3 form-control-label">Nome Empresa</label>
                  <div class="col-sm-9">
                    <input type="text" name="empresa" class="form-control" value="<?=$dados->nome;?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="fileInput" class="col-sm-3 form-control-label">Logo Empresa</label>
                  <div class="col-sm-6">
                    <input name="logo_cliente" id="logo_cliente" type="file" accept=".jpg" class="form-control-file">
                    <small class="help-block-none">Apenas imagens jpg.</small>
                  </div>
                  <?php if (!empty($dados->logo_code)): ?>
                    <div class="col-sm-3">
                      <div class="avatar">
                        <img src="<?=base_url();?>/assets/img/logo_empresas/<?=$dados->logo_code;?>" alt="empresa" class="img-fluid rounded-circle">
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="line"></div>

                <div class="form-group row">
                  <label class="col-sm-3 form-control-label">Missão</label>
                  <div class="col-sm-9">
                    <textarea name="missao" id="editor1" cols="30" rows="10"><?=$dados->missao;?></textarea>
                  </div>
                </div>
                <!--<div class="line"></div> -->
                <div class="form-group row">
                  <label class="col-sm-3 form-control-label">Visão</label>
                  <div class="col-sm-9">
                    <textarea name="visao" id="editor2" cols="30" rows="10"><?=$dados->visao;?></textarea>
                  </div>
                </div>
                <!-- <div class="line"></div> -->
                <div class="form-group row">
                  <label class="col-sm-3 form-control-label">Valores</label>
                  <div class="col-sm-9">
                    <textarea name="valores" id="editor3" cols="30" rows="10"><?=$dados->valores;?></textarea>
                  </div>
                </div>
                <div class="line"></div>
                <div class="form-group row">
                  <div class="col-sm-6 offset-sm-3">
                    <a href="<?=base_url('home');?>" class="btn btn-sm btn-secondary"><i class="fa fa-backward"></i> Voltar</a>
                    <?php
                    if (($_SESSION['is_admin'] == true) or ($_SESSION['is_coordenador'] == true)) {
                      ?>
                      <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Salvar Alteração</button>
                      <?php
                    }
                    ?>
                  </div>
                </div>
                <?php
              }
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
