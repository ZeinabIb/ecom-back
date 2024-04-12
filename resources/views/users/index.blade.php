<!-- resources/views/users/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>All Users</h1>
        <ul>
            @foreach($users as $user)
                <li>
                    <a href="{{ route('startChat', $user->id) }}">{{ $user->username }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
