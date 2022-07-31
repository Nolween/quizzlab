<?php

namespace Database\Seeders;

use App\Models\CommentApproval;
use App\Models\QuestionComment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CommentApproval::factory(100)->create();
        // Maintenant qu'on a de vrais réactions générées
        $comments = QuestionComment::all();
        foreach ($comments as $comment) {
            // Récupération des réctions utilisateur du commentaire
            $positiveApprovals = $comment->positiveApprovals->count();
            $negativeApprovals = $comment->negativeApprovals->count();
            $comment->approvals_count = $positiveApprovals;
            $comment->disapprovals_count = $negativeApprovals;
            $comment->save();
        }
    }
}
