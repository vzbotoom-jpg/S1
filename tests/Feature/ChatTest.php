<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_send_message()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $response = $this->postJson('/api/chat/message', [
            'message' => 'Hello AI!',
        ]);
        
        $response->assertStatus(202); // Accepted (async)
        $this->assertDatabaseHas('conversations', ['user_id' => $user->id]);
    }
}