<?php

namespace App\Notifications;

use App\Models\PenugasanTeknisi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeknisiNotifikasi extends Notification
{
    use Queueable;

    protected $penugasan;

    /**
     * Create a new notification instance.
     *
     * @param PenugasanTeknisi $penugasan
     * @return void
     */
    public function __construct(PenugasanTeknisi $penugasan)
    {
        $this->penugasan = $penugasan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // We want to store this notification in the database
        return ['database'];
        // You could also add 'mail' or 'broadcast' for other channels
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id_penugasan' => $this->penugasan->id_penugasan,
            'fasilitas' => $this->penugasan->laporan->fasilitas->nama_fasilitas, // Assuming 'judul' is a field in PenugasanTeknisi
            'deskripsi' => $this->penugasan->laporan->deskripsi, // Assuming 'deskripsi' is a field
            'assigned_by' => auth()->check() ? auth()->user()->name : 'System', // Who assigned it
            'link' => route('perbaikan.index'), // Example: link to the task details
        ];
    }

    /**
     * Get the mail representation of the notification (optional).
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('You have been assigned a new task: ' . $this->penugasan->laporan->fasilitas->nama_fasilitas)
                    ->action('View Task', route('perbaikan.index'))
                    ->line('Thank you for using our application!');
    }
}
