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
        <td><a href="/deleteUser/{{$user['user_id']}}">delete</a></td>
        <td><a href="/banUser/{{$user['user_id']}}">ban</a></td>
    </tr>
    @endforeach
</table>
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