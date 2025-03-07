
    <br>
    <!-- Content here -->
    <main role="main" class="container">
        <div class='row'>
            <div class='col-md-8' >
            <div class="card">
                <div class="card-header bg-secondary text-white">Edit Kategori</div>
                <div class="card-body">
                    <?php echo form_open_multipart('kategori/editKategori/'.$data_kategori['id']);?>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name='nama_kategori' value='<?php echo $data_kategori['nama_kategori'] ?>' placeholder="Nama Kategori Pengelompokan API">
                        <?php echo form_error('nama_kategori', '<small class="text-danger pl-3">','</small>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Deskripsi</label>
                        <textarea class='form-control' rows='8' name='desc' placeholder="Describe yourself here..."><?php echo $data_kategori['desc'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Gambar</label>
                        <input type="file" class="form-control" id="image" placeholder="">
                        <?php 
                        if($data_kategori['image']){
                            echo "<a href='".base_url('assets/img/thumbs/').$data_kategori['image']."' target='_blank' class='btn btn-sm btn-info'>Lihat Gambar</a>";
                        }
                        ?>
                        <small id="emailHelp" class="form-text text-muted">Jika ada</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href='<?php echo base_url('kategori'); ?>' class='btn btn-danger'>Back</a>
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