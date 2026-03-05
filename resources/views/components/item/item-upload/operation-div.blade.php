<form method="POST" action="{{ route('item_upload.upload') }}" id="item_upload_form" enctype="multipart/form-data" class="m-0">
    @csrf
    <div class="flex flex-row gap-5">
        <div class="flex flex-row">
            <label for="upload_target" class="px-10 bg-black text-white py-2">対象</label>
            <select id="upload_target" name="upload_target" class="text-sm w-32">
                @foreach(ItemUploadEnum::UPLOAD_TARGET_LIST as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-row">
            <label for="upload_type" class="px-10 bg-black text-white py-2">タイプ</label>
            <select id="upload_type" name="upload_type" class="text-sm w-32">
                @foreach(ItemUploadEnum::UPLOAD_TYPE_LIST as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex select_file">
            <label class="btn text-sm bg-btn-enter text-white py-2 px-5">
                アップロード
                <input type="file" name="select_file" class="hidden">
            </label>
        </div>
    </div>
</form>