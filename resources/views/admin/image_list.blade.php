@extends('admin/blade/master')

@section('source_css')
    <link rel="stylesheet" href="{{ asset('dist/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/dataTables.dateTime.min.css') }}">
@endsection
@section('source_js')
    <script src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/dataTables.dateTime.min.js') }}"></script>
    <script src="{{ asset('dist/js/sortable.js') }}"></script>
    <script src="{{ asset('dist/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dist/js/new_moment.min.js') }}"></script>
@endsection

@section('main_section')
<div class="container-fluid">
    <h1 class="mb-4">圖片列表</h1>
    <div class="mb-4">
        <form action="{{ route('admin.uploadImage') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group">
                <input type="file" name="image" class="form-control" required>
                <button type="submit" class="btn btn-primary">上傳圖片</button>
            </div>
        </form>
    </div>
    <div class="row">
        @if(count($images) > 0)
            @foreach($images as $image)
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-4">
                    <div class="card">
                        <div class="image-container copy-path" data-clipboard-text="img/{{ basename($image) }}">
                            <img src="{{ $image }}" class="card-img-top" alt="圖片">
                            <div class="overlay">
                                <span class="overlay-text">點擊複製檔名</span>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <p class="card-text copy-path" data-clipboard-text="img/{{ basename($image) }}" style="cursor: pointer;">
                                img/{{ basename($image) }}
                            </p>
                            <button class="btn btn-danger btn-sm delete-image" data-image-path="img/{{ basename($image) }}">刪除</button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    尚未上傳任何圖片。
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('main_css')
<style>
    textarea, input, label ,.form-control,.modal h5{
        color: black !important;
    }

    /* 讓表格文字自動換行 */
    #insurance_table td {
        word-wrap: break-word;
        white-space: normal;
    }

    /* 可選：限制表格列的最大寬度 */
    #insurance_table td:nth-child(3) { /* 第三列（保險內容） */
        max-width: 300px; /* 可根據需要調整 */
    }
    ::selection {
        background: #7ac0ec !important;
        color: black;
    }
    table{
        text-align: center;
    }
    tr,th,td{
        border: 1px solid #000 !important;
    }

    /* 統一圖片的高寬度，並保持比例縮放 */
    .card-img-top {
        width: 100%; /* 設置寬度為卡片的 100% */
        height: 250px; /* 固定高度 */
        /* object-fit: cover; 保持圖片比例並裁剪多餘部分 */
    }

    /* 讓卡片內容居中 */
    .card {
        text-align: center;
    }

    /* 圖片容器 */
    .image-container {
        position: relative;
        width: 100%;
        height: 250px; /* 固定高度 */
        overflow: hidden;
    }

    /* 圖片樣式 */
    .card-img-top {
        width: 100%;
        height: 100%;
        object-fit: cover; /* 保持比例並裁剪多餘部分 */
        transition: transform 0.3s ease; /* 添加縮放效果 */
    }

    /* 滑過時圖片縮放 */
    .image-container:hover .card-img-top {
        transform: scale(1.1); /* 放大圖片 */
    }

    /* 遮罩層 */
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8); /* 半透明黑色 */
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0; /* 初始隱藏 */
        transition: opacity 0.3s ease; /* 平滑過渡 */
    }

    /* 滑過時顯示遮罩層 */
    .image-container:hover .overlay {
        cursor: pointer;
        opacity: 1;
    }

    /* 遮罩層文字 */
    .overlay-text {
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        user-select: none;
    }

</style>
@endsection

@section('main_js')
<script>
    $(document).ready(function () {
        // 點擊複製圖片路徑
        $(document).on('click', '.copy-path', function () {
            console.log('click copy-path')
            const textToCopy = $(this).data('clipboard-text');

            // 使用 Clipboard API 複製文字
            navigator.clipboard.writeText(textToCopy).then(function () {
                showToast('檔名複製成功', 'success');
            }).catch(function () {
                showToast('複製失敗，請稍後再試。', 'error');
            });
        });

        // 點擊刪除圖片
        $(document).on('click', '.delete-image', function () {
            const imagePath = $(this).data('image-path');

            if (confirm(`確定要刪除此圖片嗎？\n( ${imagePath} )`)) {
                $.ajax({
                    url: '{{ route('admin.deleteImage') }}',
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: JSON.stringify({ image_path: imagePath }),
                    contentType: 'application/json',
                    success: function (data) {
                        if (data.success) {
                            location.reload();
                        } else if (data.error) {
                            showToast(data.error, 'error');
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 400 && xhr.responseJSON.error) {
                            showToast(xhr.responseJSON.error, 'error');
                        } else {
                            showToast('刪除失敗，請稍後再試。', 'error');
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
