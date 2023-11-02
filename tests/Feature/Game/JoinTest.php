<?php

use App\Models\Game;
use App\Models\GamePlayer;
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

it('denies join game if game is not a valid one', function () {
    $user = User::factory(['is_banned' => false])->create();
    $response = $this->actingAs($user)->getJson(route('game.join', ['game' => 1]));
    $response->assertStatus(404);
});

it('joins game if game is valid', function () {
    $user = User::factory(['is_banned' => false])->create();
    $response = $this->actingAs($user)->getJson(route('game.join', ['game' => 1]));
    $response->assertStatus(404);
});

it('denies joining game if game is full', function () {
    $user = User::factory(['is_banned' => false])->create();
    $gameRule = GameRule::factory()->create();
    $anotherUser = User::factory(['is_banned' => false])->create();
    $game = Game::factory()->create(['max_players' => 1]);
    $gamePlayer = GamePlayer::factory()->create(['game_id' => $game->id, 'user_id' => $anotherUser->id]);
    $response = $this->actingAs($user)->getJson(route('game.join', ['game' => $game->id]));
    $response->assertStatus(403);
});


it('denies joining if game is finished', function () {
    $user = User::factory(['is_banned' => false])->create();
    $gameRule = GameRule::factory()->create();
    $game = Game::factory()->create(['is_finished' => true]);
    $response = $this->actingAs($user)->getJson(route('game.join', ['game' => $game->id]));
    $response->assertStatus(403);
});

it('joins game if player joins for the first time', function () {
    $user = User::factory(['is_banned' => false])->create();
    $gameCreator = User::factory(['is_banned' => false])->create();
    $gameRule = GameRule::factory()->create();
    $game = Game::factory()->create(['max_players' => 2, 'is_finished' => false, 'user_id' => $gameCreator->id]);
    $response = $this->actingAs($user)->getJson(route('game.join', ['game' => $game->id]));
    $response->assertStatus(200);
    expect($response['data']['game']['id'])->toBe($game->id);
    expect($response['data']['game']['game_rule_id'])->toBe($gameRule->id);
    expect($response['data']['game']['user_id'])->toBe($gameCreator->id);
    expect($response['data']['players'])->toHaveCount(1);
    expect($response['data']['players'][0]['user_id'])->toBe($user->id);
    expect($response['data']['players'][0]['game_id'])->toBe($game->id);
    expect($response['data']['players'][0]['is_ready'])->toBe(0);
    expect($response['data']['players'][0]['final_score'])->toBe(0);
});
