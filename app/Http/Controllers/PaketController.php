<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Paket;
use JWTAuth;

class PaketController extends Controller
{
    public $user;

    public function __construct()
  {
    // this->$user = JWTAuth::parseToken()->authenticate();
  }
    public function store(Request $request)
  {
    $validator = Validator::make($request->all(),[
      'jenis'  => 'required|string',
      'harga'   =>'required|string',
      
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors());
    }

    $paket = new Paket();
    $paket->jenis   = $request -> jenis;
    $paket->harga   = $request -> harga;
    
    $paket->save();
    $data = Paket::where('id_paket', '=', $paket->id)->first();

    return Response()->json([
      'message' => 'Data paket berhasil ditambahkan',
      'data' => $data
    ]);
  }

  public function getAll()
  {
    $data = Paket::get();        
    return response()->json($data);
  }

  public function getById($id)
  {
    $data = Paket::where('id_paket', $id)->first();
    return response()->json($data);
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(),[
      'jenis'  => 'required|string',
      'harga'       =>'required|string',
      
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors());
    }

    $paket = Paket::where('id_paket', '=', $id)->first();
    $paket->jenis   = $request -> jenis;
    $paket->harga   = $request -> harga;
   
    $paket->save();

    return Response()->json(['message' => 'Data paket berhasil diubah',]);
  }

  public function delete($id)
  {
      $delete = Paket::where('id_paket', '=', $id)->delete();

      if ($delete) {
          return response()->json(['message' => 'Data paket berhasil dihapus']);
      }else {
          return response()->json(['message' => 'Data paket gagal dihapus']);
      }
  }
}