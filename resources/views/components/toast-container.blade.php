<!-- Toast Notification Stack Component -->
<div x-data="toastComponent()" 
     @add-toast.window="add($event.detail.message, $event.detail.type)"
     class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 w-full max-w-sm pointer-events-none">
     
    <template x-for="toast in toasts" :key="toast.id">
        <div class="pointer-events-auto relative overflow-hidden bg-white dark:bg-gray-800 border rounded-2xl shadow-xl p-4 flex items-start gap-3 w-full transition-all duration-300 transform translate-y-0"
             :class="{
                 'border-green-200 dark:border-green-800/60': toast.type === 'success',
                 'border-red-200 dark:border-red-800/60': toast.type === 'error',
                 'border-amber-200 dark:border-amber-800/60': toast.type === 'warning',
                 'border-blue-200 dark:border-blue-800/60': toast.type === 'info'
             }"
             x-show="toast.show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-12"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 translate-x-12 scale-90">
             
            <!-- Icon -->
            <div class="text-xl flex-shrink-0 mt-0.5">
                <span x-show="toast.type === 'success'">✅</span>
                <span x-show="toast.type === 'error'">❌</span>
                <span x-show="toast.type === 'warning'">⚠️</span>
                <span x-show="toast.type === 'info'">ℹ️</span>
            </div>
            
            <!-- Message Body -->
            <div class="flex-1 min-w-0">
                <p class="text-[10px] font-bold leading-tight uppercase tracking-wider"
                   :class="{
                       'text-green-600': toast.type === 'success',
                       'text-red-600': toast.type === 'error',
                       'text-amber-600': toast.type === 'warning',
                       'text-blue-600': toast.type === 'info'
                   }"
                   x-text="toast.type === 'success' ? 'Berhasil' : (toast.type === 'error' ? 'Gagal' : (toast.type === 'warning' ? 'Peringatan' : 'Info'))"></p>
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-0.5 font-medium leading-snug" x-text="toast.message"></p>
            </div>
            
            <!-- Dismiss Button -->
            <button @click="remove(toast.id)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 bg-gray-50 dark:bg-gray-700 rounded-full flex-shrink-0 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 h-1 bg-gray-100 dark:bg-gray-700/80 w-full">
                <div class="h-full transition-all duration-[4000ms] ease-linear"
                     :class="{
                         'bg-green-500': toast.type === 'success',
                         'bg-red-500': toast.type === 'error',
                         'bg-amber-500': toast.type === 'warning',
                         'bg-blue-500': toast.type === 'info'
                     }"
                     :style="toast.progressStyle"></div>
            </div>
        </div>
    </template>
</div>

<!-- Alpine Toast Logic Script (Include once) -->
<script>
    if (typeof toastComponent !== 'function') {
        function toastComponent() {
            return {
                toasts: [],
                add(message, type = 'success') {
                    if (this.toasts.length >= 3) {
                        this.toasts.shift();
                    }
                    const id = Date.now() + Math.random();
                    const toast = { id, message, type, show: true, progressStyle: 'width: 100%' };
                    this.toasts.push(toast);
                    
                    setTimeout(() => { toast.progressStyle = 'width: 0%'; }, 50);
                    setTimeout(() => { this.remove(id); }, 4000);
                },
                remove(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index !== -1) {
                        this.toasts[index].show = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 300);
                    }
                }
            }
        }
    }

    // Capture Laravel Session Flashes automatically
    document.addEventListener('DOMContentLoaded', () => {
        @if (session('success'))
            window.dispatchEvent(new CustomEvent('add-toast', { detail: { message: "{{ addslashes(session('success')) }}", type: 'success' } }));
        @endif
        @if (session('error'))
            window.dispatchEvent(new CustomEvent('add-toast', { detail: { message: "{{ addslashes(session('error')) }}", type: 'error' } }));
        @endif
        @if (session('warning'))
            window.dispatchEvent(new CustomEvent('add-toast', { detail: { message: "{{ addslashes(session('warning')) }}", type: 'warning' } }));
        @endif
        @if (session('info'))
            window.dispatchEvent(new CustomEvent('add-toast', { detail: { message: "{{ addslashes(session('info')) }}", type: 'info' } }));
        @endif
        @if (session('status'))
            window.dispatchEvent(new CustomEvent('add-toast', { detail: { message: "{{ addslashes(session('status')) }}", type: 'success' } }));
        @endif
    });
</script>
