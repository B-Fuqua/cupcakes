<?php

//Return true if a $name is valid
function validName($name)
{
    return !empty($name);
}

//Return true if at least one flavor is selected
function validFlavors($flavors)
{
    return !is_null($flavors);
}