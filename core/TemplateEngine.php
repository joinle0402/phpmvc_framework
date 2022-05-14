<?php

namespace application\core;

class TemplateEngine
{
    protected string $viewContent;

    public function __construct(string $viewContent)
    {
        $this->viewContent = $viewContent;
    }

    public function run()
    {
        $this->extendLayout();
        $this->printEntities();
        $this->printRaw();
        $this->ifCondition();
        $this->phpExpression();
        $this->forLoop();
        $this->whileLoop();
        $this->foreachLoop();
        return $this->viewContent;
    }

    protected function extendLayout()
    {
        preg_match_all('~@extends\s*\(\s*\'(.+?)\'\s*\)\s*$~im', $this->viewContent, $matches);

        if (!empty($matches[0]))
        {
            $layoutPath = str_replace('.', '/', $matches[1][0]);
            $layoutPath = ROOT_DIRECTORY."/views/$layoutPath.blade.php";
            $layoutPath = str_replace('/', '\\', $layoutPath);

            $layoutContentView = null;
            if (file_exists($layoutPath))
            {
                $layoutContentView = file_get_contents($layoutPath);
            }

            if (!empty($layoutContentView))
            {
                $this->viewContent = str_replace("@extends('".$matches[1][0]."')", $layoutContentView, $this->viewContent);
            }

            preg_match_all('~@section\s*\(\s*\'(.+?)\'\s*\)\s*(.+?)\s*@show\s*$~mis', $this->viewContent, $matches);
            if (!empty($matches[0]))
            {
                foreach ($matches[0] as $key => $value)
                {
                    $sectionShowName = $matches[1][$key];
                    $sectionShowContent = $matches[2][$key];

                    $this->viewContent = str_replace($matches[0][$key], "@yield('$sectionShowName')", $this->viewContent);

                    preg_match_all("~@section\s*\(\s*\'$sectionShowName\'\s*\)\s*(.+?)\s*@endsection\s*$~mis", $this->viewContent, $matches);
                    if (!empty($matches[0][$key]))
                    {
                        $this->viewContent = str_replace('@parent', $sectionShowContent, $this->viewContent);
                    }
                }
            }

            preg_match_all('~@include\s*\(\s*\'(.+?)\'\s*\)\s*$~im', $this->viewContent, $matches);
            if (!empty($matches[0]))
            {
                foreach ($matches[0] as $key => $include)
                {
                    $partialPath = str_replace('.', '/', $matches[1][$key]);
                    $partialPath = ROOT_DIRECTORY."/views/$partialPath.blade.php";
                    $partialPath = str_replace('/', '\\', $partialPath);

                    $partialContentView = file_exists($partialPath) ? file_get_contents($partialPath) : null;

                    if (!empty($partialContentView))
                    {
                        $this->viewContent = str_replace($include, $partialContentView, $this->viewContent);
                    }
                }
            }

            preg_match_all('~@section\s*\(\s*\'(.+?)\'\s*\)\s*(.+?)\s*@endsection\s*$~mis', $this->viewContent, $matches);
            if (!empty($matches[0]))
            {
                foreach ($matches[0] as $key => $section)
                {
                    $sectionName = $matches[1][$key];
                    $sectionContent = $matches[2][$key];

                    $this->viewContent = str_replace("@yield('$sectionName')", $sectionContent, $this->viewContent);
                    $this->viewContent = str_replace($section, '', $this->viewContent);
                }
            }

            preg_match_all("~@yield\(\'(.+?)\'\)~mi", $this->viewContent, $matches);
            if (!empty($matches[0]))
            {
                foreach ($matches[0] as $key => $yield)
                {
                    $this->viewContent = str_replace($yield, '', $this->viewContent);
                }
            }

            preg_match_all('@<!doctype html>.+?</html>@mis', $this->viewContent, $matches);
            if (count($matches[0]) > 1)
            {
                if (!empty($matches[0]))
                {
                    $this->viewContent = str_replace($matches[0][0], '', $this->viewContent);
                }
            }
        }
    }

    protected function printEntities()
    {
        preg_match_all('~{{\s*(.+?)\s*}}~si', $this->viewContent, $matches);
        if (!empty($matches[1]))
        {
            foreach ($matches[1] as $key => $match)
            {
                $this->viewContent = str_replace($matches[0][$key], "<?php echo htmlentities($match) ?>", $this->viewContent);
            }
        }
    }

    protected function printRaw()
    {
        preg_match_all('~{!\s*(.+?)\s*!}~si', $this->viewContent, $matches);
        if (!empty($matches[1]))
        {
            foreach ($matches[1] as $key => $match)
            {
                $this->viewContent = str_replace($matches[0][$key], "<?php echo $match; ?>", $this->viewContent);
            }
        }
    }

    protected function ifCondition()
    {
        preg_match_all('~@if\s*\((.+?)\)\s*$~im', $this->viewContent, $matches);
        if (!empty($matches[1]))
        {
            foreach ($matches[1] as $key => $match)
            {
                $this->viewContent = str_replace($matches[0][$key], "<?php if ($match): ?>", $this->viewContent);
            }
        }

        preg_match_all('~@else~im', $this->viewContent, $matches);
        if (!empty($matches[0]))
        {
            foreach ($matches[0] as $key => $match)
            {
                $this->viewContent = str_replace($match, "<?php else: ?>", $this->viewContent);
            }
        }


        preg_match_all('~@endif\s*$~im', $this->viewContent, $matches);
        if (!empty($matches[0]))
        {
            foreach ($matches[0] as $key => $match)
            {
                $this->viewContent = str_replace($match, "<?php endif; ?>", $this->viewContent);
            }
        }
    }

    protected function forLoop()
    {
        preg_match_all('~@for\s*\((.+?)\)\s*$~im', $this->viewContent, $matches);
        if (!empty($matches[1]))
        {
            foreach ($matches[1] as $key => $match)
            {
                $this->viewContent = str_replace($matches[0][$key], "<?php for ($match): ?>", $this->viewContent);
            }
        }

        preg_match_all('~@endfor\s*$~im', $this->viewContent, $matches);
        if (!empty($matches[0]))
        {
            foreach ($matches[0] as $key => $match)
            {
                $this->viewContent = str_replace($match, "<?php endfor; ?>", $this->viewContent);
            }
        }
    }

    protected function whileLoop()
    {
        preg_match_all('~@while\s*\((.+?)\)\s*$~im', $this->viewContent, $matches);

        if (!empty($matches[1]))
        {
            foreach ($matches[1] as $key => $match)
            {
                $this->viewContent = str_replace($matches[0][$key], "<?php while ($match): ?>", $this->viewContent);
            }
        }

        preg_match_all('~@endwhile\s*$~im', $this->viewContent, $matches);

        if (!empty($matches[0]))
        {
            foreach ($matches[0] as $key => $match)
            {
                $this->viewContent = str_replace($match, "<?php endwhile; ?>", $this->viewContent);
            }
        }
    }

    protected function foreachLoop()
    {
        preg_match_all('~@foreach\s*\((.+?)\)\s*$~im', $this->viewContent, $matches);

        if (!empty($matches[1]))
        {
            foreach ($matches[1] as $key => $match)
            {
                $this->viewContent = str_replace($matches[0][$key], "<?php foreach ($match): ?>", $this->viewContent);
            }
        }

        preg_match_all('~@endforeach\s*$~im', $this->viewContent, $matches);

        if (!empty($matches[0]))
        {
            foreach ($matches[0] as $key => $match)
            {
                $this->viewContent = str_replace($match, "<?php endforeach; ?>", $this->viewContent);
            }
        }
    }

    protected function phpExpression()
    {
        preg_match_all('~@php~im', $this->viewContent, $matches);

        if (!empty($matches[0]))
        {
            foreach ($matches[0] as $key => $match)
            {
                $this->viewContent = str_replace($match, "<?php ", $this->viewContent);
            }
        }

        preg_match_all('~@endphp\s*$~im', $this->viewContent, $matches);
        if (!empty($matches[0]))
        {
            foreach ($matches[0] as $key => $match)
            {
                $this->viewContent = str_replace($match, " ?>", $this->viewContent);
            }
        }
    }

}
