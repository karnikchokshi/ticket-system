@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 table-responsive">
                <div class="card">
                    <div class="card-header">{{ __('Users') }}
                        <a class="btn btn-primary float-right" href="{{ route('users.create') }}" role="button">Add</a>
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

                        <table class="table table-bordered user_datatable">
                            <thead>
                                <tr>
                                    {{-- <th>ID</th> --}}
                                    <th>Name</th>
                                    <th>Email</th>
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
            var table = $('.user_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [
                    // {
                    //     data: 'id',
                    //     name: 'id'
                    // },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('.user_datatable').on('click', '.delete-user', function() {
                var userId = $(this).data('user-id');

                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/users/' + userId,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": userId
                        },
                        success: function(data) {
                            $('.user_datatable').DataTable().ajax.reload();
                            // alert(data.success); // Display a success message
                            // You can also remove the deleted record from the DataTable, if applicable.
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
