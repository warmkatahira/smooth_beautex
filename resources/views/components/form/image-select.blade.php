@props([
    'label',
    'required' => null,
])

<div class="flex flex-col bg-white py-2 px-3 w-form-div">
    <label class="text-gray-800 py-2.5 pl-3">
        {{ $label }}
        @if($required)
            <span class=" bg-pink-200 text-red-600 text-xs px-2 py-1 rounded">必須</span>
        @endif
    </label>
    <div class="flex flex-row items-center">
        <p id="image_file_name" class="px-3 h-10 py-2.5 bg-white w-full border border-gray-400 whitespace-nowrap overflow-x-auto disable_scrollbar"></p>
        <label class="btn bg-theme-sub text-center border border-gray-400 ml-3 py-2.5 px-5 cursor-pointer w-20">
            選択
            <input type="file" id="image_file" name="image_file" class="hidden">
        </label>
    </div>
</div>