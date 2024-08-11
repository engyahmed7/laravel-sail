<?php
 
namespace App\Filament\Pages;

use App\Filament\Resources\PostResource\Widgets\PostsChart;

class Dashboard extends \Filament\Pages\Dashboard
{   
    // public function getWidgets(): array
    // {
    //     return [
            
    //     ];
    // }
    
    protected function getFooterWidgets(): array
    {
        return [
            PostsChart::class,
        ];
    }
}