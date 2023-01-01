@extends('layouts.app')

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/generic_search_bar.js') }}" defer> </script>
    <script type="text/javascript" src="{{ asset('js/reports.js') }}" defer> </script>

@endsection

@section('styles')
  <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection
@section('content')
<section class="adminInfo">
    <header class="m-4">
      <h1 class="ms-1">
          &#64{{ $admin->username }}
      </h1>
      @if(Auth::guard('admin')->user())
            <form id="logout"action="{{ route('adminLogout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>         
            </form>
        @endif
    </header>
</section>

<section class="reports">
    <nav class="m-4">
        <h1 class="ms-1">Reports</h1>
        <div class="d-flex flex-row justify-content-around align-items-center">
            <button  class="state btn-lg m-5 btn btn-primary btn-light">Open</button>
            <button  class="state btn-lg m-5 btn btn-primary btn-light">Closed</button>
        </div>
    </nav>
    @foreach ($reports as $report)
        <div class="card report {{$report->state}}">
            <div class="card-header">
                <h5> Reported on: <a href="../user/{{$report->reported}}">{{$report->reported}}</a> 
                     Reported by: <a href="../user/{{$report->reporter}}">{{$report->reporter}}</a>
                     Reported at: {{$report->reportsdate}}</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title">Reported reason:</h5> <p class="card-text">{{$report->reportstext}}</p>
                @if ($report->state == 'open')
                    <h5>Reported state: </h5> <p class="reportOpen">{{$report->state}}</p>
                    <form class="closeReportForm" action="{{ route("closeReport",['admin_username' => $admin->username]) }}" method="Post">
                        @csrf
                        <input type="hidden" name="reportId" value="{{$report->id}}">
                        <button type="submit" class="btn btn-danger closeReport">Close</button>
                    </form>
                @else
                <h5>Reported state: </h5> <p class="reportClosed">{{$report->state}}</p>
                @endif
            </div>
        </div>
    @endforeach
</section>

<section id="categories" class="d-flex flex-column">
    <h1 class="ms-1">Categories</h1>
    @foreach($categories as $category)
        <div class="container my-3">
            <div class="text-center">
                <a class="category btn btn-light" href="{{ route('category.edit',['id'=>$category->id]) }}" role="button">{{$category->name}}</a>
            </div>
        </div>
    @endforeach

    <div class="container my-3">
        <div class="text-center">
            <a class="btn btn-primary" href="{{ route('category.create')}}" role="button">Add a new category</a>
        </div>
    </div>
</section>

@endsection