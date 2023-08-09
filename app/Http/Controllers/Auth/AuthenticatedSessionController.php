<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'auth')]
class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    #[OA\Post(path: '/login', description: 'Endpoint for login,
    you should first make a request to the /sanctum/csrf-cookie endpoint to initialize CSRF protection for the application'
        , tags: ['auth'])]
    #[OA\RequestBody(content: [
        new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(properties: [
            new OA\Property(property: 'email', description: 'Email', type: 'string'),
            new OA\Property(property: 'password', description: 'Password', type: 'string'),
        ]
            , example: [
                '{"email": "test@mail.com", "password": "123456"}',
            ],))
    ])]
    #[OA\Response(response: 204, description: 'Successful login')]
    #[OA\Response(response: 422, description: 'These credentials do not match our records')]
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    #[OA\Post(path: '/logout', description: 'Endpoint for login', security: ["sanctum"], tags: ['auth'])]
    #[OA\Response(response: 204, description: 'Successful logout')]
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
