@props(['type' => 'text'])
<input type="{{ $type }}" {{ $attributes->merge(['class' => 'mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500']) }}>
