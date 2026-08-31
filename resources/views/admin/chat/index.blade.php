<x-admin-layout>
    <div class="h-[calc(100vh-140px)] flex flex-col space-y-4 text-white"
         x-data="{
             activeSessionId: {{ $sessions->first()?->id ?? 'null' }},
             activeSession: null,
             messages: [],
             replyText: '',
             loading: false,
             init() {
                 if (this.activeSessionId) {
                     this.loadMessages(this.activeSessionId);
                 }
                 // Tự động làm mới tin nhắn mỗi 3 giây
                 setInterval(() => {
                     if (this.activeSessionId) {
                         this.loadMessages(this.activeSessionId, false);
                     }
                 }, 3000);
             },
             loadMessages(sessionId, scroll = true) {
                 this.activeSessionId = sessionId;
                 fetch('/admin/live-chat/' + sessionId + '/messages')
                     .then(res => res.json())
                     .then(data => {
                         this.activeSession = data.session;
                         this.messages = data.messages;
                         if (scroll) {
                             this.$nextTick(() => {
                                 let box = this.$refs.adminChatBox;
                                 if (box) box.scrollTop = box.scrollHeight;
                             });
                         }
                     });
             },
             sendReply() {
                 if (!this.replyText.trim() || !this.activeSessionId || this.loading) return;
                 this.loading = true;
                 let text = this.replyText.trim();
                 this.replyText = '';

                 fetch('/admin/live-chat/' + this.activeSessionId + '/reply', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                     },
                     body: JSON.stringify({ message: text })
                 })
                 .then(res => res.json())
                 .then(() => {
                     this.loading = false;
                     this.loadMessages(this.activeSessionId);
                 });
             },
             closeChat() {
                 if (!confirm('Bạn có chắc muốn kết thúc phiên hỗ trợ này và chuyển lại cho TechBot?')) return;
                 fetch('/admin/live-chat/' + this.activeSessionId + '/close', {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                     }
                 }).then(() => {
                     window.location.reload();
                 });
             }
         }">

        <!-- Tiêu đề -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-white flex items-center gap-2">
                    <span>💬</span> Trung Tâm Live Chat & CSKH Trực Tuyến
                </h1>
                <p class="text-xs text-slate-400 mt-1">Hỗ trợ và phản hồi trực tiếp các khách hàng đang chờ tư vấn</p>
            </div>
            <span class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-mono font-bold rounded-xl">
                {{ count($sessions) }} Phiên đang kết nối
            </span>
        </div>

        <!-- Khung Chat 2 Cột -->
        <div class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-4 bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
            
            <!-- CỘT TRÁI: Danh Sách Khách Hàng Chờ -->
            <div class="md:col-span-4 border-r border-slate-800 flex flex-col bg-slate-950/60">
                <div class="p-4 border-b border-slate-800">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Hội thoại chờ xử lý</span>
                </div>

                <div class="flex-1 overflow-y-auto divide-y divide-slate-800/60 scrollbar-none">
                    @forelse($sessions as $s)
                        @php
                            $lastMsg = $s->messages->last();
                            $hasUnread = $s->messages->where('sender', 'user')->where('is_read', false)->count() > 0;
                        @endphp
                        <div @click="loadMessages({{ $s->id }})" 
                             :class="activeSessionId === {{ $s->id }} ? 'bg-slate-800/90 border-l-4 border-rose-500' : 'hover:bg-slate-900/80'"
                             class="p-4 cursor-pointer transition flex items-start gap-3">
                            
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-rose-600 to-orange-500 flex items-center justify-center font-bold text-white text-sm shrink-0 shadow">
                                {{ strtoupper(substr($s->user_name, 0, 1)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-xs text-white truncate">{{ $s->user_name }}</h4>
                                    <span class="text-[10px] text-slate-500 font-mono">{{ $s->last_message_at ? \Carbon\Carbon::parse($s->last_message_at)->format('H:i') : '' }}</span>
                                </div>
                                <p class="text-[11px] text-slate-400 truncate mt-0.5">
                                    {{ $lastMsg ? $lastMsg->message : 'Bắt đầu hội thoại...' }}
                                </p>
                            </div>

                            @if($hasUnread)
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0 mt-1"></span>
                            @endif
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-slate-500">Hiện không có khách hàng nào đang yêu cầu hỗ trợ trực tiếp.</div>
                    @endforelse
                </div>
            </div>

            <!-- CỘT PHẢI: Khung Trò Chuyện Chi Tiết -->
            <div class="md:col-span-8 flex flex-col bg-slate-900">
                <template x-if="activeSessionId">
                    <div class="flex-1 flex flex-col h-full">
                        
                        <!-- Header Phòng Chat -->
                        <div class="p-4 border-b border-slate-800 flex items-center justify-between bg-slate-950/40 shrink-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-800 flex items-center justify-center font-bold text-rose-500 text-sm">
                                    👤
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm text-white" x-text="activeSession ? activeSession.user_name : 'Đang tải...'"></h3>
                                    <p class="text-[10px] text-emerald-400 font-medium">● Khách hàng trực tuyến</p>
                                </div>
                            </div>
                            <button @click="closeChat()" type="button" class="px-3 py-1.5 bg-slate-950 border border-slate-800 hover:border-rose-500 text-rose-400 text-xs font-semibold rounded-xl transition cursor-pointer">
                                ✕ Kết thúc phiên chat
                            </button>
                        </div>

                        <!-- Khung Tin Nhắn -->
                        <div x-ref="adminChatBox" class="flex-1 p-5 overflow-y-auto space-y-4 text-xs scrollbar-none">
                            <template x-for="m in messages" :key="m.id">
                                <div class="flex gap-2.5 items-end" :class="m.sender === 'admin' ? 'justify-end' : 'justify-start'">
                                    
                                    <template x-if="m.sender !== 'admin'">
                                        <div class="w-7 h-7 rounded-xl bg-slate-800 text-white flex items-center justify-center text-xs shrink-0">
                                            <span x-text="m.sender === 'user' ? '👤' : '🤖'"></span>
                                        </div>
                                    </template>

                                    <div class="p-3.5 rounded-2xl max-w-[75%] leading-relaxed"
                                         :class="m.sender === 'admin' 
                                             ? 'bg-rose-600 text-white rounded-br-none shadow-lg font-medium' 
                                             : (m.sender === 'user' 
                                                 ? 'bg-slate-950 border border-slate-800 text-slate-200 rounded-bl-none' 
                                                 : 'bg-slate-950/60 border border-slate-800 text-slate-400 italic rounded-bl-none')">
                                        <p class="whitespace-pre-line" x-text="m.message"></p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Ô Nhập Tin Nhắn Trả Lời -->
                        <form @submit.prevent="sendReply()" class="p-4 border-t border-slate-800 bg-slate-950/60 flex items-center gap-3 shrink-0">
                            <input type="text" x-model="replyText" placeholder="Nhập câu trả lời cho khách hàng..." 
                                   class="flex-1 bg-slate-900 border border-slate-800 focus:border-rose-500 rounded-2xl px-4 py-2.5 text-xs text-white focus:outline-none">
                            <button type="submit" 
                                    :disabled="!replyText.trim() || loading"
                                    :class="(!replyText.trim() || loading) ? 'opacity-40 cursor-not-allowed' : 'hover:scale-105 active:scale-95 cursor-pointer'"
                                    class="px-5 py-2.5 bg-gradient-to-r from-rose-600 to-red-500 text-white font-bold rounded-2xl text-xs shadow-lg shadow-rose-600/30 transition">
                                Gửi Trả Lời
                            </button>
                        </form>
                    </div>
                </template>

                <template x-if="!activeSessionId">
                    <div class="flex-1 flex flex-col items-center justify-center text-slate-500 text-xs">
                        <span class="text-4xl mb-2">💬</span>
                        <p>Vui lòng chọn một cuộc hội thoại từ danh sách bên trái để phản hồi.</p>
                    </div>
                </template>
            </div>

        </div>
    </div>
</x-admin-layout>