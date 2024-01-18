<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportVerified extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Report $report // short hand to initialize a field in a class through constructor
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        //contains all the information about the report in the database
        return [
            'report_id' => $this->report->id,
            'filename' => $this->report->name,
            'employee_id' => $this->report->employee_id,
            'employer_id' => $this->report->employer_id,
            'manager_id' => $this->report->manager_id,
            'employee_name' => $this->report->employee->name,
            'employer_name' => $this->report->employer->name ?? null,
            'manager_name' => $this->report->manager->name ?? null,
            'employer_status' => $this->report->employer_status,
            'employer_feedback' => $this->report->employer_feedback,
            'manager_status' => $this->report->manager_status,
            'manager_feedback' => $this->report->manager_feedback,
        ];
    }
}
