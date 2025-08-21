<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;


class UserController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->input('search');
        $sortField = $request->input('sortField');
        $sortDirection = $request->input('sortDirection', 'asc');
        $activeStates = $request->input('activeStates', []);
        $lastDays = $request->input('lastDays', '30');
        $showDeleted = filter_var($request->input('showDeleted', 'true'), FILTER_VALIDATE_BOOLEAN);


        $query = User::query()->select('users.*')->with('roles');
        if ($showDeleted == true) {
            $query->where('active', 1);
        } else {
            $query->where('active', 0);
        }

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->WhereRaw('name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('last_name LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('date_of_birth LIKE ?', ['%' . $search . '%'])
                ;
            });
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest('users.updated_at')
                ->latest('users.created_at');
        }
        if ($lastDays) {
            if (is_numeric($lastDays)) {

                $dateFrom = Carbon::now()->subDays((int) $lastDays)->startOfDay();
                $query->where('created_at', '>=', $dateFrom);
            } else {

                if ($lastDays === 'month') {
                    $dateFrom = Carbon::now()->startOfMonth();
                    $query->where('created_at', '>=', $dateFrom);
                } elseif ($lastDays === 'year') {
                    $dateFrom = Carbon::now()->startOfYear();
                    $query->where('created_at', '>=', $dateFrom);
                }
            }
        }

        $users = $query->orderByDesc('created_at')->paginate(10);
        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => [
                'search' => $search,
                'sortField' => $sortField,
                'sortDirection' => $sortDirection,
                'activeStates' => $activeStates,
                'lastDays' => $lastDays,
                'showdeleted' => $showDeleted,

            ],
        ]);
    }
    public function create()
    {
        return Inertia::render("Users/Create");
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'last_name' => 'required|string|max:100',
                'specialty' => 'required|string|max:100',
                'phone_number' => 'required|string|max:100',
                'position' => 'required|string|max:100',
                'DNI' => 'required|string|max:255',
                'date_of_birth'        => 'required|date|before:today',
                'role' => 'required|string|in:admin,doctor,patient,staff',
            ]);

            $validated['active'] = true;
            $user = User::create($validated);
            $user->assignRole($validated['role']);
            return redirect()->route('users.index')->with('toast', 'Usuario registrado correctamente.');
        } catch (ValidationException $e) {
            return back()
                ->with('toast', 'Ocurrió un error. ' . $e->getMessage())
                ->with('toastStyle', 'danger');
        }
    }
    public function update(User $user, Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'address.country' => 'nullable|string|max:255',
            'address.city' => 'nullable|string|max:255',
            'address.state' => 'nullable|string|max:255',
            'address.street' => 'nullable|string|max:255',
            'address.postal_code' => 'nullable|string|max:20',
        ]);

        // Actualizar datos básicos
        $user->update($request->except('address', 'avatar'));

        // Manejar avatar si se proporciona
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();
        }

        // Actualizar dirección
        if ($request->has('address')) {
            $user->address()->updateOrCreate([], $request->address);
        }

        return redirect()->back()->with('success', 'Usuario actualizado correctamente');
    }
    public function show(User $user)
    {
        $user = User::find($user->id);
        $user->load(['roles']);
        return Inertia::render('Users/Show', [
            'user' => $user
        ]);
    }
}
