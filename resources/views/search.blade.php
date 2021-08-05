@include('layouts.master')

<body>
    <div di='display_appointment_info'>
        <div class="container" style="margin-top: 2rem;">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-12">
                <h3 class="text-center text-info">Appointment Info</h3>

                <table class="table table-bordered mb-none" id="datatable-default">
                    <tbody>
                        <tr>
                            <td width="25%">Patient Name</td>
                            <td width="75%">{{$appointment_info->name}}</td>
                        </tr>
                        <tr>
                            <td>Patient Symbol</td>
                            <td>{{$appointment_info->symbol}}
                            </td>
                        </tr>
                        <tr>
                            <td>Appointment code</td>
                            <td>
                                {{$appointment_info->code}}
                            </td>
                        </tr>
                        <tr>
                            <td>Special list doctor</td>
                            <td>
                                @if(empty($appointment_info->doctor_name))
                                    N/A
                                @else
                                    {{$appointment_info->doctor_name}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Appointment Date and time</td>
                            <td>
                                @if(empty($appointment_info->date))
                                    N/A
                                @else
                                    {{date('d-m-Y H:i', strtotime($appointment_info->date))}}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>

        </div>
    </div>
</body>
