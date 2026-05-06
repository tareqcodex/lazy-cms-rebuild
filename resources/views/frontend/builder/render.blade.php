@if(!empty($layout))
    @foreach($layout as $item)
        @php $type = $item['type'] ?? ''; @endphp
        @if($type === 'container' || $type === 'row')
            @include('cms-dashboard::frontend.builder.container', ['container' => $item])
        @elseif($type === 'column')
            @include('cms-dashboard::frontend.builder.column', ['column' => $item])
        @elseif(!empty($type))
            @include('cms-dashboard::frontend.builder.elements.' . $type, ['element' => $item])
        @endif
    @endforeach
@endif
