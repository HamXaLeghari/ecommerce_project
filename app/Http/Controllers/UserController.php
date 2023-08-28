<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        try {

         $validatedData = $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|email|unique:user',
             'password' => 'required|string|min:8|regex:/[0-9]/', // You might want to adjust the minimum length
             'phone' => 'required|string|max:20',
             'Current_address' => 'required|string|max:100',
             'is_admin' => 'nullable|boolean|in:1,0',
             'gender' => 'required|in:Male,Female',
         ]);
        
       /* $name = $request->input('name');
        $email = $request->input('email');
        $password=$request->input('password');
        $phone=$request->input('phone');
        $Current_address=$request->input('Current_address');
        $is_admin=$request->input('is_admin');
        $gender=$request->input('gender');



        $user= new Users();

        $user->name=$name;
        $user->email=$email;
        $user->password=$password;
        $user->phone=$phone;
        $user->Current_address=$Current_address;
        $user->is_admin=$is_admin;
        $user->gender=$gender;
         */
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user=new Users($validatedData);

        $user->save();


        return response()->json(['message' => 'User registered successfully']);
        }catch (QueryException $e) {
            // Handle database-related exceptions
            return response()->json(['message' => 'Database Query Error']);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Error Occured :Please Enter Valid Data']);
        }    
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function login(Request $request)
    {

        try {

            $validatedData = $request->validate([
                'email' => 'required',
                'password' => 'required',
            ]);
        $credentials =$request->only('email','password');

        if(Auth::attempt($credentials))
        {
            $user=Auth::user();
            if($user->is_admin)
            {
                return response()->json('Admin', 200);
            }
            else
            return response()->json('Customer', 200);
            
            return response()->json(['message' => 'Login Successful!'], 200);
        }
        else
        {
            return response()->json(['message' => 'Invalid Email or Password'], 401);   
        }

        /*$email = $request->input('email');
    $password = $request->input('password');

    // Find user by email
    $user = Users::where('email', $email)->first();

    if ($user && $user->password === $password) {
        // Authentication successful
        return response()->json(['message' => 'Login Successful!'], 200);
    } else {
        // Authentication failed
        return response()->json(['message' => 'Invalid Email or Password'], 401);
    }*/
    }catch (\Exception $e) {
        // Handle other exceptions
        return response()->json(['message' => 'Error Occured :Please Enter Both Email and Password']);
    }
    
    }
    
    
}
