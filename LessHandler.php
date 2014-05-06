<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'less.php/lessc.inc.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LessHandler
 *
 * @author Bachir
 */
class LessHandler {

    private $CI;
    private $config;

    public function __construct()
    {
        $this->CI     = &get_instance();
        $this->CI->config->load('less_parser', TRUE);
        $this->config = $this->CI->config->item('less_parser');

        try {
            $this->Parser();
        } catch (exception $e) {
            echo "fatal error: " . $e->getMessage() . '- Line :' . $e->getLine();
        }
    }

    private function Parser()
    {
        $outputFile = $this->config['output_file'];
        $inputFiles = $this->GetLessFiles();
        if ($this->IsInputNewer($outputFile, $inputFiles))
            $this->GenerateCSS($inputFiles);
    }

    /**
     * Compile les fichiers less et génère un fichier css dans le repertoire de sortie
     */
    private function GenerateCSS($inputFiles)
    {
        $parser = $this->AddFiles($inputFiles);
        $css    = $parser->getCSS();
        file_put_contents($this->config['output_file'], $css);
    }

    /**
     * Check if input files are newer than output files
     * 
     * @return boolean
     */
    private function IsInputNewer($outputFile, $inputFiles)
    {
        $isNewer = TRUE;

        if ($outputFile == '') {
            throw new Exception('Fichier de sortie non renseigné');
        } elseif (file_exists($outputFile)) {
            $isNewer          = FALSE;
            $timestamp        = filemtime($outputFile); // Output modification time

            for ($i = 0; (!$isNewer) && $i < count($inputFiles); $i++) {
                if (filemtime($inputFiles[$i]) > $timestamp)
                    $isNewer = TRUE;
            }
        }

        return $isNewer;
    }

    /**
     * Get all *.less files 
     * 
     * @return array List of all *.less files
     */
    private function GetLessFiles()
    {
        $less_files  = array();
        $directories = $this->config['input_directories'];

        // Set absolute paths
        for ($i = 0; $i < count($directories); $i++) {
            $directories[$i] = FCPATH . $directories[$i];
        }

        // Search for .less files through directories 
        foreach ($directories as $path) {
            if (is_dir($path)) {
                $less_files[] = glob($path . "\*.less");
            } elseif (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) == 'less') {
                $less_files[] = $path;
            }
        }

        if (count($less_files) == 0)
            throw new Exception('Erreur sur la lecture des fichiers d\'entrée');

        // Return flat array of all files
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($less_files));
        return iterator_to_array($iterator, false);
    }

    
    /**
     * Add less files to parser object and return it
     * @param array $inputFiles Less Files
     * @return Less_Parser Parser Instance
     */
    private function AddFiles($inputFiles)
    {
        $parser = new Less_Parser();
        foreach ($inputFiles as $file) {
            $parser->parseFile($file);
            log_message('info', 'Ajout du fichier ' . $file . ' au compilateur LESS.PHP');
        }
        return $parser;
    }

}
