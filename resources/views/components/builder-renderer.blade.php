@php
    $layout = json_decode($post->content) ?: [];
@endphp

<div class="lazy-builder-content">
    @foreach($layout as $container)
        <section class="lazy-container" style="
            padding-top: {{ $container->settings->paddingTop ?? 60 }}px;
            padding-bottom: {{ $container->settings->paddingBottom ?? 60 }}px;
            background-color: {{ $container->settings->bgColor ?? 'transparent' }};
            @if(isset($container->settings->bgImage) && $container->settings->bgImage)
                background-image: url('{{ $container->settings->bgImage }}');
                background-size: {{ $container->settings->bgImgSize ?? 'cover' }};
                background-position: {{ $container->settings->bgImgPosition ?? 'center' }};
            @endif
        ">
            <div class="row flex flex-wrap mx-auto" style="
                max-width: {{ ($container->settings->contentWidth ?? 'site') === 'site' ? '1200px' : '100%' }};
                justify-content: {{ $container->settings->justify ?? 'flex-start' }};
                align-items: {{ $container->settings->rowAlign ?? 'center' }};
            ">
                @foreach($container->columns as $column)
                    <div class="column" style="
                        flex-basis: {{ $column->basis ?? (100 / count($container->columns)) }}%;
                        max-width: {{ $column->basis ?? (100 / count($container->columns)) }}%;
                        padding: {{ $column->settings->paddingTop ?? 10 }}px {{ $column->settings->paddingRight ?? 10 }}px {{ $column->settings->paddingBottom ?? 10 }}px {{ $column->settings->paddingLeft ?? 10 }}px;
                    ">
                        @foreach($column->children as $element)
                            @if($element->type === 'heading')
                                <{{ $element->settings->tag ?? 'h2' }} style="
                                    color: {{ $element->settings->color ?? '#1a1a1a' }};
                                    font-size: {{ $element->settings->fontSize ?? '32' }}px;
                                    text-align: {{ $element->settings->textAlign ?? 'left' }};
                                    font-weight: {{ $element->settings->fontWeight ?? '800' }};
                                ">
                                    {{ $element->settings->title ?? '' }}
                                </{{ $element->settings->tag ?? 'h2' }}>
                            @elseif($element->type === 'text')
                                <div style="
                                    color: {{ $element->settings->color ?? '#4b5563' }};
                                    font-size: {{ $element->settings->fontSize ?? '16' }}px;
                                    text-align: {{ $element->settings->textAlign ?? 'left' }};
                                    line-height: {{ $element->settings->lineHeight ?? '1.6' }};
                                ">
                                    {!! nl2br(e($element->settings->content ?? '')) !!}
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach
</div>
