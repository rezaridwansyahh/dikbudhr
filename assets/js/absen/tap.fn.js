Tap = function() {
    var renderMap = function(idContainer) {
        var $container = $("#" + idContainer);
        $(".btn-tap-absen", $container).hide();
        var popup = L.popup();
        var map = L.map('mapid', {
            center: [51.505, -0.09],
            zoom: 13
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        $("#message").html('');
        $table = $('.table-history').DataTable({
            "sDom": '<"top">rt<"row"<"col-md-3"><"col-md-3"i><"col-md-6"p>><"clear">',
            "processing": true, //Feature control the processing indicator.
            "ordering": false,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "pageLength": 5,
            responsive: true,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "history_absen",
                "type": "POST",
                "data": function(d) {
                    
                }
            }
        });

        function geolocationErrorOccurred(geolocationSupported, popup, latLng) {
            $(".btn-tap-absen", $container).hide();
            if (geolocationSupported) {
                $("#message").html('<b>Error:</b> The Geolocation service failed.');
            } else $("#message").html('<b>Error:</b> This browser doesn\'t support geolocation.');

            popup.setLatLng(latLng);
            popup.setContent(geolocationSupported ?
                '<b>Error:</b> The Geolocation service failed.' :
                '<b>Error:</b> This browser doesn\'t support geolocation.');
            popup.openOn(geolocationMap);
        }
        var latLng = {};
        if (navigator.geolocation) {
            $("#message").html('Process pencarian lokasi...');
            navigator.geolocation.getCurrentPosition(function(position) {
                $("#message").html('');
                $(".btn-tap-absen", $container).show();
                // console.log(position);
                latLng = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                popup.setLatLng(latLng);
                popup.setContent('Lokasi Anda ');
                popup.openOn(map);
                $("#info").html(latLng.lat + "," + latLng.lng);
                // $.post("check_area", latLng, function(o) {
                //     if (o.success) {
                //         $("#info").html(latLng.lat + "," + latLng.lng + " (<b>" + o.message + "</b>)");
                //     } else {
                //         $("#info").html(latLng.lat + "," + latLng.lng + " (<b>" + o.message + "</b>)");
                //     }
                // }, 'json');
                map.setView(latLng);
            }, function() {
                geolocationErrorOccurred(true, map.getCenter());
            });
        } else {
            //No browser support geolocation service
            geolocationErrorOccurred(false, map.getCenter());
        }
$( "#btn_lapor_kehadiran").click(function() {
    swal({
        title: "Anda Yakin?",
        text: "Saya benar sedang jadwal BDR dan bertanggungjawab atas pelaporan ini!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: 'btn-success',
        confirmButtonText: 'Ya, Lapor BDR!',
        cancelButtonText: "Tidak, Batalkan!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
           var params = $("#form-tap-absen").serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});
            params = $.extend(params, latLng);
            var s = JSON.stringify(params);
            for (i = 1; i <= 10; i++) {
                s = btoa(s);
            }
            $.post('tap_absen', {
                o: s
            }, function(o) {
                if (o.success) {
                    $table.ajax.reload(null,true);
                    swal("Pemberitahuan!", o.message, "success");
                } else swal("Perhatian!", o.message, "error");
            }).fail(function() {
                swal("Perhatian!", "internal error", "error")
            })
            return false;
            
        } else {
            swal("Batal", "", "error");
        }
    });
            
        })
    }
    return {
        init: function(idContainer) {
            renderMap(idContainer);
        }
    }

}();