<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admins') }}
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

    .operation {
        color: blue;
        text-decoration: underline;
    }
</style>
<div style=" padding: 10px; background: green; color: white; margin: 20px; width: fit-content"  >
<a  href="{{ route('user.createAdmin') }}">Create Admin</a>
</div>
<table border = 1>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Banned</th>
        <th>Delete</th>
        <th>Ban</th>
    </tr>
    @foreach($data as $user)
    <tr>
        <td>{{$user['name']}}</td>
        <td>{{$user['email']}}</td>
        @if($user['is_banned']==1)
            <td>banned</td>
        @else
            <td>active</td>
        @endif
        <td><a class="operation" href="/deleteAdmin/{{$user['user_id']}}">delete</a></td>
        <td><a class="operation" href="/banAdmin/{{$user['user_id']}}">ban</a></td>
    </tr>
    @endforeach
</table>
<br>
<span>
    {{$data->links()}}
</span>
<style>
    .w-5{
        display:none
    }
    table{
        border-collapse:collapse;
    }
</style>
</x-app-layout>