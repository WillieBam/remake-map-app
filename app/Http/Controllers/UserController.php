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
        $this->authorize('isAdmin', User::class);
        $data = User::find($id);
        $data->delete();
        return redirect('/viewAllUser/'.$adminId);
    }
    public function deleteAdmin($id)
    {
        $this->authorize('isGlobalAdmin', User::class);
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
        $this->authorize('isUserLogIn', $data);
        $data -> name = $request -> name;
        $data -> email = $request -> email;
        $data -> country_id = $request -> country_id;
        $data -> save();
        return redirect("/profile/".$id)->with('success','Update profile successful!');
    }
    public function viewUser($id)
    {
        $data = User::find($id);
        $this->authorize('isUserLogIn', $data);
        $countries = Country::all();
        $messages = Message::where('user_id',$id)->paginate(15);
        return view('profile',["data"=>$data,"countries"=>$countries,"messages"=>$messages]);
    }
    public function adminViewUser($id){
        $admin = User::find($id);
        $this->authorize('adminViewUser', $admin);
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
        $this->authorize('isGlobalAdmin', User::class);
        $data = User::where('role_id',2)->orderBy('name','asc')->paginate(15);
        return view('viewAllAdmin',["data"=>$data]);
    }

    public function banUser($id,$adminId)
    {
        $this->authorize('isAdmin', User::class);
        $data = User::find($id);
        $data -> is_banned = true;
        $data -> save();
        return redirect('/viewAllUser/'.$adminId);
    }
     public function banAdmin($id)
    {
        $this->authorize('isGlobalAdmin', User::class);
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
        $this->authorize('isUserLogIn', $user);
        if(!Hash::check($request->currentPassword,$user->password)){
            return redirect()->back()->with('error','current password is incorrect!');
        };
        $user -> password = bcrypt($request -> newPassword);
        $user -> save();
        return redirect("/profile/".$id)->with('successPassword','Change password successful!');
    }
    public function viewChangePassword($id)
    {
        $data = User::find($id);
        $this->authorize('isUserLogIn', $data);
        return view('changePassword',["data"=>$data]);
    }
}
