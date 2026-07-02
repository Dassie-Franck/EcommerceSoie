<?php

// ═══════════════════════════════════════════════════════════════
// App\Http\Controllers\Shop\HomeController.php
// ═══════════════════════════════════════════════════════════════
 
namespace App\Http\Controllers\Shop;
 
use App\Contracts\Shop\HomeServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
 
class HomeController extends Controller
{
    public function __construct(
        private readonly HomeServiceInterface $homeService
    ) {}
 
    public function index(): View
    {
        return view('shop.home', $this->homeService->getHomeData());
    }
}
 