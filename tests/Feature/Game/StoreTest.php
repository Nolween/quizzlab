<?php

use App\Models\GameRule;
use App\Models\Question;
use App\Models\Tag;
use App\Models\User;

it('denies to store game if user is banned', function () {
    $user = User::factory(['is_banned' => true])->create();
    $response = $this->actingAs($user)->postJson('/api/games', [
        'name'       => 'test',
        'maxPlayers' => 1,
        'gameRuleId' => 1,
    ]);
    $response->assertStatus(403);
    expect($response['message'])->toBe('This action is unauthorized.');
});

it('denies to store game if form is not valid', function () {
    $user = User::factory(['is_banned' => false])->create();
    $response = $this->actingAs($user)->postJson('/api/games', [
        'maxPlayers'        => 0,
        'questionCount'     => 0,
        'responseTime'      => 0,
        'allTags'           => null,
        'selectedThemes'    => ['test', 'test2'],
        'possibleQuestions' => 0,
    ]);
    $response->assertStatus(422);
    expect($response['errors'])->toHaveKeys([
                                                'maxPlayers',
                                                'questionCount',
                                                'responseTime',
                                                'allTags',
                                                'selectedThemes.0',
                                                'selectedThemes.1',
                                                'possibleQuestions'
                                            ]);
});


it('success into storing game', function() {
    $user = User::factory(['is_banned' => false])->create();
    // Ceate some tags
    $tags = Tag::factory()->count(5)->create();

    // Create a game rule
    $gameRule = GameRule::factory()->create();

    // Create 1 question to assignate to the game
    $question = Question::factory()->create(['is_integrated' => true]);

    $response = $this->actingAs($user)->postJson('/api/games', [
        'maxPlayers'        => 2,
        'questionCount'     => 1,
        'responseTime'      => 1,
        'allTags'           => true,
        'selectedThemes'    => $tags->pluck('name')->toArray(),
        'possibleQuestions' => 1,
    ]);
    $response->assertStatus(201);

    expect($response['data']['user_id'])->toBe($user->id);
    expect($response['data']['max_players'])->toBe(2);
    expect($response['data']['question_count'])->toBe(1);
    expect($response['data']['response_time'])->toBe(1);
    expect($response['data']['questions_have_all_tags'])->toBe(true);
    expect($response['data']['game_code'])->toBeString();
    expect($response['data']['is_finished'])->toBe(false);
    expect($response['data']['has_begun'])->toBe(false);
    expect($response['data']['game_rule_id'])->toBe(1);
});
