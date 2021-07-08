<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProyectoFinalizado extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($convocatoria, $proyecto)
    {
        $this->convocatoria = $convocatoria;
        $this->proyecto     = $proyecto;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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
            "proyectoId"    => $this->proyecto->id,
            "subject"       => "Proyecto se ha finalizado",
            "message"       => "El proyecto {$this->proyecto->codigo} ha sido finalizado. Por favor verifique que la información este completa y de ser así radique el proyecto haciendo clic en los tres puntos, luego clic en 'Ver detalles', a continuación, debe dirigirse al paso de 'Finalizar formulación' y dar clic en 'Radicar'.",
            "action"        => "convocatorias/{$this->convocatoria->id}/proyectos/{$this->proyecto->id}/editar?notificacion={$this->id}"
        ];
    }
}
