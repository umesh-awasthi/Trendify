<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\GlobalConfiguration;
class GlobalConfigurationController extends Controller
{
    public function save(Request $request)
    {
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:global_configurations,email',
            'password' => 'required|string|min:8',
            'api_url' => 'required|url',
            'selection' => 'required|string', // Correct validation rule
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
         
        // Save the configuration data
        GlobalConfiguration::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Hash the password
            'api_url' => $request->api_url,
            'selection' => $request->selection, // Save the selected option
        ]);
    
        return redirect()->route('admin.dashboard')->with('success', 'Global configuration saved successfully.');
    }
    }
    
}
