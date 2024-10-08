<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyInfoRequest;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use PDF as PDF;

class CompanyInfoController extends Controller
{

    public function index()
    {
        $this->authorize('company-info');
        $info = CompanyInfo::first();
        return view('panel.company-info.index', compact('info'));
    }


    public function edit($id)
    {
        $this->authorize('company-info');
        $info = CompanyInfo::first();
        return view('panel.company-info.edit', compact('info'));
    }


    public function update(StoreCompanyInfoRequest $request, $id)
    {
        $this->authorize('company-info');
        $info = CompanyInfo::findOrfail($id);
        $info->update($request->all());
        alert()->success('اطلاعات با موفقیت ویرایش شد', 'موفقیت آمیز');
        activity_log('edit-company-information', __METHOD__, [$request->all()]);
        return redirect()->route('company-info.index');
    }

    public function copyItem(Request $request)
    {
        $this->authorize('company-info');
        activity_log('copy-information', __METHOD__, [$request->all()]);
        return "success";
    }

    public function printData(Request $request)
    {

        $this->authorize('company-info');
        $allData = $request->input('data');
        activity_log('print-information', __METHOD__, [$request->all()]);
        if (isset($allData['name'])) {
            $nameData = ['name' => $allData['name']];
            unset($allData['name']);
            $allData = array_merge($nameData, $allData);
        }
        $pdf = PDF::loadView('panel.pdf.printInfo', ['allData' => $allData], [], [
            'format' => 'A3',
            'orientation' => 'L',
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 10,
            'margin_bottom' => 0,
            'default_font' => 'vazir',
        ]);



        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . time() . '.pdf"');


//        return view('panel.company-info.printinfo', compact('allData'));

    }


}
