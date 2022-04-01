<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;
use JWTAuth;

class MemberController extends Controller
{
    public $user;

    public function __construct()
  {
    $this->user = JWTAuth::parseToken()->authenticate();
    
  }
    public function store(Request $request)
  {
    $validator = Validator::make($request->all(),[
      'nama_member'  => 'required|string',
      'alamat'       =>'required|string',
      'jenis_kelamin'=>'required|string',
      'nomor_telepon'=>'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors());
    }

    $member = new Member();
    $member->nama_member   = $request -> nama_member;
    $member->alamat        = $request -> alamat;
    $member->jenis_kelamin = $request -> jenis_kelamin;
    $member->nomor_telepon = $request -> nomor_telepon;

    $member->save();
    $data = Member::where('id_member', '=', $member->id_member)->first();

    return Response()->json([
      'success' => true,
      'message' => 'Data member berhasil ditambahkan',
      'data' => $data
    ]);
  }

  public function getAll()
  {
    $data = Member::get();        
    return response()->json($data);
  }

  public function getById($id)
  {
    $data = Member::where('id_member', $id)->first();
    return response()->json($data);
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(),[
      'nama_member'  => 'required|string',
      'alamat'       =>'required|string',
      'jenis_kelamin'=>'required|string',
      'nomor_telepon'=>'required',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors());
    }

    $member = Member::where('id_member', '=', $id)->first();
    $member->nama_member   = $request -> nama_member;
    $member->alamat        = $request -> alamat;
    $member->jenis_kelamin = $request -> jenis_kelamin;
    $member->nomor_telepon = $request -> nomor_telepon;

    $member->save();

    return Response()->json(['message' => 'Data member berhasil diubah',]);
  }

  public function delete($id)
  {
      $delete = Member::where('id_member', '=', $id)->delete();

      if ($delete) {
          return response()->json([
            'success' => true,
            'message' => 'Data member berhasil dihapus']);
      }else {
          return response()->json([
            'success' => false,
            'message' => 'Data member gagal dihapus']);
      }
  }
}
