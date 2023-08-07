<link href="{{ asset('css/login.css') }}" rel="stylesheet">
<div class="container">
   <form method="POST" action="{{ route('logout') }}">
    @csrf
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Home') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        {{__('Welcome,')}}
                    {{ __('You are logged in !') }}
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                Logout
            </button>
        </div>
    </form>
</div>
