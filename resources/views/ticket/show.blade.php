@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="d-flex justify-content-between align-items-center mb-4">
			<h1>Просмотр тикета #{{ $ticket->id }}</h1>
			<a href="{{ route('ticket.index') }}" class="btn btn-secondary">
				<i class="bi bi-arrow-left"></i> Назад к списку
			</a>
		</div>
		
		@if(session('success'))
			<div class="alert alert-success">{{ session('success') }}</div>
		@endif
		
		@if(session('error'))
			<div class="alert alert-danger">{{ session('error') }}</div>
		@endif
		
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<!-- Основная информация -->
						<div class="mb-4">
							<h3>{{ $ticket->topic }}</h3>
							<p class="text-muted">
								@if($ticket->created_at)
									Создан: {{ $ticket->created_at->format('d.m.Y H:i') }}
								@endif
								
								@if($ticket->date_responded_at)
									| Ответ дан: {{ $ticket->date_responded_at->format('d.m.Y H:i') }}
								@endif
							</p>
						</div>
						
						<!-- Статус с цветовой индикацией -->
						<div class="mb-4">
							<strong>Статус:</strong>
							<span class="badge
                @switch($ticket->status)
                    @case(\App\Models\Ticket::STATUS_NEW)
                bg-primary
            @break
            @case(\App\Models\Ticket::STATUS_IN_PROGRESS)
                bg-warning text-dark
            @break
            @case(\App\Models\Ticket::STATUS_PROCESSED)
                bg-success
            @break
            @default
                bg-secondary
            @endswitch
        ">
            {{ \App\Models\Ticket::getStatusLabel($ticket->status ?? 'new') }}
        </span>
						</div>
						
						<!-- Информация о пользователе -->
						<div class="mb-4">
							<strong>Пользователь:</strong>
							<span>{{ $ticket->customer->name ?? 'Не указан' }}</span>
						</div>
						
						<!-- Текст тикета -->
						<div class="mb-4">
							<strong>Описание:</strong>
							<div class="border p-3 bg-light rounded">
								{{ $ticket->text }}
							</div>
						</div>
						
						<!-- Действия -->
						<div class="mt-4 d-flex gap-2">
							
							<!-- Форма изменения статуса -->
							<form action="{{ route('ticket.updateStatus', ['id'=>$ticket->id]) }}" method="POST">
								@csrf
								<select name="status" class="form-select" onchange="this.form.submit()">
									<option value="{{ \App\Models\Ticket::STATUS_NEW }}"
											{{ $ticket->status === \App\Models\Ticket::STATUS_NEW ? 'selected' : '' }}>
										Новый
									</option>
									<option value="{{ \App\Models\Ticket::STATUS_IN_PROGRESS }}"
											{{ $ticket->status === \App\Models\Ticket::STATUS_IN_PROGRESS ? 'selected' : '' }}>
										В работе
									</option>
									<option value="{{ \App\Models\Ticket::STATUS_PROCESSED }}"
											{{ $ticket->status === \App\Models\Ticket::STATUS_PROCESSED ? 'selected' : '' }}>
										Обработан
									</option>
								</select>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection
