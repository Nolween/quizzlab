<?php

namespace App\Http\Requests\Comments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class QuestionCommentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'comment' => ['required', 'string'  ],
            'questionid' => ['required', 'integer', 'exists:questions,id'],
            'commentreplyid' => ['nullable', 'integer', 'exists:question_comments,id'],
            'commentid' => ['required', 'integer', 'exists:question_comments,id'],
        ];
    }
}
