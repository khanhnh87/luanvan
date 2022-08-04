@extends('layouts.admin')
@section('styles')
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, .8) url('http://i.stack.imgur.com/FhHRx.gif') 50% 50% no-repeat;
        }

        body.loading .modal {
            overflow: hidden;
        }

        body.loading .modal {
            display: block;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.performPrediction.title_singular') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="alert alert-warning mt-1 mb-1">Lưu ý: Thêm file chuẩn đoán để máy đưa ra kết quả trước khi lưu.
                </div>
            </div>
            <form method="POST" enctype="multipart/form-data" id="image-upload" action="javascript:void(0)">
                <div class="form-group">
                    <label for="image">File chuẩn đoán</label>
                    <input class="form-control " type="file" name="image" id="image" accept="image/*">
                    <span class="help-block"> </span>
                </div>
            </form>
            <div class="form-group">
                <img id="preview-image-before-upload" src="http://via.placeholder.com/512x512" alt="preview image"
                    width="512" height="512">
                <span class="help-block"> </span>
            </div>

            <form method="POST" action="{{ route('admin.perform-predictions.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="created_by_id" value="{{ auth()->user()->id }}">
                <div class="form-group">
                    <label for="ten">{{ trans('cruds.performPrediction.fields.ten') }}</label>
                    <input class="form-control {{ $errors->has('ten') ? 'is-invalid' : '' }}" type="text"
                        name="ten" id="ten" value="{{ old('ten', '') }}">
                    @if ($errors->has('ten'))
                        <div class="invalid-feedback">
                            {{ $errors->first('ten') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.performPrediction.fields.ten_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="hinh_anh">{{ trans('cruds.performPrediction.fields.hinh_anh') }}</label>
                    <input class="form-control {{ $errors->has('hinh_anh') ? 'is-invalid' : '' }}" type="text"
                        name="hinh_anh" id="hinh_anh" value="{{ old('hinh_anh', '') }}" required readonly>
                    @if ($errors->has('hinh_anh'))
                        <div class="invalid-feedback">
                            {{ $errors->first('hinh_anh') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.performPrediction.fields.hinh_anh_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required">{{ trans('cruds.performPrediction.fields.kqdd') }}</label>
                    <select class="form-control {{ $errors->has('kqdd') ? 'is-invalid' : '' }}" name="kqdd"
                        id="kqdd" required>
                        <option value disabled {{ old('kqdd', null) === null ? 'selected' : '' }}>
                            Tự động điền khi có kết quả chuẩn đoán</option>
                        @foreach (App\Models\PerformPrediction::KQDD_SELECT as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('kqdd', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-success" id="ConfidenceScore"></p>
                    @if ($errors->has('kqdd'))
                        <div class="invalid-feedback">
                            {{ $errors->first('kqdd') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.performPrediction.fields.kqdd_helper') }}</span>
                </div>
                <div class="form-group">
                    <label>{{ trans('cruds.performPrediction.fields.kqtt') }}</label>
                    <select class="form-control {{ $errors->has('kqtt') ? 'is-invalid' : '' }}" name="kqtt"
                        id="kqtt">
                        <option value disabled {{ old('kqtt', null) === null ? 'selected' : '' }}>
                            {{ trans('global.pleaseSelect') }}</option>
                        @foreach (App\Models\PerformPrediction::KQTT_SELECT as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('kqtt', '0') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('kqtt'))
                        <div class="invalid-feedback">
                            {{ $errors->first('kqtt') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.performPrediction.fields.kqtt_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger submit-button" type="submit" disabled>
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal"></div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest"></script>
    <script type="text/javascript">
        async function predict() {
            var imageName = "preview-image-before-upload";
            const model = await tf.loadLayersModel("{{ asset('storage/models/model.json') }}");
            var example = tf.browser.fromPixels(document.getElementById(imageName)).mean(2); // for example
            example = example.reshape([1, 512, 512, 1]);
            const output = model.predict(example);
            const axis = 1;
            const predictions = Array.from(output.dataSync());
            console.log(predictions[0]);
            var tmpRate = predictions[0] * 100;
            var rate = null;
            if (tmpRate < 50) {
                $("#kqdd").val(0).change();
                rate = 100 - tmpRate;
            } else {
                $("#kqdd").val(1).change();
                rate = tmpRate;
            }
            $("body").removeClass("loading");
            $('.submit-button').prop("disabled", false);
            document.getElementById('ConfidenceScore').innerHTML = 'Tỉ lệ tin cậy: ~' + rate.toString().substring(0,
                5) + '%';
        }
        $(document).ready(function(e) {
            $('#kqdd').css('pointer-events', 'none');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#image').change(function() {
                $("body").addClass("loading");
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-image-before-upload').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
                $('#image-upload').submit();
            });
            $('#image-upload').submit(function(e) {

                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.perform-prediction-demos.uploadImage') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        this.reset();
                        $("#hinh_anh").val(data);
                        predict();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });

            });
        });
    </script>
@endsection
