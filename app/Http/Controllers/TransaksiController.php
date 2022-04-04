<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\User;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    
    public $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_member' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $transaksi = new Transaksi();
        $transaksi->id_member = $request->id_member;
        $transaksi->tanggal = Carbon::now();
        $transaksi->batas_waktu = Carbon::now()->addDays(3);
        $transaksi->status = 'baru';
        $transaksi->dibayar = 'belum_dibayar';
        $transaksi->id = $this->user->id;

        $transaksi->save();

        $data = Transaksi::where('id_transaksi', '=', $transaksi->id_transaksi)->first();

        return response()->json([
            'success' => true,
            'message' => 'Data transaksi berhasil ditambahkan',
            'data' => $data
        ]);
    }

    public function getAll()
    {
        $id_user = $this->user->id;
        $data_user = User::where('id', '=', $id_user)->first();

        $data = DB::table('transaksi')  ->join('member', 'transaksi.id_member', '=', 'member.id_member')
                                        ->join('users', 'transaksi.id', 'users.id')
                                        ->select('transaksi.*', 'member.nama_member','users.name')
                                        //->select('transaksi.id', 'member.nama_member', 'transaksi.tanggal', 'transaksi.status' , 'users.name')
                                        ->where('users.id_outlet', $data_user->id_outlet)
                                        ->orderBy('transaksi.id_transaksi')
                                        ->get();
                    
        return response()->json(['success' => true,'data' =>$data]);
    }
    
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_member' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $transaksi = Transaksi::where('id_transaksi', '=', $id_transaksi)->first();
        
        $transaksi->id_member = $request->id_member;

        $transaksi->save();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diubah'
        ]);
    }

    public function getById($id)
    {
        $data = Transaksi::where('id_transaksi', '=', $id)->first();  
        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')
                                      //->join('users', 'transaksi.id', 'users.id')    
                                      ->select('transaksi.*', 'member.nama_member')
                                      ->where('transaksi.id_transaksi', '=', $id)
                                      //->where('users.id_outlet', $data_user->id_outlet)
                                      ->first();
        return response()->json($data);
    }

    public function changeStatus(Request $request, $id_transaksi)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        
        if($validator->fails()) {
            return response()->json($validator->errors());
        }
        
        $ubahStatus = Transaksi::where('id_transaksi', $id_transaksi)->update([
            'status' => $request->status
        ]);

        if ($ubahStatus) {
            return Response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah'
            ]);
        }
    }
    
    public function bayar($id)
    {
        $transaksi = Transaksi::where('id_transaksi', $id)->first();
        $total = DetailTransaksi::where('id_transaksi', $id)->sum('subtotal');
        $bayar = Transaksi::where('id_transaksi', $id)->update([
            'tanggal_bayar' => Carbon::now(),
            'status' => "diambil",
            'dibayar' => "dibayar",
            'total_bayar' => $total
        ]);

        if ($bayar) {
            return Response()->json([
                'success' => true,
                'message' => 'Pembayaran Berhasil'
            ]);
        }
    }

    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'bulan' => 'required'
        ]);
        
        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $tahun = $request->tahun;
        $bulan = $request->bulan;
        
        $id_user = $this->user->id;
        $data_user = User::where('id', '=', $id_user)->first();

        $data = DB::table('transaksi')  ->join('member', 'transaksi.id_member', '=', 'member.id_member')
                                        ->join('users', 'transaksi.id', '=', 'users.id')
                                        ->select('transaksi.id_transaksi', 'member.nama_member' , 'transaksi.tanggal','transaksi.tanggal_bayar','transaksi.total_bayar', 'users.name' )
                                        ->where('users.id_outlet', $data_user->id_outlet)
                                        ->whereYear('transaksi.tanggal', '=' , $tahun)
                                        ->whereMonth('transaksi.tanggal', '=', $bulan)
                                        ->get();
        return response()->json($data);
    }

}