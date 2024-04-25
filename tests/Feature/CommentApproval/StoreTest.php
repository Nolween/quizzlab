<?php

use App\Models\Question;
use App\Models\QuestionComment;
use App\Models\User;

it('user cannot approve if not authenticated', function () {
    $response = $this->postJson('/api/approvals', [
        'commentid'  => 1,
        'ispositive' => 1,
    ]);
    $response->assertStatus(401);
});

it('denies access if user is banned', function () {
    $user = User::factory(['is_banned' => true])->create();
    $response = $this->actingAs($user)->postJson('/api/approvals', [
        'commentid'  => 1,
        'ispositive' => 1,
    ]);
    $response->assertStatus(403);
    expect($response['message'])->toBe('This action is unauthorized.');
});

it('denies access to store comment approval if form is not valid', function () {
    $user = User::factory(['is_banned' => false])->create();
    $response = $this->actingAs($user)->postJson('/api/approvals', [
        'commentid'  => 1,
        'ispositive' => 2,
    ]);
    $response->assertStatus(422);
    expect($response['errors'])->toHaveKeys(['ispositive', 'commentid']);
});

it('user can approve a comment', function () {
    $user = User::factory(['is_banned' => false])->create();
    // Create a question with a comment
    $question = Question::factory()->create(['is_moderated' => true]);
    $comment = QuestionComment::factory()->create([
                                                      'question_id' => $question->id,
                                                  ]);
    $response = $this->actingAs($user)->postJson('/api/approvals', [
        'commentid'  => $comment->id,
        'ispositive' => 1,
    ]);
    $response->assertStatus(200);
    $response->assertJson([
                              'data' => [
                                  'ispositive'         => 1,
                                  'commentid'          => $comment->id,
                                  'approvals_count'    => 1,
                                  'disapprovals_count' => 0,
                              ]
                          ]);
});
