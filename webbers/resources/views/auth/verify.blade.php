@extends('layouts.app')
@section('content')
<div class="container" style="position: relative;top: 80px">
    <div class="row justify-content-center">
        <div class="col-md-6 border-0" style="margin-top: 30px;">
            <div class="card pl-3 pt-2 pr-3  pb-3">
                <div class="text-dark" style="font-size: 27px">{{ __('Account Email Verification') }}</div>
                
                <div class="card-body border-0">
                    @if (session('resent'))
                    <div class="alert alert-light" role="alert" style="border-left: 3px solid green">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                    @endif
                    
                    {{ __('Hi '.Auth::user()->name.',')}}<br>
                    {{ __('Before proceeding, you must verify your account email. Please check your email inbox for a verification link.') }}
                    
                    <div class="mt-2">
                        {{ __('If you\'ve check your email inbox and did not receive the email link, request for a new link by clicking the button below.') }}
                        <div class="text-center mt-2">
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm align-baseline">{{ __('Resend email link') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>


@endsection