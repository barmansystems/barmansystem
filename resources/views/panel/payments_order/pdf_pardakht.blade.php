<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    body {
        position: relative;
    }

    .box1 {
        position: absolute;
        top: 15rem;
        right: 3rem;
        font-size: 1.2rem;
    }

    .box2 {
        position: absolute;
        top: 18rem;
        right: 3rem;
        font-size: 1.2rem;
    }

    .box3 {
        position: absolute;
        top: 21rem;
        right: 3rem;
        font-size: 1.2rem;
    }

    .box4 {
        position: absolute;
        top: 24rem;
        right: 3rem;
        font-size: 1.2rem;
    }

    .box5 {
        position: absolute;
        top: 27rem;
        right: 3rem;
        font-size: 1.2rem;
    }

    .box6 {
        position: absolute;
        top: 30rem;
        right: 3rem;
        font-size: 1.2rem;
    }

    .box7 {
        position: absolute;
        top: 32rem;
        right: 3rem;
        font-size: 1.2rem;
    }

    .box8 {
        position: absolute;
        top: 6.2rem;
        left: 2.7rem;
        font-size: 1.2rem;
    }

    .box9 {
        position: absolute;
        top: 3.6rem;
        left: 4rem;
        font-size: 1.2rem;
    }
</style>
<body>

<div class="box9">{{englishToPersianNumbers($orderPayment->number)}} </div>
<div class="box8">{{englishToPersianNumbers($date)}}</div>


<div class="box1"> لطفا مبلغ :{{englishToPersianNumbers(number_format($orderPayment->amount))}} ریال</div>
<div class="box2"> به حروف :{{$orderPayment->amount_words}} ریال</div>
<div class="box3"> بابت :{{$orderPayment->for}}</div>
<div class="box4"> به شرکت / خانم / آقا:{{$orderPayment->to}}</div>
<div class="box5"> طی فاکتور شماره :{{englishToPersianNumbers($orderPayment->invoice_number??0)}} پرداخت گردد.</div>
@if($orderPayment->is_online_payment == 1)
    <div class="box6">پرداخت اینترنتی سایت :{{$orderPayment->site_name??'--'}} </div>
@else
    <div class="box6"> شماره حساب / شبا :{{englishToPersianNumbers($orderPayment->bank_number??'--')}} </div>
    <div class="box7"> بانک :{{$orderPayment->bank_name??'--'}} </div>
@endif
</body>
</html>
