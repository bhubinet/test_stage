<?php

/**
 * Part of the Joomla Framework Data Package
 *
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Data;

/**
 * DataSet is a collection class that allows the developer to operate on a set of DataObjects as if they were in a typical PHP array.
 *
 * @since  1.0
 */
class DataSet implements DumpableInterface, \ArrayAccess, \Countable, \Iterator
{
    /**
     * The current position of the iterator.
     *
     * @var    integer
     * @since  1.0
     */
    private $current = false;

    /**
     * The iterator objects.
     *
     * @var    DataObject[]
     * @since  1.0
     */
    private $objects = [];

    /**
     * The class constructor.
     *
     * @param   DataObject[]  $objects  An array of DataObject objects to bind to the data set.
     *
     * @since   1.0
     * @throws  \InvalidArgumentException if an object is not a DataObject.
     */
    public function __construct(array $objects = [])
    {
        // Set the objects.
        $this->initialise($objects);
    }

    /**
     * The magic call method is used to call object methods using the iterator.
     *
     * Example: $array = $objectList->foo('bar');
     *
     * The object list will iterate over its objects and see if each object has a callable 'foo' method.
     * If so, it will pass the argument list and assemble any return values. If an object does not have
     * a callable method no return value is recorded.
     * The keys of the objects and the result array are maintained.
     *
     * @param   string  $method     The name of the method called.
     * @param   array   $arguments  The arguments of the method called.
     *
     * @return  array   An array of values returned by the methods called on the objects in the data set.
     *
     * @since   1.0
     */
    public function __call($method, $arguments = [])
    {
        $return = [];

        // Iterate through the objects.
        foreach ($this->objects as $key => $object) {
            // Create the object callback.
            $callback = [$object, $method];

            // Check if the callback is callable.
            if (\is_callable($callback)) {
                // Call the method for the object.
                $return[$key] = \call_user_func_array($callback, $arguments);
            }
        }

        return $return;
    }

    /**
     * The magic get method is used to get a list of properties from the objects in the data set.
     *
     * Example: $array = $dataSet->foo;
     *
     * This will return a column of the values of the 'foo' property in all the objects
     * (or values determined by custom property setters in the individual DataObject's).
     * The result array will contain an entry for each object in the list (compared to __call which may not).
     * The keys of the objects and the result array are maintained.
     *
     * @param   string  $property  The name of the data property.
     *
     * @return  array  An associative array of the values.
     *
     * @since   1.0
     */
    public function __get($property)
    {
        $return = [];

        // Iterate through the objects.
        foreach ($this->objects as $key => $object) {
            // Get the property.
            $return[$key] = $object->$property;
        }

        return $return;
    }

    /**
     * The magic isset method is used to check the state of an object property using the iterator.
     *
     * Example: $array = isset($objectList->foo);
     *
     * @param   string  $property  The name of the property.
     *
     * @return  boolean  True if the property is set in any of the objects in the data set.
     *
     * @since   1.0
     */
    public function __isset($property)
    {
        $return = [];

        // Iterate through the objects.
        foreach ($this->objects as $object) {
            // Check the property.
            $return[] = isset($object->$property);
        }

        return \in_array(true, $return, true);
    }

    /**
     * The magic set method is used to set an object property using the iterator.
     *
     * Example: $objectList->foo = 'bar';
     *
     * This will set the 'foo' property to 'bar' in all of the objects
     * (or a value determined by custom property setters in the DataObject).
     *
     * @param   string  $property  The name of the property.
     * @param   mixed   $value     The value to give the data property.
     *
     * @return  void
     *
     * @since   1.0
     */
    public function __set($property, $value)
    {
        // Iterate through the objects.
        foreach ($this->objects as $object) {
            // Set the property.
            $object->$property = $value;
        }
    }

    /**
     * The magic unset method is used to unset an object property using the iterator.
     *
     * Example: unset($objectList->foo);
     *
     * This will unset all of the 'foo' properties in the list of DataObject's.
     *
     * @param   string  $property  The name of the property.
     *
     * @return  void
     *
     * @since   1.0
     */
    public function __unset($property)
    {
        // Iterate through the objects.
        foreach ($this->objects as $object) {
            unset($object->$property);
        }
    }

    /**
     * Gets an array of keys, existing in objects
     *
     * @param   string  $type  Selection type 'all' or 'common'
     *
     * @return  array   Array of keys
     *
     * @since   1.2.0
     * @throws  \InvalidArgumentException
     */
    public function getObjectsKeys($type = 'all')
    {
        $keys = null;

        if ($type == 'all') {
            $function = 'array_merge';
        } elseif ($type == 'common') {
            $function = 'array_intersect_key';
        } else {
            throw new \InvalidArgumentException("Unknown selection type: $type");
        }

        foreach ($this->objects as $object) {
            $objectVars = json_decode(json_encode($object), true);

            $keys = ($keys === null) ? $objectVars : $function($keys, $objectVars);
        }

        return array_keys($keys);
    }

    /**
     * Gets all objects as an array
     *
     * @param   boolean  $associative  Option to set return mode: associative or numeric array.
     * @param   string   ...$keys      Unlimited optional property names to extract from objects.
     *
     * @return  array  Returns an array according to defined options.
     *
     * @since   1.2.0
     */
    public function toArray($associative = true, ...$keys)
    {
        if (empty($keys)) {
            $keys = $this->getObjectsKeys();
        }

        $return = [];

        $i = 0;

        foreach ($this->objects as $key => $object) {
            $arrayItem = [];

            $key = ($associative) ? $key : $i++;

            $j = 0;

            foreach ($keys as $property) {
                $propertyKey             = ($associative) ? $property : $j++;
                $arrayItem[$propertyKey] = $object->$property ?? null;
            }

            $return[$key] = $arrayItem;
        }

        return $return;
    }

    /**
     * Gets the number of data objects in the set.
     *
     * @return  integer  The number of objects.
     *
     * @since   1.0
     */
    public function count()
    {
        return \count($this->objects);
    }

    /**
     * Clears the objects in the data set.
     *
     * @return  DataSet  Returns itself to allow chaining.
     *
     * @since   1.0
     */
    public function clear()
    {
        $this->objects = [];
        $this->rewind();

        return $this;
    }

    /**
     * Get the current data object in the set.
     *
     * @return  DataObject|false  The current object, or false if the array is empty or the pointer is beyond the end of the elements.
     *
     * @since   1.0
     */
    public function current()
    {
        return is_scalar($this->current) ? $this->objects[$this->current] : false;
    }

    /**
     * Dumps the data object in the set, recursively if appropriate.
     *
     * @param   integer             $depth   The maximum depth of recursion (default = 3).
     *                                       For example, a depth of 0 will return a stdClass with all the properties in native
     *                                       form. A depth of 1 will recurse into the first level of properties only.
     * @param   ?\SplObjectStorage  $dumped  An array of already serialized objects that is used to avoid infinite loops.
     *
     * @return  array  An associative array of the data objects in the set, dumped as a simple PHP stdClass object.
     *
     * @see     DataObject::dump()
     * @since   1.0
     */
    public function dump($depth = 3, \SplObjectStorage $dumped = null)
    {
        // Check if we should initialise the recursion tracker.
        if ($dumped === null) {
            $dumped = new \SplObjectStorage();
        }

        // Add this object to the dumped stack.
        $dumped->attach($this);

        $objects = [];

        // Make sure that we have not reached our maximum depth.
        if ($depth > 0) {
            // Handle JSON serialization recursively.
            foreach ($this->objects as $key => $object) {
                $objects[$key] = $object->dump($depth, $dumped);
            }
        }

        return $objects;
    }

    /**
     * Gets the data set in a form that can be serialised to JSON format.
     *
     * Note that this method will not return an associative array, otherwise it would be encoded into an object.
     * JSON decoders do not consistently maintain the order of associative keys, whereas they do maintain the order of arrays.
     *
     * @return  array
     *
     * @since   1.0
     */
    public function jsonSerialize()
    {
        $return = [];

        // Iterate through the objects.
        foreach ($this->objects as $object) {
            // Call the method for the object.
            $return[] = $object;
        }

        return $return;
    }

    /**
     * Gets the key of the current object in the iterator.
     *
     * @return  integer|false  The object key on success; false on failure.
     *
     * @since   1.0
     */
    public function key()
    {
        return $this->current;
    }

    /**
     * Gets the array of keys for all the objects in the iterator (emulates array_keys).
     *
     * @return  array  The array of keys
     *
     * @since   1.0
     */
    public function keys()
    {
        return array_keys($this->objects);
    }

    /**
     * Applies a function to every object in the set (emulates array_walk).
     *
     * @param   callable  $funcname  Callback function.
     *
     * @return  boolean
     *
     * @since   1.2.0
     * @throws  \InvalidArgumentException
     */
    public function walk(callable $funcname)
    {
        foreach ($this->objects as $key => $object) {
            $funcname($object, $key);
        }

        return true;
    }

    /**
     * Advances the iterator to the next object in the iterator.
     *
     * @return  void
     *
     * @since   1.0
     */
    public function next()
    {
        // Get the object offsets.
        $keys = $this->keys();

        // Check if _current has been set to false but offsetUnset.
        if ($this->current === false && isset($keys[0])) {
            // This is a special case where offsetUnset was used in a foreach loop and the first element was unset.
            $this->current = $keys[0];
        } else {
            // Get the current key.
            $position = array_search($this->current, $keys);

            // Check if there is an object after the current object.
            if ($position !== false && isset($keys[$position + 1])) {
                // Get the next id.
                $this->current = $keys[$position + 1];
            } else {
                // That was the last object or the internal properties have become corrupted.
                $this->current = null;
            }
        }
    }

    /**
     * Checks whether an offset exists in the iterator.
     *
     * @param   mixed  $offset  The object offset.
     *
     * @return  boolean
     *
     * @since   1.0
     */
    public function offsetExists($offset)
    {
        return isset($this->objects[$offset]);
    }

    /**
     * Gets an offset in the iterator.
     *
     * @param   mixed  $offset  The object offset.
     *
     * @return  DataObject|null
     *
     * @since   1.0
     */
    public function offsetGet($offset)
    {
        return $this->objects[$offset] ?? null;
    }

    /**
     * Sets an offset in the iterator.
     *
     * @param   mixed       $offset  The object offset.
     * @param   DataObject  $object  The object object.
     *
     * @return  void
     *
     * @since   1.0
     * @throws  \InvalidArgumentException if an object is not an instance of DataObject.
     */
    public function offsetSet($offset, $object)
    {
        if (!($object instanceof DataObject)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The $object argument must be an instance of "%s", a %s was given.',
                    DataObject::class,
                    \gettype($object) === 'object' ? \get_class($object) : \gettype($object)
                )
            );
        }

        // Set the offset.
        if ($offset === null) {
            $this->objects[] = $object;
        } else {
            $this->objects[$offset] = $object;
        }
    }

    /**
     * Unsets an offset in the iterator.
     *
     * @param   mixed  $offset  The object offset.
     *
     * @return  void
     *
     * @since   1.0
     */
    public function offsetUnset($offset)
    {
        if (!isset($this[$offset])) {
            // Do nothing if the offset does not exist.
            return;
        }

        // Check for special handling of unsetting the current position.
        if ($offset == $this->current) {
            // Get the current position.
            $keys     = $this->keys();
            $position = array_search($this->current, $keys);

            // Check if there is an object before the current object.
            if ($position > 0) {
                // Move the current position back one.
                $this->current = $keys[$position - 1];
            } else {
                // We are at the start of the keys AND let's assume we are in a foreach loop and `next` is going to be called.
                $this->current = false;
            }
        }

        unset($this->objects[$offset]);
    }

    /**
     * Rewinds the iterator to the first object.
     *
     * @return  void
     *
     * @since   1.0
     */
    public function rewind()
    {
        // Set the current position to the first object.
        if (empty($this->objects)) {
            $this->current = false;
        } else {
            $keys          = $this->keys();
            $this->current = array_shift($keys);
        }
    }

    /**
     * Validates the iterator.
     *
     * @return  boolean
     *
     * @since   1.0
     */
    public function valid()
    {
        // Check the current position.
        if (!is_scalar($this->current) || !isset($this->objects[$this->current])) {
            return false;
        }

        return true;
    }

    /**
     * Initialises the list with an array of objects.
     *
     * @param   array  $input  An array of objects.
     *
     * @return  void
     *
     * @since   1.0
     * @throws  \InvalidArgumentException if an object is not a DataObject.
     */
    private function initialise(array $input = [])
    {
        foreach ($input as $key => $object) {
            if ($object !== null) {
                $this[$key] = $object;
            }
        }

        $this->rewind();
    }
}
