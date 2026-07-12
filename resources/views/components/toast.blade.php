<div x-data="{
        show: false,
        message: '',
        type: 'success',
        init() {
            @if(session('success'))
                this.message = '{{ session('success') }}';
                this.type = 'success';
                this.showToast();
            @endif
            @if(session('error'))
                this.message = '{{ session('error') }}';
                this.type = 'error';
                this.showToast();
            @endif
            @if(session('status'))
                this.message = '{{ session('status') }}';
                this.type = 'success';
                this.showToast();
            @endif
        },
        showToast() {
            this.show = true;
            setTimeout(() => { this.show = false; }, 4000);
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-10"
    x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-10"
    class="fixed bottom-5 sm:bottom-auto sm:top-5 right-5 z-[9999] flex items-start max-w-sm w-full shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800"
    style="display: none;">
    
    {{-- Left color bar --}}
    <div class="w-2.5 self-stretch" :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'"></div>
    
    <div class="p-4 flex gap-3 flex-1 items-start">
        {{-- Icon --}}
        <div class="flex-shrink-0 text-xl mt-0.5">
            <span x-show="type === 'success'">✅</span>
            <span x-show="type === 'error'">❌</span>
        </div>
        
        {{-- Message --}}
        <div class="pt-0.5">
            <h4 class="text-sm font-bold text-gray-900 dark:text-white tracking-wide" x-text="type === 'success' ? 'Berhasil!' : 'Terjadi Kesalahan'"></h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed" x-text="message"></p>
        </div>
        
        {{-- Close --}}
        <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none bg-gray-50 dark:bg-gray-700 p-1.5 rounded-full transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</div>
