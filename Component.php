<?php

/*
 * PHPJs - Javascript PHP Wrapper
 * 
 * (c) David Mann <ninja@codingninja.com.au>
 *
 * This file is part of the PHPJs library.
 * For the full license. Please see the license file bundled
 * with the source code
 */

namespace PHPJs;

use \PHPJs\Converter\LiteralConverter;
use \PHPJs\Converter\VariableConverter;

/**
 * Base Component Class
 *
 * This class represents a complex javascript type. It is like the {@link \PHPJs\Converter} on steroids
 * and is more specifically tailored towards objects which contain large amounts of code such as CustomFunctions,
 * Objects, Event Handlers, Arrays, Multiple Instances, Etc, etc.
 *
 * Usage Example:
 * <pre>
 * class myComponent extends Component {
 *
 * protected $className = 'My.Component';
 *
 * protected $options = array(
 * 'title' => 'My Component'
 * );
 * }
 * </pre>
 *
 * And when used
 *
 * <code>
 * Storage::dashboard()->register(new myComponent(array('width' => 200, 'height'=>200)));
 * </code>
 *
 * @package     PHPJs
 * @subpackage  Manager
 * @author      David Mann <ninja@codingninja.com.au>
 * @copyright   David Mann
 */
abstract class Component implements Renderable
{
    
    const JSON = 0;
    
    const OBJ = 1;
    
    protected $types = array (
        self::JSON => 'json', 
        self::OBJ => 'object' 
    );
    
    protected $dependencies = array ();
    
    protected $options = array ();
    
    protected $requiredOptions = array ();
    
    public static $optionFilters = array ();
    
    /**
     * Constructor
     *
     * Options:
     * * This class supports no options as it is only a base clas meant for extending.
     *
     * @param string $name    The name of the component to create
     * @param array $config   The configuration to use on the component
     * @param constant $type  The render method for the component
     */
    public function __construct($name, $config = array(), $type = self::OBJ) {
        $config = (array) $this->filterConfig($config);
        
        $this->configure ( $config );
        
        if (! (is_array ( $config ))) {
            throw new \InvalidArgumentException ( sprintf ( 'Unable to load passed configuration of type "%s"', gettype ( $config ) ) );
        }
        
        $this->configure ();
        
        $this->setOptions ( $config );
        
        if ($diff = array_diff ( $this->requiredOptions, array_merge ( array_keys ( array_merge ( $this->options, $config ) ), array_keys ( $config ) ) )) {
            throw new \InvalidArgumentException( sprintf ( '%s requires the following options: \'%s\'.', get_class ( $this ), implode ( '\', \'', $diff ) ) );
        }
        
        $this->setType ( $type )->setName ( $name );
        $this->initialize ();
    }
    
    /**
     * Get the component config
     *
     * Return's the component's configuration data
     *
     * @return array The configuration for this component
     */
    public function getOptions() {
        $requiredKeys = $this->requiredOptions;
        $data = array_diff_key ( $this->options, array_combine ( $requiredKeys, array_fill ( 0, count ( $requiredKeys ), null ) ) );
        
        return $data;
    }
    
    /**
     * Add Required Options
     *
     * Allows you to get all the required options for this component
     *
     * @return array The required options as a key=>value array.
     * @deprecated
     */
    public function getRequiredOptions() {
        throw new \BadMethodCallException('Method "\PHPJs\PHPJs::getRequiredOptions" is deprecated');
    }
    
    /**
     * Add options
     *
     * Add multiple options
     *
     * @param array $optios The option names to add
     * @return Component The current component for a fluent interface
     */    
    public function addOptions(array $data) {
        array_map(array($this, 'addOption'), $data);
        
        return $this;
    }
    
    /**
     * Remove an option
     *
     * Remove the option
     *
     * @param string $key The option key to remove
     * @return Component The current component for a fluent interface
     */    
    public function removeOption($key) {
        unset($this->options[$key]);
        return $this;
    }
    
    /**
     * Component::addOption()
     *
     * @param mixed $key
     * @param mixed $default
     * @return
     */
    public function addOption($key, $default = null) {
        $this->options [$key] = Converter::getConverter ( $default );
        
        return $this;
    }
    
    /**
     * Component::addRequiredOption()
     *
     * @param mixed $key
     * @return
     */
    public function addRequiredOption($key) {
        $this->requiredOptions [] = $key;
        
        return $this;
    }
    
    /**
     * Set the config
     *
     * Sets the configuration on the current component to the specified $config
     *
     * @param array $config The configuration to use on the component
     * @return Component The current component for a fluent interface
     */
    public function setOptions($config) {
        if (! is_array ( $config )) {
            return $this;
        }
        
        $invalid = array ();
        foreach ( $config as $key => $value ) {
            try {
                $this->setOption ( $key, $value );
            } catch ( \InvalidArgumentException $e ) {
                $invalid [] = $key;
            }
        }
        if (count ( $invalid ) > 0) {
            throw new \InvalidArgumentException ( sprintf ( 'Options "%s" are not supported. Only "%s" are supported.', implode ( ', ', $invalid ), print_r ( $this->options, true ) ) );
        }
        
        return $this;
    }
    
    /**
     * Set an option
     *
     * Set's an option on the main configuration object
     *
     * @param string $key The key of the configuration option
     * @param mixed $value The configuration value
     * @return Component The current component for a fluid interface
     */
    public function setOption($key, $value) {
        if (! array_key_exists ( $key, $this->options )) {
            throw new \InvalidArgumentException ( sprintf ( 'Option "%s" is not a valid option.', $key ) );
        }
        if ($value instanceof Component) {
            $value->setType ( self::JSON );
        }
        $this->options [$key] = Converter::getConverter ( $value );
        
        return $this;
    }
    
    public abstract function configure();
    
    /**
     * Initialize a component
     *
     * @return null
     */
    public function initialize() {
    
    }
    
    /**
     * Get render types
     *
     * Get all the registered render methods for the component
     *
     * @return array The array of render types
     */
    public function getTypes() {
        return array (
            self::JSON, 
            self::OBJ 
        );
    }
    
    /**
     * Get the type of the current object
     *
     * @return constant The current render type
     */
    public function getType() {
        return $this->type;
    }
    
    /**
     * Set render type
     *
     * Set's the render method for the current component to $type
     *
     * @param constant $type The render type to use
     * @see {@link self::getTypes()}
     * @return Component The current component instance for fluid interface
     */
    public function setType($type) {
        if (! in_array ( $type, $this->getTypes () )) {
            throw new \InvalidArgumentException ( sprintf ( 'Type "%s" is not valid.', $type ) );
        }
        
        $this->type = $type;
        
        return $this;
    }
    
    /**
     * Get name
     *
     * Return's the current components class or XType
     *
     * @return string the Class
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set name
     *
     * Set's the name of the current component instance to the $name
     *
     * @param string $name The name of the component
     * @return Component The current component instance for a fluid interface
     */
    public function setName($name) {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Get Uuid
     *
     * Get the UUID of the current component
     *
     * @return string The component hash
     */
    public function getUuid() {
        $hash = md5 ( spl_object_hash ( $this ) );
        $hash = 'component_' . substr ( $hash, 0, 6 );
        
        return $hash;
    }
    
    /**
     * Component to string
     *
     * Proxy function to the {@link Component::render()} method.
     *
     * @return string
     */
    public function __tostring() {
        return $this->render ();
    }
    
    /**
     * Render the component
     *
     * Renders the current component based on the render {@link self::$type}
     *
     * @return string The rendered component
     */
    public function render() {
        $typeName = $this->types [$this->type];
        $func = 'renderFor' . ucfirst ( $typeName );
        
        return $this->$func ();
    }
    
    /**
     * Render as Javascript Hash
     *
     * Render the current component as a javascript hash object. Optional parameter
     * allows you to set the "xtype" key to {@link self::$name} before render,
     *
     * @param bool $includeXType Include the name as the "xtype" config value?
     * @return string The rendered hash
     */
    public function renderForJson($includeXType = false) {
        $config = $this->options;
        $data = array ();
        
        if ($includeXType) {
      $config['xtype'] = new \PHPJs\Converter\
            StringConverter ( $this->getName () );
        }
        
        foreach ( $config as $name => $item ) {
            $value = $item;
            if ($item instanceof Component) {
                $value = new Literal ( $item->getUuid () );
            }
            
            $data [] = sprintf ( "  \"%s\": %s", $name, $value );
        }
        
        return "{\n" . implode ( ",\n", $data ) . "\n}";
    }
    
    /**
     * Component::renderForObject()
     *
     * @return
     */
    public function renderForObject() {
        return sprintf ( "new %s(%s)", $this->getName (), $this->renderForJson () );
    }
    
    /**
     * Component::getDependencies()
     *
     * @param bool $refresh
     * @return
     */
    public function getDependencies($refresh = false) {
        if (! $this->dependencies || $refresh) {
            $depends = array ();
            foreach ( $this->options as $name => $data ) {
                if ($data instanceof Component) {
                    $depends [] = $data;
                } elseif (is_callable ( array (
                    $data, 
                    'getDependencies' 
                ) )) {
                    $depends = array_merge ( $depends, $data->getDependencies () );
                }
            }
            $this->dependencies = $depends;
        }
        return $this->dependencies;
    }
    
    /**
     * Component::hasDependencies()
     *
     * @param bool $refresh
     * @return
     */
    public function hasDependencies($refresh = false) {
        return count ( $this->getDependencies ( $refresh ) ) > 0;
    }
    
    public function filterConfig($config) {
        if(!$config) {
            return $config;
        }
        
        foreach(static::$optionFilters as $filter) {
            $config = $filter->filter($config, $this);
        }
        
        return $config;
    }
}
