<?php

namespace JeffersonGoncalves\MailEditor\Support;

class VariableEngine
{
    /**
     * Process a string with advanced variable syntax:
     * - Simple: {{name}}
     * - Fallback: {{name|Default Value}}
     * - Conditional: {{#if variable}}content{{/if}}
     * - Conditional with else: {{#if variable}}content{{#else}}other{{/if}}
     * - Loops: {{#each items}}{{this.name}} - {{this.price}}{{/each}}
     *
     * @param  array<string, mixed>  $variables
     */
    public function process(string $text, array $variables): string
    {
        // Process loops first (innermost first)
        $text = $this->processLoops($text, $variables);

        // Process conditionals (innermost first)
        $text = $this->processConditionals($text, $variables);

        // Process simple variables with optional fallbacks
        $text = $this->processVariables($text, $variables);

        return $text;
    }

    /**
     * Process all variables in block props recursively.
     *
     * @param  array<string, mixed>  $props
     * @param  array<string, mixed>  $variables
     * @return array<string, mixed>
     */
    public function processProps(array $props, array $variables): array
    {
        foreach ($props as $key => $value) {
            if (is_string($value)) {
                $props[$key] = $this->process($value, $variables);
            } elseif (is_array($value)) {
                $props[$key] = $this->processProps($value, $variables);
            }
        }

        return $props;
    }

    /**
     * Extract all variable names from text, including those in conditionals and loops.
     *
     * @return list<string>
     */
    public static function extractVariables(string $text): array
    {
        $vars = [];

        // Simple variables: {{name}} or {{name|fallback}}
        preg_match_all('/\{\{(\w+)(?:\|[^}]*)?\}\}/', $text, $matches);
        $vars = array_merge($vars, $matches[1]);

        // Conditionals: {{#if variable}}
        preg_match_all('/\{\{#if\s+(\w+)\}\}/', $text, $matches);
        $vars = array_merge($vars, $matches[1]);

        // Loops: {{#each variable}}
        preg_match_all('/\{\{#each\s+(\w+)\}\}/', $text, $matches);
        $vars = array_merge($vars, $matches[1]);

        return array_values(array_unique(array_filter($vars)));
    }

    /**
     * Process {{#each items}}...{{/each}} loops.
     *
     * @param  array<string, mixed>  $variables
     */
    protected function processLoops(string $text, array $variables): string
    {
        // Match innermost loops first to support nesting
        $pattern = '/\{\{#each\s+(\w+)\}\}((?:(?!\{\{#each).)*?)\{\{\/each\}\}/s';

        $maxIterations = 10;
        $iteration = 0;

        while (preg_match($pattern, $text) && $iteration < $maxIterations) {
            $text = (string) preg_replace_callback($pattern, function (array $match) use ($variables) {
                $varName = $match[1];
                $template = $match[2];
                $items = $variables[$varName] ?? [];

                if (! is_array($items)) {
                    return '';
                }

                $output = '';
                foreach ($items as $index => $item) {
                    $row = $template;

                    if (is_array($item)) {
                        // Replace {{this.key}} with item values
                        foreach ($item as $key => $value) {
                            $row = str_replace('{{this.'.$key.'}}', (string) $value, $row);
                        }
                    } else {
                        // Scalar items: {{this}} references the value
                        $row = str_replace('{{this}}', (string) $item, $row);
                    }

                    // Replace {{@index}} with loop index
                    $row = str_replace('{{@index}}', (string) $index, $row);

                    $output .= $row;
                }

                return $output;
            }, $text);

            $iteration++;
        }

        return $text;
    }

    /**
     * Process {{#if var}}...{{#else}}...{{/if}} conditionals.
     *
     * @param  array<string, mixed>  $variables
     */
    protected function processConditionals(string $text, array $variables): string
    {
        // Match innermost conditionals first
        $pattern = '/\{\{#if\s+(\w+)\}\}((?:(?!\{\{#if).)*?)\{\{\/if\}\}/s';

        $maxIterations = 10;
        $iteration = 0;

        while (preg_match($pattern, $text) && $iteration < $maxIterations) {
            $text = (string) preg_replace_callback($pattern, function (array $match) use ($variables) {
                $varName = $match[1];
                $content = $match[2];

                $value = $variables[$varName] ?? null;
                $isTruthy = ! empty($value) && (string) $value !== 'false';

                // Split on {{#else}} if present
                $parts = preg_split('/\{\{#else\}\}/', $content, 2);
                $trueBranch = $parts[0];
                $falseBranch = $parts[1] ?? '';

                return $isTruthy ? $trueBranch : $falseBranch;
            }, $text);

            $iteration++;
        }

        return $text;
    }

    /**
     * Process {{variable}} and {{variable|fallback}} replacements.
     *
     * @param  array<string, mixed>  $variables
     */
    protected function processVariables(string $text, array $variables): string
    {
        return (string) preg_replace_callback('/\{\{(\w+)(?:\|([^}]*))?\}\}/', function (array $match) use ($variables) {
            $varName = $match[1];
            $fallback = $match[2] ?? null;

            if (isset($variables[$varName]) && $variables[$varName] !== '') {
                return (string) $variables[$varName];
            }

            if ($fallback !== null) {
                return $fallback;
            }

            // Leave unresolved variables as-is
            return $match[0];
        }, $text);
    }
}
