<?php
/**
 * 
 * @author Miloslav Kmet <miloslav.kmet@gmail.com>
 */
class stValidatorGalleryFiles extends sfValidatorFile
{
    /**
     * @var string List of unzipped files to be removed on shutdown
     */
    public $tempFiles = array();
    
    /**
     * Configures the current validator
     * 
     * @param array $options An array of options
     * @param array $messages An array of messages
     * 
     * @see sfValidatorFile::configure
     */
    protected function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);
        
        $this->setOption('mime_types', array(
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/x-png',
            'image/gif',
            'application/zip',
        ));
        
        $this->addMessage('cant_open_zip', 'Failed to open a zip file.');
        $this->addMessage('no_images_found', 'No images found in a zip file.');
    }
    
    
    public function doClean($value)
    {
        $value = parent::doClean($value);
        
        // if not a zip file, return file as array
        if ($value->getType() !== 'application/zip')
        {
            return array($value);
        }
        
        // unzip and check files
        $images = array();
        $class = $this->getOption('validated_file_class');
        
        $zip = new ZipArchive;
        if (!$zip->open($value->getTempName()))
        {
            throw new sfValidatorError($this, 'cant_open_zip');
        }
    
        // register cleaner of unzipped files
        register_shutdown_function(array($this, 'shutdown'));
        
        $mimeTypes = $this->getOption('mime_types');
        if ($index = array_search('application/zip', $mimeTypes))
        {
            unset($mimeTypes[$index]);
        }
        
        for ($i=0; $i < $zip->numFiles; $i++)
        {
            $stat = $zip->statIndex($i);
            
            if ($stat['size'] > 0 && in_array(strtolower(strrchr($stat['name'], '.')), array('.png', '.jpeg', '.jpg', '.gif')))
            {
                $tempFile = tempnam(sys_get_temp_dir(), 'stGal');
                $this->tempFiles[] = $tempFile;
                $fpTmp = fopen($tempFile, 'w+');
                $fpZip = $zip->getStream($stat['name']);
                while(!feof($fpZip))
                {
                    fwrite($fpTmp, fread($fpZip, 4096));
                }
                fclose($fpZip);
                fclose($fpTmp);
                
                $mimeType = $this->getMimeType($tempFile, 'application/data');

                if (in_array($mimeType, array_map('strtolower', $mimeTypes)))
                {
                    $images[] = new $class(basename($stat['name']), $mimeType, $tempFile, filesize($tempFile), $this->getOption('path'));
                }
                else
                {
                    unlink($tempFile);
                    array_pop($this->tempFiles);
                }
            }
        }

        $zip->close();
        
        if (count($images) === 0)
        {
            throw new sfValidatorError($this, 'no_images_found');
        }

        return $images;
    }

    /**
     * Clears all temporary files created during upload
     * 
     * @return void
     */
    public function shutdown()
    {
        foreach ($this->tempFiles as $i => $tempFile)
        {
            if (is_file($tempFile))
            {
                unlink($tempFile);
                unset($this->tempFiles[$i]);
            }
        }
    }
}