@props([
    'headers' => [],
    'empty' => false,
    'emptyMessage' => '',
])

<div {{ $attributes->merge(['class' => 'spotlight-card rounded-4 overflow-hidden border border-subtle']) }}>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            @if(count($headers) > 0 || isset($thead))
                <thead>
                    @if(isset($thead))
                        {{ $thead }}
                    @else
                        <tr>
                            @foreach($headers as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    @endif
                </thead>
            @endif

            <tbody>
                @if($empty)
                    <tr>
                        <td colspan="{{ count($headers) > 0 ? count($headers) : 10 }}" class="text-center py-5">
                            <x-ui.empty-state
                                icon="fas fa-inbox"
                                :title="$emptyMessage ?: __('No records found')" />
                        </td>
                    </tr>
                @else
                    {{ $slot }}
                @endif
            </tbody>
        </table>
    </div>

    @if(isset($pagination))
        <div class="p-3 border-top border-subtle d-flex justify-content-between align-items-center">
            {{ $pagination }}
        </div>
    @endif
</div>
