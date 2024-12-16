<?php
 
namespace Odboxxx\LaravelQecalculator\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
 
class QecalculatorHistoryMail extends Mailable
{
    use Queueable, SerializesModels;
 
    public string $emailTo;
    public array $attachFiles = [];
    public string $contentMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected $data,
    ) {

        if (isset($data['email'])) {
            $this->emailTo = $data['email'];
        } else {
            $this->emailTo = config('qecalculator.email_to');
        }

        if ($data['rowsAffected'] === false) {

            $this->contentMessage = 'Во время экспорта произошла ошибка';

        } elseif ($data['rowsAffected'] === 0) {

            $this->contentMessage = 'С момента последнего экспорта новых вычислений не обнаружено';

        } else {
            
            if (is_array($data['filePath'])) {
                $this->attachFiles = $data['filePath'];
            } else {
                $this->attachFiles[] = $data['filePath'];
            }
            
            $this->contentMessage = 'С момента последнего экспорта выполнено '.$data['rowsAffected'].' вычислений. Отчёт о результатах вычислений в прилагаемом файле';

        }

    }
 
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('qecalculator.email_from')),
            subject: 'Отчёт о результатах вычислений квадратного уравнения',
            to: $this->emailTo
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'qecalculator::mail.history',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {   
        $r = [];

        if (count($this->attachFiles)>0) {
            foreach ($this->attachFiles as $file) {
                $r[] = Attachment::fromPath($file);
            }
        }

        return $r;
    }    
}
