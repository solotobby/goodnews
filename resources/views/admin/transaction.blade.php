@extends('layouts.master')
@section('title', 'Transaction List')

@section('content')

<div class="nk-block nk-block-lg">

    <div class="nk-block-head">
        <div class="nk-block-head-content">
            <h4 class="nk-block-title">Total Transaction - NGN {{ number_format($transactions->sum('amount'),2) }} </h4>
        </div>
    </div>
    <div class="card card-bordered card-preview">
        <div class="card-inner">
            <table class="datatable-init-export nowrap table" data-export-title="Export">
                <thead>
                    <tr>
                        <th>Ref</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Transaction Type</th>
                        <th>Payment Type</th>
                        <th>Status</th>
                        <th>When Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $tx)
                    <?php 
                    if($tx->payment_type == 'credit')
                    {
                        $color = "bisque"; 
                        $text = "white";
                    }else{
                        $color = "black";
                        $text = "black";
                    }
                    ?>
                    <tr>
                        <td>{{ $tx->transaction_ref }}</td>
                        <td>{{ $tx->user->name }}</td>
                        <td>NGN {{ number_format($tx->amount,2) }}</td>
                        <td>{{ $tx->transaction_type }}</td>
                        <td>{{ $tx->payment_type }}</td>
                        <td>{{ $tx->status }}</td>
                        <td>{{ \Carbon\Carbon::parse($tx->created_at)->format('d-m-Y') }}</td>
                    </tr>
                    @endforeach
                    
                 </tbody>
            </table>
        </div>
    </div><!-- .card-preview -->
</div>

@endsection