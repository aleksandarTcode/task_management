<?php

namespace App\Console;

use App\Models\Task;
use App\Notifications\TaskDueDateReminder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Notification;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
//        $schedule->call(function () {
//            $tasks = Task::where('due_date', '<=', now()->addDay())
//                ->where('status', '!=', 'Completed')
//                ->get();
//            foreach ($tasks as $task) {
//                $task->assignedTo->notify(new TaskDueNotification($task));
//            }
//        })->everySecond();

        // Send reminders 1 day before the due date
        $schedule->call(function () {
            $tasks = \App\Models\Task::whereDate('due_date', now()->addDay())->get();
            Notification::send($tasks, new TaskDueDateReminder);
        })->daily();

        // Send overdue reminders
        $schedule->call(function () {
            $tasks = \App\Models\Task::whereDate('due_date', '<', now())->get();
            Notification::send($tasks, new TaskDueDateReminder);
        })->daily();


    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
