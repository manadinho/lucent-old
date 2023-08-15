<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $req): View
    {
        if(auth()->user()) {
            Auth::logout();
        }
        
        return view('auth.register',[
            'userEmail' => $req->email, 
            'token'=>$req->token
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $invitation = DB::table('invitation')->where('token', $request->token)->first();

        if (!$invitation) {
            return abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $this->updateUser($invitation, $request);

        $this->destroyInvitationLink();
                
        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Update a user's information based on an invitation and request data.
     *
     * @param \App\Models\Invitation $invitation The invitation instance.
     * @param \Illuminate\Http\Request $request The request instance containing updated user data.
     *
     * @return \App\Models\User The updated user instance.
     */
    private function updateUser($invitation, $request)
    {
        $user = User::find($invitation->user_id);

        $user->update([
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);

        return $user;
    }

    /**
     * Destroy the invitation link based on the provided token.
     *
     * @return void
     */
    private function destroyInvitationLink(): void 
    {
        DB::table('invitation')->where('token',request()->token)->delete();
    }
}
