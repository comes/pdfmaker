@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 col-sm-12">
                <remote-data-table
                        apipath="/api/catalog"
                        vname="Kategorien"
                        type="catalog"
                        :blacklist="['updated_at','created_at','deleted_at','path','parent_id','options']"
                ></remote-data-table>
            </div>
        </div>
    </div>
@endsection
