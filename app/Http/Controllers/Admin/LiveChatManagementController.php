<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatSession;
use App\Models\ChatMessage;

class LiveChatManagementController extends Controller
{
    public function index()
    {
        $sessions = ChatSession::with(['messages', 'user'])
            ->where('mode', 'agent')
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('admin.chat.index', compact('sessions'));
    }

    public function getSessionMessages(ChatSession $session)
    {
        // Đánh dấu tin nhắn của khách là đã đọc
        $session->messages()->where('sender', 'user')->update(['is_read' => true]);

        return response()->json([
            'session' => $session->load('user'),
            'messages' => $session->messages()->orderBy('created_at', 'asc')->get()
        ]);
    }

    public function reply(Request $request, ChatSession $session)
    {
        $request->validate(['message' => 'required|string']);

        $msg = ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'admin',
            'message' => trim($request->message),
            'is_read' => true
        ]);

        $session->update(['last_message_at' => now()]);

        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function close(ChatSession $session)
    {
        $session->update(['mode' => 'bot']);
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'bot',
            'message' => 'Phiên hỗ trợ trực tuyến đã kết thúc. TechBot đã quay lại để tiếp tục hỗ trợ bạn tự động!',
            'is_read' => true
        ]);

        return response()->json(['success' => true]);
    }
}