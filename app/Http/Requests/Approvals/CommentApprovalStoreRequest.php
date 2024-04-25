<?php

namespace App\Http\Requests\Approvals;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentApprovalStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('authorized-user');
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
