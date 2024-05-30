<?php
$ci = get_instance();
?>

<div class="card card-custom">
    <div class="card-body">
        <table id="table" class="table table-bordered table-hover" cellspacing="0" width="100%">
            <thead class="bg-secondary">
                <tr>
                    <th>No</th>
                    <th>Nama Survei</th>
                    <th>Organisasi</th>
                    <th>IPKP</th>
                    <th>IPAK</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


<script>
$(document).ready(function() {
    table = $('#table').DataTable({
        "processing": true,
        "serverSide": true,
        "lengthMenu": [
            [10, 15, -1],
            [10, 15, "Semua data"]
        ],
        "pageLength": 10,
        "order": [],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
        },
        "ajax": {
            "url": "<?php echo e(base_url() . 'dashboard/ajax-list-tabel-survei-induk'); ?>",
            "type": "POST",
            "data": function(data) {}
        },
        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],
    });
});
</script>

<?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/dashboard/tabel_survei_induk.blade.php ENDPATH**/ ?>