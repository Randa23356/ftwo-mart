<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectTo();
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $this->redirectTo();
    }

    /**
     * Redirect user to appropriate dashboard based on role.
     */
    protected function redirectTo(): RedirectResponse
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard')->with('verified', true);
        }

        if ($user->hasRole('operator')) {
            return redirect()->route('operator.dashboard')->with('verified', true);
        }

        return redirect()->route('home')->with('verified', true);
    }
}
