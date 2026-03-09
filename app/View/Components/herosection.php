<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;

class herosection extends Component
{
    public $totalBooks;
    public $totalUsers;
    public $totalCategories;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->totalBooks = Book::count();
        $this->totalUsers = User::count();
        $this->totalCategories = Category::count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.hero-section');
    }
}
