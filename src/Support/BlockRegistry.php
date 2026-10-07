<?php

namespace JeffersonGoncalves\MailEditor\Support;

use JeffersonGoncalves\MailEditor\Blocks\Contracts\EmailBlock;

class BlockRegistry
{
    /** @var array<string, class-string<EmailBlock>> */
    protected array $blocks = [];

    /** @var array<string, EmailBlock> */
    protected array $instances = [];

    /** @param  class-string<EmailBlock>  $blockClass */
    public function register(string $blockClass): static
    {
        $type = $blockClass::type();
        $this->blocks[$type] = $blockClass;
        $this->instances[$type] = new $blockClass;

        return $this;
    }

    /** @return array<string, EmailBlock> */
    public function all(): array
    {
        return $this->instances;
    }

    public function find(string $type): ?EmailBlock
    {
        return $this->instances[$type] ?? null;
    }

    /** @return array<string, array{type: string, label: string, icon: string, category: string, defaultProps: array}> */
    public function catalog(): array
    {
        $catalog = [];

        foreach ($this->instances as $type => $block) {
            $catalog[$type] = [
                'type' => $block::type(),
                'label' => $block::label(),
                'icon' => $block::icon(),
                'category' => $block::category(),
                'defaultProps' => $block::defaultProps(),
            ];
        }

        return $catalog;
    }
}
