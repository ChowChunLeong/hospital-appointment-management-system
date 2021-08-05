@include('layouts.master')
<script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

<script>
    $(document).ready(function() {
        $('#appointment-table').DataTable();
    });
    $(document).on("click", ".update-status" , function() {
        var code = $(this).data('item-id');
        var selected_data = $('.update-status[data-item-id="'+code+'"]').parent('.status');
        $.ajax({
            type:'POST',
            url:'{{ route("doctor.dashbroad.update_status") }}',
            data:{code:code, _token: '{{csrf_token()}}'},
            success:function(data){
                selected_data.empty();
                selected_data.append('Done');
            }
        })
    });
</script>

<x-header data="doctor"/>
<div class="container">
    <div class="row justify-content-center align-items-center" style="margin-top: 2rem;">
        <div class="col-md-12">
            <table id="appointment-table" class="table table-bordered mb-none">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Patient name</th>
                    <th>Symbol</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($appointment_list as $appointment)
                <tr class="data-row">
                    <td class="code">{{$appointment->code}}</td>
                    <td class="name">{{$appointment->name}}</td>
                    <td class="symbol">{{$appointment->symbol}}</td>
                    <td class="symbol">{{date('d-m-Y H:i', strtotime($appointment->date))}}</td>
                    <td class="status">
                        @if(!empty($appointment->status) && $appointment->status == 'Pending' )
                            <button type="button" class="btn btn-primary update-status" data-item-id="{{$appointment->code}}">Pending</button>
                        @else
                            Done
                        @endif                    
                    </td>
                </tr>
                @endforeach 
            </tbody>
                
            </table>
        </div>
    </div>
</div>