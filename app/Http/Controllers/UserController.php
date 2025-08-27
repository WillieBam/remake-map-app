<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Country;
use App\Models\Message;
use Illuminate\Validation\Rule;
class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => ['required',Password::min(6)->letters()->numbers()->symbols()],
            'confirmPassword' =>'required|same:password'
        ]);
        $user = new User;
        $user -> name = $request -> name;
        $user -> email = $request -> email;
        $user -> password = bcrypt($request -> password); 
        $user -> role_id = 3;
        $user -> save();
        return redirect('viewSignUp')->with('success', 'Sign up successful!');
    }

    public function deleteUser($id,$adminId)
    {
        $this->authorize('deleteUser', User::class);
        $data = User::find($id);
        $data->delete();
        return redirect('/viewAllUser/'.$adminId);
    }
    public function deleteAdmin($id)
    {
        $this->authorize('deleteAdmin', User::class);
        $data = User::find($id);
        $data->delete();
        return redirect('/viewAllAdmin');
    }

    public function updateUser(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required','email',Rule::unique('users','email')->ignore($id,'user_id')]
        ]);
        $data = User::find($id);
        $data -> name = $request -> name;
        $data -> email = $request -> email;
        $data -> country_id = $request -> country_id;
        $data -> save();
        $countries = Country::all();
        $messages = Message::where('user_id',$id)->paginate(15);
        return view('profile',["data"=>$data,'success'=>'Update profile successful!','countries'=>$countries,"messages"=>$messages]);
    }
    public function viewUser($id)
    {
        $data = User::find($id);
        $countries = Country::all();
        $messages = Message::where('user_id',$id)->paginate(15);
        return view('profile',["data"=>$data,"countries"=>$countries,"messages"=>$messages]);
    }
    public function adminViewUser($id){
        $this->authorize('viewAllUser', User::class);
        $admin = User::find($id);
        if($admin->role_id==1)
            $data = User::where('role_id',3)->orderBy('name','asc')->paginate(15);
        else if($admin->role_id==2){
            $adminContinent = Country::find($admin->country_id) -> continent_id;
            $countryList = Country::where('continent_id',$adminContinent)->pluck('country_id');
            $data = User::whereIn('country_id',$countryList)->where('role_id',3)->orderBy('name','asc')->paginate(15);
        }
        return view('viewAllUser',["data"=>$data,"adminId"=>$id]);
    }
    public function globalAdminViewAdmin(){
        $this->authorize('viewAllAdmin', User::class);
        $data = User::where('role_id',2)->orderBy('name','asc')->paginate(15);
        return view('viewAllAdmin',["data"=>$data]);
    }

    public function banUser($id,$adminId)
    {
        $this->authorize('banUser', User::class);
        $data = User::find($id);
        $data -> is_banned = true;
        $data -> save();
        return redirect('/viewAllUser/'.$adminId);
    }
     public function banAdmin($id)
    {
        $this->authorize('banAdmin', User::class);
        $data = User::find($id);
        $data -> is_banned = true;
        $data -> save();
        return redirect('/viewAllAdmin');
    }
    public function changePassword(Request $request,$id)
    { 
        $request->validate([
            'currentPassword'=>'required',
            'newPassword' => ['required',Password::min(6)->letters()->numbers()->symbols()],
            'confirmPassword' =>'required|same:newPassword'
        ]);
        $user = User::find($id);
        if(!Hash::check($request->currentPassword,$user->password)){
            return redirect()->back()->with('error','current password is incorrect!');
        };
        $user -> password = bcrypt($request -> newPassword);
        $user -> save();
        return view('profile',['data'=>$user,'successPassword'=>'Change password successful!']);
    }
    public function viewChangePassword($id)
    {
        $data = User::find($id);
        return view('changePassword',["data"=>$data]);
    }
}
// Check if id matches with session user (checked in middleware)

        // Check if id exists in database

        // Mass assignment (name and email)
        // Update country Id
        // Update password (remember to hash it)

        // Save user to database

        // Return OK response
  // Check access level of session user (middleware)

        // Check if id exists in database

        // Delete user from database

        // Return OK response
// Check if name/email exists in database

        // Mass assignment (name and email)

        // Find and set country Id
        // Find and set role Id

        // Hash password

        // Save user to database

        // return OK response
/* public function getUser($id)
    {
        // Check access level of session user (middleware)

        // Check if id exists in database

        // Get user from database

        // Return user data
    }

    public function getUsers()
    {
        // check access level of session user (middleware)

        // Get all users from database

        // return users
    }
     // Check access level of session user (middleware)

        // Check if id exists in database

        // Ban user (set is_banned flag to true)

        // Return OK response*/