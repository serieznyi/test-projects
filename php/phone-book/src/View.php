<?php

namespace App;

use RuntimeException;

/**
 * Class View
 * @package App
 */
class View
{
    /**
     * @var string real path to views root directory
     */
    private $viewDir;
    /**
     * @var string layout name for use
     */
    private $layoutName;
    /**
     * @var array
     */
    private $params;

    /**
     * @var array Vars defined in view file
     */
    public $vars = [];

    /**
     * View constructor.
     * @param string $viewDir
     * @param string $layoutName
     * @param array $params Global parameters
     */
    public function __construct($viewDir, $layoutName, $params)
    {
        if (!file_exists($viewDir) || !is_dir($viewDir)) {
            throw new RuntimeException("View dir not found or not is dir: $viewDir");
        }

        $this->viewDir = $viewDir;

        $this->findViewFile($layoutName);

        $this->layoutName = $layoutName;
        $this->params = $params;
    }

    /**
     * @param string $viewName relative path to view
     * @param array $params params for pass in view
     * @return string view string
     * @throws \Throwable
     */
    public function render($viewName, $params = [])
    {
        $pageRealPath = $this->findViewFile($viewName);

        if (!file_exists($pageRealPath)) {
            throw new RuntimeException("View not found: $pageRealPath");
        }

        $allParams = array_merge($this->params, $params);

        $content = $this->renderPhpFile($pageRealPath, $allParams);

        $allParams = array_merge($allParams, $this->vars);

        $layoutRealPath = $this->findViewFile($this->layoutName);

        return $this->renderPhpFile($layoutRealPath, array_merge($allParams, ['content' => $content]));
    }

    /**
     * @param string $relativeFileName
     * @return string absolute file name
     */
    private function findViewFile($relativeFileName) {
        $path = rtrim($this->viewDir, '/') . DIRECTORY_SEPARATOR . $relativeFileName . '.php';

        if (!file_exists($path)) {
            throw new RuntimeException("View file not found: $relativeFileName");
        }

        return $path;
    }

    /**
     * Renders a view file as a PHP script.
     *
     * @param string $file the view file.
     * @param array $params the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @return string the rendering result
     * @throws \Exception
     * @throws \Throwable
     */
    private function renderPhpFile($file, $params = [])
    {
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        $context = $this;
        try {
            require $file;
            return ob_get_clean();
        } catch (\Exception $e) {
            ob_clean();
            throw $e;
        } catch (\Throwable $e) {
            ob_clean();
            throw $e;
        }
    }
}