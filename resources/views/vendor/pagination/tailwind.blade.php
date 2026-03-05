<!-- ページが複数ある場合 -->
@if($paginator->hasPages())
    <div class="flex">
        <!-- 件数情報の表示部分 -->
        <div class="ml-auto">
            <p class="text-sm text-gray-700 pt-4">
                全
                {{ $paginator->total() }}
                件 /
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                ～
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                件目
            </p>
        </div>
        <!-- ページネーション部分 -->
        <span class="relative z-0 inline-flex shadow-sm rounded-md ml-3">
            <!-- 前ページ矢印ボタン -->
            <!-- 現在のページの前がある場合 -->
            @if($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="bg-white relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-600 border border-gray-300 cursor-default rounded-l-md leading-5" aria-hidden="true">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </span>
            <!-- 現在のページの前がない場合 -->
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="bg-white relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-600 border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
            <!-- それぞれのページへのリンク -->
            {{-- 4よりもページ数が多い時 --}}
            @if($paginator->lastPage() > 4)
                {{-- 現在ページが表示するリンクの中心位置よりも左の時 --}}
                @if($paginator->currentPage() <= floor(4 / 2))
                    <?php $start_page = 1; //最初のページ ?> 
                    <?php $end_page = 4; ?>
                {{-- 現在ページが表示するリンクの中心位置よりも右の時 --}}
                @elseif($paginator->currentPage() > $paginator->lastPage() - floor(4 / 2))
                    <?php $start_page = $paginator->lastPage() - (4 - 1); ?>
                    <?php $end_page = $paginator->lastPage(); ?>
                {{-- 現在ページが表示するリンクの中心位置の時 --}}
                @else
                    <?php $start_page = $paginator->currentPage() - (floor((4 % 2 == 0 ? 4 - 1 : 4)  / 2)); ?>
                    <?php $end_page = $paginator->currentPage() + floor(4 / 2); ?>
                @endif
            {{-- 定数よりもページ数が少ない時 --}}
            @else
                <?php $start_page = 1; ?>
                <?php $end_page = $paginator->lastPage(); ?>
            @endif
            {{-- 処理部分 --}}
            @for ($i = $start_page; $i <= $end_page; $i++)
                @if($i == $paginator->currentPage())
                    <span aria-current="page">
                        <span class="bg-theme-main relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-600 border border-gray-300 cursor-default leading-5">{{ $i }}</span>
                    </span>
                @else
                    <a href="{{ $paginator->url($i) }}" class="bg-white relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-600 border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $i]) }}">
                        {{ $i }}
                    </a>
                @endif
            @endfor
            <!-- 次ページ矢印ボタン -->
            <!-- 現在のページの次がある場合 -->
            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="bg-white relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-600 border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            <!-- 現在のページの次がない場合 -->
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="bg-white relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-600 border border-gray-300 cursor-default rounded-r-md leading-5" aria-hidden="true">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </span>
            @endif
        </span>
    </div>
<!-- ページが1つの場合 -->
@else
    <div class="flex">
        <div class="ml-auto">
            <p class="text-sm text-gray-700 pt-4">
                全
                {{ $paginator->total() }}
                件
            </p>
        </div>
    </div>
@endif