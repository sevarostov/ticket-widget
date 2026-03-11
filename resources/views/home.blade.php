@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">{{ __('Dashboard') }}</div>
					
					<div class="card-body">
						@if (session('status'))
							<div class="alert alert-success" role="alert">
								{{ session('status') }}
							</div>
						@endif
      
						<p>
							<a href="{{ route('ticket.index') }}" class="btn btn-outline-secondary">
								{{ __('Manage Tickets') }}
							</a>
						</p>
					
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
