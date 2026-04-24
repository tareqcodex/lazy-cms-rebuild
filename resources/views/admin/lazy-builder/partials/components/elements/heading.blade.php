<div v-if="el.type === 'heading'" class="element-heading mb-4">
    <h2 :style="{ textAlign: el.settings.textAlign }" class="m-0 p-0 text-slate-800 font-bold leading-tight">
        @{{ el.settings.title || 'New Heading' }}
    </h2>
</div>
