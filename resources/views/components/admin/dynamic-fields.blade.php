    <div class="mt-8 space-y-8">
        @foreach($dynamicFields as $name => $field)
            @if(($field['type'] ?? '') === 'title')
                <div class="pb-3 border-b border-slate-200 mt-12 first:mt-0">
                    <h3 class="text-[17px] font-bold text-slate-800 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-[#2271b1] rounded-full"></span>
                        {{ $field['label'] }}
                    </h3>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-4 items-start py-2 group">
                    <div class="pt-1.5">
                        <label for="{{ $name }}" class="text-[14px] font-bold text-slate-700 block mb-1 group-hover:text-[#2271b1] transition-colors">
                            {{ $field['label'] }}
                        </label>
                        @if(isset($field['desc']))
                            <p class="text-[11.5px] text-slate-400 leading-relaxed max-w-[240px] italic">{{ $field['desc'] }}</p>
                        @endif
                    </div>
                    
                    <div class="w-full max-w-[600px]">
                        @if($field['type'] === 'text' || $field['type'] === 'number')
                            <div class="flex items-center gap-2">
                                @if(str_contains($name, 'url'))
                                    <span class="text-slate-400 text-[13px] font-medium bg-slate-50 px-3 py-1.5 border border-slate-200 border-r-0 rounded-l -mr-2">{{ url('/') }}/</span>
                                @endif
                                <input type="{{ $field['type'] }}" name="{{ $name }}" id="{{ $name }}"
                                    value="{{ $settings[$name] ?? ($field['default'] ?? '') }}"
                                    placeholder="{{ $field['placeholder'] ?? '' }}"
                                    class="wp-input h-10 w-full shadow-sm rounded-md border-slate-300 focus:border-[#2271b1] focus:ring-1 focus:ring-[#2271b1]/10">
                            </div>

                        @elseif($field['type'] === 'textarea')
                            <textarea name="{{ $name }}" id="{{ $name }}" rows="5"
                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                class="wp-input w-full p-3 shadow-sm rounded-md border-slate-300 focus:border-[#2271b1] focus:ring-1 focus:ring-[#2271b1]/10 min-h-[120px]">{{ $settings[$name] ?? ($field['default'] ?? '') }}</textarea>

                        @elseif($field['type'] === 'select')
                            <div class="relative">
                                <select name="{{ $name }}" id="{{ $name }}" class="wp-input w-full h-10 px-3 pr-10 shadow-sm rounded-md border-slate-300 focus:border-[#2271b1] appearance-none bg-white">
                                    @foreach($field['options'] ?? [] as $val => $label)
                                        <option value="{{ $val }}" {{ ($settings[$name] ?? ($field['default'] ?? '')) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                    <i class="fa fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>

                        @elseif($field['type'] === 'checkbox')
                            <label class="flex items-center cursor-pointer group/check w-fit">
                                <div class="relative flex items-center justify-center">
                                    <input type="checkbox" name="{{ $name }}" id="{{ $name }}" value="1" 
                                        {{ ($settings[$name] ?? ($field['default'] ?? '0')) == '1' ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-slate-300 text-[#2271b1] focus:ring-[#2271b1]/20 transition-all cursor-pointer peer">
                                </div>
                                <span class="ml-3 text-[14px] font-medium text-slate-600 peer-checked:text-slate-900 transition-colors">{{ $field['checkbox_label'] ?? 'Enable this option' }}</span>
                            </label>

                        @elseif($field['type'] === 'image')
                            <div class="flex flex-col gap-4 p-4 bg-slate-50 border border-slate-200 rounded-lg">
                                @if(!empty($settings[$name]))
                                    <div class="relative group/img w-fit">
                                        <img src="{{ asset($settings[$name]) }}" class="max-w-[180px] h-auto border-2 border-white shadow-sm rounded-md">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center rounded-md">
                                            <i class="fa fa-image text-white text-xl"></i>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex items-center gap-3">
                                    <input type="file" name="{{ $name }}" id="{{ $name }}" class="text-[13px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-[12px] file:font-bold file:bg-[#2271b1] file:text-white hover:file:bg-[#1a5b8e] cursor-pointer">
                                </div>
                            </div>

                        @elseif($field['type'] === 'media')
                            <div class="flex items-center gap-5 p-4 bg-slate-50 border border-slate-200 rounded-lg">
                                <div id="media-preview-{{ $name }}" class="w-20 h-20 border-2 border-white shadow-sm rounded-md bg-white flex items-center justify-center overflow-hidden {{ empty($settings[$name]) ? 'hidden' : '' }}">
                                    @if(!empty($settings[$name]))
                                        <img src="{{ asset('storage/' . $settings[$name]) }}" class="max-w-full max-h-full object-contain">
                                    @endif
                                </div>
                                <div class="flex flex-col gap-2">
                                    <button type="button" class="wp-btn-secondary h-9 px-5 text-[12px] font-bold shadow-sm open-media-for-setting" data-target="{{ $name }}">
                                        <i class="fa fa-photo-video mr-2"></i> Select Media
                                    </button>
                                    <input type="hidden" name="{{ $name }}" id="input-{{ $name }}" value="{{ $settings[$name] ?? '' }}">
                                    @if(!empty($settings[$name]))
                                        <p class="text-[10px] text-slate-400 font-mono truncate max-w-[200px]">{{ $settings[$name] }}</p>
                                    @endif
                                </div>
                            </div>

                        @elseif($field['type'] === 'switcher')
                            <div class="flex items-center">
                                <div class="inline-flex bg-slate-100 rounded-lg p-1.5 border border-slate-200 shadow-inner">
                                    @foreach($field['options'] ?? [] as $val => $label)
                                        @php $isActive = ($settings[$name] ?? ($field['default'] ?? '')) == $val; @endphp
                                        <label class="cursor-pointer">
                                            <input type="radio" name="{{ $name }}" value="{{ $val }}" class="hidden peer" {{ $isActive ? 'checked' : '' }}>
                                            <span class="px-8 py-2 text-[13px] font-bold flex items-center justify-center rounded-md transition-all
                                                peer-checked:bg-[#2271b1] peer-checked:text-white peer-checked:shadow-md text-slate-500 hover:text-[#2271b1]">
                                                {{ $label }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>
