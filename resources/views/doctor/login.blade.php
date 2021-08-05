@include('layouts.master')

<body>
    <div id="doctor_login">
        <div class="container" style="margin-top: 2rem;">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12"> 
                        <form id="doctor_login" class="form" action="{{ route('doctor.login') }}" method="post">
                
                        @csrf                            
                        <h3 class="text-center text-info">Doctor Login</h3>
                            @if(Session::get('fail'))
                                <div class="alert alert-danger">
                                    {{ Session::get('fail') }}
                                </div>
                            @elseif (count($errors) > 0)
                                <div class = "alert alert-danger">
                                    Please fill in the required Info
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="name" class="text-info">Name:</label><br>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="password" name="password" id="password" class="form-control" autocomplete="on">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-md">Submit </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>