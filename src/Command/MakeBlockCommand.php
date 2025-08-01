<?php

namespace AcfBlocksCli\Command;

use AcfBlocksCli\Generator\BlockGenerator;
use AcfBlocksCli\ValueObject\BlockDefinition;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeBlockCommand extends Command
{
    public function __construct(
        private readonly BlockGenerator $generator = new BlockGenerator()
    )
    {
        parent::__construct('make:block');
    }

    protected function configure(): void
    {
        $this->setDescription('Generate a new ACF block');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $io->ask('Block name (e.g. HeroBanner)', null, fn($v) => $v ?: throw new \RuntimeException('Name required.'));
        $slug = $this->slugify($name);

        $category = $io->choice('Block category', ['theme', 'text', 'media', 'design', 'widgets', 'embed'], 'theme');
        $mode = $io->choice('Block mode', ['preview', 'edit', 'auto'], 'preview');

        $generateScss = $io->confirm('Generate SCSS file?', true);
        $generateJson = $io->confirm('Generate block.json file?', true);

        $definition = new BlockDefinition($name, $slug, $category, $mode, $generateScss, $generateJson);
        $this->generator->generate($definition);

        $io->success("Block '{$name}' created successfully!");
        return Command::SUCCESS;
    }

    private function slugify(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        return preg_replace('/-+/', '-', $text);
    }

}
