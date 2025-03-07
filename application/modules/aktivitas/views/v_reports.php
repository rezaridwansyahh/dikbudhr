<div id="container-sk-slks" class="admin-box box box-danger expanded-box">
    <div class="box-header">
        <h3 class="box-title">Pencarian Lanjut</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool btn-default btn-advanced-search" data-widget="collapse">
                <i class="fa fa-plus"></i> Tampilkan
            </button>

        </div>
    </div>

    <div class="box-body">
        <?php echo form_open($this->uri->uri_string(), "id=form_search_pegawai", "form"); ?>
        <style>
            table.filter_pegawai tr td {
                padding-top: 2px;
            }
        </style>
        <table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
            <?php if ($this->auth->has_permission("Aktivitas.Reports.FullAccess")) { ?>
                <tr>
                    <td width="150px"><label for="example-text-input" class="col-form-label">Pegawai</label></td>
                    <td colspan=2>
                        <select id="user_id" name="user_id" width="100%" class="form-control select2 class_pegawai">
                            <?php
                            if ($pegawai) {
                                echo "<option selected value='" . $pegawai->ID . "'>" . $pegawai->NAMA . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Modul</label></td>
                <td colspan=2>
                    <select id="module" name="module" width="100%" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php foreach ($modules as $module) { ?>
                            <option value="<?= $module->module ?>"><?= $module->module ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Kata Kunci</label></td>
                <td colspan=2>
                    <input class="form-control" type="text" name="key" value="">
                </td>
            <tr>
                <td><label for="example-text-input" class="col-form-label">Tanggal Awal</label></td>
                <td><input class="form-control datepicker" type="text" name="tgl_awal" value="" readonly></td>
                <td class="text-center"><label for="example-text-input" class="col-form-label">Tanggal Akhir</label></td>
                <td><input class="form-control datepicker" type="text" name="tgl_akhir" value="" readonly></td>
            </tr>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Exclude Login</label></td>
                <td colspan=2>
                    <input type="checkbox" name="exclude_login" value="1" checked>
                </td>
            <tr>
            <tr>
                <td colspan=4>
                    <button type="submit" class="btn btn-warning pull-right "><i class="fa fa-search"></i> Cari</button>
                    <?php if ($this->auth->has_permission("Aktivitas.Reports.Download")) { ?>
                        <button type="button" class="btn btn-success pull-right download" targer="_BLANK"><i class="fa fa-download"></i> Download</button>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <?php
        echo form_close();
        ?>
    </div>
</div>

<div class="admin-box box box-danger">
    <div class="box-header">
        REKAP AKTIVITAS
    </div>
    <div class="box-body">
        <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover" width="100%">
            <thead>
                <tr>
                    <th style="width:10px">No</th>
                    <th>Nama<br>NIP</th>
                    <th>Aktivitas</th>
                    <th>Module</th>
                    <th style="width:50px">Tanggal</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $table = $(".table-data").DataTable({
            scrollX:        true,
            scrollCollapse: true,
            scroller:       true,

            dom: "<'row'<'col-sm-6'><'col-sm-6'>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
            processing: true,
            serverSide: true,
            "columnDefs": [
                {
                    "className": "text-center",
                    "targets": [0, 4]
                },
                {
                    "targets": [0, 4],
                    "orderable": false
                },
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap width-200'>" + data + "</div>";
                    },
                    targets: 3
                }
            ],
            fixedColumns: true,
            ajax: {
                url: "reports/ajax_list",
                type: 'POST',
                "data": function(d) {
                    d.advanced_search_filters = $("#form_search_pegawai").serializeArray();
                }
            }
        });
        $("#form_search_pegawai").submit(function() {
            $table.ajax.reload(null, true);
            return false;
        });
    });
    $(".class_pegawai").select2({
        placeholder: 'Cari Pegawai.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajaxnip"); ?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    });
    //Date picker
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        maxDate: 0
    });
    $('select2').select2();

    $(".download").click(function() {
        var xyz = $("#form_search_pegawai").serialize();
        window.open("<?= base_url() ?>/aktivitas/reports/download?" + xyz);
    });
</script>