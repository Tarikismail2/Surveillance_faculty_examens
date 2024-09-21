<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class NodeMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $department;
    public $session;
    public $schedule;
    public $enseignants;
    public $dates;
    public $reservistes;
    protected $pdfContent;
    public $id_department;
    public $id_session;

    public function __construct($department, $session, $pdfContent, $schedule, $enseignants, $dates, $reservistes, $id_department, $id_session)
    {
        $this->department = $department;
        $this->session = $session;
        $this->pdfContent = $pdfContent;
        $this->schedule = $schedule;
        $this->enseignants = $enseignants;
        $this->dates = $dates;
        $this->reservistes = $reservistes;
        $this->id_department = $id_department;
        $this->id_session = $id_session;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Emploi du Temps - ' . $this->department->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emploi.schedule',
            with: [
                'department' => $this->department,
                'session' => $this->session,
                'schedule' => $this->schedule,
                'enseignants' => $this->enseignants,
                'dates' => $this->dates,
                'reservistes' => $this->reservistes,
                'id_department' => $this->id_department,
                'id_session' => $this->id_session,
            ]
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(function() {
                return $this->pdfContent;
            }, 'emploi_du_temps.pdf')
        ];
    }
}
