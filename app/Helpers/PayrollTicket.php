<?php

namespace App\Helpers;

use Carbon\Carbon;
use InvalidArgumentException;

class PayrollTicket
{
    // Génère "Tk-ddmmyy-ddmmyy" depuis des dates YYYY-MM-DD
    public static function fromDates(string $start, string $end): string
    {
        $s = Carbon::parse($start)->format('dmy');
        $e = Carbon::parse($end)->format('dmy');
        return "Tk-{$s}-{$e}";
    }

    // Valide le format d’un ticket
    public static function isValid(string $ticket): bool
    {
        return (bool) preg_match('/^Tk-\d{6}-\d{6}$/', $ticket);
    }

    // Convertit "Tk-ddmmyy-ddmmyy" -> ['start'=>'YYYY-MM-DD','end'=>'YYYY-MM-DD']
    public static function toDates(string $ticket): array
    {
        if (!self::isValid($ticket)) {
            throw new InvalidArgumentException('Format de ticket invalide.');
        }

        preg_match('/^Tk-(\d{2})(\d{2})(\d{2})-(\d{2})(\d{2})(\d{2})$/', $ticket, $m);
        // ddmmyy -> YYYY-MM-DD (hypothèse 20xx)
        $start = "20{$m[3]}-{$m[2]}-{$m[1]}";
        $end   = "20{$m[6]}-{$m[5]}-{$m[4]}";
        return ['start' => $start, 'end' => $end];
    }
}
