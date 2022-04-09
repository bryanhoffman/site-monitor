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
                    @if (!$apps->isEmpty())
                        @foreach ($apps as $app)
                            <tr>
                                <td class="table-text">
                                    <div><a href="/app/{{ $app->id }}">{{ $app->app_name }}</a> | Status: @if ($app->status==1) <span style="color:green">Everything Looks Good!</span> @elseif ($app->status==0) <span style="background-color: red">Needs Attention!</span> @else Unknown  @endif </div>
                                    @php $domains = json_decode($app->domains) @endphp
                                    Domains: @foreach ($domains as $domain)
                                        {{ $domain }}
                                            @endforeach
                                </td>
                            </tr><br/><hr>
                            @endforeach
                            <a href="/connect">Refresh List</a>&nbsp;|
                            <a href="/status">Perform Status Check</a>

                    @else
                        <tr><td class="table-text">No apps added yet</td></tr><br/>
                        <a href="/connect">Connect to ServerPilot</a>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
