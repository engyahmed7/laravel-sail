<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    public function getCreatedNotification(): Notification {
        return Notification::make()
            ->title('Post Added')
            ->body('congratulations, new post added successfully.')
            ->icon('heroicon-o-check-circle')
            ->iconColor('green')
            ->actions([
                Action::make('View Post')
                    ->name('View Post')
                    ->color('success'),
                    // ->url(route('fillament.pages.dashboard'),shouldOpenInNewTab: true),
                Action::make('Add New Post')
                    ->name('Add Post')
                    ->color('blue'),
                    // ->url(route('posts.index'),shouldOpenInNewTab: true),
            ])
            ->sendToDatabase(auth()->user())
            ->send();
    }

}
