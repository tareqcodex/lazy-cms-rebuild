<div v-if="el.type === 'text'" class="element-text mb-4">
    <div v-html="el.settings.content || 'Start typing your content here...'" class="prose prose-slate max-w-none"></div>
</div>
