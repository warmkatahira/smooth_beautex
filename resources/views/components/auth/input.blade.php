<div class="mt-2">
    <label for="{{ $id }}" class="text-sm">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $id }}" name="{{ $id }}" class="text-sm block mt-1 w-full rounded-lg" value="{{ old($id, $db) }}" autocomplete="off">
</div>