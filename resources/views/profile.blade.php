<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

<style>
    table {
        width: 100%;
    }

    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    #update {
        color: blue;
        text-decoration: underline;
    }
</style>
<div style="padding: 20px;">
<form action="/profile/{{$data['user_id']}}" method="POST">
    @csrf
    <label for="name">name</label><br><br>
    <input type="text" name="name" value="{{$data['name']}}" ><br><br>
    <label for="email">email</label><br><br>
    <input type="email" name="email" value="{{$data['email']}}" ><br><br>
    <label for="country">Country</label><br><br>
    <select name="country_id" id="country" class="form-control">
        @if(empty($data->country_id))
            <option value="" disabled selected>-- Select Country --</option>
        @endif
        @foreach($countries as $country)
            <option value="{{ $country->country_id }}" 
                {{ old('country_id', $data->country_id ?? '') == $country->country_id ? 'selected' : '' }}>
                {{ $country->name }}
            </option>
        @endforeach
    </select><br><br>
    <a id="update" href="/changePassword/{{$data['user_id']}}">Change Password</a><br><br>
    <input type="submit" style=" width: 70px; height: 30px; background: green; color: white;" value="Update">
    <br><br>
    @if(session()->has('success'))
        <span>{{session('success')}}</span>
    @elseif(session()->has('successPassword'))
        <span>{{session('successPassword')}}</span>
    @endif
    @if ($errors->any())
        <div>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </div>  
    @endif
<h2>Message History</h2>
<table border = 1>
    <tr>
        <th>Message</th>
        <th>View</th>
    </tr>
    @foreach($messages as $message)
    <tr>
        <td>{{$message['content']}}</td>
        <td>{{$message['views']}}</td>
    </tr>
    @endforeach
</table>
<span>
    {{$messages->links()}}
</span>
<style>
    .w-5{
        display:none
    }
    table{
        border-collapse:collapse;
    }
</style>
</div>
</x-app-layout>
