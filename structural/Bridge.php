<?php

interface Formatter
{
    public function format($str): string;
}

class SimpleText implements Formatter
{
    public function format($str): string
    {
        return $str;
    }
}

class HtmlText implements Formatter
{
    public function format($str): string
    {
        return "<p>$str<p>";
    }
}

abstract class BridgeService
{
    public Formatter $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    abstract public function getFormatter($str): string;
}

class SimpleTextService extends BridgeService
{
    public function getFormatter($str): string
    {
        return $this->formatter->format($str);
    }
}

class HtmlTextService extends BridgeService
{
    public function getFormatter($str): string
    {
        return $this->formatter->format($str);
    }
}

$simpleText = new SimpleText();
$htmlText = new HtmlText();

$simpleTextService = new SimpleTextService($simpleText);
$htmlTextService = new HtmlTextService($htmlText);

echo $simpleTextService->getFormatter('text'); // text
echo "\n";
echo $htmlTextService->getFormatter('text'); // <p>text<p>

// ----------- 2 -----------

interface WebPage
{
    public function __construct(Theme $theme);

    public function getContent();
}

class About implements WebPage
{
    protected $theme;

    public function __construct(Theme $theme)
    {
        $this->theme = $theme;
    }

    public function getContent()
    {
        return "About page in " . $this->theme->getColor();
    }
}

class Careers implements WebPage
{
    protected $theme;

    public function __construct(Theme $theme)
    {
        $this->theme = $theme;
    }

    public function getContent()
    {
        return "Careers page in " . $this->theme->getColor();
    }
}

interface Theme
{
    public function getColor();
}

class DarkTheme implements Theme
{
    public function getColor()
    {
        return 'Dark Black';
    }
}

class LightTheme implements Theme
{
    public function getColor()
    {
        return 'Off white';
    }
}

$darkTheme = new DarkTheme();

$about = new About($darkTheme);
echo $about->getContent(); // About page in Dark Black

$careers = new Careers($darkTheme);
echo $careers->getContent(); // Careers page in Dark Black

$lightTheme = new LightTheme();

$about1 = new About($lightTheme);
echo $about1->getContent(); //  About page in Off white

$careers2 = new Careers($lightTheme);
echo $careers2->getContent(); // Careers page in Off white