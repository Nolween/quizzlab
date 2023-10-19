<?php

namespace Database\Seeders;

use App\Models\CommentApproval;
use App\Models\QuestionComment;
use Illuminate\Database\Seeder;

class CommentApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CommentApproval::factory(100)->create();
        // Maintenant qu'on a de vrais réactions générées
        $comments = QuestionComment::all();
        foreach ($comments as $comment) {
            // Récupération des réactions utilisateur du commentaire
            $positiveApprovals = $comment->positiveApprovals->count();
            $negativeApprovals = $comment->negativeApprovals->count();
            $comment->approvals_count = $positiveApprovals;
            $comment->disapprovals_count = $negativeApprovals;
            $comment->save();
        }
    }
}
