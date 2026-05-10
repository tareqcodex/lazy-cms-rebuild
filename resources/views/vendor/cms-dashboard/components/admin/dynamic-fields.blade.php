@if(!empty($dynamicFields))
    <div class="mt-8 pt-6 border-t border-[#c3c4c7]">
        <table class="w-full border-separate border-spacing-y-6">
            @foreach($dynamicFields as $name => $field)
            <tr>
                <th scope="row" class="w-[200px] text-left align-top pt-2">
                    <label for="{{ $name }}" class="text-[14px] font-semibold text-[#1d2327]">{{ $field['label'] }}</label>
                </th>
                <td>
                    @if($field['type'] === 'text' || $field['type'] === 'number')
                        <div class="flex items-center gap-2">
                            @if(str_contains($name, 'url'))
                                <span class="text-[#646970] text-[13px]">{{ url('/') }}/</span>
                            @endif
                            <input type="{{ $field['type'] }}" name="{{ $name }}" id="{{ $name }}"
                                value="{{ $settings[$name] ?? ($field['default'] ?? '') }}"
                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                class="wp-input h-8 shadow-sm mb-1 {{ str_contains($name, 'url') ? 'w-[280px]' : 'w-[400px]' }}">
                        </div>

                    @elseif($field['type'] === 'textarea')
                        <textarea name="{{ $name }}" id="{{ $name }}" rows="4"
                            placeholder="{{ $field['placeholder'] ?? '' }}"
                            class="wp-input w-[400px] p-2 shadow-sm mb-1">{{ $settings[$name] ?? ($field['default'] ?? '') }}</textarea>

                    @elseif($field['type'] === 'select')
                        <select name="{{ $name }}" id="{{ $name }}" class="wp-input w-[400px] h-8 py-0 mb-1">
                            @foreach($field['options'] ?? [] as $val => $label)
                                <option value="{{ $val }}" {{ ($settings[$name] ?? ($field['default'] ?? '')) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>

                    @elseif($field['type'] === 'checkbox')
                        <label class="inline-flex items-center cursor-pointer mb-1">
                        <label class="inline-flex items-center cursor-pointer mt-1">
                            <input type="checkbox" name="{{ $name }}" id="{{ $name }}" value="1" 
                                {{ ($settings[$name] ?? ($field['default'] ?? '0')) == '1' ? 'checked' : '' }}
                                class="w-4 h-4 mr-2">
                            <span class="text-[14px] text-[#1d2327]">{{ $field['checkbox_label'] ?? '' }}</span>
                        </label>

                    @elseif($field['type'] === 'image')
                        <div class="flex flex-col gap-2">
                            @if(isset($settings[$name]))
                                <div class="mb-2">
                                    <img src="{{ asset($settings[$name]) }}" class="max-w-[150px] border border-[#c3c4c7] p-1 bg-white rounded">
                                </div>
                            @endif
                            <input type="file" name="{{ $name }}" id="{{ $name }}" class="text-[13px]">
                        </div>
                    @endif

                    @if(isset($field['desc']))
                        <p class="text-[12px] text-[#646970] italic mt-1">{{ $field['desc'] }}</p>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@endif
