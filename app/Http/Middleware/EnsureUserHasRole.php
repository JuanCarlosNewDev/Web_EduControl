<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if ($role === User::ROLE_ADMIN && $user && ! $user->isAdmin()) {
            return to_route('attendances.index')
                ->with('error', 'El panel general es exclusivo de Administración. Has ingresado al espacio de asistencia de tus cursos.');
        }

        abort_unless($user?->role === $role, 403);

        return $next($request);
    }
}
