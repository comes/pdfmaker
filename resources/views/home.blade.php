@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 col-sm-12">
            <router-view></router-view>
        </div>
    </div>
</div>
@endsection
