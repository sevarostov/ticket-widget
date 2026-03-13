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
						
						<div class="mb-4">
							<strong>Email:</strong>
							<span>{{ $ticket->customer->email ?? 'Не указан' }}</span>
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
						
						<!-- Медиа‑файлы тикета -->
						@if($ticket->hasMedia('attachments'))
							<div class="mb-4">
								<h4>Прикреплённые файлы</h4>
								
								<div class="row">
									@foreach($ticket->getMedia('attachments') as $media)
										<div class="">
											<div class="card">
												<div class="card-body text-center">
													<div class="file-icon mb-2">
														@php
															$extension = pathinfo($media->file_name, PATHINFO_EXTENSION);
													$icon = \App\Models\Ticket::getSvgImageOfMediaFile($media);
														@endphp
														<img style = "width: 30px; height: 30px;"  src="/images/{{$icon}}.svg">
													</div>
													
													<h6 class="card-title mb-1">{{ $media->file_name }}</h6>
													
													<small class="text-muted">
														{{ round($media->size_in_bytes / 1024, 1) }} KB
													</small>
													
													<div class="mt-2">
														<a href="{{ $media->getFullUrl() }}"
														   class="btn btn-sm btn-outline-primary"
														   target="_blank"
														   download>
															<i class="bi bi-download"></i> Скачать
														</a>
													</div>
												</div>
											</div>
											@endforeach
										</div>
								</div>
								@else
									<div class="alert alert-info">
										К этому тикету не прикреплено файлов.
									</div>
								@endif
							
							</div>
					</div>
				</div>
			</div>
@endsection
