@extends('layouts.master')
@section('title', 'User List')
@section('content')

<div class="nk-block nk-block-lg">
    @include('layouts.alert.error')
    <div class="nk-block-head">
        <div class="nk-block-head-content">
            <h4 class="nk-block-title">User List - {{ $users->count() }}</h4>
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
                        <th>Status</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Balance</th>
                        <th>When Registered</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->status }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>NGN {{ number_format($user->wallet->balance,2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm-{{ $user->id }}">Fund Wallet</a>
                            <a href="{{ route('user.transaction', $user->id) }}" class="btn btn-secondary btn-sm">View Transaction</a>
                            <a href="{{ url('activate/user/'.$user->id) }}" class="btn btn-info btn-sm">Activate/Blacklist</a>
                        </td>
                       
                    </tr>
                    @endforeach
                    
                 </tbody>
            </table>
        </div>
    </div><!-- .card-preview -->
</div> <!-- nk-block -->


 <!-- Modal Form -->
 <div class="modal fade" id="modalForm-{{ $user->id }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fund {{ $user->name }}</h5>
                <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form action="{{ route('fund.wallet') }}" class="form-validate is-alter" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="pay-amount">Amount</label>
                        <div class="form-control-wrap">
                            <input type="number" name="amount" class="form-control" id="pay-amount" min="200">
                        </div>
                    </div>
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary">Fund Wallet</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <span class="sub-text">Modal Footer Text</span>
            </div>
        </div>
    </div>
</div>
@endsection