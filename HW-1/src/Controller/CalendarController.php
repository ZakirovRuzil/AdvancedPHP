<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class CalendarController extends AbstractController
{
    #[Route('/calendar/{view}/{month}', name: 'calendar', defaults: ['view' => 'table', 'month' => null])]
    public function showCalendar(Request $request, string $view, ?int $month = null): Response
    {
        $currentMonth = $month ?? (int)(new DateTime())->format('m');
        $currentYear = (int)(new DateTime())->format('Y');

        if ($request->query->has('month')) {
            $currentMonth = (int) $request->query->get('month');
        }

        $firstDayOfMonth = DateTime::createFromFormat('Y-m-d', "$currentYear-$currentMonth-1");
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

        $startDayOfWeek = ((int) $firstDayOfMonth->format('N') - 1) % 7;
        $calendar = [];

        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $calendar[] = null;
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = DateTime::createFromFormat('Y-m-d', "$currentYear-$currentMonth-$day");
            $calendar[] = [
                'day' => $day,
                'date' => $date,
                'isWeekend' => in_array((int) $date->format('N'), [6, 7]),
            ];
        }

        $template = match ($view) {
            'table' => 'calendar/table.html.twig',
            'weekends' => 'calendar/weekends.html.twig',
            'list' => 'calendar/list.html.twig',
            default => 'calendar/table.html.twig',
        };

        return $this->render($template, [
            'calendar' => $calendar,
            'month' => $currentMonth,
            'year' => $currentYear,
            'view' => $view,
        ]);
    }
}
