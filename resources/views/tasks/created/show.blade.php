@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Task Details</div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-black-50 text-md-end">Task Name : </p></label>
                            <div class="col-md-6">
                                <p >{{ $task->name }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-black-50 text-md-end">Description : </label>
                            <div class="col-md-6">
                                <p>{{ $task->description }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="priority" class="col-md-4 col-form-label text-black-50 text-md-end">Priority : </label>
                            <div class="col-md-6">
                                <p>{{ ucfirst($task->priority) }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="status" class="col-md-4 col-form-label text-black-50 text-md-end">Status : </label>
                            <div class="col-md-6">
                                <p>{{ ucfirst($task->status) }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="status" class="col-md-4 col-form-label text-black-50 text-md-end">Completion percentage : </label>
                            <div class="col-md-6">
                                <p>{{ ucfirst($task->completion_percentage) }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="assigned_users" class="col-md-4 col-form-label text-black-50 text-md-end">Assigned Users : </label>
                            <div class="col-md-6">
                                <ul>
                                    @foreach($task->users as $user)
                                        <li>{{ $user->name }}
                                            <ul><span class="font-weight-bold text-black-50">status</span> : {{$user->pivot->status}}</ul>
                                        </li>

                                    @endforeach
                                </ul>
                            </div>
                        </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
