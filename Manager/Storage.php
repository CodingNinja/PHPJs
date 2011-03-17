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
namespace PHPJs\Manager;

use \PHPJs\Manager;

/**
 * Output Manager Storage Class
 *
 * This class is used to wrap multiple output managers into a single area with
 * required dependencies. These dependencies are automatic and all registered
 * managers will have their dependencies setup and managed.
 *
 * @package     PHPJs
 * @subpackage  Manager
 * @author      David Mann <ninja@codingninja.com.au>
 * @copyright   David Mann
 */
class Storage
{

  protected static $managers = array();

  /**
   * Get a Manager
   *
   * Get a {@link \PHPJs\Manager} by name if it exists
   *
   * @param string $class The name of the manager to ge
   * @return Manager|false The manager instance or void if not found
   */
  public static function get($class)
  {
    if (!self::has($class))
    {
      return;
    }

    return self::$managers[$class];
  }

  /**
   * Does a manager exist
   *
   * Checks whether a {@link \PHPJs\Manager} exists by $name
   *
   * @param mixed $name The name of the {@link \PHPJs\Manager}
   * @return Whether or not the {@link \PHPJs\Manager} exists
   */
  public static function has($name)
  {
    return isset(self::$managers[$name]);
  }

  /**
   * Registers a {@link \PHPJs\Manager}
   *
   * Registers a {@link \PHPJs\Manager} class that will be managed by the global storage manager
   *
   * @param string $name The unique name of the manager
   * @param \PHPJs\Manager $manager The manager instance
   * @return The registered {@link \PHPJs\Manager}
   */
  public function register($name, Manager $manager)
  {
    return self::$managers[$name] = $manager;
  }

  /**
   * Create a manager
   *
   * Create a manager by class, type and also optionally automagically register it
   *
   * @param string $name The name of the manager
   * @param string $type The class of manager to create. Must be fully namespaced class name ("\PHPJs\Manager\Generic")
   * @param bool $register Automatically register the manager with the global storage manager?
   * @return The created manager instance
   */
  public function create($name, $type, $register = true)
  {
    if (self::has($name))
    {
      return self::get($name);
    }

    $manager = new $type($name);
    if ($register)
    {
      self::register($name, $manager);
    }

    return $manager;
  }

  /**
   * Register a manager
   *
   * Register a manager with the global storage manager
   *
   * @return Manager The registered manager
   */
  public static function registerManager(Manager $manager)
  {
    return self::$managers[$manager->getName()] = $manager;
  }

  /**
   * Get manager by *
   *
   * Allows simple retrieval of manager by name or uuid
   *
   * Example:
   * <pre>
   *  Storage::getManagerBy('name', 'application');
   *  Storage::getManagerBy('uuid', 'm1018801');
   * </pre>
   *
   * @param string $ref   The refrence to use, can be one of "name" or "uuid"
   * @param string $value The value to check against
   * @return Manager|null The manager that was found or null if nothing was found
   */
  public static function getManagerBy($ref, $value)
  {
    $validRefs = array('name' => 'getName', 'uuid' => 'getUuid');

    if (!in_array($ref, $validRefs))
    {
      throw new \InvalidArgumentException(sprintf('Unable to assertain how to retrieve an object with a refrence of "%s"',
        $ref));
    }

    $checkValue = $validRefs[$ref];
    foreach (self::$managers as $manager)
    {
      $managerValue = call_user_func(sprintf('%s::%s', get_class($manager), $function));
      if ($managerValue == $checkValue)
      {
        return $manager;
      }
    }

    return null;
  }

  /**
   * Output Managers
   *
   * Output's each of the managers in order they were registered
   *
   * @return string The output managers
   */
  public static function output($managerBeforeAfter = array())
  {
    $output = '';
    foreach (self::$managers as $manager)
    {
      $before = $after = '';
      if (isset($managerBeforeAfter[$manager->getName()]))
      {
        $beforeAfter = $managerBeforeAfter[$manager->getName()];
        if (isset($beforeAfter['before']))
        {
          $before = $beforeAfter['before'];
        } elseif (isset($beforeAfter['after']))
        {
          $after = $beforeAfter['after'];
        }
      }
      
      $output .= sprintf("\n// Auto-Generated Code\n// Date: %s\n// Manager: %s \n%s\n",
        date('d/m/Y'), $manager->getName(), $manager->render($before, $after));
    }
    return sprintf('<script type="text/javascript">%s</script>'."\n", $output);
  }

}