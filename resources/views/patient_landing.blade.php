@include('layouts.master')

<body>
    <div id="make_appointment">
        <div class="container" style="margin-top: 2rem;">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="make-appointment-form" class="form" action="" method="post">
                            @csrf
                            <h3 class="text-center text-info">Make Appointment</h3>
                            @if (count($errors) > 0)
                                <div class = "alert alert-danger">
                                    Please fill in the required Info
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="username" class="text-info">Patient name:</label><br>
                                <input type="text" name="name" id="username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="symbol" class="text-info">Symbol:</label><br>
                                <textarea name="symbol" id="symbol" cols="30" rows="4" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-md">Submit </button>
                            </div>
                        </form>
                    </div>
                    <br>
                    <div class="col-md-12">
                        <form id="search-form" class="form" action="{{route('search')}}" method="get">
                            @csrf
                            <div class="input-group mb-3"> <input type="text" name="code" class="form-control">
                                <div class="input-group-append"><button type="submit" class="btn btn-primary">Search</button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>