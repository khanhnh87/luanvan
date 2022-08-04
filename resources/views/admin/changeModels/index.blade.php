@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.changeModel.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <label for="ten">Model hiện tại: </label>
                <ul>
                    @foreach ($files as $file)
                        <li><a href="{{ asset('storage/' . $file) }}"
                                target="_blank">{{ str_replace('models/', '', $file) }}</a></li>
                    @endforeach

                </ul>
                <span class="help-block"> </span>
            </div>
            <form method="POST" action="{{ route('admin.change-models.uploadModel') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="asdasd">File model mới</label>
                    <input type="file" name="files[]" class="form-control" multiple>
                </div>
                <div class="form-group">
                    <div class="alert alert-warning mt-1 mb-1">Lưu ý: Khi upload file mới sẽ xóa hết những file có sẵn trước
                        đó và không thể hoàn tác.</div>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit"
                        onclick="return confirm('Hành động này sẽ xóa hết những file có sẵn trước đó và không thể hoàn tác.\n Xác nhận thực hiện?')">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
