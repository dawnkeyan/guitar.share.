<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'comment_yuepus',
        'comment_videos',
        'upload',
        'comment_reply',
        'comment',
        'change_status_comment',
        'private_letter',
    ];
}
