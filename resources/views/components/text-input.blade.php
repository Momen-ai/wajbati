@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-3 px-3 py-2 text-light shadow-sm', 'style' => 'background-color: var(--surface-2); color: var(--text-main); border: 1px solid var(--border-subtle); outline: none;']) }}>
