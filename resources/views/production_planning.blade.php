<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form action="{{ route('production_planning.store') }}" method="POST">
        @csrf
        @foreach ($product as $data)
            <label for="{{ $data->code }}">{{ $data->name }}</label>
            <input type="number" id="{{ $data->code }}" name="qty[{{ $data->id }}]" value="0">
            <br>
        @endforeach
        <button type="submit">Kirim</button>
</body>

</html>
