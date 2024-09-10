<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Mpdf\Mpdf;
use PDF as PDF;

if (!function_exists('active_sidebar')) {
    function active_sidebar(array $items)
    {
        $route = Route::current()->uri;
        $data = [];

        foreach ($items as $value) {
            if ($value == 'panel') {
                $data[] = "panel";
            } else {
                $data[] = "panel/" . $value;
            }
        }
        if (in_array($route, $data)) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('make_slug')) {
    function make_slug(string $string)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
        return $slug;
    }
}

if (!function_exists('upload_file')) {
    function upload_file($file, $folder)
    {
        if ($file) {
            $filename = time() . $file->getClientOriginalName();
            $year = Carbon::now()->year;
            $month = Carbon::now()->month;
            $path = public_path("/uploads/{$folder}/{$year}/{$month}/");
            $file->move($path, $filename);
            $img = "/uploads/{$folder}/{$year}/{$month}/" . $filename;
            return $img;
        }
    }
}


if (!function_exists('upload_file')) {
    function upload_file($file, $folder)
    {
        if ($file) {
            $filename = time() . $file->getClientOriginalName();
            $year = Carbon::now()->year;
            $month = Carbon::now()->month;
            $path = public_path("/uploads/{$folder}/{$year}/{$month}/");
            $file->move($path, $filename);
            $img = "/uploads/{$folder}/{$year}/{$month}/" . $filename;
            return $img;
        }
    }
}
if (!function_exists('upload_file_factor')) {
    function upload_file_factor($file, $folder)
    {
        if ($file) {
            try {
                $pdfFile = $file;
                $inputPdfPath = $pdfFile->getPathName();

                $outputPdfTempPath = storage_path('app/public/temp-processed-pdf.pdf');

                $imagePath = public_path('assets/images/parso_mohr_emza.png');

                $mpdf = new \Mpdf\Mpdf([
                    'tempDir' => storage_path('app/mpdf-temp')
                ]);

                $pageCount = $mpdf->SetSourceFile($inputPdfPath);

                list($imgWidth, $imgHeight) = getimagesize($imagePath);

                $imgWidthMm = $imgWidth * 0.264583;
                $imgHeightMm = $imgHeight * 0.264583;

                $x = 280 - $imgWidthMm;
                $y = 180 - $imgHeightMm;

                for ($i = 1; $i <= $pageCount; $i++) {
                    $templateId = $mpdf->ImportPage($i);
                    $mpdf->AddPage('L');
                    $mpdf->UseTemplate($templateId);

                    if ($i == $pageCount) {
                        $mpdf->Image($imagePath, $x, $y, $imgWidthMm, $imgHeightMm);
                    }
                }

                $mpdf->Output($outputPdfTempPath, 'F');

                $year = Carbon::now()->year;
                $month = Carbon::now()->month;
                $uploadPath = public_path("/uploads/{$folder}/{$year}/{$month}/");

                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $filename = time() . '-processed.pdf';
                $finalPath = $uploadPath . $filename;
                rename($outputPdfTempPath, $finalPath);

                $img = "/uploads/{$folder}/{$year}/{$month}/" . $filename;

                return $img;
            } catch (Exception $e) {
                alert()->warning('خطا در آپلود فایل', 'خطا');
                return redirect()->to(route('invoices.index'));
            }


        }
    }
}


if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
//         $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('sendSMS')) {
    function sendSMS(int $bodyId, string $to, array $args, array $options = [])
    {
        $url = 'https://console.melipayamak.com/api/send/shared/d131cf0fe6ef4a6cb983308e46836678';
        $data = array('bodyId' => $bodyId, 'to' => $to, 'args' => $args);
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        // Next line makes the request absolute insecure
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array('Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        \App\Models\SmsHistory::create([
            'user_id' => auth()->id(),
            'phone' => $to,
            'text' => $options['text'] ?? '',
            'status' => isset($result->recId) ? $result->recId != 11 ? 'sent' : 'failed' : 'failed',
        ]);

        return $result;
    }
}

if (!function_exists('activity_log')) {
    function activity_log($activity_name, $method, $data = [])
    {
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'activity_name' => $activity_name,
            'method' => $method,
            'data' => json_encode($data),
        ]);
    }
}
function englishToPersianNumbers($input)
{
    $persianNumbers = [
        '0' => '۰',
        '1' => '۱',
        '2' => '۲',
        '3' => '۳',
        '4' => '۴',
        '5' => '۵',
        '6' => '۶',
        '7' => '۷',
        '8' => '۸',
        '9' => '۹',
    ];

    return strtr($input, $persianNumbers);
}

function getCompany($data)
{
    $company = '';
    switch ($data) {
        case "parso":
            $company = 'پرسو تجارت ایرانیان';
            break;
        case "adaktejarat":
            $company = 'آداک تجارت خورشید قشم';
            break;
        case "barman":
            $company = 'بارمان سیستم سرزمین پارس';
            break;
    }
    return $company;
}
