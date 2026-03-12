@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="d-flex justify-content-between align-items-center mb-4">
			<h1>Список тикетов</h1>
		</div>
		
		@if(session('success'))
			<div class="alert alert-success">{{ session('success') }}</div>
		@endif
		
		@if(session('error'))
			<div class="alert alert-danger">{{ session('error') }}</div>
		@endif
		
		<div class="row">
			<div class="col-12">
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
						<tr>
							<th>ID</th>
							<th>Тема</th>
							<th>Пользователь</th>
							<th>Статус</th>
							<th>Действия</th>
						</tr>
						</thead>
						<tbody>
						@foreach($tickets as $ticket)
							<tr>
								<td>{{ $ticket->id }}</td>
								<td>{{ Str::limit($ticket->topic, 50) }}</td>
								<td>{{ $ticket->customer->name ?? '' }}</td>
								<td>
                <span class="badge
                    @switch($ticket->status)
                @case(\App\Models\Ticket::STATUS_NEW)
                    bg-primary
                @break
                @case(\App\Models\Ticket::STATUS_IN_PROGRESS)
            bg-warning
                @break
                @case(\App\Models\Ticket::STATUS_PROCESSED)
            bg-success
                @break
            @default
            bg-secondary
                @endswitch
            ">
                    {{ $ticket->status }}
                </span>
								</td>
								<td>
									<div class="btn-group" role="group">
										<a href="{{ route('ticket.show', $ticket->id) }}"
										   class="btn btn-sm btn-outline-primary">
											<i class="bi bi-eye"></i> Просмотр
										</a>
										<form action="{{ route('ticket.updateStatus', $ticket->id) }}" method="POST" class="d-inline">
											@csrf
											<select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
												<option value="{{ \App\Models\Ticket::STATUS_NEW }}"
														{{ $ticket->status === \App\Models\Ticket::STATUS_NEW ? 'selected' : '' }}>
													Открытый
												</option>
												<option value="{{ \App\Models\Ticket::STATUS_IN_PROGRESS }}"
														{{ $ticket->status === \App\Models\Ticket::STATUS_IN_PROGRESS ? 'selected' : '' }}>
													В работе
												</option>
												<option value="{{ \App\Models\Ticket::STATUS_PROCESSED }}"
														{{ $ticket->status === \App\Models\Ticket::STATUS_PROCESSED ? 'selected' : '' }}>
													Закрыт
												</option>
											</select>
										</form>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				
				<!-- Пагинация -->
				<div class="d-flex justify-content-center">
					{{ $tickets->links() }}
				</div>
			</div>
		</div>
	</div>
@endsection
