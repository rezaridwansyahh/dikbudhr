<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>


<div class="modal fade" id="modal-diklat">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Riwayat Diklat</h4>
            </div>
            <div class="modal-body" id="riwayat_diklat_form_area">
                <div class="messages">
                </div>
                <form id="riwayat_diklat_form" method="post" accept-charset="utf-8">
                    
                    <div class="form-group" id="jenis_diklat">
                        <label>Jenis Diklat</label>
                        <select class="form-control" id='jenis_diklat_select' name='jenis_diklat_id' style="width:100%" required>
                            <option disabled selected value> -- select an option -- </option>
                        </select>
                    </div>

                    <div class="form-group" id="diklatStrukturalField">
                        <label>Jenis Diklat Struktural</label>
                        <select class="form-control" id='jenis_diklat_struktural' name='diklat_struktural_id' style="width:100%" required>
                            <?php
                            foreach (json_decode($diklat_struktural,true) as $key => $value) {
                                $id = $value['id'];
                                $diklat = $value['nama'];
                                echo "<option value='$id'>$diklat</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group" id="diklat_non_struktural">
                        <label>Nama Diklat</label>
                        <input type="text" required="required" class="form-control" id="nama_diklat" name="nama_diklat">
                    </div>

                    <div class="form-group">
                        <label>Institusi Penyelenggara</label>
                        <input type="text" class="form-control" name="institusi_penyelenggara">
                    </div>

                    <div class="form-group">
                        <label>Nomor Sertifikat</label>
                        <input type="text" class="form-control" name="nomor_sertifikat" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tanggal_mulai" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input type="date" class="form-control" name="tanggal_selesai" required>
                    </div>

                    <div class="form-group">
                        <label>Tahun Diklat</label>
                        <input type="number" class="form-control" name="tahun_diklat" min="1900" required>
                    </div>

                    <div class="form-group">
                        <label>Durasi Jam</label>
                        <input type="number" class="form-control" name="durasi_jam" min="1" required>
                    </div>

                    <div class="form-group">
                        <label>Rumpun Diklat</label>
                        <select class="form-control" id='rumpun_diklat_select' name='rumpun_diklat_id' style="width:100%" required>
                            <option disabled selected value> -- select an option -- </option>
                           
                        </select>
                    </div>

                    <div class="form-group9">
                        <label for="inputNAMA" class="control-label">Berkas</label>
                        <div class='controls'>
                            <div id="form_upload">
                                <input id="file_dokumen" name="file_dokumen" class="file" type="file"
                                accept="application/pdf">
                            </div>

                        </div>
                    </div>

                    <input type="hidden" name="nip_baru" id="nip_baru" value="<?=$pegawai->NIP_BARU?>">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="siasn_id" >
                    <input type="hidden" name="pns_orang_id" >
                    

                    <br>
                    <button type="submit" id="saveButton" class="btn btn-primary">Save changes</button>
                </form>
                
            </div>
            
        </div>
    </div>
</div>


<div class="modal fade" id="modal-file-viewer">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">File Preview</h4>
            </div>
            <div class="modal-body">
                <object id="file-viewer-object" data="" type="application/pdf" style="width: 100%; height: 500px;">
                    <p>It appears your browser doesn't support PDF viewing. <a id="download-link" href="" download>Download the file</a> instead.</p>
                </object>
            </div>
        </div>
    </div>
</div>


<!-- Add spinner modal -->
<div class="modal fade" id="loading-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-spinner fa-spin fa-3x"></i>
                <h5>Loading...</h5>
            </div>
        </div>
    </div>
</div>


<div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <button type="button" class="btn btn-primary" id="addNewButton" data-toggle="modal" data-target="#modal-diklat">
        <i class="fa fa-plus"></i> Tambah
    </button>
    
    <br><br>
    <table id="tableRiwayatDiklat" class="table" style="width:100%">
        <thead>
            <tr>
                
                <th>Tahun Diklat</th>
                <th>Jenis Diklat</th>
                <th>Nama Diklat</th>
                <th>Jumlah Jam</th>
                <th>Tanggal Mulai Diklat</th>
                <th>Tanggal Selesai Diklat</th>
                <th>No Sertifikat</th>
                <th>Status SIASN</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<script>
    $(document).ready(function(){
        /**
         * Variable initialization
         */
        let nip = "<?=$pegawai->NIP_BARU;?>";
        let jenis_diklat = <?=$jenis_diklat_siasn?>;        
        let rumpun_diklat = <?=$rumpun_diklat?>;
        
        
        /**
         * Transforming data to select2 format
         */
        renameProperty(jenis_diklat,'jenis_diklat','text');
        renameProperty(rumpun_diklat,'nama','text');

        $("#diklatStrukturalField").hide();
        $("#jenis_diklat_struktural").attr('disabled','disabled');
        

        /**
         * Select 2 initialization
         */
        $("#jenis_diklat_select").select2(
            {
                
                data:jenis_diklat
            }
        );
        $("#rumpun_diklat_select").select2(
            {
                
                data:rumpun_diklat
            }
        );

        /**
         * Master table configuration
         */
        var tableConfig = {
            order: [
                [1, 'asc']
            ],
            destroy: true,
            ajax: {
                url: "<?php echo base_url() ?>pegawai/riwayat_diklat/ajax_list",
                type: 'POST',
                data: {
                    NIP_BARU: nip
                }
            },
            scrollX: true,
            processing: true,
            columns: [{
                    data: "tahun_diklat"
                },
                {
                    data: "jenis_diklat",
                    defaultContent: "Diklat Lainnya"
                },
                {
                    data: "nama_diklat",
                    defaultContent: "Diklat Lainnya"
                },
                {
                    data: "durasi_jam"
                },
                {
                    data: "tanggal_mulai"
                },
                {
                    data: "tanggal_selesai"
                },
                {
                    data: "nomor_sertifikat"
                },
                {
                    data: "sudah_kirim_siasn"
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        
                        var viewButton = "<button data-toggle='tooltip' title='View File' class='btn btn-xs btn-info btn-view-file'><i class='fa fa-eye'></i></button>";
                        var additionalFunctionality = "<button data-toggle='tooltip' title='' tooltip='Kirim Ke SIASN' class='btn btn-xs btn-primary btn-send' data-original-title='Kirim ke SIASN'><i class='fa fa-share'></i></button>";
                        var deleteButton = "<button data-toggle='tooltip' title='Hapus Data' class='btn btn-xs btn-danger btn-hapus'><i class='fa fa-trash-o'></i></button>";
                        var editButton = "<button data-toggle='tooltip' title='Ubah data' class='btn btn-xs btn-success btn-edit' data-original-title='Ubah data'><i class='fa fa-pencil'></i></button>";
                        
                        if(data.sudah_kirim_siasn=='sudah'){
                            additionalFunctionality = "";
                        }
                        return "<div class='btn-group'>" + viewButton + " " + additionalFunctionality + " " + deleteButton + " " + editButton + "</div>";
                    }
                   
                }
            ],
            fnCreatedRow: function (nRow, aData, iDataIndex, cells) {
                $(nRow).on('click', '.btn-view-file', function () {
                    $('#loading-modal').modal('show');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url() ?>pegawai/riwayat_diklat/get_file",
                        data: {
                            id: aData.id
                        },
                        dataType: "json",
                        success: function (response) {
                            $('#loading-modal').modal('hide');
                            if (response.file_base64) {
                                const fileViewer = $('#file-viewer-object');
                                const downloadLink = $('#download-link');
                                const base64WithPrefix = 'data:application/pdf;base64,' + response.file_base64;
                                fileViewer.attr('data', base64WithPrefix);
                                downloadLink.attr('href', base64WithPrefix);
                                $('#modal-file-viewer').modal('show');
                            } else {
                                swal("Notice!", "No file available for this record", "warning");
                            }
                        },
                        error: function () {
                            $('#loading-modal').modal('hide');
                            swal("Error!", "Failed to fetch file", "error");
                        }
                    });
                });
                /**
                 * Action when delete clicked
                 */
                $(nRow).on('click', '.btn-hapus', function () {
                    swal({
                            title: "Anda Yakin?",
                            text: "Anda akan menghapus data ini?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonClass: 'btn-success',
                            confirmButtonText: 'Ya!',
                            cancelButtonText: "Tidak, Batalkan!",
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true,
                            closeOnCancel: false
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                hapusDataDiklat(aData);
                            }
                        })

                })

                /**
                 * Action when 
                 */
                $(nRow).on('click', '.btn-edit', function () {
                    Object.entries(aData).forEach(([key, value]) => {
                        $('#riwayat_diklat_form input[name="' + key + '"]').val(value);
                        $('#riwayat_diklat_form textarea[name="' + key + '"]').val(value);
                        if (key == "rumpun_diklat" || key == "jenis_diklat") {
                            setSelect2OptionByName(key + "_select", value);
                        }
                        if(aData.sudah_kirim_siasn=='sudah'){
                            $('#jenis_diklat').hide()
                        }else{
                            $('#jenis_diklat').show()
                        }
                        $("#modal-diklat").modal('show');
                    });
                })

                $(nRow).on('click', '.btn-send', function () {
                    

                    swal({
                            title: "Anda Yakin?",
                            text: "Anda akan mengirim data ini ke SIASN!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonClass: 'btn-success',
                            confirmButtonText: 'Ya!',
                            cancelButtonText: "Tidak, Batalkan!",
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true,
                            closeOnCancel: false
                        },
                        function (isConfirm) {
                            sendToSIASN(aData);
                        });
                })
            }
        };

    /**
     * Initialize master table
     */
    var masterTable = $('#tableRiwayatDiklat').DataTable(tableConfig);
    

	$("#riwayat_diklat_form").on('submit',function(e){
		if (this.checkValidity() === false) {
            e.preventDefault();
            e.stopPropagation();
            alert('Please fill out the form correctly.');
        }else{
            e.preventDefault();
            e.stopPropagation();
            swal({
                    title: "Anda Yakin?",
                    text: "Anda akan menyimpan data ini?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-success',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: "Tidak, Batalkan!",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                    closeOnCancel: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        submitdatadiklat();
                    }
                })
            
        }
        return false;
	});	

    /**
     * Clear button before registering new course
     */
    $("#addNewButton").click(function(){
        $("#riwayat_diklat_form").trigger('reset');
        $('#riwayat_diklat_form').find('input[type="hidden"]').val(''); 
        $("#nip_baru").val(nip)
        $('#jenis_diklat').show()
    })

    

    $("#jenis_diklat_select").change(function(){
        var jenisDiklat = $(this).val();
        
        if(jenisDiklat==1){
            $("#diklatStrukturalField").show();
            $("#jenis_diklat_struktural").removeAttr('disabled','disabled');
            $("#jenis_diklat_struktural").trigger('change').change();
            $("#diklat_non_struktural").hide();
        }else{
            $("#nama_diklat").val('')
            $("#diklatStrukturalField").hide();
            $("#jenis_diklat_struktural").attr('disabled','disabled');
            $("#diklat_non_struktural").show()
        }
    });

    $("#jenis_diklat_struktural").change(function(){
        $("#nama_diklat").val($("#jenis_diklat_struktural option:selected").text());
    });


    function sendToSIASN(jsonObject){
        var formData = new FormData();
        
        $.each(jsonObject, function(key, value) {
            formData.append(key, value);
        });
        var json_url = "<?php echo base_url() ?>pegawai/riwayat_diklat/send_siasn";
        //console.log(formData);

        $.ajax({    
		 	type: "POST",
			url: json_url,
			data: formData,
            dataType: "json",
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
			success: function(data){ 
                //console.log(data);
                if(data.success){
                    swal({
                        title: "Sukses!",
                        text: data.message,
                        type: "success",
                        timer: 4000,
                        showConfirmButton: true
                    }, function () {
                        masterTable.ajax.reload();
                    });

                }else{
                    swal({
                        title: "Gagal!",
                        text: data.message,
                        type: "success",
                        timer: 4000,
                        showConfirmButton: true
                    }, function () {
                        masterTable.ajax.reload();
                    });
                }
               
		    }
        });
    }

    function renameProperty(objList, oldProp, newProp) {
        objList.forEach(obj => {
            if (obj.hasOwnProperty(oldProp)) {
                obj[newProp] = obj[oldProp];
                //obj['id'] = obj[oldProp]; // Set id with text property
                delete obj[oldProp];
            }
        });
    }

    function hapusDataDiklat(data){
        var json_url = "<?php echo base_url() ?>pegawai/riwayat_diklat/delete";
        var formData = new FormData();

        $.each(data, function(key, value) {
            formData.append(key, value);
        });

        
        $.ajax({    
            type: "POST",
			url: json_url,
			data: formData,
            dataType: "json",
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
			success: function(data){ 
                swal("Pemberitahuan!", data.msg, "success");
                masterTable.ajax.reload();
		    }
        });
    }

	function submitdatadiklat(){
		var the_data = new FormData(document.getElementById("riwayat_diklat_form"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayat_diklat/save";
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: the_data,
            dataType: "json",
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
			success: function(data){ 
                console.log(data);
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    masterTable.ajax.reload();
                    $("#modal-diklat").modal('hide');
                }
                else {
                    swal("Pemberitahuan!", data.msg, "warning");
                    masterTable.ajax.reload();
                    $("#modal-diklat").modal('hide');                    

                }
			}});
		return false; 
	}


    function setSelect2OptionByName(selectId, optionName) {
        var $select = $('#' + selectId);
        var $options = $select.find('option');

        $options.each(function () {
            if ($(this).text() === optionName) {
                $(this).prop('selected', true);
                return false; // Break the loop once the option is found
            }
        });

        // Trigger the change event to update select2
        $select.trigger('change');
    }
    })
</script>