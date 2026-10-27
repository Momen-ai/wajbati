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
        $locale = $notifiable->locale ?? app()->getLocale();
        $symbol = config('shop.currency.symbol', '₪');

        return (new MailMessage)
            ->locale($locale)
            ->subject(__('New Order Received! #:id', ['id' => $this->order->id], $locale))
            ->greeting(__('Hello :name', ['name' => $notifiable->name], $locale))
            ->line(__('You have received a new order for your kitchen items.', [], $locale))
            ->line(__('Order Total: :symbol:total', ['symbol' => $symbol, 'total' => number_format($this->order->total, 2)], $locale))
            ->action(__('View Order Details', [], $locale), route('chef.orders.show', $this->order->id))
            ->line(__('Thank you for being part of Wajbati!', [], $locale));
    }

    public function toArray(object $notifiable): array
    {
        $locale = $notifiable->locale ?? app()->getLocale();

        return [
            'order_id' => $this->order->id,
            'customer_name' => $this->order->user?->name ?? 'Customer',
            'total' => $this->order->total,
            'message' => __('New order #:id received from :customer', [
                'id' => $this->order->id,
                'customer' => $this->order->user?->name ?? 'Customer',
            ], $locale),
        ];
    }
}
