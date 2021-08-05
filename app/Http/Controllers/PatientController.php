<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Exception;

class PatientController extends Controller
{
    public function load_patient_view(){
        return view('patient_landing');
    }
    public function submit_and_save_register_info(Request $req){
        $req->validate([
            'name' => 'required|max:255',
            'symbol'=>'required'
        ]);
        try{   
            $patients = new Patient();
            $patients->name = $req->name;
            $patients->symbol = $req->symbol;
            $max_current_id = Patient::max('id');
            $code = generate_code($max_current_id);
            $patients->appointment_code = $code;
            $patients->appointment_status = 'Pending';
            $patients->save();
            $appointment = new appointment();
            $appointment->code = $code;
            $appointment->status = "Pending";
            $appointment->save();

            return Redirect::route('search', array('code' => $code)); 
        }catch(Exception $e){
            return view('error',['error_msg'=>'Fail to make appointment', 'back_to'=>'landing_page']);
        }
    }

    public function retrieve_patient_info(Request $request){
        $code =$request->code;
        //DB::enableQueryLog(); // Enable query log

        $result = DB::table('patients')
                    ->join('appointments','patients.appointment_code','=', 'appointments.code')
                    ->leftJoin('doctors','appointments.doctor_id','=','doctors.id')
                    ->where('patients.appointment_code','=' ,$code)
                    ->select('appointments.code as code', 'patients.name as name', 'patients.symbol as symbol', 'appointments.status as status', 'doctors.name as doctor_name','appointments.date')
                    ->first();
        if(!empty($result)){
            return view('search',['appointment_info'=>$result]);
        }else{
            return view('error',['error_msg'=>'Code not found', 'back_to'=>'landing_page']);
        }

    }
}

function generate_code($patient_current_id){
    $added_patient_id = $patient_current_id+1;
    return 'sss'.$added_patient_id;
}
