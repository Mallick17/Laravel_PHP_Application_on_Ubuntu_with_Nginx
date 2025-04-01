@extends('layouts.app')
@section('content')
    <div class="container">

        <table id="task-table" class="table table-striped" cellspacing="0"
               width="100%" role="grid" style="width: 100%;">
            <thead>
            <tr>

                <th scope="col">Task name</th>
                <th scope="col">Description</th>
                <th scope="col">Priority</th>
                <th scope="col">Assigned by</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>


            </tr>
            </thead>

        </table>
        <div>

@endsection

@push('script')
    <script>
        $(document).ready(function(){

            $('#task-table').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '{{ route("tasks.assigned.index") }}',
                    type: 'GET',
                },
                columns:[
                    {data:'name' ,name:'name'},
                    {data:'description',name:'description'},
                    {data:'priority'},
                    {data:'user.name'},
                    {data:'status'},
                    {data:'actions'},
                ],

            });

        });
    </script>
@endpush
