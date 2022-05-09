@extends('layouts.master')
@section('title', 'Buy SME Data')

@section('content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h4 class="title nk-block-title text-center">Buy SME Data</h4>
                                <div class="nk-block-des text-center">
                                    <p>Fill in the form to buy Airtime</p>
                                </div>
                            </div>
                        </div>
                        <div class="row g-gs">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                @include('layouts.alert.error')
                                <div class="card card-bordered h-100">
                                    <div class="card-inner">
                                        
                                        <form action="{{ route('buy.smedata') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label class="form-label">Select</label>
                                                <div class="form-control-wrap">
                                                   <select class="form-control" name="name" required>
                                                       @foreach ($smeData as $data)
                                                            <option value=" {{ $data->name }}:{{ $data->amount }} ">{{ $data->name }} GIG @ {{ $data->amount }} NGN</option>
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
                                                <button type="submit" class="btn btn-lg btn-primary">Buy SME Data</button>
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