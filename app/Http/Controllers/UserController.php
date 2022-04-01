<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{

	public $user;

    public function __construct()
  {
    $this->user = JWTAuth::parseToken()->authenticate();
    
  }
    public function store(Request $request)
  {
    $validator = Validator::make($request->all(),[
      'name'  => 'required|string',
      'username'       =>'required|string',
      'password'=>'required|string|min:6',
      'role'=>'required|string',
      'id_outlet' => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors());
    }

    $user = new User();
		$user->name 	= $request->name;
		$user->username = $request->username;
		$user->password = Hash::make($request->password);
		$user->role 	= $request->role;
		$user->id_outlet = $request->id_outlet;

		$user->save();

    $user->save();
    $data = User::where('id', '=', $user->id)->first();

    return Response()->json([
      'message' => 'Data user berhasil ditambahkan',
      'data' => $data
    ]);
  }

  public function getAll()
  {
    $data = DB::table('users')->join('outlet', 'users.id_outlet', '=', 'outlet.id_outlet')
    ->select('users.*', 'outlet.nama_outlet')
    ->get();

return response()->json($data);
  }

  public function getById($id)
  {
    $data = DB::table('users')->join('outlet', 'users.id_outlet', '=', 'outlet.id_outlet')
                              ->select('users.*', 'outlet.nama_outlet')
                              ->get();

      return response()->json(['data' => $data]);
  }

  public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'role' => 'required',
			'name' => 'required',
			'id_outlet' => 'required'
		]);
		
		$user = User::where('id', '=', $id)->first();
		
		$user->name = $request->name;
		$user->username = $request->username;
		$user->role = $request->role;
		$user->id_outlet = $request->id_outlet;
		if($request->password != null) {
			$user->password = Hash::make($request->password);
		}

		$user->save();

		return response()->json([
			'success' => true,
			'message' => 'Data user berhasil diubah'
		]);

	}
  public function delete($id)
  {
      $delete = User::where('id', '=', $id)->delete();

      if ($delete) {
          return response()->json(['message' => 'Data user berhasil dihapus']);
      }else {
          return response()->json(['message' => 'Data user gagal dihapus']);
      }
  }
}