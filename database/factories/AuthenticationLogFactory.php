<?php

namespace Codebyray\LaravelAuthLog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Codebyray\LaravelAuthLog\Models\AuthenticationLog;

class AuthenticationLogFactory extends Factory
{
    protected $model = AuthenticationLog::class;

    public function definition()
    {
        return [
        ];
    }
}
