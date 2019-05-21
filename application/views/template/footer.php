            <!-- Scroll to Top Button-->
            <a id="scrollToTop" class="scroll-button rounded" href="">
                <i class="fa fa-angle-up"></i>
            </a>
            <!-- Page Footer-->
            <footer class="main-footer">
                <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                    <p><a href="http://www.sgtgestaoetecnologia.com.br" class="external">SGT - Gestão e Tecnologia</a> &copy; 2018</p>
                    <!--<p><a href="" class="external"> Manual Gestão de Prazos e Produtividade</a></p>-->
                    </div>
                    <div class="col-sm-6 text-right">
                        <p>
                            Design by <a href="https://bootstrapious.com/admin-templates" class="external">Bootstrapious</a> 
                            (modify by <a href="http://www.linkedin.com/in/joyce-carvalho" class="external">Joyce Carvalho</a>)
                        </p>
                        <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
                    </div>
                </div>
                </div>
            </footer>
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
                    <div class="img-fluid col-xs-12" style="text-align: center;">
                        <img src="<?=base_url("assets/img/loading2.gif")?>" alt="loading">
                    </div>
                </div>
                <div class="modal-body" id="conteudo_body"></div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal exigência -->
    <div class="modal fade" id="modal-exigencia" tabindex="-1" role="dialog" aria-labelledby="modal-area" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="div_id">
                <div class="modal-header">
                    <h4 id="exampleModalLabel" class="modal-title">Documento com exigência</h4>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>

                <form name="exigencia" method="post" action="<?=base_url("suspender")?>">
                    <div class="modal-body" id="modal-corpo">
                        <input id="exigencia" type="hidden" name="id"> 
                        Você realmente quer deixar o documento aguardando exigência?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="memoria-delete" class="btn btn-danger">Confirmar</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal exigência -->