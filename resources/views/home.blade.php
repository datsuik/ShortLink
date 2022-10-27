<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ShortLink demo</title>
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <h1 class="mb-4">Короткие ссылки</h1>

            @if(session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif              

            <form action="{{ url('/') }}" enctype="multipart/form-data" class="row mb-5" method="post" accept-charset="utf-8">  
                @csrf
                <div class="col-md-6">
                    <div class="row mb-4">

                        <div class="col-md-6">
                            <label for="link" class="form-label">Ссылка</label>
                            <input type="text" 
                                   name="link" 
                                   id="link" 
                                   class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" 
                                   value="{{ old('link') }}">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror              
                        </div>

                        <div class="col-md-3">
                            <label for="limit" class="form-label">Лимит</label>
                            <input type="number" 
                                   name="limit" 
                                   id="limit" 
                                   min="0" 
                                   max="100"
                                   class="form-control" 
                                   value="{{ old('limit', '0') }}">
                        </div>

                        <div class="col-md-3">
                            <label for="limit" class="form-label">Время жизни</label>
                            <div class="input-group mb-3">
                                <input type="number" 
                                   name="time" 
                                   id="time" 
                                   oninput="maxLengthCheck(this)"
                                   maxlength="2"
                                   min="1" 
                                   max="24"
                                   class="form-control" 
                                   value="{{ old('time', '1') }}">
                                <span class="input-group-text">ч.</span>
                            </div>
                        </div>

                    </div>

                    <input type="submit" class="btn btn-primary" value="Сохранить" />
                </div>
            </form>



            <table class="table table-sm">
                <thead>
                    <tr>
                        <th width="40">ID</th>
                        <th>Короткая ссылка</th>
                        <th>Ссылка</th>
                        <th width="220">Время</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($links as $row)
                        @php 
                        $time_to = date("Y-m-d H:i:s", strtotime(sprintf("+%d hours", $row->url_time), strtotime($row->created_at)));
                        @endphp
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td><a href="{{ route('link', $row->token) }}" target="_blank">{{ route('link', $row->token) }}</a></td>
                            <td>{{ $row->link }}</td>
                            <td>до {{ $time_to }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table> 


        </div>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script>
function OnlyInt(input) {
    input.value=input.value.replace(new RegExp(/[^0-9]+/gi),"");
}
function maxLengthCheck(o) {
    if (o.value.length > o.maxLength) {
        o.value = o.value.slice(0, o.maxLength)
    }
}
</script>
</body></html>