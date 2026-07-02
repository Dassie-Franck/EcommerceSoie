@if(session('success'))
    <div class="alert alert-success max-w-4xl mx-auto mt-4" x-data="{ show: true }" x-show="show">
        <span>{{ session('success') }}</span>
        <button class="btn btn-ghost btn-xs" @click="show = false">✕</button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-error max-w-4xl mx-auto mt-4" x-data="{ show: true }" x-show="show">
        <span>{{ session('error') }}</span>
        <button class="btn btn-ghost btn-xs" @click="show = false">✕</button>
    </div>
@endif