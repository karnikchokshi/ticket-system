@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 table-responsive">
                <div class="card">
                    <div class="card-header">{{ __('Tickets') }}
                        <a class="btn btn-primary float-right" href="{{ route('tickets.create') }}" role="button">Add</a>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table table-bordered ticket_datatable">
                            <thead>
                                <tr>
                                    {{-- <th>ID</th> --}}
                                    <th>Title</th>
                                    {{-- <th>Description</th> --}}
                                    <th>User</th>
                                    <th>Status</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('.ticket_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('tickets.index') }}",
                columns: [
                    // {
                    //     data: 'id',
                    //     name: 'id'
                    // },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    // {
                    //     data: 'description',
                    //     name: 'description'
                    // },
                    {
                        data: 'users.name',
                        name: 'users.name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('.ticket_datatable').on('click', '.delete-ticket', function() {
                var ticketId = $(this).data('ticket-id');

                if (confirm('Are you sure you want to delete this ticket?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/tickets/' + ticketId,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": ticketId
                        },
                        success: function(data) {
                            $('.ticket_datatable').DataTable().ajax.reload();
                        },
                        error: function(error) {
                            // alert(error.responseJSON.message); // Display an error message
                        }
                    });
                }
            });
        });
    </script>
@endsection
