<div id="lot_exp_input_modal" class="lot_exp_input_modal_close hidden fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full">
    <div class="relative top-32 mx-auto shadow-lg rounded-md w-modal-window">
        <!-- モーダルヘッダー -->
        <div class="flex flex-row items-center bg-theme-main text-black rounded-t-md px-4 py-2">
            <p>LOTを入力して下さい</p>
            <p id="lot_length"></p>
        </div>
        <!-- モーダルボディー -->
        <div class="p-10 bg-theme-body">
            <div class="flex flex-col">
                <label for="lot" class="text-base">LOT</label>
                <input type="text" id="lot" class="w-full" autocomplete="off">
            </div>
            <div class="flex flex-col mt-3">
                <label for="exp" class="text-base">EXP</label>
                <input type="text" id="exp" class="w-full" placeholder="yyyymm形式で入力" autocomplete="off">
            </div>
            <!-- ボタン -->
            <div class="flex justify-between mt-10">
                <button type="button" id="lot_exp_input_enter" class="cursor-pointer btn bg-btn-enter p-3 text-white w-1/3">確定</button>
                <button type="button" class="lot_exp_input_modal_close cursor-pointer btn bg-btn-cancel p-3 text-white w-1/3">キャンセル</button>
            </div>
        </div>
    </div>
</div>