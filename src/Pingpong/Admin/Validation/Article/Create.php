<?php

namespace Pingpong\Admin\Validation\Article;

use Pingpong\Admin\Validation\Validator;

class Create extends Validator
{
    protected $rules = [
        'title' => 'required',
        'slug' => 'required|unique:articles,slug',
        'body' => 'required',
    ];

    public function rules()
    {
        return $this->rules;
    }
}
