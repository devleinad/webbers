<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class AskQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (Auth::check()) ? true : false;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "post_title" => "required|string|max:200",
            "post_content" => "required",
            "post_tag" => "required",
            'bounty' => "",
        ];
    }

    public function messages()
    {
        return [
            "post_title.required" => "You must give a title to this post!",
            "post_title.string" => "The title of your post must be characters!",
            "post_title.max:200" => "The length of your title cannot exceed 100 characters!",
            "post_content.required" => "You must tell us somthing about your post!",
            "post_tag.required" => "You have to give this post a tag!",


        ];
    }
}