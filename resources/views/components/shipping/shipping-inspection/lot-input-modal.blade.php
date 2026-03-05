<div id="lot_input_modal" class="lot_input_modal_close hidden fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full">
    <div class="relative top-32 mx-auto shadow-lg rounded-md w-modal-window">
        <!-- モーダルヘッダー -->
        <div class="flex flex-row items-center bg-theme-main text-black rounded-t-md px-4 py-2">
            <p>LOTを入力して下さい</p>
            <p id="lot_length"></p>
        </div>
        <!-- モーダルボディー -->
        <div class="p-10 bg-theme-body">
            <input type="text" id="lot" class="w-full" autocomplete="off">
            <!-- ボタン -->
            <div class="flex mt-10">
                <button type="button" class="lot_input_modal_close cursor-pointer btn bg-btn-cancel p-3 text-white w-full">キャンセル</button>
            </div>
        </div>
    </div>
</div>