<div x-data="{
    open: false,
    inputMessage: '',
    loading: false,
    chatMode: 'bot', // 'bot' hoặc 'agent'
    pollingInterval: null,
    messages: [
        {
            sender: 'bot',
            text: 'Chào bạn! Mình là **TechBot** - Trợ lý công nghệ TechZone 🤖.\nBạn cần hỏi nhanh sản phẩm hay muốn trò chuyện trực tiếp với nhân viên CSKH nè?',
            products: [],
            suggestions: ['Gặp nhân viên tư vấn', 'Tìm Laptop Gaming', 'Voucher giảm giá', 'Kiểm tra đơn hàng']
        }
    ],
    scrollToBottom() {
        this.$nextTick(() => {
            let container = this.$refs.chatContainer;
            if (container) {
                container.scrollTo({
                    top: container.scrollHeight,
                    behavior: 'smooth'
                });
            }
        });
    },
    initPolling() {
        // Tự động kiểm tra tin nhắn mới từ Admin mỗi 3 giây khi đang ở chế độ agent
        this.pollingInterval = setInterval(() => {
            if (this.open && this.chatMode === 'agent') {
                this.fetchMessages();
            }
        }, 3000);
    },
    fetchMessages() {
        fetch('{{ route('chat.messages') }}')
            .then(res => res.json())
            .then(data => {
                this.chatMode = data.mode;
                if (data.messages && data.messages.length > 0) {
                    let formatted = data.messages.map(m => ({
                        sender: m.sender,
                        text: m.message,
                        products: [],
                        suggestions: []
                    }));
                    if (formatted.length > this.messages.length) {
                        this.messages = formatted;
                        this.scrollToBottom();
                    }
                }
            });
    },
    connectAgent() {
        this.loading = true;
        fetch('{{ route('chat.request_agent') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            this.loading = false;
            this.chatMode = 'agent';
            this.messages.push({
                sender: 'bot',
                text: '🔔 **Đã chuyển sang chế độ kết nối Nhân viên CSKH.** Bạn hãy gửi câu hỏi hoặc yêu cầu cần tư vấn bên dưới nhé!',
                products: [],
                suggestions: []
            });
            this.scrollToBottom();
            this.initPolling();
        });
    },
    sendMessage(customText = null) {
        let msg = customText || this.inputMessage.trim();
        if (!msg || this.loading) return;

        if (msg === 'Gặp nhân viên tư vấn') {
            this.connectAgent();
            this.inputMessage = '';
            return;
        }

        this.messages.push({
            sender: 'user',
            text: msg,
            products: [],
            suggestions: []
        });

        this.inputMessage = '';
        this.loading = true;
        this.scrollToBottom();

        // 1. Nếu đang chat với nhân viên CSKH
        if (this.chatMode === 'agent') {
            fetch('{{ route('chat.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: msg })
            })
            .then(res => res.json())
            .then(() => {
                this.loading = false;
                this.scrollToBottom();
            });
            return;
        }

        // 2. Nếu đang chat với TechBot AI
        fetch('{{ route('techbot.chat') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: msg })
        })
        .then(res => res.json())
        .then(data => {
            this.loading = false;
            this.messages.push({
                sender: 'bot',
                text: data.reply,
                products: data.products || [],
                suggestions: data.suggestions || []
            });
            this.scrollToBottom();
        })
        .catch(() => {
            this.loading = false;
            this.messages.push({
                sender: 'bot',
                text: 'Xin lỗi bạn, TechBot đang gặp sự cố kết nối máy chủ một chút. Bạn thử lại nhé!',
                products: [],
                suggestions: []
            });
            this.scrollToBottom();
        });
    }
}" style="position: fixed; bottom: 24px; right: 24px; z-index: 99999; display: flex; flex-direction: column; align-items: flex-end;">

    <!-- 1. POPUP KHUNG CHAT TECHBOT & LIVE CHAT -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300 transform origin-bottom-right"
         x-transition:enter-start="opacity-0 translate-y-6 scale-90"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform origin-bottom-right"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-6 scale-90"
         style="width: 380px; max-width: calc(100vw - 48px); height: 540px; display: none;"
         class="bg-slate-900/95 border border-slate-800 rounded-3xl shadow-2xl flex flex-col overflow-hidden backdrop-blur-xl mb-3">
        
        <!-- Header Chatbot -->
        <div class="p-4 bg-gradient-to-r from-rose-950/80 via-slate-900 to-slate-900 border-b border-slate-800 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-rose-600 to-orange-500 p-0.5 shadow-lg shadow-rose-600/30">
                        <div class="w-full h-full bg-slate-950 rounded-2xl flex items-center justify-center font-bold text-white text-base">
                            <span x-text="chatMode === 'agent' ? '👨‍💼' : '🤖'"></span>
                        </div>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-slate-900 rounded-full"></span>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-white flex items-center gap-1.5">
                        <span x-text="chatMode === 'agent' ? 'Tư Vấn Viên TechZone' : 'TechBot Assistant'"></span>
                        <span class="px-1.5 py-0.2 rounded font-mono text-[9px] font-semibold border"
                              :class="chatMode === 'agent' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border-rose-500/30'"
                              x-text="chatMode === 'agent' ? 'LIVE' : 'AI'"></span>
                    </h3>
                    <p class="text-[11px] text-slate-400" x-text="chatMode === 'agent' ? 'Đang kết nối nhân viên trực tuyến' : 'Tự động phản hồi 24/7'"></p>
                </div>
            </div>

            <div class="flex items-center gap-1">
                <!-- Nút chuyển sang Agent nếu đang là Bot -->
                <button x-show="chatMode === 'bot'" @click="connectAgent()" type="button" title="Gặp nhân viên tư vấn"
                        class="p-2 bg-slate-950 border border-slate-800 hover:border-emerald-500 text-emerald-400 hover:text-white rounded-xl text-xs font-semibold transition cursor-pointer">
                    👨‍💼
                </button>
                <button @click="open = false" type="button" class="w-8 h-8 rounded-xl bg-slate-950 border border-slate-800 hover:border-rose-500 text-slate-400 hover:text-white flex items-center justify-center transition cursor-pointer text-sm font-bold">
                    ✕
                </button>
            </div>
        </div>

        <!-- Khung tin nhắn cuộn -->
        <div x-ref="chatContainer" 
             class="flex-1 p-4 overflow-y-auto space-y-4 text-xs [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:bg-slate-700/60 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-transparent">
            <template x-for="(msg, index) in messages" :key="index">
                <div class="space-y-2">
                    <div class="flex gap-2.5 items-end" :class="msg.sender === 'user' ? 'justify-end' : 'justify-start'">
                        
                        <!-- Avatar -->
                        <template x-if="msg.sender !== 'user'">
                            <div class="w-7 h-7 rounded-xl text-white flex items-center justify-center shrink-0 text-xs shadow-md"
                                 :class="msg.sender === 'admin' ? 'bg-emerald-600' : 'bg-gradient-to-tr from-rose-600 to-orange-500'">
                                <span x-text="msg.sender === 'admin' ? '👨‍💼' : '🤖'"></span>
                            </div>
                        </template>

                        <!-- Bong bóng tin nhắn -->
                        <div class="p-3.5 rounded-2xl max-w-[84%] leading-relaxed space-y-2"
                             :class="msg.sender === 'user' 
                                 ? 'bg-gradient-to-r from-rose-600 to-red-500 text-white rounded-br-none shadow-md shadow-rose-600/20 font-medium' 
                                 : (msg.sender === 'admin' 
                                     ? 'bg-slate-950 border border-emerald-500/40 text-slate-200 rounded-bl-none shadow-lg' 
                                     : 'bg-slate-950 border border-slate-800 text-slate-200 rounded-bl-none shadow-lg')">
                            
                            <div class="whitespace-pre-line" x-html="msg.text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/`([^`]+)`/g, '<code class=\'bg-slate-900 px-1 py-0.5 rounded text-rose-400 font-mono\'>$1</code>')"></div>

                            <!-- Card sản phẩm gợi ý -->
                            <template x-if="msg.products && msg.products.length > 0">
                                <div class="space-y-1.5 pt-2 border-t border-slate-800/80">
                                    <template x-for="p in msg.products" :key="p.slug">
                                        <a :href="'/product/' + p.slug" class="block p-2 bg-slate-900 border border-slate-800 hover:border-rose-500 rounded-xl transition group">
                                            <div class="font-bold text-white text-[11px] group-hover:text-rose-400 truncate" x-text="p.name"></div>
                                            <div class="text-[10px] text-rose-500 font-mono font-bold mt-0.5" x-text="p.price"></div>
                                        </a>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Gợi ý câu hỏi nhanh -->
                    <template x-if="msg.suggestions && msg.suggestions.length > 0">
                        <div class="flex flex-wrap gap-1.5 pl-9 pt-0.5">
                            <template x-for="sug in msg.suggestions" :key="sug">
                                <button type="button" @click="sendMessage(sug)"
                                        class="px-2.5 py-1 bg-slate-950 border border-slate-800 hover:border-rose-500 text-slate-300 hover:text-rose-400 rounded-lg text-[10px] font-semibold transition cursor-pointer shadow">
                                    <span x-text="sug"></span> &rarr;
                                </button>
                            </template>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Typing indicator -->
            <div x-show="loading" class="flex gap-2 items-center text-slate-400 text-[11px] pl-9" style="display: none;">
                <span class="inline-block w-2 h-2 rounded-full bg-rose-500 animate-bounce"></span>
                <span class="inline-block w-2 h-2 rounded-full bg-rose-500 animate-bounce [animation-delay:0.2s]"></span>
                <span class="inline-block w-2 h-2 rounded-full bg-rose-500 animate-bounce [animation-delay:0.4s]"></span>
                <span class="text-slate-500 ml-1" x-text="chatMode === 'agent' ? 'Đang gửi tin nhắn...' : 'TechBot đang soạn câu trả lời...'"></span>
            </div>
        </div>

        <!-- Ô nhập tin nhắn -->
        <form @submit.prevent="sendMessage()" class="p-3 bg-slate-950 border-t border-slate-800 flex items-center gap-2 shrink-0">
            <input type="text" x-model="inputMessage" 
                   :placeholder="chatMode === 'agent' ? 'Nhập tin nhắn cho nhân viên tư vấn...' : 'Hỏi TechBot về máy gaming, đơn hàng, ship...'" 
                   class="flex-1 bg-slate-900 border border-slate-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-2xl px-4 py-2 text-xs text-white placeholder-slate-500 focus:outline-none transition">
            
            <button type="submit" 
                    :disabled="!inputMessage.trim() || loading"
                    :class="(!inputMessage.trim() || loading) 
                        ? 'opacity-40 cursor-not-allowed bg-slate-800 text-slate-500' 
                        : 'hover:scale-105 active:scale-95 cursor-pointer bg-gradient-to-r from-rose-600 to-red-500 text-white shadow-lg shadow-rose-600/40'"
                    class="w-9 h-9 rounded-xl flex items-center justify-center transition shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-white -mr-0.5">
                    <path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" />
                </svg>
            </button>
        </form>
    </div>

    <!-- 2. NÚT TRÒN BẬT / TẮT WIDGET -->
    <button @click="open = !open; if(open) scrollToBottom();" 
            type="button"
            class="relative flex items-center gap-2.5 px-4 py-3 rounded-full bg-gradient-to-r from-rose-600 via-red-600 to-orange-500 text-white font-bold text-xs shadow-2xl shadow-rose-600/40 hover:scale-105 active:scale-95 transition-all duration-300 cursor-pointer border border-rose-400/30 group">
        <span class="text-base group-hover:rotate-12 transition-transform duration-300">💬</span>
        <span class="font-extrabold tracking-wide">Hỗ trợ trực tuyến</span>
        <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-400 border-2 border-slate-950"></span>
        </span>
    </button>
</div>