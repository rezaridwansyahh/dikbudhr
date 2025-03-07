
  <br>
    <!-- Content here -->
    <main role="main" class="container">
      <!--
      <div class='row'>
        <div class='col-md-4'>
          <a class="btn btn-md btn-primary" href="<?php echo base_url('kategori/addKategori') ?>" role="button">Tambah Kategori</a>
        </div>
      </div> 
      <br>
    -->
      <div class='row'>
      <div class='col-md-12'>
        <?php echo $this->session->flashdata('message'); ?>
        <ul class="list-group">
          <?php
            if($isi){
              foreach ($isi as $value) {
                # code...
                echo '<li class="list-group-item">
                        <h5>'.$value['nama_kategori'].'</h5>
                        <p>
                        '.$value['desc'].'
                        </p>
                        <a href="'.base_url('api_list/index/').$value['id'].'" class="btn btn-sm btn-info" >Lihat</a>&nbsp;
                        '; 
                       //echo  '<a href="'.base_url('kategori/editKategori/').$value['id'].'" class="btn btn-sm btn-success" >Edit</a>&nbsp;';
                        //echo '<a href="'.base_url('kategori/deleteKategori/').$value['id'].'" class="btn btn-sm btn-danger delete-kategori" >Delete</a>';
                      echo '</li>';
              }
            }
          ?>
        </ul>
      </div>
    
    </div>
  </main>
  
  <!-- footer -->
    <footer class="bg-dark text-white mt-5">
      <div class="container">
        <div class="row">
          <div class="col text-center">
            <p>Copyright &copy; 2019.</p>
          </div>
        </div>
      </div>
    </footer>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>