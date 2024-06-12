<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeveloperTools;
use DataTables;

class DeveloperController extends Controller
{
    public function index()
    {
        $page_title = "Developer Tools";
        $active = 'developerlist';
        return view('developer_tools.list', compact('active', 'page_title'));
    }

    public function getDeveloperTools(Request $request)
    {
        
        if (!request()->ajax()) {
            $page_title = "Developer Tools";
            $active = 'developerlist';
            return view('developer_tools.list', compact('active', 'page_title'));
        }
    
        $data = DeveloperTools::query()->select('*')->latest();
     
        return DataTables::eloquent($data)
            ->editColumn('file_uploaded', function ($data) {
                $badge = '<a href="/application/storage/app/public/'.$data->file_uploaded.'" target="_blank" style="color:blue;">View File</a>';
                return $badge;
            })
            ->addIndexColumn()
            ->rawColumns(['file_uploaded'])
            ->toJson();
    }
}
