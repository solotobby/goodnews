@extends('layouts.master')
@section('title', 'Welcome to Dashboard')

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">{{auth()->user()->name}}</h3>
                        <div class="nk-block-des text-soft">
                            <p>Welcome to your Dashboard</p>
                        </div>
                    </div><!-- .nk-block-head-content -->
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li><a href="#" class="btn btn-white btn-dim btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTopUpForm"><em class="icon ni ni-download-cloud"></em><span>Top Up</span></a></li>
                                    {{-- <li><a href="#" class="btn btn-white btn-dim btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalWithrawForm"><em class="icon ni ni-reports"></em><span>Withdraw</span></a></li> --}}
                                </ul>
                            </div><!-- .toggle-expand-content -->
                        </div><!-- .toggle-wrap -->
                    </div><!-- .nk-block-head-content -->
                </div><!-- .nk-block-between -->
            </div><!-- .nk-block-head -->
                @include('layouts.alert.error')
            <div class="nk-block">
                <div class="row g-gs">
                    <div class="col-md-6">
                        <div class="card card-bordered card-full">
                            <div class="card-inner">
                                <div class="card-title-group align-start mb-0">
                                    <div class="card-title">
                                        <h6 class="subtitle">Wallet Balance</h6>
                                    </div>
                                    <div class="card-tools">
                                        <em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" title="Total Deposit"></em>
                                    </div>
                                </div>
                                <div class="card-amount">
                                    <span class="amount"> {{  number_format($wallet->balance,2) }} <span class="currency currency-usd">NGN</span>
                                    </span>
                                    {{-- <span class="change up text-danger"><em class="icon ni ni-arrow-long-up"></em>1.93%</span> --}}
                                </div>
                                {{-- <div class="invest-data">
                                    <div class="invest-data-amount g-2">
                                        <div class="invest-data-history">
                                            <div class="title">This Month</div>
                                            <div class="amount">{{ number_format($monthly->where('transaction_type', 'top-up')->sum('amount'),2) }} <span class="currency currency-usd">NGN</span></div>
                                        </div>
                                        <div class="invest-data-history">
                                            <div class="title">Today</div>
                                            <div class="amount">{{ number_format($daily->where('transaction_type', 'top-up')->sum('amount'),2) }} <span class="currency currency-usd">NGN</span></div>
                                        </div>
                                    </div>
                                    <div class="invest-data-ck">
                                        <canvas class="iv-data-chart" id="totalDeposit"></canvas>
                                    </div>
                                </div> --}}
                            </div>
                        </div><!-- .card -->
                    </div><!-- .col -->
                    {{-- <div class="col-md-4">
                        <div class="card card-bordered card-full">
                            <div class="card-inner">
                                <div class="card-title-group align-start mb-0">
                                    <div class="card-title">
                                        <h6 class="subtitle">Total Withdraw</h6>
                                    </div>
                                    <div class="card-tools">
                                        <em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" title="Total Withdraw"></em>
                                    </div>
                                </div>
                                <div class="card-amount">
                                    <span class="amount"> 49,595.34 <span class="currency currency-usd">USD</span>
                                    </span>
                                </div>
                                <div class="invest-data">
                                    <div class="invest-data-amount g-2">
                                        <div class="invest-data-history">
                                            <div class="title">This Month</div>
                                            <div class="amount">2,940.59 <span class="currency currency-usd">USD</span></div>
                                        </div>
                                        <div class="invest-data-history">
                                            <div class="title">This Week</div>
                                            <div class="amount">1,259.28 <span class="currency currency-usd">USD</span></div>
                                        </div>
                                    </div>
                                    <div class="invest-data-ck">
                                        <canvas class="iv-data-chart" id="totalWithdraw"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .card -->
                    </div><!-- .col --> --}}
                    <div class="col-md-6">
                        <div class="card card-bordered  card-full">
                            <div class="card-inner">
                                <div class="card-title-group align-start mb-0">
                                    <div class="card-title">
                                        <h6 class="subtitle">Total Transaction</h6>
                                    </div>
                                    <div class="card-tools">
                                        <em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" title="Total Transaction"></em>
                                    </div>
                                </div>
                                <div class="card-amount">
                                    <span class="amount"> {{ number_format($transactions->sum('amount'),2) }} <span class="currency currency-usd">NGN</span>
                                    </span>
                                </div>
                                {{-- <div class="invest-data">
                                    <div class="invest-data-amount g-2">
                                        <div class="invest-data-history">
                                            <div class="title">This Month</div>
                                            <div class="amount">{{ number_format($monthly->sum('amount'),2) }} <span class="currency currency-usd">NGN</span></div>
                                        </div>
                                        <div class="invest-data-history">
                                            <div class="title">TODAY</div>
                                            <div class="amount">{{ number_format($daily->sum('amount'),2) }} <span class="currency currency-usd">NGN</span></div>
                                        </div>
                                    </div>
                                    <div class="invest-data-ck">
                                        <canvas class="iv-data-chart" id="totalBalance"></canvas>
                                    </div>
                                </div> --}}
                            </div>
                        </div><!-- .card -->
                    </div><!-- .col -->
                   
                    <div class="col-xl-12 col-xxl-8">
                        <div class="card card-bordered card-full">
                            <div class="card-inner border-bottom">
                                <div class="card-title-group">
                                    <div class="card-title">
                                        <h6 class="title">Recent Transactions</h6>
                                    </div>
                                    <div class="card-tools">
                                        <a href="#" class="link">View All</a>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-tb-list">
                                <div class="nk-tb-item nk-tb-head">
                                    <div class="nk-tb-col"><span>Transaction Ref.</span></div>
                                    <div class="nk-tb-col tb-col-sm"><span>Transction Type</span></div>
                                    <div class="nk-tb-col tb-col-lg"><span>Date</span></div>
                                    <div class="nk-tb-col"><span>Amount</span></div>
                                    <div class="nk-tb-col tb-col-sm"><span>Status</span></div>
                                    {{-- <div class="nk-tb-col"><span>Currency</span></div> --}}
                                </div>
                                @foreach ($transactions->take('10') as $transaction)
                                <div class="nk-tb-item">
                                    <div class="nk-tb-col">
                                        <div class="align-center">
                                            
                                            <span class="tb-sub ms-2">{{ $transaction->transaction_ref }}</span>
                                        </div>
                                    </div>
                                    <div class="nk-tb-col tb-col-sm">
                                        <div class="user-card">
                                            <div class="user-name">
                                                <span class="tb-lead">{{ $transaction->transaction_type }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-tb-col tb-col-lg">
                                        <span class="tb-sub">{{ $transaction->created_at }}</span>
                                    </div>
                                    <div class="nk-tb-col">
                                        <span class="tb-sub tb-amount">{{ number_format($transaction->amount,2) }} {{ $transaction->currency }}</span>
                                    </div>
                                    <div class="nk-tb-col tb-col-sm">
                                        <span class="tb-sub">{{ $transaction->status }}</span>
                                    </div>
                                    {{-- <div class="nk-tb-col nk-tb-col-action">
                                        <div class="dropdown">
                                            <a class="text-soft dropdown-toggle btn btn-sm btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-chevron-right"></em></a>
                                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-xs">
                                                <ul class="link-list-plain">
                                                    <li><a href="#">View</a></li>
                                                    <li><a href="#">Invoice</a></li>
                                                    <li><a href="#">Print</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                                @endforeach
                                
                                
                            </div>
                        </div><!-- .card -->
                    </div><!-- .col -->
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal Form -->
<div class="modal fade" id="modalTopUpForm">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wallet Top Up</h5>
                <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form method="POST" action="https://checkout.flutterwave.com/v3/hosted/pay" class="form-validate is-alter">
                {{-- <form method="POST" action="{{ route('topup') }}" class="form-validate is-alter"> --}}
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="pay-amount">Amount</label>
                        <div class="form-control-wrap">
                            <input type="number" name="initial_amount" min="200" class="form-control" id="searchTxt" required>
                        </div>
                        <div id='searchValue' style="color: chocolate"></div>     
                    </div>

                    <input type="hidden" name="public_key" value="FLWPUBK-372f8fe83c257dc0359f9f0b968540d7-X" />
                    <input type="hidden" name="customer[email]" value="{{ auth()->user()->email }}" />
                    <input type="hidden" name="customer[name]" value="{{ auth()->user()->name }}" />
                    <input type="hidden" name="tx_ref" value="{{  \Str::random(10) }}" />
                    <input type="hidden" name="amount" value="" id='sendValue' />
                    <input type="hidden" name="currency" value="NGN" />
                    <input type="hidden" name="meta[token]" value="54" />
                    <input type="hidden" name="redirect_url" value="{{ url('transaction') }}" />

                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary">Pay</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <span class="sub-text">Wallet TopUp</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalWithrawForm">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdraw/Bank Information</h5>
                <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                @if($bankInformation == null)
                <form method="POST" action="{{ route('resolve.account') }}" class="form-validate is-alter">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="pay-amount">Bank Name</label>
                        <div class="form-control-wrap">
                           <select name="bank_code" class="form-control" name="bank_code" id="bankInfo" required>
                                @foreach ($bankList as $bank)
                                    <option value="{{ $bank['code'] }}">{{ $bank['name'] }}</option>
                                @endforeach
                           </select>
                        </div>
                        {{-- <div id='searchValue' style="color: chocolate"></div>      --}}
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="pay-amount">Account Number</label>
                        <div class="form-control-wrap">
                            <input type="number" name="account_number" id="txtAccountNum" class="form-control" placeholder="Account Number"  required>
                        </div>
                        {{-- <div id='searchValue' style="color: chocolate"></div>      --}}
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary">Save</button>
                    </div>
                </form>
                @else

                <h4>Withdraw Fund</h4>
                <form method="POST" action="{{ route('withdraw') }}" class="form-validate is-alter">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="pay-amount">Amount</label>
                        <div class="form-control-wrap">
                            <input type="number" name="amount" class="form-control" placeholder="Amount" min="100"  required>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary">Withdraw</button>
                    </div>


                </form>

                @endif

            </div>
            <div class="modal-footer bg-light">
                <span class="sub-text">Wallet Withdrawal/Bank Information</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    window.onkeyup = keyup;

    //creates a global Javascript variable
    var inputTextValue;

    function keyup(e) {
  //setting your input text to the global Javascript Variable for every key press
    inputTextValue = e.target.value;
    let y = inputTextValue;
    var percentToGet = 1.5;
    var percent = (percentToGet / 100) * inputTextValue;
    var amount = Math.round(parseInt(inputTextValue) + parseInt(percent), 2);
    // var amount = parseInt(inputTextValue) + parseInt(percent);

    document.getElementById('sendValue').value = amount;
    
    $('#searchValue').text("Amount to be Charged: " + amount);
  //listens for you to press the ENTER key, at which point your web address will change to the one you have input in the search box
//   if (e.keyCode == 13) {
//     window.location = "https://duckduckgo.com/?q=" + inputTextValue;
//   }
    }

    function keyup(e){
        var accountNum = document.getElementById("txtAccountNum").value;
        var bankInfo = document.getElementById("bankInfo").value; //e.target.value;

        // var $form = $(this);
        var values = $(this).serialize();
        if(accountNum.length === 10){

            // $.ajax({
            //     url: "/resolve/"+bankInfo+"/"+accountNum,
            //     type: "get",
            //     // data: values ,
            //     success: function (response) {
            //         console.log(response);
            //         );
            //     },
            //     error: function(jqXHR, textStatus, errorThrown) {
            //     console.log(textStatus, errorThrown);
            //     }
            // });
            
           //alert(accountNum);

           request = $.ajax({
                url: "/resolve/"+bankInfo+"/"+accountNum,
                type: "get",
                //data: serializedData
            });

            request.done(function (response, textStatus, jqXHR){
                //Log a message to the console
                //alert(response);

                console.log(textStatus);
            });
        }
    }

    

</script>

@endsection