@extends('include_backend/template_backend')

@php
$ci = get_instance();
@endphp

@section('style')
<link href="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.css"
    rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container mt-5">

    <div class="card">
        <div class="card-header bg-secondary font-weight-bold">
            Pengguna Surveyor
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table id="table" class="table table-bordered" cellspacing="0" width="100%">
                    <thead class="bg-secondary">
                        <tr>
                            <th>No.</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Groups</th>
                            <th>Status</th>
                            <th></th>
                            <!-- <th></th> -->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>



</div>

@endsection

@section('javascript')
@if ($message)
<script type="text/Javascript">
    Swal.fire('@php
			echo $message
		@endphp');
	</script>
@endif

<script src="{{ TEMPLATE_BACKEND_PATH }}plugins/custom/datatables/datatables.bundle.js"></script>
<script>
$(document).ready(function() {
    table = $('#table').DataTable({

        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "{{ base_url() }}pengguna-surveyor/ajax-list-surveyor",
            "type": "POST",
            "data": function(data) {}
        },

        "columnDefs": [{
            "targets": [-1],
            "orderable": false,
        }, ],

    });
});

$('#btn-filter').click(function() {
    table.ajax.reload();
});
$('#btn-reset').click(function() {
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
</script>
@endsection