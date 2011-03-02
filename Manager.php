<?php

/*
 * ExtPHP - ExtJS PHP Wrapper
 * 
 * (c) David Mann <ninja@codingninja.com.au>
 *
 * This file is part of the ExtPHP library.
 * For the full license. Please see the license file bundled
 * with the source code
 */

namespace PHPJs;

use \PHPJs\Component;
use \PHPJs\Manager\Storage as Storage;
use \PHPJs\Config\ConfigInterface as Config;
use \PHPJs\Converter\VariableConverter;

/**
 * Base Output Manager Class
 *
 * Base class to extend when creating a manager class.
 *
 * @package     PHPJs
 * @subpackage  Manager
 * @author      David Mann <ninja@codingninja.com.au>
 * @copyright   David Mann
 * @abstract
 */
abstract class Manager
{
    protected $outputWrapper = false;
    
    protected $components = array ();
    
    protected $rendered = array ();
    
    protected $name = '';
    
    /**
     * Constructor
     *
     * @param mixed $name The name of the manager
     */
    public function __construct($name, $register = true) {
        if ($register) {
            Storage::register ( $name, $this );
        }
        $this->name = $name;
    }
    
    /**
     * Get the namagers name
     *
     * @return string name of the manager
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set the name of the component
     *
     * @param string $name The name of the component
     * @return Manager The current manager instance for a fluid interface
     */
    public function setName($name) {
        $this->resetRender ()->name = $name;
        
        return $this;
    }
    
    /**
     * Reset the renderedcache
     *
     * @return Manager The current manager instance for a fluid interface
     */
    public function resetRender() {
        $this->rendered = array ();
        
        return $this;
    }
    
    /**
     * Render's this managers items.
     *
     * @return string The rendered output
     */
    abstract public function render();
    
    /**
     * Manager::getOutputWrapper()
     *
     * Return's the "complete" output wrapper which will wrap all of the rendered managed items.
     *
     * @return string A {@link sprintf} valid string for wrapping the rendered managed items
     */
    public function getOutputWrapper() {
        return $this->outputWrapper;
    }
    
    /**
     * Set the output wrapper
     *
     * Set's the output wrapper to a valid {@link sprintf} string that will wrap
     * the complete rendered output
     *
     * @param string $wrapper The wrapper for the output
     * @throws \InvalidArgumentException thrown when the $wrapper passed in is not a {@link sprintf} compatible string
     * @return Manager The current manager instance for a fluid interface
     */
    public function setOutputWrapper($wrapper) {
        if (! strstr ( $wrapper, '%s' )) {
            throw new \InvalidArgumentException ( sprintf ( 'Output wrapper must contain "%%s". Supplied OutputWrapper:<br /><pre>"%s</pre><br /> did not.', htmlspecialchars ( $wrapper ) ) );
        }
        
        $this->resetRender ()->outputWrapper = $wrapper;
        
        return $this;
    }
    
    /**
     * Render a component
     *
     * Renders a component if it is not already rendered <em>As this is a
     * dependency managed container you cannot re-render a component and you should
     * not output a single component</em>
     *
     * @param string The component name
     * @return string|null The rendered component or null if it is already rendered
     */
    public function renderComponent($component) {
        if (isset ( $this->rendered [$component->getUuid ()] )) {
            return;
        }
        
        return $this->rendered [$component->getUuid ()] = new VariableConverter ( $component->getUuid (), $component->render (), false ) . "\r\n";
    }
    
    /**
     * Register a Component
     *
     * Registers a new {@link Component} for the manager
     *
     * @param mixed $component
     * @return
     */
    public function register(Renderable $component) {
        $this->resetRender ()->components [] = $component;
        return $this;
    }
    
    /**
     * Find a component by *
     *
     * Get a component by $ref. Can be one of name or uuid.
     *
     * @param string $ref The refrence key
     * @param string $value The name or uuid of the component
     * @return Component|null The component that was found or null
     */
    public function getComponentBy($ref, $value) {
        $methods = array (
            'name' => 'getName', 
            'uuid' => 'getUuid' 
        );
        
        if (! isset ( $methods [$ref] )) {
            throw new \InvalidArgumentException ( sprintf ( 'Invalid refrence method supplied "%s". Must be one of "%s"', $ref, var_export ( array_keys ( $methods ), true ) ) );
        }
        
        foreach ( $this->components as $component ) {
            if ($component->$method () == $value) {
                return $component;
            }
        }
        
        return null;
    }
    
    /**
     * Create an XType component
     *
     * Proxy method to the {@link Manager::ComponentFactory()} method to include the "{@link Component::XTYPE}" switch
     *
     * @param string $xtype The XType to use when rendering the component
     * @param array $config The configuration for the component
     * @return Component The component that was created
     */
    public function componentFromXType($xtype, $config) {
        return $this->componentFactory ( $xtype, $config, Component::XTYPE );
    }
    
    /**
     * Create an Object component
     *
     * Proxy method to the {@link Manager::ComponentFactory()} method to include the "{@link Component::OBJ}" switch
     *
     * @param string $name The name of the class to create
     * @param array $config The confuration for the component
     * @return Component The component that was created
     */
    public function componentFromClassName($name, $config) {
        return $this->componentFactory ( $name, $config, Component::OBJ );
    }
    
    /**
     * Create a new component
     *
     * Create a new component and register it to the current manager for dependency and output management
     *
     * @param string $class The name of the component (The Xtype or Class name depending on the $type)
     * @param array $config The configuration for the component
     * @param constant $type The render type
     * @return Component
     */
    public function componentFactory($class, $config, $type) {
        if (! is_array ( $config ) && (! ($config instanceof Config))) {
            throw new \InvalidArgumentException ( 'The configuration you supplied was not a valid array of configuration class.' );
        }
        
        $coponentClass = $this->getComponentClassFor ( $class );
        $component = new $coponentClass ( $class, $config, $type );
        $this->register ( $component );
        
        return $component;
    }
    
    /**
     * Create or get manager
     *
     * Static method to allow simple creation of {@link Manager}s. If the manager
     * exists, it returns that one.
     *
     * To use, call from the manager class you wish to create.
     * Example
     * <pre>
     * \PHPJs\Manager\Generic::application(); // Create it
     * \PHPJs\Manager\Generic::application(); // Retrieve it
     * </pre>
     *
     * @param string $func The name of the manager to create
     * @param array $args The arguments
     * @return Manager The manager instance created
     */
    public static function __callStatic($func, $args) {
        if(substr($func, 0, 3) !== 'get') {
            throw new \InvalidArgumentException(sprintf('Invalid method call to %s::%s', __NAMESPACE__ . '\\' . get_called_class(), $func));
        }   
        
        $func = strtolower(substr($func, 3));
             
        if (! Storage::has ( $func )) {
            Storage::create ( $func, get_called_class () );
        }
        
        return Storage::get ( $func );
    }
    
    public function __toString() {
        return $this->render ();
    }
    
    public abstract function getComponentClassFor($class);
}
