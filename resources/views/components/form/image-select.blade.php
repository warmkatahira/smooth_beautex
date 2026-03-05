<div class="flex flex-row">
    <label for="{{ $id }}" class="w-56 bg-black text-white py-2.5 pl-3 relative">
        {{ $label }}
        @if($required)
            <span class="absolute right-2 top-1/2 -translate-y-1/2 bg-white text-red-600 text-xs px-1.5 py-0.5 rounded">必須</span>
        @endif
    </label>
    <p id="image_file_name" class="pl-3 py-2.5 bg-white w-96 border border-black"></p>
    <label class="btn bg-theme-sub text-center border border-black ml-3 py-2.5 px-5 cursor-pointer">
        画像を選択
        <input type="file" id="image_file" name="image_file" class="hidden">
    </label>
</div>