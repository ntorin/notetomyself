@extends('layouts.app')

@section('content')
<div class="container">
    {{Form::open(array('id' => 'dashboard', 'action' => 'TestController@saveUserData', 'method' => 'post', ))}}
    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-3">
                <h2>Notes</h2>
                <textarea name="notes" form="dashboard"></textarea>
            </div>
            <div class="col-xs-3">
                <h2>Websites</h2>
                <h3>click to open</h3>
                {{ App\Website::getUserWebsites(Auth::user()->id, Auth::user()->email) }}
                <input type="text" name="websites[]">
                <br/>
            </div>
            <div class="col-xs-3">
                <h2>Images</h2>
                <h3>click for full size</h3>
                {{Form::file('file')}}
                {{ App\Image::getUserImages(Auth::user()->id) }}
            </div>
            <div class="col-xs-3">
                <h2>TBD</h2>
                <textarea name="tbd" form="dashboard"></textarea>

            </div>
        </div>
        {{Form::hidden('userid', Auth::user()->id)}}
    </div>
        <div class="row">
            {{Form::submit('Save')}}
        </div>
    {{Form::close()}}
</div>
@endsection
