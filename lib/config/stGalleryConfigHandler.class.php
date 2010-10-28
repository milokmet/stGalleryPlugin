<?php

class stGalleryConfigHandler extends sfDefineEnvironmentConfigHandler
{
    public function execute($configFiles)
    {
        return parent::execute($configFiles);
    }
    
    
    // app_st_gallery_ default $thumbnail
    protected function getValues($prefix, $category, $keys)
    {
        if (!is_array($keys))
        {
            list($key, $value) = $this->fixCategoryValue($prefix.strtolower($category), '', $keys);
            
            return array($key => $value);
        }
        
        $values = array();
        
        $category = $this->fixCategoryName($category, $prefix);
        
        foreach ($keys as $key => $value)
        {
            list($key, $value) = $this->fixCategoryValue($category, $key, $value);
            
            if (is_array($value))
            {
                foreach ($value as $kk => $vv)
                {
                    list($kkk, $vvv) = $this->fixCategoryValue($key.'_', $kk, $vv);
                    $values[$kkk] = $vvv;
                }
            }
            else
            {
                $values[$key] = $value;
            }
        }
       
        return $values;
    }
}