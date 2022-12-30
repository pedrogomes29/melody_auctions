@extends('layouts.app')
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/generic_search_bar.js') }}" defer> </script>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endsection
@section('content')
<div class="contact-section">
    <h1>Contact Us</h1>
    <p>For logistic questions or if something is not working, please send us an email on <a href="mailto:melodyauctions@gmail.com" style="color:black">melodyauctions@gmail.com</a></p>
    <p>For any other personal matters, please take a look at the contacts of our founders in the <a href="{{ route('about-us') }}" style="color:black">About Us</a> page.</p>
  </div>
@endsection