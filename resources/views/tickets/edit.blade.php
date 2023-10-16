@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit Ticket') }}</div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('tickets.update', $ticket->id) }}">
                            @csrf
                            @method('PATCH')

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input id="title" type="text" class="form-control" name="title"
                                    value="{{ $ticket->title }}" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" class="form-control" name="description" required>{{ $ticket->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="user_id">Users</label>
                                <select id="user_id" name="user_id" class="form-control" required>
                                    <option value=""> -- Select User -- </option>
                                    @foreach ($users as $uid => $user)
                                        <option value="{{ $uid }}"
                                            @if ($ticket->users->id === $uid) selected @endif>{{ $user }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="Pending" @if ($ticket->status === 'Pending') selected @endif>Pending
                                    </option>
                                    <option value="Closed" @if ($ticket->status === 'Closed') selected @endif>Closed
                                    </option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ __('Update Ticket') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
