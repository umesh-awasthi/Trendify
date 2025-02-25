@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Select Login Type') }}</div>

                <div class="card-body">
                    <form id="loginSelectionForm">
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="loginType" id="adminLogin" value="admin">
                                    <label class="form-check-label" for="adminLogin">
                                        Login as Admin
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="loginType" id="customerLogin" value="customer">
                                    <label class="form-check-label" for="customerLogin">
                                        Login as Customer
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="loginType" id="agentLogin" value="agent">
                                    <label class="form-check-label" for="agentLogin">
                                        Login as Agent
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Continue to Login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('loginSelectionForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const selectedLogin = document.querySelector('input[name="loginType"]:checked').value;
    
    switch(selectedLogin) {
        case 'admin':
            window.location.href = "{{ route('admin.login') }}";
            break;
        case 'customer':
            window.location.href = "{{ route('customer.login') }}";
            break;
        case 'agent':
            window.location.href = "{{ route('agent.login') }}";
            break;
    }
});
</script>
@endsection
