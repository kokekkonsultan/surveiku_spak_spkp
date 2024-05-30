@php
$ci = get_instance();
@endphp

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
            "url": "{{base_url() . 'dashboard/ajax-list-tabel-survei-induk'}}",
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

