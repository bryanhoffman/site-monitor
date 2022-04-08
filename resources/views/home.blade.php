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
                                    <div>{{ $app->name }}</div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td class="table-text">No apps added yet</td></tr>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
