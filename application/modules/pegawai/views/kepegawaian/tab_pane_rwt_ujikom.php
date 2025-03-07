<style>
    .modal-backdrop {
        opacity: 0.7;
    }
</style>


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
                <div id="pdf-container" style="width: 100%; height: 500px;">
                    <iframe id="pdf-iframe" style="width: 100%; height: 100%; border: none;" src=""></iframe>
                </div>
                <div id="pdf-fallback" style="display: none;">
                    <p>Unable to display PDF directly. <a id="download-link" href="" target="_blank">Open in new tab</a> or download instead.</p>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <table id="tableRiwayatUjikom" class="table">
        <thead>
            <tr>
                
                <th>Jenis</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<script>
    $(document).ready(function () {
        /**
         * Variable initialization
         */
        let nip = "<?=$pegawai->NIP_BARU;?>";

        /**
         * Master table configuration
         */
        var tableConfig = {
            order: [
                [1, 'asc']
            ],
            destroy: true,
            ajax: {
                url: "<?php echo base_url() ?>pegawai/riwayat_ujikom/ajax_list",
                type: 'POST',
                data: {
                    NIP_BARU: nip
                }
            },
            scrollX: true,
            processing: true,
            columns: [{
                    data: "jenis_ujikom",
                    defaultContent: "ujikom Lainnya"
                },
                {
                    data: "tahun"
                },


                {
                    data: null,
                    render: function (data, type, row) {
                        return '<div class="btn-group">' +
                            '<a href="' + data.link_sertifikat + '" target="_blank" ' +
                            'class="btn btn-xs btn-info" data-toggle="tooltip" title="View File">' +
                            '<i class="fa fa-eye"></i></a></div>';
                    }

                }
            ],
            fnCreatedRow: function (nRow, aData, iDataIndex, cells) {
                

            }
        };

        /**
         * Initialize master table
         */
        var masterTable = $('#tableRiwayatUjikom').DataTable(tableConfig);


        // Function to check if PDF can be displayed in browser
        function checkPdfSupport() {
            var isIE = !!window.MSInputMethodContext && !!document.documentMode;
            var isEdge = /Edge\/\d./i.test(navigator.userAgent);
            return !(isIE || isEdge);
        }

        // Function to handle PDF viewing
        function handlePdfView(url) {
            const pdfContainer = $('#pdf-container');
            const pdfFallback = $('#pdf-fallback');
            const pdfIframe = $('#pdf-iframe');
            const downloadLink = $('#download-link');

            // Update download link
            downloadLink.attr('href', url);

            if (checkPdfSupport()) {
                // Show PDF in iframe
                pdfContainer.show();
                pdfFallback.hide();
                
                // Add timestamp to prevent caching
                const timestampedUrl = url + (url.includes('?') ? '&' : '?') + 'timestamp=' + new Date().getTime();
                pdfIframe.attr('src', timestampedUrl);
            } else {
                // Show fallback message
                pdfContainer.hide();
                pdfFallback.show();
            }
        }



    })
</script>