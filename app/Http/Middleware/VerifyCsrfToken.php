<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/razorpay-success',
         '/paytm/*',
         '/payu_success',
         '/payu_fail',
         '/app_payu_success',
         '/app_payu_failed',
         '/cashfree/success',
         '/coingate/callback',
         '/sslcommerz/*',
         '/cinetpay/*',
    ];
}
