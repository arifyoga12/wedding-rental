<?php

namespace App\Core;

class SimpleTemplate
{
    private string $templateDir;
    private array $globals = [];

    public function __construct(string $templateDir)
    {
        $this->templateDir = $templateDir;
    }

    public function addGlobal(string $name, $value): void
    {
        $this->globals[$name] = $value;
    }

    public function render(string $template, array $data = []): string
    {
        $templatePath = $this->templateDir . '/' . $template;
        
        if (!file_exists($templatePath)) {
            throw new \Exception("Template not found: $template");
        }

        // Merge globals with data
        $data = array_merge($this->globals, $data);
        
        // Extract variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include template
        include $templatePath;
        
        // Get content and clean buffer
        $content = ob_get_clean();
        
        return $content;
    }
}