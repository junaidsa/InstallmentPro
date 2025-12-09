<ul class="todo-list" style="height: 34vh">
    @forelse ($notify['pagination']['data'] ?? [] as $note)
        <a href="{{ __($note['notification']['url'] ?? '') }}" style="text-decoration: none; color: inherit;">
            <li class="ui-list">
                <h6 class="text">
                    {{ __($note['notification']['title'] ?? '', json_decode($note['notification']['params'] ?? '{}', true)) }}
                    <small class="badge badge-success">
                        <i class="far fa-clock"></i>
                        {{ $note['notification']['created_at'] ?? now() }}
                    </small>
                </h6>
                <p> {{ __($note['notification']['message'] ?? '', json_decode($note['notification']['params'] ?? '{}', true)) }}
                </p>
            </li>
        </a>
    @empty
        <li><span class="text-muted">{{ __('lang.NO_NOTIFICATION') }}</span></li>
    @endforelse
</ul>

@if (!empty($notify['pagination']['links']))
    <div class="d-flex justify-content-end mt-2">
        <nav>
            <ul class="pagination">
                @foreach ($notify['pagination']['links'] as $link)
                    <li class="page-item {{ $link['active'] ? 'active' : '' }} {{ $link['url'] ? '' : 'disabled' }}">
                        @if ($link['url'])
                            <a class="page-link"
                                href="{{ $link['url'] }}&module={{ request('module', \App\Models\Notification::ALL) }}">
                                {!! $link['label'] !!}
                            </a>
                        @else
                            <span class="page-link">{!! $link['label'] !!}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
@endif
