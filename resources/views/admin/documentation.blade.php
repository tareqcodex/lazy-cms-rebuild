<x-cms-dashboard::layouts.admin>
    <x-slot name="title">Documentation - Lazy CMS</x-slot>

    <div class="px-6 py-4">
        <div class="flex items-center justify-between mb-8 border-b border-gray-200 pb-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900">System Documentation</h1>
                <p class="text-gray-500 mt-1">Everything you need to know about Lazy CMS architecture and commands.</p>
            </div>
            <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-bold border border-blue-100">
                v3.6.0 Stable
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-8 prose prose-blue max-w-none prose-headings:font-black prose-a:text-blue-600 prose-code:text-pink-600 prose-code:bg-pink-50 prose-code:px-1 prose-code:rounded prose-pre:bg-gray-900 prose-pre:text-gray-100">
                @if(!empty($content))
                    {{-- A very simple Markdown to HTML converter logic --}}
                    @php 
                        // Convert headings
                        $parsed = preg_replace('/^### (.*$)/m', '<h3 class="text-xl font-bold mt-6 mb-3">$1</h3>', $content);
                        $parsed = preg_replace('/^## (.*$)/m', '<h2 class="text-2xl font-black mt-8 mb-4 pb-2 border-b border-gray-100">$1</h2>', $parsed);
                        $parsed = preg_replace('/^# (.*$)/m', '<h1 class="text-4xl font-black mb-6">$1</h1>', $parsed);
                        
                        // Convert code blocks
                        $parsed = preg_replace('/```bash\n(.*?)\n```/s', '<div class="bg-gray-900 rounded-lg p-4 my-4 font-mono text-sm text-green-400 overflow-x-auto"><pre>$1</pre></div>', $parsed);
                        $parsed = preg_replace('/```php\n(.*?)\n```/s', '<div class="bg-gray-900 rounded-lg p-4 my-4 font-mono text-sm text-blue-300 overflow-x-auto"><pre>$1</pre></div>', $parsed);
                        $parsed = preg_replace('/```(.*?)\n(.*?)\n```/s', '<div class="bg-gray-900 rounded-lg p-4 my-4 font-mono text-sm text-gray-300 overflow-x-auto"><pre>$2</pre></div>', $parsed);
                        
                        // Convert inline code
                        $parsed = preg_replace('/`([^`]+)`/', '<code class="bg-gray-100 text-pink-600 px-1.5 py-0.5 rounded text-sm font-mono">$1</code>', $parsed);
                        
                        // Convert Bold
                        $parsed = preg_replace('/\*\*([^\*]+)\*\*/', '<strong>$1</strong>', $parsed);
                        
                        // Convert Lists
                        $parsed = preg_replace('/^\- (.*$)/m', '<li class="ml-4 list-disc text-gray-700">$1</li>', $parsed);
                        
                        // Convert Tables (Basic)
                        $parsed = preg_replace('/\| (.*) \|/m', '<tr class="border-b border-gray-100"><td class="p-3 text-sm text-gray-600">$1</td></tr>', $parsed);
                        $parsed = str_replace('<tr class="border-b border-gray-100"><td class="p-3 text-sm text-gray-600">---</td></tr>', '', $parsed);
                        $parsed = preg_replace('/(<tr class="border-b border-gray-100">.*<\/tr>)+/s', '<table class="w-full border-collapse border border-gray-200 my-6">$0</table>', $parsed);

                        // Clean up multiple line breaks
                        $parsed = nl2br($parsed);
                    @endphp
                    {!! $parsed !!}
                @else
                    <div class="text-center py-20">
                        <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <p class="text-gray-400 font-medium">README.md file not found in the package root.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 flex items-center justify-between text-gray-400 text-xs">
            <p>&copy; {{ date('Y') }} Lazy CMS Framework. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <a href="https://github.com/tareqcodex/lazy-cms-rebuild" target="_blank" class="hover:text-blue-600 transition-colors">GitHub Repository</a>
                <span>&bull;</span>
                <p>Built with ❤️ by Tareq Codex</p>
            </div>
        </div>
    </div>

    <style>
        .prose table td { border: 1px solid #e5e7eb; padding: 12px; }
        .prose table tr:nth-child(even) { background: #f9fafb; }
        .prose table tr:first-child { font-weight: bold; background: #f3f4f6; }
    </style>
</x-cms-dashboard::layouts.admin>
