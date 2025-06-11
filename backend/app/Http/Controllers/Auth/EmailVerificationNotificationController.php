<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            // Redirect based on the user's role
            switch ($request->user()->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'user':
                    return redirect()->route('userDashboard');
                case 'coach':
                    return redirect()->route('coach.dashboard');
                default:
                    return redirect()->route('homepage'); // Fallback for unrecognized roles
            }
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['status' => 'verification-link-sent']);
    }
}
