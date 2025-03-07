
  <br>
    <!-- Content here -->
    <main role="main" class="container">
      <div class='row'>
        <div class='col-md-4'>

          <!-- <a class="btn btn-md btn-primary" href="<?php echo base_url('api_list/addApi') ?>" role="button">Tambah API</a>
          <br>
          <br>
        -->
          <?php

          echo form_dropdown('kategori_id',$arr_kategori, $kategori_id, 'class="form-control" id="select-kategori"');
          ?>
          <br>
          <?php echo $this->session->flashdata('message'); ?>
          <ul class="list-group">
            <?php
              if($data_api_all){
                foreach ($data_api_all as $value) {
                  # code...
                  if($value['active']==1){
                    $badge_active = '<span class="badge badge-success">Active</span>';
                  }else{
                    $badge_active = '<span class="badge badge-danger">Deactive</span>';
                  }

                  if($value['scope']==1){
                    $badge_scope = '<span class="badge badge-primary">Private</span>';
                  }else{
                    $badge_scope = '<span class="badge badge-secondary">Public</span>';
                  }
                  echo '<li class="list-group-item"><a href="#'.$value['id'].'" data-id="'.$value['id'].'" class="select-item" >'.$value['name']."</a> : ".$badge_scope." ".$badge_active.'</li>';
                }
              }
            ?>
          </ul>
        </div>
        <div class='col-md-8'>
          <br>
          <ul class="list-group" id='detail-data'>
            
          </ul>
        </div>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script >
      $(document).ready(function() {
          console.log( "ready!" );
          $('.select-item').on('click', function name(params) {
            let id = $(this).attr('data-id');
            params.preventDefault();
            $.ajax({
                    url : "<?php echo base_url();?>api_list/ajaxGetDetailApi",
                    method : "POST",
                    dataType : 'json',
                    data : {id: id},
                    success: function(data){
                      if(data.status){
                        $('#detail-data').html('');
                        let badge_method = '';
                        if(data.data.method=='GET'){
                          badge_method = '<span class="badge badge-primary">GET</span>';
                        }else if(data.data.method=='POST'){
                          badge_method = '<span class="badge badge-success">POST</span>';
                        }else if(data.data.method=='PUT'){
                          badge_method = '<span class="badge badge-info">PUT</span>';
                        }else{
                          badge_method = '<span class="badge badge-danger">DELETE</span>';
                        }

                        let data_isi = `<li class="list-group-item active">`+data.data.name+`</li>
                                        <li class="list-group-item">Method : `+badge_method+`</li>
                                        <li class="list-group-item"><b>URL</b> : <p>`+data.data.url+`</p></li>
                                        <li class="list-group-item"><b>Deskripsi</b> : <p><small>`+data.data.description+`</small></p></li>
                                        <li class="list-group-item"><b>Parameter</b> : <p>`+data.data.parameter+`</p></li>
                                        <li class="list-group-item"><b>Example</b> : <p>`+data.data.example_param+`</p></li>
                                        <li class="list-group-item"><b>Result</b> : <pre>`+data.data.result+`</pre></li>
                                        
                                        `;
                        $('#detail-data').html(data_isi);               
                      }else{
                        alert('data tidak ditemukan');
                      }
                    }
            })
          });

          $('#select-kategori').on('change', function name(params) {
            let id = $('#select-kategori').val();
            params.preventDefault();
            //alert(id);
            if(id==''){
              location.href = "<?php echo base_url('api_list'); ?>";
            }else{
              location.href = "<?php echo base_url('api_list/index/'); ?>"+id;
            }
          });
      });
    </script>
  </body>
</html>