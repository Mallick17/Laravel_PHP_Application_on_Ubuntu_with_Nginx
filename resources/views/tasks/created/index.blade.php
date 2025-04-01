@extends('layouts.app')
@section('content')
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Holy guacamole!</strong> You should check in on some of those fields below.
        <button type="button" class="close btn" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true" class="text-lg">×</span>
        </button>
    </div>
    <div class="container w-75">
        <div class="font-weight-bolder display-6 pb-5">Task Created :</div>


        <table id="task-table" class="table table-bordered table-striped" cellspacing="0"
               role="grid">
            <thead>
            <tr>
                <th scope="col">S.No</th>
                <th scope="col">Task name</th>
                <th scope="col">Description</th>
                <th scope="col">Priority</th>
                <th scope="col">Completion Percentage</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>

        </table>
    <div>
            @endsection
            @push('script')

                <script>

                    $('#task-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{route("tasks.index")}}',
                            type: 'GET'
                        },
                        columns: [
                            {data: 'name', name: 'name'},
                            {data: 'description', name: 'description'},
                            {data: 'priority'},
                            {data: 'completion_percentage'},
                            {data: 'status'},
                            {data: 'actions', orderable: false, searchable: false},

                        ],
                    });


                </script>
    @endpush
