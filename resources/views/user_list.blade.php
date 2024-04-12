@foreach($users as $user)
    <a href="{{ url('/start-chat', $user->id) }}">{{ $user->name }}</a>
@endforeach
