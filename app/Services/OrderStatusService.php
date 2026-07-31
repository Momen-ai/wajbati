<?php

namespace App\Services;

/**
 * OrderStatusService
 *
 * Defines the allowed status transition whitelist for orders.
 * This is the single source of truth — used by both Chef\OrderController
 * and any other place that needs to validate a status change.
 *
 * Valid status flow:
 *   pending → accepted | rejected | cancelled
 *   accepted → preparing | rejected | cancelled
 *   preparing → prepared
 *   prepared → delivered
 *   delivered → completed
 *
 * Admins (Dashboard\OrderController) are intentionally exempt from these
 * restrictions — they may need to correct orders manually. See:
 * @see \App\Http\Controllers\Dashboard\OrderController::update()
 */
class OrderStatusService
{
    /**
     * Whitelist: maps each status to the set of statuses it may transition into.
     */
    public const TRANSITIONS = [
        'pending'   => ['accepted', 'rejected', 'cancelled'],
        'accepted'  => ['preparing', 'rejected', 'cancelled'],
        'preparing' => ['prepared'],
        'prepared'  => ['delivered'],
        'delivered' => ['completed'],
        // Terminal states — no further transitions allowed
        'completed' => [],
        'rejected'  => [],
        'cancelled' => [],
    ];

    /**
     * Check whether transitioning from $from to $to is permitted.
     */
    public static function canTransitionTo(string $from, string $to): bool
    {
        return in_array($to, self::TRANSITIONS[$from] ?? [], true);
    }

    /**
     * Return the list of statuses that $from may legally transition to.
     */
    public static function getAllowedStatuses(string $from): array
    {
        return self::TRANSITIONS[$from] ?? [];
    }
}
