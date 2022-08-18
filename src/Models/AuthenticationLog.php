<?php

namespace Codebyray\LaravelAuthLog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuthenticationLog extends Model
{
    public $timestamps = false;

    protected $table = 'auth_log';

    protected $fillable = [
        'ip_address',
        'user_agent',
        'login_at',
        'login_successful',
        'logout_at',
        'cleared_by_user',
        'location',
    ];

    protected $casts = [
        'cleared_by_user' => 'boolean',
        'location' => 'array',
        'login_successful' => 'boolean',
    ];

    protected $dates = [
        'login_at',
        'logout_at',
    ];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('auth-log.db_connection'));
        }

        parent::__construct($attributes);
    }

    public function getTable()
    {
        return config('auth-log.table_name', parent::getTable());
    }

    public function authenticatable(): MorphTo
    {
        return $this->morphTo();
    }
}
