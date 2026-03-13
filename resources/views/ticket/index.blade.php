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
		
		<!-- Блок фильтров -->
		<div class="row mb-4">
			<div class="col-12">
				<div class="card">
					<div class="card-header bg-light">
						<h5 class="mb-0">Фильтры</h5>
					</div>
					<div class="card-body">
						<form method="GET" action="{{ route('ticket.index') }}">
							<div class="row g-3">
								<div class="col-md-3">
									<label for="date_from" class="form-label">Дата создания от</label>
									<input type="date"
									       id="date_from"
									       name="date_from"
									       class="form-control"
									       value="{{ request('date_from') }}">
								</div>
								
								<div class="col-md-3">
									<label for="date_to" class="form-label">до</label>
									<input type="date"
									       id="date_to"
									       name="date_to"
									       class="form-control"
									       value="{{ request('date_to') }}">
								</div>
								
								<div class="col-md-3">
									<label for="status" class="form-label">Статус</label>
									<select id="status" name="status" class="form-select">
										<option value="">Все статусы</option>
										@foreach(array_keys(\App\Models\Ticket::getStatuses()) as $status)
											<option value="{{ $status }}"
													{{ request('status') == $status ? 'selected' : '' }}>
												{{ \App\Models\Ticket::getStatusLabel($status) }}
											</option>
										@endforeach
									</select>
								</div>
								
								<div class="col-md-3">
									<label for="customer_email" class="form-label">Email пользователя</label>
									<input type="email"
									       id="customer_email"
									       name="customer_email"
									       class="form-control"
									       placeholder="Введите email"
									       value="{{ request('customer_email') }}">
								</div>
								
								<div class="col-md-3">
									<label for="customer_phone" class="form-label">Телефон пользователя</label>
									<input type="text"
									       id="customer_phone"
									       name="customer_phone"
									       class="form-control"
									       placeholder="Введите телефон"
									       value="{{ request('customer_phone') }}">
								</div>
								
								<div class="col-md-3 d-flex gap-2 align-items-end">
									<button type="submit" class="btn btn-primary">Применить</button>
									<a href="{{ route('ticket.index') }}" class="btn btn-secondary">Сбросить</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
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
										<form action="{{ route('ticket.updateStatus', $ticket->id) }}" method="POST"
										      class="d-inline">
											@csrf
											<select name="status" class="form-select form-select-sm d-inline w-auto"
											        onchange="this.form.submit()">
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
