@include('layouts.master')

<script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#appointment-table').DataTable();
        $('#datetimepicker1').datetimepicker({
            format: 'DD/MM/YYYY HH:mm'        
          });
        // <--edit modal use -->
        $(document).on('click', ".edit-item", function() {
          $(this).addClass('edit-item-trigger-clicked'); 
          var options = {
            'backdrop': 'static'
          };
          $('#edit-modal').modal(options)
        })

        // on modal show
        $('#edit-modal').on('show.bs.modal', function() {
            var el = $(".edit-item-trigger-clicked"); 
            var row = el.closest(".data-row");

            // get the data
            var code = el.data('item-id');
            var name = row.children(".name").text();
            var symbol = row.children(".symbol").text();
            var status = row.children(".status").text();
            var doctor = row.children(".doctor").text();
            var description = row.children(".description").text();

            var today = new Date();
            today.setHours(0,0,0,0);
            $('#datetimepicker1').datetimepicker('date', moment(today, 'DD/MM/YYYY') );
            $('#modal-doctor').empty();
            $('#modal-doctor').append('<option value="0">-- Select doctor --</option>');

            // fill the data in the input fields
            $("#modal-code").text(code);
            $("#modal-name").text(name);
            $("#modal-symbol").text(symbol);
            $('#modal-status').text(status);
          
            $("#modal-date").val(description);
        })

        // on modal hide
        $('#edit-modal').on('hide.bs.modal', function() {
            $('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked');
            $("#edit-form").trigger("reset");
        });

        $(document).on('change', "#modal-department", function() {
            var department_id = $(this).val();
            
            $.ajax({
              type:'POST',
              url:'{{ route("nurse.dashbroad.retrieve_doctor_list") }}',
              data:{department_id:department_id, _token: '{{csrf_token()}}' },
              success:function(data) {
                var doctor_list = data.doctor_list;
                $('#modal-doctor').empty();
                var html_doctor_list = '<option value="0">-- Select doctor --</option>';
                for(var i=0; i< doctor_list.length; i++){
                  html_doctor_list = html_doctor_list+'<option value="'+doctor_list[i].id+'">'+doctor_list[i].name+'</option>';
                }
                $('#modal-doctor').append(html_doctor_list);
              }
            });
        });
        // <--edit modal use -->

    });

    function edit_modal_submit(){
        var doctor_id = $('#modal-doctor').val();
        var appointment_date = $("#datetimepicker1").data("datetimepicker").date();
        var locale_appointment_date = appointment_date.toLocaleString();
        
        var el = $(".edit-item-trigger-clicked"); 
        var row = el.closest(".data-row");
        var code = el.data('item-id');

        if(doctor_id == 0){
          alert('Please select special list doctor for appointment');
          return;
        }
        $.ajax({
              type:'POST',
              url:'{{ route("nurse.dashbroad.complete_appointment") }}',
              data:{code:code,doctor_id:doctor_id,appointment_date:locale_appointment_date, _token: '{{csrf_token()}}' },
              success:function(data) {
                //update doctor column data
                var html_string_doctor = data.doctor_name;
                html_string_doctor=html_string_doctor+"<br>"+ moment(appointment_date).format('DD/MM/YYYY HH:mm');
                row.children(".doctor").empty();
                row.children(".doctor").append(html_string_doctor);

                //update action column 
                //var html_string_action = '<button type="button" class="btn btn-primary view-item" data-item-id="'+code+'">View</button>';
                row.children(".action").empty();
                //row.children(".action").append(html_string_action);

                //update status column
                var html_string_status = '<button type="button" class="btn btn-primary update-status" data-item-id="'+code+'">Pending</button>';
                row.children(".status").empty();
                row.children(".status").append(html_string_status);

                //close modal
                $('#edit-modal').modal('hide');
                $('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked');
                $("#edit-form").trigger("reset");
              }
          });
    }

    $(document).on("click", ".update-status" , function() {
        var code = $(this).data('item-id');
        var selected_data = $('.update-status[data-item-id="'+code+'"]').parent('.status');
        $.ajax({
          type:'POST',
          url:'{{ route("nurse.dashbroad.update_status") }}',
          data:{code:code, _token: '{{csrf_token()}}'},
          success:function(data){
            selected_data.empty();
            selected_data.append('Done');
          }
        })
    });

</script>

<x-header data="nurse"/>
<div class="container" style="margin-top: 2rem;">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12">
            <table id="appointment-table" class="table table-bordered mb-none">
            <thead>
              <tr>
                <th>Code</th>
                <th>Patient name</th>
                <th>Symbol</th>
                <th>Status</th>
                <th>Doctor name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($appointment_list as $appointment)
                <tr class="data-row">
                    <td class="code">{{$appointment->code}}</td>
                    <td class="name">{{$appointment->name}}</td>
                    <td class="symbol">{{$appointment->symbol}}</td>
                    <td class="status">
                    @if(empty($appointment->doctor_name) && $appointment->status == 'Pending')
                        Pending
                    @elseif(!empty($appointment->doctor_name) && $appointment->status == 'Pending' )
                        <button type="button" class="btn btn-primary update-status" data-item-id="{{$appointment->code}}">Pending</button>
                    @else
                        Done
                    @endif
                    </td>
                    <td class="doctor">                        
                      @if(empty($appointment->doctor_name))
                        N/A
                      @else
                        {{$appointment->doctor_name}} <br>{{date('d-m-Y H:i', strtotime($appointment->date))}}
                      @endif
                    </td>

                    <td class="action">	
                        @if(empty($appointment->doctor_name))
                        <button type="button" class="btn btn-success edit-item" data-item-id="{{$appointment->code}}">edit</button>
                        @endif

                    </td>
                </tr>
                @endforeach 
            </tbody>
                
            </table>
        </div>
    </div>
</div>

<!-- Attachment Modal -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="edit-modal-label">Complete Appointment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="attachment-body-content">
        <form id="edit-form" class="form-horizontal" method="POST" action="">
          <div class="card text-black bg-light mb-0">
            <div class="card-header">
              <h2 class="m-0">Edit</h2>
            </div>
            <div class="card-body">
              <div class="form-group row">
                <label class="col-md-3 control-label" for="modal-code">Code: </label>
                <p id="modal-code" class="col-md-9"></p>
              </div>
              <div class="form-group row">
                <label class="col-md-3 control-label" for="modal-name">Patient Name:</label>
                <p id="modal-name" class="col-md-8"></p>
              </div>
              <div class="form-group row">
                <label class="col-md-3 control-label" for="modal-symbol">Patient Symbol:</label>
                <p id="modal-symbol" class="col-md-8"></p>
              </div>
              <div class="form-group row">
                <label class="col-md-3 control-label" for="modal-status">Patient status:</label>
                <p id="modal-status" class="col-md-8"></p>
              </div>
              <div class="form-group">
                <label class="col-form-label" for="modal-department">Select Department:</label>
                <select class="form-control" id="modal-department" name="department" required>
                    <option value="0">-- Select department --</option>
                    @foreach ($special_list_department as $department)
                        <option value="{{$department->id}}">{{$department->department_name}}</option>
                    @endforeach
                </select>              
              </div>
              <div class="form-group">
                <label class="col-form-label" for="modal-doctor">Select doctor:</label>
                <select class="form-control" id="modal-doctor" name="doctor" required>
                  <option value="0">-- Select doctor --</option>
                </select>           
              </div>
              <div class="form-group">
                <label class="col-form-label">Appointment date</label>
                <input type="text" id="datetimepicker1" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#datetimepicker1" />        
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="edit-modal-submit" onclick="edit_modal_submit()">Done</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- /Attachment Modal -->