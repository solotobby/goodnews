@extends('layouts.master')
@section('title', 'Queued Request')
@section('content')

<div class="nk-block nk-block-lg">
    @include('layouts.alert.error')
    <div class="nk-block-head">
        <div class="nk-block-head-content">
            <h4 class="nk-block-title">Queued Transaction - {{ $queues->count() }}</h4>
            {{-- <div class="nk-block-des">
                <p>To intialize datatable with export buttons, use <code class="code-class">.datatable-init-export</code> with <code>table</code> element. <br> <strong class="text-dark">Please Note</strong> By default export libraries is not added globally, so please include <code class="code-class">"js/libs/datatable-btns.js"</code> into your page to active export buttons.</p>
            </div> --}}
        </div>
    </div>
    <div class="card card-bordered card-preview">
        <div class="card-inner">
            <table class="datatable-init-export nowrap table" data-export-title="Export">
                <thead>
                    <tr>
                        <th>Ref</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>User</th>
                        <th>When Registered</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($queues as $user)
                    <tr>
                        <td>{{ $user->ref }}</td>
                        <td>{{ $user->status }}</td>
                        <td>{{ $user->amount }}</td>
                        <td>{{ $user->type }}</td>
                        <td>{{ $user->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</td>
                        <td>
                            {{-- <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm-{{ $user->id }}">Fund Wallet</a> --}}
                            <a href="{{ route('validate.queue', $user->id) }}" class="btn btn-secondary btn-sm">Validate Transaction</a>
                            {{-- <a href="{{ url('activate/user/'.$user->id) }}" class="btn btn-info btn-sm">Activate/Blacklist</a> --}}
                        </td>
                       
                    </tr>
                    @endforeach
                    
                 </tbody>
            </table>
        </div>
    </div><!-- .card-preview -->
</div> <!-- nk-block -->



@endsection