<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{

    public function load_login_page(){
        //Insert data into database
        return view('doctor.login');
    }

    public function login(Request $request){
        $request->validate([
            'name'=>'required',
            'password'=>'required'
        ]);

        $userInfo = Doctor::where('name','=', $request->name)->first();

        if(!$userInfo){
            return back()->with('fail','We do not recognize your email address');
        }else{
            //check password
            if(Hash::check($request->password, $userInfo->password)){
                $request->session()->put('DoctorLoggedUser', $userInfo->id);
                return redirect('/doctor/dashbroad')->with(['id'=>$userInfo->id]);
            }else{
                return back()->with('fail','Incorrect password');
            }
        }
    }
    
    public function load_dashbroad_page(){
        $doctor_id = session('DoctorLoggedUser');
        $result = DB::table('appointments')
                    ->join('patients', 'appointments.code','=','patients.appointment_code')
                    ->where('appointments.doctor_id','=',$doctor_id)
                    ->select('appointments.code as code', 'patients.name as name', 'patients.symbol as symbol','appointments.date as date','patients.appointment_status as status')
                    ->get();
        return view('doctor.dashbroad',['appointment_list'=>$result]);
    }

    public function update_status(Request $request){
        $code = $request->code;
        DB::table('patients')
            ->where('appointment_code', $code)
            ->update(['appointment_status'=>'Done']);

        return response()->json(array('result'=> true), 200);
    }

    function logout(){
        if(session()->has('DoctorLoggedUser')){
            session()->pull('DoctorLoggedUser');
            return redirect('doctor');
        }
    }

}
