<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use \App\Models\DeveloperTools;


class DeveloperToolsController extends Controller
{    
    public function developertoollist()
    {
        if (!request()->ajax()) {
            $page_title = "Developer Tools";
            $active = 'developer_tools';
            return view('admin.developer_tools.list', compact('active', 'page_title'));
        }
    
        $data = DeveloperTools::query()->select('*')->latest();
    
        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->toJson();
    }

        public function developertoolstore(Request $request){
        // Validate the form data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'required|file|max:2048', 
        ]);

        // Store the file in public/uploads directory
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
        } else {
            $filePath = null;
        }
        // Create a new item
        $item = new DeveloperTools(); 
        $item->title = $validatedData['title'];
        $item->description = $validatedData['description'];
        $item->file_uploaded = $filePath;
        $item->save();

        // You can return a response if needed
        return response()->json(['message' => 'succcessful']);
    }

    public function deleteItem(Request $request){
    
        $itemId = $request->input('id');
        $item = DeveloperTools::find($itemId);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }
        $item->delete();

        return response()->json(['success' => true, 'message' => 'Item deleted successfully']);
    }

        public function editItem(Request $request){
            $item = DeveloperTools::find($request->id); 
            $item['file_uploaded'] = '/application/storage/app/public/'.$item['file_uploaded'];

            return response()->json(['item' => $item]);

        }
       
       
        public function updateItem(Request $request){
           
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'file' => 'nullable|file|max:2048',
            ]);
        
            // Find the DeveloperTools item by ID
            $item = DeveloperTools::findOrFail($request->item_id);
        
            // Update the item properties
            $item->title = $validatedData['title'];
            $item->description = $validatedData['description'];
        
            if ($request->hasFile('file')) {
                if ($item->file_uploaded) {
                    \Storage::disk('public')->delete($item->file_uploaded);
                }
                // Store the new file
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $item->file_uploaded = $filePath;
            }
            $item->save();
        
            return response()->json(['success' => true, 'message' => 'successful']);
        }
    
}
