<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProviderController extends Controller
{
    public function index(): View
    {
        return view('admin.providers.index', [
            'providers' => Provider::paginate(),
        ]);
    }

    public function create(): View
    {
        return view('admin.providers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'api_key' => ['nullable', 'string'],
            'api_secret' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
            'configuration' => ['nullable', 'array'],
        ]);

        $validated['configuration'] = collect($request->input('configuration', []))
            ->reject(fn ($value) => $value === null || $value === '')
            ->all();

        Provider::create($validated);

        return redirect()->route('admin.providers.index')->with('status', 'Provider created');
    }

    public function edit(Provider $provider): View
    {
        return view('admin.providers.edit', compact('provider'));
    }

    public function update(Request $request, Provider $provider): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'api_key' => ['nullable', 'string'],
            'api_secret' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
            'configuration' => ['nullable', 'array'],
        ]);

        $validated['configuration'] = collect($request->input('configuration', []))
            ->reject(fn ($value) => $value === null || $value === '')
            ->all();

        $provider->update($validated);

        return redirect()->route('admin.providers.index')->with('status', 'Provider updated');
    }

    public function destroy(Provider $provider): RedirectResponse
    {
        $provider->delete();

        return back()->with('status', 'Provider removed');
    }
}
