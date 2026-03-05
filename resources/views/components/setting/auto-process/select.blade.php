@props([
    'label',
    'name',
    'class' => null,
    'items',
    'index' => null,
    'value' => null,
])

<p class="pt-2 px-3 bg-theme-main text-center border-y border-l border-black w-4/12">{{ $label }}</p>
<select name="{{ $name }}[]" class="text-xs w-8/12 {{ $class }}">
    <option value=""></option>
    @foreach($items as $key => $item)
        <option value="{{ $key }}" @if(old("operator.$index", $value ?? '') == $key) selected @endif>{{ $item }}</option>
    @endforeach
</select>