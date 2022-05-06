@extends('layouts.master')
@section('title', 'Select Network')

@section('content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h4 class="title nk-block-title text-center">Select Network</h4>
                                <div class="nk-block-des text-center">
                                    <p>Please Select a network</p>
                                </div>
                            </div>
                        </div>
                        <div class="row g-gs">
                            @foreach ($billers as $biller)
                            <div class="col-lg-6">
                                <div class="card card-bordered">
                                    <div class="card-inner">
                                        <div class="team">
                                            
                                            <div class="user-card user-card-s2">
                                                <div class="user-avatar lg bg-primary">
                                                    <span>{{ $biller['name'] }}</span>
                                                    <div class="status dot dot-lg dot-success"></div>
                                                </div>
                                                <div class="user-info">
                                                    <h6>{{ $biller['type'] }}</h6>
                                                    {{-- <span class="sub-text">UI/UX Designer</span> --}}
                                                </div>
                                            </div>
                                            
                                            <div class="team-view">
                                                <a href="{{ url('data/'.$biller['type'].'/'.$biller['name']) }}" class="btn btn-block btn-dim btn-primary"><span>Select Network</span></a>
                                            </div>
                                        </div><!-- .team -->
                                    </div><!-- .card-inner -->
                                </div><!-- .card -->
                                
                            </div>
                            @endforeach
                            
                           
                            
                        </div>
                    </div><!-- .nk-block -->
                   
                </div><!-- .components-preview -->
            </div>
        </div>
    </div>
</div>
@endsection