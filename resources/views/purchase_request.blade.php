<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form action="{{ route('purchase_request.store') }}" method="POST">
        @csrf
        <label for="vendor">Nama Vendor</label>
        <input type="text" placeholder="Vendor" id="vendor" name="vendor">
        <button type="submit">Kirim</button>
    </form>
</body>

</html>
