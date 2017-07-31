@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>

            <remote-data-table apipath="/api/product" vname="Produkte" type="product"></remote-data-table>
        </div>
    </div>
</div>


@endsection
