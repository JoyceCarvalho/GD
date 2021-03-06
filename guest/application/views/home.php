<!-- Dashboard Counts Section-->
<section class="dashboard-counts no-padding-bottom">
  <div class="container-fluid">
    <div class="row bg-white has-shadow">
      <!-- Item -->
      <div class="col-xl-3 col-sm-6">
        <a class="dashbord" href="<?=base_url('meusdocumentos');?>">
          <div class="item d-flex align-items-center" style="font-size: 14px;">
            <div class="icon bg-violet"><i class="fa fa-archive"></i></div>
            <div class="title">
              <span>Meus Documentos</span>
            </div><br/>
            <div class="number"><strong><?= $meus_documentos; ?></strong></div>
          </div>
        </a>
      </div>
      <!-- Item -->
      <div class="col-xl-3 col-sm-6">
        <a class="dashbord" href="<?=base_url('cancelados');?>">
          <div class="item d-flex align-items-center" style="font-size: 14px;">
            <div class="icon bg-red"><i class="fa fa-ban"></i></div>
            <div class="title">
              <span>Documentos Cancelados</span>
            </div>
            <div class="number"><strong><?= $documentos_cancelados; ?></strong></div>
          </div>
        </a>
      </div>
      <!-- Item -->
      <div class="col-xl-3 col-sm-6">
        <a class="dashbord" href="<?=base_url('erro');?>">
          <div class="item d-flex align-items-center" style="font-size: 14px;">
            <div class="icon bg-green"><i class="icon-bill"></i></div>
            <div class="title">
              <span>Documentos com erro</span>
            </div>
            <div class="number"><strong><?= $com_erro; ?></strong></div>
          </div>
        </a>
      </div>
      <!-- Item -->
      <div class="col-xl-3 col-sm-6">
        <a class="dashbord" href="<?=base_url('andamento');?>">
          <div class="item d-flex align-items-center" style="font-size: 14px;"  >
            <div class="icon bg-orange"><i class="fa fa-battery-half"></i></div>
            <div class="title">
              <span>Documentos em Andamento</span>
            </div>
            <div class="number"><strong><?= $em_andamento; ?></strong></div>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>


<!-- Botão para inclusão de novo documento -->
<section class="dashboard-header">
  <div class="container-fluid">
    <div class="row">
      <!-- Statistics -->
      <div class="statistics col-lg-3 col-12">
        <a class="dashbord" href="<?=base_url('novo_documento');?>">
          <div class="statistic d-flex align-items-center bg-white has-shadow">
            <div class="icon bg-blue"><i class="fa fa-cloud-upload"></i></div>
            <div class="text">Novo Documento</div>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>