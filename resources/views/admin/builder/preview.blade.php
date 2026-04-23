<x-cms-dashboard::layouts.admin>
<div class="min-h-screen bg-white">
    <div class="max-w-[1200px] mx-auto py-20 px-4">
        <h1 class="text-4xl font-bold mb-8">{{ $post->title }}</h1>
        <div class="prose max-w-none">
            @php
                $layout = json_decode($post->content, true);
            @endphp

            @if($layout && is_array($layout))
                <div class="space-y-10">
                    @foreach($layout as $container)
                        <div style="
                            padding-top: {{ $container['settings']['paddingTop'] ?? 0 }}px;
                            padding-bottom: {{ $container['settings']['paddingBottom'] ?? 0 }}px;
                            background: {{ $container['settings']['bgColor'] ?? 'transparent' }};
                             {{ ($container['settings']['bgType'] ?? '') === 'image' && ($container['settings']['bgImage'] ?? '') ? 'background-image: url('.$container['settings']['bgImage'].'); background-size: cover;' : '' }}
                        ">
                            <div class="flex flex-wrap gap-4" style="justify-content: {{ $container['settings']['justify'] ?? 'flex-start' }}">
                                @foreach($container['columns'] as $column)
                                    <div style="flex: 0 0 calc({{ $column['basis'] ?? 100 }}% - 20px);">
                                        <div class="p-4 border border-dashed border-gray-200 min-h-[100px]">
                                            @foreach($column['children'] as $child)
                                                @if($child['type'] === 'nested_row')
                                                    <div class="flex flex-wrap gap-4 mt-4">
                                                        @foreach($child['columns'] as $ncol)
                                                            <div style="flex: 0 0 calc({{ $ncol['basis'] ?? 100 }}% - 10px);" class="p-2 border border-orange-200 bg-orange-50/20">
                                                                <span class="text-[10px] text-orange-400 font-bold uppercase">Nested Col {{ $ncol['frac'] }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="bg-blue-50 p-4 rounded mb-2 text-center text-blue-500 font-bold uppercase text-[10px]">
                                                        Element: {{ $child['name'] ?? 'Undefined' }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-20 text-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 font-medium">No layout content found. Start building in the editor!</p>
                </div>
            @endif
        </div>
    </div>
</div>
</x-cms-dashboard::layouts.admin>
