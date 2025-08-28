<form action="/viewCreateAdmin" method="POST">
    @csrf
    <label for="name">Name</label><br><br>
    <input type="text" name="name" placeholder="Enter your name" required><br><br>
    <label for="email">Email</label><br><br>
    <input type="email" name="email" placeholder="Enter your email" required><br><br>
    <label for="country">Country</label><br><br>
    <select name="country_id" id="country" class="form-control" required>
        <option value="" disabled selected>-- Select Country --</option>
        @foreach($countries as $country)
            <option value="{{ $country->country_id }}" >
                {{ $country->name }}
            </option>
        @endforeach
    </select><br><br>
    <label for="password">Password</label><br><br>
    <input type="password" name="password" placeholder="Enter your password" required><br><br>
    <label for="confirmPassword">Confirm Password</label><br><br>
    <input type="password" name="confirmPassword" placeholder="Enter your password again" required><br><br>
    <input type="submit" value="Create Admin">
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
