<form action="/changePassword/{{$data['user_id']}}" method="POST">
    @csrf
    <label for="currentPassword">Current Password</label><br><br>
    <input type="text" name="currentPassword" placeholder="Enter Current Password" ><br><br>
    <label for="newPassword">New Password</label><br><br>
    <input type="password" name="newPassword" placeholder="Enter New Password" ><br><br>
    <label for="confirmPassword">Confirm Password</label><br><br>
    <input type="password" name="confirmPassword" placeholder="Confirm New Password" ><br><br>
    <input type="submit" value="Change Password">
    @if(session()->has('error'))
        <span>{{session('error')}}</span>
    @endif
    @if ($errors->any())
        <div>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </div>  
    @endif