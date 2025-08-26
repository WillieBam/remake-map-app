<form action="/profile/{{$data['user_id']}}" method="POST">
    @csrf
    <label for="name">name</label><br><br>
    <input type="text" name="name" value="{{$data['name']}}" ><br><br>
    <label for="email">email</label><br><br>
    <input type="email" name="email" value="{{$data['email']}}" ><br><br>
    <a href="/changePassword/{{$data['user_id']}}">Change Password</a><br><br>
    <input type="submit" value="Update">
    @if(isset($success)&&$success)
        <span>{{$success}}</span>
    @elseif(isset($successPassword)&&$successPassword)
        <span>{{$successPassword}}</span>
    @endif
    @if ($errors->any())
        <div>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </div>  
    @endif