    <div class="page login-page">
      <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">

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

          <div class="row">
            <!-- Logo & Information Panel-->
            <div class="col-lg-6">
              <div class="d-flex align-items-center">
                <div class="content">
                  <div class="logo">
                    <img class="img-fluid" src="<?=base_url('assets/img/logo_sgt.png');?>" alt="Logo SGT">
                  </div>
                </div>
              </div>
            </div>
            <!-- Form Panel    -->
            <div class="col-lg-6 bg-white">
              <div class="form d-flex align-items-center">
                <div class="content">
                  <div class="logo">
                    <h1 style="color: dodgerblue;">Gestão de Documentos</h1>
                    <hr>
                  </div>
                  <form id="login-form" method="post" action="<?=base_url('login_auth');?>">
                    <div class="form-group">
                      <input id="login-username1" type="text" name="cliente_code" required="" class="input-material">
                      <label for="Código Empresa" class="label-material">Código da Empresa</label>
                    </div>
                    <div class="form-group">
                      <input id="login-username" type="text" name="usuario" required="" class="input-material">
                      <label for="Usuário" class="label-material">Usuário</label>
                    </div>
                    <div class="form-group">
                      <input id="login-password" type="password" name="senha" required="" class="input-material">
                      <label for="Senha" class="label-material">Senha</label>
                    </div><button id="login" type="submit" class="btn btn-primary">Login</button>
                    <!-- This should be submit button but I replaced it with <a> for demo purposes-->
                  </form><a href="#" class="forgot-pass">Esqueceu a senha?</a><!--<br><small>Não tem uma conta? </small><a href="#" class="signup">Cadastre-se</a>-->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="copyrights text-center">
        <p>Design by <a href="https://bootstrapious.com/admin-templates" class="external">Bootstrapious</a> (modify by <a class="external" href="http://www.linkedin.com/in/joyce-carvalho">Joyce Carvalho</a>)
          <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
        </p>
      </div>
    </div>