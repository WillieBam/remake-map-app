<form action="/viewSignUp" method="POST">
    @csrf
    <label for="name">Name</label><br><br>
    <input type="text" name="name" placeholder="Enter your name"><br><br>
    <label for="email">Email</label><br><br>
    <input type="email" name="email" placeholder="Enter your email"><br><br>
    <label for="password">Password</label><br><br>
    <input type="password" name="password" placeholder="Enter your password" ><br><br>
    <label for="confirmPassword">Confirm Password</label><br><br>
    <input type="password" name="confirmPassword" placeholder="Enter your password again" ><br><br>
    <input type="submit" value="Sign Up">
    @if(session()->has('success'))
        <span>{{session('success')}}</span>
    @endif
    @if ($errors->any())
        <div>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </div>  
    @endif
