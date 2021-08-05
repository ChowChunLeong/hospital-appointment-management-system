<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Nurse;
use App\Models\Special_list_department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NurseController extends Controller
{
    //<-- navigate page view -->
    public function load_login_page(){
        return view('nurse.login');
    }
    public function load_dashbroad_page(){
        $result = DB::table('appointments')
                    ->leftJoin('patients', 'appointments.code','=','patients.appointment_code')
                    ->leftJoin('doctors','doctors.id','=','appointments.doctor_id')
                    ->select('appointments.code as code', 'patients.name as name', 'patients.symbol as symbol', 'appointments.status as status', 'doctors.name as doctor_name','appointments.date')
                    ->get();

        $special_list_department = Special_list_department::all();
        return view('nurse.dashbroad',['appointment_list'=>$result, 'special_list_department'=>$special_list_department]);
    }
    //<-- navigate page view -->


    public function login(Request $request){
        $request->validate([
            'email'=>'required | email',
            'password'=>'required'
        ]);
        $userInfo = Nurse::where('email','=', $request->email)->first();

        if(!$userInfo){
            return back()->with('fail','We do not recognize your email address');
        }else{
            //check password
            if(Hash::check($request->password, $userInfo->password)){
                $request->session()->put('NurseLoggedUser', $userInfo->id);
                return redirect('nurse/dashbroad');

            }else{
                return back()->with('fail','Incorrect password');
            }
        }
    }

    public function logout(){
        if(session()->has('NurseLoggedUser')){
            session()->pull('NurseLoggedUser');
            return redirect('nurse');
        }
    }

    public function retrieve_doctor_list(Request $request){
        $department_id = $request->department_id;
        $doctor_list = Doctor::where('department_id','=', $department_id)->get();
        return response()->json(array('doctor_list'=> $doctor_list), 200);
    }

    public function complete_appointment(Request $request){
        $doctor_id = $request->doctor_id;
        $code = $request->code;
        $appointment_date = date( "Y-m-d H:i:s", strtotime($request->appointment_date) );
        $doctor =  DB::table('doctors')->where('id', $doctor_id)->first();
        $updateData = [
            'doctor_id' => $doctor_id,
            'date' => $appointment_date
        ];
        DB::table('appointments')
            ->where('code', $code)
            ->update($updateData);
        return response()->json(array('doctor_name'=> $doctor->name), 200);
    }

    public function update_status(Request $request){
        $code = $request->code;
        DB::table('appointments')
            ->where('code', $code)
            ->update(['status'=>'Done']);

        return response()->json(array('result'=> true), 200);
    }

}
