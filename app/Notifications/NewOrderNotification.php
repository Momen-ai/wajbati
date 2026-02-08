<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Order Received! #' . $this->order->id)
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have received a new order for your kitchen items.')
            ->line('Order Total: $' . $this->order->total)
            ->action('View Order Details', route('chef.orders.show', $this->order->id))
            ->line('Thank you for being part of Wajbati!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'customer_name' => $this->order->user->name,
            'total' => $this->order->total,
            'message' => 'New order received from ' . $this->order->user->name,
        ];
    }
}
