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
    </script>

    <!-- Javascript files-->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="<?=base_url('assets/vendor/popper.js/umd/popper.min.js')?>"> </script>
    <script src="<?=base_url('assets/vendor/bootstrap/js/bootstrap.min.js');?>"></script>
    <script src="<?=base_url('assets/vendor/jquery.cookie/jquery.cookie.js');?>"> </script>
    <script src="<?=base_url('assets/vendor/chart.js/Chart.min.js');?>"></script>
    <script src="<?=base_url('assets/vendor/datatables/jquery.dataTables.js');?>"></script>
    <script src="<?=base_url('assets/vendor/datatables/dataTables.bootstrap4.js');?>"></script>
    <script src="<?=base_url('assets/vendor/jquery-validation/jquery.validate.min.js');?>"></script>
    <script src="<?=base_url('assets/js/charts-home.js');?>"></script>
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
                  "zeroRecords": "Nada encontrado",
                  "info": "Mostrando página _PAGE_ de _PAGES_",
                  "infoEmpty": "Nenhum registro disponível",
                  "infoFiltered": "(filtrado de _MAX_ registros no total)"
              }
          });
    });
    </script>
  </body>
</html>