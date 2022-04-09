@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <tr>
                        <td class="table-text">
                            <p><a href="/home">&laquo; Home</a>
                            <p>Name: {{ $app->app_name }}</p>
                            <p>Runtime: {{ $app->runtime }}</p>
                            <p>Latest Screenshot:</p>
                            @if ($app->latest_screenshot !== '')
                                <img style="max-width: 100%" src="/{{ $app->latest_screenshot }}" />
                            @else
                                No screenshot available
                            @endif
                        </td>
                    </tr>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
