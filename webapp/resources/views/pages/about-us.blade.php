@extends('layouts.app')
@section('scripts')
    <script src="{{ asset('js/generic_search_bar.js') }}" defer> </script>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endsection
@section('content')
<div class="about-section">
    <h1>About Us</h1>
    <p>We're 4 guys who study at <a target="_blank" href="https://sigarra.up.pt/feup/pt/web_page.inicial">Faculdade de Engenharia da Universidade do Porto (FEUP).</a></p>
    <p>This is a project developed in the context of our Database and Web Applications Laboratory class.</p>
    <p>Below you have some information about each member and how to reach us.</p>
  </div>
  
  <div class="row">
      <div class="profile">
        <img src="{{asset('images/202004598.jpg')}}" alt="Afonso">
        <div class="container">
          <h2>Afonso Baldo</h2>
          <p class="title">Founder/Developer</p>
          <p><a href="mailto:up202004598@up.pt" style="color: black">up202004598@up.pt</a></p>
        </div>
      </div>
  
      <div class="profile">
        <img src="{{asset('images/202007227.jpg')}}" alt="João">
        <div class="container">
          <h2>João Reis</h2>
          <p class="title">Founder/Developer</p>
          <p><a href="mailto:up202007227@up.pt" style="color: black">up202007227@up.pt</a></p>
        </div>
      </div>
  
      <div class="profile">
        <img src="{{asset('images/202006322.jpg')}}" alt="Pedro">
        <div class="container">
          <h2>Pedro Gomes</h2>
          <p class="title">Founder/Developer</p>
          <p><a href="mailto:up202006322@up.pt" style="color: black">up202006322@up.pt</a></p>
        </div>
      </div>
      <div class="profile">
        <img src="{{asset('images/202008252.jpg')}}" alt="Rui">
        <div class="container">
          <h2>Rui Pires</h2>
          <p class="title">Founder/Developer</p>
          <p><a href="mailto:up202008252@up.pt" style="color: black">up202008252@up.pt</a></p>
        </div>
      </div>
  </div>
@endsection