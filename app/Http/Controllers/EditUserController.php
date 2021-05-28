<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use DB; // подключим БД
use App\Models\Emploee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;



class EditUserController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index(){
        return view('usersAdd' , ['departments' => Department::all()]);

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function addNewUser(Request $request){
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'statusUser' => $request['statusUser'],
            'password' => Hash::make($request['password']),
        ]);


        $department = Department::find([(int) $request['department']]); // Modren Chairs, Home Chairs
//тут мне кажется есть косяк  - ибо это копипаст ) и мне кажется надо было в модели юзер добаить не такойже метод  / а обратную привязку к emploee
        //но я слишком долго просидел и так и не дошел до сути / даже задал вопрос на тосте где мне предложили добавить такойже метод с привязкой
        //  с чем я не согласился
        //но хоть работает
        //вызов предполагался такой $user->emploee()->departments()->attach($department);
        $user->departments()->attach($department);

        return redirect('/users');
    }
    public function editUser(Request $request , User $user){
//тут не хватает проверки на текущие должности и отделы  - поэтому можно добавить новый такойже

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);
        $user = User::find($request->updatedUser);


        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->statusUser = $request['statusUser'];
        $user->update();


        $department = Department::find($request->department); // Modren Chairs, Home Chairs

        $user->departments()->attach($department);

        return redirect('/users');
    }

    public function deleteUser($id){
        $user = Emploee::find($id);
        $departmentsList = [];
        foreach ($user->departments as $id => $valDepart){
            $departmentsList[] = $valDepart->id;
        }
        $departmentObb = Department::find($departmentsList);
        $user->departments()->detach($departmentObb);
        $user->delete();
        return redirect()->back();
    }

    public function editUserShow($id){
        $user = Emploee::find($id);
//        var_dump($user->name);
        $messDepartUser = [];
        foreach ($user->departments as $id => $valUser){
            $messDepartUser[] = $valUser->depart_code;
        }
        return view('usersEdit' , [
            'departmentsUser' => $user,
            'departsAll' => Department::all(),
            'departMessCurrentUser' => $messDepartUser
        ]);
    }
}
