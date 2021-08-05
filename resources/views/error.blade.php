@include('layouts.master')
  
<div class="container" style="display: flex; justify-content: center; align-items: center; height: 100vh">
  <div class="col-md-5 align-item-center">
        <h1>{{$error_msg}}</h1>
        <br>
        <a class="btn btn-primary" href="{{route($back_to)}}">Back to previous page</a>
  </div>
</div>

