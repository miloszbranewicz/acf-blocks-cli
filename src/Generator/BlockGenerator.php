<?php

namespace AcfBlocksCli\Generator;

use AcfBlocksCli\ValueObject\BlockDefinition;

class BlockGenerator
{
    public string $prefix = 'acf';
    private string $dir;

    public function __construct()
    {
        $this->dir = getcwd() . "/template-parts/blocks";
    }

    public function generate(BlockDefinition $block): void
    {
        $dir = $this->dir . '/' . $block->slug;

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }


        $this->generatePhpFile($block);

        if ($block->generateJson) {
            $this->generateBlockJson($block);
        }

        if ($block->generateScss) {
            $this->generateScssFile($block);
        }

    }

    private
    function generatePhpFile(BlockDefinition $block): void
    {
        $path = "{$this->dir}/{$block->slug}/{$block->slug}.php";
        $data = <<<PHP
                    <div>{$block->name} block</div>
                    PHP;

        if (file_exists($path)) {
            throw new \RuntimeException("File {$path} already exists. Please choose a different block name.");
        }
        file_put_contents($path, $data);
    }

    private
    function generateBlockJson(BlockDefinition $block): void
    {
        $data = json_encode([
            'name' => "{$this->prefix}/{$block->slug}",
            'title' => $block->name,
            'description' => "A custom {$block->name} block that uses ACF fields.",
            'category' => $block->category,
            'keywords' => [$block->slug, $block->name],
            'acf' => [
                'mode' => $block->mode,
                'renderTemplate' => "{$block->slug}.php",
            ],
            'supports' => [
                'anchor' => true,
                'align' => false
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        file_put_contents("$this->dir/{$block->slug}/block.json", $data);
    }

    private function generateScssFile(BlockDefinition $block): void
    {
        if (!$block->generateScss) {
            return;
        }

        $scssContent = <<<SCSS
        .{$block->slug} {
            // Add your styles here
        }
        SCSS;

        file_put_contents("{$this->dir}/{$block->slug}/{$block->slug}.scss", $scssContent);
    }


}