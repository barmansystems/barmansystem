<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<style>
    #products_table input, #products_table select{
        width: auto;
    }
    .title-sec{
        background: #ececec;
        font-size: 20px !important;
    }
    .main-content{
        margin: 0 !important;
    }

    body{
        padding: 0;
        text-align: center !important;
    }

    main{
        padding: 0 !important;
    }

    table{
        width: 100% !important;
        /*border-collapse: separate !important;*/
    }

    .table{
        width: 100% !important;
        border-collapse: collapse !important;
    }

    .table th:not(.title-sec), .table td:not(.title-sec){
        padding: 4px !important;
        border: 2px solid #000 !important;
        font-size: 18px !important;
        text-align: center !important;
    }

    tbody tr td {
        padding-top: 12px !important;
        padding-bottom: 12px !important;
        font-size: 18px !important;
    }
    /*.table tr{*/
    /*    padding: 0 !important;*/
    /*    border: 2px solid #000 !important;*/
    /*    text-align: center !important;*/
    /*}*/

    #printable_sec{
        padding: 0;
    }

    .card{
        margin: 0;
    }

    .guide_box{
        text-align: center;
    }

    *{
        color: #000 !important;
    }

    .btn, .fa {
        color: #fff !important
    }

    .table:not(.table-bordered) td {
        line-height: 1;
    }

    .content-page {
        height: 100% !important
    }
</style>
    <form action="" method="post">
        <div class="form-row">
            <table class="table table-bordered mb-0">
                <thead>
                <tr>
                    <th class="text-center p-0 title-sec">مشخصات فرستنده</th>
                </tr>
                </thead>
                <tbody>
                <tr style="padding-top: 2rem">
                    <td>
                        <img src="{{ public_path('assets/images/logo-dark.png') }}" style="width: 200px">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><strong>نام فرستنده:</strong> بازرگانی پرسو تجارت ایرانیان</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><strong>نشانی:</strong> خیابان کریمخان، خیابان ایرانشهر، پلاک 242، طبقه پنجم</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><strong>کد پستی:</strong> 1584745337</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span><strong>شماره تلفن:</strong> 02188867100</span>
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="table table-bordered mb-5" style="margin-top: 2rem">
                <thead>
                <tr>
                    <th class="text-center p-0 title-sec">مشخصات گیرنده</th>
                </tr>
                </thead>
                <tbody>
                    <tr style="padding-top: 2rem">
                        <td>
                            <span><strong>نام گیرنده:</strong> {{ $packet->receiver }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span><strong>نشانی:</strong> {{ $packet->address }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span><strong>کد پستی:</strong> {{ $packet->invoice->customer->postal_code }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span><strong>شماره تلفن:</strong> {{ $packet->invoice->customer->phone1 }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</body>
</html>

