@extends('layouts.master')
@section('title', 'Buy Airtime')

@section('content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h4 class="title nk-block-title text-center">Buy Airtime</h4>
                                <div class="nk-block-des text-center">
                                    <p>Fill in the form to buy Airtime</p>
                                </div>
                            </div>
                        </div>
                        <div class="row g-gs">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <div class="card card-bordered h-100">
                                    <div class="card-inner">
                                        
                                        <form action="{{ route('buy.airtime') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label class="form-label" for="full-name">Select Network</label>
                                                <div class="form-control-wrap">
                                                   <select class="form-control" name="name" required>
                                                       @foreach ($airtime as $air)
                                                            <option>{{ $air['name'] }}</option>
                                                       @endforeach
                                                       
                                                   </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="phone-no">Phone No</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="phone" id="phone-no" required>
                                                </div>
                                            </div>
                                           
                                            <div class="form-group">
                                                <label class="form-label" for="pay-amount">Amount</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="amount" id="pay-amount" required>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-lg btn-primary">Buy Airtime</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>
                    </div><!-- .nk-block -->
                   
                </div><!-- .components-preview -->
            </div>
        </div>
    </div>
</div>

@endsection