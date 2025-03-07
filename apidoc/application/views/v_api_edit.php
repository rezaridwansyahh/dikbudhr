
    <br>
    <!-- Content here -->
    <main role="main" class="container">
        <div class='row'>
            <div class='col-md-8' >
            <div class="card">
                <div class="card-header bg-secondary text-white">Tambah API</div>
                <div class="card-body">
                    <?php echo form_open_multipart('api_list/editApi/'.$data_api['id']);?>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Kategori</label>
                        <?php echo form_dropdown('kategori_id', $arr_kategori, $data_api['kategori_id'], 'class="form-control" '); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Tipe API</label>
                        <?php echo form_dropdown('scope', array('1' => 'Private', '2' => 'Public'), $data_api['scope'], 'class="form-control" '); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Method</label>
                        
                        <?php 
                        $arr_methode = array(
                            'GET' => 'GET',
                            'POST' => 'POST',
                            'PUT' => 'PUT',
                            'DELETE' => 'DELETE'
                        );
                        echo form_dropdown('method', $arr_methode, $data_api['method'], 'class="form-control" '); 
                        
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nama</label>
                        <input type="text" class="form-control" id="name" name='name' value='<?php echo $data_api['name'] ?>' placeholder="Judul / Nama API">
                        <?php echo form_error('name', '<small class="text-danger pl-3">','</small>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Url</label>
                        <input type="text" class="form-control" id="url" name='url' value='<?php echo $data_api['url'] ?>' placeholder="URL API">
                        <?php echo form_error('url', '<small class="text-danger pl-3">','</small>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Deskripsi</label>
                        <textarea class='form-control' rows='8' name='description' placeholder="Describe yourself here..."><?php echo $data_api['description'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Parameter</label>
                        <input type="text" class="form-control" id="parameter" name='parameter' placeholder="Nama parameter API" value='<?php echo $data_api['parameter'] ?>'>
                        <?php echo form_error('parameter', '<small class="text-danger pl-3">','</small>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Contoh</label>
                        <input type="text" class="form-control" id="example_param" name='example_param' placeholder="Contoh Parameter API" value='<?php echo $data_api['example_param'] ?>'>
                        <?php echo form_error('example_param', '<small class="text-danger pl-3">','</small>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Result</label>
                        <textarea class='form-control' rows='8' name='result' placeholder="Describe yourself here..."><?php echo $data_api['result'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Active</label>
                        <?php echo form_dropdown('active', array('1' => 'Ya', '0' => 'Tidak'), $data_api['active'], 'class="form-control" '); ?>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href='<?php echo base_url('api_list'); ?>' class='btn btn-danger'>Back</a>
                </div>
                </form>
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
  </body>
</html>