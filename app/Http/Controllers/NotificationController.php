<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Auth;
class NotificationController extends Controller
{
    public function get(){
        // return Notification::all();
        // Notificaciones de ese dia en especifico
        $unreadNotifications = Auth::user()->unreadNotifications;
        $fechaActual = date('Y-m-d');
       
        foreach ($unreadNotifications as $notification) {
         // si la noti no leida no coincide con el dia actual, lo marca como leida
            if ($fechaActual != $notification->created_at->toDateString()){
                $notification->markAsRead();
            }
        }
        // notificaciones del usuario logueado
        // retorna las notificaciones no leidas
        return Auth::user()->unreadNotifications;
    }
}
