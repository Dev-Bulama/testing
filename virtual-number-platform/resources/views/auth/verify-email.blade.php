<x-guest-layout>
    <div class="space-y-4 text-center">
        <h2 class="text-xl font-semibold">Verify your email</h2>
        <p class="text-sm text-slate-500">Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you. If you didn't receive the email, we will gladly send you another.</p>
        @if (session('status') === 'verification-link-sent')
            <div class="rounded border border-green-500 bg-green-100 px-3 py-2 text-sm text-green-700">A new verification link has been sent to your email address.</div>
        @endif
        <form method="POST" action="{{ route('verification.send') }}" class="space-y-3">
            @csrf
            <x-button class="w-full">Resend Verification Email</x-button>
        </form>
    </div>
</x-guest-layout>
