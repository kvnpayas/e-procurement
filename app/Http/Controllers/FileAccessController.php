<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileAccessController extends Controller
{
  public function view($folder, $file)
  {
    $folders = explode("\\", $folder);
    if (count($folders) == 1) {
      // Get the file path
      $filePath = storage_path('app/public/' . $folders[0] . '/' . $file);
    } else {
      $filePath = storage_path('app/public/' . $folders[0] . '/' . $folders[1] . '/' . $file);
    }



    // Return the file as a response
    return response()->file($filePath);
  }
}
