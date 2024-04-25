<?php

use App\Models\Game;
use App\Models\GameQuestion;
use App\Models\GameRule;
use App\Models\GameTag;
use App\Models\Question;
use App\Models\Tag;
use App\Models\User;

it('denies access to game index if user is banned', function () {
    $user = User::factory(['is_banned' => true])->create();
    $response = $this->actingAs($user)->getJson('/api/games');
    $response->assertStatus(403);
    expect($response['message'])->toBe('This action is unauthorized.');
});

it('user can get games', function () {
    $user = User::factory(['is_banned' => false])->create();
    $gameRule = GameRule::factory()->create();
    $games = Game::factory(10)->create(['has_begun' => false, 'is_finished' => false]);
    $response = $this->actingAs($user)->getJson('/api/games');
    $response->assertStatus(200);
    expect($response['data'])->toHaveCount(10);
});

it('user can get games with filters', function () {
    $user = User::factory(['is_banned' => false])->create();
    $gameRule = GameRule::factory()->create();
    $questions = Question::factory(40)->create(['is_moderated' => true, 'is_integrated' => true]);
    $games = Game::factory(10)->create(['has_begun' => false, 'is_finished' => false]);
    $gameQuestions = GameQuestion::factory(40)->create();
    $tags = Tag::factory(10)->create();
    $gameTags = GameTag::factory(30)->create();
    $tag = $gameTags->random()->tag;
    // Find a tag and count how many games have it
    $gamesWithTagCount = $gameTags->filter(function ($gameTag) use ($tag) {
        return $gameTag->tag_id == $tag->id;
    })
        ->map(function ($gameTag) {
            return $gameTag->game_id;
        })
        ->unique()
        ->count();

    $response = $this->actingAs($user)->getJson('/api/games?search=' . $tag->name);
    $response->assertStatus(200);
    expect($response['data'])->toHaveCount($gamesWithTagCount);
});
