<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/orgchart-master/dist/css/jquery.orgchart.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/orgchart-master/dist/js/jquery.orgchart.js"></script>
<div id="chart-container"></div>
<script type="text/javascript" src="https://cdn.rawgit.com/jakerella/jquery-mockjax/master/dist/jquery.mockjax.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/orgchart-master/examples/ondemand-loading-data/scripts.js2"></script>
<script type="text/javascript"  >
    var datascource = {
      'id': '2',
      'name': 'Koharudin',
      'title': 'Kepala Riset DIKTI',
      'relationship': '111',
    };

    var ajaxURLs = {
        'children': '/orgchart/children/',
        'parent': '/orgchart/parent/',
        // It would be helpful to have functions instead of URLs for the AJAX fetching
        // as it would allow a more flexible treatment of the results.
        'siblings': function(nodeData) {
            return '/orgchart/siblings/' + nodeData.id;
        },
        'families': function(nodeData) {
            return '/orgchart/families/' + nodeData.id;
        }
    };
    $('#chart-container').orgchart({
      'data': "<?php echo base_url(); ?>/petajabatan/struktur/load_tree",
      'depth': 2,
      'nodeContent': 'title'
    });
</script>
