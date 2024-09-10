<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="{{ route('processPdf') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="pdf">Choose PDF file:</label>
    <input type="file" id="pdf" name="pdf" accept="application/pdf" required>
    <button type="submit">Upload and Process</button>
</form>
</body>
</html>
