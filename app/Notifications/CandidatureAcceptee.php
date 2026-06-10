<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CandidatureAcceptee extends Notification
{
    protected $offer;
    protected $internship;

    public function __construct($offer, $internship = null)
    {
        $this->offer = $offer;
        $this->internship = $internship;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = $this->internship
            ? url('/internships/' . $this->internship->id . '/formulaires')
            : url('/internships');

        return (new MailMessage)
            ->subject('Votre candidature a été acceptée')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line("Votre candidature pour l'offre \"{$this->offer->title}\" a été acceptée.")
            ->line('Avant le début du stage, vous devez obligatoirement avoir une attestation d’assurance et une convention de stage.')
            ->line('Veuillez vous connecter à la plateforme pour remplir les formulaires administratifs.')
            ->line('Après remplissage, l’administration pourra imprimer les formulaires et les faire signer.')
            ->action('Remplir mes formulaires', $url)
            ->line('Merci de compléter vos informations dans les meilleurs délais.');
    }
}