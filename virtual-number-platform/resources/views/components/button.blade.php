<button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition']) }}>
    {{ $slot }}
</button>
