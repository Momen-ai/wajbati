<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order
    ) {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Order Update! #:id', ['id' => $this->order->id]))
            ->greeting(__('Hello :name', ['name' => $notifiable->name]))
            ->line(__('The status of your order #:id has been updated to: :status', [
                'id' => $this->order->id,
                'status' => strtoupper($this->order->status),
            ]))
            ->action(__('View Order Status'), route('orders.show', $this->order->id))
            ->line(__('Enjoy your delicious meal from Wajbati!'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'message' => __('Your order #:id is now :status', [
                'id' => $this->order->id,
                'status' => $this->order->status,
            ]),
        ];
    }
}
