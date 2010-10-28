<?php

class stGalleryRouteCollection extends sfRouteCollection
{
    protected
        $routeClass = 'stGalleryDoctrineRoute';
        
    /**
     * Constructor.
     * 
     * @param array $options An array of options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);
        
        if (!isset($this->options['model']))
        {
            throw new InvalidArgumentException(sprintf('You must pass a "model" option to %s ("%s" route)', get_class($this), $this->options['name']));
        }
        
        $this->options = sfToolkit::arrayDeepMerge(array(
            'actions' => false,
            'module'  => $this->options['name'],
            'prefix_path' => '/'.$this->options['name'],
            'column'  => isset($this->options['column']) ? $this->options['column'] : 'id',
            'model_methods' => array(),
            'requirements' => array(),
            'segment_names' => array(
                'edit'   => 'edit', 
                'upload' => 'upload', 
                'delete' => 'delete',
                'load'   => 'load',
                'update' => 'update'
            ),
            'default_params' => array(),
        ), $this->options);
        
        $this->options['requirements'] = array_merge(array($this->options['column'] => 'id' == $this->options['column'] ? '\d+' : null), $this->options['requirements']);
        $this->options['model_methods'] = array_merge(array('list' => null, 'object' => null), $this->options['model_methods']);
        
        if (isset($this->options['route_class']))
        {
            $this->routeClass = $this->options['route_class'];
        }
        
        $this->generateRoutes();
    }
    
    /**
     * Generate routes necessary for administration of gallery
     */
    protected function generateRoutes()
    {
        // collection actions
        // standard actions
        
        // object actions
        $actions = array('edit', 'save', 'upload', 'delete', 'load', 'update');
        foreach ($actions as $action)
        {
            $method = 'getRouteFor'.ucfirst($action);
            if (!method_exists($this, $method))
            {
                throw new InvalidArgumentException(sprintf('Unable to generate a route for the "%s" action.', $action));
            }
            
            $this->routes[$this->getRoute($action)] = $this->$method();
        }
    }
    
    protected function getRouteForEdit()
    {
        return new $this->routeClass(
            sprintf('%s/:%s/%s.:sf_format', $this->options['prefix_path'], $this->options['column'], $this->options['segment_names']['edit']),
            array_merge(array('module' => $this->options['module'], 'action' => $this->getActionMethod('edit'), 'sf_format' => 'html'), $this->options['default_params']),
            array_merge($this->options['requirements'], array('sf_method' => 'get')),
            array('model' => $this->options['model'], 'type' => 'object', 'method' => $this->options['model_methods']['object'], 'base_route_name' => $this->options['name'])
        );
    }

    protected function getRouteForSave()
    {
        return new $this->routeClass(
            sprintf('%s/:%s/%s.:sf_format', $this->options['prefix_path'], $this->options['column'], $this->options['segment_names']['edit']),
            array_merge(array('module' => $this->options['module'], 'action' => $this->getActionMethod('save'), 'sf_format' => 'html'), $this->options['default_params']),
            array_merge($this->options['requirements'], array('sf_method' => 'post')),
            array('model' => $this->options['model'], 'type' => 'object', 'method' => $this->options['model_methods']['object'], 'base_route_name' => $this->options['name'])
        );
    }
    
    protected function getRouteForUpload()
    {
        return new $this->routeClass(
            sprintf('%s/:%s/%s.:sf_format', $this->options['prefix_path'], $this->options['column'], $this->options['segment_names']['upload']),
            array_merge(array('module' => $this->options['module'], 'action' => 'uploadPictures', 'sf_format' => 'html'), $this->options['default_params']),
            array_merge($this->options['requirements'], array('sf_method' => 'post')),
            array('model' => $this->options['model'], 'type' => 'object', 'method' => $this->options['model_methods']['object'], 'base_route_name' => $this->options['name'])
        );
    }

    protected function getRouteForLoad()
    {
        return new $this->routeClass(
            sprintf('%s/:%s/%s/:picture.:sf_format', $this->options['prefix_path'], $this->options['column'], $this->options['segment_names']['load']),
            array_merge(array('module' => $this->options['module'], 'action' => 'loadPicture', 'sf_format' => 'html'), $this->options['default_params']),
            array_merge($this->options['requirements'], array('sf_method' => 'get', 'picture' => '^\d+$')),
            array('model' => $this->options['model'], 'type' => 'object', 'method' => $this->options['model_methods']['object'], 'base_route_name' => $this->options['name'])
        );
    }

    protected function getRouteForDelete()
    {
        return new $this->routeClass(
            sprintf('%s/:%s/%s/:picture.:sf_format', $this->options['prefix_path'], $this->options['column'], $this->options['segment_names']['delete']),
            array_merge(array('module' => $this->options['module'], 'action'=> 'deletePicture', 'sf_format' => 'html'), $this->options['default_params']),
            array_merge($this->options['requirements'], array('sf_method' => 'get', 'picture' => '^\d+$')),
            array('model' => $this->options['model'], 'type' => 'object', 'method' => $this->options['model_methods']['object'], 'base_route_name' => $this->options['name'])
        );
    }

    protected function getRouteForUpdate()
    {
        return new $this->routeClass(
            sprintf('%s/:%s/%s/:picture.:sf_format', $this->options['prefix_path'], $this->options['column'], $this->options['segment_names']['update']),
            array_merge(array('module' => $this->options['module'], 'action'=> 'updatePicture', 'sf_format' => 'html'), $this->options['default_params']),
            array_merge($this->options['requirements'], array('sf_method' => 'post')),
            array('model' => $this->options['model'], 'type' => 'object', 'method' => $this->options['model_methods']['object'], 'base_route_name' => $this->options['name'])
        );
    }

    protected function getRouteForObject($action, $methods)
    {
        return new $this->routeClass(
            sprintf('%s/:%s/%s.:sf_format', $this->options['prefix_path'], $this->options['column'], $action),
            array_merge(array('module' => $this->options['module'], 'action' => $action, 'sf_format' => 'html'), $this->options['default_params']),
            array_merge($this->options['requirements'], array('sf_method' => $methods)),
            array('model' => $this->options['model'], 'type' => 'object', 'method' => $this->options['model_methods']['object'], 'base_route' => $this->options['name'])
        );
    }

    protected function getRoute($action)
    {
        return 'edit' == $action ? $this->options['name'] : $this->options['name'].'_'.$action;
    }

    protected function getActionMethod($action)
    {
        return 'list' == $action ? 'index' : $action;
    }
}