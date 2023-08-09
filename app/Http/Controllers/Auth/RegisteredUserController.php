<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use OpenApi\Attributes as OA;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    #[OA\Post(path: '/register', description: 'Endpoint for registration,
    you should first make a request to the /sanctum/csrf-cookie endpoint to initialize CSRF protection for the application'
        ,tags: ['auth'])]
    #[OA\RequestBody(content: [
        new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(properties: [
            new OA\Property(property: 'first_name', description: 'First name', type: 'string'),
            new OA\Property(property: 'last_name', description: 'Last name', type: 'string'),
            new OA\Property(property: 'email', description: 'Email', type: 'string'),
            new OA\Property(property: 'password', description: 'Password', type: 'string'),
        ]
            , example: [
                '{"first_name": "Name", "last_name": "Surname", "email": "test@mail.com", "password": "123456"}',
            ],))
    ])]
    #[OA\Response(response: 204, description: 'Successful registration')]
    #[OA\Response(response: 422, description: 'An error occurred during registration, the error message is attached')]
    public function store(Request $request): Response
    {

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::min(6)],
        ]);

        $user = new User([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->save();

        //event(new Registered($user));
        //Auth::login($user);

        return response()->noContent();
    }
}
