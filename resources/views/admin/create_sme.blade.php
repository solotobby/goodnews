

@extends('layouts.master')
@section('title', 'Create SME Data')

@section('content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h4 class="title nk-block-title text-center">Create SME Data</h4>
                                <div class="nk-block-des text-center">
                                    <p>Fill in the form to create SME Data</p>
                                </div>
                            </div>
                        </div>
                        <div class="row g-gs">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                @include('layouts.alert.error')
                                <div class="card card-bordered h-100">
                                    <div class="card-inner">
                                        
                                        <form action="{{ route('store.smedata') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label class="form-label" for="phone-no">Number of GIG e.g 1, or 2</label>
                                                <div class="form-control-wrap">
                                                    <input type="numeric" class="form-control" name="name" id="phone-no" required>
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
                        
                        <div class="row g-gs mt-3">

                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <table class="table table-striped">
                                    <thead>
                                      <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">GIG</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Status</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($smeData as $data)
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->amount }}</td>
                                            <td>{{ $data->status == '1' ? 'active' : 'inactive' }}</td>
                                          </tr>
                                        @endforeach
                                      
                                    </tbody>
                                  </table>
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