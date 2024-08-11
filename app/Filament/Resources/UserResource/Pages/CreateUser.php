<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Notifications\Notification as NotificationsNotification;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    // public function getCreatedNotificationTitle(): string{
    //     return 'New User Added Successfully !!!!';
    // }

    public function getCreatedNotification(): Notification {
        return Notification::make()
            ->title('User Added')
            ->body('New User Added Successfully !!!!')
            ->icon('heroicon-o-check-circle');
    }
}
