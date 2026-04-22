<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification
{
    use Queueable;

    public $order;
    public $type; // 'new_order', 'status_update'
    public $message;

    public function __construct($order, $type, $message)
    {
        $this->order = $order;
        $this->type = $type; // e.g. 'new_order', 'status_shipped', 'status_delivered', 'status_cancelled'
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $link = '#';
        if ($this->type === 'new_order') {
            $link = route('admin.orders.show', $this->order->id);
        } else {
            // For customer
            $link = route('orders.show', $this->order->id);
        }

        return [
            'title' => $this->type === 'new_order' ? 'New Order #' . $this->order->order_number : 'Order Update',
            'message' => $this->message,
            'link' => $link,
            'type' => $this->type,
            'order_id' => $this->order->id,
        ];
    }
}
