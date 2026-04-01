<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function gallery()
    {
        $templates = Template::active()->ordered()->get();

        return view('templates.gallery', compact('templates'));
    }
}
