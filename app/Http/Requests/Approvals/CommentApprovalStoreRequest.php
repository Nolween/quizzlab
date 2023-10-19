<?php

namespace App\Http\Requests\Approvals;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CommentApprovalStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
            'ispositive' => ['boolean', 'nullable'],
            'commentid' => ['integer', 'exists:question_comments,id', 'required'],
        ];
    }
}
