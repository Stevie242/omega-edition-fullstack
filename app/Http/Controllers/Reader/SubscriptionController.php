<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('reader/Subscription');
    }
}
