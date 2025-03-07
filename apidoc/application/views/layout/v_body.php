
    <div class="jumbotron" id="home">
      <div class="container">
        <div class="text-center">
          <img src="<?php echo base_url();?>assets/img/Logo.png" class="rounded-circle img-thumbnail">
          <h1 class="display-4">API GATEWAY</h1>
          <h3 class="lead">Sumber Daya Manusia <br> Kementerian Pendidikan dan Kebudayaan</h3>
        </div>
      </div>
    </div>


    <!-- About -->
    <section class="about" id="about">
      <div class="container">
        <div class="row mb-4">
          <div class="col text-center">
            <h2>Tentang</h2>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-5">
            <p>Sekumpulan perintah, fungsi, serta protokol yang dapat digunakan oleh programmer saat membangun perangkat lunak untuk sistem operasi tertentu. API memungkinkan programmer untuk menggunakan fungsi standar untuk berinteraksi dengan sistem operasi.</p>
          </div>
          <div class="col-md-5">
            <p>Kumpulan API dari Sumber Data yang ada pada Direktorat Sumber Daya Manusia Kementerian dan Kebudayaan</p>
          </div>
        </div>
      </div>
    </section>
    <!-- Portfolio -->
    <section class="portfolio" id="portfolio">
      <div class="container">
        <div class="row pt-4 mb-4">
          <div class="col text-center">
            <h2>List Kategori API</h2>
          </div>
        </div>
        <div class="row">
        <?php 
        if($isi_kategori){
            $jml = count($isi_kategori);
            for($i=0; $i< $jml; $i++){
              echo 
              '<div class="col-md-4">
                  <div class="card">
                    <img class="card-img-top" src="'.base_url('assets/img/thumbs/1.png').'" alt="Card image cap">
                    <div class="card-body">
                      <h5 class="card-title">'.$isi_kategori[$i]['nama_kategori'].'</h5>
                      <p class="card-text">'.$isi_kategori[$i]['desc'].'</p>
                      <a href="'.base_url('api_list/index/').$isi_kategori[$i]['id'].'" class="btn btn-primary">Lihat List API</a>
                    </div>
                  </div>
                </div>';
            }
        }
        ?>
        </div>
          

      </div>
    </section>



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