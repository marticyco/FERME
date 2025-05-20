<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionCreated extends Notification
{
    use Queueable;

    protected $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Vérifie que l'objet subscription est bien chargé avec ses relations
        $clientName = $notifiable->name ?? 'Client';
        $type = $this->subscription->subscription_type ?? 'non spécifié';
        $start = $this->subscription->start_date ?? '...';
        $end = $this->subscription->end_date ?? '...';
        $address = $this->subscription->delivery_address ?? 'non définie';
        $price = $this->subscription->price ?? '0';

        return (new MailMessage)
            ->subject('Confirmation de votre abonnement 🐔')
            ->greeting("Bonjour {$clientName} !")
            ->line("Merci pour votre abonnement.")
            ->line("Type d’abonnement : {$type}")
            ->line("Durée : du {$start} au {$end}")
            ->line("Adresse de livraison : {$address}")
            ->line("Montant total : {$price} €")
            ->line("Merci de faire confiance à Ma Ferme !");
    }
}
