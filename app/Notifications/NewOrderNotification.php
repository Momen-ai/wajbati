<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldQueue
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
        $symbol = config('shop.currency.symbol', '₪');

        return (new MailMessage)
            ->subject(__('New Order Received! #:id', ['id' => $this->order->id]))
            ->greeting(__('Hello :name', ['name' => $notifiable->name]))
            ->line(__('You have received a new order for your kitchen items.'))
            ->line(__('Order Total: :symbol:total', ['symbol' => $symbol, 'total' => number_format($this->order->total, 2)]))
            ->action(__('View Order Details'), route('chef.orders.show', $this->order->id))
            ->line(__('Thank you for being part of Wajbati!'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'customer_name' => $this->order->user?->name ?? 'Customer',
            'total' => $this->order->total,
            'message' => __('New order #:id received from :customer', [
                'id' => $this->order->id,
                'customer' => $this->order->user?->name ?? 'Customer',
            ]),
        ];
    }
}
