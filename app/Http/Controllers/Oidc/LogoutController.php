<?php

namespace App\Http\Controllers\Oidc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    //
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()?->invalidate();
        $request->session()?->regenerateToken();

        if ($token = $request->user()?->token()) {
            $token->revoke();
        }
        $redir = $request->input('post_logout_redirect_uri');
        return $redir ? redirect()->away($redir) : response()->noContent();
    }
}
