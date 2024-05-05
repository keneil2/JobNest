<?php

namespace App\Http\Controllers;

use App\Models\admins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\testEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class adminsController extends Controller
{
  public function show()
  {
    return view("adminsignup");
  }


  public function CreateAccount(Request $request)
  {
    $message = [
      "username.required" => "please enter a username",

      "username.regex" => "your username must contain letters and numbers only",

      "username.min" => " the username must be at least 8 characters long",

      "password.required" => " please enter a password",

      "password.regex" => "password must contain letter, number or !@#$&",

      "password.confirmed" => "the passwords do not match",

    ];



    $request->validate([
      "username" => ["required", "regex:/[A-Za-z]+[0-9]*/", "min:8"],

      "password" => ["required", "regex:/[A-Za-z]+[0-9]*[!@#$&]*/", "min:5", "confirmed"]

    ]);


    $this->VarCode();
    $this->sendEmail(session()->get("VarCode"));


    $pass = Hash::make($request->input("password"));

    $admin = new admins;

    $admin->username = $request->input("username");

    $admin->password = $pass;
    #$admin->varcode
    $admin->save();


    // View::view("Emailvar",);

    return redirect()->route("Emailvar.get", [
      'username' => $request->input("username")
    ]);
  }
  public function EmailVarified(Request $request)
  {
    if ($request->input("code") !== session()->get("varCode")) {
      
      return back()->with("fail", "the varification is Incorrect");
    }
    admins::where("username", "=", session()->get("username"))->update(["email_verified_at" => date("Y-m-d")]);
  }



  public function showemailvarPage($username=null){
    if($username==null){
      return view("Emailvar");
    }
    session()->put("username",$username);
    return view("Emailvar");
  }


  public function LoginAdmin(Request $request)
  {
    $message = [
      "username.required" => "please enter a username",

      "username.regex" => "your username must contain letters and numbers only",

      "username.min" => " the username must be at least 5 characters long",

      "password.required" => " please enter a password",

      "password.regex" => "password must contain letter, number or !@#$&",

    ];




    $credentials = $request->validate([
      "username" => ["required", "regex:/[A-Za-z]+[0-9]*/", "min:5"],

      "password" => ["required", "regex:/[A-Za-z]+[0-9]*[!@#$&]*/", "min:5"]

    ]);





    if (Auth::guard("admins")->attempt(request()->only(["username", "password"]))) {

      $request->session()->regenerate();

      return redirect()->route("dashboard");

    }




    return back()->with("fail", "incorrect username or password");


  }


  




  public function showloginForm()
  {
    return view("adminLogin");
  }


  private function VarCode()
  {
    $code = mt_rand(10000000, 1000000000);
    session()->put("varCode", $code);
  }

  public function logoutUser(Request $request)
  {
    if (session()->has("admin_id")) {

      session()->pull("admin_id");
    }





    $request->session()->invalidate();

    $request->session()->regenerateToken();


    return redirect("/adminLogin");
  }






  public function sendEmail($code)
  {
    $email = new TestEmail($code);
    Mail::to('kenniwilliams2@gmail.com')->send($email);
    return "Email sent successfully!";
  }
}