<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="google" content="notranslate">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Cropper.js -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- jQuery UI -->
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-darkness/jquery-ui.css">

        <!-- favicon -->
        <link rel="shortcut icon" href="{{ asset('image/favicon.svg') }}">

        <!-- Styles -->
        @vite([
            'resources/css/app.css',
            'resources/sass/common.scss',
            'resources/sass/theme.scss',
            'resources/sass/navigation.scss',
            'resources/sass/loading.scss',
            'resources/sass/dropdown.scss',
            'resources/sass/profile/profile_image.scss',
            'resources/sass/height_adjustment.scss',
        ])

        <!-- Scripts -->
        @vite([
            'resources/js/app.js',
            'resources/js/loading.js',
            'resources/js/navigation.js',
            'resources/js/dropdown.js',
            'resources/js/search.js',
            'resources/js/search_date.js',
            'resources/js/image_fade_in.js',
            'resources/js/file_select.js',
            'resources/js/checkbox.js',
        ])

        <!-- Select2 -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

        <!-- LINE AWESOME -->
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Kosugi+Maru&family=Oleo+Script:wght@400;700&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">

        <!-- Lordicon -->
        <script src="https://cdn.lordicon.com/pzdvqjsp.js"></script>

        <!-- toastr.js -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <!-- Tippy.js -->
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tippy.js/6.3.7/themes/light.min.css" integrity="sha512-zpbTFOStBclqD3+SaV5Uz1WAKh9d2/vOtaFYpSLkosymyJKnO+M4vu2CK2U4ZjkRCJ7+RvLnISpNrCfJki5JXA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tippy.js/6.3.7/themes/light-border.min.css" integrity="sha512-DiG+GczLaoJczcpFjhVy4sWA1rheh0I6zmlEc+ax7vrq2y/qTg80RtxDOueLcwBrC80IsiQapIgTi++lcGHPLg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <!-- ナビゲーションメニュー -->
        @include('layouts.navigation')
        <div class="pl-20 pr-3 pt-3 mb-3">
            <!-- ページヘッダー -->
            <x-page-header />
            <!-- バリデーションエラー -->
            <x-validation-error-msg />
            <!-- アラート表示 -->
            <x-alert/>
            <!-- ローディング -->
            <x-loading />
            <!-- ページコンテンツ -->
            <main class="max-w-[2000px]">
                {{ $slot }}
            </main>
        </div>
        <!-- 画像拡大表示用モーダル -->
        <x-modal.image-fade-in-modal />
    </body>
</html>