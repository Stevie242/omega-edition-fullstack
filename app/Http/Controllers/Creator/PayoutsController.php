<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PayoutsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('creator/Payouts/Index');
    }
}
