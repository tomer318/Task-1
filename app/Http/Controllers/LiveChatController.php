<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LiveChatController extends Controller
{
    private function getOrCreateSession(Request $request): ChatSession
    {
        $token = $request->cookie('techzone_chat_token');
        if (!$token) {
            $token = Str::uuid()->toString();
            cookie()->queue(cookie()->make('techzone_chat_token', $token, 60 * 24 * 30));
        }

        $session = ChatSession::where('session_token', $token)->first();

        if (!$session) {
            $session = ChatSession::create([
                'user_id' => Auth::id(),
                'session_token' => $token,
                'user_name' => Auth::check() ? Auth::user()->name : 'Khách vãng lai',
                'mode' => 'bot',
                'status' => 'active',
                'last_message_at' => now(),
            ]);
        } elseif (Auth::check() && !$session->user_id) {
            $session->update([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name
            ]);
        }

        return $session;
    }

    // Lấy danh sách tin nhắn hiện tại
    public function getMessages(Request $request)
    {
        $session = $this->getOrCreateSession($request);
        $messages = $session->messages()->orderBy('created_at', 'asc')->get();

        return response()->json([
            'session_id' => $session->id,
            'mode' => $session->mode,
            'messages' => $messages->map(fn($m) => [
                'id' => $m->id,
                'sender' => $m->sender,
                'message' => $m->message,
                'time' => $m->created_at->format('H:i')
            ])
        ]);
    }

    // Chuyển sang gặp Nhân viên CSKH
    public function requestAgent(Request $request)
    {
        $session = $this->getOrCreateSession($request);
        $session->update([
            'mode' => 'agent',
            'last_message_at' => now()
        ]);

        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'bot',
            'message' => '🔔 Bạn đã được kết nối với hàng chờ CSKH của TechZone. Nhân viên tư vấn sẽ phản hồi bạn ngay tại đây trong giây lát nhé!',
            'is_read' => true
        ]);

        return response()->json(['success' => true, 'mode' => 'agent']);
    }

    // Khách hàng gửi tin nhắn
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        $session = $this->getOrCreateSession($request);
        $text = trim($request->message);

        $msg = ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'user',
            'message' => $text,
            'is_read' => false
        ]);

        $session->update(['last_message_at' => now()]);

        return response()->json(['success' => true, 'message' => $msg]);
    }
}