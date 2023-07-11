<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use SessionResponseMessages;
use Illuminate\Support\Facades\Hash;


class UserController extends AdminBaseController
  {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
    {
    $users = User::with("hotel")
      ->orderBy("role", "asc")
      ->get();

    $account_hotel = User::where('role','hotel')->count();
    $account_operatore = User::where('role','operatore')->count();
    $account_admin = User::where('role','admin')->count();

    $data = ["records" => $users, "account_hotel" => $account_hotel, "account_operatore" => $account_operatore, "account_admin" => $account_admin];

    return view('admin.utenti_index', compact("data"));
    }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
    {
    $user = User::with("hotel")
      ->findOrFail($id);
    
    $data = ["record" => $user];

    return view('admin.utenti_edit', compact("data"));
    }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
    {
    $data = ["record" => new User];

    return view('admin.utenti_edit', compact("data"));
    }

  /**
   * Store a newly created resource in storage.
   * @param  Request  $request
   * @return Response
   */
  public function store(Request $request)
    {
    $id = $request->input("id");

    $username_unique_for_id = null;
    if($id)
      $username_unique_for_id = ",$id";

    $this->validate($request, [
      'email' => 'required|email',
      'username' => 'required|unique:users,username'.$username_unique_for_id,
      // regex: deve contenere almeno una lettera e almeno un numero
      'password' => 'required_if:password_cambia,1|confirmed|min:8|regex:#^(?=.*[a-z])(?=.*\d).*$#',
      'role' => 'required',
      'hotel_associato' => 'required_if:role,hotel|regex:#^\d+#',
    ]);    

    if(!$id)
        $user = new User;
    else
        $user = User::findOrFail($id);

    $user->email = $request->input("email");
    $user->username = $request->input("username");
     if ($request->filled("password")) 
      {
      $user->password = Hash::make($request->input("password"));
      }
    $user->role = $request->input("role");
    $user->hotel_id = (int)$request->input("hotel_associato");
    if($user->role != 'hotel')
      $user->hotel_id = 0;

    $user->save();

    SessionResponseMessages::add("success", "Modifiche salvate con successo.");

    return SessionResponseMessages::redirect("/admin/utenti/{$user->id}/edit", $request);
    }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Request  $request
   * @return Response
   */
  public function destroy(Request $request)
    {
    $id = $request->input("id");

    User::find($id)->destroyMe();

    SessionResponseMessages::add("success", "Il record ID=$id è stato eliminato.");

    return SessionResponseMessages::redirect("/admin/utenti", $request);
    }

  }
