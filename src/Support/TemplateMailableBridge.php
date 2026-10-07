<?php

namespace JeffersonGoncalves\MailEditor\Support;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

class TemplateMailableBridge extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected string $renderedHtml = '';

    protected string $renderedPlaintext = '';

    protected VariableEngine $variableEngine;

    /**
     * @param  array<string, mixed>  $variables
     */
    public function __construct(
        protected string $slug,
        protected array $variables = [],
    ) {
        $this->variableEngine = new VariableEngine;
    }

    public function envelope(): Envelope
    {
        $template = $this->resolveTemplate();

        return new Envelope(
            subject: $template->subject ?? $this->slug,
        );
    }

    public function content(): Content
    {
        $this->buildContent();

        return new Content(
            htmlString: $this->renderedHtml,
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build(): static
    {
        $this->buildContent();

        return $this->html($this->renderedHtml)
            ->text('mail-editor::plaintext', ['content' => $this->renderedPlaintext]);
    }

    protected function buildContent(): void
    {
        if ($this->renderedHtml !== '') {
            return;
        }

        $template = $this->resolveTemplate();
        $this->renderedHtml = $template->render($this->variables);

        $generator = new PlaintextGenerator;
        $blocks = $template->blocks ?? [];
        $blocks = array_map(function (array $block) {
            $block['props'] = $this->replaceVariablesInProps($block['props']);

            return $block;
        }, $blocks);
        $this->renderedPlaintext = $generator->generate($blocks);
    }

    protected function resolveTemplate(): EmailTemplate
    {
        $model = config('mail-editor.model', EmailTemplate::class);

        return $model::where('slug', $this->slug)->firstOrFail();
    }

    /**
     * @param  array<string, mixed>  $props
     * @return array<string, mixed>
     */
    protected function replaceVariablesInProps(array $props): array
    {
        return $this->variableEngine->processProps($props, $this->variables);
    }
}
