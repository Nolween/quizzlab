<?php

use App\Models\Game;
use App\Models\GameRule;
use App\Models\User;

it('denies access if user is banned', function () {
    $user = User::factory(['is_banned' => true])->create();
    $response = $this->actingAs($user)->postJson('/api/gamechats', [
        'text' => 'test',
    ]);
    $response->assertStatus(403);
    expect($response['message'])->toBe('This action is unauthorized.');
});

it('denies access to store game chat if form is not valid', function () {
    $user = User::factory(['is_banned' => false])->create();
    $response = $this->actingAs($user)->postJson('/api/gamechats', [
        'text' => '',
        'gameId'  => 1,
    ]);
    $response->assertStatus(422);
    expect($response['errors'])->toHaveKeys(['message', 'gameId']);
});

it('user can store a game chat', function () {
    $user = User::factory(['is_banned' => false])->create();

    $gameRule = GameRule::factory()->create();

    $game = Game::factory()->create(['is_finished' => false, 'game_rule_id' => $gameRule->id]);

    $response = $this->actingAs($user)->postJson('/api/gamechats', [
        'message' => 'test',
        'gameId'  => $game->id,
    ]);
    $response->assertStatus(201);
    expect($response['data']['user_id'])->toBe($user->id);
    expect($response['data']['game_id'])->toBe($game->id);
    expect($response['data']['text'])->toBe('test');
});
