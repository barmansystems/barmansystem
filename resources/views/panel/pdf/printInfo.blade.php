<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

</head>
<style>
    body {
        font-size: 1.3rem;
    }

    table {
        width: 80%;
        border-collapse: collapse;
        text-align: center;
        border: 4px solid black;
        margin: 0 auto;
    }

    th, td {
        border: 1px solid black;
        padding: 10px;
    }

    th {
        background-color: #f2f2f2;
        text-align: center;
    }

    .col1 {
        width: 5%;
    }

    .col2 {
        width: 10%;
    }

    .col3 {
        width: 60%;
    }

    .text-right {
        text-align: right;
    }
</style>

<body>
<table>
    <thead>
    <tr>
        <th class="col1">ردیف</th>
        <th class="col2">شرح</th>
        <th class="col3">اطلاعات</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($allData as $key => $value)
        <tr>
            <td class="col1">{{ englishToPersianNumbers($loop->iteration)}}</td>
            <td class="col2 text-right">{{ dataInfoName($key) }}</td>
            <td class="col3 text-right">{{ $value }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
