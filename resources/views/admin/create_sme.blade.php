

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
                                            {{-- <div class="form-group">
                                                <label class="form-label" for="phone-no">Name</label>
                                                <div class="form-control-wrap">
                                                    <input type="numeric" class="form-control" name="name" id="phone-no" required>
                                                </div>
                                            </div> --}}
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
                                                <button type="submit" class="btn btn-lg btn-primary">Create</button>
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
                                        <th scope="col">Action</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($smeData as $data)
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->amount }}</td>
                                            <td>{{ $data->status == '1' ? 'active' : 'inactive' }}</td>
                                            {{-- <td><a href="" class="btn btn-info" >Edit</a></td> --}}
                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm-{{ $data->id }}">Edit</a>
                                            </td>
                                          </tr>



                                           <!-- Modal Form -->
                                        <div class="modal fade" id="modalForm-{{ $data->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Fund {{ $data->name }}</h5>
                                                        <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                            <em class="icon ni ni-cross"></em>
                                                        </a>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('edit.smedata') }}" class="form-validate is-alter" method="POST">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label class="form-label" for="pay-amount">Gig</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" name="name" class="form-control" value="{{ $data->name }}" id="pay-amount" min="200">
                                                                </div>
                                                                <label class="form-label" for="pay-amount">Amount</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="number" name="amount" class="form-control" value="{{ $data->amount }}" id="pay-amount" min="200">
                                                                </div>
                                                                <br>
                                                                <p>Current Status: <span style="color: brown" >{{ $data->status == '1' ? 'active' : 'inactive' }}</span></p>
                                                               
                                                                <label class="form-label" for="pay-amount">Status</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-control" name="status" required>
                                                                        <option value="1">Active</option>
                                                                        <option value="0">Deactivate</option>
                                                                    </select>
            
                                                                    <input type="hidden" name="id" class="form-control" value="{{ $data->id }}" id="pay-amount" min="200">
                                                                </div>
                                                            </div>
                                                            {{-- <input type="hidden" name="user_id" value="{{ $user->id }}"> --}}
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-lg btn-primary">Edit </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <span class="sub-text">Modal Footer Text</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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