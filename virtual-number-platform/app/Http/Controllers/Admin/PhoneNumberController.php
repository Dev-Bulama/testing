<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhoneNumber;
use App\Models\Provider;
use App\Services\Providers\ProviderManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhoneNumberController extends Controller
{
    public function __construct(protected ProviderManager $providerManager)
    {
    }

    public function index(): View
    {
        return view('admin.numbers.index', [
            'numbers' => PhoneNumber::with(['provider', 'customer'])->paginate(),
            'providers' => Provider::all(),
        ]);
    }

    public function create(): View
    {
        return view('admin.numbers.create', [
            'providers' => Provider::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'provider_id' => ['required', 'exists:providers,id'],
            'number' => ['required', 'string'],
            'country' => ['required', 'string', 'max:3'],
            'cost' => ['required', 'numeric', 'min:0'],
            'capabilities' => ['nullable', 'array'],
        ]);

        PhoneNumber::create($validated + ['status' => PhoneNumber::STATUS_AVAILABLE]);

        return redirect()->route('admin.numbers.index')->with('status', 'Number added');
    }

    public function edit(PhoneNumber $number): View
    {
        return view('admin.numbers.edit', [
            'phoneNumber' => $number,
            'providers' => Provider::all(),
        ]);
    }

    public function update(Request $request, PhoneNumber $number): RedirectResponse
    {
        $validated = $request->validate([
            'provider_id' => ['required', 'exists:providers,id'],
            'status' => ['required', 'string'],
            'cost' => ['required', 'numeric', 'min:0'],
            'capabilities' => ['nullable', 'array'],
        ]);

        $number->update($validated);

        return redirect()->route('admin.numbers.index')->with('status', 'Number updated');
    }

    public function destroy(PhoneNumber $number): RedirectResponse
    {
        if ($number->provider) {
            $this->providerManager->releaseNumber($number->provider, $number);
        }

        $number->delete();

        return back()->with('status', 'Number removed');
    }

    public function fetch(Request $request): RedirectResponse
    {
        $provider = Provider::findOrFail($request->input('provider_id'));
        $filters = $request->only(['country']);
        $numbers = $this->providerManager->fetchNumbers($provider, $filters);

        foreach ($numbers as $payload) {
            PhoneNumber::updateOrCreate([
                'number' => $payload['number'],
                'provider_id' => $provider->id,
            ], [
                'country' => $payload['country'] ?? 'US',
                'capabilities' => $payload['capabilities'] ?? [],
                'status' => PhoneNumber::STATUS_AVAILABLE,
                'external_id' => $payload['external_id'] ?? null,
                'cost' => $payload['cost'] ?? ($provider->configuration['default_cost'] ?? 0),
            ]);
        }

        return back()->with('status', 'Numbers synced');
    }
}
